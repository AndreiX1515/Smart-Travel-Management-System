<?php
session_start();
require "../conn.php"; // Move up to the parent directory

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>Employee - Dashboard</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>


  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar copy.css?v=<?php echo time(); ?>">
  <link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">

</head>

<body>

  <!-- Navbar (Always on top) -->
  <div class="navbar">

    <div class="logo-container">
      <div class="logo-content">
        <div class="logo-backdrop">
          <img src="../Assets/Logos/logo-tab.png" alt="Logo" class="sidebar-logo">
        </div>
        <span class="fw-bold">SMART TRAVEL</span>
      </div>
    </div>

    <div class="main-nav-container">

      <!-- <div class="lang-user-wrapper">

        <!-- Language Dropdown 
        <div class="nav-language-dropdown">

          <div class="nav-language-btn">
            <div class="icon"><i class="fas fa-globe"></i></div>
            <div class="label">EN</div>
            <div class="dropdown-icon">▾</div>
          </div>

          <ul class="nav-language-menu">
            <li><a href="#">English</a></li>
            <li><a href="#">한국어 (KR)</a></li>
            <li><a href="#">日本語</a></li>
            <li><a href="#">中文</a></li>
          </ul>

        </div>



        <div class="seperator"></div>

        <!-- User Dropdown 
        <div class="nav-user-dropdown">
          <div class="nav-user-btn">
            <div class="icon">
              <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-info">
              <span class="user-name">Elaine Santoyo</span>
              <span class="user-id">E001</span>
            </div>
            <div class="dropdown-icon">
              <span>▾</span>
            </div>
          </div>

          <ul class="nav-user-menu">
            <li><a href="#">Profile</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Logout</a></li>
          </ul>
        </div>

      </div> -->

    </div>

  </div>

  <!-- Body Content Wrapper -->
  <div class="body-container">

    <!-- Sidebar -->
    <?php include '../Employee Section/includes/emp-sidebar copy.php'; ?>

    <div class="main-content">

      <!-- Page Header -->
      <div class="page-header">

        <div class="page-actions">
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
                <h5 class="header-title">Dashboard</h5>
              </div>
            </div>
          </div>
        </div>

        <!-- <h2 class="page-title">Page Title</h2> -->
      </div>

      <!-- Page Body -->
      <div class="page-body">

        <!-- Page-body (Header) -->
        <div class="page-body-header">

          <div class="header-card">

            <div class="header-card-header">

              <div class="header-left">
                <div class="header-title-wrapper">
                  <span>Active Transaction</span>
                </div>
              </div>

              <div class="header-right">

              </div>

            </div>

            <div class="header-card-body">

              <div class="tab-container">

                <!-- Top Row -->
                <div class="tab">
                  <div class="tab-icon"><i class="fas fa-users"></i></div>
                  <div class="tab-info">
                    <div class="tab-count">120</div>
                    <div class="tab-name">Users</div>
                  </div>
                </div>

                <div class="tab">
                  <div class="tab-icon"><i class="fas fa-chart-line"></i></div>
                  <div class="tab-info">
                    <div class="tab-count">75</div>
                    <div class="tab-name">Sales</div>
                  </div>
                </div>

                <!-- Bottom Row -->
                <div class="tab">
                  <div class="tab-icon"><i class="fas fa-comments"></i></div>
                  <div class="tab-info">
                    <div class="tab-count">45</div>
                    <div class="tab-name">Messages</div>
                  </div>
                </div>

                <div class="tab">
                  <div class="tab-icon"><i class="fas fa-envelope"></i></div>
                  <div class="tab-info">
                    <div class="tab-count">60</div>
                    <div class="tab-name">Emails</div>
                  </div>
                </div>

                <!-- Optional 5th tab -->
                <!-- <div class="tab">
                  <div class="tab-icon"><i class="fas fa-tasks"></i></div>
                  <div class="tab-info">
                    <div class="tab-count">30</div>
                    <div class="tab-name">Tasks</div>
                  </div>
                </div> -->
              </div>
            </div>

          </div>

          <div class="header-card"></div>

          <div class="header-card"></div>

          <div class="header-card"></div>

        </div>

        <div class="page-tabs">

          <ul class="nav custom-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
                Flight Seat Tracker
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">
                Booking and Requests
              </button>
            </li>

            <!-- Uncomment if needed -->
            <!--
          <li class="nav-item">
            <button class="nav-link" id="settings-tab"
                    data-bs-toggle="tab" data-bs-target="#settings-tab-pane"
                    type="button" role="tab"
                    aria-controls="settings-tab-pane"
                    aria-selected="false">
              F.I.T
            </button>
          </li>
          -->
          </ul>
        </div>

        <div class="tab-content content-grid" id="myTabContent">

          <!-- First Layer Pane: Flight Seat Tracker -->
          <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab">

            <div class="panel flight-seat-panel">

              <!-- Panel Header -->
              <div class="panel-header flight-tabs-header">

                <!-- Second Layer nav-tabs -->
                <div class="tabs-wrapper">

                  <ul class="nav nav-pills" id="segmentedTab" role="tablist">
                    <li class="nav-item">
                      <button class="nav-link active" id="segmented-preview-tab" data-bs-toggle="tab"
                        data-bs-target="#segmented-preview-pane" type="button" role="tab"
                        aria-controls="segmented-preview-pane" aria-selected="true">
                        Cebu Pacific
                      </button>
                    </li>
                    <li class="nav-item">
                      <button class="nav-link" id="segmented-code-tab" data-bs-toggle="tab"
                        data-bs-target="#segmented-code-pane" type="button" role="tab"
                        aria-controls="segmented-code-pane" aria-selected="false">
                        Air Asia
                      </button>
                    </li>
                  </ul>
                  
                </div>

                <div class="actions-wrapper">
                  <button class="btn btn-sm btn-primary" hidden>Save</button>
                </div>
              </div>

              <!-- Panel Body -->
              <div class="panel-body flight-tabs-body">

                <!-- Second Layer Tab-content -->
                <div class="tab-content tab-content-2" id="segmentedTabContent">

                  <!-- Cebu Pacific Tab -->
                  <div class="tab-pane fade show active" id="segmented-preview-pane" role="tabpanel"
                    aria-labelledby="segmented-preview-tab">
                    <div class="table-container">
                      <!-- <div id="error-container"></div>
                      <div id="loading" class="loading">Loading flight data...</div> -->

                      <div id="flight-table"></div>
                    </div>
                  </div>

                  <!-- Air Asia Tab -->
                  <div class="tab-pane fade" id="segmented-code-pane" role="tabpanel"
                    aria-labelledby="segmented-code-tab">
                    <p>Code content goes here...</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel">
            <div class="panel">
              <div class="panel-header">
                Recent Activity
              </div>

              <div class="panel-body">
                <!-- Content grows/shrinks here -->
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="settings-tab-pane" role="tabpanel">
            <div class="panel">
              <div class="panel-header">
                Recent Activity
              </div>

              <div class="panel-body">
                <!-- Content grows/shrinks here -->
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>


  <script>
    document.getElementById('redirect-btn').addEventListener('click', function () {
      window.location.href = '../Employee Section/emp-dashboard.php';
    });
  </script>

  <script src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>
  <script>
    class FlightTableManager {
      constructor() {
        this.table = null;
        this.apiEndpoint = '../Employee Section/functions/fetchScripts/fetchFlightSeats.php'; // Main API endpoint
        this.testEndpoint = '../Employee Section/functions/fetchScripts/test-flight-fetch.php'; // Test endpoint
        this.init();
      }

      async init() {
        try {
          // First test the API endpoint
          console.log('Testing API endpoint...');
          await this.testApiEndpoint();

          // If test passes, fetch real data
          const data = await this.fetchFlightData();
          this.setupTable(data);
          document.getElementById('loading').style.display = 'none';
        } catch (error) {
          this.showError('Failed to load flight data: ' + error.message);
          document.getElementById('loading').style.display = 'none';
        }
      }

      async testApiEndpoint() {
        try {
          console.log('Testing endpoint:', this.testEndpoint);

          const response = await fetch(this.testEndpoint, {
            method: 'GET',
            headers: {
              'Accept': 'application/json'
            }
          });

          const responseText = await response.text();
          console.log('Test endpoint response:', responseText);

          if (!response.ok) {
            throw new Error(`Test endpoint failed: ${response.status}`);
          }

          const testData = JSON.parse(responseText);
          console.log('Test endpoint parsed data:', testData);

          if (!testData.success) {
            throw new Error('Test endpoint reports failure: ' + testData.error);
          }

          console.log('✅ API endpoint test successful');
        } catch (error) {
          console.error('❌ API endpoint test failed:', error);
          throw new Error('API endpoint test failed: ' + error.message);
        }
      }

      async fetchFlightData() {
        try {
          console.log('Fetching flight data from:', this.apiEndpoint);

          const response = await fetch(this.apiEndpoint, {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            }
          });

          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          const data = await response.json();

          // Log the fetched data
          console.log('Fetched flight data:', data);
          console.log('Number of records:', data.flights?.length || 0);
          console.log('Agent columns:', data.agentColumns || []);

          return data;
        } catch (error) {
          console.error('Error fetching flight data:', error);
          throw error;
        }
      }

      setupTable(data) {
        const dynamicColumns = [];
        if (data.agentColumns && data.agentColumns.length > 0) {
          data.agentColumns.forEach(agent => {
            dynamicColumns.push({
              title: agent.branchName,

              columns: [
                {
                  title: "A.L",
                  field: `${agent.branchAgentCode}_AL`,
                  width: 45,
                  hozAlign: "center",
                  vertAlign: "middle",
                  headerSort: false,   // 🔹 disable sorting
                  formatter: (cell) => {
                    const value = cell.getValue();
                    const fontWeight = value > 0 ? 'bold' : 'normal';
                    return `<div style="text-align: center; font-size: 11px; font-weight: ${fontWeight};">${value}</div>`;
                  }
                },
                {
                  title: "L.O",
                  field: `${agent.branchAgentCode}_LO`,
                  width: 45,
                  hozAlign: "center",
                  vertAlign: "middle",
                  headerSort: false,   // 🔹 disable sorting
                  formatter: (cell) => {
                    const value = cell.getValue();
                    const fontWeight = value > 0 ? 'bold' : 'normal';
                    return `<div style="text-align: center; font-size: 11px; font-weight: ${fontWeight};">${value}</div>`;
                  }
                }
              ]



            });
          });
        }

        const columns = [
          {
            title: "",
            field: "is_active",
            width: 40,
            hozAlign: "center",
            vertAlign: "middle",
            headerSort: false,
            formatter: (cell, formatterParams, onRendered) => {
              const value = cell.getValue();
              const flightId = cell.getRow().getData().flightId;
              return `<input type="checkbox" class="status-checkbox" 
                      data-id="${flightId}" 
                      data-status="${value}" 
                      ${value == 1 ? 'checked' : ''}>`;
            }
          },



          {
            title: "TEAM INFO",
            columns: [
              {
                title: "TEAM OP",
                field: "TeamOP",
                width: 100,
                hozAlign: "left",
                vertAlign: "middle",
                frozen: true,
                formatter: (cell) => {
                  const rowData = cell.getRow().getData();
                  const colorCode = rowData.colorCode || "transparent";
                  const teamOp = cell.getValue() || "";
                  return `<div class="team-op-cell" style="background-color: ${colorCode}; padding: 2px 4px; font-weight: bold; border-radius: 2px; text-align: center; width: 100%; box-sizing: border-box;">${teamOp}</div>`;
                }
              },
              {
                title: "ORIGIN",
                field: "origin",
                width: 80,
                hozAlign: "center",
                vertAlign: "middle",
                frozen: true,
                headerWordWrap: true,
                formatter: (cell) => {
                  return `<div style="text-align: center; font-weight: 500; font-size: 12px">${cell.getValue()}</div>`;
                }
              }
            ]
          },
          {
            title: "FLIGHT DATE",
            columns: [
              {
                title: "START",
                field: "Start",
                width: 85,
                hozAlign: "center",
                vertAlign: "middle",
                frozen: true,
                formatter: (cell) => {
                  const date = new Date(cell.getValue());
                  const formatted = date.toLocaleDateString("en-CA").replace(/-/g, ".");
                  return `<div style="text-align: center; font-size: 12px;">${formatted}</div>`;
                }
              },
              {
                title: "END",
                field: "End",
                width: 85,
                hozAlign: "center",
                vertAlign: "middle",
                frozen: true,
                formatter: (cell) => {
                  const date = new Date(cell.getValue());
                  const formatted = date.toLocaleDateString("en-CA").replace(/-/g, ".");
                  return `<div style="text-align: center; font-size: 12px;">${formatted}</div>`;
                }
              }
            ]
          },
          {
            title: "SEATS",
            columns: [
              {
                title: "AVAILABLE",
                field: "AvailSeats",
                width: 70,
                hozAlign: "center",
                vertAlign: "middle",
                frozen: true,
                formatter: (cell) => {
                  return `<div style="text-align: center; font-size: 12px; font-weight: normal;">${cell.getValue()}</div>`;
                }
              },
              {
                title: "ADDITIONAL",
                field: "AdditionalSeats",
                width: 70,
                hozAlign: "center",
                vertAlign: "middle",
                frozen: true,
                formatter: (cell) => {
                  return `<div style="text-align: center; font-size: 12px; font-weight: normal;">${cell.getValue()}</div>`;
                }
              }
            ]
          },
          {
            title: "OPTIONS",
            columns: [
              {
                title: "AIR + LAND",
                field: "Air+Land",
                width: 70,
                hozAlign: "center",
                vertAlign: "middle",
                frozen: true,
                formatter: (cell) => {
                  return `<div style="text-align: center; font-weight: normal; font-size: 12px;">${cell.getValue()}</div>`;
                }
              },
              {
                title: "LAND ONLY",
                field: "LandOnly",
                width: 70,
                hozAlign: "center",
                vertAlign: "middle",
                frozen: true,
                formatter: (cell) => {
                  return `<div style="text-align: center; font-weight: normal; font-size: 12px;">${cell.getValue()}</div>`;
                }
              }
            ]
          },
          {
            title: "PRICES",
            columns: [
              {
                title: "WHOLESALE",
                field: "WholesalePrice",
                width: 110,
                hozAlign: "right",
                vertAlign: "middle",
                frozen: true,
                formatter: (cell) => {
                  const value = parseFloat(cell.getValue());
                  const formatted = value.toLocaleString("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                  });
                  return `<div style="text-align: right; font-size: 12px; padding-right: 4px;">₱ ${formatted}</div>`;
                }
              },
              {
                title: "RETAIL",
                field: "RetailPrice",
                width: 110,
                hozAlign: "right",
                vertAlign: "middle",
                frozen: true,
                formatter: (cell) => {
                  const value = parseFloat(cell.getValue());
                  const formatted = value.toLocaleString("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                  });
                  return `<div style="text-align: right; font-size: 12px; padding-right: 4px;">₱ ${formatted}</div>`;
                }
              },
              {
                title: "LAND",
                field: "landPrice",
                width: 110,
                hozAlign: "right",
                vertAlign: "middle",
                frozen: true,
                formatter: (cell) => {
                  const value = parseFloat(cell.getValue());
                  const formatted = value.toLocaleString("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                  });
                  return `<div style="text-align: right; font-size: 12px; padding-right: 4px;">₱ ${formatted}</div>`;
                }
              }
            ]
          },
          ...dynamicColumns
        ];

        // Initialize Tabulator with optimized settings
        // Initialize Tabulator with optimized settings
        this.table = new Tabulator("#flight-table", {
          data: data.flights || [],

          // Optimized row formatter - minimal inline styling
          rowFormatter: function (row) {
            const data = row.getData();
            const element = row.getElement();

            // Only handle special cases that can't be done with CSS
            if (data.col === "blue") {
              element.style.backgroundColor = "#eaf4ea";
            }
            // Remove manual alternating - let CSS handle this with :nth-child
          },

          // Table configuration
          columns: columns,
          layout: "fitColumns",
          height: "100%",

          // Interaction settings
          responsiveLayout: false,
          pagination: false,
          movableColumns: false,
          resizableRows: false,
          resizableColumns: false,
          selectable: false,
          tooltips: false,
          headerSort: true,

          // Layout settings
          columnHeaderVertAlign: "middle",
          cellVertAlign: "middle",
          rowHeight: 35, // Keep your original row height

          // Performance settings
          virtualDom: true,
          virtualDomBuffer: 200,
          renderVertical: "virtual",

          // Initial sorting
          initialSort: [{ column: "Start", dir: "asc" }],

          // Simplified event handlers
          rowClick: function (e, row) {
            // Clear previous selections
            document.querySelectorAll('.tabulator-row').forEach(r => {
              r.style.backgroundColor = '';
              r.classList.remove('selected');
            });

            // Highlight clicked row
            const element = row.getElement();
            element.style.backgroundColor = "#d1ecf1";
            element.classList.add('selected');
          }

          // Note: Removed rowMouseOver and rowMouseOut - CSS :hover is more efficient
          // The CSS handles hover states automatically without JavaScript overhead
        });




        // Handle checkbox changes
        this.table.on("cellClick", (e, cell) => {
          if (cell.getField() === "is_active") {
            const checkbox = e.target;
            if (checkbox.type === "checkbox") {
              const flightId = checkbox.dataset.id;
              const newStatus = checkbox.checked ? 1 : 0;
              this.updateFlightStatus(flightId, newStatus, checkbox);
            }
          }
        });

        // Log table setup completion
        console.log('Table setup completed with', data.flights?.length || 0, 'rows');
      }

      async updateFlightStatus(flightId, status, checkbox) {
        try {
          console.log(`Updating flight ${flightId} status to:`, status);

          const response = await fetch('api/update-flight-status.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              flightId: flightId,
              status: status
            })
          });

          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          const result = await response.json();
          console.log('Status update result:', result);

          if (!result.success) {
            // Revert checkbox if update failed
            checkbox.checked = !checkbox.checked;
            this.showError('Failed to update flight status: ' + result.message);
          }
        } catch (error) {
          console.error('Error updating flight status:', error);
          // Revert checkbox if update failed
          checkbox.checked = !checkbox.checked;
          this.showError('Failed to update flight status: ' + error.message);
        }
      }

      showError(message) {
        const errorContainer = document.getElementById('error-container');
        errorContainer.innerHTML = `<div class="error">${message}</div>`;
        setTimeout(() => {
          errorContainer.innerHTML = '';
        }, 5000);
      }

      // Method to refresh table data
      async refreshData() {
        try {
          document.getElementById('loading').style.display = 'block';
          const data = await this.fetchFlightData();
          this.table.replaceData(data.flights || []);
          document.getElementById('loading').style.display = 'none';
          console.log('Table data refreshed');
        } catch (error) {
          this.showError('Failed to refresh data: ' + error.message);
          document.getElementById('loading').style.display = 'none';
        }
      }
    }

    // Initialize the flight table manager when the page loads
    document.addEventListener('DOMContentLoaded', () => {
      window.flightTableManager = new FlightTableManager();
    });

    // Example function to refresh data (can be called from elsewhere)
    function refreshFlightData() {
      if (window.flightTableManager) {
        window.flightTableManager.refreshData();
      }
    }
  </script>

</body>


</html>