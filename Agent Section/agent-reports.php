<?php 
session_start(); 

require "../conn.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-addGuest.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>
<body>
  <div class="body-container">
    <?php include "../Agent Section/includes/sidebar.php"; ?>

    <div class="main-content-container">
      <?php include "../Agent Section/includes/navbar.php"; ?>
      
      <div class="main-content">
        <div class="content-wrapper">
          <div class="content-body">

          <form method="POST" id="reportForm">
            <!-- Report Type Radio Button -->
            <div class="mb-4">
              <label class="form-label">Report Type:</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="reportType" id="monthlyReport" value="monthly" checked>
                <label class="form-check-label" for="monthlyReport">Monthly</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="reportType" id="weeklyReport" value="weekly">
                <label class="form-check-label" for="weeklyReport">Weekly</label>
              </div>
            </div>

            <!-- Monthly Selector -->
            <div id="monthlySelector" class="mb-3">
              <label for="month" class="form-label">Select Month:</label>
              <select class="form-select" name="month" id="month">
                <option selected disabled>Select Month</option>
                <option>January</option>
                <option>February</option>
                <option>March</option>
                <option>April</option>
                <option>May</option>
                <option>June</option>
                <option>July</option>
                <option>August</option>
                <option>September</option>
                <option>October</option>
                <option>November</option>
                <option>December</option>
              </select>
            </div>

            <input name="agentCode" value="<?php echo $agentCode; ?>" value="Agent Code">

            <!-- Weekly Selector -->
            <div id="weeklySelector" class="mb-3" style="display: none;">
              <label for="week" class="form-label">Select Week:</label>
              <select class="form-select" id="week" name="week">
                <option selected disabled>Select a week</option>
              </select>
            </div>

            <!-- Submit Button -->
            <div class="content-footer">
              <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
          </form>

          <!-- Table for Displaying Data -->
          <table class="table" id="dataTable" style="display:none;">
            <thead>
              <tr>
                <th>AGENT NAME</th>
                <th>FLIGHT DATE</th>
                <th>PAX</th>
                <th>AMOUNT</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <!-- Button to Generate the Report -->
          <button id="downloadReport" class="btn btn-success" style="display: none;">Download Report</button>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Optional Script to Toggle Selectors -->
  <script>
    const monthlyRadio = document.getElementById('monthlyReport');
    const weeklyRadio = document.getElementById('weeklyReport');
    const monthlySelector = document.getElementById('monthlySelector');
    const weeklySelector = document.getElementById('weeklySelector');

    monthlyRadio.addEventListener('change', () => {
      if (monthlyRadio.checked) {
        monthlySelector.style.display = 'block';
        weeklySelector.style.display = 'none';
      }
    });

    weeklyRadio.addEventListener('change', () => {
      if (weeklyRadio.checked) {
        weeklySelector.style.display = 'block';
        monthlySelector.style.display = 'none';
      }
    });
  </script>

  <script>
    function generateWeeks(year) {
      const select = document.getElementById('week');
      select.innerHTML = '<option disabled>Select a week</option>'; // Reset

      const start = new Date(year, 0, 1);
      const end = new Date(year, 11, 31);
      let weekNum = 1;

      const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
      ];

      const today = new Date();

      const format = (d) =>
        `${monthNames[d.getMonth()]} ${d.getDate().toString().padStart(2, '0')}, ${d.getFullYear()}`;

      // Align to first Monday
      while (start.getDay() !== 1) {
        start.setDate(start.getDate() + 1);
      }

      while (start < end) {
        const weekStart = new Date(start);
        const weekEnd = new Date(start);
        weekEnd.setDate(weekStart.getDate() + 6);

        const label = `${format(weekStart)} to ${format(weekEnd)}`;
        const value = `${year}-W${weekNum.toString().padStart(2, '0')}`;

        const option = document.createElement('option');
        option.value = value;
        option.textContent = label;

        // Auto-select if today is in this range
        if (today >= weekStart && today <= weekEnd) {
          option.selected = true;
        }

        select.appendChild(option);

        // Move to next week
        start.setDate(start.getDate() + 7);
        weekNum++;
      }
    }

    generateWeeks(new Date().getFullYear());
  </script>

  <script>
    document.getElementById("reportForm").addEventListener("submit", function(event) 
    {
      event.preventDefault();  // Prevent default form submission
      console.log('Form submitted');
      
      const formData = new FormData(this);
      console.log('Form data:', formData);

      fetch('../Agent Section/functions/agent-generateReports.php', 
      {
        method: 'POST',
        body: formData
      })
      .then(response => 
      {
        console.log('Response received:', response);
        return response.json();
      })
      .then(data => 
      {
        console.log('Response data:', data);

        if (data.error) 
        {
          alert(data.error);  // Show error message if no data
          console.log('Error in data:', data.error);
        } 
        else 
        {
          // Show table and fill it with data
          const tableBody = document.querySelector('#dataTable tbody');
          tableBody.innerHTML = '';  // Clear existing table data
          console.log('Filling table with data');
          data.data.forEach(row => 
          {
            console.log('Row data:', row); // Debug individual row data
            const tr = document.createElement('tr');
            tr.innerHTML = `
              <td>${row.date}</td>
              <td>${row.sales}</td>
              <td>${row.revenue}</td>`;
            tableBody.appendChild(tr);
          });

          document.getElementById('dataTable').style.display = 'table';  // Show the table
          document.getElementById('downloadReport').style.display = 'inline-block';  // Show download button
        }
      })
      .catch(error => 
      {
        console.error('Error during fetch:', error);
      });
    });

    // Download report (this could be CSV or Excel)
    document.getElementById('downloadReport').addEventListener('click', function() {
      console.log('Download button clicked');
      const formData = new FormData(document.getElementById("reportForm"));
      formData.append('format', 'csv'); // or 'excel' based on your choice

      fetch('generate-report.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        console.log('Response received for report download:', response);
        return response.blob();
      })
      .then(blob => {
        console.log('Blob received for report download');
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'report.csv';  // Set the file name
        link.click();
        console.log('Download initiated');
      })
      .catch(error => {
        console.error('Error during report download:', error);
      });
    });
  </script>

</body>

</html>