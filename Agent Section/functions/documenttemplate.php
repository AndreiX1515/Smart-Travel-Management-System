<?php
require_once('../../assets/tcpdf/tcpdf.php');  // Ensure you have the correct TCPDF path

class PDF extends TCPDF {
    // Page header
    public function Header() {
        // Add logo
        $this->Image('../../assets/images/SMART LOGO 2 (2).jpg', 45, 5, 105, 15); // Adjust 'logo.png' path, position, and size as needed
        $this->Ln(25); // Adds 10mm of vertical space

        $this->SetFont('Helvetica', 'B', 8);
        $this->SetY($this->GetY() + 2); // Set the Y position for the line, adjust if needed
        $this->SetTextColor(255, 0, 0); // Set text color to red (RGB: 255, 0, 0)
        $this->Cell(173, 0, '**Subject to Change w/o prior notice based on local Situiation**', 0, 0, 'L');
        $this->SetTextColor(0, 0, 0); 
        $this->Cell(5, 0, 'TN: 1029365', 0, 0, 'L');


        $this->Ln(3); // Adds 10mm of vertical space

        $this->SetY($this->GetY() + 2); // Set the Y position for the line, adjust if needed
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // Draw a line from x=10 to x=200 at the current Y position

        $this->Ln(1); // Adds 10mm of vertical space

        // Header lines
        $this->SetFont('Helvetica', 'B', 8);
        $this->Cell(15, 0, 'TO :', 0, 0, 'L');
        $this->Cell(100, 0, 'TRAVEL', 0, 0, 'L');
        $this->Cell(80, 0, 'ATTN :', 0, 0, 'L');
        $this->Cell(30, 0, '', 0, 1, 'L');

        $this->Cell(15, 5, 'FROM :', 0, 0, 'L');
        $this->Cell(100, 5, 'JED KIM', 0, 0, 'L');
        $this->Cell(15, 5, 'DATE :', 0, 0, 'L');
        $this->Cell(30, 5, '', 0, 1, 'L');

        $this->SetY($this->GetY() + 1); // Set the Y position for the line, adjust if needed
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // Draw a line from x=10 to x=200 at the current Y position

        $this->Ln(3); // Adds 10mm of vertical space

        // Main title
        $this->SetFont('Helvetica', 'B', 16);
        $this->Cell(0, 7, 'WINTER', 'LRT', 1, 'C');
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(0, 7, 'KOREA TOUR 5 DAYS & 4 NIGHTS', 'LRB', 1, 'C');

        // Set up columns for periods and hotel info
        $this->SetFont('Helvetica', 'B', 9);

        // Create a vertical "HOTEL" cell spanning multiple rows
        $this->SetXY(10, 59.5);  // Adjust the X and Y position if needed
        $this->Cell(40, 10, 'PERIODS', 'LRB', 0, 'C', false);  // Borders on all sides, center-aligned text
        $this->Cell(70, 10, '10, OCT. 2024 - 15, NOV. 2024', 'B', 0, 'C');




        $this->Cell(20, 10, 'GUIDE:', 'LB', 0, 'C');
        $this->Cell(60, 5, 'Mikey Lee', 'LB', 1, 'C');


        $this->SetXY(140, 64.5);  // Adjust the X and Y position if needed
        $this->Cell(60, 5, '82(0)-324-3746', 1, 1, 'C');

    }

    // Add hotel info table
    public function addHotelInfoTable() {
        $this->SetFont('Helvetica', 'B', 10);

        // Create a vertical "HOTEL" cell spanning multiple rows
        $this->SetXY(10, 69.5);  // Adjust the X and Y position if needed
        $this->Cell(40, 15, 'HOTEL', 'LRB', 0, 'C', false);  // Borders on all sides, center-aligned text

        $this->SetFont('Helvetica', 'B', 10);
        $this->SetXY(50, 70);  // Adjust the Y to align the cells properly
        // Create the cells for the cities (Incheon, Gangwon, Seoul)
        $this->Cell(30, 4, 'INCHEON', 'LRB', 0, 'C');
        $this->Cell(120, 4, '   Air Sky Hotel', 'LRB', 1, 'L');

        $this->SetXY(50, 75);  // Adjust the Y to align the cells properly
        $this->Cell(30, 4, 'GANGWON', 'LRB', 0, 'C');
        $this->Cell(120, 4, '   Centrum Hotel', 'LRB', 1, 'L');

        $this->SetXY(50, 80); // Adjust the Y position after the city names
        $this->Cell(30, 4, 'SEOUL', 'LRB', 0, 'C');
        $this->Cell(120, 4, '   Bernoui Hotel', 'LRB', 1, 'L');
    }

    // Add itinerary header
    public function addItineraryHeader() {
        $this->SetFont('Helvetica', 'B', 10);

        // Add some space after the hotel info table (to prevent overlap)
        $this->Ln(2);  // Adds a 10mm space before starting the itinerary header

        // Set the X and Y for the header
        $this->SetXY(10, $this->GetY());  // Set starting position for the header row based on current Y

        $this->Cell(15, 7, 'DAY', 1, 0, 'C');
        $this->Cell(25, 7, 'AREA', 1, 0, 'C');
        $this->Cell(90, 7, 'ITINERARY', 1, 0, 'C');
        $this->Cell(60, 7, 'MEAL PLAN', 1, 1, 'C');
    }

    // Main function to add a day-specific itinerary row dynamically
    public function day($day, $area, $itineraryContent, $mealPlan) {
        // Call helper function to add the row for each day
        $this->addItineraryRow($day, $area, $itineraryContent, $mealPlan);
    }

    // Function to add a row of itinerary details with consistent height
    private function addItineraryRow($day, $area, $itineraryContent, $mealPlan) {
        $this->SetFont('Helvetica', '', 10);
        $this->SetCellPadding(3);

        // Save the initial Y position to ensure row alignment
        $initialY = $this->GetY();

        // Calculate itinerary content height
        $this->SetXY(50, $initialY); // Temporary position for measuring only
        $this->MultiCell(90, 10, $itineraryContent, 1, 'L');
        $itineraryHeight = $this->GetY() - $initialY;

        // Reset to initial Y for meal plan measurement
        $this->SetXY(140, $initialY);
        $this->MultiCell(60, 10, $mealPlan, 1, 'L');
        $mealPlanHeight = $this->GetY() - $initialY;

        // Determine the maximum height required for this row
        $maxHeight = max(10, $itineraryHeight, $mealPlanHeight); // Minimum cell height is 10

        // Render Day and Area cells with maxHeight
        $this->SetXY(10, $initialY);
        $this->Cell(15, $maxHeight, $day, 1, 0, 'C'); // Day cell
        $this->Cell(25, $maxHeight, $area, 1, 0, 'C'); // Area cell

        // Render Itinerary cell with vertical alignment within maxHeight
        $this->SetXY(50, $initialY);
        $this->MultiCell(90, $maxHeight, $itineraryContent, 1, 'L');

        // Render Meal Plan cell with vertical alignment within maxHeight
        $this->SetXY(140, $initialY);
        $this->MultiCell(60, $maxHeight, $mealPlan, 1, 'L');

        // Move Y down by maxHeight to start the next row
        $this->SetY($initialY + $maxHeight);
    }
}

// Usage example
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->addHotelInfoTable();  // Add hotel info table
$pdf->addItineraryHeader();  // Add itinerary header row

// // Add multiple days dynamically
$pdf->day('1st', 'INCHEON', "Arrival at Incheon Airport (5j188 17:35-22:55)\nMeeting and greeting English speaking Guide\nTransfer to Hotel", 'Snack');
// $pdf->day('2nd', 'GANGWON', "Morning breakfast\nExplore Gangwon\nVisit local attractions\n\n\n\n\n", 'Lunch');
// $pdf->day('3rd', 'SEOUL', "Morning sightseeing tour\nFree time in Seoul", 'Dinner');
// $pdf->day('4th', 'SEOUL', "Visit Gyeongbokgung Palace\nExplore Bukchon Hanok Village\nKorean BBQ lunch", 'Breakfast and Dinner');
$pdf->day('5th', 'SEOUL', "Free day for shopping\nOptional: Namsan Seoul Tower visit\nDeparture in the evening", 'Breakfast and Lunch');

// Output the generated PDF
$pdf->Output();
?>
