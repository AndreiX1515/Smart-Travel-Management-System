<div class="sidebar" id="sidebar">
  <div class="logo mt-3">
      <img src="..\assets\images\SMART LOGO 2 (2).png" alt="Smart Travel Logo">
  </div>

  <div class="dashboard-title">Dashboard</div>
   <a href="../Agent Section/agent-dashboard.php" class="page-button" data-page-name="Dashboard"> <i class="fas fa-home">
   </i> Home </a>
   <a href="../Agent Section/agent-addbooking.php" class="page-button add-booking" data-page-name="Add Booking"> <i class="fa-solid fa-user-plus">
   </i> Add Booking </a>

   <div class="section-title" onclick="toggleSubMenu('operational-submenu')">
      Operational <span class="chevron-icon fas fa-chevron-down"></span>
  </div>

  <div class="submenu" id="operational-submenu">
    <a href="../Agent Section/agent-transactions.php" class="page-button" data-page-name="Transactions">
      <i class="fas fa-exchange-alt"></i> Transactions
    </a>
  </div>

  <div class="section-title" onclick="toggleSubMenu('management-submenu')">
    Management <span class="chevron-icon fas fa-chevron-down"></span>
  </div>
    <div class="submenu" id="management-submenu">
      <a href="../Agent Section/agent-client-accounts.php">
        <i class="fas fa-user-friends"></i> Client Accounts
      </a>
      <a href="../Agent Section/agent-agent-accounts.php">
        <i class="fas fa-users"></i> Agent Accounts
      </a>
      <a href="../Agent Section/agent-client-login-history.php">
        <i class="fas fa-history"></i> Client Login History
      </a>
      <a href="../Agent Section/agent-agent-login-history.php">
        <i class="fas fa-history"></i> Agent Login History
      </a>
    </div>
</div>

<script>
function toggleSubMenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    const sectionTitle = submenu.previousElementSibling;
    const chevron = sectionTitle.querySelector('.chevron-icon'); 

    // Check if the submenu is already open
    const isOpen = submenu.classList.contains('open');

    // If it's open, we need to close it, and reset the chevron
    if (isOpen) {
        submenu.classList.remove('open');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        // First, close all open submenus and reset all chevrons
        const allSubmenus = document.querySelectorAll('.submenu');
        const allChevrons = document.querySelectorAll('.chevron-icon');
        
        allSubmenus.forEach(sub => {
            sub.classList.remove('open');
        });

        allChevrons.forEach(chev => {
            chev.style.transform = 'rotate(0deg)';
        });

        // Now, open the current submenu and rotate its chevron
        submenu.classList.add('open');
        chevron.style.transform = 'rotate(180deg)';
    }
}


</script>























<script>
sidebar.classList.remove('hidden');
mainContent.classList.add('active');
</script>

