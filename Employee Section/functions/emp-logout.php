<?php
session_start();

// Check if the session is already empty
if (empty($_SESSION)) {
    echo json_encode(['success' => true, 'message' => 'No active session found.']);
    exit;
}

// Unset specific session variables if they exist
unset($_SESSION['employee_accountId'], $_SESSION['employee_employeeId'], $_SESSION['employee_fName'], 
      $_SESSION['employee_lName'], $_SESSION['employee_mName'], $_SESSION['email'], 
      $_SESSION['password'], $_SESSION['employee_userType']);

// Destroy session completely
session_destroy();

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Logout successful.']);
exit;
?>
