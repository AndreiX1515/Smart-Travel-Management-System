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
    $transactNo = !empty($_POST['transactNo']) ? $_POST['transactNo'] : null;

    // Check if the comment and transactNo are not empty
    if ($comment && $transactNo) {
        // Update the comment in the bookingcomments table
        $query = "UPDATE bookingcomments SET comment = ? WHERE transactNo = ?";
        
        // Prepare and bind the statement
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('ss', $comment, $transactNo); // 's' for string (comment and transactNo)

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Comment updated successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update comment.']);
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
