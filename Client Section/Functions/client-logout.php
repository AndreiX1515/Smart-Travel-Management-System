<?php
session_start();

// Check if session exists
if (!isset($_SESSION['accountId'])) {
    echo json_encode(["status" => "error", "message" => "No session found."]);
    exit;
}

// Unset only specific session variables
unset(

    $_SESSION['clientId'], 
    $_SESSION['clientCode'],
    $_SESSION['clientRole'], 
    $_SESSION['clientType'], 
    $_SESSION['timeout'], 
    $_SESSION['flightid'],
    $_SESSION['accountId'], 
    $_SESSION['userType'],
    $_SESSION['fName'], 
    $_SESSION['mName'], 
    $_SESSION['lName'], 
    $_SESSION['branchId']
);



// Redirect to client login page
header("Location: ../Client Section/clientLogin.php");
exit;
?>
