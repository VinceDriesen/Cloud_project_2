FROM ubuntu:22.04

# Update en installeer benodigde tools
RUN apt-get update && apt-get install -y \
    software-properties-common \
    g++ \
    make \
    cmake \
    libssl-dev \
    mosquitto-clients \
    doxygen \
    git \
    wget \
    pkg-config \
    && apt-get clean

# Werkdirectory instellen
WORKDIR /app

# Installeer de Paho MQTT C library
RUN git clone https://github.com/eclipse/paho.mqtt.c.git && \
    cd paho.mqtt.c && \
    cmake -DCMAKE_BUILD_TYPE=Release -DPAHO_WITH_SSL=ON . && \
    make && make install && ldconfig && \
    rm -rf /app/paho.mqtt.c

# Installeer de Paho MQTT C++ library
RUN git clone https://github.com/eclipse/paho.mqtt.cpp.git && \
    cd paho.mqtt.cpp && \
    cmake -DCMAKE_BUILD_TYPE=Release -DPAHO_BUILD_DOCUMENTATION=FALSE -DPAHO_BUILD_SAMPLES=FALSE . && \
    make && make install && ldconfig && \
    rm -rf /app/paho.mqtt.cpp

# Zet de CMAKE_PREFIX_PATH voor CMake om libraries te vinden
ENV CMAKE_PREFIX_PATH="/usr/local"

# Kopieer de broncode in de container
COPY . /app

# Voeg de cmake directory toe aan CMAKE_MODULE_PATH (optioneel)
ENV CMAKE_MODULE_PATH="/app/cmake"

# Configureer en bouw het project met CMake
RUN cmake -S /app -B /app/build && \
    cmake --build /app/build && \
    ls -la /app/build

RUN ls -la
# Zet de entrypoint naar het uitvoerbare bestand
ENTRYPOINT ["/app/build/HospitalSensors"]
