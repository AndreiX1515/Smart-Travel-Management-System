<?php
session_start();

// Check if the session is active
if (isset($_SESSION['accountId'])) {
    $accountId = $_SESSION['accountId'];

    // Database connection
    require "../../conn.php";

    // Query to get account type
    $stmt = $conn->prepare("SELECT accountType FROM accounts WHERE accountId = ?");
    
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Error preparing SQL statement."]);
        exit;
    }

    $stmt->bind_param("i", $accountId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if account exists and fetch account type
    if ($row = $result->fetch_assoc()) {
        $accountType = $row['accountType']; // 'admin', 'agent', 'employee', 'guest'
    
        // Check if account type is guest, then unset session variables
        if ($accountType === 'guest') {
            unset($_SESSION['accountId']);
            unset($_SESSION['flightId']);
        }
    
        // Return account type in the response
        echo json_encode(["status" => "success", "accountType" => $accountType]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Account not found."]);
        exit;
    }
    

} else {
    echo json_encode(["status" => "error", "message" => "No session found."]);
    exit;
}
?>
