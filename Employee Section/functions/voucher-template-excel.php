<?php
require '../../vendor/autoload.php';  // Ensure Composer's autoloader is included

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Ensure POST request contains itinerary and days details
if (isset($_POST['voucher'])) {
    $voucher = json_decode($_POST['voucher'], true);


    try {
        // Load the template Excel file
        $templateFile = '../../Template/Voucher Template.xlsx';  
        $spreadsheet = IOFactory::load($templateFile);
        $sheet = $spreadsheet->getActiveSheet();

        // === Set Page Size and Margins === //
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        $pageMargins = $sheet->getPageMargins();
        $pageMargins->setTop(0.2);       // 0.2 inch top margin
        $pageMargins->setBottom(0.75);   // 0.75 inch bottom margin
        $pageMargins->setLeft(0.25);     // 0.25 inch left margin
        $pageMargins->setRight(0.15);    // 0.15 inch right margin
        $pageMargins->setHeader(0.3);    // 0.3 inch header
        $pageMargins->setFooter(0.3);    // 0.3 inch footer



        // ======================= HEADER ============================ //

        $sheet->setCellValue('B5', strtoupper($voucher['details']['sentTo']));

        $sheet->setCellValue('B6', strtoupper($voucher['details']['sentFrom']));;


        

        $sheet->setCellValue('C5', $voucher['details']['tourType']);



        $sheet->setCellValue('D6', ucfirst($voucher['details']['attachment']));

        $startDate = date_create($voucher['details']['tourPeriodStart']);
        $sheet->setCellValue('E7', $startDate->format('F j, Y'));

        $endDate = date_create($voucher['details']['tourPeriodEnd']);
        $sheet->setCellValue('F7', $endDate->format('F j, Y'));

        $sheet->setCellValue('G8', $voucher['details']['noOfPax']);
        $sheet->setCellValue('H9', $voucher['details']['guideName']);
        $sheet->setCellValue('I10', $voucher['details']['contact']);
        $sheet->setCellValue('J11', $voucher['details']['employeeContact']);

        $createdAt = date_create($voucher['details']['detailCreatedAt']);
        $sheet->setCellValue('K12', $createdAt->format('F j, Y g:i A'));











        // =========== Hotels =========== //
        $startRow = 11; // Starting row
        $colCity = 'C'; // Column for cities
        $colHotel = 'F'; // Column for hotels

        if (isset($itineraryDetails['cities'])) {
            foreach ($itineraryDetails['cities'] as $index => $cityInfo) {
                $row = $startRow + $index;

                // Construct cell references
                $cityCell = $colCity . $row;
                $hotelCell = $colHotel . $row;

                // Convert to uppercase and insert values
                $cityUpper = strtoupper($cityInfo['city']);
                $hotelUpper = strtoupper($cityInfo['hotel']);

                $sheet->setCellValue($cityCell, $cityUpper);
                $sheet->setCellValue($hotelCell, $hotelUpper);

            }
        }







        // Clear the output buffer to avoid sending additional data
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Set the proper headers for the Excel file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Itinerary_' . $itineraryDetails['packageName'] . '.xlsx"');
        header('Cache-Control: max-age=0');  // No cache
        header('Cache-Control: max-age=1');  // For compatibility with older browsers

        // Write to output (force download)
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        exit;  // Ensure no other content is sent
    } catch (Exception $e) {
        // Log the error for debugging purposes
        error_log('Excel generation failed: ' . $e->getMessage());
        echo "Error generating the Excel file.";
    }
} else {
    echo "Missing itinerary or day details.";
}
