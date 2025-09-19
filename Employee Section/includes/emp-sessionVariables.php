<?php

$accountId = $_SESSION['employee_accountId'] ?? '';
$empId = $_SESSION['employee_employeeId'] ?? '';
$firstName = $_SESSION['employee_fName'] ?? '';
$lastName = $_SESSION['employee_lName'] ?? '';
$middleName = $_SESSION['employee_mName'] ?? '';
$email = $_SESSION['employee_emailAddress'] ?? '';
$userType = $_SESSION['employee_userType'] ?? '';

// Format name for display only
$middleNameInitial = $middleName ? substr($middleName, 0, 1) . '.' : '';
$fullName = $firstName . ' ' . $middleNameInitial . ' ' . $lastName;
$position = strtoupper($empId);

// Escape only when echoing
?>

