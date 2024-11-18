#ifndef TEMP_SENSOR_H
#define TEMP_SENSOR_H

#include <random>

class TempSensor 
{
public:
    TempSensor();
    float readTemperature();
    
private:
    float getRandomFloat(float min, float max);
    const float minTemperature = 18.5;
    const float maxTemperature = 23.5;

    std::random_device rd;
    std::mt19937 gen;
};

#endif // TEMP_SENSOR_H