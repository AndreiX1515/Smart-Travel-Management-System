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

        <div class="page-header-content">
          <div class="page-header-text">
            <h5 class="header-title">Sales Report</h5>
          </div>
        </div>

      </div>
    </div>

    <div class="main-content">
      
      
      <!-- Report Type Filters -->
      <div class="row">

        <!-- Report For Column -->
        <div class="col-md-6 mb-3">
          <label>For:</label><br>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="reportFor" id="agentReport" value="agent" checked>
            <label class="form-check-label" for="agentReport">Agent</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="reportFor" id="clientReport" value="client">
            <label class="form-check-label" for="clientReport">Client</label>
          </div>
        </div>

        <!-- Report Type Column -->
        <div class="col-md-6 mb-3">
          <label>Report Type:</label><br>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="reportType" id="flightReport" value="flight" checked>
            <label class="form-check-label" for="flightReport">Flight</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="reportType" id="monthlyReport" value="monthly">
            <label class="form-check-label" for="monthlyReport">Monthly</label>
          </div>
        </div>

      </div>

      <!-- Dynamic Selectors based on Report Type -->
      <div class="row">

        <div class="col-md-6">
          <div class="row">

            <!-- Agent Selector -->
            <div id="agentSelector" class="col-md-12 mb-3" style="display: none;">
              <label for="agentSelect" class="form-label">Select Agent:</label>
              <select class="form-select" name="selectedAgent" id="agentSelect">
                <option value="all">All Agents</option>
                <?php
                  $agentQuery = "SELECT agentId, fName, mName, lName FROM agent WHERE agentCode = '$agentCode'";
                  $agentResult = $conn->query($agentQuery);

                  if ($agentResult->num_rows > 0) {
                    while ($row = $agentResult->fetch_assoc()) {
                      $fullName = $row['fName'] . ' ' . (!empty($row['mName']) ? substr($row['mName'], 0, 1) . '. ' : '') . $row['lName'];
                      echo "<option value=\"{$row['agentId']}\">$fullName</option>";
                    }
                  } else {
                    echo "<option disabled>No agents available</option>";
                  }
                ?>
              </select>
            </div>

            <!-- Client Selector -->
            <div id="clientSelector" class="col-md-12 mb-3" style="display: none;">
              <label for="clientSelect" class="form-label">Select Client:</label>
              <select class="form-select" name="selectedClient" id="clientSelect">
                <option value="all">All Clients</option>
                <?php
                  $clientQuery = "SELECT clientId, fName, mName, lName FROM client WHERE clientCode = '$agentCode'";
                  $clientResult = $conn->query($clientQuery);

                  while ($row = $clientResult->fetch_assoc()) {
                    $fullName = $row['fName'] . ' ' . (!empty($row['mName']) ? substr($row['mName'], 0, 1) . '. ' : '') . $row['lName'];
                    echo "<option value=\"{$row['clientId']}\">$fullName</option>";
                  }
                ?>
              </select>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="row">
            <!-- Flight Date Selector -->
            <div id="flightSelector" class="col-md-12 mb-3" style="display: none;">
              <label for="flightDate" class="form-label">Select Flight Date:</label>
              <select class="form-select" name="flightDate" id="flightDate">
                <option selected disabled>Select a flight date</option>
                <?php
                $query = "SELECT DISTINCT flightDepartureDate FROM flight ORDER BY flightDepartureDate ASC";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    $date = $row['flightDepartureDate'];
                    $formattedDate = date("M d, Y", strtotime($date));
                    echo "<option value=\"$date\">$formattedDate</option>";
                  }
                } else {
                  echo "<option disabled>No flight dates available</option>";
                }
                ?>
              </select>
            </div>

            <!-- Monthly Selector -->
            <div id="monthlySelector" class="col-md-6 mb-3" style="display: none;">
              <label for="month" class="form-label">Select Month:</label>
              <select class="form-select" name="month" id="month">
                <option selected disabled>Select Month</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
              </select>
            </div>

            <!-- Year Selector -->
            <div id="yearSelector" class="col-md-6 mb-3" style="display: none;">
              <label for="year" class="form-label">Select Year:</label>
              <select class="form-select" name="year" id="year">
                <option selected disabled>Select Year</option>
                <?php
                  $currentYear = date("Y");
                  for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
                    echo "<option value=\"$i\">$i</option>";
                  }
                ?>
              </select>
            </div>
          </div>
        </div>
        
      </div>

      <input name="agentCode" value="<?php echo $agentCode; ?>" hidden>
      <input name="accountId" value="<?php echo $accountId; ?>" hidden>

      <div class="row">
        <div class="col-12 d-flex justify-content-end">
          <div class="me-2">
            <button id="generate-report-btn" class="btn btn-primary">Preview Report</button>
          </div>
          <div class="me-2">
            <button id="reset-filter-btn" class="btn btn-secondary">Reset Filters</button>
          </div>
        </div>
      </div>

      <!-- Table for Displaying Data -->
      <table class="table" id="dataTable" style="display:none;">
        <thead>
          <tr>
            <th>ITEM</th>
            <th>PAX</th>
            <th>AMOUNT</th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
          <tr style="font-weight: bold; border-top: 2px solid #000;">
            <td>Total Flight Sales</td>
            <td></td>
            <td id="totalFlightAmountCell"></td>
          </tr>
          <tr style="font-weight: bold;">
            <td>Total Requests</td>
            <td></td>
            <td id="totalRequestAmountCell"></td>
          </tr>
        </tfoot>
      </table>

      <!-- Button to Generate the Report -->
      <button id="downloadReport" class="btn btn-success" style="display: none;">Download Report</button>

    </div>

  </div>


  <?php require "../Agent Section/includes/scripts.php"; ?>
  <!-- SheetJS XLSX library -->
  <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

  <!-- Filter Mode, and User Type -->
  <script>
    $(document).ready(function() {
      $('#flightDate').select2({
        placeholder: "Select a Flight Date",
        allowClear: true,
        width: '100%' // Makes it match Bootstrap form-control width
      });

      $('#month').select2({
        placeholder: "Select month",
        allowClear: true,
        width: '100%' // Makes it match Bootstrap form-control width
      });

      $('#year').select2({
        placeholder: "Select year",
        allowClear: true,
        width: '100%' // Makes it match Bootstrap form-control width
      });

      $('#year-filter').select2({
        placeholder: "Select year",
        allowClear: true,
        width: '100%' // Makes it match Bootstrap form-control width
      });

      $('#agentSelect').select2({
        placeholder: "Select Agent",
        allowClear: true,
        width: '100%' // Makes it match Bootstrap form-control width
      });

      $('#clientSelect').select2({
        placeholder: "Select Travel Agency",
        allowClear: true,
        width: '100%' // Makes it match Bootstrap form-control width
      });
    });
  </script>

  <!-- Script to Toggle Selectors -->
  <script>
    // Report Type Radio Buttons
    const flightRadio = document.getElementById('flightReport');
    const monthlyRadio = document.getElementById('monthlyReport');

    // Report For Radio Buttons
    const agentReportRadio = document.getElementById('agentReport');
    const clientReportRadio = document.getElementById('clientReport');
    const allReportRadio = document.getElementById('allReport');

    // Selectors
    const flightSelector = document.getElementById('flightSelector');
    const monthlySelector = document.getElementById('monthlySelector');
    const yearSelector = document.getElementById('yearSelector');
    const agentSelector = document.getElementById('agentSelector');
    const clientSelector = document.getElementById('clientSelector');

    // Data Table and Download Report
    const dataTable = document.getElementById('dataTable');
    const downloadReport = document.getElementById('downloadReport');

    // Handle Report Type Change
    flightRadio.addEventListener('change', () => {
      if (flightRadio.checked) {
        flightSelector.style.display = 'block';
        monthlySelector.style.display = 'none';
        yearSelector.style.display = 'none'; // ⬅️ Hide year
        dataTable.style.display = 'none';
        downloadReport.style.display = 'none';
      }
    });

    monthlyRadio.addEventListener('change', () => {
      if (monthlyRadio.checked) {
        monthlySelector.style.display = 'block';
        yearSelector.style.display = 'block'; // ⬅️ Show year
        flightSelector.style.display = 'none';
        dataTable.style.display = 'none';
        downloadReport.style.display = 'none';
      }
    });

    // Handle Report For Change

    agentReportRadio.addEventListener('change', () => {
      if (agentReportRadio.checked) {
        agentSelector.style.display = 'block';
        clientSelector.style.display = 'none';
      }
    });

    clientReportRadio.addEventListener('change', () => {
      if (clientReportRadio.checked) {
        clientSelector.style.display = 'block';
        agentSelector.style.display = 'none';
      }
    });

    // Initial Setup - Trigger change events on page load to set the initial state
    (function initialSetup() {
      if (flightRadio.checked) {
        flightSelector.style.display = 'block';
        monthlySelector.style.display = 'none';
        yearSelector.style.display = 'none';
      }
      else if (monthlyRadio.checked) {
        monthlySelector.style.display = 'block';
        yearSelector.style.display = 'block';
        flightSelector.style.display = 'none';
      }

      if (agentReportRadio.checked) {
        agentSelector.style.display = 'block';
        clientSelector.style.display = 'none';
      }
      else if (clientReportRadio.checked) {
        clientSelector.style.display = 'block';
        agentSelector.style.display = 'none';
      }
      else if (allReportRadio.checked) {
        agentSelector.style.display = 'none';
        clientSelector.style.display = 'none';
      }
    })();
  </script>

  <!-- Reset Filter Script -->
  <script>
    document.getElementById('reset-filter-btn').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('flightDate').selectedIndex = 0;
      document.getElementById('month').selectedIndex = 0;
      document.getElementById('year').selectedIndex = 0;
      document.getElementById('agentSelect').selectedIndex = 0;
      document.getElementById('clientSelect').selectedIndex = 0;
    });
  </script>

  <!-- Ajax Script to backend -->
  <script>
    $(document).ready(function () {
      let reportData = []; // ✅ Global scope
      $('#generate-report-btn').on('click', function (e) {
        e.preventDefault();

        // Get Report Type
        let reportType = '';
        let url = '';
        let reportFor = '';
        let accountId = $('#accountId').val();

        if ($('#flightReport').is(':checked')) reportType = 'flight';
        else if ($('#monthlyReport').is(':checked')) reportType = 'monthly';

        // Get Report For
        if ($('#agentReport').is(':checked')) reportFor = 'agent';
        else if ($('#clientReport').is(':checked')) reportFor = 'client';
        else if ($('#allReport').is(':checked')) reportFor = 'all';

        console.log('Selected Report Type:', reportType);
        console.log('Selected Report For:', reportFor);

        const data = {
          reportType: reportType,
          reportFor: reportFor,
          agentCode: $('#agentCode').val()
        };

        // Append data based on selected report type
        if (reportType === 'flight') {
          data.flightDate = $('#flightDate').val();
        } else if (reportType === 'monthly') {
          data.month = $('#month').val();
          data.year = $('#year').val();
        }

        // Append based on selected target
        if (reportFor === 'agent') {
          data.selectedAgent = $('#agentSelect').val();
        } else if (reportFor === 'client') {
          data.selectedClient = $('#clientSelect').val();
        }

        // Dynamically assign URL based on conditions
        if (reportType === 'flight' && reportFor === 'agent') {
          url = '../Agent Section/functions/fetchSalesReportFlightAgent.php';
        } else if (reportType === 'monthly' && reportFor === 'agent') {
          url = '../Agent Section/functions/fetchSalesReportMonthlyAgent.php';
        } else if (reportType === 'flight' && reportFor === 'client') {
          url = '../Agent Section/functions/fetchSalesReportFlightClient.php';
        } else if (reportType === 'monthly' && reportFor === 'client') {
          url = '../Agent Section/functions/fetchSalesReportMonthlyClient.php';
        } else {
          alert('Invalid report type or report target selected.');
          return;
        }

        console.log('Final Data to Send:', data);
        console.log('Target URL:', url);

        // Send AJAX request using the chosen URL
        $.ajax({
          type: 'POST',
          url: url,
          data: data,
          dataType: 'json',
          success: function (response) {
            console.log('✅ Server Response:', response);

            if (response.data && response.data.length > 0) {
              console.log('✅ Report Data:', response.data);
              renderReportTable(response.data);
              setReportData(response.data, response.totalRequestAmount, response.totalFlightAmount); // Set report data for export

              console.log('📊 Total Request Amount:', response.totalRequestAmount);
              console.log('📊 Total Flight Amount:', response.totalFlightAmount);

              $('#dataTable').show();
              $('#downloadReport').show();
            } else {
              console.warn('⚠️ No data returned or empty result:', response);
              alert(response.error || 'No data found for the selected filters.');
              $('#dataTable').hide();
              $('#downloadReport').hide();
            }
          },
          error: function (xhr, status, error) {
            console.error('❌ AJAX Error:', error);
            console.log('❌ XHR:', xhr);
            console.log('❌ Status:', status);
            alert('An error occurred while generating the report.');
          }
        });
      });

      // 🔹 Render table rows
      function renderReportTable(data) {
        const tbody = $('#dataTable tbody');
        tbody.empty();

        let totalFlightAmount = 0;
        let totalRequestAmount = 0;

        data.forEach(record => {
          const amount = parseFloat(record.amount.replace(/[₱, ]/g, '')) || 0;
          totalFlightAmount += amount;

          const bookingRow = `
            <tr style="font-weight: bold;">
              <td>${record.flightDate}</td>
              <td>${record.pax}</td>
              <td>${record.amount}</td>
            </tr>
          `;
          tbody.append(bookingRow);

          // Sub-rows for requests
          if (record.requests && record.requests.length > 0) {
            record.requests.forEach(request => {
              const reqAmount = parseFloat(request.amount.replace(/[₱, ]/g, '')) || 0;
              totalRequestAmount += reqAmount;

              const requestRow = `
                <tr class="request-row" style="color: #666;">
                  <td style="padding-left: 30px;">↳ ${request.type}</td>
                  <td>${request.pax}</td>
                  <td>${request.amount}</td>
                </tr>
              `;
              tbody.append(requestRow);
            });
          }
        });

        // Update the footer totals
        $('#totalFlightAmountCell').text('₱ ' + totalFlightAmount.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $('#totalRequestAmountCell').text('₱ ' + totalRequestAmount.toLocaleString(undefined, { minimumFractionDigits: 2 }));
      }
    });
  </script>

  <!-- Script Generate to Excel File -->
  <script>
    // This is called after fetching/rendering the report
    function setReportData(data, totalRequestAmount, totalFlightAmount) {
      reportData = data;
      sendTotalRequestAmount = totalRequestAmount;
      sendTotalFlightAmount = totalFlightAmount;

      console.log("[DEBUG] Report data set:", {
        reportData,
        sendTotalRequestAmount,
        sendTotalFlightAmount
      });

      // Show the download button
      document.getElementById('downloadReport').style.display = 'inline-block';
    }

    document.getElementById('downloadReport').addEventListener('click', function () {
      if (!reportData || reportData.length === 0) {
        alert("No report data available for export.");
        console.warn("[DEBUG] Export aborted: reportData is empty or null.");
        return;
      }

      const reportType = document.querySelector('input[name="reportType"]:checked')?.value || "invalid";
      const reportFor = document.querySelector('input[name="reportFor"]:checked')?.value || "invalid";

      const flightDate = document.getElementById("flight-filter")?.value || "";
      const month = document.getElementById("month-filter")?.value || "";
      const year = document.getElementById("year-filter")?.value || "";

      const agentSelect = document.getElementById("agentSelect");
      const clientSelect = document.getElementById("clientSelect");

      let selectedName = "";

      if (reportFor === "agent" && agentSelect.value !== "all") {
        selectedName = agentSelect.options[agentSelect.selectedIndex]?.text;
      } else if (reportFor === "client" && clientSelect.value !== "all") {
        selectedName = clientSelect.options[clientSelect.selectedIndex]?.text;
      }

      console.log("Report Type:", reportType);
      console.log("Report For:", reportFor);
      console.log("Client select value:", clientSelect.value);
      console.log("Client selected index:", clientSelect.selectedIndex);
      console.log("Client selected name:", clientSelect.options[clientSelect.selectedIndex]?.text);


      console.log("Selected Name for Report:", selectedName);

      const payload = {
        reportData: reportData,
        totalRequestAmount: sendTotalRequestAmount,
        totalFlightAmount: sendTotalFlightAmount,
        reportType: reportType,
        reportFor: reportFor,
        flightDate: flightDate,
        month: month,
        year: year,
        selectedName: selectedName // ✅ Pass agent or client name here
      };

      console.log("[DEBUG] Sending report payload to PHP:", payload);

      fetch('../Agent Section/functions/generateSalesReport.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(response => {
        if (!response.ok) {
          console.error("[DEBUG] Response not OK:", response.status, response.statusText);
          throw new Error("Failed to generate report.");
        }
        console.log("[DEBUG] Report successfully generated. Preparing to download.");
        return response.blob();
      })
      .then(blob => {
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = `SalesReport_${reportType}_${reportFor}.xlsx`;
        document.body.appendChild(link);
        link.click();
        link.remove();
        console.log("[DEBUG] Report download initiated.");
      })
      .catch(error => {
        console.error("Error exporting report:", error);
        alert("Something went wrong while exporting the sales report.");
      });
    });
  </script>



</body>

</html>