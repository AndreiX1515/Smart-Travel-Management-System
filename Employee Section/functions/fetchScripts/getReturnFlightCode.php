<?php
require '../../../conn.php';

$flightCode = $_GET['flightCode'] ?? '';

$returnFlightCode = '';

if ($flightCode) {
    $stmt = $conn->prepare("SELECT returnFlightCode FROM flight WHERE flightCode = ? AND is_active = 1 LIMIT 1");
    $stmt->bind_param("s", $flightCode);
    $stmt->execute();
    $stmt->bind_result($returnFlightCode);
    $stmt->fetch();
    $stmt->close();
}

echo json_encode(['returnFlightCode' => $returnFlightCode]);

$conn->close();
?>
