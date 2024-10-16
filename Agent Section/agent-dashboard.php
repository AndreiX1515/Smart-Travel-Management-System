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

        <div class="right-section d-flex">
            <div class="date-time-container">
                <h6><?php echo $current_date; ?></h6>
            </div>
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

    <!-- Repeat the above block for the remaining cards -->
    <!-- Card 2 -->
    <div class="card order-card">
        <div class="card-block">
            <div class="header-top d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex flex-column">
                    <div class="header-top-container d-flex flex-row">
                        <h6>Total Transaction</h6>
                        <span style="color: #FB3E3E;">-50.6% <i class="fa-solid fa-arrow-down"></i></span>
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

    <!-- Add other cards similarly -->
    <!-- Card 3 -->
    <div class="card order-card">
        <div class="card-block">
            <div class="header-top d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex flex-column">
                    <div class="header-top-container d-flex flex-row">
                        <h6>Total Transaction</h6>
                        <span style="color: #FB3E3E;">-50.6% <i class="fa-solid fa-arrow-down"></i></span>
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

    <!-- Card 4 -->
    <div class="card order-card">
        <div class="card-block">
            <div class="header-top d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex flex-column">
                    <div class="header-top-container d-flex flex-row">
                        <h6>Total Transaction</h6>
                        <span style="color: #FB3E3E;">-50.6% <i class="fa-solid fa-arrow-down"></i></span>
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

    <!-- Card 5 -->
    <div class="card order-card">
        <div class="card-block">
            <div class="header-top d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex flex-column">
                    <div class="header-top-container d-flex flex-row">
                        <h6>Total Transaction</h6>
                        <span style="color: #FB3E3E;">-50.6% <i class="fa-solid fa-arrow-down"></i></span>
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


    <div class="second-row-container">
      <div class="one">
         <div class="header d-flex flex-row justify-content-between align-items-center">
             <h5>Bookings</h5>
             <div class="view-booking-container d-flex flex-row">
                <span> <a class="btn">View All Bookings <i class="fa-solid fa-arrow-right ms-2"></i></a> </span>
            </div>
         </div>

         <!-- <div class="horizontal-line"></div> -->


         <div class="body mt-4">
             <table>
                 <thead>
                     <tr>
                         <th>Booking ID</th>
                         <th>Client Name</th>
                         <th>Date</th>
                         <th>Status</th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td>001</td>
                         <td>John Doe</td>
                         <td>2024-10-01</td>
                         <td>Confirmed</td>
                     </tr>
                     <tr>
                         <td>002</td>
                         <td>Jane Smith</td>
                         <td>2024-10-02</td>
                         <td>Pending</td>
                     </tr>
                     <tr>
                         <td>003</td>
                         <td>Michael Brown</td>
                         <td>2024-10-03</td>
                         <td>Cancelled</td>
                     </tr>
                     <tr>
                         <td>004</td>
                         <td>Emily Davis</td>
                         <td>2024-10-04</td>
                         <td>Confirmed</td>
                     </tr>
                 </tbody>
             </table>
         </div>
         <div class="footer">
             
         </div>
     </div>

     <div class="two">
      <div class="header">

      </div>

      <div class="body">
          <div class="pie-chart">
              <div class="slice slice-1"></div>
              <div class="slice slice-2"></div>
              <div class="slice slice-3"></div>
              <div class="slice slice-4"></div>
              <div class="center-circle"></div>
          </div>
       </div>




   </div>
  </div>







</div>

</div>


<?php require "../Agent Section/scripts/script.php"; ?>

<?php require "../Agent Section/includes/scripts.php"; ?>

</body>

</html>
