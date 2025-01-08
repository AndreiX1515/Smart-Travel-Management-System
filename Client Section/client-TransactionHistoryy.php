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

  <title>Booking Form</title>

  <link rel="stylesheet" href="../Client Section/assets/css/client-transactionHistory.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-navbar.css?v=<?php echo time(); ?>"> 
</head>

<body>
<?php include '../Client Section/Includes/client-navbar.php'; ?>  

<div class="main-container">
  <div class="container">
    <div class="content-header">
        <div class="back-button-wrapper">
            <a href="index.php" class="back-button-link"> <i class="fa-solid fa-arrow-left me-2"></i> Back to Homepage</a>
        </div>
        <h1>Transaction History</h1>
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