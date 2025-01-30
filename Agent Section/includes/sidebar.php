<div class="sidebar" id="sidebar">
  <div class="logo mt-3">
    <img src="../Assets/Logos/logo.png" alt="Smart Travel Logo">
  </div>

  <div class="dashboard-title">Menu</div>

  <!-- Home link -->
  <a href="../Agent Section/agent-dashboard.php" class="page-button home my-0 mb-1" data-page-name="Dashboard">
    <i class="fas fa-home"></i> <span>Home</span>
  </a>

  <!-- Add Booking link -->
  <a href="../Agent Section/agent-addbooking2.php" class="page-button add-booking mb-1 my-0" data-page-name="Add Booking">
    <i class="fa-solid fa-user-plus"></i> <span>Add Booking</span>
  </a>

  <!-- Add F.I.T link -->
  <a href="../Agent Section/agent-FIT.php" class="page-button add-FIT mb-1 my-0" data-page-name="F.I.T">
    <i class="fas fa-file-invoice"></i> <span>Add F.I.T</span>
  </a>

  <!-- Transactions Section -->
  <div class="section-title" onclick="toggleSubMenu('transactiontable-submenu')">
    Transactions <span class="chevron-icon fas fa-chevron-down"></span>
  </div>

  <!-- Transactions Submenu -->
  <div class="submenu open" id="transactiontable-submenu">
    <a href="../Agent Section/agent-transactions.php" class="page-button my-0" data-page-name="Transactions">
      <i class="fas fa-file-invoice"></i> <span>Packages</span>
    </a>
    <a href="../Agent Section/agent-FIT-table.php" class="page-button my-0" data-page-name="F.I.T - View Table" style="font-size: 14px;">
      <i class="fas fa-file-invoice"></i> <span>F.I.T</span>
    </a>
  </div>

  <!-- Reports Section -->
  <div class="section-title" onclick="toggleSubMenu('operational-submenu')">
    Reports <span class="chevron-icon fas fa-chevron-down"></span>
  </div>

  <!-- Reports Submenu -->
  <div class="submenu open" id="operational-submenu">
    <a href="../Agent Section/agent-itenerary.php" class="page-button" data-page-name="Itinerary">
      <i class="fas fa-map"></i> <span>Itinerary</span>
    </a>
    <a href="../Agent Section/agent-soa.php" class="page-button" data-page-name="Statement of Accounts (SOA)">
      <i class="fas fa-file-invoice-dollar"></i> <span>SoA</span>
    </a>
    <a href="../Agent Section/agent-soaFIT.php" class="page-button" data-page-name="Statement of Accounts (SOA)">
      <i class="fas fa-file-invoice-dollar"></i> <span>FIT SoA</span>
    </a>
    <!-- <a href="../Agent Section/agent-ticket.php" class="page-button" data-page-name="Ticket">
      <i class="fas fa-ticket"></i> <span>Ticket</span>
    </a>
    <a href="../Agent Section/agent-transactions.php" class="page-button" data-page-name="Voucher">
      <i class="fas fa-gift"></i> <span>Voucher</span>
    </a> -->
  </div>
</div>

<script>
function toggleSubMenu(submenuId) {
  const submenu = document.getElementById(submenuId);
  const sectionTitle = submenu.previousElementSibling;
  const chevron = sectionTitle.querySelector('.chevron-icon');

  const isOpen = submenu.classList.contains('open');

  if (isOpen) {
    submenu.classList.remove('open');
    chevron.style.transform = 'rotate(0deg)';
  } else {
    submenu.classList.add('open');
    chevron.style.transform = 'rotate(180deg)';
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const transactionSubmenu = document.getElementById('transactiontable-submenu');
  const transactionChevron = transactionSubmenu.previousElementSibling.querySelector('.chevron-icon');

  transactionSubmenu.classList.add('open');
  transactionChevron.style.transform = 'rotate(180deg)';
});
</script>
