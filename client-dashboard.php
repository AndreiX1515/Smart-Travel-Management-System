
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Client Dashboard</title>
    
    <?php include 'includes/head.php' ?>
    <link rel="stylesheet" href="assets\css\client-dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets\css\client-navbar.css?v=<?php echo time(); ?>">    
</head>

<body>
    <?php include 'client-includes/client-navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
 
            <!-- Main content -->
            <div id="main-content" class="col-md-9 col-lg-10 w-100">
                <!-- Main Content Section -->
                <div class="profile-card ">
                    <div class="profile-info">
                        <h5>Hi, <?php echo $fullName; ?></h5>
                        <p class="fw-normal text-secondary"><?php $email ?></p>
                    </div>

                    <?php 
                        if(isset($_SESSION['status'])):
                        ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Hey!</strong> <?= $_SESSION['status']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php 
                        unset($_SESSION['status']);
                        endif;
                    ?>

                    
                    <div class="button-container">
                     <a class="btn btn-primary me-2" href="bookingform.php" role="button">Book Now</a>
                     <a class="btn btn-primary me-2" href="client-transactionStatus.php" role="button">View Transaction Status</a>
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
                    
                    <!-- Logout form -->
                    <form action="client-logout.php" method="POST">
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>

    <script src="heartbeat.js"></script>
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
    
    


</body>

</html>
