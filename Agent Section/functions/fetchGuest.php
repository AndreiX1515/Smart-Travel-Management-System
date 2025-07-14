<?php
require "../../conn.php"; // Database connection

if (isset($_POST['flightDate']) && isset($_POST['agentCode'])) 
{
  $flightDate = $_POST['flightDate'];
  $agentCode = $_POST['agentCode'];

  $assignedGuests = [];
  $unassignedGuests = [];

  // Fetch guests who are assigned to a room
  $sqlAssigned = "SELECT g.guestId, g.fName, g.mName, g.lName, g.suffix, 
                    DATE_FORMAT(g.birthdate, '%Y/%m/%d') AS birthdate, g.age, g.sex, 
                    g.nationality, g.passportNo, 
                    DATE_FORMAT(g.passportExp, '%Y/%m/%d') AS passportExp, 
                    DATE_FORMAT(g.passportIssuedDate, '%Y/%m/%d') AS passportIssued, 
                    g.transactNo, r.roomNumber, r.roomType, r.remarks, r.tip,
                    cd.concernDetailsId, f.flightCode, f.flightDepartureDate, f.returnFlightCode, f.returnArrivalDate, f.flightDepartureTime, f.flightArrivalTime,
                    f.returnDepartureTime, f.returnArrivalTime
                  FROM `guest` g
                  LEFT JOIN `roominglist` r ON g.guestId = r.guestId
                  JOIN `booking` b ON g.transactNo = b.transactNo
                  JOIN `flight` f ON b.flightId = f.flightId
                  LEFT JOIN `guestluggage` l ON g.guestId = l.guestId
                  LEFT JOIN `concerndetails` cd ON l.luggageType = cd.concernDetailsId
                  WHERE b.status = 'Confirmed' 
                    AND f.flightDepartureDate = ? 
                    AND b.agentCode = ?
                    AND r.roomNumber IS NOT NULL";

  if ($stmtAssigned = $conn->prepare($sqlAssigned)) {
    $stmtAssigned->bind_param("ss", $flightDate, $agentCode);
    $stmtAssigned->execute();
    $resultAssigned = $stmtAssigned->get_result();

    $guestMap = [];
    // ✅ Capture flight details from the first row
    $flightDetails = null;

    while ($row = $resultAssigned->fetch_assoc()) 
    {
      $guestId = $row['guestId'];
      $sexFull = ucfirst(strtolower($row['sex']));
      $shortSex = $sexFull === 'Male' ? 'M' : ($sexFull === 'Female' ? 'F' : '');
      $prefix = $sexFull === 'Male' ? 'MR' : ($sexFull === 'Female' ? 'MS' : '');
      $genderValue = $sexFull === 'Male' ? '1' : ($sexFull === 'Female' ? '2' : '');
      $suffix = $row['suffix'] !== 'N/A' ? $row['suffix'] : '';
      $fullName = trim($row['fName'] . ' ' . $suffix . ' ' . $row['lName']);
      
      if ($flightDetails === null) {
        $flightDetails = [
          "flightCode" => $row['flightCode'],
          "flightDepartureDate" => $row['flightDepartureDate'],
          "returnFlightCode" => $row['returnFlightCode'],
          "arrivalDate" => $row['returnArrivalDate'],
          "flightDepartureTime" => $row['flightDepartureTime'],
          "flightArrivalTime" => $row['flightArrivalTime'],
          "returnFlightDepartureTime" => $row['returnDepartureTime'],
          "returnArrivalTime" => $row['returnArrivalTime']
        ];
      }

      if (!isset($guestMap[$guestId])) {
        $guestMap[$guestId] = [
          "id" => $guestId,
          "transactNo" => $row['transactNo'],
          "fName" => $row['fName'],
          "mName" => $row['mName'],
          "lName" => $row['lName'],
          "suffix" => $row['suffix'],
          "fullName" => $fullName,
          "age" => $row['age'],
          "dob" => $row['birthdate'] ?: 'N/A',
          "sex" => $shortSex,
          "prefix" => $prefix,
          "genderValue" => $genderValue,
          "nationality" => $row['nationality'],
          "passport" => $row['passportNo'],
          "passportExp" => $row['passportExp'] ?: 'N/A',
          "passportIssued" => $row['passportIssued'] ?: 'N/A',
          "roomNumber" => $row['roomNumber'],
          "roomType" => $row['roomType'] ?: 'N/A',
          "tip" => $row['tip'] ?: '',
          "luggageType" => [],
          "remarks" => $row['remarks'] ?: ''
        ];
      }

      if (!empty($row['concernDetailsId'])) {
        $guestMap[$guestId]['luggageType'][] = $row['concernDetailsId'];
      }
    }

    foreach ($guestMap as $guest) {
      $assignedGuests[] = $guest;
    }
  }

  // Fetch guests who are NOT assigned to a room
  $sqlUnassigned = "SELECT g.guestId, g.fName, g.mName, g.lName, g.suffix, 
                      DATE_FORMAT(g.birthdate, '%Y/%m/%d') AS birthdate, g.age, g.sex, 
                      g.nationality, g.passportNo, 
                      DATE_FORMAT(g.passportExp, '%Y/%m/%d') AS passportExp,
                      DATE_FORMAT(g.passportIssuedDate, '%Y/%m/%d') AS passportIssued,
                      g.transactNo
                    FROM `guest` g
                    JOIN `booking` b ON g.transactNo = b.transactNo
                    JOIN `flight` f ON b.flightId = f.flightId
                    LEFT JOIN `roominglist` r ON g.guestId = r.guestId
                    WHERE b.status = 'Confirmed' 
                      AND f.flightDepartureDate = ? 
                      AND b.agentCode = ? 
                      AND r.guestId IS NULL";

  if ($stmtUnassigned = $conn->prepare($sqlUnassigned)) {
    $stmtUnassigned->bind_param("ss", $flightDate, $agentCode);
    $stmtUnassigned->execute();
    $resultUnassigned = $stmtUnassigned->get_result();

    while ($row = $resultUnassigned->fetch_assoc()) {
      $sexFull = ucfirst(strtolower($row['sex']));
      $shortSex = $sexFull === 'Male' ? 'M' : ($sexFull === 'Female' ? 'F' : '');
      $prefix = $sexFull === 'Male' ? 'MR' : ($sexFull === 'Female' ? 'MS' : '');
      $genderValue = $sexFull === 'Male' ? '1' : ($sexFull === 'Female' ? '2' : '');
      $suffix = $row['suffix'] !== 'N/A' ? $row['suffix'] : '';
      $fullName = trim($row['fName'] . ' ' . $suffix . ' ' . $row['lName']);

      $unassignedGuests[] = [
        "id" => $row['guestId'],
        "transactNo" => $row['transactNo'],
        "fName" => $row['fName'],
        "mName" => $row['mName'],
        "lName" => $row['lName'],
        "suffix" => $row['suffix'],
        "fullName" => $fullName,
        "age" => $row['age'],
        "dob" => $row['birthdate'] ?: 'N/A',
        "sex" => $shortSex,
        "prefix" => $prefix,
        "genderValue" => $genderValue,
        "nationality" => $row['nationality'],
        "passport" => $row['passportNo'],
        "passportExp" => $row['passportExp'] ?: 'N/A',
        "passportIssued" => $row['passportIssued'] ?: 'N/A'
      ];
    }
  }

  echo json_encode([
    "assignedGuests" => $assignedGuests,
    "unassignedGuests" => $unassignedGuests,
    "flightDetails" => $flightDetails
  ]);
}
?>
