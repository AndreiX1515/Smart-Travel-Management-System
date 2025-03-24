<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Itinerary</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-generateItinerary.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
</head>

<body>

    <?php include '../Employee Section/includes/emp-sidebar.php' ?>

    <!-- Main Container -->
    <div class="main-container">
        <?php include '../Employee Section/includes/emp-navbar.php' ?>

        <div class="main-content">
            <div class="form-container">

                <div class="card">
                    <div class="card-header">
                        <h5>Itinerary Details</h5>
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
                                    <select class="form-select" id="flightDate" name="flightDate" required>
                                        <option selected disabled>Select Package Type</option>
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
                                                <input type="text" class="datepicker" id="PeriodStartDate" placeholder="Start" readonly>
                                                <i class="fas fa-calendar-alt calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dash Separator -->
                                    <div class="dash-separator">-></div>

                                    <div class="form-group">
                                        <div class="date-range-inputs-wrapper">
                                            <div class="input-with-icon">
                                                <input type="text" class="datepicker" id="PeriodEndDate" placeholder="End" readonly>
                                                <i class="fas fa-calendar-alt calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- Total Pax Input -->
                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">Guide
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="flightDate" name="flightDate" required>
                                        <option selected disabled>Select Package Type</option>
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
                                    <select class="form-select" style="width: 80px;">
                                        <option selected>+63</option>
                                    </select>
                                    <input type="text" class="form-control ms-2" id="totalPax" name="totalPax" min="1" placeholder="9***********" required>
                                </div>

                            </div>

                        </div>

                        <!-- Tour Areas, Hotels -->
                        <div class="row">
                            <div class="columns col-md-8">
                                <div class="column-header">
                                    <label for="flightDate">Tour Areas, Hotels <span class="text-danger"> *</span></label>
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

                        <!-- Hotel -->
                        <!-- <div class="row">
                            <div class="column-header mb-2">
                                <label for="flightDate">Hotel
                                    <span class="text-danger"> *</span>
                                </label>
                            </div>

                            
                            <div class="columns col-md-4">
                                <div class="form-group">
                                    <select class="form-select hotel-select" id="hotel1" name="hotel1" required>
                                        <option selected disabled>Select Hotel (1)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="columns col-md-4">
                                <div class="form-group">
                                    <select class="form-select hotel-select" id="hotel2" name="hotel2" required>
                                        <option selected disabled>Select Hotel (2)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="columns col-md-4">
                                <div class="form-group">
                                    <select class="form-select hotel-select" id="hotel3" name="hotel3" required>
                                        <option selected disabled>Select Hotel (3)</option>
                                    </select>
                                </div>
                            </div>

                        </div> -->

                    </div>
                </div>

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
                                    <select class="form-select" id="select-days" name="numberOfDays" required>
                                        <option selected disabled>Select Number of Days</option>
                                    </select>
                                    <small class="form-text text-muted">Changing this will clear all your data on the fields.</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="itinerary-container" id="itinerary-container">
                    <div id="hotel-container"></div>
                </div>
            </div>

            <div class="form-footer">
                <button type="button" class="btn btn-primary" id="submitTour">Submit</button>
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
    <script>
       document.addEventListener("DOMContentLoaded", () => {
            // Cities and Hotels Data
            const cities = ["Seoul", "Busan", "Jeonju", "Jeju"];
            const hotels = {
                "Seoul": ["Lotte Hotel Seoul", "Signiel Seoul", "The Shilla Seoul", "Grand Hyatt Seoul", "InterContinental Seoul COEX"],
                "Busan": ["Park Hyatt Busan", "Paradise Hotel Busan"],
                "Jeonju": ["Lahan Hotel Jeonju"],
                "Jeju": ["Maison Glad Jeju", "Ramada Plaza Jeju"]
            };

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
                    populateDropdown(hotelSelect, hotels[selectedCity], "Select Hotel");
                }

                console.log(`Row ${rowIndex}: City Selected - ${selectedCity}`);
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
    document.addEventListener("DOMContentLoaded", function() {
        const selectDays = document.getElementById("select-days");
        const itineraryContainer = document.getElementById("itinerary-container");
        const submitButton = document.getElementById("submitTour");

        // Korean Tour Data
        const koreanTourAreas = ["Seoul", "Busan", "Jeju", "Incheon", "Gyeongju"];
        const koreanMealPlans = ["Traditional Korean Cuisine", "Street Food Tour", "Seafood Specialty", "Vegetarian Option", "Luxury Fine Dining"];
        const hotels = [
            "Lotte Hotel Seoul", "Signiel Seoul", "The Shilla Seoul", "Grand Hyatt Seoul", "InterContinental Seoul COEX",
            "Park Hyatt Busan", "Paradise Hotel Busan", "Lahan Hotel Jeonju", "Maison Glad Jeju", "Ramada Plaza Jeju"
        ];
        const itineraries = [
            "City Tour", "Cultural Experience", "Adventure Activities", "Shopping and Leisure", "Historical Landmarks", "Scenic Views", "Nightlife Exploration"
        ];

        // Generate dropdown options for selecting number of days (Max 5)
        for (let num = 1; num <= 5; num++) {
            let option = document.createElement("option");
            option.value = num;
            option.textContent = `Day ${num}`;
            selectDays.appendChild(option);
        }

        // Generate itinerary cards based on selected days
        selectDays.addEventListener("change", function() {
            const selectedDays = parseInt(selectDays.value);
            itineraryContainer.innerHTML = ""; // Clear previous selections

            for (let day = 1; day <= selectedDays; day++) {
                const card = document.createElement("div");
                card.className = "card itinerary-card mb-3";

                card.innerHTML = `
                    <div class="card-header bg-primary text-white fw-bold">Day ${day}</div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label class="form-label fw-semibold">Area:</label>
                                    <select class="form-select area-select" data-day="${day}">
                                        <option selected disabled>Select Area</option>
                                        ${koreanTourAreas.map(area => `<option value="${area}">${area}</option>`).join("")}
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label class="form-label fw-semibold">Meal Plan:</label>
                                    <select class="form-select meal-plan-select" data-day="${day}">
                                        <option selected disabled>Select Meal Plan</option>
                                        ${koreanMealPlans.map(meal => `<option value="${meal}">${meal}</option>`).join("")}
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Hotels:</label>
                                    <div class="row">
                                        ${hotels.map(hotel => `
                                            <div class="col-md-4 col-sm-12 mb-2">
                                                <select class="form-select hotel-select" data-day="${day}">
                                                    <option selected disabled>Select Hotel</option>
                                                    <option value="${hotel}">${hotel}</option>
                                                </select>
                                            </div>`).join("")}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Itinerary:</label>
                                </div>
                                ${itineraries.map(itinerary => `
                                    <div class="col-12 mb-2">
                                        <select class="form-select itinerary-select" data-day="${day}">
                                            <option selected disabled>Select Itinerary</option>
                                            <option value="${itinerary}">${itinerary}</option>
                                        </select>
                                    </div>`).join("")}
                            </div>
                        </div>
                    </div>
                `;
                itineraryContainer.appendChild(card);
            }
        });

        // Collect form data and log to console (Before Submission)
        submitButton.addEventListener("click", function() {
            let tourData = [];

            document.querySelectorAll(".itinerary-card").forEach(card => {
                let day = card.querySelector(".card-header").textContent.replace("Day ", "");
                let selectedArea = card.querySelector(".area-select")?.value || "None";
                let selectedMealPlan = card.querySelector(".meal-plan-select")?.value || "None";
                let selectedHotels = [...card.querySelectorAll(".hotel-select")].map(h => h.value || "None");
                let selectedItineraries = [...card.querySelectorAll(".itinerary-select")].map(i => i.value || "None");

                tourData.push({
                    Day: day,
                    Area: selectedArea,
                    MealPlan: selectedMealPlan,
                    Hotels: selectedHotels,
                    Itineraries: selectedItineraries
                });
            });

            console.log("Form Data:", JSON.stringify(tourData, null, 2));

            // TODO: Send tourData to backend via AJAX
        });
    });



    </script>

</body>

</html>