package be.kuleuven.restaurantapiservice.api.model;

import jakarta.persistence.*;

import java.time.LocalDateTime;

@Entity
public class Reservation {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne
    @JoinColumn(name = "table_id", nullable = false)
    private RestaurantTable table;

    private LocalDateTime startTime;
    private int duration;
    private String reservedBy;

    public Reservation(RestaurantTable table, LocalDateTime startTime, int duration, String reservedBy) {
        this.table = table;
        this.startTime = startTime;
        this.duration = duration;
        this.reservedBy = reservedBy;
    }

    public Reservation() {}

    public Long getId() {
        return id;
    }

    public RestaurantTable getTable() {
        return table;
    }

    public LocalDateTime getStartTime() {
        return startTime;
    }

    public int getDuration() {
        return duration;
    }

    public String getReservedBy() {
        return reservedBy;
    }

    public void setReservedBy(String reservedBy) {
        this.reservedBy = reservedBy;
    }

    public void setTable(RestaurantTable table) {
        this.table = table;
    }

    public void setStartTime(LocalDateTime startTime) {
        this.startTime = startTime;
    }

    public void setDuration(int duration) {
        this.duration = duration;
    }

    public void setId(Long id) {
        this.id = id;
    }
}
