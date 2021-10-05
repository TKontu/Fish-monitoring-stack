#include <WiFi.h>
#include <PubSubClient.h>
#include <config.h>

//Variables from config.h
const int mqttPort = 1883;

 
WiFiClient espClient;
PubSubClient client(espClient);

void reconnect() {
  // Loop until we're reconnected
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    // Create a random client ID
    String clientId = "ESP32Client-";
    clientId += String(random(0xffff), HEX);
    // Attempt to connect
    if (client.connect("ESP32Client", mqttUser, mqttPassword )) {
      Serial.println("Re-connected");
      // Once connected, publish an announcement...
      client.publish("outTopic", "hello world");
      // ... and resubscribe
      client.subscribe("inTopic");
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");
      // Wait 5 seconds before retrying
      delay(5000);
    }
  }
}


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
 
    if (client.connect("ESP32Client1", mqttUser, mqttPassword )) {
 
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

  if (!client.connected()) {
    reconnect();
  }
  client.loop();
  
  //char* viesti = "1,";
  //char* fishsize = random(3, 99);
  //char* lahetettava = viesti + fishsize;
  Serial.println("Lahetettava viesti");
  
  //Serial.println(lahetettava);
  Serial.println("kfgcar, 1,13");

  client.publish("kfgcar", "1,13");
  
  delay(50);
 
}

/*
void loop() {
  if (!client.connected()) {
    reconnect();
  }
  int fishsize = random(3, 99);
  Serial.println(fishsize);
  
  client.publish(fishsize); // 0,X for delete entry; 1,X for add entry; X = fishlenth in cm.
// e.g. client.publish("vbnhzm", "1,45"); would add a fish with length of 45 cm to database.
 
  client.loop();
  delay(5000); 
}
*/
