<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Smart Travel</title>

    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome Icon Kit CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Favicons -->
    <link href="assets/images/rsz_logo-tab.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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

                <label id="language-label" class="text-light ms-2">English</label>

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
    <div class="section d-flex" id="section1">
        <!-- Background Image -->
        <img src="assets\images\hero-1.jpg" alt="Background" class="background-img">

        <!-- Content -->
        <div class="hero d-flex flex-column">
            <h3>Explore Korea with <span>Smart Travel</span></h3>
            <p class="mb-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
            <div class="btn-container d-flex flex-row justify-content-center">
                <button type="button" class="btn btn-secondary">Book Now</button>
                <button type="button" class="btn btn-outline-secondary p-2 px-3 ms-3 text-light">Learn More</button>
            </div>
        </div>
    </div>

    <div class="section d-flex flex-column" id="section2">
        <div class="container-header d-flex flex-column text-center">
            <h3>Why Book with us?</h3>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. </p>
        </div>
        

        <div class="container-cards d-flex flex-row">
            <div class="card">
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h4>Affordable Packages</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="card">
                <div class="icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h4>Exclusive Destinations</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="card">
                <div class="icon">
                    <i class="fas fa-plane"></i>
                </div>
                <h4>Convenient Travel</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="card">
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
                <h4>Trusted Reviews</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </div>
    </div>

    <!-- Section 3
    <div class="section" id="section3">
        <div class="container-header-3">
            <h3>Our Packages</h3>
        </div>


        <div class="container-slide">
            <div class="slide">
                <div class="item" style="background-image: url('assets\images\1.jpg') !important; background-size: cover; background-position: center;" >
                <div class="content">
                    <div class="name">Winter</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</div>
                    <button class="btn btn-secondary">See More</button>
                </div>
            </div>

            <div class="item" style="background-image: url('assets\images\2.jpg');">
                <div class="content">
                    <div class="name">Spring</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</div>
                    <button class="btn btn-secondary">See More</button>
                </div>
            </div>

            <div class="item" style="background-image: url('assets/images/3.jpg');">
                <div class="content">
                    <div class="name">Summer</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</div>
                    <button class="btn btn-secondary">See More</button>
                </div>
            </div>

            <div class="item" style="background-image: url('assets/images/4.jpg');">
                <div class="content">
                    <div class="name">Autumn</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</div>
                    <button class="btn btn-secondary">See More</button>
                </div>
            </div>

            <div class="item" style="background-image: url('assets/images/5.jpg');">
                <div class="content">
                    <div class="name">Busan Tour</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</div>
                    <button class="btn btn-secondary">See More</button>
                </div>
            </div>

            <div class="item" style="background-image: url('assets/images/6.jpg');">
                <div class="content">
                    <div class="name">Thailand</div>
                    <div class="des">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</div>
                    <button class="btn btn-secondary">See More</button>
                </div>
            </div>
            </div>
        <div class="button">
            <button class="prev"><</button>
            <button class="next">></button>
        </div>
    </div> -->
    

    <script>
    // Navbar Button Trigger
        document.getElementById("LoginButton").onclick = function () {
        location.href = "login.php";
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



    let next = document.querySelector('.next');
    let prev = document.querySelector('.prev');

    // Event listener for 'next' button
    next.addEventListener('click', function(){
        let items = document.querySelectorAll('.item');
        document.querySelector('.slide').appendChild(items[0]);
    });

    // Event listener for 'prev' button
    prev.addEventListener('click', function(){
        let items = document.querySelectorAll('.item');
        document.querySelector('.slide').prepend(items[items.length - 1]);
    });

    // Automatically click the 'next' button every 3 seconds
    setInterval(function() {
        next.click();  // Simulate click on the 'next' button
    }, 5000);  // 3000ms = 3 seconds





    </script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>




</body>
</html>
