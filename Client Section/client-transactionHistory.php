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
  
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Add this in the <head> or before </body> -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <!-- Font Awesome Icon Kit CDN (stable version) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link rel="stylesheet" href="assets\css\client-transactionHistory.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="assets\css\client-navbar.css?v=<?php echo time(); ?>"> 
 
  <!-- Include the necessary CSS and JS for intlTelInput -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
  
  <title>Flight Booking</title>
  
  <!-- Custom CSS -->
  <style>
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    h4 {
      margin: 20px 0;
    }
  </style>
</head>
<body>

  <?php 
  include '../Client Section/Includes/client-navbar.php'; 
  ?>

  <div class="container">
    <div class="table-container">

    <div class="d-flex justify-content-between align-items-center">
        <a href="../Client Section/index.php" class="back-button">Back to Home</a>

        <div class="session-info">
            <h6>Session ID: <span><?php echo $_SESSION['accountId']; ?></span></h6>
        </div>
    </div>

          <table>
              <thead>
                  <tr>
                      <th scope="col">Transaction Number</th>
                      <th scope="col">Package Name</th>
                      <th scope="col">Flight Date</th>
                      <th scope="col">Total Pax</th>
                      <th scope="col">Amount To Pay</th>
                      <th scope="col">Downpayment Total</th>
                      <th scope="col">Status</th>
                      <th scope="col"></th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>12345</td>
                      <td>Holiday Package</td>
                      <td>December 25, 2023</td>
                      <td>4</td>
                      <td>₱ 20000</td>
                      <td>₱ 5000</td>
                      <td>Confirmed</td>
                      <td>
                          <!-- <a class="btn btn-view" href="path/to/view/file" target="_blank">View</a>
                          <a class="btn btn-download" href="path/to/download/file" target="_blank">Download</a> -->
                      </td>
                  </tr>
                  <!-- Repeat for each transaction record -->
              </tbody>
          </table>
      </div>
  </div>
  
  <!-- <script src="heartbeat.js"></script>  -->

  <?php include '../Client Section/Includes/scripts.php'; ?>
</body>
</html>