<?php
require '../../../conn.php';

$id = intval($_GET['id']);

$sql = "SELECT 
    v.requirementId, v.transactNo, v.guestId, v.fileType, v.filePath, v.docSubType,
    v.dateSubmitted, -- new column
    f.flightDepartureDate,
    CONCAT(
        g.fName, ' ',
        IF(g.mName = 'N/A' OR g.mName IS NULL, '', CONCAT(SUBSTRING(g.mName, 1, 1), '. ')),
        g.lName,
        IF(g.suffix = 'N/A' OR g.suffix IS NULL, '', CONCAT(' ', g.suffix))
    ) AS guestName,
    g.emailAdd, g.contactNo
FROM guest g
LEFT JOIN visarequirements v ON v.guestId = g.guestId
LEFT JOIN booking b ON b.transactNo = g.transactNo
LEFT JOIN flight f ON b.flightId = f.flightId
WHERE g.guestId = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

$response = [
  "guestId" => $id,
  "guestName" => "",
  "transactNo" => "",
  "departureDate" => "",
  "emailAdd" => "",
  "contactNo" => "",
  "files" => [
    "passport" => [],
    "permit" => [],
    "validId" => [],
    "certificate" => [],
    "guaranteedLetter" => []
  ]
];

while ($row = $res->fetch_assoc()) {
  // Fill in guest info (will always be available because we start from guest table)
  $response["guestName"] = $row['guestName'];
  $response["transactNo"] = $row['transactNo'];
  $response["departureDate"] = $row['flightDepartureDate'] ? date('Y.m.d', strtotime($row['flightDepartureDate'])) : "";
  $response["emailAdd"] = $row['emailAdd'];
  $response["contactNo"] = $row['contactNo'];

  // Only add files if requirement exists
  if ($row['requirementId']) {
    $docSubType = $row['docSubType'];
    if ($docSubType === 'bankCert')
      $docSubType = 'Bank Certificate';
    elseif ($docSubType === 'coe')
      $docSubType = 'COE';
    elseif ($docSubType === 'com')
      $docSubType = 'COM';
    elseif ($docSubType === 'birthCert')
      $docSubType = 'Birth Certificate';
    elseif ($docSubType === 'businessPermit')
      $docSubType = 'Business Permit';
    elseif ($docSubType === 'secDti')
      $docSubType = 'SEC/DTI';
    elseif ($docSubType === 'itr')
      $docSubType = 'ITR';

    $fileType = $row['fileType'];
    if (isset($response["files"][$fileType])) {
      $response["files"][$fileType][] = [
        "filePath" => $row['filePath'],
        "requirementId" => $row['requirementId'],
        "docSubType" => $docSubType,
        "dateSubmitted" => $row['dateSubmitted'] ? date('Y.m.d', strtotime($row['dateSubmitted'])) : ""
      ];
    }
  }
}

echo json_encode($response);
?>