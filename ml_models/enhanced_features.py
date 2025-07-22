"""
Enhanced Feature Engineering for Customer Recommendation System
Provides advanced feature engineering capabilities for customer analysis and segmentation.
"""

import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import logging
from typing import Dict, List, Tuple, Optional
from scipy import stats
from sklearn.preprocessing import StandardScaler
from sklearn.decomposition import PCA
from sklearn.cluster import KMeans

logger = logging.getLogger(__name__)


class AdvancedFeatureEngineer:
    """
    Advanced feature engineering class for customer recommendation system.
    Creates sophisticated features for customer analysis and product recommendations.
    """
    
    def __init__(self):
        """Initialize the feature engineer with default parameters."""
        self.reference_date = datetime.now()
        logger.info("AdvancedFeatureEngineer initialized")
    
    def create_customer_features(self, customer_df: pd.DataFrame) -> pd.DataFrame:
        """
        Create advanced customer features from basic customer data.
        
        Args:
            customer_df: DataFrame with customer information
            
        Returns:
            DataFrame with enhanced customer features
        """
        if customer_df.empty:
            return customer_df
        
        try:
            # Make a copy to avoid modifying original
            features_df = customer_df.copy()
            
            # Age-based features
            if 'age' in features_df.columns:
                features_df['age_group'] = pd.cut(
                    features_df['age'], 
                    bins=[0, 25, 35, 45, 55, 100], 
                    labels=['18-25', '26-35', '36-45', '46-55', '55+']
                )
                features_df['is_young_adult'] = (features_df['age'] <= 35).astype(int)
                features_df['is_middle_aged'] = ((features_df['age'] > 35) & (features_df['age'] <= 55)).astype(int)
                features_df['is_senior'] = (features_df['age'] > 55).astype(int)
            
            # Location-based features
            if 'location' in features_df.columns:
                # Extract region information (assuming Ugandan districts)
                features_df['region'] = features_df['location'].apply(self._extract_region)
                features_df['is_urban'] = features_df['location'].apply(self._is_urban_location)
            
            # Registration recency features
            if 'created_at' in features_df.columns:
                features_df['created_at'] = pd.to_datetime(features_df['created_at'])
                features_df['days_since_registration'] = (
                    self.reference_date - features_df['created_at']
                ).dt.days
                features_df['is_new_customer'] = (features_df['days_since_registration'] <= 30).astype(int)
                features_df['registration_month'] = features_df['created_at'].dt.month
                features_df['registration_year'] = features_df['created_at'].dt.year
            
            # Income-based features (if available)
            if 'income' in features_df.columns:
                features_df['income_tier'] = pd.cut(
                    features_df['income'], 
                    bins=[0, 500000, 1000000, 2000000, float('inf')], 
                    labels=['Low', 'Medium', 'High', 'Premium']
                )
                features_df['log_income'] = np.log1p(features_df['income'])
            
            logger.info(f"Created customer features for {len(features_df)} customers")
            return features_df
            
        except Exception as e:
            logger.error(f"Error creating customer features: {e}")
            return customer_df
    
    def create_rfm_features(self, interaction_df: pd.DataFrame, customer_df: pd.DataFrame) -> pd.DataFrame:
        """Create RFM features for customers"""
        if interaction_df.empty:
            return pd.DataFrame()

        # Calculate Recency, Frequency, Monetary for each customer
        rfm = interaction_df.groupby('customer_id').agg({
            'order_date': lambda x: (datetime.now() - pd.to_datetime(x).max()).days,
            'order_id': 'nunique',
            'total_amount': 'sum'
        }).reset_index()

        rfm.columns = ['customer_id', 'recency', 'frequency', 'monetary']

        # Optionally, merge with customer_df for additional info
        # rfm = rfm.merge(customer_df[['id']], left_on='customer_id', right_on='id', how='left')

        return rfm
    
    def _extract_region(self, location: str) -> str:
        """Extract region from Ugandan district location."""
        if pd.isna(location):
            return 'Unknown'
        
        # Ugandan regions mapping
        central_districts = ['Kampala', 'Wakiso', 'Mukono', 'Mpigi', 'Luwero', 'Nakaseke']
        western_districts = ['Mbarara', 'Kasese', 'Bushenyi', 'Ntungamo', 'Kabale', 'Kisoro']
        northern_districts = ['Gulu', 'Lira', 'Arua', 'Kitgum', 'Pader', 'Moyo']
        eastern_districts = ['Jinja', 'Mbale', 'Tororo', 'Busia', 'Soroti', 'Kumi']
        
        location_lower = location.lower()
        
        if any(district.lower() in location_lower for district in central_districts):
            return 'Central'
        elif any(district.lower() in location_lower for district in western_districts):
            return 'Western'
        elif any(district.lower() in location_lower for district in northern_districts):
            return 'Northern'
        elif any(district.lower() in location_lower for district in eastern_districts):
            return 'Eastern'
        else:
            return 'Other'
    
    def _is_urban_location(self, location: str) -> int:
        """Determine if location is urban (1) or rural (0)."""
        if pd.isna(location):
            return 0
        
        urban_areas = ['Kampala', 'Jinja', 'Mbarara', 'Gulu', 'Lira', 'Mbale', 'Arua', 'Masaka']
        return 1 if any(urban.lower() in location.lower() for urban in urban_areas) else 0
    
    def _categorize_rfm(self, rfm_score: str) -> str:
        """Categorize customers based on RFM score."""
        if len(rfm_score) != 3:
            return 'Unknown'
        
        r, f, m = int(rfm_score[0]), int(rfm_score[1]), int(rfm_score[2])
        
        # Champions: High value, frequent, recent
        if r >= 4 and f >= 4 and m >= 4:
            return 'Champions'
        # Loyal Customers: High frequency and monetary, but not recent
        elif f >= 4 and m >= 4:
            return 'Loyal Customers'
        # Potential Loyalists: Recent customers with good frequency
        elif r >= 4 and f >= 3:
            return 'Potential Loyalists'
        # New Customers: Recent but low frequency
        elif r >= 4 and f <= 2:
            return 'New Customers'
        # Promising: Recent with medium frequency and monetary
        elif r >= 3 and f >= 3 and m >= 3:
            return 'Promising'
        # Need Attention: Above average recency, frequency, and monetary
        elif r >= 3 and f >= 2 and m >= 2:
            return 'Need Attention'
        # About to Sleep: Below average recency, frequency, and monetary
        elif r <= 2 and f >= 2 and m >= 2:
            return 'About to Sleep'
        # At Risk: Good monetary and frequency but low recency
        elif r <= 2 and f >= 3 and m >= 3:
            return 'At Risk'
        # Cannot Lose Them: Low recency but high monetary and frequency
        elif r <= 2 and f >= 4 and m >= 4:
            return 'Cannot Lose Them'
        # Lost: Lowest recency, frequency, and monetary
        else:
            return 'Lost'
    
    def create_time_features(self, df: pd.DataFrame, date_col: str) -> pd.DataFrame:
        """
        Adds basic time-based features to the DataFrame based on the specified date column.
        """
        df = df.copy()
        if date_col not in df.columns:
            logger.warning(f"Column '{date_col}' not found in DataFrame")
            return df
            
        df[date_col] = pd.to_datetime(df[date_col], errors='coerce')
        df['month'] = df[date_col].dt.month
        df['dayofweek'] = df[date_col].dt.dayofweek
        df['is_weekend'] = (df['dayofweek'] >= 5).astype(int)
        return df


class SeasonalityDetector:
    """
    Advanced seasonality detection for time series data.
    Detects various seasonal patterns including weekly, monthly, and yearly patterns.
    """
    
    def __init__(self):
        """Initialize the seasonality detector."""
        self.scaler = StandardScaler()
        self.seasonal_patterns = {}
        logger.info("SeasonalityDetector initialized")
    
    def detect_seasonality(self, data: pd.DataFrame, date_col: str = 'date', 
                          value_col: str = 'value') -> Dict[str, any]:
        """
        Detect seasonal patterns in time series data.
        
        Args:
            data: DataFrame with date and value columns
            date_col: Name of the date column
            value_col: Name of the value column
            
        Returns:
            Dictionary with seasonality information
        """
        try:
            if data.empty:
                return {'has_seasonality': False, 'patterns': {}}
            
            # Prepare data
            df = data.copy()
            df[date_col] = pd.to_datetime(df[date_col])
            df = df.sort_values(date_col)
            
            # Create time-based features
            df['year'] = df[date_col].dt.year
            df['month'] = df[date_col].dt.month
            df['day_of_week'] = df[date_col].dt.dayofweek
            df['day_of_year'] = df[date_col].dt.dayofyear
            df['week_of_year'] = df[date_col].dt.isocalendar().week
            
            patterns = {}
            
            # Test for different seasonal patterns
            patterns['monthly'] = self._test_monthly_seasonality(df, value_col)
            patterns['weekly'] = self._test_weekly_seasonality(df, value_col)
            patterns['yearly'] = self._test_yearly_seasonality(df, value_col)
            
            # Overall seasonality assessment
            has_seasonality = any(pattern['significant'] for pattern in patterns.values())
            
            return {
                'has_seasonality': has_seasonality,
                'patterns': patterns,
                'strongest_pattern': self._find_strongest_pattern(patterns)
            }
            
        except Exception as e:
            logger.error(f"Error detecting seasonality: {e}")
            return {'has_seasonality': False, 'patterns': {}}
    
    def _test_monthly_seasonality(self, df: pd.DataFrame, value_col: str) -> Dict[str, any]:
        """Test for monthly seasonal patterns."""
        try:
            monthly_stats = df.groupby('month')[value_col].agg(['mean', 'std', 'count'])
            
            # Statistical test for monthly differences
            monthly_groups = [group[value_col].values for name, group in df.groupby('month')]
            monthly_groups = [group for group in monthly_groups if len(group) > 1]
            
            if len(monthly_groups) < 3:
                return {'significant': False, 'p_value': 1.0, 'strength': 0.0}
            
            # ANOVA test
            f_stat, p_value = stats.f_oneway(*monthly_groups)
            
            # Calculate strength (coefficient of variation of monthly means)
            cv = monthly_stats['mean'].std() / monthly_stats['mean'].mean()
            
            return {
                'significant': p_value < 0.05,
                'p_value': p_value,
                'strength': cv,
                'peak_months': monthly_stats['mean'].nlargest(3).index.tolist()
            }
            
        except Exception as e:
            logger.error(f"Error in monthly seasonality test: {e}")
            return {'significant': False, 'p_value': 1.0, 'strength': 0.0}
    
    def _test_weekly_seasonality(self, df: pd.DataFrame, value_col: str) -> Dict[str, any]:
        """Test for weekly seasonal patterns."""
        try:
            weekly_stats = df.groupby('day_of_week')[value_col].agg(['mean', 'std', 'count'])
            
            # Statistical test for weekly differences
            weekly_groups = [group[value_col].values for name, group in df.groupby('day_of_week')]
            weekly_groups = [group for group in weekly_groups if len(group) > 1]
            
            if len(weekly_groups) < 3:
                return {'significant': False, 'p_value': 1.0, 'strength': 0.0}
            
            # ANOVA test
            f_stat, p_value = stats.f_oneway(*weekly_groups)
            
            # Calculate strength
            cv = weekly_stats['mean'].std() / weekly_stats['mean'].mean()
            
            return {
                'significant': p_value < 0.05,
                'p_value': p_value,
                'strength': cv,
                'peak_days': weekly_stats['mean'].nlargest(2).index.tolist()
            }
            
        except Exception as e:
            logger.error(f"Error in weekly seasonality test: {e}")
            return {'significant': False, 'p_value': 1.0, 'strength': 0.0}
    
    def _test_yearly_seasonality(self, df: pd.DataFrame, value_col: str) -> Dict[str, any]:
        """Test for yearly seasonal patterns."""
        try:
            if df['year'].nunique() < 2:
                return {'significant': False, 'p_value': 1.0, 'strength': 0.0}
            
            yearly_stats = df.groupby('year')[value_col].agg(['mean', 'std', 'count'])
            
            # Statistical test for yearly differences
            yearly_groups = [group[value_col].values for name, group in df.groupby('year')]
            yearly_groups = [group for group in yearly_groups if len(group) > 1]
            
            if len(yearly_groups) < 2:
                return {'significant': False, 'p_value': 1.0, 'strength': 0.0}
            
            # ANOVA test
            f_stat, p_value = stats.f_oneway(*yearly_groups)
            
            # Calculate strength
            cv = yearly_stats['mean'].std() / yearly_stats['mean'].mean()
            
            return {
                'significant': p_value < 0.05,
                'p_value': p_value,
                'strength': cv,
                'trend': self._calculate_trend(yearly_stats['mean'])
            }
            
        except Exception as e:
            logger.error(f"Error in yearly seasonality test: {e}")
            return {'significant': False, 'p_value': 1.0, 'strength': 0.0}
    
    def _calculate_trend(self, yearly_means: pd.Series) -> str:
        """Calculate trend direction from yearly means."""
        try:
            if len(yearly_means) < 2:
                return 'insufficient_data'
            
            # Linear regression to determine trend
            x = np.arange(len(yearly_means))
            slope, intercept, r_value, p_value, std_err = stats.linregress(x, yearly_means.values)
            
            if p_value < 0.05:
                return 'increasing' if slope > 0 else 'decreasing'
            else:
                return 'stable'
                
        except Exception:
            return 'unknown'
    
    def _find_strongest_pattern(self, patterns: Dict[str, Dict]) -> Optional[str]:
        """Find the strongest seasonal pattern."""
        try:
            significant_patterns = {
                name: pattern for name, pattern in patterns.items() 
                if pattern.get('significant', False)
            }
            
            if not significant_patterns:
                return None
            
            # Find pattern with highest strength
            strongest = max(
                significant_patterns.items(),
                key=lambda x: x[1].get('strength', 0)
            )
            
            return strongest[0]
            
        except Exception:
            return None
    
    def generate_seasonal_features(self, data: pd.DataFrame, date_col: str = 'date') -> pd.DataFrame:
        """
        Generate seasonal features for time series data.
        
        Args:
            data: DataFrame with date column
            date_col: Name of the date column
            
        Returns:
            DataFrame with additional seasonal features
        """
        try:
            df = data.copy()
            df[date_col] = pd.to_datetime(df[date_col])
            
            # Basic time features
            df['year'] = df[date_col].dt.year
            df['month'] = df[date_col].dt.month
            df['day_of_week'] = df[date_col].dt.dayofweek
            df['day_of_month'] = df[date_col].dt.day
            df['day_of_year'] = df[date_col].dt.dayofyear
            df['week_of_year'] = df[date_col].dt.isocalendar().week
            df['quarter'] = df[date_col].dt.quarter
            
            # Cyclical features (important for ML models)
            df['month_sin'] = np.sin(2 * np.pi * df['month'] / 12)
            df['month_cos'] = np.cos(2 * np.pi * df['month'] / 12)
            df['day_of_week_sin'] = np.sin(2 * np.pi * df['day_of_week'] / 7)
            df['day_of_week_cos'] = np.cos(2 * np.pi * df['day_of_week'] / 7)
            df['day_of_year_sin'] = np.sin(2 * np.pi * df['day_of_year'] / 365)
            df['day_of_year_cos'] = np.cos(2 * np.pi * df['day_of_year'] / 365)
            
            # Business features
            df['is_weekend'] = (df['day_of_week'] >= 5).astype(int)
            df['is_month_start'] = (df['day_of_month'] <= 7).astype(int)
            df['is_month_end'] = (df['day_of_month'] >= 25).astype(int)
            df['is_quarter_start'] = df[date_col].dt.is_quarter_start.astype(int)
            df['is_quarter_end'] = df[date_col].dt.is_quarter_end.astype(int)
            df['is_year_start'] = df[date_col].dt.is_year_start.astype(int)
            df['is_year_end'] = df[date_col].dt.is_year_end.astype(int)
            
            logger.info(f"Generated seasonal features for {len(df)} records")
            return df
            
        except Exception as e:
            logger.error(f"Error generating seasonal features: {e}")
            return data