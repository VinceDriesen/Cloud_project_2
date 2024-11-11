CREATE TABLE dates (
    id SERIAL PRIMARY KEY,
    day INT NOT NULL CHECK (day >= 1 AND day <= 31),
    month INT NOT NULL CHECK (month >= 1 AND month <= 12),
    year INT NOT NULL CHECK (year >= 1900 AND year <= 2100),
    hour INT NOT NULL CHECK (hour >= 0 AND hour < 24),
    minute INT NOT NULL CHECK (minute >= 0 AND minute < 60),
    UNIQUE (day, month, year, hour, minute)
);

CREATE TABLE agendas (
    id SERIAL PRIMARY KEY,
    owner INT NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('DOCTOR', 'PATIENT')),
    shared_with INT[]
);

CREATE TABLE agenda_items (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    duration INT NOT NULL,
    date_id INT NOT NULL REFERENCES dates(id)
);

CREATE TABLE agenda_item_participants (
    agenda_item_id INT NOT NULL REFERENCES agenda_items(id),
    agenda_id INT NOT NULL REFERENCES agendas(id),
    PRIMARY KEY (agenda_item_id, agenda_id)
);

CREATE TABLE recurrence (
    id SERIAL PRIMARY KEY,
    frequency VARCHAR(20) NOT NULL CHECK (frequency IN ('DAILY', 'WEEKLY', 'MONTHLY', 'YEARLY', 'NONE'))
);

CREATE TABLE appointments (
    id SERIAL PRIMARY KEY,
    agenda_item_id INT NOT NULL REFERENCES agenda_items(id),
    doctor_agenda_id INT NOT NULL REFERENCES agendas(id),
    patient_agenda_id INT REFERENCES agendas(id),
    recurrence_id INT NOT NULL REFERENCES recurrence(id)
);
