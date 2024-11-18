package be.kuleuven.restaurantapiservice.api.controller;

import be.kuleuven.restaurantapiservice.api.model.RestaurantTable;
import be.kuleuven.restaurantapiservice.service.RestaurantTableService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Optional;

@RestController
@RequestMapping("/api/tables")
public class RestaurantTableController {

    private final RestaurantTableService tableService;

    @Autowired
    public RestaurantTableController(RestaurantTableService tableService) {
        this.tableService = tableService;
    }

    @GetMapping
    public ResponseEntity<List<RestaurantTable>> getAllTables() {
        List<RestaurantTable> tables = tableService.getAllTables();
        return ResponseEntity.ok(tables);
    }

    @GetMapping("/{id}")
    public ResponseEntity<RestaurantTable> getTableById(@PathVariable Long id) {
        Optional<RestaurantTable> table = tableService.getTableById(id);
        return table.map(ResponseEntity::ok).orElseGet(() -> ResponseEntity.notFound().build());
    }

    @PutMapping("/{id}/reserve")
    public ResponseEntity<RestaurantTable> reserveTable(@PathVariable Long id) {
        try {
            RestaurantTable table = tableService.reserveTable(id);
            return ResponseEntity.ok(table);
        } catch (IllegalArgumentException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(null);
        }
    }

    @PutMapping("/{id}/cancel")
    public ResponseEntity<RestaurantTable> cancelReservation(@PathVariable Long id) {
        try {
            RestaurantTable table = tableService.cancelReservation(id);
            return ResponseEntity.ok(table);
        } catch (IllegalArgumentException e) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(null);
        }
    }
}
