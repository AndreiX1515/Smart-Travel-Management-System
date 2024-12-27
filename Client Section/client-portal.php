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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Flight Booking</title>

  <?php include '../Client Section/Includes/head.php'; ?>
  
  <link rel="stylesheet" href="assets\css\client-portal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="assets\css\client-navbar.css?v=<?php echo time(); ?>"> 
 
</head>

<body>
  <?php include '../Client Section/Includes/client-navbar.php'; ?>

  <div class="main-container">
    <?php include '../Client Section/Includes/client-sidebar.php'; ?>

      <div class="content">
          <div class="content-header">
              <div class="back-button-wrapper">
                  <a href="index.php" class="back-button-link"> <i class="fa-solid fa-arrow-left me-2"></i> Back to Homepage</a>
              </div>
              <h1>Client Portal</h1>
              <p>Welcome to your client portal. Here you can view your transactions, update your profile, and more.</p>
          </div>

          <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Profile</h4>
                        <a href="client-profile.php" class="btn btn-primary">View</a>
                    </div>
                    <div class="card-body">
                        <p>View and update your profile information.</p>
                    </div>
                </div>
            </div>

            <!-- <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Transactions</h4>
                        <a href="client-transactionHistory.php" class="btn btn-primary">View</a>
                    </div>
                    <div class="card-body">
                        <p>View your transaction history.</p>
                    </div>
                </div>
            </div> -->

          </div>
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

          console.log("Transaction Number: ", transactionNumber); // Debugging line

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