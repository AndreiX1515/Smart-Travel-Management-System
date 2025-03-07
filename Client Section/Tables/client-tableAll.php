<div class="table-container">
  <table id="product-table-all" class="product-table">
    <thead>
      <tr>
        <th>Transaction ID</th>
        <th>Contact Person Info</th>
        <th>Contact Details</th>
        <th>Branch Name</th>
        <!-- <th>Booking Date</th> -->
        <th>Flight Date</th>
        <th>Total Pax</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $accountId = $_SESSION['client_accountId'];

      $sql1 = "SELECT b.transactNo AS `T.N`, p.packageName AS `PACKAGE`,
                              DATE_FORMAT(b.bookingDate, '%m-%d-%Y') AS `TRANSACTION DATE`, b.bookingType as bookingType,
                              DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y') AS `FLIGHT DATE`, b.pax AS `TOTAL PAX`,
                              CONCAT(b.lName, ', ', b.fName, ' ', CASE WHEN b.mName = 'N/A' THEN '' 
                                ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ', CASE WHEN b.suffix = 'N/A' THEN '' 
                                ELSE b.suffix END) AS `CONTACT NAME`, br.branchName as branchName,
                              b.email AS `CONTACT EMAIL`, CONCAT(b.countryCode, ' ', b.contactNo) AS `CONTACT PHONE`, b.status AS `STATUS`
                            FROM booking b
                            LEFT JOIN flight f ON b.flightId = f.flightId
                            LEFT JOIN package p ON b.packageId = p.packageId
                            LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
                            LEFT JOIN company c ON a.companyId = c.companyId
                            LEFT JOIN client cl ON b.accountType = 'Client' AND b.accountId = cl.accountId
                            JOIN branch br ON b.agentCode = br.branchAgentCode
                            WHERE b.accountId = '$accountId' 
                            ORDER BY b.transactNo DESC";

      $res1 = $conn->query($sql1);

      if ($res1->num_rows > 0) {
        while ($row = $res1->fetch_assoc()) {
          $transactNo = $row['T.N'];
          $pax = $row['TOTAL PAX'];

          $status = isset($row['STATUS']) ? $row['STATUS'] : 'Unknown';
          $statusClass = '';

          switch ($status) {
            case 'Confirmed':
              $statusClass = 'bg-success text-white'; // Green background, white text
              break;
            case 'Cancelled':
              $statusClass = 'bg-danger text-white'; // Red background, white text
              break;
            case 'Pending':
              $statusClass = 'bg-warning text-dark';
              break;
            default:
              $statusClass = 'bg-secondary text-white';
          }

          // Booking Date
          // <td>{$row['TRANSACTION DATE']}</td>

          echo "<tr data-url='client-transactionInfo.php?id=" . htmlspecialchars($transactNo) . "'>
                                  <td>{$transactNo}</td>
                                  <td>{$row['CONTACT NAME']}</td>
                                  <td> 
                                    <div class='d-flex flex-column'>
                                      <span><strong>Email: </strong>" . $row['CONTACT EMAIL'] . " </span>
                                      <span><strong>Contact Number: </strong> " . $row['CONTACT PHONE'] . "</span>
                                    </div>
                                  </td>
        
                                  <td>{$row['branchName']}</td>
                                  
                                  <td>{$row['FLIGHT DATE']}</td>
                                  <td style='text-align: center; font-weight: bold;'>
                                      {$row['TOTAL PAX']}
                                  </td>
                                  <td>
                                    <span class='badge p-2 rounded-pill {$statusClass} '>
                                        {$status}
                                    </span>
                                </td>
                          </tr>";
        }
      } else {
        echo "<tr>
                                <td colspan='7' class='text-center text-danger'>
                                    No bookings found.
                                </td>
                              </tr>";
      }
      if ($res1) {
        $res1->free();
      }
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


<!-- DataTables #product-table -->
<script>
  $(document).ready(function () 
  {
    const tableAll = $('#product-table-all').DataTable(
    {
      dom: 'rtip',  // Use only the relevant table elements
      language: 
      {
        emptyTable: "No Transaction Records Available"
      },
      order: [[0, 'desc']],  // Default sorting by Transaction ID (descending)
      scrollX: false,
      scrollY: '67.5vh',  // Set a fixed height for the table (adjust as necessary)
      paging: true,  // Enable pagination
      pageLength: 11,  // Set the number of rows per page
      autoWidth: false,
      autoHeight: false,  // Prevent automatic height adjustment

      // Disable sorting for specific columns
      columnDefs: 
      [
        {
          targets: [1, 2, 3,  5, 6,], // Disable sorting for 2nd and 4th columns
          orderable: false
        }
      ]
    });

    // Search Functionality
    $('#search').on('keyup', function () 
    {
      tableAll.search(this.value).draw();
    });

    // Update the custom pagination buttons and page info
    function updatePagination() 
    {
      const info = tableAll.page.info();
      const currentPage = info.page + 1; // Get current page number (1-indexed)
      const totalPages = info.pages; // Get total pages

      // Update page info text
      $('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);

      // Enable/Disable prev and next buttons based on current page
      $('#prevPage').prop('disabled', currentPage === 1);
      $('#nextPage').prop('disabled', currentPage === totalPages);
    }

    // Custom pagination button click events
    $('#prevPage').on('click', function() 
    {
      tableAll.page('previous').draw('page');
      updatePagination();
    });

    $('#nextPage').on('click', function() 
    {
      tableAll.page('next').draw('page');
      updatePagination();
    });

    // Initialize pagination on first load
    updatePagination();

    // Status Filter
    $('#status').on('change', function () 
    {
        const selectedStatus = $(this).val();
        tableAll.column(8).search(selectedStatus || '').draw();
    });

    // Package Filter
    $('#packages').on('change', function () 
    {
      const selectedPackage = $(this).val();
      tableAll.column(3).search(selectedPackage || '').draw();
    });

    // Booking Date Filter with value change
    $('#BookingStartDate').on('change', function () 
    {
      const selectedBookingDate = $(this).val();  // Get the selected value directly from the input field
      console.log("Booking Date Filter:", selectedBookingDate);  // Log the selected booking date
      tableAll.column(4).search(selectedBookingDate || '').draw();  // Column 4 (index starts at 0)
    });

    // Flight Date Filter with value change
    $('#FlightStartDate').on('change', function () 
    {
      const selectedFlightDate = $(this).val();  // Get the selected value directly from the input field
      console.log("Flight Date Filter:", selectedFlightDate);  // Log the selected flight date
      tableAll.column(5).search(selectedFlightDate || '').draw();  // Column 5 (index starts at 0)
    });

    // Apply datepicker and input validation for FlightStartDate
    $("#FlightStartDate").datepicker(
    {
      dateFormat: "mm-dd-yy", // Set the format to MM-DD-YYYY
      showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
      changeMonth: true, // Allow the month to be changed from the dropdown
      changeYear: true,  // Allow the year to be changed from the dropdown
      yearRange: "1900:2100", // Set a range of years (optional)
      onSelect: function(dateText) 
      {
        // When a date is selected, update the input field with the date
        $(this).val(dateText);
        flightStartDate = dateText; // Store the selected date
        console.log("FlightStartDate Selected Date (onSelect): " + dateText);
        tableAll.column(5).search(flightStartDate || '').draw();  // Column 5 (index starts at 0)
      }
    });

    $("#FlightStartDate").datepicker(
    {
      dateFormat: "mm-dd-yy", // Set the format to MM-DD-YYYY
      showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
      changeMonth: true, // Allow the month to be changed from the dropdown
      changeYear: true,  // Allow the year to be changed from the dropdown
      yearRange: "1900:2100", // Set a range of years (optional)
      onSelect: function(dateText) 
      {
        // When a date is selected, update the input field with the date
        if (dateText === "") 
        {
          flightStartDate = ""; // Reset the variable if the field is cleared
        } 
        else 
        {
          flightStartDate = dateText; // Store the selected date
        }
        console.log("FlightStartDate Selected Date (onSelect): " + flightStartDate);
        tableAll.column(5).search(flightStartDate || '').draw(); // Column 5 (index starts at 0)
      }
    });

    // Apply datepicker and input validation for BookingStartDate
    $("#BookingStartDate").datepicker(
    {
      dateFormat: "mm-dd-yy", // Set the format to MM-DD-YYYY
      showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
      changeMonth: true, // Allow the month to be changed from the dropdown
      changeYear: true,  // Allow the year to be changed from the dropdown
      yearRange: "1900:2100", // Set a range of years (optional)
      onSelect: function(dateText) 
      {
        // When a date is selected, update the input field with the date
        $(this).val(dateText);
        bookingStartDate = dateText; // Store the selected date
        console.log("FlightStartDate Selected Date (onSelect): " + dateText);
        tableAll.column(4).search(bookingStartDate || '').draw();  // Column 5 (index starts at 0)
      }
    });

    // BookingStartDate Input Validation and Formatting
    $("#BookingStartDate").on("input", function () 
    {
      var value = $(this).val();

      // Remove non-numeric and non-dash characters
      value = value.replace(/[^\d-]/g, '');

      // Automatically add dashes in the correct places if necessary
      if (value.length > 2 && value.charAt(2) !== '-') 
      {
        value = value.substring(0, 2) + '-' + value.substring(2);
      }
      if (value.length > 5 && value.charAt(5) !== '-') 
      {
        value = value.substring(0, 5) + '-' + value.substring(5);
      }

      // Limit the total input length to 10 characters (MM-DD-YYYY)
      if (value.length > 10) 
      {
        value = value.substring(0, 10);
      }

      // Update the input field value
      $(this).val(value);

      // Reset or update the bookingStartDate variable
      if (value === "") 
      {
        bookingStartDate = ""; // Reset the variable if the input is cleared
      } 
      else 
      {
        bookingStartDate = value; // Update the variable with the formatted value
      }

      // Update the tableAll column search
      tableAll.column(5).search(bookingStartDate || '').draw(); // Column 5 (index starts at 0)

      console.log("BookingStartDate Input Value (on input): " + value);
    });

    // Clear All Filters
    $('#clearSorting').on('click', function () 
    {
      // Clear search field
      $('#search').val('');
      tableAll.search('').draw();

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

    
      // Redraw the tableAll
      tableAll.draw();
    });
  });
</script>

