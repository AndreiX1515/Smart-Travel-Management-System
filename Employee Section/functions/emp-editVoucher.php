<?php
session_start();
require_once "../../conn copy.php";
header("Content-Type: application/json");
ini_set('display_errors', 1);
error_reporting(E_ALL);

function validateDate($date, $format = 'Y-m-d') {
  if (!$date) return false;
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}

try {
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

  $templateName   = trim($_POST['templateName'] ?? '');
  $voucherName    = trim($payload['voucherName'] ?? '');
  $details        = $payload['details'] ?? [];
  $airSchedules   = $payload['airSchedules'] ?? [];
  $guideMeeting   = $payload['guideMeeting'][0] ?? [];
  $dateAndHotels  = $payload['dateAndHotels'] ?? [];
  $includes       = $payload['includes'] ?? [];
  $excludes       = $payload['excludes'] ?? [];
  $itineraryId    = $payload['itineraryId'] ?? null;
  $flightId       = $payload['flightId'] ?? null;
  $accountId      = $_SESSION['accountId'] ?? 1;

  if (strtolower($templateName) === strtolower($voucherName)) {
    $voucherName .= "-Edited";
    error_log("Template name was same as voucherName. Modified to: $voucherName");
  }

  $toId = null;
  $branchName = $details['sentTo'] ?? '';
  if ($branchName) {
    $stmtBranch = $conn->prepare("SELECT branchId FROM branch WHERE branchName = ? LIMIT 1");
    if ($stmtBranch->execute([$branchName])) {
      $toId = $stmtBranch->fetchColumn();
    }
  }

  $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM vouchers WHERE voucherCode = ?");

  $stmtVoucher = $conn->prepare("
    INSERT INTO vouchers (voucherName, accountId, voucherCode, itineraryId, flightId)
    VALUES (?, ?, ?, ?, ?)
  ");

  $stmtDetails = $conn->prepare("
    INSERT INTO voucherdetails (
      voucherId, sentToId, sentToName, sentFrom, tourType, attachment,
      tourPeriodStart, tourPeriodEnd, guideId, noOfPax
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");

  $stmtHotel = $conn->prepare("
    INSERT INTO voucherdatehotels (voucherId, startDate, endDate, NoOfnights, city, hotel)
    VALUES (?, ?, ?, ?, ?, ?)
  ");

  $stmtAir = $conn->prepare("
    INSERT INTO voucherairschedules (
      voucherId, flightSegment, flightDate, flightNumber,
      origin, destination, departureTime, arrivalTime
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  ");

  $stmtGuide = $conn->prepare("
    INSERT INTO voucherguidemeeting (
      voucherId, guideId, meetingDate, meetingTime, meetingPlace
    ) VALUES (?, ?, ?, ?, ?)
  ");

  $stmtInclude = $conn->prepare("INSERT INTO voucherincludes (voucherId, includeItemId, includeOptionItem) VALUES (?, ?, ?)");
  $stmtExclude = $conn->prepare("INSERT INTO voucherexcludes (voucherId, excludeItemId, excludeOptionItem) VALUES (?, ?, ?)");

  $conn->beginTransaction();

  // Unique Voucher Code
  do {
    $voucherCode = "VOUCHER-" . strtoupper(uniqid());
    $stmtCheck->execute([$voucherCode]);
  } while ($stmtCheck->fetchColumn() > 0);

  $stmtVoucher->execute([
    $voucherName,
    (int)$accountId,
    $voucherCode,
    $itineraryId,
    $flightId
  ]);

  $voucherId = $conn->lastInsertId();

  $stmtDetails->execute([
    $voucherId,
    $toId,
    $branchName,
    trim($details['sentFrom'] ?? ''),
    trim($details['tourType'] ?? ''),
    trim($details['attachment'] ?? ''),
    validateDate($details['tourPeriodStart']) ? $details['tourPeriodStart'] : null,
    validateDate($details['tourPeriodEnd']) ? $details['tourPeriodEnd'] : null,
    $details['guideId'] ?? null,
    $details['noOfPax'] ?? 0
  ]);

  foreach ($dateAndHotels as $data) {
    $stmtHotel->execute([
      $voucherId,
      $data['startDate'] ?? null,
      $data['endDate'] ?? null,
      $data['nights'] ?? 0,
      trim($data['city'] ?? ''),
      trim($data['hotel'] ?? '')
    ]);
  }

  foreach ($airSchedules as $flight) {
    $segment = strtolower($flight['flightSegment'] ?? '');
    if (in_array($segment, ['departureflight', 'returningflight', 'connectingflight'])) {
      $stmtAir->execute([
        $voucherId,
        $segment,
        $flight['flightDate'] ?? null,
        $flight['flightNumber'] ?? '',
        $flight['origin'] ?? '',
        $flight['destination'] ?? '',
        $flight['departureTime'] ?? null,
        $flight['arrivalTime'] ?? null
      ]);
    }
  }

  if (!empty($guideMeeting)) {
    $stmtGuide->execute([
      $voucherId,
      $guideMeeting['guideId'] ?? null,
      $guideMeeting['meetingDate'] ?? null,
      $guideMeeting['meetingTime'] ?? null,
      trim($guideMeeting['meetingPlace'] ?? '')
    ]);
  }

  foreach ($includes as $item) {
    $value = $item['value'] ?? null;
    if (!empty($value)) {
      $stmtInclude->execute([$voucherId, $value, $item['label'] ?? '']);
    }
  }

  foreach ($excludes as $item) {
    $value = $item['value'] ?? null;
    if (!empty($value)) {
      $stmtExclude->execute([$voucherId, $value, $item['label'] ?? '']);
    }
  }

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
