package be.kuleuven.restaurantapiservice.api.model;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import jakarta.persistence.*;

import java.util.ArrayList;
import java.util.List;

@Entity
@JsonIgnoreProperties({"reservations"})
public class RestaurantTable {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @OneToMany(mappedBy = "table", cascade = CascadeType.ALL, orphanRemoval = true)
    private List<Reservation> reservations = new ArrayList<>();


    private int tableNumber;
    private int numberOfPeople;
    private boolean isOutside;

    public RestaurantTable(int tableNumber, int numberOfPeople, boolean isOutside) {
        this.tableNumber = tableNumber;
        this.numberOfPeople = numberOfPeople;
        this.isOutside = isOutside;
    }

    public List<Reservation> getReservations() {
        return reservations;
    }

    public void addReservation(Reservation reservation) {
        reservations.add(reservation);
        reservation.setTable(this);
    }

    public RestaurantTable() {}

    public Long getId() {
        return id;
    }

    public int getTableNumber() {
        return tableNumber;
    }

    public int getNumberOfPeople() {
        return numberOfPeople;
    }

    public void setOutside(boolean outside) {
        isOutside = outside;
    }

    public boolean isOutside() {
        return isOutside;
    }

    public void setNumberOfPeople(int numberOfPeople) {
        this.numberOfPeople = numberOfPeople;
    }

}
