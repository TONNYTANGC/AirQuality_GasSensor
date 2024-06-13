<?php

$servername = 'localhost';
// REPLACE with your Database name
$dbname = 'air_quality';
// REPLACE with Database user
$username = 'root';
// REPLACE with Database user password
$password = '';
$api_key_value = 'tPmAT5Ab3j7F9';

$api_key = $Humidity = $Temperature = $CO2lvl = $Quality = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $api_key = test_input($_POST["api_key"]);
  if ($api_key == $api_key_value) {
    $Humidity = test_input($_POST["Humidity"]);
    $Temperature = test_input($_POST["Temperature"]);
    $CO2lvl = test_input($_POST["CO2lvl"]);
    $Quality = test_input($_POST["Quality"]);

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO gassensor (Humidity, Temperature, CO2lvl, Quality) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $Humidity, $Temperature, $CO2lvl, $Quality);

    // Execute the prepared statement
    if ($stmt->execute()) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  } else {
    echo "Wrong API Key provided.";
  }
} else {
  echo "No data posted with HTTP POST.";
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
