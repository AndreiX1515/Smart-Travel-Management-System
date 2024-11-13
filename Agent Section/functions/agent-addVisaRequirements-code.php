<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require "../../conn.php"; // Move up to the parent directory

  if (isset($_POST['attachVisaRequirements'])) {
      // Collecting posted data
      $agentId = $_SESSION['agentId'];
      $guestIds = $_POST['guestIds'];
      $passports = $_FILES['passports'];
      $permits = $_FILES['permits'];
      $validIds = $_FILES['validIds'];
      $certificates = $_FILES['certificates'];
      $guaranteedLetters = $_FILES['guaranteedLetters'];

      // Loop over each guest and their corresponding files
      for ($i = 0; $i < count($guestIds); $i++) {
          $guestId = $guestIds[$i];
          
          // Define the upload directory
          $uploadDir = "../../uploads/visa_requirements/";

          // Create directory if it doesn't exist
          if (!is_dir($uploadDir)) {
              mkdir($uploadDir, 0777, true);
          }

          // Handling the files for the guest
          $passportPath = $uploadDir . basename($passports['name'][$i]);
          $permitPath = $uploadDir . basename($permits['name'][$i]);
          $validIdPath = $uploadDir . basename($validIds['name'][$i]);
          $certificatePath = $uploadDir . basename($certificates['name'][$i]);
          $guaranteedLetterPath = $uploadDir . basename($guaranteedLetters['name'][$i]);

          // Check and move uploaded files
          $fileUploaded = true;
          $files = [
              'passport' => $passports,
              'permit' => $permits,
              'validId' => $validIds,
              'certificate' => $certificates,
              'guaranteedLetter' => $guaranteedLetters,
          ];

          foreach ($files as $fileType => $fileArray) {
              if (!move_uploaded_file($fileArray['tmp_name'][$i], $$fileType . 'Path')) {
                  $fileUploaded = false;
                  echo "Error uploading file: " . $$fileType . 'Path' . "<br>";
              }
          }

          if ($fileUploaded) {
              // Insert the data into the database for each guest
              $query = "INSERT INTO visa_requirements (guestId, agentId, passport, permit, validId, certificate, guaranteedLetter)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

              // Prepare the statement
              $stmt = $conn->prepare($query);
              if ($stmt) {
                  // Bind the parameters
                  $stmt->bind_param("i", $guestId, $agentId, $passportPath, $permitPath, $validIdPath, $certificatePath, $guaranteedLetterPath);
                  // Execute the query
                  if ($stmt->execute()) {
                      echo "Visa requirements uploaded successfully for guest $guestId.<br>";
                  } else {
                      echo "Error executing query: " . $stmt->error . "<br>";
                  }
              } else {
                  echo "Error preparing statement: " . $conn->error . "<br>";
              }
          }
      }

      // Redirect or show a success message
      echo "Visa requirements have been successfully uploaded.";
  }
?>
