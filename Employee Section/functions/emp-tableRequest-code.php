<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require "../../conn.php"; // Move up to the parent directory

  if (isset($_POST['updateRequestStatus'])) 
  {
    $requestId = $_POST['requestId'];
    $requestStatus = $_POST['requestStatus'];

    // Start a transaction
    $conn->begin_transaction();

    // Prepare the SQL statement for updating the request status
    $sql1 = "UPDATE request SET requestStatus = ? WHERE requestId = ?";
    $stmt1 = $conn->prepare($sql1);

    if (!$stmt1) 
    {
      $_SESSION['status'] = "SQL preparation failed: " . $conn->error;
      $conn->rollback();  // Rollback transaction if the preparation fails
      header("Location: ../emp-tableRequest.php");
      exit(0);
    }

    // Bind parameters and execute the update
    $stmt1->bind_param('si', $requestStatus, $requestId);
    
    if (!$stmt1->execute()) 
    {
      $_SESSION['status'] = "Database error: " . $stmt1->error;
      $conn->rollback();  // Rollback the transaction on failure
      header("Location: ../emp-tableRequest.php");
      exit(0);
    }

    // Commit the transaction if no errors
    $conn->commit();

    // Set a success message and redirect
    $_SESSION['status'] = "Request status updated to: " . $requestStatus;
    header("Location: ../emp-tableRequest.php");
    exit(0);
  }
?>