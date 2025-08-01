<?php
require '../../../conn.php'; // Adjust path as necessary

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT DISTINCT activityName FROM itineraryactivities");
    $stmt->execute();
    $result = $stmt->get_result();
    $activities = [];

    while ($row = $result->fetch_assoc()) {
        $activities[] = $row['activityName'];
    }

    echo json_encode([
        "success" => true,
        "data" => $activities
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
