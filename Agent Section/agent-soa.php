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

          <tbody id="soaFlightsBody">
            <!-- Flight rows will be inserted here -->
          </tbody>
          <tbody>
            <tr id="soaFlights" class="table-subtotal bg-light fw-bold">
              <td colspan="5" class="text-end">Subtotal:</td>
              <td><span class="subtotal-usd"></span></td>
              <td><span class="subtotal-php"></span></td>
            </tr>
          </tbody>

          <tbody id="soaRequestsBody">
            <!-- Request rows will be inserted here -->
          </tbody>
          <tbody>
            <tr id="soaRequests" class="table-subtotal bg-light fw-bold">
              <td colspan="5" class="text-end">Subtotal:</td>
              <td><span class="subtotal-usd"></span></td>
              <td><span class="subtotal-php"></span></td>
            </tr>
          </tbody>

          <tbody id="soaPaymentsBody">
            <!-- Payment rows will be inserted here -->
          </tbody>
          <tbody>
            <tr id="soaPayments" class="table-subtotal bg-light fw-bold">
              <td colspan="5" class="text-end">Subtotal:</td>
              <td><span class="subtotal-usd"></span></td>
              <td><span class="subtotal-php"></span></td>
            </tr>
          </tbody>

          <tfoot>
            <tr id="soaBalance" class="bg-secondary text-white fw-bold">
              <td colspan="5" class="text-end">BALANCE:</td>
              <td><span id="balanceUSD"></span></td>
              <td><span id="balancePHP"></span></td>
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
  <script>
    document.getElementById('generate-soa-btn').addEventListener('click', function () 
    {
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
        dataType: 'json', // ✅ This tells jQuery to parse JSON response
        success: function(data) {
          generateBtn.disabled = false;

          console.log("Response data:", data); // Debugging line

          if (data.dataAvailable) {
            // Flights Section
            $('#soaFlightsBody').html(data.flights.rows);
            $('#soaFlights .subtotal-php').text('₱ ' + data.flights.subtotalPHP || '0.00');
            $('#soaFlights .subtotal-usd').text(data.flights.subtotalUSD || '0.00');

            // Requests Section
            $('#soaRequestsBody').html(data.requests.rows);
            $('#soaRequests .subtotal-php').text('₱ ' + data.requests.subtotalPHP || '0.00');
            $('#soaRequests .subtotal-usd').text(data.requests.subtotalUSD || '0.00');

            // Payments Section
            $('#soaPaymentsBody').html(data.payments.rows);
            $('#soaPayments .subtotal-php').text('₱ ' + data.payments.subtotalPHP || '0.00');
            $('#soaPayments .subtotal-usd').text(data.payments.subtotalUSD || '0.00');

            // Balance Section
            $('#balancePHP').text('₱ ' + data.balance.php || '0.00');
            $('#balanceUSD').text('$ ' + data.balance.usd || '0.00');

            // Enable download button
            const downloadBtn = document.getElementById('download-btn');
            if (downloadBtn) {
              downloadBtn.disabled = false;
              // downloadBtn.onclick = () => exportSOAToExcel(data.soaNumber); // if provided
            }

            // console.log("Data fetched successfully:", data);
          } else {
            displayNoData(); // Handle no data state
          }
        },
        error: function(xhr, status, error) {
          generateBtn.disabled = false;
          displayError("Error: " + error);
          console.error("AJAX error:", status, error);
        }
      });


      function displayNoData() {
        document.getElementById('soaFlightsBody').innerHTML =
          '<tr><td colspan="7">No data found for the selected filters.</td></tr>';
        document.getElementById('soaRequestsBody').innerHTML = '';
        document.getElementById('soaPaymentsBody').innerHTML = '';
        document.getElementById('balancePHP').innerText = '₱ 0.00';
        document.getElementById('balanceUSD').innerText = '0.00';
        document.getElementById('download-btn').disabled = true;
      }

      function displayError(message) {
        document.getElementById('soaFlightsBody').innerHTML =
          `<tr><td colspan="7">${message}</td></tr>`;
        document.getElementById('soaRequestsBody').innerHTML = '';
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
    console.log("Download button clicked");
    const agentId = document.getElementById('agent-filter').value;
    const companyId = document.getElementById('company-filter').value;
    const flightDate = document.getElementById('flight-filter').value;
    const month = document.getElementById('month-filter').value;
    const year = document.getElementById('year-filter').value;

    const isAgentSelected = agentId && agentId !== "Select Agent";
    const isCompanySelected = companyId && companyId !== "Select Travel Agency";
    const isFlightSelected = flightDate && flightDate !== "Select Flight Date";
    const isMonthYearSelected = month && month !== "Select month" && year && year !== "Select year";

    // Validate Agent/Company
    if ((isAgentSelected && isCompanySelected) || (!isAgentSelected && !isCompanySelected)) {
      alert("Please select either an Agent OR a Travel Agency.");
      return;
    }

    // Validate Flight OR Month+Year
    if ((isFlightSelected && isMonthYearSelected) || (!isFlightSelected && !isMonthYearSelected)) {
      alert("Please select either a Flight Date OR a Month and Year.");
      return;
    }

    const currentDate = new Date();
    const currentDateFormatted = `${currentDate.getFullYear()}-${(currentDate.getMonth() + 1).toString().padStart(2, '0')}-${currentDate.getDate().toString().padStart(2, '0')}`;

    const accountType = isAgentSelected ? "agent" : "company";
    const accountId = isAgentSelected ? agentId : companyId;

    // Step 1: Request SOA number
    const xhrAddSoA = new XMLHttpRequest();
    xhrAddSoA.open('POST', '../Agent Section/functions/agent-addSoA.php', true);
    xhrAddSoA.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhrAddSoA.responseType = 'json';

    xhrAddSoA.onload = function () {
      if (xhrAddSoA.status === 200 && xhrAddSoA.response && xhrAddSoA.response.soanum) {
        const soaNumber = xhrAddSoA.response.soanum;
        // Step 2: Generate Excel with the SOA number
        exportSOAToExcel(soaNumber);
      } else {
        alert("Failed to generate SOA number.");
      }
    };

    xhrAddSoA.onerror = function () {
      alert("An error occurred while inserting SOA data.");
    };

    xhrAddSoA.send(
      `accountType=${accountType}&accountId=${accountId}&flightDate=${flightDate}&month=${month}&year=${year}&currentDate=${currentDateFormatted}`
    );
  });

  function generateSOAExport() {
    // Get the value of the selected agent or travel agency
    

    // Call the export function with the necessary parameters
    exportSOAToExcel(soaNumber, billTo, soaGeneratedBy, formattedDate);

    console.log("Bill To: ", billTo);
    console.log("Generated By: ", soaGeneratedBy);
    console.log("Date Generated: ", formattedDate);
    console.log("SOA Number:", soaNumber);
  }

  function exportSOAToExcel(soaNumber) {
    const table = document.getElementById("soaTable");
    if (!table) {
      alert("No SOA data available to export.");
      return;
    }

    const agentSelect = document.getElementById('agent-filter');
    const companySelect = document.getElementById('company-filter');

    let billTo = '';
    if (agentSelect && agentSelect.selectedIndex > 0) {
      billTo = agentSelect.options[agentSelect.selectedIndex].text;
    } else if (companySelect && companySelect.selectedIndex > 0) {
      billTo = companySelect.options[companySelect.selectedIndex].text;
    }

    const soaGeneratedBy = <?php echo json_encode($companyName."/ ". $fName ?? ''); ?>;

    const today = new Date();
    const formattedDate = (today.getMonth() + 1).toString().padStart(2, '0') + '/' +
                          today.getDate().toString().padStart(2, '0') + '/' +
                          today.getFullYear();

    const title = [`STATEMENT OF ACCOUNT`];
    const billToLine = [`BILL TO: ${billTo || 'N/A'}`];
    const fromAndDateLine = [`FROM: ${soaGeneratedBy || 'N/A'}     Date: ${formattedDate}`];

    const ws_data = [];

    // Title
    // const title = [`Statement of Account - ${soaNumber}`];
    // Title and header info
    ws_data.push(title);
    ws_data.push([]); // Empty row
    ws_data.push(billToLine);
    ws_data.push(fromAndDateLine);
    ws_data.push([]); // Another empty row before table header

    // Header
    const header = ["No.", "Contents", "$ Price", "₱ Price", "PAX", "$ Total", "₱ Total"];
    ws_data.push(header);

    const rows = table.querySelectorAll("tbody tr, tfoot tr");
    rows.forEach(row => {
      const isSubtotal = row.classList.contains("table-subtotal");
      const isBalance = row.id === "soaBalance";

      if (isSubtotal || isBalance) {
        const label = isBalance ? "BALANCE:" : "Subtotal:";
        let usd = row.querySelector(".subtotal-usd")?.textContent.trim() || row.querySelector("#balanceUSD")?.textContent.trim();
        let php = row.querySelector(".subtotal-php")?.textContent.trim() || row.querySelector("#balancePHP")?.textContent.trim();
        const amount = php || usd || "";

        const mergedRow = [label, "", "", "", "", "", amount];
        ws_data.push(mergedRow);
      } else {
        const cells = Array.from(row.querySelectorAll("td")).map(cell => cell.textContent.trim());
        if (cells.length === 1 && row.cells[0].colSpan === 7) return;
        if (cells.length > 0) ws_data.push(cells);
      }
    });

    // Prepare merges early
    const merges = [];

    // Merge title row A1:G1
    merges.push({ s: { r: 0, c: 0 }, e: { r: 0, c: 6 } });

    // Add account info section
    const accountInfoStartRow = ws_data.length;
    const accountInfo = [
      "ACCOUNT INFORMATION",
      "Bank Name : B D O (Zuellig Branch MAKATI AVENUE)",
      "Name of Account : KIM HYUNG SUB (Nick name  Jedkim )",
      "Peso Account No. 007800151678",
      "US Dollar Account No : 107800113512"
    ];

    accountInfo.forEach((line, i) => {
      const rowIdx = accountInfoStartRow + i;
      ws_data.push([line]);
      merges.push({ s: { r: rowIdx, c: 0 }, e: { r: rowIdx, c: 6 } });
    });

    // Create worksheet
    const ws = XLSX.utils.aoa_to_sheet(ws_data);

    // Styling subtotal and balance rows
    ws_data.forEach((row, rIdx) => {
      const isSubtotal = row[0] === "Subtotal:";
      const isBalance = row[0] === "BALANCE:";

      if (isSubtotal) {
        // Merge PAX (E) to Total USD (F)
        merges.push({ s: { r: rIdx, c: 4 }, e: { r: rIdx, c: 5 } });

        // Write label
        const labelCell = XLSX.utils.encode_cell({ r: rIdx, c: 4 });
        ws[labelCell] = {
          t: "s",
          v: "Subtotal:",
          s: {
            alignment: { horizontal: "right" },
            font: { bold: true },
            border: borderStyle()
          }
        };

        // Amount cell
        const amountCell = XLSX.utils.encode_cell({ r: rIdx, c: 6 });
        if (ws[amountCell]) {
          ws[amountCell].s = {
            alignment: { horizontal: "right" },
            font: { bold: true },
            border: borderStyle()
          };
        }

        // Clear duplicate in col A if needed
        const colA = XLSX.utils.encode_cell({ r: rIdx, c: 0 });
        if (ws[colA]?.v === "Subtotal:") delete ws[colA];
      }

      if (isBalance) {
        // Merge A to E
        merges.push({ s: { r: rIdx, c: 0 }, e: { r: rIdx, c: 4 } });

        const labelCell = XLSX.utils.encode_cell({ r: rIdx, c: 0 });
        ws[labelCell] = {
          t: "s",
          v: "BALANCE:",
          s: {
            alignment: { horizontal: "right" },
            font: { bold: true },
            border: borderStyle()
          }
        };

        const amountCell = XLSX.utils.encode_cell({ r: rIdx, c: 6 });
        if (ws[amountCell]) {
          ws[amountCell].s = {
            alignment: { horizontal: "right" },
            font: { bold: true },
            border: borderStyle()
          };
        }
      }
    });

    // Style account info section
    accountInfo.forEach((line, i) => {
      const rowIdx = accountInfoStartRow + i;
      const cellRef = XLSX.utils.encode_cell({ r: rowIdx, c: 0 });

      if (!ws[cellRef]) ws[cellRef] = { t: "s", v: line };

      ws[cellRef].s = {
        font: { bold: i === 0 },
        alignment: { horizontal: "left" }
      };
    });

    // Apply border to all non-empty cells
    Object.keys(ws).forEach(cell => {
      if (cell[0] === '!') return;
      if (!ws[cell].s) ws[cell].s = {};
      if (!ws[cell].s.border) {
        ws[cell].s.border = borderStyle();
      }
    });

    // Merge and column width
    ws["!merges"] = merges;
    ws["!cols"] = [
      { wch: 5 }, { wch: 30 }, { wch: 15 }, { wch: 15 },
      { wch: 5 }, { wch: 20 }, { wch: 20 }
    ];

    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "SOA");

    XLSX.writeFile(wb, `Statement_of_Account_${soaNumber || 'Export'}.xlsx`, { cellStyles: true });

    function borderStyle() {
      return {
        top: { style: "thin", color: { rgb: "000000" } },
        bottom: { style: "thin", color: { rgb: "000000" } },
        left: { style: "thin", color: { rgb: "000000" } },
        right: { style: "thin", color: { rgb: "000000" } }
      };
    }
  }
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