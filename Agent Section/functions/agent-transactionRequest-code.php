<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require "../../conn.php"; // Move up to the parent directory

  if (isset($_POST['request'])) 
  {
    $transactNo = $_POST['transaction_number'];
    $accountId = $_POST['accountId'];
    $concern = $_POST['concern'];
    $requestDetails = $_POST['requestDetails'];
    $pax = $_POST['pax'];
    $details = $_POST['details'];
    $amount = $_POST['totalPrice'];

    // Start the transaction
    $conn->begin_transaction();

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO request (transactNo, accountId, concernId, concernDetailsId, pax, details, requestCost, requestDate, requestStatus) 
                    VALUES(?, ?, ?, ?, ?, ?, ?, Now(), 'Pending')");
    $stmt->bind_param("siiiisd", $transactNo, $accountId, $concern, $requestDetails, $pax, $details, $amount);

    // Execute the statement
    if ($stmt->execute()) 
    {
      // Commit the transaction if everything is successful
      $conn->commit();
      $_SESSION['status'] = "Request submitted successfully!";
      header("Location: ../agent-transactions.php");
      exit(0);
    } 
    else 
    {
      // Rollback the transaction if there's an error
      $_SESSION['status'] = "Database error on request insert: " . $stmt->error;
      $conn->rollback();  // Rollback the transaction if there is an error
      header("Location: ../agent-transactions.php");
      exit(0);
    }

    // Close the statement
    $stmt->close();

    // Close the connection
    $conn->close();
  }
?>
