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

        <!-- Left Section: Title and Breadcrumb -->
        <div class="header-left">
          <h1 class="page-title">Dashboard</h1>
          <nav class="breadcrumb">

            <div class="breadcrumb-item-1">
              <a href="#" class="breadcrumb-link">Main Dashboard</a>
            </div>

            <div class="breadcrumb-item-1">
              <!-- <span class="">Transaction</span> -->
            </div>
          </nav>

        </div>

        <!-- Right Section: Export Button -->
        <div class="header-right">

          <div class="tabs-wrapper">

            <div class="dropdown-tab-casing" id="segmentedDropdown">

              <button class="dropdown-toggle" type="button" id="dropdownButton" aria-expanded="false">
                <span class="selected-text">Cebu Pacific</span>
              </button>


              <ul class="dropdown-menu" role="tablist">

                <li class="dropdown-item">
                  <button class="dropdown-link active" id="segmented-preview-tab" data-bs-toggle="tab"
                    data-bs-target="#segmented-preview-pane" type="button" role="tab"
                    aria-controls="segmented-preview-pane" aria-selected="true" data-value="cebu-pacific">
                    Cebu Pacific
                  </button>
                </li>

                <li class="dropdown-item">
                  <button class="dropdown-link" id="segmented-code-tab" data-bs-toggle="tab"
                    data-bs-target="#segmented-code-pane" type="button" role="tab" aria-controls="segmented-code-pane"
                    aria-selected="false" data-value="air-asia">
                    Air Asia
                  </button>
                </li>
              </ul>
              
            </div>
            
          </div>

        </div>

        <!-- Dropdown Script -->
        <script>

          let selectedAirline = 'cebu-pacific';

          class DropdownTabs {
            constructor(containerId, storageKey = 'airlineSelection') {
              this.container = document.getElementById(containerId);
              this.storageKey = storageKey;
              this.dropdownButton = this.container.querySelector('.dropdown-toggle');
              this.dropdownMenu = this.container.querySelector('.dropdown-menu');
              this.dropdownLinks = this.container.querySelectorAll('.dropdown-link');
              this.selectedText = this.container.querySelector('.selected-text');
              this.dropdownIcon = this.container.querySelector('.dropdown-icon'); // This might be null if not in HTML

              this.init();
            }

            init() {
              this.loadInitialState();
              this.addEventListeners();
            }

            addEventListeners() {
              this.dropdownButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleDropdown();
              });

              this.dropdownLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                  e.preventDefault();
                  e.stopPropagation();
                  this.selectItem(link);
                });
              });

              document.addEventListener('click', (e) => {
                if (!this.container.contains(e.target)) {
                  this.closeDropdown();
                }
              });

              this.dropdownButton.addEventListener('keydown', (e) => {
                this.handleKeyboardNavigation(e);
              });

              this.dropdownLinks.forEach(link => {
                link.addEventListener('keydown', (e) => {
                  this.handleKeyboardNavigation(e);
                });
              });
            }

            toggleDropdown() {
              const isOpen = this.dropdownButton.getAttribute('aria-expanded') === 'true';
              if (isOpen) {
                this.closeDropdown();
              } else {
                this.openDropdown();
              }
            }

            openDropdown() {
              this.dropdownButton.setAttribute('aria-expanded', 'true');
              this.dropdownMenu.classList.add('show');
            }

            closeDropdown() {
              this.dropdownButton.setAttribute('aria-expanded', 'false');
              this.dropdownMenu.classList.remove('show');
            }

            selectItem(selectedLink) {
              // Remove active state from all links
              this.dropdownLinks.forEach(link => {
                link.classList.remove('active');
                link.setAttribute('aria-selected', 'false');
              });

              // Add active state to selected link
              selectedLink.classList.add('active');
              selectedLink.setAttribute('aria-selected', 'true');

              // Update the dropdown display text
              this.selectedText.textContent = selectedLink.textContent;

              // Save selection to localStorage
              this.saveSelection(selectedLink.dataset.value);

              // Update the global variable
              selectedAirline = selectedLink.dataset.value;
              console.log(`Global variable 'selectedAirline' updated to: ${selectedAirline}`);

              // Switch tab content - IMPROVED VERSION
              this.switchTabContent(selectedLink.dataset.bsTarget);

              // Close dropdown
              this.closeDropdown();

              // Trigger custom event - MOVED TO AFTER TAB SWITCH
              this.triggerSelectionEvent(selectedLink);
            }

            switchTabContent(targetSelector) {
              if (!targetSelector) {
                console.warn('No target selector provided for tab switching');
                return;
              }

              // Hide all tab panes in the same tab content container
              const tabContentContainer = document.querySelector('#segmentedTabContent');
              if (tabContentContainer) {
                const allPanes = tabContentContainer.querySelectorAll('.tab-pane');
                allPanes.forEach(pane => {
                  pane.classList.remove('show', 'active');
                });

                // Show the target pane
                const targetPane = tabContentContainer.querySelector(targetSelector);
                if (targetPane) {
                  // Add a small delay to ensure smooth transition
                  setTimeout(() => {
                    targetPane.classList.add('show', 'active');
                    console.log(`Switched to tab: ${targetSelector}`);
                  }, 10);
                } else {
                  console.error(`Target pane not found: ${targetSelector}`);
                }
              } else {
                console.error('Tab content container #segmentedTabContent not found');
              }
            }

            handleKeyboardNavigation(e) {
              const isDropdownOpen = this.dropdownButton.getAttribute('aria-expanded') === 'true';

              switch (e.key) {
                case 'Enter':
                case ' ':
                  e.preventDefault();
                  if (e.target === this.dropdownButton) {
                    this.toggleDropdown();
                  } else if (e.target.classList.contains('dropdown-link')) {
                    this.selectItem(e.target);
                  }
                  break;
                case 'Escape':
                  if (isDropdownOpen) {
                    e.preventDefault();
                    this.closeDropdown();
                    this.dropdownButton.focus();
                  }
                  break;
                case 'ArrowDown':
                  if (isDropdownOpen) {
                    e.preventDefault();
                    this.focusNextItem(e.target);
                  } else if (e.target === this.dropdownButton) {
                    e.preventDefault();
                    this.openDropdown();
                  }
                  break;
                case 'ArrowUp':
                  if (isDropdownOpen) {
                    e.preventDefault();
                    this.focusPreviousItem(e.target);
                  }
                  break;
              }
            }

            focusNextItem(currentElement) {
              const items = Array.from(this.dropdownLinks);
              const currentIndex = items.indexOf(currentElement);
              const nextIndex = (currentIndex + 1) % items.length;
              items[nextIndex].focus();
            }

            focusPreviousItem(currentElement) {
              const items = Array.from(this.dropdownLinks);
              const currentIndex = items.indexOf(currentElement);
              const previousIndex = currentIndex === 0 ? items.length - 1 : currentIndex - 1;
              items[previousIndex].focus();
            }

            saveSelection(value) {
              try {
                localStorage.setItem(this.storageKey, value);
              } catch (error) {
                console.warn('Could not save dropdown selection:', error);
              }
            }

            triggerSelectionEvent(selectedLink) {
              if (!selectedLink) return;

              const customEvent = new CustomEvent('dropdownTabChanged', {
                detail: {
                  selectedValue: selectedLink.dataset.value,
                  selectedText: selectedLink.textContent,
                  selectedElement: selectedLink,
                  target: selectedLink.dataset.bsTarget
                },
                bubbles: true // Allow event to bubble up
              });

              // Dispatch on both the container and document for broader reach
              this.container.dispatchEvent(customEvent);
              document.dispatchEvent(customEvent);

              console.log('Custom event dispatched:', customEvent.detail);
            }

            loadInitialState() {
              const savedValue = localStorage.getItem(this.storageKey);
              const defaultLink = this.container.querySelector('.dropdown-link.active');

              if (savedValue) {
                const savedLink = this.container.querySelector(`[data-value="${savedValue}"]`);
                if (savedLink) {
                  this.selectItem(savedLink);
                } else if (defaultLink) {
                  // If saved value doesn't match an existing link, default to the active one
                  this.selectItem(defaultLink);
                }
              } else if (defaultLink) {
                // If no saved value, use the default link from the HTML
                this.selectItem(defaultLink);
              }
            }
          }

          // Initialize when DOM is ready
          document.addEventListener('DOMContentLoaded', function () {
            console.log('Initializing DropdownTabs...');
            const dropdownTabs = new DropdownTabs('segmentedDropdown');

            // Example of how to listen for the custom event
            document.addEventListener('dropdownTabChanged', function (e) {
              console.log('Dropdown selection changed:', e.detail);
              // Add your custom logic here
            });
          });
        </script>

      </div>

      <!-- Page Body -->
      <div class="page-body">

        <!-- Page-body (Header) -->
        <div class="page-body-header">

          <!-- Card 1 -->
          <div class="header-card">

            <div class="header-card-header">

              <div class="header-left">
                <div class="header-title-wrapper">
                  <span>Active Transaction</span>
                </div>
              </div>

              <div class="header-right">
                <div class="header-title-wrapper ">
                  <span class="span-link">View All</span>
                </div>
              </div>

            </div>

            <div class="header-card-body">

              <div class="tab-subcontainer">

                <!-- First Row -->
                <div class="tab-row">
                  <div class="tab">
                    <!-- <div class="tab-icon"><i class="fas fa-exchange-alt"></i></div> -->
                    <div class="tab-info">
                      <div class="tab-count">120</div>
                      <div class="tab-name total-transactions">Total Transactions</div>
                    </div>
                  </div>
                  <div class="tab">
                    <!-- <div class="tab-icon"><i class="fas fa-exchange-alt"></i></div> -->
                    <div class="tab-info">
                      <div class="tab-count">75</div>
                      <div class="tab-name">Confirmed</div>
                    </div>
                  </div>
                </div>


                <!-- Custom Three-Card Row -->
                <div class="tab-row-3">

                  <div class="tab-col-3">
                    <div class="tab">
                      <!-- <div class="tab-icon"><i class="fas fa-exchange-alt"></i></div> -->
                      <div class="tab-info">
                        <div class="tab-count">45</div>
                        <div class="tab-name">Pending</div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-col-3">
                    <div class="tab">
                      <!-- <div class="tab-icon"><i class="fas fa-credit-card"></i></div> -->
                      <div class="tab-info">
                        <div class="tab-count">60</div>
                        <div class="tab-name">Reserved</div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-col-3">
                    <div class="tab">
                      <!-- <div class="tab-icon"><i class="fas fa-receipt"></i></div> -->
                      <div class="tab-info">
                        <div class="tab-count">32</div>
                        <div class="tab-name">Cancelled</div>
                      </div>
                    </div>
                  </div>

                </div>

              </div>

            </div>

          </div>

          <!-- Card 2 -->
          <div class="header-card">

            <div class="header-card-header">

              <div class="header-left">
                <div class="header-title-wrapper">
                  <span>On Due</span>
                </div>
              </div>

              <div class="header-right">
                <div class="header-title-wrapper">
                  <span class="span-link">View All</span>
                </div>
              </div>

            </div>

            <div class="header-card-body">

              <div class="tab-subcontainer">

                <!-- First Row -->
                <div class="tab-row">
                  <div class="tab">
                    <!-- <div class="tab-icon"><i class="fas fa-exchange-alt"></i></div> -->
                    <div class="tab-info">
                      <div class="tab-count">120</div>
                      <div class="tab-name total-transactions">5 Days Before Flight</div>
                    </div>
                  </div>
                  <div class="tab">
                    <!-- <div class="tab-icon"><i class="fas fa-exchange-alt"></i></div> -->
                    <div class="tab-info">
                      <div class="tab-count">75</div>
                      <div class="tab-name">15 Days Before Flight</div>
                    </div>
                  </div>
                </div>

                <!-- Second Row -->
                <div class="tab-row">
                  <div class="tab">
                    <!-- <div class="tab-icon"><i class="fas fa-exchange-alt"></i></div> -->
                    <div class="tab-info">
                      <div class="tab-count">120</div>
                      <div class="tab-name total-transactions">30 Days Before Flight</div>
                    </div>
                  </div>
                  <div class="tab">
                    <!-- <div class="tab-icon"><i class="fas fa-exchange-alt"></i></div> -->
                    <div class="tab-info">
                      <div class="tab-count">75</div>
                      <div class="tab-name">> 30 Days Before Flight</div>
                    </div>
                  </div>
                </div>


              </div>

            </div>

          </div>

          <!-- Card 3 -->
          <div class="header-card">

            <div class="header-card-header">

              <div class="header-left">
                <div class="header-title-wrapper">
                  <span>Total Sales</span>
                </div>
              </div>

              <div class="header-right">
                <div class="header-title-wrapper">

                  <!-- Replaced toggle with grouped buttons -->
                  <div class="mini-toggle-group" id="theme-toggle-group">
                    <button type="button" class="toggle-btn active" data-mode="current">Current</button>
                    <button type="button" class="toggle-btn" data-mode="past">Past</button>
                  </div>

                </div>
              </div>

            </div>

            <div class="header-card-body" id="headerBody">

              <div class="tab-subcontainer-sales">

                <div class="sales-tab current-tab active">

                  <div class="sales-card">

                    <div class="sales-left">

                      <div class="sales-left-info">
                        <!-- Top Section -->
                        <div class="sales-top">
                          <div class="sales-label">Current Month - September</div>

                          <div class="left-info">
                            <div class="sales-value">
                              <span>₱ 486,659,155</span>
                            </div>

                            <div class="sales-convert">
                              <i class="fas fa-exchange-alt convert-icon"></i>
                            </div>
                          </div>

                        </div>

                        <!-- Bottom Section -->
                        <div class="sales-bottom">
                          <div class="sales-label-accent">
                            As of Sept. 08, 2025 – Currency Rates
                          </div>

                        </div>
                      </div>


                       <!-- Change to KRW (Def. PHP) -->
                        <script>
                          const phpToKrwRate = 25; // 1 PHP = 25 KRW (example rate)

                          const convertIcon = document.querySelector('.convert-icon');
                          const salesValue = document.querySelector('.sales-value span');

                          let isPhp = true; // track current currency

                          convertIcon.addEventListener('click', () => {
                            // remove non-numeric characters and commas
                            let numericValue = parseFloat(salesValue.textContent.replace(/[^0-9.-]+/g, ""));

                            if (isPhp) {
                              // Convert PHP to KRW
                              let krwValue = numericValue * phpToKrwRate;
                              salesValue.textContent = `₩ ${krwValue.toLocaleString()}`;
                              isPhp = false;
                            } else {
                              // Convert KRW back to PHP
                              let phpValue = numericValue / phpToKrwRate;
                              salesValue.textContent = `₱ ${phpValue.toLocaleString()}`;
                              isPhp = true;
                            }
                          });
                        </script>

                    </div>

                    <div class="sales-right">
                      <!-- Left: Percentage (1/4) -->
                      <div class="sales-percentage-wrapper">
                        <span class="sales-percentage-plus">+ 20.76% ▲</span>
                      </div>

                      <!-- Right: Additional content (3/4) -->
                      <div class="sales-extra-info">
                        <!-- Add details here later -->
                      </div>
                    </div>


                  </div>

                </div>

                <div class="sales-tab past-tab">
                  <div class="sales-card">

                    <div class="sales-left">
                      <div class="sales-left-info">
                        <!-- Top Section -->
                        <div class="sales-top" id="sales-tab-2">
                          <div class="sales-label">Current Month - September</div>

                          <div class="left-info">
                            <div class="sales-value">
                              <span>₱ 486,659,155</span>
                            </div>
                            <div class="sales-convert">
                              <i class="fas fa-exchange-alt convert-icon"></i>
                            </div>
                          </div>
                        </div>

                        <!-- Bottom Section -->
                        <div class="sales-bottom">
                          <div class="sales-label-accent">
                            As of Sept. 08, 2025 – Currency Rates
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Currency Conversion Script -->
                    <script>
                      (function () {
                        const phpToKrwRate = 25; // Example: 1 PHP = 25 KRW

                        // Scope inside tab 2
                        const tab2 = document.getElementById("sales-tab-2");
                        const convertIcon = tab2.querySelector(".convert-icon");
                        const salesValue = tab2.querySelector(".sales-value span");

                        let isPhp = true; // track current currency

                        convertIcon.addEventListener("click", () => {
                          let numericValue = parseFloat(
                            salesValue.textContent.replace(/[^0-9.-]+/g, "")
                          );

                          if (isPhp) {
                            let krwValue = numericValue * phpToKrwRate;
                            salesValue.textContent = `₩ ${krwValue.toLocaleString()}`;
                            isPhp = false;
                          } else {
                            let phpValue = numericValue / phpToKrwRate;
                            salesValue.textContent = `₱ ${phpValue.toLocaleString()}`;
                            isPhp = true;
                          }
                        });
                      })();
                    </script>

                    <div class="sales-right">
                      <!-- Left: Percentage (1/4) -->
                      <div class="sales-percentage-wrapper">
                        <span class="sales-percentage-negative">- 20.76% ▲</span>
                      </div>

                      <!-- Right: Additional content (3/4) -->
                      <div class="sales-extra-info">
                        <!-- Add details here later -->
                      </div>
                    </div>

                  </div>
                </div>

              </div>

              <script>
                document.addEventListener('DOMContentLoaded', () => {
                  const headerBody = document.getElementById('headerBody');
                  // const headerTitle = document.getElementById('headerTitle');
                  const currentTab = document.querySelector('.current-tab');
                  const pastTab = document.querySelector('.past-tab');
                  const buttons = document.querySelectorAll('.mini-toggle-group .toggle-btn');

                  buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                      // Reset active button
                      buttons.forEach(b => b.classList.remove('active'));
                      btn.classList.add('active');

                      if (btn.dataset.mode === "past") {
                        headerBody.classList.add('toggled');
                        // headerTitle.textContent = 'Past Sales';
                        currentTab.classList.remove('active');
                        pastTab.classList.add('active');
                      } else {
                        headerBody.classList.remove('toggled');
                        // headerTitle.textContent = 'Current';
                        pastTab.classList.remove('active');
                        currentTab.classList.add('active');
                      }
                    });
                  });
                });

              </script>

            </div>
          </div>

          <!-- Card 4 -->
          <div class="header-card">

            <div class="header-card-header">

              <div class="header-left">
                <div class="header-title-wrapper">
                  <span>Currency Rates <span class="sub-text">(as of Sept. 8, 2025)</span></span>
                </div>
              </div>

              <div class="header-right">
                <div class="header-title-wrapper">
                  <span class="span-link">View All</span>
                </div>
              </div>

            </div>

            <div class="header-card-body">

              <div class="tab-subcontainer-currency">

                <!-- Left card -->
                <div class="left-currency-card">

                  <div class="left-main-card">
                    <div class="currency-flag-wrapper">
                      <img src="us-flag.png" alt="" class="currency-flag">
                    </div>

                    <div class="currency-info">
                      <h3>$ 1</h3>
                      <span>US DOLLAR</span>
                    </div>
                  </div>

                </div>

                <!-- Exchange icon -->
                <div class="currency-exchange-icon">
                  <i class="fas fa-exchange-alt"></i>
                </div>

                <!-- Right side stacked currencies -->
                <div class="right-currency-cards">

                  <div class="currency-sub-card">
                    <div class="currency-flag-wrapper">
                      <img src="kr-flag.png" alt="" class="currency-flag">
                    </div>

                    <div class="currency-info-sub">
                      <h3>₩ 1,388</h3>
                      <span>KOREAN WON</span>
                    </div>

                  </div>


                  <div class="currency-sub-card">
                    <div class="currency-flag-wrapper">
                      <img src="kr-flag.png" alt="" class="currency-flag">
                    </div>

                    <div class="currency-info-sub">
                      <h3>₩ 1,388</h3>
                      <span>PHILIPPINE PESO</span>
                    </div>
                  </div>

                </div>

              </div>



            </div>

          </div>

        </div>

        <div class="page-tabs">
          <div class="tab-section">
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

              <li class="nav-item">
                <button class="nav-link" id="fit-tab" data-bs-toggle="tab" data-bs-target="#fit-tab-pane" type="button"
                  role="tab" aria-controls="#fit-tab-pane" aria-selected="false">
                  F.I.T
                </button>
              </li>


            </ul>
          </div>
        </div>


        <!-- Record Tab State -->
        <script>
          document.addEventListener("DOMContentLoaded", function () {
            // Check if a tab is saved
            const activeTab = localStorage.getItem("activeTab");
            if (activeTab) {
              const someTabTriggerEl = document.querySelector(`[data-bs-target="${activeTab}"]`);
              if (someTabTriggerEl) {
                const tab = new bootstrap.Tab(someTabTriggerEl);
                tab.show();
              }
            }

            // Save tab on click
            const tabButtons = document.querySelectorAll('#myTab button[data-bs-toggle="tab"]');
            tabButtons.forEach((button) => {
              button.addEventListener("shown.bs.tab", function (event) {
                const target = event.target.getAttribute("data-bs-target");
                localStorage.setItem("activeTab", target);
              });
            });
          });
        </script>


        <div class="tab-content content-grid" id="myTabContent">

          <!-- First Layer Pane: Flight Seat Tracker -->
          <div class="tab-pane fade show active content-grid-pane" id="home-tab-pane" role="tabpanel"
            aria-labelledby="home-tab">

            <div class="panel flight-seat-panel">

              <!-- Panel Body -->
              <div class="panel-body flight-tabs-body">


                <!-- Second Layer Tab-content -->
                <div class="tab-content tab-content-2" id="segmentedTabContent">

                  <!-- Cebu Pacific Tab Page-->
                  <div class="tab-pane fade show active" id="segmented-preview-pane" role="tabpanel"
                    aria-labelledby="segmented-preview-tab">
                    <div class="table-container">
                      <div id="error-container"></div>
                      <div id="loading" class="loading">Loading flight data...</div>
                      <div id="flight-table"></div>
                    </div>
                  </div>

                  <!-- Air Asia Tab Page-->
                  <div class="tab-pane fade" id="segmented-code-pane" role="tabpanel"
                    aria-labelledby="segmented-code-tab">
                    <p>Code content goes here...</p>
                  </div>

                </div>

              </div>

            </div>
          </div>

          <!-- First Layer Pane: Booking and Requests -->
          <div class="tab-pane fade booking-request-wrapper" id="profile-tab-pane" role="tabpanel">

            <div class="panel">

              <!-- <div class="panel-header">
                Recent Activity
              </div> -->

              <div class="panel-body">

                <div class="first-part-wrapper">

                  <div class="request-wrapper">

                    <div class="wrapper-header">
                      <div class="header-left">
                        <div class="header-title-wrapper">
                          <span>Request</span>
                        </div>
                      </div>

                      <div class="header-right">
                        <div class="header-title-wrapper ">
                          <span class="span-link">View All</span>
                        </div>
                      </div>
                    </div>

                    <div class="request-wrapper-body">

                    </div>
                  </div>

                  <div class="booking-wrapper">

                    <div class="wrapper-header">
                      <div class="header-left">
                        <div class="header-title-wrapper">
                          <span>Booking</span>
                        </div>
                      </div>

                      <div class="header-right">
                        <div class="header-title-wrapper ">
                          <span class="span-link">View All</span>
                        </div>
                      </div>
                    </div>

                    <div class="booking-wrapper-body">

                    </div>

                  </div>

                </div>


                <div class="second-part-wrapper">
                  <div class="confirmed-wrapper">

                    <div class="wrapper-header">
                      <div class="header-left">
                        <div class="header-title-wrapper">
                          <span>Confirmed Transaction</span>
                        </div>
                      </div>

                      <div class="header-right">
                        <div class="header-title-wrapper ">
                          <span class="span-link">View All</span>
                        </div>
                      </div>
                    </div>

                    <div class="request-wrapper-body">

                    </div>
                  </div>

                </div>

              </div>


            </div>

          </div>

          <!-- First Layer Pane: F.I.T -->
          <div class="tab-pane fade" id="fit-tab-pane" role="tabpanel">
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


  <!-- For Page Back Navigation -->
  <script>
    document.getElementById('redirect-btn').addEventListener('click', function () {
      window.location.href = '../Employee Section/emp-dashboard.php';
    });
  </script>


  <!-- For Breadcrumbs -->

  <script>
    // Add click event for breadcrumb navigation
    document.querySelector('.breadcrumb-link').addEventListener('click', function (e) {
      e.preventDefault();
      console.log('Navigate to Dashboard');
      // Add your navigation functionality here
      // Example: window.location.href = '/dashboard';
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