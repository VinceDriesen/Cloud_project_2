package be.kuleuven.restaurantapiservice.api.controller;

import be.kuleuven.menu.MenuRequest;
import be.kuleuven.menu.MenuResponse;
import be.kuleuven.restaurantapiservice.api.dto.MenuRequestDto;
import be.kuleuven.restaurantapiservice.service.MenuClientService;
import com.google.protobuf.util.JsonFormat;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/api/menu")
public class RestaurantMenuController {
    private final MenuClientService menuClientService;
    private static final Logger logger = LoggerFactory.getLogger(RestaurantMenuController.class);

    @Autowired
    public RestaurantMenuController(MenuClientService menuClientService) {
        this.menuClientService = menuClientService;
    }

    @GetMapping("/getMenu")
    public ResponseEntity<String> getMenu() {
        try {
            MenuRequest menuRequest = MenuRequest.newBuilder().build();
            MenuResponse grpcResponse = menuClientService.getMenu(menuRequest);

            // Gebruik Protobuf's JSON-converter
            String jsonResponse = JsonFormat.printer().print(grpcResponse);
            return ResponseEntity.ok(jsonResponse);
        } catch (Exception e) {
            logger.error("Error occurred while fetching the menu: ", e);
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body("{\"error\": \"An error occurred while processing your request.\"}");
        }
    }
}
