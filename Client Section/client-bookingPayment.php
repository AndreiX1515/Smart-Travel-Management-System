
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

      <div class="subscription">
        <div class="section section-1 px-3">
          <div class="header-container d-flex flex-row justify-content-between mb-2">
            <h4>Choose Payment Method</h4>
          </div>

          <div class="billing-options mt-4" >
            <!-- Bank Transfer Payment Option -->
            <div class="billing-card" data-value="bank-transfer">
              <div class="radiobutton-container">
                <input type="radio" name="billing">
              </div>
              <div class="payment-logo" style="margin-top: 10px;">
                <i class="fas fa-money-bill-transfer" style="font-size: 52px;"></i>
                <span>Bank Transfer</span>
              </div>
            </div>
          </div>
        </div>

        <div class="section section-1 px-3">
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

        <hr>

        <div class="row">
          <div class="col-sm">
            <div class="d-flex justify-content-between mb-1">
              <p class="mb-0"><strong>Total:</strong></p>
              <p class="mb-0">₱ <?php echo $formattedPrice; ?></p> <!-- Added commas for better readability -->
            </div>
          </div>
        </div>
        
        <form action="../Client Section/Functions/bookingPayment-code.php" method="POST" enctype="multipart/form-data">
          <hr>
          <input type="hidden" value="<?php echo $accId; ?>" name="agentAccountId"> <!-- Account ID Input (to be hidden) -->

          <input type="hidden" value="<?php echo $transactionNumber; ?>" name="transactNo">

          <input type="number" class="form-control" name="downpayment" step="0.01" min="<?php echo $downpayment; ?>" max="<?php echo $totalPrice; ?>" placeholder="Enter Downpayment Amount" required>
          <h6 class="mt-4">Attach Proof/Screenshot of transaction:</h6>
          <input type="file" id="attachment" class="attachment" name="proofs[]" accept="image/*" required>
          <hr>

          <div class="row mt-4">
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
        </form>
      </div>
    
    </div>

 </div>
</div>

<?php include '../Client Section/Includes/scripts.php'; ?>

  </body>
</html>