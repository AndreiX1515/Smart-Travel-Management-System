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

  // Extract sections
  $templateName = $payload['templateName'] ?? '';
  $voucherDetails = $payload['voucherDetails'] ?? [];
  $airScheduleDetails = $payload['airScheduleDetails'] ?? [];
  $cardsJSONData = $payload['cardsJSONData'] ?? [];
  $includesData = $payload['includesData'] ?? [];
  $excludesData = $payload['excludesData'] ?? [];

  // Generate unique code & account ID
  $accountId = $_SESSION['accountId'] ?? 1;

  $conn->beginTransaction();

  do {
    $voucherCode = "VOUCHER-" . strtoupper(uniqid());
    $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM vouchers WHERE voucherCode = ?");
    $stmtCheck->execute([$voucherCode]);
    $exists = $stmtCheck->fetchColumn();
  } while ($exists > 0);

  // Insert into vouchers
  $stmtVoucher = $conn->prepare("INSERT INTO vouchers (accountId, voucherCode) VALUES (?, ?)");
  $stmtVoucher->execute([$accountId, $voucherCode]);
  $voucherId = $conn->lastInsertId();

  // Insert into voucherDetails
  $stmtDetails = $conn->prepare("INSERT INTO voucherDetails (
    voucherId, sentTo, sentFrom, tourType, attachment,
    tourPeriodStart, tourPeriodEnd, guideName, noOfPax
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmtDetails->execute([
    $voucherId,
    $voucherDetails['to'] ?? '',
    $voucherDetails['from'] ?? '',
    $voucherDetails['tour'] ?? '',
    $voucherDetails['attachment'] ?? '',
    $voucherDetails['periodStart'] ?? null,
    $voucherDetails['periodEnd'] ?? null,
    $voucherDetails['guide'] ?? '',
    $voucherDetails['paxCount'] ?? 0
  ]);

  // Insert into voucherDateAndHotels
  if (!empty($cardsJSONData)) {
    $stmt = $conn->prepare("INSERT INTO voucherDateAndHotels (
      voucherId, startDate, endDate, nights, city, hotel
    ) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($cardsJSONData as $hotelKey => $hotelDetails) {
      if (strpos($hotelKey, 'dateAndHotel') === 0) {
        $stmt->execute([
          $voucherId,
          $hotelDetails['startDate'],
          $hotelDetails['endDate'],
          $hotelDetails['nights'],
          $hotelDetails['city'],
          $hotelDetails['hotel']
        ]);
      }
    }
  }

  // ✅ Insert into voucherAirSchedules
  if (!empty($airScheduleDetails)) {
    $stmtAir = $conn->prepare("INSERT INTO voucherAirSchedules (
      voucherId, flightSegment, flightDate, flightNumber,
      origin, destination, departureTime, arrivalTime
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($airScheduleDetails as $segment => $flight) {
      $stmtAir->execute([
        $voucherId,
        $segment,
        $flight['flightDate'] ?? null,
        $flight['flightNumber'] ?? '',
        $flight['origin'] ?? '',
        $flight['destination'] ?? '',
        $flight['departureTime'] ?? '',
        $flight['arrivalTime'] ?? ''
      ]);
    }
  }


  // After inserting voucherDateAndHotels and before commit

  // Insert GuideMeeting if exists
  $guideMeeting = $airScheduleDetails['guideMeeting'] ?? null;

  if ($guideMeeting) {
      $stmtGuide = $conn->prepare("INSERT INTO voucherGuideMeeting (voucherId, meetingDate, meetingTime, meetingPlace) VALUES (?, ?, ?, ?)");
      $stmtGuide->execute([
          $voucherId,
          $guideMeeting['date'] ?? null,
          $guideMeeting['time'] ?? null,
          $guideMeeting['place'] ?? ''
      ]);
  }



  // Insert into voucherIncludes
  if (!empty($includesData)) {
    $stmtInclude = $conn->prepare("INSERT INTO voucherIncludes (voucherId, includeItemId) VALUES (?, ?)");
    foreach ($includesData as $includeItem) {
      $value = $includeItem['value'] ?? '';
      if (!empty($value) && $value !== '0') {
        $stmtInclude->execute([$voucherId, $value]);
      }
    }
  }

  // Insert into voucherExcludes
  if (!empty($excludesData)) {
    $stmtExclude = $conn->prepare("INSERT INTO voucherExcludes (voucherId, excludeItemId) VALUES (?, ?)");
    foreach ($excludesData as $excludeItem) {
      $value = $excludeItem['value'] ?? '';
      if (!empty($value) && $value !== '0') {
        $stmtExclude->execute([$voucherId, $value]);
      }
    }
  }

  $conn->commit();
  echo json_encode([
    "status" => "success",
    "message" => "Voucher saved successfully.",
    "voucherId" => $voucherId,
    "voucherCode" => $voucherCode
  ]);

} catch (PDOException $e) {
  $conn->rollBack();
  error_log("DB Error: " . $e->getMessage());
  echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);

} catch (Exception $e) {
  $conn->rollBack();
  error_log("General Error: " . $e->getMessage());
  echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}

exit;
?>
