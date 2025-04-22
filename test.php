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
<div id="booking-table"></div>

<script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script>

$.ajax({
  url: 'getBranches.php',
  type: 'GET',
  dataType: 'text', // Use 'text' first to inspect raw format
  success: function(response) {
    console.log("Raw getBranches.php response (as text):", response);
    console.log("typeof response:", typeof response);

    let agentColumns = [];

    try {
      // Parse the response from string to object
      const parsed = typeof response === 'string' ? JSON.parse(response) : response;
      console.log("Parsed agentColumns:", parsed);

      if (Array.isArray(parsed)) {
        if (typeof parsed[0] === 'string') {
          agentColumns = parsed;
          console.log("Agent columns as strings:", agentColumns);
        } else if (parsed[0]?.name) {
          agentColumns = parsed.map(agent => agent.name);
          console.log("Agent columns extracted from objects:", agentColumns);
        } else {
          console.warn("Array format unrecognized, defaulting to empty agent columns.");
        }
      } else {
        console.error("Agent columns response is not an array.");
      }
    } catch (e) {
      console.error("Error parsing agent columns:", e);
      return;
    }

    // Build dynamic agent columns
    const dynamicAgentColumns = agentColumns.map(agent => ({
      title: agent,
      field: agent,
      headerHozAlign: "center",
      hozAlign: "center"
    }));

    console.log("Dynamic agent columns:", dynamicAgentColumns);

    // Step 2: Fetch flight data
    $.ajax({
      url: 'getFlights.php',
      type: 'POST',
      data: { agentColumns: JSON.stringify(agentColumns) },
      dataType: 'json',
      success: function(data) {
        console.log("Flight data received:", data);

        const columns = [
          { title: "Active", field: "is_active" },
          { title: "Team OP", field: "TeamOP" },
          { title: "Origin", field: "origin" },
          { title: "Departure", field: "Start" },
          { title: "Return", field: "End" },
          { title: "Available", field: "AvailSeats" },
          { title: "Additional", field: "AdditionalSeats" },
          { title: "Air+Land", field: "Air+Land" },
          { title: "Land Only", field: "LandOnly" },
          { title: "Wholesale", field: "WholesalePrice" },
          { title: "Retail", field: "RetailPrice" },
          { title: "Land Price", field: "landPrice" },
          { title: "Package", field: "LandArrangement" },
        ];

        // Add the Express Thead section dynamically if agentColumns exist
        if (dynamicAgentColumns.length > 0) {
          columns.push({
            title: "Express Thead",
            columns: dynamicAgentColumns
          });
        }

        console.log("Final Tabulator Columns:", columns);

        new Tabulator("#booking-table", {
          data: data,
          layout: "fitColumns",
          responsiveLayout: "collapse",
          columns: columns
        });
      },
      error: function(xhr, status, error) {
        console.error("Error fetching flight data:", error);
        console.log("XHR response (getFlights.php):", xhr.responseText);
      }
    });
  },
  error: function(xhr, status, error) {
    console.error("Error fetching agent columns:", error);
    console.log("XHR response (getBranches.php):", xhr.responseText);
  }
});


</script>


</body>
</html>
