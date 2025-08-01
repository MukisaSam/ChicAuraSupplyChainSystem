import pandas as pd
import numpy as np
import joblib
import matplotlib.pyplot as plt
import logging
from demand_model import preprocess_df, load_model, ProphetDemandModel
from db_config import get_connector

logging.basicConfig(level=logging.INFO, format="%(asctime)s %(levelname)s:%(message)s")

# Load model and features
model = joblib.load('demand_model.pkl')
feature_columns = joblib.load('model_features.pkl')

# Load historical data with actual demand
conn = get_connector()
query = """
        SELECT t1.unit_price as unit_price, t1.quantity as demand,t2.order_date as sales_date, t2.delivery_address as location, t3.name as product_name from order_items t1 join orders t2 on t1.order_id = t2.id join items t3 on t1.item_id = t3.id;

"""
df = pd.read_sql(query, conn)
conn.close()

# Preprocess
df = df.drop_duplicates()
df = df.dropna(subset=['product_name', 'sales_date', 'unit_price', 'location', 'demand'])

# Save original sales_date for grouping
df['sales_date'] = pd.to_datetime(df['sales_date'])
# For daily, no need to convert to period/month

# Define products/locations to plot
products = [
    {'product_name': 'T-shirt', 'unit_price': 5.0, 'location': 'Wakiso'},
    # Add more dicts as needed
]

# Only keep rows matching the selected products/locations
mask = pd.Series(False, index=df.index)
for prod in products:
    cond = (
        (df['product_name'] == prod['product_name']) &
        (df['location'] == prod['location'])
    )
    mask = mask | cond
df = df[mask]

# Preprocess for model (drop demand column for prediction)
X = preprocess_df(df.drop(columns=['demand']), feature_columns)
df['predicted_demand'] = model.predict(X)

# Group by day, product, location
grouped = df.groupby(['sales_date', 'product_name', 'location']).agg(
    actual_demand=('demand', 'sum'),
    predicted_demand=('predicted_demand', 'sum')
).reset_index()

# Plot for each product/location in the products list
for prod in products:
    product = prod['product_name']
    location = prod['location']
    group = grouped[(grouped['product_name'] == product) & (grouped['location'] == location)]
    if group.empty:
        logging.warning(f"No data for {product} at {location}")
        continue
    plt.figure(figsize=(12, 6))
    plt.plot(group['sales_date'], group['actual_demand'], marker='o', label='Actual Demand')
    plt.plot(group['sales_date'], group['predicted_demand'], marker='o', label='Predicted Demand')
    plt.title(f"Actual vs Predicted Daily Demand\n{product} at {location}")
    plt.xlabel('Date')
    plt.ylabel('Demand (units)')
    plt.grid(True, linestyle='--', alpha=0.7)
    plt.legend()
    plt.xticks(rotation=45)
    plt.tight_layout()
    plt.show()
    logging.info(f"Displayed actual vs predicted plot for {product} at {location}")
    plt.close()