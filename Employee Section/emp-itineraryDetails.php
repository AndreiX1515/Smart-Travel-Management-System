<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generate Itinerary</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-generateItinerary.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">

  <!-- WickedPicker CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/wickedpicker@0.4.1/dist/wickedpicker.min.css">

  <!-- WickedPicker JS -->
  <script src="https://cdn.jsdelivr.net/npm/wickedpicker@0.4.1/dist/wickedpicker.min.js"></script>
</head>

<body>

  <?php include '../Employee Section/includes/emp-sidebar.php' ?>

  <!-- Main Container -->
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
            <h5 class="header-title">Itinerary Details</h5>
          </div>
        </div>

      </div>
    </div>

    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Employee Section/emp-itineraryTable.php'; // Replace with your actual URL
      });
    </script>


    <!-- DB Query for Itinerary Details based on Itinerary ID -->
    <?php
    if (!isset($_GET['id'])) {
      die("Invalid Itinerary ID");
    }

    $itineraryId = intval($_GET['id']); // Sanitize input
    
    // Fetch itinerary main details with guide info and voucher info
    $sql = "
			SELECT 
				i.itineraryId,
				i.itineraryName,
				i.noOfDays,
				i.packageId,
				i.periodStart,
				i.periodEnd,
				i.voucherId,
				v.voucherCode,
				e.fName AS guideFirstName,
				e.lName AS guideLastName,
				e.countryCode,
				e.contactNo
			FROM itineraries i
			LEFT JOIN employee e ON i.guideId = e.accountId
			LEFT JOIN vouchers v ON i.voucherId = v.voucherId
			WHERE i.itineraryId = ?
		";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $itineraryId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
      die("Itinerary not found");
    }

    // Format guide full name
    $guideName = isset($row['guideFirstName'], $row['guideLastName'])
      ? trim($row['guideFirstName'] . ' ' . $row['guideLastName'])
      : '';

    $itinerary = [
      'itineraryId' => $row['itineraryId'],
      'itineraryName' => $row['itineraryName'],
      'noOfDays' => $row['noOfDays'],
      'packageId' => $row['packageId'],
      'periodStart' => $row['periodStart'],
      'periodEnd' => $row['periodEnd'],
      'guideName' => $guideName,
      'countryCode' => $row['countryCode'] ?? '',
      'contactNumber' => $row['contactNo'] ?? '',
      'voucherId' => $row['voucherId'] ?? null,
      'voucherCode' => $row['voucherCode'] ?? null,
      'cities' => [],
      'days' => []
    ];


    // ✅ Fetch cities & hotels from itinerarytourareashotels
    $sqlCityHotel = "SELECT city, hotel FROM itinerarytourareashotels WHERE itineraryId = ? ORDER BY orderNo ASC";
    $stmt = $conn->prepare($sqlCityHotel);
    $stmt->bind_param("i", $itineraryId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($rowCity = $result->fetch_assoc()) {
      $itinerary['cities'][] = [
        'city' => $rowCity['city'],
        'hotel' => $rowCity['hotel']
      ];
    }

    // Fetch days, areas, hotels, activities, and meal plans
    $sqlDays = "
			SELECT 
      d.dayId, 
      d.dayNumber, 

      -- Areas per day
      COALESCE(a.areaIds, '') AS areas,

      -- Hotels per day
      COALESCE(h.hotelIds, '') AS hotels,

      -- Activities per day
      COALESCE(act.activities, '') AS activities,

      -- Meals per day
      COALESCE(mp.meals, '') AS meals

			FROM itineraryDays d

			-- Join: Areas (returning areaIds instead of areaNames)
      LEFT JOIN (
          SELECT 
              dayId, 
              GROUP_CONCAT(DISTINCT ida.areaId ORDER BY ida.areaId ASC SEPARATOR ',') AS areaIds
          FROM itineraryAreas ia
          INNER JOIN itinerarydataarea ida ON ia.areaName = ida.areaName
          GROUP BY dayId
      ) a ON d.dayId = a.dayId


      -- Join: Hotels (returning hotelIds instead of hotelNames)
      LEFT JOIN (
          SELECT 
              ih.dayId, 
              GROUP_CONCAT(DISTINCT h.hotelId ORDER BY h.hotelId ASC SEPARATOR ',') AS hotelIds
          FROM itineraryHotels ih
          INNER JOIN hotels h ON ih.hotelId = h.hotelId
          GROUP BY ih.dayId
      ) h ON d.dayId = h.dayId


			-- Join: Activities
			LEFT JOIN (
				SELECT 
					dayId, 
					GROUP_CONCAT(activityName ORDER BY activityId ASC SEPARATOR ',') AS activities
				FROM itineraryActivities
				GROUP BY dayId
			) act ON d.dayId = act.dayId

			-- Join: Meals (link mealId to mealName)
			LEFT JOIN (
				SELECT 
					imp.dayId, 
					GROUP_CONCAT(DISTINCT md.mealName ORDER BY md.mealId ASC SEPARATOR ',') AS meals
				FROM itineraryMealPlans imp
				INNER JOIN itineraryDataMealPlan md ON imp.mealId = md.mealId
				GROUP BY imp.dayId
			) mp ON d.dayId = mp.dayId

			WHERE d.itineraryId = ?
			ORDER BY d.dayNumber ASC;
		";

    $stmt = $conn->prepare($sqlDays);
    $stmt->bind_param("i", $itineraryId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($day = $result->fetch_assoc()) {
      $areas = $day['areas'] ? explode(',', $day['areas']) : [];
      $hotels = $day['hotels'] ? explode(',', $day['hotels']) : [];
      $meals = $day['meals'] ? explode(',', $day['meals']) : [];

      // Log if arrays are empty
      if (empty($areas)) {
        echo "<script>console.log('No areas found for day " . $day['dayNumber'] . "');</script>";
      }
      if (empty($hotels)) {
        echo "<script>console.log('No hotels found for day " . $day['dayNumber'] . "');</script>";
      }
      if (empty($meals)) {
        echo "<script>console.log('No meals found for day " . $day['dayNumber'] . "');</script>";
      }

      // Store day data in itinerary array
      $itinerary['days'][] = [
        'day' => $day['dayNumber'],
        'areas' => $areas,
        'hotels' => $hotels,
        'activities' => $day['activities'] ? explode(',', $day['activities']) : [],
        'meals' => $meals
      ];
    }

    $jsonData = json_encode($itinerary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // Output the data in the raw format in the browser's console
    echo "<script>
          const itinerary = $jsonData;
          // console.log('Database Itinerary Data:', itinerary);

           console.log(JSON.stringify(itinerary, null, 2));


          // populateItineraryValuesFromDB(itinerary);
        </script>";


    ?>

    <div class="main-content">

      <div class="form-container-wrapper">
        <form id="itineraryGenerate" class="d-flex flex-column gap-3">

          <input type="hidden" id="itineraryId" value="<?= htmlspecialchars($itineraryId); ?>" readonly>
          
          <!-- Itinerary Details Card -->
          <div class="card">

            <div class="card-header bg-primary">
              <h5>itinerary Details</h5>
            </div>

            <div class="card-body">

              <!-- Itinerary Name -->
              <div class="row mb-2">

                <div class="columns col-md-6">
                  <div class="column-header">
                    <label for="flightDate">Itinerary Name:
                      <span class="text-danger"> *</span>
                    </label>
                  </div>

                  <div class="form-group">
                    <input type="text" class="form-control" id="itineraryName" name="itineraryName"
                      value="<?= $itinerary['itineraryName']; ?>" required>
                  </div>
                </div>

              </div>

              <!-- Country, Package Row -->
              <div class="row">

                <!-- Country Dropdown -->
                <div class="columns col-md-6">
                  <div class="column-header">
                    <label for="countrySelect">Country
                      <span class="text-danger"> *</span>
                    </label>
                  </div>

                  <div class="form-group">
                    <select class="form-select" id="countrySelect" name="countrySelect" required>
                      <option disabled>Select Country</option>
                      <?php
                      // Example static list of countries; you can replace this with dynamic DB values if needed
                      $countries = ["South Korea", "Philippines", "Japan", "Thailand", "Vietnam", "Malaysia", "Singapore"];

                      foreach ($countries as $country) {
                        $selected = ($country === "Korea") ? "selected" : "";
                        echo "<option value='" . htmlspecialchars($country) . "' $selected>$country</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <!-- Package Dropdown -->
                <div class="columns col-md-6">
                  <div class="column-header">
                    <label for="packageSelect">Package
                      <span class="text-danger"> *</span>
                    </label>
                  </div>

                  <div class="form-group">
                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                      <?php
                      $selectedPackageId = $itinerary['packageId'];

                      // Fetch all packages
                      $sql1 = "SELECT packageId, packageName FROM package ORDER BY packageId ASC";
                      $res1 = $conn->query($sql1);

                      if ($res1->num_rows > 0) {
                        while ($row = $res1->fetch_assoc()) {
                          $selected = ($row['packageId'] == $selectedPackageId) ? 'selected' : '';
                          echo "<option value='{$row['packageId']}' $selected>{$row['packageName']}</option>";
                        }
                      } else {
                        echo "<option value=''>No packages available</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>

              </div>

              <!-- Periods, Guide Row -->
              <div class="row">

                <!-- Flight Date Datepicker -->
                <div class="columns col-md-6">

                  <div class="column-header">
                    <label for="flightDate">Periods
                      <span class="text-danger"> *</span>
                    </label>
                  </div>

                  <div class="datepicker-wrapper d-flex align-items-center gap-2">

                    <!-- Start Date -->
                    <div class="form-group mb-0">
                      <div class="date-range-inputs-wrapper position-relative">
                        <div class="input-with-icon">
                          <input type="text" class="datepicker form-control" id="PeriodStartDate"
                            placeholder="Start Date" value="<?= $itinerary['periodStart']; ?>" readonly required>
                          <i class="fas fa-calendar-alt calendar-icon position-absolute"
                            style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                        </div>
                      </div>
                    </div>

                    <!-- Dash Separator -->
                    <div class="dash-separator fw-bold">→</div>

                    <!-- End Date -->
                    <div class="form-group mb-0">
                      <div class="date-range-inputs-wrapper position-relative">
                        <div class="input-with-icon">
                          <input type="text" class="datepicker form-control" id="PeriodEndDate" placeholder="End Date"
                            value="<?= $itinerary['periodEnd']; ?>" readonly required>
                          <i class="fas fa-calendar-alt calendar-icon position-absolute"
                            style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                        </div>
                      </div>
                    </div>
                  </div>


                </div>

                <!-- Guide Dropdown -->
                <div class="columns col-md-6">
                  <div class="column-header">
                    <label for="guideName">Guide
                      <span class="text-danger"> *</span>
                    </label>
                  </div>

                  <div class="form-group">
                    <select class="form-select" id="guideName" name="guideName" required onchange="updateContact(this)">
                      <?php
                      $selectedGuideId = $itinerary['guideId']; // Change this to use the actual ID
                      
                      $query = "SELECT accountId, fName, lName, mName, contactNo, countryCode FROM employee WHERE isTourGuide = 1";
                      $result = mysqli_query($conn, $query);

                      while ($row = mysqli_fetch_assoc($result)) {
                        $accountId = $row['accountId'];
                        $fName = $row['fName'];
                        $lName = $row['lName'];
                        $mName = $row['mName'];
                        $contactNo = $row['contactNo'];
                        $countryCode = $row['countryCode'];

                        $middleInitial = !empty($mName) ? strtoupper(substr($mName, 0, 1)) . '.' : '';
                        $fullName = $lName . ', ' . $fName . ($middleInitial ? ' ' . $middleInitial : '');

                        $isSelected = ($selectedGuideId == $accountId) ? 'selected' : '';

                        echo "<option value=\"$accountId\" data-contact=\"$contactNo\" data-code=\"$countryCode\" $isSelected>$fullName</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <!-- Guide Script -->
                <script>
                  function updateContact(selectElement) {
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const contact = selectedOption.getAttribute('data-contact');
                    const code = selectedOption.getAttribute('data-code');

                    if (contact && code) {
                      document.getElementById('countryCode').value = code;
                      document.getElementById('contactNumber').value = contact;
                    }
                  }
                </script>

                <!-- Guide Contact Number -->
                <div class="columns col-md-4 d-none">
                  <div class="column-header">
                    <label for="contactNumber">Contact Number <span class="text-danger">*</span></label>
                  </div>
                  <div class="form-group d-flex flex-row align-items-center">
                    <select class="form-select" id="countryCode" style="width: 80px;" disabled>
                      <option value="+63" selected>+63</option>
                      <option value="+82">+82</option>
                    </select>
                    <input type="text" class="form-control ms-2" id="contactNumber" name="contactNumber"
                      placeholder="9***********" disabled>
                  </div>
                </div>

              </div>

              <!-- Tour Areas, Hotels -->
              <div class="row">
                <div class="columns col-md-12">
                  <div class="column-header">
                    <label for="tourAreas">Tour Areas, Hotels</label>
                  </div>
                </div>
              </div>

              <!-- Tour Areas, Hotels Dropdowns - 1 -->
              <div class="row">
                <div class="columns col-md-10">
                  <div class="cityhotel-wrapper d-flex align-items-center gap-2">
                    <div class="cityhotel-item">
                      <select class="form-select city-select" id="city1" name="city(1)" required>
                        <option selected disabled value="">Select City</option>
                      </select>
                    </div>
                    <div class="dash-separator">-></div>
                    <div class="cityhotel-item">
                      <select class="form-select hotel-select" id="hotel1" name="hotel(1)" required>
                        <option selected disabled value="">Select Hotel</option>
                      </select>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger text-light" id="trash1"
                      onclick="resetCityHotel(1)" title="Reset City & Hotel">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Tour Areas, Hotels Dropdowns - 2 -->
              <div class="row">
                <div class="columns col-md-10">
                  <div class="cityhotel-wrapper d-flex align-items-center gap-2">
                    <div class="cityhotel-item">
                      <select class="form-select city-select" id="city2" name="city(2)">
                        <option selected disabled value="">Select City</option>
                      </select>
                    </div>
                    <div class="dash-separator">-></div>
                    <div class="cityhotel-item">
                      <select class="form-select hotel-select" id="hotel2" name="hotel(2)">
                        <option selected disabled value="">Select Hotel</option>
                      </select>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger text-light" id="trash2"
                      onclick="resetCityHotel(2)" title="Reset City & Hotel">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Tour Areas, Hotels Dropdowns - 3 -->
              <div class="row">
                <div class="columns col-md-10">
                  <div class="cityhotel-wrapper d-flex align-items-center gap-2">
                    <div class="cityhotel-item">
                      <select class="form-select city-select" id="city3" name="city(3)">
                        <option selected disabled value="">Select City</option>
                      </select>
                    </div>
                    <div class="dash-separator">-></div>
                    <div class="cityhotel-item">
                      <select class="form-select hotel-select" id="hotel3" name="hotel(3)">
                        <option selected disabled value="">Select Hotel</option>
                      </select>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger text-light" id="trash3"
                      onclick="resetCityHotel(3)" title="Reset City & Hotel">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </div>
                </div>
              </div>

              <script>
                document.addEventListener('change', function (e) {
                  // Show/Hide Trash Icon for City/Hotel Combo Row
                  if (e.target.classList.contains('city-select') || e.target.classList.contains('hotel-select')) {
                    const row = e.target.closest('.cityhotel-row');
                    const city = row.querySelector('.city-select')?.value;
                    const hotel = row.querySelector('.hotel-select')?.value;
                    const trashBtn = row.querySelector('.remove-cityhotel');

                    if (trashBtn) {
                      trashBtn.classList.toggle('d-none', !(city || hotel));
                    }
                  }
                });

                document.addEventListener('click', function (e) {
                  const btn = e.target.closest('.remove-cityhotel');
                  if (btn) {
                    const row = btn.closest('.cityhotel-row');
                    const citySelect = row.querySelector('.city-select');
                    const hotelSelect = row.querySelector('.hotel-select');

                    if (citySelect) {
                      citySelect.selectedIndex = 0; // Reset to placeholder
                      citySelect.dispatchEvent(new Event("change"));
                    }

                    if (hotelSelect) {
                      hotelSelect.selectedIndex = 0; // Reset to placeholder
                      hotelSelect.dispatchEvent(new Event("change"));
                    }

                    btn.classList.add('d-none');
                  }
                });
              </script>

            </div>
          </div>

          <!-- No. of Days Card -->
          <div class="card">
            <div class="card-header">
              <h5>No. of Days</h5>
            </div>

            <div class="card-body">
              <!-- Package Row -->
              <div class="row">
                <div class="columns col-md-4">
                  <div class="form-group days-select-wrapper">
                    <label for="flightDate">No. of days<span class="text-danger"> *</span></label>
                    <select class="form-select" id="select-days" name="numberOfDays" required disabled>
                      <option selected disabled>Select Number of Days</option>
                    </select>
                    <small class="form-text text-muted">Changing this will clear all your data on
                      the fields.</small>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="itinerary-container" id="itinerary-container"> </div>
      </div>

      <!-- Form footer with both buttons -->
			<div class="form-footer">

				<div class="itinerary-footer-first">
					<p class="mb-0 text-muted">
						Connected to Voucher: 
						<span class="fw-bold"><?= $itinerary['voucherCode'] ?? "--"; ?></span>
					</p>
				</div>


				<div class="itinerary-footer-second">
					<!-- Right side content -->
					<button type="button" class="btn btn-primary btn-sm" id="submitEdit">Submit Edit</button>

					<select id="actionSelector" class="form-select" style="width: 120px;">
					<option value="xlsx" selected>Excel (.xlsx)</option>
					<option value="pdf" disabled>PDF</option>
					<option value="both" disabled>Excel & PDF</option>
					</select>

					<button type="button" class="btn btn-primary btn-sm disabled" id="submitTourAndVoucher">Generate Itinerary & Voucher</button>

					<button type="button" class="btn btn-primary btn-sm" id="submitTour"></button>
				</div>

			</div>

    </div>
  </div>

  <!-- Modal - Modal Template Name -->
  <div class="modal fade" id="templateNameModal" tabindex="-1" aria-labelledby="templateNameModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="templateNameModalLabel">Enter Template Name</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Please enter a template name before proceeding:</p>

          <!-- Template Name Input -->
          <div class="mt-3">
            <input type="text" class="form-control" id="templateName" placeholder="Enter template name">
            <small id="nameError" class="text-danger d-none">Template name is already taken.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="proceedWithSubmission()">Proceed</button>
        </div>
      </div>
    </div>
  </div>

  <?php include '../Employee Section/includes/emp-scripts.php' ?>

  <!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
      const modalEl = document.getElementById("templateNameModal");
      const modal = new bootstrap.Modal(modalEl);
      modal.show();
    });
  </script> -->

  <!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
      const toggle = document.getElementById("toggleVoucherSelect");
      const wrapper = document.getElementById("voucherSelectWrapper");

      toggle.addEventListener("change", function () {
        wrapper.style.display = this.checked ? "block" : "none";
      });
    });
  </script> -->

  <!-- Timepicker & Datepicker General Script -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Function to initialize flatpickr with common settings
      function initFlatpickr(selector, options) {
        document.querySelectorAll(selector).forEach(function (element) {
          flatpickr(element, options);
        });
      }

      // Initialize all datepickers with custom configuration
      initFlatpickr("input.datepicker", {
        dateFormat: "Y-m-d",
        // minDate: "today",
        disableMobile: true,
        appendTo: document.body, // Attach calendar to the body
        position: "auto", // Auto position for flexibility
        zIndex: 9999, // Ensure calendar stays on top
        onOpen: function () {
          const calendar = document.querySelector('.flatpickr-calendar');
          if (calendar) {
            calendar.style.position = 'absolute';
            const inputRect = this.input.getBoundingClientRect();
            calendar.style.top = `${inputRect.bottom + window.scrollY + 8}px`; // Position it below the input field
          }
        }
      });

      // Initialize all timepickers with 24-hour format
      initFlatpickr("input.timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i", // 24-hour format
        time_24hr: true,
        disableMobile: true,
        appendTo: document.body, // Attach timepicker to the body
        position: "auto", // Auto position for flexibility
        zIndex: 9999, // Ensure timepicker stays on top
        onOpen: function () {
          const timepicker = document.querySelector('.flatpickr-calendar');
          if (timepicker) {
            timepicker.style.position = 'absolute';
            const inputRect = this.input.getBoundingClientRect();
            timepicker.style.top = `${inputRect.bottom + window.scrollY + 8}px`; // Position it below the input field
          }
        }
      });
    });
  </script>

  <!-- JavaScript to Initialize Timepicker -->
  <script>
    $(document).ready(function () {
      $('#flightTime').wickedpicker({
        twentyFour: true, // 24-hour format
        now: null, // Don't auto-fill current time
        showSeconds: false, // Hide seconds
        title: 'Select Time', // Title of popup
        placement: 'top' // Attempt to show above input
      });
    });
  </script>

  <!-- For DB value to Itinerary Cards Values JSON (For Editing) -->
  <script>
    let liveItineraryData;
    const originalJsonData = <?= json_encode($jsonData) ?>;
    const originalJsonString = JSON.stringify(sortKeys(originalJsonData));

    // Utility to deeply sort object keys for comparison
    function sortKeys(obj) {
      if (Array.isArray(obj)) {
        return obj.map(sortKeys);
      } else if (obj !== null && typeof obj === 'object') {
        return Object.keys(obj).sort().reduce((acc, key) => {
          acc[key] = sortKeys(obj[key]);
          return acc;
        }, {});
      }
      return obj;
    }

    document.addEventListener("DOMContentLoaded", function () {
      const submitEditBtn = document.getElementById('submitEdit');
      const generateBtn = document.getElementById('submitTour');
      const selectDays = document.getElementById('select-days');
      const itineraryContainer = document.getElementById("itinerary-container");

      // submitEditBtn.disabled = true;
      // selectDays.disabled = true;
      // if (generateBtn) generateBtn.disabled = true;

      let itineraryData = <?= json_encode($itinerary); ?>;
      liveItineraryData = JSON.parse(JSON.stringify(itineraryData)); // Clone

      window.updateLiveItineraryData = function () {
        const itineraryId = parseInt(document.getElementById("itineraryId").value);
        const itineraryName = document.getElementById("itineraryName").value;
        const packageId = parseInt(document.getElementById("packageSelect").value);
        const periodStart = document.getElementById("PeriodStartDate").value;
        const periodEnd = document.getElementById("PeriodEndDate").value;
        const guideId = parseInt(document.getElementById("guideName").value);
        const countryCode = document.getElementById("countryCode").value;
        const contactNumber = document.getElementById("contactNumber").value;

        const cities = [];
        for (let i = 1; i <= 3; i++) {
          const city = document.getElementById(`city${i}`)?.value || "";
          const hotel = document.getElementById(`hotel${i}`)?.value || "";
          if (city && hotel) {
            cities.push({ city, hotel });
          }
        }

        const itineraryDetails = {
          itineraryId,
          itineraryName,
          packageId,
          periodStart,
          periodEnd,
          guideId,
          countryCode,
          contactNumber,
          cities,
          noOfDays: parseInt(selectDays.value)
        };

        const daysDetails = [];
        const cards = itineraryContainer.querySelectorAll(".itinerary-card");

        cards.forEach((card, index) => {
          const areas = Array.from(card.querySelectorAll(".area-select")).map(sel => sel.value);
          const meals = Array.from(card.querySelectorAll(".meal-plan-select")).map(sel => sel.value);
          let hotels = Array.from(card.querySelectorAll(".hotel-select")).map(sel => sel.value);
          const activities = Array.from(card.querySelectorAll(".itinerary-select")).map(sel => sel.value);

          while (hotels.length < 2) hotels.push("");
          while (areas.length < 3) areas.push("");

          daysDetails.push({
            day: index + 1,
            areas,
            meals,
            hotels,
            activities
          });
        });

        // Update global reference
        liveItineraryData = {
          itineraryDetails,
          daysDetails
        };

        // Debug log on update
        console.log("Initial loaded liveItineraryData:");
        console.log(JSON.stringify(liveItineraryData, null, 2));
      };

      // Trigger console log on initial load
      updateLiveItineraryData();
    });
  </script>

  <!-- For Tour Areas, Hotels Dropdowns -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const cities = ["Seoul", "Gyeonggi-do", "Incheon", "Jeju"];
      const hotelsByCity = {
        "Seoul": ["Smart Stay Hotel"],
        "Gyeonggi-do": ["Ramada Hotel", "Marina Bay Hotel"],
        "Incheon": ["Air Sky Hotel", "Royal Emporium", "Smart Stay Hotel"],
        "Jeju": ["Tamara Hotel"]
      };

      // Initialize dropdowns and events
      [1, 2, 3].forEach(index => {
        const citySelect = document.getElementById(`city${index}`);
        const hotelSelect = document.getElementById(`hotel${index}`);
        const trashBtn = document.getElementById(`trash${index}`);

        populateCityDropdown(citySelect, cities, "Select City");

        citySelect.addEventListener("change", () => {
          updateAllCityDropdowns();
          updateAllHotelDropdowns();
          checkSelectStatus(index);
        });

        hotelSelect.addEventListener("change", () => {
          updateAllHotelDropdowns();
          checkSelectStatus(index);
        });

        if (trashBtn) {
          trashBtn.addEventListener("click", () => resetCityHotel(index));
        }

        checkSelectStatus(index);
      });

      // Populate city dropdown
      function populateCityDropdown(select, options, placeholder) {
        select.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
        options.forEach(opt => {
          const option = document.createElement("option");
          option.value = opt;
          option.textContent = opt;
          select.appendChild(option);
        });
      }

      // Get selected cities
      function getSelectedCities(excludeIndex = null) {
        return [1, 2, 3]
          .filter(i => i !== excludeIndex)
          .map(i => document.getElementById(`city${i}`).value)
          .filter(Boolean);
      }

      // Get selected hotels
      function getSelectedHotels(excludeIndex = null) {
        return [1, 2, 3]
          .filter(i => i !== excludeIndex)
          .map(i => document.getElementById(`hotel${i}`).value)
          .filter(Boolean);
      }

      // Update all city dropdowns (disable already selected cities)
      function updateAllCityDropdowns() {
        const selectedCities = getSelectedCities();

        [1, 2, 3].forEach(index => {
          const select = document.getElementById(`city${index}`);
          const currentValue = select.value;

          populateCityDropdown(select, cities, "Select City");

          Array.from(select.options).forEach(option => {
            if (selectedCities.includes(option.value) && option.value !== currentValue) {
              option.disabled = true;
            }
          });

          if (currentValue) select.value = currentValue;
        });
      }

      // Update all hotel dropdowns based on selected cities
      function updateAllHotelDropdowns() {
        const selectedCities = getSelectedCities();
        const allowedHotels = selectedCities.flatMap(city => hotelsByCity[city] || []);
        const selectedHotels = getSelectedHotels();

        [1, 2, 3].forEach(index => {
          const hotelSelect = document.getElementById(`hotel${index}`);
          const currentHotel = hotelSelect.value;

          hotelSelect.innerHTML = `<option value="" disabled selected>Select Hotel</option>`;

          allowedHotels.forEach(hotel => {
            const option = document.createElement("option");
            option.value = hotel;
            option.textContent = hotel;

            // Disable if already selected in another dropdown
            if (selectedHotels.includes(hotel) && currentHotel !== hotel) {
              option.disabled = true;
            }
            hotelSelect.appendChild(option);
          });

          if (currentHotel) {
            hotelSelect.value = currentHotel;
          }
        });
      }

      // Reset both city and hotel
      window.resetCityHotel = function (index) {
        const citySelect = document.getElementById(`city${index}`);
        const hotelSelect = document.getElementById(`hotel${index}`);

        if (citySelect) citySelect.selectedIndex = 0;
        if (hotelSelect) hotelSelect.selectedIndex = 0;

        checkSelectStatus(index);
        updateAllCityDropdowns();
        updateAllHotelDropdowns();
      };

      // Show/hide trash icon
      function checkSelectStatus(index) {
        const city = document.getElementById(`city${index}`);
        const hotel = document.getElementById(`hotel${index}`);
        const trash = document.getElementById(`trash${index}`);

        if (!city.value && !hotel.value) {
          trash.style.display = "none";
        } else {
          trash.style.display = "inline-block";
        }
      }
    });
  </script>

  <!-- Itinerary Day Cards Generation Script -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const selectDays = document.getElementById("select-days");
      const itineraryContainer = document.getElementById("itinerary-container");
      const formFooter = document.querySelector(".form-footer");
      const totalDays = 5;


      const selectedValues = {
        area: {},
        hotel: {},
        itinerary: {}
      };


      // ========= For Areas Data Fetching and Rendering ========= 
      let koreanTourAreas = [];

      // Load Areas From DB
      loadTourAreas();

      function loadTourAreas() {
        fetch('../Employee Section/functions/fetchScripts/getAreas.php')
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              koreanTourAreas = data.data; // now contains [{ areaId, areaName }, ...]
              renderAreaSelects();
            } else {
              alert("⚠️ Failed to load tour areas.");
            }
          })
          .catch(err => {
            console.error("❌ Area fetch error:", err);
            alert("An error occurred while loading tour areas.");
          });
      }

      function renderAreaSelects() {
        const selects = document.querySelectorAll(".area-select");

        selects.forEach(select => {
          const currentValue = select.value;
          const disabled = select.disabled;

          if (disabled) return;

          select.innerHTML = `<option value="" disabled selected>Select Area</option>`;

          koreanTourAreas.forEach(area => {
            const option = document.createElement("option");
            option.value = area.areaId; // use areaId as value
            option.textContent = area.areaName;

            if (area.areaId === currentValue) {
              option.selected = true;
            }

            select.appendChild(option);
          });
        });
      }




      // ========= For Hotels Data Fetching and Rendering ========= 
      let hotelsByArea = {}; // Format: { areaId: [ { hotelId, hotelName }, ... ] }

      loadHotelsFromDB();

      function loadHotelsFromDB() {
        fetch('../Employee Section/functions/fetchScripts/getHotelsByArea.php')
          .then(res => res.json())
          .then(data => {
            const isValidObject = data.status === 'success' &&
              data.data &&
              typeof data.data === 'object' &&
              !Array.isArray(data.data);

            if (!isValidObject || Object.keys(data.data).length === 0) {
              console.warn("⚠️ Invalid or empty hotel data format received.");
              alert("⚠️ Failed to load valid hotel data.");
              return;
            }

            hotelsByArea = data.data;
            console.log("✅ Hotels Loaded (by areaId):", JSON.stringify(hotelsByArea, null, 2));

            renderHotelSelectsOnLoad();
          })
          .catch(err => {
            console.error("❌ Hotel data fetch error:", err);
            alert("An error occurred while loading hotels.");
          });
      }


      function renderHotelSelectsOnLoad() {
        const hotelSelects = document.querySelectorAll(".hotel-select");

        hotelSelects.forEach((select) => {
          const areaId = select.dataset.area?.trim();
          const currentValue = select.value;

          if (!areaId) return;

          const hotelList = hotelsByArea[areaId] || [];

          select.innerHTML = `<option value="" disabled selected>Select ${select.name?.replace(/_/g, " ").replace(/\d/g, "") || "Hotel"}</option>`;

          hotelList.forEach(hotel => {
            const option = document.createElement("option");
            option.value = hotel.hotelId;
            option.textContent = hotel.hotelName;

            if (hotel.hotelId == currentValue) {
              option.selected = true;
            }

            select.appendChild(option);
          });
        });
      }






     // ========= Delegated Listener for Itinerary and Area Selects =========
    document.addEventListener("change", function (e) {
      // Itinerary
      if (e.target.classList.contains("itinerary-select")) {
        updateItineraryDropdowns();
        checkItineraryTrashVisibility();
      }

      // Area => Hotels (on same day only)
      if (e.target.classList.contains("area-select")) {
        const day = e.target.dataset.day;
        updateHotelsForDay(day);
        // checkAreaTrashVisibility(); // Optional
      }
    });


    // ========= Update Hotels Based on Selected Areas =========
    function updateHotelsForDay(day) {
      const areaSelects = document.querySelectorAll(`.area-select[data-day="${day}"]`);
      const selectedAreas = Array.from(areaSelects)
        .map(sel => sel.value?.trim())
        .filter(val => val && val !== "");

      // Guard clause: if no areas are selected, clear hotel dropdowns
      if (selectedAreas.length === 0) {
        const hotelSelects = document.querySelectorAll(`.hotel-select[data-day="${day}"]`);
        hotelSelects.forEach(hotelSelect => {
          hotelSelect.innerHTML = `<option disabled selected value="">Select Hotel</option>`;
        });
        return;
      }

      let combinedHotels = [];
      const addedHotelIds = new Set();

      selectedAreas.forEach(area => {
        const normalizedKey = Object.keys(hotelsByArea).find(
          k => k.toLowerCase() === area.toLowerCase()
        );
        if (normalizedKey && hotelsByArea[normalizedKey]) {
          hotelsByArea[normalizedKey].forEach(hotel => {
            if (!addedHotelIds.has(hotel.hotelId)) {
              combinedHotels.push(hotel);
              addedHotelIds.add(hotel.hotelId);
            }
          });
        }
      });

      // Update hotel selects
      const hotelSelects = document.querySelectorAll(`.hotel-select[data-day="${day}"]`);
      hotelSelects.forEach(hotelSelect => {
        const currentValue = hotelSelect.value;
        hotelSelect.innerHTML =
          `<option disabled selected value="">Select Hotel</option>` +
          combinedHotels.map(hotel => `
            <option value="${hotel.hotelId}" ${hotel.hotelId == currentValue ? "selected" : ""}>
              ${hotel.hotelName}
            </option>`).join("");
      });
    }



      // ========= Disable already-chosen itinerary values =========
      function updateItineraryDropdowns() {
        const allItinerarySelects = document.querySelectorAll(".itinerary-select");
        const selectedValues = [...allItinerarySelects].map(s => s.value).filter(Boolean);

        allItinerarySelects.forEach(select => {
          const currentVal = select.value;
          const options = select.querySelectorAll("option");

          options.forEach(option => {
            if (option.value && option.value !== currentVal) {
              option.disabled = selectedValues.includes(option.value);
            } else {
              option.disabled = false;
            }
          });
        });
      }


      // ========= For Itinerary Activities Data Fetching and Rendering (For Changing - Adapt DB Table Values)========= 
      const allItineraries = [
        "Arrival at Incheon Airport - Flight: 5J118 (MNL-ICN)",
        "Meeting and Greeting with an English-speaking guide",
        "Transfer to Seoul and check in at the hotel",
        "King Canoe Quay", "Chuncheon Samaksan Mountain Lake Cable Car", "Chuncheon Sailo 248 (Suspension Bridge)",
        "Jade Garden", "PotatoBatt (Bakery)", "Nami Island",
        "Small France Culture Village", "Italian Village (Pinocchio Village)", "N Seoul Tower", "Everland Theme Park",
        "Ginseng Museum", "Cosmetic Duty Free Shop", "Free time shopping at Shilla Duty Free Shop",
        "Myeongdong Street", "Free shopping at Myeongdong Street", "Gyeongbokgung Palace", "Red Pine Store",
        "Korea Produce Jewel Amethyst Shop", "Jamsil Seokchon Lake (Cherry Blossom)", "Gimpo Hyundai Outlet",
        "Experience making Kimbop"
      ];



      // ========= For Meal Plan Data Fetching and Rendering ========= 
      let koreanMealPlans = {};


      // Meal Plans Data Fetch
      loadMealPlansFromDB();

      function loadMealPlansFromDB() {
        fetch('../Employee Section/functions/fetchScripts/getMealPlansData.php')
          .then(res => res.json())
          .then(data => {
            // console.log("Meal Plans Fetched:", JSON.stringify(data, null, 2));

            if (data.status === "success") {
              koreanMealPlans = data.data;
              renderMealSelects(); // Call rendering after load
            } else {
              alert("Failed to load meal options.");
            }
          })
          .catch(err => {
            console.error("Meal plan fetch error:", err);
            alert("An error occurred while loading meals.");
          });
      }

      function renderMealSelects() {
        const selects = document.querySelectorAll(".meal-plan-select");

        selects.forEach(select => {
          const type = select.dataset.type;
          const currentValue = select.value;

          if (!type || !koreanMealPlans[type]) return;

          // Clear existing options
          select.innerHTML = `<option value="" selected disabled>Select ${type}</option>`;

          // Re-populate
          koreanMealPlans[type].forEach(m => {
            const option = document.createElement("option");
            option.value = m.id ?? "";
            option.textContent = m.name;

            // Retain previous selection if exists
            if (m.id === currentValue) {
              option.selected = true;
            }

            select.appendChild(option);
          });
        });
      }







      // Populate days dropdown
      for (let num = 1; num <= totalDays; num++) {
        const option = document.createElement("option");
        option.value = num;
        option.textContent = `Day ${num}`;
        selectDays.appendChild(option);
      }



      
      selectDays.value = totalDays;

      // Build cards based on selected days
      const selectedDays = parseInt(selectDays.value);
      itineraryContainer.innerHTML = "";

      for (let day = 1; day <= selectedDays; day++) {
        const card = document.createElement("div");
        card.className = "card itinerary-card mb-3";

        card.innerHTML = `
          <div class="card-header bg-primary text-white fw-bold">Day ${day}</div>

          <div class="card-body">
            <div class="container-fluid">

              <!-- Area -->
              <div class="row mb-3">
              ${day === 1 ? `
                <div class="col-4">
                <label class="form-label fw-semibold">Area:</label>    
                <select class="form-select area-select" data-day="${day}" disabled>
                  <option value="Incheon" selected>Incheon</option>
                </select>
                </div>` :
            ["Area 1", "Area 2", "Area 3"].map((label, i) => `
                <div class="col-4 mb-2">
                  <label class="form-label fw-semibold">${label}</label>
                  <div class="d-flex align-items-center gap-2">
                  <select class="form-select area-select"
                      id="area${day}_area${i + 1}"
                      data-day="${day}"
                      data-index="area${day}_area${i + 1}"
                      name="area_${day}_area${i + 1}"
                      ${i === 0 ? 'required' : ''}>
                    <option value="" selected disabled>Select ${label}</option>
                    ${koreanTourAreas.map(a => `<option value="${a}">${a}</option>`).join("")}
                  </select>
                  <button type="button"
                      class="btn btn-sm btn-danger text-light area-trash"
                      id="trash-area${day}_area${i + 1}"
                      onclick="resetArea('area${day}_area${i + 1}')"
                      title="Reset Area"
                      style="display: none;">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                  </div>
                </div>
                `).join("")
          }
              </div>

              <!-- Hotels -->
              <div class="row mb-3">
                <div class="col-12">
                  <label class="form-label fw-semibold">Hotels:</label>
                  <div class="row">
                  ${["Hotel 1", "Hotel 2"].map((label, i) => {
                    return `<div class="col-md-6 col-sm-12 mb-2 d-flex align-items-center gap-2">
                              <select class="form-select hotel-select" 
                                  id="hotel${day}_${i}" 
                                  data-day="${day}" 
                                  data-index="${day}_${i}" 
                                  name="hotel_${day}_${i}" 
                                  ${i === 0 ? 'required' : ''}>
                                <option disabled selected value="">Select ${label}</option>
                                ${day === 1 
                                  ? [
                                      { id: 1, name: "Smart Stay Hotel" },
                                      { id: 4, name: "Air Sky Hotel" }
                                    ].map(hotel => `<option value="${hotel.id}">${hotel.name}</option>`).join("") 
                                  : "" }
                              </select>
                              <button type="button" 
                                  class="btn btn-sm btn-danger text-light hotel-trash"
                                  id="trash-hotel${day}_${i}"
                                  onclick="resetHotel('${day}_${i}')"
                                  title="Reset Hotel"
                                  style="display: none;">
                                <i class="fas fa-trash-alt"></i>
                              </button>
                            </div>`;
                  }).join("")}
                  </div>
                </div>
              </div>


              <!-- Meals -->
              <div class="row mb-3">
              ${day === 1 ? `
                <div class="col-4">
                <label class="form-label fw-semibold">Meal Plan:</label>
                <select class="form-select meal-plan-select" data-day="${day}" disabled>
                  <option value="9" selected>Snack</option>
                </select>
                </div>`
            :
            ["breakfast", "lunch", "dinner"].map(type => `
                <div class="col-4 mb-2">
                <label class="form-label fw-semibold text-capitalize">${type}</label>
                <div class="d-flex align-items-center gap-2">
                  <select class="form-select meal-plan-select"
                      id="meal${day}_${type}"
                      data-day="${day}"
                      data-type="${type}"
                      name="meal_${day}_${type}"
                      required>
                  <option value="" selected disabled>Select ${type}</option>

                  ${(koreanMealPlans[type] || []).map(m => `
                    <option value="${m.id ?? ''}">${m.name}</option>
                  `).join("")}


                  </select>
                  <button type="button"
                      class="btn btn-sm btn-danger text-light meal-trash"
                      id="trash-meal${day}_${type}"
                      onclick="resetMeal('${day}_${type}')"
                      title="Reset Meal"
                      style="display: none;">
                  <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </div>
                `).join("")
          }
              </div>




              <!-- Itineraries -->
              <div class="row mb-3">
                <div class="col-12"><label class="form-label fw-semibold">Itinerary:</label></div>
                ${(day === 1 ? [1, 2, 3, 4] : [1, 2, 3, 4, 5, 6, 7]).map(num => {
                    const options = (day === 1 ? allItineraries.slice(0, 3) : allItineraries.slice(3))
                      .map(i => `<option value="${i}">${i}</option>`).join("");

            return `
                      <div class="col-12 mb-2 d-flex align-items-center gap-2">
                        <select class="form-select itinerary-select" id="itinerary${day}_${num}" data-index="${day}_${num}" data-day="${day}">

                          <option value="" selected disabled hidden>Select Itinerary ${num}</option>
                          ${options}
                        </select>

                        <button type="button" class="btn btn-sm btn-danger text-light itinerary-trash"
                          id="trash-itinerary${day}_${num}"
                          onclick="resetItinerary('${day}_${num}')"
                          title="Reset Itinerary"
                          style="display: none;">
                          <i class="fas fa-trash-alt"></i>
                        </button>

                      </div>`;
            }).join("")
          }
              </div>

            </div>
          </div>
        `;

        itineraryContainer.appendChild(card);
      }

      // ✅ Call AFTER the DOM is fully built
      window.addEventListener("load", function () {
        setTimeout(() => {
          populateItineraryValuesFromDB(itinerary);
        }, 15); // increase if necessary to wait for select options to be populated
      });



      // Value Fetch
      function populateItineraryValuesFromDB(itinerary) {
        itinerary.days.forEach(day => {
          const dayNumber = day.day;

          // --- AREAS ---
          day.areas.forEach(areaId => {
            const areaSelects = document.querySelectorAll(`.area-select[data-day="${dayNumber}"]`);

            for (let select of areaSelects) {
              if (!select.value || select.value === "") {
                const optionExists = [...select.options].some(opt => opt.value === areaId);

                if (optionExists) {
                  select.value = areaId;

                  // Delay change trigger slightly to allow any listeners or DOM updates
                  setTimeout(() => {
                    select.dispatchEvent(new Event("change", { bubbles: true }));
                  }, 0);

                  break;
                }
              }
            }
          });


          // --- HOTELS ---
          day.hotels.forEach(hotelId => {
            const hotelSelects = document.querySelectorAll(`.hotel-select[data-day="${dayNumber}"]`);

            for (let select of hotelSelects) {
              if (!select.value || select.value === "") {
                const optionExists = [...select.options].some(opt => opt.value === hotelId);
                if (optionExists) {
                  select.value = hotelId;
                  select.dispatchEvent(new Event("change"));
                  break;
                }
              }
            }
          });


          // --- MEALS ---
          day.meals.forEach(mealName => {
            const mealSelects = document.querySelectorAll(`.meal-plan-select[data-day="${dayNumber}"]`);
            for (let select of mealSelects) {
              if (!select.value || select.value === "") {
                const option = [...select.options].find(opt => opt.value === mealName || opt.textContent.trim() === mealName);
                if (option) {
                  select.value = option.value;
                  select.dispatchEvent(new Event("change"));
                  break;
                }
              }
            }
          });

          // --- ITINERARIES / ACTIVITIES ---
          day.activities.forEach(activity => {
            const itinerarySelects = document.querySelectorAll(`.itinerary-select[data-day="${dayNumber}"]`);
            for (let select of itinerarySelects) {
              if (!select.value || select.value === "") {
                const optionExists = [...select.options].some(opt => opt.value === activity || opt.textContent.trim() === activity);
                if (optionExists) {
                  [...select.options].forEach(opt => {
                    if (opt.textContent.trim() === activity || opt.value === activity) {
                      select.value = opt.value;
                    }
                  });
                  select.dispatchEvent(new Event("change"));
                  break;
                }
              }
            }
          });

        });
      }


      // Show footer
      formFooter.style.display = selectedDays ? "flex" : "none";

      // Reset itinerary
      window.resetItinerary = function (index) {
        const select = document.getElementById(`itinerary${index}`);
        if (select) {
          select.selectedIndex = 0;
          updateItineraryDropdowns();
          checkItineraryTrashVisibility();
        }
      };


      // Show or hide trash buttons
      function checkItineraryTrashVisibility() {
        document.querySelectorAll(".itinerary-select").forEach(select => {
          const index = select.dataset.index;
          const trash = document.getElementById(`trash-itinerary${index}`);

          // Hide if value is blank or still on placeholder
          if (trash) {
            trash.style.display = select.value && select.value !== "" ? "inline-block" : "none";
          }
        });
      }
    });


    // Hotel Delete Logic
    document.addEventListener("DOMContentLoaded", () => {
      // Show/hide trash button for hotels
      function checkHotelTrashVisibility() {
        document.querySelectorAll(".hotel-select").forEach(select => {
          const index = select.dataset.index;
          const trash = document.getElementById(`trash-hotel${index}`);
          if (trash) {
            trash.style.display = select.value && select.value !== "" ? "inline-block" : "none";
          }
        });
      }

      // Event listeners for hotel selects
      document.querySelectorAll(".hotel-select").forEach(select => {
        select.addEventListener("change", checkHotelTrashVisibility);
      });

      // Initial visibility check
      checkHotelTrashVisibility();

      // Reset handler
      window.resetHotel = function (index) {
        const select = document.getElementById(`hotel${index}`);
        if (select) {
          select.selectedIndex = 0;
          checkHotelTrashVisibility();
        }
      };
    });


    // Meal Plan Delete Logic
    document.addEventListener("DOMContentLoaded", () => {
      // Show/hide trash button for meals
      function checkMealTrashVisibility() {
        document.querySelectorAll(".meal-plan-select").forEach(select => {
          const index = `${select.dataset.day}_${select.dataset.type}`;
          const trash = document.getElementById(`trash-meal${index}`);
          if (trash) {
            trash.style.display = select.value && select.value !== "" ? "inline-block" : "none";
          }
        });
      }

      // Event listeners for meal selects
      document.querySelectorAll(".meal-plan-select").forEach(select => {
        select.addEventListener("change", checkMealTrashVisibility);
      });

      // Initial visibility check
      checkMealTrashVisibility();

      // Reset handler
      window.resetMeal = function (index) {
        const select = document.getElementById(`meal${index}`);
        if (select) {
          select.selectedIndex = 0;
          checkMealTrashVisibility();
        }
      };
    });

    // Area Delete Logic
    document.addEventListener("DOMContentLoaded", () => {
      // Show/hide trash button for areas
      function checkAreaTrashVisibility() {
        document.querySelectorAll(".area-select").forEach(select => {
          const index = select.dataset.index;
          const trash = document.getElementById(`trash-${index}`);
          if (trash) {
            trash.style.display = select.value && select.value !== "" ? "inline-block" : "none";
          }
        });
      }

      // Event listeners for area selects
      document.querySelectorAll(".area-select").forEach(select => {
        select.addEventListener("change", checkAreaTrashVisibility);
      });

      // Initial visibility check
      checkAreaTrashVisibility();

      // Reset handler
      window.resetArea = function (index) {
        // index = e.g., area2_area2
        const select = document.querySelector(`.area-select[data-index="${index}"]`);
        if (select) {
          select.selectedIndex = 0;
          checkAreaTrashVisibility();
        }
      };
    });


    // Form validation for required selects (For Required Fields Validation)
    document.getElementById("submitTour").addEventListener("click", function (event) {
      const requiredSelects = document.querySelectorAll("select.form-select[required]");
      let isValid = true;

      requiredSelects.forEach(select => {
        if (!select.value || select.value === "Select City" || select.value === "Select Hotel") {
          select.classList.add("is-invalid");
          isValid = false;
        } else {
          select.classList.remove("is-invalid");
        }
      });

      if (isValid) {
        alert("Itinerary successfully created!");
      }
    });



  </script>



  <!-- Form Submission Script -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.getElementById("itineraryGenerate");
      const submitBtn = document.getElementById("submitTour");
      const modalEl = document.getElementById("templateNameModal");

      form.addEventListener("input", toggleSubmitButton);
      form.addEventListener("change", toggleSubmitButton);

      function toggleSubmitButton() {
        const isValid = form.checkValidity();
        submitBtn.disabled = !isValid;

        if (isValid) {
          const json = collectFormData();
          console.log("✅ Form is now valid. 'Generate' button enabled.");

          console.group("📦 Validated Form Data");
          console.table(json);
          console.groupEnd();

          console.group("📝 Stringified JSON Output");
          console.log(JSON.stringify(json, null, 2));
          console.groupEnd();
        }
      }

      submitBtn.addEventListener("click", function (e) {
        e.preventDefault();
        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }
        if (modalEl) {
          const modal = new bootstrap.Modal(modalEl);
          modal.show();
        } else {
          console.error("❌ Modal element not found!");
        }
      });
    });

    function collectFormData() {
      const getTrim = (id) => document.getElementById(id)?.value.trim() ?? "";

      const selectedPackage = getTrim("packageSelect");
      const noOfDays = getTrim("select-days");
      const startDate = getTrim("PeriodStartDate");
      const endDate = getTrim("PeriodEndDate");
      const guideName = getTrim("guideName");
      const countryCode = getTrim("countryCode");
      const contactNumber = getTrim("contactNumber");

      const guideSelect = document.getElementById("guideName");
      const guideaccountId = guideSelect?.selectedOptions[0]?.getAttribute("data-accountid")?.trim() ?? null;

      const cityHotelsData = {};
      for (let i = 1; i <= 3; i++) {
        const city = getTrim(`city${i}`);
        const hotel = getTrim(`hotel${i}`);
        if (city || hotel) {
          cityHotelsData[`city${i}`] = city;
          cityHotelsData[`hotel${i}`] = hotel;
        }
      }

      const itineraryData = [];
      document.querySelectorAll(".itinerary-card").forEach(dayCard => {
        const day = dayCard.querySelector(".hotel-select")?.dataset.day || "Unknown";

        const selectedAreas = [...dayCard.querySelectorAll(".area-select[data-day]")]
          .map(area => area.value.trim()).filter(Boolean);

        const selectedMealPlans = [...dayCard.querySelectorAll(".meal-plan-select[data-day]")]
          .map(meal => parseInt(meal.value.trim(), 10)).filter(Number.isInteger);

        const selectedHotels = [...dayCard.querySelectorAll(".hotel-select")]
          .map(select => parseInt(select.value.trim(), 10)).filter(Boolean);

        const selectedItineraries = [...dayCard.querySelectorAll(".itinerary-select")]
          .map(select => select.value.trim()).filter(Boolean);

        itineraryData.push({
          day,
          areas: selectedAreas.length ? selectedAreas : [""],
          meal_plans: selectedMealPlans.length ? selectedMealPlans : [""],
          hotels: selectedHotels.length ? selectedHotels : [""],
          itineraries: selectedItineraries.length ? selectedItineraries : [""]
        });
      });

      const connectToggle = document.getElementById("toggleVoucherSelect");
      const isConnectToVoucher = connectToggle?.checked ?? false;

      let voucherId = null;
      if (isConnectToVoucher) {
        const voucherSelect = document.getElementById("voucherId");
        const rawValue = voucherSelect?.value.trim();
        voucherId = rawValue && !isNaN(rawValue) ? parseInt(rawValue, 10) : null;
      }


      // Debugging Output
      console.group("🧪 Voucher Info");
      console.log("🔘 isConnectToVoucher:", isConnectToVoucher);
      console.log("🎫 voucherId:", voucherId);
      console.groupEnd();

      return {
        templateName: getTrim("templateName"),
        selectedPackage,
        noOfDays,
        startDate,
        endDate,
        guideName,
        guideaccountId,
        countryCode,
        contactNumber,
        cityHotelsData,
        itineraryData,
        isConnectToVoucher,
        voucherId
      };
    }

    function proceedWithSubmission() {
      const form = document.getElementById("itineraryGenerate");

      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const templateInput = document.getElementById("templateName");
      const nameError = document.getElementById("nameError");

      templateInput.classList.remove("is-invalid");
      nameError.classList.add("d-none");

      if (!templateInput.value.trim()) {
        nameError.textContent = "Please enter a template name before proceeding.";
        templateInput.classList.add("is-invalid");
        nameError.classList.remove("d-none");
        nameError.classList.add("fade-out");

        setTimeout(() => {
          nameError.classList.add("hide");
          setTimeout(() => {
            nameError.classList.add("d-none");
            nameError.classList.remove("fade-out", "hide");
            templateInput.classList.remove("is-invalid");
          }, 1000);
        }, 3000);

        templateInput.focus();
        return;
      }

      const data = collectFormData();
      const submitButton = document.getElementById("submitTour");
      submitButton.disabled = true;

      $.ajax({
        url: "../Employee Section/functions/emp-saveItinerary.php",
        type: "POST",
        data: {
          templateName: data.templateName,
          package: data.selectedPackage,
          noOfDays: data.noOfDays,
          period_start: data.startDate,
          period_end: data.endDate,
          countryCode: data.countryCode,
          contactNumber: data.contactNumber,
          guide: data.guideName,
          guideAccountId: data.guideaccountId,
          userId: <?php echo $accountId ?? 0 ?>,
          cityHotels: JSON.stringify(data.cityHotelsData),
          itinerary: JSON.stringify(data.itineraryData),
          isConnectToVoucher: data.isConnectToVoucher,
          voucherId: data.voucherId
        },
        dataType: "json",
        success: function (response) {
          submitButton.disabled = false;
          if (response.status === "success") {
            alert("Itinerary successfully created! Redirecting...");
            window.location.href = "../Employee Section/emp-itinerarytable.php";
          } else if (response.status === "exists") {
            alert("Template name already exists. Please choose a different name.");
            document.getElementById("templateName").focus();
          } else {
            alert("Error saving itinerary: " + response.message);
          }
        },
        error: function (xhr, status, error) {
          submitButton.disabled = false;
          console.error("AJAX Error:", error);
          console.error("Response Text:", xhr.responseText);
          alert("An error occurred while saving the itinerary.");
        }
      });

      const modalInstance = bootstrap.Modal.getInstance(document.getElementById("templateNameModal"));
      if (modalInstance) {
        modalInstance.hide();
      }
    }
  </script>

</body>

</html>