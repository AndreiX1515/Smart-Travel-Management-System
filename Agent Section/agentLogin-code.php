<?php
require "../conn.php"; // Move up to the parent directory
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['login'])) 
{
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check if username and password are provided
  if (!empty($username) && !empty($password)) 
  {
    // Prepare the SQL query to fetch the agent's details
    $sql = "SELECT * FROM agent WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username); // Bind the username as a string
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) 
    {
      // Fetch the agent's row
      $agent = $result->fetch_assoc();
      
      // Directly compare the plain text password (not recommended for production)
      if ($password === $agent['password']) 
      {
        // Set session variables for logged-in agent
        $_SESSION['agentId'] = $agent['agentId'];
        $_SESSION['username'] = $agent['username'];
        $_SESSION['fName'] = $agent['fName'];
        $_SESSION['lName'] = $agent['lName'];
        $_SESSION['mName'] = $agent['mName'];
        
        // Redirect to the agent dashboard or protected page
        header("Location: agent-dashboard.php");
        exit();
      } 
      else 
      {
        header("Location: agent-login.php");
        echo "Invalid password. Please try again.";
      }
    } 
    else 
    {
      header("Location: agent-login.php");
      echo "No account found with that username.";
    }
    
    $stmt->close();
  } 
  else 
  {
      echo "Please fill in both fields.";
  }
}
?>
