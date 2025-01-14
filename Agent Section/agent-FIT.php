<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);


include '../Agent Section/includes/breadcrumbs.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions</title>

  <?php include '../Agent Section/includes/head.php' ?>
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">

</head>

<body>
  <?php include '../Agent Section/includes/sidebar.php'; ?> 

  <div class="main-content" id="mainContent">
    <?php include '../Agent Section/includes/navbar.php'; ?>

    <div class="content-wrapper-transact d-flex flex-column">
      <?php 
        if(isset($_SESSION['status'])):
        ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong></strong> <?= $_SESSION['status']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php 
        unset($_SESSION['status']);
        endif;
      ?>

      <form action="../Agent Section/functions/agent-addFIT-code.php" method="POST">
        <div class="card">
          <div class="card-header bg-secondary text-white text-light">
            <h4 class="my-2 px-2">F.I.T</h4>
          </div>

          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="packageName" class="fs-6">Package <span class="text-danger fw-bold">*</span></label>
                  <select class="form-select mt-2 fs-6" id="packageName" name="packageName" required>
                    <option selected disabled>Select Package</option>
                    <?php
                      $sql1 = mysqli_query($conn, "SELECT packageId, packageName FROM fitpackage ORDER BY packageName ASC");
                      while($res1 = mysqli_fetch_array($sql1)) 
                      { 
                        echo "<option value='{$res1['packageId']}'>{$res1['packageName']}</option>";
                      }
                    ?>
                  </select>
                  <span id="packageNameError" class="text-danger"></span> <!-- Error message for package -->
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="nights" class="fs-6">No. of Nights <span class="text-danger fw-bold">*</span></label>
                  <select class="form-select mt-2 fs-6" id="nights" name="nights" required>
                    <option selected disabled>Select No. of Nights</option>
                    <option value="3">3 Nights</option>
                    <option value="4">4 Nights</option>
                    <option value="5">5 Nights</option>
                  </select>
                  <span id="nightsError" class="text-danger"></span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="hotels" class="fs-6">Hotels <span class="text-danger fw-bold">*</span></label>
                  <select class="form-select mt-2 fs-6" id="hotels" name="hotels" required>
                    <option selected disabled>Select Hotel</option>
                    <?php
                      $sql1 = mysqli_query($conn, "SELECT hotelId, hotelName FROM fithotel ORDER BY hotelName ASC");
                      while($res1 = mysqli_fetch_array($sql1)) 
                      { 
                        echo "<option value='{$res1['hotelId']}'>{$res1['hotelName']}</option>";
                      }
                    ?>
                    <!-- <option value="Smart Hotel" data-price="80">Smart Hotel - $80/night + $20 on Friday & Saturday</option>
                    <option value="Marina Bay Hotel" data-price="100">Marina Bay Hotel - $100/night + $20 on Friday & Saturday</option> -->
                  </select>
                  <span id="hotelsError" class="text-danger"></span>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-6">
                  <label for="room" class="fs-6">Rooms <span class="text-danger fw-bold">*</span></label>
                  <select class="form-select mt-2 fs-6" id="room" name="room" required>
                    <option selected disabled>Select Room</option>
                    
                  </select>
                  <span id="roomError" class="text-danger"></span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-6">
                  <label for="dayPicker" class="fs-6">Select Day <span class="text-danger fw-bold">*</span></label>
                  <input type="date" class="form-control mt-2 fs-6" id="dayPicker" name="dayPicker" required>
                  <span id="packageNameError" class="text-danger"></span> <!-- Error message for package -->
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="returnDate" class="fs-6">Return Date <span class="text-danger fw-bold">*</span></label>
                  <input type="date" class="form-control mt-2 fs-6" id="returnDate" name="returnDate" readonly>
                  <span id="packageNameError" class="text-danger"></span> <!-- Error message for package -->
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-6">
                  <label class="fs-6">No. of Rooms<span class="text-danger fw-bold">*</span></label>
                  <label id="roomsError" class="text-danger fs-6 d-none"></label> <!-- Error message for rooms -->
                  <input type="number" class="form-control mt-2 fs-6" id="rooms" name="rooms" placeholder="Enter No of Rooms" min="1" required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-6">
                  <label class="fs-6">Pax<span class="text-danger fw-bold">*</span></label>
                  
                  <label id="paxError" class="text-danger fs-6 d-none"></label> <!-- Error message for pax -->
                  <input type="number" class="form-control mt-2 fs-6" id="paxRequest" name="pax" placeholder="Enter pax" min="1" required>
                </div>
              </div>
            </div>
          </div>

          <!-- Prices -->
          <div class="card-footer d-flex justify-content-start align-items-center py-3">
            <div class="row w-100">
              <div class="col-12">
                <h5>
                  <label>
                    Additional Cost (Rooms): $ <span id="additionalRoomCost">0.00</span>
                  </label>
                </h5>
              </div>
              <div class="col-12">
                <h5>
                  <label>
                    Price: $ <span id="totalPrice">0.00</span>
                  </label>
                </h5>
              </div>
              <div class="col-12">
                <h5>
                  <label>
                    Price in PHP: ₱ <span id="totalPricePhp">0.00</span>
                  </label>
                </h5>
              </div>
              <input type="hidden" id="roomPrice" name="roomPrice" placeholder="Room Price in USD">
            </div>
          </div>
        </div>

        <div class="card mt-4">
          <div class="card-header bg-secondary text-white text-light">
            <h4 class="my-2 px-2">Contact Person Details</h4>
          </div>

          <div class="card-body p-4">
            <div class="row mb-3">
              <!-- First Name Input -->
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="fs-6" for="fName">First Name <span class="text-danger fw-bold">*</span></label>
                  <input type="text" name="fName" id="fName" class="form-control mt-2 fs-6" placeholder="Enter First Name" required>
                  <span id="fNameError" class="text-danger"></span> <!-- Error message for First Name -->
                </div>
              </div>

              <!-- Last Name Input -->
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="fs-6" for="lName">Last Name <span class="text-danger fw-bold">*</span> </label>
                  <input type="text" name="lName" id="lName" class="form-control mt-2 fs-6" placeholder="Enter Last Name" required>
                  <span id="lNameError" class="text-danger"></span> <!-- Error message for Last Name -->
                </div>
              </div>

              <!-- Middle Name Input -->
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="fs-6" for="mName">Middle Name <span class="text-danger fw-bold">write N/A if none</span></label>
                  <input type="text" name="mName" id="mName" class="form-control mt-2 fs-6" placeholder="Enter Middle Name" required>
                  <span id="mNameError" class="text-danger"></span> <!-- Error message for Middle Name -->
                </div>
              </div>

              <!-- Suffix Dropdown -->
              <div class="col-md-3">
                <div class="form-group mb-3">
                  <label class="fs-6" for="suffix">Suffix <span class="text-danger fw-bold">*</span></label>
                  <select class="form-select mt-2 fs-6" name="suffix" id="suffix" required>
                    <option selected disabled>Select Suffix</option>
                    <option value="N/A">None</option>
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
            </div>

            <div class="row mb-3">
              <!-- Contact No Input-->
              <div class="col-md-4">
                <div class="form-group mb-4">
                  <label class="fs-6" for="contactNo">Contact No. <span class="text-danger fw-bold">*</span></label>
                  <div class="input-group">
                    <select name="countryCode" id="countryCode" class="form-select mt-2 fs-6" required>
                      <option disabled selected>Country Code</option>
                      <option value="+93">Afghanistan (+93)</option>
                      <option value="+355">Albania (+355)</option>
                      <option value="+213">Algeria (+213)</option>
                      <option value="+376">Andorra (+376)</option>
                      <option value="+244">Angola (+244)</option>
                      <option value="+1-268">Antigua and Barbuda (+1-268)</option>
                      <option value="+54">Argentina (+54)</option>
                      <option value="+374">Armenia (+374)</option>
                      <option value="+61">Australia (+61)</option>
                      <option value="+43">Austria (+43)</option>
                      <option value="+994">Azerbaijan (+994)</option>
                      <option value="+1-242">Bahamas (+1-242)</option>
                      <option value="+973">Bahrain (+973)</option>
                      <option value="+880">Bangladesh (+880)</option>
                      <option value="+1-246">Barbados (+1-246)</option>
                      <option value="+375">Belarus (+375)</option>
                      <option value="+32">Belgium (+32)</option>
                      <option value="+501">Belize (+501)</option>
                      <option value="+229">Benin (+229)</option>
                      <option value="+975">Bhutan (+975)</option>
                      <option value="+591">Bolivia (+591)</option>
                      <option value="+387">Bosnia and Herzegovina (+387)</option>
                      <option value="+267">Botswana (+267)</option>
                      <option value="+55">Brazil (+55)</option>
                      <option value="+673">Brunei (+673)</option>
                      <option value="+359">Bulgaria (+359)</option>
                      <option value="+226">Burkina Faso (+226)</option>
                      <option value="+257">Burundi (+257)</option>
                      <option value="+238">Cabo Verde (+238)</option>
                      <option value="+855">Cambodia (+855)</option>
                      <option value="+237">Cameroon (+237)</option>
                      <option value="+1">Canada (+1)</option>
                      <option value="+236">Central African Republic (+236)</option>
                      <option value="+235">Chad (+235)</option>
                      <option value="+56">Chile (+56)</option>
                      <option value="+86">China (+86)</option>
                      <option value="+57">Colombia (+57)</option>
                      <option value="+269">Comoros (+269)</option>
                      <option value="+243">Congo, Democratic Republic of the (+243)</option>
                      <option value="+242">Congo, Republic of the (+242)</option>
                      <option value="+506">Costa Rica (+506)</option>
                      <option value="+385">Croatia (+385)</option>
                      <option value="+53">Cuba (+53)</option>
                      <option value="+357">Cyprus (+357)</option>
                      <option value="+420">Czech Republic (+420)</option>
                      <option value="+45">🇩🇰 Denmark (+45)</option>
                      <option value="+253">🇩🇯 Djibouti (+253)</option>
                      <option value="+1-767">🇩🇲 Dominica (+1-767)</option>
                      <option value="+1-809">🇩🇴 Dominican Republic (+1-809)</option>
                      <option value="+593">Ecuador (+593)</option>
                      <option value="+20">Egypt (+20)</option>
                      <option value="+503">El Salvador (+503)</option>
                      <option value="+240">Equatorial Guinea (+240)</option>
                      <option value="+291">Eritrea (+291)</option>
                      <option value="+372">Estonia (+372)</option>
                      <option value="+268">Eswatini (+268)</option>
                      <option value="+251">Ethiopia (+251)</option>
                      <option value="+679">Fiji (+679)</option>
                      <option value="+358">Finland (+358)</option>
                      <option value="+33">France (+33)</option>
                      <option value="+241">Gabon (+241)</option>
                      <option value="+220">Gambia (+220)</option>
                      <option value="+995">Georgia (+995)</option>
                      <option value="+49">Germany (+49)</option>
                      <option value="+233">Ghana (+233)</option>
                      <option value="+30">Greece (+30)</option>
                      <option value="+1-473">Grenada (+1-473)</option>
                      <option value="+502">Guatemala (+502)</option>
                      <option value="+224">Guinea (+224)</option>
                      <option value="+245">Guinea-Bissau (+245)</option>
                      <option value="+592">Guyana (+592)</option>
                      <option value="+509">Haiti (+509)</option>
                      <option value="+504">Honduras (+504)</option>
                      <option value="+36">Hungary (+36)</option>
                      <option value="+354">Iceland (+354)</option>
                      <option value="+91">India (+91)</option>
                      <option value="+62">Indonesia (+62)</option>
                      <option value="+98">Iran (+98)</option>
                      <option value="+964">Iraq (+964)</option>
                      <option value="+353">Ireland (+353)</option>
                      <option value="+972">Israel (+972)</option>
                      <option value="+39">Italy (+39)</option>
                      <option value="+225">Ivory Coast (+225)</option>
                      <option value="+81">Japan (+81)</option>
                      <option value="+962">Jordan (+962)</option>
                      <option value="+7">Kazakhstan (+7)</option>
                      <option value="+254">Kenya (+254)</option>
                      <option value="+686">Kiribati (+686)</option>
                      <option value="+965">Kuwait (+965)</option>
                      <option value="+996">Kyrgyzstan (+996)</option>
                      <option value="+856">Laos (+856)</option>
                      <option value="+371">Latvia (+371)</option>
                      <option value="+961">Lebanon (+961)</option>
                      <option value="+266">Lesotho (+266)</option>
                      <option value="+231">Liberia (+231)</option>
                      <option value="+218">Libya (+218)</option>
                      <option value="+423">Liechtenstein (+423)</option>
                      <option value="+370">Lithuania (+370)</option>
                      <option value="+352">Luxembourg (+352)</option>
                      <option value="+261">Madagascar (+261)</option>
                      <option value="+265">Malawi (+265)</option>
                      <option value="+60">Malaysia (+60)</option>
                      <option value="+960">Maldives (+960)</option>
                      <option value="+223">Mali (+223)</option>
                      <option value="+356">Malta (+356)</option>
                      <option value="+692">Marshall Islands (+692)</option>
                      <option value="+596">Martinique (+596)</option>
                      <option value="+222">Morocco (+222)</option>
                      <option value="+258">Mozambique (+258)</option>
                      <option value="+95">Myanmar (+95)</option>
                      <option value="+264">Namibia (+264)</option>
                      <option value="+674">Nauru (+674)</option>
                      <option value="+977">Nepal (+977)</option>
                      <option value="+31">Netherlands (+31)</option>
                      <option value="+599">Netherlands Antilles (+599)</option>
                      <option value="+64">New Zealand (+64)</option>
                      <option value="+505">Nicaragua (+505)</option>
                      <option value="+227">Niger (+227)</option>
                      <option value="+234">Nigeria (+234)</option>
                      <option value="+683">Niue (+683)</option>
                      <option value="+672">Norfolk Island (+672)</option>
                      <option value="+850">North Korea (+850)</option>
                      <option value="+1-670">Northern Mariana Islands (+1-670)</option>
                      <option value="+47">Norway (+47)</option>
                      <option value="+968">Oman (+968)</option>
                      <option value="+92">Pakistan (+92)</option>
                      <option value="+680">Palau (+680)</option>
                      <option value="+507">Panama (+507)</option>
                      <option value="+675">Papua New Guinea (+675)</option>
                      <option value="+595">Paraguay (+595)</option>
                      <option value="+51">Peru (+51)</option>
                      <option value="+63">Philippines (+63)</option>
                      <option value="+48">Poland (+48)</option>
                      <option value="+351">Portugal (+351)</option>
                      <option value="+974">Qatar (+974)</option>
                      <option value="+40">Romania (+40)</option>
                      <option value="+7">Russia (+7)</option>
                      <option value="+250">Rwanda (+250)</option>
                      <option value="+508">Saint Barthélemy (+508)</option>
                      <option value="+1-869">Saint Kitts and Nevis (+1-869)</option>
                      <option value="+1-758">Saint Lucia (+1-758)</option>
                      <option value="+590">Saint Martin (+590)</option>
                      <option value="+1-345">Cayman Islands (+1-345)</option>
                      <option value="+239">São Tomé and Príncipe (+239)</option>
                      <option value="+966">Saudi Arabia (+966)</option>
                      <option value="+221">Senegal (+221)</option>
                      <option value="+381">Serbia (+381)</option>
                      <option value="+248">Seychelles (+248)</option>
                      <option value="+232">Sierra Leone (+232)</option>
                      <option value="+65">Singapore (+65)</option>
                      <option value="+421">Slovakia (+421)</option>
                      <option value="+386">Slovenia (+386)</option>
                      <option value="+677">Solomon Islands (+677)</option>
                      <option value="+252">Somalia (+252)</option>
                      <option value="+27">South Africa (+27)</option>
                      <option value="+82">South Korea (+82)</option>
                      <option value="+211">South Sudan (+211)</option>
                      <option value="+34">Spain (+34)</option>
                      <option value="+94">Sri Lanka (+94)</option>
                      <option value="+249">Sudan (+249)</option>
                      <option value="+597">Suriname (+597)</option>
                      <option value="+268">Swaziland (+268)</option>
                      <option value="+46">Sweden (+46)</option>
                      <option value="+41">Switzerland (+41)</option>
                      <option value="+963">Syria (+963)</option>
                      <option value="+886">Taiwan (+886)</option>
                      <option value="+992">Tajikistan (+992)</option>
                      <option value="+255">Tanzania (+255)</option>
                      <option value="+66">Thailand (+66)</option>
                      <option value="+670">Timor-Leste (+670)</option>
                      <option value="+228">Togo (+228)</option>
                      <option value="+676">Tonga (+676)</option>
                      <option value="+1-868">Trinidad and Tobago (+1-868)</option>
                      <option value="+216">Tunisia (+216)</option>
                      <option value="+90">Turkey (+90)</option>
                      <option value="+993">Turkmenistan (+993)</option>
                      <option value="+1-649">Turks and Caicos Islands (+1-649)</option>
                      <option value="+688">Vanuatu (+688)</option>
                      <option value="+39">Vatican City (+39)</option>
                      <option value="+58">Venezuela (+58)</option>
                      <option value="+84">Vietnam (+84)</option>
                      <option value="+681">Wallis and Futuna (+681)</option>
                      <option value="+967">Yemen (+967)</option>
                      <option value="+260">Zambia (+260)</option>
                      <option value="+263">Zimbabwe (+263)</option>
                    </select>
                    <input type="tel" class="form-control mt-2 fs-6" id="contactNo" name="contactNo" placeholder="Contact Number" required>
                  </div>
                  <span id="contactNoError" class="text-danger"></span> <!-- Error message for Contact No -->
                </div>
              </div>

              <!-- Email Input -->
              <div class="col-md-4">
                <div class="form-group mb-4">
                  <label class="fs-6" for="email">Email <span class="text-danger fw-bold">*</span></label>
                  <input type="email" name="email" id="email" class="form-control mt-2 fs-6" placeholder="Enter Email Address" required>
                  <span id="emailError" class="text-danger"></span> <!-- Error message for Email -->
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="card mt-1 ">
          <div class="card-footer d-flex justify-content-between align-items-center py-3">
            <button type="submit" class="btn btn-primary" name="bookNow">Book Now</button>
          </div>
          <input type="" id="totalCostUSD" name="totalCostUSD" placeholder="Total Price USD">
          <input type="" id="totalCostPHP" name="totalCostPHP" placeholder="Total Price PHP">
          <!-- <input type="" class="form-control mt-2 fs-6" id="totalCost" name="totalCost" readonly> -->
        </div>

      </form>
    </div>
  </div>

  <?php include '../Agent Section/functions/exchange-rate.php'?>
  <?php require "../Agent Section/includes/scripts.php"; ?>
  

  <script>
    var usdToPhp = 56.50; // Replace this with your actual value
    $(document).ready(function () 
    {
      // Fetching Rooms once Hotel was Selected
      $('#hotels').on('change', function () 
      {
        var hotelId = $(this).val();

        if (hotelId) 
        {
          $.ajax(
          {
            url: '../Agent Section/functions/fetchRooms.php',
            type: 'POST',
            data: { hotelId: hotelId },
            success: function (response) 
            {
              // Parse the JSON response
              var data = JSON.parse(response);

              // Update the room options dropdown
              $('#room').html(data.roomOptions);

              // Optional: Debugging
              // console.log(data);
            }
          });
        } 
        else 
        {
          $('#room').html('<option selected disabled>Select Rooms</option>');
        }
      });

      // Fetching Room Price once Room was Selected
      $('#room').on('change', function () 
      {
        var roomId = $(this).val();

        if (roomId) 
        {
          $.ajax(
          {
            url: '../Agent Section/functions/fetchRoomPrice.php',
            type: 'POST',
            data: { roomId: roomId },
            success: function (response) 
            {
              // Parse the JSON response
              var data = JSON.parse(response);

              // Update the price input and available rooms
              $('#roomPrice').val(data.roomPrice);
              $('#rooms').attr('max', data.avail);

              // Optional: Debugging
              // console.log(data);
            }
          });
        } 
        else 
        {
          $('#roomPrice').val('Error');
        }
      });

      // Validate the number of rooms selected
      $('#rooms').on('change input', function () 
      {
        var roomCount = $(this).val(); // Get the current value of the input
        var maxRooms = $(this).attr('max'); // Get the max attribute value

        // Ensure the entered value does not exceed the max
        if (parseInt(roomCount) > parseInt(maxRooms)) 
        {
          alert('The number of rooms exceeds the available limit!');
          $(this).val(maxRooms); // Reset to the maximum allowed value
        }

        // Optional: Debugging
        // console.log('Rooms selected:', roomCount, 'Max available:', maxRooms);
      });

      // Function to calculate the return date
      function calculateReturnDate() 
      {
        const selectedDate = $('#dayPicker').val(); // Get the selected start date
        const nights = $('#nights').val(); // Get the number of nights

        if (selectedDate && nights) 
        {
          const startDate = new Date(selectedDate); // Convert to Date object
          startDate.setDate(startDate.getDate() + parseInt(nights)); // Add nights

          // Format the new date as yyyy-mm-dd
          const year = startDate.getFullYear();
          const month = String(startDate.getMonth() + 1).padStart(2, '0');
          const day = String(startDate.getDate()).padStart(2, '0');
          const formattedDate = `${year}-${month}-${day}`;

          // Update the return date field
          $('#returnDate').val(formattedDate);
        } 
        else 
        {
          $('#returnDate').val('');
        }
      }

      // Event listeners for changes in the date or nights dropdown
      $('#dayPicker').on('change', calculateReturnDate);
      $('#nights').on('change', calculateReturnDate);

      // Function to calculate the total cost
      function calculateTotalCost() 
      {
        const rooms = parseInt($('#rooms').val(), 10) || 1;
        const pax = parseInt($('#paxRequest').val(), 10) || 1;
        
        const maxPaxCapacity = rooms * 3; // Each room can have a maximum of 3 pax

        if (pax > maxPaxCapacity)
        {
          alert(`Maximum pax for ${rooms} room(s) is ${maxPaxCapacity}.`);
          return;
        }

        // Calculate pax surcharge
        const roomCapacity = rooms * 2; // Each room accommodates 2 pax without surcharge
        const extraPax = pax > roomCapacity ? pax - roomCapacity : 0; // Excess pax above free capacity
        const paxSurcharge = extraPax > 0 ? extraPax * 50 : 0; // $50 surcharge for each extra pax

        // Update additional cost span
        $('#additionalRoomCost').text(paxSurcharge.toFixed(2));

        // Update fields with total cost
        const roomPrice = parseFloat($('#roomPrice').val()) || 0;
        const totalCost = roomPrice * rooms + paxSurcharge;
        const totalCostInPhp = totalCost * usdToPhp;

        $('#totalCostPHP').val(totalCostInPhp.toFixed(2));
        $('#totalCostUSD').val(totalCost.toFixed(2));
        $('#totalPrice').text(totalCost.toFixed(2));
        $('#totalPricePhp').text(totalCostInPhp.toLocaleString('en-PH', { minimumFractionDigits: 2 }));
      }

      // Event listener for cost calculations
      $('#dayPicker, #nights, #rooms, #paxRequest, #roomPrice').on('change input', calculateTotalCost);

      // Initial cost calculation on page load
      calculateTotalCost();
    });
  </script>


  <!-- <script>
    // Get elements
    const dayPicker = document.getElementById('dayPicker');
    const nightsSelect = document.getElementById('nights');
    const hotelsSelect = document.getElementById('hotels');
    const tripSelect = document.getElementById('trip');
    const returnDateInput = document.getElementById('returnDate');
    const totalCostInputPHP = document.getElementById('totalCostPHP');
    const totalCostInputUSD = document.getElementById('totalCostUSD');
    const totalPriceSpan = document.getElementById('totalPrice');
    const totalPricePhpSpan = document.getElementById('totalPricePhp');
    const paxRequestInput = document.getElementById('paxRequest');
    const roomsInput = document.getElementById('rooms');
    const roomsErrorLabel = document.getElementById('roomsError');
    const paxErrorLabel = document.getElementById('paxError');
    const additionalRoomCostSpan = document.getElementById('additionalRoomCost');

    // Conversion rate from PHP
    const usdToPhp = parseFloat(<?php echo json_encode(number_format($usd_to_php, 2, '.', '')); ?>) || 1.00;

    // Function to calculate the return date and total cost
    function calculateDetails() 
    {
      const selectedDate = new Date(dayPicker.value); // Get the selected start date
      const nights = parseInt(nightsSelect.value, 10); // Get the number of nights
      const rooms = Math.max(parseInt(roomsInput.value, 10) || 1, 1); // Number of rooms (minimum 1)
      const pax = Math.max(parseInt(paxRequestInput.value, 10) || 1, 1); // Get pax (minimum 1)
      const selectedHotelOption = hotelsSelect.options[hotelsSelect.selectedIndex];
      const baseHotelCost = parseInt(selectedHotelOption.getAttribute('data-price'), 10); // Get hotel price
      const selectedTripOption = tripSelect.options[tripSelect.selectedIndex];
      const tripCost = parseInt(selectedTripOption.getAttribute('data-price'), 10); // Get trip price

      // Reset error labels
      roomsErrorLabel.classList.add('d-none');
      paxErrorLabel.classList.add('d-none');
      additionalRoomCostSpan.textContent = '0.00';

      // Validate inputs
      if (isNaN(selectedDate) || isNaN(nights) || isNaN(baseHotelCost) || isNaN(tripCost)) 
      {
        returnDateInput.value = '';
        totalCostInputPHP.value = '';
        totalCostInputUSD.value = '';
        totalPriceSpan.textContent = '0.00';
        totalPricePhpSpan.textContent = '0.00';
        return;
      }

      // Calculate maximum pax capacity
      const maxPaxCapacity = rooms * 3; // Each room can have a maximum of 3 pax

      // Validate pax against maximum capacity
      if (pax > maxPaxCapacity) 
      {
        paxErrorLabel.textContent = `Maximum pax for ${rooms} room(s) is ${maxPaxCapacity}.`;
        paxErrorLabel.classList.remove('d-none');
        return; // Stop further calculations if max capacity is exceeded
      }

      // Calculate return date
      const returnDate = new Date(selectedDate);
      returnDate.setDate(selectedDate.getDate() + nights);
      returnDateInput.value = returnDate.toISOString().split('T')[0]; // Format as YYYY-MM-DD

      // Calculate total hotel cost with weekend surcharge
      let totalHotelCost = 0;
      const tempDate = new Date(selectedDate);

      for (let i = 0; i < nights; i++) 
      {
        const dayOfWeek = tempDate.getDay(); // 0 = Sunday, ..., 6 = Saturday
        let dailyCost = baseHotelCost;

        if (dayOfWeek === 5 || dayOfWeek === 6) 
        {
          dailyCost += 20; // Weekend surcharge
        }

        totalHotelCost += dailyCost * rooms; // Multiply by number of rooms
        tempDate.setDate(tempDate.getDate() + 1);
      }

      // Calculate pax surcharge
      const roomCapacity = rooms * 2; // Each room accommodates 2 pax without surcharge
      const extraPax = pax > roomCapacity ? pax - roomCapacity : 0; // Excess pax above free capacity
      const paxSurcharge = extraPax > 0 ? extraPax * 50 : 0; // $50 surcharge for each extra pax

      // Update additional cost span
      additionalRoomCostSpan.textContent = paxSurcharge.toFixed(2);

      // Calculate total cost
      const totalCost = totalHotelCost + tripCost + paxSurcharge;

      // Update input and spans
      totalCostInputPHP.value = (totalCost * usdToPhp).toFixed(2); // In PHP
      totalCostInputUSD.value = totalCost.toFixed(2); // In USD
      totalPriceSpan.textContent = totalCost.toFixed(2); // In USD
      totalPricePhpSpan.textContent = (totalCost * usdToPhp).toLocaleString('en-PH', { minimumFractionDigits: 2 });
    }

    // Add event listeners
    dayPicker.addEventListener('change', calculateDetails);
    nightsSelect.addEventListener('change', calculateDetails);
    hotelsSelect.addEventListener('change', calculateDetails);
    tripSelect.addEventListener('change', calculateDetails);
    paxRequestInput.addEventListener('input', calculateDetails);
    roomsInput.addEventListener('input', calculateDetails);
  </script> -->









  </body>
</html>