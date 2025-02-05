<?php
// Connect to your database
require "../../conn.php"; // Include the DB connection
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['companyId']) && isset($_POST['month']) && isset($_POST['year'])) 
{
  // Get the selected filter values from the POST request
  $companyId = $_POST['companyId'];
  $month = date('m', strtotime($_POST['month']));
  $year = $_POST['year'];

  $totalRequestCostSum = 0;
  $formattedTotalPriceSum = '0.00';
  $formattedTotalRequestCostSum = '0.00';
  $formattedTotalAmount = '0.00';

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
  $sql1 .= " AND MONTH(f.flightDepartureDate) = $month AND YEAR(f.flightDepartureDate) = $year
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

  // 2nd Table - Request Data
  $sql2 = "SELECT b.flightId, cd.details, cd.price, SUM(r.pax) AS pax, SUM(r.requestCost) AS requestCost,
              COUNT(CASE WHEN r.handlingFee != 0 THEN 1 ELSE NULL END) AS handlingFeeCount
            FROM `request` r
            JOIN concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
            JOIN booking b ON r.transactNo = b.transactNo
            JOIN flight f ON b.flightId = f.flightId
            WHERE r.requestStatus = 'Confirmed' AND MONTH(f.flightDepartureDate) = $month AND YEAR(f.flightDepartureDate) = $year
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

      // Add handling fee to the tableData2 array
      $tableData2[] = [
        'no' => $count,
        'contents' => 'Handling Fee',
        'price' => '100.00',
        'pax' => $handlingFeeCount,
        'total_usd' => '',
        'total_php' => $formattedHandlingFeeTotal,
      ];

      $totalRequestCostSum = $totalCostSum + $handlingFeeTotal;
      $formattedTotalRequestCostSum = number_format($totalRequestCostSum, 2);

      $count++;
      $_SESSION['tableData2'] = $tableData2;
      $_SESSION['totalRequestCost'] = $formattedTotalRequestCostSum;
    }
  } 
  else 
  {
    $table2 = "<tr><td colspan='7'>No request data found</td></tr>";
  }

  // 3rd Table - Payment Data
  $table3 = "";
  $totalAmount = 0; // To calculate the total payment amount
  $tableData3 = []; // Array to store table3 data

  // Using prepared statements
  $sql3 = "SELECT DISTINCT p.transactNo AS transactNo, p.paymentType AS paymentType, p.amount AS amount, DATE(p.paymentDate) AS paymentDate
            FROM 
              payment p
            JOIN 
              booking b ON b.transactNo = p.transactNo
            JOIN 
              agent a ON a.agentCode = b.agentCode
            JOIN 
              flight f ON b.flightId = f.flightId
            WHERE 
              p.paymentStatus = 'Approved' AND MONTH(f.flightDepartureDate) = $month AND YEAR(f.flightDepartureDate) = $year
                AND ($companyId = 'All' OR b.agentCode IN (SELECT agentCode FROM agent 
                      WHERE branchId = $companyId AND agentRole = 'Head Agent'))";

  $res3 = $conn->query($sql3);

  if ($res3->num_rows > 0) 
  {
    while ($row = $res3->fetch_assoc()) 
    {
      // Accumulate the total payment amount
      $totalAmount += $row['amount'];
      $formattedTotalAmount = number_format($totalAmount, 2);

      // Format the payment amount
      $formattedAmount = number_format($row['amount'], 2);
      $formattedTotalAmount = number_format($totalAmount, 2);

      // Format the payment date as "Month DD, YYYY"
      $formattedDate = DateTime::createFromFormat('Y-m-d', $row['paymentDate'])->format('F d, Y');

      // Build the table row
      $table3 .= "<tr>
                    <td>$count</td>
                    <td>{$row['paymentType']} - $formattedDate</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>₱ $formattedAmount</td>
                  </tr>";

      // Add row data to the tableData3 array
      $tableData3[] = [
          'no' => $count,
          'contents' => $row['paymentType'] . ' - ' . $formattedDate,
          'price' => '', 
          'pax' => '',
          'total_usd' => '',
          'total_php' => $formattedAmount 
      ];

      $count++; // Increment row counter
    }

    // Store table data and total amount in the session
    $_SESSION['tableData3'] = $tableData3;
    $_SESSION['totalAmount'] = $formattedTotalAmount; // Store formatted total amount
  } 
  else 
  {
    $table3 = "<tr><td colspan='7'>No Payment data found</td></tr>";
  }

  $sql4 = "SELECT branchName FROM branch WHERE branchId = $companyId";
  $res4 = $conn->query($sql4);

  if ($res4 && $res4->num_rows > 0) 
  {
    $row = $res4->fetch_assoc();
    $_SESSION['branchName'] = $row['branchName']; // Store the branch name in the session
  } 
  else 
  {
    $_SESSION['branchName'] = 'Unknown Branch'; // Default value if branch is not found
  }

  $totalCost = ($totalPriceSum + $totalRequestCostSum);
  $balance = ($totalPriceSum + $totalRequestCostSum) - $totalAmount;
  $formattedBalance = number_format($balance, 2);
  $_SESSION['balance'] = $formattedBalance;

  // Return the response to the client
  // echo $response;

  // Check if there's any data to indicate availability
  $dataAvailable = false;

  // Check if there is any data in the result sets (flight, request, payment)
  if ($res1->num_rows > 0 || $res2->num_rows > 0 || $res3->num_rows > 0) {
      $dataAvailable = true;
  }

  // For each query result, log the row count
  error_log("Result 1 row count: " . $res1->num_rows);
  error_log("Result 2 row count: " . $res2->num_rows);
  error_log("Result 3 row count: " . $res3->num_rows);

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
}
?>