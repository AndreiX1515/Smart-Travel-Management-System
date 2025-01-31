
<?php 
	session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Employee - Transactions</title>
	<?php include '../Employee Section/includes/emp-head.php' ?>
	<link rel="stylesheet" href="../Employee Section/assets/css/emp-tableRequestPayment.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
  <?php include '../Employee Section/includes/emp-navbar.php' ?>

  <div class="main-content">
		<div class="content-container">
			
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

      <div id="result-container"></div>

      <div class="content-footer">
        <!-- <button class="btn btn-secondary" id="preview-btn">Preview</button> -->
        <button class="btn btn-primary" id="download-btn" disabled>Generate SoA</button>
      </div>
		</div>

  </div>
</div>


<?php include '../Employee Section/includes/emp-scripts.php' ?>

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
    xhr.open('POST', '../Employee Section/functions/fetchSoA.php', true); // Replace with your PHP file name
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    const data = `companyId=${companyId}&month=${month}&year=${year}`;

    xhr.onload = function() 
    {
      // Re-enable the button after the request is complete
      document.getElementById('generate-soa-btn').disabled = false;

      if (xhr.status === 200) 
      {
        // Parse the JSON response
        const response = JSON.parse(xhr.responseText);

        if (response.dataAvailable) 
        {
          // Update the result container with the HTML from the response
          resultContainer.innerHTML = response.htmlContent;
          // Enable the download button if data is available
          document.getElementById('download-btn').disabled = false;
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
