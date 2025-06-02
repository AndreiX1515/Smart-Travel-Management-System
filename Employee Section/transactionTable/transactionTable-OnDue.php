<?php
$onDue = isset($_GET['onDue']) ? $_GET['onDue'] : 'all';
?>

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
        <button id="prevPage" class="pagination-btn">Previous</button>
        <span id="pageInfo" class="page-info">Page 1 of 10</span>
        <button id="nextPage" class="pagination-btn">Next</button>
      </div>
    </div>

  </div>

</div>


<!-- For Button Tabs - On Due Sorting -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const onDueTabBtn = document.getElementById('pills-home-tab');
    const onDueTabPane = document.getElementById('pills-home');

    if (!onDueTabBtn || !onDueTabPane) return;

    function initOnDueFilter() {
      const onDue = "<?php echo isset($_GET['onDue']) ? $_GET['onDue'] : 'all'; ?>";
      console.log("onDue from URL:", onDue);

      const buttons = document.querySelectorAll("#booking-filter-tabs .filter-btn");

      // Remove current active-tab
      buttons.forEach(btn => btn.classList.remove("active-tab"));

      // Find and activate the matching button
      const matchedButton = Array.from(buttons).find(btn =>
        btn.getAttribute("data-filter") === onDue
      );

      if (matchedButton) {
        matchedButton.classList.add("active-tab");
        console.log("Activating button:", matchedButton.innerText);
        setTimeout(() => matchedButton.click(), 10);
      } else {
        console.log("No matching filter button found.");
      }

      // Rebind click events to prevent duplicates
      buttons.forEach(button => {
        button.removeEventListener("click", handleClick); // Prevent multiple bindings
        button.addEventListener("click", handleClick);
      });

      function handleClick() {
        buttons.forEach(btn => btn.classList.remove("active-tab"));
        this.classList.add("active-tab");

        const filterValue = this.getAttribute("data-filter")?.toLowerCase();

        if ($.fn.DataTable.isDataTable("#ondue-table")) {
          const table = $('#ondue-table').DataTable();

          // Remove existing dueDateFilter
          $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(f => f.name !== 'dueDateFilter');

          // Add new custom filter for flight date
          const dueDateFilter = function dueDateFilter(settings, data, dataIndex) {
            const flightDateStr = data[2]; // Flight Date column (index 2)

            if (!flightDateStr) return false; // exclude rows without flight date

            const flightDate = new Date(flightDateStr);
            if (isNaN(flightDate)) return false; // invalid date, exclude

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            flightDate.setHours(0, 0, 0, 0);

            const diffInDays = Math.floor((flightDate - today) / (1000 * 60 * 60 * 24));

            switch (filterValue) {
              case "overdue":
                return diffInDays < 0;  // flight date is before today
              case "5days":
                return diffInDays >= 0 && diffInDays <= 5;
              case "10days":
                return diffInDays >= 0 && diffInDays <= 10;
              case "20days":
                return diffInDays >= 0 && diffInDays <= 20;
              case "30daysplus":
                return diffInDays >= 31;
              default:
                return true; // show all rows if no filter
            }
          };

          dueDateFilter.name = 'dueDateFilter';
          $.fn.dataTable.ext.search.push(dueDateFilter);

          table.draw();
        }
      }

    }

    // Run filter logic every time the tab is shown
    onDueTabBtn.addEventListener('shown.bs.tab', function () {
      initOnDueFilter();
    });

    // If the tab is already active on page load
    if (onDueTabPane.classList.contains('active')) {
      initOnDueFilter();
    }
  });
</script>


<!-- DataTables #product-table -->
<script>
  $(document).ready(function () {
    const tableProduct = $('#ondue-table').DataTable({
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

    const updatePagination = () => {
      const info = tableProduct.page.info();
      const currentPage = info.page + 1;
      const totalPages = info.pages;

      $('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);
      const isSinglePage = totalPages <= 1;

      $('#prevPage').prop('disabled', currentPage === 1 || isSinglePage);
      $('#nextPage').prop('disabled', currentPage === totalPages || isSinglePage);
    };

    // Pagination Controls
    $('#prevPage').on('click', () => {
      tableProduct.page('previous').draw('page');
      updatePagination();
    });

    $('#nextPage').on('click', () => {
      tableProduct.page('next').draw('page');
      updatePagination();
    });

    // Search
    $('#search').on('keyup', function () {
      tableProduct.search(this.value).draw();
      updatePagination();
    });

    // Filters
    $('#packages').on('change', function () {
      const val = $(this).val();
      tableProduct.column(1).search(val || '').draw();
      updatePagination();
    });

    $('#BookingStartDate').on('change', function () {
      const val = $(this).val();
      tableProduct.column(3).search(val || '').draw();
      updatePagination();
    });

    $('#FlightStartDate').on('change', function () {
      const val = $(this).val();
      tableProduct.column(2).search(val || '').draw();
      updatePagination();
    });

    // Datepickers
    $("#FlightStartDate").datepicker({
      dateFormat: "yy-mm-dd",
      showAnim: "fadeIn",
      changeMonth: true,
      changeYear: true,
      yearRange: "1900:2100",
      onSelect: function (dateText) {
        $(this).val(dateText);
        tableProduct.column(2).search(dateText || '').draw();
        updatePagination();
      }
    });

    $("#BookingStartDate").datepicker({
      dateFormat: "mm-dd-yy",
      showAnim: "fadeIn",
      changeMonth: true,
      changeYear: true,
      yearRange: "1900:2100",
      onSelect: function (dateText) {
        $(this).val(dateText);
        tableProduct.column(4).search(dateText || '').draw();
        updatePagination();
      }
    });

    // Manual input formatter
    $("#BookingStartDate").on("input", function () {
      let value = $(this).val().replace(/[^\d-]/g, '');
      if (value.length > 2 && value.charAt(2) !== '-') {
        value = value.substring(0, 2) + '-' + value.substring(2);
      }
      if (value.length > 5 && value.charAt(5) !== '-') {
        value = value.substring(0, 5) + '-' + value.substring(5);
      }
      if (value.length > 10) {
        value = value.substring(0, 10);
      }

      $(this).val(value);
      tableProduct.column(5).search(value || '').draw();
      updatePagination();
    });

    // Clear All Filters
    $('#clearSorting').on('click', function () {
      $('#search, #BookingStartDate, #FlightStartDate').val('');
      $('#status, #packages').val('').trigger('change');

      tableProduct
        .order([[2, 'asc']])
        .search('')
        .columns().search('')
        .draw();

      updatePagination();
    });

    // Initial call
    updatePagination();
  });
</script>