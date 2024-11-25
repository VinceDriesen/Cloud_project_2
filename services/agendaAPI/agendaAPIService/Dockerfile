# Gebruik de officiële Go image als builder
FROM golang:1.23.2-alpine AS builder

# Stel de werkdirectory in
WORKDIR /app

# Update repositories en installeer vereiste pakketten afzonderlijk
RUN apk update && \
    apk add --no-cache wget && \
    apk add --no-cache git && \
    apk add --no-cache libstdc++ && \
    apk add --no-cache libgcc

# Download en installeer de migrate tool
RUN wget https://github.com/golang-migrate/migrate/releases/latest/download/migrate.linux-amd64.tar.gz && \
    tar -xvzf migrate.linux-amd64.tar.gz && \
    mv migrate /usr/local/bin/ && \
    rm migrate.linux-amd64.tar.gz

# Kopieer go.mod en go.sum om afhankelijkheden te downloaden
COPY go.mod go.sum ./
RUN go mod download

# Kopieer de rest van de broncode
COPY . ./

# Bouw de applicatie
RUN CGO_ENABLED=0 GOOS=linux go build -o /server

# Expose de poort
EXPOSE 8082

# De CMD-instructie verwijderen zodat de server niet automatisch start
CMD ["/server"]
