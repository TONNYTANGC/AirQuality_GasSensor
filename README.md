**Air Quality Monitoring System with ESP8266, DHT11, MQ135, XAMPP, and MySQL**
<br>
**Overview** <br>
This project utilizes an ESP8266 microcontroller along with DHT11 and MQ135 sensors to monitor air quality. The system includes a web server hosted on XAMPP and uses MySQL to store and display real-time data.
<br>
**Features**<br>
Real-time monitoring of humidity, temperature, CO2 level, and air quality.<br>
Web-based interface for viewing live data.<br>
Data storage in a MySQL database for historical analysis.
<br>
**Requirements**<br>
ESP8266 microcontroller<br>
DHT11 humidity and temperature sensor<br>
MQ135 gas sensor<br>
XAMPP for web server hosting<br>
MySQL for data storage
<br>
**Installation** <br>
Hardware Setup:<br>
Connect the DHT11 and MQ135 sensors to the ESP8266 as per the wiring diagram provided in the hardware_setup folder.<br>
Software Setup:<br>
Upload the esp8266_code.ino sketch to your ESP8266 board after configuring the Wi-Fi credentials and sensor pins.<br>
Import the MySQL database schema provided in the database folder to set up the necessary tables.<br>
Place the PHP files (index.php, getData.php, dbconfig.php) in the xampp\htdocs directory.<br>
Web Interface:<br>
Start XAMPP and ensure Apache and MySQL services are running.<br>
Open a web browser and navigate to http://localhost/index.php to view the live data dashboard.
<br>
**Usage** <br>
Access the web interface to monitor real-time data including humidity, temperature, CO2 level, and air quality index.
Historical data can be analyzed by querying the MySQL database directly or by using visualization tools with XAMPP.
<br>
**Contributing**<br>
Contributions are welcome! Feel free to submit pull requests or open issues for any improvements or bug fixes.
