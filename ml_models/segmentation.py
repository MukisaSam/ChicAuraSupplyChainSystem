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
warnings.filterwarnings('ignore')

class CustomerSegmentation:
    def __init__(self):
        self.scaler = StandardScaler()
        self.kmeans = None
        self.n_clusters = None
        self.feature_names = None
        self.product_features = None
        self.customer_product_matrix = None
        self.category_affinity = None
        self.price_sensitivity = None
        self.db_connection = None
    
    def connect_database(self):
        """Connect to the XAMPP database using existing configuration"""
        try:
            self.db_connection = get_connector()
            print("Database connection established successfully")
            return True
        except Exception as e:
            print(f"Database connection failed: {e}")
            return False
    
    def get_customer_order_data(self):
        """Extract customer order data from the database"""
        if not self.db_connection:
            if not self.connect_database():
                return None
        
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
            df = pd.read_sql(query, self.db_connection)
            return df
        except Exception as e:
            print(f"Error fetching customer order data: {e}")
            return None
    
    def get_customer_product_data(self):
        """Extract detailed customer-product interaction data"""
        if not self.db_connection:
            if not self.connect_database():
                return None
        
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
            df = pd.read_sql(query, self.db_connection)
            return df
        except Exception as e:
            print(f"Error fetching customer product data: {e}")
            return None
    
    def get_customer_demographics(self):
        """Extract customer demographic information"""
        if not self.db_connection:
            if not self.connect_database():
                return None
        
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
            df = pd.read_sql(query, self.db_connection)
            return df
        except Exception as e:
            print(f"Error fetching customer demographics: {e}")
            return None
    
    def close_database_connection(self):
        """Close the database connection"""
        if self.db_connection:
            self.db_connection.close()
            print("Database connection closed")
        
    def calculate_rfm_features(self, df):
        """Calculate RFM (Recency, Frequency, Monetary) features"""
        current_date = datetime.now()
        
        # Calculate Recency (days since last order)
        df['last_order_date'] = pd.to_datetime(df['order_date'])
        df['recency'] = (current_date - df['last_order_date']).dt.days
        
        # Calculate Frequency (number of orders)
        frequency = df.groupby('customer_id')['order_id'].count().reset_index()
        frequency.columns = ['customer_id', 'frequency']
        
        # Calculate Monetary (total spend)
        monetary = df.groupby('customer_id')['total_amount'].sum().reset_index()
        monetary.columns = ['customer_id', 'monetary']
        
        # Get recency for each customer (minimum recency = most recent order)
        recency = df.groupby('customer_id')['recency'].min().reset_index()
        
        # Merge all RFM features
        rfm = recency.merge(frequency, on='customer_id').merge(monetary, on='customer_id')
        
        return rfm
    
    def calculate_behavioral_features(self, df):
        """Calculate behavioral features"""
        behavioral = df.groupby('customer_id').agg({
            'total_amount': ['mean', 'std', 'min', 'max'],
            'order_id': 'count',
            'order_date': ['min', 'max']
        }).reset_index()
        
        # Flatten column names
        behavioral.columns = ['customer_id', 'avg_order_value', 'order_value_std', 
                            'min_order_value', 'max_order_value', 'total_orders',
                            'first_order_date', 'last_order_date']
        
        # Calculate customer lifetime (days between first and last order)
        behavioral['first_order_date'] = pd.to_datetime(behavioral['first_order_date'])
        behavioral['last_order_date'] = pd.to_datetime(behavioral['last_order_date'])
        behavioral['customer_lifetime'] = (behavioral['last_order_date'] - behavioral['first_order_date']).dt.days
        
        # Calculate order frequency (orders per day)
        behavioral['order_frequency'] = behavioral['total_orders'] / (behavioral['customer_lifetime'] + 1)
        
        # Fill NaN values for standard deviation (customers with single orders)
        behavioral['order_value_std'] = behavioral['order_value_std'].fillna(0)
        
        return behavioral
    
    def calculate_category_affinity(self, df_with_products):
        """Calculate category affinity scores for each customer"""
        if 'category' not in df_with_products.columns:
            print("Warning: 'category' column not found. Skipping category affinity calculation.")
            return pd.DataFrame()
        
        # Calculate category purchase frequency
        category_freq = df_with_products.groupby(['customer_id', 'category']).agg({
            'quantity': 'sum',
            'total_amount': 'sum',
            'order_id': 'count'
        }).reset_index()
        
        # Calculate total purchases per customer
        customer_totals = df_with_products.groupby('customer_id').agg({
            'quantity': 'sum',
            'total_amount': 'sum',
            'order_id': 'count'
        }).reset_index()
        customer_totals.columns = ['customer_id', 'total_quantity', 'total_amount_all', 'total_orders']
        
        # Merge and calculate affinity scores
        category_affinity = category_freq.merge(customer_totals, on='customer_id')
        
        # Calculate affinity metrics
        category_affinity['quantity_affinity'] = category_affinity['quantity'] / category_affinity['total_quantity']
        category_affinity['amount_affinity'] = category_affinity['total_amount'] / category_affinity['total_amount_all']
        category_affinity['frequency_affinity'] = category_affinity['order_id'] / category_affinity['total_orders']
        
        # Average affinity score
        category_affinity['affinity_score'] = (
            category_affinity['quantity_affinity'] * 0.3 +
            category_affinity['amount_affinity'] * 0.4 +
            category_affinity['frequency_affinity'] * 0.3
        )
        
        return category_affinity[['customer_id', 'category', 'affinity_score']]
    
    def calculate_price_sensitivity(self, df_with_products):
        """Calculate price sensitivity index for each customer"""
        if 'unit_price' not in df_with_products.columns:
            print("Warning: 'unit_price' column not found. Skipping price sensitivity calculation.")
            return pd.DataFrame()
        
        # Calculate price metrics per customer
        price_metrics = df_with_products.groupby('customer_id').agg({
            'unit_price': ['mean', 'std', 'min', 'max'],
            'quantity': 'sum',
            'total_amount': 'sum'
        }).reset_index()
        
        # Flatten column names
        price_metrics.columns = ['customer_id', 'avg_unit_price', 'price_std', 'min_price', 'max_price', 'total_quantity', 'total_spent']
        
        # Calculate price sensitivity metrics
        price_metrics['price_range'] = price_metrics['max_price'] - price_metrics['min_price']
        price_metrics['price_coefficient_variation'] = price_metrics['price_std'] / (price_metrics['avg_unit_price'] + 1e-6)
        price_metrics['price_per_unit'] = price_metrics['total_spent'] / price_metrics['total_quantity']
        
        # Calculate price sensitivity index (lower values = more price sensitive)
        # High price variation and low average price = high price sensitivity
        max_price_range = price_metrics['price_range'].max()
        max_avg_price = price_metrics['avg_unit_price'].max()
        
        price_metrics['price_sensitivity_index'] = (
            (1 - price_metrics['price_range'] / (max_price_range + 1e-6)) * 0.3 +
            (1 - price_metrics['avg_unit_price'] / (max_avg_price + 1e-6)) * 0.4 +
            (1 - price_metrics['price_coefficient_variation']) * 0.3
        )
        
        return price_metrics[['customer_id', 'price_sensitivity_index', 'avg_unit_price', 'price_range']]
    
    def calculate_seasonal_patterns(self, df):
        """Calculate seasonal purchase patterns"""
        df['order_date'] = pd.to_datetime(df['order_date'])
        df['month'] = df['order_date'].dt.month
        df['quarter'] = df['order_date'].dt.quarter
        df['day_of_week'] = df['order_date'].dt.dayofweek
        
        # Monthly patterns
        monthly_patterns = df.groupby(['customer_id', 'month']).agg({
            'total_amount': 'sum',
            'order_id': 'count'
        }).reset_index()
        
        # Calculate seasonal preference scores
        customer_monthly = monthly_patterns.groupby('customer_id').agg({
            'total_amount': ['sum', 'std'],
            'order_id': ['sum', 'std']
        }).reset_index()
        
        # Flatten column names
        customer_monthly.columns = ['customer_id', 'total_amount_all', 'amount_seasonal_std', 'total_orders_all', 'order_seasonal_std']
        
        # Calculate seasonality index (higher = more seasonal)
        customer_monthly['seasonality_index'] = (
            customer_monthly['amount_seasonal_std'] / (customer_monthly['total_amount_all'] + 1e-6) +
            customer_monthly['order_seasonal_std'] / (customer_monthly['total_orders_all'] + 1e-6)
        ) / 2
        
        return customer_monthly[['customer_id', 'seasonality_index']]
    
    def calculate_product_recommendation_features(self, df_with_products):
        """Calculate all product recommendation features"""
        features = {}
        
        # Category affinity
        if 'category' in df_with_products.columns:
            category_affinity = self.calculate_category_affinity(df_with_products)
            # Pivot to get category affinity as features
            category_pivot = category_affinity.pivot(index='customer_id', columns='category', values='affinity_score').fillna(0)
            category_pivot.columns = [f'category_affinity_{col}' for col in category_pivot.columns]
            features['category_affinity'] = category_pivot
        
        # Price sensitivity
        if 'unit_price' in df_with_products.columns:
            price_sensitivity = self.calculate_price_sensitivity(df_with_products)
            features['price_sensitivity'] = price_sensitivity.set_index('customer_id')
        
        # Seasonal patterns
        seasonal_patterns = self.calculate_seasonal_patterns(df_with_products)
        features['seasonal_patterns'] = seasonal_patterns.set_index('customer_id')
        
        # Material preferences (if available)
        if 'material' in df_with_products.columns:
            material_affinity = df_with_products.groupby(['customer_id', 'material']).agg({
                'quantity': 'sum',
                'total_amount': 'sum'
            }).reset_index()
            
            # Calculate material preference scores
            customer_totals = df_with_products.groupby('customer_id')['total_amount'].sum().reset_index()
            customer_totals.columns = ['customer_id', 'total_spent']
            
            material_affinity = material_affinity.merge(customer_totals, on='customer_id')
            material_affinity['material_preference'] = material_affinity['total_amount'] / material_affinity['total_spent']
            
            # Pivot material preferences
            material_pivot = material_affinity.pivot(index='customer_id', columns='material', values='material_preference').fillna(0)
            material_pivot.columns = [f'material_pref_{col}' for col in material_pivot.columns]
            features['material_preferences'] = material_pivot
        
        # Merge all features
        combined_features = None
        for feature_name, feature_df in features.items():
            if combined_features is None:
                combined_features = feature_df
            else:
                combined_features = combined_features.join(feature_df, how='outer')
        
        if combined_features is not None:
            combined_features = combined_features.fillna(0)
        
        return combined_features
    
    def prepare_features(self, df, df_with_products=None):
        """Prepare all features for clustering"""
        # Calculate RFM features
        rfm = self.calculate_rfm_features(df)
        
        # Calculate behavioral features
        behavioral = self.calculate_behavioral_features(df)
        
        # Merge RFM and behavioral features
        features = rfm.merge(behavioral, on='customer_id')
        
        # Add product recommendation features if product data is available
        if df_with_products is not None:
            product_features = self.calculate_product_recommendation_features(df_with_products)
            if product_features is not None:
                features = features.set_index('customer_id').join(product_features, how='left').reset_index()
                features = features.fillna(0)
        
        # Select features for clustering
        base_features = ['recency', 'frequency', 'monetary', 'avg_order_value', 
                        'order_value_std', 'customer_lifetime', 'order_frequency']
        
        # Add product recommendation features if available
        additional_features = [col for col in features.columns if col not in base_features + ['customer_id']]
        feature_columns = base_features + additional_features
        
        clustering_features = features[['customer_id'] + feature_columns].copy()
        
        # Handle any remaining NaN values
        clustering_features = clustering_features.fillna(0)
        
        self.feature_names = feature_columns
        
        return clustering_features
    
    def find_optimal_clusters(self, X, max_clusters=10):
        """Find optimal number of clusters using elbow method and silhouette score"""
        inertias = []
        silhouette_scores = []
        K_range = range(2, max_clusters + 1)
        
        for k in K_range:
            kmeans = KMeans(n_clusters=k, random_state=42, n_init=10)
            kmeans.fit(X)
            inertias.append(kmeans.inertia_)
            silhouette_scores.append(silhouette_score(X, kmeans.labels_))
        
        # Plot results
        fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(12, 5))
        
        # Elbow plot
        ax1.plot(K_range, inertias, 'bo-')
        ax1.set_xlabel('Number of Clusters (k)')
        ax1.set_ylabel('Inertia')
        ax1.set_title('Elbow Method')
        ax1.grid(True)
        
        # Silhouette score plot
        ax2.plot(K_range, silhouette_scores, 'ro-')
        ax2.set_xlabel('Number of Clusters (k)')
        ax2.set_ylabel('Silhouette Score')
        ax2.set_title('Silhouette Score')
        ax2.grid(True)
        
        plt.tight_layout()
        plt.show()
        
        # Return optimal k based on silhouette score
        optimal_k = K_range[np.argmax(silhouette_scores)]
        return optimal_k
    
    def perform_clustering(self, features_df, n_clusters=None):
        """Perform K-means clustering"""
        X = features_df[self.feature_names].values
        
        # Standardize features
        X_scaled = self.scaler.fit_transform(X)
        
        # Find optimal clusters if not specified
        if n_clusters is None:
            n_clusters = self.find_optimal_clusters(X_scaled)
        
        # Perform clustering
        self.kmeans = KMeans(n_clusters=n_clusters, random_state=42, n_init=10)
        clusters = self.kmeans.fit_predict(X_scaled)
        
        # Add cluster labels to dataframe
        features_df['cluster'] = clusters
        self.n_clusters = n_clusters
        
        return features_df
    
    def analyze_clusters(self, features_df):
        """Analyze and describe clusters"""
        cluster_summary = features_df.groupby('cluster')[self.feature_names].agg(['mean', 'std', 'count']).round(2)
        
        print("Cluster Analysis Summary:")
        print("=" * 50)
        
        for cluster_id in range(self.n_clusters):
            cluster_data = features_df[features_df['cluster'] == cluster_id]
            cluster_size = len(cluster_data)
            cluster_pct = (cluster_size / len(features_df)) * 100
            
            print(f"\nCluster {cluster_id} ({cluster_size} customers, {cluster_pct:.1f}%):")
            print("-" * 30)
            
            # Key characteristics
            avg_recency = cluster_data['recency'].mean()
            avg_frequency = cluster_data['frequency'].mean()
            avg_monetary = cluster_data['monetary'].mean()
            avg_order_value = cluster_data['avg_order_value'].mean()
            
            print(f"  Recency: {avg_recency:.1f} days")
            print(f"  Frequency: {avg_frequency:.1f} orders")
            print(f"  Monetary: ${avg_monetary:.2f}")
            print(f"  Avg Order Value: ${avg_order_value:.2f}")
            
            # Segment interpretation
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
            
            print(f"  Segment Type: {segment_type}")
        
        return cluster_summary
    
    def generate_product_recommendations(self, customer_id, features_df, df_with_products=None, top_n=5):
        """Generate product recommendations for a specific customer based on their segment"""
        if df_with_products is None:
            print("Product data not available for recommendations")
            return []
        
        # Get customer's cluster
        customer_cluster = features_df[features_df['customer_id'] == customer_id]['cluster'].iloc[0]
        
        # Get customers in the same cluster
        same_cluster_customers = features_df[features_df['cluster'] == customer_cluster]['customer_id'].tolist()
        
        # Get products purchased by similar customers
        similar_customer_products = df_with_products[
            df_with_products['customer_id'].isin(same_cluster_customers)
        ]
        
        # Get products already purchased by the target customer
        customer_products = set(df_with_products[
            df_with_products['customer_id'] == customer_id
        ]['product_id'].unique()) if 'product_id' in df_with_products.columns else set()
        
        # Calculate product popularity within the cluster
        product_popularity = similar_customer_products.groupby('product_id').agg({
            'quantity': 'sum',
            'total_amount': 'sum',
            'customer_id': 'nunique'
        }).reset_index()
        
        product_popularity.columns = ['product_id', 'total_quantity', 'total_revenue', 'unique_customers']
        
        # Calculate recommendation score
        product_popularity['recommendation_score'] = (
            product_popularity['total_quantity'] * 0.3 +
            product_popularity['total_revenue'] * 0.4 +
            product_popularity['unique_customers'] * 0.3
        )
        
        # Filter out products already purchased
        recommendations = product_popularity[
            ~product_popularity['product_id'].isin(customer_products)
        ].sort_values('recommendation_score', ascending=False).head(top_n)
        
        return recommendations.to_dict('records')
    
    def generate_category_recommendations(self, customer_id, features_df, df_with_products=None, top_n=3):
        """Generate category recommendations based on customer segment"""
        if df_with_products is None or 'category' not in df_with_products.columns:
            print("Category data not available for recommendations")
            return []
        
        # Get customer's cluster
        customer_cluster = features_df[features_df['customer_id'] == customer_id]['cluster'].iloc[0]
        
        # Get customers in the same cluster
        same_cluster_customers = features_df[features_df['cluster'] == customer_cluster]['customer_id'].tolist()
        
        # Get categories purchased by similar customers
        similar_customer_categories = df_with_products[
            df_with_products['customer_id'].isin(same_cluster_customers)
        ]
        
        # Get categories already purchased by the target customer
        customer_categories = set(df_with_products[
            df_with_products['customer_id'] == customer_id
        ]['category'].unique())
        
        # Calculate category popularity within the cluster
        category_popularity = similar_customer_categories.groupby('category').agg({
            'quantity': 'sum',
            'total_amount': 'sum',
            'customer_id': 'nunique'
        }).reset_index()
        
        category_popularity.columns = ['category', 'total_quantity', 'total_revenue', 'unique_customers']
        
        # Calculate recommendation score
        category_popularity['recommendation_score'] = (
            category_popularity['total_quantity'] * 0.3 +
            category_popularity['total_revenue'] * 0.4 +
            category_popularity['unique_customers'] * 0.3
        )
        
        # Filter out categories already purchased frequently
        recommendations = category_popularity[
            ~category_popularity['category'].isin(customer_categories)
        ].sort_values('recommendation_score', ascending=False).head(top_n)
        
        return recommendations.to_dict('records')
    
    def create_customer_product_matrix(self, df_with_products):
        """Create customer-product interaction matrix for collaborative filtering"""
        if 'product_id' not in df_with_products.columns:
            print("Product ID not available for matrix creation")
            return None
        
        # Create interaction matrix
        interaction_matrix = df_with_products.groupby(['customer_id', 'product_id']).agg({
            'quantity': 'sum',
            'total_amount': 'sum'
        }).reset_index()
        
        # Create weighted interaction score
        interaction_matrix['interaction_score'] = (
            interaction_matrix['quantity'] * 0.6 +
            interaction_matrix['total_amount'] * 0.4
        )
        
        # Pivot to create matrix
        matrix = interaction_matrix.pivot(index='customer_id', columns='product_id', values='interaction_score').fillna(0)
        
        self.customer_product_matrix = matrix
        return matrix
    
    def collaborative_filtering_recommendations(self, customer_id, top_n=5):
        """Generate recommendations using collaborative filtering"""
        if self.customer_product_matrix is None:
            print("Customer-product matrix not available")
            return []
        
        if customer_id not in self.customer_product_matrix.index:
            print(f"Customer {customer_id} not found in matrix")
            return []
        
        # Calculate customer similarity using cosine similarity
        customer_similarity = cosine_similarity(self.customer_product_matrix)
        customer_similarity_df = pd.DataFrame(
            customer_similarity, 
            index=self.customer_product_matrix.index,
            columns=self.customer_product_matrix.index
        )
        
        # Get similar customers
        similar_customers = customer_similarity_df[customer_id].sort_values(ascending=False)[1:6]  # Top 5 similar customers
        
        # Get products purchased by similar customers
        customer_products = set(self.customer_product_matrix.columns[self.customer_product_matrix.loc[customer_id] > 0])
        
        recommendations = defaultdict(float)
        
        for similar_customer, similarity_score in similar_customers.items():
            similar_customer_products = self.customer_product_matrix.loc[similar_customer]
            
            for product_id, interaction_score in similar_customer_products.items():
                if product_id not in customer_products and interaction_score > 0:
                    recommendations[product_id] += similarity_score * interaction_score
        
        # Sort recommendations
        sorted_recommendations = sorted(recommendations.items(), key=lambda x: x[1], reverse=True)[:top_n]
        
        return [{'product_id': prod_id, 'recommendation_score': score} for prod_id, score in sorted_recommendations]
    
    def visualize_clusters(self, features_df):
        """Create visualizations for clusters"""
        fig, axes = plt.subplots(2, 2, figsize=(15, 12))
        
        # RFM 3D scatter plot (projected to 2D)
        axes[0, 0].scatter(features_df['recency'], features_df['monetary'], 
                          c=features_df['cluster'], cmap='viridis', alpha=0.6)
        axes[0, 0].set_xlabel('Recency (days)')
        axes[0, 0].set_ylabel('Monetary ($)')
        axes[0, 0].set_title('Recency vs Monetary')
        
        # Frequency vs Monetary
        axes[0, 1].scatter(features_df['frequency'], features_df['monetary'], 
                          c=features_df['cluster'], cmap='viridis', alpha=0.6)
        axes[0, 1].set_xlabel('Frequency')
        axes[0, 1].set_ylabel('Monetary ($)')
        axes[0, 1].set_title('Frequency vs Monetary')
        
        # Average Order Value distribution by cluster
        for cluster_id in range(self.n_clusters):
            cluster_data = features_df[features_df['cluster'] == cluster_id]
            axes[1, 0].hist(cluster_data['avg_order_value'], alpha=0.6, 
                           label=f'Cluster {cluster_id}', bins=20)
        axes[1, 0].set_xlabel('Average Order Value ($)')
        axes[1, 0].set_ylabel('Frequency')
        axes[1, 0].set_title('Average Order Value Distribution')
        axes[1, 0].legend()
        
        # Cluster size pie chart
        cluster_sizes = features_df['cluster'].value_counts().sort_index()
        axes[1, 1].pie(cluster_sizes.values, labels=[f'Cluster {i}' for i in cluster_sizes.index], 
                       autopct='%1.1f%%')
        axes[1, 1].set_title('Cluster Size Distribution')
        
        plt.tight_layout()
        plt.show()
    
    def export_results(self, features_df, filename='customer_segments.csv'):
        """Export clustering results to CSV"""
        features_df.to_csv(filename, index=False)
        print(f"Results exported to {filename}")
    
    def run_full_analysis(self, df=None, df_with_products=None, n_clusters=None, use_database=True):
        """Run complete customer segmentation analysis with product recommendations"""
        print("Starting Customer Segmentation Analysis...")
        print("=" * 50)
        
        # Load data from database if not provided
        if use_database and df is None:
            print("1. Loading data from database...")
            df = self.get_customer_order_data()
            if df is None:
                print("Failed to load customer order data from database")
                return None, None
            
            df_with_products = self.get_customer_product_data()
            if df_with_products is None:
                print("Warning: Failed to load product data from database")
        
        # Prepare features
        print("2. Preparing features...")
        features_df = self.prepare_features(df, df_with_products)
        
        # Perform clustering
        print("3. Performing clustering...")
        features_df = self.perform_clustering(features_df, n_clusters)
        
        # Analyze clusters
        print("4. Analyzing clusters...")
        cluster_summary = self.analyze_clusters(features_df)
        
        # Create product matrix if product data is available
        if df_with_products is not None:
            print("5. Creating customer-product matrix...")
            self.create_customer_product_matrix(df_with_products)
        
        # Visualize results
        print("6. Creating visualizations...")
        self.visualize_clusters(features_df)
        
        # Export results
        print("7. Exporting results...")
        self.export_results(features_df)
        
        # Close database connection
        if use_database:
            self.close_database_connection()
        
        return features_df, cluster_summary
    
    def run_database_analysis(self, n_clusters=None):
        """Run analysis directly from database"""
        return self.run_full_analysis(use_database=True, n_clusters=n_clusters)
    
    def get_customer_recommendations(self, customer_id, features_df, df_with_products=None, method='segment', top_n=5):
        """Get product recommendations for a specific customer"""
        if method == 'segment':
            return self.generate_product_recommendations(customer_id, features_df, df_with_products, top_n)
        elif method == 'collaborative':
            return self.collaborative_filtering_recommendations(customer_id, top_n)
        elif method == 'category':
            return self.generate_category_recommendations(customer_id, features_df, df_with_products, top_n)
        else:
            print("Invalid method. Use 'segment', 'collaborative', or 'category'")
            return []

# Example usage
if __name__ == "__main__":
    # Initialize segmentation
    segmentation = CustomerSegmentation()
    print("Enhanced Customer Segmentation with Product Recommendations module ready!")
    print("Features included:")
    print("- RFM Analysis")
    print("- Category Affinity Scoring")
    print("- Price Sensitivity Analysis")
    print("- Seasonal Purchase Patterns")
    print("- Material Preferences")
    print("- Segment-based Recommendations")
    print("- Collaborative Filtering")
    print("- Category Recommendations")
    print("\nLoad your data and call segmentation.run_full_analysis(df, df_with_products) to start.")