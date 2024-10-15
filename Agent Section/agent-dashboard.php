<?php
// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard with Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../Agent Section/assets/css/agent-dashboard.css">
</head>

<body>
    <!-- Sidebar Section -->
    <div class="sidebar" id="sidebar">
        <div class="logo mt-5 mb-5">
            <!-- Logo can be added here -->
        </div>

        <div class="section-title">Dashboard</div>
        <!-- Navigation links with icons -->
        <a href="#"> <i class="fas fa-home"></i> Home </a>
        <a href="#"> <i class="fas fa-box"></i> Products </a>
        <a href="#"> <i class="fas fa-chart-line"></i> Analytics </a>
        <a href="#"> <i class="fas fa-users-cog"></i> Team Settings </a>

        <div class="section-title">Organization</div>
        <a href="#"> <i class="fas fa-plug"></i> Apps & Integrations </a>
        <a href="#"> <i class="fas fa-gift"></i> Perks & Extras </a>
        <a href="#"> <i class="fas fa-file-invoice"></i> Tax Forms </a>
        <a href="#"> <i class="fas fa-globe"></i> Global Payroll </a>

        <!-- Profile section (optional) -->
        <!-- <div class="profile-container d-flex align-items-center mt-auto">
            <img src="../assets/images/circle.png" alt="Profile" class="profile-image" width="40px" height="40px">
            <div class="ms-2">
                <h6 class="m-0">De Guzman, Andrei Vincent</h6>
                <span class="m-0">Admin</span>
            </div>
        </div> -->
    </div>

    <!-- Main Content Section -->
    <div class="main-content" id="mainContent">
        <header>
            <!-- Navbar Section -->
            <nav class="navbar navbar-expand-lg justify-content-between sticky-top">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="nav-start-container d-flex flex-row">
                        <!-- Toggle button for the sidebar -->
                        <div class="toggle-btn" id="toggleBtn">
                            <i class="fa-solid fa-bars"></i>
                        </div>

                        <a class="navbar-brand" href="#">Dashboard</a>
                    </div>

                    <div class="nav-end-container">
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown d-flex align-items-center">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="profile-container ms-2 me-3">
                                            <h6 class="m-0">De Guzman, Andrei Vincent</h6>
                                            <span class="m-0">Admin</span>
                                        </div>
                                        <img src="../assets/images/circle.png" alt="Profile" class="profile-image me-2" width="40px" height="40px">
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="navbarDropdown">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-user me-2"></i> My Profile
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-cog me-2"></i> Settings
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>


        <!-- Main Dashboard Content -->
        <div class="Dashboard-Cards">
            <div class="row">
                <div class="col-md-4 col-xl-2">
                    <div class="card bg-white order-card">
                        <div class="card-block">
                            <h6 class="m-b-20">Pending Transaction:</h6>
                            <h2 class="text-right mb-4"><span>486</span></h2>
                            <p class=""><i class="fa-solid fa-arrow-up"></i><span class="f-right">351</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 col-xl-2">
                    <div class="card bg-c-green order-card">
                        <div class="card-block">
                            <h6 class="m-b-20">Orders Received</h6>
                            <h2 class="text-right"><span>486</span></h2>
                            <p class="m-b-0">Completed Orders<span class="f-right">351</span></p>
                        </div>
                    </div>
                </div>
                
                <!-- <div class="col-md-4 col-xl-2">
                    <div class="card bg-c-yellow order-card">
                        <div class="card-block">
                            <h6 class="m-b-20">Orders Received</h6>
                            <h2 class="text-right"><i class="fa fa-refresh f-left"></i><span>486</span></h2>
                            <p class="m-b-0">Completed Orders<span class="f-right">351</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 col-xl-2">
                    <div class="card bg-c-pink order-card">
                        <div class="card-block">
                            <h6 class="m-b-20">Orders Received</h6>
                            <h2 class="text-right"><i class="fa fa-credit-card f-left"></i><span>486</span></h2>
                            <p class="m-b-0">Completed Orders<span class="f-right">351</span></p>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>


            </div>
        </nav>

        <div class="table-responsive">
            <table class="table excel-table">
                <thead>
                    <tr>
                        <th scope="col">Transaction Number</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>testing</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

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


    <script>
        // Toggle sidebar visibility
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('active');

            // Change the icon based on the sidebar state
            if (sidebar.classList.contains('active')) {
                toggleBtn.innerHTML = '<i class="fas fa-times"></i>'; // Change to X icon
            } else {
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>'; // Change to hamburger icon
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
