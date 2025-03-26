<?php
require_once '../../conn.php'; // Include database connection

header('Content-Type: application/json');

// Get itineraryId from request safely
$itineraryId = isset($_GET['itinerary_id']) ? (int) $_GET['itinerary_id'] : 0;

if ($itineraryId === 0) {
    echo json_encode(["error" => "Invalid itinerary ID"]);
    exit;
}

// SQL query to fetch itinerary details grouped by sections
$sql = "
SELECT 'Itinerary' AS section, 
       i.itineraryId, 
       NULL AS dayId, 
       NULL AS relatedId, 
       i.itineraryName AS relatedName, 
       i.noOfDays AS extraInfo, 
       i.packageName AS details, 
       i.createdAt
FROM itineraries i
WHERE i.itineraryId = ?

UNION ALL

SELECT 'Day' AS section, 
       d.itineraryId, 
       d.dayId, 
       NULL AS relatedId, 
       CONCAT('Day ', d.dayNumber) AS relatedName, 
       NULL AS extraInfo, 
       NULL AS details, 
       d.createdAt
FROM itinerarydays d
WHERE d.itineraryId = ?

UNION ALL

SELECT 'Area' AS section, 
       d.itineraryId, 
       a.dayId, 
       a.itineraryAreaId AS relatedId, 
       a.areaName AS relatedName, 
       NULL AS extraInfo, 
       NULL AS details, 
       a.created_at AS createdAt
FROM itineraryareas a
JOIN itinerarydays d ON a.dayId = d.dayId
WHERE d.itineraryId = ?

UNION ALL

SELECT 'Hotel' AS section, 
       d.itineraryId, 
       h.dayId, 
       h.hotelId AS relatedId, 
       h.hotelName AS relatedName, 
       NULL AS extraInfo, 
       NULL AS details, 
       h.createdAt
FROM itineraryhotels h
JOIN itinerarydays d ON h.dayId = d.dayId
WHERE d.itineraryId = ?

UNION ALL

SELECT 'Meal Plan' AS section, 
       d.itineraryId, 
       m.dayId, 
       m.mealId AS relatedId, 
       m.mealPlan AS relatedName, 
       NULL AS extraInfo, 
       NULL AS details, 
       m.createdAt
FROM itinerarymealplans m
JOIN itinerarydays d ON m.dayId = d.dayId
WHERE d.itineraryId = ?

UNION ALL

SELECT 'Activity' AS section, 
       d.itineraryId, 
       act.dayId, 
       act.activityId AS relatedId, 
       act.activityName AS relatedName, 
       NULL AS extraInfo, 
       NULL AS details, 
       act.createdAt
FROM itineraryactivities act
JOIN itinerarydays d ON act.dayId = d.dayId
WHERE d.itineraryId = ?

ORDER BY createdAt, FIELD(section, 'Itinerary', 'Day', 'Area', 'Hotel', 'Meal Plan', 'Activity');
";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiiii", $itineraryId, $itineraryId, $itineraryId, $itineraryId, $itineraryId, $itineraryId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch and format results
$itineraryData = [];
while ($row = $result->fetch_assoc()) {
    $itineraryData[] = $row;
}

$stmt->close();
$conn->close();

// Output JSON response
echo json_encode($itineraryData);
?>
