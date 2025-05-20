<?php
require "../../conn.php"; // DB connection
session_start();
header('Content-Type: application/json');

// Load PHPSpreadsheet
require_once '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['message' => '❌ Invalid request method.']);
  exit;
}

if (!isset($_FILES['flightFile']) || $_FILES['flightFile']['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['message' => '❌ Failed to upload file.']);
  exit;
}

$file = $_FILES['flightFile'];
$filename = $file['name'];
$tmpPath = $file['tmp_name'];
$extension = pathinfo($filename, PATHINFO_EXTENSION);
$rows = [];

// Read file
if ($extension === 'csv') {
  $handle = fopen($tmpPath, 'r');
  if ($handle === false) {
    echo json_encode(['message' => '❌ Cannot read uploaded CSV.']);
    exit;
  }
  while (($data = fgetcsv($handle, 1000, ',')) !== false) {
    $rows[] = $data;
  }
  fclose($handle);
} elseif (in_array($extension, ['xlsx', 'xls'])) {
  $spreadsheet = IOFactory::load($tmpPath);
  $sheet = $spreadsheet->getActiveSheet();
  $rows = $sheet->toArray();
} else {
  echo json_encode(['message' => '❌ Unsupported file format.']);
  exit;
}

// Remove header
$header = array_map('strtolower', $rows[0]);
unset($rows[0]);

$imported = 0;
foreach ($rows as $row) {
  if (empty(array_filter($row))) continue;

  $packageId = mysqli_real_escape_string($conn, $row[0]);
  $employeeId = mysqli_real_escape_string($conn, $row[1]);
  $origin = mysqli_real_escape_string($conn, $row[2]);
  $flightName = mysqli_real_escape_string($conn, $row[3]);
  $flightCode = mysqli_real_escape_string($conn, $row[4]);
  $flightDepartureDate = mysqli_real_escape_string($conn, $row[5]);
  $returnFlightName = mysqli_real_escape_string($conn, $row[6]);
  $returnFlightCode = mysqli_real_escape_string($conn, $row[7]);
  $returnArrivalDate = mysqli_real_escape_string($conn, $row[8]);
  
  // Remove commas from numeric values
  $wholesalePrice = floatval(str_replace(',', '', $row[9]));
  $flightPrice = floatval(str_replace(',', '', $row[10]));
  $availSeats = intval($row[11]);

  $query = "INSERT INTO flight (packageId, employeeId, origin, flightName, flightCode, flightDepartureDate, flightDepartureTime, 
              flightArrivalDate, flightArrivalTime, returnFlightName, returnFlightCode, returnDepartureDate, returnDepartureTime,
              returnArrivalDate, returnArrivalTime, wholesalePrice, flightPrice, availSeats) VALUES 
              ('$packageId', '$employeeId', '$origin', '$flightName', '$flightCode', '$flightDepartureDate', '05:45:00', $flightDepartureDate, 
              '10:45:00', '$returnFlightName', '$returnFlightCode', '$returnArrivalDate', '12:45:00', '$returnArrivalDate', '04:00:00',
              '$wholesalePrice', '$flightPrice', '$availSeats')";

  if (mysqli_query($conn, $query)) {
    $imported++;
  }
}

echo json_encode(['message' => "✅ Successfully inserted $imported row(s)."]);