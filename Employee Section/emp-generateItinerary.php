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

		<div class="main-content">

			<div class="form-container-wrapper">
				<form id="itineraryGenerate">

					<!-- Itinerary Details Card -->
					<div class="card mb-3">
						<div class="card-header bg-primary">
							<h5>Generate Itinerary</h5>
						</div>

						<div class="card-body">

							<!-- Country, Package Row -->
							<div class="row">

								<!-- Country Dropdown -->

								<div class="columns col-md-4">
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
								<div class="columns col-md-4">
									<div class="column-header">
										<label for="flightDate">Package
											<span class="text-danger"> *</span>
										</label>
									</div>

									<div class="form-group">
										<select class="form-select" id="packageSelect" name="packageSelect" required>
											<option value="" selected disabled>Select Package Type</option>
											<?php
											// Execute the SQL query
											$sql1 = "SELECT packageName, packageId FROM package ORDER BY packageId ASC";
											$res1 = $conn->query($sql1);

											if ($res1->num_rows > 0) {
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

							</div>

							<!-- Periods, Guide Row -->
							<div class="row">

								<!-- Flight Date Datepicker -->
								<div class="columns col-md-4">

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
													<input type="text" class="datepicker form-control"
														id="PeriodStartDate" placeholder="Start Date" readonly required>
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
													<input type="text" class="datepicker form-control"
														id="PeriodEndDate" placeholder="End Date" readonly required>
													<i class="fas fa-calendar-alt calendar-icon position-absolute"
														style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
												</div>
											</div>
										</div>
									</div>


								</div>

								<!-- Guide Dropdown -->
								<div class="columns col-md-4">
									<div class="column-header">
										<label for="flightDate">Guide
											<span class="text-danger"> *</span>
										</label>
									</div>

									<div class="form-group">
										<select class="form-select" id="guideName" name="guideName" required
											onchange="updateContact(this)">
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

												echo "<option value=\"$fullName\" data-accountid=\"$accountId\" data-contact=\"$contactNo\" data-code=\"$countryCode\">$fullName</option>";
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
								<div class="columns col-md-4">
									<div class="column-header">
										<label for="contactNumber">Contact Number <span
												class="text-danger">*</span></label>
									</div>
									<div class="form-group d-flex flex-row align-items-center">
										<select class="form-select" id="countryCode" style="width: 80px;" disabled>
											<option value="+63" selected>+63</option>
											<option value="+82">+82</option>
										</select>
										<input type="text" class="form-control ms-2" id="contactNumber"
											name="contactNumber" placeholder="9***********" disabled>
									</div>
								</div>

							</div>


							<!-- Tour Areas, Hotels -->

							<!-- Tour Areas, Hotels Dropdowns - 1 -->
							<div class="row">
								<div class="columns col-md-8">
									<div class="cityhotel-wrapper d-flex align-items-center gap-2">
										<div class="cityhotel-item">
											<select class="form-select city-select" id="city1" name="city(1)" required>
												<option selected disabled value="">Select City</option>
											</select>
										</div>
										<div class="dash-separator">-></div>
										<div class="cityhotel-item">
											<select class="form-select hotel-select" id="hotel1" name="hotel(1)"
												required>
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
								<div class="columns col-md-8">
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
								<div class="columns col-md-8">
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

						</div>
					</div>

					<!-- No. of Days Card -->
					<div class="card mb-3">
						<div class="card-header">
							<h5>No. of Days</h5>
						</div>

						<div class="card-body">
							<!-- Package Row -->
							<div class="row">
								<div class="columns col-md-3">
									<div class="form-group days-select-wrapper">
										<label for="flightDate">No. of days<span class="text-danger"> *</span></label>
										<select class="form-select" id="select-days" name="numberOfDays" required
											disabled>
											<option selected disabled>Select Number of Days</option>
										</select>
										<small class="form-text text-muted">Changing this will clear all your data on
											the
											fields.</small>
									</div>
								</div>
							</div>
						</div>

					</div>

					<div class="itinerary-container" id="itinerary-container"> </div>

			</div>

			<div class="form-footer">

				<!-- Disabled by Default -->
				<button type="button" class="btn btn-primary" id="submitTour">Generate Itinerary</button>

				</form>

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
			window.resetCityHotel = function(index) {
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
							koreanTourAreas = data.data; // flat array of area names
							console.log("Areas Loaded:", JSON.stringify(koreanTourAreas, null, 2));


							renderAreaSelects(); // ⬅️ render only after data is ready
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

					// Clear current options
					select.innerHTML = `<option value="" disabled selected>Select Area</option>`;

					koreanTourAreas.forEach(area => {
						const option = document.createElement("option");
						option.value = area;
						option.textContent = area;

						if (area === currentValue) {
							option.selected = true;
						}

						select.appendChild(option);
					});
				});
			}


			// ========= For Hotels Data Fetching and Rendering ========= 

			let hotelsByArea = {};

			// Load Hotels From DB (Structured: areaName => [ { hotelId, hotelName }, ... ])
			loadHotelsFromDB();

			function loadHotelsFromDB() {
				fetch('../Employee Section/functions/fetchScripts/getHotelsByArea.php')
					.then(res => res.json())
					.then(data => {
						if (data.status === 'success') {
							hotelsByArea = data.data;
							console.log("✅ Hotels Loaded:", JSON.stringify(hotelsByArea, null, 2));
							renderHotelSelects();
							setupAreaChangeListener(); // 🔁 Setup listener after hotels are loaded
						} else {
							alert("⚠️ Failed to load hotels data.");
						}
					})
					.catch(err => {
						console.error("❌ Hotel data fetch error:", err);
						alert("An error occurred while loading hotels.");
					});
			}

			// Render hotel <select> options based on current data-area
			function renderHotelSelects() {
				const hotelSelects = document.querySelectorAll(".hotel-select");

				hotelSelects.forEach((select) => {
					const area = select.dataset.area;
					const currentValue = select.value;

					if (!area || !hotelsByArea[area]) return;

					select.innerHTML = `<option value="" disabled selected>Select ${select.name?.replace(/_/g, " ").replace(/\d/g, "") || "Hotel"}</option>`;

					hotelsByArea[area].forEach(hotel => {
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

			// ========= Auto-bind hotel-selects to area-selects =========

			function setupAreaChangeListener() {
				const areaSelects = document.querySelectorAll(".area-select");

				areaSelects.forEach(areaSelect => {
					areaSelect.addEventListener("change", () => {
						const selectedArea = areaSelect.value;
						const day = areaSelect.dataset.day;

						// Target all hotel-selects sharing this day
						const hotelSelects = document.querySelectorAll(`.hotel-select[data-day="${day}"]`);

						hotelSelects.forEach(hotelSelect => {
							hotelSelect.dataset.area = selectedArea || "";
						});

						renderHotelSelects(); // Update hotel dropdowns
					});
				});
			}









			// ========= For Itinerary Activities Data Fetching and Rendering ========= 
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
						// console.log("📥 Meal Plans Fetched:", data); 

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
									<option selected>Incheon</option>
								</select>
								</div>` :
								["Area 1", "Area 2", "Area 3"].map((label, i) => `
								<div class="col-4 mb-2">
									<label class="form-label fw-semibold">${label}</label>
									<div class="d-flex align-items-center gap-2">
									<select class="form-select area-select"
											id="area${day}_area${i+1}"
											data-day="${day}"
											data-index="area${day}_area${i+1}"
											name="area_${day}_area${i+1}"
											${i === 0 ? 'required' : ''}>
										<option value="" selected disabled>Select ${label}</option>
										${koreanTourAreas.map(a => `<option value="${a}">${a}</option>`).join("")}
									</select>

									<button type="button"
											class="btn btn-sm btn-danger text-light area-trash"
											id="trash-area${day}_area${i+1}"
											onclick="resetArea('${day}_area${i+1}')"
											title="Reset Area"
											style="display: none;">
										<i class="fas fa-trash-alt"></i>
									</button>

									</div>
								</div>
								`).join("")
							}
							</div>


							<!-- Meals -->
								<div class="row mb-3">
								${day === 1 ? `
									<div class="col-4">
									<label class="form-label fw-semibold">Meal Plan:</label>
									<select class="form-select meal-plan-select" data-day="${day}" disabled>
										<option selected>Snack</option>
									</select>
									</div>` :
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



							<!-- Hotels -->
							<div class="row mb-3">
								<div class="col-12">
									<label class="form-label fw-semibold">Hotels:</label>
									<div class="row">
										${["Hotel 1", "Hotel 2"].map((label, i) => {
											const incheonHotels = hotelsByArea["Incheon"] || [];

											// Build options for Day 1 only
											const hotelOptions =
												day === 1
													? incheonHotels
															.map((hotel, index) => `
																<option value="${hotel.hotelId}">
																	Select Hotel ${index + 1} - ${hotel.hotelName}
																</option>
															`).join("")
													: "";

											return `
												<div class="col-md-6 col-sm-12 mb-2 d-flex align-items-center gap-2">
													<select class="form-select hotel-select"
														id="hotel${day}_${i}"
														data-day="${day}"
														data-index="${day}_${i}"
														${day === 1 ? `data-area="Incheon"` : ""}
														name="hotel_${day}_${i}" 
														${i === 0 ? 'required' : ''}>
														<option disabled selected value="">
															Select Hotel ${i + 1}
														</option>
														${hotelOptions}
													</select>

													<button type="button" class="btn btn-sm btn-danger text-light hotel-trash"
														id="trash-hotel${day}_${i}"
														onclick="resetHotel('${day}_${i}')"
														title="Reset Hotel"
														style="display: none;">
														<i class="fas fa-trash-alt"></i>
													</button>
												</div>
											`;
										}).join("")}
									</div>
								</div>
							</div>






							<!-- Itineraries -->
							<div class="row mb-3">
								<div class="col-12"><label class="form-label fw-semibold">Itinerary:</label></div>
								${
									(day === 1 ? [1, 2, 3, 4] : [1, 2, 3, 4, 5, 6, 7]).map(num => {
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

			// Show footer
			formFooter.style.display = selectedDays ? "flex" : "none";

			// Reset itinerary
			window.resetItinerary = function(index) {
				const select = document.getElementById(`itinerary${index}`);
				if (select) {
					select.selectedIndex = 0;
					updateItineraryDropdowns();
					checkItineraryTrashVisibility();
				}
			};


			// Delegated listener: disable already chosen itinerary values
			document.addEventListener("change", function (e) {
				if (e.target.classList.contains("itinerary-select")) {
					updateItineraryDropdowns();
					checkItineraryTrashVisibility();
				}

				if (e.target.classList.contains("area-select")) {
					const day = e.target.getAttribute("data-day");

					const selectedAreas = Array.from(document.querySelectorAll(`.area-select[data-day="${day}"]`))
						.map(select => select.value)
						.filter(val => val !== "");

					const hotels = [...new Set(selectedAreas.flatMap(area => hotelsByArea[area] || []))];

					const hotelSelects = document.querySelectorAll(`.hotel-select[data-day="${day}"]`);

					hotelSelects.forEach(select => {
						const current = select.value;
						select.innerHTML =
							`<option selected disabled value="">Select Hotel</option>` +
							[...hotels].map(hotel => `
								<option value="${hotel}" ${hotel === current ? "selected" : ""}>${hotel}</option>
							`).join("");
					});
				}
			});

			// Utility: disable same itinerary across selects
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
				const select = document.getElementById(`area${index}`);
				if (select) {
					select.selectedIndex = 0;
					checkAreaTrashVisibility();
				}
			};
		});


		// Form validation for required selects
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

	// Revalidate submit button on input or change
	form.addEventListener("input", toggleSubmitButton);
	form.addEventListener("change", toggleSubmitButton);

	function toggleSubmitButton() {
		const isValid = form.checkValidity();
		submitBtn.disabled = !isValid;

		if (isValid) {
			const json = collectFormData();

			console.log("✅ Form is now valid. 'Generate' button enabled.");

			console.group("📦 Validated Form Data");
			console.log("Template Name:", json.templateName);
			console.log("Selected Package:", json.selectedPackage);
			console.log("No. of Days:", json.noOfDays);
			console.log("Start Date:", json.startDate);
			console.log("End Date:", json.endDate);
			console.log("Guide Name:", json.guideName);
			console.log("Guide Account ID:", json.guideaccountId);
			console.log("Country Code:", json.countryCode);
			console.log("Contact Number:", json.contactNumber);
			console.log("🏨 City/Hotel JSON:", json.cityHotelsData);
			console.log("🗓️ Itinerary Data JSON:", json.itineraryData);

			console.groupEnd();

			console.group("📝 Stringified JSON Output");
			console.log(JSON.stringify(json, null, 2));
			console.groupEnd();
		}
	}

	// Handle submit button click
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
			console.error("Modal element not found!");
		}
	});
});

function collectFormData() {
	const templateInput = document.getElementById("templateName");

	const selectedPackage = document.getElementById("packageSelect")?.value.trim() ?? null;
	const noOfDays = document.getElementById("select-days")?.value.trim() ?? null;
	const startDate = document.getElementById("PeriodStartDate")?.value.trim() ?? null;
	const endDate = document.getElementById("PeriodEndDate")?.value.trim() ?? null;
	const guideName = document.getElementById("guideName")?.value.trim() ?? null;
	const countryCode = document.getElementById("countryCode")?.value.trim() ?? null;
	const contactNumber = document.getElementById("contactNumber")?.value.trim() ?? null;

	const guideSelect = document.getElementById("guideName");
	const guideaccountId = guideSelect?.selectedOptions[0]?.getAttribute("data-accountid")?.trim() ?? null;

	const cityHotelsData = {};
	for (let i = 1; i <= 3; i++) {
		const city = document.getElementById(`city${i}`)?.value.trim() ?? "";
		const hotel = document.getElementById(`hotel${i}`)?.value.trim() ?? "";

		if (city || hotel) {
			cityHotelsData[`city${i}`] = city;
			cityHotelsData[`hotel${i}`] = hotel;
		}
	}

	const itineraryData = [];
	document.querySelectorAll(".itinerary-card").forEach(dayCard => {
		const day = dayCard.querySelector(".hotel-select")?.dataset.day || "Unknown";

		const selectedAreas = [...dayCard.querySelectorAll(".area-select[data-day]")]
			.map(area => area.value.trim())
			.filter(Boolean);

		const selectedMealPlans = [...dayCard.querySelectorAll(".meal-plan-select[data-day]")]
			.map(meal => parseInt(meal.value.trim(), 10))
			.filter(Number.isInteger); // Ensure only valid integers

		const selectedHotels = [...dayCard.querySelectorAll(".hotel-select")]
			.map(select => select.value.trim())
			.filter(Boolean);

		const selectedItineraries = [...dayCard.querySelectorAll(".itinerary-select")]
			.map(select => select.value.trim())
			.filter(Boolean);

		itineraryData.push({
			day,
			areas: selectedAreas.length ? selectedAreas : [""],
			meal_plans: selectedMealPlans.length ? selectedMealPlans : [""],
			hotels: selectedHotels.length ? selectedHotels : [""],
			itineraries: selectedItineraries.length ? selectedItineraries : [""]
		});
	});


	return {
		templateName: templateInput?.value.trim() ?? "",
		selectedPackage,
		noOfDays,
		startDate,
		endDate,
		guideName,
		guideaccountId,
		countryCode,
		contactNumber,
		cityHotelsData,
		itineraryData
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

	// Clear error state
	templateInput.classList.remove("is-invalid");
	nameError.classList.add("d-none");
	nameError.classList.remove("fade-out", "hide");

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
			itinerary: JSON.stringify(data.itineraryData)
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