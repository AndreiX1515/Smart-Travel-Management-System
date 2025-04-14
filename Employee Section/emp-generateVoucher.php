<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Voucher</title>
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
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="navbar-wrapper">
                <a class="navbar-brand" id="page-title" href="#">Create Voucher</a>
            </div>
        </nav>

        <div class="main-content">
            <div class="form-container">

                <!-- Tour Condition Title Card -->
                <div class="card">
                    <div class="card-header bg-primary card-title">
                        <h5>Header</h5>
                    </div>
                </div>

                <!-- Itinerary Details Card -->
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h5>Voucher Details</h5>
                    </div>

                    <div class="card-body">
                        <!-- Package Row -->
                        <div class="row">
                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">To
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                                        <option selected disabled>Select Package Type</option>
                                    </select>
                                </div>
                            </div>

                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">From
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                                        <option selected disabled>Select Package Type</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Flight Date Dropdown -->
                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">Tour
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                                        <option selected disabled>Select Package Type</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <!-- Periods, Guide Row -->
                        <div class="row">
                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">Attachment
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                                        <option selected disabled>Select Package Type</option>
                                    </select>
                                </div>
                            </div>

                            <div class="columns col-md-4">

                                <div class="column-header">
                                    <label for="flightDate">Tour Periods:
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

                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">No. of Pax
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" id="noOfPax" required>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- Tour Condition Title Card -->
                <div class="card">
                    <div class="card-header bg-primary card-title">
                        <h5>Tour Condition</h5>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-secondary card-title first-wrapper d-flex justify-content-between align-items-center text-white">
                        <h5 class="mb-0">Date & Hotels</h5>
                        <button type="button" class="btn btn-success fw-bold" onclick="addCard()">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Container for Date & Hotel Cards -->
                <div id="cardsContainer"></div>

                <!-- Script for Add Container for Date & Hotels Section -->
                <script>
                    let cardCount = 0;

                    function addCard() {
                        cardCount++;
                        const container = document.getElementById('cardsContainer');
                        const card = document.createElement('div');

                        card.className = 'card mt-1';
                        card.innerHTML = `
                            <div class="card-header bg-secondary card-title d-flex justify-content-between align-items-center text-white">
                                <h5 class="mb-0">Date & Hotel #${cardCount}</h5>
                                <button type="button" class="btn btn-sm btn-danger remove-card-btn">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            <div class="card-body tour-content">
                                <div class="row">
                                    <div class="columns col-md-4">
                                        <div class="column-header">
                                            <label>Date Range: <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-with-icon">
                                                <input type="text" class="form-control datepicker" id="PeriodStartDate${cardCount}" name="PeriodStartDate${cardCount}" placeholder="Start" readonly>
                                                <i class="fas fa-calendar-alt calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns col-md-4">
                                        <div class="column-header"><br></div>
                                        <div class="form-group">
                                            <div class="input-with-icon">
                                                <input type="text" class="form-control datepicker" id="PeriodEndDate${cardCount}" name="PeriodEndDate${cardCount}" placeholder="End" readonly>
                                                <i class="fas fa-calendar-alt calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns col-md-4">
                                        <div class="column-header">
                                            <label>Hotel: <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-select" id="hotelSelect${cardCount}" name="hotelSelect${cardCount}" required>
                                                <option value="" disabled selected>Select Hotel</option>
                                                <option value="Shilla Stay">Shilla Stay</option>
                                                <option value="Hotel PJ Myeongdong">Hotel PJ Myeongdong</option>
                                                <option value="ENA Suite Hotel Namdaemun">ENA Suite Hotel Namdaemun</option>
                                                <option value="Stanford Hotel Myeongdong">Stanford Hotel Myeongdong</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        container.appendChild(card);

                        const removeButton = card.querySelector('.remove-card-btn');
                        removeButton.addEventListener('click', () => {
                            card.remove();
                            cardCount--;
                            updateCardHeaders();
                        });

                        // Initialize flatpickr for this card
                        flatpickr(`#PeriodStartDate${cardCount}`, {
                            dateFormat: "Y-m-d"
                        });
                        flatpickr(`#PeriodEndDate${cardCount}`, {
                            dateFormat: "Y-m-d"
                        });
                    }

                    function updateCardHeaders() {
                        const cards = document.querySelectorAll('#cardsContainer .card');
                        cards.forEach((card, index) => {
                            const header = card.querySelector('h5');
                            if (header) {
                                header.textContent = `Date & Hotel #${index + 1}`;
                            }
                        });
                    }

                    // ✅ Show the first card by default
                    addCard();
                </script>



                <!-- Other Information Title Card -->
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h5>Other Informations</h5>
                    </div>
                </div>

                <!-- Select Guide -->
                <div class="card">
                    <div class="card-body">
                        <!-- Guides Row -->
                        <div class="row">
                            <div class="columns col-md-12">
                                <div class="column-header">
                                    <label for="flightDate">Guide
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                                        <option selected disabled>Select Guide</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Air Schedule -->
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h5>Air Schedule</h5>
                    </div>

                    <div class="card-body">
                        <!-- Air Schedule -->
                        <div class="row">
                            <div class="main-header">
                                <div class="header-container">
                                    <h6>Arrival</h6>
                                </div>
                            </div>

                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">Date
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="datepicker-wrapper">
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

                            <div class="columns col-md-2">
                                <div class="column-header">
                                    <label for="flightDate">Flight
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                                        <option selected disabled>Select Flight</option>
                                    </select>
                                </div>
                            </div>

                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">Origin - Destination
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="datepicker-wrapper">
                                    <div class="form-group">
                                        <select class="form-select" id="packageSelect" name="packageSelect" required>
                                            <option selected disabled>Origin</option>
                                        </select>
                                    </div>

                                    <!-- Dash Separator -->
                                    <div class="dash-separator">-></div>

                                    <div class="form-group">
                                        <select class="form-select" id="packageSelect" name="packageSelect" required>
                                            <option selected disabled>Destination</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="columns col-md-2">
                                <div class="column-header">
                                    <label for="flightTime">Time <span class="text-danger"> *</span></label>
                                </div>

                                <div class="form-group">
                                    <div class="date-range-inputs-wrapper">
                                        <div class="input-with-icon timepicker">
                                            <input type="text" class="timepicker" placeholder="Pick a time" readonly>
                                            <i class="fas fa-clock calendar-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="main-header">
                                <div class="header-container">
                                    <h6>Destination</h6>
                                </div>
                            </div>

                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">Date
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="datepicker-wrapper">
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

                            <div class="columns col-md-2">
                                <div class="column-header">
                                    <label for="flightDate">Flight
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                                        <option selected disabled>Select Flight</option>
                                    </select>
                                </div>
                            </div>

                            <div class="columns col-md-4">
                                <div class="column-header">
                                    <label for="flightDate">Origin - Destination
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="datepicker-wrapper">
                                    <div class="form-group">
                                        <select class="form-select" id="packageSelect" name="packageSelect" required>
                                            <option selected disabled>Origin</option>
                                        </select>
                                    </div>

                                    <!-- Dash Separator -->
                                    <div class="dash-separator">-></div>

                                    <div class="form-group">
                                        <select class="form-select" id="packageSelect" name=packageSelect" required>
                                            <option selected disabled>Destination</option>"
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="columns col-md-2">
                                <div class="column-header">
                                    <label for="flightDate">Time
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                                        <option selected disabled>Select Flight</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- Guide Meeting -->
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h5>Guide Meeting</h5>
                    </div>

                    <div class="card-body">
                        <!-- Guides Row -->
                        <div class="row">

                            <!-- Date Picker -->
                            <div class="columns col-md-2">
                                <div class="column-header">
                                    <label for="PeriodStartDate">Date <span class="text-danger"> *</span></label>
                                </div>
                                <div class="datepicker-wrapper">
                                    <div class="form-group">
                                        <div class="date-range-inputs-wrapper">
                                            <div class="input-with-icon datepicker">
                                                <input type="text" class="datepicker" placeholder="Pick a date" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="columns col-md-2">
                                <div class="column-header">
                                    <label for="flightTime">
                                        Time <small class="time-format">(24H Format)</small>
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>



                                <div class="form-group">
                                    <div class="date-range-inputs-wrapper">
                                        <div class="input-with-icon timepicker">
                                            <input type="text" class="timepicker" placeholder="Pick a time" readonly>
                                            <i class="fas fa-clock calendar-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Place Selector -->
                            <div class="columns col-md-8">
                                <div class="column-header">
                                    <label for="packageSelect">Place
                                        <span class="text-danger"> *</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <select class="form-select" id="packageSelect" name="packageSelect" required>
                                        <option selected disabled>Select Flight</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Includes Header -->
                <div class="card includes-header-card">
                    <div class="card-header bg-secondary card-title includes-wrapper">
                        <h5>Includes</h5>
                        <button id="addIncludeBtn" class="btn btn-light btn-sm">+</button> <!-- Unique ID -->
                    </div>
                </div>

                <!-- Include Cards Container -->
                <div class="card include-cards">
                    <div class="card-body" id="includesContainer">
                        <!-- JS will generate .row elements here directly -->
                    </div>
                </div>

                <!-- Includes Section Functions Script -->
                <script>
                    let includeCount = 0;
                    const maxIncludes = 4;

                    // Function to get all selected include values
                    function getSelectedIncludes() {
                        const selectedValues = [];
                        const rows = document.querySelectorAll('.include-row');

                        rows.forEach(row => {
                            const select = row.querySelector('select');
                            const customInput = row.querySelector('.custom-include-input');

                            // Check if "others" is selected, and if it is, push the input value to the array
                            if (select.value === "others") {
                                selectedValues.push(customInput.value.trim());
                            } else {
                                selectedValues.push(select.value); // Otherwise, push the selected value
                            }
                        });

                        return selectedValues;
                    }

                    // Function to update disabled options based on already selected values
                    function updateDisabledOptions() {
                        const selectedValues = getSelectedIncludes();
                        const selects = document.querySelectorAll('.include-row select');

                        selects.forEach(select => {
                            const options = select.querySelectorAll('option');
                            options.forEach(option => {
                                if (
                                    option.value !== select.value &&
                                    selectedValues.includes(option.value) &&
                                    option.value !== "" &&
                                    option.value !== "others"
                                ) {
                                    option.disabled = true;
                                } else {
                                    option.disabled = false;
                                }
                            });
                        });
                    }

                    // Function to add a new include row dynamically
                    function addInclude() {
                        if (includeCount >= maxIncludes) return;

                        includeCount++;
                        const includesContainer = document.getElementById('includesContainer');

                        const newRow = document.createElement('div');
                        newRow.className = 'row include-row align-items-start mb-1';
                        newRow.setAttribute('data-index', includeCount);

                        newRow.innerHTML = `
                        <div class="col-md-12">
                                <div class="label-container">
                                    <label for="packageSelect${includeCount}" class="form-label">Includes ${includeCount}:</label>

                                    <!-- Remove Button placed at the right end inside label-container -->
                                    <button type="button" class="btn btn-sm btn-danger remove-include" title="Remove">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                <div class="content-container">
                                    <select class="form-select include-select" id="packageSelect${includeCount}" name="packageSelect${includeCount}" required>
                                        <option value="" selected disabled>Select Guide</option>
                                        <option value="1">Hotel (4 nights with twin or triple sharing)</option>
                                        <option value="2">Meals (4 times Lunch, 4 times Dinner)</option>
                                        <option value="3">(Coach, Van), Admission as the itinerary, ENGLISH guide, etc.</option>
                                        <option value="4">Airport Pick-up and Drop-off</option>
                                        <option value="5">Souvenir Pack</option>
                                        <option value="6">Travel Insurance</option>
                                        <option value="others">Others</option>
                                        <option value="0"> — No Additional Includes — </option>
                                    </select>

                                    <input type="text" class="form-control custom-include-input d-none mt-2" placeholder="Please specify..." />
                                </div>
                            </div>


                        `;

                        includesContainer.appendChild(newRow);

                        const selectEl = newRow.querySelector('select');
                        const customInput = newRow.querySelector('.custom-include-input');
                        const removeBtn = newRow.querySelector('.remove-include');

                        // Event listener for changes on select field
                        selectEl.addEventListener('change', () => {
                            if (selectEl.value === "others") {
                                customInput.classList.remove("d-none");
                                customInput.focus();
                            } else {
                                customInput.classList.add("d-none");
                            }

                            updateDisabledOptions();
                            console.log('Selected Includes:', getSelectedIncludes());
                        });

                        // Event listener for custom input value to fill into the array
                        customInput.addEventListener('input', () => {
                            const inputVal = customInput.value.trim();
                            let existingOtherOption = selectEl.querySelector('option[value="others"]');

                            if (inputVal !== "") {
                                // Keep the select value unchanged, the value in the input will be used instead
                                updateDisabledOptions();
                                console.log('Updated Custom Include:', inputVal);
                            }
                        });

                        // Event listener to remove a row
                        removeBtn.addEventListener('click', () => {
                            newRow.remove();
                            includeCount--;
                            updateIncludeLabels();
                            updateDisabledOptions();
                            console.log('Selected Includes:', getSelectedIncludes());
                        });

                        updateDisabledOptions();
                    }

                    // Function to update the labels and attributes when rows are added or removed
                    function updateIncludeLabels() {
                        const rows = document.querySelectorAll('.include-row');
                        rows.forEach((row, index) => {
                            const label = row.querySelector('label');
                            const select = row.querySelector('select');
                            const number = index + 1;
                            row.setAttribute('data-index', number);
                            label.setAttribute('for', `packageSelect${number}`);
                            label.textContent = `Includes ${number}:`;
                            select.setAttribute('id', `packageSelect${number}`);
                            select.setAttribute('name', `packageSelect${number}`);
                        });
                    }

                    // Initialize the first row when the page is loaded
                    function initIncludesSection() {
                        addInclude();
                    }

                    // Add event listener for the add include button
                    document.getElementById('addIncludeBtn').addEventListener('click', addInclude);

                    // Initialize on page load
                    window.addEventListener('DOMContentLoaded', initIncludesSection);
                </script>









                <!-- Excludes -->
                <div class="card excludes-header-card">
                    <div class="card-header bg-secondary card-title excludes-wrapper">
                        <h5>Excludes</h5>
                        <button type="button" class="add-button btn btn-primary add-exclude-button">+</button> <!-- Add Exclude Button -->
                    </div>
                </div>

                <div class="card excludes-cards">
                    <div class="card-body">
                        <!-- Excludes Rows (Dynamically added) -->
                        <div id="excludesContainer"></div>
                    </div>
                </div>

                <!-- Excludes Section Functions Script -->
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        // Configuration for Excludes Section
                        const excludeConfig = {
                            count: 0, // Initialize the count of Excludes
                            max: 4, // Maximum number of Exclude rows
                            container: document.getElementById('excludesContainer'), // Container for Excludes rows
                            addButton: document.querySelector('.add-exclude-button') // Add button for Excludes
                        };

                        // Initialize an array to store selected exclude values
                        let selectedExcludes = [];

                        // Get selected excludes
                        function getSelectedExcludes() {
                            selectedExcludes = [];
                            document.querySelectorAll('.exclude-row').forEach(row => {
                                const select = row.querySelector('select');
                                const input = row.querySelector('.custom-exclude-input');

                                if (select.value === 'others') {
                                    selectedExcludes.push(input.value.trim());
                                } else {
                                    selectedExcludes.push(select.value);
                                }
                            });
                            console.log('Selected Excludes:', selectedExcludes); // Log the array of selected excludes
                        }

                        // Update the disabled options in selects based on selected excludes
                        function updateDisabledExcludes() {
                            getSelectedExcludes();
                            document.querySelectorAll('.exclude-row select').forEach(select => {
                                select.querySelectorAll('option').forEach(option => {
                                    if (
                                        option.value !== select.value &&
                                        selectedExcludes.includes(option.value) &&
                                        option.value !== "" &&
                                        option.value !== "others"
                                    ) {
                                        option.disabled = true;
                                    } else {
                                        option.disabled = false;
                                    }
                                });
                            });
                        }

                        // Update exclude labels based on the row index
                        function updateExcludeLabels() {
                            document.querySelectorAll('.exclude-row').forEach((row, i) => {
                                const label = row.querySelector('label');
                                const select = row.querySelector('select');
                                const index = i + 1;
                                row.setAttribute('data-index', index);
                                label.setAttribute('for', `excludeSelect${index}`);
                                label.textContent = `Excludes ${index}:`;
                                select.setAttribute('id', `excludeSelect${index}`);
                                select.setAttribute('name', `excludeSelect${index}`);
                            });
                        }

                        // Create a new Exclude row
                        function createExcludeRow() {
                            excludeConfig.count++;
                            const index = excludeConfig.count;

                            const row = document.createElement('div');
                            row.className = 'row exclude-row align-items-start mb-3';
                            row.setAttribute('data-index', index);

                            row.innerHTML = `
                                <div class="col-md-12">
                                    <div class="label-container">
                                        <label for="excludeSelect${index}" class="form-label">Excludes ${index}:</label>
                                        <button type="button" class="btn btn-sm btn-danger remove-exclude" title="Remove">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <div class="content-container">
                                        <select class="form-select exclude-select mb-2" id="excludeSelect${index}" name="excludeSelect${index}" required>
                                            <option value="" selected disabled>Select Exclude</option>
                                            <option value="1">Flight (Round trip flight tickets)</option>
                                            <option value="2">Visa Fees</option>
                                            <option value="3">Meals (Meals outside the package)</option>
                                            <option value="4">Personal Expenses</option>
                                            <option value="5">Optional Tours</option>
                                            <option value="others">Others</option>
                                            <option value="0"> — No Excludes — </option>
                                        </select>
                                        <input type="text" class="form-control custom-exclude-input d-none" placeholder="Please specify..." />
                                    </div>
                                </div>
                            `;

                            // Event bindings for each new row
                            const select = row.querySelector('select');
                            const input = row.querySelector('.custom-exclude-input');
                            const removeBtn = row.querySelector('.remove-exclude');

                            // Handling change event for select options
                            select.addEventListener('change', () => {
                                if (select.value === 'others') {
                                    input.classList.remove('d-none');
                                    input.focus();
                                } else {
                                    input.classList.add('d-none');
                                }
                                updateDisabledExcludes();
                                getSelectedExcludes(); // Store and log updated selected values
                            });

                            // Handling input event for custom input field
                            input.addEventListener('input', () => {
                                getSelectedExcludes(); // Store and log updated selected values
                            });

                            // Handling remove button click
                            removeBtn.addEventListener('click', () => {
                                row.remove();
                                excludeConfig.count--;
                                updateExcludeLabels();
                                updateDisabledExcludes();
                                getSelectedExcludes(); // Store and log updated selected values after removal
                            });

                            excludeConfig.container.appendChild(row);
                            updateDisabledExcludes();
                        }

                        // Initialize the Excludes section
                        function initExcludesSection() {
                            // Create the first Exclude row
                            createExcludeRow();

                            // Add event listener to the Add button
                            excludeConfig.addButton?.addEventListener('click', () => {
                                if (excludeConfig.count < excludeConfig.max) {
                                    createExcludeRow();
                                }
                            });
                        }

                        // Initialize on page load
                        initExcludesSection();
                    });
                </script>




            </div>

            <div class="form-footer">
                <button type="button" class="btn btn-primary" id="submitTour">Generate Voucher</button>
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


    <!-- Timepicker & Datepicker General Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Function to initialize flatpickr with common settings
            function initFlatpickr(selector, options) {
                document.querySelectorAll(selector).forEach(function(element) {
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
                onOpen: function() {
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
                onOpen: function() {
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
        $(document).ready(function() {
            $('#flightTime').wickedpicker({
                twentyFour: true, // 24-hour format
                now: null, // Don't auto-fill current time
                showSeconds: false, // Hide seconds
                title: 'Select Time', // Title of popup
                placement: 'top' // Attempt to show above input
            });
        });
    </script>

    <!-- Datepicker Script -->
    <!-- <script>
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
    </script> -->




    <!-- Data Fetch to Fields
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
    </script> -->

    <!-- <script>
        const selectDays = document.getElementById("select-days");
        const itineraryContainer = document.getElementById("itinerary-container");
        const formFooter = document.querySelector(".form-footer"); // Select the form-footer
        const submitBtn = document.getElementById("submit-itinerary");

        // Korean Tour Data
        const koreanTourAreas = ["Seoul", "Busan", "Jeju", "Incheon", "Gyeongju"];

        const koreanMealPlans = ["Traditional Korean Cuisine", "Street Food Tour", "Seafood Specialty", "Vegetarian Option", "Luxury Fine Dining"];

        const hotels = [
            "", // Blank state
            "Lotte Hotel Seoul",
            "Signiel Seoul",
            "The Shilla Seoul",
            "Grand Hyatt Seoul",
            "InterContinental Seoul COEX",
            "Park Hyatt Busan",
            "Paradise Hotel Busan",
            "Lahan Hotel Jeonju",
            "Maison Glad Jeju",
            "Ramada Plaza Jeju"
        ];


        const itineraries = [
            "Gyeongbokgung Palace Tour",
            "Myeongdong Shopping District",
            "Namsan Seoul Tower",
            "Bukchon Hanok Village",
            "Dongdaemun Design Plaza",
            "Busan Gamcheon Culture Village",
            "Jeju Island Lava Tubes"
        ];

        const totalDays = 5;
        const selectedValues = {
            area: {},
            hotel: {},
            itinerary: {}
        };

        // Add options to the dropdown for 5 days
        for (let num = 1; num <= totalDays; num++) {
            let option = document.createElement("option");
            option.value = num;
            option.textContent = `Day ${num}`;
            selectDays.appendChild(option);
        }


        // Set the default selected option to Day 5
        selectDays.value = 5; // Default to Day 5

        const selectedDays = parseInt(selectDays.value);
        itineraryContainer.innerHTML = ""; // Clear previous content

        function updateDropdownOptions() {
            // Loop through all days
            for (let day = 1; day <= selectedDays; day++) {
                const areaSelects = document.querySelectorAll(`.area-select[data-day="${day}"]`);
                const hotelSelects = document.querySelectorAll(`.hotel-select[data-day="${day}"]`);
                const itinerarySelects = document.querySelectorAll(`.itinerary-select[data-day="${day}"]`);

                // Disable selected areas in other dropdowns
                areaSelects.forEach(select => {
                    select.querySelectorAll('option').forEach(option => {
                        option.disabled = selectedValues.area[day]?.includes(option.value);
                    });
                });

                // Disable selected hotels in other dropdowns
                hotelSelects.forEach(select => {
                    select.querySelectorAll('option').forEach(option => {
                        option.disabled = selectedValues.hotel[day]?.includes(option.value);
                    });
                });

                // Disable selected itineraries in other dropdowns
                itinerarySelects.forEach(select => {
                    select.querySelectorAll('option').forEach(option => {
                        option.disabled = selectedValues.itinerary[day]?.includes(option.value);
                    });
                });
            }
        }

        // Function to render the itinerary for selected days
        for (let day = 1; day <= selectedDays; day++) {
            const card = document.createElement("div");
            card.className = "card itinerary-card mb-3"; // Bootstrap margin-bottom for spacing

            card.innerHTML = `
                <div class="card-header bg-primary text-white fw-bold">Day ${day}</div>
                <div class="card-body">
                    <div class="container-fluid">

                        <div class="row mb-3">
                            ${day === 1 
                                ? `<div class="col-4">
                                    <label class="form-label fw-semibold">Area:</label>    
                                    <select class="form-select area-select" data-day="${day}" required>
                                        <option selected disabled>Select Area</option>
                                        ${koreanTourAreas.map(area => `<option value="${area}">${area}</option>`).join("")}
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

                        <div class="row mb-3">
                            ${day === 1 
                                ? `<div class="col-4">
                                    <label class="form-label fw-semibold">Meal Plan:</label>
                                    <select class="form-select meal-plan-select" data-day="${day}" required>
                                        <option selected disabled>Select Meal Plan</option>
                                        ${koreanMealPlans.map(meal => `<option value="${meal}">${meal}</option>`).join("")}
                                    </select>
                                </div>`
                                : ["Breakfast", "Lunch", "Dinner"].map(mealLabel => `
                                    <div class="col-4">
                                        <label class="form-label fw-semibold">${mealLabel}:</label>
                                        <select class="form-select meal-plan-select" data-day="${day}" required>
                                            <option selected disabled>Select ${mealLabel}</option>
                                            ${koreanMealPlans.map(meal => `<option value="${meal}">${meal}</option>`).join("")}
                                        </select>
                                    </div>
                                `).join("")
                            }
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Hotels:</label>
                                <div class="row">
                                    ${["Hotel 1", "Hotel 2"].map(hotelLabel => `
                                        <div class="col-md-4 col-sm-12 mb-2">
                                            <select class="form-select hotel-select" data-day="${day}" required>
                                                <option selected disabled>Select ${hotelLabel}</option>
                                                ${hotels.map(hotel => `<option value="${hotel}">${hotel}</option>`).join("")}
                                            </select>
                                        </div>
                                    `).join("")}
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Itinerary:</label>
                            </div>
                            ${day === 1 
                                ? [1, 2, 3, 4].map(num => `
                                    <div class="col-12 mb-2">
                                        <select class="form-select itinerary-select" data-day="${day}" required>
                                            <option selected disabled>Select Itinerary ${num}</option>
                                            ${itineraries.map(itinerary => `<option value="${itinerary}">${itinerary}</option>`).join("")}
                                        </select>
                                    </div>
                                `).join("")
                                : [1, 2, 3, 4, 5, 6, 7].map(num => `
                                    <div class="col-12 mb-2">
                                        <select class="form-select itinerary-select" data-day="${day}" required>
                                            <option selected disabled>Select Itinerary ${num}</option>
                                            ${itineraries.map(itinerary => `<option value="${itinerary}">${itinerary}</option>`).join("")}
                                        </select>
                                    </div>
                                `).join("")
                            }
                        </div>

                    </div>
                </div>
            `;

            itineraryContainer.appendChild(card);
        }


        // Hide the form-footer when itinerary is cleared
        formFooter.style.display = selectedDays ? "flex" : "none";

        document.getElementById("submit-itinerary").addEventListener("click", function(event) {
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

        // Event listener to handle selection changes
        document.addEventListener("change", function(event) {
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
    </script> -->

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
                        window.location.href = "../Employee Section/emp-itinerarytable.php";
                    } else {
                        alert("Error saving itinerary: " + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    submitButton.disabled = false;
                    console.error("AJAX Error:", error);
                    console.error("Response Text:", xhr.responseText);
                    alert("An error occurred while saving the itinerary.");
                }
            });

            $("#templateNameModal").modal("hide");
        }
    </script> -->

</body>

</html>