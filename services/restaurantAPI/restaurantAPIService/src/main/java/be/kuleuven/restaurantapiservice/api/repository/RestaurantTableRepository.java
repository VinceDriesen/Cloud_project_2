package be.kuleuven.restaurantapiservice.api.repository;

import be.kuleuven.restaurantapiservice.api.model.RestaurantTable;
import org.springframework.data.jpa.repository.JpaRepository;

public interface RestaurantTableRepository extends JpaRepository<RestaurantTable, Long> {
}
