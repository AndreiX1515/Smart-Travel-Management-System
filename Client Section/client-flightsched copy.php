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
  if (isset($_SESSION['status'])):
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
              <div class="flight-card">
                <div class="flight-info">

                  <!-- <div class="airline-logo">
                    <img src="lufthansa.png" alt="Airline Logo">
                  </div> -->

                  <div class="flight-details">
                    <div class="details-header">
                      <h3>Manila</h3>
                    </div>
                  
                  </div>
                    
                  <div class="flight-date-wrapper">
                      <label for="">Flight Date: </label>

                      <div class="flight-date">
                          <div class="flight-start">
                            <label for="">Start:</label>
                            <h5>2024-02-10</h5>
                          </div>

                          <div class="flight-end">
                            <label for="">End:</label>
                            <h5>2024-02-15</h5>
                          </div>

                      </div>
                  </div>

                  <div class="seats-wrapper">
                      <div class="seats-container">
                          <div class="seats-info">
                              <label for="">Available Seats:</label>
                              <p><strong>5</strong></p>
                          </div>

                          <div class="seats-info">
                              <label for="">Additional Seats:</label>
                              <p><strong>2</strong></p>
                          </div>
                      </div>

                      <div class="book-now-container">
                          <a href="#" class="btn book-now">Book Now</a>
                      </div>
                      
                  </div>

                  

                  <!-- <div class="flight-price">
                    <span>$250</span>
                  </div> -->

                  
                </div>
              </div>
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
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll("tr[data-url]").forEach(function(row) {
        row.addEventListener("click", function() {
          const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

          console.log("Transaction Number: ", transactionNumber);

          // Use AJAX to send the transaction number to the server
          $.ajax({
            url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
            type: 'POST',
            data: {
              transaction_number: transactionNumber
            },
            success: function(response) {
              console.log("Response: ", response); // Debugging line

              // Redirect to the next page after successfully setting the session
              window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
            },
            error: function(xhr, status, error) {
              console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
            }
          });
        });
      });
    });
  </script>

</body>

</html>