
<!DOCTYPE html>
<html lang="en">
<head>    
  <title>Client Transaction History</title>
  <?php include 'includes/head.php' ?>
  <link rel="stylesheet" href="assets\css\client-dashboard.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="assets\css\client-navbar.css?v=<?php echo time(); ?>">
</head>

<body>
     <?php include 'client-includes/client-navbar.php'; ?>

  <div class="container-fluid">
    <div id="main-content" class="col-md-9 col-lg-10 w-100 p-5">

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

      <a class="btn btn-primary me-2" href="client-dashboard.php" role="button">Go Back</a>

      <div class="table-responsive px-4">
        <div class="table-controls mb-4">
          <div class="d-flex flex-column">
            <label for="" class="mb-2">Search</label>
            <input type="text" class="search-input" placeholder="Search..." />
          </div>

          <div class="d-flex flex-column">
            <label for="" class="mb-2 ms-2">Package Name</label>
            <select class="sort-dropdown">
                <option value="">Sort by Package Name</option>
                <option value="packageNameAsc">Package Name (A-Z)</option>
                <option value="packageNameDesc">Package Name (Z-A)</option>
            </select>
          </div>

          <div class="d-flex flex-column">
            <label for="" class="mb-2 ms-2">Package Name</label>
            <select class="sort-dropdown">
              <option value="">Sort by Package Name</option>
              <option value="packageNameAsc">Package Name (A-Z)</option>
              <option value="packageNameDesc">Package Name (Z-A)</option>
            </select>
          </div>

          <div class="d-flex flex-column">
            <label for="" class="mb-2 ms-2">Status</label>
            <select class="sort-dropdown">
              <option value="">Sort by Status</option>
              <option value="StatusCompleted">Completed</option>
              <option value="StatusPending">Pending</option>
              <option value="StatusOngoing">Ongoing</option>
              <option value="StatusTerminated">Terminated</option>
            </select>
          </div>

          <!-- <div class="d-flex flex-row align-items-end">
            <button class="add-button">Add Booking</button>
          </div> -->
        </div>
        <table class="table excel-table">
          <thead>
            <tr>
              <th scope="col">Transaction Number</th>
              <th scope="col">Package Name</th>
              <th scope="col">Flight Date</th>
              <th scope="col">Total Pax</th>
              <th scope="col">Amount To Pay</th>
              <th scope="col">Downpayment Total</th>
              <th scope="col">Status</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody> 
            <?php
              // data-package='" . htmlspecialchars($row['packageName']) . "'
              // data-flight='" . htmlspecialchars($row['flightDate']) . "'
              // data-pax='" . htmlspecialchars($row['totalPax']) . "'
              // data-amount='" . number_format($row['amountToPay'], 2) . "'
              // data-downpayment='" . number_format($row['downpayment'], 2) . "'

              $sql1 = "SELECT DISTINCT
                    b.transactNo,
                    b.pax AS totalPax,
                    b.totalPrice AS amountToPay,
                    a.amount as downpayment,
                    b.status, b.agentId, 
                    p.packageName,
                    DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y') AS flightDate
                FROM 
                    booking b
                JOIN 
                    guest g ON b.transactNo = g.transactNo
                JOIN 
                    flight f ON g.flightId = f.flightId
                JOIN 
                    package p ON f.packageId = p.packageId
                JOIN 
                    payment a ON a.transactNo = b.transactNo
                WHERE 
                    b.accountId = '$accId'";

              $res1 = $conn->query($sql1);

              if ($res1->num_rows > 0) 
              {
                while ($row = $res1->fetch_assoc()) 
                {
                  
                  // For Status Auto Adapt
                  $status = htmlspecialchars($row['status']); // Get the status from the row
                  $statusClass = ''; // Variable to hold the class based on status
              
                  // Determine the class based on the status value
                  switch ($status) 
                  {
                    case 'Paid':
                      $statusClass = 'bg-success'; // Green for paid
                      break;
                    case 'Pending':
                      $statusClass = 'bg-warning'; // Yellow for pending
                      break;
                    case 'Overdue':
                      $statusClass = 'bg-danger'; // Red for overdue
                      break;
                    case 'To be confirmed':
                      $statusClass = 'bg-secondary'; // Grey for to be confirmed
                      break;
                    default:
                      $statusClass = 'bg-light text-dark'; // Default styling for unknown status
                      break;
                  }
                 
                  // Each row's modal
                  echo "<tr>
                          <td>" . htmlspecialchars($row['transactNo']) . "</td>
                          <td>" . htmlspecialchars($row['packageName']) . "</td>
                          <td>" . htmlspecialchars($row['flightDate']) . "</td>
                          <td>" . htmlspecialchars($row['totalPax']) . "</td>
                          <td>₱ " . number_format($row['amountToPay'], 2) . "</td>
                          <td>₱ " . number_format($row['downpayment'], 2) . "</td>
                          <td>
                            <span class='badge rounded-pill ". $statusClass . "'>". $status ."</span> 
                          </td>
                          <td>
                            <div class='dropdown text-center' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false' style='cursor: pointer;'>
                              <i class='fa-solid fa-ellipsis-vertical' style='font-size: 18px;'></i>
                              <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <li><a class='dropdown-item' href='#'  
                                  data-bs-toggle='modal' 
                                  data-bs-target='#modal_" . htmlspecialchars($row['transactNo']) . "'
                                  >Request</a>
                                </li>

                                <li><a class='dropdown-item' href='#' 
                                  data-bs-toggle='modal' 
                                  data-bs-target='#downpayment_modal_" . htmlspecialchars($row['transactNo']) . "'
                                  >Pay Downpayment </a>
                                </li>

                                <li><a class='dropdown-item' href='#' 
                                  data-bs-toggle='modal' 
                                  data-bs-target='#payment_history_modal_" . htmlspecialchars($row['transactNo']) . "'>Payment History </a>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>";

                          // <p><strong>Package Name:</strong> <span id='packageName_" . htmlspecialchars($row['transactNo']) . "'>" . htmlspecialchars($row['packageName']) . "</span></p>
                          // <p><strong>Flight Date:</strong> <span id='flightDate_" . htmlspecialchars($row['transactNo']) . "'>" . htmlspecialchars($row['flightDate']) . "</span></p>
                          // <p><strong>Total Pax:</strong> <span id='totalPax_" . htmlspecialchars($row['transactNo']) . "'>" . htmlspecialchars($row['totalPax']) . "</span></p>
                          // <p><strong>Amount to Pay:</strong> ₱ <span id='amountToPay_" . htmlspecialchars($row['transactNo']) . "'>" . number_format($row['amountToPay'], 2) . "</span></p>
                          // <p><strong>Downpayment:</strong> ₱ <span id='downpayment_" . htmlspecialchars($row['transactNo']) . "'>" . number_format($row['downpayment'], 2) . "</span></p>

                    // Modal for Request
                    echo "<div class='modal fade' id='modal_" . htmlspecialchars($row['transactNo']) . "' tabindex='-1' aria-labelledby='modalLabel_" . htmlspecialchars($row['transactNo']) . "' aria-hidden='true'>
                            <div class='modal-dialog modal-dialog-centered'>
                              <div class='modal-content'>
                                <div class='modal-header'>
                                  <h6 class='h5 modal-title' id='modalLabel_" . htmlspecialchars($row['transactNo']) . "'>Request for Transaction " . htmlspecialchars($row['transactNo']) . "</h6>
                                  <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <form action='clientTransactionStatus-code.php' method='POST'>
                                  <div class='modal-body'>
                                    <p><strong>Transaction No:</strong> <span id='transactNo_" . htmlspecialchars($row['transactNo']) . "'>" . htmlspecialchars($row['transactNo']) . "</span></p>
                                    <input type='hidden' name='transactNo' value='" . htmlspecialchars($row['transactNo']) . "'>
                                    <input type='hidden' name='agentId' value='" . $row['agentId'] . "'>
                                    <div class='mb-3'>
                                      <select class='form-select mt-2' name='concern' required>
                                        <option selected disabled>Select Request</option>
                                        <option value='Additional Baggage'>Additional Baggage</option>
                                        <option value='Additional Headcount'>Additional Headcount</option>
                                        <option value='Additional Meal'>Additional Meal</option>
                                        <option value='Hotel Room'>Hotel Room</option>
                                        <option value='Package Only'>Package Only</option>
                                        <option value='Seat Selection'>Seat Selection</option>
                                        <option value='Visa'>Visa</option>
                                      </select>
                                    </div>
                                    <div class='mb-3'>
                                      <label class='form-label'>Details</label>
                                      <textarea class='form-control' name='details' placeholder='Enter Message' rows='4' required></textarea>
                                    </div>     
                                  </div>
                                  <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <button type='submit' name='request' class='btn btn-primary'>Send Request</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>";

                          // Modal - Downpayment
                          echo "<div class='modal fade' id='downpayment_modal_" . htmlspecialchars($row['transactNo']) . "' tabindex='-1' aria-labelledby='downpaymentModalLabel_" . htmlspecialchars($row['transactNo']) . "' aria-hidden='true'>
                                  <div class='modal-dialog modal-dialog-centered'>
                                    <div class='modal-content'>
                                      <div class='modal-header'>
                                        <h6 class='h5 modal-title' id='downpaymentModalLabel_" . htmlspecialchars($row['transactNo']) . "'>Downpayment for Transaction " . htmlspecialchars($row['transactNo']) . "</h6>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                      </div>
                                      <form action='clientTransactionStatus-code.php' method='POST' enctype='multipart/form-data'>
                                        <div class='modal-body'>
                                          <p><strong>Transaction No:</strong> <span id='downpaymentTransactNo_" . htmlspecialchars($row['transactNo']) . "'>" . htmlspecialchars($row['transactNo']) . "</span></p>
                                          <input type='hidden' name='transactNo' value='" . htmlspecialchars($row['transactNo']) . "'>
                                          <input type='hidden' name='agentId' value='" . $row['agentId'] . "'>
                                          
                                          <div class='mb-3'>
                                            <label class='form-label'>Downpayment Amount</label>
                                            <input type='number' class='form-control' name='amount' placeholder='Enter Downpayment Amount' required>
                                          </div>

                                          <div class='mb-3'>
                                            <label class='form-label'>Payment Method</label>
                                            <select class='form-select' name='paymentMethod' required>
                                              <option selected disabled>Select Payment Method</option>
                                              <option value='Credit Card'>Credit Card</option>
                                              <option value='Bank Transfer'>Bank Transfer</option>
                                              <option value='PayPal'>PayPal</option>
                                            </select>
                                          </div>

                                          <div class='mb-3'>
                                            <label class='form-label'>Proof of Payment</label>
                                            <input type='file' class='form-control' name='proof' accept='image/*' placeholder='Enter proof of payment'>
                                          </div>     
                                        </div>
                                        <div class='modal-footer'>
                                          <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                          <button type='submit' name='downpayment' class='btn btn-primary'>Submit Downpayment</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>";

                         // <p><strong>Transaction No:</strong> " . htmlspecialchars($row['transactNo']) . "</p>

                         // Modal - Payment History
                         echo"<div class='modal fade' id='payment_history_modal_" . htmlspecialchars($row['transactNo']) . "' tabindex='-1' aria-labelledby='paymentHistoryLabel_" . htmlspecialchars($row['transactNo']) . "' aria-hidden='true'>
                                <div class='modal-dialog modal-dialog-centered'>
                                  <div class='modal-content'>
                                    <div class='modal-header'>
                                      <h6 class='h5 modal-title' id='paymentHistoryLabel_" . htmlspecialchars($row['transactNo']) . "'>Payment History for Transaction " . htmlspecialchars($row['transactNo']) . "</h6>
                                      <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                      <div class='mb-3'>
                                        <label class='form-label fw-bold'>Payment History</label>

                                      </div>
                                    </div>
                                    <div class='modal-footer'>
                                      <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    </div>
                                  </div>
                                </div>
                              </div>"; 
                }  
              } 

              else 
              {
                echo "<tr><td colspan='8'>No bookings found</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- Logout Confirmation Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to logout?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="client-logout.php" class="btn btn-danger" id="logoutButton">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <?php include 'includes/scripts.php'; ?>

  <script src="heartbeat.js"></script>

  <script>
    $('#logoutButton').on('click', function (e) 
    {
      // Send AJAX request to handle the logout
      $.ajax(
      {
        url: 'client-logout.php', // Your PHP script for logging out
        method: 'POST',
        dataType: 'json',
        success: function (response) 
        {
          if (response.status === 'success') 
          {
            window.location.href = 'login.php';
          }
        },
        error: function () 
        {
          $('#message-1').text('Error logging out. Please try again.').addClass('show'); // Handle error display
          showMessage();
        }
      });
    });
  </script>
</body>

</html>