<?php 
  require '../conn.php';
  session_start();

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
  $accId = $_SESSION['accountId'] ?? '';
  
?>

<!DOCTYPE html>
<html lang="en">
<head>

  <title>Smart Travel</title>

  <?php include '../Client Section/Includes/head.php' ?>

 <link rel="stylesheet" href="../Client Section/assets/css/Homepage.css?v=<?php echo time(); ?>">

 <style>
    .button-disabled {
         background-color: #ccc; /* Light gray background */
         color: #666; /* Darker gray text */
         pointer-events: none; /* Prevent mouse events */
         cursor: not-allowed; /* Change cursor to indicate it's disabled */
     }

     /* Positioning for the background images container */
     .container-background {
         position: absolute;
         width: 100vw;
         height: 100vh;
         top: 0;
         left: 0;
         overflow: hidden; /* Hide overflow to prevent scrollbars */
     }

     /* Dark overlay to add contrast to the hero section */
     .dark-overlay {
         position: absolute;
         width: 100vw;
         height: 100vh;
         background: linear-gradient(to top, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0)); /* Gradient from dark to transparent */
         z-index: 1; /* Ensure the overlay is above the background images */
     }

     /* Background images settings */
     .background-image {
         position: absolute;
         width: 100vw;
         height: 100vh;
         background-size: cover; /* Ensure images cover the entire area */
         background-position: center; /* Center the images */
         opacity: 0; /* Start with images hidden */
         animation: BgFade 30s infinite; /* Loop through images every 30 seconds */
         z-index: 0; /* Keep the background images behind the overlay */
     }

     /* Define each background with its specific timing */
     .bg1 { background-image: url('../Assets/Places in Korea/hero-1.jpg'); animation-delay: 0s; }
     .bg2 { background-image: url('../Assets/Places in Korea/hero-2.jpg'); animation-delay: 5s; }
     .bg3 { background-image: url('../Assets/Places in Korea/hero-3.jpg'); animation-delay: 10s; }
     .bg4 { background-image: url('../Assets/Places in Korea/hero-4.jpg'); animation-delay: 15s; }
     .bg5 { background-image: url('../Assets/Places in Korea/hero-5.jpg'); animation-delay: 20s; }
     .bg6 { background-image: url('../Assets/Places in Korea/hero-6.jpg'); animation-delay: 25s; }

     /* Keyframes for fading background images */
     @keyframes BgFade {
         0%, 100% { opacity: 0; }   
         10%, 40% { opacity: 1; }    
     }

 </style>
</head>

<body>

<header>
  <nav>
    <div class="logo-container d-flex flex-row g-2">
      <img src="../Assets/Logos/logo.png" alt="Logo" class="logo">
    </div>

    <div class="menu-container">
      <ul class="menu-list d-flex align-items-center"> <!-- Add flex for horizontal alignment -->
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#contact">Contact</a></li>
        <?php 
        if (isset($_SESSION['accountId'])): ?>
        <!-- <li> <a href="../Client Section/client-portall.php">Client Portal</a></li> -->
        <?php endif; ?> 
      </ul>
      <!-- Vertical line between menu items and login button -->
      <div class="vertical-line"></div>

      <div class="login-btn-container mt-1">
        <div class="collapse navbar-collapse show" id="navbarNav"> <!-- Add "show" class to make sure it’s visible -->
          <ul class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['accountId'])): ?>
              <!-- Profile Dropdown when Session is Active -->
              <li class="nav-item dropdown d-flex align-items-center">
                <a class="nav-link dropdown-toggle text-light d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="profile-container ms-2 me-3">
                    <!-- <h6 class="mb-1"><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></h6> 
                    <span class="m-0">Branch: <?php echo $branch; ?></span> -->
                  </div>
                  <img src="../Assets/Icons/user.png" alt="Profile" class="profile-image me-2" width="40px" height="40px">
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> My Profile</a></li>
                  <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Transaction History</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
              </li>
            <?php else: ?>

              <!-- Show Login Button when No Session is Active -->
              <li class="nav-item">
                <button class="btn btn-login" id="LoginButton">LOGIN</button>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>

<section class="hero">
  <!-- Background images container -->
  <div class="container-background">
    <div class="background-image bg1"></div>
    <div class="background-image bg2"></div>
    <div class="background-image bg3"></div>
    <div class="background-image bg4"></div>
    <div class="background-image bg5"></div>
    <div class="background-image bg6"></div>
  </div>

  <!-- Dark overlay -->
  <div class="overlay"></div>

  <!-- Main content in the hero section -->
  <div class="section-container">
    <h1>Discover the World with <span class="highlight">Smart Travel</span></h1>
    <p>Your satisfaction is our top priority. Experience travel like never before!</p> 
    <div class="d-flex flex-row gap-3">


      <a href="<?php echo isset($_SESSION['accountId']) ?  'login.php' :  'client-flightSched.php'; ?>" class="cta-button">
        Book Now</a>
      <a href="#learn-more" class="cta-button-outline">Learn More</a>
    </div>
    
  </div>
</section>


<section class="about">
    <div class="image-accent">
        <img src="../Assets/Places in Korea/hero-2.JPG" alt="Description of the image">
    </div>

    <div class="about-content d-flex flex-column">
        <div class="content-header">
            <h3>About <span class="highlight">Smart Travel</span> Tours</h3>
        </div>

        <div class="section-1">
          <p>
            Embark on the journey of a lifetime with Smart Travel, your trusted tour operator in South Korea and the Philippines. We specialize in providing personalized and group tours that cater to your unique passions and create unforgettable memories.
          </p>


          </div>

        <div class="rated-star-container">
          <!-- 5 Star Review -->
           <div class="card-2">
             <div class="star-rating mb-1 mt-2">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
              </div>
                  <span class="rating-text ms-2 mb-4">(4.5/5 based on 250 reviews)</span>
           </div>
        </div>

       <div class="about-card-content mt-3">
         <div class="card-1">
             <h3>100+</h3>
             <p>Passionate Guides</p>
         </div>

         <div class="card-1">
             <h3>550K+</h3>
             <p>Trusted Service Quality</p>
         </div>
       </div>

    </div>

</section>

<section class="whybook">
    <h3>WHY BOOK WITH US?</h3>
    <hr>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia id corrupti libero porro, unde mollitia minus est at ut animi.</p>

    
    <div class="cards-container">
     <!-- Card 1 -->
     <div class="card">
         <div class="icon my-4 text-primary">
             <i class="fas fa-plane fa-3x"></i>
         </div>
         <h5 class="card-title">Travel Planning Assistance</h5>
         <p class="card-text">Our expert team will help you plan every detail of your trip, ensuring a hassle-free and enjoyable experience tailored to your preferences.</p>
         <a href="#" class="btn btn-primary">Learn More</a>
     </div>

     <!-- Card 2 -->
     <div class="card">
         <div class="icon my-4 text-primary">
             <i class="fas fa-map-marker-alt fa-3x"></i>
         </div>
         <h5 class="card-title">Local Experience Guides</h5>
         <p class="card-text">Explore new destinations with local experts who will show you the hidden gems and cultural hotspots.</p>
         <a href="#" class="btn btn-primary">Learn More</a>
     </div>

     <!-- Card 3 -->
     <div class="card">
         <div class="icon my-4 text-primary">
             <i class="fas fa-tags fa-3x"></i>
         </div>
         <h5 class="card-title">Exclusive Deals and Offers</h5>
         <p class="card-text">Get access to special deals and discounts, available only to our clients for a more budget-friendly journey.</p>
         <a href="#" class="btn btn-primary">Learn More</a>
     </div>

     <!-- Card 4 -->
     <div class="card">
         <div class="icon my-4 text-primary">
             <i class="fas fa-user-check fa-3x"></i>
         </div>
         <h5 class="card-title">Personalized Travel Experiences</h5>
         <p class="card-text">Tailor your trip to match your interests and needs with our personalized travel planning services.</p>
         <a href="#" class="btn btn-primary">Learn More</a>
     </div>
   </div>
</section>

<section class="testimonies">
   <div class="mb-lg-3">
    <h3>CLIENT TESTIMONIALS</h3>
     <hr>
     <p>See what our clients have to say about their experiences with us.</p>
  </div>
   
  <div id="carouselExample" class="carousel slide mt-5" data-bs-ride="carousel">
     <div class="carousel-inner mt-5">
      <div class="carousel-item active">
          <div class="testimony-content">
              <img src="https://picsum.photos/100/100?random=1" alt="Client Image" class="client-image">
              <div class="card-body">
                  <p class="client-quote">"The travel planning assistance was exceptional! They took care of everything!"</p>
                  <h5 class="client-name">- Jane Doe</h5>
              </div>
          </div>
      </div>
      <div class="carousel-item">
          <div class="testimony-content">
              <img src="https://picsum.photos/100/100?random=2" alt="Client Image" class="client-image">
              <div class="card-body">
                  <p class="client-quote">"Thanks to the local experience guides, I discovered places I would have never found on my own!"</p>
                  <h5 class="client-name">- John Smith</h5>
              </div>
          </div>
      </div>
      <div class="carousel-item">
          <div class="testimony-content">
              <img src="https://picsum.photos/100/100?random=3" alt="Client Image" class="client-image">
              <div class="card-body">
                  <p class="client-quote">"I got amazing deals that saved me a lot of money! Highly recommend!"</p>
                  <h5 class="client-name">- Emily Johnson</h5>
              </div>
          </div>
      </div>
      <div class="carousel-item">
          <div class="testimony-content">
              <img src="https://picsum.photos/100/100?random=4" alt="Client Image" class="client-image">
              <div class="card-body">

                  <!-- 5 Star Review -->
                  <div class="card-2">
                    <div class="star-rating">
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star"></i>
                         <i class="fas fa-star-half-alt"></i>
                     </div>
                         <span class="rating-text ms-2 mb-4">(4.5/5)</span>
                  </div>

                  <p class="client-quote">"The personalized travel experience was top-notch. It truly felt like a vacation tailored just for me!"</p>

                  <h5 class="client-name">- Michael Brown</h5>
              </div>
          </div>
      </div>
    </div>

    <div class="btn-container">
     <button class="carousel-control-prev me-5" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         <span class="visually-hidden">Previous</span>
     </button>
     <button class="carousel-control-next ms-5" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
         <span class="carousel-control-next-icon" aria-hidden="true"></span>
         <span class="visually-hidden">Next</span>
     </button>
    </div>

</div>
</section>

<section class="packages-offered">
  <div class="packages-offered-header">
     <div class="left-side me-3">
        <div class="mb-5">
         <h5>Packages Offered</h5>
         <hr>
         <h3 class="mt-3">Discover Amazing Destinations</h3>
         <p>Experience the world’s top destinations with our tailored travel packages. From vibrant cities to serene landscapes, enjoy guided tours, exclusive deals, and personalized itineraries designed to make your trip unforgettable.</p>
       </div>     
     </div>
     
     <div class="right-side"> 
      <div class="circle-buttons">
          <button type="button" class="circle-btn left" onclick="moveLeft()" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="prev">
              <i class="fa-solid fa-chevron-left"></i>
          </button>

          <button type="button" class="circle-btn right" onclick="moveRight()" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="next">
              <i class="fa-solid fa-chevron-right"></i>
          </button>

      </div>
    </div>
 </div>


 <div class="carousel-content-wrapper">
     <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
         <div class="carousel-inner">
          
             <div class="carousel-item active">
               <div class="carousel-content">
                     <div class="image-container">
                         <img src="../Assets/Places in Korea/Jeju Island/jeongbang_waterfall.jpg"  alt="Jeongbang Waterfall">
                     </div>
                     <div class="content-container">
                         <h3>Summer Package</h3>
                         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem iure nam totam blanditiis necessitatibus doloribus quasi, eius veniam, dicta vero labore vitae quidem. Laboriosam culpa facere porro dolorem iusto repellat..</p>

                         <div class="icon-buttons-container">
                            <div class="icon-button">
                                <i class="fas fa-sun"></i> 5 Days
                            </div>
                            <div class="icon-button">
                                <i class="fas fa-calendar-alt"></i> Mar - Oct
                            </div>
                            <div class="icon-button">
                                <i class="fas fa-map-marker-alt"></i> Keflavik Airport
                            </div>
                            <div class="icon-button">
                                <i class="fas fa-clock"></i> Flexible
                            </div>
                        </div>

                         <div class="book-btn-container">
                             <button class="btn btn-primary px-4 py-2"> Book Now </button>
                         </div>

                     </div>
                 </div>
             </div>

             <div class="carousel-item">
                <div class="carousel-content">
                   <div class="image-container">
                       <img src="../Assets/Places in Korea/Jeju Island/jeongbang_waterfall.jpg"  alt="Image 2">
                   </div>
                   <div class="content-container">
                         <h3>Summer Package</h3>
                         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem iure nam totam blanditiis necessitatibus doloribus quasi, eius veniam, dicta vero labore vitae quidem. Laboriosam culpa facere porro dolorem iusto repellat..</p>

                         <div class="icon-buttons-container">
                            <div class="icon-button">
                                <i class="fas fa-sun"></i> 5 Days
                            </div>
                            <div class="icon-button">
                                <i class="fas fa-calendar-alt"></i> Mar - Oct
                            </div>
                            <div class="icon-button">
                                <i class="fas fa-map-marker-alt"></i> Keflavik Airport
                            </div>
                            <div class="icon-button">
                                <i class="fas fa-clock"></i> Flexible
                            </div>
                        </div>

                         <div class="book-btn-container">
                             <button class="btn btn-primary px-4 py-2"> Book Now </button>
                         </div>

                     </div>
                 </div>
             </div>

             <div class="carousel-item">
               <div class="carousel-content">
                 <div class="image-container">
                     <img src="../Assets/Places in Korea/Jeju Island/hallasan.jpg"  alt="Image 3">
                 </div>
                 <div class="content-container">
                         <h3>Summer Package</h3>
                         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem iure nam totam blanditiis necessitatibus doloribus quasi, eius veniam, dicta vero labore vitae quidem. Laboriosam culpa facere porro dolorem iusto repellat..</p>

                         <div class="icon-buttons-container">
                            <div class="icon-button">
                                <i class="fas fa-sun"></i> 5 Days
                            </div>
                            <div class="icon-button">
                                <i class="fas fa-calendar-alt"></i> Mar - Oct
                            </div>
                            <div class="icon-button">
                                <i class="fas fa-map-marker-alt"></i> Keflavik Airport
                            </div>
                            <div class="icon-button">
                                <i class="fas fa-clock"></i> Flexible
                            </div>
                        </div>

                         <div class="book-btn-container">
                             <button class="btn btn-primary px-4 py-2"> Book Now </button>
                         </div>

                     </div>
                 </div>
             </div>

             <!-- Add more carousel items as needed -->
         </div>
     </div>
   </div>

</section>

<section class="featured-hotels">
  <div class="section-header">
   <div class="mb-5">
    <h3>Partnered Hotels</h3>
    <hr>
    <p>Discover our curated selection of partner hotels, specially chosen to enhance your travel experience with comfort, luxury, and convenience.</p>
  </div>
 </div>

 <div class="section-body">
  <div class="center-part">
    <img src="../Assets/Hotels/marinabay.jpg" alt="Left Part Background" class="background-img">
    <div class="content-overlay">
     <div class="content-overlay-text w-75">
      <h2>Smart Hotel</h2>
      <p>Experience the vibrant waterfront with stunning marina views and world-class amenities.</p>
     </div>
    </div>
  </div>
 </div>

 <div class="section-body">
  <div class="left-part">
    <img src="../Assets/Hotels/marinabay.jpg" alt="Left Part Background" class="background-img">
    <div class="content-overlay">
     <div class="content-overlay-text w-75">
      <h2>Marina Bay Hotel</h2>
      <p>Experience the vibrant waterfront with stunning marina views and world-class amenities.</p>
     </div>
    </div>
  </div>

  <div class="right-part">
    <div class="top-part">
        <img src="../Assets/Hotels/ramada.jpg" alt="Top Part Background" class="background-img">
        <div class="content-overlay">
         <div class="content-overlay-text w-75">
            <h6>Ramada Hotel Korea</h6>
            <p>Experience modern comfort and authentic Korean hospitality in the heart of the city.</p>
        </div>
       </div>
    </div>
    <div class="bottom-part">
        <img src="../Assets/Hotels/airsky (2).jpg" alt="Bottom Part Background" class="background-img">
        <div class="content-overlay">
         <div class="content-overlay-text w-75">
          <h6>Airsky Hotel</h6>
          <p>Discover elevated luxury with panoramic views and a serene, sky-high escape.</p>
          </div>
        </div>
    </div>
  </div>
 </div>

 <div class="section-body">
  <div class="right-part">
    <div class="top-part">
        <img src="../Assets/Hotels/centum.png" alt="Top Part Background" class="background-img">
        <div class="content-overlay">
         <div class="content-overlay-text w-75">
          <h6>Centum Hotel</h6>
          <p>Elegant accommodations with convenient access to the city's premier shopping and cultural centers.</p>
          
        </div>
       </div>
    </div>
    <div class="bottom-part">
        <img src="../Assets/Hotels/recenz hotel.png" alt="Bottom Part Background" class="background-img">
        <div class="content-overlay">
         <div class="content-overlay-text w-75">
          <h6>Recenz Hotel</h6>
          <p>Experience modern comfort in a prime location, ideal for both business and leisure travelers.</p>
          
          </div>
        </div>
    </div>
  </div>

  <div class="left-part">
   <img src="../Assets/Hotels/royal emporium.jpg" alt="Left Part Background" class="background-img">
   <div class="content-overlay">
    <div class="content-overlay-text w-75">
     <h2>Royal Emporium Hotel</h2>
     <p>A blend of elegance and modern amenities, offering a refined stay near local attractions and shopping districts.</p>

    </div>
   </div>
 </div>
</div>

<div class="section-body">
  <div class="left-part">
   <img src="../Assets/Hotels/ibis.jpg" alt="Left Part Background" class="background-img">
   <div class="content-overlay">
    <div class="content-overlay-text w-75">
     <h2>Ibis Insadong Hotel</h2>
<p>Located in the heart of Seoul, Ibis Insadong offers stylish accommodations with easy access to cultural sites and vibrant local markets.</p>

    </div>
   </div>
 </div>

 <div class="right-part">
   <div class="top-part">
       <img src="../Assets/Hotels/blue ocean.png" alt="Top Part Background" class="background-img">
       <div class="content-overlay">
        <div class="content-overlay-text w-75">
         <h6>Blue Ocean Hotel</h6>
         <p>Situated near the coastline, Blue Ocean Hotel provides breathtaking sea views and a relaxing atmosphere, perfect for beach lovers and travelers seeking tranquility.</p>
         
       </div>
      </div>
   </div>
 </div>

 

</div>

 




</section>

<footer class="footer">
 <section>
   <div class="container-fluid">
     <div class="row">
       <div class="col-12 col-lg-4 bg-dark py-4 py-md-5 py-xxl-8">
         <div class="row h-100 align-items-end justify-content-center">

           <div class="col-12 col-md-11 col-xl-10">

             <div class="footer-logo-wrapper">
               <a href="#!">
                 <img src="../Assets/Logos/logo.png" alt="Smart Travel Logo" width="250" height="50">
               </a>
             </div>

             <!-- Social Icons Div -->
             <div class="social-media-wrapper mt-5">
               <ul class="nav">
                 <li class="nav-item me-3">
                   <a class="nav-link link-primary p-2 bg-light rounded" href="#!">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                       <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                     </svg>
                   </a>
                 </li>
                 <li class="nav-item me-3">
                   <a class="nav-link link-primary p-2 bg-light rounded" href="#!">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                       <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
                     </svg>
                   </a>
                 </li>
                 <li class="nav-item me-3">
                   <a class="nav-link link-primary p-2 bg-light rounded" href="#!">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                       <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                     </svg>
                   </a>
                 </li>
                 <li class="nav-item">
                   <a class="nav-link link-primary p-2 bg-light rounded" href="#!">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                       <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                     </svg>
                   </a>
                 </li>
               </ul>
             </div>


             <div class="address-wrapper mt-5">
                 <h6 class="text-light">Manila:</h6>
               <address class="mb-2 text-white"> <i class=""> </i> Suite 406, Ermite Center Building 1350 Roxas Blvd., Ermita, Manila, Philippines</address>
               <!-- <p class="mb-2">
                 <a class="link-light text-decoration-none" href="tel:+15057922430">(505) 792-2430</a>
               </p> -->
               <p class="mb-0">
                 <a class="link-light text-decoration-none" href="mailto:smarttravelmanila04@gmail.com">smarttravelmanila04@gmail.com</a>
               </p>
             </div>

             <div class="address-wrapper mt-5">
                 <h6 class="text-light">Korea:</h6>
               <address class="mb-2 text-white">#319, VABIEN 3, 86 Tongil-ro, Jung-gu, Seoul, Korea, 04517</address>
               <p class="mb-2">
                 <a class="link-light text-decoration-none" href="tel:+15057922430">+82-584-3202</a>
               </p>
               <p class="mb-0">
                 <a class="link-light text-decoration-none" href="mailto:smarttourkorea@gmail.com">smarttourkorea@gmail.com</a>
               </p>
             </div>

         </div>
       </div>
     </div>


       <div class="col-12 col-lg-8 bg-light py-4 py-md-5 py-xxl-8">

         <div class="row justify-content-center">

           <div class="col-12 col-md-11 col-xxl-10">
             
             <div class="row gy-4 gy-sm-0">

               <div class="col-6 col-sm-3 me-5">

                 <div class="widget">
                   <h4 class="widget-title mb-4 fw-bold">Services</h4>
                   <ul class="list-unstyled">
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">Travel Tours</a>
                     </li>
                     <!-- <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">Digital Marketing</a>
                     </li>
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">App Development</a>
                     </li>
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">SEO Consultancy</a>
                     </li>
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">Web Design</a>
                     </li>
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">Video Animations</a>
                     </li>
                     <li class="mb-0">
                       <a href="#!" class="link-secondary text-decoration-none">Logo Design</a>
                     </li> -->
                   </ul>
                 </div>

               </div>

               <!-- <div class="col-6 col-sm-3">

                 <div class="widget">
                   <h4 class="widget-title mb-4">Company</h4>
                   <ul class="list-unstyled">
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">About</a>
                     </li>
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">Contact</a>
                     </li>
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">Advertise</a>
                     </li>
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">Events</a>
                     </li>
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">Careers</a>
                     </li>
                     <li class="mb-3">
                       <a href="#!" class="link-secondary text-decoration-none">Terms of Service</a>
                     </li>
                     <li class="mb-0">
                       <a href="#!" class="link-secondary text-decoration-none">Privacy Policy</a>
                     </li>
                   </ul>
                 </div>


               </div> -->


               <div class="col-12 col-sm-6">
                 <div class="widget">
                   <h3 class="widget-title mb-2 fw-bold">For Inquiries/Request</h3>
                     <p class="mb-4">Have questions about our tours? Fill out the form below, and we’ll get back to you as soon as possible!</p>

                     <form action="#!">
                         <div class="row gy-4">
                             <!-- Email in the same row -->
                             <div class="col-md-6">
                                 <!-- <div class="input-group">
                                     <span class="input-group-text" id="name-addon">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                         <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.5 8a5.5 5.5 0 1 1 11 0H2.5Z"/>
                                     </svg>
                                     </span>
                                     <input type="text" class="form-control" id="name" placeholder="Your Name" aria-label="name" aria-describedby="name-addon" required>
                                 </div> -->

                                 <div class="input-group">
                                     <span class="input-group-text" id="email-addon">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                         <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                     </svg>
                                     </span>
                                     <input type="email" class="form-control" id="email" placeholder="Your Email" aria-label="email" aria-describedby="email-addon" required>
                                 </div>
                             </div>
                             
                             <!-- Phone Number -->
                             <div class="col-md-6">
                                 <div class="input-group">
                                     <span class="input-group-text" id="phone-addon">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                         <path d="M3.654 1.328a1.733 1.733 0 0 1 2.377-.195l1.567 1.568c.507.507.574 1.29.217 1.884l-1.025 1.788a.678.678 0 0 0 .145.815l2.431 2.432a.678.678 0 0 0 .815.145l1.788-1.025c.593-.357 1.377-.29 1.884.217l1.568 1.567a1.733 1.733 0 0 1-.195 2.377l-2.494 2.495a2.734 2.734 0 0 1-3.013.564c-3.25-1.511-5.707-3.968-7.219-7.219a2.734 2.734 0 0 1 .564-3.013L3.654 1.328Z"/>
                                     </svg>
                                     </span>
                                     <input type="tel" class="form-control" id="phone" placeholder="Phone" aria-label="phone" aria-describedby="phone-addon">
                                 </div>
                             </div>

                             

                             <!-- Message Field -->
                             <div class="col-md-12">
                                 <div class="input-group">
                                     <span class="input-group-text" id="message-addon">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="25" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                                         <path d="M14 1a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H3.414a1 1 0 0 0-.707.293L1 14V2a1 1 0 0 1 1-1h12Zm-2 5a.5.5 0 0 0 0-1H4a.5.5 0 0 0 0 1h8Zm0 2a.5.5 0 0 0 0-1H4a.5.5 0 0 0 0 1h8Zm0 2a.5.5 0 0 0 0-1H4a.5.5 0 0 0 0 1h8Z"/>
                                     </svg>
                                     </span>
                                     <textarea class="form-control" id="message" placeholder="Your Message" aria-label="message" aria-describedby="message-addon" rows="2" required></textarea>
                                 </div>
                             </div>

                             <!-- Submit Button -->
                             <div class="col-12 mt-5">
                                 <div class="d-grid">
                                     <button class="btn btn-primary" type="submit">Send Inquiry</button>
                                 </div>
                             </div>
                         </div>
                 </form>
             </div>
         </div>


     </div>


     <div class="row mt-5 border-top border-light-subtle">
       <div class="footer-copyright-wrapper mt-2">
         &copy; 2024. All Rights Reserved.
       </div>
       <!-- <div class="credits text-secondary mt-2 fs-7">
         Built by <a href="https://bootstrapbrain.com/" class="link-secondary text-decoration-none">Smart Travel</a>
       </div> -->
     </div>

   </div>
 </div>
</div>


 </div>
</div>

</section>

</footer>


<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="logoutModalLabel">Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to logout?
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="../Client Section/Functions/client-logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</div>

</body>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navbar Button Trigger
    const loginButton = document.getElementById("LoginButton");
    if (loginButton) {
        loginButton.onclick = function () {
            location.href = "login.php";
        };
    } else {
        console.error('LoginButton element not found');
    }
});
</script>

<script>
window.addEventListener('scroll', function() {
  const header = document.querySelector('header');

  // Check if the user has scrolled down
  if (window.scrollY > 0) {
      header.classList.add('scrolled'); // Add the scrolled class
  } else {
      header.classList.remove('scrolled'); // Remove the scrolled class
  }
});
</script>



</html>