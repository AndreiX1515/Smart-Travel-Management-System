<?php
// include 'session_validate.php'; // This will check if the session is valid

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch session variables directlys
$email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
$accId = $_SESSION['accountId'] ?? '';

$sql1 = "SELECT * FROM Agent WHERE accId = $accId";
if ($accId !== '') 
{
  // Use a prepared statement to safely query the database
  $stmt = $conn->prepare("SELECT agentCode, agentId, agentRole FROM agent WHERE accountId = ?");
  $stmt->bind_param("i", $accId); // Bind the accountId parameter to the query
  $stmt->execute();
  $result = $stmt->get_result(); // Get the result of the query

  // Check if the query returns any rows
  if ($result->num_rows > 0) 
  {
    // Fetch the result as an associative array
    while ($row = $result->fetch_assoc()) 
    {
      $_SESSION['agentCode'] = $row['agentCode'];
      $_SESSION['agentId'] = $row['agentId'];
      $_SESSION['agentRole'] = $row['agentRole'];
      $_SESSION['agent_agentType'] = $row['agentType'];
    }
  } 
  else 
  {
    echo "No agent found with the given account ID.";
  }

  // Close the statement
  $stmt->close();
} 
else 
{
  echo "Account ID is missing.";
}
?>

<header>
  <nav class="navbar navbar-expand-lg justify-content-between sticky-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <!-- Logo -->
      <a href="index.php" class="navbar-brand">
        <img src="../Assets/Logos/SMART LOGO 2 (2).png" alt="Logo" width="180" height="30" class="me-2">
      </a>

      <!-- Nav Links in the Middle -->
      <div class="navbar-middle">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link px-3" href="index.php">Home</a>
          </li>

          <li class="nav-item">
            <a class="nav-link px-3" href="client-bookingform.php">Booking</a>
          </li>
          
        </ul>
      </div>

      <!-- Profile and End Section -->
      <div class="nav-end-container">
        <!-- Collapsible Navbar Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>

      <!-- Collapsible Navbar Content -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link px-3" href="index.php">Home</a>
          </li>

          <li class="nav-item">
            <a class="nav-link px-3" href="client-bookingform.php">Booking</a>
          </li>
          
        </ul>
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
         <a href="../Client Section/Functions/client-logout.php" class="btn btn-danger" id="logoutButton">Logout</a>
     </div>
   </div>
 </div>
</div>