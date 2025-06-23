<?php
require '../../../conn.php';

header('Content-Type: application/json');

$data = [
    'cities' => [],
    'hotels' => []
];

// Get all distinct area names (to use as "cities")
$sqlCities = "SELECT DISTINCT areaName FROM itinerarydataarea ORDER BY areaName";
$resultCities = $conn->query($sqlCities);

if ($resultCities) {
    while ($row = $resultCities->fetch_assoc()) {
        $data['cities'][] = $row['areaName'];
    }
    $resultCities->free();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch areas']);
    $conn->close();
    exit();
}

// Get all hotels with area they belong to (via junction table)
$sqlHotels = "
    SELECT 
        h.hotelId, 
        h.hotelName, 
        a.areaName AS hotelCity
    FROM hotels h
    JOIN itinerarydatahotels ih ON ih.hotelId = h.hotelId
    JOIN itinerarydataarea a ON a.areaId = ih.areaId
    ORDER BY a.areaName, h.hotelName
";
$resultHotels = $conn->query($sqlHotels);

if ($resultHotels) {
    while ($row = $resultHotels->fetch_assoc()) {
        $data['hotels'][] = $row;
    }
    $resultHotels->free();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch hotels']);
    $conn->close();
    exit();
}

$conn->close();
echo json_encode($data);
