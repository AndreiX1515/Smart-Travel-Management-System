<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Table</title>
  <?php include "Agent Section/includes/head.php"; ?>
  <link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
  <style>
    body { padding: 20px; }
    .tabulator { font-size: 14px; }
  </style>
</head>
<body>

<h2>Booking Records</h2>

<div id="flight-table"></div>

<script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

<script>
fetch('getFlights.php')
  .then(response => response.json())
  .then(data => {
    if (data.length === 0) {
      console.error("No data found!");
      return;
    }

    console.log("Fetched Data:", data); // Debugging: Log the fetched data

    // 1. Basic columns
    let columns = [
      {title: "", field: "isActive", frozen: true},
      {title: "TEAM OP", field: "TeamOP", frozen: true},
      {title: "ORIGIN", field: "origin"},
      {
        title: "FLIGHT DATE",
        columns: [
          {title: "START", field: "Start"},
          {title: "END", field: "End"}
        ]
      },
      {title: "AVAILABLE SEATS", field: "AvailSeats", hozAlign: "center"},
      {title: "ADDITIONAL SEATS", field: "AdditionalSeats", hozAlign: "center"},
      {title: "AIR + LAND", field: "FlightSeat"},
      {title: "LAND ONLY", field: "landOnly"},
      {title: "WHOLESALE PRICE", field: "wholesalePrice"},
      {title: "RETAIL PRICE", field: "RetailPrice"},
      {title: "LAND PRICE", field: "landPrice"}
    ];

    // 2. Dynamically create agent columns
    const sample = data[0];
    const agentCols = {};

    console.log("Sample Data:", sample); // Debugging: Log the first row to check all available fields

    Object.keys(sample).forEach(key => {
    const match = key.match(/^(.+)_([A|L]O)$/); // Matching agent columns like 'agentCode_AL' and 'agentCode_LO'
    
    if (match) {
        const agent = match[1];  // Extract the agent code (e.g., "Agent1")
        const type = match[2];   // Extract the type (either "AL" or "LO")

        if (!agentCols[agent]) {
            agentCols[agent] = {
                title: agent.replace(/_/g, ' '),  // Use the agent code as the title
                columns: []  // Create a subcolumns array
            };
        }

        // Ensure both A.L and L.O are always added
        if (type === "AL" && !agentCols[agent].columns.some(col => col.title === "A.L")) {
            agentCols[agent].columns.push({
                title: "A.L",
                field: key,
                hozAlign: "center"
            });
        }

        if (type === "LO" && !agentCols[agent].columns.some(col => col.title === "L.O")) {
            agentCols[agent].columns.push({
                title: "L.O",
                field: key,
                hozAlign: "center"
            });
        }
    }
});


    // Debugging: Log final agent columns to ensure both A.L and L.O are being added
    console.log("Agent Columns:", agentCols);

    // 3. Push agent columns to the table
    columns = columns.concat(Object.values(agentCols));

    // Debugging: Log final columns to be used in the table
    console.log("Final Columns:", columns);

    // 4. Initialize Tabulator
    new Tabulator("#flight-table", {
      data,
      layout: "fitDataStretch",
      columns,
      responsiveLayout: true,
      height: "500px",
    });
  })
  .catch(error => {
    console.error("Error fetching flight data:", error);
  });
</script>








</body>
</html>
