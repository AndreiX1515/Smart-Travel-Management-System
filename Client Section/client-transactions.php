<?php 
session_start();
require "../conn.php"; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="body-container">
  <?php include "../Client Section/Includes/client-sidebar.php"; ?>

  <div class="main-content-container">
    <div class="navbar">
      <h5 class="title-page">Packages - Transactions table</h5>
    </div>

    <div class="main-content">
      <div class="table-wrapper">
        <div class="table-header">
          <div class="search-wrapper">
            <div class="search-input-wrapper">
              <input type="text" id="search" placeholder="Search here..">
              <!-- <span class="icon">🔍</span> -->
            </div>
          </div>

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
                    if ($res1->num_rows > 0) 
                    {
                      // Loop through the results and generate options
                      while ($row = $res1->fetch_assoc()) 
                      {
                        echo "<option value='" . $row['branchName'] . "'>" . $row['branchName'] . "</option>";
                      }
                    } 
                    else 
                    {
                      echo "<option value=''>No companies available</option>";
                    }
                  ?>
                </select>
              </div>
            </div>

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

        <div class="navpills-container">
          <ul class="nav nav-pills nav-underline" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="true">
                All 
                <span class="badge">
                  <?php
                    $sql1 = "SELECT COUNT(*) AS totalBookings FROM booking WHERE accountId = $accountId";
                    $result = mysqli_query($conn, $sql1);
                    
                    if ($result) 
                    {
                      $row = mysqli_fetch_assoc($result);
                      $totalBookings = $row['totalBookings'];
                    } 
                    else 
                    {
                      $totalBookings = 0; // Default value if query fails
                    }
                    echo $totalBookings;
                  ?>
                </span>
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-pending-tab" data-bs-toggle="pill" data-bs-target="#pills-pending" type="button" role="tab" aria-controls="pills-pending" aria-selected="false">
                Pending / Reserved 
                <span class="badge">
                  <?php
                    $sql1 = "SELECT COUNT(*) AS totalBookings FROM booking 
                              WHERE accountId = $accountId AND (status = 'Pending' OR status = 'Reserved')";
                    $result = mysqli_query($conn, $sql1);
                    
                    if ($result) 
                    {
                      $row = mysqli_fetch_assoc($result);
                      $totalBookings = $row['totalBookings'];
                    } 
                    else 
                    {
                      $totalBookings = 0; // Default value if query fails
                    }
                    echo $totalBookings;
                  ?>
                </span>
              </button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-confirmed-tab" data-bs-toggle="pill" data-bs-target="#pills-confirmed" type="button" role="tab" aria-controls="pills-confirmed" aria-selected="false">
                Confirmed 
                <span class="badge">
                  <?php
                    $sql1 = "SELECT COUNT(*) AS totalBookings FROM booking 
                              WHERE accountId = $accountId AND status = 'Confirmed'";
                    $result = mysqli_query($conn, $sql1);
                    
                    if ($result) 
                    {
                      $row = mysqli_fetch_assoc($result);
                      $totalBookings = $row['totalBookings'];
                    } 
                    else 
                    {
                      $totalBookings = 0; // Default value if query fails
                    }
                    echo $totalBookings;
                  ?>
                </span>
              </button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-cancelled-tab" data-bs-toggle="pill" data-bs-target="#pills-cancelled" type="button" role="tab" aria-controls="pills-cancelled" aria-selected="false">
                Cancelled 
                <span class="badge">
                  <?php
                    $sql1 = "SELECT COUNT(*) AS totalBookings FROM booking 
                              WHERE accountId = $accountId AND status = 'Cancelled'";
                    $result = mysqli_query($conn, $sql1);
                    
                    if ($result) 
                    {
                      $row = mysqli_fetch_assoc($result);
                      $totalBookings = $row['totalBookings'];
                    } 
                    else 
                    {
                      $totalBookings = 0; // Default value if query fails
                    }
                    echo $totalBookings;
                  ?>
                </span>
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
          <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab" tabindex="0">
            <div class="table-container">
              <table id="product-table" class="product-table">
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

                      if ($res1->num_rows > 0) 
                      {
                        while ($row = $res1->fetch_assoc()) 
                        {
                          $transactNo = $row['T.N'];
                          $pax = $row['TOTAL PAX'];

                          $status = isset($row['STATUS']) ? $row['STATUS'] : 'Unknown';
                          $statusClass = '';

                          switch ($status) 
                          {
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
                                      <span><strong>Email: </strong>" . $row['CONTACT EMAIL'] ." </span>
                                      <span><strong>Contact Number: </strong> " . $row['CONTACT PHONE'] ."</span>
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
                      }
                      else
                      {
                        echo "<tr>
                                <td colspan='7' class='text-center text-danger'>
                                    No bookings found.
                                </td>
                              </tr>";
                      }
                    if ($res1) 
                    {
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
          </div>

          <div class="tab-pane fade" id="pills-pending" role="tabpanel" aria-labelledby="pills-pending-tab" tabindex="0">
            <?php include 'client-tablePending.php'; ?>
          </div>


          <div class="tab-pane fade" id="pills-confirmed" role="tabpanel" aria-labelledby="pills-confirmed-tab" tabindex="0">
            <?php include 'client-tableConfirmed.php'; ?>
          </div>

          <div class="tab-pane fade" id="pills-cancelled" role="tabpanel" aria-labelledby="pills-cancelled-tab" tabindex="0">
            <?php include 'client-tableCancelled.php'; ?>
          </div>
        </div> 
      </div>    
    </div>
  </div>
</div>

<script>
  function toggleSubMenu(submenuId) 
  {
    const submenu = document.getElementById(submenuId);
    const sectionTitle = submenu.previousElementSibling;
    const chevron = sectionTitle.querySelector('.chevron-icon'); 

    // Check if the submenu is already open
    const isOpen = submenu.classList.contains('open');

    // If it's open, we need to close it, and reset the chevron
    if (isOpen) 
    {
      submenu.classList.remove('open');
      chevron.style.transform = 'rotate(0deg)';
    } 
    else 
    {
      // First, close all open submenus and reset all chevrons
      const allSubmenus = document.querySelectorAll('.submenu');
      const allChevrons = document.querySelectorAll('.chevron-icon');
      
      allSubmenus.forEach(sub => 
      {
        sub.classList.remove('open');
      });

      allChevrons.forEach(chev => 
      {
        chev.style.transform = 'rotate(0deg)';
      });

      // Now, open the current submenu and rotate its chevron
      submenu.classList.add('open');
      chevron.style.transform = 'rotate(180deg)';
    }
  }
</script>

<!-- Row Click Selection JS -->
<script>
  document.addEventListener("DOMContentLoaded", function() 
  {
    document.querySelectorAll("tr[data-url]").forEach(function(row) 
    {
      row.addEventListener("click", function() 
      {
        const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

        console.log("Transaction Number: ", transactionNumber); // Debugging line

        // Use AJAX to send the transaction number to the server
        $.ajax(
        {
          url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
          type: 'POST',
          data: { transaction_number: transactionNumber },
          success: function(response) 
          {
            console.log("Response: ", response); // Debugging line

            // Redirect to the next page after successfully setting the session
            window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
          },
          error: function(xhr, status, error)
          {
            console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
          }
        });
      });
    });
  });
</script>

<!-- JQuery Datapicker -->
<script>
  document.addEventListener("scroll", function () 
  {
    const searchBar = document.querySelector(".search-bar");
    const scrollPosition = window.scrollY;

    // Add or remove the upward adjustment class based on scroll position
    if (scrollPosition > 70) 
    { // Adjust the threshold as needed
      searchBar.classList.add("scrolled-upward");
    } 
    else 
    {
      searchBar.classList.remove("scrolled-upward");
    }
  });
</script>

<!-- DataTables #product-table -->
<script>
  $(document).ready(function () 
  {
    const table = $('#product-table').DataTable(
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
      table.search(this.value).draw();
    });

    // Update the custom pagination buttons and page info
    function updatePagination() 
    {
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
    $('#prevPage').on('click', function() 
    {
      table.page('previous').draw('page');
      updatePagination();
    });

    $('#nextPage').on('click', function() 
    {
      table.page('next').draw('page');
      updatePagination();
    });

    // Initialize pagination on first load
    updatePagination();

    // Status Filter
    $('#status').on('change', function () 
    {
        const selectedStatus = $(this).val();
        table.column(8).search(selectedStatus || '').draw();
    });

    // Package Filter
    $('#packages').on('change', function () 
    {
      const selectedPackage = $(this).val();
      table.column(3).search(selectedPackage || '').draw();
    });

    // Booking Date Filter with value change
    $('#BookingStartDate').on('change', function () 
    {
      const selectedBookingDate = $(this).val();  // Get the selected value directly from the input field
      console.log("Booking Date Filter:", selectedBookingDate);  // Log the selected booking date
      table.column(4).search(selectedBookingDate || '').draw();  // Column 4 (index starts at 0)
    });

    // Flight Date Filter with value change
    $('#FlightStartDate').on('change', function () 
    {
      const selectedFlightDate = $(this).val();  // Get the selected value directly from the input field
      console.log("Flight Date Filter:", selectedFlightDate);  // Log the selected flight date
      table.column(5).search(selectedFlightDate || '').draw();  // Column 5 (index starts at 0)
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
        table.column(5).search(flightStartDate || '').draw();  // Column 5 (index starts at 0)
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
        table.column(5).search(flightStartDate || '').draw(); // Column 5 (index starts at 0)
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
        table.column(4).search(bookingStartDate || '').draw();  // Column 5 (index starts at 0)
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

      // Update the table column search
      table.column(5).search(bookingStartDate || '').draw(); // Column 5 (index starts at 0)

      console.log("BookingStartDate Input Value (on input): " + value);
    });

    // Clear All Filters
    $('#clearSorting').on('click', function () 
    {
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

<script>
  function addGuestInfo(transactionNumber) 
  {
    console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
    $.ajax(
    {
      url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
      type: 'POST',
      data: { transaction_number: transactionNumber },
      success: function(response) 
      {
        console.log("Response: ", response); // Debug line (To Remove in Prod)
        window.location.href = '../Agent Section/agent-addGuest.php'; // Redirect to your next page
      },
      error: function(xhr, status, error) 
      {
        console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
      }
    });
  }

  function showGuestInfo(transactionNumber) 
  {
    console.log("Transaction Number: ", transactionNumber); // Debug line
    // Use AJAX to send the transaction number to the server
    $.ajax(
    {
      url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
      type: 'POST',
      data: { transaction_number: transactionNumber },
      success: function(response) 
      {
        console.log("Response: ", response); // Debug line
        // Redirect to the next page after setting the session
        window.location.href = '../Agent Section/agent-showGuest.php'; // Redirect to your next page
      },
      error: function(xhr, status, error) 
      {
        console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
      }
    });
  }

  function showRequestHistory(transactionNumber)  
  {
    console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
    // Use AJAX to send the transaction number to the server
    $.ajax(
    {
      url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
      type: 'POST',
      data: { transaction_number: transactionNumber },
      success: function(response) 
      {
        console.log("Response: ", response); // Debug line
        // Redirect to the next page after setting the session
        window.location.href = '../Agent Section/agent-showRequest.php'; // Redirect to your next page
      },
      error: function(xhr, status, error) 
      {
        console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
      }
    });
  }

  function showPaymentHistory(transactionNumber) 
  {
    console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
    
    $.ajax(
    {
      url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
      type: 'POST',
      data: { transaction_number: transactionNumber },
      success: function(response) 
      {
        console.log("Response: ", response); // Debug line
        // Redirect to the next page after setting the session
        window.location.href = '../Agent Section/agent-showPayment.php'; // Redirect to your next page
      },
      error: function(xhr, status, error) 
      {
        console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
      }
    });
  }
</script>

<script>
  function addGuestInfo(transactionNumber) 
  {
    console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
    $.ajax(
    {
      url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
      type: 'POST',
      data: { transaction_number: transactionNumber },
      success: function(response) 
      {
        console.log("Response: ", response); // Debug line (To Remove in Prod)
        window.location.href = '../Agent Section/agent-addGuest.php'; // Redirect to your next page
      },
      error: function(xhr, status, error) 
      {
        console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
      }
    });
  }

  function showGuestInfo(transactionNumber) 
  {
    console.log("Transaction Number: ", transactionNumber); // Debug line
    // Use AJAX to send the transaction number to the server
    $.ajax(
    {
      url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
      type: 'POST',
      data: { transaction_number: transactionNumber },
      success: function(response) 
      {
        console.log("Response: ", response); // Debug line
        // Redirect to the next page after setting the session
        window.location.href = '../Agent Section/agent-showGuest.php'; // Redirect to your next page
      },
      error: function(xhr, status, error) 
      {
        console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
      }
    });
  }

  function showRequestHistory(transactionNumber)  
  {
    console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
    // Use AJAX to send the transaction number to the server
    $.ajax(
    {
      url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
      type: 'POST',
      data: { transaction_number: transactionNumber },
      success: function(response) 
      {
        console.log("Response: ", response); // Debug line
        // Redirect to the next page after setting the session
        window.location.href = '../Agent Section/agent-showRequest.php'; // Redirect to your next page
      },
      error: function(xhr, status, error) 
      {
        console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
      }
    });
  }


  function showPaymentHistory(transactionNumber) 
  {
    console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
    
    $.ajax(
    {
      url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
      type: 'POST',
      data: { transaction_number: transactionNumber },
      success: function(response) 
      {
        console.log("Response: ", response); // Debug line
        // Redirect to the next page after setting the session
        window.location.href = '../Agent Section/agent-showPayment.php'; // Redirect to your next page
      },
      error: function(xhr, status, error) 
      {
        console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
      }
    });
  }
</script>

<?php require "../Agent Section/includes/scripts.php"; ?>

  </body>
</html>