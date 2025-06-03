<?php
session_start();
require "../conn.php";

echo "<script>console.log('Session Data:', " . json_encode($_SESSION, JSON_PRETTY_PRINT) . ");</script>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-dashboard.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">

  <?php include '../Agent Section/functions/exchange-rate.php' ?>
</head>

<body>

  <?php include "../Client Section/Includes/client-sidebar.php"; ?>

  <div class="main-container">

    <div class="navbar">
      <div class="page-header-wrapper">

        <!-- <div class="page-header-top">
          <div class="back-btn-wrapper">
            <button class="back-btn" id="redirect-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
          </div>
        </div> -->

        <div class="page-header-content">
          <div class="page-header-text">
            <h5 class="header-title">Transaction</h5>
          </div>
        </div>

      </div>
    </div>

    <div class="main-content">

      <div class="content-container">

        <!-- Cards Count 1st Row -->
        <div class="header-counts">

          <!-- Card 1 -->
          <div class="card">
            <div class="counts-header">
              <div class="title-wrapper">
                <h6 class="">Active Transaction</h6>
              </div>

              <div class="accent-pill mt-1">
                <h6 class="accent-pill"><?php echo date('F, Y'); ?></h6>
              </div>
            </div>

            <div class="card-content card-content-body">
              <!-- Total and Confirmed Transaction Count -->
              <div class="row">

                <div class="col-md-5 clickable-card" onclick="redirectToTransactionStatus('current')">
                  <div class="card-icon icon-blue">
                    <i class="fas fa-calendar-alt"></i>
                  </div>

                  <div class="side-content">
                    <?php
                    $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking b
                                                JOIN flight f ON b.flightId = f.flightId 
                                                WHERE b.accountId = '$accountId' AND f.flightDepartureDate >= CURDATE()";

                    $result = mysqli_query($conn, $totalTransactionsQuery);

                    if ($result) {
                      $row = mysqli_fetch_assoc($result);
                      $totalTransactions = $row['total'];
                    } else {
                      $totalTransactions = 0;
                    }
                    ?>
                    <h5><?php echo $totalTransactions; ?></h5>
                    <p>TOTAL</p>
                  </div>
                </div>

                <div class="col-md-5 clickable-card" onclick="redirectToTransactionStatus('Confirmed')">
                  <div class="card-icon icon-green">
                    <i class="fas fa-check-circle"></i>
                  </div>

                  <div class="side-content d-flex flex-column">
                    <?php
                    $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking b
                                                JOIN flight f ON b.flightId = f.flightId
                                                WHERE b.status='Confirmed' AND b.accountId = '$accountId' 
                                                AND f.flightDepartureDate >= CURDATE()";

                    $result = mysqli_query($conn, $totalTransactionsQuery);

                    if ($result) {
                      $row = mysqli_fetch_assoc($result);
                      $totalTransactions = $row['total'];
                    } else {
                      $totalTransactions = 0;
                    }
                    ?>
                    <h5><?php echo $totalTransactions; ?></h5>
                    <p>CONFIRMED</p>
                  </div>
                </div>

              </div>

              <!-- Pending, Reserved, and Cancelled Transaction Count -->
              <div class="row">

                <div class="col-md-4 clickable-card" onclick="redirectToTransactionStatus('Pending')">
                  <div class="card-icon icon-yellow">
                    <i class="fas fa-exclamation-triangle"></i>
                  </div>

                  <div class="side-content d-flex flex-column">
                    <?php
                    $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking b
                                                JOIN flight f ON b.flightId = f.flightId
                                                WHERE b.status = 'Pending' AND b.accountId = '$accountId' 
                                                AND f.flightDepartureDate >= CURDATE()";

                    $result = mysqli_query($conn, $totalTransactionsQuery);

                    if ($result) {
                      $row = mysqli_fetch_assoc($result);
                      $totalTransactions = $row['total'];
                    } else {
                      $totalTransactions = 0;
                    }
                    ?>
                    <h5><?php echo $totalTransactions; ?></h5>
                    <p>PENDING</p>
                  </div>
                </div>

                <div class="col-md-4 clickable-card" onclick="redirectToTransactionStatus('Reserved')">
                  <div class="card-icon bg-secondary">
                    <i class="fas fa-exclamation-triangle"></i>
                  </div>

                  <div class="side-content d-flex flex-column">
                    <?php
                    $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking b
                                                JOIN flight f ON b.flightId = f.flightId
                                                WHERE b.status = 'Reserved' AND b.accountId = '$accountId' 
                                                AND f.flightDepartureDate >= CURDATE()";

                    $result = mysqli_query($conn, $totalTransactionsQuery);

                    if ($result) {
                      $row = mysqli_fetch_assoc($result);
                      $totalTransactions = $row['total'];
                    } else {
                      $totalTransactions = 0;
                    }
                    ?>
                    <h5><?php echo $totalTransactions; ?></h5>
                    <p>RESERVED</p>
                  </div>
                </div>

                <div class="col-md-4 clickable-card card-cancelled" onclick="redirectToTransactionStatus('Cancelled')">
                  <div class="card-icon icon-red">
                    <i class="fas fa-times-circle"></i>
                  </div>

                  <div class="side-content d-flex flex-column">
                    <?php
                    $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking b
                                                JOIN flight f ON b.flightId = f.flightId
                                                WHERE b.status = 'Cancelled' AND b.accountId = '$accountId' 
                                                AND f.flightDepartureDate >= CURDATE()";

                    $result = mysqli_query($conn, $totalTransactionsQuery);

                    if ($result) {
                      $row = mysqli_fetch_assoc($result);
                      $totalTransactions = $row['total'];
                    } else {
                      $totalTransactions = 0;
                    }
                    ?>
                    <h5><?php echo $totalTransactions; ?></h5>
                    <p>CANCELLED</p>
                  </div>
                </div>

              </div>
            </div>

          </div>

          <!-- Card 2 -->
          <div class="card card-top">
            <div class="counts-header">
              <div class="title-wrapper">
                <h6 class="">On Due</h6>
              </div>
            </div>

            <div class="card-content card-content-body">
              <!-- 5 Days and 15 Days Due Count -->
              <div class="row">
                <!-- 5 Days Due Count -->
                <div class="col-md-5 clickable-card" onclick="redirectToTransactionOnDue('5days')">

                  <div class="card-icon icon-blue">
                    <i class="fas fa-calendar-check"></i>
                  </div>

                  <div class="side-content">
                    <?php
                      $days5Query = "SELECT COUNT(*) AS bookingsDueIn5Days 
                                    FROM booking b 
                                    JOIN flight f ON b.flightId = f.flightId
                                    LEFT JOIN (
                                      SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) AS totalPaid 
                                      FROM paymentc GROUP BY transactNo) p ON b.transactNo = p.transactNo
                                    WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) <= 5 
                                      AND DATEDIFF(f.flightDepartureDate, CURDATE()) >= 0
                                      AND (b.totalPrice > IFNULL(p.totalPaid, 0)) 
                                      AND b.accountId = '$accountId' 
                                      AND b.status = 'Confirmed'";

                      $result = $conn->query($days5Query);
                      $bookingsDueIn5Days = ($result->num_rows > 0) ? $result->fetch_assoc()['bookingsDueIn5Days'] : 0;
                    ?>
                    <h5><?php echo $bookingsDueIn5Days; ?></h5>
                    <p>5 DAYS BEFORE FLIGHT</p>
                  </div>
                </div>

                <!-- 15 Days Due Count -->
                <div class="col-md-5 clickable-card" onclick="redirectToTransactionOnDue('15days')">

                  <div class="card-icon icon-blue">
                    <i class="fas fa-calendar-check"></i>
                  </div>

                  <div class="side-content">
                    <?php
                      $days15Query = "SELECT COUNT(*) AS bookingsDueIn15Days 
                                      FROM booking b
                                      JOIN flight f ON b.flightId = f.flightId
                                      LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) AS totalPaid 
                                        FROM paymentc GROUP BY transactNo) p ON b.transactNo = p.transactNo
                                      WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 6 AND 15
                                        AND (b.totalPrice > IFNULL(p.totalPaid, 0)) 
                                        AND b.accountId = '$accountId' 
                                        AND b.status = 'Confirmed'";

                      $result = $conn->query($days15Query);
                      $bookingsDueIn15Days = ($result->num_rows > 0) ? $result->fetch_assoc()['bookingsDueIn15Days'] : 0;
                    ?>
                    <h5><?php echo $bookingsDueIn15Days; ?></h5>
                    <p>15 DAYS BEFORE FLIGHT</p>
                  </div>
                </div>
              </div>

              <!-- 30 Days and more than 30 Days Due Count -->
              <div class="row">
                <!-- 30 Days Due Count -->
                <div class="col-md-5 clickable-card" onclick="redirectToTransactionOnDue('30days')">

                  <div class="card-icon icon-blue">
                    <i class="fas fa-calendar-check"></i>
                  </div>

                  <div class="side-content">
                    <?php
                      $days30Query = "SELECT COUNT(*) AS bookingsDueIn30Days 
                                      FROM booking b 
                                      JOIN flight f ON b.flightId = f.flightId
                                      LEFT JOIN (
                                        SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) AS totalPaid 
                                        FROM paymentc GROUP BY transactNo
                                      ) p ON b.transactNo = p.transactNo
                                      WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 16 AND 30
                                        AND (b.totalPrice > IFNULL(p.totalPaid, 0)) 
                                        AND b.accountId = '$accountId' 
                                        AND b.status = 'Confirmed'";

                      $result = $conn->query($days30Query);
                      $bookingsDueIn30Days = ($result->num_rows > 0) ? $result->fetch_assoc()['bookingsDueIn30Days'] : 0;
                    ?>
                    <h5><?php echo $bookingsDueIn30Days; ?></h5>
                    <p>30 DAYS BEFORE FLIGHT</p>
                  </div>
                </div>

                <!-- More than 30 Days Due Count -->
                <div class="col-md-5 clickable-card" onclick="redirectToTransactionOnDue('30daysplus')">

                  <div class="card-icon icon-blue">
                    <i class="fas fa-calendar-check"></i>
                  </div>

                  <div class="side-content">
                    <?php
                      $daysMoreThan30Query = "SELECT COUNT(*) AS bookingsOver30DaysAfterFlight 
                                              FROM booking b 
                                              JOIN flight f ON b.flightId = f.flightId
                                              LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) AS totalPaid 
                                                FROM paymentc GROUP BY transactNo) p ON b.transactNo = p.transactNo
                                              WHERE DATEDIFF(CURDATE(), f.flightDepartureDate) > 30
                                                AND (b.totalPrice > IFNULL(p.totalPaid, 0)) 
                                                AND b.accountId = '$accountId' 
                                                AND b.status = 'Confirmed'";

                      $result = $conn->query($daysMoreThan30Query);
                      $bookingsDueInMoreThan30Days = ($result->num_rows > 0) ? $result->fetch_assoc()['bookingsOver30DaysAfterFlight'] : 0;
                    ?>
                    <h5><?php echo $bookingsDueInMoreThan30Days; ?></h5>
                    <p>MORE THAN A MONTH</p>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <!-- Card 3 -->
          <div class="card card-top">
            <div class="counts-header">
              <div class="title-wrapper">
                <h6 class="">Total Sales</h6>
              </div>
            </div>

            <div class="card-content card-content-body">
              <div class="row">
                <!-- Current Month Sales -->
                <div class="col-md-5 d-flex flex-row">
                  <div class="card-icon icon-gray">
                    <i class="fas fa-check-circle"></i>
                  </div>
                  <div class="side-content d-flex flex-column">
                    <?php
                      $currentMonthQuery = "SELECT IFNULL((SELECT SUM(totalPrice)
                                            FROM booking 
                                            WHERE status = 'Confirmed' AND accountId = $accountId 
                                              AND YEAR(bookingDate) = YEAR(CURRENT_DATE) 
                                              AND MONTH(bookingDate) = MONTH(CURRENT_DATE)), 0) +
                                              IFNULL((SELECT SUM(r.requestCost)
                                            FROM booking b
                                            JOIN request r ON r.transactNo = b.transactNo
                                            WHERE b.status = 'Confirmed' AND b.accountId = $accountId 
                                              AND YEAR(b.bookingDate) = YEAR(CURRENT_DATE) 
                                              AND MONTH(b.bookingDate) = MONTH(CURRENT_DATE)
                                              AND r.requestStatus = 'Confirmed' 
                                              AND YEAR(r.requestDate) = YEAR(CURRENT_DATE)
                                              AND MONTH(r.requestDate) = MONTH(CURRENT_DATE)), 0) AS totalSales";

                      $currentMonthResult = $conn->query($currentMonthQuery);
                      $currentMonthTotal = 0.00;

                      if ($currentMonthResult && $currentMonthResult->num_rows > 0) {
                        $currentMonthRow = $currentMonthResult->fetch_assoc();
                        if (isset($currentMonthRow['totalSales'])) {
                          $currentMonthTotal = (float) $currentMonthRow['totalSales'];
                        }
                      }
                    ?>
                    <h5>₱ <?php echo number_format($currentMonthTotal, 2); ?></h5>
                    <p>CURRENT MONTH</p>
                  </div>
                </div>
              </div>

              <div class="row">
                <!-- Past Month Sales -->
                <div class="col-md-5 d-flex flex-row total-sales">
                  <div class="card-icon icon-red">
                    <i class="fas fa-calendar-alt"></i>
                  </div>
                  <div class="side-content d-flex flex-column">
                    <?php
                      $pastMonthQuery = "SELECT IFNULL((SELECT SUM(totalPrice)
                                        FROM booking
                                        WHERE status = 'Confirmed' AND accountId = $accountId 
                                          AND MONTH(bookingDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                                          AND YEAR(bookingDate) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)), 0) +
                                          IFNULL((SELECT SUM(r.requestCost)
                                        FROM booking b
                                        JOIN request r ON r.transactNo = b.transactNo
                                        WHERE b.status = 'Confirmed' AND b.accountId = $accountId
                                          AND MONTH(b.bookingDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                                          AND YEAR(b.bookingDate) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
                                          AND r.requestStatus = 'Confirmed'
                                          AND MONTH(r.requestDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                                          AND YEAR(r.requestDate) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)), 0) AS totalSales";

                      $pastMonthResult = $conn->query($pastMonthQuery);
                      $pastMonthTotal = 0.00;

                      if ($pastMonthResult && $pastMonthResult->num_rows > 0) {
                        $pastMonthRow = $pastMonthResult->fetch_assoc();
                        if (isset($pastMonthRow['totalSales'])) {
                          $pastMonthTotal = (float) $pastMonthRow['totalSales'];
                        }
                      }
                    ?>
                    <h5>₱ <?php echo number_format($pastMonthTotal, 2); ?></h5>
                    <p>PAST MONTH</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 4 -->
          <div class="card card-4">

            <div class="counts-header">
              <div class="title-wrapper">
                <h6 class="">Currency Conversion</h6>
              </div>

              <div class="accent-pill">
                <button class="btn btn-primary view-currency-btn" id="addCurrencyBtn"
                  onclick="window.location.href='../Employee Section/emp-currencyHistory.php';">
                  View History
                </button>
              </div>
            </div>

            <div class="card-content card-content-body">

              <div class="currency-row">

                <!-- USD Section -->
                <div class="currency-card usd-card-wrapper">
                  <div class="flag-icon-wrapper">
                    <img src="../Assets/Flags/english-flag.png" alt="US Flag">
                    <div class="currency-text-wrapper">
                      <h5 class="currency-value">$ 1</h5>
                      <p class="currency-label">US DOLLAR</p>
                    </div>
                  </div>
                </div>

                <!-- Exchange Icon -->
                <div class="icon-container">
                  <div class="icon-wrapper-currency">
                    <i class="fas fa-exchange-alt"></i>
                  </div>
                </div>

                <!-- PHP-KR Section -->
                <div class="php-kr-card-wrapper">

                  <div class="card-php-kr">
                    <div class="card-icon kr-icon-wrapper">
                      <div class="flag-icon-wrapper">
                        <img width="30px" height="30px" src="../Assets/Flags/korean-flag.png" alt="">
                      </div>
                    </div>

                    <div class="side-content d-flex flex-column">
                      <h5 class="currency-text">₩ <?php echo number_format($usd_to_krw, 0); ?> </h5>
                      <p>KOREAN WON</p>
                    </div>
                  </div>

                  <div class="card-php-kr">
                    <div class="card-icon">
                      <div class="flag-icon-wrapper">
                        <img width="30px" height="30px" src="../Assets/Flags/philippines (2).png" alt="">
                      </div>
                    </div>

                    <div class="side-content d-flex flex-column">
                      <h5 class="currency-text">₱ <?php echo number_format($usd_to_php, 2); ?></h5>
                      <p>PHILIPPINE PESO</p>
                    </div>

                  </div>
                </div>

              </div>

            </div>

          </div>
        </div>

        <div class="second-div">
          <div class="navTabs-wrapper">
            <ul class="nav nav-pills" id="pills-tab" role="tablist">

              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill"
                  data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                  aria-selected="false">Flight Seat
                  Tracker</button>
              </li>

              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                  type="button" role="tab" aria-controls="pills-home" aria-selected="true">Payment and Requests</button>
              </li>
            </ul>
          </div>

          <div class="content-heading">
            <div class="tabs-sorting-wrapper">
              <div class="second-header-wrapper">
                <div class="date-range-wrapper flightbooking-wrapper">
                  <div class="date-range-inputs-wrapper">
                    <div class="input-with-icon">
                      <input type="text" class="datepicker" id="FlightStartDate" placeholder="Flight Date" readonly>
                      <i class="fas fa-calendar-alt calendar-icon"></i>
                    </div>
                  </div>
                </div>

                <!-- <div class="date-range-wrapper sorting-wrapper">
                      <div class="select-wrapper">
                        <select id="packages">
                          <option value="All" disabled selected>Select Branch</option>
                          <?php
                          // // Execute the SQL query
                          // $sql1 = "SELECT branchId, branchName FROM branch ORDER BY branchName ASC";
                          // $res1 = $conn->query($sql1);
                          
                          // // Check if there are results
                          // if ($res1->num_rows > 0) {
                          //   // Loop through the results and generate options
                          //   while ($row = $res1->fetch_assoc()) {
                          //     echo "<option value='" . $row['branchName'] . "'>" . $row['branchName'] . "</option>";
                          //   }
                          // } else {
                          //   echo "<option value=''>No companies available</option>";
                          // }
                          ?>
                        </select>
                      </div>
                    </div> -->


                <div class="buttons-wrapper">
                  <button id="clearSorting" class="btn btn-secondary">
                    Clear Filters
                  </button>
                </div>

              </div>
            </div>
          </div>
        </div>

        <div class="tab-content" id="pills-tabContent">

          <!-- Flight Seat - Booking Tab -->
          <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
            tabindex="0">

            <div class="tab-pane-content">

              <!-- Flight Seat -->
              <div class="one">

                <div class="table-wrapper confirm-table-container-flight">
                  <table id="info-table" class="info-table">
                    <thead>
                      <tr>
                        <th rowspan="2">ORIGIN</th>
                        <th colspan="2" class="text-center">FLIGHT DATE</th>
                        <th rowspan="2">AVAILABLE SEATS</th>
                        <th rowspan="2">ADDITIONAL SEATS</th>
                        <th rowspan="2">PRICE</th>
                        <th rowspan="2"></th>
                      </tr>

                      <tr style="top: -8px">
                        <th>START</th>
                        <th>END</th>
                      </tr>

                    </thead>

                    <tbody>
                      <?php
                        $sql = "SELECT DISTINCT a.agentCode AS agentCode, a.agentType AS agentType
                                      FROM agent a
                                      WHERE a.agentCode IS NOT NULL AND a.agentCode != ''";
                        $result = $conn->query($sql);

                        $agentColumns = '';
                        while ($row = $result->fetch_assoc()) {
                          $agentColumns .= "IFNULL(SUM(CASE WHEN b.bookingType = 'Package' AND (b.status = 'Confirmed' OR b.status = 
                                                    'Reserved') AND b.agentCode = '$agentCode' AND a.agentType = 'Retailer' 
                                                    THEN b.pax ELSE 0 END), 0) AS `{$agentCode}_AL`,

                                                  IFNULL(SUM(CASE WHEN b.bookingType = 'Package' AND (b.status = 'Confirmed' OR b.status = 
                                                    'Reserved')AND b.agentCode = '$agentCode' AND a.agentType = 'Wholeseller' 
                                                    THEN b.pax ELSE 0 END), 0) AS `{$agentCode}_LO`, ";
                        }

                        $agentColumns = rtrim($agentColumns, ', ');

                        $sql = "SELECT CONCAT(e.lName, ', ', e.fName, 
                                        IF(e.mName IS NOT NULL AND e.mName != '', CONCAT(' ', LEFT(e.mName, 1)), '')) AS TeamOP,
                                        f.origin, f.flightId as flightId, f.flightDepartureDate AS Start, f.returnDepartureDate AS End, 
                                        f.availSeats AS FlightSeat, 
                                        GREATEST(f.availSeats - IFNULL(SUM(CASE 
                                          WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                                          AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0), 0) AS AvailSeats, 
                                        IF((f.availSeats - IFNULL(SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                                          AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)) < 0, 
                                          ABS(f.availSeats - IFNULL(SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                                            AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)), 0) AS AdditionalSeats,
                                        SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                                          AND b.bookingType = 'Package' AND a.agentType = 'Retailer' THEN b.pax ELSE 0 END) AS `Air+Land`,
                                        SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') AND b.bookingType = 'Package' 
                                          AND a.agentType = 'Wholeseller' THEN b.pax ELSE 0 END) AS `LandOnly`,
                                        f.wholesalePrice AS WholesalePrice, f.flightPrice AS RetailPrice, p.packagePrice AS LandArrangement, 
                                        $agentColumns
                                    FROM employee e 
                                    RIGHT JOIN flight f ON f.employeeId = e.employeeId
                                    LEFT JOIN booking b ON b.flightId = f.flightId
                                    LEFT JOIN package p ON f.packageId = p.packageId
                                    LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
                                    LEFT JOIN client c ON b.accountType = 'Client' AND b.accountId = c.accountId
                                    WHERE f.flightDepartureDate >= CURDATE()
                                    GROUP BY 
                                        f.flightId, e.lName, e.fName, e.mName, f.origin, f.flightDepartureDate, f.returnDepartureDate, 
                                        f.availSeats, f.wholesalePrice, f.flightPrice, p.packagePrice
                                    ORDER BY f.flightDepartureDate";

                        // Step 3: Execute the query
                        $result = $conn->query($sql);

                        // Step 4: Display the results in HTML table
                        if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {

                            $formattedStart = date('Y.m.d', strtotime($row['Start']));
                            $formattedEnd = date('Y.m.d', strtotime($row['End']));

                            echo '<tr>';
                            echo '<td>' . $row['origin'] . '</td>';
                            echo '<td>' . $formattedStart . '</td>';
                            echo '<td>' . $formattedEnd . '</td>';
                            echo '<td class="fw-bold">' . $row['AvailSeats'] . '</td>';
                            echo '<td class="fw-bolder">' . $row['AdditionalSeats'] . '</td>';
                            echo '<td>₱ ' . number_format($row['RetailPrice'], 2) . '</td>';
                            echo '<td>
                                <a href="../Client Section/client-addBooking-flight.php?flightid=' . urlencode($row['flightId']) . '" class="btn btn-outline-primary">Book Now</a></td>';
                            echo '</tr>';
                          }
                        } else {
                          echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
                        }
                      ?>
                    </tbody>
                  </table>
                </div>

              </div>

              <div class="flight-seat-footer">
                <div class="pagination-controls">
                  <button id="prevPage" class="pagination-btn">Previous</button>
                  <div id="pageNumbers" class="page-numbers"></div> <!-- Optional, can be removed -->
                  <button id="nextPage" class="pagination-btn">Next</button>
                </div>
              </div>

            </div>

          </div>

          <!-- Pending/Request Container -->
          <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

            <div class="tab-pane-content">

              <div class="header-wrapper">

                <!-- Pending Transactions table -->
                <div class="pending-wrapper">
                  <div class="table-header">
                    <div class="title-wrapper">
                      <h6 class="">Pending</h6>
                    </div>
                  </div>

                  <div class="table-wrapper unconfirm-table-container">
                    <table class="table unconfirm-table">
                      <thead>
                        <tr>
                          <th>NO.</th>
                          <th>NAME</th>
                          <th>FLIGHT DATE</th>
                          <th>BOOKING DATE</th>
                          <th>STATUS</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $sql1 = "SELECT b.transactNo AS `T.N`, p.packageName AS `PACKAGE`, b.bookingType as bookingType, b.bookingDate,
                                    CASE 
                                      WHEN b.flightId IS NULL THEN 'Land Only'
                                      ELSE f.flightDepartureDate END AS `departureDate`,
                                    b.pax AS `TOTAL PAX`,
                                    CONCAT(b.lName, ', ', b.fName, ' ', 
                                      CASE WHEN b.mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ',
                                      CASE WHEN b.suffix = 'N/A' THEN '' ELSE b.suffix END ) AS `CONTACT NAME`, 
                                    b.status AS `STATUS`,
                                    CASE 
                                      WHEN a.accountId IS NOT NULL 
                                          THEN CASE WHEN a.companyId IS NOT NULL THEN c.companyName ELSE br.branchName END
                                      WHEN cl.accountId IS NOT NULL 
                                          THEN CASE WHEN cl.companyId IS NOT NULL THEN cc.companyName ELSE br.branchName END
                                      ELSE 'Unknown' END AS `ACCOUNT NAME`
                                  FROM booking b
                                  LEFT JOIN flight f ON b.flightId = f.flightId
                                  LEFT JOIN package p ON b.packageId = p.packageId
                                  LEFT JOIN agent a ON b.accountId = a.accountId
                                  LEFT JOIN company c ON a.companyId = c.companyId
                                  LEFT JOIN client cl ON b.accountId = cl.accountId
                                  LEFT JOIN company cc ON cl.companyId = cc.companyId
                                  JOIN branch br ON b.agentCode = br.branchAgentCode
                                  WHERE b.accountId = '$accountId' AND f.flightDepartureDate >= CURDATE()
                                  AND b.status = 'Pending' 
                                  ORDER BY b.transactNo DESC";

                          $res1 = $conn->query($sql1);

                          if ($res1->num_rows > 0) {
                            while ($row = $res1->fetch_assoc()) {
                              $status = htmlspecialchars($row['STATUS']);
                              $badgeClass = '';

                              $formattedFlightDate = date('Y.m.d', strtotime($row['departureDate']));
                              $formattedBookingDate = date('Y.m.d', strtotime($row['bookingDate']));

                              // Assign badge classes based on status
                              switch ($status) {
                                case 'Confirmed':
                                  $badgeClass = 'bg-success text-white'; // Green
                                  break;
                                case 'Cancelled':
                                  $badgeClass = 'bg-danger text-white'; // Red
                                  break;
                                case 'Pending':
                                  $badgeClass = 'bg-warning text-dark'; // Yellow
                                  break;
                                case 'Reject':
                                  $badgeClass = 'bg-danger text-white'; // Dark Red
                                  break;
                                default:
                                  $badgeClass = 'bg-info text-white'; // Blue for other statuses
                                  break;
                              }

                              echo "
                                    <tr data-url='client-transactionInfo.php?id=" . htmlspecialchars($row['T.N']) . "'>
                                      <td>" . htmlspecialchars(substr($row['T.N'], 5)) . "</td>
                                      <td>" . htmlspecialchars($row['ACCOUNT NAME']) . "</td>
                                      <td>" . htmlspecialchars($formattedFlightDate) . "</td>
                                      <td>" . htmlspecialchars($formattedBookingDate) . "</td>
                                      <td> <span class='badge " . $badgeClass . " p-2'>" . $status . "</span> </td>
                                    </tr>";
                            }
                          } else {
                            echo "<tr><td colspan='8' style='text-align: left;'>No bookings as of the moment</td></tr>";
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Requests table -->
                <div class="request-wrapper">
                  <div class="table-header">
                    <div class="title-wrapper">
                      <h6 class="">Requests</h6>
                    </div>
                  </div>

                  <div class="table-wrapper request-table-container">
                    <table class="table request-table">
                      <thead>
                        <tr>
                          <th>NO.</th>
                          <th>NAME</th>
                          <th>FLIGHT DATE</th>
                          <th>REQUEST</th>
                          <th>DATE REQUESTED</th>
                          <th>STATUS</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $sql1 = "SELECT r.transactNo AS `T.N`, c.concernTitle AS `Request`, COALESCE(cd.details, r.customRequest) AS `Details`, 
                                    DATE_FORMAT(r.requestDate, '%m-%d-%Y') AS `Date`,  r.requestStatus AS `Status`, b.transactNo, f.flightDepartureDate,
                                    CASE 
                                      WHEN a.accountId IS NOT NULL 
                                        THEN CASE WHEN a.companyId IS NOT NULL THEN co.companyName ELSE br.branchName END
                                      WHEN cl.accountId IS NOT NULL 
                                        THEN CASE WHEN cl.companyId IS NOT NULL THEN cc.companyName ELSE br.branchName END
                                      ELSE 'Unknown' END AS `ACCOUNT NAME`
                                  FROM request r
                                  LEFT JOIN booking b ON r.transactNo = b.transactNo
                                  LEFT JOIN flight f ON b.flightId = f.flightId
                                  LEFT JOIN concern c ON r.concernId = c.concernId
                                  LEFT JOIN concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
                                  LEFT JOIN agent a ON b.accountId = a.accountId
                                  LEFT JOIN company co ON a.companyId = co.companyId
                                  LEFT JOIN client cl ON b.accountId = cl.accountId
                                  LEFT JOIN company cc ON cl.companyId = cc.companyId
                                  JOIN branch br ON b.agentCode = br.branchAgentCode
                                  WHERE b.accountId = '$accountId' AND f.flightDepartureDate >= CURDATE()
                                  AND r.requestStatus = 'Submitted'
                                  ORDER BY r.requestDate DESC";

                          $res1 = $conn->query($sql1);

                          if ($res1 && $res1->num_rows > 0) {
                            while ($row = $res1->fetch_assoc()) {
                              $status = htmlspecialchars($row['Status']);
                              $statusClass = '';

                              $formattedRequestDate = date('Y.m.d', strtotime($row['Date']));
                              $formattedFlightDate = date('Y.m.d', strtotime($row['flightDepartureDate']));

                              // Assign badge classes based on status
                              switch ($status) {
                                case 'Active':
                                  $statusClass = 'badge bg-success text-white'; // Green
                                  break;
                                case 'Pending':
                                  $statusClass = 'badge bg-warning text-dark'; // Yellow
                                  break;
                                case 'Inactive':
                                  $statusClass = 'badge bg-danger text-white'; // Red
                                  break;
                                case 'To be confirmed':
                                  $statusClass = 'badge bg-secondary text-white'; // Gray
                                  break;
                                case 'Submitted':
                                  $statusClass = 'badge bg-warning text-dark'; // Orange
                                  break;
                                default:
                                  $statusClass = 'badge bg-light text-dark'; // Light Gray
                                  break;
                              }

                              echo "<tr data-url='client-transactionInfo.php?id=" . htmlspecialchars($row['T.N']) . "'>
                                      <td>" . htmlspecialchars(substr($row['T.N'], 5)) . "</td> 
                                      <td>" . htmlspecialchars($row['ACCOUNT NAME']) . "</td>
                                      <td>" . htmlspecialchars($formattedFlightDate) . "</td> 
                                      <td>" . htmlspecialchars($row['Request']) . "</td> 
                                      <td>" . htmlspecialchars($formattedRequestDate) . "</td> 
                                      <td>
                                        <span class='{$statusClass} p-2'>
                                          " . $status . "
                                        </span>
                                      </td>
                                    </tr>";
                            }
                          } else {
                            echo "<tr><td colspan='5' style='text-align: left;'>No requests at the moment.</td></tr>";
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Payment table -->
                <div class="payment-wrapper">
                  <div class="table-header">
                    <div class="title-wrapper">
                      <h6 class="">Payment</h6>
                    </div>
                  </div>

                  <div class="table-wrapper payment-table-container">
                    <table class="table payment-table">
                      <thead>
                        <tr>
                          <th>NO.</th>
                          <th>NAME</th>
                          <th>FLIGHT DATE</th>
                          <th>PAYMENT INFO</th>
                          <th>AMOUNT</th>
                          <th>STATUS</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sql2 = "SELECT p.transactNo AS `Transaction No`, p.paymentTitle AS `Payment Title`, CONCAT(FORMAT(p.amount, 2)) AS `Amount`, 
                                  p.paymentDate, p.paymentType AS `Payment Type`, p.paymentStatus, f.flightDepartureDate,
                                  CASE 
                                    WHEN a.accountId IS NOT NULL 
                                      THEN CASE 
                                        WHEN a.companyId IS NOT NULL THEN co.companyName 
                                        ELSE br.branchName END
                                    WHEN cl.accountId IS NOT NULL 
                                      THEN CASE 
                                        WHEN cl.companyId IS NOT NULL THEN cc.companyName 
                                        ELSE br.branchName END
                                    ELSE 'Unknown' END AS `Account Name`
                                FROM paymentc p
                                JOIN booking b ON p.transactNo = b.transactNo
                                JOIN flight f ON b.flightId = f.flightId
                                LEFT JOIN agent a ON b.accountId = a.accountId
                                LEFT JOIN company co ON a.companyId = co.companyId
                                LEFT JOIN client cl ON b.accountId = cl.accountId
                                LEFT JOIN company cc ON cl.companyId = cc.companyId
                                LEFT JOIN branch br ON b.agentCode = br.branchAgentCode
                                WHERE b.accountId = $accountId
                                AND p.paymentStatus = 'Submitted'
                                ORDER BY p.paymentDate DESC";

                        $res2 = $conn->query($sql2);

                        if ($res2 && $res2->num_rows > 0) {
                          while ($row = $res2->fetch_assoc()) {
                            $status = htmlspecialchars($row['paymentStatus']);
                            $badgeClass = '';

                            $formattedFlightDate = date('Y.m.d', strtotime($row['flightDepartureDate']));
                            $formattedPaymentDate = date('Y.m.d', strtotime($row['paymentDate']));

                            // Assign badge classes based on payment status
                            switch ($status) {
                              case 'Submitted':
                                $badgeClass = 'badge bg-warning text-dark'; // Yellow
                                break;
                              case 'Confirmed':
                                $badgeClass = 'badge bg-success'; // Green
                                break;
                              case 'Pending':
                                $badgeClass = 'badge bg-danger'; // Red
                                break;
                              default:
                                $badgeClass = 'badge bg-secondary'; // Gray
                                break;
                            }

                            $rowTrans = htmlspecialchars(substr($row['Transaction No'], 5));

                            echo "<tr data-url='agent-showGuest.php?id=" . htmlspecialchars($row['Transaction No']) . "'>
                                    <td>{$rowTrans}</td>
                                    <td>" . htmlspecialchars($row['Account Name'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . "</td>
                                    <td>{$formattedFlightDate}</td>
                                    <td> 
                                      <div class='td-content'>
                                        <h6>Title: <span>" . htmlspecialchars($row['Payment Title']) . "</span></h6>
                                        <h6>Type: <span>" . htmlspecialchars($row['Payment Type']) . "</span></h6>
                                      </div>
                                    </td>

                                    <td> 
                                      <div class='td-content d-flex flex-column align-items-left'>
                                        <h6>Amount: <span>₱ " . htmlspecialchars($row['Amount']) . "</span></h6>
                                        <h6>Date Submitted: <span>" . htmlspecialchars($formattedPaymentDate) . "</span></h6>
                                      </div>
                                    </td> 

                                    <td>
                                      <span class='{$badgeClass} p-2'>{$status}</span>
                                    </td>
                                  </tr>";
                          }
                        } else {
                          echo "<tr><td colspan='5' style='text-align: left;'>No payments at the moment</td></tr>";
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>

              <!-- Confirmed Table -->
              <div class="confirm-container">

                <div class="table-header">
                  <div class="title-wrapper">
                    <h6 class="">Confirm Transactions</h6>
                  </div>
                </div>

                <div class="table-wrapper confirm-table-container">
                  <table class="table confirm-table">
                    <thead>
                      <tr>
                        <th>NO.</th>
                        <th>NAME</th>
                        <th>FLIGHT DATE</th>
                        <th>TOTAL PAX.</th>
                        <th>CONTACT NAME</th>
                        <th>BOOKING TYPE</th>
                        <th>AMOUNT PAID</th>
                        <th>BALANCE</th>
                        <th>STATUS</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        // Query to select all records from the booking table
                        $query = "SELECT b.transactNo, b.flightId, b.pax, b.totalPrice AS packagePrice, 
                                    CONCAT(DATE_FORMAT(f.flightDepartureDate, '%Y.%m.%d'), ' - ', DATE_FORMAT(f.returnDepartureDate, 
                                    '%Y.%m.%d')) AS FlightDate, p.packageName AS packageName, br.branchName as branchName,
                                    CONCAT(b.lName, ', ', b.fName, ' ', 
                                        CASE WHEN b.mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ',
                                        CASE WHEN b.suffix = 'N/A' THEN '' ELSE b.suffix END) AS contactName, 
                                    IFNULL(req.totalRequestCost, 0) AS totalRequestCost, IFNULL(paid.totalPaidAmount, 0) AS totalPaidAmount,
                                    b.status AS bookingStatus, b.bookingType, (b.totalPrice + IFNULL(req.totalRequestCost, 0)) AS TotalCost,
                                    CASE 
                                      WHEN a.accountId IS NOT NULL 
                                        THEN CASE 
                                          WHEN a.companyId IS NOT NULL THEN co.companyName 
                                          ELSE br.branchName END
                                      WHEN cl.accountId IS NOT NULL 
                                        THEN CASE 
                                          WHEN cl.companyId IS NOT NULL THEN cc.companyName 
                                          ELSE br.branchName END
                                    ELSE 'Unknown' END AS `Account Name`
                                  FROM booking b
                                  JOIN flight f ON b.flightId = f.flightId
                                  LEFT JOIN package p ON b.packageId = p.packageId
                                  JOIN branch br ON b.agentCode = br.branchAgentCode
                                  LEFT JOIN agent a ON b.accountId = a.accountId
                                  LEFT JOIN company co ON a.companyId = co.companyId
                                  LEFT JOIN client cl ON b.accountId = cl.accountId
                                  LEFT JOIN company cc ON cl.companyId = cc.companyId
                                  LEFT JOIN 
                                    (SELECT transactNo, SUM(amount) AS totalPaidAmount FROM paymentc
                                      WHERE paymentStatus = 'Approved' GROUP BY transactNo) paid ON b.transactNo = paid.transactNo
                                  LEFT JOIN 
                                    (SELECT transactNo, SUM(requestCost) AS totalRequestCost FROM request
                                      WHERE requestStatus = 'Confirmed' GROUP BY transactNo) req ON b.transactNo = req.transactNo
                                  WHERE 
                                    b.status = 'Confirmed' and b.accountId = '$accountId' and f.flightDepartureDate >= CURDATE()";

                        $result = $conn->query($query); // Execute the query
                        
                        // Check if there are results and populate the table
                        if ($result && $result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            $bookingStatus = htmlspecialchars($row['bookingStatus']);
                            $statusClass = '';

                            switch ($bookingStatus) {
                              case 'Confirmed':
                                $statusClass = 'bg-success text-white';
                                break;
                              case 'Pending':
                                $statusClass = 'bg-warning text-dark';
                                break;
                              case 'Cancelled':
                                $statusClass = 'bg-danger text-white';
                                break;
                              case 'To be confirmed':
                                $statusClass = 'bg-secondary text-white';
                                break;
                              case 'Submitted':
                                $statusClass = 'bg-success text-white';
                                break;

                              default:
                                $statusClass = 'bg-light text-dark';
                                break;
                            }

                            $totalAmountPaid = $row['totalPaidAmount'];
                            $totalAmountToBePaid = $row['packagePrice'] + $row['totalRequestCost']; // Total price + total request cost
                            $balance = $totalAmountToBePaid - $totalAmountPaid; // Balance calculation
                        
                            // Determine if fully paid or not
                            $status = ($totalAmountPaid == $totalAmountToBePaid) ? 'Fully Paid' : 'Not Paid';

                            // Display table row
                            echo "<tr data-url='agent-showGuest.php?id=" . htmlspecialchars($row['transactNo']) . "'>";
                            echo "<td>" . htmlspecialchars(substr($row['transactNo'], 5)) . "</td>"; // TransactNo
                            echo "<td>" . htmlspecialchars($row['Account Name']) . "</td>"; // Package Name
                            echo "<td>" . htmlspecialchars($row['FlightDate']) . "</td>"; // Flight Date Range
                            echo "<td>" . htmlspecialchars($row['pax']) . "</td>"; // Pax (Number of Passengers)
                            echo "<td>" . htmlspecialchars($row['contactName']) . "</td>"; // Contact Name
                            echo "<td>" . $row['bookingType'] . "</td>"; // Booking Type 
                            echo "<td>₱ " . number_format($totalAmountPaid, 2) . "</td>"; // Total Amount Paid
                            echo "<td>₱ " . number_format($balance, 2) . "</td>"; // Balance (Amount to be paid - Amount paid)
                            echo "<td>
                                                  <span class='badge <?php echo $statusClass; ?> p-2'>
                                                      {$bookingStatus}
                                                  </span>
                                              </td>";
                            echo "</tr>";
                          }
                        } else {
                          // Display a message if no records are found
                          echo "<tr><td colspan='12'>No records found</td></tr>";
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>

        </div>

      </div>
    </div>

  </div>

  <?php require "../Agent Section/includes/scripts.php"; ?>

  <!-- Tab Div Hide Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const secondHeader = document.querySelector('.second-header-wrapper');
      const flightSeatsTab = document.querySelector('#pills-profile-tab');

      // Show on page load if default is active
      if (flightSeatsTab.classList.contains('active')) {
        secondHeader.style.display = 'flex';
      } else {
        secondHeader.style.display = 'none';
      }

      // Listen for tab shown event
      const tabs = document.querySelectorAll('button[data-bs-toggle="pill"]');
      tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
          if (e.target.id === 'pills-profile-tab') {
            secondHeader.style.display = 'flex';
          } else {
            secondHeader.style.display = 'none';
          }
        });
      });
    });
  </script>

  <script>
    function redirectToAgentTransaction(status) {
      console.log("Redirecting with status:", status);
      window.location.href = `../Client Section/client-transactions.php?status=${encodeURIComponent(status)}`;
    }
  </script>

  <script>
    $(document).ready(function () {
      const table = $('#info-table').DataTable({
        dom: 'rtip',
        paging: false, // Disable pagination
        language: {
          emptyTable: "No Transaction Records Available"
        },
        order: [[0, 'desc']],
        autoWidth: false,
        columnDefs: [{
          targets: "_all",
          className: "text-center"
        }]
      });

      // 🔍 Text Search
      $('#search').on('keyup', function () {
        table.search(this.value).draw();
      });

      // 🔹 Package Filter
      $('#packages').on('change', function () {
        const selectedPackage = $(this).val();
        table.column(3).search(selectedPackage || '').draw();
      });

      // 📅 Date Picker for FlightStartDate
      $("#FlightStartDate").datepicker({
        dateFormat: "yy-mm-dd",
        showAnim: "fadeIn",
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2100",
        appendTo: "body",
        beforeShow: function (input, inst) {
          setTimeout(function () {
            inst.dpDiv.css({
              top: $(input).offset().top + $(input).outerHeight(),
              left: $(input).offset().left
            });
          }, 0);
        },
        onSelect: function (dateText) {
          console.log("FlightStartDate Selected:", dateText);
          table.column(1).search(dateText || '').draw();
        }
      });

      // 🔹 Flight Date Filter on Change
      $('#FlightStartDate').on('change', function () {
        const selectedFlightDate = $(this).val();
        console.log("Flight Date Filter:", selectedFlightDate);
        table.column(1).search(selectedFlightDate || '').draw();
      });

      // 🔄 Clear All Filters
      $('#clearSorting').on('click', function () {
        $('#search').val('');
        table.search('').draw();

        $('#packages').val('All').change();

        $('#FlightStartDate').datepicker("setDate", null);
        table.column(1).search('').draw();

        $("#priceRange").slider("values", [0, 10000]);
        $("#min_price").val(0);
        $("#max_price").val(10000);
        table.draw();
      });
    });
  </script>

</body>

</html>