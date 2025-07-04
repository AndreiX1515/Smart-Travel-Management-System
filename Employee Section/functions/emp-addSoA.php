<?php
require "../../conn.php";
session_start();

// Validate and sanitize inputs
$flightDate = (isset($_POST['flightDate']) && $_POST['flightDate'] !== "Select Flight Date" && $_POST['flightDate'] !== "")
    ? $_POST['flightDate'] : null;

$monthInput = (isset($_POST['month']) && $_POST['month'] !== "") ? $_POST['month'] : null;
$year = (isset($_POST['year']) && $_POST['year'] !== "") ? $_POST['year'] : date('Y');
$currentDate = isset($_POST['currentDate']) ? $_POST['currentDate'] : date('Y-m-d');
$accountId = isset($_POST['accountId']) ? (int)$_POST['accountId'] : null;

if (!$accountId) {
    echo json_encode(['error' => 'Missing or invalid accountId']);
    exit;
}

$month = $monthInput ? date('m', strtotime($monthInput)) : null;
$dateGenerated = date('Y-m-d H:i:s');

$conn->begin_transaction();

// Fetch last SOA number
$sqlLast = "SELECT MAX(id) AS lastSoAId FROM soa";
$resultLast = $conn->query($sqlLast);

if (!$resultLast) {
    $conn->rollback();
    echo json_encode(['error' => "Error fetching last SOA number."]);
    exit;
}

$row = $resultLast->fetch_assoc();
$newSoANo = ($row && $row['lastSoAId'] !== null) ? $row['lastSoAId'] + 1 : 1;
$formattedCounter = str_pad($newSoANo, 5, '0', STR_PAD_LEFT);
$soaNo = 'SMT-' . $formattedCounter;

// Insert new SOA record
$sqlInsert = "INSERT INTO soa (soaNo, branchId, month, flightId, dateGenerated, status)
              VALUES (?, ?, ?, ?, ?, ?)";
$stmtInsert = $conn->prepare($sqlInsert);

if (!$stmtInsert) {
    $conn->rollback();
    echo json_encode(['error' => "Error preparing SQL statement."]);
    exit;
}

$status = 'Partially Paid';
$stmtInsert->bind_param('siisss', $soaNo, $accountId, $month, $flightDate, $dateGenerated, $status);

if ($stmtInsert->execute()) {
    $conn->commit();
    echo json_encode(['soanum' => $soaNo]);
} else {
    $conn->rollback();
    echo json_encode(['error' => "Error inserting SOA number."]);
}

$stmtInsert->close();
$conn->close();
?>
