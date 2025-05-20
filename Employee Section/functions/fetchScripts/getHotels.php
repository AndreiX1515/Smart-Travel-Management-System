<?php
require '../../../conn.php';

header('Content-Type: application/json');

$data = [
    'cities' => [],
    'hotels' => []
];

// Get distinct cities
$sqlCities = "SELECT DISTINCT hotelCity FROM hotel ORDER BY hotelCity";
$resultCities = $conn->query($sqlCities);

if ($resultCities) {
    while ($row = $resultCities->fetch_assoc()) {
        $data['cities'][] = $row['hotelCity'];
    }
    $resultCities->free();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch cities']);
    $conn->close();
    exit();
}

// Get all hotels (hotelId, hotelName, hotelCity)
$sqlHotels = "SELECT hotelId, hotelName, hotelCity FROM hotel ORDER BY hotelCity, hotelName";
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
