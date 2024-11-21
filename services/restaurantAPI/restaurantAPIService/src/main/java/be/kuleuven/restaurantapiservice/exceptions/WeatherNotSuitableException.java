package be.kuleuven.restaurantapiservice.exceptions;

public class WeatherNotSuitableException extends RuntimeException {
    public WeatherNotSuitableException(String message) {
        super(message);
    }
}
