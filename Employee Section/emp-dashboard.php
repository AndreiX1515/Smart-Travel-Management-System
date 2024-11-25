<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Dashboard</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">
    
</head>
<body>


<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container bg-body">
   <?php include '../Employee Section/includes/emp-navbar.php' ?>

   <div class="main-content">
    <div class="counts-wrapper">

      <!-- CARD 1 -->
      <div class="card border-0">
        <div class="header">
            <h6 class="text-secondary fw-600">Current Transaction</h6>
        </div>
    
        <div class="card-content px-3">
          <div class="row">
            <div class="col-md-5 d-flex flex-row">
                <div class="card-icon icon-blue">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="side-content d-flex flex-column">
                    <h5>0</h5>
                    <p>TOTAL TRANSACTIONS</p>
                </div>
            </div>
       
           <div class="col-md-5 d-flex flex-row">
               <div class="card-icon icon-green">
                 <i class="fas fa-check-circle"></i>
               </div>
               <div class="side-content d-flex flex-column">
                   <h5>0</h5>
                   <p>COMPLETED</p>
               </div>
           </div>
         </div>

         <div class="row">
           <div class="col-md-5 d-flex flex-row">
               <div class="card-icon icon-yellow">
                 <i class="fas fa-exclamation-triangle"></i>
               </div>
               <div class="side-content d-flex flex-column">
                   <h5>0</h5>
                   <p>PENDING</p>
               </div>
           </div>
      
          <div class="col-md-5 d-flex flex-row">
              <div class="card-icon icon-red">
                <i class="fas fa-times-circle"></i>
              </div>
              <div class="side-content d-flex flex-column">
                  <h5>0</h5>
                  <p>CANCELLED</p>
              </div>
          </div>
        </div>
      </div>
    </div>
  
    <!-- CARD 2 -->
    <div class="card border-0" >
     <div class="header">
         <h6 class="text-secondary fw-600">Transaction History</h6>
     </div>
 
     <div class="card-content px-3">
       <div class="row">
         <div class="col-md-5 d-flex flex-row">
             <div class="card-icon icon-blue">
                 <i class="fas fa-calendar-alt"></i>
             </div>
             <div class="side-content d-flex flex-column">
                 <h5>0</h5>
                 <p>PAST</p>
             </div>
         </div>
    
        <div class="col-md-5 d-flex flex-row">
            <div class="card-icon icon-gray">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="side-content d-flex flex-column">
                <h5>0</h5>
                <p>CURRENT</p>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-5 d-flex flex-row">
            <div class="card-icon icon-yellow">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="side-content d-flex flex-column">
                <h5>0</h5>
                <p>ON GOING</p>
            </div>
        </div>
   
       <div class="col-md-5 d-flex flex-row">
           <div class="card-icon icon-green">
             <i class="fas fa-times-circle"></i>
           </div>
           <div class="side-content d-flex flex-column">
               <h5>0</h5>
               <p>CONFIRMED</p>
           </div>
       </div>
     </div>
   
    </div>
   </div>

   <!-- CARD 3 -->
   <div class="card border-0">
    <div class="header">
        <h6 class="text-secondary fw-600">On Due</h6>
    </div>

    <div class="card-content px-3">
      <div class="row">
        <div class="col-md-5 d-flex flex-row">
            <div class="card-icon icon-blue">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="side-content d-flex flex-column">
                <h5>0</h5>
                <p>5 DAYS</p>
            </div>
        </div>
   
       <div class="col-md-5 d-flex flex-row">
           <div class="card-icon icon-green">
             <i class="fas fa-check-circle"></i>
           </div>
           <div class="side-content d-flex flex-column">
               <h5>0</h5>
               <p>10 DAYS</p>
           </div>
       </div>
     </div>

      <div class="row">
        <div class="col-md-5 d-flex flex-row">
            <div class="card-icon icon-yellow">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="side-content d-flex flex-column">
                <h5>0</h5>
                <p>15 DAYS</p>
            </div>
        </div>
   
       <div class="col-md-5 d-flex flex-row">
           <div class="card-icon icon-red">
             <i class="fas fa-times-circle"></i>
           </div>
           <div class="side-content d-flex flex-column">
               <h5>0</h5>
               <p>30 DAYS</p>
           </div>
       </div>
     </div>
   
    </div>
 </div>

  <!-- CARD 4 -->
  <div class="card border-0">
   <div class="header d-flex justify-content-between align-items-center mb-2">
    <h6 class="text-secondary">Daily Currency Conversion</h6>
    <a href="" style="font-size: 12px; text-decoration: none;">View History</a>
   </div>

   <div class="card-body-currency">
    <div class="currency-cards">
      <div class="currency-card">
          <div class="flag-icon-wrapper">
              <img src="../assets/images/Flags/english-flag.png" alt="">
              <h6>USD</h6>

              <h6>$1</h6>
          </div>
      </div>

        <div class="icon-wrapper">
            <i class="fas fa-exchange-alt"></i>
        </div>

        <div class="currency-card">
          <div class="flag-icon-wrapper">
              <img src="../assets/images/Flags/philippines (2).png" alt="">
              <h6>PHP</h6>

              <h6>$1</h6>
          </div>
        </div>

        <div class="currency-card">
          <div class="flag-icon-wrapper">
              <img src="../assets/images/Flags/korean-flag.png" alt="">
              <h6>KOR</h6>

              <h6>$1</h6>
          </div>
        </div>


        <div class="currency-card">
           <div class="flag-icon-wrapper">
              <img src="../assets/images/Flags/european.png" alt="">
              <h6>EUR</h6>

              <h6>$1</h6>
          </div>
        </div>
    </div>
</div>

   
   




  </div>
</div>
 
<div class="header-wrapper">
   <div class="price-table-wrapper">
      <div class="header p-3">
           <h6 class="text-secondary">Price</h6>
     </div>

     <!-- <div class="price-table-container">
        <table class="price-table">
         <thead>
             <tr>
                 <th>Wholesale Price</th>
                 <th>Retail Price</th>
                 <th>Land Arrangement</th>
             </tr>
         </thead>
         <tbody>
             <tr>
                 <td>Basic</td>
                 <td>$19.99</td>
                 <td>5 Features</td>
             </tr>
             <tr>
                 <td>Standard</td>
                 <td>$49.99</td>
                 <td>10 Features</td>
             </tr>
             <tr>
                 <td>Premium</td>
                 <td>$99.99</td>
                 <td>Unlimited Features</td>
             </tr>
         </tbody>
     </table>
  </div> -->

 </div>


  <div class="pending-wrapper">
    <div class="header p-3">
     <h6 class="text-secondary">Requests</h6>
    </div>


  </div>

  <div class="Payment-wrapper">
    <div class="header p-3">
      <h6 class="text-secondary">Payment</h6>
    </div>

    <div class="table-container">



    </div>
    
  </div>
</div>

     
<div class="main-table-wrapper-one">
  <div class="table-info-container">
    <div class="header p-3 d-flex flex-row justify-content-between align-items-center">
      <h6 class="text-secondary">Flights</h6>

      <div class="end-part">
       <div class="legend-guides">


       </div>

       <button class="btn btn-primary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></button>

      </div>
    </div>

    <div class="info-table-container">
     <table class="info-table">
      <thead>
          <tr>
              <th rowspan="2">TEAM OP</th>
              <th rowspan="2">ORIGIN</th>
              <th colspan="2">FLIGHT DATE</th> <!-- Flight Date columns -->
              <th rowspan="2">FLIGHT SEAT</th>
              <th rowspan="2" style="font-size: 10px;">AVAILABLE SEATS</th>
              <th rowspan="2" style="font-size: 10px;">ADDITIONAL SEATS</th>
              <th rowspan="2">AIR + LAND</th>
              <th rowspan="2">LAND ONLY</th>
              <th rowspan="2">WHOLESALE PRICE</th>
              <th rowspan="2">RETAIL PRICE</th>
              <th rowspan="2">LAND ARRANGEMENT</th>

              <th colspan="2" data-bs-toggle="tooltip" title="A1">A1</th> <!-- A1 columns -->
              <th colspan="2" data-bs-toggle="tooltip" title="A2">A2</th> <!-- A2 columns -->
              <th colspan="2" data-bs-toggle="tooltip" title="A3">A3</th> <!-- A3 columns -->
              <th colspan="2" data-bs-toggle="tooltip" title="A4">A4</th> <!-- A4 columns -->
              <th colspan="2" data-bs-toggle="tooltip" title="A5">A5</th> <!-- A5 columns -->
              <th colspan="2" data-bs-toggle="tooltip" title="A6">A6</th> <!-- A6 columns -->
              <th colspan="2" data-bs-toggle="tooltip" title="A7">A7</th> <!-- A7 columns -->
              <th colspan="2" data-bs-toggle="tooltip" title="A8">A8</th> <!-- A8 columns -->
              <th colspan="2" data-bs-toggle="tooltip" title="A9">A9</th> <!-- A9 columns -->
              <th colspan="2" data-bs-toggle="tooltip" title="A10">A10</th> <!-- A10 columns -->

          </tr>
          <tr>
              <th>Start</th>
              <th>End</th>
              <!-- A1, A2, A3, A4, A5, A6, A7 Sub Headers -->
              <th>A.L</th>
              <th>L.O</th>

              <th>A.L</th>
              <th>L.O</th>

              <th>A.L</th>
              <th>L.O</th>

              <th>A.L</th>
              <th>L.O</th>

              <th>A.L</th>
              <th>L.O</th>

              <th>A.L</th>
              <th>L.O</th>

              <th>A.L</th>
              <th>L.O</th>

              <th>A.L</th>
              <th>L.O</th>

              <th>A.L</th>
              <th>L.O</th>

              <th>A.L</th>
              <th>L.O</th>
          </tr>
      </thead>
      <tbody>
         <tr>
             <td>Anna</td>
             <td>Manila</td>
             <td>2024. 12. 4</td>
             <td>2024. 12. 4</td>
             <td>2</td>
             <td>1</td>
             <td>15</td> <!-- Random number for A.L1 -->
             <td>8</td>  <!-- Random number for L.O1 -->
             <td>20</td> <!-- Random number for A.L2 -->

             <td>P28,734.88</td>
             <td>P28,734.88</td>
             <td>P28,734.88</td>

            
             <td>12</td> <!-- Random number for L.O2 -->
             <td>30</td> <!-- Random number for A.L3 -->
             <td>18</td> <!-- Random number for L.O3 -->
             <td>22</td> <!-- Random number for A.L4 -->
             <td>14</td> <!-- Random number for L.O4 -->
             <td>35</td> <!-- Random number for A.L5 -->
             <td>28</td> <!-- Random number for L.O5 -->
             <td>40</td> <!-- Random number for A.L6 -->
             <td>33</td> <!-- Random number for L.O6 -->
             <td>50</td> <!-- Random number for A.L7 -->
             <td>42</td> <!-- Random number for L.O7 -->
             <td>28</td> <!-- Random number for L.O5 -->
             <td>40</td> <!-- Random number for A.L6 -->
             <td>33</td> <!-- Random number for L.O6 -->
             <td>33</td> <!-- Random number for L.O6 -->
             <td>50</td> <!-- Random number for A.L7 -->
             <td>42</td> <!-- Random number for L.O7 -->
             <td>28</td> <!-- Random number for L.O5 -->
             <td>40</td> <!-- Random number for A.L6 -->
             <td>33</td> <!-- Random number for L.O6 -->
         </tr>

         <tr>
          <td>Anna</td>
          <td>Manila</td>
          <td>2024. 12. 4</td>
          <td>2024. 12. 4</td>
          <td>2</td>
          <td>1</td>
          <td>15</td> <!-- Random number for A.L1 -->
          <td>8</td>  <!-- Random number for L.O1 -->
          <td>20</td> <!-- Random number for A.L2 -->

          <td>P28,734.88</td>
          <td>P28,734.88</td>
          <td>P28,734.88</td>

         
          <td>12</td> <!-- Random number for L.O2 -->
          <td>30</td> <!-- Random number for A.L3 -->
          <td>18</td> <!-- Random number for L.O3 -->
          <td>22</td> <!-- Random number for A.L4 -->
          <td>14</td> <!-- Random number for L.O4 -->
          <td>35</td> <!-- Random number for A.L5 -->
          <td>28</td> <!-- Random number for L.O5 -->
          <td>40</td> <!-- Random number for A.L6 -->
          <td>33</td> <!-- Random number for L.O6 -->
          <td>50</td> <!-- Random number for A.L7 -->
          <td>42</td> <!-- Random number for L.O7 -->
          <td>28</td> <!-- Random number for L.O5 -->
          <td>40</td> <!-- Random number for A.L6 -->
          <td>33</td> <!-- Random number for L.O6 -->
          <td>33</td> <!-- Random number for L.O6 -->
          <td>50</td> <!-- Random number for A.L7 -->
          <td>42</td> <!-- Random number for L.O7 -->
          <td>28</td> <!-- Random number for L.O5 -->
          <td>40</td> <!-- Random number for A.L6 -->
          <td>33</td> <!-- Random number for L.O6 -->
      </tr>

      <tr>
       <td>Anna</td>
       <td>Manila</td>
       <td>2024. 12. 4</td>
       <td>2024. 12. 4</td>
       <td>2</td>
       <td>1</td>
       <td>15</td> <!-- Random number for A.L1 -->
       <td>8</td>  <!-- Random number for L.O1 -->
       <td>20</td> <!-- Random number for A.L2 -->

       <td>P28,734.88</td>
       <td>P28,734.88</td>
       <td>P28,734.88</td>

      
       <td>12</td> <!-- Random number for L.O2 -->
       <td>30</td> <!-- Random number for A.L3 -->
       <td>18</td> <!-- Random number for L.O3 -->
       <td>22</td> <!-- Random number for A.L4 -->
       <td>14</td> <!-- Random number for L.O4 -->
       <td>35</td> <!-- Random number for A.L5 -->
       <td>28</td> <!-- Random number for L.O5 -->
       <td>40</td> <!-- Random number for A.L6 -->
       <td>33</td> <!-- Random number for L.O6 -->
       <td>50</td> <!-- Random number for A.L7 -->
       <td>42</td> <!-- Random number for L.O7 -->
       <td>28</td> <!-- Random number for L.O5 -->
       <td>40</td> <!-- Random number for A.L6 -->
       <td>33</td> <!-- Random number for L.O6 -->
       <td>33</td> <!-- Random number for L.O6 -->
       <td>50</td> <!-- Random number for A.L7 -->
       <td>42</td> <!-- Random number for L.O7 -->
       <td>28</td> <!-- Random number for L.O5 -->
       <td>40</td> <!-- Random number for A.L6 -->
       <td>33</td> <!-- Random number for L.O6 -->
   </tr>

   <tr>
    <td>Anna</td>
    <td>Manila</td>
    <td>2024. 12. 4</td>
    <td>2024. 12. 4</td>
    <td>2</td>
    <td>1</td>
    <td>15</td> <!-- Random number for A.L1 -->
    <td>8</td>  <!-- Random number for L.O1 -->
    <td>20</td> <!-- Random number for A.L2 -->

    <td>P28,734.88</td>
    <td>P28,734.88</td>
    <td>P28,734.88</td>

   
    <td>12</td> <!-- Random number for L.O2 -->
    <td>30</td> <!-- Random number for A.L3 -->
    <td>18</td> <!-- Random number for L.O3 -->
    <td>22</td> <!-- Random number for A.L4 -->
    <td>14</td> <!-- Random number for L.O4 -->
    <td>35</td> <!-- Random number for A.L5 -->
    <td>28</td> <!-- Random number for L.O5 -->
    <td>40</td> <!-- Random number for A.L6 -->
    <td>33</td> <!-- Random number for L.O6 -->
    <td>50</td> <!-- Random number for A.L7 -->
    <td>42</td> <!-- Random number for L.O7 -->
    <td>28</td> <!-- Random number for L.O5 -->
    <td>40</td> <!-- Random number for A.L6 -->
    <td>33</td> <!-- Random number for L.O6 -->
    <td>33</td> <!-- Random number for L.O6 -->
    <td>50</td> <!-- Random number for A.L7 -->
    <td>42</td> <!-- Random number for L.O7 -->
    <td>28</td> <!-- Random number for L.O5 -->
    <td>40</td> <!-- Random number for A.L6 -->
    <td>33</td> <!-- Random number for L.O6 -->
</tr>

<tr>
 <td>Anna</td>
 <td>Manila</td>
 <td>2024. 12. 4</td>
 <td>2024. 12. 4</td>
 <td>2</td>
 <td>1</td>
 <td>15</td> <!-- Random number for A.L1 -->
 <td>8</td>  <!-- Random number for L.O1 -->
 <td>20</td> <!-- Random number for A.L2 -->

 <td>P28,734.88</td>
 <td>P28,734.88</td>
 <td>P28,734.88</td>


 <td>12</td> <!-- Random number for L.O2 -->
 <td>30</td> <!-- Random number for A.L3 -->
 <td>18</td> <!-- Random number for L.O3 -->
 <td>22</td> <!-- Random number for A.L4 -->
 <td>14</td> <!-- Random number for L.O4 -->
 <td>35</td> <!-- Random number for A.L5 -->
 <td>28</td> <!-- Random number for L.O5 -->
 <td>40</td> <!-- Random number for A.L6 -->
 <td>33</td> <!-- Random number for L.O6 -->
 <td>50</td> <!-- Random number for A.L7 -->
 <td>42</td> <!-- Random number for L.O7 -->
 <td>28</td> <!-- Random number for L.O5 -->
 <td>40</td> <!-- Random number for A.L6 -->
 <td>33</td> <!-- Random number for L.O6 -->
 <td>33</td> <!-- Random number for L.O6 -->
 <td>50</td> <!-- Random number for A.L7 -->
 <td>42</td> <!-- Random number for L.O7 -->
 <td>28</td> <!-- Random number for L.O5 -->
 <td>40</td> <!-- Random number for A.L6 -->
 <td>33</td> <!-- Random number for L.O6 -->
</tr>

      </tbody>
  </table>
  
  
 </div>
 


     
  </div>
</div>

   <!-- <div class="main-table-wrapper-two">
     <div class="table-counts-container">
       <div class="header p-3">
         <h6 class="text-black">Count Per Flight</h6>

       </div>

     </div>
  </div> -->
 </div>
</div>

<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
