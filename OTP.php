<?php
  require 'conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Centered Div with Logo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets\css\otp.css">
  
  <style>
    
  </style>
</head>

<body>
  <div class="logo-container">
    <a href=""><img src="assets\images\logo.png" alt="Logo" class="logo"></a>
  </div>

  <div class="card p-2 text-center">
    <form action="OTP-code.php" method="post">
      <h6>An OTP has been sent <br> to verify your account</h6>

      <div> 
          <span>A code has been sent to</span> <small>*******@gmail.com</small> 
      </div>
      
      <div id="otp" class="inputs d-flex flex-row justify-content-center my-4"> 
          <!-- <input class="m-2 text-center form-control rounded" type="text" id="first" maxlength="1" /> 
          <input class="m-2 text-center form-control rounded" type="text" id="second" maxlength="1" /> 
          <input class="m-2 text-center form-control rounded" type="text" id="third" maxlength="1" /> 
          <input class="m-2 text-center form-control rounded" type="text" id="fourth" maxlength="1" /> 
          <input class="m-2 text-center form-control rounded" type="text" id="fifth" maxlength="1" /> 
          <input class="m-2 text-center form-control rounded" type="text" id="sixth" maxlength="1" /> -->
          <input type="text" name="otp" placeholder="Enter Verification Code"> 
      </div>
      
      <a class="link-opacity-5 mt-5" href="#">Resend</a>

      <div class="mt-4"> 
          <button class="btn btn-danger px-4 validate" name="validate">Validate</button> 
      </div>
    </form>
  </div>
 
  <script>
    document.addEventListener("DOMContentLoaded", function(event) {

    function OTPInput() {
        const inputs = document.querySelectorAll('#otp > *[id]');
            for (let i = 0; i < inputs.length; i++) { 
                inputs[i].addEventListener('keydown', function(event) { 
                    if (event.key==="Backspace" ) { 
                        inputs[i].value='' ; 
                        if (i !==0) 
                            inputs[i - 1].focus(); 
                        } else { 

                        if (i===inputs.length - 1 && inputs[i].value !=='' ) { 
                            return true; 

                        } else if (event.keyCode> 47 && event.keyCode < 58) { 
                            inputs[i].value=event.key; if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); 
                        } 
                        else if (event.keyCode> 64 && event.keyCode < 91) { 
                            inputs[i].value=String.fromCharCode(event.keyCode);

                            if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); 
                        } 
                    } 
                }); 
            } 
        } OTPInput(); 
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
