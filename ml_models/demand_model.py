import pandas as pd
import numpy as np
import joblib
from prophet import Prophet
from prophet.diagnostics import cross_validation, performance_metrics
from db_config import get_connector
import logging
import warnings
from datetime import datetime, timedelta
import matplotlib.pyplot as plt
from sklearn.metrics import mean_absolute_percentage_error
warnings.filterwarnings('ignore')

logging.basicConfig(level=logging.INFO, format="%(asctime)s %(levelname)s:%(message)s")

class ProphetDemandModel:
    """
    A wrapper class that mimics the sklearn interface but uses Prophet internally
    Enhanced with proper train/test split and evaluation methodology
    """
    def __init__(self, prediction_frequency='D'):
        self.models = {}  # Store models for different product-location combinations
        self.feature_columns = ['product_name', 'location', 'unit_price', 'sales_date']
        self.prediction_frequency = prediction_frequency  # 'D' for daily, 'M' for monthly
        self.model_metadata = {}  # Store metadata about each model
        self.evaluation_results = {}  # Store evaluation results
        
    def _validate_data(self, df, min_data_points=30):
        """
        Validate data quality and return cleaned data
        """
        df = df.copy()
        df['sales_date'] = pd.to_datetime(df['sales_date'])
        df = df.sort_values('sales_date')
        
        # Remove duplicates and missing values
        initial_count = len(df)
        df = df.drop_duplicates()
        df = df.dropna(subset=['product_name', 'location', 'unit_price', 'sales_date', 'demand'])
        
        logging.info(f"Data validation: {initial_count} -> {len(df)} records after cleaning")
        
        # Check date range
        if len(df) > 0:
            date_range = df['sales_date'].max() - df['sales_date'].min()
            logging.info(f"Date range: {df['sales_date'].min()} to {df['sales_date'].max()} ({date_range.days} days)")
        
        return df
    
    def _prepare_data_for_prophet(self, df, product_name, location):
        """
        Prepare data for Prophet model with proper aggregation
        """
        # Filter data for specific product-location
        subset = df[(df['product_name'] == product_name) & (df['location'] == location)].copy()
        
        if len(subset) == 0:
            return None
            
        subset['sales_date'] = pd.to_datetime(subset['sales_date'])
        subset = subset.sort_values('sales_date')
        
        # Aggregate based on prediction frequency
        if self.prediction_frequency == 'M':
            # Monthly aggregation
            subset['period'] = subset['sales_date'].dt.to_period('M').dt.to_timestamp()
            agg_df = subset.groupby(['period', 'product_name', 'location']).agg({
                'demand': 'sum',
                'unit_price': 'mean'
            }).reset_index()
            agg_df = agg_df.rename(columns={'period': 'ds', 'demand': 'y'})
        else:
            # Daily aggregation
            agg_df = subset.groupby(['sales_date', 'product_name', 'location']).agg({
                'demand': 'sum',
                'unit_price': 'mean'
            }).reset_index()
            agg_df = agg_df.rename(columns={'sales_date': 'ds', 'demand': 'y'})
        
        # Add additional features
        agg_df['unit_price'] = agg_df['unit_price']
        agg_df['month'] = agg_df['ds'].dt.month
        agg_df['day_of_week'] = agg_df['ds'].dt.dayofweek
        agg_df['is_weekend'] = (agg_df['day_of_week'] >= 5).astype(int)
        agg_df['quarter'] = agg_df['ds'].dt.quarter
        
        logging.info(f"Prepared {len(agg_df)} data points for {product_name} at {location} ({self.prediction_frequency} frequency)")
        
        return agg_df
    
    def _train_test_split(self, df, test_size=0.2):
        """
        Implement proper time-based train/test split
        """
        df = df.sort_values('ds')
        split_index = int(len(df) * (1 - test_size))
        
        train_df = df.iloc[:split_index].copy()
        test_df = df.iloc[split_index:].copy()
        
        logging.info(f"Train/Test split: {len(train_df)} train, {len(test_df)} test")
        logging.info(f"Train period: {train_df['ds'].min()} to {train_df['ds'].max()}")
        logging.info(f"Test period: {test_df['ds'].min()} to {test_df['ds'].max()}")
        
        return train_df, test_df
    
    def _create_prophet_model(self):
        """
        Create Prophet model with optimized configuration
        """
        model = Prophet(
            daily_seasonality=True,    # Important for daily predictions
            weekly_seasonality=True,   # Critical for retail patterns
            yearly_seasonality=True,   # Seasonal clothing patterns
            changepoint_prior_scale=0.05,
            seasonality_prior_scale=10.0,
            holidays_prior_scale=10.0,
            seasonality_mode='multiplicative',  # Better for retail modeling
            interval_width=0.8,  # 80% confidence intervals
            uncertainty_samples=1000
        )
        
        # Add custom regressors
        model.add_regressor('unit_price')
        model.add_regressor('is_weekend')
        
        # Add monthly seasonality for better monthly patterns
        if self.prediction_frequency == 'M':
            model.add_seasonality(name='monthly', period=30.5, fourier_order=5)
        
        return model
    
    def fit(self, X, y):
        """
        Train Prophet models with proper train/test methodology
        """
        # Validate and prepare data
        df = X.copy()
        df['demand'] = y
        df = self._validate_data(df)
        
        if len(df) == 0:
            logging.error("No valid data available for training")
            return self
        
        # Get unique product-location combinations
        combinations = df[['product_name', 'location']].drop_duplicates()
        logging.info(f"Training models for {len(combinations)} product-location combinations")
        
        for _, combo in combinations.iterrows():
            product_name = combo['product_name']
            location = combo['location']
            
            logging.info(f"Training Prophet model for {product_name} at {location}")
            
            # Prepare data for this combination
            prophet_df = self._prepare_data_for_prophet(df, product_name, location)
            
            if prophet_df is None or len(prophet_df) < 30:
                logging.warning(f"Insufficient data for {product_name} at {location} ({len(prophet_df) if prophet_df is not None else 0} points)")
                continue
            
            try:
                # CRITICAL: Implement proper train/test split
                train_df, test_df = self._train_test_split(prophet_df, test_size=0.2)
                
                if len(train_df) < 20:
                    logging.warning(f"Insufficient training data for {product_name} at {location}")
                    continue
                
                # Create and train model on training data ONLY
                model = self._create_prophet_model()
                model.fit(train_df[['ds', 'y', 'unit_price', 'is_weekend']])
                
                # Evaluate on test data ONLY
                if len(test_df) > 0:
                    test_metrics = self._evaluate_model_performance(model, test_df)
                    self.evaluation_results[f"{product_name}_{location}"] = test_metrics
                
                # Store the model
                key = f"{product_name}_{location}"
                self.models[key] = model
                
                # Store metadata
                self.model_metadata[key] = {
                    'product_name': product_name,
                    'location': location,
                    'train_data_points': len(train_df),
                    'test_data_points': len(test_df),
                    'date_range': f"{prophet_df['ds'].min()} to {prophet_df['ds'].max()}",
                    'frequency': self.prediction_frequency
                }
                
                logging.info(f"Successfully trained model for {product_name} at {location}")
                
                # Prophet time series cross-validation (optional, for additional validation)
                if len(train_df) >= 60:  # Only if we have enough data
                    try:
                        initial_days = max(30, len(train_df) // 3)
                        horizon_days = min(30, len(train_df) // 4)
                        
                        df_cv = cross_validation(
                            model, 
                            initial=f'{initial_days} days', 
                            period='7 days', 
                            horizon=f'{horizon_days} days',
                            parallel="processes"
                        )
                        df_p = performance_metrics(df_cv)
                        cv_rmse = df_p['rmse'].mean()
                        cv_mae = df_p['mae'].mean()
                        cv_mape = df_p['mape'].mean()
                        
                        logging.info(f"Cross-validation results for {product_name} at {location}:")
                        logging.info(f"  CV RMSE: {cv_rmse:.2f}, CV MAE: {cv_mae:.2f}, CV MAPE: {cv_mape:.2f}%")
                        
                        # Store CV results
                        self.evaluation_results[key]['cv_rmse'] = cv_rmse
                        self.evaluation_results[key]['cv_mae'] = cv_mae
                        self.evaluation_results[key]['cv_mape'] = cv_mape
                        
                    except Exception as e:
                        logging.warning(f"Cross-validation failed for {product_name} at {location}: {e}")
                
            except Exception as e:
                logging.error(f"Failed to train model for {product_name} at {location}: {e}")
        
        logging.info(f"Training completed. Successfully trained {len(self.models)} models")
        
        return self
    
    def _evaluate_model_performance(self, model, test_df):
        """
        Evaluate model on test data only with comprehensive metrics
        """
        try:
            # Make predictions on test data
            forecast = model.predict(test_df[['ds', 'unit_price', 'is_weekend']])
            
            y_true = test_df['y'].values
            y_pred = forecast['yhat'].values
            
            # Ensure non-negative predictions
            y_pred = np.maximum(y_pred, 0)
            
            # Calculate comprehensive metrics
            rmse = np.sqrt(np.mean((y_true - y_pred) ** 2))
            mae = np.mean(np.abs(y_true - y_pred))
            
            # MAPE (Mean Absolute Percentage Error)
            mape = np.mean(np.abs((y_true - y_pred) / np.maximum(y_true, 1))) * 100
            
            # R² score
            ss_res = np.sum((y_true - y_pred) ** 2)
            ss_tot = np.sum((y_true - np.mean(y_true)) ** 2)
            r2 = 1 - (ss_res / ss_tot) if ss_tot != 0 else 0
            
            # Directional accuracy
            if len(y_true) > 1:
                true_direction = np.diff(y_true) > 0
                pred_direction = np.diff(y_pred) > 0
                directional_accuracy = np.mean(true_direction == pred_direction) * 100
            else:
                directional_accuracy = 0
            
            # Mean bias
            bias = np.mean(y_pred - y_true)
            
            metrics = {
                'rmse': rmse,
                'mae': mae,
                'mape': mape,
                'r2': r2,
                'directional_accuracy': directional_accuracy,
                'bias': bias,
                'test_points': len(y_true)
            }
            
            logging.info(f"Test metrics - RMSE: {rmse:.2f}, MAE: {mae:.2f}, MAPE: {mape:.2f}%, R²: {r2:.3f}")
            
            return metrics
            
        except Exception as e:
            logging.error(f"Model evaluation failed: {e}")
            return {'error': str(e)}
    
    def predict(self, X):
        """
        Make predictions using Prophet models with confidence intervals
        """
        predictions = []
        
        for _, row in X.iterrows():
            product_name = row['product_name']
            location = row['location']
            sales_date = pd.to_datetime(row['sales_date'])
            unit_price = row['unit_price']
            
            key = f"{product_name}_{location}"
            
            if key in self.models:
                # Use Prophet model
                try:
                    future_df = pd.DataFrame({
                        'ds': [sales_date],
                        'unit_price': [unit_price],
                        'is_weekend': [1 if sales_date.weekday() >= 5 else 0]
                    })
                    
                    forecast = self.models[key].predict(future_df)
                    prediction = max(0, forecast['yhat'].iloc[0])
                    predictions.append(prediction)
                    
                except Exception as e:
                    logging.warning(f"Prophet prediction failed for {product_name} at {location}: {e}")
                    # Fall back to heuristic
                    prediction = self._heuristic_prediction(sales_date, unit_price)
                    predictions.append(prediction)
            else:
                # Use heuristic prediction
                prediction = self._heuristic_prediction(sales_date, unit_price)
                predictions.append(prediction)
        
        return np.array(predictions)
    
    def predict_with_intervals(self, X):
        """
        Make predictions with confidence intervals
        """
        predictions = []
        lower_bounds = []
        upper_bounds = []
        
        for _, row in X.iterrows():
            product_name = row['product_name']
            location = row['location']
            sales_date = pd.to_datetime(row['sales_date'])
            unit_price = row['unit_price']
            
            key = f"{product_name}_{location}"
            
            if key in self.models:
                try:
                    future_df = pd.DataFrame({
                        'ds': [sales_date],
                        'unit_price': [unit_price],
                        'is_weekend': [1 if sales_date.weekday() >= 5 else 0]
                    })
                    
                    forecast = self.models[key].predict(future_df)
                    
                    prediction = max(0, forecast['yhat'].iloc[0])
                    lower = max(0, forecast['yhat_lower'].iloc[0])
                    upper = max(0, forecast['yhat_upper'].iloc[0])
                    
                    predictions.append(prediction)
                    lower_bounds.append(lower)
                    upper_bounds.append(upper)
                    
                except Exception as e:
                    logging.warning(f"Prophet prediction failed for {product_name} at {location}: {e}")
                    prediction = self._heuristic_prediction(sales_date, unit_price)
                    predictions.append(prediction)
                    lower_bounds.append(prediction * 0.8)
                    upper_bounds.append(prediction * 1.2)
            else:
                prediction = self._heuristic_prediction(sales_date, unit_price)
                predictions.append(prediction)
                lower_bounds.append(prediction * 0.8)
                upper_bounds.append(prediction * 1.2)
        
        return {
            'predictions': np.array(predictions),
            'lower_bounds': np.array(lower_bounds),
            'upper_bounds': np.array(upper_bounds)
        }
    
    def _heuristic_prediction(self, sales_date, unit_price):
        """
        Enhanced heuristic prediction when Prophet model is not available
        """
        base_demand = 50
        
        # Price elasticity
        price_factor = max(0.1, 1 / (unit_price * 0.1))
        
        # Seasonal factors
        seasonal_factor = 1 + 0.3 * np.sin(2 * np.pi * (sales_date.month - 1) / 12)
        
        # Weekend effect
        weekend_factor = 0.8 if sales_date.weekday() >= 5 else 1.0
        
        # Monthly frequency adjustment
        if self.prediction_frequency == 'M':
            # Monthly predictions should be higher (sum of daily predictions)
            monthly_factor = 30
        else:
            monthly_factor = 1
        
        return base_demand * price_factor * seasonal_factor * weekend_factor * monthly_factor
    
    def get_model_summary(self):
        """
        Get comprehensive summary of trained models and their performance
        """
        summary = {
            'total_models': len(self.models),
            'prediction_frequency': self.prediction_frequency,
            'models': []
        }
        
        for key, metadata in self.model_metadata.items():
            model_info = metadata.copy()
            
            # Add evaluation results if available
            if key in self.evaluation_results:
                model_info['evaluation'] = self.evaluation_results[key]
            
            summary['models'].append(model_info)
        
        return summary

def preprocess_df(df, feature_columns=None):
    """
    Enhanced preprocessing with better error handling and validation
    """
    try:
        df = df.copy()
        
        if 'sales_date' in df.columns:
            df['sales_date'] = pd.to_datetime(df['sales_date'])
            df['sales_month'] = df['sales_date'].dt.month
            df['sales_day'] = df['sales_date'].dt.day
            df['sales_weekday'] = df['sales_date'].dt.weekday
            df['is_weekend'] = df['sales_weekday'].isin([5, 6]).astype(int)
            df['season'] = df['sales_date'].dt.month % 12 // 3 + 1
            df = df.sort_values('sales_date')
        
        # Keep essential columns for Prophet
        essential_cols = ['product_name', 'location', 'unit_price', 'sales_date']
        if feature_columns is not None:
            return df[essential_cols] if all(col in df.columns for col in essential_cols) else df
        
        return df
        
    except Exception as e:
        logging.error(f"Preprocessing failed: {e}")
        return df

def evaluate_model_performance(model, test_data):
    """
    Evaluate model on test data only with comprehensive reporting
    """
    if not hasattr(model, 'evaluation_results'):
        logging.warning("No evaluation results found in model")
        return None
    
    # Aggregate evaluation results
    all_metrics = []
    for key, metrics in model.evaluation_results.items():
        if 'error' not in metrics:
            all_metrics.append(metrics)
    
    if not all_metrics:
        logging.warning("No valid evaluation metrics found")
        return None
    
    # Calculate average metrics across all models
    avg_metrics = {}
    for metric in ['rmse', 'mae', 'mape', 'r2', 'directional_accuracy', 'bias']:
        values = [m[metric] for m in all_metrics if metric in m]
        if values:
            avg_metrics[f'avg_{metric}'] = np.mean(values)
            avg_metrics[f'std_{metric}'] = np.std(values)
    
    avg_metrics['num_models'] = len(all_metrics)
    avg_metrics['total_test_points'] = sum(m['test_points'] for m in all_metrics)
    
    return avg_metrics

def predict_demand(input_data, prediction_frequency='D'):
    """
    Enhanced predict demand function with frequency support
    """
    try:
        model = load_model()
        if model is None:
            # Enhanced fallback heuristic
            sales_date = pd.to_datetime(input_data.get('sales_date', pd.Timestamp.now()))
            unit_price = input_data.get('unit_price', 10.0)
            
            base_demand = 50
            price_factor = max(0.1, 1 / (unit_price * 0.1))
            seasonal_factor = 1 + 0.3 * np.sin(2 * np.pi * (sales_date.month - 1) / 12)
            weekend_factor = 0.8 if sales_date.weekday() >= 5 else 1.0
            
            # Frequency adjustment
            if prediction_frequency == 'M':
                freq_factor = 30  # Monthly = ~30 daily predictions
            else:
                freq_factor = 1
            
            return base_demand * price_factor * seasonal_factor * weekend_factor * freq_factor
        
        # Set prediction frequency if different
        if hasattr(model, 'prediction_frequency'):
            model.prediction_frequency = prediction_frequency
        
        # Create input DataFrame
        input_df = pd.DataFrame([input_data])
        if 'sales_date' not in input_df.columns:
            input_df['sales_date'] = pd.Timestamp.now()
        
        # Make prediction
        prediction = model.predict(input_df)
        return prediction[0] if len(prediction) > 0 else 50.0
        
    except Exception as e:
        logging.error(f"Prediction failed: {e}")
        return 50.0

def main():
    """
    Enhanced main function with proper train/test methodology and comprehensive evaluation
    """
    logging.info("Starting enhanced Prophet model training with proper evaluation...")
    
    try:
        # Connect and load data
        conn = get_connector()
        query = """
        SELECT product_name, sales_date, unit_price, location,
               units_sold AS demand
        FROM supplied_items
        WHERE sales_date IS NOT NULL AND units_sold IS NOT NULL
        """
        df = pd.read_sql(query, conn)
        conn.close()

        # Data validation
        df = df.drop_duplicates()
        df = df.dropna(subset=['product_name', 'sales_date', 'unit_price', 'location', 'demand'])
        
        logging.info(f"Loaded {len(df)} records for training")
        
        if len(df) == 0:
            logging.warning("No data available for training. Creating a dummy model...")
            model = ProphetDemandModel()
            joblib.dump(model, 'demand_model.pkl')
            joblib.dump(['product_name', 'location', 'unit_price', 'sales_date'], 'model_features.pkl')
            logging.info("Dummy model saved as demand_model.pkl")
            return model
        
        # Data frequency analysis
        df['sales_date'] = pd.to_datetime(df['sales_date'])
        date_range = df['sales_date'].max() - df['sales_date'].min()
        unique_dates = df['sales_date'].nunique()
        avg_records_per_date = len(df) / unique_dates if unique_dates > 0 else 0
        
        logging.info(f"Data analysis:")
        logging.info(f"  Date range: {date_range.days} days")
        logging.info(f"  Unique dates: {unique_dates}")
        logging.info(f"  Average records per date: {avg_records_per_date:.2f}")
        
        # Determine optimal frequency based on data
        if date_range.days > 365 and unique_dates > 100:
            frequency = 'D'  # Daily if we have good daily coverage
        else:
            frequency = 'M'  # Monthly for sparse data
        
        logging.info(f"Selected frequency: {frequency}")
        
        # Prepare features and target
        X = df[['product_name', 'location', 'unit_price', 'sales_date']]
        y = df['demand']
        
        # Train model with proper evaluation
        model = ProphetDemandModel(prediction_frequency=frequency)
        model.fit(X, y)
        
        # Get comprehensive evaluation results
        evaluation_summary = evaluate_model_performance(model, None)
        if evaluation_summary:
            logging.info("Overall Model Performance (Test Data Only):")
            for metric, value in evaluation_summary.items():
                if isinstance(value, (int, float)):
                    logging.info(f"  {metric}: {value:.3f}")
        
        # Get model summary
        summary = model.get_model_summary()
        logging.info(f"\nModel Summary:")
        logging.info(f"  Total models trained: {summary['total_models']}")
        logging.info(f"  Prediction frequency: {summary['prediction_frequency']}")
        
        # Save model and features
        joblib.dump(model, 'demand_model.pkl')
        joblib.dump(['product_name', 'location', 'unit_price', 'sales_date'], 'model_features.pkl')
        
        logging.info(f"Enhanced Prophet model saved as demand_model.pkl")
        logging.info("Model training completed successfully!")
        
        return model
        
    except Exception as e:
        logging.error(f"Training failed: {e}")
        # Create fallback model
        model = ProphetDemandModel()
        joblib.dump(model, 'demand_model.pkl')
        joblib.dump(['product_name', 'location', 'unit_price', 'sales_date'], 'model_features.pkl')
        return model

def load_model():
    """
    Load the Prophet model with enhanced error handling
    """
    try:
        model = joblib.load('demand_model.pkl')
        logging.info("Prophet model loaded successfully")
        return model
    except FileNotFoundError:
        logging.warning("No model found at demand_model.pkl. Please train the model first.")
        return None
    except Exception as e:
        logging.error(f"Failed to load model: {e}")
        return None

if __name__ == "__main__":
    main()