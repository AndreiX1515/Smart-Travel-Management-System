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

    <div class="container">
      <form action="bookingform-code.php" method="post">
      <div class="card">
          <div class="card-header py-3 px-3">
            Personal Information
          </div>
          <div class="card-body mt-3">
            <div>
              <div class="row g-2">
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="fname" placeholder="First Name">
                    <label for="floatingInput">First Name <small class="text-danger">*</small></label>
                  </div>
                </div>

                <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" name="lname" placeholder="Last Name">
                      <label for="floatingInput">Last Name <small class="text-danger">*</small> </label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" name="mname" placeholder="Middle Name">
                      <label for="floatingInput">Middle Name <small class="text-danger">*</small> </label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" name="suffix" placeholder="Suffix">
                      <label for="floatingInput">Suffix <small class="text-secondary fw-lighter">(Optional)</small></label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" name="contactNo" placeholder="Contact No">
                      <label for="floatingInput">Contact No.</label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Email">
                      <label for="floatingInput">Email</label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" name="houseNo" placeholder="HouseNo">
                      <label for="floatingInput">House No. <small class="text-danger">*</small></label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" name="street" placeholder="Street">
                      <label for="floatingInput">Street</label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" name="subdivision" placeholder="Subdivision">
                      <label for="floatingInput">Subdivision </label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" name="barangay" placeholder="Barangay">
                      <label for="floatingInput">Barangay </label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" name="city" placeholder="City">
                      <label for="floatingInput">City </label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingInput" name="country" placeholder="Country">
                      <label for="floatingInput">Country </label>
                    </div>
                  </div>
            </div>

        <div class="row g-2 pt-3">
            <div class="col-md-2">
              <div class="form-floating">
                <input type="number" class="form-control" id="floatingInput" name="age" placeholder="age">
                <label for="floatingInput">Age <small class="text-danger">*</small></label>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-floating">
                <input type="date" class="form-control" id="floatingInput" name="birthdate" placeholder="Date of Birthdate">
                <label for="floatingInput">Birthdate <small class="text-danger">*</small></label>
              </div>
            </div>

             <!-- Form Group for Dropdown -->
             <div class="col-md-4 mb-4 pb-2">
                <!-- Enhanced Dropdown Component -->
                <div class="form-outline">
                    <select id="enhancedDropdown" class="form-select" name="nationality">
                        <option selected disabled>Nationality <small class="text-danger">*</small></option>
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
                    <select id="enhancedDropdown" class="form-select" name="gender">
                        <option selected disabled>Gender <small class="text-danger">*</small></option>
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
                      <select id="enhancedDropdown" class="form-select" name="packageName" required>
                          <option selected disabled>Package Name</option>
                          <option value="Winter Tour Package">Winter Tour Package</option>
                          <option value="Spring Tour Package">Spring Tour Package</option>
                          <option value="Summer Tour Package">Summer Tour Package</option>
                          <option value="Autumn Tour Package">Autumn Tour Package</option>
                          <option value="Cherry Blossom Tour Package">Cherry Blossom Tour Package</option>
                          <option value="Regular Tour Package">Regular Tour Package</option>
                          <option value="Busan Tour Package">Busan Tour Package</option>
                      </select>
                    </div>
                </div>

                <div class="col-md-4 mb-4 pb-2">
                  <div>
                    <h5 class="h6 mt-2">Passport No.</h5>
                  </div>
                  <!-- Enhanced Dropdown Component -->
                  <div class="form-floating">
                    <input type="text" class="form-control" name="passportNo" id="floatingInput" >
                    <label for="floatingInput">Passport No.</label>
                  </div>
                </div>

                <div class="col-md-4 mb-4 pb-2">
                  <div>
                    <h5 class="h6 mt-2">Visa Status</h5>
                  </div>
                  <!-- Enhanced Dropdown Component -->
                  <div class="form-floating">
                    <input type="text" class="form-control" name="visaStatus" id="floatingInput" >
                    <label for="floatingInput">Visa Status</label>
                  </div>
                </div>

                <div class="col-md-4 mb-4 pb-2">
                  <div>
                    <h5 class="h6 mt-2">Flight Date</h5>
                  </div>
                  <!-- Enhanced Dropdown Component -->
                  <div class="form-floating">
                  <div class="form-floating">
                      <input type="date" class="form-control" id="floatingInput" >
                      <label for="floatingInput">Flight Date</label>
                   </div>
                  </div>
                </div>
            </div>
          </div>

          <div class="row g-2 pt-2">
          <div class="col-md-12 d-flex justify-content-between">
            <button type="button" id="back-button" class="btn btn-primary px-3">Back</button>
            <button type="submit" id="submit-button" name="submit" class="btn btn-primary px-3">Submit</button>
          </div>
      </div>

          </div>
        </div>
      </div>
      </form>
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