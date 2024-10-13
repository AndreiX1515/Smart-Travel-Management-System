<?php
session_start(); // Start the session at the beginning
include "conn.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$response = []; // Initialize response array to store messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form fields from the POST request
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $middleName = isset($_POST['middleName']) ? $_POST['middleName'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Store the values in session variables
    $_SESSION['firstName'] = $firstName;
    $_SESSION['lastName'] = $lastName;
    $_SESSION['middleName'] = $middleName;
    $_SESSION['email'] = $email; // Email is directly assigned from POST
    $_SESSION['password'] = $password; // Store hashed password, not the plain password
    $_SESSION['otp'] = ""; // Initialize OTP session variable

    // Function to generate a verification code
    function generateVerificationCode() {
        return substr(number_format(time() * rand(), 0, '', ''), 0, 6);
    }

    // Check if the OTP is already set
    if (empty($_SESSION['otp'])) {
        // Generate new verification code
        $verificationCode = generateVerificationCode();
        $_SESSION['otp'] = $verificationCode;
        $response['message'] = "Generated new OTP: $verificationCode"; // Store the new OTP generation message
    } else {
        // Unset the existing OTP and generate a new one
        unset($_SESSION['otp']);
        $verificationCode = generateVerificationCode();
        $_SESSION['otp'] = $verificationCode;
        $response['message'] = "Previous OTP cleared. Generated new OTP: $verificationCode"; // Store OTP reset message
    }

    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->SMTPDebug = 0; // Set to 2 for detailed debug output
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'no.repyltesting@gmail.com'; // Replace with your actual email
        $mail->Password = 'ufrf wclh fuqy zawp'; // Replace with your actual password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set sender and recipient
        $mail->setFrom('no.repyltesting@gmail.com', 'Smart Travel'); // Set a valid email and name
        $mail->addAddress($email, "Recipient"); // The recipient

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'One-Time-Password for Email Verification';
        $mail->Body = '<div style="font-family: Arial, sans-serif; padding: 20px 0px 10px 0px; background-color: #fff; line-height: 1.6; text-align: left;">
        <img src="https://i.postimg.cc/7hRTGpt1/SMART-LOGO-2-2.png" alt="Smart Travel Logo" style="width: 260px; height: 45px; margin-bottom: 10px;">
        <p style="font-size: 1em;">Hi,</p>
        <p>Use the One-Time Password (OTP) below to verify your account:</p>
        <h2 style="padding: 10px; background-color: #333; color: #fff; border-radius: 5px; letter-spacing: 5px; width: 90px;">'.$verificationCode.'</h2>
        <p>This OTP is valid for 10 minutes. Do not share it with anyone.</p>
        <p style="font-size: 1.2em;">Thank you, <br/> Smart Travel </p>
        <hr style="border-top: 1px solid #eee;"/>
        <p style="font-size: 0.9em; color: #999;">If this not for you, please ignore this email or contact support.</p>
    </div>';

        // Send the email
        $mail->send();
        $response['message'] = "OTP has been sent to your email."; // Store success message
    } catch (Exception $e) {
        $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; // Store error message
    }

    // Return the response as JSON
    echo json_encode($response);
}
?>
