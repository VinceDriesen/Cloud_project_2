package be.kuleuven.restaurantapiservice.service;

import be.kuleuven.restaurantapiservice.api.dto.ReservationRequest;
import be.kuleuven.restaurantapiservice.api.model.Reservation;
import be.kuleuven.restaurantapiservice.api.model.RestaurantTable;
import be.kuleuven.restaurantapiservice.api.repository.ReservationRepository;
import be.kuleuven.restaurantapiservice.api.repository.RestaurantTableRepository;
import be.kuleuven.restaurantapiservice.exceptions.TableNotFoundException;
import be.kuleuven.restaurantapiservice.exceptions.WeatherNotSuitableException;
import be.kuleuven.restaurantapiservice.grpc.WeatherResponse;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.time.LocalDateTime;

@Service
public class ReservationService {

    @Autowired
    private ReservationRepository reservationRepository;

    @Autowired
    private WeatherServiceGrpcClient weatherClient;

    @Autowired
    private RestaurantTableRepository restaurantTableRepository;

    public Reservation reserveTable(Long tableId, ReservationRequest reservationRequest) {
        RestaurantTable table = restaurantTableRepository.findById(tableId)
                .orElseThrow(() -> new TableNotFoundException("Table not found"));

        if(reservationRequest.getDuration() == 0 || reservationRequest.getStartTime() == null || reservationRequest.getReservedBy() == null)
        {
            throw new IllegalArgumentException("Invalid reservation request");
        }

        boolean hasConflict = table.getReservations().stream().anyMatch(existingReservation -> {
            LocalDateTime existingEnd = existingReservation.getStartTime().plusHours(existingReservation.getDuration());
            LocalDateTime newEnd = reservationRequest.getStartTime().plusHours(reservationRequest.getDuration());
            return reservationRequest.getStartTime().isBefore(existingEnd) && newEnd.isAfter(existingReservation.getStartTime());
        });


        if (hasConflict) {
            throw new IllegalArgumentException("Time slot not available");
        }

        if(table.isOutside())
        {
            WeatherResponse response = weatherClient.getWeather("hasselt");
            if(response.getTemperature() < 10 || response.getTemperature() > 30)
            {
                throw new WeatherNotSuitableException("Weather not suitable for outside table");
            }
        }

        Reservation reservation = new Reservation();
        reservation.setTable(table);
        reservation.setStartTime(reservationRequest.getStartTime());
        reservation.setDuration(reservationRequest.getDuration());
        reservation.setReservedBy(reservationRequest.getReservedBy());

        return reservationRepository.save(reservation);
    }
}

