
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

                  $_SESSION['transactNo'] = htmlspecialchars($row['transactNo']);
                  $_SESSION['agentId'] = ($row['agentId']);
              
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
                }
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

  <!--  Modal for Request -->
  <div class="modal fade" id="modal_<?php echo $_SESSION['transactNo'];?>" tabindex='-1' aria-labelledby="modalLabel_<?php echo $_SESSION['transactNo'];?>" aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h6 class='h5 modal-title' id="modal_<?php echo $_SESSION['transactNo'];?>">Request for Transaction <?php echo $_SESSION['transactNo'];?></h6>
          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <form action='clientTransactionStatus-code.php' method='POST'>
          <div class='modal-body'>
            <p><strong>Transaction No:</strong> <span id="transactNo_<?php echo $_SESSION['transactNo'];?>"><?php echo $_SESSION['transactNo'];?></span></p>
            <input type='hidden' name='transactNo' value=<?php echo $_SESSION['transactNo'];?>>
            <input type='hidden' name='agentId' value=<?php echo $_SESSION['agentId'];?>>
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
  </div>

  <!-- Modal - Downpayment -->
  <div class='modal fade' id="downpayment_modal_<?php echo $_SESSION['transactNo'];?>" tabindex='-1' aria-labelledby="downpaymentModalLabel_<?php echo $_SESSION['transactNo'];?>" aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h6 class='h5 modal-title' id="downpaymentModalLabel_<?php echo $_SESSION['transactNo'];?>">Downpayment for Transaction <?php echo $_SESSION['transactNo'];?></h6>
          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <form action='clientTransactionStatus-code.php' method='POST' enctype='multipart/form-data'>
          <div class='modal-body'>
            <p><strong>Transaction No:</strong> <span id="downpaymentTransactNo_<?php echo $_SESSION['transactNo'];?>"><?php echo $_SESSION['transactNo'];?></span></p>
            <input type='hidden' name='transactNo' value=<?php echo $_SESSION['transactNo'];?>>
            <input type='hidden' name='agentId' value=<?php echo $_SESSION['agentId'];?>>
            
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
  </div>

  <!-- Modal - Payment History -->
  <div class='modal fade' id="payment_history_modal_<?php echo $_SESSION['transactNo'];?>" tabindex='-1' aria-labelledby="paymentHistoryLabel_<?php echo $_SESSION['transactNo'];?>" aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h6 class='h5 modal-title' id="paymentHistoryLabel_<?php echo $_SESSION['transactNo'];?>">Payment History for Transaction <?php echo $_SESSION['transactNo'];?></h6>
          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body'>
          <label class='form-label fw-bold'>Payment History</label>

          <table class="table table-striped table-bordered table-hover excel-table">
            <thead class="table-light">
              <tr>
                <th class="text-center col">Date</th>
                <th class="text-center col">Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $transactNo = $_SESSION['transactNo'];

                // Query to fetch payment records for the specific transaction number
                $sql1 = "SELECT 
                        DATE_FORMAT(paymentDate, '%m-%d-%Y') AS formattedDate, 
                        DATE_FORMAT(paymentDate, '%h:%i %p') AS formattedTime, 
                        amount 
                    FROM payment 
                    WHERE transactNo = '$transactNo'";
                $res1 = $conn->query($sql1); // Execute the query

                if ($res1->num_rows > 0) 
                {
                  // Loop through the results
                  while ($row = $res1->fetch_assoc()) 
                  {
                    // Fetch the date and amount from the row
                    $paymentDate = htmlspecialchars($row['formattedDate'])." ".htmlspecialchars($row['formattedTime']);
                    $paymentAmount = number_format($row['amount'], 2);
                    ?>
                      <tr>
                        <td><?php echo $paymentDate; ?></td>
                        <td>₱ <?php echo $paymentAmount; ?></td>
                      </tr>
                    <?php
                  }
                } 
                else 
                {
                  // Handle the case where there are no payments
                  echo "<tr><td colspan='2' class='text-center'>No payment records found.</td></tr>";
                }
              ?>
            </tbody>
          </table>
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
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