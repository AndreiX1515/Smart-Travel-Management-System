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
    <div class="counts-wrapper">

      <!-- CARD 1 -->
      <div class="card border-0">
        <div class="header">
            <h6 class="text-secondary fw-600">Current Transaction</h6>
        </div>
    
        <div class="card-content px-3">
          <div class="row">
            <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-blue">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="side-content d-flex flex-column">
                    <h5>0</h5>
                    <p>TOTAL TRANSACTIONS</p>
                </div>
            </div>
       
           <div class="col-md-5 d-flex flex-row">
               <div class="card-icon icon-green">
                 <i class="fas fa-check-circle"></i>
               </div>
               <div class="side-content d-flex flex-column">
                   <h5>0</h5>
                   <p>COMPLETED</p>
               </div>
           </div>
         </div>

         <div class="row">
           <div class="col-md-5 d-flex flex-row">
               <div class="card-icon icon-yellow">
                 <i class="fas fa-exclamation-triangle"></i>
               </div>
               <div class="side-content d-flex flex-column">
                   <h5>0</h5>
                   <p>PENDING</p>
               </div>
           </div>
      
          <div class="col-md-5 d-flex flex-row">
              <div class="card-icon icon-red">
                <i class="fas fa-times-circle"></i>
              </div>
              <div class="side-content d-flex flex-column">
                  <h5>0</h5>
                  <p>CANCELLED</p>
              </div>
          </div>
        </div>
      </div>
    </div>
  
    <!-- CARD 2 -->
    <div class="card border-0" >
     <div class="header">
         <h6 class="text-secondary fw-600">Transaction History</h6>
     </div>
 
     <div class="card-content px-3">
       <div class="row">
         <div class="col-md-5 d-flex flex-row">
             <div class="card-icon icon-blue">
                 <i class="fas fa-calendar-alt"></i>
             </div>
             <div class="side-content d-flex flex-column">
                 <h5>0</h5>
                 <p>PAST</p>
             </div>
         </div>
    
        <div class="col-md-5 d-flex flex-row">
            <div class="card-icon icon-gray">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="side-content d-flex flex-column">
                <h5>0</h5>
                <p>CURRENT</p>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-5 d-flex flex-row">
            <div class="card-icon icon-yellow">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="side-content d-flex flex-column">
                <h5>0</h5>
                <p>ON GOING</p>
            </div>
        </div>
   
       <div class="col-md-5 d-flex flex-row">
           <div class="card-icon icon-green">
             <i class="fas fa-times-circle"></i>
           </div>
           <div class="side-content d-flex flex-column">
               <h5>0</h5>
               <p>CONFIRMED</p>
           </div>
       </div>
     </div>
   
    </div>
   </div>

   <!-- CARD 3 -->
   <div class="card border-0">
    <div class="header">
        <h6 class="text-secondary fw-600">On Due</h6>
    </div>

    <div class="card-content px-3">
      <div class="row">
        <div class="col-md-5 d-flex flex-row">
            <div class="card-icon icon-blue">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="side-content d-flex flex-column">
                <h5>0</h5>
                <p>5 DAYS</p>
            </div>
        </div>
   
       <div class="col-md-5 d-flex flex-row">
           <div class="card-icon icon-green">
             <i class="fas fa-check-circle"></i>
           </div>
           <div class="side-content d-flex flex-column">
               <h5>0</h5>
               <p>10 DAYS</p>
           </div>
       </div>
     </div>

      <div class="row">
        <div class="col-md-5 d-flex flex-row">
            <div class="card-icon icon-yellow">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="side-content d-flex flex-column">
                <h5>0</h5>
                <p>15 DAYS</p>
            </div>
        </div>
   
       <div class="col-md-5 d-flex flex-row">
           <div class="card-icon icon-red">
             <i class="fas fa-times-circle"></i>
           </div>
           <div class="side-content d-flex flex-column">
               <h5>0</h5>
               <p>30 DAYS</p>
           </div>
       </div>
     </div>
   
    </div>
 </div>

  <!-- CARD 4 -->
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
 
<div class="header-wrapper">
   <div class="price-table-wrapper">
      <div class="header p-3">
           <h6 class="text-secondary">Price</h6>
     </div>

     <!-- <div class="price-table-container">
        <table class="price-table">
         <thead>
             <tr>
                 <th>Wholesale Price</th>
                 <th>Retail Price</th>
                 <th>Land Arrangement</th>
             </tr>
         </thead>
         <tbody>
             <tr>
                 <td>Basic</td>
                 <td>$19.99</td>
                 <td>5 Features</td>
             </tr>
             <tr>
                 <td>Standard</td>
                 <td>$49.99</td>
                 <td>10 Features</td>
             </tr>
             <tr>
                 <td>Premium</td>
                 <td>$99.99</td>
                 <td>Unlimited Features</td>
             </tr>
         </tbody>
     </table>
  </div> -->

 </div>


  <div class="request-wrapper">
    <div class="header p-3">
      <h6 class="text-secondary">Requests</h6>
    </div>

    <div class="request-table-container">
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
                          DATE_FORMAT(r.requestDate, '%M %d, %Y') AS `Date`,
                          r.requestStatus, b.agentId
                      FROM 
                          request r
                      JOIN 
                          booking b ON r.transactNo = b.transactNo
                      JOIN 
                          concern c ON r.concernId = c.concernId
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
                        <td>{$row['T.N']}</td>
                        <td>{$row['Request']}</td>
                        <td>{$row['Date']}</td>
                        <td><span class='{$statusClass}'>{$row['requestStatus']}</span></td>
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

  <div class="payment-wrapper">
    <div class="header p-3">
      <h6 class="text-secondary">Payment</h6>
    </div>

    <div class="payment-table-container px-3">
      <table class="payment-table">
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
                      DATE_FORMAT(p.paymentDate, '%M %d, %Y') AS `Date`,  -- Format the date as specified
                      p.paymentType AS `Payment Type`,
                      p.paymentStatus, b.agentId
                    FROM 
                      payment p
                    JOIN 
                      booking b ON p.transactNo = b.transactNo
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
                        <td>{$row['Transaction No']}</td>
                        <td>{$row['Payment Title']}</td>
                        <td>{$row['Payment Type']}</td>
                        <td>₱ {$row['Amount']}</td>
                        <td>{$row['Date']}</td>
                        <td><span class='{$statusClass}'>{$row['paymentStatus']}</span></td>
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

     
<div class="main-table-wrapper-one">
  <div class="table-info-container">
    <div class="header p-3 d-flex flex-row justify-content-between align-items-center">
      <h6 class="text-secondary">Flights</h6>

      <div class="end-part">
       <div class="legend-guides">


       </div>

       <button class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></button>

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
              // Fetch agent columns headers dynamically
              $sql = "SELECT DISTINCT agentId FROM booking WHERE agentId IS NOT NULL AND agentId != ''";
              $result = $conn->query($sql);
              while ($row = $result->fetch_assoc()) 
              {
                echo '<th colspan="2" data-bs-toggle="tooltip" title="' . $row['agentId'] . '">' . $row['agentId'] . '</th>';
              }
            ?>
          </tr>
          <tr style="top: -8px">
            <th>START</th>
            <th>END</th>
            <!-- A1, A2, A3, A4, A5, A6, A7 Sub Headers -->
            <!-- Dynamic sub-headers for agent columns -->
            <?php
              $result = $conn->query($sql);
              while ($row = $result->fetch_assoc()) 
              {
                echo '<th>A.L</th><th>L.O</th>';
              }
            ?>
          </tr>
        </thead>
        <tbody>
          <?php
            // Step 1: Dynamically generate agent columns
            $sql = "SELECT DISTINCT agentId FROM booking WHERE agentId IS NOT NULL AND agentId != ''";
            $result = $conn->query($sql);

            $agentColumns = '';
            while ($row = $result->fetch_assoc()) 
            {
              $agentColumns .= 
                  'SUM(CASE WHEN b.agentId = "' . $row['agentId'] . '" AND b.bookingType = "Package" and b.status = "Confirmed" THEN b.pax ELSE 0 END) AS `' . $row['agentId'] . '_AL`, ' .
                  'SUM(CASE WHEN b.agentId = "' . $row['agentId'] . '" AND b.bookingType = "Land" and b.status = "Confirmed" THEN b.pax ELSE 0 END) AS `' . $row['agentId'] . '_LO`, ';
            }

            // Remove the trailing comma
            $agentColumns = rtrim($agentColumns, ', ');

            // Step 2: Construct the full SQL query
            $sql = "
                SELECT 
                    CONCAT(e.lName, ', ', e.fName, 
                        IF(e.mName IS NOT NULL AND e.mName != '', CONCAT(' ', LEFT(e.mName, 1)), '')) AS TeamOP,
                    f.origin, 
                    f.flightDepartureDate AS Start, 
                    f.returnDepartureDate AS End, 
                    f.availSeats AS FlightSeat, 
                    GREATEST(
                              (f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' AND b.bookingType = 'Package' 
                              THEN b.pax ELSE 0 END), 0)),0) AS AvailSeats, 
                    IF(
                        (f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)) < 0, 
                        ABS(f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)), 
                        0
                    ) AS AdditionalSeats,
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
                GROUP BY 
                    f.flightId
            ";

            // Step 3: Execute the query
            $result = $conn->query($sql);

            // Step 4: Display the results in HTML table
            if ($result->num_rows > 0) 
            {
              while ($row = $result->fetch_assoc()) 
              {
                echo '<tr>';
                echo '<td>' . $row['TeamOP'] . '</td>';
                echo '<td>' . $row['origin'] . '</td>';
                echo '<td>' . $row['Start'] . '</td>';
                echo '<td>' . $row['End'] . '</td>';
                echo '<td>' . $row['FlightSeat'] . '</td>';
                echo '<td>' . $row['AvailSeats'] . '</td>';
                echo '<td>' . $row['AdditionalSeats'] . '</td>';
                echo '<td>' . $row['Air+Land'] . '</td>';
                echo '<td>' . $row['LandOnly'] . '</td>';
                echo '<td>₱ ' . $row['WholesalePrice'] . '</td>';
                echo '<td>₱ ' . $row['RetailPrice'] . '</td>';
                echo '<td>₱ ' . $row['LandArrangement'] . '</td>';

                // Dynamically populate agent columns
                foreach ($row as $key => $value) 
                {
                  if (strpos($key, '_AL') !== false || strpos($key, '_LO') !== false) 
                  {
                    echo '<td>' . $value . '</td>';
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
</div>


<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
