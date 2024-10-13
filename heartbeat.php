<?php
session_start();
include 'conn.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['accountid'])) {
    echo json_encode(['status' => 'Unauthorized']);
    http_response_code(401);
    exit;
}

$session_id = session_id();
$accountid = $_SESSION['accountid'];

// Fetch session from user_sessions table
$heartbeat_stmt = $conn->prepare("SELECT * FROM user_sessions WHERE session_id = ? AND accountid = ?");
$heartbeat_stmt->bind_param("si", $session_id, $accountid);
$heartbeat_stmt->execute();
$heartbeat_result = $heartbeat_stmt->get_result();

if ($heartbeat_result->num_rows !== 1) {
    echo json_encode(['status' => 'Invalid Session']);
    http_response_code(401);
    exit;
}

// Update last_activity to current time
$update_stmt = $conn->prepare("UPDATE user_sessions SET last_activity = NOW() WHERE session_id = ?");
$update_stmt->bind_param("s", $session_id);
$update_stmt->execute();
$update_stmt->close();

echo json_encode(['status' => 'OK']);
?>
