<?php
require '../../../conn copy.php';

$stmt = $conn->prepare("SELECT includesId, itemName FROM voucherincludeoptions ORDER BY includesId ASC");
$stmt->execute();
$options = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($options);
?>
