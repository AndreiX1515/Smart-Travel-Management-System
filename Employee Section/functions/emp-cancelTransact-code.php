<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../../conn.php"; // Move up to the parent directory

if (isset($_POST['confirmCancel'])) 
{
    $transactNo = $_POST['transactNo'];
    $remarks = isset($_POST['remarks']) && !empty($_POST['remarks']) ? $_POST['remarks'] : null;
    $accountId = $_POST['accId'];

    // Set the session variable for the current user in MySQL
    $conn->query("SET @current_user_id = $accountId");

    // Start a transaction
    $conn->begin_transaction();

    // Prepare the SQL statement for inserting into concerndetails
    $sql1 = "UPDATE booking SET status='Cancelled', remarks= ? WHERE transactNo = ?";
    $stmt1 = $conn->prepare($sql1);

    if (!$stmt1) 
    {
      $_SESSION['status'] = "SQL preparation failed: " . $conn->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback();  // Rollback transaction if preparation fails
      header("Location: ../emp-transactionInfo.php?id=" . $transactNo);
      exit(0);
    }

    // Bind parameters correctly (concernId is expected to be an integer)
    $stmt1->bind_param('ss', $remarks, $transactNo);
    
    // Execute the query
    if (!$stmt1->execute()) 
    {
      $_SESSION['status'] = "Database error: " . $stmt1->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback();  // Rollback the transaction on failure
      header("Location: ../emp-transactionInfo.php?id=" . $transactNo);
      exit(0);
    }

    // Commit the transaction if no errors
    $conn->commit();

    $_SESSION['status'] = "New Request Item has been added";
    $_SESSION['toastColor'] = 'text-bg-secondary'; // Blue color for Submitted status

    header("Location: ../emp-transactionInfo.php?id=" . $transactNo);
    exit(0);
}
?>
