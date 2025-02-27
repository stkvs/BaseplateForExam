import hashlib

import mysql.connector as mysql

# Connect to the database
db = mysql.connect(
    host="localhost",
    user="root",
    password="",
    database="permissionDB"
)

cursor = db.cursor()
users = [
    {"email": "admin@example.com", "password": "admin123", "permissions": ["admin"]},
    {"email": "editor@example.com", "password": "editor123", "permissions": ["paid"]},
    {"email": "viewer@example.com", "password": "viewer123", "permissions": ["free"]},
    {"email": "user1@example.com", "password": "user123", "permissions": ["free"]}
]

for user in users:
    hashed_password = hashlib.md5(user["password"].encode()).hexdigest()
    
    cursor.execute("INSERT INTO userDetails (email, password) VALUES (%s, %s)", 
                  (user["email"], hashed_password))
    
    user_id = cursor.lastrowid
    
    for permission in user["permissions"]:
        cursor.execute("INSERT INTO userPermissions (userID, permission) VALUES (%s, %s)",
                      (user_id, permission))

db.commit()
db.close()

print("Sample data created successfully!")