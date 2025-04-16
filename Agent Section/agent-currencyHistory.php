<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-currency-conversion.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>

  <div class="body-container">
    <?php include "../Agent Section/includes/sidebar.php"; ?>

    <div class="main-content-container">
      <div class="navbar">
        <h5 class="title-page">Currency History (USD - PHP)</h5>
      </div>

      <div class="main-content">
        <div class="content-header">

        </div>

        <div class="content-body">
          <div class="table-container">
          <table id="currency-table" class="currency-table">
  <thead>
    <tr>
      <th>Currency</th>
      <th>Rate</th>
      <th>Percentage Difference</th>
      <th>Date and Time Recorded</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Fetch all currency data
    $query = "SELECT * FROM currencyRates ORDER BY base_currency, target_currency, time_recorded DESC";
    $result = $conn->query($query);

    $currencyMap = [];

    if ($result->num_rows > 0) {
      // Group data by currency pair and date
      while ($row = $result->fetch_assoc()) {
        $pairKey = $row['base_currency'] . '_' . $row['target_currency'];
        $date = date('Y-m-d', strtotime($row['time_recorded']));
        $currencyMap[$pairKey][$date] = $row; // map latest rate by date
      }

      // Iterate through the data
      foreach ($currencyMap as $pair => $dates) {
        krsort($dates); // sort dates descending

        foreach ($dates as $currentDate => $currentData) {
          $currentRate = $currentData['exchange_rate'];
          $currencyLabel = $currentData['base_currency'] . ' to ' . $currentData['target_currency'];
          $dateTime = $currentData['time_recorded'];

          // Get yesterday's date
          $yesterday = date('Y-m-d', strtotime($currentDate . ' -1 day'));
          $percentageDiff = 'N/A';
          $changeClass = 'rate-neutral';
          $arrow = '';

          if (isset($dates[$yesterday])) {
            $yesterdayRate = $dates[$yesterday]['exchange_rate'];
            $diff = $currentRate - $yesterdayRate;
            $percentChange = ($diff / $yesterdayRate) * 100;

            $arrow = $percentChange > 0 ? '↑' : ($percentChange < 0 ? '↓' : '');
            $changeClass = $percentChange > 0 ? 'rate-up' : ($percentChange < 0 ? 'rate-down' : 'rate-neutral');
            $symbol = $percentChange >= 0 ? '+' : '';
            $percentageDiff = $arrow . ' ' . $symbol . number_format($percentChange, 2) . '%';

            // // Optional debugging
            // echo "<script>console.log('{$pair} | {$currentDate}: {$currentRate} vs {$yesterday}: {$yesterdayRate} → {$percentageDiff}');</script>";
          }

          echo '<tr>';
          echo '<td>' . $currencyLabel . '</td>';
          echo '<td>' . $currentRate . '</td>';
          echo '<td class="' . $changeClass . '">' . $percentageDiff . '</td>';
          echo '<td>' . $dateTime . '</td>';
          echo '</tr>';
        }
      }
    } else {
      echo '<tr><td colspan="4">No currency data found.</td></tr>';
    }

    $conn->close();
    ?>
  </tbody>
</table>


          </div>

          <div class="table-footer">
            <div class="pagination-controls">
              <button id="prevPage" class="pagination-btn">Previous</button>
              <span id="pageInfo" class="page-info">Page 1 of 10</span>
              <button id="nextPage" class="pagination-btn">Next</button>
            </div>
          </div>
        </div>


      </div>
    </div>
  </div>


  <?php require "../Agent Section/includes/scripts.php"; ?>

</body>

</html>