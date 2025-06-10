<?php  session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee - Transactions</title>
  <?php include '../Employee Section/includes/emp-head.php'?>
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-transactionTableFIT.css?v=<?php echo time(); ?>">
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
        <table class="product-table" id="product-table">
          <thead class="table-light">
            <tr>
              <th>TRANSACT NO.</th>
              <th>HOTEL NAME</th>
              <th>ROOM TYPE</th>
              <th>NUMBER OF ROOMS</th>
              <th>NUMBER OF GUESTS</th>
              <th>TRIP DURATION</th>
              <th>PAYMENT TYPE</th>
              <th>AMOUNT</th>
              <th>PROOF OF PAYMENT</th>
              <th>PAYMENT DATE</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sql1 = "SELECT p.paymentId as paymentId, f.transactionNo as transactNo, p.paymentType as paymentType, p.amount as amount, 
                          p.filePath as filePath, h.hotelName as hotelName, r.rooms as roomName, f.rooms as rooms, f.pax as pax,
                          CONCAT(DATE_FORMAT(f.startDate, '%Y-%m-%d'), ' to ', DATE_FORMAT(f.returnDate, '%Y-%m-%d')) AS tripDuration,
                          p.paymentDate as paymentDate
                        FROM fit f
                        JOIN fithotel h ON h.hotelId = f.hotelId
                        JOIN fitrooms r ON r.roomId = f.roomId
                        JOIN fitpayment p ON p.transactNo = f.transactionNo
                        WHERE p.paymentStatus = 'Submitted'";

              $res1 = $conn->query($sql1);

              if ($res1->num_rows > 0) 
              {
                while ($row = $res1->fetch_assoc()) 
                {
                  // Define the payment type class for styling
                  $paymentTypeClass = $row['paymentType'] === 'Partial Payment' ? 'badge bg-warning text-dark' : 
                                      ($row['paymentType'] === 'Full Payment' ? 'badge bg-success' : 'badge bg-secondary');

                  // Format the payment date
                  $rawPaymentDate = $row['paymentDate'];
                  $formattedDate = (new DateTime($rawPaymentDate))->format('F j, Y');

                  // Output table row
                  echo "<tr class='transaction-row' data-paymentId='{$row['paymentId']}'>
                          <td>{$row['transactNo']}</td>
                          <td>{$row['hotelName']}</td>
                          <td>{$row['roomName']}</td>
                          <td>{$row['rooms']}</td>
                          <td>{$row['pax']}</td>
                          <td>{$row['tripDuration']}</td>
                          <td><span class='$paymentTypeClass p-2'>{$row['paymentType']}</span></td>
                          <td>₱ " . number_format($row['amount'], 2) . "</td>
                          <td>
                            <a class='btn btn-sm btn-primary' href='../Agent Section/functions/view-file.php?file=" . urlencode($row['filePath']) . "' target='_blank'>View</a>
                            <a class='btn btn-sm btn-secondary' href='../Agent Section/functions/download.php?file=" . urlencode($row['filePath']) . "' target='_blank'>Download</a>
                          </td>
                          <td>$formattedDate</td>
                        </tr>";
                }
              } 
              else 
              {
                echo "<tr><td colspan='10' class='text-center'>No Payments Found</td></tr>";
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

<!-- Payment Status Modal-->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Transaction Details - ID: <span id="transactionModalLabel"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../Employee Section/functions/emp-tableFITPayment-code.php" method="POST">
        <div class="modal-body">
          <input type="hidden" id="paymentIdInput" name="paymentId">
          
          <!-- Request Status Section -->
          <div class="mb-4">
            <!-- Request Status Dropdown -->
            <label for="paymentStatus" class="form-label fw-bold">Request Status:</label>
            <select id="paymentStatus" name="paymentStatus" class="form-select">
              <option selected disabled>Select Option</option>
              <option value="Approved">Approved</option>
              <option value="Rejected">Rejected</option>
            </select>
          </div>

          <div class="mb-4">
            <!-- Remarks Input -->
            <label for="paymentRemarks" class="form-label fw-bold">Remarks:</label>
            <input type="text" id="paymentRemarks" name="paymentRemarks" class="form-control" 
              placeholder="Enter remarks or additional comments here">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="updatePaymentStatus" class="btn btn-primary">Update Status</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../Employee Section/includes/emp-scripts.php' ?>

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
              data: { transaction_number: transactionNumber },
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

<?php
  // Fetch the status from the session
  $statusMessage = isset($_SESSION['status']) ? $_SESSION['status'] : '';

  // Set default toast color, and check if status is "Cancelled"
  $toastColor = 'text-bg-primary'; // Default color
  if (isset($_SESSION['status']) && strpos($_SESSION['status'], 'Cancelled') !== false) 
  {
    $toastColor = 'text-bg-danger'; // Change to red for "Cancelled" status
  } 
  elseif (isset($_SESSION['toastColor'])) 
  {
    $toastColor = $_SESSION['toastColor']; // Use session-defined toast color
  }


  if (!empty($statusMessage))
  {
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
  document.addEventListener('DOMContentLoaded', function () 
  {
    // Automatically display the toast if it exists
    const toastElement = document.getElementById('statusToast');
    if (toastElement) 
    {
      const toast = new bootstrap.Toast(toastElement);
      toast.show();
    }
  });
</script>

<script>
  document.querySelectorAll('.viewdownloadfile-wrapper a').forEach((link) => 
  {
    link.addEventListener('click', (event) => 
    {
      if (link.textContent.trim() === 'View File') 
      {
        // Close the modal
        const modal = document.getElementById('transactionModal');
        const bootstrapModal = bootstrap.Modal.getInstance(modal); // Get the active modal instance
        if (bootstrapModal) 
        {
          bootstrapModal.hide(); // Close the modal
        }
      }
    });
  });
</script>

<script>
  // Wait for the DOM to be fully loaded
  document.addEventListener('DOMContentLoaded', function() 
  {
    // Get all the rows with the class 'transaction-row'
    const rows = document.querySelectorAll('.transaction-row');
    
    rows.forEach(row => 
    {
      // Add click event listener to each row
      row.addEventListener('click', function() 
      {
        // Get the transaction number (data attribute)
        const paymentId = row.getAttribute('data-paymentId');
        
        // Set the transaction number in the modal
        document.getElementById('paymentIdInput').value = paymentId;
        document.getElementById('transactionModalLabel').textContent = paymentId
        // Show the modal (using Bootstrap modal)
        const modal = new bootstrap.Modal(document.getElementById('transactionModal'));
        modal.show();
      });
    });
  });
</script>




</body>
</html>
