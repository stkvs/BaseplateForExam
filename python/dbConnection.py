def dbConnect():
    import json
    import mysql.connector as mysql
    import os
    
    config_path = os.path.join(os.path.dirname(__file__), 'db_config.json')
    with open(config_path, 'r') as f:
        db_config = json.load(f)
    
    # Make sure we have a proper connection object
    try:
        conn = mysql.connect(**db_config)
        return conn
    except mysql.Error as err:
        print(f"Database connection failed: {err}")
        raise