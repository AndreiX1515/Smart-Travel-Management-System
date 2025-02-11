<?php

session_start();
require "../../conn.php"; // Move up to the parent directory

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Initialize response array
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $otp = $_POST['otp'];
    $accountStatus = $_POST['accountStatus'];
    $accountType = $_POST['accountType'];

    // Get agent information
    $agentCode = $_POST['agentCode'];
    $branchId = $_POST['branchId'];
    $fName = $_POST['fName'];
    $iName = $_POST['iName'];
    $mName = $_POST['mName'];
    $countryCode = $_POST['countryCode'];
    $contactNo = $_POST['contactNo'];
    $agentType = $_POST['agentType'];
    $agentRole = $_POST['agentRole'];
    $commissionRate = $_POST['commissionRate'];

    // Check if the provided agentCode exists
    $sql_check_agentCode = "SELECT COUNT(*) FROM agent WHERE agentCode = '$agentCode'";
    $result = mysqli_query($conn, $sql_check_agentCode);
    $row = mysqli_fetch_array($result);

    // If the agentCode exists, generate a new one
    if ($row[0] > 0) {
        // Generate an incremented agentCode based on existing records
        $sql_max_code = "SELECT MAX(CAST(agentCode AS UNSIGNED)) AS maxCode FROM agent";
        $result_max_code = mysqli_query($conn, $sql_max_code);
        $max_code_row = mysqli_fetch_assoc($result_max_code);
        
        // Generate new agentCode by incrementing the max value found
        $newAgentCode = $max_code_row['maxCode'] + 1;
    } else {
        // Use the provided agentCode if it's unique
        $newAgentCode = $agentCode;
    }

    // Insert into the accounts table first
    $sql_account = "INSERT INTO accounts (email, password, otp, accountStatus, accountType, createdAt) 
                    VALUES ('$email', '$password', '$otp', '$accountStatus', '$accountType', NOW())";
    
    if (mysqli_query($conn, $sql_account)) {
        // Get the last inserted accountId
        $accountId = mysqli_insert_id($conn);
        
        // Insert agent details using the generated or provided agentCode
        $sql_agent = "INSERT INTO agent (accountId, agentCode, branchId, fName, iName, mName, countryCode, contactNo, agentType, agentRole, commissionRate)
        VALUES ('$accountId', '$newAgentCode', '$branchId', '$fName', '$iName', '$mName', '$countryCode', '$contactNo', '$agentType', '$agentRole', '$commissionRate')";
        
        if (mysqli_query($conn, $sql_agent)) {
            // Success message
            $response['message'] = "User added successfully with Agent Code: $newAgentCode!";
        } else {
            // Error message for agent table
            $response['message'] = "Error inserting into agent table: " . mysqli_error($conn);
        }
    } else {
        // Error message for accounts table
        $response['message'] = "Error inserting into accounts table: " . mysqli_error($conn);
    }

    // Send the response as JSON
    echo json_encode($response);
}

// Close the database connection
mysqli_close($conn);
?>
