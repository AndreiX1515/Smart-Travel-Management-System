<?php
require_once '../../conn.php';

// Enable error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/delete_voucher_error.log');
error_reporting(E_ALL);

error_log("🔍 Incoming DELETE request for voucher");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("📥 POST request received");

    if (isset($_POST['itineraryId'])) {
        $voucherId = $_POST['itineraryId'];
        error_log("✅ Received voucherId: $voucherId");

        $stmt = $conn->prepare("DELETE FROM vouchers WHERE voucherId = ?");
        if (!$stmt) {
            error_log("❌ Prepare failed: " . $conn->error);
            echo "Error preparing statement: " . $conn->error;
            exit;
        }

        $stmt->bind_param("i", $voucherId);

        if ($stmt->execute()) {
            error_log("🗑️ Successfully deleted voucherId: $voucherId");
            echo "Deleted successfully";
        } else {
            error_log("❌ Execute failed: " . $stmt->error);
            echo "Error deleting voucher: " . $stmt->error;
        }

        $stmt->close();
    } else {
        error_log("⚠️ voucherId not set in POST");
        echo "voucherId not provided.";
    }

    $conn->close();
} else {
    error_log("❌ Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo "Invalid request";
}
