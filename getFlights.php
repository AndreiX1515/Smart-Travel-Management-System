<?php
include "conn.php"; // Database connection

// Decode the agent columns sent via AJAX
$agentColumns = isset($_POST['agentColumns']) ? json_decode($_POST['agentColumns'], true) : [];

// Debugging: Check if agentColumns is correctly received
error_log("Agent Columns: " . print_r($agentColumns, true));

if (empty($agentColumns)) {
    die("No agent columns received or invalid format.");
}

$agentColumnSQL = ''; // Initialize the agent column SQL portion
foreach ($agentColumns as $agent) {
    if (isset($agent['agentCode'])) {
        $agentCode = $conn->real_escape_string($agent['agentCode']); // Escape agent code for safety
        $agentColumnSQL .= "
            IFNULL(SUM(CASE 
                WHEN b.bookingType = 'Package' 
                AND (b.status = 'Confirmed' OR b.status = 'Reserved') 
                AND (a.agentCode = '$agentCode' OR c.clientCode = '$agentCode') 
                AND (a.agentType = 'Retailer' OR c.clientType = 'Retailer') 
                THEN b.pax ELSE 0 END), 0) AS `{$agentCode}_AL`,

            IFNULL(SUM(CASE 
                WHEN b.bookingType = 'Package' 
                AND (b.status = 'Confirmed' OR b.status = 'Reserved') 
                AND (a.agentCode = '$agentCode' OR c.clientCode = '$agentCode') 
                AND (a.agentType = 'Wholeseller' OR c.clientType = 'Wholeseller') 
                THEN b.pax ELSE 0 END), 0) AS `{$agentCode}_LO`,"; // Add agent columns for AL and LO
    }
}

// Remove trailing comma
$agentColumnSQL = rtrim($agentColumnSQL, ','); // Ensure no trailing comma

// Debugging: Check the final SQL
error_log("Generated SQL with Agent Columns: " . $agentColumnSQL);

$sql = "
    SELECT 
        f.flightId, f.is_active, f.origin, 
        f.flightDepartureDate AS Start, f.returnDepartureDate AS End,

        CONCAT(
            IF(e.lName IS NOT NULL AND e.lName != '', CONCAT(e.lName, ', '), ''),
            e.fName,
            IF(e.mName IS NOT NULL AND e.mName != '' AND e.lName IS NOT NULL AND e.lName != '', CONCAT(' ', LEFT(e.mName, 1)), '')
        ) AS TeamOP,

        e.colorCode, 
        f.availSeats AS FlightSeat,

        GREATEST(
            f.availSeats - IFNULL(SUM(CASE 
                WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0), 0
        ) AS AvailSeats,

        IF(
            (f.availSeats - IFNULL(SUM(CASE 
                WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)) < 0,
            ABS(f.availSeats - IFNULL(SUM(CASE 
                WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)),
            0
        ) AS AdditionalSeats,

        SUM(CASE 
            WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
            AND b.bookingType = 'Package' 
            AND (a.agentType = 'Retailer' OR c.clientType = 'Retailer') 
            THEN b.pax ELSE 0 END) AS `Air+Land`,

        SUM(CASE 
            WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
            AND b.bookingType = 'Package' 
            AND (a.agentType = 'Wholeseller' OR c.clientType = 'Wholeseller') 
            THEN b.pax ELSE 0 END) AS `LandOnly`,

        f.wholesalePrice AS WholesalePrice,
        f.flightPrice AS RetailPrice,
        p.packagePrice AS LandArrangement,
        f.landPrice AS landPrice,

        $agentColumnSQL

    FROM employee e
    RIGHT JOIN flight f ON f.employeeId = e.employeeId
    LEFT JOIN booking b ON b.flightId = f.flightId
    LEFT JOIN package p ON f.packageId = p.packageId
    LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
    LEFT JOIN client c ON b.accountType = 'Client' AND b.accountId = c.accountId

    WHERE f.flightDepartureDate >= CURDATE()
    GROUP BY f.flightId
    ORDER BY f.flightDepartureDate
";

// Debugging: Check the final SQL query
error_log("Final SQL Query: " . $sql);

$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>
