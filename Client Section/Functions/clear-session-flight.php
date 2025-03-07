<?php
session_start();

// Unset all specific session variables
$_SESSION['clientId'] = null;
$_SESSION['client_accountId'] = null;
$_SESSION['clientCode'] = null;
$_SESSION['client_userType'] = null;
$_SESSION['clientRole'] = null;
$_SESSION['clientType'] = null;
$_SESSION['client_timeout'] = null;
$_SESSION['client_flightid'] = null;
$_SESSION['client_fName'] = null;
$_SESSION['client_mName'] = null;
$_SESSION['client_lName'] = null;
$_SESSION['client_branchId'] = null;

// Unset the variables and remove from the session
unset(
    $_SESSION['clientId'], 
    $_SESSION['client_accountId'],
    $_SESSION['clientCode'],
    $_SESSION['client_userType'],
    $_SESSION['clientRole'], 
    $_SESSION['clientType'], 
    $_SESSION['client_timeout'], 
    $_SESSION['client_flightid'],
    $_SESSION['client_fName'], 
    $_SESSION['client_mName'], 
    $_SESSION['client_lName'], 
    $_SESSION['client_branchId']
);

// Optional: Destroy session completely
session_write_close();

// Send JSON response for AJAX success
echo json_encode(["success" => true, "message" => "Logout successful."]);
exit;
?>
