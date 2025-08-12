<?php
// fetchTransactionTable.php
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection
include_once '../../../../conn.php'; // Adjust path as needed

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);
$showCurrentDate = !empty($input['showCurrentDate']); // cast to boolean

// Debug log
error_log('Received request - showCurrentDate: ' . ($showCurrentDate ? 'true' : 'false'));

// Build date filter
$dateFilter = '';

if ($showCurrentDate) {
    $currentDate = date('Y-m-d'); // format with dashes
    $dateFilter = "AND f.flightDepartureDate >= '$currentDate'";
} else {
    $dateFilter = '';
}

$counts = [];

try {
    // Single query to get all counts at once
    $sql = "SELECT 
              COUNT(*) AS totalBookings,
              SUM(b.status = 'Pending') AS Pending,
              SUM(b.status = 'Reserved') AS Reserved,
              SUM(b.status = 'Confirmed') AS Confirmed,
              SUM(b.status = 'Cancelled') AS Cancelled
            FROM booking b
            JOIN flight f ON f.flightId = b.flightId
            WHERE 1=1 $dateFilter";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        // Query error
        http_response_code(500);
        $error = ['error' => 'Database query failed: ' . mysqli_error($conn)];
        error_log('Error: ' . json_encode($error));
        echo json_encode($error);
        exit;
    }

    $row = mysqli_fetch_assoc($result);
    $counts['all'] = (int)$row['totalBookings'];
    $counts['Pending'] = (int)$row['Pending'];
    $counts['Reserved'] = (int)$row['Reserved'];
    $counts['Confirmed'] = (int)$row['Confirmed'];
    $counts['Cancelled'] = (int)$row['Cancelled'];

    // Debug log
    error_log('Counts calculated: ' . json_encode($counts));

    // Return JSON response
    echo json_encode($counts);

} catch (Exception $e) {
    http_response_code(500);
    $error = ['error' => 'Unexpected error: ' . $e->getMessage()];
    error_log('Error: ' . json_encode($error));
    echo json_encode($error);
}

if (isset($conn)) {
    mysqli_close($conn);
}
?>
