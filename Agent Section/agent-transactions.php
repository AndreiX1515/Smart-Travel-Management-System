<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">

</head>

<body>
  <?php include '../Agent Section/includes/sidebar.php'; ?> 

  <div class="main-content" id="mainContent">
    <?php include '../Agent Section/includes/navbar.php'; ?>

    <div class="content-wrapper d-flex flex-column">
      <div class="table-container">
        <div class="search-bar">
          <div class="left-side">
            <div class="search-input mb-3">
              <label for="search">Search</label>
              <input type="text" id="search" class="form-control mt-2" placeholder="Search">
            </div>
          </div>

          <div class="right-side">
            <div class="filter-group">
              <div class="filter-field mb-3 d-flex flex-column">
                <label for="packages">Packages</label>
                <select id="packages" class="custom-select mt-2">
                  <option>Packages</option>
                  <option>All</option>
                </select>
              </div>

              <div class="filter-field mb-3 d-flex flex-column">
                <label for="category">Category</label>
                <select id="category" class="custom-select mt-2">
                  <option>Category</option>
                  <option>All</option>
                </select>
              </div>

              <div class="filter-field mb-3 d-flex flex-column">
                <label for="status">Status</label>
                <select id="status" class="custom-select mt-2">
                  <option>Status</option>
                  <option>All</option>
                </select>
              </div>

              <div class="filter-field mb-3 d-flex flex-column">
                <label for="date-range">Date Range (Start - End)</label>
                <div class="input-group date-range-picker mt-2">
                  <input type="date" class="form-control" id="startDate" placeholder="Start date">
                  <span class="input-group-text">→</span>
                  <input type="date" class="form-control" id="endDate" placeholder="End date">
                </div>
              </div>

              <div class="filter-field d-flex justify-content-center">
                <button class="search-button mt-3"><i class="fa-solid fa-magnifying-glass"></i></button>
              </div>
            </div>
          </div>
        </div>

        <!-- <hr style="border: 1px solid grey; margin: 5px 0 20px 0;"> -->

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
        

        <table class="product-table">
          <thead>
            <tr>
                <th>ID</th>
                <th>Contact Person Name</th>
                <th>Contact Person Email</th>
                <th>Contact Person Phone Number</th>
                <th>Package Name</th>
                <th>Booking Date</th>
                <th>Flight Date</th>
                <th>Total Pax</th>
                <th>Status</th>
                <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sql1 = "SELECT
                  b.transactNo AS `T.N`,
                  p.packageName AS `PACKAGE`,
                  b.bookingDate AS `TRANSACTION DATE`,
                  CASE 
                      WHEN b.flightId IS NULL THEN 'Land Only'
                      ELSE DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y')
                  END AS `FLIGHT DATE`,
                  b.pax AS `TOTAL PAX`,
                  CONCAT(
                      b.lName, ', ', b.fName, ' ', 
                      CASE WHEN b.mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ',
                      CASE WHEN b.suffix = 'N/A' THEN '' ELSE b.suffix END
                  ) AS `CONTACT NAME`,
                  b.email AS `CONTACT EMAIL`,
                  CONCAT(b.countryCode, b.contactNo) AS `CONTACT PHONE`,
                  b.status AS `STATUS`
              FROM 
                  booking b
              LEFT JOIN 
                  flight f ON b.flightId = f.flightId
              LEFT JOIN 
                  package p ON b.packageId = p.packageId
              LEFT JOIN
                  agent a ON b.agentId = a.agentId
              WHERE 
                  b.agentId = '$agentId' AND b.status = 'Pending'
              ORDER BY 
                  b.transactNo DESC LIMIT 10";

              $res1 = $conn->query($sql1);

              if ($res1->num_rows > 0) 
              {
                while ($row = $res1->fetch_assoc()) 
                {
                  $transactNo = $row['T.N'];
                  $pax = $row['TOTAL PAX'];

                  $status = isset($row['STATUS']) ? $row['STATUS'] : 'Unknown';
                  $statusClass = '';

                  switch ($status) {
                      case 'Active':
                          $statusClass = 'bg-success text-white'; 
                          break;
                      case 'Inactive':
                          $statusClass = 'bg-danger text-white'; 
                          break;
                      case 'Pending':
                          $statusClass = 'bg-warning text-dark'; 
                          break;
                      default:
                          $statusClass = 'bg-secondary text-white'; 
                  }

                  echo "<tr>
                          <td>{$transactNo}</td>
                          <td>{$row['CONTACT NAME']}</td>
                          <td>{$row['CONTACT EMAIL']}</td>
                          <td>{$row['CONTACT PHONE']}</td>
                          <td>{$row['PACKAGE']}</td>
                          <td>{$row['TRANSACTION DATE']}</td>
                          <td>{$row['FLIGHT DATE']}</td>
                          <td>{$row['TOTAL PAX']}</td>
                          <td>
                           <span class='badge p-2 rounded-pill {$statusClass} '>
                               {$status}
                           </span>
                         </td>
                          <td>
                            <div class='dropdown'>
                              <button class='btn btn-link p-0 text-dark' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='fas fa-ellipsis-v fs-5'></i>
                              </button>
                              <ul class='dropdown-menu'>
                                <li> <a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#updateBookingModal' data-transaction-id='{$row['T.N']}'>
                                  Update Booking </a> 
                                </li>
                                
                                <li><a class='dropdown-item' href='#' onclick='showGuestInfo(\"{$row['T.N']}\")'>Show Guest Information</a></li>
                                
                                <li><a class='dropdown-item' href='#' onclick='showRequestHistory(\"{$row['T.N']}\")'>Show Request History</a></li>
                                
                                <li><a class='dropdown-item' href='#' onclick='showPaymentHistory(\"{$row['T.N']}\")'>Show Payment History</a></li>";
                        
                                // Add the conditional button if FLIGHT DATE is "Land Only"
                                if ($row['FLIGHT DATE'] === "Land Only") 
                                {
                                  echo "<li>
                                          <a class='dropdown-item' href='#' onclick='showLandOnlyDetails(\"{$row['T.N']}\")'>Show Land Only Details</a>
                                        </li>";
                                }
                   echo "</ul>
                       </div>
                     </td>
                   </tr>";
                }
              } 
              else 
              {
                echo "<tr><td colspan='10'>No bookings found</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal for Update Booking -->
  <div class="modal fade" id="updateBookingModal" tabindex="-1" aria-labelledby="updateBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="updateBookingModalLabel">Update Booking</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <!-- Form for updating booking details -->
        <form action="../Agent Section/functions/agent-transactionUpdateBooking-code.php" id="updateBookingForm" method="POST">
          <div class="modal-body">
            <div class="mb-4 d-flex align-items-center w-100">
              <h6 class="mb-0">Transaction ID:</h6>
              <span id="transactionId" class="ms-2"></span>
            </div>

            <input type="hidden" name="transaction_number" value="">

            <h6 class="fw-bold">Personal Information:</h6>

            <div class="row mt-2">
              <div class="col-md-3 mb-3">
                <label for="contactName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fName" name="fName" required>
              </div>
              <div class="col-md-3 mb-3">
                <label for="contactLName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lName" name="lName">
              </div>
              <div class="col-md-3 mb-3">
                <label for="contactMName" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="mName" name="mName">
              </div>
              <div class="col-md-3 mb-3">
                <label for="contactSuffix" class="form-label">Suffix</label>
                <select class="form-control" id="suffix" name="suffix">
                  <option value="Jr.">Jr.</option>
                  <option value="Sr.">Sr.</option>
                  <option value="III">III</option>
                  <option value="IV">IV</option>
                  <option value="V">V</option>
                  <option value="N/A">N/A</option>
                  <!-- Add more options as needed -->
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2 mb-3">
                <label for="countryCode" class="form-label">Country Code</label>
                <!-- <input type="text" class="form-control" id="countryCode" name="countryCode"> -->
                <select name="countryCode" id="countryCode" class="form-select" required>
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
              </div>
              <div class="col-md-4 mb-3">
                <label for="contactPhone" class="form-label">Contact Phone</label>
                <input type="tel" class="form-control" id="contactNo" name="contactNo" placeholder="Contact Number" required>
                <!-- <input type="text" class="form-control" id="contactPhone" name="contactNo" required> -->
              </div>
              <div class="col-md-6 mb-3">
                <label for="contactEmail" class="form-label">Contact Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
            </div>
          
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="updateBooking">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php require "../Agent Section/includes/scripts.php"; ?>

  <script>
    function addGuestInfo(transactionNumber) 
    {
      console.log("Transaction Number: ", transactionNumber); // Debug line
      // Use AJAX to send the transaction number to the server
      $.ajax(
      {
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: { transaction_number: transactionNumber },
        success: function(response) 
        {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Agent Section/agent-addGuest.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) 
        {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }

    function showGuestInfo(transactionNumber) 
    {
      console.log("Transaction Number: ", transactionNumber); // Debug line
      // Use AJAX to send the transaction number to the server
      $.ajax(
      {
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: { transaction_number: transactionNumber },
        success: function(response) 
        {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Agent Section/agent-showGuest.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) 
        {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }

    function showRequestHistory(transactionNumber) 
    {
      console.log("Transaction Number: ", transactionNumber); // Debug line
      // Use AJAX to send the transaction number to the server
      $.ajax(
      {
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: { transaction_number: transactionNumber },
        success: function(response) 
        {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Agent Section/agent-showRequest.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) 
        {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }

    function showPaymentHistory(transactionNumber) 
    {
      console.log("Transaction Number: ", transactionNumber); // Debug line
      // Use AJAX to send the transaction number to the server
      $.ajax(
      {
        url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file that will handle the session setting
        type: 'POST',
        data: { transaction_number: transactionNumber },
        success: function(response) 
        {
          console.log("Response: ", response); // Debug line
          // Redirect to the next page after setting the session
          window.location.href = '../Agent Section/agent-showPayment.php'; // Redirect to your next page
        },
        error: function(xhr, status, error) 
        {
          console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
        }
      });
    }
  </script>



  <script>
    document.addEventListener('DOMContentLoaded', function () 
    {
      const updateBookingModal = document.getElementById('updateBookingModal');
      updateBookingModal.addEventListener('show.bs.modal', function (event) 
      {
        const button = event.relatedTarget; // Button that triggered the modal
        const transactionId = button.getAttribute('data-transaction-id'); // Fetch transaction ID

        // Populate the hidden input field with the transaction ID
        document.querySelector('#updateBookingForm input[name="transaction_number"]').value = transactionId;

        // Display the transaction ID in the modal
        document.getElementById('transactionId').textContent = transactionId;

        // Fetch booking details based on the transaction ID
        fetchBookingDetails(transactionId);
      });

    });
    
    $(document).ready(function ()
    {
      // Fetching Additional Details once Request Type is Selected
      $('#concern').on('change', function () 
      {
        var concernId = $(this).val();  // Get the selected concern ID
        $('#requestDetails').html('<option selected disabled>Select Specific Detail</option>'); // Clear request details field
        $('#price').val(''); // Clear request details field

        // Debugging: Log the selected concernId
        console.log("Selected concernId: ", concernId);

        // Hide the additional details select container initially
        $('#additionalSelectContainer').hide();
        
        // Clear previous options
        $('#additionalDetails').html('<option selected disabled>Select Additional Detail</option>');

        if (concernId) 
        {
          // Debugging: Log the concernId being sent to the server
          console.log("Sending concernId to server: ", concernId);

          $.ajax(
          {
            url: '../Agent Section/functions/fetchConcernDetails.php',  // Your server-side script to fetch additional details
            type: 'POST',
            data: { concernId: concernId },  // Send the concernId as a parameter
            success: function (response) 
            {
              // Debugging: Log the raw response from the server
              console.log("Server response: ", response);

              // Parse the JSON response
              try 
              {
                var data = JSON.parse(response);

                // Debugging: Log the parsed data
                console.log("Parsed response data: ", data);

                // Show the additional select container once data is available
                $('#additionalSelectContainer').show();

                // Populate the additional details select dropdown
                if (Array.isArray(data.detailsData)) {
                  data.detailsData.forEach(function (item) 
                  {
                    var option = $('<option>').val(item.id).text(item.title).data('price', item.price);  // Create an option element
                    $('#requestDetails').append(option);  // Append the option to the additionalDetails dropdown
                  });
                } 
                else 
                {
                  console.error("Error: detailsData is not an array");
                }
              } catch (e) 
              {
                // Handle any JSON parsing errors
                console.error("Error parsing JSON response: ", e);
              }
            },
            error: function (xhr, status, error) 
            {
              // Debugging: Log any AJAX error
              console.error("Error fetching additional details:", error);
              console.log("AJAX error details: ", xhr, status);
            }
          });
        } 
        else 
        {
          // If no valid concern ID is selected, reset the additional details dropdown
          $('#additionalSelectContainer').hide();
          $('#additionalDetails').html('<option selected disabled>Select Additional Detail</option>');
        }
      });

      // When an additional detail is selected, update the price input field
      $('#requestDetails').on('change', function () 
      {
        // Get the selected option's price
        var selectedOption = $(this).find('option:selected');
        var price = selectedOption.data('price');  // Retrieve the price from the selected option

        // Update the price input field with the selected price
        $('#price').val(price);  // Set the price value in the input field

        // Perform the calculation with the 'pax' input
        calculateTotalPrice();
      });

      // When the 'pax' input value changes, recalculate the total price
      $('#paxRequest').on('input', function () 
      {
        calculateTotalPrice();
      });

      // Function to calculate the total price
      function calculateTotalPrice() 
      {
        var price = parseFloat($('#price').val().replace(/,/g, '')) || 0; // Remove commas for calculation
        var pax = parseInt($('#paxRequest').val()) || 0; // Get the pax, default to 0 if NaN

        // Calculate the total price
        var totalPrice = pax * price;

        $('#displayTotalPrice').text(formatNumberWithCommas(totalPrice.toFixed(2))); // Update the input field with the calculated total price
        // Update the price input field or display the total price wherever needed
        $('#TotalPrice').val(totalPrice.toFixed(2)); // Update the input field with the calculated total price
      } 
    });

    function fetchBookingDetails(transactionId) 
    {
      // Use Fetch API to get booking details
      fetch('../Agent Section/functions/getBookingDetails.php', 
      {
        method: 'POST',
        headers: 
        {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ transaction_id: transactionId }),
      })

      .then(response => response.json())
      .then(data => 
      {
        if (data.success) 
        {
          // Populate fields with the fetched data
          document.getElementById('fName').value = data.booking.fName;
          document.getElementById('lName').value = data.booking.lName;
          document.getElementById('mName').value = data.booking.mName;
          document.getElementById('suffix').value = data.booking.suffix;
          document.getElementById('countryCode').value = data.booking.countryCode;
          document.getElementById('contactNo').value = data.booking.contactNo;
          document.getElementById('email').value = data.booking.email;
          document.getElementById('pax').value = data.booking.pax;
        } 
        else 
        {
          console.error('Error fetching booking details:', data.message);
        }
      })
      .catch(error => 
      {
        console.error('Fetch error:', error);
      });
    }

    // Add an event listener to the input field to validate as the user types
    document.getElementById('paxRequest').addEventListener('input', function() 
    {
      validateMaxValue(this);
    });

    
    function updateBooking() 
    {
      const form = document.getElementById('updateBookingForm');
      const formData = new FormData(form);
      // Implement AJAX call to update booking...
      console.log("Updating booking with data:", formData);
    }

    // Helper function to format numbers with commas
    function formatNumberWithCommas(num) 
    {
      return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
  </script>













</body>
</html>