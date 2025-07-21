<?php
// Set content type to JSON and disable output buffering
header('Content-Type: application/json');


// Import DB connection
require_once '../../../conn copy.php'; // Adjust path if needed

try {
    $stmt = $conn->prepare("SELECT DISTINCT areaName FROM itinerarydataarea ORDER BY areaName ASC");
    $stmt->execute();
    $areas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($areas)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No areas found.'
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'data' => $areas
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'General error: ' . $e->getMessage()
    ]);
}
