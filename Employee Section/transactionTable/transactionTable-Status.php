<?php
$statusTab = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : 'all';
$showAll = isset($_GET['showAll']) && $_GET['showAll'] == '1';
$dateFilter = $showAll ? '' : "f.flightDepartureDate > CURDATE()";

// Build status WHERE condition only when not 'All'
$whereStatus = '';

if ($statusTab !== 'all') {
  $statusEsc = mysqli_real_escape_string($conn, $statusTab);
  $whereStatus = " AND b.status = '{$statusEsc}'";
}

// // Example: main query that feeds the table (use $whereStatus)
// $sql = "SELECT b.*, f.* 
//         FROM booking b 
//         JOIN flight f ON f.flightId = b.flightId
//         WHERE 1 {$whereStatus} " . ($dateFilter ? "AND $dateFilter" : "");
// $result = mysqli_query($conn, $sql);
?>



<div class="table-container">

  <div class="table-header">

    <div class="search-wrapper">
      <label class="" for="">Search: </label>
      <div class="search-input-wrapper">
        <input type="text" id="search" placeholder="Search here..">
      </div>
    </div>

    <div class="second-header-wrapper">

      <!-- Branch Selection -->
      <div class="sorting-wrapper aligned-item">
        <label for="branch">Select Branch</label>
        <div class="select-wrapper">
          <select id="branch">
            <option value="" disabled selected>Select Branch</option>
            <?php
            $sql1 = "SELECT branchId, branchName FROM branch ORDER BY branchName ASC";
            $res1 = $conn->query($sql1);
            if ($res1->num_rows > 0) {
              while ($row = $res1->fetch_assoc()) {
                echo "<option value='" . $row['branchName'] . "'>" . $row['branchName'] . "</option>";
              }
            } else {
              echo "<option value=''>No companies available</option>";
            }
            ?>
          </select>
        </div>
      </div>

      <!-- Show All Transaction Toggle -->
      <div class="sorting-wrapper aligned-item">
        <form id="showAllForm">
          <label style="opacity: 0;">Clear</label>
          <label class="toggle-switch">
            <input type="checkbox" id="showAllToggle" checked>
            <span class="slider"></span>
          </label>
          <label for="showAllToggle">Show All Transaction</label>
          <input type="hidden" name="showAll" id="showAllInput" value="1">
        </form>
      </div>

      
      <!-- Reverse Toggle State (Default - 1) -->
      <script>
        document.addEventListener("DOMContentLoaded", function () {
          const toggle = document.getElementById("showAllToggle");
          const hiddenInput = document.getElementById("showAllInput");

          // Set default value to 1 on page load
          hiddenInput.value = toggle.checked ? 1 : 0;

          // Update value on toggle change
          toggle.addEventListener("change", function () {
            hiddenInput.value = this.checked ? 1 : 0;
          });
        });
      </script>


      <!-- <script>
        document.getElementById('showAllToggle').addEventListener('change', function() {
          const val = this.checked ? '1' : '0';
          document.getElementById('showAllInput').value = val;
          console.log("showAll value:", val);
          // document.getElementById('showAllForm').submit();
        });
      </script> -->



      <!-- Clear Button -->
      <div class="aligned-item">
        <label style="opacity: 0;">Clear</label> <!-- invisible label to align height -->
        <button id="clearSorting" class="btn btn-secondary">
          Clear Filters
        </button>
      </div>

    </div>

  </div>

  <div class="navpills-container">

    <ul class="nav nav-pills nav-underline" id="booking-filter-tabs" role="tablist">

      <!-- All -->
      <li class="nav-item" role="presentation">
        <button class="nav-link filter-btn <?php if ($statusTab == 'all' || !$statusTab)
          echo 'active'; ?>" id="status-all-filter" data-filter="all" data-bs-toggle="pill" type="button" role="tab"
          aria-controls="all-tab"
          aria-selected="<?php echo ($statusTab == 'all' || !$statusTab) ? 'true' : 'false'; ?>">
          All <span class="badge">
            
          </span>
        </button>

      </li>

      <!-- Pending -->
      <li class="nav-item" role="presentation">
        <button class="nav-link filter-btn <?php if ($statusTab == 'Pending')
          echo 'active'; ?>" id="status-pending-filter" data-filter="Pending" data-bs-toggle="pill" type="button"
          role="tab" aria-controls="pending-tab"
          aria-selected="<?php echo ($statusTab == 'Pending') ? 'true' : 'false'; ?>">
          Pending <span class="badge">
            
          </span>
        </button>
      </li>

      <!-- Reserved -->
      <li class="nav-item" role="presentation">
        <button class="nav-link filter-btn <?php if ($statusTab == 'Reserved')
          echo 'active'; ?>" id="status-reserved-filter" data-filter="Reserved" data-bs-toggle="pill" type="button"
          role="tab" aria-controls="reserved-tab"
          aria-selected="<?php echo ($statusTab == 'Reserved') ? 'true' : 'false'; ?>">
          Reserved <span class="badge">
            
          </span>
        </button>
      </li>

      <!-- Confirmed -->
      <li class="nav-item" role="presentation">
        <button class="nav-link filter-btn <?php if ($statusTab == 'Confirmed')
          echo 'active'; ?>" id="status-confirmed-filter" data-filter="Confirmed" data-bs-toggle="pill" type="button"
          role="tab" aria-controls="confirmed-tab"
          aria-selected="<?php echo ($statusTab == 'Confirmed') ? 'true' : 'false'; ?>">
          Confirmed <span class="badge">
           
          </span>
        </button>
      </li>

      <!-- Cancelled -->
      <li class="nav-item" role="presentation">
        <button class="nav-link filter-btn <?php if ($statusTab == 'Cancelled')
          echo 'active'; ?>" id="status-cancelled-filter" data-filter="Cancelled" data-bs-toggle="pill" type="button"
          role="tab" aria-controls="cancelled-tab"
          aria-selected="<?php echo ($statusTab == 'Cancelled') ? 'true' : 'false'; ?>">
          Cancelled <span class="badge">
            
          </span>
        </button>
      </li>

    </ul>

  </div>


  <div class="body-content-wrapper">
    <div class="table-wrapper">
      <table id="bookingTable" class="table-clean">
        <thead>
          <tr>
            <th>TRANSACTION NO.</th>
            <th>BRANCH</th>
            <th>FLIGHT DATE</th>
            <th>TOTAL PAX</th>
            <th>PACKAGE PRICE</th>
            <th>TOTAL REQUEST COST</th>
            <th>AMOUNT PAID</th>
            <th>BALANCE</th>
            <th>BOOKING DATE</th>
            <th>STATUS</th>
          </tr>
        </thead>

        <tbody id="statusTableBody">

        </tbody>

      </table>
    </div>

    <div class="table-footer">
      <div class="last-update-wrapper accent-text">
        <span class="header-text">Last updated:</span>
        <span>April 30, 2025 - 10:15 AM</span>
      </div>

      <div class="">
        <button id="prevPage" class="btn-pagination">Previous</button>
        <span id="pageInfo" class="page-info"></span>
        <button id="nextPage" class="btn-pagination">Next</button>
      </div>
    </div>

  </div>

</div>



<!-- Navpills Active State Change -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const filterButtons = document.querySelectorAll('#booking-filter-tabs .filter-btn');

    filterButtons.forEach(button => {
      button.addEventListener('click', () => {
        // Remove active from all
        filterButtons.forEach(btn => btn.classList.remove('active'));
        // Add active to clicked
        button.classList.add('active');

        // TODO: Add your filtering logic here
        // For example: filter your DataTable or fetch filtered data
      });
    });
  });
</script>

<!-- Tabs Based Status Filter Script -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const statusTabBtn = document.getElementById('pills-profile-tab'); // Bootstrap tab trigger button
    const statusTabPane = document.getElementById('pills-profile');    // Tab content

    if (!statusTabBtn || !statusTabPane) return;

    const buttons = document.querySelectorAll("#booking-filter-tabs .filter-btn");
    const tableSelector = '#bookingTable'; // Change if your table ID is different
    const statusColIndex = 9;

    function filterTableByStatus(filterValue) {
      if ($.fn.DataTable.isDataTable(tableSelector)) {
        let searchTerm = filterValue && filterValue.toLowerCase() !== 'all' ? filterValue : '';
        $(tableSelector).DataTable()
          .column(statusColIndex)
          .search(searchTerm, true, false)
          .draw();
      }
    }

    function initStatusFilter() {
      // Read status from PHP or default to 'all'
      const status = "<?php echo isset($_GET['status']) ? $_GET['status'] : 'all'; ?>";

      console.log("Status from URL:", status);

      // Remove all active classes
      buttons.forEach(btn => btn.classList.remove("active"));

      // Find button matching status (case-insensitive)
      const matchedButton = Array.from(buttons).find(btn =>
        btn.getAttribute("data-filter").toLowerCase() === status.toLowerCase()
      );

      if (matchedButton) {
        matchedButton.classList.add("active");
        filterTableByStatus(matchedButton.getAttribute("data-filter"));
      } else {

        // Fallback to 'all'
        const defaultBtn = document.querySelector("#booking-filter-tabs .filter-btn[data-filter='all']");
        if (defaultBtn) {
          defaultBtn.classList.add("active");
          filterTableByStatus(defaultBtn.getAttribute("data-filter"));
        }
      }

    }

    // Add click listeners once
    buttons.forEach(button => {
      button.addEventListener("click", function () {
        buttons.forEach(btn => btn.classList.remove("active"));
        this.classList.add("active");

        const filterValue = this.getAttribute("data-filter");
        filterTableByStatus(filterValue);
      });
    });

    // Run initStatusFilter when tab is shown
    statusTabBtn.addEventListener('shown.bs.tab', initStatusFilter);

    // Also initialize if tab content already active on page load
    if (statusTabPane.classList.contains('active')) {
      initStatusFilter();
    }
  });
</script>



<!-- Table Data Generation -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    loadBookings();
  });

  let tableProduct = null;

  function loadBookings() {
    fetch("../Employee Section/functions/fetchScripts/tableFetch/fetchTransactionTable.php")
      .then(response => {
        if (!response.ok) throw new Error("Network response was not ok");
        return response.json();
      })
      .then(data => {
        const tbody = document.querySelector("#bookingTable tbody");
        tbody.innerHTML = ""; // Clear table rows

        if (!data || data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="10">No available bookings</td></tr>`;
        }

        else {
          data.forEach(row => {
            // Determine the status class based on row.status (case-insensitive)
            let statusClass = 'bg-secondary text-white'; // default
            switch (row.status.toLowerCase()) {
              case 'pending':
                statusClass = 'bg-warning text-dark';
                break;
              case 'confirmed':
                statusClass = 'bg-success text-white';
                break;
              case 'cancelled':
                statusClass = 'bg-danger text-white';
                break;
              case 'reject':
                statusClass = 'bg-secondary text-white';
                break;
            }

            // Format bookingDate to mm.dd.yyyy if not already formatted
            let formattedBookingDate = row.bookingDate;

            try {
              const dateObj = new Date(row.bookingDate);
              if (!isNaN(dateObj)) {
                formattedBookingDate = dateObj.toLocaleDateString('en-US', {
                  month: '2-digit',
                  day: '2-digit',
                  year: 'numeric'
                }).replace(/\//g, '.');
              }
            } catch {
              // fallback keep original
            }

            // Create URL with safe encoding
            const transactionUrl = `emp-transactionInfo.php?id=${encodeURIComponent(row.transactNo)}`;

            tbody.innerHTML += `
                        <tr data-url="${transactionUrl}" style="cursor:pointer;">
                            <td>${row.transactNo}</td>
                            <td>${row.branchName || ''}</td>
                            <td>${row.departureDate || ''}</td>
                            <td class="fw-bold ps-3">${row.totalPax || ''}</td>
                            <td>₱ ${row.packagePrice || '0.00'}</td>
                            <td>₱ ${row.requestTotal || '0.00'}</td>
                            <td>₱ ${row.amountPaid || '0.00'}</td>
                            <td>₱ ${row.balance || '0.00'}</td>
                            <td>${formattedBookingDate}</td>
                            <td><span class="badge rounded-pill ${statusClass} p-2">${row.status}</span></td>
                        </tr>
                    `;

          });
        }


        initDataTable();

        // Transaction Id Based Redirect
        bindRowClickEvents();
      })
      .catch(error => {
        console.error("Error fetching data:", error);
      });
  }

  // Function: With Transaction Id Page Redirect
  function bindRowClickEvents() {
    document.querySelectorAll("tr[data-url]").forEach(row => {
      row.addEventListener("click", () => {
        const transactionUrl = row.getAttribute("data-url");
        const transactionNumber = transactionUrl.split('=')[1];

        console.log("Transaction Number:", transactionNumber);

        // Using jQuery AJAX to set session then redirect
        $.ajax({
          url: '../Agent Section/functions/fetchTransactNo.php',
          type: 'POST',
          data: { transaction_number: transactionNumber },
          success: function (response) {
            console.log("Response:", response);
            window.location.href = transactionUrl;
          },
          error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
          }
        });
      });
    });
  }



  function initDataTable() {
    // Destroy existing DataTable if exists
    if ($.fn.DataTable.isDataTable('#bookingTable')) {
      $('#bookingTable').DataTable().destroy();
    }


    // Initialize DataTable
    tableProduct = $('#bookingTable').DataTable({
      dom: 'rtip',
      language: { emptyTable: "No Transaction Records Available" },
      order: [[2, 'asc']],
      scrollX: false,
      paging: true,
      pageLength: 13,
      autoWidth: false,
      responsive: true,
      columnDefs: [
        { targets: [1, 3, 4, 5, 6, 7], orderable: false },
        { targets: 0, width: '10%' },
        { targets: 1, width: '12%' },
        { targets: 2, width: '8%' },
        { targets: 3, width: '6%' },
        { targets: 4, width: '11%' },
        { targets: 5, width: '11%' },
        { targets: 6, width: '11%' },
        { targets: 7, width: '11%' },
        { targets: 8, width: '11%' },
        { targets: 9, width: '11%' },
      ],
      drawCallback: function () {
        $('#bookingTable tbody tr').css('height', '36px');
      }
    });

    // Apply status filter from URL param
    const statusColIndex = 9;
    const urlParams = new URLSearchParams(window.location.search);
    let initialFilter = urlParams.get('status') || 'all';

    if (initialFilter.toLowerCase() !== 'all') {
      tableProduct.column(statusColIndex).search('^' + initialFilter + '$', true, false).draw();
    } else {
      tableProduct.column(statusColIndex).search('').draw();
    }

    // === Pagination Info Update
    function updatePagination() {
      const info = tableProduct.page.info();
      $('#pageInfo').text(`Page ${info.page + 1} of ${info.pages}`);
      $('#prevPage').prop('disabled', info.page + 1 === 1 || info.pages <= 1);
      $('#nextPage').prop('disabled', info.page + 1 === info.pages || info.pages <= 1);
    }

    tableProduct.on('draw', updatePagination);
    tableProduct.draw();

    // Pagination buttons
    $('#nextPage').on('click', () => tableProduct.page('next').draw('page'));
    $('#prevPage').on('click', () => tableProduct.page('previous').draw('page'));

    // Search
    $('#search').on('keyup', function () {
      tableProduct.search(this.value).draw();
    });

    // Packages dropdown filter
    $('#branch').on('change', function () {
      tableProduct.column(1).search($(this).val() || '').draw();
    });

    // Trigger filtering based on toggle change - Fixed: use showAllToggle
    $('#showAllToggle').on('change', function () {
      tableProduct.draw();

      // Update URL parameter
      const newUrl = new URL(window.location.href);
      if (this.checked) {
        newUrl.searchParams.set('showAll', '1');
      } else {
        newUrl.searchParams.delete('showAll');
      }
      history.replaceState(null, '', newUrl.toString());
    });


    // Custom search filter for flight date column based on toggle
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
      if (settings.nTable.id !== 'bookingTable') {
        return true; // Only apply to #bookingTable
      }

      const showAll = $('#showAllToggle').prop('checked'); // toggle state

      if (!showAll) {
        return true; // If toggle unchecked, show all rows (no date filter)
      }

      const flightDateStr = data[2]; // Flight Date column (index 2)
      if (!flightDateStr || flightDateStr.trim() === '') {
        return false; // Hide empty dates when filtering
      }

      // Parse date assuming YYYY.MM.DD or adjust if different
      // This example assumes flightDateStr is already in YYYY.MM.DD format
      const today = new Date();
      today.setHours(0,0,0,0);

      // Convert flightDateStr to Date
      const parts = flightDateStr.split('.');
      if(parts.length !== 3) return false;
      const flightDate = new Date(`${parts[0]}-${parts[1]}-${parts[2]}`);
      if (isNaN(flightDate)) return false;

      return flightDate >= today;
    });


    function updateStatusTabCountsFromServer() {
      const showCurrentDate = $('#showAllToggle').prop('checked'); // true or false

      console.log('Fetching counts with showCurrentDate:', showCurrentDate);

      fetch('../Employee Section/functions/fetchScripts/tableFetch/getTransactionCounts-Status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ showCurrentDate: showCurrentDate ? 1 : 0 }) // Send as 1 or 0
      })
      .then(response => response.json())
      .then(data => {
        console.log('Counts received:', data);

        // Update badges if they exist
        const badges = {
          all: document.querySelector('#status-all-filter .badge'),
          Pending: document.querySelector('#status-pending-filter .badge'),
          Reserved: document.querySelector('#status-reserved-filter .badge'),
          Confirmed: document.querySelector('#status-confirmed-filter .badge'),
          Cancelled: document.querySelector('#status-cancelled-filter .badge')
        };

        for (const [key, badge] of Object.entries(badges)) {
          if (badge) {
            // Use fallback '0' if data missing
            badge.textContent = data[key] !== undefined ? data[key] : '0';
          }
        }
      })
      .catch(error => {
        console.error('Fetch error:', error);
      });
    }


    function initDataTable() {
      if ($.fn.DataTable.isDataTable('#bookingTable')) {
        $('#bookingTable').DataTable().destroy();
      }

      window.tableProduct = $('#bookingTable').DataTable({
        dom: 'rtip',
        language: { emptyTable: "No Transaction Records Available" },
        order: [[2, 'asc']],
        scrollX: false,
        paging: true,
        pageLength: 13,
        autoWidth: false,
        responsive: true,
        columnDefs: [
          { targets: [1, 3, 4, 5, 6, 7], orderable: false },
          { targets: 0, width: '10%' },
          { targets: 1, width: '12%' },
          { targets: 2, width: '8%' },
          { targets: 3, width: '6%' },
          { targets: 4, width: '11%' },
          { targets: 5, width: '11%' },
          { targets: 6, width: '11%' },
          { targets: 7, width: '11%' },
          { targets: 8, width: '11%' },
          { targets: 9, width: '11%' }
        ],
        drawCallback: function () {
          $('#bookingTable tbody tr').css('height', '36px');
        }
      });

      // Initial draw to apply the filter
      tableProduct.draw();
    }

    $(document).ready(function() {
      initDataTable();

      // Listen for toggle change
      $('#showAllToggle').on('change', function() {
        const val = this.checked ? '1' : '0';
        $('#showAllInput').val(val);
        console.log('showAll value:', val);

        if ($.fn.DataTable.isDataTable('#bookingTable')) {
          $('#bookingTable').DataTable().draw(); // re-filter table rows
        }

        updateStatusTabCountsFromServer(); // update counts after toggle change
      });

      // Initial badge update on page load
      updateStatusTabCountsFromServer();
    });


    // Clear filters
    $('#clearSorting').on('click', function () {
      // Clear filters and search
      $('#search').val('');
      tableProduct.search('').draw();
      $('#branch').val('').trigger('change');
      $('#status').val('').trigger('change');
      tableProduct.order([[2, 'asc']]).columns().search('').draw();

      // Reset toggle (uncheck)
      $('#showAllToggle').prop('checked', false);

      // Run the same function/logic as when toggling back
      if (typeof handleShowAllToggle === 'function') {
        handleShowAllToggle(false); // Pass false to mimic uncheck
      } else {
        $('#showAllToggle').trigger('change'); // Fallback if no separate function
      }

      // Remove "showAll" from URL
      const newUrl = new URL(window.location.href);
      newUrl.searchParams.delete('showAll');
      history.replaceState(null, '', newUrl.toString());
    });




    // Update active tab visually
    $('.navpills-container .nav-link').removeClass('active')
      .filter(`[data-filter="${initialFilter}"]`).addClass('active');
    }




  // // Call this function on page load to initialize counts
  // document.addEventListener('DOMContentLoaded', function() {
  //   console.log('DOM loaded, initializing counts...');
  //   updateStatusTabCountsFromServer();
  // });

  // // Also call when window loads (backup)
  // window.addEventListener('load', function() {
  //   console.log('Window loaded, updating counts...');
  //   setTimeout(() => {
  //     updateStatusTabCountsFromServer();
  //   }, 500);
  // });

  // Test function to manually check badge updates
  // function testBadgeUpdate() {
  //   const testData = {
  //     all: 10,
  //     Pending: 2,
  //     Reserved: 3,
  //     Confirmed: 4,
  //     Cancelled: 1
  //   };
    
  //   console.log('Testing badge updates with test data:', testData);
    
  //   document.querySelector('#status-all-filter .badge').textContent = testData.all;
  //   document.querySelector('#status-pending-filter .badge').textContent = testData.Pending;
  //   document.querySelector('#status-reserved-filter .badge').textContent = testData.Reserved;
  //   document.querySelector('#status-confirmed-filter .badge').textContent = testData.Confirmed;
  //   document.querySelector('#status-cancelled-filter .badge').textContent = testData.Cancelled;
  // }

  // Call this in browser console to test if badge updates work: testBadgeUpdate();
    
  



</script>