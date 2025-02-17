<?php
require "../conn.php";

header('Content-Type: application/json');
ini_set('display_errors', 0); // Prevent PHP errors from breaking JSON

// Initialize response
$response = [
    "success" => false,
    "message" => "",
    "data" => []
];

try {
    $agentColumns = '';
    $agentData = []; // Store agent info for JSON response

    // Fetch agent data
    $sql = "SELECT branchName, branchAgentCode FROM branch WHERE branchAgentCode IS NOT NULL AND branchAgentCode != ''";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Agent query failed: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $agentName = $row['branchName'];
        $agentCode = $row['branchAgentCode'];

        // Dynamic agent columns
        $agentColumns .= "IFNULL(SUM(CASE WHEN b.bookingType = 'Package' 
                                    AND (b.status = 'Confirmed' OR b.status = 'Reserved')
                                    AND b.agentCode = '{$agentCode}' AND a.agentType = 'Retailer' 
                                    THEN b.pax ELSE 0 END), 0) AS `{$agentCode}_AL`, 
                          IFNULL(SUM(CASE WHEN b.bookingType = 'Package' 
                                    AND (b.status = 'Confirmed' OR b.status = 'Reserved')
                                    AND b.agentCode = '{$agentCode}' AND a.agentType = 'Wholeseller' 
                                    THEN b.pax ELSE 0 END), 0) AS `{$agentCode}_LO`, ";

        $agentData[] = ['name' => $agentName, 'code' => $agentCode];
    }

    // Remove the last comma
    $agentColumns = rtrim($agentColumns, ', ');

    // Ensure SQL is valid even if no agent columns exist
    if (!empty($agentColumns)) {
        $agentColumns = ", " . $agentColumns;
    }

    // Fetch flight data
    $sql = "SELECT f.flightId, f.is_active, f.origin, f.flightDepartureDate AS Start, f.returnDepartureDate AS End,
                    CONCAT(e.lName, ', ', e.fName, IF(e.mName IS NOT NULL AND e.mName != '', CONCAT(' ', LEFT(e.mName, 1)), '')) AS TeamOP,
                    f.availSeats AS FlightSeat, 
                    GREATEST(f.availSeats - IFNULL(SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                                            AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0), 0) AS AvailSeats, 
                    IF((f.availSeats - IFNULL(SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                                            AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)) < 0, 
                        ABS(f.availSeats - IFNULL(SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                                              AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)), 0) AS AdditionalSeats,
                    SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                                AND b.bookingType = 'Package' AND a.agentType = 'Retailer' THEN b.pax ELSE 0 END) AS `AirLand`,
                    SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') AND b.bookingType = 'Package' 
                                AND a.agentType = 'Wholeseller' THEN b.pax ELSE 0 END) AS `LandOnly`,
                    f.wholesalePrice AS WholesalePrice, f.flightPrice AS RetailPrice, p.packagePrice AS LandArrangement,
                    f.landPrice AS landPrice
                    $agentColumns
              FROM employee e
              RIGHT JOIN flight f ON f.employeeId = e.employeeId
              LEFT JOIN booking b ON b.flightId = f.flightId
              LEFT JOIN package p ON f.packageId = p.packageId
              LEFT JOIN agent a ON b.agentId = a.agentId
              WHERE f.flightDepartureDate >= CURDATE()
              GROUP BY f.flightId
              ORDER BY f.flightDepartureDate";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Flight query failed: " . $conn->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row['agents'] = $agentData; // Attach agents array to each row
        $data[] = $row;
    }

    $response["success"] = true;
    $response["message"] = "Data fetched successfully";
    $response["data"] = $data;

} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

// Encode JSON with error checking
$json = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["error" => "JSON Encoding Error: " . json_last_error_msg()]);
} else {
    echo $json;
}
?>
