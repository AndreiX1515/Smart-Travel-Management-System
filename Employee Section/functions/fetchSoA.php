<?php
require "../../conn.php";
session_start();

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure required data is present


// if (!isset($_POST['companyId']) || !isset($_POST['month']) || !isset($_POST['year'])) {
//   echo json_encode(['dataAvailable' => false, 'error' => 'Missing required POST data (companyId, month, year)']);
//   exit;
// }


  $companyId = $_POST['companyId'];
  $month = $_POST['month'];
  $year = $_POST['year'];

  $formattedTotalPriceSum = "0.00";
  $formattedTotalRequestCostSum = "0.00";
  $formattedTotalAmount = "0.00";

  // Get branchAgentCode
  $sql1 = "SELECT branchAgentCode FROM branch WHERE branchId = '$companyId'";
  $result = $conn->query($sql1);

  $businessUnit = "";
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $businessUnit = $row['branchAgentCode'];
  } else {
    echo json_encode(['dataAvailable' => false, 'error' => 'Invalid Company ID']);
    exit;
  }

  // ========== FLIGHT DATA ==========
  $totalPriceSum = 0;
  $count = 1;
  $table1 = '';
  $tableData1 = [];

  $sql2 = "SELECT b.flightId as flightId, CONCAT(f.flightDepartureDate, ' - ', f.returnArrivalDate) AS flightDates, b.pax as pax,
            CASE 
              WHEN a.agentRole = 'Wholeseller' OR cl.clientRole = 'Wholeseller' 
              THEN f.wholesalePrice 
              ELSE f.flightPrice 
            END AS flightPrice, b.totalPrice as totalPrice
          FROM booking b
          LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
          LEFT JOIN client cl ON b.accountType = 'Client' AND b.accountId = cl.accountId
          JOIN flight f ON b.flightId = f.flightId
          WHERE b.agentCode = '$businessUnit' 
            AND MONTH(f.flightDepartureDate) = $month 
            AND YEAR(f.flightDepartureDate) = $year
            AND b.status = 'Confirmed'";

  $res2 = $conn->query($sql2);
  if ($res2 && $res2->num_rows > 0) {
    while ($row = $res2->fetch_assoc()) {
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
        'price' => $formattedFlightPrice,
        'pax' => $row['pax'],
        'total_usd' => '',
        'total_php' => $formattedTotalPrice,
      ];
      $count++;
    }
    $_SESSION['tableData1'] = $tableData1;
    $_SESSION['totalPriceSum'] = $formattedTotalPriceSum;
  } else {
    $_SESSION['totalPriceSum'] = "0.00";
    $table1 = "<tr><td colspan='7'>No data found</td></tr>";
  }

  // ========== REQUEST DATA ==========
  $totalCostSum = 0;
  $handlingFeeCount = 0;
  $handlingFeeTotal = 0;
  $totalRequestCostSum = 0;
  $table2 = '';
  $tableData2 = [];

  $sql3 = "SELECT b.flightId, cd.details, cd.price, SUM(r.pax) AS pax, SUM(r.requestCost) AS requestCost,
            COUNT(CASE WHEN r.handlingFee != 0 THEN 1 ELSE NULL END) AS handlingFeeCount
          FROM `request` r
          JOIN concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
          JOIN booking b ON r.transactNo = b.transactNo
          JOIN flight f ON b.flightId = f.flightId
          WHERE r.requestStatus = 'Confirmed' 
            AND MONTH(f.flightDepartureDate) = $month 
            AND YEAR(f.flightDepartureDate) = $year
            AND b.agentCode = '$businessUnit' 
          GROUP BY b.flightId, cd.details, cd.price, r.concernDetailsId";

  $res3 = $conn->query($sql3);
  if ($res3 && $res3->num_rows > 0) {
    while ($row = $res3->fetch_assoc()) {
      $handlingFeeCount += $row['handlingFeeCount'];
      $totalCostSum += $row['requestCost'];
      $formattedRequestPrice = number_format($row['price'], 2);
      $formattedRequestCost = number_format($row['requestCost'], 2);

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
        'total_php' => $formattedRequestCost
      ];
      $count++;
    }

    if ($handlingFeeCount > 0) {
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
        'total_php' => $formattedHandlingFeeTotal
      ];

      $totalRequestCostSum = $totalCostSum + $handlingFeeTotal;
    } else {
      $totalRequestCostSum = $totalCostSum;
    }

    $formattedTotalRequestCostSum = number_format($totalRequestCostSum, 2);
    $_SESSION['tableData2'] = $tableData2;
    $_SESSION['totalRequestCost'] = $formattedTotalRequestCostSum;
  } else {
    $_SESSION['totalRequestCost'] = "0.00";
    $table2 = "<tr><td colspan='7'>No request data found</td></tr>";
  }

  // ========== PAYMENT DATA ==========
  $totalAmount = 0;
  $table3 = '';
  $tableData3 = [];

  $sql4 = "SELECT DISTINCT p.transactNo AS transactNo, p.paymentType AS paymentType, p.amount AS amount, 
              DATE(p.paymentDate) AS paymentDate
            FROM payment p
            JOIN booking b ON b.transactNo = p.transactNo
            JOIN flight f ON b.flightId = f.flightId
            WHERE p.paymentStatus = 'Approved' 
              AND MONTH(f.flightDepartureDate) = $month 
              AND YEAR(f.flightDepartureDate) = $year
              AND b.agentCode = '$businessUnit'";

  $res4 = $conn->query($sql4);
  if ($res4 && $res4->num_rows > 0) {
    while ($row = $res4->fetch_assoc()) {
      $totalAmount += $row['amount'];
      $formattedAmount = number_format($row['amount'], 2);
      $formattedDate = DateTime::createFromFormat('Y-m-d', $row['paymentDate'])->format('F d, Y');

      $table3 .= "<tr>
                    <td>$count</td>
                    <td>{$row['paymentType']} - $formattedDate</td>
                    <td></td><td></td><td></td><td></td>
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
    $_SESSION['tableData3'] = $tableData3;
    $_SESSION['totalAmount'] = $formattedTotalAmount;
  } else {
    $_SESSION['totalAmount'] = "0.00";
    $table3 = "<tr><td colspan='7'>No Payment data found</td></tr>";
  }

  // ========== BALANCE ==========
  $balance = ($totalPriceSum + $totalRequestCostSum) - $totalAmount;
  $formattedBalance = number_format($balance, 2);
  $_SESSION['balance'] = $formattedBalance;

  // ========== FINAL OUTPUT ==========
  $dataAvailable = ($res2->num_rows > 0 || $res3->num_rows > 0 || $res4->num_rows > 0);

  echo json_encode([
    'dataAvailable' => $dataAvailable,
    'flights' => ['rows' => $table1, 'subtotalPHP' => $formattedTotalPriceSum, 'subtotalUSD' => ''],
    'requests' => ['rows' => $table2, 'subtotalPHP' => $formattedTotalRequestCostSum, 'subtotalUSD' => ''],
    'payments' => ['rows' => $table3, 'subtotalPHP' => $formattedTotalAmount, 'subtotalUSD' => ''],
    'balance' => ['php' => $formattedBalance, 'usd' => '']
  ]);


?>
