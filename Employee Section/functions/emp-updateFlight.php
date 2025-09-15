<?php
session_start();
require "../../conn.php";

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
  exit;
}

// Collect POST data safely
$flightId             = $_POST['flightId'] ?? null;
$packageId            = $_POST['packageId'] ?? null;
$employeeId           = $_POST['employeeId'] ?? null; // can be null if "No Team OP"

$airlineId            = $_POST['airlineId'] ?? null;
$flightCode           = $_POST['flightCode'] ?? null;
$flightDepartureDate  = $_POST['flightDepartureDate'] ?? null;
$flightDepartureTime  = $_POST['flightDepartureTime'] ?? null;
$flightArrivalDate    = $_POST['flightArrivalDate'] ?? null;
$flightArrivalTime    = $_POST['flightArrivalTime'] ?? null;

$returnAirlineId      = $_POST['returnAirlineId'] ?? null;
$returnFlightCode     = $_POST['returnFlightCode'] ?? null;
$returnDepartureDate  = $_POST['returnDepartureDate'] ?? null;
$returnDepartureTime  = $_POST['returnDepartureTime'] ?? null;
$returnArrivalDate    = $_POST['returnArrivalDate'] ?? null;
$returnArrivalTime    = $_POST['returnArrivalTime'] ?? null;

$wholesalePrice       = $_POST['wholesalePrice'] ?? 0;
$flightPrice          = $_POST['flightPrice'] ?? 0;
$landPrice            = $_POST['landPrice'] ?? 0;
$availSeats           = $_POST['availSeats'] ?? 0;

// Validate
if (!$flightId || !$packageId || !$airlineId || !$flightCode) {
  echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
  exit;
}

if ($employeeId === "" || strtolower($employeeId) === "null") {
  $employeeId = null;
}

try {
  $sql = "UPDATE flight SET packageId = ?, airlineId = ?, employeeId = ?, flightCode = ?, flightDepartureDate = ?, flightDepartureTime = ?, 
            flightArrivalDate = ?, flightArrivalTime = ?, returnAirlineId = ?, returnFlightCode = ?, returnDepartureDate = ?, returnDepartureTime = ?,
            returnArrivalDate = ?, returnArrivalTime = ?, wholesalePrice = ?, flightPrice = ?, landPrice = ?, availSeats = ?
          WHERE flightId = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iissssssisssssdddii", $packageId, $airlineId, $employeeId, $flightCode, $flightDepartureDate, $flightDepartureTime, $flightArrivalDate,
                    $flightArrivalTime, $returnAirlineId, $returnFlightCode, $returnDepartureDate, $returnDepartureTime, $returnArrivalDate,
                    $returnArrivalTime, $wholesalePrice, $flightPrice, $landPrice, $availSeats, $flightId);

  if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Flight updated successfully.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update flight.']);
  }

  $stmt->close();
  $conn->close();

} catch (Exception $e) {
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
