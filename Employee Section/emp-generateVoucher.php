<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generate Voucher</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-generateVoucher.css?v=<?php echo time(); ?>">

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
            <h5 class="header-title">Voucher</h5>
          </div>
        </div>

      </div>
    </div>

    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Employee Section/emp-voucherTable.php'; // Replace with your actual URL
      });
    </script>

    <div class="main-content">
      <div class="form-container">

        <form id="voucherForm" class="d-flex flex-column gap-3">

          <!-- To/From -->
          <div class="card">

            <div class="card-body">

              <div class="row">

                <!-- To -->
                <div class="columns col-md-4">
                  <label for="voucherTo">To <span class="text-danger">*</span></label>

                  <select class="form-select" id="voucherTo" name="voucherTo" required>
                    <option value="" disabled selected>Select Branch</option>

                    <?php
                    // Execute the SQL query
                    $sql1 = "SELECT branchId, branchName FROM branch ORDER BY branchName ASC";
                    $res1 = $conn->query($sql1);

                    // Check if there are results
                    if ($res1->num_rows > 0) {
                      // Loop through the results and generate options
                      while ($row = $res1->fetch_assoc()) {
                        echo "<option value='" . $row['branchName'] . "'>" . $row['branchName'] . "</option>";
                      }
                    } else {
                      echo "<option value=''>No companies available</option>";
                    }
                    ?>
                  </select>
                </div>

                <!-- From -->
                <div class="columns col-md-4">
                  <label for="voucherFrom">From <span class="text-danger">*</span></label>
                  <select class="form-select" id="voucherFrom" name="voucherFrom" required>
                    <option disabled value="">Select Sender</option>
                    <option selected value="Smart Travel">Smart Travel</option>

                  </select>
                </div>

                <!-- Number of Pax -->
                <div class="columns col-md-4">
                  <label for="voucherPaxCount">No. of Pax <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="voucherPaxCount" required>
                </div>

              </div>

            </div>

          </div>

          <!-- Connect to Itinerary -->
          <div class="card">
            <div class="card-header bg-secondary">
              <h5>Connect to Current itinerary (Optional)</h5>
            </div>

            <div class="card-body">
              <div class="row">

                <div class="columns col-md-3">
                  <div class="column-header">
                    <label for="departure1Date"></label>
                  </div>

                  <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="toggleItinerarySelect">
                    <label class="form-check-label" for="toggleItinerarySelect">
                      Connect to Current itinerary:
                    </label>
                  </div>

                </div>

                <div class="columns col-md-6" id="itinerarySelectWrapper" style="display: none;">
                  <select class="form-select mt-1" id="itineraryId" name="itineraryId">
                    <option value="" disabled selected>Select Itinerary</option>
                    <?php
                    $sql = "SELECT itineraryId, itineraryName FROM itineraries ORDER BY createdAt DESC";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['itineraryId'] . "'>" . htmlspecialchars($row['itineraryName']) . "</option>";
                      }
                    } else {
                      echo "<option value=''>No itineraries available</option>";
                    }
                    ?>
                  </select>

                </div>

              </div>

            </div>
          </div>

          <!-- Voucher Details Card -->
          <div class="card">
            <div class="card-header bg-secondary">
              <h5>Voucher Details</h5>
            </div>

            <div class="card-body">

              <!-- First Row -->
              <div class="row">

                <!-- Tour Type -->
                <div class="columns col-md-6">
                  <label for="voucherTour">Tour <span class="text-danger">*</span></label>
                  <select class="form-select" id="voucherTour" name="voucherTour" required>
                    <option selected disabled value="">Select Package Type</option>


                    <?php
                    // Execute the SQL query
                    $sql1 = "SELECT packageId, packageName FROM package ORDER BY packageName ASC";
                    $res1 = $conn->query($sql1);

                    // Check if there are results
                    if ($res1->num_rows > 0) {
                      // Loop through the results and generate options
                      while ($row = $res1->fetch_assoc()) {
                        echo "<option value='" . $row['packageId'] . "'>" . $row['packageName'] . "</option>";
                      }
                    } else {
                      echo "<option value=''>No companies available</option>";
                    }
                    ?>
                  </select>


                </div>

                <!-- Attachment
              <div class="columns col-md-4">
                <label for="voucherAttachment">Attachment <span class="text-danger">*</span></label>
                <select class="form-select" id="voucherAttachment" name="voucherAttachment" required>
                  <option value="" selected disabled>Select Attachment</option>
                  <option value="voucher">Voucher</option>
                  <option value="itinerary">Itinerary</option>
                  <option value="voucher_and_itinerary">Voucher and Itinerary</option>
                </select>
              </div> -->

                <!-- Tour Period -->
                <div class="columns col-md-6">
                  <label for="voucherPeriodStart">Tour Periods <span class="text-danger">*</span></label>
                  <div class="datepicker-wrapper d-flex align-items-center">
                    <div class="input-with-icon me-2">
                      <input type="text" class="datepicker" id="voucherPeriodStart" placeholder="Start" readonly>
                      <i class="fas fa-calendar-alt calendar-icon"></i>
                    </div>
                    <span class="mx-2">→</span>
                    <div class="input-with-icon">
                      <input type="text" class="datepicker" id="voucherPeriodEnd" placeholder="End" readonly>
                      <i class="fas fa-calendar-alt calendar-icon"></i>
                    </div>
                  </div>
                </div>

              </div>

              <!-- Second Row -->
              <div class="row">

                <!-- Guide Dropdown -->
                <div class="columns col-md-6">
                  <label for="guideSelect">Guide <span class="text-danger">*</span></label>
                  <select class="form-select" id="guideSelect" name="guideSelect" required>
                    <option selected disabled value="">Select Guide</option>

                    <?php
                    $sql = "SELECT accountId AS guideAccountId, fName, mName, lName, contactNo, countryCode FROM employee WHERE isTourGuide = 1 ORDER BY lName, fName ASC";
                    $res = $conn->query($sql);

                    if ($res && $res->num_rows > 0) {
                      while ($row = $res->fetch_assoc()) {
                        $guideAccountId = htmlspecialchars($row['guideAccountId']);
                        $fName = $row['fName'];
                        $mName = $row['mName'];
                        $lName = $row['lName'];
                        $contactNo = htmlspecialchars($row['contactNo']);
                        $countryCode = htmlspecialchars($row['countryCode']);

                        $middle = !empty($mName) ? ' ' . $mName : '';
                        $displayName = htmlspecialchars("$lName, $fName$middle", ENT_QUOTES);

                        echo "<option 
                              value='$guideAccountId' 
                              data-name='$displayName' 
                              data-contact='$contactNo' 
                              data-code='$countryCode'>
                              $displayName
                            </option>";
                      }
                    } else {
                      echo "<option disabled>No available tour guides</option>";
                    }
                    ?>
                  </select>
                </div>

                <!-- Guide Contact Info -->
                <div class="columns col-md-6">
                  <label for="contactNumber">Guide Contact <span class="text-danger">*</span></label>
                  <div class="form-group d-flex flex-row align-items-center">
                    <select class="form-select" id="countryCode" style="width: 80px;" disabled>
                      <option value="+63">+63</option>
                      <option value="+82">+82</option>
                    </select>
                    <input type="text" class="form-control ms-2" id="contactNumber" name="contactNumber"
                      placeholder="9***********" disabled>
                  </div>
                </div>

                <!-- Single Unified Script -->
                <script>
                  document.addEventListener("DOMContentLoaded", function () {
                    const guideSelect = document.getElementById('guideSelect');
                    const contactInput = document.getElementById('contactNumber');
                    const codeSelect = document.getElementById('countryCode');

                    function fillGuideContact() {
                      const selected = guideSelect.options[guideSelect.selectedIndex];
                      if (selected && selected.value !== "") {
                        contactInput.value = selected.dataset.contact || '';
                        codeSelect.value = selected.dataset.code || '+63';
                      } else {
                        contactInput.value = '';
                        codeSelect.value = '+63';
                      }
                    }

                    guideSelect.addEventListener('change', fillGuideContact);

                    // Pre-fill on load if already selected
                    if (guideSelect.value) {
                      fillGuideContact();
                    }
                  });
                </script>

              </div>



            </div>
          </div>

          <!-- Date and Hotels Title Card -->
          <div class="card">
            <div
              class="card-header bg-secondary card-title first-wrapper d-flex justify-content-between align-items-center text-white">
              <h5 class="mb-0">Date & Hotels</h5>
              <button type="button" class="add-button btn btn-primary add-exclude-button" onclick="addCard()">
                <i class="fas fa-plus"></i>
              </button>
            </div>

            <div class="card-body">
              <!-- Container for Date & Hotel Cards -->
              <div id="cardsContainer"></div>
            </div>

          </div>

          <!-- Air Schedule -->
          <div class="card">
            <div class="card-header bg-secondary">
              <h5>Air Schedule</h5>
            </div>

            <div class="card-body">

              <!-- Departure #1 -->
              <div class="row">
                <div class="main-header">
                  <div class="header-container">
                    <h6>Departure Flight</h6>
                  </div>
                </div>


                <!-- Flight -->
                <div class="columns col-md-2">
                  <div class="column-header">
                    <label for="departure1Flight">Flight <span class="text-danger">*</span></label>
                  </div>

                  <div class="form-group">
                    <select class="form-select" id="departure1Flight" name="departure1Flight" required>
                      <option value="" selected disabled>Select Flight</option>
                      <option value="KE123">KE123</option>
                      <option value="OZ456">OZ456</option>
                      <option value="JL789">JL789</option>
                    </select>
                  </div>

                </div>

                <!-- Date -->
                <div class="columns col-md-2">
                  <div class="column-header">
                    <label for="departure1Date">Date <span class="text-danger">*</span></label>
                  </div>

                  <div class="datepicker-wrapper">
                    <div class="form-group">
                      <div class="input-with-icon">
                        <input type="text" class="datepicker" id="departure1Date" name="departure1Date"
                          placeholder="Departure Date" readonly>
                        <i class="fas fa-calendar-alt calendar-icon"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Origin - Destination -->
                <div class="columns col-md-4">
                  <div class="column-header">
                    <label>Origin - Destination <span class="text-danger">*</span></label>
                  </div>
                  <div class="datepicker-wrapper d-flex align-items-center">
                    <div class="form-group">
                      <select class="form-select" id="departure1Origin" name="departure1Origin" required>
                        <option value="" selected disabled>Origin</option>
                        <option value="MNL">Manila</option>
                        <option value="ICN">Incheon</option>
                      </select>
                    </div>
                    <div class="dash-separator px-2">→</div>
                    <div class="form-group">
                      <select class="form-select" id="departure1Destination" name="departure1Destination" required>
                        <option value="" selected disabled>Destination</option>
                        <option value="MNL">Manila</option>
                        <option value="ICN">Incheon</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Departure Time - Arrival Time -->
                <div class="columns col-md-4">
                  <div class="column-header">
                    <label>Departure Time - Arrival Time <span class="text-danger">*</span></label>
                  </div>
                  <div class="form-group d-flex flex-row gap-2">

                    <div class="input-with-icon timepicker">
                      <input type="text" class="timepicker form-control-sm" id="departure1DepartureTime"
                        name="departure1DepartureTime" placeholder="Departure Time" readonly required>
                      <i class="fas fa-clock calendar-icon"></i>
                    </div>

                    <span class="align-self-center">to</span>

                    <div class="input-with-icon timepicker">
                      <input type="text" class="timepicker form-control-sm" id="departure1ArrivalTime"
                        name="departure1ArrivalTime" placeholder="Arrival Time" readonly required>
                      <i class="fas fa-clock calendar-icon"></i>
                    </div>

                  </div>
                </div>
              </div>

              <!-- Returning Date -->
              <div class="row">
                <div class="main-header">
                  <div class="header-container">
                    <h6>Returning Flight</h6>
                  </div>
                </div>

                <!-- Flight -->
                <div class="columns col-md-2">
                  <div class="column-header">
                    <label for="departure2Flight">Flight <span class="text-danger">*</span></label>
                  </div>
                  <div class="form-group">
                    <select class="form-select" id="departure2Flight" name="departure2Flight" required>
                      <option value="" selected disabled>Select Flight</option>
                      <option value="KE321">KE321</option>
                      <option value="OZ654">OZ654</option>
                      <option value="JL987">JL987</option>
                    </select>
                  </div>
                </div>

                <!-- Date -->
                <div class="columns col-md-2">
                  <div class="column-header">
                    <label for="departure2Date">Date <span class="text-danger">*</span></label>
                  </div>
                  <div class="datepicker-wrapper">
                    <div class="form-group">
                      <div class="input-with-icon">
                        <input type="text" class="datepicker" id="departure2Date" name="departure2Date"
                          placeholder="Returning Date" readonly>
                        <i class="fas fa-calendar-alt calendar-icon"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Origin - Destination -->
                <div class="columns col-md-4">
                  <div class="column-header">
                    <label>Origin - Destination <span class="text-danger">*</span></label>
                  </div>

                  <div class="datepicker-wrapper d-flex align-items-center">
                    <div class="form-group">
                      <select class="form-select" id="departure2Origin" name="departure2Origin" required>
                        <option value="" selected disabled>Origin</option>
                        <option value="MNL">Manila</option>
                        <option value="ICN">Incheon</option>
                      </select>
                    </div>
                    <span class="mx-1 text-muted">→</span>
                    <div class="form-group">
                      <select class="form-select" id="departure2Destination" name="departure2Destination" required>
                        <option value="" selected disabled>Destination</option>
                        <option value="MNL">Manila</option>
                        <option value="ICN">Incheon</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Departure Time - Arrival Time -->
                <div class="columns col-md-4">
                  <div class="column-header">
                    <label>Departure Time - Arrival Time <span class="text-danger">*</span></label>
                  </div>
                  <div class="form-group d-flex flex-row gap-2">
                    <div class="input-with-icon timepicker">
                      <input type="text" class="timepicker form-control-sm" id="departure2DepartureTime"
                        name="departure2DepartureTime" placeholder="Departure Time" readonly required>
                      <i class="fas fa-clock calendar-icon"></i>
                    </div>
                    <span class="align-self-center">to</span>
                    <div class="input-with-icon timepicker">
                      <input type="text" class="timepicker form-control-sm" id="departure2ArrivalTime"
                        name="departure2ArrivalTime" placeholder="Arrival Time" readonly required>
                      <i class="fas fa-clock calendar-icon"></i>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <!-- Includes Header -->
          <div class="card includes-header-card">
            <div class="card-header bg-secondary card-title includes-wrapper">
              <h5>Includes</h5>
              <button type="button" id="addIncludeBtn" class="add-button btn btn-primary add-exclude-button">+</button>
            </div>

            <div class="card-body" id="includesContainer">
              <!-- JS will generate .row elements here directly -->
            </div>

          </div>

          <!-- Excludes -->
          <div class="card excludes-header-card">
            <div class="card-header bg-secondary card-title excludes-wrapper">
              <h5>Excludes</h5>
              <button type="button" id="addExcludeBtn" class="add-button btn btn-primary add-exclude-button">+</button>
              <!-- Add Exclude Button -->
            </div>

            <div class="card-body">
              <!-- Excludes Rows (Dynamically added) -->
              <div id="excludesContainer"></div>
            </div>

          </div>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary" id="submitTour">Create Voucher</button>
        </form>
      </div>

    </div>
  </div>

  <!-- Modal - For Template Name -->
  <div class="modal fade" id="templateNameModal" tabindex="-1" aria-labelledby="templateNameLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="templateNameLabel">Enter Template Name</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" id="templateName" class="form-control" placeholder="Template Name" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="proceedButton">Proceed</button>
        </div>
      </div>
    </div>
  </div>

  <?php include '../Employee Section/includes/emp-scripts.php' ?>

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
        minDate: "today",
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


  <!-- JS for Itinerary Toggle -->
  <script>
    let isToggled = false; // Declare globally so it's accessible in both scopes

    document.addEventListener("DOMContentLoaded", function () {
      const toggle = document.getElementById("toggleItinerarySelect");
      const itinerarySelectWrapper = document.getElementById("itinerarySelectWrapper");
      const itinerarySelect = document.getElementById("itineraryId");

      toggle.addEventListener("change", function () {
        if (this.checked) {
          // console.log("🟢 Toggle ON: Showing itinerary select");


          itinerarySelectWrapper.style.display = "block";
          itinerarySelect.disabled = false;
          itinerarySelect.setAttribute("required", "required");
          disableItineraryRequirement(true);

          isToggled = true;

          // console.log("📦 isToggled =", isToggled);

        }

        else {
          itinerarySelectWrapper.style.display = "none";
          itinerarySelect.disabled = true;
          itinerarySelect.removeAttribute("required");
          itinerarySelect.value = "";
          disableItineraryRequirement(false);

          isToggled = false;

          // console.log("🔘 Toggle OFF → isToggled =", isToggled);
        }
      });



      itinerarySelect.addEventListener("change", function () {
        const itineraryId = this.value;

        console.log("Itinerary ID:", itineraryId);

        if (!itineraryId) {
          console.warn("⚠️ No itinerary ID selected.");
          return;
        }

        fetch(`../Employee Section/functions/get-itinerary.php?id=${itineraryId}`)
          .then(response => {
            // console.log("Fetch response status:", response.status);

            if (!response.ok) {
              throw new Error(`Server responded with status ${response.status}`);
            }

            return response.json();
          })

          .then(data => {

            console.log("Fetched itinerary data:", JSON.stringify(data, null, 2));

            if (data.error) {
              console.error("Server returned an error:", data.error);
              return;
            }

            populateItineraryForm(data);
          })

          .catch(err => {
            console.error("Fetch failed:", err);
          });
      });


      // Enable or disable required status
      function disableItineraryRequirement(disable) {
        document.querySelectorAll(".itinerary-select").forEach(sel => {
          if (disable) {
            sel.removeAttribute("required");
          } else {
            sel.setAttribute("required", "required");
          }
        });
      }

      // Fill form with itinerary values
      function populateItineraryForm(data) {
        if (!data) {
          console.warn("⚠️ No data received for itinerary");
          return;
        }

        const safeSet = (id, value) => {
          const el = document.getElementById(id);
          if (el) el.value = value || "";
          else console.warn(`⚠️ Element with ID '${id}' not found`);
        };

        safeSet("voucherPeriodStart", data.periodStart);
        safeSet("voucherPeriodEnd", data.periodEnd);
        safeSet("guideSelect", data.accountId);
        safeSet("countryCode", data.countryCode);
        safeSet("contactNumber", data.contactNumber);


        // safeSet("itineraryName", data.itineraryName);
        // safeSet("city1", data.city1);
        // safeSet("hotel1", data.hotel1);
        // safeSet("city2", data.city2);
        // safeSet("hotel2", data.hotel2);
        // safeSet("city3", data.city3);
        // safeSet("hotel3", data.hotel3);
        // safeSet("select-days", data.noOfDays);


        // ✅ Manually select correct option in the voucherTour dropdown
        const voucherSelect = document.getElementById("voucherTour");
        if (voucherSelect) {
          const matchingOption = [...voucherSelect.options].find(opt => opt.value == data.packageId);
          if (matchingOption) {
            voucherSelect.value = matchingOption.value;
          } else {
            console.warn("⚠️ No matching option in #voucherTour for packageId:", data.packageId);
            voucherSelect.selectedIndex = 0; // fallback to "Select Package Type"
          }
        } else {
          console.warn("⚠️ #voucherTour select not found");
        }

        // Optional: regenerate itinerary day cards
        if (typeof generateItineraryCards === "function") {
          generateItineraryCards(parseInt(data.noOfDays));
        }

        if (typeof updateLiveItineraryData === "function") {
          updateLiveItineraryData();
        }
      }
    });
  </script>

  <!-- JSON Variables -->
  <script>
    let voucherDetails = {};
    let cardsJSONData = {};
    let airScheduleDetails = {};
    let includesData = {};
    let excludesData = {};
  </script>

  <!-- Voucher Details JSON -->
  <script>
    function updateVoucherDetails() {
      const toggleCheckbox = document.getElementById("toggleItinerarySelect");
      const isToggled = toggleCheckbox?.checked || false;

      const itinerarySelect = document.getElementById("itineraryId");
      const selectedItineraryId = itinerarySelect?.value || 0;

      const attachmentValue = isToggled
        ? "Voucher & Itinerary"
        : "Voucher";

      const voucherDetails = {
        to: document.getElementById("voucherTo").value,
        from: document.getElementById("voucherFrom").value,
        tour: document.getElementById("voucherTour").value,
        attachment: attachmentValue,
        isConnectToItinerary: isToggled ? 1 : 0,
        itineraryId: isToggled ? selectedItineraryId : null,
        periodStart: document.getElementById("voucherPeriodStart").value,
        periodEnd: document.getElementById("voucherPeriodEnd").value,
        paxCount: document.getElementById("voucherPaxCount").value,
        guide: document.getElementById("guideSelect").value
      };

      return voucherDetails;
    }
  </script>



  <!-- Date & Hotels Data Fetch -->
  <script>
    let cardCount = 0;
    const maxCards = 3;
    let cities = [];
    let hotelsList = [];

    document.addEventListener("DOMContentLoaded", () => {
      fetch('../Employee Section/functions/fetchScripts/getHotels.php')

        .then(response => {
          if (!response.ok) {
            throw new Error(`Network response was not OK (status: ${response.status})`);
          }
          return response.json();
        })

        .then(data => {
          cities = data.cities || [];
          hotelsList = data.hotels || [];

          // Add one default card
          addCard();
        })

        .catch(err => {
          console.error('Failed to fetch hotels:', err);
          alert('Error loading hotel data');
        });
    });


    function generateCardsJSON() {
      const cards = document.querySelectorAll('#cardsContainer > div[data-card-id]');
      let cardsJSONData = {};

      cards.forEach((card, index) => {
        const cardId = index + 1;

        const startDate = document.getElementById(`PeriodStartDate${cardId}`)?.value || '';
        const endDate = document.getElementById(`PeriodEndDate${cardId}`)?.value || '';
        const nights = document.getElementById(`nights${cardId}`)?.value || '';
        const city = document.getElementById(`city${cardId}`)?.value || '';
        const hotel = document.getElementById(`hotel${cardId}`)?.value || '';

        cardsJSONData[`dateAndHotel${cardId}`] = {
          startDate,
          endDate,
          nights,
          city,
          hotel
        };
      });

      return cardsJSONData;
    }


    function addCard() {
      const addBtn = document.getElementById('addIncludeBtn');
      if (addBtn.getAttribute('data-locked') === 'true') return;

      if (cardCount >= maxCards) {
        addBtn.classList.add('btn-disabled');
        addBtn.setAttribute('data-locked', 'true');
        return;
      }

      cardCount++;
      const isFirstCard = cardCount === 1;
      const container = document.getElementById('cardsContainer');
      const card = document.createElement('div');
      card.className = 'mb-4';
      card.setAttribute('data-card-id', cardCount);

      card.innerHTML = `
        <div class="col-12 mb-3 mt-3">
          <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
            <h6 class="fw-semibold text-uppercase text-muted m-0">Date and Hotels #${cardCount}</h6>
            <button type="button" class="btn btn-sm text-white bg-danger border-0 px-2 py-1 remove-card-btn" title="Delete">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </div>

        <div class="row g-4 align-items-end">
          <div class="col-12 col-md-5">
            <label class="form-label">Date</label>
            <div class="d-flex gap-2 align-items-center">
              <div class="position-relative w-100">
                <input type="text" class="form-control datepicker" id="PeriodStartDate${cardCount}" placeholder="Start" readonly>
                <input type="text" class="required-start hidden-required-field"
                  name="requiredStartDate${cardCount}"
                  ${isFirstCard ? 'required' : ''}
                  style="position:absolute; left:-9999px; width:1px; height:1px; opacity:0;">
                <i class="fas fa-calendar-alt position-absolute text-muted"
                  style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
              </div>

              <span class="mx-1 text-muted">→</span>

              <div class="position-relative w-100">
                <input type="text" class="form-control datepicker" id="PeriodEndDate${cardCount}" placeholder="End" readonly>
                <input type="text" class="required-end hidden-required-field"
                  name="requiredEndDate${cardCount}"
                  ${isFirstCard ? 'required' : ''}
                  style="position:absolute; left:-9999px; width:1px; height:1px; opacity:0;">
                <i class="fas fa-calendar-alt position-absolute text-muted"
                  style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-2">
            <label class="form-label" for="nights${cardCount}">No. of Nights</label>
            <input type="text" class="form-control" id="nights${cardCount}" name="nights${cardCount}" ${isFirstCard ? 'required' : ''}>
          </div>

          <div class="col-6 col-md-2">
            <label class="form-label" for="city${cardCount}">City</label>
            <select class="form-control city-select" id="city${cardCount}" name="city${cardCount}" ${isFirstCard ? 'required' : ''}>
              <option value="" disabled selected>Select City</option>
              ${cities.map(city => `<option value="${city}">${city}</option>`).join('')}
            </select>
          </div>

          <div class="col-12 col-md-3">
            <label class="form-label" for="hotel${cardCount}">Hotel</label>
            <select class="form-control hotel-select" id="hotel${cardCount}" name="hotel${cardCount}" disabled ${isFirstCard ? 'required' : ''}>
              <option value="" disabled selected>Select Hotel</option>
            </select>
          </div>
        </div>
      `;

      container.appendChild(card);

      const startInput = document.getElementById(`PeriodStartDate${cardCount}`);
      const endInput = document.getElementById(`PeriodEndDate${cardCount}`);
      const hiddenStart = document.querySelector(`[name="requiredStartDate${cardCount}"]`);
      const hiddenEnd = document.querySelector(`[name="requiredEndDate${cardCount}"]`);
      const nightsInput = document.getElementById(`nights${cardCount}`);

      function calculateNights() {
        const startDate = new Date(startInput.value);
        const endDate = new Date(endInput.value);
        if (startInput.value && endInput.value && endDate > startDate) {
          const diffDays = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
          nightsInput.value = diffDays;
        } else {
          nightsInput.value = "";
        }
      }

      flatpickr(startInput, {
        dateFormat: "Y-m-d",
        minDate: "today",
        disableMobile: true,
        onChange: function (selectedDates, dateStr) {
          if (hiddenStart) hiddenStart.value = dateStr;
          calculateNights();
        }
      });

      flatpickr(endInput, {
        dateFormat: "Y-m-d",
        minDate: "today",
        disableMobile: true,
        onChange: function (selectedDates, dateStr) {
          if (hiddenEnd) hiddenEnd.value = dateStr;
          calculateNights();
        }
      });

      const citySelect = card.querySelector('.city-select');
      const hotelSelect = card.querySelector('.hotel-select');

      citySelect.addEventListener('change', (e) => {
        const selectedCity = e.target.value;
        populateHotelsForCity(hotelSelect, selectedCity);
      });

      hotelSelect.addEventListener('change', () => {
        cardsJSONData = generateCardsJSON();
        console.log("Voucher Details:", JSON.stringify(voucherDetails, null, 2));
        console.log("Date and Hotels:", JSON.stringify(cardsJSONData, null, 2));
      });

      card.querySelector('.remove-card-btn').addEventListener('click', () => {
        const index = parseInt(card.getAttribute('data-card-id'));
        if (index === 1) {
          startInput._flatpickr.clear();
          endInput._flatpickr.clear();
          nightsInput.value = "";
          citySelect.value = "";
          hotelSelect.innerHTML = `<option value="" disabled selected>Select Hotel</option>`;
          hotelSelect.disabled = true;
          if (hiddenStart) hiddenStart.value = "";
          if (hiddenEnd) hiddenEnd.value = "";
        } else {
          card.remove();
          cardCount--;
          updateCardHeaders();

          if (cardCount < maxCards) {
            addBtn.classList.remove('btn-disabled');
            addBtn.removeAttribute('data-locked');
          }
        }
      });

      if (cardCount >= maxCards) {
        addBtn.classList.add('btn-disabled');
        addBtn.setAttribute('data-locked', 'true');
      }
    }


    function populateHotelsForCity(hotelSelect, city) {
      hotelSelect.innerHTML = `<option value="" disabled selected>Select Hotel</option>`;
      if (!city) {
        hotelSelect.disabled = true;
        return;
      }

      const matches = hotelsList.filter(h => h.hotelCity === city);
      if (matches.length === 0) {
        hotelSelect.disabled = true;
        return;
      }

      matches.forEach(hotel => {
        const opt = document.createElement('option');
        opt.value = hotel.hotelId;
        opt.textContent = hotel.hotelName;
        hotelSelect.appendChild(opt);
      });

      hotelSelect.disabled = false;
    }

    function updateCardHeaders() {
      const cards = document.querySelectorAll('#cardsContainer > div[data-card-id]');
      cardCount = cards.length;

      cards.forEach((card, idx) => {
        const cardId = idx + 1;
        card.setAttribute('data-card-id', cardId);

        const header = card.querySelector('h6');
        if (header) header.textContent = `Date and Hotels #${cardId}`;

        card.querySelectorAll('input, select').forEach(el => {
          const base = el.id.replace(/\d+$/, '');
          el.id = base + cardId;
          el.name = base + cardId;
        });
      });
    }
  </script>

  <!-- Flight Details Fetch Script - JSON generation -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {

      // Add connecting flight in the future
      let departureFlightsJSON = {
        departure: {},
        returning: {},
      };

      // --- Log full JSON ---
      const updateAndLogCombinedJSON = () => {

        departureFlightsJSON.departure = {
          flightNumber: document.getElementById('departure1Flight')?.value || '',
          flightDate: document.getElementById('departure1Date')?.value || '',
          origin: document.getElementById('departure1Origin')?.value || '',
          destination: document.getElementById('departure1Destination')?.value || '',
          departureTime: document.getElementById('departure1DepartureTime')?.value || '',
          arrivalTime: document.getElementById('departure1ArrivalTime')?.value || '',
        };

        departureFlightsJSON.returning = {
          flightNumber: document.getElementById('departure2Flight')?.value || '',
          flightDate: document.getElementById('departure2Date')?.value || '',
          origin: document.getElementById('departure2Origin')?.value || '',
          destination: document.getElementById('departure2Destination')?.value || '',
          departureTime: document.getElementById('departure2DepartureTime')?.value || '',
          arrivalTime: document.getElementById('departure2ArrivalTime')?.value || '',
        };

        console.log("Combined Departure Flights JSON:\n", JSON.stringify(departureFlightsJSON, null, 2));
      };

      // --- Populate Departure 1 Flight Select ---
      function populateDeparture1FlightSelect() {
        fetch('../Employee Section/functions/fetchScripts/getFlightCode.php')
          .then(res => res.json())
          .then(flightCodes => {
            const select = document.getElementById('departure1Flight');
            select.innerHTML = '<option selected disabled>Select Flight</option>';
            flightCodes.forEach(code => {
              const option = document.createElement('option');
              option.value = code;
              option.textContent = code;
              select.appendChild(option);
            });
          });
      }

      // --- Populate Departure 2 Flight Select (single option from returnFlightCode) ---
      function populateDeparture2FlightSelect(returnFlightCode) {
        const select = document.getElementById('departure2Flight');
        select.innerHTML = '';

        if (returnFlightCode) {
          const option = document.createElement('option');
          option.value = returnFlightCode;
          option.textContent = returnFlightCode;
          select.appendChild(option);
          select.disabled = false;
        } else {
          select.innerHTML = '<option selected disabled>No Return Flight Available</option>';
          select.disabled = true;
        }
      }

      // --- Autofill Fields with fetched data ---
      function autofillDepartureFields(num, data, isReturn = false) {
        document.getElementById(`departure${num}Origin`).value = isReturn ? (data.returnOrigin || '') : (data.origin || '');
        document.getElementById(`departure${num}DepartureTime`).value = isReturn ? formatTime(data.returnDepartureTime) : formatTime(data.flightDepartureTime);
        document.getElementById(`departure${num}ArrivalTime`).value = isReturn ? formatTime(data.returnArrivalTime) : formatTime(data.flightArrivalTime);

        // Optional: auto-fill destination (if field exists)
        if (document.getElementById(`departure${num}Destination`)) {
          document.getElementById(`departure${num}Destination`).value = isReturn ? (data.returnDestination || '') : (data.destination || '');
        }

        updateAndLogCombinedJSON();
      }

      function formatTime(timeStr) {
        if (!timeStr) return '';
        const [hours, minutes] = timeStr.split(':');
        return `${hours}:${minutes}`;
      }

      // --- When Departure 1 Flight is selected ---
      document.getElementById('departure1Flight').addEventListener('change', function () {
        const flightCode = this.value;
        if (!flightCode) return;

        fetch(`../Employee Section/functions/fetchScripts/getFlightDetails.php?flightCode=${encodeURIComponent(flightCode)}`)
          .then(res => res.json())
          .then(data => {
            autofillDepartureFields(1, data, false);

            const returnFlightCode = data.returnFlightCode;
            populateDeparture2FlightSelect(returnFlightCode);

            // Pre-fill Departure 2 if available
            if (returnFlightCode) {
              autofillDepartureFields(2, data, true);
            }
          });
      });

      // --- When Departure 2 Flight is selected ---
      document.getElementById('departure2Flight').addEventListener('change', function () {
        const flightCode = this.value;
        if (!flightCode) return;

        fetch(`../Employee Section/functions/fetchScripts/getFlightDetails.php?flightCode=${encodeURIComponent(flightCode)}`)
          .then(res => res.json())
          .then(data => {
            autofillDepartureFields(2, data, true);
          });
      });

      // --- Monitor all related fields for real-time JSON updates ---
      ['departure1Origin', 'departure1Destination', 'departure1DepartureTime', 'departure1ArrivalTime',
        'departure2Flight', 'departure2Origin', 'departure2Destination', 'departure2DepartureTime', 'departure2ArrivalTime'
      ].forEach(id => {
        const field = document.getElementById(id);
        if (field) {
          field.addEventListener('input', updateAndLogCombinedJSON);
          field.addEventListener('change', updateAndLogCombinedJSON);
        }
      });

      // --- Initialize ---
      populateDeparture1FlightSelect();
      document.getElementById('departure2Flight').disabled = true;
    });
  </script>

  <!-- Air Details and Guide Meeting - JSON generation Script -->
  <script>
    function getAirScheduleDetailsWithGuideMeeting() {
      // Utility: Add minutes to a date object
      const addMinutes = (date, minutes) => new Date(date.getTime() + minutes * 60000);

      // Utility: Parse time string "HH:mm" to a Date object
      const parseTimeStringToDate = (timeString) => {
        const [hours, minutes] = timeString.split(':').map(Number);
        const now = new Date();
        now.setHours(hours);
        now.setMinutes(minutes);
        now.setSeconds(0);
        now.setMilliseconds(0);
        return now;
      };

      // Guide Meeting Info
      const arrivalTimeStartValue = document.getElementById('departure1ArrivalTime').value;
      const currentTime = parseTimeStringToDate(arrivalTimeStartValue);
      const updatedTime = addMinutes(currentTime, 15);

      const formattedTime = `${updatedTime.getHours().toString().padStart(2, '0')}:${updatedTime.getMinutes().toString().padStart(2, '0')}`;
      const selectedPlace = document.getElementById('departure1Destination').value;

      const placeOptions = {
        'ICN': 'Incheon Airport (Terminal 1)',
        'Other': 'Custom Place'
      };

      const guideMeetingPlace = placeOptions[selectedPlace] || 'Custom Place';

      // Construct unified structure
      const airScheduleDetails = {
        departure1: {
          flightDate: document.getElementById("departure1Date").value,
          flightNumber: document.getElementById("departure1Flight").value,
          origin: document.getElementById("departure1Origin").value,
          destination: document.getElementById("departure1Destination").value,
          departureTime: document.getElementById("departure1DepartureTime").value,
          arrivalTime: document.getElementById("departure1ArrivalTime").value
        },
        departure2: {
          flightDate: document.getElementById("departure2Date").value,
          flightNumber: document.getElementById("departure2Flight").value,
          origin: document.getElementById("departure2Origin").value,
          destination: document.getElementById("departure2Destination").value,
          departureTime: document.getElementById("departure2DepartureTime").value,
          arrivalTime: document.getElementById("departure2ArrivalTime").value
        },
        guideMeeting: {
          guideId: document.getElementById("guideSelect").value,
          date: document.getElementById("departure1Date").value,
          time: formattedTime,
          place: guideMeetingPlace
        }
      };


      console.log("Air Schedule with Guide Meeting: \n", JSON.stringify(airScheduleDetails, null, 2));

      return airScheduleDetails;
    }
  </script>

  <!-- Includes Section Functions and JSON generation Script -->
  <script>
    let includeCount = 0;
    const maxIncludes = 4;

    // Function to get the selected includes from all rows
    function getSelectedIncludes() {
      const selectedValues = [];
      const rows = document.querySelectorAll('.include-row');

      rows.forEach(row => {
        const select = row.querySelector('select');
        const customInput = row.querySelector('.custom-include-input');
        if (select.value === "others") {
          selectedValues.push({ id: select.id, value: customInput.value.trim() });
        } else {
          selectedValues.push({ id: select.id, value: select.value });
        }
      });

      return selectedValues;
    }

    // Function to update disabled options for selects based on selected values
    function updateDisabledIncludeOptions() {
      const selectedValues = getSelectedIncludes();
      const selects = document.querySelectorAll('.include-row select');

      selects.forEach(currentSelect => {
        const options = currentSelect.querySelectorAll('option');

        options.forEach(option => {
          if (
            option.value !== currentSelect.value &&
            selectedValues.some(item => item.value === option.value) &&
            option.value !== "" &&
            option.value !== "others" &&
            option.value !== "0"
          ) {
            option.disabled = true;
          } else {
            option.disabled = false;
          }
        });
      });
    }


    // Function to update the includes data object after each change
    function updateIncludesData() {
      const includeRows = document.querySelectorAll('.include-row');
      const includesData = {};
      const structuredIncludesList = [];

      includeRows.forEach((row, index) => {
        const includeIndex = index + 1;
        const select = row.querySelector('select.include-select');
        const customInput = row.querySelector('.custom-include-input');
        const selectedValue = select.value;

        if (!selectedValue) return;

        const selectedLabel = select.options[select.selectedIndex]?.text || "";
        const customText = customInput.value.trim();

        let finalLabel = selectedLabel;

        if (selectedValue === "1") {
          // ID 1 is treated as "Others"
          if (!customText) return; // Skip if custom input is empty
          finalLabel = customText;
        }

        // Keyed version (for internal reference, if needed)
        includesData[`includes${includeIndex}`] = {
          value: selectedValue,
          label: finalLabel,
          custom: selectedValue === "1" ? customText : ""
        };

        // Array version (structured for AJAX/DB)
        structuredIncludesList.push({
          includeItemId: selectedValue,
          includeOptionItem: finalLabel,
          custom: selectedValue === "1" ? customText : ""
        });
      });

      // console.log("✅ Includes Data:", JSON.stringify(includesData, null, 2));
      console.log("📦 Structured Includes List:", JSON.stringify(structuredIncludesList, null, 2));

      return structuredIncludesList;
    }






    // Function to add a new include row
    function addInclude() {
      if (includeCount >= maxIncludes) return;

      includeCount++;
      const includesContainer = document.getElementById('includesContainer');
      const isFirst = includeCount === 1;

      const newRow = document.createElement('div');
      newRow.className = 'row include-row align-items-start mb-3';
      newRow.setAttribute('data-index', includeCount);

      // Build the base structure
      newRow.innerHTML = `
        <div class="col-md-12">
          <div class="label-container d-flex justify-content-between align-items-center">
            <label for="includesSelect${includeCount}" class="form-label">Includes ${includeCount}:</label>
            <button type="button" class="btn btn-sm btn-danger remove-include" title="Remove">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
          <div class="content-container">
            <select class="form-select include-select" id="includesSelect${includeCount}" name="includesSelect${includeCount}">
              <option value="" selected disabled>Select Includes</option>
            </select>
            <input type="text" class="form-control custom-include-input d-none mt-2" placeholder="Please specify..." />
          </div>
        </div>
      `;

      includesContainer.appendChild(newRow);

      const selectEl = newRow.querySelector('select');
      const customInput = newRow.querySelector('.custom-include-input');
      const removeBtn = newRow.querySelector('.remove-include');

      // Fetch options from backend
      fetch('../Employee Section/functions/fetchScripts/getIncludeOptions.php')
        .then(res => res.json())
        .then(options => {
          options.forEach(opt => {
            const option = document.createElement('option');
            option.value = opt.includesId;
            option.textContent = opt.itemName;
            selectEl.appendChild(option);
          });

          // Add the "Others" option at the end
          const othersOption = document.createElement('option');
          othersOption.value = "1";
          othersOption.textContent = "Others";
          selectEl.appendChild(othersOption);
        });

      selectEl.addEventListener('change', () => {
        if (selectEl.value === "1") {
          customInput.classList.remove("d-none");
          customInput.focus();
        } else {
          customInput.classList.add("d-none");
          customInput.value = ""; // clear custom input if not "Others"
        }

        updateDisabledIncludeOptions();
        updateIncludesData();
      });

      customInput.addEventListener('input', () => {
        updateIncludesData();
      });


      if (removeBtn) {
        removeBtn.addEventListener('click', () => {
          const rowIndex = parseInt(newRow.getAttribute('data-index'));
          if (rowIndex === 1) {
            selectEl.value = "";
            customInput.value = "";
            customInput.classList.add("d-none");
          } else {
            newRow.remove();
            includeCount--;
            updateIncludeLabels();
          }

          updateDisabledIncludeOptions();
          updateIncludesData();
        });
      }

      updateDisabledIncludeOptions();
    }

    // Function to update include labels and ids after removing an include
    function updateIncludeLabels() {
      const rows = document.querySelectorAll('.include-row');
      rows.forEach((row, index) => {
        const label = row.querySelector('label');
        const select = row.querySelector('select');
        const number = index + 1;
        row.setAttribute('data-index', number);
        label.setAttribute('for', `includesSelect${number}`);
        label.textContent = `Includes ${number}:`;
        select.setAttribute('id', `includesSelect${number}`);
        select.setAttribute('name', `includesSelect${number}`);
      });
    }

    // Initialize the section with one include field on page load
    function initIncludesSection() {
      addInclude();  // Automatically add the first include row on page load
    }

    // Event listeners
    document.getElementById('addIncludeBtn').addEventListener('click', addInclude);
    window.addEventListener('DOMContentLoaded', initIncludesSection);
  </script>

  <!-- Excludes Section Functions and JSON generation Script -->
  <script>
    let excludeCount = 0;
    const maxExcludes = 4;

    // Function to get the selected excludes from all rows
    function getSelectedExcludes() {
      const selectedValues = [];
      const rows = document.querySelectorAll('.exclude-row');

      rows.forEach(row => {
        const select = row.querySelector('select');
        const customInput = row.querySelector('.custom-exclude-input');
        if (select.value === "others") {
          selectedValues.push({ id: select.id, value: customInput.value.trim() });
        } else {
          selectedValues.push({ id: select.id, value: select.value });
        }
      });

      return selectedValues;
    }

    // Function to update disabled options for excludes based on selected values
    function updateDisabledExcludeOptions() {
      const selectedValues = getSelectedExcludes();
      const selects = document.querySelectorAll('.exclude-row select');

      selects.forEach(currentSelect => {
        const options = currentSelect.querySelectorAll('option');

        options.forEach(option => {
          if (
            option.value !== currentSelect.value &&
            selectedValues.some(item => item.value === option.value) &&
            option.value !== "" &&
            option.value !== "others" &&
            option.value !== "0"
          ) {
            option.disabled = true;
          } else {
            option.disabled = false;
          }
        });
      });
    }

    // Function to update exclude labels and IDs
    function updateExcludeLabels() {
      const rows = document.querySelectorAll('.exclude-row');
      rows.forEach((row, index) => {
        const label = row.querySelector('label');
        const select = row.querySelector('select');
        const number = index + 1;
        row.setAttribute('data-index', number);
        label.setAttribute('for', `excludesSelect${number}`);
        label.textContent = `Excludes ${number}:`;
        select.setAttribute('id', `excludesSelect${number}`);
        select.setAttribute('name', `excludesSelect${number}`);
      });
    }


    function updateExcludesData() {
      const excludeRows = document.querySelectorAll('.exclude-row');
      const excludesData = {};
      const structuredExcludesList = [];

      excludeRows.forEach((row, index) => {
        const excludeIndex = index + 1;
        const select = row.querySelector('select.exclude-select');
        const customInput = row.querySelector('.custom-exclude-input');
        const selectedValue = select.value;

        if (!selectedValue) return;

        const selectedLabel = select.options[select.selectedIndex]?.text || "";
        const customText = customInput.value.trim();

        let finalLabel = selectedLabel;

        if (selectedValue === "1") {
          // ID 1 is treated as "Others"
          if (!customText) return; // Skip if custom input is empty
          finalLabel = customText;
        }

        // Keyed version
        excludesData[`excludes${excludeIndex}`] = {
          value: selectedValue,
          label: finalLabel,
          custom: selectedValue === "1" ? customText : ""
        };

        // Array version (structured for AJAX/DB)
        structuredExcludesList.push({
          excludeItemId: selectedValue,
          excludeOptionItem: finalLabel,
          custom: selectedValue === "1" ? customText : ""
        });
      });

      // console.log("✅ Excludes Data:", JSON.stringify(excludesData, null, 2));
      console.log("📦 Structured Excludes List:", JSON.stringify(structuredExcludesList, null, 2));

      // structuredExcludesList,
      // return excludesData;
      return structuredExcludesList;
    }



    function addExclude() {
      if (excludeCount >= maxExcludes) return;

      excludeCount++;
      const excludesContainer = document.getElementById('excludesContainer');
      const isFirst = excludeCount === 1;

      const newRow = document.createElement('div');
      newRow.className = 'row exclude-row align-items-start mb-3';
      newRow.setAttribute('data-index', excludeCount);

      // Build the base structure with empty select
      newRow.innerHTML = `
        <div class="col-md-12">
          <div class="label-container d-flex justify-content-between align-items-center">
            <label for="excludesSelect${excludeCount}" class="form-label">Excludes ${excludeCount}:</label>
            <button type="button" class="btn btn-sm btn-danger remove-exclude" title="Remove">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
          <div class="content-container">
            <select class="form-select exclude-select" id="excludesSelect${excludeCount}" name="excludesSelect${excludeCount}">
              <option value="" selected disabled>Select Exclude</option>
            </select>
            <input type="text" class="form-control custom-exclude-input d-none mt-2" placeholder="Please specify..." />
          </div>
        </div>
      `;

      excludesContainer.appendChild(newRow);

      const selectEl = newRow.querySelector('select');
      const customInput = newRow.querySelector('.custom-exclude-input');
      const removeBtn = newRow.querySelector('.remove-exclude');

      // Fetch and populate exclude options
      fetch('../Employee Section/functions/fetchScripts/getExcludeOptions.php')
        .then(res => res.json())

        .then(options => {
          options.forEach(opt => {
            const option = document.createElement('option');
            option.value = opt.excludesId;
            option.textContent = opt.itemName;
            selectEl.appendChild(option);
          });

          const othersOption = document.createElement('option');
          othersOption.value = "1";
          othersOption.textContent = "Others";
          selectEl.appendChild(othersOption);
        });

      selectEl.addEventListener('change', () => {
        if (selectEl.value === "1") {
          customInput.classList.remove("d-none");
          customInput.focus();
        } else {
          customInput.classList.add("d-none");
        }

        updateDisabledExcludeOptions();
        updateExcludesData();
      });

      customInput.addEventListener('input', () => {
        updateExcludesData();
      });

      if (removeBtn) {
        removeBtn.addEventListener('click', () => {
          const rowIndex = parseInt(newRow.getAttribute('data-index'));
          if (rowIndex === 1) {
            selectEl.value = "";
            customInput.value = "";
            customInput.classList.add("d-none");
          } else {
            newRow.remove();
            excludeCount--;
            updateExcludeLabels();
          }

          updateDisabledExcludeOptions();
          updateExcludesData();
        });
      }

      updateDisabledExcludeOptions();
    }


    // Initialize the section with one exclude field on page load
    function initExcludesSection() {
      addExclude(); // Automatically add the first exclude row
    }

    window.addEventListener('DOMContentLoaded', () => {
      document.getElementById('addExcludeBtn').addEventListener('click', addExclude);
      initExcludesSection();
    });
  </script>




  <!-- Insertion Script -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const voucherForm = document.getElementById("voucherForm");
      const proceedBtn = document.getElementById("proceedButton");
      const modalElement = document.getElementById("templateNameModal");

      let proceedCallback = null; // Will hold the post-modal action

      if (voucherForm) {
        voucherForm.addEventListener("submit", (e) => {
          e.preventDefault(); // Prevent actual form submission
          console.log("📝 Form submission intercepted - showing modal");

          const modal = new bootstrap.Modal(modalElement);
          modal.show();

          // Set callback after modal confirmation
          proceedCallback = () => {
            proceedWithSubmission();
          };
        });
      }

      if (proceedBtn) {
        proceedBtn.addEventListener("click", () => {
          const templateNameInput = document.getElementById("templateName");
          const templateName = templateNameInput?.value.trim();

          if (!templateName) {
            alert("⚠️ Please enter a template name before proceeding.");
            templateNameInput?.focus();
            return;
          }

          // Store for reuse
          window.templateNameForVoucher = templateName;

          // Close modal
          const modalInstance = bootstrap.Modal.getInstance(modalElement);
          if (modalInstance) modalInstance.hide();

          // Continue with submission
          if (typeof proceedCallback === "function") {
            proceedCallback();
          }
        });
      }
    });

    function proceedWithSubmission() {
      const templateName = window.templateNameForVoucher?.trim();
      const submitButton = document.getElementById("submitTour");

      if (!templateName) {
        alert("❌ Missing template name.");
        return;
      }

      if (submitButton) submitButton.disabled = true;

      const voucherDetails = (typeof updateVoucherDetails === "function") ? updateVoucherDetails() : {};
      const cardsJSONData = (typeof generateCardsJSON === "function") ? generateCardsJSON() : {};
      const airScheduleDetails = (typeof getAirScheduleDetailsWithGuideMeeting === "function") ? getAirScheduleDetailsWithGuideMeeting() : {};
      const includesData = (typeof updateIncludesData === "function") ? updateIncludesData() : {};
      const excludesData = (typeof updateExcludesData === "function") ? updateExcludesData() : {};

      const voucherPayload = {
        templateName,
        voucherDetails,
        cardsJSONData,
        airScheduleDetails,
        includesData,
        excludesData
      };


      console.log("For Insertion:\n", JSON.stringify(voucherPayload, null, 2));

      $.ajax({
        url: "../Employee Section/functions/emp-saveVoucher.php",
        type: "POST",
        data: {
          voucherPayload: JSON.stringify(voucherPayload)
        },
        dataType: "json",
        success: (response) => {
          if (submitButton) submitButton.disabled = false;

          console.log("Server Response: \n", JSON.stringify(response, null, 2));

          if (response.status === "success") {
            alert("Voucher saved successfully. Generating template...");
            // window.location.href = "../Employee Section/emp-Table.php";

          } else {
            alert("Failed to save Voucher: \n" + (response.message || "Unknown error occurred."));
          }

        },

        error: (xhr, status, error) => {
          if (submitButton) submitButton.disabled = false;
          console.error("❌ AJAX Error:", error);
          console.error("📄 Response Text:\n", xhr.responseText);
          alert("❌ Server error. Please try again later.");
        }
      });
    }
  </script>



  </body>
</html>