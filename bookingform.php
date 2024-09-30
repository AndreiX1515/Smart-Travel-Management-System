<?php 
  session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome Icon Kit CDN (stable version) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  
  <title>Sample Booking</title>
  
  <!-- Custom CSS -->
  <style>
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    h4 {
      margin: 20px 0;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <?php 
          if(isset($_SESSION['status'])):
        ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Hey!</strong> <?= $_SESSION['status']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php 
          unset($_SESSION['status']);
          endif;
        ?>

        <h4>Guest Booking Information
          <button class="add-more-form float-end btn btn-primary">ADD MORE</button>
        </h4>

        <form action="bookingform-code.php" method="POST">
          <div class="card mt-4 guest-form">
            <div class="card-header">
              <h4>Guest Information</h4>
              <button class="btn btn-sm btn-outline-primary float-end" type="button" data-bs-toggle="collapse" data-bs-target="#cardBodyContent" aria-expanded="true" aria-controls="cardBodyContent">
                Toggle
              </button>
            </div>

            <div id="cardBodyContent" class="card-body collapse show">
              <div class="main-form mt-3 border-bottom">
                
                <!-- Personal Information Group -->
                <div class="row mb-3">
                  <h5>Personal Information</h5>
                  
                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="fName">First Name</label>
                      <input type="text" name="fName[]" class="form-control" placeholder="Enter First Name">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="lName">Last Name</label>
                      <input type="text" name="lName[]" class="form-control" placeholder="Enter Last Name">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="mName">Middle Name</label>
                      <input type="text" name="mName[]" class="form-control" placeholder="Enter Middle Name (Optional)">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="suffix">Suffix</label>
                      <select id="enhancedDropdown" class="form-select" name="suffix[]">
                        <option value="">Select Suffix</option>
                        <option value="Jr.">Jr.</option>
                        <option value="Sr.">Sr.</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                        <option value="V">V</option>
                        <option value="None">None</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="age">Age</label>
                      <input type="number" name="age[]" class="form-control" placeholder="Enter Age">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="birthdate">Birthdate</label>
                      <input type="date" name="birthdate[]" class="form-control">
                    </div>
                  </div>
                </div>

                <!-- Contact Information Group -->
                <div class="row mb-3">
                  <h5>Contact Information</h5>
                  
                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="contactNo">Contact No.</label>
                      <input type="text" name="contactNo[]" class="form-control" placeholder="Enter Contact No">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="email">Email</label>
                      <input type="email" name="email[]" class="form-control" placeholder="Enter Email Address">
                    </div>
                  </div>
                </div>
                
                <!-- Address Information Group -->
                <div class="row mb-3">
                  <h5>Address Information</h5>
                  
                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="houseNo">House No.</label>
                      <input type="text" name="houseNo[]" class="form-control" required placeholder="Enter House No">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="street">Street</label>
                      <input type="text" name="street[]" class="form-control" placeholder="Enter Street (Optional)">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="subdivision">Subdivision</label>
                      <input type="text" name="subdivision[]" class="form-control" placeholder="Enter Subdivision (Optional)">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="barangay">Barangay</label>
                      <input type="text" name="barangay[]" class="form-control" required placeholder="Enter Barangay">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="city">City</label>
                      <input type="text" name="city[]" class="form-control" required placeholder="Enter City">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-2">
                      <label for="country">Country</label>
                      <input type="text" name="country[]" class="form-control" required placeholder="Enter Country">
                    </div>
                  </div>
                </div> 

                <!-- Flight Details Group -->
                <div class="row mb-3">
                  <h5>Flight Information</h5>

                  <div class="col-md-6">
                    <div class="form-group mb-2">
                      <label for="passportNo">Passport No.</label>
                      <input type="text" name="passportNo[]" class="form-control" placeholder="Enter Passport No">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-2">
                      <label for="visaStatus">Visa Status</label>
                      <select id="enhancedDropdown" class="form-select" name="visaStatus[]">
                        <option selected disabled>Select Visa Status</option>
                        <option value="available">Available</option>
                        <option value="no">No</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-2">
                      <label for="packageName">Package</label>
                      <select id="enhancedDropdown" class="form-select" name="packageName[]">
                        <option selected disabled>Select Package</option>
                        <option value="Winter Tour Package">Winter Tour Package</option>
                        <option value="Spring Tour Package">Spring Tour Package</option>
                        <option value="Summer Tour Package">Summer Tour Package</option>
                        <option value="Autumn Tour Package">Autumn Tour Package</option>
                        <option value="Cherry Blossom Tour Package">Cherry Blossom Tour Package</option>
                        <option value="Regular Tour Package">Regular Tour Package</option>
                        <option value="Busan Tour Package">Busan Tour Package</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-2">
                      <label for="flightName">Flight</label>
                      <select id="enhancedDropdown" class="form-select" name="flightName[]">
                        <option selected disabled>Select Flight</option>
                        <option value="MNL - ICN">MNL - ICN</option>
                        <option value="ICN - MNL">ICN - MNL</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-2">
                      <label for="totalPrice">Total Price</label>
                      <input type="text" name="totalPrice[]" class="form-control" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="paste-new-forms"></div>

          <button type="submit" name="bookNow" class="btn btn-primary mt-3">Book now</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const formTemplate = document.querySelector('.guest-form').cloneNode(true);
      document.querySelector('.add-more-form').addEventListener('click', function() {
        const newForm = formTemplate.cloneNode(true);
        newForm.querySelector('.btn-outline-primary').setAttribute('data-bs-target', '#newCard' + Date.now());
        newForm.querySelector('.collapse').id = 'newCard' + Date.now();
        newForm.querySelector('.btn-outline-primary').classList.remove('show');
        newForm.querySelector('.collapse').classList.remove('show');
        
        newForm.querySelector('.card-header').innerHTML += '<button class="remove-btn btn btn-danger float-end">Remove</button>';
        document.querySelector('.paste-new-forms').append(newForm);
        
        newForm.querySelector('.remove-btn').addEventListener('click', function() {
          newForm.remove();
        });
      });
    });
  </script>
</body>
</html>
