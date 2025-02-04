<?php
session_start();
require "../../conn.php";

// Debugging: Check if session starts
error_log("Session started");

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    error_log("Invalid request method.");
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

// Fetch and sanitize input data
$email = $_POST["email"] ?? '';
$accountId = $_POST["accountId"] ?? '';
$flightid = $_POST["flightid"] ?? '';

// Debugging: Log incoming data
error_log("Received Data - Email: $email, Account ID: $accountId, Flight ID: $flightid");

// Validate required fields
if (empty($email) || empty($accountId)) {
    error_log("Missing required fields.");
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

// Update session variables
$_SESSION['email'] = $email;
$_SESSION['accountId'] = $accountId;
$_SESSION['flightid'] = $flightid;

// Debugging: Log session updates
error_log("Session Updated - Email: {$_SESSION['email']}, Account ID: {$_SESSION['accountId']}, Flight ID: {$_SESSION['flightid']}");

// Fetch agent and branch details if the user is an agent
$stmt = $conn->prepare("
    SELECT ag.accountId, ag.agentId, ag.agentCode, ag.agentRole, 
           b.branchName, b.branchId 
    FROM agent ag
    JOIN branch b ON ag.branchId = b.branchId
    WHERE ag.accountId = ?
");
$stmt->bind_param("i", $accountId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Store agent details in the session
    $_SESSION['agentId'] = $row['agentId'];
    $_SESSION['agentType'] = $row['agentRole'];  // Fixed: agentType was missing
    $_SESSION['agentCode'] = $row['agentCode'];
    $_SESSION['agentRole'] = $row['agentRole'];
    $_SESSION['branchId'] = $row['branchId'];
    $_SESSION['branchName'] = $row['branchName'];

    // Debugging: Log agent details
    error_log("Agent Details Fetched: Agent ID: {$_SESSION['agentId']}, Branch ID: {$_SESSION['branchId']}");
} else {
    error_log("No agent details found for Account ID: $accountId");
}

// Close the statement
$stmt->close();

// Return success response
echo json_encode(["status" => "success"]);
exit;
?>
