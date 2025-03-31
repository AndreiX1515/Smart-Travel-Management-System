<?php
require_once "../conn.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get user type from session, default to 'user'
$userType = $_SESSION['userType'] ?? 'user';

// Retrieve session values based on user type
$email = $accountId = '';

if ($userType === 'agent') {
    $email = $_SESSION['agent_email'] ?? '';
    $accountId = $_SESSION['agent_accountId'] ?? '';

} elseif ($userType === 'client') {
    $email = $_SESSION['client_email'] ?? '';
    $accountId = $_SESSION['client_accountId'] ?? '';
} else {
    echo "<script>console.warn('Unknown user type detected');</script>";
}

// Debugging: Log session values in the console safely
echo "<script>
    console.log('User Type:', '" . htmlspecialchars($userType) . "');
    console.log('Email:', '" . htmlspecialchars($email) . "');
    console.log('Account ID:', '" . htmlspecialchars($accountId) . "');
</script>";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../User/assets/user-changePassword.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="main-container">
        <div class="login-container">
            <div class="container-header">
                <!-- Logo Section -->
                <div class="logo">
                    <img src="../Assets/Logos/logo-tab.png" alt="Company Logo" width="60">
                </div>

                <!-- Header Section -->
                <div class="login-header">
                    <h2>Provide New Password</h2>
                    <p>The system detects a new account. Provide a new password to proceed.</p>
                </div>
            </div>

            <!-- Form Section -->
            <div class="login-form-wrapper">
                <form id="resetForm">
                    <div class="login-form-fields">
                        <div class="form-inputs">
                            <label for="current-password" class="input-label">Current Password</label>
                            <input type="password" id="current-password" name="current_password" class="input-field" placeholder="Enter current password" required>
                        </div>
                        
                        <div class="form-inputs">
                            <label for="new-password" class="input-label">New Password</label>
                            <input type="password" id="new-password" name="new_password" class="input-field" placeholder="Enter new password" required>
                        </div>

                        <div class="form-inputs">
                            <label for="confirm-password" class="input-label">Confirm New Password</label>
                            <input type="password" id="confirm-password" name="confirm_password" class="input-field" placeholder="Confirm new password" required>
                        </div>
                    </div>

                    <!-- Button Group -->
                    <div class="button-group">
                        <button type="button" class="secondary" onclick="goBack()">Back to sign in</button>
                        <button type="submit" class="primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        $(document).ready(function () {
            $("#resetForm").submit(function (e) {
                e.preventDefault(); // Prevent default form submission

                let currentPassword = $("#current-password").val();
                let newPassword = $("#new-password").val();
                let confirmPassword = $("#confirm-password").val();

                if (newPassword !== confirmPassword) {
                    alert("New passwords do not match!");
                    return;
                }

                $.ajax({
                    url: "../User/functions/changepassword.php",
                    type: "POST",
                    data: {
                        current_password: currentPassword,
                        new_password: newPassword
                    },
                    success: function (response) {
                        let jsonResponse;
                        try {
                            jsonResponse = JSON.parse(response.trim());
                        } catch (e) {
                            alert("Unexpected server response.");
                            return;
                        }

                        if (jsonResponse.status === "error") {
                            alert(jsonResponse.message);
                        } else if (jsonResponse.status === "success") {
                            alert("Password updated successfully. Redirecting to Login page...");
                            setTimeout(() => {
                                window.location.href = "../Agent Section/agentLogin.php";
                            }, 3000);
                        }
                    },
                    error: function () {
                        alert("An error occurred. Please try again.");
                        window.location.href = "../Agent Section/agentLogin.php";
                    }
                });
            });
        });
    </script>
</body>
</html>
