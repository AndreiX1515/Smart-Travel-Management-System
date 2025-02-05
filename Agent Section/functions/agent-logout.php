<?php
session_start();
require "../../conn.php"; // Database connection

$response = ["status" => "error", "message" => "No session found."];

// Check if session exists
if (!isset($_SESSION['accountId'])) {
    echo json_encode($response);
    exit;
}

$accountId = $_SESSION['accountId'];
$conn->begin_transaction(); // Start transaction

try {
    // Check if account exists in user_sessions
    $checkStmt = $conn->prepare("SELECT COUNT(*) AS sessionCount FROM user_sessions WHERE accountid = ?");
    $checkStmt->bind_param("i", $accountId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $sessionData = $checkResult->fetch_assoc();

    if ($sessionData['sessionCount'] > 0) {
        // Retrieve account type
        $stmt = $conn->prepare("SELECT accountType FROM accounts WHERE accountId = ?");
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $accountType = $row['accountType']; // 'admin', 'agent', 'employee', 'guest'

            // Delete session record from user_sessions table
            $deleteStmt = $conn->prepare("DELETE FROM user_sessions WHERE accountid = ?");
            $deleteStmt->bind_param("i", $accountId);
            $deleteStmt->execute();

            // Remove specific session variables for agent/guest
            if ($accountType === 'guest' || $accountType === 'agent') {
                unset($_SESSION['accountId'], $_SESSION['flightId'], $_SESSION['agentId'], $_SESSION['agentCode'], $_SESSION['agentRole'], $_SESSION['agentType'], $_SESSION['fName'], $_SESSION['lName'], $_SESSION['mName'], $_SESSION['branchId'], $_SESSION['email'], $_SESSION['password']);

                session_destroy();
            }

            // Commit transaction
            $conn->commit();

            // Return success response with account type
            echo json_encode(["success" => true, "accountType" => $accountType, "message" => "Logout successful."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Account not found."]);
        }

        // Close statements
        $stmt->close();
        $deleteStmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "No active session found in user_sessions."]);
    }

    $checkStmt->close();
} catch (Exception $e) {
    $conn->rollback(); // Rollback if error occurs
    echo json_encode(["status" => "error", "message" => "Logout failed: " . $e->getMessage()]);
}

$conn->close();
exit;
?>
