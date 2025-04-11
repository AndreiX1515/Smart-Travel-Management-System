<?php

session_start();

// Check if session exists
if (!isset($_SESSION['client_accountId'])) {
    echo json_encode(["success" => false, "message" => "No session found."]);
    exit;
}

unset(
    $_SESSION['clientId'], 
    $_SESSION['clientCode'],
    $_SESSION['clientRole'], 
    $_SESSION['clientType'], 
    $_SESSION['userType'], 
    $_SESSION['emailAddress']
);

// Unset all session variables that start with 'client_'
foreach ($_SESSION as $key => $value) {
    if (strpos($key, 'client_') === 0) {
        unset($_SESSION[$key]);
    }
}



// Send JSON response for AJAX success
echo json_encode(["success" => true, "message" => "Logout successful."]);
exit;

?>