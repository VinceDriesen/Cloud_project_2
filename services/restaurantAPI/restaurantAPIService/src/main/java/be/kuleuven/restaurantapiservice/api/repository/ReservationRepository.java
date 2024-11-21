package be.kuleuven.restaurantapiservice.api.repository;

import be.kuleuven.restaurantapiservice.api.model.Reservation;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

public interface ReservationRepository extends JpaRepository<Reservation, Long> {
    List<Reservation> findByTableId(Long tableId);
}
