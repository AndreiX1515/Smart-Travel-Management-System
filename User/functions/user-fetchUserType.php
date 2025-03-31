<?php
require_once "../conn.php";
session_start();

// Force JSON response type
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Ensure cross-origin requests work if needed

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering to prevent accidental output before JSON
ob_start();

// Check if request is POST and userType is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['userType'])) {
    $allowedUserTypes = ['user', 'client', 'agent'];
    $userType = trim($_POST['userType']);

    if (in_array($userType, $allowedUserTypes, true)) {
        $_SESSION['userType'] = $userType;
        
        // Clear buffer before outputting JSON
        ob_end_clean();
        echo json_encode(["status" => "success", "message" => "User type set successfully"]);
        exit;
        
    } else {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Invalid user type"]);
        exit;
    }
}

// If request is invalid
ob_end_clean();
echo json_encode(["status" => "error", "message" => "Invalid request"]);
exit;
?>
