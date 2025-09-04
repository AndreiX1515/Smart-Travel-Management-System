<?php
// api/update-flight-status.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

header('Content-Type: application/json');
require_once '../../../conn copy.php';

try {
    // Check if request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }

    // Validate required fields
    if (!isset($input['flightId']) || !isset($input['status'])) {
        throw new Exception('Missing required fields: flightId and status');
    }

    $flightId = (int)$input['flightId'];
    $status = (int)$input['status'];

    // Validate status value (should be 0 or 1)
    if ($status !== 0 && $status !== 1) {
        throw new Exception('Status must be 0 or 1');
    }

    // Prepare and execute update query
    $stmt = $conn->prepare("UPDATE flight SET is_active = ? WHERE flightId = ?");
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }

    $stmt->bind_param("ii", $status, $flightId);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to update flight status: ' . $stmt->error);
    }

    // Check if any rows were affected
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if ($affectedRows === 0) {
        throw new Exception('No flight found with the specified ID or no changes made');
    }

    // Log the update (optional)
    error_log("Flight status updated: Flight ID $flightId, Status: $status");

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Flight status updated successfully',
        'flightId' => $flightId,
        'newStatus' => $status,
        'affectedRows' => $affectedRows,
        'timestamp' => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>