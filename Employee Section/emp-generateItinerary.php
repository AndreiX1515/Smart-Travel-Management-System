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
                        <h5>No. of Days</h5>
                    </div>

                    <div class="card-body">

                        <!-- Package Row -->
                        <div class="row">
                            
                            <div class="columns col-md-3">
                                <div class="form-group">
                                    <label for="flightDate">No. of days<span class="text-danger"> *</span></label>

                                    <select class="form-select" id="select-days" name="numberOfDays" required>
                                        <option selected disabled>Select Number of Days</option>
                                    </select>

                                    <!-- JavaScript -->
                                    <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        const selectDays = document.getElementById("select-days");

                                        // Populate options from 1 to 7
                                        for (let i = 1; i <= 7; i++) {
                                            let option = document.createElement("option");
                                            option.value = i;
                                            option.textContent = `Day ${i}`;
                                            selectDays.appendChild(option);
                                        }
                                    });
                                    </script>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5>Itinerary Details</h5>
                    </div>

                    <div class="card-body">

                        <!-- Package Row -->
                        <div class="row">
                            <!-- Flight Date Dropdown -->
                            <div class="columns col-md-4">
                                <div class="form-group">
                                    <label for="flightDate">Package<span class="text-danger"> *</span></label>
                                    <select class="form-select" id="flightDate" name="flightDate" required>
                                        <option selected disabled>Select Flight Date</option>
                                    </select>
                                    <span id="flightDateError" class="text-danger"></span>
                                    <!-- Error message for outbound flight -->
                                </div>
                            </div>

                        </div>

                        <!-- Periods, Guide Row -->
                        <div class="row">
                            <!-- Flight Date Dropdown -->
                            <div class="columns col-md-4">
                                <div class="form-group">
                                    <label for="flightDate">Periods<span class="text-danger"> *</span></label>
                                    <select class="form-select" id="flightDate" name="flightDate" required>
                                        <option selected disabled>Select Flight Date</option>
                                    </select>

                                    <span id="flightDateError" class="text-danger"></span>
                                    <!-- Error message for outbound flight -->
                                </div>
                            </div>

                            <!-- Total Pax Input -->
                            <div class="columns col-md-4">
                                <div class="form-group">
                                    <div class="col-header">
                                        <label for="totalPax">Guide<span class="text-danger"> *</span></label>
                                    </div>

                                    <input type="number" class="form-control" id="totalPax" name="totalPax" min="1" placeholder="Enter Total Pax" required>

                                    <span id="totalPaxError" class="text-danger"></span>
                                    <!-- Error message for Total Pax -->
                                </div>
                            </div>

                            <div class="columns col-md-4">
                                <div class="form-group">
                                    <div class="col-header">
                                        <label for="totalPax">Contact Number<span class="text-danger"> *</span></label>
                                        <label id="maxSeats"></label>
                                        <label id="availSeats"></label>
                                    </div>

                                    <input type="number" class="form-control" id="totalPax" name="totalPax" min="1" placeholder="Enter Total Pax" required>

                                    <span id="totalPaxError" class="text-danger"></span>
                                    <!-- Error message for Total Pax -->
                                </div>
                            </div>

                        </div>

                        <!-- Hotel -->
                        <div class="row">
                            <!-- Flight Date Dropdown -->
                            <div class="columns col-md-4">
                                <div class="form-group">
                                    <label for="flightDate">Hotels<span class="text-danger"> *</span></label>

                                    <select class="form-select" id="flightDate" name="flightDate" required>
                                        <option selected disabled>Select Flight Date</option>
                                    </select>

                                    <span id="flightDateError" class="text-danger"></span>
                                    <!-- Error message for outbound flight -->
                                </div>
                            </div>

                            <!-- Total Pax Input -->
                            <div class="columns col-md-4">
                                <div class=" form-group">
                                    <div class="col-header">
                                        <label for="totalPax"><span class="text-danger"></span></label>
                                    </div>

                                    <select class="form-select" id="flightDate" name="flightDate" required>
                                        <option selected disabled>Select Flight Date</option>
                                    </select>

                                    <span id="totalPaxError" class="text-danger"></span>
                                    <!-- Error message for Total Pax -->
                                </div>
                            </div>

                            <div class="columns col-md-4">
                                <div class="form-group">
                                    <div class="col-header">
                                        <label for="totalPax"><span class="text-danger"></span></label>
                                        
                                    </div>

                                    <select class="form-select" id="flightDate" name="flightDate" required>
                                        <option selected disabled>Select Flight Date</option>
                                    </select>

                                    <span id="totalPaxError" class="text-danger"></span>
                                    <!-- Error message for Total Pax -->
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div id="itinerary-container">
                    <div id="hotel-container">

                    </div>
                </div>


            </div>
        </div>
    </div>

    <?php include '../Employee Section/includes/emp-scripts.php' ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const selectDays = document.getElementById("select-days");
        const itineraryContainer = document.getElementById("itinerary-container");
        const hotelContainer = document.getElementById("hotel-container");
        const mealPlanContainer = document.getElementById("meal-plan-container");
        
        function updateItinerary() {
            const selectedDays = parseInt(selectDays.value);
            itineraryContainer.innerHTML = "";
            
            for (let day = 1; day <= selectedDays; day++) {
                const card = document.createElement("div");
                card.className = "card itinerary-card";
                card.innerHTML = `
                <div class="card-header">Day ${day}</div>
                <div class="card-body">
                    <!-- Itinerary Section -->
                    <div class="itinerary-section">
                        <label>Itinerary:</label>
                        
                        ${[...Array(7)].map((_, i) => `
                            <div class="select-wrapper">
                                <div class="select-number">
                                    <h6>${i + 1}.</h6>
                                </div>
                                <div class="select-container">
                                    <select class="form-select" name="itinerary[${day}][${i + 1}]" id="itinerary-${day}-${i + 1}" required>
                                        <option selected disabled>Select Itinerary ${i + 1}</option>
                                    </select>
                                </div>
                            </div>
                        `).join("")}
                    </div>
                    
                    
                </div>
            `;
            itineraryContainer.appendChild(card);
            }
            initializeHotelSelections(selectedDays);
            initializeMealPlanSelections(selectedDays);
        }
        
        function initializeHotelSelections(selectedDays) {
            hotelContainer.innerHTML = "";
            
            for (let day = 1; day <= selectedDays; day++) {
                for (let i = 1; i <= 3; i++) {
                    const card = document.createElement("div");
                    card.className = "card hotel-card";
                    card.innerHTML = `
                        <div class="card-header">Hotel Day ${day} - ${i}</div>
                        <div class="card-body">
                            <label>Hotel Day ${day} - ${i}:</label>
                            <select class="form-control" name="hotel[${day}][${i}]">
                                <option value="">Select Hotel</option>
                            </select>
                        </div>
                    `;
                    hotelContainer.appendChild(card);
                }
            }
        }
        
        function initializeMealPlanSelections(selectedDays) {
            mealPlanContainer.innerHTML = "";
            
            for (let day = 1; day <= selectedDays; day++) {
                for (let i = 1; i <= 3; i++) {
                    const card = document.createElement("div");
                    card.className = "card meal-plan-card";
                    card.innerHTML = `
                        <div class="card-header">Meal Plan Day ${day} - ${i}</div>
                        <div class="card-body">
                            <label>Meal Plan Day ${day} - ${i}:</label>
                            <select class="form-control" name="meal_plan[${day}][${i}]">
                                <option value="">Select Meal Plan</option>
                            </select>
                        </div>
                    `;
                    mealPlanContainer.appendChild(card);
                }
            }
        }
        
        selectDays.addEventListener("change", updateItinerary);
        
        updateItinerary();
    });


    </script>





</body>

</html>