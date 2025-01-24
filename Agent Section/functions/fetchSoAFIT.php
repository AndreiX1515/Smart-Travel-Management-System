<?php
// Connect to your database
require "../../conn.php"; // Include the DB connection
session_start();

// Get the selected filter values from the POST request
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
    $_SESSION['totalPriceSum'] = $formattedTotalPriceSum;

    $count++;
  }
}
else 
{
  $table1 = "<tr><td colspan='7'>No data found</td></tr>";
}


// Return the response to the client
// echo $response;

// Check if there's any data to indicate availability
$dataAvailable = false;

// Check if there is any data in the result sets (flight, request, payment)
if ($res1->num_rows > 0 || $res2->num_rows > 0 || $res3->num_rows > 0) {
    $dataAvailable = true;
}


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
      <span class='subtotal-php'>₱ " . $formattedTotalPriceSum . "</span>
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
      <span class='subtotal-php'>₱ " . $formattedTotalRequestCostSum . "</span>
    </div>
  </div>
  <table class='product-table'>
    <tbody>
      $table3
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
      <span class='subtotal-php'>₱ " . $formattedTotalAmount . "</span>
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
      <span class='subtotal-php'>₱ " . $formattedBalance . "</span>
    </div>
  </div>";


// Combine HTML content and data availability flag in a response array
$responseData = [
    'dataAvailable' => $dataAvailable,
    'htmlContent' => $response // The HTML content you generated
];

// Send the data as a JSON response
echo json_encode($responseData);


?>