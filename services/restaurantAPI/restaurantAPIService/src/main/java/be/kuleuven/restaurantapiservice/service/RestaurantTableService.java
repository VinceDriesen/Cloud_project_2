package be.kuleuven.restaurantapiservice.service;

import be.kuleuven.restaurantapiservice.api.model.RestaurantTable;
import be.kuleuven.restaurantapiservice.api.repository.RestaurantTableRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

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

    public Optional<RestaurantTable> getTableById(Long id) {
        return tableRepository.findById(id);
    }

    public RestaurantTable reserveTable(Long id) {
        Optional<RestaurantTable> tableOpt = tableRepository.findById(id);
        if (tableOpt.isPresent()) {
            RestaurantTable table = tableOpt.get();
            table.setReserved(true);
            return tableRepository.save(table);
        }
        throw new IllegalArgumentException("Table with ID " + id + " not found");
    }

    public RestaurantTable cancelReservation(Long id) {
        Optional<RestaurantTable> tableOpt = tableRepository.findById(id);
        if (tableOpt.isPresent()) {
            RestaurantTable table = tableOpt.get();
            table.setReserved(false);
            return tableRepository.save(table);
        }
        throw new IllegalArgumentException("Table with ID " + id + " not found");
    }
}
