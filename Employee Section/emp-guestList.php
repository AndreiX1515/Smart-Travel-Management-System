<?php session_start(); ?>

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

      <div class="table-subheader">
        <div class="left-side-wrapper mt-2">
          <div class="d-flex flex-row gap-3">
            <div class="filter-wrapper">
              <label for="" class="" style="margin-bottom: 11px;">Status</label>
              <select id="statusDropdown" class="status-dropdown">
                <option value="Pending">Pending</option>
                <option value="Confirmed">Confirmed</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>

            <div class="flight-dateRange-wrapper">
              <label for="" class="">Flight Date (Departure)</label>
              <div class="flight-field-wrapper">
                <input type="date" class="form-control" id="flightStartDate">
                <span class="mx-2">to</span>
                <input type="date" class="form-control" id="flightEndDate">
              </div>
            </div>

            <!-- Booking Date Range Picker -->
            <div class="booking-dateRange-wrapper">
              <label for="bookingStartDate" class="">Booking Date</label>
              <div class="d-flex align-items-center">
                <input type="date" class="form-control" id="bookingStartDate">
                <span class="mx-2">to</span>
                <input type="date" class="form-control" id="bookingEndDate">
              </div>
            </div>
          </div>

          <div class="button-wrappers">
            <button class="btn btn-outline-secondary" id="clearFiltersButton">Clear Filters</button>
            <button class="btn btn-primary" id="clearFiltersButton"><i class="fa-solid fa-user-plus"></i> Add Booking</button>
          </div>
        </div>

        <div class="search-wrapper">
          <label for="tableSearchInput" class="search-label">Search:</label>

          <input type="text" id="tableSearchInput" placeholder="Search..." class="form-control search-input" oninput="toggleClearButton(this)"/>

          <button type="button" class="clear-button" onclick="clearInput(this)">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <div class="table-wrapper">
        <table class="table-transaction table-striped">
          <thead>
            <tr>
                <th rowspan="2">Guest ID</th>
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
              // SQL query for fetching data
              $sql = "SELECT g.guestId as guestId, g.transactNo as transactNo, f.flightId as flightId, g.fName as fname, 
                        g.mName as mName, g.lName as lName, g.suffix as suffix, g.birthdate as birthdate, g.age as age, g.sex as sex, 
                        g.Nationality as Nationality, f.flightDepartureDate as departureDate, f.returnArrivalDate as returnDate
                      FROM guest g
                      JOIN booking b ON g.transactNo = b.transactNo
                      JOIN flight f ON f.flightId = b.flightId
                      ORDER BY guestId, f.flightDepartureDate";

              // Execute the query and check for errors
              if ($result = $conn->query($sql)) {

                  // Check if the query returns any rows
                  if ($result->num_rows > 0) {

                      // Loop through the results and display them
                      while ($row = $result->fetch_assoc()) {

                          // Format the guest name with proper handling for middle name and suffix
                          $guestName = htmlspecialchars($row['lName']) . ", " . htmlspecialchars($row['fname']);
                          if (!empty($row['mName']) && $row['mName'] !== 'N/A') {
                              $guestName .= " " . htmlspecialchars(substr($row['mName'], 0, 1)) . ".";
                          }
                          if (!empty($row['suffix']) && $row['suffix'] !== 'N/A') {
                              $guestName .= " " . htmlspecialchars($row['suffix']);
                          }

                          // Format the dates for departure and return flight
                          $departureDate = date('Y-m-d', strtotime($row['departureDate']));
                          $returnDate = date('Y-m-d', strtotime($row['returnDate']));

                          // Output the row data in HTML table format
                          echo "<tr>
                                  <td>" . htmlspecialchars($row['guestId']) . "</td>
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
                  } else {
                      // No results found, display a message
                      echo "<tr><td colspan='9' class='text-center'>No data available</td></tr>";
                  }
              } else {
                  // Query failed, display an error message
                  echo "<tr><td colspan='9' class='text-center'>Error fetching data: " . $conn->error . "</td></tr>";
              }
              ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Table Transaction DataTables and Sorting Functions -->
<script>
 $(document).ready(function() 
 {
    var table = $('.table-transaction').DataTable(
    {
      paging: true,
      searching: true,
      ordering: true,
      info: true,
      pageLength: 12, // Set the number of rows per page
      language: 
      {
        emptyTable: "No Transaction Records Available"
      },
      dom: '<"top"f>rt<"bottom"p><"clear">', // Custom DOM layout to display only the table and pagination
      initComplete: function () 
      {
        $(".dataTables_info").hide(); // Hide the entries (e.g., "Showing 1 to 10 of 100 entries")
        $(".dataTables_filter").hide(); // Hide the search field
      },
      columnDefs: 
      [
        {
          // Assuming the flight date is in the fourth column (index 3)
          targets: 3, // Change this index to your flight date column
          render: function(data, type, row) 
          {
            var date = new Date(data); // Convert numeric value (timestamp) to Date object

            if (type === 'display' || type === 'filter') 
            {
              // Return the formatted date as YYYY/MM/DD
              var year = date.getFullYear();
              var month = (date.getMonth() + 1).toString().padStart(2, '0');
              var day = date.getDate().toString().padStart(2, '0');
              return year + '/' + month + '/' + day; // YYYY/MM/DD
            }

            // For sorting, return the raw numeric value (timestamp) so DataTables can sort it correctly
            return data;
          }
        }
      ]
    });

    // Function to filter the table by date range
    function filterByFlightDate() 
    {
      var startDate = $('#flightStartDate').val();
      var endDate = $('#flightEndDate').val();

      // Convert start and end dates to timestamps (numeric values) to ensure the filter works properly
      var startTimestamp = new Date(startDate).getTime();
      var endTimestamp = new Date(endDate).getTime();

      // Apply the date filter to the DataTable using raw timestamp values
      table.column(3).search(function(settings, data, dataIndex) 
      {
        var rowDate = new Date(data).getTime(); // Convert each row's date to timestamp
        return rowDate >= startTimestamp && rowDate <= endTimestamp;
      }).draw(); // Redraw the table after applying the filter
    }

    // Event listeners to trigger the filter when the user selects dates
    $('#flightStartDate, #flightEndDate').on('change', function() 
    {
      filterByFlightDate();
    });

    // Optional: Apply sorting by flight date after applying the filter
    table.order([3, 'asc']).draw();  // Assuming the flight date column is at index 3
  });

  // Booking Date Range Sorting
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) 
  {
    const bookingStartDate = $('#bookingStartDate').val(); // Booking Start Date
    const bookingEndDate = $('#bookingEndDate').val(); // Booking End Date
    const bookingDate = data[4]; // Assuming Booking Date is in column index 4

    const booking = bookingDate ? new Date(bookingDate) : null;
    const startDate = bookingStartDate ? new Date(bookingStartDate) : null;
    const endDate = bookingEndDate ? new Date(bookingEndDate) : null;

    // Booking Date filtering logic
    if ((!startDate || (booking && booking >= startDate)) && (!endDate || (booking && booking <= endDate))) 
    {
      return true; // Row matches Booking Date filter
    }
    return false; // Otherwise, hide this row
  });

  // Handle Booking Date Range Filtering on change
  $('#bookingStartDate, #bookingEndDate').on('change', function() 
  {
    // Redraw table to apply booking date filters
    table.draw();
  });


  // Handle custom "Items per Page" dropdown
  $('#itemsPerPageDropdown .dropdown-item').on('click', function() 
  {
    var pageSize = $(this).data('page-size');
    table.page.len(pageSize).draw();
  });

  // Function to filter the table by date range
  function filterByFlightDate() 
  {
    var startDate = $('#flightStartDate').val();
    var endDate = $('#flightEndDate').val();

    // Apply the date filter to the DataTable
    table.column(3).search(startDate + ' to ' + endDate).draw(); // assuming flight date is in column 1 (adjust as needed)
  }

  // Custom search functionality
  $('#tableSearchInput').on('input', function() 
  {
    table.search(this.value).draw();
  });

  // Clear all filters functionality
  $('#clearFiltersButton').on('click', function() 
  {
    $('#tableSearchInput').val('');
    $('#flightStartDate').val('');
    $('#flightEndDate').val('');
    $('#bookingStartDate').val('');
    $('#bookingEndDate').val('');
    $('#itemsPerPageDropdown .dropdown-item').removeClass('active');
    table.search('').draw();
    table.page.len(10).draw();
  });


  // // Handle Booking Date Range Filtering
  // $('#bookingStartDate, #bookingEndDate').on('change', function() {
  //     var bookingStartDate = $('#bookingStartDate').val();
  //     var bookingEndDate = $('#bookingEndDate').val();
  //     if (bookingStartDate && bookingEndDate) {
  //         table.column(4).search(bookingStartDate + ' to ' + bookingEndDate).draw();
  //     }
  // });

  // Clear filters functionality
  $('#clearFiltersButton').on('click', function() 
  {
    $('#flightStartDate').val('');
    $('#flightEndDate').val('');
    $('#bookingStartDate').val('');
    $('#bookingEndDate').val('');
    $('#tableSearchInput').val('');
    table.search('').columns().search('').draw(); // Reset the search and clear column filters
  });

  // Toggle clear button visibility
  function toggleClearButton(input) 
  {
    const clearButton = input.nextElementSibling; // Get the button next to the input
    clearButton.style.display = input.value ? "block" : "none";
  }

  // Clear the input field and reset search when clicked (same as clearFiltersButton)
  function clearInput(button) 
  {
    const input = button.previousElementSibling; // Get the input field before the button
    input.value = ''; // Clear the input field
    button.style.display = 'none'; // Hide the clear button
    input.focus(); // Refocus on the input field

    // Reset the DataTable search and column filters, similar to clearFiltersButton
    table.search('').columns().search('').draw(); // Reset DataTable search and column filters
  }
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


<?php include '../Employee Section/includes/emp-scripts.php' ?>

</body>
</html>
