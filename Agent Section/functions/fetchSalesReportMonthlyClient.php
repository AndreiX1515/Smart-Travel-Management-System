<?php
require "../../conn.php";
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $month = $_POST['month'] ?? '';
  $year = $_POST['year'] ?? '';
  $clientIdSelect = $_POST['selectedClient'] ?? '';
  $totalFlightAmount = 0;
  $totalRequestAmount = 0;

  if (!empty($month) && !empty($year) && !empty($clientIdSelect)) {
    // Step 1: Get all flightIds for the given month/year
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
      // Step 2: Get client bookings with requests
      $placeholders = implode(',', array_fill(0, count($flightIds), '?'));
      $types = str_repeat('i', count($flightIds)) . 's';

      $sql = "SELECT b.transactNo, b.pax AS bookingPax, b.totalPrice, f.flightDepartureDate, f.returnArrivalDate,
                     a.fName, a.mName, a.lName,
                     r.pax AS requestPax, r.requestCost, cd.details
              FROM booking b
              JOIN flight f ON f.flightId = b.flightId
              JOIN client a ON a.accountId = b.accountId
              LEFT JOIN request r ON r.transactNo = b.transactNo
              LEFT JOIN concerndetails cd ON cd.concernDetailsId = r.concernDetailsId
              WHERE b.flightId IN ($placeholders) AND a.clientId = ? AND b.accountType = 'Client' 
                AND (b.status= 'Confirmed' OR b.status='Reserved') AND r.requestStatus = 'Confirmed'";

      $stmt = $conn->prepare($sql);
      $params = array_merge($flightIds, [$clientIdSelect]);
      $stmt->bind_param($types, ...$params);
      $stmt->execute();
      $result = $stmt->get_result();

      $reportData = [];

      while ($row = $result->fetch_assoc()) {
        $txn = $row['transactNo'];

        if (!isset($reportData[$txn])) {
          $clientFullName = trim("{$row['fName']} {$row['mName']} {$row['lName']}");
          $flightRange = date("F j, Y", strtotime($row['flightDepartureDate'])) . " - " . date("F j, Y", strtotime($row['returnArrivalDate']));

          $reportData[$txn] = [
            'name' => $clientFullName,
            'flightDate' => $flightRange,
            'pax' => (int)$row['bookingPax'],
            'amount' => '₱ ' . number_format($row['totalPrice'], 2),
            'requests' => []
          ];

          $totalFlightAmount += (float)$row['totalPrice'];
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
    $response['error'] = 'Missing month, year, or client selection.';
  }
} else {
  $response['error'] = 'Invalid request method.';
}

echo json_encode($response);
