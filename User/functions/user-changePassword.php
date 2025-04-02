<?php
require_once "../../conn.php";
session_start();

// Ensure the session contains the required values
if (!isset($_SESSION['userType']) || (!isset($_SESSION['agent_accountId']) && !isset($_SESSION['client_accountId']))) {
    echo json_encode(["status" => "error", "message" => "Session expired. Please log in again."]);
    
    exit();
}

// Get user details from session
$userType = $_SESSION['userType'];
$accountId = ($userType === 'agent') ? $_SESSION['agent_accountId'] : $_SESSION['client_accountId'];

// Get form data
$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';

// Validate input
if (empty($currentPassword) || empty($newPassword)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all fields."]);
    exit();
}

// Retrieve stored password from the correct table
$query = "SELECT password FROM accounts WHERE accountid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $accountId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($storedPassword);
    $stmt->fetch();

    // Verify current password
    if ($currentPassword !== $storedPassword) {
        echo json_encode(["status" => "error", "message" => "Incorrect current password."]);
        exit();
    }

    // Update password without hashing
    $updateQuery = "UPDATE accounts SET password = ?, defaultPasswordStat = 'no' WHERE accountid = ?";

    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $newPassword, $accountId);

    if ($updateStmt->execute()) {
        if ($userType == "agent") {
            // Unset only specific session variables
            unset(
                $_SESSION['agent_accountId'], 
                $_SESSION['agent_userType'], 
                $_SESSION['agent_fName'], 
                $_SESSION['agent_mName'], 
                $_SESSION['agent_lName'], 
                $_SESSION['agentId'],  
                $_SESSION['agentCode'],  
                $_SESSION['agentRole'],  
                $_SESSION['agentType'],  
                $_SESSION['agent_branchId'],  
                $_SESSION['agent_timeout'],
                $_SESSION['agent_flightId'],
                $_SESSION['userType']
            );


            // Unset all agent-related session variables
            foreach ($_SESSION as $key => $value) {
                if (strpos($key, 'agent_') === 0) {
                    unset($_SESSION[$key]);
                }
            } 
        }

        else if ($userType == "client") {
            unset(
                $_SESSION['clientId'], 
                $_SESSION['clientCode'],
                $_SESSION['clientRole'], 
                $_SESSION['clientType'],
                $_SESSION['userType'] 
            );
            
            // Unset all session variables that start with 'client_'
            foreach ($_SESSION as $key => $value) {
                if (strpos($key, 'client_') === 0) {
                    unset($_SESSION[$key]);
                }
            }
        }
        
        echo json_encode(["status" => "success", "message" => "Password updated successfully."]);

    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update password. Try again."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not found."]);
}

$stmt->close();
$conn->close();
?>
