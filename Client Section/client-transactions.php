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
        <div class="page-header-wrapper">

          <!-- <div class="page-header-top">
            <div class="back-btn-wrapper">
              <button class="back-btn" id="redirect-btn">
                <i class="fas fa-chevron-left"></i>
              </button>
            </div>
          </div> -->

          <div class="page-header-content">
            <div class="page-header-text">
              <h5 class="header-title">Transaction History</h5>
            </div>
          </div>

        </div>
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
            <table id="product-table" class="product-table">
              <thead>
                <tr>
                  <th>Transaction ID</th>
                  <th>Contact Person Info</th>
                  <th>Contact Details</th>
                  <th>Branch Name</th>
                  <th>Flight Date</th>
                  <th>Total Pax</th>
                  <th>Package Price</th>
                  <th>Total Request Cost</th>
                  <th>Amount Paid Balance</th>
                  <th>Balance</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql1 = "SELECT b.transactNo AS `T.N`, p.packageName AS `PACKAGE`, DATE_FORMAT(b.bookingDate, '%m-%d-%Y') AS `TRANSACTION DATE`, 
                            b.bookingType as bookingType, DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y') AS `FLIGHT DATE`, b.pax AS `TOTAL PAX`, 
                            CONCAT(b.lName, ', ', b.fName, ' ', CASE WHEN b.mName = 'N/A' THEN '' 
                            ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ', CASE WHEN b.suffix = 'N/A' THEN '' 
                            ELSE b.suffix END) AS `CONTACT NAME`, br.branchName as branchName,
                            b.email AS `CONTACT EMAIL`, CONCAT(b.countryCode, ' ', b.contactNo) AS `CONTACT PHONE`, b.status AS `STATUS`, 
                            COALESCE(SUM(r.requestCost), 0) AS TotalRequestAmount, b.totalPrice AS PackagePrice, 
                            COALESCE(SUM(pa.amount), 0) AS TotalAmountPaid
                          FROM booking b
                          LEFT JOIN flight f ON b.flightId = f.flightId
                          LEFT JOIN package p ON b.packageId = p.packageId
                          LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
                          JOIN branch br ON b.agentCode = br.branchAgentCode
                          LEFT JOIN payment pa ON pa.transactNo = b.transactNo AND pa.paymentStatus = 'Approved'
                          LEFT JOIN request r ON r.transactNo = b.transactNo AND r.requestStatus = 'Confirmed'
                          WHERE b.accountId = $accountId 
                          GROUP BY b.transactNo
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

                      $packagePrice = number_format($row['PackagePrice'] ?? 0, 2);
                      $requestTotal = number_format($row['TotalRequestAmount'] ?? 0, 2);
                      $amountPaid = number_format($row['TotalAmountPaid'] ?? 0, 2);

                      // Calculate numeric balance first, then format
                      $rawBalance = max(($row['PackagePrice'] ?? 0) + ($row['TotalRequestAmount'] ?? 0) - ($row['TotalAmountPaid'] ?? 0), 0);
                      $balance = number_format($rawBalance, 2);
                      // Booking Date
                      // <td>{$row['TRANSACTION DATE']}</td>

                      echo "<tr data-url='agent-showGuest.php?id=" . htmlspecialchars($transactNo) . "'>
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
                              <td>₱ $packagePrice</td>
                              <td>₱ $requestTotal</td>
                              <td>₱ $amountPaid</td>
                              <td>₱ {$balance}</td>
                              <td>
                                <span class='badge p-2 rounded-pill {$statusClass}'>
                                  {$status}
                                </span>
                            </td>
                          </tr>";
                    }
                  }
      
                  if ($res1) 
                  {
                    $res1->free();
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

  <!-- DataTables #product-table script working-->
  <script>
    $(document).ready(function() 
    {
      const table = $('#product-table').DataTable(
      {
        dom: 'rtip',
        language: { emptyTable: "No Transaction Records Available" },
        order: [[0, 'desc']], // Sort by Transaction ID descending
        scrollX: true, // enable horizontal scrolling to prevent text overflow
        scrollY: '66.1vh',
        paging: true,
        pageLength: 11,
        autoWidth: true, // allow automatic column width adjustment
        autoHeight: false,
        columnDefs: 
        [
          { targets: [1, 2, 3, 5, 6, 7, 8, 9, 10], orderable: false }
          // Only Transaction ID (index 0) remains orderable
        ]
      });

      // Search Functionality
      $('#search').on('keyup', function() 
      {
        table.search(this.value).draw();
      });

      // Update Pagination
      function updatePagination() 
      {
        const info = table.page.info();
        const currentPage = info.page + 1;
        const totalPages = info.pages;
        $('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);
        $('#prevPage').prop('disabled', currentPage === 1);
        $('#nextPage').prop('disabled', currentPage === totalPages);
      }

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

      updatePagination(); // Initialize pagination

      // Package Filter
      $('#packages').on('change', function() 
      {
        const selectedPackage = $(this).val();
        table.column(3).search(selectedPackage || '').draw();
      });

      $("#FlightStartDate").datepicker(
      {
        dateFormat: "mm-dd-yy",
        showAnim: "fadeIn",
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2100",
        onSelect: function(dateText) 
        {
          console.log("FlightStartDate Selected:", dateText);
          table.column(4).search(dateText || '').draw();
        }
      });

      // Flight Date Filter
      $('#FlightStartDate').on('change', function() 
      {
        const selectedFlightDate = $(this).val();
        console.log("Flight Date Filter:", selectedFlightDate);
        table.column(4).search(selectedFlightDate || '').draw();
      });

      // Clear All Filters
      $('#clearSorting').on('click', function() 
      {
        $('#search').val('');
        table.search('').draw();

        $('#packages').val('All').change();
        
        $('#FlightStartDate').datepicker("setDate", null); // Properly clear date
        table.column(4).search('').draw(); // Explicitly reset column filter

        updatePagination(); // Ensure pagination updates after clearing filters
      });
    });
  </script>

  <!-- Filter Script  working-->
  <script>
    document.addEventListener("DOMContentLoaded", function () 
    {
      // Get the status from the URL
      let statusTab = "<?php echo isset($_GET['status']) ? $_GET['status'] : ''; ?>";
      console.log("Status from URL:", statusTab); // Debugging

      // Find all filter tabs
      let tabs = document.querySelectorAll("#booking-filter-tabs li");

      // Remove 'active' class from all tabs
      tabs.forEach(tab => tab.classList.remove("active"));

      // Find the tab that matches the status
      let matchedTab = [...tabs].find(tab => tab.getAttribute("data-filter") === statusTab);

      if (matchedTab) 
      {
        matchedTab.classList.add("active"); // Highlight the correct tab
        console.log("Activating tab:", matchedTab.innerText);

        
        setTimeout(() => 
        {
          matchedTab.dispatchEvent(new Event("click", { bubbles: true }));
        }, 3);

      } 
      else 
      {
        // Default to "All" if no match found
        let defaultTab = document.querySelector("#booking-filter-tabs li[data-filter='']");
        if (defaultTab) 
        {
          defaultTab.classList.add("active");
          console.log("Activating default tab: All");

          
          setTimeout(() => 
          {
            defaultTab.dispatchEvent(new Event("click", { bubbles: true }));
          }, 100);
        }
      }
    });
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
            data: 
            {
              transaction_number: transactionNumber
            },
            success: function(response) 
            {
              console.log("Response: ", response); // Debugging line

              // Redirect to the next page after successfully setting the session
              window.location.href = `../Client Section/client-transactionInfo.php?id=${transactionNumber}`;

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
    document.addEventListener("scroll", function() 
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

  <!-- Status Sorting tabs -->
  <script>
    document.addEventListener("DOMContentLoaded", function() 
    {
      const tabs = document.querySelectorAll("#booking-filter-tabs li");

      tabs.forEach(tab => 
      {
        tab.addEventListener("click", function() 
        {
          // Remove active class from all tabs
          tabs.forEach(t => t.classList.remove("active"));
          // Add active class to the clicked tab
          this.classList.add("active");

          let filterValue = this.getAttribute("data-filter");

          // Apply DataTables filtering (assuming your table uses DataTables)
          if ($.fn.DataTable.isDataTable("#product-table"))
          {
            $('#product-table').DataTable().column(6).search(filterValue || '', true, false).draw();
          }
        });
      });
    });
  </script>

  <!-- Function for clickable rows  -->
  <script>
    function addGuestInfo(transactionNumber) 
    {
      console.log("Transaction Number: ", transactionNumber); // Debug line (To Remove in Prod)
      $.ajax(
      {
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: 
        {
          transaction_number: transactionNumber
        },
        success: function(response) 
        {
          console.log("Response: ", response); // Debug line (To Remove in Prod)
          window.location.href = '../Client Section/client-transactionInfo.php'; // Redirect to your next page
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
        data: 
        {
          transaction_number: transactionNumber
        },
        success: function(response) 
        {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Client Section/client-transactionInfo.php'; // Redirect to your next page
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
        data: 
        {
          transaction_number: transactionNumber
        },
        success: function(response) 
        {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Client Section/client-transactionInfo.php'; // Redirect to your next page
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
        data: 
        {
          transaction_number: transactionNumber
        },
        success: function(response) 
        {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Client Section/client-transactionInfo.php'; // Redirect to your next page
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