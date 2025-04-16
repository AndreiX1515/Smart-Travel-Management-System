<?php
require "../../../conn.php"; // DB connection

// Fetch all records for sorting and displaying
$query = "SELECT * FROM currencyRates ORDER BY base_currency, target_currency, time_recorded DESC";
$result = $conn->query($query);

$currencyMap = [];
$data = [];

if ($result->num_rows > 0) {
    // Organize data by currency pair and date
    while ($row = $result->fetch_assoc()) {
        $pairKey = $row['base_currency'] . '_' . $row['target_currency'];
        $date = date('Y-m-d', strtotime($row['time_recorded']));
        $currencyMap[$pairKey][$date][] = $row;  // Store multiple entries per date
    }

    // Process each currency pair
    foreach ($currencyMap as $pair => $dates) {
        // Sort the dates in descending order
        krsort($dates);

        // Now loop through the dates and process each one
        foreach ($dates as $currentDate => $entries) {
            foreach ($entries as $currentData) {
                $currentRate = $currentData['exchange_rate'];
                $currencyLabel = $currentData['base_currency'] . ' to ' . $currentData['target_currency'];
                $dateTime = $currentData['time_recorded'];

                // Prepare the change percentage logic
                $yesterday = date('Y-m-d', strtotime($currentDate . ' -1 day'));
                $percentageDiff = 'N/A';
                $changeClass = 'rate-neutral';
                $arrow = '';

                // If a rate exists for the previous day, calculate the percentage change
                if (isset($dates[$yesterday])) {
                    $yesterdayRate = $dates[$yesterday][0]['exchange_rate']; // Assuming only one rate per date
                    $diff = $currentRate - $yesterdayRate;
                    $percentChange = ($diff / $yesterdayRate) * 100;
                    $arrow = $percentChange > 0 ? '↑' : ($percentChange < 0 ? '↓' : '');
                    $changeClass = $percentChange > 0 ? 'rate-up' : ($percentChange < 0 ? 'rate-down' : 'rate-neutral');
                    $symbol = $percentChange >= 0 ? '+' : '';
                    $percentageDiff = $arrow . ' ' . $symbol . number_format($percentChange, 2) . '%';
                }

                // Collect the final data for JSON output
                $data[] = [
                    'currencyLabel' => $currencyLabel,
                    'currentRate' => $currentRate,
                    'percentageDiff' => $percentageDiff,
                    'changeClass' => $changeClass,
                    'dateTime' => $dateTime
                ];
            }
        }
    }
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($data);
?>
