
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Payment for FIT - Agent</title>
    
    <?php include "../Agent Section/includes/head.php"; ?>
    <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Agent Section/assets/css/agent-payment.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
  </head>
 
  <body>
    <?php include '../Agent Section/includes/sidebar.php' ?>

    <div class="main-container">

      <div class="navbar">
        <div class="page-header-wrapper">
          <div class="page-header-content">
            <div class="page-header-text">
              <h5 class="header-title">FIT Payment Details</h5>
            </div>
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
                      <!-- Bank Transfer -->
                      <div class="card-wrapper">
                        <div class="billing-card">
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
                        <div class="billing-card">
                          <div class="payment-content">
                            <div class="payment-logo">
                              <!-- <i class="fas fa-mobile-alt fa-2x"></i>  -->
                            </div>
                            <div class="payment-name">
                              <span></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="section-content">
                    <div class="row-content">
                      <!-- Bank Transfer -->
                      <div class="card-wrapper">
                        <div class="billing-card disabled">
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

                      <!-- Smart/Sun -->
                      <div class="card-wrapper">
                        <div class="billing-card disabled">
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

            <div class="subscription">

              <script>
                document.addEventListener("DOMContentLoaded", function() {
                  document.querySelectorAll('.billing-card').forEach(card => {
                    card.addEventListener('click', function() {
                      // Remove active state from all cards
                      document.querySelectorAll('.billing-card').forEach(c => {
                        c.classList.remove('active');
                        c.querySelector('.hidden-radio').checked = false;
                      });

                      // Add active state to the clicked card
                      this.classList.add('active');
                      this.querySelector('.hidden-radio').checked = true;
                    });
                  });
                });
              </script>

              <div class="section section-1">
                <div class="header-container">
                  <h4>Bank Details</h4>
                  <p>Please ensure that the payment details are correct before proceeding with the transaction.</p>
                </div>

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

          </div>

          <div class="order-summary">
            <?php
              $sql1 = mysqli_query($conn, "SELECT h.HotelName as hotelName, r.rooms as roomName, f.nights as nights, 
                                            CONCAT(DATE_FORMAT(f.startDate, '%Y-%m-%d'), ' to ', DATE_FORMAT(f.returnDate, '%Y-%m-%d')) 
                                            AS tripDuration, f.pax as pax, f.rooms as noOfRooms, f.phpPrice as price
                                          FROM fit f
                                          JOIN fithotel h ON h.hotelId = f.hotelId
                                          JOIN fitrooms r ON r.roomId = f.roomId
                                          WHERE transactionNo = '$transactionNumber'");

              if ($sql1 && mysqli_num_rows($sql1) > 0) 
              {
                $res1 = mysqli_fetch_assoc($sql1); // Fetch a single row since transactNo is unique
                $downpayment = $res1['pax'] * 1000;
                $formattedPrice = number_format($res1['price'], 2); // Format to 2 decimal places
              } 
              else 
              {
                echo "<p class='text-danger'>No booking details found for TransactNo: $transactionNumber.</p>";
              }
            ?>

            <?php if (!empty($res1)) 
            { ?>
              <div class="row">
                <div class="col-sm">
                  <div class="d-flex justify-content-between mb-1">
                    <p class="mb-0"><strong>Hotel:</strong></p>
                    <p class="mb-0"><?php echo $res1['hotelName']; ?></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm">
                  <div class="d-flex justify-content-between mb-1">
                    <p class="mb-0"><strong>Room Type:</strong></p>
                    <p class="mb-0"><?php echo $res1['roomName']; ?></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm">
                  <div class="d-flex justify-content-between mb-1">
                    <p class="mb-0"><strong>Total Nights:</strong></p>
                    <p class="mb-0"><?php echo $res1['nights']; ?></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm">
                  <div class="d-flex justify-content-between mb-1">
                    <p class="mb-0"><strong>Number of Rooms:</strong></p>
                    <p class="mb-0"><?php echo $res1['noOfRooms']; ?></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm">
                  <div class="d-flex justify-content-between mb-1">
                    <p class="mb-0"><strong>Trip Duration:</strong></p>
                    <p class="mb-0"><?php echo $res1['tripDuration']; ?></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm">
                  <div class="d-flex justify-content-between mb-1">
                    <p class="mb-0"><strong>Total Guests:</strong></p>
                    <p class="mb-0"><?php echo $res1['pax']; ?></p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm">
                  <div class="d-flex justify-content-between mb-1">
                    <p class="mb-0"><strong>Total Price:</strong></p>
                    <p class="mb-0">₱ <?php echo number_format($res1['price'], 2); ?></p>
                  </div>
                </div>
              </div>
            <?php 
            } 
            ?>

            <form id="paymentForm" enctype="multipart/form-data">
              <hr>
              <input type="hidden" value="<?php echo $_SESSION['agent_accountId']; ?>" name="agentAccountId">
              <input type="hidden" value="<?php echo $transactionNumber; ?>" name="transactNo">
              <input type="number" class="form-control" name="downpayment" step="0.01" 
                    min="<?php echo $downpayment; ?>" 
                    max="<?php echo $res1['price']; ?>" 
                    placeholder="Enter Downpayment Amount" required>
              <h6 class="mt-4">Attach Proof/Screenshot of transaction:</h6>
              <input type="file" id="attachment" class="attachment" name="proofs[]" accept="image/*" required>
              <hr>

              <div class="row mt-4">
                <div class="col-sm">
                  <div class="d-flex align-items-left mb-3">
                    <input type="checkbox" id="termsCheckbox" class="ms-1 me-3" required>
                    <div class="checkbox-text">
                      <span>
                        By clicking this, I agree to Smart Travel <a href="#" class="terms-link">Terms & Conditions</a> and 
                        <a href="#" class="privacy-link">Privacy Policy</a>
                      </span>
                    </div>
                  </div>
                  <button type="submit" class="pay-button btn btn-success">Pay Now</button>
                </div>
              </div>
            </form>
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

    <!-- Script for Pay Now -->
    <script>
      $(document).ready(function () {
        $("#paymentForm").on("submit", function (event) {
          event.preventDefault(); // Prevent default form submission

          $('#message-payment').html(''); // Clear previous messages
          $(".error-text").remove(); // Remove previous error messages

          let hasErrors = false;

          // Get input values
          let agentId = $("input[name='agentAccountId']").val().trim();
          let transactNo = $("input[name='transactNo']").val().trim();
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

          console.log("Form is valid, proceeding with AJAX submission...");
          console.log("Downpayment:", downpayment);
          console.log("Attachment:", attachment);
          console.log("Terms Checked:", termsChecked);

          let formData = new FormData();
          formData.append('agentAccountId', agentId);
          formData.append('transactNo', transactNo);
          formData.append('downpayment', downpayment);
          formData.append('proofs[]', $("#attachment")[0].files[0]);
          formData.append('termsChecked', termsChecked);
          formData.append('pay', true);

          for (let pair of formData.entries()) {
            console.log(pair[0]+ ':', pair[1]);
          }
                    
          // AJAX submission
          $.ajax(
          {
            url: "../Agent Section/functions/agent-addFITBookingPayment.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () 
            {
              $('#message-payment').html('<div class="alert alert-info">Processing payment...</div>');
            },
            success: function (response) 
            {
              console.log("Server Response:", response);

              let res;

              try {
                res = typeof response === "string" ? JSON.parse(response) : response;

                if (res.status === "success") {
                  let bookingStatus = res.bookingStatus; // Get bookingStatus from response
                  let transactionNumber = res.transactionNumber; // Get transactionNumber

                  // ✅ Always show Booked modal
                  $("#successModal").modal("show");

                  // ✅ If "Pay Later", show Reserved modal
                  // if (bookingStatus === "Pay Later") {
                  //   $("#successModalLater").modal("show");

                  // ✅ Otherwise, show Booked modal
                  // } else {
                  //   $("#successModal").modal("show");
                  // }

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
            window.location.href = "../Agent Section/agent-showFITBooking.php?id=<?= $transactionNumber ?>";
          }, 500); // Small delay for a smooth transition
        });

        // ✅ Redirect when modal is closed (by clicking outside or pressing ESC)
        $("#successModal").on("hidden.bs.modal", function () {
          // window.location.href = "../Agent Section/agent-FIT.php";
          window.location.href = "../Agent Section/agent-showFITBooking.php?id=<?= $transactionNumber ?>";
        });
      });
    </script>

  </body>

</html>