<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Agent Section/assets/css/agent-dashboard.css?v=<?php echo time(); ?>">
</head>

<body>

  <?php include '../Agent Section/includes/sidebar.php' ?>

  <!-- Main Content Section -->
  <div class="main-content" id="mainContent">
    <?php include '../Agent Section/includes/navbar.php' ?>

    <!-- Main Dashboard Content -->
    <div class="container-wrapper">
    
      <!-- Cards Count Total  -->
      <div class="dashboard-cards d-flex flex-wrap justify-content-between">

        <div class="dashboard-cards-three card">
          <div class="dcard-header">
            <div class="header-text">
              <h3>Current Transaction Status</h3>
            </div>
          </div>

          <!-- Total Transaction, and Canceled Transaction -->
          <div class="dcard-body">
            <!-- Total Transaction Card -->
            <div class="month-transaction">
              <div class="logo-container transaction-total">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <?php
                // Assuming you already have a connection to your database
                $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking where agentId = '$agentId'";
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
              <div class="content-container">
                <h3><?php echo $totalTransactions; ?></h3>
                <p>TOTAL TRANSACTION</p> <!-- Additional description -->
              </div>
            </div>

            <!-- Total Cancelled Transaction -->
            <div class="month-transaction">
              <div class="logo-container transaction-cancelled">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <?php
                // Assuming you already have a connection to your database
                $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking where status='Cancelled' and agentId = '$agentId'";
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
              <div class="content-container">
                <h3><?php echo $totalTransactions; ?></h3>
                <p>CANCELLED</p> <!-- Additional description -->
              </div>
            </div>
          </div>

          <!-- Pending, and Confirmed Transaction -->
          <div class="dcard-body">
            <!-- Pending Transaction -->
            <div class="month-transaction">
              <div class="logo-container transaction-ongoing">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <?php
                // Assuming you already have a connection to your database
                $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking where status='Pending' and agentId = '$agentId'";
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
              <div class="content-container">
                <h3><?php echo $totalTransactions; ?></h3>
                <p>PENDING</p> <!-- Additional description -->
              </div>
            </div>

            <!-- Confirmed Transaction -->
            <div class="month-transaction">
              <div class="logo-container transaction-confirmed">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <?php
                // Assuming you already have a connection to your database
                $totalTransactionsQuery = "SELECT COUNT(*) AS total FROM booking where status='Confirmed' and agentId = '$agentId'";
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
              <div class="content-container">
                <h3><?php echo $totalTransactions; ?></h3>
                <p>CONFIRMED</p> <!-- Additional description -->
              </div>
            </div>
          </div>

        </div>

        <div class="dashboard-cards-two card">
          <div class="dcard-header">
            <div class="header-text">
              <h3>Transaction History</h3>
            </div>
          </div>

          <div class="dcard-body">
            <div class="month-transaction">
              <div class="logo-container transaction-total">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <?php
                  // Get the current month
                  $currentMonth = date('m');
                  $currentYear = date('Y');

                  // Past Transactions: -1 month from the current month
                  $pastTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                            WHERE agentId = '$agentId' 
                                            AND MONTH(bookingDate) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) 
                                            AND YEAR(bookingDate) = '$currentYear'";
                  $pastResult = mysqli_query($conn, $pastTransactionsQuery);
                  $pastTransactions = $pastResult ? mysqli_fetch_assoc($pastResult)['total'] : 0;
                ?>
                <h3><?php echo $pastTransactions; ?></h3>
                <p>PAST</p> <!-- Additional description -->
              </div>
            </div>

            <div class="month-transaction">
              <div class="logo-container transaction-cancelled">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <?php
                  // Current Transactions: transactions in the current month
                  $currentTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                                WHERE agentId = '$agentId' 
                                                AND MONTH(bookingDate) = '$currentMonth' 
                                                AND YEAR(bookingDate) = '$currentYear'";
                  $currentResult = mysqli_query($conn, $currentTransactionsQuery);
                  $currentTransactions = $currentResult ? mysqli_fetch_assoc($currentResult)['total'] : 0;
                ?>
                <h3><?php echo $currentTransactions; ?></h3>
                <p>CURRENT</p> <!-- Additional description -->
              </div>
            </div>
          </div>

          <div class="dcard-body">
            <div class="month-transaction">
              <div class="logo-container transaction-ongoing">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <?php
                  // Future Transactions: +1 month from the current month
                  $futureTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                              WHERE agentId = '$agentId' 
                                              AND MONTH(bookingDate) = MONTH(DATE_ADD(CURDATE(), INTERVAL 1 MONTH)) 
                                              AND YEAR(bookingDate) = '$currentYear'";
                  $futureResult = mysqli_query($conn, $futureTransactionsQuery);
                  $futureTransactions = $futureResult ? mysqli_fetch_assoc($futureResult)['total'] : 0;
                ?>
                <h3><?php echo $futureTransactions; ?></h3>
                <p>ON GOING</p> <!-- Additional description -->
              </div>
            </div>

            <div class="month-transaction">
              <div class="logo-container transaction-confirmed">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <?php
                  // Calculate the future month and year for "Future More Transactions"
                  $futureMonth = $currentMonth + 2;
                  $futureYear = $currentYear;
                  // Adjust the year if the future month exceeds December
                  if ($futureMonth > 12) 
                  {
                    $futureMonth -= 12;
                    $futureYear += 1;
                  }

                  // Future More Transactions: Current month +2 and beyond
                  $futureMoreTransactionsQuery = "SELECT COUNT(*) AS total FROM booking 
                                                WHERE agentId = '$agentId' 
                                                AND (YEAR(bookingDate) > '$futureYear' 
                                                    OR (YEAR(bookingDate) = '$futureYear' 
                                                        AND MONTH(bookingDate) >= '$futureMonth'))";
                  $futureMoreResult = mysqli_query($conn, $futureMoreTransactionsQuery);
                  $futureMoreTransactions = $futureMoreResult ? mysqli_fetch_assoc($futureMoreResult)['total'] : 0;
                ?>
                <h3><?php echo $futureMoreTransactions; ?></h3>
                <p>CONFIRMED</p> <!-- Additional description -->
              </div>
            </div>
          </div>

          
        </div>

        <div class="dashboard-cards-three card">
          <div class="dcard-header">
            <div class="header-text">
              <h3>On Due</h3>
            </div>
          </div>

          <div class="dcard-body">
            <!-- Due on 5 Days -->
            <div class="month-transaction">
              <div class="logo-container transaction-total">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <?php
                  // Assuming $conn is your database connection
                  $days5Query = "SELECT COUNT(*) AS `bookingsDueIn5Days` 
                                FROM booking b 
                                JOIN flight f ON b.flightId = f.flightId
                                WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) <= 5 
                                  AND DATEDIFF(f.flightDepartureDate, CURDATE()) >= 0";

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
                <h3><?php echo $bookingsDueIn5Days; ?></h3>
                <p>5 DAYS DUE</p> <!-- Additional description -->
              </div>
            </div>


            <!-- Due on 10 Days -->
            <div class="month-transaction">
              <div class="logo-container transaction-cancelled">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <?php
                  // Assuming $conn is your database connection
                  $days10Query = "SELECT COUNT(*) AS `bookingsDueIn10Days` 
                                FROM booking b 
                                JOIN flight f ON b.flightId = f.flightId
                                WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) = 10";

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
                <h3><?php echo $bookingsDueIn10Days; ?></h3>
                <p>10 DAYS DUE</p> <!-- Additional description -->
              </div>
            </div>
          </div>

          <div class="dcard-body">
            <!-- Due on 20 Days -->
            <div class="month-transaction">
              <div class="logo-container transaction-ongoing">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <?php
                  // Assuming $conn is your database connection
                  $days20Query = "SELECT COUNT(*) AS `bookingsDueIn20Days` 
                                FROM booking b 
                                JOIN flight f ON b.flightId = f.flightId
                                WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) = 20";

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
                <h3><?php echo $bookingsDueIn20Days; ?></h3>
                <p>20 DAYS DUE</p> <!-- Additional description -->
              </div>
            </div>

            <!-- Due on 30 Days -->
            <div class="month-transaction">
              <div class="logo-container transaction-confirmed">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <?php
                  // Assuming $conn is your database connection
                  $days30Query = "SELECT COUNT(*) AS `bookingsDueIn30Days` 
                                FROM booking b 
                                JOIN flight f ON b.flightId = f.flightId
                                WHERE DATEDIFF(f.flightDepartureDate, CURDATE()) = 30";

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
                <h3><?php echo $bookingsDueIn30Days; ?></h3>
                <p>30 DAYS DUE</p> <!-- Additional description -->
              </div>
            </div>
          </div>

        </div>

        <div class="dashboard-cards-four card">
          <div class="dcard-header">
            <div class="header-text">
              <h3>Currency Conversion</h3>
            </div>
          </div>

          <?php include '../Agent Section/functions/exchange-rate.php'?>

          <div class="dcard-body">
            <div class="currency-content mt-2">
              <div class="currency-item">
                <img src="../assets/images/Flags/english-flag.png" alt="" class="currency-flag">
                <p class="currency-name">1 USD</p>
                <p class="conversion-rate">$1.00</p> <!-- Rate of 1 USD to itself -->
              </div>

              <div>
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
              </div>

              <div class="currency-item me-3">
                <img src="../assets/images/Flags/philippines (2).png" alt="" class="currency-flag">
                <p class="currency-name">PHP</p>
                <p class="conversion-rate">₱ <?php echo number_format($usd_to_php, 2); ?></p>
              </div>

              <div class="currency-item me-3">
                <img src="../assets/images/Flags/korean-flag.png" alt="" class="currency-flag">
                <p class="currency-name">WON</p>
                <p class="conversion-rate">₩ <?php echo number_format($usd_to_krw, 2); ?></p>
              </div>

              <div class="currency-item">
                <img src="../assets/images/Flags/european.png" alt="" class="currency-flag">
                <p class="currency-name">EURO</p>
                <p class="conversion-rate">€ <?php echo number_format($usd_to_euro, 2); ?></p> 
              </div>
            </div>
          </div>
        </div>
      </div> 

      <div class="second-row-container">
        <div class="one">
          <div class="header d-flex justify-content-between align-items-center">
            <h6>Transactions</h6>
            <div class="view-booking-container">
            </div>
          </div>
          
          <div class="body">
            <div class="table-container">
              <table class="unconfirm-table" style="font-size: 10px;">
                <thead>
                  <tr>
                    <th>TRANSACTION NO.</th>
                    <th>PACKAGE</th> 
                    <th>FLIGHT DATE</th>
                    <th>PAX.</th>
                    <th>CONTACT NAME</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sql1 = "SELECT
                              b.transactNo AS `T.N`,
                              p.packageName AS `PACKAGE`,
                              CASE 
                                  WHEN b.flightId IS NULL THEN 'Land Only'
                                  ELSE CONCAT(DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y'), ' ', DATE_FORMAT(f.flightDepartureTime, '%h:%i %p'))
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
                                b.agentId = '$agentId' and status='Pending'
                            ORDER BY 
                                b.transactNo DESC LIMIT 5";
          
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
                              <td>" . htmlspecialchars($row['T.N']) . "</td>
                              <td>" . htmlspecialchars($row['PACKAGE']) . "</td>
                              <td>" . htmlspecialchars($row['FLIGHT DATE']) . "</td>
                              <td>" . htmlspecialchars($row['TOTAL PAX']) . "</td>
                              <td>" . htmlspecialchars($row['CONTACT NAME']) . "</td>
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

        <div class="two">
          <div class="header d-flex justify-content-between align-items-center">
            <h6>Requests</h6>
          </div>

          <div class="body">
            <table class="request-table" >
              <thead style="font-size: 10px;">
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
                                DATE_FORMAT(r.requestDate, '%M-%d-%Y %h:%i %p') AS `Date`,
                                r.requestStatus as status, b.agentId
                            FROM 
                                request r
                            JOIN 
                                booking b ON r.transactNo = b.transactNo
                            JOIN 
                                concern c ON r.concernId = c.concernId
                            WHERE 
                                b.agentId = '$agentId' and status = 'Pending'
                            ORDER BY 
                                r.requestDate DESC LIMIT 5";  // Order by request date
      
                  $res1 = $conn->query($sql1);
                    
                  if ($res1->num_rows > 0) 
                  {
                    while ($row = $res1->fetch_assoc()) 
                    {
                      echo "<tr data-url='agent-showGuest.php?id=" . htmlspecialchars($row['T.N']) . "'>
                              <td>{$row['T.N']}</td>
                              <td>{$row['Request']}</td>
                              <td>{$row['Date']}</td>
                              <td>{$row['status']}</td>
                            </tr>";
                    }
                  } 
                  else 
                  {
                    echo "<tr><td colspan='6' style='text-align: center;'>No Request found as of the moment</td></tr>";
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="three">
         <div class="header d-flex justify-content-between align-items-center">
           <h6>Payment</h6>
         </div>

         <div class="body">
           <table class="pending-payment-table">
             <thead style="font-size: 10px;">
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
                            DATE_FORMAT(p.paymentDate, '%M %d, %Y %h:%i %p') AS `Date`,  -- Format the date as specified
                            p.paymentType AS `Payment Type`,
                            p.paymentStatus AS `Status`, b.agentId
                          FROM 
                            payment p
                          JOIN 
                            booking b ON p.transactNo = b.transactNo
                          WHERE 
                            b.agentId = '$agentId' and Status = 'Pending'  -- Adjust conditions as needed
                          ORDER BY 
                            p.paymentDate DESC LIMIT 5";  // Order by payment date
     
                 $res2 = $conn->query($sql2);
                 
                 if ($res2->num_rows > 0) {
                   while ($row = $res2->fetch_assoc()) {
                     echo "<tr data-url='agent-showGuest.php?id=" . htmlspecialchars($row['Transaction No']) . "'>
                             <td>{$row['Transaction No']}</td>
                             <td>{$row['Payment Title']}</td>
                             <td>{$row['Payment Type']}</td>
                             <td>₱ {$row['Amount']}</td>
                             <td> {$row['Date']} </td>
                             <td>{$row['Status']}</td>
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

      <div class="confirm-container">
        <div class="one">
          <div class="header d-flex justify-content-between align-items-center justify-content-between">
            <h6>Confirmed Transactions</h6>
          </div>
            
          <div class="body">
            <table class="confirm-table">
              <thead style="font-size: 10px;">
                <tr>
                  <th>TRANSACTION NO.</th>
                  <th>PACKAGE</th>
                  <th>FLIGHT DATE</th>
                  <th>TOTAL PAX.</th>
                  <th>CONTACT NAME</th>
                  <th>STATUS</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql1 = "SELECT
                            b.transactNo AS `T.N`,
                            p.packageName AS `PACKAGE`,
                            CASE 
                                WHEN b.flightId IS NULL THEN 'Land Only'
                                ELSE DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y')
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
                              b.agentId = '$agentId' and status='Confirmed'
                          ORDER BY 
                              b.transactNo DESC LIMIT 5";
        
                  // Run the query and check for results
                  $res1 = $conn->query($sql1);
                    
                  // Check if there are any results
                  if ($res1->num_rows > 0) 
                  {
                    // Output data for each row
                    while ($row = $res1->fetch_assoc()) 
                    {
                      echo "<tr data-url='agent-showGuest.php?id=" . htmlspecialchars($row['T.N']) . "'>
                              <td>{$row['T.N']}</td>
                              <td>{$row['PACKAGE']}</td>
                              <td>{$row['FLIGHT DATE']}</td>
                              <td>{$row['TOTAL PAX']}</td>
                              <td>{$row['CONTACT NAME']}</td>
                              <td>{$row['STATUS']}</td>
                            </tr>";
                    }
                  } 
                  else 
                  {
                    // If no records found
                    echo "<tr><td colspan='12' style='text-align: center;'>No Confirmed Transactions as of the moment</td></tr>";
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


  <?php require "../Agent Section/includes/scripts.php"; ?>


  <script>
    document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll("tr[data-url]").forEach(function(row) {
        row.addEventListener("click", function() {
            window.location.href = row.getAttribute("data-url");
        });
    });
});




        // Add event listener to each row for redirection
        const rows = document.querySelectorAll("tr[data-url]");
        
        rows.forEach(row => {
            row.addEventListener("click", function() {
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
