<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Account - Agent</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Admin Section/assets/css/admin-addAccountAgent.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Admin Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>

  <div class="body-container">
    <?php include "../Admin Section/includes/sidebar.php"; ?>

    <div class="main-content-container">
      <div class="navbar">
        <h5 class="title-page">Add Account - Agent</h5>
      </div>

      <div class="main-content">
        <div class="content-container">
          <form id="addAccountForm">

            <div class="card mb-3">
              <div class="card-header">
                Personal Information
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" required>
                  </div>
                  <div class="col-md-4">
                    <label for="middleName" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middleName" name="middleName">
                  </div>
                  <div class="col-md-4">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" required>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                  </div>
                  <div class="col-md-6">
                    <label for="contactNo" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contactNo" name="contactNo" required>
                  </div>
                </div>
              </div>
              
            </div>

            <div class="card mb-3">
              <div class="card-header">Account Information</div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <label for="accountType" class="form-label">Account Type</label>
                    <select class="form-select" id="accountType" name="accountType" required>
                      <option value="admin">Admin</option>
                      <option value="agent">Agent</option>
                      <option value="employee">Employee</option>
                      <option value="guest">Guest</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="accountStatus" class="form-label">Account Status</label>
                    <select class="form-select" id="accountStatus" name="accountStatus" required>
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>


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