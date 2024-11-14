
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
          <th>Price</th>
          <th>Pax</th>
          <th>Total Amount to be paid</th>
          <th>Total Amount Paid</th>
          <th>Balance</th>
        </tr>
      </thead>
      <tbody>
      <?php
        // Query to select all records from the booking table
        $query = "SELECT 
                    booking.transactNo, 
                    booking.pax, 
                    booking.totalPrice, 
                    booking.status, 
                    booking.agentId, 
                    flight.flightId, 
                    flight.flightPrice, 
                    CONCAT(DATE_FORMAT(flight.flightDepartureDate, '%M %d, %Y'), ' - ', DATE_FORMAT(flight.returnDepartureDate, '%M %d, %Y')) AS FlightDate,
                    (SELECT SUM(payment.amount)
                    FROM payment
                    WHERE payment.transactNo = booking.transactNo AND payment.paymentStatus = 'Approved') AS totalAmountPaid,
                    (SELECT SUM(IFNULL(request.requestCost, 0))
                    FROM request
                    WHERE request.transactNo = booking.transactNo AND request.requestStatus = 'Confirmed') AS totalRequestCost
                  FROM booking
                  JOIN agent ON booking.agentId = agent.agentId
                  JOIN request ON booking.transactNo = request.transactNo
                  JOIN flight ON booking.flightId = flight.flightId
                  WHERE booking.status = 'Confirmed' 
                    AND request.requestStatus = 'Confirmed'
                  GROUP BY booking.transactNo, flight.flightId"; // Group by transactNo and flightId
        
        $result = $conn->query($query); // Execute the query

        // Check if there are results and populate the table
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {

            // Calculate Balance
            $totalAmountPaid = $row['totalAmountPaid'];
            $totalAmountToBePaid = $row['totalPrice'] + $row['totalRequestCost']; // Total price + total request cost
            $balance = $totalAmountToBePaid - $totalAmountPaid; // Balance calculation

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['transactNo']) . "</td>"; // Escape output for security
            echo "<td>" . htmlspecialchars($row['FlightDate']) . "</td>";
            echo "<td>₱ " . number_format($row['flightPrice'], 2) . "</td>"; // Format flight price
            echo "<td>" . htmlspecialchars($row['pax']) . "</td>";
            echo "<td>₱ " . number_format($totalAmountToBePaid, 2) . "</td>"; // Format total price
            echo "<td>₱ " . number_format($totalAmountPaid, 2) . "</td>"; // Format total amount paid
            echo "<td>₱ " . number_format($balance, 2) . "</td>"; // Format balance
            echo "</tr>";
          }
        } else {
          // Display a message if no records are found
          echo "<tr><td colspan='7'>No records found.</td></tr>";
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