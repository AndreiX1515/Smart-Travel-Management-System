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
  

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

 
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="assets\css\bookingform.css?v=<?php echo time(); ?>">

  <title>Booking Process</title>
  
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

      <div class="header-container d-flex flex-row align-items-center justify-content-between w-100 mt-3 mb-2 px-3">
        <h5>Booking</h5>
        <button class="add-more-form btn btn-primary"><i class="fa-solid fa-plus"></i></button>
      </div>


 <form action="bookingform-code.php" method="POST" id="bookingForm" onsubmit="return validation();">
   <div class="card">
     <div class="card-header bg-secondary text-white text-light">
       <h6 class="my-2 px-2">Details</h6>
     </div>

     <div class="card-body pt-3 pb-0 px-4">
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

             <span id="agentError" class="text-danger"></span> <!-- Error message for agent -->

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
             <span id="packageError" class="text-danger"></span> <!-- Error message for package -->
           </div>
         </div>

       </div>

       <div class="row">
         <div class="col-md-4">
           <div class="form-group mb-4">
             <label class="mb-2" for="origin">Origin <span class="text-danger fw-bold">*</span></label>
             <select class="form-select" id="origin" name="origin" required>
               <option selected disabled>Select Origin</option>
             </select>
             <span id="originError" class="text-danger"></span> <!-- Error message for origin -->
           </div>
         </div>

         <div class="col-md-4">
           <div class="form-group mb-4">
             <label class="mb-2" for="month">Month</label>
             <select class="form-select" id="month" name="month">
               <option selected disabled>Select Month</option>
               <option value="January">January</option>
               <option value="February">February</option>
               <option value="March">March</option>
               <option value="April">April</option>
               <option value="May">May</option>
               <option value="June">June</option>
               <option value="July">July</option>
               <option value="August">August</option>
               <option value="September">September</option>
               <option value="October">October</option>
               <option value="November">November</option>
               <option value="December">December</option>
             </select>
           </div>
         </div>

         <div class="col-md-4">
           <div class="form-group mb-4">
             <label class="mb-2" for="outboundFlight">Flight Date <span class="text-danger fw-bold">*</span></label>
             <select class="form-select" id="outboundFlight" name="outboundFlight" required>
               <option selected disabled>Select Flight Available Dates</option>
             </select>
             <span id="flightError" class="text-danger"></span> <!-- Error message for outbound flight -->
           </div>
         </div>
       </div>

       <div class="row hidden-container">
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

         
  <div class="card-footer footer-adjust align-baseline">
     <h6> <label>Price: ₱ <span id="flightPrice" ></span>
        <!-- <input style="border: none; outline: none;" id="flightPrice" name="flightPrice" value="0.00" readonly> -->
      </label> 
    </h6>
  </div>
</div>

<!-- Guest Information Card -->
<div class="card mt-4 guest-form shadow-sm">

  <div class="card-header bg-secondary text-white">
      <h6 class="mb-3 font-weight-bold">Guest Information 1</h6>
      <button class="btn btn-sm btn-outline-light float-end" type="button" data-bs-toggle="collapse" data-bs-target="#cardBodyContent" aria-expanded="true" aria-controls="cardBodyContent">
        Toggle
      </button>
  </div>

  <input type="hidden" name="accId" value="<?php echo $_SESSION['accountid']; ?>">

  <div id="card-body" class="card-body collapse show">
    <div class="main-form">         
      <!-- Personal Information Group -->
      <div class="row mb-0">
       <div class="header-container d-flex flex-row mb-3">
         <h5 class="card-title bg-primary text-white w-100">Personal Information</h5>
       </div>

        <div class="col-md-3">
          <div class="form-group mb-3">
            <label class="mb-2" for="fName">First Name <span class="text-danger fw-bold">*</span></label>
            <input type="text" name="fName[]" class="form-control" placeholder="Enter First Name" required>
            <span id="fNameError" class="text-danger"></span> <!-- Error message for First Name -->
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group mb-3">
            <label class="mb-2" for="lName">Last Name <span class="text-danger fw-bold">*</span> </label>
            <input type="text" name="lName[]" class="form-control" placeholder="Enter Last Name" required>
            <span id="lNameError" class="text-danger"></span> <!-- Error message for Last Name -->
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
            <select class="form-control" name="suffix[]" required>
              <option selected disabled>Select Suffix</option>
              <option value="">None</option>
              <option value="Jr.">Jr.</option>
              <option value="Sr.">Sr.</option>
              <option value="II">II</option>
              <option value="III">III</option>
              <option value="IV">IV</option>
              <option value="V">V</option>
            </select>
            <span id="suffixError" class="text-danger"></span> <!-- Error message for Suffix -->
          </div>
        </div>
        

        <div class="col-md-3">
          <div class="form-group mb-3">
            <label class="mb-2" for="birthdate">Birthdate <span class="text-danger fw-bold">*</span> </label>
            <input type="date" name="birthdate[]" class="form-control" required>
            <span id="birthdateError" class="text-danger"></span> <!-- Error message for Birthdate -->
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group mb-3">
            <label class="mb-2" for="age">Age <span class="text-danger fw-bold">*</span> </label>
            <input type="number" name="age[]" class="form-control" placeholder="Enter Age" required>
            <span id="ageError" class="text-danger"></span> <!-- Error message for Age -->
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
            <span id="sexError" class="text-danger"></span> <!-- Error message for Sex -->
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
            <span id="nationalityError" class="text-danger"></span> <!-- Error message for Nationality -->
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group mb-3">
            <label class="mb-2" for="passportNo">Passport No. <span class="text-danger fw-bold">*</span></label>
            <input type="text" name="passportNo[]" class="form-control" placeholder="Enter Passport No" required>
            <span id="passportNoError" class="text-danger"></span> <!-- Error message for Passport No -->
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group mb-3">
            <label class="mb-2" for="passportExp">Date of Expiration: <span class="text-danger fw-bold">*</span></label>
            <input type="date" name="passportExp[]" class="form-control" required>
            <span id="passportExpError" class="text-danger"></span> <!-- Error message for Passport Exp -->
          </div>
        </div>

     </div>

      <!-- Contact Information Group -->
     <div class="row mb-0">

       <div class="header-container d-flex flex-row w-100 mb-3 ">
         <h5 class="card-title bg-primary text-white w-100">Contact Information</h5>
       </div>

        <div class="col-md-6">
          <div class="form-group mb-3">
            <label class="mb-2" for="contactNo">Contact No. <span class="text-danger fw-bold">*</span></label>
            <input type="text" name="contactNo[]" class="form-control" placeholder="Enter Contact No" required>
            <span id="contactNoError" class="text-danger"></span> <!-- Error message for Contact No -->
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group mb-3">
            <label class="mb-2" for="email">Email <span class="text-danger fw-bold">*</span></label>
            <input type="email" name="email[]" class="form-control" placeholder="Enter Email Address" required>
            <span id="emailError" class="text-danger"></span> <!-- Error message for Email -->
          </div>
        </div>
      </div>
      
      <!-- Address Information Group -->
      <div class="row mb-4">
        <div class="header-container d-flex flex-row w-100 mb-3">
          <h5 class="card-title bg-primary text-white w-100">Address Information</h5>
        </div>

          <div class="col-md-2">
            <div class="form-group mb-3">
              <label class="mb-2" for="houseNo">House No. <span class="text-danger fw-bold">*</span></label>
              <input type="text" name="houseNo[]" class="form-control" placeholder="Enter House No" required>
              <span id="houseNoError" class="text-danger"></span> <!-- Error message for House No -->
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
              <span id="barangayError" class="text-danger"></span> <!-- Error message for Barangay -->
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group mb-3">
              <label class="mb-2" for="city">City <span class="text-danger fw-bold">*</span></label>
              <input type="text" name="city[]" class="form-control" placeholder="Enter City" required>
              <span id="cityError" class="text-danger"></span> <!-- Error message for City -->
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
              <span id="countryError" class="text-danger"></span> <!-- Error message for Country -->
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

                  <div class="col-md-3">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="suffix">Suffix</label>
                      <select class="form-control" name="suffix[]" required>
                        <option selected disabled>Select Suffix</option>
                        <option value=" ">None</option>
                        <option value="Jr.">Jr.</option>
                        <option value="Sr.">Sr.</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                        <option value="V">V</option>
                      </select>
                      <span id="suffixError" class="text-danger"></span> <!-- Error message for Suffix -->
                    </div>
                  </div>
                  

  <div class="my-3">
    <div class="card mt-2 ">
      <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h5 class="align-items-center pt-2 fw-bolder">Total Price: ₱ <span id="displayTotalPrice">0</span></h5>
        <button type="button" class="btn btn-primary p-2 px-3" id="bookNowButton">Book Now</button>
      </div>
      <input type="hidden" id="totalPrice" name="totalPrice">    
    </div>
  </div>
</div>

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
      $('#origin').on('change', function () {
          fetchFlights(); // Call the function to fetch flights based on the new origin
      });

      // When month is selected or changed, re-fetch flights
      $('#month').on('change', function () {
          fetchFlights(); // Call the same function to fetch flights based on the new month
      });

      // When outbound flight is selected, fetch the return flight and apply to all guests
      $('#outboundFlight').on('change', function () 
      {
        var outboundFlight = $(this).val();
        var selectedFlight = $("#outboundFlight option:selected").text();
        // Extract only the flight date by splitting at the " || " (delimiter between date and price)
        var selectedDate = selectedFlight.split(' || ')[0].trim();

        // Update the <p> element with the extracted flight date
        $('#selectedDate').text(selectedDate);

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

              // // Recalculate total price after the flight price is set
              // calculateTotalPrice();
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

      // Validation logic for booking
      $('#bookNowButton').click(function (event) 
      {
        event.preventDefault(); // Prevent default form submission

        let isValid = true; // Assume form is valid initially

        // Reset error messages and remove invalid class
        $('#agentError, #packageError, #originError, #flightError').text('');
        $('select, input').removeClass('is-invalid'); // Remove invalid class from all fields

        // Check for required fields
        const agentId = $('#agentId').val();
        if (!agentId) 
        {
          $('#agentError').text('Please Select an Agent.'); // Set error message for agentId
          $('#agentId').addClass('is-invalid'); // Add invalid class to agentId
          isValid = false; // Set valid flag to false
        }

        const packageName = $('#packageName').val();
        if (!packageName) 
        {
          $('#packageError').text('Please Select a Package.'); // Set error message for packageName
          $('#packageName').addClass('is-invalid'); // Add invalid class to packageName
          isValid = false; // Set valid flag to false
        }

        const origin = $('#origin').val();
        if (!origin) 
        {
          $('#originError').text('Please Select an Origin.'); // Set error message for origin
          $('#origin').addClass('is-invalid'); // Add invalid class to origin
          isValid = false; // Set valid flag to false
        }

        const outboundFlight = $('#outboundFlight').val();
        if (!outboundFlight) 
        {
          $('#flightError').text('Please Select Flight Date.'); // Set error message for outboundFlight
          $('#outboundFlight').addClass('is-invalid'); // Add invalid class to outboundFlight
          isValid = false; // Set valid flag to false
        }

        // Check for guest information validation
        $('.guest-form').each(function (index) 
        {
          const firstName = $(this).find('input[name^="fName"]').val();
          const lastName = $(this).find('input[name^="lName"]').val();
          const suffix = $(this).find('select[name^="suffix"]').val(); // Check suffix
          const birthdate = $(this).find('input[name^="birthdate"]').val();
          const age = $(this).find('input[name^="age"]').val();
          const sex = $(this).find('select[name^="sex"]').val();
          const nationality = $(this).find('input[name^="nationality"]').val();
          const passportNo = $(this).find('input[name^="passportNo"]').val();
          const passportExp = $(this).find('input[name^="passportExp"]').val();
          const contactNo = $(this).find('input[name^="contactNo"]').val();
          const email = $(this).find('input[name^="email"]').val();
          const houseNo = $(this).find('input[name^="houseNo"]').val();
          const barangay = $(this).find('input[name^="barangay"]').val();
          const city = $(this).find('input[name^="city"]').val();
          const country = $(this).find('input[name^="country"]').val();

          // Check if first name is filled
          if (firstName === '') 
          {
            $(this).find('input[name^="fName"]').addClass('is-invalid');
            $('#fNameError').text('First name is required.'); // Set error message for first name
            isValid = false; // Set valid flag to false
          }

          // Check if last name is filled
          if (lastName === '') 
          {
            $(this).find('input[name^="lName"]').addClass('is-invalid');
            $('#lNameError').text('Last name is required.'); // Set error message for last name
            isValid = false; // Set valid flag to false
          }

          // Check if suffix is selected
          if (!suffix) 
          {
            $(this).find('select[name^="suffix"]').addClass('is-invalid'); // Add invalid class to suffix
            $('#suffixError').text('Suffix is required.'); // Set error message for suffix
            isValid = false; // Set valid flag to false
          }

          // Check if birthdate is filled
          if (!birthdate) 
          {
            $(this).find('input[name^="birthdate"]').addClass('is-invalid');
            $('#birthdateError').text('Birthdate is required.'); // Set error message for birthdate
            isValid = false; // Set valid flag to false
          }

          // Check if age is filled
          if (age === '') 
          {
            $(this).find('input[name^="age"]').addClass('is-invalid');
            $('#ageError').text('Age is required.'); // Set error message for age
            isValid = false; // Set valid flag to false
          }

          // Check if sex is selected
          if (!sex) 
          {
            $(this).find('select[name^="sex"]').addClass('is-invalid');
            $('#sexError').text('Sex is required.'); // Set error message for sex
            isValid = false; // Set valid flag to false
          }

          // Check if nationality is selected
          if (!nationality) 
          {
            $(this).find('input[name^="nationality"]').addClass('is-invalid');
            $('#nationalityError').text('Nationality is required.'); // Set error message for nationality
            isValid = false; // Set valid flag to false
          }

          // Check if passport number is filled
          if (passportNo === '') 
          {
            $(this).find('input[name^="passportNo"]').addClass('is-invalid');
            $('#passportNoError').text('Passport number is required.'); // Set error message for passport number
            isValid = false; // Set valid flag to false
          }

          // Check if passport expiration date is filled
          if (passportExp === '') 
          {
            $(this).find('input[name^="passportExp"]').addClass('is-invalid');
            $('#passportExpError').text('Passport expiration date is required.'); // Set error message for passport expiration
            isValid = false; // Set valid flag to false
          }

          // Check if contact number is filled
          if (contactNo === '') 
          {
            $(this).find('input[name^="contactNo"]').addClass('is-invalid'); // Add invalid class
            $('#contactNoError').text('Contact number is required.'); // Set error message for contact number
            isValid = false; // Set valid flag to false
          }

          // Check if email is filled
          if (email === '') 
          {
            $(this).find('input[name^="email"]').addClass('is-invalid'); // Add invalid class
            $('#emailError').text('Email is required.'); // Set error message for email
            isValid = false; // Set valid flag to false
          }

          // Check if house number is filled
          if (houseNo === '') 
          {
            $(this).find('input[name^="houseNo"]').addClass('is-invalid'); // Add invalid class
            $('#houseNoError').text('House number is required.'); // Set error message for house number
            isValid = false; // Set valid flag to false
          }

          // Check if barangay is filled
          if (barangay === '') 
          {
            $(this).find('input[name^="barangay"]').addClass('is-invalid'); // Add invalid class
            $('#barangayError').text('Barangay is required.'); // Set error message for barangay
            isValid = false; // Set valid flag to false
          }

          // Check if city is filled
          if (city === '') 
          {
            $(this).find('input[name^="city"]').addClass('is-invalid'); // Add invalid class
            $('#cityError').text('City is required.'); // Set error message for city
            isValid = false; // Set valid flag to false
          }

          // Check if country is filled
          if (!country) 
          {
            $(this).find('input[name^="country"]').addClass('is-invalid'); // Add invalid class
            $('#countryError').text('Country is required.'); // Set error message for country
            isValid = false; // Set valid flag to false
          }
        });

        // If the form is valid, show the booking confirmation modal
        if (isValid) 
        {
          $('#BookingSummaryModal').modal('show'); // Trigger modal display
        }
      });

      // Optional: If you want to clear validation errors when the user focuses on the field
      $('select, input').focus(function () 
      {
        $(this).removeClass('is-invalid');
        $('#agentError').text(''); // Set error message for agentId
        $('#packageError').text(''); // Set error message for packageName
        $('#originError').text(''); // Set error message for origin
        $('#flightError').text(''); // Set error message for Flight Date
        $('#fNameError').text(''); // Set error message for First Name
        $('#lNameError').text(''); // Set error message for Last Name
        $('#suffixError').text(''); // Set error message for Suffix
        $('#birthdateError').text(''); // Set error message for Birthdate
        $('#ageError').text(''); // Set error message for Age
        $('#sexError').text(''); // Set error message for Sex
        $('#nationalityError').text(''); // Set error message for Nationality
        $('#passportNoError').text(''); // Set error message for Passport No
        $('#passportExpError').text(''); // Set error message for Passport Exp
        $('#contactNoError').text(''); // Set error message for Contact No
        $('#emailError').text(''); // Set error message for Email
        $('#houseNoError').text(''); // Set error message for House No
        $('#barangayError').text(''); // Set error message for Barangay
        $('#cityError').text(''); // Set error message for City
        $('#countryError').text(''); // Set error message for Country
      });

      // Function to fetch flights based on packageId, origin, and month
      function fetchFlights() 
      {
          var packageId = $('#packageName').val();
          var origin = $('#origin').val();
          var month = $('#month').val(); // Get the selected month (optional)
          var selectedOrigin = $("#origin option:selected").text();

          // Update the modal with the selected origin
          $('#selectedOrigin').text(selectedOrigin);

          // Clear outbound flight field
          $('#outboundFlight').html('<option selected disabled>Select Flight Available Dates</option>');
          
          $('#returnFlight').val(''); // Clear return flight field
          $('#flightId').val(''); // Clear Flight Id field
          $('#flightPrice').val('0.00'); // Clear Flight Price field
          $('#displayTotalPrice').text('0.00'); // Clear Total Price field
          $('#totalPrice').val('0.00'); // Clear Total Price Input field

          if (packageId && origin) 
          {
              $.ajax({
                  url: 'fetchOutboundFlight.php',
                  type: 'POST',
                  data: { packageId: packageId, origin: origin, month: month }, // Send packageId, origin, and month (even if empty)
                  success: function (response) {
                      console.log(response); // Debugging the response
                      $('#outboundFlight').html(response); // Update outbound flights dropdown
                  },
                  error: function (xhr, status, error) {
                      console.error('Error fetching outbound flights:', error); // Log the error to console
                  }
              });
          } 
          else 
          {
              $('#outboundFlight').html('<option selected disabled>Select Flight Available Dates</option>');
              $('#returnFlight').val('');
          }
      }

      // Function to calculate the total price
      function calculateTotalPrice() 
      {
        var totalPrice = flightPricePerGuest * $('.guest-form').length; // Calculate total price based on the number of guests

        console.log("Total Price:", totalPrice); // Debug: log the total price before updating the field

        // Format the total price with commas and two decimal places
        var formattedTotalPrice = totalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Flag to check if all required fields are filled
        var allRequiredFieldsFilled = true;

        // Loop through each guest form
        $('.guest-form').each(function () 
        {
          const firstName = $(this).find('input[name^="fName"]').val();
          const lastName = $(this).find('input[name^="lName"]').val();
          const suffix = $(this).find('select[name^="suffix"]').val();
          const birthdate = $(this).find('input[name^="birthdate"]').val();
          const age = $(this).find('input[name^="age"]').val();
          const sex = $(this).find('select[name^="sex"]').val();
          const nationality = $(this).find('input[name^="nationality"]').val();
          const passportNo = $(this).find('input[name^="passportNo"]').val();
          const passportExp = $(this).find('input[name^="passportExp"]').val();
          const contactNo = $(this).find('input[name^="contactNo"]').val();
          const email = $(this).find('input[name^="email"]').val();
          const houseNo = $(this).find('input[name^="houseNo"]').val();
          const barangay = $(this).find('input[name^="barangay"]').val();
          const city = $(this).find('input[name^="city"]').val();
          const country = $(this).find('input[name^="country"]').val();

          // Check if any required field is empty
          if (!firstName || !lastName || !suffix || !birthdate || !age || !sex || !nationality || !passportNo || !passportExp || 
              !contactNo || !email || !houseNo || !barangay || !city || !country) 
          {
            allRequiredFieldsFilled = false; // Set flag to false if any required field is empty
          }
        });

        // If all required fields are filled, calculate the total price
        if (allRequiredFieldsFilled) 
        {
          totalPrice = flightPricePerGuest * $('.guest-form').length; // Calculate total price based on the number of guests

          // Update the displayed total price in the span
          $('#displayTotalPrice').text(formattedTotalPrice);

          // Store the total price in the hidden input field for form submission
          $('#totalPrice').val(totalPrice.toFixed(2)); // Make sure the input value is properly set
        } 
        else 
        {
          totalPrice = 0; // Set total price to 0 if any required field is empty
          // Update the displayed total price in the span
          $('#displayTotalPrice').text("0.00");

          // Store the total price in the hidden input field for form submission
          $('#totalPrice').val("0.00"); // Make sure the input value is properly set
        }
      }

      // Automatically update total price when any required field changes
      $('input[name^="fName"], input[name^="lName"], select[name^="suffix"], input[name^="birthdate"], ' +
        'input[name^="age"], select[name^="sex"], input[name^="nationality"], input[name^="passportNo"], ' +
        'input[name^="passportExp"], input[name^="contactNo"], input[name^="email"], input[name^="houseNo"], ' +
        'input[name^="barangay"], input[name^="city"], input[name^="country"]')
        .on('input change', function () 
      {
        // Flag to check if all fields are filled
        let allFieldsFilled = true;

        // Loop through each required field to check if any is empty
        $('input[name^="fName"], input[name^="lName"], select[name^="suffix"], input[name^="birthdate"], ' +
          'input[name^="age"], select[name^="sex"], input[name^="nationality"], input[name^="passportNo"], ' +
          'input[name^="passportExp"], input[name^="contactNo"], input[name^="email"], input[name^="houseNo"], ' +
          'input[name^="barangay"], input[name^="city"], input[name^="country"]').each(function() 
        {
          if ($(this).val() === '') 
          {
            allFieldsFilled = false; // Set to false if any field is empty
          }
        });

        // If all required fields are filled, calculate the total price
        if (allFieldsFilled) 
        {
          calculateTotalPrice();
        } 
        else 
        {
          // Optional: You can set total price to 0 or display a warning
          setTotalPriceToZero(); // Implement this function if needed
        }
      });

      // Function to set total price to 0
      function setTotalPriceToZero() 
      {
        $('#displayTotalPrice').text('0.00'); // Reset the value of the input field to 0
        $('#totalPrice').val('0.00'); // Reset the value of the input field to 0
        $('#totalPrice').text('0.00'); // Reset the value of the input field to 0
      }

      // Initialize event listeners for the first form
      calculateTotalPrice();
    });
  </script>

</body>
</html>