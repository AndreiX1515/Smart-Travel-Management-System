<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); // Ensure JSON output

// Check if session exists
if (!isset($_SESSION['client_accountId'])) {
    echo json_encode(["success" => false, "message" => "No session found."]);
    exit;
}

// Unset only specific session variables
unset(
    $_SESSION['client_accountId'], 
    $_SESSION['client_userType'], 
    $_SESSION['client_fName'], 
    $_SESSION['client_mName'], 
    $_SESSION['client_lName'], 
    $_SESSION['clientId'],  
    $_SESSION['clientCode'],  
    $_SESSION['clientRole'],  
    $_SESSION['clientType'],  
    $_SESSION['client_branchId'],  
    $_SESSION['client_timeout'],
    $_SESSION['client_flightId']
);

// Ensure all other agent-related session variables are also removed
foreach ($_SESSION as $key => $value) {
    if (strpos($key, 'client_') === 0) {
        unset($_SESSION[$key]);
    }
}


// Return success response
echo json_encode(["success" => true, "message" => "Logout successful."]);
exit;
?>
