<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabulator Example</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://unpkg.com/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <!-- Tabulator CSS -->
    <link href="https://unpkg.com/tabulator-tables@6.3.1/dist/css/tabulator.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Flight Information</h2>
        <!-- This is where the table will be rendered -->
        <div id="info-table"></div>
    </div>

    <!-- jQuery and Tabulator JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/tabulator-tables@6.3.1/dist/js/tabulator.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Define the columns for Tabulator (Static Example)
            var columns = [
                { title: "TEAM OP", field: "teamOp", width: 150 },
                { title: "ORIGIN", field: "origin", width: 150 },
                { title: "FLIGHT DATE", columns: [  
                    { title: "START", field: "startDate", width: 100 },
                    { title: "END", field: "endDate", width: 100 }
                ] },
                { title: "AVAILABLE SEATS", field: "availSeats", width: 100 },
                { title: "ADDITIONAL SEATS", field: "additionalSeats", width: 100 },
                { title: "AIR + LAND", field: "airLand", width: 100 },
                { title: "LAND ONLY", field: "landOnly", width: 100 },
                { title: "WHOLESALE PRICE", field: "wholesalePrice", width: 120, formatter: "money" },
                { title: "RETAIL PRICE", field: "retailPrice", width: 120, formatter: "money" },
                { title: "LAND ARRANGEMENT PRICE", field: "landArrangement", width: 150, formatter: "money" },
                { title: "LAND PRICE", field: "landPrice", width: 120, formatter: "money" }
            ];

            // Static example data
            var tableData = [
                {
                    teamOp: "John Doe",
                    origin: "New York",
                    startDate: "2025-03-01",
                    endDate: "2025-03-10",
                    availSeats: 30,
                    additionalSeats: 5,
                    airLand: 200,
                    landOnly: 150,
                    wholesalePrice: 400,
                    retailPrice: 500,
                    landArrangement: 100,
                    landPrice: 50
                },
                {
                    teamOp: "Jane Smith",
                    origin: "London",
                    startDate: "2025-04-15",
                    endDate: "2025-04-25",
                    availSeats: 50,
                    additionalSeats: 10,
                    airLand: 300,
                    landOnly: 250,
                    wholesalePrice: 600,
                    retailPrice: 750,
                    landArrangement: 150,
                    landPrice: 80
                },
                {
                    teamOp: "Michael Brown",
                    origin: "Paris",
                    startDate: "2025-05-10",
                    endDate: "2025-05-20",
                    availSeats: 40,
                    additionalSeats: 7,
                    airLand: 250,
                    landOnly: 180,
                    wholesalePrice: 500,
                    retailPrice: 650,
                    landArrangement: 120,
                    landPrice: 70
                }
            ];

            // Initialize the Tabulator table
            var table = new Tabulator("#info-table", {
                height: "311px",  // Set table height
                columns: columns, // Define the columns
                data: tableData,  // Use the static data
                pagination: "local", // Enable local pagination
                paginationSize: 5, // Rows per page
                layout: "fitColumns", // Fit columns to table width
                responsiveLayout: "hide", // Hide columns on small screens
                tooltips: true, // Enable tooltips
            });
        });
    </script>
</body>
</html>
