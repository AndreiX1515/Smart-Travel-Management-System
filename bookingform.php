<?php 
  include 'session_validate.php';
  require "conn.php";
  
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Add this in the <head> or before </body> -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <!-- Font Awesome Icon Kit CDN (stable version) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <title>Flight Booking</title>
  
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

        <div class="header-container d-flex flex-row align-items-center justify-content-between w-100 my-2 px-3">
          <h4>Flight Booking</h4>
          <button class="add-more-form btn btn-primary"><i class="fa-solid fa-plus"></i></button>
        </div>


        <form action="bookingform-code.php" method="POST">
          <div class="card">
              <div class="card-header bg-secondary text-white text-light">
                <h4 class="my-2 px-2">Flight Details</h4>
              </div>

              <div class="card-body p-4">

                <div class="row">

                  <div class="col-md-6 mb-3">
                    <div class="form-group mb-6">
                      <label for="agent">Select Agent <span class="text-danger fw-bold">*</span></label>

                      <select class="form-select mt-2" id="agentId" name="agentId" required>
                        <option selected disabled>Select Agent</option>
                        <option value="">None</option>
                        <?php
                          $sql1 = mysqli_query($conn, "SELECT agentId, CONCAT(lName, ', ', fName, 
                            CASE 
                              WHEN mName != '' THEN CONCAT(' ', SUBSTRING(mName, 1, 1), '.') 
                              ELSE '' 
                            END) AS agentName FROM agent ORDER BY lName ASC");
                          while($res1 = mysqli_fetch_array($sql1)) {
                            echo "<option value='{$res1['agentId']}'>{$res1['agentName']}</option>";
                          }
                        ?>
                      </select>
                      
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-6">

                      <label for="packageName">Package <span class="text-danger fw-bold">*</span></label>

                      <select class="form-select mt-2" id="packageName" name="packageName" required>
                        <option selected disabled>Select Package</option>
                        <?php
                          $sql1 = mysqli_query($conn, "SELECT DISTINCT packageId, packageName FROM package ORDER BY packageName ASC");
                          while($res1 = mysqli_fetch_array($sql1)) {
                            echo "<option value='{$res1['packageId']}'>{$res1['packageName']}</option>";
                          }
                        ?>
                      </select>

                    </div>
                  </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                      <div class="form-group mb-6">
                        <label class="mb-2" for="origin">Origin <span class="text-danger fw-bold">*</span></label>
                        <select class="form-select" id="origin" name="origin" required>
                          <option selected disabled>Select Origin</option>
                        </select>
                      </div>
                  </div>

                    <div class="col-md-6">
                      <div class="form-group mb-6">
                        <label class="mb-2" for="outboundFlight">Flight Date <span class="text-danger fw-bold">*</span></label>
                        <select class="form-select" id="outboundFlight" name="outboundFlight" required>
                          <option selected disabled>Select Flight Available Dates</option>
                        </select>
                      </div>
                    </div>

                </div>

                  <div class="row">

                  <div class="form-group">
                    <label for="modeOfPayment">Mode of Payment:</label>
          
                    <!-- Radio Buttons for Mode of Payment -->
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="paymentMethod" id="bank" value="bank" onchange="showPaymentDetails(this.value)" required>
                      <label class="form-check-label" for="bank">Bank Transfer</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="paymentMethod" id="gcash" value="gcash" onchange="showPaymentDetails(this.value)" required>
                      <label class="form-check-label" for="gcash">GCash</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" value="creditCard" onchange="showPaymentDetails(this.value)" required>
                      <label class="form-check-label" for="creditCard">Credit Card</label>
                    </div>
                  </div>

                <!-- Dynamic Payment Details Section -->
                <div id="paymentDetails" class="mt-4"></div>

                    <div class="col-md-6">
                      <div class="form-group mb-6">
                        <input type="hidden" id="returnFlight" name="returnFlight" class="form-control" readonly>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-6">
                        <input type="hidden" id="flightId" name="flightId" value="">
                      </div>
                    </div>
                  </div>
              </div>

              <div class="card-footer">
                <h2>
                  <label for="">Price: ₱ 
                    <input style="border: none; outline: none;" id="flightPrice" name="flightPrice" value="0.00" readonly>
                  </label> 
                </h2>
              </div>
          </div>

            <!-- Guest Information Card -->
            <div class="card mt-4 guest-form shadow-sm">

              <div class="card-header bg-secondary text-white">
                  <h4 class="mb-3 font-weight-bold">Guest Information 1</h4>
                  <button class="btn btn-sm btn-outline-light float-end" type="button" data-bs-toggle="collapse" data-bs-target="#cardBodyContent" aria-expanded="true" aria-controls="cardBodyContent">
                    Toggle
                  </button>
              </div>

              <input type="hidden" name="accId" value="<?php echo $_SESSION['accountid']; ?>">

              <div id="cardBodyContent" class="card-body collapse show">
                <div class="main-form mt-3">
                  
                  <!-- Personal Information Group -->

                  <div class="header-container d-flex flex-row w-100 mb-3">
                    <h5 class="card-title bg-primary text-white p-3 w-100">Personal Information</h5>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-3">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="fName">First Name <span class="text-danger fw-bold">*</span></label>
                        <input type="text" name="fName[]" class="form-control" placeholder="Enter First Name" required>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="lName">Last Name <span class="text-danger fw-bold">*</span> </label>
                        <input type="text" name="lName[]" class="form-control" placeholder="Enter Last Name" required>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="mName">Middle Name</label>
                        <input type="text" name="mName[]" class="form-control" placeholder="Enter Middle Name (Optional)">
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="suffix">Suffix</label>
                        <select class="form-select" name="suffix[]">
                          <option selected disabled>Select Suffix</option>
                          <option value="Jr.">Jr.</option>
                          <option value="Sr.">Sr.</option>
                          <option value="II">II</option>
                          <option value="III">III</option>
                          <option value="IV">IV</option>
                          <option value="V">V</option>
                          <option value="">None</option>
                        </select>
                      </div>
                    </div>
                    

                    <div class="col-md-3">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="birthdate">Birthdate <span class="text-danger fw-bold">*</span> </label>
                        <input type="date" name="birthdate[]" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="age">Age <span class="text-danger fw-bold">*</span> </label>
                        <input type="number" name="age[]" class="form-control" placeholder="Enter Age" required>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="sex">Sex <span class="text-danger fw-bold">*</span> </label>
                        <select class="form-select" name="sex[]" required>
                          <option selected disabled>Select Sex</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>
                      </div>
                    </div>
            
                    <div class="col-md-3">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="nationality">Nationality <span class="text-danger fw-bold">*</span> </label>
                        <select class="form-select" name="nationality[]" required>
                          <option selected disabled>Select Nationality</option>
                          <option value="Chinese">Chinese</option>
                          <option value="Filipino">Filipino</option>
                          <option value="Japanese">Japanese</option>
                          <option value="Korean">Korean</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="passportNo">Passport No. <span class="text-danger fw-bold">*</span></label>
                        <input type="text" name="passportNo[]" class="form-control" placeholder="Enter Passport No" required>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="passportExp">Date of Expiration: <span class="text-danger fw-bold">*</span></label>
                        <input type="date" name="passportExp[]" class="form-control" required>
                      </div>
                    </div>

                  </div>

                  <!-- Contact Information Group -->
                  <div class="row mb-3 ">
                    <div class="header-container d-flex flex-row w-100 mb-3 ">
                      <h5 class="card-title bg-primary text-white p-3 w-100">Contact Information</h5>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="contactNo">Contact No. <span class="text-danger fw-bold">*</span></label>
                        <input type="text" name="contactNo[]" class="form-control" placeholder="Enter Contact No" required>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="email">Email <span class="text-danger fw-bold">*</span></label>
                        <input type="email" name="email[]" class="form-control" placeholder="Enter Email Address" required>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Address Information Group -->
                <div class="row mb-3">
                  <div class="header-container d-flex flex-row w-100 mb-3">
                    <h5 class="card-title bg-primary text-white p-3 w-100">Address Information</h5>
                  </div>

                    <div class="col-md-2">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="houseNo">House No. <span class="text-danger fw-bold">*</span></label>
                        <input type="text" name="houseNo[]" class="form-control" placeholder="Enter House No" required>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="street">Street</label>
                        <input type="text" name="street[]" class="form-control" placeholder="Enter Street (Optional)">
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="subdivision">Subdivision</label>
                        <input type="text" name="subdivision[]" class="form-control" placeholder="Enter Subdivision (Optional)">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="barangay">Barangay <span class="text-danger fw-bold">*</span></label>
                        <input type="text" name="barangay[]" class="form-control" placeholder="Enter Barangay" required>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="city">City <span class="text-danger fw-bold">*</span></label>
                        <input type="text" name="city[]" class="form-control" placeholder="Enter City" required>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group mb-3">
                        <label class="mb-2" for="country">Country <span class="text-danger fw-bold">*</span></label>
                        <select class="form-select" name="country[]" required>
                          <option selected disabled>Select Country</option>
                          <option value="China">China</option>
                          <option value="Japan">Japan</option>
                          <option value="Korea">Korea</option>
                          <option value="Philippines">Philippines</option>
                        </select>
                      </div>
                    </div>
                </div>
                
              </div>
            </div>
          </div>

          <div class="paste-new-forms"></div>

          <div class="my-4">
            <div class="card mt-2 ">

              <div class="card-header d-flex justify-content-between align-items-center py-4">
                <h5 class="align-items-center pt-2 fw-bolder">Total Price: ₱ <span id="displayTotalPrice">0</span></h5>
                <button type="submit" name="bookNow" class="btn btn-primary p-2 px-3">Book Now</button>
              </div>
              
              <input type="hidden" id="totalPrice" name="totalPrice">
                        
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>


  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


  <script src="heartbeat.js"></script>
  <script>
    $(document).ready(function () 
    {
      let flightPricePerGuest = 0; // Initialize flight price per guest

      // Adding more guest forms dynamically
      $('.add-more-form').click(function () {
          var guestForm = $('.guest-form:first').clone(); // Clone only personal information
          var formCount = $('.guest-form').length + 1; // Count the total number of forms

          // Reset the values in the cloned form
          guestForm.find('input').val(''); // Reset input fields for personal info
          guestForm.find('select').prop('selectedIndex', 0); // Reset select fields
          guestForm.find('.card-body').removeClass('show'); // Collapse the newly added form

          // Change the header for the new guest form
          guestForm.find('.card-header h4').text('Guest Information ' + formCount);

         // Create a remove button
          const removeButton = $('<button type="button" class="remove-guest btn btn-danger mt-2">Remove Guest</button>');

          // Find the toggle button (assuming you have a class for it, e.g., 'toggle-button')
          const toggleButton = guestForm.find('.toggle-button'); // Replace with the actual selector for your toggle button

          // Append the title in the card header (if not already done)
          guestForm.find('.card-header h4').text('Guest Information ' + formCount);

          // Set the card header to use flexbox for layout
          guestForm.find('.card-header').css('display', 'flex').css('justify-content', 'space-between').css('align-items', 'center');

          // Append the toggle button first, then the remove button to keep them close together
          guestForm.find('.card-header').append(toggleButton, removeButton); // Reverse their positions

          // Remove margin for the remove button to ensure they are close together
          removeButton.css('margin', '0'); // No margin for closer alignment
          toggleButton.css('margin', '0'); // Ensure no margin on toggle button

          // Generate a unique ID for the card body
          var uniqueId = 'cardBodyContent' + formCount;
          guestForm.find('.card-body').attr('id', uniqueId); // Set unique ID for the card body

          // Update the toggle button's data-target attribute
          guestForm.find('.btn[data-bs-toggle="collapse"]').attr('data-bs-target', '#' + uniqueId);

          

          // Add the new form to the container and show it with a slide-down effect
          guestForm.hide().appendTo('.paste-new-forms').slideDown();
      });

    
      // Remove guest form dynamically
      $(document).on('click', '.remove-guest', function () 
      {
        $(this).closest('.guest-form').slideUp(function () 
        {
          $(this).remove(); // Remove the form after sliding up
          calculateTotalPrice(); // Recalculate total price after removing a form
        });
      });

      // Flight selection logic (single selection, applies to all guests)
      $('#packageName').on('change', function () 
      {
        var packageId = $(this).val();
        $('#origin').html('<option selected disabled>Select Origin</option>'); // Clear origin field
        $('#outboundFlight').html('<option selected disabled>Select Flight Available Dates</option>'); // Clear outbound flight field
        $('#returnFlight').val(''); // Clear return flight field
        $('#flightId').val(''); // Clear Flight Id field
        $('#flightPrice').val('0.00'); // Clear Flight Price field
        $('#displayTotalPrice').text('0.00'); // Clear Total Price field
        $('#totalPrice').val(''); // Clear Total Price Input field

        if (packageId) 
        {
          $.ajax(
          {
            url: 'fetchSelect.php',
            type: 'POST',
            data: { packageId: packageId },
            success: function (response) 
            {
              console.log(response); // Debugging the response
              $('#origin').html(response); // Update the origin dropdown
            },
            error: function (xhr, status, error) 
            {
              console.error('Error fetching origins:', error); // Log the error to console
            }
          });
        } 
        else 
        {
          $('#origin').html('<option selected disabled>Select Origin</option>');
        }
      });

      // When origin is selected, populate the outbound flights
      $('#origin').on('change', function () 
      {
        var packageId = $('#packageName').val();
        var origin = $(this).val();

        $('#outboundFlight').html('<option selected disabled>Select Flight Available Dates</option>'); // Clear outbound flight field
        $('#returnFlight').val(''); // Clear return flight field
        $('#flightId').val(''); // Clear Flight Id field
        $('#flightPrice').val('0.00'); // Clear Flight Price field
        $('#displayTotalPrice').text('0.00'); // Clear Total Price field
        $('#totalPrice').val(''); // Clear Total Price Input field

        if (packageId && origin) 
        {
          $.ajax(
          {
            url: 'fetchOutboundFlight.php',
            type: 'POST',
            data: { packageId: packageId, origin: origin },
            success: function (response) 
            {
              console.log(response); // Debugging the response
              $('#outboundFlight').html(response); // Update outbound flights dropdown
            },
            error: function (xhr, status, error) 
            {
              console.error('Error fetching outbound flights:', error); // Log the error to console
            }
          });
        } 
        else 
        {
          $('#outboundFlight').html('<option selected disabled>Select Flight Available Dates</option>');
          $('#returnFlight').val('');
        }
      });

      // When outbound flight is selected, fetch the return flight and apply to all guests
      $('#outboundFlight').on('change', function () 
      {
        var outboundFlight = $(this).val();

        if (outboundFlight) 
        {
          $.ajax(
          {
            url: 'fetchReturnFlight.php', // Separate PHP file for return flight
            type: 'POST',
            data: { outboundFlight: outboundFlight },
            success: function (response) 
            {
              console.log(response); // Debugging the response
              var data = JSON.parse(response); // Parse the JSON response

              // Set flight price for each guest based on the selected outbound flight
              flightPricePerGuest = data.flightPrice; // Set flight price per guest
              
              // Update the return flight input field for all guests
              $('input[name^="returnFlight"]').val(data.returnFlight); 

              // Update the flight price for all guests
              $('input[name^="flightPrice"]').val(data.flightPrice);

              // Update the flight ID for all guests
              $('input[name^="flightId"]').val(data.flightId);

              // Recalculate total price after the flight price is set
              calculateTotalPrice();
            },
            error: function (xhr, status, error) 
            {
              console.error('Error fetching return flight:', error); // Log the error to console
            }
          });
        } 
        else 
        {
          $('input[name^="returnFlight"]').val(''); // Clear return flight input fields if no outbound flight selected
        }
      });

      // Function to calculate the total flight price
      function calculateTotalPrice() 
      {
        var totalPrice = flightPricePerGuest * $('.guest-form').length; // Calculate total price based on the number of guests

        console.log("Total Price:", totalPrice); // Debug: log the total price before updating the field

        // Update the displayed total price in the span
        $('#displayTotalPrice').text(totalPrice.toFixed(2)); // Display total price with 2 decimal places

        // Store the total price in the hidden input field for form submission
        $('#totalPrice').val(totalPrice.toFixed(2)); // Make sure the input value is properly set
      }

      // Initialize event listeners for the first form
      calculateTotalPrice();
    });
  </script>

</body>
</html>