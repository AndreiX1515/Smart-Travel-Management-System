<?php
require '../../conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

file_put_contents('debug_voucher.log', "Script started at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

if (isset($_POST['voucher'])) {
    $voucherJson = $_POST['voucher'];
    file_put_contents('debug_voucher.log', "Received raw JSON:\n$voucherJson\n", FILE_APPEND);

    $voucher = json_decode($voucherJson, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = json_last_error_msg();
        error_log("JSON Decode Error: $error");
        file_put_contents('debug_voucher.log', "JSON decode error: $error\n", FILE_APPEND);
        http_response_code(400);
        echo "Invalid JSON data: $error";
        exit;
    }

    try {
        file_put_contents('debug_voucher.log', "Decoded voucher:\n" . print_r($voucher, true), FILE_APPEND);

        // Determine correct template file
        $dateAndHotels = $voucher['dateAndHotels'] ?? [];
        $templatePath = '../../Template/';
        switch (count($dateAndHotels)) {
            case 1: $templateFile = $templatePath . 'Voucher Template - 1.xlsx'; break;
            case 2: $templateFile = $templatePath . 'Voucher Template - 2.xlsx'; break;
            case 3:

            default: $templateFile = $templatePath . 'Voucher Template.xlsx'; break;
        }

        if (!file_exists($templateFile)) {
            throw new Exception("Template not found: $templateFile");
        }

        file_put_contents('debug_voucher.log', "Loading template: $templateFile\n", FILE_APPEND);
        $spreadsheet = IOFactory::load($templateFile);
        $sheet = $spreadsheet->getActiveSheet();

        // Header setup
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageMargins()->setTop(0.2)->setBottom(0.2)->setLeft(0.2)->setRight(0.2);



        $details = $voucher['details'] ?? [];

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
            ['start' => 'C12', 'end' => 'C15', 'nights' => 'E12', 'city' => 'F12', 'hotel' => 'H12'],
            ['start' => 'C18', 'end' => 'C21', 'nights' => 'E18', 'city' => 'F18', 'hotel' => 'H18'],
            ['start' => 'C24', 'end' => 'C27', 'nights' => 'E24', 'city' => 'F24', 'hotel' => 'H24'],
        ];

        foreach ($dateAndHotels as $i => $item) {
            if ($i > 2) break;
            $map = $cellMap[$i];
            $sheet->setCellValue($map['start'], strtoupper(!empty($item['startDate']) ? date('F j, Y', strtotime($item['startDate'])) : ''));
            $sheet->setCellValue($map['end'], strtoupper(!empty($item['endDate']) ? date('F j, Y', strtotime($item['endDate'])) : ''));
            $sheet->setCellValue($map['nights'], $item['nights'] ?? '');
            $sheet->setCellValue($map['city'], strtoupper($item['city'] ?? ''));
            $sheet->setCellValue($map['hotel'], strtoupper($item['hotel'] ?? ''));
        }

        // Guide
        $guideId = $details['guideId'] ?? '';
        $guideName = 'Unknown Guide';
        if ($guideId) {
            $stmt = $conn->prepare("SELECT fName, mName, lName, countryCode, contactNo FROM employee WHERE id = ?");
            $stmt->bind_param("i", $guideId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $guideName = trim("{$row['fName']} " . (!empty($row['mName']) ? strtoupper(substr($row['mName'], 0, 1)) . '. ' : '') . "{$row['lName']}");
                $guideContact = trim("{$row['countryCode']}" . (!empty($row['countryCode']) && !empty($row['contactNo']) ? '-' : '') . "{$row['contactNo']}");
                $guideName .= " ($guideContact)";
            }
            $stmt->close();
        }
        $sheet->setCellValue('C30', $guideName);

        // Flights
        if (!empty($voucher['airSchedules'])) {
            foreach ($voucher['airSchedules'] as $i => $flight) {
                if ($i >= 2) break;
                $row = 33 + $i;
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
            $sheet->setCellValue('C36', strtoupper(date('F j, Y', strtotime($meeting['meetingDate'] ?? ''))));
            $sheet->setCellValue('E36', strtoupper(date('g:i A', strtotime($meeting['meetingTime'] ?? ''))));
            $sheet->setCellValue('G36', strtoupper($meeting['meetingPlace'] ?? ''));
        }

        // ✅ Includes Section
        if (!empty($voucher['includes']) && is_array($voucher['includes'])) {
            $row = 38;
            foreach ($voucher['includes'] as $item) {
                if (!is_array($item) || !isset($item['value'])) continue;

                $id = $item['value'];

                // If 'others', use label instead
                if ($id === 'others') {
                    $label = isset($item['label']) ? trim($item['label']) : '';
                    if (!empty($label)) {
                        $sheet->setCellValue("C$row", strtoupper($label));
                        $row++;
                    }
                    continue;
                }

                // Skip non-numeric IDs
                if (!is_numeric($id)) continue;

                $id = intval($id);
                $stmt = $conn->prepare("SELECT itemName FROM voucherincludeoptions WHERE includesId = ?");
                
                if (!$stmt) {
                    error_log("Includes Prepare failed: " . $conn->error);
                    continue;
                }

                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($rowItem = $result->fetch_assoc()) {
                    if (!empty($rowItem['itemName'])) {
                        $sheet->setCellValue("C$row", strtoupper($rowItem['itemName']));
                        $row++;
                    }
                }
                $stmt->close();
            }
        }


        // Excludes
        if (!empty($voucher['excludes']) && is_array($voucher['excludes'])) {
            $row = 42;
            foreach ($voucher['excludes'] as $item) {
                if (!is_array($item) || !isset($item['value'])) continue;

                $id = $item['value'];

                // If 'others', use label instead
                if ($id === 'others') {
                    $label = isset($item['label']) ? trim($item['label']) : '';
                    if (!empty($label)) {
                        $sheet->setCellValue("C$row", strtoupper($label));
                        $row++;
                    }
                    continue;
                }

                // Skip non-numeric IDs
                if (!is_numeric($id)) continue;

                $id = intval($id);
                $stmt = $conn->prepare("SELECT itemName FROM voucherexcludeoptions WHERE excludesId = ?");
                if (!$stmt) {
                    error_log("Excludes Prepare failed: " . $conn->error);
                    continue;
                }

                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($rowItem = $result->fetch_assoc()) {
                    if (!empty($rowItem['itemName'])) {
                        $sheet->setCellValue("C$row", strtoupper($rowItem['itemName']));
                        $row++;
                    }
                }
                $stmt->close();
            }
        }

        $sheet->setCellValue('I10', $details['contact'] ?? '');
        $sheet->setCellValue('J11', $details['employeeContact'] ?? '');

        $voucherName = $voucher['voucherName'] ?? 'Voucher_File';

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

} else {
    http_response_code(400);
    echo "Missing voucher data.";
    file_put_contents('debug_voucher.log', "Request failed: No voucher POST data\n", FILE_APPEND);
}
