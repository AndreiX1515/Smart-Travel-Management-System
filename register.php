<?php session_start(); ?>

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

    <link href="assets/css/sampleregister.css?v=<?php echo time(); ?>" rel="stylesheet">

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
  
            <div class="header-container d-flex flex-column text-start mt-3 mb-3">
                <h5 class="header h2 fw-bolder mb-0">Register</h5> <!-- Removed margin-bottom -->
                <p class="h6 sub-header mb-0">The start of your journey with us.</p>
            </div>
  

        
            <!-- Registration Form -->
            <form class="mt-lg-3" id="registerForm" method="POST" action="register.php">
            <div class="message-1" id="message-1"> </div>
                <div class="mb-3">
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
                </div>

                <div class="mb-2">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="floatingEmail" name="Reg-Email" placeholder="Email" required>
                        <label for="floatingEmail">Email <small class="text-danger"> *</small></label>
                    </div>
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

                <div class="message mt-1 mb-2 d-flex justify-content-start text-danger" id="message-confirmp"></div>

                <div class="mb-3">
                    <div class="send-otp d-flex flex-row align-items-center">
                        <div class="form-floating me-3 flex-grow-1">
                            <input type="text" class="form-control" id="floatingOtp" name="Reg-OTP" placeholder="Enter OTP">
                            <label for="floatingOtp">OTP</label>
                        </div>
                        <!-- Send OTP link with countdown -->
                        <a href="#" id="sendOtpLink" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Send OTP <span id="otpCountdown"></span></a>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100" id="verifyOtpButton">Verify OTP</button>
            </form>
      </div>
  </div>
  
  <!-- Modals -->

   
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="staticBackdropLabel">Enter the Confirmation Code sent to your Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

        <div class="modal-body p-4">
            <div class="primary-text mb-4">
                 Let us know this email belongs to you. Enter the code in the email sent to <span class="fw-bold"> ********@gmail.com. </span>
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

            </div>
            
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit Contact Info</button>
            <button type="button" class="btn btn-primary">Submit</button>
        </div>

        </div>
    </div>
</div>



    <!-- Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <!-- Main Bootstrap JS CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
            $(document).ready(function () {
                $('#sendOtpLink').on('click', function (e) {
                    e.preventDefault(); // Prevent the default anchor behavior
                    let emailField = $('#floatingEmail');
                    let usernameField = $('#floatingUsername');
                    let passwordField = $('#floatingPassword');
                    let cpasswordField = $('#floatingPassword2');
                    let fnameField = $('#floatingFirstName');
                    let lnameField = $('#floatingLastName');
                    let mnameField = $('#floatingMiddleName');
                    let email = emailField.val();
                    let username = usernameField.val();
                    let password = passwordField.val();
                    let cpassword = cpasswordField.val();
                    let fname = fnameField.val();
                    let lname = lnameField.val();
                    let mname = mnameField.val();
                    console.log(email);

                    // Clear any previous message
                    $('#message-1').text('');

                    // Email format validation
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple email regex

                    // Check if email is empty
                    if (email === '') {
                        $('#message-1').text('Fields are empty'); // Show message below the email field
                        emailField.css('border', '1px solid lightcoral'); // Change border to light red
                        usernameField.css('border', '1px solid lightcoral');
                        passwordField.css('border', '1px solid lightcoral');
                        cpasswordField.css('border', '1px solid lightcoral');
                        fnameField.css('border', '1px solid lightcoral');
                        lnameField.css('border', '1px solid lightcoral');
                        mnameField.css('border', '1px solid lightcoral');
                        return; // Stop further execution
                    } else if (!emailRegex.test(email)) {
                        $('#message-1').text('Please enter a valid email address.'); // Show message for invalid email
                        emailField.css('border', '1px solid lightred'); // Change border to light red
                        return; // Stop further execution
                    } else {
                        emailField.css('border', ''); // Reset border color if email is valid
                    }

                    // Gather form data
                    const firstName = $('#floatingFirstName').val();
                    const lastName = $('#floatingLastName').val();
                    const middleName = $('#floatingMiddleName').val();
                    const emails = $('#floatingEmail').val();
                    const passwords = $('#floatingPassword').val();

                    $.ajax({
                        url: 'send-otp.php',
                        method: 'POST',
                        data: {
                            firstName: firstName,
                            lastName: lastName,
                            middleName: middleName,
                            email: emails,
                            password: passwords
                        },
                        dataType: 'json', // Expect a JSON response
                        success: function (response) {
                            console.log(response.message); // Log the message from the server
                            $('#message-1').text(response.message).addClass('show'); // Display the message in #message-1

                            // Automatically hide the message after 5 seconds
                            setTimeout(function () {
                                $('#message-1').css('opacity', 0); // Start fading out
                                $('#message-1').removeClass('show'); // Remove show class to hide
                            }, 5000);

                            // Reset the opacity back to 1 when shown again
                            setTimeout(function () {
                                $('#message-1').css('opacity', 1); // Reset opacity for the next message
                            }, 0);
                        },
                        error: function () {
                            console.log('Failed to send OTP');
                            $('#message-1').text('Failed to send OTP. Please try again.').addClass('show'); // Handle error display
                        }
                    });

                });

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
                            // Redirect to the desired page after successful registration
                            // window.location.href = 'login.php'; // Change this to your desired URL
                        } else {
                            // Show an error message
                            $('#message-1').text(data.message).addClass('show');
                        }
                    }).fail(function () {
                        // Handle the error when the request fails
                        $('#message-1').text('Failed to verify OTP. Please try again.').addClass('show');
                    });
                });

            });
    </script>

    <script>
            // Email Validation on Blur
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("floatingEmail").addEventListener("blur", function () {
                const email = this.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const messageEl = document.getElementById("message-email");

                if (email === "") {
                    messageEl.textContent = "Email is required.";
                    this.classList.add('is-invalid'); // Add red border class
                } else if (!emailRegex.test(email)) {
                    messageEl.textContent = "Invalid email format.";
                    this.classList.add('is-invalid'); // Add red border class
                } else {
                    messageEl.textContent = ""; // Clear error message if valid
                    this.classList.remove('is-invalid'); // Remove red border class
                }
            });

        // Function to display message and handle invalid state
        function displayValidationMessage(element, message, isValid) {
            const messageEl = document.getElementById("message-confirmp");
            messageEl.textContent = message; // Update message
            if (isValid) {
                element.style.border = ''; // Reset border if valid
                messageEl.classList.remove('text-danger'); // Remove red text
            } else {
                element.style.border = '1px solid lightcoral'; // Add red border if invalid
                messageEl.classList.add('text-danger'); // Add red text for invalid message
            }
        }

        // Password Validation on Blur
        document.getElementById("floatingPassword").addEventListener("blur", function () {
            const password = this.value;
            const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (password === "") {
                displayValidationMessage(this, "Password is required.", false);
            } else if (!passwordRegex.test(password)) {
                displayValidationMessage(this, "Password must be at least 8 characters, with upper and lowercase letters, numbers, and special characters.", false);
            } else {
                displayValidationMessage(this, "", true); // Clear message if valid
            }
        });

        // Confirm Password Validation on Blur
        document.getElementById("floatingPassword2").addEventListener("blur", function () {
            const confirmPassword = this.value;
            const password = document.getElementById("floatingPassword").value;

            if (confirmPassword === "") {
                displayValidationMessage(this, "Confirm Password is required.", false);
            } else if (confirmPassword !== password) {
                displayValidationMessage(this, "Passwords do not match.", false);
            } else {
                displayValidationMessage(this, "", true); // Clear message if valid
            }
        });

        // Name Validation Function
        function validateName(inputElement, isRequired = false) {
            const name = inputElement.value.trim();
            const nameRegex = /^[A-Za-zñÑáéíóúÁÉÍÓÚ ]+$/; // Accepts alphabetical characters and spaces, including ñ

            if (isRequired && name === "") {
                inputElement.classList.add('is-invalid'); // Add red border class for invalid
                inputElement.classList.remove('is-valid'); // Remove valid class if invalid
            } else if (!nameRegex.test(name)) {
                inputElement.classList.add('is-invalid'); // Add red border class for invalid
                inputElement.classList.remove('is-valid'); // Remove valid class if invalid
            } else {
                inputElement.classList.remove('is-invalid'); // Remove red border class
                inputElement.classList.add('is-valid'); // Add valid class
            }
        }

        // First Name Validation on Blur
        document.getElementById("floatingFirstName").addEventListener("blur", function () {
            validateName(this, true);
        });

        // Middle Name Validation on Blur
        document.getElementById("floatingMiddleName").addEventListener("blur", function () {
            validateName(this);
        });

        // Last Name Validation on Blur
        document.getElementById("floatingLastName").addEventListener("blur", function () {
            validateName(this, true);
        });

        // OTP Validation on Blur
        document.getElementById("floatingOtp").addEventListener("blur", function () {
            const otp = this.value.trim();
            const otpRegex = /^\d{4,6}$/;
            const messageEl = document.getElementById("message-1");

            if (otp === "") {
                messageEl.textContent = "OTP is required.";
            } else if (!otpRegex.test(otp)) {
                messageEl.textContent = "OTP must be 4-6 digits.";
            } else {
                messageEl.textContent = ""; // Clear error message if valid
            }
        });
    });

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