<?php
$statusTab = isset($_GET['status']) ? $_GET['status'] : 'all';
$showAll = isset($_GET['showAll']) && $_GET['showAll'] == '1';
$dateFilter = $showAll ? '' : "f.flightDepartureDate > CURDATE()";
?>

<div class="table-container">

  <div class="table-header">

    <div class="search-wrapper">
      <div class="search-input-wrapper">
        <input type="text" id="search" placeholder="Search here..">
      </div>
    </div>

    <div>
      <form method="GET">
        <input type="checkbox" id="showAll" name="showAll" value="1"
          <?= isset($_GET['showAll']) ? 'checked' : '' ?>
          onchange="this.form.submit()">
        <label for="showAll">Show All Transactions</label>
      </form>
    </div>

    <div class="second-header-wrapper">
      <div class="date-range-wrapper sorting-wrapper">
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
      <button class="filter-btn active" data-filter="">
        All
        <span class="badge-status-tab">
          <h6>
            <?php
              $sql = "SELECT COUNT(*) AS totalBookings 
                      FROM booking b 
                      JOIN flight f ON f.flightId = b.flightId 
                      WHERE 1 " . ($dateFilter ? "AND $dateFilter" : "");
              $result = mysqli_query($conn, $sql);
              echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
            ?>
          </h6>
        </span>
      </button>

      <!-- Pending Button -->
      <button class="filter-btn" data-filter="Pending">Pending
        <span class="badge-status-tab">
          <h6>
            <?php
              $sql = "SELECT COUNT(*) AS totalBookings 
                      FROM booking b 
                      JOIN flight f ON f.flightId = b.flightId 
                      WHERE b.status = 'Pending' " . ($dateFilter ? "AND $dateFilter" : "");
              $result = mysqli_query($conn, $sql);
              echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
            ?>
          </h6>
        </span>
      </button>

      <!-- Reserved Button -->
      <button class="filter-btn" data-filter="Reserved">Reserved
        <span class="badge-status-tab">
          <h6>
            <?php
              $sql = "SELECT COUNT(*) AS totalBookings 
                      FROM booking b 
                      JOIN flight f ON f.flightId = b.flightId 
                      WHERE b.status = 'Reserved' " . ($dateFilter ? "AND $dateFilter" : "");
              $result = mysqli_query($conn, $sql);
              echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
            ?>
          </h6>
        </span>
      </button>

      <!-- Confirmed Button -->
      <button class="filter-btn" data-filter="Confirmed">Confirmed
        <span class="badge-status-tab">
          <h6>
            <?php
              $sql = "SELECT COUNT(*) AS totalBookings 
                      FROM booking b 
                      JOIN flight f ON f.flightId = b.flightId 
                      WHERE b.status = 'Confirmed' " . ($dateFilter ? "AND $dateFilter" : "");
              $result = mysqli_query($conn, $sql);
              echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
            ?>
          </h6>
        </span>
      </button>

      <!-- Cancelled Button -->
      <button class="filter-btn" data-filter="Cancelled">Cancelled
        <span class="badge-status-tab">
          <h6>
            <?php
              $sql = "SELECT COUNT(*) AS totalBookings 
                      FROM booking b 
                      JOIN flight f ON f.flightId = b.flightId 
                      WHERE b.status = 'Cancelled' " . ($dateFilter ? "AND $dateFilter" : "");
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
      <table id="product-table" class="product-table">
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
            <th>BOOKING DATE</th>
            <th>STATUS</th>
          </tr>
        </thead>

        <tbody>
          <?php
            // Ensure $conn is properly initialized
            if (!isset($conn)) {
              die("Database connection error.");
            }

            $whereClauses = [];
            if ($dateFilter) $whereClauses[] = $dateFilter;

            $where = count($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

            $sql = "SELECT b.transactNo, DATE_FORMAT(f.flightDepartureDate, '%Y.%m.%d') AS departureDate, f.returnDepartureDate AS returnDate, 
                      b.status AS bookingStatus, CONCAT(f.flightDepartureDate, ' | ', f.returnDepartureDate) AS FlightDate, 
                      p.packageName AS PackageName, b.bookingDate, b.pax AS TotalPax,  
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
                    $where
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
                // $formattedDepartureDate = $departureDate ? (new DateTime($departureDate))->format('F j, Y') : 'N/A';
                $formattedReturnDate = $returnDate ? (new DateTime($returnDate))->format('F j, Y') : 'N/A';
                $formattedBookingDate = date('m.d.Y', strtotime($row['bookingDate']));

                // Securely encode URL
                $transactionUrl = htmlspecialchars("emp-transactionInfo.php?id=$transactNo");

                // Output each row as a table row
                echo "<tr data-url='$transactionUrl'>";
                echo "<td>$transactNo</td>";
                echo "<td>" . htmlspecialchars($row['branchName'] ?? '') . "</td>";
                echo "<td>$departureDate</td>";
                echo "<td class='fw-bold ps-3'>$totalPax</td>";
                echo "<td>₱ " . number_format($packagePrice, 2) . "</td>";
                echo "<td>₱ " . number_format($requestTotal, 2) . "</td>";
                echo "<td>₱ " . number_format($amountPaid, 2) . "</td>";
                echo "<td>₱ " . number_format($balance, 2) . "</td>";
                echo "<td>" . $formattedBookingDate . "</td>";
                echo "<td> <span class='badge rounded-pill $statusClass p-2'>$status</span></td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='8' class='text-center'>No records found</td></tr>";
            }

            echo "<!-- DEBUG: Total rows = " . ($result->num_rows ?? 0) . " -->";
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


<!-- For Button Tabs Status Sorting -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const statusTabBtn = document.getElementById('pills-profile-tab'); // Tab trigger
    const statusTabPane = document.getElementById('pills-profile');    // Tab content

    if (!statusTabBtn || !statusTabPane) return;

    function initStatusFilter() {
      const status = "<?php echo isset($_GET['status']) ? $_GET['status'] : 'all'; ?>";
      console.log("Status from URL:", status);

      const buttons = document.querySelectorAll("#booking-filter-tabs .filter-btn");

      // Reset classes
      buttons.forEach(btn => btn.classList.remove("active"));

      // Find and activate matching button
      const matchedButton = Array.from(buttons).find(btn =>
        btn.getAttribute("data-filter") === status
      );

      if (matchedButton) {
        matchedButton.classList.add("active");
        console.log("Activating button:", matchedButton.innerText);
        setTimeout(() => matchedButton.click(), 10);
      } else {
        const defaultButton = document.querySelector("#booking-filter-tabs .filter-btn[data-filter='']");
        if (defaultButton) {
          defaultButton.classList.add("active");
          console.log("Activating default button: All");
          setTimeout(() => defaultButton.click(), 100);
        }
      }

      // Rebind click events to avoid duplication
      buttons.forEach(button => {
        button.removeEventListener("click", handleClick);
        button.addEventListener("click", handleClick);
      });

      function handleClick() {
        buttons.forEach(btn => btn.classList.remove("active"));
        this.classList.add("active");

        const filterValue = this.getAttribute("data-filter");

        if ($.fn.DataTable.isDataTable("#product-table")) {
          console.log(filterValue);

          $('#product-table').DataTable()
            .column(9) // STATUS column
            .search(filterValue || '', true, false)
            .draw();
        }
      }
    }

    // Bind tab show event correctly
    statusTabBtn.addEventListener('shown.bs.tab', function () {
      initStatusFilter();
    });

    // Initialize if already active
    if (statusTabPane.classList.contains('active')) {
      initStatusFilter();
    }
  });
</script>

<!-- DataTables #product-table -->
<script>
  $(document).ready(function () {
    // Destroy previous instance if it exists
    if ($.fn.DataTable.isDataTable('#product-table')) {
      $('#product-table').DataTable().destroy();
    }

    const tableProduct = $('#product-table').DataTable({
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
      columnDefs: [
        { targets: [1, 3, 4, 5, 6, 7], orderable: false }
      ]
    });

    // Always recalculate pagination correctly
    function updatePagination() {
      const info = tableProduct.page.info();
      const currentPage = info.page + 1;
      const totalPages = info.pages;

      $('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);
      $('#prevPage').prop('disabled', currentPage === 1 || totalPages <= 1);
      $('#nextPage').prop('disabled', currentPage === totalPages || totalPages <= 1);
    }

    tableProduct.on('draw', function () {
      updatePagination();
    });

    tableProduct.draw();

    $('#nextPage').on('click', function () {
      tableProduct.one('draw', updatePagination); // Wait until draw completes
      tableProduct.page('next').draw('page');
    });

    $('#prevPage').on('click', function () {
      tableProduct.one('draw', updatePagination); // Wait until draw completes
      tableProduct.page('previous').draw('page');
    });

    $('#search').on('keyup', function () {
      tableProduct.search(this.value).draw();
    });

    $('#clearSorting').on('click', function () {
      $('#search').val('');
      tableProduct.search('').draw();

      $('#packages').val('').trigger('change');
      $('#status').val('').trigger('change');

      tableProduct.order([[2, 'asc']])
        .columns().search('')
        .draw();
    });

    $('#packages').on('change', function () {
      const selectedPackage = $(this).val();
      tableProduct.column(1).search(selectedPackage || '').draw();
    });
  });

</script>