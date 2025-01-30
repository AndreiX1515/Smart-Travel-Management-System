
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statement of Account (FIT)</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-soa.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">

</head>

<body>
<?php include '../Agent Section/includes/sidebar.php'; ?> 

<div class="main-content" id="mainContent">
    
  <?php
    
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
            <h1>Statement of Accounts (SoA FIT)</h1>
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
          <div class="columns col-md-2">
            <div class="table-filters-container">
              <label for="company-filter ">Company Name:</label>
              <select id="company-filter" name="company-filter" class="form-control">
                <option selected disabled>Select a company</option>
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

      <div>
        <button class="btn btn-primary" id="download-btn" disabled>Generate SoA</button>
      </div>
    </div>
  </div>
</div>

<?php require "../Agent Section/includes/scripts.php"; ?>

<!-- Preview SoA -->
<script>
  document.getElementById('generate-soa-btn').addEventListener('click', function() 
  {
    const companyId = document.getElementById('company-filter').value;
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

    const data = `companyId=${companyId}&month=${month}&year=${year}`;

    xhr.onload = function() 
    {
      document.getElementById('generate-soa-btn').disabled = false;

      // console.log("Raw Response:", xhr.responseText); // Debugging output

      if (xhr.status === 200) 
      {
        try {
          const response = JSON.parse(xhr.responseText); // This is where the error occurs
          if (response.dataAvailable) {
            resultContainer.innerHTML = response.htmlContent;
            document.getElementById('download-btn').disabled = false;
          } else {
            resultContainer.innerHTML = '<p>No data found for the selected filters.</p>';
            document.getElementById('download-btn').disabled = true;
          }
        } catch (error) {
          console.error("JSON Parsing Error:", error, "Response:", xhr.responseText);
          resultContainer.innerHTML = '<p>Error processing response. Please try again.</p>';
          document.getElementById('download-btn').disabled = true;
        }
      } 
      else 
      {
        resultContainer.innerHTML = `<p>Error loading data. Status Code: ${xhr.status}. Please try again later.</p>`;
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

<!-- Generate SoA -->
<script>
  document.getElementById('download-btn').addEventListener('click', function() 
  {
    const companyId = document.getElementById('company-filter').value;
    const month = document.getElementById('month-filter').value;
    const year = document.getElementById('year-filter').value;

    // Get current date in mm/dd/yyyy format
    const currentDate = new Date();
    const currentDateFormatted = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '/' +
                                  currentDate.getDate().toString().padStart(2, '0') + '/' +
                                  currentDate.getFullYear();

    // First, send the request to agent-addSoA.php to insert SOA data
    const xhrAddSoA = new XMLHttpRequest();
    xhrAddSoA.open('POST', '../Agent Section/functions/agent-addSoAFIT.php', true);
    xhrAddSoA.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhrAddSoA.responseType = 'json'; // Expect JSON response for the SOA number

    xhrAddSoA.onload = function() 
    {
      if (xhrAddSoA.status === 200) 
      {
        const response = xhrAddSoA.response;
        
        if (response.soanum) 
        {
          const soaNumber = response.soanum; // Get the generated SOA number

          // Proceed to generate the SOA PDF
          const xhrPdf = new XMLHttpRequest();
          xhrPdf.open('POST', '../Agent Section/functions/generateSoAFIT.php', true);
          xhrPdf.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhrPdf.responseType = 'blob';

          xhrPdf.onload = function() 
          {
            if (xhrPdf.status === 200) 
            {
              // Create a link to download the PDF
              const blob = new Blob([xhrPdf.response], { type: 'application/pdf' });
              const link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = `Statement_of_Account_${soaNumber}.pdf`;
              link.click();
            } 
            else 
            {
              alert('Failed to generate the SOA PDF. Please try again.');
            }
          };

          xhrPdf.onerror = function() {
            alert('An error occurred while generating the SOA PDF.');
          };

          // Send the request to generate the SOA PDF with the SOA number
          xhrPdf.send(`companyId=${companyId}&month=${month}&year=${year}&currentDate=${currentDateFormatted}&soaNumber=${soaNumber}`);
        } 
        else 
        {
          alert('Failed to generate SOA Number. Please try again.');
        }
      } 
      else 
      {
        alert('Failed to insert SOA number. Server error: ' + xhrAddSoA.statusText);
      }
    };

    xhrAddSoA.onerror = function() 
    {
      alert('An error occurred while processing the request to insert SOA data.');
    };

    // Send the request with the necessary values for SOA number
    xhrAddSoA.send(`companyId=${companyId}&month=${month}&year=${year}&currentDate=${currentDateFormatted}`);
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