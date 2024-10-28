<!DOCTYPE html>
<html lang="en">
<head>
    <title>Smart Travel</title>

    <?php include 'includes\head.php' ?>

    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/utilities/font-size/font-size.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/utilities/margin/margin.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/utilities/padding/padding.css">

    <!-- External CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">

    
</head>

<body>
    <header class="sub-header d-flex">
        <div class="info-container d-inline-flex flex-row">
            <a href="tel: +123456789" class="d-flex align-items-center text-decoration-none text-light">
                <i class="fa-solid fa-phone me-2"></i> +123 456 789
            </a>
            <a href="mailto:info@example.com" class="d-flex align-items-center text-decoration-none text-light">
                <i class="fa-solid fa-envelope ps-4 pe-2"></i> info@example.com
            </a>
        </div>
    </header> 

    <?php include 'navbar.php'; ?>

    </header>

    <!-- Section 1 -->
    <div class="section d-flex" id="section1">
        <div class="dark-overlay"></div>

        <div class="container-background"> 
          <div class="background-image bg1"></div>
          <div class="background-image bg2"></div>
          <div class="background-image bg3"></div>
          <div class="background-image bg4"></div>
          <div class="background-image bg5"></div>
          <div class="background-image bg6"></div>
        </div>

         <div class="hero d-flex flex-column align-items-center text-center">
           
         <h3 class="text-pop">Discover the World with <span class="highlight">Smart Travel</span></h3>


         <p class="mb-2 travel-highlight">Your satisfaction is our top priority. Experience travel like never before!</p>

         <div class="banner" id="banner">
                 <h2 class="banner-title">
                     Price Starts at <span style="font-weight: bold;">P34,000.00</span> only<br>
                     <span style="font-size: 16px; font-weight: normal;">(Tour Package: 5 Days, 4 Nights)</span>
                 </h2>
             </div>


           <!-- 5 Star Review -->
           <div class="star-rating mb-1 mt-2">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
           </div>
               <span class="rating-text ms-2 mb-4">(4.5/5 based on 250 reviews)</span>

               



           <div class="btn-container d-flex flex-row justify-content-center">
               <button type="button" class="btn btn-secondary">Book Now</button>
               <button type="button" class="btn btn-outline-secondary p-2 px-3 ms-3 text-light">Learn More</button>
           </div>
        </div>

    </div>
    
    <!-- Why book us? -->
    
    <div class="section d-flex flex-column " id="section2">
    <h2>Why book with us?</h2>
          <p class="lead text-secondary">We provide affordable and unforgettable travel experiences tailored to your needs.</p>
          <div class="card-container row mt-3">
              <div class="col-md-3 mb-4">
                  <div class="card p-3 border-0 shadow text-center"> <!-- Added text-center for card content -->
                      <div class="card-body">
                          <div class="icon my-4 text-primary">
                              <i class="fas fa-plane fa-3x"></i> 
                          </div>
                          <h5 class="card-title">Travel Planning Assistance</h5>
                          <p class="card-text">Our expert team will help you plan every detail of your trip, ensuring a hassle-free and enjoyable experience tailored to your preferences.</p>
                          <a href="#" class="btn btn-primary">Learn More </a>
                      </div>
                  </div>
              </div>
              <div class="col-md-3 mb-4">
                  <div class="card p-3 border-0 shadow text-center">
                      <div class="card-body">
                          <div class="icon my-4 text-primary">
                              <i class="fas fa-map-marker-alt fa-3x"></i> 
                          </div>
                          <h5 class="card-title">Local Experience Guides</h5>
                          <p class="card-text">Discover hidden gems and local hotspots with our knowledgeable guides, providing you with authentic experiences that go beyond typical tourist attractions.</p>
                          <a href="#" class="btn btn-primary">Learn More </a>
                      </div>
                  </div>
              </div>
              <div class="col-md-3 mb-4">
                  <div class="card p-3 border-0 shadow text-center">
                      <div class="card-body">
                          <div class="icon my-4 text-primary">
                              <i class="fas fa-tags fa-3x"></i> 
                          </div>
                          <h5 class="card-title">Exclusive Deals and Offers</h5>
                          <p class="card-text">Take advantage of our exclusive discounts and packages, ensuring you get the best value for your travel adventures. Book now and enjoy special rates for early bookings!</p>
                          <a href="#" class="btn btn-primary">Learn More</a>
                      </div>
                  </div>
              </div>
              <div class="col-md-3 mb-4">
                  <div class="card p-3 border-0 shadow text-center">
                      <div class="card-body">
                          <div class="icon my-4 text-primary">
                              <i class="fas fa-suitcase-rolling fa-3x"></i> 
                          </div>
                          <h5 class="card-title">Personalized Travel Experiences</h5>
                          <p class="card-text">We customize your travel itinerary based on your interests, ensuring a unique and memorable journey tailored just for you. Let us help you explore the world your way!</p>
                          <a href="#" class="btn btn-primary">Learn More</a>
                      </div>
                  </div>
              </div>
          </div>
    </div>

   
    <!-- Activities -->
    <div class="section d-flex flex-column" id="section3"> 
    <div class="destination-slider-container">
        <div class="destination-slider-header">
            <h1 class="section-title">Featured Destinations</h1>
            <div class="tabs">
                <span class="tab active" data-region="seoul">Seoul</span>
                <span class="tab" data-region="busan">Busan</span>
                <span class="tab" data-region="jeju">Jeju Island</span>
            </div>
        </div>

        <div class="destination-slider">
            <div class="destination-slider-content">
                <!-- Seoul Destinations -->
                <div class="destination-slider-item" data-region="seoul">
                    <img src="assets\images\Places in Korea\Seoul\seoul_tower.jpg" alt="N Seoul Tower">
                    <div class="caption">
                        <h3>N Seoul Tower</h3>
                        <p>Experience panoramic views of the city</p>
                    </div>
                </div>
                <div class="destination-slider-item" data-region="seoul">
                    <img src="assets\images\Places in Korea\Seoul\gyeongbokgung.jpg" alt="Gyeongbokgung Palace">
                    <div class="caption">
                        <h3>Gyeongbokgung Palace</h3>
                        <p>Discover the rich history of Korea</p>
                    </div>
                </div>
                <div class="destination-slider-item" data-region="seoul">
                    <img src="assets\images\Places in Korea\Seoul\bukchon_hanok_village.jpg" alt="Bukchon Hanok Village">
                    <div class="caption">
                        <h3>Bukchon Hanok Village</h3>
                        <p>Immerse yourself in traditional Korean culture</p>
                    </div>
                </div>

                <!-- Busan Destinations -->
                <div class="destination-slider-item" data-region="busan">
                    <img src="assets\images\Places in Korea\Busan\haedong_yonggungsa.jpg" alt="Haedong Yonggungsa Temple">
                    <div class="caption">
                        <h3>Haedong Yonggungsa Temple</h3>
                        <p>A stunning seaside temple</p>
                    </div>
                </div>
                <div class="destination-slider-item" data-region="busan">
                    <img src="assets\images\Places in Korea\Busan\ghwangan_bridge.jpg" alt="Gwangalli Beach">
                    <div class="caption">
                        <h3>Gwangalli Beach</h3>
                        <p>Enjoy beautiful beaches and nightlife</p>
                    </div>
                </div>
                <div class="destination-slider-item" data-region="busan">
                    <img src="assets\images\Places in Korea\Busan\jagalchi_market.jpg" alt="Jagalchi Fish Market">
                    <div class="caption">
                        <h3>Jagalchi Fish Market</h3>
                        <p>Sample fresh seafood and local delicacies</p>
                    </div>
                </div>

                <!-- Jeju Island Destinations -->
                <div class="destination-slider-item" data-region="jeju">
                    <img src="assets\images\Places in Korea\Jeju Island\hallasan.jpg" alt="Hallasan Mountain">
                    <div class="caption">
                        <h3>Hallasan Mountain</h3>
                        <p>Hike to the highest peak in South Korea</p>
                    </div>
                </div>
                <div class="destination-slider-item" data-region="jeju">
                    <img src="assets/images/Places in Korea/Jeju Island/jeongbang_waterfall.jpg" alt="Jeongbang Waterfall">
                    <div class="caption">
                        <h3>Jeongbang Waterfall</h3>
                        <p>Witness nature's beauty in Jeju</p>
                    </div>
                </div>
                <div class="destination-slider-item" data-region="jeju">
                    <img src="assets\images\Places in Korea\Jeju Island\seongsan_ilchulbong.jpg" alt="Seongsan Ilchulbong">
                    <div class="caption">
                        <h3>Seongsan Ilchulbong</h3>
                        <p>Catch a breathtaking sunrise at this volcanic peak</p>
                    </div>
                </div>
            </div>

            <div class="circle-container">
               <div class="arrow left">&#10094;</div>
               <div class="arrow right">&#10095;</div>
            </div>
            
        </div>
    </div>
</div>


    <script>
     const destinationSliderContent = document.querySelector('.destination-slider-content');
     const destinationSliderItems = document.querySelectorAll('.destination-slider-item');
     const totalItems = destinationSliderItems.length;
     let currentIndex = 0;

     // Tabs and Arrows
     const tabs = document.querySelectorAll('.tab');
     const leftArrow = document.querySelector('.arrow.left');
     const rightArrow = document.querySelector('.arrow.right');

     // Function to show the current slide
     function showSlide(index) {
       const slideWidth = destinationSliderItems[0].offsetWidth;
       destinationSliderContent.style.transform = `translateX(-${index * slideWidth}px)`;
       updateActiveTab(destinationSliderItems[index].dataset.region);
     }

     // Function to update the active tab
     function updateActiveTab(region) {
       tabs.forEach(tab => {
         tab.classList.remove('active');
         if (tab.dataset.region === region) {
           tab.classList.add('active');
         }
       });
     }

     // Arrow controls (left/right)
     rightArrow.addEventListener('click', () => {
       currentIndex = (currentIndex + 1) % totalItems;
       showSlide(currentIndex);
     });

     leftArrow.addEventListener('click', () => {
       currentIndex = (currentIndex - 1 + totalItems) % totalItems;
       showSlide(currentIndex);
     });

     // Auto slider
     let autoSlideInterval = setInterval(() => {
       currentIndex = (currentIndex + 1) % totalItems;
       showSlide(currentIndex);
     }, 6000);

     // Function to filter items by region and reset the slider to the first item of that region
     function filterSlidesByRegion(region) {
       const visibleItems = Array.from(destinationSliderItems).filter(item => item.dataset.region === region);
       
       // Update the totalItems to be the count of visible items for the selected region
       totalVisibleItems = visibleItems.length;

       // Adjust the index if needed
       if (visibleItems.length > 0) {
         currentIndex = Array.from(destinationSliderItems).indexOf(visibleItems[0]);
         showSlide(currentIndex);
       }
     }

     // Tab click functionality
     tabs.forEach(tab => {
       tab.addEventListener('click', function() {
         const region = this.dataset.region;
         
         // Clear interval for auto-slide when manually clicked
         clearInterval(autoSlideInterval);
         
         // Filter slides based on the selected tab
         filterSlidesByRegion(region);
         
         // Restart auto slider after manual click
         autoSlideInterval = setInterval(() => {
           currentIndex = (currentIndex + 1) % totalItems;
           showSlide(currentIndex);
         }, 6000);
       });
     });

     // Initial setup - show the first slide
     showSlide(currentIndex);

    </script>

     <!-- Packages Offered -->
     <div class="section d-flex flex-column" id="section4"> 
         <div class="package-container">
             <div class="container-header d-flex flex-row mb-3">
                 <div class="left-side me-3">
                     <h5>Packages Offered</h5>
                     <h3 class="mt-3">Discover Amazing Destinations</h3>
                     <p>Experience the world’s top destinations with our tailored travel packages. From vibrant cities to serene landscapes, enjoy guided tours, exclusive deals, and personalized itineraries designed to make your trip unforgettable.</p>
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
                                     <img src="assets/images/Places in Korea/Jeju Island/jeongbang_waterfall.jpg"  alt="Jeongbang Waterfall">
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
                                   <img src="assets\images\Places in Korea\Jeju Island\seongsan_ilchulbong.jpg"  alt="Image 2">
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
                                 <img src="assets\images\Places in Korea\Jeju Island\hallasan.jpg"  alt="Image 3">
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

         </div>
     </div>

    



     <!-- Packages Offered -->
     <div class="section" id="section5"> 
          

     </div>

    


    <!-- <div class="hero-slider">
        
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
            
    </div> -->

  <footer class="footer">
    <section>
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-lg-4 bg-dark py-4 py-md-5 py-xxl-8">
            <div class="row h-100 align-items-end justify-content-center">

              <div class="col-12 col-md-11 col-xl-10">

                <div class="footer-logo-wrapper">
                  <a href="#!">
                    <img src="assets/images/logo.png" alt="Smart Travel Logo" width="300" height="60">
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
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn btn-primary" type="submit">Send Inquiry</button>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>


        </div>


        <div class="row mt-6 border-top border-light-subtle">
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
