# Gebruik een officiële Python image
FROM python:3.13-slim

# Stel de werkdirectory in
WORKDIR /app

# Kopieer de requirements.txt en installeer de dependencies
COPY requirements.txt ./
RUN pip install -r requirements.txt

# Kopieer de hele code naar de container
COPY src /app/src
COPY generated /app/generated

# Optioneel: debug stap om te controleren of de bestanden correct zijn gekopieerd
RUN ls -la .
RUN ls -la /app

# Exposeer poort voor gRPC server
EXPOSE 50051

CMD ["python", "-m", "src.server"]

