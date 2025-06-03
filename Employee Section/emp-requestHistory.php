<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee - Transactions</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>
  <link rel="stylesheet"
    href="../Employee Section/assets/css/emp-transactionRequestPayment.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
</head>

<body>

  <?php include '../Employee Section/includes/emp-sidebar.php' ?>

  <!-- Main Container -->
  <div class="main-container">

    <div class="navbar">
      <div class="page-header-wrapper">

        <div class="page-header-top">
          <div class="back-btn-wrapper">
            <button class="back-btn" id="redirect-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
          </div>
        </div>

        <div class="page-header-content">
          <div class="page-header-text">
            <h5 class="header-title">Request</h5>
          </div>
        </div>

      </div>
    </div>

    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Employee Section/emp-dashboard.php'; // Replace with your actual URL
      });
    </script>

    <div class="main-content">
      <div class="table-container">

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
                  <input type="text" class="datepicker" id="FlightStartDate" placeholder="Request Date">
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
          <table class="product-table" id="product-table">
            <thead>
              <tr>
                <th>TRANSACT NO</th>
                <th>BRANCH</th>
                <th>FLIGHT DATE</th>
                <th>REQUEST TITLE</th>
                <th>REQUEST DETAILS</th>
                <th>SPECIFIC DETAILS</th>
                <th>TOTAL PAX</th>
                <th>TOTAL AMOUNT</th>
                <th>REQUEST DATE</th>
                <th>STATUS</th>
                <th>REQUEST REMARKS</th>
                <th style='display:none;'>RAW REQUEST DATE</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql1 = "SELECT r.requestId, r.transactNo AS `TransactNo`, br.branchName,
													c.concernTitle AS `RequestTitle`, cd.details AS `RequestDetails`, b.pax AS `TotalPax`,
													r.requestCost as requestCost, r.requestRemarks,
													r.customRequest as customRequest, r.details as details, r.requestDate, 
													r.requestStatus AS `Status`, f.flightDepartureDate AS `FlightDate`
												FROM request r
												LEFT JOIN concern c ON r.concernId = c.concernId
												LEFT JOIN concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
												LEFT JOIN booking b ON r.transactNo = b.transactNo
                        JOIN flight f ON b.flightId = f.flightId
												LEFT JOIN branch br ON br.branchAgentCode = b.agentCode
												WHERE r.requestStatus = 'Confirmed'
												GROUP BY r.requestId";

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
                  $requestId = $row['requestId'];
                  $formattedRequestCost = number_format($row['requestCost'], 2);
                  $formattedRequestDate = date("m.d.Y", strtotime($row['requestDate']));
                  $formattedRequestDateFilter = date('Y-m-d', strtotime($row['requestDate']));
                  $formattedFlightDate = date('Y.m.d', strtotime($row['FlightDate']));

                  // Output table row with data-transactno attribute
                  echo "<tr data-transactno='{$row['TransactNo']}' data-requestid='{$requestId}' class='transaction-row'>
													<td>{$row['TransactNo']}</td>
													<td>{$row['branchName']}</td>
													<td>{$formattedFlightDate}</td>
                          <td>{$title}</td>
													<td>{$details}</td>
													<td>{$row['details']}</td>
													<td>{$row['TotalPax']}</td>
													<td>₱ {$formattedRequestCost}</td>
													<td>{$formattedRequestDate}</td>
													<td>{$row['Status']}</td>
													<td>{$row['requestRemarks']}</td>
													<td style='display:none;'>{$formattedRequestDateFilter}</td> <!-- hidden raw date -->
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

  <?php include '../Employee Section/includes/emp-scripts.php' ?>
  <!-- Add in your <head> or before </body> -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


  <!-- JQuery Datapicker -->
  <script>
    document.addEventListener("scroll", function () {
      const searchBar = document.querySelector(".search-bar");
      const scrollPosition = window.scrollY;

      // Add or remove the upward adjustment class based on scroll position
      if (scrollPosition > 70) { // Adjust the threshold as needed
        searchBar.classList.add("scrolled-upward");
      }
      else {
        searchBar.classList.remove("scrolled-upward");
      }
    });
  </script>

  <!-- DataTables #product-table -->
  <script>
    $(document).ready(function () {
      const table = $('#product-table').DataTable({
        dom: 'rtip',
        language: { emptyTable: "No Transaction Records Available" },
        order: [[0, 'asc']], // Sort by Transaction ID
        scrollX: false,
        scrollY: '69vh',
        paging: true,
        pageLength: 20,
        autoWidth: false,
        columnDefs: [
          {
            targets: [1, 2, 3, 5, 6],
            orderable: false
          },
          { targets: 0, width: "120px" }, // TRANSACT NO
          { targets: 1, width: "150px" }, // BRANCH
          { targets: 2, width: "130px" }, // FLIGHT DATE
          { targets: 3, width: "160px" }, // REQUEST TITLE
          { targets: 4, width: "180px" }, // REQUEST DETAILS
          { targets: 5, width: "160px" }, // SPECIFIC DETAILS
          { targets: 6, width: "80px" },  // TOTAL PAX
          { targets: 7, width: "120px" }, // TOTAL AMOUNT
          { targets: 8, width: "130px" }, // REQUEST DATE
          { targets: 9, width: "120px" }, // STATUS
          { targets: 10, width: "160px" }, // REQUEST REMARKS
          { targets: 11, visible: false }, // RAW REQUEST DATE
          { targets: [1,2,3,5,6,9,10], orderable: false }
        ]
      });

      // Global search
      $('#search').on('keyup', function () {
        table.search(this.value).draw();
      });

      // Custom Pagination Info
      function updatePagination() {
        const info = table.page.info();
        $('#pageInfo').text(`Page ${info.page + 1} of ${info.pages}`);
        $('#prevPage').prop('disabled', info.page === 0);
        $('#nextPage').prop('disabled', info.page + 1 === info.pages);
      }

      $('#prevPage').on('click', function () {
        table.page('previous').draw('page');
        updatePagination();
      });

      $('#nextPage').on('click', function () {
        table.page('next').draw('page');
        updatePagination();
      });

      updatePagination(); // On load

      // Flight Date Filter
      $('#FlightStartDate').on('change', function () {
        table.column(11).search($(this).val() || '').draw();
      });

      // Datepickers
      $("#FlightStartDate").datepicker({
        dateFormat: "yy-mm-dd",
        showAnim: "fadeIn",
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2100",
        onSelect: function (dateText) {
          $(this).val(dateText);
          table.column(11).search(dateText || '').draw();
        }
      });


      // Clear all filters
      $('#clearSorting').on('click', function () {
        $('#search').val('');
        table.search('').draw();

        $('#status, #packages').val('All').change();

        $('#BookingStartDate, #FlightStartDate').val('').trigger('change');

        table.draw();
      });
    });
  </script>

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



</body>

</html>