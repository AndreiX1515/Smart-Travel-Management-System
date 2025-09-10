<!DOCTYPE html>

<?php
session_start();
require "../conn.php";

echo "<script>console.log('Session Data:', " . json_encode($_SESSION, JSON_PRETTY_PRINT) . ");</script>";

?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <?php include "../Agent Section/includes/head.php"; ?>


  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-addBooking.css?v=<?php echo time(); ?>">
</head>

<body>

  <?php include "../Agent Section/includes/sidebar.php"; ?>

  <div class="main-container">

    <div class="navbar">
      <div class="page-header-wrapper">

        <div class="page-header-top">
          <div class="back-btn-wrapper">
            <button class="back-btn" id="redirect-btn">
              <i class="fas fa-chevron-left"></i>
            </button>
          </div>
        </div>

        <div class="page-header-content">
          <div class="page-header-text">
            <h5 class="header-title">Booking</h5>
          </div>
        </div>

      </div>
    </div>

    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Agent Section/agent-dashboard.php'; // Replace with your actual URL
      });
    </script>

    <div class="main-content">

      <?php
      // Set flightId to session value by default, if available
      $flightId = $_SESSION['agent_flightId'] ?? null;

      // If GET is set, override flightId and update session
      if (isset($_GET['flightid'])) {
        $flightId = $_GET['flightid'];
        $_SESSION['agent_flightId'] = $flightId;
      }

      // Proceed only if flightId is available
      if ($flightId) {
        // SQL query to join flight and package tables
        $sql1 = "SELECT flight.*, package.packageName, flight.landPrice as packagePrice
                      FROM flight
                      JOIN package ON flight.packageId = package.packageId
                      WHERE flight.flightId = ?";

        // Prepare the statement
        if ($stmt = $conn->prepare($sql1)) {
          // Bind the flightId as an integer parameter
          $stmt->bind_param("i", $flightId);

          // Execute the statement
          if ($stmt->execute()) {
            $result = $stmt->get_result();

            // Check if a row is returned
            if ($result->num_rows > 0) {
              // Fetch the data
              while ($row = $result->fetch_assoc()) {
                $packageId = $row['packageId'];
                $packageName = $row['packageName'];
                $packagePrice = $row['packagePrice'];
                $origin = $row['origin'];
                $year = date('Y', strtotime($row['flightDepartureDate']));
                $month = date('F', strtotime($row['flightDepartureDate']));
                $flightDepartureDate = $row['flightDepartureDate'];
                $flightPrice = $row['flightPrice'];
                $wholesalePrice = $row['wholesalePrice'];
              }
            } else {
              echo "No flight found with that ID.";
            }
          } else {
            echo "Error executing query: " . $stmt->error;
          }
          // Close the statement
          $stmt->close();
        } else {
          echo "Error preparing statement: " . $conn->error;
        }
      }
      ?>

      <div class="booking-wrapper">

        <form id="bookingForm">

          <!-- Main Booking Details Card -->
          <div class="card-container">

            <header class="section-header">
              <h2>Booking Details</h2>
            </header>

            <div class="card-body">

              <div class="form-grid">

                <!-- Flight Date Selection -->
                <div class="form-group">

                  <div class="field-wrapper">
                    <label for="flightDate" class="form-label">
                      Flight Date <span class="required-indicator">*</span>
                    </label>

                    <select class="form-control form-select" id="flightDate" name="flightDate" required>
                      <option value="" disabled selected>Select Flight Date</option>
                      <?php
                      // Query to fetch packageId and packageName
                      $sql1 = mysqli_query($conn, "SELECT flightId, flightDepartureDate, flightPrice, wholesalePrice FROM flight WHERE flightDepartureDate >= CURDATE() ORDER BY flightDepartureDate ASC");

                      // Loop through the result to create options
                      while ($res1 = mysqli_fetch_array($sql1)) {

                        // Check if this packageId is equal to the selected packageId (to mark it as selected)
                        $formattedRetailPrice = number_format($res1['flightPrice'], 2);
                        $formattedWholesalePrice = number_format($res1['wholesalePrice'], 2);

                        if ($agentType === 'Retailer') {
                          $selected = ($res1['flightId'] == $flightId) ? 'selected' : '';
                          echo "<option value='{$res1['flightId']}' {$selected}>
                                " . date('M j, Y', strtotime($res1['flightDepartureDate'])) . " || Price: ₱ {$formattedRetailPrice}
                              </option>";

                        } else if ($agentType === 'Wholeseller') {
                          $selected = ($res1['flightDepartureDate'] == $flightDepartureDate) ? 'selected' : '';
                          echo "<option value='{$res1['flightId']}' {$selected}>
                                " . date('M j, Y', strtotime($res1['flightDepartureDate'])) . " || Price: ₱ {$formattedWholesalePrice}
                              </option>";
                        }


                      }
                      ?>
                    </select>
                    <span id="flightDateError" class="error-message"></span>
                  </div>

                  <div class="field-wrapper">
                    <!-- Infant Passengers -->
                    <label for="infantPax" class="form-label">Number of Infants</label>
                    <input type="number" class="form-control" id="infantPax" name="infantPax" min="0" max="10" value="0"
                      placeholder="Enter number of infants">
                    <span class="help-text">
                      Infants (under 2 years) will not be included in total pax but will require guest information.
                    </span>
                    <span id="infantPaxError" class="error-message"></span>
                  </div>

                </div>

                <!-- Total Pax Section -->
                <div class="form-group">

                  <div class="field-wrapper">
                    <label for="totalPax" class="form-label">
                      Total Passengers <span class="required-indicator">*</span>
                    </label>

                    <input type="number" class="form-control" id="totalPax" name="totalPax" min="1" max="50"
                      placeholder="Enter number of passengers" required>

                    <span id="totalPaxError" class="text-danger"></span>
                  </div>

                  <!-- Seat Availability Info -->
                  <div class="pax-info" id="paxInfo">
                    <div class="pax-stats">
                      <div class="stat-item">
                        <span class="stat-label">Max Capacity</span>
                        <span class="stat-value" id="maxSeats"></span>
                      </div>
                      <div class="stat-item">
                        <span class="stat-label">Remaining</span>
                        <span class="stat-value" id="availSeats"></span>
                      </div>
                      <div class="stat-item">
                        <span class="stat-label">Available</span>
                        <span class="stat-value success" id="remainingSeats">0</span>
                      </div>
                    </div>
                  </div>


                </div>
              </div>

              <!-- <br class="br-divider"> -->

              <!-- Land Only Option -->
              <div class="checkbox-group" onclick="toggleCheckbox('landOnly')">
                <input type="checkbox" id="landOnly" name="landOnly" value="1" class="checkbox-input">
                <label for="landOnly" class="checkbox-label">
                  Land Package Only (No Flight)
                </label>
              </div>

              <!-- Collapsible Flight Details Section -->
              <div class="collapsible-content" id="flightDetailsContainer">
                <div class="form-group">
                  <div class="field-wrapper">
                    <label for="flightDetails" class="form-label mb-1">
                      Flight Details for Package Only
                    </label>
                    <textarea class="form-control" id="flightDetails" name="flightDetails" rows="4"
                      placeholder="Please provide additional flight details, special requests, or notes here..."></textarea>
                    <span class="help-text">
                      Include any special requirements, seat preferences, or additional information
                    </span>
                  </div>
                </div>
              </div>

              <!-- Hidden Fields -->
              <fieldset class="sr-only"> <!-- Add "visible" to the class to make it inputs Visible -->
                <input type="text" id="agentCode" name="agentCode" value="<?php echo $_SESSION['agentCode']; ?>">
                <input type="text" id="flightId" name="flightId" value="<?php echo $flightId; ?>">
                <input type="text" id="packagePrice" name="packagePrice"
                  value="<?php echo isset($packagePrice) ? $packagePrice : ''; ?>">
                <input type="text" name="flightPrice" id="flightPrice"
                  value="<?php echo isset($agentType) ? ($agentType === 'Retailer' ? htmlspecialchars($flightPrice) : htmlspecialchars($wholesalePrice)) : ''; ?>">
                <input type="text" name="agentId" id="agentId" value="<?php echo $_SESSION['agentId']; ?>">
                <input type="text" name="agentType" value="<?php echo $_SESSION['agentType']; ?>">
                <input type="text" name="accId" id="accId" value="<?php echo $_SESSION['agent_accountId']; ?>">
                <input type="text" name="packageId" id="packageId"
                  value="<?php echo isset($packageId) ? $packageId : ''; ?>">
                <input type="text" name="packageName" id="packageName"
                  value="<?php echo isset($packageName) ? $packageName : ''; ?>">
                <input type="text" name="origin" id="origin" value="<?php echo isset($origin) ? $origin : ''; ?>">
              </fieldset>

            </div>

            <!-- <footer class="card-footer">
                  <div class="price-display" id="priceDisplay" style="display: none;">
                      Total Price: ₱ <span id="totalPrice">0.00</span>
                  </div>
              </footer> -->
          </div>

          <div class="card-container">
            <header class="section-header">
              <h2>Contact Details</h2>
            </header>

            <div class="card-body">

              <div class="form-grid">

                <div class="form-group">

                  <div class="field-wrapper">
                    <label for="fName">First Name <span class="text-danger"> *</span></label>
                    <input type="text" name="fName" id="fName" class="form-control" placeholder="Enter First Name"
                      tabindex="1" required>

                    <span id="fNameError" class="text-danger"></span>
                    <!-- Error message for First Name -->
                  </div>

                  <div class="form-group">
                    <div class="field-wrapper">
                      <div class="label-container-2">
                        <label for="mName">Middle Name </label>
                        <!-- <span class="text-secondary">Type N/A if none</span> -->
                      </div>

                      <input type="text" name="mName" id="mName" class="form-control" placeholder="Enter Middle Name"
                        tabindex="3">

                      <span id="mNameError" class="text-danger"></span>
                      <!-- Error message for Middle Name -->

                    </div>
                  </div>


                  <div class="field-wrapper contact-number">
                    <label for="contactNo" class="contactNo">Contact Number <span class="text-danger">*</span></label>

                    <div class="input-group">
                      <select name="countryCode" id="countryCode" class="form-control" tabindex="5" required>
                        <option disabled>Country Code</option>
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
                        <option value="+63" selected>Philippines (+63)</option>
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

                      <input type="tel" class="form-control" id="contactNo" name="contactNo"
                        placeholder="Contact Number" tabindex="6" required>
                    </div>

                    <span id="contactNoError" class="text-danger"></span>
                  </div>

                </div>

                <div class="form-group">

                  <div class="field-wrapper">
                    <label for="lName">Last Name <span class="text-danger"> *</span> </label>
                    <input type="text" name="lName" id="lName" class="form-control" placeholder="Enter Last Name"
                      tabindex="2" required>
                    <span id="lNameError" class="text-danger"></span>
                    <!-- Error message for Last Name -->
                  </div>

                  <div class="field-wrapper">
                    <label for="suffix">Suffix </label>
                    <select class="form-control" name="suffix" id="suffix" tabindex="4">
                      <option value="" selected disabled>Select Suffix</option>
                      <option value="">None</option>
                      <option value="Jr.">Jr.</option>
                      <option value="Sr.">Sr.</option>
                      <option value="II">II</option>
                      <option value="III">III</option>
                      <option value="IV">IV</option>
                      <option value="V">V</option>
                    </select>

                    <span id="suffixError" class="text-danger"></span>
                    <!-- Error message for Suffix -->
                  </div>

                  <div class="field-wrapper">
                    <label for="email">Email <span class="text-danger"> *</span></label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Address"
                      tabindex="7" required>
                    <span id="emailError" class="text-danger"></span> <!-- Error message for Email -->
                  </div>

                </div>


              </div>

            </div>
          </div>

          <div class="card-container total-price-wrapper">
            <div class="card-body-footer">
              <div class="price-display">
                Total Price: ₱ <span id="totalPriceDisplay">0.00</span>
              </div>
              <input type="hidden" id="totalPrice" name="totalPrice" placeholder="Total Price">

              <div class="button-wrapper">
                <button type="button" class="btn btn-primary" id="bookNowButton">Book Now</button>
              </div>
            </div>
          </div>

          <!-- Booking Summary Modal -->
          <div class="modal fade" id="BookingSummaryModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Added modal-lg for a wider modal -->
              <div class="modal-content position-relative">

                <!-- Close Button -->
                <button type="button" class="btn-close close-outside p-4" data-bs-dismiss="modal"
                  aria-label="Close"></button>

                <!-- Modal Body -->
                <div class="modal-body">

                  <!-- Booking Summary Container -->
                  <div class="confirmation-container">

                    <!-- Modal Header Section: Logo -->
                    <div class="modal-header">
                      <img src="../Assets/Logos/SMART LOGO 2 (2).png" alt="Trip Image" class="img-fluid"
                        style="max-width: 250px; max-height: 80px;">
                    </div>

                    <!-- Combined Info Section -->
                    <div class="info-section">
                      <div class="summary-title-container">
                        <!-- Booking Summary Title -->
                        <h5 class="summary-title">BOOKING SUMMARY</h5>
                      </div>

                      <!-- Contact Info -->
                      <div class="info-item">
                        <strong>Contact Guest Name:</strong> <span id="contactPersonName">Sample Name</span>
                      </div>
                      <div class="info-item">
                        <strong>Contact Email:</strong> <span id="contactPersonEmail">Sample Email</span>
                      </div>

                      <!-- Package Details -->
                      <div class="info-item">
                        <strong>Package Name:</strong> <span id="selectedPackage">No Package Selected</span>
                      </div>
                      <div class="info-item">
                        <strong>No. of Guests:</strong> <span id="guestCount">1</span>
                      </div>
                      <div class="info-item">
                        <strong>No. of Infant:</strong> <span id="infantCount"></span>
                      </div>

                      <!-- Flight/Origin Details -->
                      <div class="info-item">
                        <strong>Origin:</strong> <span id="selectedOrigin">No Origin Selected</span>
                      </div>
                      <div class="info-item">
                        <strong>Flight Date:</strong> <span id="selectedDate">No Flight Date Selected</span>
                      </div>
                    </div>

                    <!-- Modal Footer with Buttons -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary" name="bookNow" id="bookNowSubmit">Proceed to
                        Payment</button>
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

  <?php require "../Agent Section/includes/scripts.php"; ?>


  <!-- Custom Script for Interactive Features -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const landOnlyCheckbox = document.getElementById('landOnly');
      const flightDetailsContainer = document.getElementById('flightDetailsContainer');
      const totalPaxInput = document.getElementById('totalPax');
      const remainingSeatsSpan = document.getElementById('remainingSeats');
      const availSeatsSpan = document.getElementById('availSeats');
      const paxInfo = document.getElementById('paxInfo');

      // Toggle flight details with smooth animation
      landOnlyCheckbox.addEventListener('change', function () {
        if (this.checked) {
          flightDetailsContainer.classList.add('show');
        } else {
          flightDetailsContainer.classList.remove('show');
        }
      });



      // Update remaining seats with visual feedback
      function updateRemainingSeats() {
        let totalPax = parseInt(totalPaxInput.value, 10);

        const availableSeats = parseInt(availSeatsSpan.textContent, 10) || 0;
        let remaining;

        if (isNaN(totalPax) || totalPax <= 0) {
          // If input is empty or invalid → reset to 0
          totalPax = 0;
          remaining = availableSeats;
        } else if (totalPax > availableSeats) {
          // Clamp input value to max available
          totalPax = availableSeats;
          totalPaxInput.value = availableSeats;
          remaining = 0;
        } else {
          remaining = availableSeats - totalPax;
        }

        // Update remaining seats text
        remainingSeatsSpan.textContent = remaining;

        // Reset feedback classes
        remainingSeatsSpan.className = 'stat-value';

        if (totalPax > availableSeats) {
          remainingSeatsSpan.classList.add('danger');
        } else if (remaining < 5) {
          remainingSeatsSpan.classList.add('warning');
        } else {
          remainingSeatsSpan.classList.add('success');
        }

        // Animate pax info
        paxInfo.classList.add('updating');
        setTimeout(() => paxInfo.classList.remove('updating'), 200);
      }

      totalPaxInput.addEventListener('input', updateRemainingSeats);



      // Form validation
      // const form = document.getElementById('bookingForm');

      // form.addEventListener('submit', function (e) {
      //   let isValid = true;

      //   const totalPax = parseInt(totalPaxInput.value);
      //   const availSeats = parseInt(availSeatsSpan.textContent);

      //   if (totalPax > availSeats) {
      //     document.getElementById('totalPaxError').textContent = 'Exceeds available seats';
      //     isValid = false;
      //   } else {
      //     document.getElementById('totalPaxError').textContent = '';
      //   }

      //   if (!isValid) {
      //     e.preventDefault();
      //   }
      // });


      // Smooth focus transitions
      const inputs = document.querySelectorAll('.form-control');
      inputs.forEach(input => {
        input.addEventListener('focus', function () {
          this.parentElement.style.transform = 'translateY(-1px)';
        });

        input.addEventListener('blur', function () {
          this.parentElement.style.transform = 'translateY(0)';
        });
      });


    });



    // Helper function for checkbox
    function toggleCheckbox(id) {
      const checkbox = document.getElementById(id);
      checkbox.checked = !checkbox.checked;
      checkbox.dispatchEvent(new Event('change'));
    }
  </script>

  <!-- Input Validation for Total Pax -->
  <script>
    document.getElementById("totalPax").addEventListener("input", function () {
      // Allow only digits
      this.value = this.value.replace(/[^0-9]/g, "");

      // Handle min/max bounds
      const min = parseInt(this.min);
      const max = parseInt(this.max);

      if (this.value !== "") {
        let val = parseInt(this.value);

        if (val < min) {
          this.value = min;
        } else if (val > max) {
          this.value = max;
        }
      }
    });

  </script>

  <!-- <script>
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
  </script> -->

  <!-- Form Submission & Flight Date Change Script  -->

  <script>

    // Booking Form Handler - Function-based approach with best practices

    // Configuration object
    const BOOKING_CONFIG = {
      endpoints: {
        fetchFlightDetails: '../Agent Section/functions/fetchFlightDetails.php',
        fetchMaxSeats: '../Agent Section/functions/fetchMaxSeatsPerAgent.php',
        submitBookingData: '../Agent Section/functions/agent-revisedAddBooking-code.php'
      },
      validation: {
        minPax: 1,
        maxPax: 999,
        minInfants: 0,
        maxInfants: 10
      },
      debounceDelay: 300
    };

    // Application state
    let bookingState = {
      flightData: {},
      maxSeats: {},
      pricing: {},
      validation: {}
    };

    // Utility functions
    function debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }

    function formatCurrency(amount) {
      return new Intl.NumberFormat('en-PH', {
        style: 'decimal',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }).format(amount);
    }

    function isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    }

    function showLoading() {
      console.log('Loading...');
      // Add loading spinner implementation here
    }

    function hideLoading() {
      console.log('Loading complete');
      // Hide loading spinner implementation here
    }

    function showError(message) {
      console.error(message);
      // You can implement a proper error display mechanism here
      const errorElement = document.getElementById('errorMessage');
      if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
      } else {
        alert(message); // Fallback
      }
    }

    // Field validation functions
    function showFieldError(element, message) {
      if (!element) return;

      element.classList.add('is-invalid');
      const errorSpan = document.getElementById(element.id + 'Error');
      if (errorSpan) {
        errorSpan.textContent = message;
        errorSpan.style.display = 'block';
      }
    }

    function clearFieldError(element) {
      if (!element) return;

      element.classList.remove('is-invalid');
      const errorSpan = document.getElementById(element.id + 'Error');
      if (errorSpan) {
        errorSpan.textContent = '';
        errorSpan.style.display = 'none';
      }
    }

    function validateField(element) {
      if (!element) return false;

      const value = element.value.trim();
      if (!value) return false;

      if (element.type === 'email' && !isValidEmail(value)) {
        showFieldError(element, 'Please enter a valid email address.');
        return false;
      }

      return true;
    }


    // API functions - Fetch flight details
    async function fetchFlightDetails(flightId) {
      try {
        const formData = new FormData();
        formData.append('flightId', flightId);
        formData.append('agentType', document.querySelector('input[name="agentType"]')?.value || '');

        const response = await fetch(BOOKING_CONFIG.endpoints.fetchFlightDetails, {
          method: 'POST',
          body: formData
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        bookingState.flightData = data;
        console.log('Fetched flight details:', data);
        return data;

      } catch (error) {
        console.error('Error fetching flight details:', error);
        throw error;
      }
    }

    // API functions - Fetch Max Seats details
    async function fetchMaxSeats(flightId, accId) {
      try {
        const formData = new FormData();

        formData.append('flightId', flightId);
        formData.append('accId', accId);

        const response = await fetch(BOOKING_CONFIG.endpoints.fetchMaxSeats, {
          method: 'POST',
          body: formData
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const dataMaxSeats = await response.json();
        console.log('Fetched max seats:', dataMaxSeats);

        return dataMaxSeats;

      } catch (error) {
        console.error('Error fetching max seats:', error);
        throw error;
      }
    }


    // Flight handling functions
    async function handleFlightDateChange(flightId) {
      if (!flightId) return;

      try {
        showLoading();

        // Update hidden flight ID
        const flightIdInput = document.getElementById('flightId');

        if (flightIdInput) {
          flightIdInput.value = flightId;
        }

        // Update displayed date
        const selectedOption = document.querySelector(`#flightDate option[value="${flightId}"]`);
        if (selectedOption) {
          const selectedDate = selectedOption.text.split(' || ')[0].trim();
          const selectedDateElement = document.getElementById('selectedDate');
          if (selectedDateElement) {
            selectedDateElement.textContent = selectedDate;
          }
        }

        // Fetch flight details
        const flightData = await fetchFlightDetails(flightId);
        updateFlightDetails(flightData);

        // Update max seats
        await updateMaxSeats();

        // Reset passenger counts
        resetPassengerCounts();

        // Update pricing
        updateTotalPrice();

        hideLoading();

      } catch (error) {
        console.error('Error handling flight date change:', error);
        showError('Failed to load flight details. Please try again.');
        hideLoading();
      }
    }


    function updateFlightDetails(data) {

      const fieldMappings = {
        packagePrice: 'packagePrice',
        packageName: 'packageName',
        flightPricee: 'flightPrice',
        packageId: 'packageId',
        origin: 'origin'
      };

      Object.entries(fieldMappings).forEach(([elementId, dataKey]) => {
        const element = document.getElementById(elementId);
        if (element && data[dataKey] !== undefined) {
          element.value = data[dataKey];
        }
      });

      // Update total price display
      const displayTotalPrice = document.getElementById('totalPriceDisplay');

      if (displayTotalPrice && data.totalPrice) {
        displayTotalPrice.textContent = formatCurrency(data.totalPrice);
      }
    }

    async function updateMaxSeats() {
      const flightId = document.getElementById('flightId')?.value;
      const accId = document.getElementById('accId')?.value;
      const isLandOnly = document.getElementById('landOnly')?.checked || false;

      if (!flightId) return;

      try {
        const data = await fetchMaxSeats(flightId, accId);

        if (data.flightId) {
          updateSeatDisplay(data, isLandOnly);
        }

      } catch (error) {
        console.error('Error updating max seats:', error);
      }
    }


    function updateSeatDisplay(data, isLandOnly) {
      const maxSeatsElement = document.getElementById('maxSeats');
      const availSeatsElement = document.getElementById('availSeats');
      const totalPaxElement = document.getElementById('totalPax');
      const remainingPaxElement = document.getElementById('remainingSeats');


      if (isLandOnly) {
        if (maxSeatsElement) maxSeatsElement.textContent = '';
        if (availSeatsElement) availSeatsElement.textContent = '';
        if (remainingPaxElement) availSeatsElement.textContent = '';
        if (totalPaxElement) totalPaxElement.setAttribute('max', BOOKING_CONFIG.validation.maxPax);

      } else {

        if (maxSeatsElement) maxSeatsElement.textContent = data.maxSeats;
        if (availSeatsElement) availSeatsElement.textContent = `${data.totalSeatsLeft}`;
        if (totalPaxElement) {
          totalPaxElement.setAttribute('max', data.maxSeats);

          // Adjust current value if it exceeds max
          const currentPax = parseInt(totalPaxElement.value) || 0;
          if (currentPax > data.maxSeats) {
            totalPaxElement.value = data.maxSeats;
          }
        }
      }
    }


    // Passenger handling functions
    function validatePaxInput(input) {
      const value = parseInt(input.value) || 0;
      const max = parseInt(input.getAttribute('max')) || BOOKING_CONFIG.validation.maxPax;
      const min = BOOKING_CONFIG.validation.minPax;

      if (value > max) {
        input.value = max;
      } else if (value < min && input.value !== '') {
        input.value = min;
      }
    }

    function validateInfantInput(input) {
      const value = parseInt(input.value) || 0;
      const max = BOOKING_CONFIG.validation.maxInfants;
      const min = BOOKING_CONFIG.validation.minInfants;

      if (value > max) {
        input.value = max;
      } else if (value < min) {
        input.value = min;
      }
    }

    function resetPassengerCounts() {
      const totalPaxInput = document.getElementById('totalPax');
      const infantPaxInput = document.getElementById('infantPax');


      if (totalPaxInput) totalPaxInput.value = '';
      if (infantPaxInput) infantPaxInput.value = '0';
    }




    // Land only functions
    function handleLandOnlyToggle(isChecked) {
      const flightDetailsContainer = document.getElementById('flightDetailsContainer');

      if (flightDetailsContainer) {
        flightDetailsContainer.style.display = isChecked ? 'block' : 'none';
      }

      updateMaxSeats();
      updateTotalPrice();
    }


    // Pricing functions
    function updateTotalPrice() {
      const totalPaxElement = document.getElementById('totalPax');
      const isLandOnlyElement = document.getElementById('landOnly');
      const totalPax = parseInt(totalPaxElement?.value) || 0;
      const isLandOnly = isLandOnlyElement?.checked || false;
      let totalPrice = 0;

      if (isLandOnly) {
        const packagePriceElement = document.getElementById('packagePrice');
        const packagePrice = parseFloat(packagePriceElement?.value) || 0;
        totalPrice = packagePrice * totalPax;
      } else {
        const flightPriceElement = document.getElementById('flightPrice');
        const flightPriceValue = flightPriceElement?.value || '0';
        const flightPrice = parseFloat(flightPriceValue.replace(/[,₱]/g, ''));
        totalPrice = flightPrice * totalPax;
      }

      // Update total price displays
      const totalPriceDisplayElement = document.getElementById('totalPriceDisplay');
      if (totalPriceDisplayElement) {
        totalPriceDisplayElement.textContent = formatCurrency(totalPrice);
      }

      // Update hidden total price field
      const totalPriceField = document.getElementById('totalPrice');
      if (totalPriceField) {
        totalPriceField.value = totalPrice.toFixed(2);
      }
    }

    // Currency formatting function
    function formatCurrency(amount) {
      return parseFloat(amount || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }

    // Event listeners for pricing
    document.addEventListener('DOMContentLoaded', function () {
      // Total passengers input change
      const totalPaxInput = document.getElementById('totalPax');
      if (totalPaxInput) {
        totalPaxInput.addEventListener('input', updateTotalPrice);
        totalPaxInput.addEventListener('change', updateTotalPrice);
      }

      // Land only checkbox change
      const landOnlyCheckbox = document.getElementById('landOnly');
      if (landOnlyCheckbox) {
        landOnlyCheckbox.addEventListener('change', updateTotalPrice);
      }

      // Flight date selection change
      const flightDateSelect = document.getElementById('flightDate');
      if (flightDateSelect) {
        flightDateSelect.addEventListener('change', function () {
          // Extract price from selected option and update flightPrice field
          const selectedOption = this.options[this.selectedIndex];
          if (selectedOption && selectedOption.text) {
            const priceMatch = selectedOption.text.match(/₱\s*([\d,]+\.?\d*)/);
            if (priceMatch) {
              const flightPriceField = document.getElementById('flightPrice');
              if (flightPriceField) {
                flightPriceField.value = priceMatch[1].replace(/,/g, '');
              }
            }
          }
          updateTotalPrice();
        });
      }
    });


    function checkSeatAvailability() {
      const isLandOnly = document.getElementById('landOnly')?.checked || false;
      if (isLandOnly) return true;

      const totalPax = parseInt(document.getElementById('totalPax')?.value) || 0;
      const availSeatsText = document.getElementById('availSeats')?.textContent || '';
      const totalSeats = parseInt(availSeatsText.replace(/\D/g, '')) || 0;

      if (totalPax > totalSeats) {
        showError('The available seats are not enough for your party size.');
        return false;
      }
      return true;
    }

    async function handleBookNow(event) {
      event.preventDefault();
      try {
        const validationResult = validateForm();
        if (!validationResult.isValid) {
          displayValidationErrors(validationResult.errors);
          return;
        }

        if (!checkSeatAvailability()) {
          return;
        }

        // Add the actual booking logic here
        // This function currently doesn't do anything after validation

      } catch (error) {
        console.error('Error in book now process:', error);
        showError('An error occurred while processing your booking. Please try again.');
      }
    }





    function displayValidationErrors(errors) {
      errors.forEach(error => {
        showFieldError(error.element, error.message);
      });
    }

    // Event binding functions
    function bindFlightDateEvents() {
      const flightDateElement = document.getElementById('flightDate');
      if (flightDateElement) {
        flightDateElement.addEventListener('change', (e) => {
          handleFlightDateChange(e.target.value);
        });
      }
    }

    function bindPassengerEvents() {
      const totalPaxElement = document.getElementById('totalPax');
      const infantPaxElement = document.getElementById('infantPax');

      if (totalPaxElement) {
        const debouncedPaxHandler = debounce((e) => {
          validatePaxInput(e.target);
          updateTotalPrice();
        }, BOOKING_CONFIG.debounceDelay);

        totalPaxElement.addEventListener('input', debouncedPaxHandler);
        totalPaxElement.addEventListener('change', () => updateTotalPrice());
      }

      if (infantPaxElement) {
        const debouncedInfantHandler = debounce((e) => {
          validateInfantInput(e.target);
        }, BOOKING_CONFIG.debounceDelay);

        infantPaxElement.addEventListener('input', debouncedInfantHandler);
      }
    }

    function bindLandOnlyEvents() {
      const landOnlyElement = document.getElementById('landOnly');
      if (landOnlyElement) {
        landOnlyElement.addEventListener('change', (e) => {
          handleLandOnlyToggle(e.target.checked);
        });
      }
    }

    function bindBookingEvents() {
      const bookNowButton = document.getElementById('bookNowButton');
      if (bookNowButton) {
        bookNowButton.addEventListener('click', handleBookNow);
      }
    }

    function bindValidationEvents() {
      const form = document.getElementById('addBookingFlight');
      if (!form) return;

      const inputs = form.querySelectorAll('input, select');
      inputs.forEach(input => {
        input.addEventListener('focus', () => clearFieldError(input));
        input.addEventListener('blur', () => validateField(input));
      });
    }

    function bindPricingEvents() {
      const flightDateElement = document.getElementById('flightDate');
      const totalPaxElement = document.getElementById('totalPax');

      if (flightDateElement) {
        flightDateElement.addEventListener('change', updateTotalPrice);


      }

      if (totalPaxElement) {
        totalPaxElement.addEventListener('input', updateTotalPrice);
        totalPaxElement.addEventListener('change', updateTotalPrice);
      }
    }


    // Initialization function
    function initializeBookingForm() {
      // Bind all events
      bindFlightDateEvents();
      bindPassengerEvents();
      bindLandOnlyEvents();
      bindBookingEvents();
      bindValidationEvents();
      bindPricingEvents();

      // Initialize form state
      updateTotalPrice();
      updateMaxSeats();
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', initializeBookingForm);

    // Make key functions available globally for debugging/external use
    window.bookingFormUtils = {
      updateTotalPrice
    };
  </script>




  <script>
    // Function to collect all form data with enhanced logging
    function collectFormData() {
      console.log('Starting form data collection...');

      const formData = {
        // Main Booking Details
        flightDate: document.getElementById('flightDate')?.value || '',
        infantPax: document.getElementById('infantPax')?.value || '0',
        totalPax: document.getElementById('totalPax')?.value || '',
        landOnly: document.getElementById('landOnly')?.checked ? '1' : '0',
        flightDetails: document.getElementById('flightDetails')?.value || '',

        // Contact Details
        fName: document.getElementById('fName')?.value || '',
        mName: document.getElementById('mName')?.value || '',
        lName: document.getElementById('lName')?.value || '',
        suffix: document.getElementById('suffix')?.value || '',
        countryCode: document.getElementById('countryCode')?.value || '',
        contactNo: document.getElementById('contactNo')?.value || '',
        email: document.getElementById('email')?.value || '',

        // Hidden Fields (Session/System Data)
        agentCode: document.getElementById('agentCode')?.value || '',
        flightId: document.getElementById('flightId')?.value || '',
        packagePrice: document.getElementById('packagePrice')?.value || '',
        flightPrice: document.getElementById('flightPrice')?.value || '',
        agentId: document.getElementById('agentId')?.value || '',
        agentType: document.querySelector('input[name="agentType"]')?.value || '',
        accId: document.getElementById('accId')?.value || '',
        packageId: document.getElementById('packageId')?.value || '',
        packageName: document.getElementById('packageName')?.value || '',
        origin: document.getElementById('origin')?.value || '',

        // Calculated Fields
        totalPrice: document.getElementById('totalPrice')?.value || ''
      };

      console.log('Form data collected successfully:', formData);
      return formData;
    }

    // Enhanced validation function
    function validateForm() {
      console.log('Starting form validation...');

      const errors = [];
      const fields = {
        totalPax: { selector: '#totalPax', message: 'Please enter total passengers.' },
        flightDate: { selector: '#flightDate', message: 'Please select flight date.' },
        fName: { selector: '#fName', message: 'Please enter first name.' },
        lName: { selector: '#lName', message: 'Please enter last name.' },
        countryCode: { selector: '#countryCode', message: 'Please select country code.' },
        contactNo: { selector: '#contactNo', message: 'Please enter contact number.' },
        email: { selector: '#email', message: 'Please enter email address.' },
      };

      // Clear previous errors
      Object.values(fields).forEach(field => {
        const element = document.querySelector(field.selector);
        if (element) {
          clearFieldError(element);
        }
      });

      // Validate each field
      Object.entries(fields).forEach(([fieldName, config]) => {
        const element = document.querySelector(config.selector);
        const value = element?.value?.trim();

        if (!value || (element.tagName === 'SELECT' && value === '')) {
          errors.push({
            field: fieldName,
            message: config.message,
            element: element
          });
        }
      });

      // Special validation for totalPax
      const totalPaxElement = document.getElementById('totalPax');
      const totalPaxError = document.getElementById('totalPaxError');
      const totalPax = parseInt(totalPaxElement?.value) || 0;

      if (totalPaxError) {
        totalPaxError.textContent = '';
      }

      if (totalPax <= 0) {
        errors.push({
          field: 'totalPax',
          message: 'Total passengers must be greater than 0.',
          element: totalPaxElement
        });
        if (totalPaxError) {
          totalPaxError.textContent = 'TOTAL PASSENGERS MUST BE GREATER THAN 0.';
        }
      }

      // Email validation
      const emailElement = document.getElementById('email');
      const email = emailElement?.value?.trim();
      if (email && !isValidEmail(email)) {
        errors.push({
          field: 'email',
          message: 'Please enter a valid email address.',
          element: emailElement
        });
      }

      // Phone number validation
      const contactNo = document.getElementById('contactNo')?.value?.trim();
      if (contactNo && contactNo.length < 10) {
        errors.push({
          field: 'contactNo',
          message: 'Contact number must be at least 10 digits.',
          element: document.getElementById('contactNo')
        });
      }

      console.log('Validation completed. Errors found:', errors.length);
      if (errors.length > 0) {
        console.log('Validation errors:', errors);
      }

      return {
        isValid: errors.length === 0,
        errors
      };
    }

    // Email validation helper
    function isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    }

    // Seat availability check
    function checkSeatAvailability() {
      console.log('Checking seat availability...');

      const isLandOnly = document.getElementById('landOnly')?.checked || false;
      console.log('Land only booking:', isLandOnly);

      if (isLandOnly) {
        console.log('Land only booking - skipping seat check');
        return true;
      }

      const totalPax = parseInt(document.getElementById('totalPax')?.value) || 0;
      const availSeatsText = document.getElementById('availSeats')?.textContent || '';
      const totalSeats = parseInt(availSeatsText.replace(/\D/g, '')) || 0;

      console.log('Total passengers requested:', totalPax);
      console.log('Available seats:', totalSeats);

      if (totalPax > totalSeats) {
        console.log('Insufficient seats available');
        showError('The available seats are not enough for your party size.');
        return false;
      }

      console.log('Seat availability check passed');
      return true;
    }


    // Enhanced AJAX submission function
    function submitFormData() {
      console.log('Starting form submission process...');

      // Re-validate before submission
      const validation = validateForm();
      if (!validation.isValid) {
        console.log('Form validation failed during submission');
        displayValidationErrors(validation.errors);
        return;
      }

      // Check seat availability
      if (!checkSeatAvailability()) {
        console.log('Seat availability check failed during submission');
        return;
      }

      const formData = collectFormData();
      formData.bookNow = true; // make sure backend detects it

      $.ajax({
        url: '../Agent Section/functions/agent-revisedAddBooking-code.php',
        type: 'POST',
        data: JSON.stringify(formData),   // send raw JSON
        contentType: 'application/json',  // tell server it's JSON
        dataType: 'json',                 // expect JSON back
        beforeSend: function () {
          console.log('AJAX request starting...');
          $('button[name="bookNow"]').prop('disabled', true).text('Processing...');
          $('.modal-backdrop').addClass('loading-backdrop');
        },

        success: function (response) {
          console.log('Server response received:', response);

          if (response.success) {
            console.log('Booking submitted successfully');
            alert('Booking submitted successfully!');

            // Close modal and redirect
            $('#BookingSummaryModal').modal('hide');

            // Optional: redirect to confirmation page
            if (response.redirectUrl) {
              window.location.href = response.redirectUrl;
            } else {
              // window.location.href = 'booking-confirmation.php';
            }
          } else {
            console.log('Booking submission failed:', response.message);
            alert('Error: ' + (response.message || 'Booking failed'));

            if (response.errors) {
              console.log('Server validation errors:', response.errors);
              displayValidationErrors(response.errors);
            }
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error Details:');
          console.error('Status:', status);
          console.error('Error:', error);
          console.error('Response Text:', xhr.responseText);

          alert('An error occurred while submitting the booking. Please try again.');
        },
        complete: function () {
          console.log('AJAX request completed');
          // Re-enable submit button
          $('button[name="bookNow"]').prop('disabled', false).text('Proceed to Payment');
          $('.modal-backdrop').removeClass('loading-backdrop');
        }
      });
    }

    // Enhanced booking summary population
    function populateBookingSummary(formData) {
      // console.log('Populating booking summary modal with data:', formData);

      try {
        // Format full name properly
        const nameComponents = [formData.fName, formData.mName, formData.lName].filter(name => name && name.trim() && name !== 'N/A');
        const fullName = nameComponents.join(' ').trim();

        $('#contactPersonName').text(fullName || 'Not specified');
        $('#contactPersonEmail').text(formData.email || 'Not specified');
        $('#selectedPackage').text(formData.packageName || 'No Package Selected');
        $('#guestCount').text(formData.totalPax || '0');
        $('#infantCount').text(formData.infantPax || '0');
        $('#selectedOrigin').text(formData.origin || 'No Origin Selected');

        // Format flight date if available
        if (formData.flightDate) {
          const flightSelect = document.getElementById('flightDate');
          if (flightSelect && flightSelect.selectedIndex > 0) {
            const selectedOption = flightSelect.options[flightSelect.selectedIndex];
            const dateText = selectedOption.text.split(' || ')[0] || 'No Flight Date Selected';
            $('#selectedDate').text(dateText);
          } else {
            $('#selectedDate').text('No Flight Date Selected');
          }
        } else {
          $('#selectedDate').text('No Flight Date Selected');
        }

        console.log('Booking summary populated successfully');
        
      } catch (error) {
        console.error('Error populating booking summary:', error);
      }
    }

    // Enhanced error display function
    function displayValidationErrors(errors) {
      console.log('Displaying validation errors:', errors);

      // Clear previous errors
      $('.error-message, .text-danger').text('');

      // Display new errors
      if (Array.isArray(errors)) {
        errors.forEach(function (error) {
          if (error.element) {
            showFieldError(error.element, error.message);
          }
        });
      } else {
        Object.keys(errors).forEach(function (fieldName) {
          const errorElement = document.getElementById(fieldName + 'Error');
          if (errorElement) {
            errorElement.textContent = errors[fieldName];
          }
        });
      }
    }

    // Event listeners with enhanced logging
    $(document).ready(function () {
      console.log('DOM ready - initializing form handlers...');

      // "Book Now" button - Show booking summary modal
      $('#bookNowButton').click(function (e) {
        console.log('=== BOOK NOW BUTTON CLICKED ===');
        e.preventDefault();

        // Validate form first
        const validation = validateForm();
        if (!validation.isValid) {
          console.log('Form validation failed - stopping process');
          validation.errors.forEach(error => {
            if (error.element) {
              showFieldError(error.element, error.message);
            }
          });
          return;
        }

        // Check seat availability
        if (!checkSeatAvailability()) {
          console.log('Seat availability check failed - stopping process');
          return;
        }

        console.log('All validations passed - proceeding with form data collection');

        // Collect and log form data
        const formData = collectFormData();
        console.log(JSON.stringify(formData, null, 2));


        // Populate modal and show
        populateBookingSummary(formData);
        $('#BookingSummaryModal').modal('show');
      });

      // "Proceed to Payment" button - Submit form via AJAX
      $('#bookNowSubmit').click(function (e) {
        console.log('=== PROCEED TO PAYMENT CLICKED ===');
        e.preventDefault();
        submitFormData();
      });

      console.log('Form event handlers initialized successfully');
    });

    // Helper functions
    function showFieldError(element, message) {
      const errorElement = document.getElementById(element.id + 'Error');
      if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        errorElement.style.color = '#dc3545';
      }
    }

    function clearFieldError(element) {
      const errorElement = document.getElementById(element.id + 'Error');
      if (errorElement) {
        errorElement.textContent = '';
        errorElement.style.display = 'none';
      }
    }

    function showError(message) {
      console.error('Error:', message);
      alert(message);
    }

  </script>



</body>

</html>