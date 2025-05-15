<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Itinerary Details</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-editItinerary.css?v=<?php echo time(); ?>">

  <!-- CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/wickedpicker@0.4.3/dist/wickedpicker.min.css">

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/wickedpicker@0.4.3/dist/wickedpicker.min.js"></script>



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
            <h5 class="header-title">Voucher Details</h5>
          </div>
        </div>

      </div>
    </div>

    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Employee Section/emp-voucherTable.php'; // Replace with your actual URL
      });
    </script>

    <!-- Data Fetch JSON Script -->
    <?php
    if (!isset($_GET['id'])) {
      die("Invalid Voucher ID");
    }

    $voucherId = intval($_GET['id']); // Ensure it's an integer
    
    // Step 1: Fetch core voucher info and details
    $sql = "
            SELECT 
                v.voucherId,
                v.voucherName,
                v.voucherCode,
                v.accountId,
                v.createdAt AS voucherCreatedAt,
                d.sentTo,
                d.sentFrom,
                d.tourType,
                d.attachment,
                d.tourPeriodStart,
                d.tourPeriodEnd,
                d.guideName,
                d.noOfPax,
                d.createdAt AS detailCreatedAt
            FROM vouchers v
            LEFT JOIN voucherDetails d ON v.voucherId = d.voucherId
            WHERE v.voucherId = ?
        ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
      die("Voucher not found");
    }

    $voucher = [
      'voucherId' => $row['voucherId'],
      'voucherName' => $row['voucherName'],
      'voucherCode' => $row['voucherCode'],
      'accountId' => $row['accountId'],
      'voucherCreatedAt' => $row['voucherCreatedAt'],
      'details' => [
        'sentTo' => $row['sentTo'],
        'sentFrom' => $row['sentFrom'],
        'tourType' => $row['tourType'],
        'attachment' => $row['attachment'],
        'tourPeriodStart' => $row['tourPeriodStart'],
        'tourPeriodEnd' => $row['tourPeriodEnd'],
        'guideName' => $row['guideName'],
        'noOfPax' => $row['noOfPax'],
        'detailCreatedAt' => $row['detailCreatedAt']
      ],
      'dateAndHotels' => [],
      'includes' => [],
      'excludes' => []
    ];

    // Step 2: Fetch voucherDateAndHotels
    $sqlDates = "
                SELECT startDate, endDate, nights, city, hotel
                FROM voucherDateAndHotels
                WHERE voucherId = ?
                ORDER BY startDate ASC
            ";

    $stmt = $conn->prepare($sqlDates);
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $voucher['dateAndHotels'][] = $row;
    }

    // Step 3: Fetch voucherIncludes
    $sqlIncludes = "
                SELECT i.itemName 
                FROM voucherIncludes vi
                INNER JOIN voucherIncludeOptions i ON vi.includeItemId = i.includeItemId
                WHERE vi.voucherId = ?
            ";

    $stmt = $conn->prepare($sqlIncludes);
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $voucher['includes'][] = $row['itemName'];
    }

    // Step 4: Fetch voucherExcludes
    $sqlExcludes = "
                SELECT e.itemName 
                FROM voucherExcludes ve
                INNER JOIN voucherExcludeOptions e ON ve.excludeItemId = e.excludeItemId
                WHERE ve.voucherId = ?
            ";

    $stmt = $conn->prepare($sqlExcludes);
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $voucher['excludes'][] = $row['itemName'];
    }

    // Output to browser console as JSON
    $jsonData = json_encode($voucher, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "<script>console.log($jsonData);</script>";

    ?>

    <div class="main-content">
      <input type="hidden" id="itineraryId" value="<?= htmlspecialchars($itineraryId); ?>" readonly>

      <div class="form-container">

        <!-- Voucher Details -->
        <div class="card mb-4 shadow-sm">
          <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 text-primary">Voucher Details</h5>
          </div>

          <div class="card-body">
            <!-- Row 1: Voucher Name & Code -->
            <div class="row g-3 mb-3">
              <div class="col-12 col-md-6 col-lg-4">
                <label for="itineraryName" class="form-label">Voucher Name</label>
                <input type="text" class="form-control" id="itineraryName" name="itineraryName" value="<?= !empty($voucher['voucherName']) ? htmlspecialchars($voucher['voucherName'], ENT_QUOTES) : 'Untitled Voucher' ?>" readonly>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label for="voucherCode" class="form-label">Voucher Code</label>
                <input type="text" class="form-control" id="voucherCode" name="voucherCode"
                  value="<?= $voucher['voucherCode']; ?>" readonly>
              </div>
            </div>

            <!-- Row 2: To, From, Package -->
            <div class="row g-3 mb-3">
              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">To</label>
                <input type="text" class="form-control" value="<?= $voucher['details']['sentTo']; ?>" readonly>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">From</label>
                <input type="text" class="form-control" value="<?= $voucher['voucherCode']; ?>" readonly>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label for="packageSelect" class="form-label">Package</label>
                <select class="form-select" id="packageSelect" name="packageSelect" required>
                  <option value="" disabled>Select a package</option>

                  <?php
                  // Fetch packageId and packageName
                  $sql1 = "SELECT packageId, packageName FROM package ORDER BY packageId ASC";
                  $res1 = $conn->query($sql1);

                  if ($res1->num_rows > 0) {
                    while ($row = $res1->fetch_assoc()) {
                      // Check if this packageId matches voucher's packageId to mark selected
                      $selected = ($row['packageId'] == $voucher['packageId']) ? "selected" : "";
                      echo "<option value='" . htmlspecialchars($row['packageId'], ENT_QUOTES) . "' $selected>"
                        . htmlspecialchars($row['packageName'], ENT_QUOTES) . "</option>";
                    }
                  } else {
                    echo "<option value=''>No packages available</option>";
                  }
                  ?>

                </select>
              </div>


            </div>

            <!-- Row 3: Dates, Guide -->
            <div class="row g-3">
              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">Tour Periods <span class="text-danger">*</span></label>
                <div class="d-flex align-items-center gap-2">
                  <!-- Start Date -->
                  <div class="input-with-icon position-relative w-100">
                    <input type="text" class="form-control pe-5 datepicker" id="PeriodStartDate" placeholder="Start"
                      value="<?= $voucher['details']['tourPeriodStart']; ?>" readonly>
                    <i class="fas fa-calendar-alt calendar-icon"></i>
                  </div>

                  <span class="mx-1">→</span>

                  <!-- End Date -->
                  <div class="input-with-icon position-relative w-100">
                    <input type="text" class="form-control pe-5 datepicker" id="PeriodEndDate" placeholder="End"
                      value="<?= $voucher['details']['tourPeriodEnd']; ?>">
                    <i class="fas fa-calendar-alt calendar-icon"></i>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label for="guideName" class="form-label">Guide <span class="text-danger">*</span></label>
                <select class="form-select" id="guideName" name="guideName" required>
                    <option disabled <?= empty($voucher['details']['guideName']) ? 'selected' : '' ?>>Select Guide</option>
                    <?php
                    $currentGuide = trim($itinerary['guideName'] ?? '');
                    
                    $sql = "SELECT id, fName, mName, lName FROM employee WHERE branch = 'Korea'";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $employeeId = htmlspecialchars($row['id']);
                        $fullName = trim($row['fName'] . ' ' . $row['mName'] . ' ' . $row['lName']);
                        $fullNameEscaped = htmlspecialchars($fullName);
                        $isSelected = ($currentGuide === $fullName) ? 'selected' : '';
                        echo "<option value=\"$fullNameEscaped\" $isSelected>$fullNameEscaped</option>";
                      }
                    } else {
                      echo "<option disabled>No guides available</option>";
                    }
                    ?>
                  </select>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">No. of Pax</label>
                <input type="text" class="form-control" value="<?= $voucher['details']['noOfPax']; ?>" readonly>
              </div>

            </div>
          </div>
        </div>

        <!-- Air Schedule -->
        <div class="card mb-2">
          <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0 text-primary">
              <!-- <i class="fas fa-plane-departure me-2 text-secondary"></i> -->
              Air Schedule
            </h5>
          </div>

          <div class="card-body">

            <!-- Arrival Section -->
            <div class="row mb-3">

              <div class="col-12 mb-2">
                <h6 class="fw-semibold text-uppercase text-muted border-bottom pb-1">Arrival</h6>
              </div>

              <div class="col-md-6 col-lg-3 mb-3">
                <label for="arrivalDate" class="form-label">Date </label>

                <div class="input-with-icon">
                  <input type="text" class="datepicker" id="arrivalDate" name="arrivalDate" placeholder="Arrival Date"
                    readonly>
                  <i class="fas fa-calendar-alt calendar-icon"></i>
                </div>

              </div>

              <div class="col-md-6 col-lg-3 mb-3">

                <label for="arrivalFlight" class="form-label">Flight</label>
                <select class="form-select" id="arrivalFlight" name="arrivalFlight" required>
                  <option selected disabled>Select Flight</option>
                  <option value="KE123">KE123</option>
                  <option value="OZ456">OZ456</option>
                  <option value="JL789">JL789</option>
                </select>

              </div>

              <div class="col-md-12 col-lg-6 mb-3">
                <label class="form-label">Origin - Destination</label>
                <div class="d-flex flex-column flex-sm-row gap-2">
                  <select class="form-select" id="arrivalOrigin" name="arrivalOrigin" required>
                    <option selected disabled>Origin</option>
                    <option value="MNL">Manila</option>
                    <option value="ICN">Incheon</option>
                    <option value="NRT">Narita</option>
                    <option value="LAX">Los Angeles</option>
                  </select>
                  <div class="dash-separator d-none d-sm-flex align-items-center px-2">→</div>
                  <select class="form-select" id="arrivalDestination" name="arrivalDestination" required>
                    <option selected disabled>Destination</option>
                    <option value="MNL">Manila</option>
                    <option value="CEB">Cebu</option>
                    <option value="BKK">Bangkok</option>
                    <option value="ICN">Incheon</option>
                  </select>
                </div>
              </div>

              <div class="col-md-12 col-lg-6 mb-3">
                <label for="arrivalTimeStart" class="form-label">Arrival Time (Start - End) </label>
                <div class="d-flex flex-column flex-sm-row gap-2">
                  <div class="input-with-icon">
                    <input type="text" class="form-control timepicker" id="arrivalTimeStart" name="arrivalTimeStart"
                      placeholder="Pick a time" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                  <span class="align-self-center">to</span>
                  <div class="input-with-icon">
                    <input type="text" class="form-control timepicker" id="arrivalTimeEnd" name="arrivalTimeEnd"
                      placeholder="Pick a time" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                </div>
              </div>

            </div>

            <!-- Destination Section -->
            <div class="row">
              <div class="col-12 mb-2">
                <h6 class="fw-semibold text-uppercase text-muted border-bottom pb-1">Destination</h6>
              </div>

              <div class="col-md-6 col-lg-3 mb-3">
                <label for="destinationDate" class="form-label">Date <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                  <input type="text" class="form-control datepicker" id="destinationDate" name="destinationDate"
                    placeholder="Destination Date" readonly>
                  <i class="fas fa-calendar-alt calendar-icon"></i>
                </div>
              </div>

              <div class="col-md-6 col-lg-3 mb-3">
                <label for="destinationFlight" class="form-label">Flight <span class="text-danger">*</span></label>
                <select class="form-select" id="destinationFlight" name="destinationFlight" required>
                  <option selected disabled>Select Flight</option>
                  <option value="KE321">KE321</option>
                  <option value="OZ654">OZ654</option>
                  <option value="JL987">JL987</option>
                </select>
              </div>

              <div class="col-md-12 col-lg-6 mb-3">
                <label class="form-label">Origin - Destination <span class="text-danger">*</span></label>
                <div class="d-flex flex-column flex-sm-row gap-2">
                  <select class="form-select" id="destinationOrigin" name="destinationOrigin" required>
                    <option selected disabled>Origin</option>
                    <option value="ICN">Incheon</option>
                    <option value="MNL">Manila</option>
                    <option value="CEB">Cebu</option>
                    <option value="BKK">Bangkok</option>
                  </select>
                  <div class="dash-separator d-none d-sm-flex align-items-center px-2">→</div>
                  <select class="form-select" id="destinationArrival" name="destinationArrival" required>
                    <option selected disabled>Destination</option>
                    <option value="MNL">Manila</option>
                    <option value="ICN">Incheon</option>
                    <option value="NRT">Narita</option>
                    <option value="LAX">Los Angeles</option>
                  </select>
                </div>
              </div>

              <div class="col-md-12 col-lg-6 mb-3">
                <label for="departureTimeStart" class="form-label">Departure Time (Start - End)</label>

                <div class="d-flex flex-column flex-sm-row gap-2">
                  <div class="input-with-icon">
                    <input type="text" class="form-control timepicker" id="departureTimeStart" name="departureTimeStart"
                      placeholder="Start" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                  <span class="align-self-center">to</span>
                  <div class="input-with-icon">
                    <input type="text" class="form-control timepicker" id="departureTimeEnd" name="departureTimeEnd"
                      placeholder="End" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                </div>
              </div>


            </div>
          </div>

        </div>

        <!-- Date and Hotels -->
        <div class="card">
          <div class="card-header">
            <h5 class="fw-bold">Date and Hotels</h5>
          </div>

          <div class="card-body">

            <div id="dateHotelContainer"></div>

          </div>
        </div>


      </div>



    </div>

    <!-- Form footer with both buttons -->
    <div class="form-footer">
      <button type="button" class="btn btn-primary" id="submitEdit">Submit Edit</button>

      <select id="actionSelector" class="form-select" style="width: 120px;">
        <option value="xlsx" selected>Excel (.xlsx)</option>
        <option value="pdf">PDF</option>
        <option value="both">Excel and PDF </option>
      </select>

      <button type="button" class="btn btn-primary" id="submitTour">Generate Itinerary</button>
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



  <!-- Datepicker Script -->
  <script>
    $(document).ready(function () {
      // Apply datepicker for PeriodStartDate
      $("#PeriodStartDate").datepicker({
        dateFormat: "yy-mm-dd",
        showAnim: "fadeIn",
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2100",
        onSelect: function (dateText) {
          console.log("PeriodStartDate Selected: " + dateText);
        }
      });

      // Apply datepicker for PeriodEndDate
      $("#PeriodEndDate").datepicker({
        dateFormat: "yy-mm-dd",
        showAnim: "fadeIn",
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2100",
        onSelect: function (dateText) {
          console.log("PeriodEndDate Selected: " + dateText);
        }
      });
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Arrival time pickers
      new WickedPicker('#arrivalTimeStart', { now: "12:00", twentyFour: false });
      new WickedPicker('#arrivalTimeEnd', { now: "12:30", twentyFour: false });

      // Departure time pickers
      new WickedPicker('#departureTimeStart', { now: "14:00", twentyFour: false });
      new WickedPicker('#departureTimeEnd', { now: "14:30", twentyFour: false });
    });
  </script>


  <!-- FOR DATE AND HOTEL CARD -->
  <script>
    const voucher = <?= json_encode($voucher, JSON_UNESCAPED_UNICODE); ?>;

    document.addEventListener('DOMContentLoaded', () => {
      if (!voucher || !Array.isArray(voucher.dateAndHotels)) {
        console.error('voucher.dateAndHotels is missing or not an array');
        return;
      }

      const voucherDateAndHotels = voucher.dateAndHotels;
      const container = document.getElementById('dateHotelContainer');
      container.innerHTML = ''; // Clear existing content

      voucherDateAndHotels.forEach((item, index) => {
        const num = index + 1;

        const wrapper = document.createElement('div');
        wrapper.className = 'mb-4'; // Removed card-like styles

        wrapper.innerHTML = `
          

          <div class="col-12 mb-3 mt-3">
            <h6 class="fw-semibold text-uppercase text-muted border-bottom pb-1">Date and Hotels #${num}</h6>
          </div>

          <div class="row g-4 align-items-end">

            <!-- Date Range -->
            <div class="col-12 col-md-5">
              <label class="form-label">Date</label>
              <div class="d-flex gap-2 align-items-center">
                
                <!-- Start Date -->
                <div class="position-relative w-100">
                  <input type="text" class="form-control datepicker" id="PeriodStartDate${num}" 
                    placeholder="Start" value="${item.startDate || ''}" readonly>
                  <i class="fas fa-calendar-alt position-absolute text-muted"
                    style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                </div>

                <span class="mx-1 text-muted">→</span>

                <!-- End Date -->
                <div class="position-relative w-100">
                  <input type="text" class="form-control datepicker" id="PeriodEndDate${num}" 
                    placeholder="End" value="${item.endDate || ''}" readonly>
                  <i class="fas fa-calendar-alt position-absolute text-muted"
                    style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                </div>
              </div>
            </div>

            <!-- No. of Nights -->
            <div class="col-6 col-md-2">
              <label for="nights${num}" class="form-label">No. of Nights</label>
              <input type="text" class="form-control" id="nights${num}" 
                name="nights${num}" value="${item.nights || ''}" readonly>
            </div>

            <!-- City -->
            <div class="col-6 col-md-2">
              <label for="city${num}" class="form-label">City</label>
              <input type="text" class="form-control" id="city${num}" 
                name="city${num}" value="${item.city || ''}" readonly>
            </div>

            <!-- Hotel -->
            <div class="col-12 col-md-3">
              <label for="hotel${num}" class="form-label">Hotel</label>
              <input type="text" class="form-control" id="hotel${num}" 
                name="hotel${num}" value="${item.hotel || ''}" readonly>
            </div>

          </div>
        `;

        container.appendChild(wrapper);
      });
    });
  </script>




  <!-- For Itinerary Card -->
  <script>
    let liveItineraryData;

    document.addEventListener("DOMContentLoaded", function () {

      document.getElementById('submitEdit').disabled = true;
      document.getElementById('select-days').disabled = true;

      const itineraryContainer = document.getElementById("itinerary-container");
      const selectDays = document.getElementById("select-days");
      const formFooter = document.querySelector(".form-footer");

      let itineraryData = <?= json_encode($itinerary); ?>;

      liveItineraryData = JSON.parse(JSON.stringify(itineraryData)); // ✅ assign, don't declare


      window.updateLiveItineraryData = function () {
        const itineraryName = document.getElementById("itineraryName").value;
        const packageSelect = document.getElementById("packageSelect").value;
        const periodStart = document.getElementById("PeriodStartDate").value;
        const periodEnd = document.getElementById("PeriodEndDate").value;
        const guideName = document.getElementById("guideName").value;
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
          itineraryId: 1,  // Assuming itineraryId is constant or comes from elsewhere
          itineraryName,
          packageName: packageSelect,
          periodStart,
          periodEnd,
          guideName,
          countryCode,
          contactNumber,
          cities,
          noOfDays: parseInt(selectDays.value)  // Moved noOfDays inside itineraryDetails
        };

        const daysDetails = [];
        const cards = itineraryContainer.querySelectorAll(".itinerary-card");
        cards.forEach((card, index) => {
          const areas = Array.from(card.querySelectorAll(".area-select")).map(sel => sel.value);
          const meals = Array.from(card.querySelectorAll(".meal-plan-select")).map(sel => sel.value);
          const hotels = Array.from(card.querySelectorAll(".hotel-select")).map(sel => sel.value);
          const activities = Array.from(card.querySelectorAll(".itinerary-select")).map(sel => sel.value);

          daysDetails.push({
            day: index + 1,
            areas,
            meals,
            hotels,
            activities
          });
        });

        // Assign the result to the global variable
        liveItineraryData = {
          itineraryDetails,  // Updated sequence: itineraryDetails first
          daysDetails  // daysDetails second
        };

        console.log("Updated from DOM:", JSON.stringify(liveItineraryData, null, 2));
      };


      // Ensure days exist as an array
      let days = Array.isArray(itineraryData.days) ? itineraryData.days : [];
      let selectedValue = itineraryData.noOfDays || 0;

      // Function to extract values while keeping order
      const extractValues = (arr, key) => {
        let values = [];

        arr.forEach(day => {
          if (day && Array.isArray(day[key])) {
            day[key].forEach(item => {
              if (!values.includes(item)) {
                values.push(item); // Maintain order while ensuring uniqueness
              }
            });
          }
        });

        return values;
      };

      // Korean Tour Data
      const koreanTourAreas = ["Seoul", "Busan", "Jeju", "Incheon", "Gyeongju"];
      const koreanMealPlans = ["Traditional Korean Cuisine", "Street Food Tour", "Seafood Specialty", "Vegetarian Option", "Luxury Fine Dining"];

      const hotels = [
        "Lotte Hotel Seoul", "Signiel Seoul", "The Shilla Seoul", "Grand Hyatt Seoul", "InterContinental Seoul COEX",
        "Park Hyatt Busan", "Paradise Hotel Busan", "Lahan Hotel Jeonju", "Maison Glad Jeju", "Ramada Plaza Jeju"
      ];

      const itineraries = [
        "Gyeongbokgung Palace Tour", "Myeongdong Shopping District", "Namsan Seoul Tower", "Bukchon Hanok Village",
        "Dongdaemun Design Plaza", "Busan Gamcheon Culture Village", "Jeju Island Lava Tubes"
      ];

      // Extract ordered values from `days` while keeping original order
      let availableAreas = [...extractValues(days, "areas"), ...koreanTourAreas.filter(area => !days.some(day => day.areas.includes(area)))];
      let availableHotels = [...extractValues(days, "hotels"), ...hotels.filter(hotel => !days.some(day => day.hotels.includes(hotel)))];
      let availableMeals = [...extractValues(days, "meals"), ...koreanMealPlans.filter(meal => !days.some(day => day.meals.includes(meal)))];
      let availableActivities = [...extractValues(days, "activities"), ...itineraries];

      // console.log("Ordered Available Areas:", availableAreas);
      // console.log("Ordered Available Hotels:", availableHotels);
      // console.log("Ordered Available Meal Plans:", availableMeals);
      // console.log("Ordered Available Activities:", availableActivities);

      // Populate Days Dropdown
      selectDays.innerHTML = "";
      for (let num = 1; num <= 5; num++) {
        let option = document.createElement("option");
        option.value = num;
        option.textContent = `Day ${num}`;
        if (num === selectedValue) option.selected = true;
        selectDays.appendChild(option);
      }

      function createSelectColumn(label, className, options = [], selectedValue, index = 1) {
        // console.log(`Creating select dropdown for: ${label} ${index}`);

        if (!Array.isArray(options) || options.length === 0) {
          // console.error("Options array is empty or undefined for:", label);
          return `<div class="col-4"><label class="form-label fw-normal">${label} ${index}:</label><p style="color: red;">No options available</p></div>`;
        }

        // Ensure options are unique and sorted
        let uniqueOptions = [...new Set(options)].sort();

        // console.log("Final options before rendering:", uniqueOptions);

        return `
                <div class="col-4">
                    <label class="form-label fw-normal">${label} ${index}:</label>
                    <select class="form-select ${className}">
                        <option selected disabled>Select ${label} ${index}</option>
                        ${uniqueOptions.map(opt => {
          // console.log(`Processing option: ${opt}`);
          return `<option value="${opt}" ${String(opt) === String(selectedValue) ? "selected" : ""}>${opt}</option>`;
        }).join("")}
                    </select>
                </div>
            `;
      }

      // Function to create multiple select columns for hotels
      function createMultipleSelectColumns(labels, className, options, selectedValues = []) {
        return labels.map((label, index) => createSelectColumn(label, className, options, selectedValues[index] || "", index + 1)).join("");
      }

      // Function to generate itinerary cards for each day
      function generateItineraryCards(days) {
        itineraryContainer.innerHTML = "";

        // console.log("Generating Itinerary for Days:", days);

        for (let day = 1; day <= days; day++) {
          let dayData = itineraryData.days.find(d => d.day == day) || {};

          let areas = Array.isArray(dayData.areas) ? dayData.areas : [];
          let hotels = Array.isArray(dayData.hotels) ? dayData.hotels : [];
          let meals = Array.isArray(dayData.meals) ? dayData.meals : [];
          let activities = Array.isArray(dayData.activities) ? dayData.activities : [];

          // console.log(`\n=== Day ${day} Data ===`);
          // console.log("Areas:", areas);
          // console.log("Hotels:", hotels);
          // console.log("Meals:", meals);
          // console.log("Activities:", activities);

          const card = document.createElement("div");
          card.className = "card itinerary-card mb-3";
          card.innerHTML = `
                    <div class="card-header bg-primary text-white fw-bold">
                        Day ${day}
                    </div>

                    <div class="card-body">
                        <div class="container-fluid">
                        
                            <!-- Area Section (Dynamic) -->
                            <div class="row mb-3">
                                ${areas.map((area, index) => {
            // console.log(`Creating Select for Area ${index + 1}:`, area);
            return createSelectColumn("Area", "area-select", availableAreas, area, index + 1);
          }).join("")}
                            </div>

                            <!-- Meal Plan Section (Dynamic) -->
                            <div class="row mb-3">
                                ${meals.map((meal, index) => {
            // console.log(`Creating Select for Meal ${index + 1}:`, meal);
            return createSelectColumn("Meal Plan", "meal-plan-select", availableMeals, meal, index + 1);
          }).join("")}
                            </div>

                            <!-- Hotels Section (Dynamic) -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Hotels:</label>
                                    <div class="row">
                                        ${createMultipleSelectColumns(["Hotel", "Hotel"], "hotel-select", availableHotels, hotels)}
                                    </div>
                                </div>
                            </div>

                            <!-- Itinerary Section (Dynamic) -->
                            <div class="row mb-3">
                                <div class="col-md-9">
                                    <label class="form-label fw-semibold">Itinerary:</label>
                                    <div class="row">
                                        ${activities.map((activity, index) => {
            // console.log(`Rendering Activity Dropdown ${index + 1}:`, activity);

            return `    
                                                <div class="col-12 mb-2">
                                                    <select class="form-select itinerary-select">
                                                        <option selected disabled>Select Activity ${index + 1}</option>
                                                        ${availableActivities.map((act, actIndex) => {

              // console.log(`Adding Option ${actIndex + 1}:`, act);
              return `<option value="${act}" ${String(act) === String(activity) ? "selected" : ""}>${act}</option>`;
            }).join("")}
                                                        
                                                    </select>
                                                </div>
                                            `;
          }).join("")}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                `;
          itineraryContainer.appendChild(card);
        }


        formFooter.style.display = days ? "flex" : "none";
      }

      function attachSelectChangeListeners() {
        const editButton = document.getElementById("submitEdit");

        // Attach listener to ALL select elements within the itinerary card
        document.querySelectorAll(".card select").forEach(select => {
          select.addEventListener("change", function () {
            // Enable the Edit button
            if (editButton.disabled) {
              editButton.disabled = false;
            }

            // If a city is selected, update the corresponding hotel select options
            if (this.classList.contains("city-select")) {
              const index = this.dataset.index;
              const selectedCity = this.value;

              const hotelSelect = document.getElementById(`hotel${parseInt(index) + 1}`);
              if (hotelSelect) {
                updateHotelOptions(selectedCity, hotelSelect);
              }
            }

            // Re-run live data update after every change
            updateLiveItineraryData();
          });
        });

        // Initial run to capture default state
        updateLiveItineraryData();
      }

      function updateHotelOptions(selectedCity, hotelSelect) {
        const hotelData = {
          "Seoul": ["Lotte Hotel Seoul", "Signiel Seoul", "The Shilla Seoul", "Grand Hyatt Seoul", "InterContinental Seoul COEX"],
          "Busan": ["Park Hyatt Busan", "Paradise Hotel Busan"],
          "Jeonju": ["Lahan Hotel Jeonju"],
          "Jeju": ["Maison Glad Jeju", "Ramada Plaza Jeju"]
        };

        const hotels = hotelData[selectedCity] || [];
        hotelSelect.innerHTML = hotels.length ? "" : "<option disabled selected>No hotels available</option>";

        hotels.forEach(hotel => {
          const option = document.createElement("option");
          option.value = hotel;
          option.textContent = hotel;
          hotelSelect.appendChild(option);
        });
      }

      // Event listener for days selection change
      selectDays.addEventListener("change", function () {
        const selectedDays = parseInt(selectDays.value);
        generateItineraryCards(selectedDays);

        // Re-attach listeners after generating cards
        setTimeout(() => {
          attachSelectChangeListeners();
        }, 0);
      });

      // Initialize itinerary on page load if selectedValue is greater than 0
      if (selectedValue > 0) {
        generateItineraryCards(selectedValue);

        setTimeout(() => {
          attachSelectChangeListeners(); // Use the shared function
        }, 0);
      }

    });
  </script>

  <!-- Edit Script -->
  <script>
    const submitButton = document.getElementById("submitEdit");
    submitButton.disabled = true; // Keep disabled on load

    function proceedWithSubmission() {
      if (typeof liveItineraryData === "undefined") {
        alert("No itinerary data found.");
        return;
      }

      submitButton.disabled = true; // Prevent multiple submissions

      console.log("Sending the following liveItineraryData:", liveItineraryData);

      $.ajax({
        url: "../Employee Section/functions/emp-editItinerary.php",
        type: "POST",
        data: {
          itinerary: JSON.stringify(liveItineraryData)
        },
        dataType: "json",
        success: function (response) {
          if (response.status === "success") {
            alert("Itinerary successfully edited!");
            window.location.href = "../Employee Section/emp-itinerarytable.php";
          } else {
            alert("Error: " + response.message);
            submitButton.disabled = false; // Re-enable on failure
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", error);
          console.error("Response Text:", xhr.responseText);
          alert("An error occurred while editing the itinerary.");
          submitButton.disabled = false; // Re-enable on error
        }
      });
    }

    document.getElementById("submitEdit").addEventListener("click", proceedWithSubmission);
  </script>

  <!-- Generate Itinerary File -->
  <script>
    $('#submitTour').click(function () {
      const $submitTourBtn = $(this);

      // Check if itinerary data is loaded
      if (typeof liveItineraryData === 'undefined' || !liveItineraryData.itineraryDetails) {
        alert('Itinerary data is not loaded.');
        return;
      }

      const itineraryDetails = liveItineraryData.itineraryDetails;
      const daysDetails = liveItineraryData.daysDetails;
      const itineraryId = itineraryDetails.itineraryId || '';
      const itineraryName = itineraryDetails.itineraryName || 'Untitled_Itinerary';
      const format = $('#actionSelector').val(); // Get selected format: xlsx, pdf, both

      // Validate itineraryId
      if (!itineraryId) {
        alert('Itinerary ID is missing from the data.');
        return;
      }

      // Disable button and show loading state
      $submitTourBtn.prop('disabled', true).text('Generating...');

      // Handle generation based on selected format
      if (format === 'xlsx' || format === 'pdf') {
        generateItinerary(itineraryDetails, daysDetails, itineraryId, itineraryName, format, function () {
          $submitTourBtn.prop('disabled', false).text('Generate Itinerary');
        });
      } else if (format === 'both') {
        // Generate both formats sequentially (xlsx, then pdf)
        generateItinerary(itineraryDetails, daysDetails, itineraryId, itineraryName, 'xlsx', function () {
          generateItinerary(itineraryDetails, daysDetails, itineraryId, itineraryName, 'pdf', function () {
            $submitTourBtn.prop('disabled', false).text('Generate Itinerary');
          });
        });
      }
    });

    // Function to generate the itinerary file (XLSX or PDF)
    function generateItinerary(itineraryDetails, daysDetails, itineraryId, itineraryName, format, callback) {
      $.ajax({
        url: '../Employee Section/functions/itinerary-template-excel.php',
        type: 'POST',
        data: {
          itineraryDetails: JSON.stringify(itineraryDetails),
          daysDetails: JSON.stringify(daysDetails),
          itineraryId: itineraryId,
          format: format
        },
        xhrFields: { responseType: 'blob' },
        success: function (blobResponse) {
          // Determine file extension and MIME type based on format
          const fileExtension = format === 'pdf' ? 'pdf' : 'xlsx';
          const mimeType = fileExtension === 'pdf'
            ? 'application/pdf'
            : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

          // Create a Blob object from the response
          const blob = new Blob([blobResponse], { type: mimeType });

          // Create a link to trigger file download
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = `Itinerary_${itineraryName}.${fileExtension}`;

          // Append the link to the document and trigger click to start download
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);

          // Log success and call callback function if provided
          console.log(`${fileExtension.toUpperCase()} file generated successfully.`);
          if (typeof callback === 'function') callback();
        },
        error: function () {
          // Handle error during file generation
          alert('Failed to generate the itinerary file. Please try again.');
          if (typeof callback === 'function') callback();
        }
      });
    }
  </script>


  <!-- JS Script for JSON (Array) console.log -->
  <!-- <script>
        document.addEventListener("change", function(event) {
            if (event.target.matches(".area-select, .hotel-select, .meal-plan-select, .itinerary-select")) {
                const day = event.target.dataset.day;

                if (!day) {
                    console.warn("data-day attribute is missing!");
                    return;
                }

                // Get ALL selected values for the specific day
                const selectedAreas = [...document.querySelectorAll(`.area-select[data-day="${day}"]`)]
                    .map(a => a.value || "None");
                const selectedMealPlans = [...document.querySelectorAll(`.meal-plan-select[data-day="${day}"]`)]
                    .map(m => m.value || "None");
                const selectedHotels = [...document.querySelectorAll(`.hotel-select[data-day="${day}"]`)]
                    .map(h => h.value || "None");
                const selectedItineraries = [...document.querySelectorAll(`.itinerary-select[data-day="${day}"]`)]
                    .map(i => i.value || "None");

                console.log(JSON.stringify({
                    Day: day,
                    Areas: selectedAreas,
                    MealPlans: selectedMealPlans,
                    Hotels: selectedHotels,
                    Itineraries: selectedItineraries
                }, null, 2));
            }
        });
    </script> -->

</body>

</html>