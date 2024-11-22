<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar with Navbar and Profile Dropdown</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-transactionInfo.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
    
</head>
<body>


<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
   <?php include '../Employee Section/includes/emp-navbar.php' ?>

   <div class="main-content">
    <div class="header">
     <div class="back-button-wrapper">
         <button class="back-button" onclick="window.location.href='../Employee Section/emp-transaction.php';">
             <i class="fas fa-arrow-left"></i>
         </button>

         <div class="title">
            <h1>TRANSACTION ID: A002-5485478</h1>
         </div>
     </div>
     
     <div class="status-wrapper">
         <!-- <span class="status">Pending</span>
         <span class="date">March, 2024</span>
         <button class="more-options">
             <i class="fas fa-ellipsis-h"></i>
         </button> -->
     </div>
   </div>

   <div class="first-part-wrapper mt-1">
      <div class="transaction-info-wrapper">
        <div class="card-header py-2 mb-2">
           <h6>Transaction Information</h6>

        </div>


        <div class="row g-3 mb-1">
             <div class="col-md-5 mb-2 d-flex flex-column gap-1">
                 <p><strong>Transaction No:</strong> 12345678</p>
                 <p><strong>Total Pax:</strong> 4</p>
                 <p><strong>Package:</strong> Premium Package</p>
                 <p><strong>Flight Date:</strong> March 2024</p>
                 <p class="align-items-center">
                    <strong>Status:</strong> 
                    <span class="badge rounded-pill bg-warning text-dark fs-7 pt-2" style="padding: 0.3rem 0.6rem; display: inline-block;">Pending</span>
                </p>
             </div>

             <div class="col-md-5 mb-3 d-flex flex-column gap-1">
                 <p><strong>Contact Person:</strong> John Doe</p>
                 <p><strong>Contact No:</strong> +1234567890</p>
                 <p><strong>Email:</strong> johndoe@example.com</p>
             </div>
         </div>

      </div>

      <div class="guest-info-table-wrapper">
        <div class="card-header py-2 mb-2">
           <h6>Guest Informations</h6>

        </div>





      </div>
   </div>

   <div class="nav-pills-wrapper">
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Request History</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Payment History</button>
        </li>
        <!-- <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</button>
        </li> -->
        <!-- <li class="nav-item" role="presentation">
          <button class="nav-link" id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false" disabled>Disabled</button>
        </li> -->
      </ul>
   </div>



   
   <div class="tab-content" id="pills-tabContent">
     <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">


     </div>

     <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">



     </div>


     <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">




     </div>

     <div class="tab-pane fade" id="pills-disabled" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">




     </div>
   </div>
   
  </div>
</div>

<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
