<?php
require "../../conn.php";
session_start();

	// Validate and sanitize inputs
	$flightDate = (isset($_POST['flight-filter']) && $_POST['flight-filter'] !== "Select Flight Date" && $_POST['flight-filter'] !== "") 
			? $_POST['flight-filter'] : null;

	$monthInput = (isset($_POST['month-filter']) && $_POST['month-filter'] !== "")
    ? $_POST['month-filter']
    : null;

	$year = (isset($_POST['year-filter']) && $_POST['year-filter'] !== "")
    ? $_POST['year-filter']
    : date('Y'); // fallback to current year
	$currentDate = isset($_POST['currentDate']) ? $_POST['currentDate'] : null;


$month = date('m', strtotime($monthInput));
$dateGenerated = date('Y-m-d H:i:s');

$conn->begin_transaction();

// Fetch last SOA number
$sql5 = "SELECT MAX(id) AS lastSoAId FROM soa";
$result5 = $conn->query($sql5);

if (!$result5) {
    $conn->rollback();
    echo json_encode(['error' => "Error fetching last SOA number."]);
    exit;
}

$row = $result5->fetch_assoc();
$newSoANo = ($row && $row['lastSoAId'] !== null) ? $row['lastSoAId'] + 1 : 1;
$formattedCounter = str_pad($newSoANo, 5, '0', STR_PAD_LEFT);
$soaNo = 'SMT-' . $formattedCounter;

// Insert SOA with flightId
$sql6 = "INSERT INTO soa (soaNo, branchId, month, flightId, dateGenerated, status)
         VALUES (?, ?, ?, ?, ?, ?)";
$stmt6 = $conn->prepare($sql6);

if (!$stmt6) {
    $conn->rollback();
    echo json_encode(['error' => "Error preparing SQL statement."]);
    exit;
}

$status = 'Partially Paid';
$stmt6->bind_param('siisss', $soaNo, $accountId, $month, $flightDate, $dateGenerated, $status);

if ($stmt6->execute()) {
    $conn->commit();
    echo json_encode(['soanum' => $soaNo]);
} else {
    $conn->rollback();
    echo json_encode(['error' => "Error inserting SOA number."]);
}

$stmt6->close();
$conn->close();
?>
