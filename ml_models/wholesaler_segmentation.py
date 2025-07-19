import pandas as pd
import numpy as np
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler
import matplotlib.pyplot as plt
import json
from db_config import get_connector
import logging

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class WholesalerSegmentation:
    """Simple wholesaler segmentation using machine learning"""
    
    def __init__(self):
        self.scaler = StandardScaler()
        self.kmeans = None
        
    def get_wholesaler_data(self):
        """Get wholesaler data from database"""
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
            connection = get_connector()
            df = pd.read_sql(query, connection)
            connection.close()
            logger.info(f"Loaded {len(df)} wholesaler records")
            return df
        except Exception as e:
            logger.error(f"Error loading data: {e}")
            return None
    
    def parse_preferred_categories(self, preferred_categories_str):
        """Parse JSON preferred categories to extract insights"""
        try:
            if pd.isna(preferred_categories_str) or preferred_categories_str == '':
                return 0, 0  # num_categories, avg_percentage
            
            data = json.loads(preferred_categories_str)
            categories = data.get('categories', [])
            
            num_categories = len(categories)
            avg_percentage = np.mean([cat.get('percentage', 0) for cat in categories]) if categories else 0
            
            return num_categories, avg_percentage
        except:
            return 0, 0
    
    def prepare_features(self, df):
        """Prepare features for clustering"""
        # Calculate recency (days since last order)
        df['last_order_date'] = pd.to_datetime(df['last_order_date'])
        current_date = pd.Timestamp.now()
        df['recency'] = (current_date - df['last_order_date']).dt.days
        df['recency'] = df['recency'].fillna(365)  # If no orders, set to 1 year
        
        # Parse preferred categories
        category_data = df['preferred_categories'].apply(self.parse_preferred_categories)
        df['num_preferred_categories'] = [x[0] for x in category_data]
        df['avg_category_percentage'] = [x[1] for x in category_data]
        
        # Customer lifetime (days since registration)
        df['registration_date'] = pd.to_datetime(df['registration_date'])
        df['customer_lifetime'] = (current_date - df['registration_date']).dt.days
        
        # Order frequency (orders per month)
        df['order_frequency'] = df['total_orders'] / (df['customer_lifetime'] / 30 + 1)
        
        # Fill missing values
        df['monthly_order_volume'] = df['monthly_order_volume'].fillna(0)
        df['total_orders'] = df['total_orders'].fillna(0)
        df['total_spent'] = df['total_spent'].fillna(0)
        df['avg_order_value'] = df['avg_order_value'].fillna(0)
        
        # Select features for clustering
        feature_columns = [
            'recency',               # How recently they ordered
            'total_orders',          # How frequently they order (total)
            'total_spent',           # How much they spend (monetary)
            'avg_order_value',       # Average order size
            'monthly_order_volume',  # Their stated monthly volume
            'order_frequency',       # Orders per month
            'customer_lifetime',     # How long they've been customers
            'num_preferred_categories',  # Number of preferred categories
            'avg_category_percentage'    # Average category focus
        ]
        
        return df[['customer_id', 'customer_name', 'business_type'] + feature_columns], feature_columns
    
    def perform_clustering(self, df, feature_columns, n_clusters=4):
        """Perform K-means clustering"""
        # Prepare features for clustering
        X = df[feature_columns].values
        X_scaled = self.scaler.fit_transform(X)
        
        # Perform clustering
        self.kmeans = KMeans(n_clusters=n_clusters, random_state=42, n_init=10)
        clusters = self.kmeans.fit_predict(X_scaled)
        
        df['cluster'] = clusters
        return df
    
    def analyze_segments(self, df, feature_columns):
        """Analyze and name the segments"""
        segment_names = {}
        segment_descriptions = {}
        
        for cluster in range(4):
            cluster_data = df[df['cluster'] == cluster]
            
            avg_recency = cluster_data['recency'].mean()
            avg_total_spent = cluster_data['total_spent'].mean()
            avg_frequency = cluster_data['total_orders'].mean()
            avg_order_value = cluster_data['avg_order_value'].mean()
            
            # Determine segment type based on characteristics
            if avg_total_spent > df['total_spent'].quantile(0.75) and avg_frequency > df['total_orders'].quantile(0.75):
                name = "Premium Wholesalers"
                description = "High-value, frequent buyers with large order volumes"
            elif avg_recency <= 30 and avg_frequency > df['total_orders'].median():
                name = "Active Regular Buyers"
                description = "Recently active with consistent ordering patterns"
            elif avg_recency > 90 or avg_frequency < df['total_orders'].quantile(0.25):
                name = "At-Risk/Dormant"
                description = "Low activity or haven't ordered recently, need re-engagement"
            else:
                name = "Occasional Buyers"
                description = "Moderate activity, potential for growth"
            
            segment_names[cluster] = name
            segment_descriptions[cluster] = description
            
            print(f"\n--- {name} (Cluster {cluster}) ---")
            print(f"Size: {len(cluster_data)} customers ({len(cluster_data)/len(df)*100:.1f}%)")
            print(f"Description: {description}")
            print(f"Avg Recency: {avg_recency:.1f} days")
            print(f"Avg Total Spent: ${avg_total_spent:,.2f}")
            print(f"Avg Orders: {avg_frequency:.1f}")
            print(f"Avg Order Value: ${avg_order_value:,.2f}")
            
            # Show sample customers
            print("Sample customers:")
            sample_customers = cluster_data[['customer_name', 'business_type', 'total_spent', 'total_orders']].head(3)
            for _, customer in sample_customers.iterrows():
                print(f"  - {customer['customer_name']} ({customer['business_type']}) - ${customer['total_spent']:,.0f} total, {customer['total_orders']} orders")
        
        return segment_names, segment_descriptions
    
    def visualize_segments(self, df):
        """Create visualizations of the segments"""
        fig, axes = plt.subplots(2, 2, figsize=(15, 10))
        
        # Recency vs Total Spent
        scatter = axes[0, 0].scatter(df['recency'], df['total_spent'], c=df['cluster'], cmap='viridis', alpha=0.6)
        axes[0, 0].set_xlabel('Recency (days)')
        axes[0, 0].set_ylabel('Total Spent ($)')
        axes[0, 0].set_title('Recency vs Total Spent')
        plt.colorbar(scatter, ax=axes[0, 0])
        
        # Total Orders vs Average Order Value
        scatter = axes[0, 1].scatter(df['total_orders'], df['avg_order_value'], c=df['cluster'], cmap='viridis', alpha=0.6)
        axes[0, 1].set_xlabel('Total Orders')
        axes[0, 1].set_ylabel('Average Order Value ($)')
        axes[0, 1].set_title('Frequency vs Order Value')
        plt.colorbar(scatter, ax=axes[0, 1])
        
        # Cluster distribution
        cluster_counts = df['cluster'].value_counts().sort_index()
        axes[1, 0].bar(range(len(cluster_counts)), cluster_counts.values)
        axes[1, 0].set_xlabel('Cluster')
        axes[1, 0].set_ylabel('Number of Customers')
        axes[1, 0].set_title('Customers per Segment')
        axes[1, 0].set_xticks(range(len(cluster_counts)))
        
        # Monthly order volume by cluster
        df.boxplot(column='monthly_order_volume', by='cluster', ax=axes[1, 1])
        axes[1, 1].set_title('Monthly Order Volume by Segment')
        axes[1, 1].set_xlabel('Cluster')
        
        plt.tight_layout()
        plt.show()
    
    def export_results(self, df, filename='wholesaler_segments.csv'):
        """Export results to CSV"""
        try:
            df.to_csv(filename, index=False)
            logger.info(f"Results exported to {filename}")
            return True
        except Exception as e:
            logger.error(f"Error exporting results: {e}")
            return False
    
    def run_segmentation(self):
        """Run the complete segmentation analysis"""
        print("Starting Wholesaler Segmentation Analysis...")
        print("=" * 50)
        
        # Step 1: Load data
        print("1. Loading wholesaler data...")
        df = self.get_wholesaler_data()
        if df is None:
            return None
        
        # Step 2: Prepare features
        print("2. Preparing features...")
        df, feature_columns = self.prepare_features(df)
        
        # Step 3: Perform clustering
        print("3. Performing clustering...")
        df = self.perform_clustering(df, feature_columns)
        
        # Step 4: Analyze segments
        print("4. Analyzing segments...")
        segment_names, segment_descriptions = self.analyze_segments(df, feature_columns)
        
        # Step 5: Visualize
        print("5. Creating visualizations...")
        self.visualize_segments(df)
        
        # Step 6: Export results
        print("6. Exporting results...")
        self.export_results(df)
        
        print("\nSegmentation analysis completed!")
        return df, segment_names, segment_descriptions

if __name__ == "__main__":
    segmentation = WholesalerSegmentation()
    results = segmentation.run_segmentation()