<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require "../conn.php"; // Move up to the parent directory
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Dashboard</title>
    <?php include '../Employee Section/includes/emp-head.php'?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
    
</head>
<body>


<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container bg-body">
  <?php include '../Employee Section/includes/emp-navbar.php' ?>

  <div class="main-content">
    <!-- Cards Count 1st Row -->
    <div class="counts-wrapper">
      <!-- CARD 1 - Current Transactions -->
      <div class="card border-0">
        <div class="header">
          <h6 class="text-secondary fw-600">Current Transaction</h6>
        </div>
    
        <div class="card-content px-3">
          <!-- Total and Confirmed Transaction Count -->
          <div class="row">
            <!-- Total Transaction Count -->
            <div class="col-md-5 d-flex flex-row">
              <div class="card-icon icon-blue">
                <i class="fas fa-calendar-alt"></i> 
              </div>
              <div class="side-content d-flex flex-column">
                <?php
                  // Assuming you already have a connection to your database
                  $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking WHERE MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                              AND YEAR(bookingDate) = YEAR(CURRENT_DATE())";
                  $result = mysqli_query($conn, $totalTransactionsQuery);

                  if ($result) 
                  {
                    $row = mysqli_fetch_assoc($result);
                    $totalTransactions = $row['total'];
                  } 
                  else 
                  {
                    $totalTransactions = 0; // default to 0 if query fails
                  }
                ?>
                <h5><?php echo $totalTransactions;?></h5>
                <p>TOTAL TRANSACTIONS</p>
              </div>
            </div>
       
            <!-- Confirmed Transaction Count -->
            <div class="col-md-5 d-flex flex-row">
              <div class="card-icon icon-green">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="side-content d-flex flex-column">
                <?php
                  // Assuming you already have a connection to your database
                  $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking WHERE MONTH(bookingDate) = MONTH(CURRENT_DATE()) AND YEAR(bookingDate) = YEAR(CURRENT_DATE()) AND status = 'Confirmed'";
                  $result = mysqli_query($conn, $totalTransactionsQuery);

                  if ($result) 
                  {
                    $row = mysqli_fetch_assoc($result);
                    $totalTransactions = $row['total'];
                  } 
                  else 
                  {
                    $totalTransactions = 0; // default to 0 if query fails
                  }
                ?>
                <h5><?php echo $totalTransactions; ?></h5>
                <p>CONFIRMED</p>
              </div>
            </div>
          </div>

          <!-- Pending, and Cancelled Transaction Count -->
          <div class="row">
            <!-- Pending Transaction Count -->
            <div class="col-md-5 d-flex flex-row">
              <div class="card-icon icon-yellow">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <div class="side-content d-flex flex-column">
                <?php
                  // Assuming you already have a connection to your database
                  $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking WHERE MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                              AND YEAR(bookingDate) = YEAR(CURRENT_DATE()) AND status = 'Pending'";
                  $result = mysqli_query($conn, $totalTransactionsQuery);

                  if ($result) 
                  {
                    $row = mysqli_fetch_assoc($result);
                    $totalTransactions = $row['total'];
                  } 
                  else 
                  {
                    $totalTransactions = 0; // default to 0 if query fails
                  }
                ?>
                <h5><?php echo $totalTransactions; ?></h5>
                <p>PENDING</p>
              </div>
            </div>
      
            <!-- Cancelled Transaction Count -->
            <div class="col-md-5 d-flex flex-row">
              <div class="card-icon icon-red">
                <i class="fas fa-times-circle"></i>
              </div>
              <div class="side-content d-flex flex-column">
                <?php
                  // Assuming you already have a connection to your database
                  $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking WHERE MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                              AND YEAR(bookingDate) = YEAR(CURRENT_DATE()) AND status = 'Cancelled'";
                  $result = mysqli_query($conn, $totalTransactionsQuery);

                  if ($result) 
                  {
                    $row = mysqli_fetch_assoc($result);
                    $totalTransactions = $row['total'];
                  } 
                  else 
                  {
                    $totalTransactions = 0; // default to 0 if query fails
                  }
                ?>
                <h5><?php echo $totalTransactions; ?></h5>
                <p>CANCELLED</p>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- CARD 2 - On Due -->
      <div class="card border-0">
        <div class="header">
          <h6 class="text-secondary fw-600">On Due</h6>
        </div>

        <div class="card-content px-3">
          <!-- 5 Days and 10 Days Due Count -->
          <div class="row">
            <!-- 5 Days Due Count -->
            <div class="col-md-5 d-flex flex-row">
              <div class="card-icon icon-blue">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div class="side-content d-flex flex-column">
                <?php
                  // Assuming $conn is your database connection
                  $days5Query = "SELECT COUNT(*) AS `bookingsDueIn5Days` FROM booking b 
                                  JOIN flight f ON b.flightId = f.flightId
                                  LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                    AS totalPaid FROM payment GROUP BY transactNo) p ON b.transactNo = p.transactNo
                                WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) <= 5 AND DATEDIFF(f.flightDepartureDate, CURDATE()) >= 0
                                  AND (b.totalPrice > IFNULL(p.totalPaid, 0)) and b.status='Confirmed'";

                  $result = $conn->query($days5Query);

                  // Check if the query returned a result
                  if ($result->num_rows > 0) 
                  {
                    $row = $result->fetch_assoc();
                    $bookingsDueIn5Days = $row['bookingsDueIn5Days'];
                  } 
                  else 
                  {
                    $bookingsDueIn5Days = 0;  // Default to 0 if no records found
                  }
                ?>
                <h5><?php echo $bookingsDueIn5Days; ?></h5>
                <p>5 DAYS</p>
              </div>
            </div>
   
            <!-- 10 Days Due Count -->
            <div class="col-md-5 d-flex flex-row">
              <div class="card-icon icon-green">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="side-content d-flex flex-column">
                <?php
                  // Assuming $conn is your database connection
                  $days10Query = "SELECT COUNT(*) AS bookingsDueIn10Days FROM booking b
                                    JOIN flight f ON b.flightId = f.flightId
                                    LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                    AS totalPaid FROM payment GROUP BY transactNo) p ON b.transactNo = p.transactNo
                                  WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 6 AND 10
                                    AND (b.totalPrice > IFNULL(p.totalPaid, 0)) and b.status='Confirmed'";

                  $result = $conn->query($days10Query);

                  // Check if the query returned a result
                  if ($result->num_rows > 0) 
                  {
                    $row = $result->fetch_assoc();
                    $bookingsDueIn10Days = $row['bookingsDueIn10Days'];
                  } 
                  else 
                  {
                    $bookingsDueIn10Days = 0;  // Default to 0 if no records found
                  }
                ?>
                <h5><?php echo $bookingsDueIn10Days; ?></h5>
                <p>10 DAYS</p>
              </div>
            </div>
          </div>

          <!-- 20 Days and 30 Days Due Count -->
          <div class="row">
            <!-- 20 Days Due Count -->
            <div class="col-md-5 d-flex flex-row">
              <div class="card-icon icon-yellow">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <div class="side-content d-flex flex-column">
                <?php
                  // Assuming $conn is your database connection
                  $days20Query = "SELECT COUNT(*) AS `bookingsDueIn20Days` FROM booking b 
                                  JOIN flight f ON b.flightId = f.flightId
                                  LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                            AS totalPaid FROM payment GROUP BY transactNo) p 
                                  ON b.transactNo = p.transactNo
                                  WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 10 AND 20
                                    AND (b.totalPrice > IFNULL(p.totalPaid, 0)) and b.status='Confirmed'";

                  $result = $conn->query($days20Query);

                  // Check if the query returned a result
                  if ($result->num_rows > 0) 
                  {
                    $row = $result->fetch_assoc();
                    $bookingsDueIn20Days = $row['bookingsDueIn20Days'];
                  } 
                  else 
                  {
                    $bookingsDueIn20Days = 0;  // Default to 0 if no records found
                  }
                ?>
                <h5><?php echo $bookingsDueIn20Days; ?></h5>
                <p>15 DAYS</p>
              </div>
            </div>
      
            <!-- 30 Days Due Count -->
            <div class="col-md-5 d-flex flex-row">
              <div class="card-icon icon-red">
                <i class="fas fa-times-circle"></i>
              </div>
              <div class="side-content d-flex flex-column">
                <?php
                  // Assuming $conn is your database connection
                  $days30Query = "SELECT COUNT(*) AS `bookingsDueIn30Days` FROM booking b 
                                    JOIN flight f ON b.flightId = f.flightId
                                    LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                    AS totalPaid FROM payment GROUP BY transactNo) p ON b.transactNo = p.transactNo
                                  WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 20 AND 30
                                    AND (b.totalPrice > IFNULL(p.totalPaid, 0)) AND b.status = 'Confirmed'";

                  $result = $conn->query($days30Query);

                  // Check if the query returned a result
                  if ($result->num_rows > 0) 
                  {
                    $row = $result->fetch_assoc();
                    $bookingsDueIn30Days = $row['bookingsDueIn30Days'];
                  } 
                  else 
                  {
                    $bookingsDueIn30Days = 0;  // Default to 0 if no records found
                  }
                ?>
                <h5><?php echo $bookingsDueIn30Days; ?></h5>
                <p>30 DAYS</p>
              </div>
            </div>
          </div>
      
        </div>
      </div>

      <!-- CARD 3 - Total Payment -->
      <div class="card border-0" >
        <div class="header">
          <h6 class="text-secondary fw-600">Total Sales</h6>
        </div>

        <?php
          // Query for total payments in the past month
          $pastMonthQuery = "SELECT IFNULL(SUM(amount), 0) AS totalPastMonth 
          FROM payment WHERE paymentStatus = 'Approved' 
          AND MONTH(paymentDate) = MONTH(CURDATE() - INTERVAL 1 MONTH)
          AND YEAR(paymentDate) = YEAR(CURDATE() - INTERVAL 1 MONTH)";

          $pastMonthResult = $conn->query($pastMonthQuery);
          $pastMonthTotal = ($pastMonthResult->num_rows > 0) ? number_format($pastMonthResult->fetch_assoc()['totalPastMonth'], 2) : '0.00';
        ?>

        <?php
          // Query for total payments in the current month
          $currentMonthQuery = "SELECT IFNULL(SUM(amount), 0) AS totalCurrentMonth 
          FROM payment WHERE paymentStatus = 'Approved' 
          AND MONTH(paymentDate) = MONTH(CURDATE()) 
          AND YEAR(paymentDate) = YEAR(CURDATE())";

          $currentMonthResult = $conn->query($currentMonthQuery);
          $currentMonthTotal = ($currentMonthResult->num_rows > 0) ? number_format($currentMonthResult->fetch_assoc()['totalCurrentMonth'], 2) : 0;
        ?>


        <div class="card-content px-3">
        <div class="row">
              <div class="col-md-5 d-flex flex-row total-sales">
                <div class="card-icon icon-blue">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="side-content d-flex flex-column">
                 
                  <h5 class="month-sales">₱ <?php echo $currentMonthTotal; ?></h5>
                  <p>CURRENT MONTH</p>
                </div>
              </div>

            
            </div>

            <div class="row">
              <div class="col-md-5 d-flex flex-row total-sales">
                <div class="card-icon icon-red">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="side-content d-flex flex-column">
                  
                  <h5 class="month-sales">₱ <?php echo $pastMonthTotal; ?></h5>
                  <p>PAST MONTH</p>

                </div>
              </div>
            </div>
            
          
        </div>
      </div>

      <!-- CARD 4 - Money Convertion -->
      <div class="card border-0">
        <div class="header d-flex justify-content-between align-items-center mb-2">
          <h6 class="text-secondary">Daily Currency Conversion</h6>
          <a href="" style="font-size: 12px; text-decoration: none;">View History</a>
        </div>

        <div class="card-body-currency">
          <div class="currency-cards">
            <div class="currency-card">
              <div class="flag-icon-wrapper">
                <img src="../assets/images/Flags/english-flag.png" alt="">
                <h6>USD</h6>
                <h6>$1</h6>
              </div>
            </div>

            <div class="icon-wrapper">
              <i class="fas fa-exchange-alt"></i>
            </div>

            <div class="currency-card">
              <div class="flag-icon-wrapper">
                <img src="../assets/images/Flags/philippines (2).png" alt="">
                <h6>PHP</h6>

                <h6>$1</h6>
              </div>
            </div>

            <div class="currency-card">
              <div class="flag-icon-wrapper">
                <img src="../assets/images/Flags/korean-flag.png" alt="">
                <h6>KOR</h6>

                <h6>$1</h6>
              </div>
            </div>

            <div class="currency-card">
              <div class="flag-icon-wrapper">
                <img src="../assets/images/Flags/european.png" alt="">
                <h6>EUR</h6>

                <h6>$1</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
 
    <div class="navTabs-wrapper">
      <ul class="nav nav-pills my-3" id="pills-tab" role="tablist">
       <li class="nav-item" role="presentation">
          <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Flight Seat Tracker</button>
        </li>

       <li class="nav-item" role="presentation">
         <button class="nav-link " id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Payment and Requests</button>
       </li>
       
       <!-- <li class="nav-item" role="presentation">
         <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</button>
       </li>
       <li class="nav-item" role="presentation">
         <button class="nav-link" id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false" disabled>Disabled</button>
       </li> -->
     </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">

    <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
        <div class="main-table-wrapper-one">
          <div class="table-info-container">
            <div class="header p-3 d-flex flex-row justify-content-between align-items-center">

              <div class="start-section">
                <h6 class="">Flight Seat Tracker</h6>
              </div>

              <div class="end-section d-flex align-items-center gap-3">
                <div class="legend-guides d-flex flex-row gap-3">
                  <div class="legend-item-wrapper">
                    <div class="legend-item">
                      <span class="color-circle" style="background-color: #ADD8E6;"></span> <!-- Red -->
                      <h6 class="legend-text">A01</h6>
                    </div>
                  </div>
                  <div class="legend-item-wrapper">
                    <div class="legend-item">
                      <span class="color-circle" style="background-color: #98FB98;"></span> <!-- Green -->
                      <h6 class="legend-text">A02</h6>
                    </div>
                  </div>
                  <div class="legend-item-wrapper">
                    <div class="legend-item">
                      <span class="color-circle" style="background-color: #FFFFCC; color: black;"></span> <!-- Blue -->
                      <h6 class="legend-text">A03</h6>
                    </div>
                  </div>
                  <div class="legend-item-wrapper">
                    <div class="legend-item">
                      <span class="color-circle" style="background-color: #E6E6FA;"></span> <!-- Yellow -->
                      <h6 class="legend-text">A04</h6>
                    </div>
                  </div>
                  <div class="legend-item-wrapper">
                    <div class="legend-item">
                      <span class="color-circle" style="background-color: #FFDAB9;"></span> <!-- Magenta -->
                      <h6 class="legend-text">A05</h6>
                    </div>
                  </div>
                </div>

                <div class="refresh-button-wrapper">
                  <button class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></button>
                </div>
              </div>

            </div>

            <div class="info-table-container">
              <table class="info-table">
                <thead class="border-2">
                  <tr>
                    <th rowspan="2">TEAM OP</th>
                    <th rowspan="2">ORIGIN</th>
                    <th colspan="2">FLIGHT DATE</th> <!-- Flight Date columns -->
                    <th rowspan="2">FLIGHT SEAT</th>
                    <th rowspan="2" style="font-size: 10px;">AVAILABLE SEATS</th>
                    <th rowspan="2" style="font-size: 10px;">ADDITIONAL SEATS</th>
                    <th rowspan="2">AIR + LAND</th>
                    <th rowspan="2">LAND ONLY</th>
                    <th rowspan="2">WHOLESALE PRICE</th>
                    <th rowspan="2">RETAIL PRICE</th>
                    <th rowspan="2">LAND ARRANGEMENT</th>

                    <!-- Dynamic headers for agent columns -->
                    <?php
                    // Define an array of colors to style the <th> elements
                    $colors = ['#ADD8E6', '#98FB98', '#FFFFCC', '#E6E6FA', '#FFDAB9']; // Extend this array as needed

                    // Fetch agent column headers dynamically
                    $sql = "SELECT DISTINCT agentCode FROM agent WHERE agentCode IS NOT NULL AND agentCode != ''";
                    $result = $conn->query($sql);

                    // Initialize a counter for cycling through the color array
                    $colorIndex = 0;

                    while ($row = $result->fetch_assoc()) 
                    {
                        // Get the current color based on the index and loop through the color array
                        $color = $colors[$colorIndex % count($colors)];
                        
                        // Output the <th> element with the inline style for background color
                        echo '<th colspan="2" data-bs-toggle="tooltip" title="' . $row['agentCode'] . '" style="background-color: ' . $color . ';">' . $row['agentCode'] . '</th>';
                        
                        // Increment the color index for the next iteration
                        $colorIndex++;
                    }
                    ?>


                  </tr>
                  <tr style="top: -8px">
                    <th>START</th>
                    <th>END</th>
                    <!-- A1, A2, A3, A4, A5, A6, A7 Sub Headers -->
                    <!-- Dynamic sub-headers for agent columns -->
                    <?php
                    // Define the same array of colors to style the <th> elements
                    $colors = ['#ADD8E6', '#98FB98', '#FFFFCC', '#E6E6FA', '#FFDAB9']; // Extend this array as needed

                    // Fetch agent column headers dynamically
                    $sql = "SELECT DISTINCT agentCode FROM agent WHERE agentCode IS NOT NULL AND agentCode != ''";
                    $result = $conn->query($sql);

                    // Initialize a counter for cycling through the color array
                    $colorIndex = 0;

                    while ($row = $result->fetch_assoc()) 
                    {
                        // Get the current color based on the index and loop through the color array
                        $color = $colors[$colorIndex % count($colors)];
                        
                        // Output the <th> elements with the inline style for background color
                        echo '<th style="background-color: ' . $color . ';">A.L</th>';
                        echo '<th style="background-color: ' . $color . ';">L.O</th>';
                        
                        // Increment the color index for the next iteration
                        $colorIndex++;
                    }
                    ?>

                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT DISTINCT agentCode FROM agent WHERE agentCode IS NOT NULL AND agentCode != ''";
                    $result = $conn->query($sql);

                    $agentColumns = '';
                    while ($row = $result->fetch_assoc()) 
                    {
                      $agentColumns .= 
                          'SUM(CASE WHEN b.agentCode = "' . $row['agentCode'] . '" AND b.bookingType = "Package" and b.status = "Confirmed" THEN b.pax ELSE 0 END) AS `' . $row['agentCode'] . '_AL`, ' .
                          'SUM(CASE WHEN b.agentCode = "' . $row['agentCode'] . '" AND b.bookingType = "Land" and b.status = "Confirmed" THEN b.pax ELSE 0 END) AS `' . $row['agentCode'] . '_LO`, ';
                    }

             
                    $agentColumns = rtrim($agentColumns, ', ');

                    $sql = "
                    SELECT CONCAT(e.lName, ', ', e.fName, 
                            IF(e.mName IS NOT NULL AND e.mName != '', CONCAT(' ', LEFT(e.mName, 1)), '')) AS TeamOP,
                        f.origin, 
                        f.flightDepartureDate AS Start, 
                        f.returnDepartureDate AS End, 
                        f.availSeats AS FlightSeat, 
                        GREATEST(
                            (f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' AND b.bookingType = 'Package' 
                            THEN b.pax ELSE 0 END), 0)), 0) AS AvailSeats, 
                        IF(
                            (f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' AND b.bookingType = 'Package' 
                            THEN b.pax ELSE 0 END), 0)) < 0, 
                            ABS(f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' AND b.bookingType = 'Package' 
                            THEN b.pax ELSE 0 END), 0)), 
                            0) AS AdditionalSeats,
                        SUM(CASE WHEN b.bookingType = 'Package' AND b.status = 'Confirmed' THEN b.pax ELSE 0 END) AS `Air+Land`,
                        SUM(CASE WHEN b.bookingType = 'Land' AND b.status = 'Confirmed' THEN b.pax ELSE 0 END) AS `LandOnly`,
                        f.wholesalePrice AS WholesalePrice, 
                        f.flightPrice AS RetailPrice, 
                        p.packagePrice AS LandArrangement, 
                        $agentColumns
                    FROM 
                        employee e 
                    JOIN 
                        flight f ON f.employeeId = e.employeeId
                    LEFT JOIN 
                        booking b ON b.flightId = f.flightId
                    LEFT JOIN 
                        package p ON f.packageId = p.packageId
                    WHERE 
                        f.flightDepartureDate >= CURDATE()
                    GROUP BY 
                        f.flightId, e.lName, e.fName, e.mName, f.origin, f.flightDepartureDate, f.returnDepartureDate, f.availSeats, 
                        f.wholesalePrice, f.flightPrice, p.packagePrice
                    ORDER BY 
                        f.flightDepartureDate";

                    // Step 3: Execute the query
                    $result = $conn->query($sql);

                    // Step 4: Display the results in HTML table
                    if ($result->num_rows > 0) 
                    {
                      while ($row = $result->fetch_assoc()) 
                      {
                        echo '<tr>';
                        echo '<td class="fw-bold">' . $row['TeamOP'] . '</td>';
                        echo '<td>' . $row['origin'] . '</td>';
                        echo '<td>' . $row['Start'] . '</td>';
                        echo '<td>' . $row['End'] . '</td>';
                        echo '<td>' . $row['FlightSeat'] . '</td>';
                        echo '<td>' . $row['AvailSeats'] . '</td>';
                        echo '<td>' . $row['AdditionalSeats'] . '</td>';
                        echo '<td>' . $row['Air+Land'] . '</td>';
                        echo '<td>' . $row['LandOnly'] . '</td>';
                        echo '<td>₱ ' . number_format($row['WholesalePrice'], 2) . '</td>';
                        echo '<td>₱ ' . number_format($row['RetailPrice'], 2) . '</td>';
                        echo '<td>₱ ' . number_format($row['LandArrangement'], 2) . '</td>';


                        // Dynamically populate agent columns
                        // Dynamically populate agent columns
                        foreach ($row as $key => $value) 
                        {
                          $colors = ['#ADD8E6', '#98FB98', '#FFFFCC', '#E6E6FA', '#FFDAB9']; // Color array
                          if (strpos($key, '_AL') !== false || strpos($key, '_LO') !== false) 
                          {
                              // Determine font weight
                              $fontWeight = ($value >= 1) ? 'bolder' : 'normal';

                              // Get the background color by cycling through the color array
                              $colorIndex = array_search($key, array_keys($row)) % count($colors); // Cycle through the color array
                              $backgroundColor = $colors[$colorIndex];

                              echo '<td style="font-weight: ' . $fontWeight . '; background-color: ' . $backgroundColor . ';">' . $value . '</td>';
                          }
                        }
                        echo '</tr>';
                      }
                    } 
                    else 
                    {
                      echo "No records found.";
                    }
                  ?>
                </tbody>
              </table>
          
            </div>
          </div>
        </div>            
      </div>

      <div class="tab-pane fade " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
        <div class="header-wrapper">
          <div class="price-table-wrapper">
            <div class="header p-3">
            <h6 class="text-secondary">Price</h6>
            </div>

            <div class="price-table-container">
              <table class="price-table">
                <thead>
                  <tr>
                  
                  </tr>
                </thead>
                <tbody>
                
                </tbody>
              </table>
            </div>
          </div>

          <div class="request-wrapper">
            <div class="header p-3">
              <h6 class="text-secondary">Requests</h6>
            </div>

            <div class="request-table-container">
              <table class="request-table">
                <thead>
                  <tr>
                    <th>AGENT NAME</th>
                    <th>REQUEST</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sql1 = "SELECT 
                                  r.transactNo AS `T.N`,
                                  c.concernTitle AS `Request`,
                                  DATE_FORMAT(r.requestDate, '%m.%d.%Y') AS `Date`,
                                  r.requestStatus, b.agentId,
                                  CONCAT(a.lName, ', ', a.fName, 
                                      IF(a.mName IS NOT NULL AND a.mName != '', CONCAT(' ', LEFT(a.mName, 1)), '')) AS agentName
                              FROM 
                                  request r
                              JOIN 
                                  booking b ON r.transactNo = b.transactNo
                              JOIN 
                                  concern c ON r.concernId = c.concernId
                              JOIN
                                  agent a ON b.agentId = a.agentId
                              WHERE 
                                r.requestStatus = 'Submitted'
                              ORDER BY 
                                  r.requestDate DESC";  // Order by request date

                    $res1 = $conn->query($sql1);
                      
                    if ($res1->num_rows > 0) 
                    {
                      while ($row = $res1->fetch_assoc()) {
                        
                        $statusClass = '';
                        switch ($row['requestStatus']) {
                            case 'Confirmed':
                                $statusClass = 'badge bg-success'; // Green pill for "Approved"
                                break;
                            case 'Pending':
                                $statusClass = 'badge bg-primary'; // Yellow pill for "Pending"
                                break;
                            case 'Rejected':
                                $statusClass = 'badge bg-danger'; // Red pill for "Rejected"
                                break;
                            default:
                                $statusClass = 'badge bg-secondary'; // Grey pill for unknown statuses
                                break;
                        }
                    
                        // Echo table row with dynamically styled pills
                        echo "<tr>
                                <td>{$row['agentName']}</td>
                                <td>{$row['Request']}</td>
                                <td>{$row['Date']}</td>
                                <td><span class='{$statusClass}'>{$row['requestStatus']}</span></td>
                              </tr>";
                    }
                  
                    } 
                    else 
                    {
                      echo "<tr><td colspan='6' style='text-align: center; font-size: 10px;'>NO CURRENT REQUEST AS OF THE MOMENT</td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="payment-wrapper">
            <div class="header p-3">
              <h6 class="text-secondary">Payment</h6>
            </div>

            <div class="payment-table-container px-3">
              <table class="payment-table">
                <thead>
                  <tr>
                    <th>AGENT NAME</th>
                    <th>PAYMENT TITLE</th>
                    <th>PAYMENT TYPE</th>
                    <th>PAYMENT AMOUNT</th>
                    <th>DATE</th> 
                    <th>STATUS</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sql2 = "SELECT 
                              p.transactNo AS `Transaction No`,
                              p.paymentTitle AS `Payment Title`,
                              CONCAT(FORMAT(p.amount, 2)) AS `Amount`,  -- Format the amount as a currency with two decimal places
                              DATE_FORMAT(p.paymentDate, '%m.%d.%Y') AS `Date`,  -- Format the date as specified
                              p.paymentType AS `Payment Type`,
                              p.paymentStatus, b.agentId,
                              CONCAT(a.lName, ', ', a.fName, 
                                      IF(a.mName IS NOT NULL AND a.mName != '', CONCAT(' ', LEFT(a.mName, 1)), '')) AS agentName
                            FROM 
                              payment p
                            JOIN 
                              booking b ON p.transactNo = b.transactNo
                            JOIN
                                agent a ON b.agentId = a.agentId
                            WHERE 
                              p.paymentStatus = 'Submitted'
                            ORDER BY 
                              p.paymentDate DESC";  // Order by payment date

                    $res2 = $conn->query($sql2);
                    
                    if ($res2->num_rows > 0) {
                      while ($row = $res2->fetch_assoc()) {
                        // Map paymentStatus to Bootstrap pill classes
                        $statusClass = '';
                        switch ($row['paymentStatus']) {
                            case 'Approved':
                                $statusClass = 'badge bg-success text-light'; // Green pill for "Paid"
                                break;
                            case 'Pending':
                                $statusClass = 'badge bg-warning text-dark'; // Yellow pill for "Pending"
                                break;
                            case 'Submitted':
                                $statusClass = 'badge bg-primary text-light'; // Red pill for "Failed"
                                break;
                            default:
                                $statusClass = 'badge bg-secondary'; // Grey pill for unknown statuses
                                break;
                        }
                    
                        // Echo table row with dynamically styled pills
                        echo "<tr>
                                <td>{$row['agentName']}</td>
                                <td>{$row['Payment Title']}</td>
                                <td>{$row['Payment Type']}</td>
                                <td>₱ {$row['Amount']}</td>
                                <td>{$row['Date']}</td>
                                <td><span class='{$statusClass}'>{$row['paymentStatus']}</span></td>
                              </tr>";
                    }
                  
                    } else {  
                      echo "<tr><td colspan='12' style='text-align: center; font-size: 10px;'>NO CURRENT PAYMENTS AS OF THE MOMENT</td></tr>";
                    }
                  ?>
                </tbody>
              </table>


            </div>
            
          </div>
        </div>
      </div>

      <!-- Flights Table -->
      

      <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">...</div>

     <div class="tab-pane fade" id="pills-disabled" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">...</div>
    </div>

  </div>
</div>


<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
