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
      <div class="search-wrapper position-relative d-flex flex-column align-items-start mb-2 mt-3">
        <label for="search-label" class="search-label">Search:</label>
        <input
          type="text"
          id="tableSearchInput"
          placeholder="Search..."
          class="form-control search-input"
          oninput="toggleClearButton(this)"
        />
        <!-- <button
          type="button"
          class="clear-button"
          onclick="clearInput(this)"
          style="display: none;"
        >
          <i class="fas fa-times"></i>
        </button> -->
      </div>
    
       <div class="left-side-wrapper pt-2">
        <div class="show-entries-wrapper">
          <label for="itemsPerPageDropdown" class="">Show Entries</label>
          <div class="dropdown mt-2">
              <button
                  class="btn btn-outline-secondary dropdown-toggle"
                  type="button"
                  id="itemsPerPageDropdown"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
              >
                  All
              </button>
              <ul class="dropdown-menu" aria-labelledby="itemsPerPageDropdown">
                  <li><a class="dropdown-item" href="#" data-page-size="5">All</a></li>
                  <li><a class="dropdown-item" href="#" data-page-size="10">10</a></li>
                  <li><a class="dropdown-item" href="#" data-page-size="50">50</a></li>
                  <li><a class="dropdown-item" href="#" data-page-size="100">100</a></li>
              </ul>
          </div>
         </div>

       <!-- Filter Dropdown -->
       <div class="filter-wrapper">
        <label for="filterDropdown" class="">Status</label>
        <div class="dropdown mt-2">
            <button
                class="btn btn-outline-secondary dropdown-toggle"
                type="button"
                id="filterDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                Status
            </button>
            <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                <li><a class="dropdown-item" href="#">Status</a></li>
                <li><a class="dropdown-item" href="#">Package name (A-Z)</a></li>
            </ul>
        </div>
      </div>
     
      <!-- Flight Date Range Picker -->
     <div class="flight-dateRange-wrapper">
       <label for="flightStartDate" class="">Flight Date (Departure)</label>
       <div class="d-flex align-items-center">
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

      <div class="button-wrapper mt-4">
       <button class="btn btn-outline-secondary mt-2" id="clearFiltersButton">
            Clear Filters and Search
       </button>
     </div>

     <div class="vertical-line"></div> <!-- Vertical line -->
     
     <div class="button-wrapper">
       <button class="btn btn-primary mt-3" id="clearFiltersButton">
       <i class="fa-solid fa-user-plus"></i> Add Booking
       </button>
     </div>

    </div>
   </div>


  <div class="table-wrapper">
    <table class="table-transaction table-striped">
      <thead>
        <tr>
          <th rowspan="2">Transact No</th>
          <th rowspan="2">Agent Name</th>
          <th rowspan="2">Package Name</th>
          <th colspan="2" class="text-center">Flight Date</th>
          <th rowspan="2">Booking Date</th>
          <th rowspan="2">Total Pax</th>
          <th rowspan="2">Package Price</th>
          <th rowspan="2">Status</th>
        </tr>
        <tr>
          <th>Departure</th>
          <th>Return</th>
        </tr>
      </thead>
      <tbody>
        <?php
          // SQL query for SOA
          $sql = "SELECT b.transactNo, f.flightDepartureDate as departureDate, f.returnDepartureDate as returnDate, b.status as bookingStatus,
                      CONCAT(f.flightDepartureDate, ' | ', f.returnDepartureDate) AS FlightDate, p.packageName AS PackageName, 
                      DATE_FORMAT(b.bookingDate, '%m.%d.%Y') AS BookingDate, b.pax AS TotalPax, b.totalPrice AS PackagePrice, 
                      CONCAT(a.lName, ', ', a.fName, ' ', IFNULL(CONCAT(SUBSTRING(a.mName, 1, 1), '.'), '')) AS agentName
                  FROM 
                    booking b
                  JOIN 
                    flight f ON f.flightId = b.flightId
                  JOIN 
                    package p ON p.packageId = b.packageId
                  JOIN
                    agent a ON a.agentId = b.agentId
                  ORDER BY 
                    b.bookingDate ASC";

          // Execute the query
          $result = $conn->query($sql);

          // Check if there are results
          if ($result->num_rows > 0) 
          {
            while ($row = $result->fetch_assoc()) 
            {
              
              // Output each row as a table row
              echo "<tr data-url='emp-transactionInfo.php?id=" . htmlspecialchars($row['transactNo']) . "'>";
              echo "<td>" . htmlspecialchars($row['transactNo']) . "</td>";
              echo "<td>" . htmlspecialchars($row['agentName']) . "</td>";
              echo "<td>" . htmlspecialchars($row['PackageName']) . "</td>";
              // echo "<td>" . htmlspecialchars($row['FlightDate']) . "</td>";
              echo "<td>" . htmlspecialchars($row['departureDate']) . "</td>";
              echo "<td>" . htmlspecialchars($row['returnDate']) ."</td>";
              echo "<td>" . htmlspecialchars($row['BookingDate']) . "</td>";
              echo "<td class=' fw-bold'>" . htmlspecialchars($row['TotalPax']) . "</td>";
              echo "<td>₱ " . number_format($row['PackagePrice'], 2) . "</td>";
              echo "<td>" . htmlspecialchars($row['bookingStatus']) . "</td>";
              echo "</tr>";
            }
          } 
          
        ?>
      </tbody>
    </table>
  </div>
  </div>

 </div>
</div>

<script>
  $(document).ready(function() {
    var table = $('.table-transaction').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        columnDefs: [
            { width: '8%', targets: 0 }, // Transact No
            { width: '14%', targets: 1 }, // Transact No
            { width: '14%', targets: 2 }, // Package Name
            { width: '8%', targets: 3 }, // Flight Date
            { width: '6%', targets: 4 }, // Booking Date
            { width: '5%', targets: 5 },  // Total Pax
            { width: '8%', targets: 6 }, // Package Price
            { width: '10%', targets: 7 }, // Package Price
        ],
        language: {
            emptyTable: "NO RECORDS AVAILABLE"
        }
    });

    // Custom search functionality
    $('#tableSearchInput').on('input', function() {
        table.search(this.value).draw();
    });

    // Handle custom "Items per Page" dropdown
    $('#itemsPerPageDropdown .dropdown-item').on('click', function() {
        var pageSize = $(this).data('page-size');
        table.page.len(pageSize).draw();
    });

    // Flight Date Range Sorting (only for Departure)
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        const flightStartDate = $('#flightStartDate').val(); // Flight Start Date
        const flightEndDate = $('#flightEndDate').val(); // Flight End Date
        const departureDate = data[2]; // Assuming Flight Date is in column index 2 (Departure Date)

        const departure = departureDate ? new Date(departureDate) : null;
        const startDate = flightStartDate ? new Date(flightStartDate) : null;
        const endDate = flightEndDate ? new Date(flightEndDate) : null;

        // Departure Date filtering logic
        if (
            (!startDate || (departure && departure >= startDate)) &&
            (!endDate || (departure && departure <= endDate))
        ) {
            return true; // Row matches Departure Date filter
        }
        return false; // Otherwise, hide this row
    });

    // Handle Flight Date Range Filtering on change
    $('#flightStartDate, #flightEndDate').on('change', function() {
        // Redraw table to apply flight date filters
        table.draw();
    });

    // Handle Flight Date Range Filtering on change
    $('#flightStartDate, #flightEndDate').on('change', function() {
        // Redraw table to apply flight date filters
        table.draw();
    });

    // Booking Date Range Sorting
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        const bookingStartDate = $('#bookingStartDate').val(); // Booking Start Date
        const bookingEndDate = $('#bookingEndDate').val(); // Booking End Date
        const bookingDate = data[4]; // Assuming Booking Date is in column index 4

        const booking = bookingDate ? new Date(bookingDate) : null;
        const startDate = bookingStartDate ? new Date(bookingStartDate) : null;
        const endDate = bookingEndDate ? new Date(bookingEndDate) : null;

        // Booking Date filtering logic
        if (
            (!startDate || (booking && booking >= startDate)) &&
            (!endDate || (booking && booking <= endDate))
        ) {
            return true; // Row matches Booking Date filter
        }
        return false; // Otherwise, hide this row
    });

    // Handle Booking Date Range Filtering on change
    $('#bookingStartDate, #bookingEndDate').on('change', function() {
        // Redraw table to apply booking date filters
        table.draw();
    });

    // Clear all filters functionality
    $('#clearFiltersButton').on('click', function() {
        $('#tableSearchInput').val('');
        $('#flightStartDate').val('');
        $('#flightEndDate').val('');
        $('#bookingStartDate').val('');
        $('#bookingEndDate').val('');
        $('#itemsPerPageDropdown .dropdown-item').removeClass('active');
        table.search('').draw();
        table.page.len(10).draw();
    });
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
    $('#clearFiltersButton').on('click', function() {
        $('#flightStartDate').val('');
        $('#flightEndDate').val('');
        $('#bookingStartDate').val('');
        $('#bookingEndDate').val('');
        $('#tableSearchInput').val('');
        table.search('').columns().search('').draw(); // Reset the search and clear column filters
    });

// Toggle clear button visibility
function toggleClearButton(input) {
    const clearButton = input.nextElementSibling; // Get the button next to the input
    clearButton.style.display = input.value ? "block" : "none";
}

// Clear the input field and reset search when clicked (same as clearFiltersButton)
function clearInput(button) {
    const input = button.previousElementSibling; // Get the input field before the button
    input.value = ''; // Clear the input field
    button.style.display = 'none'; // Hide the clear button
    input.focus(); // Refocus on the input field

    // Reset the DataTable search and column filters, similar to clearFiltersButton
    table.search('').columns().search('').draw(); // Reset DataTable search and column filters
}
</script>

<style>
  .dataTables_length {
    display: none;
  }

  .dataTables_filter {
    display: none;
  }
</style>

<script> 
let lastScrollTop = 0; // Keeps track of the last scroll position
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

</script>

 <!-- Clickable rows script -->
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
