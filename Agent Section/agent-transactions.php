<?php
  // Start session
  require "../conn.php";
  date_default_timezone_set('Asia/Taipei'); // Set the timezone to Taipei
  $current_date = date('D, F d, Y'); // Format: "Tue, January 01, 2024"
  // Start session
  date_default_timezone_set('Asia/Taipei'); // Set the timezone to Taipei
  $current_date = date('D, F d, Y'); // Format: "Tue, January 01, 2024"
?>

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
  <?php include '../Agent Section/includes/sidebar.php' ?>

  <div class="main-content" id="mainContent">

    <?php 
    
    include '../Agent Section/includes/navbar.php';
    require "../conn.php";
    date_default_timezone_set('Asia/Taipei'); // Set the timezone to Taipei
    $current_date = date('D, F d, Y'); // Format: "Tue, January 01, 2024"
    // Start session
    date_default_timezone_set('Asia/Taipei'); // Set the timezone to Taipei
    $current_date = date('D, F d, Y'); // Format: "Tue, January 01, 2024"
    ?>

   

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

      <hr style="border: 1px solid grey; margin: 5px 0 20px 0;">

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

        <div class="pagination">
          <button id="prev-page" class="pagination-button" onclick="prevPage()">&#8249;</button>
          <button class="pagination-number" onclick="goToPage(1)">1</button>
          <button class="pagination-number" onclick="goToPage(2)">2</button>
          <button id="page-info" class="pagination-number active">3</button>
          <span class="pagination-ellipsis">...</span>
          <button class="pagination-number" onclick="goToPage(10)">10</button>
          <button id="next-page" class="pagination-button" onclick="nextPage()">&#8250;</button>
        </div>
      </div>

      <table class="product-table">
        <thead>
          <tr>
            <!-- <th><input type="checkbox"></th> -->
            <th>T.N</th>
            <th>Contact Person Name</th>
            <th>Contact Person Email</th>
            <th>Contact Person Phone Number</th>
            <th>Package Name</th>
            <th>Booking Date</th>
            <th>Flight Date</th>
            <th>Total Pax</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql1 = "SELECT
                  b.transactNo AS `T.N`,
                  p.packageName AS `PACKAGE`,
                  DATE_FORMAT(b.bookingDate, '%M %d, %Y %h:%i %p') AS `TRANSACTION DATE`,  -- Format as mm-dd-yy hh:mm with abbreviated month
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
                  b.email AS `CONTACT EMAIL`,  -- Assuming the email is stored in the booking table
                  CONCAT(b.countryCode, b.contactNo) AS `CONTACT PHONE`,  -- Concatenating countryCode and contactNo
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
                  b.agentId = '$agentId' AND b.status = 'Pending'
                ORDER BY 
                  b.transactNo DESC LIMIT 10";
 
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
                        <td>{$row['CONTACT NAME']}</td>
                        <td>{$row['CONTACT EMAIL']}</td>
                        <td>{$row['CONTACT PHONE']}</td>
                        <td>{$row['PACKAGE']}</td>
                        <td>{$row['TRANSACTION DATE']}</td>
                        <td>{$row['FLIGHT DATE']}</td>
                        <td>{$row['TOTAL PAX']}</td>
                        <td>{$row['STATUS']}</td>
                        <td>

                        </td>
                      </tr>";
              }
            } 
            else 
            {
              // If no records found
              echo "<tr><td colspan='9'>No bookings found</td></tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>

 
  <script> 
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');

    // Prevent selecting an end date earlier than the start date
    startDate.addEventListener('change', function () {
        endDate.min = this.value;
    });

    endDate.addEventListener('change', function () {
        startDate.max = this.value;
    });
  </script>

  <?php require "../Agent Section/scripts/script.php"; ?>

  <?php require "../Agent Section/includes/scripts.php"; ?>

</body>

</html>