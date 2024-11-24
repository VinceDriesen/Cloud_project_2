package be.kuleuven.restaurantapiservice.client;

import io.grpc.ManagedChannel;
import io.grpc.ManagedChannelBuilder;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

@Configuration
public class MenuClientConfig {

    @Bean
    public static ManagedChannel managedChannel() {
        return ManagedChannelBuilder.forAddress("menuapi", 50051)
                .usePlaintext()
                .build();
    }
}
