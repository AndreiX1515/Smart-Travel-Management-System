<div class="table-container">
  <div class="table-header">
    <div class="search-wrapper">
      <div class="search-input-wrapper">
        <input type="text" id="search" placeholder="Search here..">
      </div>
    </div>

    <!-- Filter group -->
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
              if ($res1->num_rows > 0) 
              {
                // Loop through the results and generate options
                while ($row = $res1->fetch_assoc()) 
                {
                  echo "<option value='" . $row['branchName'] . "'>" . $row['branchName'] . "</option>";
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

      <div class="buttons-wrapper">
        <button id="clearSorting" class="btn btn-secondary">
          Clear Filters
        </button>
      </div>
    </div>

  </div>

  <div class="navpills-container">
    <div class="filter-tabs" id="booking-filter-tabs">
      <!-- All Button -->
      <button class="filter-btn active" data-filter="all">
        All
        <span class="badge-status-tab">
          <h6>
            <?php
              $sql = "SELECT COUNT(*) AS totalBookings FROM booking b
                      JOIN flight f ON b.flightId = f.flightId
                      WHERE f.flightDepartureDate < CURDATE()";
              $result = mysqli_query($conn, $sql);
              echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
            ?>
          </h6>
        </span>
      </button>

      <button class="filter-btn active" data-filter="overdue">
        Overdue
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

      <!-- 5 Days (Default) -->
      <button class="filter-btn" data-filter="5days">
        5 Days
        <span class="badge-status-tab">
          <h6>
            <?php
            $sql = "SELECT COUNT(*) AS totalBookings FROM booking";
            $result = mysqli_query($conn, $sql);
            echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
            ?>
          </h6>
        </span>
      </button>

      <!-- 10 Days -->
      <button class="filter-btn" data-filter="10days">
        10 Days
        <span class="badge-status-tab">
          <h6>
            <?php
            $sql = "SELECT COUNT(*) AS totalBookings FROM booking WHERE status = 'Pending'";
            $result = mysqli_query($conn, $sql);
            echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
            ?>
          </h6>
        </span>
      </button>

      <!-- 20 Days -->
      <button class="filter-btn" data-filter="20days">
        20 Days
        <span class="badge-status-tab">
          <h6>
            <?php
            $sql = "SELECT COUNT(*) AS totalBookings FROM booking WHERE status = 'Reserved'";
            $result = mysqli_query($conn, $sql);
            echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
            ?>
          </h6>
        </span>
      </button>

      <!-- More than 30 Days -->
      <button class="filter-btn" data-filter="30daysplus">
        > 30 Days
        <span class="badge-status-tab">
          <h6>
            <?php
            $sql = "SELECT COUNT(*) AS totalBookings FROM booking WHERE status = 'Confirmed'";
            $result = mysqli_query($conn, $sql);
            echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
            ?>
          </h6>
        </span>
      </button>

    </div>
  </div>

  <div class="body-content-wrapper">
    <div class="table-wrapper">
      <table class="ondue-table" id="ondue-table">
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
            <!-- <th>STATUS</th> -->
          </tr>
        </thead>
        <tbody>
          <?php
            // Ensure $conn is properly initialized
            if (!isset($conn)) 
            {
              die("Database connection error.");
            }

            $sql = "SELECT b.transactNo, DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y') AS departureDate, f.returnDepartureDate AS returnDate, 
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
            if ($result->num_rows > 0) 
            {
              while ($row = $result->fetch_assoc()) 
              {
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
                  default => "bg-secondary text-white",};

                // Format dates
                // $formattedDepartureDate = $departureDate ? (new DateTime($departureDate))->format('F j, Y') : 'N/A';
                $formattedReturnDate = $returnDate ? (new DateTime($returnDate))->format('F j, Y') : 'N/A';

                // Securely encode URL
                $transactionUrl = htmlspecialchars("emp-transactionInfo.php?id=$transactNo");

                // Output each row as a table row
                echo "<tr data-url='$transactionUrl'>";
                echo "<td>$transactNo</td>";
                echo "<td>" . htmlspecialchars($row['ACCOUNT NAME'] ?? '') . "</td>";
                echo "<td>$departureDate</td>";
                echo "<td class='fw-bold ps-3'>$totalPax</td>";
                echo "<td>₱ " . number_format($packagePrice, 2) . "</td>";
                echo "<td>₱ " . number_format($requestTotal, 2) . "</td>";
                echo "<td>₱ " . number_format($amountPaid, 2) . "</td>";
                echo "<td>₱ " . number_format($balance, 2) . "</td>";
                echo "<td> <span class='badge rounded-pill $statusClass p-2'>$status</span></td>";
                echo "</tr>";
              }
            } 
            else 
            {
              echo "<tr><td colspan='8' class='text-center'>No records found</td></tr>";
            }
          ?>
        </tbody>
      </table>
    </div>

    <div class="table-footer">
      <div class="last-update-wrapper">
        <span>Last updated:</span>
        <span>April 30, 2025 • 10:15 AM</span>
      </div>

      <div class="pagination-controls">
        <button id="onduePrevPage" class="pagination-btn">Previous</button>
        <span id="onduePageInfo" class="page-info">Page 1 of 10</span>
        <button id="ondueNextPage" class="pagination-btn">Next</button>
      </div>
    </div>

  </div>
</div>


<!-- Enhanced Script for Button Tabs - On Due Sorting -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const onDueTabBtn = document.getElementById('pills-home-tab');
    const onDueTabPane = document.getElementById('pills-home');
    const buttons = document.querySelectorAll("#booking-filter-tabs .filter-btn");

    if (!onDueTabBtn || !onDueTabPane || !buttons.length) return;

    const onDue = "<?php echo isset($_GET['onDue']) ? $_GET['onDue'] : 'all'; ?>";
    console.log("onDue from URL:", onDue);

    // Initialize on tab shown
    onDueTabBtn.addEventListener('shown.bs.tab', () => {
      requestAnimationFrame(initOnDueFilter);
    });

    // Also run on load if already active
    if (onDueTabPane.classList.contains('active')) {
      requestAnimationFrame(initOnDueFilter);
    }

    function initOnDueFilter() {
      // Reset all active-tab classes
      buttons.forEach(btn => btn.classList.remove("active-tab"));

      const matchedButton = Array.from(buttons).find(btn =>
        btn.getAttribute("data-filter") === onDue
      );

      if (matchedButton) {
        matchedButton.classList.add("active-tab");
        console.log("Activating filter button:", matchedButton.innerText);
        matchedButton.click(); // Trigger handler
      } else {
        console.warn("No matching filter button found for:", onDue);
      }

      // Clean old and reattach new event listeners
      buttons.forEach(button => {
        button.removeEventListener("click", handleClick);
        button.addEventListener("click", handleClick, { passive: true });
      });
    }

    function handleClick(event) {
      buttons.forEach(btn => btn.classList.remove("active-tab"));
      this.classList.add("active-tab");

      const filterValue = this.getAttribute("data-filter")?.toLowerCase() || "";

      if (!$.fn.DataTable.isDataTable("#ondue-table")) return;

      const table = $('#ondue-table').DataTable();

      // Clear old filters with name 'dueDateFilter'
      $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(fn => fn.name !== 'dueDateFilter');

      const dueDateFilter = function dueDateFilter(settings, data) {
        const dateStr = data[2]; // Assumes date is in column index 2
        if (!dateStr) return false;

        const flightDate = new Date(dateStr);
        if (isNaN(flightDate)) return false;

        const today = new Date();
        today.setHours(0, 0, 0, 0);
        flightDate.setHours(0, 0, 0, 0);

        const diffInDays = Math.floor((flightDate - today) / (1000 * 60 * 60 * 24));

        switch (filterValue) {
          case "overdue": return diffInDays < 0;
          case "5days": return diffInDays >= 0 && diffInDays <= 5;
          case "10days": return diffInDays >= 0 && diffInDays <= 10;
          case "20days": return diffInDays >= 0 && diffInDays <= 20;
          case "30daysplus": return diffInDays >= 31;
          default: return true;
        }
      };

      dueDateFilter.name = 'dueDateFilter';
      $.fn.dataTable.ext.search.push(dueDateFilter);

      table.draw();
    }
  });
</script>


<!-- DataTables #ondue-table -->
<script>
  $(document).ready(function () {
    const ondueTable = $('#ondue-table').DataTable({
      dom: 'rtip',
      language: {
        emptyTable: "No Transaction Records Available"
      },
      order: [[2, 'asc']],
      scrollX: false,
      paging: true,
      pageLength: 14,
      autoWidth: false,
      autoHeight: false,
      columnDefs: [{
        targets: [1, 3, 4, 5, 6, 7, 8],
        orderable: false
      }]
    });

    const updateOnduePagination = () => {
      const info = ondueTable.page.info();
      const currentPage = info.page + 1;
      const totalPages = info.pages;

      $('#onduePageInfo').text(`Page ${currentPage} of ${totalPages}`);
      const isSinglePage = totalPages <= 1;

      $('#onduePrevPage').prop('disabled', currentPage === 1 || isSinglePage);
      $('#ondueNextPage').prop('disabled', currentPage === totalPages || isSinglePage);
    };

    // Pagination Controls
    $('#onduePrevPage').on('click', () => {
      ondueTable.page('previous').draw('page');
      updateOnduePagination();
    });

    $('#ondueNextPage').on('click', () => {
      ondueTable.page('next').draw('page');
      updateOnduePagination();
    });

    // Search
    $('#ondueSearch').on('keyup', function () {
      ondueTable.search(this.value).draw();
      updateOnduePagination();
    });

    // Filters
    $('#onduePackagesFilter').on('change', function () {
      const val = $(this).val();
      ondueTable.column(1).search(val || '').draw();
      updateOnduePagination();
    });

    // // Flight Date filter using Flatpickr
    // const ondueFlightDateInput = document.getElementById("ondueFlightStartDate");

    // flatpickr(ondueFlightDateInput, {
    //   dateFormat: "Y-m-d",
    //   allowInput: true,
    //   onChange: function (selectedDates, dateStr, instance) {
    //     ondueTable.column(2).search(dateStr || '').draw();
    //     updateOnduePagination();
    //   }
    // });

    // // Manual input fallback
    // ondueFlightDateInput.addEventListener('input', function () {
    //   const val = this.value;
    //   ondueTable.column(2).search(val || '').draw();
    //   updateOnduePagination();
    // });

    // Clear All Filters
    $('#ondueClearFilters').on('click', function () {
      $('#ondueSearch, #ondueBookingStartDate, #ondueFlightStartDate').val('');
      $('#ondueStatusFilter, #onduePackagesFilter').val('').trigger('change');

      ondueTable
        .order([[2, 'asc']])
        .search('')
        .columns().search('')
        .draw();

      updateOnduePagination();
    });

    // Initial call
    updateOnduePagination();
  });
</script>

