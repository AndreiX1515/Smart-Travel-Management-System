<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>F.I.T | Table</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>

  <!-- Include Flatpickr -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  
  <!-- Components CSS -->
  <link rel="stylesheet" href="../Employee Section/assets/css/components/table-clean.css?v=<?php echo time(); ?>">

  <link rel="stylesheet" href="../Employee Section/assets/css/components/page-layout-tabs.css?v=<?php echo time(); ?>">

  <link rel="stylesheet" href="../Employee Section/assets/css/components/table-header.css?v=<?php echo time(); ?>">



  <!-- Page Specific CSS -->
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-transactionTableFIT.css?v=<?php echo time(); ?>">

  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">


 
  






<body>

  <?php include '../Employee Section/includes/emp-sidebar.php' ?>

  <!-- Main Container -->
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
            <h5 class="header-title">F.I.T</h5>
          </div>
        </div>

      </div>
    </div>

    <!-- Navbar Back Button Script -->
    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Employee Section/emp-dashboard.php'; // Replace with your actual URL
      });
    </script>

    <div class="main-content">

      <div class="table-wrapper">

        <div class="table-header">

          <div class="search-wrapper">
            <label class="" for="">Search: </label>
            <div class="search-input-wrapper">
              <input type="text" id="search" placeholder="Search here..">
            </div>
          </div>


          <div class="second-header-wrapper">

            <!-- Flight Date Picker -->
            <div class="sorting-wrapper aligned-item">
              <label for="FlightStartDate">Flight Date:</label>

              <div class="filter-date-inputs">
                <div class="filter-input-with-icon--input">
                  <input type="text" id="FlightStartDate" class="filter-input" placeholder="Flight Date" readonly>
                  <i class="fas fa-calendar-alt filter-calendar-icon"></i>
                </div>
              </div>

            </div>

            <!-- Package Selection -->
            <div class="sorting-wrapper aligned-item">

              <label for="packages">Select Package:</label>
              <div class="select-wrapper">
                <select class="package-select" id="packages">
                  <option value="All" disabled selected>Select Packages</option>
                  <option value="Autumn Tour Package">Autumn Tour</option>
                  <option value="Summer Tour Package">Summer Tour</option>
                  <option value="Spring Tour Package">Spring Tour</option>
                  <option value="Winter Tour Package">Winter Tour</option>
                  <option value="Regular Tour Package">Regular Tour</option>
                  <option value="Busan Tour Package">Busan Tour</option>
                </select>
              </div>

            </div>

            <!-- Clear Button -->
            <div class="aligned-item">
              <label style="opacity: 0;">Clear</label> <!-- invisible label to align height -->
              <button id="clearSorting" class="btn btn-secondary">
                Clear Filters
              </button>
            </div>

          </div>


        </div>

        <div class="navpills-container">

          <ul class="nav nav-pills nav-underline" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                type="button" role="tab" aria-controls="pills-home" aria-selected="true"> All
                <span class="badge">88</span>
              </button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                type="button" role="tab" aria-controls="pills-profile" aria-selected="false"> Pending
                <span class="badge">61</span>
              </button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact"
                type="button" role="tab" aria-controls="pills-contact" aria-selected="false"> Confirmed
                <span class="badge">27</span>
              </button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact"
                type="button" role="tab" aria-controls="pills-contact" aria-selected="false"> Cancelled
                <span class="badge">27</span>
              </button>
            </li>
          </ul>

        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="pills-tabContent">

          <div class="tab-pane fade show active" id="pills-home" role="tabpanel">

            <div class="table-container">

              <table id="fitBookingTable" class="table-clean">
                <thead>
                  <tr>
                    <th>Transaction No.</th>
                    <th>Contact Details</th>
                    <th>Package Name</th>
                    <th>No. of Nights</th>
                    <th>Hotel Details</th>
                    <th>Check-in/out</th>
                    <th>Guests</th>
                    <th>Price (₱)</th>
                    <th>Transaction Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql1 = "SELECT f.transactionNo AS `Transaction No`, 
                                  CONCAT(f.lName, ', ', f.fName, ' ', 
                                        IF(f.mName IS NOT NULL AND f.mName != '', CONCAT(LEFT(f.mName, 1), '.'), ''), 
                                        IF(f.suffix IS NOT NULL AND f.suffix != 'N/A', CONCAT(' ', f.suffix), '')) AS `Contact Name`,
                                  CONCAT(f.countryCode, ' ', f.contactNo) AS `Contact Details`,
                                  fp.packageName AS `Package Name`, DATEDIFF(f.returnDate, f.startDate) AS `No. of Nights`,
                                  fh.hotelName AS `Hotel Name`, fr.rooms AS `Room Type`, f.startDate AS `Check-in Date`,
                                  f.returnDate AS `Check-out Date`, f.pax AS `Total Guests`, f.phpPrice AS `Price`,
                                  f.bookingDate AS `Transaction Date`, f.status AS `Status`
                              FROM fit f
                              JOIN fitpackage fp ON fp.packageId = f.packageId
                              JOIN fithotel fh ON fh.hotelId = f.hotelId
                              JOIN fitrooms fr ON fr.roomId = f.roomId";

                  $res1 = $conn->query($sql1);

                  if ($res1->num_rows > 0) {
                    while ($row = $res1->fetch_assoc()) {
                      $transactNo = $row['Transaction No'];
                      $statusClass = match ($row['Status']) {
                        'Confirmed' => 'bg-success text-white',
                        'Cancelled' => 'bg-danger text-white',
                        'Pending' => 'bg-warning text-dark',
                        default => 'bg-secondary text-white',
                      };

                      echo "<tr data-url='agent-showFITBooking.php?id=" . htmlspecialchars($transactNo) . "'>
                              <td>{$transactNo}</td>
                              <td>
                                <div class='text-start'>
                                  <p><strong>Contact Name:</strong> {$row['Contact Name']}</p>
                                  <p><strong>Phone Number:</strong> {$row['Contact Details']}</p>
                                </div>
                              </td>
                              <td>{$row['Package Name']}</td>
                              <td>{$row['No. of Nights']}</td>
                              <td>
                                <div class='text-start'>
                                  <p><strong>Hotel:</strong> {$row['Hotel Name']}</p>
                                  <p><strong>Room Type:</strong> {$row['Room Type']}</p>
                                </div>
                              </td>
                              <td>
                                <div class='text-start'>
                                  <p><strong>Check In:</strong> {$row['Check-in Date']}</p>
                                  <p><strong>Check Out:</strong> {$row['Check-out Date']}</p>
                                </div>
                              </td>
                              <td><strong>{$row['Total Guests']}</strong></td>
                              <td>₱" . number_format($row['Price'], 2) . "</td>
                              <td>{$row['Transaction Date']}</td>
                              <td><span class='badge p-2 rounded-pill {$statusClass}'>{$row['Status']}</span></td>
                            </tr>";
                    }
                  } else {
                    echo "<tr>
                            <td colspan='10'>No bookings found</td>
                          </tr>";
                  }
                  ?>
                </tbody>
              </table>
              
            </div>

            <!-- Pagination Controls -->
            <div class="table-footer">

              <div class="last-update-wrapper accent-text">
                <span class="header-text">Last updated:</span>
                <span>April 30, 2025 - 10:15 AM</span>
              </div>


              <div class="">
                <button id="prevPage" class="btn-pagination">Previous</button>
                <span id="pageInfo" class="page-info"></span>
                <button id="nextPage" class="btn-pagination">Next</button>
              </div>
            </div>

          </div>

          <!-- Other Tab Content -->
          <div class="tab-pane fade" id="pills-profile" role="tabpanel"></div>
          <div class="tab-pane fade" id="pills-confirmed" role="tabpanel"></div>
          <div class="tab-pane fade" id="pills-cancelled" role="tabpanel"></div>

        </div>

      </div>

    </div>

  </div>

  <?php include '../Employee Section/includes/emp-scripts.php' ?>

  <!-- Data tables Script-->
  <script>
    $(document).ready(function () {
      $('#fitBookingTable').DataTable({
        autoWidth: true,
        searching: false, // ✅ disables the search bar
        columnDefs: [
          { width: '10%', targets: 0 },  // Transaction No
          { width: '15%', targets: 1 },  // Contact Details
          { width: '12%', targets: 2 },  // Package Name
          { width: '8%', targets: 3 },   // Nights
          { width: '15%', targets: 4 },  // Hotel Details
          { width: '15%', targets: 5 },  // Check-in/out
          { width: '5%', targets: 6 },   // Guests
          { width: '10%', targets: 7 },  // Price
          { width: '10%', targets: 8 },  // Transaction Date
          { width: '10%', targets: 9 }   // Status
        ],
        scrollX: true
      });
    });

  </script>

  <script>
    flatpickr("#FlightStartDate", {
      dateFormat: "Y-m-d",
      allowInput: true,
      defaultDate: null,
      yearSelectorType: "dropdown", // Enable dropdown for year
      minDate: `${new Date().getFullYear() - 10}-01-01`,
      maxDate: `${new Date().getFullYear() + 10}-12-31`,
      onChange: function (selectedDates, dateStr) {
        $('#FlightStartDate').val(dateStr);
        table.column(11).search(dateStr || '').draw();
      }
    });

  </script>









  <!-- Row Click Selection JS -->
  <!-- <script>
document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll("tr[data-url]").forEach(function(row) {
      row.addEventListener("click", function() {
          const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

          console.log("Transaction Number: ", transactionNumber); // Debugging line

          // Use AJAX to send the transaction number to the server
          $.ajax({
              url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
              type: 'POST',
              data: { transaction_number: transactionNumber },
              success: function(response) {
                  console.log("Response: ", response); // Debugging line

                  // Redirect to the next page after successfully setting the session
                  window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
              },
              error: function(xhr, status, error) {
                  console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
              }
          });
      });
  });
});
</script> -->

</body>

</html>