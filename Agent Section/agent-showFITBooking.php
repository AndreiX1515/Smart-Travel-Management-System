<?php 
session_start(); 
require "../conn.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FIT Transactions</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-showguest.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>
  <?php include '../Agent Section/includes/sidebar.php'; ?>

  <!-- Current Date Variable --> 
  <?php
    date_default_timezone_set('Asia/Taipei');
    $current_date = date('D, F d, Y');
  ?>

  <!-- Transact Number Session Variable -->
  <?php 
    if (isset($_SESSION['transaction_number'])) {
      $transactionNumber = $_SESSION['transaction_number'];
    } 

    if (isset($_GET['id'])) {
      $transactionNumber = htmlspecialchars($_GET['id']);
    } 
  ?>

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
            <h5 class="header-title">Transaction</h5>
          </div>
        </div>

      </div>
    </div>

    <?php
      $query1 = "SELECT f.transactionNo as transactNo, f.nights as noOfNights, f.rooms as noOfRooms, f.startDate as startDate, 
                    f.pax as pax, fh.hotelName as hotelName, fr.rooms as roomName, f.fName as fName, f.mName as mName, f.lName as lName,
                    f.suffix as suffix, f.countryCode as countryCode, f.contactNo as contactNo, f.phpPrice as totalPrice, f.email as email,
                    f.status as status
                  FROM fit f
                  JOIN fithotel fh ON f.hotelId = fh.hotelId
                  JOIN fitrooms fr ON f.roomId = fr.roomId
                  WHERE f.transactionNo = '$transactionNumber'";

      $result1 = $conn->query($query1);

      if ($result1->num_rows > 0) 
      {
        // Output data of each row
        while ($row1 = $result1->fetch_assoc()) 
        {
          $transactNum = $row1['transactNo'];
          $fName = $row1['fName'];
          $mName = $row1['mName'];
          $lName = $row1['lName'];
          $suffix = $row1['suffix'];
          $countryCode = $row1['countryCode'];
          $contact = $row1['contactNo'];
          $email = $row1['email'];
          $pax = $row1['pax'];
          $status = $row1['status'];
          $price = $row1['totalPrice'];
          $hotelName = $row1['hotelName'];
          $roomType = $row1['roomName'];

          // Construct the full name using the conditions for middle name and suffix
          $fullName = $lName . ", " . $fName . " " . 
                      ($suffix !== 'N/A' ? $suffix . " " : "") .  // Add space after suffix only if it's not 'N/A'
                      ($mName !== 'N/A' ? substr($mName, 0, 1) . ". " : "");  // Add middle initial with dot only if it's not 'N/A'
          $contactNo = $countryCode . $contact;

          $status = isset($row1['status']) ? $row1['status'] : 'Unknown';

          // Initialize an empty class string
          $statusClass = '';

          // Assign classes based on the status value using switch
          switch ($status) 
          {
            case 'Confirmed':
              $statusClass = 'bg-success text-white'; // Green background, white text
              break;
            case 'Cancelled':
              $statusClass = 'bg-danger text-white'; // Red background, white text
              break;
            case 'Pending':
              $statusClass = 'bg-warning text-dark'; // Yellow background, dark text
              break;
            default:
              $statusClass = 'bg-secondary text-white'; // Gray background, white text
              break;
          }
        }
      } 
      else 
      {
        echo "0 results";
      }
    ?>

    <div class="main-content">
      <div class="show-guest-wrapper">
        <div class="header">
          <div class="transaction-info">
            <div class="transaction-header">
              <h5 class="">Transaction Information: </h5>
            </div>
        
            <div class="transaction-info-body">
              <div class="row">
                <div class="col-md-5 columns">
                  <div class="info-item">
                    <p><strong>Transaction No:</strong> <?php echo htmlspecialchars($transactNum); ?></p>
                  </div>

                  <div class="info-item">
                    <p><strong>Total Pax:</strong> <?php echo htmlspecialchars($pax); ?></p>
                  </div>

                  <div class="info-item">
                    <p><strong>Hotel:</strong> <?php echo htmlspecialchars($hotelName); ?></p>
                  </div>

                  <div class="info-item">
                    <p><strong>Room Type:</strong> <?php echo htmlspecialchars($roomType); ?></p>
                  </div>

                  <div class="info-item">
                    <p><strong>Status:</strong> <span class="badge rounded-pill <?php echo $statusClass; ?>"> 
                    <?php echo htmlspecialchars($status); ?> </span> </p>
                  </div>
                </div>
            


                <div class="col-md-7 columns">
                  <div class="info-item">
                    <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($fullName); ?></p>
                  </div>

                  <div class="info-item">
                    <p><strong>Contact No:</strong> <?php echo htmlspecialchars($contactNo); ?></p>
                  </div>

                  <div class="info-item-email">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                  </div>

                  <div class="info-item">
                    <p><strong>Price: ₱ <?php echo number_format((float)$price, 2); ?></strong></p>
                  </div>
                </div>
              </div> 
            </div>

            <div class="transaction-info-footer">
              <!-- Cancel Transaction Button -->
              <button class="cancel-btn" data-bs-toggle="modal" data-bs-target="#cancelTransactionModal">
                  Cancel Transaction
              </button>
              <button class="payment-btn" data-toggle="modal" data-target="#paymentModal<?= $transactNum ?>" 
                data-transact-no="<?= $transactNum ?>" data-account-id="<?= $accountId ?>">
                Add Payment
              </button>
            </div>
          </div>
        </div>

        <div class="pills-tab-container">
          <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Payment History</button>
            </li>
          </ul>
        </div>

        <div class="transaction-body">
          <div class="body-tab-container">
            <div class="tab-content" id="pills-tabContent">
              <?php include 'agent-tableFITPayment.php'; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <!-- Modal for Cancel Transaction -->
  <div class="modal fade" id="cancelTransactionModal" tabindex="-1" aria-labelledby="cancelTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelTransactionModalLabel">Confirm Cancellation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../Agent Section/functions/agent-cancelFITTransact-code.php" method="POST">
          <div class="modal-body">
            <p class="mb-3">
              Are you sure you want to cancel this transaction? This action cannot be undone.
            </p>

            <!-- Hidden Input for Transaction Number -->
            <input type="hidden" name="updateTransactNo" value="<?php echo htmlspecialchars($transactNum); ?>">

            <!-- Reason for Cancellation -->
            <div class="mb-3">
              <label for="cancellationReason" class="form-label">
                Reason for Cancellation <span class="text-danger fw-bold">*</span>
              </label>
              <input id="cancellationReason" name="reason" class="form-control" placeholder="Enter the reason for cancellation" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="cancelTransact" class="btn btn-danger">Confirm Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>



  <?php require "../Agent Section/includes/scripts.php"; ?>

  <!-- Back Button -->
  <script>
    document.getElementById("redirect-btn").addEventListener("click", function () {
      window.location.href = "../Agent Section/agent-FIT-table.php";
    });
  </script>

 </body>
</html>
