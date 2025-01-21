<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-dashboard copy 3.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar copy.css?v=<?php echo time(); ?>">
</head>
<body>


<div class="body-container">
  <?php include "../Agent Section/includes/sidebar copy.php"; ?>

  <div class="main-content-container">
    <div class="navbar">
      <h5>Dashboard</h5>
    </div>

    <div class="main-content">
      <div class="count-containers">
        <div class="count-card">
          <div class="card-header">
            <div class="card-name">
              <h5>Transactions</h5>
            </div>
            
            <!-- <div class="pill-container">
              <ul class="nav nav-pills" id="pills-tab" role="tablist">

                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Current & Pending</button>
                </li>

                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Completed</button>
                </li>
                
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Cancelled</button>
                </li>
                
              </ul>
            </div> -->

          </div>

          <div class="card-body">
            <!-- <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

              </div>

              <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">

              </div>

              <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">


              </div>

              <div class="tab-pane fade" id="pills-disabled" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">


              </div>
            </div> -->
          </div>
        </div>

        <div class="count-card">
          
        </div>

        <div class="count-card">
          
        </div>
        
        <div class="count-card">
          
        </div>
      </div>

    </div>
  </div>




</div>


<?php require "../Agent Section/includes/scripts.php"; ?>

<script>
function toggleSubMenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    const sectionTitle = submenu.previousElementSibling;
    const chevron = sectionTitle.querySelector('.chevron-icon'); 

    // Check if the submenu is already open
    const isOpen = submenu.classList.contains('open');

    // If it's open, we need to close it, and reset the chevron
    if (isOpen) {
        submenu.classList.remove('open');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        // First, close all open submenus and reset all chevrons
        const allSubmenus = document.querySelectorAll('.submenu');
        const allChevrons = document.querySelectorAll('.chevron-icon');
        
        allSubmenus.forEach(sub => {
            sub.classList.remove('open');
        });

        allChevrons.forEach(chev => {
            chev.style.transform = 'rotate(0deg)';
        });

        // Now, open the current submenu and rotate its chevron
        submenu.classList.add('open');
        chevron.style.transform = 'rotate(180deg)';
    }
}


</script>

  </body>
</html>