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
											$sql1 = "SELECT packageName FROM package ORDER BY packageId ASC";
											$res1 = $conn->query($sql1);

											if ($res1->num_rows > 0) {
												while ($row = $res1->fetch_assoc()) {
													echo "<option value='" . $row['packageName'] . "'>" . $row['packageName'] . "</option>";
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
													<input type="text" class="datepicker form-control" id="PeriodStartDate"
														placeholder="Start Date" readonly required>
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
														readonly required>
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
										<select class="form-select" id="guideName" name="guideName" required onchange="updateContact(this)">
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

							<!-- Tour Areas, Hotels Dropdowns - 1 -->
							<div class="row">

								<div class="columns col-md-8">
									<div class="column-header">
										<label for="flightDate">Tour Areas, Hotels <span class="text-danger">*</span></label>
									</div>

									<div class="cityhotel-wrapper">

										<div class="cityhotel-item">
											<div class="form-group d-flex flex-row align-items-center">
												<select class="form-select city-select" id="city1" name="city(1)" required>
													<option selected value="" disabled>Select City</option>
												</select>
											</div>
										</div>

										<div class="dash-separator">-></div>

										<div class="cityhotel-item">
											<div class="form-group d-flex flex-row align-items-center">
												<select class="form-select hotel-select" id="hotel1" name="hotel(1)" required>
													<option selected value="" disabled>Select Hotel</option>
												</select>
											</div>
										</div>

									</div>
								</div>

							</div>

							<!-- Tour Areas, Hotels Dropdowns - 2 -->
							<div class="row">

								<div class="columns col-md-8">
									<div class="cityhotel-wrapper">
										<div class="cityhotel-item">
											<div class="form-group d-flex flex-row align-items-center">
												<select class="form-select city-select" id="city2" name="city(2)">
													<option selected disabled>Select City</option>
												</select>
											</div>
										</div>
										<div class="dash-separator">-></div>
										<div class="cityhotel-item">
											<div class="form-group d-flex flex-row align-items-center">
												<select class="form-select hotel-select" id="hotel2" name="hotel(2)">
													<option selected disabled>Select Hotel</option>
												</select>
											</div>
										</div>
									</div>
								</div>

							</div>

							<!-- Tour Areas, Hotels Dropdowns - 3 -->
							<div class="row">

								<div class="columns col-md-8">
									<div class="cityhotel-wrapper">
										<div class="cityhotel-item">
											<div class="form-group d-flex flex-row align-items-center">
												<select class="form-select city-select" id="city3" name="city(3)">
													<option selected disabled>Select City</option>
												</select>
											</div>
										</div>
										<div class="dash-separator">-></div>
										<div class="cityhotel-item">
											<div class="form-group d-flex flex-row align-items-center">
												<select class="form-select hotel-select" id="hotel3" name="hotel(3)">
													<option selected disabled>Select Hotel</option>
												</select>
											</div>
										</div>
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
										<select class="form-select" id="select-days" name="numberOfDays" required disabled>
											<option selected disabled>Select Number of Days</option>
										</select>
										<small class="form-text text-muted">Changing this will clear all your data on the
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



	<!-- Datepicker Script -->
	<script>
		$(document).ready(function () {
			// Common configuration for datepickers
			function initDatepicker(selector) {
				$(selector).datepicker({
					dateFormat: "yy-mm-dd",
					showAnim: "fadeIn",
					changeMonth: true,
					changeYear: true,
					yearRange: "1900:2100",
					minDate: 0, // Equivalent to 'today'
					beforeShow: function (input, inst) {
						setTimeout(function () {
							$(inst.dpDiv).css({
								position: 'absolute',
								top: $(input).offset().top + $(input).outerHeight() + 8 + "px",
								left: $(input).offset().left + "px",
								zIndex: 9999
							});
						}, 0);
					},
					onSelect: function (dateText, inst) {
						console.log(input.id + " Selected: " + dateText);
					}
				});
			}

			// Initialize specific date fields
			initDatepicker("#PeriodStartDate");
			initDatepicker("#PeriodEndDate");
		});
	</script>


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


	<!-- Data Fetch to Fields -->
	<script>
		document.addEventListener("DOMContentLoaded", () => {
			const cities = ["Seoul", "Gyeonggi-do", "Incheon", "Jeju"];
			const hotels = {
				"Seoul": ["Smart Stay Hotel"],
				"Gyeonggi-do": ["Ramada Hotel", "Marina Bay Hotel"],
				"Incheon": ["Air Sky Hotel", "Royal Emporium", "Smart Stay Hotel"],
				"Jeju": ["Tamara Hotel"]
			};

			const selectedHotels = {};

			loadStoredHotels();

			document.querySelectorAll(".city-select").forEach((select, index) => {
				populateDropdown(select, cities, "Select City");
				select.addEventListener("change", () => updateHotelDropdown(select, index + 1));
			});

			function populateDropdown(select, optionsList, placeholderText) {
				if (!select) return;
				select.innerHTML = `<option value="" selected disabled>${placeholderText}</option>`;
				optionsList.forEach(optionValue => {
					const option = document.createElement("option");
					option.value = optionValue;
					option.textContent = optionValue;
					select.appendChild(option);
				});
			}

			function updateHotelDropdown(citySelect, rowIndex) {
				const row = citySelect.closest(".row");
				const hotelSelect = row.querySelector(".hotel-select");

				if (!hotelSelect) return;

				const selectedCity = citySelect.value;
				hotelSelect.innerHTML = `<option value="" selected disabled>Select Hotel</option>`;

				if (hotels[selectedCity]) {
					const availableHotels = hotels[selectedCity].filter(hotel => {
						return !Object.entries(selectedHotels).some(([city, hotelList]) =>
							city === selectedCity && hotelList.includes(hotel)
						);
					});

					populateDropdown(hotelSelect, availableHotels, "Select Hotel");
					disableOtherHotels(selectedCity);
				}

				console.log(`Row ${rowIndex}: City Selected - ${selectedCity}`);
			}

			function disableOtherHotels(city) {
				document.querySelectorAll(".hotel-select").forEach(select => {
					const citySelect = select.closest(".row")?.querySelector(".city-select");
					if (!citySelect || citySelect.value !== city) return;

					const selectedHotel = select.value;
					select.querySelectorAll("option").forEach(option => {
						option.disabled = (selectedHotel === option.value);
					});
				});
			}

			function loadStoredHotels() {
				const storedHotels = JSON.parse(localStorage.getItem("selectedHotels")) || {};
				document.querySelectorAll(".hotel-select").forEach((select, index) => {
					const row = select.closest(".row");
					const citySelect = row?.querySelector(".city-select");
					const rowIndex = index + 1;

					if (citySelect && citySelect.value && hotels[citySelect.value]) {
						populateDropdown(select, hotels[citySelect.value], "Select Hotel");

						const selectedHotel = storedHotels[citySelect.value];
						if (selectedHotel && hotels[citySelect.value].includes(selectedHotel)) {
							select.value = selectedHotel;
						}
					}

					console.log(`Row ${rowIndex}: Loaded City - ${citySelect?.value || "None"}, Hotel - ${select.value || "None"}`);
				});
			}

			document.body.addEventListener("change", (event) => {
				if (event.target.classList.contains("hotel-select")) {
					const row = event.target.closest(".row");
					const citySelect = row?.querySelector(".city-select");

					if (!citySelect || !citySelect.value) return;

					const selectedCity = citySelect.value;
					const selectedHotel = event.target.value === "" ? null : event.target.value;

					// Reset and re-track
					selectedHotels[selectedCity] = [selectedHotel].filter(Boolean);

					const storedHotels = JSON.parse(localStorage.getItem("selectedHotels")) || {};
					storedHotels[selectedCity] = selectedHotel;
					localStorage.setItem("selectedHotels", JSON.stringify(storedHotels));

					const rowIndex = Array.from(document.querySelectorAll(".row")).indexOf(row) + 1;
					console.log(`Row ${rowIndex}: City - ${selectedCity}, Hotel - ${selectedHotel}`);
				}
			});

			const observer = new MutationObserver(() => {
				document.querySelectorAll(".hotel-select").forEach(select => {
					if (!select.hasAttribute("data-initialized")) {
						select.setAttribute("data-initialized", "true");
						const row = select.closest(".row");
						const citySelect = row?.querySelector(".city-select");
						const rowIndex = Array.from(document.querySelectorAll(".row")).indexOf(row) + 1;
						if (citySelect) updateHotelDropdown(citySelect, rowIndex);
					}
				});
			});

			observer.observe(document.body, {
				childList: true,
				subtree: true
			});
		});
	</script>



	<!-- Dropdown Script -->

	<script>
		const selectDays = document.getElementById("select-days");
		const itineraryContainer = document.getElementById("itinerary-container");
		const formFooter = document.querySelector(".form-footer");
		const submitBtn = document.getElementById("submitTour");

		// Korean Tour Data
		const koreanTourAreas = ["Seoul", "Gyeonggi-do", "Incheon", "Jeju"];


		const hotelsByArea = {
			"Seoul": ["Smart Stay Hotel"],
			"Gyeonggi-do": ["Ramada Hotel", "Marina Bay Hotel"],
			"Incheon": ["Air Sky Hotel", "Royal Emporium Hotel", "Smart Stay Hotel"],
			"Jeju": ["Tamara Hotel"]
		};


		const koreanMealPlans = {
			breakfast: [
				"Hotel B/F"
			],
			lunch: [
				"BBQ Chicken",
				"Food Coupon",
				"Shabu-Shabu",
				"Grilled Fish",
			],
			dinner: [
				"Korean Food",
				"Hotel Buffet",
				"10000 won",
				"Bulgogi",
			]
		};

		// const itinerariesByDay = {
		// 	1: [
		// 		"Arrive at Incheon Airport, transfer to the hotel",
		// 		"Check in and freshen up at the hotel",
		// 		"Namsan Seoul Tower",
		// 		"Bukchon Hanok Village"
		// 	],
		// 	2: [
		// 		"Breakfast in Hotel",
		// 		"Lotte World Adventure",
		// 		"Han River Cruise",
		// 		"Itaewon Culture Walk",
		// 		"Gyeongbokgung Palace Tour"
		// 	],
		// 	3: [

		// 		"Busan Gamcheon Culture Village",
		// 		"Haeundae Beach",
		// 		"Busan Tower",
		// 		"Jagalchi Fish Market",
		// 		"Dongbaekseom Island"
		// 	],
		// 	4: [
		// 		"Jeju Island Lava Tubes",
		// 		"Manjanggul Cave",
		// 		"Seongsan Ilchulbong Peak",
		// 		"Jeju Folk Village",
		// 		"Hallim Park"
		// 	],
		// 	5: [
		// 		"Nami Island Day Trip",
		// 		"Petite France",
		// 		"The Garden of Morning Calm",
		// 		"Korean Folk Village",
		// 		"COEX Mall & Aquarium"
		// 	]
		// };

		// Combine all itineraries into one master list
		const allItineraries = [
			// Day 1
			"Arrival at Incheon Airport - Flight: 5J118 (MNL-ICN)",
			"Meeting and Greeting with an English-speaking guide",
			"Transfer to Seoul and check in at the hotel",

			// Day 2 - CHERRY BLOSSOM
			"King Canoe Quay",
			"Chuncheon Samaksan Mountain Lake Cable Car",
			"Chuncheon Sailo 248 (Suspension Bridge)",
			"Jade Garden",
			"PotatoBatt (Bakery)",

			// Day 2 - BASIC TOUR
			"Breakfast at the hotel",
			"Nami Island",
			"Small France Culture Village",
			"Italian Village (Pinocchio Village)",

			// Day 3
			"N Seoul Tower",
			"Everland Theme Park",

			// Day 4
			"Ginseng Museum",
			"Cosmetic Duty Free Shop",
			"Free time shopping at Shilla Duty Free Shop",
			"Myeongdong Street",
			"Free shopping at Myeongdong Street",

			// Day 5
			"Gyeongbokgung Palace",
			"Red Pine Store",
			"Korea Produce Jewel Amethyst Shop",
			"Jamsil Seokchon Lake (Cherry Blossom)",
			"Gimpo Hyundai Outlet",
			"Experience making Kimbop"
		];


		// Structured per day
		const itinerariesByDay = {
			1: allItineraries.slice(0, 4),     // First 4 only
			2: allItineraries,                 // Full list
			3: allItineraries,
			4: allItineraries,
			5: allItineraries
		};


		const totalDays = 5;

		const selectedValues = {
			area: {},
			hotel: {},
			itinerary: {}
		};

		// Populate dropdown for selecting number of days
		for (let num = 1; num <= totalDays; num++) {
			let option = document.createElement("option");
			option.value = num;
			option.textContent = `Day ${num}`;
			selectDays.appendChild(option);
		}
		selectDays.value = 5;

		const selectedDays = parseInt(selectDays.value);
		itineraryContainer.innerHTML = "";

		for (let day = 1; day <= selectedDays; day++) {
			const card = document.createElement("div");
			card.className = "card itinerary-card mb-3";

			card.innerHTML = `
		<div class="card-header bg-primary text-white fw-bold">Day ${day}</div>

		<div class="card-body">
			<div class="container-fluid">

				<!-- Area Selection -->
				<div class="row mb-3">
				${day === 1
					? `
					<div class="col-4">
						<label class="form-label fw-semibold">Area:</label>    
						<select class="form-select area-select" data-day="${day}" disabled>
						<option selected>Incheon</option>
						</select>
					</div>`
					: ["Area 1", "Area 2", "Area 3"].map((areaLabel, index) => {
						const isRequired = index === 0; // Only first (Area 1) is required
						const nameAttr = `area_${day}_area${index + 1}`;
						return `
						<div class="col-4">
							<label class="form-label fw-semibold">${areaLabel}:</label>    
							<select class="form-select area-select" data-day="${day}" name="${nameAttr}" ${isRequired ? 'required' : ''}>
							<option value="" selected disabled>Select ${areaLabel}</option>
							${koreanTourAreas.map(area => `<option value="${area}">${area}</option>`).join("")}
							<option value="">No Area</option>
							</select>
						</div>
						`;
					}).join("")
				}
				</div>



				<!-- Meal Plans -->
				<div class="row mb-3">
				${day === 1
					? `
					<div class="col-4">
						<label class="form-label fw-semibold">Meal Plan:</label>
						<select class="form-select meal-plan-select" data-day="${day}" disabled>
						<option selected>Snack</option>
						</select>
					</div>`
					: ["breakfast", "lunch", "dinner"].map(mealType => `
						<div class="col-4">
						<label class="form-label fw-semibold">
							${mealType.charAt(0).toUpperCase() + mealType.slice(1)}:
						</label>
						<select class="form-select meal-plan-select" data-day="${day}" data-meal="${mealType}" name="meal_${day}_${mealType}" required>
							<option value="" selected disabled>Select ${mealType.charAt(0).toUpperCase() + mealType.slice(1)}</option>
							${koreanMealPlans[mealType].map(meal => `<option value="${meal}">${meal}</option>`).join("")}
							<option value="">No ${mealType.charAt(0).toUpperCase() + mealType.slice(1)}</option>
						</select>
						</div>
					`).join("")
				}
				</div>


				<!-- Hotels -->
				<div class="row mb-3">
					<div class="col-12">
						<label class="form-label fw-semibold">Hotels:</label>
						<div class="row">
							${["Hotel 1", "Hotel 2"].map((hotelLabel, index) => {
								const hotelOptions = day === 1
									? ["Air Sky Hotel", "Royal Emporium Hotel", "Smart Stay Hotel"]
										.map(hotel => `<option value="${hotel}">${hotel}</option>`).join("")
									: "";

								const isRequired = index === 0;

								// Proper placeholder (blocks submission if unchanged)
								const placeholderOption = `<option value="" disabled selected>Select ${hotelLabel}</option>`;

								// "No Hotel" option using value="null"
								const noHotelOption = `<option value="">No Hotel</option>`;

								return `
									<div class="col-md-4 col-sm-12 mb-2">
										<select class="form-select hotel-select" data-day="${day}" name="hotel_${day}_${index}" ${isRequired ? 'required' : ''}>
											${placeholderOption}
											${hotelOptions}
											${noHotelOption}
										</select>
									</div>
								`;
							}).join("")}
						</div>
					</div>
				</div>





				<!-- Itineraries -->
				<div class="row mb-3">
					<div class="col-12">
						<label class="form-label fw-semibold">Itinerary:</label>
					</div>
						${
							(day === 1 ? [1, 2, 3, 4] : [1, 2, 3, 4, 5, 6, 7]).map(num => {
								const placeholderOption = `<option value="" selected disabled hidden>Select Itinerary ${num}</option>`;
								const noItineraryOption = `<option value="">No Itinerary</option>`;

								const options = (day === 1
									? allItineraries.slice(0, 3)
									: allItineraries.slice(3)
								).map(itinerary => `
									<option value="${itinerary}">${itinerary}</option>
								`).join("");

								return `
									<div class="col-12 mb-2">
										<select class="form-select itinerary-select" data-day="${day}">
											${noItineraryOption}
											${placeholderOption}
											${options}
										</select>
									</div>
								`;
							}).join("")
						}
					</div>

				</div>
			</div>
		`;

			itineraryContainer.appendChild(card);
		}


		// Event Delegation: Update hotel options based on selected area(s)
		document.addEventListener("change", function (e) {
			if (e.target.classList.contains("area-select")) {
				const day = e.target.getAttribute("data-day");

				// Get all selected area values for the current day
				const selectedAreas = Array.from(document.querySelectorAll(`.area-select[data-day="${day}"]`))
					.map(select => select.value)
					.filter(val => val !== "Select Area" && val !== "");

				// Flatten and deduplicate hotel options from selected areas
				const allHotels = [...new Set(
					selectedAreas.flatMap(area => hotelsByArea[area] || [])
				)];

				const hotelSelects = document.querySelectorAll(`.hotel-select[data-day="${day}"]`);

				// Populate each hotel select
				hotelSelects.forEach(hotelSelect => {
					const currentValue = hotelSelect.value;

					hotelSelect.innerHTML =
						`<option selected disabled value="">Select Hotel</option>` +
						[...allHotels, "No Hotel"]
							.map(hotel => `
								<option value="${hotel}" ${hotel === currentValue ? "selected" : ""}>
									${hotel}
								</option>
							`).join("");
				});
			}
		});



		// Hide the form-footer when itinerary is cleared
		formFooter.style.display = selectedDays ? "flex" : "none";

		// Event listener to handle selection changes
		document.addEventListener("change", function (event) {
			if (event.target.matches(".area-select, .hotel-select, .itinerary-select")) {
				const day = event.target.dataset.day;
				const selectedValue = event.target.value;
				const className = event.target.className.split(" ")[1].split("-")[0]; // Extract area, hotel or itinerary from class name

				if (!selectedValues[className][day]) {
					selectedValues[className][day] = [];
				}

				// Add the selected value to the corresponding category for that day
				selectedValues[className][day].push(selectedValue);

				// Update all dropdowns to disable already selected values
				updateDropdownOptions();
			}
		});

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
			console.log("🔄 Checking form validity...");
			submitBtn.disabled = !form.checkValidity();
		}

		// Handle submit button click
		submitBtn.addEventListener("click", function (e) {
			e.preventDefault();

			if (!form.checkValidity()) {
				form.reportValidity();
				return;
			}

			// Show modal using Bootstrap 5 Modal API
			if (modalEl) {
				const modal = new bootstrap.Modal(modalEl);
				modal.show();
			} else {
				console.error("❌ Modal element not found!");
			}
		});
	});

	function proceedWithSubmission() {
		const form = document.getElementById("itineraryGenerate");

		if (!form.checkValidity()) {
			form.reportValidity();
			return;
		}

		const templateName = document.getElementById("templateName")?.value.trim();
		if (!templateName) {
			alert("⚠️ Please enter a template name before proceeding.");
			return;
		}

		// Collect top-level inputs
		const selectedPackage = document.getElementById("packageSelect")?.value.trim() ?? null;
		const noOfDays = document.getElementById("select-days")?.value.trim() ?? null;
		const startDate = document.getElementById("PeriodStartDate")?.value.trim() ?? null;
		const endDate = document.getElementById("PeriodEndDate")?.value.trim() ?? null;
		const guideName = document.getElementById("guideName")?.value.trim() ?? null;
		const countryCode = document.getElementById("countryCode")?.value.trim() ?? null;
		const contactNumber = document.getElementById("contactNumber")?.value.trim() ?? null;

		const guideSelect = document.getElementById("guideName");
		const accountId = guideSelect?.selectedOptions[0]?.getAttribute("data-accountid")?.trim() ?? null;

		const city1 = document.getElementById("city1")?.value.trim() ?? null;
		const hotel1 = document.getElementById("hotel1")?.value.trim() ?? null;
		const city2 = document.getElementById("city2")?.value.trim() ?? null;
		const hotel2 = document.getElementById("hotel2")?.value.trim() ?? null;
		const city3 = document.getElementById("city3")?.value.trim() ?? null;
		const hotel3 = document.getElementById("hotel3")?.value.trim() ?? null;


		// Collect itinerary data
		const itineraryData = [];

		document.querySelectorAll(".itinerary-card").forEach(dayCard => {
			const day = dayCard.querySelector(".hotel-select")?.dataset.day || "Unknown";

			const selectedAreas = [...dayCard.querySelectorAll(".area-select[data-day]")]
				.map(area => area.value.trim())
				.filter(value => value !== "");

			const selectedMealPlans = [...dayCard.querySelectorAll(".meal-plan-select[data-day]")]
				.map(meal => meal.value.trim())
				.filter(value => value !== "");

			const selectedHotels = [...dayCard.querySelectorAll(".hotel-select")]
				.map(select => select.value.trim())
				.filter(value => value !== "");

			const selectedItineraries = [...dayCard.querySelectorAll(".itinerary-select")]
				.map(select => select.value.trim())
				.filter(value => value !== "");

			itineraryData.push({
				day,
				areas: selectedAreas.length ? selectedAreas : [""],
				meal_plans: selectedMealPlans.length ? selectedMealPlans : [""],
				hotels: selectedHotels.length ? selectedHotels : [""],
				itineraries: selectedItineraries.length ? selectedItineraries : [""]
			});
		});

		// Console Output
		console.group("📦 Submitting Itinerary");
		console.table({
			selectedPackage, noOfDays, startDate, endDate, guideName, accountId,
			countryCode, contactNumber,
			city1, hotel1, city2, hotel2, city3, hotel3
		});
		console.table(itineraryData);
		console.groupEnd();

		// Disable submit button during AJAX
		const submitButton = document.getElementById("submitTour");
		submitButton.disabled = true;

		// AJAX Submit
		$.ajax({
			url: "../Employee Section/functions/emp-saveItinerary.php",
			type: "POST",
			data: {
				noOfDays: noOfDays,
				package: selectedPackage,
				period_start: startDate,
				period_end: endDate,
				countryCode: countryCode,
				contactNumber: contactNumber,
				guideAccountId: accountId,
				guide: guideName,
				city1: city1,
				hotel1: hotel1,
				city2: city2,
				hotel2: hotel2,
				city3: city3,
				hotel3: hotel3,
				itinerary: JSON.stringify(itineraryData),
				templateName: templateName
			},
			dataType: "json",
			success: function (response) {
				submitButton.disabled = false;

				if (response.status === "success") {
					alert("Itinerary successfully created! Redirecting to Itinerary Table");
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

		// Close modal using Bootstrap 5 API
		const modalInstance = bootstrap.Modal.getInstance(document.getElementById("templateNameModal"));
		if (modalInstance) {
			modalInstance.hide();
		}
	}
</script>



</body>

</html>