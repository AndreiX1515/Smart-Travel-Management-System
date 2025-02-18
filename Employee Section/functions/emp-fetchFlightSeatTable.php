<?php
require "../../conn.php";

header('Content-Type: application/json');

// Debug: Check Database Connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Dynamically generate agent-specific columns
$agentColumns = '';
$agentInfo = [];

// Fetch distinct agent codes along with their branch names
$sql_agents = "SELECT DISTINCT a.agentCode, a.agentType, b.branchName 
               FROM agent a
               LEFT JOIN branch b ON a.agentCode = b.branchAgentCode
               WHERE a.agentCode IS NOT NULL AND a.agentCode != '' AND b.branchName IS NOT NULL";

$result_agents = $conn->query($sql_agents);

if (!$result_agents) {
    die(json_encode(["error" => "Failed to fetch agent data: " . $conn->error]));
}

// Loop through agent data and dynamically generate columns
while ($row = $result_agents->fetch_assoc()) {
    $agentCode = $row['agentCode'];
    $branchName = $row['branchName'] ?? 'N/A'; // Default to 'N/A' if branchName is NULL

    // Dynamically generate agent-specific SQL columns:
    // For AL: use agentType 'Retailer'
    $agentColumns .= "IFNULL(SUM(CASE WHEN b.bookingType = 'Package' 
                           AND b.status IN ('Pending', 'Processing') 
                           AND b.agentCode = '$agentCode' 
                           AND a.agentType = 'Retailer' 
                           THEN b.pax ELSE 0 END), 0) AS `{$agentCode}_AL`, ";

    // For LO: use agentType 'Wholeseller'
    $agentColumns .= "IFNULL(SUM(CASE WHEN b.bookingType = 'Package' 
                           AND b.status IN ('Pending', 'Processing') 
                           AND b.agentCode = '$agentCode' 
                           AND a.agentType = 'Wholeseller' 
                           THEN b.pax ELSE 0 END), 0) AS `{$agentCode}_LO`, ";

    // Store agent codes and branch names for later use in dynamic columns
    $agentInfo[] = ['agentCode' => $agentCode, 'branchName' => $branchName];
}

$agentColumns = rtrim($agentColumns, ', ');

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
                              SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') 
                                AND b.bookingType = 'Package' AND a.agentType = 'Retailer' THEN b.pax ELSE 0 END) AS `Air+Land`,
                              SUM(CASE WHEN (b.status = 'Confirmed' OR b.status = 'Reserved') AND b.bookingType = 'Package' 
                                AND a.agentType = 'Wholeseller' THEN b.pax ELSE 0 END) AS `LandOnly`,
                              f.wholesalePrice AS WholesalePrice, f.flightPrice AS RetailPrice, p.packagePrice AS LandArrangement,
                              f.landPrice AS landPrice, 
                              $agentColumns
                            FROM employee e
                            RIGHT JOIN flight f ON f.employeeId = e.employeeId
                            LEFT JOIN booking b ON b.flightId = f.flightId
                            LEFT JOIN package p ON f.packageId = p.packageId
                            LEFT JOIN agent a ON b.agentId = a.agentId
                            WHERE f.flightDepartureDate >= CURDATE()
                            GROUP BY f.flightId, f.is_active, f.origin, f.flightDepartureDate, f.returnDepartureDate, f.availSeats, 
                              f.wholesalePrice, f.flightPrice, p.packagePrice
                            ORDER BY f.flightDepartureDate";

error_log("SQL Query: " . $sql); // Log query for debugging
$result = $conn->query($sql);

// Debug: If Query Fails, Log Error
if (!$result) {
    die(json_encode(["error" => "Query failed: " . $conn->error]));
}

// Debug: Check If Any Data Exists
if ($result->num_rows === 0) {
    die(json_encode(["message" => "No data found in the database."]));
}

// Fetch data and ensure each agent-specific column is added dynamically
$data = [];
$dynamicColumns = [];

// Loop through the result set and prepare data
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "flightId" => $row["flightId"],
        "origin" => $row["origin"],
        "startDate" => $row["Start"],
        "endDate" => $row["End"],
        "teamOP" => $row["TeamOP"],
        "flightSeat" => $row["FlightSeat"],
        "availSeats" => $row["AvailSeats"],
        "additionalSeats" => $row["AdditionalSeats"],
        "airLand" => $row["Air+Land"],
        "landOnly" => $row["LandOnly"],
        "wholesalePrice" => number_format($row["WholesalePrice"] ?? 0, 2),
        "retailPrice" => number_format($row["RetailPrice"] ?? 0, 2),
        "landArrangement" => number_format($row["LandArrangement"] ?? 0, 2),
        "landPrice" => number_format($row["landPrice"] ?? 0, 2),
    ];
}

    // Dynamically create "Branch" and "AL" / "LO" columns for each agent
    $dynamicColumns = [];
    foreach ($agentInfo as $agent) {
        
        $dynamicColumns[] = [
            "title" => $agent['branchName'],  // Use branch name as the header
            "headerHozAlign" => "center", 
            "columns" => [
                [
                    "title" => "AL",  // AirLand column
                    "field" => "{$agent['agentCode']}_AL",  // Must match the alias in the SQL query
                    "hozAlign" => "center",
                    "cellStyle" => "function(cell){
                        var value = cell.getValue();
                        var fontWeight = (value >= 1) ? 'bolder' : 'normal';
                        var colors = ['#ADD8E6', '#98FB98', '#FFFFCC', '#E6E6FA', '#FFDAB9'];
                        var colorIndex = 0;
                        if (!isNaN(value)) {
                            colorIndex = parseInt(value) % colors.length;
                        }
                            
                        var backgroundColor = colors[colorIndex];
                        return {
                            'text-align': 'center',
                            'font-weight': fontWeight,
                            'background-color': backgroundColor,
                            'border-left': '1px solid #ddd',
                            'border-right': '1px solid #ddd'
                        };
                    }"
                ],
                [
                    "title" => "LO",  // LandOnly column
                    "field" => "{$agent['agentCode']}_LO",  // Must match the alias in the SQL query
                    "hozAlign" => "center",
                    "cellStyle" => "function(cell){
                        var value = cell.getValue();
                        var fontWeight = (value >= 1) ? 'bolder' : 'normal';
                        var colors = ['#ADD8E6', '#98FB98', '#FFFFCC', '#E6E6FA', '#FFDAB9'];
                        var colorIndex = 0;
                        if (!isNaN(value)) {
                            colorIndex = parseInt(value) % colors.length;
                        }
                        var backgroundColor = colors[colorIndex];
                        return {
                            'text-align': 'center',
                            'font-weight': fontWeight,
                            'background-color': backgroundColor,
                            'border-left': '1px solid #ddd',
                            'border-right': '1px solid #ddd'
                        };
                    }"
                ]
            ]
        ];
    }


echo json_encode(['data' => $data, 'columns' => $dynamicColumns]);
$conn->close();
?>
