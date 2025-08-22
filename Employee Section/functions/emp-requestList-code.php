<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../../conn.php";

if (isset($_POST['submit'])) {
  $requestTitle   = $_POST['requestTitle'] ?? null;  // existing concernId
  $newRequestTitle = trim($_POST['newRequestTitle'] ?? ""); // new concern name
  $requestDetails = $_POST['requestDetails'] ?? "";
  $requestAmount  = $_POST['requestAmount'] ?? 0;

  try {
    $conn->begin_transaction();

    // Step 1: If user entered new request title, insert into concern
    if (!empty($newRequestTitle)) {
      $sqlConcern = "INSERT INTO concern (concernTitle) VALUES (?)";
      $stmtConcern = $conn->prepare($sqlConcern);
      $stmtConcern->bind_param("s", $newRequestTitle);

      if (!$stmtConcern->execute()) {
        throw new Exception("Failed to insert new request title: " . $stmtConcern->error);
      }

      // Use the new concernId
      $concernId = $stmtConcern->insert_id;
    } else {
      // Use existing concernId from dropdown
      $concernId = $requestTitle;
    }

    // Step 2: Insert into concerndetails
    $sqlDetails = "INSERT INTO concerndetails (concernId, details, price) VALUES (?, ?, ?)";
    $stmtDetails = $conn->prepare($sqlDetails);
    $stmtDetails->bind_param("isd", $concernId, $requestDetails, $requestAmount);

    if (!$stmtDetails->execute()) {
      throw new Exception("Failed to insert concern details: " . $stmtDetails->error);
    }

    $conn->commit();

    $_SESSION['status'] = "Request has been added successfully.";
    $_SESSION['toastColor'] = 'text-bg-secondary';

  } catch (Exception $e) {
    $conn->rollback();
    $_SESSION['status'] = "Error: " . $e->getMessage();
    $_SESSION['toastColor'] = 'text-bg-danger';
  }

  header("Location: ../emp-requestList.php");
  exit(0);
}
?>
