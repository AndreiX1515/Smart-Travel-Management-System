<?php
// include 'session_validate.php'; // This will check if the session is valid

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch session variables directlys
$email = $_SESSION['client_email'] ?? ''; // Use null coalescing operator to avoid undefined index
$accId = $_SESSION['client_accountId'] ?? '';

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
  <nav class="navbar">
    <div class="navbar-content">
      <!-- Logo -->
      <a href="index.php" class="navbar-brand">
        <img src="../Assets/Logos/SMART LOGO 2 (2).png" alt="Logo" width="180" height="30" class="me-2">
      </a>

    </div>
  </nav>
</header>


