<?php
require "../../conn.php"; // Include the DB connection
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if $conn is valid
    if (!$conn) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
        exit;
    }

    // Sanitize and validate the input
    $comment = !empty($_POST['comment']) ? $_POST['comment'] : null;
    $transactNo = !empty($_POST['id']) ? $_POST['id'] : null;

    // Check if the comment and transactNo are not empty
    if ($comment && $transactNo) {
        // Insert the comment into the bookingcomments table for the specific booking ID
        $query = "INSERT INTO bookingcomments (transactNo, comment) VALUES (?, ?)";
        
        // Prepare and bind the statement
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('ss', $transactNo, $comment); // 's' for string (transactNo and comment)

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Comment submitted successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to submit comment.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Comment or ID is missing.']);
    }
}
?>
