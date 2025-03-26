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

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-addBooking.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>
  <div class="body-container">
    <?php include "../Agent Section/includes/sidebar.php"; ?>

    <div class="main-content-container">
      <?php include "../Agent Section/includes/navbar.php"; ?>
      
      <div class="main-content">
        <h2 class="text-center">Guest Room Assignment</h2>

        <label for="flightDate">Select Flight Date</label>
        <select id="flightDate" class="form-control" required>
          <option disabled selected>Select a Flight Date</option>
          <?php
            $sql1 = "SELECT DISTINCT flightDepartureDate FROM flight 
                    WHERE flightDepartureDate >= CURDATE()
                    ORDER BY flightDepartureDate ASC";
            $result = $conn->query($sql1);
        
            if ($result->num_rows > 0) 
            {
              while ($row = $result->fetch_assoc()) 
              {
                $formattedFlightDate = date("F j, Y", strtotime($row['flightDepartureDate']));
                echo "<option value='" . $row['flightDepartureDate'] . "'>" . $formattedFlightDate . "</option>";
              }
            }
            else 
            {
              echo "<option value='' disabled>No flights available</option>";
            }
          ?>
        </select>
      
        <div class="row">
          <div class="col-md-6">
            <label for="guestName">Select Guests:</label>
            <select id="guestName" class="form-control" multiple></select>
              
              <?php
                $luggageOptions = [];
                
                $query = "SELECT concernDetailsId, details FROM concerndetails"; // Adjust table & column names
                $result = $conn->query($query);

                if ($result->num_rows > 0) 
                {
                  while ($row = $result->fetch_assoc()) 
                  {
                    $luggageOptions[] = $row;
                  }
                }

                echo "<script>let luggageOptions = " . json_encode($luggageOptions) . ";</script>";
              ?>
          </div>
          <div class="col-md-4">
            <label for="roomType">Room Type:</label>
            <select id="roomType" class="form-control" onchange="updateGuestList()">
              <option value="twin">Twin Room (Max 2)</option>
              <option value="double">Double Room (Max 2)</option>
              <option value="triple">Triple Room (Min 2 - Max 3)</option>
            </select>
          </div>
          <div class="col-md-2 mt-4">
            <button class="btn btn-primary w-100" onclick="assignRoom()">Assign</button>
          </div>
        </div>
      
        <h4 class="mt-4">Assigned Rooms</h4>
        <table class="table table-bordered mt-2">
          <thead class="thead-dark">
            <tr>
              <th style="text-align: center; vertical-align: middle;">#</th>
              <th style="text-align: center; vertical-align: middle;">AGE</th>
              <th style="text-align: center; vertical-align: middle;">GIVEN NAME</th>
              <th style="text-align: center; vertical-align: middle;">SURNAME</th>
              <th style="text-align: center; vertical-align: middle;">FULLNAME</th>
              <th style="text-align: center; vertical-align: middle;">DOB</th>
              <th style="text-align: center; vertical-align: middle;">NAT</th>
              <th style="text-align: center; vertical-align: middle;">PASSPORT</th>
              <th style="text-align: center; vertical-align: middle;">D of E</th>
              <th style="text-align: center; vertical-align: middle;">SEX</th>
              <th style="text-align: center; vertical-align: middle;">ROOMING</th>
              <th style="text-align: center; vertical-align: middle;">LUGGAGE (AIR TICKET)</th>
              <th style="text-align: center; vertical-align: middle;"></th>
            </tr>
          </thead>
          <tbody id="assignedRoomsTable"></tbody>
        </table>

        <button class="btn btn-success btn-sm" onclick="generateExcel()">Generate Excel File</button>
        <button class="btn btn-success btn-sm" id="saveAssignments">Save</button>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


  <!-- combined script working -->
  <script>
    let guests = [];  // Stores all guests fetched from PHP
    let availableGuests = [];  // Stores guests available for assignment
    let rooms = [];
    let globalGuestIndex = 0;

    document.getElementById('saveAssignments').addEventListener('click', function () 
    {
      let roomAssignments = [];

      $.ajax(
      {
        url: '../Agent Section/functions/fetchMaxRoomNumber.php', // Create this PHP file
        type: 'GET',
        dataType: 'json',
        success: function(response) 
        {
          let maxRoomNumber = response.maxRoomNumber || 0; // Start from 0 if no existing data

          let roomAssignments = [];

          // Assign room numbers dynamically
          rooms.forEach((room) => 
          {
            maxRoomNumber++; // Increment for the next room
            
            room.guests.forEach(guest => 
            {
              roomAssignments.push(
              {
                transactNo: guest.transactNo,
                guestId: guest.id,
                roomType: room.type,
                roomNumber: maxRoomNumber // Assign based on fetched maxRoomNumber
              });
            });
          });

          // Ensure there's data to send
          if (roomAssignments.length === 0) 
          {
            alert("No room assignments to save.");
            return;
          }

          // Send AJAX request to insert room assignments
          $.ajax(
          {
            url: '../Agent Section/functions/agent-addRoomingList.php',
            type: 'POST',
            data: { roomAssignments: JSON.stringify(roomAssignments) },
            success: function(response) 
            {
              console.log(response);
              alert("Room assignments saved successfully!");
            },
            error: function(xhr, status, error) 
            {
              console.error('Error inserting room assignments:', error);
              alert("Failed to save room assignments.");
            }
          });
        },
        error: function(xhr, status, error) 
        {
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

      $('#guestName').html('<option selected disabled>Loading guests...</option>');

      if (flightDate) {
          $.ajax({
              url: '../Agent Section/functions/fetchGuest.php', 
              type: 'POST',
              data: { flightDate: flightDate, agentCode: agentCode },
              success: function (response) {
                  console.log(response);
                  let guestSelect = $('#guestName');
                  guestSelect.empty(); // Clear existing options

                  let data = JSON.parse(response);
                  
                  // Populate assigned guests in room list
                  if (data.assignedGuests.length > 0) {
                    rooms = []; // Clear existing rooms before reloading
                    data.assignedGuests.forEach(guest => {
                        let room = {
                            type: guest.roomType,
                            guests: [{ ...guest }],
                            roomNumber: guest.roomNumber
                        };
                        rooms.push(room);
                    });
                    updateRoomList();
                  }

                  // Populate unassigned guests in select dropdown
                  if (data.unassignedGuests.length > 0) {
                      data.unassignedGuests.forEach(guest => {
                          guestSelect.append(new Option(guest.name, guest.id));
                      });

                      guests = data.unassignedGuests; // Store unassigned guests for reference
                  } else {
                      $('#guestName').html('<option selected disabled>No guests found</option>');
                  }
              },
              error: function (xhr, status, error) {
                  console.error('Error fetching guests:', error);
                  $('#guestName').html('<option selected disabled>No guests found</option>');
              }
          });
      } else {
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
      if (alreadyAssigned) {
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

    function updateRoomList() 
    {
      let assignedRoomsTable = document.getElementById('assignedRoomsTable');
      assignedRoomsTable.innerHTML = ''; 

      let rowNumber = 1;
      globalGuestIndex = 0;

      rooms.forEach((room, roomIndex) => 
      {
        let firstGuest = true;

        room.guests.forEach((guest) => 
        {
          let row = assignedRoomsTable.insertRow();
          // Add guest ID and transaction number for reference
          row.setAttribute("data-guest-id", guest.id);
          row.setAttribute("data-transact-no", guest.transactNo); 

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
            ${firstGuest ? `<td style="text-align: center; class="room-type"
                  vertical-align: middle;" rowspan="${room.guests.length}">${room.type.toUpperCase()}</td>` : ''} 
            <td style="text-align: center; vertical-align: middle; width: 200px; white-space: nowrap; overflow: hidden;">
              <div id="luggageContainer-${globalGuestIndex}">
                ${(guest.luggage ?? []).map(luggage => `<span class="luggage-item">${luggage}</span>`).join('')}
              </div>
              ${exportMode ? '' : `
                <button type="button" class="btn btn-success btn-sm" onclick="addLuggageSelect(${globalGuestIndex})">+</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeLuggageSelect(${globalGuestIndex})">-</button>
              `}
            </td>
            ${firstGuest ? `<td style="vertical-align: middle;" rowspan="${room.guests.length}">
              <button style="display: block; margin: auto;" class="btn btn-danger btn-sm" onclick="removeRoom(${roomIndex})">
                Remove
              </button>
            </td>` : ''}`;

          firstGuest = false;
          globalGuestIndex++;
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
        let removedGuests = rooms[index].guests; // Store guests from removed room
        let guestSelect = document.getElementById('guestName');

        // Add removed guests back to the global guests array
        guests = [...guests, ...removedGuests];

        // Sort and update the guest dropdown
        updateGuestDropdown();

        // Remove the room from the list
        rooms.splice(index, 1);

        // Update the displayed room list
        updateRoomList();
    }

    function updateGuestDropdown() 
    {
        let guestSelect = document.getElementById('guestName');
        guestSelect.innerHTML = ''; // Clear the dropdown before repopulating

        if (guests.length > 0) {
            guests.forEach(guest => {
                let option = document.createElement('option');
                option.value = guest.id;
                option.textContent = guest.name;
                guestSelect.appendChild(option);
            });
        } else {
            guestSelect.innerHTML = '<option selected disabled>No guests available</option>';
        }

        sortGuestDropdown(); // Ensure dropdown remains sorted
    }

    function sortGuestDropdown() 
    {
      let guestSelect = document.getElementById('guestName');
      let options = Array.from(guestSelect.options);

      options.sort((a, b) => a.textContent.localeCompare(b.textContent));

      guestSelect.innerHTML = '';
      options.forEach(option => guestSelect.appendChild(option));
    }

    function getMinCapacity(roomType) 
    {
      switch (roomType) 
      {
        case 'twin':
        case 'double':
          return 1; 
        case 'triple':
          return 2; 
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
          return 2;
        case 'triple':
          return 3;
        default:
          return 1;
      }
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

  <!-- Generate to excel Script -->
  <script>
    let exportMode = false; // Global flag

    function generateExcel() {
    let table = document.getElementById("assignedRoomsTable");

    if (!table || table.rows.length === 0) {
      alert("No data available to export.");
      return;
    }

    let data = [];
    let thead = table.querySelector("thead");
    
    if (thead) {
      let headers = Array.from(thead.rows[0].cells).map(cell => cell.innerText.trim());
      data.push(headers); // Add headers
    } else {
      data.push(["#", "AGE", "GIVEN NAME", "SURNAME", "FULLNAME", "DOB", "NAT", "PASSPORT", "D of E", "SEX", "ROOMING", "LUGGAGE (AIR TICKET)"]);
    }

    let rows = table.querySelectorAll("tbody tr");
    rows.forEach(row => {
      let rowData = [];
      let cells = row.cells;

      for (let j = 0; j < cells.length - 1; j++) { // Exclude last empty column
        let cell = cells[j];
        let selects = cell.querySelectorAll("select");

        if (selects.length > 0) {
          let selectedTexts = Array.from(selects).map(select => select.options[select.selectedIndex].text);
          rowData.push(selectedTexts.join(", "));
        } else {
          rowData.push(cell.innerText.trim());
        }
      }

      data.push(rowData);
    });

    let ws = XLSX.utils.aoa_to_sheet(data);
    let wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Room Assignments");
    XLSX.writeFile(wb, `room_assignments_${new Date().toISOString().slice(0, 10)}.xlsx`);
  }

  // Ensure this script runs after the DOM is ready
  $(document).ready(function () {
    $('#exportToExcel').on('click', function () {
      exportMode = true;
      updateRoomList(); // Remove buttons
      generateExcel();  // Export to Excel
      exportMode = false;
      updateRoomList(); // Restore buttons
    });
  });
  </script>

</body>
</html>