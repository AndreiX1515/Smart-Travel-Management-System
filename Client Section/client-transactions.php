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

          <div class="navpills-container">
            <ul class="filter-tabs" id="booking-filter-tabs">
              <li class="active" data-filter="">All
                <span class="badge">
                  <?php
                  $sql = "SELECT COUNT(*) AS totalBookings FROM booking WHERE accountId = $accountId";
                  $result = mysqli_query($conn, $sql);
                  echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                  ?>
                </span>
              </li>

              <li data-filter="Pending">Pending
                <span class="badge">
                  <?php
                  $sql = "SELECT COUNT(*) AS totalBookings FROM booking 
                WHERE accountId = $accountId AND status = 'Pending'";
                  $result = mysqli_query($conn, $sql);
                  echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                  ?>
                </span>
              </li>

              <li data-filter="Reserved">Reserved
                <span class="badge">
                  <?php
                  $sql = "SELECT COUNT(*) AS totalBookings FROM booking 
                WHERE accountId = $accountId AND status = 'Reserved'";
                  $result = mysqli_query($conn, $sql);
                  echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                  ?>
                </span>
              </li>

              <li data-filter="Confirmed">Confirmed
                <span class="badge">
                  <?php
                  $sql = "SELECT COUNT(*) AS totalBookings FROM booking 
                WHERE accountId = $accountId AND status = 'Confirmed'";
                  $result = mysqli_query($conn, $sql);
                  echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                  ?>
                </span>
              </li>

              <li data-filter="Cancelled">Cancelled
                <span class="badge">
                  <?php
                  $sql = "SELECT COUNT(*) AS totalBookings FROM booking 
                WHERE accountId = $accountId AND status = 'Cancelled'";
                  $result = mysqli_query($conn, $sql);
                  echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                  ?>
                </span>
              </li>
            </ul>
          </div>

          <div class="table-container">
            <table id="product-table" class="product-table">
              <thead>
                <tr>
                  <th>Transaction ID</th>
                  <th>Contact Person Info</th>
                  <th>Contact Details</th>
                  <th>Branch Name</th>
                  <th>Flight Date</th>
                  <th>Total Pax</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $accountId = $_SESSION['client_accountId'];
                $sql1 = "SELECT b.transactNo AS `T.N`, CONCAT(b.lName, ', ', b.fName, ' ', 
                CASE WHEN b.mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ', 
                CASE WHEN b.suffix = 'N/A' THEN '' ELSE b.suffix END) AS `CONTACT NAME`, 
                br.branchName AS branchName, 
                DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y') AS `FLIGHT DATE`, 
                b.pax AS `TOTAL PAX`, b.email AS `CONTACT EMAIL`, 
                CONCAT(b.countryCode, ' ', b.contactNo) AS `CONTACT PHONE`, 
                b.status AS `STATUS` FROM booking b 
                LEFT JOIN flight f ON b.flightId = f.flightId 
                LEFT JOIN branch br ON b.agentCode = br.branchAgentCode 
                WHERE b.accountId = '$accountId' 
                ORDER BY b.transactNo DESC";

                $res1 = $conn->query($sql1);
                if ($res1->num_rows > 0) {
                  while ($row = $res1->fetch_assoc()) {
                    $statusClass = match ($row['STATUS']) {
                      'Confirmed' => 'bg-success text-white',
                      'Cancelled' => 'bg-danger text-white',
                      'Pending' => 'bg-warning text-dark',
                      default => 'bg-secondary text-white',
                    };
                    echo "<tr data-url='client-transactionInfo.php?id=" . htmlspecialchars($row['T.N']) . "'>
                  <td>{$row['T.N']}</td>
                  <td>{$row['CONTACT NAME']}</td>
                  <td>
                    <div class='d-flex flex-column'>
                      <span><strong>Email: </strong>{$row['CONTACT EMAIL']}</span>
                      <span><strong>Contact Number: </strong>{$row['CONTACT PHONE']}</span>
                    </div>
                  </td>
                  <td>{$row['branchName']}</td>
                  <td>{$row['FLIGHT DATE']}</td>
                  <td style='text-align: center; font-weight: bold;'>{$row['TOTAL PAX']}</td>
                  <td><span class='badge p-2 rounded-pill {$statusClass}'>{$row['STATUS']}</span></td>
                </tr>";
                  }
                } else {
                  echo "<tr><td colspan='7' class='text-center text-danger'>No bookings found.</td></tr>";
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
  </div>

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

  <!-- Row Click Selection JS -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll("tr[data-url]").forEach(function(row) {
        row.addEventListener("click", function() {
          const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

          console.log("Transaction Number: ", transactionNumber); // Debugging line

          // Use AJAX to send the transaction number to the server
          $.ajax({
            url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
            type: 'POST',
            data: {
              transaction_number: transactionNumber
            },
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
  </script>

  <!-- JQuery Datapicker -->
  <script>
    document.addEventListener("scroll", function() {
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

  <!-- Status Sorting tabs -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const tabs = document.querySelectorAll("#booking-filter-tabs li");

      tabs.forEach(tab => {
        tab.addEventListener("click", function() {
          // Remove active class from all tabs
          tabs.forEach(t => t.classList.remove("active"));
          // Add active class to the clicked tab
          this.classList.add("active");

          let filterValue = this.getAttribute("data-filter");

          // Apply DataTables filtering (assuming your table uses DataTables)
          if ($.fn.DataTable.isDataTable("#product-table")) {
            $('#product-table').DataTable().column(6).search(filterValue || '', true, false).draw();
          }
        });
      });
    });
  </script>

  <!-- DataTables #product-table -->
  <script>
    $(document).ready(function() {
    const table = $('#product-table').DataTable({
        dom: 'rtip',
        language: { emptyTable: "No Transaction Records Available" },
        order: [[0, 'desc']],
        scrollX: false,
        scrollY: '66.1vh',
        paging: true,
        pageLength: 11,
        autoWidth: false,
        autoHeight: false,
        columnDefs: [
            { targets: [1, 2, 3, 5, 6], orderable: false }
        ]
    });

    // Search Functionality
    $('#search').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Update Pagination
    function updatePagination() {
        const info = table.page.info();
        const currentPage = info.page + 1;
        const totalPages = info.pages;
        $('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);
        $('#prevPage').prop('disabled', currentPage === 1);
        $('#nextPage').prop('disabled', currentPage === totalPages);
    }

    $('#prevPage').on('click', function() {
        table.page('previous').draw('page');
        updatePagination();
    });

    $('#nextPage').on('click', function() {
        table.page('next').draw('page');
        updatePagination();
    });

    updatePagination(); // Initialize pagination

    // Package Filter
    $('#packages').on('change', function() {
        const selectedPackage = $(this).val();
        table.column(3).search(selectedPackage || '').draw();
    });

    $("#FlightStartDate").datepicker({
        dateFormat: "mm-dd-yy",
        showAnim: "fadeIn",
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2100",
        onSelect: function(dateText) {
            console.log("FlightStartDate Selected:", dateText);
            table.column(4).search(dateText || '').draw();
        }
    });

    // Flight Date Filter
    $('#FlightStartDate').on('change', function() {
        const selectedFlightDate = $(this).val();
        console.log("Flight Date Filter:", selectedFlightDate);
        table.column(4).search(selectedFlightDate || '').draw();
    });

    // Clear All Filters
    $('#clearSorting').on('click', function() {
        $('#search').val('');
        table.search('').draw();

        $('#packages').val('All').change();
        
        $('#FlightStartDate').datepicker("setDate", null); // Properly clear date
        table.column(4).search('').draw(); // Explicitly reset column filter

        updatePagination(); // Ensure pagination updates after clearing filters
    });
});

  </script>








  <script>
    function addGuestInfo(transactionNumber) {
      console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
      $.ajax({
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: {
          transaction_number: transactionNumber
        },
        success: function(response) {
          console.log("Response: ", response); // Debug line (To Remove in Prod)
          window.location.href = '../Agent Section/agent-addGuest.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }

    function showGuestInfo(transactionNumber) {
      console.log("Transaction Number: ", transactionNumber); // Debug line
      // Use AJAX to send the transaction number to the server
      $.ajax({
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: {
          transaction_number: transactionNumber
        },
        success: function(response) {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Agent Section/agent-showGuest.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }

    function showRequestHistory(transactionNumber) {
      console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
      // Use AJAX to send the transaction number to the server
      $.ajax({
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: {
          transaction_number: transactionNumber
        },
        success: function(response) {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Agent Section/agent-showRequest.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }

    function showPaymentHistory(transactionNumber) {
      console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)

      $.ajax({
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: {
          transaction_number: transactionNumber
        },
        success: function(response) {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Agent Section/agent-showPayment.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }
  </script>

  <script>
    function addGuestInfo(transactionNumber) {
      console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
      $.ajax({
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: {
          transaction_number: transactionNumber
        },
        success: function(response) {
          console.log("Response: ", response); // Debug line (To Remove in Prod)
          window.location.href = '../Agent Section/agent-addGuest.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }

    function showGuestInfo(transactionNumber) {
      console.log("Transaction Number: ", transactionNumber); // Debug line
      // Use AJAX to send the transaction number to the server
      $.ajax({
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: {
          transaction_number: transactionNumber
        },
        success: function(response) {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Agent Section/agent-showGuest.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }

    function showRequestHistory(transactionNumber) {
      console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
      // Use AJAX to send the transaction number to the server
      $.ajax({
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: {
          transaction_number: transactionNumber
        },
        success: function(response) {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Agent Section/agent-showRequest.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }


    function showPaymentHistory(transactionNumber) {
      console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)

      $.ajax({
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: {
          transaction_number: transactionNumber
        },
        success: function(response) {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Agent Section/agent-showPayment.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }
  </script>

  <?php require "../Agent Section/includes/scripts.php"; ?>

</body>

</html>