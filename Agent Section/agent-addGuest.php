<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>
  <?php include '../Agent Section/includes/sidebar.php'; ?> 

  <div class="main-content" id="mainContent">
    <?php 
      include '../Agent Section/includes/navbar.php'; 
      
      // Check if the transaction number is set in the session
      if (isset($_SESSION['transaction_number'])) 
      {
        $transactionNumber = $_SESSION['transaction_number'];
      } 
      else 
      {
        echo "No transaction number found.";
      }
    ?>

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

    <!-- PHP to fetch the pax count -->
    <?php
      $stmt = $conn->prepare("SELECT * FROM booking WHERE transactNo = ?");
      $stmt->bind_param("s", $transactionNumber);
      $stmt->execute();
      $result = $stmt->get_result();
    
      $pax = 0; // Default value if no result is found
      if ($row = $result->fetch_assoc()) {
        $_SESSION['pax'] = $row['pax']; // Store the total pax in the session
      }
    
      // Fetch the count of existing guests
      $stmt = $conn->prepare("SELECT COUNT(*) FROM guest WHERE transactNo = ?");
      $stmt->bind_param("s", $transactionNumber);
      $stmt->execute();
      $result = $stmt->get_result();
      $guestCount = $result->fetch_row()[0]; // Get the number of guests already added
      $stmt->close();
    
      // Calculate Available Pax
      $availablePax = $_SESSION['pax'] - $guestCount;
    ?>

    <button type="button" class="btn btn-secondary" onclick="window.location.href='agent-showGuest.php?id=<?php echo htmlspecialchars($transactionNumber); ?>'">Back</button>
    <div class="content-wrapper bg-transparent px-5 pt-2">
      <div class="d-flex flex-row gap-5">
        <h6 class="fw-bold">Transaction No: <span class="fw-normal"><?php echo $transactionNumber ?></span></h6>
        <h6 class="fw-bold">Total Pax: <span class="fw-normal"><?php echo $_SESSION['pax']; ?></span></h6>
        <h6 class="fw-bold">Available Pax: <span class="fw-normal"><?php echo $availablePax; ?></span></h6>
        <button id="addGuestFormButton" type="button" class="btn btn-primary">Add Guest Information Form</button>
      </div>

      <!-- Dynamically generate Guest Information Cards based on pax -->
      <form action="../Agent Section/functions/agent-addGuest-code.php" method="POST">
        <input type="hidden" name="transactNo" value="<?php echo $transactionNumber; ?>">
        <!-- Guest Forms Container -->
        <div id="guestFormsContainer">
          <!-- Default initial form -->
          <div class="card guest-form shadow-sm mb-3">
            <div class="card-header bg-secondary text-white d-flex flex-row justify-content-between align-items-center">
              <h5 class="font-weight-bold mt-1">Guest Information 1</h5>
              <button class="btn btn-sm btn-outline-light float-end" type="button" data-bs-toggle="collapse" data-bs-target="#cardBodyContent1" aria-expanded="false" aria-controls="cardBodyContent1">
                Toggle
              </button>
              <!-- <button class="btn btn-sm btn-danger deleteGuestFormButton ms-2" type="button">Delete</button> -->
            </div>

            <div id="cardBodyContent1" class="collapse show">
              <div class="card-body px-5">
                

                <!-- Guest Personal Information -->
                <div class="header-container d-flex flex-row w-100 mb-3">
                  <h5 class="card-title bg-primary text-white p-3 w-100 personal-info-header">Personal Information</h5>
                </div>

                <!--Guest Name Input Fields-->
                <div class="row mb-3">
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
                      <label class="mb-2" for="mName">Middle Name <span class="text-danger fw-bold">write N/A if none</span></label>
                      <input type="text" name="mName[]" class="form-control" placeholder="Enter Middle Name" required>
                      <span id="mNameError" class="text-danger"></span> <!-- Error message for Middle Name -->
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="suffix">Suffix <span class="text-danger fw-bold">*</span></label>
                      <select class="form-control" name="suffix[]" required>
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

                <!--Guest Birthdate, Age, Sex, and Nationality-->
                <div class="row mb-3">
                  <div class="col-md-3">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="birthdate">Birthdate <span class="text-danger fw-bold">*</span> </label>
                      <input type="date" name="birthdate[]" class="form-control" required>
                      <span id="birthdateError" class="text-danger"></span> <!-- Error message for Birthdate -->
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="age">Age <span class="text-danger fw-bold">*</span> <span id="infant"></span></label>
                      <input type="number" name="age[]" class="form-control" placeholder="Age" readonly>
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
                      <input type="text" class="form-control" name="nationality[]" list="nationality" placeholder="Enter Nationality" required>
                        <datalist id="nationality"></datalist>
                      <span id="nationalityError" class="text-danger"></span> <!-- Error message for Nationality -->
                    </div>
                  </div>
                </div>

                <!--Guest Passport No, and Expiration-->
                <div class="row mb-3">
                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="passportNo">Passport No. <span class="text-danger fw-bold">*</span></label>
                      <input type="text" name="passportNo[]" class="form-control" placeholder="Enter Passport No" required>
                      <span id="passportNoError" class="text-danger"></span> <!-- Error message for Passport No -->
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="passportExp">Date of Expiration: <span class="text-danger fw-bold">*</span> <span id="expPassport" class="text-danger">Expired</span></label>
                      <input type="date" name="passportExp[]" class="form-control" required>
                      <span id="passportExpError" class="text-danger"></span> <!-- Error message for Passport Exp -->
                    </div>
                  </div>
                </div>

                <!-- Guest Contact Information -->
                <div class="header-container d-flex flex-row w-100 mb-3">
                  <h5 class="card-title bg-primary text-white p-3 w-100 contact-info-header">Contact Information</h5>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group mb-4">
                      <label class="mb-2" for="contactNo">Contact No. <span class="text-danger fw-bold">*</span></label>
                      <div class="input-group">
                        <select name="countryCode[]" class="form-select" required>
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
                        <input type="tel" class="form-control" id="contactNo" name="contactNo[]" placeholder="Contact Number" required>
                      </div>
                      <span id="contactNoError" class="text-danger"></span> <!-- Error message for Contact No -->
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-4">
                      <label class="mb-2" for="2ndcontactNo">Other Contact No.</label>
                      <div class="input-group">
                        <select name="2ndcountryCode[]" class="form-select">
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
                        <input type="tel" class="form-control" name="2ndcontactNo[]" placeholder="Enter Contact No">
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group mb-4">
                      <label class="mb-2" for="email">Email <span class="text-danger fw-bold">*</span></label>
                      <input type="email" name="email[]" class="form-control" placeholder="Enter Email Address" required>
                      <span id="emailError" class="text-danger"></span> <!-- Error message for Email -->
                    </div>
                  </div>
                </div>

                <!-- Guest Address Information -->
                <div class="header-container d-flex flex-row w-100 mb-3">
                  <h5 class="card-title bg-primary text-white p-3 w-100 address-info-header">Address Information</h5>
                </div>

                <div class="row">
                  <!-- Address Line 1 -->
                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="addressLine">Address Line 1 <span class="text-danger fw-bold">*</span></label>
                      <input type="text" name="addressLine[]" class="form-control" placeholder="Enter Address Line 1" required>
                      <small class="form-text text-muted">E.g., Street, Barangay</small> <!-- Instruction for Address Line 1 -->
                      <span id="addressLineError" class="text-danger"></span>
                    </div>
                  </div>

                  <!-- Address Line 2 -->
                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="addressLine2">Address Line 2 (Optional)</label>
                      <input type="text" name="2ndaddressLine[]" class="form-control" placeholder="Enter Address Line 2">
                      <small class="form-text text-muted">E.g., Subdivision, Apartment, Unit, Floor</small> <!-- Instruction for Address Line 2 -->
                    </div>
                  </div>

                  <!-- City -->
                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="city">City <span class="text-danger fw-bold">*</span></label>
                      <input type="text" name="city[]" class="form-control" placeholder="Enter City" required>
                      <span id="cityError" class="text-danger"></span> <!-- Error message for City -->
                    </div>
                  </div>

                  <!-- State/Province/Region -->
                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="state">State/Province/Region <span class="text-danger fw-bold">*</span></label>
                      <input type="text" name="state[]" class="form-control" placeholder="Enter State/Province/Region" required>
                      <span id="stateError" class="text-danger"></span> <!-- Error message for State -->
                    </div>
                  </div>

                  <!-- Zip/Postal Code -->
                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="zipCode">Zip/Postal Code <span class="text-danger fw-bold">*</span></label>
                      <input type="text" name="zipCode[]" class="form-control" placeholder="Enter Zip/Postal Code" required>
                      <span id="zipCodeError" class="text-danger"></span> <!-- Error message for Zip/Postal Code -->
                    </div>
                  </div>

                  <!-- Country -->
                  <div class="col-md-4">
                    <div class="form-group mb-3">
                      <label class="mb-2" for="country">Country <span class="text-danger fw-bold">*</span></label>
                      <input type="text" name="country[]" class="form-control" list="countries" placeholder="Enter Country" required>
                      <datalist id="countries">
                        <option value="Afghanistan">Afghanistan</option>
                        <option value="Albania">Albania</option>
                        <option value="Algeria">Algeria</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Angola">Angola</option>
                        <option value="Antigua">Antigua</option>
                        <option value="Barbuda">Barbuda</option>
                        <option value="Argentina">Argentina</option>
                        <option value="Armenia">Armenia</option>
                        <option value="Australia">Australia</option>
                        <option value="Austria">Austria</option>
                        <option value="Azerbaijan">Azerbaijan</option>
                        <option value="Bahamas">Bahamas</option>
                        <option value="Bahrain">Bahrain</option>
                        <option value="Bangladesh">Bangladesh</option>
                        <option value="Barbados">Barbados</option>
                        <option value="Belarus">Belarus</option>
                        <option value="Belgium">Belgium</option>
                        <option value="Belize">Belize</option>
                        <option value="Benin">Benin</option>
                        <option value="Bhutan">Bhutan</option>
                        <option value="Bolivia">Bolivia</option>
                        <option value="Bosnia">Bosnia</option>
                        <option value="Herzegovina">Herzegovina</option>
                        <option value="Botswana">Botswana</option>
                        <option value="Brazil">Brazil</option>
                        <option value="Brunei">Brunei</option>
                        <option value="Bulgaria">Bulgaria</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Burundi">Burundi</option>
                        <option value="Cabo Verde">Cabo Verde</option>
                        <option value="Cambodia">Cambodia</option>
                        <option value="Cameroon">Cameroon</option>
                        <option value="Canada">Canada</option>
                        <option value="Central African Republic">Central African Republic</option>
                        <option value="Chad">Chad</option>
                        <option value="Chile">Chile</option>
                        <option value="China">China</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Comoros">Comoros</option>
                        <option value="Congo">Congo</option>
                        <option value="Costa Rica">Costa Rica</option>
                        <option value="Croatia">Croatia</option>
                        <option value="Cuba">Cuba</option>
                        <option value="Cyprus">Cyprus</option>
                        <option value="Czech Republic">Czech Republic</option>
                        <option value="Denmark">Denmark</option>
                        <option value="Djibouti">Djibouti</option>
                        <option value="Dominica">Dominica</option>
                        <option value="Dominican Republic">Dominican Republic</option>
                        <option value="Ecuador">Ecuador</option>
                        <option value="Egypt">Egypt</option>
                        <option value="El Salvador">El Salvador</option>
                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                        <option value="Eritrea">Eritrea</option>
                        <option value="Estonia">Estonia</option>
                        <option value="Eswatini">Eswatini</option>
                        <option value="Ethiopia">Ethiopia</option>
                        <option value="Fiji">Fiji</option>
                        <option value="Finland">Finland</option>
                        <option value="France">France</option>
                        <option value="Gabon">Gabon</option>
                        <option value="Gambia">Gambia</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Germany">Germany</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Greece">Greece</option>
                        <option value="Grenada">Grenada</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Guinea">Guinea</option>
                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                        <option value="Guyana">Guyana</option>
                        <option value="Haiti">Haiti</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Hungary">Hungary</option>
                        <option value="Iceland">Iceland</option>
                        <option value="India">India</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Iran">Iran</option>
                        <option value="Iraq">Iraq</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Israel">Israel</option>
                        <option value="Italy">Italy</option>
                        <option value="Jamaica">Jamaica</option>
                        <option value="Japan">Japan</option>
                        <option value="Jordan">Jordan</option>
                        <option value="Kazakhstan">Kazakhstan</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Kiribati">Kiribati</option>
                        <option value="Kuwait">Kuwait</option>
                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                        <option value="Laos">Laos</option>
                        <option value="Latvia">Latvia</option>
                        <option value="Lebanon">Lebanon</option>
                        <option value="Lesotho">Lesotho</option>
                        <option value="Liberia">Liberia</option>
                        <option value="Libya">Libya</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Lithuania">Lithuania</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Madagascar">Madagascar</option>
                        <option value="Malawi">Malawi</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Maldives">Maldives</option>
                        <option value="Mali">Mali</option>
                        <option value="Malta">Malta</option>
                        <option value="Marshall Islands">Marshall Islands</option>
                        <option value="Mauritania">Mauritania</option>
                        <option value="Mauritius">Mauritius</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Micronesia">Micronesia</option>
                        <option value="Moldova">Moldova</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Mongolia">Mongolia</option>
                        <option value="Montenegro">Montenegro</option>
                        <option value="Morocco">Morocco</option>
                        <option value="Mozambique">Mozambique</option>
                        <option value="Myanmar">Myanmar</option>
                        <option value="Namibia">Namibia</option>
                        <option value="Nauru">Nauru</option>
                        <option value="Nepal">Nepal</option>
                        <option value="Netherlands">Netherlands</option>
                        <option value="New Zealand">New Zealand</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Niger">Niger</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="North Macedonia">North Macedonia</option>
                        <option value="Norway">Norway</option>
                        <option value="Oman">Oman</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="Palau">Palau</option>
                        <option value="Panama">Panama</option>
                        <option value="Papua New Guinea">Papua New Guinea</option>
                        <option value="Paraguay">Paraguay</option>
                        <option value="Peru">Peru</option>
                        <option value="Philippines">Philippines</option>
                        <option value="Poland">Poland</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Qatar">Qatar</option>
                        <option value="Romania">Romania</option>
                        <option value="Russia">Russia</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="Saint Kitts">Saint Kitts</option>
                        <option value="Saint Nevis">Saint Nevis</option>
                        <option value="Saint Vincent">Saint Vincent</option>
                        <option value="Grenadines">Grenadines</option>
                        <option value="Sao Tome">Sao Tome</option>
                        <option value="Principe">Principe</option>
                        <option value="Saudi Arabia">Saudi Arabia</option>
                        <option value="Senegal">Senegal</option>
                        <option value="Serbia">Serbia</option>
                        <option value="Seychelles">Seychelles</option>
                        <option value="Sierra Leone">Sierra Leone</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Slovakia">Slovakia</option>
                        <option value="Slovenia">Slovenia</option>
                        <option value="Solomon Islands">Solomon Islands</option>
                        <option value="Somalia">Somalia</option>
                        <option value="South Africa">South Africa</option>
                        <option value="South Korea">South Korea</option>
                        <option value="South Sudan">South Sudan</option>
                        <option value="Spain">Spain</option>
                        <option value="Sri Lanka">Sri Lanka</option>
                        <option value="Sudan">Sudan</option>
                        <option value="Suriname">Suriname</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Syria">Syria</option>
                        <option value="Tajikistan">Tajikistan</option>
                        <option value="Tanzania">Tanzania</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Timor-Leste">Timor-Leste</option>
                        <option value="Togo">Togo</option>
                        <option value="Tonga">Tonga</option>
                        <option value="Trinidad">Trinidad</option>
                        <option value="Tobago">Tobago</option>
                        <option value="Tunisia">Tunisia</option>
                        <option value="Turkey">Turkey</option>
                        <option value="Turkmenistan">Turkmenistan</option>
                        <option value="Tuvalu">Tuvalu</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="United Arab Emirates">United Arab Emirates</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="United States">United States</option>
                        <option value="Uruguay">Uruguay</option>
                        <option value="Uzbekistan">Uzbekistan</option>
                        <option value="Vanuatu">Vanuatu</option>
                        <option value="Vatican City">Vatican City</option>
                        <option value="Venezuela">Venezuela</option>
                        <option value="Vietnam">Vietnam</option>
                        <option value="Yemen">Yemen</option>
                        <option value="Zambia">Zambia</option>
                        <option value="Zimbabwe">Zimbabwe</option>
                      </datalist>
                      <span id="countryError" class="text-danger"></span> <!-- Error message for Country -->
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end mb-5 my-3">
          <button type="submit" class="btn btn-primary" id="addGuest" name="addGuestInformation">Save Guest Information</button>
        </div>
      </form>   
    </div>

  </div>

  <?php require "../Agent Section/includes/scripts.php"; ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- <script>
    $(document).ready(function () 
    {
      // Validation logic for booking
      $('#addGuest').click(function (event) 
      {
        event.preventDefault(); // Prevent default form submission

        let isValid = true; // Initialize isValid flag

        // Primary Guest field validation
        $('.guest-form').each(function (index) 
        {
          const guestFormNumber = index; // Get guest form number
          const guestFields = 
          [
            { name: 'fName', error: 'First name is required.' },
            { name: 'lName', error: 'Last name is required.' },
            { name: 'mName', error: 'Middle name is required.' },
            { name: 'suffix', error: 'Suffix is required.', isSelect: true },
            { name: 'birthdate', error: 'Birthdate is required.' },
            { name: 'age', error: 'Age is required.' },
            { name: 'sex', error: 'Sex is required.', isSelect: true },
            { name: 'nationality', error: 'Nationality is required.' },
            { name: 'passportNo', error: 'Passport number is required.' },
            { name: 'passportExp', error: 'Passport expiration date is required.' },
            { name: 'countryCode', error: 'Country Code is required.', isSelect: true },
            { name: 'contactNo', error: 'Contact number is required.' },
            { name: 'email', error: 'Email is required.' },
            { name: 'addressLine', error: 'Address is required.' },
            { name: 'city', error: 'City is required.' },
            { name: 'state', error: 'State is required.' },
            { name: 'zipCode', error: 'Zip Code is required.' },
            { name: 'country', error: 'Country is required.' }
          ];

          // Iterate through the fields to validate
          guestFields.forEach(({ name, error, isSelect }) => 
          {
            // Update ID for the specific guest form (assuming error spans follow this pattern)
            const errorSpanId = `#${name}Error`;
            const input = isSelect
                ? $(this).find(`select[name^="${name}"]`)
                : $(this).find(`input[name^="${name}"]`);

            // Validate if the input/select is empty
            if (!input.val()) 
            {
              input.addClass('is-invalid'); // Add invalid class
              $(errorSpanId).text(error);   // Set error message dynamically
              isValid = false;              // Set valid flag to false
            }

            // Clear error when input field is focused or changed
            input.on('focus change', function () 
            {
              $(this).removeClass('is-invalid'); // Remove invalid class
              $(errorSpanId).text('');           // Clear error message
            });
          });
        });

        // Cloned Guest field validation
        $('.guest-form').each(function (index) 
        {
          const guestFormNumber = index + 1; // Get guest form number
          const guestFields = [
              { name: 'fName', error: 'First name is required.' },
              { name: 'lName', error: 'Last name is required.' },
              { name: 'mName', error: 'Middle name is required.' },
              { name: 'suffix', error: 'Suffix is required.', isSelect: true },
              { name: 'birthdate', error: 'Birthdate is required.' },
              { name: 'age', error: 'Age is required.' },
              { name: 'sex', error: 'Sex is required.', isSelect: true },
              { name: 'nationality', error: 'Nationality is required.' },
              { name: 'passportNo', error: 'Passport number is required.' },
              { name: 'passportExp', error: 'Passport expiration date is required.' },
              { name: 'countryCode', error: 'Country Code is required.', isSelect: true }, // Added countryCode validation
              { name: 'contactNo', error: 'Contact number is required.' },
              { name: 'email', error: 'Email is required.' },
              { name: 'addressLine', error: 'Address is required.' },
              { name: 'city', error: 'City is required.' },
              { name: 'state', error: 'State is required.' },
              { name: 'zipCode', error: 'Zip Code is required.' },
              { name: 'country', error: 'Country is required.' }
          ];

          guestFields.forEach(({ name, error, isSelect }) => 
          {
            // Update ID for the specific guest form
            // const errorSpanId = `#${name}Error${guestFormNumber}`;
            const input = isSelect ? $(this).find(`select[name^="${name}"]`) : $(this).find(`input[name^="${name}"]`);

            if (!input.val()) 
            {
              input.addClass('is-invalid'); // Add invalid class
              $(errorSpanId).text(error); // Set error message dynamically
              isValid = false; // Set valid flag to false
            }

            // Clear error when input field is focused or changed
            input.on('focus change', function () 
            {
              $(this).removeClass('is-invalid'); // Remove invalid class
              $(errorSpanId).text(''); // Clear error message
            });
          });
        });

        // If the form is valid, submit the form
        if (isValid) 
        {
          console.log("Form is valid. Submitting...");
          $('#guestForm').submit(); // Submit the form with ID #guestForm
        } 
        else 
        {
          console.log("Form is invalid. Fix errors before submitting.");
        }
      });
    });
  </script> -->

    <script>
      // List of nationalities
      const nationalities = [
        "Afghan", "Albanian", "Algerian", "American", "Andorran", "Angolan", "Antiguan",
        "Argentine", "Armenian", "Australian", "Austrian", "Azerbaijani", "Bahaman", "Bahraini",
        "Bangladeshi", "Barbadian", "Bashkir", "Belarusian", "Belgian", "Belizean", "Beninese",
        "Bhutanese", "Bolivian", "Bosnian", "Brazilian", "Bruneian", "Bulgarian", "Burkinabe",
        "Burundian", "Cabo Verdean", "Cambodian", "Cameroonian", "Canadian", "Central African",
        "Chadian", "Chilean", "Chinese", "Colombian", "Comoran", "Congolese", "Costa Rican",
        "Croatian", "Cuban", "Cypriot", "Czech", "Danish", "Djiboutian", "Dominican", "Dutch",
        "East Timorese", "Ecuadorean", "Egyptian", "Emirati", "Equatorial Guinean", "Eritrean",
        "Estonian", "Eswatini", "Ethiopian", "Fijian", "Filipino", "Finnish", "French", "Gabonese",
        "Gambian", "Georgian", "German", "Ghanaian", "Greek", "Grenadian", "Guatemalan",
        "Guinea-Bissauan", "Guinean", "Guyanese", "Haitian", "Honduran", "Hungarian", "Icelander",
        "Indian", "Indonesian", "Iranian", "Iraqi", "Irish", "Israeli", "Italian", "Ivorian",
        "Jamaican", "Japanese", "Jordanian", "Kazakhstani", "Kenyan", "Kuwaiti", "Kyrgyz", "Laotian",
        "Latvian", "Lebanese", "Liberian", "Libyan", "Liechtenstein citizen", "Lithuanian",
        "Luxembourger", "Malagasy", "Malawian", "Malaysian", "Maldivian", "Malian", "Maltese",
        "Marshallese", "Mauritanian", "Mauritian", "Mexican", "Micronesian", "Moldovan", "Monacan",
        "Mongolian", "Montenegrin", "Moroccan", "Mozambican", "Myanmar", "Namibian", "Nauruan",
        "Nepali", "New Zealander", "Nicaraguan", "Nigerien", "Nigerian", "North Korean",
        "North Macedonian", "Norwegian", "Omani", "Pakistani", "Palauan", "Panamanian",
        "Papua New Guinean", "Paraguayan", "Peruvian", "Polish", "Portuguese", "Qatari", "Romanian",
        "Russian", "Rwandan", "Saint Kitts", "and Nevis", "Saint Lucian", "Salvadoran", "Samoan",
        "San Marinese", "Sao Tomean", "Saudi Arabian", "Scottish", "Senegalese", "Serbian",
        "Seychellois", "Sierra Leonean", "Singaporean", "Slovak", "Slovenian", "Solomon Islander",
        "Somali", "South African", "South Korean", "Spanish", "Sri Lankan", "Sudanese", "Surinamese",
        "Swedish", "Swiss", "Syrian", "Taiwanese", "Tajik", "Tanzanian", "Thai", "Togolese",
        "Tongan", "Trinidadian", "Tobagonian", "Tunisian", "Turkish", "Turkmen", "Tuvaluan",
        "Ugandan", "Ukrainian", "Uruguayan", "Uzbek", "Venezuelan", "Vietnamese", "Welsh", "Yemeni",
        "Zambian", "Zimbabwean"
      ];

    // Populate the datalist
    const datalist = document.getElementById("nationality");
    nationalities.forEach(nationality => 
    {
      const option = document.createElement("option");
      option.value = nationality;
      datalist.appendChild(option);
    });
  </script>

  <script>
    $(document).ready(function () {
  var maxPax = <?php echo $availablePax; ?>; // Passing maxPax value from PHP to JS

  // Add a new guest form
  $('#addGuestFormButton').on('click', function () {
    const guestFormsContainer = $('#guestFormsContainer');
    const existingForms = $('.guest-form');
    const currentCount = existingForms.length;

    if (currentCount < maxPax) {
      // Clone the first form
      const newForm = existingForms.first().clone();
      const newIndex = currentCount + 1;

      // Update IDs and Labels in the cloned form
      newForm.find('h5').text(`Guest Information ${newIndex}`);
      const collapsible = newForm.find('[data-bs-target]');
      const collapsibleContent = newForm.find('.collapse');

      collapsible.attr('data-bs-target', `#cardBodyContent${newIndex}`);
      collapsibleContent.attr('id', `cardBodyContent${newIndex}`);

      // Reset form values
      newForm.find('input').val(''); // Clear input values
      newForm.find('.error-message').text(''); // Clear error messages

      // Dynamically update span IDs and reset their content
      newForm.find('span[id]').each(function () {
        const baseId = $(this).attr('id').replace(/\d+$/, ''); // Remove existing numeric suffix
        $(this).attr('id', `${baseId}${newIndex}`).text(''); // Add new index and clear content
      });

      // Ensure the delete button is present only in the cloned forms
      const cardHeader = newForm.find('.card-header');
      let deleteButton = cardHeader.find('.deleteGuestFormButton');

      if (deleteButton.length === 0) {
        deleteButton = $('<button>', {
          class: 'btn btn-sm btn-danger deleteGuestFormButton ms-2',
          type: 'button',
          text: 'Delete',
        });
        cardHeader.append(deleteButton);
      }

      // Append the new form to the container
      guestFormsContainer.append(newForm);
      renumberForms(); // Renumber the remaining forms
    } else {
      alert(`You can only add up to ${maxPax} guest forms.`);
    }
  });

  // Delete a guest form
  $(document).on('click', '.deleteGuestFormButton', function () {
    $(this).closest('.guest-form').slideUp(function () {
      $(this).remove(); // Remove the form
      renumberForms(); // Renumber the remaining forms
    });
  });

  // Function to renumber forms
  function renumberForms() {
    $('.guest-form').each(function (index) {
      const formIndex = index + 1;

      // Update the main guest form header (Guest Information 1, 2, etc.)
      $(this).find('.font-weight-bold.mt-1').text(`Guest Information ${formIndex}`);

      // Update section headers dynamically
      $(this).find('.personal-info-header').text(`Personal Information ${formIndex}`);
      $(this).find('.contact-info-header').text(`Contact Information ${formIndex}`);
      $(this).find('.address-info-header').text(`Address Information ${formIndex}`);

      // Update collapsible elements
      const collapsible = $(this).find('[data-bs-target]');
      const collapsibleContent = $(this).find('.collapse');

      // Update error IDs dynamically
      $(this).find('.error-message').each(function () {
        const baseId = $(this).attr('id').replace(/\d+$/, ''); // Strip existing index
        $(this).attr('id', `${baseId}${formIndex}`); // Append updated index
      });

      // Update span IDs dynamically
      $(this).find('span[id]').each(function () {
        const baseId = $(this).attr('id').replace(/\d+$/, ''); // Remove existing numeric suffix
        $(this).attr('id', `${baseId}${formIndex}`).text(''); // Clear and append updated index
      });

      collapsible.attr('data-bs-target', `#cardBodyContent${formIndex}`);
      collapsibleContent.attr('id', `cardBodyContent${formIndex}`);
    });
  }

  // Event listener for birthdate field
  $(document).on('change', 'input[name^="birthdate"]', function () {
    const birthdate = $(this).val();

    // Make sure the birthdate is in a valid format (YYYY-MM-DD)
    if (isValidDate(birthdate)) {
      const age = calculateAge(birthdate); // Calculate age

      // Update the age field and handle infant text
      const parentCard = $(this).closest('.card-body');
      parentCard.find('input[name^="age"]').val(age > 0 ? age : 0);

      const infantSpan = parentCard.find('span[id^="infant"]');
      if (age === 0) {
        infantSpan.text('Infant'); // Display "Infant" for age 0
      } else {
        infantSpan.text(''); // Clear if not an infant
      }

    } else {
      // Clear invalid fields
      $(this).closest('.card-body').find('input[name^="age"]').val('');
      $(this).closest('.card-body').find('span[id^="infant"]').text('');
    }
  });

  // Function to calculate age from birthdate
  function calculateAge(birthdate) {
    const birthDateObj = new Date(birthdate); // Convert birthdate string into Date object
    const today = new Date();
    let age = today.getFullYear() - birthDateObj.getFullYear();

    // Adjust if the birthday hasn't occurred yet this year
    const monthDiff = today.getMonth() - birthDateObj.getMonth();
    const dayDiff = today.getDate() - birthDateObj.getDate();
    if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
      age--;
    }

    return age < 1 ? 0 : age; // Return 0 if less than 1 year old
  }

  // Function to check if date is valid
  function isValidDate(dateString) {
    const regex = /^\d{4}-\d{2}-\d{2}$/; // Check format YYYY-MM-DD
    if (!regex.test(dateString)) return false;

    const dateObj = new Date(dateString);
    return !isNaN(dateObj.getTime()); // Check if date is valid
  }
});


  </script>

  <script>
    $(document).ready(function () 
    {
      // Add event listener to birthdate fields to auto-calculate age
      $(document).on('change', 'input[name^="birthdate"]', function () 
      {
        const birthdate = $(this).val(); // Get the birthdate value

        // Make sure the birthdate is in a valid format (YYYY-MM-DD)
        if (isValidDate(birthdate)) 
        {
          const age = calculateAge(birthdate); // Calculate the age based on birthdate

          // Update the age field in the same form that contains the birthdate field
          const ageField = $(this).closest('.card-body').find('input[name^="age"]');
          const infantSpan = $('#infant'); // Target the span for infants

          if (!isNaN(age) && age >= 0) 
          {
            // Display age (0 for infants)
            ageField.val(age);

            // If age is 0, display "Infant" in the span
            if (age === 0) 
            {
              infantSpan.text('Infant');
            } 
            else 
            {
              infantSpan.text(''); // Clear the text if not an infant
            }
          } 
          else 
          {
            ageField.val(''); // Clear the age field if invalid
            infantSpan.text(''); // Clear the span text if invalid
          }
        } 
        else 
        {
          $(this).closest('.card-body').find('input[name^="age"]').val(''); // Clear the age field if date is invalid
          $('#infant').text(''); // Clear the span text if date is invalid
        }
      });

      // Function to calculate age from birthdate
      function calculateAge(birthdate) 
      {
        const birthDateObj = new Date(birthdate); // Convert the birthdate string into a Date object
        const today = new Date(); // Get the current date

        let age = today.getFullYear() - birthDateObj.getFullYear(); // Calculate age by year difference

        // Adjust the age if the birthday hasn't occurred yet this year
        const monthDiff = today.getMonth() - birthDateObj.getMonth();
        const dayDiff = today.getDate() - birthDateObj.getDate();

        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) 
        {
          age--; // Reduce age if the birthdate hasn't occurred yet this year
        }

        // If the age is less than 1 year, consider it an infant (age 0)
        if (age < 1) 
        {
          age = 0;
        }

        return age;
      }

      // Function to check if the date is in a valid format (YYYY-MM-DD)
      function isValidDate(dateString) 
      {
        // Check if the date is in the format YYYY-MM-DD
        const regex = /^\d{4}-\d{2}-\d{2}$/;
        if (!regex.test(dateString)) 
        {
          return false;
        }

        // Check if the date is a real calendar date
        const dateObj = new Date(dateString);
        return dateObj instanceof Date && !isNaN(dateObj.getTime());
      }

    });
  </script>

</body>
</html>
