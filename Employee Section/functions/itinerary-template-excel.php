<?php

// Debugging settings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../conn.php';  // Ensure the database connection is included
require '../../vendor/autoload.php';  // Ensure Composer's autoloader is included

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;  // Changed from Dompdf to Mpdf
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Ensure POST request contains itinerary and days details
if (isset($_POST['itineraryDetails']) && isset($_POST['daysDetails'])) {
    $itineraryDetails = json_decode($_POST['itineraryDetails'], true);
    $daysDetails = json_decode($_POST['daysDetails'], true);

    // Create separate variables for each day's data
    $day1 = $day2 = $day3 = $day4 = $day5 = [];

    // Loop through each day's details and assign to the respective day
    foreach ($daysDetails as $dayData) {
        switch ($dayData['day']) {
            case 1:
                $day1 = $dayData;
                break;
            case 2:
                $day2 = $dayData;
                break;
            case 3:
                $day3 = $dayData;
                break;
            case 4:
                $day4 = $dayData;
                break;
            case 5:
                $day5 = $dayData;
                break;
        }
    }

    try {
        // Load the template Excel file
        $templateFile = '../../Template/Itinerary Template 2.xlsx';
        $spreadsheet = IOFactory::load($templateFile);

        $sheet = $spreadsheet->getActiveSheet();

        // === Set Page Size and Margins === //
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        $pageMargins = $sheet->getPageMargins();
        $pageMargins->setTop(0.2);
        $pageMargins->setBottom(0.1);
        $pageMargins->setLeft(0.1);
        $pageMargins->setRight(0.1);
        $pageMargins->setHeader(0.3);
        $pageMargins->setFooter(0.3);

        // === Template-specific cell positions === //
        $cellPositions = [];
        
        if ($format === 'pdf') {
            // PDF template cell positions (adjust if your PDF template has different layout)
            $cellPositions = [
                'transactionNumber' => 'L2',
                'title' => 'A9',
                'period' => 'C6',
                'guideName' => 'H6',
                'contactNumber' => 'H7',
                'cityStart' => 11,
                'cityCol' => 'C',
                'hotelCol' => 'F'
            ];
        } else {
            // Excel template cell positions (original)
            $cellPositions = [
                'transactionNumber' => 'L2',
                'title' => 'A9', 
                'period' => 'C6',
                'guideName' => 'H6',
                'contactNumber' => 'H7',
                'cityStart' => 11,
                'cityCol' => 'C',
                'hotelCol' => 'F'
            ];
        }

        // Sheet protection settings
        $protection = $sheet->getProtection();
        $protection->setPassword('smtPassword123');
        $protection->setSheet(true);
        $protection->setSort(false);
        $protection->setInsertRows(false);
        $protection->setFormatCells(false);
        $protection->setDeleteColumns(false);
        $protection->setDeleteRows(false);

        // Transaction Number
        $transactionNumberRaw = $itineraryDetails['itineraryId'] ?? 0;
        $transactionNumber = str_pad($transactionNumberRaw, 6, '0', STR_PAD_LEFT);
        $sheet->setCellValue($cellPositions['transactionNumber'], $transactionNumber);

        // Package Name
        function formatPackageName($name) {
            $words = preg_split('/\s+/', trim($name));
            if (count($words) >= 2) {
                return "{$words[0]} {$words[1]}";
            }
            return $words[0] ?? '';
        }

        $displayName = '';
        $fullPackageName = '';

        if (!empty($itineraryDetails['packageName'])) {
            $packageId = (int) $itineraryDetails['packageName'];
            $stmt = $conn->prepare("SELECT packageName FROM package WHERE packageId = ?");
            $stmt->bind_param("i", $packageId);

            if ($stmt->execute()) {
                $stmt->bind_result($fetchedPackageName);
                if ($stmt->fetch()) {
                    $fullPackageName = $fetchedPackageName;
                    $displayName = $fetchedPackageName;
                }
            }
            $stmt->close();
        }

        $formattedItineraryTitle = strtoupper($displayName . ' - KOREA TOUR 5D/4N');
        $sheet->setCellValue($cellPositions['title'], $formattedItineraryTitle);

        // Hotels
        $startRow = $cellPositions['cityStart'];
        $colCity = $cellPositions['cityCol'];
        $colHotel = $cellPositions['hotelCol'];

        if (isset($itineraryDetails['cities']) && is_array($itineraryDetails['cities'])) {
            $currentRow = $startRow;
            foreach ($itineraryDetails['cities'] as $info) {
                if (empty($info['areaName']) && empty($info['hotelName'])) {
                    continue;
                }
                $areaCell = $colCity . $currentRow;
                $hotelCell = $colHotel . $currentRow;
                $areaUpper = strtoupper($info['areaName'] ?? '');
                $hotelUpper = strtoupper($info['hotelName'] ?? '');
                $sheet->setCellValue($areaCell, $areaUpper);
                $sheet->setCellValue($hotelCell, $hotelUpper);
                $currentRow++;
            }
        }

        // Period
        $startDate = new DateTime($itineraryDetails['periodStart']);
        $endDate = new DateTime($itineraryDetails['periodEnd']);
        $formattedPeriod = $startDate->format('F j, Y') . ' - ' . $endDate->format('F j, Y');
        $sheet->setCellValue($cellPositions['period'], $formattedPeriod);
        $sheet->setCellValue($cellPositions['guideName'], $itineraryDetails['guideName']);

        // Contact Number
        $formattedNumber = sprintf(
            "(%s) %s %s %s",
            $itineraryDetails['countryCode'],
            substr($itineraryDetails['contactNumber'], 0, 2),
            substr($itineraryDetails['contactNumber'], 2, 4),
            substr($itineraryDetails['contactNumber'], 6)
        );
        $sheet->setCellValue($cellPositions['contactNumber'], $formattedNumber);

        // Day functions (keeping your existing functions)
        function setDay1Areas($sheet, $dayData) {
            $areaCell = 'B16';
            if (isset($dayData['areas'])) {
                foreach ($dayData['areas'] as $index => $area) {
                    $uppercaseArea = strtoupper(trim($area));
                    $sheet->setCellValue($areaCell, $uppercaseArea);
                    $areaCell++;
                }
            }
        }

        function setDay1Hotels($sheet, $dayData) {
            $hotelCellG = 'E20';
            $hotelCellJ = 'H20';
            if (isset($dayData['hotels'])) {
                $counter = 0;
                foreach ($dayData['hotels'] as $index => $hotel) {
                    if ($counter == 0) {
                        $sheet->setCellValue($hotelCellG, trim($hotel));
                        $hotelCellG++;
                    } elseif ($counter == 1) {
                        $sheet->setCellValue($hotelCellJ, trim($hotel));
                        $hotelCellJ++;
                    }
                    $counter++;
                    if ($counter >= 2) break;
                }
            }
        }

        function setDay1Activities($sheet, $dayData) {
            $activityCell = 'D16';
            $counter = 0;
            if (isset($dayData['activities'])) {
                foreach ($dayData['activities'] as $index => $activity) {
                    if ($counter >= 5) break;
                    $sheet->setCellValue($activityCell, trim($activity));
                    $activityCell++;
                    $counter++;
                }
            }
        }

        function setDay1Meals($sheet, $dayData) {
            $mealCell = 'K16';
            if (isset($dayData['meals'])) {
                foreach ($dayData['meals'] as $index => $meal) {
                    $sheet->setCellValue($mealCell, trim($meal));
                    $mealCell++;
                }
            }
        }

        // Day 2 functions
        function setDay2Areas($sheet, $dayData) {
            $column = 'B';
            if (isset($dayData['areas']) && is_array($dayData['areas'])) {
                $nonEmptyAreas = array_filter($dayData['areas'], function ($area) {
                    return trim($area) !== '';
                });
                $count = count($nonEmptyAreas);
                $row = ($count === 1) ? 24 : 21;
                foreach ($nonEmptyAreas as $index => $area) {
                    $uppercaseArea = strtoupper(trim($area));
                    $cell = $column . $row;
                    $sheet->setCellValue($cell, $uppercaseArea);
                    $row += 3;
                }
            }
        }

        function setDay2Hotels($sheet, $dayData) {
            $hotelCellG = 'E29';
            $hotelCellJ = 'H29';
            if (isset($dayData['hotels'])) {
                $counter = 0;
                foreach ($dayData['hotels'] as $index => $hotel) {
                    if ($counter == 0) {
                        $sheet->setCellValue($hotelCellG, trim($hotel));
                        $hotelCellG++;
                    } elseif ($counter == 1) {
                        $sheet->setCellValue($hotelCellJ, trim($hotel));
                        $hotelCellJ++;
                    }
                    $counter++;
                    if ($counter >= 2) break;
                }
            }
        }

        function setDay2Activities($sheet, $dayData) {
            $activityCell = 'D21';
            if (isset($dayData['activities'])) {
                foreach ($dayData['activities'] as $index => $activity) {
                    $sheet->setCellValue($activityCell, trim($activity));
                    $activityCell++;
                }
            }
        }

        function setDay2Meals($sheet, $dayData) {
            $column = 'L';
            $startRow = 21;
            if (isset($dayData['meals'])) {
                foreach ($dayData['meals'] as $index => $meal) {
                    if ($index === 0) {
                        $currentRow = $startRow;
                    } elseif ($index === 1) {
                        $currentRow = $startRow + 3;
                    } else {
                        $currentRow = $startRow + 3 + (($index - 1) * 2);
                    }
                    $cell = $column . $currentRow;
                    $sheet->setCellValue($cell, trim($meal));
                }
            }
        }

        // Day 3 functions
        function setDay3Areas($sheet, $dayData) {
            $column = 'B';
            if (isset($dayData['areas']) && is_array($dayData['areas'])) {
                $nonEmptyAreas = array_filter($dayData['areas'], function ($area) {
                    return trim($area) !== '';
                });
                $count = count($nonEmptyAreas);
                $row = ($count === 1) ? 33 : 30;
                foreach ($nonEmptyAreas as $index => $area) {
                    $uppercaseArea = strtoupper(trim($area));
                    $cell = $column . $row;
                    $sheet->setCellValue($cell, $uppercaseArea);
                    $row += 3;
                }
            }
        }

        function setDay3Hotels($sheet, $dayData) {
            $hotelCellG = 'E38';
            $hotelCellJ = 'H38';
            $counter = 0;
            if (isset($dayData['hotels'])) {
                foreach ($dayData['hotels'] as $index => $hotel) {
                    if ($counter == 0) {
                        $sheet->setCellValue($hotelCellG, trim($hotel));
                        $hotelCellG++;
                    } elseif ($counter == 1) {
                        $sheet->setCellValue($hotelCellJ, trim($hotel));
                        $hotelCellJ++;
                    }
                    $counter++;
                    if ($counter >= 2) break;
                }
            }
        }

        function setDay3Activities($sheet, $dayData) {
            $activityCell = 'D30';
            if (isset($dayData['activities'])) {
                foreach ($dayData['activities'] as $index => $activity) {
                    $sheet->setCellValue($activityCell, trim($activity));
                    $activityCell++;
                }
            }
        }

        function setDay3Meals($sheet, $dayData) {
            $column = 'L';
            $startRow = 30;
            if (isset($dayData['meals'])) {
                foreach ($dayData['meals'] as $index => $meal) {
                    if ($index === 0) {
                        $currentRow = $startRow;
                    } elseif ($index === 1) {
                        $currentRow = $startRow + 3;
                    } else {
                        $currentRow = $startRow + 3 + (($index - 1) * 2);
                    }
                    $cell = $column . $currentRow;
                    $sheet->setCellValue($cell, trim($meal));
                }
            }
        }

        // Day 4 functions
        function setDay4Areas($sheet, $dayData) {
            $column = 'B';
            if (isset($dayData['areas']) && is_array($dayData['areas'])) {
                $nonEmptyAreas = array_filter($dayData['areas'], function ($area) {
                    return trim($area) !== '';
                });
                $count = count($nonEmptyAreas);
                $row = ($count === 1) ? 42 : 39;
                foreach ($nonEmptyAreas as $index => $area) {
                    $uppercaseArea = strtoupper(trim($area));
                    $cell = $column . $row;
                    $sheet->setCellValue($cell, $uppercaseArea);
                    $row += 3;
                }
            }
        }

        function setDay4Hotels($sheet, $dayData) {
            $hotelCellG = 'E47';
            $hotelCellJ = 'H47';
            $counter = 0;
            if (isset($dayData['hotels'])) {
                foreach ($dayData['hotels'] as $index => $hotel) {
                    if ($counter == 0) {
                        $sheet->setCellValue($hotelCellG, trim($hotel));
                        $hotelCellG++;
                    } elseif ($counter == 1) {
                        $sheet->setCellValue($hotelCellJ, trim($hotel));
                        $hotelCellJ++;
                    }
                    $counter++;
                    if ($counter >= 2) break;
                }
            }
        }

        function setDay4Activities($sheet, $dayData) {
            $activityCell = 'D39';
            if (isset($dayData['activities'])) {
                foreach ($dayData['activities'] as $index => $activity) {
                    $sheet->setCellValue($activityCell, trim($activity));
                    $activityCell++;
                }
            }
        }

        function setDay4Meals($sheet, $dayData) {
            $column = 'L';
            $startRow = 39;
            if (isset($dayData['meals'])) {
                foreach ($dayData['meals'] as $index => $meal) {
                    if ($index === 0) {
                        $currentRow = $startRow;
                    } elseif ($index === 1) {
                        $currentRow = $startRow + 3;
                    } else {
                        $currentRow = $startRow + 3 + (($index - 1) * 2);
                    }
                    $cell = $column . $currentRow;
                    $sheet->setCellValue($cell, trim($meal));
                }
            }
        }

        // Day 5 functions
        function setDay5Areas($sheet, $dayData) {
            $column = 'B';
            if (isset($dayData['areas']) && is_array($dayData['areas'])) {
                $nonEmptyAreas = array_filter($dayData['areas'], function ($area) {
                    return trim($area) !== '';
                });
                $count = count($nonEmptyAreas);
                $row = ($count === 1) ? 51 : 48;
                foreach ($nonEmptyAreas as $index => $area) {
                    $uppercaseArea = strtoupper(trim($area));
                    $cell = $column . $row;
                    $sheet->setCellValue($cell, $uppercaseArea);
                    $row += 3;
                }
            }
        }

        function setDay5Hotels($sheet, $dayData) {
            $hotelCellG = 'E56';
            $hotelCellJ = 'H56';
            $counter = 0;
            if (isset($dayData['hotels'])) {
                foreach ($dayData['hotels'] as $index => $hotel) {
                    if ($counter == 0) {
                        $sheet->setCellValue($hotelCellG, trim($hotel));
                        $hotelCellG++;
                    } elseif ($counter == 1) {
                        $sheet->setCellValue($hotelCellJ, trim($hotel));
                        $hotelCellJ++;
                    }
                    $counter++;
                    if ($counter >= 2) break;
                }
            }
        }

        function setDay5Activities($sheet, $dayData) {
            $activityCell = 'D48';
            if (isset($dayData['activities'])) {
                foreach ($dayData['activities'] as $index => $activity) {
                    $sheet->setCellValue($activityCell, trim($activity));
                    $activityCell++;
                }
            }
        }

        function setDay5Meals($sheet, $dayData) {
            $column = 'L';
            $startRow = 48;
            if (isset($dayData['meals'])) {
                foreach ($dayData['meals'] as $index => $meal) {
                    if ($index === 0) {
                        $currentRow = $startRow;
                    } elseif ($index === 1) {
                        $currentRow = $startRow + 3;
                    } else {
                        $currentRow = $startRow + 3 + (($index - 1) * 2);
                    }
                    $cell = $column . $currentRow;
                    $sheet->setCellValue($cell, trim($meal));
                }
            }
        }

        function setDay6($sheet, $dayData) {
            $column = 'D';
            $startRow = 57;
            $departureFlight = isset($dayData['flight']) ? trim($dayData['flight']) : '5J185';
            $departureTime = isset($dayData['time']) ? trim($dayData['time']) : '08:20';
            $departureText = "Depart from Incheon Airport ({$departureFlight} {$departureTime})";
            $cell = $column . $startRow;
            $sheet->setCellValue($cell, $departureText);
        }

        // Execute all day functions
        setDay1Areas($sheet, $day1);
        setDay1Hotels($sheet, $day1);
        setDay1Activities($sheet, $day1);
        setDay1Meals($sheet, $day1);

        setDay2Areas($sheet, $day2);
        setDay2Hotels($sheet, $day2);
        setDay2Activities($sheet, $day2);
        setDay2Meals($sheet, $day2);

        setDay3Areas($sheet, $day3);
        setDay3Hotels($sheet, $day3);
        setDay3Activities($sheet, $day3);
        setDay3Meals($sheet, $day3);

        setDay4Areas($sheet, $day4);
        setDay4Hotels($sheet, $day4);
        setDay4Activities($sheet, $day4);
        setDay4Meals($sheet, $day4);

        setDay5Areas($sheet, $day5);
        setDay5Hotels($sheet, $day5);
        setDay5Activities($sheet, $day5);
        setDay5Meals($sheet, $day5);

        setDay6($sheet, $dayData);

        // Clear the output buffer to avoid sending additional data
        if (ob_get_length()) {
            ob_end_clean();
        }

        // ========== Check format parameter from JavaScript ========== //
        $format = $_POST['format'] ?? 'xlsx'; // Default to xlsx if not specified
        
        if ($format === 'pdf') {
            // === PDF-Specific Configuration === //
            
            // Configure PDF page setup before generation
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            
            // PDF margins (adjust these as needed)
            $pageMargins = $sheet->getPageMargins();
            $pageMargins->setTop(0.1);       // Top margin in inches
            $pageMargins->setBottom(0.15);    // Bottom margin in inches  
            $pageMargins->setLeft(0.3);     // Left margin in inches
            $pageMargins->setRight(0.1);    // Right margin in inches
            $pageMargins->setHeader(0.1);    // Header margin in inches
            $pageMargins->setFooter(0.2);    // Footer margin in inches
            
            // PDF scaling options
            $sheet->getPageSetup()->setFitToPage(true);
            $sheet->getPageSetup()->setFitToWidth(1);    // Fit to 1 page wide
            $sheet->getPageSetup()->setFitToHeight(0);   // 0 = unlimited height
            
            // === PDF Font Configuration === //
            // Override ALL fonts to Arial for PDF
            $sheet->getParent()->getDefaultStyle()->getFont()
                ->setName('Arial')           // Font family
                ->setSize(10);              // Font size
            
            // Force Arial on the entire used range
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $fullRange = 'A1:' . $highestColumn . $highestRow;
            
            // General Font
            $sheet->getStyle($fullRange)->getFont()
                ->setName('Arial')
                ->setSize(8);

            $sheet->getStyle('A1:F13')->getFont()       // Header section
                ->setName('Arial')
                ->setSize(8);

            // Title Font
            $sheet->getStyle('A9')->getFont()          // Title
                ->setName('Arial')
                ->setSize(12)
                ->setBold(true);
            
            // Method 1: Center horizontally and vertically
            $sheet->getStyle('G3')->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);   // ATTN
    
            
            $sheet->getStyle('H6:H7')->getFont()          // Guide
                ->setBold(false);
            
            $sheet->getStyle('A11:L13')->getFont()          // Hotel - Areas    
                ->setBold(false);

            
            // Body
            $sheet->getStyle('A16:L50')->getFont()          // Title
                ->setSize(8);
            

            // === SOLUTION 5: Cell-by-cell with forced removal === //
            $allCellsInRanges = [];

            // Generate all cells in ranges
            foreach (['B21:B27', 'B30:B36', 'B39:B45', 'B48:B54'] as $range) {
                $parts = explode(':', $range);
                $startRow = (int)filter_var($parts[0], FILTER_SANITIZE_NUMBER_INT);
                $endRow = (int)filter_var($parts[1], FILTER_SANITIZE_NUMBER_INT);
                
                for ($row = $startRow; $row <= $endRow; $row++) {
                    $allCellsInRanges[] = 'B' . $row;
                }
            }

            // Force remove all borders from each cell
            foreach ($allCellsInRanges as $cell) {
                // Multiple methods to ensure removal
                $cellStyle = $sheet->getStyle($cell);
                
                $cellStyle->getBorders()->getTop()->setBorderStyle(Border::BORDER_NONE);
                $cellStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_NONE);
                $cellStyle->getBorders()->getLeft()->setBorderStyle(Border::BORDER_NONE);
                $cellStyle->getBorders()->getRight()->setBorderStyle(Border::BORDER_NONE);
                
                // Also try with applyFromArray
                $cellStyle->applyFromArray([
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_NONE],
                        'bottom' => ['borderStyle' => Border::BORDER_NONE],
                        'left' => ['borderStyle' => Border::BORDER_NONE],
                        'right' => ['borderStyle' => Border::BORDER_NONE]
                    ]
                ]);
            }



            

            // Override any bold text to ensure Arial
            $sheet->getStyle($fullRange)->getFont()->setName('Arial');
            

            // Generate PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="Itinerary_' . $itineraryDetails['packageName'] . '.pdf"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');

            // Create PDF writer and save to output
            $pdfWriter = new Mpdf($spreadsheet);
            $pdfWriter->save('php://output');

        } else {
            // Generate Excel (xlsx format)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Itinerary_' . $itineraryDetails['packageName'] . '.xlsx"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');

            // Write Excel to output
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }

        exit;

    } catch (Exception $e) {
        error_log('PDF generation failed: ' . $e->getMessage());
        echo "Error generating the PDF file: " . $e->getMessage();
    }

} else {
    echo "Missing itinerary or day details.";
}
?>