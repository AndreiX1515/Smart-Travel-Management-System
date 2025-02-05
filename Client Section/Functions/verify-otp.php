<?php
session_start(); // Start the session
require '../../conn.php'; // Your database connection

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Common function to output JSON response
  function jsonResponse($success, $message)
  {
    // Check if the success flag is false, then add a fallback error message
    if (!$success) {
      $message = $message ?: 'An unexpected error occurred. Please try again later.';
    }
    
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
    $password = trim($_SESSION['password'] ?? '');
    $branchId = $_SESSION['branchId'];

    // Additional fields
    $account_status = 'active'; // Default to active
    $otp = $_SESSION['otp']; // Store the OTP in the database
    $created_at = date('Y-m-d H:i:s'); // Current timestamp

    // Start a transaction
    $conn->begin_transaction();
    $conn->autocommit(FALSE);  // Disable autocommit to manually manage transactions

    // Insert data into the accounts table
    $stmt1 = $conn->prepare("INSERT INTO accounts (email, password, otp, accountStatus, accountType, createdAt) VALUES 
    (?, ?, ?, ?, 'guest', NOW())");
    $stmt1->bind_param("ssis", $email, $password, $otp, $account_status);

    if ($stmt1->execute()) {
      // Get the last inserted accountId
      $accountId = $conn->insert_id;

      // Retrieve branchAgentCode from the branch table
      $stmt2 = $conn->prepare("SELECT branchAgentCode FROM branch WHERE branchId = ?");
      $stmt2->bind_param("i", $branchId);
      $stmt2->execute();
      $stmt2->bind_result($branchAgentCode);
      $stmt2->fetch();
      $stmt2->close(); // Properly close $stmt2

      // Check if branchAgentCode is NULL or empty
      if (is_null($branchAgentCode) || $branchAgentCode === '') {
        jsonResponse(false, "Error: Branch agent code not found for branchId: " . $branchId);
        $conn->rollback();
        exit;
      }

      // Generate agentId (e.g., A001)
      $stmt3 = $conn->prepare("SELECT MAX(id) FROM agent");
      $stmt3->execute();
      $stmt3->bind_result($maxId);
      $stmt3->fetch();
      $stmt3->close(); // Properly close $stmt3

      // If no agents exist, start with A001
      $nextId = $maxId ? $maxId + 1 : 1;
      $agentId = "A" . str_pad($nextId, 3, "0", STR_PAD_LEFT);

      // Insert data into the agent table
      $stmt4 = $conn->prepare("INSERT INTO agent (agentId, agentCode, accountId, branchId, agentType, agentRole) 
          VALUES (?, ?, ?, ?, 'Retailer', 'Sub Agent2')");
      $stmt4->bind_param("ssii", $agentId, $branchAgentCode, $accountId, $branchId);

      if ($stmt4->execute()) {
        // Commit the transaction
        $conn->commit();

        // Clear session variables after successful registration
        unset($_SESSION['Reg-FirstName'], $_SESSION['Reg-LastName'], $_SESSION['Reg-MiddleName'], $_SESSION['Reg-Email'], $_SESSION['Reg-Password'], $_SESSION['otp']);
        jsonResponse(true, 'Registration successful! Agent created with ID: ' . $agentId);
      } else {
        jsonResponse(false, "Error inserting agent: " . $stmt4->error);
        $conn->rollback();
      }
    } else {
      jsonResponse(false, "Error inserting account: " . $stmt1->error);
      $conn->rollback();
    }

    $stmt1->close();
    $stmt4->close();
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
    jsonResponse(true, 'OTP sent to your email. Your OTP is: ' . $_SESSION['otp']);
  }
}

// Function to generate a 6-digit OTP
function generateVerificationCode()
{
  return substr(number_format(time() * rand(), 0, '', ''), 0, 6);
}
?>
