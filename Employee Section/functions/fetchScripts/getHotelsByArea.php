<?php
header('Content-Type: application/json');
require_once '../../../conn copy.php';

try {
    if (!isset($conn) || !$conn) {
        throw new Exception("❌ Database connection is not established.");
    }

    $sql = "
        SELECT 
            ida.areaId, ida.areaName, h.hotelId, h.hotelName
        FROM itinerarydatahotels idh
        INNER JOIN hotels h ON idh.hotelId = h.hotelId
        INNER JOIN itinerarydataarea ida ON idh.areaId = ida.areaId
        ORDER BY ida.areaId, h.hotelName
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$results || count($results) === 0) {
        throw new Exception("⚠️ No results found from the hotel query.");
    }

    $grouped = [];

    foreach ($results as $row) {
        $areaId = $row['areaId'];
        $hotelId = $row['hotelId'];
        $hotelName = $row['hotelName'];

        if (!isset($grouped[$areaId])) {
            $grouped[$areaId] = [];
        }

        // Prevent duplicates
        if (!array_filter($grouped[$areaId], fn($h) => $h['hotelId'] == $hotelId)) {
            $grouped[$areaId][] = [
                'hotelId' => $hotelId,
                'hotelName' => $hotelName
            ];
        }
    }

    echo json_encode([
        'status' => 'success',
        'data' => $grouped
    ]);

} catch (PDOException $pdoEx) {
    echo json_encode([
        'status' => 'error',
        'type' => 'PDOException',
        'message' => $pdoEx->getMessage()
    ]);
    
} catch (Exception $ex) {
    echo json_encode([
        'status' => 'error',
        'type' => 'GeneralException',
        'message' => $ex->getMessage()
    ]);
}
