package be.kuleuven.restaurantapiservice.api.model;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;

@Entity
public class RestaurantTable {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    private int tableNumber;
    private int numberOfPeople;
    private boolean isReserved;
    private boolean isOutside;

    public RestaurantTable(int tableNumber, int numberOfPeople, boolean isReserved, boolean isOutside) {
        this.tableNumber = tableNumber;
        this.numberOfPeople = numberOfPeople;
        this.isReserved = isReserved;
        this.isOutside = isOutside;
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

    public boolean isReserved() {
        return isReserved;
    }

    public void setReserved(boolean reserved) {
        isReserved = reserved;
    }

    public boolean isOutside() {
        return isOutside;
    }

    public void setNumberOfPeople(int numberOfPeople) {
        this.numberOfPeople = numberOfPeople;
    }

}
