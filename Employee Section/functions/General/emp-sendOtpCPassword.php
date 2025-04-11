<?php
session_start();
include "../../../conn.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';

header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("[OTP] POST request received.");

    $email = isset($_POST['emailAddress']) ? trim($_POST['emailAddress']) : null;
    error_log("[OTP] Received email: " . var_export($email, true));

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("[OTP] Invalid email provided.");
        echo json_encode([
            'status' => 'error',
            'emailAddress' => null,
            'message' => 'Invalid email format.'
        ]);
        exit;
    }

    function generateVerificationCode() {
        return substr(number_format(time() * rand(), 0, '', ''), 0, 6);
    }

    unset($_SESSION['otp']);
    $verificationCode = generateVerificationCode();
    $_SESSION['otp'] = $verificationCode;
    $_SESSION['emailAddress'] = $email;

    error_log("[OTP] Generated code: $verificationCode");

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set to DEBUG_SERVER for dev
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'no.repyltesting@gmail.com';
        $mail->Password   = 'ufrf wclh fuqy zawp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('no.repyltesting@gmail.com', 'Smart Travel');
        $mail->addAddress($email, 'Recipient');

        $mail->isHTML(true);
        $mail->Subject = 'Agent One-Time-Password Verification for Password Change';
        $mail->Body    = '
        <div style="font-family: Arial, sans-serif; padding: 20px 0px 10px 0px; background-color: #fff; line-height: 1.6; text-align: left;">
            <img src="https://i.postimg.cc/7hRTGpt1/SMART-LOGO-2-2.png" alt="Smart Travel Logo" style="width: 260px; height: 45px; margin-bottom: 10px;">
            <p style="font-size: 1em;">Hi,</p>
            <p>Here is your OTP needed for password change:</p>
            <h2 style="padding: 10px; background-color: #333; color: #fff; border-radius: 5px; letter-spacing: 5px; width: 90px;">' . $verificationCode . '</h2>
            <p>This OTP is valid for 10 minutes. Do not share it with anyone.</p>
            <p style="font-size: 1.2em;">Thank you, <br/> Smart Travel </p>
            <hr style="border-top: 1px solid #eee;"/>
            <p style="font-size: 0.9em; color: #999;">If this is not for you, please ignore this email or contact support.</p>
        </div>';

        $mail->send();
        error_log("[OTP] Email successfully sent to $email");

        $response = [
            'status' => 'success',
            'message' => 'OTP has been sent to your email address.',
            'otp' => $_SESSION['otp'],
            'emailAddress' => $email
        ];

        unset($_SESSION['emailAddress']); // Clean up after response

    } catch (Exception $e) {
        error_log("[OTP] Mailer Error: " . $mail->ErrorInfo);
        $response = [
            'status' => 'error',
            'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo
        ];
    }

} else {
    error_log("[OTP] Invalid request method. Only POST allowed.");
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method.'
    ];
}

echo json_encode($response);
exit;
?>
