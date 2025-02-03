
<?php
  require '../conn.php';
  
  session_start();

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  
  // Fetch session variables directlys
  $email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
  $accId = $_SESSION['accountId'] ?? '';

  if ($accId) {
    // Use a prepared statement to safely query the database
    $stmt = $conn->prepare("SELECT agentCode, agentId, agentRole, agentType FROM agent WHERE accountId = ?");
    $stmt->bind_param("i", $accId);
    $stmt->execute();
    $result = $stmt->get_result();

      // Fetch a single row
      if ($row = $result->fetch_assoc()) {
          // Store mandatory fields in the session
          $_SESSION['agent_agentId'] = $row['agentId'] ?? '';
          $_SESSION['agent_agentCode'] = $row['agentCode'] ?? '';
          $_SESSION['agent_agentRole'] = $row['agentRole'] ?? '';
          $_SESSION['agent_agentType'] = $row['agentType'] ?? '';
      } else {
          echo "No agent found with the given account ID.";
      }

    } else {
        echo "Invalid account ID.";
    }


  // Close the statement
  $stmt->close();
  
  // include '../Client Section/Functions/session_validate.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../Client Section/Includes/head.php'; ?>

  <title>Booking Form</title>

  <link rel="stylesheet" href="../Client Section/assets/css/client-bookingform.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-navbar.css?v=<?php echo time(); ?>"> 
 
</head>

<body>

<?php include '../Client Section/Includes/client-navbar.php'; ?>

<div class="body-container">
  <div class="main-container">  
    <div class="content-header">
        <div class="back-button-wrapper">
            <a href="index.php" class="back-button-link"> <i class="fa-solid fa-arrow-left me-2"></i> Back to Homepage</a>
        </div>
        <h1>Booking</h1>
        <p>Begin your unforgettable journey by making your booking with us</p>
    </div>

    <div class="container-body">

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

      <form action="../Client Section/Functions/bookingform-code.php" method="POST">
        <div class="bookingform">


        <?php
          if (isset($_GET['flightid'])) 
          {
            $flightid = $_GET['flightid'];
            $_SESSION['flightid'] = $flightid;
            $sql1 = "SELECT * FROM flight WHERE flightId = ?";

            // Prepare the statement
            if ($stmt = $conn->prepare($sql1)) 
            {
              // Bind the flightId as an integer parameter
              $stmt->bind_param("i", $flightid); // "i" means integer

              // Execute the statement
              if ($stmt->execute()) 
              {
                // Get the result
                $result = $stmt->get_result();

                // Check if a row is returned
                if ($result->num_rows > 0) 
                {
                  // Fetch the data
                  while ($row = $result->fetch_assoc()) 
                  {
                    $packageId = $row['packageId'];
                    $origin = $row['origin'];
                    $year = date('Y', strtotime($row['flightDepartureDate']));
                    $month = date('F', strtotime($row['flightDepartureDate']));
                    $flightDepartureDate = $row['flightDepartureDate'];
                    $flightPrice = $row['flightPrice'];
                    // Display other columns as needed
                  }
                } 
                else 
                {
                  echo "No flight found with that ID.";
                }
              } 
              else 
              {
                echo "Error executing query: " . $stmt->error;
              }

              // Close the statement
              $stmt->close();
            } 
            else 
            {
              echo "Error preparing statement: " . $conn->error;
            }
          } 
          else 
          {
            echo "Flight ID is not set.";
          }
        ?>

          <h4>Flight ID: <?php echo htmlspecialchars($flightid); ?></h4>    


          <div class="card">
            <div class="card-header bg-secondary text-white text-light">
              <h4 class="my-2 px-2">Details</h4>
            </div>

            <div class="card-body">
              <!-- <div class="row">
                <div class="columns col-md-6">
                  <label for="agentId">Select Agent: <span class="text-danger fw-bold">*</span></label>
                  <select class="form-select" id="agentId" name="agentId" required>
                    <option selected disabled>SELECT AGENT</option>
                    <?php
                      $sql1 = mysqli_query($conn, "SELECT agentId, CONCAT(lName, ', ', fName, CASE WHEN mName IS NULL OR mName = 'N/A' 
                      THEN '' ELSE CONCAT(' ', LEFT(mName, 1), '.') END) AS agentName FROM agent 
                      ORDER BY agentName ASC");
                                      
                      while ($res1 = mysqli_fetch_assoc($sql1)) 
                      { 
                        echo "<option value='" . $res1['agentId'] . "'>" . $res1['agentName'] . "</option>";
                      }
                    ?>
                  </select>

                  <input type="hidden" id="agentCode" name="agentCode" placeholder="Agent Code">

                  <span id="agentError" class="text-danger"></span>
                </div>
              </div> -->

              <div class="row">
                <!-- Package Dropdown -->
                <div class="columns col-md-6">
                  <div class="form-group">
                    <label for="packageName"> Package <span class="text-danger fw-bold">*</span> </label>
                    <select class="form-select" id="packageName" name="packageName" required>
                      <option selected disabled>SELECT PACKAGE</option>
                      <?php
                        // Query to fetch packageId and packageName
                        $sql1 = mysqli_query($conn, "SELECT DISTINCT packageId, packageName FROM package ORDER BY packageName ASC");

                        // Loop through the result to create options
                        while ($res1 = mysqli_fetch_array($sql1)) 
                        {
                          // Check if this packageId is equal to the selected packageId (to mark it as selected)
                          $selected = ($res1['packageId'] == $packageId) ? 'selected' : '';
                          echo "<option value='{$res1['packageId']}' {$selected}>{$res1['packageName']}</option>";
                        }
                      ?>
                    </select>
                    <span id="packageNameError" class="text-danger"></span>
                  </div>
                </div>

                <!-- Origin Dropdown -->
                <div class="columns col-md-6">
                  <div class="form-group">
                    <label for="origin">Origin <span class="text-danger fw-bold">*</span></label>
                    <select class="form-select" id="origin" name="origin" required>
                      <option selected disabled>SELECT ORIGIN</option>
                      <?php
                        // Query to fetch packageId and packageName
                        $sql1 = mysqli_query($conn, "SELECT DISTINCT origin FROM flight
                                        ORDER BY origin ASC");

                        // Loop through the result to create options
                        while ($res1 = mysqli_fetch_array($sql1)) 
                        {
                          // Check if this packageId is equal to the selected packageId (to mark it as selected)
                          $selected = ($res1['origin'] == $origin) ? 'selected' : '';
                          echo "<option value='{$res1['origin']}' {$selected}>{$res1['origin']}</option>";
                        }
                      ?>
                    </select>
                    <span id="originError" class="text-danger"></span>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="columns col-md-6">
                  <div class="form-group">
                    <!-- Year Dropdown -->
                    <label for="year">Year <span class="text-danger fw-bold">*</span></label>
                    <select class="form-select" id="year" name="year" required>
                      <option selected disabled>SELECT YEAR</option>
                      <?php
                        // Query to fetch packageId and packageName
                        $sql1 = mysqli_query($conn, "SELECT DISTINCT YEAR(flightDepartureDate) as year FROM flight 
                                        ORDER BY flightDepartureDate ASC");

                        // Loop through the result to create options
                        while ($res1 = mysqli_fetch_array($sql1)) 
                        {
                          // Check if this packageId is equal to the selected packageId (to mark it as selected)
                          $selected = ($res1['year'] == $year) ? 'selected' : '';
                          echo "<option value='{$res1['year']}' {$selected}>{$res1['year']}</option>";
                        }
                      ?>
                    </select>
                    <span id="yearError" class="text-danger"></span> <!-- Error message for year -->
                  </div>
                </div>

                
                <div class="columns col-md-6">
                  <div class="form-group">
                    <!-- Month Dropdown -->
                    <label for="month">Month <span class="text-danger fw-bold">*</span></label>
                    <select class="form-select" id="month" name="month" required>
                      <option selected disabled>SELECT MONTH</option>
                      <?php
                        // Query to fetch packageId and packageName
                        $sql1 = mysqli_query($conn, "SELECT DISTINCT MONTHNAME(flightDepartureDate) as month FROM flight
                                        ORDER BY flightDepartureDate ASC");

                        // Loop through the result to create options
                        while ($res1 = mysqli_fetch_array($sql1)) 
                        {
                          // Check if this packageId is equal to the selected packageId (to mark it as selected)
                          $selected = ($res1['month'] == $month) ? 'selected' : '';
                          echo "<option value='{$res1['month']}' {$selected}>{$res1['month']}</option>";
                        }
                      ?>
                    </select>
                    <span id="monthError" class="text-danger"></span> <!-- Error message for month -->
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="columns col-md-6">
                  <div class="form-group">
                    <label for="flightDate">Flight Date <span class="text-danger fw-bold">*</span> </label>
                    <select class="form-select" id="flightDate" name="flightDate" required>
                      <option selected disabled>SELECT FLIGHT DATE</option>
                      <?php
                        // Query to fetch packageId and packageName
                        $sql1 = mysqli_query($conn, "SELECT flightId, flightDepartureDate, flightPrice FROM flight WHERE packageId = $packageId AND 
                                        MONTHNAME(flightDepartureDate) = '$month' ORDER BY flightDepartureDate ASC");

                        // Loop through the result to create options
                        while ($res1 = mysqli_fetch_array($sql1)) 
                        {
                          // Check if this packageId is equal to the selected packageId (to mark it as selected)
                          $selected = ($res1['flightDepartureDate'] == $flightDepartureDate) ? 'selected' : '';
                          echo "<option value='{$res1['flightId']}' {$selected}>
                                  " . date('M j, Y', strtotime($res1['flightDepartureDate'])) . " || Price: {$res1['flightPrice']}
                                </option>";
                        }
                      ?>
                      </select>
                    <span id="flightDateError" class="text-danger"></span> <!-- Error message for outbound flight -->
                  </div>
                </div>

                <!-- Total Pax Input -->
                <div class="columns col-md-6">
                  <div class="form-group">
                    <label for="totalPax">Total Pax <span class="text-danger fw-bold">*</span></label>
                    <label id="maxSeats"></label> 
                    <label id="availSeats"></label>
                    <input type="number" class="form-control" id="totalPax" name="totalPax" min="1" placeholder="Enter Total Pax" required>
                    <span id="totalPaxError" class="text-danger"></span> <!-- Error message for Total Pax -->
                  </div>
                </div>
              </div>

              <div class="land-only row">
                <div class="columns col-md-12">
                  <input type="checkbox" id="land" name="land" value="Land Only">
                  <label for="land"> Check if Land Only</label><br>
                </div>
              </div>

              <div class="land-only row">
                <!-- Flight Details Input -->
                <div class="columns col-md-12" id="flightDetailsContainer" style="display: none;">
                  <div class="form-group">
                    <label for="flightDetails">Flight Details for Package Only</label>
                    <textarea class="form-control" id="flightDetails" name="flightDetails" placeholder="Input Flight Details Here"></textarea>
                  </div>
                </div>
                
                <input type="hidden" id="flightId" name="flightId" value="<?php echo $flightid; ?>" placeholder="Flight Id Input">
                <input type="hidden" id="packagePrice" name="packagePrice" placeholder="Package Price">
                <input type="hidden" name="flightPrice" id="flightPricee" value="<?php echo $flightPrice; ?>" placeholder="Flight Price">
                <!-- <input type="" name="agentId" id="agentId" value="<?php echo $_SESSION['agent_agentId']; ?>" placeholder="Agent Id"> -->
              </div>
            </div>

            <div class="card-footer"> 
              <h5> <label style="display: none;">Price: ₱ <span id="flightPrice">0.00</span></label> </h5>
            </div>
          </div>

          <div class="card ContactPerson">
            <div class="card-header bg-secondary text-white text-light">
              <h4 class="my-2 px-2">Contact Person Details</h4>
            </div>

            <div class="card-body">
              <div class="row">
                <!-- First Name Input -->
                <div class="columns col-md-3">
                  <div class="form-group mb-3">
                    <label for="fName">First Name <span class="text-danger fw-bold">*</span></label>
                    <input type="text" name="fName" id="fName" class="form-control" placeholder="Enter First Name" required>
                    <span id="fNameError" class="text-danger"></span> <!-- Error message for First Name -->
                  </div>
                </div>

                <!-- Last Name Input -->
                <div class="columns col-md-3">
                  <div class="form-group">
                    <label for="lName">Last Name <span class="text-danger fw-bold">*</span> </label>
                    <input type="text" name="lName" id="lName" class="form-control" placeholder="Enter Last Name" required>
                    <span id="lNameError" class="text-danger"></span> <!-- Error message for Last Name -->
                  </div>
                </div>

                <!-- Middle Name Input -->
                <div class="columns col-md-3">
                  <div class="form-group">
                    <label class="mName" for="mName">Middle Name <span class="text-danger fw-bold">N/A if None</span></label>
                    <input type="text" name="mName" id="mName" class="form-control" placeholder="Enter Middle Name" required>
                    <span id="mNameError" class="text-danger"></span> <!-- Error message for Middle Name -->
                  </div>
                </div>

                <!-- Suffix Dropdown -->
                <div class="columns col-md-3">
                  <div class="form-group">
                    <label for="suffix">Suffix <span class="text-danger fw-bold">*</span></label>
                    <select class="form-select" name="suffix" id="suffix" required>
                      <option selected disabled>SELECT SUFFIX</option>
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

              <div class="row">
                <!-- Contact No Input-->
                <div class="columns col-md-3">
                  <div class="form-group">
                    <label for="contactNo">Contact No. <span class="text-danger fw-bold">*</span></label>
                    <script>
                      function updateCountryCodeValue() {
                        var countryCode = document.getElementById("countryCode").value;
                        document.getElementById("selectedCountryCode").textContent = countryCode || "None";
                      }
                    </script>
                    <div class="input-group">
                      <select name="countryCode" id="countryCode" class="form-select country-select" required onchange="updateCountryCodeValue()">
                          <option value="+263">(+263) Zimbabwe</option>
                          <option value="+260">(+260) Zambia</option>
                          <option value="+967">(+967) Yemen</option>
                          <option value="+681">(+681) Wallis and Futuna</option>
                          <option value="+84">(+84) Vietnam</option>
                          <option value="+58">(+58) Venezuela</option>
                          <option value="+39">(+39) Vatican City</option>
                          <option value="+688">(+688) Vanuatu</option>
                          <option value="+1-649">(+1-649) Turks and Caicos Islands</option>
                          <option value="+993">(+993) Turkmenistan</option>
                          <option value="+90">(+90) Turkey</option>
                          <option value="+216">(+216) Tunisia</option>
                          <option value="+1-868">(+1-868) Trinidad and Tobago</option>
                          <option value="+676">(+676) Tonga</option>
                          <option value="+228">(+228) Togo</option>
                          <option value="+670">(+670) Timor-Leste</option>
                          <option value="+66">(+66) Thailand</option>
                          <option value="+255">(+255) Tanzania</option>
                          <option value="+992">(+992) Tajikistan</option>
                          <option value="+886">(+886) Taiwan</option>
                          <option value="+963">(+963) Syria</option>
                          <option value="+41">(+41) Switzerland</option>
                          <option value="+46">(+46) Sweden</option>
                          <option value="+268">(+268) Swaziland</option>
                          <option value="+597">(+597) Suriname</option>
                          <option value="+249">(+249) Sudan</option>
                          <option value="+94">(+94) Sri Lanka</option>
                          <option value="+34">(+34) Spain</option>
                          <option value="+211">(+211) South Sudan</option>
                          <option value="+82">(+82) South Korea</option>
                          <option value="+27">(+27) South Africa</option>
                          <option value="+252">(+252) Somalia</option>
                          <option value="+677">(+677) Solomon Islands</option>
                          <option value="+386">(+386) Slovenia</option>
                          <option value="+421">(+421) Slovakia</option>
                          <option value="+65">(+65) Singapore</option>
                          <option value="+232">(+232) Sierra Leone</option>
                          <option value="+248">(+248) Seychelles</option>
                          <option value="+381">(+381) Serbia</option>
                          <option value="+221">(+221) Senegal</option>
                          <option value="+966">(+966) Saudi Arabia</option>
                          <option value="+239">(+239) São Tomé and Príncipe</option>
                          <option value="+1-345">(+1-345) Cayman Islands</option>
                          <option value="+590">(+590) Saint Martin</option>
                          <option value="+1-758">(+1-758) Saint Lucia</option>
                          <option value="+1-869">(+1-869) Saint Kitts and Nevis</option>
                          <option value="+508">(+508) Saint Barthélemy</option>
                          <option value="+250">(+250) Rwanda</option>
                          <option value="+7">(+7) Russia</option>
                          <option value="+40">(+40) Romania</option>
                          <option value="+974">(+974) Qatar</option>
                          <option value="+351">(+351) Portugal</option>
                          <option value="+48">(+48) Poland</option>
                          <option value="+63" selected>(+63) Philippines</option> <!-- Default selected -->
                          <option value="+51">(+51) Peru</option>
                          <option value="+595">(+595) Paraguay</option>
                          <option value="+675">(+675) Papua New Guinea</option>
                          <option value="+507">(+507) Panama</option>
                          <option value="+680">(+680) Palau</option>
                          <option value="+92">(+92) Pakistan</option>
                          <option value="+968">(+968) Oman</option>
                          <option value="+47">(+47) Norway</option>
                          <option value="+1-670">(+1-670) Northern Mariana Islands</option>
                          <option value="+850">(+850) North Korea</option>
                          <option value="+672">(+672) Norfolk Island</option>
                          <option value="+683">(+683) Niue</option>
                          <option value="+234">(+234) Nigeria</option>
                          <option value="+227">(+227) Niger</option>
                          <option value="+505">(+505) Nicaragua</option>
                          <option value="+64">(+64) New Zealand</option>
                          <option value="+599">(+599) Netherlands Antilles</option>
                          <option value="+31">(+31) Netherlands</option>
                          <option value="+977">(+977) Nepal</option>
                          <option value="+674">(+674) Nauru</option>
                          <option value="+264">(+264) Namibia</option>
                          <option value="+95">(+95) Myanmar</option>
                          <option value="+258">(+258) Mozambique</option>
                          <option value="+222">(+222) Morocco</option>
                          <option value="+596">(+596) Martinique</option>
                          <option value="+692">(+692) Marshall Islands</option>
                          <option value="+356">(+356) Malta</option>
                          <option value="+223">(+223) Mali</option>
                          <option value="+960">(+960) Maldives</option>
                          <option value="+60">(+60) Malaysia</option>
                          <option value="+265">(+265) Malawi</option>
                          <option value="+261">(+261) Madagascar</option>
                          <option value="+352">(+352) Luxembourg</option>
                          <option value="+370">(+370) Lithuania</option>
                          <option value="+423">(+423) Liechtenstein</option>
                          <option value="+218">(+218) Libya</option>
                          <option value="+231">(+231) Liberia</option>
                          <option value="+266">(+266) Lesotho</option>
                          <option value="+961">(+961) Lebanon</option>
                          <option value="+371">(+371) Latvia</option>
                          <option value="+856">(+856) Laos</option>
                          <option value="+996">(+996) Kyrgyzstan</option>
                          <option value="+965">(+965) Kuwait</option>
                          <option value="+686">(+686) Kiribati</option>
                          <option value="+254">(+254) Kenya</option>
                          <option value="+7">(+7) Kazakhstan</option>
                          <option value="+962">(+962) Jordan</option>
                          <option value="+81">(+81) Japan</option>
                          <option value="+225">(+225) Ivory Coast</option>
                          <option value="+39">(+39) Italy</option>
                          <option value="+972">(+972) Israel</option>
                          <option value="+353">(+353) Ireland</option>
                          <option value="+964">(+964) Iraq</option>
                          <option value="+98">(+98) Iran</option>
                          <option value="+62">(+62) Indonesia</option>
                          <option value="+91">(+91) India</option>
                          <option value="+354">(+354) Iceland</option>
                          <option value="+36">(+36) Hungary</option>
                          <option value="+504">(+504) Honduras</option>
                          <option value="+509">(+509) Haiti</option>
                          <option value="+592">(+592) Guyana</option>
                          <option value="+245">(+245) Guinea-Bissau</option>
                          <option value="+224">(+224) Guinea</option>
                          <option value="+502">(+502) Guatemala</option>
                          <option value="+1-473">(+1-473) Grenada</option>
                          <option value="+30">(+30) Greece</option>
                          <option value="+233">(+233) Ghana</option>
                          <option value="+49">(+49) Germany</option>
                          <option value="+995">(+995) Georgia</option>
                          <option value="+220">(+220) Gambia</option>
                          <option value="+241">(+241) Gabon</option>
                          <option value="+33">(+33) France</option>
                          <option value="+358">(+358) Finland</option>
                          <option value="+679">(+679) Fiji</option>
                          <option value="+251">(+251) Ethiopia</option>
                          <option value="+268">(+268) Eswatini</option>
                          <option value="+372">(+372) Estonia</option>
                          <option value="+291">(+291) Eritrea</option>
                          <option value="+240">(+240) Equatorial Guinea</option>
                          <option value="+503">(+503) El Salvador</option>
                          <option value="+20">(+20) Egypt</option>
                          <option value="+593">(+593) Ecuador</option>
                          <option value="+1-809">(+1-809) Dominican Republic</option>
                          <option value="+1-767">(+1-767) Dominica</option>
                          <option value="+253">(+253) Djibouti</option>
                          <option value="+45">(+45) Denmark</option>
                          <option value="+420">(+420) Czech Republic</option>
                          <option value="+357">(+357) Cyprus</option>
                          <option value="+53">(+53) Cuba</option>
                          <option value="+385">(+385) Croatia</option>
                          <option value="+506">(+506) Costa Rica</option>
                          <option value="+242">(+242) Congo, Republic of the</option>
                          <option value="+243">(+243) Congo, Democratic Republic of the</option>
                          <option value="+269">(+269) Comoros</option>
                          <option value="+1-809">(+1-809) Colombia</option>
                          <option value="+229">(+229) Benin</option>
                          <option value="+52">(+52) Mexico</option>
                      </select>
                      <input type="tel" class="form-control contactNo" id="contactNo" name="contactNo" placeholder="Contact Number" required>
                    </div>
                    <span id="contactNoError" class="text-danger"></span> <!-- Error message for Contact No -->
                  </div>
                </div>

                <!-- Email Input -->
                <div class="columns col-md-3">
                  <div class="form-group">
                    <label for="email">Email <span class="text-danger fw-bold">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Address" required>
                    <span id="emailError" class="text-danger"></span> <!-- Error message for Email -->
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="total-price-container">
            <div class="card border">
              <div class="card-header">
                <h5>Total Price: ₱ <span id="displayTotalPrice">0</span></h5>
                <strong id="errorMessage" class="text-danger"></strong>
                <button type="button" id="bookNowButton" class="btn">Book Now</button>
              </div>
              <input type="hidden" id="totalPrice" name="totalPrice">
            </div>
          </div>
        </div>

        <!-- Booking Summary Modal -->
        <div class="modal fade" id="BookingSummaryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered"> <!-- Added modal-lg for a wider modal -->
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Booking Summary</h5>
                <button type="button" class="btn-close close-outside" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
                  
              <div class="modal-body">
                <!-- Transaction and Contact Info -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="modal-columns-content">
                      <p>Guest Name:</p>
                      <p id="contactPersonName" class="bold"></p>
                    </div>
                
                    <div class="modal-columns-content">
                      <p>Email:</p>
                      <p id="contactPersonEmail" class="bold"></p>
                    </div>
                  </div>
                </div>

                <hr>

                <!-- Package Details -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="modal-columns-content">
                      <p>Package Name:</p>
                      <p id="selectedPackage" class="bold"></p>
                    </div>
                  
                    <div class="modal-columns-content">
                      <p>No. of Guests:</p>
                      <p id="guestCount" class="bold"></p>
                    </div>
                  </div>
                  
                </div>

                <hr>

                <!-- Flight/Origin Details -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="modal-columns-content">
                      <p>Origin:</p>
                      <p id="selectedOrigin" class="bold"></p>
                    </div>
                
                    <div class="modal-columns-content">
                      <p>Flight Date:</p>
                      <p id="selectedDate" class="bold"></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="bookNow">Proceed to Payment</button>
              </div>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

 

<?php include '../Client Section/Includes/scripts.php'; ?>
<!-- <script src="heartbeat.js"></script>  -->

<!-- Row Click Selection JS -->
<script>
  document.addEventListener("DOMContentLoaded", function() 
  {
    document.querySelectorAll("tr[data-url]").forEach(function(row) 
    {
      row.addEventListener("click", function() 
      {
        const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

        console.log("Transaction Number: ", transactionNumber);

        // Use AJAX to send the transaction number to the server
        $.ajax(
        {
          url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
          type: 'POST',
          data: { transaction_number: transactionNumber },
          success: function(response)
          {
            console.log("Response: ", response); // Debugging line

            // Redirect to the next page after successfully setting the session
            window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
          },
          error: function(xhr, status, error) 
          {
            console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
          }
        });
      });
    });
  });
</script>

<!-- <script>
  window.onload = function() {
  // Triggering the modal to show
  var myModal = new bootstrap.Modal(document.getElementById('BookingSummaryModal'));
  myModal.show();
};
</script> -->

<script>
$(document).ready(function () {
  // Fetching Agent Code once an agent is selected
  // $('#agentId').on('change', function () 
  // {
  //   var agentId = $(this).val(); // Get the selected agentId

  //   // Reset dependent fields
  //   // $('#packageName').html('<option selected disabled>Select Package</option>');
  //   $('#origin').html('<option selected disabled>Select Origin</option>');
  //   $('#year').html('<option selected disabled>Select Year</option>');
  //   $('#month').html('<option selected disabled>Select Month</option>');
  //   $('#flightDate').html('<option selected disabled>Select Flight Date</option>');
  //   $('#flightId').val('');
  //   $('#flightPrice').text('0.00');
  //   $('#maxSeats').text('');
  //   $('#availSeats').text('');
  //   $('#displayTotalPrice').text('0.00');
  //   $('#totalPrice').val('0.00');
  //   $('#totalPax').val('');
  //   $('#totalPax').attr('placeholder', 'Enter Total Pax');

  //   if (agentId) 
  //   {
  //     // Make an AJAX request to fetch agent code
  //     $.ajax(
  //     {
  //       url: '../Agent Section/functions/fetchAgentCode.php',
  //       type: 'POST',
  //       data: { agentId: agentId },
  //       success: function (response) 
  //       {
  //         try 
  //         {
  //           // Parse the JSON response
  //           var data = JSON.parse(response);

  //           // Update the agentCode input field
  //           $('#agentCode').val(data.agentCode || ''); // Set agent code or clear if empty
  //         } catch (e) 
  //         {
  //           console.error('Error parsing JSON response:', e);
  //         }
  //       },
  //       error: function (xhr, status, error) {
  //           console.error('Error fetching agent code:', error);
  //       }
  //     });
  //   } 
  //   else 
  //   {
  //     $('#agentCode').val(''); // Clear the agentCode input field if no agent is selected
  //   }
  // });

  // Fetching Origin once Package was Selected
  $('#packageName').on('change', function () 
  {
    var packageId = $(this).val();
    var selectedPackageName = $("#packageName option:selected").text();
    $('#origin').html('<option selected disabled>Select Origin</option>'); // Clear origin field
    $('#year').html('<option selected disabled>Select Year</option>'); // Clear year field
    $('#month').html('<option selected disabled>Select Month</option>'); // Clear month field
    $('#flightDate').html('<option selected disabled>Select Flight Date</option>'); // Clear Flight Date field
    $('#flightId').val(''); // Clear Flight Id field
    $('#flightPrice').text('0.00'); // Clear Flight Price field
    $('#maxSeats').text(''); // Clear Max Seat field
    $('#availSeats').text(''); // Clear Avail Seats field
    $('#displayTotalPrice').text("0.00"); // Display total price
    $('#totalPrice').val("0.00"); // Set hidden input value
    $('#totalPax').val("Enter Total Pax"); // Set Total Pax value

    // Update the modal with the selected package name
    $('#selectedPackage').text(selectedPackageName);

    if (packageId) 
    {
      $.ajax(
      {
        url: '../Agent Section/functions/fetchOrigin.php',
        type: 'POST',
        data: { packageId: packageId },
        success: function (response) 
        {
          // Parse the JSON response
          var data = JSON.parse(response);

          // Update the origin dropdown
          $('#origin').html(data.originOptions); // Use originOptions from the response

          // Check if the origin is already selected, if not, retain the previous value
          // if (!$('#origin').val()) 
          // {
          //   $('#origin').val("<?php echo isset($origin) ? $origin : ''; ?>");
          // }

          // if (!$('#year').val()) 
          // {
          //   $('#year').val("<?php echo isset($year) ? $year : ''; ?>");
          // }

          // if (!$('#month').val()) 
          // {
          //   $('#month').val("<?php echo isset($month) ? $month : ''; ?>");
          // }

          // Update the package price input
          $('#packagePrice').val(data.packagePrice); // Set the package price value

          // console.log(data); // Optional: For debugging
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

  // Fetching Distinct Year once origin was Selected
  $('#origin').on('change', function () 
  {
    var packageId = $('#packageName').val();
    var origin = $('#origin').val();
    var selectedOrigin = $("#origin option:selected").text();

    // Update the modal with the selected origin
    $('#selectedOrigin').text(selectedOrigin);

    $('#year').html('<option selected disabled>Select Year</option>'); // Clear year field
    $('#month').html('<option selected disabled>Select Month</option>'); // Clear month field
    $('#flightDate').html('<option selected disabled>Select Flight Date</option>'); // Clear Flight Date field
    $('#flightId').val(''); // Clear Flight Id field
    $('#flightPrice').text('0.00'); // Clear Flight Price field
    $('#maxSeats').text(''); // Clear Max Seat field
    $('#availSeats').text(''); // Clear Avail Seats field
    $('#displayTotalPrice').text("0.00"); // Display total price
    $('#totalPrice').val("0.00"); // Set hidden input value
    $('#totalPax').val("Enter Total Pax"); // Set Total Pax value

    if (packageId && origin) 
    {
      $.ajax(
      {
        url: '../Agent Section/functions/fetchYear.php',
        type: 'POST',
        data: { packageId: packageId, origin: origin}, // Send packageId, origin
        success: function (response) 
        {
          // console.log(response); // Debugging the response
          $('#year').html(response); // Update year dropdown with the fetched years
          // Check if the origin is already selected, if not, retain the previous value
        },
        error: function (xhr, status, error) 
        {
          console.error('Error fetching year:', error); // Log the error to console
        }
      });
    } 
    else 
    {
      $('#year').html('<option selected disabled>Select Year</option>');
    }
  });

  // Fetching Distinct Month once year depending on the package and origin was Selected
  $('#year').on('change', function () 
  {
    var packageId = $('#packageName').val();
    var origin = $('#origin').val();
    var selectedYear = $('#year').val();  // Get the selected year

    // Clear month and flight fields
    $('#month').html('<option selected disabled>Select Month</option>');
    $('#flightDate').html('<option selected disabled>Select Flight Date</option>');
    $('#flightId').val('');  // Clear Flight Id field
    $('#flightPrice').val('0.00'); // Clear Flight Price field
    $('#maxSeats').text(''); // Clear Max Seat field
    $('#availSeats').text(''); // Clear Avail Seats field
    $('#displayTotalPrice').text("0.00"); // Display total price
    $('#totalPrice').val("0.00"); // Set hidden input value
    $('#totalPax').val("Enter Total Pax"); // Set Total Pax value

    if (packageId && origin && selectedYear) 
    {
      $.ajax(
      {
        url: '../Agent Section/functions/fetchMonth.php',  // PHP file to fetch distinct months
        type: 'POST',
        data: {
          packageId: packageId,
          origin: origin,
          year: selectedYear  // Send the selected year to fetch relevant months
        },
        success: function (response) 
        {
          // Update month dropdown with the fetched distinct months
          $('#month').html(response);
        },
        error: function (xhr, status, error) 
        {
          console.error('Error fetching months:', error);  // Log the error to the console
        }
      });
    } 
    else 
    {
      $('#month').html('<option selected disabled>Select Month</option>');
    }
  });

  // Fetching Flight Date based on the package, origin, year, and month
  $('#month').on('change', function () 
  {
    var packageId = $('#packageName').val();
    var origin = $('#origin').val();
    var selectedYear = $('#year').val();  // Get the selected year
    var selectedMonth = $('#month').val();  // Get the selected month

    // Clear flight fields
    $('#flightDate').html('<option selected disabled>Select Flight Date</option>');
    $('#flightId').val('');  // Clear Flight Id field
    $('#flightPrice').text('0.00'); // Clear Flight Price field
    $('#flightPrice').val('0.00'); // Clear Flight Price field
    $('#maxSeats').text(''); // Clear Max Seat field
    $('#availSeats').text(''); // Clear Avail Seats field
    $('#displayTotalPrice').text("0.00"); // Display total price
    $('#totalPrice').val("0.00"); // Set hidden input value
    $('#totalPax').val("Enter Total Pax"); // Set Total Pax value

    if (packageId && origin && selectedYear && selectedMonth) 
    {
      $.ajax(
      {
        url: '../Agent Section/functions/fetchFlightDate.php',  // PHP file to fetch flight dates
        type: 'POST',
        data: {
          packageId: packageId,
          origin: origin,
          year: selectedYear,
          month: selectedMonth  // Send the selected month to fetch relevant flight dates
        },
        success: function (response) 
        {
          // Update flight date dropdown with the fetched flight dates
          $('#flightDate').html(response);
        },
        error: function (xhr, status, error) 
        {
          console.error('Error fetching flight dates:', error);  // Log the error to the console
        }
      });
    } 
    else 
    {
      $('#flightDate').html('<option selected disabled>Select Flight Date</option>');
    }
  });

  // Fetching Flight Id once Flight Date was Selected
  $('#flightDate').on('change', function () 
  {
    var flightDate = $(this).val();
    var selectedFlight = $("#flightDate option:selected").text();
    // Extract only the flight date by splitting at the " || " (delimiter between date and price)
    var selectedDate = selectedFlight.split(' || ')[0].trim();

    // Update the <p> element with the extracted flight date
    $('#selectedDate').text(selectedDate);

    if (flightDate === "Null") 
    {
      // If outbound flight is "Null", use the package price instead of the flight price
      var packagePrice = parseFloat($('#packagePrice').val()); // Get the package price value
      flightPrice = packagePrice; // Ensure it's a number
      var formattedPrice = packagePrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      const totalPax = parseInt($('#totalPax').val()) || 0;
      const totalPrice = flightPrice * totalPax;

      // Update the flight price display with the formatted package price
      $('#flightPrice').text(formattedPrice);

      // Update the flight price for all guests with the package price
      $('input[name="flightPrice"]').val(packagePrice);

      $('input[name="flightId"]').val("Null");

      // Manually trigger the change event on #flightId
      $('#flightId').trigger('change');

      // Format total price with commas
      $('#displayTotalPrice').text(formatNumberWithCommas(totalPrice.toFixed(2))); // Display total price
      $('#totalPrice').val(totalPrice.toFixed(2)); // Set hidden input value

      console.log('Outbound flight is null, using package price:', packagePrice);
    } 
    else if (flightDate) 
    {
      // If a valid outbound flight is selected, fetch return flight and flight price
      $.ajax(
      {
        url: '../Agent Section/functions/fetchFlightId.php', // Separate PHP file for return flight
        type: 'POST',
        data: { flightDate: flightDate },
        success: function (response) 
        {
          var data = JSON.parse(response); // Parse the JSON response

          flightPrice = parseFloat(data.flightPrice); // Ensure it's a number

          // Format the price with commas and two decimal places
          var formattedPrice = flightPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

          // Update the flight price display with the formatted price
          $('#flightPrice').text(formattedPrice);
          $('#flightPricee').val(formattedPrice);

          // Update the flight ID 
          $('input[name="flightId"]').val(data.flightId);

          // Manually trigger the change event on #flightId
          $('#flightId').trigger('change');

          const totalPax = parseInt($('#totalPax').val()) || 0;
          const totalPrice = flightPrice * totalPax;

          // Format total price with commas
          $('#displayTotalPrice').text(formatNumberWithCommas(totalPrice.toFixed(2))); // Display total price
          $('#totalPrice').val(totalPrice.toFixed(2)); // Set hidden input value

        },
        error: function (xhr, status, error) 
        {
          console.error('Error fetching return flight:', error); // Log the error to console
        }
      });
    } 
    else 
    {
      // If no Flight Date is selected, clear return flight input fields
      console.error('Error fetching Flight Date:', error); // Log the error to console
    }
  });

  // Event listeners
  // $('#flightId').on('change', updateTotalPaxMax); // Trigger on flight change
  // $('#land').on('change', updateTotalPaxMax);    // Trigger on "Land Only" checkbox toggle

  // Ensure that if the user manually enters a number greater than the max, it's automatically corrected
  $('#totalPax').on('input', function() 
  {
    var maxSeats = parseInt($(this).attr('max'));
    var currentPax = parseInt($(this).val());

    // If currentPax is greater than maxSeats or less than 1, adjust the value
    if (currentPax > maxSeats) {
      $(this).val(maxSeats); // Reset to the max value
    } else if (currentPax < 1 || isNaN(currentPax)) {
      $(this).val(1); // Reset to 1 if the value is less than 1 or not a number
    }
  });

  // Book Now Button Click Event
  $('#bookNowButton').click(function (event) 
  {
    event.preventDefault(); // Prevent default form submission

    const errors = {
      packageName: 'Please Select a Package.',
      totalPax: 'Please Enter Total Pax.',
      origin: 'Please Select Origin',
      year: 'Please Select Year',
      month: 'Please Select Month',
      flightDate: 'Please Select Flight Date.',
      fName: 'Please Enter First Name',
      lName: 'Please Enter Last Name',
      mName: 'Please Enter Middle Name',
      suffix: 'Please Select Suffix',
      countryCode: 'Please Select Country Code',
      contactNo: 'Please Enter Contact No',
      email: 'Please Enter Email'
    };

    // Reset error messages and remove invalid class
    $('span[id$="Error"]').text('');
    $('select, input').removeClass('is-invalid');

    let isValid = true; // Initialize isValid flag
    // Extract the numeric value from the label's text
    let totalSeatsText = $('#availSeats').text();
    let totalSeats = parseInt(totalSeatsText.replace(/\D/g, '')) || 0;  // Replace all non-digit characters and parse the number
    let landOnly = $('#land').prop('checked');

    console.log(totalSeats);

    // Validation function
    const validateField = (selector, errorMsgKey) => 
    {
      const fieldValue = $(selector).val();
      if (!fieldValue) 
      {
        $(`${selector}Error`).text(errors[errorMsgKey]); // Update error message
        $(selector).addClass('is-invalid'); // Add invalid class
        isValid = false; // Set valid flag to false
      }
    };

    // Validate all fields
    validateField('#packageName', 'packageName');
    validateField('#totalPax', 'totalPax');
    validateField('#origin', 'origin');
    validateField('#year', 'year');
    validateField('#month', 'month');
    validateField('#flightDate', 'flightDate');
    validateField('#fName', 'fName');
    validateField('#lName', 'lName');
    validateField('#mName', 'mName');
    validateField('#suffix', 'suffix');
    validateField('#countryCode', 'countryCode');
    validateField('#contactNo', 'contactNo');
    validateField('#email', 'email');

    // Additional check for totalPax to ensure it is not 0
    const totalPax = parseInt($('#totalPax').val());
    if (totalPax === 0 || isNaN(totalPax)) 
    {
      $('#totalPaxError').text('Total Pax cannot be 0. Please enter a valid number.');
      $('#totalPax').addClass('is-invalid');
      isValid = false;
    }

    // Clear error messages when inputs are focused or changed
    $('select, input').on('focus change', function () 
    {
      const errorSpanId = `#${$(this).attr('id')}Error`;
      $(this).removeClass('is-invalid'); // Remove invalid class
      $(errorSpanId).text(''); // Clear error message
      $('#errorMessage').text(''); // Show error message in the UI
    });

    // Combined validation for Land Only or Seat availability
    if (isValid) 
    {
      const firstName = $('#fName').val().trim();
      const lastName = $('#lName').val().trim();
      let middleName = $('#mName').val().trim() || '';
      let suffix = $('#suffix').val().trim() || '';
      let email = $('#email').val().trim();

      // Set suffix and middle name to an empty string if they are "N/A"
      suffix = suffix === 'N/A' ? '' : suffix;
      middleName = middleName === 'N/A' ? '' : middleName;

      // Format middle name to the first letter followed by a dot, if not empty
      middleName = middleName ? middleName.charAt(0) + '.' : '';

      // Concatenate to full name in the desired format
      const fullName = `${lastName}, ${firstName} ${suffix} ${middleName}`;

      // Check if "Land Only" is selected
      if ($('#land').prop('checked')) 
      {
        // Set the full name and email, and trigger modal
        $('#contactPersonName').text(fullName);
        $('#contactPersonEmail').text(email);
        $('#guestCount').text(totalPax);
        $('#BookingSummaryModal').modal('show'); // Trigger modal display
      } 
      // else if (totalPax > totalSeats) 
      // {
      //   // If land only is not selected, check for seat availability
      //   $('#errorMessage').text('The Available Seats are not enough.'); // Show error message in the UI
      //   alert('The Available Seats are not enough.'); // Show error message as an alert
      // } 
      else 
      {
        // Set the full name in the contactPersonName paragraph
        $('#contactPersonName').text(fullName);
        // Set the email in the email paragraph
        $('#contactPersonEmail').text(email);
        // Set the total number of guests in the guestCount paragraph
        $('#guestCount').text(totalPax);

        $('#BookingSummaryModal').modal('show'); // Trigger modal display
      }
    } 
    else 
    {
      $('#errorMessage').text('Validation failed or no seats available.'); // Show error message in the UI
      console.error('Validation failed or no seats available.');
    }
  });

  // Automatically recalculate total price when flightDate or totalPax changes
  $('#flightDate, #totalPax').on('input change', function () 
  {
    updateTotalPrice(); // Recalculate total price
  });

  // Recalculate total price when "land" checkbox is toggled
  document.getElementById('land').addEventListener('change', function() 
  {
    updateTotalPrice(); // Recalculate total price when land is checked/unchecked
  });

  // Function to update total price calculation
  function updateTotalPrice() 
  {
    let totalPrice = 0;
    const isLandChecked = document.getElementById('land').checked; // Check if "land" checkbox is checked
    const totalPax = parseInt($('#totalPax').val()) || 0; // Get total passengers, default to 0 if invalid

    if (isLandChecked) 
    {
      // If the "land" checkbox is checked, use the package price
      flightDetailsContainer.style.display = 'block'; // Show when checked
      const packagePriceField = document.getElementById('packagePrice');
      if (packagePriceField) 
      {
        const packagePrice = parseFloat(packagePriceField.value) || 0; // Use package price, default to 0 if invalid
        totalPrice = packagePrice * totalPax; // Multiply by total passengers

        // Update the display to show the package price per pax
        const flightPriceSpan = document.getElementById('flightPrice');
        if (flightPriceSpan) 
        {
          const formattedPackagePrice = `${formatNumberWithCommas(packagePrice.toFixed(2))}`;
          flightPriceSpan.innerText = formattedPackagePrice; // Show package price per pax
        }
      }
    } 
    else 
    {
      // If "land" is unchecked, use the original flight price from the hidden input
      const flightPriceSpan = document.getElementById('flightPrice');
      const flightPriceField = $('#flightPricee'); // Hidden input field using jQuery
      flightDetailsContainer.style.display = 'none'; // Show when checked
      if (flightPriceSpan && flightPriceField.length) 
      {
        const originalFlightPrice = parseFloat(
          flightPriceField.val().replace(/,/g, '').replace('₱', '').trim()
        ) || 0; // Retrieve and parse the original flight price
        totalPrice = originalFlightPrice * totalPax; // Calculate total price using original flight price

        const formattedOriginalPrice = `${formatNumberWithCommas(originalFlightPrice.toFixed(2))}`;
        flightPriceSpan.innerText = formattedOriginalPrice; // Show original flight price
      }
    }

    // Format and update the total price display
    const displayTotalPriceElement = document.getElementById('displayTotalPrice');
    if (displayTotalPriceElement) 
    {
      displayTotalPriceElement.innerText = `${formatNumberWithCommas(totalPrice.toFixed(2))}`; // Format with commas
    }

    // Update the totalPrice hidden input field
    const totalPriceField = document.getElementById('totalPrice');
    if (totalPriceField) 
    {
      totalPriceField.value = totalPrice.toFixed(2); // Set value with 2 decimal places
    }
  }

  // Optional: Listen for changes in pax fields
  document.querySelectorAll('.pax').forEach((element) => 
  {
    element.addEventListener('input', function() 
    {
      updateTotalPrice(); // Recalculate when pax value changes
    });
  });

  // Helper function to format numbers with commas
  function formatNumberWithCommas(num) 
  {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  // function updateTotalPaxMax() 
  // {
  //   // Get the input values
  //   var flightId = $('#flightId').val();
  //   var agentId = $('#agentId').val();
  //   var isLandOnlyChecked = $('#land').is(':checked');

  //   if (flightId !== '') 
  //   {
  //     // Perform an AJAX request to fetch seat information
  //     $.ajax(
  //     {
  //       url: 'Agent Section/functions/fetchMaxSeatsPerAgent.php', // Replace with your server-side script URL
  //       method: 'POST',
  //       data: { flightId: flightId, agentId: agentId }, // Send the flightId to the server
  //       dataType: 'json', // Specify that we're expecting JSON response
  //       success: function(response) 
  //       {
  //         if (response.flightId !== null) 
  //         {
  //           // Extract the maxSeats from the response
  //           var maxSeats = response.maxSeats;
  //           var totalSeats = response.totalSeatsLeft;

  //           if (!isLandOnlyChecked) 
  //           {
  //             // If "Land Only" is not checked, dynamically update the max attribute
  //             $('#totalPax').attr('max', maxSeats);

  //             // Check if the current value of totalPax exceeds maxSeats, reset to maxSeats if needed
  //             var currentPax = $('#totalPax').val();
  //             if (currentPax > maxSeats) 
  //             {
  //               $('#totalPax').val(maxSeats); // Adjust the value
  //               console.log('Pax left: ' + maxSeats);
  //               console.log('Seats left: ' + totalSeats);
  //             }

  //             // Display the available seats
  //             $('#maxSeats').text('Agent-Specific Available Seats for this Flight: ' + maxSeats);
  //             $('#availSeats').text('Total Remaining Seats for this Flight: ' + totalSeats);
  //           } 
  //           else 
  //           {
  //             // If "Land Only" is checked, set a default max value and clear the display
  //             $('#totalPax').attr('max', 999); // Example max value, adjust as needed
  //             $('#maxSeats').text(' ');
  //             $('#availSeats').text(' ');
  //           }
  //         } 
  //         else 
  //         {
  //           // Handle the case where no flight information is found
  //           $('#maxSeats').text('Available Seats for this Flight: N/A');
  //         }
  //       },
  //       error: function(xhr, status, error) 
  //       {
  //         // Log any errors
  //         console.error('AJAX Error:', error);
  //       }
  //     });
  //   } 
  //   else 
  //   {
  //       // Reset if no flight ID is selected
  //       $('#totalPax').removeAttr('max');
  //       $('#maxSeats').text('Available Seats for this Flight: N/A');
  //   }
  // }

  // Initial call to set total price on page load
  updateTotalPrice();

});

</script>

 </body>
</html>