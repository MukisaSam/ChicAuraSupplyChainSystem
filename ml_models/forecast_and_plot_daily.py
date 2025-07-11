import pandas as pd
import numpy as np
import joblib
import matplotlib.pyplot as plt
from pathlib import Path
import logging
import argparse
import sys
from demand_model import preprocess_df  # Reuse your main preprocessing

logging.basicConfig(level=logging.INFO, format="%(asctime)s %(levelname)s:%(message)s")

# Parse command line arguments
parser = argparse.ArgumentParser(description='Generate demand forecast for a specific product')
parser.add_argument('--product_name', required=True, help='Product name to forecast')
parser.add_argument('--unit_price', type=float, required=True, help='Unit price of the product')
parser.add_argument('--location', required=True, help='Location for the forecast')
parser.add_argument('--output_dir', default='../SupplyChain/public/forecast_plots', help='Output directory for the forecast plot')

args = parser.parse_args()

# Create output directory
output_dir = Path(args.output_dir)
output_dir.mkdir(exist_ok=True)

# Load model and features
try:
    model = joblib.load('demand_model.pkl')
    feature_columns = joblib.load('model_features.pkl')
except FileNotFoundError as e:
    logging.error(f"Model files not found: {e}")
    sys.exit(1)

# Use command line arguments
products = [
    {'product_name': args.product_name, 'unit_price': args.unit_price, 'location': args.location}
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

    # Save plot
    safe_product = prod['product_name'].replace(" ", "_").replace("/", "_")
    safe_location = prod['location'].replace(" ", "_").replace("/", "_")
    plot_filename = f"forecast_{safe_product}_{safe_location}_{pd.Timestamp.now().strftime('%Y%m%d_%H%M%S')}.png"
    plot_path = output_dir / plot_filename
    
    plt.savefig(plot_path, dpi=300, bbox_inches='tight')
    logging.info(f"Saved plot to {plot_path}")
    
    # Output the filename for Laravel to read
    print(f"FORECAST_IMAGE:{plot_filename}")
    
    plt.close()