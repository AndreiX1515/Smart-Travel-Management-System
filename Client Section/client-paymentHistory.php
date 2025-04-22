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
        <h5 class="title-page">Guest Information List</h5>
      </div>

      <?php
        $statusTab = isset($_GET['status']) ? $_GET['status'] : '';
      ?>

      <div class="main-content">
        <div class="table-wrapper">

          <!-- Filter Inputs -->
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
                  Clear
                </button>
              </div>
            </div>
          </div>

          <!-- Table  -->
          <div class="table-container">
            <table id="product-table" class="product-table">
              <thead>
                <tr>
                  <th>TRANSACTION NO</th>
                  <th>AMOUNT</th>
                  <th>PROOF OF PAYMENT</th>
                  <th>PAYMENT DATE</th>
                  <th>STATUS</th>
                  <th>REMARKS</th>
                  <th>FLIGHT DATE</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  if ($agentRole != 'Head Agent')
                  {
                    $sql1 = "SELECT b.transactNo, f.flightDepartureDate, p.paymentId, p.amount, p.filePath, p.paymentDate, p.paymentStatus, 
                              p.paymentRemarks
                            FROM `booking` b
                            JOIN `flight` f ON b.flightId = f.flightId
                            JOIN `payment` p ON b.transactNo = p.transactNo
                            WHERE b.accountId = $accountId
                            ORDER BY p.paymentId ASC";

                    // Execute the query
                    $result1 = $conn->query($sql1);

                    // Check if query execution was successful
                    if (!$result1) 
                    {
                      die("Query error: " . $conn->error);
                    }

                    // Fetch results and display rows
                    if ($result1->num_rows > 0) 
                    {
                      while ($row = $result1->fetch_assoc()) 
                      {
                        $amount = number_format($row['amount'], 2);
                        $date = date("F d, Y", strtotime($row['paymentDate']));
                        $remarks = !empty($row['paymentRemarks']) ? $row['paymentRemarks'] : 'N/A';
                        $flightDate = date("m-d-Y", strtotime($row['flightDepartureDate']));

                        $status = isset($row['paymentStatus']) ? $row['paymentStatus'] : 'Unknown';
                        $statusClass = '';

                        switch ($status) 
                        {
                          case 'Approved':
                            $statusClass = 'bg-success text-white'; // Green background, white text
                            break;
                          case 'Rejected':
                            $statusClass = 'bg-danger text-white'; // Red background, white text
                            break;
                          case 'Submitted':
                            $statusClass = 'bg-warning text-dark';
                            break;
                          default:
                            $statusClass = 'bg-secondary text-white';
                        }

                        echo "<tr>
                                <td>" . $row['transactNo'] . "</td>
                                <td>₱ " . $amount . "</td>
                                <td>
                                  <a href='../Agent Section/functions/view-file.php?file=" . urlencode($row['filePath']) . "' target='_blank'>View File</a> 
                                  <a href='../Agent Section/functions/download.php?file=" . urlencode($row['filePath']) . "' target='_blank'>Download File</a> 
                                </td>
                                <td>" . $date . "</td>
                                <td>
                                  <span class='badge p-2 rounded-pill {$statusClass}'>
                                    {$status}
                                  </span>
                                </td>
                                <td>" . $remarks . "</td>
                                <td>" . $flightDate . "</td> <!-- Hidden Flight Date Column -->
                              </tr>";
                      }
                    }
                  }
                  else
                  {
                    $sql1 = "SELECT b.transactNo, f.flightDepartureDate, p.paymentId, p.amount, p.filePath, p.paymentDate, p.paymentStatus, 
                              p.paymentRemarks
                            FROM `booking` b
                            JOIN `flight` f ON b.flightId = f.flightId
                            JOIN `payment` p ON b.transactNo = p.transactNo
                            WHERE b.agentCode = '$agentCode'
                            ORDER BY p.paymentId ASC";

                    // Execute the query
                    $result1 = $conn->query($sql1);

                    // Check if query execution was successful
                    if (!$result1) 
                    {
                      die("Query error: " . $conn->error);
                    }

                    // Fetch results and display rows
                    if ($result1->num_rows > 0) 
                    {
                      while ($row = $result1->fetch_assoc()) 
                      {
                        $amount = number_format($row['amount'], 2);
                        $date = date("F-d-Y", strtotime($row['paymentDate']));
                        $remarks = !empty($row['paymentRemarks']) ? $row['paymentRemarks'] : 'N/A';
                        $flightDate = date("m-d-Y", strtotime($row['flightDepartureDate']));

                        $status = isset($row['paymentStatus']) ? $row['paymentStatus'] : 'Unknown';
                        $statusClass = '';

                        switch ($status) 
                        {
                          case 'Approved':
                            $statusClass = 'bg-success text-white'; // Green background, white text
                            break;
                          case 'Rejected':
                            $statusClass = 'bg-danger text-white'; // Red background, white text
                            break;
                          case 'Submitted':
                            $statusClass = 'bg-warning text-dark';
                            break;
                          default:
                            $statusClass = 'bg-secondary text-white';
                        }

                        echo "<tr>
                                <td>" . $row['transactNo'] . "</td>
                                <td>₱ " . $amount . "</td>
                                <td>
                                  <a href='../Agent Section/functions/view-file.php?file=" . urlencode($row['filePath']) . "' target='_blank'>View File</a> 
                                  <a href='../Agent Section/functions/download.php?file=" . urlencode($row['filePath']) . "' target='_blank'>Download File</a> 
                                </td>
                                <td>" . $date . "</td>
                                <td>
                                  <span class='badge p-2 rounded-pill {$statusClass}'>
                                    {$status}
                                  </span>
                                </td>
                                <td>" . $remarks . "</td>
                                <td>" . $flightDate . "</td> <!-- Hidden Flight Date Column -->
                              </tr>";
                      }
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
        columnDefs: [
          { targets: [1, 2, 3, 5, 6], orderable: false } // Flight Date column hidden
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

      $("#FlightStartDate").datepicker({
        dateFormat: "mm-dd-yy",
        showAnim: "fadeIn",
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2100",
        onSelect: function(dateText) {
          console.log("FlightStartDate Selected:", dateText);
          table.column(6).search(dateText || '').draw(); // Use the correct index for Flight Date
        }
      });

      $('#FlightStartDate').on('change', function() {
        const selectedFlightDate = $(this).val();
        console.log("Flight Date Filter:", selectedFlightDate);
        table.column(6).search(selectedFlightDate || '').draw(); // Keep index consistent
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


  <?php require "../Agent Section/includes/scripts.php"; ?>

</body>

</html>