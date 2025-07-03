<?php
require_once '../../../conn copy.php';
header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("
        SELECT 
            ida.areaName,
            h.hotelId,
            h.hotelName
        FROM itinerarydatahotels idh
        INNER JOIN hotels h ON idh.hotelId = h.hotelId
        INNER JOIN itinerarydataarea ida ON idh.areaId = ida.areaId
        ORDER BY ida.areaName, h.hotelName
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $grouped = [];

    foreach ($results as $row) {
        $area = $row['areaName'];
        if (!isset($grouped[$area])) {
            $grouped[$area] = [];
        }

        // Prevent duplicates
        if (!in_array($row['hotelId'], array_column($grouped[$area], 'hotelId'))) {
            $grouped[$area][] = [
                'hotelId' => $row['hotelId'],
                'hotelName' => $row['hotelName']
            ];
        }
    }

    echo json_encode([
        'status' => 'success',
        'data' => $grouped
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
