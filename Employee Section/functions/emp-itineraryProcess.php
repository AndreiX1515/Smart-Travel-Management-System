<?php
require('../../conn.php');  

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure that the itinerary ID is provided
if (!isset($_POST['itineraryId'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid Itinerary ID']);
    exit();
}

$itineraryId = intval($_POST['itineraryId']); // Ensure it's an integer

// Fetch itinerary basic details
$sql = "SELECT itineraryName, noOfDays, packageName, periodStart, periodEnd, guideName, countryCode, contactNumber, city1, hotel1, city2, hotel2, city3, hotel3 FROM itineraries
        WHERE itineraryId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $itineraryId);
$stmt->execute();
$result = $stmt->get_result();

// If no rows are returned, send a failure message
if (!$row = $result->fetch_assoc()) {
    echo json_encode(['success' => false, 'message' => 'Itinerary not found']);
    exit();
}

// Itinerary details (First JSON)
$itineraryDetails = [
    'itineraryId' => $itineraryId,
    'itineraryName' => $row['itineraryName'],
    'noOfDays' => $row['noOfDays'],
    'packageName' => $row['packageName'],
    'periodStart' => $row['periodStart'],
    'periodEnd' => $row['periodEnd'],
    'guideName' => $row['guideName'],
    'countryCode' => $row['countryCode'],
    'contactNumber' => $row['contactNumber'],
    'cities' => [
        ['city' => $row['city1'], 'hotel' => $row['hotel1']],
        ['city' => $row['city2'], 'hotel' => $row['hotel2']],
        ['city' => $row['city3'], 'hotel' => $row['hotel3']]
    ]
];

// Fetch day-wise details (Second JSON)
$sqlDays = "
    SELECT 
        d.dayId, 
        d.dayNumber, 
        COALESCE(a.areas, '') AS areas,
        COALESCE(h.hotels, '') AS hotels,
        COALESCE(act.activities, '') AS activities,
        COALESCE(mp.meals, '') AS meals
    FROM itinerarydays d
    LEFT JOIN (
        SELECT dayId, GROUP_CONCAT(DISTINCT areaName ORDER BY itineraryAreaId ASC SEPARATOR ', ') AS areas
        FROM itineraryareas 
        GROUP BY dayId
    ) a ON d.dayId = a.dayId
    LEFT JOIN (
        SELECT dayId, GROUP_CONCAT(DISTINCT hotelName ORDER BY hotelId ASC SEPARATOR ', ') AS hotels
        FROM itineraryhotels 
        GROUP BY dayId
    ) h ON d.dayId = h.dayId
    LEFT JOIN (
        SELECT dayId, GROUP_CONCAT(activityName ORDER BY activityId ASC SEPARATOR ', ') AS activities
        FROM itineraryactivities 
        GROUP BY dayId
    ) act ON d.dayId = act.dayId
    LEFT JOIN (
        SELECT dayId, GROUP_CONCAT(DISTINCT mealPlan ORDER BY mealId ASC SEPARATOR ', ') AS meals
        FROM itinerarymealplans 
        GROUP BY dayId
    ) mp ON d.dayId = mp.dayId
    WHERE d.itineraryId = ?
    GROUP BY d.dayId, d.dayNumber
    ORDER BY d.dayNumber ASC;
";

$stmt = $conn->prepare($sqlDays);
$stmt->bind_param("i", $itineraryId);
$stmt->execute();
$result = $stmt->get_result();

$daysDetails = [];
while ($day = $result->fetch_assoc()) {
    $areas = $day['areas'] ? explode(',', $day['areas']) : [];
    $hotels = $day['hotels'] ? explode(',', $day['hotels']) : [];
    $meals = $day['meals'] ? explode(',', $day['meals']) : [];

    // Store day data in daysDetails array
    $daysDetails[] = [
        'day' => $day['dayNumber'],
        'areas' => $areas,
        'hotels' => $hotels,
        'activities' => $day['activities'] ? explode(',', $day['activities']) : [],
        'meals' => $meals
    ];
}

// Convert to JSON (Separate JSONs for itinerary and days)
$itineraryJson = json_encode($itineraryDetails);
$daysJson = json_encode($daysDetails);

// Return success response with data
echo json_encode([
    'success' => true,
    'message' => 'Itinerary details fetched successfully.',
    'itineraryDetails' => $itineraryDetails,
    'daysDetails' => $daysDetails
]);

?>
