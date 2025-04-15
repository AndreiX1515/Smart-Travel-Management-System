<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <?php include "../Agent Section/includes/head.php"; ?>

  <link rel="stylesheet" href="../Agent Section/assets/css/agent-currency-conversion.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../Agent Section/assets/css/navbar-sidebar.css?v=<?php echo time(); ?>">
</head>

<body>

  <div class="body-container">
    <?php include "../Agent Section/includes/sidebar.php"; ?>

    <div class="main-content-container">
      <div class="navbar">
        <h5 class="title-page">Currency History</h5>
      </div>

      <div class="main-content">
        <div class="content-header">

        </div>

        <div class="content-body">
          <div class="table-container">
            <table id="currency-table" class="currency-table">
              <thead>
                <tr>
                  <th>Transaction ID</th>
                  <th>Contact Person Info</th>
                  <th>Contact Details</th>
                  <th>Branch Name</th>
                  <th>Flight Date</th>
                  <th>Total Pax</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>TXN00123</td>
                  <td>Kim Min-jun</td>
                  <td>minjun.kim@example.com</td>
                  <td>Seoul Central</td>
                  <td>2025-05-10</td>
                  <td>4</td>
                  <td><span class="badge confirmed">Confirmed</span></td>
                </tr>
                <tr>
                  <td>TXN00124</td>
                  <td>Lee Jisoo</td>
                  <td>jisoo.lee@example.com</td>
                  <td>Busan Branch</td>
                  <td>2025-05-12</td>
                  <td>2</td>
                  <td><span class="badge pending">Pending</span></td>
                </tr>
                <tr>
                  <td>TXN00125</td>
                  <td>Park Hyunwoo</td>
                  <td>hyunwoo.park@example.com</td>
                  <td>Incheon Intl</td>
                  <td>2025-05-15</td>
                  <td>6</td>
                  <td><span class="badge cancelled">Cancelled</span></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="table-footer">
            <div class="pagination-controls">
              <button id="prevPage" class="pagination-btn">Previous</button>
              <span id="pageInfo" class="page-info">Page 1 of 10</span>
              <button id="nextPage" class="pagination-btn">Next</button>
            </div>
          </div>
        </div>


      </div>
    </div>
  </div>


  <?php require "../Agent Section/includes/scripts.php"; ?>

</body>

</html>