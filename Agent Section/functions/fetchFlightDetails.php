<?php
// Set proper headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require "../../conn.php";
session_start();

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check if required parameters exist
if (!isset($_POST['flightId']) || !isset($_POST['agentType'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters: flightId and agentType']);
    exit;
}

$flightId = intval($_POST['flightId']); // Sanitize input
$agentType = trim($_POST['agentType']);

// Validate agent type
if (!in_array($agentType, ['Retailer', 'Wholeseller'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid agent type']);
    exit;
}


try {
    // Use prepared statement to prevent SQL injection
    if ($agentType === 'Retailer') {
        $sql = "SELECT f.flightPrice as flightPrice, f.origin as origin, f.packageId as packageId, 
                       f.landPrice as packagePrice, p.packageName as packageName
                FROM flight f
                JOIN package p ON f.packageId = p.packageId
                WHERE f.flightId = ?";
    } else { // Wholeseller
        $sql = "SELECT f.wholesalePrice as flightPrice, f.origin as origin, f.packageId as packageId, 
                       f.landPrice as packagePrice, p.packageName as packageName
                FROM flight f
                JOIN package p ON f.packageId = p.packageId
                WHERE f.flightId = ?";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("i", $flightId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = [
            'success' => true,
            'data' => [
                'packageName' => $row['packageName'],
                'packagePrice' => $row['packagePrice'],
                'flightPrice' => $row['flightPrice'],
                'origin' => $row['origin'],
                'packageId' => $row['packageId']
            ]
        ];
        
    } else {
        $response = [
            'success' => false,
            'data' => [
                'packageName' => null,
                'packagePrice' => null,
                'flightPrice' => null,
                'origin' => null,
                'packageId' => null
            ],
            'message' => 'Flight not found'
        ];
    }

    $stmt->close();
    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>