package be.kuleuven.restaurantapiservice.service;

import io.grpc.ManagedChannel;
import org.springframework.stereotype.Service;
import be.kuleuven.menu.*;
import io.grpc.StatusRuntimeException;

@Service
public class MenuClientService {

    private final MenuServiceGrpc.MenuServiceBlockingStub grpcClientStub;

    public MenuClientService(ManagedChannel managedChannel) {
        this.grpcClientStub = MenuServiceGrpc.newBlockingStub(managedChannel);
    }

    public MenuResponse getMenu(MenuRequest request) {
        try {
            // Roep de gRPC service aan met de blokkende stub
            return grpcClientStub.getMenu(request);
        } catch (StatusRuntimeException e) {
            // Foutafhandeling als de gRPC-aanroep mislukt
            System.out.println("RPC failed: " + e.getStatus());
            throw e;  // Gooi de exception opnieuw
        }
    }

    public FoodResponse getFood(MenuRequest request) {
        try {
            return grpcClientStub.getFood(request);
        } catch (StatusRuntimeException e) {
            System.out.println("RPC failed: " + e.getStatus());
            throw e;
        }
    }

    public DesertResponse getDesserts(MenuRequest request) {
        try {
            return grpcClientStub.getDesserts(request);
        } catch (StatusRuntimeException e) {
            System.out.println("RPC failed: " + e.getStatus());
            throw e;
        }
    }

    public DrinkResponse getDrinks(MenuRequest request) {
        try {
            return grpcClientStub.getDrinks(request);
        } catch (StatusRuntimeException e) {
            System.out.println("RPC failed: " + e.getStatus());
            throw e;
        }
    }
}
