<?php
session_start();
include 'conn.php';

if (isset($_SESSION['accountId'])) {
    $session_id = session_id();
    $accountid = $_SESSION['accountId'];

    // Remove session from user_sessions table
    $logout_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
    $logout_stmt->bind_param("s", $session_id);
    $logout_stmt->execute();
    $logout_stmt->close();
    header("location: index.php");
   
}

// Destroy PHP session
session_unset();
session_destroy();

exit;
?>
