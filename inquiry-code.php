<?php
  include 'session_validate.php'; // This will check if the session is valid
  require "conn.php";

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  if (isset($_POST['inquiry'])) 
  {
    $transactNo = $_POST['transactNo'];
    $agentId = $_POST['agentId'];
    $concern = $_POST['concern'];
    $details = $_POST['details'];

    // Start a transaction
    $conn->begin_transaction();

    // Prepare the SQL statement for insertion into the booking table
    $sql1 = "INSERT INTO inquiry (transactNo, agentId, concern, details) VALUES (?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);

    // Check if the statement was prepared successfully
    if (!$stmt1) 
    {
      $_SESSION['status'] = "Inquiry SQL preparation failed: " . $conn->error;
      $conn->rollback();  // Rollback transaction
      header("Location: client-transactionStatus.php");
      exit(0);
    }

    // Bind and execute the booking insertion
    $stmt1->bind_param('siss', $transactNo, $agentId, $concern, $details);
    
    if (!$stmt1->execute()) 
    {
      $_SESSION['status'] = "Database error on inquiry insert: " . $stmt1->error;
      $conn->rollback();  // Rollback the transaction if there is an error
      header("Location: client-transactionStatus.php");
      exit(0);
    }

  // Commit the transaction if everything is successful
  $conn->commit();
  $_SESSION['status'] = "Inquiry submitted successfully!";
  header("Location: client-transactionStatus.php");
  exit(0);
}

// Close the prepared statement
$stmt1->close();
$conn->close();
?>