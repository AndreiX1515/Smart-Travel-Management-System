<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Ensure JSON response

require "../../conn.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pay'])) {
    $transactNo = $_POST['transactNo'];
    $accountId = $_POST['agentAccountId'];
    $amount = $_POST['downpayment'];

    date_default_timezone_set('Asia/Taipei');
    $paymentDate = (new DateTime())->format('Y-m-d H:i:s');

    $conn->query("SET @current_user_id = $accountId");

    if (!isset($_FILES['proofs']) || count($_FILES['proofs']['name']) === 0) {
        echo json_encode(["status" => "error", "message" => "Proof of payment files are required."]);
        exit();
    }

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Payment Uploads/" . $transactNo . "/";
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    $maxFileSize = 4 * 1024 * 1024; // 4MB
    $uploadedFiles = [];

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
        echo json_encode(["status" => "error", "message" => "Failed to create upload directory."]);
        exit();
    }

    foreach ($_FILES['proofs']['name'] as $key => $fileName) {
        $fileTmpPath = $_FILES['proofs']['tmp_name'][$key];
        $fileSize = $_FILES['proofs']['size'][$key];
        $fileError = $_FILES['proofs']['error'][$key];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileError !== UPLOAD_ERR_OK) {
            echo json_encode(["status" => "error", "message" => "Error uploading file: $fileName. Code: $fileError"]);
            exit();
        }

        if (!in_array($fileExtension, $allowedExtensions) || $fileSize > $maxFileSize) {
            echo json_encode(["status" => "error", "message" => "Invalid file type or file exceeds 4MB: $fileName"]);
            exit();
        }

        $newFileName = $transactNo . '-' . date('m-d-Y_H-i') . '-' . uniqid() . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            echo json_encode(["status" => "error", "message" => "Failed to save file: $fileName"]);
            exit();
        }

        $uploadedFiles[] = "Files Uploads/Payment Uploads/$transactNo/$newFileName";
    }

    if (empty($uploadedFiles)) {
        echo json_encode(["status" => "error", "message" => "No valid files uploaded."]);
        exit();
    }

    // Begin database transaction
    $conn->begin_transaction();
    $sql = "INSERT INTO payment (transactNo, accountId, paymentTitle, paymentType, amount, filePath, paymentDate, paymentStatus) 
            VALUES (?, ?, 'Package Payment', 'Downpayment', ?, ?, ?, 'Submitted')";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "SQL error: " . $conn->error]);
        $conn->rollback();
        exit();
    }

    foreach ($uploadedFiles as $filePath) {
        $stmt->bind_param('sidss', $transactNo, $accountId, $amount, $filePath, $paymentDate);
        if (!$stmt->execute()) {
            echo json_encode(["status" => "error", "message" => "Database insert error: " . $stmt->error]);
            $conn->rollback();
            exit();
        }
    }

    $sql1 = "UPDATE booking SET status = 'Pending' WHERE transactNo = ?";
    $stmt1 = $conn->prepare($sql1);

    if (!$stmt1) {
        echo json_encode(["status" => "error", "message" => "SQL update error: " . $conn->error]);
        $conn->rollback();
        exit();
    }

    $stmt1->bind_param('s', $transactNo);
    if (!$stmt1->execute()) {
        echo json_encode(["status" => "error", "message" => "Booking update failed: " . $stmt1->error]);
        $conn->rollback();
        exit();
    }

    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Payment and proof files uploaded successfully!"]);
    exit();
}
?>
