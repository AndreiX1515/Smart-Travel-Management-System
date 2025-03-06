<?php
session_start();


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

// Return success response
echo json_encode(["success" => true, "message" => "Logout successful."]);
exit;
?>
