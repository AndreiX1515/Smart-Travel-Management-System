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
    <title>Employee - Dashboard</title>
    <?php include '../SuperAdmin/includes/admin-head.php'?>
    <link rel="stylesheet" href="../SuperAdmin/assets/css/admin-dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../SuperAdmin/assets/css/admin-sidebar-navbar.css?v=<?php echo time(); ?>">
   
</head>
<body>

<?php include '../SuperAdmin/includes/admin-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container bg-body">
  <?php include '../SuperAdmin/includes/admin-navbar.php' ?>

  <div class="main-content">
    

  </div>
</div>




 </body>
</html>
