<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee - Transaction</title>

  <?php include '../Employee Section/includes/emp-head.php' ?>

  <link rel="stylesheet" href="../Employee Section/assets/css/emp-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">

</head>

<body>

  <?php include '../Employee Section/includes/emp-sidebar.php' ?>

  <!-- Main Container -->
  <div class="main-container">
    <nav class="navbar navbar-expand-lg navbar-custom">
      <div class="container-fluid mx-1">
          <a class="navbar-brand" id="page-title" href="#">Transaction</a>
      </div>
    </nav>

    <?php
    $statusTab = isset($_GET['status']) ? $_GET['status'] : '';
    ?>

    <div class="main-content">

      <div class="table-container">

        <div class="table-header">

          <div class="search-wrapper">
            <div class="search-input-wrapper">
              <input type="text" id="search" placeholder="Search here..">
            </div>
          </div>

          <div class="second-header-wrapper">
            <div class="date-range-wrapper flightbooking-wrapper">
              <div class="date-range-inputs-wrapper">
                <div class="input-with-icon">
                  <input type="text" class="datepicker" id="FlightStartDate" placeholder="Flight Date" readonly>
                  <i class="fas fa-calendar-alt calendar-icon"></i>
                </div>
              </div>
            </div>

            <div class="date-range-wrapper sorting-wrapper">
              <div class="select-wrapper">
                <select id="packages">
                  <option value="" disabled selected>Select Branch</option>
                  <?php
                  // Execute the SQL query
                  $sql1 = "SELECT branchId, branchName FROM branch ORDER BY branchName ASC";
                  $res1 = $conn->query($sql1);

                  // Check if there are results
                  if ($res1->num_rows > 0) {
                    // Loop through the results and generate options
                    while ($row = $res1->fetch_assoc()) {
                      echo "<option value='" . $row['branchName'] . "'>" . $row['branchName'] . "</option>";
                    }
                  } else {
                    echo "<option value=''>No companies available</option>";
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="buttons-wrapper">
              <button id="clearSorting" class="btn btn-secondary">
                Clear Filters
              </button>
            </div>
          </div>

        </div>

        <div class="navpills-container">
          <div class="filter-tabs" id="booking-filter-tabs">
            <button class="filter-btn active" data-filter="">
              All
              <span class="badge-status-tab">
                <h6>
                  <?php
                  $sql = "SELECT COUNT(*) AS totalBookings FROM booking;";
                  $result = mysqli_query($conn, $sql);
                  echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                  ?>
                </h6>
              </span>
            </button>

            <button class="filter-btn" data-filter="Pending">Pending
              <span class="badge-status-tab">
                <h6>
                  <?php
                  $sql = "SELECT COUNT(*) AS totalBookings FROM booking 
                  WHERE status = 'Pending'";
                  $result = mysqli_query($conn, $sql);
                  echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                  ?>
                </h6>
              </span>
            </button>

            <button class="filter-btn" data-filter="Reserved">Reserved
              <span class="badge-status-tab">
                <h6>
                  <?php
                  $sql = "SELECT COUNT(*) AS totalBookings FROM booking 
                  WHERE status = 'Reserved'";
                  $result = mysqli_query($conn, $sql);
                  echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                  ?>
                </h6>
              </span>
            </button>

            <button class="filter-btn" data-filter="Confirmed">Confirmed
              <span class="badge-status-tab">
                <h6>
                  <?php
                  $sql = "SELECT COUNT(*) AS totalBookings FROM booking 
                WHERE status = 'Confirmed'";
                  $result = mysqli_query($conn, $sql);
                  echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                  ?>
                </h6>
              </span>
            </button>

            <button class="filter-btn" data-filter="Cancelled">Cancelled
              <span class="badge-status-tab">
                <h6>
                  <?php
                  $sql = "SELECT COUNT(*) AS totalBookings FROM booking 
                  WHERE status = 'Cancelled'";
                  $result = mysqli_query($conn, $sql);
                  echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                  ?>
                </h6>
              </span>
            </button>
          </div>
        </div>


        <div class="table-wrapper">
          <table class="product-table" id="product-table">
            <thead>
              <tr>
                <th>TRANSACT NO</th>
                <th>BRANCH</th>
                <th>FLIGHT DATE</th>
                <th>TOTAL PAX</th>
                <th>PACKAGE PRICE</th>
                <th>TOTAL REQUEST COST</th>
                <th>AMOUNT PAID</th>
                <th>BALANCE</th>
                <th>STATUS</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Ensure $conn is properly initialized
              if (!isset($conn)) {
                die("Database connection error.");
              }

              $sql = "SELECT b.transactNo, f.flightDepartureDate AS departureDate, f.returnDepartureDate AS returnDate, 
                        b.status AS bookingStatus, CONCAT(f.flightDepartureDate, ' | ', f.returnDepartureDate) AS FlightDate, 
                        p.packageName AS PackageName, DATE_FORMAT(b.bookingDate, '%m.%d.%Y') AS BookingDate, b.pax AS TotalPax,  
                        b.totalPrice AS PackagePrice, br.branchName as branchName, COALESCE(SUM(pa.amount), 0) AS TotalAmountPaid,
                        CONCAT(a.lName, ', ', a.fName, ' ', IFNULL(CONCAT(SUBSTRING(a.mName, 1, 1), '.'), '')) AS agentName,
                        COALESCE(SUM(r.requestCost), 0) AS TotalRequestAmount,
                        CASE 
                          WHEN a.accountId IS NOT NULL 
                            THEN CASE WHEN a.companyId IS NOT NULL THEN c.companyName ELSE br.branchName END
                          WHEN cl.accountId IS NOT NULL 
                            THEN CASE WHEN cl.companyId IS NOT NULL THEN cc.companyName ELSE br.branchName END
                          ELSE 'Unknown'END AS `ACCOUNT NAME`
                      FROM booking b
                      JOIN branch br ON b.agentCode = br.branchAgentCode
                      JOIN flight f ON f.flightId = b.flightId
                      JOIN package p ON p.packageId = b.packageId
                      LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
                      LEFT JOIN company c ON a.companyId = c.companyId
                      LEFT JOIN client cl ON b.accountType = 'Client' AND b.accountId = cl.accountId
                      LEFT JOIN company cc ON cl.companyId = cc.companyId
                      LEFT JOIN payment pa ON pa.transactNo = b.transactNo AND pa.paymentStatus = 'Approved'
                      LEFT JOIN request r ON r.transactNo = b.transactNo AND r.requestStatus = 'Confirmed'
                      GROUP BY 
                        b.transactNo, f.flightDepartureDate, f.returnDepartureDate, b.status, 
                        p.packageName, b.bookingDate, b.pax, b.totalPrice, a.lName, a.fName, a.mName, br.branchName
                      ORDER BY CAST(SUBSTRING_INDEX(b.transactNo, '-', -1) AS UNSIGNED)";

              // Execute the query
              $result = $conn->query($sql);

              // Check if there are results
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  // Safely handle null values
                  $transactNo = htmlspecialchars($row['transactNo'] ?? '');
                  $agentName = htmlspecialchars($row['agentName'] ?? '');
                  $packageName = htmlspecialchars($row['PackageName'] ?? '');
                  $departureDate = $row['departureDate'] ?? null;
                  $returnDate = $row['returnDate'] ?? null;
                  $bookingDate = htmlspecialchars($row['BookingDate'] ?? '');
                  $totalPax = htmlspecialchars($row['TotalPax'] ?? 0);
                  $packagePrice = $row['PackagePrice'] ?? 0;
                  $requestTotal = $row['TotalRequestAmount'] ?? 0;
                  $amountPaid = $row['TotalAmountPaid'] ?? 0;
                  $balance = max(($packagePrice + $requestTotal) - $amountPaid, 0); // Prevent negative balances
                  $status = htmlspecialchars($row['bookingStatus'] ?? 'Unknown');

                  // Determine the status class
                  $statusClass = match ($status) {
                    "Pending" => "bg-warning text-dark",
                    "Confirmed" => "bg-success text-white",
                    "Cancelled" => "bg-danger text-white",
                    "Reject" => "bg-secondary text-white",
                    default => "bg-secondary text-white",
                  };

                  // Format dates
                  $formattedDepartureDate = $departureDate ? (new DateTime($departureDate))->format('F j, Y') : 'N/A';
                  $formattedReturnDate = $returnDate ? (new DateTime($returnDate))->format('F j, Y') : 'N/A';

                  // Securely encode URL
                  $transactionUrl = htmlspecialchars("emp-transactionInfo.php?id=$transactNo");

                  // Output each row as a table row
                  echo "<tr data-url='$transactionUrl'>";
                  echo "<td>$transactNo</td>";
                  echo "<td>" . htmlspecialchars($row['ACCOUNT NAME'] ?? '') . "</td>";
                  echo "<td>$formattedDepartureDate</td>";
                  echo "<td class='fw-bold ps-3'>$totalPax</td>";
                  echo "<td>₱ " . number_format($packagePrice, 2) . "</td>";
                  echo "<td>₱ " . number_format($requestTotal, 2) . "</td>";
                  echo "<td>₱ " . number_format($amountPaid, 2) . "</td>";
                  echo "<td>₱ " . number_format($balance, 2) . "</td>";
                  echo "<td> <span class='badge rounded-pill $statusClass p-2'>$status</span></td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='8' class='text-center'>No records found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>

        <div class="table-footer">
          <div class="pagination-controls">
            <button id="prevPage" class="pagination-btn">Previous</button>
            <span id="pageInfo" class="page-info">Page 1 of 10</span>
            <button id="nextPage" class="pagination-btn">Next</button>
          </div>
        </div>

      </div>

    </div>
  </div>

  <?php include '../Employee Section/includes/emp-scripts.php' ?>

  
  <!-- For Button Tabs Status Sorting -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Get the status from the URL
      let statusTab = "<?php echo isset($_GET['status']) ? $_GET['status'] : ''; ?>";
      console.log("Status from URL:", statusTab); // Debugging

      // Find all filter buttons
      let buttons = document.querySelectorAll("#booking-filter-tabs .filter-btn");

      // Remove 'active' class from all buttons
      buttons.forEach(btn => btn.classList.remove("active"));

      // Find the button that matches the status
      let matchedButton = [...buttons].find(btn => btn.getAttribute("data-filter") === statusTab);

      if (matchedButton) {
        matchedButton.classList.add("active"); // Highlight the correct button
        console.log("Activating button:", matchedButton.innerText);

        setTimeout(() => {
          matchedButton.click();
        }, 3);

      } else {
        // Default to "All" if no match found
        let defaultButton = document.querySelector("#booking-filter-tabs .filter-btn[data-filter='']");
        if (defaultButton) {
          defaultButton.classList.add("active");
          console.log("Activating default button: All");

          setTimeout(() => {
            defaultButton.click();
          }, 100);
        }
      }

      // Add click event listener to each button
      buttons.forEach(button => {
        button.addEventListener("click", function() {
          // Remove active class from all buttons
          buttons.forEach(btn => btn.classList.remove("active"));

          // Add active class to the clicked button
          this.classList.add("active");

          let filterValue = this.getAttribute("data-filter");

          // Apply DataTables filtering
          if ($.fn.DataTable.isDataTable("#product-table")) {
            $('#product-table').DataTable().column(8).search(filterValue || '', true, false).draw();
          }
        });
      });
    });
  </script>

  <!-- Row Click Selection-->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll("tr[data-url]").forEach(function(row) {
        row.addEventListener("click", function() {
          const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

          console.log("Transaction Number: ", transactionNumber); // Debugging line

          // Use AJAX to send the transaction number to the server
          $.ajax({
            url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
            type: 'POST',
            data: {
              transaction_number: transactionNumber
            },
            success: function(response) {
              console.log("Response: ", response); // Debugging line

              // Redirect to the next page after successfully setting the session
              window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
            },
            error: function(xhr, status, error) {
              console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
            }
          });
        });
      });
    });
  </script>

  <!-- DataTables #product-table -->
  <script>
    $(document).ready(function() {
      const table = $('#product-table').DataTable({
        dom: 'rtip', // Use only the relevant table elements
        language: {
          emptyTable: "No Transaction Records Available"
        },
        order: [
          [0, 'desc']
        ], // Default sorting by Transaction ID (descending)
        scrollX: false,
        scrollY: '73vh', // Set a fixed height for the table (adjust as necessary)
        paging: true, // Enable pagination
        pageLength: 15, // Set the number of rows per page
        autoWidth: false,
        autoHeight: false, // Prevent automatic height adjustment

        // Disable sorting for specific columns
        columnDefs: [{
          targets: [1, 2, 3, 4, 5, 6, 7, 8], // Disable sorting for 2nd and 4th columns
          orderable: false
        }]
      });

      // Search Functionality
      $('#search').on('keyup', function() {
        table.search(this.value).draw();
      });

      // Update the custom pagination buttons and page info
      function updatePagination() {
        const info = table.page.info();
        const currentPage = info.page + 1; // Get current page number (1-indexed)
        const totalPages = info.pages; // Get total pages

        // Update page info text
        $('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);

        // Enable/Disable prev and next buttons based on current page
        $('#prevPage').prop('disabled', currentPage === 1);
        $('#nextPage').prop('disabled', currentPage === totalPages);
      }

      // Custom pagination button click events
      $('#prevPage').on('click', function() {
        table.page('previous').draw('page');
        updatePagination();
      });

      $('#nextPage').on('click', function() {
        table.page('next').draw('page');
        updatePagination();
      });

      // Initialize pagination on first load
      updatePagination();

      // Package Filter
      $('#packages').on('change', function() {
        const selectedPackage = $(this).val();
        table.column(1).search(selectedPackage || '').draw();
      });

      // Booking Date Filter with value change
      $('#BookingStartDate').on('change', function() {
        const selectedBookingDate = $(this).val(); // Get the selected value directly from the input field
        console.log("Booking Date Filter:", selectedBookingDate); // Log the selected booking date
        table.column(3).search(selectedBookingDate || '').draw(); // Column 4 (index starts at 0)
      });

      // Flight Date Filter with value change
      $('#FlightStartDate').on('change', function() {
        const selectedFlightDate = $(this).val(); // Get the selected value directly from the input field
        console.log("Flight Date Filter:", selectedFlightDate); // Log the selected flight date
        table.column(3).search(selectedFlightDate || '').draw(); // Column 5 (index starts at 0)
      });

      // Apply datepicker and input validation for FlightStartDate
      $("#FlightStartDate").datepicker({
        dateFormat: "yy-mm-dd", // Set the format to MM-DD-YYYY
        showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
        changeMonth: true, // Allow the month to be changed from the dropdown
        changeYear: true, // Allow the year to be changed from the dropdown
        yearRange: "1900:2100", // Set a range of years (optional)
        onSelect: function(dateText) {
          // When a date is selected, update the input field with the date
          $(this).val(dateText);
          flightStartDate = dateText; // Store the selected date
          console.log("FlightStartDate Selected Date (onSelect): " + dateText);
          table.column(3).search(flightStartDate || '').draw(); // Column 5 (index starts at 0)
        }
      });


      // Apply datepicker and input validation for BookingStartDate
      $("#BookingStartDate").datepicker({
        dateFormat: "mm-dd-yy", // Set the format to MM-DD-YYYY
        showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
        changeMonth: true, // Allow the month to be changed from the dropdown
        changeYear: true, // Allow the year to be changed from the dropdown
        yearRange: "1900:2100", // Set a range of years (optional)
        onSelect: function(dateText) {
          // When a date is selected, update the input field with the date
          $(this).val(dateText);
          bookingStartDate = dateText; // Store the selected date
          console.log("FlightStartDate Selected Date (onSelect): " + dateText);
          table.column(4).search(bookingStartDate || '').draw(); // Column 5 (index starts at 0)
        }
      });

      // BookingStartDate Input Validation and Formatting
      $("#BookingStartDate").on("input", function() {
        var value = $(this).val();

        // Remove non-numeric and non-dash characters
        value = value.replace(/[^\d-]/g, '');

        // Automatically add dashes in the correct places if necessary
        if (value.length > 2 && value.charAt(2) !== '-') {
          value = value.substring(0, 2) + '-' + value.substring(2);
        }
        if (value.length > 5 && value.charAt(5) !== '-') {
          value = value.substring(0, 5) + '-' + value.substring(5);
        }

        // Limit the total input length to 10 characters (MM-DD-YYYY)
        if (value.length > 10) {
          value = value.substring(0, 10);
        }

        // Update the input field value
        $(this).val(value);

        // Reset or update the bookingStartDate variable
        if (value === "") {
          bookingStartDate = ""; // Reset the variable if the input is cleared
        } else {
          bookingStartDate = value; // Update the variable with the formatted value
        }

        // Update the table column search
        table.column(5).search(bookingStartDate || '').draw(); // Column 5 (index starts at 0)

        console.log("BookingStartDate Input Value (on input): " + value);
      });


      // Clear All Filters
      $('#clearSorting').on('click', function() {
        // Clear search field
        $('#search').val('');
        table.search('').draw();

        // Reset status dropdown to "Select Status"
        $('#status').val('').trigger('change');

        // Reset branch dropdown to "Select Branch"
        $('#packages').val('').trigger('change');

        // Explicitly reset date filter variables
        flightStartDate = '';
        bookingStartDate = '';

        // Clear date fields
        $('#BookingStartDate').val('').trigger('change');
        $('#FlightStartDate').val('').trigger('change');

        // Reset DataTable filters & sorting
        table.order([
            [0, 'desc']
          ]) // Default sort by first column (Transaction ID)
          .search('') // Clear any search input
          .columns().search('') // Reset all column filters
          .draw(); // Redraw table to default state
      });

    });
  </script>

  </body>
</html>