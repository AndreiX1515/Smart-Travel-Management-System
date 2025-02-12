<?php
session_start();
require "../../conn.php"; // Move up to the parent directory

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize response array
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $otp = mysqli_real_escape_string($conn, $_POST['otp']);
    $accountStatus = mysqli_real_escape_string($conn, $_POST['accountStatus']);
    $accountType = mysqli_real_escape_string($conn, $_POST['accountType']);

    // Agent information
    $branchId = mysqli_real_escape_string($conn, $_POST['branchId']);
    $fName = mysqli_real_escape_string($conn, $_POST['fName']);
    $iName = mysqli_real_escape_string($conn, $_POST['iName']);
    $mName = mysqli_real_escape_string($conn, $_POST['mName']);
    $countryCode = mysqli_real_escape_string($conn, $_POST['countryCode']);
    $contactNo = mysqli_real_escape_string($conn, $_POST['contactNo']);
    $agentType = mysqli_real_escape_string($conn, $_POST['agentType']);
    $agentRole = mysqli_real_escape_string($conn, $_POST['agentRole']);
    $commissionRate = mysqli_real_escape_string($conn, $_POST['commissionRate']);

    // Validate required fields
    if (empty($email) || empty($password) || empty($otp) || empty($accountStatus) || empty($accountType) ||
        empty($branchId) || empty($fName) || empty($iName) || empty($mName) || empty($contactNo) ||
        empty($agentType) || empty($agentRole) || empty($commissionRate)) {
        $response['status'] = 'error';
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit();
    }

    // Insert into the accounts table first
    $sql_account = "INSERT INTO accounts (email, password, otp, accountStatus, accountType, createdAt) 
                    VALUES ('$email', '$password', '$otp', '$accountStatus', '$accountType', NOW())";
    
    if (mysqli_query($conn, $sql_account)) {
        // Get the last inserted accountId
        $accountId = mysqli_insert_id($conn);

        // Get the last agentId and increment it for the new agent
        $result = $conn->query("SELECT MAX(agentId) AS lastAgentId FROM agent");

        if ($result) {
            $row = $result->fetch_assoc();
            $newAgentId = $row['lastAgentId'] + 1;  // Increment the last agentId
        } else {
            $newAgentId = 1;  // If no records exist, start from 1
        }

        // Insert agent details using the new agentId
        $sql_agent = "INSERT INTO agent (agentId, accountId, branchId, fName, iName, mName, countryCode, contactNo, agentType, agentRole, commissionRate)
                      VALUES ('$newAgentId', '$accountId', '$branchId', '$fName', '$iName', '$mName', '$countryCode', '$contactNo', '$agentType', '$agentRole', '$commissionRate')";
        
        if (mysqli_query($conn, $sql_agent)) {
            // Success response
            $response['status'] = 'success';
            $response['message'] = "User added successfully with Agent ID: $newAgentId!";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error inserting into agent table: " . mysqli_error($conn);
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "Error inserting into accounts table: " . mysqli_error($conn);
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

// Send the response as JSON
echo json_encode($response);

// Close the database connection
mysqli_close($conn);
?>
