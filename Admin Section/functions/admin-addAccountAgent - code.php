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
    $fName = $_POST['firstName'];
    $lName = $_POST['lastName'];
    $mName = $_POST['middleName'];
    $Suffix = $_POST['Suffix'];
    $countryCode = $_POST['countryCode'];
    $contactNo = $_POST['contactNo'];
    // $email = $_POST['email'];
    $password = $_POST['password'];
    $branchId = $_POST['branchId'];
    $accountType = $_POST['accountType'];
    $agentType = $_POST['agentType'];
    $agentRole = $_POST['agentRole'];
    $agentCode = "BU" . $_POST['branchId'];

    // Validation: Ensure required fields are not empty
    if (empty($fName) || empty($lName) || empty($password) || empty($branchId)) {
        $response['status'] = 'error';
        $response['message'] = 'Required fields cannot be empty.';
        echo json_encode($response);
        exit();
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Logic for agent account type
    if ($accountType === 'agent') {
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

        // Prepared statement for inserting into accounts table
        $sql_account = "INSERT INTO accounts (email, password, otp, accountStatus, accountType, createdAt) 
                        VALUES (?, ?, '', 'active', ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql_account);
        mysqli_stmt_bind_param($stmt, "sss", $newAgentId, $hashed_password, $accountType);

        if (mysqli_stmt_execute($stmt)) {
            // Get the last inserted accountId
            $accountId = mysqli_insert_id($conn);

            // Prepared statement for inserting agent details
            $sql_agent = "INSERT INTO agent (agentId, agentCode, accountId, branchId, fName, lName, mName, countryCode, contactNo, agentType, agentRole, comissionRate)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '')";
            $stmt_agent = mysqli_prepare($conn, $sql_agent);
            mysqli_stmt_bind_param($stmt_agent, "ssissssssss", $newAgentId, $agentCode, $accountId, $branchId, $fName, $lName, $mName, $countryCode, $contactNo, $agentType, $agentRole);

            if (mysqli_stmt_execute($stmt_agent)) {
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
    } 

   // Logic for guest account type
elseif ($accountType === 'guest') {
    // Fetch the latest clientId from the database
    $sql_max_id = "SELECT MAX(CAST(SUBSTRING(clientId, 2) AS UNSIGNED)) AS maxId FROM client";
    $result_max_id = mysqli_query($conn, $sql_max_id);

    if (!$result_max_id) {
        $response['status'] = 'error';
        $response['message'] = "Error fetching max client ID: " . mysqli_error($conn);
        echo json_encode($response);
        exit();
    }

    $max_id_row = mysqli_fetch_assoc($result_max_id);
    $nextId = ($max_id_row['maxId'] !== null) ? $max_id_row['maxId'] + 1 : 1; // If no records exist, start from 1

    // Format the new clientId (instead of agentId)
    if ($nextId < 10) {
        $newClientId = 'C00' . $nextId; // C001, C002...
    } elseif ($nextId < 100) {
        $newClientId = 'C0' . $nextId; // C010, C011...
    } else {
        $newClientId = 'C' . $nextId; // C100, C101...
    }

    // Check if the generated clientId already exists
    $sql_check_duplicate = "SELECT COUNT(*) AS count FROM client WHERE clientId = '$newClientId'";
    $result_check = mysqli_query($conn, $sql_check_duplicate);

    if (!$result_check) {
        $response['status'] = 'error';
        $response['message'] = "Error checking duplicate client ID: " . mysqli_error($conn);
        echo json_encode($response);
        exit();
    }

    $check_row = mysqli_fetch_assoc($result_check);

    if ($check_row['count'] > 0) {
        // If the clientId exists, increment the ID and retry
        $nextId++;
        if ($nextId < 10) {
            $newClientId = 'C00' . $nextId;
        } elseif ($nextId < 100) {
            $newClientId = 'C0' . $nextId;
        } else {
            $newClientId = 'C' . $nextId;
        }
    }

    // Prepared statement for inserting into accounts table
    $sql_account = "INSERT INTO accounts (email, password, otp, accountStatus, accountType, createdAt) 
                    VALUES (?, ?, '', 'active', ?, NOW())";

    $stmt = mysqli_prepare($conn, $sql_account);
    mysqli_stmt_bind_param($stmt, "sss", $newClientId, $hashed_password, $accountType);

    if (mysqli_stmt_execute($stmt)) {
        // Get the last inserted accountId
        $accountId = mysqli_insert_id($conn);

        // Prepared statement for inserting guest details
        $sql_guest = "INSERT INTO client (clientId, clientCode, accountId, branchId, fName, lName, mName, countryCode, contactNo, clientType, clientRole)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_guest = mysqli_prepare($conn, $sql_guest);
        mysqli_stmt_bind_param($stmt_guest, "ssiisssssss", $newClientId, $agentCode, $accountId, $branchId, $fName, $lName, $mName, $countryCode, $contactNo, $agentType, $agentRole);

        if (mysqli_stmt_execute($stmt_guest)) {
            $response['status'] = 'success';
            $response['message'] = "User added successfully with Client ID: $newClientId!";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error inserting into client table: " . mysqli_error($conn);
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "Error inserting into accounts table: " . mysqli_error($conn);
    }
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
