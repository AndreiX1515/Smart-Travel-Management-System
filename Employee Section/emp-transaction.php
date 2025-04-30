<?php session_start(); ?>

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

  <?php
  $statusTab = isset($_GET['status']) ? $_GET['status'] : '';
  ?>

  <!-- Main Container -->
  <div class="main-container">

    <div class="navbar">
      <div class="page-header-wrapper">

        <!-- <div class="page-header-top">
          <div class="back-btn-wrapper">
            <button class="back-btn" id="redirect-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
          </div>
        </div> -->

        <div class="page-header-content">
          <div class="page-header-text">
            <h5 class="header-title">Transactions</h5>
          </div>
        </div>

      </div>
    </div>

    <div class="main-content">

      <div class="tabs-wrapper">
        <div class="navs-wrapper">
          <ul class="nav nav-pills" id="pills-tab" role="tablist">

            <!-- Status Tab -->
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill"
                data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                aria-selected="false">STATUS</button>
            </li>

            <!-- On Due Balance Tab -->
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                type="button" role="tab" aria-controls="pills-home" aria-selected="true">ON DUE</button>
            </li>

             <!-- With Remaining Balance Tab -->
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-remaining-balance-tab" data-bs-toggle="pill" data-bs-target="#pills-remaining-balance" 
                type="button" role="tab" aria-controls="pills-remaining-balance" aria-selected="false">WITH REMAINING BALANCE</button>
            </li>

          </ul>
        </div>
      </div>

      <div class="tab-content" id="pills-tabContent">

        <!-- Status Table -->
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">

           <?php 
           include '../Employee Section/transactionTable/transactionTable-Status.php'; 
           ?> 

        </div>

        <!-- On Due Table -->
        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

          <?php 
          include '../Employee Section/transactionTable/transactionTable-OnDue.php'; 
          ?>  

        </div>

        <!-- With Remaining Balance Table -->
        <div class="tab-pane fade" id="pills-remaining-balance" role="tabpanel" aria-labelledby="pills-remaining-balance-tab">

          <?php 
          include '../Employee Section/transactionTable/transactionTable-RemainingBalance.php'; 
          ?>   

        </div>       

      </div>

    </div>

  </div>
  </div>
  </div>

  <?php include '../Employee Section/includes/emp-scripts.php' ?>

  <!-- Row Click Selection-->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll("tr[data-url]").forEach(function (row) {
        row.addEventListener("click", function () {
          const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

          console.log("Transaction Number: ", transactionNumber); // Debugging line

          // Use AJAX to send the transaction number to the server
          $.ajax({
            url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
            type: 'POST',
            data: {
              transaction_number: transactionNumber
            },
            success: function (response) {
              console.log("Response: ", response); // Debugging line

              // Redirect to the next page after successfully setting the session
              window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
            },
            error: function (xhr, status, error) {
              console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
            }
          });
        });
      });
    });
  </script>

  

</body>

</html>