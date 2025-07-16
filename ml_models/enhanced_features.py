"""
Enhanced Feature Engineering Utilities
Shared feature engineering functions for all ML models in the system
"""

import pandas as pd
import numpy as np
from datetime import datetime, timedelta
from typing import Dict, List, Tuple, Optional, Union
import logging
from sklearn.preprocessing import StandardScaler, LabelEncoder, MinMaxScaler
from sklearn.feature_selection import SelectKBest, f_regression, RFE
from sklearn.ensemble import RandomForestRegressor
import warnings
warnings.filterwarnings('ignore')

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class AdvancedFeatureEngineer:
    """Advanced feature engineering for time series and recommendation systems"""
    
    def __init__(self):
        self.scalers = {}
        self.encoders = {}
        self.feature_names = []
        
    def create_time_features(self, df: pd.DataFrame, date_col: str) -> pd.DataFrame:
        """Create comprehensive time-based features"""
        df = df.copy()
        df[date_col] = pd.to_datetime(df[date_col])
        
        # Basic time features
        df['year'] = df[date_col].dt.year
        df['month'] = df[date_col].dt.month
        df['day'] = df[date_col].dt.day
        df['dayofweek'] = df[date_col].dt.dayofweek
        df['dayofyear'] = df[date_col].dt.dayofyear
        df['week'] = df[date_col].dt.isocalendar().week
        df['quarter'] = df[date_col].dt.quarter
        
        # Cyclical features (important for time series)
        df['month_sin'] = np.sin(2 * np.pi * df['month'] / 12)
        df['month_cos'] = np.cos(2 * np.pi * df['month'] / 12)
        df['day_sin'] = np.sin(2 * np.pi * df['day'] / 31)
        df['day_cos'] = np.cos(2 * np.pi * df['day'] / 31)
        df['dayofweek_sin'] = np.sin(2 * np.pi * df['dayofweek'] / 7)
        df['dayofweek_cos'] = np.cos(2 * np.pi * df['dayofweek'] / 7)
        
        # Business features
        df['is_weekend'] = (df['dayofweek'] >= 5).astype(int)
        df['is_month_start'] = df[date_col].dt.is_month_start.astype(int)
        df['is_month_end'] = df[date_col].dt.is_month_end.astype(int)
        df['is_quarter_start'] = df[date_col].dt.is_quarter_start.astype(int)
        df['is_quarter_end'] = df[date_col].dt.is_quarter_end.astype(int)
        
        # Holiday features (basic - can be extended with specific holidays)
        df['is_holiday_season'] = ((df['month'] == 12) | (df['month'] == 1)).astype(int)
        df['is_summer'] = ((df['month'] >= 6) & (df['month'] <= 8)).astype(int)
        
        logger.info(f"Created {len([col for col in df.columns if col not in [date_col]])} time features")
        return df
    
    def create_lag_features(self, df: pd.DataFrame, target_col: str, 
                          lags: List[int] = [1, 2, 3, 7, 14, 30], 
                          group_cols: Optional[List[str]] = None) -> pd.DataFrame:
        """Create lag features for time series"""
        df = df.copy()
        
        if group_cols:
            for lag in lags:
                df[f'{target_col}_lag_{lag}'] = df.groupby(group_cols)[target_col].shift(lag)
        else:
            for lag in lags:
                df[f'{target_col}_lag_{lag}'] = df[target_col].shift(lag)
        
        logger.info(f"Created {len(lags)} lag features for {target_col}")
        return df
    
    def create_rolling_features(self, df: pd.DataFrame, target_col: str,
                              windows: List[int] = [3, 7, 14, 30],
                              group_cols: Optional[List[str]] = None) -> pd.DataFrame:
        """Create rolling window features"""
        df = df.copy()
        
        for window in windows:
            if group_cols:
                df[f'{target_col}_rolling_mean_{window}'] = df.groupby(group_cols)[target_col].rolling(window, min_periods=1).mean().reset_index(0, drop=True)
                df[f'{target_col}_rolling_std_{window}'] = df.groupby(group_cols)[target_col].rolling(window, min_periods=1).std().reset_index(0, drop=True)
                df[f'{target_col}_rolling_min_{window}'] = df.groupby(group_cols)[target_col].rolling(window, min_periods=1).min().reset_index(0, drop=True)
                df[f'{target_col}_rolling_max_{window}'] = df.groupby(group_cols)[target_col].rolling(window, min_periods=1).max().reset_index(0, drop=True)
            else:
                df[f'{target_col}_rolling_mean_{window}'] = df[target_col].rolling(window, min_periods=1).mean()
                df[f'{target_col}_rolling_std_{window}'] = df[target_col].rolling(window, min_periods=1).std()
                df[f'{target_col}_rolling_min_{window}'] = df[target_col].rolling(window, min_periods=1).min()
                df[f'{target_col}_rolling_max_{window}'] = df[target_col].rolling(window, min_periods=1).max()
        
        logger.info(f"Created rolling features for {len(windows)} windows")
        return df
    
    def create_trend_features(self, df: pd.DataFrame, target_col: str,
                            group_cols: Optional[List[str]] = None) -> pd.DataFrame:
        """Create trend and momentum features"""
        df = df.copy()
        
        # Price changes
        if group_cols:
            df[f'{target_col}_change'] = df.groupby(group_cols)[target_col].diff()
            df[f'{target_col}_pct_change'] = df.groupby(group_cols)[target_col].pct_change()
        else:
            df[f'{target_col}_change'] = df[target_col].diff()
            df[f'{target_col}_pct_change'] = df[target_col].pct_change()
        
        # Momentum indicators
        df[f'{target_col}_momentum_3'] = df[f'{target_col}_change'].rolling(3).sum()
        df[f'{target_col}_momentum_7'] = df[f'{target_col}_change'].rolling(7).sum()
        
        # Volatility
        df[f'{target_col}_volatility'] = df[f'{target_col}_pct_change'].rolling(7).std()
        
        logger.info(f"Created trend and momentum features for {target_col}")
        return df
    
    def create_customer_features(self, customer_df: pd.DataFrame) -> pd.DataFrame:
        """Create features for customer analysis and recommendations"""
        df = customer_df.copy()
        
        # Age group encoding
        if 'age_group' in df.columns:
            age_mapping = {'18-25': 1, '26-35': 2, '36-45': 3, '46-55': 4, '55+': 5}
            df['age_group_encoded'] = df['age_group'].map(age_mapping).fillna(0)
        
        # Income bracket encoding
        if 'income_bracket' in df.columns:
            income_mapping = {'<30k': 1, '30k-50k': 2, '50k-75k': 3, '75k-100k': 4, '100k+': 5}
            df['income_bracket_encoded'] = df['income_bracket'].map(income_mapping).fillna(0)
        
        # Purchase frequency encoding
        if 'purchase_frequency' in df.columns:
            freq_mapping = {'rarely': 1, 'monthly': 2, 'weekly': 3, 'daily': 4}
            df['purchase_frequency_encoded'] = df['purchase_frequency'].map(freq_mapping).fillna(0)
        
        # Gender encoding
        if 'gender' in df.columns:
            df['gender_encoded'] = LabelEncoder().fit_transform(df['gender'].fillna('unknown'))
        
        logger.info("Created customer demographic features")
        return df
    
    def create_product_features(self, product_df: pd.DataFrame) -> pd.DataFrame:
        """Create features for product analysis"""
        df = product_df.copy()
        
        # Category encoding
        if 'category' in df.columns:
            le_category = LabelEncoder()
            df['category_encoded'] = le_category.fit_transform(df['category'].fillna('unknown'))
            self.encoders['category'] = le_category
        
        # Material encoding
        if 'material' in df.columns:
            le_material = LabelEncoder()
            df['material_encoded'] = le_material.fit_transform(df['material'].fillna('unknown'))
            self.encoders['material'] = le_material
        
        # Price features
        if 'base_price' in df.columns:
            df['price_log'] = np.log1p(df['base_price'])
            df['price_sqrt'] = np.sqrt(df['base_price'])
        
        # Stock features
        if 'stock_quantity' in df.columns:
            df['stock_level'] = pd.cut(df['stock_quantity'], bins=5, labels=['very_low', 'low', 'medium', 'high', 'very_high'])
            df['stock_level_encoded'] = LabelEncoder().fit_transform(df['stock_level'].astype(str))
        
        logger.info("Created product features")
        return df
    
    def create_interaction_features(self, df: pd.DataFrame, 
                                  feature_pairs: List[Tuple[str, str]]) -> pd.DataFrame:
        """Create interaction features between specified columns"""
        df = df.copy()
        
        for col1, col2 in feature_pairs:
            if col1 in df.columns and col2 in df.columns:
                # Multiplication interaction
                df[f'{col1}_x_{col2}'] = df[col1] * df[col2]
                # Addition interaction
                df[f'{col1}_plus_{col2}'] = df[col1] + df[col2]
                # Ratio interaction (avoid division by zero)
                df[f'{col1}_div_{col2}'] = df[col1] / (df[col2] + 1e-8)
        
        logger.info(f"Created interaction features for {len(feature_pairs)} pairs")
        return df
    
    def select_features(self, X: pd.DataFrame, y: pd.Series, 
                       method: str = 'mutual_info', k: int = 50) -> Tuple[pd.DataFrame, List[str]]:
        """Select top k features using specified method"""
        
        if method == 'mutual_info':
            from sklearn.feature_selection import mutual_info_regression
            selector = SelectKBest(score_func=mutual_info_regression, k=k)
        elif method == 'f_regression':
            selector = SelectKBest(score_func=f_regression, k=k)
        elif method == 'rfe':
            estimator = RandomForestRegressor(n_estimators=50, random_state=42)
            selector = RFE(estimator=estimator, n_features_to_select=k)
        else:
            raise ValueError(f"Unknown method: {method}")
        
        X_selected = selector.fit_transform(X, y)
        selected_features = X.columns[selector.get_support()].tolist()
        
        logger.info(f"Selected {len(selected_features)} features using {method}")
        return pd.DataFrame(X_selected, columns=selected_features, index=X.index), selected_features
    
    def scale_features(self, X: pd.DataFrame, method: str = 'standard', 
                      fit: bool = True) -> pd.DataFrame:
        """Scale features using specified method"""
        
        if method == 'standard':
            scaler_class = StandardScaler
        elif method == 'minmax':
            scaler_class = MinMaxScaler
        else:
            raise ValueError(f"Unknown scaling method: {method}")
        
        if fit:
            self.scalers[method] = scaler_class()
            X_scaled = self.scalers[method].fit_transform(X)
        else:
            if method not in self.scalers:
                raise ValueError(f"Scaler for {method} not fitted yet")
            X_scaled = self.scalers[method].transform(X)
        
        return pd.DataFrame(X_scaled, columns=X.columns, index=X.index)
    
    def handle_missing_values(self, df: pd.DataFrame, 
                            strategy: Dict[str, str] = None) -> pd.DataFrame:
        """Handle missing values with different strategies per column"""
        df = df.copy()
        
        default_strategy = {
            'numeric': 'median',
            'categorical': 'mode',
            'datetime': 'forward_fill'
        }
        
        for column in df.columns:
            if df[column].isnull().sum() > 0:
                dtype = str(df[column].dtype)
                
                if column in (strategy or {}):
                    method = strategy[column]
                elif 'int' in dtype or 'float' in dtype:
                    method = default_strategy['numeric']
                elif 'object' in dtype:
                    method = default_strategy['categorical']
                elif 'datetime' in dtype:
                    method = default_strategy['datetime']
                else:
                    method = 'median'
                
                if method == 'median':
                    df[column].fillna(df[column].median(), inplace=True)
                elif method == 'mean':
                    df[column].fillna(df[column].mean(), inplace=True)
                elif method == 'mode':
                    df[column].fillna(df[column].mode().iloc[0] if not df[column].mode().empty else 'unknown', inplace=True)
                elif method == 'forward_fill':
                    df[column].fillna(method='ffill', inplace=True)
                elif method == 'backward_fill':
                    df[column].fillna(method='bfill', inplace=True)
                elif method == 'zero':
                    df[column].fillna(0, inplace=True)
        
        logger.info("Handled missing values")
        return df
    
    def detect_outliers(self, df: pd.DataFrame, columns: List[str] = None, 
                       method: str = 'iqr', threshold: float = 1.5) -> pd.DataFrame:
        """Detect and optionally remove outliers"""
        df = df.copy()
        
        if columns is None:
            columns = df.select_dtypes(include=[np.number]).columns.tolist()
        
        outlier_mask = pd.Series([False] * len(df), index=df.index)
        
        for column in columns:
            if method == 'iqr':
                Q1 = df[column].quantile(0.25)
                Q3 = df[column].quantile(0.75)
                IQR = Q3 - Q1
                lower_bound = Q1 - threshold * IQR
                upper_bound = Q3 + threshold * IQR
                column_outliers = (df[column] < lower_bound) | (df[column] > upper_bound)
            
            elif method == 'zscore':
                z_scores = np.abs((df[column] - df[column].mean()) / df[column].std())
                column_outliers = z_scores > threshold
            
            else:
                raise ValueError(f"Unknown outlier detection method: {method}")
            
            outlier_mask |= column_outliers
        
        logger.info(f"Detected {outlier_mask.sum()} outliers using {method} method")
        
        # Add outlier flag column
        df['is_outlier'] = outlier_mask
        
        return df
    
    def create_rfm_features(self, order_data: pd.DataFrame, 
                          customer_col: str = 'customer_id',
                          date_col: str = 'order_date',
                          amount_col: str = 'total_amount') -> pd.DataFrame:
        """Create RFM (Recency, Frequency, Monetary) features"""
        
        # Calculate reference date (latest date in dataset)
        reference_date = pd.to_datetime(order_data[date_col]).max()
        
        # Group by customer
        rfm = order_data.groupby(customer_col).agg({
            date_col: lambda x: (reference_date - pd.to_datetime(x).max()).days,  # Recency
            amount_col: ['count', 'sum', 'mean']  # Frequency and Monetary
        }).round(2)
        
        # Flatten column names
        rfm.columns = ['recency', 'frequency', 'monetary_total', 'monetary_avg']
        rfm.reset_index(inplace=True)
        
        # Create RFM scores (1-5 scale)
        rfm['recency_score'] = pd.qcut(rfm['recency'], 5, labels=[5,4,3,2,1], duplicates='drop')
        rfm['frequency_score'] = pd.qcut(rfm['frequency'].rank(method='first'), 5, labels=[1,2,3,4,5], duplicates='drop')
        rfm['monetary_score'] = pd.qcut(rfm['monetary_total'], 5, labels=[1,2,3,4,5], duplicates='drop')
        
        # Create combined RFM score
        rfm['rfm_score'] = rfm['recency_score'].astype(str) + rfm['frequency_score'].astype(str) + rfm['monetary_score'].astype(str)
        
        # Customer segmentation based on RFM
        def segment_customers(row):
            score = row['rfm_score']
            if score in ['555', '554', '544', '545', '454', '455', '445']:
                return 'Champions'
            elif score in ['543', '444', '435', '355', '354', '345', '344', '335']:
                return 'Loyal Customers'
            elif score in ['553', '551', '552', '541', '542', '533', '532', '531', '452', '451']:
                return 'Potential Loyalists'
            elif score in ['512', '511', '422', '421', '412', '411', '311']:
                return 'New Customers'
            elif score in ['155', '154', '144', '214', '215', '115', '114']:
                return 'At Risk'
            elif score in ['155', '154', '144', '214', '215', '115']:
                return 'Cannot Lose Them'
            else:
                return 'Others'
        
        rfm['customer_segment'] = rfm.apply(segment_customers, axis=1)
        
        logger.info("Created RFM analysis features")
        return rfm


class SeasonalityDetector:
    """Detect seasonal patterns in time series data"""
    
    def __init__(self):
        self.seasonal_patterns = {}
    
    def detect_seasonality(self, ts_data: pd.Series, 
                         periods: List[int] = [7, 30, 365]) -> Dict[str, float]:
        """Detect seasonal patterns using autocorrelation"""
        from scipy.stats import pearsonr
        
        seasonality_scores = {}
        
        for period in periods:
            if len(ts_data) > period:
                # Calculate autocorrelation at lag = period
                correlation, p_value = pearsonr(ts_data[:-period], ts_data[period:])
                seasonality_scores[f'period_{period}'] = {
                    'correlation': correlation,
                    'p_value': p_value,
                    'significant': p_value < 0.05
                }
        
        return seasonality_scores
    
    def create_seasonal_features(self, df: pd.DataFrame, date_col: str, 
                               target_col: str) -> pd.DataFrame:
        """Create features based on detected seasonality"""
        df = df.copy()
        df[date_col] = pd.to_datetime(df[date_col])
        
        # Sort by date
        df = df.sort_values(date_col)
        
        # Detect seasonality
        seasonality = self.detect_seasonality(df[target_col])
        
        # Create seasonal decomposition features
        from statsmodels.tsa.seasonal import seasonal_decompose
        
        if len(df) >= 24:  # Minimum periods for decomposition
            try:
                decomposition = seasonal_decompose(df[target_col], model='additive', period=min(12, len(df)//2))
                df['trend'] = decomposition.trend
                df['seasonal'] = decomposition.seasonal
                df['residual'] = decomposition.resid
            except:
                logger.warning("Could not perform seasonal decomposition")
        
        return df