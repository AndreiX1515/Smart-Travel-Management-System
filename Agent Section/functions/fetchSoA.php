<?php
require "../../conn.php";
session_start();

// ✅ Always return JSON
header('Content-Type: application/json');

// For debugging during development — remove in production
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['companyId']) && isset($_POST['month']) && isset($_POST['year'])) {
  $companyId = (int) $_POST['companyId'];
  $month = (int) $_POST['month'];
  $year = (int) $_POST['year'];

  // Setup base JSON structure
  $soaData = [
    'dataAvailable' => false,
    'flights' => ['rows' => '', 'subtotalPHP' => '0.00'],
    'requests' => ['rows' => '', 'subtotalPHP' => '0.00'],
    'payments' => ['rows' => '', 'subtotalPHP' => '0.00', 'subtotalUSD' => '0.00'],
    'balance' => ['php' => '0.00', 'usd' => '0.00']
  ];

  $flightRows = '';
  $requestRows = '';
  $paymentRows = '';
  $transactNumbers = [];
  $rowCounter = 1;
  $flightSubtotal = 0;
  $requestSubtotal = 0;
  $handlingFeeTotal = 0;
  $paymentTotal = 0;

  // ✅ Fetch Flights
  $sql = "
    SELECT b.flightId, b.transactNo, b.pax, f.wholesalePrice, f.flightPrice,
           f.flightDepartureDate, f.returnArrivalDate,
           b.totalPrice
    FROM booking b
    JOIN client cl ON b.accountId = cl.accountId
    JOIN company c ON cl.companyId = c.companyId
    JOIN flight f ON b.flightId = f.flightId
    WHERE cl.companyId = ?
      AND b.status = 'Confirmed'
      AND MONTH(f.flightDepartureDate) = ?
      AND YEAR(f.flightDepartureDate) = ?
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iii", $companyId, $month, $year);
  $stmt->execute();
  $res = $stmt->get_result();

  while ($row = $res->fetch_assoc()) {
    $transactNumbers[] = $row['transactNo'];
    $flightSubtotal += $row['totalPrice'];
    $formattedPrice = number_format($row['flightPrice'], 2);
    $formattedTotal = number_format($row['totalPrice'], 2);
    $dates = $row['flightDepartureDate'] . " - " . $row['returnArrivalDate'];

    $flightRows .= "<tr>
      <td>{$rowCounter}</td>
      <td>{$dates}</td>
      <td></td>
      <td>₱ {$formattedPrice}</td>
      <td>{$row['pax']}</td>
      <td></td>
      <td>₱ {$formattedTotal}</td>
    </tr>";
    $rowCounter++;
  }
  $soaData['flights']['rows'] = $flightRows;
  $soaData['flights']['subtotalPHP'] = number_format($flightSubtotal, 2);

  // ✅ Fetch Requests
  if (!empty($transactNumbers)) {
    $transactsIn = "'" . implode("','", array_map([$conn, 'real_escape_string'], $transactNumbers)) . "'";
    $sqlReq = "
      SELECT cd.details, cd.price, SUM(r.pax) as pax, SUM(r.requestCost) as requestCost,
             COUNT(CASE WHEN r.handlingFee != 0 THEN 1 ELSE NULL END) AS handlingFeeCount
      FROM request r
      JOIN concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
      JOIN booking b ON r.transactNo = b.transactNo
      WHERE r.transactNo IN ({$transactsIn}) AND r.requestStatus = 'Confirmed'
      GROUP BY cd.details, cd.price
    ";
    $resReq = $conn->query($sqlReq);

    while ($row = $resReq->fetch_assoc()) {
      $requestSubtotal += $row['requestCost'];
      $formattedRequestPrice = number_format($row['price'], 2);
      $formattedRequestCost = number_format($row['requestCost'], 2);

      $requestRows .= "<tr>
        <td>{$rowCounter}</td>
        <td>{$row['details']}</td>
        <td></td>
        <td>₱ {$formattedRequestPrice}</td>
        <td>{$row['pax']}</td>
        <td></td>
        <td>₱ {$formattedRequestCost}</td>
      </tr>";

      if ($row['handlingFeeCount'] > 0) {
        $fee = $row['handlingFeeCount'] * 100;
        $handlingFeeTotal += $fee;
        $requestRows .= "<tr>
          <td>{$rowCounter}</td>
          <td>Handling Fee</td>
          <td></td>
          <td>₱ 100.00</td>
          <td>{$row['handlingFeeCount']}</td>
          <td></td>
          <td>₱ " . number_format($fee, 2) . "</td>
        </tr>";
        $rowCounter++;
      }
      $rowCounter++;
    }

    $soaData['requests']['rows'] = $requestRows;
    $soaData['requests']['subtotalPHP'] = number_format($requestSubtotal + $handlingFeeTotal, 2);
  }

  // ✅ Fetch Payments
  if (!empty($transactNumbers)) {
    $paymentSQL = "
      SELECT p.transactNo, p.paymentType, p.amount, p.paymentDate
      FROM payment p
      JOIN booking b ON b.transactNo = p.transactNo
      WHERE p.paymentStatus = 'Approved'
        AND p.transactNo IN ({$transactsIn})
    ";
    $resPay = $conn->query($paymentSQL);
    while ($row = $resPay->fetch_assoc()) {
      $paymentTotal += $row['amount'];
      $formattedAmount = number_format($row['amount'], 2);
      $formattedDate = date("F d, Y", strtotime($row['paymentDate']));

      $paymentRows .= "<tr>
        <td>{$rowCounter}</td>
        <td>{$row['paymentType']} - {$formattedDate}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>₱ {$formattedAmount}</td>
      </tr>";
      $rowCounter++;
    }
    $soaData['payments']['rows'] = $paymentRows;
    $soaData['payments']['subtotalPHP'] = number_format($paymentTotal, 2);
  }

  // ✅ Calculate Balance
  $soaData['balance']['php'] = number_format(($flightSubtotal + $requestSubtotal + $handlingFeeTotal) - $paymentTotal, 2);
  $soaData['dataAvailable'] = ($flightSubtotal > 0 || $requestSubtotal > 0 || $paymentTotal > 0);

  // ✅ Return JSON only
  echo json_encode($soaData);
  exit;
}

// ❌ If inputs not set, return error JSON
echo json_encode([
  'dataAvailable' => false,
  'error' => 'Missing required POST data (companyId, month, year)'
]);
exit;
?>
