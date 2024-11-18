#ifndef OXYGENSENSOR_H
#define OXYGENSENSOR_H

#include <random>


class OxygenSensor {
public:
    OxygenSensor();
    float readOxygenLevel();
private:
    float getRandomFloat(float min, float max);
    const float minOxygenLevel = 90.0;
    const float maxOxygenLevel = 100.0;

    std::random_device rd;
    std::mt19937 gen;
};

#endif // OXYGENSENSOR_H