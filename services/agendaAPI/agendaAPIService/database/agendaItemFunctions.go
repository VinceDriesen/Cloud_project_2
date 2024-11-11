package db

import (
	"agendaAPIService/graph/model"
	"database/sql"
	"fmt"
	"log"
	"strconv"
)


func CreateAgendaItem(agendaID string, input model.CreateAgendaItem) (*model.AgendaItem, error) {
    var agendaItem model.AgendaItem

	if(input.Participants == nil) {
		return nil, fmt.Errorf("participants input is required")
	}

    if input.Date == nil {
        return nil, fmt.Errorf("date input is required")
    }

    _, err := GetAgenda(agendaID)
    if err != nil {
        return nil, fmt.Errorf("agenda with ID %s does not exist: %v", agendaID, err)
    }

    date, err := CreateOrGetDate(*input.Date)
    if err != nil {
        return nil, err
    }

    err = db.QueryRow(`
        INSERT INTO agenda_items (title, description, duration, date_id) 
        VALUES ($1, $2, $3, $4) 
        RETURNING id
    `, input.Title, input.Description, input.Duration, date.ID).Scan(&agendaItem.ID)
    if err != nil {
        return nil, fmt.Errorf("failed to create agenda item: %v", err)
    }

    agendaItem.Title = input.Title
    agendaItem.Description = input.Description
    agendaItem.Duration = input.Duration
    agendaItem.Date = date

    if len(input.Participants) == 0 {
        return nil, fmt.Errorf("no participants provided")
    }

    var participantIDs []int
    for _, participantID := range input.Participants {
        participant, err := GetAgenda(participantID)
        if err != nil {
            return nil, fmt.Errorf("failed to get participant agenda with ID %s: %v", participantID, err)
        }

        participantIDInt, err := strconv.Atoi(participant.ID)
        if err != nil {
            return nil, fmt.Errorf("failed to convert participant ID to int: %v", err)
        }
        participantIDs = append(participantIDs, participantIDInt)
    }

    for _, participantID := range participantIDs {
        _, err = db.Exec(`
            INSERT INTO agenda_item_participants (agenda_item_id, agenda_id) 
            VALUES ($1, $2)
        `, agendaItem.ID, participantID)
        if err != nil {
            return nil, fmt.Errorf("failed to add participant to agenda item: %v", err)
        }
    }

    participants, err := GetAgendaParticipants(agendaItem.ID)
    if err != nil {
        return nil, fmt.Errorf("failed to get participants: %v", err)
    }
    agendaItem.Participants = participants

    return &agendaItem, nil
}

func UpdateAgendaItem(id string, input model.UpdateAgendaItem) (*model.AgendaItem, error) {
    if input.Date == nil {
        return nil, fmt.Errorf("date input is required")
    }

    // Maak of haal de datum-ID op
    date, err := CreateOrGetDate(*input.Date)
    if err != nil {
        return nil, err
    }
    dateIDInt, err := strconv.Atoi(date.ID)
    if err != nil {
        return nil, fmt.Errorf("failed to convert date ID to int: %v", err)
    }

    // Begin met de query opbouw voor update van `agenda_items`
    query := "UPDATE agenda_items SET date_id = " + strconv.Itoa(dateIDInt)

    if input.Title != nil {
        query += ", title = '" + *input.Title + "'"
    }
    if input.Description != nil {
        query += ", description = '" + *input.Description + "'"
    }
    if input.Duration != nil && *input.Duration != 0 {
        query += ", duration = " + strconv.Itoa(*input.Duration)
    }

    // Voeg WHERE-clausule toe
    query += " WHERE id = " + id

    // Voer de query uit
    _, err = db.Exec(query)
    if err != nil {
        log.Printf("Error updating agenda item: %v", err)
        return nil, err
    }

    // Verwijder bestaande deelnemers uit `agenda_item_participants`
    deleteParticipantsQuery := "DELETE FROM agenda_item_participants WHERE agenda_item_id = " + id
    _, err = db.Exec(deleteParticipantsQuery)
    if err != nil {
        log.Printf("Error deleting participants: %v", err)
        return nil, err
    }

    // Voeg nieuwe deelnemers toe, indien aanwezig
    for _, participantID := range input.Participants {
        insertParticipantQuery := "INSERT INTO agenda_item_participants (agenda_item_id, agenda_id) VALUES (" + id + ", " + participantID + ")"
        _, err = db.Exec(insertParticipantQuery)
        if err != nil {
            log.Printf("Error inserting participant: %v", err)
            return nil, err
        }
    }

    // Haal het bijgewerkte agenda item op om terug te geven
    updatedAgendaItem, err := GetAgendaItem(id)
    if err != nil {
        log.Printf("Error fetching updated agenda item: %v", err)
    }
    return updatedAgendaItem, err
}


func DeleteAgendaItem(id string) error {
	_, err := db.Exec("DELETE FROM agenda_items WHERE id = $1", id)
	return err
}

func GetAgendaItems(agendaID string) ([]*model.AgendaItem, error) {
    rows, err := db.Query(`
        SELECT ai.id, ai.title, ai.description, ai.duration, ai.date_id
        FROM agenda_items ai
        INNER JOIN agenda_item_participants aip ON aip.agenda_item_id = ai.id
        WHERE aip.agenda_id = $1
    `, agendaID)
    if err != nil {
        return nil, err
    }
    defer rows.Close()

    var agendaItems []*model.AgendaItem
    for rows.Next() {
        var agendaItem model.AgendaItem
        var dateID string

        if err := rows.Scan(&agendaItem.ID, &agendaItem.Title, &agendaItem.Description, &agendaItem.Duration, &dateID); err != nil {
            return nil, err
        }

        date, err := GetDate(dateID)
        if err != nil {
            return nil, err
        }

        agendaItem.Date = date

        participants, err := GetAgendaParticipants(agendaItem.ID)
        if err != nil {
            return nil, err
        }
        agendaItem.Participants = participants

        agendaItems = append(agendaItems, &agendaItem)
    }
    if err = rows.Err(); err != nil {
        return nil, err
    }
    return agendaItems, nil
}

func GetAgendaItem(id string) (*model.AgendaItem, error) {
    row := db.QueryRow(`
        SELECT 
            agenda_items.id, 
            agenda_items.title, 
            agenda_items.description, 
            agenda_items.duration, 
            agenda_items.date_id, 
            dates.day, 
            dates.month, 
            dates.year, 
            dates.hour, 
            dates.minute
        FROM agenda_items
        LEFT JOIN dates ON agenda_items.date_id = dates.id
        WHERE agenda_items.id = $1
    `, id)

    var agendaItem model.AgendaItem
    var title sql.NullString
    var description sql.NullString
    var duration sql.NullInt64
    var dateID sql.NullInt64
    var day sql.NullInt64
    var month sql.NullInt64
    var year sql.NullInt64
    var hour sql.NullInt64
    var minute sql.NullInt64

    if err := row.Scan(
        &agendaItem.ID,
        &title,
        &description,
        &duration,
        &dateID,
        &day,
        &month,
        &year,
        &hour,
        &minute,
    ); err != nil {
        if err == sql.ErrNoRows {
            return nil, nil
        }
        return nil, fmt.Errorf("error scanning row: %w", err)
    }

    agendaItem.Title = title.String
    agendaItem.Description = &description.String
    agendaItem.Duration = int(duration.Int64)
    agendaItem.Date = &model.Date{
        ID:     fmt.Sprintf("%d", dateID.Int64),
        Day:    int(day.Int64),
        Month:  int(month.Int64),
        Year:   int(year.Int64),
        Hour:   int(hour.Int64),
        Minute: int(minute.Int64),
    }

    participants, err := GetAgendaParticipants(agendaItem.ID)
    if err != nil {
        return nil, fmt.Errorf("error getting participants: %w", err)
    }

    agendaItem.Participants = participants

    return &agendaItem, nil
}



func GetAgendaParticipants(agendaitemID string) ([]*model.Agenda, error) {
	log.Printf("Getting participants for agenda item %s", agendaitemID)
    rows, err := db.Query(`
        SELECT 
            agendas.id, 
            agendas.owner, 
            agendas.role
        FROM agenda_item_participants
        JOIN agendas ON agenda_item_participants.agenda_id = agendas.id
        WHERE agenda_item_participants.agenda_item_id = $1
    `, agendaitemID)
    if err != nil {
        return nil, fmt.Errorf("error executing query: %w", err)
    }
    defer rows.Close()

    var participants []*model.Agenda

    for rows.Next() {
        var agenda model.Agenda
        var role string

        if err := rows.Scan(&agenda.ID, &agenda.Owner, &role); err != nil {
            return nil, fmt.Errorf("error scanning participant row: %w", err)
        }

        newRole, err := StringToRole(role)
        if err != nil {
            return nil, fmt.Errorf("error converting role: %w", err)
        }
        agenda.Role = newRole

        participants = append(participants, &agenda)
    }

    if err := rows.Err(); err != nil {
        return nil, fmt.Errorf("error during rows iteration: %w", err)
    }

    return participants, nil
}

