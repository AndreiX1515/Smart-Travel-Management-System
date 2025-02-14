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
    $fName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $iName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $mName = mysqli_real_escape_string($conn, $_POST['middleName']);
    $Suffix = mysqli_real_escape_string($conn, $_POST['Suffix']);
    $countryCode = mysqli_real_escape_string($conn, $_POST['countryCode']);
    $contactNo = mysqli_real_escape_string($conn, $_POST['contactNo']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $branchId = mysqli_real_escape_string($conn, $_POST['branchId']);
    $accountType = mysqli_real_escape_string($conn, $_POST['accountType']);
    $agentType = mysqli_real_escape_string($conn, $_POST['agentType']);
    $agentRole = mysqli_real_escape_string($conn, $_POST['agentRole']);
    $agentCode = "BU" . mysqli_real_escape_string($conn, $_POST['branchId']);

    // Validation: Ensure required fields are not empty
    if (empty($fName) || empty($iName) || empty($email) || empty($password) || empty($branchId)) {
        $response['status'] = 'error';
        $response['message'] = 'Required fields cannot be empty.';
        echo json_encode($response);
        exit();
    }

    // Validation: Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit();
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Fetch the latest agentId from the database
    $sql_max_id = "SELECT MAX(CAST(SUBSTRING(agentId, 2) AS UNSIGNED)) AS maxId FROM agent";
    $result_max_id = mysqli_query($conn, $sql_max_id);

    if (!$result_max_id) {
        $response['status'] = 'error';
        $response['message'] = "Error fetching max agent ID: " . mysqli_error($conn);
        echo json_encode($response);
        exit();
    }

    $max_id_row = mysqli_fetch_assoc($result_max_id);
    $nextId = ($max_id_row['maxId'] !== null) ? $max_id_row['maxId'] + 1 : 1; // If no records exist, start from 1

    // Format the new agentId
    if ($nextId < 10) {
        $newAgentId = 'A00' . $nextId; // A001, A002...
    } elseif ($nextId < 100) {
        $newAgentId = 'A0' . $nextId; // A010, A011...
    } else {
        $newAgentId = 'A' . $nextId; // A100, A101...
    }


    // Insert into the accounts table first
    $sql_account = "INSERT INTO accounts (email, password, otp, accountStatus, accountType, createdAt) VALUES ('$email', '$hashed_password', '', 'active', '$accountType', NOW())";
    
    if (mysqli_query($conn, $sql_account)) {
        // Get the last inserted accountId
        $accountId = mysqli_insert_id($conn);

        // Insert agent details using the generated or provided agentCode
        $sql_agent = "INSERT INTO agent (agentId, agentCode, accountId, branchId, fName, lName, mName, countryCode, contactNo, agentType, agentRole, comissionRate)
                      VALUES ('$newAgentId', '$agentCode', '$accountId', $branchId, '$fName', '$iName', '$mName', '$countryCode', '$contactNo', '$agentType', '$agentRole', '')";
        
        if (mysqli_query($conn, $sql_agent)) {
            $response['status'] = 'success';
            $response['message'] = "User added successfully with Agent Code: $newAgentId!";
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
