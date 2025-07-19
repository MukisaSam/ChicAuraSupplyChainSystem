"""
Advanced Recommendation System
Hybrid recommendation engine combining collaborative filtering, content-based filtering,
and demographic-based recommendations for e-commerce customers
"""

import pandas as pd
import numpy as np
from datetime import datetime, timedelta
from typing import Dict, List, Tuple, Optional, Union
import logging
import pickle
import json
import warnings
warnings.filterwarnings('ignore')

from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity, euclidean_distances
from sklearn.decomposition import TruncatedSVD, NMF
from sklearn.preprocessing import StandardScaler, MinMaxScaler
from sklearn.cluster import KMeans
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import precision_score, recall_score, f1_score
import tensorflow as tf
from tensorflow.keras.models import Model
from tensorflow.keras.layers import Input, Embedding, Flatten, Dense, Concatenate, Dropout
from tensorflow.keras.optimizers import Adam
from tensorflow.keras.regularizers import l2
from functools import lru_cache
import redis

from db_config import get_customer_data, execute_query
from enhanced_features import AdvancedFeatureEngineer

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class HybridRecommendationSystem:
    """Advanced hybrid recommendation system with multiple algorithms"""
    
    def __init__(self):
        self.feature_engineer = AdvancedFeatureEngineer()
        self.scaler = StandardScaler()
        self.min_max_scaler = MinMaxScaler()
        
        # Model components
        self.collaborative_model = None
        self.content_model = None
        self.demographic_model = None
        self.neural_cf_model = None
        self.clustering_model = KMeans(n_clusters=5, random_state=42)
        
        # Feature extractors
        self.tfidf_vectorizer = TfidfVectorizer(max_features=100, stop_words='english')
        self.svd_model = TruncatedSVD(n_components=15, random_state=42)  # Reduced to handle small datasets
        self.nmf_model = NMF(n_components=10, random_state=42)  # Reduced to handle small datasets
        
        # Hybrid weights
        self.weights = {
            'collaborative': 0.35,
            'content_based': 0.25,
            'demographic': 0.20,
            'popularity': 0.10,
            'seasonal': 0.10
        }
        
        self.models_trained = False
        self.user_item_matrix = None
        self.item_features = None
        self.customer_features = None
        
    def load_data(self) -> Tuple[pd.DataFrame, pd.DataFrame, pd.DataFrame]:
        """Load customer, product, and interaction data"""
        try:
            # Customer data
            customers = get_customer_data()
            customer_df = pd.DataFrame(customers) if customers else pd.DataFrame()
            
            # Customer order data
            order_query = """
            SELECT 
                co.customer_id,
                co.id as order_id,
                co.total_amount,
                co.created_at as order_date,
                coi.item_id,
                coi.quantity,
                coi.unit_price,
                coi.size,
                coi.color,
                i.name as item_name,
                i.category,
                i.material,
                i.base_price,
                i.type as item_type
            FROM customer_orders co
            JOIN customer_order_items coi ON co.id = coi.customer_order_id
            JOIN items i ON coi.item_id = i.id
            WHERE co.status != 'cancelled'
            ORDER BY co.created_at DESC
            """
            
            order_data = execute_query(order_query)
            order_df = pd.DataFrame(order_data) if order_data else pd.DataFrame()
            
            # Product data
            product_query = """
            SELECT 
                i.*,
                AVG(coi.unit_price) as avg_selling_price,
                COUNT(coi.id) as total_sales,
                COUNT(DISTINCT co.customer_id) as unique_customers,
                AVG(coi.quantity) as avg_quantity_per_order
            FROM items i
            LEFT JOIN customer_order_items coi ON i.id = coi.item_id
            LEFT JOIN customer_orders co ON coi.customer_order_id = co.id
            WHERE i.type = 'finished_product'
            GROUP BY i.id
            """
            
            product_data = execute_query(product_query)
            product_df = pd.DataFrame(product_data) if product_data else pd.DataFrame()
            
            logger.info(f"Loaded {len(customer_df)} customers, {len(product_df)} products, {len(order_df)} interactions")
            
            return customer_df, product_df, order_df
            
        except Exception as e:
            logger.error(f"Error loading recommendation data: {e}")
            return pd.DataFrame(), pd.DataFrame(), pd.DataFrame()
    
    def create_user_item_matrix(self, interaction_df: pd.DataFrame) -> pd.DataFrame:
        """Create user-item interaction matrix for collaborative filtering"""
        if interaction_df.empty:
            return pd.DataFrame()
        
        # Create rating matrix based on purchase frequency and recency
        interaction_df['order_date'] = pd.to_datetime(interaction_df['order_date'])
        
        # Calculate implicit ratings based on:
        # 1. Purchase frequency
        # 2. Quantity purchased
        # 3. Recency of purchase
        # 4. Price paid (higher price = higher preference)
        
        # Aggregate by customer-item
        user_item_agg = interaction_df.groupby(['customer_id', 'item_id']).agg({
            'quantity': 'sum',
            'unit_price': 'mean',
            'order_date': ['count', 'max'],
            'total_amount': 'sum'
        }).round(2)
        
        # Flatten column names
        user_item_agg.columns = ['total_quantity', 'avg_price', 'purchase_frequency', 'last_purchase', 'total_spent']
        user_item_agg.reset_index(inplace=True)
        
        # Calculate recency score (more recent = higher score)
        max_date = interaction_df['order_date'].max()
        user_item_agg['days_since_last'] = (max_date - user_item_agg['last_purchase']).dt.days
        user_item_agg['recency_score'] = 1 / (1 + user_item_agg['days_since_last'] / 30)  # Decay over months
        
        # Calculate implicit rating (0-5 scale)
        user_item_agg['frequency_score'] = np.clip(user_item_agg['purchase_frequency'] / 2, 0, 5)
        user_item_agg['quantity_score'] = np.clip(np.log1p(user_item_agg['total_quantity']), 0, 5)
        user_item_agg['price_score'] = np.clip(user_item_agg['avg_price'] / user_item_agg['avg_price'].median(), 0, 5)
        
        # Combined implicit rating
        user_item_agg['implicit_rating'] = (
            user_item_agg['frequency_score'] * 0.4 +
            user_item_agg['quantity_score'] * 0.3 +
            user_item_agg['recency_score'] * 0.2 +
            user_item_agg['price_score'] * 0.1
        )
        
        # Create user-item matrix
        user_item_matrix = user_item_agg.pivot(
            index='customer_id', 
            columns='item_id', 
            values='implicit_rating'
        ).fillna(0)
        
        self.user_item_matrix = user_item_matrix
        logger.info(f"Created user-item matrix: {user_item_matrix.shape}")
        
        return user_item_matrix
    
    def train_collaborative_filtering(self, user_item_matrix: pd.DataFrame):
        """Train collaborative filtering models"""
        if user_item_matrix.empty:
            logger.warning("Empty user-item matrix, skipping collaborative filtering")
            return
        
        try:
            # Dynamically adjust components based on available features
            n_features = min(user_item_matrix.shape)
            
            # Matrix Factorization with SVD
            if self.svd_model.n_components >= n_features:
                self.svd_model.n_components = max(1, n_features - 1)
            self.svd_model.fit(user_item_matrix.values)
            
            # Non-negative Matrix Factorization
            if self.nmf_model.n_components >= n_features:
                self.nmf_model.n_components = max(1, n_features - 1)
            self.nmf_model.fit(user_item_matrix.values)
            
            logger.info("Collaborative filtering models trained")
            
        except Exception as e:
            logger.error(f"Error training collaborative filtering: {e}")
    
    def train_neural_collaborative_filtering(self, interaction_df: pd.DataFrame):
        """Train neural collaborative filtering model"""
        if interaction_df.empty:
            return
        
        try:
            # Prepare data for neural CF
            users = interaction_df['customer_id'].unique()
            items = interaction_df['item_id'].unique()
            
            user_to_idx = {user: idx for idx, user in enumerate(users)}
            item_to_idx = {item: idx for idx, item in enumerate(items)}
            
            # Create training data
            user_input = [user_to_idx[user] for user in interaction_df['customer_id']]
            item_input = [item_to_idx[item] for item in interaction_df['item_id']]
            
            # Create implicit ratings
            ratings = []
            for _, row in interaction_df.iterrows():
                # Implicit rating based on quantity and frequency
                rating = min(5.0, row['quantity'] * 0.5 + 1.0)
                ratings.append(rating)
            
            # Create negative samples
            negative_samples = []
            for user in users[:100]:  # Limit for training efficiency
                user_items = set(interaction_df[interaction_df['customer_id'] == user]['item_id'])
                for _ in range(min(5, len(user_items))):  # 5 negative samples per user
                    negative_item = np.random.choice([item for item in items if item not in user_items])
                    negative_samples.append((user, negative_item, 0.0))
            
            # Add negative samples to training data
            for user, item, rating in negative_samples:
                user_input.append(user_to_idx[user])
                item_input.append(item_to_idx[item])
                ratings.append(rating)
            
            # Convert to numpy arrays
            user_input = np.array(user_input)
            item_input = np.array(item_input)
            ratings = np.array(ratings)
            
            # Build neural CF model
            n_users = len(users)
            n_items = len(items)
            embedding_dim = 50
            
            # User and item inputs
            user_input_layer = Input(shape=(), name='user_input')
            item_input_layer = Input(shape=(), name='item_input')
            
            # Embeddings
            user_embedding = Embedding(n_users, embedding_dim, name='user_embedding')(user_input_layer)
            item_embedding = Embedding(n_items, embedding_dim, name='item_embedding')(item_input_layer)
            
            # Flatten embeddings
            user_vec = Flatten(name='user_flatten')(user_embedding)
            item_vec = Flatten(name='item_flatten')(item_embedding)
            
            # Concatenate user and item vectors
            concat = Concatenate(name='concat')([user_vec, item_vec])
            
            # Dense layers
            dense1 = Dense(128, activation='relu', kernel_regularizer=l2(0.01))(concat)
            dropout1 = Dropout(0.3)(dense1)
            dense2 = Dense(64, activation='relu', kernel_regularizer=l2(0.01))(dropout1)
            dropout2 = Dropout(0.3)(dense2)
            output = Dense(1, activation='sigmoid', name='output')(dropout2)
            
            # Create and compile model
            model = Model(inputs=[user_input_layer, item_input_layer], outputs=output)
            model.compile(optimizer=Adam(learning_rate=0.001), loss='mse', metrics=['mae'])
            
            # Train model
            X_train = [user_input, item_input]
            y_train = ratings / 5.0  # Normalize to 0-1
            
            model.fit(X_train, y_train, epochs=10, batch_size=256, validation_split=0.2, verbose=0)
            
            self.neural_cf_model = model
            self.user_to_idx = user_to_idx
            self.item_to_idx = item_to_idx
            
            logger.info("Neural collaborative filtering model trained")
            
        except Exception as e:
            logger.error(f"Error training neural CF: {e}")
    
    def prepare_content_features(self, product_df: pd.DataFrame) -> pd.DataFrame:
        """Prepare content-based features for products"""
        if product_df.empty:
            return pd.DataFrame()
        
        # Create product feature matrix
        features_df = product_df.copy()
        
        # Text features from product descriptions
        text_features = features_df['name'].fillna('') + ' ' + features_df['description'].fillna('')
        
        if len(text_features) > 0:
            try:
                tfidf_matrix = self.tfidf_vectorizer.fit_transform(text_features)
                tfidf_df = pd.DataFrame(
                    tfidf_matrix.toarray(),
                    columns=[f'tfidf_{i}' for i in range(tfidf_matrix.shape[1])],
                    index=features_df.index
                )
                features_df = pd.concat([features_df, tfidf_df], axis=1)
            except:
                logger.warning("Could not create TF-IDF features")
        
        # Categorical features
        categorical_cols = ['category', 'material', 'type']
        for col in categorical_cols:
            if col in features_df.columns:
                features_df = pd.get_dummies(features_df, columns=[col], prefix=col)
        
        # Numerical features - convert Decimal to float first
        numerical_cols = ['base_price', 'total_sales', 'unique_customers']
        for col in numerical_cols:
            if col in features_df.columns:
                # Convert Decimal to float to avoid numpy issues
                features_df[col] = pd.to_numeric(features_df[col], errors='coerce')
                features_df[f'{col}_log'] = np.log1p(features_df[col].fillna(0))
                features_df[f'{col}_sqrt'] = np.sqrt(features_df[col].fillna(0))
        
        # Popularity features
        if 'total_sales' in features_df.columns and 'unique_customers' in features_df.columns:
            # Convert to numeric to handle Decimal types
            features_df['total_sales'] = pd.to_numeric(features_df['total_sales'], errors='coerce')
            features_df['unique_customers'] = pd.to_numeric(features_df['unique_customers'], errors='coerce')
            features_df['popularity_score'] = (
                features_df['total_sales'].fillna(0) * 0.6 +
                features_df['unique_customers'].fillna(0) * 0.4
            )
        
        # Price tier
        if 'base_price' in features_df.columns:
            features_df['price_tier'] = pd.qcut(
                features_df['base_price'].fillna(features_df['base_price'].median()),
                q=5, labels=['budget', 'low', 'medium', 'high', 'premium'],
                duplicates='drop'
            )
            features_df = pd.get_dummies(features_df, columns=['price_tier'], prefix='price_tier')
        
        self.item_features = features_df
        logger.info(f"Created content features: {features_df.shape}")
        
        return features_df
    
    def prepare_customer_features(self, customer_df: pd.DataFrame, interaction_df: pd.DataFrame) -> pd.DataFrame:
        """Prepare customer demographic and behavioral features"""
        if customer_df.empty:
            return pd.DataFrame()
        
        # Start with demographic features
        features_df = self.feature_engineer.create_customer_features(customer_df.copy())
        
        # Add behavioral features from interaction data
        if not interaction_df.empty:
            # Calculate customer behavior metrics
            customer_behavior = interaction_df.groupby('customer_id').agg({
                'order_id': 'nunique',  # Number of orders
                'item_id': 'nunique',   # Number of unique items
                'quantity': ['sum', 'mean'],  # Total and average quantity
                'unit_price': ['mean', 'std'],  # Price preferences
                'total_amount': ['sum', 'mean'],  # Spending behavior
                'order_date': ['min', 'max', 'count']  # Temporal behavior
            }).round(2)
            
            # Flatten column names
            customer_behavior.columns = [
                'total_orders', 'unique_items', 'total_quantity', 'avg_quantity',
                'avg_price_paid', 'price_std', 'total_spent', 'avg_order_value',
                'first_order', 'last_order', 'order_frequency'
            ]
            
            # Calculate additional behavioral features
            customer_behavior['days_as_customer'] = (
                pd.to_datetime(customer_behavior['last_order']) - 
                pd.to_datetime(customer_behavior['first_order'])
            ).dt.days
            
            customer_behavior['avg_days_between_orders'] = (
                customer_behavior['days_as_customer'] / customer_behavior['total_orders']
            ).fillna(0)
            
            customer_behavior['customer_lifetime_value'] = customer_behavior['total_spent']
            
            # Merge with demographic features
            features_df = features_df.merge(
                customer_behavior, 
                left_on='id', 
                right_index=True, 
                how='left'
            )
            
            # Category preferences
            category_prefs = interaction_df.groupby(['customer_id', 'category']).agg({
                'quantity': 'sum',
                'total_amount': 'sum'
            }).reset_index()
            
            # Create category preference features
            for category in category_prefs['category'].unique():
                cat_data = category_prefs[category_prefs['category'] == category]
                cat_series = cat_data.set_index('customer_id')['quantity']
                features_df[f'pref_{category}'] = features_df['id'].map(cat_series).fillna(0)
        
        # Customer segmentation using RFM
        if not interaction_df.empty:
            try:
                rfm_features = self.feature_engineer.create_rfm_features(
                    interaction_df, 
                    customer_df
                )
                
                # Debug: Check what columns are available
                logger.info(f"RFM features columns: {list(rfm_features.columns)}")
                logger.info(f"Customer features columns: {list(features_df.columns)}")
                
                # Check if customer_id exists in rfm_features
                if 'customer_id' in rfm_features.columns:
                    features_df = features_df.merge(
                        rfm_features,
                        left_on='id',
                        right_on='customer_id',
                        how='left'
                    )
                else:
                    logger.warning("customer_id not found in RFM features, skipping RFM merge")
            except Exception as e:
                logger.error(f"Error processing RFM features: {e}")
                logger.warning("Continuing without RFM features")
        
        self.customer_features = features_df
        logger.info(f"Created customer features: {features_df.shape}")
        
        return features_df
    
    def train_demographic_model(self, customer_features: pd.DataFrame, interaction_df: pd.DataFrame):
        """Train demographic-based recommendation model"""
        if customer_features.empty or interaction_df.empty:
            return
        
        try:
            # Create training data for demographic model
            # Predict category preferences based on demographics
            
            # Get category purchases by customer
            category_purchases = interaction_df.groupby(['customer_id', 'category']).size().reset_index(name='purchase_count')
            category_pivot = category_purchases.pivot(index='customer_id', columns='category', values='purchase_count').fillna(0)
            
            # Merge with customer features
            demo_data = customer_features.merge(
                category_pivot,
                left_on='id',
                right_index=True,
                how='inner'
            )
            
            if len(demo_data) < 10:  # Need minimum samples
                logger.warning("Insufficient data for demographic model")
                return
            
            # Select demographic features
            demo_features = [
                'age_group_encoded', 'gender_encoded', 'income_bracket_encoded',
                'purchase_frequency_encoded'
            ]
            available_features = [col for col in demo_features if col in demo_data.columns]
            
            if len(available_features) < 2:
                logger.warning("Insufficient demographic features")
                return
            
            X = demo_data[available_features].fillna(0)
            
            # Train separate models for each category
            self.demographic_model = {}
            categories = [col for col in category_pivot.columns]
            
            for category in categories:
                if category in demo_data.columns:
                    y = (demo_data[category] > 0).astype(int)  # Binary: bought category or not
                    
                    if y.sum() > 5:  # Need positive samples
                        try:
                            model = RandomForestClassifier(n_estimators=50, random_state=42)
                            model.fit(X, y)
                            self.demographic_model[category] = model
                        except:
                            continue
            
            logger.info(f"Trained demographic models for {len(self.demographic_model)} categories")
            
        except Exception as e:
            logger.error(f"Error training demographic model: {e}")
    
    def get_collaborative_recommendations(self, customer_id: int, n_recommendations: int = 10) -> List[int]:
        """Get recommendations using collaborative filtering"""
        if self.user_item_matrix is None or customer_id not in self.user_item_matrix.index:
            return []
        
        try:
            # Get user vector
            user_idx = self.user_item_matrix.index.get_loc(customer_id)
            user_vector = self.user_item_matrix.iloc[user_idx].values.reshape(1, -1)
            
            # Get SVD-based recommendations
            user_svd = self.svd_model.transform(user_vector)
            all_items_svd = self.svd_model.transform(self.user_item_matrix.values)
            
            # Calculate similarities
            similarities = cosine_similarity(user_svd, all_items_svd)[0]
            
            # Get items user hasn't purchased
            user_items = set(self.user_item_matrix.columns[self.user_item_matrix.iloc[user_idx] > 0])
            all_items = set(self.user_item_matrix.columns)
            candidate_items = list(all_items - user_items)
            
            if not candidate_items:
                return []
            
            # Score candidate items
            item_scores = []
            for item in candidate_items:
                if item in self.user_item_matrix.columns:
                    item_idx = self.user_item_matrix.columns.get_loc(item)
                    
                    # SVD score
                    svd_score = np.dot(user_svd[0], all_items_svd[item_idx])
                    
                    # Popularity score
                    popularity = self.user_item_matrix[item].sum()
                    
                    # Combined score
                    combined_score = svd_score * 0.8 + popularity * 0.2
                    item_scores.append((item, combined_score))
            
            # Sort and return top recommendations
            item_scores.sort(key=lambda x: x[1], reverse=True)
            return [item for item, score in item_scores[:n_recommendations]]
            
        except Exception as e:
            logger.error(f"Error in collaborative recommendations: {e}")
            return []
    
    def get_content_based_recommendations(self, customer_id: int, n_recommendations: int = 10) -> List[int]:
        """Get recommendations using content-based filtering"""
        if self.item_features is None or self.customer_features is None:
            return []
        
        try:
            # Get customer's purchase history
            customer_orders_query = """
            SELECT DISTINCT coi.item_id
            FROM customer_orders co
            JOIN customer_order_items coi ON co.id = coi.customer_order_id
            WHERE co.customer_id = %s
            """
            
            customer_items_data = execute_query(customer_orders_query, (customer_id,))
            if not customer_items_data:
                return []
            
            purchased_items = [item['item_id'] for item in customer_items_data]
            
            # Get features for purchased items
            purchased_features = self.item_features[self.item_features['id'].isin(purchased_items)]
            
            if purchased_features.empty:
                return []
            
            # Create customer preference profile (average of purchased items)
            feature_cols = [col for col in purchased_features.columns if col.startswith(('tfidf_', 'category_', 'material_', 'price_tier_'))]
            
            if not feature_cols:
                return []
            
            customer_profile = purchased_features[feature_cols].mean()
            
            # Score all items not purchased
            all_items = self.item_features[~self.item_features['id'].isin(purchased_items)]
            
            if all_items.empty:
                return []
            
            # Calculate content similarity
            similarities = cosine_similarity(
                customer_profile.values.reshape(1, -1),
                all_items[feature_cols].fillna(0).values
            )[0]
            
            # Create recommendations
            item_scores = list(zip(all_items['id'].values, similarities))
            item_scores.sort(key=lambda x: x[1], reverse=True)
            
            return [item for item, score in item_scores[:n_recommendations]]
            
        except Exception as e:
            logger.error(f"Error in content-based recommendations: {e}")
            return []
    
    def get_demographic_recommendations(self, customer_id: int, n_recommendations: int = 10) -> List[int]:
        """Get recommendations based on demographic similarity"""
        if not self.demographic_model or self.customer_features is None:
            return []
        
        try:
            # Get customer demographics
            customer_data = self.customer_features[self.customer_features['id'] == customer_id]
            if customer_data.empty:
                return []
            
            demo_features = [
                'age_group_encoded', 'gender_encoded', 'income_bracket_encoded',
                'purchase_frequency_encoded'
            ]
            available_features = [col for col in demo_features if col in customer_data.columns]
            
            if not available_features:
                return []
            
            customer_vector = customer_data[available_features].fillna(0).values[0].reshape(1, -1)
            
            # Get category preferences
            category_scores = {}
            for category, model in self.demographic_model.items():
                try:
                    prob = model.predict_proba(customer_vector)[0][1]  # Probability of liking category
                    category_scores[category] = prob
                except:
                    continue
            
            if not category_scores:
                return []
            
            # Get items from preferred categories
            recommended_items = []
            sorted_categories = sorted(category_scores.items(), key=lambda x: x[1], reverse=True)
            
            for category, score in sorted_categories[:3]:  # Top 3 categories
                category_items_query = """
                SELECT id FROM items 
                WHERE category = %s AND type = 'finished_product'
                ORDER BY stock_quantity DESC
                LIMIT %s
                """
                
                items_data = execute_query(category_items_query, (category, n_recommendations // 3))
                if items_data:
                    category_items = [item['id'] for item in items_data]
                    recommended_items.extend(category_items)
            
            return recommended_items[:n_recommendations]
            
        except Exception as e:
            logger.error(f"Error in demographic recommendations: {e}")
            return []
    
    def get_popularity_recommendations(self, n_recommendations: int = 10) -> List[int]:
        """Get popular item recommendations"""
        try:
            # Fixed query - use subquery to handle aggregate functions properly
            popular_items_query = """
            SELECT 
                item_stats.id,
                item_stats.order_count,
                item_stats.total_quantity,
                item_stats.unique_customers
            FROM (
                SELECT 
                    i.id,
                    COUNT(coi.id) as order_count,
                    SUM(coi.quantity) as total_quantity,
                    COUNT(DISTINCT co.customer_id) as unique_customers
                FROM items i
                JOIN customer_order_items coi ON i.id = coi.item_id
                JOIN customer_orders co ON coi.customer_order_id = co.id
                WHERE i.type = 'finished_product' 
                AND co.status != 'cancelled'
                AND co.created_at >= DATE_SUB(NOW(), INTERVAL 3 MONTH)
                GROUP BY i.id
            ) as item_stats
            ORDER BY (item_stats.order_count * 0.4 + item_stats.total_quantity * 0.3 + item_stats.unique_customers * 0.3) DESC
            LIMIT %s
            """
            
            popular_data = execute_query(popular_items_query, (n_recommendations,))
            if popular_data:
                return [item['id'] for item in popular_data]
            
            # Fallback to newest items if no popular items found
            fallback_query = """
            SELECT id FROM items 
            WHERE type = 'finished_product' 
            AND stock_quantity > 0
            ORDER BY created_at DESC
            LIMIT %s
            """
            
            fallback_data = execute_query(fallback_query, (n_recommendations,))
            if fallback_data:
                return [item['id'] for item in fallback_data]
            
            return []
            
        except Exception as e:
            logger.error(f"Error getting popularity recommendations: {e}")
            return []
    
    def get_seasonal_recommendations(self, n_recommendations: int = 10) -> List[int]:
        """Get seasonal recommendations based on current time"""
        try:
            current_month = datetime.now().month
            
            # Define seasonal categories
            seasonal_categories = {
                'winter': ['coats', 'sweaters', 'boots'],  # Dec, Jan, Feb
                'spring': ['dresses', 'light_jackets', 'sneakers'],  # Mar, Apr, May
                'summer': ['shorts', 't-shirts', 'sandals'],  # Jun, Jul, Aug
                'fall': ['jackets', 'jeans', 'boots']  # Sep, Oct, Nov
            }
            
            if current_month in [12, 1, 2]:
                season = 'winter'
            elif current_month in [3, 4, 5]:
                season = 'spring'
            elif current_month in [6, 7, 8]:
                season = 'summer'
            else:
                season = 'fall'
            
            categories = seasonal_categories.get(season, [])
            
            if not categories:
                return []
            
            # Get trending items in seasonal categories
            seasonal_query = """
            SELECT 
                i.id,
                COUNT(coi.id) as recent_orders
            FROM items i
            JOIN customer_order_items coi ON i.id = coi.item_id
            JOIN customer_orders co ON coi.customer_order_id = co.id
            WHERE i.type = 'finished_product' 
                AND co.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
                AND (i.category LIKE %s OR i.name LIKE %s OR i.description LIKE %s)
            GROUP BY i.id
            ORDER BY recent_orders DESC
            LIMIT %s
            """
            
            seasonal_items = []
            for category in categories:
                pattern = f"%{category}%"
                items_data = execute_query(seasonal_query, (pattern, pattern, pattern, n_recommendations // len(categories)))
                if items_data:
                    seasonal_items.extend([item['id'] for item in items_data])
            
            return seasonal_items[:n_recommendations]
            
        except Exception as e:
            logger.error(f"Error getting seasonal recommendations: {e}")
            return []
    
    def get_hybrid_recommendations(self, customer_id: int, n_recommendations: int = 10) -> List[Dict]:
        """Get hybrid recommendations combining all methods"""
        try:
            # Get recommendations from each method
            collaborative_recs = self.get_collaborative_recommendations(customer_id, n_recommendations)
            content_recs = self.get_content_based_recommendations(customer_id, n_recommendations)
            demographic_recs = self.get_demographic_recommendations(customer_id, n_recommendations)
            popularity_recs = self.get_popularity_recommendations(n_recommendations)
            seasonal_recs = self.get_seasonal_recommendations(n_recommendations)
            
            # Combine and score recommendations
            all_recs = {}
            
            # Add scores from each method
            for item in collaborative_recs:
                all_recs[item] = all_recs.get(item, 0) + self.weights['collaborative']
            
            for item in content_recs:
                all_recs[item] = all_recs.get(item, 0) + self.weights['content_based']
            
            for item in demographic_recs:
                all_recs[item] = all_recs.get(item, 0) + self.weights['demographic']
            
            for item in popularity_recs:
                all_recs[item] = all_recs.get(item, 0) + self.weights['popularity']
            
            for item in seasonal_recs:
                all_recs[item] = all_recs.get(item, 0) + self.weights['seasonal']
            
            # Sort by combined score
            sorted_recs = sorted(all_recs.items(), key=lambda x: x[1], reverse=True)
            
            # Get item details for top recommendations
            top_items = [item_id for item_id, score in sorted_recs[:n_recommendations]]
            
            if not top_items:
                return []
            
            # Get detailed item information
            items_query = """
            SELECT 
                i.id,
                i.name,
                i.description,
                i.category,
                i.material,
                i.base_price,
                i.stock_quantity,
                COALESCE(AVG(coi.unit_price), i.base_price) as avg_selling_price,
                COUNT(coi.id) as total_sales
            FROM items i
            LEFT JOIN customer_order_items coi ON i.id = coi.item_id
            WHERE i.id IN ({})
            GROUP BY i.id
            """.format(','.join(['%s'] * len(top_items)))
            
            items_data = execute_query(items_query, tuple(top_items))
            
            if not items_data:
                return []
            
            # Create final recommendations with details and scores
            recommendations = []
            for item_data in items_data:
                item_id = item_data['id']
                recommendation = {
                    'item_id': item_id,
                    'name': item_data['name'],
                    'description': item_data['description'],
                    'category': item_data['category'],
                    'material': item_data['material'],
                    'price': float(item_data['avg_selling_price'] or item_data['base_price']),
                    'stock_quantity': item_data['stock_quantity'],
                    'popularity_score': item_data['total_sales'] or 0,
                    'recommendation_score': all_recs.get(item_id, 0),
                    'recommendation_reasons': []
                }
                
                # Add reasons for recommendation
                if item_id in collaborative_recs:
                    recommendation['recommendation_reasons'].append('Similar customers liked this')
                if item_id in content_recs:
                    recommendation['recommendation_reasons'].append('Similar to your previous purchases')
                if item_id in demographic_recs:
                    recommendation['recommendation_reasons'].append('Popular with similar customers')
                if item_id in popularity_recs:
                    recommendation['recommendation_reasons'].append('Trending product')
                if item_id in seasonal_recs:
                    recommendation['recommendation_reasons'].append('Perfect for this season')
                
                recommendations.append(recommendation)
            
            # Sort final recommendations by score
            recommendations.sort(key=lambda x: x['recommendation_score'], reverse=True)
            
            return recommendations[:n_recommendations]
            
        except Exception as e:
            logger.error(f"Error generating hybrid recommendations: {e}")
            return []
    
    def train_models(self):
        """Train all recommendation models"""
        logger.info("Training recommendation models...")
        
        # Load data
        customer_df, product_df, interaction_df = self.load_data()
        
        if interaction_df.empty:
            logger.warning("No interaction data available for training")
            return False
        
        # Create user-item matrix
        user_item_matrix = self.create_user_item_matrix(interaction_df)
        
        # Train collaborative filtering
        if not user_item_matrix.empty:
            self.train_collaborative_filtering(user_item_matrix)
            self.train_neural_collaborative_filtering(interaction_df)
        
        # Prepare features
        if not product_df.empty:
            self.prepare_content_features(product_df)
        
        if not customer_df.empty:
            customer_features = self.prepare_customer_features(customer_df, interaction_df)
            self.train_demographic_model(customer_features, interaction_df)
        
        self.models_trained = True
        logger.info("Recommendation models trained successfully")
        
        return True
    
    def save_models(self, filepath: str = 'recommendation_models/'):
        """Save trained models"""
        import os
        os.makedirs(filepath, exist_ok=True)
        
        models_to_save = {
            'svd_model.pkl': self.svd_model,
            'nmf_model.pkl': self.nmf_model,
            'tfidf_vectorizer.pkl': self.tfidf_vectorizer,
            'scaler.pkl': self.scaler,
            'clustering_model.pkl': self.clustering_model
        }
        
        for filename, model in models_to_save.items():
            try:
                with open(os.path.join(filepath, filename), 'wb') as f:
                    pickle.dump(model, f)
            except Exception as e:
                logger.warning(f"Could not save {filename}: {e}")
        
        # Save demographic models
        if self.demographic_model:
            with open(os.path.join(filepath, 'demographic_model.pkl'), 'wb') as f:
                pickle.dump(self.demographic_model, f)
        
        # Save neural model
        if self.neural_cf_model:
            try:
                self.neural_cf_model.save(os.path.join(filepath, 'neural_cf_model.h5'))
            except Exception as e:
                logger.warning(f"Could not save neural model: {e}")
        
        # Save configuration
        config = {
            'weights': self.weights,
            'models_trained': self.models_trained
        }
        
        with open(os.path.join(filepath, 'config.json'), 'w') as f:
            json.dump(config, f, indent=2)
        
        logger.info(f"Models saved to {filepath}")
    
    def should_retrain(self) -> bool:
        """Check if models need retraining based on new data"""
        # Check last training time, new interactions, etc.
        pass

    def incremental_train(self, new_interactions: pd.DataFrame):
        """Update models with new interaction data"""
        pass

    def get_recommendations_with_variant(self, customer_id: int, variant: str = 'default'):
        """Support A/B testing different recommendation strategies"""
        if variant == 'content_heavy':
            self.weights['content_based'] = 0.5
            self.weights['collaborative'] = 0.3
        elif variant == 'popularity_heavy':
            self.weights['popularity'] = 0.4
            self.weights['collaborative'] = 0.4
    
        return self.get_hybrid_recommendations(customer_id)

    def add_diversity_filter(self, recommendations: List[Dict], diversity_factor: float = 0.3):
        """Ensure recommendations are diverse across categories"""
        diverse_recs = []
        seen_categories = set()
        
        for rec in recommendations:
            if rec['category'] not in seen_categories or len(diverse_recs) < 3:
                diverse_recs.append(rec)
                seen_categories.add(rec['category'])
            elif len(diverse_recs) < len(recommendations):
                # Add remaining items if we have space
                diverse_recs.append(rec)
        
        return diverse_recs

    def calculate_enhanced_score(self, base_score: float, item_data: Dict) -> float:
        """Enhanced scoring considering business rules"""
        score = base_score
        
        # Boost high-margin items
        if item_data.get('profit_margin', 0) > 0.3:
            score *= 1.1
        
        # Boost items with good ratings
        if item_data.get('avg_rating', 0) > 4.0:
            score *= 1.05
        
        # Reduce score for low stock
        if item_data.get('stock_quantity', 0) < 10:
            score *= 0.9
        
        return score


class CachedRecommendationSystem(HybridRecommendationSystem):
    def __init__(self):
        super().__init__()
        self.redis_client = redis.Redis(host='localhost', port=6379, db=0)
    
    @lru_cache(maxsize=1000)
    def get_cached_recommendations(self, customer_id: int, n_recommendations: int):
        """Cache recommendations for frequent requests"""
        cache_key = f"recommendations:{customer_id}:{n_recommendations}"
        
        cached = self.redis_client.get(cache_key)
        if cached:
            return json.loads(cached)
        
        recommendations = self.get_hybrid_recommendations(customer_id, n_recommendations)
        
        # Cache for 1 hour
        self.redis_client.setex(cache_key, 3600, json.dumps(recommendations, default=str))
        
        return recommendations

    def get_batch_recommendations(self, customer_ids: List[int], n_recommendations: int = 10):
        """Generate recommendations for multiple customers efficiently"""
        batch_recommendations = {}
        
        # Pre-load common data
        self._preload_batch_data()
        
        for customer_id in customer_ids:
            try:
                recommendations = self.get_hybrid_recommendations(customer_id, n_recommendations)
                batch_recommendations[customer_id] = recommendations
            except Exception as e:
                logger.error(f"Failed to generate recommendations for customer {customer_id}: {e}")
                batch_recommendations[customer_id] = []
        
        return batch_recommendations


def main():
    """Main function to train and test recommendation system"""
    recommender = HybridRecommendationSystem()
    
    try:
        # Train models
        success = recommender.train_models()
        
        if success:
            # Save models
            recommender.save_models()
            
            # Test with a sample customer
            test_customer_id = 1  # Replace with actual customer ID
            recommendations = recommender.get_hybrid_recommendations(test_customer_id, 10)
            
            print(f"\n=== RECOMMENDATIONS FOR CUSTOMER {test_customer_id} ===")
            for i, rec in enumerate(recommendations, 1):
                print(f"{i}. {rec['name']} (${rec['price']:.2f})")
                print(f"   Category: {rec['category']}")
                print(f"   Reasons: {', '.join(rec['recommendation_reasons'])}")
                print(f"   Score: {rec['recommendation_score']:.3f}")
                print()
            
            # Save sample recommendations (replace existing file)
            output_file = '../SupplyChain/public/recommendations_sample.json'
            
            # Add metadata to the recommendations
            output_data = {
                'last_updated': datetime.now().isoformat(),
                'customer_id': test_customer_id,
                'total_recommendations': len(recommendations),
                'recommendations': recommendations
            }
            
            with open(output_file, 'w') as f:
                json.dump(output_data, f, indent=2, default=str)
            
            print(f"Sample recommendations saved to {output_file}")
        
        else:
            print("Model training failed")
    
    except Exception as e:
        logger.error(f"Recommendation system failed: {e}")
        raise


if __name__ == "__main__":
    main()