# Makefile voor het starten van de Docker services

# De naam van de services die je wilt starten
SERVICES=restaurant_api db_restaurant adminer

# Docker-compose commando om de containers te starten
up:
	docker-compose up $(SERVICES)

# Docker-compose commando om de containers te stoppen
down:
	docker-compose down

# Docker-compose commando om de logs van de containers te bekijken
logs:
	docker-compose logs -f $(SERVICES)

build:
	docker-compose build $(SERVICES) --progress=plain

# Docker-compose commando om de containers te stoppen en te verwijderen
clean:
	docker-compose down --volumes --remove-orphans
