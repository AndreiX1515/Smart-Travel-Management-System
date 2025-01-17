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

<div class="navbar">
  <div class="navbar-title">
    <h5>F.I.T</h5>
  </div>
  
</div>

<?php include '../Agent Section/includes/logoutViewPassModal.php'; ?>