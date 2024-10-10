<?php
session_start();
include 'conn.php';

if (isset($_SESSION['accountid'])) {
    $session_id = session_id();
    $accountid = $_SESSION['accountid'];

    // Remove session from user_sessions table
    $logout_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
    $logout_stmt->bind_param("s", $session_id);
    $logout_stmt->execute();
    $logout_stmt->close();
}

// Destroy PHP session
session_unset();
session_destroy();

// Optionally, return a response (not required for sendBeacon)
echo json_encode(['status' => 'success', 'message' => 'Session destroyed successfully.']);
exit;
?>
