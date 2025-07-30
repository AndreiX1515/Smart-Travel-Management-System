<?php
require '../../../conn.php';
header('Content-Type: application/json');

$data = ['hotels' => []];

// Fetch hotels with the corresponding area they belong to
$sqlHotels = "
    SELECT 
        h.hotelId, 
        h.hotelName, 
        a.areaId
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
    echo json_encode(['status' => 'success', 'data' => $data['hotels']]);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch hotels']);
}

$conn->close();
