<!DOCTYPE html>
<html lang="en">
<head>
    <?php include ('includes\head.php') ?>

    <!-- External CSS -->
    <link rel="stylesheet" href="assets\css\style.css">
    
    <style>
        #section1 {
            width: 100vw;
            height: 100vh;
            background: linear-gradient(
            rgba(0, 0, 0, 0.7), 
            rgba(0, 0, 0, 0.7)
            ), url('assets/images/hero-1.jpg') no-repeat center center !important;
            background-size: cover;
            background-attachment: fixed;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1;
    }

    </style>
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

    <!-- TODO: Hover Problem -->
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

        <button class="btn btn-primary" id="LoginButton">Book Now</button>
    </header>

    <!-- Section 1 -->
    <div class="section" id="section1">
        <!-- <div class="content-wrapper d-flex text-start align-content-start">
            <h1>Welcome to Section 1</h1>
            <p>This is a brief description about the section. Learn more about what we offer below.</p>
             <button class="btn btn-primary">Learn More</button> 
        </div> -->
    </div>

    <!-- Section 2 -->
    <div class="section" id="section2">
        <!-- <div class="content-wrapper d-flex text-start align-content-start">
            <h1>Welcome to Section 1</h1>
            <p>This is a brief description about the section. Learn more about what we offer below.</p>
             <button class="btn btn-primary">Learn More</button> 
        </div> -->
    </div>

    <script>
        document.getElementById("LoginButton").onclick = function () {
        location.href = "client-login.php";
    };
    </script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
</body>
</html>
