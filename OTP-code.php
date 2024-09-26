<?php
include "conn.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['register'])) 
{
  $email = $_POST['email'];
  $pass = $_POST['pass'];

  // Input validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
  {
    echo "Invalid email format";
    exit();
  }

  if (strlen($pass) < 6) 
  {
    echo "Password must be at least 6 characters long";
    exit();
  }

  // Initialize PHPMailer
  $mail = new PHPMailer(true);

  try 
  {
    $mail -> SMTPDebug = 0;
    $mail -> isSMTP();
    $mail -> Host = 'smtp.gmail.com';
    $mail -> SMTPAuth = true;
    $mail -> Username = 'no.repyltesting@gmail.com';  // Replace with your actual email
    $mail -> Password = 'ufrf wclh fuqy zawp';  // Replace with your actual password
    $mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail -> Port = 587;
    
    // Fix for the From address error
    $mail -> setFrom('no.repyltesting@gmail.com', 'Smart Travel');  // Set a valid email and name
    $mail -> addAddress($email, "name");
    $mail -> isHTML(true);

    // Generate verification code
    $verificationCode = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
    $mail -> Subject = 'Email Verification';
    $mail -> Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verificationCode . '</b></p>';
    $mail -> send();
    
    // Hash the password
    $encryptedPassword = password_hash($pass, PASSWORD_DEFAULT);

    // Insert into database using prepared statements
    $sql = "INSERT INTO account (email, password, otp, status) VALUES (?, ?, ?, '')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $email, $encryptedPassword, $verificationCode);

    if ($stmt->execute()) 
    {
      header("location: OTP.php");
      exit();
    } 
    else 
    {
      echo "Database error: " . $stmt->error;
    }
  } 
  catch (Exception $e) 
  {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}

// OTP.php validate button
if(isset($_POST['validate']))
{
  $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
  $sql1 = "SELECT * FROM account WHERE otp = $otp_code";
  $res1 = mysqli_query($conn, $sql1);

  if(mysqli_num_rows($res1) > 0)
  {
    $fetch_data = mysqli_fetch_assoc($res1);
    $fetch_code = $fetch_data['otp'];
    $email = $fetch_data['email'];
    $code = 0;
    $status = 'verified';
    $update_otp = "UPDATE account SET otp = $code, status = '$status' WHERE otp = $fetch_code";
    $update_res = mysqli_query($conn, $update_otp);
    if($update_res)
    {
      header('location: client-login.php');
      exit();
    }
    else
    {
      $errors['otp-error'] = "Failed while updating code!";
    }
  }
  else
  {
    $errors['otp-error'] = "You've entered incorrect code!";
  }
}

// client-login.php login code
if(isset($_POST['login'])) 
{
  $email = $_POST['email'];
  $pass = $_POST['pass'];

  // Use prepared statements to prevent SQL injection
  $sql1 = "SELECT * FROM account WHERE email = ? AND status = 'verified'";
  $stmt = $conn->prepare($sql1);
  $stmt->bind_param('s', $email); // 's' specifies that $email is a string
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if any user exists with the given email
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Verify the hashed password
    if (password_verify($pass, $row['password'])) {
      echo "Login Success.";
      // You can start a session here if needed: session_start();
      // Store user details in session: $_SESSION['user'] = $row;
    } else {
      echo "Wrong password.";
    }
  } else {
    echo "No user exists with this email or the account is not verified.";
  }

  $stmt->close();
}
?>
