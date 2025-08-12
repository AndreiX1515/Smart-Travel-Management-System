<?php
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="RoomingList_' . date('Y-m-d_H-i-s') . '.xlsx"');
header('Cache-Control: max-age=0');

$input = json_decode(file_get_contents('php://input'), true);
$rooms = $input['rooms'] ?? [];
$travelAgency = $input['travelAgency'] ?? 'Unknown Travel Agency';
$flightDetailsArray = $input['flightDetails'] ?? [];

$flightDetails = '';
$flightDate = '';
// 👇 Helper to format date range
function formatFlightDateRange($startDate, $endDate) {
  try {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);

    if ($start->format('Y') === $end->format('Y')) {
      if ($start->format('F') === $end->format('F')) {
        // Same month & year → July 6–11, 2025
        return $start->format('F j') . '–' . $end->format('j, Y');
      } else {
        // Different month, same year → July 28 – August 2, 2025
        return $start->format('F j') . ' – ' . $end->format('F j, Y');
      }
    } else {
      // Different year → Dec 30, 2025 – Jan 6, 2026
      return $start->format('M j, Y') . ' – ' . $end->format('M j, Y');
    }
  } catch (Exception $e) {
    return 'Invalid Travel Dates';
  }
}

if (!empty($flightDetailsArray)) {
  $flightCode = $flightDetailsArray['flightCode'] ?? 'N/A';
  $departureDate = $flightDetailsArray['flightDepartureDate'] ?? null;

  $arrivalTime = substr($flightDetailsArray['flightArrivalTime'] ?? '', 0, 5);
  $departureTime = substr($flightDetailsArray['flightDepartureTime'] ?? '', 0, 5);
  $returnETD = substr($flightDetailsArray['returnFlightDepartureTime'] ?? '', 0, 5);
  $returnETA = substr($flightDetailsArray['returnArrivalTime'] ?? '', 0, 5);

  $returnFlightCode = $flightDetailsArray['returnFlightCode'] ?? 'N/A';
  $returnDate = $flightDetailsArray['arrivalDate'] ?? null;

  $flightDetails = "{$flightCode} {$departureTime}-{$arrivalTime} / "
                  . "{$returnFlightCode} {$returnETD}-{$returnETA}";

  $flightDate = formatFlightDateRange($departureDate, $returnDate);
}

$totalGuests = array_reduce($rooms, fn($sum, $room) => $sum + count($room['guests']), 0);
$totalRooms = count(array_unique(array_map(fn($room) => $room['roomNumber'] ?? '', array_merge(...array_map(fn($r) => $r['guests'], $rooms)))));

$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Malgun Gothic')->setSize(10); // 👈 Apply font globally

$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Rooming List');

$logo = new Drawing();
$logo->setName('Company Logo');
$logo->setDescription('Company Logo');
$logo->setPath('../../Template/SMT-Rooming-List-Logo.png');
$logo->setResizeProportional(false);
$logo->setHeight(75); // height in pixels
$logo->setWidth(750); // width in pixels
$logo->setCoordinates('A1'); // You can adjust this if needed
$logo->setOffsetX(10);
$logo->setOffsetY(5);
$logo->setWorksheet($sheet);

$sheet->getRowDimension(1)->setRowHeight(32);      // 42 pixels
$sheet->getRowDimension(2)->setRowHeight(33.5);   // 44 pixels

// -------------------------
// Info Table Rows 3–5
// -------------------------
$sheet->setCellValue('A3', 'TRAVEL AGENCY: ' . $travelAgency);
$sheet->setCellValue('A4', 'TRAVEL DATE: ' . $flightDate);
$sheet->setCellValue('A5', 'NO. OF PAX: ' . $totalGuests);

$sheet->setCellValue('J3', 'ROOMS: ' . $totalRooms);
$sheet->setCellValue('J4', 'BREAKFAST: '. $totalGuests);
$sheet->setCellValue('J5', 'FLIGHT DETAILS: '. $flightDetails);

// Merge info table cells
$sheet->mergeCells('A3:I3');
$sheet->mergeCells('A4:I4');
$sheet->mergeCells('A5:I5');
$sheet->mergeCells('J3:R3');
$sheet->mergeCells('J4:R4');
$sheet->mergeCells('J5:R5');

// Style for info table
$sheet->getStyle('A3:R5')->applyFromArray([
  'font' => ['bold' => true, 'size' => 10, 'name' => 'Malgun Gothic'],
  'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
  'borders' => [
    'allBorders' => [
      'borderStyle' => Border::BORDER_THIN
    ]
  ]
]);


// Spacer Row 6
// $sheet->getRowDimension(6)->setRowHeight(10);
// Merge entire Row 6 (A6:R6) and apply styling
$sheet->mergeCells('A6:R6');
$sheet->getStyle('A6:R6')->applyFromArray([
  'font' => ['name' => 'Malgun Gothic', 'size' => 10],
  'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FCE4B2']],
  'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
  'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);
$sheet->setCellValue('A6', ''); // Optional placeholder content
$sheet->getRowDimension(6)->setRowHeight(18);

// -------------------------
// Table Headers (Rows 7–8)
// -------------------------
$headers = [
  '#', 'AGE', 'MS/MR', 'GIVEN NAME', 'SURNAME', 'FULL NAME',
  'DOB', 'NAT.', 'PASSPORT', 'I of E Issue of Date', 'D of E Expiry of Date',
  'SEX', '', 'ROOMING', '', 'Tipping', 'LUGGAGE(AIR TICKET)', 'REMARKS(Separate air time, wheelchair, etc)'
];

$subHeaders = array_fill(0, count($headers), '');

$sheet->fromArray($headers, null, 'A7');
$sheet->fromArray($subHeaders, null, 'A8');

$sheet->mergeCells('L7:M7'); // SEX
$sheet->mergeCells('N7:O7'); // ROOMING

// Style headers
$sheet->getStyle('A7:R7')->applyFromArray([
  'font' => ['bold' => true, 'name' => 'Malgun Gothic', 'size' => 10],
  'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'A9D18E']],
  'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
  'alignment' => [
    'horizontal' => Alignment::HORIZONTAL_CENTER,
    'vertical' => Alignment::VERTICAL_CENTER,
    'wrapText' => true                    // ✅ enable wrap
  ]
]);

// Style subheaders
$sheet->getStyle('A8:R8')->applyFromArray([
  'font' => ['name' => 'Malgun Gothic', 'size' => 10],
  'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FCE4B2']],
  'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]],
  'alignment' => [
    'horizontal' => Alignment::HORIZONTAL_CENTER,
    'vertical' => Alignment::VERTICAL_CENTER,
    'wrapText' => true                    // ✅ enable wrap
  ]
]);

$sheet->getRowDimension(7)->setRowHeight(24);
$sheet->getRowDimension(8)->setRowHeight(18);

// -------------------------
// Guest Data Rows (start at 9)
// -------------------------
$row = 9;
$counter = 1;
$displayRoomNumber = 1;

// Step 1: Group guests by actual saved roomNumber
$roomGroups = []; // roomNumber => array of guests
foreach ($rooms as $room) {
  foreach ($room['guests'] as $guest) {
    $roomNo = $guest['roomNumber'] ?? 'Unassigned';
    $roomGroups[$roomNo]['guests'][] = $guest;

    // ✅ only set roomType if not already set (preserve original value)
    $roomGroups[$roomNo]['roomType'] = strtoupper($guest['roomType'] ?? ($room['type'] ?? ''));
  }
}

// Step 2: Loop grouped rooms
foreach ($roomGroups as $group) {
  $guests = $group['guests'];
  $roomType = $group['roomType'] ?? '';
  $rowSpan = count($guests);
  $firstRow = $row;

  foreach ($guests as $guest) {
    $suffix = ($guest['suffix'] ?? '') === 'N/A' ? '' : ' ' . trim($guest['suffix']);
    $prefix = $guest['prefix'] ?? '';

    // Write guest data row (skip tipping text, add cell background color instead)
    $sheet->fromArray([
      $counter++,
      $guest['age'] ?? '',
      $prefix,
      trim(($guest['fName'] ?? '') . $suffix),
      $guest['lName'] ?? '',
      $guest['fullName'] ?? '',
      $guest['dob'] ?? '',
      $guest['nationality'] ?? '',
      $guest['passport'] ?? '',
      $guest['passportIssued'] ?? '',
      $guest['passportExp'] ?? '',
      $guest['sex'] ?? '',
      $guest['genderValue'] ?? '',
      '', // N: Room Number
      '', // O: Room Type
      '', // P: Tipping (leave blank, use color instead)
      implode(", ", (array)($guest['luggageText'] ?? [])),
      $guest['remarks'] ?? ''
    ], null, 'A' . $row);

    // Handle tipping color only (column P)
    $tipping = strtolower(trim($guest['tip'] ?? ''));
    $fillColor = null;

    if ($tipping === 'in korea') {
      $fillColor = 'ADD8E6'; // Light Blue
    } elseif ($tipping === 'in manila') {
      $fillColor = 'FFFF00'; // Yellow
    }

    if ($fillColor) {
      $sheet->getStyle("P{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB($fillColor);
    }

    $row++;
  }

  // Merge & write room number and type
  $sheet->setCellValue("N{$firstRow}", $displayRoomNumber);
  $sheet->setCellValue("O{$firstRow}", $roomType);

  $roomColor = null;
  if (stripos($roomType, 'twin') !== false) {
    $roomColor = 'FFFF00'; // Yellow
  } elseif (stripos($roomType, 'double') !== false) {
    $roomColor = 'B57EDC'; // Lavender
  } elseif (stripos($roomType, 'triple') !== false) {
    $roomColor = '3CFF00'; // green
  } elseif (stripos($roomType, 'single') !== false) {
    $roomColor = '003cffff'; // blue
  }

  if ($roomColor) {
    $sheet->getStyle("O{$firstRow}:O" . ($firstRow + $rowSpan - 1))
          ->getFill()->setFillType(Fill::FILL_SOLID)
          ->getStartColor()->setRGB($roomColor);
  }

  if ($rowSpan > 1) {
    $sheet->mergeCells("N{$firstRow}:N" . ($firstRow + $rowSpan - 1));
    $sheet->mergeCells("O{$firstRow}:O" . ($firstRow + $rowSpan - 1));
  }

  $sheet->getStyle("N{$firstRow}:O" . ($firstRow + $rowSpan - 1))
    ->getAlignment()
    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

  $displayRoomNumber++;
}



$dataStart = 9;
$dataEnd = $row - 1;

// Style data rows
$sheet->getStyle("A{$dataStart}:R{$dataEnd}")->applyFromArray([
  'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
  'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
]);

$sheet->getStyle("A{$dataStart}:R{$dataEnd}")
      ->getAlignment()
      ->setHorizontal(Alignment::HORIZONTAL_CENTER)
      ->setVertical(Alignment::VERTICAL_CENTER);

// Column-specific alignment
$sheet->getStyle("B{$dataStart}:B{$dataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("C{$dataStart}:C{$dataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("L{$dataStart}:M{$dataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("N{$dataStart}:O{$dataEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Define target column
$tippingCol = 'P';

for ($i = $dataStart; $i <= $dataEnd; $i++) {
  $cell = $tippingCol . $i;
  $value = $sheet->getCell($cell)->getValue();

  if (strcasecmp(trim($value), 'In Korea') === 0) {
    // Blue - Accent 1, Lighter 40% (#A9D0F5 or similar)
    $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
          ->getStartColor()->setRGB('A9D0F5');
  } elseif (strcasecmp(trim($value), 'In Manila') === 0) {
    // Yellow (standard Excel yellow: FFFF00)
    $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
          ->getStartColor()->setRGB('FFFF00');
  }
}

// Auto-size columns A–R
// foreach (range('A', 'R') as $col) {
//     $sheet->getColumnDimension($col)->setAutoSize(true);
// }

// Set fixed column widths (converted from pixels to approx. character widths)
$columnWidths = [
  'A' => 28,  'B' => 44,  'C' => 52,  'D' => 136, 'E' => 141,
  'F' => 196, 'G' => 151, 'H' => 41,  'I' => 112, 'J' => 112,
  'K' => 105, 'L' => 31,  'M' => 31,  'N' => 24,  'O' => 65,
  'P' => 65,  'Q' => 151, 'R' => 254
];

foreach ($columnWidths as $col => $pixels) {
  $charWidth = round($pixels / 7, 2); // rough pixel to character width
  $sheet->getColumnDimension($col)->setWidth($charWidth);
}

// Freeze headers (row 9 is data start)
$sheet->freezePane('A9');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
