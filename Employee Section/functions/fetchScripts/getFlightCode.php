<?php
require '../../../conn.php';

// Query distinct flightCode for departure flights (not return flights)
$sql = "SELECT DISTINCT flightCode FROM flight WHERE is_active = 1 ORDER BY flightCode ASC";
$result = $conn->query($sql);

$flightCodes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $flightCodes[] = $row['flightCode'];
    }
}

echo json_encode($flightCodes);

$conn->close();
?>
