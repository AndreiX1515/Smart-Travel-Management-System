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
    $requestRemarks = $_POST['requestRemarks'];
    $accountId = $_SESSION['employee_accountId'];

    // Set the session variable for the current user in MySQL
    $conn->query("SET @current_user_id = $accountId");

    // Start a transaction
    $conn->begin_transaction();

    // Set remarks to NULL if empty
    if (empty($requestRemarks)) 
    {
      $requestRemarks = NULL;
    }

    // Prepare the SQL statement for updating the request status
    $sql1 = "UPDATE request SET requestStatus = ?, requestRemarks = ? WHERE requestId = ?";
    $stmt1 = $conn->prepare($sql1);

    if (!$stmt1) 
    {
      $_SESSION['status'] = "SQL preparation failed: " . $conn->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback();  // Rollback transaction if the preparation fails
      header("Location: ../emp-tableRequest.php");
      exit(0);
    }

    // Bind parameters and execute the update
    $stmt1->bind_param('ssi', $requestStatus, $requestRemarks, $requestId);
    
    if (!$stmt1->execute()) 
    {
      $_SESSION['status'] = "Database error: " . $stmt1->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback();  // Rollback the transaction on failure
      header("Location: ../emp-tableRequest.php");
      exit(0);
    }

    // Commit the transaction if no errors
    $conn->commit();

    $_SESSION['status'] = "Request ID " . $requestId . " - status successfully updated to: " . $requestStatus;
    $_SESSION['toastColor'] = 'text-bg-secondary'; // Blue color for Submitted status

    header("Location: ../emp-tableRequest.php");
    exit(0);
  }
?>
