<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>

    <?php include '../Client Section/Includes/head.php' ?>

    <link href="../Client Section/assets/css/sampleregister.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>

<body>
<!-- Back to homepage button -->
<!-- <a href="login.php" class="back-btn">
		<i class="fas fa-arrow-left"></i> Back to Login Page
</a> -->
		
<div class="container-background"> </div>

<div class="main-container">
    <!-- Login Form Section -->
    <div class="loginform">
        <div class="header-container">
            <h5 class="header h1 fw-bolder mb-2">Register</h5>
            <p class="h5 sub-header mb-0">The start of your journey with us.</p>
        </div>

        <div class="form-container">
            <!-- Registration Form -->
            <form class="" id="registerForm" method="POST" action="register.php">
                <div class="message-1 mb-2 fw-bold" id="message-1"></div>

                <div class="mb-2">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="floatingEmail" name="Reg-Email" placeholder="Email" required>
                        <label for="floatingEmail">Email <small class="text-danger"> *</small></label>
                    </div>
                    <div id="email-message" class="text-danger"></div>
                </div>

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

                <div class="bottom-register-div">
                    <button type="submit" class="btn btn-primary" id="SubmitRegButton">Register</button>
                    <span class="account-link">
                        Already have an account? <a href="login.php" class="login-link">Log in</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals -->

<!-- OTP Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">OTP Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="primary-text">
                <h2 class="otp-header">OTP Verification</h2>
                <p class="otp-subheader">Enter the code sent to your email to complete the verification process.</p>
                </div>

                <div class="otp-field-container">
                    <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingOtp"  maxlength="6" name="Reg-OTP" placeholder="Enter OTP">
                                    <label for="floatingOtp">OTP</label>
                    </div>
                    
                    <a href="#" id="sendOtpLink-modal">Send OTP <span id="otpCountdown"></span></a>
                </div>

                <div class="message-otp mt-2 fw-bold" id="message-otp"> </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="verifyOtpButton">Submit</button>
            </div>

        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
      </div>
      <div class="modal-body text-center">
        <div class="success-icon">
          <div class="circle"></div>
          <div class="checkmark"></div>
        </div>

        <div class="text-content">
					<h4>Successfully Registered</h4>
					<p>Congratulations! Your registration was successful. You can now access all the features and enjoy our services.</p>
			</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success w-100" id="okButton">Got it</button>
      </div>
    </div>
  </div>
</div>

<script>
	document.getElementById('floatingOtp').addEventListener('input', function(event) {
        let value = event.target.value;
        // Only allow numbers
        event.target.value = value.replace(/[^0-9]/g, '');
    });
</script>

<script>
    // Button element (ensure this exists in your HTML)
    const button = $('#SubmitRegButton');
    button.prop('disabled', true);

    emailField = $('#floatingEmail');
    passwordField = $('#floatingPassword');
    cpasswordField = $('#floatingPassword2');

    email = emailField.val();
    password = passwordField.val();
    cpassword = cpasswordField.val();

    // Flags to track the validation status
    let isEmailValid = false;
    let isPasswordValid = false;
    let isConfirmPasswordValid = false;

    // Function to validate email
    function validateEmail() {
        emailField = $('#floatingEmail');
        email = emailField.val(); // Get latest email value
        isEmailValid = false; // Reset flag

        // Clear previous messages and borders
        $('#message-1').text('').removeClass('show');
        emailField.css('border', '');

        // If email field is blank
        if (!email) {
            // $('#message-1')
            //     .text('Email is required.')
            //     .addClass('show');
            // emailField.css('border', '1px solid lightcoral');
            updateButtonState();
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            $('#message-1')
                .text('Please enter a valid email address.')
                .addClass('show');
            emailField.css('border', '1px solid lightcoral');
            updateButtonState();
            return;
        }

        // Check if email is already in use
        $.ajax({
            url: '../Client Section/Functions/email-check.php', // PHP script to check the email
            method: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function (response) {
                if (response.exists) {
                    $('#message-1')
                        .text('This email is already in use.')
                        .addClass('show');
                    emailField.css('border', '1px solid lightcoral');
                    isEmailValid = false;
                } else {
                    emailField.css('border', '');
                    isEmailValid = true;
                }
                updateButtonState(); // Update button state after the response
            },
            error: function () {
                $('#message-1')
                    .text('Error checking email. Please try again.')
                    .addClass('show');
                emailField.css('border', '1px solid lightcoral');
                isEmailValid = false;
                updateButtonState();
            }
        });
    }

    // // Trigger the function when the password field is focused
    // $('#floatingPassword').on('focus', function() {
    //     updateButtonState(); // Disable button when the password field is focused
    // });

    // Event listeners for validation
    $('#floatingEmail').on('blur', validateEmail);
    $('#floatingPassword').on('blur', validatePassword);
    $('#floatingPassword2').on('blur', validateConfirmPassword);

    // Function to validate password
    function validatePassword() {
        passwordField = $('#floatingPassword');
        password = passwordField.val(); // Get latest password value

        cpasswordField = $('#floatingPassword2');
        cpassword = cpasswordField.val(); // Get latest

        isPasswordValid = false; // Reset flag

        // Clear previous messages and borders
        $('#message-1').text('').removeClass('show');
        passwordField.css('border', '');

        // If password field is blank
        if (!password) {
            // $('#message-1')
            //     .text('Password is required.')
            //     .addClass('show');
            // passwordField.css('border', '1px solid lightcoral');
            updateButtonState();
            return;
        }

        // Password length validation
        if (password.length < 8) {
            $('#message-1')
                .text('Password must be at least 8 characters long.')
                .addClass('show');
            passwordField.css('border', '1px solid lightcoral');
            updateButtonState();
            return;
        }

        isPasswordValid = true;
        updateButtonState();
    }

    // Function to validate confirm password
    function validateConfirmPassword() {
        let passwordField = $('#floatingPassword');
        let password = passwordField.val(); // Get latest password value

        let cpasswordField = $('#floatingPassword2');
        let cpassword = cpasswordField.val(); // Get latest confirm password value
        isConfirmPasswordValid = false; // Reset flag

        // Clear previous messages and borders
        $('#message-1').text('').removeClass('show');
        cpasswordField.css('border', '');

        // If confirm password field is blank
        if (!cpassword) {
            // $('#message-1')
            //     .text('Confirm Password is required.')
            //     .addClass('show');
            // cpasswordField.css('border', '1px solid lightcoral');
            updateButtonState();
            return;
        }

        // Confirm password validation
        if (password !== cpassword) {
            $('#message-1')
                .text('Passwords do not match.')
                .addClass('show');
            cpasswordField.css('border', '1px solid lightcoral');
            updateButtonState();
            return;
        }

        isConfirmPasswordValid = true;
        updateButtonState();
    }


    // Final check to enable or disable the button
    function updateButtonState() {
        if (isEmailValid && isPasswordValid && isConfirmPasswordValid) {
            button.prop('disabled', false);
            console.log(email, password, cpassword);

        } else {
            button.prop('disabled', true);
            console.log(email, password, cpassword);
        }
    }
    
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

        // Add loading animation to the Register button
        var button = $('#SubmitRegButton');
                    button.html('Processing, Please Wait... <span class="loader"></span>'); // Add spinner
                    button.prop('disabled', true); // Disable button to prevent multiple clicks


        // Check if email is already in use before sending OTP
        $.ajax({
            url: '../Client Section/Functions/email-check.php', // Your PHP script to check the email
            method: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function (response) {
                if (response.exists) {
                    $('#message-1').text('This email is already in use.').addClass('show');
                    emailField.css('border', '1px solid lightcoral');
                    showMessage(); // Call to handle fade out
                    button.html('Register'); // Reset button text
                    button.prop('disabled', false); // Re-enable button
                    return; // Stop further execution if email exists
                }

                // If email is not in use, proceed to send OTP
                $.ajax({
                    url: '../Client Section/Functions/send-otp.php',
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

                        // Reset the button text and re-enable it once modal is shown
                        button.html('Register');
                        button.prop('disabled', false); // Re-enable button
                    },
                    error: function () {
                        $('#message-1').text('Failed to send OTP. Please try again.').addClass('show'); // Handle error display
                        showMessage();
                        button.html('Register'); // Reset button text
                        button.prop('disabled', false); // Re-enable button
                    }
                });
            },
            error: function () {
                $('#message-1').text('Error checking email. Please try again.').addClass('show'); // Handle error display
                showMessage();
                button.html('Register'); // Reset button text
                button.prop('disabled', false); // Re-enable button
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

			setTimeout(function () {
					$('#message-otp').fadeOut(500, function () {
					$('#message-otp').text('').removeClass('show').show(); // Reset after fade out
				});
			}, 5000); // 5 seconds
		}

    // Function to start the OTP countdown
    function startOtpCountdown(linkElement) {
			$(linkElement).addClass('link-dark disabled'); // Add dark class and disabled class to grey it out
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
							countdownElement.textContent = ''; // Clear text when countdown ends
							$(linkElement).removeClass('link-dark disabled'); // Re-enable the link and remove dark color
							$(linkElement).css('pointer-events', 'auto'); // Allow clicking again
					}
		}, 1000);

			// Disable clicking the link until countdown is finished
			$(linkElement).css('pointer-events', 'none');
    }

    // Event listener for sending OTP
    $('#sendOtpLink-modal').on('click', function (e) {
        e.preventDefault(); // Prevent default behavior

        // Start the OTP countdown immediately when the link is clicked
        startOtpCountdown(this); // Pass the link element

        $.ajax({
            url: '../Client Section/Functions/send-otp.php',
            method: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'json', // Expect a JSON response
            success: function (response) {
                $('#message-otp').text('Another OTP has been sent to your email!').addClass('show');
                showMessage();
            },
            error: function () {
                $('#message-otp').text('Failed to send OTP. Please try again.').addClass('show'); // Handle error display
                showMessage();
            }
        });
    });

		
    // OTP verification handler
    $('#verifyOtpButton').on('click', function (e) {
        e.preventDefault();

        // Get the OTP input value
        let otp = $('input[name="Reg-OTP"]').val();

        let formData = {
            'Reg-OTP': otp  // The key must match the name attribute in your HTML form field
        };

        // Send OTP for verification
        $.post('../Client Section/Functions/verify-otp.php', formData, function (response) {
            console.log(response); // Log the response for debugging
            
            // Parse the JSON response
            var data = JSON.parse(response);
            
            // Check if the OTP verification was successful
           // Assuming data.success indicates OTP verification success
					if (data.success) {
							// Show the success modal
							$('#successModal').modal('show'); 

							// Handle the OK button click
							$('#okButton').on('click', function() {
									window.location.href = 'login.php'; // Redirect to the login page
							});
					}
					else 
					{
                // Show an error message
                $('#message-otp').text(data.message).addClass('show');
            }
        }).fail(function () {
            // Handle the error when the request fails
            $('#message-otp').text('Failed to verify OTP. Please try again.').addClass('show');
        });
    });

</script>

<script>
    // // Handle form submission
    // document.getElementById('SubmitRegButton').addEventListener('click', function() {
    //     var button = this;
    //     var loader = document.createElement('span');
    //     loader.classList.add('loader');
        
    //     // Change the button text and add the loader
    //     button.innerHTML = 'Processing, Please Wait... <span class="loader"></span>';
    //     button.appendChild(loader);

    //     // Simulate an action (e.g., form submission, etc.)
    //     setTimeout(function() {
    //         // After action completes, trigger the modal to show
    //         var modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
    //         modal.show();

    //         // Hide the loader when the modal is shown
    //         button.innerHTML = 'Register';
    //         button.removeChild(loader);
    //     }, 3000); // Simulated action time (replace with actual logic)
    // });

    // // Remove loading spinner when modal is shown
    // document.getElementById('staticBackdrop').addEventListener('shown.bs.modal', function() {
    //     var button = document.getElementById('SubmitRegButton');
    //     var loader = button.querySelector('.loader');
        
    //     // Remove loader if it's still there
    //     if (loader) {
    //         button.innerHTML = 'Register'; // Reset button text
    //         button.removeChild(loader); // Remove the spinner
    //     }
    //     });

    // // Function to handle message fade out
    // function showMessage() {
    //     // Show message, then fade out after 5 seconds
    //     setTimeout(function () {
    //         $('#message-1').fadeOut(500, function () {
    //         $('#message-1').text('').removeClass('show').show(); // Reset after fade out
    //         });
    //     }, 5000); // 5 seconds

    //     setTimeout(function () {
    //         $('#message-otp').fadeOut(500, function () {
    //         $('#message-otp').text('').removeClass('show').show(); // Reset after fade out
    //         });
    //     }, 5000); // 5 seconds
    // }

    // // Function to start the OTP countdown
    // function startOtpCountdown(linkElement) {
    //     $(linkElement).addClass('link-dark disabled'); // Add dark class and disabled class to grey it out
    //     let countdownTime = 10; // Countdown time in seconds
    //     const countdownElement = document.getElementById('otpCountdown');

    //     // Show the countdown
    //     countdownElement.textContent = `(${countdownTime})`;

    //     const countdownInterval = setInterval(() => {
    //         countdownTime--;
    //         countdownElement.textContent = `(${countdownTime})`;

    //         // When countdown reaches 0
    //         if (countdownTime <= 0) {
    //             clearInterval(countdownInterval);
    //             countdownElement.textContent = ''; // Clear text when countdown ends
    //             $(linkElement).removeClass('link-dark disabled'); // Re-enable the link and remove dark color
    //             $(linkElement).css('pointer-events', 'auto'); // Allow clicking again
    //         }
    //     }, 1000);

    //     // Disable clicking the link until countdown is finished
    //     $(linkElement).css('pointer-events', 'none');
    // }


    
</script>

<?php include '../Client Section/Includes/scripts.php'; ?>

<!-- View Password Toggle -->
<script>
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