<?php
// include 'session_validate.php'; // This will check if the session is valid
require '../conn.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
$accId = $_SESSION['accountId'] ?? '';


echo "<script>";
echo "var sessionData = " . json_encode($_SESSION, JSON_PRETTY_PRINT) . ";";
echo "console.log('Session Data:', sessionData);";
echo "</script>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../Client Section/includes/head.php'; ?>

    <title>Flight Schedules</title>
    <link rel="stylesheet" href="../Mobile/assets/css/client-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Mobile/assets/css/client-flightSched.css?v=<?php echo time(); ?>">

</head>

<body>

    <?php
    if (isset($_SESSION['status'])):
        ?>

        <!-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Hey!</strong> <?= $_SESSION['status']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> -->

        <?php
        unset($_SESSION['status']);
    endif;
    ?>

    <?php include '../Mobile/includes/client-navbar.php'; ?>

    <div class="body-container">
        <div class="main-container">

            <div class="flight-schedules">

                <div class="section-wrapper">

                    <div class="section-header">
                        <div class="header-info">
                            <h3>Flight Schedules</h3>
                            <p>Check out our latest flight schedules here!</p>
                        </div>

                        <div class="filters-container">
                            <div class="filters">
                                <!-- Month Filter -->
                                <div class="filter-month">
                                    <label for="filter-month">Month:</label>
                                    <select id="filter-month">
                                        <option value="">All Months</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>

                                <!-- Year Filter -->
                                <div class="filter-year">
                                    <label for="filter-year">Year:</label>
                                    <select id="filter-year"></select>
                                </div>

                                <!-- Date Filter -->
                                <div class="filter-date">
                                    <label for="filter-date">Flight Date:</label>
                                    <input type="date" id="filter-date">
                                </div>

                                <div class="filter-button">
                                    <label for="filter-date"></label>
                                    <button id="clear-filters" class="clear-btn">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="section-main-content">
                        <button id="back-to-top" class="back-to-top">
                            <i class="fas fa-arrow-up"></i>
                        </button>

                        <div id="flights-container">

                            <?php
                            // Database query
                            $sql = "SELECT 
                                    f.flightId AS flightid, 
                                    f.origin, 
                                    f.flightDepartureDate AS Start, 
                                    f.returnDepartureDate AS End, 
                                    f.availSeats AS FlightSeat, 
                                    GREATEST(
                                        (f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' 
                                        AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)), 
                                        0
                                    ) AS AvailSeats, 
                                    IF(
                                        (f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' 
                                        AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)) < 0, 
                                        ABS(f.availSeats - IFNULL(SUM(CASE WHEN b.status = 'Confirmed' 
                                        AND b.bookingType = 'Package' THEN b.pax ELSE 0 END), 0)), 
                                        0
                                    ) AS AdditionalSeats, 
                                    f.flightPrice AS FlightPrice   
                                FROM 
                                    flight f
                                LEFT JOIN 
                                    booking b ON b.flightId = f.flightId
                                WHERE 
                                    f.flightDepartureDate >= CURDATE()
                                GROUP BY 
                                    f.flightId, f.origin, f.flightDepartureDate, f.returnDepartureDate, f.availSeats, f.flightPrice
                                ORDER BY 
                                    f.flightDepartureDate";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <div class="flight-card" data-date="<?= htmlspecialchars($row['Start']) ?>">
                                        <div class="flight-info">
                                            <div class="flight-details">
                                                <h3><?= htmlspecialchars($row['origin']) ?></h3>
                                            </div>

                                            <div class="flight-date-wrapper">
                                                <div class="flight-date-content">

                                                    <div class="container-body flight-date-body">
                                                        <div class="flight-start">
                                                            <label>Start:</label>
                                                            <h5><?= htmlspecialchars($row['Start']) ?></h5>
                                                        </div>

                                                        <div class="flight-start">
                                                            <label>End:</label>
                                                            <h5><?= htmlspecialchars($row['End']) ?></h5>
                                                        </div>

                                                        <div class="flight-start">
                                                            <label>Package Price:</label>
                                                            <h5>₱ <?= number_format($row['FlightPrice'], 2) ?></h5>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="flight-seats-wrapper">
                                                    <div class="seats-container">
                                                        <div class="container-body seats-body">

                                                            <div class="first-part">
                                                                <div class="seats-info">
                                                                    <label>Available Seats:</label>
                                                                    <h5><?= htmlspecialchars($row['AvailSeats']) ?></h5>
                                                                </div>
                                                                <div class="seats-info">
                                                                    <label>Additional Seats:</label>
                                                                    <h5><?= htmlspecialchars($row['AdditionalSeats']) ?></h5>
                                                                </div>
                                                            </div>

                                                            <div class="seats-info">
                                                                <div class="book-now-container">
                                                                    <form action="../Mobile/agentLogin.php" method="POST">
                                                                        <input type="hidden" name="flightid"
                                                                            value="<?= htmlspecialchars($row['flightid']) ?>">
                                                                        <button type="submit" class="btn book-now">Book
                                                                            Now</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<p>No flights available.</p>";
                            }
                            ?>
                        </div>
                    </div>
                    
                </div>

            </div>

        </div>
    </div>


    <?php include '../Client Section/Includes/scripts.php'; ?>
    <!-- <script src="heartbeat.js"></script>  -->


    <!-- JS for back-to-top button -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const backToTopButton = document.getElementById("back-to-top");

            if (!backToTopButton) {
                console.error("Button #back-to-top not found.");
                return;
            }

            window.addEventListener("scroll", function () {
                if (window.scrollY > 300) {
                    backToTopButton.classList.add("show");
                } else {
                    backToTopButton.classList.remove("show");
                }
            });

            backToTopButton.addEventListener("click", function () {
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
        });
    </script>



    <!-- JS for Year Select -->
    <script>
        // Get the current year
        const currentYear = new Date().getFullYear();

        // Reference the select element
        const yearSelect = document.getElementById("filter-year");

        // Generate years (from 10 years ago to 10 years ahead)
        const startYear = currentYear - 10;
        const endYear = currentYear + 10;

        for (let year = startYear; year <= endYear; year++) {
            let option = document.createElement("option");
            option.value = year;
            option.textContent = year;

            // Set current year as default
            if (year === currentYear) {
                option.selected = true;
            }

            yearSelect.appendChild(option);
        }
    </script>

    <!-- JS for Sorting Function -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const datePicker = document.getElementById("filter-date");
            const monthSelect = document.getElementById("filter-month");
            const yearSelect = document.getElementById("filter-year");
            const flightCards = document.querySelectorAll(".flight-card");
            const clearButton = document.getElementById("clear-filters"); // Clear button

            function filterFlights() {
                const selectedDate = datePicker.value; // Get selected date (YYYY-MM-DD)
                const selectedMonth = monthSelect.value; // Get selected month (MM)
                const selectedYear = yearSelect.value; // Get selected year (YYYY)

                flightCards.forEach(card => {
                    const flightDate = card.getAttribute("data-date"); // Flight's full date (YYYY-MM-DD)

                    if (!flightDate) return; // Skip if no date available

                    const [year, month, day] = flightDate.split("-"); // Extract year, month, day

                    const matchesDate = selectedDate === "" || flightDate === selectedDate;
                    const matchesMonth = selectedMonth === "" || month === selectedMonth;
                    const matchesYear = selectedYear === "" || year === selectedYear;

                    if (matchesDate && matchesMonth && matchesYear) {
                        card.style.display = "block"; // Show matching flights
                    } else {
                        card.style.display = "none"; // Hide non-matching flights
                    }
                });
            }

            function clearFilters() {
                datePicker.value = "";
                monthSelect.value = "";
                yearSelect.value = new Date().getFullYear(); // Reset to current year
                filterFlights(); // Refresh flights display after clearing
            }

            datePicker.addEventListener("change", filterFlights);
            monthSelect.addEventListener("change", filterFlights);
            yearSelect.addEventListener("change", filterFlights);
            clearButton.addEventListener("click", clearFilters); // Attach event to clear button
        });
    </script>


    <!-- Row Click Selection JS
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("tr[data-url]").forEach(function (row) {
                row.addEventListener("click", function () {
                    const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

                    console.log("Transaction Number: ", transactionNumber);

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
    </script> -->

</body>

</html>