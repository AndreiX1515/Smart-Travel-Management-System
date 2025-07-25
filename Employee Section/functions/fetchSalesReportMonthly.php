<?php
require "../../conn.php";
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $month = $_POST['month'] ?? '';
  $year = $_POST['year'] ?? '';
  $branchCode = $_POST['branchCode'] ?? '';

  if (!empty($month) && !empty($year) && !empty($branchCode)) {
    // Step 1: Get matching flightId(s) for the given month and year
    $stmt = $conn->prepare("SELECT flightId FROM flight WHERE MONTH(flightDepartureDate) = ? AND YEAR(flightDepartureDate) = ?");
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $flightIds = [];
    while ($row = $result->fetch_assoc()) {
      $flightIds[] = $row['flightId'];
    }
    $stmt->close();

    if (empty($flightIds)) {
      $response['error'] = "No flights found for the selected month.";
    } else {
      // Step 2: Get agent bookings with requests
      $placeholders = implode(',', array_fill(0, count($flightIds), '?'));
      $types = str_repeat('i', count($flightIds)) . 's';

      $sql = "SELECT b.transactNo, b.pax AS bookingPax, b.totalPrice, f.flightDepartureDate, f.returnArrivalDate,
                     IFNULL(a.fName, c.fName) AS fName, IFNULL(a.mName, c.mName) AS mName, IFNULL(a.lName, c.lName) AS lName, b.accountType,
                     r.pax AS requestPax, r.requestCost, cd.details
              FROM booking b
              JOIN flight f ON f.flightId = b.flightId
              LEFT JOIN agent a ON a.accountId = b.accountId
              LEFT JOIN client c ON c.accountId = b.accountId
              LEFT JOIN request r ON r.transactNo = b.transactNo
              LEFT JOIN concerndetails cd ON cd.concernDetailsId = r.concernDetailsId
              WHERE b.flightId IN ($placeholders) AND b.agentCode = ?
                AND (b.status = 'Confirmed' OR b.status = 'Reserved') AND r.requestStatus = 'Confirmed'";

      $stmt = $conn->prepare($sql);
      $params = array_merge($flightIds, [$branchCode]);
      $stmt->bind_param($types, ...$params);
      $stmt->execute();
      $result = $stmt->get_result();

      $reportData = [];
      $totalFlightAmount = 0;
      $totalRequestAmount = 0;

      while ($row = $result->fetch_assoc()) {
        $txn = $row['transactNo'];

        if (!isset($reportData[$txn])) {
          $agentFullName = trim("{$row['fName']} {$row['mName']} {$row['lName']}");
          $flightRange = date("F j, Y", strtotime($row['flightDepartureDate'])) . " - " . date("F j, Y", strtotime($row['returnArrivalDate']));

          $reportData[$txn] = [
            'name' => $agentFullName,
            'accountType' => $row['accountType'],
            'flightDate' => $flightRange,
            'pax' => (int)$row['bookingPax'],
            'amount' => '₱ ' . number_format($row['totalPrice'], 2),
            'requests' => []
          ];

          $totalFlightAmount += (float)$row['totalPrice']; // Sum flight amount once per transaction
        }

        if (!empty($row['details'])) {
          $reportData[$txn]['requests'][] = [
            'type' => $row['details'],
            'pax' => (int)$row['requestPax'],
            'amount' => '₱ ' . number_format($row['requestCost'], 2)
          ];

          $totalRequestAmount += (float)$row['requestCost'];
        }
      }

      $response['data'] = array_values($reportData);
      $response['totalFlightAmount'] = '₱ ' . number_format($totalFlightAmount, 2);
      $response['totalRequestAmount'] = '₱ ' . number_format($totalRequestAmount, 2);
    }
  } else {
    $response['error'] = 'Missing month, year, or branch code.';
  }
} else {
  $response['error'] = 'Invalid request method.';
}

echo json_encode($response);
