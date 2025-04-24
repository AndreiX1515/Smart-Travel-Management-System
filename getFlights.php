<?php
include 'conn.php'; // include your DB connection

// Query to get agent columns
$sql = "SELECT branchName, branchAgentCode FROM branch WHERE branchAgentCode IS NOT NULL AND branchAgentCode != ''";
$result = $conn->query($sql);

$agentColumns = '';
while ($row = $result->fetch_assoc()) {
    $agentCode = $row['branchAgentCode'];

    // Debug: Log the agentCode being processed
    error_log("Processing agent code: $agentCode");

    // Make sure AL and LO columns are both included
    $agentColumns .= "IFNULL(SUM(CASE WHEN b.bookingType = 'Package' 
        AND (b.status = 'Confirmed' OR b.status = 'Reserved')
        AND (a.agentCode = '$agentCode' OR c.clientCode = '$agentCode') 
        AND (a.agentType = 'Retailer' OR c.clientType = 'Retailer')
        THEN b.pax ELSE 0 END), 0) AS `{$agentCode}_AL`, 

    IFNULL(SUM(CASE WHEN b.bookingType = 'Package' 
        AND (b.status = 'Confirmed' OR b.status = 'Reserved')
        AND (a.agentCode = '$agentCode' OR c.clientCode = '$agentCode')
        AND (a.agentType = 'Wholeseller' OR c.clientType = 'Wholeseller')
        THEN b.pax ELSE 0 END), 0) AS `{$agentCode}_LO`, ";
}
$agentColumns = rtrim($agentColumns, ', ');

// Debugging: Log the generated agent columns SQL
error_log("Generated Agent Columns SQL: " . $agentColumns);

// Main query to fetch flight details
$sql = "SELECT f.flightId, f.is_active, f.origin, f.flightDepartureDate AS Start, f.returnDepartureDate AS End,
    CONCAT(e.lName, ', ', e.fName, 
        IF(e.mName IS NOT NULL AND e.mName != '', CONCAT(' ', LEFT(e.mName, 1)), '')) AS TeamOP,
    f.availSeats AS FlightSeat, 
    GREATEST(f.availSeats - IFNULL(SUM(CASE 
        WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
        AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0), 0) AS AvailSeats, 
    IF((f.availSeats - IFNULL(SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
        AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)) < 0, 
        ABS(f.availSeats - IFNULL(SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
            AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)), 0) AS AdditionalSeats,
    f.flightPrice AS RetailPrice,
    $agentColumns
    FROM employee e
    RIGHT JOIN flight f ON f.employeeId = e.employeeId
    LEFT JOIN booking b ON b.flightId = f.flightId
    LEFT JOIN package p ON f.packageId = p.packageId
    LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
    LEFT JOIN client c ON b.accountType = 'Client' AND b.accountId = c.accountId
    WHERE f.flightDepartureDate >= CURDATE()
    GROUP BY f.flightId, f.is_active, f.origin, f.flightDepartureDate, f.returnDepartureDate, f.availSeats, 
        f.wholesalePrice, f.flightPrice, p.packagePrice, f.landPrice
    ORDER BY f.flightDepartureDate";

// Debug: Log the final SQL query to check if it’s correctly formed
error_log("Final SQL Query: " . $sql);

$result = $conn->query($sql);
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Debug: Log the row content to check the returned fields for AL and LO
        error_log("Returned Row: " . print_r($row, true));  // This will print the row into your PHP error log
        
        // Check if both AL and LO columns are set for each agent
        foreach ($row as $key => $value) {
            if (strpos($key, '_AL') !== false) {
                error_log("AL Column Found: $key => $value");
            }
            if (strpos($key, '_LO') !== false) {
                error_log("LO Column Found: $key => $value");
            }
        }
        
        $data[] = $row;
    }
} else {
    error_log("No results found.");
}

header('Content-Type: application/json');
echo json_encode($data);
?>
