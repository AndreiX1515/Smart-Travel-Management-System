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

				<!-- Itinerary Details Card -->
				<div class="card">
					<div class="card-header bg-primary">
						<h5>Generate Itinerary</h5>
					</div>

					<div class="card-body">

						<!-- Package Row -->
						<div class="row">
							<!-- Flight Date Dropdown -->
							<div class="columns col-md-4">
								<div class="column-header">
									<label for="flightDate">Package
										<span class="text-danger"> *</span>
									</label>
								</div>

								<div class="form-group">

									<select class="form-select" id="packageSelect" name="packageSelect" required>
										<option selected disabled>Select Package Type</option>
										<?php
										// Execute the SQL query
										$sql1 = "SELECT packageName FROM package ORDER BY packageId ASC";
										$res1 = $conn->query($sql1);

										// Check if there are results
										if ($res1->num_rows > 0) {
											// Loop through the results and generate options
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

							<!-- Flight Date Dropdown -->
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
												<input type="text" class="datepicker form-control" id="PeriodStartDate" placeholder="Start Date"
													readonly>
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
													readonly>
												<i class="fas fa-calendar-alt calendar-icon position-absolute"
													style="right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
											</div>
										</div>
									</div>
								</div>


							</div>

							<div class="columns col-md-4">
								<div class="column-header">
									<label for="flightDate">Guide
										<span class="text-danger"> *</span>
									</label>
								</div>

								<div class="form-group">
									<select class="form-select" id="guideName" name="guideName" required onchange="updateContact(this)">
										<option selected disabled>Select Guide</option>
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

											$middleInitial = !empty($mName) ? strtoupper(substr($mName, 0, 1)) . '.' : '';
											$fullName = $lName . ', ' . $fName . ($middleInitial ? ' ' . $middleInitial : '');

											// Embed contactNo and countryCode as data attributes
											echo "<option value=\"$fullName\" data-contact=\"$contactNo\" data-code=\"$countryCode\">$fullName</option>";
										}
										?>
									</select>
								</div>


							</div>


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
						<div class="row">
							<div class="columns col-md-8">
								<div class="column-header">
									<label for="flightDate">Tour Areas, Hotels <span class="text-danger">
											*</span></label>
								</div>
								<div class="cityhotel-wrapper">

									<div class="cityhotel-item">
										<div class="form-group d-flex flex-row align-items-center">
											<select class="form-select city-select" id="city1" name="city(1)" required>
												<option selected disabled>Select City</option>
											</select>
										</div>
									</div>

									<div class="dash-separator">-></div>

									<div class="cityhotel-item">
										<div class="form-group d-flex flex-row align-items-center">
											<select class="form-select hotel-select" id="hotel1" name="hotel(1)" required>
												<option selected disabled>Select Hotel</option>
											</select>
										</div>
									</div>

								</div>
							</div>
						</div>

						<div class="row">
							<div class="columns col-md-8">
								<div class="cityhotel-wrapper">
									<div class="cityhotel-item">
										<div class="form-group d-flex flex-row align-items-center">
											<select class="form-select city-select" id="city2" name="city(2)" required>
												<option selected disabled>Select City</option>
											</select>
										</div>
									</div>
									<div class="dash-separator">-></div>
									<div class="cityhotel-item">
										<div class="form-group d-flex flex-row align-items-center">
											<select class="form-select hotel-select" id="hotel2" name="hotel(2)" required>
												<option selected disabled>Select Hotel</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="columns col-md-8">
								<div class="cityhotel-wrapper">
									<div class="cityhotel-item">
										<div class="form-group d-flex flex-row align-items-center">
											<select class="form-select city-select" id="city3" name="city(3)" required>
												<option selected disabled>Select City</option>
											</select>
										</div>
									</div>
									<div class="dash-separator">-></div>
									<div class="cityhotel-item">
										<div class="form-group d-flex flex-row align-items-center">
											<select class="form-select hotel-select" id="hotel3" name="hotel(3)" required>
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
				<div class="card">
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
				<button type="button" class="btn btn-primary" id="submitTour">Generate Itinerary</button>
			</div>
		</div>

	</div>

	<!-- Modal -->
	<div class="modal fade" id="templateNameModal" tabindex="-1" aria-labelledby="templateNameModalLabel" aria-hidden="true">
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
			// Cities and Hotels Data
			const cities = ["Seoul", "Gyeonggi-do", "Incheon", "Jeju"];

			const hotels = {
				"Seoul": ["Smart Stay Hotel"],
				"Gyeonggi-do": ["Ramada Hotel", "Marina Bay Hotel"],
				"Incheon": ["Air Sky Hotel", "Royal Emporium"],
				"Jeju": ["Tamara Hotel"]
			};

			const selectedHotels = {}; // To track selected hotels for each row

			// Load stored hotel selections
			loadStoredHotels();

			// Populate city dropdowns
			document.querySelectorAll(".city-select").forEach((select, index) => {
				populateDropdown(select, cities, "Select City");
				select.addEventListener("change", () => updateHotelDropdown(select, index + 1));
			});

			// Function to populate dropdowns (City or Hotel)
			function populateDropdown(select, optionsList, placeholderText) {
				if (!select) return;

				select.innerHTML = `<option selected disabled>${placeholderText}</option>`;
				optionsList.forEach(optionValue => {
					const option = document.createElement("option");
					option.value = optionValue;
					option.textContent = optionValue;
					select.appendChild(option);
				});
			}

			// Function to update the corresponding Hotel dropdown when a City is selected
			function updateHotelDropdown(citySelect, rowIndex) {
				const row = citySelect.closest(".row"); // Get the parent row
				const hotelSelect = row.querySelector(".hotel-select"); // Find corresponding hotel select

				if (!hotelSelect) return;

				const selectedCity = citySelect.value;
				hotelSelect.innerHTML = `<option selected disabled>Select Hotel</option>`; // Reset hotels

				if (hotels[selectedCity]) {
					// Filter out already selected hotels in other rows
					const availableHotels = hotels[selectedCity].filter(hotel => !Object.values(selectedHotels).includes(hotel));

					// Populate hotel dropdown with filtered hotels
					populateDropdown(hotelSelect, availableHotels, "Select Hotel");

					// Disable selected hotels in other rows for the same city
					disableOtherHotels(selectedCity);
				}

				console.log(`Row ${rowIndex}: City Selected - ${selectedCity}`);
			}

			// Disable selected hotels in other dropdowns for the same city
			function disableOtherHotels(city) {
				const allHotelSelects = document.querySelectorAll(".hotel-select");

				allHotelSelects.forEach(select => {
					const selectedHotel = select.value;
					const citySelect = select.closest(".row").querySelector(".city-select");

					// Disable the option if it is already selected in another row for the same city
					if (citySelect.value === city) {
						const options = select.querySelectorAll("option");
						options.forEach(option => {
							if (selectedHotel === option.value) {
								option.disabled = true;
							} else {
								option.disabled = false;
							}
						});
					}
				});
			}

			// Function to load stored hotels from localStorage
			function loadStoredHotels() {
				const storedHotels = JSON.parse(localStorage.getItem("selectedHotels")) || {};
				document.querySelectorAll(".hotel-select").forEach((select, index) => {
					const row = select.closest(".row");
					const citySelect = row.querySelector(".city-select");
					const rowIndex = index + 1;

					if (citySelect) {
						const selectedCity = citySelect.value;
						if (selectedCity && hotels[selectedCity]) {
							populateDropdown(select, hotels[selectedCity], "Select Hotel");
							if (storedHotels[selectedCity]) {
								select.value = storedHotels[selectedCity];
							}
						}
					}

					console.log(`Row ${rowIndex}: Loaded City - ${citySelect?.value || "None"}, Hotel - ${select.value || "None"}`);
				});
			}

			// Save selected hotels to localStorage
			document.body.addEventListener("change", (event) => {
				if (event.target.classList.contains("hotel-select")) {
					const row = event.target.closest(".row");
					const citySelect = row.querySelector(".city-select");
					const rowIndex = Array.from(document.querySelectorAll(".row")).indexOf(row) + 1;
					if (!citySelect) return;

					const selectedCity = citySelect.value;
					const selectedHotel = event.target.value;

					// Update selectedHotels to track the hotel selection for the row
					if (!selectedHotels[selectedCity]) {
						selectedHotels[selectedCity] = [];
					}
					selectedHotels[selectedCity].push(selectedHotel);

					// Save to localStorage
					let storedHotels = JSON.parse(localStorage.getItem("selectedHotels")) || {};
					storedHotels[selectedCity] = selectedHotel;
					localStorage.setItem("selectedHotels", JSON.stringify(storedHotels));

					console.log(`Row ${rowIndex}: City - ${selectedCity}, Hotel - ${selectedHotel}`);
				}
			});

			// Observe dynamically added elements
			const observer = new MutationObserver(() => {
				document.querySelectorAll(".hotel-select").forEach(select => {
					if (!select.hasAttribute("data-initialized")) {
						select.setAttribute("data-initialized", "true");
						const row = select.closest(".row");
						const citySelect = row.querySelector(".city-select");
						const rowIndex = Array.from(document.querySelectorAll(".row")).indexOf(row) + 1;
						if (citySelect) {
							updateHotelDropdown(citySelect, rowIndex);
						}
					}
				});
			});

			observer.observe(document.body, {
				childList: true,
				subtree: true
			});
		});
	</script>


	<script>
		const selectDays = document.getElementById("select-days");
		const itineraryContainer = document.getElementById("itinerary-container");
		const formFooter = document.querySelector(".form-footer");
		const submitBtn = document.getElementById("submit-itinerary");

		// Korean Tour Data
		const koreanTourAreas = ["Seoul", "Gyeonggi-do", "Incheon", "Jeju"];

		const hotelsByArea = {
			"Seoul": ["Smart Stay Hotel"],
			"Gyeonggi-do": ["Ramada Hotel", "Marina Bay Hotel"],
			"Incheon": ["Air Sky Hotel", "Royal Emporium Hotel"],
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



		const itinerariesByDay = {
			1: [
				"Arrive at Incheon Airport, transfer to the hotel",
				"Check in and freshen up at the hotel",
				"Namsan Seoul Tower",
				"Bukchon Hanok Village"
			],
			2: [
				"Breakfast in Hotel",
				"Lotte World Adventure",
				"Han River Cruise",
				"Itaewon Culture Walk",
				"Gyeongbokgung Palace Tour"
			],
			3: [
				"Busan Gamcheon Culture Village",
				"Haeundae Beach",
				"Busan Tower",
				"Jagalchi Fish Market",
				"Dongbaekseom Island"
			],
			4: [
				"Jeju Island Lava Tubes",
				"Manjanggul Cave",
				"Seongsan Ilchulbong Peak",
				"Jeju Folk Village",
				"Hallim Park"
			],
			5: [
				"Nami Island Day Trip",
				"Petite France",
				"The Garden of Morning Calm",
				"Korean Folk Village",
				"COEX Mall & Aquarium"
			]
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

		// Render itinerary cards for each day
		for (let day = 1; day <= selectedDays; day++) {
			const card = document.createElement("div");
			card.className = "card itinerary-card mb-3";

			// Conditional layout based on day
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
																		}
																		</select>
																</div>`

					: ["Area 1", "Area 2", "Area 3"].map(areaLabel => `
																		<div class="col-4">
																				<label class="form-label fw-semibold">${areaLabel}:</label>    
																				<select class="form-select area-select" data-day="${day}" required>
																						<option selected disabled>Select ${areaLabel}</option>
																						${koreanTourAreas.map(area => `<option value="${area}">${area}</option>`).join("")}
																				</select>
																		</div>
																`).join("")
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
														<label class="form-label fw-semibold">${mealType.charAt(0).toUpperCase() + mealType.slice(1)}:</label>
														<select class="form-select meal-plan-select" data-day="${day}" data-meal="${mealType}" required>
														<option selected disabled>Select ${mealType.charAt(0).toUpperCase() + mealType.slice(1)}</option>
														${koreanMealPlans[mealType].map(meal => `<option value="${meal}">${meal}</option>`).join("")}
														</select>
												</div>
												`).join("")

				}
												</div>

												<!-- Hotels (based on selected areas) -->
												<div class="row mb-3">
														<div class="col-12">
																<label class="form-label fw-semibold">Hotels:</label>
																<div class="row">
																		${["Hotel 1", "Hotel 2"].map(hotelLabel => {
								const hotelOptions = day === 1
									? ["Air Sky Hotel", "Royal Emporium Hotel"].map(hotel => `<option value="${hotel}">${hotel}</option>`).join("")
									: "<!-- Options will be dynamically added based on area selections -->";

								return `
									<div class="col-md-4 col-sm-12 mb-2">
											<select class="form-select hotel-select" data-day="${day}" required>
													<option selected disabled>Select ${hotelLabel}</option>
													${hotelOptions}
											</select>
									</div>`;
									}).join("")}

																</div>
														</div>
												</div>


												<!-- Itineraries -->
												<div class="row mb-3">
														<div class="col-12">
																<label class="form-label fw-semibold">Itinerary:</label>
														</div>
														${(day === 1 ? [1, 2, 3, 4] : [1, 2, 3, 4, 5, 6, 7]).map(num => `
																<div class="col-12 mb-2">
																		<select class="form-select itinerary-select" data-day="${day}" required>
																				<option selected disabled>Select Itinerary ${num}</option>
																				${(itinerariesByDay[day] || []).map(itinerary => `
																								<option value="${itinerary}">${itinerary}</option>
																						`).join("")
					}
																		</select>
																</div>
														`).join("")}
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
				const selectedAreas = Array.from(document.querySelectorAll(`.area-select[data-day="${day}"]`))
					.map(select => select.value)
					.filter(val => val !== "Select Area" && val !== "");

				const allHotels = selectedAreas.flatMap(area => hotelsByArea[area] || []);
				const hotelSelects = document.querySelectorAll(`.hotel-select[data-day="${day}"]`);

				hotelSelects.forEach(hotelSelect => {
					const currentValue = hotelSelect.value;
					hotelSelect.innerHTML = `<option selected disabled>Select Hotel</option>` +
						allHotels.concat("No Hotel").map(hotel => `<option value="${hotel}" ${hotel === currentValue ? "selected" : ""}>${hotel}</option>`).join("");
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

		document.getElementById("submit-itinerary").addEventListener("click", function (event) {
			const selects = document.querySelectorAll(".form-select");
			let isValid = true;

			// Loop through all select elements and check if any are not selected
			selects.forEach(select => {
				if (!select.value) {
					select.classList.add("is-invalid"); // Optionally add invalid styling
					isValid = false;
				} else {
					select.classList.remove("is-invalid");
				}
			});

			// If the form is valid, proceed with the action
			if (isValid) {
				// Submit the form or take the next action (e.g., create itinerary)
				alert("Itinerary successfully created!");
				// Call next step like redirecting or performing another action
			} else {
				// Prevent form submission or alert user to fill all fields
				alert("Please fill in all required fields.");
			}
		});
	</script>

	<!-- Form Submission Script -->
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

			const itineraryData = [];

			const selectedPackage = document.getElementById("packageSelect")?.value.trim() || "None";
			const noOfDays = document.getElementById("select-days")?.value.trim() || "None";
			const startDate = document.getElementById("PeriodStartDate")?.value.trim() || "None";
			const endDate = document.getElementById("PeriodEndDate")?.value.trim() || "None";
			const guideName = document.getElementById("guideName")?.value.trim() || "None";
			const countryCode = document.getElementById("countryCode")?.value.trim() || "None";
			const contactNumber = document.getElementById("contactNumber")?.value.trim() || "None";

			const city1 = document.getElementById("city1")?.value.trim() || "None";
			const hotel1 = document.getElementById("hotel1")?.value.trim() || "None";
			const city2 = document.getElementById("city2")?.value.trim() || "None";
			const hotel2 = document.getElementById("hotel2")?.value.trim() || "None";
			const city3 = document.getElementById("city3")?.value.trim() || "None";
			const hotel3 = document.getElementById("hotel3")?.value.trim() || "None";

			document.querySelectorAll(".itinerary-card").forEach(dayCard => {
				const day = dayCard.querySelector(".hotel-select")?.dataset.day || "Unknown";

				const selectedAreas = [...dayCard.querySelectorAll(".area-select[data-day]")].map(area => area.value.trim()).filter(value => value !== "");
				const selectedMealPlans = [...dayCard.querySelectorAll(".meal-plan-select[data-day]")].map(meal => meal.value.trim()).filter(value => value !== "");
				const selectedHotels = [...dayCard.querySelectorAll(".hotel-select")].map(select => select.value.trim()).filter(value => value !== "");
				const selectedItineraries = [...dayCard.querySelectorAll(".itinerary-select")].map(select => select.value.trim()).filter(value => value !== "");

				itineraryData.push({
					day,
					areas: selectedAreas.length ? selectedAreas : ["None"],
					meal_plans: selectedMealPlans.length ? selectedMealPlans : ["None"],
					hotels: selectedHotels.length ? selectedHotels : ["None"],
					itineraries: selectedItineraries.length ? selectedItineraries : ["None"]
				});
			});

			console.group("📌 Submitting Itinerary Data");

			console.table({
				selectedPackage,
				startDate,
				endDate,
				guideName,
				city1,
				hotel1,
				city2,
				hotel2,
				city3,
				hotel3
			});

			console.table(itineraryData);
			console.groupEnd();

			const submitButton = document.getElementById("submitTour");
			submitButton.disabled = true;

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
					guide: guideName,
					city1: city1,
					hotel1: hotel1,
					city2: city2,
					hotel2: hotel2,
					city3: city3,
					hotel3: hotel3,
					itinerary: JSON.stringify(itineraryData),
					templateName: templateName // Pass only the template name
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