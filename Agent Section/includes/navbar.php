<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../conn.php";
// Fetch session variables directlys
$agentId = $_SESSION['agentId'];
$email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
$fName = $_SESSION['fName'] ?? '';
$lName = $_SESSION['lName'] ?? '';
$mName = $_SESSION['mName'] ?? '';
// $accId = $_SESSION['accountid'] ?? '';
$branch = $_SESSION['branch'] ?? '';

$fullName = htmlspecialchars($lName . ', ' . $fName . ($mName ? ' ' . substr($mName, 0, 1) . '.' : ''));
?>

<header>      
  <nav class="navbar navbar-expand-lg justify-content-between sticky-top">
     <div class="container-fluid d-flex justify-content-between">
        <div class="nav-start-container d-flex flex-row">
            <!-- Toggle button for the sidebar -->
            <div class="toggle-btn" id="toggleBtn">
                <i class="fa-solid fa-bars"></i>
            </div>

            <a class="navbar-brand" id="page-title" style="font-weight: 600;">Dashboard</a> 
        </div>

          <div class="nav-end-container d-flex flex-row align">

          <div class="date-time-container d-flex flex-row align-items-center">
              <h6><?php echo $current_date; ?></h6>
          </div>

          <div class="vertical-line-navbar"></div> <!-- Vertical Line -->

           <div class="collapse navbar-collapse" id="navbarNav">
             <ul class="navbar-nav ms-auto">
               <li class="nav-item dropdown d-flex align-items-center">

                   <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                       <div class="profile-container ms-2 me-3">
                       <h6 class="m-0">Agent Id <?php echo $agentId; ?></h6>
                           <h6 class="m-0"><?php echo $fullName; ?></h6>
                           <span class="m-0"><?php echo $branch; ?></span>
                       </div>
                       <img src="../assets/images/circle.png" alt="Profile" class="profile-image me-2" width="40px" height="40px">
                   </a>

                  <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="navbarDropdown">
                     <li>
                         <a class="dropdown-item" href="#">
                             <i class="fas fa-user me-2"></i> My Profile
                         </a>
                     </li>
                     <li>
                         <a class="dropdown-item" href="#">
                             <i class="fas fa-cog me-2"></i> Settings
                         </a>
                     </li>
                     <li>
                         <hr class="dropdown-divider">
                     </li>
                     <li>
                         <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
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

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
 <div class="modal-dialog">
   <div class="modal-content">
     <div class="modal-header">
         <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
     <div class="modal-body">
         Are you sure you want to logout?
     </div>
     <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
         <a href="../Agent Section/agent-login.php" class="btn btn-danger" id="logoutButton">Logout</a>
     </div>
   </div>
 </div>
</div>