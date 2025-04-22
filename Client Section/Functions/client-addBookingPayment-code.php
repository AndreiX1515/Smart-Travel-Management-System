<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../conn.php"; // Move up to the parent directory

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    $transactNo = $_POST['transactNo'];
    $accountId = $_POST['agentAccountId'];
    $amount = $_POST['downpayment'];

    date_default_timezone_set('Asia/Taipei');
    $paymentDate = (new DateTime())->format('Y-m-d H:i:s'); // Current date and time

    $conn->query("SET @current_user_id = $accountId");

    if (isset($_FILES['proofs']) && count($_FILES['proofs']['name']) > 0) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Payment Uploads" . DIRECTORY_SEPARATOR . $transactNo . DIRECTORY_SEPARATOR;
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
        $maxFileSize = 4 * 1024 * 1024; // 4MB per file
        $uploadedFiles = [];

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($_FILES['proofs']['name'] as $key => $fileName) {
            $fileTmpPath = $_FILES['proofs']['tmp_name'][$key];
            $fileSize = $_FILES['proofs']['size'][$key];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExtension, $allowedExtensions) && $fileSize <= $maxFileSize && $_FILES['proofs']['error'][$key] === UPLOAD_ERR_OK) {
                $newFileName = $transactNo . '-' . date('m-d-Y_H-i') . '-' . uniqid() . '.' . $fileExtension;
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $uploadedFiles[] = $destPath;
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to upload file: $fileName"]);
                    exit;
                }
            } else {
                echo json_encode(["status" => "error", "message" => "File $fileName is invalid or exceeds 4MB."]);
                exit;
            }
        }

        if (!empty($uploadedFiles)) {
            $conn->begin_transaction();
            $sql = "INSERT INTO payment (transactNo, accountId, paymentTitle, paymentType, amount, filePath, paymentDate, paymentStatus) 
                    VALUES (?, ?, 'Package Payment', 'Downpayment', ?, ?, ?, 'Submitted')";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                echo json_encode(["status" => "error", "message" => "Payment SQL preparation failed: " . $conn->error]);
                $conn->rollback();
                exit;
            }

            foreach ($uploadedFiles as $filePath) {
                $stmt->bind_param('sidss', $transactNo, $accountId, $amount, $filePath, $paymentDate);
                if (!$stmt->execute()) {
                    echo json_encode(["status" => "error", "message" => "Database error on payment insert: " . $stmt->error]);
                    $conn->rollback();
                    exit;
                }
            }

            $sql1 = "UPDATE booking SET status = 'Pending' WHERE transactNo = ?";
            $stmt1 = $conn->prepare($sql1);

            if (!$stmt1) {
                echo json_encode(["status" => "error", "message" => "Booking update preparation failed: " . $conn->error]);
                $conn->rollback();
                exit;
            }

            $stmt1->bind_param('s', $transactNo);
            if (!$stmt1->execute()) {
                echo json_encode(["status" => "error", "message" => "Database error on booking update: " . $stmt1->error]);
                $conn->rollback();
                exit;
            }

            $conn->commit();
            echo json_encode([
                "status" => "success",
                "message" => "Payment and proof files uploaded successfully!",
                "bookingStatus" => "Pending",
                "transactionNumber" => $transactNo
            ]);
            exit;
        } else {
            echo json_encode(["status" => "error", "message" => "No valid files uploaded."]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Proof of payment files are required."]);
        exit;
    }
}
?>
