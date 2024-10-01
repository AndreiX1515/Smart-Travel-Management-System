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
    
    <link rel="stylesheet" href="assets\css\register-1.css">
    
    
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
        <h1 class="fw-bold">Register</h1>
    </div>


    <div class="container">
      <form action="OTP-code.php" method="post">
        <!-- <div class="mt-2 mb-3 d-flex justify-content-end">
            <button class="btn btn-primary"><i class="fa-solid fa-plus fa-sm fa-fw mr-2 text-gray-400"></i></button>
        </div> -->
 
        <!-- <div class="pb-2">
            <h5>Personal Information</h5>
        </div> -->

        <!-- <div class="row g-2">
            <div class="col-md-3">
              <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" placeholder="First Name">
                <label for="floatingInput">First Name <small class="text-danger">*</small></label>
              </div>
            </div>

            <div class="col-md-3">
                <div class="form-floating">
                  <input type="text" class="form-control" id="floatingInput" placeholder="Last Name">
                  <label for="floatingInput">Last Name <small class="text-danger">*</small> </label>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-floating">
                  <input type="text" class="form-control" id="floatingInput" placeholder="Middle Name">
                  <label for="floatingInput">Middle Name <small class="text-danger">*</small> </label>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-floating">
                  <input type="text" class="form-control" id="floatingInput" placeholder="Suffix">
                  <label for="floatingInput">Suffix <small class="text-secondary fw-lighter">(Optional)</small></label>
                </div>
              </div>
        </div> -->

        <div class="row g-2 pt-3">
          <div class="col-md-4">
            <div class="form-floating">
              <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com">
              <label for="floatingInput">Email address</label>
            </div>
          </div>

          <!-- <div class="col-md-4">
            <div class="form-floating">
              <div class="input-group">
                <span class="input-group-text">+63</span>
                <input type="text" class="form-control" id="floatingPhone" placeholder="9123456789" pattern="\d*" maxlength="10" required>
              </div>
            </div>

            <div class="form-text">Please enter a 10-digit phone number.</div>
          </div> -->

          <div class="row-md-4">
            <div class="form-floating">
              <input type="Password" class="form-control" id="floatingInput" name="pass" placeholder="">
              <label for="floatingInput">Password</label>
            </div>
          </div>

        </div>

        <div class="row g-2 pt-3">
          <div class="col-md-12 d-flex justify-content-between">
            <button type="button" id="back-button" class="btn btn-primary px-3">Back</button>
            <button type="submit" id="submit-button" name="register" class="btn btn-primary px-3">Register</button>
          </div>
      </div>
      </form>
    </div>

    <!-- <script>
        document.getElementById("submit-button").onclick = function () {
          location.href = "OTP.php";
        };

        document.getElementById("back-button").onclick = function () {
          location.href = "client-login.php";
        };


    </script> -->
  
    <!-- Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <!-- Main Bootstrap JS CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>
</html>