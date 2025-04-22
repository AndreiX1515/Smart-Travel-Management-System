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
        <div class="page-header-wrapper">

          <div class="page-header-top">
            <div class="back-btn-wrapper">
              <button class="back-btn" id="redirect-btn">
                <i class="fas fa-chevron-left"></i>
              </button>
            </div>
          </div>

          <div class="page-header-content">
            <div class="page-header-text">
              <h5 class="header-title">Currency History (USD - PHP)</h5>
            </div>
          </div>

        </div>
      </div>

      <script>
        document.getElementById("redirect-btn").addEventListener("click", function () {
          window.location.href = "../Agent Section/agent-dashboard.php";
        });
      </script>

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
                  <!-- <th>Percentage Difference</th> -->
                  <th>Date and Time Recorded</th>
                </tr>
              </thead>
              <tbody id="currency-body">
                <tr>
                  <td colspan="4">Loading...</td>
                </tr>
              </tbody>
            </table>

          </div>
          <div class="table-footer">
            <div class="last-updated-wrapper">
              <h6>Last Updated: <span class="" id="lastUpdated"></span></h6>
            </div>

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

  <script>
    function fetchCurrencyRates() {
      fetch('../Agent Section/functions/currencyRateHistory/fetchCurrency.php')
        .then(response => response.json())
        .then(data => {
          const tbody = document.getElementById('currency-body');
          tbody.innerHTML = '';

          if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4">No currency data found.</td></tr>';
            return;
          }

          data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
              <td>${row.currencyLabel}</td>
              <td>${row.currentRate}</td>
              <td>${row.dateTime}</td>
            `;
            tbody.appendChild(tr);
          });
        })
        .catch(err => {
          console.error('Failed to fetch currency rates:', err);
        });
    }

    // Initial load
    fetchCurrencyRates();
    // Poll every 5 seconds
    setInterval(fetchCurrencyRates, 5000);

    // <td class="${row.changeClass}">${row.percentageDiff}</td>
  </script>





</body>

</html>