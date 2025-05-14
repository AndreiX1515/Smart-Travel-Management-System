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
  $cardsJSONData = $payload['cardsJSONData'] ?? [];
  $includesData = $payload['includesData'] ?? [];
  $excludesData = $payload['excludesData'] ?? [];

  // Generate unique code & account ID
  $accountId = $_SESSION['accountId'] ?? 1;

  // Begin transaction early
  $conn->beginTransaction();

  do {
      $voucherCode = "VOUCHER-" . strtoupper(uniqid());

      // Check if voucherCode already exists
      $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM vouchers WHERE voucherCode = ?");
      $stmtCheck->execute([$voucherCode]);
      $exists = $stmtCheck->fetchColumn();
  } 
  
  while ($exists > 0); // Keep generating until unique

  // Insert into vouchers table
  $stmtVoucher = $conn->prepare("INSERT INTO vouchers (accountId, voucherCode) VALUES (?, ?)");
  $stmtVoucher->execute([$accountId, $voucherCode]);

  $voucherId = $conn->lastInsertId();


  
  // 2️⃣ Insert into voucherDetails     
  $stmtDetails = $conn->prepare("INSERT INTO voucherDetails (
      voucherId, sentTo, sentFrom, tourType, attachment,
      tourPeriodStart, tourPeriodEnd, noOfPax
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");


  $stmtDetails->execute([
    $voucherId,
    $voucherDetails['to'] ?? '',
    $voucherDetails['from'] ?? '',
    $voucherDetails['tour'] ?? '',
    $voucherDetails['attachment'] ?? '',
    $voucherDetails['periodStart'] ?? null,
    $voucherDetails['periodEnd'] ?? null,
    $voucherDetails['paxCount'] ?? 0
  ]);

  $cardsJSONData = $payload['cardsJSONData'] ?? [];

  // 3️⃣ Insert into voucherHotels (loop through cardsJSONData)
  if (!empty($cardsJSONData)) {
    // Prepare the insert query
    $stmt = $conn->prepare("
            INSERT INTO voucherDateAndHotels (
                voucherId, startDate, endDate, nights, city, hotel
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");

    // Loop through each section in cardsJSONData (dateAndHotel1, dateAndHotel2, etc.)
    foreach ($cardsJSONData as $hotelKey => $hotelDetails) {

      // Check if the key starts with 'dateAndHotel' (i.e., dateAndHotel1, dateAndHotel2, etc.)
      if (strpos($hotelKey, 'dateAndHotel') === 0) {
        // Insert each section one by one into voucherDateAndHotels
        $stmt->execute([
          $voucherId, // Reference to the voucherId
          $hotelDetails['startDate'], // Start date for the hotel stay
          $hotelDetails['endDate'], // End date for the hotel stay
          $hotelDetails['nights'], // Number of nights
          $hotelDetails['city'], // City for the hotel stay
          $hotelDetails['hotel'] // Hotel name
        ]);
      }
    }
  } else {
    
  }



  // 4️⃣ Insert into voucherIncludes
  if (!empty($includesData)) {
    $stmtInclude = $conn->prepare("INSERT INTO voucherIncludes (voucherId, includeItemId) VALUES (?, ?)");

    foreach ($includesData as $includeItem) {
      $value = $includeItem['value'] ?? '';
      // Skip if "No Includes" (value = 0) or blank
      if (!empty($value) && $value !== '0') {
        $stmtInclude->execute([$voucherId, $value]);
      }
    }
  }

  // 5️⃣ Insert into voucherExcludes
  if (!empty($excludesData)) {
    $stmtExclude = $conn->prepare("INSERT INTO voucherExcludes (voucherId, excludeItemId) VALUES (?, ?)");

    foreach ($excludesData as $excludeItem) {
      $value = $excludeItem['value'] ?? '';
      // Skip if "No Excludes" (value = 0) or blank
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