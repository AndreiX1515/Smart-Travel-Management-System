<?php
$statusTab = isset($_GET['status']) && $_GET['status'] !== ''
    ? $_GET['status']
    : 'all';
$showAll = isset($_GET['showAll']) && $_GET['showAll'] == '1';
$dateFilter = $showAll ? '' : "f.flightDepartureDate > CURDATE()";

// Build status WHERE condition only when not 'All'
$whereStatus = '';
if ($statusTab !== 'All') {
    $statusEsc = mysqli_real_escape_string($conn, $statusTab);
    $whereStatus = " AND b.status = '{$statusEsc}'";
}

// Example: main query that feeds the table (use $whereStatus)
$sql = "SELECT b.*, f.* 
        FROM booking b 
        JOIN flight f ON f.flightId = b.flightId
        WHERE 1 {$whereStatus} " . ($dateFilter ? "AND $dateFilter" : "");
$result = mysqli_query($conn, $sql);
?>



<div class="table-container">

    <div class="table-header">

        <div class="search-wrapper">
            <label class="" for="">Search: </label>
            <div class="search-input-wrapper">
                <input type="text" id="search" placeholder="Search here..">
            </div>
        </div>

        <div class="second-header-wrapper">

            <!-- Branch Selection -->
            <div class="sorting-wrapper aligned-item">
                <label for="branch">Select Branch</label>
                <div class="select-wrapper">
                    <select id="branch">
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

            <!-- Show All Transaction Toggle -->
            <!-- <div class="sorting-wrapper aligned-item">
                    <label style="opacity: 0;">Clear</label>
                    <form method="GET">
                    <label class="toggle-switch">
                        <input type="checkbox" id="showAll" name="showAll" value="1" <?= isset($_GET['showAll']) ? 'checked' : '' ?>
                        onchange="this.form.submit()">
                        <span class="slider"></span>
                    </label>
                    <label for="showAll">Show All Transactions</label>
                    </form>
                </div> -->

            <!-- Clear Button -->
            <div class="aligned-item">
                <label style="opacity: 0;">Clear</label> <!-- invisible label to align height -->
                <button id="clearSorting" class="btn btn-secondary">
                    Clear Filters
                </button>
            </div>

        </div>

    </div>

    <div class="navpills-container">
        <ul class="nav nav-pills nav-underline" id="booking-filter-tabs" role="tablist">

            <!-- All -->
            <li class="nav-item" role="presentation">
                <button id="viewAll-all-filter" class="nav-link filter-btn" data-filter="all" type="button">
                    All <span class="badge">
                        <?php
                        $sql = "SELECT COUNT(*) AS totalBookings 
                            FROM booking b 
                            JOIN flight f ON f.flightId = b.flightId 
                            WHERE 1 " . ($dateFilter ? "AND $dateFilter" : "");
                        $res = mysqli_query($conn, $sql);
                        echo ($res) ? mysqli_fetch_assoc($res)['totalBookings'] : 0;
                        ?>
                    </span>
                </button>
            </li>

            <!-- Pending -->
            <li class="nav-item" role="presentation">
                <button id="viewAll-pending-filter"  class="nav-link filter-btn" data-filter="Pending" type="button">
                    Pending <span class="badge">
                        <?php
                        $sql = "SELECT COUNT(*) AS totalBookings 
                            FROM booking b 
                            JOIN flight f ON f.flightId = b.flightId 
                            WHERE b.status = 'Pending' " . ($dateFilter ? "AND $dateFilter" : "");
                        $res = mysqli_query($conn, $sql);
                        echo ($res) ? mysqli_fetch_assoc($res)['totalBookings'] : 0;
                        ?>
                    </span>
                </button>
            </li>

            <!-- Reserved -->
            <li class="nav-item" role="presentation">
                <button id="viewAll-reserved-filter"  class="nav-link filter-btn" data-filter="Reserved" type="button">
                    Reserved <span class="badge">
                        <?php
                        $sql = "SELECT COUNT(*) AS totalBookings 
                            FROM booking b 
                            JOIN flight f ON f.flightId = b.flightId 
                            WHERE b.status = 'Reserved' " . ($dateFilter ? "AND $dateFilter" : "");
                        $res = mysqli_query($conn, $sql);
                        echo ($res) ? mysqli_fetch_assoc($res)['totalBookings'] : 0;
                        ?>
                    </span>
                </button>
            </li>

            <!-- Confirmed -->
            <li class="nav-item" role="presentation">
                <button id="viewAll-confirmed-filter"  class="nav-link filter-btn" data-filter="Confirmed" type="button">
                    Confirmed <span class="badge">
                        <?php
                        $sql = "SELECT COUNT(*) AS totalBookings 
                            FROM booking b 
                            JOIN flight f ON f.flightId = b.flightId 
                            WHERE b.status = 'Confirmed' " . ($dateFilter ? "AND $dateFilter" : "");
                        $res = mysqli_query($conn, $sql);
                        echo ($res) ? mysqli_fetch_assoc($res)['totalBookings'] : 0;
                        ?>
                    </span>
                </button>
            </li>

            <!-- Cancelled -->
            <li class="nav-item" role="presentation">
                <button id="viewAll-cancelled-filter" class="nav-link filter-btn" data-filter="Cancelled" type="button">
                    Cancelled <span class="badge">
                        <?php
                        $sql = "SELECT COUNT(*) AS totalBookings 
                            FROM booking b 
                            JOIN flight f ON f.flightId = b.flightId 
                            WHERE b.status = 'Cancelled' " . ($dateFilter ? "AND $dateFilter" : "");
                        $res = mysqli_query($conn, $sql);
                        echo ($res) ? mysqli_fetch_assoc($res)['totalBookings'] : 0;
                        ?>
                    </span>
                </button>
            </li>

        </ul>
    </div>



    <div class="body-content-wrapper">
        <div class="table-wrapper">
            <table id="viewAll-table" class="table-clean">
                <thead>
                    <tr>
                        <th>TRANSACTION NO.</th>
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

                    if ($dateFilter)

                        $whereClauses[] = $dateFilter;

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
                    GROUP BY b.transactNo, f.flightDepartureDate, f.returnDepartureDate, b.status, p.packageName, b.bookingDate, b.pax, b.totalPrice, a.lName, a.fName, a.mName, br.branchName
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

        <div class="table-footer" id="viewAll-footer">
            <!-- Last Update Info -->
            <div class="last-update-wrapper accent-text">
                <span class="header-text">Last updated:</span>
                <span id="viewAll-lastUpdate">April 30, 2025 - 10:15 AM</span>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination-controls">
                <button id="viewAll-prevPage" class="btn-pagination" disabled>Previous</button>
                <span id="viewAll-pageInfo" class="page-info">Page 1 of 1</span>
                <button id="viewAll-nextPage" class="btn-pagination" disabled>Next</button>
            </div>
        </div>



    </div>
    
</div>


<!-- For Button Tabs - View All Filtering -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const viewAllTabBtn = document.getElementById('pills-view-tab'); // View All tab trigger
        const viewAllTabPane = document.getElementById('pills-viewTransactions'); // View All tab content

        if (!viewAllTabBtn || !viewAllTabPane) return;

        function initViewAllFilter() {
            const statusFromURL = "<?php echo isset($_GET['status']) ? $_GET['status'] : 'all'; ?>";
            console.log("Status from URL:", statusFromURL);

            const buttons = document.querySelectorAll("#booking-filter-tabs .filter-btn");
            buttons.forEach(btn => btn.classList.remove("active"));

            const matchedButton = Array.from(buttons).find(
                btn => btn.getAttribute("data-filter") === statusFromURL
            );

            if (matchedButton) {
                matchedButton.classList.add("active");
                setTimeout(() => matchedButton.click(), 10);
            } else {
                const defaultButton = document.querySelector("#booking-filter-tabs .filter-btn[data-filter='']");
                if (defaultButton) {
                    defaultButton.classList.add("active");
                    setTimeout(() => defaultButton.click(), 100);
                }
            }

            buttons.forEach(button => {
                button.removeEventListener("click", handleFilterClick);
                button.addEventListener("click", handleFilterClick);
            });

            function handleFilterClick() {
                buttons.forEach(btn => btn.classList.remove("active"));
                this.classList.add("active");

                const filterValue = this.getAttribute("data-filter");
                if ($.fn.DataTable.isDataTable("#viewAll-table")) {
                    $('#viewAll-table').DataTable()
                        .column(9) // STATUS column
                        .search(filterValue || '', true, false)
                        .draw();
                }
            }
        }

        // Trigger when the View All tab is shown
        viewAllTabBtn.addEventListener('shown.bs.tab', initViewAllFilter);

        // Initialize if it's already active on page load
        if (viewAllTabPane.classList.contains('active')) {
            initViewAllFilter();
        }
    });
</script>


<!-- DataTables Init for #viewAll-table -->
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#viewAll-table')) {
            $('#viewAll-table').DataTable().destroy();
        }

        const tableViewAll = $('#viewAll-table').DataTable({
            dom: 'rtip',
            language: { emptyTable: "No Transaction Records Available" },
            order: [[2, 'asc']],
            scrollX: false,
            paging: true,
            pageLength: 13,
            autoWidth: false,
            columnDefs: [
                { targets: [1, 3, 4, 5, 6, 7], orderable: false }
            ]
        });

        const statusColIndex = 9;
        const urlParams = new URLSearchParams(window.location.search);
        let initialFilter = urlParams.get('status') || 'all';

        if (initialFilter.toLowerCase() !== 'all') {
            tableViewAll.column(statusColIndex).search('^' + initialFilter + '$', true, false).draw();
        } else {
            tableViewAll.column(statusColIndex).search('').draw();
        }

        // Pagination Info - View All
        function updateViewAllPagination() {
            const info = tableViewAll.page.info();
            $('#viewAll-pageInfo').text(`Page ${info.page + 1} of ${info.pages}`);
            $('#viewAll-prevPage').prop('disabled', info.page + 1 === 1 || info.pages <= 1);
            $('#viewAll-nextPage').prop('disabled', info.page + 1 === info.pages || info.pages <= 1);
        }

        tableViewAll.on('draw', updateViewAllPagination);
        updateViewAllPagination();

        // Pagination buttons - View All
        $('#viewAll-nextPage').on('click', () => tableViewAll.page('next').draw('page'));
        $('#viewAll-prevPage').on('click', () => tableViewAll.page('previous').draw('page'));




        // Search box
        $('#search').on('keyup', function () {
            tableViewAll.search(this.value).draw();
        });

        // Clear filters
        $('#clearSorting').on('click', function () {
            $('#search').val('');
            $('#branch').val('').trigger('change');
            $('#status').val('').trigger('change');
            tableViewAll.order([[2, 'asc']]).columns().search('').draw();
            $('#showAll').prop('checked', false);

            const newUrl = new URL(window.location.href);
            newUrl.searchParams.delete('showAll');
            history.replaceState(null, '', newUrl.toString());
        });

        // Branch filter
        $('#branch').on('change', function () {
            tableViewAll.column(1).search($(this).val() || '').draw();
        });

        // Nav pills click filter
        $('.navpills-container .nav-link').on('click', function () {
            const status = $(this).data('filter') || 'all';

            if (!$(this).hasClass('active')) {
                $('.navpills-container .nav-link').removeClass('active');
                $(this).addClass('active');
            }

            if (status.toLowerCase() === 'all') {
                tableViewAll.column(statusColIndex).search('').draw();
                history.replaceState(null, '', window.location.pathname);
            } else {
                tableViewAll.column(statusColIndex).search('^' + status + '$', true, false).draw();
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set('status', status);
                history.replaceState(null, '', newUrl.toString());
            }
        });
    });
</script>