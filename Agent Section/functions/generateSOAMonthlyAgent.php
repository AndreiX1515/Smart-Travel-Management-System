<?php
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

Settings::setLocale('en_PH');

function htmlTableRowsToArray($html) {
  $rows = [];
  if (empty($html)) return $rows;

  libxml_use_internal_errors(true);
  $dom = new DOMDocument();
  $dom->loadHTML('<?xml encoding="UTF-8"><table>' . $html . '</table>');
  $trs = $dom->getElementsByTagName('tr');

  foreach ($trs as $tr) {
    $cols = [];
    foreach ($tr->getElementsByTagName('td') as $td) {
      $text = html_entity_decode($td->nodeValue, ENT_QUOTES | ENT_HTML5, 'UTF-8');
      $text = str_replace('â±', '₱', $text);
      $cols[] = trim($text);
    }
    if (!empty($cols)) {
      $rows[] = $cols;
    }
  }
  return $rows;
}

$input = json_decode(file_get_contents('php://input'), true);
$accountName = $input['accountName'] ?? 'Unknown Recipient';
$fromName = $input['fromName'] ?? 'Unknown Agent';
$fromCompany = $input['fromCompany'] ?? '';
$generationDate = date('m/d/Y');
$soaNumber = $input['soaNumber'] ?? 'SOA_Unknown';
$data = $input['data'] ?? [];
$month = $input['month'] ?? '';
$year = $input['year'] ?? '';
$monthYearLabel = ($month && $year) ? strtoupper(date('F', mktime(0, 0, 0, $month, 1))) . " $year" : '';

$flights = htmlTableRowsToArray($data['flights']['rows'] ?? '');
$requests = htmlTableRowsToArray($data['requests']['rows'] ?? '');
$payments = htmlTableRowsToArray($data['payments']['rows'] ?? '');
$balancePHP = $data['balance']['php'] ?? '0.00';
$balanceUSD = $data['balance']['usd'] ?? '0.00';
$flightSubtotalPHP = $data['flights']['subtotalPHP'] ?? '0.00';
$requestSubtotalPHP = $data['requests']['subtotalPHP'] ?? '0.00';
$paymentSubtotalPHP = $data['payments']['subtotalPHP'] ?? '0.00';

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("SOA-" . $soaNumber);
$sheet->getDefaultRowDimension()->setRowHeight(21.75);
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(14);

$logo = new Drawing();
$logo->setName('Company Logo');
$logo->setDescription('Company Logo');
$logo->setPath('../../Template/SMT-MANILA-LOGO.png');
$logo->setResizeProportional(false);
$logo->setHeight(150); // height in pixels
$logo->setWidth(1000); // width in pixels
$logo->setCoordinates('B1');
$logo->setOffsetX(10);
$logo->setOffsetY(5);
$logo->setWorksheet($sheet);

$sheet->mergeCells('A6:G7')->setCellValue('A6', 'STATEMENT OF ACCOUNT' . ($monthYearLabel ? " - $monthYearLabel" : ''));
$sheet->getStyle('A6')->getFont()->setBold(true)->setSize(28);
$sheet->getStyle('A6')->getAlignment()->setHorizontal('center');
$sheet->getStyle('A6:G7')->getAlignment()->setVertical('center');
$rowNum = 9;

$sheet->mergeCells("A$rowNum:G$rowNum")->setCellValue("A$rowNum", "BILL TO: $accountName");
$sheet->getStyle("A$rowNum")->getFont()->setSize(16);
$rowNum++;

$sheet->mergeCells("A$rowNum:D$rowNum")->setCellValue("A$rowNum", "FROM: $fromCompany / $fromName");
$sheet->mergeCells("E$rowNum:G$rowNum")->setCellValue("E$rowNum", "Date: $generationDate");
$sheet->getStyle("A$rowNum:G$rowNum")->getFont()->setSize(16);
$rowNum++;

$sheet->fromArray(['No', 'Contents', '$ Price', '₱ Price', 'PAX', '$ Total', '₱ Total'], null, "A$rowNum");
$headerRow = $rowNum;
$sheet->getStyle("A$rowNum:G$rowNum")->getFont()->setBold(true)->setSize(16);
$sheet->getStyle("A$rowNum:G$rowNum")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
$rowNum++;

$serial = 1;
foreach ($flights as $row) {
  $row[0] = $serial++;
  $sheet->fromArray($row, null, "A$rowNum");
  $sheet->getStyle("A$rowNum:G$rowNum")->getFont()->setSize(14);
  $rowNum++;
}

foreach ($requests as $row) {
  $row[0] = $serial++;
  $sheet->fromArray($row, null, "A$rowNum");
  $sheet->getStyle("A$rowNum:G$rowNum")->getFont()->setSize(14);
  $rowNum++;
}

$combinedSubtotal = number_format(
  floatval(str_replace(',', '', $flightSubtotalPHP)) + floatval(str_replace(',', '', $requestSubtotalPHP)),
  2,
  '.',
  ','
);

$sheet->setCellValue("A$rowNum", $serial++);
$sheet->mergeCells("E$rowNum:F$rowNum")->setCellValue("E$rowNum", 'Sub Total');
$sheet->setCellValue("G$rowNum", $combinedSubtotal);
$sheet->getStyle("B$rowNum:G$rowNum")->getFont()->setBold(true)->setSize(14);
$sheet->getStyle("A$rowNum:G$rowNum")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF2CC');
$rowNum++;

foreach ($payments as $row) {
  $row[0] = $serial++;
  if (isset($row[6])) {
    $row[6] = '-' . $row[6];
  }
  $sheet->fromArray($row, null, "A$rowNum");
  $sheet->getStyle("A$rowNum:G$rowNum")->getFont()->setSize(14);
  $sheet->getStyle("G$rowNum")->getFont()->getColor()->setRGB('FF0000');
  $rowNum++;
}

$sheet->setCellValue("A$rowNum", $serial++);
$sheet->mergeCells("E$rowNum:F$rowNum")->setCellValue("E$rowNum", 'Sub Total');
$sheet->setCellValue("G$rowNum", "-₱ $paymentSubtotalPHP");
$sheet->getStyle("B$rowNum:G$rowNum")->getFont()->setBold(true)->setSize(14);
$sheet->getStyle("A$rowNum:G$rowNum")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF2CC');
$sheet->getStyle("G$rowNum")->getFont()->getColor()->setRGB('FF0000');
$rowNum++;

$sheet->mergeCells("A$rowNum:E$rowNum")->setCellValue("A$rowNum", 'BALANCE');
$sheet->getStyle("A$rowNum")->getAlignment()->setHorizontal('center');
$sheet->setCellValue("G$rowNum", "-₱ $balancePHP");
$sheet->getStyle("A$rowNum:G$rowNum")->getFont()->setBold(true)->setSize(14);
$sheet->getStyle("A$rowNum:G$rowNum")->getFont()->getColor()->setRGB('FF0000');
$sheet->getStyle("A$rowNum:G$rowNum")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFD966');

$tableEndRow = $rowNum;
$rowNum++;

$sheet->getStyle("A$headerRow:G$tableEndRow")->applyFromArray([
  'borders' => [
    'allBorders' => [
      'borderStyle' => Border::BORDER_THIN,
      'color' => ['argb' => '000000'],
    ]
  ]
]);

$sheet->mergeCells("A$rowNum:G$rowNum")->setCellValue("A$rowNum", 'ACCOUNT INFORMATION');
$sheet->getStyle("A$rowNum")->getFont()->setSize(14)->setBold(true);
$rowNum++;
$sheet->mergeCells("A$rowNum:G$rowNum")->setCellValue("A$rowNum", 'Bank Name : B D O (Zuellig Branch MAKATI AVENUE)'); $rowNum++;
$sheet->mergeCells("A$rowNum:G$rowNum")->setCellValue("A$rowNum", 'Name of Account : KIM HYUNG SUB (Nick name  Jedkim )'); $rowNum++;
$sheet->mergeCells("A$rowNum:G$rowNum")->setCellValue("A$rowNum", 'Peso Account No.: 007800151678'); $rowNum++;
$sheet->mergeCells("A$rowNum:G$rowNum")->setCellValueExplicit("A$rowNum", 'US Dollar Account No : 107800113512', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); $rowNum++;

$accountInfoStart = $rowNum - 5;
$accountInfoEnd = $rowNum - 1;
for ($i = $accountInfoStart; $i <= $accountInfoEnd; $i++) {
  foreach (range('A', 'G') as $col) {
    $cell = $col . $i;
    $borders = ['left' => ['borderStyle' => Border::BORDER_THIN], 'right' => ['borderStyle' => Border::BORDER_THIN]];
    if ($i === $accountInfoStart) $borders['top'] = ['borderStyle' => Border::BORDER_THIN];
    if ($i === $accountInfoEnd) $borders['bottom'] = ['borderStyle' => Border::BORDER_THIN];
    $sheet->getStyle($cell)->applyFromArray(['borders' => $borders]);
  }
}

$sheet->getColumnDimension('A')->setWidth(3);
$sheet->getColumnDimension('B')->setWidth(40);
$sheet->getColumnDimension('C')->setWidth(12);
$sheet->getColumnDimension('D')->setWidth(12);
$sheet->getColumnDimension('E')->setWidth(5);
$sheet->getColumnDimension('F')->setWidth(12);
$sheet->getColumnDimension('G')->setWidth(12);

$currencyFormatPeso = '#,##0.00';
$currencyFormatUSD = '"$"#,##0.00';
$sheet->getStyle("D$headerRow:D$rowNum")->getNumberFormat()->setFormatCode($currencyFormatPeso);
$sheet->getStyle("F$headerRow:F$rowNum")->getNumberFormat()->setFormatCode($currencyFormatUSD);
$sheet->getStyle("G$headerRow:G$rowNum")->getNumberFormat()->setFormatCode($currencyFormatPeso);
$sheet->getStyle("B$headerRow:E$rowNum")->getAlignment()->setHorizontal('left');
$sheet->getStyle("F$headerRow:G$rowNum")->getAlignment()->setHorizontal('right');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=SOA_{$soaNumber}_{$month}_{$year}.xlsx");
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
