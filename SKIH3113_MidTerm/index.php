<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="refresh" content="5">
  <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
  <title>Air Quality Data</title>
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
  <style>
    h1 {
      display: flex;
      justify-content: center;
      align-items: center;

    }

    .chart-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .chart {
      width: calc(33.33% - 10px);
      margin-bottom: 10px;
    }

    .quality-table {
      border-collapse: collapse;
      width: 100%;
    }

    .quality-table th,
    .quality-table td {
      border: 1px #ddd;
      padding: 8px;
      text-align: left;
    }

    .quality-table th {
      background-color: #f2f2f2;
    }

    .chart-heading {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 5px;
    }
  </style>
</head>

<body>
  <h1>Air Quality</h1>
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
  <h1>Latest Quality Data (Last 20 Entries)</h1>
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
  $servername = 'localhost';
  $dbname = 'air_quality';
  $username = 'root';
  $password = '';

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT id, Humidity, Temperature, CO2lvl, Quality, time FROM gassensor ORDER BY id DESC LIMIT 20";
  $data = [];
  if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
      $row_time = date('Y-m-d H:i:s', strtotime($row["time"]));
      $data[] = [
        'time' => $row_time,
        'humidity' => $row["Humidity"],
        'temperature' => $row["Temperature"],
        'co2lvl' => $row["CO2lvl"],
        'quality' => $row["Quality"]
      ];
    }
    $result->free();
  } else {
    echo "0 results";
  }
  $conn->close();
  ?>

  <script>
    const data = <?php echo json_encode($data); ?>;

    const times = data.map(d => d.time);
    const humidity = data.map(d => d.humidity);
    const temperature = data.map(d => d.temperature);
    const co2lvl = data.map(d => d.co2lvl);
    const quality = data.map(d => d.quality);

    const traceHumidity = {
      x: times,
      y: humidity,
      mode: 'lines',
      name: 'Humidity (%)',
      line: { color: 'blue' }
    };

    const traceTemperature = {
      x: times,
      y: temperature,
      mode: 'lines',
      name: 'Temperature (°C)',
      line: { color: 'red' }
    };

    const traceCO2 = {
      x: times,
      y: co2lvl,
      mode: 'lines',
      name: 'CO2 Level (ppm)',
      line: { color: 'green' }
    };

    Plotly.newPlot('humidityChart', [traceHumidity]);
    Plotly.newPlot('temperatureChart', [traceTemperature]);
    Plotly.newPlot('co2Chart', [traceCO2]);

    const qualityTableBody = document.getElementById('qualityTableBody');
    qualityTableBody.innerHTML = '';
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