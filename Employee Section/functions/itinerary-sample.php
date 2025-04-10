<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include necessary files
if (!file_exists('../../tcpdf/tcpdf.php')) {
    die('TCPDF file not found!');
}
if (!file_exists('../../conn.php')) {
    die('Database connection file not found!');
}

require('../../tcpdf/tcpdf.php');
require('../../conn.php');

// Create a custom PDF class that extends TCPDF
class PDF extends TCPDF {
    private $packageName;
    private $noOfDays;
    private $periodStart;
    private $periodEnd;
    private $guideName;
    private $countryCode;
    private $contactNumber;

    public function setItineraryDetails($packageName, $noOfDays, $periodStart, $periodEnd, $guideName, $countryCode, $contactNumber) {
        $this->packageName = $packageName;
        $this->noOfDays = $noOfDays;
        $this->periodStart = $periodStart;
        $this->periodEnd = $periodEnd;
        $this->guideName = $guideName;
        $this->countryCode = $countryCode;
        $this->contactNumber = $contactNumber;
    }

    public function generateContent($itineraryDetails, $daysDetails) {
        $this->AddPage();
        
        // Title Section
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 10, 'Itinerary: ' . $itineraryDetails['packageName'], 0, 1, 'C');
        $this->SetFont('helvetica', '', 12);

        // Itinerary Details
        $this->Ln(5);
        $this->Cell(0, 10, 'Package Name: ' . $itineraryDetails['packageName']);
        $this->Cell(0, 10, 'No. of Days: ' . $itineraryDetails['noOfDays']);
        $this->Cell(0, 10, 'Period: ' . $itineraryDetails['periodStart'] . ' to ' . $itineraryDetails['periodEnd']);
        $this->Cell(0, 10, 'Guide: ' . $itineraryDetails['guideName']);
        $this->Cell(0, 10, 'Contact: ' . $itineraryDetails['countryCode'] . ' ' . $itineraryDetails['contactNumber']);
        
        // Day-by-Day Itinerary
        $this->Ln(10);
        foreach ($daysDetails as $day) {
            $this->Cell(0, 10, 'Day ' . $day['day'] . ':');
            $this->Ln(5);

            $this->Cell(0, 10, 'Areas: ' . implode(', ', $day['areas']));
            $this->Cell(0, 10, 'Hotels: ' . implode(', ', $day['hotels']));
            $this->Cell(0, 10, 'Activities: ' . implode(', ', $day['activities']));
            $this->Cell(0, 10, 'Meals: ' . implode(', ', $day['meals']));
            $this->Ln(10);
        }
    }
}

// Retrieve POST data
$itineraryDetails = json_decode($_POST['itineraryDetails'], true);
$daysDetails = json_decode($_POST['daysDetails'], true);

// Check if data is valid
if (empty($itineraryDetails) || empty($daysDetails)) {
    die('Invalid data received for itinerary or days details.');
}

// Create PDF object
$pdf = new PDF();

// Set itinerary details
$pdf->setItineraryDetails(
    $itineraryDetails['packageName'],
    $itineraryDetails['noOfDays'],
    $itineraryDetails['periodStart'],
    $itineraryDetails['periodEnd'],
    $itineraryDetails['guideName'],
    $itineraryDetails['countryCode'],
    $itineraryDetails['contactNumber']
);

// Generate the PDF content
$pdf->generateContent($itineraryDetails, $daysDetails);

// Clear previous output buffers
ob_end_clean();

// Set headers for PDF output
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="Itinerary_' . $itineraryDetails['packageName'] . '.pdf"');

// Output the PDF to the browser
$pdf->Output('', 'I');
exit();
?>
