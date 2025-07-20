import pandas as pd
import numpy as np
import joblib
from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
from db_config import get_connector, get_demand_data
import logging
import warnings
from datetime import datetime, timedelta
import matplotlib.pyplot as plt
import seaborn as sns

warnings.filterwarnings('ignore')
logging.basicConfig(level=logging.INFO, format="%(asctime)s %(levelname)s:%(message)s")

class MLDemandModel:
    def __init__(self):
        self.model = RandomForestRegressor(n_estimators=100, random_state=42)
        self.label_encoders = {}
        self.feature_columns = []
        self.is_trained = False
        
    def preprocess_data(self, df):
        """Preprocess data with proper feature engineering"""
        df = df.copy()
        
        # Convert numeric columns to proper types first
        numeric_columns = ['unit_price', 'demand']
        for col in numeric_columns:
            if col in df.columns:
                df[col] = pd.to_numeric(df[col], errors='coerce')
        
        # Convert datetime
        if 'sales_date' in df.columns:
            df['sales_date'] = pd.to_datetime(df['sales_date'], errors='coerce')
            df = df[df['sales_date'].notna()]
            
            # Extract time features
            df['sales_month'] = df['sales_date'].dt.month
            df['sales_day'] = df['sales_date'].dt.day
            df['sales_weekday'] = df['sales_date'].dt.weekday
            df['is_weekend'] = df['sales_weekday'].isin([5, 6]).astype(int)
            df['season'] = df['sales_date'].dt.month % 12 // 3 + 1
            df['quarter'] = df['sales_date'].dt.quarter
            
            # Cyclical features
            df['month_sin'] = np.sin(2 * np.pi * df['sales_month'] / 12)
            df['month_cos'] = np.cos(2 * np.pi * df['sales_month'] / 12)
            df['day_sin'] = np.sin(2 * np.pi * df['sales_weekday'] / 7)
            df['day_cos'] = np.cos(2 * np.pi * df['sales_weekday'] / 7)
        
        # Product features
        if 'product_name' in df.columns:
            df['is_basic'] = df['product_name'].str.contains('Basic', case=False, na=False).astype(int)
            df['is_premium'] = df['product_name'].str.contains('Premium|Silk|Leather', case=False, na=False).astype(int)
            df['is_mens'] = df['product_name'].str.contains("Men's|Male|Boy|Men|Man|Man's", case=False, na=False).astype(int)
            df['is_womens'] = df['product_name'].str.contains("Women's|Female|Ladies|Girls|Babies|Baby", case=False, na=False).astype(int)
            df['is_tshirt'] = df['product_name'].str.contains('T-Shirt|Shirt', case=False, na=False).astype(int)
            df['is_dress'] = df['product_name'].str.contains('Dress', case=False, na=False).astype(int)
            df['is_jeans'] = df['product_name'].str.contains('Jeans|Denim', case=False, na=False).astype(int)
            
            # Encode product names
            if 'product_name' not in self.label_encoders:
                self.label_encoders['product_name'] = LabelEncoder()
                df['product_encoded'] = self.label_encoders['product_name'].fit_transform(df['product_name'])
            else:
                # Handle unseen categories
                known_categories = self.label_encoders['product_name'].classes_
                df['product_encoded'] = df['product_name'].apply(
                    lambda x: self.label_encoders['product_name'].transform([x])[0] if x in known_categories else -1
                )
        
        # Location features
        if 'location' in df.columns:
            # Comment out all location dummy columns
            # df['is_kampala'] = (df['location'] == 'Kampala').astype(int)
            # df['is_entebbe'] = (df['location'] == 'Entebbe').astype(int)
            # df['is_jinja'] = (df['location'] == 'Jinja').astype(int)
            # df['is_gulu'] = (df['location'] == 'Gulu').astype(int)
            # df['is_wakiso'] = (df['location'] == 'Wakiso').astype(int)
            # df['is_mukono'] = (df['location'] == 'Mukono').astype(int)
            # df['is_mbarara'] = (df['location'] == 'Mbarara').astype(int)
            # df['is_hoima'] = (df['location'] == 'Hoima').astype(int)
            # df['is_arua'] = (df['location'] == 'Arua').astype(int)
            # df['is_mbale'] = (df['location'] == 'Mbale').astype(int)
            # df['is_masaka'] = (df['location'] == 'Masaka').astype(int)
            # df['is_moroto'] = (df['location'] == 'Moroto').astype(int)
            # df['is_kabale'] = (df['location'] == 'Kabale').astype(int)
            # df['is_lira'] = (df['location'] == 'Lira').astype(int)
            # df['is_moyo'] = (df['location'] == 'Moyo').astype(int)
            # df['is_kasese'] = (df['location'] == 'Kasese').astype(int)
            # df['is_kiryandongo'] = (df['location'] == 'Kiryandongo').astype(int)
            # df['is_kiboga'] = (df['location'] == 'Kiboga').astype(int)
            # df['is_kisoro'] = (df['location'] == 'Kisoro').astype(int)
            # df['is_mityana'] = (df['location'] == 'Mityana').astype(int)
            # df['is_kamuli'] = (df['location'] == 'Kamuli').astype(int)
            # df['is_kaliro'] = (df['location'] == 'Kaliro').astype(int)
            # df['is_kibale'] = (df['location'] == 'Kibale').astype(int)
            # df['is_kabarole'] = (df['location'] == 'Kabarole').astype(int)

            # Urban classification (optional, you can keep or comment out)
            # urban_locations = ['Kampala', 'Entebbe', 'Wakiso']
            # df['is_urban'] = df['location'].isin(urban_locations).astype(int)

            # Encode locations
            if 'location' not in self.label_encoders:
                self.label_encoders['location'] = LabelEncoder()
                df['location_encoded'] = self.label_encoders['location'].fit_transform(df['location'])
            else:
                known_locations = self.label_encoders['location'].classes_
                df['location_encoded'] = df['location'].apply(
                    lambda x: self.label_encoders['location'].transform([x])[0] if x in known_locations else -1
                )

        # Interaction features
        if 'unit_price' in df.columns and 'product_encoded' in df.columns:
            df['price_product_interaction'] = df['unit_price'] * df['product_encoded']

        # Fill NaN values
        df = df.fillna(0)
        
        return df
    
    def train(self, df):
        """Train the model"""
        logging.info("Starting model training...")

        # Debug: Check data types
        logging.info(f"Data types before preprocessing:")
        logging.info(f"unit_price: {df['unit_price'].dtype}")
        logging.info(f"demand: {df['demand'].dtype}")

        # Preprocess data
        df = self.preprocess_data(df)

        # Debug: Check data types after preprocessing
        logging.info(f"Data types after preprocessing:")
        logging.info(f"unit_price: {df['unit_price'].dtype}")
        logging.info(f"demand: {df['demand'].dtype}")

        # Define feature columns
        feature_columns = [
            'unit_price', 'price_log', 'price_squared', 'price_sqrt',
            'is_low_price', 'is_medium_price', 'is_high_price',
            'sales_month', 'sales_weekday', 'is_weekend', 'season', 'quarter',
            'month_sin', 'month_cos', 'day_sin', 'day_cos',
            'product_length', 'is_basic', 'is_premium', 'is_mens', 'is_womens',
            'is_tshirt', 'is_dress', 'is_jeans', 'product_encoded',
            'unit_price',
            'location_encoded',
            'price_product_interaction',
            # 'location_length', # optional, comment out if not used
            # Location dummies commented out
            # 'is_kampala', 'is_entebbe', 'is_jinja', 'is_gulu', 'is_wakiso', 'is_mukono',
            # 'is_mbarara', 'is_hoima', 'is_arua', 'is_mbale', 'is_masaka', 'is_moroto',
            # 'is_kabale', 'is_lira', 'is_moyo', 'is_kiryandongo', 'is_kiboga',
            # 'is_kisoro', 'is_mityana', 'is_kamuli', 'is_kaliro', 'is_kibale',
            # 'is_kasese', 'is_kabarole',
            # 'is_urban',
            # Interaction features commented out
            # 'price_kampala_interaction', 'price_urban_interaction',
            # 'price_premium_interaction', 'price_mens_interaction', 'price_tshirt_interaction',
            # 'price_dress_interaction', 'price_jeans_interaction'
        ]

        # Keep only available features
        available_features = [col for col in feature_columns if col in df.columns]
        self.feature_columns = available_features

        logging.info(f"Using {len(available_features)} features for training")

        # Prepare training data
        X = df[available_features]
        y = df['demand']

        # Debug: Check for any remaining non-numeric data
        logging.info(f"X data types: {X.dtypes.to_dict()}")
        logging.info(f"y data type: {y.dtype}")

        # Convert any remaining non-numeric columns
        for col in X.columns:
            if not pd.api.types.is_numeric_dtype(X[col]):
                logging.warning(f"Converting non-numeric column {col} to numeric")
                # Use a different approach that always returns a Series
                X[col] = X[col].astype(str).apply(pd.to_numeric, errors='coerce')

        # Ensure y is numeric
        if not pd.api.types.is_numeric_dtype(y):
            y = pd.to_numeric(y, errors='coerce')

        # Remove rows with NaN values
        before_len = len(X)
        mask = ~(X.isna().any(axis=1) | y.isna())
        X = X[mask]
        y = y[mask]
        after_len = len(X)

        if before_len != after_len:
            logging.warning(f"Removed {before_len - after_len} rows with NaN values")

        if len(X) == 0:
            raise ValueError("No valid data remaining after preprocessing")

        # Split data
        X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

        # Train model
        self.model.fit(X_train, y_train)

        # Evaluate
        y_pred = self.model.predict(X_test)
        mae = mean_absolute_error(y_test, y_pred)
        mse = mean_squared_error(y_test, y_pred)
        r2 = r2_score(y_test, y_pred)

        logging.info(f"Model Performance:")
        logging.info(f"MAE: {mae:.2f}")
        logging.info(f"MSE: {mse:.2f}")
        logging.info(f"R2: {r2:.4f}")

        # Feature importance
        feature_importance = pd.DataFrame({
            'feature': available_features,
            'importance': self.model.feature_importances_
        }).sort_values('importance', ascending=False)

        logging.info("\nTop 10 Most Important Features:")
        for idx, row in feature_importance.head(10).iterrows():
            logging.info(f"{row['feature']}: {row['importance']:.4f}")

        self.is_trained = True

        # Save model
        joblib.dump(self.model, 'demand_model.pkl')
        joblib.dump(self.label_encoders, 'label_encoders.pkl')
        joblib.dump(self.feature_columns, 'model_features.pkl')

        logging.info("Model saved successfully!")

        return {
            'mae': mae,
            'mse': mse,
            'r2': r2,
            'feature_importance': feature_importance
        }
    
    def predict(self, df):
        """Make predictions using the trained model"""
        if not self.is_trained:
            raise ValueError("Model is not trained yet. Call train() first.")
        
        # Preprocess data
        df = self.preprocess_data(df)
        
        # Keep only the features used during training
        try:
            X = df[self.feature_columns]
        except KeyError as e:
            missing_cols = [col for col in self.feature_columns if col not in df.columns]
            raise ValueError(f"Missing columns in prediction data: {missing_cols}")
        
        # Convert any remaining non-numeric columns
        for col in X.columns:
            if not pd.api.types.is_numeric_dtype(X[col]):
                # Use the same pattern that works in train()
                X[col] = X[col].astype(str).apply(pd.to_numeric, errors='coerce')
        
        # Fill NaN values with 0
        X = X.fillna(0)
        
        # Make predictions
        return self.model.predict(X)

# Standalone preprocessing function for compatibility
def preprocess_df(df, feature_columns=None):
    """Standalone preprocessing function for compatibility with forecast scripts"""
    model = MLDemandModel()
    processed_df = model.preprocess_data(df)
    
    if feature_columns is not None:
        # Use only the features that were provided
        available_features = [col for col in feature_columns if col in processed_df.columns]
        if available_features:
            processed_df = processed_df[available_features]
    
    return processed_df

def main():
    """Train the ML model"""
    logging.info("Loading data...")
    
    # Get data from database
    data = get_demand_data()
    
    if not data:
        logging.error("No data available for training")
        return
    
    # Convert to DataFrame
    df = pd.DataFrame(data)
    logging.info(f"Loaded {len(df)} records")
    
    # Debug: Show sample data
    logging.info("Sample data:")
    logging.info(df.head())
    logging.info(f"Columns: {df.columns.tolist()}")
    
    # Create and train model
    model = MLDemandModel()
    results = model.train(df)
    
    print("Model training completed!")
    print(f"R² Score: {results['r2']:.4f}")
    print(f"Mean Absolute Error: {results['mae']:.2f}")

if __name__ == "__main__":
    main()