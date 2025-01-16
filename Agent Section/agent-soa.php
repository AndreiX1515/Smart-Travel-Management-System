
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
                    <option value="P91 Travel & Tours">P91 Travel & Tours</option>
                    <option value="APD Travel & Tours">APD Travel & Tours</option>
                    <option value="FRANCIA Travel & Tours">FRANCIA Travel & Tours</option>
                    <option value="Travel Escape">Travel Escape</option>
                    <option value="FRANCIA Travel & Tours">EWINER</option>
                </select>
              </div>
          </div>

          <div class="columns col-md-2">
            <div class="table-filters-container">
              <label for="company-filter">Month</label>
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
            // Get the current month as a number (0 = January, 1 = February, ..., 11 = December)
            const currentMonthIndex = new Date().getMonth();
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
            
            // Get the month select element and set the current month as selected
            const monthSelect = document.getElementById('month-filter');
            monthSelect.selectedIndex = currentMonthIndex;
          </script>

        </div>

        <div class="btn-container"> 
         <!--  data-bs-toggle="modal" data-bs-target="#staticBackdrop" -->
          <button id="generate-soa-btn" class="btn btn-primary" >
            Generate SOA
          </button>
        </div>

      </div>
    
      <script>
          // Get the generate SOA button and table container
          const generateSoaBtn = document.getElementById('generate-soa-btn');
          const tableContainer = document.querySelector('.table-content-product');

          // Add event listener to the button to show the table container
          generateSoaBtn.addEventListener('click', function() {
              // Get the selected values
              const company = document.getElementById('company-filter').value;
              const month = document.getElementById('month-filter').value;
              const year = document.getElementById('year-filter').value;

              // Fetch data based on selected values (you can replace this part with your data-fetching logic)
              console.log('Company:', company);
              console.log('Month:', month);
              console.log('Year:', year);

              // Show the table container
              tableContainer.style.display = 'block';

              // Optionally, you can dynamically populate the table based on the selected filters
              // For example, using AJAX to fetch the data from the server
              // Here you could write AJAX or other logic to load the relevant data into the table.
          });
      </script>

      <div class="table-container-product">
        <div class="table-content-product" style="display: none;">
          <table class="product-table" id="soa-table" >
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
             
              <tr>
                <td>2</td>
                <td>Hotel Stay B</td>
                <td>150</td>
                <td>7,500</td>
                <td>5</td>
                <td>750</td>
                <td>37,500</td>
              </tr>
              <tr>
                <td>3</td>
                <td>Flight C</td>
                <td>300</td>
                <td>15,000</td>
                <td>8</td>
                <td>2,400</td>
                <td>120,000</td>
              </tr>
              <tr>
                <td>4</td>
                <td>Tour Package D</td>
                <td>450</td>
                <td>22,500</td>
                <td>12</td>
                <td>5,400</td>
                <td>270,000</td>
              </tr>
              <tr>
                <td>5</td>
                <td>Hotel Stay E</td>
                <td>200</td>
                <td>10,000</td>
                <td>6</td>
                <td>1,200</td>
                <td>60,000</td>
              </tr>
              <tr>
                <td>6</td>
                <td>Flight F</td>
                <td>250</td>
                <td>12,500</td>
                <td>7</td>
                <td>1,750</td>
                <td>87,500</td>
              </tr>
              <tr>
                <td>7</td>
                <td>Tour Package G</td>
                <td>600</td>
                <td>30,000</td>
                <td>15</td>
                <td>9,000</td>
                <td>450,000</td>
              </tr>
            </tbody>
          </table>


          <div class="subtotal-container">
            <div class="balance">
              <span>SUBTOTAL: </span>
            </div>
            <div class="subtotal-item-usd">
              <span>USD:</span>
              <span class="subtotal-usd">1,275,000</span>
            </div>
            <div class="subtotal-item-php">
              <span>PHP:</span>
              <span class="subtotal-php">1,275,000</span>
            </div>
          </div>

          <table class="product-table">
            <!-- <thead>
              <tr>
                <th>No.</th>
                <th>Contents</th>
                <th>Price (USD)</th>
                <th>Price (PHP)</th>
                <th>PAX</th>
                <th>Total (USD)</th>
                <th>Total (PHP)</th>
              </tr>
            </thead> -->
            <tbody>
             
              <tr>
                <td>2</td>
                <td>Hotel Stay B</td>
                <td>150</td>
                <td>7,500</td>
                <td>5</td>
                <td>750</td>
                <td>37,500</td>
              </tr>
              <tr>
                <td>3</td>
                <td>Flight C</td>
                <td>300</td>
                <td>15,000</td>
                <td>8</td>
                <td>2,400</td>
                <td>120,000</td>
              </tr>
              <tr>
                <td>4</td>
                <td>Tour Package D</td>
                <td>450</td>
                <td>22,500</td>
                <td>12</td>
                <td>5,400</td>
                <td>270,000</td>
              </tr>
              <tr>
                <td>5</td>
                <td>Hotel Stay E</td>
                <td>200</td>
                <td>10,000</td>
                <td>6</td>
                <td>1,200</td>
                <td>60,000</td>
              </tr>
              <tr>
                <td>6</td>
                <td>Flight F</td>
                <td>250</td>
                <td>12,500</td>
                <td>7</td>
                <td>1,750</td>
                <td>87,500</td>
              </tr>
              <tr>
                <td>7</td>
                <td>Tour Package G</td>
                <td>600</td>
                <td>30,000</td>
                <td>15</td>
                <td>9,000</td>
                <td>450,000</td>
              </tr>
              <tr>
                <td>7</td>
                <td>Tour Package G</td>
                <td>600</td>
                <td>30,000</td>
                <td>15</td>
                <td>9,000</td>
                <td>450,000</td>
              </tr>
              <tr>
                <td>7</td>
                <td>Tour Package G</td>
                <td>600</td>
                <td>30,000</td>
                <td>15</td>
                <td>9,000</td>
                <td>450,000</td>
              </tr>
              <tr>
                <td>7</td>
                <td>Tour Package G</td>
                <td>600</td>
                <td>30,000</td>
                <td>15</td>
                <td>9,000</td>
                <td>450,000</td>
              </tr>
            </tbody>
          </table>


          <div class="subtotal-container">
            <div class="balance">
              <span>SUBTOTAL: </span>
            </div>
            <div class="subtotal-item-usd">
              <span>USD:</span>
              <span class="subtotal-usd">1,275,000</span>
            </div>
            <div class="subtotal-item-php">
              <span>PHP:</span>
              <span class="subtotal-php">1,275,000</span>
            </div>
          </div>


          <div class="balance-container">
            <div class="balance">
              <span>BALANCE:</span>
              
            </div>
            <div class="balanceUSD">
              <span>USD:</span>
              <span class="subtotal-usd">1,275,000</span>
            </div>
            <div class="balancePHP">
              <span>PHP:</span>
              <span class="subtotal-php">1,275,000</span>
            </div>
          </div>



        </div>
      </div>
    </div>
    
    <div class="content-footer">
      <button class="btn btn-secondary" id="preview-btn">Preview</button>
      <button class="btn btn-primary" id="download-btn">Download</button>
    </div>

  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop-tablerows" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <div class="content-header">
          <h6 id="modal-content">Modal Content Here</h6>
        </div>
        <div class="content-body">
          <!-- Additional content for the modal body goes here -->
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>

    </div>
  </div>
</div>


<div class="modal fade modalSOA" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title" id="staticBackdropLabel">Generate SOA</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="content-header">

          <div class="row">
            <div class="columns col-md-2">
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

            <div class="columns col-md-2">
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

          <div class="btn-container">
            <button id="generate-soa-btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
              Generate SOA
            </button>
          </div>
        </div>

        <div class="content-body">


        </div>

        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">Generate</button>
      </div>
    </div>
  </div>
</div>


<?php require "../Agent Section/includes/scripts.php"; ?>

<script>
  $(document).ready(function () {
  // Dynamically populate year options
  const currentYear = new Date().getFullYear();
  const yearSelect = $('#year-filter');
  for (let i = currentYear - 5; i <= currentYear + 5; i++) {
    const option = $('<option>', {
      value: i,
      text: i
    });
    yearSelect.append(option);
  }
  yearSelect.val(currentYear); // Set the current year as default

  // Handle the Generate SOA button click
  $('#generate-soa-btn').on('click', function () {
    const companyName = $('#company-filter').val();
    const month = $('#month-filter').val();
    const year = $('#year-filter').val();

    // Check if all required values are selected
    if (companyName !== 'All' && month && year) {
      // Show the table container
      $('.table-content-product').show();

      // Simulating an AJAX call to fetch data (dummy data)
      setTimeout(function () {
        // Dummy data based on the selection
        const dummyData = [
          { no: 1, description: 'Hotel Stay B', priceUSD: 150, pricePHP: 7500, pax: 5, totalUSD: 750, totalPHP: 37500 },
          { no: 2, description: 'Flight C', priceUSD: 300, pricePHP: 15000, pax: 8, totalUSD: 2400, totalPHP: 120000 },
          { no: 3, description: 'Tour Package D', priceUSD: 450, pricePHP: 22500, pax: 12, totalUSD: 5400, totalPHP: 270000 },
          { no: 4, description: 'Hotel Stay E', priceUSD: 200, pricePHP: 10000, pax: 6, totalUSD: 1200, totalPHP: 60000 },
          { no: 5, description: 'Flight F', priceUSD: 250, pricePHP: 12500, pax: 7, totalUSD: 1750, totalPHP: 87500 },
          { no: 6, description: 'Tour Package G', priceUSD: 600, pricePHP: 30000, pax: 15, totalUSD: 9000, totalPHP: 450000 }
        ];

        // Populate the table with dummy data
        let tableContent = '';
        dummyData.forEach(function (row) {
          tableContent += `<tr>
            <td>${row.no}</td>
            <td>${row.description}</td>
            <td>${row.priceUSD}</td>
            <td>${row.pricePHP}</td>
            <td>${row.pax}</td>
            <td>${row.totalUSD}</td>
            <td>${row.totalPHP}</td>
          </tr>`;
        });

        // Insert the rows into the table body
        $('.table-content-product tbody').html(tableContent);
      }, 500); // Simulating AJAX delay
    } else {
      // If any filter is not selected, show an alert
      alert('Please select a valid company, month, and year.');
    }
  });
});

</script>








<script>

function openModal(row) {
    const transactNo = row.getAttribute('data-transact-no'); // Get the transact number
    const modalContent = document.getElementById('modal-content');
    modalContent.innerHTML = `<p>${transactNo}</p>`; // Update modal content

    // Use Bootstrap's modal methods to show the modal
    const modal = new bootstrap.Modal(document.getElementById('staticBackdrop-tablerows'));
    modal.show();
}

function closeModal() {
    // Use Bootstrap's modal methods to hide the modal
    const modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
    modal.hide();
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
          {width: '12%', targets: 0}, // Transact No.
          {width: '22%', targets: 1}, // To/From
          {width: '6%', targets: 2},  // Pax
          {width: '15%', targets: 3}, // Booking Type
          {width: '12%', targets: 4}, // Package Price
          {width: '12%', targets: 5}, // Amount to be Paid
          {width: '12%', targets: 6}, // Amount Paid
          {width: '9%', targets: 7}   // Status


        ],
        language: {
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