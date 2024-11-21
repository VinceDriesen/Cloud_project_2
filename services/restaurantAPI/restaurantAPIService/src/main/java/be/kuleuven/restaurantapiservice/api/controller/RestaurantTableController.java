package be.kuleuven.restaurantapiservice.api.controller;

import be.kuleuven.restaurantapiservice.api.dto.ReservationRequest;
import be.kuleuven.restaurantapiservice.api.model.Reservation;
import be.kuleuven.restaurantapiservice.api.model.RestaurantTable;
import be.kuleuven.restaurantapiservice.service.ReservationService;
import be.kuleuven.restaurantapiservice.service.RestaurantTableService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.format.annotation.DateTimeFormat;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.time.LocalDateTime;
import java.util.List;
import java.util.Optional;

@RestController
@RequestMapping("/api/tables")
public class RestaurantTableController {

    private final RestaurantTableService tableService;
    private final ReservationService reservationService;

    @Autowired
    public RestaurantTableController(RestaurantTableService tableService, ReservationService reservationService) {
        this.tableService = tableService;
        this.reservationService = reservationService;
    }

    @GetMapping
    public ResponseEntity<List<RestaurantTable>> getAllTables() {
        List<RestaurantTable> tables = tableService.getAllTables();
        return ResponseEntity.ok(tables);
    }

    @GetMapping("/available")
    public ResponseEntity<List<RestaurantTable>> getAvailableTables(
            @RequestParam LocalDateTime startTime,
            @RequestParam int duration) {
        List<RestaurantTable> availableTables = tableService.getAvailableTables(startTime, duration);
        return ResponseEntity.ok(availableTables);
    }

    @GetMapping("/reserved")
    public ResponseEntity<List<RestaurantTable>> getReservedTables(
            @RequestParam LocalDateTime startTime,
            @RequestParam int duration) {
        List<RestaurantTable> tables = tableService.getReservedTables(startTime, duration);
        return ResponseEntity.ok(tables);
    }

    @GetMapping("/available/inside")
    public ResponseEntity<List<RestaurantTable>> getAvailableTablesInside(
            @RequestParam LocalDateTime startTime,
            @RequestParam int duration) {
        List<RestaurantTable> availableTables = tableService.getAvailableTables(startTime, duration);
        availableTables = availableTables.stream().filter(table -> !table.isOutside()).toList();
        return ResponseEntity.ok(availableTables);
    }

    @GetMapping("/reserved/inside")
    public ResponseEntity<List<RestaurantTable>> getReservedTablesInside(
            @RequestParam LocalDateTime startTime,
            @RequestParam int duration) {
        List<RestaurantTable> tables = tableService.getReservedTables(startTime, duration);
        tables = tables.stream().filter(table -> !table.isOutside()).toList();
        return ResponseEntity.ok(tables);
    }

    @GetMapping("/available/outside")
    public ResponseEntity<List<RestaurantTable>> getAvailableTablesOutside(
            @RequestParam LocalDateTime startTime,
            @RequestParam int duration) {
        List<RestaurantTable> availableTables = tableService.getAvailableTables(startTime, duration);
        availableTables = availableTables.stream().filter(RestaurantTable::isOutside).toList();
        return ResponseEntity.ok(availableTables);
    }

    @GetMapping("/reserved/outside")
    public ResponseEntity<List<RestaurantTable>> getReservedTablesOutside(
            @RequestParam LocalDateTime startTime,
            @RequestParam int duration) {
        List<RestaurantTable> tables = tableService.getReservedTables(startTime, duration);
        tables = tables.stream().filter(table -> table.isOutside()).toList();
        return ResponseEntity.ok(tables);
    }

    @GetMapping("/{id}")
    public ResponseEntity<?> getTableById(@PathVariable Long id) {
        Optional<RestaurantTable> table = tableService.getTableById(id);
        if (table.isPresent()) {
            return ResponseEntity.ok(table.get());
        }
        return ResponseEntity.status(HttpStatus.NOT_FOUND).body("Table not found.");
    }

    @PutMapping("/{id}/reserve")
    public ResponseEntity<?> reserveTable(
            @PathVariable Long id,
            @RequestBody ReservationRequest reservationRequest)
    {
        try{
            Reservation reservation = reservationService.reserveTable(id, reservationRequest);
            return ResponseEntity.ok(reservation);
        } catch (IllegalArgumentException e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST).body("Invalid reservation parameters.");
        }
    }
}
