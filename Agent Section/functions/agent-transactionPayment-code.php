<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../../conn.php"; // Move up to the parent directory

if (isset($_POST['payment'])) {
    $transactNo = $_POST['transactNo'];
    $accountId = $_POST['accountId'];
    $paymentTitle = $_POST['paymentTitle'];
    $paymentType = $_POST['paymentType'];
    $amount = $_POST['amount'];

    // Set the timezone (replace 'Asia/Taipei' with your preferred timezone if needed)
    date_default_timezone_set('Asia/Taipei');
    $paymentDate = (new DateTime())->format('Y-m-d H:i:s'); // Current date and time

    if (isset($_FILES['proofs']) && count($_FILES['proofs']['name']) > 0) {
        $uploadDir = "../../uploads/{$transactNo}/";
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
        $maxFileSize = 4 * 1024 * 1024; // 4MB per file
        $uploadedFiles = []; // Array to store file paths

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($_FILES['proofs']['name'] as $key => $fileName) {
            $fileTmpPath = $_FILES['proofs']['tmp_name'][$key];
            $fileSize = $_FILES['proofs']['size'][$key];
            $fileType = $_FILES['proofs']['type'][$key];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExtension, $allowedExtensions) && $fileSize <= $maxFileSize && $_FILES['proofs']['error'][$key] === UPLOAD_ERR_OK) {
                $newFileName = $transactNo . '-' . date('m-d-Y_H-i') . '-' . uniqid() . '.' . $fileExtension;
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $uploadedFiles[] = $destPath;
                } else {
                    $_SESSION['status'] = "Failed to upload file: $fileName";
                    header("Location: ../agent-transactions.php");
                    exit(0);
                }
            } else {
                $_SESSION['status'] = "File $fileName is invalid or exceeds size limit of 4MB.";
                header("Location: ../agent-transactions.php");
                exit(0);
            }
        }

        if (!empty($uploadedFiles)) {
            $conn->begin_transaction();
            $filePathsSerialized = serialize($uploadedFiles);

            $sql = "INSERT INTO payment (transactNo, accountId, paymentTitle, paymentType, amount, proof, paymentDate, paymentStatus) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                $_SESSION['status'] = "Booking SQL preparation failed: " . $conn->error;
                $conn->rollback();
                header("Location: ../agent-transactions.php");
                exit(0);
            }

            $stmt->bind_param('sissdss', $transactNo, $accountId, $paymentTitle, $paymentType, $amount, $filePathsSerialized, $paymentDate);

            if ($stmt->execute()) {
                $conn->commit();
                $_SESSION['status'] = "Payment and proof files uploaded successfully!";
                header("Location: ../agent-transactions.php");
                exit(0);
            } else {
                $_SESSION['status'] = "Database error on payment insert: " . $stmt->error;
                $conn->rollback();
                header("Location: ../agent-transactions.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "No valid files uploaded.";
            header("Location: ../agent-transactions.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Proof of payment files are required.";
        header("Location: ../agent-transactions.php");
        exit(0);
    }
}
?>
