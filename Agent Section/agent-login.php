<?php
    require "../conn.php"; // Move up to the parent directory

    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <meta charset="UTF-8">
    

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome Icon Kit CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <link href="..\Agent Section\assets\css\agent-login.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>

    <main class="main-container">
        <div class="login-container">
            <div class="logo mt-5 mb-5">
                <img src="..\assets\images\logo-tab.png" alt="" class="logo-image" width="160" height="120">
            </div>
            <form class="mt-5" id="loginForm" action="agentLogin-code.php" method="POST">
                <!-- Username input field -->
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control border-1" id="floatingUsername" name="username" placeholder="Username" required>
                        <label for="floatingUsername">Username</label>
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
                <div class="fp-container mt-3 mb-5 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input me-2 mb-1" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label> 
                    </div>
                    <a href="#" class="">Forgot Password?</a>
                </div>

                <!-- Placeholder for login messages -->
                <div id="message-login" class="message-login mt-3"></div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 p-3 mt-5" id="LoginButton" name="login">LOGIN</button>
            </form>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

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
