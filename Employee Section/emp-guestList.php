<?php session_start(); ?>

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
                <!-- <span class="icon">🔍</span> -->
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
                <input type="text" class="datepicker" id="FlightStartDate" placeholder="Flight Date">
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
        <table class="product-table" id="product-table">
          <thead>
            <tr>
              <!-- <th rowspan="2">Guest ID</th> -->
              <th rowspan="2">Transaction No</th>
              <th rowspan="2">Guest Name</th>
              <th rowspan="2">Birthdate</th>
              <th rowspan="2">Age</th>
              <th rowspan="2">Sex</th>
              <th rowspan="2">Nationality</th>
              <th colspan="2" class="text-center">Flight Dates</th>
            </tr>
            <tr>
              <th>Departure</th>
              <th>Return</th>
            </tr>
          </thead>
          <tbody>
            <?php
              // SQL query for SOA
              $sql = "SELECT g.guestId AS guestId, g.transactNo AS transactNo, g.fName AS fname, g.mName AS mName, g.lName AS lName, 
                        g.suffix AS suffix, g.birthdate AS birthdate, g.age AS age, g.sex AS sex, g.Nationality AS Nationality, 
                        f.flightDepartureDate AS departureDate, f.returnArrivalDate AS returnDate
                      FROM `guest` g
                      INNER JOIN `booking` b ON g.transactNo = b.transactNo
                      INNER JOIN `flight` f ON b.flightId = f.flightId
                      WHERE b.status = 'Confirmed'
                      ORDER BY f.flightDepartureDate ASC";

              // Execute the query
              $result = $conn->query($sql);

              // Check if there are results
              if ($result->num_rows > 0) 
              {
                // Loop through the results and display them
                while ($row = $result->fetch_assoc()) 
                {
                  // Format the guest name with proper handling for middle name and suffix
                  $guestName = htmlspecialchars($row['lName']) . ", " . htmlspecialchars($row['fname']);
                  if (!empty($row['mName']) && $row['mName'] !== 'N/A') 
                  {
                    $guestName .= " " . htmlspecialchars(substr($row['mName'], 0, 1)) . ".";
                  }
                  if (!empty($row['suffix']) && $row['suffix'] !== 'N/A') 
                  {
                    $guestName .= " " . htmlspecialchars($row['suffix']);
                  }

                  // Format the dates for departure and return flight
                  $departureDate = date('Y-m-d', strtotime($row['departureDate']));
                  $returnDate = date('Y-m-d', strtotime($row['returnDate']));

                  // Output the row data in HTML table format
                  echo "<tr>
                          <td>" . htmlspecialchars($row['transactNo']) . "</td>
                          <td>" . $guestName . "</td>
                          <td>" . htmlspecialchars($row['birthdate']) . "</td>
                          <td>" . htmlspecialchars($row['age']) . "</td>
                          <td>" . htmlspecialchars($row['sex']) . "</td>
                          <td>" . htmlspecialchars($row['Nationality']) . "</td>
                          <td>" . $departureDate . "</td>
                          <td>" . $returnDate . "</td>
                        </tr>";
                }
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
<script>
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
</script>

<!-- DataTables #product-table -->
<script>
$(document).ready(function () {
      const table = $('#product-table').DataTable({
        dom: 'rtip',  // Use only the relevant table elements
        language: {
            emptyTable: "No Transaction Records Available"
        },
        order: [[0, 'desc']],  // Default sorting by Transaction ID (descending)
        scrollX: false,
        scrollY: '69vh',  // Set a fixed height for the table (adjust as necessary)
        paging: true,  // Enable pagination
        pageLength: 15,  // Set the number of rows per page
        autoWidth: false,
        autoHeight: false,  // Prevent automatic height adjustment

        // Disable sorting for specific columns
        columnDefs: [
          {
            targets: [1, 2, 3,  5, 6,], // Disable sorting for 2nd and 4th columns
            orderable: false
          }
        ]
    });


    // Search Functionality
    $('#search').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Update the custom pagination buttons and page info
    function updatePagination() {
      const info = table.page.info();
      const currentPage = info.page + 1; // Get current page number (1-indexed)
      const totalPages = info.pages; // Get total pages

      // Update page info text
      $('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);

      // Enable/Disable prev and next buttons based on current page
      $('#prevPage').prop('disabled', currentPage === 1);
      $('#nextPage').prop('disabled', currentPage === totalPages);
    }

    // Custom pagination button click events
    $('#prevPage').on('click', function() {
      table.page('previous').draw('page');
      updatePagination();
    });

    $('#nextPage').on('click', function() {
      table.page('next').draw('page');
      updatePagination();
    });

    // Initialize pagination on first load
    updatePagination();

    // Status Filter
    $('#status').on('change', function () {
        const selectedStatus = $(this).val();
        table.column(8).search(selectedStatus || '').draw();
    });

    // Package Filter
    $('#packages').on('change', function () {
        const selectedPackage = $(this).val();
        table.column(2).search(selectedPackage || '').draw();
    });

    // Booking Date Filter with value change
    $('#BookingStartDate').on('change', function () {
      const selectedBookingDate = $(this).val();  // Get the selected value directly from the input field
      console.log("Booking Date Filter:", selectedBookingDate);  // Log the selected booking date
      table.column(3).search(selectedBookingDate || '').draw();  // Column 4 (index starts at 0)
    });

    // Flight Date Filter with value change
    $('#FlightStartDate').on('change', function () {
      const selectedFlightDate = $(this).val();  // Get the selected value directly from the input field
      console.log("Flight Date Filter:", selectedFlightDate);  // Log the selected flight date
      table.column(3).search(selectedFlightDate || '').draw();  // Column 5 (index starts at 0)
    });

    // Apply datepicker and input validation for FlightStartDate
    $("#FlightStartDate").datepicker({
        dateFormat: "yy-mm-dd", // Set the format to MM-DD-YYYY
        showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
        changeMonth: true, // Allow the month to be changed from the dropdown
        changeYear: true,  // Allow the year to be changed from the dropdown
        yearRange: "1900:2100", // Set a range of years (optional)
        onSelect: function(dateText) {
            // When a date is selected, update the input field with the date
            $(this).val(dateText);
            flightStartDate = dateText; // Store the selected date
            console.log("FlightStartDate Selected Date (onSelect): " + dateText);
            table.column(3).search(flightStartDate || '').draw();  // Column 5 (index starts at 0)
        }
    });


    // Apply datepicker and input validation for BookingStartDate
    $("#BookingStartDate").datepicker({
        dateFormat: "mm-dd-yy", // Set the format to MM-DD-YYYY
        showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
        changeMonth: true, // Allow the month to be changed from the dropdown
        changeYear: true,  // Allow the year to be changed from the dropdown
        yearRange: "1900:2100", // Set a range of years (optional)
        onSelect: function(dateText) {
            // When a date is selected, update the input field with the date
            $(this).val(dateText);
            bookingStartDate = dateText; // Store the selected date
            console.log("FlightStartDate Selected Date (onSelect): " + dateText);
            table.column(4).search(bookingStartDate || '').draw();  // Column 5 (index starts at 0)
        }
    });

    // BookingStartDate Input Validation and Formatting
    $("#BookingStartDate").on("input", function () {
        var value = $(this).val();

        // Remove non-numeric and non-dash characters
        value = value.replace(/[^\d-]/g, '');

        // Automatically add dashes in the correct places if necessary
        if (value.length > 2 && value.charAt(2) !== '-') {
            value = value.substring(0, 2) + '-' + value.substring(2);
        }
        if (value.length > 5 && value.charAt(5) !== '-') {
            value = value.substring(0, 5) + '-' + value.substring(5);
        }

        // Limit the total input length to 10 characters (MM-DD-YYYY)
        if (value.length > 10) {
            value = value.substring(0, 10);
        }

        // Update the input field value
        $(this).val(value);

        // Reset or update the bookingStartDate variable
        if (value === "") {
            bookingStartDate = ""; // Reset the variable if the input is cleared
        } else {
            bookingStartDate = value; // Update the variable with the formatted value
        }

        // Update the table column search
        table.column(5).search(bookingStartDate || '').draw(); // Column 5 (index starts at 0)

        console.log("BookingStartDate Input Value (on input): " + value);
    });

    // Clear All Filters
    $('#clearSorting').on('click', function () {
        // Clear search field
        $('#search').val('');
        table.search('').draw();

        // Clear status dropdown
        $('#status').val('All').change();

        // Clear packages dropdown
        $('#packages').val('All').change();

         // Explicitly reset the variables
         flightStartDate = '';
        bookingStartDate = '';

        // Clear date fields
        $('#BookingStartDate').val('').trigger('change'); // Reset and trigger input for BookingStartDate
        $('#FlightStartDate').val('').trigger('change');  // Reset and trigger input for FlightStartDate

       

        // Redraw the table
        table.draw();
    });


});
</script>



<!-- Table Head  -->
<script> 
  let lastScrollTop = 0;
  const header = document.querySelector('.table-wrapper thead');

  window.addEventListener('scroll', function() 
  {
    let currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScrollTop > lastScrollTop) 
    {
      // Scrolling down
      header.classList.add('has-border-top'); // Add the border-top
      header.style.transform = 'translateY(-5px)'; // Adjust upwards slightly
    } 
    else 
    {
      // Scrolling up
      header.classList.remove('has-border-top'); // Remove the border-top
      header.style.transform = 'translateY(0)'; // Reset to original position
    }

    lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop; // Prevent negative scroll position
  });
</script>

<!-- Clickable table rows script -->
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



</body>
</html>
