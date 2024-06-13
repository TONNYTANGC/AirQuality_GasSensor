<!DOCTYPE html>
<html>

<head>
  <!-- Set the page to refresh every 10 seconds -->
  <meta http-equiv="refresh" content="10">
  <!-- Link to the CSS file for styling -->
  <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
  <!-- Title of the web page -->
  <title>Air Quality Data</title>
  <!-- Load the Plotly.js library for creating charts -->
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
  <style>
    /* Center align the main heading */
    h1 {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* Style for the container holding the charts */
    .chart-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    /* Style for individual charts */
    .chart {
      width: calc(33.33% - 10px);
      margin-bottom: 10px;
    }

    /* Style for the air quality data table */
    .quality-table {
      border-collapse: collapse;
      width: 100%;
    }

    /* Style for table headers and cells */
    .quality-table th,
    .quality-table td {
      border: 1px #ddd;
      padding: 8px;
      text-align: left;
    }

    /* Background color for table headers */
    .quality-table th {
      background-color: #f2f2f2;
    }

    /* Style for chart headings */
    .chart-heading {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 5px;
    }
  </style>
</head>

<body>
  <!-- Main heading for the page -->
  <h1>Air Quality</h1>
  <!-- Container for the charts -->
  <div class="chart-container">
    <div id="humidityChart" class="chart">
      <h1 class="chart-heading">Humidity Chart</h1>
    </div>
    <div id="temperatureChart" class="chart">
      <h1 class="chart-heading">Temperature Chart</h1>
    </div>
    <div id="co2Chart" class="chart">
      <h1 class="chart-heading">CO2 Level Chart</h1>
    </div>
  </div>
  <br>
  <!-- Subheading for the latest quality data table -->
  <h1>Latest Quality Data (Last 20 Entries)</h1>
  <!-- Table for displaying the latest air quality data -->
  <table class="quality-table">
    <thead>
      <tr>
        <th>Timestamp</th>
        <th>Air Quality</th>
      </tr>
    </thead>
    <tbody id="qualityTableBody"></tbody>
  </table>

  <?php
  // Database connection parameters
  $servername = 'localhost';
  $dbname = 'air_quality';
  $username = 'root';
  $password = '';

  // Create connection to the database
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check if the connection failed
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to fetch the last 20 entries from the gassensor table
  $sql = "SELECT id, Humidity, Temperature, CO2lvl, Quality, time FROM gassensor ORDER BY id DESC LIMIT 20";
  $data = [];
  // Execute the query and process the result set
  if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
      // Format the time to a readable format
      $row_time = date('Y-m-d H:i:s', strtotime($row["time"]));
      // Add the row data to the data array
      $data[] = [
        'time' => $row_time,
        'humidity' => $row["Humidity"],
        'temperature' => $row["Temperature"],
        'co2lvl' => $row["CO2lvl"],
        'quality' => $row["Quality"]
      ];
    }
    // Free the result set
    $result->free();
  } else {
    echo "0 results";
  }
  // Close the database connection
  $conn->close();
  ?>

  <script>
    // Convert the PHP data array to a JavaScript object
    const data = <?php echo json_encode($data); ?>;

    // Extract individual data arrays for plotting
    const times = data.map(d => d.time);
    const humidity = data.map(d => d.humidity);
    const temperature = data.map(d => d.temperature);
    const co2lvl = data.map(d => d.co2lvl);
    const quality = data.map(d => d.quality);

    // Trace for humidity data
    const traceHumidity = {
      x: times,
      y: humidity,
      mode: 'lines',
      name: 'Humidity (%)',
      line: { color: 'blue' }
    };

    // Trace for temperature data
    const traceTemperature = {
      x: times,
      y: temperature,
      mode: 'lines',
      name: 'Temperature (°C)',
      line: { color: 'red' }
    };

    // Trace for CO2 level data
    const traceCO2 = {
      x: times,
      y: co2lvl,
      mode: 'lines',
      name: 'CO2 Level (ppm)',
      line: { color: 'green' }
    };

    // Plot the humidity chart
    Plotly.newPlot('humidityChart', [traceHumidity]);
    // Plot the temperature chart
    Plotly.newPlot('temperatureChart', [traceTemperature]);
    // Plot the CO2 level chart
    Plotly.newPlot('co2Chart', [traceCO2]);

    // Get the table body element for quality data
    const qualityTableBody = document.getElementById('qualityTableBody');
    // Clear any existing rows
    qualityTableBody.innerHTML = '';
    // Populate the table with the latest quality data
    data.forEach(item => {
      const formattedTime = new Date(item.time).toLocaleString();
      qualityTableBody.innerHTML += `<tr>
        <td>${formattedTime}</td>
        <td>${item.quality}</td>
      </tr>`;
    });
  </script>
</body>

</html>
