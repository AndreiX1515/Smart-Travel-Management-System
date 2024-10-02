<?php
session_start();  // Start the session

include "conn.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    // $response = [];

    // // // Check if this is a resend request
    // // if (isset($_POST['resend']) && $_POST['resend'] == 'true') {
    // //     unset($_SESSION['otp']); // Unset the OTP only for resend requests
    // //     $response['message'] = 'OTP DELETED'; // This will be logged in the console
    // // }

    // Generate verification code
    $verificationCode = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

    // Store the OTP in the session
    $_SESSION['otp'] = $verificationCode;

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'no.repyltesting@gmail.com';  // Replace with your actual email
        $mail->Password = 'ufrf wclh fuqy zawp';  // Replace with your actual password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Fix for the From address error
        $mail->setFrom('no.repyltesting@gmail.com', 'Smart Travel');  // Set a valid email and name
        $mail->addAddress($email, "name");  // The recipient
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verificationCode . '</b></p>';

        // Send the email
        $mail->send();
        echo "OTP has been sent to your email.";
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


    // // Generate random 6-digit OTP
    // $otp = rand(100000, 999999);
    

    // // Store OTP in session
    // $_SESSION['otp'] = $otp;
    
    // // Simulate sending OTP (you can replace this with real email sending code)
    // $message = "Your OTP is: " . $otp;
    
    // echo "OTP sent successfully to $email. $message"; // For debugging purposes

?>


