<?php
// Start session
session_start();

// Check if the user is logged in by verifying if session variables are set
// if (!isset($_SESSION['client_id'])) {
//     // If session variables are not set, redirect to login page
//     header("Location: login.php");
//     exit();
// }

// Access session values
$client_id = $_SESSION['client_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/client-dashboard.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navbar -->
            <nav class="navbar bg-secondary sticky-top px-5">
                <div class="container-fluid d-flex justify-content-between align-items-center">
                    <!-- Logo Container -->
                    <div class="logo-container">
                        <a href="#" class="text-light text-decoration-none fs-5 fw-bolder">St. Joseph Catholic Cemetery</a>
                    </div>

                    <!-- Profile Container -->
                    <div class="profile-container d-flex flex-row align-items-center">
                        <span class="text-light me-3">
                            <?php echo htmlspecialchars($last_name) . ", " . htmlspecialchars($first_name); ?>
                        </span>

                        <!-- Profile Image -->
                        <div class="circle-image me-2">
                            <img id="profile-pic" src="assets/img/user.png" alt="Profile Icon" style="width: 40px; height: 40px;">
                        </div>

                        <!-- Dropdown for Logout -->
                        <div class="dropdown">
                            <a class="dropdown-toggle text-light" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"></a>

                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>      

            <!-- Main content -->
            <div id="main-content" class="col-md-12 main-content">
                <!-- Main Content Section -->
                <div class="profile-card mt-5 w-100 px-4 d-flex justify-content-between">
                    <div>
                        <h3>Hi, <?php echo htmlspecialchars($last_name) . ", " . htmlspecialchars($first_name); ?></h3>
                        <p><?php echo htmlspecialchars($email); ?></p>
                    </div>

                    <div class="buttons-container d-flex flex-row align-items-center">
                        <button class="btn btn-primary me-2">Inquire</button>
                        <button class="btn btn-primary">Process Payment</button>
                    </div>
                </div>

                <div class="table px-4 mt-5">
                    <div class="d-flex flex-row my-3 justify-content-between align-items-center">
                        <h4>Relative Names:</h4>
                        <button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-plus fa-1x"></i></button>
                    </div>


                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID #</th>
                                <th scope="col">First</th>
                                <th scope="col">Last</th>
                                <th scope="col">Middle Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>George</td>
                                <td>Dela Cruz</td>
                                <td>Dimagiba</td>
                                <td><span class="badge text-bg-danger">For Expiration</span></td>
                                <td><i class="fa-solid fa-pen-to-square me-2"></i>
                                    <i class="fa-solid fa-trash me-2"></i>
                                </td>
                            </tr>
                            <!-- <tr>
                                <th scope="row">2</th>
                                <td>Mary</td>
                                <td>Dela Cruz</td>
                                <td>Dimagiba</td>
                                <td><span class="badge text-bg-success">Paid</span></td>
                                <td><i class="fa-solid fa-pen-to-square me-2"></i>
                                    <i class="fa-solid fa-trash me-2"></i>
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="client-logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

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
