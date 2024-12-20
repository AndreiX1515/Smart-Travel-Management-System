<?php 
  require '../conn.php';
  session_start();

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
  $accId = $_SESSION['accountId'] ?? '';
  
?>