**Air Quality Monitoring System with ESP8266, DHT11, MQ135, XAMPP, and MySQL**
<br>
**Overview**
This project utilizes an ESP8266 microcontroller along with DHT11 and MQ135 sensors to monitor air quality. The system includes a web server hosted on XAMPP and uses MySQL to store and display real-time data.

**Features**
Real-time monitoring of humidity, temperature, CO2 level, and air quality.
Web-based interface for viewing live data.
Data storage in a MySQL database for historical analysis.
**Requirements**
ESP8266 microcontroller
DHT11 humidity and temperature sensor
MQ135 gas sensor
XAMPP for web server hosting
MySQL for data storage
**Installation**
Hardware Setup:
Connect the DHT11 and MQ135 sensors to the ESP8266 as per the wiring diagram provided in the hardware_setup folder.
Software Setup:
Upload the esp8266_code.ino sketch to your ESP8266 board after configuring the Wi-Fi credentials and sensor pins.
Import the MySQL database schema provided in the database folder to set up the necessary tables.
Place the PHP files (index.php, getData.php, dbconfig.php) in the xampp\htdocs directory.
Web Interface:
Start XAMPP and ensure Apache and MySQL services are running.
Open a web browser and navigate to http://localhost/index.php to view the live data dashboard.
**Usage**
Access the web interface to monitor real-time data including humidity, temperature, CO2 level, and air quality index.
Historical data can be analyzed by querying the MySQL database directly or by using visualization tools with XAMPP.
**Contributing**
Contributions are welcome! Feel free to submit pull requests or open issues for any improvements or bug fixes.
