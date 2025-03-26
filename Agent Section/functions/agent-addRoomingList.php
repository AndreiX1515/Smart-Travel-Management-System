<?php
require "../../conn.php"; // Database connection
session_start(); // Start session

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['roomAssignments'])) 
{
    $roomAssignments = json_decode($_POST['roomAssignments'], true);

    if (!empty($roomAssignments)) 
    {
        $conn->begin_transaction(); // Start transaction

        // Prepare statements for checking, updating, and inserting
        $checkQuery = "SELECT roomType, roomNumber FROM roominglist WHERE transactNo = ? AND guestId = ?";
        $checkStmt = $conn->prepare($checkQuery);

        $updateQuery = "UPDATE roominglist SET roomType = ?, roomNumber = ? WHERE transactNo = ? AND guestId = ?";
        $updateStmt = $conn->prepare($updateQuery);

        $insertQuery = "INSERT INTO roominglist (transactNo, guestId, roomType, roomNumber) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);

        foreach ($roomAssignments as $room) 
        {
            $transactNo = $room['transactNo'];
            $guestId = (int) $room['guestId']; // Ensure integer type
            $roomType = $room['roomType']; 
            $roomNumber = (int) $room['roomNumber']; // Ensure integer type

            // Check if guestId already exists in roominglist
            $checkStmt->bind_param("si", $transactNo, $guestId);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) 
            {
                // Fetch current room assignment
                $row = $result->fetch_assoc();

                // Check if the room type or number has changed
                if ($row['roomType'] !== $roomType || $row['roomNumber'] !== $roomNumber) 
                {
                    // Update only if values have changed
                    $updateStmt->bind_param("sisi", $roomType, $roomNumber, $transactNo, $guestId);
                    $updateStmt->execute();
                }
            } 
            else 
            {
                // Insert new record if guestId is not in roominglist
                $insertStmt->bind_param("sisi", $transactNo, $guestId, $roomType, $roomNumber);
                $insertStmt->execute();
            }
        }

        $conn->commit(); // Commit transaction
        echo json_encode(["status" => "success", "message" => "Room assignments saved successfully"]);
        
        // Close statements
        $checkStmt->close();
        $updateStmt->close();
        $insertStmt->close();
    } 
    else 
    {
        echo json_encode(["status" => "error", "message" => "No room assignments provided"]);
    }
} 
else 
{
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

$conn->close();
?>
