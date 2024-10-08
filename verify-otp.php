<?php 
session_start(); // Start the session
require 'conn.php'; // Your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Common function to output JSON response
    function jsonResponse($success, $message) {
        echo json_encode(['success' => $success, 'message' => $message]);
        exit;
    }

    // Check if OTP is being verified
    if (isset($_POST['Reg-OTP'])) {
        $enteredOtp = $_POST['Reg-OTP'];

        // Validate OTP
        if (!isset($_SESSION['otp'])) {
            jsonResponse(false, 'OTP session not found. Please request a new OTP.');
        }

        if ($enteredOtp !== $_SESSION['otp']) {
            jsonResponse(false, 'Invalid OTP. Please try again.');
        }

        // OTP verified successfully, now register the user
        $firstName = $_SESSION['firstName'] ?? '';
        $lastName = $_SESSION['lastName'] ?? '';
        $middleName = $_SESSION['middleName'] ?? '';
        $email = $_SESSION['email'] ?? '';
        $password = mb_convert_encoding(trim($_SESSION['password'] ?? ''), 'UTF-8');

        // Hash the password using MD5
        $hashedPassword = md5($password); 

        // Additional fields
        $account_status = 'active'; // Default to active
        $otp = $_SESSION['otp']; // Store the OTP in the database
        $created_at = date('Y-m-d H:i:s'); // Current timestamp

        // Insert data into the database using a prepared statement
        $stmt = $conn->prepare("INSERT INTO accounts (first_name, last_name, middle_name, email, upassword, account_status, otp, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $firstName, $lastName, $middleName, $email, $hashedPassword, $account_status, $otp, $created_at);

        if ($stmt->execute()) {
            // Clear session variables after successful registration
            unset($_SESSION['Reg-FirstName'], $_SESSION['Reg-LastName'], $_SESSION['Reg-MiddleName'], $_SESSION['Reg-Email'], $_SESSION['Reg-Password'], $_SESSION['otp']);
            jsonResponse(true, 'Registration successful!' . $hashedPassword);
        } else {
            jsonResponse(false, "Error: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
        
    } else {
        // Registration data submission
        $firstName = $_POST['Reg-FirstName'] ?? '';
        $lastName = $_POST['Reg-LastName'] ?? '';
        $middleName = $_POST['Reg-MiddleName'] ?? '';
        $email = $_POST['Reg-Email'] ?? '';
        $password = $_POST['Reg-Password'] ?? '';

        // Validation: Check if fields are not empty
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            jsonResponse(false, 'Please fill in all required fields.');
        }

        // Validation: Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            jsonResponse(false, 'Please enter a valid email address.');
        }

        // Store registration data in session for later use (after OTP verification)
        $_SESSION['Reg-FirstName'] = $firstName;
        $_SESSION['Reg-LastName'] = $lastName;
        $_SESSION['Reg-MiddleName'] = $middleName;
        $_SESSION['Reg-Email'] = $email;
        $_SESSION['Reg-Password'] = $password;

        // Generate and store OTP
        $_SESSION['otp'] = generateVerificationCode();

        // Placeholder: Print OTP for demonstration (remove this in production)
        echo jsonResponse(true, 'OTP sent to your email. Your OTP is: ' . $_SESSION['otp']);
    }
}

// Function to generate a 6-digit OTP
function generateVerificationCode() {
    return substr(number_format(time() * rand(), 0, '', ''), 0, 6);
}
?>
