<?php
  session_start();
  require "../conn.php"; // Move up to the parent directory

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <?php include '../Employee Section/includes/emp-head.php'?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container bg-body">
  <?php include '../Employee Section/includes/emp-navbar.php' ?>

  <?php include '../Agent Section/functions/exchange-rate.php'?>

  <div class="main-content">

    <!-- Cards Count 1st Row -->
    <div class="counts-wrapper">

      <!-- CARD 1 - Current Transactions -->
      <div class="card border-0">
        <div class="header">
          <h6 class="white-pill">Current Transaction</h6>
        </div>
    
        <div class="card-content">
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
          <h6 class="white-pill">On Due</h6>
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
          <h6 class="white-pill">Total Sales</h6>
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

      <!-- CARD 4 -->
      <div class="card border-0">
          <div class="header d-flex justify-content-between align-items-center">
            <h6 class="white-pill">Daily Currency Conversion</h6>
            <a href="" class="pill-button">View History</a>
          </div>

          <div class="card-body-currency mt-2">
            <div class="currency-cards">

              <!-- USD CARD -->
              <div class="currency-card">
                <div class="flag-icon-wrapper">
                  <img src="../assets/Flags/english-flag.png" alt="">
                  <h6 class="mt-2">USD</h6>
                  <div class="currency-text-wrapper">
                    <h5>$ 1</h5>
                  </div>
                </div>
              </div>

              <div class="icon-wrapper mx-2">
                <i class="fas fa-exchange-alt"></i>
              </div>

              <!-- PHP CARD -->
              <div class="currency-card">
                <div class="flag-icon-wrapper">
                  <img src="../assets/Flags/philippines (2).png" alt="">
                  <h6 class="mt-2">PHP</h6>
                  <div class="currency-text-wrapper">
                   <h5>₱ <?php echo number_format($usd_to_php, 2); ?></h5>
                  </div>
                </div>
              </div>

              <!-- KOR CARD -->
              <div class="currency-card">
                <div class="flag-icon-wrapper">
                  <img src="../assets/Flags/korean-flag.png" alt="">
                  <h6 class="mt-2">KOR</h6>
                  <div class="currency-text-wrapper">
                    <h5>₩ <?php echo number_format($usd_to_krw, 0); ?></h5>
                  </div>
                </div>
              </div>

            </div> 
          </div>
      </div>

    </div>
 
    <div class="navTabs-wrapper">
      <ul class="nav nav-pills" id="pills-tab" role="tablist">
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

    <!-- Flight Seat Tracker Tab -->
    <div class="tab-content" id="pills-tabContent">

      <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">

        <!-- Flight Seat Tracker Table -->
        <div class="info-table-container">
          <table class="info-table" id="info-table">
            <thead>
              <tr>
                <th rowspan="2"></th>
                <th rowspan="2">TEAM OP</th>
                <th rowspan="2">ORIGIN</th>
                <th colspan="2">FLIGHT DATE</th> <!-- Flight Date columns -->
                <th rowspan="2" style="font-size: 10px;">AVAILABLE SEATS</th>
                <th rowspan="2" style="font-size: 10px;">ADDITIONAL SEATS</th>
                <th rowspan="2">AIR + LAND</th>
                <th rowspan="2">LAND ONLY</th>
                <th rowspan="2">WHOLESALE PRICE</th>
                <th rowspan="2">RETAIL PRICE</th>
                <th rowspan="2" style="font-size: 10px; padding: 0px 5px">LAND ARRANGEMENT PRICE</th>

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
              <tr style="top: -10px">
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
                $sql = "SELECT DISTINCT a.agentCode AS agentCode, a.agentType AS agentType
                        FROM agent a
                        WHERE a.agentCode IS NOT NULL AND a.agentCode != ''";
                $result = $conn->query($sql);
                
                $agentColumns = '';
                while ($row = $result->fetch_assoc()) 
                {
                  $agentCode = $row['agentCode'];
                  // Removed agentRole filter, now summing pax per agentCode without multiplying by the number of agents with the same code
                  $agentColumns .= "
                      SUM(CASE WHEN b.bookingType = 'Package' AND b.status = 'Confirmed' 
                              AND b.agentCode = '$agentCode' AND a.agentType = 'Retailer' 
                              THEN b.pax ELSE 0 END) AS `{$agentCode}_AL`,
                      SUM(CASE WHEN b.bookingType = 'Package' AND b.status = 'Confirmed' 
                              AND b.agentCode = '$agentCode' AND a.agentType = 'Wholeseller' 
                              THEN b.pax ELSE 0 END) AS `{$agentCode}_LO`, ";
                }
                
                // Trim the trailing comma from the dynamically generated columns
                $agentColumns = rtrim($agentColumns, ', ');
                
                // Main query
                $sql = "SELECT CONCAT(e.lName, ', ', e.fName, 
                              IF(e.mName IS NOT NULL AND e.mName != '', CONCAT(' ', LEFT(e.mName, 1)), '')) AS TeamOP,
                              f.origin, f.flightDepartureDate AS Start, f.returnDepartureDate AS End, 
                              f.availSeats AS FlightSeat, 
                              GREATEST(f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' AND b.bookingType = 'Package' 
                              THEN b.pax ELSE 0 END), 0), 0) AS AvailSeats, 
                              IF(
                                  (f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' AND b.bookingType = 'Package'
                                  THEN b.pax ELSE 0 END), 0)) < 0, 
                                  ABS(f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' AND b.bookingType = 'Package'
                                  THEN b.pax ELSE 0 END), 0)), 0) AS AdditionalSeats,
                              SUM(CASE WHEN b.bookingType = 'Package' AND b.status = 'Confirmed' AND a.agentType = 'Retailer' 
                                      THEN b.pax ELSE 0 END) AS `Air+Land`,
                              SUM(CASE WHEN b.bookingType = 'Package' AND b.status = 'Confirmed' AND a.agentType = 'Wholeseller' 
                                      THEN b.pax ELSE 0 END) AS `LandOnly`,
                              f.wholesalePrice AS WholesalePrice, f.flightPrice AS RetailPrice, p.packagePrice AS LandArrangement,
                              $agentColumns
                          FROM 
                              employee e
                          JOIN 
                              flight f ON f.employeeId = e.employeeId
                          LEFT JOIN 
                              booking b ON b.flightId = f.flightId
                          LEFT JOIN 
                              package p ON f.packageId = p.packageId
                          LEFT JOIN 
                              agent a ON b.agentId = a.agentId
                          WHERE 
                              f.flightDepartureDate >= CURDATE()
                          GROUP BY 
                              f.flightId, f.origin, f.flightDepartureDate, f.returnDepartureDate, f.availSeats, 
                              f.wholesalePrice, f.flightPrice, p.packagePrice
                          ORDER BY 
                              f.flightDepartureDate";

                // Step 3: Execute the query
                $result = $conn->query($sql);

                // Step 4: Display the results in HTML table

                // class="form-check-input"
                if ($result->num_rows > 0) 
                {
                  while ($row = $result->fetch_assoc()) 
                  {

                    $colorMapping = [
                        "Heo, Vicky" => "#FFD700",  // Gold
                        "Kim, Gwen" => "#ADD8E6",   // Light Blue
                        "Sample, Dorothy" => "#98FB98", // Pale Green
                        "Lm, Anna" => "#FFB6C1",    // Light Pink
                        "Park, Lia" => "#E6E6FA",   // Lavender
                        "Testing, Pamela" => "#FFDAB9" // Peach
                    ];

                    $rowColor = isset($colorMapping[$row['TeamOP']]) ? $colorMapping[$row['TeamOP']] : "transparent"; // Default to transparent if not listed





                    echo '<tr>';
                    echo '<td class="fw-bold" style="font-size: 12x; background-color: ' . $rowColor . ';">
                            <input type="checkbox" class="row-checkbox">
                          </td>';
                    echo '<td class="" style="font-size: 12px; white-space: nowrap; background-color: ' . $rowColor . '; font-weight: bold;">' . $row['TeamOP'] . '</td>';
                    echo '<td>' . $row['origin'] . '</td>';
                    echo '<td>' . $row['Start'] . '</td>';
                    echo '<td>' . $row['End'] . '</td>';
                    echo '<td>' . $row['AvailSeats'] . '</td>';
                    echo '<td>' . $row['AdditionalSeats'] . '</td>';
                    echo '<td>' . $row['Air+Land'] . '</td>';
                    echo '<td>' . $row['LandOnly'] . '</td>';
                    echo '<td>₱ ' . number_format($row['WholesalePrice'], 2) . '</td>';
                    echo '<td>₱ ' . number_format($row['RetailPrice'], 2) . '</td>';
                    echo '<td>₱ ' . number_format($row['LandArrangement'], 2) . '</td>';

                    foreach ($row as $key => $value) {
                        $colors = ['#ADD8E6', '#98FB98', '#FFFFCC', '#E6E6FA', '#FFDAB9']; // Color array
                        if (strpos($key, '_AL') !== false || strpos($key, '_LO') !== false) {
                            $fontWeight = ($value >= 1) ? 'bolder' : 'normal';
                            $colorIndex = array_search($key, array_keys($row)) % count($colors);
                            $backgroundColor = $colors[$colorIndex];

                            echo '<td style="font-weight: ' . $fontWeight . '; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">' . $value . '</td>';
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

      <!-- Payment and Requests Table -->
      <div class="tab-pane fade " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

      <div class="tab-content">
        <div class="header-wrapper">
          <!-- Request Table -->
          <div class="request-wrapper">
            <div class="table-header">
              <h6 class="white-pill">Requests</h6>
            </div>

            <div class="request-table-container">
              <table class="table request-table">
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
                    $sql1 = "SELECT r.transactNo AS `T.N`, c.concernTitle AS `Request`, DATE_FORMAT(r.requestDate, '%m.%d.%Y') AS `Date`,
                                  r.requestStatus, b.agentCode, CONCAT(a.lName, ', ', a.fName, 
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

          <!-- Payment Table -->
          <div class="payment-wrapper">
            <div class="table-header">
              <h6 class="white-pill">Payment</h6>
            </div>

            <div class="payment-table-container">
              <table class="payment-table table ">
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
                    $sql2 = "SELECT p.transactNo AS `Transaction No`, p.paymentTitle AS `Payment Title`,
                              CONCAT(FORMAT(p.amount, 2)) AS `Amount`, DATE_FORMAT(p.paymentDate, '%m.%d.%Y') AS `Date`, p.paymentType AS `Payment Type`,
                              p.paymentStatus, b.agentCode, CONCAT(a.lName, ', ', a.fName, 
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

        <!-- Confirmed Transaction Tables -->
        <div class="confirm-container">
            <div class="table-header">
              <h6 class="white-pill">Confirmed Transactions</h6>
            </div>
                
            <div class="body">
              <div class="table-container confirm-table-container">
                <table class="confirm-table">
                  <thead>
                    <tr>
                      <th>TRANSACTION NO.</th>
                      <th>AGENT NAME</th>
                      <th>PACKAGE</th>
                      <th>FLIGHT DATE</th>
                      <th>TOTAL PAX.</th>
                      <th>BOOKING TYPE</th>
                      <th>STATUS</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $query1 = "SELECT b.*, f.flightDepartureDate AS Start, p.packageName,
                                        f.returnDepartureDate AS End, CONCAT(a.lName, ', ', a.fName, 
                                        IF(a.mName IS NOT NULL AND a.mName != '', CONCAT(' ', LEFT(a.mName, 1)), '')) AS agentName
                                  FROM booking b 
                                  JOIN agent a ON b.agentId = a.agentId
                                  JOIN flight f ON b.flightId = f.flightId
                                  JOIN package p ON b.packageId = p.packageId
                                  WHERE status = 'Confirmed'";

                      $result = $conn->query($query1);

                      // Check if the query returned any results
                      if ($result && $result->num_rows > 0) {
                        
                        while ($row = $result->fetch_assoc()) {
                 

                              $status = $row['status'];
                          
                              // Define the pill status class based on the status value
                              switch ($status) {
                                  case 'Confirmed':
                                      $pillClass = 'bg-success';
                                      break;
                                  case 'Cancelled':
                                      $pillClass = 'bg-danger';
                                      break;
                                  case 'Pending':
                                      $pillClass = 'bg-warning';
                                      break;
                                  case 'Rejected':
                                      $pillClass = 'bg-info';
                                      break;
                                  default:
                                      $pillClass = 'bg-secondary';
                                      break;
                              }
                          
                              // Generate the table row
                              echo "<tr>
                                      <td>{$row['transactNo']}</td>
                                      <td>{$row['agentName']}</td>
                                      <td>{$row['packageName']}</td>
                                      <td>{$row['Start']}</td>
                                      <td>{$row['pax']}</td>
                                      <td>{$row['bookingType']}</td>
                                      <td>
                                          <span class='badge $pillClass p-2'>{$status}</span>
                                      </td>
                                    </tr>";
                          }
                       
                          
                      } 
                      else 
                      {
                        // No records found
                        echo "<tr><td colspan='7'>No confirmed bookings found.</td></tr>";
                      }
                      
                      if ($result) {
                        $result->free();
                      }


                      $conn->close();
                    ?>
                  </tbody>
                </table>
              </div>
           

          </div>
        </div>

      </div>
    </div>


      <!-- <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0"></div>
      <div class="tab-pane fade" id="pills-disabled" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0"></div> -->
    
    </div>

  </div>
</div>


    <?php include '../Employee Section/includes/emp-scripts.php' ?>

    <script>
$(document).ready(function () {
    // Initialize DataTable for .info-table if not already initialized
    if (!$.fn.DataTable.isDataTable('.info-table')) {
        var table = $('.info-table').DataTable({
            autoWidth: false,
            scrollX: true, // Enable horizontal scrolling
            scrollY: "583px", // Enable vertical scrolling and set height
            paging: false, // Disable pagination
            searching: false, // Disable search
            info: false, // Disable info
            fixedColumns: {
                leftColumns: 12 // Freeze the first 11 columns
            },
            dom: 'rt<"bottom"flp>',
            ordering: false // Disable sorting on all columns
        });

        // Ensure uniform row height between frozen and non-frozen columns
        function syncRowHeights() {
            setTimeout(() => {
                $('.DTFC_Cloned tbody tr').each(function (index) {
                    let originalRow = $('.dataTable tbody tr').eq(index);
                    let clonedRow = $(this);
                    let originalHeight = originalRow.height();
                    clonedRow.height(originalHeight);
                });
            }, 50); // Allow DataTable rendering before adjusting height
        }

        // Call sync function after initialization
        syncRowHeights();

        // Re-adjust heights on window resize or table updates
        $(window).on('resize', syncRowHeights);
        $('.info-table').on('draw.dt', syncRowHeights);
    }

    // Prevent row selection when clicking on the checkbox
    $('.info-table tbody').on('click', 'input[type="checkbox"]', function (e) {
        e.stopPropagation(); // Stop event from propagating to row selection
    });

    // Apply the 'selected' class to rows in both tables when clicked (excluding checkboxes)
    // function selectRowInBothTables(index) {
    //     $('.info-table tbody tr, div.dataTables_wrapper tbody tr').removeClass('selected');
    //     $('.info-table tbody tr').eq(index).addClass('selected');
    //     $('div.dataTables_wrapper tbody tr').eq(index).addClass('selected');
    // }

    // Add event listener for row clicks in .info-table using event delegation
    $('.info-table').on('click', 'tbody tr', function (e) {
        if ($(e.target).is('input[type="checkbox"]')) return; // Ignore checkboxes
        const index = $(this).index();
        selectRowInBothTables(index);
    });

    // Add event listener for row clicks in div.dataTables_wrapper using event delegation
    $('div.dataTables_wrapper').on('click', 'tbody tr', function (e) {
        if ($(e.target).is('input[type="checkbox"]')) return;
        const index = $(this).index();
        selectRowInBothTables(index);
    });

    // Add custom CSS for the selected row
    $('<style>')
        .prop('type', 'text/css')
        .html(`
             .info-table tbody tr.selected, div.dataTables_wrapper tbody tr.selected {
                background-color: rgb(42, 204, 253) !important;
                color: black !important;
                font-weight: bold;
                // height: 20px !important;
                
            }

            /* Ensure uniform row height */
            // .dataTable tbody tr, 
            // .dataTables_scrollBody tbody tr {
            //     height: 20px !important;
            // }

            /* Adjust checkbox styling */
            // .dataTable tbody tr td input[type="checkbox"] {
            //     width: 10px;
            //     height: 10px;
            //     vertical-align: middle;
            // }
        `)
        .appendTo('head');
});



</script>





  </body>
</html>
