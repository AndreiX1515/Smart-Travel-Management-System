<?php
session_start();
require "../conn.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$accountId = $_SESSION['accountId'];
$agentId = $_SESSION['agentId'];
$fName = $_SESSION['fName'] ?? '';
$lName = $_SESSION['lName'] ?? '';
$mName = $_SESSION['mName'] ?? '';
$branch = $_SESSION['branch'] ?? '';

// Format the full name
$fullName = htmlspecialchars($lName . ', ' . $fName . ($mName ? ' ' . substr($mName, 0, 1) . '.' : ''));

// Query to retrieve the password based on account ID
$sql = "SELECT password, email FROM accounts WHERE accountId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $accountId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
 $row = $result->fetch_assoc();
 $password = $row['password']; // Retrieve the password
 $email = $row['email']; // Retrieve the email
} else {
 $password = 'Password not found.';
 $email = 'Email not found.'; // Set a default value for email if no results are found
}


// Optional: hide password by default
$maskedPassword = '••••••••••';
?>

<?php
// Start session
date_default_timezone_set('Asia/Taipei'); // Set the timezone to Taipei
$current_date = date('D, F d, Y'); // Format: "Tue, January 01, 2024"
?>

<header>      
  <nav class="navbar navbar-expand-lg justify-content-between sticky-top">
     <div class="container-fluid d-flex justify-content-between">
        <div class="nav-start-container d-flex flex-row">
            <!-- Toggle button for the sidebar -->
            <!-- <div class="toggle-btn" id="toggleBtn">
                <i class="fa-solid fa-bars"></i>
            </div> -->

            <a class="navbar-brand" id="page-title" style="font-weight: 500;"></a> 
        </div>

          <div class="nav-end-container d-flex flex-row align">

          <div class="date-time-container d-flex flex-row align-items-center">
              <h6><?php echo $current_date; ?></h6>
          </div>

          <div class="vertical-line-navbar"></div> <!-- Vertical Line -->

           <div class="collapse navbar-collapse" id="navbarNav">
             <ul class="navbar-nav ms-auto">
               <li class="nav-item dropdown d-flex align-items-center">

                   <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                       <div class="profile-container ms-2 me-3">
                           <h6 class="mb-1"><?php echo $fullName; ?></h6> 
                           <span class="m-0">Branch: <?php echo $branch; ?></span>
                           <!-- <span class="m-0">Agent Id: <?php echo $agentId; ?></span> -->
                       </div>
                       <img src="../assets/images/circle.png" alt="Profile" class="profile-image me-2" width="40px" height="40px">
                   </a>

                  <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="navbarDropdown">
                      <li>
                       <a class="dropdown-item" href="#" style="font-size: 14px;" data-bs-toggle="modal" data-bs-target="#viewPasswordModal">
                         <i class="fas fa-user me-2"></i> View Password
                       </a>
                     </li>

                     <li>
                         <hr class="dropdown-divider">
                     </li>
                     <li>
                         <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" style="font-size: 14px;">
                             <i class="fas fa-sign-out-alt me-2"></i> Logout
                         </a>
                     </li>
                  </ul>

                </li>

                
              </ul>
            </div>
          </div>
      </div>
  </nav>
</header>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
   <div class="modal-content">
     <div class="modal-header">
         <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
     <div class="modal-body">
         Are you sure you want to logout?
     </div>
     <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
         <a href="../Agent Section/agent-login.php" class="btn btn-danger" id="logoutButton">Logout</a>
     </div>
   </div>
 </div>
</div>

<!-- View Password Modal -->
<div class="modal fade" id="viewPasswordModal" tabindex="-1" aria-labelledby="viewPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewPasswordModalLabel">Manage Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

       <div class="modal-body">
         <!-- Password Display Section -->
         <div class="password-header d-flex align-items-center justify-content-between mb-3">
           <p class="mb-0 me-3">Your password is: <span id="passwordText"><?= htmlspecialchars($maskedPassword); ?></span></p>
           <button type="button" class="btn btn-outline-secondary" id="togglePasswordBtn">
             <i class="fas fa-eye" id="toggleIcon"></i>
           </button>
         </div>

         <hr class="mt-3 mb-4">

         <h6 class="fw-bold mb-3">Change Password:</h6>
         <!-- Change Password Fields -->
         <form id="changePasswordForm" action="path_to_handle_password_change.php" method="POST">

         <div class="mb-3">
           <label for="newPassword" class="form-label">New Password</label>
           <input type="password" class="form-control" id="newPassword" name="newPassword" required>
         </div>
         <div class="mb-3">
           <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
           <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" required>
         </div>
         <div class="mb-3 d-flex justify-content-between align-items-center">
           <div class="w-75">
             <label for="otp" class="form-label">OTP</label>
             <input type="text" class="form-control" id="otp" name="otp" required>
           </div>
           <button type="button" class="btn btn-outline-primary" id="sendOtpBtn" style="margin-top: 30px;">Send OTP</button>
         </div>

         <!-- Message Alert Div with Red Border and Light Red Background -->
         <div id="messageAlert" style="display:none; padding: 10px; margin: 15px 0; border: 1px solid red; background-color: #f8d7da; color: red; border-radius: 5px;">
         </div>


         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
           <button type="submit disabled" class="btn btn-primary" id="changePasswordBtn" style="margin-right: -15px;">Change Password</button>
         </div>
       </form>
    </div>
  </div>
</div>
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
document.getElementById('togglePasswordBtn').addEventListener('click', function() {
  const passwordText = document.getElementById('passwordText');
  const toggleIcon = document.getElementById('toggleIcon');
  
  // Toggle between masked and actual password
  if (passwordText.textContent === '••••••••••') {
    passwordText.textContent = "<?= htmlspecialchars($password); ?>"; // Replace dots with actual password
    toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    passwordText.textContent = '••••••••••';
    toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
  }
});
</script>


<script>

   $(document).ready(function () {
    // Handle OTP Send Button
    $('#sendOtpBtn').click(function () {
        // Get the values for the new password and confirmed password
        var newPassword = $('#newPassword').val();
        var confirmPassword = $('#confirmNewPassword').val();

        console.log(newPassword,confirmPassword)
        
        // Check if both new password and confirmed password have values
        if (!newPassword || !confirmPassword) {
            // Display error message if either password is empty
            $('#messageAlert').text('Please fill in both the new password and confirm password fields.')
                .css('border', '1px solid red')
                .css('background-color', '#f8d7da')
                .css('color', 'red')
                .show();
            return;  // Stop the function from continuing
        }

        // Check if the new password and confirmed password match
        if (newPassword !== confirmPassword) {
            // Display error message if passwords don't match
            $('#messageAlert').text('The new password and confirm password do not match.')
                .css('border', '1px solid red')
                .css('background-color', '#f8d7da')
                .css('color', 'red')
                .show();
            return;  // Stop the function from continuing
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
            success: function (response) {
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
            error: function () {
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



$(document).ready(function () {
  // Handle Change Password Form submission
  $('#changePasswordForm').submit(function (e) {
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
        'newPassword': newPassword  // No underscore here
    },
    success: function (response) {
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
                    'newPassword': newPassword  // No underscore here
                },
                success: function (changePasswordResponse) {
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
                error: function () {
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
    error: function () {
        $('#messageAlert').text('An error occurred while verifying the OTP.')
            .css('border', '1px solid red')
            .css('background-color', '#f8d7da')
            .css('color', 'red')
            .show();
    }
});





  });
});


</script>



<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Check if there's a saved title in local storage
    const savedTitle = localStorage.getItem('pageTitle');
    if (savedTitle) {
        document.getElementById('page-title').textContent = savedTitle;
    }

    const buttons = document.querySelectorAll('.page-button');

    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const newPageName = button.getAttribute('data-page-name');
            document.getElementById('page-title').textContent = newPageName;

            // Save the title to local storage
            localStorage.setItem('pageTitle', newPageName);

            const newUrl = button.getAttribute('href');
            setTimeout(() => {
                window.location.href = newUrl;
            }, 25);
        });
    });
});



</script>