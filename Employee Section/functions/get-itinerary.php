<?php
require '../../conn.php'; // Ensure this defines $conn as a MySQLi connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set Content-Type for JSON
header('Content-Type: application/json');

// Logging helper
function log_debug($message) {
    error_log("[DEBUG] " . $message);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    log_debug("Received ID: $id");

    // Prepare and execute the query safely
    $stmt = $conn->prepare("SELECT * FROM itineraries WHERE itineraryId = ?");
    
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            log_debug("Itinerary found: " . json_encode($row));
            echo json_encode($row);
        } else {
            log_debug("No itinerary found with ID: $id");
            http_response_code(404);
            echo json_encode(["error" => "Itinerary not found."]);
        }

        $stmt->close();
    } else {
        log_debug("DB Prepare Error: " . $conn->error);
        http_response_code(500);
        echo json_encode(["error" => "Database error occurred."]);
    }
} else {
    log_debug("No ID provided in request");
    http_response_code(400);
    echo json_encode(["error" => "No ID provided."]);
}

$conn->close();
