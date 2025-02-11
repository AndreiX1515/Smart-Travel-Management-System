<?php 
session_start();
require "../conn.php";  
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Accounts - Agent</title>

  <?php include "../Admin Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Admin Section/assets/css/admin-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Admin Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="body-container">
  <?php include "../Admin Section/includes/sidebar.php"; ?>

  <div class="main-content-container">
    <div class="navbar">
      <h5 class="title-page">Manage Account - Agent</h5>
    </div>

    <div class="main-content">
      <div class="table-wrapper">
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
              <!-- <div class="date-range-wrapper sorting-wrapper">
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
              </div> -->

              <!-- <div class="date-range-wrapper flightbooking-wrapper">
                <div class="date-range-inputs-wrapper">
                  <div class="input-with-icon">
                    <input type="text" class="datepicker" id="BookingStartDate" placeholder="Booking Date">
                    <i class="fas fa-calendar-alt calendar-icon"></i>
                  </div>
                </div>
              </div> -->

              <!-- <div class="date-range-wrapper flightbooking-wrapper">
                <div class="date-range-inputs-wrapper">
                  <div class="input-with-icon">
                    <input type="text" class="datepicker" id="FlightStartDate" placeholder="Flight Date">
                    <i class="fas fa-calendar-alt calendar-icon"></i>
                  </div>
                </div>
              </div> -->

              <!-- <div class="buttons-wrapper">
                <button id="clearSorting" class="btn btn-secondary">
                    Clear Filters
                </button>
              </div> -->

              <div class="buttons-wrapper">
                <button id="Add Account" class="btn btn-primary">
                    Add Account
                </button>
              </div>
            </div>

          </div>

          <div class="navpills-container">
              <ul class="nav nav-pills nav-underline" id="pills-tab" role="tablist">
                  <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                          All <span class="badge">88</span>
                      </button>
                  </li>
                  <li class="nav-item" role="presentation">
                      <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                          Pending <span class="badge">61</span>
                      </button>
                  </li>

                  <li class="nav-item" role="presentation">
                      <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
                          Confirmed <span class="badge">27</span>
                      </button>
                  </li>

                  <li class="nav-item" role="presentation">
                      <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
                          Cancelled <span class="badge">27</span>
                      </button>
                  </li>
              </ul>
          </div>

          <?php 
            if(isset($_SESSION['status'])):
          ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <strong>Hey!</strong> <?= $_SESSION['status']; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php 
            unset($_SESSION['status']);
            endif;
          ?>

          <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                <div class="table-container">
                  <table id="product-table" class="product-table">
                    <thead>
                      <tr>
                          <th>Account ID</th>
                          <th>Agent Code</th>
                          <th>Agent ID</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Password</th>
                          <th>Contact No.</th>
                          <th>Agent Type</th>
                          <th>Agent Role</th>
                          <th>Status</th>
                          <th></th>
                      </tr>
                    </thead>
                      <?php
                      $sql = "SELECT 
                      a.accountId AS `Account ID`,
                      ag.agentCode AS `Agent Code`,
                      ag.agentId AS `Agent ID`,
                      CONCAT(ag.lName, ', ', ag.fName, ' ', 
                            CASE WHEN ag.mName = 'N/A' OR ag.mName IS NULL 
                            THEN '' ELSE CONCAT(SUBSTRING(ag.mName, 1, 1), '.') END) AS `Name`,
                      a.email AS `Email`,
                      a.password AS `Password`,
                      CONCAT(ag.countryCode, ' ', ag.contactNo) AS `Contact No.`,
                      ag.agentType AS `Agent Type`,
                      ag.agentRole AS `Agent Role`,
                      a.accountStatus AS `Status`
                      FROM accounts a
                      LEFT JOIN agent ag ON a.accountId = ag.accountId
                      WHERE a.accountType = 'agent'
                      ORDER BY ag.agentCode ASC, a.accountId ASC";  // Prioritizing Agent Code (A001, A002)

                      $result = $conn->query($sql);

                     
                      ?>
                    <tbody>
                      <?php 
                      
                        // Check if there are records
                        if ($result->num_rows > 0) {

                        while ($row = $result->fetch_assoc()) {
                          $accountId = htmlspecialchars($row['Account ID']);

                          echo "<tr>
                            <td>{$row['Account ID']}</td>
                            <td>{$row['Agent Code']}</td>
                            <td>{$row['Agent ID']}</td>
                            <td>{$row['Name']}</td>
                            <td>{$row['Email']}</td>
                            <td>{$row['Password']}</td>
                            <td>{$row['Contact No.']}</td>
                            <td>{$row['Agent Type']}</td>
                            <td class='agentRole'>{$row['Agent Role']}</td>
                            <td>{$row['Status']}</td>
                            <td>
                                <div class='dropdown-center' style='text-align: center; position: relative;'>
                                    <button class='btn btn-light' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='fas fa-ellipsis-v'></i>
                                    </button>
                                    <ul class='dropdown-menu' style='position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%);'>
                                        <li>
                                            <a class='dropdown-item edit' href='#' data-id='<?php $accountId; ?>' data-bs-toggle='modal' data-bs-target='#editModal'>
                                                <i class='fas fa-edit'></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class='dropdown-item delete text-danger' href='#' data-id='<?php echo $accountId; ?>' data-bs-toggle='modal' data-bs-target='#deleteModal'>
                                                <i class='fas fa-trash-alt'></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>


                          </tr>";

                  
                        }
                        } else {
                        echo "<tr><td colspan='11' style='text-align: center;'>No agent records found</td></tr>";
                        }

                        // Close the connection
                        $conn->close();
                        ?>
                    </tbody>

                  </table>
                </div>

                <!-- Custom Pagination Container -->
                <div class="table-footer">
                  <div class="pagination-controls">
                    <button id="prevPage" class="pagination-btn">Previous</button>
                    <span id="pageInfo" class="page-info">Page 1 of 10</span>
                    <button id="nextPage" class="pagination-btn">Next</button>
                  </div>
                </div>
  

              </div>

              <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                  <!-- Content for Pickups -->
                  Pending Table Here
              </div>


              <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                  <!-- Content for Returns -->
                  Confirmed table Here
              </div>


              <!-- <div class="tab-pane fade" id="pills-disabled" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">
                  <!-- Content for Disabled 
                  Disabled Content Here
              </div> -->

          </div> 
      </div> 


    </div>
  </div>

</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editAccountId">
                    <!-- Add form fields for account details here -->
                    <div class="mb-3">
                        <label for="editAccountName" class="form-label">Account Name</label>
                        <input type="text" class="form-control" id="editAccountName" placeholder="Enter account name">
                    </div>
                    <!-- Add more fields as necessary -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this account?</p>
                <form id="deleteForm">
                    <input type="hidden" id="deleteAccountId">
                    <!-- You can add additional information or details if necessary -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>


<?php require "../Agent Section/includes/scripts.php"; ?>

<script>
function toggleSubMenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    const sectionTitle = submenu.previousElementSibling;
    const chevron = sectionTitle.querySelector('.chevron-icon'); 

    // Check if the submenu is already open
    const isOpen = submenu.classList.contains('open');

    // If it's open, we need to close it, and reset the chevron
    if (isOpen) {
        submenu.classList.remove('open');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        // First, close all open submenus and reset all chevrons
        const allSubmenus = document.querySelectorAll('.submenu');
        const allChevrons = document.querySelectorAll('.chevron-icon');
        
        allSubmenus.forEach(sub => {
            sub.classList.remove('open');
        });

        allChevrons.forEach(chev => {
            chev.style.transform = 'rotate(0deg)';
        });

        // Now, open the current submenu and rotate its chevron
        submenu.classList.add('open');
        chevron.style.transform = 'rotate(180deg)';
    }
}


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
        scrollY: '68vh',  // Set a fixed height for the table (adjust as necessary)
        paging: true,  // Enable pagination
        pageLength: 12,  // Set the number of rows per page
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
        table.column(3).search(selectedPackage || '').draw();
    });

    // Booking Date Filter with value change
    $('#BookingStartDate').on('change', function () {
      const selectedBookingDate = $(this).val();  // Get the selected value directly from the input field
      console.log("Booking Date Filter:", selectedBookingDate);  // Log the selected booking date
      table.column(4).search(selectedBookingDate || '').draw();  // Column 4 (index starts at 0)
    });

    // Flight Date Filter with value change
    $('#FlightStartDate').on('change', function () {
      const selectedFlightDate = $(this).val();  // Get the selected value directly from the input field
      console.log("Flight Date Filter:", selectedFlightDate);  // Log the selected flight date
      table.column(5).search(selectedFlightDate || '').draw();  // Column 5 (index starts at 0)
    });

    // Apply datepicker and input validation for FlightStartDate
    $("#FlightStartDate").datepicker({
        dateFormat: "mm-dd-yy", // Set the format to MM-DD-YYYY
        showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
        changeMonth: true, // Allow the month to be changed from the dropdown
        changeYear: true,  // Allow the year to be changed from the dropdown
        yearRange: "1900:2100", // Set a range of years (optional)
        onSelect: function(dateText) {
            // When a date is selected, update the input field with the date
            $(this).val(dateText);
            flightStartDate = dateText; // Store the selected date
            console.log("FlightStartDate Selected Date (onSelect): " + dateText);
            table.column(5).search(flightStartDate || '').draw();  // Column 5 (index starts at 0)
        }
    });

    $("#FlightStartDate").datepicker({
        dateFormat: "mm-dd-yy", // Set the format to MM-DD-YYYY
        showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
        changeMonth: true, // Allow the month to be changed from the dropdown
        changeYear: true,  // Allow the year to be changed from the dropdown
        yearRange: "1900:2100", // Set a range of years (optional)
        onSelect: function(dateText) {
            // When a date is selected, update the input field with the date
            if (dateText === "") {
                flightStartDate = ""; // Reset the variable if the field is cleared
            } else {
                flightStartDate = dateText; // Store the selected date
            }
            console.log("FlightStartDate Selected Date (onSelect): " + flightStartDate);
            table.column(5).search(flightStartDate || '').draw(); // Column 5 (index starts at 0)
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


  </body>
</html>