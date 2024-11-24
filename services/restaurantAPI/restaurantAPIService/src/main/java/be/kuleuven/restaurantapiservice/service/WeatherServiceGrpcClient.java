package be.kuleuven.restaurantapiservice.service;

import org.springframework.stereotype.Service;
import org.springframework.web.client.RestTemplate;
import org.springframework.web.util.UriComponentsBuilder;
import be.kuleuven.restaurantapiservice.grpc.WeatherResponse;

@Service
public class WeatherServiceGrpcClient {

    public WeatherResponse getWeather(String location) {
        String apiUrl = "https://api.open-meteo.com/v1/forecast";
        RestTemplate restTemplate = new RestTemplate();

        String uri = UriComponentsBuilder.fromHttpUrl(apiUrl)
                .queryParam("latitude", 50.9311)
                .queryParam("longitude", 5.3378)
                .queryParam("current_weather", true)
                .toUriString();

        OpenMeteoResponse apiResponse = restTemplate.getForObject(uri, OpenMeteoResponse.class);

        if (apiResponse == null || apiResponse.current_weather == null) {
            throw new RuntimeException("Geen weergegevens ontvangen van Open-Meteo");
        }

        return WeatherResponse.newBuilder()
                .setLocation(location)
                .setForecast("Huidig weer: " + apiResponse.current_weather.weathercode)
                .setTemperature((float) apiResponse.current_weather.temperature)
                .build();
    }

    public static class OpenMeteoResponse {
        public CurrentWeather current_weather;

        public static class CurrentWeather {
            public double temperature;
            public String weathercode;
        }
    }
}
