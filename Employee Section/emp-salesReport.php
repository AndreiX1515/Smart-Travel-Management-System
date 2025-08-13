<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Report</title>
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
            <h5 class="header-title">Sales Report</h5>
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

            <!-- 🔹 Row 1: Report Type Radio Buttons (Right-aligned) -->
            <div class="row mb-2">
              <label class="form-label d-block">Filter By:</label>
              <div class="col-md-6 d-flex justify-content-start align-items-start">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="reportType" id="flightReport" value="flight"
                    checked>
                  <label class="form-check-label" for="flightReport">Flight</label>
                </div>
              </div>

              <div class="col-md-6 d-flex justify-content-start align-items-start">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="reportType" id="monthlyReport" value="monthly">
                  <label class="form-check-label" for="monthlyReport">Monthly</label>
                </div>
              </div>


            </div>

          </div>

          <div class="bottom-part-wrapper">

            <!-- 🔹 Row 2: Branch (Left) + Dynamic Selectors (Right) -->
            <div class="row w-50">

              <!-- Branch Selector -->
              <div class="col-md-4 mb-3">
                <label for="branchSelect" class="form-label">Select Branch:</label>
                <select class="form-select" name="selectedBranch" id="branchSelect">
                  <option selected disabled>Select Branch</option>
                  <?php
                  $branchQuery = "SELECT branchName, branchAgentCode FROM branch ORDER BY branchAgentCode";
                  $branchResult = $conn->query($branchQuery);

                  if ($branchResult->num_rows > 0) {
                    while ($row = $branchResult->fetch_assoc()) {
                      echo "<option value=\"{$row['branchAgentCode']}\">{$row['branchName']}</option>";
                    }
                  } else {
                    echo "<option disabled>No agents available</option>";
                  }
                  ?>
                </select>
              </div>

              <!-- Flight Selector -->
              <div id="flightSelector" class="col-md-4 mb-3" style="display: none;">
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
              <div id="monthlySelector" class="col-md-4 mb-3" style="display: none;">
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
              <div id="yearSelector" class="col-md-4 mb-3" style="display: none;">
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

            <!-- Buttons Row -->
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

            <input name="accountId" id="accountId" value="<?php echo $accountId; ?>" hidden>
          </div>

        </div>

        <!-- Tab Content -->
        <div class="tab-content">

          <div class="table-container">

            <!-- Table and Export Button -->
            <table class="table-clean" id="dataTable" style="display:none;">
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

          </div>

          <div class="table-footer">
            <button id="downloadReport" class="btn btn-success" style="display: none;">Download Report</button>
          </div>


        </div>

      </div>
    </div>


  </div>






  <?php include '../Employee Section/includes/emp-scripts.php' ?>

  <!-- Script to Toggle Report Type Selectors -->
   
  <script>
    // Report Type Radio Buttons
    const flightRadio = document.getElementById('flightReport');
    const monthlyRadio = document.getElementById('monthlyReport');

    // Selectors
    const flightSelector = document.getElementById('flightSelector');
    const monthlySelector = document.getElementById('monthlySelector');
    const yearSelector = document.getElementById('yearSelector');

    // Table and Export Button
    const dataTable = document.getElementById('dataTable');
    const downloadReport = document.getElementById('downloadReport');

    // Handle Report Type Toggle
    flightRadio.addEventListener('change', () => {
      if (flightRadio.checked) {
        flightSelector.style.display = 'block';
        monthlySelector.style.display = 'none';
        yearSelector.style.display = 'none';
        dataTable.style.display = 'none';
        downloadReport.style.display = 'none';
      }
    });

    monthlyRadio.addEventListener('change', () => {
      if (monthlyRadio.checked) {
        flightSelector.style.display = 'none';
        monthlySelector.style.display = 'block';
        yearSelector.style.display = 'block';
        dataTable.style.display = 'none';
        downloadReport.style.display = 'none';
      }
    });

    // Initial Setup on Page Load
    (function initialSetup() {
      if (flightRadio.checked) {
        flightSelector.style.display = 'block';
        monthlySelector.style.display = 'none';
        yearSelector.style.display = 'none';
      } else if (monthlyRadio.checked) {
        flightSelector.style.display = 'none';
        monthlySelector.style.display = 'block';
        yearSelector.style.display = 'block';
      }

      // Hide output elements initially
      dataTable.style.display = 'none';
      downloadReport.style.display = 'none';
    })();
  </script>

  <!-- Reset Filter Script -->
  <script>
    document.getElementById('reset-filter-btn').addEventListener('click', function (e) {
      e.preventDefault();

      // Reset select elements if they exist
      const flightDate = document.getElementById('flightDate');
      const month = document.getElementById('month');
      const year = document.getElementById('year');
      const branchSelect = document.getElementById('branchSelect');

      if (flightDate) flightDate.selectedIndex = 0;
      if (month) month.selectedIndex = 0;
      if (year) year.selectedIndex = 0;
      if (branchSelect) branchSelect.selectedIndex = 0;

      // Optionally reset the view
      dataTable.style.display = 'none';
      downloadReport.style.display = 'none';
    });
  </script>

  <!-- Ajax Script to backend -->
  <script>
    $(document).ready(function () {
      let reportData = [];

      $('#generate-report-btn').on('click', function (e) {
        e.preventDefault();

        // 🔹 Get selected report type
        let reportType = $('input[name="reportType"]:checked').val(); // flight or monthly
        let url = '';
        let branchCode = $('#branchSelect').val(); // ✅ Corrected selector
        let accountId = $('#accountId').val(); // (if needed)

        const data = {
          reportType: reportType,
          branchCode: branchCode,
          accountId: accountId
        };

        console.log('reportType:', reportType);
        console.log('branchCode:', branchCode);
        console.log('accountId:', accountId);

        // 🔹 Add specific filters based on report type
        if (reportType === 'flight') {
          data.flightDate = $('#flightDate').val();
        } else if (reportType === 'monthly') {
          data.month = $('#month').val();
          data.year = $('#year').val();
        }

        // 🔹 Determine URL to call
        if (reportType === 'flight' && branchCode) {
          url = '../Employee Section/functions/fetchSalesReportFlight.php';
        } else if (reportType === 'monthly' && branchCode) {
          url = '../Employee Section/functions/fetchSalesReportMonthly.php';
        } else {
          alert('Please select a valid report type and branch.');
          return;
        }

        console.log('📤 Sending Data:', data);
        console.log('📡 Endpoint:', url);

        // 🔹 Send AJAX request
        $.ajax({
          type: 'POST',
          url: url,
          data: data,
          dataType: 'json',
          success: function (response) {
            console.log('✅ Server Response:', response);

            if (response.data && response.data.length > 0) {
              renderReportTable(response.data);
              setReportData(response.data, response.totalRequestAmount, response.totalFlightAmount); // Set report data for export

              console.log('📊 Total Request Amount:', response.totalRequestAmount);
              console.log('📊 Total Flight Amount:', response.totalFlightAmount);

              $('#dataTable').show();
              $('#downloadReport').show();
            } else {
              alert(response.error || 'No data found for the selected filters.');
              $('#dataTable').hide();
              $('#downloadReport').hide();
            }
          },
          error: function (xhr, status, error) {
            console.error('❌ AJAX Error:', error);
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

    // Set this when data is fetched
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
      const flightDate = document.getElementById("flightDate")?.value || "";
      const month = document.getElementById("month")?.value || "";
      const year = document.getElementById("year")?.value || "";
      const branchSelect = document.getElementById("branchSelect");
      const branchName = branchSelect?.options[branchSelect.selectedIndex]?.text || "";

      const payload = {
        reportData: reportData,
        totalRequestAmount: sendTotalRequestAmount,
        totalFlightAmount: sendTotalFlightAmount,
        reportType: reportType,
        flightDate: flightDate,
        month: month,
        year: year,
        branchName: branchName
      };

      console.log("[DEBUG] Sending report payload:", payload);

      fetch('../Employee Section/functions/generateSalesReport.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
        .then(response => {
          if (!response.ok) {
            throw new Error("Failed to generate report.");
          }
          return response.blob();
        })
        .then(blob => {
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = `SalesReport_${reportType}_${branchName}.xlsx`;
          document.body.appendChild(link);
          link.click();
          link.remove();
        })
        .catch(error => {
          console.error("Error exporting report:", error);
          alert("Something went wrong while exporting the sales report.");
        });
    });
  </script>


</body>
</html>