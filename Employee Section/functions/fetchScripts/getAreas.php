<?php
header('Content-Type: application/json');
require_once '../../../conn copy.php';

try {
    $stmt = $conn->prepare("SELECT DISTINCT areaId, areaName FROM itinerarydataarea ORDER BY areaName ASC");
    $stmt->execute();
    $areas = $stmt->fetchAll(PDO::FETCH_ASSOC); // fetch both areaId and areaName

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
