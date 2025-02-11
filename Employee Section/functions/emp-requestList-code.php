<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../../conn.php"; // Move up to the parent directory

if (isset($_POST['submit'])) 
{
    $requestTitle = $_POST['requestTitle'];
    $requestDetails = $_POST['requestDetails'];
    $requestAmount = $_POST['requestAmount'];

    // Start a transaction
    $conn->begin_transaction();

    // Prepare the SQL statement for inserting into concerndetails
    $sql1 = "INSERT INTO concerndetails (concernId, details, price) VALUES (?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);

    if (!$stmt1) 
    {
      $_SESSION['status'] = "SQL preparation failed: " . $conn->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback();  // Rollback transaction if preparation fails
      header("Location: ../emp-requestList.php");
      exit(0);
    }

    // Bind parameters correctly (concernId is expected to be an integer)
    $stmt1->bind_param('isd', $requestTitle, $requestDetails, $requestAmount);
    
    // Execute the query
    if (!$stmt1->execute()) 
    {
      $_SESSION['status'] = "Database error: " . $stmt1->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback();  // Rollback the transaction on failure
      header("Location: ../emp-requestList.php");
      exit(0);
    }

    // Commit the transaction if no errors
    $conn->commit();

    $_SESSION['status'] = "New Request Item has been added";
    $_SESSION['toastColor'] = 'text-bg-secondary'; // Blue color for Submitted status

    header("Location: ../emp-requestList.php");
    exit(0);
}
?>
