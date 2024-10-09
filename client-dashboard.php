<?php
require 'session_validate.php'; // Include the session validation script

// Fetch session variables directly
$email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
$firstName = $_SESSION['first_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Client Dashboard</title>
    
    <?php include 'includes/head.php' ?>
    <link rel="stylesheet" href="assets\css\client-dashboard.css?v=<?php echo time(); ?>">    
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <!-- <div id="sidebar" class="col-md-3 col-lg-2 nav-sidebar">
                <button class="toggle-btn" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="nav flex-column mt-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-gift"></i><span> Rewards</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-phone"></i><span> Contact info</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user-friends"></i><span> Guest traveler info</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-id-card"></i><span> Traveler info</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-credit-card"></i><span> Payment methods</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-plane"></i><span> Airline credits</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-star"></i><span> Loyalty programs</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-leaf"></i><span> Carbon footprint</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-calendar-alt"></i><span> Calendar sync</span></a>
                    </li>
                </ul>
            </div> -->

            <nav class="navbar-custom d-flex flex-row justify-content-between" id="navbar">
                    <div class="logo">
                        <a href="#" class="logo"><img src="assets\images\SMART LOGO 2 (2).png" alt="Logo" width="200px" height="35px"></a>
                    </div>

                    <div class="navbar-profile dropdown" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="text-secondary"><?php echo $lastName.', '.$firstName?></span>
                        <img src="assets\images\profile-user.png" width="40px" height="40px" alt="User Image">
                        <i class="fas fa-chevron-down"></i>
                    </div>

                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item" href="#" id="logout" data-bs-toggle="modal" data-bs-target="#logoutModal" ><i class="fas fa-sign-out-alt"></i>Logout</a>
                        </li>
                    </ul>
            </nav>
 
            
                
            <!-- Main content -->
            <div id="main-content" class="col-md-9 col-lg-10 w-100">
                <!-- Main Content Section -->
                <div class="profile-card ">
                    <div class="profile-info">
                        <h3>Hi, <?php echo $lastName.', '.$firstName?></h3>
                        <p class="fw-normal text-secondary"><? echo $email ?></p>
                    </div>

                    <button class="btn btn-primary me-2">View Transaction Status</button>
                    <button class="btn btn-primary">Transaction Inquiry</button>
                    

                    <div class="button-container">
                        
                </div>



                <!-- <div class="container-nav-tabs">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Home</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</button>
                            <button class="nav-link" id="nav-disabled-tab" data-bs-toggle="tab" data-bs-target="#nav-disabled" type="button" role="tab" aria-controls="nav-disabled" aria-selected="false" disabled>Disabled</button>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">...</div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">...</div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">...</div>
                        <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div>
                    </div>




                </div> -->
            </div>
        </div>
    </div>

    <!-- Modals -->

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
                    <a href="" class="btn btn-danger" id="logoutButton">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/scripts.php' ?>

    <script>
        $('#logoutButton').on('click', function (e) {

            // Send AJAX request to handle the logout
            $.ajax({
                url: 'client-logout.php', // Your PHP script for logging out
                method: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        window.location.href = 'login.php';
                    }
                },
                error: function () {
                    $('#message-1').text('Error logging out. Please try again.').addClass('show'); // Handle error display
                    showMessage();
                }
            });
        });
    </script>

    <!-- <script>
        // JavaScript for toggling sidebar and changing navbar width
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            var sidebar = document.getElementById('sidebar');
            var mainContent = document.getElementById('mainContent');
            var navbar = document.getElementById('navbar');
            var toggleIcon = document.querySelector('#sidebarToggle i');

            // Toggle sidebar visibility
            if (sidebar.classList.contains('expanded-sidebar')) {
                sidebar.classList.remove('expanded-sidebar');
                mainContent.classList.remove('expanded-main-content');
                navbar.style.width = 'calc(100% - 0px)'; // Adjust navbar width for hidden sidebar
                toggleIcon.classList.remove('fa-times');
                toggleIcon.classList.add('fa-bars');
            } else {
                sidebar.classList.add('expanded-sidebar');
                mainContent.classList.add('expanded-main-content');
                navbar.style.width = 'calc(100% - 250px)'; // Adjust navbar width for visible sidebar
                toggleIcon.classList.remove('fa-bars');
                toggleIcon.classList.add('fa-times');
            }
        });
    </script> -->

    <script>
       let isClosing = false;

        // Detect when the user is trying to leave the page (close the tab or window)
        window.addEventListener("beforeunload", function (event) {
            // Show confirmation dialog
            const confirmationMessage = "All unsaved data will be lost. Do you really want to leave?"; 
            event.returnValue = confirmationMessage; // For most browsers
            return confirmationMessage; // For some browsers (deprecated but still supported)
        });

        // Detect when the tab is being closed
        window.addEventListener("unload", function () {
            // User is closing the tab or window
            navigator.sendBeacon('client-logout.php'); // Attempt to log out the user
        });

    </script>











</body>

</html>
