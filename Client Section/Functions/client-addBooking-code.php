<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../../conn.php"; // Move up to the parent directory

header('Content-Type: application/json');
$response = ["status" => "error", "message" => "Invalid request."];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$accountId = $_SESSION['client_accountId'] ?? null;

	if (!$accountId) {
		echo json_encode(["status" => "error", "message" => "Session expired. Please log in again."]);
		exit;
	}

	$agentId = 'Client';
	$agentCode = $_POST['agentCode'] ?? '';
	$fName = $_POST['fName'] ?? '';
	$mName = $_POST['mName'] ?? '';
	$lName = $_POST['lName'] ?? '';
	$suffix = $_POST['suffix'] ?? '';
	$countryCode = $_POST['countryCode'] ?? '';
	$contactNo = $_POST['contactNo'] ?? '';
	$email = $_POST['email'] ?? '';
	$packageId = $_POST['packageId'] ?? '';
	$flightId = $_POST['flightDate'] ?? '';
	$totalPax = $_POST['totalPax'] ?? 1;
	$infantPax = $_POST['infantPax'];
	$totalPrice = $_POST['totalPrice'] ?? 0;
	$bookingType = isset($_POST['land']) ? 'Land' : 'Package';
	$flightDetails = ($bookingType === 'Land') ? ($_POST['flightDetails'] ?? NULL) : NULL;

	// Get the last bookingId and increment it
	$result = $conn->query("SELECT MAX(bookingId) AS lastBookingId FROM booking");
	if (!$result) {
		echo json_encode(["status" => "error", "message" => "Error fetching last booking ID: " . $conn->error]);
		exit;
	}

	$row = $result->fetch_assoc();
	$newBookingId = ($row && $row['lastBookingId'] !== null) ? $row['lastBookingId'] + 1 : 1;
	$formattedCounter = str_pad($newBookingId, 6, '0', STR_PAD_LEFT);
	$transactNo = $agentCode . '-' . $formattedCounter;

	// Handle "Own Flight" selection
	$flightId = ($flightId === 'Null') ? NULL : $flightId;

	// Set session variable for MySQL
	$conn->query("SET @current_user_id = $accountId");

	// Start transaction
	$conn->begin_transaction();

	try 
	{
		// Prepare SQL statement
		$sql1 = "INSERT INTO booking (accountId, transactNo, accountType, agentCode, flightId, packageId, fName, lName, mName, suffix, countryCode, 
								contactNo, email, pax, infantPax, totalPrice, bookingType, flightDetails, status, bookingDate) VALUES 
								(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Reserved', NOW())";
		$stmt1 = $conn->prepare($sql1);

		if (!$stmt1) {
			throw new Exception("Booking SQL preparation failed: " . $conn->error);
		}

		// Bind and execute the booking insertion
		$stmt1->bind_param('isssiisssssssiidss', $accountId, $transactNo, $agentId, $agentCode, $flightId, $packageId, $fName, $lName, $mName, 
				$suffix, $countryCode, $contactNo, $email, $totalPax, $infantPax, $totalPrice, $bookingType, $flightDetails);

		if (!$stmt1->execute()) {
			throw new Exception("Database error on booking insert: " . $stmt1->error);
		}

		// Commit transaction
		$conn->commit();

		// Send email notification (commented out)
		/*
		$subject = "Booking Confirmation - " . $transactNo;
		$message = "Dear $fName $lName,\n\nThank you for booking with us.\n\nYour transaction number is: $transactNo.\n\nWe look forward to serving you!\n\nBest regards,\nTravel Team";
		$headers = "From: no-reply@yourdomain.com";

		if (!mail($email, $subject, $message, $headers)) {
				// Log email failure or handle accordingly
		}
		*/

		echo json_encode(["status" => "success", "message" => "Booking successful!", "transactNo" => $transactNo]);
		exit;
	} catch (Exception $e) {
		$conn->rollback();
		echo json_encode(["status" => "error", "message" => $e->getMessage()]);
		exit;
	}
}

echo json_encode($response);
exit;
?>
