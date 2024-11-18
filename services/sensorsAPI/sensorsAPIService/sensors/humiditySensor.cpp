#include "humiditySensor.h"
#include <random>

HumiditySensor::HumiditySensor() : gen(rd()) 
{}


float HumiditySensor::readHumidity()
{
    return getRandomFloat(minHumidity, maxHumidity);
}

float HumiditySensor::getRandomFloat(float min, float max)
{
    std::uniform_real_distribution<float> dist(min, max);
    return dist(gen);
}