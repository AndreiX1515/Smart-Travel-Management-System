
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statement of Account</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">

</head>

<body>
  <?php include '../Agent Section/includes/sidebar.php'; ?> 

  <div class="main-content" id="mainContent">
    <?php include '../Agent Section/includes/navbar.php'; ?>

    <div class="content-wrapper d-flex flex-column">
      <table class="product-table">
      <thead>
        <tr>
          <th>TransactNo</th>
          <th>Contents</th>
          <th>Pax</th>
          <th>Booking Type</th>
          <th>Package Price</th>
          <th>Total Request Cost</th>
          <th>Total Amount to be paid</th>
          <th>Total Amount Paid</th>
          <th>Balance</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
          // Query to select all records from the booking table
          $agentId = $_SESSION['agentId'];
          $query = "SELECT b.transactNo, b.flightId, b.pax, b.totalPrice AS packagePrice, 
                      CONCAT(DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y'), ' - ', DATE_FORMAT(f.returnDepartureDate, '%M %d, %Y')) AS FlightDate,
                      IFNULL(req.totalRequestCost, 0) AS totalRequestCost,
                      IFNULL(paid.totalPaidAmount, 0) AS totalPaidAmount,
                      b.status AS bookingStatus, b.bookingType,
                      (b.totalPrice + IFNULL(req.totalRequestCost, 0)) AS TotalCost
                    FROM 
                      booking b
                    JOIN flight f ON b.flightId = f.flightId
                    LEFT JOIN 
                      (SELECT transactNo, SUM(amount) AS totalPaidAmount FROM payment
                        WHERE paymentStatus = 'Approved' GROUP BY transactNo) paid ON b.transactNo = paid.transactNo
                    LEFT JOIN 
                        (SELECT transactNo, SUM(requestCost) AS totalRequestCost FROM request
                        WHERE requestStatus = 'Confirmed' GROUP BY transactNo) req ON b.transactNo = req.transactNo
                    WHERE 
                        b.status = 'Confirmed' and b.agentId = '$agentId'";

          $result = $conn->query($query); // Execute the query

          // Check if there are results and populate the table
          if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

              // Calculate Balance
              $totalAmountPaid = $row['totalPaidAmount'];
              $totalAmountToBePaid = $row['packagePrice'] + $row['totalRequestCost']; // Total price + total request cost
              $balance = $totalAmountToBePaid - $totalAmountPaid; // Balance calculation

              // Determine if fully paid or not
              $status = ($totalAmountPaid == $totalAmountToBePaid) ? 'Fully Paid' : 'Not Paid';

              // Display table row
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['transactNo']) . "</td>"; // TransactNo
              echo "<td>" . htmlspecialchars($row['FlightDate']) . "</td>"; // Contents (Flight Date Range)
              echo "<td>" . htmlspecialchars($row['pax']) . "</td>"; // Pax (Number of Passengers)
              echo "<td>" . $row['bookingType'] . "</td>"; // Booking Type 
              echo "<td>₱ " . number_format($row['packagePrice'], 2) . "</td>"; // Price (Flight Price)
              echo "<td>₱ " . number_format($row['totalRequestCost'], 2) . "</td>"; // Total Request Cost
              echo "<td>₱ " . number_format($totalAmountToBePaid, 2) . "</td>"; // Total Amount to be paid (Total Price + Request Cost)
              echo "<td>₱ " . number_format($totalAmountPaid, 2) . "</td>"; // Total Amount Paid
              echo "<td>₱ " . number_format($balance, 2) . "</td>"; // Balance (Amount to be paid - Amount paid)
              echo "<td>" . htmlspecialchars($status) . "</td>"; // Status (Fully Paid or Not Paid)
              echo "</tr>";
            }
          } else {
            // Display a message if no records are found
            echo "<tr><td colspan='9'>No records found.</td></tr>";
          }

          $conn->close(); // Close the database connection
        ?>
      </tbody>
      </table>
    </div>


  </div>

  <?php require "../Agent Section/includes/scripts.php"; ?>

</body>
</html>