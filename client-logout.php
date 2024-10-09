<?php
session_start();

// Include your database connection
include 'conn.php'; // Adjust the path as necessary

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

// Return a JSON response
echo json_encode(['status' => 'success', 'message' => 'You have been logged out successfully.']);
exit;
?>
