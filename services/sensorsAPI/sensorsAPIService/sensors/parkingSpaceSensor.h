#ifndef PARKING_SPACE_SENSOR_H
#define PARKING_SPACE_SENSOR_H

#include <random>

class ParkingSpaceSensor 
{
public:
    ParkingSpaceSensor();
    int getNumberOfAvailableSpaces();
private:
    int getRandomInt(int min, int max);
    const int minSpaces = 100;
    const int maxSpaces = 200;

    std::random_device rd;
    std::mt19937 gen;
};

#endif // PARKING_SPACE_SENSOR_H