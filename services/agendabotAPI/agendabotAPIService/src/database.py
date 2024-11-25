import psycopg2
import logging

DB_HOST = "db_agenda"
DB_NAME = "agenda_database"
DB_USER = "postgres"
DB_PASSWORD = "postgres"

def get_db_connection():
    try:
        conn = psycopg2.connect(
            host=DB_HOST,
            database=DB_NAME,
            user=DB_USER,
            password=DB_PASSWORD
        )
        return conn
    except Exception as e:
        logging.error(f"Database connectie fout: {e}")
        raise

def get_doctor_agendas():
    """
    Haalt alle agenda-ID's op van dokters.
    """
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT id FROM agendas WHERE role = 'doctor'
        """)
        doctors_agendas = [row[0] for row in cur.fetchall()]
        cur.close()
        return doctors_agendas
    except Exception as e:
        logging.error(f"Fout bij het ophalen van doktersagenda's: {e}")
        raise
    finally:
        conn.close()

def get_free_appointments_for_doctor(doctor_agenda_id):
    """
    Haalt vrije afspraken op voor een specifieke dokter.
    """
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT a.id, a.date, a.patient_id
            FROM appointments a
            WHERE a.doctor_agenda_id = %s AND a.patient_id IS NULL
        """, (doctor_agenda_id,))

        rows = cur.fetchall()
        cur.close()
        appointments = [
            {"id": row[0], "date": row[1], "doctor_agenda_id": doctor_agenda_id, "date" : get_date_by_id(get_agenda_item_by_id(row[2])["date_id"])}
            for row in rows
        ]
        print(appointments)
        return appointments
    except Exception as e:
        logging.error(f"Fout bij het ophalen van afspraken voor dokter {doctor_agenda_id}: {e}")
        raise
    finally:
        conn.close()

def get_agenda_item_by_id(agenda_item_id):
    """
    Haalt een specifiek agenda-item op.
    """
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT ai.id, ai.title, ai.description, ai.date_id
            FROM agenda_items ai
            WHERE ai.id = %s
        """, (agenda_item_id,))

        row = cur.fetchone()
        cur.close()
        agenda_item = {
            "id": row[0],
            "title": row[1],
            "description": row[2],
            "date_id": row[3]
        }
        return agenda_item
    except Exception as e:
        logging.error(f"Fout bij het ophalen van agenda-item {agenda_item_id}: {e}")
        raise
    finally:
        conn.close()

def get_date_by_id(date_id):
    """
    Haalt een specifieke datum op.
    """
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT d.day, d.month, d.year, d.hour, d.minute
            FROM dates d
            WHERE d.id = %s
        """, (date_id,))

        row = cur.fetchone()
        cur.close()
        date = {
            "id": row[0],
            "day": row[1],
            "month": row[2],
            "year": row[3],
            "hour": row[4],
            "minute": row[5]
        }
        dateString = f"{date['day']}/{date['month']}/{date['year']} {date['hour']}:{date['minute']}"

        date_obj = datetime.strptime(dateString, "%d/%m/%Y %H:%M")
        iso_date = date_obj.isoformat()

        return iso_date
    except Exception as e:
        logging.error(f"Fout bij het ophalen van datum {date_id}: {e}")
        raise
    finally:
        conn.close()
