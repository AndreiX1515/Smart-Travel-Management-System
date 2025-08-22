<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee - Transaction</title>
  <?php include '../Employee Section/includes/emp-head.php' ?>

  <!-- Necessary CSS -->
  <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">

  <!-- Components CSS -->
  <!-- <link rel="stylesheet" href="../Employee Section/assets/css/components/page-layout-tabs.css?v=<?php echo time(); ?>"> -->

  <link rel="stylesheet" href="../Employee Section/assets/css/components/table-header.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Employee Section/assets/css/components/table-clean.css?v=<?php echo time(); ?>">


  <!-- Page Specifics -->
  <link rel="stylesheet"
    href="../Employee Section/assets/css/emp-transactionVisaRequirements.css?v=<?php echo time(); ?>">


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
            <h5 class="header-title">Visa Requirements</h5>
          </div>
        </div>

      </div>
    </div>

    <div class="main-content">

      <div class="table-wrapper">

        <div class="table-header">

          <!-- Search -->
          <div class="search-wrapper">
            <label for="search">Search: </label>
            <div class="search-input-wrapper">
              <input type="text" id="search" placeholder="Search here..">
            </div>
          </div>

          <div class="second-header-wrapper">

            <!-- Branch Selection -->
            <!-- <div class="sorting-wrapper aligned-item">
              <label for="branch">Select Branch</label>
              <div class="select-wrapper">
                <select id="branch">
                  <option value="" selected>All Branches</option>
                  <?php
                  $sql1 = "SELECT branchId, branchName FROM branch ORDER BY branchName ASC";
                  $res1 = $conn->query($sql1);
                  if ($res1->num_rows > 0) {
                    while ($row = $res1->fetch_assoc()) {
                      echo "<option value='" . htmlspecialchars($row['branchName'], ENT_QUOTES) . "'>" . htmlspecialchars($row['branchName']) . "</option>";
                    }
                  } else {
                    echo "<option value=''>No branches available</option>";
                  }
                  ?>
                </select>
              </div>
            </div> -->

            <!-- Clear Button -->
            <div class="aligned-item">
              <label style="opacity: 0;">Clear</label> <!-- invisible label to align height -->
              <button id="clearSorting" class="btn btn-secondary">
                Clear Filters
              </button>
            </div>

          </div>

        </div>


        <div class="table-container">

          <?php
          $sql = "SELECT transactNo, guestId, fName, lName, mName, emailAdd, contactNo, nationality
        FROM guest
        ORDER BY transactNo DESC, guestId ASC";
          $result = $conn->query($sql);

          $currentTransact = null;
          $cardCounter = 0;

          echo '<div class="transaction-list">';

          // Check if there are any results
          if ($result->num_rows === 0) {
            // No data state
            echo '<div class="no-data-container">
            <div class="no-data-content">
                <div class="no-data-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        <path d="M12 11h4"></path>
                        <path d="M12 16h4"></path>
                        <path d="M8 11h.01"></path>
                        <path d="M8 16h.01"></path>
                    </svg>
                </div>
                <h3>No Guest Records Found</h3>
                <p>There are currently no guest transactions to display.</p>
            </div>
          </div>';
          } else {
            // Process results as usual
            while ($row = $result->fetch_assoc()) {
              // New transaction header
              if ($currentTransact !== $row['transactNo']) {
                // Close previous container if any
                if ($currentTransact !== null) {
                  echo "</div>"; // close .guest-list
                  echo "</div>"; // close .transaction-container
                }

                $currentTransact = $row['transactNo'];
                $cardCounter = 0;

                echo "<div class='transaction-container' data-transact='{$row['transactNo']}'>
                    <div class='transaction-header'>
                        <div class='header-child bg-primary'>
                            <h5>{$row['transactNo']}</h5>
                        </div>
                    </div>
                    <div class='guest-list'>";
              }

              // Guest card
              $cardCounter++;

              // Handle empty middle name
              $middleName = !empty($row['mName']) ? " " . $row['mName'] : "";
              $fullName = trim($row['fName'] . $middleName . " " . $row['lName']);

              // Handle empty fields gracefully
              $email = !empty($row['emailAdd']) ? $row['emailAdd'] : 'Not provided';
              $contact = !empty($row['contactNo']) ? $row['contactNo'] : 'Not provided';
              $nationality = !empty($row['nationality']) ? $row['nationality'] : 'Not provided';

              echo "<div class='guest-card guest-row'
                data-bs-toggle='offcanvas'
                data-bs-target='#guestOffcanvas'
                data-guest-id='{$row['guestId']}'>
                
                <div class='guest-pic-wrapper'>
                    <div class='guest-photo'>
                        <!-- This is where the passport photo will be displayed -->
                        <!-- For now, showing initials as default state -->
                        <div class='guest-avatar-default'>
                            " . strtoupper(substr($row['fName'], 0, 1)) . strtoupper(substr($row['lName'], 0, 1)) . "
                        </div>
                    </div>
                </div>
                
                <div class='guest-info'>
                    <div class='guest-info-name-wrapper'>
                        <strong class='guest-info-name'>{$fullName}</strong>
                    </div>
                    
                    <div class='guest-info-details'>
                        <div class='guest-info-email'>
                            <span>Email:</span> {$email}
                        </div>
                        <div class='guest-info-contact'>
                            <span>Contact:</span> {$contact}
                        </div>
                        <div class='guest-info-nationality'>
                            <span>Nationality:</span> {$nationality}
                        </div>
                    </div>
                </div>
              </div>";
            }

            // Close last container
            if ($currentTransact !== null) {
              echo "</div>"; // close .guest-list
              echo "</div>"; // close .transaction-container
            }
          }

          echo "</div>"; // close .transaction-list
          ?>


        </div>




      </div>
    </div>

  </div>


  <div class="offcanvas offcanvas-end guest-offcanvas" tabindex="-1" id="guestOffcanvas" aria-labelledby="guestOffcanvasLabel">

  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="guestOffcanvasLabel">
      <span id="offcanvasGuestName">Guest Details</span>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    
    <!-- Hidden Guest ID -->
    <p style="display: none;">
      <strong>Guest ID:</strong> <span id="offcanvasGuestId"></span>
    </p>
  </div>
  
  <div class="offcanvas-body">
    <div class="guest-info-section">
      <!-- Left: Passport Picture -->
      <div class="guest-photo">
        <img id="offcanvasGuestPhoto" src="" alt="Guest Photo" style="display: none;">
        <div class="guest-photo-placeholder" id="offcanvasPhotoPlaceholder">
          <span id="offcanvasGuestInitials"></span>
        </div>
      </div>
      
      <!-- Right: Guest Info -->
      <div class="guest-details">
        <div class="detail-row">
          <label>Email Address:</label>
          <span id="offcanvasGuestEmail">Not provided</span>
        </div>
        
        <div class="detail-row">
          <label>Contact Number:</label>
          <span id="offcanvasGuestContact">Not provided</span>
        </div>
        
        <!-- <div class="detail-row">
          <label>Nationality</label>
          <span id="offcanvasGuestNationality">Not provided</span>
        </div> -->
        
        <div class="detail-row">
          <label>Departure Date:</label>
          <span id="offcanvasDeparture">Not provided</span>
        </div>
      </div>
    </div>
    
    <div class="files-section">
      <div class="files-header">
        <div class="files-header-name">
          <h6>Documents</h6>
        </div>
        
        <div class="files-header-add">
          <button type="button" data-bs-toggle="modal" data-bs-target="#addAttachmentModal">
            <i class="fas fa-plus"></i>
            Add Document
          </button>
        </div>
      </div>
      
      <div class="files-body" id="offcanvasFiles">
        <!-- Default no files state -->
        <div class="no-files" id="noFilesState">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <p>No documents uploaded yet</p>
        </div>
        
        <!-- Files will be dynamically populated here -->
      </div>
    </div>
  </div>
</div>

<!-- JavaScript for handling photo display -->
<script>
// Function to handle guest photo display
function updateGuestPhoto(photoSrc, guestName) {
  const photoImg = document.getElementById('offcanvasGuestPhoto');
  const placeholder = document.getElementById('offcanvasPhotoPlaceholder');
  const initials = document.getElementById('offcanvasGuestInitials');
  
  if (photoSrc && photoSrc.trim() !== '') {
    // Show actual photo
    photoImg.src = photoSrc;
    photoImg.style.display = 'block';
    placeholder.style.display = 'none';
    
    // Handle image load error
    photoImg.onerror = function() {
      photoImg.style.display = 'none';
      placeholder.style.display = 'flex';
      // Set initials from guest name
      const names = guestName.split(' ');
      const guestInitials = names.length >= 2 
        ? names[0].charAt(0) + names[names.length - 1].charAt(0) 
        : names[0].charAt(0) + names[0].charAt(1);
      initials.textContent = guestInitials.toUpperCase();
    };
  } else {
    // Show placeholder with initials
    photoImg.style.display = 'none';
    placeholder.style.display = 'flex';
    const names = guestName.split(' ');
    const guestInitials = names.length >= 2 
      ? names[0].charAt(0) + names[names.length - 1].charAt(0) 
      : names[0].charAt(0) + names[0].charAt(1);
    initials.textContent = guestInitials.toUpperCase();
  }
}

// Function to populate guest details (call this when opening offcanvas)
function populateGuestDetails(guestData) {
  // Update name and title
  document.getElementById('offcanvasGuestName').textContent = guestData.fullName;
  document.getElementById('offcanvasGuestId').textContent = guestData.guestId;
  
  // Update details
  document.getElementById('offcanvasGuestEmail').textContent = guestData.email || 'Not provided';
  document.getElementById('offcanvasGuestContact').textContent = guestData.contact || 'Not provided';
  document.getElementById('offcanvasGuestNationality').textContent = guestData.nationality || 'Not provided';
  document.getElementById('offcanvasDeparture').textContent = guestData.departure || 'Not provided';
  
  // Update photo
  updateGuestPhoto(guestData.photoSrc, guestData.fullName);
}

// Function to handle files display
function updateFilesSection(files) {
  const filesBody = document.getElementById('offcanvasFiles');
  const noFilesState = document.getElementById('noFilesState');
  
  if (!files || files.length === 0) {
    noFilesState.style.display = 'flex';
    // Clear any existing file cards
    const existingCards = filesBody.querySelectorAll('.file-card');
    existingCards.forEach(card => card.remove());
  } else {
    noFilesState.style.display = 'none';
    // Populate with files (implement based on your file structure)
    // This is where you'd create file-card elements
  }
}
</script>

<!-- Guest ID to Offcanvas Data Fetch -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const guestOffcanvasEl = document.getElementById('guestOffcanvas');
      const guestOffcanvas = new bootstrap.Offcanvas(guestOffcanvasEl);

      // Async function to load guest info
      async function loadGuestInfo(guestId) {
        // Reset guest info
        document.getElementById("offcanvasGuestId").textContent = "Loading...";
        document.getElementById("offcanvasGuestName").textContent = "";
        document.getElementById("offcanvasGuestEmail").textContent = "";
        document.getElementById("offcanvasGuestContact").textContent = "";
        document.getElementById("offcanvasDeparture").textContent = "";
        document.getElementById("offcanvasFiles").innerHTML = "<em>Loading files...</em>";

        try {
          let res = await fetch("../Employee Section/functions/fetchScripts/getGuestInfo.php?id=" + guestId);
          if (!res.ok) throw new Error("Network response was not ok");

          let data = await res.json();

          // Populate guest info
          document.getElementById("offcanvasGuestId").textContent = data.guestId || guestId;
          document.getElementById("offcanvasGuestName").textContent = data.guestName || "";
          document.getElementById("offcanvasGuestEmail").textContent = data.emailAdd || "";
          document.getElementById("offcanvasGuestContact").textContent = data.contactNo || "";
          document.getElementById("offcanvasDeparture").textContent = data.departureDate || "";

          // Build file sections dynamically
          let filesContainer = document.getElementById("offcanvasFiles");
          filesContainer.innerHTML = ""; // clear old

          if (data.files) {

            // Loop through each file type
            Object.keys(data.files).forEach(fileType => {
              // Skip if this file type has no files
              if (!data.files[fileType] || data.files[fileType].length === 0) return;

              // Create wrapper for the whole file type card
              let section = document.createElement("div");
              section.classList.add("file-card");

              // Card header (file type name)
              let header = document.createElement("div");
              header.classList.add("file-card-header");
              header.innerHTML = `<span class="file-type">${fileType.charAt(0).toUpperCase() + fileType.slice(1)}</span>`;
              section.appendChild(header);

              // Card body (list of files)
              let body = document.createElement("div");
              body.classList.add("file-card-body");
              section.appendChild(body);

              // Counter for unnamed files
              let unnamedCounter = 0;

              // Populate files for this type
              data.files[fileType].forEach(file => {
                let fileItem = document.createElement("div");
                fileItem.classList.add("file-item");

                // Handle docSubType naming
                let labelName = file.docSubType && file.docSubType.trim() !== ""
                  ? file.docSubType
                  : (() => {
                    unnamedCounter++;
                    return unnamedCounter === 1 ? "File" : `File (${unnamedCounter})`;
                  })();

                // Format dateSubmitted if exists
                let dateText = file.dateSubmitted && file.dateSubmitted.trim() !== ""
                  ? `<div class="file-date">Uploaded: ${file.dateSubmitted}</div>`
                  : "";

                fileItem.innerHTML = `
                      <div class="file-info">
                          <label class="fw-semibold">${labelName}</label>
                          ${dateText}
                      </div>

                      <div class="file-actions">
                          <a class="btn btn-info btn-sm text-white" href="../Agent Section/functions/view-file.php?file=${encodeURIComponent(file.filePath)}" target="_blank">View</a>
                          <a class="btn btn-success btn-sm" href="../Employee Section/functions/download.php?file=${encodeURIComponent(file.filePath)}" target="_blank">Download</a>
                      </div>
                  `;

                body.appendChild(fileItem);
              });


              // Append the section to the main container
              filesContainer.appendChild(section);
            });


          }

          // If no files at all
          if (filesContainer.innerHTML.trim() === "") {
            const noFilesDiv = document.createElement("div");
            noFilesDiv.classList.add("no-files"); // optional for styling
            noFilesDiv.textContent = "No files uploaded";
            filesContainer.appendChild(noFilesDiv);
          }


        } catch (err) {
          console.error("Error fetching guest:", err);
          document.getElementById("offcanvasGuestId").textContent = "Error loading guest";
          document.getElementById("offcanvasFiles").innerHTML = "<em>Error loading files</em>";
        }
      }

      // Attach click event to all elements with data-guest-id
      document.querySelectorAll("[data-guest-id]").forEach(el => {
        el.addEventListener("click", function () {
          let guestId = this.getAttribute("data-guest-id");
          loadGuestInfo(guestId);   // load guest data
          guestOffcanvas.show();    // show offcanvas
        });
      });
    });
  </script>


  <!-- Add Attachment Modal in OffCanvas -->
  <div class="modal add-file-modal fade" id="addAttachmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">

      <div class="modal-content modal-lg modal-minimalist">

        <div class="modal-header">
          <h6 class="modal-title">Add Document Attachment</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

          <!-- Hidden Guest ID (for processing later) -->
          <input type="hidden" id="modalGuestId">

          <!-- Select Section -->
          <div class="file-type-wrapper">
            <label class="form-label">Document Type</label>
            <select class="form-select form-select-sm">
              <option value="">Choose...</option>
              <?php

              // Fetch distinct fileType
              $sql = "SELECT DISTINCT fileType FROM visarequirements ORDER BY fileType ASC";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $fileType = htmlspecialchars($row['fileType']);
                  echo "<option value='$fileType'>" . ucfirst($fileType) . "</option>";
                }
              } else {
                echo "<option value=''>No types found</option>";
              }

              $conn->close();
              ?>
            </select>
          </div>


          <!-- File Upload -->
          <div class="file-upload-wrapper">

            <!-- Enhanced Upload Box -->
            <label for="fileInput" class="upload-box" id="dropArea">
              <div class="upload-progress" id="uploadProgress"></div>
              <i class="fas fa-cloud-upload-alt upload-icon"></i>
              <div class="upload-text" id="uploadText">
                Drag & Drop your files here or click to browse
              </div>
              <div class="upload-subtext">
                Supports PDF, DOC, DOCX, XLS, XLSX, Images (JPG, PNG, GIF)
              </div>
              <input type="file" id="fileInput" multiple hidden
                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif">
            </label>

            <!-- Upload Statistics -->
            <div class="upload-stats" id="uploadStats" style="display: none;">
              <div class="upload-stats-left">
                <span id="fileCount">0</span> files selected
              </div>

              <div class="upload-stats-right">
                <div>Total: <span id="totalSize">0 KB</span></div>
                <button onclick="clearAllFiles()"
                  style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 12px; text-decoration: underline;">Clear
                  All</button>
              </div>
            </div>

            <!-- Enhanced File Preview -->
            <div id="filePreview" class="file-preview">
              <div class="empty-state" id="emptyState">
                <i class="fas fa-folder-open"></i>
                <div>No files selected</div>
              </div>
            </div>

          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success btn-sm">Upload</button>
        </div>

      </div>
    </div>
  </div>


  <!-- Page Load Modal Open -->
  <!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Get modal element
      var modalEl = document.getElementById("addAttachmentModal");

      // Initialize Bootstrap modal
      var modal = new bootstrap.Modal(modalEl);

      // Show on page load
      modal.show();
    });
  </script> -->


  <!-- Modal for Offcanvas Script -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const guestIdSpan = document.getElementById("offcanvasGuestId");
      const modalGuestId = document.getElementById("modalGuestId");

      // Attach click listener to Add Attachment button
      document.querySelectorAll(".files-header-add button").forEach(button => {
        button.addEventListener("click", () => {
          const guestId = guestIdSpan.textContent.trim();
          modalGuestId.value = guestId; // set into modal hidden input
        });
      });
    });
  </script>

  <script>
    const modal = document.getElementById("attachmentModal");
    const openBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementById("closeModalBtn");
    const cancelBtn = document.getElementById("cancelBtn");

    openBtn.addEventListener("click", () => {
      modal.classList.remove("hidden");
    });

    closeBtn.addEventListener("click", () => {
      modal.classList.add("hidden");
    });

    cancelBtn.addEventListener("click", () => {
      modal.classList.add("hidden");
    });

    window.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.classList.add("hidden");
      }
    });

  </script>



  <!-- Enhanced JavaScript with AJAX upload -->
  <script>
    const dropArea = document.getElementById("dropArea");
    const fileInput = document.getElementById("fileInput");
    const filePreview = document.getElementById("filePreview");
    const uploadStats = document.getElementById("uploadStats");
    const uploadProgress = document.getElementById("uploadProgress");
    const emptyState = document.getElementById("emptyState");

    let selectedFiles = [];
    let uploadedFiles = [];

    // Enhanced drag and drop with visual feedback
    dropArea.addEventListener("click", () => fileInput.click());

    fileInput.addEventListener("change", (e) => handleFiles(Array.from(e.target.files)));

    dropArea.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropArea.classList.add("dragover");
    });

    dropArea.addEventListener("dragleave", (e) => {
      if (!dropArea.contains(e.relatedTarget)) {
        dropArea.classList.remove("dragover");
      }
    });

    dropArea.addEventListener("drop", (e) => {
      e.preventDefault();
      dropArea.classList.remove("dragover");
      const files = Array.from(e.dataTransfer.files);
      handleFiles(files);
    });

    function handleFiles(files) {
      // Add new files to selected files array
      selectedFiles = [...selectedFiles, ...files];
      updatePreview();
      updateStats();
    }

    function updatePreview() {
      filePreview.innerHTML = "";

      if (selectedFiles.length === 0) {
        filePreview.appendChild(emptyState);
        uploadStats.style.display = "none";
        return;
      }

      uploadStats.style.display = "flex";

      selectedFiles.forEach((file, index) => {
        const fileCard = createFileCard(file, index);
        filePreview.appendChild(fileCard);
      });
    }

    function createFileCard(file, index) {
      // Main card container
      const fileCard = document.createElement("div");
      fileCard.classList.add("file-card", "new-file");
      fileCard.setAttribute("data-index", index);

      // Left section (icon + info)
      const fileCardLeft = document.createElement("div");
      fileCardLeft.classList.add("file-card-left");

      // Icon section
      const iconDiv = document.createElement("div");
      iconDiv.classList.add("file-card-icon");
      const { iconClass, iconType } = getFileIcon(file.type);
      iconDiv.classList.add(iconType);

      const icon = document.createElement("i");
      icon.className = iconClass;
      iconDiv.appendChild(icon);

      // Info section
      const fileInfo = document.createElement("div");
      fileInfo.classList.add("file-card-info");

      const fileName = document.createElement("div");
      fileName.classList.add("file-card-name");
      fileName.textContent = file.name;
      fileName.title = file.name;

      const fileDetails = document.createElement("div");
      fileDetails.classList.add("file-card-details");

      const fileSize = document.createElement("span");
      fileSize.classList.add("file-card-size");
      fileSize.textContent = formatFileSize(file.size);

      const fileType = document.createElement("span");
      fileType.classList.add("file-card-type");
      fileType.textContent = getFileExtension(file.name);

      fileDetails.appendChild(fileSize);
      fileDetails.appendChild(fileType);
      fileInfo.appendChild(fileName);
      fileInfo.appendChild(fileDetails);

      // Right section (status + actions)
      const fileCardRight = document.createElement("div");
      fileCardRight.classList.add("file-card-right");

      const fileStatus = document.createElement("div");
      fileStatus.classList.add("file-card-status");
      fileStatus.textContent = "Ready";

      // Progress bar for individual file
      const progressBar = document.createElement("div");
      progressBar.classList.add("file-progress");
      progressBar.style.display = "none";
      progressBar.innerHTML = '<div class="file-progress-bar"></div>';

      const fileActions = document.createElement("div");
      fileActions.classList.add("file-card-actions");

      // const uploadBtn = document.createElement("button");
      // uploadBtn.classList.add("upload-btn");
      // uploadBtn.innerHTML = '<i class="fas fa-cloud-upload-alt"></i>';
      // uploadBtn.title = "Upload file";
      // uploadBtn.addEventListener("click", () => uploadSingleFile(file, index));

      const deleteBtn = document.createElement("button");
      deleteBtn.classList.add("delete-btn");
      deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
      deleteBtn.title = "Remove file";
      deleteBtn.addEventListener("click", () => removeFile(index));

      // fileActions.appendChild(uploadBtn);
      fileActions.appendChild(deleteBtn);

      fileCardRight.appendChild(fileStatus);
      fileCardRight.appendChild(progressBar);
      fileCardRight.appendChild(fileActions);

      // Assemble the card
      fileCardLeft.appendChild(iconDiv);
      fileCardLeft.appendChild(fileInfo);
      fileCard.appendChild(fileCardLeft);
      fileCard.appendChild(fileCardRight);

      return fileCard;
    }






    // Upload single file
    function uploadSingleFile(file, index) {
      const formData = new FormData();
      formData.append('files', file);

      const fileCard = document.querySelector(`[data-index="${index}"]`);
      const statusElement = fileCard.querySelector('.file-card-status');
      const progressBar = fileCard.querySelector('.file-progress');
      const progressBarInner = fileCard.querySelector('.file-progress-bar');
      const uploadBtn = fileCard.querySelector('.upload-btn');

      // Show progress and update status
      statusElement.textContent = 'Uploading...';
      progressBar.style.display = 'block';
      uploadBtn.disabled = true;

      const xhr = new XMLHttpRequest();

      // Track upload progress
      xhr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
          const percentComplete = (e.loaded / e.total) * 100;
          progressBarInner.style.width = percentComplete + '%';
        }
      });

      xhr.addEventListener('load', () => {
        if (xhr.status === 200) {
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.success && response.uploaded_files.length > 0) {
              statusElement.textContent = 'Uploaded';
              statusElement.classList.add('success');
              uploadBtn.innerHTML = '<i class="fas fa-check"></i>';
              uploadBtn.disabled = true;

              // Store uploaded file info
              uploadedFiles.push(response.uploaded_files[0]);

              // Show success message
              showNotification('File uploaded successfully!', 'success');
            } else {
              throw new Error(response.errors ? response.errors.join(', ') : 'Upload failed');
            }
          } catch (error) {
            handleUploadError(fileCard, error.message);
          }
        } else {
          handleUploadError(fileCard, `Server error: ${xhr.status}`);
        }

        progressBar.style.display = 'none';
      });

      xhr.addEventListener('error', () => {
        handleUploadError(fileCard, 'Network error occurred');
        progressBar.style.display = 'none';
      });

      xhr.open('POST', '../Employee Section/functions/insertScripts/file-upload.php');
      xhr.send(formData);
    }

    // Upload all files
    function uploadAllFiles() {
      if (selectedFiles.length === 0) {
        showNotification('No files to upload', 'warning');
        return;
      }

      selectedFiles.forEach((file, index) => {
        const fileCard = document.querySelector(`[data-index="${index}"]`);
        const statusElement = fileCard.querySelector('.file-card-status');

        // Only upload files that haven't been uploaded yet
        if (statusElement.textContent === 'Ready') {
          uploadSingleFile(file, index);
        }
      });
    }

    function handleUploadError(fileCard, errorMessage) {
      const statusElement = fileCard.querySelector('.file-card-status');
      const uploadBtn = fileCard.querySelector('.upload-btn');

      statusElement.textContent = 'Failed';
      statusElement.classList.add('error');
      uploadBtn.disabled = false;

      showNotification(`Upload failed: ${errorMessage}`, 'error');
    }

    function showNotification(message, type = 'info') {
      // Create notification element
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.innerHTML = `
        <span>${message}</span>
        <button class="notification-close">&times;</button>
    `;

      // Add styles
      notification.style.cssText = `
        position: fixed; top: 20px; right: 20px; z-index: 1000;
        padding: 12px 16px; border-radius: 4px; color: white;
        max-width: 300px; word-wrap: break-word;
        background: ${type === 'success' ? '#059669' : type === 'error' ? '#dc2626' : '#2563eb'};
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        animation: slideIn 0.3s ease-out;
    `;

      // Add click handler for close button
      notification.querySelector('.notification-close').addEventListener('click', () => {
        document.body.removeChild(notification);
      });

      document.body.appendChild(notification);

      // Auto remove after 5 seconds
      setTimeout(() => {
        if (document.body.contains(notification)) {
          document.body.removeChild(notification);
        }
      }, 5000);
    }

    // Add CSS for notifications animation
    const style = document.createElement('style');
    style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .notification-close {
        background: none; border: none; color: inherit;
        font-size: 18px; margin-left: 8px; cursor: pointer;
    }
    .file-progress {
        width: 100%; height: 4px; background: #e5e7eb;
        border-radius: 2px; margin: 4px 0;
    }
    .file-progress-bar {
        height: 100%; background: #3b82f6;
        border-radius: 2px; transition: width 0.3s ease;
        width: 0%;
    }
    .file-card-status.success { color: #059669; }
    .file-card-status.error { color: #dc2626; }
    .upload-btn { background: #3b82f6; color: white; border: none;
        padding: 4px 8px; border-radius: 4px; cursor: pointer; }
    .upload-btn:disabled { background: #9ca3af; cursor: not-allowed; }
    `;
    document.head.appendChild(style);

    // Rest of the existing functions remain the same
    function getFileIcon(fileType) {
      if (fileType.startsWith("image/")) {
        return { iconClass: "fas fa-file-image", iconType: "image" };
      } else if (fileType === "application/pdf") {
        return { iconClass: "fas fa-file-pdf", iconType: "pdf" };
      } else if (fileType.includes("word") || fileType.includes("document")) {
        return { iconClass: "fas fa-file-word", iconType: "word" };
      } else if (fileType.includes("sheet") || fileType.includes("excel")) {
        return { iconClass: "fas fa-file-excel", iconType: "excel" };
      } else {
        return { iconClass: "fas fa-file", iconType: "default" };
      }
    }

    function getFileExtension(filename) {
      return filename.split('.').pop().toUpperCase() || 'FILE';
    }

    function formatFileSize(bytes) {
      if (bytes === 0) return '0 B';
      const k = 1024;
      const sizes = ['B', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function removeFile(index) {
      selectedFiles.splice(index, 1);
      updatePreview();
      updateStats();
    }

    function clearAllFiles() {
      selectedFiles = [];
      uploadedFiles = [];
      fileInput.value = '';
      updatePreview();
      updateStats();
    }

    function updateStats() {
      const fileCount = document.getElementById("fileCount");
      const totalSize = document.getElementById("totalSize");

      if (fileCount) fileCount.textContent = selectedFiles.length;
      if (totalSize) {
        const total = selectedFiles.reduce((sum, file) => sum + file.size, 0);
        totalSize.textContent = formatFileSize(total);
      }
    }

    function previewFile(file) {
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          const modal = document.createElement('div');
          modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.8); display: flex; align-items: center;
                justify-content: center; z-index: 1000; cursor: pointer;
            `;
          modal.innerHTML = `<img src="${e.target.result}" style="max-width: 90%; max-height: 90%; border-radius: 8px;">`;

          modal.addEventListener('click', () => document.body.removeChild(modal));
          document.body.appendChild(modal);
        };
        reader.readAsDataURL(file);
      } else {
        alert(`Preview not available for ${file.type}. File: ${file.name}`);
      }
    }

    // Add upload all button functionality
    // function addUploadAllButton() {
    //   const uploadAllBtn = document.createElement('button');
    //   uploadAllBtn.id = 'uploadAllBtn';
    //   uploadAllBtn.textContent = 'Upload All Files';
    //   uploadAllBtn.style.cssText = `
    //     background: #059669; color: white; border: none;
    //     padding: 8px 16px; border-radius: 4px; cursor: pointer;
    //     margin: 10px 0; font-weight: 500;
    // `;
    //   uploadAllBtn.addEventListener('click', uploadAllFiles);

    //   // Add after upload stats or wherever appropriate
    //   const statsElement = document.getElementById('uploadStats');
    //   if (statsElement) {
    //     statsElement.parentNode.insertBefore(uploadAllBtn, statsElement.nextSibling);
    //   }
    // }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
      updatePreview();
      // addUploadAllButton();
    });
  </script>




  




  <!-- Table Search and Branch Filtering -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const searchInput = document.getElementById("search");
      const clearBtn = document.getElementById("clearSorting");
      const transactionContainers = document.querySelectorAll(".transaction-container");

      function filterTransactions() {
        let searchVal = searchInput.value.trim().toLowerCase();

        transactionContainers.forEach(container => {
          let transactNo = container.getAttribute("data-transact").toLowerCase();
          if (searchVal === "" || transactNo.includes(searchVal)) {
            container.style.display = "block"; // show matching transact
          } else {
            container.style.display = "none"; // hide non-matching
          }
        });
      }

      // Event listeners
      searchInput.addEventListener("input", filterTransactions);

      // Clear filters
      clearBtn.addEventListener("click", function () {
        searchInput.value = "";
        filterTransactions();
      });
    });
  </script>



  <?php include '../Employee Section/includes/emp-scripts.php' ?>

  <!-- Table Head  -->
  <script>
    let lastScrollTop = 0;
    const header = document.querySelector('.table-wrapper thead');

    window.addEventListener('scroll', function () {
      let currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

      if (currentScrollTop > lastScrollTop) {
        // Scrolling down
        header.classList.add('has-border-top'); // Add the border-top
        header.style.transform = 'translateY(-5px)'; // Adjust upwards slightly
      } else {
        // Scrolling up
        header.classList.remove('has-border-top'); // Remove the border-top
        header.style.transform = 'translateY(0)'; // Reset to original position
      }

      lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop; // Prevent negative scroll position
    });
  </script>

  <!-- Clickable table rows script -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll("tr[data-url]").forEach(function (row) {
        row.addEventListener("click", function () {
          window.location.href = row.getAttribute("data-url");
        });
      });
    });
    // Add event listener to each row for redirection
    const rows = document.querySelectorAll("tr[data-url]");

    rows.forEach(row => {
      row.addEventListener("click", function () {
        const url = row.getAttribute("data-url");
        window.location.href = url; // Redirect to the specified URL
      });
    });
  </script>



</body>

</html>