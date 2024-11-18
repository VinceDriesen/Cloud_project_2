cmake_minimum_required(VERSION 3.10)

project(HospitalSensors)

set(CMAKE_CXX_STANDARD 17)
set(CMAKE_CXX_STANDARD_REQUIRED True)

# Explicitly define paths for the Paho MQTT libraries
set(PAHO_MQTT_CPP_INCLUDE_DIRS "/usr/local/include")
set(PAHO_MQTT_CPP_LIBRARIES "/usr/local/lib/libpaho-mqttpp3.so")
set(PAHO_MQTT_C_INCLUDE_DIRS "/usr/local/include")
set(PAHO_MQTT_C_LIBRARIES "/usr/local/lib/libpaho-mqtt3c.so")

# Define the executable target
add_executable(${PROJECT_NAME}
    main.cpp
    hospitalSensorData.cpp
    sensors/tempSensor.cpp
    sensors/oxygenSensor.cpp
    sensors/humiditySensor.cpp
    sensors/parkingSpaceSensor.cpp
)

# Set the runtime output directory for the executable
set_target_properties(${PROJECT_NAME} PROPERTIES RUNTIME_OUTPUT_DIRECTORY ${CMAKE_BINARY_DIR})

# Add the include directories for the MQTT libraries
target_include_directories(${PROJECT_NAME}
    PRIVATE
    ${PROJECT_SOURCE_DIR}/sensors
    ${PROJECT_SOURCE_DIR}
    ${PAHO_MQTT_CPP_INCLUDE_DIRS}
    ${PAHO_MQTT_C_INCLUDE_DIRS}
)

# Link the libraries
target_link_libraries(${PROJECT_NAME}
    PRIVATE
    ${PAHO_MQTT_CPP_LIBRARIES}
    ${PAHO_MQTT_C_LIBRARIES}
)
