import pandas as pd
from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split, TimeSeriesSplit, GridSearchCV
from sklearn.metrics import mean_squared_error, r2_score, mean_absolute_error
import joblib
from db_config import get_connector
import numpy as np
import logging

logging.basicConfig(level=logging.INFO, format="%(asctime)s %(levelname)s:%(message)s")

def preprocess_df(df, feature_columns=None):
    df = df.copy()
    if 'sales_date' in df.columns:
        df['sales_date'] = pd.to_datetime(df['sales_date'])
        df['sales_month'] = df['sales_date'].dt.month
        df['sales_day'] = df['sales_date'].dt.day
        df['sales_weekday'] = df['sales_date'].dt.weekday
        df['is_weekend'] = df['sales_weekday'].isin([5, 6]).astype(int)
        # Example: add season
        df['season'] = df['sales_date'].dt.month % 12 // 3 + 1  # 1=winter, 2=spring, etc.
        df = df.sort_values('sales_date')
        # Example lag feature (previous day's sales)
        #if 'units_sold' in df.columns:
            #df['lag_1'] = df['units_sold'].shift(1).fillna(0)
            #df['rolling_mean_7'] = df['units_sold'].rolling(window=7, min_periods=1).mean()
        df = df.drop('sales_date', axis=1)
    # One-hot encode categorical features
    df = pd.get_dummies(df, columns=['product_name', 'location', 'season'], drop_first=False)
    if feature_columns is not None:
        df = df.reindex(columns=feature_columns, fill_value=0)
    return df

def main():
    # Connect and load data
    conn = get_connector()
    query = """
    SELECT product_name, sales_date, unit_price, location,
           units_sold AS demand
    FROM supplied_items
    """
    df = pd.read_sql(query, conn)
    conn.close()

    # Data cleaning
    df = df.drop_duplicates()
    df = df.dropna(subset=['product_name', 'sales_date', 'unit_price', 'location'])

    # Preprocess
    df = preprocess_df(df)

    # Remove rows where demand is missing
    train_df = df[df['demand'].notna()]
    X = train_df.drop('demand', axis=1)
    y = train_df['demand']

    # Use time-series split for validation if data is sequential
    tscv = TimeSeriesSplit(n_splits=3)
    param_grid = {
        'n_estimators': [100, 200],
        'max_depth': [None, 10, 20],
        'min_samples_split': [2, 5],
        'min_samples_leaf': [1, 2]
    }
    grid_search = GridSearchCV(
        RandomForestRegressor(random_state=42),
        param_grid,
        cv=tscv,
        n_jobs=-1,
        scoring='neg_mean_squared_error'
    )
    grid_search.fit(X, y)
    best_model = grid_search.best_estimator_

    # Evaluate the model
    y_pred = best_model.predict(X)
    rmse = np.sqrt(mean_squared_error(y, y_pred))
    mae = mean_absolute_error(y, y_pred)
    r2 = r2_score(y, y_pred)
    logging.info(f'Root Mean Squared Error: {rmse:.2f}')
    logging.info(f'Mean Absolute Error: {mae:.2f}')
    logging.info(f'R^2 Score: {r2:.2f}')
    logging.info(f'Best Parameters: {grid_search.best_params_}')

    # Feature importance
    importances = best_model.feature_importances_
    feature_names = X.columns
    importance_df = pd.DataFrame({'feature': feature_names, 'importance': importances})
    importance_df = importance_df.sort_values('importance', ascending=False)
    logging.info("Top 10 Feature Importances:\n" + str(importance_df.head(10)))

    # Save the model and feature columns
    joblib.dump(best_model, 'demand_model.pkl')
    joblib.dump(X.columns.tolist(), 'model_features.pkl')
    logging.info("Model and feature columns saved.")

def load_model():
    return joblib.load('demand_model.pkl')

def predict_demand(input_data):
    model = load_model()
    feature_columns = joblib.load('model_features.pkl')
    input_df = pd.DataFrame([input_data])
    input_df = preprocess_df(input_df, feature_columns)
    prediction = model.predict(input_df)
    return prediction[0]

if __name__ == "__main__":
    main()