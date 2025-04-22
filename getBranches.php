<?php
include "conn.php";

$sql = "SELECT branchName, branchAgentCode 
        FROM branch 
        WHERE branchAgentCode IS NOT NULL AND branchAgentCode != ''";

$result = $conn->query($sql);

$agentColumns = [];

while ($row = $result->fetch_assoc()) {
    $agentCode = $row['branchAgentCode'];

    // Instead of returning an array of objects with agentCode and columns, 
    // we will just extract agent names (or whatever data you need) directly.
    $agentColumns[] = $agentCode;  // If you need to extract the agent code as the name
}

header('Content-Type: application/json');
echo json_encode($agentColumns);  // Returning an array of agent codes
?>
