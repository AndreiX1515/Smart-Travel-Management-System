<?php
require "../../conn.php"; // DB connection
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
  // Get all input arrays from POST
  $employeeIds = $_POST['employeeId'] ?? [];
  $packageIds = $_POST['packageId'] ?? [];
  $origins = $_POST['origin'] ?? [];
  $departureCodes = $_POST['departureFlightCode'] ?? [];
  $departureDates = $_POST['departureDate'] ?? [];
  $returnCodes = $_POST['returnFlightCode'] ?? [];
  $returnDates = $_POST['returnDate'] ?? [];
  $wholesalePrices = $_POST['wholesalePrice'] ?? [];
  $flightPrices = $_POST['flightPrice'] ?? [];
  $availSeats  = $_POST['availSeats'] ?? [];

  $rowCount = count($employeeIds);
  $errors = [];
  $inserted = 0;
  $insertedIds = [];
  $hasError = false;

  // Begin transaction
  $conn->begin_transaction();

  // Prepare statement
  $stmt = $conn->prepare("INSERT INTO flight (
      packageId, employeeId, origin, flightName, flightCode, flightDepartureDate, flightDepartureTime,
      flightArrivalDate, flightArrivalTime, returnFlightName, returnFlightCode, returnDepartureDate, 
      returnDepartureTime, returnArrivalDate, returnArrivalTime, wholesalePrice, flightPrice, availSeats
    ) VALUES (
      ?, ?, ?, ?, ?, ?, '05:45:00', ?, '10:45:00', ?, ?, ?, '12:45:00', ?, '04:00:00', ?, ?, ?
    )");

  if (!$stmt) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
      'status' => 'error',
      'message' => 'Failed to prepare SQL statement.',
      'details' => $conn->error
    ]);
    exit;
  }

  for ($i = 0; $i < $rowCount; $i++) 
  {
    // Validate required fields
    if (
      empty($employeeIds[$i]) || empty($packageIds[$i]) || empty($origins[$i]) ||
      empty($departureCodes[$i]) || empty($departureDates[$i]) || empty($returnCodes[$i]) ||
      empty($returnDates[$i]) || $wholesalePrices[$i] === '' || $flightPrices[$i] === '' || $availSeats[$i] === ''
    ) {
      $errors[] = "Missing required fields on row $i.";
      $hasError = true;
      break;
    }

    // Assign and sanitize values
    $employeeId = $employeeIds[$i];
    $packageId = $packageIds[$i];
    $origin = $origins[$i];
    $departureCode = $departureCodes[$i];
    $departureDate = $departureDates[$i];
    $returnCode = $returnCodes[$i];
    $returnDate = $returnDates[$i];
    $wholesalePrice = floatval($wholesalePrices[$i]);
    $flightPrice = floatval($flightPrices[$i]);
    $seats = intval($availSeats[$i]);

    // Derived names
    $flightName = "$origin - DEST";
    $returnName = "DEST - $origin";

    // Bind and execute
    $stmt->bind_param('sssssssssssddi',
      $packageId, $employeeId, $origin, $flightName, $departureCode, $departureDate,
      $departureDate, $returnName, $returnCode, $returnDate, $returnDate,
      $wholesalePrice, $flightPrice, $seats
    );

    if ($stmt->execute()) {
      $inserted++;
      $insertedIds[] = $conn->insert_id;
    } else {
      $errors[] = "Error on row $i: " . $stmt->error;
      $hasError = true;
      break;
    }
  }

  $stmt->close();

  if ($hasError) 
  {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
      'status' => 'error',
      'message' => 'Transaction failed. No flights were saved.',
      'details' => $errors
    ]);
  } 
  else 
  {
    $conn->commit();
    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'message' => "$inserted flight(s) saved successfully.",
      'insertedIds' => $insertedIds
    ]);
  }
} 
else 
{
  http_response_code(405);
  echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
