<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require "../../conn.php"; // Move up to the parent directory

  if (isset($_POST['updatePaymentStatus'])) 
  {
    $paymentId = $_POST['paymentId'];
    $paymentStatus = $_POST['paymentStatus'];
    $paymentRemarks = $_POST['paymentRemarks'];
    $accountId = $_SESSION['employee_accountId'];

    // Set the session variable for the current user in MySQL
    $conn->query("SET @current_user_id = $accountId");
    
    // Start a transaction
    $conn->begin_transaction();

    // Set remarks to NULL if empty
    if (empty($paymentRemarks)) 
    {
      $paymentRemarks = NULL;
    }

    // Prepare the SQL statement for updating the request status
    $sql1 = "UPDATE payment SET paymentStatus = ?, paymentRemarks = ? WHERE paymentId = ?";
    $stmt1 = $conn->prepare($sql1);

    if (!$stmt1) 
    {
      $_SESSION['status'] = "SQL preparation failed: " . $conn->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback();  // Rollback transaction if the preparation fails
      header("Location: ../emp-tablePayment.php");
      exit(0);
    }

    // Bind parameters and execute the update
    $stmt1->bind_param('ssi', $paymentStatus, $paymentRemarks, $paymentId);
    
    if (!$stmt1->execute()) 
    {
      $_SESSION['status'] = "Database error: " . $stmt1->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback();  // Rollback the transaction on failure
      header("Location: ../emp-tablePayment.php");
      exit(0);
    }

    // Commit the transaction if no errors
    $conn->commit();

    // Set a success message and redirect
    $_SESSION['status'] = $paymentId . " - status successfully updated to: " . $paymentStatus;
    $_SESSION['toastColor'] = 'text-bg-success'; // Green color for success
    header("Location: ../emp-tablePayment.php");
    exit(0);
  }
?>
