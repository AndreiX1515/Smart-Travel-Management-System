<?php
session_start();
include 'conn.php'; // Your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Prepare and execute the query to check if the email exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    // Return JSON response
    echo json_encode(['exists' => $count > 0]);

    $stmt->close();
    $conn->close();
}
?>
