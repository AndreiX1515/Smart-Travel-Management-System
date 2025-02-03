<?php
require_once('../../tcpdf/tcpdf.php');
session_start();

// Get the selected filter values from the POST request
$companyId = $_POST['companyId'];
$monthName = date('F', strtotime($_POST['month']));
$year = $_POST['year'];
$formattedDate = $_POST['currentDate'];
$soaNumber = $_POST['soaNumber'];

$space = "";

class PDF extends TCPDF 
{
  private $yPosition;
  private $branchName = '';
  private $formattedDate = '';
  private $monthName = '';
  private $soaNumber = '';

  public function setBranchName($branchName) 
  {
    $this->branchName = $branchName;
  }

  public function setUpdateDate($formattedDate)
  {
    $this->formattedDate = $formattedDate;
  }

  public function setDateRange($monthName)
  {
    $this->monthName = $monthName;
  }

  public function setSoANo($soaNumber)
  {
    $this->soaNumber = $soaNumber;
  }

  // Header function
  public function Header() 
  {
    if ($this->getPage() == 1) 
    { // Check if it's the first page
      // Add logo
      $this->Image('../../Assets/Logos/SMART LOGO 2 (2).jpg', 10, 10, 65, 13); // Adjust 'logo.png' path, position, and size as needed
      $this->Ln(30); // Adds 30mm of vertical space

      // Voucher title
      $this->SetFillColor(211, 211, 211); // Set the fill color
      $this->SetTextColor(0, 0, 0); // Text color
      $this->SetFont('Helvetica', 'B', 12, true);
      $this->Cell(0, 10, 'STATEMENT OF ACCOUNT (SOA)', 'LRTB', 1, 'C', true);

      // Add TO, ATTACHMENT, etc.
      $this->SetFont('Helvetica', '', 10, true);
      $this->SetXY(10, 43);
      $this->Cell(30, 8, 'SOA NO.:', 1, 0, 'C');
      $this->Cell(70, 8, $this->soaNumber, 1, 0, 'C');
      $this->Cell(30, 8, 'DATE RANGE:', 1, 0, 'C');
      $this->Cell(60, 8, $this->monthName, 1, 1, 'C');

      $this->SetXY(10, 51);
      $this->Cell(30, 8, 'BILL TO:', 1, 0, 'C');
      $this->Cell(70, 8, $this->branchName, 1, 0, 'C');
      $this->Cell(30, 8, 'FROM:', 1, 0, 'C');
      $this->Cell(60, 8, 'Smart Travel', 1, 1, 'C');

      $this->SetXY(110, 59);

      $this->Cell(30, 8, 'UPDATE DATE:', 1, 0, 'C');
      $this->Cell(60, 8, $this->formattedDate, 1, 1, 'C');

      // Add a bit of space before starting the table
      $this->Ln(2); 
    }
  }

  // Table Header function
  public function tableHeader() 
  {
    $this->SetFont('Helvetica', 'B', 10);

    // Add some space after the hotel info table (to prevent overlap)
    $this->Ln(2);

    // Set the X and Y for the header
    $this->SetXY(10, 69);

    $this->SetFont('Helvetica', 'B', 10, true);
    $this->SetFillColor(255, 255, 255); // White background
    $this->SetTextColor(0, 0, 0); // Black text color

    // Render header cells
    $this->Cell(10, 6, 'NO.', 1, 0, 'C', true); 
    $this->Cell(55, 6, " ".'CONTENTS', 1, 0, 'L', true);
    $this->Cell(27, 6, 'Price (USD)', 1, 0, 'C', true);
    $this->Cell(27, 6, 'Price (PHP)', 1, 0, 'C', true);
    $this->Cell(10, 6, 'PAX', 1, 0, 'C', true);
    $this->Cell(30.5, 6, 'TOTAL (USD)', 1, 0, 'C', true);
    $this->Cell(30.5, 6, 'TOTAL (PHP)', 1, 1, 'C', true);

    $this->SetFont('Helvetica', '', 10, true);
    // Reset text color
    $this->SetTextColor(0, 0, 0);
  }

  public function tableContent($tableData, $yPosition) 
  {
    $this->SetFont('Helvetica', 'B', 10);

    // Add some space after the hotel info table (to prevent overlap)
    $this->Ln(2);

    // Set the X and Y for the header
    $this->SetXY(10, $yPosition);

    // Header cells
    $this->SetFont('Helvetica', '', 10, true);
    $this->SetFillColor(255, 255, 255); // White background
    $this->SetTextColor(0, 0, 0); // Black text color

    $col1 = 10;
    $col2 = 55;
    $col3 = 27;
    $col4 = 10;
    $col5 = 30.5;
    $col6 = 30.5;

    // Reset text color
    $this->SetTextColor(0, 0, 0);
  
    // Loop through content rows and adjust Y position
    foreach ($tableData as $row) 
    {
      // Set the X and Y for each row based on the current Y position
      $this->SetXY(10, $yPosition);

      // Render cells with data
      $this->Cell($col1, 7, $row['no'], 1, 0, 'C');
      $this->Cell($col2, 7, " ".$row['contents'], 1, 0, 'L');
      $this->Cell($col3, 7, " ", 1, 0, 'C');
      $this->Cell($col3, 7, $row['price'], 1, 0, 'C');
      $this->Cell($col4, 7, $row['pax'], 1, 0, 'C');
      $this->Cell($col5, 7, $row['total_usd'], 1, 0, 'R');
      $this->Cell($col6, 7, $row['total_php'], 1, 1, 'R');

      // Increment Y position for the next row
      $yPosition += 7;
    }

    // Return the final Y position for reference
    return $yPosition;
  }
  
  public function tableContentSubTotal($subTotal, $yPosition) 
  {
    $this->SetFont('Helvetica', 'B', 10);

    // Add some space after the previous content (to prevent overlap)
    $this->Ln(2);

    // Set the X and Y for the header based on passed Y position
    $this->SetXY(10, $yPosition);

    // Header cells
    $this->SetFont('Helvetica', 'B', 10, true);
    
    $this->SetFillColor(211, 211, 211); // Set the fill color
    $this->SetTextColor(0, 0, 0); // Black text color

    // Render subtotal cells
    $this->Cell(92, 7, '', 1, 0, 'C', true);


    $this->SetFont('Helvetica', 'B', 7.5, true);

    $this->Cell(37, 7, "  ".'SUB TOTAL: ', 1, 0, 'L', true);

    $this->SetFont('Helvetica', 'B', 10, true);

    $this->Cell(30.5, 7, " ", 1, 0, 'R', true);  // Dynamically use the $subTotal variable
    $this->Cell(30.5, 7, $subTotal, 1, 0, 'R', true);  // Dynamically use the $subTotal variable

    // Reset text color
    $this->SetFont('Helvetica', '', 10, true);
    $this->SetFillColor(255, 255, 255); // White background
    $this->SetTextColor(0, 0, 0);

    // Return the final Y position for reference (add 7 for the row height)
    return $yPosition + 7;
  }
  
  public function tableRequest($tableData2, $yPosition) 
  {
    $this->SetFont('Helvetica', 'B', 10);

    // Add some space after the hotel info table (to prevent overlap)
    $this->Ln(2);

    // Set the X and Y for the header
    $this->SetXY(10, $yPosition);

    // Header cells
    $this->SetFont('Helvetica', '', 10, true);
    $this->SetFillColor(255, 255, 255); // White background
    $this->SetTextColor(0, 0, 0); // Black text color

    $col1 = 10;
    $col2 = 55;
    $col3 = 27;
    $col4 = 10;
    $col5 = 30.5;
    $col6 = 30.5;

    // Reset text color
    $this->SetTextColor(0, 0, 0);
  
    // Loop through content rows and adjust Y position
    foreach ($tableData2 as $row) 
    {
      // Set the X and Y for each row based on the current Y position
      $this->SetXY(10, $yPosition);

      // Render cells with data
      $this->Cell($col1, 7, $row['no'], 1, 0, 'C');
      $this->Cell($col2, 7, "  " . $row['contents'], 1, 0, 'L');
      $this->Cell($col3, 7, " ", 1, 0, 'C');
      $this->Cell($col3, 7, $row['price'], 1, 0, 'C');
      $this->Cell($col4, 7, $row['pax'], 1, 0, 'C');
      $this->Cell($col5, 7, $row['total_usd'], 1, 0, 'R');
      $this->Cell($col6, 7, $row['total_php'], 1, 1, 'R');

      // Increment Y position for the next row
      $yPosition += 7;
    }

    // Return the final Y position for reference
    return $yPosition;
  }
  
  public function tableContentSubTotal2($totalRequestCost, $yPosition) 
  {
    $this->SetFont('Helvetica', 'B', 10);

    // Add some space after the previous content (to prevent overlap)
    $this->Ln(2);

    // Set the X and Y for the header based on passed Y position
    $this->SetXY(10, $yPosition);

    // Header cells
    $this->SetFont('Helvetica', '', 10, true);
    $this->SetFillColor(211, 211, 211); // Set the fill color
    $this->SetTextColor(0, 0, 0); // Black text color

    // Render subtotal cells
    $this->Cell(92, 7, '', 1, 0, 'C', true);
    $this->SetFont('Helvetica', 'B', 7, true);

    $this->Cell(37, 7, "  " . 'TOTAL REQUEST COST: ', 1, 0, 'L', true);

    $this->SetFont('Helvetica', 'B', 10, true);

    $this->Cell(30.5, 7, " ", 1, 0, 'R', true);
    $this->Cell(30.5, 7, $totalRequestCost, 1, 0, 'R', true);

    // Reset text color
    $this->SetFillColor(255, 255, 255); // White background
    $this->SetTextColor(0, 0, 0);

    // Return the final Y position for reference (add 10 for the row height)
    return $yPosition + 7;
  }

  public function tablePayment($tableData3, $yPosition) 
  {
    $this->SetFont('Helvetica', 'B', 8.5);
    
    // Add some space after the previous content (to prevent overlap)
    $this->Ln(2);
    
    // Set the X and Y for the header based on passed Y position
    $this->SetXY(10, $yPosition);
    
    // Header cells
    $this->SetFont('Helvetica', '', 10, true);
    $this->SetFillColor(255, 255, 255); // White background
    $this->SetTextColor(0, 0, 0); // Black text color
    
    $col1 = 10;
    $col2 = 55;
    $col3 = 27;
    $col4 = 10;
    $col5 = 30.5;
    $col6 = 30.5;

    // Reset text color
    $this->SetTextColor(0, 0, 0);
    
    // Loop through content rows and adjust Y position
    foreach ($tableData3 as $row) 
    {
      // Set the X and Y for each row based on the current Y position
      $this->SetXY(10, $yPosition);
  
      // Render cells with data
      $this->Cell($col1, 7, $row['no'], 1, 0, 'C');
      $this->Cell($col2, 7, "  " . $row['contents'], 1, 0, 'L');
      $this->Cell($col3, 7, $row['price'], 1, 0, 'C');
      $this->Cell($col3, 7, $row['price'], 1, 0, 'C');
      $this->Cell($col4, 7, $row['pax'], 1, 0, 'C');
      $this->Cell($col5, 7, $row['total_usd'], 1, 0, 'R');
      $this->Cell($col6, 7, $row['total_php'], 1, 1, 'R');
  
      // Increment Y position for the next row
      $yPosition += 7;
    }
    
    // Return the final Y position for reference
    return $yPosition;
  }

  public function tableContentSubTotal3($totalAmount, $yPosition) 
  {
    $this->SetFont('Helvetica', 'B', 10);

    // Add some space after the previous content (to prevent overlap)
    $this->Ln(2);

    // Set the X and Y for the header based on passed Y position
    $this->SetXY(10, $yPosition);

    // Header cells
    $this->SetFont('Helvetica', 'B', 10, true);
    $this->SetFillColor(211, 211, 211); // Set the fill color
    $this->SetTextColor(0, 0, 0); // Black text color

    // Render subtotal cells
    $this->Cell(92, 7, '', 1, 0, 'C', true);
    $this->SetFont('Helvetica', 'B', 8, true);
    $this->Cell(37, 7, "  " . 'TOTAL PAYMENT: ', 1, 0, 'L', true);
    $this->SetFont('Helvetica', 'B', 10, true);
    $this->Cell(30.5, 7, " ", 1, 0, 'R', true);
    $this->Cell(30.5, 7, $totalAmount, 1, 0, 'R', true);

    // Reset text color and font
    $this->SetFont('Helvetica', '', 10); // Reset to normal weight
    $this->SetFillColor(255, 255, 255); // White background
    $this->SetTextColor(0, 0, 0);

    // Return the final Y position for reference (add 10 for the row height)
    return $yPosition + 7;
  }
    
  public function tableBalance($balance, $yPosition) 
  {
    $this->SetFont('Helvetica', 'B', 10);

    // Add some space after the previous content (to prevent overlap)
    $this->Ln(2);

    // Set the X and Y for the header based on passed Y position
    $this->SetXY(10, $yPosition);

    // Header cells
    $this->SetFont('Helvetica', 'B', 10); // Set font to bold ('B')
    $this->SetFillColor(211, 211, 211); // Set the fill color
    $this->SetTextColor(0, 0, 0); // Black text color

    // Render header cells for Balance table
    $this->Cell(92, 7, '', 1, 0, 'C', true); 
    $this->SetFont('Helvetica', 'B', 8);
    $this->Cell(37, 7, "  " . 'BALANCE: ', 1, 0, 'L', true); 
    $this->SetFont('Helvetica', 'B', 10);
    $this->Cell(30.5, 7, $balance, 1, 0, 'R', true);
    $this->Cell(30.5, 7, $balance, 1, 0, 'R', true);  

    // Reset text color and font
    $this->SetFont('Helvetica', '', 10); // Reset to normal weight
    $this->SetFillColor(255, 255, 255); // White background
    $this->SetTextColor(0, 0, 0);

    // Return the final Y position for reference (add 7 for the row height)
    return $yPosition + 11;
  }

  public function accountInfo($yPosition) 
  {
    $this->SetFont('Helvetica', 'B', 10);

    // Add some space after the previous content (to prevent overlap)
    $this->Ln(2);

    // Set the X and Y for the header based on passed Y position
    $this->SetXY(10, $yPosition);

    // Header cells
    $this->SetFont('Helvetica', '', 10, true);
    $this->SetFillColor(255, 255, 255); // White background
    $this->SetTextColor(0, 0, 0); // Black text color   

    // Render header cells for Balance table using MultiCell
    $this->SetFont('Helvetica', 'B', 10, true);
    // Render MultiCell with no bottom border
    $this->MultiCell(190, 4, 'ACCOUNT INFORMATION', 'LTR', 'L', true);
    $this->SetFont('Helvetica', '', 10, true);
    $this->MultiCell(190, 7, 
    "    " . 'Bank Name: Banco De Oro (BDO) - Zuellig Branch Makati Avenue
    Name of Account: Hyung Sub Kim (Nickname: Jed Kim)
    Peso Account No.: 007800203252
    US Dollar Account No.: 107800113512','LBR', 'L', true);

    // Reset text color
    $this->SetTextColor(0, 0, 0);

    // Return the final Y position for reference (add 7 for the row height)
    return $yPosition + 7;
  }
}

// Create a new PDF instance and add pages as needed
$pdf = new PDF();

$tableData = $_SESSION['tableData'];
$totalPriceSum = $_SESSION['totalPriceSum'];
$tableData2 = $_SESSION['tableData2'];
$totalRequestCost = $_SESSION['totalRequestCost'];
$tableData3 =  $_SESSION['tableData3'];
$totalAmount = $_SESSION['totalAmount'];
$balance = $_SESSION['balance'];
$branchName = $_SESSION['branchName'];

// Set margins
$pdf->SetMargins(10, 10, 10); // Adjust to provide consistent spacing


// $pdf->tableBalance();
// $pdf->tableContentSubTotal();
// Output the PDF

// Set the branch name
$pdf->setBranchName($branchName);
$pdf->setDateRange($monthName);
$pdf->setUpdateDate($formattedDate);
$pdf->setSoANo($soaNumber);
$pdf->AddPage();
$pdf->tableHeader();

// Get the initial Y position after rendering the header
$yPosition = 75; // Set the starting position for the first table

// Pass the Y position to tableContent and get the updated position
$yPosition = $pdf->tableContent($tableData, $yPosition);

// Pass the updated Y position to tableContentSubTotal and get the final position
$yPosition = $pdf->tableContentSubTotal($totalPriceSum, $yPosition);

// Pass the final Y position to tablePayment and get the final position
$yPosition = $pdf->tableRequest($tableData2, $yPosition);

// Pass the final Y position to tableContentSubTotal2 and get the final position
$yPosition = $pdf->tableContentSubTotal2($totalRequestCost, $yPosition);

// Pass the final Y position to tablePayment and get the final position
$yPosition = $pdf->tablePayment($tableData3, $yPosition);

// Pass the final Y position to tableContentSubTotal2 and get the final position
$yPosition = $pdf->tableContentSubTotal3($totalAmount, $yPosition);

// Pass the updated Y position to tableBalance and get the final position
$yPosition = $pdf->tableBalance($balance, $yPosition);

$yPosition = $pdf->accountInfo($yPosition);

// Output the PDF
$pdf->Output('itinerary-Winter.pdf', 'I');

// Clear session variables after the PDF is output
unset($_SESSION['tableData']);
unset($_SESSION['totalPriceSum']);
unset($_SESSION['tableData2']);
unset($_SESSION['totalRequestCost']);
unset($_SESSION['tableData3']);
unset($_SESSION['totalAmount']);
unset($_SESSION['balance']);
unset($_SESSION['branchName']);
?>