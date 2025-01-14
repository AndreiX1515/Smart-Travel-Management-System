
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statement of Account</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-soa.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">

</head>

<body>
<?php include '../Agent Section/includes/sidebar.php'; ?> 

<div class="main-content" id="mainContent">
    
  <?php
    require "../conn.php";

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $accountId = $_SESSION['agent_accountId'];
    $agentId = $_SESSION['agent_agentId'];
    $agentCode = $_SESSION['agent_agentCode'];
    $agentRole = $_SESSION['agent_agentRole'];
    $agentType = $_SESSION['agent_agentType'];
    $fName =  $_SESSION['agent_fName'] ?? '';
    $lName = $_SESSION['agent_lName'] ?? '';
    $mName = $_SESSION['agent_mName'] ?? '';
    $branchId = $_SESSION['agent_branchId'] ?? '';
    $email = $_SESSION['email'] ?? '';
    $password = $_SESSION['password'] ?? '';

    $sql1 = "Select * from branch where branchId= '$branchId'";
    $result1 = $conn->query($sql1);

    // Check if a result is returned
    if ($result1->num_rows > 0) {
        // Fetch the branchName
        $row = $result1->fetch_assoc();
        $branchName = $row['branchName'];
    } else {
        $branchName = "No Branch";
    }

    // Format the full name
    $fullName = htmlspecialchars($lName . ', ' . $fName . ($mName ? ' ' . substr($mName, 0, 1) . '.' : ''));

    // Optional: hide password by default
    $maskedPassword = '••••••••••';
  ?>

  <?php
    date_default_timezone_set('Asia/Taipei');
    $current_date = date('D, F d, Y'); 
  ?>

  <header>      
    <nav class="navbar navbar-expand-lg justify-content-between sticky-top">
      <div class="container-fluid d-flex justify-content-between">
        <div class="nav-start-container d-flex flex-row">
          <div class="content-header">
            <div class="back-button-wrapper">
                <a href="agent-dashboard.php" class="back-button-link"> <i class="fa-solid fa-arrow-left me-2"></i> Back to Dashboard</a>
            </div>
            <h1>Statement of Accounts (SOA)</h1>
          </div>
        </div>

        <div class="nav-end-container d-flex flex-row align">
          <div class="date-time-container d-flex flex-row align-items-center">
              <h6><?php echo $current_date; ?></h6>
          </div>

          <div class="vertical-line-navbar"></div>

          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item dropdown d-flex align-items-center">

                  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <div class="profile-container ms-2 me-3">
                          <h6 class="mb-1"><?php echo $fullName; ?></h6>
                          <span class="m-0">Branch: <?php echo $branchName; ?></span>
                          <span class="m-0">Agent ID: <?php echo $agentId; ?></span>
                      </div>
                      <img src="../Assets/Icons/circle.png" alt="Profile" class="profile-image me-2" width="40px" height="40px">
                  </a>

                  <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="navbarDropdown">
                    <li>
                      <a class="dropdown-item" href="#" style="font-size: 14px;" data-bs-toggle="modal" data-bs-target="#viewPasswordModal">
                        <i class="fas fa-user me-2"></i> View Password
                      </a>
                    </li>

                    <li>
                      <hr class="dropdown-divider">
                    </li>

                    <li>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" style="font-size: 14px;">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                      </a>
                    </li>

                  </ul>

                </li>  
              </ul>
            </div>

      </div>

      </div>
    </nav>
  </header>

  <?php include '../Agent Section/includes/logoutViewPassModal.php'; ?>

  <div class="content-wrapper">

    <div class="content-body">
      <div class="table-actions">
        <div class="row">
          <div class="col-md-2">
              <div class="table-filters-container">
                <label for="company-filter ">Company Name:</label>
                <select id="company-filter" name="company-filter" class="form-control">
                    <option value="All">Select a company</option>
                    <option value="P91 Travel & Tours">P91 Travel & Tours</option>
                    <option value="APD Travel & Tours">APD Travel & Tours</option>
                    <option value="FRANCIA Travel & Tours">FRANCIA Travel & Tours</option>
                    <option value="Travel Escape">Travel Escape</option>
                    <option value="FRANCIA Travel & Tours">EWINER</option>
                </select>
              </div>
          </div>

          <div class="col-md-1">
              <div class="table-filters-container">
                <label for="company-filter ">Month</label>
                <select id="company-filter" name="company-filter" class="form-control">
                  <option value="" selected>All</option>
                  <option value="January">January</option>
                  <option value="February">February</option>
                  <option value="March">March</option>
                  <option value="April">April</option>
                  <option value="May">May</option>
                  <option value="June">June</option>
                  <option value="July">July</option>
                  <option value="August">August</option>
                  <option value="September">September</option>
                  <option value="October">October</option>
                  <option value="November">November</option>
                  <option value="December">December</option>
                </select>
              </div>
          </div>

        </div>
      </div>
    
      <div class="table-container">
        <table class="product-table" id="product-table">
          <thead>
            <tr>
              <th>Transact No.</th>
              <th>To/From</th>
              <th>Pax</th>
              <th>Booking Info</th>
              <th>Request Cost</th>
              <th>To be paid</th>
              <th>Amount Paid</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
              // Query to select all records from the booking table
              $agentId = $_SESSION['agent_agentId'];
              $agentRole = $_SESSION['agent_agentRole'];
              $agentCode = $_SESSION['agent_agentCode'];
              $accountId = $_SESSION['agent_accountId'];
              $agentType = $_SESSION['agent_agentType'];

              if ($agentRole === 'Head Agent')
              {
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
                            b.status = 'Confirmed' and b.agentCode = '$agentCode'";

                $result = $conn->query($query); // Execute the query

                // Check if there are results and populate the table
                if ($result && $result->num_rows > 0) 
                {
                  while ($row = $result->fetch_assoc()) 
                  {

                    // Calculate Balance
                    $totalAmountPaid = $row['totalPaidAmount'];
                    $totalAmountToBePaid = $row['packagePrice'] + $row['totalRequestCost']; // Total price + total request cost
                    $balance = $totalAmountToBePaid - $totalAmountPaid; // Balance calculation

                    // Determine if fully paid or not
                    $status = ($totalAmountPaid == $totalAmountToBePaid) ? 'Fully Paid' : 'Not Paid';

                    // Display table row

                    echo "<tr data-transact-no='" . htmlspecialchars($row['transactNo']) . "' onclick='openModal(this)' style='cursor: pointer;'>";
                    echo "<td>" . htmlspecialchars($row['transactNo']) . "</td>"; // TransactNo
                    echo "<td class='flight-date'>" . htmlspecialchars($row['FlightDate']) . "</td>"; // To/From (Flight Date Range)
                    echo "<td class='text-center'>" . htmlspecialchars($row['pax']) . "</td>"; // Pax (Number of Passengers)
                    echo "<td>
                          <div>
                              <span>" . htmlspecialchars('Autumn Tour Package') . "</span> <!-- Booking Type -->
                              <br>
                              <span>₱ " . number_format($row['packagePrice'], 2) . "</span> <!-- Package Price -->
                          </div>
                        </td>";

                    echo "<td>₱ " . number_format($row['totalRequestCost'], 2) . "</td>"; // Request Cost
                    echo "<td>₱ " . number_format($totalAmountToBePaid, 2) . "</td>"; // Amount to be paid (Total Price + Request Cost)
                    echo "<td>
                          <div>
                              <span>₱ " . number_format($totalAmountPaid, 2) . "</span> <!-- Amount Paid -->
                              <br>
                              Balance: <span>₱ " . number_format($balance, 2) . "</span> <!-- Balance -->
                          </div>
                        </td>";

                    echo "<td>" . htmlspecialchars($status) . "</td>"; // Status (Fully Paid or Not Paid)
                    echo "</tr>";

                  }
                } 
                else 
                {
                  // Display a message if no records are found
                  echo "<tr><td colspan='9'>No records found.</td></tr>";
                }
              }

              else
              {
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
                if ($result && $result->num_rows > 0) 
                {
                  while ($row = $result->fetch_assoc()) 
                  {
                    // Calculate Balance
                    $totalAmountPaid = $row['totalPaidAmount'];
                    $totalAmountToBePaid = $row['packagePrice'] + $row['totalRequestCost']; // Total price + total request cost
                    $balance = $totalAmountToBePaid - $totalAmountPaid; // Balance calculation

                    // Determine if fully paid or not
                    $status = ($totalAmountPaid == $totalAmountToBePaid) ? 'Fully Paid' : 'Not Paid';

                    // Display table row
                    echo "<tr data-transact-no='" . htmlspecialchars($row['transactNo']) . "' onclick='openModal(this)' style='cursor: pointer;'>";
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
                } 
                else 
                {
                  // Display a message if no records are found
                  echo "<tr><td colspan='9'>No records found.</td></tr>";
                }
              }

              
            ?>
          </tbody>
        </table>
      </div>

       <!-- <?php
      $agentRole = $_SESSION['agent_agentRole'];
      $agentCode = $_SESSION['agent_agentCode'];
      $accountId = $_SESSION['agent_accountId'];
      $agentType = $_SESSION['agent_agentType'];

      if($agentType === 'Wholeseller')
      {
        ?>
          <h6>For Comission Amount (W/S)</h6>
          <table class="product-table" id="dataTable">
            <thead>
                <tr>
                    <th>TransactNo</th>
                    <th>Flight Date</th>
                    <th>Package</th>
                    <th>Booking Type</th>
                    <th>Total Price</th>
                    <th>Commission Amount</th>
                </tr>
            </thead>
            <tbody>
              <?php
              // Get the agent ID from the session
              $agentId = $_SESSION['agent_agentId'];

              // Query to fetch the booking and commission data
              $query = "SELECT b.transactNo, CONCAT(DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y'), ' - ', 
                          DATE_FORMAT(f.returnDepartureDate, '%M %d, %Y')) AS flightDate,
                      p.packageName AS package,
                      b.bookingType,
                      b.totalPrice,
                      FORMAT((b.totalPrice * (a.comissionRate / 100)), 2) AS commissionAmount
                  FROM 
                      booking b
                  JOIN 
                      agent a ON a.agentId = b.agentId
                  JOIN 
                      flight f ON b.flightId = f.flightId
                  JOIN 
                      package p ON b.packageId = p.packageId
                  WHERE 
                      b.status = 'Confirmed' 
                      AND a.agentType = 'Wholeseller'
                      AND a.agentId = '$agentId'";

              // Execute the query
              $result = $conn->query($query);

              // Check if there are results and populate the table
              if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($row['transactNo']) . "</td>"; // TransactNo
                      echo "<td>" . htmlspecialchars($row['flightDate']) . "</td>"; // Flight Date
                      echo "<td>" . htmlspecialchars('') . "</td>"; // Flight Date
                      echo "<td>" . htmlspecialchars($row['package']) . "</td>"; // Package
                      echo "<td>" . htmlspecialchars($row['bookingType']) . "</td>"; // Booking Type
                      echo "<td>₱ " . number_format($row['totalPrice'], 2) . "</td>"; // Total Price
                      echo "<td>₱ " . htmlspecialchars($row['commissionAmount']) . "</td>"; // Commission Amount
                      echo "</tr>";
                  }
              } else {
                  // Display a message if no records are found
                  echo "<tr><td colspan='6' style='text-align: center;'>No records found.</td></tr>";
              }
              ?>
            </tbody>
          </table>
      
        <?php
      }
    ?> -->
      
    </div>
    

  </div>
</div>

<div id="modal">
  <div class="modal-container">
    <!-- Modal Header -->
    <div class="modal-header">
      <h5 class="modal-title"></h5>
      <span class="close-btn" onclick="closeModal()">&#x2716;</span>
    </div>

    <!-- Modal Body -->
    <div class="modal-body" >
      <div class="content-header">
        <h6 class="" id="modal-content"></h6>
      </div>

      <div class="content-body">


      </div>
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
      <button type="button" class="btn btn-primary">Save changes</button>
    </div>
  </div>
</div>




<?php require "../Agent Section/includes/scripts.php"; ?>

<script>
function openModal(row) {
    const transactNo = row.getAttribute('data-transact-no'); // Get the transact number
    const modalContent = document.getElementById('modal-content');
    modalContent.innerHTML = `<p>${transactNo}</p>`;
    const modal = document.getElementById('modal');
    modal.style.display = 'block';
}

function closeModal() {
    const modal = document.getElementById('modal');
    modal.style.display = 'none';
}

</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll("tr[data-url]").forEach(function(row) {
        row.addEventListener("click", function() {
            const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

            console.log("Transaction Number: ", transactionNumber); // Debugging line

            // Use AJAX to send the transaction number to the server
            $.ajax({
                url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
                type: 'POST',
                data: { transaction_number: transactionNumber },
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


<script>
 const table = $('#product-table').DataTable({
        dom: 'rtip',
        columnDefs: [
          {width:'12%', targets:0}, // Transact No.
          {width:'25%', targets:1}, // To/From
          {width:'5%', targets:2}, // Pax
          {width:'15%', targets:3}, // Booking Type
          {width:'12%', targets:4}, // Package Price
          {width:'12%', targets:5}, // Amount to be Paid
          {width:'12%', targets:6}, // Amount Paid
          {width:'5%', targets:7} // Status

        ],
        language: {
            emptyTable: "No Transaction Records Available"
        },
        order: [[0, 'desc']],
        scrollX: false,
        autoWidth: false,
        pageLength: 11, // Limit the number of rows per page to 8
    });
</script>


</body>
</html>