package be.kuleuven;

import be.kuleuven.services.MenuServiceImpl;
import io.grpc.Server;
import io.grpc.ServerBuilder;

import java.io.IOException;

public class GrpcServer {

    private final int port = 50051; // De poort waarop de server luistert
    private final Server server;

    public GrpcServer() {
        // Stel je gRPC server in met de service
        this.server = ServerBuilder.forPort(port)
                .addService(new MenuServiceImpl()) // Voeg je implementatie toe
                .build();
    }

    // Methode om de server te starten
    public void start() throws IOException {
        server.start();
        System.out.println("Server gestart op poort " + port);
        Runtime.getRuntime().addShutdownHook(new Thread(() -> {
            System.err.println("Server wordt afgesloten...");
            GrpcServer.this.stop();
            System.err.println("Server afgesloten.");
        }));
    }

    // Methode om de server te stoppen
    public void stop() {
        if (server != null) {
            server.shutdown();
        }
    }

    // Methode om de server te blokkeren en te wachten tot deze afgesloten wordt
    public void blockUntilShutdown() throws InterruptedException {
        if (server != null) {
            server.awaitTermination();
        }
    }

    public static void main(String[] args) throws IOException, InterruptedException {
        final GrpcServer server = new GrpcServer();
        server.start();
        server.blockUntilShutdown();
    }
}

