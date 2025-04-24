<?php
require "../conn.php"; // Move up to the parent directory

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['flightid'])) {
    $_SESSION['flightid'] = htmlspecialchars($_POST['flightid']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <meta charset="UTF-8">

    <?php include '../Agent Section/includes/head.php' ?>

    <link href="../Mobile/assets/css/agent-login.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>

<body>
<!-- Back to homepage button -->
<a href="../Mobile/flightsched.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> <span> Back to Flight Schedules </span> 
  </a>

    <main class="main-container">
        <div class="login-container">
            <div class="logo">
                <img src="../Assets/Logos/logo-tab.png" alt="" class="logo-image" width="160" height="120">
            </div>

            <div class="fields-container">
                <form class="mt-3" id="loginForm" method="POST">

                    <input type="hidden" name="flightid" id="flightid" value="<?= isset($_SESSION['flightid']) ? $_SESSION['flightid'] : '' ?>">

                    <!-- Username input field -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control border-1" id="floatingUsername" name="username" placeholder="Username" required>
                            <label for="floatingUsername">Enter User ID </label>
                        </div>
                    </div>

                    <!-- Password input field -->
                    <div class="mb-1 position-relative">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" aria-describedby="togglePassword" required>
                            <label for="floatingPassword">Password</label>
                            <span id="togglePassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                <i class="far fa-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Forgot password and Remember me options -->
                    <div class="fp-container">
                        <a href="#" class="forgot-password">Forgot your password?</a>
                        <div class="fp-flag">Please contact the admin for assistance.</div>
                    </div>

                    <!-- Placeholder for login messages -->
                    <div id="message-login" class="message-login"></div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 p-3" id="LoginButton" name="login">LOGIN</button>
                </form>
            </div>
        </div>
    </main>
</body>

<?php include "../Agent Section/includes/scripts.php"; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const forgotPassword = document.querySelector(".forgot-password");
        const flag = document.querySelector(".fp-flag");

        forgotPassword.addEventListener("click", function(event) {
            event.preventDefault(); // Prevents default link behavior
            event.stopPropagation(); // Prevents immediate closing on click
            flag.style.display = flag.style.display === "block" ? "none" : "block";
        });

        document.addEventListener("click", function(event) {
            if (!forgotPassword.contains(event.target)) {
                flag.style.display = "none"; // Hide when clicking outside
            }
        });
    });
</script>

<script>
    function showLoginMessage(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

        // Set the message HTML
        $('#message-login').html(`
            <div class="alert ${alertClass} text-center" id="autoDismissMessage">${message}</div>
        `);

        // Automatically remove the message after 5 seconds
        setTimeout(() => {
            $('#autoDismissMessage').fadeOut(400, function () {
                $(this).remove();
            });
        }, 5000);
    }


    $(document).ready(function () {
        $('#loginForm').on('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            // Clear previous messages
            $('#message-login').html('');
            const formData = new FormData(this);
            formData.append('login', '1'); // Add login field to indicate form submission

            // Perform AJAX request
            $.ajax({
                url: '../Mobile/function/agentLogin-code.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json', // Expecting JSON response
                success: function (data) {
                    if (data.success) {
                        showLoginMessage(data.message || 'Login successful!', 'success');

                        setTimeout(() => {
                            const flightid = document.getElementById('flightid')?.value || '';

                            if (data.accountType === 'agent') {

                                if (flightid) {
                                    window.location.href = '../Mobile/agent-addBooking-flight.php';
                                } else {
                                    alert('No flight selected. Redirecting to flight schedule.');
                                    window.location.href = '../Mobile/flightsched.php';
                                }

                            } else if (data.accountType === 'guest') {

                                if (flightid) {
                                    alert('Redirected to Client Booking');
                                    window.location.href = `../Mobile/client-addBooking-flight.php`;
                                } else {
                                    alert('No flight selected. Redirecting to flight schedule.');
                                    window.location.href = '../Mobile/flightsched.php';
                                }
                                
                            } else {
                                alert('Unknown account type. Please contact support.');
                            }
                        }, 1500); // Delay before proceeding
                    }

                    
                    else {
                        
                        showLoginMessage(data.message || 'Login failed. Please try again.', 'error');

                        if (data.message?.trim() === "You are logged in on another device. Please close from other tab or devices then reload before logging in again!") {
                            console.log("Disabling login button for 'Logged in on another device.'");
                            $('#LoginButton').addClass('button-disabled');
                        }
                    }
                },

                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    showLoginMessage('An error occurred. Please try again later.', 'error');
                    $('#LoginButton').addClass('button-disabled');
                }

            });
        });
    });
</script>


<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
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