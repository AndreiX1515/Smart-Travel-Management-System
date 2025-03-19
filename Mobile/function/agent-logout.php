<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); // Ensure JSON output

// Check if session exists
if (!isset($_SESSION['agent_accountId'])) {
    echo json_encode(["success" => false, "message" => "No session found."]);
    exit;
}

// Unset only specific session variables
unset(
    $_SESSION['agent_accountId'], 
    $_SESSION['agent_userType'], 
    $_SESSION['agent_fName'], 
    $_SESSION['agent_mName'], 
    $_SESSION['agent_lName'], 
    $_SESSION['agentId'],  
    $_SESSION['agentCode'],  
    $_SESSION['agentRole'],  
    $_SESSION['agentType'],  
    $_SESSION['agent_branchId'],  
    $_SESSION['agent_timeout'],
    $_SESSION['agent_flightId']
);

// Ensure all other agent-related session variables are also removed
foreach ($_SESSION as $key => $value) {
    if (strpos($key, 'agent_') === 0) {
        unset($_SESSION[$key]);
    }
}


// Return success response
echo json_encode(["success" => true, "message" => "Logout successful."]);
exit;
?>
