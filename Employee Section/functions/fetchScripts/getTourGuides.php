<?php
require_once '../../../conn.php';

// Make sure accountId exists in your table or comes from a proper join
$query = "SELECT accountId, fName, mName, lName, countryCode, contactNo FROM employee WHERE isTourGuide = 1";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    'status' => 'success',
    'data' => $data
]);
?>
