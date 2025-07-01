<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Itinerary Details</title>
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

		<!-- Navbar Back Script -->
		<script>
			document.getElementById('redirect-btn').addEventListener('click', function () {
				window.location.href = '../Employee Section/emp-itineraryTable.php'; // Replace with your actual URL
			});
		</script>

		<!-- DB Query for Itinerary Details based on Itinerary ID -->
		<!-- DB Query for Itinerary Details based on Itinerary ID -->
		<?php
		if (!isset($_GET['id'])) {
			die("Invalid Itinerary ID");
		}

		$itineraryId = intval($_GET['id']); // Sanitize input

		// Fetch itinerary main details with guide info
		$sql = "
			SELECT 
				i.itineraryName,
				i.noOfDays,
				i.packageId,
				i.periodStart,
				i.periodEnd,
				e.fName AS guideFirstName,
				e.lName AS guideLastName,
				e.countryCode,
				e.contactNo
			FROM itineraries i
			LEFT JOIN employee e ON i.guideId = e.id
			WHERE i.itineraryId = ?
		";

		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $itineraryId);
		$stmt->execute();
		$result = $stmt->get_result();

		if (!$row = $result->fetch_assoc()) {
			die("Itinerary not found");
		}

		// Format guide full name
		$guideName = isset($row['guideFirstName'], $row['guideLastName']) 
			? trim($row['guideFirstName'] . ' ' . $row['guideLastName']) 
			: '';

		$itinerary = [
			'itineraryId' => $itineraryId,
			'itineraryName' => $row['itineraryName'],
			'noOfDays' => $row['noOfDays'],
			'packageId' => $row['packageId'],
			'periodStart' => $row['periodStart'],
			'periodEnd' => $row['periodEnd'],
			'guideName' => $guideName,
			'countryCode' => $row['countryCode'] ?? '',
			'contactNumber' => $row['contactNo'] ?? '',
			'cities' => [],
			'days' => []
		];

		// ✅ Fetch cities & hotels from itinerarytourareashotels
		$sqlCityHotel = "SELECT city, hotel FROM itinerarytourareashotels WHERE itineraryId = ? ORDER BY orderNo ASC";
		$stmt = $conn->prepare($sqlCityHotel);
		$stmt->bind_param("i", $itineraryId);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($rowCity = $result->fetch_assoc()) {
			$itinerary['cities'][] = [
				'city' => $rowCity['city'],
				'hotel' => $rowCity['hotel']
			];
		}

		// ✅ Fetch daily itinerary breakdown
		$sqlDays = "
			SELECT 
				d.dayId, 
				d.dayNumber, 
				COALESCE(a.areas, '') AS areas,
				COALESCE(h.hotels, '') AS hotels,
				COALESCE(act.activities, '') AS activities,
				COALESCE(mp.meals, '') AS meals
			FROM itinerarydays d
			LEFT JOIN (
				SELECT dayId, GROUP_CONCAT(DISTINCT areaName ORDER BY itineraryAreaId ASC SEPARATOR ',') AS areas
				FROM itineraryareas 
				GROUP BY dayId
			) a ON d.dayId = a.dayId
			LEFT JOIN (
				SELECT dayId, GROUP_CONCAT(DISTINCT hotelName ORDER BY hotelId ASC SEPARATOR ',') AS hotels
				FROM itineraryhotels 
				GROUP BY dayId
			) h ON d.dayId = h.dayId
			LEFT JOIN (
				SELECT dayId, GROUP_CONCAT(activityName ORDER BY activityId ASC SEPARATOR ',') AS activities
				FROM itineraryactivities 
				GROUP BY dayId
			) act ON d.dayId = act.dayId
			LEFT JOIN (
				SELECT dayId, GROUP_CONCAT(DISTINCT mealPlan ORDER BY mealId ASC SEPARATOR ',') AS meals
				FROM itinerarymealplans 
				GROUP BY dayId
			) mp ON d.dayId = mp.dayId
			WHERE d.itineraryId = ?
			ORDER BY d.dayNumber ASC
		";

		$stmt = $conn->prepare($sqlDays);
		$stmt->bind_param("i", $itineraryId);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($day = $result->fetch_assoc()) {
			$itinerary['days'][] = [
				'day' => $day['dayNumber'],
				'areas' => $day['areas'] ? array_map('trim', explode(',', $day['areas'])) : [],
				'hotels' => $day['hotels'] ? array_map('trim', explode(',', $day['hotels'])) : [],
				'activities' => $day['activities'] ? array_map('trim', explode(',', $day['activities'])) : [],
				'meals' => $day['meals'] ? array_map('trim', explode(',', $day['meals'])) : []
			];
		}

		// Output to console
		$jsonData = json_encode($itinerary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

		echo "<script>console.log($jsonData);</script>";
		?>



		<div class="main-content">
			<input type="hidden" id="itineraryId" value="<?= htmlspecialchars($itineraryId); ?>" readonly>

			<div class="form-container-wrapper">

				<div class="card">
					<div class="card-header">
						<h5>Itinerary Details</h5>
					</div>

					<div class="card-body">

						<!-- Package Row -->
						<div class="row mb-2">

							<div class="columns col-md-4">
								<div class="column-header">
									<label for="flightDate">Itinerary Name:
										<span class="text-danger"> *</span>
									</label>
								</div>

								<div class="form-group">
									<input type="text" class="form-control" id="itineraryName" name="itineraryName"
										value="<?= $itinerary['itineraryName']; ?>" required>
								</div>
							</div>


							<div class="columns col-md-4">
								<div class="column-header">
									<label for="packageSelect">Package
										<span class="text-danger"> *</span>
									</label>
								</div>

								<div class="form-group">
									<select class="form-select" id="packageSelect" name="packageSelect" required>
										<?php
										$selectedPackageId = $itinerary['packageId'];

										// Fetch all packages
										$sql1 = "SELECT packageId, packageName FROM package ORDER BY packageId ASC";
										$res1 = $conn->query($sql1);

										if ($res1->num_rows > 0) {
											while ($row = $res1->fetch_assoc()) {
												$selected = ($row['packageId'] == $selectedPackageId) ? 'selected' : '';
												echo "<option value='{$row['packageId']}' $selected>{$row['packageName']}</option>";
											}
										} else {
											echo "<option value=''>No packages available</option>";
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
													value="<?= $itinerary['periodStart']; ?>" readonly required>
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
													value="<?= $itinerary['periodEnd']; ?>" readonly required>
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
										<?php
										$selectedGuide = $itinerary['guideName'];
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

											$isSelected = ($selectedGuide == $fullName) ? 'selected' : '';

											echo "<option value=\"$fullName\" data-contact=\"$contactNo\" data-code=\"$countryCode\" $isSelected>$fullName</option>";
										}
										?>
									</select>
								</div>

							</div>

							<div class="columns col-md-4">
								<div class="column-header">
									<label for="flightDate">Contact Number
										<span class="text-danger"> *</span>
									</label>
								</div>

								<div class="form-group d-flex flex-row align-items-center">
									<!-- Country Code Dropdown -->
									<select class="form-select" id="countryCode" style="width: 100px;">
										<option value="" disabled selected>Select Country Code</option>
										<option value="+82" <?= ($itinerary['countryCode'] == '+82') ? 'selected' : ''; ?>>
											+82</option>
										<option value="+1" <?= ($itinerary['countryCode'] == '+1') ? 'selected' : ''; ?>>+1
										</option>
										<option value="+44" <?= ($itinerary['countryCode'] == '+44') ? 'selected' : ''; ?>>
											+44</option>
										<option value="+91" <?= ($itinerary['countryCode'] == '+91') ? 'selected' : ''; ?>>
											+91</option>
										<option value="+63" <?= ($itinerary['countryCode'] == '+63') ? 'selected' : ''; ?>>
											+63</option>

										<!-- Add more country codes as needed -->
									</select>

									<!-- Contact Number Input -->
									<input type="text" class="form-control ms-2" id="contactNumber" name="contactNumber"
										value="<?= htmlspecialchars($itinerary['contactNumber']); ?>" required
										placeholder="Enter Contact Number">
								</div>
							</div>

						</div>

						<div class="column-header mb-2">
							<label for="">Tour Area, Hotels
								<span class="text-danger"> *</span>
							</label>
						</div>

						<?php
						$cities = ["Seoul", "Gyeonggi-do", "Incheon", "Jeju"];
						$hotels = [
							"Seoul" => ["Smart Stay Hotel"],
							"Gyeonggi-do" => ["Ramada Hotel", "Marina Bay Hotel"],
							"Incheon" => ["Air Sky Hotel", "Royal Emporium"],
							"Jeju" => ["Tamara Hotel"]
						];

						for ($i = 0; $i < 3; $i++) {
							$cityKey = "city" . ($i + 1);
							$hotelKey = "hotel" . ($i + 1);
							$selectedCity = $itinerary['cities'][$i]['city'] ?? "";
							$selectedHotel = $itinerary['cities'][$i]['hotel'] ?? "";
						?>

							<div class="row mb-3 cityhotel-row">
								<div class="columns col-md-8">
									<div class="cityhotel-wrapper d-flex flex-row align-items-center gap-2">

										<!-- City dropdown -->
										<div class="cityhotel-item">
											<div class="form-group d-flex flex-row align-items-center">
												<select class="form-select city-select" id="<?= $cityKey ?>" name="city(<?= $i + 1 ?>)" data-index="<?= $i ?>" required>
													<option value="" disabled <?= empty($selectedCity) ? 'selected' : '' ?>>Select City</option>
													<?php foreach ($cities as $city): ?>
														<option value="<?= $city ?>" <?= $selectedCity === $city ? 'selected' : '' ?>><?= $city ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

										<div class="dash-separator">-></div>

										<!-- Hotel dropdown -->
										<div class="cityhotel-item">
											<div class="form-group d-flex flex-row align-items-center">
												<select class="form-select hotel-select" id="<?= $hotelKey ?>" name="hotel(<?= $i + 1 ?>)" required>
													<option value="" disabled <?= empty($selectedHotel) ? 'selected' : '' ?>>Select Hotel</option>

													<?php
													if (!empty($selectedCity) && isset($hotels[$selectedCity])) {
														foreach ($hotels[$selectedCity] as $hotel): ?>
															<option value="<?= $hotel ?>" <?= $selectedHotel === $hotel ? 'selected' : '' ?>><?= $hotel ?></option>
														<?php endforeach;
													}
													?>
												</select>
											</div>
										</div>

										<!-- Trash button -->
										<button type="button" class="btn btn-danger btn-sm remove-cityhotel <?= ($selectedCity || $selectedHotel) ? '' : 'd-none' ?>">
											<i class="fas fa-trash-alt"></i>
										</button>

									</div>
								</div>
							</div>

						<?php } ?>



							<script>
								document.addEventListener('change', function (e) {
									// Show/Hide Trash Icon for City/Hotel Combo Row
									if (e.target.classList.contains('city-select') || e.target.classList.contains('hotel-select')) {
										const row = e.target.closest('.cityhotel-row');
										const city = row.querySelector('.city-select')?.value;
										const hotel = row.querySelector('.hotel-select')?.value;
										const trashBtn = row.querySelector('.remove-cityhotel');

										if (trashBtn) {
											trashBtn.classList.toggle('d-none', !(city || hotel));
										}
									}
								});

								document.addEventListener('click', function (e) {
									const btn = e.target.closest('.remove-cityhotel');
									if (btn) {
										const row = btn.closest('.cityhotel-row');
										const citySelect = row.querySelector('.city-select');
										const hotelSelect = row.querySelector('.hotel-select');

										if (citySelect) {
											citySelect.selectedIndex = 0; // Reset to placeholder
											citySelect.dispatchEvent(new Event("change"));
										}

										if (hotelSelect) {
											hotelSelect.selectedIndex = 0; // Reset to placeholder
											hotelSelect.dispatchEvent(new Event("change"));
										}

										btn.classList.add('d-none');
									}
								});
							</script>

					</div>
				</div>


				<div class="card select-days-card">
					<div class="card-header">
						<h5 class="fw-bold">No. of Days</h5>
					</div>

					<div class="card-body">

						<div class="row">
							<div class="columns col-md-3">
								<div class="form-group days-select-wrapper">
									<label for="flightDate">No. of days<span class="text-danger"> *</span></label>
									<select class="form-select" id="select-days" name="numberOfDays" required>
										<option value="<?= $noOfDays; ?>" selected>Day <?= $noOfDays; ?></option>
										<!-- Keeps preselected value -->
									</select>

									<!-- <small class="form-text text-muted">Changing this will clear all your data on the fields.</small> -->
								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="itinerary-container" id="itinerary-container"> </div>

			</div>

			<!-- Select at the top -->


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

			<!-- JavaScript to handle file format selection -->
			<script>
				document.addEventListener('DOMContentLoaded', function () {
					const actionSelector = document.getElementById('actionSelector');
					const submitEditBtn = document.getElementById('submitEdit');
					const submitTourBtn = document.getElementById('submitTour');

					// Initial check based on the selected option (default: XLSX)
					toggleButtons(actionSelector.value);

					// On change event
					actionSelector.addEventListener('change', function () {
						toggleButtons(this.value);
					});

					function toggleButtons(value) {
						if (value === 'xlsx') {
							submitTourBtn.innerText = 'Generate XLSX Itinerary'; // Update button text for XLSX
						} else {
							submitTourBtn.innerText = 'Generate PDF Itinerary'; // Update button text for PDF
						}
					}
				});
			</script>

		</div>
	</div>


	<!-- Modal - Template Name -->
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
					<div class="mt-3">
						<label for="templateName" class="form-label">Template Name:</label>
						<input type="text" class="form-control" id="templateName" placeholder="Enter template name">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" id="confirmTemplateName">Proceed</button>
				</div>
			</div>
		</div>
	</div>

	<?php include '../Employee Section/includes/emp-scripts.php' ?>

	<!-- For Periods Datepickers -->
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

	<!-- For Itinerary Card -->
	<script>

		let liveItineraryData;
		const originalJsonData = <?= json_encode($jsonData) ?>;
		const originalJsonString = JSON.stringify(sortKeys(originalJsonData));

		// Utility to deeply sort object keys for comparison
		function sortKeys(obj) {
			if (Array.isArray(obj)) {
				return obj.map(sortKeys);
			} else if (obj !== null && typeof obj === 'object') {
				return Object.keys(obj).sort().reduce((acc, key) => {
					acc[key] = sortKeys(obj[key]);
					return acc;
				}, {});
			}
			return obj;
		}

		document.addEventListener("DOMContentLoaded", function () {
			const submitEditBtn = document.getElementById('submitEdit');
			const generateBtn = document.getElementById('submitTour');
			const selectDays = document.getElementById('select-days');
			const itineraryContainer = document.getElementById("itinerary-container");

			submitEditBtn.disabled = true;
			selectDays.disabled = true;
			if (generateBtn) generateBtn.disabled = true;

			let itineraryData = <?= json_encode($itinerary); ?>;
			liveItineraryData = JSON.parse(JSON.stringify(itineraryData)); // Clone

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
					itineraryId: 1,
					itineraryName,
					packageName: packageSelect,
					periodStart,
					periodEnd,
					guideName,
					countryCode,
					contactNumber,
					cities,
					noOfDays: parseInt(selectDays.value)
				};

				const daysDetails = [];
				const cards = itineraryContainer.querySelectorAll(".itinerary-card");

				cards.forEach((card, index) => {
					const areas = Array.from(card.querySelectorAll(".area-select")).map(sel => sel.value);
					const meals = Array.from(card.querySelectorAll(".meal-plan-select")).map(sel => sel.value);
					let hotels = Array.from(card.querySelectorAll(".hotel-select")).map(sel => sel.value);
					const activities = Array.from(card.querySelectorAll(".itinerary-select")).map(sel => sel.value);

					while (hotels.length < 2) hotels.push("");
					while (areas.length < 3) areas.push("");

					daysDetails.push({
						day: index + 1,
						areas,
						meals,
						hotels,
						activities
					});
				});

				// Update global reference
				liveItineraryData = {
					itineraryDetails,
					daysDetails
				};

				// const currentString = JSON.stringify(sortKeys(liveItineraryData));
				// const originalSorted = JSON.stringify(sortKeys(originalJsonData));
				// const isSame = currentString === originalSorted;

				// Update button states
				// if (generateBtn) generateBtn.disabled = isSame;
				// if (submitEditBtn) submitEditBtn.disabled = isSame;

				console.log(JSON.stringify(liveItineraryData, null, 2));
			};


			document.addEventListener("DOMContentLoaded", function () {
				document.getElementById('submitEdit').disabled = true;
				document.getElementById('select-days').disabled = true;

				// Disable "Generate Itinerary" initially if no changes
				const generateBtn = document.getElementById("submitTour");
				if (generateBtn) {
					generateBtn.disabled = true;
				}
			});


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




			// Areas
			const koreanTourAreas = ["Seoul", "Busan", "Jeju", "Incheon", "Gyeongju"];

			// Area-specific Hotels
			const koreanHotels = {
				"Seoul": ["Smart Stay Hotel", "Royal Emporium Hotel"],
				"Busan": ["Marina Bay Hotel", "Ramada Hotel"],
				"Jeju": ["Tamara Hotel", "Smart Stay Hotel"],
				"Incheon": ["Air Sky Hotel", "Royal Emporium Hotel"],
				"Gyeongju": ["Ramada Hotel"]
			};

			// Meal Plans → Each meal type has multiple meal options
			const koreanMealPlans = {
				"Breakfast": [
					"Korean Traditional Breakfast Set",
					"Continental Breakfast",
					"Buffet Style Breakfast"
				],
				"Lunch": [
					"Bibimbap Set",
					"Street Food Sampler",
					"Seafood Hotpot",
					"Vegetarian Bulgogi"
				],
				"Dinner": [
					"Korean BBQ Experience",
					"Luxury Fine Dining",
					"Seafood Specialty Platter",
					"Hot Stone Grill"
				]
			};

			// Area-specific Itinerary Activities
			const itinerariesByArea = {
				"Seoul": [
					"Gyeongbokgung Palace Tour",
					"Myeongdong Shopping District",
					"Namsan Seoul Tower",
					"Bukchon Hanok Village",
					"Dongdaemun Design Plaza"
				],
				"Busan": [
					"Busan Gamcheon Culture Village",
					"Haeundae Beach Visit",
					"Jagalchi Fish Market Tour"
				],
				"Jeju": [
					"Jeju Island Lava Tubes",
					"Seongsan Ilchulbong Peak",
					"Manjanggul Cave"
				],
				"Incheon": [
					"Incheon Chinatown Walk",
					"Songdo Central Park Tour"
				],
				"Gyeongju": [
					"Bulguksa Temple Visit",
					"Cheomseongdae Observatory Tour"
				]
			};



			// Extract ordered values from `days` while keeping original order

			// ✅ Available Areas
			let availableAreas = [
				...extractValues(days, "areas"),
				...koreanTourAreas.filter(area => !days.some(day => (day.areas || []).includes(area)))
			];

			// ✅ Available Hotels (flatten from area-specific hotels)
			let existingHotels = extractValues(days, "hotels");
			let allHotels = Object.values(koreanHotels).flat();
			let availableHotels = [...existingHotels, ...allHotels.filter(h => !existingHotels.includes(h))];

			// ✅ Available Meals (flatten from meal types)
			let existingMeals = extractValues(days, "meals");
			let allMeals = Object.values(koreanMealPlans).flat();
			let availableMeals = [...existingMeals, ...allMeals.filter(m => !existingMeals.includes(m))];

			// ✅ Available Itinerary Activities (flatten from itineraries by area)
			let existingActivities = extractValues(days, "activities");
			let allActivities = Object.values(itinerariesByArea).flat();
			let availableActivities = [...existingActivities, ...allActivities.filter(a => !existingActivities.includes(a))];

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



			// FOR ITINERARY SELECTS IN CARDS
			// Handle itinerary value changes
			document.addEventListener('change', function (e) {
				if (e.target.classList.contains('itinerary-select')) {
					const select = e.target;
					const trashBtn = select.closest('.itinerary-item')?.querySelector('.remove-itinerary');

					if (trashBtn) {
						trashBtn.classList.toggle('d-none', !select.value);
					}

					// 🔁 Update global JSON data
					updateLiveItineraryData();
				}
			});

			// Handle trash icon click
			document.addEventListener('click', function (e) {
				const btn = e.target.closest('.remove-itinerary');
				if (btn) {
					const item = btn.closest('.itinerary-item');
					const select = item?.querySelector('.itinerary-select');

					if (select) {
						select.value = ""; // Clear value
					}

					btn.classList.add('d-none');

					// ✅ Enable submitEdit button since a change occurred
					const editButton = document.getElementById("submitEdit");
					if (editButton && editButton.disabled) {
						editButton.disabled = false;
					}

					// 🔁 Update global JSON data
					updateLiveItineraryData();
				}
			});

			// FOR HOTELS SELECTS IN CARDS
			document.addEventListener('change', function (e) {
				if (e.target.classList.contains('hotel-select')) {
					const select = e.target;
					const trashBtn = select.closest('.hotel-select-item')?.querySelector('.remove-hotel-select');

					if (trashBtn) {
						// Show trash if a value is selected
						trashBtn.classList.toggle('d-none', !select.value);
					}

					updateLiveItineraryData();

					const editButton = document.getElementById("submitEdit");
					if (editButton && editButton.disabled) {
						editButton.disabled = false;
					}
				}
			});


			document.addEventListener('click', function (e) {
				const btn = e.target.closest('.remove-hotel-select');
				if (btn) {
					const item = btn.closest('.hotel-select-item');
					const select = item?.querySelector('.hotel-select');

					if (select) {
						select.value = ""; // Clear the hotel selection
						select.dispatchEvent(new Event("change")); // trigger change to update state
					}

					btn.classList.add('d-none');

					updateLiveItineraryData();

					const editButton = document.getElementById("submitEdit");
					if (editButton && editButton.disabled) {
						editButton.disabled = false;
					}
				}
			});


			// FOR AREA SELECTS IN CARDS
			document.addEventListener('change', function (e) {
				if (e.target.classList.contains('area-select')) {
					const select = e.target;
					const trashBtn = select.closest('.area-select-item')?.querySelector('.remove-area-select');

					if (trashBtn) {
						// Show trash if a value is selected
						trashBtn.classList.toggle('d-none', !select.value);
					}

					updateLiveItineraryData();

					const editButton = document.getElementById("submitEdit");
					if (editButton && editButton.disabled) {
						editButton.disabled = false;
					}
				}
			});

			document.addEventListener('click', function (e) {
				const btn = e.target.closest('.remove-area-select');
				if (btn) {
					const item = btn.closest('.area-select-item');
					const select = item?.querySelector('.area-select');

					if (select) {
						select.value = ""; // Clear the area selection
						select.dispatchEvent(new Event("change")); // trigger change to update state
					}

					btn.classList.add('d-none');

					updateLiveItineraryData();

					const editButton = document.getElementById("submitEdit");
					if (editButton && editButton.disabled) {
						editButton.disabled = false;
					}
				}
			});



			// FOR MEAL PLAN SELECTS IN CARDS
			document.addEventListener('change', function (e) {
				if (e.target.classList.contains('meal-plan-select')) {
					const select = e.target;
					const trashBtn = select.closest('.meal-plan-select-item')?.querySelector('.remove-meal-plan-select');

					if (trashBtn) {
						// Show trash if a value is selected
						trashBtn.classList.toggle('d-none', !select.value);
					}

					updateLiveItineraryData();

					const editButton = document.getElementById("submitEdit");
					if (editButton && editButton.disabled) {
						editButton.disabled = false;
					}
				}
			});

			document.addEventListener('click', function (e) {
				const btn = e.target.closest('.remove-meal-plan-select');
				if (btn) {
					const item = btn.closest('.meal-plan-select-item');
					const select = item?.querySelector('.meal-plan-select');

					if (select) {
						select.value = ""; // Clear the meal plan selection
						select.dispatchEvent(new Event("change")); // trigger change to update state
					}

					btn.classList.add('d-none');

					updateLiveItineraryData();

					const editButton = document.getElementById("submitEdit");
					if (editButton && editButton.disabled) {
						editButton.disabled = false;
					}
				}
			});


			// FOR SUBMIT EDIT BUTTON
			function deepEqual(obj1, obj2) {
				return JSON.stringify(sortKeys(obj1)) === JSON.stringify(sortKeys(obj2));
			}

			function sortKeys(obj) {
				if (Array.isArray(obj)) {
					return obj.map(sortKeys);
				} else if (obj !== null && typeof obj === 'object') {
					return Object.keys(obj).sort().reduce((result, key) => {
						result[key] = sortKeys(obj[key]);
						return result;
					}, {});
				}
				return obj;
			}

			// Main listener attachment
			function attachSelectChangeListeners() {
				const editButton = document.getElementById("submitEdit");
				const generateButton = document.getElementById("submitTour");

				// Attach listener to all selects inside cards
				document.querySelectorAll(".card select").forEach(select => {
					select.addEventListener("change", function () {
						// Always re-run the live data sync
						updateLiveItineraryData();

						// Enable the Edit button if not already
						if (editButton && editButton.disabled) {
							editButton.disabled = false;
						}

						// If city is selected, update hotel dropdown
						if (this.classList.contains("city-select")) {
							const index = this.dataset.index;
							const selectedCity = this.value;
							const hotelSelect = document.getElementById(`hotel${parseInt(index) + 1}`);
							if (hotelSelect) {
								updateHotelOptions(selectedCity, hotelSelect);
							}
						}

						// Compare and toggle Generate button (based on reversion to original)
						if (generateButton) {
							const isReverted = deepEqual(liveItineraryData, originalJsonData);
							generateButton.disabled = isReverted;
						}
					});
				});

				// Initial update
				updateLiveItineraryData();

				if (generateButton) {
					generateButton.disabled = deepEqual(liveItineraryData, originalJsonData);
				}
			}


			// Itinerary Area Select Column
			function createAreaSelectColumn(selectedValue, index = 1) {
				const uniqueOptions = [...new Set(availableAreas)].sort();
				const isPlaceholderSelected = !selectedValue || selectedValue === "null";

				return `
					<div class="col-4 d-flex align-items-end area-select-item">
						<div class="w-100 me-2">
							<label class="form-label fw-normal">Area ${index}:</label>
							<select class="form-select area-select" name="area_${index}">
								<option value="" disabled ${isPlaceholderSelected ? "selected" : ""}>Select Area ${index}</option>
								${uniqueOptions.map(opt => `
									<option value="${opt}" ${String(opt) === String(selectedValue) ? "selected" : ""}>${opt}</option>
								`).join("")}
							</select>
						</div>
						<button type="button" class="btn btn-danger btn-sm mt-4 remove-area-select ${selectedValue ? "" : "d-none"}">
							<i class="fas fa-trash-alt"></i>
						</button>
					</div>
				`;
			}


			// Itinerary Meal Select Column
			function createMealSelectColumn(label, selectedValue, index = 1) {
				const uniqueOptions = [...new Set(availableMeals)].sort();
				const isPlaceholderSelected = !selectedValue || selectedValue === "null";

				return `
					<div class="col-4 d-flex align-items-end meal-plan-select-item">

						<div class="w-100 me-2">
							<label class="form-label fw-normal">${label}:</label>
							<select class="form-select meal-plan-select" name="${label.toLowerCase()}_${index}">
								<option value="" disabled ${isPlaceholderSelected ? "selected" : ""}>Select ${label}</option>
								${uniqueOptions.map(opt => `
									<option value="${opt}" ${String(opt) === String(selectedValue) ? "selected" : ""}>${opt}</option>
								`).join("")}
							</select>
						</div>

						<button type="button" class="btn btn-danger btn-sm mt-4 remove-meal-plan-select ${selectedValue ? "" : "d-none"}">
							<i class="fas fa-trash-alt"></i>
						</button>
					</div>
				`;
			}


			// Itinerary Hotel Column
			function createHotelSelectColumn(selectedValue, index = 1) {
				const uniqueOptions = [...new Set(availableHotels)].sort();
				const isPlaceholderSelected = !selectedValue || selectedValue === "null";

				return `
					<div class="col-4 d-flex align-items-end hotel-select-item">
						<div class="w-100 me-2">
							<label class="form-label fw-normal">Hotel ${index}:</label>
							<select class="form-select hotel-select" name="hotel_${index}">
								<option value="" disabled ${isPlaceholderSelected ? "selected" : ""}>Select Hotel ${index}</option>
								${uniqueOptions.map(opt => `
									<option value="${opt}" ${String(opt) === String(selectedValue) ? "selected" : ""}>${opt}</option>
								`).join("")}
							</select>
						</div>
						<button type="button" class="btn btn-danger btn-sm mt-4 remove-hotel-select ${selectedValue ? "" : "d-none"}">
							<i class="fas fa-trash-alt"></i>
						</button>
					</div>
				`;
			}


			// Itinerary Select Column
			function createItinerarySelectColumn(selectedValue, day, index = 1) {
				const uniqueOptions = [...new Set(availableActivities)].sort();
				const isPlaceholderSelected = !selectedValue || selectedValue === "null";


				return `
					<div class="col-12 mb-2 d-flex align-items-center itinerary-item">
						<select class="form-select itinerary-select me-2 flex-grow-1" data-day="${day}">
							<option value="" disabled ${isPlaceholderSelected ? "selected" : ""}>Select Itinerary Activity ${index}</option>
							${uniqueOptions.map(opt => `
								<option value="${opt}" ${String(opt) === String(selectedValue) ? "selected" : ""}>${opt}</option>
							`).join("")}
						</select>
						<button type="button" class="btn btn-danger btn-sm remove-itinerary ${selectedValue ? "" : "d-none"}">
							<i class="fas fa-trash-alt"></i>
						</button>
					</div>
				`;
			}


			// CARDS RENDERING FUNCTION
			function generateItineraryCards(days) {
				itineraryContainer.innerHTML = "";

				for (let day = 1; day <= days; day++) {
					let dayData = itineraryData.days.find(d => d.day == day) || {};

					let areas = Array.isArray(dayData.areas) ? dayData.areas : [];
					let hotels = Array.isArray(dayData.hotels) ? dayData.hotels : [];
					let meals = Array.isArray(dayData.meals) ? dayData.meals : [];
					let activities = Array.isArray(dayData.activities) ? dayData.activities : [];

					const card = document.createElement("div");
					card.className = "card itinerary-card mb-3";
					card.innerHTML = `
						<div class="card-header bg-primary text-white fw-bold">Day ${day}</div>
						<div class="card-body">
							<div class="container-fluid">

								<!-- Area Section -->
								<div class="row mb-3">
									${[0, 1, 2].map(index => createAreaSelectColumn(areas[index] || "", index + 1)).join("")}
								</div>


								<!-- Meal Plan Section -->
								<div class="row mb-3">
									${day === 1
										? `<div class="col-4"><label class="form-label fw-semibold">Snack:</label><select class="form-select" disabled><option selected>Snack</option></select></div>`
										: ["Breakfast", "Lunch", "Dinner"].map((label, index) => createMealSelectColumn(label, meals[index], index + 1)).join("")
									}
								</div>

								<!-- Hotel Section -->
									<div class="row mb-3">
										<div class="col-md-12">
											<label class="form-label fw-semibold">Hotels:</label>
											<div class="row" id="hotels-day-${day}">
												${(() => {
													const output = [];
													for (let i = 0; i < 2; i++) {
														const value = hotels[i] || "";
														output.push(createHotelSelectColumn(value, i + 1));
													}
													return output.join("");
												})()}
											</div>
										</div>
									</div>



								<!-- Itinerary Section -->
								<div class="row mb-3">
									<div class="col-md-9">
										<label class="form-label fw-semibold">Itinerary:</label>
										<div class="row" id="itinerary-day-${day}">
											${(day === 1 ? [...Array(4)] : [...Array(7)]).map((_, index) =>
										createItinerarySelectColumn(activities[index] || "", day, index + 1)
									).join("")}
										</div>
									</div>
								</div>

							</div>
						</div>
					`;
					itineraryContainer.appendChild(card);
				}
			}




			function updateHotelOptions(selectedCity, hotelSelect) {
				const hotelData = {
					"Seoul": ["Smart Stay Hotel"],
					"Gyeonggi-do": ["Ramada Hotel", "Marina Bay Hotel"],
					"Incheon": ["Air Sky Hotel", "Royal Emporium Hotel"],
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

		let pendingSubmission = false;

		// Trigger modal first when user clicks submit
		document.getElementById("submitEdit").addEventListener("click", function () {
			const currentName = document.getElementById("itineraryName").value.trim();
			document.getElementById("templateName").value = currentName; // Auto-fill modal input
			pendingSubmission = true;

			// Show modal
			const modal = new bootstrap.Modal(document.getElementById('templateNameModal'));
			modal.show();
		});

		// Final submission function when user confirms
		document.getElementById("confirmTemplateName").addEventListener("click", function () {
			if (!pendingSubmission) return;

			const newTemplateName = document.getElementById("templateName").value.trim();
			if (!newTemplateName) {
				alert("Template name cannot be empty.");
				return;
			}

			// Update itineraryName field with user-edited name
			document.getElementById("itineraryName").value = newTemplateName;

			// Regenerate liveItineraryData
			updateLiveItineraryData();

			// Disable the button to prevent double submissions
			submitButton.disabled = true;

			// Hide modal before proceeding
			const modal = bootstrap.Modal.getInstance(document.getElementById('templateNameModal'));
			modal.hide();

			// Proceed to AJAX
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
						submitButton.disabled = false;
					}
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error:", error);
					console.error("Response Text:", xhr.responseText);
					alert("An error occurred while editing the itinerary.");
					submitButton.disabled = false;
				}
			});
		});
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

</body>

</html>