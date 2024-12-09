<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agent - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-dashboard.css?v=<?php echo time(); ?>">
</head>

<body>
  <?php include '../Agent Section/includes/sidebar.php' ?>

  <div class="main-content" id="mainContent">
    <?php include '../Agent Section/includes/navbar.php' ?>

    <div class="container-wrapper">
      <!-- Cards First Row -->
      <div class="counts-wrapper">
        <!-- CARD 1 Current Transaction Counts-->
        <div class="card border-0">
          <div class="header-counts">
            <h6 class="text-secondary white-pill">Current Transaction</h6>
          </div>
      
          <div class="card-content px-3">
            <!-- Total Transaction, and Completed Transaction -->
            <div class="row">
              <!-- Total Transaction Card -->
              <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-blue">
                  <i class="fas fa-calendar-alt"></i> 
                </div>
                <div class="side-content d-flex flex-column">
                  <?php
                    // Get session variables
                    $accountId = $_SESSION['agent_accountId'];
                    $agentId = $_SESSION['agent_agentId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Determine which query to run based on the agent's role
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId
                      $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                                WHERE accountId = '$accountId' AND MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                                AND YEAR(bookingDate) = YEAR(CURRENT_DATE())";
                    } 
                    else 
                    {
                      // Query for Head Agent, use agentCode
                      $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                                WHERE agentCode = '$agentCode' AND MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                                AND YEAR(bookingDate) = YEAR(CURRENT_DATE())";
                    }

                    // Execute the query
                    $result = mysqli_query($conn, $totalTransactionsQuery);

                    // Check if the query was successful and fetch the result
                    if ($result) 
                    {
                      $row = mysqli_fetch_assoc($result);
                      $totalTransactions = $row['total'];
                    } 
                    else 
                    {
                      $totalTransactions = 0; // Default to 0 if query fails
                    }
                    ?>
                  <h5><?php echo $totalTransactions; ?></h5>
                  <p>TOTAL TRANSACTIONS</p>
                </div>
              </div>
        
              <!-- Confirmed Transaction -->
              <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-green">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="side-content d-flex flex-column">
                  <?php
                    // Get session variables
                    $accountId = $_SESSION['agent_accountId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Determine which query to run based on the agent's role
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId
                      $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                                WHERE status = 'Confirmed' AND accountId = '$accountId' 
                                                AND MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                                AND YEAR(bookingDate) = YEAR(CURRENT_DATE())";
                    } 
                    else 
                    {
                      // Query for Head Agent, use agentCode
                      $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                                WHERE status = 'Confirmed' AND agentCode = '$agentCode' 
                                                AND MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                                AND YEAR(bookingDate) = YEAR(CURRENT_DATE())";
                    }

                    // Execute the query
                    $result = mysqli_query($conn, $totalTransactionsQuery);

                    // Check if the query was successful and fetch the result
                    if ($result) 
                    {
                      $row = mysqli_fetch_assoc($result);
                      $totalTransactions = $row['total'];
                    } 
                    else 
                    {
                      $totalTransactions = 0; // Default to 0 if query fails
                    }
                  ?>
                  <h5><?php echo $totalTransactions; ?></h5>
                  <p>COMPLETED</p>
                </div>
              </div>
            </div>

            <!-- Pending, and Cancelled Transaction -->
            <div class="row">
              <!-- Pending Transaction -->
              <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-yellow">
                  <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="side-content d-flex flex-column">
                  <?php
                    // Get session variables
                    $accountId = $_SESSION['agent_accountId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Determine which query to run based on the agent's role
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId
                      $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                                WHERE status = 'Pending' AND accountId = '$accountId' 
                                                AND MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                                AND YEAR(bookingDate) = YEAR(CURRENT_DATE())";
                    } 
                    else 
                    {
                      // Query for Head Agent, use agentCode
                      $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                                WHERE status = 'Pending' AND agentCode = '$agentCode' 
                                                AND MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                                AND YEAR(bookingDate) = YEAR(CURRENT_DATE())";
                    }

                    // Execute the query
                    $result = mysqli_query($conn, $totalTransactionsQuery);

                    // Check if the query was successful and fetch the result
                    if ($result) 
                    {
                      $row = mysqli_fetch_assoc($result);
                      $totalTransactions = $row['total'];
                    } 
                    else 
                    {
                      $totalTransactions = 0; // Default to 0 if query fails
                    }
                  ?>
                  <h5><?php echo $totalTransactions; ?></h5>
                  <p>PENDING</p>
                </div>
              </div>
        
              <!-- Total Cancelled Transaction -->
              <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-red">
                  <i class="fas fa-times-circle"></i>
                </div>
                <div class="side-content d-flex flex-column">
                  <?php
                    // Get session variables
                    $accountId = $_SESSION['agent_accountId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Determine which query to run based on the agent's role
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId
                      $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                                WHERE status = 'Cancelled' AND accountId = '$accountId' 
                                                AND MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                                AND YEAR(bookingDate) = YEAR(CURRENT_DATE())";
                    } 
                    else 
                    {
                      // Query for Head Agent, use agentCode
                      $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                                WHERE status = 'Cancelled' AND agentCode = '$agentCode' 
                                                AND MONTH(bookingDate) = MONTH(CURRENT_DATE()) 
                                                AND YEAR(bookingDate) = YEAR(CURRENT_DATE())";
                    }

                    // Execute the query
                    $result = mysqli_query($conn, $totalTransactionsQuery);

                    // Check if the query was successful and fetch the result
                    if ($result) 
                    {
                      $row = mysqli_fetch_assoc($result);
                      $totalTransactions = $row['total'];
                    } 
                    else 
                    {
                      $totalTransactions = 0; // Default to 0 if query fails
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
          <div class="header-counts">
            <h6 class="text-secondary white-pill">On Due</h6>
          </div>

          <div class="card-content px-3">
            <div class="row">
              <!-- Due on 5 Days -->
              <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-blue">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="side-content d-flex flex-column">
                  <?php
                    // Get session variables
                    $accountId = $_SESSION['agent_accountId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Determine which query to run based on the agent's role
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId
                      $days5Query = "SELECT COUNT(*) AS `bookingsDueIn5Days` FROM booking b 
                                    JOIN flight f ON b.flightId = f.flightId
                                    LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                                AS totalPaid FROM payment GROUP BY transactNo) p ON b.transactNo = p.transactNo
                                    WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) <= 5 
                                    AND DATEDIFF(f.flightDepartureDate, CURDATE()) >= 0
                                    AND (b.totalPrice > IFNULL(p.totalPaid, 0)) AND b.accountId = '$accountId' 
                                    AND b.status = 'Confirmed'";
                    } 
                    else 
                    {
                      // Query for Head Agent, use agentCode
                      $days5Query = "SELECT COUNT(*) AS `bookingsDueIn5Days` FROM booking b 
                                    JOIN flight f ON b.flightId = f.flightId
                                    LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                                AS totalPaid FROM payment GROUP BY transactNo) p ON b.transactNo = p.transactNo
                                    WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) <= 5 
                                    AND DATEDIFF(f.flightDepartureDate, CURDATE()) >= 0
                                    AND (b.totalPrice > IFNULL(p.totalPaid, 0)) AND b.agentCode = '$agentCode' 
                                    AND b.status = 'Confirmed'";
                    }

                    // Execute the query
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
        
              <!-- Due on 10 Days -->
              <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-green">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="side-content d-flex flex-column">
                  <?php
                    // Assuming you already have a connection to your database
                    $accountId = $_SESSION['agent_accountId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Determine which query to run based on the agent's role
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId
                      $days10Query = "SELECT COUNT(*) AS bookingsDueIn10Days FROM booking b
                                      JOIN flight f ON b.flightId = f.flightId
                                      LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                                AS totalPaid FROM payment GROUP BY transactNo) p ON b.transactNo = p.transactNo
                                      WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 6 AND 10
                                      AND (b.totalPrice > IFNULL(p.totalPaid, 0)) AND b.accountId = '$accountId' 
                                      AND b.status = 'Confirmed'";
                    } 
                    else 
                    {
                      // Query for Head Agent, use agentCode
                      $days10Query = "SELECT COUNT(*) AS bookingsDueIn10Days FROM booking b
                                      JOIN flight f ON b.flightId = f.flightId
                                      LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                                AS totalPaid FROM payment GROUP BY transactNo) p ON b.transactNo = p.transactNo
                                      WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 6 AND 10
                                      AND (b.totalPrice > IFNULL(p.totalPaid, 0)) AND b.agentCode = '$agentCode' 
                                      AND b.status = 'Confirmed'";
                    }

                    // Execute the query
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

            <div class="row">
              <!-- Due on 20 Days -->
              <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-yellow">
                  <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="side-content d-flex flex-column">
                  <?php
                    // Assuming you already have a connection to your database
                    $accountId = $_SESSION['agent_accountId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Determine which query to run based on the agent's role
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId
                      $days20Query = "SELECT COUNT(*) AS bookingsDueIn20Days FROM booking b 
                                      JOIN flight f ON b.flightId = f.flightId
                                      LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                                AS totalPaid FROM payment GROUP BY transactNo) p 
                                      ON b.transactNo = p.transactNo
                                      WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 10 AND 20
                                      AND (b.totalPrice > IFNULL(p.totalPaid, 0)) AND b.accountId = '$accountId' 
                                      AND b.status = 'Confirmed'";
                    } 
                    else 
                    {
                      // Query for Head Agent, use agentCode
                      $days20Query = "SELECT COUNT(*) AS bookingsDueIn20Days FROM booking b 
                                      JOIN flight f ON b.flightId = f.flightId
                                      LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                                AS totalPaid FROM payment GROUP BY transactNo) p 
                                      ON b.transactNo = p.transactNo
                                      WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 10 AND 20
                                      AND (b.totalPrice > IFNULL(p.totalPaid, 0)) AND b.agentCode = '$agentCode' 
                                      AND b.status = 'Confirmed'";
                    }

                    // Execute the query
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
                  <p>20 DAYS</p>
                </div>
              </div>
        
              <!-- Due on 30 Days -->
              <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-red">
                  <i class="fas fa-times-circle"></i>
                </div>
                <div class="side-content d-flex flex-column">
                  <?php
                    // Assuming you already have a connection to your database
                    $accountId = $_SESSION['agent_accountId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Determine which query to run based on the agent's role
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId
                      $days30Query = "SELECT COUNT(*) AS bookingsDueIn30Days FROM booking b 
                                      JOIN flight f ON b.flightId = f.flightId
                                      LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                                AS totalPaid FROM payment GROUP BY transactNo) p 
                                      ON b.transactNo = p.transactNo
                                      WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 20 AND 30
                                      AND (b.totalPrice > IFNULL(p.totalPaid, 0)) AND b.accountId = '$accountId' 
                                      AND b.status = 'Confirmed'";
                    } 
                    else 
                    {
                      // Query for Head Agent, use agentCode
                      $days30Query = "SELECT COUNT(*) AS bookingsDueIn30Days FROM booking b 
                                      JOIN flight f ON b.flightId = f.flightId
                                      LEFT JOIN (SELECT transactNo, SUM(CASE WHEN paymentStatus = 'Approved' THEN amount ELSE 0 END) 
                                                AS totalPaid FROM payment GROUP BY transactNo) p 
                                      ON b.transactNo = p.transactNo
                                      WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 20 AND 30
                                      AND (b.totalPrice > IFNULL(p.totalPaid, 0)) 
                                      AND b.agentCode = '$agentCode' 
                                      AND b.status = 'Confirmed'";
                    }

                    // Execute the query
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
          <div class="header-counts">
            <h6 class="text-secondary fw-600 white-pill">Total Sales</h6>
          </div>

          <div class="card-content px-3">
            <div class="row">
              <div class="col-md-5 d-flex flex-row totalpayment">
                <div class="card-icon icon-blue">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="side-content d-flex flex-column">
                  <?php
                    // Assuming you already have a connection to your database
                    $accountId = $_SESSION['agent_accountId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Determine which query to run based on the agent's role
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId in the payment table
                      $pastMonthQuery = "SELECT IFNULL(SUM(amount), 0) AS totalPastMonth FROM payment p
                                          JOIN booking b ON p.transactNo = b.transactNo
                                          WHERE b.accountId = '$accountId' AND p.paymentStatus = 'Approved' 
                                          AND MONTH(p.paymentDate) = MONTH(CURDATE() - INTERVAL 1 MONTH)
                                          AND YEAR(p.paymentDate) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
                    } 
                    else 
                    {
                      // Query for Head Agent, filter payments related to the agentCode in the booking table
                      $pastMonthQuery = "SELECT IFNULL(SUM(amount), 0) AS totalPastMonth FROM payment p
                                          JOIN booking b ON p.transactNo = b.transactNo
                                          WHERE b.agentCode = '$agentCode' AND p.paymentStatus = 'Approved' 
                                          AND MONTH(p.paymentDate) = MONTH(CURDATE() - INTERVAL 1 MONTH)
                                          AND YEAR(p.paymentDate) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
                    }

                    // Execute the query
                    $pastMonthResult = $conn->query($pastMonthQuery);

                    // Check if the query returned a result
                    $pastMonthTotal = ($pastMonthResult->num_rows > 0) 
                        ? number_format($pastMonthResult->fetch_assoc()['totalPastMonth'], 2) 
                        : '0.00';
                  ?>
                  <h5>₱ <?php echo $pastMonthTotal; ?></h5>
                  <p>PAST MONTH</p>
                </div>
              </div>

              <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-gray">
                  <i class="fas fa-check-circle"></i>
                </div>
                <div class="side-content d-flex flex-column">
                  <?php
                    // Assuming you already have a connection to your database
                    $accountId = $_SESSION['agent_accountId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Determine which query to run based on the agent's role
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId in the payment table
                      $currentMonthQuery = "SELECT IFNULL(SUM(amount), 0) AS totalCurrentMonth FROM payment p
                                          JOIN booking b ON p.transactNo = b.transactNo
                                          WHERE b.accountId = '$accountId' AND p.paymentStatus = 'Approved' 
                                          AND MONTH(p.paymentDate) = MONTH(CURDATE()) AND YEAR(p.paymentDate) = YEAR(CURDATE())";
                    } 
                    else 
                    {
                      // Query for Head Agent, filter payments related to the agentCode in the booking table
                      $currentMonthQuery = "SELECT IFNULL(SUM(amount), 0) AS totalCurrentMonth FROM payment p
                                          JOIN booking b ON p.transactNo = b.transactNo
                                          WHERE b.agentCode = '$agentCode'  AND p.paymentStatus = 'Approved' 
                                          AND MONTH(p.paymentDate) = MONTH(CURDATE()) AND YEAR(p.paymentDate) = YEAR(CURDATE())";
                    }

                    // Execute the query
                    $currentMonthResult = $conn->query($currentMonthQuery);

                    // Check if the query returned a result
                    $currentMonthTotal = ($currentMonthResult->num_rows > 0) 
                        ? number_format($currentMonthResult->fetch_assoc()['totalCurrentMonth'], 2) 
                        : 0;
                  ?>
                  <h5>₱ <?php echo $currentMonthTotal; ?></h5>
                  <p>CURRENT MONTH</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php include '../Agent Section/functions/exchange-rate.php'?>

        <!-- CARD 4 -->
        <div class="card border-0">
          <div class="header-counts d-flex justify-content-between align-items-center mb-2">
            <h6 class="text-secondary white-pill">Daily Currency Conversion</h6>
            <a href="" class="pill-button">View History</a>
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
                  <h6 class="currency-text">₱ <?php echo number_format($usd_to_php, 2); ?></h6>
                </div>
              </div>

              <div class="currency-card">
                <div class="flag-icon-wrapper">
                  <img src="../assets/images/Flags/korean-flag.png" alt="">
                  <h6>KOR</h6>
                  <h6>₩ <?php echo number_format($usd_to_krw, 0); ?></h6>
                </div>
              </div>

              <div class="currency-card">
                <div class="flag-icon-wrapper">
                  <img src="../assets/images/Flags/european.png" alt="">
                  <h6>EUR</h6>
                  <h6>€ <?php echo number_format($usd_to_euro, 2); ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Transactions, Request, Payment Tables Second Row -->
      <div class="second-row-container">
        <!-- Transactions table -->
        <div class="one">
          <div class="header d-flex justify-content-between align-items-center">
            <h6>Transactions</h6>
            <div class="view-booking-container">
            </div>
          </div>
        
          <div class="body">
            <div class="table-container" style="max-height: 315px;">
              <table class="unconfirm-table py-2">
                <thead>
                  <tr>
                    <th>TRANSACTION NO.</th>
                    <th>PACKAGE</th> 
                    <th>FLIGHT DATE</th>
                    <th>PAX.</th>
                    <th>CONTACT NAME</th>
                    <th>BOOKING TYPE</th>
                    <th>STATUS</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sql1 = "SELECT
                              b.transactNo AS `T.N`,
                              p.packageName AS `PACKAGE`, b.bookingType as bookingType,
                              CASE 
                                  WHEN b.flightId IS NULL THEN 'Land Only'
                                  ELSE CONCAT(DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y'), ' ', DATE_FORMAT(f.flightDepartureTime, '%h:%i %p'))
                              END AS `FLIGHT DATE`,
                              b.pax AS `TOTAL PAX`,
                              CONCAT(
                                  b.lName, ', ', b.fName, ' ', 
                                  CASE WHEN b.mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ',
                                  CASE WHEN b.suffix = 'N/A' THEN '' ELSE b.suffix END
                              ) AS `CONTACT NAME`,
                              b.status AS `STATUS`
                            FROM 
                                booking b
                            LEFT JOIN 
                                flight f ON b.flightId = f.flightId
                            LEFT JOIN 
                                package p ON b.packageId = p.packageId
                            LEFT JOIN
                                agent a ON b.agentId = a.agentId
                            WHERE 
                                b.agentId = '$agentId'
                            ORDER BY 
                                b.transactNo DESC";
          
                    // Run the query and check for results
                    $res1 = $conn->query($sql1);
                      
                    // Check if there are any results
                    if ($res1->num_rows > 0) 
                    {
                      // Output data for each row
                      while ($row = $res1->fetch_assoc()) 
                      {
                        echo "
                            <tr data-url='agent-showGuest.php?id=" . htmlspecialchars($row['T.N']) . "'>
                              <td>" . htmlspecialchars(substr($row['T.N'], 5)) . "</td>
                              <td>" . htmlspecialchars($row['PACKAGE']) . "</td>
                              <td>" . htmlspecialchars($row['FLIGHT DATE']) . "</td>
                              <td>" . htmlspecialchars($row['TOTAL PAX']) . "</td>
                              <td>" . htmlspecialchars($row['CONTACT NAME']) . "</td>
                              <td>" . $row['bookingType'] . "</td>
                              <td>" . $row['STATUS'] . "</td>
                            </tr>";
                      }
                    } 
                    else 
                    {
                      // If no records found
                      echo "<tr><td colspan='12' style='text-align: center;'>No bookings as of the moment</td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Requests table -->
        <div class="two">
          <div class="header d-flex justify-content-between align-items-center">
            <h6>Requests</h6>
          </div>

          <div class="body">
            <div class="table-container" style="max-height: 400px;">
              <table class="request-table">
                <thead>
                  <tr>
                    <th>TRANSACTION NO.</th>
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
                                cd.details AS `Details`,
                                r.customRequest AS `CustomRequest`,
                                DATE_FORMAT(r.requestDate, '%m-%d-%Y') AS `Date`, 
                                r.requestStatus AS `Status`, 
                                b.transactNo
                            FROM 
                                request r
                            LEFT JOIN 
                                booking b ON r.transactNo = b.transactNo
                            LEFT JOIN 
                                concern c ON r.concernId = c.concernId
                            LEFT JOIN 
                                concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
                            WHERE 
                                b.agentId = '$agentId' AND r.requestStatus = 'Submitted'
                            ORDER BY 
                                r.requestDate DESC";

                    $res1 = $conn->query($sql1);

                    if ($res1 && $res1->num_rows > 0) 
                    {
                      while ($row = $res1->fetch_assoc()) 
                      {
                        // Handle custom request fallback logic
                        $title = $row['Request'] ?? 'Custom Request'; // Use 'Custom Request' if `Request` is NULL
                        $details = $row['Details'] ?? $row['CustomRequest']; // Use `CustomRequest` if `Details` is NULL

                        echo "<tr data-url='agent-showGuest.php?id=" . htmlspecialchars($row['T.N']) . "'>
                                <td>" . htmlspecialchars(substr($row['transactNo'], 5)) . "</td> <!-- Transaction No -->
                                <td>" . htmlspecialchars($title) . "</td> <!-- Request -->
                                <td>" . htmlspecialchars($row['Date']) . "</td> <!-- Date -->
                                <td>" . htmlspecialchars($row['Status']) . "</td> <!-- Status -->
                              </tr>";
                      }
                    } 
                    else 
                    {
                      echo "<tr><td colspan='4' style='text-align: center;'>No Requests found as of the moment</td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Payment table -->
        <div class="three">
          <div class="header d-flex justify-content-between align-items-center">
            <h6>Payment</h6>
          </div>

          <div class="body">
            <div class="table-container">
              <table class="pending-payment-table">
                <thead>
                  <tr>
                    <th>TRANSACTION NO.</th>
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
                              DATE_FORMAT(p.paymentDate, '%m-%d-%Y') AS `Date`,  -- Format the date as specified
                              p.paymentType AS `Payment Type`,
                              p.paymentStatus, b.agentId
                            FROM 
                              payment p
                            JOIN 
                              booking b ON p.transactNo = b.transactNo
                            WHERE 
                              b.agentId = '$agentId' and p.paymentStatus = 'Submitted'  -- Adjust conditions as needed
                            ORDER BY 
                              p.paymentDate DESC";  // Order by payment date

                    $res2 = $conn->query($sql2);
                    
                    if ($res2->num_rows > 0) {
                      while ($row = $res2->fetch_assoc()) {
                        $rowTrans = htmlspecialchars(substr($row['Transaction No'], 5));


                        echo "<tr data-url='agent-showGuest.php?id=" . htmlspecialchars($row['Transaction No']) . "'>
                                <td>{$rowTrans}</td>
                                <td>{$row['Payment Title']}</td>
                                <td>{$row['Payment Type']}</td>
                                <td>₱ {$row['Amount']}</td>
                                <td> {$row['Date']} </td>
                                <td>{$row['paymentStatus']}</td>
                              </tr>";
                      }
                    } else {  
                      echo "<tr><td colspan='12' style='text-align: center;'>No payments found as of the moment</td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>

      <!-- Confirmed Transactions Third Row -->
      <div class="confirm-container">
        <div class="one">
          <div class="header ms-2 d-flex justify-content-between align-items-center justify-content-between">
            <h6>Confirmed Transactions</h6>
          </div>
            
          <div class="body">
            <div class="confirm-table-container">
              <table class="confirm-table">
                <thead style="font-size: 12px;">
                  <tr>
                    <th>TRANSACTION NO.</th>
                    <th>PACKAGE</th>
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
                    // Get session variables
                    $accountId = $_SESSION['agent_accountId'];
                    $agentId = $_SESSION['agent_agentId'];
                    $agentCode = $_SESSION['agent_agentCode'];
                    $agentRole = $_SESSION['agent_agentRole'];

                    // Check if the agent is a Head Agent or not
                    if ($agentRole != 'Head Agent') 
                    {
                      // Query for non-Head Agent, use accountId
                      $query = "SELECT b.transactNo, b.flightId, b.pax, b.totalPrice AS packagePrice, 
                                  CONCAT(DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y'), ' - ', 
                                  DATE_FORMAT(f.returnDepartureDate, '%m-%d-%Y')) AS FlightDate, p.packageName AS packageName, 
                                  CONCAT(b.lName, ', ', b.fName, ' ', 
                                      CASE WHEN b.mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ',
                                      CASE WHEN b.suffix = 'N/A' THEN '' ELSE b.suffix END) AS contactName, 
                                  IFNULL(req.totalRequestCost, 0) AS totalRequestCost,
                                  IFNULL(paid.totalPaidAmount, 0) AS totalPaidAmount,
                                  b.status AS bookingStatus, b.bookingType, (b.totalPrice + IFNULL(req.totalRequestCost, 0)) AS TotalCost
                                FROM 
                                  booking b
                                JOIN flight f ON b.flightId = f.flightId
                                LEFT JOIN 
                                  package p ON b.packageId = p.packageId
                                LEFT JOIN 
                                  (SELECT transactNo, SUM(amount) AS totalPaidAmount FROM payment
                                    WHERE paymentStatus = 'Approved' GROUP BY transactNo) paid ON b.transactNo = paid.transactNo
                                LEFT JOIN 
                                  (SELECT transactNo, SUM(requestCost) AS totalRequestCost FROM request
                                    WHERE requestStatus = 'Confirmed' GROUP BY transactNo) req ON b.transactNo = req.transactNo
                                WHERE 
                                  b.status = 'Confirmed' AND b.accountId = '$accountId' AND f.flightDepartureDate >= CURDATE()";
                    } 
                    else 
                    {
                      // Query for Head Agent, use agentCode
                      $query = "SELECT b.transactNo, b.flightId, b.pax, b.totalPrice AS packagePrice, 
                                  CONCAT(DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y'), ' - ', 
                                  DATE_FORMAT(f.returnDepartureDate, '%m-%d-%Y')) AS FlightDate, p.packageName AS packageName, 
                                  CONCAT(b.lName, ', ', b.fName, ' ', 
                                      CASE WHEN b.mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ',
                                      CASE WHEN b.suffix = 'N/A' THEN '' ELSE b.suffix END) AS contactName, 
                                  IFNULL(req.totalRequestCost, 0) AS totalRequestCost,
                                  IFNULL(paid.totalPaidAmount, 0) AS totalPaidAmount,
                                  b.status AS bookingStatus, b.bookingType, (b.totalPrice + IFNULL(req.totalRequestCost, 0)) AS TotalCost
                                FROM 
                                  booking b
                                JOIN flight f ON b.flightId = f.flightId
                                LEFT JOIN 
                                  package p ON b.packageId = p.packageId
                                LEFT JOIN 
                                  (SELECT transactNo, SUM(amount) AS totalPaidAmount FROM payment
                                    WHERE paymentStatus = 'Approved' GROUP BY transactNo) paid ON b.transactNo = paid.transactNo
                                LEFT JOIN 
                                  (SELECT transactNo, SUM(requestCost) AS totalRequestCost FROM request
                                    WHERE requestStatus = 'Confirmed' GROUP BY transactNo) req ON b.transactNo = req.transactNo
                                WHERE 
                                  b.status = 'Confirmed' AND b.agentCode = '$agentCode' AND f.flightDepartureDate >= CURDATE()";
                    }

                    // Execute the query
                    $result = $conn->query($query);

                    // Check if there are results and populate the table
                    if ($result && $result->num_rows > 0) 
                    {
                      while ($row = $result->fetch_assoc()) 
                      {
                        // Calculate Balance
                        $totalAmountPaid = $row['totalPaidAmount'];
                        $totalAmountToBePaid = $row['packagePrice'] + $row['totalRequestCost']; // Total price + total request cost
                        $balance = $totalAmountToBePaid - $totalAmountPaid; // Balance calculation

                        // Determine if fully paid or not
                        $status = ($totalAmountPaid == $totalAmountToBePaid) ? 'Fully Paid' : 'Not Paid';

                        // Display table row
                        echo "<tr data-url='agent-showGuest.php?id=" . htmlspecialchars($row['transactNo']) . "'>";
                        echo "<td>" . htmlspecialchars(substr($row['transactNo'], 5)) . "</td>"; // TransactNo
                        echo "<td>" . htmlspecialchars($row['packageName']) . "</td>"; // Package Name
                        echo "<td>" . htmlspecialchars($row['FlightDate']) . "</td>"; // Flight Date Range
                        echo "<td>" . htmlspecialchars($row['pax']) . "</td>"; // Pax (Number of Passengers)
                        echo "<td>" . htmlspecialchars($row['contactName']) . "</td>"; // Contact Name
                        echo "<td>" . $row['bookingType'] . "</td>"; // Booking Type 
                        echo "<td>₱ " . number_format($totalAmountPaid, 2) . "</td>"; // Total Amount Paid
                        echo "<td>₱ " . number_format($balance, 2) . "</td>"; // Balance (Amount to be paid - Amount paid)
                        echo "<td>" . htmlspecialchars($row['bookingStatus']) . "</td>"; // Status (Fully Paid or Not Paid)
                        echo "</tr>";
                      }
                    } 
                    else 
                    {
                      // Display a message if no records are found
                      echo "<tr><td colspan='12'>No records found.</td></tr>";
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

  <?php require "../Agent Section/includes/scripts.php"; ?>

  <!-- Clickable rows script -->
  <script>
    document.addEventListener("DOMContentLoaded", function() 
    {
      document.querySelectorAll("tr[data-url]").forEach(function(row) 
      {
        row.addEventListener("click", function() 
        {
          window.location.href = row.getAttribute("data-url");
        });
      });
    });
    // Add event listener to each row for redirection
    const rows = document.querySelectorAll("tr[data-url]");
    
    rows.forEach(row => 
    {
      row.addEventListener("click", function() 
      {
        const url = row.getAttribute("data-url");
        window.location.href = url; // Redirect to the specified URL
      });
    });
  </script>


  <!-- Chart.js library 
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

  <!-- Chart.js Data Labels plugin -->
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

  <!-- Script for rendering the Doughnut chart -->
  <script>
    var ctx = document.getElementById('myDoughnutChart').getContext('2d');
    var myDoughnutChart = new Chart(ctx, 
    {
      type: 'doughnut',
      data: 
      {
        labels: ['Total Seats', 'Seats Sold', 'Remaining Seats'],
        datasets: [
        {
          data: [46, 15, 29], // Example data
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'], // Example colors
          hoverOffset: 10
        }]
      },
      options: 
      {
        responsive: true,
        maintainAspectRatio: false, // Ensure the chart resizes to fit the div
        cutout: '80%', // Adjusts the thickness of the doughnut
        plugins: 
        {
          legend: 
          {
            display: true,
            position: 'bottom', // Keep legend on the right side
            labels: 
            {
              boxWidth: 15, // Adjust size of legend boxes
              padding: 10 // Reduce padding between legend and doughnut
            }
          },
          datalabels: 
          {
            color: '#000', // Label color
            anchor: 'center', // Position the label inside the doughnut
            align: 'center',
            borderColor: '#36A2EB', // Border color around the label
            borderWidth: 2, // Thickness of the border
            backgroundColor: '#fff', // Background color of the label
            borderRadius: 4, // Rounded corners for the label background
            padding: 6, // Padding around the label for spacing
            font: 
            {
              weight: 'bold' // Make the label font bold
            },
            formatter: (value, context) => 
            {
              let sum = 0;
              let dataArr = context.chart.data.datasets[0].data;
              dataArr.map(data => 
              {
                sum += data;
              });
              let percentage = (value * 100 / sum).toFixed(1) + "%"; // Display percentage with one decimal
              return percentage;
            }
          }
        }
      },
      plugins: [ChartDataLabels] // Activate the datalabels plugin
    });
  </script>
  
</body>

</html>