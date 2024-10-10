<?php
session_start();
include 'conn.php'; // Include your database connection

$inactive = 1800; // 30 minutes

if (!isset($_SESSION['accountid'])) {
    header("Location: login.php?message=Please log in."); // Redirect with message
    echo json_encode(['status' => 'expired']);
    exit;
}

$session_id = session_id();
$accountid = $_SESSION['accountid'];

$session_stmt = $conn->prepare("SELECT * FROM user_sessions WHERE session_id = ? AND accountid = ?");
$session_stmt->bind_param("si", $session_id, $accountid);
$session_stmt->execute();
$session_result = $session_stmt->get_result();

if ($session_result->num_rows !== 1) {
    // Session not found, destroy session
    session_unset();
    session_destroy();
    echo json_encode(['status' => 'expired']);
    exit;
}

$session = $session_result->fetch_assoc();

$last_activity = strtotime($session['last_activity']);
if ((time() - $last_activity) > $inactive) {
    // Session has timed out, destroy it
    $delete_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
    $delete_stmt->bind_param("s", $session_id);
    $delete_stmt->execute();
    $delete_stmt->close();

    session_unset();
    session_destroy();
    echo json_encode(['status' => 'expired']);
    exit;
}

// Update last_activity to current time
$update_stmt = $conn->prepare("UPDATE user_sessions SET last_activity = NOW() WHERE session_id = ?");
$update_stmt->bind_param("s", $session_id);
$update_stmt->execute();
$update_stmt->close();

// echo json_encode(['status' => 'active']);
?>
