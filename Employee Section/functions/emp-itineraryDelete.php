<?php
require_once '../../conn.php';

// Enable error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/delete_itinerary_error.log');
error_reporting(E_ALL);

error_log("🔍 Incoming DELETE request");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("📥 POST request received");

    if (isset($_POST['itineraryId'])) {
        $itineraryId = $_POST['itineraryId'];
        error_log("✅ Received itineraryId: $itineraryId");

        $stmt = $conn->prepare("DELETE FROM itineraries WHERE itineraryId = ?");
        if (!$stmt) {
            error_log("❌ Prepare failed: " . $conn->error);
            echo "Error preparing statement: " . $conn->error;
            exit;
        }

        $stmt->bind_param("i", $itineraryId);

        if ($stmt->execute()) {
            error_log("🗑️ Successfully deleted itineraryId: $itineraryId");
            echo "Deleted successfully";
        } else {
            error_log("❌ Execute failed: " . $stmt->error);
            echo "Error deleting itinerary: " . $stmt->error;
        }

        $stmt->close();
    } else {
        error_log("⚠️ itineraryId not set in POST");
        echo "itineraryId not provided.";
    }

    $conn->close();
} else {
    error_log("❌ Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo "Invalid request";
}
