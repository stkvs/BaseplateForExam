import mysql.connector as mysql

db = mysql.connect(
    host="localhost",
    user="root",
    password=""
)

cursor = db.cursor()

sql = 'CREATE DATABASE IF NOT EXISTS permissionDB'
cursor.execute(sql)

db.close()

print("Database created successfully!")