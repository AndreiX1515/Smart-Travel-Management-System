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

          <!-- General Info -->
          <div class="card">

            <div class="card-header bg-secondary">
              <h5>General Information</h5>
            </div>

            <div class="card-body">

              <div class="row align-items-center">

                <!-- Flight Dropdown -->
                <div class="col-md-6" id="flightSelectWrapper">
                  <label for="flightId" class="form-label">Select Flight <span class="text-danger">*</span></label>
                  <select class="form-select" id="flightId" name="flightId">
                    <option value="" disabled selected>Select Flight</option>

                    <?php
                    $query = "SELECT * FROM flight WHERE is_active = 1";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0):
                      while ($row = $result->fetch_assoc()):
                        $flightId = htmlspecialchars($row['flightId']);
                        $flightCode = htmlspecialchars($row['flightCode']);
                        $flightName = htmlspecialchars($row['flightName']);
                        $departureDate = htmlspecialchars($row['flightDepartureDate']);

                        // Encode the whole row as JSON and escape it for the HTML attribute
                        $flightDataJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                        ?>
                        <option value="<?= $flightId ?>" data-flight='<?= $flightDataJson ?>'>
                          <?= $flightCode ?> – <?= $flightName ?> (<?= $departureDate ?>)
                        </option>
                        <?php
                      endwhile;
                    else:
                      ?>
                      <option disabled>No active flights available</option>
                    <?php endif; ?>
                  </select>
                </div>

              </div>

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
                        echo "<option value='" . $row['branchId'] . "'>" . $row['branchName'] . "</option>";
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
                    $sql = "SELECT accountId AS guideAccountId, fName, mName, lName, contactNo, countryCode 
                            FROM employee 
                            WHERE isTourGuide = 1 
                            ORDER BY lName, fName ASC";
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
                    <select class="form-select" id="countryCode" name="countryCode" style="width: 80px;" disabled>
                      <option value="+63">+63</option>
                      <option value="+82">+82</option>
                    </select>
                    <input type="text" class="form-control ms-2" id="contactNumber" name="contactNumber"
                      placeholder="9***********" disabled>
                  </div>
                </div>

                <!-- Unified Script for Auto-Updating Contact -->
                <script>
                  document.addEventListener("DOMContentLoaded", function () {
                    const guideSelect = document.getElementById('guideSelect');
                    const contactInput = document.getElementById('contactNumber');
                    const codeSelect = document.getElementById('countryCode');

                    function updateGuideContact() {
                      const selected = guideSelect.options[guideSelect.selectedIndex];
                      const contact = selected.getAttribute('data-contact') || '';
                      const code = selected.getAttribute('data-code') || '+63';

                      // Temporarily enable to set value, then disable again
                      codeSelect.disabled = false;
                      contactInput.disabled = false;

                      codeSelect.value = code;
                      contactInput.value = contact;

                      codeSelect.disabled = true;
                      contactInput.disabled = true;
                    }

                    guideSelect.addEventListener('change', updateGuideContact);

                    // Prefill if already selected on page load
                    if (guideSelect.value) {
                      updateGuideContact();
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


            <?php
            // Fetch flights from DB where active
            $flightQuery = "SELECT flightCode, returnFlightCode FROM flight WHERE is_active = 1";
            $flightResult = $conn->query($flightQuery);

            $departureCodes = [];
            $returnCodes = [];

            if ($flightResult && $flightResult->num_rows > 0) {
              while ($row = $flightResult->fetch_assoc()) {
                if (!empty($row['flightCode'])) {
                  $departureCodes[] = htmlspecialchars($row['flightCode']);
                }
                if (!empty($row['returnFlightCode'])) {
                  $returnCodes[] = htmlspecialchars($row['returnFlightCode']);
                }
              }

              // Remove duplicates just in case
              $departureCodes = array_unique($departureCodes);
              $returnCodes = array_unique($returnCodes);
            }
            ?>


            <div class="card-body">

              <!-- Departure #1 -->
              <div class="row">
                <div class="main-header">
                  <div class="header-container">
                    <h6>Departure Flight</h6>
                  </div>
                </div>


                <div class="columns col-md-2">
                  <div class="column-header">
                    <label for="departure1Flight">Flight <span class="text-danger">*</span></label>
                  </div>
                  <div class="form-group">
                    <select class="form-select" id="departure1Flight" name="departure1Flight" required>
                      <option value="" selected disabled>Select Flight</option>
                      <?php foreach ($departureCodes as $code): ?>
                        <option value="<?= $code ?>"><?= $code ?></option>
                      <?php endforeach; ?>
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
                      <?php foreach ($returnCodes as $code): ?>
                        <option value="<?= $code ?>"><?= $code ?></option>
                      <?php endforeach; ?>
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

      // Initialize all datepickers with custom configuration (ALLOW PAST DATES)
      initFlatpickr("input.datepicker", {
        dateFormat: "Y-m-d",
        // minDate: "today", ← ❌ Remove or comment out to allow past dates
        disableMobile: true,
        appendTo: document.body,
        position: "auto",
        zIndex: 9999,
        onOpen: function () {
          const calendar = document.querySelector('.flatpickr-calendar');
          if (calendar) {
            calendar.style.position = 'absolute';
            const inputRect = this.input.getBoundingClientRect();
            calendar.style.top = `${inputRect.bottom + window.scrollY + 8}px`;
          }
        }
      });

      // Initialize all timepickers with 24-hour format
      initFlatpickr("input.timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        disableMobile: true,
        appendTo: document.body,
        position: "auto",
        zIndex: 9999,
        onOpen: function () {
          const timepicker = document.querySelector('.flatpickr-calendar');
          if (timepicker) {
            timepicker.style.position = 'absolute';
            const inputRect = this.input.getBoundingClientRect();
            timepicker.style.top = `${inputRect.bottom + window.scrollY + 8}px`;
          }
        }
      });

    });
  </script>

  <!-- JavaScript to Initialize Timepicker
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
  </script> -->


  <!-- JS for Flight Selection -->
  <script>
    document.getElementById('flightId').addEventListener('change', function () {
      const selectedOption = this.options[this.selectedIndex];

      if (!selectedOption || !selectedOption.value) {
        console.warn("⚠️ No flight selected.");
        return;
      }

      const selectedFlightId = selectedOption.value;
      console.log("Selected Flight ID:", selectedFlightId);

      // ✅ Parse JSON from custom attribute (not using data-*)
      const flightJson = selectedOption.getAttribute('data-flight');

      const flightDetails = JSON.parse(flightJson);

      // ✅ Console log in pretty JSON format
      console.log("🛫 Selected Flight Data (JSON):\n", JSON.stringify(flightDetails, null, 2));

      // Call your form-populating logic
      fillFlightFields(flightDetails);
    });

    function fillFlightFields(flight) {
      if (!flight) {
        console.warn("⚠️ No flight data provided.");
        return;
      }

      console.log("📝 Populating form with flight data:", flight);

      // Departure Flight
      document.getElementById("departure1Flight").value = flight.flightCode || "";
      document.getElementById("departure1Date").value = flight.flightDepartureDate || "";
      document.getElementById("departure1Origin").value = flight.origin === "Manila" ? "MNL" : "ICN";
      document.getElementById("departure1Destination").value = flight.origin === "Manila" ? "ICN" : "MNL";
      document.getElementById("departure1DepartureTime").value = flight.flightDepartureTime || "";
      document.getElementById("departure1ArrivalTime").value = flight.flightArrivalTime || "";

      // Returning Flight
      document.getElementById("departure2Flight").value = flight.returnFlightCode || "";
      document.getElementById("departure2Date").value = flight.returnDepartureDate || "";
      document.getElementById("departure2Origin").value = flight.origin === "Manila" ? "ICN" : "MNL";
      document.getElementById("departure2Destination").value = flight.origin === "Manila" ? "MNL" : "ICN";
      document.getElementById("departure2DepartureTime").value = flight.returnDepartureTime || "";
      document.getElementById("departure2ArrivalTime").value = flight.returnArrivalTime || "";
    }
  </script>

  <!-- JS for Itinerary Toggle (with fetch + JSON console.log) -->

  <script>
    let isToggled = false;

    document.addEventListener("DOMContentLoaded", function () {
      const toggle = document.getElementById("toggleItinerarySelect");
      const itinerarySelectWrapper = document.getElementById("itinerarySelectWrapper");
      const itinerarySelect = document.getElementById("itineraryId");

      toggle.addEventListener("change", function () {
        if (this.checked) {
          itinerarySelectWrapper.style.display = "block";
          itinerarySelect.disabled = false;
          itinerarySelect.required = true;
          disableItineraryRequirement(true);
          isToggled = true;
        } else {
          itinerarySelectWrapper.style.display = "none";
          itinerarySelect.disabled = true;
          itinerarySelect.required = false;
          itinerarySelect.value = "";
          disableItineraryRequirement(false);
          isToggled = false;
        }
      });

      itinerarySelect.addEventListener("change", function () {
        const itineraryId = this.value;
        if (!itineraryId) {
          console.warn("⚠️ No itinerary ID selected.");
          return;
        }

        console.log("Selected Itinerary ID:", itineraryId);

        fetch(`../Employee Section/functions/get-itinerary.php?id=${itineraryId}`)
          .then(response => {
            if (!response.ok) {
              throw new Error(`Server responded with status ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            if (data.error) {
              console.error("Server returned an error:", data.error);
              return;
            }

            // ✅ Pretty JSON log
            console.log("📋 Fetched Itinerary Data (JSON):\n", JSON.stringify(data, null, 2));

            populateItineraryForm(data);
          })
          .catch(err => {
            console.error("❌ Fetch failed:", err);
          });
      });

      function disableItineraryRequirement(disable) {
        document.querySelectorAll(".itinerary-select").forEach(sel => {
          if (disable) sel.removeAttribute("required");
          else sel.setAttribute("required", "required");
        });
      }

      function populateItineraryForm(data) {
        const safeSet = (id, value) => {
          const el = document.getElementById(id);
          if (el) el.value = value || "";
          else console.warn(`⚠️ Element #${id} not found`);
        };

        safeSet("voucherPeriodStart", data.periodStart);
        safeSet("voucherPeriodEnd", data.periodEnd);
        safeSet("guideSelect", data.guideId);
        safeSet("countryCode", data.countryCode);
        safeSet("contactNumber", data.contactNumber);

        const voucherSelect = document.getElementById("voucherTour");
        if (voucherSelect) {
          const match = [...voucherSelect.options].find(opt => opt.value == data.packageId);
          voucherSelect.value = match ? match.value : "";
        }

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
    let guideMeeting = {};
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
        toId: document.getElementById("voucherTo").value,
        from: document.getElementById("voucherFrom").value,
        tour: document.getElementById("voucherTour").value,
        attachment: attachmentValue,
        itineraryId: isToggled ? selectedItineraryId : null,
        flightId: document.getElementById("flightId").value,
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
              ${cities.map(city => `<option value="${city.areaId}">${city.areaName}</option>`).join('')}
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

      // Initialize the nights input to empty
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
        // minDate: "today",
        disableMobile: true,
        onChange: function (selectedDates, dateStr) {
          if (hiddenStart) hiddenStart.value = dateStr;
          calculateNights();
        }
      });

      flatpickr(endInput, {
        dateFormat: "Y-m-d",
        // minDate: "today",
        disableMobile: true,
        onChange: function (selectedDates, dateStr) {
          if (hiddenEnd) hiddenEnd.value = dateStr;
          calculateNights();
        }
      });



      // City -> hotel Dynamic Data
      const citySelect = card.querySelector('.city-select');
      const hotelSelect = card.querySelector('.hotel-select');

      // Event listener: on city (areaId) change
      citySelect.addEventListener('change', (e) => {
        const selectedAreaId = parseInt(e.target.value);
        populateHotelsForCity(hotelSelect, selectedAreaId);
      });

      // Event listener: on hotel change
      hotelSelect.addEventListener('change', () => {
        cardsJSONData = generateCardsJSON();
        console.log("Date and Hotels:", JSON.stringify(cardsJSONData, null, 2));
      });

      // console.log("Voucher Details:", JSON.stringify(voucherDetails, null, 2));

      // Helper: Populate hotels based on selected areaId
      function populateHotelsForCity(hotelSelect, areaId) {
        hotelSelect.innerHTML = `<option value="" disabled selected>Select Hotel</option>`;

        if (!areaId || isNaN(areaId)) {
          hotelSelect.disabled = true;
          return;
        }

        const matches = hotelsList.filter(hotel => parseInt(hotel.areaId) === areaId);
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



      // Delete Card Button
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

      // Get values from DOM
      const departure1Date = document.getElementById("departure1Date").value;
      const departure1Flight = document.getElementById("departure1Flight").value;
      const departure1Origin = document.getElementById("departure1Origin").value;
      const departure1Destination = document.getElementById("departure1Destination").value;
      const departure1DepartureTime = document.getElementById("departure1DepartureTime").value;
      const departure1ArrivalTime = document.getElementById("departure1ArrivalTime").value;

      const departure2Date = document.getElementById("departure2Date").value;
      const departure2Flight = document.getElementById("departure2Flight").value;
      const departure2Origin = document.getElementById("departure2Origin").value;
      const departure2Destination = document.getElementById("departure2Destination").value;
      const departure2DepartureTime = document.getElementById("departure2DepartureTime").value;
      const departure2ArrivalTime = document.getElementById("departure2ArrivalTime").value;

      const guideId = document.getElementById("guideSelect").value;

      // Compute Guide Meeting Time (15 minutes after arrival)
      const currentTime = parseTimeStringToDate(departure1ArrivalTime);
      const updatedTime = addMinutes(currentTime, 15);
      const formattedTime = `${updatedTime.getHours().toString().padStart(2, '0')}:${updatedTime.getMinutes().toString().padStart(2, '0')}`;

      // Determine meeting place
      const placeOptions = {
        'ICN': 'Incheon Airport (Terminal 1)',
        'Other': 'Custom Place'
      };
      
      const guideMeetingPlace = placeOptions[departure1Destination] || 'Custom Place';

      // ✈️ Air Schedule (only flights)
      const airScheduleDetails = {
        departureflight: {
          flightDate: departure1Date,
          flightNumber: departure1Flight,
          origin: departure1Origin,
          destination: departure1Destination,
          departureTime: departure1DepartureTime,
          arrivalTime: departure1ArrivalTime
        },
        returningflight: {
          flightDate: departure2Date,
          flightNumber: departure2Flight,
          origin: departure2Origin,
          destination: departure2Destination,
          departureTime: departure2DepartureTime,
          arrivalTime: departure2ArrivalTime
        }
      };

      // 👨‍✈️ Guide Meeting (separate object)
      const guideMeeting = {
        guideId: guideId,
        date: departure1Date,
        time: formattedTime,
        place: guideMeetingPlace
      };

      // Log output
      console.log("✈️ Air Schedule:\n", JSON.stringify(airScheduleDetails, null, 2));
      console.log("👨‍✈️ Guide Meeting:\n", JSON.stringify(guideMeeting, null, 2));

      // Return both separately
      return {
        airScheduleDetails,
        guideMeeting
      };
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
        });

      selectEl.addEventListener('change', () => {
        const selectedText = selectEl.options[selectEl.selectedIndex]?.text?.toLowerCase();
        if (selectedText === 'others') {
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
        });

      selectEl.addEventListener('change', () => {
        const selectedText = selectEl.options[selectEl.selectedIndex]?.text?.toLowerCase();
        if (selectedText === 'others') {
          customInput.classList.remove("d-none");
          customInput.focus();
        } else {
          customInput.classList.add("d-none");
          customInput.value = '';
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
      const airAndGuide = (typeof getAirScheduleDetailsWithGuideMeeting === "function") ? getAirScheduleDetailsWithGuideMeeting() : {};
      const includesData = (typeof updateIncludesData === "function") ? updateIncludesData() : {};
      const excludesData = (typeof updateExcludesData === "function") ? updateExcludesData() : {};

      // Separate the two parts returned from getAirScheduleDetailsWithGuideMeeting
      const airScheduleDetails = airAndGuide.airScheduleDetails || {};
      const guideMeeting = airAndGuide.guideMeeting || {};

      const voucherPayload = {
        templateName,
        voucherDetails,
        cardsJSONData,
        airScheduleDetails,
        guideMeeting,
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