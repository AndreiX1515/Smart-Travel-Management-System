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
    $transactNo = !empty($_POST['transactNo']) ? $_POST['transactNo'] : null;

    // Check if the transactNo is valid
    if ($transactNo) {
        // Prepare the delete query
        $query = "DELETE FROM bookingcomments WHERE transactNo = ?";  // Assuming 'transactNo' is the identifier for the comment

        // Prepare and bind the statement
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('s', $transactNo);  // 's' for string (transactNo)

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Comment deleted successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete comment.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Transaction ID is missing.']);
    }
}
?>
