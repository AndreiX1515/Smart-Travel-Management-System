<?php
require '../../../conn.php';

header('Content-Type: application/json');

$data = [
    'cities' => [],
    'hotels' => []
];

// Fetch all unique area IDs and names (used as "cities")
$sqlCities = "SELECT DISTINCT areaId, areaName FROM itinerarydataarea ORDER BY areaName";
$resultCities = $conn->query($sqlCities);

if ($resultCities) {
    while ($row = $resultCities->fetch_assoc()) {
        $data['cities'][] = [
            'areaId' => $row['areaId'],
            'areaName' => $row['areaName']
        ];
    }
    $resultCities->free();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch areas']);
    $conn->close();
    exit();
}

// Fetch hotels with the corresponding area they belong to
$sqlHotels = "
    SELECT 
        h.hotelId, 
        h.hotelName, 
        a.areaId,
        a.areaName
    FROM hotels h
    JOIN itinerarydatahotels ih ON ih.hotelId = h.hotelId
    JOIN itinerarydataarea a ON a.areaId = ih.areaId
    ORDER BY a.areaName, h.hotelName
";
$resultHotels = $conn->query($sqlHotels);

if ($resultHotels) {
    while ($row = $resultHotels->fetch_assoc()) {
        $data['hotels'][] = [
            'hotelId' => $row['hotelId'],
            'hotelName' => $row['hotelName'],
            'areaId' => $row['areaId']
        ];
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
