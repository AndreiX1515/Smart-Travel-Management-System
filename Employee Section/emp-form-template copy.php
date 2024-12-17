<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Transactions</title>
    <?php include '../Employee Section/includes/emp-head.php' ?>
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Employee Section/assets/css/emp-sidebar-navbar.css?v=<?php echo time(); ?>">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f4f4f9;
        }

        /* Main Container */
        .container {
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        /* Button Container */
        .button-container {
            width: 100%;
            height: 10%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #116530;
            color: white;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .button-container button {
            background-color: #fff;
            color: #116530;
            border: 2px solid #116530;
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .button-container button:hover {
            background-color: #116530;
            color: #fff;
        }

        /* Hidden Div */
        .hidden-div {
            display: none;
            width: 100%;
            height: 20%;
            background-color: #e6f7ff;
            border: 1px solid #b8daff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        .hidden-div button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .hidden-div button:hover {
            background-color: #c82333;
        }
    </style>

</head>
<body>

<?php include '../Employee Section/includes/emp-sidebar.php' ?>

<!-- Main Container -->
<div class="main-container">
   <?php include '../Employee Section/includes/emp-navbar.php' ?>

   <div class="main-content">
   <div class="container">
        <!-- Main Button Container -->
        <div class="button-container">
            <button id="toggleButton">Show Div</button>
        </div>

        
    </div>

    
   </div>
</div>








<?php include '../Employee Section/includes/emp-scripts.php' ?>


</body>
</html>
