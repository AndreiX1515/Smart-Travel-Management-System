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
    <div class="logo-container">
      <div class="logo-content">
        <div class="logo-backdrop">
          <img src="../Assets/Logos/logo-tab.png" alt="Logo" class="sidebar-logo">
        </div>
        <span class="fw-bold">SMART TRAVEL</span>
      </div>
    </div>

    <div class="main-nav-container">
      <div class="lang-user-wrapper">

        <!-- Language Dropdown -->
        <div class="nav-language-dropdown">

          <div class="nav-language-btn">
            <div class="icon"><i class="fas fa-globe"></i></div>
            <div class="label">EN</div>
            <div class="dropdown-icon">▾</div>
          </div>

          <ul class="nav-language-menu">
            <li><a href="#">English</a></li>
            <li><a href="#">한국어 (KR)</a></li>
            <li><a href="#">日本語</a></li>
            <li><a href="#">中文</a></li>
          </ul>

        </div>



        <div class="seperator"></div>

        <!-- User Dropdown -->
        <div class="nav-user-dropdown">
          <div class="nav-user-btn">
            <div class="icon">
              <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-info">
              <span class="user-name">Elaine Santoyo</span>
              <span class="user-id">E001</span>
            </div>
            <div class="dropdown-icon">
              <span>▾</span>
            </div>
          </div>

          <ul class="nav-user-menu">
            <li><a href="#">Profile</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Logout</a></li>
          </ul>
        </div>



      </div>
    </div>

  </div>

  <!-- Body Content Wrapper -->
  <div class="body-container">

    <!-- Sidebar -->
    <?php include '../Employee Section/includes/emp-sidebar copy.php'; ?>

    <div class="main-content">

      <!-- Page Header -->
      <div class="page-header">

        <div class="page-actions">
          <div class="page-header-wrapper">

            <div class="page-header-top">
              <div class="back-btn-wrapper">
                <button class="back-btn" id="redirect-btn">
                  <i class="fas fa-chevron-left"></i>
                </button> 
              </div>
            </div>

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

        <!-- Page-body (Header) -->
        <div class="page-body-header">

          <div class="header-card">

            <div class="header-card-header">

              <div class="header-left">
                <div class="header-title-wrapper">
                  <span>Active Transaction</span>
                </div>
              </div>

              <div class="header-right">
               
              </div>

            </div>

            <div class="header-card-body">
              
              <div class="tab-container">

                <!-- Top Row -->
                <div class="tab">
                  <div class="tab-icon"><i class="fas fa-users"></i></div>
                  <div class="tab-info">
                    <div class="tab-count">120</div>
                    <div class="tab-name">Users</div>
                  </div>
                </div>

                <div class="tab">
                  <div class="tab-icon"><i class="fas fa-chart-line"></i></div>
                  <div class="tab-info">
                    <div class="tab-count">75</div>
                    <div class="tab-name">Sales</div>
                  </div>
                </div>

                <!-- Bottom Row -->
                <div class="tab">
                  <div class="tab-icon"><i class="fas fa-comments"></i></div>
                  <div class="tab-info">
                    <div class="tab-count">45</div>
                    <div class="tab-name">Messages</div>
                  </div>
                </div>
                <div class="tab">
                  <div class="tab-icon"><i class="fas fa-envelope"></i></div>
                  <div class="tab-info">
                    <div class="tab-count">60</div>
                    <div class="tab-name">Emails</div>
                  </div>
                </div>
                
                <!-- Optional 5th tab -->
                <!-- <div class="tab">
                  <div class="tab-icon"><i class="fas fa-tasks"></i></div>
                  <div class="tab-info">
                    <div class="tab-count">30</div>
                    <div class="tab-name">Tasks</div>
                  </div>
                </div> -->
              </div>
            </div>

          </div>
       
          <div class="header-card"></div>

          <div class="header-card"></div>

          <div class="header-card"></div>

        </div>

        <div class="page-tabs">

          <!-- Bootstrap nav tabs -->
          <ul class="nav custom-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab">
                Flight Seat Tracker
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab">
                Booking and Requests
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings-tab-pane" type="button" role="tab">
                F.I.T
              </button>
            </li>
          </ul>

        </div>

        <div class="tab-content content-grid" id="myTabContent">

          <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel">

            <div class="panel flight-seat-panel">

              <div class="panel-header flight-tabs-header">

                <div class="tabs-wrapper">

                  <ul class="nav nav-pills" id="segmentedTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview" type="button" role="tab">
                        Cebu Pacific
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="code-tab" data-bs-toggle="tab" data-bs-target="#code" type="button" role="tab">
                        Air Asia
                      </button>
                    </li>
                  </ul>

                </div>

                <div class="actions-wrapper">
                  <button class="btn btn-sm btn-primary" hidden>Save</button>
                </div>

              </div>

              <div class="panel-body flight-tabs-body">

                <div class="tab-content tab-content-2" id="segmentedTabContent">

                  <div class="tab-pane fade show active" id="preview" role="tabpanel">
                    <p>Preview content goes here...</p>
                  </div>

                  <div class="tab-pane fade" id="code" role="tabpanel">
                    <p>Code content goes here...</p>
                  </div>

                </div>

              </div>

            </div>
          </div>

          <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel">
            <div class="panel">
              <div class="panel-header">
                Recent Activity
              </div>
              
              <div class="panel-body">
                <!-- Content grows/shrinks here -->
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="settings-tab-pane" role="tabpanel">
            <div class="panel">
              <div class="panel-header">
                Recent Activity
              </div>
              
              <div class="panel-body">
                <!-- Content grows/shrinks here -->
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>


  <script>
    document.getElementById('redirect-btn').addEventListener('click', function () {
      window.location.href = '../Employee Section/emp-dashboard.php';
    });
  </script>

</body>


</html>