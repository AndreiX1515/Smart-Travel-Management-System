<!DOCTYPE html>
<html lang="en">
<head>
    <?php include ('includes\head.php') ?>

    <!-- External CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <header class="sub-header">
        <div class="container d-inline-flex ps-5">
            <a href="tel:+123456789" class="d-flex align-items-center text-decoration-none text-light pe-2">
                <i class="fa-solid fa-phone pe-2"></i> +123 456 789
            </a>
            <a href="mailto:info@example.com" class="d-flex align-items-center text-decoration-none text-light">
                <i class="fa-solid fa-envelope ps-2 pe-2"></i> info@example.com
            </a>
        </div>
    </header>

    <!-- Main Header -->
    <header class="header d-flex justify-content-between align-items-center">
        <a href="#" class="logo"><img src="assets\images\logo.png" alt="Logo"></a>

        <nav class="navbar d-flex">
            <a href="#" class="nav-link">Home</a>
            <a href="#" class="nav-link">Destination</a>
            <a href="#" class="nav-link">Tours</a>
            <a href="#" class="nav-link">About</a>
            <a href="#" class="nav-link">Contact</a>
        </nav>

        <div class="d-flex flex-form">

            <!-- <label class="text-light">Lang:</label> -->
            <div class="container dropdown-toggle d-flex flex-row align-items-center" id="translation-container" data-bs-toggle="dropdown" aria-expanded="false"> 
                <div class="dropdown">
                    <div class="circle-image">
                        <img id="language-flag" src="assets\images\Flags\english-flag.png" alt="English Flag" class="img-fluid"> <!-- Initial flag image -->
                    </div>
                </div>

                <label id="language-label" class="text-light ms-2">English</label> <!-- Label for the selected language -->

                <div class="dropdown-container ms-0">
                    <!-- <a class="dropdown-toggle ms-1 text-decoration-none text-secondary" type="button" id="dropdownMenuButton" aria-expanded="false">
                        ▼
                    </a> -->

                    <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item my-2" href="#" data-lang="English" data-image="assets\images\Flags\english-flag.png"> <img src="assets\images\Flags\english-flag.png" alt=""> English</a></li>
                        <li><a class="dropdown-item my-2" href="#" data-lang="Korean" data-image="assets\images\Flags\korean-flag.png"> <img src="assets\images\Flags\korean-flag.png" alt=""> Korean</a></li>
                        <li><a class="dropdown-item my-2" href="#" data-lang="Bahasa" data-image="assets\images\Flags\bahasa-flag.png"> <img src="assets\images\Flags\bahasa-flag.png" alt=""> Bahasa</a></li>
                    </ul>
                </div>
            </div>

            <div class="vl"></div>

            <button class="btn btn-success text-lighter fw-normal py-1 px-2 ms-3 my-1" id="LoginButton">Book Now</button>
    </div>


    </header>

    <!-- Section 1 -->
    <div class="section d-flex justify-content-start align-items-center position-relative" id="section1">
        <!-- Background Image -->
        <img src="assets\images\hero-1.jpg" alt="Background" class="background-img">

        <!-- Content -->
        <div class="hero d-flex flex-column text-light position-relative">
            <h3>Travel with Satisfaction</h3>
            <p>Discover your next adventure, one destination at a time.</p>
            <div class="d-inline-flex flex-row">
                <button type="button" class="btn btn-secondary">Book Now</button>
                <button type="button" class="btn btn-outline-secondary p-2 px-3 ms-3 text-light">Learn More</button>
            </div>
        </div>
    </div>

    <!-- Section 2 -->
    <div class="section" id="section2">
        <!-- <div class="content-wrapper d-flex text-start align-content-start">
            <h1>Welcome to Section 1</h1>
            <p>This is a brief description about the section. Learn more about what we offer below.</p>
             <button class="btn btn-primary">Learn More</button> 
        </div> -->
    </div>

    <div class="section" id="section3">
        <!-- <div class="content-wrapper d-flex text-start align-content-start">
            <h1>Welcome to Section 1</h1>
            <p>This is a brief description about the section. Learn more about what we offer below.</p>
             <button class="btn btn-primary">Learn More</button> 
        </div> -->
    </div>

    <script>

    // Navbar Button Trigger
        document.getElementById("LoginButton").onclick = function () {
        location.href = "client-login.php";
    };

    // For Language Dropdown
    document.addEventListener("DOMContentLoaded", function() {
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    const languageLabel = document.getElementById('language-label');
    const languageFlag = document.getElementById('language-flag');

    dropdownItems.forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default anchor behavior

                // Change the displayed language
                languageLabel.textContent = this.getAttribute('data-lang');
                // Change the flag image
                languageFlag.src = this.getAttribute('data-image');
            });
        });
    });

    </script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>




</body>
</html>
