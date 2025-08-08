<?php

ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json');
require '../../../../conn.php'; // Make sure $conn is defined here

$dateFilter = $_GET['date'] ?? '';
$whereClauses = [];

if ($dateFilter) {
    $whereClauses[] = "DATE(f.flightDepartureDate) = '" . $conn->real_escape_string($dateFilter) . "'";
}

$where = count($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

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

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $packagePrice = $row['PackagePrice'] ?? 0;
    $requestTotal = $row['TotalRequestAmount'] ?? 0;
    $amountPaid   = $row['TotalAmountPaid'] ?? 0;
    $balance      = max(($packagePrice + $requestTotal) - $amountPaid, 0);

    $data[] = [
        'transactNo'      => $row['transactNo'],
        'branchName'      => $row['branchName'],
        'departureDate'   => $row['departureDate'],
        'totalPax'        => $row['TotalPax'],
        'packagePrice'    => number_format($packagePrice, 2),
        'requestTotal'    => number_format($requestTotal, 2),
        'amountPaid'      => number_format($amountPaid, 2),
        'balance'         => number_format($balance, 2),
        'bookingDate'     => date('m.d.Y', strtotime($row['bookingDate'])),
        'status'          => $row['bookingStatus']
    ];
}

echo json_encode($data);
exit;
