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

  // Extract sections from JSON
  $templateName        = $payload['templateName'] ?? '';
  $voucherDetails      = $payload['voucherDetails'] ?? [];
  $airScheduleDetails  = $payload['airScheduleDetails'] ?? [];
  $guideMeeting        = $airScheduleDetails['guideMeeting'] ?? null;
  unset($airScheduleDetails['guideMeeting']); // Remove guideMeeting from air segments
  $cardsJSONData       = $payload['cardsJSONData'] ?? [];
  $includesData        = $payload['includesData'] ?? [];
  $excludesData        = $payload['excludesData'] ?? [];

  $accountId = $_SESSION['accountId'] ?? 1;

  $conn->beginTransaction();

  // Generate unique voucherCode
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
    $errorInfo = $stmtVoucher->errorInfo();
    throw new Exception("Failed to insert voucher: " . implode(" | ", $errorInfo));
  }

  $voucherId = $conn->lastInsertId();

  // Validate and sanitize guideName as integer or null
  $guideName = null;
  if (isset($voucherDetails['guide'])) {
    $guideName = filter_var($voucherDetails['guide'], FILTER_VALIDATE_INT);
    if ($guideName === false) {
      $guideName = null;
    }
  }

  // Validate date fields
  $periodStart = isset($voucherDetails['periodStart']) && validateDate($voucherDetails['periodStart']) ? $voucherDetails['periodStart'] : null;
  $periodEnd = isset($voucherDetails['periodEnd']) && validateDate($voucherDetails['periodEnd']) ? $voucherDetails['periodEnd'] : null;

  // Insert voucherDetails
  $stmtDetails = $conn->prepare("
    INSERT INTO voucherDetails (
      voucherId, sentTo, sentFrom, tourType, attachment,
      tourPeriodStart, tourPeriodEnd, guideName, noOfPax
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $success = $stmtDetails->execute([
    $voucherId,
    $voucherDetails['to'] ?? '',
    $voucherDetails['from'] ?? '',
    $voucherDetails['tour'] ?? '',
    $voucherDetails['attachment'] ?? '',
    $periodStart,
    $periodEnd,
    $guideName,
    $voucherDetails['paxCount'] ?? 0
  ]);

  if (!$success) {
    $errorInfo = $stmtDetails->errorInfo();
    throw new Exception("Failed to insert voucherDetails: " . implode(" | ", $errorInfo));
  }

  // // Fetch the inserted voucherDetails row for confirmation / response
  // $stmtFetchDetails = $conn->prepare("SELECT * FROM voucherDetails WHERE voucherId = ?");
  // $stmtFetchDetails->execute([$voucherId]);
  // $insertedVoucherDetails = $stmtFetchDetails->fetch(PDO::FETCH_ASSOC);
  // if (!$insertedVoucherDetails) {
  //   throw new Exception("Failed to fetch inserted voucherDetails.");
  // }






  // Insert hotel and date info
  if (!empty($cardsJSONData)) {
    $stmtHotel = $conn->prepare("
      INSERT INTO voucherDateAndHotels (
        voucherId, startDate, endDate, nights, city, hotel
      ) VALUES (?, ?, ?, ?, ?, ?)
    ");
    foreach ($cardsJSONData as $key => $data) {
      if (strpos($key, 'dateAndHotel') === 0) {
        $stmtHotel->execute([
          $voucherId,
          $data['startDate'],
          $data['endDate'],
          $data['nights'],
          $data['city'],
          $data['hotel']
        ]);
      }
    }
  }

  // Insert flight schedule
  if (!empty($airScheduleDetails)) {
    $stmtAir = $conn->prepare("
      INSERT INTO voucherAirSchedules (
        voucherId, flightSegment, flightDate, flightNumber,
        origin, destination, departureTime, arrivalTime
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    foreach ($airScheduleDetails as $segment => $flight) {
      $enumSegment = strtolower(str_replace(['#', ' '], '', $segment)); // normalize
      if (in_array($enumSegment, ['departure1', 'departure2'])) {
        $stmtAir->execute([
          $voucherId,
          $enumSegment,
          $flight['flightDate'] ?? null,
          $flight['flightNumber'] ?? '',
          $flight['origin'] ?? '',
          $flight['destination'] ?? '',
          $flight['departureTime'] ?? null,
          $flight['arrivalTime'] ?? null
        ]);
      }
    }
  }

  // Insert guide meeting
  if (!empty($guideMeeting)) {
    $stmtGuide = $conn->prepare("
      INSERT INTO voucherGuideMeeting (
        voucherId, guideId, meetingDate, meetingTime, meetingPlace
      ) VALUES (?, ?, ?, ?, ?)
    ");
    $stmtGuide->execute([
      $voucherId,
      $guideMeeting['guideId'] ?? null,
      $guideMeeting['date'] ?? null,
      $guideMeeting['time'] ?? null,
      $guideMeeting['place'] ?? ''
    ]);
  }

  // Insert includes
  if (!empty($includesData)) {
    $stmtInclude = $conn->prepare("
      INSERT INTO voucherIncludes (voucherId, includeItemId)
      VALUES (?, ?)
    ");
    foreach ($includesData as $item) {
      $val = $item['value'] ?? '';
      if (!empty($val) && $val !== '0') {
        $stmtInclude->execute([$voucherId, $val]);
      }
    }
  }

  // Insert excludes
  if (!empty($excludesData)) {
    $stmtExclude = $conn->prepare("
      INSERT INTO voucherExcludes (voucherId, excludeItemId)
      VALUES (?, ?)
    ");
    foreach ($excludesData as $item) {
      $val = $item['value'] ?? '';
      if (!empty($val) && $val !== '0') {
        $stmtExclude->execute([$voucherId, $val]);
      }
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
  if ($conn->inTransaction()) {
    $conn->rollBack();
  }
  error_log("DB Error: " . $e->getMessage());
  echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
  if ($conn->inTransaction()) {
    $conn->rollBack();
  }
  error_log("General Error: " . $e->getMessage());
  echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}

exit;


// Helper function to validate date string in 'Y-m-d' format
function validateDate($date, $format = 'Y-m-d') {
  if (!$date) return false;
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}
?>
