import mysql.connector

def get_connector():
    return mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='supply_chain',
    )