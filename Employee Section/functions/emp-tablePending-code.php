<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../../conn.php"; // Move up to the parent directory

if (isset($_POST['updateBookingStatus'])) 
{
    $transactNo = $_POST['transactNo'];
    $bookingStatus = $_POST['bookingStatus'];
    $bookingRemarks = $_POST['bookingRemarks'];

    // Start a transaction
    $conn->begin_transaction();

    // Set remarks to NULL if empty
    if (empty($bookingRemarks)) 
    {
        $bookingRemarks = NULL;
    }

    // Prepare the SQL statement for updating the request status
    $sql1 = "UPDATE booking SET status = ?, remarks = ? WHERE transactNo = ?";
    $stmt1 = $conn->prepare($sql1);

    if (!$stmt1) 
    {
        $_SESSION['status'] = "SQL preparation failed: " . $conn->error;
        $_SESSION['toastColor'] = 'text-bg-danger'; // Red for errors
        $conn->rollback();  // Rollback transaction if the preparation fails
        header("Location: ../emp-tablePending.php");
        exit(0);
    }

    // Bind parameters and execute the update
    $stmt1->bind_param('sss', $bookingStatus, $bookingRemarks, $transactNo);
    
    if (!$stmt1->execute()) 
    {
        $_SESSION['status'] = "Database error: " . $stmt1->error;
        $_SESSION['toastColor'] = 'text-bg-danger'; // Red for errors
        $conn->rollback();  // Rollback the transaction on failure
        header("Location: ../emp-tablePending.php");
        exit(0);
    }

    // Commit the transaction if no errors
    $conn->commit();

    // Set a success message and toast color
    $_SESSION['status'] = $transactNo . " - status successfully updated to: " . $bookingStatus;

    $_SESSION['toastColor'] = 'text-bg-success'; // Green for success
    header("Location: ../emp-tablePending.php");
    exit(0);
}
?>
