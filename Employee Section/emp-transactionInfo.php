

<?php 

session_start();

if (isset($_GET['id'])) {
 // Sanitize the input to prevent XSS attacks
 $transactionId = htmlspecialchars($_GET['id']);

}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-transactionInfo.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
    
</head>
<body>


<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
    <nav class="navbar navbar-expand-lg navbar-custom mt-2">
         <div class="back-button-wrapper py-3 px-4">
            <button class="back-button" onclick="window.location.href='../Employee Section/emp-transaction.php';">
                 <i class="fas fa-arrow-left"></i>
             </button>

             <div class="title">
                <h1>TRANSACTION ID: <?php echo $transactionId; ?></h1>
             </div>

            <!-- Navbar items and functionality can be added here -->
        </div>
    </nav>

   <div class="main-content">
    <div class="header">
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
             <div class="col-md-5 mb-2 me-4 d-flex flex-column gap-1">
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
        <div class="card-header px-2 py-1">
           <h6>Guest Informations</h6>
        </div>

         <div class="guest-table-wrapper ">
           <table class="guest-table table-stripped">
             <thead>
               <tr>
                 <th>ID</th>
                 <th>CONTACT NAME</th>
                 <th>BIRTHDATE</th>
                 <th>SEX</th>
                 <th>NATIONALITY</th>
                 <th>OTHER CONTACT</th>
                 <th>EMAIL</th>
                 <th>ADDRESS</th>
                 <th>PASSPORT NO.</th>
                 <th>PASSPORT EXP.</th>
                 <th>VISA STATUS</th>
               </tr>
             </thead>
             <tbody>
               <tr>
                 <td>A001-000101</td>
                 <td>John Doe</td>
                 <td>1990-05-14</td>
                 <td>Male</td>
                 <td>American</td>
                 <td>+1234567890</td>
                 <td>johndoe@email.com</td>
                 <td>123 Main St, New York, NY</td>
                 <td>P123456789</td>
                 <td>2030-04-10</td>
                 <td>
                   <span class="status status-confirmed">
                     <span class=""></span> Confirmed
                   </span>
                 </td>
               </tr>
               <tr>
                 <td>A001-000102</td>
                 <td>Jane Smith</td>
                 <td>1985-11-22</td>
                 <td>Female</td>
                 <td>Canadian</td>
                 <td>+9876543210</td>
                 <td>janesmith@email.com</td>
                 <td>456 Elm St, Toronto, ON</td>
                 <td>P987654321</td>
                 <td>2028-08-15</td>
                 <td>
                   <span class="status status-confirmed">
                     <span class=""></span> Confirmed
                   </span>
                 </td>
               </tr>
               <tr>
                 <td>A001-000103</td>
                 <td>Michael Brown</td>
                 <td>1992-07-09</td>
                 <td>Male</td>
                 <td>British</td>
                 <td>+441234567890</td>
                 <td>michaelbrown@email.com</td>
                 <td>789 Pine St, London, UK</td>
                 <td>P112233445</td>
                 <td>2027-12-31</td>
                 <td>
                   <span class="status status-confirmed">
                     <span class=""></span> Confirmed
                   </span>
                 </td>
               </tr>
               <tr>
                 <td>A001-000104</td>
                 <td>Emily Davis</td>
                 <td>1997-03-18</td>
                 <td>Female</td>
                 <td>Australian</td>
                 <td>+61412345678</td>
                 <td>emilydavis@email.com</td>
                 <td>12 Queen St, Sydney, AUS</td>
                 <td>P223344556</td>
                 <td>2026-06-20</td>
                 <td>
                   <span class="status status-confirmed">
                     <span class=""></span> Confirmed
                   </span>
                 </td>
               </tr>
               <tr>
                 <td>A001-000105</td>
                 <td>Chris Johnson</td>
                 <td>1988-01-25</td>
                 <td>Male</td>
                 <td>Filipino</td>
                 <td>+639171234567</td>
                 <td>chrisjohnson@email.com</td>
                 <td>89 Rizal St, Manila, PH</td>
                 <td>P334455667</td>
                 <td>2031-02-11</td>
                 <td>
                   <span class="status status-confirmed">
                     <span class=""></span> Confirmed
                   </span>
                 </td>
               </tr>

               <tr>
                 <td>A001-000105</td>
                 <td>Chris Johnson</td>
                 <td>1988-01-25</td>
                 <td>Male</td>
                 <td>Filipino</td>
                 <td>+639171234567</td>
                 <td>chrisjohnson@email.com</td>
                 <td>89 Rizal St, Manila, PH</td>
                 <td>P334455667</td>
                 <td>2031-02-11</td>
                 <td>
                   <span class="status status-confirmed">
                     <span class=""></span> Confirmed
                   </span>
                 </td>
               </tr>

               <tr>
                 <td>A001-000105</td>
                 <td>Chris Johnson</td>
                 <td>1988-01-25</td>
                 <td>Male</td>
                 <td>Filipino</td>
                 <td>+639171234567</td>
                 <td>chrisjohnson@email.com</td>
                 <td>89 Rizal St, Manila, PH</td>
                 <td>P334455667</td>
                 <td>2031-02-11</td>
                 <td>
                   <span class="status status-confirmed">
                     <span class=""></span> Confirmed
                   </span>
                 </td>
               </tr>

               <tr>
                 <td>A001-000105</td>
                 <td>Chris Johnson</td>
                 <td>1988-01-25</td>
                 <td>Male</td>
                 <td>Filipino</td>
                 <td>+639171234567</td>
                 <td>chrisjohnson@email.com</td>
                 <td>89 Rizal St, Manila, PH</td>
                 <td>P334455667</td>
                 <td>2031-02-11</td>
                 <td>
                   <span class="status status-confirmed">
                     <span class=""></span> Confirmed
                   </span>
                 </td>
               </tr>
             </tbody>
           </table>
         </div>

      </div>
   </div>

   <div class="nav-pills-wrapper">
      <ul class="nav nav-pills " id="pills-tab" role="tablist">
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
       <?php include '../Employee Section/emp-requestHistory.php' ?>

     
    </div>
 
  </div>
</div>

<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
