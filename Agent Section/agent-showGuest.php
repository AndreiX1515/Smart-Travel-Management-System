<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="../Agent Section/assets/css/agent-showguest.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>
  <?php include '../Agent Section/includes/sidebar.php'; ?>

  <div class="main-content" id="mainContent">
    <?php 
      include '../Agent Section/includes/navbar.php'; 

      // // Check if 'transaction_number' exists in the session
      if (isset($_SESSION['transaction_number'])) {
          $transactionNumber = $_SESSION['transaction_number'];
      } else {
          echo "No transaction number found in the session.<br>";
      }

      // Check if 'id' is passed in the URL
      if (isset($_GET['id'])) 
      {
       $transactionNumber = htmlspecialchars($_GET['id']);
       
      } 
      
      ?>
    

    <?php if(isset($_SESSION['status'])): ?>
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Hey!</strong> <?= $_SESSION['status']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

      <?php 
        unset($_SESSION['status']);
        endif;
    ?>

     <?php
       $query1 = "SELECT booking.*, package.packageName, flight.flightDepartureDate 
                   FROM booking 
                   JOIN package ON booking.packageId = package.packageId
                   LEFT JOIN flight ON booking.flightId = flight.flightId
                   WHERE transactNo = '$transactionNumber'";

       $result1 = $conn->query($query1);

       if ($result1->num_rows > 0) 
       {
         // Output data of each row
         while ($row1 = $result1->fetch_assoc()) 
         {
           $transactNum = $row1['transactNo'];
           $fName = $row1['fName'];
           $mName = $row1['mName'];
           $lName = $row1['lName'];
           $suffix = $row1['suffix'];
           $countryCode = $row1['countryCode'];
           $contact = $row1['contactNo'];
           $email = $row1['email'];
           $packageName = $row1['packageName'];
           $flightDate = $row1['flightDepartureDate'];
           $pax = $row1['pax'];
           $status = $row1['status'];
           $flightId = $row1['flightId']; // Fetch flightId

           // Construct the full name using the conditions for middle name and suffix
           $fullName = $lName . ", " . $fName . " " . 
                       ($suffix !== 'N/A' ? $suffix . " " : "") .  // Add space after suffix only if it's not 'N/A'
                       ($mName !== 'N/A' ? substr($mName, 0, 1) . ". " : "");  // Add middle initial with dot only if it's not 'N/A'
           $contactNo = $countryCode . $contact;

           // Check if flightId is NULL and set flightDate accordingly
           if (is_null($flightId)) 
           {
             $flightDate = "Land Package Only";
           }

           $status = isset($row1['status']) ? $row1['status'] : 'Unknown';

           // Initialize an empty class string
           $statusClass = '';

           // Assign classes based on the status value using switch
           switch ($status) {
               case 'Confirmed':
                   $statusClass = 'bg-success text-white'; // Green background, white text
                   break;
               case 'Cancelled':
                   $statusClass = 'bg-danger text-white'; // Red background, white text
                   break;
               case 'Pending':
                   $statusClass = 'bg-warning text-dark'; // Yellow background, dark text
                   break;
               default:
                   $statusClass = 'bg-secondary text-white'; // Gray background, white text
                   break;
           }

           ?>

     <div class="content-wrapper">
       <div class="header">
         <div class="transaction-info">
           <div class="row g-3 mb-1">
              <!-- <h5 class="fw-bold">Transaction Information: </h5> -->
              <div class="col-md-5 mb-2 d-flex flex-column gap-1">
                 <p class=""><strong>Transaction No:</strong> <?php echo htmlspecialchars($transactNum); ?></p>
                 <p class=""><strong>Total Pax:</strong> <?php echo htmlspecialchars($pax); ?></p>
                 <p class=""><strong>Package:</strong> <?php echo htmlspecialchars($packageName); ?></p>
                 <p class=""><strong>Flight Date:</strong> <?php echo htmlspecialchars($flightDate); ?></p>
                 <p class=""><strong>Status:</strong> <span class="badge rounded-pill <?php echo $statusClass; ?>"> 
                  <?php echo htmlspecialchars($status); ?> </span> </p>
              </div>

              <div class="col-md-5 mb-3 d-flex flex-column gap-1">
                <p class=""><strong>Contact Person:</strong> <?php echo htmlspecialchars($fullName); ?></p>
                <p class=""><strong>Contact No:</strong> <?php echo htmlspecialchars($contactNo); ?></p>
                <p class=""><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
              </div>

             <?php
             }

             } else {
               echo "0 results";
             }
           ?>
         </div> 

         <div class="transaction-info-footer d-flex justify-content-end">
             <button class="btn btn-danger btn-sm mt-2">Cancel Transaction</button>


         </div>
       </div>
      
        <div class="table-wrapper">




       </div>
      
        
    </div>

      

      <hr style="border: 1px solid #ccc; width: 100%; margin: 5px 0;">


      <ul class="nav nav-pills" id="pills-tab" role="tablist">
       <li class="nav-item" role="presentation">
         <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Guest Information</button>
       </li>

       <li class="nav-item" role="presentation">
         <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Request History</button>
       </li>

       <li class="nav-item" role="presentation">
         <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Payment History</button>
       </li>

       <?php
         // Uncomment if needed
         // if (is_null($flightId)) {
       ?>
       <!-- 
       <li class="nav-item" role="presentation">
         <button class="nav-link" id="pills-flight-tab" data-bs-toggle="pill" data-bs-target="#pills-flight" type="button" role="tab" aria-controls="pills-flight" aria-selected="false">Flight Details</button>
       </li>
       -->
       <?php
         // }
       ?>
    </ul>

    <div class="tab-content" id="pills-tabContent">
       <!-- Guest Table -->
       <?php include 'agent-guestTable.php'; ?>
       <?php include 'agent-requestTable.php'; ?>
       <?php include 'agent-paymentTable.php'; ?>
       
       <?php 
       // include 'agent-flightTable.php'; 
       ?>   

    </div>
   </div>
 </div>

    <?php require "../Agent Section/includes/scripts.php"; ?>         
 </body>
</html>
