#include "parkingSpaceSensor.h"

ParkingSpaceSensor::ParkingSpaceSensor() : gen(rd()) 
{}

int ParkingSpaceSensor::getNumberOfAvailableSpaces()
{
    return getRandomInt(minSpaces, maxSpaces);
}

int ParkingSpaceSensor::getRandomInt(int min, int max)
{
    std::uniform_int_distribution<int> dist(min, max);
    return dist(gen);
}

