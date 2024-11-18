#include "tempSensor.h"

TempSensor::TempSensor() : gen(rd()) 
{}

float TempSensor::readTemperature()
{
    return getRandomFloat(minTemperature, maxTemperature);
}

float TempSensor::getRandomFloat(float min, float max)
{
    std::uniform_real_distribution<float> dist(min, max);
    return dist(gen);
}

