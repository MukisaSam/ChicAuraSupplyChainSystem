import pandas as pd
import numpy as np
import joblib
import matplotlib
matplotlib.use('Agg')  # Use non-interactive backend for web server
import matplotlib.pyplot as plt
from pathlib import Path
import logging
import argparse
import sys
import os

# Add current directory to path
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

try:
    from demand_model import MLDemandModel, preprocess_df
except ImportError as e:
    print(f"ERROR: Failed to import demand_model: {e}")
    sys.exit(1)

logging.basicConfig(level=logging.INFO, format="%(asctime)s %(levelname)s:%(message)s")

# List of all locations used in your model
ALL_LOCATIONS = [
    "Kampala", "Entebbe", "Jinja", "Gulu", "Wakiso", "Mukono", "Mbarara", "Hoima",
    "Arua", "Mbale", "Masaka", "Moroto", "Kabale", "Lira", "Moyo", "Kiryandongo",
    "Kiboga", "Kisoro", "Mityana", "Kamuli", "Kaliro", "Kibale", "Kasese", "Kabarole"
    # Add any other locations your model expects
]

def main():
    try:
        parser = argparse.ArgumentParser(description='Generate monthly demand forecast')
        parser.add_argument('--product_name', required=True, help='Product name to forecast')
        parser.add_argument('--unit_price', type=float, required=True, help='Unit price of the product')
        parser.add_argument('--location', required=True, help='Location for the forecast')
        parser.add_argument('--output_dir', default='../SupplyChain/public/forecast_plots', help='Output directory for the forecast plot')

        args = parser.parse_args()

        # Create output directory
        output_dir = Path(args.output_dir)
        output_dir.mkdir(parents=True, exist_ok=True)

        # Load model components
        model_path = Path('demand_model.pkl')
        encoders_path = Path('label_encoders.pkl')
        features_path = Path('model_features.pkl')
        
        if not model_path.exists():
            print(f"ERROR: Model file not found: {model_path.absolute()}")
            print("Please run: python demand_model.py")
            sys.exit(1)
            
        if not encoders_path.exists() or not features_path.exists():
            print(f"ERROR: Model components not found")
            print("Please run: python demand_model.py")
            sys.exit(1)

        try:
            # Load model components
            model_instance = MLDemandModel()
            model_instance.model = joblib.load(model_path)
            model_instance.label_encoders = joblib.load(encoders_path)
            model_instance.feature_columns = joblib.load(features_path)
            model_instance.is_trained = True
            
            logging.info("Model loaded successfully")
        except Exception as e:
            print(f"ERROR: Failed to load model: {e}")
            sys.exit(1)

        # Generate future dates for next 12 months
        future_dates = pd.date_range(start=pd.Timestamp.today().normalize(), periods=12, freq='MS')

        logging.info(f"Forecasting monthly demand for {args.product_name} at {args.location} with price ${args.unit_price}")

        if args.location.lower() == "countrywide":
            # Aggregate predictions for all locations
            monthly_predictions = np.zeros(len(future_dates))
            for loc in ALL_LOCATIONS:
                future_df = pd.DataFrame({
                    'product_name': [args.product_name] * len(future_dates),
                    'sales_date': future_dates,
                    'unit_price': [args.unit_price] * len(future_dates),
                    'location': [loc] * len(future_dates),
                })
                preds = model_instance.predict(future_df)
                preds = np.maximum(preds, 0)
                monthly_predictions += preds * 30  # Aggregate monthly predictions
            logging.info(f"Aggregated monthly predictions for all locations")
        else:
            # Single location prediction
            future_df = pd.DataFrame({
                'product_name': [args.product_name] * len(future_dates),
                'sales_date': future_dates,
                'unit_price': [args.unit_price] * len(future_dates),
                'location': [args.location] * len(future_dates),
            })
            predictions = model_instance.predict(future_df)
            predictions = np.maximum(predictions, 0)
            monthly_predictions = predictions * 30

        logging.info(f"Generated monthly predictions for {len(monthly_predictions)} months")
        logging.info(f"Monthly prediction range: {monthly_predictions.min():.2f} to {monthly_predictions.max():.2f}")
        
        # Create the plot
        plt.figure(figsize=(15, 8))
        
        # Plot the forecast
        plt.plot(future_dates, monthly_predictions, marker='o', linewidth=3, markersize=8, 
                 color='#1f77b4', label='Predicted Monthly Demand')
        
        # Fill area under the curve
        plt.fill_between(future_dates, monthly_predictions, alpha=0.3, color='#1f77b4')
        
        # Add trend line
        x_numeric = np.arange(len(monthly_predictions))
        if len(monthly_predictions) > 1:
            z = np.polyfit(x_numeric, monthly_predictions, 1)
            p = np.poly1d(z)
            plt.plot(future_dates, p(x_numeric), "--", 
                     color='red', alpha=0.8, linewidth=2, label='Trend Line')
        
        # Customize the plot
        plt.title(f'12-Month Demand Forecast\n{args.product_name} at {args.location} (Price: Ugx{args.unit_price:,.0f})', 
                  fontsize=16, fontweight='bold', pad=20)
        plt.xlabel('Month', fontsize=12)
        plt.ylabel('Predicted Demand (units)', fontsize=12)
        plt.grid(True, linestyle='--', alpha=0.7)
        plt.legend(fontsize=12)
        plt.xticks(rotation=45)
        
        # Format x-axis
        plt.gca().xaxis.set_major_formatter(plt.matplotlib.dates.DateFormatter('%Y-%m'))
        plt.gca().xaxis.set_major_locator(plt.matplotlib.dates.MonthLocator(interval=2))
        
        # Add summary statistics
        total_demand = monthly_predictions.sum()
        avg_demand = monthly_predictions.mean()
        max_demand = monthly_predictions.max()
        min_demand = monthly_predictions.min()
        
        stats_text = f'Total: {total_demand:.0f} units\nAvg: {avg_demand:.1f} units/month\nMax: {max_demand:.1f} units\nMin: {min_demand:.1f} units'
        plt.text(0.02, 0.98, stats_text, transform=plt.gca().transAxes, 
                 bbox=dict(boxstyle='round', facecolor='white', alpha=0.8),
                 verticalalignment='top', fontsize=10)
        
        plt.tight_layout()

        # Use consistent filename - this will replace the existing file
        plot_filename = "forecast_monthly_latest.png"
        plot_path = output_dir / plot_filename
        
        try:
            plt.savefig(plot_path, dpi=300, bbox_inches='tight')
            logging.info(f"Monthly forecast plot saved to {plot_path}")
            
            # Output the pattern that Laravel expects
            print(f"SUCCESS: Monthly forecast generated for {args.product_name} at {args.location}")
            print(f"FORECAST_MONTHLY_IMAGE:{plot_filename}")
            print(f"TOTAL_DEMAND:{total_demand:.0f}")
            print(f"AVG_DEMAND:{avg_demand:.1f}")
            print(f"FORECAST_PERIOD:{future_dates[0].strftime('%Y-%m')} to {future_dates[-1].strftime('%Y-%m')}")
            
        except Exception as e:
            print(f"ERROR: Error saving plot: {e}")
            sys.exit(1)
        
        plt.close()

    except Exception as e:
        print(f"ERROR: Unexpected error in main: {e}")
        import traceback
        traceback.print_exc()
        sys.exit(1)

if __name__ == "__main__":
    main()