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
    <ul class="nav nav-pills nav-underline" id="ondue-filter-tabs" role="tablist">

      <!-- All On Due -->
      <li class="nav-item" role="presentation">
        <button id="onDue-all-filter" class="nav-link <?php if (empty($onDueTab) || strtolower($onDueTab) == 'all')
          echo 'active'; ?>" data-filter="all" type="button">
          All
          <span class="badge">
          </span>
        </button>
      </li>

      <!-- 5 Days -->
      <li class="nav-item" role="presentation">
        <button id="onDue-5d-filter" class="nav-link <?php if ($onDueTab == '5days')
          echo 'active'; ?>" data-filter="5days" type="button">
          5 Days
          <span class="badge">
          </span>
        </button>
      </li>

      <!-- 10 Days -->
      <li class="nav-item" role="presentation">
        <button id="onDue-10d-filter" class="nav-link <?php if ($onDueTab == '10days')
          echo 'active'; ?>" data-filter="10days" type="button">
          10 Days
          <span class="badge">
          </span>
        </button>
      </li>

      <!-- 20 Days -->
      <li class="nav-item" role="presentation">
        <button id="onDue-20d-filter" class="nav-link <?php if ($onDueTab == '20days')
          echo 'active'; ?>" data-filter="20days" type="button">
          20 Days
          <span class="badge">
          </span>
        </button>
      </li>

      <!-- > 30 Days -->
      <li class="nav-item" role="presentation">
        <button id="onDue-30d-filter" class="nav-link <?php if ($onDueTab == '30daysplus')
          echo 'active'; ?>" data-filter="30daysplus" type="button">
          > 30 Days
          <span class="badge">
          </span>
        </button>
      </li>

    </ul>
  </div>

  <div class="body-content-wrapper">
    <div class="table-wrapper">
      <table id="bookingTableOnDue" class="table-clean">
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

        <tbody id="onDueTableBody">

        </tbody>

      </table>
    </div>

    <div class="table-footer">
      <div class="last-update-wrapper accent-text">
        <span class="header-text">Last updated:</span>
        <span>April 30, 2025 - 10:15 AM</span>
      </div>

      <div class="">
        <button id="onDuePrevPage" class="btn-pagination">Previous</button>
        <span id="onDuePageInfo" class="page-info"></span>
        <button id="onDueNextPage" class="btn-pagination">Next</button>
      </div>
    </div>

  </div>

</div>


<!-- OnDue Table Data Generation -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    loadOnDueTransactions();
  });

  let onDueDataTable = null;

  function loadOnDueTransactions() {
    fetch("../Employee Section/functions/fetchScripts/tableFetch/fetchTransactionTable.php")
      .then(response => {
        if (!response.ok) throw new Error("Network response was not ok");
        return response.json();
      })
      .then(data => {
        const tbody = document.querySelector("#bookingTableOnDue tbody");
        tbody.innerHTML = ""; // Clear table rows

        if (!data || data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="10">No available transactions on due</td></tr>`;
        } else {
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

        initOnDueDataTable();
        bindOnDueRowClickEvents();
      })
      .catch(error => {
        console.error("Error fetching onDue data:", error);
      });
  }

  // Function: OnDue Transaction Id Based Redirect
  function bindOnDueRowClickEvents() {
    document.querySelectorAll("tr[data-url]").forEach(row => {
      row.addEventListener("click", () => {
        const transactionUrl = row.getAttribute("data-url");
        const transactionNumber = transactionUrl.split('=')[1];

        console.log("OnDue Transaction Number:", transactionNumber);

        // Using jQuery AJAX to set session then redirect
        $.ajax({
          url: '../Agent Section/functions/fetchTransactNo.php',
          type: 'POST',
          data: { transaction_number: transactionNumber },
          success: function (response) {
            console.log("OnDue Response:", response);
            window.location.href = transactionUrl;
          },
          error: function (xhr, status, error) {
            console.error("OnDue AJAX Error:", status, error);
          }
        });
      });
    });
  }

  function initOnDueDataTable() {
    // Destroy existing DataTable if exists
    if ($.fn.DataTable.isDataTable('#bookingTableOnDue')) {
      $('#bookingTableOnDue').DataTable().destroy();
    }

    // Initialize DataTable
    onDueDataTable = $('#bookingTableOnDue').DataTable({
      dom: 'rtip',
      language: { emptyTable: "No OnDue Transaction Records Available" },
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
        $('#bookingTableOnDue tbody tr').css('height', '36px');
      }
    });

    // Apply status filter from URL param
    const statusColIndex = 9;
    const urlParams = new URLSearchParams(window.location.search);
    let initialFilter = urlParams.get('status') || 'all';

    if (initialFilter.toLowerCase() !== 'all') {
      onDueDataTable.column(statusColIndex).search('^' + initialFilter + '$', true, false).draw();
    } else {
      onDueDataTable.column(statusColIndex).search('').draw();
    }

    setupOnDuePagination();
    setupOnDueFilters();
    setupOnDueDateFilter();

    // Update active tab visually
    $('.navpills-container .nav-link').removeClass('active')
      .filter(`[data-filter="${initialFilter}"]`).addClass('active');
  }

  function setupOnDuePagination() {
    // === Pagination Info Update
    function updateOnDuePagination() {
      const info = onDueDataTable.page.info();
      $('#onDuePageInfo').text(`Page ${info.page + 1} of ${info.pages}`);
      $('#onDuePrevPage').prop('disabled', info.page + 1 === 1 || info.pages <= 1);
      $('#onDueNextPage').prop('disabled', info.page + 1 === info.pages || info.pages <= 1);
    }

    onDueDataTable.on('draw', updateOnDuePagination);
    onDueDataTable.draw();

    // Pagination buttons
    $('#onDueNextPage').on('click', () => onDueDataTable.page('next').draw('page'));
    $('#onDuePrevPage').on('click', () => onDueDataTable.page('previous').draw('page'));
  }

  function setupOnDueFilters() {
    // Search
    $('#onDueSearch').on('keyup', function () {
      onDueDataTable.search(this.value).draw();
    });

    // Branch dropdown filter
    $('#onDueBranch').on('change', function () {
      onDueDataTable.column(1).search($(this).val() || '').draw();
    });

    // Clear filters
    $('#onDueClearSorting').on('click', function () {
      // Clear filters and search
      $('#onDueSearch').val('');
      onDueDataTable.search('').draw();
      $('#onDueBranch').val('').trigger('change');
      onDueDataTable.order([[2, 'asc']]).columns().search('').draw();

      // Reset toggle (uncheck)
      $('#onDueShowAllToggle').prop('checked', false);
      $('#onDueShowAllToggle').trigger('change');

      // Remove "showAll" from URL
      const newUrl = new URL(window.location.href);
      newUrl.searchParams.delete('showAll');
      history.replaceState(null, '', newUrl.toString());
    });
  }

  function setupOnDueDateFilter() {
    // Trigger filtering based on toggle change
    $('#onDueShowAllToggle').on('change', function () {
      onDueDataTable.draw();

      // Update URL parameter
      const newUrl = new URL(window.location.href);
      if (this.checked) {
        newUrl.searchParams.set('showAll', '1');
      } else {
        newUrl.searchParams.delete('showAll');
      }
      history.replaceState(null, '', newUrl.toString());

      updateOnDueStatusTabCounts();
    });

    // Custom search filter for flight date column based on toggle
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
      if (settings.nTable.id !== 'bookingTableOnDue') {
        return true; // Only apply to #bookingTableOnDue
      }

      const showAll = $('#onDueShowAllToggle').prop('checked');

      if (!showAll) {
        return true; // If toggle unchecked, show all rows (no date filter)
      }

      const flightDateStr = data[2]; // Flight Date column (index 2)
      if (!flightDateStr || flightDateStr.trim() === '') {
        return false; // Hide empty dates when filtering
      }

      // Parse date assuming YYYY.MM.DD format
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      // Convert flightDateStr to Date
      const parts = flightDateStr.split('.');
      if (parts.length !== 3) return false;
      const flightDate = new Date(`${parts[0]}-${parts[1]}-${parts[2]}`);
      if (isNaN(flightDate)) return false;

      return flightDate >= today;
    });
  }

  function updateOnDueStatusTabCounts() {
    const showCurrentDate = $('#onDueShowAllToggle').prop('checked');

    console.log('Fetching onDue counts with showCurrentDate:', showCurrentDate);

    fetch('../Employee Section/functions/fetchScripts/tableFetch/getTransactionCounts-OnDue.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ showCurrentDate: showCurrentDate ? 1 : 0 })
    })
      .then(response => response.json())
      .then(data => {
        console.log('OnDue counts received:', data);

        // Update badges if they exist
        const badges = {
          pastDue: document.querySelector('#onDue-all-filter .badge'),
          fiveDays: document.querySelector('#onDue-5d-filter .badge'),
          tenDays: document.querySelector('#onDue-10d-filter .badge'),
          twentyDays: document.querySelector('#onDue-20d-filter .badge'),
          thirtyDays: document.querySelector('#onDue-30d-filter .badge')
        };

        for (const [key, badge] of Object.entries(badges)) {
          if (badge) {
            badge.textContent = data[key] !== undefined ? data[key] : '0';
          }
        }
      })
      .catch(error => {
        console.error('OnDue fetch error:', error);
      });
  }

  // Initialize everything when document is ready
  $(document).ready(function () {
    // Initial badge update on page load
    updateOnDueStatusTabCounts();
  });

</script>