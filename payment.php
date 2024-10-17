<?php
    include 'session_validate.php';
    require "conn.php";
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" name="viewport">

    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome Icon Kit CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <!-- Favicons -->
    <link href="assets/images/rsz_logo-tab.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link rel="stylesheet" href="assets\css\payment.css">
</head>

<body>
    <a href="client-dashboard.php" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container">
        <div class="subscription">
            <h3 class="ms-3">Payment Details</h3>

            <div class="section section-1 px-3">
                <div class="header-container d-flex flex-row justify-content-between mb-2">
                    <h4>Choose Payment Method</h4>
                </div>

                <div class="billing-options mt-4" >
                    <!-- GCash Payment Option -->
                    <!-- <div class="billing-card" data-value="monthly">
                        <div class="radiobutton-container">
                            <input type="radio" name="billing" checked>
                        </div>
                        <div class="payment-logo">
                            <img src="assets/images/GCash Logo/gcash-seeklogo.svg" alt="GCash Logo" width="100" height="80">
                            <span>Pay with GCash</span>
                        </div>
                    </div>
                
                    <!-- Maya Payment Option 
                    <div class="billing-card" data-value="yearly">
                        <div class="radiobutton-container">
                            <input type="radio" name="billing">
                        </div>
                        <div class="payment-logo">
                            <img src="assets/images/Maya_logo.svg" alt="Maya Logo" width="100" height="80">
                            <span>Pay with Maya</span>
                        </div>
                    </div>
                
                    <!-- Credit Card Payment Option 
                    <div class="billing-card" data-value="credit-card">
                        <div class="radiobutton-container">
                            <input type="radio" name="billing">
                        </div>
                        <div class="payment-logo" style="margin-top: 10px;">
                            <i class="fas fa-credit-card" style="font-size: 52px;"></i>
                            <span>Credit Card</span>
                        </div>
                    </div> -->
                
                    <!-- Bank Transfer Payment Option -->
                    <div class="billing-card" data-value="bank-transfer">
                        <div class="radiobutton-container">
                            <input type="radio" name="billing">
                        </div>
                        <div class="payment-logo" style="margin-top: 10px;">
                            <i class="fas fa-money-bill-transfer" style="font-size: 52px;"></i>
                            <span>Bank Transfer</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section section-1 px-3">
                <!-- <div class="header-container d-flex flex-row justify-content-between mb-2">
                    <h4 class="mb-2">Add payment method</h4>
                </div> -->
                
                <h3>Bank Details</h3>
                <div class="bank-detail-row">
                    <div class="bank-detail-col">
                        <label for="bank-name">Bank Name:</label>
                        <p id="bank-name">Banco De Oro (BDO)</p>
                    </div>
                    <div class="bank-detail-col">
                        <label for="account-name">Account Name:</label>
                        <p id="account-name">Hyung Sub Kim (Nickname: Jed Kim)</p>
                    </div>
                </div>
                <div class="bank-detail-row">
                    <div class="bank-detail-col">
                        <label for="account-number">Account Number (PH - Peso):</label>
                        <p id="account-number">00780020352</p>
                    </div>
                    <!-- <div class="bank-detail-col">
                        <label for="swift-code">SWIFT Code:</label>
                        <p id="swift-code">BOW12345</p>
                    </div> -->
                </div>

                <div class="bank-detail-row">
                    <div class="bank-detail-col">
                        <label for="account-number">Account Number (US - Dollar):</label>
                        <p id="account-number">10780018789</p>
                    </div>
                    <!-- <div class="bank-detail-col">
                        <label for="swift-code">SWIFT Code:</label>
                        <p id="swift-code">BOW12345</p>
                    </div> -->
                </div>
            </div>

        </div>
            <!-- Add-ons Section -->
            <!-- <div class="section section-3 px-3">
                <div class="header-container d-flex flex-row justify-content-between mb-2">
                    <h4>3. Select add-ons</h4>
                </div>

                <div class="addon">
                    <input type="checkbox" id="sso">
                    <label for="sso">
                        SAML single sign-on (SSO) - $3 per seat / month<br>
                        <span class="addon-desc">A secure way to control access to Calendly and simplify the sign-on process</span>
                    </label>
                </div>

            </div> -->
    
            <!-- Payment Method Section -->
            <!-- <div class="section section-4 px-3">
                <div class="header-container d-flex flex-row justify-content-between mb-2">
                    <h4>4. Add payment method</h4>
                </div>

                <div class="payment-method">
                    <input type="radio" name="payment" checked> Credit card <span class="secure">Secure payment</span><br>
                    <div class="card-info">
                        <img src="visa.png" alt="Visa">
                        <button>Save with link</button>
                    </div>
                </div>

            </div> -->
         
        <div class="order-summary">
            <div class="row">
                <div class="col-sm">
                    <div class="d-flex justify-content-between mb-1">
                        <?php
                            $transactNo = $_SESSION['transactNo'];
                            $sql1 = mysqli_query($conn, "
                                SELECT b.*, DATE_FORMAT(f.flightDepartureDate, '%M %d, %Y') AS onboardFlightSched, p.packageName 
                                FROM booking b 
                                JOIN guest g ON b.transactNo = g.transactNo
                                JOIN flight f ON g.flightId = f.flightId 
                                JOIN package p ON f.packageId = p.packageId 
                                WHERE b.transactNo = '$transactNo'
                            ");
                            
                            while ($res1 = mysqli_fetch_array($sql1)) 
                            {
                                $totalPrice = $res1['totalPrice'];
                                $formattedPrice = number_format($totalPrice, 2); // Format to 2 decimal places
                                $downpayment = $res1['pax'] * 1000;
                                $formattedDP = number_format($downpayment, 2); // Format to 2 decimal places
                            
                                // Get additional fields
                                $flightDate = $res1['onboardFlightSched'];
                                $packageName = $res1['packageName'];
                                $pax = $res1['pax'];
                                
                                // You can now use $flightDate and $packageName as needed
                            }
                            
                        ?>
                        <p class="mb-0"><strong>Package Name:</strong></p>
                        <p class="mb-0"><?php echo $packageName; ?></p> <!-- Added commas for better readability -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="d-flex justify-content-between mb-1">
                        <p class="mb-0"><strong>Total Number of Guest:</strong></p>
                        <p class="mb-0"><?php echo $pax; ?></p> <!-- Added commas for better readability -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="d-flex justify-content-between mb-1">
                        <p class="mb-0"><strong>Flight Date:</strong></p>
                        <p class="mb-0"><?php echo $flightDate; ?></p> <!-- Added commas for better readability -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="d-flex justify-content-between mb-1">
                        <p class="mb-0"><strong>Downpayment:</strong></p>
                        <p class="mb-0">Minimum ₱ <?php echo $formattedDP; ?></p> <!-- Added commas for better readability -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="d-flex justify-content-between mb-1">
                        <p class="mb-0">₱ 1,000 per Guest.</p> <!-- Added space for better readability -->
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm">
                    <div class="d-flex justify-content-between mb-1">
                        <p class="mb-0"><strong>Total:</strong></p>
                        <p class="mb-0">₱ <?php echo $formattedPrice; ?></p> <!-- Added commas for better readability -->
                    </div>
                </div>
            </div>

            <form action="payment-code.php" method="POST" enctype="multipart/form-data">
                <hr>
                <input type="hidden" value="<?php echo $_SESSION['transactNo']; ?>" name="transactNo">
                <input type="number" class="form-control" name="downpayment" min="<?php echo $downpayment; ?>" placeholder="Enter Downpayment Amount" required>
                <h6 class="mt-4">Attach Proof/Screenshot of transaction:</h6>
                <input type="file" id="attachment" class="attachment" name="proof" accept="image/*" required>
                <hr>

                <div class="row mt-4">
                    <div class="col-sm">
                        <div class="d-flex align-items-left mb-3"> <!-- Align items center for checkbox -->
                            <input type="checkbox" class="ms-1 me-3"> <!-- Added margin to the checkbox -->
                            <div class="checkbox-text">
                                <span>
                                By clicking this, I agree to Smart Travel <a href="#" class="terms-link">Terms & Conditions</a> and 
                                <a href="#" class="privacy-link">Privacy Policy</a>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="pay-button" name="pay">Pay Now</button>
                    </div>
                </div>
            </form>
        </div>
    </div> 
    
    
</body>
    <script src="heartbeat.js"></script>
    <script>
        document.querySelectorAll('.billing-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove "active" class from all billing cards
                document.querySelectorAll('.billing-card').forEach(item => item.classList.remove('active'));
                
                // Add "active" class to the clicked billing card
                card.classList.add('active');
                
                // Check the input radio button within the clicked card
                card.querySelector('input[type="radio"]').checked = true;
            });
        });



    </script>


    <!-- Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <!-- Main Bootstrap JS CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <!-- JQuery JS CDN-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>