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
parser = argparse.ArgumentParser(description='Generate monthly demand forecast for a specific product')
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

# Generate future months (next 12 months)
future_months = pd.date_range(start=pd.Timestamp.today().replace(day=1), periods=12, freq='MS')

for prod in products:
    logging.info(f"Forecasting for {prod['product_name']} at {prod['location']}")
    
    # Build DataFrame for this product/location
    future_df = pd.DataFrame({
        'product_name': [prod['product_name']] * len(future_months),
        'sales_date': future_months,
        'unit_price': [prod['unit_price']] * len(future_months),
        'location': [prod['location']] * len(future_months),
    })

    # Preprocess data using shared function
    processed_df = preprocess_df(future_df, feature_columns)
    
    # Predict demand
    predicted = model.predict(processed_df)
    
    # Create results DataFrame
    results = pd.DataFrame({
        'month': future_months,
        'product': prod['product_name'],
        'location': prod['location'],
        'predicted_demand': predicted.round(2)
    })
    
    # Plot
    plt.figure(figsize=(12, 6))
    plt.plot(future_months, predicted, marker='o', label='Predicted Demand')
    
    # Optional: Add a simple confidence interval (±std of predictions)
    if hasattr(model, "estimators_"):
        preds = np.stack([est.predict(processed_df) for est in model.estimators_])
        std = preds.std(axis=0)
        plt.fill_between(future_months, predicted - std, predicted + std, color='gray', alpha=0.2, label='Confidence Interval')
    
    plt.title(f"12-Month Demand Forecast\n{prod['product_name']} at {prod['location']}")
    plt.xlabel('Month')
    plt.ylabel('Predicted Demand (units)')
    plt.grid(True, linestyle='--', alpha=0.7)
    plt.legend()
    plt.xticks(rotation=45)
    plt.tight_layout()

    # Save plot
    safe_product = prod['product_name'].replace(" ", "_").replace("/", "_")
    safe_location = prod['location'].replace(" ", "_").replace("/", "_")
    plot_filename = f"forecast_monthly_{safe_product}_{safe_location}_{pd.Timestamp.now().strftime('%Y%m%d_%H%M%S')}.png"
    plot_path = output_dir / plot_filename
    
    plt.savefig(plot_path, dpi=300, bbox_inches='tight')
    logging.info(f"Saved monthly plot to {plot_path}")
    
    # Output the filename for Laravel to read
    print(f"FORECAST_MONTHLY_IMAGE:{plot_filename}")
    
    plt.close()