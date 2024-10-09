<?php
session_start(); // Start session

// Include your database connection
include 'conn.php'; // Adjust this to match your actual database connection

// Define session timeout duration (e.g., 30 minutes)
$inactive = 1800; // seconds

// Check if user is logged in
if (!isset($_SESSION['accountid'])) {
    // Not logged in
    header("Location: login.php?message=Please log in.");
    exit;
}

// Get session details
$session_id = session_id();
$accountid = $_SESSION['accountid'];

// Fetch session from user_sessions table
$session_stmt = $conn->prepare("SELECT * FROM user_sessions WHERE session_id = ? AND accountid = ?");
$session_stmt->bind_param("si", $session_id, $accountid);
$session_stmt->execute();
$session_result = $session_stmt->get_result();

if ($session_result->num_rows !== 1) {
    // Session not found or multiple sessions found
    // Destroy session and redirect to login
    session_unset();
    session_destroy();
    header("Location: login.php?message=Invalid session.");
    exit;
}

$session = $session_result->fetch_assoc();

// Check for session timeout based on last_activity
$last_activity = strtotime($session['last_activity']);
if ((time() - $last_activity) > $inactive) {
    // Session has timed out
    // Remove session from user_sessions table
    $delete_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
    $delete_stmt->bind_param("s", $session_id);
    $delete_stmt->execute();
    $delete_stmt->close();

    // Destroy PHP session
    session_unset();
    session_destroy();

    // Redirect to login with timeout message
    header("Location: login.php?message=Session expired due to inactivity.");
    exit;
}

// Update last_activity to current time
$update_stmt = $conn->prepare("UPDATE user_sessions SET last_activity = NOW() WHERE session_id = ?");
$update_stmt->bind_param("s", $session_id);
$update_stmt->execute();
$update_stmt->close();

// Update session timeout
$_SESSION['timeout'] = time();
?>
