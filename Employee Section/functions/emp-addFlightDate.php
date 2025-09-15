<?php
require "../../conn.php"; // DB connection
session_start();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
  exit;
}

// Collect POST arrays (use null coalesce to avoid notices)
$employeeIds        = $_POST['employeeId']           ?? [];
$packageIds         = $_POST['packageId']            ?? [];
$airlineIds         = $_POST['airlineId']            ?? [];
$origins            = $_POST['origin']               ?? [];
$destinations       = $_POST['destination']          ?? [];
$flightCodes        = $_POST['flightCode']           ?? [];
$departureDates     = $_POST['departureDate']        ?? [];
$departureTimes     = $_POST['departureTime']        ?? [];
$arrivalDates       = $_POST['arrivalDate']          ?? [];
$arrivalTimes       = $_POST['arrivalTime']          ?? [];

$returnAirlineIds   = $_POST['returnAirlineId']      ?? [];
$returnOrigins      = $_POST['returnOrigin']         ?? [];
$returnDestinations = $_POST['returnDestination']    ?? [];
$returnFlightCodes  = $_POST['returnFlightCode']     ?? [];
$returnDates        = $_POST['returnDate']           ?? [];
$returnDepartureTimes = $_POST['returnDepartureTime'] ?? [];
$returnArrivalDates   = $_POST['returnArrivalDate']    ?? [];
$returnArrivalTimes   = $_POST['returnArrivalTime']    ?? [];

$wholesalePrices    = $_POST['wholesalePrice']       ?? [];
$flightPrices       = $_POST['flightPrice']          ?? [];
$landPrices         = $_POST['landPrice']            ?? [];
$availSeats         = $_POST['availSeats']           ?? [];

// Determine row count (use packageIds as canonical; fail if arrays inconsistent)
$rowCount = count($packageIds);

// Quick consistency check: at least packageIds should be present
if ($rowCount === 0) {
  http_response_code(400);
  echo json_encode(['status' => 'error', 'message' => 'No rows submitted.']);
  exit;
}

// Optional: verify other arrays have at least $rowCount elements
$expectedArrays = [
  'employeeId' => $employeeIds,
  'airlineId' => $airlineIds,
  'origin' => $origins,
  'destination' => $destinations,
  'flightCode' => $flightCodes,
  'departureDate' => $departureDates,
  'departureTime' => $departureTimes,
  'arrivalDate' => $arrivalDates,
  'arrivalTime' => $arrivalTimes,
  'returnAirlineId' => $returnAirlineIds,
  'returnOrigin' => $returnOrigins,
  'returnDestination' => $returnDestinations,
  'returnFlightCode' => $returnFlightCodes,
  'returnDate' => $returnDates,
  'returnDepartureTime' => $returnDepartureTimes,
  'returnArrivalDate' => $returnArrivalDates,
  'returnArrivalTime' => $returnArrivalTimes,
  'wholesalePrice' => $wholesalePrices,
  'flightPrice' => $flightPrices,
  'landPrice' => $landPrices,
  'availSeats' => $availSeats
];

foreach ($expectedArrays as $name => $arr) {
  if (count($arr) < $rowCount) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => "Input array '$name' has fewer elements than packageId[] (expected at least $rowCount)."]);
    exit;
  }
}

// Start transaction
$conn->begin_transaction();

try {
  // Prepare INSERT. Columns match your table.
  $sql = "INSERT INTO flight (packageId, employeeId, airlineId, origin, destination, flightName, flightCode, flightDepartureDate, flightDepartureTime,
                              flightArrivalDate, flightArrivalTime, returnAirlineId, returnOrigin, returnDestination, returnFlightName, returnFlightCode, 
                              returnDepartureDate, returnDepartureTime, returnArrivalDate, returnArrivalTime, wholesalePrice, flightPrice, landPrice, 
                              availSeats) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    throw new Exception("Prepare failed: " . $conn->error);
  }

  // Build type string: 3 ints, 8 strings (outbound), 1 int, 8 strings (return), 3 doubles, 1 int
  // We'll construct using repeat to avoid mistakes:
  $types = 'isi' . str_repeat('s', 8) . 'i' . str_repeat('s', 8) . 'ddd' . 'i'; // total 24 params

  $inserted = 0;
  $insertedIds = [];

  // Loop rows
  for ($i = 0; $i < $rowCount; $i++) {
    // Trim and fetch values (use null coalesce to avoid notices)
    $packageId = intval($packageIds[$i]);
    $employeeId  = trim($employeeIds[$i]) === '' ? null : trim($employeeIds[$i]); // "" → NULL
    $airlineId = intval($airlineIds[$i]);
    $origin = trim($origins[$i]);
    $destination = trim($destinations[$i]);
    $flightCode = trim($flightCodes[$i]);
    $departureDate = trim($departureDates[$i]);
    $departureTime = trim($departureTimes[$i]);
    $arrivalDate = trim($arrivalDates[$i]);
    $arrivalTime = trim($arrivalTimes[$i]);

    $returnAirlineId = intval($returnAirlineIds[$i]);
    $returnOrigin = trim($returnOrigins[$i]);
    $returnDestination = trim($returnDestinations[$i]);
    $returnFlightCode = trim($returnFlightCodes[$i]);
    $returnDate = trim($returnDates[$i]);
    $returnDepartureTime = trim($returnDepartureTimes[$i]);
    $returnArrivalDate = trim($returnArrivalDates[$i]);
    $returnArrivalTime = trim($returnArrivalTimes[$i]);

    $wholesalePrice = is_numeric($wholesalePrices[$i]) ? floatval($wholesalePrices[$i]) : 0.0;
    $flightPrice = is_numeric($flightPrices[$i]) ? floatval($flightPrices[$i]) : 0.0;
    $landPrice = is_numeric($landPrices[$i]) ? floatval($landPrices[$i]) : 0.0;
    $seats = is_numeric($availSeats[$i]) ? intval($availSeats[$i]) : 0;

    // Basic validation for required fields (you can expand as needed)
    $rowErrors = [];
    if ($packageId <= 0) $rowErrors[] = "packageId";
    if ($employeeId === '') $rowErrors[] = "employeeId";
    if ($airlineId <= 0) $rowErrors[] = "airlineId";
    if ($origin === '') $rowErrors[] = "origin";
    if ($destination === '') $rowErrors[] = "destination";
    if ($flightCode === '') $rowErrors[] = "flightCode";
    if ($departureDate === '' || $departureTime === '') $rowErrors[] = "departureDate/time";
    if ($arrivalDate === '' || $arrivalTime === '') $rowErrors[] = "arrivalDate/time";
    if ($returnAirlineId <= 0) $rowErrors[] = "returnAirlineId";
    if ($returnOrigin === '') $rowErrors[] = "returnOrigin";
    if ($returnDestination === '') $rowErrors[] = "returnDestination";
    if ($returnFlightCode === '') $rowErrors[] = "returnFlightCode";
    if ($returnDate === '' || $returnDepartureTime === '' || $returnArrivalDate === '' || $returnArrivalTime === '') $rowErrors[] = "return date/time";
    if ($seats <= 0) $rowErrors[] = "availSeats";
    // prices can be zero but must be numeric
    if (!is_numeric($wholesalePrice) || !is_numeric($flightPrice) || !is_numeric($landPrice)) $rowErrors[] = "prices";

    if (!empty($rowErrors)) {
      throw new Exception("Validation failed for row " . ($i + 1) . ". Missing/invalid: " . implode(', ', $rowErrors));
    }

    // Derive names if you want (or set as NULL). We'll derive readable names:
    $flightName = $origin . " - " . $destination;
    $returnFlightName = $returnOrigin . " - " . $returnDestination;

    // Bind parameters (must be variables)
    $bindResult = $stmt->bind_param($types, $packageId, $employeeId,  $airlineId, $origin, $destination, $flightName, $flightCode, $departureDate,
                                    $departureTime, $arrivalDate, $arrivalTime, $returnAirlineId, $returnOrigin, $returnDestination, $returnFlightName,
                                    $returnFlightCode, $returnDate, $returnDepartureTime, $returnArrivalDate, $returnArrivalTime, $wholesalePrice,
                                    $flightPrice, $landPrice, $seats);
    

    if ($bindResult === false) {
      throw new Exception("bind_param failed: " . $stmt->error);
    }

    if (!$stmt->execute()) {
      throw new Exception("Insert failed on row " . ($i + 1) . ": " . $stmt->error);
    }

    $inserted++;
    $insertedIds[] = $conn->insert_id;
  } // end loop

  // Success
  $stmt->close();
  $conn->commit();

  echo json_encode([
    'status' => 'success',
    'message' => "$inserted row(s) inserted.",
    'insertedIds' => $insertedIds
  ]);
  exit;
} catch (Exception $e) {
  // Rollback & return error
  $conn->rollback();

  // Close statement if open
  if (isset($stmt) && $stmt instanceof mysqli_stmt) {
    $stmt->close();
  }

  http_response_code(500);
  echo json_encode([
    'status' => 'error',
    'message' => 'Failed to insert flights.',
    'details' => $e->getMessage()
  ]);
  exit;
}
