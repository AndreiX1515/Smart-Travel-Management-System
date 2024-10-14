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
    <link rel="stylesheet" href="style.css">
    <style>
        /* Sidebar styles */
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #f8f9fa; /* Background color */
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 20px;
            transition: transform 0.3s ease;
            transform: translateX(-250px); /* Hidden by default */
        }

        .sidebar.active {
            transform: translateX(0); /* Show sidebar */
        }

        /* Toggle button styles */
        .toggle-btn {
            display: flex;
            align-items: center; /* Aligns the icon vertically */
            cursor: pointer;
            margin-right: 0px; /* Optional: Add some space to the right */
            border: none;
            border-radius: 0 5px 5px 0;
            width: 40px;
            height: 40px;
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: transform 0.3s ease;
            z-index: 1100; /* Keep it above the sidebar */
            color: #000;
        }



        .navbar {
            padding: 10px 20px; /* Adjust as necessary */
        }

        /* Aligns navbar contents */
        .navbar-brand {
            margin-left: 0px; /* Add some space to the left */
            margin-bottom: 2px;
        }

        .navbar-toggler {
            margin-left: auto; /* Ensures the toggler is at the end */
        }


        /* Main content */
        .main-content {
            /* margin-left: 0; /* Start with no margin to account for hidden sidebar
            padding: 20px 30px; */
            transition: margin-left 0.3s ease;
            position: relative;
        }

        .main-content.active {
            margin-left: 250px; /* Adjusted when sidebar is shown */
        }

        /* Additional styles for the sidebar links */
        .sidebar h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #007bff;
            color: white;
        }

        .sidebar .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .sidebar .invite-teammates {
            margin-top: auto;
            border-top: 1px solid #eaeaea;
            padding-top: 20px;
        }

        /* Navbar styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff; /* Use your preferred color */
            padding: 20px; /* Adjust padding as necessary */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Bottom shadow */
            z-index: 999; /* Ensure the navbar is above other elements */
        }

        .nav-item.dropdown {
            display: flex; /* Make the dropdown a flex container */
            align-items: center; /* Align items vertically centered */
        }

        .profile-container {
            display: flex; /* Use flexbox for the name and position */
            flex-direction: column; /* Stack name and position vertically */
            margin-left: 8px; /* Space between image and text */
        }

        .profile-container h6 {
            font-weight: bold; /* Bold for the name */
            margin: 0; /* Remove margin */
        }

        .profile-container span {
            font-size: 12px; /* Smaller font for position */
            color: gray; /* Gray color for the position */
            margin: 0; /* Remove margin */
        }

        .profile-image {
            border-radius: 50%; /* Circular image */
        }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="logo mt-5 mb-5">
            <!-- <img src="..\assets\images\SMART LOGO 2 (2).png" alt="" width="180" height="35"> -->
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

        <!-- <div class="profile-container d-flex align-items-center mt-auto">
            <img src="../assets/images/circle.png" alt="Profile" class="profile-image" width="40px" height="40px">
            <div class="ms-2">
                <h6 class="m-0">De Guzman, Andrei Vincent</h6>
                <span class="m-0">Admin</span>
            </div>
        </div> -->
    </div>


    <div class="main-content" id="mainContent">
        <nav class="navbar navbar-expand-lg justify-content-between sticky-top">
            <div class="container-fluid d-flex justify-content-between">
                <div class="nav-start-container d-flex flex-row">
                    <div class="toggle-btn" id="toggleBtn">
                        <i class="fa-solid fa-bars"></i>
                    </div>

                    <a class="navbar-brand" href="#">Dashboard</a>
                </div>

                
                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
                
                <div class="nav-end-container">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown d-flex align-items-center"> <!-- Use flexbox for alignment -->
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                                    <div class="profile-container ms-2 me-3"> <!-- Flex container -->
                                        <h6 class="m-0">De Guzman, Andrei Vincent</h6> <!-- User's Name -->
                                        <span class="m-0">Admin</span> <!-- User's Position -->
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

<<<<<<< HEAD
=======

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


>>>>>>> 00a8703d60c9dd233b07bc0f8dc81eb83eb2a70a
    <script>
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

    <script>
        document.getElementById('profileButton').addEventListener('click', function () {
                const dropdownMenu = document.getElementById('dropdownMenu');
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            });

            // Close the dropdown if the user clicks outside of it
            window.onclick = function(event) {
                if (!event.target.matches('.dropbtn')) {
                    const dropdowns = document.getElementsByClassName("dropdown-content");
                    for (let i = 0; i < dropdowns.length; i++) {
                        const openDropdown = dropdowns[i];
                        if (openDropdown.style.display === 'block') {
                            openDropdown.style.display = 'none';
                        }
                    }
                }
            }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
