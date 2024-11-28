<div class="sidebar">
  <div class="sidebar-logo-section">
    <a class="nav-link logo-link" href="#">
        <div class="logo-content">
          <div class="logo-backdrop">
             <img src="../assets/images/logo-tab.png" alt="Logo" class="sidebar-logo">
          </div>
            <span class="fw-bold">SMART TRAVEL</span>
        </div>
    </a>
</div>

<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link page-button" href="../Employee Section/emp-dashboard.php" data-page-name="Dashboard">
            <div class="icon"><i class="fa-solid fa-house"></i></div> <!-- Home Icon -->
            <span class="label">Dashboard</span>
        </a> 
    </li>
    
    <li class="nav-item add-booking">
        <a class="nav-link page-button" href="" data-page-name="Add Booking">
            <div class="icon"><i class="fa-solid fa-user-plus"></i></div> <!-- User Icon -->
            <span class="label">Add Booking</span>
        </a>
    </li>

    <li class="nav-item transaction"> 
        <a class="nav-link page-button" href="../Employee Section/emp-transaction.php" data-page-name="Transactions">
            <div class="icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div> <!-- User Icon -->
            <span class="label">Transactions</span>
        </a>
     </li>

    <!-- Manage Bookings Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link page-button" href="#" id="manageBookingDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#manageBookingMenu" aria-expanded="false" aria-controls="manageBookingMenu" data-page-name="Operationals">
            <div class="icon"><i class="fa-regular fa-file"></i></i></div>
            <span class="label">Reports</span>
        </a>
        <div class="collapse" id="manageBookingMenu">
           <ul class="nav flex-column ms-3">
               <li class="nav-item">
                   <a class="nav-link page-button" href="#" data-page-name="Itinerary">Itinerary</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link page-button" href="#" data-page-name="SOA">SOA</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link page-button" href="#" data-page-name="Ticket">Ticket</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link page-button" href="#" data-page-name="Vouchers">Vouchers</a>
               </li>
           </ul>
       </div>

    </li>
</ul>

<?php
require "../conn.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize variables
$accountId = $_SESSION['employee_accountId'] ?? '';
$agentId = $_SESSION['agentId'] ?? '';
$firstName =  $_SESSION['employee_fName'] ?? '';
$lastName =  $_SESSION['employee_lName'] ?? '';
$middleName = $_SESSION['employee_mName'] ?? '';  // Middle name is optional
$email = $_SESSION['email'] ?? '';
$password = $_SESSION['password'] ?? '';
$userType = $_SESSION['employee_userType'] ?? '';  // Assuming user type is part of the session


// Format the full name: Get the first letter of the middle name and place it at the end
$middleNameInitial = $middleName ? substr($middleName, 0, 1) . '.' : ''; // First initial of middle name
$fullName = htmlspecialchars($firstName . ' ' . $middleNameInitial . ' ' . $lastName);  // Full name with middle name initial at the end

// Position or role (assuming userType and accountType are available)
$position = htmlspecialchars(strtoupper($agentId));
?>
  <!-- Logout button at the bottom, outside the <ul> -->
  <div class="logout">
   <div class="profile-section">
       <div class="profile-left">
           <!-- Display Full Name and Position -->
           <div class="name"><?php echo $fullName; ?></div>
           <div class="position"><?php echo $position; ?></div>
       </div>

       <div class="profile-icon profile-icon-visible">
           <i class="fa-solid fa-user-circle"></i>
       </div>

   </div>

   <a class="nav-link" href="#">
      <div class="icon"><i class="fas fa-eye"></i></div> <!-- View Password Icon -->
      <span class="label">View Password</span>
   </a>
   
   <a class="nav-link" href="#">
      <div class="icon"><i class="fa-solid fa-right-from-bracket"></i></div> <!-- Logout Icon -->
      <span class="label">Logout</span>
   </a>
  </div>
</div>


