<?php
require "../../conn.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $employeeIds     = $_POST['employeeId'] ?? [];
  $packageIds      = $_POST['packageId'] ?? [];
  $wholesalePrices = $_POST['wholesalePrice'] ?? [];
  $retailPrices    = $_POST['retailPrice'] ?? [];
  $landPrices      = $_POST['landPrice'] ?? [];
  $availableSeats  = $_POST['availableSeats'] ?? [];

  $legTypes        = $_POST['legType'] ?? [];
  $origins         = $_POST['origin'] ?? [];
  $airlineIds      = $_POST['airlineId'] ?? [];
  $flightNames     = $_POST['flightName'] ?? [];
  $flightNumbers   = $_POST['flightNumber'] ?? [];
  $departureDates  = $_POST['departureDate'] ?? [];
  $departureTimes  = $_POST['departureTime'] ?? [];
  $arrivalDates    = $_POST['arrivalDate'] ?? [];
  $arrivalTimes    = $_POST['arrivalTime'] ?? [];

  $conn->begin_transaction();
  $insertedTrips = [];

  try {
    foreach ($employeeIds as $tripIndex => $employeeId) {
      $packageId      = $packageIds[$tripIndex];
      $wholesalePrice = floatval($wholesalePrices[$tripIndex]);
      $retailPrice    = floatval($retailPrices[$tripIndex]);
      $landPrice      = floatval($landPrices[$tripIndex]);
      $seats          = intval($availableSeats[$tripIndex]);

      // Normalize employeeId: if "0" or empty → NULL
      $employeeIdRaw = $employeeIds[$tripIndex] ?? null;
      $employeeId    = ($employeeIdRaw && $employeeIdRaw !== "0") ? intval($employeeIdRaw) : null;

      if ($employeeId === null) {
        // Insert with NULL for employeeId
        $stmtTrip = $conn->prepare("INSERT INTO trip (packageId, employeeId, wholesalePrice, retailPrice, landPrice, availableSeats)
                                    VALUES (?, NULL, ?, ?, ?, ?)");
        $stmtTrip->bind_param("diii", $wholesalePrice, $retailPrice, $landPrice, $seats);
        $stmtTrip->bind_param("i", $packageId); // bind separately
      } else {
        // Insert with actual employeeId
        $stmtTrip = $conn->prepare("INSERT INTO trip (packageId, employeeId, wholesalePrice, retailPrice, landPrice, availableSeats)
                                    VALUES (?, ?, ?, ?, ?, ?)");
        $stmtTrip->bind_param("iiddii", $packageId, $employeeId, $wholesalePrice, $retailPrice, $landPrice, $seats);
      }

      if (!$stmtTrip->execute()) {
        throw new Exception("Trip insert failed: " . $stmtTrip->error);
      }
      $tripId = $stmtTrip->insert_id;
      $insertedTrips[] = $tripId;
      $stmtTrip->close();

      // Insert legs
      if (!empty($legTypes[$tripIndex])) {
        foreach ($legTypes[$tripIndex] as $legIdx => $legType) {
          $origin      = $origins[$tripIndex][$legIdx];
          $airlineId   = $airlineIds[$tripIndex][$legIdx];
          $flightName  = $flightNames[$tripIndex][$legIdx];
          $flightNo    = $flightNumbers[$tripIndex][$legIdx];
          $depDate     = $departureDates[$tripIndex][$legIdx];
          $depTime     = $departureTimes[$tripIndex][$legIdx];
          $arrDate     = $arrivalDates[$tripIndex][$legIdx];
          $arrTime     = $arrivalTimes[$tripIndex][$legIdx];

          // Insert flight
          $stmtF = $conn->prepare("INSERT INTO flight (airlineId, flightNumber, origin, flightName, departureDate, 
                                  departureTime, arrivalDate, arrivalTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
          $stmtF->bind_param("isssssss", $airlineId, $flightNo, $origin, $flightName, $depDate, $depTime, $arrDate, $arrTime);

          if (!$stmtF->execute()) {
            throw new Exception("Flight insert failed: " . $stmtF->error);
          }
          $flightId = $stmtF->insert_id;
          $stmtF->close();

          // Link flight to trip
          $stmtTF = $conn->prepare("INSERT INTO tripflight (tripId, flightId, legOrder, legType)
                                    VALUES (?, ?, ?, ?)");
          $legOrder = $legIdx + 1;
          $stmtTF->bind_param("iiis", $tripId, $flightId, $legOrder, $legType);

          if (!$stmtTF->execute()) {
            throw new Exception("TripFlight insert failed: " . $stmtTF->error);
          }
          $stmtTF->close();
        }
      }
    }

    $conn->commit();
    echo json_encode([
      "status" => "success",
      "message" => count($insertedTrips) . " trip(s) saved successfully.",
      "tripIds" => $insertedTrips
    ]);
  } catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
      "status" => "error",
      "message" => "Transaction failed. Nothing was saved.",
      "details" => $e->getMessage()
    ]);
  }
} else {
  http_response_code(405);
  echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
