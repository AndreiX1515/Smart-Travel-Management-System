<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">

</head>

<body>
<?php include '../Agent Section/includes/sidebar.php'; ?> 

<div class="main-content" id="mainContent">
  <?php include '../Agent Section/includes/navbar.php'; ?>

  <div class="content-wrapper">
  <div class="table-container">
   <div class="search-bar">
       <div class="left-side">
           <div class="search-input mb-3">
               <label for="search">Search</label>
               <input type="text" id="search" class="form-control mt-2" placeholder="Search">
           </div>
       </div>

       <div class="right-side">
           <div class="filter-group">
               <div class="filter-field mb-3 d-flex flex-column">
                   <label for="packages">Packages</label>
                   <select id="packages" class="custom-select mt-2">
                       <option>Packages</option>
                       <option>All</option>
                   </select>
               </div>

               <div class="filter-field mb-3 d-flex flex-column">
                   <label for="category">Category</label>
                   <select id="category" class="custom-select mt-2">
                       <option>Category</option>
                       <option>All</option>
                   </select>
               </div>

               <div class="filter-field mb-3 d-flex flex-column">
                   <label for="status">Status</label>
                   <select id="status" class="custom-select mt-2">
                       <option>Status</option>
                       <option>All</option>
                   </select>
               </div>

               <div class="filter-field mb-3 d-flex flex-column">
                   <label for="date-range">Date Range (Start - End)</label>
                   <div class="input-group date-range-picker mt-2">
                       <input type="date" class="form-control" id="startDate" placeholder="Start date">
                       <span class="input-group-text">→</span>
                       <input type="date" class="form-control" id="endDate" placeholder="End date">
                   </div>
               </div>

               <div class="filter-field d-flex justify-content-center">
                   <button class="search-button mt-3"><i class="fa-solid fa-magnifying-glass"></i></button>
               </div>
           </div>
       </div>
   </div>

   <!-- <hr style="border: 1px solid grey; margin: 5px 0 20px 0;"> -->

   <div class="table-actions">
       <div class="show-column">
           <span>Show: </span>
           <select>
               <option>10</option>
               <option>20</option>
               <option>30</option>
               <option>All</option>
           </select>
       </div>

       <!-- <div class="pagination">
           <button id="prev-page" class="pagination-button" onclick="prevPage()">&#8249;</button>
           <button class="pagination-number" onclick="goToPage(1)">1</button>
           <button class="pagination-number" onclick="goToPage(2)">2</button>
           <button id="page-info" class="pagination-number active">3</button>
           <span class="pagination-ellipsis">...</span>
           <button class="pagination-number" onclick="goToPage(10)">10</button>
           <button id="next-page" class="pagination-button" onclick="nextPage()">&#8250;</button>
       </div> -->
   </div>

   <table class="product-table">
       <thead>
           <tr>
               <th>ID</th>
               <th>Contact Person Name</th>
               <th>Contact Person Email</th>
               <th>Contact Person Phone Number</th>
               <th>Package Name</th>
               <th>Booking Date</th>
               <th>Flight Date</th>
               <th>Total Pax</th>
               <th>Status</th>
               <th></th>
           </tr>
       </thead>
       <tbody>
           <?php
           $sql1 = "SELECT
               b.transactNo AS `T.N`,
               p.packageName AS `PACKAGE`,
               b.bookingDate AS `TRANSACTION DATE`,
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
               b.email AS `CONTACT EMAIL`,
               CONCAT(b.countryCode, b.contactNo) AS `CONTACT PHONE`,
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
               b.agentId = $agentId AND b.status = 'Pending'
           ORDER BY 
               b.transactNo DESC LIMIT 10";

           $res1 = $conn->query($sql1);

           if ($res1->num_rows > 0) {
               while ($row = $res1->fetch_assoc()) {
                   echo "<tr>
                       <td>{$row['T.N']}</td>
                       <td>{$row['CONTACT NAME']}</td>
                       <td>{$row['CONTACT EMAIL']}</td>
                       <td>{$row['CONTACT PHONE']}</td>
                       <td>{$row['PACKAGE']}</td>
                       <td>{$row['TRANSACTION DATE']}</td>
                       <td>{$row['FLIGHT DATE']}</td>
                       <td>{$row['TOTAL PAX']}</td>
                       <td>{$row['STATUS']}</td>
                   </tr>";
               }
           } else {
               echo "<tr><td colspan='9'>No bookings found</td></tr>";
           }
           ?>
       </tbody>
   </table>

   <div class="table-footer border-0">
       <div class="total-records">Total Records: <?php echo $res1->num_rows; ?></div>
       <div class="footer-pagination">
           <button class="pagination-button" onclick="prevPage()">&#8249; Prev</button>
           <span>Page 1 of 10</span>
           <button class="pagination-button" onclick="nextPage()">Next &#8250;</button>
       </div>
   </div>
</div>

  </div>
</div>

    <?php require "../Agent Section/scripts/script.php"; ?>
    <?php require "../Agent Section/includes/scripts.php"; ?>
</body>
</html>
