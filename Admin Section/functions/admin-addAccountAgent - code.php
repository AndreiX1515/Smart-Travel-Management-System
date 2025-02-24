<?php

session_start();
require "../../conn.php"; // Move up to the parent directory

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize response array
$response = array();

// Start output buffering to prevent unexpected output
ob_start();

// Start a database transaction
mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and sanitize form data
        $fName = $_POST['firstName'];
        $lName = $_POST['lastName'];
        $mName = $_POST['middleName'];
        $Suffix = $_POST['Suffix'];
        $countryCode = $_POST['countryCode'];
        $contactNo = $_POST['contactNo'];
        $password = $_POST['password'];
        $branchId = $_POST['branchId'];
        $accountType = $_POST['accountType'];
        $agentType = $_POST['agentType'];
        $agentRole = $_POST['agentRole'];
        $agentCode = "BU" . $_POST['branchId'];

        // Validation: Ensure required fields are not empty
        if (empty($fName) || empty($lName) || empty($password) || empty($branchId)) {
            throw new Exception("Required fields cannot be empty.");
        }

        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Logic for agent account type
        if ($accountType === 'agent') {
            // Fetch the latest agentId
            $sql_max_id = "SELECT MAX(CAST(SUBSTRING(agentId, 2) AS UNSIGNED)) AS maxId FROM agent";
            $result_max_id = mysqli_query($conn, $sql_max_id);

            if (!$result_max_id) {
                throw new Exception("Error fetching max agent ID: " . mysqli_error($conn));
            }

            $max_id_row = mysqli_fetch_assoc($result_max_id);
            $nextId = ($max_id_row['maxId'] !== null) ? $max_id_row['maxId'] + 1 : 1;

            $newAgentId = ($nextId < 10) ? 'A00' . $nextId : (($nextId < 100) ? 'A0' . $nextId : 'A' . $nextId);
            $agentUsername = $agentCode . '-' . $newAgentId;

            // Insert into accounts table
            $sql_account = "INSERT INTO accounts (email, password, otp, accountStatus, accountType, createdAt) 
                            VALUES (?, ?, '', 'active', ?, NOW())";
            $stmt = mysqli_prepare($conn, $sql_account);
            mysqli_stmt_bind_param($stmt, "sss", $agentUsername, $hashed_password, $accountType);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error inserting into accounts table: " . mysqli_error($conn));
            }

            $accountId = mysqli_insert_id($conn);

            // Insert into agent table
            $sql_agent = "INSERT INTO agent (agentId, agentCode, accountId, branchId, fName, lName, mName, countryCode, contactNo, agentType, agentRole, comissionRate, seats)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '', 0)";
            $stmt_agent = mysqli_prepare($conn, $sql_agent);
            mysqli_stmt_bind_param($stmt_agent, "ssissssssss", $newAgentId, $agentCode, $accountId, $branchId, $fName, $lName, $mName, $countryCode, $contactNo, $agentType, $agentRole);

            if (!mysqli_stmt_execute($stmt_agent)) {
                throw new Exception("Error inserting into agent table: " . mysqli_error($conn));
            }

            // Commit transaction
            mysqli_commit($conn);
            $response = ['status' => 'success', 'message' => "User added successfully with Agent Code: $agentUsername!"];
        }

        // Logic for guest account type
        elseif ($accountType === 'guest') {
            $sql_max_id = "SELECT MAX(CAST(SUBSTRING(clientId, 2) AS UNSIGNED)) AS maxId FROM client";
            $result_max_id = mysqli_query($conn, $sql_max_id);

            if (!$result_max_id) {
                throw new Exception("Error fetching max client ID: " . mysqli_error($conn));
            }

            $max_id_row = mysqli_fetch_assoc($result_max_id);
            $nextId = ($max_id_row['maxId'] !== null) ? $max_id_row['maxId'] + 1 : 1;
            $newClientId = ($nextId < 10) ? 'C00' . $nextId : (($nextId < 100) ? 'C0' . $nextId : 'C' . $nextId);
            $clientUsername = $agentCode . '-' . $newClientId;

            $sql_account = "INSERT INTO accounts (email, password, otp, accountStatus, accountType, createdAt) 
                        VALUES (?, ?, '', 'active', ?, NOW())";
            $stmt = mysqli_prepare($conn, $sql_account);

            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt, "sss", $clientUsername, $hashed_password, $accountType);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error inserting into accounts table: " . mysqli_error($conn));
            }

            $accountId = mysqli_insert_id($conn);

            // Insert into client table
            $sql_guest = "INSERT INTO client (clientId, clientCode, accountId, branchId, fName, lName, mName, countryCode, contactNo, clientType, clientRole)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_guest = mysqli_prepare($conn, $sql_guest);

            if (!$stmt_guest) {
                throw new Exception("Prepare statement failed: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt_guest, "ssiisssssss", $newClientId, $agentCode, $accountId, $branchId, $fName, $lName, $mName, $countryCode, $contactNo, $agentType, $clientRole);

            if (!mysqli_stmt_execute($stmt_guest)) {
                throw new Exception("Error inserting into client table: " . mysqli_error($conn));
            }

            // Commit transaction
            mysqli_commit($conn);
            $response = ['status' => 'success', 'message' => "User added successfully with Client ID: $newClientId!"];
        }
    } else {
        throw new Exception('Invalid request method.');
    }
} catch (Exception $e) {
    mysqli_rollback($conn); // Rollback any changes on error
    ob_end_clean(); // Clear any buffered output
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

// Send the response as JSON
echo json_encode($response);

// Close the database connection
mysqli_close($conn);
?>
