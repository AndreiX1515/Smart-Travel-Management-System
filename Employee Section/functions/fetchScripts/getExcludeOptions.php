<?php
require '../../../conn copy.php'; // adjust path as needed

$stmt = $conn->query("SELECT excludesId, itemName FROM voucherexcludeoptions ORDER BY excludesId ASC");
$options = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($options);
