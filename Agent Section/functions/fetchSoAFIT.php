<?php
// Connect to your database
require "../../conn.php"; // Include the DB connection
session_start();

// Get the selected filter values from the POST request
$month = isset($_POST['month']) ? date('m', strtotime($_POST['month'])) : date('m');
$year = isset($_POST['year']) ? $_POST['year'] : date('Y');

// Initialize variables
$dataAvailable = false;
$table1 = '';
$table2 = '';
$totalPhpSum = 0;
$totalUsdSum = 0;
$count = 1;

// Query: Fetch booking data
$sql = "SELECT f.startDate AS startDate, fh.hotelName AS hotelName, fr.rooms AS roomType, f.rooms AS NoofRooms, f.pax AS pax, 
          f.phpPrice AS bookingPhpPrice, f.usdPrice AS bookingUsdPrice
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
    $formattedBookingPhpPrice = number_format($row['bookingPhpPrice'], 2);
    $formattedBookingUsdPrice = number_format($row['bookingUsdPrice'], 2);
    $bookingPhpTotal = $row['bookingPhpPrice'] * $row['pax'];
    $bookingUsdTotal = $row['bookingUsdPrice'] * $row['pax'];
    $totalPhpSum += $bookingPhpTotal;
    $totalUsdSum += $bookingUsdTotal;

    $table1 .= "<tr>
                  <td>$count</td>
                  <td>{$row['hotelName']} ({$row['roomType']}) No. of Rooms: {$row['NoofRooms']}</td>
                  <td>$ $formattedBookingUsdPrice</td>
                  <td>₱ $formattedBookingPhpPrice</td>
                  <td>{$row['pax']}</td>
                  <td>$ " . number_format($bookingUsdTotal, 2) . "</td>
                  <td>₱ " . number_format($bookingPhpTotal, 2) . "</td>
                </tr>";
    $count++;
  }
} 
else 
{
  $table1 = "<tr><td colspan='7'>No Record found</td></tr>";
}

$sql2 = "SELECT fp.paymentType AS paymentType,  DATE_FORMAT(fp.paymentDate, '%M %d, %Y') AS paymentDate, fp.amount AS paymentAmount 
         FROM `fit` f
         JOIN fitpayment fp ON f.transactionNo = fp.transactNo
         WHERE MONTH(f.startDate) = $month AND YEAR(f.startDate) = $year AND f.status = 'Completed' AND fp.paymentStatus = 'Approved'";

// Execute the query for payment data
$res2 = $conn->query($sql2);

if ($res2 && $res2->num_rows > 0) 
{
  $dataAvailable = true;
  while ($row = $res2->fetch_assoc()) 
  {
    // Payment Details
    $formattedPaymentAmount = number_format($row['paymentAmount'], 2);

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
";


// Combine response and data availability flag
$responseData = [
    'dataAvailable' => $dataAvailable,
    'htmlContent' => $response
];

// Send the data as a JSON response
echo json_encode($responseData);
?>
