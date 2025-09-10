<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee - Transaction</title>

  <?php include '../Employee Section/includes/emp-head.php' ?>

  <!-- Include Flatpickr CSS and JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <!-- Necessary CSS -->
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">

  <!-- Components CSS -->
  <!-- <link rel="stylesheet" href="../Employee Section/assets/css/components/page-layout-tabs.css?v=<?php echo time(); ?>"> -->
  <link rel="stylesheet" href="../Employee Section/assets/css/components/table-header.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/components/table-clean.css?v=<?php echo time(); ?>">



  <!-- Page Specifics CSS -->
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-transaction.css?v=<?php echo time(); ?>">


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
            <h5 class="header-title">Transactions</h5>
          </div>
        </div>

      </div>
      
    </div>

    <script>
      document.getElementById('redirect-btn').addEventListener('click', function () {
        window.location.href = '../Employee Section/emp-dashboard.php'; // Replace with your actual URL
      });
    </script>

    <?php $tab = isset($_GET['tab']) ? $_GET['tab'] : 'status'; ?>

    <!-- Tab Navigation Script -->
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        function getParameterByName(name) {
          const url = window.location.href;
          name = name.replace(/[\[\]]/g, '\\$&');
          const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
          const results = regex.exec(url);
          if (!results) return null;
          if (!results[2]) return '';
          return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        const tabParam = getParameterByName('tab');

        const tabMap = {
          status: {
            tabId: 'pills-profile-tab',
            defaultFilterValue: 'current'
          },
          onDue: {
            tabId: 'pills-home-tab',
            defaultFilterValue: 'all'
          },
          remainBal: {
            tabId: 'pills-remaining-balance-tab',
            defaultFilterValue: 'all'
          }
        };

        let targetTab = 'status'; // Default to STATUS if no ?tab=
        if (tabParam && tabMap[tabParam]) {
          targetTab = tabParam;
        }

        const { tabId, defaultFilterValue } = tabMap[targetTab];
        const tabTriggerEl = document.getElementById(tabId);

        if (tabTriggerEl) {
          const tab = new bootstrap.Tab(tabTriggerEl);
          tab.show();

          // Delay filter button click until after tab is activated
          setTimeout(() => {
            const defaultFilterBtn = document.querySelector(
              `[data-filter="${defaultFilterValue}"]`
            );
            if (defaultFilterBtn) defaultFilterBtn.click();
          }, 300);
        }
      });
    </script>

    <div class="main-content">

      <!-- Main Container Tabs -->
      <div class="main-tabs-wrapper">
        <div class="navs-wrapper">
          <ul class="nav nav-pills" id="pills-tab" role="tablist">

            <!-- Status Tab -->
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill"
                data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                aria-selected="false">STATUS</button>
            </li>

            <!-- On Due Balance Tab -->
            <!-- <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                type="button" role="tab" aria-controls="pills-home" aria-selected="true">ON DUE</button>
            </li> -->

            <!-- View All Tab -->
            <!-- <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-remaining-balance-tab"
                data-bs-toggle="pill" data-bs-target="#pills-remaining-balance"
                type="button" role="tab" aria-controls="pills-remaining-balance" aria-selected="false">
                ALL TRANSACTIONS
              </button>
            </li> -->

          </ul>
        </div>
      </div>

      <div class="tab-main-content" id="pills-tabContent">

        <!-- Status Table -->
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
          tabindex="0">

          <?php
          include '../Employee Section/transactionTable/transactionTable-Status.php';
          ?>

        </div>

        <!-- On Due Table -->
        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

          <?php
          include '../Employee Section/transactionTable/transactionTable-OnDue.php';
          ?>

        </div>

        <!-- With Remaining Balance Table -->
        <!-- <div class="tab-pane fade" id="pills-remaining-balance" role="tabpanel" aria-labelledby="pills-remaining-balance-tab">

          <?php
          // include '../Employee Section/transactionTable/transactionTable-ViewAllTransactions.php';
          ?>

        </div> -->

      </div>

    </div>

  </div>

  <?php include '../Employee Section/includes/emp-scripts.php' ?>

  <!-- <script>
    document.querySelectorAll('[data-filter]').forEach(tabBtn => {
      tabBtn.addEventListener('click', function () {
        const filter = this.getAttribute('data-filter');
        fetchTableData(filter); // always refetch when tab is clicked
      });
    });

  </script> -->

  <!-- Page All Status Default -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Map each tab ID to its "All" filter button ID
      const defaultFilters = {
        "pills-profile": "status-all-filter",           // Status tab
        "pills-home": "onDue-all-filter",               // On Due tab
        "pills-remaining-balance": "viewAll-all-filter" // View All tab
      };

      // When a tab becomes active
      document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(tabBtn => {
        tabBtn.addEventListener('shown.bs.tab', function (event) {
          // Use 'this' to reliably refer to the button that triggered the event
          const btn = this;

          // Get data-bs-target safely
          const target = btn.getAttribute("data-bs-target");

          if (!target) {
            console.warn("No data-bs-target attribute found on tab button", btn);
            return;
          }

          const targetPaneId = target.replace("#", "");
          const filterBtnId = defaultFilters[targetPaneId];

          if (filterBtnId) {
            const filterBtn = document.getElementById(filterBtnId);
            if (filterBtn) {
              filterBtn.classList.add("active");
              filterBtn.click();
            }
          }
        });
      });

      // Run once for the initially active tab on page load
      const activePane = document.querySelector(".tab-pane.show.active");
      if (activePane) {
        const filterBtnId = defaultFilters[activePane.id];
        if (filterBtnId) {
          const filterBtn = document.getElementById(filterBtnId);
          if (filterBtn) {
            filterBtn.classList.add("active");
            filterBtn.click();
          }
        }
      }
    });

  </script>


  <!-- Record Last Tab -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const tabKey = "activeTab"; // storage key

      // Restore the active tab on page load
      const lastTab = localStorage.getItem(tabKey);
      if (lastTab) {
        const tabTriggerEl = document.querySelector(`[data-bs-target="${lastTab}"]`);
        if (tabTriggerEl) {
          const tab = new bootstrap.Tab(tabTriggerEl);
          tab.show();
        }
      }

      // Listen for tab changes and store the active tab
      const tabButtons = document.querySelectorAll('#pills-tab button[data-bs-toggle="pill"]');
      tabButtons.forEach((tabBtn) => {
        tabBtn.addEventListener("shown.bs.tab", function (event) {
          localStorage.setItem(tabKey, event.target.getAttribute("data-bs-target"));
        });
      });
    });
  </script>









  <!-- Row Click Selection
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll("tr[data-url]").forEach(function (row) {
        row.addEventListener("click", function () {
          const transactionNumber = row.getAttribute("data-url").split('=')[1]; // Extract transaction number from the URL

          console.log("Transaction Number: ", transactionNumber); // Debugging line

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