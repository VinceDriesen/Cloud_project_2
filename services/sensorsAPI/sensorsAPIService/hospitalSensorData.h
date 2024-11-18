#ifndef HOSPITALSENSORDATA_H
#define HOSPITALSENSORDATA_H

#include "humiditySensor.h"
#include "oxygenSensor.h"
#include "parkingSpaceSensor.h"
#include "tempSensor.h"


class HospitalSensorData 
{
public:
    HospitalSensorData();
    
    float readHumidity();
    float readOxygenLevel();
    int getNumberOfAvailableSpaces();
    float readTemperature();
private:
    HumiditySensor humiditySensor;
    OxygenSensor oxygenSensor;
    ParkingSpaceSensor parkingSpaceSensor;
    TempSensor tempSensor;
};

#endif // HOSPITALSENSORDATA_H