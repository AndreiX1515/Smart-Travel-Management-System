<?php

header('Content-Type: application/json');

// // Enable full error reporting for debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


// Import DB connection
require_once '../../../conn copy.php';

try {
    if (!isset($conn) || !$conn) {
        throw new Exception("❌ Database connection is not established.");
    }

    // Prepare the SQL query
    $sql = "
        SELECT 
            ida.areaName, h.hotelId, h.hotelName
        FROM itinerarydatahotels idh
        INNER JOIN hotels h ON idh.hotelId = h.hotelId
        INNER JOIN itinerarydataarea ida ON idh.areaId = ida.areaId
        ORDER BY ida.areaName, h.hotelName
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("❌ Failed to prepare SQL statement.");
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$results || count($results) === 0) {
        throw new Exception("⚠️ No results found from the hotel query.");
    }

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
