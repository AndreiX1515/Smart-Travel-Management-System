
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
      <!-- <div class="counts-section">
        
      </div> -->

      <div class="table-actions">
        <div class="row">
          <div class="columns col-md-2">
            <div class="table-filters-container">
              <label for="company-filter ">Company Name:</label>
              <select id="company-filter" name="company-filter" class="form-control">
                <option value="All">Select a company</option>
                <?php
                  // Execute the SQL query
                  $sql1 = "SELECT branchId, branchName FROM branch";
                  $res1 = $conn->query($sql1);

                  // Check if there are results
                  if ($res1->num_rows > 0) 
                  {
                    // Loop through the results and generate options
                    while ($row = $res1->fetch_assoc()) 
                    {
                      echo "<option value='" . $row['branchId'] . "'>" . $row['branchName'] . "</option>";
                    }
                  } 
                  else 
                  {
                    echo "<option value=''>No companies available</option>";
                  }
                ?>
              </select>
            </div>
          </div>

          <div class="columns col-md-2">
            <div class="table-filters-container">
              <label for="month-filter">Month</label>
              <select id="month-filter" name="month-filter" class="form-control">
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

          <script>
            // Get the current month as a number (0 = January, 1 = February, ..., 11 = December)
            const currentMonth = new Date().getMonth();
            
            // Get the select element
            const selectElement = document.getElementById('company-filter');
            
            // Select the option corresponding to the current month
            selectElement.selectedIndex = currentMonth;
          </script>

          <div class="columns col-md-3">
            <div class="table-filters-container">
              <label for="year-filter">Year</label>
              <select id="year-filter" name="year-filter" class="form-control">
                <!-- Year options will be populated dynamically -->
              </select>
            </div>
          </div>

          <script>
            // Get the current month (1 = January, 2 = February, ..., 12 = December)
            const currentMonthIndex = new Date().getMonth() + 1; // Add 1 to make it 1-based
            const currentYear = new Date().getFullYear();

            // Get the year select element
            const yearSelect = document.getElementById('year-filter');
            
            // Dynamically populate the years
            for (let i = currentYear - 5; i <= currentYear + 5; i++) {
              const option = document.createElement('option');
              option.value = i;
              option.textContent = i;
              yearSelect.appendChild(option);
            }

            // Optionally set the current year as selected
            yearSelect.value = currentYear;
            
            // Get the month select element
            const monthSelect = document.getElementById('month-filter');

            // Dynamically populate the months (1 = January, 2 = February, ..., 12 = December)
            for (let i = 1; i <= 12; i++) {
              const option = document.createElement('option');
              option.value = i; // The value will be 1-based (1, 2, 3, ..., 12)
              option.textContent = new Date(0, i - 1).toLocaleString('default', { month: 'long' }); // Convert to month name
              monthSelect.appendChild(option);
            }

            // Set the current month as selected
            monthSelect.value = currentMonthIndex; // Use 1-based month index
          </script>

        </div>

        <div class="btn-container">
          <button id="generate-soa-btn" class="btn btn-primary">
            Generate SOA
          </button>
        </div>

      </div>

      <div id="result-container"></div>
    
      <!-- <div class="table-container-product">
        <div class="table-content-product">
          <table class="product-table">
            <thead>
              <tr>
                <th>No.</th>
                <th>Description</th>
                <th>Price (USD)</th>
                <th>Price (PHP)</th>
                <th>PAX</th>
                <th>Total (USD)</th>
                <th>Total (PHP)</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $totalPriceSum = 0;
                $count = 1; // Initialize the counter
                $sql1 = "SELECT f.flightId, f.flightPrice, CONCAT(f.flightDepartureDate, ' - ', f.returnArrivalDate) AS flightDates, 
                            SUM(DISTINCT b.pax) AS pax, SUM(DISTINCT b.totalPrice) AS totalPrice
                          FROM 
                              payment p
                          LEFT JOIN 
                              booking b ON p.transactNo = b.transactNo
                          LEFT JOIN
                              flight f ON f.flightId = b.flightId
                          WHERE 
                              b.status = 'Confirmed' 
                              AND MONTH(f.flightDepartureDate) = 1
                              AND YEAR(f.flightDepartureDate) = 2025
                          GROUP BY 
                              f.flightId, f.flightDepartureDate, f.returnArrivalDate";
                $res1 = $conn->query($sql1);

                $res1 = $conn->query($sql1);

                if ($res1->num_rows > 0) 
                {
                  while ($row = $res1->fetch_assoc()) 
                  {
                    $totalPriceSum += $row['totalPrice'];
                    // Format flightPrice with commas and display the row
                    $formattedFlightPrice = number_format($row['flightPrice'], 2);
                    $formattedTotalPrice = number_format($row['totalPrice'], 2);
                    echo "<tr'>
                            <td>$count</td>
                            <td>$row[flightDates]</td>
                            <td></td>
                            <td>₱ $formattedFlightPrice</td>
                            <td>$row[pax]</td>
                            <td></td>
                            <td>₱ $formattedTotalPrice</td>
                          </tr>";
                    $count++;
                  }
                } 
              ?>
            </tbody>
          </table>

          <div class="subtotal-container">
            <div class="balance">
              <span>SUBTOTAL: </span>
            </div>
            <div class="subtotal-item-usd">
              <span>USD:</span>
              <span class="subtotal-usd"></span>
            </div>
            <div class="subtotal-item-php">
              <span>PHP:</span>
              <span class="subtotal-php">₱ <?php echo number_format($totalPriceSum, 2); ?></span>
            </div>
          </div>

          <table class="product-table">
            <tbody>
              <?php
                $totalCostSum = 0;
                $handlingFeeCount = 0;
                $sql1 = "SELECT b.flightId, cd.details, cd.price, SUM(r.pax) AS pax, SUM(r.requestCost) AS requestCost, 
                          COUNT(CASE WHEN r.handlingFee != 0 THEN 1 ELSE NULL END) AS handlingFeeCount
                        FROM 
                          `request` r
                        JOIN 
                          concerndetails cd 
                        ON 
                          r.concernDetailsId = cd.concernDetailsId
                        JOIN 
                         booking b 
                        ON 
                          r.transactNo = b.transactNo
                        JOIN
                          flight f
                        ON 
                        b.flightId = f.flightId
                        WHERE 
                          r.requestStatus = 'Confirmed'AND MONTH(f.flightDepartureDate) = 1 AND YEAR(f.flightDepartureDate) = 2025
                        GROUP BY 
                          r.concernDetailsId";

                $res1 = $conn->query($sql1);

                if ($res1->num_rows > 0) 
                {
                  while ($row = $res1->fetch_assoc()) 
                  {
                    $handlingFeeCount += $row['handlingFeeCount'];
                    $handlingFeeTotal = $handlingFeeCount * 100;
                    $handlingFeeTotal = number_format($handlingFeeTotal, 2);
                    $totalCostSum += $row['requestCost'];
                    $formattedRequestPrice = number_format($row['price'], 2);
                    $formattedRequestCost = number_format($row['requestCost'], 2);
                    echo "<tr'>
                            <td>$count</td>
                            <td>$row[details]</td>
                            <td></td>
                            <td>₱ $formattedRequestPrice</td>
                            <td>$row[pax]</td>
                            <td></td>
                            <td>₱ $formattedRequestCost</td>
                          </tr>";
                    $count++;
                  }  
                }

                echo "<tr>
                          <td>$count</td>
                          <td>Handling Fee</td>
                          <td></td>
                          <td>₱ 100.00</td>
                          <td>$handlingFeeCount</td>
                          <td></td>
                          <td>₱ $handlingFeeTotal</td>
                        </tr>";
                $count++;
              ?>
            </tbody>
          </table>

          <div class="subtotal-container">
            <div class="balance">
              <span>SUBTOTAL: </span>
            </div>
            <div class="subtotal-item-usd">
              <span>USD:</span>
              <span class="subtotal-usd"></span>
            </div>
            <div class="subtotal-item-php">
              <span>PHP:</span>
              <span class="subtotal-php">₱ <?php $total1 = $totalCostSum + $handlingFeeTotal; echo number_format($total1, 2); ?></span>
            </div>
          </div>

          <div class="balance-container">
            <div class="balance">
              <span>BALANCE:</span>
              
            </div>
            <div class="balanceUSD">
              <span>USD:</span>
              <span class="subtotal-usd"></span>
            </div>
            <div class="balancePHP">
              <span>PHP:</span>
              <span class="subtotal-php">₱ <?php $total = $totalCostSum + $totalPriceSum + $handlingFeeTotal; echo number_format($total, 2); ?></span>
            </div>
          </div>
        </div>
      </div> -->
      
    </div>
    
    <div class="content-footer">
      <button class="btn btn-secondary" id="preview-btn">Preview</button>
      <button class="btn btn-primary" id="download-btn">Download</button>
    </div>

  </div>
</div>


<!-- Modal -->

<?php require "../Agent Section/includes/scripts.php"; ?>

<script>
  document.getElementById('generate-soa-btn').addEventListener('click', function() 
  {
    const companyId = document.getElementById('company-filter').value;
    const month = document.getElementById('month-filter').value;
    const year = document.getElementById('year-filter').value;

    // Send data to PHP using AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../Agent Section/functions/fetchSoA.php', true); // Replace with your PHP file name
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    const data = `companyId=${companyId}&month=${month}&year=${year}`;
    
    xhr.onload = function() 
    {
      if (xhr.status === 200) 
      {
        // Update the result container with the server response
        document.getElementById('result-container').innerHTML = xhr.responseText;
      } 
      else 
      {
        alert('Error: ' + xhr.status);
      }
    };
    
    xhr.send(data);
  });

</script>

<!-- Modal -->
<!-- <script>
  function openModal(row) 
  {
    const transactNo = row.getAttribute('data-transact-no'); // Get the transact number
    const modalContent = document.getElementById('modal-content');
    modalContent.innerHTML = `<p>${transactNo}</p>`; // Update modal content

    // Use Bootstrap's modal methods to show the modal
    const modal = new bootstrap.Modal(document.getElementById('staticBackdrop-tablerows'));
    modal.show();
  }

  function closeModal() 
  {
    // Use Bootstrap's modal methods to hide the modal
    const modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
    modal.hide();
  }
</script> -->

<!-- Row Select -->
<!-- <script>
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
</script> -->

<script>
  const table = $('#product-table').DataTable(
  {
        dom: 'rtip',
        columnDefs: [
          {width: '12%', targets: 0}, // Transact No.
          {width: '22%', targets: 1}, // To/From
          {width: '6%', targets: 2},  // Pax
          {width: '15%', targets: 3}, // Booking Type
          {width: '12%', targets: 4}, // Package Price
          {width: '12%', targets: 5}, // Amount to be Paid
          {width: '12%', targets: 6}, // Amount Paid
          {width: '9%', targets: 7}   // Status


        ],
        language: 
        {
          emptyTable: "No Transaction Records Available"
        },
        order: [[0, 'desc']],
        scrollX: false,
        autoWidth: false,
        pageLength: 10, // Limit the number of rows per page to 8
    });
</script>


</body>
</html>