<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Payment</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-payment.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="body-container">
  <?php include "../Client Section/Includes/client-sidebar.php"; ?>

  <div class="main-content-container">
    <div class="navbar">
      <div class="backbutton-wrapper">
        <div class="back-button-wrapper">
          <a href="../Client Section/client-transactions.php" class="back-button-link">
              <i class="fa-solid fa-arrow-left"></i>
          </a>
        </div>

        <div class="page-name-wrapper">
            <h5>Transaction</h5>
        </div>

      </div>
    </div>

    <?php
      // Check if 'id' is passed in the URL
      if (isset($_GET['id'])) 
      {
        $transactionNumber = htmlspecialchars($_GET['id']);
      }
    ?>

    <div class="main-content">
      <div class="container-body">
    
        <div class="subscription">
          <h3 class="ms-3">Payment Details</h3>
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
          
          <form action="../Client Section/Functions/client-addBookingPayment-code.php" method="POST" enctype="multipart/form-data">
            <hr>
            <input type="hidden" value="<?php echo $_SESSION['accountId']; ?>" name="agentAccountId">
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
</div>


<?php require "../Agent Section/includes/scripts.php"; ?>

  </body>
</html>