<?php
// Start session
session_start();


date_default_timezone_set('Asia/Taipei'); // Set the timezone to Taipei
$current_date = date('D, F d, Y'); // Format: "Tue, January 01, 2024"

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Agent Section/assets/css/agent-dashboard.css?v=<?php echo time(); ?>">
</head>

<body>

<?php include '../Agent Section/includes/sidebar.php' ?>

    <!-- Main Content Section -->
<div class="main-content" id="mainContent">
     <?php include '../Agent Section/includes/navbar.php' ?>

<!-- Main Dashboard Content -->
<div class="container-wrapper">
    <div class="info-container d-flex justify-content-between align-items-center">
        <div class="left-section d-flex align-items-center">
            <h2 class="info-title">Dashboard</h2>
        </div>

        <div class="right-section d-flex fw-bold">
          <div class="date-time-container d-flex flex-row align-items-center">
              <h6><?php echo $current_date; ?></h6>
              <i class="fa-solid fa-calendar-days"></i>
          </div>
      </div>
    </div>

     
    <div class="dashboard-cards d-flex flex-wrap justify-content-between">
   
    <div class="dashboard-cards-one card">
        <div class="dcard-header">
            <div class="header-text">
               <h3>Transaction Status Count:</h3>
            </div>
           
            <!-- <div class="view-button">
                <a class="btn">
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div> -->
        </div>

        <div class="dcard-body">
           <div class="month-transaction">
               <h3>18</h3>
               <span class="status-cancelled">CANCELLED</span>
           </div>

           <div class="vertical-line"></div>

           <div class="month-transaction">
               <h3>18</h3>
               <span class="status-unconfirmed">UNCONFIRMED</span>
           </div>

           <div class="vertical-line"></div>

           <div class="month-transaction">
               <h3>18</h3>
               <span class="status-confirmed">CONFIRMED</span>
           </div>
       </div>
     </div>

    <div class="dashboard-cards-two card">
        <div class="dcard-header">
            <div class="header-text">
               <h3>Booking Count:</h3>
            </div>
        </div>

        <div class="dcard-body">
           <div class="month-transaction">
               <h3>18</h3>
               <span class="">LAST MONTH</span>
               <span class="accent">(September)</span>
           </div>

           <div class="vertical-line"></div>

           <div class="month-transaction">
               <h3>18</h3>
               <span class="">THIS MONTH</span>
               <span class="accent">(October)</span>
           </div>

           <div class="vertical-line"></div>

           <div class="month-transaction">
               <h3>18</h3>
               <span class="">NEXT MONTH</span>
               <span class="accent">(November)</span>
           </div>
       </div>

        <div class="dcard-footer">


        </div>
    </div>

    <div class="dashboard-cards-three card">
      <div class="dcard-header">
          <div class="header-text">
             <h3>Transaction Count:</h3>
          </div>
         
          <!-- <div class="view-button">
              <a class="btn">
                  <i class="fa-solid fa-arrow-right"></i>
              </a>
          </div> -->
      </div>

      <div class="dcard-body">
         
      </div>

      <div class="dcard-footer">

      </div>

    </div>

    

<div class="dashboard-cards-four card">
    <div class="dcard-header">
        <div class="header-text">
            <h3>Currency Conversion</h3>
        </div>
    </div>

    
    <?php
// Your API key
$apiKey = '77dc42e0276c97b3f723a125';
$timeInterval = 3600; // 1 hour in seconds
$lastFetchFile = 'last_fetch_time.txt'; // File to store last fetch time
$exchangeRatesFile = 'exchange_rates.json'; // File to store exchange rates

// Function to fetch the latest conversion rates
function getExchangeRates($apiKey) {
    $url = "https://v6.exchangerate-api.com/v6/$apiKey/latest/USD"; // USD as the base currency
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Check last fetch time
if (file_exists($lastFetchFile)) {
    $lastFetchTime = (int)file_get_contents($lastFetchFile);

    // Fetch new rates if last fetch was more than 1 hour ago
    if (time() - $lastFetchTime >= $timeInterval) {
        $exchangeRates = getExchangeRates($apiKey);
        file_put_contents($exchangeRatesFile, json_encode($exchangeRates));
        file_put_contents($lastFetchFile, time()); // Update last fetch time
    } else {
        // Load rates from saved file
        $exchangeRates = json_decode(file_get_contents($exchangeRatesFile), true);
    }
} else {
    // First run, fetch rates from API
    $exchangeRates = getExchangeRates($apiKey);
    file_put_contents($exchangeRatesFile, json_encode($exchangeRates));
    file_put_contents($lastFetchFile, time()); // Set initial fetch time
}

// Extract conversion rates or use fallback values
$usd_to_php = $exchangeRates['conversion_rates']['PHP'] ?? 56.50; // Fallback if not found
$usd_to_krw = $exchangeRates['conversion_rates']['KRW'] ?? 1320;  // Fallback if not found
$usd_to_euro = $exchangeRates['conversion_rates']['EUR'] ?? 0.85;  // Fallback if not found
?>

<div class="dcard-body">
    <div class="currency-content mt-2">
        <div class="currency-item">
            <img src="../assets/images/Flags/english-flag.png" alt="" class="currency-flag">
            <p class="currency-name">1 USD</p>
            <p class="conversion-rate">$1.00</p> <!-- Rate of 1 USD to itself -->
        </div>

        <div>
            <i class="fa-solid fa-arrow-right-arrow-left"></i>
        </div>

        <div class="currency-item me-3">
            <img src="../assets/images/Flags/philippines (2).png" alt="" class="currency-flag">
            <p class="currency-name">PHP</p>
            <p class="conversion-rate">₱ <?php echo number_format($usd_to_php, 2); ?></p>
        </div>

        <div class="currency-item me-3">
            <img src="../assets/images/Flags/korean-flag.png" alt="" class="currency-flag">
            <p class="currency-name">WON</p>
            <p class="conversion-rate">₩ <?php echo number_format($usd_to_krw, 2); ?></p>
        </div>

        <div class="currency-item">
            <img src="../assets/images/Flags/european.png" alt="" class="currency-flag">
            <p class="currency-name">EURO</p>
            <p class="conversion-rate">€ <?php echo number_format($usd_to_euro, 2); ?></p> 
        </div>
    </div>
</div>


 </div>
  
</div> 

   <div class="second-row-container">
     <div class="one">
       <div class="header d-flex justify-content-between align-items-center">
          <h6>Unconfirmed Transactions</h6>
          <div class="view-booking-container">
              <a class="btn">
                  <i class="fa-solid fa-arrow-right"></i>
              </a>
          </div>
      </div>
      
       <div class="body mt-4">
           <!-- <table class="styled-table" id="transactionTable">
               <thead>
                   <tr>
                       <th>Transaction No.</th>
                       <th>Flight Date</th>
                       <th>Total Pax.</th>
                       <th>Contact Name</th>
                       <th>Status</th>
                   </tr>
               </thead>
               <tbody>
                   <tr>
                       <td>#Inv06562</td>
                       <td>18 Nov 2023</td>
                       <td>Henry, Arthur</td>
                       <td>$25</td>
                       <td><span class="payment-status cash"></span> Completed</td>
                   </tr>
                   <tr>
                       <td>#Inv03562</td>
                       <td>18 Nov 2023</td>
                       <td>Eleanor Pena</td>
                       <td>$4</td>
                       <td><span class="payment-status bank"></span> Pending</td>
                   </tr>
                   <tr>
                       <td>#Inv02645</td>
                       <td>18 Nov 2023</td>
                       <td>Courtney Henry</td>
                       <td>$5</td>
                       <td><span class="payment-status bank"></span> Pending</td>
                   </tr>
                   <tr>
                       <td>#Inv06256</td>
                       <td>18 Nov 2023</td>
                       <td>Jane Cooper</td>
                       <td>$21</td>
                       <td><span class="payment-status card"></span> Completed</td>
                   </tr>
                   <tr>
                       <td>#Inv06256</td>
                       <td>18 Nov 2023</td>
                       <td>Jane Cooper</td>
                       <td>$15</td>
                       <td><span class="payment-status cash"></span> Completed</td>
                   </tr>
                   <tr>
                       <td>#Inv06256</td>
                       <td>18 Nov 2023</td>
                       <td>Jane Cooper</td>
                       <td>$15</td>
                       <td><span class="payment-status cash"></span> Completed</td>
                   </tr>
               </tbody>
           </table> -->



           <footer class="footer">
               <!-- <div class="pagination-container">
                   <div id="pagination" class="pagination">
                       <button id="prevBtn" onclick="changePage(-1)">Previous</button>
                       <div id="pageNumbers" class="page-numbers">
                           <span class="page-number active">1</span>
                           <span class="page-number">2</span>
                           <span class="page-number">3</span>
                           <span class="page-number">4</span>
                       </div>
                       <button id="nextBtn" onclick="changePage(1)">Next</button>
                   </div>
               </div> -->
           </footer>
       </div>
   </div>



   <div class="two">
      <div class="header d-flex justify-content-between align-items-center">
          <h6>Requests</h6>
          <div class="view-booking-container">
              <a class="btn">
                  <i class="fa-solid fa-arrow-right"></i>
              </a>
          </div>
      </div>


     <!-- <div class="header d-flex justify-content-between align-items-center justify-content-between">
            <h6>Seats</h6>
            <div class="view-booking-container">
                <a class="btn">
                    <!-- <i class="fa-solid fa-arrow-right"></i> 
                </a>
            </div>
        </div>
        
     <div class="body">
         <canvas id="myDoughnutChart"></canvas> <!-- Doughnut chart will render here 
     </div> -->
  </div>


 </div>


 <div class="confirm-container">
   <div class="one">
         <div class="header d-flex justify-content-between align-items-center justify-content-between">
            <h6>Confirmed Transactions</h6>
            <div class="view-booking-container">
                <a class="btn">
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
        
         <div class="body mt-4">
             <!-- <table class="styled-table" id="transactionTable">
                 <thead>
                     <tr>
                         <th>Transaction No.</th>
                         <th>Flight Date</th>
                         <th>Total Pax.</th>
                         <th>Contact Name</th>
                         <th>Status</th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td>#Inv06562</td>
                         <td>18 Nov 2023</td>
                         <td>Henry, Arthur</td>
                         <td>$25</td>
                         <td><span class="payment-status cash"></span> Completed</td>
                     </tr>
                     <tr>
                         <td>#Inv03562</td>
                         <td>18 Nov 2023</td>
                         <td>Eleanor Pena</td>
                         <td>$4</td>
                         <td><span class="payment-status bank"></span> Pending</td>
                     </tr>
                     <tr>
                         <td>#Inv02645</td>
                         <td>18 Nov 2023</td>
                         <td>Courtney Henry</td>
                         <td>$5</td>
                         <td><span class="payment-status bank"></span> Pending</td>
                     </tr>
                     <tr>
                         <td>#Inv06256</td>
                         <td>18 Nov 2023</td>
                         <td>Jane Cooper</td>
                         <td>$21</td>
                         <td><span class="payment-status card"></span> Completed</td>
                     </tr>
                     <tr>
                         <td>#Inv06256</td>
                         <td>18 Nov 2023</td>
                         <td>Jane Cooper</td>
                         <td>$15</td>
                         <td><span class="payment-status cash"></span> Completed</td>
                     </tr>
                     <tr>
                         <td>#Inv06256</td>
                         <td>18 Nov 2023</td>
                         <td>Jane Cooper</td>
                         <td>$15</td>
                         <td><span class="payment-status cash"></span> Completed</td>
                     </tr>
                 </tbody>
             </table> -->



             <footer class="footer">
                 <!-- <div class="pagination-container">
                     <div id="pagination" class="pagination">
                         <button id="prevBtn" onclick="changePage(-1)">Previous</button>
                         <div id="pageNumbers" class="page-numbers">
                             <span class="page-number active">1</span>
                             <span class="page-number">2</span>
                             <span class="page-number">3</span>
                             <span class="page-number">4</span>
                         </div>
                         <button id="nextBtn" onclick="changePage(1)">Next</button>
                     </div>
                 </div> -->
             </footer>
         </div>
     </div>






 </div>






 </div>
</div>


<!-- Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart.js Data Labels plugin -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
 let currentPage = 1;
 const rowsPerPage = 5; // Number of rows to display per page
 const tableRows = document.querySelectorAll('#transactionTable tbody tr');

function displayTablePage(page) {
    const startIndex = (page - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;

    tableRows.forEach((row, index) => {
        row.style.display = (index >= startIndex && index < endIndex) ? '' : 'none';
    });

    updatePagination();
}

function updatePagination() {
    const totalPages = Math.ceil(tableRows.length / rowsPerPage);
    const pageNumbers = document.querySelectorAll('.page-number');

    pageNumbers.forEach((number, index) => {
        number.classList.remove('active');
        if (index < totalPages) {
            number.textContent = index + 1;
            number.style.display = 'inline'; // Show number if within total pages
        } else {
            number.style.display = 'none'; // Hide excess page numbers
        }
    });

    if (currentPage <= 1) {
        document.getElementById('prevBtn').disabled = true;
    } else {
        document.getElementById('prevBtn').disabled = false;
    }

    if (currentPage >= totalPages) {
        document.getElementById('nextBtn').disabled = true;
    } else {
        document.getElementById('nextBtn').disabled = false;
    }

    pageNumbers[currentPage - 1].classList.add('active'); // Highlight the current page
}

function changePage(direction) {
    const totalPages = Math.ceil(tableRows.length / rowsPerPage);
    currentPage += direction;

    // Ensure the current page stays within the valid range
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages) currentPage = totalPages;

    displayTablePage(currentPage);
}

// Initial display of the first page
displayTablePage(currentPage);
</script>


<!-- Script for rendering the Doughnut chart -->
<script>
    var ctx = document.getElementById('myDoughnutChart').getContext('2d');
    var myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Total Seats', 'Seats Sold', 'Remaining Seats'],
            datasets: [{
                data: [46, 15, 29], // Example data
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'], // Example colors
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Ensure the chart resizes to fit the div
            cutout: '80%', // Adjusts the thickness of the doughnut
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom', // Keep legend on the right side
                    labels: {
                        boxWidth: 15, // Adjust size of legend boxes
                        padding: 10 // Reduce padding between legend and doughnut
                    }
                },
                datalabels: {
                   color: '#000', // Label color
                   anchor: 'center', // Position the label inside the doughnut
                   align: 'center',
                   borderColor: '#36A2EB', // Border color around the label
                   borderWidth: 2, // Thickness of the border
                   backgroundColor: '#fff', // Background color of the label
                   borderRadius: 4, // Rounded corners for the label background
                   padding: 6, // Padding around the label for spacing
                   font: {
                       weight: 'bold' // Make the label font bold
                   },
                   formatter: (value, context) => {
                       let sum = 0;
                       let dataArr = context.chart.data.datasets[0].data;
                       dataArr.map(data => {
                           sum += data;
                       });
                       let percentage = (value * 100 / sum).toFixed(1) + "%"; // Display percentage with one decimal
                       return percentage;
                   }
               }
  
            }
        },
        plugins: [ChartDataLabels] // Activate the datalabels plugin
    });
</script>


<?php require "../Agent Section/scripts/script.php"; ?>

<?php require "../Agent Section/includes/scripts.php"; ?>

</body>

</html>
