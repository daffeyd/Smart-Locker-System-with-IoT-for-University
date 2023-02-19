#include <WiFi.h>
#include <HTTPClient.h>

int trigPin = 17;
int echoPin = 16;
long waktu;
int jarak;
char ssid [] = "unity";
char password [] = "dwl686168";
int led[] = {23, 32, 21, 19, 18, 5 };
int relay[] = {22, 33, 25, 26, 27, 14 };
int IR =  15;

String PrevStatus = "";
String RecStatus = "";

#define ON HIGH
#define OFF LOW
#include <WiFiClient.h>
WiFiClient client;

int firstVal, SecondVal;
void setup() {
  Serial.begin(115200);

  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  Serial.println("Timer set to 5 seconds (timerDelay variable), it will take 5 seconds before publishing the first reading.");

  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
  pinMode(IR, INPUT);
  for (int i = 0; i < 6; i++) {
    pinMode(led[i], OUTPUT);
    pinMode(relay[i], OUTPUT);
  }
}
String getValue(String data, char separator, int index)
{
  int found = 0;
  int strIndex[] = {0, 0};
  int maxIndex = data.length();
  for (int i = 0; i <= maxIndex && found <= index; i++)
  {
    if (data.charAt(i) == separator || i == maxIndex)
    {
      found++;
      strIndex[0] = strIndex[1] + 1;
      strIndex[1] = (i == maxIndex) ? i + 1 : i;
    }
  }
  String ketemu = found > index ? data.substring(strIndex[0], strIndex[1]) : "";
  return ketemu;
}
void loop() {
  // put your main code here, to run repeatedly:
  if (WiFi.status() == WL_CONNECTED)
  {
    HTTPClient http;
    String link = "http://192.168.43.7/BASELocker/statusCheck.php?kampus=BINUS%20ASO&lokasi=Main%20Hall%20Way&nama=A";
    http.begin(client, link);
    int httpCode = http.GET();
    Serial.println(httpCode);
    if (httpCode > 0)
    {
      String status = http.getString();
      String nilai = getValue(status, ',', 0);
      String RecStatus = nilai;
      for (int i = 0; i < 6; i++) {
        if (RecStatus.substring(i, i + 1) == "1") {
          Serial.println("1");
          if (PrevStatus.substring(i, i + 1) == "0") {
            digitalWrite(led[i], HIGH);
            digitalWrite(relay[i], HIGH);
            Serial.println("mengunci Relay");
            unsigned long delayStart = 0; // the time the delay started
            bool delayRunning = false; // true if still waiting for delay to finish
            delayRunning = true; // not finished yet
            bool closed = false;
            Serial.println("memulai delay");
            while (delayRunning) {
              int statusIR = digitalRead(IR);
              if (statusIR == 0) {
                Serial.println(statusIR);
                int IsiLocker = LoadStatus () ;
                Serial.println(statusIR);

                Serial.println(IsiLocker);
                if (closed == false) {
                  delayStart = millis();   // start delay
                  closed = true;
                }

                if (IsiLocker < 10 ) {
                  delay(500);
                  delayRunning = false;
                }

                if (IsiLocker >= 10 ) {
                  Serial.println(millis() - delayStart);
                  if (((millis() - delayStart) >= 10000)) {
                    String Position = String(i + 1);
                    String alamat = "http://192.168.43.7/BASELocker/proses/returnLocker.php?auth=lockerA&kampus=BINUS%20ASO&lokasi=Main%20Hall%20Way&name=A&position=" ;
                    String link = alamat + Position;
                    http.begin(client, link);
                    httpCode = http.GET();

                    delayRunning = false;
                  }
                }
              }
              if (statusIR == 1) {
                Serial.println(statusIR);
                delayStart = millis();   // start delay
              }
            }
          }
          digitalWrite(relay[i], LOW);
          digitalWrite(led[i], HIGH);
          Serial.println("membuka Relay");
        }
        else {

          if (PrevStatus.substring(i, i + 1) == "1") {
            digitalWrite(relay[i], HIGH);
            unsigned long delayStart = 0; // the time the delay started
            bool delayRunning = false; // true if still waiting for delay to finish
            delayRunning = true; // not finished yet
            bool closed = false;
            Serial.println("memulai delay");
            while (delayRunning) {
              int statusIR = digitalRead(IR);
              if (statusIR == 0) {
                Serial.println(statusIR);
                int IsiLocker = LoadStatus () ;
                Serial.println(statusIR);

                Serial.println(IsiLocker);
                if (closed == false) {
                  delayStart = millis();   // start delay
                  closed = true;
                }

                if (IsiLocker > 10 ) {

                  delay(500);
                  delayRunning = false;
                }

                if (IsiLocker <= 10 ) {
                  Serial.println(millis() - delayStart);
                  if (((millis() - delayStart) >= 10000)) {
                    String Position = String(i + 1);
                    delayRunning = false;
                  }
                }
              }
              if (statusIR == 1) {
                Serial.println(statusIR);
                delayStart = millis();   // start delay
              }
            }
          }


          digitalWrite(relay[i], LOW);
          digitalWrite(led[i], LOW);
          Serial.println("membuka Relay");
          Serial.println("0");
        } 
        PrevStatus = RecStatus;
      }

      int nilai1 = nilai.toInt();
      if (nilai1 == 1 )
      {
        Serial.print( "yey");
      }
      Serial.print("loker  ---->");
      Serial.println(nilai);
    }
  }
}
int LoadStatus () {

  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  waktu = pulseIn(echoPin, HIGH);
  jarak = waktu * 0.034 / 2;
  Serial.print("Jarak: ");
  Serial.println(jarak);
  delay(200);
  return jarak;
}
