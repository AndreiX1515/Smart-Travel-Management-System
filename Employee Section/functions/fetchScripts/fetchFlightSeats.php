<?php
// api/flight-data.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../../conn.php';

try {
    // Get agent columns first
    $agentColumns = [];
    $sql = "SELECT branchName, branchAgentCode 
            FROM branch 
            WHERE branchAgentCode IS NOT NULL 
              AND branchAgentCode != ''";
    $result = $conn->query($sql);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $agentColumns[] = [
                'branchName' => $row['branchName'],
                'branchAgentCode' => $row['branchAgentCode']
            ];
        }
    }

    // Build dynamic agent columns for the main query
    $agentColumnsSQL = '';
    foreach ($agentColumns as $agent) {
        $agentCode = $conn->real_escape_string($agent['branchAgentCode']); // escape
        $agentColumnsSQL .= "
            IFNULL(SUM(CASE WHEN b.bookingType = 'Package' 
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

    // Remove trailing comma
    $agentColumnsSQL = rtrim($agentColumnsSQL, ', ');

    // Main query
    $sql = "
        SELECT f.flightId, f.is_active, f.origin, 
               f.flightDepartureDate AS Start, 
               f.returnDepartureDate AS End,

               e.fName AS TeamOP,


               e.colorCode, 
               f.availSeats AS FlightSeat, 
               
               GREATEST(f.availSeats - IFNULL(SUM(CASE 
                   WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                   AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0), 0) AS AvailSeats, 

               IF((f.availSeats - IFNULL(SUM(CASE 
                   WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                   AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)) < 0, 
                   ABS(f.availSeats - IFNULL(SUM(CASE 
                       WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                       AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)), 0) AS AdditionalSeats,

               SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') AND b.bookingType = 'Package' 
                   AND (a.agentType = 'Retailer' OR c.clientType = 'Retailer') THEN b.pax ELSE 0 END) AS `Air+Land`,

               SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') AND b.bookingType = 'Package' 
                   AND (a.agentType = 'Wholeseller' OR c.clientType = 'Wholeseller') THEN b.pax ELSE 0 END) AS `LandOnly`,

               f.wholesalePrice AS WholesalePrice, 
               f.flightPrice AS RetailPrice, 
               p.packagePrice AS LandArrangement,
               f.landPrice AS landPrice
    ";

    // Add dynamic columns if they exist
    if (!empty($agentColumnsSQL)) {
        $sql .= ", " . $agentColumnsSQL;
    }

    $sql .= "
        FROM flight f
        LEFT JOIN employee e ON f.employeeId = e.employeeId
        LEFT JOIN booking b ON b.flightId = f.flightId
        LEFT JOIN package p ON f.packageId = p.packageId
        LEFT JOIN agent a ON b.accountType = 'Agent' AND b.accountId = a.accountId
        LEFT JOIN client c ON b.accountType = 'Client' AND b.accountId = c.accountId
        WHERE f.flightDepartureDate >= CURDATE()
        GROUP BY f.flightId, f.is_active, f.origin, f.flightDepartureDate, f.returnDepartureDate, 
                 f.availSeats, f.wholesalePrice, f.flightPrice, p.packagePrice, f.landPrice, 
                 e.employeeId, e.colorCode
        ORDER BY f.flightDepartureDate
    ";

    // Debug log query
    error_log("🔍 SQL Query:\n" . $sql, 3, __DIR__ . "/error.log");

    $result = $conn->query($sql);

    $flights = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $flight = [
                'flightId' => (int)$row['flightId'],
                'is_active' => (int)$row['is_active'],
                'origin' => $row['origin'],
                'Start' => $row['Start'],
                'End' => $row['End'],
                'TeamOP' => $row['TeamOP'],
                'colorCode' => $row['colorCode'],
                'FlightSeat' => (int)$row['FlightSeat'],
                'AvailSeats' => (int)$row['AvailSeats'],
                'AdditionalSeats' => (int)$row['AdditionalSeats'],
                'Air+Land' => (int)($row['Air+Land'] ?? 0),
                'LandOnly' => (int)($row['LandOnly'] ?? 0),
                'WholesalePrice' => (float)$row['WholesalePrice'],
                'RetailPrice' => (float)$row['RetailPrice'],
                'LandArrangement' => (float)($row['LandArrangement'] ?? 0),
                'landPrice' => (float)$row['landPrice']
            ];

            // Add dynamic agent columns
            foreach ($agentColumns as $agent) {
                $agentCode = $agent['branchAgentCode'];
                $flight["{$agentCode}_AL"] = (int)($row["{$agentCode}_AL"] ?? 0);
                $flight["{$agentCode}_LO"] = (int)($row["{$agentCode}_LO"] ?? 0);
            }

            $flights[] = $flight;
        }
    }

    $response = [
        'success' => true,
        'flights' => $flights,
        'agentColumns' => $agentColumns,
        'totalRecords' => count($flights),
        'timestamp' => date('Y-m-d H:i:s')
    ];

    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    error_log("❌ Exception: " . $e->getMessage(), 3, __DIR__ . "/error.log");
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage(),
        'flights' => [],
        'agentColumns' => [],
        'totalRecords' => 0
    ]);
}
?>
