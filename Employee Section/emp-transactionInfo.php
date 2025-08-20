<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-transactionInfo.css?v=<?php echo time(); ?>">
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

        <?php
        if (isset($_GET['id'])) {
          // Sanitize the input to prevent XSS attacks
          $transactionId = htmlspecialchars($_GET['id']);
        }
        ?>

        <div class="page-header-content">
          <div class="page-header-text">
            <h5 class="header-title">Transaction ID: <span class="fw-normal"><?php echo $transactionId; ?></span></h5>
          </div>
        </div>

      </div>
    </div>

    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Employee Section/emp-transaction.php'; // Replace with your actual URL
      });
    </script>

    <div class="main-content">
      <div class="content-container">

        <div class="first-part-wrapper">

          <div class="transaction-info-wrapper">
            <div class="card-header">
              <div class="card-title-wrapper">
                <h6 class="card-title">Transaction Information</h6>
              </div>
            </div>


            <?php
            $query1 = "SELECT b.*, p.packageName, f.flightDepartureDate, COALESCE(SUM(pa.amount), 0) AS TotalAmountPaid,
                        COALESCE(SUM(r.requestCost), 0) AS TotalRequestAmount
                      FROM booking b 
                      JOIN package p ON b.packageId = p.packageId
                      LEFT JOIN flight f ON b.flightId = f.flightId
                      LEFT JOIN payment pa ON pa.transactNo = b.transactNo AND pa.paymentStatus = 'Approved'
                      LEFT JOIN request r ON r.transactNo = b.transactNo AND r.requestStatus = 'Confirmed'
                      WHERE b.transactNo = '$transactionId'
                      GROUP BY b.transactNo";

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
                $infantPax = $row1['infantPax'];
                $status = $row1['status'];
                $price = $row1['totalPrice'] ?? 0;
                $requestCost = $row1['TotalRequestAmount'] ?? 0;
                $amountPaid = $row1['TotalAmountPaid'] ?? 0;
                $flightId = $row1['flightId']; // Fetch flightId
                $balance = ($price + $requestCost) - $amountPaid;
                $formattedBalance = number_format($balance, 2);

                // Construct the full name using the conditions for middle name and suffix
                $fullName = $lName . ", " . $fName . " " .
                  ($suffix !== 'N/A' ? $suffix . " " : "") .  // Add space after suffix only if it's not 'N/A'
                  ($mName !== 'N/A' ? substr($mName, 0, 1) . ". " : "");  // Add middle initial with dot only if it's not 'N/A'
                $contactNo = $countryCode . $contact;

                // Check if flightId is NULL and set flightDate accordingly
                if (is_null($flightId)) {
                  $flightDate = "Land Package Only";
                }

                $status = isset($row1['status']) ? $row1['status'] : 'Unknown';

                // Initialize an empty class string
                $statusClass = '';

                // Assign classes based on the status value using switch
                switch ($status) {
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
            } else {
              echo "0 results";
            }
            ?>

            <div class="card-body booking-transaction-body">
              <div class="transaction-details-container">
                <div class="row guest-info-row">

                  <!-- Left Column -->
                  <div class="col-md-6 transaction-details-left">
                    <p class="mb-2"><strong>Transaction No:</strong> <?php echo $transactNum; ?></p>
                    <p class="mb-2"><strong>Number of Pax:</strong> <?php echo $pax; ?></p>
                    <p class="mb-2"><strong>Infant Pax::</strong> <?php echo $infantPax; ?></p>
                    <p class="mb-2"><strong>Package:</strong> <?php echo $packageName; ?></p>
                    <p class="mb-2"><strong>Flight Date:</strong> <?php echo $flightDate; ?></p>
                  </div>

                  <!-- Right Column -->
                  <div class="col-md-6 transaction-details-right">
                    <p class="mb-2"><strong>Contact Person:</strong> <?php echo $fullName; ?></p>
                    <p class="mb-2"><strong>Contact No:</strong> <?php echo $contactNo; ?></p>
                    <p class="mb-2"><strong>Email:</strong> <?php echo $email; ?></p>
                    <p class="mb-0"><strong>Balance: ₱</strong> <?php echo $formattedBalance; ?></p>
                    <p class="mb-0 d-flex align-items-center">
                      <strong class="me-2">Status:</strong>
                      <span class="badge rounded-pill bg-warning text-dark p-2">
                        <?php echo $status; ?>
                      </span>
                    </p>
                  </div>

                </div>
              </div>
            </div>

            <div class="card-footer">
              <button class="btn btn-danger btn-sm cancel-btn" data-transact="<?php echo $transactNo; ?>" data-bs-toggle="modal" data-bs-target="#cancelModal">
                Cancel Transaction
              </button>
            </div>

          </div>

          <div class="guest-info-table-wrapper">

          </div>

        </div>

        <div class="nav-pills-wrapper">
          <ul class="nav nav-pills " id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Guest Information</button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="true">Request History</button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Payment History</button>
            </li>
            <!-- <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</button>
        </li> -->
            <!-- <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false" disabled>Disabled</button>
        </li> -->
          </ul>
        </div>

        <div class="tab-content" id="pills-tabContent">
          <?php include '../Employee Section/emp-transactionGuestInfo.php' ?>
          <?php include '../Employee Section/emp-transactionRequestHistory.php' ?>
          <?php include '../Employee Section/emp-transactionPaymentHistory.php' ?>
        </div>

      </div>

    </div>
  </div>

  <!-- Cancel Transaction Modal -->
  <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelModalLabel">Cancel Transaction</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../Employee Section/functions/emp-cancelTransact-code.php" method="POST">
          <div class="modal-body">
            <p>Are you sure you want to cancel this transaction?</p>
            <p><strong>Transaction No: <?php echo $transactNum; ?></strong></p>

            <input type="hidden" name="transactNo" value="<?php echo $transactNum; ?>" />
            <input type="hidden" name="accId" value="<?php echo $accountId; ?>" />

            <div class="form-group">
              <label for="remarks">Remarks</label>
              <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Enter Remarks" />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="confirmCancel" class="btn btn-danger" id="confirmCancel">Confirm Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Visa Status Modal -->
  <div class="modal fade" id="guestModal" tabindex="-1" role="dialog" aria-labelledby="guestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="guestModalLabel">Update Visa Status</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="../Employee Section/functions/emp-updateVisaStatus-code.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="guestId" id="guestIdField">
            <input type="hidden" name="transactNo" placeholder="transactNo" value="<?php echo $transactNum; ?>">

            <!-- <p class="mb-3">
            Are you sure you want to cancel this transaction? This action cannot be undone.
          </p> -->

            <div class="mb-4">
              <label for="visaStatus" class="form-label fw-bold">Visa Status:</label>
              <select id="visaStatus" name="visaStatus" class="form-select">
                <option selected disabled>Select Option</option>
                <option value="Approved">Approved</option>
                <option value="Denied">Denied</option>
              </select>
            </div>

            <!-- Reason for Cancellation -->
            <div class="mb-3">
              <label for="cancellationReason" class="form-label">
                Reason for Denied <span class="text-danger fw-bold"></span>
              </label>
              <input id="cancellationReason" name="reason" class="form-control" placeholder="Enter the remarks for Denied Visa">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" name="updateVisaStatus" data-dismiss="modal">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include '../Employee Section/includes/emp-scripts.php' ?>

  <script>
    // Select all table rows with the class 'table-row'
    document.querySelectorAll('.table-row').forEach(row => {
      row.addEventListener('click', function() {
        // Get the data from the clicked row
        const guestId = this.getAttribute('data-guest-id');

        // Set the guestId input field with the clicked row's guestId
        document.getElementById('guestIdField').value = guestId;

        // Open the modal
        $('#guestModal').modal('show');
      });
    });
  </script>


</body>

</html>