<?php
session_start();
require_once "../conn.php"; // Move up to the parent directory

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>


<!DOCTYPE html>
<html lang="en">

<head>

  <title>Employee - Dashboard</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>

  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar copy.css?v=<?php echo time(); ?>">

  <link rel="stylesheet" href="../Employee Section/assets/css/emp-transactionInfo-test.css?v=<?php echo time(); ?>">

  <link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">
  <script src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

</head>

<body>

  <?php include_once '../Employee Section/includes/emp-sessionVariables.php' ?>

  <!-- Navbar (Always on top) -->
  <div class="navbar">

    <div class="logo-container">
      <div class="logo-content">
        <div class="logo-backdrop">
          <img src="../Assets/Logos/logo-tab.png" alt="Logo" class="sidebar-logo">
        </div>
        <span class="fw-bold">SMART TRAVEL</span>
      </div>
    </div>

    <div class="main-nav-container">

      <div class="main-nav-items">

        <!-- Notification Icon with Red Dot and Dropdown -->
        <div class="main-nav-icon-container">
          <button id="alert-btn" class="main-nav-icon-btn" aria-expanded="false" aria-haspopup="true">
            <i class="fa-solid fa-bell main-nav-icon"></i>
          </button>
          <span class="main-nav-alert-dot"></span>

          <!-- Dropdown menu -->
          <div id="alert-dropdown" class="main-nav-dropdown-menu hidden" role="menu" aria-orientation="vertical"
            aria-labelledby="alert-btn" tabindex="-1">
            <div class="py-1" role="none">
              <a href="#" class="main-nav-dropdown-item" role="menuitem" tabindex="-1">
                <i class="fa-solid fa-triangle-exclamation"></i> You have 2 new alerts.
              </a>
              <a href="#" class="main-nav-dropdown-item" role="menuitem" tabindex="-1">
                <i class="fa-solid fa-download"></i> System update is ready.
              </a>
              <a href="#" class="main-nav-dropdown-item" role="menuitem" tabindex="-1">
                <i class="fa-solid fa-bell"></i> View all alerts
              </a>
            </div>
          </div>
        </div>

        <!-- Message Icon and Dropdown -->
        <div class="main-nav-icon-container">
          <button id="message-btn" class="main-nav-icon-btn" aria-expanded="false" aria-haspopup="true">
            <i class="fa-solid fa-comment-dots main-nav-icon"></i>
          </button>

          <!-- Dropdown menu -->
          <div id="message-dropdown" class="main-nav-dropdown-menu hidden" role="menu" aria-orientation="vertical"
            aria-labelledby="message-btn" tabindex="-1">
            <div class="py-1" role="none">
              <a href="#" class="main-nav-dropdown-item" role="menuitem" tabindex="-1">
                <i class="fa-solid fa-envelope"></i> Jane Doe sent you a message.
              </a>
              <a href="#" class="main-nav-dropdown-item" role="menuitem" tabindex="-1">
                <i class="fa-solid fa-user-circle"></i> John Smith is now online.
              </a>
              <a href="#" class="main-nav-dropdown-item" role="menuitem" tabindex="-1">
                <i class="fa-solid fa-inbox"></i> View all messages
              </a>
            </div>
          </div>
        </div>

        <!-- Vertical Separator -->
        <div class="main-nav-separator"></div>

        <!-- Profile Icon and Dropdown -->
        <div class="main-nav-icon-container">

          <button type="button" id="profile-btn" class="main-nav-profile-btn" aria-expanded="false"
            aria-haspopup="true">
            <div class="main-nav-profile-circle">
              <i class="fas fa-user"></i>
            </div>
          </button>

          <!-- Dropdown menu -->
          <div id="profile-dropdown" class="main-nav-dropdown-menu hidden" role="menu" aria-orientation="vertical"
            aria-labelledby="profile-btn" tabindex="-1">

            <div class="py-1" role="none">

              <!-- Profile header -->
              <div class="profile-dropdown-header">
                <div class="profile-avatar">
                  <i class="fas fa-user-circle"></i>
                </div>
                <div class="profile-info">
                  <span class="profile-name"><?= htmlspecialchars($fullName) ?></span>
                  <span class="profile-email"><?= htmlspecialchars($position) ?></span>
                </div>
              </div>

              <!-- Menu items -->
              <div class="profile-dropdown-links">

                <!-- Link to open modal -->
                <a href="#" class="main-nav-dropdown-item" id="changePasswordTrigger">
                  <i class="fas fa-user"></i> Change Password
                </a>


                <a href="#" 
                  id="raiseTicket" 
                  class="main-nav-dropdown-item" 
                  role="menuitem" 
                  tabindex="-1" 
                  data-bs-toggle="modal" 
                  data-bs-target="#raiseTicketModal">
                  <i class="fas fa-cog"></i> Raise a Ticket
                </a>

                <a href="#" class="main-nav-dropdown-item" role="menuitem" tabindex="-1" id="menu-item-2">
                  <i class="fas fa-sign-out-alt"></i> Sign out
                </a>
                
              </div>

            </div>
          </div>



        </div>

      </div>

    </div>

    <!-- Alert and Message Script -->
    <script>
      const alertBtn = document.getElementById('alert-btn');
      const messageBtn = document.getElementById('message-btn');
      const profileBtn = document.getElementById('profile-btn');

      const alertDropdown = document.getElementById('alert-dropdown');
      const messageDropdown = document.getElementById('message-dropdown');
      const profileDropdown = document.getElementById('profile-dropdown');

      // Function to hide all dropdowns
      function hideAllDropdowns() {
        alertDropdown.classList.add('hidden');
        messageDropdown.classList.add('hidden');
        profileDropdown.classList.add('hidden');
      }

      // Toggle the dropdown visibility for each button
      alertBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        hideAllDropdowns();
        alertDropdown.classList.toggle('hidden');
      });

      messageBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        hideAllDropdowns();
        messageDropdown.classList.toggle('hidden');
      });

      profileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        hideAllDropdowns();
        profileDropdown.classList.toggle('hidden');
      });

      // Close the dropdowns if the user clicks outside of them
      window.addEventListener('click', () => {
        hideAllDropdowns();
      });

    </script>

  </div>

  <?php
  if (isset($_GET['id'])) {
    // Sanitize the input to prevent XSS attacks
    $transactionId = htmlspecialchars($_GET['id']);
  }
  ?>

  <!-- <script>
    document.getElementById('redirect-btn').addEventListener('click', function () {
      window.location.href = '../Employee Section/emp-transaction.php';
    });
  </script> -->

  <!-- Body Content Wrapper -->
  <div class="body-container">

    <!-- Sidebar -->
    <?php include '../Employee Section/includes/emp-sidebar copy.php'; ?>

    <div class="main-content">

      <!-- Page Header -->
      <div class="page-header">

        <!-- Left Section: Title and Breadcrumb -->
        <div class="header-left">
          <h2 class="page-title">Transaction Information</h1>
          <nav class="breadcrumb">

            <div class="breadcrumb-item-1">
              <a href="#" class="breadcrumb-link">Transaction: BU1-00001</a>
            </div>

            <div class="breadcrumb-item-1">
              <!-- <span class="">Transaction</span> -->
            </div>
          </nav>

        </div>

        <!-- Right Section: Export Button -->
        <div class="header-right">

        </div>

      </div>

      <!-- Page Body -->
      <div class="page-body">

        <div class="first-part-wrapper">

          <div class="transaction-info-wrapper">

            <div class="card-header">

              <div class="card-title-wrapper">
                <h6 class="card-title">
                  <span class="badge bg-secondary">Transaction Information</span>
                </h6>
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

                <div class="transaction-details-grid">
                  <div class="detail-item">
                    <span class="detail-label">Number of Pax:</span>
                    <span class="detail-value"><?php echo $pax; ?></span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-label">Contact Person:</span>
                    <span class="detail-value"><?php echo $fullName; ?></span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-label">Infant Pax:</span>
                    <span class="detail-value"><?php echo $infantPax; ?></span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-label">Contact No:</span>
                    <span class="detail-value"><?php echo $contactNo; ?></span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-label">Package:</span>
                    <span class="detail-value"><?php echo $packageName; ?></span>
                  </div>
                  <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo $email; ?></span>
                  </div>

                  <div class="detail-item">
                    <span class="detail-label">Flight Date:</span>
                    <span class="detail-value"><?php echo $flightDate; ?></span>
                  </div>

                  <div class="detail-item balance-item">
                    <span class="detail-label">Balance:</span>
                    <span class="detail-value balance-value">₱ <?= $formattedBalance ?></span>
                  </div>
                </div>

              </div>

              <div class="status-item status-item" style="grid-column: 1 / -1;">
                <span class="detail-label">Status:</span>
                <span class="status-badge"><?php echo $status; ?></span>
              </div>

            </div>

          </div>

          <div class="guest-info-table-wrapper">

          </div>

          <div class="transaction-history-wrapper">

            <div class="card-header">
              <div class="card-title-wrapper">
                <h6 class="card-title">
                  <span class="badge bg-secondary">Transaction History</span>
                </h6>
              </div>
            </div>

            <div class="card-body transaction-history-body">
              <div class="transaction-history-card">
                <div class="transaction-item">
                  <span class="transaction-text">Agent added guest info</span>
                  <span class="transaction-date">2025-09-02 10:15 AM</span>
                </div>
                <div class="transaction-item">
                  <span class="transaction-text">Agent edited booking details</span>
                  <span class="transaction-date">2025-09-02 11:00 AM</span>
                </div>
                <div class="transaction-item">
                  <span class="transaction-text">Agent removed guest info</span>
                  <span class="transaction-date">2025-09-02 01:30 PM</span>
                </div>
                
              </div>
            </div>



          </div>

        </div>

        <div class="nav-pills-wrapper">
            <ul class="nav nav-pills " id="pills-tab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                  type="button" role="tab" aria-controls="pills-home" aria-selected="true">Guest Information</button>
              </li>

              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact"
                  type="button" role="tab" aria-controls="pills-contact" aria-selected="true">Request History</button>
              </li>

              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                  type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Payment History</button>
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
          <?php include '../Employee Section/emp-transactionGuestInfo copy.php' ?>
          <?php include '../Employee Section/emp-transactionRequestHistory.php' ?>
          <?php include '../Employee Section/emp-transactionPaymentHistory.php' ?>
        </div>

      </div>

    </div>
  </div>

  </body>
</html>