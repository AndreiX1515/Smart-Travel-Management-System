<?php
require "../../conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['roomAssignments'])) 
{
  $roomAssignments = json_decode($_POST['roomAssignments'], true);
  $luggageAssignments = isset($_POST['luggageAssignments']) ? json_decode($_POST['luggageAssignments'], true) : [];

  if (!empty($roomAssignments)) 
  {
    $conn->begin_transaction();

    $checkQuery = "SELECT roomType, roomNumber FROM roominglist WHERE transactNo = ? AND guestId = ?";
    $checkStmt = $conn->prepare($checkQuery);

    $updateQuery = "UPDATE roominglist SET roomType = ?, roomNumber = ?, tip = ?, remarks = ? WHERE transactNo = ? AND guestId = ?";
    $updateStmt = $conn->prepare($updateQuery);

    $insertQuery = "INSERT INTO roominglist (transactNo, guestId, roomType, roomNumber, tip, remarks) VALUES (?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);

    $checkLuggageQuery = "SELECT COUNT(*) FROM guestluggage WHERE guestId = ? AND luggageType = ?";
    $checkLuggageStmt = $conn->prepare($checkLuggageQuery);

    $insertLuggageQuery = "INSERT INTO guestluggage (guestId, luggageType) VALUES (?, ?)";
    $insertLuggageStmt = $conn->prepare($insertLuggageQuery);

    // Handle deleted guests
    $existingGuests = [];
    $transactNos = array_unique(array_column($roomAssignments, 'transactNo'));

    foreach ($transactNos as $transactNo) 
    {
      $query = "SELECT guestId FROM roominglist WHERE transactNo = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $transactNo);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()) 
      {
        $existingGuests[$transactNo][] = (int)$row['guestId'];
      }

      $stmt->close();
    }

    $newAssignments = [];
    foreach ($roomAssignments as $room) 
    {
      $newAssignments[$room['transactNo']][] = (int)$room['guestId'];
    }

    foreach ($existingGuests as $transactNo => $guestIds) 
    {
      $assignedGuestIds = isset($newAssignments[$transactNo]) ? $newAssignments[$transactNo] : [];

      foreach ($guestIds as $guestId) 
      {
        if (!in_array($guestId, $assignedGuestIds)) 
        {
          $deleteQuery = "DELETE FROM roominglist WHERE transactNo = ? AND guestId = ?";
          $deleteStmt = $conn->prepare($deleteQuery);
          $deleteStmt->bind_param("si", $transactNo, $guestId);
          $deleteStmt->execute();
          $deleteStmt->close();

          $deleteLuggageQuery = "DELETE FROM guestluggage WHERE guestId = ?";
          $deleteLuggageStmt = $conn->prepare($deleteLuggageQuery);
          $deleteLuggageStmt->bind_param("i", $guestId);
          $deleteLuggageStmt->execute();
          $deleteLuggageStmt->close();
        }
      }
    }

    // Save new or updated rooming assignments
    foreach ($roomAssignments as $room) 
    {
      $transactNo = $room['transactNo'];
      $guestId = (int)$room['guestId'];
      $roomType = $room['roomType'];
      $roomNumber = (int)$room['roomNumber'];
      $tip = isset($room['tipping']) ? $room['tipping'] : null;
      $remarks = isset($room['remarks']) ? $room['remarks'] : null;

      $checkStmt->bind_param("si", $transactNo, $guestId);
      $checkStmt->execute();
      $result = $checkStmt->get_result();

      if ($result->num_rows > 0) 
      {
        $updateStmt->bind_param("sisssi", $roomType, $roomNumber, $tip, $remarks, $transactNo, $guestId);
        $updateStmt->execute();
      } 
      else 
      {
        $insertStmt->bind_param("sissss", $transactNo, $guestId, $roomType, $roomNumber, $tip, $remarks);
        $insertStmt->execute();
      }

      $result->free();
    }

    // Save luggage entries
    if (!empty($luggageAssignments)) 
    {
      foreach ($luggageAssignments as $luggage) 
      {
        $guestId = (int)$luggage['guestId'];
        $luggageType = $luggage['luggageId'];

        $checkLuggageStmt->bind_param("is", $guestId, $luggageType);
        $checkLuggageStmt->execute();
        $checkLuggageStmt->bind_result($count);
        $checkLuggageStmt->fetch();
        $checkLuggageStmt->free_result();

        if ($count == 0) 
        {
          $insertLuggageStmt->bind_param("is", $guestId, $luggageType);
          $insertLuggageStmt->execute();
        }
      }
    }

    $conn->commit();

    echo json_encode([
      "status" => "success",
      "message" => "Room assignments, tipping, remarks, and luggage saved successfully"
    ]);

    // Close all
    $checkStmt->close();
    $updateStmt->close();
    $insertStmt->close();
    $checkLuggageStmt->close();
    $insertLuggageStmt->close();
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
