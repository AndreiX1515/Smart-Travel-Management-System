<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Transaction</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-transaction.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
    
</head>
<body>


<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
   <?php include '../Employee Section/includes/emp-navbar.php' ?>
   
  <div class="main-content">
   <div class="table-container">
     <!-- <div class="table-tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
           <li class="nav-item" role="presentation">
             <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Home</button>
           </li>
           <li class="nav-item" role="presentation">
             <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Profile</button>
           </li>
           <li class="nav-item" role="presentation">
             <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Contact</button>
           </li>
           <li class="nav-item" role="presentation">
             <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false" disabled>Disabled</button>
           </li>
         </ul>
      </div> -->

      <div class="table-header">
       <div class="header-left">
         <input
           type="text"
           placeholder="Search..."
           class="search-input"
         />
         <button class="btn-search">
           <i class="fas fa-search"></i>
         </button>
       </div>


    <!-- <div class="header-right">
      
    </div>  -->
    
  </div>

    <div class="table-subheader ">
      <div class="d-flex justify-content-between align-items-center">
          <!-- Dropdown Button -->
          <div class="dropdown">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  All
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="#">5</a></li>
                <li><a class="dropdown-item" href="#">10</a></li>
                <li><a class="dropdown-item" href="#">50</a></li>
                <li><a class="dropdown-item" href="#">100</a></li>
              </ul>
          </div>

          <!-- Date Range, Filter, Add New Column Buttons -->
          <div>
              <button class="btn btn-outline-secondary me-2">
                  <i class="fas fa-calendar-alt"></i> Date Range
              </button>
              <button class="btn btn-outline-secondary me-2">
                  <i class="fas fa-filter"></i> Filter
              </button>
              <!-- <button class="btn btn-outline-secondary">
                  <i class="fas fa-plus"></i> Add new column
              </button> -->
          </div>
      </div>
  </div>

   <div class="table-wrapper">
     <table class="">
       <thead>
         <tr>
           <th>Transact No</th>
           <th>Package Name</th>
           <th>Flight Date</th>
           <th>Booking Date</th>
           <th>Total Pax</th>
           <th>Package Price</th>
           <th>Request Cost</th>
           <th>Amount to be paid</th>
           <th>Amount Paid</th>
           <th>Remaining Balance</th>
           <th>Status</th>
         </tr>
       </thead>
       <tbody>
        <?php
          // SQL query
          $sql = "SELECT b.transactNo, CONCAT(f.flightDepartureDate, ' - ', f.returnDepartureDate) AS FlightDate, 
                          p.packageName AS PackageName, b.bookingDate AS BookingDate, b.pax AS TotalPax, b.totalPrice AS PackagePrice, 
                          SUM(CASE WHEN r.requestStatus = 'Confirmed' THEN r.requestCost ELSE 0 END) AS RequestCost,
                          (b.totalPrice + SUM(CASE WHEN r.requestStatus = 'Confirmed' THEN r.requestCost ELSE 0 END)) AS AmountToPaid,
                          SUM(CASE WHEN y.paymentStatus = 'Approved' THEN y.amount ELSE 0 END) AS AmountPaid,
                          ((b.totalPrice + SUM(CASE WHEN r.requestStatus = 'Confirmed' THEN r.requestCost ELSE 0 END)) - 
                          SUM(CASE WHEN y.paymentStatus = 'Approved' THEN y.amount ELSE 0 END)) AS Balance
                  FROM 
                    booking b
                  JOIN 
                    flight f ON f.flightId = b.flightId
                  JOIN 
                    package p ON p.packageId = b.packageId
                  LEFT JOIN 
                    request r ON r.transactNo = b.transactNo
                  LEFT JOIN 
                    payment y ON y.transactNo = b.transactNo
                  GROUP BY 
                    b.transactNo, f.flightDepartureDate, p.packageName, b.totalPrice, b.bookingDate, b.pax";

          // Execute the query
          $result = $conn->query($sql);

          // Check if there are results
          if ($result->num_rows > 0) 
          {
            while ($row = $result->fetch_assoc()) 
            {
              // Determine the status based on Balance
              $status = ($row['Balance'] <= 0) ? 'Fully Paid' : 'Pending';

              // Output each row as a table row
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['transactNo']) . "</td>";
              echo "<td>" . htmlspecialchars($row['PackageName']) . "</td>";
              echo "<td>" . htmlspecialchars($row['FlightDate']) . "</td>";
              echo "<td>" . htmlspecialchars($row['BookingDate']) . "</td>";
              echo "<td>" . htmlspecialchars($row['TotalPax']) . "</td>";
              echo "<td>₱ " . number_format($row['PackagePrice'], 2) . "</td>";
              echo "<td>₱ " . number_format($row['RequestCost'], 2) . "</td>";
              echo "<td>₱ " . number_format($row['AmountToPaid'], 2) . "</td>";
              echo "<td>₱ " . number_format($row['AmountPaid'], 2) . "</td>";
              echo "<td>₱ " . number_format(max($row['Balance'], 0), 2) . "</td>"; // Ensure Balance doesn't go negative
              echo "<td>" . htmlspecialchars($status) . "</td>";
              echo "</tr>";
            }
          } 
          else 
          {
            echo "<tr><td colspan='11' style='text-align: center;'>No records found.</td></tr>";
          }
        ?>
        
       </tbody>
     </table>
   </div>
</div>

<!-- <div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">...</div>
  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">...</div>
  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">...</div>
  <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">...</div>
</div> -->

   </div>
</div>


<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
