package db


import (
	"agendaAPIService/graph/model"
	"database/sql"
	"fmt"
	"log"
	_ "github.com/lib/pq" // Voeg de PostgreSQL-driver toe
)

// db is de databaseverbinding die we in andere delen van de code zullen gebruiken
var db *sql.DB

func InitDB(dataSourceName string) {
	var err error
	db, err = sql.Open("postgres", dataSourceName)
	if err != nil {
		log.Fatalf("Failed to connect to the database: %v", err)
	}

	// Ping de database om te controleren of de verbinding werkt
	if err = db.Ping(); err != nil {
		log.Fatalf("Failed to ping database: %v", err)
	}
}

func StringToRole(role string) (model.Role, error) {
	switch role {
	case "DOCTOR":
		return model.RoleDoctor, nil
	case "PATIENT":
		return model.RolePatient, nil
	default:
		return model.RoleDoctor, fmt.Errorf("unknown role: %s", role)
	}
}

func CreateOrGetDate(input model.DateInput) (*model.Date, error) {
    var date model.Date

    err := db.QueryRow("SELECT id FROM dates WHERE (day, month, year, hour, minute) = ($1, $2, $3, $4, $5)",
        input.Day, input.Month, input.Year, input.Hour, input.Minute).Scan(&date.ID)
    if err != nil {
        if err == sql.ErrNoRows {
            err = db.QueryRow("INSERT INTO dates (day, month, year, hour, minute) VALUES ($1, $2, $3, $4, $5) RETURNING id",
                input.Day, input.Month, input.Year, input.Hour, input.Minute).Scan(&date.ID)
            if err != nil {
                log.Printf("Failed to insert date: %v", err)
                return nil, fmt.Errorf("failed to insert date: %v", err)
            }
        } else {
            log.Printf("Failed to get date: %v", err)
            return nil, fmt.Errorf("failed to get date: %v", err)
        }
    }

    date.Day = input.Day
    date.Month = input.Month
    date.Year = input.Year
    date.Hour = input.Hour
    date.Minute = input.Minute

    log.Printf("Date created or retrieved with ID: %s", date.ID)
    return &date, nil
}

func GetDate(dateID string) (*model.Date, error) {
	var date model.Date
	err := db.QueryRow("SELECT id, day, month, year, hour, minute FROM dates WHERE id = $1", dateID).
		Scan(&date.ID, &date.Day, &date.Month, &date.Year, &date.Hour, &date.Minute)
	if err != nil {
		return nil, err
	}
	return &date, nil
}

func GetReccuranceFrequency(recurring string) (*int, error) {
    var recurrence int

    err := db.QueryRow("SELECT id FROM recurrence WHERE (frequency) = ($1)",
        recurring).Scan(&recurrence)

    log.Printf("Recurrence: %d", recurrence)

    if err != nil {
        if err == sql.ErrNoRows {
            err = db.QueryRow("INSERT INTO recurrence (frequency) VALUES ($1) RETURNING id",
                recurring).Scan(&recurrence)
            if err != nil {
                log.Printf("Failed to insert recurrence: %v", err)
                return nil, fmt.Errorf("failed to insert recurrence: %v", err)
            }
        } else {
            log.Printf("Failed to get recurrence: %v", err)
            return nil, fmt.Errorf("failed to get recurrence: %v", err)
        }
    }

    return &recurrence, nil
    
}


func GetReccuranceFrequencyByID(id int) (*model.RecurrenceFrequency, error) {
    var frequency string

    err := db.QueryRow("SELECT frequency FROM recurrence WHERE id = $1",
        id).Scan(&frequency)

    if err != nil {
        return nil, fmt.Errorf("error getting recurrence frequency: %w", err)
    }

    reccurence, err := StringToRecurrenceFrequency(frequency)
    if err != nil {
        return nil, fmt.Errorf("error converting recurrence frequency: %w", err)
    }

    return &reccurence, nil
}

func StringToRecurrenceFrequency(frequency string) (model.RecurrenceFrequency, error) {
    switch frequency {
    case "NONE":
        return model.RecurrenceFrequencyNone, nil
    case "DAILY":
        return model.RecurrenceFrequencyDaily, nil
    case "WEEKLY":
        return model.RecurrenceFrequencyWeekly, nil
    case "MONTHLY":
        return model.RecurrenceFrequencyMonthly, nil
    case "YEARLY":
        return model.RecurrenceFrequencyYearly, nil
    default:
        return model.RecurrenceFrequencyNone, fmt.Errorf("unknown recurrence frequency: %s", frequency)
    }
}

