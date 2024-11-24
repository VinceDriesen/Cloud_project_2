FROM eclipse-temurin:17-jdk-alpine
WORKDIR /app
RUN ls -la .
COPY build/libs/*.jar service.jar
RUN ls -la .
EXPOSE 50051
ENTRYPOINT ["java", "-jar", "service.jar"]
