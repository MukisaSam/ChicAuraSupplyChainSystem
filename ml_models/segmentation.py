import pandas as pd
import numpy as np
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import silhouette_score
from sklearn.metrics.pairwise import cosine_similarity
import matplotlib.pyplot as plt
import seaborn as sns
from datetime import datetime, timedelta
from collections import defaultdict
import json
from db_config import get_connector
import warnings
import logging
from typing import Optional, Dict, List, Tuple, Any, Union
from contextlib import contextmanager
from dataclasses import dataclass
from pathlib import Path

warnings.filterwarnings('ignore')

logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('segmentation.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

@dataclass
class SegmentationConfig:
    """Configuration class for segmentation parameters"""
    quantity_weight: float = 0.3
    amount_weight: float = 0.4
    frequency_weight: float = 0.3
    
    price_range_weight: float = 0.3
    avg_price_weight: float = 0.4
    price_variation_weight: float = 0.3
    
    quantity_interaction_weight: float = 0.6
    amount_interaction_weight: float = 0.4
    
    max_clusters: int = 10
    random_state: int = 42
    n_init: int = 10
    
    epsilon: float = 1e-6
    
    export_filename: str = 'customer_segments.csv'
    log_filename: str = 'segmentation.log'

class CustomerSegmentation:
    """Enhanced customer segmentation with product recommendations.
    
    This class provides comprehensive customer segmentation capabilities including:
    - RFM (Recency, Frequency, Monetary) analysis
    - Category affinity scoring
    - Price sensitivity analysis
    - Seasonal purchase patterns
    - Material preferences
    - Segment-based recommendations
    - Collaborative filtering
    
    Attributes:
        config: Configuration object with all parameters
        scaler: StandardScaler for feature normalization
        kmeans: KMeans clustering model
        n_clusters: Number of clusters
        feature_names: List of feature column names
        customer_product_matrix: Matrix for collaborative filtering
    """
    
    def __init__(self, config: Optional[SegmentationConfig] = None):
        """Initialize CustomerSegmentation with optional configuration.
        
        Args:
            config: Configuration object. If None, uses default configuration.
        """
        self.config = config or SegmentationConfig()
        self.scaler = StandardScaler()
        self.kmeans: Optional[KMeans] = None
        self.n_clusters: Optional[int] = None
        self.feature_names: Optional[List[str]] = None
        self.customer_product_matrix: Optional[pd.DataFrame] = None
        logger.info("CustomerSegmentation initialized with configuration")
    
    def _validate_customer_id(self, customer_id: Union[int, str]) -> bool:
        """Validate customer ID input.
        
        Args:
            customer_id: Customer ID to validate
            
        Returns:
            bool: True if valid, False otherwise
        """
        if customer_id is None:
            logger.error("Customer ID cannot be None")
            return False
        
        if isinstance(customer_id, str) and not customer_id.strip():
            logger.error("Customer ID cannot be empty string")
            return False
            
        try:
            int(customer_id)
            return True
        except (ValueError, TypeError):
            logger.error(f"Invalid customer ID format: {customer_id}")
            return False
    
    def _validate_dataframe(self, df: pd.DataFrame, required_columns: List[str]) -> bool:
        """Validate DataFrame has required columns.
        
        Args:
            df: DataFrame to validate
            required_columns: List of required column names
            
        Returns:
            bool: True if valid, False otherwise
        """
        if df is None or df.empty:
            logger.error("DataFrame is None or empty")
            return False
            
        missing_columns = [col for col in required_columns if col not in df.columns]
        if missing_columns:
            logger.error(f"Missing required columns: {missing_columns}")
            return False
            
        return True
    
    @contextmanager
    def _database_connection(self):
        """Context manager for database connections.
        
        Yields:
            Database connection object
            
        Raises:
            Exception: If connection fails
        """
        connection = None
        try:
            connection = get_connector()
            logger.info("Database connection established successfully")
            yield connection
        except Exception as e:
            logger.error(f"Database connection failed: {e}")
            raise
        finally:
            if connection:
                try:
                    connection.close()
                    logger.info("Database connection closed")
                except Exception as e:
                    logger.warning(f"Error closing database connection: {e}")
    
    def get_customer_order_data(self) -> Optional[pd.DataFrame]:
        """Extract customer order data from the database.
        
        Returns:
            DataFrame with customer order data or None if error occurs
            
        Raises:
            Exception: If database query fails
        """
        query = """
        SELECT 
            o.wholesaler_id as customer_id,
            o.id as order_id,
            o.order_date,
            o.total_amount,
            o.status,
            o.payment_method,
            w.business_type,
            w.business_address,
            w.preferred_categories,
            w.monthly_order_volume,
            u.name as customer_name,
            u.email as customer_email
        FROM orders o
        JOIN wholesalers w ON o.wholesaler_id = w.id
        JOIN users u ON w.user_id = u.id
        WHERE o.status IN ('delivered', 'confirmed', 'shipped')
        ORDER BY o.order_date DESC
        """
        
        try:
            with self._database_connection() as connection:
                df = pd.read_sql(query, connection)
                
                if df.empty:
                    logger.warning("No customer order data found")
                    return None
                    
                logger.info(f"Successfully loaded {len(df)} customer order records")
                return df
                
        except Exception as e:
            logger.error(f"Error fetching customer order data: {e}")
            return None
    
    def get_customer_product_data(self) -> Optional[pd.DataFrame]:
        """Extract detailed customer-product interaction data.
        
        Returns:
            DataFrame with customer-product interaction data or None if error occurs
            
        Raises:
            Exception: If database query fails
        """
        query = """
        SELECT 
            o.wholesaler_id as customer_id,
            o.id as order_id,
            oi.item_id as product_id,
            o.order_date,
            o.total_amount,
            o.status,
            o.payment_method,
            oi.quantity,
            oi.unit_price,
            oi.total_price,
            i.name as product_name,
            i.category,
            i.material,
            i.base_price,
            i.size_range,
            i.color_options,
            w.business_type,
            w.business_address,
            w.preferred_categories,
            w.monthly_order_volume,
            u.name as customer_name,
            u.email as customer_email
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN items i ON oi.item_id = i.id
        JOIN wholesalers w ON o.wholesaler_id = w.id
        JOIN users u ON w.user_id = u.id
        WHERE o.status IN ('delivered', 'confirmed', 'shipped')
        ORDER BY o.order_date DESC
        """
        
        try:
            with self._database_connection() as connection:
                df = pd.read_sql(query, connection)
                
                if df.empty:
                    logger.warning("No customer product data found")
                    return None
                    
                logger.info(f"Successfully loaded {len(df)} customer product records")
                return df
                
        except Exception as e:
            logger.error(f"Error fetching customer product data: {e}")
            return None
    
    def get_customer_demographics(self) -> Optional[pd.DataFrame]:
        """Extract customer demographic information.
        
        Returns:
            DataFrame with customer demographics or None if error occurs
            
        Raises:
            Exception: If database query fails
        """
        query = """
        SELECT 
            w.id as customer_id,
            u.name as customer_name,
            u.email as customer_email,
            u.created_at as registration_date,
            w.business_type,
            w.business_address,
            w.preferred_categories,
            w.monthly_order_volume,
            w.phone,
            COUNT(DISTINCT o.id) as total_orders,
            COALESCE(SUM(o.total_amount), 0) as total_spent,
            COALESCE(AVG(o.total_amount), 0) as avg_order_value,
            MAX(o.order_date) as last_order_date,
            MIN(o.order_date) as first_order_date
        FROM wholesalers w
        JOIN users u ON w.user_id = u.id
        LEFT JOIN orders o ON w.id = o.wholesaler_id AND o.status IN ('delivered', 'confirmed', 'shipped')
        GROUP BY w.id, u.name, u.email, u.created_at, w.business_type, w.business_address, 
                 w.preferred_categories, w.monthly_order_volume, w.phone
        """
        
        try:
            with self._database_connection() as connection:
                df = pd.read_sql(query, connection)
                
                if df.empty:
                    logger.warning("No customer demographics found")
                    return None
                    
                logger.info(f"Successfully loaded {len(df)} customer demographic records")
                return df
                
        except Exception as e:
            logger.error(f"Error fetching customer demographics: {e}")
            return None
    
        
    def calculate_rfm_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """Calculate RFM (Recency, Frequency, Monetary) features.
        
        Args:
            df: DataFrame with customer order data
            
        Returns:
            DataFrame with RFM features for each customer
            
        Raises:
            ValueError: If required columns are missing
        """
        required_columns = ['customer_id', 'order_id', 'order_date', 'total_amount']
        if not self._validate_dataframe(df, required_columns):
            raise ValueError(f"DataFrame missing required columns: {required_columns}")
            
        try:
            current_date = datetime.now()
            
            df['last_order_date'] = pd.to_datetime(df['order_date'])
            df['recency'] = (current_date - df['last_order_date']).dt.days
            
            frequency = df.groupby('customer_id')['order_id'].count().reset_index()
            frequency.columns = ['customer_id', 'frequency']
            
            monetary = df.groupby('customer_id')['total_amount'].sum().reset_index()
            monetary.columns = ['customer_id', 'monetary']
            
            recency = df.groupby('customer_id')['recency'].min().reset_index()
            
            rfm = recency.merge(frequency, on='customer_id').merge(monetary, on='customer_id')
            
            logger.info(f"Successfully calculated RFM features for {len(rfm)} customers")
            return rfm
            
        except Exception as e:
            logger.error(f"Error calculating RFM features: {e}")
            raise
    
    def calculate_behavioral_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """Calculate behavioral features for customers.
        
        Args:
            df: DataFrame with customer order data
            
        Returns:
            DataFrame with behavioral features
        """
        behavioral = df.groupby('customer_id').agg({
            'total_amount': ['mean', 'std', 'min', 'max'],
            'order_id': 'count',
            'order_date': ['min', 'max']
        }).reset_index()
        
        behavioral.columns = ['customer_id', 'avg_order_value', 'order_value_std', 
                            'min_order_value', 'max_order_value', 'total_orders',
                            'first_order_date', 'last_order_date']
        
        behavioral['first_order_date'] = pd.to_datetime(behavioral['first_order_date'])
        behavioral['last_order_date'] = pd.to_datetime(behavioral['last_order_date'])
        behavioral['customer_lifetime'] = (behavioral['last_order_date'] - behavioral['first_order_date']).dt.days
        
        behavioral['order_frequency'] = behavioral['total_orders'] / (behavioral['customer_lifetime'] + 1)
        
        behavioral['order_value_std'] = behavioral['order_value_std'].fillna(0)
        
        return behavioral
    
    def calculate_category_affinity(self, df_with_products: pd.DataFrame) -> pd.DataFrame:
        """Calculate category affinity scores for each customer.
        
        Args:
            df_with_products: DataFrame with customer-product data
            
        Returns:
            DataFrame with category affinity scores
        """
        if 'category' not in df_with_products.columns:
            logger.warning("'category' column not found. Skipping category affinity calculation.")
            return pd.DataFrame()
        
        category_freq = df_with_products.groupby(['customer_id', 'category']).agg({
            'quantity': 'sum',
            'total_amount': 'sum',
            'order_id': 'count'
        }).reset_index()
        
        customer_totals = df_with_products.groupby('customer_id').agg({
            'quantity': 'sum',
            'total_amount': 'sum',
            'order_id': 'count'
        }).reset_index()
        customer_totals.columns = ['customer_id', 'total_quantity', 'total_amount_all', 'total_orders']
        
        category_affinity = category_freq.merge(customer_totals, on='customer_id')
        
        category_affinity['quantity_affinity'] = category_affinity['quantity'] / (
            category_affinity['total_quantity'] + self.config.epsilon
        )
        category_affinity['amount_affinity'] = category_affinity['total_amount'] / (
            category_affinity['total_amount_all'] + self.config.epsilon
        )
        category_affinity['frequency_affinity'] = category_affinity['order_id'] / (
            category_affinity['total_orders'] + self.config.epsilon
        )
        
        category_affinity['affinity_score'] = (
            category_affinity['quantity_affinity'] * self.config.quantity_weight +
            category_affinity['amount_affinity'] * self.config.amount_weight +
            category_affinity['frequency_affinity'] * self.config.frequency_weight
        )
        
        return category_affinity[['customer_id', 'category', 'affinity_score']]
    
    def calculate_price_sensitivity(self, df_with_products: pd.DataFrame) -> pd.DataFrame:
        """Calculate price sensitivity index for each customer.
        
        Args:
            df_with_products: DataFrame with customer-product data
            
        Returns:
            DataFrame with price sensitivity metrics
        """
        if 'unit_price' not in df_with_products.columns:
            logger.warning("'unit_price' column not found. Skipping price sensitivity calculation.")
            return pd.DataFrame()
        
        price_metrics = df_with_products.groupby('customer_id').agg({
            'unit_price': ['mean', 'std', 'min', 'max'],
            'quantity': 'sum',
            'total_amount': 'sum'
        }).reset_index()
        
        price_metrics.columns = ['customer_id', 'avg_unit_price', 'price_std', 'min_price', 'max_price', 'total_quantity', 'total_spent']
        
        price_metrics['price_range'] = price_metrics['max_price'] - price_metrics['min_price']
        price_metrics['price_coefficient_variation'] = price_metrics['price_std'] / (
            price_metrics['avg_unit_price'] + self.config.epsilon
        )
        price_metrics['price_per_unit'] = price_metrics['total_spent'] / price_metrics['total_quantity']
        
        max_price_range = price_metrics['price_range'].max()
        max_avg_price = price_metrics['avg_unit_price'].max()
        
        price_metrics['price_sensitivity_index'] = (
            (1 - price_metrics['price_range'] / (max_price_range + self.config.epsilon)) * self.config.price_range_weight +
            (1 - price_metrics['avg_unit_price'] / (max_avg_price + self.config.epsilon)) * self.config.avg_price_weight +
            (1 - price_metrics['price_coefficient_variation']) * self.config.price_variation_weight
        )
        
        return price_metrics[['customer_id', 'price_sensitivity_index', 'avg_unit_price', 'price_range']]
    
    def calculate_seasonal_patterns(self, df: pd.DataFrame) -> pd.DataFrame:
        """Calculate seasonal purchase patterns for customers.
        
        Args:
            df: DataFrame with customer order data
            
        Returns:
            DataFrame with seasonal pattern features
        """
        df['order_date'] = pd.to_datetime(df['order_date'])
        df['month'] = df['order_date'].dt.month
        df['quarter'] = df['order_date'].dt.quarter
        df['day_of_week'] = df['order_date'].dt.dayofweek
        
        monthly_patterns = df.groupby(['customer_id', 'month']).agg({
            'total_amount': 'sum',
            'order_id': 'count'
        }).reset_index()
        
        customer_monthly = monthly_patterns.groupby('customer_id').agg({
            'total_amount': ['sum', 'std'],
            'order_id': ['sum', 'std']
        }).reset_index()
        
        customer_monthly.columns = ['customer_id', 'total_amount_all', 'amount_seasonal_std', 'total_orders_all', 'order_seasonal_std']
        
        customer_monthly['seasonality_index'] = (
            customer_monthly['amount_seasonal_std'] / (customer_monthly['total_amount_all'] + self.config.epsilon) +
            customer_monthly['order_seasonal_std'] / (customer_monthly['total_orders_all'] + self.config.epsilon)
        ) / 2
        
        return customer_monthly[['customer_id', 'seasonality_index']]
    
    def calculate_product_recommendation_features(self, df_with_products: pd.DataFrame) -> Optional[pd.DataFrame]:
        """Calculate all product recommendation features.
        
        Args:
            df_with_products: DataFrame with customer-product data
            
        Returns:
            DataFrame with product recommendation features or None if error
        """
        features = {}
        
        if 'category' in df_with_products.columns:
            category_affinity = self.calculate_category_affinity(df_with_products)
            category_pivot = category_affinity.pivot(index='customer_id', columns='category', values='affinity_score').fillna(0)
            category_pivot.columns = [f'category_affinity_{col}' for col in category_pivot.columns]
            features['category_affinity'] = category_pivot
        
        if 'unit_price' in df_with_products.columns:
            price_sensitivity = self.calculate_price_sensitivity(df_with_products)
            features['price_sensitivity'] = price_sensitivity.set_index('customer_id')
        
        seasonal_patterns = self.calculate_seasonal_patterns(df_with_products)
        features['seasonal_patterns'] = seasonal_patterns.set_index('customer_id')
        
        if 'material' in df_with_products.columns:
            material_affinity = df_with_products.groupby(['customer_id', 'material']).agg({
                'quantity': 'sum',
                'total_amount': 'sum'
            }).reset_index()
            
            customer_totals = df_with_products.groupby('customer_id')['total_amount'].sum().reset_index()
            customer_totals.columns = ['customer_id', 'total_spent']
            
            material_affinity = material_affinity.merge(customer_totals, on='customer_id')
            material_affinity['material_preference'] = material_affinity['total_amount'] / (
                material_affinity['total_spent'] + self.config.epsilon
            )
            
            material_pivot = material_affinity.pivot(index='customer_id', columns='material', values='material_preference').fillna(0)
            material_pivot.columns = [f'material_pref_{col}' for col in material_pivot.columns]
            features['material_preferences'] = material_pivot
        
        combined_features = None
        for feature_name, feature_df in features.items():
            if combined_features is None:
                combined_features = feature_df
            else:
                combined_features = combined_features.join(feature_df, how='outer')
        
        if combined_features is not None:
            combined_features = combined_features.fillna(0)
        
        return combined_features
    
    def prepare_features(self, df: pd.DataFrame, df_with_products: Optional[pd.DataFrame] = None) -> pd.DataFrame:
        """Prepare all features for clustering.
        
        Args:
            df: DataFrame with customer order data
            df_with_products: DataFrame with customer-product data (optional)
            
        Returns:
            DataFrame with prepared features for clustering
        """
        # Core features: RFM + behavioral patterns
        rfm = self.calculate_rfm_features(df)
        behavioral = self.calculate_behavioral_features(df)
        features = rfm.merge(behavioral, on='customer_id')
        
        # Add product-specific features if available
        if df_with_products is not None:
            product_features = self.calculate_product_recommendation_features(df_with_products)
            if product_features is not None:
                features = features.set_index('customer_id').join(product_features, how='left').reset_index()
                features = features.fillna(0)
        
        # Base clustering features
        base_features = ['recency', 'frequency', 'monetary', 'avg_order_value', 
                        'order_value_std', 'customer_lifetime', 'order_frequency']
        
        additional_features = [col for col in features.columns if col not in base_features + ['customer_id']]
        feature_columns = base_features + additional_features
        
        clustering_features = features[['customer_id'] + feature_columns].copy()
        clustering_features = clustering_features.fillna(0)
        
        self.feature_names = feature_columns
        
        return clustering_features
    
    def find_optimal_clusters(self, X: np.ndarray, max_clusters: Optional[int] = None) -> int:
        """Find optimal number of clusters using elbow method and silhouette score.
        
        Args:
            X: Feature matrix
            max_clusters: Maximum number of clusters to test
            
        Returns:
            Optimal number of clusters
        """
        if max_clusters is None:
            max_clusters = self.config.max_clusters
            
        inertias = []
        silhouette_scores = []
        K_range = range(2, max_clusters + 1)
        
        logger.info(f"Testing cluster range from 2 to {max_clusters}")
        
        for k in K_range:
            kmeans = KMeans(
                n_clusters=k, 
                random_state=self.config.random_state, 
                n_init=self.config.n_init
            )
            kmeans.fit(X)
            inertias.append(kmeans.inertia_)
            silhouette_scores.append(silhouette_score(X, kmeans.labels_))
            logger.debug(f"k={k}: inertia={kmeans.inertia_:.2f}, silhouette={silhouette_scores[-1]:.3f}")
        
        # Plot results
        try:
            fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(12, 5))
            
            ax1.plot(K_range, inertias, 'bo-')
            ax1.set_xlabel('Number of Clusters (k)')
            ax1.set_ylabel('Inertia')
            ax1.set_title('Elbow Method')
            ax1.grid(True)
            
            ax2.plot(K_range, silhouette_scores, 'ro-')
            ax2.set_xlabel('Number of Clusters (k)')
            ax2.set_ylabel('Silhouette Score')
            ax2.set_title('Silhouette Score')
            ax2.grid(True)
            
            plt.tight_layout()
            plt.show()
        except Exception as e:
            logger.warning(f"Could not create plots: {e}")
        
        optimal_k = K_range[np.argmax(silhouette_scores)]
        best_silhouette = max(silhouette_scores)
        logger.info(f"Optimal number of clusters: {optimal_k} (silhouette score: {best_silhouette:.3f})")
        return optimal_k
    
    def perform_clustering(self, features_df: pd.DataFrame, n_clusters: Optional[int] = None) -> pd.DataFrame:
        """Perform K-means clustering on customer features.
        
        Args:
            features_df: DataFrame with customer features
            n_clusters: Number of clusters (optional, will find optimal if not provided)
            
        Returns:
            DataFrame with cluster assignments
        """
        X = features_df[self.feature_names].values
        X_scaled = self.scaler.fit_transform(X)
        
        if n_clusters is None:
            n_clusters = self.find_optimal_clusters(X_scaled)
        
        self.kmeans = KMeans(n_clusters=n_clusters, random_state=42, n_init=10)
        clusters = self.kmeans.fit_predict(X_scaled)
        
        features_df['cluster'] = clusters
        self.n_clusters = n_clusters
        
        return features_df
    
    def analyze_clusters(self, features_df: pd.DataFrame) -> pd.DataFrame:
        """Analyze and describe clusters.
        
        Args:
            features_df: DataFrame with customer features and cluster assignments
            
        Returns:
            DataFrame with cluster summary statistics
        """
        cluster_summary = features_df.groupby('cluster')[self.feature_names].agg(['mean', 'std', 'count']).round(2)
        
        logger.info("Cluster Analysis Summary:")
        logger.info("=" * 50)
        
        for cluster_id in range(self.n_clusters):
            cluster_data = features_df[features_df['cluster'] == cluster_id]
            cluster_size = len(cluster_data)
            cluster_pct = (cluster_size / len(features_df)) * 100
            
            logger.info(f"\nCluster {cluster_id} ({cluster_size} customers, {cluster_pct:.1f}%):")
            logger.info("-" * 30)
            
            avg_recency = cluster_data['recency'].mean()
            avg_frequency = cluster_data['frequency'].mean()
            avg_monetary = cluster_data['monetary'].mean()
            avg_order_value = cluster_data['avg_order_value'].mean()
            
            logger.info(f"  Recency: {avg_recency:.1f} days")
            logger.info(f"  Frequency: {avg_frequency:.1f} orders")
            logger.info(f"  Monetary: ${avg_monetary:.2f}")
            logger.info(f"  Avg Order Value: ${avg_order_value:.2f}")
            
            if avg_recency < 30 and avg_frequency > 5 and avg_monetary > 1000:
                segment_type = "Premium/VIP Customers"
            elif avg_recency < 60 and avg_frequency > 3:
                segment_type = "Regular Customers"
            elif avg_recency > 90 and avg_frequency < 2:
                segment_type = "At-Risk/Dormant Customers"
            elif avg_frequency < 2 and avg_recency < 60:
                segment_type = "New Customers"
            else:
                segment_type = "Occasional Customers"
            
            logger.info(f"  Segment Type: {segment_type}")
        
        return cluster_summary
    
    def generate_product_recommendations(
        self, 
        customer_id: Union[int, str], 
        features_df: pd.DataFrame, 
        df_with_products: Optional[pd.DataFrame] = None, 
        top_n: int = 5
    ) -> List[Dict[str, Any]]:
        """Generate product recommendations for a specific customer based on their segment.
        
        Args:
            customer_id: Customer ID
            features_df: DataFrame with customer features and clusters
            df_with_products: DataFrame with customer-product data
            top_n: Number of recommendations to return
            
        Returns:
            List of product recommendations
        """
        if not self._validate_customer_id(customer_id):
            return []
            
        if df_with_products is None:
            logger.warning("Product data not available for recommendations")
            return []
        
        customer_data = features_df[features_df['customer_id'] == customer_id]
        if customer_data.empty:
            logger.warning(f"Customer {customer_id} not found in features data")
            return []
            
        customer_cluster = customer_data['cluster'].iloc[0]
        
        same_cluster_customers = features_df[features_df['cluster'] == customer_cluster]['customer_id'].tolist()
        
        similar_customer_products = df_with_products[
            df_with_products['customer_id'].isin(same_cluster_customers)
        ]
        
        customer_products = set(df_with_products[
            df_with_products['customer_id'] == customer_id
        ]['product_id'].unique()) if 'product_id' in df_with_products.columns else set()
        
        product_popularity = similar_customer_products.groupby('product_id').agg({
            'quantity': 'sum',
            'total_amount': 'sum',
            'customer_id': 'nunique'
        }).reset_index()
        
        product_popularity.columns = ['product_id', 'total_quantity', 'total_revenue', 'unique_customers']
        
        product_popularity['recommendation_score'] = (
            product_popularity['total_quantity'] * self.config.quantity_weight +
            product_popularity['total_revenue'] * self.config.amount_weight +
            product_popularity['unique_customers'] * self.config.frequency_weight
        )
        
        recommendations = product_popularity[
            ~product_popularity['product_id'].isin(customer_products)
        ].sort_values('recommendation_score', ascending=False).head(top_n)
        
        return recommendations.to_dict('records')
    
    def generate_category_recommendations(
        self, 
        customer_id: Union[int, str], 
        features_df: pd.DataFrame, 
        df_with_products: Optional[pd.DataFrame] = None, 
        top_n: int = 3
    ) -> List[Dict[str, Any]]:
        """Generate category recommendations based on customer segment.
        
        Args:
            customer_id: Customer ID
            features_df: DataFrame with customer features and clusters
            df_with_products: DataFrame with customer-product data
            top_n: Number of recommendations to return
            
        Returns:
            List of category recommendations
        """
        if not self._validate_customer_id(customer_id):
            return []
            
        if df_with_products is None or 'category' not in df_with_products.columns:
            logger.warning("Category data not available for recommendations")
            return []
        
        customer_data = features_df[features_df['customer_id'] == customer_id]
        if customer_data.empty:
            logger.warning(f"Customer {customer_id} not found in features data")
            return []
            
        customer_cluster = customer_data['cluster'].iloc[0]
        
        same_cluster_customers = features_df[features_df['cluster'] == customer_cluster]['customer_id'].tolist()
        
        similar_customer_categories = df_with_products[
            df_with_products['customer_id'].isin(same_cluster_customers)
        ]
        
        customer_categories = set(df_with_products[
            df_with_products['customer_id'] == customer_id
        ]['category'].unique())
        
        category_popularity = similar_customer_categories.groupby('category').agg({
            'quantity': 'sum',
            'total_amount': 'sum',
            'customer_id': 'nunique'
        }).reset_index()
        
        category_popularity.columns = ['category', 'total_quantity', 'total_revenue', 'unique_customers']
        
        category_popularity['recommendation_score'] = (
            category_popularity['total_quantity'] * self.config.quantity_weight +
            category_popularity['total_revenue'] * self.config.amount_weight +
            category_popularity['unique_customers'] * self.config.frequency_weight
        )
        
        recommendations = category_popularity[
            ~category_popularity['category'].isin(customer_categories)
        ].sort_values('recommendation_score', ascending=False).head(top_n)
        
        return recommendations.to_dict('records')
    
    def create_customer_product_matrix(self, df_with_products: pd.DataFrame) -> Optional[pd.DataFrame]:
        """Create customer-product interaction matrix for collaborative filtering.
        
        Args:
            df_with_products: DataFrame with customer-product data
            
        Returns:
            Customer-product interaction matrix or None if creation fails
        """
        if 'product_id' not in df_with_products.columns:
            logger.warning("Product ID not available for matrix creation")
            return None
        
        interaction_matrix = df_with_products.groupby(['customer_id', 'product_id']).agg({
            'quantity': 'sum',
            'total_amount': 'sum'
        }).reset_index()
        
        interaction_matrix['interaction_score'] = (
            interaction_matrix['quantity'] * self.config.quantity_interaction_weight +
            interaction_matrix['total_amount'] * self.config.amount_interaction_weight
        )
        
        matrix = interaction_matrix.pivot(index='customer_id', columns='product_id', values='interaction_score').fillna(0)
        
        self.customer_product_matrix = matrix
        return matrix
    
    def collaborative_filtering_recommendations(
        self, 
        customer_id: Union[int, str], 
        top_n: int = 5
    ) -> List[Dict[str, Any]]:
        """Generate recommendations using collaborative filtering.
        
        Args:
            customer_id: Customer ID
            top_n: Number of recommendations to return
            
        Returns:
            List of product recommendations
        """
        if not self._validate_customer_id(customer_id):
            return []
            
        if self.customer_product_matrix is None:
            logger.warning("Customer-product matrix not available")
            return []
        
        if customer_id not in self.customer_product_matrix.index:
            logger.warning(f"Customer {customer_id} not found in matrix")
            return []
        
        customer_similarity = cosine_similarity(self.customer_product_matrix)
        customer_similarity_df = pd.DataFrame(
            customer_similarity, 
            index=self.customer_product_matrix.index,
            columns=self.customer_product_matrix.index
        )
        
        similar_customers = customer_similarity_df[customer_id].sort_values(ascending=False)[1:6]
        
        customer_products = set(self.customer_product_matrix.columns[self.customer_product_matrix.loc[customer_id] > 0])
        
        recommendations = defaultdict(float)
        
        for similar_customer, similarity_score in similar_customers.items():
            similar_customer_products = self.customer_product_matrix.loc[similar_customer]
            
            for product_id, interaction_score in similar_customer_products.items():
                if product_id not in customer_products and interaction_score > 0:
                    recommendations[product_id] += similarity_score * interaction_score
        
        sorted_recommendations = sorted(recommendations.items(), key=lambda x: x[1], reverse=True)[:top_n]
        
        return [{'product_id': prod_id, 'recommendation_score': score} for prod_id, score in sorted_recommendations]
    
    def visualize_clusters(self, features_df: pd.DataFrame) -> None:
        """Create visualizations for clusters.
        
        Args:
            features_df: DataFrame with customer features and cluster assignments
        """
        fig, axes = plt.subplots(2, 2, figsize=(15, 12))
        
        axes[0, 0].scatter(features_df['recency'], features_df['monetary'], 
                          c=features_df['cluster'], cmap='viridis', alpha=0.6)
        axes[0, 0].set_xlabel('Recency (days)')
        axes[0, 0].set_ylabel('Monetary ($)')
        axes[0, 0].set_title('Recency vs Monetary')
        
        axes[0, 1].scatter(features_df['frequency'], features_df['monetary'], 
                          c=features_df['cluster'], cmap='viridis', alpha=0.6)
        axes[0, 1].set_xlabel('Frequency')
        axes[0, 1].set_ylabel('Monetary ($)')
        axes[0, 1].set_title('Frequency vs Monetary')
        
        for cluster_id in range(self.n_clusters):
            cluster_data = features_df[features_df['cluster'] == cluster_id]
            axes[1, 0].hist(cluster_data['avg_order_value'], alpha=0.6, 
                           label=f'Cluster {cluster_id}', bins=20)
        axes[1, 0].set_xlabel('Average Order Value ($)')
        axes[1, 0].set_ylabel('Frequency')
        axes[1, 0].set_title('Average Order Value Distribution')
        axes[1, 0].legend()
        
        cluster_sizes = features_df['cluster'].value_counts().sort_index()
        axes[1, 1].pie(cluster_sizes.values, labels=[f'Cluster {i}' for i in cluster_sizes.index], 
                       autopct='%1.1f%%')
        axes[1, 1].set_title('Cluster Size Distribution')
        
        plt.tight_layout()
        plt.show()
    
    def export_results(self, features_df: pd.DataFrame, filename: Optional[str] = None) -> bool:
        """Export clustering results to CSV.
        
        Args:
            features_df: DataFrame with clustering results
            filename: Output filename (optional)
            
        Returns:
            True if export successful, False otherwise
        """
        if filename is None:
            filename = self.config.export_filename
            
        try:
            features_df.to_csv(filename, index=False)
            logger.info(f"Results exported to {filename}")
            return True
        except Exception as e:
            logger.error(f"Error exporting results: {e}")
            return False
    
    def run_full_analysis(
        self, 
        df: Optional[pd.DataFrame] = None, 
        df_with_products: Optional[pd.DataFrame] = None, 
        n_clusters: Optional[int] = None, 
        use_database: bool = True
    ) -> Tuple[Optional[pd.DataFrame], Optional[pd.DataFrame]]:
        """Run complete customer segmentation analysis with product recommendations.
        
        Args:
            df: Customer order data (optional)
            df_with_products: Customer-product data (optional)
            n_clusters: Number of clusters (optional)
            use_database: Whether to load data from database
            
        Returns:
            Tuple of (features_df, cluster_summary)
        """
        logger.info("Starting Customer Segmentation Analysis...")
        logger.info("=" * 50)
        
        try:
            # Step 1: Load data
            if use_database and df is None:
                logger.info("1. Loading data from database...")
                df = self.get_customer_order_data()
                if df is None:
                    logger.error("Failed to load customer order data from database")
                    return None, None
                
                df_with_products = self.get_customer_product_data()
                if df_with_products is None:
                    logger.warning("Failed to load product data from database")
            
            # Step 2: Feature engineering
            logger.info("2. Preparing features...")
            features_df = self.prepare_features(df, df_with_products)
            
            # Step 3: Clustering
            logger.info("3. Performing clustering...")
            features_df = self.perform_clustering(features_df, n_clusters)
            
            # Step 4: Analysis
            logger.info("4. Analyzing clusters...")
            cluster_summary = self.analyze_clusters(features_df)
            
            # Step 5: Recommendation setup
            if df_with_products is not None:
                logger.info("5. Creating customer-product matrix...")
                self.create_customer_product_matrix(df_with_products)
            
            # Step 6: Visualization
            logger.info("6. Creating visualizations...")
            self.visualize_clusters(features_df)
            
            # Step 7: Export
            logger.info("7. Exporting results...")
            self.export_results(features_df)
            
            logger.info("Customer segmentation analysis completed successfully")
            return features_df, cluster_summary
            
        except Exception as e:
            logger.error(f"Error during full analysis: {e}")
            return None, None
    
    def run_database_analysis(self, n_clusters: Optional[int] = None) -> Tuple[Optional[pd.DataFrame], Optional[pd.DataFrame]]:
        """Run analysis directly from database.
        
        Args:
            n_clusters: Number of clusters (optional)
            
        Returns:
            Tuple of (features_df, cluster_summary)
        """
        return self.run_full_analysis(use_database=True, n_clusters=n_clusters)
    
    def get_customer_recommendations(
        self, 
        customer_id: Union[int, str], 
        features_df: pd.DataFrame, 
        df_with_products: Optional[pd.DataFrame] = None, 
        method: str = 'segment', 
        top_n: int = 5
    ) -> List[Dict[str, Any]]:
        """Get product recommendations for a specific customer.
        
        Args:
            customer_id: Customer ID
            features_df: DataFrame with customer features and clusters
            df_with_products: DataFrame with customer-product data
            method: Recommendation method ('segment', 'collaborative', 'category')
            top_n: Number of recommendations to return
            
        Returns:
            List of recommendations
        """
        if not self._validate_customer_id(customer_id):
            return []
            
        if method == 'segment':
            return self.generate_product_recommendations(customer_id, features_df, df_with_products, top_n)
        elif method == 'collaborative':
            return self.collaborative_filtering_recommendations(customer_id, top_n)
        elif method == 'category':
            return self.generate_category_recommendations(customer_id, features_df, df_with_products, top_n)
        else:
            logger.error(f"Invalid method: {method}. Use 'segment', 'collaborative', or 'category'")
            return []

if __name__ == "__main__":
    segmentation = CustomerSegmentation()
    logger.info("Enhanced Customer Segmentation with Product Recommendations module ready!")
    logger.info("Features included:")
    logger.info("- RFM Analysis")
    logger.info("- Category Affinity Scoring")
    logger.info("- Price Sensitivity Analysis")
    logger.info("- Seasonal Purchase Patterns")
    logger.info("- Material Preferences")
    logger.info("- Segment-based Recommendations")
    logger.info("- Collaborative Filtering")
    logger.info("- Category Recommendations")
    logger.info("\nLoad your data and call segmentation.run_full_analysis(df, df_with_products) to start.")