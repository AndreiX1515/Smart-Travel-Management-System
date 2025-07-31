<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../../conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) 
{
  $transactNo = $_POST['transactNo'];
  $accountId = $_POST['agentAccountId'];
  $amount = $_POST['downpayment'];

  date_default_timezone_set('Asia/Taipei');
  $paymentDate = (new DateTime())->format('Y-m-d H:i:s');

  $conn->query("SET @current_user_id = $accountId");

  if (!isset($_FILES['proofs']) || count($_FILES['proofs']['name']) === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Proof of payment files are required.']);
    exit;
  }

  $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/FIT Payment Uploads/" . $transactNo . "/";
  $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
  $maxFileSize = 4 * 1024 * 1024;
  $uploadedFiles = [];

  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  foreach ($_FILES['proofs']['name'] as $key => $fileName) 
  {
    $fileTmpPath = $_FILES['proofs']['tmp_name'][$key];
    $fileSize = $_FILES['proofs']['size'][$key];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExtension, $allowedExtensions) && $fileSize <= $maxFileSize && $_FILES['proofs']['error'][$key] === UPLOAD_ERR_OK) {
      $newFileName = $transactNo . '-' . date('m-d-Y_H-i') . '-' . uniqid() . '.' . $fileExtension;
      $destPath = $uploadDir . $newFileName;

      if (move_uploaded_file($fileTmpPath, $destPath)) {
        $uploadedFiles[] = $destPath;
      } else {
        echo json_encode(['status' => 'error', 'message' => "Failed to upload file: $fileName"]);
        exit;
      }
    } else {
      echo json_encode(['status' => 'error', 'message' => "File $fileName is invalid or exceeds 4MB."]);
      exit;
    }
  }

  if (empty($uploadedFiles)) {
    echo json_encode(['status' => 'error', 'message' => 'No valid files uploaded.']);
    exit;
  }

  // Save to database
  $conn->begin_transaction();
  $stmt = $conn->prepare("INSERT INTO fitpayment (transactNo, accountId, paymentType, amount, filePath, paymentDate, paymentStatus) 
                          VALUES (?, ?, 'Downpayment', ?, ?, ?, 'Submitted')");
  if (!$stmt) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'SQL Error: ' . $conn->error]);
    exit;
  }

  foreach ($uploadedFiles as $filePath) {
    $stmt->bind_param('sidss', $transactNo, $accountId, $amount, $filePath, $paymentDate);
    if (!$stmt->execute()) {
      $conn->rollback();
      echo json_encode(['status' => 'error', 'message' => 'Insert error: ' . $stmt->error]);
      exit;
    }
  }

  // ✅ Get bookingStatus for client-side logic
  $statusQuery = $conn->prepare("SELECT status FROM fit WHERE transactionNo = ?");
  $statusQuery->bind_param('s', $transactNo);
  $statusQuery->execute();
  $result = $statusQuery->get_result();
  $bookingStatus = ($result && $row = $result->fetch_assoc()) ? $row['status'] : 'Unknown';

  $conn->commit();
  echo json_encode([
    'status' => 'success',
    'message' => 'Payment and proof files uploaded successfully.',
    'transactionNumber' => $transactNo,
    'bookingStatus' => $bookingStatus
  ]);
  
  exit;
}

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
?>
