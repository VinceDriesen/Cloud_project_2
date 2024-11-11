package main

import (
	db "agendaAPIService/database"
	"agendaAPIService/graph"
	"log"
	"net/http"
	"os"
	"github.com/99designs/gqlgen/graphql/handler"
	"github.com/99designs/gqlgen/graphql/playground"
)

const defaultPort = "8082"

func main() {
	port := os.Getenv("PORT")
	if port == "" {
		port = defaultPort
	}

	db.InitDB("host=db_agenda port=5432 user=postgres password=postgres dbname=agenda_database sslmode=disable")

	srv := handler.NewDefaultServer(graph.NewExecutableSchema(graph.Config{Resolvers: &graph.Resolver{}}))

	http.Handle("/", playground.Handler("GraphQL playground", "/query"))
	http.Handle("/query", srv)

	log.Printf("connect to http://localhost:%s/ for GraphQL playground", port)
	log.Fatal(http.ListenAndServe(":"+port, nil))
}

func init() {
    // Open of maak het logbestand
    file, err := os.OpenFile("app.log", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0666)
    if err != nil {
        log.Fatalf("failed to open log file: %v", err)
    }
    
    // Zet log output naar het bestand
    log.SetOutput(file)
}
