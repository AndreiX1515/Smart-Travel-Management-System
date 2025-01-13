
<?php
    // include 'session_validate.php'; // This will check if the session is valid
    require '../conn.php';
    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Fetch session variables directlys
    $email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
    // $firstName = $_SESSION['first_name'] ?? '';
    // $lastName = $_SESSION['last_name'] ?? '';
    // $middleName = $_SESSION['middle_name'] ?? '';
    $accId = $_SESSION['accountId'] ?? '';

    // $fullName = htmlspecialchars($lastName . ', ' . $firstName . ($middleName ? ' ' . substr($middleName, 0, 1) . '.' : ''));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../Client Section/Includes/head.php'; ?>

  <title>Booking Form</title>

  <link rel="stylesheet" href="../Client Section/assets/css/payment.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-navbar.css?v=<?php echo time(); ?>"> 
</head>

<body>

<?php
  // Check if 'id' is passed in the URL
  if (isset($_GET['id'])) 
  {
    $transactionNumber = htmlspecialchars($_GET['id']);
  }
?>

<?php include '../Client Section/Includes/client-navbar.php'; ?>

<div class="body-container">
  <div class="main-container">  
    <div class="content-header">
        <div class="back-button-wrapper">
            <a href="client-bookingform.php" class="back-button-link"> <i class="fa-solid fa-arrow-left me-2"></i> Back to Booking Section</a>
        </div>
        <h1>Payment Details</h1>
        <p>To confirm your booking, a down payment is required to secure your reservation.</p>
    </div>

    <div class="container-body">
      <!-- <?php 
      // if(isset($_SESSION['status'])):
      ?>

        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Hey!</strong> <?= $_SESSION['status']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

      <?php 
        // unset($_SESSION['status']);
        // endif;
      ?> -->

      <div class="subscription">
      
        <div class="section section-1">
          <div class="header-container">
            <h4>Choose Payment Method</h4>
          </div>

          <div class="billing-options">
            <ul class="nav nav-pills" id="pills-tab" role="tablist">

              <li class="navitems nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true" onclick="activateTab('home')">
                  <div class="card card-tab">
                    <div class="card-body">
                      <i class="fas fa-money-bill-transfer"></i>
                      <span>Bank Transfer</span>
                    </div>
                  </div>
                </button>
              </li>

              <!-- Credit Card Card -->
              <li class="navitems nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false" onclick="activateTab('profile')">
                  <div class="card card-tab">
                    <div class="card-body">
                      <i class="fas fa-credit-card"></i>
                      <span>Credit Card</span>
                    </div>
                  </div>
                </button>
              </li>

              <!-- PayPal Card -->
              <li class="navitems nav-item" role="presentation">
                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false" onclick="activateTab('contact')">
                  <div class="card card-tab">
                    <div class="card-body">
                      <i class="fas fa-paypal"></i>
                      <span>PayPal</span>
                    </div>
                  </div>
                </button>
              </li>

            </ul>

          </div>

          <!-- Feedback Section to Display Active Tab Note: To know which card are toggled-->
          <!-- <div id="activeTabFeedback">
            Currently active tab: <span id="activeTab">Bank Transfer</span>
          </div> -->
          
        </div>

        <script>
          // JavaScript function to activate a specific tab and track the active state
          function activateTab(tab) {
            // Reset active class for all buttons
            document.querySelectorAll('.nav-link').forEach(function(button) {
              button.classList.remove('active');
              button.setAttribute('aria-selected', 'false');
            });

            // Set active class for the selected button
            let selectedTab = document.getElementById('pills-' + tab + '-tab');
            selectedTab.classList.add('active');
            selectedTab.setAttribute('aria-selected', 'true');
            
            // Update feedback section with the currently active tab
            document.getElementById('activeTab').textContent = capitalizeFirstLetter(tab);
          }

          // Capitalize the first letter of the tab name for better readability
          function capitalizeFirstLetter(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
          }
        </script>

        <div class="tab-content" id="pills-tabContent">

          <!-- Bank Transfer Content -->
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="section section-1">
              <h3>Bank Details</h3>
              <div class="bank-detail-row">
                <div class="bank-detail-col">
                  <label for="bank-name">Bank Name:</label>
                  <p id="bank-name">Banco De Oro (BDO)</p>
                </div>
                <div class="bank-detail-col">
                  <label for="account-name">Account Name:</label>
                  <p id="account-name">Hyung Sub Kim (Nickname: Jed Kim)</p>
                </div>
              </div>
              <div class="bank-detail-row">
                <div class="bank-detail-col">
                  <label for="account-number">Account Number (PH - Peso):</label>
                  <p id="account-number">00780020352</p>
                </div>
              </div>
              <div class="bank-detail-row">
                <div class="bank-detail-col">
                  <label for="account-number">Account Number (US - Dollar):</label>
                  <p id="account-number">10780018789</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Credit Card Content -->
          <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="section section-2">
              <h3>Credit Card Details</h3>
              <!-- Credit Card Content Goes Here -->
            </div>
          </div>

          <!-- PayPal Content -->
          <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
            <div class="section section-3">
              <h3>PayPal Details</h3>
              <!-- PayPal Content Goes Here -->
            </div>
          </div>

        </div>
      </div>
      

      <div class="order-summary">
        <?php
          $packageName = "N/A"; 
          $pax = 0;             
          $flightDate = "N/A";  
          $formattedDP = "0.00"; 
          $formattedPrice = "0.00"; 
        
          $sql1 = mysqli_query($conn, "SELECT b.pax, b.totalPrice,
                  IF(f.flightId != 0, DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y'), 'Custom Scheduled Flight') 
                  AS onboardFlightSched, p.packageName 
              FROM booking b 
              JOIN flight f ON b.flightId = f.flightId 
              JOIN package p ON b.packageId = p.packageId 
              WHERE b.transactNo = '$transactionNumber'");
          
          if ($sql1 && mysqli_num_rows($sql1) > 0) 
          {
            while ($res1 = mysqli_fetch_array($sql1)) 
            {
              $totalPrice = $res1['totalPrice'];
              $formattedPrice = number_format($totalPrice, 2); // Format to 2 decimal places
              $downpayment = $res1['pax'] * 1000;
              $formattedDP = number_format($downpayment, 2); // Format to 2 decimal places
    
              // Get additional fields
              $flightDate = $res1['onboardFlightSched'];
              $packageName = $res1['packageName'];
              $pax = $res1['pax'];
            }
          }
          else 
          {
            echo "<p class='text-danger'>No booking details found for TransactNo: $transactionNumber.</p>";
          }
        ?>

        <div class="row">
          <div class="col-sm">
            <div class="d-flex justify-content-between mb-1">
              <p class="mb-0"><strong>Package Name:</strong></p>
              <p class="mb-0"><?php echo $packageName; ?></p> <!-- Added commas for better readability -->
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm">
            <div class="d-flex justify-content-between mb-1">
              <p class="mb-0"><strong>Total Number of Guest:</strong></p>
              <p class="mb-0"><?php echo $pax; ?></p> <!-- Added commas for better readability -->
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm">
            <div class="d-flex justify-content-between mb-1">
              <p class="mb-0"><strong>Flight Date:</strong></p>
              <p class="mb-0"><?php echo $flightDate; ?></p> <!-- Added commas for better readability -->
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm">
            <div class="d-flex justify-content-between mb-1">
              <p class="mb-0"><strong>Downpayment:</strong></p>
              <p class="mb-0">Minimum ₱ <?php echo $formattedDP; ?></p> <!-- Added commas for better readability -->
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm">
            <div class="d-flex justify-content-between mb-1">
              <p class="mb-0">₱ 1,000 per Guest.</p> <!-- Added space for better readability -->
            </div>
          </div>
        </div>

        <div class="total row">
            <div class="col-sm">
              <div class="d-flex justify-content-between mb-1">
                <p class="mb-0"><strong>Total:</strong></p>
                <p class="mb-0">₱ <?php echo $formattedPrice; ?></p>
              </div>
            </div>
        </div>   

        <form action="../Client Section/Functions/bookingPayment-code.php" method="POST" enctype="multipart/form-data">

        <div class="os-second-section">
          <div class="downpayment row">
            <div class="col-sm">
              <input type="hidden" value="<?php echo $accId; ?>" name="agentAccountId"> 
              <input type="hidden" value="<?php echo $transactionNumber; ?>" name="transactNo">

              <h6 class="my-3">Attach Proof/Screenshot of transaction:</h6>

              <div class="file-attachment">
                <input type="file" id="attachment" class="attachment" name="proofs[]" accept="image/*" required>
              </div>

              <input type="number" class="form-control" name="downpayment" step="0.01" min="<?php echo $downpayment; ?>" max="<?php echo $totalPrice; ?>" placeholder="Enter Downpayment Amount" required>
              
            </div>
          </div>

          <div class="row mt-2">
            <div class="col-sm">
              <div class="d-flex align-items-left mb-3"> <!-- Align items center for checkbox -->
                <input type="checkbox" class="ms-1 me-3"> <!-- Added margin to the checkbox -->
                <div class="checkbox-text">
                  <span>
                    By clicking this, I agree to Smart Travel <a href="#" class="terms-link">Terms & Conditions</a> and 
                    <a href="#" class="privacy-link">Privacy Policy</a>
                  </span>
                </div>
              </div>
              <button type="submit" class="pay-button" name="pay">Pay Now</button>
            </div>
          </div>


        </div>

        </form>
      </div>
    </div>

 </div>
</div>

<?php include '../Client Section/Includes/scripts.php'; ?>

  </body>
</html>