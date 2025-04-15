<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">

        <div class="modal-title-wrapper">
          <h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
          <small class="modal-subtext">Ensure your new password is secure and different from previous ones.</small>
        </div>

        <div class="modal-close-wrapper">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>

      <form id="changePasswordForm">
        <div class="modal-body">
          <div class="mb-3">
            <label for="currentPassword" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Enter current password">
            <small id="currentPasswordError" class="error-label text-danger"></small>
          </div>

          <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter new password" required>
            <small id="newPasswordError" class="error-label text-danger"></small>
          </div>

          <div class="mb-3">
            <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" placeholder="Re-enter new password" required>
            <small id="confirmPasswordError" class="error-label text-danger"></small>
          </div>

          <!-- <div id="otpFieldContainer">
              <label for="otp" class="form-label">OTP</label>
              <div class="d-flex align-items-center otp-container">
                  <input type="text" class="form-control otp-input" id="otp" name="otp" max="6" placeholder="Enter OTP">
                  <button type="button" class="btn btn-outline-primary resend-btn" id="sendOtpBtn">Send OTP</button>
              </div>
              <small id="confirmPasswordError" class="error-label text-danger"></small>
          </div> -->

          <div id="messageAlert"> </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Change Password</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- OTP Verification Modal -->
<div class="modal fade" id="otpVerificationModal" tabindex="-1" aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content otp-modal-content">

      <div class="modal-body">
        <div class="header-body-wrapper">
          <div class="otp-header">
            <div class="otp-icon-wrapper">
              <div class="otp-icon">
                <i class="fa-solid fa-lock"></i>
              </div>
            </div>

            <div class="header-body-content">
              <h5 class="modal-title">Verify OTP</h5>
              <p class="otp-subtext">To proceed with resetting your password, we’ve sent a verification code to your email address. <br> <span class="otp-email-mask">is****a8@gmail.com</span></p>
            </div>
          </div>
        </div>

        <form id="otpVerificationForm">
          <div id="otpFieldContainer" class="otp-field-container">
            <div class="otp-modal-container">
              <input type="text" class="otp-modal-input" maxlength="1" id="otp1">
              <input type="text" class="otp-modal-input" maxlength="1" id="otp2">
              <input type="text" class="otp-modal-input" maxlength="1" id="otp3">
              <input type="text" class="otp-modal-input" maxlength="1" id="otp4">
              <input type="text" class="otp-modal-input" maxlength="1" id="otp5">
              <input type="text" class="otp-modal-input" maxlength="1" id="otp6">
            </div>

            <small id="otpError" class="error-label text-danger"></small>
          </div>

          <div class="verify-button-wrapper">
            <button type="submit" class="btn btn-primary otp-verify-btn">Verify</button>
          </div>

          <div class="otpResent-button-wrapper">
            <p class="otp-resend-text">Didn’t receive code? </p> <a href="#" id="sendOtpBtn" class="otp-resend-link">Resend</a>
          </div>

          <div id="otpAlert"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- OTP Input Focus Script -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const otpInputs = document.querySelectorAll("#otpVerificationModal .otp-modal-input");

    otpInputs.forEach((input, index) => {
      input.addEventListener("input", (e) => {
        if (e.target.value && index < otpInputs.length - 1) {
          otpInputs[index + 1].focus();
        }
      });

      input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && index > 0 && !e.target.value) {
          otpInputs[index - 1].focus();
        }
      });
    });
  });
</script>

<!-- jQuery Script for Change Password Modal -->
<script>
$(document).ready(function() {

    function showOtpAlert(message, status) {

      let backgroundColor, textColor, borderColor;

        // Determine the color scheme based on the provided status
        if (status === 'success') {
            backgroundColor = '#d4edda'; // Green background
            textColor = '#155724'; // Dark green text
            borderColor = '#c3e6cb'; // Green border
        } else if (status === 'error') {
            backgroundColor = '#f8d7da'; // Red background
            textColor = '#721c24'; // Dark red text
            borderColor = '#f5c6cb'; // Red border
        } else {
            backgroundColor = '#fff3cd'; // Yellow background (default for warnings)
            textColor = '#856404'; // Dark yellow text
            borderColor = '#ffeeba'; // Yellow border
        }

        // Apply the styles and show the alert
        $('#otpAlert').text(message).css({
            'background-color': backgroundColor,
            'color': textColor,
            'border': `1px solid ${borderColor}`
        }).show();

        // Hide the alert after 3.5 seconds (3500 milliseconds)
        setTimeout(function() {
            $('#otpAlert').fadeOut();
        }, 3500);
    }

    function showCPAlert(message, status) {

    // Set the color and background based on the status
    let backgroundColor, textColor, borderColor;

      // Determine the color scheme based on the provided status
      if (status === 'success') {
          backgroundColor = '#d4edda'; // Green background
          textColor = '#155724'; // Dark green text
          borderColor = '#c3e6cb'; // Green border
      } else if (status === 'error') {
          backgroundColor = '#f8d7da'; // Red background
          textColor = '#721c24'; // Dark red text
          borderColor = '#f5c6cb'; // Red border
      } else {
          backgroundColor = '#fff3cd'; // Yellow background (default for warnings)
          textColor = '#856404'; // Dark yellow text
          borderColor = '#ffeeba'; // Yellow border
      }

      // Apply the styles and show the alert
      $('#messageAlert').text(message).css({
          'background-color': backgroundColor,
          'color': textColor,
          'border': `1px solid ${borderColor}`
      }).show();

      // Hide the alert after 3.5 seconds (3500 milliseconds)
      setTimeout(function() {
          $('#messageAlert').fadeOut();
      }, 3500);
    }

    function sendOtp(currentPassword, emailAddress) {
      // Check if email address is missing or invalid
      if (!emailAddress || emailAddress === 'null' || emailAddress.trim() === '') {
        showOtpAlert('Email address is missing. Please update your profile to receive OTP.', 'error');
        console.warn('Attempted to send OTP without a valid email address.');
        return;
      }

      $.ajax({
        url: '../Agent Section/functions/agent-sendOtpCPassword.php',
        type: 'POST',
        data: {
          currentPassword: currentPassword,
          emailAddress: emailAddress
        },
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            console.log('OTP Sent:', response.otp);

            // Mask the email for display
            function maskEmail(email) {
              const parts = email.split('@');
              const username = parts[0];
              const domain = parts[1];
              const maskedUsername = username.charAt(0) + '******' + username.charAt(username.length - 1);
              return maskedUsername + '@' + domain;
            }

            // Show success alert immediately
            showOtpAlert('OTP has been sent to your email address.', 'success');

            // Delay action before showing OTP modal
            setTimeout(function() {
              $('#changePasswordModal').modal('hide');
              $('#otpVerificationModal').modal('show');

              // Append accountId to form
              $('#otpVerificationForm').append('<input type="hidden" name="accountId" value="' + response.accountId + '">');

              // Mask and display the email address
              const maskedEmail = maskEmail(emailAddress);
              $('#otpVerificationModal .otp-email-mask').text(maskedEmail);

              console.log('Masked Email:', maskedEmail);
            }, 500); // 2.5 second delay

          } else {
            // OTP sending failed
            console.log('Error Sending OTP:', response.message);
            showOtpAlert('Failed to send OTP. Please try again.', 'error');
          }
        },

        error: function(xhr, status, error) {
          // AJAX call itself failed
          console.log('AJAX Error:', error);
          showOtpAlert('An error occurred while sending OTP.', 'error');
        }
      });
    }
    
    // Handle form submission for change password
    $('#changePasswordForm').on('submit', function(e) {
      e.preventDefault(); // Prevent default form submission

      // Clear previous error messages and hide error labels
      document.getElementById('currentPasswordError').textContent = '';
      document.getElementById('newPasswordError').textContent = '';
      document.getElementById('confirmPasswordError').textContent = '';
      $('#messageAlert').hide();

      // Get form data
      const currentPassword = document.getElementById('currentPassword').value;
      const newPassword = document.getElementById('newPassword').value;
      const confirmNewPassword = document.getElementById('confirmNewPassword').value;

      // Validate New Password
      if (newPassword.length < 8) {
        document.getElementById('newPasswordError').textContent = 'Password must be at least 8 characters long.';
        document.getElementById('newPasswordError').style.display = 'block';
        return;
      }

      // Validate New Password and Confirm Password
      if (newPassword !== confirmNewPassword) {
        document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
        document.getElementById('confirmPasswordError').style.display = 'block';
        return;
      }

      $.ajax({
          url: '../Client Section/Functions/General/client-changePassword.php', // Your PHP script URL
          type: 'POST',
          data: {
              currentPassword: currentPassword,
              newPassword: newPassword
          },
          success: function(response) {
              response = JSON.parse(response);

              // Handle errors from PHP
              if (response.status === 'error') {
                  // If the error is related to the current password
                  if (response.message.includes('Current password is incorrect')) {
                      document.getElementById('currentPasswordError').textContent = response.message;
                      document.getElementById('currentPasswordError').style.display = 'block';
                  }
                  
                  // If the error is related to emailAddress being empty or invalid
                  else if (response.message.includes('Email address not found or is empty')) {
                      showCPAlert(response.message, response.status)
                  }
                 

                  else {
                      showCPAlert(response.message, 'error');
                  }

              } else if (response.status === 'success') {
                  const accountId = response.accountId;
                  const emailAddress = response.emailAddress;

                  // Store in sessionStorage
                  sessionStorage.setItem('emailAddress', emailAddress);
                  console.log('Email Address:', emailAddress); // Debugging log

                  showCPAlert(response.message, 'success');

                  // Call OTP function after success
                  setTimeout(function() {
                      sendOtp(currentPassword, emailAddress); // Reusable OTP function
                  }, 500);
              }
          },
          
          error: function(xhr, status, error) {
              console.log('AJAX Error:', error);
              showCPAlert('An error occurred while validating the password.', 'error');
          }
      });

    });

    // OTP verification form submission
    $('#otpVerificationForm').on('submit', function(e) {
      e.preventDefault(); // Prevent normal form submission

      let otp = '';
      $('.otp-modal-input').each(function() {
        otp += $(this).val();
      });

      if (otp.length !== 6) {
        $('#otpError').text('Please enter all 6 digits of the OTP.');
        return;
      }

      $.ajax({
        url: '../Agent Section/functions/General/agent-verifyOTP.php',
        type: 'POST',
        data: {
          otp: otp
        },

        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            console.log('OTP Verified Successfully'); // Debugging log

            let newPassword = $('#newPassword').val();
            let accountId = <?= $accountId; ?>;

            if (!newPassword || newPassword.trim() === '') {

              showOtpAlert(response.message, 'error'); 
              return;
            }

            // Password Change AJAX Request
            $.ajax({
              url: '../Client Section/Functions/General/client-newPasswordChange.php',
              method: 'POST',
              data: {
                newPassword: newPassword,
                accountId: accountId
              },
              dataType: 'json',
              success: function(res) {
                if (res.status === 'success') {

                  showOtpAlert(response.message, response.status); 

                  setTimeout(function() {
                    location.reload(); // Reload the page after 2 seconds
                  }, 3000);

                } else {
                  $('#messageAlert').show().text(res.message).css({
                    'background-color': '#f8d7da',
                    'color': '#721c24',
                    'border': '1px solid #f5c6cb'
                  });
                }


              },
              error: function(xhr, status, error) {
                console.error("AJAX Error (Password Change):", error);
                console.log("Response Text (Password Change):", xhr.responseText);

                $('#messageAlert').show().text('An error occurred while updating the password. Please try again.').css({
                  'background-color': '#f8d7da',
                  'color': '#721c24',
                  'border': '1px solid #f5c6cb'
                });

              }
            });

          } else if (response.status === 'error') {
            console.log('OTP Verification Failed:', response.message); 
            showOtpAlert(response.message, response.status); 
          }   
          
          else {
            showOtpAlert(response.message, response.status); 
          }

        },
        error: function(xhr, status, error) {
          console.error("AJAX Error (OTP Verification):", error);
          console.log("Response Text (OTP Verification):", xhr.responseText);
          $('#otpError').text('An error occurred during OTP verification. Please try again.');
        }
      });
    });

    // Resend OTP functionality
    $('#sendOtpBtn').click(function(e) {
        e.preventDefault();

        const currentPassword = document.getElementById('currentPassword').value;
        let emailAddress = <?= json_encode($_SESSION['emailAddress'] ?? null); ?>;

        // Fallback: use sessionStorage if PHP session is null, 'null', or empty string
        if (!emailAddress || emailAddress === 'null' || emailAddress === '') {
          emailAddress = sessionStorage.getItem('emailAddress');
        }

        // Check if password is empty
        if (!currentPassword) {
          showOtpAlert('Please enter your current password to resend OTP.', 'error');
          return;
        }

        // Proceed if emailAddress is available
        if (emailAddress) {
          sendOtp(currentPassword, emailAddress); // Reuse existing OTP sending function
          showOtpAlert('Resending OTP. Please wait...', 'success');
        } else {
          console.warn('Email address not found in session or sessionStorage.');
          showOtpAlert('Unable to send OTP, Please Try Again.', 'error');
        }
      });


  });
</script>

<!-- Logout Script -->
<script>
  $(document).ready(function() {
    $('#logoutButton').click(function() {
      $.ajax({
        url: '../Client Section/Functions/client-logout.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            window.location.href = '../Agent Section/agentLogin.php';
          } else {
            window.location.href = '../Agent Section/agentLogin.php';
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.error('AJAX Error:', textStatus, errorThrown);
          alert("An error occurred during logout. Please try again.");
          window.location.href = '../Agent Section/agentLogin.php';
        }
      });
    });
  });
</script>

