<!DOCTYPE html>
<html lang="en">
<head>
    <title>Smart Travel</title>

    <?php include 'includes\head.php' ?>

    <!-- External CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <header class="sub-header d-inline-flex">
        <div class="container d-inline-flex">
            <a href="tel: +123456789" class="d-flex align-items-center text-decoration-none text-light">
                <i class="fa-solid fa-phone me-2"></i> +123 456 789
            </a>
            <a href="mailto:info@example.com" class="d-flex align-items-center text-decoration-none text-light">
                <i class="fa-solid fa-envelope ps-4 pe-2"></i> info@example.com
            </a>
        </div>
    </header> 

    <!-- Main Header -->
    <header class="header d-flex justify-content-between align-items-center">
        <div class="logo">
            <a href="#" class="logo"><img src="assets\images\SMART LOGO 2 (2).png" alt="Logo"></a>
        </div>
        <nav class="navbar d-flex justify-content-end">
            <a href="#" class="nav-link">Home</a>
            <a href="#" class="nav-link">Packages</a>
            <a href="#" class="nav-link">Hotels</a>
            <a href="#" class="nav-link">About</a>
            <a href="#" class="nav-link">Contact</a>
            <div class="login-container ms-5">
                <button class="btn btn-success text-lighter fw-normal" id="LoginButton">Book Now</button>
            </div>
        </nav>
        
        <!-- <div class="d-flex flex-form">

            <!-- <label class="text-light">Lang:</label> 
            <div class="container dropdown-toggle d-flex flex-row align-items-center" id="translation-container" data-bs-toggle="dropdown" aria-expanded="false"> 
                <!-- <div class="dropdown">
                    <div class="circle-image">
                        <img id="language-flag" src="assets\images\Flags\english-flag.png" alt="English Flag" class="img-fluid"> <!-- Initial flag image 
                    </div>
                </div>

                <label id="language-label" class="text-light ms-2">English</label>

                <div class="dropdown-container ms-0">
                    <!-- <a class="dropdown-toggle ms-1 text-decoration-none text-secondary" type="button" id="dropdownMenuButton" aria-expanded="false">
                        ▼
                    </a> 

                    <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item my-2" href="#" data-lang="English" data-image="assets\images\Flags\english-flag.png"> <img src="assets\images\Flags\english-flag.png" alt=""> English</a></li>
                        <li><a class="dropdown-item my-2" href="#" data-lang="Korean" data-image="assets\images\Flags\korean-flag.png"> <img src="assets\images\Flags\korean-flag.png" alt=""> Korean</a></li>
                        <li><a class="dropdown-item my-2" href="#" data-lang="Bahasa" data-image="assets\images\Flags\bahasa-flag.png"> <img src="assets\images\Flags\bahasa-flag.png" alt=""> Bahasa</a></li>
                    </ul>
                </div>
            </div>

            <div class="vl"></div> 

            
    </div> -->
    </header>

    <!-- Section 1 -->
    <div class="section d-flex" id="section1">
        <!-- Background Image -->
        <img src="assets/images/_RJH6060_1.jpg" alt="Background" class="background-img">

        <!-- Dark Overlay -->
        

        <div class="hero-slider">
        <div class="overlay"></div>
            <div class="slide active" data-background="#ffcccc">
                <img src="assets/images/hero-1.jpg" alt="Hero Image 1">
                <div class="content">
                    <h1>Transfer Service</h1>
                    <p>Explore your travel with our seamless transfer services.</p>
                    <a href="#" class="btn">Click Here</a>
                </div>
            </div>
            <div class="slide" data-background="#ccffcc">
                <img src="assets/images/hero-2.jpg" alt="Hero Image 2">
                <div class="content">
                    <h1>Adventure Awaits</h1>
                    <p>Join us for unforgettable adventures.</p>
                    <a href="#" class="btn">Discover More</a>
                </div>
            </div>
            <div class="slide" data-background="#ccccff">
                <img src="assets/images/hero-3.jpg" alt="Hero Image 3">
                <div class="content">
                    <h1>Cultural Experiences</h1>
                    <p>Immerse yourself in local cultures.</p>
                    <a href="#" class="btn">Learn More</a>
                </div>
            </div>
            <div class="slide" data-background="#ffffcc">
                <img src="assets/images/hero-4.jpg" alt="Hero Image 4">
                <div class="content">
                    <h1>Gourmet Delights</h1>
                    <p>Savor the flavors of the world.</p>
                    <a href="#" class="btn">Taste Now</a>
                </div>
            </div>
            <div class="slide" data-background="#ffccff">
                <img src="assets/images/hero-5.jpg" alt="Hero Image 5">
                <div class="content">
                    <h1>Relaxation & Wellness</h1>
                    <p>Find your peace with us.</p>
                    <a href="#" class="btn">Join Us</a>
                </div>
            </div>
            <div class="slide" data-background="#ccffff">
                <img src="assets/images/hero-6.jpg" alt="Hero Image 6">
                <div class="content">
                    <h1>Family Fun</h1>
                    <p>Fun for the whole family!</p>
                    <a href="#" class="btn">Explore Now</a>
                </div>
            </div>

            <div class="nav-buttons">
                <button class="prev">❮</button>
                <button class="next">❯</button>
            </div>

            <div class="pagination">
                <span class="dot active" data-slide="0"></span>
                <span class="dot" data-slide="1"></span>
                <span class="dot" data-slide="2"></span>
                <span class="dot" data-slide="3"></span>
                <span class="dot" data-slide="4"></span>
                <span class="dot" data-slide="5"></span>
            </div>
            
    </div>



        <!-- Content -->
        <!-- <div class="hero d-flex flex-column">
            <h3>Explore Korea with <span>Smart Travel</span></h3>
            <p class="mb-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
            <div class="btn-container d-flex flex-row justify-content-center">
                <button type="button" class="btn btn-secondary">Book Now</button>
                <button type="button" class="btn btn-outline-secondary p-2 px-3 ms-3 text-light">Learn More</button>
            </div>
        </div> -->
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

    <div class="section" id="section3"> 




    </div>

    <div class="section" id="section4"> 
    



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
    
    <?php include 'includes/scripts.php'; ?>   

    <script>
    // Navbar Button Trigger
        document.getElementById("LoginButton").onclick = function () {
        location.href = "login.php";
    };

    
    // // For Language Dropdown
    // document.addEventListener("DOMContentLoaded", function() {
    // const dropdownItems = document.querySelectorAll('.dropdown-item');
    // const languageLabel = document.getElementById('language-label');
    // const languageFlag = document.getElementById('language-flag');

    // dropdownItems.forEach(item => {
    //     item.addEventListener('click', function(event) {
    //         event.preventDefault(); // Prevent default anchor behavior

    //             // Change the displayed language
    //             languageLabel.textContent = this.getAttribute('data-lang');
    //             // Change the flag image
    //             languageFlag.src = this.getAttribute('data-image');
    //         });
    //     });
    // });



    $(document).ready(function() {
        let currentSlide = 0;
        const slides = $('.slide');
        const totalSlides = slides.length;

        // Show the first slide
        slides.eq(currentSlide).addClass('active');

        // Update pagination based on current slide
        function updatePagination() {
            $('.dot').removeClass('active');
            $('.dot').eq(currentSlide).addClass('active');
        }

        // Initial pagination setup
        updatePagination();

        // Function to go to the next slide
        function goToNextSlide() {
            slides.eq(currentSlide).removeClass('active');
            currentSlide = (currentSlide + 1) % totalSlides; // Loop to the next slide
            slides.eq(currentSlide).addClass('active');
            updatePagination();
        }

        // Function to go to the previous slide
        $('.prev').click(function() {
            slides.eq(currentSlide).removeClass('active');
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides; // Loop to the previous slide
            slides.eq(currentSlide).addClass('active');
            updatePagination();
        });

        // Next Button
        $('.next').click(function() {
            goToNextSlide();
        });

        // Pagination Click (Make Dots Clickable)
        $('.dot').click(function() {
            const slideIndex = $(this).index(); // Get the index from the clicked dot
            slides.removeClass('active'); // Hide all slides
            slides.eq(slideIndex).addClass('active'); // Show the selected slide
            currentSlide = slideIndex; // Update the current slide
            updatePagination(); // Update the pagination dots
        });

        // Automatically go to the next slide every 4 seconds
        setInterval(function() {
            goToNextSlide();
        }, 4000); // 4000 milliseconds = 4 seconds
    });


    </script>

    </body>
</html>
