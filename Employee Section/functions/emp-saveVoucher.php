<?php
session_start();
require_once "../../conn copy.php";
header("Content-Type: application/json");
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
  if (!isset($_POST["voucherPayload"])) {
    echo json_encode(["status" => "error", "message" => "Missing voucher payload."]);
    exit;
  }

  $payload = json_decode($_POST["voucherPayload"], true);
  if (!$payload) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON format."]);
    exit;
  }

  // Extract data
  $templateName        = $payload['templateName'] ?? '';
  $voucherDetails      = $payload['voucherDetails'] ?? [];
  $airScheduleDetails  = $payload['airScheduleDetails'] ?? [];
  $guideMeeting        = $airScheduleDetails['guideMeeting'] ?? null;
  unset($airScheduleDetails['guideMeeting']);
  $cardsJSONData       = $payload['cardsJSONData'] ?? [];
  $includesData        = $payload['includesData'] ?? [];
  $excludesData        = $payload['excludesData'] ?? [];

  $accountId = $_SESSION['accountId'] ?? 1;

  $conn->beginTransaction();

  // Generate unique code
  do {
    $voucherCode = "VOUCHER-" . strtoupper(uniqid());
    $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM vouchers WHERE voucherCode = ?");
    $stmtCheck->execute([$voucherCode]);
  } while ($stmtCheck->fetchColumn() > 0);

  // Insert main voucher
  $stmtVoucher = $conn->prepare("
    INSERT INTO vouchers (voucherName, accountId, voucherCode)
    VALUES (?, ?, ?)
  ");
  if (!$stmtVoucher->execute([$templateName, $accountId, $voucherCode])) {
    throw new Exception("Failed to insert into `vouchers`.");
  }
  $voucherId = $conn->lastInsertId();

  // Handle guide
  $guideName = isset($voucherDetails['guide']) && filter_var($voucherDetails['guide'], FILTER_VALIDATE_INT) ? $voucherDetails['guide'] : null;

  $periodStart = validateDate($voucherDetails['periodStart']) ? $voucherDetails['periodStart'] : null;
  $periodEnd = validateDate($voucherDetails['periodEnd']) ? $voucherDetails['periodEnd'] : null;

  // Insert voucher details
  $stmtDetails = $conn->prepare("
    INSERT INTO voucherDetails (
      voucherId, sentTo, sentFrom, tourType, attachment,
      tourPeriodStart, tourPeriodEnd, guideId, noOfPax
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");
  if (!$stmtDetails->execute([
    $voucherId,
    $voucherDetails['to'] ?? '',
    $voucherDetails['from'] ?? '',
    $voucherDetails['tour'] ?? '',
    $voucherDetails['attachment'] ?? '',
    $periodStart,
    $periodEnd,
    $guideName,
    $voucherDetails['paxCount'] ?? 0
  ])) {
    throw new Exception("Failed to insert into `voucherDetails`.");
  }

  // Insert date & hotels
  if (!empty($cardsJSONData)) {
    $stmtHotel = $conn->prepare("
      INSERT INTO voucherDateAndHotels (
        voucherId, startDate, endDate, nights, city, hotel
      ) VALUES (?, ?, ?, ?, ?, ?)
    ");
    foreach ($cardsJSONData as $key => $data) {
      if (strpos($key, 'dateAndHotel') === 0) {
        if (!$stmtHotel->execute([
          $voucherId,
          $data['startDate'],
          $data['endDate'],
          $data['nights'],
          $data['city'],
          $data['hotel']
        ])) {
          throw new Exception("Failed to insert into `voucherDateAndHotels`.");
        }
      }
    }
  }

  // Insert air schedule
  if (!empty($airScheduleDetails)) {
    $stmtAir = $conn->prepare("
      INSERT INTO voucherAirSchedules (
        voucherId, flightSegment, flightDate, flightNumber,
        origin, destination, departureTime, arrivalTime
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    foreach ($airScheduleDetails as $segment => $flight) {
      $enumSegment = strtolower(str_replace(['#', ' '], '', $segment));
      if (in_array($enumSegment, ['departure1', 'departure2'])) {
        if (!$stmtAir->execute([
          $voucherId,
          $enumSegment,
          $flight['flightDate'] ?? null,
          $flight['flightNumber'] ?? '',
          $flight['origin'] ?? '',
          $flight['destination'] ?? '',
          $flight['departureTime'] ?? null,
          $flight['arrivalTime'] ?? null
        ])) {
          throw new Exception("Failed to insert into `voucherAirSchedules`.");
        }
      }
    }
  }

  // Insert guide meeting if exists
  if (!empty($guideMeeting)) {
    $stmtGuide = $conn->prepare("
      INSERT INTO voucherGuideMeeting (
        voucherId, guideId, meetingDate, meetingTime, meetingPlace
      ) VALUES (?, ?, ?, ?, ?)
    ");
    if (!$stmtGuide->execute([
      $voucherId,
      $guideMeeting['guideId'] ?? null,
      $guideMeeting['date'] ?? null,
      $guideMeeting['time'] ?? null,
      $guideMeeting['place'] ?? ''
    ])) {
      throw new Exception("Failed to insert into `voucherGuideMeeting`.");
    }
  }

  // Insert includes
  if (!empty($includesData)) {
    $stmtInclude = $conn->prepare("INSERT INTO voucherIncludes (voucherId, includeItemId) VALUES (?, ?)");
    foreach ($includesData as $item) {
      $val = $item['value'] ?? '';
      if (!empty($val) && $val !== '0') {
        if (!$stmtInclude->execute([$voucherId, $val])) {
          throw new Exception("Failed to insert into `voucherIncludes`.");
        }
      }
    }
  }

  // Insert excludes
  if (!empty($excludesData)) {
    $stmtExclude = $conn->prepare("INSERT INTO voucherExcludes (voucherId, excludeItemId) VALUES (?, ?)");
    foreach ($excludesData as $item) {
      $val = $item['value'] ?? '';
      if (!empty($val) && $val !== '0') {
        if (!$stmtExclude->execute([$voucherId, $val])) {
          throw new Exception("Failed to insert into `voucherExcludes`.");
        }
      }
    }
  }

  // All good
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


// Helper function to validate date
function validateDate($date, $format = 'Y-m-d') {
  if (!$date) return false;
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}
?>
