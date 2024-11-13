<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-transaction.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>
  <?php include '../Agent Section/includes/sidebar.php'; ?> 

  <div class="main-content" id="mainContent">
    <?php 
      include '../Agent Section/includes/navbar.php'; 
      
      // Check if the transaction number is set in the session
      if (isset($_SESSION['transaction_number'])) 
      {
        $transactionNumber = $_SESSION['transaction_number'];
      } 
      else 
      {
        echo "No transaction number found.";
      }
    ?>

    <?php 
      if(isset($_SESSION['status'])):
    ?>
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Hey!</strong> <?= $_SESSION['status']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php 
      unset($_SESSION['status']);
      endif;
    ?>

    
    <div class="content-wrapper">
      <h6>Transaction No: <?php echo $transactionNumber ?></h6>

      <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#visaModal">
          Attach Visa Requirements
        </button>
      </div>

      <table class="product-table">
        <thead>
          <tr>
            <th>Guest Id</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Middle Name</th>
            <th>Suffix</th>
            <th>Birthdate</th>
            <th>Age</th>
            <th>Sex</th>
            <th>Nationality</th>
            <th>Contact No</th>
            <th>Other Contact No</th>
            <th>Email</th>
            <th>Address</th>
            <th>Passport No</th>
            <th>Passport Exp</th>
            <th>Visa Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql1= "SELECT *, DATE_FORMAT(birthdate, '%M %d, %Y') AS birthdate, CONCAT(countryCode, contactNo) AS contactNo,
                    CASE 
                      WHEN countryCode2 IS NULL OR contactNo2 IS NULL THEN 'N/A'
                      ELSE CONCAT(countryCode2, contactNo2)
                    END AS contactNo2, CONCAT(addressLine1, ', ', 
                    CASE 
                      WHEN addressLine2 IS NOT NULL AND addressLine2 != '' THEN CONCAT(addressLine2, ', ') 
                      ELSE '' 
                    END, city, ', ', state, ', ', zipcode, ', ', country) AS address
                    FROM guest 
                    WHERE transactNo = '$transactionNumber'";

            $res1 = $conn->query($sql1);

            if ($res1->num_rows > 0) 
            {
              while ($row = $res1->fetch_assoc()) 
              {
                echo "<tr>
                        <td>{$row['guestId']}</td>
                        <td>{$row['fName']}</td>
                        <td>{$row['lName']}</td>
                        <td>{$row['mName']}</td>
                        <td>{$row['suffix']}</td>
                        <td>{$row['birthdate']}</td>
                        <td>{$row['age']}</td>
                        <td>{$row['sex']}</td>
                        <td>{$row['nationality']}</td>
                        <td>{$row['contactNo']}</td>
                        <td>{$row['contactNo2']}</td>
                        <td>{$row['emailAdd']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['passportNo']}</td>
                        <td>{$row['passportExp']}</td>
                        <td>{$row['visaStatus']}</td>
                      </tr>";
              }
            } 
            else 
            {
              echo "<tr><td colspan='10'>No Guest found</td></tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
    

  </div>
  <?php require "../Agent Section/includes/scripts.php"; ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Attach Visa Requirements Modal -->
  <div class="modal fade" id="visaModal" tabindex="-1" aria-labelledby="visaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="visaModalLabel">Visa Requirements for TransactionNo: <?php echo $transactionNumber; ?></h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="../Agent Section/functions/agent-addVisaRequirements-code.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <?php
              // Assuming you have a database connection established
              $query1 = "SELECT guestId, CONCAT(
                      lName, ', ', fName, ' ', 
                      CASE WHEN mName = 'N/A' THEN '' ELSE CONCAT(SUBSTRING(mName, 1, 1), '.') END, ' ',
                      CASE WHEN suffix = 'N/A' THEN '' ELSE suffix END
                  ) AS `FULLNAME` FROM guest WHERE transactNo = '$transactionNumber'";

              // Execute the query
              $res1 = mysqli_query($conn, $query1); // Use mysqli_query directly

              if ($res1) 
              {
                // Count the number of guests
                $guestCount = mysqli_num_rows($res1);

                // Display the name and guestId for each guest inside input fields
                while ($row = mysqli_fetch_assoc($res1)) 
                {
                  $guestId = $row['guestId'];
                  $name = $row['FULLNAME'];

                  // Create input fields for each guest
                  echo "<div class='mb-3'>";
                  echo "<label for='guest-$guestId' class='form-label'>Guest ID: $guestId</label>";
                  echo "<input type='text' class='form-control' id='guest-$guestId' name='guestIds[]' value='$guestId' readonly>";

                  echo "<label for='name-$guestId' class='form-label'>Name</label>";
                  echo "<input type='text' class='form-control' id='name-$guestId' name='guestNames[]' value='$name' readonly>";

                  echo "<h6 class='form-label'>Visa Requirements</h6>";

                  echo "<label for='passport-$guestId' class='form-label'>Passport</label>";
                  echo "<input type='file' class='form-control' id='passport-$guestId' name='passports[]' />";

                  echo "<label for='permit-$guestId' class='form-label'>Permit</label>";
                  echo "<input type='file' class='form-control' id='permit-$guestId' name='permits[]' />";

                  echo "<label for='validId-$guestId' class='form-label'>Valid Id</label>";
                  echo "<input type='file' class='form-control' id='validId-$guestId' name='validIds[]' />";

                  echo "<label for='certificate-$guestId' class='form-label'>Certificate</label>";
                  echo "<input type='file' class='form-control' id='certificate-$guestId' name='certificates[]' />";
                  
                  echo "<label for='guaranteedLetter-$guestId' class='form-label'>Guaranteed Letter</label>";
                  echo "<input type='file' class='form-control' id='guaranteedLetter-$guestId' name='guaranteedLetters[]' />";
                  echo "</div>";
                }
              } 
              else 
              {
                echo "<p>Error: " . mysqli_error($conn) . "</p>"; // Display error if query fails
              }
              ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="attachVisaRequirements" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  

</body>
</html>
