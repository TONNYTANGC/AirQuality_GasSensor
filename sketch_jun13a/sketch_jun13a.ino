#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <DHT.h>
#include <MQ135.h>
#include <LiquidCrystal_I2C.h>


//Network credentials
const char* ssid = "Tonny WiFi";
const char* password = "chiewlih82761401";

// Server IP and PHP script URL
const char* serverName = "http://172.20.10.3/SKIH3113_MidTerm/post.php";

// API key
String apiKeyValue = "tPmAT5Ab3j7F9";

// DHT11 setup
#define DHTPIN D4
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);
LiquidCrystal_I2C lcd(0x27, 16, 2);

// MQ135 setup
#define MQ135PIN A0  // Analog pin connected to the MQ135 sensor
MQ135 mq135(MQ135PIN);

int co2lvl;
WiFiClient client;

void setup() {
  Serial.begin(115200);
  lcd.init();
  dht.begin();
  // Turn on LCD backlight and clear the screen
  lcd.backlight();
  lcd.clear();
  delay(1000);
  lcd.clear();  // Clear again after a delay

  // Resetting WiFi module
  WiFi.disconnect(true);
  delay(1000);

  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  // Reading data from DHT11
  float humidity = dht.readHumidity();
  float temperature = dht.readTemperature();

  // Check if any reads failed
  if (isnan(humidity) || isnan(temperature)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }

  // Reading data from MQ135
  int analogValue = analogRead(A0);
  co2lvl = analogValue - 600;  // Adjust for sensor offset
  co2lvl = map(co2lvl, 0, 1024, 400, 500);

  // Display readings on LCD
  lcd.setCursor(0, 0);
  lcd.print("Humi :");
  lcd.print(humidity);
  lcd.print(" %");
  lcd.setCursor(0, 1);
  lcd.print("Temp :");
  lcd.print(temperature);
  lcd.print(" C");
  delay(5000);  // Delay to read before updating LCD
  lcd.clear();  // Clear screen before displaying CO2 reading
  lcd.setCursor(0, 0);
  lcd.print("CO2 :");
  lcd.print(co2lvl);
  lcd.print(" PPM");
  String quality;
  // Display air quality status based on CO2 level
  if ((co2lvl >= 350) && (co2lvl <= 1400)) {
    lcd.setCursor(0, 1);
    lcd.print(" Good ");
    quality = "Good";
  } else if ((co2lvl >= 1400) && (co2lvl <= 2000)) {
    lcd.setCursor(0, 1);
    lcd.print(" Bad ");
    quality = "Bad";
  } else {
    lcd.setCursor(0, 1);
    lcd.print(" Danger!");
    quality = "Danger";
  }

  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(client, serverName);  // Updated to use WiFiClient

    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // Prepare your HTTP POST request data
    String httpRequestData = "api_key=" + apiKeyValue
                             + "&Humidity=" + String(humidity)
                             + "&Temperature=" + String(temperature)
                             + "&CO2lvl=" + String(co2lvl)
                             + "&Quality=" + quality;
    Serial.print("httpRequestData: ");
    Serial.println(httpRequestData);

    // Send HTTP POST request
    int httpResponseCode = http.POST(httpRequestData);

    if (httpResponseCode > 0) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
    } else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
      Serial.print("HTTPClient error: ");
      Serial.println(http.errorToString(httpResponseCode).c_str());
      // Print additional error information if available
      if (httpResponseCode == HTTPC_ERROR_CONNECTION_REFUSED) {
        Serial.println("Connection refused by server");
      } else if (httpResponseCode == HTTPC_ERROR_SEND_PAYLOAD_FAILED) {
        Serial.println("Failed to send HTTP payload");
      } else if (httpResponseCode == HTTPC_ERROR_READ_TIMEOUT) {
        Serial.println("Read timeout");
      }
    }
    // Free resources
    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }
  // Send an HTTP POST request every 10 seconds
  delay(10000);
}
