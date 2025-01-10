<?php
include '../conn.php'; // Include your database connection

$inactive = 600; // 10 minutes (600 seconds)

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['accountId'])) {
    header("Location: ../Client Section/login.php?message=Please log in."); // Redirect with message
    exit;
} 

// $session_id = session_id();
// $accountid = $_SESSION['accountId'];

// // Query to check if session exists in the database
// $session_stmt = $conn->prepare("SELECT * FROM user_sessions WHERE session_id = ? AND accountid = ?");
// $session_stmt->bind_param("si", $session_id, $accountid);
// $session_stmt->execute();
// $session_result = $session_stmt->get_result();

// if ($session_result->num_rows !== 1) {
//     session_unset();
//     session_destroy();
//     echo json_encode(['status' => 'expired']);
//     exit;
// }

// $session = $session_result->fetch_assoc();

// $last_activity = strtotime($session['last_activity']);
// if ((time() - $last_activity) > $inactive) {
//     // Session has timed out, destroy it
//     $delete_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
//     $delete_stmt->bind_param("s", $session_id);
//     $delete_stmt->execute();
//     $delete_stmt->close();

//     // Destroy session
//     session_unset();
//     session_destroy();
//     echo json_encode(['status' => 'expired']);
//     exit;
// }

// // Update the last activity time to the current time (keep session active)
// $update_stmt = $conn->prepare("UPDATE user_sessions SET last_activity = NOW() WHERE session_id = ?");
// $update_stmt->bind_param("s", $session_id);
// $update_stmt->execute();
// $update_stmt->close();

// // Session is active, continue with normal processing (e.g., load user data, etc.)
// // echo json_encode(['status' => 'active']);
?>
