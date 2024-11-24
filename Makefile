# Makefile voor het starten van de Docker services

# De naam van de services die je wilt starten
SERVICES=restaurantapi db_restaurant adminer menuapi 
up:
	docker-compose up $(SERVICES)

down:
	docker-compose down $(SERVICES)

# Docker-compose commando om de logs van de containers te bekijken
logs:
	docker-compose logs -f $(SERVICES)

build:
	docker-compose build $(SERVICES) --progress=plain

# Docker-compose commando om de containers te stoppen en te verwijderen
clean:
	docker-compose down --volumes --remove-orphans

upall:
	docker-compose up

downall:
	docker-compose down

buildall:
	docker-compose build --progress=plain
