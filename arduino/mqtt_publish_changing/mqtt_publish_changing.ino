#include <WiFi.h>
#include <PubSubClient.h>

//Variables from config.h
const int mqttPort = 1883;

 
WiFiClient espClient;
PubSubClient client(espClient);
 
void setup() {
 
  Serial.begin(115200);
  WiFi.begin(ssid, password);
 
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.println("Connecting to WiFi..");
  }
 
  Serial.println("Connected to the WiFi network");
 
  client.setServer(mqttServer, mqttPort);
 
  while (!client.connected()) {
    Serial.println("Connecting to MQTT...");
 
    if (client.connect("ESP32Client", mqttUser, mqttPassword )) {
 
      Serial.println("connected");
 
    } else {
 
      Serial.print("failed with state ");
      Serial.print(client.state());
      delay(2000);
 
    }
  }
 
//  client.publish("jnmtrp", "1,45"); // 0,X for delete entry; 1,X for add entry; X = fishlenth in cm.
// e.g. client.publish("vbnhzm", "1,45"); would add a fish with length of 45 cm to database.
  
  
  
}
 
void loop() {

  //mqtt message consists of a topic and a message divided with a comma, first in the message is topic, which is a trap name:
  int i = random(0, 3);
  char *traps[] = {"pwvlkw", "vbnhzm", "jnmtrp", "jvxsfd"}

  //Second part is the message, either 0 or 1 which defines if fish is added or removed and followed by fish size (divided by comma), e.g. "1,45"
  const char* partOne = "1,";
  int partTwo = random(3, 99);
  const char* sendable = partOne + partTwo;
  
  Serial.println("Message topic:");
  Serial.println(traps[i]);

  Serial.println("Message to be sent:");
  Serial.println(sendable);

  client.publish(traps[i], sendable);
  
  delay(5000);
  client.loop();

}
