<?php
require '../../conn.php';  // Ensure the database connection is included
require '../../vendor/autoload.php';  // Ensure Composer's autoloader is included

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
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
                break; // Once we get Day 1, break the loop
            case 2:
                $day2 = $dayData;
                break; // Once we get Day 2, break the loop
            case 3:
                $day3 = $dayData;
                break; // Once we get Day 3, break the loop
            case 4:
                $day4 = $dayData;
                break; // Once we get Day 4, break the loop
            case 5:
                $day5 = $dayData;
                break; // Once we get Day 5, break the loop
        }
    }


    try {
        // Load the template Excel file
        $templateFile = '../../Template/Itinerary Template 2.xlsx';  // Replace with the path to your template
        $spreadsheet = IOFactory::load($templateFile);

        IOFactory::registerWriter('Pdf', Dompdf::class);

        $sheet = $spreadsheet->getActiveSheet();

        // === Set Page Size and Margins === //
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        $pageMargins = $sheet->getPageMargins();
        $pageMargins->setTop(0.2);       // 0.2 inch top margin
        $pageMargins->setBottom(0.1);   // 0.75 inch bottom margin
        $pageMargins->setLeft(0.1);     // 0.25 inch left margin
        $pageMargins->setRight(0.1);    // 0.15 inch right margin
        $pageMargins->setHeader(0.3);    // 0.3 inch header
        $pageMargins->setFooter(0.3);    // 0.3 inch footer

        // Step 1: Lock all cells (they are usually locked by default, but just in case)
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);

        // Step 2: Protect the sheet (and allow printing)
        $protection = $sheet->getProtection();
        $protection->setPassword('yourPassword'); // Set a password
        $protection->setSheet(true); // Lock the sheet
        $protection->setSort(false);
        $protection->setInsertRows(false);
        $protection->setFormatCells(false);
        $protection->setDeleteColumns(false);
        $protection->setDeleteRows(false);

        // Allow printing specifically
        $sheet->getProtection()->setPassword('smtPassword123');
        $sheet->getProtection()->setSheet(true); // Enable sheet protection
        $sheet->getProtection()->setSort(false);
        $sheet->getProtection()->setInsertRows(false);
        $sheet->getProtection()->setFormatCells(false);


        // Header
        // Transaction Number 

        // Transaction Number
        $transactionNumberRaw = $itineraryDetails['itineraryId'] ?? 0; // Default to 0 if not set
        $transactionNumber = str_pad($transactionNumberRaw, 6, '0', STR_PAD_LEFT); // Pad to 6 digits

        // ✅ Output formatted transaction number to A9
        $sheet->setCellValue('L2', $transactionNumber);









        // =========== Package Name =========== //
        function formatPackageName($name) {
            $words = preg_split('/\s+/', trim($name));

            // Return first two words if more than one exists, else just the first
            if (count($words) >= 2) {
                return "{$words[0]} {$words[1]}";
            }

            return $words[0] ?? '';
        }

        // ✅ Fetch and format package name using packageId from itineraryDetails
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

        // ✅ Compose final title with suffix
        $formattedItineraryTitle = strtoupper($displayName . ' - KOREA TOUR 5D/4N');

        // ✅ Output formatted name to A9
        $sheet->setCellValue('A9', $formattedItineraryTitle);






       // =========== Hotels =========== //
        $startRow = 11; // Starting row
        $colCity = 'C'; // Column for areas
        $colHotel = 'F'; // Column for hotels

        if (isset($itineraryDetails['cities']) && is_array($itineraryDetails['cities'])) {
            $currentRow = $startRow;

            foreach ($itineraryDetails['cities'] as $info) {
                // Skip if both areaName and hotelName are empty
                if (empty($info['areaName']) && empty($info['hotelName'])) {
                    continue;
                }

                // Construct cell references
                $areaCell = $colCity . $currentRow;
                $hotelCell = $colHotel . $currentRow;

                // Convert to uppercase and insert values
                $areaUpper = strtoupper($info['areaName'] ?? '');
                $hotelUpper = strtoupper($info['hotelName'] ?? '');

                $sheet->setCellValue($areaCell, $areaUpper);
                $sheet->setCellValue($hotelCell, $hotelUpper);

                $currentRow++; // Move to next row
            }
        }




        // =========== Period =========== //
        $startDate = new DateTime($itineraryDetails['periodStart']);
        $endDate = new DateTime($itineraryDetails['periodEnd']);

        // Format: "October 10, 2024 - November 15, 2024"
        $formattedPeriod = $startDate->format('F j, Y') . ' - ' . $endDate->format('F j, Y');

        // Set in cell
        $sheet->setCellValue('C6', $formattedPeriod);
        $sheet->setCellValue('H6', $itineraryDetails['guideName']);




        // =========== Contact Number =========== //
        $formattedNumber = sprintf(
            "(%s) %s %s %s",
            $itineraryDetails['countryCode'],
            substr($itineraryDetails['contactNumber'], 0, 2),
            substr($itineraryDetails['contactNumber'], 2, 4),
            substr($itineraryDetails['contactNumber'], 6)
        );

        // Set the formatted number in Excel cell H7
        $sheet->setCellValue('H7', $formattedNumber);

        


         // ========== DAY 1 ========== //
        function setDay1Areas($sheet, $dayData)
        {
            echo "Day 1 Areas: <br>";
            $areaCell = 'B16';  // Example starting cell for areas in Excel

            // Check if areas are set for Day 1 and loop through them
            if (isset($dayData['areas'])) {
                foreach ($dayData['areas'] as $index => $area) {
                    // Convert area to uppercase
                    $uppercaseArea = strtoupper(trim($area));

                    // Set value for areas and echo to the console
                    $sheet->setCellValue($areaCell, $uppercaseArea);  // Set uppercase value for areas
                    echo ($index + 1) . ". " . $uppercaseArea . "<br>";

                    $areaCell++;  // Move to the next column
                }
            }
        }


        function setDay1Hotels($sheet, $dayData)
        {
            echo "Day 1 Hotels: <br>";

            // Define the starting cells for columns G and J
            $hotelCellG = 'E21';  // First loop will use column G
            $hotelCellJ = 'H21';  // Second loop will use column J

            // Check if hotels are set for Day 1 and loop through them
            if (isset($dayData['hotels'])) {
                $counter = 0; // Counter to limit to two loops

                foreach ($dayData['hotels'] as $index => $hotel) {
                    // On the first loop, use column G
                    if ($counter == 0) {
                        $sheet->setCellValue($hotelCellG, trim($hotel));  // Set value for hotels in column G
                        echo ($index + 1) . ". " . trim($hotel) . " (G)<br>";
                        $hotelCellG++;  // Move to the next column (for G)
                    }
                    // On the second loop, use column J
                    elseif ($counter == 1) {
                        $sheet->setCellValue($hotelCellJ, trim($hotel));  // Set value for hotels in column J
                        echo ($index + 1) . ". " . trim($hotel) . " (J)<br>";
                        $hotelCellJ++;  // Move to the next column (for J)
                    }

                    // Increment the counter after each loop
                    $counter++;

                    // Stop the loop after 2 iterations (since we want to limit it to 2 loops)
                    if ($counter >= 2) {
                        break;
                    }
                }
            }
        }


        function setDay1Activities($sheet, $dayData)
        {
            echo "Day 1 Activities: <br>";
            $activityCell = 'D16';  // Example starting cell for activities in Excel
            $counter = 0; // Counter to limit the loop to 5 activities

            // Check if activities are set for Day 1 and loop through them
            if (isset($dayData['activities'])) {
                foreach ($dayData['activities'] as $index => $activity) {
                    // Check if the loop has already processed 5 activities
                    if ($counter >= 5) {
                        break; // Stop the loop after the 5th activity
                    }

                    // Set value for activities and echo to the console
                    $sheet->setCellValue($activityCell, trim($activity));  // Set value for activities
                    echo ($index + 1) . ". " . trim($activity) . "<br>";

                    $activityCell++;  // Move to the next column
                    $counter++; // Increment the counter
                }
            }
        }

        // Function to display and set values for Meals in Excel
        function setDay1Meals($sheet, $dayData)
        {
            echo "Day 1 Meals: <br>";
            $mealCell = 'K16';  // Example starting cell for meals in Excel

            // Check if meals are set for Day 1 and loop through them
            if (isset($dayData['meals'])) {
                foreach ($dayData['meals'] as $index => $meal) {
                    $sheet->setCellValue($mealCell, trim($meal));  // Set value for meals
                    echo ($index + 1) . ". " . trim($meal) . "<br>";
                    $mealCell++;  // Move to the next column
                }
            }
        }

        setDay1Areas($sheet, $day1);
        setDay1Hotels($sheet, $day1);
        setDay1Activities($sheet, $day1);
        setDay1Meals($sheet, $day1);

        // Day 2
        // Function to display and set values for Areas on Day 2
        function setDay2Areas($sheet, $dayData)
        {
            echo "Day 2 Areas: <br>";
            $column = 'B';

            if (isset($dayData['areas']) && is_array($dayData['areas'])) {
                // Filter out empty strings
                $nonEmptyAreas = array_filter($dayData['areas'], function ($area) {
                    return trim($area) !== '';
                });

                $count = count($nonEmptyAreas);

                // Determine starting row
                $row = ($count === 1) ? 25 : 22;

                foreach ($nonEmptyAreas as $index => $area) {
                    $uppercaseArea = strtoupper(trim($area));
                    $cell = $column . $row;

                    $sheet->setCellValue($cell, $uppercaseArea);
                    echo ($index + 1) . ". " . $uppercaseArea . " -> $cell<br>";

                    $row += 3;  // Move to next row with +3 spacing
                }
            }
        }





        // Function to display and set values for Hotels on Day 2
        function setDay2Hotels($sheet, $dayData)
        {
            echo "Day 2 Hotels: <br>";

            // Define the starting cells for columns G and J
            $hotelCellG = 'E30';  // First loop will use column G
            $hotelCellJ = 'H30';  // Second loop will use column J

            // Check if hotels are set for Day 2 and loop through them
            if (isset($dayData['hotels'])) {
                $counter = 0; // Counter to limit to two loops

                foreach ($dayData['hotels'] as $index => $hotel) {
                    // On the first loop, use column G
                    if ($counter == 0) {
                        $sheet->setCellValue($hotelCellG, trim($hotel));  // Set value for hotels in column G
                        echo ($index + 1) . ". " . trim($hotel) . " (G)<br>";
                        $hotelCellG++;  // Move to the next column (for G)
                    }
                    // On the second loop, use column J
                    elseif ($counter == 1) {
                        $sheet->setCellValue($hotelCellJ, trim($hotel));  // Set value for hotels in column J
                        echo ($index + 1) . ". " . trim($hotel) . " (J)<br>";
                        $hotelCellJ++;  // Move to the next column (for J)
                    }

                    // Increment the counter after each loop
                    $counter++;

                    // Stop the loop after 2 iterations (since we want to limit it to 2 loops)
                    if ($counter >= 2) {
                        break;
                    }
                }
            }
        }

        // Function to display and set values for Activities on Day 2
        function setDay2Activities($sheet, $dayData)
        {
            echo "Day 2 Activities: <br>";
            $activityCell = 'D22';  // Example starting cell for activities in Excel

            // Check if activities are set for Day 2 and loop through them
            if (isset($dayData['activities'])) {
                foreach ($dayData['activities'] as $index => $activity) {
                    // Format index with padding (e.g., "1.    ")
                    $paddedIndex = str_pad(($index + 1) . '.', 6, ' ', STR_PAD_RIGHT);

                    // Set value for activities and echo to the console
                    $sheet->setCellValue($activityCell, trim($activity));  // Set value for activities
                    echo $paddedIndex . trim($activity) . "<br>";

                    $activityCell++;  // Move to the next column
                }
            }
        }


        // Function to display and set values for Meals on Day 2
       function setDay2Meals($sheet, $dayData)
        {
            echo "Day 2 Meals: <br>";

            $column = 'L';
            $startRow = 22;

            if (isset($dayData['meals'])) {
                foreach ($dayData['meals'] as $index => $meal) {
                    if ($index === 0) {
                        $currentRow = $startRow; // 22
                    } elseif ($index === 1) {
                        $currentRow = $startRow + 3; // 25
                    } else {
                        $currentRow = $startRow + 3 + (($index - 1) * 2); // 27, 29, 31...
                    }

                    $cell = $column . $currentRow;
                    $sheet->setCellValue($cell, trim($meal));
                    echo ($index + 1) . ". " . trim($meal) . " -> $cell<br>";
                }
            }
        }







        // Call the functions for Day 2 (assuming $sheet and $day2 are defined and available)
        setDay2Areas($sheet, $day2);
        setDay2Hotels($sheet, $day2);
        setDay2Activities($sheet, $day2);
        setDay2Meals($sheet, $day2);


        // ========== DAY 3 ========== //
        function setDay3Areas($sheet, $dayData)
        {
            echo "Day 3 Areas: <br>";
            $column = 'B';

            if (isset($dayData['areas']) && is_array($dayData['areas'])) {
                // Filter out empty or whitespace-only areas
                $nonEmptyAreas = array_filter($dayData['areas'], function ($area) {
                    return trim($area) !== '';
                });

                $count = count($nonEmptyAreas);

                // Determine starting row
                $row = ($count === 1) ? 34 : 31;

                foreach ($nonEmptyAreas as $index => $area) {
                    $uppercaseArea = strtoupper(trim($area));
                    $cell = $column . $row;

                    $sheet->setCellValue($cell, $uppercaseArea);
                    echo ($index + 1) . ". " . $uppercaseArea . " -> $cell<br>";

                    $row += 3;
                }
            }
        }



        function setDay3Hotels($sheet, $dayData)
        {
            echo "Day 3 Hotels: <br>";
            $hotelCellG = 'E39';
            $hotelCellJ = 'H39';
            $counter = 0;

            if (isset($dayData['hotels'])) {
                foreach ($dayData['hotels'] as $index => $hotel) {
                    if ($counter == 0) {
                        $sheet->setCellValue($hotelCellG, trim($hotel));
                        echo ($index + 1) . ". " . trim($hotel) . " (G)<br>";
                        $hotelCellG++;
                    } elseif ($counter == 1) {
                        $sheet->setCellValue($hotelCellJ, trim($hotel));
                        echo ($index + 1) . ". " . trim($hotel) . " (J)<br>";
                        $hotelCellJ++;
                    }
                    $counter++;
                    if ($counter >= 2) break;
                }
            }
        }

        function setDay3Activities($sheet, $dayData)
        {
            echo "Day 3 Activities: <br>";
            $activityCell = 'D31';

            if (isset($dayData['activities'])) {
                foreach ($dayData['activities'] as $index => $activity) {
                    $paddedIndex = str_pad(($index + 1) . '.', 6, ' ', STR_PAD_RIGHT);
                    $sheet->setCellValue($activityCell, trim($activity));
                    echo $paddedIndex . trim($activity) . "<br>";
                    $activityCell++;
                }
            }
        }

        function setDay3Meals($sheet, $dayData)
        {
            echo "Day 3 Meals: <br>";

            $column = 'L';
            $startRow = 31;

            if (isset($dayData['meals'])) {
                foreach ($dayData['meals'] as $index => $meal) {
                    if ($index === 0) {
                        $currentRow = $startRow; // 31
                    } elseif ($index === 1) {
                        $currentRow = $startRow + 3; // 34
                    } else {
                        $currentRow = $startRow + 3 + (($index - 1) * 2); // 36, 38, 40...
                    }

                    $cell = $column . $currentRow;
                    $sheet->setCellValue($cell, trim($meal));
                    echo ($index + 1) . ". " . trim($meal) . " -> $cell<br>";
                }
            }
        }



        setDay3Areas($sheet, $day3);
        setDay3Hotels($sheet, $day3);
        setDay3Activities($sheet, $day3);
        setDay3Meals($sheet, $day3);


        // ========== DAY 4 ========== //
        function setDay4Areas($sheet, $dayData)
        {
            echo "Day 4 Areas: <br>";
            $column = 'B';

            if (isset($dayData['areas']) && is_array($dayData['areas'])) {
                // Remove empty or whitespace-only values
                $nonEmptyAreas = array_filter($dayData['areas'], function ($area) {
                    return trim($area) !== '';
                });

                $count = count($nonEmptyAreas);

                // Start at B43 if only 1, otherwise start at B40
                $row = ($count === 1) ? 43 : 40;

                foreach ($nonEmptyAreas as $index => $area) {
                    $uppercaseArea = strtoupper(trim($area));
                    $cell = $column . $row;

                    $sheet->setCellValue($cell, $uppercaseArea);
                    echo ($index + 1) . ". " . $uppercaseArea . " -> $cell<br>";

                    $row += 3;
                }
            }
        }



        function setDay4Hotels($sheet, $dayData)
        {
            echo "Day 4 Hotels: <br>";
            $hotelCellG = 'E48';
            $hotelCellJ = 'H48';
            $counter = 0;

            if (isset($dayData['hotels'])) {
                foreach ($dayData['hotels'] as $index => $hotel) {
                    if ($counter == 0) {
                        $sheet->setCellValue($hotelCellG, trim($hotel));
                        echo ($index + 1) . ". " . trim($hotel) . " (G)<br>";
                        $hotelCellG++;
                    } elseif ($counter == 1) {
                        $sheet->setCellValue($hotelCellJ, trim($hotel));
                        echo ($index + 1) . ". " . trim($hotel) . " (J)<br>";
                        $hotelCellJ++;
                    }
                    $counter++;
                    if ($counter >= 2) break;
                }
            }
        }

        function setDay4Activities($sheet, $dayData)
        {
            echo "Day 4 Activities: <br>";
            $activityCell = 'D40';

            if (isset($dayData['activities'])) {
                foreach ($dayData['activities'] as $index => $activity) {
                    $paddedIndex = str_pad(($index + 1) . '.', 6, ' ', STR_PAD_RIGHT);
                    $sheet->setCellValue($activityCell, trim($activity));
                    echo $paddedIndex . trim($activity) . "<br>";
                    $activityCell++;
                }
            }
        }

        function setDay4Meals($sheet, $dayData)
        {
            echo "Day 4 Meals: <br>";

            $column = 'L';
            $startRow = 40;

            if (isset($dayData['meals'])) {
                foreach ($dayData['meals'] as $index => $meal) {
                    if ($index === 0) {
                        $currentRow = $startRow; // 40
                    } elseif ($index === 1) {
                        $currentRow = $startRow + 3; // 43
                    } else {
                        $currentRow = $startRow + 3 + (($index - 1) * 2); // 45, 47, 49...
                    }

                    $cell = $column . $currentRow;
                    $sheet->setCellValue($cell, trim($meal));
                    echo ($index + 1) . ". " . trim($meal) . " -> $cell<br>";
                }
            }
        }



        setDay4Areas($sheet, $day4);
        setDay4Hotels($sheet, $day4);
        setDay4Activities($sheet, $day4);
        setDay4Meals($sheet, $day4);


        // ========== DAY 5 ========== //
        function setDay5Areas($sheet, $dayData)
        {
            echo "Day 5 Areas: <br>";
            $column = 'B';

            if (isset($dayData['areas']) && is_array($dayData['areas'])) {
                // Filter out empty/whitespace-only values
                $nonEmptyAreas = array_filter($dayData['areas'], function ($area) {
                    return trim($area) !== '';
                });

                $count = count($nonEmptyAreas);

                // Start at B52 if only 1 area, else start at B49
                $row = ($count === 1) ? 52 : 49;

                foreach ($nonEmptyAreas as $index => $area) {
                    $uppercaseArea = strtoupper(trim($area));
                    $cell = $column . $row;

                    $sheet->setCellValue($cell, $uppercaseArea);
                    echo ($index + 1) . ". " . $uppercaseArea . " -> $cell<br>";

                    $row += 3;
                }
            }
        }



        function setDay5Hotels($sheet, $dayData)
        {
            echo "Day 5 Hotels: <br>";
            $hotelCellG = 'E57';
            $hotelCellJ = 'H57';
            $counter = 0;

            if (isset($dayData['hotels'])) {
                foreach ($dayData['hotels'] as $index => $hotel) {
                    if ($counter == 0) {
                        $sheet->setCellValue($hotelCellG, trim($hotel));
                        echo ($index + 1) . ". " . trim($hotel) . " (G)<br>";
                        $hotelCellG++;
                    } elseif ($counter == 1) {
                        $sheet->setCellValue($hotelCellJ, trim($hotel));
                        echo ($index + 1) . ". " . trim($hotel) . " (J)<br>";
                        $hotelCellJ++;
                    }
                    $counter++;
                    if ($counter >= 2) break;
                }
            }
        }

        function setDay5Activities($sheet, $dayData)
        {
            echo "Day 5 Activities: <br>";
            $activityCell = 'D49';

            if (isset($dayData['activities'])) {
                foreach ($dayData['activities'] as $index => $activity) {
                    $paddedIndex = str_pad(($index + 1) . '.', 6, ' ', STR_PAD_RIGHT);
                    $sheet->setCellValue($activityCell, trim($activity));
                    echo $paddedIndex . trim($activity) . "<br>";
                    $activityCell++;
                }
            }
        }

        function setDay5Meals($sheet, $dayData)
        {
            echo "Day 5 Meals: <br>";

            $column = 'L';
            $startRow = 49;

            if (isset($dayData['meals'])) {
                foreach ($dayData['meals'] as $index => $meal) {
                    if ($index === 0) {
                        $currentRow = $startRow; // 49
                    } elseif ($index === 1) {
                        $currentRow = $startRow + 3; // 52
                    } else {
                        $currentRow = $startRow + 3 + (($index - 1) * 2); // 54, 56, 58...
                    }

                    $cell = $column . $currentRow;
                    $sheet->setCellValue($cell, trim($meal));
                    echo ($index + 1) . ". " . trim($meal) . " -> $cell<br>";
                }
            }
        }





        setDay5Areas($sheet, $day5);
        setDay5Hotels($sheet, $day5);
        setDay5Activities($sheet, $day5);
        setDay5Meals($sheet, $day5);

        function setDay6($sheet, $dayData)
        {
            echo "Day 6: <br>";
            $column = 'D';
            $startRow = 58;

            // === Dynamic departure info ===
            $departureFlight = isset($dayData['flight']) ? trim($dayData['flight']) : '5J185'; // Original: $departureFlight = '5J185';
            $departureTime = isset($dayData['time']) ? trim($dayData['time']) : '08:20';       // Original: $departureTime = '08:20';

            // === Construct departure text ===
            $departureText = "Depart from Incheon Airport ({$departureFlight} {$departureTime})";
            // Original: $sheet->setCellValue($cell, trim("Depart From Incheon Airport (". $departureFlight."));

            // === Set cell value ===
            $cell = $column . $startRow;
            $sheet->setCellValue($cell, $departureText);

            // === Output for debugging ===
            echo "1. {$departureText} -> $cell<br>";
        }

        setDay6($sheet, $dayData);


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
