<?php
require_once '../../../conn copy.php';
header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("
        SELECT 
            ida.areaName, h.hotelId, h.hotelName
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
        $hotelId = $row['hotelId'];
        $hotelName = $row['hotelName'];

        if (!isset($grouped[$area])) {
            $grouped[$area] = [];
        }

        // Avoid duplicates based on hotelId
        if (!array_filter($grouped[$area], fn($h) => $h['hotelId'] == $hotelId)) {
            $grouped[$area][] = [
                'hotelId' => $hotelId,
                'hotelName' => $hotelName
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
