<?php
session_start();
require_once "../../conn copy.php";
header("Content-Type: application/json");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Helper function to validate date
function validateDate($date, $format = 'Y-m-d') {
  if (!$date) return false;
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}

try {
  // Payload presence check
  if (!isset($_POST["voucherPayload"])) {
    echo json_encode(["status" => "error", "message" => "Missing voucher payload."]);
    exit;
  }

  $payload = json_decode($_POST["voucherPayload"], true);
  
  if (!$payload) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON format."]);
    exit;
  }

  // Extract structured data
  $templateName        = trim($payload['templateName'] ?? '');
  $voucherDetails      = $payload['voucherDetails'] ?? [];
  $airScheduleDetails  = $payload['airScheduleDetails'] ?? [];
  $guideMeeting        = $airScheduleDetails['guideMeeting'] ?? null;

  unset($airScheduleDetails['guideMeeting']);

  $cardsJSONData       = $payload['cardsJSONData'] ?? [];
  $includesData        = $payload['includesData'] ?? [];
  $excludesData        = $payload['excludesData'] ?? [];

  $accountId = $_SESSION['accountId'] ?? 1;

  // Validate essential fields before DB inserts
  if (empty($templateName)) {
    throw new Exception("Template name is required.");
  }

  // Prepare and validate all statements first before execution phase
  $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM vouchers WHERE voucherCode = ?");

  $stmtVoucher = $conn->prepare("INSERT INTO vouchers (voucherName, accountId, voucherCode, itineraryId, isConnectToItinerary) VALUES (?, ?, ?, ?, ?)");

  $stmtDetails = $conn->prepare("
    INSERT INTO voucherDetails (
      voucherId, sentTo, sentFrom, tourType, attachment,
      tourPeriodStart, tourPeriodEnd, guideId, noOfPax
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");

  $stmtHotel = $conn->prepare("
    INSERT INTO voucherdatehotels (
      voucherId, startDate, endDate, nights, city, hotel
    ) VALUES (?, ?, ?, ?, ?, ?)
  ");

  $stmtAir = $conn->prepare("
    INSERT INTO voucherAirSchedules (
      voucherId, flightSegment, flightDate, flightNumber,
      origin, destination, departureTime, arrivalTime
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $stmtGuide = $conn->prepare("
    INSERT INTO voucherGuideMeeting (
      voucherId, guideId, meetingDate, meetingTime, meetingPlace
    ) VALUES (?, ?, ?, ?, ?)
  ");

  $stmtInclude = $conn->prepare("INSERT INTO voucherIncludes (voucherId, includeItemId, includeOptionItem) VALUES (?, ?, ?)");
  $stmtExclude = $conn->prepare("INSERT INTO voucherExcludes (voucherId, excludeItemId, excludeOptionItem) VALUES (?, ?, ?)");


  // Start transaction
  $conn->beginTransaction();






  // Generate unique voucher code
  do {
    $voucherCode = "VOUCHER-" . strtoupper(uniqid());
    $stmtCheck->execute([$voucherCode]);
  } while ($stmtCheck->fetchColumn() > 0);


  $accountId   = isset($accountId) ? (int) $accountId : null; // assumed available

  $guideName   = isset($voucherDetails['guide']) && filter_var($voucherDetails['guide'], FILTER_VALIDATE_INT)
    ? $voucherDetails['guide']
    : null;

  $periodStart = validateDate($voucherDetails['periodStart']) ? $voucherDetails['periodStart'] : null;
  $periodEnd   = validateDate($voucherDetails['periodEnd'])   ? $voucherDetails['periodEnd']   : null;

  $isConnectToItinerary = isset($voucherDetails['isConnectToItinerary']) && $voucherDetails['isConnectToItinerary'] ? 1 : 0;
  $itineraryId = $isConnectToItinerary && isset($voucherDetails['itineraryId']) && is_numeric($voucherDetails['itineraryId'])
    ? (int)$voucherDetails['itineraryId']
    : null;

  // Insert into vouchers table
  $stmtVoucher = $conn->prepare("INSERT INTO vouchers (voucherName, accountId, voucherCode, itineraryId, isConnectToItinerary) VALUES (?, ?, ?, ?, ?)");

  if (!$stmtVoucher->execute([
    $templateName,
    $accountId,
    $voucherCode,
    $itineraryId,
    $isConnectToItinerary
  ])) {
    throw new Exception("Failed to insert into `vouchers`.");
  }

  $voucherId = $conn->lastInsertId();





  if (!$stmtDetails->execute([
    $voucherId,
    trim($voucherDetails['to'] ?? ''),
    trim($voucherDetails['from'] ?? ''),
    trim($voucherDetails['tour'] ?? ''),
    trim($voucherDetails['attachment'] ?? ''),
    $periodStart,
    $periodEnd,
    $guideName,
    $voucherDetails['paxCount'] ?? 0
  ])) {
    throw new Exception("Failed to insert into `voucherDetails`.");
  }


  // Insert Date & Hotels
  foreach ($cardsJSONData as $key => $data) {
    if (strpos($key, 'dateAndHotel') === 0) {
      $stmtHotel->execute([
        $voucherId,
        $data['startDate'] ?? null,
        $data['endDate'] ?? null,
        $data['nights'] ?? 0,
        trim($data['city'] ?? ''),
        trim($data['hotel'] ?? '')
      ]);
    }
  }


  // Insert Air Schedules
  foreach ($airScheduleDetails as $segment => $flight) {
    $enumSegment = strtolower(str_replace(['#', ' '], '', $segment));
    if (in_array($enumSegment, ['departureflight', 'returningflight', 'connectingflight'])) {
      $stmtAir->execute([
        $voucherId,
        $enumSegment,
        $flight['flightDate'] ?? null,
        trim($flight['flightNumber'] ?? ''),
        trim($flight['origin'] ?? ''),
        trim($flight['destination'] ?? ''),
        $flight['departureTime'] ?? null,
        $flight['arrivalTime'] ?? null
      ]);
    }
  }


  // Insert Guide Meeting
  if (!empty($guideMeeting)) {
    $stmtGuide->execute([
      $voucherId,
      $guideMeeting['guideId'] ?? null,
      $guideMeeting['date'] ?? null,
      $guideMeeting['time'] ?? null,
      trim($guideMeeting['place'] ?? '')
    ]);
  }


  // Insert Includes
  foreach ($includesData as $item) {
    $includeItemId = $item['id'] ?? null;
    $includeOptionItem = isset($item['custom']) && !empty($item['custom']) ? $item['custom'] : '';

    if (!empty($includeItemId) && $includeItemId !== '0') {
      $stmtInclude->execute([$voucherId, $includeItemId, $includeOptionItem]);
    }
  }

  // Insert Excludes
  foreach ($excludesData as $item) {
    $excludeItemId = $item['excludeItemId'] ?? null;
    $excludeOptionItem = isset($item['custom']) && !empty($item['custom']) ? $item['custom'] : '';

    if (!empty($excludeItemId) && $excludeItemId !== '0') {
      $stmtExclude->execute([$voucherId, $excludeItemId, $excludeOptionItem]);
    }
  }




  // Commit transaction if all successful
  $conn->commit();

  echo json_encode([
    "status" => "success",
    "message" => "Voucher saved successfully.",
    "voucherId" => $voucherId,
    "voucherCode" => $voucherCode,
    "voucherPayload" => $payload
  ]);


} catch (PDOException $e) {
  if ($conn->inTransaction()) $conn->rollBack();
  error_log("DB Error: " . $e->getMessage());
  echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
} 
  catch (Exception $e) {
  if ($conn->inTransaction()) $conn->rollBack();
  error_log("General Error: " . $e->getMessage());
  echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}

exit;
?>
