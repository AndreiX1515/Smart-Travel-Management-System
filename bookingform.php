<?php 
  session_start(); 
  require "conn.php";
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
          <div class="card mt-4 guest-form shadow-sm">
            <div class="card-header bg-primary text-white">
              <h4 class="mb-0">Guest Information</h4>
              <button class="btn btn-sm btn-outline-light float-end" type="button" data-bs-toggle="collapse" data-bs-target="#cardBodyContent" aria-expanded="true" aria-controls="cardBodyContent">
                Toggle
              </button>
            </div>

            <div id="cardBodyContent" class="card-body collapse show">
              <div class="main-form mt-3 border-bottom pb-3">
                
                <!-- Personal Information Group -->
                <div class="row mb-3">
                  <h5 class="mb-3">Personal Information</h5>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="fName">First Name</label>
                      <input type="text" name="fName[]" class="form-control" placeholder="Enter First Name" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="lName">Last Name</label>
                      <input type="text" name="lName[]" class="form-control" placeholder="Enter Last Name" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="mName">Middle Name</label>
                      <input type="text" name="mName[]" class="form-control" placeholder="Enter Middle Name (Optional)">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="suffix">Suffix</label>
                      <select class="form-select" name="suffix[]">
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

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="age">Age</label>
                      <input type="number" name="age[]" class="form-control" placeholder="Enter Age" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="birthdate">Birthdate</label>
                      <input type="date" name="birthdate[]" class="form-control" required>
                    </div>
                  </div>
                </div>

                <!-- Contact Information Group -->
                <div class="row mb-3">
                  <h5 class="mb-3">Contact Information</h5>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="contactNo">Contact No.</label>
                      <input type="text" name="contactNo[]" class="form-control" placeholder="Enter Contact No" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="email">Email</label>
                      <input type="email" name="email[]" class="form-control" placeholder="Enter Email Address" required>
                    </div>
                  </div>
                </div>
                
                <!-- Address Information Group -->
                <div class="row mb-3">
                  <h5 class="mb-3">Address Information</h5>

                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label for="houseNo">House No.</label>
                      <input type="text" name="houseNo[]" class="form-control" placeholder="Enter House No" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label for="street">Street</label>
                      <input type="text" name="street[]" class="form-control" placeholder="Enter Street (Optional)">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label for="subdivision">Subdivision</label>
                      <input type="text" name="subdivision[]" class="form-control" placeholder="Enter Subdivision (Optional)">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label for="barangay">Barangay</label>
                      <input type="text" name="barangay[]" class="form-control" placeholder="Enter Barangay" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label for="city">City</label>
                      <input type="text" name="city[]" class="form-control" placeholder="Enter City" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label for="country">Country</label>
                      <input type="text" name="country[]" class="form-control" placeholder="Enter Country" required>
                    </div>
                  </div>
                </div>

                <!-- Flight Details Group -->
                <div class="row mb-3">
                  <h5 class="mb-3">Flight Information</h5>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="passportNo">Passport No.</label>
                      <input type="text" name="passportNo[]" class="form-control" placeholder="Enter Passport No" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="origin">Origin</label>
                      <select class="form-select" id="origin" name="origin[]" required>
                        <option selected disabled>Select Origin</option>
                        <?php
                          $sql1 = mysqli_query($conn, "Select Distinct origin from flight order by origin asc");
                          while($res1 = mysqli_fetch_array($sql1)) {
                            ?>
                            <option value="<?php echo $res1['origin']; ?>"><?php echo $res1['origin']; ?></option>
                            <?php
                          }
                        ?>
                      </select>
                    </div>
                  </div>

                  <!-- <div class="col-md-6">
                    <div class="form-group mb-2">
                      <label for="flightName">Flight</label>
                      <select id="enhancedDropdown" class="form-select" name="flightName[]">
                        <option selected disabled>Select Flight</option>
                        <option value="MNL - ICN">MNL - ICN</option>
                        <option value="ICN - MNL">ICN - MNL</option>
                      </select>
                    </div>
                  </div> -->

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="outboundFlight">Outbound Flight</label>
                      <select class="form-select" id="outboundFlight" name="outboundFlight[]" required>
                        <option selected disabled>Select Outbound Flight</option>
                        <?php
                          $sql1 = mysqli_query($conn, "
                            SELECT CONCAT(
                              DATE_FORMAT(flightDepartureDate, '%M %d, %Y'), ' ', 
                              TIME_FORMAT(flightDepartureTime, '%H:%i'), ' - ', 
                              DATE_FORMAT(flightArrivalDate, '%M %d, %Y'), ' ', 
                              TIME_FORMAT(flightArrivalTime, '%H:%i')) AS onboardFlightSched, 
                              CONCAT(
                              DATE_FORMAT(returnDepartureDate, '%M %d, %Y'), ' ', 
                              TIME_FORMAT(returnDepartureTime, '%H:%i'), ' - ', 
                              DATE_FORMAT(returnArrivalDate, '%M %d, %Y'), ' ', 
                              TIME_FORMAT(returnArrivalTime, '%H:%i')) AS returnFlightSched 
                            FROM flight");

                          while($res1 = mysqli_fetch_array($sql1)) 
                          {
                            ?>
                              <option value="<?php echo $res1['onboardFlightSched']; ?>" 
                                data-return-sched="<?php echo $res1['returnFlightSched']; ?>">
                                <?php echo $res1['onboardFlightSched']; ?>
                              </option>
                            <?php
                          }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="returnFlight">Return Flight</label>
                      <input id="returnFlight" name="returnFlight[]" class="form-control" readonly>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="packageName">Package</label>
                      <input type="text" id="packageName"name="packageName[]" class="form-control" placeholder="Package" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="paste-new-forms"></div>

          <button type="submit" name="bookNow" class="btn btn-primary mt-3">Book Now</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() 
    {
      const formTemplate = document.querySelector('.guest-form').cloneNode(true);

      // Add more forms when "Add More" button is clicked
      document.querySelector('.add-more-form').addEventListener('click', function() 
      {
        const newForm = formTemplate.cloneNode(true);
        const uniqueId = Date.now();

        // Update the unique IDs for the new form's fields
        newForm.querySelector('#outboundFlight').id = 'outboundFlight' + uniqueId;
        newForm.querySelector('#returnFlight').id = 'returnFlight' + uniqueId;

        // Update the collapse functionality to work for each new card
        newForm.querySelector('.btn-outline-primary').setAttribute('data-bs-target', '#newCard' + uniqueId);
        newForm.querySelector('.collapse').id = 'newCard' + uniqueId;
        newForm.querySelector('.btn-outline-primary').classList.remove('show');
        newForm.querySelector('.collapse').classList.remove('show');

        // Add the remove button to the new form
        newForm.querySelector('.card-header').innerHTML += '<button class="remove-btn btn btn-danger float-end">Remove</button>';
        document.querySelector('.paste-new-forms').append(newForm);

        // Add event listener to the remove button
        newForm.querySelector('.remove-btn').addEventListener('click', function() 
        {
          newForm.remove();
        });

        // Add event listener to dynamically update the return flight for this specific form
        newForm.querySelector('#outboundFlight' + uniqueId).addEventListener('change', function() 
        {
            // Get the selected option
            const selectedFlight = this.options[this.selectedIndex];
            // Get the return flight time from the selected option
            const returnFlightSched = selectedFlight.getAttribute('data-return-sched');
            // Set the return flight input value
            newForm.querySelector('#returnFlight' + uniqueId).value = returnFlightSched;
        });
      });

        // Initial form - set event listener for return flight update
      document.getElementById('outboundFlight').addEventListener('change', function() 
      {
        const selectedFlight = this.options[this.selectedIndex];
        const returnFlightSched = selectedFlight.getAttribute('data-return-sched');
        document.getElementById('returnFlight').value = returnFlightSched;
      });
    });
</script>



  
</body>
</html>
