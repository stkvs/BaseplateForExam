import sys
import json
import mysql.connector as mysql

def connect_to_db():
    try:
        db = mysql.connect(
            host="localhost",
            user="root",
            password="",
            database="permissiondb"
        )
        return db
    except mysql.Error as err:
        print(f"Error: {err}")
        sys.exit(1)

def parse_and_execute(data, db):
    try:
        cursor = db.cursor()
        parsed_data = json.loads(data)
        sql = parsed_data.get("sql")
        params = parsed_data.get("params", [])
        
        if sql:
            cursor.execute(sql, params)
            db.commit()
            print("SQL executed successfully")
        else:
            print("No SQL statement found in the data")
    except Exception as e:
        print(f"Error: {e}")

if len(sys.argv) > 1:
    try:
        data = sys.argv[1].replace("'", "\"").replace(":", "\":\"").replace(",", "\",\"").replace("{", "{\"").replace("}", "\"}")
        print("Received data:", data)
        
        db = connect_to_db()
        parse_and_execute(data, db)
        
    except Exception as e:
        print(f"Error: {e}")
