<?php  session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Transactions</title>
    <?php include '../Employee Section/includes/emp-head.php'?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-transactionTableRequest.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
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
        <table class="product-table" id="productTable">
          <thead>
            <tr>
              <th>Transact No</th>
              <th>Agent Name</th>
              <th>Request Title</th>
              <th>Request Details</th>
              <th>Specific Details</th>
              <th>Total Pax</th>
              <th>Total Amount</th>
              <th>Request Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sql1 = "SELECT r.requestId, r.transactNo AS `TransactNo`,
              CONCAT(a.lName, ', ', a.fName, 
                  IF(a.mName IS NOT NULL AND a.mName != '', CONCAT(' ', LEFT(a.mName, 1), '.'), '')) AS AgentName,
              c.concernTitle AS `RequestTitle`, cd.details AS `RequestDetails`, b.pax AS `TotalPax`,
              r.requestCost as requestCost,
              r.customRequest as customRequest, r.details as details, DATE_FORMAT(r.requestDate, '%m-%d-%Y') AS `RequestDate`, 
              r.requestStatus AS `Status`, br.branchName as branchName
          FROM 
              request r
          
          LEFT JOIN 
              concern c ON r.concernId = c.concernId
          LEFT JOIN 
              concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
          LEFT JOIN 
              booking b ON r.transactNo = b.transactNo
          JOIN branch br ON b.agentCode = br.branchAgentCode
          LEFT JOIN 
              payment p ON b.transactNo = p.transactNo
          LEFT JOIN 
              agent a ON b.agentId = a.agentId
          WHERE
            r.requestStatus = 'Submitted'
          GROUP BY 
              r.requestId";

              $res1 = $conn->query($sql1);

              if ($res1->num_rows > 0) {
                while ($row = $res1->fetch_assoc()) {
                  // Determine the badge class based on the status
                  $status = $row['Status'];
                  $badgeClass = '';
                  switch ($status) {
                    case 'Confirmed':
                        $badgeClass = 'text-bg-success'; // Green for Confirmed
                        break;
                    case 'Submitted':
                        $badgeClass = 'text-bg-secondary'; // Gray for Submitted
                        break;
                    case 'Rejected':
                        $badgeClass = 'text-bg-danger'; // Red for Rejected
                        break;
                    default:
                        $badgeClass = 'text-bg-info'; // Blue for other statuses
                        break;
                  }

                  // Ensure that title and details are displayed properly
                  $title = $row['RequestTitle'] ?? 'Custom Request';
                  $details = $row['RequestDetails'] ?? $row['customRequest'];

                  // Output table row with data-transactno attribute
                  echo "<tr class='request-row' data-requestId='{$row['requestId']}'>
                          <td>{$row['TransactNo']}</td>
                          <td>{$row['AgentName']}</td>
                          <td>{$title}</td>
                          <td>{$details}</td>
                          <td>{$row['details']}</td>
                          <td>{$row['TotalPax']}</td>
                          <td>{$row['requestCost']}</td>
                          <td>{$row['RequestDate']}</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='9' style='text-align: center;'>No Requests Found</td></tr>";
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

<!-- Request Status Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Transaction Details - ID: <span id="transactionModalLabel"> </span> </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../Employee Section/functions/emp-tableRequest-code.php" method="POST">
        <div class="modal-body">
          <input type="hidden" id="requestIdInput" name="requestId">
          
          <!-- Request Status Section -->
          <div class="mb-3">
            <label for="requestStatus" class="form-label"><strong>Request Status:</strong></label>
            <select id="requestStatus" name="requestStatus" class="form-select" required>
              <option selected disabled>Select Option</option>
              <option value="Confirmed">Confirmed</option>
              <option value="Rejected">Reject</option>
            </select>
          </div>

          <!-- Handling Fee -->
          <div class="mb-3">
            <label for="requestHandlingFee" class="form-label"><strong>Handling Fee:</strong></label>
            <select id="requestHandlingFee" name="requestHandlingFee" class="form-select">
              <option selected value="0">No Handling Fee</option>
              <option value="100">₱ 100</option>
              <option value="200">₱ 200</option>
              <option value="300">₱ 300</option>
              <option value="400">₱ 400</option>
              <option value="500">₱ 500</option>
            </select>
          </div>

          <div class="mb-4">
            <!-- Remarks Input -->
            <label for="requestRemarks" class="form-label fw-bold">Remarks:</label>
            <input type="text" id="requestRemarks" name="requestRemarks" class="form-control" 
            placeholder="Enter remarks or additional comments here">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="updateRequestStatus" class="btn btn-primary">Update Status</button>
        </div>
      </form>
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


<?php
// Fetch the status from the session
$statusMessage = isset($_SESSION['status']) ? $_SESSION['status'] : '';

// Set default toast color, and check if status is "Submitted", "Confirmed", or "Rejected"
$toastColor = 'text-bg-primary'; // Default color

// Check for specific status messages and set the appropriate toast color
if (isset($_SESSION['status'])) {
    if (strpos($_SESSION['status'], 'Cancelled') !== false) {
        // Change to red for "Cancelled" status
        $toastColor = 'text-bg-danger';
    } elseif (strpos($_SESSION['status'], 'Submitted') !== false) {
        // Blue color for "Submitted" status
        $toastColor = 'text-bg-secondary';
    } elseif (strpos($_SESSION['status'], 'Confirmed') !== false) {
        // Green color for "Confirmed" status
        $toastColor = 'text-bg-success';
    } elseif (strpos($_SESSION['status'], 'Rejected') !== false) {
        // Red color for "Rejected" status
        $toastColor = 'text-bg-danger';
    }
} elseif (isset($_SESSION['toastColor'])) {
    // Use session-defined toast color if available
    $toastColor = $_SESSION['toastColor'];
}


if (!empty($statusMessage)) {
    // You can use this status message in a toast or somewhere else
    echo '<div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="statusToast" class="toast align-items-center ' . $toastColor . ' border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ' . htmlspecialchars($statusMessage) . '
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
          </div>';
    
    // After displaying the status message, unset session variables
    unset($_SESSION['status']);
    unset($_SESSION['toastColor']);
}
?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Automatically display the toast if it exists
    const toastElement = document.getElementById('statusToast');
    if (toastElement) {
      const toast = new bootstrap.Toast(toastElement);
      toast.show();
    }
  });
</script>


<script>
  // Wait for the DOM to be fully loaded
  document.addEventListener('DOMContentLoaded', function() 
  {
    // Get all the rows with the class 'request-row'
    const rows = document.querySelectorAll('.request-row');
    
    rows.forEach(row => 
    {
      // Add click event listener to each row
      row.addEventListener('click', function() 
      {
        // Get the requestId (data attribute)
        const requestId = row.getAttribute('data-requestId');
        
        // Set the requestId in both the <span> and <input> fields
        document.getElementById('requestIdInput').value = requestId;
        document.getElementById('transactionModalLabel').textContent = requestId;
        
        // Show the modal (using Bootstrap modal)
        const modal = new bootstrap.Modal(document.getElementById('transactionModal'));
        modal.show();
      });
    });
  });
</script>




</body>
</html>
