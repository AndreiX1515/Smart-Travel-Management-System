<?php
session_start();
require "../conn.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Booking</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-roomingList.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>

    <?php include "../Agent Section/includes/sidebar.php"; ?>

    <div class="main-container">

      <div class="navbar">
        <div class="page-header-wrapper">

          <div class="page-header-content">
            <div class="page-header-text">
              <h5 class="header-title">Rooming List</h5>
            </div>
          </div>

        </div>
      </div>

      <div class="main-content">

        <div class="field-select-wrapper">
          <div class="row">
            <div class="col-md-6 columns ">
              <!-- Header Div for Label -->
              <div class="header-wrapper">
                <label for="flightDate">Select Flight Date</label>
              </div>

              <div class="select-wrapper">
                <select id="flightDate" class="form-control" required>
                  <option disabled selected>Select a Flight Date</option>
                  <?php
                    $sql1 = "SELECT DISTINCT flightDepartureDate FROM flight ORDER BY flightDepartureDate ASC";
                    $result = $conn->query($sql1);

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $formattedFlightDate = date("F j, Y", strtotime($row['flightDepartureDate']));
                        echo "<option value='" . $row['flightDepartureDate'] . "'>" . $formattedFlightDate . "</option>";
                      }
                    } else {
                      echo "<option value='' disabled>No flights available</option>";
                    }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">

            <div class="columns col-md-6">
              <div class="header-wrapper">
                <label for="guestName">Select Guests:</label>
              </div>

              <div class="select-wrapper">
                <select id="guestName" class="form-control" multiple></select>
                <?php
                  $luggageOptions = [];
                  $guestLuggage = []; // Stores guestId → selected luggage mapping

                  // Fetch luggage options
                  $query = "SELECT concernDetailsId, details FROM concerndetails";
                  $result = $conn->query($query);
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $luggageOptions[] = $row;
                    }
                  }

                  // Fetch stored luggage selections for each guest
                  $query = "SELECT g.guestId, cd.concernDetailsId 
                            FROM guestluggage g
                            JOIN concerndetails cd ON g.luggageType = cd.concernDetailsId"; // Adjust your table name
                  $result = $conn->query($query);
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $guestLuggage[$row['guestId']] = $row['concernDetailsId'];
                    }
                  }

                  // Pass data to JavaScript
                  echo "<script>
                          let luggageOptions = " . json_encode($luggageOptions) . ";
                          let guestLuggage = " . json_encode($guestLuggage) . ";
                        </script>";
                ?>
              </div>

            </div>

            <div class="columns col-md-6 room-type-wrapper">
              <div class="room-type-select">
                <div class="header-wrapper">
                  <label for="roomType">Room Type:</label>
                </div>

                <div class="select-wrapper">
                  <select id="roomType" class="form-control">
                    <option value="Single">Single Supplement (Max 1)</option>
                    <option value="Twin">Twin Room (Max 2)</option>
                    <option value="Double">Double Room (Max 2)</option>
                    <option value="Triple">Triple Room (Min 2 - Max 3)</option>
                  </select>
                </div>
              </div>

              <div class="assign-btn-wrapper">
                <button id="resetFilter" class="btn btn-secondary mx-2">Reset Filter</button>
                <button class="btn btn-primary" onclick="assignRoom()">Assign</button>
              </div>

            </div>
          </div>
        </div>

        <div class="table-wrapper">
          <div class="header-wrapper">
            <h5>Assigned Rooms</h5>
          </div>
          
          <div class="table-container">
            <table class="assigned-rooms-table table table-bordered">
              <colgroup>
								<col style="width: 3%;">   <!-- # -->
								<col style="width: 3%;">   <!-- AGE -->
								<col style="width: 3%;">   <!-- MS/MR -->
								<col style="width: 10%;">  <!-- GIVEN NAME -->
								<col style="width: 10%;">  <!-- SURNAME -->
								<col style="width: 15%;">  <!-- FULL NAME -->
								<col style="width: 8%;">   <!-- DOB -->
								<col style="width: 6%;">   <!-- NAT. -->
								<col style="width: 8%;">  <!-- PASSPORT -->
								<col style="width: 8%;">   <!-- I of E -->
								<col style="width: 8%;">   <!-- D of E -->
								<col style="width: 3%;">   <!-- SEX M/F -->
								<col style="width: 3%;">   <!-- SEX 1/2 -->
								<col style="width: 2%;">   <!-- ROOM TYPE -->
								<col style="width: 6%;">   <!-- ROOM NO. -->
								<col style="width: 10%;">   <!-- TIPPING -->
								<col style="width: 15%;">  <!-- LUGGAGE -->
								<col style="width: 15%;">  <!-- REMARKS -->
								<col style="width: 8%;">   <!-- ACTION -->
							</colgroup>
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>AGE</th>
                  <th>MS/MR</th>
                  <th>GIVEN NAME</th>
                  <th>SURNAME</th>
                  <th>FULL NAME</th>
                  <th>DOB</th>
                  <th>NAT.</th>
                  <th>PASSPORT</th>
                  <th>I of E<br><small>Issue of Date</small></th>
                  <th>D of E<br><small>Expiry of Date</small></th>
                  <th colspan="2">SEX</th>
                  <th colspan="2">ROOMING</th>
                  <th>TIPPING</th>
                  <th>LUGGAGE<br>(AIR TICKET)</th>
                  <th>REMARKS<br><small>(Separate air time, wheelchair, etc)</small></th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="assignedRoomsTable"></tbody>
            </table>
          </div>
          
          <div class="table-footer">
            <button class="btn btn-success btn-sm" onclick="generateExcel()">Download</button>
            <button class="btn btn-success btn-sm" id="saveAssignments">Save</button>
          </div>
        
        </div>

      </div>
    </div>

  <?php require "../Agent Section/includes/scripts.php"; ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

  <!-- Reset Filter Script -->
  <script>
    document.getElementById("resetFilter").addEventListener("click", function () {
      // Reset flightDate select to default
      const flightDate = document.getElementById("flightDate");
      if (flightDate) flightDate.selectedIndex = 0;

      // Clear and reset guestName multiple select
      const guestName = document.getElementById("guestName");
      if (guestName) {
        guestName.innerHTML = '<option selected disabled>No guests available</option>';
      }

      // Optional: clear any rooms assigned in memory
      rooms = [];
      updateRoomList();

      // Optional: clear guests array
      guests = [];
      updateGuestDropdown();
    });
  </script>

  <!-- Combined script working -->
  <script>
    let guests = [];  // Stores all guests fetched from PHP
    let availableGuests = [];  // Stores guests available for assignment
    let rooms = [];
    let removedLuggage = [];
    let flightDetails = null;

    const roomCapacities = {
      Single: { min: 1, max: 1 },
      Twin:   { min: 2, max: 2 },
      Double: { min: 2, max: 2 },
      Triple: { min: 3, max: 3 }, // Example: now Triple can have min 2 guests
    };

    document.getElementById('saveAssignments').addEventListener('click', function () {
      let roomAssignments = [];
      let luggageAssignments = [];

      $.ajax({
        url: '../Agent Section/functions/fetchMaxRoomNumber.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
          let maxRoomNumber = response.maxRoomNumber || 0;

          rooms.forEach((room) => {
            maxRoomNumber++; // Increment for each room

            room.guests.forEach(guest => {
              // ✅ Get tipping value
              const tippingSelect = document.querySelector(`[name="tipping-${guest.id}"]`);
              const tippingValue = tippingSelect ? tippingSelect.value : '';

              // ✅ Get remarks value
              const remarksTextarea = document.querySelector(`[name="remarks-${guest.id}"]`);
              const remarksValue = remarksTextarea ? remarksTextarea.value.trim() : '';

              // ✅ Push room assignment with tipping and remarks
              roomAssignments.push({
                transactNo: guest.transactNo,
                guestId: guest.id,
                roomType: room.type,
                roomNumber: maxRoomNumber,
                tipping: tippingValue,
                remarks: remarksValue
              });

              // ✅ Collect luggage data
              const luggageItems = Array.from(document.querySelectorAll(`#luggageContainer-${guest.id} select`))
                .map(select => select.value)
                .filter(val => val !== ""); // Only push non-empty

              luggageItems.forEach(luggage => {
                luggageAssignments.push({
                  transactNo: guest.transactNo,
                  guestId: guest.id,
                  luggageId: luggage
                });
              });
            });
          });

          if (roomAssignments.length === 0) {
            alert("No room assignments to save.");
            return;
          }

          // ✅ POST to save with new tipping + remarks
          $.ajax({
            url: '../Agent Section/functions/agent-addRoomingList.php',
            type: 'POST',
            data: {
              roomAssignments: JSON.stringify(roomAssignments),
              luggageAssignments: JSON.stringify(luggageAssignments)
            },
            success: function (response) {
              console.log(response);
              alert("Room assignments and luggage details saved successfully!");
            },
            error: function (xhr, status, error) {
              console.error('Error saving data:', error);
              alert("Failed to save room assignments and luggage details.");
            }
          });
        },
        error: function (xhr, status, error) {
          console.error('Error fetching max room number:', error);
          alert("Failed to fetch max room number.");
        }
      });
    });

    // Fetch guests when flight date changes
    $('#flightDate').on('change', function () 
    {
      let flightDate = $(this).val();
      let agentCode = "<?php echo $agentCode; ?>";
      console.log("Luggage Options:", luggageOptions);
      $('#guestName').html('<option selected disabled>Loading guests...</option>');

      if (flightDate) 
      {
        $.ajax(
        {
          url: '../Agent Section/functions/fetchGuest.php', 
          type: 'POST',
          data: { flightDate: flightDate, agentCode: agentCode },
          success: function (response) 
          {
            console.log(response);
            let guestSelect = $('#guestName');
            guestSelect.empty(); // Clear existing options

            let data = JSON.parse(response);
            flightDetails = data.flightDetails || {};

            // 👇 Preserve manually entered luggage before clearing rooms
            preserveLuggageSelections();
            
            // Populate assigned guests in room list
            if (data.assignedGuests.length > 0)
            {
                rooms = []; // Clear existing rooms before reloading

                data.assignedGuests.forEach(guest => {
                  let existingRoom = rooms.find(room =>
                    room.roomNumber === guest.roomNumber && room.type === guest.roomType
                  );

                  if (existingRoom) {
                    existingRoom.guests.push({ ...guest });
                  } else {
                    rooms.push({
                      type: guest.roomType,
                      roomNumber: guest.roomNumber,
                      guests: [{ ...guest }]
                    });
                  }
                });

                updateRoomList();
              }

            // Populate unassigned guests in select dropdown
            if (data.unassignedGuests.length > 0) 
            {
              data.unassignedGuests.forEach(guest => 
              {
                guestSelect.append(new Option(guest.fullName, guest.id));
              });

              guests = data.unassignedGuests; // Store unassigned guests for reference
            } 
            else 
            {
              $('#guestName').html('<option selected disabled>No guests found</option>');
            }
          },
          error: function (xhr, status, error) 
          {
            console.error('Error fetching guests:', error);
            $('#guestName').html('<option selected disabled>No guests found</option>');
          }
        });
      } 
      else 
      {
        $('#guestName').html('<option selected disabled>Select a guest</option>');
      }
    });

    function assignRoom() 
    {
      let guestSelect = document.getElementById('guestName');
      let selectedGuests = Array.from(guestSelect.selectedOptions).map(opt => 
      {
        let guestData = guests.find(g => g.id == opt.value);
        return { ...guestData };
      });

      let roomType = document.getElementById('roomType').value;
      let minCapacity = getMinCapacity(roomType);
      let maxCapacity = getMaxCapacity(roomType);

      if (selectedGuests.length < minCapacity || selectedGuests.length > maxCapacity) 
      {
        alert(`A ${roomType} room must have between ${minCapacity} and ${maxCapacity} guests.`);
        return;
      }

      // Fetch latest room number before assigning
      let transactNo = selectedGuests[0]?.transactNo; // Assuming all guests share the same transactNo
      if (!transactNo) 
      {
        alert("Error: Missing transaction number.");
        return;
      }

      // Prevent duplicate assignments
      let alreadyAssigned = selectedGuests.some(guest =>
        rooms.some(room => room.guests.some(g => g.id == guest.id))
      );
      if (alreadyAssigned) 
      {
        alert("One or more guests are already assigned to a room.");
        return;
      }

      $.ajax(
      {
        url: '../Agent Section/functions/fetchMaxRoomNumber.php',
        type: 'POST',
        data: { transactNo: transactNo },
        success: function (response) 
        {
          let lastRoomNumber = !isNaN(parseInt(response)) ? parseInt(response) : 0; // Default to 0 if no rooms exist
          let nextRoomNumber = lastRoomNumber + 1; // Increment for new room assignment

          // Update available guests list after assignment
          guests = guests.filter(g => !selectedGuests.some(sg => sg.id == g.id));

          // Add luggage types to selected guests before assigning to room
          selectedGuests.forEach(guest => 
          {
            // Assuming guestLuggage is a global object holding luggage types by guestId
            guest.luggageTypes = guestLuggage[guest.id] || [];
          });

          let room = { type: roomType, guests: selectedGuests, roomNumber: nextRoomNumber };
          rooms.push(room);

          updateRoomList();
          updateGuestDropdown(); // Refresh guest dropdown after assignment
        },
        error: function (xhr, status, error) 
        {
          console.error("Error fetching last room number:", error);
          alert("Failed to fetch room number.");
        }
      });
    }

    function preserveLuggageSelections() {
      rooms.forEach(room => {
        room.guests.forEach(guest => {
          const selects = document.querySelectorAll(`#luggageContainer-${guest.id} select`);
          if (selects.length > 0) {
            guest.luggageType = Array.from(selects).map(select => select.value).filter(Boolean);
          }
        });
      });
    }

    // Function to update the room list
    function updateRoomList() {
      const assignedRoomsTable = document.getElementById('assignedRoomsTable');
      assignedRoomsTable.innerHTML = ''; // Clear the existing table

      let rowNumber = 1;
      let roomDisplayNumber = 1;
      let femaleIndex = 1;
      let maleIndex = 1;

      const sortedRooms = [...rooms].sort((a, b) => a.roomNumber - b.roomNumber);

      sortedRooms.forEach((room, roomIndex) => {
        let firstGuest = true;
        room.guests.sort((a, b) => (a.fullName || "").localeCompare(b.fullName || ""));

        room.guests.forEach((guest) => {
          const row = assignedRoomsTable.insertRow();
          row.setAttribute("data-guest-id", guest.id);
          row.setAttribute("data-transact-no", guest.transactNo);

          const luggageCount = (guest.luggageType || []).length;
          let luggageSelectGroup = `
            <div id="luggageContainer-${guest.id}" class="luggage-group" data-guest-id="${guest.id}">
              <div id="luggageSelects-${guest.id}">`;

          for (let i = 0; i < luggageCount; i++) {
            luggageSelectGroup += `
              <select class="form-control" name="luggageSelect-${guest.id}[]" 
                style="width: 100%; display: block; margin-bottom: 5px;" 
                id="luggageSelect-${guest.id}-${i}">
                <option value="">Select Luggage</option>`;

            luggageOptions.forEach(option => {
              const selected = option.concernDetailsId == guest.luggageType[i] ? 'selected' : '';
              luggageSelectGroup += `<option value="${option.concernDetailsId}" ${selected}>${option.details}</option>`;
            });

            luggageSelectGroup += `</select>`;
          }

          luggageSelectGroup += `
              </div>
              <button type="button" class="btn btn-sm btn-primary mt-1" style="margin-top: 5px;" onclick="addLuggageSelect(${guest.id})">Add</button>
              <button type="button" class="btn btn-sm btn-danger mt-1" style="margin-top: 5px; margin-left: 5px;" onclick="removeLuggageSelect(${guest.id})">Remove</button>
            </div>`;

          const isFemale = guest.sex?.toLowerCase() === 'female';
          const title = isFemale ? 'MS' : 'MR';
          const sexLabel = isFemale ? `F\t${femaleIndex++}` : `M\t${maleIndex++}`;

          // ✅ Room type color (if first guest)
          let roomTypeStyle = '';
          if (firstGuest && room.type) {
            const lowerRoomType = room.type.toLowerCase();
            if (lowerRoomType.includes('twin')) {
              roomTypeStyle = 'background-color: #FFFF00;';
            } else if (lowerRoomType.includes('double')) {
              roomTypeStyle = 'background-color: #B57EDC;';
            } else if (lowerRoomType.includes('triple')) {
              roomTypeStyle = 'background-color: #3CFF00;';
            } else if (lowerRoomType.includes('single')) {
              roomTypeStyle = 'background-color: #003CFF; color: white;';
            }
          }

          // ✅ Tipping color
          let tippingStyle = '';
          if (guest.tip === 'In Korea') {
            tippingStyle = 'background-color: #FFFF00;';
          } else if (guest.tip === 'In Manila') {
            tippingStyle = 'background-color: #ADD8E6;';
          }

          row.innerHTML = `
            <td style="text-align: center; vertical-align: middle;">${rowNumber++}</td>
            <td style="text-align: center; vertical-align: middle;">${guest.age || "N/A"}</td>
            <td style="text-align: center; vertical-align: middle;">${title}</td>
            <td style="text-align: center; vertical-align: middle;">${(guest.fName ?? 'N/A')}${guest.suffix && guest.suffix !== 'N/A' ? ' ' + guest.suffix : ''}</td>
            <td style="text-align: center; vertical-align: middle;">${guest.lName || "N/A"}</td>
            <td style="text-align: center; vertical-align: middle;">${guest.fullName || `${guest.fName || ""} ${guest.lName || ""}`}</td>
            <td style="text-align: center; vertical-align: middle;">${guest.dob || "N/A"}</td>
            <td style="text-align: center; vertical-align: middle;">${guest.nationality || "N/A"}</td>
            <td style="text-align: center; vertical-align: middle;">${guest.passport || "N/A"}</td>
            <td style="text-align: center; vertical-align: middle;">${guest.passportIssued || "N/A"}</td>
            <td style="text-align: center; vertical-align: middle;">${guest.passportExp || "N/A"}</td>
            <td style="text-align: center; vertical-align: middle;">${guest.sex || "N/A"}</td>
            <td style="text-align: center; vertical-align: middle;">${guest.genderValue || ""}</td>
            ${firstGuest ? `<td style="text-align: center; vertical-align: middle;" rowspan="${room.guests.length}">${roomDisplayNumber}</td>` : ''}
            ${firstGuest ? `<td style="text-align: center; vertical-align: middle; ${roomTypeStyle}" class="room-type" rowspan="${room.guests.length}">${room.type.toUpperCase()}</td>` : ''}
            <td style="text-align: center; vertical-align: middle; ${tippingStyle}">
              <select name="tipping-${guest.id}" class="form-control">
                <option value="">Select</option>
                <option value="In Korea" ${guest.tip === 'In Korea' ? 'selected' : ''}>In Korea</option>
                <option value="In Manila" ${guest.tip === 'In Manila' ? 'selected' : ''}>In Manila</option>
              </select>
            </td>
            <td style="text-align: center; vertical-align: middle;">${luggageSelectGroup}</td>
            <td style="text-align: center; vertical-align: middle;">
              <textarea name="remarks-${guest.id}" class="form-control" rows="2" style="resize: vertical; width: 100%;">${guest.remarks || ''}</textarea>
            </td>
            ${firstGuest ? `<td style="vertical-align: middle;" rowspan="${room.guests.length}">
              <button style="display: block; margin: auto;" class="btn btn-danger btn-sm" onclick="removeRoom(${roomIndex})">
                Remove
              </button>
            </td>` : ''}`;

          firstGuest = false;
        });

        roomDisplayNumber++;
      });
    }

    // Add a luggage select dropdown dynamically
    function addLuggageSelect(guestId) 
    {
      const container = document.getElementById(`luggageSelects-${guestId}`);
      const selectCount = container.querySelectorAll('select').length;

      let select = document.createElement('select');
      select.className = 'form-control';
      select.name = `luggageSelect-${guestId}[]`;
      select.id = `luggageSelect-${guestId}-${selectCount}`;
      select.style.cssText = 'width: 100%; display: block; margin-bottom: 5px;';

      let defaultOption = document.createElement('option');
      defaultOption.value = '';
      defaultOption.textContent = 'Select Luggage';
      select.appendChild(defaultOption);

      luggageOptions.forEach(option => {
        let opt = document.createElement('option');
        opt.value = option.concernDetailsId;
        opt.textContent = option.details;
        select.appendChild(opt);
      });

      container.appendChild(select); // Append to the luggageSelects container
    }

    function generateLuggageSelects(guestId) 
    {
      let html = "";

      if (guestLuggage[guestId]) 
      {
        // Ensure luggageIds are always an array
        let luggageIds = Array.isArray(guestLuggage[guestId]) ? guestLuggage[guestId] : [guestLuggage[guestId]];

        // Iterate through all luggage types assigned to the guest
        luggageIds.forEach((luggageId, index) => 
        {
          let select = `<select class="form-control" name="luggageSelect-${guestId}[]" style="width: 100%; display: block;" id="luggageSelect-${guestId}-${index}">
                          <option value="">Select Luggage</option>`;

          // Populate luggage options dynamically
          luggageOptions.forEach(option => 
          {
            select += `<option value="${option.concernDetailsId}" ${option.concernDetailsId == luggageId ? 'selected' : ''}>${option.details}</option>`;
          });

          select += `</select>`;
          html += select;
        });
      }

      return html;
    }

    // Remove the last luggage select dropdown for a guest
    function removeLuggageSelect(guestId) 
    {
      const container = document.getElementById(`luggageSelects-${guestId}`);
      const selects = container.querySelectorAll('select');

      if (selects.length > 0) 
      {
        container.removeChild(selects[selects.length - 1]);
      }
    }

    // Remove a room and reassign the guests to the unassigned list
    function removeRoom(index) 
    {
      let removedGuests = rooms[index].guests;
      guests = [...guests, ...removedGuests];
      updateGuestDropdown();
      rooms.splice(index, 1);
      updateRoomList();
    }

    // Update the guest dropdown
    function updateGuestDropdown() 
    {
      let guestSelect = document.getElementById('guestName');
      guestSelect.innerHTML = '';
      if (guests.length > 0) 
      {
        guests.forEach(guest => 
        {
          let option = document.createElement('option');
          option.value = guest.id;
          option.textContent = guest.fullName;
          guestSelect.appendChild(option);
        });
      } 
      else 
      {
        guestSelect.innerHTML = '<option selected disabled>No guests available</option>';
      }
      sortGuestDropdown();
    }

    // Sort the guest dropdown alphabetically
    function sortGuestDropdown() 
    {
      let guestSelect = document.getElementById('guestName');
      let options = Array.from(guestSelect.options);
      options.sort((a, b) => a.textContent.localeCompare(b.textContent));
      guestSelect.innerHTML = '';
      options.forEach(option => guestSelect.appendChild(option));
    }

    // Get minimum capacity based on room type
    function getMinCapacity(roomType) 
    {
      switch (roomType) 
      {
        case 'Single':
          return 1;
        case 'Twin':
        case 'Double':
          return 2;
        case 'Triple':
          return 3;
        default:
          return 1;
      }
    }

    // Get maximum capacity based on room type
    function getMaxCapacity(roomType) 
    {
      switch (roomType) 
      {
        case 'Single':
          return 1;
        case 'Twin':
        case 'Double':
          return 2;
        case 'Triple':
          return 3;
        default:
          return 1;
      }
    }
  </script>

  <!-- Generate to excel Script -->
  <script>
    function generateExcel() {
      if (!rooms || rooms.length === 0) {
        alert("No room assignments available to export.");
        return;
      }

      // ✅ Pull latest values from the form into `rooms`
      syncGuestFormDataIntoRooms();

      const flightDate = document.getElementById("flightDate").value || "unknown-date";
      const travelAgency = "<?php echo $companyName ?? 'Unknown Company'; ?>";

      const formattedRooms = rooms.map(room => ({
        ...room,
        guests: room.guests.map(guest => ({
          ...guest,
          luggageText: getLuggageTextFromIds(guest.luggageType || [])
        }))
      }));

      const payload = {
        rooms: formattedRooms,
        flightDate,
        travelAgency,
        flightDetails
      };

      // 🔍 Log the payload you're sending to the backend
      console.log("Sending rooming list payload to backend:", payload);

      fetch('../Agent Section/functions/generateRoomingList.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(response => {
        if (!response.ok) throw new Error("Failed to generate Excel file.");
        return response.blob();
      })
      .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `RoomingList_${flightDate}.xlsx`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
      })
      .catch(error => {
        console.error("Error exporting to Excel:", error);
        alert("Something went wrong while generating the Excel file.");
      });
    }

    function getLuggageTextFromIds(luggageIds) {
      if (!Array.isArray(luggageIds)) return [];

      return luggageIds.map(id => {
        let match = luggageOptions.find(opt => opt.concernDetailsId == id);
        return match ? match.details : id; // fallback to ID if not found
      });
    }

    function syncGuestFormDataIntoRooms() {
      rooms.forEach(room => {
        room.guests.forEach(guest => {
          const tipSelect = document.querySelector(`[name="tipping-${guest.id}"]`);
          const remarksTextarea = document.querySelector(`[name="remarks-${guest.id}"]`);

          if (tipSelect) guest.tip = tipSelect.value;
          if (remarksTextarea) guest.remarks = remarksTextarea.value;
        });
      });
    }
  </script>

  <!-- Dynamic addition of guest in the table as well as the request script
  <script>
    let guests = $("#guestName option").map(function() {
        return { value: $(this).val(), text: $(this).text() };
    }).get();  // Fetch guest details from PHP
    let availableGuests = [...guests];  // Dynamic list for UI updates
    let rooms = [];
    let globalGuestIndex = 0; // 🔹 Unique index across all rooms

    function updateGuestList()
    {
      let guestSelect = document.getElementById('guestName');
      guestSelect.innerHTML = ''; // 🛑 Clear previous options

      availableGuests.forEach(guest => 
      {
        let option = document.createElement('option');
        option.value = guest.id;
        option.textContent = guest.name;
        guestSelect.appendChild(option);
      });
    }

    function assignRoom() 
    {
      let guestSelect = document.getElementById('guestName');
      let selectedGuests = Array.from(guestSelect.selectedOptions).map(opt => 
      {
        let guestData = guests.find(g => g.id == opt.value);  // Fetch full guest details
        return { ...guestData }; // Return full guest object
      });

      let roomType = document.getElementById('roomType').value;

      let minCapacity = getMinCapacity(roomType);
      let maxCapacity = getMaxCapacity(roomType);

      if (selectedGuests.length < minCapacity || selectedGuests.length > maxCapacity) 
      {
        alert(`A ${roomType} room must have between ${minCapacity} and ${maxCapacity} guests.`);
        return;
      }

      // 🛑 Remove assigned guests from available list
      availableGuests = availableGuests.filter(g => !selectedGuests.some(sg => sg.id == g.id));

      // ✅ Remove selected guests from dropdown
      selectedGuests.forEach(guest => 
      {
        let optionToRemove = guestSelect.querySelector(`option[value="${guest.id}"]`);
        if (optionToRemove) 
        {
            optionToRemove.remove();
        }
      });

      // Store assigned room
      let room = { type: roomType, guests: selectedGuests };
      rooms.push(room);

      updateRoomList();
    }

    function updateRoomList() 
    {
      let assignedRoomsTable = document.getElementById('assignedRoomsTable');
      assignedRoomsTable.innerHTML = '';  // Clear previous rows

      let rowNumber = 1; // Initialize guest counter
      globalGuestIndex = 0; // Reset when updating list

      rooms.forEach((room, roomIndex) => 
      {
        let firstGuest = true; // Track the first row for rowspan effect

        room.guests.forEach((guest) => 
        {
          let row = assignedRoomsTable.insertRow();

          row.innerHTML = `
            <td style="text-align: center; vertical-align: middle;">${rowNumber++}</td> 
            <td style="text-align: center; vertical-align: middle;">${guest.age || "N/A"}</td> 
            <td style="text-align: center; vertical-align: middle;">${guest.name.split(" ")[0]}</td> 
            <td style="text-align: center; vertical-align: middle;">${guest.name.split(" ").slice(-1).join(" ")}</td> 
            <td style="text-align: center; vertical-align: middle;">${guest.name}</td> 
            <td style="text-align: center; vertical-align: middle;">${guest.dob || "N/A"}</td> 
            <td style="text-align: center; vertical-align: middle;">${guest.nationality || "N/A"}</td> 
            <td style="text-align: center; vertical-align: middle;">${guest.passport || "N/A"}</td> 
            <td style="text-align: center; vertical-align: middle;">${guest.passportExp || "N/A"}</td> 
            <td style="text-align: center; vertical-align: middle;">${guest.sex || "N/A"}</td> 
            ${firstGuest ? `<td style="text-align: center;
                  vertical-align: middle;" rowspan="${room.guests.length}">${room.type.toUpperCase()}</td>` : ''} 
            <td style="text-align: center; vertical-align: middle; width: 200px; white-space: nowrap; overflow: hidden;">
              <div id="luggageContainer-${globalGuestIndex}"></div> 
              <button type="button" class="btn btn-success btn-sm" onclick="addLuggageSelect(${globalGuestIndex})">+</button>
              <button type="button" class="btn btn-danger btn-sm" onclick="removeLuggageSelect(${globalGuestIndex})">-</button>
            </td>
            ${firstGuest ? `<td style="vertical-align: middle;" rowspan="${room.guests.length}">
              <button style="display: block; margin: auto;" class="btn btn-danger btn-sm" onclick="removeRoom(${roomIndex})">
                Remove
              </button>
            </td>` : ''}`;

          firstGuest = false; // Prevent rowspan duplication in next guest rows
          globalGuestIndex++; // 🔹 Increment for each guest across rooms
        });
      });
    }

    function addLuggageSelect(guestIndex) 
    {
      let container = document.getElementById(`luggageContainer-${guestIndex}`);

      let select = document.createElement("select");
      select.style.width = "100%";
      select.style.display = "block"; // Ensures proper positioning
      select.classList.add("form-control");;

      luggageOptions.forEach(option => 
      {
        let opt = document.createElement("option");
        opt.value = option.concernDetailsId;
        opt.textContent = option.details;
        select.appendChild(opt);
      });

      // Append to container (adds at the end)
      container.appendChild(select);
    }

    // Function to remove last luggage select
    function removeLuggageSelect(guestIndex) 
    {
      let container = document.getElementById(`luggageContainer-${guestIndex}`);
      if (container.children.length > 0) 
      {
        container.removeChild(container.lastChild);
      }
    }

    function removeRoom(index) 
    {
      let guestSelect = document.getElementById('guestName');

      // Restore guests to available list
      rooms[index].guests.forEach(guest => 
      {
        if (!availableGuests.some(g => g.id == guest.id)) 
        {
          availableGuests.push(guest);

          // ✅ Add guest back to the dropdown
          let option = document.createElement('option');
          option.value = guest.id;
          option.textContent = guest.name;
          guestSelect.appendChild(option);
        }
      });

      // Sort the dropdown after adding guests back
      sortGuestDropdown();

      // Remove the room from the list
      rooms.splice(index, 1);
      updateRoomList();
    }

    function sortGuestDropdown() 
    {
      let guestSelect = document.getElementById('guestName');
      let options = Array.from(guestSelect.options);

      options.sort((a, b) => a.textContent.localeCompare(b.textContent));

      guestSelect.innerHTML = ''; // Clear existing options
      options.forEach(option => guestSelect.appendChild(option)); // Append sorted options
    }

    function getMinCapacity(roomType) 
    {
      switch (roomType) 
      {
        case 'twin':
        case 'double':
          return 1; // Can be occupied by 1 or 2 guests
        case 'triple':
          return 2; // Must have at least 2 guests
        default:
          return 1;
      }
    }

    function getMaxCapacity(roomType) 
    {
      switch (roomType) 
      {
        case 'twin':
        case 'double':
          return 2; // Max 2 guests
        case 'triple':
          return 3; // Max 3 guests
        default:
          return 1;
      }
    }

    // ✅ Initialize guest list on page load
    document.addEventListener("DOMContentLoaded", updateGuestList);
  </script>

  Dynamic Guest Info 
  <script>
    $('#flightDate').on('change', function () 
    {
      var flightDate = $(this).val();
      var agentCode = "<?php echo $agentCode; ?>";
      
      // Clear guest dropdown while loading
      $('#guestName').html('<option selected disabled>Loading guests...</option>');

      if (flightDate) 
      {
        $.ajax(
        {
          url: '../Agent Section/functions/fetchGuest.php', // PHP file to handle request
          type: 'POST',
          data: { flightDate: flightDate,
                  agentCode: agentCode},
          success: function (response) 
          {
            console.log(response);
            // Parse response and update guest dropdown
            $('#guestName').html(response);
          },
          error: function (xhr, status, error) 
          {
            console.error('Error fetching guests:', error);
            $('#guestName').html('<option selected disabled>No guests found</option>');
          }
        });
      } 
      else 
      {
        $('#guestName').html('<option selected disabled>Select a guest</option>'); // Reset if no flight date
      }
    });
  </script> -->

</body>

</html>