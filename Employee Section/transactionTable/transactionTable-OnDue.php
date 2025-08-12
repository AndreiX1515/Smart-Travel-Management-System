<div class="table-container">

  <!-- Header Section -->
  <div class="table-header">

    <!-- Search Input -->
    <div class="search-wrapper">
      <label class="" for="">Search: </label>
      <div class="search-input-wrapper">
        <input type="text" id="search" placeholder="Search here..">
      </div>
    </div>

    <!-- Filters -->
    <div class="second-header-wrapper">

      <!-- Show All Transaction Toggle -->
      <!-- <div class="sorting-wrapper aligned-item">
        <label style="opacity: 0;">Clear</label>
        <form method="GET">
          <label class="toggle-switch">
            <input type="checkbox" id="showAll" name="showAll" value="1" 
            <?= 
              isset($_GET['showAll']) ? 'checked' : '' 
            ?>
              onchange="this.form.submit()">
            <span class="slider"></span>
          </label>
          <label for="showAll">Show All Transactions</label>
        </form>
      </div> -->

      <!-- Flight Date Filter -->
      <div class="sorting-wrapper aligned-item">
        <label class="" for="">Flight Date: </label>
        <div class="input-with-icon">
          <input type="text" class="datepicker" id="FlightStartDate" placeholder="Flight Date" readonly>
          <i class="fas fa-calendar-alt calendar-icon"></i>
        </div>
      </div>

      <!-- Branch Selection -->
      <div class="sorting-wrapper aligned-item">
        <label class="" for="">Select Branch: </label>
        <div class="select-wrapper">
          <select id="packages">
            <option value="" disabled selected>Select Branch</option>
            <?php
            $sql1 = "SELECT branchId, branchName FROM branch ORDER BY branchName ASC";
            $res1 = $conn->query($sql1);
            if ($res1->num_rows > 0) {
              while ($row = $res1->fetch_assoc()) {
                echo "<option value='" . $row['branchName'] . "'>" . $row['branchName'] . "</option>";
              }
            } else {
              echo "<option value=''>No branches available</option>";
            }
            ?>
          </select>
        </div>
      </div>

      <!-- Clear Filters Button -->
      <div class="aligned-item">
        <button id="clearSorting" class="btn btn-secondary">Clear Filters</button>
      </div>

    </div>
    
  </div>

  <!-- On Due Filter Tabs -->
  <div class="navpills-container">
    <ul class="nav nav-pills nav-underline" id="ondue-filter-tabs" role="tablist">

      <!-- All On Due -->
      <li class="nav-item" role="presentation">
        <button id="onDue-all-filter" class="nav-link <?php if (empty($onDueTab) || strtolower($onDueTab) == 'all')
          echo 'active'; ?>"
          data-filter="all" type="button">
          All
          <span class="badge">
            <?php
            $sql = "SELECT COUNT(*) AS totalBookings 
                  FROM booking b
                  JOIN flight f ON b.flightId = f.flightId
                  WHERE b.status = 'Confirmed' AND f.flightDepartureDate < CURDATE()";
            $res = mysqli_query($conn, $sql);
            echo ($res) ? mysqli_fetch_assoc($res)['totalBookings'] : 0;
            ?>
          </span>
        </button>
      </li>

      <!-- 5 Days -->
      <li class="nav-item" role="presentation">
        <button id="onDue-5d-filter" class="nav-link <?php if ($onDueTab == '5days')
          echo 'active'; ?>" data-filter="5days" type="button">
          5 Days
          <span class="badge">
            <?php
            $sql = "SELECT COUNT(*) AS totalBookings 
                  FROM booking b
                  JOIN flight f ON b.flightId = f.flightId
                  WHERE b.status = 'Confirmed' AND DATEDIFF(f.flightDepartureDate, CURDATE()) <= 5";
            $res = mysqli_query($conn, $sql);
            echo ($res) ? mysqli_fetch_assoc($res)['totalBookings'] : 0;
            ?>
          </span>
        </button>
      </li>

      <!-- 10 Days -->
      <li class="nav-item" role="presentation">
        <button id="onDue-10d-filter" class="nav-link <?php if ($onDueTab == '10days')
          echo 'active'; ?>" data-filter="10days" type="button">
          10 Days
          <span class="badge">
            <?php
            $sql = "SELECT COUNT(*) AS totalBookings 
                  FROM booking b
                  JOIN flight f ON b.flightId = f.flightId
                  WHERE b.status = 'Confirmed' AND DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 6 AND 10";
            $res = mysqli_query($conn, $sql);
            echo ($res) ? mysqli_fetch_assoc($res)['totalBookings'] : 0;
            ?>
          </span>
        </button>
      </li>

      <!-- 20 Days -->
      <li class="nav-item" role="presentation">
        <button id="onDue-20d-filter" class="nav-link <?php if ($onDueTab == '20days')
          echo 'active'; ?>" data-filter="20days" type="button">
          20 Days
          <span class="badge">
            <?php
            $sql = "SELECT COUNT(*) AS totalBookings 
                  FROM booking b
                  JOIN flight f ON b.flightId = f.flightId
                  WHERE b.status = 'Confirmed' AND DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 11 AND 20";
            $res = mysqli_query($conn, $sql);
            echo ($res) ? mysqli_fetch_assoc($res)['totalBookings'] : 0;
            ?>
          </span>
        </button>
      </li>

      <!-- > 30 Days -->
      <li class="nav-item" role="presentation">
        <button id="onDue-30d-filter" class="nav-link <?php if ($onDueTab == '30daysplus')
          echo 'active'; ?>" data-filter="30daysplus"
          type="button">
          > 30 Days
          <span class="badge">
            <?php
            $sql = "SELECT COUNT(*) AS totalBookings 
                  FROM booking b
                  JOIN flight f ON b.flightId = f.flightId
                  WHERE b.status = 'Confirmed' AND DATEDIFF(f.flightDepartureDate, CURDATE()) > 30";
            $res = mysqli_query($conn, $sql);
            echo ($res) ? mysqli_fetch_assoc($res)['totalBookings'] : 0;
            ?>
          </span>
        </button>
      </li>

    </ul>
  </div>

  <!-- Table & Pagination -->
  <div class="body-content-wrapper">
    <div class="table-wrapper">
      <table id="ondue-table" class="table-clean">
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

    <!-- Footer -->
    <div class="table-footer">
      <div class="last-update-wrapper accent-text">
        <span class="header-text">Last updated:</span>
        <span>April 30, 2025 • 10:15 AM</span>
      </div>

      <div class="pagination-controls">
        <button id="onduePrevPage" class="btn-pagination">Previous</button>
        <span id="onduePageInfo" class="page-info">Page 1 of 10</span>
        <button id="ondueNextPage" class="btn-pagination">Next</button>
      </div>
    </div>
  </div>

</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const tableSelector = '#ondue-table';
    const dateColIndex = 2; // Column index for flight departure date
    const navLinks = document.querySelectorAll(".navpills-container .nav-link");

    const prevBtn = document.getElementById('onduePrevPage');
    const nextBtn = document.getElementById('ondueNextPage');
    const pageInfoEl = document.getElementById('onduePageInfo');


    // Init DataTable
    if ($.fn.DataTable.isDataTable(tableSelector)) {
        $(tableSelector).DataTable().destroy();
    }

    const table = $(tableSelector).DataTable({
        dom: 'rtip',
        language: { emptyTable: "No Transaction Records Available" },
        order: [[dateColIndex, 'asc']],
        scrollX: false,
        paging: true,
        pageLength: 13,
        autoWidth: false,
        columnDefs: [
            { targets: [1, 3, 4, 5, 6, 7], orderable: false }
        ]
    });

    setTimeout(() => { table.columns.adjust().draw(); }, 100);


    function updatePageInfo() {
        const pageInfo = table.page.info();
        pageInfoEl.textContent = `Page ${pageInfo.page + 1} of ${pageInfo.pages}`;
        
        // Disable buttons if at ends
        prevBtn.disabled = pageInfo.page === 0;
        nextBtn.disabled = pageInfo.page === pageInfo.pages - 1 || pageInfo.pages === 0;
    }

    // Bind buttons
    prevBtn.addEventListener('click', () => {
        table.page('previous').draw('page');
        updatePageInfo();
    });

    nextBtn.addEventListener('click', () => {
        table.page('next').draw('page');
        updatePageInfo();
    });

    // Update on table draw
    table.on('draw', updatePageInfo);

    // Init state
    updatePageInfo();


    // Search
    $('#search').on('keyup', function () {
      tableProduct.search(this.value).draw();
    });

    // Clear filters
    $('#clearSorting').on('click', function () {
        // Clear other filters
        $('#search').val('');
        tableProduct.search('').draw();
        $('#branch').val('').trigger('change');
        $('#status').val('').trigger('change');
        tableProduct.order([[2, 'asc']]).columns().search('').draw();

        // Reset toggle (uncheck)
        $('#showAll').prop('checked', false);

        // Remove "showAll" from URL
        const newUrl = new URL(window.location.href);
        newUrl.searchParams.delete('showAll');
        history.replaceState(null, '', newUrl.toString());


        // OPTIONAL: If you need to re-fetch default table data via AJAX without reload
        // tableProduct.ajax.url('your-default-data-url.php').load();
    });


    // Packages dropdown filter
    $('#branch').on('change', function () {
      tableProduct.column(1).search($(this).val() || '').draw();
    });



    flatpickr("#FlightStartDate", {
      dateFormat: "Y-m-d",
      allowInput: true,
      defaultDate: null,
      yearSelectorType: "dropdown", // Enable dropdown for year
      minDate: `${new Date().getFullYear() - 10}-01-01`,
      maxDate: `${new Date().getFullYear() + 10}-12-31`,
      onChange: function (selectedDates, dateStr) {
        $('#FlightStartDate').val(dateStr);
        table.column(2).search(dateStr || '').draw();
      }
    });


    // Get URL params
    const urlParams = new URLSearchParams(window.location.search);
    let initialOnDue = urlParams.get('onDue') || 'all';

    // Apply initial filter
    applyOnDueFilter(initialOnDue);

    // Set active tab visually
    navLinks.forEach(link => {
        if ((link.dataset.filter || '') === initialOnDue) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    // Nav click handler
    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            const filter = this.dataset.filter || 'all';
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            applyOnDueFilter(filter);
            updateUrlParam('onDue', filter);
        });
    });

    // Apply On Due filter logic (date range only)
    function applyOnDueFilter(filterValue) {
        // Remove any previous date filter
        $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(fn => fn.name !== 'dueDateFilter');

        // If "all" → show everything (skip date filter)
        if (filterValue === 'all') {
            table.draw();
            return;
        }

        const dueDateFilter = function dueDateFilter(settings, data) {
            const dateStr = data[dateColIndex];
            if (!dateStr) return false;

            const flightDate = new Date(dateStr);
            if (isNaN(flightDate)) return false;

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            flightDate.setHours(0, 0, 0, 0);

            const diffInDays = Math.floor((flightDate - today) / (1000 * 60 * 60 * 24));

            switch (filterValue) {
                case "5days": return diffInDays <= 5;
                case "10days": return diffInDays >= 6 && diffInDays <= 10;
                case "20days": return diffInDays >= 11 && diffInDays <= 20;
                case "30daysplus": return diffInDays >= 31;
                default: return true;
            }
        };

        dueDateFilter.name = 'dueDateFilter';
        $.fn.dataTable.ext.search.push(dueDateFilter);

        table.draw();
    }

    // Update URL without reload
    function updateUrlParam(key, value) {
        const newUrl = new URL(window.location.href);
        if (value === 'all') {
            newUrl.searchParams.delete(key);
        } else {
            newUrl.searchParams.set(key, value);
        }
        history.replaceState(null, '', newUrl.toString());
    }
});
</script>


<script>
    

  </script>