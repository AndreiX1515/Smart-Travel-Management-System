<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Transaction</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-transaction.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
    
</head>
<body>


<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
   <?php include '../Employee Section/includes/emp-navbar.php' ?>
   
  <div class="main-content">
   <div class="table-container">
     <!-- <div class="table-tabs">
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

      <div class="table-header">
       <div class="header-left">
         <input
           type="text"
           placeholder="Search..."
           class="search-input"
         />
         <button class="btn-search">
           <i class="fas fa-search"></i>
         </button>
       </div>


    <!-- <div class="header-right">
      
    </div>  -->
    
  </div>

    <div class="table-subheader ">
      <div class="d-flex justify-content-between align-items-center">
          <!-- Dropdown Button -->
          <div class="dropdown">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  All
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="#">5</a></li>
                <li><a class="dropdown-item" href="#">10</a></li>
                <li><a class="dropdown-item" href="#">50</a></li>
                <li><a class="dropdown-item" href="#">100</a></li>
              </ul>
          </div>

          <!-- Date Range, Filter, Add New Column Buttons -->
          <div>
              <button class="btn btn-outline-secondary me-2">
                  <i class="fas fa-calendar-alt"></i> Date Range
              </button>
              <button class="btn btn-outline-secondary me-2">
                  <i class="fas fa-filter"></i> Filter
              </button>
              <!-- <button class="btn btn-outline-secondary">
                  <i class="fas fa-plus"></i> Add new column
              </button> -->
          </div>
      </div>
  </div>

   <div class="table-wrapper">
     <table class="">
       <thead>
         <tr>
           <th>ID</th>
           <th>Contact Person Name</th>
           <th>Contact Person Email</th>
           <th>Contact Person Phone Number</th>
           <th>Package Name</th>
           <th>Booking Date</th>
           <th>Flight Date</th>
           <th>Total Pax</th>
           <th>Status</th>
         </tr>
       </thead>
       <tbody>

         <tr data-href="emp-transactionInfo.php?id=A002-000015">
           <td>A002-000015</td>
           <td>Martinez, David D.</td>
           <td>davidmartinez@email.com</td>
           <td>+639201234571</td>
           <td>Autumn Tour Package</td>
           <td>2024-11-24 00:00:00</td>
           <td>December 21, 2024</td>
           <td>2</td>
           <td>
             <span class="status status-confirmed">
               <span class="status-dot"></span> Confirmed
             </span>
           </td>
         </tr>


         <tr data-href="emp-transactionInfo.php?id=A002-000015">
           <td>A002-000014</td>
           <td>Davis, Emily C. III</td>
           <td>emilydavis@email.com</td>
           <td>+639191234570</td>
           <td>Cherry Blossom Tour Package</td>
           <td>2024-11-23 00:00:00</td>
           <td>December 14, 2024</td>
           <td>1</td>
           <td>
             <span class="status status-confirmed">
               <span class="status-dot"></span> Confirmed
             </span>
           </td>
         </tr>

         <tr data-href="emp-transactionInfo.php?id=A002-000015">
           <td>A002-000014</td>
           <td>Davis, Emily C. III</td>
           <td>emilydavis@email.com</td>
           <td>+639191234570</td>
           <td>Cherry Blossom Tour Package</td>
           <td>2024-11-23 00:00:00</td>
           <td>December 14, 2024</td>
           <td>1</td>
           <td>
             <span class="status status-confirmed">
               <span class="status-dot"></span> Confirmed
             </span>
           </td>
         </tr>

       </tbody>
     </table>
   </div>
</div>

<!-- <div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">...</div>
  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">...</div>
  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">...</div>
  <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">...</div>
</div> -->

   </div>
</div>


<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
