<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Itinerary Table</title>

  <?php include '../Employee Section/includes/emp-head.php' ?>

  <link rel="stylesheet" href="../Employee Section/assets/css/emp-voucherTable.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">


  <!-- WickedPicker CSS
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/wickedpicker@0.4.1/dist/wickedpicker.min.css"> -->

  <!-- WickedPicker JS -->
  <script src="https://cdn.jsdelivr.net/npm/wickedpicker@0.4.1/dist/wickedpicker.min.js"></script>
  </body>

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
            <h5 class="header-title">Itinerary</h5>
          </div>
        </div>

      </div>
    </div>

    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Employee Section/emp-dashboard.php'; // Replace with your actual URL
      });
    </script>

    <?php
    $statusTab = isset($_GET['status']) ? $_GET['status'] : '';
    ?>

    <div class="main-content">

      <div id="notification-banner" class="notification-banner">
        <span id="notification-message">Itinerary deleted successfully.</span>
        <div class="loader"></div>
      </div>

      <div class="table-container">

        <div class="second-div">
          <div class="navTabs-wrapper">
            <div class="nav-inner-wrapper">
              <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                    aria-selected="false">Itinerary Main Templates</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                    type="button" role="tab" aria-controls="pills-home" aria-selected="true">Created Itinerary
                    Templates</button>
                </li>
              </ul>
            </div>
          </div>

          <div class="content-heading">
            <div class="content-inner-wrapper">
              <button id="createItinerary" class="btn btn-primary">Create Itinerary</button>
            </div>
          </div>
        </div>

        <!-- Flight Seat Tracker Tab -->
        <div class="tab-content" id="pills-tabContent">

          <!-- Main Template Modals Tab -->
          <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
            tabindex="0">

            <!-- Main voucher content -->
            <div class="itinerary-grid">
              <?php
              $sql = "SELECT * FROM itineraries WHERE isMainTemplate = 1 ORDER BY createdAt DESC;";

              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $itineraryId = htmlspecialchars($row['itineraryId'] ?? '');
                  $packageName = htmlspecialchars($row['itineraryName'] ?? 'Untitled');
                  $createdAt = $row['createdAt'] ? (new DateTime($row['createdAt']))->format('F j, Y g:i A') : 'N/A';

                  // Determine an icon letter (e.g., "IT" for itinerary)
                  $iconLetter = strtoupper(substr($packageName, 0, 1));
                  ?>
                  <div class="itinerary-card" data-id="<?php echo $itineraryId; ?>">
                    <div class="card-content-wrap">

                      <!-- Header Section -->
                      <div class="it-card-header">

                        <div class="itinerary-info">
                          <div class="itinerary-name">
                            <h6><?php echo $packageName; ?></h6>
                          </div>

                          <div class="status-container">
                            <span class="badge-type">IT</span>
                          </div>
                        </div>

                        <!-- Dropdown Options -->
                        <div class="options dropdown">
                          <button class="btn dropdown-toggle p-0 border-0 bg-transparent" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                              <a class="dropdown-item create-template-btn" href="#" data-id="<?php echo $itineraryId; ?>"
                                data-name="<?php echo $packageName; ?>">
                                Create Quick Template
                              </a>
                            </li>

                          </ul>
                        </div>
                      </div>

                      <!-- Body Section -->
                      <div class="it-card-body">
                        <div class="itinerary-icon itinerary-bg-body"><?php echo $iconLetter; ?></div>
                      </div>

                      <!-- Footer Section (Placeholder for future content) -->
                      <!-- <div class="it-card-footer"></div> -->
                    </div>
                  </div>
                  <?php

                }

              } else {
                echo "<p class='no-records'>No Itinerary Templates Found.</p>";
              }

              ?>
            </div>

          </div>

          <!-- Created Itinerary Templates Tab -->
          <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

            <!-- Main voucher content -->
            <div class="itinerary-grid">
              <?php
              $sql = "SELECT * FROM itineraries WHERE isMainTemplate = 0 OR isMainTemplate IS NULL ORDER BY createdAt DESC;";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $itineraryId = htmlspecialchars($row['itineraryId'] ?? '');
                  $packageName = htmlspecialchars($row['itineraryName'] ?? 'Untitled');
                  $createdAt = $row['createdAt'] ? (new DateTime($row['createdAt']))->format('F j, Y g:i A') : 'N/A';

                  $iconLetter = strtoupper(substr($packageName, 0, 1));
                  ?>
                  <div class="itinerary-card" data-id="<?php echo $itineraryId; ?>">
                    <div class="card-content-wrap">
                      <!-- Header Section -->
                      <div class="it-card-header">
                        <div class="itinerary-info">
                          <div class="itinerary-name">
                            <h6><?php echo $packageName; ?></h6>
                          </div>
                          <div class="status-container">
                            <span class="badge-type">IT</span>
                          </div>
                        </div>

                        <!-- Dropdown Options -->
                        <div class="options dropdown">
                          <button class="btn dropdown-toggle p-0 border-0 bg-transparent" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                              <a class="dropdown-item text-danger delete-itinerary" href="#"
                                data-id="<?php echo $itineraryId; ?>" data-id="<?php echo $packageName; ?>">
                                Delete
                              </a>
                            </li>
                          </ul>
                        </div>
                      </div>

                      <!-- Body Section -->
                      <div class="it-card-body">
                        <div class="itinerary-icon itinerary-bg-body"><?php echo $iconLetter; ?></div>
                      </div>

                    </div>
                  </div>
                  <?php
                }
              } else {
                echo "<p class='no-records'>No itineraries found.</p>";
              }
              ?>
            </div>

          </div>

        </div>

        <!-- <div class="navpills-container">

          <ul class="filter-tabs nav nav-tabs" id="booking-filter-tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="filter-btn nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main"
                type="button" role="tab" aria-controls="main" aria-selected="true">
                Main Template Voucher
                <span class="badge-status-tab">
                  <h6>
                    <?php
                    $sql = "SELECT COUNT(*) AS totalBookings FROM itineraries;";
                    $result = mysqli_query($conn, $sql);
                    echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                    ?>
                  </h6>
                </span>
              </button>
            </li>

            <li class="nav-item" role="presentation">
              <button class="filter-btn nav-link" id="created-tab" data-bs-toggle="tab" data-bs-target="#created"
                type="button" role="tab" aria-controls="created" aria-selected="false">
                Created Voucher
                <span class="badge-status-tab">
                  <h6>
                    <?php
                    $sql = "SELECT COUNT(*) AS totalBookings FROM itineraries;";
                    $result = mysqli_query($conn, $sql);
                    echo ($result) ? mysqli_fetch_assoc($result)['totalBookings'] : 0;
                    ?>
                  </h6>
                </span>
              </button>
            </li>
          </ul>

          <div class="create-itinerary-wrapper">

          </div>
        </div>

        <div class="tab-content" id="bookingTabContent">
          <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">

          </div>

          <div class="tab-pane fade" id="created" role="tabpanel" aria-labelledby="created-tab">
            <p>This is the Created Voucher tab content.</p>
          </div>
        </div> -->
      </div>

    </div>
  </div>


  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="deleteConfirmLabel">
            Confirm Deletion <small class="text-light" id="itineraryIdLabel"></small>
          </h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this itinerary? This action cannot be undone.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Step 1: Template Copy Setup -->
  <div class="modal fade" id="copyTemplateSettingsModal" tabindex="-1" aria-labelledby="copyTemplateSettingsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content custom-modal">
        <div class="modal-header custom-header">
          <h6 class="modal-title" id="copyTemplateSettingsLabel">Create Quick Template</h6>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body custom-body">
          <form>
            <input type="hidden" id="templateItineraryId" name="itineraryId">

            <div class="row align-items-end mb-3">

              <!-- To -->
              <div class="col-md-4">
                <label for="voucherTo" class="custom-label mb-1">To:</label>
                <select class="form-select" id="voucherTo" name="voucherTo" required>
                  <option value="" disabled selected>Select Branch</option>
                  <?php
                  $sentToValue = $voucher['details']['sentTo'] ?? '';
                  $sql1 = "SELECT branchId, branchName FROM branch ORDER BY branchName ASC";
                  $res1 = $conn->query($sql1);
                  if ($res1->num_rows > 0) {
                    while ($row = $res1->fetch_assoc()) {
                      $selected = ($row['branchId'] == $sentToValue) ? 'selected' : '';
                      echo "<option value='" . htmlspecialchars($row['branchId']) . "' $selected>" . htmlspecialchars($row['branchName']) . "</option>";
                    }
                  } else {
                    echo "<option value=''>No branches available</option>";
                  }
                  ?>
                </select>
              </div>

              <!-- Period Picker -->
              <div class="col-md-8">
                <label class="custom-label mb-1">Period:</label>
                <div class="d-flex align-items-center gap-2">

                  <!-- Start Date -->
                  <div class="form-group mb-0 flex-grow-1 position-relative">
                    <input type="text" class="datepicker form-control" id="PeriodStartDate" placeholder="Start Date"
                      readonly required>
                    <i class="fas fa-calendar-alt calendar-icon position-absolute"
                      style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                  </div>

                  <!-- Arrow -->
                  <span class="fw-bold">→</span>

                  <!-- End Date -->
                  <div class="form-group mb-0 flex-grow-1 position-relative">
                    <input type="text" class="datepicker form-control" id="PeriodEndDate" placeholder="End Date"
                      readonly required>
                    <i class="fas fa-calendar-alt calendar-icon position-absolute"
                      style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                  </div>

                </div>
              </div>

            </div>


            <div class="row mb-3">

              <!-- Guide -->
              <div class="col-md-6">
                <label class="custom-label">Guide:</label>
                <select class="form-select" id="guideName" name="guideName" onchange="updateContact(this)">
                  <option value="" selected disabled>Select Guide</option>
                  <?php
                  $query = "SELECT accountId, fName, lName, mName, contactNo, countryCode FROM employee WHERE isTourGuide = 1";
                  $result = mysqli_query($conn, $query);
                  while ($row = mysqli_fetch_assoc($result)) {
                    $accountId = $row['accountId'];
                    $fName = $row['fName'];
                    $lName = $row['lName'];
                    $mName = $row['mName'];
                    $contactNo = $row['contactNo'];
                    $countryCode = $row['countryCode'];
                    $middleName = !empty($mName) ? $mName : '';
                    $fullName = trim(preg_replace('/\s+/', ' ', $fName . ' ' . $middleName . ' ' . $lName));
                    echo "<option value=\"$accountId\" data-accountid=\"$accountId\" data-contact=\"$contactNo\" data-code=\"$countryCode\">$fullName</option>";
                  }
                  ?>
                </select>
              </div>

              <!-- Contact Number -->
              <div class="col-md-6">
                <label class="custom-label">Contact Number:</label>
                <div class="form-group d-flex align-items-center">
                  <select class="form-select" id="countryCode" style="width: 80px;" disabled>
                    <option value="+63" selected>+63</option>
                    <option value="+82">+82</option>
                  </select>
                  <input type="text" class="form-control ms-2" id="contactNumber" name="contactNumber"
                    placeholder="9***********" disabled>
                </div>
              </div>

            </div>


            <div class="row mb-3 align-items-end custom-voucher-row">

              <!-- Voucher Toggle -->
              <div class="col-md-6">
                <label class="custom-label">Connect to Voucher:</label>

                <?php
                $sql = "SELECT voucherId, voucherCode, voucherName FROM vouchers WHERE itineraryId IS NULL OR itineraryId = 0 ORDER BY createdAt DESC";
                $result = $conn->query($sql);
                $hasVouchers = ($result && $result->num_rows > 0);
                ?>

                <div class="form-check">
                  <input type="hidden" name="isConnectToVoucher" value="false">
                  <input class="form-check-input" type="checkbox" id="toggleVoucherSelect" name="isConnectToVoucher"
                    value="true" <?= $hasVouchers ? '' : 'disabled' ?>>
                  <label class="form-check-label <?= $hasVouchers ? '' : 'text-muted' ?>" for="toggleVoucherSelect">
                    Connect this itinerary to an existing voucher
                  </label>
                </div>

                <?php if (!$hasVouchers): ?>
                  <small class="text-danger d-block mt-1">No available vouchers to connect.</small>
                <?php endif; ?>
              </div>

              <!-- Voucher Select -->
              <div class="col-md-6" id="voucherSelectWrapper" style="display: none;">
                <label for="voucherId" class="custom-label d-block mb-1">Select Voucher:</label>
                <select class="form-select w-100 mt-2 fs-6" id="voucherId" name="voucherId" <?= $hasVouchers ? '' : 'disabled' ?>>
                  <option value="" disabled selected>
                    <?= $hasVouchers ? 'Select Voucher' : 'No vouchers available' ?>
                  </option>
                  <?php if ($hasVouchers): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                      <option value="<?= htmlspecialchars($row['voucherId']) ?>">
                        <?= htmlspecialchars($row['voucherCode']) ?>  |  <?= htmlspecialchars($row['voucherName']) ?>
                      </option>
                    <?php endwhile; ?>
                  <?php endif; ?>
                </select>
              </div>
              
            </div>

          
        </div>

        <div class="modal-footer custom-footer">
          <button type="button" class="custom-btn secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="custom-btn primary" id="proceedToNameModal">Next</button>
        </div>

         </form>
      </div>
    </div>
  </div>


  <!-- JS: Toggle Voucher Select -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggle = document.getElementById('toggleVoucherSelect');
      const wrapper = document.getElementById('voucherSelectWrapper');
      if (toggle && !toggle.disabled) {
        toggle.addEventListener('change', () => {
          wrapper.style.display = toggle.checked ? 'block' : 'none';
        });
      }
    });

    function updateContact(selectElement) {
      const selectedOption = selectElement.options[selectElement.selectedIndex];
      document.getElementById('contactNumber').value = selectedOption.getAttribute('data-contact') || '';
      document.getElementById('countryCode').value = selectedOption.getAttribute('data-code') || '+63';
    }
  </script>


  <!-- Step 2: Template Name -->
  <div class="modal fade" id="copyTemplateNameModal" tabindex="-1" aria-labelledby="copyTemplateNameLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content custom-modal">
        <div class="modal-header custom-header">
          <h5 class="modal-title" id="copyTemplateNameLabel">Name Your Itinerary</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body custom-body">
          <div class="row mb-3">
            <div class="col">
              <label class="custom-label">Itinerary Name:</label>
              <input type="text" id="newItineraryName" class="custom-input" placeholder="Enter itinerary name">
              <small id="itineraryNameMessage" style="display: none;"></small>
            </div>
          </div>
        </div>
        <div class="modal-footer custom-footer">
          <button type="button" class="custom-btn secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="custom-btn success" id="submitTemplateCopy">Create Template</button>
        </div>
      </div>
    </div>
  </div>




  <!-- Flatpickr Initialization and Modal Handling -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let templateData = {};

      // ✅ Initialize Flatpickr on demand when modal is shown
      const initModalFlatpickrs = () => {
        console.log("Initializing Flatpickr...");

        const commonOptions = {
          dateFormat: "Y-m-d",
          disableMobile: true,
          appendTo: document.body,
          zIndex: 1055,
          onOpen: function () {
            requestAnimationFrame(() => {
              const calendar = document.querySelector(".flatpickr-calendar");
              if (calendar) {
                const inputRect = this.input.getBoundingClientRect();
                calendar.style.top = `${inputRect.bottom + window.scrollY + 8}px`;
                // console.log("Calendar repositioned:", inputRect);
              }
            });
          },
          onReady: function () {
            // Prevent Bootstrap modal from stealing focus on select inside calendar
            const calendar = document.querySelector(".flatpickr-calendar");
            if (calendar) {
              calendar.querySelectorAll("select").forEach(select => {
                select.addEventListener("mousedown", (e) => {
                  e.stopPropagation();
                  console.log("Stopped mousedown on select to prevent modal focus");
                });
              });
            }
          }
        };

        flatpickr("#PeriodStartDate", commonOptions);
        flatpickr("#PeriodEndDate", commonOptions);

        // Optional: Time picker
        flatpickr("input.timepicker", {
          enableTime: true,
          noCalendar: true,
          dateFormat: "H:i",
          time_24hr: true,
          disableMobile: true,
          appendTo: document.body,
          zIndex: 1055,
          onOpen: function () {
            requestAnimationFrame(() => {
              const calendar = document.querySelector(".flatpickr-calendar");
              if (calendar) {
                const inputRect = this.input.getBoundingClientRect();
                calendar.style.top = `${inputRect.bottom + window.scrollY + 8}px`;
              }
            });
          }
        });
      };



      // ✅ Open First Modal and Init Flatpickrs
      document.querySelectorAll('.create-template-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
          e.preventDefault();

          const itineraryId = this.getAttribute('data-id');
          document.getElementById('templateItineraryId').value = itineraryId;

          const modalEl = document.getElementById('copyTemplateSettingsModal');
          const modal = new bootstrap.Modal(modalEl, {
            focus: false // 🔒 Prevent modal from stealing focus
          });

          modal.show();

          modalEl.addEventListener('shown.bs.modal', function handleShown() {
            console.log("Modal shown: initializing Flatpickr...");
            initModalFlatpickrs(); // Ensure datepickers load after modal is visible
            modalEl.removeEventListener('shown.bs.modal', handleShown);
          });
        });
      });



      // ✅ Move to Second Modal
      document.getElementById('proceedToNameModal').addEventListener('click', function (e) {
        e.preventDefault(); // prevent form submission if inside <form>

        const userId = <?php echo json_encode($accountId ?? null); ?>;

        // Get values
        const itineraryId = document.getElementById('templateItineraryId').value;
        const to = document.getElementById('voucherTo').value;
        const startDate = document.getElementById('PeriodStartDate').value;
        const endDate = document.getElementById('PeriodEndDate').value;
        const guide = document.getElementById('guideName').value;
        const countryCode = document.getElementById('countryCode').value;
        const contactNo = document.getElementById('contactNumber').value;
        const isConnectToVoucher = document.getElementById('toggleVoucherSelect').checked;
        const voucherId = isConnectToVoucher ? document.getElementById('voucherId').value : null;

        console.log("Collected Fields:");
        console.log({ itineraryId, to, startDate, endDate, guide, countryCode, contactNo, voucherId });

        // Validation
        if (!to || !startDate || !endDate || !guide) {
          alert("Please fill in all required fields (To, Period, and Guide).");
          return;
        }

        // Save to object
        templateData = {
          userId: userId,
          itineraryId,
          to,
          periods: { start: startDate, end: endDate },
          guide,
          countryCode,
          contactNo,
          isConnectToVoucher,
          voucherId
        };


        console.log("✅ Template data ready:", templateData);

        // Hide current modal
        const currentModal = bootstrap.Modal.getInstance(document.getElementById('copyTemplateSettingsModal'));
        if (currentModal) currentModal.hide();

        // Show next modal
        const nextModal = new bootstrap.Modal(document.getElementById('copyTemplateNameModal'), {
          focus: false
        });
        nextModal.show();
      });



      document.getElementById('submitTemplateCopy').addEventListener('click', function () {
        const name = document.getElementById('newItineraryName').value;
        if (!name) {
          alert("Please provide an itinerary name.");
          return;
        }

        templateData.name = name;
        // console.log("Final template data to submit:", templateData);
        console.log(JSON.stringify(templateData, null, 2));

        $.ajax({
            url: '../Employee Section/functions/emp-itineraryData.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
              itineraryId: templateData.itineraryId // make sure templateData is defined
            }),

            success: function(response) {
              console.log("Raw Response: (1)", response);

              try {
                if (response.success && response.itineraryId && response.itinerary) {
                  const itineraryId = response.itineraryId;
                  const itineraryData = response.itinerary;

                  // console.log("Itinerary ID:", itineraryId);
                  // console.log(JSON.stringify(templateData, null, 2));
                  // console.log(JSON.stringify(itineraryData, null, 2));

                  $.ajax({
                    url: '../Employee Section/functions/emp-saveItineraryQuick.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                      templateData: templateData, // use the collected data
                      itineraryData: itineraryData // make sure templateData is defined
                    }),

                    success: function(response) {
                    console.log("Raw Response: (2)", response);

                    try {
                      const data = typeof response === 'string' ? JSON.parse(response) : response;
                      const messageElem = document.getElementById("itineraryNameMessage");

                      // Always hide the message by default
                      if (messageElem) {
                        messageElem.style.display = "none";
                        messageElem.textContent = "";
                      }

                      if (data.status === "success") {
                        console.log("✅ Success:", data.message);
                        alert("Itinerary created successfully!");
                        window.location.href = "../Employee Section/emp-itinerarytable.php";

                      } else if (data.status === "error") {
                        console.error("❌ Error:", data.message);
                        alert("Error: " + data.message);

                      } else if (data.status === "exists") {
                        console.warn("⚠️ Duplicate:", data.message);

                        // Show the message below the itinerary name input
                        if (messageElem) {
                          messageElem.style.display = "block";
                          messageElem.textContent = data.message;
                          messageElem.style.color = "red";
                        }

                      } else {
                        console.warn("⚠️ Unexpected status in response:", data);
                      }

                    } catch (e) {
                      console.error("Error parsing or processing response:", e);
                    }
                  },

                  error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert("AJAX request failed: " + error);
                  }



                  });






                  


                  // Continue using itineraryData as needed
                } else {
                  console.error('Invalid response structure or missing fields.');
                }
              } catch (e) {
                console.error('Error processing response:', e);
              }
            },

            error: function(xhr, status, error) {
              console.error('AJAX Error:', status, error);
            }

          });





      });


    });
  </script>














  <!-- Create Voucher Page Redirect -->
  <script>
    document.getElementById("createItinerary").addEventListener("click", function () {
      window.location.href = "../Employee Section/emp-generateItinerary.php";
    });
  </script>


  <!-- Delete Itinerary Script -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
      let itineraryToDelete = null;

      document.querySelectorAll(".delete-itinerary").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          itineraryToDelete = btn.getAttribute("data-id");

          // 🔁 Update modal header to show itinerary ID
          const modalTitle = document.getElementById('deleteConfirmLabel');
          const labelSpan = document.getElementById('itineraryIdLabel');
          if (labelSpan) {
            labelSpan.textContent = `(ID: ${itineraryToDelete})`;
          } else {
            // In case <span> isn't in DOM yet
            const span = document.createElement('span');
            span.id = 'itineraryIdLabel';
            span.className = 'text-light';
            span.textContent = `(ID: ${itineraryToDelete})`;
            modalTitle.appendChild(span);
          }

          modal.show();
        });
      });

      document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
        if (itineraryToDelete) {
          fetch("../Employee Section/functions/emp-itineraryDelete.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `itineraryId=${encodeURIComponent(itineraryToDelete)}`
          })
            .then(res => res.text())
            .then(response => {
              const card = document.querySelector(`.itinerary-card[data-id="${itineraryToDelete}"]`);
              if (card) card.remove();

              showNotification(`Itinerary ID: ${itineraryToDelete} successfully deleted`);
              modal.hide();
            })
            .catch(err => console.error("Delete failed", err));
        }
      });

      function showNotification(message, duration = 3000) {
        const banner = document.getElementById("notification-banner");
        const messageSpan = document.getElementById("notification-message");

        messageSpan.textContent = message;
        banner.classList.remove("hide");
        banner.classList.add("show");

        const loader = banner.querySelector(".loader");
        loader.style.animation = "none";
        loader.offsetHeight;
        loader.style.animation = `loaderAnim ${duration}ms linear forwards`;

        setTimeout(() => {
          banner.classList.remove("show");
          banner.classList.add("hide");
        }, duration);
      }
    });
  </script>

  <!-- For Button Tabs Status Sorting -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Get the status from the URL
      let statusTab = "<?php echo isset($_GET['status']) ? $_GET['status'] : ''; ?>";
      console.log("Status from URL:", statusTab); // Debugging

      // Find all filter buttons
      let buttons = document.querySelectorAll("#booking-filter-tabs .filter-btn");

      // Remove 'active' class from all buttons
      buttons.forEach(btn => btn.classList.remove("active"));

      // Find the button that matches the status
      let matchedButton = [...buttons].find(btn => btn.getAttribute("data-filter") === statusTab);

      if (matchedButton) {
        matchedButton.classList.add("active"); // Highlight the correct button
        console.log("Activating button:", matchedButton.innerText);

        setTimeout(() => {
          matchedButton.click();
        }, 3);

      } else {
        // Default to "All" if no match found
        let defaultButton = document.querySelector("#booking-filter-tabs .filter-btn[data-filter='']");
        if (defaultButton) {
          defaultButton.classList.add("active");
          console.log("Activating default button: All");

          setTimeout(() => {
            defaultButton.click();
          }, 100);
        }
      }

      // Add click event listener to each button
      buttons.forEach(button => {
        button.addEventListener("click", function () {
          // Remove active class from all buttons
          buttons.forEach(btn => btn.classList.remove("active"));

          // Add active class to the clicked button
          this.classList.add("active");

          let filterValue = this.getAttribute("data-filter");

          // Apply DataTables filtering
          if ($.fn.DataTable.isDataTable("#product-table")) {
            $('#product-table').DataTable().column(8).search(filterValue || '', true, false).draw();
          }
        });
      });
    });
  </script>

  <!-- For Itinerary Card Click -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.addEventListener("click", function (event) {
        let cardBody = event.target.closest(".it-card-body");
        if (cardBody) {
          let itineraryCard = cardBody.closest(".itinerary-card");
          let itineraryId = itineraryCard ? itineraryCard.getAttribute("data-id") : null;
          if (itineraryId) {
            window.location.href = `emp-itineraryDetails.php?id=${itineraryId}`;
          }
        }
      });
    });
  </script>


  <!-- Row Click Selection-->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll("tr[data-url]").forEach(function (row) {
        row.addEventListener("click", function () {
          const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

          console.log("Transaction Number: ", transactionNumber); // Debugging line

          // Use AJAX to send the transaction number to the server
          $.ajax({
            url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
            type: 'POST',
            data: {
              transaction_number: transactionNumber
            },
            success: function (response) {
              console.log("Response: ", response); // Debugging line

              // Redirect to the next page after successfully setting the session
              window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
            },
            error: function (xhr, status, error) {
              console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
            }
          });
        });
      });
    });
  </script>


















  <!-- DataTables #product-table
  <script>
    $(document).ready(function () {
      const table = $('#product-table').DataTable({
        dom: 'rtip', // Use only the relevant table elements
        language: {
          emptyTable: "No Transaction Records Available"
        },
        order: [
          [0, 'desc']
        ], // Default sorting by Transaction ID (descending)
        scrollX: false,
        scrollY: '65.5vh', // Set a fixed height for the table (adjust as necessary)
        paging: true, // Enable pagination
        pageLength: 14, // Set the number of rows per page
        autoWidth: false,
        autoHeight: false, // Prevent automatic height adjustment

        // Disable sorting for specific columns
        columnDefs: [{
          targets: [1, 2, 3, 4, 5, 6, 7, 8], // Disable sorting for 2nd and 4th columns
          orderable: false
        }]
      });

      // Search Functionality
      $('#search').on('keyup', function () {
        table.search(this.value).draw();
      });

      // Update the custom pagination buttons and page info
      function updatePagination() {
        const info = table.page.info();
        const currentPage = info.page + 1; // Get current page number (1-indexed)
        const totalPages = info.pages; // Get total pages

        // Update page info text
        $('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);

        // Enable/Disable prev and next buttons based on current page
        $('#prevPage').prop('disabled', currentPage === 1);
        $('#nextPage').prop('disabled', currentPage === totalPages);
      }

      // Custom pagination button click events
      $('#prevPage').on('click', function () {
        table.page('previous').draw('page');
        updatePagination();
      });

      $('#nextPage').on('click', function () {
        table.page('next').draw('page');
        updatePagination();
      });

      // Initialize pagination on first load
      updatePagination();

      // Package Filter
      $('#packages').on('change', function () {
        const selectedPackage = $(this).val();
        table.column(1).search(selectedPackage || '').draw();
      });

      // Booking Date Filter with value change
      $('#BookingStartDate').on('change', function () {
        const selectedBookingDate = $(this).val(); // Get the selected value directly from the input field
        console.log("Booking Date Filter:", selectedBookingDate); // Log the selected booking date
        table.column(3).search(selectedBookingDate || '').draw(); // Column 4 (index starts at 0)
      });

      // Flight Date Filter with value change
      $('#FlightStartDate').on('change', function () {
        const selectedFlightDate = $(this).val(); // Get the selected value directly from the input field
        console.log("Flight Date Filter:", selectedFlightDate); // Log the selected flight date
        table.column(3).search(selectedFlightDate || '').draw(); // Column 5 (index starts at 0)
      });

      // Apply datepicker and input validation for FlightStartDate
      $("#FlightStartDate").datepicker({
        dateFormat: "yy-mm-dd", // Set the format to MM-DD-YYYY
        showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
        changeMonth: true, // Allow the month to be changed from the dropdown
        changeYear: true, // Allow the year to be changed from the dropdown
        yearRange: "1900:2100", // Set a range of years (optional)
        onSelect: function (dateText) {
          // When a date is selected, update the input field with the date
          $(this).val(dateText);
          flightStartDate = dateText; // Store the selected date
          console.log("FlightStartDate Selected Date (onSelect): " + dateText);
          table.column(3).search(flightStartDate || '').draw(); // Column 5 (index starts at 0)
        }
      });


      // Apply datepicker and input validation for BookingStartDate
      $("#BookingStartDate").datepicker({
        dateFormat: "mm-dd-yy", // Set the format to MM-DD-YYYY
        showAnim: "fadeIn", // Optional: Adds a fade-in effect when the date picker is opened
        changeMonth: true, // Allow the month to be changed from the dropdown
        changeYear: true, // Allow the year to be changed from the dropdown
        yearRange: "1900:2100", // Set a range of years (optional)
        onSelect: function (dateText) {
          // When a date is selected, update the input field with the date
          $(this).val(dateText);
          bookingStartDate = dateText; // Store the selected date
          console.log("FlightStartDate Selected Date (onSelect): " + dateText);
          table.column(4).search(bookingStartDate || '').draw(); // Column 5 (index starts at 0)
        }
      });

      // BookingStartDate Input Validation and Formatting
      $("#BookingStartDate").on("input", function () {
        var value = $(this).val();

        // Remove non-numeric and non-dash characters
        value = value.replace(/[^\d-]/g, '');

        // Automatically add dashes in the correct places if necessary
        if (value.length > 2 && value.charAt(2) !== '-') {
          value = value.substring(0, 2) + '-' + value.substring(2);
        }
        if (value.length > 5 && value.charAt(5) !== '-') {
          value = value.substring(0, 5) + '-' + value.substring(5);
        }

        // Limit the total input length to 10 characters (MM-DD-YYYY)
        if (value.length > 10) {
          value = value.substring(0, 10);
        }

        // Update the input field value
        $(this).val(value);

        // Reset or update the bookingStartDate variable
        if (value === "") {
          bookingStartDate = ""; // Reset the variable if the input is cleared
        } else {
          bookingStartDate = value; // Update the variable with the formatted value
        }

        // Update the table column search
        table.column(5).search(bookingStartDate || '').draw(); // Column 5 (index starts at 0)

        console.log("BookingStartDate Input Value (on input): " + value);
      });


      // Clear All Filters
      $('#clearSorting').on('click', function () {
        // Clear search field
        $('#search').val('');
        table.search('').draw();

        // Reset status dropdown to "Select Status"
        $('#status').val('').trigger('change');

        // Reset branch dropdown to "Select Branch"
        $('#packages').val('').trigger('change');

        // Explicitly reset date filter variables
        flightStartDate = '';
        bookingStartDate = '';

        // Clear date fields
        $('#BookingStartDate').val('').trigger('change');
        $('#FlightStartDate').val('').trigger('change');

        // Reset DataTable filters & sorting
        table.order([
          [0, 'desc']
        ]) // Default sort by first column (Transaction ID)
          .search('') // Clear any search input
          .columns().search('') // Reset all column filters
          .draw(); // Redraw table to default state
      });

    });
  </script> -->

</body>

</html>