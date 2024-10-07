<?php
session_start(); // Make sure to start the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>

    <?php include 'includes/head.php' ?>

    <link href="assets/css/sampleregister.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>

<body>
    <!-- Back to homepage button -->
    <a href="login.php" class="back-btn">
      <i class="fas fa-arrow-left"></i> Back to Login Page
    </a>
    
    <div class="container-background"></div>
  
    <div class="main-container">
      <!-- Accent Image Section -->
      <div class="image-accent position-relative">
        <div class="logo-container text-left">
            <img src="assets/images/logo.png" alt="Logo">
        </div>
          <div class="bg-overlay"></div>
          <img src="assets/images/register-accent-image.jpg" alt="Accent Image" class="img-fluid">
      </div>
  
      <!-- Login Form Section -->
      <div class="loginform d-flex flex-column">
            <div class="header-container d-flex flex-column text-start mt-3 mb-3">
                <h5 class="header h2 fw-bolder mb-0">Register</h5> <!-- Removed margin-bottom -->
                <p class="h6 sub-header mb-0">The start of your journey with us.</p>
            </div>
            <!-- Registration Form -->
            <form class="mt-lg-3" id="registerForm" method="POST" action="register.php">
                <div class="message-1 mb-2 fw-bold" id="message-1"> </div>
                <!-- <div class="mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingFirstName" name="Reg-FirstName" placeholder="First Name" required>
                        <label for="floatingFirstName">First Name<small class="text-danger"> *</small></label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingLastName" name="Reg-LastName" placeholder="Last Name" required>
                        <label for="floatingLastName">Last Name<small class="text-danger"> *</small></label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingMiddleName" name="Reg-MiddleName" placeholder="Middle Name">
                        <label for="floatingMiddleName">Middle Name</label>
                    </div>
                </div> -->

                <div class="mb-2">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="floatingEmail" name="Reg-Email" placeholder="Email" required>
                        <label for="floatingEmail">Email <small class="text-danger"> *</small></label>
                    </div>

                <div id="email-message" class="text-danger"></div>
                 <!-- Message area -->
                </div>

                <div class="message mt-1 mb-1 d-flex justify-content-start text-danger" id="message-email"></div>

                <div class="mt-2 mb-3 position-relative">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" name="Reg-Password" placeholder="Password" required>
                        <label for="floatingPassword">Password <small class="text-danger"> *</small></label>
                        <span id="togglePassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);">
                            <i class="far fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-2 position-relative">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword2" name="Reg-CPassword" placeholder="Confirm Password" required>
                        <label for="floatingPassword2">Confirm Password <small class="text-danger"> *</small></label>
                        <span id="togglePassword2" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);">
                            <i class="far fa-eye" id="toggleIcon2"></i>
                        </span>
                    </div>
                </div>

                <!-- <div class="message mt-1 mb-2 d-flex justify-content-start text-danger" id="message-confirmp"></div> -->

                <div class="mb-3">
                    <!-- <div class="send-otp d-flex flex-row align-items-center">
                        <div class="form-floating me-3 flex-grow-1">
                            <input type="text" class="form-control" id="floatingOtp" name="Reg-OTP" placeholder="Enter OTP">
                            <label for="floatingOtp">OTP</label>
                        </div>
                         Send OTP link with countdown 
                        <a href="#" id="sendOtpLink" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Send OTP <span id="otpCountdown"></span></a>
                    </div> -->
                </div>

                <button type="submit" class="btn btn-primary w-100" id="SubmitRegButton">Register</button>
                
            </form>
      </div>
  </div>

    <!-- Modals -->
    <!-- OTP Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="staticBackdropLabel">Enter the Confirmation Code sent to your Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

        <div class="modal-body p-4">
            

            <div class="primary-text mb-4">
                 Let us know this email belongs to you. Enter the code in the email sent to <br> <span class="fw-bold"> 
                    <?php 
                        echo $email
                        
                    ?> 
                 
                
                </span>
            </div>

            <div class="otp-field-container mb-4">
                <div class="send-otp d-flex flex-row align-items-center mb-4">
                        <div class="form-floating me-3 flex-grow-1">
                            <input type="text" class="form-control" id="floatingOtp" name="Reg-OTP" placeholder="Enter OTP">
                            <label for="floatingOtp">OTP</label>
                        </div>
                </div>

                <!-- Send OTP link with countdown -->
                <a href="#" id="sendOtpLink">Send OTP <span id="otpCountdown"></span></a>
                <div class="message-otp my-2 fw-bold" id="message-otp"> </div>
            </div>
            
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit Contact Info</button>
            <button type="button" class="btn btn-primary" id="verifyOtpButton">Submit</button>
        </div>

        </div>
    </div>
</div>

    <?php include 'includes\scripts.php'; ?>

    <script>
    $(document).ready(function () {
        // Check if email is already in use on blur
        $('#floatingEmail').on('blur', function () {
            let emailField = $(this);
            let email = emailField.val();
            
            // Clear previous message and reset border
            $('#message-1').text('').removeClass('show');
            emailField.css('border', '');

            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                $('#message-1').text('Please enter a valid email address').addClass('show');
                emailField.css('border', '1px solid lightcoral');
                showMessage(); // Call to handle fade out
                return;
            }

            // Check if email is already in use
            $.ajax({
                url: 'email-check.php', // Your PHP script to check the email
                method: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function (response) {
                    if (response.exists) {
                        $('#message-1').text('This email is already in use.').addClass('show');
                        emailField.css('border', '1px solid lightcoral');
                        showMessage(); // Call to handle fade out
                    } else {
                        // Email is available
                        emailField.css('border', ''); // Reset border if valid
                    }
                },
                error: function () {
                    $('#message-1').text('Error checking email. Please try again.').addClass('show'); // Handle error display
                    showMessage();
                }
            });
        });

        // Registration button click handler
        $('#SubmitRegButton').on('click', function (e) {
            e.preventDefault(); // Prevent default behavior

            // Retrieve input field values
            let emailField = $('#floatingEmail');
            let email = emailField.val();
            let passwordField = $('#floatingPassword');
            let password = passwordField.val();
            let cpasswordField = $('#floatingPassword2');
            let cpassword = cpasswordField.val();

            // Clear any previous message and reset borders
            $('#message-1').text('').removeClass('show');
            emailField.css('border', '');
            passwordField.css('border', '');
            cpasswordField.css('border', '');

            // Validation logic
            if (!email || !password || !cpassword) {
                $('#message-1').text('All fields are required').addClass('show');
                if (!email) emailField.css('border', '1px solid lightcoral');
                if (!password) passwordField.css('border', '1px solid lightcoral');
                if (!cpassword) cpasswordField.css('border', '1px solid lightcoral');
                showMessage(); // Call to handle fade out
                return;
            }

            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                $('#message-1').text('Please enter a valid email address').addClass('show');
                emailField.css('border', '1px solid lightcoral');
                showMessage();
                return;
            }

            // Password length validation
            if (password.length < 8) {
                $('#message-1').text('Password must be at least 8 characters long').addClass('show');
                passwordField.css('border', '1px solid lightcoral');
                showMessage();
                return;
            }

            // Check if passwords match
            if (password !== cpassword) {
                $('#message-1').text('Passwords do not match').addClass('show');
                cpasswordField.css('border', '1px solid lightcoral');
                showMessage();
                return;
            }

            // Check if email is already in use before sending OTP
            $.ajax({
                url: 'email-check.php', // Your PHP script to check the email
                method: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function (response) {
                    if (response.exists) {
                        $('#message-1').text('This email is already in use.').addClass('show');
                        emailField.css('border', '1px solid lightcoral');
                        showMessage(); // Call to handle fade out
                        return; // Stop further execution if email exists
                    }

                    // If email is not in use, proceed to send OTP
                    $.ajax({
                        url: 'send-otp.php',
                        method: 'POST',
                        data: {
                            email: email,
                            password: password
                        },
                        dataType: 'json', // Expect a JSON response
                        success: function (response) {
                            // Trigger the Bootstrap modal
                            $('#staticBackdrop').modal('show');
                            // Handle other response messages here
                        },
                        error: function () {
                            $('#message-1').text('Failed to send OTP. Please try again.').addClass('show'); // Handle error display
                            showMessage();
                        }
                    });
                },
                error: function () {
                    $('#message-1').text('Error checking email. Please try again.').addClass('show'); // Handle error display
                    showMessage();
                }
            });
        });

        // Function to handle message fade out
        function showMessage() {
            // Show message, then fade out after 5 seconds
            setTimeout(function () {
                $('#message-1').fadeOut(500, function () {
                    $('#message-1').text('').removeClass('show').show(); // Reset after fade out
                });
            }, 5000); // 5 seconds
        }

        // Function to start the OTP countdown
        function startOtpCountdown(linkElement) { // Use linkElement as the parameter
            $(linkElement).addClass('disabled'); // Add the disabled class to grey it out
            let countdownTime = 10; // Countdown time in seconds
            const countdownElement = document.getElementById('otpCountdown');

            // Show the countdown
            countdownElement.textContent = `(${countdownTime})`;

            const countdownInterval = setInterval(() => {
                countdownTime--;
                countdownElement.textContent = `(${countdownTime})`;

                // When countdown reaches 0
                if (countdownTime <= 0) {
                    clearInterval(countdownInterval);
                    countdownElement.textContent = ''; // Change text when countdown ends
                    $(linkElement).removeClass('disabled'); // Re-enable the link
                    $(linkElement).css('pointer-events', 'auto'); // Allow clicking again
                }
            }, 1000);

            // Disable clicking the link until countdown is finished
            $(linkElement).css('pointer-events', 'none');
        }

        // OTP verification handler
        $('#verifyOtpButton').on('click', function (e) {
            e.preventDefault();

            // Get the OTP input value
            let otp = $('input[name="Reg-OTP"]').val();

            let formData = {
                'Reg-OTP': otp  // The key must match the name attribute in your HTML form field
            };

            // Send OTP for verification
            $.post('verify-otp.php', formData, function (response) {
                console.log(response); // Log the response for debugging
                
                // Parse the JSON response
                var data = JSON.parse(response);
                
                // Check if the OTP verification was successful
                if (data.success) {
                    window.location.href = 'login.php'; // Change this to your desired URL
                } else {
                    // Show an error message
                    $('#message-otp').text(data.message).addClass('show');
                }
            }).fail(function () {
                // Handle the error when the request fails
                $('#message-otp').text('Failed to verify OTP. Please try again.').addClass('show');
            });
        });
    });

    </script>


    <script>
    // Show Password Toggle
        document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('floatingPassword');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('far', 'fa-eye'); // Remove line-type eye
            toggleIcon.classList.add('far', 'fa-eye-slash'); // Change to line-type eye-slash
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('far', 'fa-eye-slash'); // Remove line-type eye-slash
            toggleIcon.classList.add('far', 'fa-eye'); // Change back to line-type eye
        }
    });

    // Confirm Password Toggle
    document.getElementById('togglePassword2').addEventListener('click', function () {
        const passwordField2 = document.getElementById('floatingPassword2');
        const toggleIcon2 = document.getElementById('toggleIcon2');

        if (passwordField2.type === 'password') {
            passwordField2.type = 'text';
            toggleIcon2.classList.remove('far', 'fa-eye'); // Remove line-type eye
            toggleIcon2.classList.add('far', 'fa-eye-slash'); // Change to line-type eye-slash
        } else {
            passwordField2.type = 'password';
            toggleIcon2.classList.remove('far', 'fa-eye-slash'); // Remove line-type eye-slash
            toggleIcon2.classList.add('far', 'fa-eye'); // Change back to line-type eye
        }
    });
    </script>

    
    </body>
</html>