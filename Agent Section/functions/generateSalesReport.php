<?php
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

Settings::setLocale('en_PH');

// ✅ Decode POSTed JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['reportData']) || !is_array($input['reportData'])) {
  http_response_code(400);
  echo 'Missing or invalid report data.';
  exit;
}

$reportData = $input['reportData'];
$reportFor = $input['reportFor'] ?? 'agent';
$selectedName = $input['selectedName'] ?? '';
$totalRequestAmount = $input['totalRequestAmount'];
$totalFlightAmount = $input['totalFlightAmount'];
$generationDate = date('m/d/Y');

// Create Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Sales Report");
$sheet->getDefaultRowDimension()->setRowHeight(21.75);
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);

// Header
$rowNum = 1;
$sheet->mergeCells("A$rowNum:E$rowNum")->setCellValue("A$rowNum", "SALES REPORT");
$sheet->getStyle("A$rowNum")->getFont()->setBold(true)->setSize(18);
$sheet->getStyle("A$rowNum")->getAlignment()->setHorizontal('center');
$rowNum++;

$sheet->mergeCells("A$rowNum:E$rowNum")->setCellValue("A$rowNum", "Prepared For: $selectedName");
$rowNum++;
$sheet->mergeCells("A$rowNum:E$rowNum")->setCellValue("A$rowNum", "Date Generated: $generationDate");
$rowNum += 2;

// Table Headers
$headers = ['FLIGHT DATE', 'PAX', 'AMOUNT', 'REQUEST TYPE', 'REQ PAX', 'REQ AMOUNT'];
$sheet->fromArray($headers, null, "A$rowNum");

$headerRow = $rowNum;
$rowNum++;

// Table Body
foreach ($reportData as $entry) {
  $name = $entry['name'] ?? '';
  $flightDate = $entry['flightDate'] ?? '';
  $pax = $entry['pax'] ?? '';
  $amount = $entry['amount'] ?? '';
  $requests = $entry['requests'] ?? [];

  if (empty($requests)) {
    // No request — output one row
    $sheet->fromArray([$flightDate, $pax, $amount, '', '', ''], null, "A$rowNum");
    $rowNum++;
  } else {
    // With requests — multiple rows per request
    foreach ($requests as $req) {
      $reqType = $req['type'] ?? '';
      $reqPax = $req['pax'] ?? '';
      $reqAmount = $req['amount'] ?? '';
      $sheet->fromArray([$flightDate, $pax, $amount, $reqType, $reqPax, $reqAmount], null, "A$rowNum");
      $rowNum++;
      // Clear repeated info to avoid redundancy in display (optional)
      $name = $flightDate = $pax = $amount = '';
    }
  }
}

// Format table
$lastRow = $rowNum - 1;
$colCount = count($headers);
$lastCol = chr(64 + $colCount);

// Auto width
foreach (range('A', $lastCol) as $col) {
  $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Borders and header fill
$sheet->getStyle("A$headerRow:$lastCol$lastRow")->applyFromArray([
  'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);
$sheet->getStyle("A$headerRow:$lastCol$headerRow")->getFont()->setBold(true);
$sheet->getStyle("A$headerRow:$lastCol$headerRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');

// ➕ TOTAL ROW (for AMOUNT and REQ AMOUNT)
$totalRow = $rowNum;
$sheet->setCellValue("B$totalRow", "TOTAL");
$sheet->setCellValue("C$totalRow", $totalFlightAmount);
$sheet->setCellValue("F$totalRow", $totalRequestAmount);
$sheet->getStyle("B$totalRow:F$totalRow")->getFont()->setBold(true);
$sheet->getStyle("B$totalRow:F$totalRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF2CC');
$sheet->getStyle("B$totalRow:F$totalRow")->applyFromArray([
  'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

// Output Excel
$filename = "Sales_Report_" . date('Ymd_His') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
