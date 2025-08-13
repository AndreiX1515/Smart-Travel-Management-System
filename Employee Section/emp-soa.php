<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOA</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>


  <!-- Page Layout CSS -->
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/components/page-layout-tabs.css?v=<?php echo time(); ?>">

  <!-- Components CSS -->
  <link rel="stylesheet" href="../Employee Section/assets/css/components/table-clean.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/components/table-header.css?v=<?php echo time(); ?>">


  <!-- Page Specific CSS -->
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-soa.css?v=<?php echo time(); ?>">

</head>

<body>

  <?php include '../Employee Section/includes/emp-sidebar.php' ?>

  <!-- Main Container -->
  <div class="main-container">

    <div class="navbar">
      <div class="page-header-wrapper">

        <div class="page-header-top">
          <div class="back-btn-wrapper">
            <button class="back-btn" id="redirect-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
          </div>
        </div>

        <div class="page-header-content">
          <div class="page-header-text">
            <h5 class="header-title">SOA</h5>
          </div>
        </div>

      </div>
    </div>

    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Employee Section/emp-dashboard.php'; // Replace with your actual URL
      });
    </script>

    <div class="main-content">

      <div class="table-wrapper">

        <div class="table-header">

          <div class="top-part-wrapper">

            <div class="row mb-3">

              <div class="col-md-12">
                <label class="mb-2">Filter by:</label><br />

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="filterMode" id="filterByFlight" value="flight"
                    checked>
                  <label class="form-check-label" for="filterByFlight">Flight Date</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="filterMode" id="filterByMonth" value="month">
                  <label class="form-check-label" for="filterByMonth">Month + Year</label>
                </div>

              </div>
            </div>

          </div>

          <div class="bottom-part-wrapper">

            <!-- Filters Row -->
            <div class="row w-50">

              <!-- Branch Select -->
              <div class="bottom-col col-md-3 mb-3">
                <label for="company-filter">Branch:</label>
                <select id="company-filter" name="company-filter" class="form-control">
                  <option selected disabled>Select a Branch</option>
                  <?php
                  $sql1 = "SELECT branchId, branchName FROM branch";
                  $res1 = $conn->query($sql1);
                  if ($res1->num_rows > 0) {
                    while ($row = $res1->fetch_assoc()) {
                      echo "<option value='" . $row['branchId'] . "'>" . $row['branchName'] . "</option>";
                    }
                  } else {
                    echo "<option value=''>No Branches available</option>";
                  }
                  ?>
                </select>
              </div>

              <!-- Flight Date Select -->
              <div class="bottom-col col-md-3 mb-3" id="flight-container">
                <label for="flight-filter">Flight Date:</label>
                <select id="flight-filter" name="flight-filter" class="form-control">
                  <option value="Select Flight Date" selected disabled>Select Flight Date</option>
                  <?php
                  $sql1 = "SELECT DISTINCT flightDepartureDate FROM flight ORDER BY flightDepartureDate ASC";
                  $result = $conn->query($sql1);
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $formattedFlightDate = date("F j, Y", strtotime($row['flightDepartureDate']));
                      echo "<option value='" . $row['flightDepartureDate'] . "'>$formattedFlightDate</option>";
                    }
                  } else {
                    echo "<option value='' disabled>No flights available</option>";
                  }
                  ?>
                </select>
              </div>

              <!-- Month Select -->
              <div class="bottom-col col-md-3 mb-3 filter-month" id="month-container" style="display:none;">
                <label class="mb-2" for="month-filter">Month</label>
                <select id="month-filter" name="month-filter" class="form-control">
                  <option value="" selected disabled>Select month</option>
                  <?php
                  $months = [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December"
                  ];
                  foreach ($months as $index => $month) {
                    echo "<option value='" . ($index + 1) . "'>$month</option>";
                  }
                  ?>
                </select>
              </div>

              <!-- Year Select -->
              <div class="bottom-col col-md-3 mb-3 filter-month" id="year-container" style="display:none;">
                <label class="mb-2" for="year-filter">Year</label>
                <select id="year-filter" name="year-filter" class="form-control">
                  <option value="" selected disabled>Select year</option>
                </select>
              </div>
            </div>

            <!-- Buttons -->
            <div class="row w-50">
              <label for=""></label>
              <div class="col-12 d-flex justify-content-end">
                <div class="me-2">
                  <button id="generate-soa-btn" class="btn btn-primary">Preview SOA</button>
                </div>
                <div class="me-2">
                  <button id="reset-filter-btn" class="btn btn-secondary">Reset Filters</button>
                </div>
              </div>
            </div>




          </div>

        </div>

        <!-- Tab Content -->
        <div class="tab-content">

          <div id="soaWrapper" class="" style="display: none; overflow-x: auto;">

            <table id="soaTable" class="table-clean">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Contents</th>
                  <th>Price ($)</th>
                  <th>Price (₱)</th>
                  <th>PAX</th>
                  <th>Total ($)</th>
                  <th>Total (₱)</th>
                </tr>
              </thead>

              <!-- Flight Rows + Subtotal -->
              <tbody id="soaFlightsBody">
                <!-- All flight rows including subtotal will be inserted here from JS -->
              </tbody>

              <!-- Request Rows + Subtotal -->
              <tbody id="soaRequestsBody">
                <!-- All request rows including subtotal will be inserted here from JS -->
              </tbody>

              <!-- Payment Rows + Subtotal -->
              <tbody id="soaPaymentsBody">
                <!-- All payment rows including subtotal will be inserted here from JS -->
              </tbody>

              <!-- Final Balance -->
              <tfoot>
                <tr id="soaBalance" style="font-weight: bold; background-color: #f8f9fa;">
                  <td colspan="5" class="text-center">BALANCE:</td>
                  <td><span id="balanceUSD"></span></td>
                  <td style="color: red;"><span id="balancePHP"></span></td>
                </tr>
              </tfoot>
            </table>

            <div class="d-flex justify-content-end mt-3">
              <button id="download-btn" class="btn btn-success btn-sm" disabled>Download</button>
            </div>
          </div>


        </div>
      </div>
    </div>


  </div>
 
  <?php include '../Employee Section/includes/emp-scripts.php' ?>

  <!-- Filter Script -->
  <script>
    // Populate Year Select
    const yearSelect = document.getElementById("year-filter");
    const currentYear = new Date().getFullYear();
    for (let i = currentYear - 5; i <= currentYear + 5; i++) {
      const option = document.createElement("option");
      option.value = i;
      option.textContent = i;
      yearSelect.appendChild(option);
    }

    // Toggle filter visibility based on radio selection
    function updateFilterDisplay() {
      const isFlight = document.getElementById("filterByFlight").checked;
      document.querySelectorAll(".filter-flight").forEach(el => el.style.display = isFlight ? "block" : "none");
      document.querySelectorAll(".filter-month").forEach(el => el.style.display = isFlight ? "none" : "block");
    }

    document.querySelectorAll('input[name="filterMode"]').forEach(radio => {
      radio.addEventListener("change", updateFilterDisplay);
    });

    window.addEventListener("DOMContentLoaded", updateFilterDisplay);
  </script>

  <!-- Reset Filter Script -->
  <script>
    document.getElementById("reset-filter-btn").addEventListener("click", function () {
      // Reset all dropdowns to their default (disabled) option
      const dropdowns = ["company-filter", "flight-filter", "month-filter", "year-filter"];
      dropdowns.forEach(id => {
        const select = document.getElementById(id);
        if (select) select.selectedIndex = 0;
      });

      // Reset filter mode to default: Flight Date
      const flightRadio = document.getElementById("filterByFlight");
      if (flightRadio) {
        flightRadio.checked = true;
      }

      // Toggle filter section visibility
      document.querySelectorAll(".filter-flight").forEach(el => el.style.display = "block");
      document.querySelectorAll(".filter-month").forEach(el => el.style.display = "none");

      // Optionally reset SOA display if present
      const soaWrapper = document.getElementById("soaWrapper");
      if (soaWrapper) {
        document.getElementById("soaFlightsBody").innerHTML = "";
        document.getElementById("soaPaymentsBody").innerHTML = "";
        document.getElementById("balancePHP").innerText = "₱ 0.00";
        document.getElementById("balanceUSD").innerText = "0.00";
        soaWrapper.style.display = "none";
      }

      // Disable download button if exists
      const downloadBtn = document.getElementById("download-btn");
      if (downloadBtn) downloadBtn.disabled = true;

      console.log("✅ Filters reset to default.");
    });
  </script>

  <!-- Debug Script when changing values -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const companyFilter = document.getElementById("company-filter");
      const flightFilter = document.getElementById("flight-filter");
      const monthFilter = document.getElementById("month-filter");
      const yearFilter = document.getElementById("year-filter");

      const filterByFlight = document.getElementById("filterByFlight");
      const filterByMonth = document.getElementById("filterByMonth");

      function logCurrentSelection() {
        console.clear();
        const filterMode = document.querySelector('input[name="filterMode"]:checked')?.value;
        const selectedCompany = companyFilter?.value;
        const selectedFlight = flightFilter?.value;
        const selectedMonth = monthFilter?.value;
        const selectedYear = yearFilter?.value;

        console.log("===== DEBUG LOG =====");
        console.log("Filter Mode:", filterMode);
        console.log("Company ID:", selectedCompany);
        console.log("Flight Date:", selectedFlight);
        console.log("Month:", selectedMonth);
        console.log("Year:", selectedYear);
        console.log("======================");
      }

      // Bind change listeners
      [companyFilter, flightFilter, monthFilter, yearFilter].forEach(el => {
        if (el) {
          el.addEventListener('change', logCurrentSelection);
        }
      });

      [filterByFlight, filterByMonth].forEach(el => {
        el.addEventListener('change', () => {
          console.log("Changed filter mode to:", el.value);
          logCurrentSelection();
        });
      });

      // Trigger log on page load
      logCurrentSelection();
    });
  </script>

  <!-- Preview SOA New -->
  <script>
    let soaPreviewData = null;

    document.getElementById('generate-soa-btn').addEventListener('click', function () {
      const companySelect = document.getElementById('company-filter');
      const monthFilter = document.getElementById('month-filter');
      const yearFilter = document.getElementById('year-filter');
      const flightFilter = document.getElementById('flight-filter');

      const companyId = companySelect && companySelect.selectedIndex > 0 ? companySelect.value : null;
      const flightId = flightFilter && flightFilter.value !== "Select Flight Date" && flightFilter.value !== "" ? flightFilter.value : null;
      const month = monthFilter && monthFilter.value !== "" ? monthFilter.value : null;
      const year = yearFilter && yearFilter.value !== "" ? yearFilter.value : null;

      console.log("Selected Company ID:", companyId);
      console.log("Selected Flight ID:", flightId);
      console.log("Selected Month:", month);
      console.log("Selected Year:", year);

      console.log("Selected Filters:", {
        companyId: companyId,
        flightId: flightId,
        month: month,
        year: year
      });

      let url = '';
      let params = new URLSearchParams();

      if (companyId) {
        if (month && year) {
          url = '../Employee Section/functions/fetchSoA.php';
          params.append('companyId', companyId);
          params.append('month', month);
          params.append('year', year);
        } else if (flightId && flightId !== "Select Flight Date" && flightId !== "") {
          url = '../Employee Section/functions/fetchSoAByFlightDate.php';
          params.append('companyId', companyId);
          params.append('flightId', flightId);
        } else {
          alert("Please select a valid flight or month/year for the company.");
          return false;
        }
      } else {
        alert("Please select a Company.");
        return false;
      }

      const generateBtn = document.getElementById('generate-soa-btn');
      generateBtn.disabled = true;

      const soaWrapper = document.getElementById('soaWrapper');
      document.getElementById('soaFlightsBody').innerHTML = '<tr><td colspan="7">Loading Flights...</td></tr>';
      document.getElementById('soaPaymentsBody').innerHTML = '<tr><td colspan="7">Loading Payments...</td></tr>';
      soaWrapper.style.display = 'block';

      $.ajax({
        url: url,
        method: 'POST',
        data: Object.fromEntries(params),
        dataType: 'json',
        success: function (data) {
          generateBtn.disabled = false;
          soaPreviewData = data;
          console.log("Preview data received:", soaPreviewData);

          if (data.dataAvailable) {
            // ✅ Combine flight + request rows
            $('#soaFlightsBody').html(data.flights.rows + data.requests.rows);

            const flightRows = (data.flights.rows.match(/<tr>/g) || []).length;
            const requestRows = (data.requests.rows.match(/<tr>/g) || []).length;
            let rowCounter = flightRows + requestRows + 1;

            // ✅ Subtotal of flights + requests
            const subtotalFlight = parseFloat((data.flights.subtotalPHP || '0').replace(/,/g, ''));
            const subtotalRequest = parseFloat((data.requests.subtotalPHP || '0').replace(/,/g, ''));
            const combinedSubtotal = (subtotalFlight + subtotalRequest).toLocaleString('en-US', { minimumFractionDigits: 2 });

            $('#soaFlightsBody').append(`
                <tr class="subtotal-row" style="font-weight: bold;">
                  <td>${rowCounter++}</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td colspan="2" style="background-color:rgb(253, 229, 13);">Sub Total</td>
                  <td style="background-color:rgb(253, 229, 13);">₱ ${combinedSubtotal}</td>
                </tr>
              `);

            // ✅ Process payment rows
            const paymentRows = $(data.payments.rows);
            let paymentHtml = '';

            paymentRows.each(function () {
              const cells = $(this).find('td');
              if (cells.length === 7) {
                cells.eq(0).html(rowCounter++); // renumber

                const amountCell = cells.eq(6);
                const amountText = amountCell.text().trim();
                if (amountText.startsWith('₱')) {
                  amountCell.html(`<span style="color:red;">-${amountText}</span>`);
                }

                paymentHtml += `<tr>${cells.map((i, td) => td.outerHTML).get().join('')}</tr>`;
              }
            });

            $('#soaPaymentsBody').html(paymentHtml);

            // ✅ Payment Subtotal
            const paymentSubtotalPHP = data.payments.subtotalPHP || '0.00';
            $('#soaPaymentsBody').append(`
                <tr class="subtotal-row" style="font-weight: bold;">
                  <td>${rowCounter++}</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td colspan="2" style="background-color:rgb(253, 229, 13);">Sub Total</td>
                  <td style="background-color:rgb(253, 229, 13); color: red;">-₱ ${paymentSubtotalPHP}</td>
                </tr>
              `);

            // ✅ Balance update
            const phpBalance = data.balance?.php ?? '0.00';
            const usdBalance = data.balance?.usd ?? '0.00';
            $('#balancePHP').text('-₱ ' + phpBalance);
            $('#balanceUSD').text('$ ' + usdBalance);

            // ✅ Enable export
            document.getElementById('download-btn').disabled = false;
          } else {
            displayNoData();
          }
        },
        error: function (xhr, status, error) {
          generateBtn.disabled = false;
          displayError("Error: " + error);
        }
      });

      function displayNoData() {
        document.getElementById('soaFlightsBody').innerHTML = '<tr><td colspan="7">No data found for the selected filters.</td></tr>';
        document.getElementById('soaPaymentsBody').innerHTML = '';
        document.getElementById('balancePHP').innerText = '₱ 0.00';
        document.getElementById('balanceUSD').innerText = '0.00';
        document.getElementById('download-btn').disabled = true;
      }

      function displayError(message) {
        document.getElementById('soaFlightsBody').innerHTML = `<tr><td colspan="7">${message}</td></tr>`;
        document.getElementById('soaPaymentsBody').innerHTML = '';
        document.getElementById('balancePHP').innerText = '₱ 0.00';
        document.getElementById('balanceUSD').innerText = '0.00';
        document.getElementById('download-btn').disabled = true;
      }
    });
  </script>

  <!-- Generate SOA (Excel) for Employee -->
  <script>
    document.getElementById('download-btn').addEventListener('click', function () {
      if (!soaPreviewData) {
        alert("No preview data available for export.");
        return;
      }

      const companySelect = document.getElementById('company-filter');
      const companyId = companySelect.value;
      const flightDate = document.getElementById('flight-filter').value;
      const month = document.getElementById('month-filter').value;
      const year = document.getElementById('year-filter').value;

      const accountType = "company";
      const accountId = companyId;

      const accountName = companySelect.options[companySelect.selectedIndex].text;

      const fromName = <?php echo json_encode($firstName ?? 'Unknown Employee'); ?>;
      const fromCompany = "Smart Travel";

      const currentDate = new Date();
      const currentDateFormatted = `${currentDate.getFullYear()}-${(currentDate.getMonth() + 1)
        .toString().padStart(2, '0')}-${currentDate.getDate().toString().padStart(2, '0')}`;

      console.log("Exporting SOA with the following details:");
      console.log("Account Type:", accountType);
      console.log("Account ID:", accountId);
      console.log("Flight Date:", flightDate);
      console.log("Month:", month);
      console.log("Year:", year);

      // STEP 1: Generate SOA Number
      const xhrAddSoA = new XMLHttpRequest();
      xhrAddSoA.open('POST', '../Employee Section/functions/emp-addSoA.php', true);
      xhrAddSoA.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhrAddSoA.responseType = 'json';

      xhrAddSoA.onload = function () {
        if (xhrAddSoA.status === 200 && xhrAddSoA.response?.soanum) {
          const soaNumber = xhrAddSoA.response.soanum;

          // STEP 2: Determine export mode
          const selectedFilterMode = document.querySelector('input[name="filterMode"]:checked')?.value;

          const phpFile = selectedFilterMode === "monthly"
            ? '../Employee Section/functions/generateSOAMonthly.php'
            : '../Employee Section/functions/generateSoAByFlightDate.php';

          // STEP 3: Send preview data to backend for Excel generation
          fetch(phpFile, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              soaNumber: soaNumber,
              data: soaPreviewData,
              accountName: accountName,
              fromName: fromName,
              fromCompany: fromCompany
            })
          })
            .then(response => response.blob())
            .then(blob => {
              const link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = `SOA_${soaNumber}.xlsx`;
              document.body.appendChild(link);
              link.click();
              link.remove();
            })
            .catch(error => {
              console.error("Error downloading SOA:", error);
              alert("Something went wrong while exporting the SOA.");
            });

        } else {
          alert("Failed to generate SOA number.");
        }
      };

      xhrAddSoA.onerror = function () {
        alert("An error occurred while generating SOA number.");
      };

      xhrAddSoA.send(`accountType=${accountType}&accountId=${accountId}&flightDate=${flightDate}&month=${month}&year=${year}&currentDate=${currentDateFormatted}`);
    });
  </script>





  <!-- Working Merge Preview SOA -->
  <!-- <script>
        document.getElementById('generate-soa-btn').addEventListener('click', function() {
          const companyId = document.getElementById('company-filter').value;
          const monthFilter = document.getElementById('month-filter');
          const yearFilter = document.getElementById('year-filter');
          const flightFilter = document.getElementById('flight-filter');
          const selectedText = flightFilter.options[flightFilter.selectedIndex].text;
          console.log(selectedText);

          let url = '';
          let data = `companyId=${companyId}`;

          console.log(yearFilter.value);
          console.log(monthFilter.value);

          // Determine whether to use the date filter or the flight filter
          if (monthFilter.value !== "Select month" && yearFilter.value !== "Select year") {
            // Use Month & Year (Orig Preview SoA)
            url = '../Employee Section/functions/fetchSoA.php';
            data += `&month=${monthFilter.value}&year=${yearFilter.value}`;
          } else if (flightFilter && flightFilter.value) {
            // Use Flight ID (Flight Date Preview SoA)
            url = '../Employee Section/functions/fetchSoAByFlightDate.php';
            data += `&flightId=${flightFilter.value}`;
          } else {
            // Handle case where no filter is selected
            document.getElementById('result-container').innerHTML = '<p>Please select valid filters.</p>';
            return;
          }

          // Disable the button while the request is in progress
          document.getElementById('generate-soa-btn').disabled = true;

          // Show a loading indicator
          const resultContainer = document.getElementById('result-container');
          resultContainer.innerHTML = '<p>Loading...</p>';

          // Send data to PHP using AJAX
          const xhr = new XMLHttpRequest();
          xhr.open('POST', url, true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

          xhr.onload = function() {
            // Re-enable the button after the request is complete
            document.getElementById('generate-soa-btn').disabled = false;

            if (xhr.status === 200) {
              // Parse the JSON response
              const response = JSON.parse(xhr.responseText);

              if (response.dataAvailable) {
                // Update the result container with the HTML from the response
                resultContainer.innerHTML = response.htmlContent;
                // Enable the download button if data is available
                document.getElementById('download-btn').disabled = false;
              } else {
                // If no data available, update the result container and disable the button
                resultContainer.innerHTML = '<p>No data found for the selected filters.</p>';
                document.getElementById('download-btn').disabled = true;
              }
            } else {
              // Handle errors in the request
              resultContainer.innerHTML = '<p>Error loading data. Please try again later.</p>';
              document.getElementById('download-btn').disabled = true;
            }
          };

          xhr.onerror = function() {
            // Handle network errors
            resultContainer.innerHTML = '<p>Network error. Please check your connection and try again.</p>';
            document.getElementById('generate-soa-btn').disabled = false;
            document.getElementById('download-btn').disabled = true;
          };

          console.log(data);

          // Send the data to the server
          xhr.send(data);
        });
    </script> -->

  <!-- Working Merge Generate SOA -->
  <!-- <script>
      document.getElementById('download-btn').addEventListener('click', function() {
        const companyId = document.getElementById('company-filter').value;
        const monthFilter = document.getElementById('month-filter');
        const yearFilter = document.getElementById('year-filter');
        const flightFilter = document.getElementById('flight-filter');
        const selectedText = flightFilter.options[flightFilter.selectedIndex].text;
        console.log(selectedText);
        // console.log()

        // Get current date in mm/dd/yyyy format
        const currentDate = new Date();
        const currentDateFormatted = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '/' +
          currentDate.getDate().toString().padStart(2, '0') + '/' +
          currentDate.getFullYear();

        let urlAddSoA = '';
        let urlGenerateSoA = '';
        let data = `companyId=${companyId}&currentDate=${currentDateFormatted}`;

        // Determine the request type based on available filters
        if (monthFilter.value !== "Select month" && yearFilter.value !== "Select year") {

          // Use Month & Year (Orig Generate SoA)
          urlAddSoA = '../Employee Section/functions/emp-addSoA.php';
          urlGenerateSoA = '../Employee Section/functions/generateSoA.php';
          data += `&month=${monthFilter.value}&year=${yearFilter.value}`;

        } 
        
        else if (flightFilter.value !== "Select Flight Date") {
          console.log(data);
          // Use Flight ID (Flight Date Generate SoA)
          urlAddSoA = '../Employee Section/functions/emp-addSoAByFlightDate.php';
          urlGenerateSoA = '../Employee Section/functions/generateSoAByFlightDate.php';
          data += `&flightId=${flightFilter.value}&flightDate=${selectedText}`;
          console.log(data);
        } 
        
        else {
          // Handle case where no valid filters are selected
          alert('Please select valid filters before generating the SOA.');
          return;
        }


        // First, send the request to insert SOA data and get the generated SOA number
        const xhrAddSoA = new XMLHttpRequest();
        xhrAddSoA.open('POST', urlAddSoA, true);
        xhrAddSoA.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhrAddSoA.responseType = 'json'; // Expect JSON response for the SOA number

        xhrAddSoA.onload = function() {
          if (xhrAddSoA.status === 200) {
            const response = xhrAddSoA.response;
            console.log(response);

            if (response.soanum) {
              const soaNumber = response.soanum; // Get the generated SOA number
              console.log(soaNumber);

              // Proceed to generate the SOA PDF
              const xhrPdf = new XMLHttpRequest();
              xhrPdf.open('POST', urlGenerateSoA, true);
              xhrPdf.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
              xhrPdf.responseType = 'blob';

              xhrPdf.onload = function() {
                if (xhrPdf.status === 200) {
                  // Create a link to download the PDF
                  const blob = new Blob([xhrPdf.response], {
                    type: 'application/pdf'
                  });
                  const link = document.createElement('a');
                  link.href = window.URL.createObjectURL(blob);
                  link.download = `Statement_of_Account_${soaNumber}.pdf`;
                  link.click();
                } else {
                  alert('Failed to generate the SOA PDF. Please try again.');
                }
              };

              xhrPdf.onerror = function() {
                alert('An error occurred while generating the SOA PDF.');
              };

              const finalData = data + `&soaNumber=${soaNumber}`;
              xhrPdf.send(finalData);
              console.log(finalData);
              
            } else {
              alert('Failed to generate SOA Number. Please try again.');
            }
          } else {
            alert('Failed to insert SOA number. Server error: ' + xhrAddSoA.statusText);
          }
        };

        xhrAddSoA.onerror = function() {
          alert('An error occurred while processing the request to insert SOA data.');
        };

        // Send the request with the necessary values for SOA number
        xhrAddSoA.send(data);
      });
    </script> -->


</body>

</html>