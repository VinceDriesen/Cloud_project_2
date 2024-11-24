package be.kuleuven.services;

import com.google.protobuf.util.JsonFormat;
import io.grpc.stub.StreamObserver;
import be.kuleuven.menu.*;

import java.io.IOException;
import java.io.InputStream;
import java.nio.charset.StandardCharsets;

public class MenuServiceImpl extends MenuServiceGrpc.MenuServiceImplBase {

    private MenuResponse menuData;

    public MenuServiceImpl() {
        try (InputStream inputStream = getClass().getClassLoader().getResourceAsStream("menu.json")) {
            if (inputStream == null) {
                throw new RuntimeException("Het bestand 'menu.json' kon niet worden gevonden");
            }
            byte[] jsonData = inputStream.readAllBytes();
            String jsonStr = new String(jsonData, StandardCharsets.UTF_8);

            MenuResponse.Builder builder = MenuResponse.newBuilder();
            JsonFormat.parser().merge(jsonStr, builder);
            menuData = builder.build();
        } catch (IOException e) {
            throw new RuntimeException("Kon menu.json niet laden", e);
        }
    }

    @Override
    public void getFood(MenuRequest request, StreamObserver<FoodResponse> responseObserver) {
        FoodResponse response = FoodResponse.newBuilder()
                .addAllFoodItems(menuData.getFoodItemsList())
                .build();
        responseObserver.onNext(response);
        responseObserver.onCompleted();
    }

    @Override
    public void getMenu(MenuRequest request, StreamObserver<MenuResponse> responseObserver) {
        responseObserver.onNext(menuData);
        responseObserver.onCompleted();
    }

    @Override
    public void getDesserts(MenuRequest request, StreamObserver<DesertResponse> responseObserver) {
        DesertResponse response = DesertResponse.newBuilder()
                .addAllDesertItems(menuData.getDesertItemsList())
                .build();
        responseObserver.onNext(response);
        responseObserver.onCompleted();
    }

    @Override
    public void getDrinks(MenuRequest request, StreamObserver<DrinkResponse> responseObserver) {
        DrinkResponse response = DrinkResponse.newBuilder()
                .addAllDrinkItems(menuData.getDrinkItemsList())
                .build();
        responseObserver.onNext(response);
        responseObserver.onCompleted();
    }
}
