<?php
require "../../conn.php"; // Include the DB connection
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

if (isset($_POST['companyId']) && isset($_POST['flightId']) && isset($_POST['currentDate'])) 
{
  // Get data from the POST request
  $companyId = $_POST['companyId'];
  $currentDate = $_POST['currentDate']; // Current date sent from the client
  $flightId = $_POST['flightId'];

  // Check if flightId is provided or if we use month/year
  if ($flightId) 
  {
    // Flight date-based SOA
    $sqlFlight = "SELECT flightDepartureDate FROM flight WHERE id = ?";
    $stmtFlight = $conn->prepare($sqlFlight);
    $stmtFlight->bind_param('i', $flightId);
    $stmtFlight->execute();
    $resultFlight = $stmtFlight->get_result();
    
    if ($rowFlight = $resultFlight->fetch_assoc()) 
    {
      $flightDate = $rowFlight['flightDepartureDate'];
      $month = date('m', strtotime($flightDate));
      $year = date('Y', strtotime($flightDate));
    } 
    else 
    {
      echo json_encode(['error' => "Invalid Flight ID."]);
      exit();
    }

    $stmtFlight->close();
  }
  else 
  {
    // Month/Year-based SOA
    $month = date('m', strtotime($_POST['month']));
    $year = $_POST['year'];
  }

  // Begin the transaction
  $conn->begin_transaction();

  // Fetch the last SOA number for the current year
  $sql5 = "SELECT MAX(id) AS lastSoAId FROM soa";
  $result5 = $conn->query($sql5);

  if (!$result5) 
  {
    $conn->rollback();
    echo json_encode(['error' => "Error fetching last SOA number."]);
    exit();
  }

  // Get the next SOA number
  $row = $result5->fetch_assoc();
  $newSoANo = ($row && $row['lastSoAId'] !== null) ? $row['lastSoAId'] + 1 : 1;
  $formattedCounter = str_pad($newSoANo, 5, '0', STR_PAD_LEFT);
  $soaNo = 'SMT-' . $year . '-' . $formattedCounter;

  // Set the current date and time for the `dateGenerated` field
  $dateGenerated = date('Y-m-d H:i:s');

  // Prepare the SQL for insertion
  $sql6 = "INSERT INTO soa (soaNo, branchId, flightId, dateGenerated, status)
          VALUES (?, ?, ?, ?, ?)";
  $stmt6 = $conn->prepare($sql6);

  if (!$stmt6) 
  {
    $conn->rollback();
    echo json_encode(['error' => "Error preparing SQL statement."]);
    exit();
  }

  // Bind parameters to the prepared statement
  $status = 'Partially Paid';
  $stmt6->bind_param('siiss', $soaNo, $companyId, $flightId, $dateGenerated, $status);

  // Execute the statement
  if ($stmt6->execute()) 
  {
    $conn->commit();
    echo json_encode(['soanum' => $soaNo]);
  } 
  else 
  {
    $conn->rollback();
    echo json_encode(['error' => "Error inserting SOA number."]);
  }

  // Close the statement and the connection
  $stmt6->close();
  $conn->close();
}
?>