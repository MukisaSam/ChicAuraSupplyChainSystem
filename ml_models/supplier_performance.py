"""
Supplier Performance Analytics System
Comprehensive supplier KPI tracking, scoring, and predictive analytics
"""

import pandas as pd
import numpy as np
from datetime import datetime, timedelta
from typing import Dict, List, Tuple, Optional, Union
import logging
import pickle
import json
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.ensemble import RandomForestRegressor, IsolationForest
from sklearn.preprocessing import StandardScaler, MinMaxScaler
from sklearn.cluster import KMeans
from sklearn.metrics import silhouette_score
import warnings
warnings.filterwarnings('ignore')

from db_config import get_supplier_performance_data, execute_query
from enhanced_features import AdvancedFeatureEngineer

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class SupplierPerformanceAnalyzer:
    """Comprehensive supplier performance analysis and scoring system"""
    
    def __init__(self):
        self.feature_engineer = AdvancedFeatureEngineer()
        self.performance_scaler = StandardScaler()
        self.anomaly_detector = IsolationForest(contamination=0.1, random_state=42)
        self.clustering_model = KMeans(n_clusters=4, random_state=42)
        self.performance_predictor = RandomForestRegressor(n_estimators=100, random_state=42)
        
        # KPI weights for overall score calculation
        self.kpi_weights = {
            'delivery_performance': 0.25,
            'quality_score': 0.25,
            'price_competitiveness': 0.20,
            'reliability_score': 0.15,
            'communication_score': 0.10,
            'volume_capacity': 0.05
        }
        
        # Performance thresholds
        self.thresholds = {
            'excellent': 85,
            'good': 70,
            'average': 55,
            'poor': 40
        }
        
        self.models_trained = False
    
    def load_supplier_data(self) -> pd.DataFrame:
        """Load and prepare supplier performance data"""
        try:
            data = get_supplier_performance_data()
            if not data:
                logger.warning("No supplier performance data found")
                return pd.DataFrame()
            
            df = pd.DataFrame(data)
            
            # Data type conversions
            date_columns = ['delivery_date', 'due_date', 'request_date']
            for col in date_columns:
                if col in df.columns:
                    df[col] = pd.to_datetime(df[col], errors='coerce')
            
            # Handle missing values
            df['quality_rating'] = df['quality_rating'].fillna(df['quality_rating'].median())
            df['delivery_delay_days'] = df['delivery_delay_days'].fillna(0)
            df['delivered_quantity'] = df['delivered_quantity'].fillna(0)
            df['delivered_price'] = df['delivered_price'].fillna(0)
            
            # Calculate fulfillment rate
            df['fulfillment_rate'] = np.where(
                df['requested_quantity'] > 0,
                df['delivered_quantity'] / df['requested_quantity'],
                0
            )
            df['fulfillment_rate'] = np.clip(df['fulfillment_rate'], 0, 1)
            
            logger.info(f"Loaded {len(df)} supplier performance records")
            return df
            
        except Exception as e:
            logger.error(f"Error loading supplier data: {e}")
            return pd.DataFrame()
    
    def calculate_delivery_performance(self, df: pd.DataFrame) -> pd.DataFrame:
        """Calculate delivery performance metrics"""
        supplier_delivery = df.groupby('supplier_id').agg({
            'delivery_delay_days': ['mean', 'std', 'count'],
            'due_date': 'count',
            'delivery_date': lambda x: (x.notna()).sum()
        }).round(2)
        
        # Flatten column names
        supplier_delivery.columns = [
            'avg_delay_days', 'delay_std', 'total_deliveries',
            'total_requests', 'completed_deliveries'
        ]
        
        # Calculate KPIs
        supplier_delivery['on_time_delivery_rate'] = (
            (df.groupby('supplier_id')['delivery_delay_days'].apply(lambda x: (x <= 0).sum()) /
             supplier_delivery['total_deliveries']) * 100
        ).fillna(0)
        
        supplier_delivery['early_delivery_rate'] = (
            (df.groupby('supplier_id')['delivery_delay_days'].apply(lambda x: (x < 0).sum()) /
             supplier_delivery['total_deliveries']) * 100
        ).fillna(0)
        
        supplier_delivery['late_delivery_rate'] = (
            (df.groupby('supplier_id')['delivery_delay_days'].apply(lambda x: (x > 0).sum()) /
             supplier_delivery['total_deliveries']) * 100
        ).fillna(0)
        
        # Delivery performance score (0-100)
        supplier_delivery['delivery_performance_score'] = np.clip(
            100 - (supplier_delivery['avg_delay_days'] * 2) - (supplier_delivery['late_delivery_rate'] * 0.5),
            0, 100
        )
        
        return supplier_delivery
    
    def calculate_quality_metrics(self, df: pd.DataFrame) -> pd.DataFrame:
        """Calculate quality performance metrics"""
        quality_metrics = df.groupby('supplier_id').agg({
            'quality_rating': ['mean', 'std', 'count', 'min', 'max']
        }).round(2)
        
        # Flatten column names
        quality_metrics.columns = [
            'avg_quality_rating', 'quality_std', 'quality_samples',
            'min_quality', 'max_quality'
        ]
        
        # Quality consistency score (lower std is better)
        max_std = quality_metrics['quality_std'].max() if quality_metrics['quality_std'].max() > 0 else 1
        quality_metrics['quality_consistency_score'] = (
            (1 - (quality_metrics['quality_std'] / max_std)) * 100
        ).fillna(100)
        
        # Overall quality score
        quality_metrics['quality_score'] = (
            (quality_metrics['avg_quality_rating'] / 5 * 70) +  # 70% weight for avg rating
            (quality_metrics['quality_consistency_score'] * 0.30)  # 30% weight for consistency
        )
        
        return quality_metrics
    
    def calculate_price_competitiveness(self, df: pd.DataFrame) -> pd.DataFrame:
        """Calculate price competitiveness metrics"""
        # Group by item to get market prices
        item_prices = df.groupby('item_name')['delivered_price'].agg(['mean', 'median', 'std']).reset_index()
        item_prices.columns = ['item_name', 'market_avg_price', 'market_median_price', 'market_price_std']
        
        # Merge back with supplier data
        df_with_market = df.merge(item_prices, on='item_name', how='left')
        
        # Calculate price competitiveness for each supplier-item combination
        df_with_market['price_vs_market'] = np.where(
            df_with_market['market_avg_price'] > 0,
            (df_with_market['delivered_price'] / df_with_market['market_avg_price'] - 1) * 100,
            0
        )
        
        # Aggregate by supplier
        price_metrics = df_with_market.groupby('supplier_id').agg({
            'price_vs_market': ['mean', 'std'],
            'delivered_price': ['count']
        }).round(2)
        
        # Flatten column names
        price_metrics.columns = ['avg_price_vs_market', 'price_volatility', 'price_samples']
        
        # Price competitiveness score (lower prices are better, but not too volatile)
        price_metrics['price_competitiveness_score'] = np.clip(
            100 - price_metrics['avg_price_vs_market'] - (price_metrics['price_volatility'] * 0.1),
            0, 100
        )
        
        return price_metrics
    
    def calculate_reliability_metrics(self, df: pd.DataFrame) -> pd.DataFrame:
        """Calculate supplier reliability metrics"""
        reliability_metrics = df.groupby('supplier_id').agg({
            'fulfillment_rate': ['mean', 'std', 'count'],
            'delivered_quantity': 'sum',
            'requested_quantity': 'sum'
        }).round(3)
        
        # Flatten column names
        reliability_metrics.columns = [
            'avg_fulfillment_rate', 'fulfillment_std', 'total_orders',
            'total_delivered', 'total_requested'
        ]
        
        # Overall fulfillment rate
        reliability_metrics['overall_fulfillment_rate'] = np.where(
            reliability_metrics['total_requested'] > 0,
            reliability_metrics['total_delivered'] / reliability_metrics['total_requested'],
            0
        )
        
        # Reliability consistency (lower std is better)
        max_std = reliability_metrics['fulfillment_std'].max() if reliability_metrics['fulfillment_std'].max() > 0 else 1
        reliability_metrics['fulfillment_consistency'] = (
            (1 - (reliability_metrics['fulfillment_std'] / max_std)) * 100
        ).fillna(100)
        
        # Reliability score
        reliability_metrics['reliability_score'] = (
            (reliability_metrics['overall_fulfillment_rate'] * 70) +
            (reliability_metrics['fulfillment_consistency'] * 0.30)
        )
        
        return reliability_metrics
    
    def calculate_communication_score(self, df: pd.DataFrame) -> pd.DataFrame:
        """Calculate communication responsiveness score"""
        # Get chat data for suppliers
        try:
            chat_query = """
            SELECT 
                sender_id as supplier_user_id,
                COUNT(*) as total_messages,
                AVG(TIMESTAMPDIFF(HOUR, created_at, read_at)) as avg_response_time_hours,
                SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as messages_read,
                COUNT(*) as total_sent
            FROM chat_messages cm
            JOIN users u ON cm.sender_id = u.id
            WHERE u.role = 'supplier'
            GROUP BY sender_id
            """
            
            chat_data = execute_query(chat_query)
            
            if chat_data:
                chat_df = pd.DataFrame(chat_data)
                
                # Map supplier user_id to supplier_id
                supplier_mapping_query = """
                SELECT s.id as supplier_id, s.user_id as supplier_user_id
                FROM suppliers s
                """
                supplier_mapping = execute_query(supplier_mapping_query)
                mapping_df = pd.DataFrame(supplier_mapping)
                
                chat_df = chat_df.merge(mapping_df, on='supplier_user_id', how='inner')
                
                # Calculate communication metrics
                chat_df['response_rate'] = (chat_df['messages_read'] / chat_df['total_sent']) * 100
                chat_df['avg_response_time_hours'] = chat_df['avg_response_time_hours'].fillna(24)
                
                # Communication score (faster response and higher rate is better)
                chat_df['communication_score'] = np.clip(
                    (chat_df['response_rate'] * 0.6) + 
                    ((1 / (chat_df['avg_response_time_hours'] + 1)) * 100 * 0.4),
                    0, 100
                )
                
                return chat_df[['supplier_id', 'communication_score', 'response_rate', 'avg_response_time_hours']]
            
        except Exception as e:
            logger.warning(f"Could not calculate communication scores: {e}")
        
        # Default communication score for all suppliers
        unique_suppliers = df['supplier_id'].unique()
        default_comm = pd.DataFrame({
            'supplier_id': unique_suppliers,
            'communication_score': [70] * len(unique_suppliers),  # Default average score
            'response_rate': [70] * len(unique_suppliers),
            'avg_response_time_hours': [6] * len(unique_suppliers)
        })
        
        return default_comm
    
    def calculate_volume_capacity(self, df: pd.DataFrame) -> pd.DataFrame:
        """Calculate supplier volume handling capacity"""
        volume_metrics = df.groupby('supplier_id').agg({
            'delivered_quantity': ['sum', 'mean', 'max'],
            'requested_quantity': ['sum', 'mean', 'max'],
            'item_name': 'nunique'  # Number of different items supplied
        }).round(2)
        
        # Flatten column names
        volume_metrics.columns = [
            'total_volume_delivered', 'avg_volume_per_order', 'max_single_delivery',
            'total_volume_requested', 'avg_volume_requested', 'max_volume_requested',
            'product_diversity'
        ]
        
        # Volume capacity score based on total volume and consistency
        max_volume = volume_metrics['total_volume_delivered'].max()
        if max_volume > 0:
            volume_metrics['volume_capacity_score'] = (
                (volume_metrics['total_volume_delivered'] / max_volume * 50) +
                (volume_metrics['product_diversity'] / volume_metrics['product_diversity'].max() * 30) +
                (volume_metrics['avg_volume_per_order'] / volume_metrics['avg_volume_per_order'].max() * 20)
            )
        else:
            volume_metrics['volume_capacity_score'] = 50
        
        return volume_metrics
    
    def calculate_overall_performance(self, supplier_metrics: Dict[str, pd.DataFrame]) -> pd.DataFrame:
        """Calculate overall supplier performance score"""
        # Start with delivery performance as base
        overall = supplier_metrics['delivery'].copy()
        
        # Merge all metrics
        for metric_name, metric_df in supplier_metrics.items():
            if metric_name != 'delivery':
                overall = overall.merge(
                    metric_df, 
                    left_index=True, 
                    right_on='supplier_id' if 'supplier_id' in metric_df.columns else right_index=True,
                    how='left'
                )
        
        # Fill missing values with average scores
        score_columns = [col for col in overall.columns if col.endswith('_score')]
        for col in score_columns:
            overall[col] = overall[col].fillna(overall[col].median())
        
        # Calculate weighted overall score
        overall['overall_performance_score'] = (
            overall['delivery_performance_score'] * self.kpi_weights['delivery_performance'] +
            overall['quality_score'] * self.kpi_weights['quality_score'] +
            overall['price_competitiveness_score'] * self.kpi_weights['price_competitiveness'] +
            overall['reliability_score'] * self.kpi_weights['reliability_score'] +
            overall['communication_score'] * self.kpi_weights['communication_score'] +
            overall['volume_capacity_score'] * self.kpi_weights['volume_capacity']
        )
        
        # Performance tier classification
        overall['performance_tier'] = pd.cut(
            overall['overall_performance_score'],
            bins=[0, self.thresholds['poor'], self.thresholds['average'], 
                  self.thresholds['good'], self.thresholds['excellent'], 100],
            labels=['Poor', 'Below Average', 'Average', 'Good', 'Excellent']
        )
        
        return overall
    
    def detect_performance_anomalies(self, df: pd.DataFrame) -> pd.DataFrame:
        """Detect anomalous supplier performance patterns"""
        # Select numeric columns for anomaly detection
        numeric_cols = df.select_dtypes(include=[np.number]).columns
        score_cols = [col for col in numeric_cols if 'score' in col or 'rate' in col]
        
        if len(score_cols) == 0:
            df['anomaly_score'] = 0
            df['is_anomaly'] = False
            return df
        
        # Prepare data for anomaly detection
        X = df[score_cols].fillna(df[score_cols].median())
        
        # Fit isolation forest
        try:
            anomaly_scores = self.anomaly_detector.fit_predict(X)
            df['is_anomaly'] = anomaly_scores == -1
            df['anomaly_score'] = self.anomaly_detector.decision_function(X)
        except:
            df['is_anomaly'] = False
            df['anomaly_score'] = 0
        
        return df
    
    def cluster_suppliers(self, df: pd.DataFrame) -> pd.DataFrame:
        """Cluster suppliers based on performance characteristics"""
        # Select features for clustering
        clustering_features = [
            'delivery_performance_score', 'quality_score', 'price_competitiveness_score',
            'reliability_score', 'communication_score', 'volume_capacity_score'
        ]
        
        available_features = [col for col in clustering_features if col in df.columns]
        
        if len(available_features) < 3:
            df['supplier_cluster'] = 'Default'
            return df
        
        # Prepare data
        X = df[available_features].fillna(df[available_features].median())
        X_scaled = self.performance_scaler.fit_transform(X)
        
        # Find optimal number of clusters
        silhouette_scores = []
        K_range = range(2, min(8, len(df)//2 + 1))
        
        for k in K_range:
            kmeans = KMeans(n_clusters=k, random_state=42)
            cluster_labels = kmeans.fit_predict(X_scaled)
            if len(set(cluster_labels)) > 1:  # Ensure we have multiple clusters
                silhouette_avg = silhouette_score(X_scaled, cluster_labels)
                silhouette_scores.append((k, silhouette_avg))
        
        if silhouette_scores:
            optimal_k = max(silhouette_scores, key=lambda x: x[1])[0]
            self.clustering_model = KMeans(n_clusters=optimal_k, random_state=42)
        
        # Fit clustering model
        cluster_labels = self.clustering_model.fit_predict(X_scaled)
        df['supplier_cluster'] = cluster_labels
        
        # Name clusters based on performance characteristics
        cluster_names = {}
        for cluster_id in df['supplier_cluster'].unique():
            cluster_data = df[df['supplier_cluster'] == cluster_id]
            avg_score = cluster_data['overall_performance_score'].mean()
            
            if avg_score >= self.thresholds['excellent']:
                cluster_names[cluster_id] = 'Elite Performers'
            elif avg_score >= self.thresholds['good']:
                cluster_names[cluster_id] = 'Strong Performers'
            elif avg_score >= self.thresholds['average']:
                cluster_names[cluster_id] = 'Average Performers'
            else:
                cluster_names[cluster_id] = 'Underperformers'
        
        df['cluster_name'] = df['supplier_cluster'].map(cluster_names)
        
        return df
    
    def predict_future_performance(self, df: pd.DataFrame, days_ahead: int = 30) -> pd.DataFrame:
        """Predict supplier performance for future period"""
        # Create time-based features
        df_with_time = self.feature_engineer.create_time_features(df.reset_index(), 'request_date')
        
        # Select features for prediction
        feature_cols = [
            'month', 'dayofweek', 'is_weekend', 'delivery_performance_score',
            'quality_score', 'price_competitiveness_score', 'reliability_score'
        ]
        
        available_features = [col for col in feature_cols if col in df_with_time.columns]
        
        if len(available_features) < 3:
            df['predicted_performance'] = df['overall_performance_score']
            return df
        
        # Prepare training data
        X = df_with_time[available_features].fillna(df_with_time[available_features].median())
        y = df_with_time['overall_performance_score'].fillna(df_with_time['overall_performance_score'].median())
        
        # Train prediction model
        try:
            self.performance_predictor.fit(X, y)
            
            # Create future date features
            future_date = datetime.now() + timedelta(days=days_ahead)
            future_features = pd.DataFrame({
                'month': [future_date.month],
                'dayofweek': [future_date.weekday()],
                'is_weekend': [1 if future_date.weekday() >= 5 else 0]
            })
            
            # Add current performance scores for prediction
            current_scores = df[['delivery_performance_score', 'quality_score', 
                               'price_competitiveness_score', 'reliability_score']].mean()
            
            for col in current_scores.index:
                if col in available_features:
                    future_features[col] = [current_scores[col]]
            
            # Make predictions for each supplier
            predictions = []
            for supplier_id in df.index:
                supplier_features = future_features.copy()
                
                # Use supplier-specific current performance
                for col in ['delivery_performance_score', 'quality_score', 
                          'price_competitiveness_score', 'reliability_score']:
                    if col in df.columns and col in available_features:
                        supplier_features[col] = [df.loc[supplier_id, col]]
                
                # Ensure all required features are present
                for col in available_features:
                    if col not in supplier_features.columns:
                        supplier_features[col] = [X[col].median()]
                
                pred = self.performance_predictor.predict(supplier_features[available_features])[0]
                predictions.append(pred)
            
            df['predicted_performance'] = predictions
            
        except Exception as e:
            logger.warning(f"Could not train performance prediction model: {e}")
            df['predicted_performance'] = df['overall_performance_score']
        
        return df
    
    def generate_supplier_insights(self, df: pd.DataFrame) -> Dict[str, any]:
        """Generate insights and recommendations for supplier management"""
        insights = {
            'summary': {},
            'top_performers': {},
            'underperformers': {},
            'recommendations': [],
            'alerts': []
        }
        
        # Summary statistics
        insights['summary'] = {
            'total_suppliers': len(df),
            'avg_performance_score': df['overall_performance_score'].mean().round(2),
            'excellent_suppliers': len(df[df['overall_performance_score'] >= self.thresholds['excellent']]),
            'poor_suppliers': len(df[df['overall_performance_score'] < self.thresholds['poor']]),
            'anomalous_suppliers': df['is_anomaly'].sum() if 'is_anomaly' in df.columns else 0
        }
        
        # Top performers
        top_suppliers = df.nlargest(5, 'overall_performance_score')
        insights['top_performers'] = {
            'suppliers': top_suppliers[['supplier_name', 'overall_performance_score', 'performance_tier']].to_dict('records'),
            'avg_score': top_suppliers['overall_performance_score'].mean().round(2)
        }
        
        # Underperformers
        bottom_suppliers = df.nsmallest(5, 'overall_performance_score')
        insights['underperformers'] = {
            'suppliers': bottom_suppliers[['supplier_name', 'overall_performance_score', 'performance_tier']].to_dict('records'),
            'avg_score': bottom_suppliers['overall_performance_score'].mean().round(2)
        }
        
        # Generate recommendations
        if 'delivery_performance_score' in df.columns:
            poor_delivery = df[df['delivery_performance_score'] < 60]
            if len(poor_delivery) > 0:
                insights['recommendations'].append(
                    f"Consider delivery training for {len(poor_delivery)} suppliers with poor delivery performance"
                )
        
        if 'quality_score' in df.columns:
            poor_quality = df[df['quality_score'] < 60]
            if len(poor_quality) > 0:
                insights['recommendations'].append(
                    f"Implement quality improvement programs for {len(poor_quality)} suppliers"
                )
        
        # Generate alerts
        if 'is_anomaly' in df.columns:
            anomalous = df[df['is_anomaly'] == True]
            for _, supplier in anomalous.iterrows():
                insights['alerts'].append(
                    f"Anomalous performance detected for supplier {supplier.get('supplier_name', supplier.name)}"
                )
        
        if 'predicted_performance' in df.columns:
            declining = df[df['predicted_performance'] < df['overall_performance_score'] - 10]
            for _, supplier in declining.iterrows():
                insights['alerts'].append(
                    f"Performance decline predicted for supplier {supplier.get('supplier_name', supplier.name)}"
                )
        
        return insights
    
    def save_models(self, filepath: str = 'supplier_models/'):
        """Save trained models"""
        import os
        os.makedirs(filepath, exist_ok=True)
        
        models_to_save = {
            'performance_scaler.pkl': self.performance_scaler,
            'anomaly_detector.pkl': self.anomaly_detector,
            'clustering_model.pkl': self.clustering_model,
            'performance_predictor.pkl': self.performance_predictor
        }
        
        for filename, model in models_to_save.items():
            try:
                with open(os.path.join(filepath, filename), 'wb') as f:
                    pickle.dump(model, f)
            except Exception as e:
                logger.warning(f"Could not save {filename}: {e}")
        
        # Save configuration
        config = {
            'kpi_weights': self.kpi_weights,
            'thresholds': self.thresholds,
            'models_trained': self.models_trained
        }
        
        with open(os.path.join(filepath, 'config.json'), 'w') as f:
            json.dump(config, f, indent=2)
        
        logger.info(f"Models saved to {filepath}")
    
    def run_full_analysis(self) -> Tuple[pd.DataFrame, Dict[str, any]]:
        """Run complete supplier performance analysis"""
        logger.info("Starting supplier performance analysis...")
        
        # Load data
        df = self.load_supplier_data()
        if df.empty:
            logger.error("No data available for analysis")
            return pd.DataFrame(), {}
        
        # Calculate all performance metrics
        logger.info("Calculating performance metrics...")
        
        delivery_metrics = self.calculate_delivery_performance(df)
        quality_metrics = self.calculate_quality_metrics(df)
        price_metrics = self.calculate_price_competitiveness(df)
        reliability_metrics = self.calculate_reliability_metrics(df)
        communication_metrics = self.calculate_communication_score(df)
        volume_metrics = self.calculate_volume_capacity(df)
        
        # Combine all metrics
        supplier_metrics = {
            'delivery': delivery_metrics,
            'quality': quality_metrics,
            'price': price_metrics,
            'reliability': reliability_metrics,
            'communication': communication_metrics,
            'volume': volume_metrics
        }
        
        # Calculate overall performance
        logger.info("Calculating overall performance scores...")
        overall_performance = self.calculate_overall_performance(supplier_metrics)
        
        # Add supplier names
        try:
            supplier_names_query = """
            SELECT s.id as supplier_id, u.name as supplier_name
            FROM suppliers s
            JOIN users u ON s.user_id = u.id
            """
            supplier_names = execute_query(supplier_names_query)
            if supplier_names:
                names_df = pd.DataFrame(supplier_names).set_index('supplier_id')
                overall_performance = overall_performance.merge(
                    names_df, left_index=True, right_index=True, how='left'
                )
        except Exception as e:
            logger.warning(f"Could not add supplier names: {e}")
            overall_performance['supplier_name'] = 'Unknown'
        
        # Advanced analytics
        logger.info("Running advanced analytics...")
        overall_performance = self.detect_performance_anomalies(overall_performance)
        overall_performance = self.cluster_suppliers(overall_performance)
        overall_performance = self.predict_future_performance(overall_performance)
        
        # Generate insights
        insights = self.generate_supplier_insights(overall_performance)
        
        # Save models
        self.save_models()
        self.models_trained = True
        
        logger.info("Supplier performance analysis completed successfully")
        
        return overall_performance, insights


def main():
    """Main function to run supplier performance analysis"""
    analyzer = SupplierPerformanceAnalyzer()
    
    try:
        performance_df, insights = analyzer.run_full_analysis()
        
        if not performance_df.empty:
            # Save results
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            output_file = f'../SupplyChain/public/supplier_analysis_{timestamp}.csv'
            performance_df.to_csv(output_file)
            
            # Save insights
            insights_file = f'../SupplyChain/public/supplier_insights_{timestamp}.json'
            with open(insights_file, 'w') as f:
                json.dump(insights, f, indent=2, default=str)
            
            print(f"Analysis completed. Results saved to {output_file}")
            print(f"Insights saved to {insights_file}")
            
            # Print summary
            print("\n=== SUPPLIER PERFORMANCE SUMMARY ===")
            print(f"Total Suppliers Analyzed: {insights['summary']['total_suppliers']}")
            print(f"Average Performance Score: {insights['summary']['avg_performance_score']}")
            print(f"Excellent Performers: {insights['summary']['excellent_suppliers']}")
            print(f"Poor Performers: {insights['summary']['poor_suppliers']}")
            
            if insights['recommendations']:
                print("\n=== RECOMMENDATIONS ===")
                for rec in insights['recommendations']:
                    print(f"- {rec}")
            
            if insights['alerts']:
                print("\n=== ALERTS ===")
                for alert in insights['alerts']:
                    print(f"⚠️  {alert}")
        
    except Exception as e:
        logger.error(f"Analysis failed: {e}")
        raise


if __name__ == "__main__":
    main()