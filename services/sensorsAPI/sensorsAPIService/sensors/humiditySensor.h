#ifndef HUMIDITYSENSOR_H
#define HUMIDITYSENSOR_H

#include <random>


class HumiditySensor 
{
public:
    HumiditySensor();
    float readHumidity();
private:
    float getRandomFloat(float min, float max);
    const float minHumidity = 30.0;
    const float maxHumidity = 70.0;

    std::random_device rd;
    std::mt19937 gen;
};


#endif // HUMIDITYSENSOR_H