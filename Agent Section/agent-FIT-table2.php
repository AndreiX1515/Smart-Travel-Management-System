<?php 
session_start();
require "../conn.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-FIT.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar copy.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="body-container">
  <?php include "../Agent Section/includes/sidebar copy.php"; ?>

  <div class="main-content-container">
    <div class="navbar">
      <h5>Transactions</h5>
    </div>

    <div class="main-content">
      <div class="table-container">
          <table id="product-table" class="product-table mt-2">
            <thead>
              <tr>
                <th>Transaction No</th>
                <th>Contact Person Info</th>
                <th>Contact Details</th>
                <th>Package Name</th>
                <th>No. of Nights</th>
                <th>Hotel Name</th>
                <th>Room Type</th>
                <th>Check-in Date</th>
                <th>Check-out Date</th>
                <th>Total Guests</th>
                <th>Price</th>
                <th>Transaction Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $sql1 = "SELECT f.transactionNo AS `Transaction No`, 
                            CONCAT(f.lName, ', ', f.fName, ' ', 
                                  IF(f.mName IS NOT NULL AND f.mName != '', CONCAT(LEFT(f.mName, 1), '.'), ''), 
                                  IF(f.suffix IS NOT NULL AND f.suffix != 'N/A', CONCAT(' ', f.suffix), '')) AS `Contact Name`,
                            CONCAT(f.countryCode, ' ', f.contactNo) AS `Contact Details`,
                            fp.packageName AS `Package Name`, DATEDIFF(f.returnDate, f.startDate) AS `No. of Nights`,
                            fh.hotelName AS `Hotel Name`, fr.rooms AS `Room Type`, f.startDate AS `Check-in Date`,
                            f.returnDate AS `Check-out Date`, f.pax AS `Total Guests`, f.phpPrice AS `Price`,
                            f.bookingDate AS `Transaction Date`,f.status AS `Status`
                        FROM fit f
                        JOIN fitpackage fp ON fp.packageId = f.packageId
                        JOIN fithotel fh ON fh.hotelId = f.hotelId
                        JOIN fitrooms fr ON fr.roomId = f.roomId";

                $res1 = $conn->query($sql1);

                if ($res1->num_rows > 0) 
                {
                  while ($row = $res1->fetch_assoc()) 
                  {
                    $statusClass = '';
                    switch ($row['Status']) 
                    {
                      case 'Confirmed':
                          $statusClass = 'bg-success text-white';
                          break;
                      case 'Cancelled':
                          $statusClass = 'bg-danger text-white';
                          break;
                      case 'Pending':
                          $statusClass = 'bg-warning text-dark';
                          break;
                      default:
                          $statusClass = 'bg-secondary text-white';
                    }

                    echo "<tr>
                            <td>{$row['Transaction No']}</td>
                            <td>{$row['Contact Name']}</td>
                            <td>{$row['Contact Details']}</td>
                            <td>{$row['Package Name']}</td>
                            <td>{$row['No. of Nights']}</td>
                            <td>{$row['Hotel Name']}</td>
                            <td>{$row['Room Type']}</td>
                            <td>{$row['Check-in Date']}</td>
                            <td>{$row['Check-out Date']}</td>
                            <td style='text-align: center; font-weight: bold;'>{$row['Total Guests']}</td>
                            <td>{$row['Price']}</td>
                            <td>{$row['Transaction Date']}</td>
                            <td><span class='badge p-2 rounded-pill {$statusClass}'>{$row['Status']}</span></td>
                          </tr>";
                  }
                } 
                else 
                {
                  echo "<tr><td colspan='13'>No bookings found</td></tr>";
                }
              ?>
            </tbody>
          </table>
      </div>
      

    </div>
  </div>

</div>


<?php require "../Agent Section/includes/scripts.php"; ?>

<script>
function toggleSubMenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    const sectionTitle = submenu.previousElementSibling;
    const chevron = sectionTitle.querySelector('.chevron-icon'); 

    // Check if the submenu is already open
    const isOpen = submenu.classList.contains('open');

    // If it's open, we need to close it, and reset the chevron
    if (isOpen) {
        submenu.classList.remove('open');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        // First, close all open submenus and reset all chevrons
        const allSubmenus = document.querySelectorAll('.submenu');
        const allChevrons = document.querySelectorAll('.chevron-icon');
        
        allSubmenus.forEach(sub => {
            sub.classList.remove('open');
        });

        allChevrons.forEach(chev => {
            chev.style.transform = 'rotate(0deg)';
        });

        // Now, open the current submenu and rotate its chevron
        submenu.classList.add('open');
        chevron.style.transform = 'rotate(180deg)';
    }
}
</script>

  </body>
</html>