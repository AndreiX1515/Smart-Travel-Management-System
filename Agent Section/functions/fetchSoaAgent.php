<?php
// Connect to your database
require "../../conn.php"; // Include the DB connection
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['month']) && isset($_POST['year'])) 
{
  $agentId = $_POST['agentId'];
  $month = (int) $_POST['month'];
  $year = (int) $_POST['year'];

  $formattedTotalPriceSum = "0.00";
  $formattedTotalRequestCostSum = "0.00";
  $formattedTotalAmount = "0.00";

  $flightIds = [];
  $sqlFlights = "SELECT flightId FROM flight WHERE MONTH(flightDepartureDate) = $month AND YEAR(flightDepartureDate) = $year";
  $resultFlights = $conn->query($sqlFlights);
  while ($row = $resultFlights->fetch_assoc()) {
    $flightIds[] = $row['flightId'];
  }

  $flightIdsString = implode(',', $flightIds);
  $transactNumbers = [];
  $totalPriceSum = 0;
  $count = 1;
  $table1 = '';

  if (!empty($flightIdsString)) {
    $sql3 = "SELECT b.flightId as flightId, CONCAT(f.flightDepartureDate, ' - ', f.returnArrivalDate) AS flightDates, b.pax as pax, b.transactNo,
              CASE
                WHEN b.bookingType = 'Land' THEN f.landPrice
                WHEN a.agentRole = 'Wholeseller' THEN f.wholesalePrice
                ELSE f.flightPrice
              END AS flightPrice, b.totalPrice AS totalPrice
            FROM booking b
            JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
            JOIN company c ON a.companyId = c.companyId
            JOIN flight f ON b.flightId = f.flightId
            WHERE f.flightId IN ($flightIdsString) AND b.status = 'Confirmed' AND b.accountId = $agentId
            ORDER BY f.flightId";

    $res3 = $conn->query($sql3);
    if ($res3 && $res3->num_rows > 0) {
      while ($row = $res3->fetch_assoc()) {
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
        $count++;
      }
    } else {
      $table1 = "<tr><td colspan='7'>No flight bookings found</td></tr>";
    }
  } else {
    $table1 = "<tr><td colspan='7'>No flight data found</td></tr>";
  }

  $transactNoString = "'" . implode("','", $transactNumbers) . "'";

  $totalCostSum = 0;
  $handlingFeeCount = 0;
  $handlingFeeTotal = 0;
  $totalRequestCostSum = 0;
  $table2 = '';

  $sql4 = "SELECT b.flightId, cd.details, cd.price, SUM(r.pax) AS pax, SUM(r.requestCost) AS requestCost,
            COUNT(CASE WHEN r.handlingFee != 0 THEN 1 ELSE NULL END) AS handlingFeeCount
          FROM request r
          JOIN concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
          JOIN booking b ON r.transactNo = b.transactNo
          JOIN flight f ON b.flightId = f.flightId
          WHERE r.requestStatus = 'Confirmed' AND r.transactNo IN ($transactNoString)
          GROUP BY b.flightId, cd.details, cd.price, r.concernDetailsId";

  $res4 = $conn->query($sql4);
  if ($res4->num_rows > 0) {
    while ($row = $res4->fetch_assoc()) {
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
      $totalRequestCostSum = $totalCostSum + $handlingFeeTotal;
    } else {
      $totalRequestCostSum = $totalCostSum;
    }
    $formattedTotalRequestCostSum = number_format($totalRequestCostSum, 2);
  } else {
    $table2 = "<tr><td colspan='7'>No request data found</td></tr>";
  }

  $table3 = "";
  $totalAmount = 0;

  $sql5 = "SELECT DISTINCT p.transactNo AS transactNo, p.paymentType AS paymentType, p.amount AS amount, DATE(p.paymentDate) AS paymentDate
            FROM payment p
            JOIN booking b ON b.transactNo = p.transactNo
            JOIN flight f ON b.flightId = f.flightId
            WHERE p.paymentStatus = 'Approved' AND p.transactNo IN ($transactNoString)";

  $res5 = $conn->query($sql5);
  if ($res5->num_rows > 0) {
    while ($row = $res5->fetch_assoc()) {
      $totalAmount += $row['amount'];
      $formattedTotalAmount = number_format($totalAmount, 2);
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
      $count++;
    }
  } else {
    $table3 = "<tr><td colspan='7'>No Payment data found</td></tr>";
  }

  $balance = ($totalPriceSum + $totalRequestCostSum) - $totalAmount;
  $formattedBalance = number_format($balance, 2);

  $dataAvailable = ($res3->num_rows > 0 || $res4->num_rows > 0);

  echo json_encode([
    'dataAvailable' => $dataAvailable,
    'flights' => [
      'rows' => $table1,
      'subtotalPHP' => $formattedTotalPriceSum,
      'subtotalUSD' => ''
    ],
    'requests' => [
      'rows' => $table2,
      'subtotalPHP' => $formattedTotalRequestCostSum,
      'subtotalUSD' => ''
    ],
    'payments' => [
      'rows' => $table3,
      'subtotalPHP' => $formattedTotalAmount,
      'subtotalUSD' => ''
    ],
    'balance' => [
      'php' => $formattedBalance,
      'usd' => ''
    ]
  ]);
}
?>
