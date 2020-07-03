#include<Wire.h>
#include "UART_ARDUINO.h"
#include <Adafruit_BMP085.h>
#include <DHT.h>
#include<LiquidCrystal_I2C.h>

//i2c lcd
LiquidCrystal_I2C lcd(0x27, 16, 2);

//bmp
Adafruit_BMP085 bmp;

//dht11
const int DHTPIN = 2;
const int DHTTYPE = DHT11;
DHT dht(DHTPIN, DHTTYPE);

//uart
UART Gui;

//value from sensor
uint16_t nhiet_do;    //  *C
uint16_t do_am_khi;   //  %
uint32_t ap_suat;     //  hpa
uint8_t do_am_dat;    //  %
uint16_t toc_do_gio;  //  m/s

void setup() {
  Gui.begin(9600);

  //lcd
  lcd.init();
  lcd.backlight();
  
  //dht
  dht.begin();

  //bmp
  bmp.begin();

  //soil moisture
  pinMode(3, INPUT);
  pinMode(A0, INPUT);
  pinMode(4, OUTPUT);
}

void loop() {
  do_am_khi = (int)(dht.readHumidity());
  nhiet_do = (int)(dht.readTemperature());
  ap_suat = (bmp.readPressure())/100;
  do_am_dat = analogRead(A0);
  do_am_dat = map(do_am_dat, 0, 1023, 0, 100);
  toc_do_gio = analogRead(A1) * (5.0 / 1023.0) * 6;

  if (isnan(nhiet_do) || isnan(do_am_khi) || isnan(ap_suat) || isnan(do_am_dat) || isnan(toc_do_gio) ) {
    //Serial.println("erro read form sensor");
    digitalWrite(4, LOW);
  }
  else {
    if(nhiet_do == 0 || do_am_khi==0 || ap_suat==0 || do_am_dat==0){
      digitalWrite(4, LOW);
    }else{
       digitalWrite(4, HIGH);
    }
    
    gui_du_lieu(nhiet_do, do_am_khi, ap_suat, do_am_dat, toc_do_gio);
    lcd.setCursor(0,0);
    lcd.print(round(nhiet_do));
 
    lcd.print(" C");
    lcd.setCursor(10, 0);
    lcd.print(round(do_am_khi));
    lcd.print(" %");
    lcd.setCursor(0,1);
    lcd.print(round(do_am_dat));
    lcd.print(" %");
    lcd.setCursor(10, 1);
    lcd.print(round(toc_do_gio));
    lcd.print(" m/s ");
  }
  delay(1000);
}
void gui_du_lieu(uint16_t a, uint16_t b, uint32_t c, uint8_t d, uint16_t e) {
  //max data send = 64 byte
  Gui.clear_buffer();     // reset lại bộ nhớ đệm
  Gui.write_uint16_t(a);
  Gui.write_uint16_t(b);  // 2 byte
  Gui.write_uint32_t(c);  // 4 byte
  Gui.write_uint8_t(d);   // 1 byte
  Gui.write_uint16_t(e);
}
