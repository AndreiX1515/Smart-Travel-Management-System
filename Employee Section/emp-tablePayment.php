<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Transactions</title>
    <?php include '../Employee Section/includes/emp-head.php'?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-tableRequestPayment.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
<body>

<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
   <?php include '../Employee Section/includes/emp-navbar.php' ?>

   <div class="main-content">
      <div class="table-container">
         <!-- <div class="table-header">
           <div class="header-left">
              <div class="table-tabs">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                 <li class="nav-item" role="presentation">
                   <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Home</button>
                 </li>
                 <li class="nav-item" role="presentation">
                   <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Profile</button>
                 </li>
                 <li class="nav-item" role="presentation">
                   <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Contact</button>
                 </li>
                 <li class="nav-item" role="presentation">
                   <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false" disabled>Disabled</button>
                 </li>
               </ul>
            </div> -->
       

     <div class="table-subheader d-flex align-items-center justify-content-between p-3 border-bottom bg-light">
        <!-- Search -->
        <div class="search-wrapper position-relative">
           <input
             type="text"
             placeholder="Search..."
             class="form-control search-input"
             oninput="toggleClearButton(this)"
           />
           <button 
             type="button"
             class="clear-button"
             onclick="clearInput(this)"
             style="display: none;"
           >
             <i class="fas fa-times"></i>
           </button>
         </div>

        <!-- Dropdowns -->
        <div class="dropdowns d-flex align-items-center gap-3">
          <!-- Items per Page Dropdown -->
          <div class="dropdown">
            <button
              class="btn btn-outline-secondary dropdown-toggle"
              type="button"
              id="itemsPerPageDropdown"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              Items per Page
            </button>
            <ul class="dropdown-menu" aria-labelledby="itemsPerPageDropdown">
              <li><a class="dropdown-item" href="#">5</a></li>
              <li><a class="dropdown-item" href="#">10</a></li>
              <li><a class="dropdown-item" href="#">50</a></li>
              <li><a class="dropdown-item" href="#">100</a></li>
            </ul>
          </div>

          <!-- Date Range Dropdown -->
          <div class="dropdown">
            <button
              class="btn btn-outline-secondary dropdown-toggle"
              type="button"
              id="dateRangeDropdown"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              Date Range
            </button>
            <ul class="dropdown-menu" aria-labelledby="dateRangeDropdown">
              <li><a class="dropdown-item" href="#">Today</a></li>
              <li><a class="dropdown-item" href="#">This Week</a></li>
              <li><a class="dropdown-item" href="#">This Month</a></li>
              <li><a class="dropdown-item" href="#">Custom Range</a></li>
            </ul>
          </div>

          <!-- Filter Options Dropdown -->
          <div class="dropdown">
            <button
              class="btn btn-outline-secondary dropdown-toggle"
              type="button"
              id="filterDropdown"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              Filter Options
            </button>
            <ul class="dropdown-menu" aria-labelledby="filterDropdown">
              <li><a class="dropdown-item" href="#">Status</a></li>
              <li><a class="dropdown-item" href="#">Category</a></li>
              <li><a class="dropdown-item" href="#">Priority</a></li>
              <li><a class="dropdown-item" href="#">Custom Filter</a></li>
            </ul>
          </div>

          <!-- Export Options Dropdown -->
          <div class="dropdown">
            <button
              class="btn btn-outline-secondary dropdown-toggle"
              type="button"
              id="exportDropdown"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              Export
            </button>
            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
              <li><a class="dropdown-item" href="#">Export as CSV</a></li>
              <li><a class="dropdown-item" href="#">Export as Excel</a></li>
              <li><a class="dropdown-item" href="#">Export as PDF</a></li>
            </ul>
          </div>

          <div class="clear-button-wrapper">
           <button class="btn btn-danger">
              <i class="fa-solid fa-circle-xmark"></i>
           </button>
          </div>
        </div>
      </div>

      <script>
 
       function toggleClearButton(input) {
         const clearButton = input.nextElementSibling; // Get the button next to the input
         clearButton.style.display = input.value ? "block" : "none";
       }

       // Clear the input field
       function clearInput(button) {
         const input = button.previousElementSibling; // Get the input field before the button
         input.value = "";
         button.style.display = "none"; // Hide the clear button
         input.focus(); // Refocus on the input
       }

      </script>



      <div class="table-wrapper">
        <table class="">
          <thead>
            <tr>
              <th>Transact No</th>
              <th>Package Name</th>
              <th>Flight Date</th>
              <th>Booking Date</th>
              <th>Total Pax</th>
              <th>Package Price</th>
              <th>Request Cost</th>
              <th>Amount to be paid</th>
              <th>Amount Paid</th>
              <th>Remaining Balance</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
           
          </tbody>

      </table>
     </div>

   </div>
  </div>
</div>


<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
