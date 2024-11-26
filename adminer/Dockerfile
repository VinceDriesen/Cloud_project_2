# Gebruik de officiële Adminer image
FROM adminer:4.8.1-standalone

# Zorg ervoor dat we als root gebruiker werken voor het installeren van pakketten
USER root

# Installeer de MongoDB PHP-extensie
RUN apt-get update \
    && apt-get install -y php-mongodb \
    && rm -rf /var/lib/apt/lists/*

# Zet de standaardpoort van Adminer
EXPOSE 8080

# Zet de gebruiker terug naar de standaard gebruiker (optioneel)
USER adminer
