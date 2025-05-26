<?php
require_once '../../../conn.php'; // include your DB connection file

header('Content-Type: application/json');

// Initialize debug log file
$debugFile = __DIR__ . '/debug_sql.log';

// Prepare agent columns dynamically
$agentsSql = "SELECT branchAgentCode FROM branch WHERE branchAgentCode IS NOT NULL AND branchAgentCode != ''";
$agentResult = $conn->query($agentsSql);

$agentColumns = [];
$agentCodes = [];

if ($agentResult) {
    while ($agent = $agentResult->fetch_assoc()) {
        $code = $conn->real_escape_string($agent['branchAgentCode']); // prevent injection
        $agentCodes[] = $code;

        $agentColumns[] = "
            IFNULL(SUM(CASE WHEN b.bookingType = 'Package' 
                AND (b.status = 'Confirmed' OR b.status = 'Reserved') 
                AND (a.agentCode = '$code' OR c.clientCode = '$code') 
                AND (a.agentType = 'Retailer' OR c.clientType = 'Retailer') 
                THEN b.pax ELSE 0 END), 0) AS `{$code}_AL`";

        $agentColumns[] = "
            IFNULL(SUM(CASE WHEN b.bookingType = 'Package' 
                AND (b.status = 'Confirmed' OR b.status = 'Reserved') 
                AND (a.agentCode = '$code' OR c.clientCode = '$code') 
                AND (a.agentType = 'Wholeseller' OR c.clientType = 'Wholeseller') 
                THEN b.pax ELSE 0 END), 0) AS `{$code}_LO`";
    }
} else {
    file_put_contents($debugFile, "Failed to fetch agents: " . $conn->error);
}

$agentColumnsSql = implode(",\n", $agentColumns);

// Main query
$sql = "SELECT 
            f.flightId, f.is_active, f.origin, 
            f.flightDepartureDate AS Start, f.returnDepartureDate AS End,
            CONCAT(
                IF(e.lName IS NOT NULL AND e.lName != '', CONCAT(e.lName, ', '), ''),
                e.fName,
                IF(e.mName IS NOT NULL AND e.mName != '' AND e.lName IS NOT NULL AND e.lName != '', CONCAT(' ', LEFT(e.mName, 1)), '')
            ) AS TeamOP,
            e.colorCode, 
            f.availSeats AS FlightSeat, 
            GREATEST(f.availSeats - IFNULL(SUM(CASE 
              WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
              AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0), 0) AS AvailSeats, 
            IF((f.availSeats - IFNULL(SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
              AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)) < 0, 
              ABS(f.availSeats - IFNULL(SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)), 0) AS AdditionalSeats,
            SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') AND b.bookingType = 'Package' 
              AND (a.agentType = 'Retailer' OR c.clientType = 'Retailer') THEN b.pax ELSE 0 END) AS `Air+Land`,
            SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') AND b.bookingType = 'Package' 
              AND (a.agentType = 'Wholeseller' OR c.clientType = 'Wholeseller') THEN b.pax ELSE 0 END) AS `LandOnly`,
            f.wholesalePrice AS WholesalePrice, f.flightPrice AS RetailPrice, f.landPrice AS landPrice"
            . ($agentColumnsSql ? ",\n" . $agentColumnsSql : "") . "
        FROM employee e
        RIGHT JOIN flight f ON f.employeeId = e.employeeId
        LEFT JOIN booking b ON b.flightId = f.flightId
        LEFT JOIN package p ON f.packageId = p.packageId
        LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
        LEFT JOIN client c ON b.accountType = 'Client' AND b.accountId = c.accountId
        WHERE f.flightDepartureDate >= CURDATE()
        GROUP BY f.flightId
        ORDER BY f.flightDepartureDate";

// Debug: Log the final SQL query
file_put_contents($debugFile, "Executing SQL:\n" . $sql . "\n\n");

$result = $conn->query($sql);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    // Log query error
    file_put_contents($debugFile, "Query failed: " . $conn->error . "\n\n", FILE_APPEND);
}

echo json_encode([
    'agents' => $agentCodes,
    'flights' => $data
]);
?>
