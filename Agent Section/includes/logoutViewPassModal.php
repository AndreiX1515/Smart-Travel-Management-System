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

          <div id="otpFieldContainer" class="mb-3" style="display: flex;">
            <label for="otp" class="form-label">OTP</label>
            <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP">
            <button type="button" class="btn btn-outline-primary" id="sendOtpBtn" style="margin-top: 30px;">Send OTP</button>
          </div>

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


<script>
  $(document).ready(function () {

    // Handle form submission
    $('#changePasswordForm').on('submit', function (e) {
      e.preventDefault(); // Prevent default form submission

      // Clear previous error messages and hide error labels
      document.getElementById('currentPasswordError').textContent = '';
      document.getElementById('newPasswordError').textContent = '';
      document.getElementById('confirmPasswordError').textContent = '';
      document.getElementById('messageAlert').style.display = 'none';

      // Hide all error labels (set display to none again before showing)
      document.getElementById('currentPasswordError').style.display = 'none';
      document.getElementById('newPasswordError').style.display = 'none';
      document.getElementById('confirmPasswordError').style.display = 'none';

      // Get form data
      const currentPassword = document.getElementById('currentPassword').value;
      const newPassword = document.getElementById('newPassword').value;
      const confirmNewPassword = document.getElementById('confirmNewPassword').value;

      // Validate New Password (custom logic)
      if (newPassword.length < 8) {
          document.getElementById('newPasswordError').textContent = 'Password must be at least 8 characters long.';
          document.getElementById('newPasswordError').style.display = 'block'; // Show error label
          return;  // Stop further checks if new password is invalid
      }

      // Validate New Password and Confirm Password
      if (newPassword !== confirmNewPassword) {
          document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
          document.getElementById('confirmPasswordError').style.display = 'block'; // Show error label
          return;  // Stop further checks if passwords don't match
      }

      $.ajax({
        url: '../Agent Section/functions/General/agent-changePassword.php',
        type: 'POST',
        data: { currentPassword: currentPassword },
        success: function (response) {
          console.log('Response:', response); // Log the entire response object

          // Make sure response is parsed correctly (in case it's a JSON string)
          response = JSON.parse(response);

          // Check if there are any errors in the response
          if (response.status === 'error') {
            // Log the errors if present
            console.log('Errors:', response.errors);

            // Display the error message in the general message alert
            $('#messageAlert').show();
            $('#messageAlert').text(response.message); // Show error message from response
            $('#messageAlert').css({
              'background-color': '#f8d7da', // Red background for error
              'color': '#721c24', // Dark red text color for error
              'border': '1px solid #f5c6cb' // Border color for error
            });

            // Display the first error message in the current password error label
            document.getElementById('currentPasswordError').textContent = response.message;
            document.getElementById('currentPasswordError').style.display = 'block'; // Show error label

          } else if (response.status === 'success') {
            // If successful, log the success response
            console.log('Success:', response.message);

            // Display the success message in the general message alert
            $('#messageAlert').show();
            $('#messageAlert').text(response.message); // Show success message from response
            $('#messageAlert').css({
              'background-color': '#d4edda', // Green background for success
              'color': '#155724', // Dark green text color for success
              'border': '1px solid #c3e6cb' // Border color for success
            });

            // Proceed with further actions (e.g., show OTP field, redirect, etc.)
            console.log('Proceed to password change or OTP step.');

            // Add a 2-second delay before showing the OTP field container
            setTimeout(function() {
              // Show OTP field container (if OTP is part of the process)
              var otpFieldContainer = document.getElementById('otpFieldContainer');
              otpFieldContainer.style.display = 'block'; // Show OTP field
            }, 2000); // 2-second delay
          }
        },
        error: function () {
          // If an error occurs with the AJAX request, show an error message
          $('#messageAlert').show().text('An error occurred while validating the password.');
          $('#messageAlert').css({
            'background-color': '#f8d7da', // Red background for error
            'color': '#721c24', // Dark red text color for error
            'border': '1px solid #f5c6cb' // Border color for error
          });
        }
      });

      
    });
  });
</script>







<!-- Logout Script --> 
<script>
  $(document).ready(function() {
    $('#logoutButton').click(function() {
      $.ajax({
        url: '../Agent Section/functions/agent-logout.php',
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






<!-- <script>
  $(document).ready(function() {
    // Handle OTP Send Button
    $('#sendOtpBtn').click(function() {
      // Get the values for the new password and confirmed password
      var newPassword = $('#newPassword').val();
      var confirmPassword = $('#confirmNewPassword').val();

      console.log(newPassword, confirmPassword)

      // Check if both new password and confirmed password have values
      if (!newPassword || !confirmPassword) {
        // Display error message if either password is empty
        $('#messageAlert').text('Please fill in both the new password and confirm password fields.')
          .css('border', '1px solid red')
          .css('background-color', '#f8d7da')
          .css('color', 'red')
          .show();
        return; // Stop the function from continuing
      }

      // Check if the new password and confirmed password match
      if (newPassword !== confirmPassword) {
        // Display error message if passwords don't match
        $('#messageAlert').text('The new password and confirm password do not match.')
          .css('border', '1px solid red')
          .css('background-color', '#f8d7da')
          .css('color', 'red')
          .show();
        return; // Stop the function from continuing
      }

      // Clear the error message if the passwords are valid
      $('#messageAlert').hide();

      var email = "<?php echo htmlspecialchars($email); ?>"; // PHP to JS variable

      // Show a loading spinner or disable the button to prevent multiple requests
      $('#sendOtpBtn')
        .prop('disabled', true)
        .text('Sending OTP...')
        .css('font-size', '12px'); // This sets the font size to 12px (adjust as needed)

      $.ajax({
        url: '../Agent Section/functions/agent-sendOtpCPassword.php',
        type: 'POST',
        data: {
          email: email // Send the user's email to the server
        },
        success: function(response) {
          // Parse the JSON response from the server
          var jsonResponse = JSON.parse(response);

          if (jsonResponse.success) {
            // Inform the user that OTP was sent successfully
            $('#messageAlert').text('OTP has been sent to your registered email address.')
              .css('border', '1px solid green')
              .css('background-color', '#d4edda')
              .css('color', 'green')
              .show();
          } else {
            // Handle the error (invalid email, failed to send OTP, etc.)
            $('#messageAlert').text('Failed to send OTP: ' + jsonResponse.message)
              .css('border', '1px solid red')
              .css('background-color', '#f8d7da')
              .css('color', 'red')
              .show();
          }

          // Re-enable the button and reset its text
          $('#sendOtpBtn').prop('disabled', false).text('Send OTP');
        },
        error: function() {
          // Handle any error that occurred during the AJAX request
          $('#messageAlert').text('An error occurred while sending the OTP.')
            .css('border', '1px solid red')
            .css('background-color', '#f8d7da')
            .css('color', 'red')
            .show();
          $('#sendOtpBtn').prop('disabled', false).text('Send OTP');
        }
      });
    });
  });

  $(document).ready(function() {
    // Handle Change Password Form submission
    $('#changePasswordForm').submit(function(e) {
      e.preventDefault(); // Prevent the default form submission

      // Get the entered OTP and new password details
      var enteredOtp = $('#otp').val(); // OTP input field ID 'otp'
      var newPassword = $('#newPassword').val();
      var confirmNewPassword = $('#confirmNewPassword').val();
      var account_id = "<?php echo htmlspecialchars($accountId); ?>"; // PHP to JS variable


      // Check if the new password and confirmation match
      if (newPassword !== confirmNewPassword) {
        $('#messageAlert').text('Passwords do not match.')
          .css('border', '1px solid red')
          .css('background-color', '#f8d7da')
          .css('color', 'red')
          .show();
        return; // Stop further execution if passwords do not match
      }

      // Perform the AJAX request to verify OTP
      $.ajax({
        url: '../Agent Section/functions/agent-verify-otp.php', // Path to OTP verification script
        type: 'POST',
        data: {
          'changepass-OTP': enteredOtp,
          'accountid': account_id,
          'newPassword': newPassword // No underscore here
        },
        success: function(response) {
          var jsonResponse = JSON.parse(response); // Assuming the server returns JSON

          // If OTP is valid, proceed with password change
          if (jsonResponse.success) {
            // Proceed with password change if OTP is verified
            $.ajax({
              url: '../Agent Section/functions/agent-passwordChangeFunction.php', // Path to password change script
              type: 'POST',
              data: {
                'changepass-OTP': enteredOtp,
                'accountid': account_id,
                'newPassword': newPassword // No underscore here
              },
              success: function(changePasswordResponse) {
                var changeResponse = JSON.parse(changePasswordResponse);
                if (changeResponse.status === 'success') {
                  $('#messageAlert').text('Password changed successfully.')
                    .css('border', '1px solid green')
                    .css('background-color', '#d4edda')
                    .css('color', 'green')
                    .show();
                  location.reload();
                } else {
                  $('#messageAlert').text(changeResponse.message)
                    .css('border', '1px solid red')
                    .css('background-color', '#f8d7da')
                    .css('color', 'red')
                    .show();
                }
              },
              error: function() {
                $('#messageAlert').text('An error occurred while changing the password.')
                  .css('border', '1px solid red')
                  .css('background-color', '#f8d7da')
                  .css('color', 'red')
                  .show();
              }
            });
          } else {
            $('#messageAlert').text('Invalid OTP entered.')
              .css('border', '1px solid red')
              .css('background-color', '#f8d7da')
              .css('color', 'red')
              .show();
          }
        },
        error: function() {
          $('#messageAlert').text('An error occurred while verifying the OTP.')
            .css('border', '1px solid red')
            .css('background-color', '#f8d7da')
            .css('color', 'red')
            .show();
        }
      });
    });
  });
</script> -->

<!-- <script>
  document.addEventListener('DOMContentLoaded', () => 
  {
    // Check if there's a saved title in local storage
    const savedTitle = localStorage.getItem('pageTitle');
    if (savedTitle) 
    {
      document.getElementById('page-title').textContent = savedTitle;
    }

    const buttons = document.querySelectorAll('.page-button');

    buttons.forEach(button => 
    {
      button.addEventListener('click', (event) => 
      {
        event.preventDefault();
        const newPageName = button.getAttribute('data-page-name');
        document.getElementById('page-title').textContent = newPageName;

        localStorage.setItem('pageTitle', newPageName);

        const newUrl = button.getAttribute('href');
        setTimeout(() => 
        {
          window.location.href = newUrl;
        }, 25);
      });
    });
  });
</script> -->