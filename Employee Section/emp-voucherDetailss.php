<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Itinerary Details</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-editVoucher.css?v=<?php echo time(); ?>">

  <!-- WickedPicker CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/wickedpicker@0.4.3/dist/wickedpicker.min.css">

  <!-- WickedPicker JS -->
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

    <!-- Redirect Button Script -->
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
    
    $sql = "
        SELECT 
            v.voucherId,
            v.voucherName,
            v.voucherCode,
            v.accountId,
            v.itineraryId,
            v.createdAt AS voucherCreatedAt,
            d.sentTo,
            d.sentFrom,
            d.tourType,
            d.attachment,
            d.tourPeriodStart,
            d.tourPeriodEnd,
            d.guideId,
            d.noOfPax,
            e.countryCode AS employeeCountryCode,
            e.contactNo AS employeeContactNo
        FROM vouchers v
        LEFT JOIN voucherDetails d ON v.voucherId = d.voucherId
        LEFT JOIN employee e ON v.accountId = e.accountId
        WHERE v.voucherId = ?
    ";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
      die("Voucher not found");
    }

 

    // Combine voucherDetails contact
    $contactFullDetail = '';

    // if (!empty($row['detailCountryCode']) && !empty($row['detailContactNo'])) {
    //   $contactFullDetail = trim($row['detailCountryCode']) . ' ' . trim($row['detailContactNo']);
    // } 

    if (!empty($row['detailContactNo'])) {
      $contactFullDetail = trim($row['detailContactNo']);
    } elseif (!empty($row['detailCountryCode'])) {
      $contactFullDetail = trim($row['detailCountryCode']);
    }


    // Combine employee contact
    $contactFullEmployee = '';
    if (!empty($row['employeeCountryCode']) && !empty($row['employeeContactNo'])) {
      $contactFullEmployee = trim($row['employeeCountryCode']) . ' ' . trim($row['employeeContactNo']);
    } elseif (!empty($row['employeeContactNo'])) {
      $contactFullEmployee = trim($row['employeeContactNo']);
    } elseif (!empty($row['employeeCountryCode'])) {
      $contactFullEmployee = trim($row['employeeCountryCode']);
    }

    $voucher = [
      'voucherId' => $row['voucherId'] ?? null,
      'voucherName' => $row['voucherName'] ?? null,
      'voucherCode' => $row['voucherCode'] ?? null,
      'accountId' => $row['accountId'] ?? null,
      'itineraryId' => !empty($row['itineraryId']) ? $row['itineraryId'] : 0,
      'voucherCreatedAt' => $row['voucherCreatedAt'] ?? null,
      'details' => [
        'sentTo' => $row['sentTo'] ?? null,
        'sentFrom' => $row['sentFrom'] ?? null,
        'tourType' => $row['tourType'] ?? null,
        'attachment' => $row['attachment'] ?? null,
        'tourPeriodStart' => $row['tourPeriodStart'] ?? null,
        'tourPeriodEnd' => $row['tourPeriodEnd'] ?? null,
        'noOfPax' => $row['noOfPax'] ?? null,
        'guideName' => $row['guideId'] ?? null,
        'contact' => $contactFullDetail ?? null,
        'employeeContact' => $contactFullEmployee ?? null,
      ],
      'dateAndHotels' => [],
      'includes' => [],
      'excludes' => [],
      'airSchedules' => [],
      'guideMeeting' => []
    ];

    // Step 2: Fetch voucherDateAndHotels
    $sqlDates = "
        SELECT startDate, endDate, nights, city, hotel
        FROM voucherDateHotels
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
        SELECT i.includesId, i.itemName 
        FROM voucherIncludes vi
        INNER JOIN voucherIncludeOptions i ON vi.includeItemId = i.includesId
        WHERE vi.voucherId = ?
    ";

    $stmt = $conn->prepare($sqlIncludes);
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    $includesCounter = 1;
    while ($row = $result->fetch_assoc()) {
        $voucher['includes']["includes{$includesCounter}"] = [
            'value' => (string) $row['includesId'],
            'label' => ($row['includesId'] === 'others') ? $row['itemName'] : ''
        ];
        $includesCounter++;
    }


    // Step 4: Fetch voucherExcludes
    $sqlExcludes = "
        SELECT e.excludesId, e.itemName 
        FROM voucherExcludes ve
        INNER JOIN voucherExcludeOptions e ON ve.excludeItemId = e.excludesId
        WHERE ve.voucherId = ?
    ";

    $stmt = $conn->prepare($sqlExcludes);
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    $excludesCounter = 1;
    while ($row = $result->fetch_assoc()) {
        $voucher['excludes']["excludes{$excludesCounter}"] = [
            'value' => (string) $row['excludesId'],
            'label' => ($row['excludesId'] === 'others') ? $row['itemName'] : ''
        ];
        $excludesCounter++;
    }

    // Step 5: Fetch Air Schedules
    $sqlAirSchedules = "
      SELECT 
        flightSegment, 
        flightDate, 
        flightNumber, 
        origin, 
        destination, 
        departureTime, 
        arrivalTime 
      FROM voucherAirSchedules
      WHERE voucherId = ?
      ORDER BY FIELD(flightSegment, 'departure1', 'departure2'), flightDate ASC
    ";

    $stmt = $conn->prepare($sqlAirSchedules);
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $voucher['airSchedules'][] = $row;
    }

    // Step 6: Fetch guideMeeting(s)
    $sqlGuideMeeting = "
      SELECT guideId, meetingDate, meetingTime, meetingPlace
      FROM voucherguidemeeting
      WHERE voucherId = ?
    ";

    $stmt = $conn->prepare($sqlGuideMeeting);
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $voucher['guideMeeting'][] = $row;
    }

    // Output to browser console as JSON using JSON.stringify
    $jsonData = json_encode($voucher, JSON_UNESCAPED_UNICODE);
    echo "<script>console.log(JSON.stringify(" . $jsonData . ", null, 2));</script>";
    ?>

    <div class="main-content">
      <input type="hidden" id="itineraryId" value="<?= htmlspecialchars($itineraryId); ?>" readonly>

      <div class="form-container">

      <form id="formDetailSubmit" class="d-flex flex-column gap-3">

        <!-- Voucher Details -->
        <div class="card">
      
          <div class="card-body">

            <!-- Row 1: Voucher Name & Code -->
            <div class="row g-3 mb-3">
              <div class="col-12 col-md-6 col-lg-4">
                <label for="itineraryName" class="form-label">Voucher Name</label>
                <input type="text" class="form-control" id="itineraryName" name="itineraryName"
                  value="<?= !empty($voucher['voucherName']) ? htmlspecialchars($voucher['voucherName'], ENT_QUOTES) : 'Untitled Voucher' ?>"
                  readonly>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label for="voucherCode" class="form-label">Voucher Code</label>
                <input type="text" class="form-control" id="voucherCode" name="voucherCode"
                  value="<?= htmlspecialchars($voucher['voucherCode'], ENT_QUOTES); ?>" readonly>
              </div>
            </div>

          </div>
        </div>

        <div class="card">

          <div class="card-header border-bottom">
            <h5 class="mb-0">Voucher Details</h5>
          </div>

          <div class="card-body">

            <!-- Row 1: To, From, Package -->
            <div class="row g-3 mb-3">

              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">To</label>
                <select class="form-select" id="voucherTo" name="voucherTo" required>
                  <option value="" disabled <?= empty($voucher['details']['sentTo']) ? 'selected' : '' ?>>Select Branch
                  </option>

                  <?php
                  $selectedTo = $voucher['details']['sentTo'] ?? '';

                  $sql1 = "SELECT branchId, branchName FROM branch ORDER BY branchName ASC";
                  $res1 = $conn->query($sql1);

                  if ($res1->num_rows > 0) {
                    while ($row = $res1->fetch_assoc()) {
                      $branchName = htmlspecialchars($row['branchName'], ENT_QUOTES);
                      $isSelected = ($branchName === $selectedTo) ? 'selected' : '';
                      echo "<option value='$branchName' $isSelected>$branchName</option>";
                    }

                  } else {
                    echo "<option value=''>No branches available</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">From</label>
                <select class="form-select" name="sentFrom" id="sentFrom">
                  <option value="" disabled <?= empty($voucher['details']['sentFrom']) ? 'selected' : '' ?>>Select Sender
                  </option>
                  <option value="Agency A" <?= $voucher['details']['sentFrom'] == 'Agency A' ? 'selected' : '' ?>>Agency A
                  </option>
                  <option value="Agency B" <?= $voucher['details']['sentFrom'] == 'Agency B' ? 'selected' : '' ?>>Agency B
                  </option>
                  <!-- Add more options as needed -->
                </select>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label for="packageSelect" class="form-label">Package</label>
                <select class="form-select" id="packageSelect" name="packageSelect" required>
                  <option value="" disabled>Select a package</option>
                  <?php
                  $sql1 = "SELECT packageId, packageName FROM package ORDER BY packageId ASC";
                  $res1 = $conn->query($sql1);

                  if ($res1->num_rows > 0) {
                    while ($row = $res1->fetch_assoc()) {
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

            <!-- Row 2: Dates, Guide, Pax -->
            <div class="row g-3">
              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">Tour Periods <span class="text-danger">*</span></label>
                <div class="d-flex align-items-center gap-2">
                  <!-- Start Date -->
                  <div class="input-with-icon position-relative w-100">
                    <input type="text" class="form-control pe-5 datepicker" id="PeriodStartDate" placeholder="Start"
                      value="<?= htmlspecialchars($voucher['details']['tourPeriodStart']) ?>" readonly>
                    <i class="fas fa-calendar-alt calendar-icon"></i>
                  </div>
                  <span class="mx-1">→</span>
                  <!-- End Date -->
                  <div class="input-with-icon position-relative w-100">
                    <input type="text" class="form-control pe-5 datepicker" id="PeriodEndDate" placeholder="End"
                      value="<?= htmlspecialchars($voucher['details']['tourPeriodEnd']) ?>">
                    <i class="fas fa-calendar-alt calendar-icon"></i>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label for="guideName" class="form-label">Guide <span class="text-danger">*</span></label>
                <select class="form-select" id="guideName" name="guideName" required>
                  <option disabled <?= empty($voucher['details']['guideName']) ? 'selected' : '' ?>>Select Guide</option>
                  <?php
                  // Current guide is stored as employee ID
                  $currentGuideId = trim($voucher['details']['guideName'] ?? '');

                  $sql = "SELECT id, fName, mName, lName FROM employee WHERE branch = 'Korea'";
                  $result = $conn->query($sql);

                  if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $employeeId = htmlspecialchars($row['id']);
                      $fullName = trim($row['fName'] . ' ' . $row['mName'] . ' ' . $row['lName']);
                      $fullNameEscaped = htmlspecialchars($fullName);
                      // Compare selected guide by employee id, cast to string for safe comparison
                      $isSelected = ($currentGuideId === (string) $row['id']) ? 'selected' : '';
                      echo "<option value=\"$employeeId\" $isSelected>$fullNameEscaped</option>";
                    }
                  } else {
                    echo "<option disabled>No guides available</option>";
                  }
                  ?>
                </select>
              </div>


              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">No. of Pax</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($voucher['details']['noOfPax']); ?>"
                  readonly>
              </div>
            </div>

          </div>
        </div>


        <!-- Air Schedule -->
        <div class="card">
          <div class="card-header">
            <h5 class="fw-bold mb-0">Air Schedule</h5>
          </div>

          <div class="card-body">

            <!-- Departure #1 -->
            <div class="row mb-3">
              <div class="col-12 mb-2">
                <h6 class="fw-semibold text-uppercase text-muted border-bottom pb-1">Departure Flight</h6>
              </div>

              <div class="col-md-6 col-lg-3 mb-3">
                <label for="arrivalDate" class="form-label">Date</label>
                <div class="input-with-icon">
                  <input type="text" class="datepicker" id="arrivalDate" name="arrivalDate" placeholder="Arrival Date"
                    value="2025-05-19" readonly>
                  <i class="fas fa-calendar-alt calendar-icon"></i>
                </div>
              </div>

              <div class="col-md-6 col-lg-3 mb-3">
                <label for="arrivalFlight" class="form-label">Flight</label>
                <select class="form-select" id="arrivalFlight" name="arrivalFlight" required>
                  <option disabled>Select Flight</option>
                  <option value="KE123" selected>KE123</option>
                  <option value="OZ456">OZ456</option>
                  <option value="JL789">JL789</option>
                </select>
              </div>

              <div class="col-md-12 col-lg-6 mb-3">
                <label class="form-label">Origin - Destination</label>
                <div class="d-flex flex-column flex-sm-row align-items-center gap-2">
                  <!-- Origin -->
                  <select class="form-select" id="arrivalOrigin" name="arrivalOrigin" required>
                    <option disabled>Origin</option>
                    <option value="MNL" selected>Manila</option>
                    <option value="ICN">Incheon</option>
                    <option value="NRT">Narita</option>
                    <option value="LAX">Los Angeles</option>
                  </select>

                  <!-- Arrow Separator -->
                  <div class="text-muted fw-bold fs-5 d-flex align-items-center justify-content-center">→</div>

                  <!-- Destination -->
                  <select class="form-select" id="arrivalDestination" name="arrivalDestination" required>
                    <option disabled>Destination</option>
                    <option value="MNL">Manila</option>
                    <option value="CEB">Cebu</option>
                    <option value="BKK">Bangkok</option>
                    <option value="ICN" selected>Incheon</option>
                  </select>
                </div>
              </div>


              <div class="col-md-12 col-lg-6 mb-3">
                <label for="arrivalTimeStart" class="form-label">Departure - Arrival Time</label>

                <div class="d-flex flex-column flex-sm-row gap-2">
                  <div class="input-with-icon">
                    <input type="text" class="form-control timepicker" id="arrivalTimeStart" name="arrivalTimeStart"
                      value="12:30:00" placeholder="Departure Time" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>

                  <!-- Arrow Separator -->
                  <div class="text-muted fw-bold fs-5 d-flex align-items-center justify-content-center">→</div>

                  <div class="input-with-icon">
                    <input type="text" class="form-control timepicker" id="arrivalTimeEnd" name="arrivalTimeEnd"
                      value="12:30:00" placeholder="Arrival Time" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                </div>

              </div>
            </div>

            <!-- Departure #2 -->
            <div class="row">
              <div class="col-12 mb-2">
                <h6 class="fw-semibold text-uppercase text-muted border-bottom pb-1">Returning Flight</h6>
              </div>

              <div class="col-md-6 col-lg-3 mb-3">
                <label for="destinationDate" class="form-label">Date <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                  <input type="text" class="form-control datepicker" id="destinationDate" name="destinationDate"
                    value="2025-05-26" placeholder="Destination Date" readonly>
                  <i class="fas fa-calendar-alt calendar-icon"></i>
                </div>
              </div>

              <div class="col-md-6 col-lg-3 mb-3">
                <label for="destinationFlight" class="form-label">Flight <span class="text-danger">*</span></label>
                <select class="form-select" id="destinationFlight" name="destinationFlight" required>
                  <option disabled>Select Flight</option>
                  <option value="KE321" selected>KE321</option>
                  <option value="OZ654">OZ654</option>
                  <option value="JL987">JL987</option>
                </select>
              </div>

              <div class="col-md-12 col-lg-6 mb-3">
                <label class="form-label">Origin - Destination <span class="text-danger">*</span></label>
                <div class="d-flex flex-column flex-sm-row gap-2">
                  <select class="form-select" id="destinationOrigin" name="destinationOrigin" required>
                    <option disabled>Origin</option>
                    <option value="ICN" selected>Incheon</option>
                    <option value="MNL">Manila</option>
                    <option value="CEB">Cebu</option>
                    <option value="BKK">Bangkok</option>
                  </select>
                  <!-- Arrow Separator -->
                  <div class="text-muted fw-bold fs-5 d-flex align-items-center justify-content-center">→</div>
                  <select class="form-select" id="destinationArrival" name="destinationArrival" required>
                    <option disabled>Destination</option>
                    <option value="MNL" selected>Manila</option>
                    <option value="ICN">Incheon</option>
                    <option value="NRT">Narita</option>
                    <option value="LAX">Los Angeles</option>
                  </select>
                </div>
              </div>

              <div class="col-md-12 col-lg-6 mb-3">
                <label for="departureTimeStart" class="form-label">Departure - Arrival Time</label>
                <div class="d-flex flex-column flex-sm-row gap-2">
                  <div class="input-with-icon">
                    <input type="text" class="form-control timepicker" id="departureTimeStart" name="departureTimeStart"
                      value="12:00:00" placeholder="Departure Time" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                  <!-- Arrow Separator -->
                  <div class="text-muted fw-bold fs-5 d-flex align-items-center justify-content-center">→</div>
                  <div class="input-with-icon">
                    <input type="text" class="form-control timepicker" id="departureTimeEnd" name="departureTimeEnd"
                      value="12:00:00" placeholder="Arrival Time" readonly required>
                    <i class="fas fa-clock calendar-icon"></i>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Date and Hotels -->
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Date and Hotels</h5>
            <button id="addDateHotelBtn" type="button" class="btn btn-sm btn-primary">Add Date & Hotel</button>
          </div>
          <div class="card-body">
            <div id="dateHotelContainer"></div>
          </div>
        </div>

        <!-- Includes Card -->
        <div class="card includes-header-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="text-white mb-0">Includes</h5>
            <button id="addIncludeBtn" class="btn btn-sm btn-light">
              <i class="fas fa-plus"></i>
            </button>
          </div>
          <div class="card-body" >
            <div id="includesContainer"></div>
          </div>
        </div>

        <!-- Excludes Card -->
        <div class="card excludes-header-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="text-white mb-0">Excludes</h5>
            <button id="addExcludeBtn" class="btn btn-sm btn-light">
              <i class="fas fa-plus"></i>
            </button>
          </div>
          <div class="card-body">
            <div id="excludesContainer">
              <!-- JS will generate .col-md-12 blocks here -->
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

        <button type="button" class="btn btn-primary" id="submitVoucher">Generate Itinerary</button>

        </form>
      </div>


    </div>

  </div>
 

  <!-- Modal - For Template Name -->
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
    function initFlatpickrFields() {
      // Initialize date pickers
      document.querySelectorAll("input.datepicker:not(.flatpickr-initialized)").forEach(function (element) {
        const value = element.value.trim();
        // Skip if the value is non-date or contains alphabetic characters (e.g., "TEST 1")
        if (value && isNaN(Date.parse(value))) return;

        flatpickr(element, {
          dateFormat: "Y-m-d",
          minDate: "today",
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
        element.classList.add("flatpickr-initialized");
      });

      // Initialize time pickers
      document.querySelectorAll("input.timepicker:not(.flatpickr-initialized)").forEach(function (element) {
        const value = element.value.trim();
        if (value && !/^\d{1,2}:\d{2}$/.test(value)) return;

        flatpickr(element, {
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
        element.classList.add("flatpickr-initialized");
      });
    }

  </script>

  <!-- <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="fw-bold mb-0">Date and Hotels</h5>
      <button id="addDateHotelBtn" type="button" class="btn btn-sm btn-primary">Add Date & Hotel</button>
    </div>

    <div class="card-body" id="dateHotelContainer">

    </div>
  </div> -->

  <!-- Date and Hotels Script -->
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('dateHotelContainer');
    const addBtn = document.getElementById('addDateHotelBtn');
    const MAX_CARDS = 3;

    const originalData = <?= json_encode($voucher['dateAndHotels'] ?? [], JSON_UNESCAPED_UNICODE); ?>;
    const hotels = <?= json_encode($hotels ?? [], JSON_UNESCAPED_UNICODE); ?>;

    const voucherDateAndHotelsData = [...originalData];

    console.log("Initial Voucher Data:", voucherDateAndHotelsData);
    console.log("Hotels Data:", hotels);

    function renderAll() {
      const container = document.querySelector('.card-body #dateHotelContainer'); // Adapted selector
      const addBtn = document.getElementById('addDateHotelBtn');
      container.innerHTML = '';

      const cities = [...new Set(hotels.map(h => h.hotelCity))];

      voucherDateAndHotelsData.forEach((item, index) => {
        const num = index + 1;
        const filteredHotels = hotels.filter(h => h.hotelCity === item.city);

        const card = document.createElement('div');
        card.className = 'mb-4 date-hotel-card p-3 border rounded';
        card.dataset.index = index;

        console.log(`Rendering Card #${num}`, item);

        card.innerHTML = `
          <div class="d-flex justify-content-between align-items-center border-bottom pb-1 pt-1 mb-3">
            <h6 class="fw-semibold text-uppercase text-muted mb-0">Date and Hotels #${num}</h6>
            <button type="button" class="btn btn-danger btn-sm btn-delete-datehotel"
              title="Delete this entry" style="padding: 0.25rem 0.4rem; font-size: 1rem; line-height: 1;">
              &times;
            </button>
          </div>

          <div class="row g-3 align-items-end">
            <!-- Start & End Dates -->
            <div class="col-12 col-md-5">
              <label class="form-label">Date</label>
              <div class="d-flex gap-2 align-items-center">
                <div class="position-relative w-100">
                  <input type="text" class="form-control datepicker" id="PeriodStartDate${num}"
                    placeholder="Start" value="${item.startDate || ''}" readonly>
                  <i class="fas fa-calendar-alt position-absolute text-muted"
                    style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                </div>
                <span class="text-muted">→</span>
                <div class="position-relative w-100">
                  <input type="text" class="form-control datepicker" id="PeriodEndDate${num}"
                    placeholder="End" value="${item.endDate || ''}" readonly>
                  <i class="fas fa-calendar-alt position-absolute text-muted"
                    style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                </div>
              </div>
            </div>

            <!-- Nights -->
            <div class="col-6 col-md-2">
              <label for="nights${num}" class="form-label">No. of Nights</label>
              <input type="text" class="form-control" id="nights${num}" name="nights${num}" 
                value="${item.nights || ''}" readonly>
            </div>

            <!-- City -->
            <div class="col-6 col-md-2">
              <label for="city${num}" class="form-label">City</label>
              <select class="form-select city-select" id="city${num}" name="city${num}">
                <option value="">Select City</option>
                ${cities.map(city => `
                  <option value="${city}" ${item.city === city ? 'selected' : ''}>${city}</option>
                `).join('')}
              </select>
            </div>

            <!-- Hotel -->
            <div class="col-12 col-md-3">
              <label for="hotel${num}" class="form-label">Hotel</label>
              <select class="form-select hotel-select" id="hotel${num}" name="hotel${num}">
                <option value="">Select Hotel</option>
                ${filteredHotels.map(h => `
                  <option value="${h.hotelName}" ${item.hotel === h.hotelName ? 'selected' : ''}>${h.hotelName}</option>
                `).join('')}
              </select>
            </div>
          </div>
        `;

        container.appendChild(card);
      });

      // After rendering, rebind UI logic
      bindDeleteEvents();
      initializeDatepickers();
      addBtn.disabled = voucherDateAndHotelsData.length >= MAX_CARDS;
    }



    function bindDeleteEvents() {
      container.querySelectorAll('.btn-delete-datehotel').forEach(button => {
        button.onclick = () => {
          const idx = Number(button.closest('.date-hotel-card').dataset.index);
          voucherDateAndHotelsData.splice(idx, 1);
          console.log("Deleted index:", idx);
          renderAll();
        };
      });
    }

    function addNewDateHotel() {
      if (voucherDateAndHotelsData.length >= MAX_CARDS) return;

      let nextItem = originalData.find(orig =>
        !voucherDateAndHotelsData.some(curr => JSON.stringify(curr) === JSON.stringify(orig))
      ) || {};

      console.log("Adding new item:", nextItem);
      voucherDateAndHotelsData.push(nextItem);
      renderAll();
    }

    function initializeDatepickers() {
      document.querySelectorAll('.datepicker').forEach(input => {
        if (input._flatpickr) input._flatpickr.destroy();
        flatpickr(input, {
          dateFormat: "Y-m-d",
          minDate: "today",
          disableMobile: true
        });
      });
    }

    addBtn.addEventListener('click', addNewDateHotel);
    renderAll();
  });
  </script>

  <!-- Inject dynamic includes JSON -->
  <script>
    let includesData = <?php echo json_encode($voucher['includes'], JSON_PRETTY_PRINT); ?>;
  </script>

  <!-- Includes Fetch Script -->
  <script>
    let maxIncludes = 4;

    function renderIncludes() {
      const container = document.getElementById('includesContainer');
      container.innerHTML = '';

      const keys = Object.keys(includesData).sort((a, b) => {
        return Number(a.replace('includes', '')) - Number(b.replace('includes', ''));
      });

      keys.forEach((key, idx) => {
        const index = idx + 1;
        const data = includesData[key];
        const row = createIncludeRow(index, data);
        container.appendChild(row);
      });

      updateDisabledOptions();
    }

    // Create one row DOM element for Includes section
    function createIncludeRow(index, data) {
      const row = document.createElement('div');
      row.className = 'include-row mb-3';
      row.setAttribute('data-index', index);
      row.innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-1">
        <label for="includesSelect${index}">Includes ${index}:</label>
        <button type="button" class="btn btn-danger btn-sm remove-btn" title="Remove Includes ${index}" style="font-size: 1rem; line-height: 1;">
          &times;
        </button>

      </div>
      <select id="includesSelect${index}" name="includesSelect${index}" class="form-select" required>
        <option value="" disabled ${!data.value ? 'selected' : ''}>Select Include</option>
        <option value="1">Hotel (4 nights with twin or triple sharing)</option>
        <option value="2">Meals (4 times Lunch, 4 times Dinner)</option>
        <option value="3">(Coach, Van), Admission as the itinerary, ENGLISH guide, etc.</option>
        <option value="4">Airport Pick-up and Drop-off</option>
        <option value="5">Souvenir Pack</option>
        <option value="6">Travel Insurance</option>
        <option value="others">Others</option>
        <option value="0">— No Includes —</option>
      </select>
      <input type="text" class="form-control mt-2 custom-input ${data.value === 'others' ? '' : 'd-none'}" placeholder="Please specify..." value="${data.label || ''}">
    `;

      // Select and input elements for event handlers
      const select = row.querySelector('select');
      const customInput = row.querySelector('input.custom-input');
      const removeBtn = row.querySelector('.remove-btn');

      // Set select value
      select.value = data.value;

      // Event: When select changes
      select.addEventListener('change', () => {
        if (select.value === 'others') {
          customInput.classList.remove('d-none');
        } else {
          customInput.classList.add('d-none');
          customInput.value = '';
        }
        updateDataFromUI();
        updateDisabledOptions();
      });

      // Event: When user types in custom input
      customInput.addEventListener('input', () => {
        updateDataFromUI();
      });

      // Event: Remove row
      removeBtn.addEventListener('click', () => {
        delete includesData[`includes${index}`];
        reindexData(includesData, 'includes');
        renderIncludes();
      });

      return row;
    }

    // Sync includesData JSON with current UI selects and inputs
    function updateDataFromUI() {
      includesData = {};
      const rows = document.querySelectorAll('.include-row');
      rows.forEach((row, i) => {
        const idx = i + 1;
        const select = row.querySelector('select');
        const customInput = row.querySelector('input.custom-input');
        const val = select.value;
        const label = val === 'others' ? customInput.value.trim() : '';

        includesData[`includes${idx}`] = { value: val, label: label };
      });
    }

    // Re-index the keys of includesData after deletion to keep sequential keys
    function reindexData(dataObj, prefix) {
      const values = Object.values(dataObj);
      includesData = {}; // reset global object
      values.forEach((val, idx) => {
        includesData[`${prefix}${idx + 1}`] = val;
      });
    }

    // Disable options in other selects if already selected to avoid duplicates (except "others" and "0")
    function updateDisabledOptions() {
      const selectedVals = Object.values(includesData).map(d => d.value).filter(v => v !== '' && v !== 'others' && v !== '0');

      document.querySelectorAll('.include-row select').forEach(select => {
        const currentVal = select.value;
        select.querySelectorAll('option').forEach(opt => {
          if (opt.value === '' || opt.value === 'others' || opt.value === '0') {
            opt.disabled = false;
            return;
          }
          opt.disabled = selectedVals.includes(opt.value) && opt.value !== currentVal;
        });
      });
    }

    // Add new include row on button click
    document.getElementById('addIncludeBtn').addEventListener('click', () => {
      const count = Object.keys(includesData).length;
      if (count >= maxIncludes) {
        alert(`You can add maximum ${maxIncludes} Includes.`);
        return;
      }
      includesData[`includes${count + 1}`] = { value: '', label: '' };
      renderIncludes();
    });

    // Initial render on page load
    document.addEventListener('DOMContentLoaded', () => {
      renderIncludes();
    });
  </script>

  <!-- Inject dynamic excludes JSON from PHP -->
  <script>
    let excludesData = <?php echo json_encode($voucher['excludes'], JSON_PRETTY_PRINT); ?>;
  </script>

  <!-- EXCLUDES -->
  <script>
    let maxExcludes = 4;

    function renderExcludes() {
      const container = document.getElementById('excludesContainer');
      container.innerHTML = '';

      const keys = Object.keys(excludesData).sort((a, b) => {
        return Number(a.replace('excludes', '')) - Number(b.replace('excludes', ''));
      });

      keys.forEach((key, idx) => {
        const index = idx + 1;
        const data = excludesData[key];
        const row = createExcludeRow(index, data);
        container.appendChild(row);
      });

      updateDisabledExcludeOptions();
    }

    function createExcludeRow(index, data) {
      const row = document.createElement('div');
      row.className = 'exclude-row mb-3';
      row.setAttribute('data-index', index);
      row.innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-1">
        <label for="excludesSelect${index}">Excludes ${index}:</label>
        <button type="button" class="btn btn-danger btn-sm remove-btn" title="Remove Includes ${index}" style="font-size: 1rem; line-height: 1;">
          &times;
        </button>

      </div>
      <select id="excludesSelect${index}" name="excludesSelect${index}" class="form-select" required>
        <option value="" disabled ${!data.value ? 'selected' : ''}>Select Exclude</option>
        <option value="1">Personal expenses</option>
        <option value="2">Visa Fees</option>
        <option value="3">Optional Tours</option>
        <option value="4">Tips and Gratuities</option>
        <option value="others">Others</option>
        <option value="0">— No Excludes —</option>
      </select>
      <input type="text" class="form-control mt-2 custom-input ${data.value === 'others' ? '' : 'd-none'}" placeholder="Please specify..." value="${data.label || ''}">
    `;

      const select = row.querySelector('select');
      const customInput = row.querySelector('input.custom-input');
      const removeBtn = row.querySelector('.remove-btn');

      select.value = data.value;

      select.addEventListener('change', () => {
        if (select.value === 'others') {
          customInput.classList.remove('d-none');
        } else {
          customInput.classList.add('d-none');
          customInput.value = '';
        }
        updateExcludesDataFromUI();
        updateDisabledExcludeOptions();
      });

      customInput.addEventListener('input', () => {
        updateExcludesDataFromUI();
      });

      removeBtn.addEventListener('click', () => {
        delete excludesData[`excludes${index}`];
        reindexData(excludesData, 'excludes');
        renderExcludes();
      });

      return row;
    }

    function updateExcludesDataFromUI() {
      excludesData = {};
      const rows = document.querySelectorAll('.exclude-row');
      rows.forEach((row, i) => {
        const idx = i + 1;
        const select = row.querySelector('select');
        const customInput = row.querySelector('input.custom-input');
        const val = select.value;
        const label = val === 'others' ? customInput.value.trim() : '';
        excludesData[`excludes${idx}`] = { value: val, label: label };
      });
    }

    function updateDisabledExcludeOptions() {
      const selectedVals = Object.values(excludesData).map(d => d.value).filter(v => v !== '' && v !== 'others' && v !== '0');

      document.querySelectorAll('.exclude-row select').forEach(select => {
        const currentVal = select.value;
        select.querySelectorAll('option').forEach(opt => {
          if (opt.value === '' || opt.value === 'others' || opt.value === '0') {
            opt.disabled = false;
            return;
          }
          opt.disabled = selectedVals.includes(opt.value) && opt.value !== currentVal;
        });
      });
    }

    document.getElementById('addExcludeBtn').addEventListener('click', () => {
      const count = Object.keys(excludesData).length;
      if (count >= maxExcludes) {
        alert(`You can add maximum ${maxExcludes} Excludes.`);
        return;
      }
      excludesData[`excludes${count + 1}`] = { value: '', label: '' };
      renderExcludes();
    });

    document.addEventListener('DOMContentLoaded', () => {
      renderExcludes();
    });
  </script>

  <!-- Generate Voucher File -->
  <script>
    $(document).ready(function () {
      $('#submitVoucher').on('click', function () {
        const $btn = $(this);

        // Retrieve voucher data from PHP
        const voucherData = <?php echo json_encode($voucher, JSON_UNESCAPED_UNICODE); ?>;
        if (!voucherData || typeof voucherData !== 'object') {
          alert('Invalid voucher data.');
          return;
        }

        const voucherId = voucherData.voucherId || '';
        const voucherName = voucherData.voucherName || 'Untitled_Voucher';
        const fileFormat = 'xlsx';

        // Disable button while generating
        $btn.prop('disabled', true).text('Generating...');

        // Trigger file generation
        generateVoucherFile(voucherData, voucherId, voucherName, fileFormat, () => {
          $btn.prop('disabled', false).text('Generate Voucher');
        });
      });

      function generateVoucherFile(voucher, id, name, format, callback) {
        $.ajax({
          url: '../Employee Section/functions/voucher-template-excel.php',
          type: 'POST',
          data: {
            voucher: JSON.stringify(voucher),
            voucherId: id,
            format: format
          },
          xhrFields: { responseType: 'blob' },
          success: function (responseBlob) {
            const fileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            const fileExt = 'xlsx';
            const fileName = `Voucher_${name}.${fileExt}`;

            const blob = new Blob([responseBlob], { type: fileType });
            const downloadLink = document.createElement('a');
            downloadLink.href = URL.createObjectURL(blob);
            downloadLink.download = fileName;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            console.log(`${fileExt.toUpperCase()} file generated: ${fileName}`);
            if (typeof callback === 'function') callback();
          },
          error: function (xhr, status, error) {
            console.error('Error:', status, error);
            alert('Failed to generate the voucher file. Please try again.');
            if (typeof callback === 'function') callback();
          }
        });
      }
    });
  </script>

















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