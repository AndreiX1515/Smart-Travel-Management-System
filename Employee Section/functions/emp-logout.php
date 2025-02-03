<?php
session_start();

// Unset specific session variables
unset($_SESSION['employee_accountId']);
unset($_SESSION['employee_employeeId']);
unset($_SESSION['employee_fName']);
unset($_SESSION['employee_lName']);
unset($_SESSION['employee_mName']);
unset($_SESSION['email']);
unset($_SESSION['password']);
unset($_SESSION['employee_userType']);

// Send success response
http_response_code(200);
exit;
?>
