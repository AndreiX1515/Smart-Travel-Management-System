<?php

require '../../conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    file_put_contents('debug_voucher.log', "ExportVoucher1Day started at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

    $templateFile = '../../Template/Voucher Template - 2.xlsx';
    $spreadsheet = IOFactory::load($templateFile);
    $sheet = $spreadsheet->getActiveSheet();

    // Page setup
    $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    $sheet->getPageMargins()->setTop(0.2)->setBottom(0.2)->setLeft(0.2)->setRight(0.2);

    $details = $voucher['details'] ?? [];
    $dateAndHotels = $voucher['dateAndHotels'] ?? [];

    // Header
    $voucherCode = $voucher['voucherCode'] ?? '';
    $sheet->setCellValue('I2', strtoupper(date('F j, Y')));
    $sheet->setCellValue('C2', strtoupper(str_replace('VOUCHER-', '', $voucherCode)));

    $sheet->setCellValue('K12', isset($details['detailCreatedAt']) ? date('F j, Y g:i A', strtotime($details['detailCreatedAt'])) : '');
    $sheet->setCellValue('B5', strtoupper($details['sentTo'] ?? ''));
    $sheet->setCellValue('B6', strtoupper($details['sentFrom'] ?? ''));
    $sheet->setCellValue('H5', strtoupper($details['attachment'] ?? 'VOUCHER ONLY'));



    // Tour Period
    $startDate = $details['tourPeriodStart'] ?? '';
    $endDate = $details['tourPeriodEnd'] ?? '';
    $tourPeriod = ($startDate && $endDate) ? date('F j', strtotime($startDate)) . ' - ' . date('F j', strtotime($endDate)) :
        ($startDate ? date('F j', strtotime($startDate)) : ($endDate ? date('F j', strtotime($endDate)) : ''));
    $sheet->setCellValue('H6', strtoupper($tourPeriod));

    $sheet->setCellValue('H7', isset($details['noOfPax']) ? $details['noOfPax'] . ' PAX' : '');


    // Package Name
    $packageName = '';
    if (!empty($details['tourType'])) {
        $stmt = $conn->prepare("SELECT packageName FROM package WHERE packageId = ?");
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

        $stmt->bind_param("i", $details['tourType']);
        $stmt->execute();
        $stmt->bind_result($packageNameResult);
        if ($stmt->fetch()) {
            $packageName = $packageNameResult;
        }
        $stmt->close();
    }

    $words = explode(' ', trim($packageName));
    $shortName = count($words) === 4 ? "{$words[0]} {$words[1]}" : ($words[0] ?? '');
    $sheet->setCellValue('B7', strtoupper("$shortName KOREA TOUR 5D/4N"));



    // Dates & Hotels
    $cellMap = [
        ['start' => 'C12', 'end' => 'C16', 'nights' => 'E12', 'city' => 'F12', 'hotel' => 'H12'],
        ['start' => 'C18', 'end' => 'C22', 'nights' => 'E18', 'city' => 'F18', 'hotel' => 'H18']
        // Add more rows if needed
    ];

    $maxRows = min(count($dateAndHotels), count($cellMap));

    for ($i = 0; $i < $maxRows; $i++) {
        $item = $dateAndHotels[$i];
        $map = $cellMap[$i];

        $sheet->setCellValue($map['start'], strtoupper(!empty($item['startDate']) ? date('F j, Y', strtotime($item['startDate'])) : ''));
        $sheet->setCellValue($map['end'], strtoupper(!empty($item['endDate']) ? date('F j, Y', strtotime($item['endDate'])) : ''));
        $sheet->setCellValue($map['nights'], (!empty($item['nights']) ? $item['nights'] . 'N' : ''));

        // Fetch and display areaName from itinerarydataarea
        $areaName = '';
        if (!empty($item['city'])) {
            $areaId = intval($item['city']);
            $stmt = $conn->prepare("SELECT areaName FROM itinerarydataarea WHERE areaId = ?");
            $stmt->bind_param("i", $areaId);
            $stmt->execute();
            $stmt->bind_result($fetchedAreaName);
            if ($stmt->fetch()) {
                $areaName = $fetchedAreaName;
            }
            $stmt->close();
        }

        // Fetch and display hotelName from hotels
        $hotelName = '';
        if (!empty($item['hotel'])) {
            $hotelId = intval($item['hotel']);
            $stmt = $conn->prepare("SELECT hotelName FROM hotels WHERE hotelId = ?");
            $stmt->bind_param("i", $hotelId);
            $stmt->execute();
            $stmt->bind_result($fetchedHotelName);
            if ($stmt->fetch()) {
                $hotelName = $fetchedHotelName;
            }
            $stmt->close();
        }

        // Set city and hotel into sheet
        $sheet->setCellValue($map['city'], strtoupper($areaName));
        $sheet->setCellValue($map['hotel'], strtoupper($hotelName));
    }





    // Guide
    $guideId = $details['guideId'] ?? '';
    $guideName = 'Unknown Guide';
    if ($guideId) {
        $stmt = $conn->prepare("SELECT fName, lName, countryCode, contactNo FROM employee WHERE accountid = ?");
        $stmt->bind_param("i", $guideId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $fullName = ucwords(strtolower(trim("{$row['fName']} {$row['lName']}")));
            $contact = trim("({$row['countryCode']}) {$row['contactNo']}");
            $guideName = "$fullName $contact";
        }
        $stmt->close();
    }
    $sheet->setCellValue('C25', $guideName);




    // Air Schedules
    if (!empty($voucher['airSchedules'])) {
        foreach ($voucher['airSchedules'] as $i => $flight) {
            if ($i >= 2) break;
            $row = 28 + $i;
            $flightDate = !empty($flight['flightDate']) ? strtoupper(date('F j', strtotime($flight['flightDate']))) : '';
            $flightNo = strtoupper($flight['flightNumber'] ?? '');
            $route = strtoupper("{$flight['origin']} - {$flight['destination']}");
            $timeRange = (isset($flight['departureTime'], $flight['arrivalTime'])) ?
                date('H:i', strtotime($flight['departureTime'])) . ' - ' . date('H:i', strtotime($flight['arrivalTime'])) : '';

            $sheet->setCellValue("E$row", $flightDate);
            $sheet->setCellValue("F$row", $flightNo);
            $sheet->setCellValue("H$row", $route);
            $sheet->setCellValue("J$row", $timeRange);
        }
    }

    // Guide Meeting
    if (!empty($voucher['guideMeeting'][0])) {
        $meeting = $voucher['guideMeeting'][0];
        
        $sheet->setCellValue('C31', strtoupper(date('F j, Y', strtotime($meeting['meetingDate'] ?? ''))));
        $sheet->setCellValue('E31', strtoupper(date('g:i A', strtotime($meeting['meetingTime'] ?? ''))));

        $meetingPlace = $meeting['meetingPlace'] ?? '';
        if (strtoupper(trim($meetingPlace)) === 'ICN') {
            $meetingPlace = 'Incheon Airport (Terminal 1)';
        }
        $sheet->setCellValue('G31', $meetingPlace);
    }


    // Includes Section
    if (!empty($voucher['includes']) && is_array($voucher['includes'])) {
        $row = 33; // Starting row for includes
        foreach ($voucher['includes'] as $item) {
            $id = $item['value'] ?? null;
            if (!is_numeric($id)) continue;

            $stmt = $conn->prepare("SELECT itemName FROM voucherincludeoptions WHERE includesId = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($rowItem = $result->fetch_assoc()) {
                $sheet->setCellValue("C$row", $rowItem['itemName']);
                $row++; // Increment row for next include
            }
            $stmt->close();
        }
    }


    // Excludes Section
    if (!empty($voucher['excludes']) && is_array($voucher['excludes'])) {
        $row = 37; // Starting row for excludes
        foreach ($voucher['excludes'] as $item) {
            $id = $item['value'] ?? null;
            if (!is_numeric($id)) continue;

            $stmt = $conn->prepare("SELECT itemName FROM voucherexcludeoptions WHERE excludesId = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($rowItem = $result->fetch_assoc()) {
                $sheet->setCellValue("C$row", $rowItem['itemName']);
                $row++; // Increment row for next exclude
            }
            $stmt->close();
        }
    }



    // Contact Info
    $sheet->setCellValue('I10', $details['contact'] ?? '');
    $sheet->setCellValue('J11', $details['employeeContact'] ?? '');

    // Output with debug logging
    $voucherName = $voucher['voucherName'] ?? 'Voucher_1_Day';

    if (ob_get_length()) ob_end_clean();

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"{$voucherName}.xlsx\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    file_put_contents('debug_voucher.log', "Excel file output success at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    exit;

} catch (Throwable $e) {
    error_log("Fatal Error: " . $e->getMessage());
    error_log($e->getTraceAsString());
    file_put_contents('debug_voucher.log', "Exception: {$e->getMessage()}\nTrace: {$e->getTraceAsString()}\n", FILE_APPEND);
    http_response_code(500);
    echo "Server error: " . $e->getMessage();
}
?>
