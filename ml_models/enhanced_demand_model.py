import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestRegressor, GradientBoostingRegressor, ExtraTreesRegressor
from sklearn.linear_model import Ridge, Lasso, ElasticNet
from sklearn.svm import SVR
from sklearn.neural_network import MLPRegressor
from sklearn.model_selection import train_test_split, TimeSeriesSplit, GridSearchCV
from sklearn.metrics import mean_squared_error, r2_score, mean_absolute_error
from sklearn.preprocessing import StandardScaler, RobustScaler
from sklearn.feature_selection import SelectKBest, f_regression, RFE
from sklearn.decomposition import PCA
from sklearn.pipeline import Pipeline
import joblib
from db_config import get_connector
import logging
import warnings
warnings.filterwarnings('ignore')

logging.basicConfig(level=logging.INFO, format="%(asctime)s %(levelname)s:%(message)s")

def advanced_feature_engineering(df):
    """Advanced feature engineering for demand prediction"""
    df = df.copy()
    
    if 'sales_date' in df.columns:
        df['sales_date'] = pd.to_datetime(df['sales_date'])
        df = df.sort_values('sales_date')
        
        # Time-based features
        df['sales_month'] = df['sales_date'].dt.month
        df['sales_day'] = df['sales_date'].dt.day
        df['sales_weekday'] = df['sales_date'].dt.weekday
        df['sales_quarter'] = df['sales_date'].dt.quarter
        df['sales_year'] = df['sales_date'].dt.year
        df['is_weekend'] = df['sales_weekday'].isin([5, 6]).astype(int)
        df['is_month_start'] = df['sales_date'].dt.is_month_start.astype(int)
        df['is_month_end'] = df['sales_date'].dt.is_month_end.astype(int)
        df['day_of_year'] = df['sales_date'].dt.dayofyear
        
        # Cyclical features
        df['month_sin'] = np.sin(2 * np.pi * df['sales_month'] / 12)
        df['month_cos'] = np.cos(2 * np.pi * df['sales_month'] / 12)
        df['day_sin'] = np.sin(2 * np.pi * df['sales_day'] / 31)
        df['day_cos'] = np.cos(2 * np.pi * df['sales_day'] / 31)
        df['weekday_sin'] = np.sin(2 * np.pi * df['sales_weekday'] / 7)
        df['weekday_cos'] = np.cos(2 * np.pi * df['sales_weekday'] / 7)
        
        # Season with better encoding
        df['season'] = df['sales_date'].dt.month % 12 // 3 + 1
        
        # Advanced lag and rolling features
        if 'demand' in df.columns:
            # Multiple lag features
            for lag in [1, 2, 3, 7, 14, 30]:
                df[f'lag_{lag}'] = df['demand'].shift(lag)
            
            # Rolling statistics
            for window in [3, 7, 14, 30]:
                df[f'rolling_mean_{window}'] = df['demand'].rolling(window=window, min_periods=1).mean()
                df[f'rolling_std_{window}'] = df['demand'].rolling(window=window, min_periods=1).std()
                df[f'rolling_min_{window}'] = df['demand'].rolling(window=window, min_periods=1).min()
                df[f'rolling_max_{window}'] = df['demand'].rolling(window=window, min_periods=1).max()
                df[f'rolling_median_{window}'] = df['demand'].rolling(window=window, min_periods=1).median()
            
            # Exponential weighted moving averages
            for alpha in [0.1, 0.3, 0.5]:
                df[f'ewm_{alpha}'] = df['demand'].ewm(alpha=alpha).mean()
            
            # Trend features
            df['trend_7'] = df['demand'].rolling(window=7, min_periods=1).apply(lambda x: np.polyfit(range(len(x)), x, 1)[0] if len(x) > 1 else 0, raw=False)
            df['trend_30'] = df['demand'].rolling(window=30, min_periods=1).apply(lambda x: np.polyfit(range(len(x)), x, 1)[0] if len(x) > 1 else 0, raw=False)
            
            # Volatility features
            df['volatility_7'] = df['demand'].rolling(window=7, min_periods=1).std()
            df['volatility_30'] = df['demand'].rolling(window=30, min_periods=1).std()
            
            # Relative features
            df['demand_vs_7day_avg'] = df['demand'] / df['rolling_mean_7']
            df['demand_vs_30day_avg'] = df['demand'] / df['rolling_mean_30']
        
        # Price-based features
        if 'unit_price' in df.columns:
            df['price_lag_1'] = df['unit_price'].shift(1)
            df['price_change'] = df['unit_price'].pct_change()
            df['price_rolling_mean_7'] = df['unit_price'].rolling(window=7, min_periods=1).mean()
            df['price_vs_avg'] = df['unit_price'] / df['price_rolling_mean_7']
        
        df = df.drop('sales_date', axis=1)
    
    # One-hot encode categorical features
    categorical_cols = ['product_name', 'location', 'season']
    for col in categorical_cols:
        if col in df.columns:
            df = pd.get_dummies(df, columns=[col], drop_first=False)
    
    # Fill NaN values
    df = df.fillna(0)
    
    return df

def remove_outliers(df, target_col='demand', method='iqr', factor=1.5):
    """Remove outliers using IQR or Z-score method"""
    if target_col not in df.columns:
        return df
    
    df = df.copy()
    
    if method == 'iqr':
        Q1 = df[target_col].quantile(0.25)
        Q3 = df[target_col].quantile(0.75)
        IQR = Q3 - Q1
        lower_bound = Q1 - factor * IQR
        upper_bound = Q3 + factor * IQR
        df = df[(df[target_col] >= lower_bound) & (df[target_col] <= upper_bound)]
    
    elif method == 'zscore':
        z_scores = np.abs((df[target_col] - df[target_col].mean()) / df[target_col].std())
        df = df[z_scores < factor]
    
    return df

def create_ensemble_model():
    """Create ensemble of different algorithms"""
    models = {
        'rf': RandomForestRegressor(random_state=42),
        'gb': GradientBoostingRegressor(random_state=42),
        'et': ExtraTreesRegressor(random_state=42),
        'ridge': Ridge(random_state=42),
        'lasso': Lasso(random_state=42),
        'elastic': ElasticNet(random_state=42),
        'mlp': MLPRegressor(random_state=42, max_iter=500)
    }
    
    param_grids = {
        'rf': {
            'n_estimators': [200, 300, 500],
            'max_depth': [10, 20, 30, None],
            'min_samples_split': [2, 5, 10],
            'min_samples_leaf': [1, 2, 4],
            'max_features': ['auto', 'sqrt', 'log2']
        },
        'gb': {
            'n_estimators': [100, 200, 300],
            'learning_rate': [0.01, 0.1, 0.2],
            'max_depth': [3, 5, 7],
            'subsample': [0.8, 0.9, 1.0]
        },
        'et': {
            'n_estimators': [200, 300, 500],
            'max_depth': [10, 20, 30],
            'min_samples_split': [2, 5, 10],
            'min_samples_leaf': [1, 2, 4]
        },
        'ridge': {
            'alpha': [0.1, 1.0, 10.0, 100.0]
        },
        'lasso': {
            'alpha': [0.001, 0.01, 0.1, 1.0]
        },
        'elastic': {
            'alpha': [0.001, 0.01, 0.1, 1.0],
            'l1_ratio': [0.1, 0.5, 0.7, 0.9]
        },
        'mlp': {
            'hidden_layer_sizes': [(50,), (100,), (50, 50), (100, 50)],
            'learning_rate_init': [0.001, 0.01, 0.1],
            'alpha': [0.0001, 0.001, 0.01]
        }
    }
    
    return models, param_grids

def feature_selection_pipeline(X, y, method='rfe', k=50):
    """Feature selection using various methods"""
    if method == 'univariate':
        selector = SelectKBest(score_func=f_regression, k=min(k, X.shape[1]))
        X_selected = selector.fit_transform(X, y)
        selected_features = X.columns[selector.get_support()].tolist()
    
    elif method == 'rfe':
        estimator = RandomForestRegressor(n_estimators=100, random_state=42)
        selector = RFE(estimator, n_features_to_select=min(k, X.shape[1]))
        X_selected = selector.fit_transform(X, y)
        selected_features = X.columns[selector.get_support()].tolist()
    
    elif method == 'importance':
        rf = RandomForestRegressor(n_estimators=100, random_state=42)
        rf.fit(X, y)
        importances = rf.feature_importances_
        indices = np.argsort(importances)[::-1]
        selected_indices = indices[:min(k, len(indices))]
        selected_features = X.columns[selected_indices].tolist()
        X_selected = X[selected_features]
    
    return X_selected, selected_features

def main():
    logging.info("Starting enhanced demand prediction model training...")
    
    # Connect and load data
    conn = get_connector()
    query = """
    SELECT product_name, sales_date, unit_price, location,
           units_sold AS demand
    FROM supplied_items
    """
    df = pd.read_sql(query, conn)
    conn.close()
    
    logging.info(f"Loaded {len(df)} records from database")
    
    # Data cleaning
    initial_len = len(df)
    df = df.drop_duplicates()
    df = df.dropna(subset=['product_name', 'sales_date', 'unit_price', 'location'])
    logging.info(f"After cleaning: {len(df)} records (removed {initial_len - len(df)} records)")
    
    # Remove outliers
    df = remove_outliers(df, target_col='demand', method='iqr', factor=1.5)
    logging.info(f"After outlier removal: {len(df)} records")
    
    # Advanced feature engineering
    df = advanced_feature_engineering(df)
    logging.info(f"Created {len(df.columns)} features after engineering")
    
    # Remove rows where demand is missing
    train_df = df[df['demand'].notna()]
    X = train_df.drop('demand', axis=1)
    y = train_df['demand']

    # --- Train/test split (chronological, for time series) ---
    test_size = 0.2
    split_idx = int(len(X) * (1 - test_size))
    X_train, X_test = X.iloc[:split_idx], X.iloc[split_idx:]
    y_train, y_test = y.iloc[:split_idx], y.iloc[split_idx:]

    logging.info(f"Train set: {len(X_train)} samples, Test set: {len(X_test)} samples")

    # Feature selection (fit only on train)
    X_train_selected, selected_features = feature_selection_pipeline(X_train, y_train, method='importance', k=100)
    X_test_selected = X_test[selected_features]
    logging.info(f"Selected {len(selected_features)} features")

    # Create ensemble models
    models, param_grids = create_ensemble_model()
    
    # Time-series cross-validation (on train only)
    tscv = TimeSeriesSplit(n_splits=5)
    
    best_models = {}
    best_scores = {}
    
    # Train and tune each model
    for model_name, model in models.items():
        logging.info(f"Training {model_name}...")
        
        # Create pipeline with scaling for linear models
        if model_name in ['ridge', 'lasso', 'elastic', 'mlp']:
            pipeline = Pipeline([
                ('scaler', StandardScaler()),
                ('model', model)
            ])
            param_grid = {f'model__{k}': v for k, v in param_grids[model_name].items()}
        else:
            pipeline = model
            param_grid = param_grids[model_name]
        
        # Grid search with cross-validation
        grid_search = GridSearchCV(
            pipeline,
            param_grid,
            cv=tscv,
            n_jobs=-1,
            scoring='neg_mean_squared_error',
            verbose=0
        )
        
        grid_search.fit(X_train_selected, y_train)
        
        best_models[model_name] = grid_search.best_estimator_
        best_scores[model_name] = -grid_search.best_score_
        
        logging.info(f"{model_name} - Best CV MSE: {best_scores[model_name]:.4f}")
    
    # Select best performing model
    best_model_name = min(best_scores, key=best_scores.get)
    best_model = best_models[best_model_name]
    
    logging.info(f"Best model: {best_model_name}")
    
    # Final evaluation on train
    y_pred_train = best_model.predict(X_train_selected)
    rmse_train = np.sqrt(mean_squared_error(y_train, y_pred_train))
    mae_train = mean_absolute_error(y_train, y_pred_train)
    r2_train = r2_score(y_train, y_pred_train)
    logging.info(f"Train Performance: RMSE={rmse_train:.4f}, MAE={mae_train:.4f}, R²={r2_train:.6f}")

    # Final evaluation on test
    y_pred_test = best_model.predict(X_test_selected)
    rmse_test = np.sqrt(mean_squared_error(y_test, y_pred_test))
    mae_test = mean_absolute_error(y_test, y_pred_test)
    r2_test = r2_score(y_test, y_pred_test)
    logging.info(f"Test Performance: RMSE={rmse_test:.4f}, MAE={mae_test:.4f}, R²={r2_test:.6f}")

    # Feature importance for tree-based models
    if hasattr(best_model, 'feature_importances_'):
        importances = best_model.feature_importances_
        importance_df = pd.DataFrame({
            'feature': selected_features,
            'importance': importances
        }).sort_values('importance', ascending=False)
        logging.info("Top 15 Feature Importances:")
        logging.info(str(importance_df.head(15)))
    
    # Create weighted ensemble of top 3 models (on train)
    sorted_models = sorted(best_scores.items(), key=lambda x: x[1])
    top_3_models = sorted_models[:3]
    
    logging.info("Creating weighted ensemble of top 3 models...")
    ensemble_predictions_train = []
    weights = [1/score for _, score in top_3_models]
    weights = [w/sum(weights) for w in weights]  # Normalize weights
    
    for i, (model_name, _) in enumerate(top_3_models):
        pred = best_models[model_name].predict(X_train_selected)
        ensemble_predictions_train.append(pred * weights[i])
    
    ensemble_pred_train = np.sum(ensemble_predictions_train, axis=0)
    ensemble_rmse_train = np.sqrt(mean_squared_error(y_train, ensemble_pred_train))
    ensemble_mae_train = mean_absolute_error(y_train, ensemble_pred_train)
    ensemble_r2_train = r2_score(y_train, ensemble_pred_train)
    
    # Ensemble on test
    ensemble_predictions_test = []
    for i, (model_name, _) in enumerate(top_3_models):
        pred = best_models[model_name].predict(X_test_selected)
        ensemble_predictions_test.append(pred * weights[i])
    ensemble_pred_test = np.sum(ensemble_predictions_test, axis=0)
    ensemble_rmse_test = np.sqrt(mean_squared_error(y_test, ensemble_pred_test))
    ensemble_mae_test = mean_absolute_error(y_test, ensemble_pred_test)
    ensemble_r2_test = r2_score(y_test, ensemble_pred_test)

    logging.info(f"Ensemble Train Performance: RMSE={ensemble_rmse_train:.4f}, MAE={ensemble_mae_train:.4f}, R²={ensemble_r2_train:.6f}")
    logging.info(f"Ensemble Test Performance: RMSE={ensemble_rmse_test:.4f}, MAE={ensemble_mae_test:.4f}, R²={ensemble_r2_test:.6f}")

    # Save the best individual model or ensemble based on test R²
    if ensemble_r2_test > r2_test:
        logging.info("Ensemble model performs better on test, saving ensemble...")
        ensemble_model = {
            'models': {name: best_models[name] for name, _ in top_3_models},
            'weights': weights,
            'model_names': [name for name, _ in top_3_models]
        }
        joblib.dump(ensemble_model, 'enhanced_demand_model.pkl')
        final_r2 = ensemble_r2_test
    else:
        logging.info("Individual model performs better on test, saving best model...")
        joblib.dump(best_model, 'enhanced_demand_model.pkl')
        final_r2 = r2_test

    # Save feature columns and preprocessing info
    joblib.dump(selected_features, 'enhanced_model_features.pkl')
    
    logging.info(f"Model training complete. Final Test R² Score: {final_r2:.6f}")
    
    return final_r2

def load_enhanced_model():
    """Load the enhanced model"""
    return joblib.load('enhanced_demand_model.pkl')

def predict_demand_enhanced(input_data):
    """Predict demand using the enhanced model"""
    model = load_enhanced_model()
    feature_columns = joblib.load('enhanced_model_features.pkl')
    
    # Prepare input data
    input_df = pd.DataFrame([input_data])
    input_df = advanced_feature_engineering(input_df)
    
    # Select features
    input_df = input_df.reindex(columns=feature_columns, fill_value=0)
    
    # Make prediction
    if isinstance(model, dict):  # Ensemble model
        predictions = []
        for i, model_name in enumerate(model['model_names']):
            pred = model['models'][model_name].predict(input_df)
            predictions.append(pred[0] * model['weights'][i])
        prediction = sum(predictions)
    else:  # Individual model
        prediction = model.predict(input_df)[0]
    
    return prediction

if __name__ == "__main__":
    main()