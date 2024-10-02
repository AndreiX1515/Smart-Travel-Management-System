<?php
session_start();  // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredOtp = $_POST['Reg-OTP'];

    // Check if the OTP matches the one stored in the session
    if (isset($_SESSION['otp'])) {
        $sessionOtp = $_SESSION['otp'];

        // Compare the entered OTP with the session OTP
        if ($enteredOtp == $sessionOtp) {
            echo "OTP verified successfully. Registration complete.";
        } else {
            echo "Invalid OTP. Please try again.";
        }
    } else {
        echo "OTP session not found. Please request a new OTP.";
    }
}

// <!-- // Insert into database using prepared statements -->
// <!-- $sql = "INSERT INTO account (email, password, otp, status) VALUES (?, ?, ?, '')";
//       $stmt = $conn->prepare($sql);
//       $stmt->bind_param('sss', $email, $pass, $verificationCode);
  
//       if ($stmt->execute()) 
//       {
//         echo ("Account Created");
//         exit();
//       } 
//       else 
//       {
//         echo "Database error: " . $stmt->error;
//       } -->


//       <!-- // Hash the password 
//       // $encryptedPassword = password_hash($pass, PASSWORD_DEFAULT); -->