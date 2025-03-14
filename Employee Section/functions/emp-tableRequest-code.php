<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../../conn.php"; // Move up to the parent directory

header("Content-Type: application/json"); // Ensure JSON response

$response = [
    "status" => "error",
    "message" => "Invalid request!"
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['requestId'], $_POST['requestStatus'], $_POST['requestHandlingFee'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields!"
        ]);
        exit;
    }

    $requestId = $_POST['requestId'];
    $requestStatus = $_POST['requestStatus'];
    $requestRemarks = $_POST['requestRemarks'] ?? NULL;
    $accountId = $_SESSION['employee_accountId'];
    $requestHandlingFee = $_POST['requestHandlingFee'];

    // Define user-friendly labels for statuses
    $statusLabels = [
        "Pending" => "Request is pending review",
        "Confirmed" => "Request successfully approved",
        "Rejected" => "Request has been rejected",
        "Completed" => "Request is now completed"
    ];

    // Determine the friendly label for the current status
    $statusLabel = $statusLabels[$requestStatus] ?? "Success";

    try {
        // Set the session variable for the current user in MySQL
        $conn->query("SET @current_user_id = $accountId");

        // Start a transaction
        $conn->begin_transaction();

        // Prepare the SQL statement for updating the request status
        $sql1 = "UPDATE request SET requestStatus = ?, requestRemarks = ?, handlingFee = ? WHERE requestId = ?";
        $stmt1 = $conn->prepare($sql1);

        if (!$stmt1) {
            throw new Exception("SQL preparation failed: " . $conn->error);
        }

        // Bind parameters and execute the update
        $stmt1->bind_param('ssdi', $requestStatus, $requestRemarks, $requestHandlingFee, $requestId);
        
        if (!$stmt1->execute()) {
            throw new Exception("Database error: " . $stmt1->error);
        }

        // Commit the transaction if no errors
        $conn->commit();  
        
        $response = [
            "status" => "success",
            "message" => "Request ID $requestId updated successfully to: $requestStatus",
            "requestStatus" => $requestStatus, // Raw status
            "statusLabel" => $statusLabel      // Friendly label
        ];
        
    } catch (Exception $e) {
        $conn->rollback(); // Rollback the transaction on failure
        $response = [
            "status" => "error",
            "message" => $e->getMessage()
        ];
    }
}

echo json_encode($response);
exit;
?>
