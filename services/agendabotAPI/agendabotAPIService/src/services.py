from src.database import get_db_connection


def get_doctor_agendas():
    """
    Haalt alle agenda-ID's op van dokters.
    """
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT id FROM agendas WHERE role = 'DOCTOR'
        """)
        doctors_agendas = [row[0] for row in cur.fetchall()]
        cur.close()
        return doctors_agendas
    except Exception as e:
        print(f"Fout bij het ophalen van doktersagenda's: {e}")
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
            SELECT a.id, a.agenda_item_id
            FROM appointments a
            WHERE a.doctor_agenda_id = %s AND a.patient_agenda_id IS NULL
        """, (doctor_agenda_id,))
        
        rows = cur.fetchall()
        cur.close()
        print(rows)
        appointments = [
            {"id": row[0], "doctor_id": doctor_agenda_id , "date": get_date_from_agenda_item_by_id(row[1])}
            for row in rows
        ]
    
        return appointments
    except Exception as e:
        print(f"Fout bij het ophalen van afspraken: {e}")
        raise
    finally:
        conn.close()

def get_date_from_agenda_item_by_id(agenda_item_id):
    """
    Haalt een specifiek agenda-item op.
    """
    print("agenda_item_id", agenda_item_id)
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT date_id FROM agenda_items WHERE id = %s
        """, (agenda_item_id,))
        date_id = cur.fetchone()[0]
        cur.close()
        return get_date_by_id(date_id)
    except Exception as e:
        print(f"Fout bij het ophalen van agenda-item: {e}")
        raise
    finally:
        conn.close()

import datetime

def get_date_by_id(date_id):
    """
    Haalt een specifieke datum op en converteert deze naar ISO 8601 formaat.
    """
    try:
        print("date_id", date_id)
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("""
            SELECT d.day, d.month, d.year, d.hour, d.minute FROM dates d WHERE d.id = %s
        """, (date_id,))
        rows = cur.fetchall()
        cur.close()
        print(rows)

        if not rows:
            return None
        row = rows[0]
        date_dict = {
            "day": row[0],
            "month": row[1],
            "year": row[2],
            "hour": row[3],
            "minute": row[4]
        }

        date_obj = datetime.datetime(
            year=date_dict["year"],
            month=date_dict["month"],
            day=date_dict["day"],
            hour=date_dict["hour"],
            minute=date_dict["minute"]
        )

        iso_date = date_obj.isoformat()
        return iso_date
    except Exception as e:
        print(f"Fout bij het ophalen van datum: {e}")
        raise
    finally:
        conn.close()


