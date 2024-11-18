#include "hospitalSensorData.h"

HospitalSensorData::HospitalSensorData() 
{}

float HospitalSensorData::readHumidity()
{
    return humiditySensor.readHumidity();
}

float HospitalSensorData::readOxygenLevel()
{
    return oxygenSensor.readOxygenLevel();
}

int HospitalSensorData::getNumberOfAvailableSpaces()
{
    return parkingSpaceSensor.getNumberOfAvailableSpaces();
}

float HospitalSensorData::readTemperature()
{
    return tempSensor.readTemperature();
}