<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require "../../conn.php"; // Move up to the parent directory

  if (isset($_POST['updatePrice'])) 
  {
    $requestId = $_POST['requestId'];
    $requestAmount = $_POST['requestAmount'];
    $accountId = $_SESSION['employee_accountId'];

    // Set the session variable for the current user in MySQL
    $conn->query("SET @current_user_id = $accountId");

    // Start transaction
    $conn->begin_transaction();

    // Prepare the SQL statement
    $sql1 = "UPDATE request SET requestCost = ? WHERE requestId = ?";
    $stmt1 = $conn->prepare($sql1);

    if (!$stmt1) 
    {
      $_SESSION['status'] = "SQL preparation failed: " . $conn->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback(); // Rollback transaction if preparation fails
      header("Location: ../emp-tablePayment.php");
      exit(0);
    }

    // Bind parameters (requestCost should be float or decimal)
    $stmt1->bind_param('di', $requestAmount, $requestId); // 'd' for decimal, 'i' for integer

    // Execute the statement
    if (!$stmt1->execute()) 
    {
      $_SESSION['status'] = "Database error: " . $stmt1->error;
      $_SESSION['toastColor'] = 'text-bg-danger'; // Red color for error
      $conn->rollback(); // Rollback the transaction on failure
      $stmt1->close();
      header("Location: ../emp-tablePayment.php");
      exit(0);
    }

    // Close the first statement
    $stmt1->close();

    // Commit the transaction if no errors
    $conn->commit();

    // Set a success message and redirect
    $_SESSION['status'] = "Request ID {$requestId} successfully updated with amount: ₱" . number_format($requestAmount, 2);
    $_SESSION['toastColor'] = 'text-bg-success'; // Green color for success
    header("Location: ../emp-requestHistory.php");
    exit(0);
  }
?>