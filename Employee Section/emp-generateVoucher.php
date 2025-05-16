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

        <!-- Voucher Details Card -->
        <div class="card">
          <div class="card-header bg-secondary">
            <h5>Voucher Details</h5>
          </div>

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

              <!-- Tour Type -->
              <div class="columns col-md-4">
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



            </div>

            <div class="row mt-3">
              <!-- Attachment -->
              <div class="columns col-md-4">
                <label for="voucherAttachment">Attachment <span class="text-danger">*</span></label>
                <select class="form-select" id="voucherAttachment" name="voucherAttachment" required>
                  <option value="" selected disabled>Select Attachment</option>
                  <option value="voucher">Voucher</option>
                  <option value="itinerary">Itinerary</option>
                  <option value="voucher_and_itinerary">Voucher and Itinerary</option>
                </select>
              </div>

              <!-- Tour Period -->
              <div class="columns col-md-4">
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

              <!-- Number of Pax -->
              <div class="columns col-md-4">
                <label for="voucherPaxCount">No. of Pax <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="voucherPaxCount" required>
              </div>
            </div>

            <div class="row">

              <div class="columns col-md-8">

                <div class="column-header">
                  <label for="flightDate">Guide
                    <span class="text-danger"> *</span>
                  </label>
                </div>

                <div class="form-group">
                  <select class="form-select" id="guideSelect" name="guideSelect" required>
                    <option selected disabled>Select Guide</option>
                    <?php
                    $sql = "SELECT id, fName, mName, lName FROM employee WHERE branch = 'Korea'";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $employeeId = htmlspecialchars($row['id']);
                        $fullName = htmlspecialchars(trim($row['fName'] . ' ' . $row['mName'] . ' ' . $row['lName']));
                        echo "<option value=\"$employeeId\">$fullName</option>";
                      }
                    } else {
                      echo "<option disabled>No guides available</option>";
                    }
                    ?>
                  </select>
                </div>



              </div>
            </div>

          </div>
        </div>

        <!-- Date and Hotels Title Card -->
        <div class="card">
          <div
            class="card-header bg-secondary card-title first-wrapper d-flex justify-content-between align-items-center text-white">
            <h5 class="mb-0">Date & Hotels</h5>
            <button type="button" class="btn btn-success fw-bold" onclick="addCard()">
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
                  <h6>Departure #1</h6>
                </div>
              </div>

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

              <div class="columns col-md-2">
                <div class="column-header">
                  <label for="departure1Flight">Flight <span class="text-danger">*</span></label>
                </div>
                <div class="form-group">
                  <select class="form-select" id="departure1Flight" name="departure1Flight" required>
                    <option selected disabled>Select Flight</option>
                    <option value="KE123">KE123</option>
                    <option value="OZ456">OZ456</option>
                    <option value="JL789">JL789</option>
                  </select>
                </div>
              </div>

              <div class="columns col-md-4">
                <div class="column-header">
                  <label>Origin - Destination <span class="text-danger">*</span></label>
                </div>
                <div class="datepicker-wrapper d-flex align-items-center">
                  <div class="form-group">
                    <select class="form-select" id="departure1Origin" name="departure1Origin" required>
                      <option selected disabled>Origin</option>
                      <option value="MNL">Manila</option>
                      <option value="ICN">Incheon</option>
                    </select>
                  </div>
                  <div class="dash-separator px-2">→</div>
                  <div class="form-group">
                    <select class="form-select" id="departure1Destination" name="departure1Destination" required>
                      <option selected disabled>Destination</option>
                      <option value="MNL">Manila</option>
                      <option value="ICN">Incheon</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="columns col-md-4">
                <div class="column-header">
                  <label>Departure (to Origin) Time - Arrival (to Destination) Time <span
                      class="text-danger">*</span></label>
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

            <!-- Departure #2 -->
            <div class="row">
              <div class="main-header">
                <div class="header-container">
                  <h6>Departure #2</h6>
                </div>
              </div>

              <div class="columns col-md-2">
                <div class="column-header">
                  <label for="departure2Date">Date <span class="text-danger">*</span></label>
                </div>
                <div class="datepicker-wrapper">
                  <div class="form-group">
                    <div class="input-with-icon">
                      <input type="text" class="datepicker" id="departure2Date" name="departure2Date"
                        placeholder="Departure Date" readonly>
                      <i class="fas fa-calendar-alt calendar-icon"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="columns col-md-2">
                <div class="column-header">
                  <label for="departure2Flight">Flight <span class="text-danger">*</span></label>
                </div>
                <div class="form-group">
                  <select class="form-select" id="departure2Flight" name="departure2Flight" required>
                    <option selected disabled>Select Flight</option>
                    <option value="KE321">KE321</option>
                    <option value="OZ654">OZ654</option>
                    <option value="JL987">JL987</option>
                  </select>
                </div>
              </div>

              <div class="columns col-md-4">
                <div class="column-header">
                  <label>Origin - Destination <span class="text-danger">*</span></label>
                </div>
                <div class="datepicker-wrapper d-flex align-items-center">
                  <div class="form-group">
                    <select class="form-select" id="departure2Origin" name="departure2Origin" required>
                      <option selected disabled>Origin</option>
                      <option value="ICN">Incheon</option>
                      <option value="MNL">Manila</option>
                      <option value="CEB">Cebu</option>
                    </select>
                  </div>
                  <div class="dash-separator px-2">→</div>
                  <div class="form-group">
                    <select class="form-select" id="departure2Destination" name="departure2Destination" required>
                      <option selected disabled>Destination</option>
                      <option value="MNL">Manila</option>
                      <option value="ICN">Incheon</option>
                      <option value="NRT">Narita</option>
                      <option value="LAX">Los Angeles</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="columns col-md-4">
                <div class="column-header">
                  <label>Departure (to Origin) Time - Arrival (to Destination) Time <span
                      class="text-danger">*</span></label>
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
            <button id="addIncludeBtn" class="add-button btn btn-primary add-exclude-button">+</button>
          </div>

          <div class="card-body" id="includesContainer">
            <!-- JS will generate .row elements here directly -->
          </div>

        </div>

        <!-- Excludes -->
        <div class="card excludes-header-card">
          <div class="card-header bg-secondary card-title excludes-wrapper">
            <h5>Excludes</h5>
            <button id="addExcludeBtn" class="add-button btn btn-primary add-exclude-button">+</button>
            <!-- Add Exclude Button -->
          </div>

          <div class="card-body">
            <!-- Excludes Rows (Dynamically added) -->
            <div id="excludesContainer"></div>
          </div>

        </div>

      </div>

      <div class="form-footer">
        <button type="button" class="btn btn-primary" id="submitTour">Generate Voucher</button>
      </div>

    </div>

  </div>

  <!-- Modal -->
  <div class="modal fade" id="templateNameModal" tabindex="-1" aria-labelledby="templateNameModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="templateNameModalLabel">Enter Template Name</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" id="templateName" class="form-control" placeholder="Template Name">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="proceedWithSubmission()">Proceed</button>
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

  <!-- JSON Variables -->
  <script>
    let voucherDetails = {};  // Voucher Details (Details Card)
    let cardsJSONData = {};   // Date and Hotels
    let airScheduleDetails = {};      // Air Schedule
    let includesData = {};    // Includes
    let excludesData = {};    // Excludes
  </script>

  <!-- Voucher Details -->
  <script>
    function updateVoucherDetails() {
      voucherDetails = {
        to: document.getElementById("voucherTo").value,
        from: document.getElementById("voucherFrom").value,
        tour: document.getElementById("voucherTour").value,
        attachment: document.getElementById("voucherAttachment").value,
        periodStart: document.getElementById("voucherPeriodStart").value,
        periodEnd: document.getElementById("voucherPeriodEnd").value,
        paxCount: document.getElementById("voucherPaxCount").value,
        guide: document.getElementById("guideSelect").value
      };

      console.log("Voucher Details JSON:", JSON.stringify(voucherDetails, null, 2));
    }
  </script>

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
          date: document.getElementById("departure1Date").value,
          time: formattedTime,
          place: guideMeetingPlace
        }
      };

      console.log("Unified Air Schedule with Guide Meeting:", airScheduleDetails);
      return airScheduleDetails;
    }
  </script>



  <!-- Date and Hotel Section Functions and JSON generation Script -->
  <script>
    let cardCount = 0;
    const maxCards = 3;

    document.addEventListener("DOMContentLoaded", function () {
      // Add 2 cards by default on load
      addCard();
      addCard();
    });

    document.getElementById('addCardBtn').addEventListener('click', addCard);

    function addCard() {
      if (cardCount >= maxCards) {
        alert("You can only add a maximum of 3 cards.");
        return;
      }

      cardCount++;
      const container = document.getElementById('cardsContainer');
      const card = document.createElement('div');
      card.className = 'mb-4';
      card.setAttribute('data-card-id', cardCount);

      card.innerHTML = `
        <div class="col-12 mb-3 mt-3">
          <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
            <div>
              <h6 class="fw-semibold text-uppercase text-muted m-0">Date and Hotels #${cardCount}</h6>
            </div>
            <div>
              <button type="button" class="btn btn-sm text-white bg-danger border-0 px-2 py-1 remove-card-btn" title="Delete">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="row g-4 align-items-end">

          <!-- Date Range -->
          <div class="col-12 col-md-5">
            <label class="form-label">Date</label>
            <div class="d-flex gap-2 align-items-center">
              <div class="position-relative w-100">
                <input type="text" class="form-control datepicker" id="PeriodStartDate${cardCount}" placeholder="Start" readonly>
                <i class="fas fa-calendar-alt position-absolute text-muted" style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
              </div>
              <span class="mx-1 text-muted">→</span>
              <div class="position-relative w-100">
                <input type="text" class="form-control datepicker" id="PeriodEndDate${cardCount}" placeholder="End" readonly>
                <i class="fas fa-calendar-alt position-absolute text-muted" style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
              </div>
            </div>
          </div>

          <!-- No. of Nights -->
          <div class="col-6 col-md-2">
            <label for="nights${cardCount}" class="form-label">No. of Nights</label>
            <input type="text" class="form-control" id="nights${cardCount}" name="nights${cardCount}" value="">
          </div>

          <!-- City -->
          <div class="col-6 col-md-2">
            <label for="city${cardCount}" class="form-label">City</label>
            <select class="form-control" id="city${cardCount}" name="city${cardCount}">
              <option value="" selected disabled>Select City</option>
              <option value="New York">New York</option>
              <option value="Paris">Paris</option>
              <option value="Tokyo">Tokyo</option>
            </select>
          </div>

          <!-- Hotel -->
          <div class="col-12 col-md-3">
            <label for="hotel${cardCount}" class="form-label">Hotel</label>
            <select class="form-control" id="hotel${cardCount}" name="hotel${cardCount}">
              <option value="" selected disabled>Select Hotel</option>
              <option value="Hotel A">Hotel A</option>
              <option value="Hotel B">Hotel B</option>
              <option value="Hotel C">Hotel C</option>
            </select>
          </div>

        </div>
      `;

      container.appendChild(card);

      // Initialize flatpickr on the new date inputs
      flatpickr(`#PeriodStartDate${cardCount}`, { dateFormat: "Y-m-d" });
      flatpickr(`#PeriodEndDate${cardCount}`, { dateFormat: "Y-m-d" });

      // Attach delete button event
      card.querySelector('.remove-card-btn').addEventListener('click', () => {
        card.remove();
        cardCount--;
        updateCardHeaders();
        generateCardsJSON();
      });

      generateCardsJSON();
    }

    function updateCardHeaders() {
      const cards = document.querySelectorAll('#cardsContainer > .mb-4');
      cards.forEach((card, index) => {
        const header = card.querySelector('h6');
        if (header) {
          header.textContent = `Date and Hotels #${index + 1}`;
          card.setAttribute('data-card-id', index + 1);

          // Also update IDs of inputs/selects inside this card accordingly to keep consistent
          const startDate = card.querySelector(`#PeriodStartDate${index + 2}`) || card.querySelector(`#PeriodStartDate${index + 1}`);
          const endDate = card.querySelector(`#PeriodEndDate${index + 2}`) || card.querySelector(`#PeriodEndDate${index + 1}`);
          const nights = card.querySelector(`#nights${index + 2}`) || card.querySelector(`#nights${index + 1}`);
          const city = card.querySelector(`#city${index + 2}`) || card.querySelector(`#city${index + 1}`);
          const hotel = card.querySelector(`#hotel${index + 2}`) || card.querySelector(`#hotel${index + 1}`);

          // Update all input/select IDs and names inside the card accordingly
          card.querySelectorAll('input, select').forEach(input => {
            const baseId = input.id.replace(/[0-9]+$/, '');
            input.id = baseId + (index + 1);
            if (input.name) {
              input.name = baseId + (index + 1);
            }
          });
        }
      });
    }

    function generateCardsJSON() {
      const cards = document.querySelectorAll('#cardsContainer > .mb-4');
      const jsonData = {};

      cards.forEach((card, index) => {
        const cardId = index + 1; // use updated numbering

        const cardDetails = {
          startDate: document.getElementById(`PeriodStartDate${cardId}`)?.value || '',
          endDate: document.getElementById(`PeriodEndDate${cardId}`)?.value || '',
          nights: document.getElementById(`nights${cardId}`)?.value || '',
          city: document.getElementById(`city${cardId}`)?.value || '',
          hotel: document.getElementById(`hotel${cardId}`)?.value || ''
        };

        jsonData[`dateAndHotel${cardId}`] = cardDetails;
      });

      // You can use cardsJSONData later
      window.cardsJSONData = jsonData;
      // console.log(JSON.stringify(cardsJSONData, null, 2));
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
      // Reset object by reassigning a new empty object
      includesData = {};

      const rows = document.querySelectorAll('.include-row');

      rows.forEach((row, index) => {
        const includeIndex = index + 1;
        const select = row.querySelector('select');
        const customInput = row.querySelector('.custom-include-input');
        let value = "";

        if (select.value === "others") {
          value = customInput.value.trim();
        } else {
          value = select.value;
        }

        includesData[`includes${includeIndex}`] = { value: value };
      });

      console.log('Updated Includes Data (JSON):', JSON.stringify(includesData, null, 2));

      updateVoucherDetails();
    }

    // Function to add a new include row
    function addInclude() {
      if (includeCount >= maxIncludes) return;

      includeCount++;
      const includesContainer = document.getElementById('includesContainer');

      const newRow = document.createElement('div');
      newRow.className = 'row include-row align-items-start mb-3';
      newRow.setAttribute('data-index', includeCount);

      newRow.innerHTML = `
        <div class="col-md-12">
          <div class="label-container">
            <label for="includesSelect${includeCount}" class="form-label">Includes ${includeCount}:</label>
            <button type="button" class="btn btn-sm btn-danger remove-include" title="Remove">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
          <div class="content-container">
            <select class="form-select include-select" id="includesSelect${includeCount} required" name="includesSelect${includeCount}" required>
              <option value="" selected disabled>Select Includes</option>
              <option value="1">Hotel (4 nights with twin or triple sharing)</option>
              <option value="2">Meals (4 times Lunch, 4 times Dinner)</option>
              <option value="3">(Coach, Van), Admission as the itinerary, ENGLISH guide, etc.</option>
              <option value="4">Airport Pick-up and Drop-off</option>
              <option value="5">Souvenir Pack</option>
              <option value="6">Travel Insurance</option>
              <option value="others">Others</option>
              <option value="0"> — No Includes — </option>
            </select>
            <input type="text" class="form-control custom-include-input d-none mt-2" placeholder="Please specify..." />
          </div>
        </div>
      `;

      includesContainer.appendChild(newRow);

      const selectEl = newRow.querySelector('select');
      const customInput = newRow.querySelector('.custom-include-input');
      const removeBtn = newRow.querySelector('.remove-include');

      selectEl.addEventListener('change', () => {
        if (selectEl.value === "others") {
          customInput.classList.remove("d-none");
          customInput.focus();
        } else {
          customInput.classList.add("d-none");
        }

        updateDisabledIncludeOptions();
        updateIncludesData();
      });

      customInput.addEventListener('input', () => {
        updateIncludesData();
      });

      removeBtn.addEventListener('click', () => {
        newRow.remove();
        includeCount--;
        updateIncludeLabels();
        updateDisabledIncludeOptions();
        updateIncludesData();
      });

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
      excludesData = []; // This should be a global variable or accessible where you need it

      excludeRows.forEach(row => {
        const select = row.querySelector('select.exclude-select');
        const customInput = row.querySelector('.custom-exclude-input');
        const selectedValue = select.value;

        // Skip if no selection or explicitly "No Excludes"
        if (!selectedValue || selectedValue === "0") return;

        // Handle "others"
        if (selectedValue === "others") {
          const customText = customInput.value.trim();
          if (customText !== '') {
            excludesData.push({
              value: selectedValue,
              label: customText
            });
          }
        } else {
          // Push standard exclude option
          const selectedOption = select.options[select.selectedIndex];
          excludesData.push({
            value: selectedValue,
            label: selectedOption.text
          });
        }
      });
    }


    // Function to add a new exclude row
    function addExclude() {
      if (excludeCount >= maxExcludes) return;

      excludeCount++;
      const excludesContainer = document.getElementById('excludesContainer');

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
              <option value="1">Flight (Round trip flight tickets)</option>
              <option value="2">Visa Fees</option>
              <option value="3">Meals (Meals outside the package)</option>
              <option value="4">Personal Expenses</option>
              <option value="5">Optional Tours</option>
              <option value="others">Others</option>
              <option value="0">— No Excludes —</option>
            </select>
            <input type="text" class="form-control custom-exclude-input d-none mt-2" placeholder="Please specify..." />
          </div>
        </div>
      `;

      excludesContainer.appendChild(newRow);

      const selectEl = newRow.querySelector('select');
      const customInput = newRow.querySelector('.custom-exclude-input');
      const removeBtn = newRow.querySelector('.remove-exclude');

      selectEl.addEventListener('change', () => {
        if (selectEl.value === "others") {
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

      removeBtn.addEventListener('click', () => {
        newRow.remove();
        excludeCount--;
        updateExcludeLabels();
        updateDisabledExcludeOptions();
        updateExcludesData();
      });

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

  <script>
    // Wait for DOM to be ready
    document.addEventListener("DOMContentLoaded", function () {
      // When Submit Voucher button is clicked
      const submitBtn = document.getElementById("submitTour");
      if (submitBtn) {
        submitBtn.addEventListener("click", function () {
          const modal = new bootstrap.Modal(document.getElementById("templateNameModal"));
          modal.show();
        });
      }
    });

    // Function to handle actual submission
    function proceedWithSubmission() {
      const templateName = document.getElementById("templateName")?.value.trim();
      const submitButton = document.getElementById("submitTour");

      if (!templateName) {
        alert("⚠️ Please enter a template name before proceeding.");
        return;
      }

      // Disable to prevent double submit
      if (submitButton) submitButton.disabled = true;

      // Call your data collection functions if they exist
      if (typeof collectVoucherDetails === 'function') collectVoucherDetails();
      if (typeof generateCardsJSON === 'function') generateCardsJSON();
      if (typeof updateAirScheduleDetails === 'function') updateAirScheduleDetails();
      if (typeof updateIncludesData === 'function') updateIncludesData();
      if (typeof updateExcludesData === 'function') updateExcludesData();

      const voucherPayload = {
        templateName: templateName,
        voucherDetails: voucherDetails || {},
        airScheduleDetails: airScheduleDetails || {},
        cardsJSONData: cardsJSONData || {},
        includesData: includesData || {},
        excludesData: excludesData || {}
      };

      console.log("📦 Voucher Payload to be submitted:", voucherPayload);

      $.ajax({
        url: "../Employee Section/functions/emp-saveVoucher.php",
        type: "POST",
        data: {
          voucherPayload: JSON.stringify(voucherPayload)
        },
        dataType: "json",
        success: function (response) {
          if (submitButton) submitButton.disabled = false;

          if (response.status === "success") {
            alert("✅ Voucher saved successfully!");
            window.location.href = "../Employee Section/emp-vouchertable.php";
          } else {
            alert("❌ Failed to save itinerary:\n" + response.message);
          }
        },
        error: function (xhr, status, error) {
          if (submitButton) submitButton.disabled = false;
          console.error("❌ AJAX Error:", error);
          console.error("📄 Response Text:", xhr.responseText);
          alert("❌ A server error occurred while saving the itinerary.");
        }
      });

      // Hide modal
      const modalElement = document.getElementById("templateNameModal");
      const modalInstance = bootstrap.Modal.getInstance(modalElement);
      if (modalInstance) modalInstance.hide();
    }
  </script>




</body>

</html>