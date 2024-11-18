#include "oxygenSensor.h"

OxygenSensor::OxygenSensor() : gen(rd()) 
{}

float OxygenSensor::readOxygenLevel()
{
    return getRandomFloat(minOxygenLevel, maxOxygenLevel);
}

float OxygenSensor::getRandomFloat(float min, float max)
{
    std::uniform_real_distribution<float> dist(min, max);
    return dist(gen);
}