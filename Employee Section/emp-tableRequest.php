<?php  session_start(); ?>

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
      <!-- <div class="table-header">
        <div class="header-left">
          <div class="table-tabs">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Home</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Profile</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Contact</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false" disabled>Disabled</button>
              </li>
            </ul>
        </div> -->
      

      <div class="table-subheader d-flex align-items-center justify-content-between p-3 border-bottom bg-light">
        <!-- Search -->
        <div class="search-wrapper d-flex align-items-center">
          <input
            type="text"
            placeholder="Search..."
            class="form-control search-input me-2"
          />
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
              aria-expanded="false">
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



      <div class="table-wrapper">
        <table class="">
          <thead>
            <tr>
              <th>Request Id</th>
              <th>Transact No</th>
              <th>Agent Name</th>
              <th>Request Title</th>
              <th>Request Details</th>
              <th>Specific Details</th>
              <th>Total Pax</th>
              <th>Total Amount</th>
              <th>Request Date</th>
              <th>Status</th>
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
                          r.requestStatus AS `Status`
                      FROM 
                          request r
                      LEFT JOIN 
                          concern c ON r.concernId = c.concernId
                      LEFT JOIN 
                          concerndetails cd ON r.concernDetailsId = cd.concernDetailsId
                      LEFT JOIN 
                          booking b ON r.transactNo = b.transactNo
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
                          <td>{$row['requestId']}</td>
                          <td>{$row['TransactNo']}</td>
                          <td>{$row['AgentName']}</td>
                          <td>{$title}</td>
                          <td>{$details}</td>
                          <td>{$row['details']}</td>
                          <td>{$row['TotalPax']}</td>
                          <td>{$row['requestCost']}</td>
                          <td>{$row['RequestDate']}</td>
                          <td>
                            <span class='badge rounded-pill {$badgeClass} p-2'>{$status}</span>
                          </td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='9' style='text-align: center;'>No Requests Found</td></tr>";
              }
            ?>
          </tbody>
        </table>
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

<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
