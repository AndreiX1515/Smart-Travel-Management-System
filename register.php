
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Smart Travel</title>

    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome Icon Kit CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">


    <!-- Favicons -->
    <link href="assets/images/rsz_logo-tab.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="assets/css/sampleregister.css" rel="stylesheet">

  <style>
    
  </style>
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
  
        <div class="header-container d-flex flex-column text-start my-4">
            <h5 class="header h2 fw-bolder mb-0">Register</h5> <!-- Removed margin-bottom -->
            <p class="h4 sub-header mb-0">The start of your journey with us.</p> <!-- Removed margin-bottom -->
        </div>
  
          <!-- Login Form -->
          <form class="mt-lg-5" id="registerForm">
            <div class="mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingUsername" name="Reg-Username" placeholder="Username">
                    <label for="floatingUsername">Username</label>
                </div>
            </div>

            <div class="mb-1">
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingEmail" name="Reg-Email" placeholder="Email" require>
                    <label for="floatingEmail">Email</label>
                </div>
            </div>

            <div id="message" style="color: lightcoral; font-size: 14px;"></div> <!-- Message display -->

            <div class="mt-3 mb-3 position-relative">
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="Reg-Password" placeholder="Password" aria-describedby="togglePassword">
                    <label for="floatingPassword">Password</label>
                    <span id="togglePassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);">
                        <i class="far fa-eye" id="toggleIcon"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3 position-relative">
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword2" name="Reg-CPassword" placeholder="Confirm Password" aria-describedby="togglePassword2">
                    <label for="floatingPassword2">Confirm Password</label>
                    <span id="togglePassword2" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);">
                        <i class="far fa-eye" id="toggleIcon2"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <div class="send-otp d-flex flex-row align-items-center">
                    <div class="form-floating me-3 flex-grow-1">
                        <input type="text" class="form-control" id="floatingOtp" name="Reg-OTP" placeholder="Enter OTP">
                        <label for="floatingOtp">OTP</label>
                    </div>
                    <!-- Send OTP link with countdown -->
                    <a href="#" id="sendOtpLink">Send OTP <span id="otpCountdown"></span></a>
                </div>
            </div>


            <button type="submit" class="btn btn-primary w-100" id="verifyOtpButton">Verify OTP</button>

            <div class="message-1" id="message" style="position: flex; justify-content:center;"></div>
        </form>

      </div>
  </div>
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
            $(document).ready(function() {
                $('#sendOtpLink').on('click', function(e) {
                    e.preventDefault(); // Prevent the default anchor behavior
                    let emailField = $('#floatingEmail');
                    let email = emailField.val();
                    console.log(email);

                    // Clear any previous message
                    $('#message').text('');

                    // Email format validation
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple email regex

                    // Check if email is empty
                    if (email === '') {
                        $('#message').text('Enter email first.'); // Show message below the email field
                        emailField.css('border', '1px solid lightcoral'); // Change border to light red
                        return; // Stop further execution

                    } else if (!emailRegex.test(email)) {
                        $('#message').text('Please enter a valid email address.'); // Show message for invalid email
                        emailField.css('border', '1px solid lightred'); // Change border to light red
                        return; // Stop further execution

                    } else {
                        emailField.css('border', ''); // Reset border color if email is valid
                    }


                    $.ajax({
                        url: 'send-otp.php',
                        method: 'POST',
                        data: { email: email },
                        success: function(response) {
                            console.log('Sent! Check Email');
                            startOtpCountdown('#sendOtpLink'); // Pass the selector as a string
                        },
                        error: function() {
                            console.log('Failed to send OTP');
                        }
                    });
                });

            // Function to start the OTP countdown
                function startOtpCountdown(linkElement) { // Use linkElement as the parameter
                    // Disable the link and grey it out
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
                            countdownElement.textContent = '(Resend OTP)'; // Change text when countdown ends
                            $(linkElement).removeClass('disabled'); // Re-enable the link
                            $(linkElement).css('pointer-events', 'auto'); // Allow clicking again
                        }
                    }, 1000);

                    // Disable clicking the link until countdown is finished
                    $(linkElement).css('pointer-events', 'none');
                }


        // OTP Verification on form submit
        $('#verifyOtpButton').on('click', function(e) {
            e.preventDefault();

            // Get the OTP input value
            let otp = $('input[name="Reg-OTP"]').val();

            let formData = {
                'Reg-OTP': otp  // The key must match the name attribute in your HTML form field
            };

            // Send OTP for verification
            $.post('verify-otp.php', formData, function(response) {
                $('#message-1').text(response);  // Show OTP verification response
            });
        });
    });

    </script>

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