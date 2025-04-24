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

<div class="body-container">
  <?php include "../Agent Section/includes/sidebar.php"; ?>

  <div class="main-content-container">
    <div class="navbar">
      <h5 class="title-page" id="page-title">Dashboard</h5>
    </div>

    <div class="main-content">
      <div class="content-wrapper">
        <div class="content-body">
          <div class="table-actions">
            <div class="row">

              <!-- Agent/Client Filter -->
              <div class="col-md-4 mb-3">
                <div class="table-filters-container" id="name-container">
                  <label for="name-filter">Agent/Client Name:</label>
                  <select id="name-filter" name="name-filter" class="form-control">
                    <option selected disabled>Select Agent/Client</option>
                    <!-- PHP options here -->
                  </select>
                </div>
              </div>

              <!-- Month Filter -->
              <div class="col-md-2 mb-3">
                <div class="table-filters-container" id="month-container">
                  <label for="month-filter">Month</label>
                  <select id="month-filter" name="month-filter" class="form-control">
                    <option selected disabled>Select month</option>
                    <!-- Month options -->
                  </select>
                </div>
              </div>

              <!-- Year Filter -->
              <div class="col-md-2 mb-3">
                <div class="table-filters-container" id="year-container">
                  <label for="year-filter">Year</label>
                  <select id="year-filter" name="year-filter" class="form-control">
                    <!-- JS will populate years -->
                  </select>
                </div>
              </div>

              <!-- Flight Date Filter -->
              <div class="col-md-4 mb-3">
                <div class="table-filters-container" id="flight-container">
                  <label for="flight-filter">Select Flight Date:</label>
                  <select id="flight-filter" name="flight-filter" onchange="toggleFilters()" class="form-control">
                    <option selected disabled>Select Flight Date</option>
                    <!-- PHP options here -->
                  </select>
                </div>
              </div>

            </div>

            <!-- Buttons -->
            <div class="row">
              <div class="col-12 d-flex justify-content-end">
                <div class="me-2">
                  <button id="generate-soa-btn" class="btn btn-primary">Preview SOA</button>
                </div>
                <div>
                  <button class="btn btn-primary" id="download-btn" disabled>Generate SoA</button>
                </div>
              </div>
            </div>

            <!-- Results -->
            <div id="result-container" class="mt-3"></div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php require "../Agent Section/includes/scripts.php"; ?>

<!-- JavaScript for Date Filters -->
<script>
  const currentDate = new Date();
  const currentMonthIndex = currentDate.getMonth(); // 0-based
  const currentYear = currentDate.getFullYear();

  // Month select
  const monthSelect = document.getElementById('month-filter');
  const monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];
  monthSelect.value = monthNames[currentMonthIndex];

  // Year select
  const yearSelect = document.getElementById('year-filter');
  for (let y = currentYear - 5; y <= currentYear + 5; y++) {
    const option = document.createElement('option');
    option.value = y;
    option.textContent = y;
    yearSelect.appendChild(option);
  }
  yearSelect.value = currentYear;
</script>

<!-- Preview SoA -->
<script>
  document.getElementById('generate-soa-btn').addEventListener('click', function() {
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
    xhr.open('POST', '../Agent Section/functions/fetchSoA.php', true); // Replace with your PHP file name
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    const data = `companyId=${companyId}&month=${month}&year=${year}`;

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

    // Send the data to the server
    xhr.send(data);
  });
</script>

<!-- Generate SoA -->
<script>
  document.getElementById('download-btn').addEventListener('click', function() {
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

    xhrAddSoA.onload = function() {
      if (xhrAddSoA.status === 200) {
        const response = xhrAddSoA.response;

        if (response.soanum) {
          const soaNumber = response.soanum; // Get the generated SOA number

          // Proceed to generate the SOA PDF
          const xhrPdf = new XMLHttpRequest();
          xhrPdf.open('POST', '../Agent Section/functions/generateSoA.php', true);
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

          // Send the request to generate the SOA PDF with the SOA number
          xhrPdf.send(`companyId=${companyId}&month=${month}&year=${year}&currentDate=${currentDateFormatted}&soaNumber=${soaNumber}`);
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
</script>
</body>

</html>