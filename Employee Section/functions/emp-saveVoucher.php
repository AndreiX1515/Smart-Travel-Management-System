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
    error_log("Missing voucher payload.");
    echo json_encode(["status" => "error", "message" => "Missing voucher payload."]);
    exit;
  }

  $payload = json_decode($_POST["voucherPayload"], true);
  
  if (!$payload) {
    error_log("Invalid JSON format.");
    echo json_encode(["status" => "error", "message" => "Invalid JSON format."]);
    exit;
  }

  // Extract structured data
  $templateName        = trim($payload['templateName'] ?? '');
  $airScheduleDetails  = $payload['airScheduleDetails'] ?? [];
  $guideMeeting        = $payload['guideMeeting'] ?? [];
  $cardsJSONData       = $payload['cardsJSONData'] ?? [];
  $includesData        = $payload['includesData'] ?? [];
  $excludesData        = $payload['excludesData'] ?? [];
  $voucherDetails      = $payload['voucherDetails'] ?? [];

  $accountId = $_SESSION['accountId'] ?? 1;

  $toId = $voucherDetails['toId'] ?? null;
  $branchName = '';

  if ($toId) {
    $stmtBranch = $conn->prepare("SELECT branchName FROM branch WHERE branchId = ? LIMIT 1");
    if (!$stmtBranch->execute([$toId])) {
      throw new Exception("Failed to fetch branchName for toId: $toId");
    }
    $branchName = $stmtBranch->fetchColumn() ?: '';
    error_log("Fetched branchName: $branchName for branchId: $toId");
  }

  if (empty($templateName)) {
    error_log("Template name is required.");
    throw new Exception("Template name is required.");
  }

  // Prepare all statements
  $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM vouchers WHERE voucherCode = ?");

  $stmtVoucher = $conn->prepare("
    INSERT INTO vouchers (voucherName, accountId, voucherCode, itineraryId, flightId) 
    VALUES (?, ?, ?, ?, ?)
  ");

  $stmtDetails = $conn->prepare("INSERT INTO voucherDetails (
      voucherId, sentToId, sentToName, sentFrom, tourType, attachment,
      tourPeriodStart, tourPeriodEnd, guideId, noOfPax
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  $stmtHotel = $conn->prepare("INSERT INTO voucherdatehotels (voucherId, startDate, endDate, NoOfnights, city, hotel) VALUES (?, ?, ?, ?, ?, ?)");

  $stmtAir = $conn->prepare("INSERT INTO voucherAirSchedules (
      voucherId, flightSegment, flightDate, flightNumber,
      origin, destination, departureTime, arrivalTime
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

  $stmtGuide = $conn->prepare("INSERT INTO voucherGuideMeeting (
      voucherId, guideId, meetingDate, meetingTime, meetingPlace
  ) VALUES (?, ?, ?, ?, ?)");

  $stmtInclude = $conn->prepare("INSERT INTO voucherIncludes (voucherId, includeItemId, includeOptionItem) VALUES (?, ?, ?)");
  $stmtExclude = $conn->prepare("INSERT INTO voucherExcludes (voucherId, excludeItemId, excludeOptionItem) VALUES (?, ?, ?)");

  $conn->beginTransaction();

  // Generate unique voucher code
  do {
    $voucherCode = "VOUCHER-" . strtoupper(uniqid());
    $stmtCheck->execute([$voucherCode]);
  } while ($stmtCheck->fetchColumn() > 0);

  error_log("Generated unique voucherCode: $voucherCode");

  $guideName   = isset($voucherDetails['guide']) && filter_var($voucherDetails['guide'], FILTER_VALIDATE_INT)
    ? $voucherDetails['guide']
    : null;

  $periodStart = validateDate($voucherDetails['periodStart']) ? $voucherDetails['periodStart'] : null;
  $periodEnd   = validateDate($voucherDetails['periodEnd'])   ? $voucherDetails['periodEnd']   : null;

  // $isConnectToItinerary = isset($voucherDetails['itineraryId']) && is_numeric($voucherDetails['itineraryId']) ? 1 : 0;

  $itineraryId = isset($voucherDetails['itineraryId']) && is_numeric($voucherDetails['itineraryId']) 
    ? (int)$voucherDetails['itineraryId'] 
    : null;


  $flightId = isset($voucherDetails['flightId']) && is_numeric($voucherDetails['flightId']) 
    ? (int)$voucherDetails['flightId'] 
    : null;


  if (!$stmtVoucher->execute([
    $templateName,
    (int)$accountId,
    $voucherCode,
    $itineraryId,
    $flightId
  ])) {
    error_log("Failed to insert into vouchers.");
    throw new Exception("Failed to insert into `vouchers`.");
  }


  $voucherId = $conn->lastInsertId();
  error_log("Inserted voucherId: $voucherId");

  if (!$stmtDetails->execute([
    $voucherId,
    $toId,
    $branchName,
    trim($voucherDetails['from'] ?? ''),
    trim($voucherDetails['tour'] ?? ''),
    trim($voucherDetails['attachment'] ?? ''),
    $periodStart,
    $periodEnd,
    $guideName,
    $voucherDetails['paxCount'] ?? 0
  ])) {
    error_log("Failed to insert into voucherDetails.");
    throw new Exception("Failed to insert into `voucherDetails`.");
  }


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
      error_log("Inserted hotel record for $key");
    }
  }

  // Insert air schedule segments
  if (!empty($airScheduleDetails) && is_array($airScheduleDetails)) {
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

        error_log("✅ Inserted air schedule segment: $enumSegment");
      } else {
        error_log("⚠️ Skipped unknown segment: $enumSegment");
      }
    }
  }

  // Insert guide meeting data
  if (!empty($guideMeeting) && is_array($guideMeeting)) {
    $stmtGuide->execute([
      $voucherId,
      $guideMeeting['guideId'] ?? null,
      $guideMeeting['date'] ?? null,
      $guideMeeting['time'] ?? null,
      trim($guideMeeting['place'] ?? '')
    ]);

    error_log("✅ Inserted guide meeting data");
  }


  foreach ($includesData as $item) {
    $includeItemId = $item['includeItemId'] ?? null; // ✅ fixed key
    $includeOptionItem = isset($item['custom']) && !empty($item['custom']) ? $item['custom'] : ($item['includeOptionItem'] ?? '');

    if (!empty($includeItemId) && $includeItemId !== '0') {
      $stmtInclude->execute([$voucherId, $includeItemId, $includeOptionItem]);
      error_log("Inserted include item ID: $includeItemId");
    }
  }

  foreach ($excludesData as $item) {
    $excludeItemId = $item['excludeItemId'] ?? null;
    $excludeOptionItem = isset($item['custom']) && !empty($item['custom']) ? $item['custom'] : ($item['excludeOptionItem'] ?? '');

    if (!empty($excludeItemId) && $excludeItemId !== '0') {
      $stmtExclude->execute([$voucherId, $excludeItemId, $excludeOptionItem]);
      error_log("Inserted exclude item ID: $excludeItemId");
    }
  }

    error_log("Preparing to insert: voucherId=$voucherId, includeItemId=$includeItemId, includeOptionItem=$includeOptionItem");


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

} catch (Exception $e) {
  if ($conn->inTransaction()) $conn->rollBack();
  error_log("General Error: " . $e->getMessage());
  echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}

exit;
