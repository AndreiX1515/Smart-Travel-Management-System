
<?php
  // include 'session_validate.php'; // This will check if the session is valid
  require '../conn.php';
  session_start();

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  
  // Fetch session variables directlys
  $email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
  // $firstName = $_SESSION['first_name'] ?? '';
  // $lastName = $_SESSION['last_name'] ?? '';
  // $middleName = $_SESSION['middle_name'] ?? '';
  $accId = $_SESSION['accountId'] ?? '';
  
  // $fullName = htmlspecialchars($lastName . ', ' . $firstName . ($middleName ? ' ' . substr($middleName, 0, 1) . '.' : ''));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../Client Section/Includes/head.php'; ?>

  <title>Flight Schedules</title>

  <link rel="stylesheet" href="../Client Section/assets/css/client-portal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-flightSched.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-navbar.css?v=<?php echo time(); ?>"> 
 
</head>

<body>

<?php 
    if(isset($_SESSION['status'])):
?>

  <!-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Hey!</strong> <?= $_SESSION['status']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div> -->

<?php 
  unset($_SESSION['status']);
  endif;
?>


<?php include '../Client Section/Includes/client-navbar.php'; ?>

<div class="body-container">
 

  <div class="main-container">
    <section class="flight-schedules">
      <div class="section-wrapper">
        <div class="section-header">
          <h3>Flight Schedules</h3>
          <p>Check out our latest flight schedules and book your next adventure today!</p>
        </div>

        <div class="section-main-content">
        <div class="confirm-table-container-flight">
    <?php
    $sql = "SELECT f.flightId AS flightid, f.origin, f.flightDepartureDate AS Start, f.returnDepartureDate AS End, 
                f.availSeats AS FlightSeat, 
                GREATEST((f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' 
                AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)), 0) AS AvailSeats, 
                IF((f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' AND b.bookingType = 'Package' 
                    THEN b.pax ELSE 0 END), 0)) < 0, ABS(f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' 
                    AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)), 0) AS AdditionalSeats, 
                f.flightPrice AS RetailPrice
            FROM employee e 
            JOIN flight f ON f.employeeId = e.employeeId
            LEFT JOIN booking b ON b.flightId = f.flightId
            WHERE f.flightDepartureDate >= CURDATE()
            GROUP BY f.flightId, f.origin, f.flightDepartureDate, f.returnDepartureDate, f.availSeats, f.flightPrice
            ORDER BY f.flightDepartureDate";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="flight-card">';
            echo '<div class="flight-info">';
            // echo '<div class="airline-logo"><img src="lufthansa.png" alt="Airline Logo"></div>';
            echo '<div class="flight-details">';
            echo '<h3>' . $row['origin'] . '</h3>';
            echo '<p><strong>Flight Date:</strong> ' . $row['Start'] . ' - ' . $row['End'] . '</p>';
            echo '<p><strong>Available Seats:</strong> ' . $row['AvailSeats'] . '</p>';
            echo '<p><strong>Additional Seats:</strong> ' . $row['AdditionalSeats'] . '</p>';
            echo '</div>';
            echo '<div class="flight-price">';
            // echo '<span>$' . $row['RetailPrice'] . '</span>';
            echo '</div>';
            echo '<a href="../Client Section/login.php?flightid=' . $row['flightid'] . '" class="btn book-now">Book Now</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "<p>No flights available.</p>";
    }
    ?>
</div>


        </div>
      </div>

    </section>

  </div>
</div>







<?php include '../Client Section/Includes/scripts.php'; ?>
<!-- <script src="heartbeat.js"></script>  -->

<!-- Row Click Selection JS -->
<script>
  document.addEventListener("DOMContentLoaded", function() 
  {
    document.querySelectorAll("tr[data-url]").forEach(function(row) 
    {
      row.addEventListener("click", function() 
      {
        const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

        console.log("Transaction Number: ", transactionNumber);

        // Use AJAX to send the transaction number to the server
        $.ajax(
        {
          url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
          type: 'POST',
          data: { transaction_number: transactionNumber },
          success: function(response)
          {
            console.log("Response: ", response); // Debugging line

            // Redirect to the next page after successfully setting the session
            window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
          },
          error: function(xhr, status, error) 
          {
            console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
          }
        });
      });
    });
  });
</script>

 </body>
</html>