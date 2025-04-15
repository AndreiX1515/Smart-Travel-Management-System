<?php
require "../../conn.php"; // DB connection
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'] ?? '';
    $month = $_POST['month'] ?? '';
    $week = $_POST['week'] ?? '';
    $agentCode = $_POST['agentCode'] ?? '';

    // Monthly report
    if ($reportType === 'monthly' && $month) 
    {
      // Convert month name to numeric format
      $monthNum = date('m', strtotime($month));
      $year = date('Y'); // Or get from POST if dynamic

      $sql = "SELECT b.pax, b.totalPrice, f.flightDepartureDate as departureDate, f.returnArrivalDate as arrivalDate
              FROM booking b
              JOIN flight f ON f.flightId = b.flightId
              JOIN 
              WHERE MONTH(bookingDate) = ? AND YEAR(bookingDate) = ? AND agentCode = ?";
      
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("iis", $monthNum, $year, $agentCode);
      $stmt->execute();
      $result = $stmt->get_result();

      $reportData = [];
      while ($row = $result->fetch_assoc()) 
      {
        $reportData[] = $row;
      }

      if (empty($reportData)) 
      {
        $response['error'] = "No records found for $month.";
      } 
      else 
      {
        $response['data'] = $reportData;
      }
    }
    // Weekly report
    elseif ($reportType === 'weekly' && $week) 
    {
        // Assume week format is YYYY-W## (e.g., 2025-W15)
        $weekParts = explode('-W', $week);
        $year = $weekParts[0];
        $weekNum = $weekParts[1];

        $sql = "SELECT DATE(sale_date) AS date, COUNT(*) AS sales, SUM(amount) AS revenue
                FROM reports_table
                WHERE YEAR(sale_date) = ? AND WEEK(sale_date, 1) = ?
                GROUP BY DATE(sale_date)
                ORDER BY DATE(sale_date) ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $year, $weekNum);
        $stmt->execute();
        $result = $stmt->get_result();

        $reportData = [];
        while ($row = $result->fetch_assoc()) {
            $reportData[] = $row;
        }

        if (empty($reportData)) {
            $response['error'] = "No records found for week $week.";
        } else {
            $response['data'] = $reportData;
        }
    } 
    else 
    {
      $response['error'] = 'Missing or invalid report type/selection';
    }
} 
else 
{
  $response['error'] = 'Invalid request method';
}

echo json_encode($response);
?>
