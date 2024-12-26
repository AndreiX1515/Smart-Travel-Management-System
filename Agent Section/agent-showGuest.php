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
  <title>Transactions</title>

  <?php include '../Agent Section/includes/head.php'; ?>
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-showguest.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>
  <?php include '../Agent Section/includes/sidebar.php'; ?>

  <div class="main-content" id="mainContent">
    <!-- Session Variables -->
    <?php  
      $accountId = $_SESSION['agent_accountId'];
      $agentId = $_SESSION['agent_agentId'];
      $agentCode = $_SESSION['agent_agentCode'];
      $agentRole = $_SESSION['agent_agentRole'];
      $agentType = $_SESSION['agent_agentType'];
      $fName =  $_SESSION['agent_fName'] ?? '';
      $lName = $_SESSION['agent_lName'] ?? '';
      $mName = $_SESSION['agent_mName'] ?? '';
      $branchId = $_SESSION['agent_branchId'] ?? '';
      $email = $_SESSION['email'] ?? '';
      $password = $_SESSION['password'] ?? '';

      $sql1 = "Select * from branch where branchId= '$branchId'";
      $result1 = $conn->query($sql1);

      // Check if a result is returned
      if ($result1->num_rows > 0) {
          // Fetch the branchName
          $row = $result1->fetch_assoc();
          $branchName = $row['branchName'];
      } else {
          $branchName = "No Branch";
      }

      // Format the full name
      $fullName = htmlspecialchars($lName . ', ' . $fName . ($mName ? ' ' . substr($mName, 0, 1) . '.' : ''));

      // Optional: hide password by default
      $maskedPassword = '••••••••••';
    ?>

    <!-- Current Date Variable --> 
    <?php
      date_default_timezone_set('Asia/Taipei');
      $current_date = date('D, F d, Y');
    ?>

    <!-- Transact Number Session Variable -->
    <?php 
      if (isset($_SESSION['transaction_number'])) 
      {
        $transactionNumber = $_SESSION['transaction_number'];
      } 
     
      if (isset($_GET['id'])) 
      {
       $transactionNumber = htmlspecialchars($_GET['id']);
      } 
    ?>

    <header>      
      <nav class="navbar navbar-expand-lg justify-content-between sticky-top">
        <div class="container-fluid d-flex justify-content-between">
          <div class="nav-start-container d-flex flex-row">
            <button class="back-button" onclick="window.location.href='../Agent Section/agent-transactions.php';">
              <i class="fas fa-arrow-left"></i>
            </button>

            <h6>Transaction: <span><?php echo $transactionNumber; ?> </span></h6>
          </div>

          <div class="nav-end-container d-flex flex-row align">
            <div class="date-time-container d-flex flex-row align-items-center">
              <h6><?php echo $current_date; ?></h6>
            </div>

						<div class="vertical-line-navbar"></div>

            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown d-flex align-items-center">
                  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-container ms-2 me-3">
                      <h6 class="mb-1"><?php echo $fullName; ?></h6>
                      <span class="m-0">Branch: <?php echo $branchName; ?></span>
                      <span class="m-0">Agent ID: <?php echo $agentId; ?></span>
                    </div>
                    <img src="../assets/images/circle.png" alt="Profile" class="profile-image me-2" width="40px" height="40px">
                  </a>

                  <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="navbarDropdown">
                    <li>
                      <a class="dropdown-item" href="#" style="font-size: 14px;" data-bs-toggle="modal" data-bs-target="#viewPasswordModal">
                        <i class="fas fa-user me-2"></i> View Password
                      </a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <li>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" style="font-size: 14px;">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
															
          </div>
        </div>
      </nav>
    </header>

    <?php include '../Agent Section/includes/logoutViewPassModal.php'; ?>

    <?php if(isset($_SESSION['status'])): ?>
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Hey!</strong> <?= $_SESSION['status']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

      <?php 
        unset($_SESSION['status']);
        endif;
    ?>

    <?php
      $query1 = "SELECT booking.*, package.packageName, flight.flightDepartureDate 
                  FROM booking 
                  JOIN package ON booking.packageId = package.packageId
                  LEFT JOIN flight ON booking.flightId = flight.flightId
                  WHERE transactNo = '$transactionNumber'";

      $result1 = $conn->query($query1);

      if ($result1->num_rows > 0) {
        // Output data of each row
        while ($row1 = $result1->fetch_assoc()) {
          $transactNum = $row1['transactNo'];
          $fName = $row1['fName'];
          $mName = $row1['mName'];
          $lName = $row1['lName'];
          $suffix = $row1['suffix'];
          $countryCode = $row1['countryCode'];
          $contact = $row1['contactNo'];
          $email = $row1['email'];
          $packageName = $row1['packageName'];
          $flightDate = $row1['flightDepartureDate'];
          $pax = $row1['pax'];
          $status = $row1['status'];
          $price = $row1['totalPrice'];
          $flightId = $row1['flightId']; // Fetch flightId

          // Construct the full name using the conditions for middle name and suffix
          $fullName = $lName . ", " . $fName . " " . 
                      ($suffix !== 'N/A' ? $suffix . " " : "") .  // Add space after suffix only if it's not 'N/A'
                      ($mName !== 'N/A' ? substr($mName, 0, 1) . ". " : "");  // Add middle initial with dot only if it's not 'N/A'
          $contactNo = $countryCode . $contact;

          // Check if flightId is NULL and set flightDate accordingly
          if (is_null($flightId)) 
          {
            $flightDate = "Land Package Only";
          }

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
    ?>


	  <div class="content-wrapper">
			<div class="header">
        <div class="transaction-info">
          <div class="row g-3">
            <h5 class="fw-bold">Transaction Information: </h5>
            <div class="col-md-5 mb-2 d-flex flex-column gap-1">
              <p class=""><strong>Transaction No:</strong> <?php echo htmlspecialchars($transactNum); ?></p>
              <p class=""><strong>Total Pax:</strong> <?php echo htmlspecialchars($pax); ?></p>
              <p class=""><strong>Package:</strong> <?php echo htmlspecialchars($packageName); ?></p>
              <p class=""><strong>Flight Date:</strong> <?php echo htmlspecialchars($flightDate); ?></p>
              <p class=""><strong>Status:</strong> <span class="badge rounded-pill <?php echo $statusClass; ?>"> 
              <?php echo htmlspecialchars($status); ?> </span> </p>
            </div>

            <div class="col-md-5 mb-3 d-flex flex-column gap-1">
              <p class=""><strong>Contact Person:</strong> <?php echo htmlspecialchars($fullName); ?></p>
              <p class=""><strong>Contact No:</strong> <?php echo htmlspecialchars($contactNo); ?></p>
              <p class=""><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
              <p class=""><strong>Price: ₱ <?php echo number_format((float)$price, 2); ?></strong> </p>
            </div>

            <?php
              }

              } 
              else 
              {
                echo "0 results";
              }
            ?>
          </div> 

          <div class="transaction-info-footer d-flex justify-content-end">
            <button class="btn btn-danger btn-sm mt-2 me-2" data-bs-toggle="modal" data-bs-target="#cancelTransactionModal">
              Cancel Transaction
            </button>
            <button class="btn btn-primary btn-sm mt-2 me-2" data-bs-toggle="modal" data-bs-target="#paymentModal<?= $transactNum ?>"
              data-transact-no="<?= $transactNum ?>" data-account-id="<?= $accountId ?>">Add Payment</button>
            <button class="btn btn-primary btn-sm mt-2 me-2" data-bs-toggle="modal" data-bs-target="#requestModal" 
              data-transaction-id="<?= $transactionNumber ?>">Add Request</button>
          </div>
        </div>
				
        <div class="table-wrapper">

        </div>
				
			</div>

			<hr style="border: 1px solid #ccc; width: 100%; margin: 5px 0;">

      <ul class="nav nav-pills" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Guest Information</button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-visa-tab" data-bs-toggle="pill" data-bs-target="#pills-visa" type="button" role="tab" aria-controls="pills-visa" aria-selected="false">Visa Requirements</button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Request History</button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Payment History</button>
        </li>
			</ul>

			<div class="tab-content" id="pills-tabContent">
        <!-- Guest Table -->
        <?php include 'agent-guestTable.php'; ?>
        <?php include 'agent-showVisa.php'; ?>
        <?php include 'agent-requestTable.php'; ?>
        <?php include 'agent-paymentTable.php'; ?>
        
        <?php 
        // include 'agent-flightTable.php'; 
        ?>   
			</div>
    </div>
  </div>

<!-- Cancel Transaction Modal -->
<div class="modal fade" id="cancelTransactionModal" tabindex="-1" aria-labelledby="cancelTransactionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelTransactionModalLabel">Confirm Cancellation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../Agent Section/functions/agent-cancelTransact-code.php" method="POST">
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

 </body>
</html>
