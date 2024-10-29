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
    <title>Add Booking</title>
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
        <h2 class="info-title">Add Booking</h2>
    </div>

    <div class="right-section d-flex">
      <div class="date-time-container d-flex flex-row align-items-center">
          <h6><?php echo $current_date; ?></h6>
          <i class="fa-solid fa-calendar-days"></i>
      </div>
  </div>
</div>



</div>

<?php require "../Agent Section/scripts/script.php"; ?>
<?php require "../Agent Section/includes/scripts.php"; ?>



</body>

</html>
