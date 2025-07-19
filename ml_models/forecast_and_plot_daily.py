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

def main():
    try:
        parser = argparse.ArgumentParser(description='Generate daily demand forecast')
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

        # Generate future dates (next 30 days)
        future_dates = pd.date_range(start=pd.Timestamp.today().normalize(), periods=30, freq='D')

        logging.info(f"Forecasting for {args.product_name} at {args.location} with price ${args.unit_price}")
        
        # Build DataFrame for this product/location
        future_df = pd.DataFrame({
            'product_name': [args.product_name] * len(future_dates),
            'sales_date': future_dates,
            'unit_price': [args.unit_price] * len(future_dates),
            'location': [args.location] * len(future_dates),
        })

        try:
            # Generate predictions
            predictions = model_instance.predict(future_df)
            
            logging.info(f"Generated predictions for {len(predictions)} days")
            logging.info(f"Prediction range: {predictions.min():.2f} to {predictions.max():.2f}")
            
        except Exception as e:
            print(f"ERROR: Error during prediction: {e}")
            import traceback
            traceback.print_exc()
            sys.exit(1)

        # Create the plot
        plt.figure(figsize=(15, 8))
        
        # Plot the forecast
        plt.plot(future_dates, predictions, marker='o', linewidth=3, markersize=8, 
                 color='#2E86AB', label='Predicted Daily Demand')
        
        # Fill area under the curve
        plt.fill_between(future_dates, predictions, alpha=0.3, color='#2E86AB')
        
        # Add trend line
        x_numeric = np.arange(len(predictions))
        if len(predictions) > 1:
            z = np.polyfit(x_numeric, predictions, 1)
            p = np.poly1d(z)
            plt.plot(future_dates, p(x_numeric), "--", 
                     color='#A23B72', alpha=0.8, linewidth=2, label='Trend Line')
        
        # Customize the plot
        plt.title(f'30-Day Demand Forecast\n{args.product_name} at {args.location} (Price: Ugx{args.unit_price:,.0f})', 
                  fontsize=16, fontweight='bold', pad=20)
        plt.xlabel('Date', fontsize=12)
        plt.ylabel('Predicted Demand (units)', fontsize=12)
        plt.grid(True, linestyle='--', alpha=0.7)
        plt.legend(fontsize=12)
        plt.xticks(rotation=45)
        
        # Format x-axis
        plt.gca().xaxis.set_major_formatter(plt.matplotlib.dates.DateFormatter('%Y-%m-%d'))
        plt.gca().xaxis.set_major_locator(plt.matplotlib.dates.DayLocator(interval=5))
        
        # Add summary statistics
        total_demand = predictions.sum()
        avg_demand = predictions.mean()
        max_demand = predictions.max()
        min_demand = predictions.min()
        
        stats_text = f'Total: {total_demand:.0f} units\nAvg: {avg_demand:.1f} units/day\nMax: {max_demand:.1f} units\nMin: {min_demand:.1f} units'
        plt.text(0.02, 0.98, stats_text, transform=plt.gca().transAxes, 
                 bbox=dict(boxstyle='round', facecolor='white', alpha=0.8),
                 verticalalignment='top', fontsize=10)
        
        plt.tight_layout()

        # Use consistent filename
        plot_filename = "forecast_daily_latest.png"
        plot_path = output_dir / plot_filename
        
        try:
            plt.savefig(plot_path, dpi=300, bbox_inches='tight')
            logging.info(f"Daily forecast plot saved to {plot_path}")
            
            # Output the pattern that Laravel expects
            print(f"SUCCESS: Daily forecast generated for {args.product_name} at {args.location}")
            print(f"FORECAST_IMAGE:{plot_filename}")
            print(f"TOTAL_DEMAND:{total_demand:.0f}")
            print(f"AVG_DEMAND:{avg_demand:.1f}")
            print(f"FORECAST_PERIOD:{future_dates[0].strftime('%Y-%m-%d')} to {future_dates[-1].strftime('%Y-%m-%d')}")
            
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