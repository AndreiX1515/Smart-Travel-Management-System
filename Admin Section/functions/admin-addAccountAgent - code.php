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
    $fName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $iName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $mName = mysqli_real_escape_string($conn, $_POST['middleName']);
    $countryCode = mysqli_real_escape_string($conn, $_POST['countryCode']);
    $contactNo = mysqli_real_escape_string($conn, $_POST['contactNo']);


    // $password = mysqli_real_escape_string($conn, $_POST['password']);
    // $otp = mysqli_real_escape_string($conn, 999999);
    // $accountStatus = mysqli_real_escape_string($conn, $_POST['accountStatus']);
    // $accountType = mysqli_real_escape_string($conn, $_POST['accountType']);

    // // Agent information
    // $agentCode = mysqli_real_escape_string($conn, $_POST['agentCode']);
    // $branchId = mysqli_real_escape_string($conn, $_POST['branchId']);
   
    // 
    // $agentType = mysqli_real_escape_string($conn, $_POST['agentType']);
    // $agentRole = mysqli_real_escape_string($conn, $_POST['agentRole']);
    // $commissionRate = mysqli_real_escape_string($conn, $_POST['commissionRate']);

    // Validate required fields
    // if (empty($email) || empty($password) || empty($otp) || empty($accountStatus) || empty($accountType) ||
    //     empty($branchId) || empty($fName) || empty($iName) || empty($mName) || empty($contactNo) ||
    //     empty($agentType) || empty($agentRole) || empty($commissionRate)) {
    //     $response['status'] = 'error';
    //     $response['message'] = 'All fields are required.';
    //     echo json_encode($response);
    //     exit();
    // }

    // Check if the provided agentCode exists
    $sql_check_agentCode = "SELECT COUNT(*) AS count FROM agent WHERE agentCode = '$agentCode'";
    $result = mysqli_query($conn, $sql_check_agentCode);

    if (!$result) {
        $response['status'] = 'error';
        $response['message'] = "Error checking agent code: " . mysqli_error($conn);
        echo json_encode($response);
        exit();
    }

    $row = mysqli_fetch_assoc($result);

    // If the agentCode exists, generate a new one
    if ($row['count'] > 0) {
        // Generate an incremented agentCode based on existing records
        $sql_max_code = "SELECT MAX(CAST(agentCode AS UNSIGNED)) AS maxCode FROM agent";
        $result_max_code = mysqli_query($conn, $sql_max_code);
        
        if ($result_max_code) {
            $max_code_row = mysqli_fetch_assoc($result_max_code);
            $newAgentCode = $max_code_row['maxCode'] + 1;
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error fetching max agent code: " . mysqli_error($conn);
            echo json_encode($response);
            exit();
        }
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
            // Success response
            $response['status'] = 'success';
            $response['message'] = "User added successfully with Agent Code: $newAgentCode!";
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
