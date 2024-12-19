<?php 
session_start(); 

include '../Agent Section/includes/breadcrumbs.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions</title>

  <?php include '../Agent Section/includes/head.php' ?>
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">

</head>

<body>
  <?php include '../Agent Section/includes/sidebar.php'; ?> 

  <div class="main-content" id="mainContent">
    <?php include '../Agent Section/includes/navbar.php'; ?>

    <div class="content-wrapper-transact d-flex flex-column">
      <!-- <div class="table-header">
         <div class="sorting-wrapper">

           <div class="dropdown-sorting-wrapper">
              <div class="filter-field mb-3 d-flex flex-column">
                <label for="packages">Packages:</label>
                <div class="select-wrapper mt-2">
                  <select id="packages" class="custom-select">
                    <option value="">All</option>
                    <option value="Autumn Tour Package">Autumn Tour</option>
                    <option value="Summer Tour Package">Summer Tour</option>
                    <option value="Spring Tour Package">Spring Tour</option>
                    <option value="Winter Tour Package">Winter Tour</option>
                    <option value="Regular Tour Package">Regular Tour</option>
                    <option value="Busan Tour Package">Busan Tour</option>
                  </select>
                </div>
              </div>

              <div class="filter-field mb-3 d-flex flex-column">
                <label for="status">Status:</label>
                <div class="select-wrapper mt-2">
                  <select id="status" class="custom-select">
                    <option value="">All</option>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Cancelled">Cancelled</option>
                  </select>
                </div>
              </div>
           </div>

           <div class="flightbooking-wrapper d-flex flex-row ">

             <div class="date-range-wrapper">
               <div class="label-wrapper">
                 <label for="status">Booking Date (Start - End):</label>
               </div>
               <div class="date-range-inputs-wrapper">
                 <input type="text" class="datepicker" id="BookingStartDate" placeholder="Start Date">
                 <span class="fw-bolder"> <i class="fa-solid fa-arrow-right"></i> </span>
                 <input type="text" class="datepicker" id="BookingEndDate" placeholder="End Date">
               </div>
             </div>

             <div class="date-range-wrapper">
               <div class="label-wrapper">
                 <label for="status">Flight Date (Start - End):</label>
               </div>
               <div class="date-range-inputs-wrapper">
                 <input type="text" class="datepicker" id="FlightStartDate" placeholder="Start Date">
                 <span class="fw-bold"> <i class="fa-solid fa-arrow-right"> </i> </span>
                 <input type="text" class="datepicker" id="FlightEndDate" placeholder="End Date">
               </div>
             </div>

            
             <div class="filter-field d-flex align-center">
              <button id="clearSorting" class="btn btn-secondary btn-sm clearFilters">
                 Clear Filters
              </button>
            </div>

           </div>
         </div>

         <div class="search-bar">
           <div class="left-side">
             <div class="search-input">
               <label for="status">Search:</label>
               <input type="text" id="search" class="form-control mt-1" placeholder="Search">
             </div>
           </div>

           <div class="right-side" style="display: flex; align-items: baseline; gap: 10px;">
             <label for="entries" style="font-family: Arial, sans-serif;">Show </label>
             <select id="entries" style="padding: 5px; font-family: Arial, sans-serif; border: 1px solid #ced4da; border-radius: 4px;">
               <option value="10">10</option>
               <option value="25">25</option>
               <option value="50">50</option>
               <option value="100">100</option>
             </select>
             <label for="entries" style="font-family: Arial, sans-serif;">Entries</label>
           </div>
           
         </div>
      </div> -->

      <div class="table-container">
        <table id="product-table" class="product-table mt-2">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Contact Person Info</th>
                    <th>Contact Person Contact Details</th>
                    <th>Package Name</th>
                    <th>Transaction Date</th>
                    <th>Flight Date</th>
                    <th>Total Pax</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $agentCode = $_SESSION['agent_agentCode'];
                $agentId = $_SESSION['agent_accountId'];

                $sql1 = "SELECT b.transactNo AS `T.N`, p.packageName AS `PACKAGE`, 
                            DATE_FORMAT(b.bookingDate, '%m-%d-%Y') AS `TRANSACTION DATE`, b.bookingType as bookingType,
                            DATE_FORMAT(f.flightDepartureDate, '%m-%d-%Y') AS `FLIGHT DATE`,
                            b.pax AS `TOTAL PAX`, CONCAT(b.lName, ', ', b.fName, ' ', CASE WHEN b.mName = 'N/A' 
                            THEN '' ELSE CONCAT(SUBSTRING(b.mName, 1, 1), '.') END, ' ', CASE WHEN b.suffix = 'N/A' THEN '' 
                            ELSE b.suffix END) AS `CONTACT NAME`, b.email AS `CONTACT EMAIL`,
                            CONCAT(b.countryCode, ' ', b.contactNo) AS `CONTACT PHONE`, b.status AS `STATUS`
                        FROM 
                            booking b
                        LEFT JOIN 
                            flight f ON b.flightId = f.flightId
                        LEFT JOIN 
                            package p ON b.packageId = p.packageId
                        LEFT JOIN
                            agent a ON b.agentId = a.agentId
                        WHERE 
                            b.agentCode = '$agentCode' 
                        ORDER BY 
                            b.transactNo DESC";

                $res1 = $conn->query($sql1);

                if ($res1->num_rows > 0) {
                    while ($row = $res1->fetch_assoc()) {
                        $transactNo = $row['T.N'];
                        $pax = $row['TOTAL PAX'];

                        $status = isset($row['STATUS']) ? $row['STATUS'] : 'Unknown';
                        $statusClass = '';

                        switch ($status) {
                            case 'Confirmed':
                                $statusClass = 'bg-success text-white'; // Green background, white text
                                break;
                            case 'Cancelled':
                                $statusClass = 'bg-danger text-white'; // Red background, white text
                                break;
                            case 'Pending':
                                $statusClass = 'bg-warning text-dark'; 
                                break;
                            default:
                                $statusClass = 'bg-secondary text-white'; 
                        }

                        echo "<tr data-url='agent-showGuest.php?id=" . htmlspecialchars($transactNo) . "'>
                                <td>{$transactNo}</td>
                                <td>{$row['CONTACT NAME']}</td>
                                <td>
                                    <div class='d-flex flex-column'>
                                        <span><strong>Email: </strong>" . $row['CONTACT EMAIL'] ."</span>
                                        <span><strong>Contact Number: </strong>" . $row['CONTACT PHONE'] ."</span>
                                    </div>
                                </td>
                                <td>{$row['PACKAGE']}</td>
                                <td>{$row['TRANSACTION DATE']}</td>
                                <td>{$row['FLIGHT DATE']}</td>
                                <td style='text-align: center; font-weight: bold;'>{$row['TOTAL PAX']}</td>
                                <td>
                                    <span class='badge p-2 rounded-pill {$statusClass}'>
                                        {$status}
                                    </span>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No bookings found</td></tr>";
                }

                if ($res1) {
                    $res1->free();
                }

                $conn->close();
                ?>
            </tbody>
        </table>
</div>

    </div>
  </div>

  <!-- DataTables #product-table -->
<script>
$(document).ready(function () {
      const table = $('#product-table').DataTable({
        dom: 'rtip',
        columnDefs: [
            { width: '10%', targets: 0 }, // ID
            { width: '15%', targets: 1 }, // Contact Person Name
            { width: '20%', targets: 2 }, // Contact Person Details
            { width: '15%', targets: 3 }, // Package Name
            { width: '12%', targets: 4 }, // Booking Date
            { width: '13%', targets: 5 }, // Flight Date
            { width: '10%', targets: 6 }, // Total Pax
            { width: '5%',  targets: 7 }  // Status
        ],
        language: {
            emptyTable: "No Transaction Records Available"
        },
        order: [[0, 'desc']],
        scrollX: false,
        autoWidth: false,
        pageLength: 8, // Limit the number of rows per page to 8
    });

    // Search Functionality
    $('#search').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Status Filter
    $('#status').on('change', function () {
        const selectedStatus = $(this).val();
        table.column(8).search(selectedStatus || '').draw();
    });

    // Package Filter
    $('#packages').on('change', function () {
        const selectedPackage = $(this).val();
        table.column(3).search(selectedPackage || '').draw();
    });

    // Initialize Datepickers
    const dateFields = ['#FlightStartDate', '#FlightEndDate', '#BookingStartDate', '#BookingEndDate'];
    dateFields.forEach((field) => {
        $(field).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        }).on('focus', (e) => {
            e.preventDefault();
        });
    });

    // Helper Function to Check Date in Range
    function isDateInRange(startDate, endDate, targetDate) {
        if (!targetDate) return true; // Allow empty target dates
        const target = new Date(targetDate);
        const start = startDate ? new Date(startDate) : null;
        const end = endDate ? new Date(endDate) : null;
        return (!start || target >= start) && (!end || target <= end);
    }

    // Custom Filter for Flight Dates
    $.fn.dataTable.ext.search.push(function (settings, data) {
        const flightStartDate = $('#FlightStartDate').val();
        const flightEndDate = $('#FlightEndDate').val();
        const flightDate = data[5];
        return isDateInRange(flightStartDate, flightEndDate, flightDate);
    });

    // Custom Filter for Booking Dates
    $.fn.dataTable.ext.search.push(function (settings, data) {
        const bookingStartDate = $('#BookingStartDate').val();
        const bookingEndDate = $('#BookingEndDate').val();
        const bookingDate = data[4];
        return isDateInRange(bookingStartDate, bookingEndDate, bookingDate);
    });

    // Apply Filters on Date Change
    $('#FlightStartDate, #FlightEndDate, #BookingStartDate, #BookingEndDate').on('change', function () {
        table.draw();
    });

    // Clear All Filters
    $('#clearSorting').on('click', function () {
        $('#search').val('');
        table.search('').draw();

        $('#status').val('').change();
        $('#packages').val('').change();

        dateFields.forEach((field) => {
            $(field).val('');
        });

        table.draw();
    });
});
</script>

  <?php require "../Agent Section/includes/scripts.php"; ?>


  </body>
</html>