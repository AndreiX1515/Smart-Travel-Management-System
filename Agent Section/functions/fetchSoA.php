<?php
require "../../conn.php";
session_start();

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_POST['companyId']) || !isset($_POST['month']) || !isset($_POST['year'])) {
  echo json_encode(['dataAvailable' => false, 'error' => 'Missing required POST data (companyId, month, year)']);
  exit;
}

$companyId = (int) $_POST['companyId'];
$month = (int) $_POST['month'];
$year = (int) $_POST['year'];

$formattedTotalPriceSum = "0.00";
$formattedTotalRequestCostSum = "0.00";
$formattedTotalAmount = "0.00";

$rowCounter = 1;
$flightSubtotal = 0;
$requestSubtotal = 0;
$handlingFeeTotal = 0;
$paymentTotal = 0;

$table1 = '';
$table2 = '';
$table3 = '';
$tableData1 = [];
$tableData2 = [];
$tableData3 = [];
$transactNumbers = [];

$sql = "SELECT b.flightId, b.transactNo, b.pax, f.wholesalePrice, f.flightPrice, f.flightDepartureDate, f.returnArrivalDate, b.totalPrice
        FROM booking b
        JOIN client cl ON b.accountId = cl.accountId
        JOIN company c ON cl.companyId = c.companyId
        JOIN flight f ON b.flightId = f.flightId
        WHERE cl.companyId = ?
          AND b.status = 'Confirmed'
          AND MONTH(f.flightDepartureDate) = ?
          AND YEAR(f.flightDepartureDate) = ?";
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
  $table1 .= "<tr><td>{$rowCounter}</td><td>{$dates}</td><td></td><td>₱ {$formattedPrice}</td><td>{$row['pax']}</td><td></td><td>₱ {$formattedTotal}</td></tr>";
  $tableData1[] = [
    'no' => $rowCounter,
    'contents' => $dates,
    'flightId' => $row['flightId'],
    'price' => $formattedPrice,
    'pax' => $row['pax'],
    'total_usd' => '',
    'total_php' => $formattedTotal
  ];
  $rowCounter++;
}

$_SESSION['tableData1'] = $tableData1;
$_SESSION['totalPriceSum'] = number_format($flightSubtotal, 2);

if (empty($transactNumbers)) {
  echo json_encode([
    'dataAvailable' => false,
    'flights' => ['rows' => '<tr><td colspan="7">No flight data found</td></tr>', 'subtotalPHP' => '0.00'],
    'requests' => ['rows' => '<tr><td colspan="7">No request data found</td></tr>', 'subtotalPHP' => '0.00'],
    'payments' => ['rows' => '<tr><td colspan="7">No Payment data found</td></tr>', 'subtotalPHP' => '0.00'],
    'balance' => ['php' => '0.00', 'usd' => '']
  ]);
  exit;
}

$transactIn = "'" . implode("','", array_map([$conn, 'real_escape_string'], $transactNumbers)) . "'";

$sqlReq = "SELECT cd.details, cd.price, SUM(r.pax) as pax, SUM(r.requestCost) as requestCost,
                  COUNT(CASE WHEN r.handlingFee != 0 THEN 1 ELSE NULL END) AS handlingFeeCount
           FROM request r
           JOIN concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
           JOIN booking b ON r.transactNo = b.transactNo
           WHERE r.transactNo IN ({$transactIn}) AND r.requestStatus = 'Confirmed'
           GROUP BY cd.details, cd.price";
$resReq = $conn->query($sqlReq);
while ($row = $resReq->fetch_assoc()) {
  $requestSubtotal += $row['requestCost'];
  $formattedRequestPrice = number_format($row['price'], 2);
  $formattedRequestCost = number_format($row['requestCost'], 2);
  $table2 .= "<tr><td>{$rowCounter}</td><td>{$row['details']}</td><td></td><td>₱ {$formattedRequestPrice}</td><td>{$row['pax']}</td><td></td><td>₱ {$formattedRequestCost}</td></tr>";
  $tableData2[] = [
    'no' => $rowCounter,
    'contents' => $row['details'],
    'price' => $formattedRequestPrice,
    'pax' => $row['pax'],
    'total_usd' => '',
    'total_php' => $formattedRequestCost
  ];
  $rowCounter++;
  if ($row['handlingFeeCount'] > 0) {
    $fee = $row['handlingFeeCount'] * 100;
    $handlingFeeTotal += $fee;
    $formattedFee = number_format($fee, 2);
    $table2 .= "<tr><td>{$rowCounter}</td><td>Handling Fee</td><td></td><td>₱ 100.00</td><td>{$row['handlingFeeCount']}</td><td></td><td>₱ {$formattedFee}</td></tr>";
    $tableData2[] = [
      'no' => $rowCounter,
      'contents' => 'Handling Fee',
      'price' => '100.00',
      'pax' => $row['handlingFeeCount'],
      'total_usd' => '',
      'total_php' => $formattedFee
    ];
    $rowCounter++;
  }
}

$requestTotal = $requestSubtotal + $handlingFeeTotal;
$_SESSION['tableData2'] = $tableData2;
$_SESSION['totalRequestCost'] = number_format($requestTotal, 2);

$sqlPay = "SELECT p.paymentType, p.amount, p.paymentDate
           FROM payment p
           JOIN booking b ON p.transactNo = b.transactNo
           WHERE p.paymentStatus = 'Approved' AND p.transactNo IN ({$transactIn})";
$resPay = $conn->query($sqlPay);
while ($row = $resPay->fetch_assoc()) {
  $paymentTotal += $row['amount'];
  $formattedAmount = number_format($row['amount'], 2);
  $formattedDate = date("F d, Y", strtotime($row['paymentDate']));
  $table3 .= "<tr><td>{$rowCounter}</td><td>{$row['paymentType']} - {$formattedDate}</td><td></td><td></td><td></td><td></td><td>₱ {$formattedAmount}</td></tr>";
  $tableData3[] = [
    'no' => $rowCounter,
    'contents' => $row['paymentType'] . ' - ' . $formattedDate,
    'price' => '',
    'pax' => '',
    'total_usd' => '',
    'total_php' => $formattedAmount
  ];
  $rowCounter++;
}

$_SESSION['tableData3'] = $tableData3;
$_SESSION['totalAmount'] = number_format($paymentTotal, 2);

$balance = ($flightSubtotal + $requestTotal) - $paymentTotal;
$_SESSION['balance'] = number_format($balance, 2);

echo json_encode([
  'dataAvailable' => ($flightSubtotal > 0 || $requestTotal > 0 || $paymentTotal > 0),
  'flights' => ['rows' => $table1, 'subtotalPHP' => number_format($flightSubtotal, 2), 'subtotalUSD' => ''],
  'requests' => ['rows' => $table2, 'subtotalPHP' => number_format($requestTotal, 2), 'subtotalUSD' => ''],
  'payments' => ['rows' => $table3, 'subtotalPHP' => number_format($paymentTotal, 2), 'subtotalUSD' => ''],
  'balance' => ['php' => number_format($balance, 2), 'usd' => '']
]);
exit;
