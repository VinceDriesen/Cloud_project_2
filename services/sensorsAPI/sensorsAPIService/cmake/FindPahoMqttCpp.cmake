find_package(PahoMqttC REQUIRED)

find_path(PAHO_MQTT_CPP_INCLUDE_DIRS
  NAMES mqtt/async_client.h
  PATH_SUFFIXES paho/mqtt
)

find_library(PAHO_MQTT_CPP_LIBRARIES
  NAMES paho-mqttpp3
)

include(FindPackageHandleStandardArgs)
find_package_handle_standard_args(PahoMqttCpp DEFAULT_MSG
  PAHO_MQTT_CPP_LIBRARIES PAHO_MQTT_CPP_INCLUDE_DIRS
)

mark_as_advanced(PAHO_MQTT_CPP_INCLUDE_DIRS PAHO_MQTT_CPP_LIBRARIES)
