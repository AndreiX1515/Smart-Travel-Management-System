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
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-payment copy.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>

  <?php include "../Agent Section/includes/sidebar.php"; ?>

  <div class="main-container">

    <div class="navbar">

      <div class="page-header-wrapper">

        <div class="page-header-top">
          <div class="back-btn-wrapper">
            <button class="back-btn" id="logout-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
          </div>
        </div>

        <div class="page-header-content">
          <div class="page-header-text">
            <h5 class="header-title">Payment Details</h5>
          </div>
        </div>

      </div>

    </div>

    <?php
    // Check if 'id' is passed in the URL
    if (isset($_GET['id'])) {
      $transactionNumber = htmlspecialchars($_GET['id']);
      $_SESSION['transaction_number'] = $transactionNumber; // Store in session for later use
    }
    ?>

    <div class="main-content">

      <div class="container-body">

        <div class="info-wrapper">

          <div class="payment-method">

            <div class="section section-1">

              <div class="header-container">
                <h4>Payment Method</h4>
                <p>Please select your preferred payment method to complete the booking process.</p>
              </div>


              <div class="section-body">

                <div class="section-content">
                  <div class="row-content">

                    <div class="card-wrapper">
                      <div class="billing-card" data-payment="bank-transfer">
                        <div class="payment-content">
                          <div class="payment-logo">
                            <i class="fas fa-university fa-2x"></i>
                          </div>
                          <div class="payment-name">
                            <span>Bank Transfer</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="card-wrapper">
                      <div class="billing-card disabled not-clickable" data-payment="other-digitalwallet">
                        <div class="payment-content">
                          <div class="payment-logo">
                            <i class="fas fa-mobile-alt fa-2x"></i>
                          </div>
                          <div class="payment-name">
                            <span>Other Digital Wallet</span>
                          </div>
                        </div>
                        <div class="coming-soon-badge">Coming Soon</div>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="section-content">
                  <div class="row-content">


                    <div class="card-wrapper">
                      <div class="billing-card" data-payment="gcash">
                        <div class="payment-content">
                          <div class="payment-logo">
                            <i class="fas fa-mobile-alt fa-2x"></i>
                          </div>
                          <div class="payment-name">
                            <span>GCash</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="card-wrapper">
                      <div class="billing-card" data-payment="paymaya">
                        <div class="payment-content">
                          <div class="payment-logo">
                            <i class="fas fa-mobile-alt fa-2x"></i>
                          </div>
                          <div class="payment-name">
                            <span>Paymaya</span>
                          </div>
                        </div>
                      </div>
                    </div>


                  </div>
                </div>
              </div>

            </div>

          </div>

          <!-- Payment Details Section -->
          <div class="subscription">

            <div class="tab-content" id="payment-details-content">

              <!-- Bank Transfer Tab Pane -->
              <div class="tab-pane active show" id="bank-transfer-details">

                <div class="header-container">
                  <h4 id="payment-details-title">Bank Details</h4>
                  <p id="payment-details-description">Please ensure that the payment details are correct before
                    proceeding with the transaction.</p>
                </div>

                <div class="bank-detail-row">
                  <div class="bank-detail-col">
                    <label>Bank Name:</label>
                    <p>Banco De Oro (BDO)</p>
                  </div>
                  <div class="bank-detail-col">
                    <label>Account Name:</label>
                    <p>Hyung Sub Kim (Nickname: Jed Kim)</p>
                  </div>
                </div>

                <div class="bank-detail-row">
                  <div class="bank-detail-col">
                    <label>Account Number (PH - Peso):</label>
                    <p>00780020352</p>
                  </div>
                  <div class="bank-detail-col">
                    <label>Account Number (US - Dollar):</label>
                    <p>10780018789</p>
                  </div>
                </div>

              </div>


              <!-- Credit Card Tab Pane -->
              <div class="tab-pane" id="credit-card-details">

                <div class="bank-detail-row">
                  <div class="bank-detail-col">
                    <label>Card Number:</label>
                    <p>**** **** **** 1234</p>
                  </div>
                  <div class="bank-detail-col">
                    <label>Cardholder Name:</label>
                    <p>John Doe</p>
                  </div>
                </div>

                <div class="bank-detail-row">
                  <div class="bank-detail-col">
                    <label>Expiry Date:</label>
                    <p>12/25</p>
                  </div>
                  <div class="bank-detail-col">
                    <label>CVV:</label>
                    <p>***</p>
                  </div>
                </div>

              </div>


              <!-- PayPal Tab Pane -->
              <div class="tab-pane" id="paypal-details">
                <div class="bank-detail-row">
                  <div class="bank-detail-col">
                    <label>PayPal Email:</label>
                    <p>payment@example.com</p>
                  </div>
                  <div class="bank-detail-col">
                    <label>Redirect URL:</label>
                    <p>https://paypal.com/checkout</p>
                  </div>
                </div>
              </div>


            </div>
          </div>

        </div>

        <div class="order-summary">

          <div class="summary-data">

            <div class="order-summary-wrapper">

            <div class="summary-header">
              <h4>Order Summary</h4>
            </div>

            <?php
            $packageName = "N/A";
            $pax = 0;
            $infantPax = 0;
            $flightDate = "N/A";
            $formattedDP = "0.00";
            $formattedPrice = "0.00";

            $sql1 = mysqli_query($conn, "SELECT b.pax, b.totalPrice, b.infantPax, p.packageName ,
                                  IF(f.flightId != 0, DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y'), 'Custom Scheduled Flight') AS onboardFlightSched
                                FROM booking b 
                                JOIN flight f ON b.flightId = f.flightId 
                                JOIN package p ON b.packageId = p.packageId 
                                WHERE b.transactNo = '$transactionNumber'");

            if ($sql1 && mysqli_num_rows($sql1) > 0) {
              while ($res1 = mysqli_fetch_array($sql1)) {
                $totalPrice = $res1['totalPrice'];
                $formattedPrice = number_format($totalPrice, 2);
                $downpayment = $res1['pax'] * 3000;
                $formattedDP = number_format($downpayment, 2);

                $flightDate = $res1['onboardFlightSched'];
                $packageName = $res1['packageName'];
                $pax = $res1['pax'];
                $infantPax = $res1['infantPax'];
              }
            } else {
              echo "<p class='text-danger'>No booking details found for TransactNo: $transactionNumber.</p>";
            }
            ?>

            <div class="summary-body">

              <!-- Booking Details -->
              <div class="summary-item">
                <div class="first">
                  <span><strong>Package Name:</strong></span>
                </div>

                <div class="second">
                  <span><?php echo $packageName; ?></span>
                </div>
              </div>

              <div class="summary-item">
                <div class="first">
                  <span><strong>Flight Date:</strong></span>
                </div>

                <div class="second">
                  <span><?php echo $flightDate; ?></span>
                </div>
              </div>

              <div class="summary-item">
                <div class="first">
                  <span><strong>Number of Guest:</strong></span>
                </div>

                <div class="second">
                  <span><?php echo $pax; ?></span>
                </div>
              </div>

              <div class="summary-item">
                <div class="first">
                  <span><strong>Number of Infant:</strong></span>
                </div>

                <div class="second">
                  <span><?php echo $infantPax; ?></span>
                </div>
              </div>

              <div class="summary-item">
                <div class="first">
                  <span><strong>Downpayment:</strong></span>
                </div>

                <div class="second minimum">
                  <span>₱ <?php echo $formattedDP; ?></span>
                  <span> <small>₱ 3,000 per Guest</small></span>
                </div>
              </div>

            </div>

            <form id="paymentForm" enctype="multipart/form-data">
              <input type="hidden" value="<?php echo $_SESSION['agent_accountId']; ?>" name="agentAccountId">

              <input type="hidden" value="<?php echo $transactionNumber; ?>" name="transactNo">
            </div>

            <div class="summary-item-total total">
              <div class="summary-label">
                <span>Total:</span>
              </div>

              <div class="summary-total-price">
                <span>₱ <?php echo $formattedPrice; ?></span>
              </div>
            </div>
            
          </div>

          <div class="secondary-wrapper">
              <div class="downpayment-wrapper">
                <h6>Downpayment:</h6>
                <input type="number" class="form-control mb-3" name="downpayment" step="0.01"
                  min="<?php echo $downpayment; ?>" max="<?php echo $totalPrice; ?>"
                  placeholder="Enter Downpayment Amount" required>
              </div>

              <div class="file-attach-wrapper">
                <h6>Attach Proof/Screenshot of Payment:</h6>

                <div class="file-input-container">
                  <input type="file" id="attachment" class="attachment" name="proofs[]" accept="image/*" required>
                  <label for="attachment" class="file-input-label">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span class="file-text">Choose file or drag here</span>
                    <span class="file-subtext">PNG, JPG up to 10MB</span>
                  </label>
                </div>
              </div>
          </div>



          <!-- Buttons Wrapper -->
          <div class="pay-btn-wrapper">

            <div class="terms-wrapper">
              <div class="check-box-wrapper">
                <input type="checkbox" id="termsCheckbox" class="form-check-input" required>
              </div>

              <div class="terms-info-wrapper">
                <label class="form-check-label" for="termsCheckbox">
                  By clicking this, I agree to Smart Travel
                  <a href="#" class="terms-link">Terms & Conditions</a> and
                  <a href="#" class="privacy-link">Privacy Policy</a>.
                </label>
              </div>
            </div>

            <div class="button-group">
              <button type="button" class="reserve-button btn btn-secondary flex-fill" data-bs-toggle="modal"
                data-bs-target="#payLaterModal">
                Reserve Booking
              </button>

              <button type="submit" class="pay-button btn btn-success flex-fill">
                Pay Now
              </button>
            </div>


          </div>

          </form>


        </div>

      </div>

    </div>


  </div>



  <!-- Pay Now Modal (Centered) -->
  <div class="modal fade" id="payNowModal" tabindex="-1" aria-labelledby="payNowLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">

      <!-- Mobile-friendly & Centered -->
      <div class="modal-content pay-now-modal">
        <!-- Success Icon -->
        <div class="modal-body text-center">
          <div class="pay-now-success-icon">
            <div class="circle"></div>
            <div class="checkmark"></div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="modal-body pay-now-body">
          <p>Booking Confirmation</p>
          <p class="pay-now-secondary">Ensure sufficient balance or a valid payment method before proceeding.</p>
        </div>

        <!-- Footer -->
        <div class="modal-footer pay-now-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success" id="confirmPayment">Confirm</button>
        </div>
      </div>

    </div>
  </div>

  <!-- Pay Later Modal (Centered) -->
  <div class="modal fade" id="payLaterModal" tabindex="-1" aria-labelledby="payLaterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">

      <!-- Mobile-friendly & Centered -->
      <div class="modal-content pay-later-modal">
        <form id="reservedBooking" enctype="multipart/form-data">
          <input type="hidden" value="<?php echo $_SESSION['agent_accountId']; ?>" name="agentAccountId">
          <input type="hidden" value="<?php echo $transactionNumber; ?>" name="transactNo">

          <input type="hidden" name="downpayment" value="0">
          <input type="hidden" name="paymentTitle" value="No Downpayment">

          <!-- Warning Icon -->
          <div class="modal-body text-center">
            <div class="pay-later-warning-icon">
              <div class="circle"></div>
              <div class="exclamation"></div>
            </div>
          </div>

          <!-- Main Content -->
          <div class="modal-body pay-later-body">
            <p>Your booking will be placed under <strong>"Reserved"</strong> status.</p>
            <p class="pay-later-secondary">Failure to complete the payment within the given timeframe may result in
              cancellation.</p>
          </div>

          <!-- Footer -->
          <div class="modal-footer pay-later-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Confirm</button>
          </div>
        </form>
      </div>

    </div>
  </div>

  <!-- Booking Accept Modal - Reserved -->
  <div class="modal fade" id="successModalLater" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body text-center">

          <div class="success-icon">
            <div class="circle"></div>
            <div class="checkmark"></div>
          </div>

          <div class="text-content">
            <h4>Successfully Booked!</h4>
            <p>Your booking transaction <strong><?php echo $transactionNumber; ?></strong> has been successfully
              <strong>Booked!</strong> Please wait for a confirmation email, which will be sent to you shortly.
            </p>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success w-100" id="okButtonLater">Got it</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Booking Accept Modal - Pending -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body text-center">

          <div class="success-icon">
            <div class="circle"></div>
            <div class="checkmark"></div>
          </div>

          <div class="text-content">
            <h4>Successfully Booked!</h4>
            <p>Your booking transaction <strong><?php echo $transactionNumber; ?></strong> has been successfully
              <strong>Booked!</strong> Please wait for a confirmation email, which will be sent to you shortly.
            </p>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-success w-100" id="okButton">Got it</button>
        </div>
      </div>
    </div>
  </div>


  <?php require "../Agent Section/includes/scripts.php"; ?>


  <!-- Payment - Container Change -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const paymentCards = document.querySelectorAll('.billing-card');

      // Function to show specific tab content
      function showTabContent(targetPayment) {
        // Hide all tab content panels
        const allTabPanes = document.querySelectorAll('.tab-pane');
        allTabPanes.forEach(pane => {
          pane.classList.remove('active', 'show');
        });

        // Show selected tab content
        const targetPane = document.querySelector(`#${targetPayment}-details`);
        if (targetPane) {
          targetPane.classList.add('active', 'show');
        }
      }

      // Function to set active payment card
      function setActiveCard(selectedCard) {
        // Remove active state from all cards
        paymentCards.forEach(card => {
          card.classList.remove('active');
        });

        // Add active state to selected card
        selectedCard.classList.add('active');
      }

      paymentCards.forEach(card => {
        card.addEventListener('click', function () {
          // Skip if card is disabled or not clickable
          if (this.classList.contains('disabled') || this.classList.contains('not-clickable')) {
            return;
          }

          // Get selected payment method
          const selectedPayment = this.getAttribute('data-payment');

          // Set active card
          setActiveCard(this);

          // Show corresponding tab content
          showTabContent(selectedPayment);
        });
      });



      // AUTO-ACTIVATE BANK TRANSFER ON PAGE LOAD
      const bankTransferCard = document.querySelector('.billing-card[data-payment="bank-transfer"]');
      if (bankTransferCard) {
        setActiveCard(bankTransferCard);
        showTabContent('bank-transfer');
      }
    });
  </script>

  <!-- File-Attach Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const fileInput = document.getElementById('attachment');
      const fileLabel = document.querySelector('.file-input-label');
      const fileText = document.querySelector('.file-text');
      const container = document.querySelector('.file-input-container');

      // Handle file selection
      fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
          fileText.textContent = this.files[0].name;
          container.classList.add('has-file');
        } else {
          fileText.textContent = 'Choose file or drag here';
          container.classList.remove('has-file');
        }
      });

      // Handle drag and drop
      ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileLabel.addEventListener(eventName, preventDefaults, false);
      });

      function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
      }

      ['dragenter', 'dragover'].forEach(eventName => {
        a
        fileLabel.addEventListener(eventName, highlight, false);
      });

      ['dragleave', 'drop'].forEach(eventName => {
        fileLabel.addEventListener(eventName, unhighlight, false);
      });

      function highlight() {
        fileLabel.classList.add('dragover');
      }

      function unhighlight() {
        fileLabel.classList.remove('dragover');
      }
    });
  </script>











  <!-- Script for Pay Later Modal -->
  <script>
    $(document).ready(function () {
      $("#reservedBooking").on("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        $('#message-payment').html(''); // Clear previous messages
        $(".error-text").remove(); // Remove previous error messages

        let downpayment = $("input[name='downpayment']").val().trim();
        let paymentTitle = $("input[name='paymentTitle']").val().trim();

        let formData = new FormData(this);
        formData.append('pay', '1'); // Add identifier for processing

        $.ajax({
          // 
          url: "../Agent Section/functions/agent-addBookingReserved-code.php",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $('#message-payment').html('<div class="alert alert-info">Processing payment...</div>');
          },
          success: function (response) {
            console.log("Server Response:", response);

            let res;

            try {
              res = typeof response === "string" ? JSON.parse(response) : response;

              if (res.status === "success") {
                let bookingStatus = res.bookingStatus; // Get bookingStatus from response
                let transactionNumber = res.transactionNumber; // Get transactionNumber

                // ✅ If "Pay Later", show Reserved modal
                if (bookingStatus === "Pay Later") {
                  $("#successModalLater").modal("show");

                  // ✅ Otherwise, show Booked modal
                } else {
                  $("#successModal").modal("show");
                }

              } else {
                $('#message-payment').html('<div class="alert alert-danger">' + res.message + '</div>');
                console.error("Payment Error:", res.message);
              }
            } catch (error) {
              $('#message-payment').html('<div class="alert alert-danger">Unexpected error. Please try again.</div>');
              console.error("JSON Parse Error:", error);
            }
          },
          error: function (xhr, status, error) {
            $('#message-payment').html('<div class="alert alert-danger">Error processing payment. Please try again.</div>');
            console.error("AJAX Error:", status, error);
          }
        });
      });

      // ✅ Ensure modal allows closing by clicking outside or pressing ESC
      $("#successModalLater").modal({
        backdrop: true,  // Allow closing by clicking outside
        keyboard: true   // Allow closing with ESC key
      });

      // ✅ Redirect when "Got it" is clicked
      $("#okButtonLater").on("click", function () {
        $("#successModalLater").modal("hide"); // Ensure modal hides first
        setTimeout(function () {
          window.location.href = "../Agent Section/agent-showGuest.php?id=<?= $transactionNumber ?>";
        }, 500); // Small delay for a smooth transition
      });

      // ✅ Redirect when modal is closed (by clicking outside or pressing ESC)
      $("#successModalLater").on("hidden.bs.modal", function () {
        window.location.href = "../Agent Section/agent-showGuest.php?id=<?= $transactionNumber ?>";
      });
    });
  </script>

  <!-- Script for Pay Now -->
  <script>
    $(document).ready(function () {
      $("#paymentForm").on("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        $('#message-payment').html(''); // Clear previous messages
        $(".error-text").remove(); // Remove previous error messages

        let hasErrors = false;

        // Get input values
        let downpayment = $("input[name='downpayment']").val().trim();
        let minDownpayment = parseFloat($("input[name='downpayment']").attr("min"));
        let attachment = $("#attachment").val();
        let termsChecked = $("#termsCheckbox").is(":checked");

        // Downpayment Validation
        if (downpayment === "" || isNaN(downpayment)) {
          $("input[name='downpayment']").after('<small class="error-text text-danger">Please enter a valid amount.</small>');
          hasErrors = true;
        } else if (parseFloat(downpayment) < minDownpayment) {
          $("input[name='downpayment']").after(`<small class="error-text text-danger">Minimum downpayment is ${minDownpayment}.</small>`);
          hasErrors = true;
        }

        // Attachment Validation
        if (attachment === "") {
          $("#attachment").after('<small class="error-text text-danger">Proof of transaction is required.</small>');
          hasErrors = true;
        }

        // Terms Checkbox Validation
        if (!termsChecked) {
          $('#message-payment').html('<div class="alert alert-danger">You must agree to the Terms & Conditions and Privacy Policy.</div>');
          hasErrors = true;
        }

        // Prevent AJAX submission & modal opening if errors exist
        if (hasErrors) {
          return;
        }

        let formData = new FormData(this);
        formData.append('pay', '1'); // Add identifier for processing

        $.ajax({
          // 
          url: "../Agent Section/functions/agent-addBookingPayment-code.php",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $('#message-payment').html('<div class="alert alert-info">Processing payment...</div>');
          },
          success: function (response) {
            console.log("Server Response:", response);

            let res;

            try {
              res = typeof response === "string" ? JSON.parse(response) : response;

              if (res.status === "success") {
                let bookingStatus = res.bookingStatus; // Get bookingStatus from response
                let transactionNumber = res.transactionNumber; // Get transactionNumber

                // ✅ If "Pay Later", show Reserved modal
                if (bookingStatus === "Pay Later") {
                  $("#successModalLater").modal("show");

                  // ✅ Otherwise, show Booked modal
                } else {
                  $("#successModal").modal("show");
                }

              } else {
                $('#message-payment').html('<div class="alert alert-danger">' + res.message + '</div>');
                console.error("Payment Error:", res.message);
              }
            } catch (error) {
              $('#message-payment').html('<div class="alert alert-danger">Unexpected error. Please try again.</div>');
              console.error("JSON Parse Error:", error);
            }
          },
          error: function (xhr, status, error) {
            $('#message-payment').html('<div class="alert alert-danger">Error processing payment. Please try again.</div>');
            console.error("AJAX Error:", status, error);
          }
        });
      });

      // ✅ Ensure modal allows closing by clicking outside or pressing ESC
      $("#successModal").modal({
        backdrop: true,  // Allow closing by clicking outside
        keyboard: true   // Allow closing with ESC key
      });

      // ✅ Redirect when "Got it" is clicked
      $("#okButton").on("click", function () {
        $("#successModal").modal("hide"); // Ensure modal hides first
        setTimeout(function () {
          window.location.href = "../Agent Section/agent-showGuest.php?id=<?= $transactionNumber ?>";
        }, 500); // Small delay for a smooth transition
      });

      // ✅ Redirect when modal is closed (by clicking outside or pressing ESC)
      $("#successModal").on("hidden.bs.modal", function () {
        window.location.href = "../Agent Section/agent-showGuest.php?id=<?= $transactionNumber ?>";
      });
    });



  </script>

</body>

</html>