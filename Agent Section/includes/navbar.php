<?php
  require "../conn.php";

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $accountId = $_SESSION['accountId'];
  
  $agentId = $_SESSION['agentId'];
  $agentCode = $_SESSION['agentCode'];
  $agentRole = $_SESSION['agentRole'];
  $agentType = $_SESSION['agentType'];
  $fName =  $_SESSION['fName'] ?? '';
  $lName = $_SESSION['lName'] ?? '';
  $mName = $_SESSION['mName'] ?? '';
  $branchId = $_SESSION['branchId'] ?? '';
  $email = $_SESSION['email'] ?? '';
  $password = $_SESSION['password'] ?? '';

  $sql1 = "Select * from branch where branchId= '$branchId'";
  $result1 = $conn->query($sql1);

  // Check if a result is returned
  if ($result1->num_rows > 0) {
      // Fetch the branchName
      $row = $result1->fetch_assoc();
      $branchName = $row['branchName'];
  } else {
      $branchName = "No Branch";
  }

  // Format the full name
  $fullName = htmlspecialchars($lName . ', ' . $fName . ($mName ? ' ' . substr($mName, 0, 1) . '.' : ''));

  // Optional: hide password by default
  $maskedPassword = '••••••••••';
?>

<?php
  date_default_timezone_set('Asia/Taipei');
  $current_date = date('D, F d, Y'); 
?>

<header>      
  <nav class="navbar navbar-expand-lg justify-content-between sticky-top">
    <div class="container-fluid d-flex justify-content-between">
      <div class="nav-start-container d-flex flex-row">
          <a class="navbar-brand" id="page-title" style="font-weight: 500;"></a> 
      </div>

      <div class="nav-end-container d-flex flex-row align">
        <div class="date-time-container d-flex flex-row align-items-center">
            <h6><?php echo $current_date; ?></h6>
        </div>

        <div class="vertical-line-navbar"></div>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown d-flex align-items-center">

                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-container ms-2 me-3">
                        <h6 class="mb-1"><?php echo $fullName; ?></h6>
                        <span class="m-0">Branch: <?php echo $branchName; ?></span>
                        <span class="m-0">Agent ID: <?php echo $agentId; ?></span>
                    </div>
                    <img src="../Assets/Icons/circle.png" alt="Profile" class="profile-image me-2" width="40px" height="40px">
                </a>

                <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="navbarDropdown">
                  <li>
                    <a class="dropdown-item" href="#" style="font-size: 14px;" data-bs-toggle="modal" data-bs-target="#viewPasswordModal">
                      <i class="fas fa-user me-2"></i> View Password
                    </a>
                  </li>

                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" style="font-size: 14px;">
                      <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                  </li>
                </ul>

              </li>  
            </ul>
          </div>

     </div>

    </div>
  </nav>
</header>






<?php include '../Agent Section/includes/logoutViewPassModal.php'; ?>