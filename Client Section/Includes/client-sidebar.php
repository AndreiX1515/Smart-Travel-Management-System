<div class="sidebar">
  <ul>
    <li><a href="client-portal.php" class="sidebar-link">Profile</a></li>
    <li><a href="client-transactionHistoryy.php" class="sidebar-link">Transaction History</a></li>
    <li><a href="client-support.php" class="sidebar-link">Support</a></li>
    <li><a href="client-settings.php" class="sidebar-link">Settings</a></li>
  </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Get the current page's URL (or just the pathname)
  const currentPage = window.location.pathname;

  // Get all sidebar links
  const sidebarLinks = document.querySelectorAll('.sidebar-link');

  // Loop through all sidebar links and add 'active' class if the link matches the current page URL
  sidebarLinks.forEach(link => {
    // If the href matches the current page, add 'active' class
    if (link.href.includes(currentPage)) {
      link.classList.add('active');
    }
  });
});




</script>