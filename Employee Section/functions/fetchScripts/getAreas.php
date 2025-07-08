<?php
require_once '../../../conn copy.php';
header('Content-Type: application/json');

try {
    // Fetch areaName as a flat array
    $stmt = $conn->prepare("SELECT areaName FROM itinerarydataarea ORDER BY areaName ASC");
    $stmt->execute();

    $areaNames = $stmt->fetchAll(PDO::FETCH_COLUMN); // Returns: ["Incheon", "Jeju", "Seoul", ...]

    echo json_encode([
        'status' => 'success',
        'data' => $areaNames
    ]);
} catch (Exception $e) {
    // Handle and return any error in JSON
    echo json_encode([
        'status' => 'error',
        'message' => 'Error fetching areas: ' . $e->getMessage()
    ]);
}
