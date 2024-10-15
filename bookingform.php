<?php 
  include 'session_validate.php';
  require "conn.php";
  
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $email = $_SESSION['email'] ?? '';
  $firstName = $_SESSION['first_name'] ?? '';
  $lastName = $_SESSION['last_name'] ?? '';
  $middleName = $_SESSION['middle_name'] ?? '';
  // Combine last name, first name, and middle initial
  $fullName = $lastName . ', ' . $firstName . ($middleName ? ' ' . substr($middleName, 0, 1) . '.' : '');
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
  <link rel="stylesheet" href="assets\css\bookingform.css">

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
              <h4> <label>Price: ₱ <span id="flightPrice" ></span>
                  <input type="hidden" id="flightPrice" name="flightPrice" value="0.00" readonly>
                </label> 
              </h4>
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
                      <select class="form-control" name="suffix[]">
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
                      <select class="form-control" name="sex[]" required>
                        <option selected disabled>Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                  </div>
          
                  <div class="col-md-3">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="nationality">Nationality <span class="text-danger fw-bold">*</span> </label>
                      <select class="form-control" name="nationality[]" required>
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
                        <select class="form-control" name="country[]" required>
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
                  <button type="button" class="btn btn-primary p-2 px-3" data-bs-toggle="modal" data-bs-target="#BookingSummaryModal">Book Now</button>
                </div>
                <input type="hidden" id="totalPrice" name="totalPrice">    
              </div>
            </div>
          </div>

          <!--  -->

          <!-- Modal -->
          <div class="modal fade" id="BookingSummaryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Added modal-lg for a wider modal -->
              <div class="modal-content position-relative">
                    
                <button type="button" class="btn-close close-outside" data-bs-dismiss="modal" aria-label="Close"></button>
                    
                <div class="modal-body">
                  <div class="confirmation-container container">
                    <!-- Logo Section -->
                    <div class="row text-center my-4">
                      <div class="col">
                        <img src="assets/images/SMART LOGO 2 (2).png" alt="Trip Image" class="img-fluid" style="max-width: 250px; max-height: 80px;">
                      </div>
                    </div>

                    <h4 class="text-left mb-4">Booking Summary</h4>

                    <!-- Transaction and Contact Info -->
                    <div class="transaction-info row mb-3">
                      <div class="col-12">

                        <div class="d-flex justify-content-between mb-1">
                          <p class="mb-0"><strong>Contact Guest Name:</strong></p>
                          <p class="mb-0"><?php echo $fullName ?></p>
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                          <p class="mb-0"><strong>Contact Email:</strong></p>
                          <p class="mb-0"><?php echo $email ?></p>
                        </div>
                      </div>
                    </div>

                    <hr>

                    <!-- Hotel/Package Details -->
                    <div class="row hotel-details mb-3">
                      <div class="col-12">
                        <div class="d-flex justify-content-between mb-1">
                          <p class="mb-0"><strong>Package Name:</strong></p>
                          <p class="mb-0" id="selectedPackage">No Package Selected</p>
                        </div>

                        <div class="d-flex justify-content-between">
                          <p class="mb-0"><strong>No. of Guests:</strong></p>
                          <p class="mb-0" id="guestCount">1</p>
                        </div>
                      </div>
                    </div>

                    <hr>

                    <!-- Flight/Origin Details -->
                    <div class="row mb-3">
                      <div class="col-12">
                        <div class="d-flex justify-content-between mb-1">
                          <p class="mb-0"><strong>Origin:</strong></p>
                          <p class="mb-0" id="selectedOrigin">No Origin Selected</p>
                        </div>

                        <div class="d-flex justify-content-between">
                          <p class="mb-0"><strong>Flight Date:</strong></p>
                          <p class="mb-0" id="selectedDate">No Flight Date Selected</p>
                        </div>
                      </div>
                    </div>

                        <hr>

                    <!-- Proceed to Payment -->
                    <div class="row mt-4">
                      <div class="col d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="bookNow">Proceed to Payment</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="heartbeat.js"></script>

  <script>
    $(document).ready(function () 
    {
      let flightPricePerGuest = 0; // Initialize flight price per guest

      // Adding more guest forms dynamically
      $('.add-more-form').click(function () 
      {
        var guestForm = $('.guest-form:first').clone(); // Clone only personal information
        var formCount = $('.guest-form').length + 1; // Count the total number of forms
        $('#guestCount').text(formCount); // Update the modal with the total number of guests

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

        // Initialize event listeners for the first form
          calculateTotalPrice();
      });

      // Remove guest form dynamically
      $(document).on('click', '.remove-guest', function () 
      {
        $(this).closest('.guest-form').slideUp(function () 
        {
          $(this).remove(); // Remove the form after sliding up
          // Recalculate the total number of guest forms
          var formCount = $('.guest-form').length;

          // Update the modal with the new total number of guests
          $('#guestCount').text(formCount);
          calculateTotalPrice(); // Recalculate total price after removing a form
        });
      });

      // Flight selection logic (single selection, applies to all guests)
      $('#packageName').on('change', function () 
      {
        var packageId = $(this).val();
        var selectedPackageName = $("#packageName option:selected").text();
        $('#origin').html('<option selected disabled>Select Origin</option>'); // Clear origin field
        $('#outboundFlight').html('<option selected disabled>Select Flight Available Dates</option>'); // Clear outbound flight field
        $('#returnFlight').val(''); // Clear return flight field
        $('#flightId').val(''); // Clear Flight Id field
        $('#flightPrice').val('0.00'); // Clear Flight Price field
        $('#displayTotalPrice').text('0.00'); // Clear Total Price field
        $('#totalPrice').val(''); // Clear Total Price Input field

        // Update the modal with the selected package name
        $('#selectedPackage').text(selectedPackageName);

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
        var selectedOrigin = $("#origin option:selected").text();

        // Update the modal with the selected origin
        $('#selectedOrigin').text(selectedOrigin);

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
        var selectedFlight = $("#outboundFlight option:selected").text();

        // Update the modal with the selected Flight Date
        $('#selectedDate').text(selectedFlight);

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

              flightPricePerGuest = parseFloat(data.flightPrice); // Ensure it's a number

              // Format the price with commas and two decimal places
      var formattedPrice = flightPricePerGuest.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

// Update the flight price display with the formatted price
$('#flightPrice').text(formattedPrice);

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

        // Format the total price with commas and two decimal places
  var formattedTotalPrice = totalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

// Update the displayed total price in the span
$('#displayTotalPrice').text(formattedTotalPrice);

        // Store the total price in the hidden input field for form submission
        $('#totalPrice').val(totalPrice.toFixed(2)); // Make sure the input value is properly set
      }

      // Initialize event listeners for the first form
      calculateTotalPrice();
    });
  </script>

</body>
</html>