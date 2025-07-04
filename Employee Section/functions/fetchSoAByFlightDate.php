<?php
require "../../conn.php";
session_start();

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Validate inputs
// if (!isset($_POST['flightId']) || !isset($_POST['companyId'])) {
//   echo json_encode(['dataAvailable' => false, 'error' => 'Missing flightId or companyId']);
//   exit;
// }

if (isset($_POST['companyId']) && isset($_POST['flightId'])) 
{
  $flightDepartureDate = $_POST['flightId'];
$branchId = (int) $_POST['companyId'];

// Initialize variables
$flightIds = [];
$transactNumbers = [];
$totalPriceSum = 0;
$totalCostSum = 0;
$handlingFeeCount = 0;
$handlingFeeTotal = 0;
$totalAmount = 0;
$count = 1;

$table1 = '';
$table2 = '';
$table3 = '';
$tableData1 = [];
$tableData2 = [];
$tableData3 = [];

// Get branchAgentCode from branch
$sql1 = "SELECT branchAgentCode FROM branch WHERE branchId = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $branchId);
$stmt1->execute();
$result1 = $stmt1->get_result();
$businessUnit = ($result1 && $row = $result1->fetch_assoc()) ? $row['branchAgentCode'] : null;

if (!$businessUnit) {
  echo json_encode(['dataAvailable' => false, 'error' => 'Invalid Branch']);
  exit;
}

// Fetch all flight IDs with same departure date
$sqlFlights = "SELECT flightId FROM flight WHERE flightDepartureDate = ?";
$stmtFlights = $conn->prepare($sqlFlights);
$stmtFlights->bind_param("s", $flightDepartureDate);
$stmtFlights->execute();
$resultFlights = $stmtFlights->get_result();

while ($row = $resultFlights->fetch_assoc()) {
  $flightIds[] = $row['flightId'];
}

if (empty($flightIds)) {
  echo json_encode(['dataAvailable' => false, 'error' => 'No flights found for the given date']);
  exit;
}

// Build dynamic placeholders for IN clause
$placeholders = implode(',', array_fill(0, count($flightIds), '?'));
$sql3 = "SELECT b.flightId, CONCAT(f.flightDepartureDate, ' - ', f.returnArrivalDate) AS flightDates, b.pax, b.transactNo,
        CASE WHEN a.agentRole = 'Wholeseller' OR cl.clientRole = 'Wholeseller' THEN f.wholesalePrice ELSE f.flightPrice END AS flightPrice, 
        b.totalPrice
        FROM booking b
        LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
        LEFT JOIN client cl ON b.accountType = 'Client' AND b.accountId = cl.accountId
        JOIN flight f ON b.flightId = f.flightId
        WHERE b.agentCode = ? AND b.status = 'Confirmed' AND f.flightId IN ($placeholders)";
$stmt3 = $conn->prepare($sql3);

$params = array_merge([$businessUnit], $flightIds);
$types = str_repeat('i', count($flightIds));
$stmt3->bind_param("s$types", ...$params);
$stmt3->execute();
$res3 = $stmt3->get_result();

while ($row = $res3->fetch_assoc()) {
  $transactNumbers[] = $row['transactNo'];
  $totalPriceSum += $row['totalPrice'];
  $formattedFlightPrice = number_format($row['flightPrice'], 2);
  $formattedTotalPrice = number_format($row['totalPrice'], 2);
  $table1 .= "<tr><td>$count</td><td>{$row['flightDates']}</td><td></td><td>₱ $formattedFlightPrice</td><td>{$row['pax']}</td><td></td><td>₱ $formattedTotalPrice</td></tr>";
  $tableData1[] = [
    'no' => $count,
    'contents' => $row['flightDates'],
    'flightId' => $row['flightId'],
    'price' => $formattedFlightPrice,
    'pax' => $row['pax'],
    'total_usd' => '',
    'total_php' => $formattedTotalPrice
  ];
  $count++;
}
$_SESSION['tableData1'] = $tableData1;
$_SESSION['totalPriceSum'] = number_format($totalPriceSum, 2);

// Handle empty transactions
if (empty($transactNumbers)) {
  echo json_encode([
    'dataAvailable' => ($res3->num_rows > 0),
    'flights' => ['rows' => $table1, 'subtotalPHP' => number_format($totalPriceSum, 2)],
    'requests' => ['rows' => '', 'subtotalPHP' => '0.00'],
    'payments' => ['rows' => '', 'subtotalPHP' => '0.00'],
    'balance' => ['php' => number_format($totalPriceSum, 2)]
  ]);
  exit;
}

$transactPlaceholders = "'" . implode("','", array_map([$conn, 'real_escape_string'], $transactNumbers)) . "'";

// Request Table
$sql4 = "SELECT cd.details, cd.price, SUM(r.pax) AS pax, SUM(r.requestCost) AS requestCost,
         COUNT(CASE WHEN r.handlingFee != 0 THEN 1 ELSE NULL END) AS handlingFeeCount
         FROM request r
         JOIN concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
         JOIN booking b ON r.transactNo = b.transactNo
         WHERE r.requestStatus = 'Confirmed' AND r.transactNo IN ($transactPlaceholders)
         GROUP BY cd.details, cd.price";
$res4 = $conn->query($sql4);

while ($row = $res4->fetch_assoc()) {
  $totalCostSum += $row['requestCost'];
  $handlingFeeCount += $row['handlingFeeCount'];
  $formattedRequestPrice = number_format($row['price'], 2);
  $formattedRequestCost = number_format($row['requestCost'], 2);
  $table2 .= "<tr><td>$count</td><td>{$row['details']}</td><td></td><td>₱ $formattedRequestPrice</td><td>{$row['pax']}</td><td></td><td>₱ $formattedRequestCost</td></tr>";
  $tableData2[] = [
    'no' => $count,
    'contents' => $row['details'],
    'price' => $formattedRequestPrice,
    'pax' => $row['pax'],
    'total_usd' => '',
    'total_php' => $formattedRequestCost
  ];
  $count++;
}

// Add handling fee row if applicable
if ($handlingFeeCount > 0) {
  $handlingFeeTotal = $handlingFeeCount * 100;
  $table2 .= "<tr><td>$count</td><td>Handling Fee</td><td></td><td>₱ 100.00</td><td>$handlingFeeCount</td><td></td><td>₱ " . number_format($handlingFeeTotal, 2) . "</td></tr>";
  $tableData2[] = [
    'no' => $count,
    'contents' => 'Handling Fee',
    'price' => '100.00',
    'pax' => $handlingFeeCount,
    'total_usd' => '',
    'total_php' => number_format($handlingFeeTotal, 2)
  ];
  $count++;
}

$totalRequestCostSum = $totalCostSum + $handlingFeeTotal;
$_SESSION['tableData2'] = $tableData2;
$_SESSION['totalRequestCost'] = number_format($totalRequestCostSum, 2);

// Payment Table
$sql5 = "SELECT p.paymentType, p.amount, p.paymentDate FROM payment p
         JOIN booking b ON p.transactNo = b.transactNo
         WHERE p.paymentStatus = 'Approved' AND p.transactNo IN ($transactPlaceholders)";
$res5 = $conn->query($sql5);

while ($row = $res5->fetch_assoc()) {
  $totalAmount += $row['amount'];
  $formattedAmount = number_format($row['amount'], 2);
  $formattedDate = date("F d, Y", strtotime($row['paymentDate']));
  $table3 .= "<tr><td>$count</td><td>{$row['paymentType']} - {$formattedDate}</td><td></td><td></td><td></td><td></td><td>₱ $formattedAmount</td></tr>";
  $tableData3[] = [
    'no' => $count,
    'contents' => $row['paymentType'] . ' - ' . $formattedDate,
    'price' => '',
    'pax' => '',
    'total_usd' => '',
    'total_php' => $formattedAmount
  ];
  $count++;
}
$_SESSION['tableData3'] = $tableData3;
$_SESSION['totalAmount'] = number_format($totalAmount, 2);

// Final Balance
$balance = ($totalPriceSum + $totalRequestCostSum) - $totalAmount;
$_SESSION['balance'] = number_format($balance, 2);

// Response
$response = [
  'dataAvailable' => ($res3->num_rows > 0 || $res4->num_rows > 0 || $res5->num_rows > 0),
  'flights' => [
    'rows' => $table1,
    'subtotalPHP' => number_format($totalPriceSum, 2),
    'subtotalUSD' => ''
  ],
  'requests' => [
    'rows' => $table2,
    'subtotalPHP' => number_format($totalRequestCostSum, 2),
    'subtotalUSD' => ''
  ],
  'payments' => [
    'rows' => $table3,
    'subtotalPHP' => number_format($totalAmount, 2),
    'subtotalUSD' => ''
  ],
  'balance' => [
    'php' => number_format($balance, 2),
    'usd' => ''
  ]
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;

}


?>
