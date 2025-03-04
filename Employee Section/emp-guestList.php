<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee - Transaction</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-transactionGuestList.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">

</head>

<body>

  <?php include '../Employee Section/includes/emp-sidebar.php' ?>

  <!-- Main Container -->
  <div class="main-container">
    <?php include '../Employee Section/includes/emp-navbar.php' ?>

    <div class="main-content">
      <div class="table-container">

        <div class="table-header">
          <div class="search-wrapper">
            <div class="search-input-wrapper">
              <input type="text" id="search" placeholder="Search here..">
            </div>
          </div>

          <!-- <div class="filter-field">
                <!-- <label for="status">Status:</label> 
                <div class="select-wrapper">
                  <select id="status">
                    <option value="All" disabled selected>Select Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Cancelled">Cancelled</option>
                  </select>
                </div>
              </div> -->

          <div class="second-header-wrapper">
            <div class="date-range-wrapper sorting-wrapper">
              <div class="select-wrapper">
                <select id="packages">
                  <option value="All" disabled selected>Select Branch</option>
                  <?php
                  // Execute the SQL query
                  $sql1 = "SELECT branchId, branchName FROM branch ORDER BY branchName ASC";
                  $res1 = $conn->query($sql1);

                  // Check if there are results
                  if ($res1->num_rows > 0) {
                    // Loop through the results and generate options
                    while ($row = $res1->fetch_assoc()) {
                      echo "<option value='" . $row['branchName'] . "'>" . $row['branchName'] . "</option>";
                    }
                  } else {
                    echo "<option value=''>No companies available</option>";
                  }
                  ?>
                </select>
              </div>
            </div>

            <!-- <div class="date-range-wrapper flightbooking-wrapper">
            <div class="date-range-inputs-wrapper">
              <div class="input-with-icon">
                <input type="text" class="datepicker" id="BookingStartDate" placeholder="Booking Date">
                <i class="fas fa-calendar-alt calendar-icon"></i>
              </div>
            </div>
          </div> -->

            <div class="date-range-wrapper flightbooking-wrapper">
              <div class="date-range-inputs-wrapper">
                <div class="input-with-icon">
                  <input type="text" class="datepicker" id="FlightStartDate" placeholder="Flight Date" readonly>
                  <i class="fas fa-calendar-alt calendar-icon"></i>
                </div>
              </div>
            </div>

            <div class="buttons-wrapper">
              <button id="clearSorting" class="btn btn-secondary">
                Clear Filters
              </button>
            </div>
          </div>

        </div>

        <div class="table-container">
          <table class="product-table" id="product-table" aria-describedby="product-table-caption">
            <thead>
              <tr>
                <th>Transaction No</th>
                <th>Guest Name</th>
                <th>Birthdate</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Nationality</th>
                <th>Departure Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Ensure valid database connection
              if (!isset($conn) || $conn->connect_error) {
                die("Database connection error: " . ($conn->connect_error ?? 'Unknown error.'));
              }

              // SQL query for guest details
              $sql = "SELECT 
              g.guestId, 
              g.transactNo, 
              g.fName, 
              g.mName, 
              g.lName, 
              g.suffix, 
              g.birthdate, 
              g.age, 
              g.sex, 
              g.nationality, 
              f.flightDepartureDate 
            FROM `guest` g
            JOIN `booking` b ON g.transactNo = b.transactNo
            JOIN `flight` f ON b.flightId = f.flightId
            WHERE b.status = 'Confirmed'
            ORDER BY f.flightDepartureDate ASC";

              // Execute the query
              $result = $conn->query($sql);

              // Check if query execution was successful
              if (!$result) {
                die("Query error: " . $conn->error);
              }

              // Fetch results and display rows
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  // Sanitize and format guest name
                  $guestName = htmlspecialchars($row['lName'], ENT_QUOTES, 'UTF-8') . ", " .
                    htmlspecialchars($row['fName'], ENT_QUOTES, 'UTF-8');
                  if (!empty($row['mName']) && strtolower((string) $row['mName']) !== 'n/a') {
                    $guestName .= " " . htmlspecialchars(substr($row['mName'], 0, 1), ENT_QUOTES, 'UTF-8') . ".";
                  }
                  if (!empty($row['suffix']) && strtolower((string) $row['suffix']) !== 'n/a') {
                    $guestName .= " " . htmlspecialchars($row['suffix'], ENT_QUOTES, 'UTF-8');
                  }

                  // Format dates
                  $birthdate = !empty($row['birthdate']) ? date('Y-m-d', strtotime($row['birthdate'])) : 'N/A';
                  $departureDate = !empty($row['flightDepartureDate']) ? date('Y-m-d', strtotime($row['flightDepartureDate'])) : 'N/A';

                  echo "<tr>
                    <td>" . htmlspecialchars($row['transactNo'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . $guestName . "</td>
                    <td>" . htmlspecialchars($birthdate, ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['age'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['sex'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['nationality'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($departureDate, ENT_QUOTES, 'UTF-8') . "</td>
                </tr>";
                }
              } else {
                echo "<tr><td colspan='7'>No records found.</td></tr>";
              }
              ?>
            </tbody>
          </table>


        </div>

        <div class="table-footer">
          <div class="pagination-controls">
            <button id="prevPage" class="pagination-btn">Previous</button>
            <span id="pageInfo" class="page-info">Page 1 of 10</span>
            <button id="nextPage" class="pagination-btn">Next</button>
          </div>
        </div>

      </div>
    </div>
  </div>


  <?php include '../Employee Section/includes/emp-scripts.php' ?>


  <!-- JQuery Datapicker -->
  <!-- <script>
  document.addEventListener("scroll", function () {
  const searchBar = document.querySelector(".search-bar");
  const scrollPosition = window.scrollY;

  // Add or remove the upward adjustment class based on scroll position
  if (scrollPosition > 70) { // Adjust the threshold as needed
    searchBar.classList.add("scrolled-upward");
  } else {
    searchBar.classList.remove("scrolled-upward");
  }
});
</script> -->

  <!-- DataTables #product-table -->
  <script>
    $(document).ready(function() {
      let flightStartDate = '';
      let bookingStartDate = '';

      const table = $('#product-table').DataTable({
        dom: 'rtip',
        language: {
          emptyTable: "No Transaction Records Available"
        },
        order: [
          [0, 'desc']
        ],
        scrollX: true,
        scrollY: '69vh',
        paging: true,
        pageLength: 15,
        autoWidth: false,
        columnDefs: [{
            targets: [1, 2, 3, 5, 6],
            orderable: false
          },
          {
            targets: 0,
            width: '6%'
          },
          {
            targets: 1,
            width: '14%'
          },
          {
            targets: 2,
            width: '14%'
          },
          {
            targets: 3,
            width: '18%'
          },
          {
            targets: 4,
            width: '14%'
          },
          {
            targets: 5,
            width: '8%'
          },
          {
            targets: 6,
            width: '13%',
            className: 'text-center'
          },
          {
            targets: 7,
            width: '13%',
            className: 'text-center'
          }
        ]
      });

      function updatePagination() {
        const info = table.page.info();
        $('#pageInfo').text(`Page ${info.page + 1} of ${info.pages}`);
        $('#prevPage').prop('disabled', info.page === 0);
        $('#nextPage').prop('disabled', info.page + 1 === info.pages);
      }

      $('#prevPage').on('click', function() {
        table.page('previous').draw('page');
      });

      $('#nextPage').on('click', function() {
        table.page('next').draw('page');
      });

      // Update pagination on every table redraw
      table.on('draw', function() {
        updatePagination();
      });

      $('#search').on('keyup', function() {
        table.search(this.value).draw();
      });

      $('#status').on('change', function() {
        table.column(6).search($(this).val() || '').draw();
      });

      $('#packages').on('change', function() {
        table.column(2).search($(this).val() || '').draw();
      });

      $("#FlightStartDate, #BookingStartDate").datepicker({
        dateFormat: "yy-mm-dd",
        showAnim: "fadeIn",
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2100",
        onSelect: function(dateText) {
          $(this).val(dateText);
          const columnIndex = $(this).attr('id') === "FlightStartDate" ? 7 : 4;
          table.column(columnIndex).search(dateText || '').draw();
        }
      });

      $('#clearSorting').on('click', function() {
        $('#search, #BookingStartDate, #FlightStartDate').val('');
        $('#status, #packages').val('All').change();
        flightStartDate = bookingStartDate = '';
        table.columns().search('').draw();
      });

      updatePagination();
    });
  </script>


  <!-- Table Head 
  <script>
    let lastScrollTop = 0;
    const header = document.querySelector('.table-wrapper thead');

    window.addEventListener('scroll', function() {
      let currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

      if (currentScrollTop > lastScrollTop) {
        // Scrolling down
        header.classList.add('has-border-top'); // Add the border-top
        header.style.transform = 'translateY(-5px)'; // Adjust upwards slightly
      } else {
        // Scrolling up
        header.classList.remove('has-border-top'); // Remove the border-top
        header.style.transform = 'translateY(0)'; // Reset to original position
      }

      lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop; // Prevent negative scroll position
    });
  </script> -->

  <!-- Clickable table rows script -->
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



</body>

</html>