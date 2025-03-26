<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Room Assignment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Guest Room Assignment</h2>
    
    <div class="row">
        <div class="col-md-6">
            <label for="guestName">Select Guests:</label>
            <select id="guestName" class="form-control" multiple>

            
            </select>
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
    <ul id="assignedRooms" class="list-group mt-2"></ul>
</div>

<script>
    let rooms = [];
    let guests = ["Alice", "Bob", "Charlie", "David", "Emma", "Frank"];
    
    function updateGuestList() {
        let guestSelect = document.getElementById('guestName');
        guestSelect.innerHTML = '';
        
        guests.forEach(guest => {
            let option = document.createElement('option');
            option.value = guest;
            option.textContent = guest;
            guestSelect.appendChild(option);
        });
    }
    
    function assignRoom() {
        let selectedGuests = Array.from(document.getElementById('guestName').selectedOptions).map(opt => opt.value);
        let roomType = document.getElementById('roomType').value;
        
        if (selectedGuests.length < getMinCapacity(roomType) || selectedGuests.length > getMaxCapacity(roomType)) {
            alert(`A ${roomType} room must have between ${getMinCapacity(roomType)} and ${getMaxCapacity(roomType)} guests.`);
            return;
        }

        let room = { type: roomType, guests: selectedGuests };
        rooms.push(room);
        guests = guests.filter(g => !selectedGuests.includes(g));
        
        updateGuestList();
        updateRoomList();
    }
    
    function getMaxCapacity(roomType) {
        return roomType === 'triple' ? 3 : 2;
    }
    
    function getMinCapacity(roomType) {
        return roomType === 'triple' ? 2 : 1;
    }

    function updateRoomList() {
        let assignedRooms = document.getElementById('assignedRooms');
        assignedRooms.innerHTML = '';
        
        rooms.forEach((room, index) => {
            let listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            
            let roomInfo = `Room Type: ${room.type.toUpperCase()} - Guests: ${room.guests.join(', ')}`;
            listItem.innerHTML = `${roomInfo} <button class="btn btn-danger btn-sm" onclick="removeRoom(${index})">Remove</button>`;
            
            assignedRooms.appendChild(listItem);
        });
    }
    
    function removeRoom(index) {
        guests = guests.concat(rooms[index].guests);
        rooms.splice(index, 1);
        updateGuestList();
        updateRoomList();
    }

    updateGuestList();
</script>

</body>
</html>
