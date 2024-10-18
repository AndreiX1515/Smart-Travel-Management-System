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
    <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
</head>

<body>

<?php include '../Agent Section/includes/sidebar.php' ?>

<div class="main-content" id="mainContent">

 <?php include '../Agent Section/includes/navbar.php' ?>

 <!-- First row Div -->
<div class="info-container d-flex justify-content-between align-items-center">
  <div class="left-section d-flex align-items-center">
      <h4 class="info-title">Your Transaction Records</h4>
      <!-- <div class="date-picker d-flex align-items-center ml-4">
          <button class="btn btn-outline-secondary"><i class="fas fa-chevron-left"></i></button>
          <span class="date-text mx-2">Monday, 15 October</span>
          <button class="btn btn-outline-secondary"><i class="fas fa-chevron-right"></i></button>
      </div>  -->
  </div>

  <div class="right-section d-flex">
      <div class="date-time-container">
          <h6><?php echo $current_date; ?></h6>
      </div>

      <!-- <button class="btn btn-outline-secondary d-flex align-items-center mr-2">
          <i class="fas fa-file-alt mr-2"></i> Attendance Report
      </button>
      <button class="btn btn-success d-flex align-items-center">
          <i class="fas fa-user-plus mr-2"></i> Add Attendance
      </button> -->
  </div> 
</div> 

<!-- Second Row Div -->
<div class="dashboard-cards d-flex flex-wrap justify-content-between">
     <div class="card order-card">
         <div class="card-block">
             <div class="header-top d-flex justify-content-between align-items-center mb-4">
                 <div class="d-flex flex-column">
                     <div class="header-top-container d-flex flex-row">
                         <h6>Total Transaction</h6>
                         <span style="color: #71A814;">+30.6% <i class="fa-solid fa-arrow-up"></i></span>
                     </div>
                     <h2 class="mt-1">436</h2>
                 </div>
             </div>
             <div class="bottom-section">
                 <div class="d-flex flex-row justify-content-between">
                     <h5>vs. last month</h5>
                     <div class="arrow-up">
                         <i class="fa-solid fa-square-arrow-up-right"></i>
                     </div>
                 </div>
             </div>
         </div>
     </div>
</div>


<div class="search-bar">
    <div class="search-input">
        <input type="text" placeholder="Search">
    </div>
    <div class="filter-group">
        <select>
            <option>Category</option>
            <option>All</option>
        </select>
        <select>
            <option>Status</option>
            <option>All</option>
        </select>
        <button class="search-button"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>
</div>



<div class="table-container">
    <div class="table-actions">
      <div class="show-column">
          <span>Show: </span>
          <select>
              <option>All Columns</option>
          </select>
      </div>

      <div class="pagination">
          <button id="prev-page" class="pagination-button" onclick="prevPage()">&#8249;</button>
          <button class="pagination-number" onclick="goToPage(1)">1</button>
          <button class="pagination-number" onclick="goToPage(2)">2</button>
          <button id="page-info" class="pagination-number active">3</button>
          <span class="pagination-ellipsis">...</span>
          <button class="pagination-number" onclick="goToPage(10)">10</button>
          <button id="next-page" class="pagination-button" onclick="nextPage()">&#8250;</button>
      </div>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <!-- <th><input type="checkbox"></th> -->
                <th>ID</th>
                <th>Guest Name</th>
                <th>Guest Email</th>
                <th>Guest Phone Number</th>
                <th>Package Name</th>
                <th>Transaction Date</th>
                <th>Flight Date</th>
                <th>Total Pax</th>
                <th>Status</th>
                <th></th>
                
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>John Doe</td>
            <td>john.doe@example.com</td>
            <td>(555) 123-4567</td>
            <td>Summer Package</td>
            <td>2024-06-01</td>
            <td>2024-06-15</td>
            <td>2</td>
            <td><span class="td-status-confirmed">Confirmed</span></td>
            <td><a class="action-button"><i class="fa-solid fa-pen-to-square"></i></a></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jane Smith</td>
            <td>jane.smith@example.com</td>
            <td>(555) 765-4321</td>
            <td>Winter Package</td>
            <td>2024-07-10</td>
            <td>2024-07-20</td>
            <td>4</td>
            <td><span class="td-status-pending">Pending</span></td>
            <td><a class="action-button"><i class="fa-solid fa-pen-to-square"></i></a></td>
        </tr>
    </tbody>
    </table>
</div>
</div>

<script>
    let currentPage = 3;  // Set to the current page
    const rowsPerPage = 5;

    const data = [
        { id: 1077620, shipify: '17713', date: '22 Jan 2020', status: 'Pending', customer: 'Ahmed', email: 'ahmed.123@mail.com', country: 'Australia', shipping: 'Australian Post API', source: 'ShopifyAU', orderType: 'Customer' },
        // Add more rows as needed
    ];

    function displayTable(page) {
        const tableContainer = document.getElementById('table-content');
        tableContainer.innerHTML = '';

        const start = (page - 1) * rowsPerPage;
        const end = page * rowsPerPage;
        const paginatedItems = data.slice(start, end);

        let tableHTML = `
            <table class="product-table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>ID</th>
                        <th>SHIPIFY #</th>
                        <th>DATE</th>
                        <th>STATUS</th>
                        <th>CUSTOMER</th>
                        <th>EMAIL</th>
                        <th>COUNTRY</th>
                        <th>SHIPPING</th>
                        <th>SOURCE</th>
                        <th>ORDER TYPE</th>
                    </tr>
                </thead>
                <tbody>
        `;

        paginatedItems.forEach(item => {
            tableHTML += `
                <tr>
                    <td><input type="checkbox"></td>
                    <td>${item.id}</td>
                    <td>${item.shipify}</td>
                    <td>${item.date}</td>
                    <td>${item.status}</td>
                    <td>${item.customer}</td>
                    <td>${item.email}</td>
                    <td>${item.country}</td>
                    <td>${item.shipping}</td>
                    <td>${item.source}</td>
                    <td>${item.orderType}</td>
                </tr>
            `;
        });

        tableHTML += '</tbody></table>';
        tableContainer.innerHTML = tableHTML;
    }

    function goToPage(page) {
        currentPage = page;
        displayTable(page);
        updatePagination();
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            displayTable(currentPage);
            updatePagination();
        }
    }

    function nextPage() {
        if (currentPage < 10) {  // Assuming 10 pages for now
            currentPage++;
            displayTable(currentPage);
            updatePagination();
        }
    }

    function updatePagination() {
        const pageNumbers = document.querySelectorAll('.pagination-number');
        pageNumbers.forEach(button => {
            button.classList.remove('active');
            if (parseInt(button.textContent) === currentPage) {
                button.classList.add('active');
            }
        });
    }

    // Initialize table display on page load
    displayTable(currentPage);
    updatePagination();
</script>



<?php require "../Agent Section/scripts/script.php"; ?>

<?php require "../Agent Section/includes/scripts.php"; ?>

</body>

</html>
