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



    <!-- Font Awesome Icon Kit CDN
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> -->

    <!-- Favicons -->
    <link href="assets/images/rsz_logo-tab.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="assets/css/samplelogin.css" rel="stylesheet">

  <style>
    
  </style>
</head>


<body>
  <!-- Back to homepage button -->
  <a href="index.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> Back to Home Page
  </a>
  
  <div class="container-background"> </div>

  <div class="main-container">
    <!-- Login Form Section -->
    <div class="loginform d-flex flex-column">
        <div class="logo-container text-left">
            <img src="assets/images/logo.png" alt="Logo">
        </div>

        <div class="header-container d-flex flex-column text-start mt-1">
            <h6 class="header h4 fw-bolder">Experience Travel with Us.</h6>
            <p class="h6 sub-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>

        <!-- <div class="other-options mt-3">
            <span class="text-secondary my-2">Other options: </span>
        </div> -->

        <!-- Social Icons -->
        <div class="social-icons">
            <a href="#" class="facebook" style="background-color: #1877F2;"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="google" style="background-color: #FFF; color: #ff0800;"><i class="fab fa-google"></i></a>
            <a href="#" class="twitter" style="background-color: #000; color: #FFF;"><i class="fa-brands fa-x-twitter"></i></a>
        </div>

        <!-- Divider -->
        <div class="divider">
            <hr>
            <span>or</span>
            <hr>
        </div>

        <!-- Login Form -->
        <form class="mt-0">
            <div class="mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control border-1" id="floatingUsername" placeholder="Username">
                    <label for="floatingUsername">Username</label>
                </div>
            </div>
        
            <div class="mb-3">
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingEmail" placeholder="Email">
                    <label for="floatingEmail">Email</label>
                </div>
            </div>
        
            <div class="mb-1 position-relative">
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password" aria-describedby="togglePassword">
                    <label for="floatingPassword">Password</label>
                    <span id="togglePassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i class="far fa-eye" id="toggleIcon"></i> <!-- Line type icon for showing password -->
                    </span>
                </div>
            </div>
        
            <div class="fp-container mb-1 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <!-- Uncomment if needed
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                    -->
                </div>
                <a href="#" class="">Forgot Password?</a>
            </div>
        
            <button type="submit" class="btn btn-primary w-100" id="LoginButton">Login</button>
        
            <div class="bottom-login-account mt-3 text-center">
                <p class="mb-0">Have an account? <a href="register.php" class="text-decoration-none">Register Now</a></p>
            </div>
        </form>
        

    </div>

    <!-- Accent Image Section -->
    <div class="image-accent position-relative">
        <div class="bg-overlay"></div>
        <img src="assets/images/login-accent-image.JPG" alt="Accent Image" class="img-fluid">
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
    </script>



    </body>
</html>