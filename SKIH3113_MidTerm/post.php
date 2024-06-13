<?php

// Database connection parameters
$servername = 'localhost';
//  Database name
$dbname = 'air_quality';
// Database user
$username = 'root';
// Database user password
$password = '';
// Predefined API key value for security
$api_key_value = 'tPmAT5Ab3j7F9';

// Initialize variables
$api_key = $Humidity = $Temperature = $CO2lvl = $Quality = "";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve and sanitize the API key from POST data
  $api_key = test_input($_POST["api_key"]);
  // Verify the API key
  if ($api_key == $api_key_value) {
    // Retrieve and sanitize the data from POST request
    $Humidity = test_input($_POST["Humidity"]);
    $Temperature = test_input($_POST["Temperature"]);
    $CO2lvl = test_input($_POST["CO2lvl"]);
    $Quality = test_input($_POST["Quality"]);

    // Create a connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check if the connection failed
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind an SQL statement for inserting data
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
    // Output error message for wrong API key
    echo "Wrong API Key provided.";
  }
} else {
  // Output error message for no data posted
  echo "No data posted with HTTP POST.";
}

// Function to sanitize input data
function test_input($data) {
  $data = trim($data);            // Remove whitespace from both sides
  $data = stripslashes($data);    // Remove backslashes
  $data = htmlspecialchars($data); // Convert special characters to HTML entities
  return $data;
}
?>
