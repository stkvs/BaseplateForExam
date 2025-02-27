import mysql.connector as mysql

db = mysql.connect(
    host="localhost",
    user="root",
    password="",
    database="permissionDB"
)

cursor = db.cursor()

sql = '''CREATE TABLE IF NOT EXISTS userDetails (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)'''
cursor.execute(sql)

sql = '''CREATE TABLE IF NOT EXISTS userPermissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    permission VARCHAR(255) NOT NULL,
    FOREIGN KEY (userID) REFERENCES userDetails(userID)
)'''
cursor.execute(sql)

db.close()

print("Tables created successfully!")