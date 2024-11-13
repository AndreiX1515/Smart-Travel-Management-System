<?php session_start(); ?>
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

  <script>
  </script>


</body>
</html>
