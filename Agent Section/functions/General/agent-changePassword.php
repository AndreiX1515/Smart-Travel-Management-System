<?php
session_start();
require "../../../conn.php"; // Database connection

$accountId = $_SESSION['agent_accountId']; // Get account ID from session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['currentPassword'];
    

    // Check if current password is provided
    if (empty($currentPassword)) {
        // Respond with an error message if the current password is not provided
        echo json_encode([
            'status' => 'error',
            'message' => 'Current password is required.',
            'currentPassword' => $currentPassword 
        ]);
        
    } else {
        // Verify password against the database
        $stmt = $conn->prepare("SELECT password, emailAddress FROM accounts WHERE accountId = ?");
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $stmt->bind_result($storedPassword, $emailAddress); // Bind both password and emailAddress
        $stmt->fetch();
        $stmt->close();
    
        // Check if password matches
        if ($currentPassword !== $storedPassword) {
            // Respond with an error message if the current password is incorrect
            echo json_encode([
                'status' => 'error',
                'message' => 'Current password is incorrect.',
                'currentPassword' => $currentPassword // Include the current password in the response (for debugging)
            ]);
            
        } else {
            $_SESSION['emailAddress'] = $emailAddress;

            echo json_encode([
                'status' => 'success',
                'message' => 'Sending OTP to your email to verify change password...',
                'currentPassword' => $currentPassword,
                'accountId' => $accountId,
                'emailAddress' => $emailAddress
            ]);
        }
    }
    

    $conn->close(); // Close connection
}
?>
