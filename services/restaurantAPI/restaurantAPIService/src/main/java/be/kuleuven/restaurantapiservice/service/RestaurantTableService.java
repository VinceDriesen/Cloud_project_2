package be.kuleuven.restaurantapiservice.service;

import be.kuleuven.restaurantapiservice.api.model.Reservation;
import be.kuleuven.restaurantapiservice.api.model.RestaurantTable;
import be.kuleuven.restaurantapiservice.api.repository.RestaurantTableRepository;
import be.kuleuven.restaurantapiservice.exceptions.WeatherNotSuitableException;
import be.kuleuven.restaurantapiservice.grpc.WeatherResponse;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;
import java.util.stream.Collectors;

@Service
public class RestaurantTableService {

    private final RestaurantTableRepository tableRepository;

    @Autowired
    public RestaurantTableService(RestaurantTableRepository tableRepository) {
        this.tableRepository = tableRepository;
    }

    public List<RestaurantTable> getAllTables() {
        return tableRepository.findAll();
    }

    public List<Reservation> getAllReservations(Long id) {
        return tableRepository.findById(id).get().getReservations();
    }

    public Optional<RestaurantTable> getTableById(Long id) {
        return tableRepository.findById(id);
    }

    public RestaurantTable createTable(RestaurantTable table) {
        return tableRepository.save(table);
    }

    public List<RestaurantTable> getInsideTables() {
        return tableRepository.findAll().stream().filter(table -> !table.isOutside()).toList();
    }

    public List<RestaurantTable> getOutsideTables() {
        return tableRepository.findAll().stream().filter(RestaurantTable::isOutside).toList();
    }


    public List<RestaurantTable> getAvailableTables(LocalDateTime startTime, int duration) {
        List<RestaurantTable> tables = new ArrayList<>();
        for (var table : getAllTables()) {
            if (!hasCollision(startTime, duration, table)) {
                tables.add(table);
            }
        }
        return tables;
    }

    public List<RestaurantTable> getReservedTables(LocalDateTime startTime, int duration) {
        List<RestaurantTable> tables = new ArrayList<>();
        for (var table : getAllTables()) {
            if (hasCollision(startTime, duration, table)) {
                tables.add(table);
            }
        }
        return tables;
    }

    boolean hasCollision(LocalDateTime startTime, int duration, RestaurantTable table) {
        // Gebruik de duur in uren en voeg het toe aan de starttijd om de eindtijd te berekenen
        LocalDateTime endTime = startTime.plusHours(duration); // Aangezien 'duration' in uren is

        for (Reservation reservation : table.getReservations()) {
            LocalDateTime reservationBegin = reservation.getStartTime();
            LocalDateTime reservationEnd = reservationBegin.plusHours(reservation.getDuration()); // Duur van de reservering in uren

            // Check of de nieuwe reservering een overlap heeft met een bestaande reservering
            if (startTime.isBefore(reservationEnd) && endTime.isAfter(reservationBegin)) {
                return true; // Er is een conflict, de tijden overlappen
            }
        }
        return false; // Geen conflict, geen overlap
    }






}
