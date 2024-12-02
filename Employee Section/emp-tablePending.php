<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Transactions</title>
    <?php include '../Employee Section/includes/emp-head.php'?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-tableRequestPayment.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
<body>

<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
  <?php include '../Employee Section/includes/emp-navbar.php' ?>

  <div class="main-content">
    <div class="table-container">
        
      <div class="table-subheader d-flex align-items-center justify-content-between p-3 border-bottom bg-light">
        <!-- Search -->
        <div class="search-wrapper position-relative">
           <input
             type="text"
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

        <!-- Dropdowns -->
        <div class="dropdowns d-flex align-items-center gap-3">
          <!-- Items per Page Dropdown -->
          <div class="dropdown">
            <button
              class="btn btn-outline-secondary dropdown-toggle"
              type="button"
              id="itemsPerPageDropdown"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              Items per Page
            </button>
            <ul class="dropdown-menu" aria-labelledby="itemsPerPageDropdown">
              <li><a class="dropdown-item" href="#">5</a></li>
              <li><a class="dropdown-item" href="#">10</a></li>
              <li><a class="dropdown-item" href="#">50</a></li>
              <li><a class="dropdown-item" href="#">100</a></li>
            </ul>
          </div>

          <!-- Date Range Dropdown -->
          <div class="dropdown">
            <button
              class="btn btn-outline-secondary dropdown-toggle"
              type="button"
              id="dateRangeDropdown"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              Date Range
            </button>
            <ul class="dropdown-menu" aria-labelledby="dateRangeDropdown">
              <li><a class="dropdown-item" href="#">Today</a></li>
              <li><a class="dropdown-item" href="#">This Week</a></li>
              <li><a class="dropdown-item" href="#">This Month</a></li>
              <li><a class="dropdown-item" href="#">Custom Range</a></li>
            </ul>
          </div>

          <!-- Filter Options Dropdown -->
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
              <li><a class="dropdown-item" href="#">Category</a></li>
              <li><a class="dropdown-item" href="#">Priority</a></li>
              <li><a class="dropdown-item" href="#">Custom Filter</a></li>
            </ul>
          </div>

          <!-- Export Options Dropdown -->
          <div class="dropdown">
            <button
              class="btn btn-outline-secondary dropdown-toggle"
              type="button"
              id="exportDropdown"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              Export
            </button>
            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
              <li><a class="dropdown-item" href="#">Export as CSV</a></li>
              <li><a class="dropdown-item" href="#">Export as Excel</a></li>
              <li><a class="dropdown-item" href="#">Export as PDF</a></li>
            </ul>
          </div>

          <div class="clear-button-wrapper">
           <button class="btn btn-danger">
              <i class="fa-solid fa-circle-xmark"></i>
           </button>
          </div>
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
       <table class="">
          <thead>
            <tr>
              <th>Payment Id</th>
              <th>Transact No</th>
              <th>Agent Name</th>
              <th>PAYMENT TITLE</th>
              <th>PAYMENT TYPE</th>
              <th>AMOUNT</th>
              <th>PROOF OF PAYMENT</th>
              <th>PAYMENT DATE</th>
              <th>PAYMENT STATUS</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sql1 = "SELECT p.paymentId, p.transactNo, 
                              CONCAT(a.lName, ', ', a.fName, 
                                  IF(a.mName IS NOT NULL AND a.mName != '', CONCAT(' ', LEFT(a.mName, 1), '.'), '')) AS agentName, 
                              p.paymentTitle, p.paymentType, FORMAT(p.amount, 2) AS amount, 
                              p.filePath, DATE_FORMAT(p.paymentDate, '%m-%d-%Y') AS paymentDate, p.paymentStatus
                          FROM 
                              payment p
                          LEFT JOIN 
                              booking b ON p.transactNo = b.transactNo
                          LEFT JOIN 
                              agent a ON b.agentId = a.agentId
                          WHERE
                              p.paymentStatus = 'Submitted'";

              $res1 = $conn->query($sql1);

              if ($res1->num_rows > 0) {
                while ($row = $res1->fetch_assoc()) {
                  // Determine the badge class for the payment status
                  $status = $row['paymentStatus'];
                  $badgeClass = '';
                  
                  switch ($status) {
                    case 'Submitted':
                      $badgeClass = 'bg-primary'; // Blue for Submitted
                      break;
                    case 'Approved':
                      $badgeClass = 'bg-success'; // Green for Approved
                      break;
                   case 'Rejected':
                    $badgeClass = 'bg-warning text-light'; // Red for Rejected
                    break;

                    default:
                      $badgeClass = 'bg-secondary'; // Gray for unknown statuses
                      break;
                  }

                  // Output table row with data-transactno attribute
                  echo "<tr class='transaction-row' data-paymentId='{$row['paymentId']}'>
                          <td>{$row['paymentId']}</td>
                          <td>{$row['transactNo']}</td>
                          <td>{$row['agentName']}</td>
                          <td>{$row['paymentTitle']}</td>
                          <td>{$row['paymentType']}</td>
                          <td>₱ {$row['amount']}</td>
                          <td>
                              <a href='functions/view-file.php?file=" . urlencode($row['filePath']) . "' target='_blank'>View File</a> 
                              <a href='functions/download.php?file=" . urlencode($row['filePath']) . "' target='_blank'>Download File</a>
                          </td>
                          <td>{$row['paymentDate']}</td>
                          <td>
                              <span class='badge rounded-pill {$badgeClass} py-2'> {$status} </span>
                          </td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='8' style='text-align: center;'>No Payments Found</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>


<!-- Payment Status Modal-->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../Employee Section/functions/emp-tablePayment-code.php" method="POST">
        <div class="modal-body">
          <input type="text" id="paymentIdInput" name="paymentId">
          
          <!-- Request Status Section -->
          <div class="mb-3">
            <label for="paymentStatus" class="form-label"><strong>Booking</strong></label>
            <select id="paymentStatus" name="paymentStatus" class="form-select">
                <option selected disabled>Select Option</option>
                <option value="Confirmed">Approved</option>
                <option value="Reject">Reject</option>
            </select>
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
        
        // Show the modal (using Bootstrap modal)
        const modal = new bootstrap.Modal(document.getElementById('transactionModal'));
        modal.show();
      });
    });
  });
</script>

<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
