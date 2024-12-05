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

        <!-- Dropdowns Section -->
        <div class="show-entries-wrapper">
           <label for="itemsPerPageDropdown" class="">Show Entries</label>
           <div class="dropdown">
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
           <label for="filterDropdown" class="">Filter Options</label>
           <div class="dropdown">
               <button
                   class="btn btn-outline-secondary dropdown-toggle"
                   type="button"
                   id="filterDropdown"
                   data-bs-toggle="dropdown"
                   aria-expanded="false"
               >
                   Filter Options
               </button>
               <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                   <li><a class="dropdown-item" href="#">Status</a></li>
                   <li><a class="dropdown-item" href="#">Package name (A-Z)</a></li>
               </ul>
           </div>
      </div>
     
      <!-- Flight Date Range Picker -->
      <div class="flight-dateRange-wrapper">
          <label for="flightStartDate" class="">Flight Date</label>
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

      <div class="button-wrapper">
        <button class="btn btn-danger" id="clearFiltersButton">
            <i class="fa-solid fa-circle-xmark"></i> Clear Filters
        </button>
     </div>
   </div>

   <div class="table-subheader2 my-3">
      <!-- Search Section -->
      <div class="search-wrapper position-relative d-flex flex-column mb-2 mt-3">
        <input
          type="text"
          id="tableSearchInput"
          placeholder="Search..."
          class="form-control search-input"
          oninput="toggleClearButton(this)"
        />
        <button
          type="button"
          class="clear-button"
          onclick="clearInput(this)"
          style="display: none;"
        >
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="button-wrapper mt-2">
        <button class="btn btn-danger" id="clearFiltersButton">
            <i class="fa-solid fa-circle-xmark"></i> Clear Filters
        </button>
     </div>

    </div>

   <script>
     function toggleClearButton(input) {
       const clearButton = input.nextElementSibling; // Get the button next to the input
       clearButton.style.display = input.value ? "block" : "none";
     }

     // Clear the input field
     function clearInput(button) {
       const input = button.previousElementSibling; // Get the input field before the button
       input.value = "";
       button.style.display = "none"; // Hide the clear button
       input.focus(); // Refocus on the input
     }
   </script>

   <div class="table-wrapper">
     <table class="table-transaction">
     <thead>
        <tr>
          <th>Transact No</th>
          <th>Package Name</th>
          <th>Flight Date</th>
          <th>Booking Date</th>
          <th>Total Pax</th>
          <th>Package Price</th>
          <th>Request Cost</th>
          <th>Amount to be Paid</th>
          <th>Amount Paid</th>
          <th>Remaining Balance</th>
          <th>Status</th>
        </tr>
       </thead>
       <tbody>
        <?php
          // SQL query for SOA
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

             // Determine the status based on the balance
              $status = ($row['Balance'] <= 0) ? 'Fully Paid' : 'Pending';

              // Assign the appropriate badge class based on the status
              $statusClass = '';
              switch ($status) {
                  case 'Active':
                      $statusClass = 'badge bg-success'; // Green pill for "Active"
                      break;
                  case 'Pending':
                      $statusClass = 'badge bg-warning text-dark'; // Yellow pill for "Pending"
                      break;
                  case 'Inactive':
                      $statusClass = 'badge bg-secondary'; // Grey pill for "Inactive"
                      break;
                  case 'To be confirmed':
                      $statusClass = 'badge bg-info text-dark'; // Blue pill for "To be confirmed"
                      break;
                 case 'Fully Paid':
                      $statusClass = 'badge bg-success'; // Green pill for "Fully Paid"
                      break;
                  default:
                      $statusClass = 'badge bg-dark'; // Dark pill for unknown statuses
                      break;
              }
              

              // Output each row as a table row
              echo "<tr data-url='emp-transactionInfo.php?id=" . htmlspecialchars($row['transactNo']) . "'>";
              echo "<td>" . htmlspecialchars($row['transactNo']) . "</td>";
              echo "<td>" . htmlspecialchars($row['PackageName']) . "</td>";
              echo "<td>" . htmlspecialchars($row['FlightDate']) . "</td>";
              echo "<td>" . htmlspecialchars($row['BookingDate']) . "</td>";
              echo "<td class='text-center fw-bold'>" . htmlspecialchars($row['TotalPax']) . "</td>";
              echo "<td>₱ " . number_format($row['PackagePrice'], 2) . "</td>";
              echo "<td>₱ " . number_format($row['RequestCost'], 2) . "</td>";
              echo "<td>₱ " . number_format($row['AmountToPaid'], 2) . "</td>";
              echo "<td>₱ " . number_format($row['AmountPaid'], 2) . "</td>";
              echo "<td>₱ " . number_format(max($row['Balance'], 0), 2) . "</td>"; // Ensure Balance doesn't go negative
              echo "<td><span class='{$statusClass} py-2'>{$status}</span></td>";
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
            { width: '10%', targets: 0 }, // Transact No
            { width: '13%', targets: 1 }, // Package Name
            { width: '13%', targets: 2 }, // Flight Date
            { width: '10%', targets: 3 }, // Booking Date
            { width: '5%', targets: 4 },  // Total Pax
            { width: '10%', targets: 5 }, // Package Price
            { width: '10%', targets: 6 }, // Request Cost
            { width: '10%', targets: 7 }, // Amount to be Paid
            { width: '10%', targets: 8 }, // Amount Paid
            { width: '10%', targets: 9 }, // Remaining Balance
            { width: '7%', targets: 10 } // Status
        ],
        language: {
            emptyTable: "No records available"
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

    // Handle Flight Date Range Filtering
    $('#flightStartDate, #flightEndDate').on('change', function() {
        var flightStartDate = $('#flightStartDate').val();
        var flightEndDate = $('#flightEndDate').val();
        if (flightStartDate && flightEndDate) {
            table.column(2).search(flightStartDate + ' to ' + flightEndDate).draw();
        }
    });

    // Handle Booking Date Range Filtering
    $('#bookingStartDate, #bookingEndDate').on('change', function() {
        var bookingStartDate = $('#bookingStartDate').val();
        var bookingEndDate = $('#bookingEndDate').val();
        if (bookingStartDate && bookingEndDate) {
            table.column(3).search(bookingStartDate + ' to ' + bookingEndDate).draw();
        }
    });

    // Clear filters functionality
    $('#clearFiltersButton').on('click', function() {
        $('#flightStartDate').val('');
        $('#flightEndDate').val('');
        $('#bookingStartDate').val('');
        $('#bookingEndDate').val('');
        $('#tableSearchInput').val('');
        table.search('').columns().search('').draw();
    });
});

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
