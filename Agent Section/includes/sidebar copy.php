<?php
  require "../conn.php";

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $accountId = $_SESSION['agent_accountId'];

  



  $agentId = $_SESSION['agent_agentId'];
  $agentCode = $_SESSION['agent_agentCode'];
  $agentRole = $_SESSION['agent_agentRole'];
  $agentType = $_SESSION['agent_agentType'];
  $fName =  $_SESSION['agent_fName'] ?? '';
  $lName = $_SESSION['agent_lName'] ?? '';
  $mName = $_SESSION['agent_mName'] ?? '';
  $branchId = $_SESSION['agent_branchId'] ?? '';
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

<div class="sidebar" id="sidebar">
  <div class="main-sidebar">
    <div class="logo mt-3">
      <img src="../Assets/Logos/logo.png" alt="Smart Travel Logo">
    </div>

    <div class="dashboard-title">Menu</div>
    
    <a href="../Agent Section/agent-dashboard copy 2.php" class="page-button home my-0 mb-1 " data-page-name="Dashboard"> 
      <i class="fas fa-home"></i> <span> Home </span> 
    </a>
   
    <a href="../Agent Section/agent-addbooking - rename.php" class="page-button add-booking mb-1 my-0" data-page-name="Add Booking"> 
      <i class="fa-solid fa-user-plus"></i> <span> Add Booking </span>
    </a>

    <a href="../Agent Section/agent-FIT - rename.php" class="page-button add-FIT mb-1 my-0" data-page-name="Add F.I.T Booking">
      <i class="fa-solid fa-user-plus"></i> <span> Add F.I.T </span>
    </a>
  
    <div class="section-title" onclick="toggleSubMenu('transactiontable-submenu')">
      Transactions <span class="chevron-icon fas fa-chevron-down"></span>
    </div>

    <div class="submenu open" id="transactiontable-submenu">
      <a href="../Agent Section/agent-transactions - rename.php" class="page-button my-0" data-page-name="Transactions">
        <i class="fas fa-file-invoice"></i> Packages
      </a>

      <a href="../Agent Section/agent-FIT-table - rename.php" class="page-button my-0" data-page-name="F.I.T - View Table" style="font-size: 14px;">
        <i class="fas fa-file-invoice"></i> F.I.T 
      </a> 
    </div>

    <div class="section-title" onclick="toggleSubMenu('operational-submenu')">
      Reports <span class="chevron-icon fas fa-chevron-down"></span>
    </div>

    <div class="submenu open" id="operational-submenu">
      <!-- <a href="../Agent Section/agent-itenerary.php" class="page-button" data-page-name="Itinerary">
        <i class="fas fa-map"></i> Itinerary
      </a> -->

      <a href="../Agent Section/agent-soa2.php" class="page-button" data-page-name="Statement of Accounts (SOA) - Packages">
        <i class="fas fa-file-invoice-dollar"></i> SoA - Packages
      </a>

      <a href="../Agent Section/agent-fitSOA - rename.php" class="page-button" data-page-name="Statement of Accounts (SOA) - F.I.T">
        <i class="fas fa-file-invoice-dollar"></i> SoA - F.I.T
      </a>

      <!-- <a href="../Agent Section/agent-ticket.php" class="page-button" data-page-name="Ticket">
        <i class="fas fa-ticket"></i> Ticket
      </a>

      <a href="../Agent Section/agent-transactions.php" class="page-button" data-page-name="Voucher">
        <i class="fas fa-gift"></i> Voucher
      </a> -->
    </div>
  </div>

  <div class="profile-wrapper">
    <!-- Profile Section -->
    <div class="profile-section">
      <div class="profile-icon">
        <i class="fas fa-user-circle"></i>
      </div>
      <div class="profile-details">
        <h6 class="profile-name"><?php echo $fullName; ?></h>
        <p class="profile-role mt-1"> <span><?php echo $branchName; ?> </span></p>
      </div>
    </div>

    <div class="logout-wrapper">
      <a href="../Agent Section/logout.php" class="page-button logout" data-page-name="Logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
      </a>
    </div>

  </div>
</div>

<?php include '../Agent Section/includes/logoutViewPassModal.php'; ?>

<script>
function toggleSubMenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    const sectionTitle = submenu.previousElementSibling;
    const chevron = sectionTitle.querySelector('.chevron-icon'); 

    // Check if the submenu is already open
    const isOpen = submenu.classList.contains('open');

    // Toggle the submenu: If it's open, close it; If it's closed, open it
    if (isOpen) {
        submenu.classList.remove('open');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        submenu.classList.add('open');
        chevron.style.transform = 'rotate(180deg)';
    }
}

// Optionally: Automatically open the submenu when the page loads (Transaction submenu is open by default in this case)
document.addEventListener('DOMContentLoaded', function () {
    const transactionSubmenu = document.getElementById('transactiontable-submenu');
    const transactionChevron = document.querySelector('#transactiontable-submenu').previousElementSibling.querySelector('.chevron-icon');

    // Set the default opened submenu (Transaction)
    transactionSubmenu.classList.add('open');
    transactionChevron.style.transform = 'rotate(180deg)';
});
</script>
