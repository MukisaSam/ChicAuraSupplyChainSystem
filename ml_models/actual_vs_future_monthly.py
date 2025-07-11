import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import joblib
from datetime import timedelta
from demand_model import preprocess_df, load_model, ProphetDemandModel
from db_config import get_connector

# --- CONFIG ---
months_to_forecast = 6  # Number of future months to predict
products = [
    {'product_name': 'T-shirt', 'unit_price': 5.0, 'location': 'Wakiso'},
    # Add more dicts as needed
]

# --- LOAD HISTORICAL DATA ---
conn = get_connector()
query = """
SELECT product_name, sales_date, unit_price, location, units_sold AS demand
FROM supplied_items
WHERE units_sold IS NOT NULL
"""
df = pd.read_sql(query, conn)
conn.close()

df['sales_date'] = pd.to_datetime(df['sales_date'])
df = df.drop_duplicates()
df = df.dropna(subset=['product_name', 'sales_date', 'unit_price', 'location', 'demand'])

# --- AGGREGATE HISTORICAL MONTHLY DEMAND ---
df['month'] = df['sales_date'].dt.to_period('M').dt.to_timestamp()
monthly_hist = df.groupby(['month', 'product_name', 'location']).agg(
    actual_demand=('demand', 'sum')
).reset_index()

# --- FORECAST FUTURE MONTHS ---
model = load_model()
feature_columns = joblib.load('model_features.pkl')

future_rows = []
for prod in products:
    # Get last month in historical data for this product/location
    mask = (
        (monthly_hist['product_name'] == prod['product_name']) &
        (monthly_hist['location'] == prod['location'])
    )
    if not monthly_hist[mask].empty:
        last_month = monthly_hist[mask]['month'].max()
    else:
        continue  # No history for this product/location

    for i in range(1, months_to_forecast + 1):
        future_month = (last_month + pd.DateOffset(months=i)).replace(day=1)
        row = prod.copy()
        row['sales_date'] = future_month
        future_rows.append(row)

future_df = pd.DataFrame(future_rows)
if not future_df.empty:
    X_future = preprocess_df(future_df, feature_columns)
    future_df['forecasted_demand'] = model.predict(X_future)
    future_df['month'] = future_df['sales_date'].dt.to_period('M').dt.to_timestamp()

# --- PLOT ---
for prod in products:
    product = prod['product_name']
    location = prod['location']
    hist = monthly_hist[
        (monthly_hist['product_name'] == product) &
        (monthly_hist['location'] == location)
    ]
    fut = future_df[
        (future_df['product_name'] == product) &
        (future_df['location'] == location)
    ] if not future_df.empty else pd.DataFrame()

    plt.figure(figsize=(12, 6))
    plt.plot(hist['month'], hist['actual_demand'], marker='o', label='Historical Demand')
    if not fut.empty:
        plt.plot(fut['month'], fut['forecasted_demand'], marker='o', linestyle='--', color='orange', label='Forecasted Demand')
    plt.title(f"Historical vs Forecasted Monthly Demand\n{product} at {location}")
    plt.xlabel('Month')
    plt.ylabel('Demand (units)')
    plt.grid(True, linestyle='--', alpha=0.7)
    plt.legend()
    plt.xticks(rotation=45)
    plt.tight_layout()
    plt.show()