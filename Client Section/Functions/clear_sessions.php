<?php
session_start();
require "../../conn.php";

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

// Fetch and sanitize input data
$email = $_POST["email"] ?? '';
$accountId = $_POST["accountId"] ?? '';
$flightid = $_POST["flightid"] ?? '';

// Log data for debugging
error_log("Email: " . $email);
error_log("Account ID: " . $accountId);
error_log("Flight ID: " . $flightid);

// Validate required fields
if (empty($email) || empty($accountId) || empty($flightid)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

// Update session variables
$_SESSION['email'] = $email;
$_SESSION['accountId'] = $accountId;
$_SESSION['flightid'] = $flightid;

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
    $_SESSION['agentCode'] = $row['agentCode'];
    $_SESSION['agentRole'] = $row['agentRole'];
    $_SESSION['branchId'] = $row['branchId'];
    $_SESSION['branchName'] = $row['branchName'];
}

// Close the statement
$stmt->close();

// Return success response
echo json_encode(["status" => "success"]);
exit;
?>
