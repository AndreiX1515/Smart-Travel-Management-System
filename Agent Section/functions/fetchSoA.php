<?php
// Connect to your database
require "../../conn.php"; // Include the DB connection
session_start();

// Get the selected filter values from the POST request
$companyId = $_POST['companyId'];
$month = date('m', strtotime($_POST['month']));
$year = $_POST['year'];

// Prepare SQL queries based on selected values
// 1st Table - Flight Data
$sql1 = "SELECT f.flightId, f.flightPrice, CONCAT(f.flightDepartureDate, ' - ', f.returnArrivalDate) AS flightDates, 
                SUM(DISTINCT b.pax) AS pax, SUM(DISTINCT b.totalPrice) AS totalPrice
          FROM payment p
          LEFT JOIN booking b ON p.transactNo = b.transactNo
          LEFT JOIN flight f ON f.flightId = b.flightId
          WHERE b.status = 'Confirmed'";

if ($companyId != 'All') 
{
  // Query to get the agentCode of the Head Agent for the selected branch
  $agentQuery = "SELECT agentCode FROM agent WHERE branchId = $companyId AND agentRole = 'Head Agent' LIMIT 1";
  $agentResult = $conn->query($agentQuery);

  // Check if an agentCode is found
  if ($agentResult && $agentResult->num_rows > 0) 
  {
    $agentRow = $agentResult->fetch_assoc();
    $headAgentCode = $agentRow['agentCode'];

    // Append the agentCode condition to the main query
    $sql1 .= "AND b.agentCode = '$headAgentCode' ";  // Filter by Head Agent's agentCode
  } 
  else 
  {
    // If no Head Agent found, handle gracefully (e.g., return no results or an error message)
    echo "No Head Agent found for the selected company.<br>";
  }
}

// Add the month and year filter to the query
$sql1 .= "AND MONTH(f.flightDepartureDate) = $month AND YEAR(f.flightDepartureDate) = $year
          GROUP BY f.flightId, f.flightDepartureDate, f.returnArrivalDate";

// Execute the query
$res1 = $conn->query($sql1);

$totalPriceSum = 0;
$count = 1;
$table1 = '';
$tableData = [];

if ($res1->num_rows > 0) 
{
  while ($row = $res1->fetch_assoc()) 
  {
    $totalPriceSum += $row['totalPrice'];
    $formattedFlightPrice = number_format($row['flightPrice'], 2);
    $formattedTotalPrice = number_format($row['totalPrice'], 2);
    $table1 .= "<tr>
                  <td>$count</td>
                  <td>{$row['flightDates']}</td>
                  <td></td>
                  <td>₱ $formattedFlightPrice</td>
                  <td>{$row['pax']}</td>
                  <td></td>
                  <td>₱ $formattedTotalPrice</td>
                </tr>";

    // Add the row data to the tableData array
    $tableData[] = [
      'no' => $count,  // Sequential number
      'contents' => $row['flightDates'],  // Flight dates (as per your original code)
      'price' => $formattedFlightPrice,  // Formatted flight price in PHP
      'pax' => $row['pax'],  // Number of passengers
      'total_usd' => '',  // Total price in USD
      'total_php' => $formattedTotalPrice,  // Total price in PHP
    ];

    $_SESSION['tableData'] = $tableData;
    $_SESSION['totalPriceSum'] = $formattedTotalPrice;

    $count++;
  }
}
else 
{
  $table1 = "<tr><td colspan='7'>No data found</td></tr>";
}

// 2nd Table - Request Data
$sql2 = "SELECT b.flightId, cd.details, cd.price, SUM(r.pax) AS pax, SUM(r.requestCost) AS requestCost,
            COUNT(CASE WHEN r.handlingFee != 0 THEN 1 ELSE NULL END) AS handlingFeeCount
          FROM `request` r
          JOIN concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
          JOIN booking b ON r.transactNo = b.transactNo
          JOIN flight f ON b.flightId = f.flightId
          WHERE r.requestStatus = 'Confirmed' 
          AND MONTH(f.flightDepartureDate) = $month 
          AND YEAR(f.flightDepartureDate) = $year
          AND ($companyId = 'All' OR b.agentCode = (SELECT agentCode FROM agent WHERE branchId = $companyId 
                  AND agentRole = 'Head Agent' LIMIT 1 ))
          GROUP BY r.concernDetailsId";

$res2 = $conn->query($sql2);
$totalCostSum = 0;
$handlingFeeCount = 0;
$handlingFeeTotal = 0;  // Default to 0 if no handling fees
$totalFinal = 0;  // Default to 0
$table2 = '';
$tableData2 = [];

if ($res2->num_rows > 0) 
{
  while ($row = $res2->fetch_assoc()) 
  {
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
      'no' => $count,  // Sequential number for requests
      'contents' => $row['details'],  // Request details
      'price' => $formattedRequestPrice,  // Formatted request price in PHP
      'pax' => $row['pax'],  // Number of passengers for the request
      'total_usd' => '',  // No USD conversion for requests
      'total_php' => $formattedRequestCost,  // Total request cost in PHP
    ];

    $count++;
  }
  // Add the Handling Fee row only if there are handling fees
  if ($handlingFeeCount > 0) 
  {
    $handlingFeeTotal = $handlingFeeCount * 100;
    $handlingFeeTotalFormatted = number_format($handlingFeeTotal, 2);
    $table2 .= "<tr>
                  <td>$count</td>
                  <td>Handling Fee</td>
                  <td></td>
                  <td>₱ 100.00</td>
                  <td>$handlingFeeCount</td>
                  <td></td>
                  <td>₱ $handlingFeeTotalFormatted</td>
                </tr>";

    // Add handling fee to the tableData2 array
    $tableData2[] = [
      'no' => $count,
      'contents' => 'Handling Fee',
      'price' => '100.00',
      'pax' => $handlingFeeCount,
      'total_usd' => '',
      'total_php' => $handlingFeeTotalFormatted,
    ];

    $totalFinal = $totalPriceSum + $totalCostSum + $handlingFeeTotal;
  }
} 
else 
{
  $table2 = "<tr><td colspan='7'>No request data found</td></tr>";
}

$sql3 = "SELECT branchName FROM branch WHERE branchId = $companyId";
$res3 = $conn->query($sql3);

if ($res3 && $res3->num_rows > 0) 
{
  $row = $res3->fetch_assoc();
  $_SESSION['branchName'] = $row['branchName']; // Store the branch name in the session
} 
else 
{
  $_SESSION['branchName'] = 'Unknown Branch'; // Default value if branch is not found
}

$_SESSION['tableData2'] = $tableData2;
$totalRequestCost = number_format($totalCostSum + $handlingFeeTotal, 2);
$_SESSION['totalRequestCost'] = $totalRequestCost;
$_SESSION['totalFinal'] = number_format($totalFinal, 2);

$response = "
  <table class='product-table'>
    <thead>
      <tr>
        <th>No.</th>
        <th>Contents</th>
        <th>Price (USD)</th>
        <th>Price (PHP)</th>
        <th>PAX</th>
        <th>Total (USD)</th>
        <th>Total (PHP)</th>
      </tr>
    </thead>
    <tbody>
      $table1
    </tbody>
  </table>
  <div class='subtotal-container'>
    <div class='balance'>
      <span>SUBTOTAL: </span>
    </div>
    <div class='subtotal-item-usd'>
      <span>USD:</span>
      <span class='subtotal-usd'></span>
    </div>
    <div class='subtotal-item-php'>
      <span>PHP:</span>
      <span class='subtotal-php'>₱ " . number_format($totalPriceSum, 2) . "</span>
    </div>
  </div>
  <table class='product-table'>
    <tbody>
      $table2
    </tbody>
  </table>
  <div class='subtotal-container'>
    <div class='balance'>
      <span>SUBTOTAL: </span>
    </div>
    <div class='subtotal-item-usd'>
      <span>USD:</span>
      <span class='subtotal-usd'></span>
    </div>
    <div class='subtotal-item-php'>
      <span>PHP:</span>
      <span class='subtotal-php'>₱ " . number_format($totalCostSum + $handlingFeeTotal, 2) . "</span>
    </div>
  </div>
  <div class='balance-container'>
    <div class='balance'>
      <span>BALANCE:</span>
    </div>
    <div class='balanceUSD'>
      <span>USD:</span>
      <span class='subtotal-usd'></span>
    </div>
    <div class='balancePHP'>
      <span>PHP:</span>
      <span class='subtotal-php'>₱ " . number_format($totalFinal, 2) . "</span>
    </div>
  </div>
";

// Return the response to the client
echo $response;
?>
