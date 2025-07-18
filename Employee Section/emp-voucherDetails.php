<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Voucher Details</title>

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
    
    $sql = "SELECT 
            v.voucherId,
            v.voucherName,
            v.voucherCode,
            v.accountId,
            v.itineraryId,
            v.flightId,
            v.createdAt AS voucherCreatedAt,

            d.sentToId,
            d.sentToName,
            d.sentFrom,
            d.tourType,
            d.attachment,
            d.tourPeriodStart,
            d.tourPeriodEnd,
            d.guideId,
            d.noOfPax,

            e.countryCode AS employeeCountryCode,
            e.contactNo AS employeeContactNo,

            a.emailAddress AS accountEmail,
            a.accountType AS accountRole,
            a.accountStatus AS accountStatus

        FROM vouchers v
        LEFT JOIN voucherDetails d ON v.voucherId = d.voucherId
        LEFT JOIN employee e ON v.accountId = e.accountId
        LEFT JOIN accounts a ON v.accountId = a.accountId
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
      'flightId' => !empty($row['flightId']) ? $row['flightId'] : 0,
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
        SELECT startDate, endDate, NoOfNights AS nights, city, hotel
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
        TIME_FORMAT(departureTime, '%H:%i') AS departureTime, 
        TIME_FORMAT(arrivalTime, '%H:%i') AS arrivalTime
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
      SELECT 
        meetingId,
        voucherId,
        guideId,
        meetingDate,
        meetingTime,
        meetingPlace
      FROM voucherguidemeeting
      WHERE voucherId = ?
    ";

    $stmt = $conn->prepare($sqlGuideMeeting);
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $voucher['guideMeeting'][] = [
        'meetingId' => $row['meetingId'],
        'voucherId' => $row['voucherId'],
        'guideId' => $row['guideId'],
        'meetingDate' => $row['meetingDate'],
        'meetingTime' => $row['meetingTime'],
        'meetingPlace' => $row['meetingPlace']
      ];
    }

    // Output to browser console as JSON using JSON.stringify
    $jsonData = json_encode($voucher, JSON_UNESCAPED_UNICODE);
    echo "<script>console.log(JSON.stringify(" . $jsonData . ", null, 2));</script>";

    ?>

    <div class="main-content">
      <div class="form-container">

        <form id="voucherDetailSubmit" class="d-flex flex-column gap-3">
          <input type="hidden" id="itineraryId" value="<?= htmlspecialchars($itineraryId); ?>" readonly>

          <!-- To/From -->
          <div class="card">

            <div class="card-body">

              <div class="row">

                <!-- Voucher Name -->
                <div class="columns col-md-3">
                  <label>Voucher Name<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="voucherName" name="itineraryName"
                    value="<?= !empty($voucher['voucherName']) ? htmlspecialchars($voucher['voucherName'], ENT_QUOTES) : 'Untitled Voucher' ?>"
                    readonly>
                </div>

                <!-- Voucher Code -->
                <div class="columns col-md-3">
                  <label>Voucher Code<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="voucherCode" name="voucherCode"
                    value="<?= htmlspecialchars($voucher['voucherCode'], ENT_QUOTES); ?>" readonly>
                </div>

                <!-- Associated to Itinerary -->
                <div class="columns col-md-3">
                  <label>Associated to Itinerary</label>
                  <select class="form-select" id="itinerarySelect" name="itineraryId">
                    <option value="" disabled <?= empty($selectedItineraryId) ? 'selected' : '' ?>>Select Itinerary
                    </option>


                    <?php
                    $selectedItineraryId = $voucher['itineraryId'] ?? '';

                    $sql = "SELECT itineraryId, itineraryName FROM itineraries ORDER BY createdAt DESC";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $id = $row['itineraryId'];
                        $name = htmlspecialchars($row['itineraryName']);
                        $isSelected = ($id == $selectedItineraryId) ? 'selected' : '';
                        echo "<option value='$id' $isSelected>$name - ID: $id</option>";
                      }
                    } else {
                      echo "<option value=''>No itineraries available</option>";
                    }
                    ?>
                  </select>
                </div>

                <!-- Associated to Flight -->
                <div class="col-md-3" id="flightSelectWrapper">
                  <label for="flightId" class="form-label">Associated to Flight<span
                      class="text-danger">*</span></label>
                  <select class="form-select" id="flightId" name="flightId">
                    <option value="" disabled <?= empty($voucher['flightId']) ? 'selected' : '' ?>>Select Flight</option>

                    <?php
                    $selectedFlightId = $voucher['flightId'] ?? '';

                    $query = "SELECT * FROM flight WHERE is_active = 1";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0):
                      while ($row = $result->fetch_assoc()):
                        $flightId = htmlspecialchars($row['flightId']);
                        $flightCode = htmlspecialchars($row['flightCode']);
                        $flightName = htmlspecialchars($row['flightName']);
                        $departureDate = htmlspecialchars($row['flightDepartureDate']);

                        $flightDataJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                        // Check if this flight is selected
                        $isSelected = ($selectedFlightId == $flightId) ? 'selected' : '';
                        ?>
                        <option value="<?= $flightId ?>" data-flight='<?= $flightDataJson ?>' <?= $isSelected ?>>
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

              </div>

            </div>
          </div>

          <!-- Connect to Itinerary -->
          <!-- <div class="card">
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
                    // $sql = "SELECT itineraryId, itineraryName FROM itineraries ORDER BY createdAt DESC";
                    // $result = $conn->query($sql);
                    // if ($result && $result->num_rows > 0) {
                    //   while ($row = $result->fetch_assoc()) {
                    //     echo "<option value='" . $row['itineraryId'] . "'>" . htmlspecialchars($row['itineraryName']) . "</option>";
                    //   }
                    // } else {
                    //   echo "<option value=''>No itineraries available</option>";
                    // }
                    ?>
                  </select>

                </div>

              </div>

            </div>
          </div> -->

          <!-- Voucher Details Card -->
          <div class="card">
            <div class="card-header">
              <h5>Voucher Details</h5>
            </div>

            <div class="card-body">

              <div class="row">

                <!-- To -->
                <div class="columns col-md-4">
                  <label>To <span class="text-danger">*</span></label>

                  <select class="form-select" id="voucherTo" name="voucherTo" required>
                    <option value="" disabled>Select Branch</option>

                    <?php
                    // Ensure $voucher['details']['sentTo'] is available
                    $sentToValue = $voucher['details']['sentTo'] ?? '';

                    $sql1 = "SELECT branchId, branchName FROM branch ORDER BY branchName ASC";
                    $res1 = $conn->query($sql1);

                    if ($res1->num_rows > 0) {
                      while ($row = $res1->fetch_assoc()) {
                        $selected = ($row['branchName'] === $sentToValue) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['branchName']) . "' $selected>" . htmlspecialchars($row['branchName']) . "</option>";
                      }
                    } else {
                      echo "<option value=''>No branches available</option>";
                    }
                    ?>
                  </select>
                </div>

                <!-- From --> <!-- Smart Travel by default -->
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
                  <input type="text" class="form-control" id="voucherPaxCount" name="voucherPaxCount" required
                    value="<?= htmlspecialchars($voucher['details']['noOfPax'] ?? '') ?>">
                </div>


              </div>

              <!-- First Row -->
              <div class="row">

                <!-- Tour Type -->
                <div class="columns col-md-6">
                  <label for="voucherTour">Tour <span class="text-danger">*</span></label>
                  <select class="form-select" id="voucherTour" name="voucherTour" required>
                    <option selected disabled value="">Select Package Type</option>

                    <?php
                    $selectedTourType = $voucher['details']['tourType'] ?? '';

                    $sql1 = "SELECT packageId, packageName FROM package ORDER BY packageName ASC";
                    $res1 = $conn->query($sql1);

                    if ($res1->num_rows > 0) {
                      while ($row = $res1->fetch_assoc()) {
                        $isSelected = ($row['packageId'] == $selectedTourType) ? "selected" : "";
                        echo "<option value='" . $row['packageId'] . "' $isSelected>" . htmlspecialchars($row['packageName']) . "</option>";
                      }
                    } else {
                      echo "<option value=''>No packages available</option>";
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

                    <!-- Start Date -->
                    <div class="input-with-icon me-2">
                      <input type="text" class="datepicker form-control" id="voucherPeriodStart"
                        name="voucherPeriodStart" placeholder="Start" readonly
                        value="<?= isset($voucher['details']['tourPeriodStart']) ? htmlspecialchars($voucher['details']['tourPeriodStart']) : '' ?>">
                      <i class="fas fa-calendar-alt calendar-icon"></i>
                    </div>

                    <span class="mx-2">→</span>

                    <!-- End Date -->
                    <div class="input-with-icon">
                      <input type="text" class="datepicker form-control" id="voucherPeriodEnd" name="voucherPeriodEnd"
                        placeholder="End" readonly
                        value="<?= isset($voucher['details']['tourPeriodEnd']) ? htmlspecialchars($voucher['details']['tourPeriodEnd']) : '' ?>">
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
                    // Guide preselect from voucher JSON if available
                    $selectedGuide = $voucher['details']['guideName'] ?? null;

                    $sql = "
                      SELECT 
                        accountId AS guideAccountId, 
                        fName, mName, lName, 
                        contactNo, countryCode 
                      FROM employee 
                      WHERE isTourGuide = 1 
                      ORDER BY lName, fName ASC
                    ";
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

                        // Check if this guide is selected
                        $selected = ($selectedGuide == $guideAccountId) ? 'selected' : '';

                        echo "<option 
                                value='$guideAccountId' 
                                data-name='$displayName' 
                                data-contact='$contactNo' 
                                data-code='$countryCode'
                                $selected>
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

          <!-- Date and Hotels -->
          <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="fw-bold mb-0">Date and Hotels</h5>
              <button id="addDateHotelBtn" type="button"
                class="add-button btn btn-primary add-exclude-button">+</button>
            </div>

            <div class="card-body">
              <div id="dateHotelContainer"></div>
            </div>

          </div>

          <!-- Air Schedule -->
          <div class="card">
            <div class="card-header">
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

              // Remove duplicates
              $departureCodes = array_unique($departureCodes);
              $returnCodes = array_unique($returnCodes);
            }
            ?>

            <div class="card-body">
              <!-- Departure #1 -->
              <div class="row">
                <div class="main-header">
                  <div class="header-container">
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="dnh-badge text-uppercase">Departure Flight</span>
                    </div>
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

              <!-- Returning Flight -->
              <div class="row">
                <div class="main-header">
                  <div class="header-container">
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="dnh-badge text-uppercase">Returning Flight</span>
                    </div>
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

      <!-- Form footer with both buttons -->
      <div class="form-footer">
        <button type="button" class="btn btn-primary" id="submitEdit">Submit Edit</button>

        <select id="actionSelector" class="form-select" style="width: 120px;">
          <option value="xlsx" selected>Excel (.xlsx)</option>
          <option value="pdf" disabled>PDF</option>
          <option value="both" disabled>Excel and PDF </option>
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

  <!-- PHP Fetch of Area and Hotel Values -->
  <?php
  $cityOptions = [];
  $cityHotelMap = [];

  $sql = "
      SELECT a.areaId, a.areaName AS hotelCity, h.hotelName
      FROM itineraryDataHotels d
      JOIN itineraryDataArea a ON a.areaId = d.areaId
      JOIN hotels h ON h.hotelId = d.hotelId
    ";

  $result = $conn->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $areaId = $row['areaId'];
      $cityName = $row['hotelCity'];
      $hotelName = $row['hotelName'];

      if (!isset($cityOptions[$areaId])) {
        $cityOptions[$areaId] = $cityName;
      }

      $cityHotelMap[$areaId][] = $hotelName;
    }
  }
  ?>

  <!-- Global variable -->
  <script>
    let voucherDateAndHotelsData = [];
    let cities = [];
    let hotelsList = [];

    // Renders all existing data cards
    function renderAll() {
      const container = document.getElementById('dateHotelContainer');

      const maxCards = 3;

      container.innerHTML = '';

      voucherDateAndHotelsData.forEach((item, index) => {
        const num = index + 1;
        const isFirstCard = num === 1;
        const selectedCityId = item.city;
        const selectedHotelId = item.hotel;

        const filteredHotels = hotelsList.filter(h => h.hotelCity == selectedCityId);

        const card = document.createElement('div');
        card.className = 'mb-4 date-hotel-card';
        card.setAttribute('data-card-id', num);

        card.innerHTML = `
          <div class="header-container">
            <div class="d-flex justify-content-between align-items-center">
              <span class="dnh-badge text-uppercase">Date and Hotels #${num}</span>
              <button type="button" class="btn btn-sm text-white bg-danger border-0 px-2 py-1 btn-delete-datehotel" title="Delete">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>

          <div class="row g-4 align-items-end">
            <div class="col-12 col-md-5">
              <label class="form-label">Date</label>
              <div class="d-flex gap-2 align-items-center">
                <div class="position-relative w-100">
                  <input type="text" class="form-control datepicker" id="PeriodStartDate${num}" value="${item.startDate || ''}" placeholder="Start" readonly>
                  <input type="text" class="required-start hidden-required-field" name="requiredStartDate${num}" ${isFirstCard ? 'required' : ''} style="position:absolute; left:-9999px; width:1px; height:1px; opacity:0;" value="${item.startDate || ''}">
                </div>
                
                <span class="mx-1 text-muted">→</span>
                <div class="position-relative w-100">
                  <input type="text" class="form-control datepicker" id="PeriodEndDate${num}" value="${item.endDate || ''}" placeholder="End" readonly>
                  <input type="text" class="required-end hidden-required-field" name="requiredEndDate${num}" ${isFirstCard ? 'required' : ''} style="position:absolute; left:-9999px; width:1px; height:1px; opacity:0;" value="${item.endDate || ''}">
                </div>
              </div>
            </div>

            <div class="col-6 col-md-2">
              <label class="form-label" for="nights${num}">No. of Nights</label>
              <input type="text" class="form-control" id="nights${num}" name="nights${num}" value="${item.nights || ''}" ${isFirstCard ? 'required' : ''}>
            </div>

            <div class="col-6 col-md-2">
              <label class="form-label" for="city${num}">City</label>
              <select class="form-control city-select" id="city${num}" name="city${num}" ${isFirstCard ? 'required' : ''}>
                <option value="" disabled ${!selectedCityId ? 'selected' : ''}>Select City</option>
                ${cities.map(city => `
                  <option value="${city.areaId}" ${city.areaId == selectedCityId ? 'selected' : ''}>${city.areaName}</option>
                `).join('')}
              </select>
            </div>

            <div class="col-12 col-md-3">
              <label class="form-label" for="hotel${num}">Hotel</label>
              <select class="form-control hotel-select" id="hotel${num}" name="hotel${num}" ${isFirstCard ? 'required' : ''} ${!selectedCityId ? 'disabled' : ''}>
                <option value="" disabled ${!selectedHotelId ? 'selected' : ''}>Select Hotel</option>
                ${hotelsList.filter(h => h.areaId == selectedCityId)
            .map(h => `<option value="${h.hotelId}" ${h.hotelId == selectedHotelId ? 'selected' : ''}>${h.hotelName}</option>`)}
              </select>
            </div>
          </div>
        `;

        container.appendChild(card);

        // Inputs setup
        const startInput = document.getElementById(`PeriodStartDate${num}`);
        const endInput = document.getElementById(`PeriodEndDate${num}`);
        const nightsInput = document.getElementById(`nights${num}`);
        const citySelect = document.getElementById(`city${num}`);
        const hotelSelect = document.getElementById(`hotel${num}`);
        const hiddenStart = document.querySelector(`[name="requiredStartDate${num}"]`);
        const hiddenEnd = document.querySelector(`[name="requiredEndDate${num}"]`);

        function calculateNights() {
          const start = new Date(startInput.value);
          const end = new Date(endInput.value);
          if (start && end && end > start) {
            nightsInput.value = Math.round((end - start) / (1000 * 60 * 60 * 24));
          } else {
            nightsInput.value = "";
          }
        }

        flatpickr(startInput, {
          dateFormat: "Y-m-d",
          // minDate: "today",
          disableMobile: true,
          defaultDate: item.startDate || null,
          onChange: (_, dateStr) => {
            if (hiddenStart) hiddenStart.value = dateStr;
            calculateNights();
          }
        });

        flatpickr(endInput, {
          dateFormat: "Y-m-d",
          // minDate: "today",
          disableMobile: true,
          defaultDate: item.endDate || null,
          onChange: (_, dateStr) => {
            if (hiddenEnd) hiddenEnd.value = dateStr;
            calculateNights();
          }
        });

        citySelect.addEventListener('change', (e) => {
          const selectedCityId = e.target.value;
          item.city = selectedCityId;
          const hotelsForCity = hotelsList.filter(h => h.areaId == selectedCityId);
          hotelSelect.innerHTML = `<option value="" disabled selected>Select Hotel</option>` +
            hotelsForCity.map(h => `<option value="${h.hotelId}">${h.hotelName}</option>`).join('');
          hotelSelect.disabled = false;
          item.hotel = '';
        });

        hotelSelect.addEventListener('change', () => {
          item.hotel = hotelSelect.value;
          console.log("✅ Updated JSON:", JSON.stringify(voucherDateAndHotelsData, null, 2));
        });

        card.querySelector('.btn-delete-datehotel').addEventListener('click', () => {
          if (num === 1) {
            startInput._flatpickr.clear();
            endInput._flatpickr.clear();
            nightsInput.value = "";
            citySelect.value = "";
            hotelSelect.innerHTML = `<option value="" disabled selected>Select Hotel</option>`;
            hotelSelect.disabled = true;
            hiddenStart.value = "";
            hiddenEnd.value = "";
          } else {
            voucherDateAndHotelsData.splice(index, 1);
            renderAll();
          }
        });
      });

      // Button state
      const addBtn = document.getElementById('addDateHotelBtn');
      if (voucherDateAndHotelsData.length >= maxCards) {
        addBtn.classList.add('btn-disabled');
        addBtn.setAttribute('data-locked', 'true');
      } else {
        addBtn.classList.remove('btn-disabled');
        addBtn.removeAttribute('data-locked');
      }
    }

    // Function to add new card
    function addNewDateHotel() {
      const maxCards = 3;
      if (voucherDateAndHotelsData.length >= maxCards) return;
      voucherDateAndHotelsData.push({
        startDate: '',
        endDate: '',
        nights: '',
        city: '',
        hotel: ''
      });
      renderAll();
      updateLiveVoucherData(); // to remove
    }

    // DOM Ready
    document.addEventListener('DOMContentLoaded', () => {
      const originalData = <?= json_encode($voucher['dateAndHotels'] ?? [], JSON_UNESCAPED_UNICODE); ?>;
      voucherDateAndHotelsData = [...originalData];

      fetch('../Employee Section/functions/fetchScripts/getHotels.php')
        .then(response => {
          if (!response.ok) throw new Error(`Network error: ${response.status}`);
          return response.json();
        })
        .then(data => {
          cities = data.cities || [];
          hotelsList = data.hotels || [];
          renderAll();

          document.getElementById('addDateHotelBtn').addEventListener('click', addNewDateHotel);
        })
        .catch(err => {
          console.error('❌ Failed to fetch hotels:', err);
          alert('Error loading hotel data');
        });
    });
  </script>

  <!-- Air Schedule Script -->
  <script>
    // Inject the PHP airSchedules array into JavaScript
    const airSchedules = <?= json_encode($voucher['airSchedules'] ?? [], JSON_UNESCAPED_UNICODE); ?>;

    // Function to populate air schedule form fields
    function populateAirScheduleFields(data = []) {
      data.forEach(schedule => {
        const {
          flightSegment,
          flightDate,
          flightNumber,
          origin,
          destination,
          departureTime,
          arrivalTime
        } = schedule;

        if (flightSegment === "departureFlight") {
          document.getElementById("departure1Date").value = flightDate;
          document.getElementById("departure1Flight").value = flightNumber;
          document.getElementById("departure1Origin").value = origin;
          document.getElementById("departure1Destination").value = destination;
          document.getElementById("departure1DepartureTime").value = departureTime;
          document.getElementById("departure1ArrivalTime").value = arrivalTime;
        }

        if (flightSegment === "returningFlight") {
          document.getElementById("departure2Date").value = flightDate;
          document.getElementById("departure2Flight").value = flightNumber;
          document.getElementById("departure2Origin").value = origin;
          document.getElementById("departure2Destination").value = destination;
          document.getElementById("departure2DepartureTime").value = departureTime;
          document.getElementById("departure2ArrivalTime").value = arrivalTime;
        }
      });
    }

    // Call this on page load
    document.addEventListener("DOMContentLoaded", () => {
      if (Array.isArray(airSchedules)) {
        populateAirScheduleFields(airSchedules);
      }
    });
  </script>

  <!-- Includes -->
  <script>
    let includesData = <?php echo json_encode($voucher['includes'], JSON_PRETTY_PRINT); ?>;
    let maxIncludes = 4;
    let includeOptions = [];
    let includeCount = 0;

    // Fetch options once
    async function fetchIncludeOptions() {
      const res = await fetch('../Employee Section/functions/fetchScripts/getIncludeOptions.php');
      includeOptions = await res.json();
      renderIncludes();
    }

    function renderIncludes() {
      const container = document.getElementById('includesContainer');
      container.innerHTML = '';

      const keys = Object.keys(includesData).sort((a, b) => {
        return Number(a.replace('includes', '')) - Number(b.replace('includes', ''));
      });

      includeCount = keys.length;

      keys.forEach((key, idx) => {
        const index = idx + 1;
        const data = includesData[key];
        const row = createIncludeRow(index, data);
        container.appendChild(row);
      });

      updateDisabledOptions();
    }

    function createIncludeRow(index, data) {
      const row = document.createElement('div');
      row.className = 'include-row mb-3';
      row.setAttribute('data-index', index);
      row.innerHTML = `
      <div class="d-flex justify-content-between align-items-center">
        <label for="includesSelect${index}">Includes ${index}:</label>
        <button type="button" class="btn btn-sm btn-danger remove-include" title="Remove">
          <i class="fas fa-trash-alt"></i>
        </button>
      </div>

      <select id="includesSelect${index}" name="includesSelect${index}" class="form-select include-select" required>
        <option value="" disabled ${!data.value ? 'selected' : ''}>Select Include</option>
      </select>
      <input type="text" class="form-control mt-2 custom-input ${data.value === 'others' ? '' : 'd-none'}" placeholder="Please specify..." value="${data.label || ''}">
    `;

      const select = row.querySelector('select');
      const customInput = row.querySelector('input.custom-input');
      const removeBtn = row.querySelector('.remove-include');

      // Populate options
      includeOptions.forEach(opt => {
        const option = document.createElement('option');
        option.value = opt.includesId;
        option.textContent = opt.itemName;
        select.appendChild(option);
      });

      // Set initial value
      if (data.value) {
        select.value = data.value;
        if (data.value === 'others') {
          customInput.classList.remove('d-none');
        }
      }

      // Event: select change
      select.addEventListener('change', () => {
        if (select.value === 'others') {
          customInput.classList.remove('d-none');
          customInput.focus();
        } else {
          customInput.classList.add('d-none');
          customInput.value = '';
        }
        updateDataFromUI();
        updateDisabledOptions();
      });

      // Event: input
      customInput.addEventListener('input', () => {
        updateDataFromUI();
      });

      // Event: remove
      removeBtn.addEventListener('click', () => {
        const rowIndex = parseInt(row.getAttribute('data-index'));
        if (rowIndex === 1 && includeCount === 1) {
          select.value = "";
          customInput.value = "";
          customInput.classList.add("d-none");

        } else {
          row.remove();
          includeCount--;
          updateIncludeLabels();
        }

        updateDataFromUI();
        updateDisabledOptions();

      });

      return row;
    }

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

    function updateDisabledOptions() {
      const selectedVals = Object.values(includesData)
        .map(d => d.value)
        .filter(v => v && v !== 'others');

      document.querySelectorAll('.include-row select').forEach(select => {
        const currentVal = select.value;
        select.querySelectorAll('option').forEach(opt => {
          if (opt.value === '' || opt.value === 'others') {
            opt.disabled = false;
          } else {
            opt.disabled = selectedVals.includes(opt.value) && opt.value !== currentVal;
          }
        });
      });
    }

    document.getElementById('addIncludeBtn').addEventListener('click', () => {
      const count = Object.keys(includesData).length;
      if (count >= maxIncludes) {
        alert(`You can add maximum ${maxIncludes} Includes.`);
        return;
      }

      includesData[`includes${count + 1}`] = { value: '', label: '' };
      includeCount++;
      renderIncludes();
    });

    document.addEventListener('DOMContentLoaded', fetchIncludeOptions);
  </script>

  <!-- Excludes -->
  <script>
    let excludesData = <?php echo json_encode($voucher['excludes'], JSON_PRETTY_PRINT); ?>;
    let maxExcludes = 4;
    let excludeOptions = [];
    let excludeCount = 0;

    // Fetch exclude options once
    async function fetchExcludeOptions() {
      const res = await fetch('../Employee Section/functions/fetchScripts/getExcludeOptions.php');
      excludeOptions = await res.json();

      // Add "Others" at the end
      excludeOptions.push({ excludesId: 'others', itemName: 'Others' });

      renderExcludes();
    }

    function renderExcludes() {
      const container = document.getElementById('excludesContainer');
      container.innerHTML = '';

      const keys = Object.keys(excludesData).sort((a, b) => {
        return Number(a.replace('excludes', '')) - Number(b.replace('excludes', ''));
      });

      excludeCount = keys.length;

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
        <button type="button" class="btn btn-sm btn-danger remove-exclude" title="Remove">
          <i class="fas fa-trash-alt"></i>
        </button>
      </div>

      <select id="excludesSelect${index}" name="excludesSelect${index}" class="form-select exclude-select" required>
        <option value="" disabled ${!data.value ? 'selected' : ''}>Select Exclude</option>
      </select>
      <input type="text" class="form-control mt-2 custom-input ${data.value === 'others' ? '' : 'd-none'}" placeholder="Please specify..." value="${data.label || ''}">
    `;

      const select = row.querySelector('select');
      const customInput = row.querySelector('input.custom-input');
      const removeBtn = row.querySelector('.remove-exclude');

      // Populate select
      excludeOptions.forEach(opt => {
        const option = document.createElement('option');
        option.value = opt.excludesId;
        option.textContent = opt.itemName;
        select.appendChild(option);
      });

      // Set selected value
      if (data.value) {
        select.value = data.value;
        if (data.value === 'others') {
          customInput.classList.remove('d-none');
        }
      }

      // Events
      select.addEventListener('change', () => {
        if (select.value === 'others') {
          customInput.classList.remove('d-none');
          customInput.focus();
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
        const rowIndex = parseInt(row.getAttribute('data-index'));
        if (rowIndex === 1 && excludeCount === 1) {
          select.value = "";
          customInput.value = "";
          customInput.classList.add("d-none");
        } else {
          row.remove();
          excludeCount--;
          updateExcludeLabels();
        }

        updateExcludesDataFromUI();
        updateDisabledExcludeOptions();
      });

      return row;
    }

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
      const selectedVals = Object.values(excludesData).map(d => d.value).filter(v => v && v !== 'others');

      document.querySelectorAll('.exclude-row select').forEach(select => {
        const currentVal = select.value;
        select.querySelectorAll('option').forEach(opt => {
          if (opt.value === '' || opt.value === 'others') {
            opt.disabled = false;
          } else {
            opt.disabled = selectedVals.includes(opt.value) && opt.value !== currentVal;
          }
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
      excludeCount++;
      renderExcludes();
    });

    document.addEventListener('DOMContentLoaded', fetchExcludeOptions);
  </script>


  <!-- Console.log -->
  <script>
    document.addEventListener('input', (e) => {
      if (e.target.matches('#voucherName, #voucherCode, .form-control, .form-select')) {
        updateLiveVoucherData();
      }
    }); 
  </script>



  <!-- Field Values JSON -->
  <script>
    const serverVoucherId = <?= json_encode($voucher['voucherId']) ?>;
    const serverAccountId = <?= json_encode($voucher['accountId']) ?>;

    // 🔄 Extract all date + hotel entries
    function extractDateAndHotelsJSON() {
      const data = [];
      const cards = document.querySelectorAll('.date-hotel-card');

      cards.forEach(card => {
        const num = card.getAttribute('data-card-id');

        const startDate = document.getElementById(`PeriodStartDate${num}`)?.value || '';
        const endDate = document.getElementById(`PeriodEndDate${num}`)?.value || '';
        const nights = document.getElementById(`nights${num}`)?.value || '';
        const city = document.getElementById(`city${num}`)?.value || '';
        const hotel = document.getElementById(`hotel${num}`)?.value || '';

        data.push({ startDate, endDate, nights, city, hotel });
      });

      return data;
    }

    // ✅ Build and return liveVoucherData with array-based includes/excludes
    function buildLiveVoucherData() {
      const liveVoucherData = {
        voucherId: serverVoucherId,
        voucherName: document.getElementById('voucherName')?.value || '',
        voucherCode: document.getElementById('voucherCode')?.value || '',
        accountId: serverAccountId,
        itineraryId: parseInt(document.getElementById('itinerarySelect')?.value) || null,
        flightId: parseInt(document.getElementById('flightId')?.value) || null,

        details: {
          sentTo: document.getElementById('voucherTo')?.value || null,
          sentFrom: document.getElementById('voucherFrom')?.value || '',
          noOfPax: parseInt(document.getElementById('voucherPaxCount')?.value) || 0,
          tourType: document.getElementById('voucherTour')?.value || '',
          tourPeriodStart: document.getElementById('voucherPeriodStart')?.value || '',
          tourPeriodEnd: document.getElementById('voucherPeriodEnd')?.value || '',
          guideId: parseInt(document.getElementById('guideSelect')?.value) || null,
          countryCode: document.getElementById('countryCode')?.value || '',
          contactNo: document.getElementById('contactNumber')?.value || ''
        },

        dateAndHotels: extractDateAndHotelsJSON(),

        // ✅ Ensure includes/excludes are passed as arrays (not objects)
        includes: Array.isArray(includesData) ? includesData : Object.values(includesData || {}),
        excludes: Array.isArray(excludesData) ? excludesData : Object.values(excludesData || {}),

        airSchedules: [
          {
            flightSegment: 'departureFlight',
            flightCode: document.getElementById('departure1Flight')?.value || '',
            flightDate: document.getElementById('departure1Date')?.value || '',
            flightNumber: document.getElementById('departure1Flight')?.value || '',
            origin: document.getElementById('departure1Origin')?.value || '',
            destination: document.getElementById('departure1Destination')?.value || '',
            departureTime: document.getElementById('departure1DepartureTime')?.value || '',
            arrivalTime: document.getElementById('departure1ArrivalTime')?.value || ''
          },
          {
            flightSegment: 'returningFlight',
            flightDate: document.getElementById('departure2Date')?.value || '',
            flightNumber: document.getElementById('departure2Flight')?.value || '',
            origin: document.getElementById('departure2Origin')?.value || '',
            destination: document.getElementById('departure2Destination')?.value || '',
            departureTime: document.getElementById('departure2DepartureTime')?.value || '',
            arrivalTime: document.getElementById('departure2ArrivalTime')?.value || ''
          }
        ],

        guideMeeting: [
          (function () {
            const arrivalTime = document.getElementById('departure1ArrivalTime')?.value || '';
            const arrivalDateRaw = document.getElementById('departure1Date')?.value || '';
            const departure1Destination = document.getElementById('departure1Destination')?.value || '';
            const guideId = parseInt(document.getElementById('guideSelect')?.value) || null;

            let finalMeetingDate = arrivalDateRaw;

            // Handle time overflow (e.g., "24:30")
            const timeParts = arrivalTime.split(':');
            const hour = parseInt(timeParts[0], 10);
            const minute = parseInt(timeParts[1] || '0', 10);

            if (!isNaN(hour) && hour >= 24 && arrivalDateRaw) {
              const originalDate = new Date(arrivalDateRaw);
              originalDate.setDate(originalDate.getDate() + 1);
              finalMeetingDate = originalDate.toISOString().split('T')[0];
            }

            // Map destination to readable meeting place
            const placeOptions = {
              'ICN': 'Incheon Airport (Terminal 1)',
              'Other': 'Custom Place'
            };
            const meetingPlace = placeOptions[departure1Destination] || 'Custom Place';

            return {
              voucherId: serverVoucherId,
              guideId,
              meetingTime: arrivalTime,
              meetingPlace,
              meetingDate: finalMeetingDate
            };
          })()
        ]

      };

      // ✅ Console logs preserved
      console.log('Final liveVoucherData:');
      console.log(JSON.stringify(liveVoucherData, null, 2));

      // console.log('📦 Payload size:', JSON.stringify(liveVoucherData).length, 'characters');

      return liveVoucherData;
    }

    function updateLiveVoucherData() {
      window.liveVoucherData = buildLiveVoucherData();
    }

    document.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        updateLiveVoucherData();
      }, 100);
    });
  </script>




  <!-- Generate Voucher File -->
  <script>
    $(document).ready(function () {
      // === Event Binding ===
      $('#submitEdit').on('click', handleVoucherSave);
      $('#submitVoucher').on('click', handleVoucherGenerate);

      // === Handler: Save Voucher ===
      function handleVoucherSave() {
        
        updateLiveVoucherData(); // builds and assigns window.liveVoucherData
        const voucherPayload = JSON.stringify(window.liveVoucherData);
        const templateName = <?= json_encode($voucher['voucherName']) ?>;

        console.log("📝 For Insertion:\n", voucherPayload);

        $.ajax({
          url: "../Employee Section/functions/emp-editVoucher.php",
          type: "POST",
          data: {
            voucherPayload,
            templateName
          },
          dataType: "json",
          success: function (response) {
            console.log("✅ Server Response:\n", JSON.stringify(response, null, 2));

            if (response.status === "success") {
              alert("Voucher saved successfully. Generating template...");
              // window.location.href = "../Employee Section/emp-Table.php";
            } else {
              alert("❌ Failed to save Voucher: \n" + (response.message || "Unknown error occurred."));
            }
          },
          error: function (xhr, status, error) {
            console.error("❌ AJAX Error:", error);
            console.error("📄 Response Text:\n", xhr.responseText);
            alert("❌ Server error. Please try again later.");
          }
        });
      }

      // === Handler: Generate Voucher Excel ===
      function handleVoucherGenerate() {
        const $btn = $('#submitVoucher');
        let voucherData;

        try {
          voucherData = buildLiveVoucherData();
        } catch (err) {
          console.error('❌ Failed to build voucher data:', err);
          alert('Error building voucher data. Please review your form inputs.');
          return;
        }

        if (!voucherData || typeof voucherData !== 'object') {
          alert('Invalid voucher data structure.');
          return;
        }

        const estimatedSize = JSON.stringify(voucherData).length;
        if (estimatedSize > 50000) {
          alert(`Voucher data too large (${estimatedSize} chars). Please simplify.`);
          return;
        }

        const voucherName = (voucherData.voucherName || 'Untitled_Voucher').replace(/\s+/g, '_');
        const fileFormat = 'xlsx';

        $btn.prop('disabled', true).text('Generating...');

        const formData = new FormData();
        formData.append('voucher', JSON.stringify(voucherData));
        formData.append('format', fileFormat);

        $.ajax({
          url: '../Employee Section/functions/voucher-template-excel.php',
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          xhrFields: { responseType: 'blob' },

          success: function (blob) {
            const fileName = `Voucher_${voucherName}.${fileFormat}`;
            const downloadBlob = new Blob([blob], {
              type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });

            const downloadLink = document.createElement('a');
            downloadLink.href = URL.createObjectURL(downloadBlob);
            downloadLink.download = fileName;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            console.log(`✅ File downloaded: ${fileName}`);
            console.log('📦 Sent voucherData:', voucherData);
          },

          error: function (xhr, status, error) {
            console.error('❌ Error generating voucher:', error);
            console.error('📩 Response:', xhr.responseText);
            alert('❌ Failed to generate the voucher. See console for technical info.');
          },

          complete: function () {
            $btn.prop('disabled', false).text('Generate Voucher');
          }
        });
      }

      // Optional: Make globally accessible
      window.proceedWithSubmission = proceedWithSubmission;
    });
  </script>








</body>

</html>