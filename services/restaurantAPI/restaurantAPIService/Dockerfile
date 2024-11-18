# Gebruik een officiële OpenJDK 17 Alpine image (kleiner dan de standaard JDK image)
FROM eclipse-temurin:17-jdk-alpine
WORKDIR /app
COPY build/libs/*.jar app.jar
RUN ls -la .
ENTRYPOINT ["java", "-jar", "app.jar"]
