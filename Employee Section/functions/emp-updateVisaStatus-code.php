<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require "../../conn.php"; // Move up to the parent directory

  if (isset($_POST['updateVisaStatus'])) 
  {
    $guestId = $_POST['guestId'];
    $transactNo = $_POST['transactNo'];
    $visaStatus = $_POST['visaStatus'];
    $updateVisaStatus = $_POST['updateVisaStatus'];
    $accountId = $_SESSION['employee_accountId'];

    // Set the session variable for the current user in MySQL
    $conn->query("SET @current_user_id = $accountId");

    // Start transaction
    $conn->begin_transaction();

    // Prepare the SQL statement
    $sql1 = "UPDATE guest SET visaStatus = ?, visaRemarks = ? WHERE guestId = ?";
    $stmt1 = $conn->prepare($sql1);

    if (!$stmt1) 
    {
      $_SESSION['status'] = "SQL preparation failed: " . $conn->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback(); // Rollback transaction if preparation fails
      header("Location: ../emp-transactionInfo.php?id=" . htmlspecialchars($transactNo));
      exit(0);
    }

    // Bind parameters (requestCost should be float or decimal)
    $stmt1->bind_param('ssi', $visaStatus, $updateVisaStatus, $guestId); // 'd' for decimal, 'i' for integer

    // Execute the statement
    if (!$stmt1->execute()) 
    {
      $_SESSION['status'] = "Database error: " . $stmt1->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback(); // Rollback the transaction on failure
      $stmt1->close();
      header("Location: ../emp-transactionInfo.php?id=" . htmlspecialchars($transactNo));
      exit(0);
    }

    // Close the first statement
    $stmt1->close();

    // Commit the transaction if no errors
    $conn->commit();

    // Set a success message and redirect
    $_SESSION['status'] = "Visa status for Guest ID {$guestId} successfully changed to: {$visaStatus}.";
    $_SESSION['toastColor'] = 'text-bg-success'; // Green color for success
    header("Location: ../emp-transactionInfo.php?id=" . htmlspecialchars($transactNo));
    exit(0);
  }
?>