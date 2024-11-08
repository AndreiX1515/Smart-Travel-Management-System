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
              <h3>Transaction Status</h3>
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
              <h3>Booking</h3>
            </div>
          </div>

          <div class="dcard-body">
            <div class="month-transaction">
              <div class="logo-container transaction-total">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <h3>100</h3>
                <p>TOTAL TRANSACTION</p> <!-- Additional description -->
              </div>
            </div>

            <div class="month-transaction">
              <div class="logo-container transaction-cancelled">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <h3>100</h3>
                <p>CANCELLED</p> <!-- Additional description -->
              </div>
            </div>
          </div>

          <div class="dcard-body">
            <div class="month-transaction">
              <div class="logo-container transaction-ongoing">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <h3>100</h3>
                <p>ON GOING</p> <!-- Additional description -->
              </div>
            </div>

            <div class="month-transaction">
              <div class="logo-container transaction-confirmed">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <h3>100</h3>
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
            <div class="month-transaction">
              <div class="logo-container transaction-total">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <h3>100</h3>
                <p>TOTAL TRANSACTION</p> <!-- Additional description -->
              </div>
            </div>

            <div class="month-transaction">
              <div class="logo-container transaction-cancelled">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <h3>100</h3>
                <p>CANCELLED</p> <!-- Additional description -->
              </div>
            </div>
          </div>

          <div class="dcard-body">
            <div class="month-transaction">
              <div class="logo-container transaction-ongoing">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <h3>100</h3>
                <p>ON GOING</p> <!-- Additional description -->
              </div>
            </div>

            <div class="month-transaction">
              <div class="logo-container transaction-confirmed">
                <i class="fas fa-calendar-alt"></i> <!-- Example icon for logo -->
              </div>
              <div class="content-container">
                <h3>100</h3>
                <p>CONFIRMED</p> <!-- Additional description -->
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
            <h6>Unconfirmed Transactions</h6>
            <div class="view-booking-container">
            </div>
          </div>
          
          <div class="body">
            <div class="table-container">
              <table class="unconfirm-table">
                <thead>
                  <tr>
                    <th>T.N</th>
                    <th>PACKAGE</th>
                    <th>FLIGHT DATE</th>
                    <th>PAX.</th>
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
                        echo "<tr>
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
                      echo "<tr><td colspan='6' style='text-align: center;'>No bookings found</td></tr>";
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
            <table class="request-table">
              <thead>
                <tr>
                  <th>T.N</th>
                  <th>Request</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql1 = "SELECT 
                                r.transactNo AS `T.N`,
                                c.concernTitle AS `Request`,
                                DATE_FORMAT(r.requestDate, '%M-%d-%Y %h:%i:%s %p') AS `Date`
                            FROM 
                                request r
                            JOIN 
                                booking b ON r.transactNo = b.transactNo
                            JOIN 
                                concern c ON r.concernId = c.concernId
                            WHERE 
                                b.agentId = '$agentId'
                            ORDER BY 
                                r.requestDate DESC";  // Order by request date
      
                  $res1 = $conn->query($sql1);
                    
                  if ($res1->num_rows > 0) 
                  {
                    while ($row = $res1->fetch_assoc()) 
                    {
                      echo "<tr>
                              <td>{$row['T.N']}</td>
                              <td>{$row['Request']}</td>
                              <td>" . date('F d, Y', strtotime($row['Date'])) . "</td>
                            </tr>";
                    }
                  } 
                  else 
                  {
                    echo "<tr><td colspan='6' style='text-align: center;'>No Request found</td></tr>";
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="three">
         <div class="header d-flex justify-content-between align-items-center">
           <h6>Payment Approvals</h6>
           
         </div>

         <div class="body">
           <table class="pending-payment-table">
             <thead>
               <tr>
                 <th>Transaction No</th>
                 <th>Payment Title</th>
                 <th>Payment Type</th>
                 <th>Payment Amount</th>
                 <th>Date</th>
               </tr>
             </thead>
             <tbody>
               <?php
                 $sql2 = "SELECT 
                            p.transactNo AS `Transaction No`,
                            p.paymentTitle AS `Payment Title`,
                            CONCAT(FORMAT(p.amount, 2)) AS `Amount`,  -- Format the amount as a currency with two decimal places
                            DATE_FORMAT(p.paymentDate, '%M-%d-%Y %h:%i:%s %p') AS `Date`,  -- Format the date as specified
                            p.paymentType AS `Payment Type`,
                            p.paymentStatus AS `Status`
                          FROM 
                            payment p
                          JOIN 
                            booking b ON p.transactNo = b.transactNo
                          WHERE 
                            b.agentId = '$agentId' AND b.status = 'Pending'  -- Adjust conditions as needed
                          ORDER BY 
                            p.paymentDate DESC";  // Order by payment date
     
                 $res2 = $conn->query($sql2);
                 
                 if ($res2->num_rows > 0) {
                   while ($row = $res2->fetch_assoc()) {
                     echo "<tr>
                             <td>{$row['Transaction No']}</td>
                             <td>{$row['Payment Title']}</td>
                             <td>{$row['Payment Type']}</td>
                             <td>₱ {$row['Amount']}</td>
                             <td>" . date('F d, Y', strtotime($row['Date'])) . "</td>
                           </tr>";
                   }
                 } else {  
                   echo "<tr><td colspan='3' style='text-align: center;'>No payments found</td></tr>";
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
              <thead>
                <tr>
                  <th>T.N</th>
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
                      echo "<tr>
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
                    echo "<tr><td colspan='6' style='text-align: center;'>No Confirmed Transactions found</td></tr>";
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
