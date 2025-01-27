<?php
// Connect to your database
require "../../conn.php"; // Include the DB connection
session_start();

// Get the selected filter values from the POST request
$month = isset($_POST['month']) ? intval(date('m', strtotime($_POST['month']))) : date('m');
$year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');

// Initialize totals and count variables
$totalPhpSum = 0;
$totalUsdSum = 0;
$totalPaymentAmount = 0;
$balance = 0;
$count = 1;

// Initialize data availability flag and table variables
$dataAvailable = false;
$table1 = '';
$table2 = '';

// Query: Fetch booking data
$sql = "SELECT f.startDate AS startDate, fh.hotelName AS hotelName, fr.rooms AS roomType, fr.price as roomPrice, f.rooms AS NoofRooms, 
          f.pax AS pax, f.phpPrice AS bookingPhpPrice, f.usdPrice AS bookingUsdPrice
        FROM `fit` f
        JOIN fithotel fh ON f.hotelId = fh.hotelId
        JOIN fitrooms fr ON f.roomId = fr.roomId
        WHERE MONTH(f.startDate) = $month AND YEAR(f.startDate) = $year AND f.status = 'Completed'";

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) 
{
  $dataAvailable = true;
  while ($row = $res->fetch_assoc()) 
  {
    // Booking Details
    $formattedBookingUsdPrice = number_format($row['roomPrice'], 2);
    $totalPhpSum += $row['bookingPhpPrice'];
    $totalUsdSum += $row['bookingUsdPrice'];

    $table1 .= "<tr>
                  <td>$count</td>
                  <td>{$row['hotelName']} ({$row['roomType']}) No. of Rooms: {$row['NoofRooms']}</td>
                  <td>$ $formattedBookingUsdPrice</td>
                  <td>-</td>
                  <td>{$row['pax']}</td>
                  <td>$ " . number_format($row['bookingUsdPrice'], 2) . "</td>
                  <td>₱ " . number_format($row['bookingPhpPrice'], 2) . "</td>
                </tr>";
    $count++;
  }
} 
else 
{
  $table1 = "<tr><td colspan='7'>No Record found</td></tr>";
}

// Query: Fetch payment data
$sql2 = "SELECT fp.paymentType AS paymentType,  DATE_FORMAT(fp.paymentDate, '%M %d, %Y') AS paymentDate, fp.amount AS paymentAmount 
         FROM `fit` f
         JOIN fitpayment fp ON f.transactionNo = fp.transactNo
         WHERE MONTH(f.startDate) = $month AND YEAR(f.startDate) = $year AND f.status = 'Completed' AND fp.paymentStatus = 'Approved'";

$res2 = $conn->query($sql2);

if ($res2 && $res2->num_rows > 0) 
{
  $dataAvailable = true;
  while ($row = $res2->fetch_assoc()) 
  {
    // Payment Details
    $formattedPaymentAmount = number_format($row['paymentAmount'], 2);
    $totalPaymentAmount += $row['paymentAmount'];

    $table2 .= "<tr>
                  <td>$count</td>
                  <td>{$row['paymentType']} (Payment Date: {$row['paymentDate']})</td>
                  <td>-</td>
                  <td>₱ $formattedPaymentAmount</td>
                  <td>-</td>
                  <td>-</td>
                  <td>₱ $formattedPaymentAmount</td>
                </tr>";
    $count++;
  }
} 
else 
{
  $table2 = "<tr><td colspan='7'>No Payment Records found</td></tr>";
}

// Generate subtotals
$formattedTotalPhpSum = number_format($totalPhpSum, 2);
$formattedTotalUsdSum = number_format($totalUsdSum, 2);
$formattedTotalPaymentAmount = number_format($totalPaymentAmount, 2);
$balance = $totalPhpSum - $totalPaymentAmount;
$formattedBalance = number_format($balance, 2);

// Generate combined response
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
      <span>SUBTOTAL (Bookings): </span>
    </div>
    <div class='subtotal-item-usd'>
      <span>USD:</span>
      <span class='subtotal-usd'>$ $formattedTotalUsdSum</span>
    </div>
    <div class='subtotal-item-php'>
      <span>PHP:</span>
      <span class='subtotal-php'>₱ $formattedTotalPhpSum</span>
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
      <span class='subtotal-php'>₱ $formattedTotalPaymentAmount</span>
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
      <span class='subtotal-php'>₱ $formattedBalance</span>
    </div>
  </div>";

// Combine response and data availability flag
$responseData = [
    'dataAvailable' => $dataAvailable,
    'htmlContent' => $response
];

// Send the data as a JSON response
echo json_encode($responseData);
?>
