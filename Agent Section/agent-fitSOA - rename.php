<?php 
session_start();
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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-soa.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar copy.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="body-container">
  <?php include "../Agent Section/includes/sidebar copy.php"; ?>

  <div class="main-content-container">
    <div class="navbar">
      <h5 class="title-page">Transactions</h5>
    </div>

    <div class="main-content">
      <div class="content-body">
        <div class="table-actions">
          <div class="row">
            <div class="columns col-md-2">
              <div class="table-filters-container">
                
              </div>
            </div>

             <div class="columns col-md-2">
                <div class="table-filters-container">
                  <label for="month-filter">Month</label>
                  <select id="month-filter" name="month-filter" class="form-control">
                    <option selected disabled>Select month</option>
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
                
                // Get the select element for the month filter
                const selectElement = document.getElementById('month-filter');
                
                // Set the selected index to the current month (currentMonth is 0-based)
                selectElement.selectedIndex = currentMonth + 1; // Add 1 to account for the "Select month" option
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

              // Set the current month as selected
              monthSelect.value = currentMonthIndex; // Use 1-based month index
            </script>

          </div>

          <div class="btn-container">
            <button id="generate-soa-btn" class="btn btn-primary">
              Preview SOA
            </button>
          </div>

        </div>

        <div id="result-container">

        
        </div>

        <div class="content-footer" id="content-footer">
          <button class="btn btn-primary" id="download-btn" disabled>Generate SoA</button>
        </div>

      </div>
    </div>
  </div>

</div>


<?php require "../Agent Section/includes/scripts.php"; ?>

<script>
  document.getElementById('generate-soa-btn').addEventListener('click', function() 
  {
    const month = document.getElementById('month-filter').value;
    const year = document.getElementById('year-filter').value;

    // Disable the button while the request is in progress
    document.getElementById('generate-soa-btn').disabled = true;

    // Show a loading indicator
    const resultContainer = document.getElementById('result-container');
    resultContainer.innerHTML = '<p>Loading...</p>';

    // Send data to PHP using AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../Agent Section/functions/fetchSoAFIT.php', true); // Replace with your PHP file name
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    const data = `month=${month}&year=${year}`;

    xhr.onload = function() 
    {
      // Re-enable the button after the request is complete
      document.getElementById('generate-soa-btn').disabled = false;

      if (xhr.status === 200) 
      {
        // Parse the JSON response
        const response = JSON.parse(xhr.responseText);

        if (response.dataAvailable) {
          // Update the result container with the HTML from the response
          resultContainer.innerHTML = response.htmlContent;
          // Enable the download button if data is available
          document.getElementById('download-btn').disabled = false;
          document.getElementById('content-footer').style.display = 'flex';
        } 
        else 
        {
          // If no data available, update the result container and disable the button
          resultContainer.innerHTML = '<p>No data found for the selected filters.</p>';
          document.getElementById('download-btn').disabled = true;
        }
      } 
      else 
      {
        // Handle errors in the request
        resultContainer.innerHTML = '<p>Error loading data. Please try again later.</p>';
        document.getElementById('download-btn').disabled = true;
      }
    };

    xhr.onerror = function() 
    {
      // Handle network errors
      resultContainer.innerHTML = '<p>Network error. Please check your connection and try again.</p>';
      document.getElementById('generate-soa-btn').disabled = false;
      document.getElementById('download-btn').disabled = true;
    };

    // Send the data to the server
    xhr.send(data);
  });
</script>

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