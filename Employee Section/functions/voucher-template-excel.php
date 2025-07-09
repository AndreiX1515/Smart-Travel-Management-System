<?php
require '../../conn.php';  // Ensure database connection is included
require '../../vendor/autoload.php';  // Ensure Composer autoloader is included

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if (isset($_POST['voucher'])) {

  $voucher = json_decode($_POST['voucher'], true);






  try {

    // Date and Hotels
    $dateAndHotels = $voucher['dateAndHotels'] ?? [];

    // Choose template based on number of date/hotel entries
    switch (count($dateAndHotels)) {
        case 1:
            $templateFile = '../../Template/Voucher Template 1.xlsx';
            break;
        case 2:
            $templateFile = '../../Template/Voucher Template 2.xlsx';
            break;
        case 3:
        default:
            $templateFile = '../../Template/Voucher Template.xlsx';
            break;
    }


    // To be fixed the route of below code based on selected template
    // If you have different templates for 1, 2, or 3 entries,




    $templateFile = '../../Template/Voucher Template.xlsx';

    if (!file_exists($templateFile)) {
      throw new Exception("Template file not found.");
    }

    $spreadsheet = IOFactory::load($templateFile);
    $sheet = $spreadsheet->getActiveSheet();

    // Page setup
    $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    $sheet->getPageMargins()
      ->setTop(0.2)
      ->setBottom(0.2)
      ->setLeft(0.2)
      ->setRight(0.2)
      ->setHeader(0.25)
      ->setFooter(0.25);



    // ==================== HEADER FIELDS =================================== //
    $voucherCode = $voucher['voucherCode'] ?? [];

    $currentDateFull = date('F j, Y');  // e.g. "May 20, 2025"

    $sheet->setCellValue('I2', $currentDateFull);

    $sheet->setCellValue('C2', strtoupper($voucherCode ?? ''));

    $details = $voucher['details'] ?? [];

    $sheet->setCellValue('K12', isset($details['detailCreatedAt']) ? date('F j, Y g:i A', strtotime($details['detailCreatedAt'])) : '');

    $sheet->setCellValue('B5', strtoupper($details['sentTo'] ?? ''));
    $sheet->setCellValue('B6', strtoupper($details['sentFrom'] ?? ''));
    $sheet->setCellValue('B7', strtoupper($details['tourType'] ?? ''));

    $sheet->setCellValue('H5', strtoupper($details['attachment'] ?? ''));

    // Format without year: "F j" = Month name and day number only
    $startDate = isset($details['tourPeriodStart']) ? date('F j', strtotime($details['tourPeriodStart'])) : '';
    $endDate = isset($details['tourPeriodEnd']) ? date('F j', strtotime($details['tourPeriodEnd'])) : '';

    // Combine with a dash if both exist, else show just one or empty string
    $tourPeriod = '';
    if ($startDate && $endDate) {
        $tourPeriod = $startDate . ' - ' . $endDate;
    } elseif ($startDate) {
        $tourPeriod = $startDate;
    } elseif ($endDate) {
        $tourPeriod = $endDate;
    }

    // Put combined tour period into one cell, for example E7
    $sheet->setCellValue('H6', strtoupper($tourPeriod ?? ''));

    // Optionally clear F7 or leave empty if you don’t want the end date separately
    // $sheet->setCellValue('F7', '');

    $totalPax = $details['noOfPax'] . ' ' . 'PAX';

    $sheet->setCellValue('H7', $totalPax ?? '');



    // Get packageName based on tourType (which holds packageId)
    $packageName = '';
    if (!empty($details['tourType'])) {
        $packageId = $details['tourType'];

        // Prepare and execute query to fetch packageName
        $stmt = $conn->prepare("SELECT packageName FROM package WHERE packageId = ?");
        $stmt->bind_param("i", $packageId);
        $stmt->execute();
        $stmt->bind_result($packageNameResult);
        if ($stmt->fetch()) {
            $packageName = $packageNameResult;
        }
        $stmt->close();
    }

    function getPackageDisplayName($packageName) {
        $words = explode(' ', trim($packageName));
        $wordCount = count($words);

        if ($wordCount === 4) {
            // Return first and second words if exactly 4 words
            return $words[0] . ' ' . $words[1];
        } else {
            // Otherwise, return only the first word
            return $words[0];
        }
    }

    $displayName = getPackageDisplayName($packageName);
    $sheet->setCellValue('B7', strtoupper($displayName . ' KOREA TOUR 5D/4N'));





    // ==================== BODY FIELDS =================================== //
    
    $cellMap = [
        ['start' => 'C12', 'end' => 'C15', 'nights' => 'E12', 'city' => 'F12', 'hotel' => 'H12'],
        ['start' => 'C18', 'end' => 'C21', 'nights' => 'E18', 'city' => 'F18', 'hotel' => 'H18'],
        ['start' => 'C24', 'end' => 'C27', 'nights' => 'E24', 'city' => 'F24', 'hotel' => 'H24'],
    ];





    $maxItems = min(3, count($dateAndHotels));

    for ($i = 0; $i < $maxItems; $i++) {
        $item = $dateAndHotels[$i];
        $map = $cellMap[$i];

        $startDate = !empty($item['startDate']) ? date('F j, Y', strtotime($item['startDate'])) : '';
        $endDate = !empty($item['endDate']) ? date('F j, Y', strtotime($item['endDate'])) : '';
        
        $sheet->setCellValue($map['start'], strtoupper($startDate));   // e.g., May 19, 2025
        $sheet->setCellValue($map['end'], strtoupper($endDate));       // e.g., May 21, 2025
        $sheet->setCellValue($map['nights'], $item['nights'] ?? '');
        $sheet->setCellValue($map['city'], strtoupper($item['city'] ?? ''));
        $sheet->setCellValue($map['hotel'], strtoupper($item['hotel'] ?? ''));

        // Hotel Details to be Add






    }


    // Guide
    $guideId = $details['guideName'] ?? ''; // assuming this is employee id (int)
    $guideFullName = '';
    $employeeContact = '';

    if (!empty($guideId)) {
        $stmt = $conn->prepare("SELECT fName, mName, lName, countryCode, contactNo FROM employee WHERE id = ?");
        $stmt->bind_param("i", $guideId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Prepare each name part safely
            $firstName = !empty($row['fName']) ? $row['fName'] : '';
            $middleInitial = !empty($row['mName']) ? strtoupper(substr($row['mName'], 0, 1)) . '.' : '';
            $lastName = !empty($row['lName']) ? $row['lName'] : '';

            // Compose full name, only join parts if they exist
            $nameParts = array_filter([$firstName, $middleInitial, $lastName]);
            $guideFullName = implode(' ', $nameParts);

            // Compose contact with country code and contact number if available
            $countryCode = !empty($row['countryCode']) ? $row['countryCode'] : '';
            $contactNo = !empty($row['contactNo']) ? $row['contactNo'] : '';
            $employeeContact = trim($countryCode . ($countryCode && $contactNo ? '-' : '') . $contactNo);
        }
        $stmt->close();
    }

    $displayText = $guideFullName ?: 'Unknown Guide'; // fallback if empty name

    if (!empty($employeeContact)) {
        $displayText .= " ({$employeeContact})";
    }

    $sheet->setCellValue('C30', $displayText);


    // Air Schedule
    if (isset($voucher['airSchedules']) && is_array($voucher['airSchedules'])) {
        foreach ($voucher['airSchedules'] as $index => $flight) {
            if ($index >= 2) break; // Maximum of 2 flights

            $row = 33 + $index; // 33 for departure1, 34 for departure2

            // Format flight date without year
            $flightDate = isset($flight['flightDate']) ? date('F j', strtotime($flight['flightDate'])) : '';

            // Combine origin and destination in uppercase
            $route = strtoupper(($flight['origin'] ?? '') . ' - ' . ($flight['destination'] ?? ''));

            // Format departure and arrival time in 24-hour format without seconds
            $departureTime = isset($flight['departureTime']) ? date('H:i', strtotime($flight['departureTime'])) : '';
            $arrivalTime = isset($flight['arrivalTime']) ? date('H:i', strtotime($flight['arrivalTime'])) : '';
            $timeRange = ($departureTime && $arrivalTime) ? "$departureTime - $arrivalTime" : '';

            // Assign to cells
            $sheet->setCellValue("E{$row}", strtoupper($flightDate));                // Flight Date (without year)
            $sheet->setCellValue("F{$row}", strtoupper($flight['flightNumber'] ?? '')); // Flight Number uppercase
            $sheet->setCellValue("H{$row}", $route);                     // Route (MNL - ICN)
            $sheet->setCellValue("J{$row}", $timeRange);                 // Time Range (24h format)
        }
    }


    // Guide Meeting
    if (isset($voucher['guideMeeting']) && is_array($voucher['guideMeeting']) && count($voucher['guideMeeting']) > 0) {
        $meeting = $voucher['guideMeeting'][0]; // Assuming only one meeting

        // Format meeting date
        $meetingDate = isset($meeting['meetingDate']) ? date('F j, Y', strtotime($meeting['meetingDate'])) : '';

        // Format meeting time without seconds, e.g. "12:15 PM"
        $meetingTime = isset($meeting['meetingTime']) ? date('g:i A', strtotime($meeting['meetingTime'])) : '';

        // Meeting place
        $meetingPlace = $meeting['meetingPlace'] ?? '';

        // Set cells
        $sheet->setCellValue('C36', strtoupper($meetingDate));
        $sheet->setCellValue('E36', strtoupper($meetingTime));
        $sheet->setCellValue('G36', strtoupper($meetingPlace));
    }

    // Includes
    if (isset($voucher['includes']) && is_array($voucher['includes'])) {
        $startRow = 38; // starting row to write include items, adjust as needed
        foreach ($voucher['includes'] as $include) {
            $includeId = intval($include['value']);
            if ($includeId > 0) {
                // Fetch itemName from DB
                $stmt = $conn->prepare("SELECT itemName FROM voucherincludeoptions WHERE includeItemId = ?");
                $stmt->bind_param("i", $includeId);
                $stmt->execute();
                $result = $stmt->get_result();
                $itemName = '';
                if ($result && $row = $result->fetch_assoc()) {
                    $itemName = strtoupper($row['itemName']); // uppercase as per your style
                }
                $stmt->close();

                // Set cell value, e.g. Column B for include item name, adjust column as needed
                $sheet->setCellValue('C' . $startRow, $itemName);

                // Increment row for next include
                $startRow++;
            }
        }
    }

    // Excludes
    if (isset($voucher['excludes']) && is_array($voucher['excludes'])) {
        $startRow = 42; // adjust start row for excludes (below includes)
        foreach ($voucher['excludes'] as $exclude) {
            $excludeId = intval($exclude['value']);
            if ($excludeId > 0) {
                $stmt = $conn->prepare("SELECT itemName FROM voucherexcludeoptions WHERE excludeItemId = ?");
                $stmt->bind_param("i", $excludeId);
                $stmt->execute();
                $result = $stmt->get_result();
                $itemName = '';
                if ($result && $row = $result->fetch_assoc()) {
                    $itemName = strtoupper($row['itemName']);
                }
                $stmt->close();
                $sheet->setCellValue('C' . $startRow, $itemName);
                $startRow++;
            }
        }
    }













    
    $sheet->setCellValue('I10', $details['contact'] ?? '');
    $sheet->setCellValue('J11', $details['employeeContact'] ?? '');

    


























    // Set filename for download, fallback if not provided
    $voucherName = !empty($voucher['voucherName']) ? $voucher['voucherName'] : 'Voucher_File';

    // === OUTPUT EXCEL FILE ===
    if (ob_get_length()) {
      ob_end_clean(); // Clear output buffer to prevent corrupt file
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"{$voucherName}.xlsx\"");
    header('Cache-Control: max-age=0');
    header('Expires: 0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

  } catch (Exception $e) {
    error_log("Excel generation error: " . $e->getMessage());
    http_response_code(500);
    echo "Error generating the Excel file.";
  }
} else {
  http_response_code(400);
  echo "Missing voucher data.";
}
