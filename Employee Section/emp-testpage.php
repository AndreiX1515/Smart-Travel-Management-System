<?php
session_start();
require "../conn.php"; // Move up to the parent directory

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>Employee - Dashboard</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>

  <!-- <link rel="stylesheet" href="../Employee Section/assets/css/emp-dashboard.css?v=<?php echo time(); ?>"> -->
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar copy.css?v=<?php echo time(); ?>">
</head>

<body>

  <!-- Navbar (Always on top) -->
  <div class="navbar">
    
  </div>

  <!-- Body Content Wrapper -->
  <div class="body-container">

    <!-- Sidebar -->
    <?php include '../Employee Section/includes/emp-sidebar.php'; ?>

    <div class="main-content">

      <!-- Page Header -->
      <div class="page-header">

        <div class="page-actions">
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
                <h5 class="header-title">Dashboard</h5>
              </div>
            </div>
          </div>
        </div>

        <!-- <h2 class="page-title">Page Title</h2> -->
      </div>

      <!-- Page Body -->
      <div class="page-body">

        <!-- Stats / Summary Row -->
        <div class="stats-row">
          <div class="stat-card">Total Users</div>
          <div class="stat-card">Revenue</div>
          <div class="stat-card">Active Sessions</div>
        </div>

        <!-- Main Content Grid -->
        <div class="content-grid">

          <!-- Left Panel -->
          <div class="panel panel-left">
            <div class="panel-header">Recent Activity</div>
            <div class="panel-body">
              <!-- Example list -->
              <ul>
                <li>User A logged in</li>
                <li>Order #123 completed</li>
                <li>Server uptime: 99.9%</li>
              </ul>
            </div>
          </div>

          <!-- Right Panel -->
          <div class="panel panel-right">
            <div class="panel-header">Sales Overview</div>
            <div class="panel-body">
              <!-- Placeholder for chart -->
              <div class="chart-placeholder">[Chart goes here]</div>
            </div>
          </div>

        </div>

      </div>

      <!-- Page Footer
      <div class="page-footer">
        <span>© 2025 Your Company</span>
      </div> -->

    </div>


  </div>

  <script>
    document.getElementById('redirect-btn').addEventListener('click', function () {
      window.location.href = '../Employee Section/emp-dashboard.php';
    });
  </script>

</body>


</html>