<div class="sidebar" id="sidebar">
  <div class="logo mt-3">
    <img src="../Assets/Logos/logo.png" alt="Smart Travel Logo">
  </div>

  <div class="dashboard-title">Menu</div>
  
  <a href="../Agent Section/agent-dashboard copy 2.php" class="page-button home my-0 mb-1 " data-page-name="Dashboard"> 
    <i class="fas fa-home"></i> <span> Home </span> 
  </a>
   
  <a href="../Agent Section/agent-addbooking.php" class="page-button add-booking mb-1 my-0" data-page-name="Add Booking"> 
    <i class="fa-solid fa-user-plus"></i> <span> Add Booking </span>
  </a>

  <a href="../Agent Section/agent-FIT3.php" class="page-button add-FIT mb-1 my-0" data-page-name="F.I.T">
    <i class="fas fa-file-invoice"></i> <span> Add F.I.T </span>
  </a>
  
  <div class="section-title" onclick="toggleSubMenu('transactiontable-submenu')">
    Transactions <span class="chevron-icon fas fa-chevron-down"></span>
  </div>

  <!-- This submenu is open by default -->
  <div class="submenu open" id="transactiontable-submenu">
    <a href="../Agent Section/agent-transactions2.php" class="page-button my-0" data-page-name="Transactions">
      <i class="fas fa-file-invoice"></i> Packages
    </a>

    <a href="../Agent Section/agent-FIT-table2.php" class="page-button my-0" data-page-name="F.I.T - View Table" style="font-size: 14px;">
      <i class="fas fa-file-invoice"></i> F.I.T 
    </a> 
  </div>

  <div class="section-title" onclick="toggleSubMenu('operational-submenu')">
    Reports <span class="chevron-icon fas fa-chevron-down"></span>
  </div>

  <!-- Make submenu open by default by adding the 'open' class -->
  <div class="submenu open" id="operational-submenu">
    <a href="../Agent Section/agent-itenerary.php" class="page-button" data-page-name="Itinerary">
      <i class="fas fa-map"></i> Itinerary
    </a>

    <a href="../Agent Section/agent-soa2.php" class="page-button" data-page-name="Statement of Accounts (SOA)">
      <i class="fas fa-file-invoice-dollar"></i> SoA
    </a>

    <a href="../Agent Section/agent-ticket.php" class="page-button" data-page-name="Ticket">
      <i class="fas fa-ticket"></i> Ticket
    </a>

    <a href="../Agent Section/agent-transactions.php" class="page-button" data-page-name="Voucher">
      <i class="fas fa-gift"></i> Voucher
    </a>
  </div>

</div>

<!-- <style>
/* Initially, no animation on load */
.submenu {
  display: none;
  transition: none; /* Disable transition initially */
}

.submenu.open {
  display: block;
}

/* When toggling the submenu, enable the transition */
.submenu-toggle {
  transition: height 0.3s ease; /* You can adjust the timing as per your preference */
}
</style> -->

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
