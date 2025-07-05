import pandas as pd
import numpy as np
import joblib
import matplotlib.pyplot as plt
from pathlib import Path
import logging
from demand_model import preprocess_df  # Reuse your main preprocessing

logging.basicConfig(level=logging.INFO, format="%(asctime)s %(levelname)s:%(message)s")

# Create output directory
output_dir = Path("forecast_plots")
output_dir.mkdir(exist_ok=True)

# Load model and features
model = joblib.load('demand_model.pkl')
feature_columns = joblib.load('model_features.pkl')

# Sample products
products = [
    {'product_name': 'T-shirt', 'unit_price': 5.0, 'location': 'Wakiso'}
]

# Generate future dates (next 30 days)
future_dates = pd.date_range(start=pd.Timestamp.today().normalize(), periods=30, freq='D')

for prod in products:
    logging.info(f"Forecasting for {prod['product_name']} at {prod['location']}")
    
    # Build DataFrame for this product/location
    future_df = pd.DataFrame({
        'product_name': [prod['product_name']] * len(future_dates),
        'sales_date': future_dates,
        #'units_sold': [0] * len(future_dates),  # 0 for forecasting
        'unit_price': [prod['unit_price']] * len(future_dates),
        'location': [prod['location']] * len(future_dates),
        #'stock_level': [100] * len(future_dates),  # Example: constant stock
        #'total_sale_amount': [0.0] * len(future_dates)
    })

    # Preprocess data using shared function
    processed_df = preprocess_df(future_df, feature_columns)
    
    # Predict demand
    predicted = model.predict(processed_df)
    
    # Create results DataFrame
    results = pd.DataFrame({
        'date': future_dates,
        'product': prod['product_name'],
        'location': prod['location'],
        'predicted_demand': predicted.round(2)
    })
    
    # Save forecast to CSV (safe filenames)
    # safe_product = prod['product_name'].replace(" ", "_")
    # safe_location = prod['location'].replace(" ", "_")
    # csv_path = output_dir / f"forecast_{safe_product}_{safe_location}.csv"
    # results.to_csv(csv_path, index=False)
    # logging.info(f"Saved forecast to {csv_path}")
    
    # Plot
    plt.figure(figsize=(12, 6))
    plt.plot(future_dates, predicted, marker='o', label='Predicted Demand')
    
    # Optional: Add a simple confidence interval (±std of predictions)
    if hasattr(model, "estimators_"):
        preds = np.stack([est.predict(processed_df) for est in model.estimators_])
        std = preds.std(axis=0)
        plt.fill_between(future_dates, predicted - std, predicted + std, color='gray', alpha=0.2, label='Confidence Interval')
    
    plt.title(f"30-Day Demand Forecast\n{prod['product_name']} at {prod['location']}")
    plt.xlabel('Date')
    plt.ylabel('Predicted Demand (units)')
    plt.grid(True, linestyle='--', alpha=0.7)
    plt.legend()
    plt.xticks(rotation=45)
    plt.tight_layout()

    # Show plot
    plt.show()
    logging.info(f"Displayed plot for {prod['product_name']} at {prod['location']}")
    plt.close()