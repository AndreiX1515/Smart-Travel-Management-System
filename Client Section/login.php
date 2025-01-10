<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>

    <?php include '../Client Section/Includes/head.php' ?>

    <!-- External CSS -->
    <link href="../Client Section/assets/css/samplelogin.css?v=<?php echo time(); ?>" rel="stylesheet">

    <style>
        
    </style>
</head>

<body>
  <!-- Back to homepage button -->
  <a href="index.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> Back to Home Page
  </a>
  
  <div class="dark-overlay"></div>

  <div class="container-background"> </div>

<div class="loginform d-flex flex-column">
    <div class="logo-container text-left">
        <img src="../Assets/Logos/SMART LOGO 2 (2).png" alt="Logo">
    </div>

    <div class="header-container d-flex flex-column text-start mt-1">
        <h6 class="header h4 fw-bolder">Experience Travel with Us.</h6>
        <p class="h6 sub-header">Discover new horizons and create unforgettable memories with our curated travel experiences tailored just for you.</p>
    </div>

    <!-- Login Form -->
    <form class="mt-5" id="loginForm">
        <!-- Email input field -->
        <div class="mb-3">
            <div class="form-floating">
                <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="Email" required>
                <label for="floatingEmail">Email</label>
            </div>
        </div>

        <!-- Password input field -->
        <div class="mb-1 position-relative">
                <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                        <span id="togglePassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                <i class="far fa-eye" id="toggleIcon"></i>
                        </span>
                </div>
        </div>

        <div class="fp-container mb-1 d-flex justify-content-end align-items-center mt-2">
            <a href="#" class="">Forgot Password?</a>
        </div>

        <button type="submit" class="loginbtn btn btn-primary w-100" id="LoginButton">Login</button>

        <div class="bottom-login-account mt-3 text-center">
            <p class="mb-0">Don't have an account? <a href="../Client Section/register.php" class="text-decoration-none">Register Now</a></p>
        </div>

        <div id="message-login" class="message-login mt-3 h6 fw-light fs-6" style="font-size: 8px;"></div>
    </form>
</div>




    <script>
       const LoginButton = document.getElementById('LoginButton'); // Ensure this matches the button ID
    
    document.getElementById('loginForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission
    
        // Clear previous messages
        document.getElementById('message-login').innerHTML = '';
    
        // Create FormData object to gather the form data
        const formData = new FormData(this);
    
        // Log form data to the console for debugging
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`); // Log each field for debugging
        }
    
        // Perform AJAX request
        fetch('login-process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Log the full response object for debugging
            console.log('Response:', response);
    
            // Ensure response is OK, if not throw a response error
            if (!response.ok) {
                throw new Error(`Network response was not ok. Status: ${response.status}`);
            }
    
            return response.json(); // Parse JSON response
        })
        .catch(error => {
            // Handle errors from the fetch or JSON parsing
            console.error('Fetch Error:', error);
            document.getElementById('message-login').innerHTML = `An error occurred: ${error.message}`;
        })
        .then(data => {
            console.log('Data:', data);  // Log the data to verify its content
    
            if (data.success) {
                // Redirect to dashboard or homepage
                window.location.href = '../Client Section/index.php';
            } else if (data.message && data.message.trim() === "User not found.") {
                // Show specific message for user not found
                document.getElementById('message-login').innerHTML = '<div class="alert alert-danger text-center">' + data.message + '</div>';
            } else if (data.message && data.message.trim() === "Invalid email or password.") {
                // Show specific message for invalid credentials
                document.getElementById('message-login').innerHTML = '<div class="alert alert-danger text-center">' + data.message + '</div>';
            } else if (data.message && data.message.trim() === "Your account is inactive. Please contact support.") {
                // Show specific message for inactive account
                document.getElementById('message-login').innerHTML = '<div class="alert alert-warning text-center">' + data.message + '</div>';
            } else {
                // Fallback for unexpected responses
                document.getElementById('message-login').innerHTML = '<div class="alert alert-danger text-center">An unexpected error occurred. Please try again.</div>';
            }
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
    </script>

   <?php include '../Client Section/Includes/scripts.php' ?>
    
    </body>
</html>