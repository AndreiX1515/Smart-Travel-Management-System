<?php
session_start();
include 'conn.php'; // Include your database connection

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate the input
    if (isset($input['username'])) {
        $username = $input['username'];
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No username provided.']);
        exit;
    }
    
    // Retrieve account ID from the session
    if (!isset($_SESSION['accountid'])) {
        echo json_encode(['status' => 'error', 'message' => 'No session found.']);
        exit;
    }
    
    $accountid = $_SESSION['accountid'];

    // Remove the session from the database
    $session_id = session_id();
    $delete_stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ? AND accountid = ?");
    $delete_stmt->bind_param("si", $session_id, $accountid);
    $delete_stmt->execute();
    $delete_stmt->close();

    // Destroy the session
    session_unset();
    session_destroy();

    echo json_encode(['status' => 'success', 'message' => 'Session successfully removed.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
