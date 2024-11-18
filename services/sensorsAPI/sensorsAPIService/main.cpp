#include <iostream>
#include <string>
#include <thread>
#include <chrono>
#include "mqtt/async_client.h"
#include "hospitalSensorData.h"

const std::string SERVER_ADDRESS = "tcp://mqtt5_broker:1883";
const std::string CLIENT_ID = "sensor_publisher";
const std::string TOPIC = "hospital/sensors";

void publishData(mqtt::async_client& client, const std::string& data) {
    try {
        mqtt::message_ptr msg = std::make_shared<mqtt::message>(TOPIC, data);
        client.publish(msg);
    }
    catch (const mqtt::exception& e) {
        std::cerr << "Error publishing message: " << e.what() << std::endl;
    }
}

int main() {
    mqtt::async_client client(SERVER_ADDRESS, CLIENT_ID);

    mqtt::connect_options connOpts;
    connOpts.set_clean_session(true);
    connOpts.set_user_name("user1");
    connOpts.set_password("user1");

    try {
        client.connect(connOpts)->wait();
    }
    catch (const mqtt::exception& e) {
        std::cerr << "Error connecting to broker: " << e.what() << std::endl;
        return 1;
    }

    HospitalSensorData hospitalSensorData;

    while (true) {
        float humidity = hospitalSensorData.readHumidity();
        float oxygenLevel = hospitalSensorData.readOxygenLevel();
        int availableParkingSpaces = hospitalSensorData.getNumberOfAvailableSpaces();
        float temperature = hospitalSensorData.readTemperature();

        std::string sensorData = "{\"humidity\":" + std::to_string(humidity) +
                                 ", \"oxygenLevel\":" + std::to_string(oxygenLevel) +
                                 ", \"availableParkingSpaces\":" + std::to_string(availableParkingSpaces) +
                                 ", \"temperature\":" + std::to_string(temperature) + "}";

        publishData(client, sensorData);

        std::this_thread::sleep_for(std::chrono::seconds(5));
    }

    client.disconnect()->wait();
    return 0;
}
