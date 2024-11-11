Dit is mijn poging voor het school project van Cloud computing
Voor de databases integratie bij mijn services heb ik steeds voor een andere database gekozen i.p.v. steeds dezelfde te gebruiken. 
Dit heb ik gedaan om meer kennis op te doen van de verschillende soorten databases. Dit behoort niet direct tot dit vak, maar wou het zelf doen.

-- Laravel deel --
Hierin komt het volledige laravel project waarin in alle api's ga willen aansturen.


Bij het pullen van de applicatie ga je eerst het volgende moeten doen:
Het is belangrijk voor het runnen composer install te runnen in de folder. Anders zal het niet werken
Of je moet in de docker-compose het volume verwijderen, echter kan je dan geen aanpassingen meer maken.

.env maken met .env.example. Je moet de db aanpassen naar de docker-compose. Eenmaal als je in de docker exec zit moet je ook nog een key generaten met:
`php aritsn key:generate`



Eerst de docker file runnen : 
    docker-compose up --build - d

Ik maak hier gebruik van de database 'maria-db' zoals ook in de les is gebruikt

Om in de docker/shell te gaan maak dan gebruik van 
    docker exec -it hospital_laravel /bin/bash

Hierna zit je dan in de folder app, waarna je in de hospitalWebsite moet gaan. Hier staat nu je laravel project
Nu kan je ook alle commands van php artisan gebruiken. Zolang je in de docker shell blijft

Runnen kan met, maar dit wordt echter door de dockerfile gedaan:
    php artisan serve --host 0.0.0.0
    npm run dev of npm run build

Indien pagina te lang doet over refreshen volgende commandos:
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear

Je kan met de database verbinden via adminer:
    - Systeem:  MySql
    - Server: db_laravel
    - Username: laravel
    - Password: laravelPwd
    - Database: hospital_laravel_database

-- Services - userProfileAPISrvice --
Deze service is voor het volledig profiel van de gebruiker op te vragen
Ik maak hier gebruik van postgresql als database en .NET SOAP in C# voor de service implementatie
Hiervoor heb ik gekozen omdat SOAP vaak in C# wordt geschreven, maar het ook zou kunnen in java. 
Echter, aangezien ik java al beter ken dan C# én java wordt nog bij andere services gebruikt heb ik gekozen voor C#

Om in de docker/shell te gaan maak dan gebruik van 
    docker exec -it user_profile_api /bin/bash

Na docker-compose up heb je vaak ook nog dotnet ef nodig voor de database, Dit kan je dan doen met volgende commandos:
    dotnet tool install --global dotnet-ef
    export PATH="$PATH:/app/.dotnet/tools"
Hierna kan je gebruik maken van:
    dotnet ef migration add ...
    dotnet ef database update

Je kan met de database verbinden via adminer:
    - Systeem:  MySql
    - Server: db_user_profile
    - Username: postgres
    - Password: postgres
    - Database: user_profile_database

-- Services - agendaAPIService --
Deze service is voor agendas te kunnen maken, met afspraken. Elke agenda is van een owner met id die van laravel
Ook kan je dan indien het van een dokter is een afspraak maken, en deze dan van de owner met id die van laravel.
Ik heb hier gebruik van database TimescaleDB, dit is gebouwd op postgreSQL met C, de service heb ik geschreven in GOLANG en maakt gebruik van GraphQL.
Ik heb gekozen voor Go aangezien hier een heel makkelijke library voor is om samen te werken met GraphQL.
Ook omdat ik zelf nog geen Go kon, dus nu ken ik er al de basics van. Ik had het ook met Python kunnen doen in de les, maar wou wat moeilijker.

De TimescaleDB heb ik gebruikt aangezien ik wel wou gebruik maken van postgreSQL, en had dit gevonden op Google, dus wou ik het eens proberen.
Ook aangezien dit veel sneller is als je veel gebruik maakt van timestamps. Dit moet ik echter nog ondervinden. 

Echter heb ik wel gemerkt dat Go niet heel makkelijk is om met databases te werken zoals Laravel of .C# met .NET. 
Om bv tables te maken moet je je eigen MIGRATIONS schrijven, wat het iets ingewikkelder maakt

Om in de docker/shell te gaan maak dan gebruik van 
    docker exec -it agenda_api /bin/sh 

Verder moet je dan gebruik maken van graphQl door de volgende commandos:
    go run github.com/99designs/gqlgen generate - Als je bewerkingen in je schema hebt gedaan


Voor Database functies heb ik in de Dockerfile ook golang-migrate geïnstalleerd, hierna kan je gebruik maken van migration commands
Om bv de databasee te updaten kun je in je terminal `migrate -path ./migrations/ -database "postgresql://postgres:postgres@db_agenda:5432/agenda_database?sslmode=disable" -verbose up` uitvoeren. Of `migrate -path ./migrations/ -database "postgresql://postgres:postgres@db_agenda:5432/agenda_database?sslmode=disable" -verbose down` om het te verwijderen.

Je kan met de database verbinden via adminer:
    - Systeem:  PostgreSQL
    - Server: db_agenda
    - Username: postgres
    - Password: postgres
    - Database: agenda_database
