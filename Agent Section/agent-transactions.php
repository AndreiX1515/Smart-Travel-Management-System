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
    <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
</head>

<body>

<?php include '../Agent Section/includes/sidebar.php' ?>

<div class="main-content" id="mainContent">

 <?php include '../Agent Section/includes/navbar.php' ?>


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

   <div class="table-container">
    <div class="table-header">
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search..." />
        </div>
        <div class="button-container">
            <button id="addButton">Add New</button>
        </div>
    </div>
    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>john@example.com</td>
                <td>(123) 456-7890</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jane Smith</td>
                <td>jane@example.com</td>
                <td>(098) 765-4321</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Bob Johnson</td>
                <td>bob@example.com</td>
                <td>(555) 123-4567</td>
            </tr>
        </tbody>
    </table>
</div>


<?php require "../Agent Section/scripts/script.php"; ?>

<?php require "../Agent Section/includes/scripts.php"; ?>

</body>

</html>
