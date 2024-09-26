<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- MDB UI Kit CDN
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet"> -->

    <!-- Font Awesome Icon Kit CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="assets\css\bookingform.css">
    
    
    <title>Document</title>

    <style>
      .form-floating .input-group > .form-control, 
      .form-floating .input-group > .form-select {
        height: calc(3.5rem + 2px); /* Adjusting height for floating label */
      }
      .form-floating .input-group-text {
        border-radius: 0.375rem 0 0 0;
      }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="" class="logo"><img src="assets/images/logo.png" alt="Logo"></a>
    </div>
    
    <div class="accent d-flex justify-content-center align-items-center">
        <h1 class="fw-bold">Booking</h1>
    </div>

    <!-- <div class="mt-2 mb-3 d-flex justify-content-end">
            <button class="btn btn-primary"><i class="fa-solid fa-plus fa-sm fa-fw mr-2 text-gray-400"></i></button>
        </div> -->

    <div class="container">
        <div class="card">
          <div class="card-header py-3 px-3">
            Personal Information
          </div>
          <div class="card-body mt-3">
            <div>
              <div class="row g-2">
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">First Name <small class="text-danger">*</small></label>
                  </div>
                </div>

                <div class="col-md-3">
                    <div class="form-floating">
                      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                      <label for="floatingInput">Last Name <small class="text-danger">*</small> </label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                      <label for="floatingInput">Middle Name <small class="text-danger">*</small> </label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
                      <label for="floatingInput">Suffix <small class="text-secondary fw-lighter">(Optional)</small></label>
                    </div>
                  </div>
            </div>

        <div class="row g-2 pt-3">
            <div class="col-md-2">
              <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Age</label>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-floating">
                <input type="date" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Birthdate</label>
              </div>
            </div>

             <!-- Form Group for Dropdown -->
             <div class="col-md-4 mb-4 pb-2">
                <!-- Enhanced Dropdown Component -->
                <div class="form-outline">
                    <select id="enhancedDropdown" class="form-select" required>
                        <option selected disabled>Nationality</option>
                        <option value="PH">Filipino</option>
                        <option value="AF">Afghan</option>
                        <option value="AM">Armenian</option>
                        <option value="AZ">Azerbaijani</option>
                        <option value="BH">Bahraini</option>
                        <option value="BD">Bangladeshi</option>
                        <option value="BT">Bhutanese</option>
                        <option value="BN">Bruneian</option>
                        <option value="KH">Cambodian</option>
                        <option value="CN">Chinese</option>
                        <option value="CY">Cypriot</option>
                        <option value="GE">Georgian</option>
                        <option value="IN">Indian</option>
                        <option value="ID">Indonesian</option>
                        <option value="IR">Iranian</option>
                        <option value="IQ">Iraqi</option>
                        <option value="IL">Israeli</option>
                        <option value="JP">Japanese</option>
                        <option value="KZ">Kazakhstani</option>
                        <option value="KP">North Korean</option>
                        <option value="KR">South Korean</option>
                        <option value="KW">Kuwaiti</option>
                        <option value="KG">Kyrgyzstani</option>
                        <option value="LA">Laotian</option>
                        <option value="LB">Lebanese</option>
                        <option value="MO">Macao</option>
                        <option value="MY">Malaysian</option>
                        <option value="MV">Maldivian</option>
                        <option value="MN">Mongolian</option>
                        <option value="MM">Myanmar (Burma)</option>
                        <option value="NP">Nepalese</option>
                        <option value="OM">Omani</option>
                        <option value="PK">Pakistani</option>
                        <option value="QA">Qatari</option>
                        <option value="RU">Russian</option>
                        <option value="SA">Saudi Arabian</option>
                        <option value="SG">Singaporean</option>
                        <option value="LK">Sri Lankan</option>
                        <option value="SY">Syrian</option>
                        <option value="TJ">Tajikistani</option>
                        <option value="TH">Thai</option>
                        <option value="TL">Timorese</option>
                        <option value="TM">Turkmen</option>
                        <option value="AE">Emirati</option>
                        <option value="UZ">Uzbekistani</option>
                        <option value="VN">Vietnamese</option</option>
                        <option value="YE">Yemeni</option>
                    </select>
                  </div>
              </div>

              <!-- Form Group for Dropdown -->
             <div class="col-md-3 mb-4 pb-2">
                <!-- Enhanced Dropdown Component -->
                <div class="form-outline">
                    <select id="enhancedDropdown" class="form-select" required>
                        <option selected disabled>Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                  </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container mt-3">
      <div class="card">
        <div class="card-header py-3 px-3">
          Booking Information
        </div>
        <div class="card-body">
          <div>
            <div class="row g-2">
                <div class="col-md-4 mb-4 pb-2">
                  <h5 class="h6 mt-2">Package Name</h5>
                  <!-- Enhanced Dropdown Component -->
                  <div class="form-outline">
                      <select id="enhancedDropdown" class="form-select" required>
                          <option selected disabled>Package Name</option>
                          <option value="Winter">Winter Package</option>
                          <option value="Spring">Spring Package</option>
                          <option value="Summer">Summer Package</option>
                          <option value="Autumn">Autumn Package</option>
                          <option value="Cherry Blossom">Cherry Blossom Package</option>
                          <option value="Regular Tour">Regular Tour Package</option>
                          <option value="Busan Tour">Busan Tour Package</option>
                      </select>
                    </div>
                </div>

                <div class="col-md-4 mb-4 pb-2">
                  <div>
                    <h5 class="h6 mt-2">Passport No.</h5>
                  </div>
                  <!-- Enhanced Dropdown Component -->
                  <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Passport No.</label>
                  </div>
                </div>

                <div class="col-md-4 mb-4 pb-2">
                  <div>
                    <h5 class="h6 mt-2">Flight Date</h5>
                  </div>
                  <!-- Enhanced Dropdown Component -->
                  <div class="form-floating">
                  <div class="form-floating">
                      <input type="date" class="form-control" id="floatingInput" placeholder="name@example.com">
                      <label for="floatingInput">Flight Date</label>
                   </div>
                  </div>
                </div>
            </div>
          </div>

          <div class="row g-2 pt-2">
          <div class="col-md-12 d-flex justify-content-between">
            <button type="button" id="back-button" class="btn btn-primary px-3">Back</button>
            <button type="button" id="submit-button" class="btn btn-primary px-3">Submit</button>
          </div>
      </div>

          <!-- <div class="row g-2 pt-3">
              <div class="col-md-4">
                <div class="form-floating">
                  <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                  <label for="floatingInput">Email address</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating">
                  <div class="input-group">
                    <span class="input-group-text">+63</span>
                    <input type="text" class="form-control" id="floatingPhone" placeholder="9123456789" pattern="\d*" maxlength="10" required>
                  </div>
                  
                </div>
                <div class="form-text">Please enter a 10-digit phone number.</div>
              </div>

              <div class="col-md-4">
                <div class="form-floating">
                  <input type="Password" class="form-control" id="floatingInput" placeholder="">
                  <label for="floatingInput">Password</label>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>

    <script>
        document.getElementById("submit-button").onclick = function () {
          location.href = "OTP.php";
        };

        document.getElementById("back-button").onclick = function () {
          location.href = "client-login.php";
        };


    </script>
  
    <!-- Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <!-- Main Bootstrap JS CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>
</html>