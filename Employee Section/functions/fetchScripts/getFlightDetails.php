<?php
require '../../../conn.php';

$flightCode = $_GET['flightCode'] ?? '';

if ($flightCode) {
    $stmt = $conn->prepare("SELECT 
        origin, 
        flightDepartureDate, 
        flightDepartureTime, 
        flightArrivalDate, 
        flightArrivalTime,
        returnFlightName,
        returnFlightCode,
        returnDepartureDate,
        returnDepartureTime,
        returnArrivalDate,
        returnArrivalTime
        FROM flight 
        WHERE flightCode = ? AND is_active = 1 LIMIT 1");
    $stmt->bind_param("s", $flightCode);
    $stmt->execute();
    $result = $stmt->get_result();

    $flightData = $result->fetch_assoc() ?: [];

    echo json_encode($flightData);

    $stmt->close();
} else {
    echo json_encode([]);
}

$conn->close();
?>
