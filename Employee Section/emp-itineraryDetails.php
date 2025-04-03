<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itinerary Details</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-generateItinerary.css?v=<?php echo time(); ?>">


</head>

<body>

    <?php include '../Employee Section/includes/emp-sidebar.php' ?>

    <!-- Main Container -->
    <div class="main-container">
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="back-button-wrapper">
                <div class="back-button-container">
                    <button class="back-button" onclick="window.location.href='../Employee Section/emp-itinerarytable.php';">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
                <div class="title-container">
                    <h5>Itinerary Details</h5>
                </div>
            </div>
        </nav>


        <?php
        if (!isset($_GET['id'])) {
            die("Invalid Itinerary ID");
        }

        $itineraryId = intval($_GET['id']); // Ensure it's an integer

        // Fetch itinerary details
        $sql = "SELECT itineraryName, noOfDays, packageName, periodStart, periodEnd, guideName, countryCode, contactNumber, city1, hotel1, city2, hotel2, city3, hotel3 FROM itineraries
        WHERE itineraryId = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $itineraryId);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$row = $result->fetch_assoc()) {
            die("Itinerary not found");
        }

        $itinerary = [
            'itineraryId' => $itineraryId,
            'itineraryName' => $row['itineraryName'],
            'noOfDays' => $row['noOfDays'],
            'packageName' => $row['packageName'],
            'periodStart' => $row['periodStart'],
            'periodEnd' => $row['periodEnd'],
            'guideName' => $row['guideName'],
            'countryCode' => $row['countryCode'],
            'contactNumber' => $row['contactNumber'],
            'cities' => [
                ['city' => $row['city1'], 'hotel' => $row['hotel1']],
                ['city' => $row['city2'], 'hotel' => $row['hotel2']],
                ['city' => $row['city3'], 'hotel' => $row['hotel3']]
            ],
            'days' => []
        ];

        // Fetch days, areas, hotels, activities, and meal plans
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
                    SELECT dayId, GROUP_CONCAT(DISTINCT areaName ORDER BY itineraryAreaId ASC SEPARATOR ', ') AS areas
                    FROM itineraryareas 
                    GROUP BY dayId
                ) a ON d.dayId = a.dayId
                LEFT JOIN (
                    SELECT dayId, GROUP_CONCAT(DISTINCT hotelName ORDER BY hotelId ASC SEPARATOR ', ') AS hotels
                    FROM itineraryhotels 
                    GROUP BY dayId
                ) h ON d.dayId = h.dayId
                LEFT JOIN (
                    SELECT dayId, GROUP_CONCAT(activityName ORDER BY activityId ASC SEPARATOR ', ') AS activities
                    FROM itineraryactivities 
                    GROUP BY dayId
                ) act ON d.dayId = act.dayId
                LEFT JOIN (
                    SELECT dayId, GROUP_CONCAT(DISTINCT mealPlan ORDER BY mealId ASC SEPARATOR ', ') AS meals
                    FROM itinerarymealplans 
                    GROUP BY dayId
                ) mp ON d.dayId = mp.dayId
                WHERE d.itineraryId = ?
                GROUP BY d.dayId, d.dayNumber
                ORDER BY d.dayNumber ASC;
                ";

        $stmt = $conn->prepare($sqlDays);
        $stmt->bind_param("i", $itineraryId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($day = $result->fetch_assoc()) {
            $areas = $day['areas'] ? explode(',', $day['areas']) : [];
            $hotels = $day['hotels'] ? explode(',', $day['hotels']) : [];
            $meals = $day['meals'] ? explode(',', $day['meals']) : [];

            // Log if arrays are empty
            if (empty($areas)) {
                echo "<script>console.log('No areas found for day " . $day['dayNumber'] . "');</script>";
            }
            if (empty($hotels)) {
                echo "<script>console.log('No hotels found for day " . $day['dayNumber'] . "');</script>";
            }
            if (empty($meals)) {
                echo "<script>console.log('No meals found for day " . $day['dayNumber'] . "');</script>";
            }

            // Store day data in itinerary array
            $itinerary['days'][] = [
                'day' => $day['dayNumber'],
                'areas' => $areas,
                'hotels' => $hotels,
                'activities' => $day['activities'] ? explode(',', $day['activities']) : [],
                'meals' => $meals
            ];
        }

        // Convert array to JSON
        $jsonData = json_encode($itinerary);
        echo "<script>console.log(" . json_encode($itinerary, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) . ");</script>";

        ?>

        <!-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const itineraryId = urlParams.get("id");

            if (itineraryId) {
                fetch(`../Employee Section/functions/emp-fetchItineraryDetails.php?id=${itineraryId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                        } else {
                            document.getElementById("packageSelect").value = data.packageName;
                            document.getElementById("PeriodStartDate").value = data.periodStart;
                            document.getElementById("PeriodEndDate").value = data.periodEnd;
                            document.getElementById("guideName").value = data.guideName;
                            document.getElementById("countryCode").value = data.countryCode;
                            document.getElementById("contactNumber").value = data.contactNumber;
                            document.getElementById("city1").value = data.city1;
                            document.getElementById("hotel1").value = data.hotel1;
                            document.getElementById("city2").value = data.city2;
                            document.getElementById("hotel2").value = data.hotel2;
                            document.getElementById("city3").value = data.city3;
                            document.getElementById("hotel3").value = data.hotel3;
                            document.getElementById("select-days").value = data.noOfDays;
                        }
                    })
                    .catch(error => console.error("Error fetching itinerary:", error));
            }
        });


        </script> -->


        <div class="main-content">
            <!-- HTML Form -->
            <input type="hidden" id="itineraryId" value="<?= htmlspecialchars($itineraryId); ?>" readonly>

            <div class="form-container">
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
                                    <input type="text" class="form-control" id="itineraryName" name="itineraryName" value="<?= $itinerary['itineraryName']; ?>" required>
                                </div>
                            </div>


                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">Package
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                                        <option selected><?= $itinerary['packageName']; ?></option>
                                        <?php
                                        // Execute the SQL query
                                        $sql1 = "SELECT packageName FROM package ORDER BY packageId ASC";
                                        $res1 = $conn->query($sql1);

                                        // Check if there are results
                                        if ($res1->num_rows > 0) {
                                            // Loop through the results and generate option
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

                                <div class="datepicker-wrapper">
                                    <div class="form-group">
                                        <div class="date-range-inputs-wrapper">
                                            <div class="input-with-icon">
                                                <input type="text" class="datepicker" id="PeriodStartDate" placeholder="Start" value="<?= $itinerary['periodStart']; ?>" readonly>
                                                <i class="fas fa-calendar-alt calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dash Separator -->
                                    <div class="dash-separator">-></div>

                                    <div class="form-group">
                                        <div class="date-range-inputs-wrapper">
                                            <div class="input-with-icon">
                                                <input type="text" class="datepicker" id="PeriodEndDate" placeholder="End" value="<?= $itinerary['periodEnd']; ?>" readonly>
                                                <i class="fas fa-calendar-alt calendar-icon"></i>
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
                                    <select class="form-select" id="guideName" name="flightDate" required>
                                        <option selected><?= $itinerary['guideName']; ?></option>
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
                                    <select class="form-select" id="countryCode" style="width: 80px;">
                                        <option selected><?= $itinerary['countryCode']; ?></option>
                                        <option value="+82">+82</option>
                                    </select>
                                    <input type="text" class="form-control ms-2" id="contactNumber" name="contactNumber" value="<?= $itinerary['contactNumber']; ?>" required>
                                </div>

                            </div>

                        </div>

                        <div class="column-header mb-2">
                            <label for="">Tour Area, Hotels
                                <span class="text-danger"> *</span>
                            </label>
                        </div>

                        <!-- Tour Areas, Hotels -->
                        <?php
                        $cities = ["Seoul", "Busan", "Jeonju", "Jeju"];
                        $hotels = [
                            "Seoul" => ["Lotte Hotel Seoul", "Signiel Seoul", "The Shilla Seoul", "Grand Hyatt Seoul", "InterContinental Seoul COEX"],
                            "Busan" => ["Park Hyatt Busan", "Paradise Hotel Busan"],
                            "Jeonju" => ["Lahan Hotel Jeonju"],
                            "Jeju" => ["Maison Glad Jeju", "Ramada Plaza Jeju"]
                        ];

                        for ($i = 0; $i < 3; $i++) {
                            $cityKey = "city" . ($i + 1);
                            $hotelKey = "hotel" . ($i + 1);
                            $selectedCity = $itinerary['cities'][$i]['city'] ?? "";
                            $selectedHotel = $itinerary['cities'][$i]['hotel'] ?? "";
                        ?>
                            <div class="row">
                                <div class="columns col-md-8">
                                    <div class="cityhotel-wrapper">
                                        <div class="cityhotel-item">
                                            <div class="form-group d-flex flex-row align-items-center">
                                                <select class="form-select city-select" id="<?= $cityKey ?>" name="city(<?= $i + 1 ?>)" data-index="<?= $i ?>" required>
                                                    <?php foreach ($cities as $city) : ?>
                                                        <option value="<?= $city ?>" <?= ($city === $selectedCity) ? 'selected' : '' ?>><?= $city ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="dash-separator">-></div>

                                        <div class="cityhotel-item">
                                            <div class="form-group d-flex flex-row align-items-center">
                                                <select class="form-select hotel-select" id="<?= $hotelKey ?>" name="hotel(<?= $i + 1 ?>)" required>
                                                    <option disabled <?= empty($selectedHotel) ? 'selected' : '' ?>>Select Hotel</option>
                                                    <?php if (!empty($selectedCity) && isset($hotels[$selectedCity])) : ?>
                                                        <?php foreach ($hotels[$selectedCity] as $hotel) : ?>
                                                            <option value="<?= $hotel ?>" <?= ($hotel === $selectedHotel) ? 'selected' : '' ?>><?= $hotel ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    let selectedCity<?= $i ?> = document.getElementById("<?= $cityKey ?>").value;
                                    // console.log("Selected City <?= $i + 1 ?>:", selectedCity<?= $i ?>);
                                });
                            </script>

                        <?php } ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="fw-bold">No. of Days</h5>
                    </div>

                    <div class="card-body">

                        <!-- Package Row -->
                        <div class="row">
                            <div class="columns col-md-3">
                                <div class="form-group days-select-wrapper">
                                    <label for="flightDate">No. of days<span class="text-danger"> *</span></label>
                                    <select class="form-select" id="select-days" name="numberOfDays" required>
                                        <option value="<?= $noOfDays; ?>" selected>Day <?= $noOfDays; ?></option> <!-- Keeps preselected value -->
                                    </select>

                                    <small class="form-text text-muted">Changing this will clear all your data on the fields.</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="itinerary-container" id="itinerary-container">

                </div>
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
        $(document).ready(function() {
            // Apply datepicker for PeriodStartDate
            $("#PeriodStartDate").datepicker({
                dateFormat: "yy-mm-dd",
                showAnim: "fadeIn",
                changeMonth: true,
                changeYear: true,
                yearRange: "1900:2100",
                onSelect: function(dateText) {
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
                onSelect: function(dateText) {
                    console.log("PeriodEndDate Selected: " + dateText);
                }
            });
        });
    </script>

    <!-- First Card Script -->
    <!-- <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Cities and Hotels Data
            const cities = ["Seoul", "Busan", "Jeonju", "Jeju"];
            const hotels = {
                "Seoul": ["Lotte Hotel Seoul", "Signiel Seoul", "The Shilla Seoul", "Grand Hyatt Seoul", "InterContinental Seoul COEX"],
                "Busan": ["Park Hyatt Busan", "Paradise Hotel Busan"],
                "Jeonju": ["Lahan Hotel Jeonju"],
                "Jeju": ["Maison Glad Jeju", "Ramada Plaza Jeju"]
            };

            // Populate existing city dropdowns
            document.querySelectorAll(".city-select").forEach((select) => {
                const storedValue = select.getAttribute("data-selected"); // Get selected value from PHP
                populateDropdown(select, cities, "Select City", storedValue);
                select.addEventListener("change", () => updateHotelDropdown(select));
            });

            // Load stored hotel selections from localStorage
            loadStoredHotels();

            // Function to populate dropdowns (City or Hotel)
            function populateDropdown(select, optionList, placeholderText, selectedValue = "") {
                if (!select) return;

                select.innerHTML = `<option disabled>${placeholderText}</option>`;
                optionList.forEach(optionValue => {
                    const option = document.createElement("option");
                    option.value = optionValue;
                    option.textContent = optionValue;
                    if (optionValue === selectedValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            }

            // Function to update hotel dropdown based on city selection
            function updateHotelDropdown(citySelect) {
                const row = citySelect.closest(".row"); // Get parent row
                const hotelSelect = row.querySelector(".hotel-select"); // Find corresponding hotel select

                if (!hotelSelect) return;

                const selectedCity = citySelect.value;
                hotelSelect.innerHTML = `<option selected disabled>Select Hotel</option>`; // Reset hotels

                if (hotels[selectedCity]) {
                    populateDropdown(hotelSelect, hotels[selectedCity], "Select Hotel");
                }

                console.log(`City Selected: ${selectedCity}`);
            }

            // Function to load stored hotels from localStorage
            function loadStoredHotels() {
                const storedHotels = JSON.parse(localStorage.getItem("selectedHotels")) || {};
                document.querySelectorAll(".row").forEach((row) => {
                    const citySelect = row.querySelector(".city-select");
                    const hotelSelect = row.querySelector(".hotel-select");

                    if (citySelect && hotelSelect) {
                        const selectedCity = citySelect.value;
                        if (selectedCity && hotels[selectedCity]) {
                            populateDropdown(hotelSelect, hotels[selectedCity], "Select Hotel");
                            if (storedHotels[selectedCity]) {
                                hotelSelect.value = storedHotels[selectedCity];
                            }
                        }
                    }
                });
            }

            // Save selected hotels to localStorage
            document.body.addEventListener("change", (event) => {
                if (event.target.classList.contains("hotel-select")) {
                    const row = event.target.closest(".row");
                    const citySelect = row.querySelector(".city-select");
                    if (!citySelect) return;

                    const selectedCity = citySelect.value;
                    const selectedHotel = event.target.value;

                    let storedHotels = JSON.parse(localStorage.getItem("selectedHotels")) || {};
                    storedHotels[selectedCity] = selectedHotel;
                    localStorage.setItem("selectedHotels", JSON.stringify(storedHotels));

                    console.log(`Saved: City - ${selectedCity}, Hotel - ${selectedHotel}`);
                }
            });

            // MutationObserver for dynamically added elements
            const observer = new MutationObserver(() => {
                document.querySelectorAll(".city-select").forEach((select) => {
                    if (!select.hasAttribute("data-initialized")) {
                        select.setAttribute("data-initialized", "true");
                        select.addEventListener("change", () => updateHotelDropdown(select));
                    }
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });

    </script> -->

    <!-- For Itinerary Card -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const itineraryContainer = document.getElementById("itinerary-container");
            const selectDays = document.getElementById("select-days");
            const formFooter = document.querySelector(".form-footer");

            let itineraryData = <?= json_encode($itinerary); ?>;
            // console.log("Loaded itineraryData:", itineraryData);

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
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Hotels:</label>
                                    <div class="row">
                                        ${createMultipleSelectColumns(["Hotel", "Hotel", "Hotel"], "hotel-select", availableHotels, hotels)}
                                    </div>
                                </div>
                            </div>

                            <!-- Itinerary Section (Dynamic) -->
                            <div class="row mb-3">
                                <div class="col-12">
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

            // Event listener for days selection change
            selectDays.addEventListener("change", function() {
                const selectedDays = parseInt(selectDays.value);
                generateItineraryCards(selectedDays);
            });

            // Initialize itinerary on page load if selectedValue is greater than 0
            if (selectedValue > 0) {
                generateItineraryCards(selectedValue);
            }
        });
    </script>

    <!-- JS Script for JSON (Array) console.log -->
    <script>
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
    </script>



    <!--- Generate Itinerary PDF -->
    <script>
        $('#submitTour').click(function() {
        const itineraryId = $('#itineraryId').val();
        const itineraryName = $('#itineraryName').val();

    if (!itineraryId) {
        alert('Please enter a valid Itinerary ID.');
        return;
    }

    // Step 1: Open Itinerary Template (Executes SQL Query)
    let templateWindow = window.open(`../Employee Section/functions/Itinerary-template.php?itineraryId=${encodeURIComponent(itineraryId)}`, '_blank');

    if (!templateWindow) {
        alert('Popup blocked! Please allow popups for this site.');
        return;
    }

    // Step 2: Wait for the Template to Load Before Proceeding
    let checkTemplateLoaded = setInterval(function() {
        if (templateWindow.closed) {
            clearInterval(checkTemplateLoaded);

            // Step 3: Generate PDF Only If Template Loaded Successfully
            let xhrPdf = new XMLHttpRequest();
            xhrPdf.open('GET', `../Employee Section/functions/generateItineraryPDF.php?itineraryId=${encodeURIComponent(itineraryId)}`, true);
            xhrPdf.responseType = 'blob';

            xhrPdf.onload = function() {
                if (xhrPdf.status === 200) {
                    const blob = new Blob([xhrPdf.response], { type: 'application/pdf' });
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = `Itinerary_${itineraryName}.pdf`;
                    link.click();
                } else {
                    alert('Failed to generate the itinerary PDF. Please try again.');
                }
            };

            xhrPdf.onerror = function() {
                alert('An error occurred while generating the itinerary PDF.');
            };

            xhrPdf.send();
        }
    }, 1000); // Check every second if template is closed
});

    </script>



    <!-- <script>
        document.getElementById("submitTour").addEventListener("click", function() {
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
                success: function(response) {
                    submitButton.disabled = false;
                    if (response.status === "success") {
                        alert("Itinerary successfully created!");
                        location.reload();
                    } else {
                        console.error("❌ Server Error:", response.message);
                        alert("❌ Error saving itinerary: " + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    submitButton.disabled = false;
                    console.error("⚠️ AJAX Error:", error);
                    console.error("⚠️ Response Text:", xhr.responseText);
                    alert("An error occurred while saving the itinerary.");
                }
            });

            $("#templateNameModal").modal("hide");
        }
    </script> -->

</body>

</html>