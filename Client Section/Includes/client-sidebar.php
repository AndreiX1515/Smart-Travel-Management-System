<?php
  require "../conn.php";

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $accountId = $_SESSION['client_accountId'];
  $agentId = $_SESSION['clientId'];
  $agentCode = $_SESSION['clientCode'];
  $agentRole = $_SESSION['clientRole'];
  $agentType = $_SESSION['clientType'];
  $fName =  $_SESSION['client_fName'] ?? '';
  $lName = $_SESSION['client_lName'] ?? '';
  $mName = $_SESSION['client_mName'] ?? '';
  $branchId = $_SESSION['client_branchId'] ?? '';
  $email = $_SESSION['client_email'] ?? '';
  $password = $_SESSION['client_password'] ?? '';

  // Fetch Branch Name
  $sql1 = "SELECT branchName FROM branch WHERE branchId = ?";
  $stmt1 = $conn->prepare($sql1);
  $stmt1->bind_param("i", $branchId);
  $stmt1->execute();
  $result1 = $stmt1->get_result();

  if ($result1->num_rows > 0) 
  {
    $row = $result1->fetch_assoc();
    $branchName = $row['branchName'];
  } 
  else 
  {
    $branchName = "No Branch";
  }
  $stmt1->close();

  // Fetch Agent Info (to get companyId)
  $sql2 = "SELECT companyId FROM client WHERE accountId = ?";
  $stmt2 = $conn->prepare($sql2);
  $stmt2->bind_param("i", $accountId);
  $stmt2->execute();
  $result2 = $stmt2->get_result();

  if ($result2->num_rows > 0) 
  {
    $row2 = $result2->fetch_assoc();
    $companyId = $row2['companyId'];

    // Fetch Company Name if companyId is NOT NULL
    if (!is_null($companyId)) 
    {
      $sql3 = "SELECT companyName FROM company WHERE companyId = ?";
      $stmt3 = $conn->prepare($sql3);
      $stmt3->bind_param("i", $companyId);
      $stmt3->execute();
      $result3 = $stmt3->get_result();

      if ($result3->num_rows > 0) 
      {
        $row3 = $result3->fetch_assoc();
        $companyName = $row3['companyName'];
      } 
      else 
      {
        $companyName = "Unknown Company"; // Fallback if no company record found
      }
      $stmt3->close();
    } 
    else 
    {
      $companyName = null; // No company assigned
    }
  } 
  else 
  {
    // Only set "No Branch" if branchName is still empty
    if (empty($branchName)) 
    {
      $branchName = "No Branch";
    }
  }
  $stmt2->close();

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
    
    <a href="../Client Section/client-dashboard.php" class="page-button home my-0 mb-1 " data-page-name="Dashboard"> 
      <i class="fas fa-home"></i> <span> Home </span> 
    </a>
   
    <!-- <a href="../Agent Section/agent-revisedAddbooking.php" class="page-button add-booking mb-1 my-0" data-page-name="Add Booking - Packages"> 
      <i class="fa-solid fa-user-plus"></i> <span> Add Booking </span>
    </a> -->

    <!-- <a href="../Agent Section/agent-FIT.php" class="page-button add-FIT mb-1 my-0" data-page-name="Add Booking - F.I.T">
      <i class="fa-solid fa-user-plus"></i> <span> Add F.I.T </span>
    </a> -->
  
    <div class="section-title" onclick="toggleSubMenu('transactiontable-submenu')">
      Transactions <span class="chevron-icon fas fa-chevron-down"></span>
    </div>

    <div class="submenu open" id="transactiontable-submenu">
      <a href="../Client Section/client-transactions.php" class="page-button my-0" data-page-name="Packages - Transactions table">
        <i class="fas fa-file-invoice"></i> Packages
      </a>

      <!-- <a href="../Agent Section/agent-FIT-table.php" class="page-button my-0" data-page-name="F.I.T - Transactions Table" style="font-size: 14px;">
        <i class="fas fa-file-invoice"></i> F.I.T 
      </a>  -->
    </div>


    <!-- <div class="section-title" onclick="toggleSubMenu('operational-submenu')">
      Reports <span class="chevron-icon fas fa-chevron-down"></span>
    </div>

    <div class="submenu open" id="operational-submenu">
      <a href="../Agent Section/agent-itenerary.php" class="page-button" data-page-name="Itinerary">
        <i class="fas fa-map"></i> Itinerary
      </a>

      <a href="../Agent Section/agent-soa2.php" class="page-button" data-page-name="Statement of Accounts (SOA) - Packages">
        <i class="fas fa-file-invoice-dollar"></i> SOA - Packages
      </a>

      <a href="../Agent Section/agent-fitSOA - rename.php" class="page-button" data-page-name="Statement of Accounts (SOA) - F.I.T">
        <i class="fas fa-file-invoice-dollar"></i> SOA - F.I.T
      </a>

      <a href="../Agent Section/agent-ticket.php" class="page-button" data-page-name="Ticket">
        <i class="fas fa-ticket"></i> Ticket
      </a>

      <a href="../Agent Section/agent-transactions.php" class="page-button" data-page-name="Voucher">
        <i class="fas fa-gift"></i> Voucher
      </a>
    </div> -->

  </div>

  <div class="profile-wrapper">
    <!-- Profile Section -->
    <div class="profile-section">
      <div class="profile-icon">
        <i class="fas fa-user-circle"></i>
      </div>
      <div class="profile-details">
        <h6 class="profile-name"><?php echo $fullName; ?></h>
        <p class="profile-role mt-1"> 
          <span><?php echo htmlspecialchars(!empty($companyName) ? $companyName : $branchName);  ?> </span>
        </p>
      </div>
    </div>

    <!-- ../Agent Section/logout.php -->
    <div class="logout-wrapper">
      <a href="#" class="page-button logout" data-page-name="" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
      </a>
    </div>

  </div>
</div>


<?php 
include '../Client Section/Includes/logoutViewPassModal.php'; 
?>

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

<!-- <script>
  document.addEventListener('DOMContentLoaded', () => {
    // Check if there's a saved title in local storage
    const savedTitle = localStorage.getItem('pageTitle');
    if (savedTitle) {
        document.getElementById('page-title').textContent = savedTitle;
    }

    const buttons = document.querySelectorAll('.page-button');
    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const newPageName = button.getAttribute('data-page-name');
            document.getElementById('page-title').textContent = newPageName;

            // Save the title to local storage
            localStorage.setItem('pageTitle', newPageName);

            const newUrl = button.getAttribute('href');
            setTimeout(() => {
                window.location.href = newUrl;
            }, 25);
        });
    });
  });
</script> -->