import mysql.connector
from mysql.connector import pooling, Error
import os
import logging
from typing import Optional, Dict, Any
import time
from contextlib import contextmanager

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class DatabaseConfig:
    """Enhanced database configuration with connection pooling and error handling"""
    
    def __init__(self):
        self.config = {
            'host': os.getenv('DB_HOST', 'localhost'),
            'user': os.getenv('DB_USER', 'root'),
            'password': os.getenv('DB_PASSWORD', ''),
            'database': os.getenv('DB_NAME', 'mukisa'),
            'charset': 'utf8mb4',
            'autocommit': True,
            'raise_on_warnings': True,
            'sql_mode': 'TRADITIONAL',
            'use_unicode': True,
            'connect_timeout': 30,
            'buffered': True
        }
        
        self.pool_config = {
            'pool_name': 'ml_models_pool',
            'pool_size': 10,
            'pool_reset_session': True,
            'autocommit': True
        }
        
        self.connection_pool = None
        self._initialize_pool()
    
    def _initialize_pool(self):
        """Initialize connection pool"""
        try:
            pool_config = {**self.config, **self.pool_config}
            self.connection_pool = pooling.MySQLConnectionPool(**pool_config)
            logger.info("Database connection pool initialized successfully")
        except Error as e:
            logger.error(f"Error creating connection pool: {e}")
            self.connection_pool = None
    
    @contextmanager
    def get_connection(self):
        """Get database connection with proper error handling and cleanup"""
        connection = None
        try:
            if self.connection_pool:
                connection = self.connection_pool.get_connection()
            else:
                connection = mysql.connector.connect(**self.config)
            
            yield connection
            
        except Error as e:
            logger.error(f"Database error: {e}")
            if connection and connection.is_connected():
                connection.rollback()
            raise
        finally:
            if connection and connection.is_connected():
                connection.close()
    
    def execute_query(self, query: str, params: Optional[tuple] = None, 
                     fetch_all: bool = True) -> Optional[list]:
        """Execute query with error handling and retries"""
        max_retries = 3
        retry_delay = 1
        
        for attempt in range(max_retries):
            try:
                with self.get_connection() as connection:
                    cursor = connection.cursor(dictionary=True)
                    cursor.execute(query, params or ())
                    
                    if query.strip().upper().startswith('SELECT'):
                        result = cursor.fetchall() if fetch_all else cursor.fetchone()
                        return result
                    else:
                        connection.commit()
                        return cursor.rowcount
                        
            except Error as e:
                logger.warning(f"Query attempt {attempt + 1} failed: {e}")
                if attempt < max_retries - 1:
                    time.sleep(retry_delay * (2 ** attempt))  # Exponential backoff
                else:
                    logger.error(f"Query failed after {max_retries} attempts: {e}")
                    raise
    
    def get_table_schema(self, table_name: str) -> list:
        """Get table schema information"""
        query = """
        SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, COLUMN_KEY
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s
        ORDER BY ORDINAL_POSITION
        """
        return self.execute_query(query, (self.config['database'], table_name))
    
    def check_connection(self) -> bool:
        """Check if database connection is working"""
        try:
            with self.get_connection() as connection:
                cursor = connection.cursor()
                cursor.execute("SELECT 1")
                return True
        except:
            return False

# Global database instance
db = DatabaseConfig()

def get_connector():
    """Legacy function for backward compatibility"""
    return mysql.connector.connect(**db.config)

def get_connection():
    """Get database connection using connection pool"""
    return db.get_connection()

def execute_query(query: str, params: Optional[tuple] = None, fetch_all: bool = True):
    """Execute query with enhanced error handling"""
    return db.execute_query(query, params, fetch_all)

def get_demand_data(product_filter: Optional[str] = None, 
                   location_filter: Optional[str] = None,
                   start_date: Optional[str] = None,
                   end_date: Optional[str] = None) -> list:
    """Get demand data with optional filters"""
    
    base_query = """
    SELECT 
        t1.unit_price as unit_price, 
        t1.quantity as demand,
        t2.order_date as sales_date, 
        t2.delivery_address as location, 
        t3.name as product_name,
        t3.id as product_id,
        t3.category,
        t3.material,
        t1.size,
        t1.color,
        t2.total_amount,
        t2.id as order_id
    FROM order_items t1 
    JOIN orders t2 ON t1.order_id = t2.id 
    JOIN items t3 ON t1.item_id = t3.id
    WHERE 1=1
    """
    
    params = []
    
    if product_filter:
        base_query += " AND t3.name LIKE %s"
        params.append(f"%{product_filter}%")
    
    if location_filter:
        base_query += " AND t2.delivery_address LIKE %s"
        params.append(f"%{location_filter}%")
    
    if start_date:
        base_query += " AND t2.order_date >= %s"
        params.append(start_date)
    
    if end_date:
        base_query += " AND t2.order_date <= %s"
        params.append(end_date)
    
    base_query += " ORDER BY t2.order_date"
    
    return execute_query(base_query, tuple(params))

def get_customer_data(include_orders: bool = False) -> list:
    """Get customer data for recommendation system"""
    query = """
    SELECT 
        c.*,
        COUNT(co.id) as total_orders,
        SUM(co.total_amount) as total_spent,
        MAX(co.created_at) as last_order_date,
        AVG(co.total_amount) as avg_order_value
    FROM customers c
    LEFT JOIN customer_orders co ON c.id = co.customer_id
    GROUP BY c.id
    """
    
    return execute_query(query)

def get_supplier_performance_data() -> list:
    """Get data for supplier performance analysis"""
    query = """
    SELECT 
        s.id as supplier_id,
        s.business_address,
        s.materials_supplied,
        u.name as supplier_name,
        sr.id as request_id,
        sr.quantity as requested_quantity,
        sr.due_date,
        sr.status as request_status,
        si.delivered_quantity,
        si.delivery_date,
        si.quality_rating,
        si.price as delivered_price,
        DATEDIFF(si.delivery_date, sr.due_date) as delivery_delay_days,
        i.name as item_name,
        i.category,
        sr.created_at as request_date
    FROM suppliers s
    JOIN users u ON s.user_id = u.id
    LEFT JOIN supply_requests sr ON s.id = sr.supplier_id
    LEFT JOIN supplied_items si ON sr.id = si.supply_request_id
    LEFT JOIN items i ON sr.item_id = i.id
    WHERE sr.id IS NOT NULL
    ORDER BY sr.created_at DESC
    """
    
    return execute_query(query)