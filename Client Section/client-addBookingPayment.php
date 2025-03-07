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

      <!-- Check if 'id' is passed in the URL -->
      <?php
      if (isset($_GET['transactNo'])) {
        $transactionNumber = htmlspecialchars($_GET['transactNo']);
      }
      ?>

      <div class="main-content">
        <div class="header-container">
          <h3>Booking Payment</h2>
          <p>Proceed your booking by providing a payment (Optional)</p>
        </div>

        <div class="container-body"> 
          <div class="content-wrapper">

            <div class="subscription">
              <div class="section section-1">
                <div class="section-container">
                  <h4>Choose Payment Method</h4>
                </div>

                <div class="billing-options">
                  <!-- Bank Transfer -->
                  <div class="billing-card" data-value="bank-transfer">
                    <div class="radiobutton-container">
                      <input type="radio" name="billing">
                    </div>
                    <div class="payment-logo" style="margin-top: 10px;">
                      <i class="fas fa-money-bill-transfer" style="font-size: 52px;"></i>
                      <span>Bank Transfer</span>
                    </div>
                  </div>

                  <!-- Credit/Debit Card -->
                  <div class="billing-card" data-value="card-payment">
                    <div class="radiobutton-container">
                      <input type="radio" name="billing">
                    </div>
                    <div class="payment-logo" style="margin-top: 10px;">
                      <i class="fas fa-credit-card" style="font-size: 52px;"></i>
                      <span>Credit/Debit Card</span>
                    </div>
                  </div>

                  <!-- PayPal -->
                  <div class="billing-card" data-value="paypal">
                    <div class="radiobutton-container">
                      <input type="radio" name="billing">
                    </div>
                    <div class="payment-logo" style="margin-top: 10px;">
                      <i class="fab fa-paypal" style="font-size: 52px;"></i>
                      <span>PayPal</span>
                    </div>
                  </div>

                  <!-- GCash -->
                  <div class="billing-card" data-value="gcash">
                    <div class="radiobutton-container">
                      <input type="radio" name="billing">
                    </div>
                    <div class="payment-logo" style="margin-top: 10px;">
                      <i class="fas fa-mobile-alt" style="font-size: 52px;"></i>
                      <span>GCash</span>
                    </div>
                  </div>

                  <!-- Other E-Wallets -->
                  <div class="billing-card" data-value="ewallet">
                    <div class="radiobutton-container">
                      <input type="radio" name="billing">
                    </div>
                    <div class="payment-logo" style="margin-top: 10px;">
                      <i class="fas fa-wallet" style="font-size: 52px;"></i>
                      <span>Other E-Wallets</span>
                    </div>
                  </div>
                </div>

              </div>

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

            <div class="order-summary">
              <div class="order-summary-wrapper">
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

                if ($sql1 && mysqli_num_rows($sql1) > 0) {
                  while ($res1 = mysqli_fetch_array($sql1)) {
                    $totalPrice = $res1['totalPrice'];
                    $formattedPrice = number_format($totalPrice, 2); // Format to 2 decimal places
                    $downpayment = $res1['pax'] * 3000;
                    $formattedDP = number_format($downpayment, 2); // Format to 2 decimal places

                    // Get additional fields
                    $flightDate = $res1['onboardFlightSched'];
                    $packageName = $res1['packageName'];
                    $pax = $res1['pax'];
                  }
                } else {
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

                <form id="paymentForm" enctype="multipart/form-data">
                  <hr>
                  <input type="hidden" value="<?php echo $_SESSION['client_accountId']; ?>" name="agentAccountId">
                  <input type="hidden" value="<?php echo $transactionNumber; ?>" name="transactNo">

                  <input type="number" class="form-control" name="downpayment" step="0.01" min="<?php echo $downpayment; ?>" max="<?php echo $totalPrice; ?>" placeholder="Enter Downpayment Amount" required>

                  <h6 class="mt-4">Attach Proof/Screenshot of transaction:</h6>
                  <input type="file" id="attachment" class="attachment" name="proofs[]" accept="image/*" required>
                  <hr>

                  <div class="row mt-4">
                    <div class="col-sm">
                      <div class="d-flex align-items-left mb-3">
                        <input type="checkbox" id="termsCheckbox" class="ms-1 me-3">
                        <div class="checkbox-text">
                          <span>
                            By clicking this, I agree to Smart Travel
                            <a href="#" class="terms-link">Terms & Conditions</a> and
                            <a href="#" class="privacy-link">Privacy Policy</a>
                          </span>
                        </div>
                      </div>

                      <div id="messageBox" class="" style="display: none;"></div>

                      <button type="submit" class="pay-button" name="pay">Pay Now</button>
                      <!-- Message box -->
                      
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>


  <?php require "../Agent Section/includes/scripts.php"; ?>

  <script>
$(document).ready(function () {
    $("#paymentForm").submit(function (e) {
        e.preventDefault(); // Prevent normal form submission

        // Check if the checkbox is checked
        if (!$("#termsCheckbox").is(":checked")) {
            showMessage("You must agree to the Terms & Conditions.", "error");
            return;
        }

        var formData = new FormData(this);
        formData.append("pay", "1"); // Append identifier

        $.ajax({
            url: "../Client Section/Functions/client-addBookingPayment-code.php",
            type: "POST",
            data: formData,
            dataType: "json", // Automatically parse JSON
            processData: false,
            contentType: false,
            beforeSend: function () {
                showMessage("Processing payment...", "info");
                $("#paymentForm :input").prop("disabled", true);
            },
            success: function (jsonResponse) {
                $("#paymentForm :input").prop("disabled", false);
                
                if (jsonResponse.success) {
                    showMessage(jsonResponse.message, "success");

                    setTimeout(() => {
                        let redirectUrl =
                            "../Client Section/client-transactionInfo.php?id=" +
                            encodeURIComponent(jsonResponse.transactNo);
                        window.location.href = redirectUrl;
                    }, 2000);
                } else {
                    showMessage(jsonResponse.message || "Payment failed.", "error");
                }
            },
            error: function (xhr, status, error) {
                $("#paymentForm :input").prop("disabled", false);
                console.error("AJAX Error:", xhr.responseText);
                showMessage("Payment submission failed. Please try again.", "error");
            },
        });
    });

    // Function to show messages in the message box
    function showMessage(message, type) {
        var messageBox = $("#messageBox");
        messageBox
            .text(message)
            .removeClass()
            .addClass("mt-3 alert alert-" + getMessageClass(type))
            .show(); // Ensure it's visible

        setTimeout(() => messageBox.fadeOut(), 3000); 
    }

    // Get Bootstrap alert class based on message type
    function getMessageClass(type) {
        switch (type) {
            case "success":
                return "success"; // Green
            case "error":
                return "danger"; // Red
            case "info":
                return "primary"; // Blue
            default:
                return "secondary"; // Gray
        }
    }
});

  </script>


<!-- For Toggle Effect on Cards -->
<script>
/* JavaScript for toggling active class */
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".billing-card").forEach(card => {
        card.addEventListener("click", function () {
            document.querySelectorAll(".billing-card").forEach(c => c.classList.remove("selected"));
            this.classList.add("selected");
        });
    });
});

</script>


</body>

</html>