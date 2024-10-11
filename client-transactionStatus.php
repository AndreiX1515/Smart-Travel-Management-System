<?php
  include 'session_validate.php'; // This will check if the session is valid
  require "conn.php";

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  // Fetch session variables directly
  $email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
  $firstName = $_SESSION['first_name'] ?? '';
  $lastName = $_SESSION['last_name'] ?? '';
  $accId = $_SESSION['accountid'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>    
  <title>Client Transaction History</title>
  <?php include 'includes/head.php' ?>
  <link rel="stylesheet" href="assets\css\client-dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
  <div class="container-fluid">
    <div class="row">
        
        <nav class="navbar-custom d-flex flex-row justify-content-between" id="navbar">
          <div class="logo">
            <a href="#" class="logo"><img src="assets\images\SMART LOGO 2 (2).png" alt="Logo" width="200px" height="35px"></a>
          </div>

          <div class="navbar-profile dropdown" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="text-secondary"><?php echo $lastName.', '.$firstName?></span>
            <img src="assets\images\profile-user.png" width="40px" height="40px" alt="User Image">
            <i class="fas fa-chevron-down"></i>
          </div>

          <ul class="dropdown-menu" aria-labelledby="profileDropdown">
            <li>
              <a class="dropdown-item" href="#" id="logout" data-bs-toggle="modal" data-bs-target="#logoutModal" ><i class="fas fa-sign-out-alt"></i>Logout</a>
            </li>
          </ul>
        </nav>
            
        <!-- Main content -->
        <div id="main-content" class="col-md-9 col-lg-10 w-100">
          <!-- Main Content Section -->
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Transaction Number</th>
                <th scope="col">Package Name</th>
                <th scope="col">Flight Date</th>
                <th scope="col">Total Pax</th>
                <th scope="col">Amount To Pay</th>
                <th scope="col">Downpayment Amount to Pay</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $sql1 = "SELECT Distinct
                  b.transactNo,
                  b.pax AS totalPax,
                  b.totalPrice AS amountToPay,
                  (1000 * b.pax) AS downpayment, 
                  p.packageName,
                  DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y') AS flightDate  -- Format flightDepartureDate
                    FROM 
                        booking b
                    JOIN 
                        guest g ON b.transactNo = g.transactNo
                    JOIN 
                        flight f ON g.flightId = f.flightId
                    JOIN 
                        package p ON f.packageId = p.packageId
                    WHERE 
                        b.accountId = '$accId'";

                $res1 = $conn->query($sql1);

                if ($res1->num_rows > 0) 
                {
                  while ($row = $res1->fetch_assoc()) 
                  {
                    echo "<tr>
                            <td>".$row['transactNo']."</td>
                            <td>".$row['packageName']."</td>
                            <td>".$row['flightDate']."</td>  <!-- Use the formatted flight date -->
                            <td>".$row['totalPax']."</td>
                            <td>₱ ".number_format($row['amountToPay'], 2)."</td>  <!-- Format amountToPay with commas -->
                            <td>₱ ".number_format($row['downpayment'], 2)."</td>  <!-- Format downpayment with commas -->
                          </tr>";
                  }
                } else 
                {
                  echo "<tr><td colspan='6'>No bookings found</td></tr>";
                }
              ?>
              
            </tbody>
          </table>
          
        </div>
    </div>
  </div>

  <!-- Logout Confirmation Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to logout?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="" class="btn btn-danger" id="logoutButton">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <?php include 'includes/scripts.php' ?>
  <script src="heartbeat.js"></script>

  <script>
    $('#logoutButton').on('click', function (e) 
    {
      // Send AJAX request to handle the logout
      $.ajax(
      {
        url: 'client-logout.php', // Your PHP script for logging out
        method: 'POST',
        dataType: 'json',
        success: function (response) 
        {
          if (response.status === 'success') 
          {
            window.location.href = 'login.php';
          }
        },
        error: function () 
        {
          $('#message-1').text('Error logging out. Please try again.').addClass('show'); // Handle error display
          showMessage();
        }
      });
    });
  </script>
</body>
</html>