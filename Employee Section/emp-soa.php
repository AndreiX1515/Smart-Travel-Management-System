<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Transactions</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-soa.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
  <?php include '../Employee Section/includes/emp-navbar.php' ?>

  <div class="main-content">
    <div class="content-wrapper">
      <div class="content-body">
        <!-- <div class="counts-section"></div>  -->
        <div class="table-actions">
          <div class="row">

            <div class="columns col-md-3">
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

            <!-- <div class="columns col-md-3" id="flight-container">
              <div class="table-filters-container">
                <label for="flight-filter ">Select Flight Date:</label>
                <select id="flight-filter" name="flight-filter" class="form-control" onchange="toggleFilters()">
                  <option selected disabled>Select Flight Date</option>
                  <?php
                    // Execute the SQL query
                    $sql1 = "SELECT flightId, DATE_FORMAT(flightDepartureDate, '%M %d, %Y') AS formattedDepartureDate FROM flight";
                    $res1 = $conn->query($sql1);

                    // Check if there are results
                    if ($res1->num_rows > 0) 
                    {
                      // Loop through the results and generate options
                      while ($row = $res1->fetch_assoc()) 
                      {
                        echo "<option value='" . $row['flightId'] . "'>" . $row['formattedDepartureDate'] . "</option>";
                      }
                    } 
                    else 
                    {
                      echo "<option value=''>No flights available</option>";
                    }
                  ?>
                </select>
              </div>
            </div> -->

            <div class="columns col-md-2" id="month-container">
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

            <div class="columns col-md-2" id="year-container">
              <div class="table-filters-container">
                <label for="year-filter">Year</label>
                <select id="year-filter" name="year-filter" class="form-control">
                  <!-- Year options will be populated dynamically -->
                  <option selected disabled>Select year</option>
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
              for (let i = currentYear - 5; i <= currentYear + 5; i++) 
              {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                yearSelect.appendChild(option);
              }

              // Optionally set the current year as selected
              yearSelect.selectedIndex = 0;
              
              // Get the month select element
              const monthSelect = document.getElementById('month-filter');

              // Set the current month as selected
              monthSelect.selectedIndex = 0; // Use 1-based month index
            </script>

            <div class="columns col-md-2" id="reset-button-container" style="display: none;">
              <button class="btn btn-warning" onclick="resetFilters()">Reset Filters</button>
            </div>
          </div>

          <div class="btn-container">
            <button id="generate-soa-btn" class="btn btn-primary">Preview SOA</button>
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
              <div class="balance"> <span>SUBTOTAL: </span> </div>
              <div class="subtotal-item-usd"> <span>USD:</span> <span class="subtotal-usd"></span> </div>
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
                            concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
                          JOIN booking b ON r.transactNo = b.transactNo
                          JOIN flight f ON b.flightId = f.flightId
                          WHERE r.requestStatus = 'Confirmed'AND MONTH(f.flightDepartureDate) = 1 
                                AND YEAR(f.flightDepartureDate) = 2025
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
              <div class="balance"> <span>SUBTOTAL: </span> </div>
              <div class="subtotal-item-usd"> <span>USD:</span> <span class="subtotal-usd"></span> </div>
              <div class="subtotal-item-php">
                <span>PHP:</span>
                <span class="subtotal-php">₱ <?php $total1 = $totalCostSum + $handlingFeeTotal; echo number_format($total1, 2); ?></span>
              </div>
            </div>

            <div class="balance-container">
              <div class="balance"> <span>BALANCE:</span> </div>
              <div class="balanceUSD"> <span>USD:</span> <span class="subtotal-usd"></span> </div>
              <div class="balancePHP">
                <span>PHP:</span>
                <span class="subtotal-php">₱ <?php $total = $totalCostSum + $totalPriceSum + $handlingFeeTotal; echo number_format($total, 2); ?></span>
              </div>
            </div>
          </div>
        </div> -->

      </div>

      <div class="content-footer">
          <!-- <button class="btn btn-secondary" id="preview-btn">Preview</button> -->
          <button class="btn btn-primary" id="download-btn" disabled>Generate SoA</button>
        </div>
    </div>
  </div>
</div>

<?php include '../Employee Section/includes/emp-scripts.php' ?>

<!-- Filter Script -->
<!-- <script>
  function toggleFilters() 
  {
    const flightContainer = document.getElementById("flight-container");
    const flightSelect = document.getElementById("flight-filter");
    const monthContainer = document.getElementById("month-container");
    const yearContainer = document.getElementById("year-container");
    const monthSelect = document.getElementById("month-filter");
    const yearSelect = document.getElementById("year-filter");
    const resetButtonContainer = document.getElementById("reset-button-container");

    // If a Flight Date is selected, hide the month and year filters
    if (flightSelect.value !== "Select Flight Date" && flightSelect.value !== "") 
    {
      flightContainer.style.display = "block";  // Show the flight container
      monthContainer.style.display = "none";   // Hide month container
      yearContainer.style.display = "none";    // Hide year container
      resetButtonContainer.style.display = "block"; // Show reset button
    } 
    // If Month or Year is selected, hide the flight filter
    else if (monthSelect.value !== "Select month" || yearSelect.value !== "") 
    {
      flightContainer.style.display = "none";  // Hide the flight container
      monthContainer.style.display = "block"; // Show month container
      yearContainer.style.display = "block";  // Show year container
      resetButtonContainer.style.display = "block"; // Show reset button
    } 
    // If neither Flight Date, Month, nor Year is selected, show all filters
    else 
    {
      flightContainer.style.display = "block";
      monthContainer.style.display = "block";
      yearContainer.style.display = "block";
      resetButtonContainer.style.display = "none"; // Hide reset button
    }
  }

  function resetFilters() 
  {
    // Reset the values of the filters
    document.getElementById("flight-filter").value = "Select Flight Date";
    document.getElementById("month-filter").value = "Select month";
    document.getElementById("year-filter").value = "";

    // Make sure all filter containers are visible
    const flightFilter = document.getElementById("flight-filter");
    const monthFilter = document.getElementById("month-filter");
    const yearFilter = document.getElementById("year-filter");
    const flightContainer = flightFilter.closest(".columns");
    const monthContainer = monthFilter.closest(".columns");
    const yearContainer = yearFilter.closest(".columns");

    // Display all the containers
    flightContainer.style.display = "block";
    monthContainer.style.display = "block";
    yearContainer.style.display = "block";

    // Hide the reset button
    const resetButtonContainer = document.getElementById("reset-button-container");
    resetButtonContainer.style.display = "none";

    // Call the toggleFilters() function to ensure proper visibility
    toggleFilters();
  }
</script> -->

<!-- Preview SoA -->
<!-- <script>
  $(document).ready(function () 
  {
    $("#generate-soa-btn").on("click", function () 
    {
      const companyId = $("#company-filter").val();
      const month = $("#month-filter").val();
      const year = $("#year-filter").val();
      const flightId = $("#flight-filter").val();
      const resultContainer = $("#result-container");
      const downloadBtn = $("#download-btn");

      console.log(companyId);
      console.log(month);
      console.log(year);
      console.log(flightId);

      // Disable the button while processing
      $(this).prop("disabled", true);
      resultContainer.html("<p>Loading...</p>");

      let requestUrl = "";
      let requestData = {};

      if (flightId != null) 
      {
        // AJAX Request
        $.ajax(
        {
          url: "../Employee Section/functions/fetchSoAByFlightDate.php",
          type: "POST",
          data: {companyId: companyId, flightId: flightId},
          dataType: "json",
          success: function (response) 
          {
            $("#generate-soa-btn").prop("disabled", false); // Re-enable the button

            if (response.dataAvailable) 
            {
              resultContainer.html(response.htmlContent);
              downloadBtn.prop("disabled", false);
            } 
            else 
            {
              resultContainer.html("<p>No data found for the selected filters.</p>");
              downloadBtn.prop("disabled", true);
            }
          },
          error: function (xhr, status, error) 
          {
            resultContainer.html("<p>Error loading data. Please try again later.</p>");
            $("#generate-soa-btn").prop("disabled", false);
            downloadBtn.prop("disabled", true);
            console.error("AJAX Error:", status, error); // Debugging
          },
        });
      } 
      else if (month != null && year != null) 
      {
        // AJAX Request
        $.ajax(
        {
          url: "../Employee Section/functions/fetchSoA.php",
          type: "POST",
          data: {companyId: companyId, month: month, year: year},
          dataType: "json",
          success: function (response) 
          {
            $("#generate-soa-btn").prop("disabled", false); // Re-enable the button

            if (response.dataAvailable) 
            {
              resultContainer.html(response.htmlContent);
              downloadBtn.prop("disabled", false);
            } 
            else 
            {
              resultContainer.html("<p>No data found for the selected filters.</p>");
              downloadBtn.prop("disabled", true);
            }
          },
          error: function (xhr, status, error) {
            resultContainer.html("<p>Error loading data. Please try again later.</p>");
            $("#generate-soa-btn").prop("disabled", false);
            downloadBtn.prop("disabled", true);
            console.error("AJAX Error:", status, error); // Debugging
          },
        });
      }
      else 
      {
        console.log("Invalid input: Please select a flight date OR both month and year.");
      }  
    });
  });
</script> -->

<!-- Generate SoA -->
<!-- <script>
  $(document).ready(function () 
  {
    $("#download-btn").click(function () 
    {
      const companyId = $("#company-filter").val();
      const month = $("#month-filter").val();
      const year = $("#year-filter").val();
      const flightId = $("#flight-filter").val();
      const downloadBtn = $("#download-btn");
      const generateSoaBtn = $("#generate-soa-btn");
      const resultContainer = $("#result-container");
      let soaNumber = "";

      // Get current date in mm/dd/yyyy format
      const currentDate = new Date();
      const currentDateFormatted = (currentDate.getMonth() + 1).toString().padStart(2, "0") +  "/" +
                                    currentDate.getDate().toString().padStart(2, "0") +
                                    "/" + currentDate.getFullYear();

      console.log("Inputs:", { companyId, month, year, flightId }); // Debugging

      let requestUrl = "";
      let requestData = { companyId: companyId, currentDate: currentDateFormatted };

      // Determine request URL and parameters
      if (flightId) 
      {
        requestUrl = "../Employee Section/functions/emp-addSoAByFlightDate.php";
        requestData.flightId = flightId;
        // Step 1: Insert SOA Data
        $.ajax(
        {
          url: "../Employee Section/functions/emp-addSoAByFlightDate.php",
          type: "POST",
          data: requestData,
          dataType: "json",
          beforeSend: function () 
          {
            console.log("Generating SOA, please wait..."); // Debugging
            generateSoaBtn.prop("disabled", true);
            downloadBtn.prop("disabled", true);
            resultContainer.html("<p>Generating SOA, please wait...</p>");
          },
          success: function (response) 
          {
            console.log("Response received:", response); // Debugging

            if (response.success && response.soanum) 
            {
              soaNumber = response.soanum; // Extract SOA number
              console.log("SOA Number received:", soaNumber); // Debugging

              resultContainer.html("<p>SOA generated successfully!</p>");
              generateSoaBtn.prop("disabled", false);
              downloadBtn.prop("disabled", false);

              // Step 2: Generate PDF
              generateSoAPdf(soaNumber, requestData);
            } 
            else 
            {
              console.warn("Failed to generate SOA:", response); // Debugging
              resultContainer.html("<p>Failed to generate SOA. Please check your inputs.</p>");
              generateSoaBtn.prop("disabled", false);
            }
          },
          error: function (xhr, status, error) 
          {
            console.error("AJAX Error:", status, error, xhr.responseText); // Debugging
            resultContainer.html("<p>Error processing request. Please try again later.</p>");
            generateSoaBtn.prop("disabled", false);
          },
        });
      } 
      else if (month && year) 
      {
        requestData.month = month;
        requestData.year = year;

        console.log("Sending AJAX request to:", "../Employee Section/functions/emp-addSoA.php");
        console.log("Request Data:", requestData); // Debugging

        // Step 1: Insert SOA Data
        $.ajax(
        {
          url: "../Employee Section/functions/emp-addSoA.php",
          type: "POST",
          data: requestData,
          dataType: "json",
          beforeSend: function () 
          {
            console.log("Generating SOA, please wait..."); // Debugging
            generateSoaBtn.prop("disabled", true);
            downloadBtn.prop("disabled", true);
            resultContainer.html("<p>Generating SOA, please wait...</p>");
          },
          success: function (response) 
          {
            console.log("Response received:", response); // Debugging

            if (response.success && response.soanum) 
            {
              soaNumber = response.soanum; // Extract SOA number
              console.log("SOA Number received:", soaNumber); // Debugging
              console.log("Request Data: ", requestData); // Debugging

              resultContainer.html("<p>SOA generated successfully!</p>");
              generateSoaBtn.prop("disabled", false);
              downloadBtn.prop("disabled", false);

              // Step 2: Generate PDF
              generateSoAPdf(soaNumber, requestData);
            } 
            else 
            {
              console.warn("Failed to generate SOA:", response); // Debugging
              resultContainer.html("<p>Failed to generate SOA. Please check your inputs.</p>");
              generateSoaBtn.prop("disabled", false);
            }
          },
          error: function (xhr, status, error) 
          {
            console.error("AJAX Error:", status, error, xhr.responseText); // Debugging
            resultContainer.html("<p>Error processing request. Please try again later.</p>");
            generateSoaBtn.prop("disabled", false);
          },
        });
      } 
      else 
      {
        console.warn("Invalid input: Please select a flight date OR both month and year."); // Debugging
        return;
      }
    });

    // Function to Generate SOA PDF
    function generateSoAPdf(soaNumber, requestData) 
    {
      requestData.soaNumber = soaNumber;

      $.ajax(
      {
        url: "../Employee Section/functions/generateSoA.php",
        type: "POST",
        data: requestData,
        xhr: function () 
        {
          let xhr = new XMLHttpRequest();
          xhr.responseType = "blob"; // Expect binary response
          return xhr;
        },
        success: function (data) 
        {
          const blob = new Blob([data], { type: "application/pdf" });
          const link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          link.download = `Statement_of_Account_${soaNumber}.pdf`;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        },
        error: function (xhr, status, error) 
        {
          console.error("PDF Generation Error:", status, error, xhr.responseText);
          alert("Failed to generate the SOA PDF. Please try again.");
        }
      });
    }
  });
</script> -->

<!-- Orig Preview SoA -->
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

<!-- Orig Generate SoA -->
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
    xhrAddSoA.open('POST', '../Agent Section/functions/agent-addSoA.php', true);
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
          xhrPdf.open('POST', '../Agent Section/functions/generateSoA.php', true);
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
