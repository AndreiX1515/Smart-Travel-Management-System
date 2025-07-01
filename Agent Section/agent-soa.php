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

  <?php include "../Agent Section/includes/sidebar.php"; ?>

  <div class="main-container">

    <div class="navbar">
      <div class="page-header-wrapper">

        <!-- <div class="page-header-top">
          <div class="back-btn-wrapper">
            <button class="back-btn" id="redirect-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
          </div>
        </div> -->

        <div class="page-header-content">
          <div class="page-header-text">
            <h5 class="header-title">Transaction</h5>
          </div>
        </div>

      </div>
    </div>

    <div class="main-content">
      <!-- Row for User Type and Filter Mode Radios -->
      <div class="row">
        <!-- User Type Radio Buttons -->
        <div class="col-md-6 mb-3">
          <label>User Type:</label><br>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="user-type" id="user-agent" value="agent" checked>
            <label class="form-check-label" for="user-agent">Agent</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="user-type" id="user-client" value="client">
            <label class="form-check-label" for="user-client">Client</label>
          </div>
        </div>

        <!-- Filter Mode Radio Buttons -->
        <div class="col-md-6 mb-3">
          <label>Filter Mode:</label><br>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="filter-mode" id="mode-flight" value="flight" checked>
            <label class="form-check-label" for="mode-flight">By Flight</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="filter-mode" id="mode-month" value="month">
            <label class="form-check-label" for="mode-month">By Month</label>
          </div>
        </div>
      </div>

      <!-- Row for Select Dropdowns -->
      <div class="row">
        <!-- Left Column: Agent & Company -->
        <div class="col-md-6">
          <div class="row">
            <!-- Agent Select -->
            <div class="col-md-12 mb-3" id="agent-container">
              <label for="agent-filter">Agent Name:</label>
              <select id="agent-filter" name="agent-filter" class="form-control">
                <option disabled selected>Select Agent</option>
                <?php
                $agentQuery = "SELECT accountId, fName, mName, lName FROM agent WHERE agentCode = '$agentCode'";
                $agentResult = $conn->query($agentQuery);

                if ($agentResult->num_rows > 0) {
                  while ($row = $agentResult->fetch_assoc()) {
                    $fullName = $row['fName'] . ' ' . (!empty($row['mName']) ? substr($row['mName'], 0, 1) . '. ' : '') . $row['lName'];
                    echo "<option value=\"{$row['accountId']}\">$fullName</option>";
                  }
                } else {
                  echo "<option disabled>No Agent available</option>";
                }
                ?>
              </select>
            </div>

            <!-- Travel Agency Select -->
            <div class="col-md-12 mb-3" id="company-container" style="display:none;">
              <label for="company-filter">Travel Agency:</label>
              <select id="company-filter" name="company-filter" class="form-control">
                <option disabled selected>Select Travel Agency</option>
                <?php
                $companyQuery = "SELECT companyId, companyName FROM company WHERE branchId = $branchId";
                $companyResult = $conn->query($companyQuery);

                if ($companyResult->num_rows > 0) {
                  while ($row = $companyResult->fetch_assoc()) {
                    echo "<option value=\"{$row['companyId']}\">{$row['companyName']}</option>";
                  }
                } else {
                  echo "<option disabled>No Travel Agency available</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>

        <!-- Right Column: Flight, Month, Year -->
        <div class="col-md-6">
          <div class="row">
            <!-- Flight Date Select -->
            <div class="col-md-12 mb-3 filter-flight" id="flight-container">
              <label for="flight-filter">Select Flight Date:</label>
              <select id="flight-filter" name="flight-filter" class="form-control">
                <option value="Select Flight Date" selected disabled>Select Flight Date</option>
                <?php
                $sql1 = "SELECT DISTINCT flightDepartureDate FROM flight ORDER BY flightDepartureDate ASC";
                $result = $conn->query($sql1);

                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    $formattedFlightDate = date("F j, Y", strtotime($row['flightDepartureDate']));
                    echo "<option value='" . $row['flightDepartureDate'] . "'>" . $formattedFlightDate . "</option>";
                  }
                } else {
                  echo "<option value='' disabled>No flights available</option>";
                }
                ?>
              </select>
            </div>

            <!-- Month Select -->
            <div class="col-md-6 mb-3 filter-month" id="month-container" style="display:none;">
              <label for="month-filter">Month</label>
              <select id="month-filter" name="month-filter" class="form-control">
                <option value="" selected disabled>Select month</option>
              </select>
            </div>

            <!-- Year Select -->
            <div class="col-md-6 mb-3 filter-month" id="year-container" style="display:none;">
              <label for="year-filter">Year</label>
              <select id="year-filter" name="year-filter" class="form-control">
                <option value="" selected disabled>Select year</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Buttons -->
      <div class="row">
        <div class="col-12 d-flex justify-content-end">
          <div class="me-2">
            <button id="generate-soa-btn" class="btn btn-primary">Preview SOA</button>
          </div>
          <!-- <div>
            <button class="btn btn-primary" id="download-btn" disabled>Generate SoA</button>
          </div> -->
        </div>
      </div>

      <div id="soaWrapper" class="container-fluid p-4 border rounded bg-white shadow-sm mt-4" style="display: none; overflow-x: auto;">

        <!-- <table id="soaTable" class="product-table w-100">
          <thead>
            <tr>
              <th>No.</th>
              <th>Contents</th>
              <th>$ Price</th>
              <th>₱ Price</th>
              <th>PAX</th>
              <th>$ Total</th>
              <th>₱ Total</th>
            </tr>
          </thead>

          <tbody id="soaFlightsBody">
            <!-- Flight rows will be inserted here
          </tbody>
          <tbody>
            <tr id="soaFlights" class="table-subtotal bg-light fw-bold">
              <td class="text-end">Subtotal:</td>
              <td><span class="subtotal-usd"></span></td>
              <td><span class="subtotal-php"></span></td>
            </tr>
          </tbody>

          <tbody id="soaRequestsBody">
            <!-- Request rows will be inserted here
          </tbody>
          <tbody>
            <tr id="soaRequests" class="table-subtotal bg-light fw-bold">
              <td class="text-end">Subtotal:</td>
              <td><span class="subtotal-usd"></span></td>
              <td><span class="subtotal-php"></span></td>
            </tr>
          </tbody>

          <tbody id="soaPaymentsBody">
            <!-- Payment rows will be inserted here
          </tbody>
          <tbody>
            <tr id="soaPayments" class="table-subtotal bg-light fw-bold">
              <td class="text-end">Subtotal:</td>
              <td><span class="subtotal-usd"></span></td>
              <td><span class="subtotal-php"></span></td>
            </tr>
          </tbody>

          <tfoot>
            <tr id="soaBalance" class="bg-secondary text-white fw-bold">
              <td class="text-end">BALANCE:</td>
              <td><span id="balanceUSD"></span></td>
              <td><span id="balancePHP"></span></td>
            </tr>
          </tfoot>
        </table> -->

        <table id="soaTable" class="product-table w-100">
          <thead>
            <tr>
              <th>No.</th>
              <th>Contents</th>
              <th>$ Price</th>
              <th>₱ Price</th>
              <th>PAX</th>
              <th>$ Total</th>
              <th>₱ Total</th>
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


  <?php require "../Agent Section/includes/scripts.php"; ?>
  <!-- jQuery (if not already included) -->
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

  <!-- SheetJS XLSX library -->
  <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

  <!-- JavaScript for Date Filters -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const currentDate = new Date();
      const currentMonthIndex = currentDate.getMonth(); // 0-based: Jan = 0
      const currentYear = currentDate.getFullYear();

      const monthSelect = document.getElementById('month-filter');
      const yearSelect = document.getElementById('year-filter');

      const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
      ];

      // Populate Month Options
      monthNames.forEach((month, index) => {
        const option = document.createElement("option");
        option.value = index + 1;
        option.textContent = month;
        monthSelect.appendChild(option);
      });

      // Populate Year Options (range: currentYear - 5 to currentYear + 5)
      for (let y = currentYear - 5; y <= currentYear + 5; y++) {
        const option = document.createElement("option");
        option.value = y;
        option.textContent = y;
        yearSelect.appendChild(option);
      }
    });
  </script>

  <!-- JS for user and mode filter -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const userTypeRadios = document.getElementsByName("user-type");
      const agentContainer = document.getElementById("agent-container");
      const companyContainer = document.getElementById("company-container");

      const flightFilters = document.querySelectorAll(".filter-flight");
      const monthFilters = document.querySelectorAll(".filter-month");

      // Get select elements
      const agentFilter = document.getElementById("agent-filter");
      const companyFilter = document.getElementById("company-filter");
      const flightFilter = document.getElementById("flight-filter");
      const monthFilter = document.getElementById("month-filter");
      const yearFilter = document.getElementById("year-filter");

      function toggleUserType() {
        const selectedType = document.querySelector('input[name="user-type"]:checked').value;

        // Reset dropdowns
        agentFilter.selectedIndex = 0;
        companyFilter.selectedIndex = 0;
        flightFilter.selectedIndex = 0;
        monthFilter.selectedIndex = 0;
        yearFilter.selectedIndex = 0;

        if (selectedType === "agent") {
          console.log(selectedType);
          agentContainer.style.display = "block";
          companyContainer.style.display = "none";
        }
        else {
          console.log(selectedType);
          agentContainer.style.display = "none";
          companyContainer.style.display = "block";
        }
      }

      function toggleFilterMode() {
        const selectedMode = document.querySelector('input[name="filter-mode"]:checked').value;

        // Reset dropdowns
        flightFilter.selectedIndex = 0;
        monthFilter.selectedIndex = 0;
        yearFilter.selectedIndex = 0;

        if (selectedMode === "flight") {
          console.log(selectedMode);
          flightFilters.forEach(el => el.style.display = "block");
          monthFilters.forEach(el => el.style.display = "none");
        }
        else {
          console.log(selectedMode);
          flightFilters.forEach(el => el.style.display = "none");
          monthFilters.forEach(el => el.style.display = "block");
        }
      }

      // Event bindings
      userTypeRadios.forEach(radio => radio.addEventListener('change', toggleUserType));
      document.getElementsByName("filter-mode").forEach(radio => radio.addEventListener('change', toggleFilterMode));

      // Initial state
      toggleUserType();
      toggleFilterMode();
    });

    document.getElementById('month-filter').addEventListener('change', function () {
      console.log('Selected month:', this.value);
    });

    // Listen for changes in the year dropdown
    document.getElementById('year-filter').addEventListener('change', function () {
      console.log('Selected year:', this.value);
    });

    document.getElementById('company-filter').addEventListener('change', function () {
      console.log('Company Id:', this.value);
    });

    document.getElementById('agent-filter').addEventListener('change', function () {
      console.log('Account Id:', this.value);
    });
  </script>

  <!-- Preview SoA -->
  <!-- <script>
    let soaPreviewData = null;

    document.getElementById('generate-soa-btn').addEventListener('click', function () {
      const agentSelect = document.getElementById('agent-filter');
      const companySelect = document.getElementById('company-filter');
      const monthFilter = document.getElementById('month-filter');
      const yearFilter = document.getElementById('year-filter');
      const flightFilter = document.getElementById('flight-filter');

      const agentId = agentSelect && agentSelect.selectedIndex > 0 ? agentSelect.value : null;
      const companyId = companySelect && companySelect.selectedIndex > 0 ? companySelect.value : null;
      const flightId = flightFilter && flightFilter.value !== "Select Flight Date" && flightFilter.value !== "" ? flightFilter.value : null;
      const month = monthFilter && monthFilter.value !== "" ? monthFilter.value : null;
      const year = yearFilter && yearFilter.value !== "" ? yearFilter.value : null;

      let url = '';
      let params = new URLSearchParams();

      if (agentId && !companyId) {
        params.append('agentId', agentId);
        if (month && year) {
          url = '../Agent Section/functions/fetchSoaAgent.php';
          params.append('month', month);
          params.append('year', year);
        } else if (flightId) {
          url = '../Agent Section/functions/fetchSoAByFlightDateAgent.php';
          params.append('flightId', flightId);
        } else {
          alert("Please select a flight or month/year for the agent.");
          return;
        }
      } else if (companyId && !agentId) {
        params.append('companyId', companyId);
        if (month && year) {
          url = '../Agent Section/functions/fetchSoA.php';
          params.append('month', month);
          params.append('year', year);
        } else if (flightId) {
          url = '../Agent Section/functions/fetchSoAByFlightDate.php';
          params.append('flightId', flightId);
        } else {
          alert("Please select a flight or month/year for the company.");
          return;
        }
      } else {
        alert("Please select either an Agent or a Company (not both).");
        return;
      }

      const generateBtn = document.getElementById('generate-soa-btn');
      generateBtn.disabled = true;

      const soaWrapper = document.getElementById('soaWrapper');
      document.getElementById('soaFlightsBody').innerHTML = '<tr><td colspan="7">Loading Flights...</td></tr>';
      document.getElementById('soaRequestsBody').innerHTML = '<tr><td colspan="7">Loading Requests...</td></tr>';
      document.getElementById('soaPaymentsBody').innerHTML = '<tr><td colspan="7">Loading Payments...</td></tr>';
      soaWrapper.style.display = 'block';

      $.ajax({
        url: url,
        method: 'POST',
        data: Object.fromEntries(params),
        dataType: 'json',
        success: function (data) {
          generateBtn.disabled = false;

          if (data && typeof data === 'object' && data.dataAvailable) {
            soaPreviewData = data;

            // Flights
            $('#soaFlightsBody').html(data.flights.rows);
            $('#soaFlights .subtotal-php').text(data.flights.subtotalPHP ? '₱ ' + data.flights.subtotalPHP : '₱ 0.00');
            $('#soaFlights .subtotal-usd').text(data.flights.subtotalUSD ? '$ ' + data.flights.subtotalUSD : '$ 0.00');

            // Requests
            $('#soaRequestsBody').html(data.requests.rows);
            $('#soaRequests .subtotal-php').text(data.requests.subtotalPHP ? '₱ ' + data.requests.subtotalPHP : '₱ 0.00');
            $('#soaRequests .subtotal-usd').text(data.requests.subtotalUSD ? '$ ' + data.requests.subtotalUSD : '$ 0.00');

            // Payments
            $('#soaPaymentsBody').html(data.payments.rows);
            $('#soaPayments .subtotal-php').text(data.payments.subtotalPHP ? '₱ ' + data.payments.subtotalPHP : '₱ 0.00');
            $('#soaPayments .subtotal-usd').text(data.payments.subtotalUSD ? '$ ' + data.payments.subtotalUSD : '$ 0.00');

            // Balance
            $('#balancePHP').text(data.balance.php ? '₱ ' + data.balance.php : '₱ 0.00');
            $('#balanceUSD').text(data.balance.usd ? '$ ' + data.balance.usd : '$ 0.00');

            const downloadBtn = document.getElementById('download-btn');
            if (downloadBtn) downloadBtn.disabled = false;
          } else {
            displayNoData();
          }
        },
        error: function (xhr, status, error) {
          generateBtn.disabled = false;
          displayError("Error: " + error);
          console.error("AJAX error:", status, error);
        }
      });

      function displayNoData() {
        document.getElementById('soaFlightsBody').innerHTML = '<tr><td colspan="7">No data found for the selected filters.</td></tr>';
        document.getElementById('soaRequestsBody').innerHTML = '';
        document.getElementById('soaPaymentsBody').innerHTML = '';
        document.getElementById('balancePHP').innerText = '₱ 0.00';
        document.getElementById('balanceUSD').innerText = '$ 0.00';
        document.getElementById('download-btn').disabled = true;
      }

      function displayError(message) {
        document.getElementById('soaFlightsBody').innerHTML = `<tr><td colspan="7">${message}</td></tr>`;
        document.getElementById('soaRequestsBody').innerHTML = '';
        document.getElementById('soaPaymentsBody').innerHTML = '';
        document.getElementById('balancePHP').innerText = '₱ 0.00';
        document.getElementById('balanceUSD').innerText = '$ 0.00';
        document.getElementById('download-btn').disabled = true;
      }
    });
  </script> -->

  <!-- Preview SOA New -->
  <script>
    let soaPreviewData = null; // Global to store preview data for export

    document.getElementById('generate-soa-btn').addEventListener('click', function () {
      const agentSelect = document.getElementById('agent-filter');
      const companySelect = document.getElementById('company-filter');
      const monthFilter = document.getElementById('month-filter');
      const yearFilter = document.getElementById('year-filter');
      const flightFilter = document.getElementById('flight-filter');

      const agentId = agentSelect && agentSelect.selectedIndex > 0 ? agentSelect.value : null;
      const companyId = companySelect && companySelect.selectedIndex > 0 ? companySelect.value : null;
      const flightId = flightFilter && flightFilter.value !== "Select Flight Date" && flightFilter.value !== "" ? flightFilter.value : null;
      const month = monthFilter && monthFilter.value !== "" ? monthFilter.value : null;
      const year = yearFilter && yearFilter.value !== "" ? yearFilter.value : null;

      let url = '';
      let params = new URLSearchParams();

      if (agentId && !companyId) {
        params.append('agentId', agentId);
        if (month && year) {
          url = '../Agent Section/functions/fetchSoaAgent.php';
          params.append('month', month);
          params.append('year', year);
        } else if (flightId) {
          url = '../Agent Section/functions/fetchSoAByFlightDateAgent.php';
          params.append('flightId', flightId);
        } else {
          alert("Please select a flight or month/year for the agent.");
          return;
        }
      } else if (companyId && !agentId) {
        params.append('companyId', companyId);
        if (month && year) {
          url = '../Agent Section/functions/fetchSoA.php';
          params.append('month', month);
          params.append('year', year);
        } else if (flightId) {
          url = '../Agent Section/functions/fetchSoAByFlightDate.php';
          params.append('flightId', flightId);
        } else {
          alert("Please select a flight or month/year for the company.");
          return;
        }
      } else {
        alert("Please select either an Agent or a Company (not both).");
        return;
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
            // ✅ Combine and insert flight + request rows
            $('#soaFlightsBody').html(data.flights.rows + data.requests.rows);

            const flightRowCount = (data.flights.rows.match(/<tr>/g) || []).length;
            const requestRowCount = (data.requests.rows.match(/<tr>/g) || []).length;
            let rowCounter = flightRowCount + requestRowCount + 1;

            // ✅ Calculate combined subtotal
            const flightSubtotal = parseFloat((data.flights.subtotalPHP || '0').replace(/,/g, ''));
            const requestSubtotal = parseFloat((data.requests.subtotalPHP || '0').replace(/,/g, ''));
            const combinedSubtotal = (flightSubtotal + requestSubtotal).toLocaleString('en-US', { minimumFractionDigits: 2 });

            // ✅ Append combined subtotal
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

            // ✅ Insert payment rows with styles
            const paymentRows = $(data.payments.rows);
            let paymentHtml = '';

            paymentRows.each(function () {
              const cells = $(this).find('td');
              if (cells.length === 7) {
                cells.eq(0).html(rowCounter++); // Update row number

                // Style the ₱ amount cell (last column)
                const amountCell = cells.eq(6);
                const amountText = amountCell.text().trim();
                if (amountText.startsWith('₱')) {
                  amountCell.html(`<span style="color:red;">-${amountText}</span>`);
                }

                paymentHtml += `<tr>${cells.map((i, td) => td.outerHTML).get().join('')}</tr>`;
              }
            });

            $('#soaPaymentsBody').html(paymentHtml);

            // ✅ Append payment subtotal
            if (data.payments.subtotalPHP || data.payments.subtotalUSD) {
              $('#soaPaymentsBody').append(`
                <tr class="subtotal-row" style="font-weight: bold;">
                  <td>${rowCounter++}</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td colspan="2" style="background-color:rgb(253, 229, 13);">Sub Total</td>
                  <td style="background-color:rgb(253, 229, 13); color: red;">-₱ ${data.payments.subtotalPHP}</td>
                </tr>
              `);
            }

            // ✅ Balance update
            const phpBalance = data.balance?.php ?? '0.00';
            const usdBalance = data.balance?.usd ?? '0.00';
            $('#balancePHP').text('-₱ ' + phpBalance);
            $('#balanceUSD').text('$ ' + usdBalance);

            // ✅ Enable download
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

  <!-- Generate SOA (Excel) -->
  <script>
    document.getElementById('download-btn').addEventListener('click', function () {
      if (!soaPreviewData) {
        alert("No preview data available for export.");
        return;
      }

      const agentSelect = document.getElementById('agent-filter');
      const companySelect = document.getElementById('company-filter');

      const agentId = document.getElementById('agent-filter').value;
      const companyId = document.getElementById('company-filter').value;
      const flightDate = document.getElementById('flight-filter').value;
      const month = document.getElementById('month-filter').value;
      const year = document.getElementById('year-filter').value;

      const isAgentSelected = agentId && agentId !== "Select Agent";
      const isCompanySelected = companyId && companyId !== "Select Travel Agency";

      const accountType = isAgentSelected ? "agent" : "company";
      const accountId = isAgentSelected ? agentId : companyId;

      const fromName = <?php echo json_encode($fullName); ?>;
      const fromCompany = <?php echo json_encode($companyName); ?>;

      const accountName = isAgentSelected
        ? agentSelect.options[agentSelect.selectedIndex].text
        : companySelect.options[companySelect.selectedIndex].text;

      const currentDate = new Date();
      const currentDateFormatted = `${currentDate.getFullYear()}-${(currentDate.getMonth() + 1)
        .toString().padStart(2, '0')}-${currentDate.getDate().toString().padStart(2, '0')}`;

      // STEP 1: Generate SOA Number first
      const xhrAddSoA = new XMLHttpRequest();
      xhrAddSoA.open('POST', '../Agent Section/functions/agent-addSoA.php', true);
      xhrAddSoA.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhrAddSoA.responseType = 'json';

      xhrAddSoA.onload = function () {
        if (xhrAddSoA.status === 200 && xhrAddSoA.response?.soanum) {
          const soaNumber = xhrAddSoA.response.soanum;

          // STEP 2: Send the preview JSON to PHPSpreadsheet backend
          fetch('../Agent Section/functions/generateSOAFlightDateAgent.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              soaNumber: soaNumber,
              data: soaPreviewData, // ✅ send stored preview data
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

  <!-- Generate SOA (pdf) -->
  <!-- <script>
    document.getElementById('download-btn').addEventListener('click', function () {
      const companyId = document.getElementById('company-filter').value;
      const month = document.getElementById('month-filter').value;
      const year = document.getElementById('year-filter').value;

      const currentDate = new Date();
      const currentDateFormatted = `${currentDate.getFullYear()}-${(currentDate.getMonth() + 1)
        .toString()
        .padStart(2, '0')}-${currentDate.getDate().toString().padStart(2, '0')}`;

      // Step 1: Send the request to insert SOA data and get SOA number
      const xhrAddSoA = new XMLHttpRequest();
      xhrAddSoA.open('POST', '../Agent Section/functions/agent-addSoA.php', true);
      xhrAddSoA.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhrAddSoA.responseType = 'json';

      xhrAddSoA.onload = function () {
        if (xhrAddSoA.status === 200 && xhrAddSoA.response.soanum) {
          const soaNumber = xhrAddSoA.response.soanum;

          // Step 2: Fetch data for Excel
          const xhrExcel = new XMLHttpRequest();
          xhrExcel.open('POST', '../Agent Section/functions/generateSoA.php', true);
          xhrExcel.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhrExcel.responseType = 'json';

          xhrExcel.onload = function () {
            if (xhrExcel.status === 200 && Array.isArray(xhrExcel.response)) {
              const soaData = xhrExcel.response;

              // Step 3: Generate Excel
              const ws = XLSX.utils.json_to_sheet(soaData);
              const wb = XLSX.utils.book_new();
              XLSX.utils.book_append_sheet(wb, ws, "SOA");

              XLSX.writeFile(wb, `Statement_of_Account_${soaNumber}.xlsx`);
            } else {
              alert("Failed to fetch SOA data for Excel export.");
            }
          };

          xhrExcel.onerror = function () {
            alert("An error occurred while fetching SOA data for Excel.");
          };

          xhrExcel.send(
            `companyId=${companyId}&month=${month}&year=${year}&currentDate=${currentDateFormatted}&soaNumber=${soaNumber}`
          );
        } else {
          alert("Failed to generate SOA number.");
        }
      };

      xhrAddSoA.onerror = function () {
        alert("An error occurred while inserting SOA data.");
      };

      xhrAddSoA.send(
        `companyId=${companyId}&month=${month}&year=${year}&currentDate=${currentDateFormatted}`
      );
    });
  </script> -->

  <!-- <script>
  //   document.getElementById('download-btn').addEventListener('click', function() {
  //     const companyId = document.getElementById('company-filter').value;
  //     const month = document.getElementById('month-filter').value;
  //     const year = document.getElementById('year-filter').value;

  //     // Get current date in mm/dd/yyyy format
  //     const currentDate = new Date();
  //     const currentDateFormatted = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '/' +
  //       currentDate.getDate().toString().padStart(2, '0') + '/' +
  //       currentDate.getFullYear();

  //     // First, send the request to agent-addSoA.php to insert SOA data
  //     const xhrAddSoA = new XMLHttpRequest();
  //     xhrAddSoA.open('POST', '../Agent Section/functions/agent-addSoA.php', true);
  //     xhrAddSoA.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  //     xhrAddSoA.responseType = 'json'; // Expect JSON response for the SOA number

  //           if (response.soanum) {
  //             const soaNumber = response.soanum; // Get the generated SOA number

  //             // Proceed to generate the SOA PDF
  //             const xhrPdf = new XMLHttpRequest();
  //             xhrPdf.open('POST', '../Agent Section/functions/generateSoA.php', true);
  //             xhrPdf.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  //             xhrPdf.responseType = 'blob';

  //             xhrPdf.onload = function () {
  //               if (xhrPdf.status === 200) {
  //                 // Create a link to download the PDF
  //                 const blob = new Blob([xhrPdf.response], {
  //                   type: 'application/pdf'
  //                 });
  //                 const link = document.createElement('a');
  //                 link.href = window.URL.createObjectURL(blob);
  //                 link.download = `Statement_of_Account_${soaNumber}.pdf`;
  //                 link.click();
  //               } else {
  //                 alert('Failed to generate the SOA PDF. Please try again.');
  //               }
  //             };

  //             xhrPdf.onerror = function () {
  //               alert('An error occurred while generating the SOA PDF.');
  //             };

  //             // Send the request to generate the SOA PDF with the SOA number
  //             xhrPdf.send(`companyId=${companyId}&month=${month}&year=${year}&currentDate=${currentDateFormatted}&soaNumber=${soaNumber}`);
  //           } else {
  //             alert('Failed to generate SOA Number. Please try again.');
  //           }
  //         } else {
  //           alert('Failed to insert SOA number. Server error: ' + xhrAddSoA.statusText);
  //         }
  //       };

  //       xhrAddSoA.onerror = function () {
  //         alert('An error occurred while processing the request to insert SOA data.');
  //       };

  //     // Send the request with the necessary values for SOA number
  //     xhrAddSoA.send(`companyId=${companyId}&month=${month}&year=${year}&currentDate=${currentDateFormatted}`);
  //   });
  // </script> -->

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

  <!-- <script>
  const table = $('#product-table').DataTable({
    dom: 'rtip',
    columnDefs: [{
      width: '12%',
      targets: 0
    }, // Transact No.
    {
      width: '22%',
      targets: 1
    }, // To/From
    {
      width: '6%',
      targets: 2
    }, // Pax
    {
      width: '15%',
      targets: 3
    }, // Booking Type
    {
      width: '12%',
      targets: 4
    }, // Package Price
    {
      width: '12%',
      targets: 5
    }, // Amount to be Paid
    {
      width: '12%',
      targets: 6
    }, // Amount Paid
    {
      width: '9%',
      targets: 7
    } // Status


    ],
    language: {
      emptyTable: "No Transaction Records Available"
    },
    order: [
      [0, 'desc']
    ],
    scrollX: false,
    autoWidth: false,
    pageLength: 10, // Limit the number of rows per page to 8
  });
  </script> -->


</body>

</html>