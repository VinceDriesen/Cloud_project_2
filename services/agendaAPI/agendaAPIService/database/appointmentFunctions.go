package db

import (
	"agendaAPIService/graph/model"
	"database/sql"
	"fmt"
	"log"
	"strconv"
	"strings"
)


func CreateAppointment(input model.CreateAppointment) (*model.Appointment, error) {

    if input.AgendaItemID == "" {
        return nil, fmt.Errorf("agenda item ID is required")
    }
    if input.Doctor == 0 {
        return nil, fmt.Errorf("doctor ID is required")
    }
    if input.Recurring == "" {
        return nil, fmt.Errorf("recurrence frequency is required")
    }

    reccurence, err := GetReccuranceFrequency(input.Recurring.String())
    if err != nil {
        return nil, fmt.Errorf("error getting recurrence frequency: %w", err)
    }

    appointmentID := 0

    log.Printf("Recurrence: %d", *reccurence)

    err = db.QueryRow(`
        INSERT INTO appointments (agenda_item_id, doctor_agenda_id, recurrence_id)
        VALUES ($1, $2, $3) RETURNING id`,
        input.AgendaItemID, input.Doctor, reccurence).Scan(&appointmentID)

    if err != nil {
		log.Printf("Error: %w", err)
        return nil, fmt.Errorf("error inserting appointment: %w", err)
    }

    appointment, err := GetAppointment(appointmentID)
    if err != nil {
        return nil, fmt.Errorf("error getting appointment: %w", err)
    }

    return appointment, nil
}


func GetAppointments() ([]*model.Appointment, error) {
    appointments := make([]*model.Appointment, 0)

    rows, err := db.Query(`
        SELECT 
            id, 
            agenda_item_id, 
            doctor_agenda_id, 
            patient_agenda_id, 
            recurrence_id 
        FROM appointments
    `)
    if err != nil {
        return nil, fmt.Errorf("error executing query: %w", err)
    }
    defer rows.Close()
    for rows.Next() {
        var appointmentID, agendaItemID, doctorAgendaID, recurrenceID int
        var patientAgendaID sql.NullInt32 

        var appointment model.Appointment
        var agendaItem model.AgendaItem
        appointment.AgendaItem = &agendaItem

        if err := rows.Scan(
            &appointmentID, &agendaItemID, &doctorAgendaID, &patientAgendaID, &recurrenceID,
        ); err != nil {
            return nil, fmt.Errorf("error scanning row: %w", err)
        }

        appointment.ID = strconv.Itoa(appointmentID)
        appointment.AgendaItem.ID = strconv.Itoa(agendaItemID)
        appointment.Doctor = doctorAgendaID

        if patientAgendaID.Valid {
            patientID := int(patientAgendaID.Int32)
            appointment.Patient = &patientID
        } else {
            appointment.Patient = nil
        }

        recurrence, err := GetReccuranceFrequencyByID(recurrenceID)
        if err != nil {
            return nil, fmt.Errorf("error getting recurrence frequency: %w", err)
        }
        appointment.Recurring = *recurrence

        if agendaItemID != 0 {
            agendaItem, err := GetAgendaItem(appointment.AgendaItem.ID)
            if err != nil {
                return nil, fmt.Errorf("error getting agenda item: %w", err)
            }
            appointment.AgendaItem = agendaItem
        }

        appointments = append(appointments, &appointment)
    }

    if err := rows.Err(); err != nil {
        return nil, fmt.Errorf("error during rows iteration: %w", err)
    }

    return appointments, nil
}


func GetAppointment(id int) (*model.Appointment, error) {
    var appointmentID, agendaItemID, doctorAgendaID, recurrenceID int
    var patientAgendaID sql.NullInt32

    var appointment model.Appointment
    var agendaItem model.AgendaItem
    appointment.AgendaItem = &agendaItem

    log.Printf("Querying row")
    row := db.QueryRow(`
        SELECT 
            id, 
            agenda_item_id, 
            doctor_agenda_id, 
            patient_agenda_id, 
            recurrence_id 
        FROM appointments 
        WHERE id = $1
        LIMIT 1
    `, id)

    log.Printf("Scanning row")
    if err := row.Scan(
        &appointmentID, &agendaItemID, &doctorAgendaID, &patientAgendaID, &recurrenceID,
    ); err != nil {
        log.Printf("Error scanning row: %v", err)
        if err == sql.ErrNoRows {
            log.Printf("No rows found")
            return nil, nil
        }
        return nil, fmt.Errorf("error scanning row: %w", err)
    }

    log.Printf("Appointment ID: %d", appointmentID)
    appointment.ID = strconv.Itoa(appointmentID)
    log.Printf("Agenda item ID: %d", agendaItemID)
    appointment.AgendaItem.ID = strconv.Itoa(agendaItemID)

    log.Printf("Doctor agenda ID: %d", doctorAgendaID)
    appointment.Doctor = doctorAgendaID

    if patientAgendaID.Valid {
        log.Printf("Patient agenda ID: %d", patientAgendaID.Int32)
        patientID := int(patientAgendaID.Int32)
        appointment.Patient = &patientID
    } else {
        log.Printf("Patient agenda ID is NULL")
        appointment.Patient = nil
    }

    log.Printf("Recurrence ID: %d", recurrenceID)
    recurrence, err := GetReccuranceFrequencyByID(recurrenceID)
    if err != nil {
        return nil, fmt.Errorf("error getting recurrence frequency: %w", err)
    }
    appointment.Recurring = *recurrence
    log.Printf("Appointment: %v", appointment)

    if agendaItemID != 0 {
        log.Printf("Getting agenda item")
        agendaItem, err := GetAgendaItem(appointment.AgendaItem.ID)
        if err != nil {
            return nil, fmt.Errorf("error getting agenda item: %w", err)
        }
        appointment.AgendaItem = agendaItem
    }

    return &appointment, nil
}




func GetAppointmentsFromAgenda(agendaID string) ([]*model.Appointment, error) {
    appointments := make([]*model.Appointment, 0)

    rows, err := db.Query(`
        SELECT 
            id, 
            agenda_item_id, 
            doctor_agenda_id, 
            patient_agenda_id, 
            recurrence_id 
        FROM appointments 
        WHERE doctor_agenda_id = $1 OR patient_agenda_id = $1
    `, agendaID)

    if err != nil {
        return nil, fmt.Errorf("error executing query: %w", err)
    }
    defer rows.Close()

    for rows.Next() {
        var appointment model.Appointment
        var agendaItem model.AgendaItem
        var patientAgendaID sql.NullInt32 
        var recurringID int

        appointment.AgendaItem = &agendaItem

        if err := rows.Scan(
            &appointment.ID, &agendaItem.ID, &appointment.Doctor, &patientAgendaID, &recurringID,
        ); err != nil {
            return nil, fmt.Errorf("error scanning row: %w", err)
        }

        if patientAgendaID.Valid {
            patientID := int(patientAgendaID.Int32)
            appointment.Patient = &patientID
        } else {
            appointment.Patient = nil
        }

        recurrence, err := GetReccuranceFrequencyByID(recurringID)
        if err != nil {
            return nil, fmt.Errorf("error getting recurrence frequency: %w", err)
        }
        appointment.Recurring = *recurrence

        if agendaItem.ID != "" {
            agendaItem, err := GetAgendaItem(appointment.AgendaItem.ID)
            if err != nil {
                return nil, fmt.Errorf("error getting agenda item: %w", err)
            }
            appointment.AgendaItem = agendaItem
        }

        appointments = append(appointments, &appointment)
    }

    if err := rows.Err(); err != nil {
        return nil, fmt.Errorf("error during rows iteration: %w", err)
    }

    return appointments, nil
}


func UpdateAppointment(id string, agendaItemID *string, patient *int, doctor *int, recurring *model.RecurrenceFrequency) (*model.Appointment, error) {
    if id == "" {
        return nil, fmt.Errorf("appointment ID is required")
    }

    updates := []string{}

    idInt, err := strconv.Atoi(id)
    if err != nil {
        return nil, fmt.Errorf("failed to convert id to int: %v", err)
    }
    appointMent, err := GetAppointment(idInt)
    if err != nil {
        return nil, fmt.Errorf("error getting appointment: %w", err)
    }

    if agendaItemID != nil {
        log.Printf("Agenda item ID: %s", *agendaItemID)
        updates = append(updates, "agenda_item_id = '" + *agendaItemID + "'")
    }

    if patient != nil {
        log.Printf("Patient: %d", *patient)
        updates = append(updates, "patient_agenda_id = " + strconv.Itoa(*patient))
    }

    if doctor != nil {
        log.Printf("Doctor: %d", *doctor)
        updates = append(updates, "doctor_agenda_id = " + strconv.Itoa(*doctor))
    }

    if recurring != nil {
        log.Printf("Recurring: %s", recurring.String())
        recurrence, err := GetReccuranceFrequency(recurring.String())
        if err != nil {
            return nil, fmt.Errorf("error getting recurrence frequency: %w", err)
        }
        updates = append(updates, "recurrence_id = " + strconv.Itoa(*recurrence))
    }

    if len(updates) == 0 {
        return nil, fmt.Errorf("no valid fields to update")
    }

    query := fmt.Sprintf("UPDATE appointments SET %s WHERE id = '%s'", strings.Join(updates, ", "), id)

    log.Printf("Query: %s", query)

    res, err := db.Exec(query)
    if err != nil {
        log.Printf("Error updating appointment: %v", err)
        return nil, err
    }

    n, err := res.RowsAffected()
    if err != nil {
        log.Printf("Error getting rows affected: %v", err)
        return nil, err
    }

    log.Printf("Rows affected: %d", n)

    if appointMent.AgendaItem.ID != "" {
        log.Printf("Agenda item ID to update: %s", appointMent.AgendaItem.ID)

        agendaItem, err := GetAgendaItem(appointMent.AgendaItem.ID)
        if err != nil {
            log.Printf("Failed to get agenda item: %v", err)
            return nil, err
        }

        if agendaItem == nil {
            return nil, fmt.Errorf("agenda item not found: %s", appointMent.AgendaItem.ID)
        }

        participants := []string{}
        if doctor == nil {
            doctor = &appointMent.Doctor
        }
        participants = append(participants, strconv.Itoa(*doctor))
        
        if patient != nil {
            participants = append(participants, strconv.Itoa(*patient))
        }

        updateData := model.UpdateAgendaItem{
            Date: func() *model.DateInput {
                date, err := CreateOrGetDate(model.DateInput{
                    Day:    agendaItem.Date.Day,
                    Month:  agendaItem.Date.Month,
                    Year:   agendaItem.Date.Year,
                    Hour:   agendaItem.Date.Hour,
                    Minute: agendaItem.Date.Minute,
                })
                if err != nil {
                    log.Printf("Error creating or getting date: %v", err)
                    return nil
                }
                return &model.DateInput{
                    Day:    date.Day,
                    Month:  date.Month,
                    Year:   date.Year,
                    Hour:   date.Hour,
                    Minute: date.Minute,
                }
            }(),
            Participants: participants,
        }

        log.Printf("Agenda item update data: %v", updateData)

        if _, err := UpdateAgendaItem(appointMent.AgendaItem.ID, updateData); err != nil {
            log.Printf("Error updating agenda item: %v", err)
            return nil, err
        }

        log.Printf("Updated agenda item")
    }

    updatedAppointment, err := GetAppointment(idInt)
    if err != nil {
        return nil, fmt.Errorf("error retrieving updated appointment: %v", err)
    }

    return updatedAppointment, nil
}

func DeleteAppointment(id string) error {
    idInt, err := strconv.Atoi(id)
    if err != nil {
        return err
    }
    appointment, err := GetAppointment(idInt)
    log.Printf("Appointment: %v", appointment)
    if err != nil {
        return err
    }

    _, err = db.Exec("DELETE FROM appointments WHERE id = $1", id)
    if err != nil {
        return err
    }
    err = DeleteAgendaItem(appointment.AgendaItem.ID)
    log.Printf("Deleted agenda item")
    if err != nil {
        log.Printf("Failed to delete agenda item: %v", err)
        return err
    }
    return nil
}



