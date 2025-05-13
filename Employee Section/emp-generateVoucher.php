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
              <div class="columns col-md-3">
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
              <div class="columns col-md-2">
                <label for="voucherPaxCount">No. of Pax <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="voucherPaxCount" required>
              </div>
            </div>
          </div>
        </div>

        <!-- Tour Condition Title Card -->
        <div class="card">
          <div class="card-header bg-primary card-title">
            <h5>Tour Condition</h5>
          </div>
        </div>

        <div class="card">
          <div
            class="card-header bg-secondary card-title first-wrapper d-flex justify-content-between align-items-center text-white">
            <h5 class="mb-0">Date & Hotels</h5>
            <button type="button" class="btn btn-success fw-bold" onclick="addCard()">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>

        <!-- Container for Date & Hotel Cards -->
        <div id="cardsContainer"></div>

        <!-- Other Information Title Card -->
        <div class="card">
          <div class="card-header bg-secondary">
            <h5>Other Informations</h5>
          </div>
        </div>

        <!-- Select Guide -->
        <div class="card">
          <div class="card-body">
            <!-- Guides Row -->
            <div class="row">
              <div class="columns col-md-6">
                <div class="column-header">
                  <label for="flightDate">Guide
                    <span class="text-danger"> *</span>
                  </label>
                </div>

                <div class="form-group">
                  <select class="form-select" id="packageSelect" name="packageSelect" required>
                    <option selected disabled>Select Guide</option>
                    <option value="John Kim<">John Kim</option>
                    <option value="Anna Lee">Anna Lee</option>
                    <option value="Minho Park">Minho Park</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Air Schedule -->
        <div class="card">
          <div class="card-header bg-secondary">
            <h5>Air Schedule</h5>
          </div>

          <div class="card-body">

            <!-- Air Schedule -->
            <div class="row">
              <div class="main-header">
                <div class="header-container">
                  <h6>Arrival</h6>
                </div>
              </div>

              <div class="columns col-md-4">
                <div class="column-header">
                  <label for="arrivalDate">Date <span class="text-danger">*</span></label>
                </div>
                <div class="datepicker-wrapper">
                  <div class="form-group">
                    <div class="date-range-inputs-wrapper">
                      <div class="input-with-icon">
                        <input type="text" class="datepicker" id="arrivalDate" name="arrivalDate"
                          placeholder="Arrival Date" readonly>
                        <i class="fas fa-calendar-alt calendar-icon"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="columns col-md-2">
                <div class="column-header">
                  <label for="arrivalFlight">Flight <span class="text-danger">*</span></label>
                </div>
                <div class="form-group">
                  <select class="form-select" id="arrivalFlight" name="arrivalFlight" required>
                    <option selected disabled>Select Flight</option>
                    <option value="KE123">KE123</option>
                    <option value="OZ456">OZ456</option>
                    <option value="JL789">JL789</option>
                  </select>
                </div>
              </div>

              <div class="columns col-md-4">
                <div class="column-header">
                  <label for="arrivalOrigin">Origin - Destination <span class="text-danger">*</span></label>
                </div>
                <div class="datepicker-wrapper d-flex align-items-center">
                  <div class="form-group">
                    <select class="form-select" id="arrivalOrigin" name="arrivalOrigin" required>
                      <option selected disabled>Origin</option>
                      <option value="MNL">Manila</option>
                      <option value="ICN">Incheon</option>
                      <option value="NRT">Narita</option>
                      <option value="LAX">Los Angeles</option>
                    </select>
                  </div>
                  <div class="dash-separator px-2">→</div>
                  <div class="form-group">
                    <select class="form-select" id="arrivalDestination" name="arrivalDestination" required>
                      <option selected disabled>Destination</option>
                      <option value="MNL">Manila</option>
                      <option value="CEB">Cebu</option>
                      <option value="BKK">Bangkok</option>
                      <option value="ICN">Incheon</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="columns col-md-4">
                <div class="column-header">
                  <label for="arrivalTimeStart">Arrival Time (Start - End) <span class="text-danger">*</span></label>
                </div>
                <div class="form-group d-flex flex-row gap-2">
                  <div class="input-with-icon timepicker">
                    <input type="text" class="timepicker form-control-sm" id="arrivalTimeStart" name="arrivalTimeStart"
                      placeholder="Pick a time" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                  <span class="align-self-center">to</span>
                  <div class="input-with-icon timepicker">
                    <input type="text" class="timepicker form-control-sm" id="arrivalTimeEnd" name="arrivalTimeEnd"
                      placeholder="Pick a time" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                </div>
              </div>
            </div>

            <!-- Destination Section -->
            <div class="row">
              <div class="main-header">
                <div class="header-container">
                  <h6>Destination</h6>
                </div>
              </div>

              <div class="columns col-md-4">
                <div class="column-header">
                  <label for="destinationDate">Date <span class="text-danger">*</span></label>
                </div>
                <div class="datepicker-wrapper">
                  <div class="form-group">
                    <div class="date-range-inputs-wrapper">
                      <div class="input-with-icon">
                        <input type="text" class="datepicker" id="destinationDate" name="destinationDate"
                          placeholder="Destination Date" readonly>
                        <i class="fas fa-calendar-alt calendar-icon"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="columns col-md-2">
                <div class="column-header">
                  <label for="destinationFlight">Flight <span class="text-danger">*</span></label>
                </div>
                <div class="form-group">
                  <select class="form-select" id="destinationFlight" name="destinationFlight" required>
                    <option selected disabled>Select Flight</option>
                    <option value="KE321">KE321</option>
                    <option value="OZ654">OZ654</option>
                    <option value="JL987">JL987</option>
                  </select>
                </div>
              </div>

              <div class="columns col-md-4">
                <div class="column-header">
                  <label for="destinationOrigin">Origin - Destination <span class="text-danger">*</span></label>
                </div>
                <div class="datepicker-wrapper d-flex align-items-center">
                  <div class="form-group">
                    <select class="form-select" id="destinationOrigin" name="destinationOrigin" required>
                      <option selected disabled>Origin</option>
                      <option value="ICN">Incheon</option>
                      <option value="MNL">Manila</option>
                      <option value="CEB">Cebu</option>
                      <option value="BKK">Bangkok</option>
                    </select>
                  </div>
                  <div class="dash-separator px-2">→</div>
                  <div class="form-group">
                    <select class="form-select" id="destinationArrival" name="destinationArrival" required>
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
                  <label for="departureTimeStart">Departure Time (Start - End) <span
                      class="text-danger">*</span></label>
                </div>
                <div class="form-group d-flex flex-row gap-2">
                  <div class="input-with-icon timepicker">
                    <input type="text" class="timepicker form-control-sm" id="departureTimeStart"
                      name="departureTimeStart" placeholder="Pick a time" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                  <span class="align-self-center">to</span>
                  <div class="input-with-icon timepicker">
                    <input type="text" class="timepicker form-control-sm" id="departureTimeEnd" name="departureTimeEnd"
                      placeholder="Pick a time" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Guide Meeting
        <div class="card">
          <div class="card-header bg-secondary">
            <h5>Guide Meeting</h5>
          </div>

          <div class="card-body">
       
            <div class="row">

             
              <div class="columns col-md-2">
                <div class="column-header">
                  <label for="PeriodStartDate">Date <span class="text-danger"> *</span></label>
                </div>
                <div class="datepicker-wrapper">
                  <div class="form-group">
                    <div class="date-range-inputs-wrapper">
                      <div class="input-with-icon datepicker">
                        <input type="text" class="datepicker" placeholder="Pick a date" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="columns col-md-2">
                <div class="column-header">
                  <label for="flightTime">
                    Time <small class="time-format">(24H Format)</small>
                    <span class="text-danger"> *</span>
                  </label>
                </div>



                <div class="form-group">
                  <div class="date-range-inputs-wrapper">
                    <div class="input-with-icon timepicker">
                      <input type="text" class="timepicker" placeholder="Pick a time" readonly>
                      <i class="fas fa-clock calendar-icon"></i>
                    </div>
                  </div>
                </div>
              </div>

            
              <div class="columns col-md-8">
                <div class="column-header">
                  <label for="packageSelect">Place
                    <span class="text-danger"> *</span>
                  </label>
                </div>

                <div class="form-group">
                  <select class="form-select" id="packageSelect" name="packageSelect" required>
                    <option selected disabled>Select Flight</option>
                  </select>
                </div>
              </div>

            </div>
          </div>
        </div> -->

        <!-- Includes Header -->
        <div class="card includes-header-card">
          <div class="card-header bg-secondary card-title includes-wrapper">
            <h5>Includes</h5>
            <button id="addIncludeBtn" class="add-button btn btn-primary add-exclude-button">+</button>
            <!-- Unique ID -->
          </div>
        </div>

        <!-- Include Cards Container -->
        <div class="card include-cards">
          <div class="card-body" id="includesContainer">
            <!-- JS will generate .row elements here directly -->
          </div>
        </div>

        <!-- Excludes -->
        <div class="card excludes-header-card">
          <div class="card-header bg-secondary card-title excludes-wrapper">
            <h5>Excludes</h5>
            <button type="button" class="add-button btn btn-primary add-exclude-button">+</button>
            <!-- Add Exclude Button -->
          </div>
        </div>

        <div class="card excludes-cards">
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
          <p>Please enter a template name before proceeding:</p>

          <!-- Template Name Input -->
          <div class="mt-3">
            <label for="templateName" class="form-label">Template Name:</label>
            <input type="text" class="form-control" id="templateName" placeholder="Enter template name">
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
    let includesData = {};    // Includes
    let excludesData = {};    // Excludes
  </script>

  <script>
    function updateVoucherDetails() {
      voucherDetails = {
        to: document.getElementById("voucherTo").value,
        from: document.getElementById("voucherFrom").value,
        tour: document.getElementById("voucherTour").value,
        attachment: document.getElementById("voucherAttachment").value,
        periodStart: document.getElementById("voucherPeriodStart").value,
        periodEnd: document.getElementById("voucherPeriodEnd").value,
        paxCount: document.getElementById("voucherPaxCount").value
      };

      console.log("Voucher Details JSON:", JSON.stringify(voucherDetails, null, 2));
    }
  </script>

  <!-- Date and Hotel Section Functions and JSON generation Script -->
  <script>
    let cardCount = 0;
    const maxCards = 3; // Maximum number of cards allowed

    document.addEventListener("DOMContentLoaded", function () {
      console.log("Page loaded");
      addCard(); // Load the first card
    });

    function addCard() {
      // Check if the card count is less than the max allowed cards
      if (cardCount >= maxCards) {
        alert("You can only add a maximum of 3 cards.");
        return; // Stop adding more cards
      }

      cardCount++;
      console.log(`Adding card #${cardCount}`);

      const container = document.getElementById('cardsContainer');
      const card = document.createElement('div');
      card.className = 'card mt-1';
      card.setAttribute('data-card-id', cardCount);

      card.innerHTML = `
        <div class="card-header bg-secondary d-flex justify-content-between align-items-center text-white">
          <h5 class="mb-0">Date & Hotel #${cardCount}</h5>
          <button type="button" class="btn btn-sm btn-danger remove-card-btn">
            <i class="fas fa-trash-alt"></i>
          </button>
        </div>
        <div class="card-body tour-content">
          <div class="row">
            <div class="columns col-md-2">
              <label>Date Range: <span class="text-danger">*</span></label>
              <div class="form-group input-with-icon">
                <input type="text" class="form-control datepicker" id="startDate${cardCount}" placeholder="Start" readonly>
                <i class="fas fa-calendar-alt calendar-icon"></i>
              </div>
            </div>

            <div class="columns col-md-2">
              <label>&nbsp;</label>
              <div class="form-group input-with-icon">
                <input type="text" class="form-control datepicker" id="endDate${cardCount}" placeholder="End" readonly>
                <i class="fas fa-calendar-alt calendar-icon"></i>
              </div>
            </div>

            <div class="columns col-md-2">
              <label>No. of Nights: <span class="text-danger">*</span></label>
              <div class="form-group input-with-icon">
                <input type="number" class="form-control" id="nightCount${cardCount}" placeholder="Enter number of nights" min="1">
                <i class="fas fa-moon calendar-icon"></i>
              </div>
            </div>

            <div class="columns col-md-3">
              <label>City: <span class="text-danger">*</span></label>
              <div class="form-group">
                <select class="form-select" id="city${cardCount}" required>
                  <option value="" disabled selected>Select City</option>
                  <option value="Seoul">Seoul</option>
                  <option value="Busan">Busan</option>
                </select>
              </div>
            </div>

            <div class="columns col-md-3">
              <label>Hotel: <span class="text-danger">*</span></label>
              <div class="form-group">
                <select class="form-select" id="hotel${cardCount}" required>
                  <option value="" disabled selected>Select Hotel</option>
                  <option value="Shilla Stay">Shilla Stay</option>
                  <option value="Hotel PJ Myeongdong">Hotel PJ Myeongdong</option>
                  <option value="ENA Suite Hotel Namdaemun">ENA Suite Hotel Namdaemun</option>
                  <option value="Stanford Hotel Myeongdong">Stanford Hotel Myeongdong</option>
                </select>
              </div>
            </div>

          </div>
        </div>
      `;

      container.appendChild(card);

      // Remove card logic
      card.querySelector('.remove-card-btn').addEventListener('click', () => {
        card.remove();
        updateCardHeaders();
        generateCardsJSON();
      });

      // Flatpickr for date pickers
      flatpickr(`#startDate${cardCount}`, { dateFormat: "Y-m-d" });
      flatpickr(`#endDate${cardCount}`, { dateFormat: "Y-m-d" });

      // Update JSON whenever a card is added
      generateCardsJSON();
    }

    function updateCardHeaders() {
      const cards = document.querySelectorAll('#cardsContainer .card');
      cards.forEach((card, index) => {
        const header = card.querySelector('h5');
        if (header) {
          header.textContent = `Date & Hotel #${index + 1}`;
        }
      });
    }

    function generateCardsJSON() {
      const cards = document.querySelectorAll('#cardsContainer .card');
      const jsonData = {};

      cards.forEach((card, index) => {
        const cardId = card.getAttribute('data-card-id');

        const cardDetails = {
          startDate: document.getElementById(`startDate${cardId}`)?.value || '',
          endDate: document.getElementById(`endDate${cardId}`)?.value || '',
          nights: document.getElementById(`nightCount${cardId}`)?.value || '',
          city: document.getElementById(`city${cardId}`)?.value || '',
          hotel: document.getElementById(`hotel${cardId}`)?.value || ''
        };

        jsonData[`dateAndHotel${index + 1}`] = cardDetails;
      });

      // Assign to the global variable
      cardsJSONData = jsonData;

      console.log("Sectioned Cards JSON:", JSON.stringify(cardsJSONData, null, 2));
    }

  </script>

  <!-- JSON generation for Air Details and Guide Meeting -->
  <script>
    function getAirDetails() {
      // Utility: Add minutes to a date object
      const addMinutes = (date, minutes) => new Date(date.getTime() + minutes * 60000);

      // Utility: Parse time string "HH:mm" to a Date object (using today’s date)
      const parseTimeStringToDate = (timeString) => {
        const [hours, minutes] = timeString.split(':').map(Number);
        const now = new Date();
        now.setHours(hours);
        now.setMinutes(minutes);
        now.setSeconds(0);
        now.setMilliseconds(0);
        return now;
      };

      // Parse and adjust guide meeting time
      const arrivalTimeStartValue = document.getElementById('arrivalTimeStart').value;
      const currentTime = parseTimeStringToDate(arrivalTimeStartValue);
      const updatedTime = addMinutes(currentTime, 15);

      // Format time to "HH:mm"
      const formattedTime = `${updatedTime.getHours().toString().padStart(2, '0')}:${updatedTime.getMinutes().toString().padStart(2, '0')}`;

      // Get selected guide meeting place
      const selectedPlace = document.getElementById('arrivalDestination').value;

      // Define selectable places
      const placeOptions = {
        'ICN': 'Incheon Airport (Terminal 1)',
        'Other': 'Custom Place'
        // Add more options if needed
      };

      const guideMeetingPlace = placeOptions[selectedPlace] || 'Custom Place';

      // Construct the air details object
      const airDetails = {
        arrival: {
          date: document.getElementById('arrivalDate').value,
          flight: document.getElementById('arrivalFlight').value,
          origin: document.getElementById('arrivalOrigin').value,
          destination: document.getElementById('arrivalDestination').value,
          timeStart: arrivalTimeStartValue,
          timeEnd: document.getElementById('arrivalTimeEnd').value
        },
        destination: {
          date: document.getElementById('destinationDate').value,
          flight: document.getElementById('destinationFlight').value,
          origin: document.getElementById('destinationOrigin').value,
          destination: document.getElementById('destinationArrival').value,
          timeStart: document.getElementById('departureTimeStart').value,
          timeEnd: document.getElementById('departureTimeEnd').value
        },
        guideMeeting: {
          date: document.getElementById('arrivalDate').value,
          time: formattedTime,
          place: guideMeetingPlace
        }
      };

      console.log("Air Details JSON:", airDetails);
      return airDetails;
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

      // Assuming `getAirDetails()` and `updateVoucherDetails()` are defined elsewhere
      getAirDetails();
      updateVoucherDetails();
    }

    // Function to add a new include row
    function addInclude() {
      if (includeCount >= maxIncludes) return;

      includeCount++;
      const includesContainer = document.getElementById('includesContainer');

      const newRow = document.createElement('div');
      newRow.className = 'row include-row align-items-start mb-1';
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
    document.addEventListener('DOMContentLoaded', () => {
      const excludeConfig = {
        count: 0,
        max: 4,
        container: document.getElementById('excludesContainer'),
        addButton: document.querySelector('.add-exclude-button')
      };

      const excludesData = {};

      // Function to retrieve selected excludes
      function getSelectedExcludes() {
        const selectedValues = [];
        document.querySelectorAll('.exclude-row').forEach(row => {
          const select = row.querySelector('select');
          const input = row.querySelector('.custom-exclude-input');
          const value = select.value === 'others' ? input.value.trim() : select.value;
          selectedValues.push({ id: select.id, value });
        });
        return selectedValues;
      }

      // Function to update disabled options based on selections
      function updateDisabledExcludes() {
        const selectedValues = getSelectedExcludes().map(item => item.value);
        document.querySelectorAll('.exclude-row select').forEach(select => {
          select.querySelectorAll('option').forEach(option => {
            if (
              option.value !== select.value &&
              selectedValues.includes(option.value) &&
              option.value !== "" &&
              option.value !== "others"
            ) {
              option.disabled = true;
            } else {
              option.disabled = false;
            }
          });
        });
      }

      // Function to update labels and attributes
      function updateExcludeLabels() {
        document.querySelectorAll('.exclude-row').forEach((row, i) => {
          const label = row.querySelector('label');
          const select = row.querySelector('select');
          const index = i + 1;
          row.setAttribute('data-index', index);
          label.setAttribute('for', `excludesSelect${index}`);
          label.textContent = `Excludes ${index}:`;
          select.setAttribute('id', `excludesSelect${index}`);
          select.setAttribute('name', `excludesSelect${index}`);
        });
      }

      function updateExcludesData() {
        const selectedExcludes = getSelectedExcludes();

        // Reassign new object to global variable
        excludesData = {};

        selectedExcludes.forEach((exclude, index) => {
          const excludeIndex = index + 1;
          excludesData[`excludes${excludeIndex}`] = { value: exclude.value };
        });

        console.log('Updated Excludes Data (JSON):', JSON.stringify(excludesData, null, 2));
      }

      // Function to create a new exclude row
      function createExcludeRow() {
        if (excludeConfig.count >= excludeConfig.max) return;

        excludeConfig.count++;
        const index = excludeConfig.count;

        const row = document.createElement('div');
        row.className = 'row exclude-row align-items-start mb-3';
        row.setAttribute('data-index', index);

        row.innerHTML = `
          <div class="col-md-12">
            <div class="label-container">
              <label for="excludesSelect${index}" class="form-label">Excludes ${index}:</label>
              <button type="button" class="btn btn-sm btn-danger remove-exclude" title="Remove">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
            <div class="content-container">
              <select class="form-select exclude-select mb-2" id="excludesSelect${index}" name="excludesSelect${index}" required>
                <option value="" selected disabled>Select Exclude</option>
                <option value="1">Flight (Round trip flight tickets)</option>
                <option value="2">Visa Fees</option>
                <option value="3">Meals (Meals outside the package)</option>
                <option value="4">Personal Expenses</option>
                <option value="5">Optional Tours</option>
                <option value="others">Others</option>
                <option value="0"> — No Excludes — </option>
              </select>
              <input type="text" class="form-control custom-exclude-input d-none" placeholder="Please specify..." />
            </div>
          </div>
        `;

        const select = row.querySelector('select');
        const input = row.querySelector('.custom-exclude-input');
        const removeBtn = row.querySelector('.remove-exclude');

        // Event listener for select change
        select.addEventListener('change', () => {
          if (select.value === 'others') {
            input.classList.remove('d-none');
            input.focus();
          } else {
            input.classList.add('d-none');
          }
          updateDisabledExcludes();
          updateExcludesData();
        });

        // Event listener for input change
        input.addEventListener('input', () => {
          updateExcludesData();
        });

        // Event listener for remove button
        removeBtn.addEventListener('click', () => {
          row.remove();
          excludeConfig.count--;
          updateExcludeLabels();
          updateDisabledExcludes();
          updateExcludesData();
        });

        excludeConfig.container.appendChild(row);
        updateDisabledExcludes();
      }

      // Initialize the Excludes section
      function initExcludesSection() {
        createExcludeRow();
        excludeConfig.addButton?.addEventListener('click', () => {
          if (excludeConfig.count < excludeConfig.max) {
            createExcludeRow();
          }
        });
      }

      initExcludesSection();
    });
  </script>

  <!-- Voucher Form Submission Script -->
  <script>
    document.getElementById("submitTour").addEventListener("click", function () {
      $("#templateNameModal").modal("show");
    });

    // Function to proceed after entering the template name
    function proceedWithSubmission() {
      const templateName = document.getElementById("templateName")?.value.trim();

      if (!templateName) {
        alert("Please enter a template name before proceeding.");
        return;
      }

      const voucherPayload = {
        templateName: templateName,
        voucherDetails: voucherDetails || {},
        cardsJSONData: cardsJSONData || {},
        includesData: includesData || {},
        excludesData: excludesData || {}
      };

      console.log("Voucher Payload to be submitted:", voucherPayload);


      $.ajax({
        url: "../Employee Section/functions/emp-saveVoucher.php",
        type: "POST",
        data: {
          voucherPayload: JSON.stringify(voucherPayload)
        },
        dataType: "json",
        success: function (response) {
          submitButton.disabled = false;
          if (response.status === "success") {
            alert("Itinerary successfully created!");
            window.location.href = "../Employee Section/emp-itinerarytable.php";
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

      $("#templateNameModal").modal("hide");
    }
  </script>


  </body>
</html>