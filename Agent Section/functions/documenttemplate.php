<?php
require_once('../../assets/tcpdf/tcpdf.php');  // Ensure you have the correct TCPDF path

class PDF extends TCPDF {
    // Page header
    public function Header() {
        // Add logo
        $this->Image('../../assets/images/SMART LOGO 2 (2).jpg', 45, 7, 105, 15); // Adjust 'logo.png' path, position, and size as needed
        $this->Ln(25); // Adds 10mm of vertical space

        // Set font for header
        $this->SetFont('Helvetica', 'B', 14);
        
        // Header lines
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(15, 0, 'TO :', 0, 0, 'L');
        $this->Cell(60, 0, 'TRAVEL', 0, 0, 'L');
        $this->Cell(30, 0, 'ATTN :', 0, 0, 'L');
        $this->Cell(30, 0, '', 0, 1, 'L');

        $this->Cell(15, 10, 'FROM :', 0, 0, 'L');
        $this->Cell(60, 10, 'JED KIM', 0, 0, 'L');
        $this->Cell(15, 10, 'DATE :', 0, 0, 'L');
        $this->Cell(30, 10, 'NOV. 24, 2024', 0, 1, 'L');

        // Main title
        $this->SetFont('Helvetica', 'B', 12);
        $this->Cell(0, 12, 'KOREA AUTUMN WITH MT. SORAK 5D/4N', 1, 1, 'C');

        $this->Ln(3); // Adds 10mm of vertical space

        // Set up columns for periods and hotel info
        $this->SetFont('Helvetica', 'B', 9);
        $this->Cell(40, 6, 'PERIODS', 1, 0, 'C');
        $this->Cell(70, 6, '10, OCT. 2024 - 15, NOV. 2024', 1, 0, 'C');
        $this->Cell(20, 6, 'GUIDE:', 1, 0, 'C');
        $this->Cell(60, 6, 'Sample Text', 1, 1, 'C');
    }

    // Add hotel info table
    public function addHotelInfoTable() {
        $this->SetFont('Helvetica', 'B', 10);

        // Create a vertical "HOTEL" cell spanning multiple rows
        $this->SetXY(10, 60);  // Adjust the X and Y position if needed
        $this->Cell(40, 15, 'HOTEL', 'LRB', 0, 'C', false);  // Borders on all sides, center-aligned text

        $this->SetFont('Helvetica', 'B', 10);
        // Create the horizontal cells for cities and their corresponding hotels
        $this->SetXY(50, 60.5);  // Adjust the Y to align the cells properly
        // Create the cells for the cities (Incheon, Gangwon, Seoul)
        $this->Cell(30, 4, 'INCHEON', 'LRB', 0, 'C');
        $this->Cell(120, 4, '   Air Sky Hotel', 'LRB', 1, 'L');

        $this->SetXY(50, 65.5);  // Adjust the Y to align the cells properly
        $this->Cell(30, 4, 'GANGWON', 'LRB', 0, 'C');
        $this->Cell(120, 4, '   Centrum Hotel', 'LRB', 1, 'L');

        $this->SetXY(50, 70.5); // Adjust the Y position after the city names
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

// Add multiple days dynamically
$pdf->day('01', 'INCHEON', "- Arrival at Incheon Airport (5j188 17:35-22:55)\n- Meeting and greeting English speaking Guide\n- Transfer to Hotel", 'Snack');
$pdf->day('02', 'GANGWON', "- Morning breakfast\n- Explore Gangwon\n- Visit local attractions", 'Lunch');
$pdf->day('03', 'SEOUL', "- Morning sightseeing tour\n- Free time in Seoul", 'Dinner');
$pdf->day('04', 'SEOUL', "- Visit Gyeongbokgung Palace\n- Explore Bukchon Hanok Village\n- Korean BBQ lunch", 'Breakfast and Dinner');
$pdf->day('05', 'SEOUL', "- Free day for shopping\n- Optional: Namsan Seoul Tower visit\n- Departure in the evening", 'Breakfast and Lunch');
$pdf->day('06', 'INCHEON', "- Transfer to Incheon Airport\n- Flight back home", 'Snack');

// Output the generated PDF
$pdf->Output();
?>
