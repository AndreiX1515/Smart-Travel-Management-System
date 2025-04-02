<?php

require_once('../../conn.php'); 

// Prevent unwanted output before PDF generation
ob_start();

// Set JSON response headers
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

// Validate Itinerary ID
if (!isset($_POST['itineraryId']) || !is_numeric($_POST['itineraryId'])) {
    echo json_encode(['error' => 'Invalid Itinerary ID']);
    exit;
}

$itineraryId = intval($_POST['itineraryId']);
error_log("Received Itinerary ID: " . $itineraryId); // Debugging log

if (!$conn) {
    echo json_encode(['error' => 'Database connection error']);
    exit;
}

// Fetch itinerary details
$sql = "SELECT itineraryName, noOfDays, packageName, periodStart, periodEnd, guideName, countryCode, contactNumber, city1, hotel1, city2, hotel2, city3, hotel3 
        FROM itineraries WHERE itineraryId = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'SQL error: ' . $conn->error]);
    exit;
}
$stmt->bind_param("i", $itineraryId);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    echo json_encode(['error' => 'Itinerary not found']);
    exit;
}

// Store fetched itinerary details
$itineraryName = $row['itineraryName'];
$noOfDays = $row['noOfDays'];
$packageName = $row['packageName'];
$periodStart = $row['periodStart'];
$periodEnd = $row['periodEnd'];
$guideName = $row['guideName'];
$contactNumber = "+" . $row['countryCode'] . " " . $row['contactNumber'];
$cities = array_filter([
    ['city' => $row['city1'], 'hotel' => $row['hotel1']],
    ['city' => $row['city2'], 'hotel' => $row['hotel2']],
    ['city' => $row['city3'], 'hotel' => $row['hotel3']]
], fn($c) => !empty($c['city']));

// Fetch itinerary days, areas, hotels, activities, and meals
$sqlDays = "
    SELECT 
        d.dayId, d.dayNumber, 
        COALESCE(a.areas, '') AS areas,
        COALESCE(h.hotels, '') AS hotels,
        COALESCE(act.activities, '') AS activities,
        COALESCE(mp.meals, '') AS meals
    FROM itinerarydays d
    LEFT JOIN (SELECT dayId, GROUP_CONCAT(DISTINCT areaName ORDER BY itineraryAreaId ASC SEPARATOR ', ') AS areas FROM itineraryareas GROUP BY dayId) a ON d.dayId = a.dayId
    LEFT JOIN (SELECT dayId, GROUP_CONCAT(DISTINCT hotelName ORDER BY hotelId ASC SEPARATOR ', ') AS hotels FROM itineraryhotels GROUP BY dayId) h ON d.dayId = h.dayId
    LEFT JOIN (SELECT dayId, GROUP_CONCAT(activityName ORDER BY activityId ASC SEPARATOR ', ') AS activities FROM itineraryactivities GROUP BY dayId) act ON d.dayId = act.dayId
    LEFT JOIN (SELECT dayId, GROUP_CONCAT(DISTINCT mealPlan ORDER BY mealId ASC SEPARATOR ', ') AS meals FROM itinerarymealplans GROUP BY dayId) mp ON d.dayId = mp.dayId
    WHERE d.itineraryId = ?
    ORDER BY d.dayNumber ASC;
";

$stmt = $conn->prepare($sqlDays);
$stmt->bind_param("i", $itineraryId);
$stmt->execute();
$result = $stmt->get_result();

$itineraryDays = [];
while ($day = $result->fetch_assoc()) {
    $itineraryDays[] = [
        'day' => $day['dayNumber'],
        'areas' => $day['areas'] ? explode(', ', $day['areas']) : [],
        'hotels' => $day['hotels'] ? explode(', ', $day['hotels']) : [],
        'activities' => $day['activities'] ? explode(', ', $day['activities']) : [],
        'meals' => $day['meals'] ? explode(', ', $day['meals']) : []
    ];
}

// Clean any buffered output to avoid "Some data has already been output" error
ob_end_clean();




?>