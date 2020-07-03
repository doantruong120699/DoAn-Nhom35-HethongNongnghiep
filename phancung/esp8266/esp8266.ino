#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266HTTPClient.h>
#include "UART_ARDUINO.h"
#include <Arduino_JSON.h>
#include <Servo.h>

Servo servo;
int servoDegree = 0;
//Uart connect
UART Nhan;

//Value form sensor
uint16_t nhiet_do;
uint16_t do_am_khi;
uint32_t ap_suat;
uint16_t toc_do_gio;
uint8_t  do_am_dat;

//Network crendentials
const char* ssid = "test";
const char* password = "12345678";

//Server name, api, sensor location
//const char* serverNameControl = "http://dubaothoitiet.atwebpages.com/esp_control/esp-outputs-action.php?action=outputs_state";
const char* serverNameControl = "http://test-cqt-esp8266.000webhostapp.com/esp_control/esp-outputs-action.php?action=outputs_state";
//const char* serverNameControl = "http://dubaothoitiet.epizy.com/esp_control/esp-outputs-action.php?action=outputs_state";

//String serverName = "http://dubaothoitiet.atwebpages.com/post-esp-data.php";
String serverName = "http://test-cqt-esp8266.000webhostapp.com/post-esp-data.php";

String apiKeyValue = "tPmAT5Ab3j7F9";
String sensorLocation = "Da Nang";
String outputsState;
int currentDegSerco = 0;

//Pin out for l298n
#define IN1 15
#define IN2 13
#define IN3 2
#define IN4 0
#define MAX_SPEED 255
#define MIN_SPEED 0

// Pin set Rain sensor
const int rainPin = 12;     // the number of the pushbutton pin
// Rain variables state
int rainState = 0;         // variable for reading the pushbutton status
int lastState = 0;
// key rain chatbox
String key = "puj5mnhmu";
// String rain chatbox
String message = "C%E1%BA%A3nh%20b%C3%A1o!!!%20%C4%90ang%20c%C3%B3%20m%C6%B0a!!!";

String jsonCurrent = "{}";
void setup() {
  //Start Serial
  Nhan.begin(9600);

  //Connect to wifi
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  //Pin mode
  pinMode(4, OUTPUT);
  pinMode(5, OUTPUT);
  pinMode(14, OUTPUT);

  //Servo start up
  servo.attach(14);
  servo.write(0);
  servo.detach();
  
  //L298N morto
  pinMode(IN1, OUTPUT);
  pinMode(IN2, OUTPUT);
  pinMode(IN3, OUTPUT);
  pinMode(IN4, OUTPUT);

  //lcd check wifi
  pinMode(16, OUTPUT);
}

void loop() {
    if (WiFi.status() == WL_CONNECTED) {
      digitalWrite(16, HIGH);
      post_request();
      delay(500);
      outputsState = httpGETRequest(serverNameControl);
      demoParse(outputsState);
      Serial.println(outputsState);
      postRain();
    } else {
      digitalWrite(16, LOW);
      Serial.println("WiFi Disconnected");
      digitalWrite(4, LOW);
      digitalWrite(5, LOW);
      digitalWrite(12, LOW);
      digitalWrite(14, LOW);
    }
    delay(1000);
}

//morto dc control
void motor_1_Dung() {
  digitalWrite(IN1, LOW);
  digitalWrite(IN2, LOW);
}
 
void motor_2_Dung() {
  digitalWrite(IN3, LOW);
  digitalWrite(IN4, LOW);
}
 
void motor_1_Tien(int speed) {
  speed = constrain(speed, MIN_SPEED, MAX_SPEED);
  digitalWrite(IN1, HIGH);
  analogWrite(IN2, 255 - speed);
}
 
void motor_2_Tien(int speed) { 
  speed = constrain(speed, MIN_SPEED, MAX_SPEED);
  analogWrite(IN3, 255 - speed);
  digitalWrite(IN4, HIGH);// chân này không có PWM
}

String httpGETRequest(const char* serverName) {
  WiFiClient client;
  HTTPClient http;

  // Your IP address with path or Domain name with URL path
  http.begin(client, serverName);

  // Send HTTP POST request
  int httpResponseCode = http.GET();

  String payload = "{}";

  if (httpResponseCode > 0) {
    if(httpResponseCode == 200){
      Serial.print("HTTP Response code get: ");
      Serial.println(httpResponseCode);
      payload = http.getString();
      jsonCurrent = payload;
    }else{
      Serial.print("HTTP Response code get: ");
      Serial.println(httpResponseCode);
      payload = jsonCurrent;
    }
  }
  else {
    Serial.print("Error code: ");
    Serial.println(httpResponseCode);
    payload = jsonCurrent;
  }
  http.end();
  return payload;
}

void demoParse(String input) {
  JSONVar myObject = JSON.parse(input); //creat a object json with char* input

  // JSON.typeof(jsonVar) can be used to get the type of the var
  if (JSON.typeof(myObject) == "undefined") {
    Serial.println("Parsing input failed!");
    digitalWrite(4, LOW);
    digitalWrite(5, LOW);
    digitalWrite(12, LOW);
    digitalWrite(14, LOW);
    return;
  }
  Serial.print("JSON.typeof(myObject) = ");
  Serial.println(JSON.typeof(myObject)); // prints: object

  JSONVar keys = myObject.keys(); //get an array of all keys in the json object
  Serial.println(myObject);

  int pinId = 0, statusId = 0, valueId = 0;
  //LED control
  pinId = atoi("4");
  statusId = atoi(myObject["4"]);
  valueId = atoi(myObject["4-on"]);
  if (statusId == 1) {
    digitalWrite(4, HIGH);
  } else {
    digitalWrite(4, LOW);
  }
  //DC control
  pinId = atoi("5");
  statusId = atoi(myObject["5"]);
  valueId = atoi(myObject["5-on"]);
  if (statusId == 1) {
    motor_1_Tien(MAX_SPEED);
  } else {
    motor_1_Dung();
  }
  //FAN control
  pinId = atoi("12");
  statusId = atoi(myObject["12"]);
  valueId = atoi(myObject["12-on"]);
  if (statusId == 1) {
    motor_2_Tien(MAX_SPEED);
  } else {
    motor_2_Dung();  
  }
  //SERVO control
  pinId = atoi("14");
  statusId = atoi(myObject["14"]);
  //valueId = atoi(myObject["14-on"]);
  valueId = 70;
  if( statusId == 1){
     if(servoDegree != valueId){
     servo.attach(14);
     servo.write(valueId);
     delay(1000);
     servo.detach();
     servoDegree = valueId;
     }
  }else{
    if(servoDegree != 0){
     servo.attach(14);
     servo.write(0);
     delay(1000);
     servo.detach();
     servoDegree = 0;
     }
  }
}

//get data from uart communicate with arduino
bool get_data_uart(uint16_t *a, uint16_t *b, uint32_t *c, uint8_t *d, uint16_t *e) {
  uint32_t size_package = sizeof(*a) + sizeof(*b) + sizeof(*c) + sizeof(*d) + sizeof(*e);
  if (Serial.available() >= size_package) {
    (*a) = Nhan.read_uint16_t();
    (*b) = Nhan.read_uint16_t();
    (*c) = Nhan.read_uint32_t();
    (*d) = Nhan.read_uint8_t();
    (*e) = Nhan.read_uint16_t();
    Nhan.clear_buffer();
    return true;
  } else {
    return false;
  }
}

void post_request() {
  HTTPClient http;
  http.begin(serverName);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  uint16_t value1 = 0 , value2 = 0, value5 = 0;
  uint32_t value3 = 0;
  uint8_t value4 = 0;  if (get_data_uart(&nhiet_do, &do_am_khi, &ap_suat, &do_am_dat, &toc_do_gio) == true) {
    value1 = nhiet_do;
    value2 = do_am_khi;
    value3 = ap_suat;
    value4 = toc_do_gio;
    value5 = do_am_dat;
    String httpRequestData = "api_key=" + apiKeyValue + "&location=" + sensorLocation + "&value1=" + String(value1) + "&value2=" + String(value2) + "&value3=" + String(value3) + "&value4=" + String(value4) + "&value5=" + String(value5) + "";
    Serial.print("httpRequestData: ");
    Serial.println(httpRequestData);

    //Send http post request
    int httpResponseCode = http.POST(httpRequestData);
    if (httpResponseCode > 0) {
      Serial.print("HTTP Response code post: ");
      Serial.println(httpResponseCode);
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    // Free resources
    http.end();
  }
}

void postRain(){
 rainState = digitalRead(rainPin);
 if (rainState == LOW && lastState==0) { // Cảm biến đang mưa
    
    Serial.println("Dang mua");
    //-----
    if (WiFi.status() == WL_CONNECTED) {
      std::unique_ptr<BearSSL::WiFiClientSecure> client(new BearSSL::WiFiClientSecure);
      client->setInsecure();
      HTTPClient https;
      https.begin(*client, "https://taymay.herokuapp.com/send/?key="+key+"&message="+message);
      Serial.println(https.GET());
      https.end();
      digitalWrite(5, HIGH);
      delay(2000);
      digitalWrite(5, LOW);
    } else {
      Serial.println("WiFi Disconnected");
    }
    delay(1000);
    //-----
    lastState= 1;
    
  }else if(rainState == LOW && lastState==1){
    Serial.println("Dang");
  }
  else {
    Serial.println("Dang khong mua");
    lastState = 0;
  }
}
