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
    <div id="main-content" class="col-md-9 col-lg-10 w-100 p-5">
      <div class="table-responsive">
        <div class="table-controls mb-4">
          <div class="d-flex flex-column">
            <label for="" class="mb-2">Search</label>
            <input type="text" class="search-input" placeholder="Search..." />
          </div>

          <div class="d-flex flex-column">
            <label for="" class="mb-2 ms-2">Package Name</label>
            <select class="sort-dropdown">
              <option value="">Sort by Package Name</option>
              <option value="packageNameAsc">Package Name (A-Z)</option>
              <option value="packageNameDesc">Package Name (Z-A)</option>
            </select>
          </div>

          <div class="d-flex flex-column">
            <label for="" class="mb-2 ms-2">Package Name</label>
            <select class="sort-dropdown">
              <option value="">Sort by Package Name</option>
              <option value="packageNameAsc">Package Name (A-Z)</option>
              <option value="packageNameDesc">Package Name (Z-A)</option>
            </select>
          </div>

          <div class="d-flex flex-column">
            <label for="" class="mb-2 ms-2">Status</label>
            <select class="sort-dropdown">
              <option value="">Sort by Status</option>
              <option value="StatusCompleted">Completed</option>
              <option value="StatusPending">Pending</option>
              <option value="StatusOngoing">Ongoing</option>
              <option value="StatusTerminated">Terminated</option>
            </select>
          </div>

          <!-- <div class="d-flex flex-row align-items-end">
            <button class="add-button">Add Booking</button>
          </div> -->
        </div>
        <table class="table excel-table">
          <thead>
            <tr>
              <th scope="col">Transaction Number</th>
              <th scope="col">Package Name</th>
              <th scope="col">Flight Date</th>
              <th scope="col">Total Pax</th>
              <th scope="col">Amount To Pay</th>
              <th scope="col">Downpayment Total</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
              <?php
                $sql1 = "SELECT Distinct
                          b.transactNo,
                          b.pax AS totalPax,
                          b.totalPrice AS amountToPay,
                          b.downpaymentAmount AS downpayment, 
                          b.status,
                          p.packageName,
                          DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y') AS flightDate
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
                            <td>" . htmlspecialchars($row['transactNo']) . "</td>
                            <td>" . htmlspecialchars($row['packageName']) . "</td>
                            <td>" . htmlspecialchars($row['flightDate']) . "</td>
                            <td>" . htmlspecialchars($row['totalPax']) . "</td>
                            <td>₱ " . number_format($row['amountToPay'], 2) . "</td>
                            <td>₱ " . number_format($row['downpayment'], 2) . "</td>
                            <td>". $row['status'] ."</td>
                            <td><button type='button' class='btn btn-info'>Inquiry</button></td>
                          </tr>";
                  }
                } 
                else 
                {
                  echo "<tr><td colspan='8'>No bookings found</td></tr>";
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