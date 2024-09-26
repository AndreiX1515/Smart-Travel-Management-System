<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('includes/head.php'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet">
  <link href="assets/css/client-login.css" rel="stylesheet">

  <style>
    .container-background {
      width: 100vw;
      height: 100vh;
      background: linear-gradient(
        rgba(0, 0, 0, 0.7), 
        rgba(0, 0, 0, 0.7)
      ), url('assets/images/hero-1.jpg') no-repeat center center !important;
      background-size: cover;
      background-attachment: fixed;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1;
    }

    .logo-container {
      position: absolute;
      top: -14%;
      left: 40%;
      width: 150px;
      height: 150px;
    }

  </style>
</head>


<body>
  <!-- Back to homepage button -->
  <a href="index.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> Back to Homepage
  </a>
  
  <div class="container-background"></div>

  <div class="container-fluid content-wrapper align-center">
    <div class="clear-background p-5">
      <div class="logo-container">
        <img src="assets\images\logo-tab.png" alt="">
      </div> 

      <form action="OTP-code.php" method="post">
        <!-- <div class="d-flex flex-row align-items-center justify-content-center">
          <p class="lead fw-normal mb-0 me-3">Sign in with</p>

          <button type="button" id="fb-circle" class="btn btn-primary btn-floating mx-1">
            <i class="fab fa-facebook-f"></i>
          </button>

          <button type="button" class="btn btn-warning btn-floating mx-1">
            <i class="fab fa-google"></i>
          </button>
        </div> -->

        <!-- <div class="divider d-flex align-items-center my-4">
          <p class="text-center fw-bold mx-3 mb-0">Or</p>
        </div> -->

        <!-- Email input -->
        <div class="form-outline mb-4 mt-3">
          <input type="email" id="form3Example3" name="email" class="form-control form-control-lg"
            placeholder="Enter a valid email address" style="color: white;" />
          <label class="form-label" for="form3Example3" id="loginfield">Email address</label>
        </div>

        <!-- Password input with toggle icon -->
        <div class="form-outline mb-3">
          <input type="password" id="form3Example4" name="pass" class="form-control form-control-lg"
            placeholder="Enter password" style="color: white;"/>
          <label class="form-label" for="form3Example4" id="loginfield">Password</label>
          <i class="fas fa-eye toggle-password" id="togglePassword"></i> <!-- Add toggle password icon -->
        </div>

        <div class="d-flex justify-content-between align-items-center">
          <div class="form-check mb-0">
            <!-- <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
            <label class="form-check-label" for="form2Example3">
              Remember me
            </label> -->
          </div>
          <a href="#!" id="fp-link" class="">Forgot password?</a>
        </div>

        <div class="text-center mt-4 pt-2">
          <button type="submit" class="btn btn-danger btn-lg" name="login"
            style="padding-left: 2.5rem; padding-right: 2.5rem; margin-bottom: 10px;">Login</button>
          <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="register.php"
              class="link-danger text-decoration-underline">Register</a></p>
        </div>
      </form>
    </div>
    <!-- <a href="admin-login.php" class="text-white-50 text-decoration-underline" style="position: absolute; bottom: 30px; right: 30px;">Admin Login</a> -->
  </div>
 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>

  <!-- Show/Hide Password Script -->
  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#form3Example4');

    togglePassword.addEventListener('click', function () {
      // Toggle the type attribute
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      
      // Toggle the icon
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>