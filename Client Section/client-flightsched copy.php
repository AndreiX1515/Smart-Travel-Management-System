<?php
// include 'session_validate.php'; // This will check if the session is valid
require '../conn.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch session variables directlys
$email = $_SESSION['email'] ?? ''; // Use null coalescing operator to avoid undefined index
// $firstName = $_SESSION['first_name'] ?? '';
// $lastName = $_SESSION['last_name'] ?? '';
// $middleName = $_SESSION['middle_name'] ?? '';
$accId = $_SESSION['accountId'] ?? '';

// $fullName = htmlspecialchars($lastName . ', ' . $firstName . ($middleName ? ' ' . substr($middleName, 0, 1) . '.' : ''));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../Client Section/Includes/head.php'; ?>

  <title>Flight Schedules</title>

  <link rel="stylesheet" href="../Client Section/assets/css/client-portal.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-flightSched.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Client Section/assets/css/client-navbar.css?v=<?php echo time(); ?>">

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


  <?php include '../Client Section/Includes/client-navbar.php'; ?>

  <div class="body-container">


    <div class="main-container">
      <div class="flight-schedules">

        <div class="section-wrapper">
          <div class="section-header">
            <div class="header-info">
                <h3>Flight Schedules</h3>
                <p>Check out our latest flight schedules and book your next adventure today!</p>
            </div>

            <div class="filters-container">
                <div class="filters">
                    <div class="filter-group">
                        <label for="flight-month">Select Month:</label>
                        <select id="flight-month">
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

                    <div class="filter-group">
                        <label for="flight-date">Select Flight Date:</label>
                        <input type="date" id="flight-date">
                    </div>
                    
                    <button id="clear-filters" class="clear-btn">Clear</button>
                </div>
            </div>




          </div>


          <div class="section-main-content">
            <div class="confirm-table-container-flight">
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
                      f.flightPrice AS RetailPrice,  
                      f.flightPrice AS FlightPrice   
                  FROM 
                      employee e 
                  JOIN 
                      flight f ON f.employeeId = e.employeeId
                  LEFT JOIN 
                      booking b ON b.flightId = f.flightId
                  WHERE 
                      f.flightDepartureDate >= CURDATE()
                  GROUP BY 
                      f.flightId, f.origin, f.flightDepartureDate, f.returnDepartureDate, f.availSeats, f.flightPrice
                  ORDER BY 
                      f.flightDepartureDate;
                  ";

              $result = $conn->query($sql);
              ?>

              <!-- Date Picker -->
              <div id="flights-container">
                <?php
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo '<div class="flight-card" data-date="' . htmlspecialchars($row['Start']) . '">';
                    echo '    <div class="flight-info">';

                    // Flight Details
                    echo '        <div class="flight-details">';
                    echo '            <div class="details-header">';
                    echo '                <h3>' . htmlspecialchars($row['origin']) . '</h3>';
                    echo '            </div>';
                    echo '        </div>';

                    // Flight Date Section
                    echo '        <div class="flight-date-wrapper">';
                    echo '            <label for="">Flight Date: </label>';
                    echo '            <div class="flight-date">';
                    echo '                <div class="flight-start">';
                    echo '                    <label for="">Start:</label>';
                    echo '                    <h5>' . htmlspecialchars($row['Start']) . '</h5>';
                    echo '                </div>';
                    echo '                <div class="flight-end">';
                    echo '                    <label for="">End:</label>';
                    echo '                    <h5>' . htmlspecialchars($row['End']) . '</h5>';
                    echo '                </div>';
                    echo '            </div>';

                    echo '            <div class="flight-date">';
                    echo '                <div class="flight-start">';
                    echo '                    <label for="">Package Price</label>';
                    echo '                    <h5> ₱ ' . number_format($row['FlightPrice'], 2) . '</h5>';
                    echo '                </div>';
                    echo '            </div>';
                    

                    echo '';            
                    echo '        </div>';

                    // Seats Section
                    echo '        <div class="seats-wrapper">';
                    echo '            <div class="seats-container">';
                    echo '                <div class="seats-info">';
                    echo '                    <label for="">Available Seats:</label>';
                    echo '                    <p><strong>' . htmlspecialchars($row['AvailSeats']) . '</strong></p>';
                    echo '                </div>';
                    echo '                <div class="seats-info">';
                    echo '                    <label for="">Additional Seats:</label>';
                    echo '                    <p><strong>' . htmlspecialchars($row['AdditionalSeats']) . '</strong></p>';
                    echo '                </div>';
                    echo '            </div>';

                    // Book Now Button
                    echo '            <div class="book-now-container">';
                    echo '                <a href="../Client Section/login.php?flightid=' . urlencode($row['flightid']) . '" class="btn book-now">Book Now</a>';
                    echo '            </div>';


                    echo '        </div>';

                    echo '    </div>';
                    echo '</div>';
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
  </div>


  <?php include '../Client Section/Includes/scripts.php'; ?>
  <!-- <script src="heartbeat.js"></script>  -->

  <script>
  document.addEventListener("DOMContentLoaded", function () {
      const datePicker = document.getElementById("flight-date");
      const monthSelect = document.getElementById("flight-month");
      const flightCards = document.querySelectorAll(".flight-card");
      const clearButton = document.getElementById("clear-filters"); // Get Clear button

      function filterFlights() {
          const selectedDate = datePicker.value; // Get selected date
          const selectedMonth = monthSelect.value; // Get selected month

          flightCards.forEach(card => {
              const flightDate = card.getAttribute("data-date"); // Get flight's departure date
              const flightMonth = flightDate ? flightDate.split("-")[1] : ""; // Extract month

              const matchesDate = selectedDate === "" || flightDate === selectedDate;
              const matchesMonth = selectedMonth === "" || flightMonth === selectedMonth;

              if (matchesDate && matchesMonth) {
                  card.style.display = "block"; // Show matching flights
              } else {
                  card.style.display = "none"; // Hide non-matching flights
              }
          });
      }

      function clearFilters() {
          datePicker.value = "";
          monthSelect.value = "";
          filterFlights(); // Refresh flights display after clearing
      }

      datePicker.addEventListener("change", filterFlights);
      monthSelect.addEventListener("change", filterFlights);
      clearButton.addEventListener("click", clearFilters); // Attach event to clear button
  });


  </script>


  <!-- Row Click Selection JS -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll("tr[data-url]").forEach(function(row) {
        row.addEventListener("click", function() {
          const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

          console.log("Transaction Number: ", transactionNumber);

          // Use AJAX to send the transaction number to the server
          $.ajax({
            url: '../Agent Section/functions/fetchTransactNo.php', // The PHP file to handle the session setting
            type: 'POST',
            data: {
              transaction_number: transactionNumber
            },
            success: function(response) {
              console.log("Response: ", response); // Debugging line

              // Redirect to the next page after successfully setting the session
              window.location.href = row.getAttribute("data-url"); // Use the original URL stored in data-url attribute
            },
            error: function(xhr, status, error) {
              console.error("AJAX Error: " + status + " " + error); // Enhanced error logging
            }
          });
        });
      });
    });
  </script>

</body>

</html>