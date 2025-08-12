<?php
// Prevent BOM or accidental whitespace before output
if (ob_get_length())
    ob_clean();
ob_start();

ini_set('display_errors', 1); // Turn on for debugging (disable in production)
error_reporting(E_ALL);

header('Content-Type: application/json');

// Debug helper
function debug_log($msg)
{
    file_put_contents(__DIR__ . '/debug.log', "[" . date('Y-m-d H:i:s') . "] $msg\n", FILE_APPEND);
}

require '../../../../conn.php'; // Ensure $conn is properly defined

$dateFilter = $_GET['date'] ?? '';
$whereClauses = [];

if ($dateFilter) {
    $safeDate = $conn->real_escape_string($dateFilter);
    $whereClauses[] = "DATE(f.flightDepartureDate) = '$safeDate'";
    debug_log("Date filter applied: $safeDate");
}

$where = count($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
debug_log("WHERE clause: $where");

$sql = "SELECT b.transactNo, DATE_FORMAT(f.flightDepartureDate, '%Y.%m.%d') AS departureDate, f.returnDepartureDate AS returnDate, 
        b.status AS bookingStatus, CONCAT(f.flightDepartureDate, ' | ', f.returnDepartureDate) AS FlightDate, 
        p.packageName AS PackageName, b.bookingDate, b.pax AS TotalPax,  
        b.totalPrice AS PackagePrice, br.branchName as branchName, COALESCE(SUM(pa.amount), 0) AS TotalAmountPaid,
        CONCAT(a.lName, ', ', a.fName, ' ', IFNULL(CONCAT(SUBSTRING(a.mName, 1, 1), '.'), '')) AS agentName,
        COALESCE(SUM(r.requestCost), 0) AS TotalRequestAmount
        FROM booking b
        JOIN branch br ON b.agentCode = br.branchAgentCode
        JOIN flight f ON f.flightId = b.flightId
        JOIN package p ON p.packageId = b.packageId
        LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
        LEFT JOIN company c ON a.companyId = c.companyId
        LEFT JOIN client cl ON b.accountType = 'Client' AND b.accountId = cl.accountId
        LEFT JOIN company cc ON cl.companyId = cc.companyId
        LEFT JOIN payment pa ON pa.transactNo = b.transactNo AND pa.paymentStatus = 'Approved'
        LEFT JOIN request r ON r.transactNo = b.transactNo AND r.requestStatus = 'Confirmed'
        $where
        GROUP BY b.transactNo, f.flightDepartureDate, f.returnDepartureDate, b.status, 
                 p.packageName, b.bookingDate, b.pax, b.totalPrice, a.lName, a.fName, a.mName, br.branchName
        ORDER BY CAST(SUBSTRING_INDEX(b.transactNo, '-', -1) AS UNSIGNED)";

debug_log("SQL Query: $sql");

$result = $conn->query($sql);

if (!$result) {
    $errorMsg = "SQL Error: " . $conn->error;
    debug_log($errorMsg);
    echo json_encode(['error' => $errorMsg]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $packagePrice = $row['PackagePrice'] ?? 0;
    $requestTotal = $row['TotalRequestAmount'] ?? 0;
    $amountPaid = $row['TotalAmountPaid'] ?? 0;
    $balance = max(($packagePrice + $requestTotal) - $amountPaid, 0);

    $data[] = [
        'transactNo' => $row['transactNo'],
        'branchName' => $row['branchName'],
        'departureDate' => $row['departureDate'],
        'totalPax' => $row['TotalPax'],
        'packagePrice' => number_format($packagePrice, 2),
        'requestTotal' => number_format($requestTotal, 2),
        'amountPaid' => number_format($amountPaid, 2),
        'balance' => number_format($balance, 2),
        'bookingDate' => date('m.d.Y', strtotime($row['bookingDate'])),
        'status' => $row['bookingStatus']
    ];
}

debug_log("Total records fetched: " . count($data));

ob_clean(); // Clear any debug output
echo json_encode($data);
exit;
