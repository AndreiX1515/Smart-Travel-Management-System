<?php
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Check if form was submitted
if (isset($_POST['action']) && $_POST['action'] === 'generate') {
    $reportTitle = $_POST['title'] ?? 'Sample Report';
    $data = [];
    
    // Get data from form
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($_POST["name_$i"])) {
            $data[] = [
                'id' => $i,
                'name' => $_POST["name_$i"] ?? '',
                'email' => $_POST["email_$i"] ?? '',
                'department' => $_POST["dept_$i"] ?? '',
                'salary' => $_POST["salary_$i"] ?? ''
            ];
        }
    }
    
    if (!empty($data)) {
        $result = generateReport($reportTitle, $data);
        
        if ($result['success']) {
            // Provide download links
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px;'>";
            echo "<h3>Files generated successfully!</h3>";
            echo "<p><a href='{$result['excel']}' download>Download Excel File</a></p>";
            echo "<p><a href='{$result['pdf']}' download>Download PDF File</a></p>";
            echo "<p><a href='?'>Generate Another Report</a></p>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px;'>";
            echo "<h3>Error: {$result['error']}</h3>";
            echo "</div>";
        }
    }
} else {
    // Show the form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Excel to PDF Generator</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            .form-container { max-width: 800px; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
            th { background-color: #f2f2f2; }
            input[type="text"], input[type="email"] { width: 100%; padding: 5px; }
            .submit-btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
            .submit-btn:hover { background: #0056b3; }
        </style>
    </head>
    <body>
        <div class="form-container">
            <h1>Excel to PDF Report Generator</h1>
            <form method="POST">
                <input type="hidden" name="action" value="generate">
                
                <div style="margin: 20px 0;">
                    <label><strong>Report Title:</strong></label>
                    <input type="text" name="title" value="Employee Report" style="width: 300px; padding: 5px;">
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <tr>
                            <td><input type="text" name="name_<?= $i ?>" placeholder="Enter name"></td>
                            <td><input type="email" name="email_<?= $i ?>" placeholder="Enter email"></td>
                            <td><input type="text" name="dept_<?= $i ?>" placeholder="Department"></td>
                            <td><input type="text" name="salary_<?= $i ?>" placeholder="$50,000"></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
                
                <button type="submit" class="submit-btn">Generate Excel & PDF</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}

function generateReport($title, $data) {
    try {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Report');
        
        // Header
        $sheet->setCellValue('A1', strtoupper($title));
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Date
        $sheet->setCellValue('A2', 'Generated: ' . date('Y-m-d H:i:s'));
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Table headers
        $headers = ['Name', 'Email', 'Department', 'Salary'];
        $headerRow = 4;
        
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index);
            $sheet->setCellValue($column . $headerRow, $header);
        }
        
        // Style headers
        $headerRange = 'A' . $headerRow . ':D' . $headerRow;
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');
        
        // Add data
        $dataStartRow = $headerRow + 1;
        foreach ($data as $rowIndex => $row) {
            $currentRow = $dataStartRow + $rowIndex;
            $sheet->setCellValue('A' . $currentRow, $row['name']);
            $sheet->setCellValue('B' . $currentRow, $row['email']);
            $sheet->setCellValue('C' . $currentRow, $row['department']);
            $sheet->setCellValue('D' . $currentRow, $row['salary']);
        }
        
        // Borders
        $tableRange = 'A' . $headerRow . ':D' . ($dataStartRow + count($data) - 1);
        $sheet->getStyle($tableRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
        
        // Auto-size columns
        foreach (range('A', 'D') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Generate filenames
        $timestamp = date('Y-m-d_H-i-s');
        $excelFile = 'report_' . $timestamp . '.xlsx';
        $pdfFile = 'report_' . $timestamp . '.pdf';
        
        // Save Excel
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFile);
        
        // Save PDF
        $pdfWriter = new Mpdf($spreadsheet);
        $pdfWriter->save($pdfFile);
        
        return [
            'success' => true,
            'excel' => $excelFile,
            'pdf' => $pdfFile
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}
?>