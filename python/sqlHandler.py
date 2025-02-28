import sys
import mysql.connector as mysql
import dbConnection as db

input_string = sys.argv[1]
parts = input_string.split('|')

sql_query = parts[0].replace('?', '%s')
params = tuple(parts[1:])

try:
    conn = db.dbConnect()
    cursor = conn.cursor()

    cursor.execute(sql_query, params)

    if sql_query.strip().upper().startswith("SELECT"):
        results = cursor.fetchall()
        if results:
            for result in results:
                print(result[0])
        else:
            print("No results found")
    else:
        conn.commit()
        print("Success")

    cursor.close()
    conn.close()

except mysql.Error as err:
    print(f"Error: {err}")
