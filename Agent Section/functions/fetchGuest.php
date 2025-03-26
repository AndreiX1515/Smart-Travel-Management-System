<?php
require "../../conn.php"; // Database connection
session_start(); // Start session to access $_SESSION variables

if (isset($_POST['flightDate']) && isset($_POST['agentCode'])) {
    $flightDate = $_POST['flightDate'];
    $agentCode = $_POST['agentCode'];

    $assignedGuests = [];
    $unassignedGuests = [];

    // Fetch guests who are already assigned to a room
    $sqlAssigned = "SELECT g.guestId, g.fName, g.mName, g.lName, g.suffix, g.birthdate, g.age, g.sex, 
                    g.nationality, g.passportNo, g.passportExp, g.transactNo, r.roomNumber, r.roomType
                    FROM `guest` g
                    JOIN `roominglist` r ON g.guestId = r.guestId
                    JOIN `booking` b ON g.transactNo = b.transactNo
                    JOIN `flight` f ON b.flightId = f.flightId
                    WHERE b.status = 'Confirmed' AND f.flightDepartureDate = ? AND b.agentCode = ?";

    if ($stmtAssigned = $conn->prepare($sqlAssigned)) {
        $stmtAssigned->bind_param("ss", $flightDate, $agentCode);
        $stmtAssigned->execute();
        $resultAssigned = $stmtAssigned->get_result();

        while ($row = $resultAssigned->fetch_assoc()) {
            if ($row['mName'] === 'N/A') $row['mName'] = '';
            if ($row['suffix'] === 'N/A') $row['suffix'] = '';

            $fullName = trim($row['fName'] . " " . $row['suffix'] . " " . $row['lName']);
            $birthdate = !empty($row['birthdate']) ? date('Y M d', strtotime($row['birthdate'])) : 'N/A';
            $passportExp = !empty($row['passportExp']) ? date('d M Y', strtotime($row['passportExp'])) : 'N/A';

            $assignedGuests[] = [
                "id" => $row['guestId'],
                "transactNo" => $row['transactNo'],
                "name" => $fullName,
                "age" => $row['age'],
                "dob" => $birthdate,
                "sex" => $row['sex'],
                "nationality" => $row['nationality'],
                "passport" => $row['passportNo'],
                "passportExp" => $passportExp,
                "roomNumber" => $row['roomNumber'],
                "roomType" => $row['roomType']
            ];
        }
    }

    // Fetch guests who are NOT assigned to a room
    $sqlUnassigned = "SELECT g.guestId, g.fName, g.mName, g.lName, g.suffix, g.birthdate, g.age, g.sex, 
                      g.nationality, g.passportNo, g.passportExp, g.transactNo
                      FROM `guest` g
                      JOIN `booking` b ON g.transactNo = b.transactNo
                      JOIN `flight` f ON b.flightId = f.flightId
                      WHERE b.status = 'Confirmed' AND f.flightDepartureDate = ? AND b.agentCode = ?
                      AND g.guestId NOT IN (SELECT guestId FROM roomingList)";

    if ($stmtUnassigned = $conn->prepare($sqlUnassigned)) {
        $stmtUnassigned->bind_param("ss", $flightDate, $agentCode);
        $stmtUnassigned->execute();
        $resultUnassigned = $stmtUnassigned->get_result();

        while ($row = $resultUnassigned->fetch_assoc()) {
            if ($row['mName'] === 'N/A') $row['mName'] = '';
            if ($row['suffix'] === 'N/A') $row['suffix'] = '';

            $fullName = trim($row['fName'] . " " . $row['suffix'] . " " . $row['lName']);
            $birthdate = !empty($row['birthdate']) ? date('Y M d', strtotime($row['birthdate'])) : 'N/A';
            $passportExp = !empty($row['passportExp']) ? date('d M Y', strtotime($row['passportExp'])) : 'N/A';

            $unassignedGuests[] = [
                "id" => $row['guestId'],
                "transactNo" => $row['transactNo'],
                "name" => $fullName,
                "age" => $row['age'],
                "dob" => $birthdate,
                "sex" => $row['sex'],
                "nationality" => $row['nationality'],
                "passport" => $row['passportNo'],
                "passportExp" => $passportExp
            ];
        }
    }

    // Return both assigned and unassigned guests
    echo json_encode([
        "assignedGuests" => $assignedGuests,
        "unassignedGuests" => $unassignedGuests
    ]);
}
?>
