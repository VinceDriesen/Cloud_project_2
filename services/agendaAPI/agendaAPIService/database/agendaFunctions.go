package db

import (
	"agendaAPIService/graph/model"
	"database/sql"
	"fmt"
)


func CreateAgenda(owner int, role model.Role) (*model.Agenda, error) {
    var agenda model.Agenda
    err := db.QueryRow("INSERT INTO agendas (owner, role) VALUES ($1, $2) RETURNING id", owner, role.String()).Scan(&agenda.ID)
    if err != nil {
        return nil, err
    }
    agenda.Owner = owner
    agenda.Role = role
    return &agenda, nil
}


func GetAgendaFromOwner(owner int) (*model.Agenda, error) {
    row := db.QueryRow(`
        SELECT 
            agendas.id AS agenda_id, 
            agendas.owner AS agenda_owner, 
            agendas.role AS agenda_role
        FROM agendas
        WHERE agendas.owner = $1 LIMIT 1
    `, owner)

    var agendaID int
    var ownerID int
    var role string
    var agenda model.Agenda
    if err := row.Scan(
        &agendaID, &ownerID, &role,
    ); err != nil {
        if err == sql.ErrNoRows {
            return nil, nil
        }
        return nil, fmt.Errorf("error scanning row: %w", err)
    }

    newRole, err := StringToRole(role)
    if err != nil {
        return nil, fmt.Errorf("error converting role: %w", err)
    }
    agenda.ID = fmt.Sprintf("%d", agendaID)
    agenda.Owner = ownerID
    agenda.Role = newRole

    agendaItems, err := GetAgendaItems(agenda.ID)
    if err != nil {
        return nil, fmt.Errorf("error getting agenda items: %w", err)
    }
    agenda.AgendaItems = agendaItems


    return &agenda, nil

}

func GetAgendas() ([]*model.Agenda, error) {
    rows, err := db.Query(`
        SELECT 
            agendas.id, 
            agendas.owner, 
            agendas.role
        FROM agendas
    `)
    if err != nil {
        return nil, fmt.Errorf("error executing query: %w", err)
    }
    defer rows.Close()

    var agendas []*model.Agenda

    for rows.Next() {
        var agenda model.Agenda
        var role string

        if err := rows.Scan(&agenda.ID, &agenda.Owner, &role); err != nil {
            return nil, fmt.Errorf("error scanning agenda row: %w", err)
        }

        newRole, err := StringToRole(role)
        if err != nil {
            return nil, fmt.Errorf("error converting role: %w", err)
        }
        agenda.Role = newRole

        agendaItems, err := GetAgendaItems(agenda.ID)
        if err != nil {
            return nil, fmt.Errorf("error getting agenda items for agenda %s: %w", agenda.ID, err)
        }
        agenda.AgendaItems = agendaItems

        agendas = append(agendas, &agenda)
    }

    if err := rows.Err(); err != nil {
        return nil, fmt.Errorf("error during rows iteration: %w", err)
    }

    return agendas, nil
}


func GetAgenda(id string) (*model.Agenda, error) {
    var agenda model.Agenda

    row := db.QueryRow(`
        SELECT 
            agendas.id, 
            agendas.owner, 
            agendas.role
        FROM agendas
        WHERE agendas.id = $1
    `, id)

    var agendaID int
    var owner int
    var role string

    if err := row.Scan(&agendaID, &owner, &role); err != nil {
        if err == sql.ErrNoRows {
            return nil, nil
        }
        return nil, fmt.Errorf("error scanning row: %w", err)
    }

    newRole, err := StringToRole(role)
    if err != nil {
        return nil, fmt.Errorf("error converting role: %w", err)
    }

    agenda.ID = fmt.Sprintf("%d", agendaID)
    agenda.Owner = owner
    agenda.Role = newRole

    agendaItems, err := GetAgendaItems(agenda.ID)
    if err != nil {
        return nil, fmt.Errorf("error getting agenda items: %w", err)
    }
    agenda.AgendaItems = agendaItems

    return &agenda, nil
}


func DeleteAgenda(id string) error {
	_, err := db.Exec("DELETE FROM agendas WHERE id = $1", id)
	return err
}

func UpdateAgenda(id string, owner *int, role *model.Role) (*model.Agenda, error) {
    var agenda model.Agenda

    if owner != nil && role != nil {
        err := db.QueryRow("UPDATE agendas SET owner = $1, role = $2 WHERE id = $3 RETURNING id, owner, role", *owner, role.String(), id).Scan(&agenda.ID, &agenda.Owner, &role)
        if err != nil {
            return nil, fmt.Errorf("failed to update agenda: %v", err)
        }
    } else {
        return nil, fmt.Errorf("input.owner and input.role are required")
    }

    newRole, err := StringToRole(role.String())
	if err != nil {
		return nil, fmt.Errorf("error converting role: %w", err)
	}
	agenda.Role = newRole

    return &agenda, nil
}