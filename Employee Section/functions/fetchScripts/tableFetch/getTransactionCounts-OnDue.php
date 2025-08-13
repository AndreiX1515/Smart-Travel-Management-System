<?php
// fetchTransactionTable.php
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../../../conn.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$showCurrentDate = !empty($input['showCurrentDate']); // cast to boolean

$dateFilter = '';
if ($showCurrentDate) {
    $currentDate = date('Y-m-d');
    $dateFilter = "AND f.flightDepartureDate >= '$currentDate'";
}

$counts = [];

try {
    // Single query with CASE to calculate multiple ranges
    $sql = "
        SELECT 
            COUNT(*) AS totalBookings,
            SUM(b.status = 'Pending') AS Pending,
            SUM(b.status = 'Reserved') AS Reserved,
            SUM(b.status = 'Confirmed') AS Confirmed,
            SUM(b.status = 'Cancelled') AS Cancelled,

            -- Remaining days filters
            SUM(DATEDIFF(f.flightDepartureDate, CURDATE()) < 0) AS pastDue,
            SUM(DATEDIFF(f.flightDepartureDate, CURDATE()) <= 5 AND DATEDIFF(f.flightDepartureDate, CURDATE()) >= 0) AS within5days,
            SUM(DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 6 AND 10) AS within10days,
            SUM(DATEDIFF(f.flightDepartureDate, CURDATE()) BETWEEN 11 AND 20) AS within20days,
            SUM(DATEDIFF(f.flightDepartureDate, CURDATE()) > 30) AS over30days
        FROM booking b
        JOIN flight f ON f.flightId = b.flightId
        WHERE b.status = 'Confirmed' $dateFilter
    ";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
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

    // Remaining days categories
    $counts['pastDue'] = (int)$row['pastDue'];
    $counts['fiveDays'] = (int)$row['within5days'];
    $counts['tenDays'] = (int)$row['within10days'];
    $counts['twentyDays'] = (int)$row['within20days'];
    $counts['thirtyDays'] = (int)$row['over30days'];

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
