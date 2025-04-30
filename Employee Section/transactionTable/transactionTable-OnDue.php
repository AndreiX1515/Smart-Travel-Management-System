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
            <!-- All Button -->
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

            <!-- Pending Button -->
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

            <!-- Reserved Button -->
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

            <!-- Confirmed Button -->
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

            <!-- Cancelled Button -->
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

            <!-- Remaining Balance Button -->
            <button class="filter-btn remaining-balance" data-filter="Balanced">With Remaining Balance
                <span class="badge-status-tab">
                    <h6>
                        <?php
                        $sql = "SELECT COUNT(*) AS totalBookings, SUM(p.amount) AS sum
                                FROM booking b
                                JOIN payment p ON p.transactNo = b.transactNo;
                                ";
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
                    } else {
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