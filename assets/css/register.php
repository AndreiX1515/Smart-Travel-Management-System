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

    <link href="assets\css\sampleregister.css" rel="stylesheet">

  <style>
    
  </style>
</head>


<body>
    <!-- Back to homepage button -->
    <a href="login.php" class="back-btn">
      <i class="fas fa-arrow-left"></i> Back to Login Page
    </a>
    
    <div class="container-background"> </div>
  
    <div class="main-container">
      <!-- Accent Image Section -->
      <div class="image-accent position-relative">
        <div class="logo-container text-left">
            <img src="assets/images/logo.png" alt="Logo">
        </div>
          <div class="bg-overlay"></div>
          <img src="assets\images\register-accent-image.jpg" alt="Accent Image" class="img-fluid">
      </div>
  
      <!-- Login Form Section -->
      <div class="loginform d-flex flex-column">
  
        <div class="header-container d-flex flex-column text-start my-4">
            <h5 class="header h2 fw-bolder mb-0">Register</h5> <!-- Removed margin-bottom -->
            <p class="h4 sub-header mb-0">The start of your journey with us.</p> <!-- Removed margin-bottom -->
        </div>
  
  
          <!-- Divider
          <div class="divider">
              <hr>
              <span>or</span>
              <hr>
          </div> -->
  
          <!-- Login Form -->
          <form class="mt-lg-5">
              <div class="mb-3">
                  <div class="form-floating">
                      <input type="text" class="form-control" id="floatingUsername" placeholder="Username">
                      <label for="floatingUsername">Username</label>
                  </div>
              </div>
          
              <div class="mb-3">
                  <div class="form-floating">
                      <input type="email" class="form-control" id="floatingEmail" placeholder="Email">
                      <label for="floatingEmail">Email</label>
                  </div>
              </div>
          
              <div class="mb-3 position-relative">
                  <div class="form-floating">
                      <input type="password" class="form-control" id="floatingPassword" placeholder="Password" aria-describedby="togglePassword">
                      <label for="floatingPassword">Password</label>
                      <span id="togglePassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                          <i class="far fa-eye" id="toggleIcon"></i> <!-- Line type icon for showing password -->
                      </span>
                  </div>
              </div>

              <div class="mb-3 position-relative">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" aria-describedby="togglePassword">
                        <label for="floatingPassword">Confirm Password</label>
                        <span id="togglePassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            <i class="far fa-eye" id="toggleIcon"></i> <!-- Line type icon for showing password -->
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="send-otp d-flex flex-row align-items-center">
                        <div class="form-floating me-3 flex-grow-1">
                            <input type="text" class="form-control" id="floatingUsername" placeholder="Enter OTP">
                            <label for="floatingUsername">OTP</label>
                        </div>
                        <!-- Send OTP link with countdown -->
                        <a href="#" id="sendOtpLink" class="">Send OTP <span id="otpCountdown"></span></a>
                    </div>
                </div>
                
                
                
          
              <!-- <div class="fp-container mb-3 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                      Uncomment if needed
                      <input type="checkbox" class="form-check-input" id="rememberMe">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                  </div>
                  <a href="#" class="">Forgot Password?</a>
              </div> -->
          
              <button type="submit" class="btn btn-primary w-100" id="LoginButton">Register</button>
          
              <!-- <div class="bottom-login-account mt-3 text-center">
                  <p class="mb-0">Have an account? <a href="#" class="text-decoration-none">Register Now</a></p>
              </div> -->
          </form>
      </div>
  </div>
  

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

        
        document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sendOtpLink').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default anchor behavior

            // Disable the link and start the countdown
            this.classList.add('disabled'); // Add the disabled class to grey it out
            let countdownTime = 60; // Countdown time in seconds
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
                    this.classList.remove('disabled'); // Re-enable the link
                    this.style.pointerEvents = 'auto'; // Allow clicking again
                }
            }, 1000);

            // Disable clicking the link until countdown is finished
            this.style.pointerEvents = 'none';
        });
    });






    </script>



    </body>
</html>