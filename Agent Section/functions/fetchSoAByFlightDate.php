<?php
// Connect to your database
require "../../conn.php"; // Include the DB connection
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['flightId'])) 
{
  $companyId = $_POST['companyId'];
  $flightDepartureDate = $_POST['flightId'];

  $formattedTotalPriceSum = "0.00";
  $formattedTotalRequestCostSum = "0.00";
  $formattedTotalAmount = "0.00";
  $formattedBalance = "0.00";

  $sql1 = "SELECT c.companyName, b.branchAgentCode
           FROM company c 
           JOIN branch b ON c.branchId = b.branchId
           WHERE c.companyId = $companyId";
  $result = $conn->query($sql1);

  $businessUnit = "";
  if ($result && $result->num_rows > 0) 
  {
    $row = $result->fetch_assoc();
    $businessUnit = $row['branchAgentCode'];
    $_SESSION['companyName'] = $row['companyName'];
  }
  else
  {
    $businessUnit = null;
  }

  $flightIds = [];
  $sqlFlights = "SELECT flightId FROM flight WHERE flightDepartureDate = '$flightDepartureDate'";
  $resultFlights = $conn->query($sqlFlights);

  while ($row = $resultFlights->fetch_assoc()) 
  {
    $flightIds[] = $row['flightId'];
  }

  $flightIdsString = implode(',', $flightIds);
  $transactNumbers = [];
  $totalPriceSum = 0;
  $count = 1;
  $table1 = '';
  $tableData1 = [];

  if (!empty($flightIdsString)) 
  {
    $sql3 = "SELECT b.flightId as flightId, CONCAT(f.flightDepartureDate, ' - ', f.returnArrivalDate) AS flightDates, b.pax as pax, b.transactNo,
                CASE 
                  WHEN cl.clientRole = 'Wholeseller' THEN f.wholesalePrice 
                  ELSE f.flightPrice 
                END AS flightPrice, b.totalPrice as totalPrice
              FROM booking b
              JOIN client cl ON b.accountType = 'Client' AND b.accountId = cl.accountId
              JOIN company c ON cl.companyId = c.companyId
              JOIN flight f ON b.flightId = f.flightId
              WHERE b.agentCode = '$businessUnit' 
                AND f.flightId IN ($flightIdsString)
                AND b.status = 'Confirmed'
                AND cl.companyId = $companyId
              ORDER BY f.flightId";

    $res3 = $conn->query($sql3);

    if ($res3 && $res3->num_rows > 0) 
    {
      while ($row = $res3->fetch_assoc()) 
      {
        $transactNumbers[] = $row['transactNo'];
        $totalPriceSum += $row['totalPrice'];

        $formattedFlightPrice = number_format($row['flightPrice'], 2);
        $formattedTotalPrice = number_format($row['totalPrice'], 2);
        $formattedTotalPriceSum = number_format($totalPriceSum, 2);

        $table1 .= "<tr>
                      <td>$count</td>
                      <td>{$row['flightDates']}</td>
                      <td></td>
                      <td>₱ $formattedFlightPrice</td>
                      <td>{$row['pax']}</td>
                      <td></td>
                      <td>₱ $formattedTotalPrice</td>
                    </tr>";

        $tableData1[] = [
          'no' => $count,
          'contents' => $row['flightDates'],
          'flightId' => $row['flightId'],
          'price' => $formattedFlightPrice,
          'pax' => $row['pax'],
          'total_usd' => '',
          'total_php' => $formattedTotalPrice,
        ];

        $count++;
      }

      $table1 .= "<tr>
        <td>$count</td>
        <td><strong>Subtotal:</strong></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>₱ $formattedTotalPriceSum</strong></td>
      </tr>";

      $tableData1[] = [
        'no' => $count,
        'contents' => 'Subtotal:',
        'price' => '',
        'pax' => '',
        'total_usd' => '',
        'total_php' => $formattedTotalPriceSum
      ];

      $count++;
      $_SESSION['tableData1'] = $tableData1;
      $_SESSION['totalPriceSum'] = $formattedTotalPriceSum;
    } 
    else 
    {
      $_SESSION['totalPriceSum'] = "0.00";
      $table1 = "<tr><td colspan='7'>No flight bookings found</td></tr>";
    }
  } 
  else 
  {
    $_SESSION['totalPriceSum'] = "0.00";
    $table1 = "<tr><td colspan='7'>No flight data found</td></tr>";
  }

  $transactNoString = "'" . implode("','", $transactNumbers) . "'";

  $totalCostSum = 0;
  $handlingFeeCount = 0;
  $handlingFeeTotal = 0;
  $totalRequestCostSum = 0;
  $table2 = '';
  $tableData2 = [];

  $sql4 = "SELECT b.flightId, cd.details, cd.price, SUM(r.pax) AS pax, SUM(r.requestCost) AS requestCost,
            COUNT(CASE WHEN r.handlingFee != 0 THEN 1 ELSE NULL END) AS handlingFeeCount
          FROM `request` r
          JOIN concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
          JOIN booking b ON r.transactNo = b.transactNo
          JOIN flight f ON b.flightId = f.flightId
          WHERE r.requestStatus = 'Confirmed' AND r.transactNo IN ($transactNoString)
          GROUP BY b.flightId, cd.details, cd.price, r.concernDetailsId";

  $res4 = $conn->query($sql4);

  if ($res4->num_rows > 0) 
  {
    while ($row = $res4->fetch_assoc()) 
    {
      $handlingFeeCount += $row['handlingFeeCount'];
      $totalCostSum += $row['requestCost'];
      $formattedRequestPrice = number_format($row['price'], 2);
      $formattedRequestCost = number_format($row['requestCost'], 2);
      $formattedRequestCostSum = number_format($totalCostSum, 2);

      $table2 .= "<tr>
                    <td>$count</td>
                    <td>{$row['details']}</td>
                    <td></td>
                    <td>₱ $formattedRequestPrice</td>
                    <td>{$row['pax']}</td>
                    <td></td>
                    <td>₱ $formattedRequestCost</td>
                  </tr>";

      $tableData2[] = [
        'no' => $count,
        'contents' => $row['details'],
        'price' => $formattedRequestPrice,
        'pax' => $row['pax'],
        'total_usd' => '',
        'total_php' => $formattedRequestCost,
      ];

      $count++;
    }

    if ($handlingFeeCount > 0) 
    {
      $handlingFeeTotal = $handlingFeeCount * 100;
      $formattedHandlingFeeTotal = number_format($handlingFeeTotal, 2);

      $table2 .= "<tr>
                    <td>$count</td>
                    <td>Handling Fee</td>
                    <td></td>
                    <td>₱ 100.00</td>
                    <td>$handlingFeeCount</td>
                    <td></td>
                    <td>₱ $formattedHandlingFeeTotal</td>
                  </tr>";

      $tableData2[] = [
        'no' => $count,
        'contents' => 'Handling Fee',
        'price' => '100.00',
        'pax' => $handlingFeeCount,
        'total_usd' => '',
        'total_php' => $formattedHandlingFeeTotal,
      ];

      $totalRequestCostSum = $totalCostSum + $handlingFeeTotal;
    } 
    else 
    {
      $totalRequestCostSum = $totalCostSum;
    }

    $formattedTotalRequestCostSum = number_format($totalRequestCostSum, 2);

    $table2 .= "<tr>
      <td>$count</td>
      <td><strong>Subtotal:</strong></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td><strong>₱ $formattedRequestCostSum</strong></td>
    </tr>";

    $tableData2[] = [
      'no' => $count,
      'contents' => 'Subtotal:',
      'price' => '',
      'pax' => '',
      'total_usd' => '',
      'total_php' => $formattedRequestCostSum
    ];

    $count++;
    $_SESSION['tableData2'] = $tableData2;
    $_SESSION['totalRequestCost'] = $formattedTotalRequestCostSum;
  } 
  else 
  {
    $table2 = "<tr><td colspan='7'>No request data found</td></tr>";
    $_SESSION['totalRequestCost'] = "0.00";
  }

  $table3 = "";
  $totalAmount = 0;
  $tableData3 = [];

  $sql5 = "SELECT DISTINCT p.transactNo AS transactNo, p.paymentType AS paymentType, p.amount AS amount, 
              DATE(p.paymentDate) AS paymentDate
            FROM payment p
            JOIN booking b ON b.transactNo = p.transactNo
            JOIN flight f ON b.flightId = f.flightId
            WHERE p.paymentStatus = 'Approved' AND p.transactNo IN ($transactNoString)";

  $res5 = $conn->query($sql5);

  if ($res5->num_rows > 0) 
  {
    while ($row = $res5->fetch_assoc()) 
    {
      $totalAmount += $row['amount'];
      $formattedAmount = number_format($row['amount'], 2);
      $formattedDate = DateTime::createFromFormat('Y-m-d', $row['paymentDate'])->format('F d, Y');

      $table3 .= "<tr>
                    <td>$count</td>
                    <td>{$row['paymentType']} - $formattedDate</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>₱ $formattedAmount</td>
                  </tr>";

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

    $formattedTotalAmount = number_format($totalAmount, 2);
    $table3 .= "<tr>
      <td>$count</td>
      <td><strong>Subtotal:</strong></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td><strong>₱ $formattedTotalAmount</strong></td>
    </tr>";

    $tableData3[] = [
      'no' => $count,
      'contents' => 'Subtotal:',
      'price' => '',
      'pax' => '',
      'total_usd' => '',
      'total_php' => $formattedTotalAmount
    ];

    $count++;
    $balance = ($totalPriceSum + $totalRequestCostSum) - $totalAmount;
    $formattedBalance = number_format($balance, 2);
    $_SESSION['balance'] = $formattedBalance;

    $table3 .= "<tr id='soaBalance' class='bg-secondary text-white fw-bold'>
      <td>$count</td>
      <td><strong>BALANCE:</strong></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>₱ $formattedBalance</td>
    </tr>";

    $tableData3[] = [
      'no' => $count,
      'contents' => 'BALANCE:',
      'price' => '',
      'pax' => '',
      'total_usd' => '',
      'total_php' => $formattedBalance
    ];

    $_SESSION['tableData3'] = $tableData3;
    $_SESSION['totalAmount'] = $formattedTotalAmount;
  } 
  else 
  {
    $table3 = "<tr><td colspan='7'>No Payment data found</td></tr>";
    $_SESSION['tableData3'] = [];
    $_SESSION['totalAmount'] = "0.00";
  }

  echo json_encode([
    'dataAvailable' => true,
    'flights' => [
      'rows' => $table1,
      'tableData' => $tableData1,
      'subtotalPHP' => $formattedTotalPriceSum
    ],
    'requests' => [
      'rows' => $table2,
      'tableData' => $tableData2,
      'subtotalPHP' => $formattedTotalRequestCostSum
    ],
    'payments' => [
      'rows' => $table3,
      'tableData' => $tableData3,
      'subtotalPHP' => $formattedTotalAmount
    ],
    'balance' => [
      'php' => $formattedBalance
    ],
    'summary' => [
      'totalFlightCost' => $formattedTotalPriceSum,
      'totalRequestCost' => $formattedTotalRequestCostSum,
      'totalPayments' => $formattedTotalAmount,
      'balance' => $formattedBalance
    ]
  ]);
}?>
