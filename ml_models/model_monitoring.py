"""
Model Monitoring and Retraining System
Automated monitoring for model performance drift and retraining triggers
"""

import pandas as pd
import numpy as np
from datetime import datetime, timedelta
from typing import Dict, List, Tuple, Optional, Union
import logging
import json
import pickle
import os
import schedule
import time
import threading
from pathlib import Path

from sklearn.metrics import mean_absolute_percentage_error, mean_squared_error, r2_score
import matplotlib.pyplot as plt
import seaborn as sns

# Import ML modules
from demand_model import main as train_demand_model
from recommendation_system import HybridRecommendationSystem
from supplier_performance import SupplierPerformanceAnalyzer
from db_config import get_demand_data, get_customer_data, get_supplier_performance_data, execute_query

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class ModelMonitor:
    """Monitor ML model performance and trigger retraining when needed"""
    
    def __init__(self, config_path: str = "monitoring_config.json"):
        self.config_path = config_path
        self.config = self._load_config()
        
        # Performance thresholds for triggering retraining
        self.performance_thresholds = {
            'demand_model': {
                'mape_threshold': 0.25,  # 25% MAPE threshold
                'r2_threshold': 0.6,     # R² threshold
                'drift_threshold': 0.15,  # 15% performance degradation
                'min_days_between_retraining': 7
            },
            'recommendation_system': {
                'precision_threshold': 0.25,
                'recall_threshold': 0.20,
                'drift_threshold': 0.10,
                'min_days_between_retraining': 14
            },
            'supplier_analyzer': {
                'accuracy_threshold': 0.80,
                'drift_threshold': 0.12,
                'min_days_between_retraining': 30
            }
        }
        
        # Performance history
        self.performance_history = {
            'demand_model': [],
            'recommendation_system': [],
            'supplier_analyzer': []
        }
        
        # Last retraining dates
        self.last_retrained = {
            'demand_model': None,
            'recommendation_system': None,
            'supplier_analyzer': None
        }
        
        # Load historical data
        self._load_performance_history()
        
        # Model instances
        self.demand_model = None
        self.recommendation_system = None
        self.supplier_analyzer = None
        
        # Monitoring status
        self.monitoring_active = False
        self.monitoring_thread = None
    
    def _load_config(self) -> Dict:
        """Load monitoring configuration"""
        default_config = {
            "monitoring_enabled": True,
            "check_interval_hours": 24,
            "alert_email": None,
            "auto_retrain": True,
            "backup_models": True,
            "performance_window_days": 30,
            "min_data_points": 100
        }
        
        try:
            if os.path.exists(self.config_path):
                with open(self.config_path, 'r') as f:
                    config = json.load(f)
                # Merge with defaults
                default_config.update(config)
            else:
                # Save default config
                with open(self.config_path, 'w') as f:
                    json.dump(default_config, f, indent=2)
        except Exception as e:
            logger.error(f"Failed to load config: {e}")
        
        return default_config
    
    def _save_config(self):
        """Save monitoring configuration"""
        try:
            with open(self.config_path, 'w') as f:
                json.dump(self.config, f, indent=2)
        except Exception as e:
            logger.error(f"Failed to save config: {e}")
    
    def _load_performance_history(self):
        """Load performance history from disk"""
        history_file = "performance_history.pkl"
        
        try:
            if os.path.exists(history_file):
                with open(history_file, 'rb') as f:
                    data = pickle.load(f)
                    self.performance_history = data.get('performance_history', self.performance_history)
                    self.last_retrained = data.get('last_retrained', self.last_retrained)
                logger.info("Performance history loaded")
        except Exception as e:
            logger.error(f"Failed to load performance history: {e}")
    
    def _save_performance_history(self):
        """Save performance history to disk"""
        history_file = "performance_history.pkl"
        
        try:
            data = {
                'performance_history': self.performance_history,
                'last_retrained': self.last_retrained
            }
            with open(history_file, 'wb') as f:
                pickle.dump(data, f)
        except Exception as e:
            logger.error(f"Failed to save performance history: {e}")
    
    def _backup_model(self, model_type: str):
        """Create backup of current model before retraining"""
        if not self.config.get('backup_models', True):
            return
        
        try:
            backup_dir = f"model_backups/{model_type}"
            os.makedirs(backup_dir, exist_ok=True)
            
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            
            if model_type == 'demand_model':
                # Backup demand model
                if os.path.exists('demand_model.pkl'):
                    backup_path = f"{backup_dir}/demand_model_{timestamp}.pkl"
                    os.rename('demand_model.pkl', backup_path)
                    logger.info(f"Demand model backed up to {backup_path}")
            
            elif model_type == 'recommendation_system':
                # Backup recommendation models
                rec_dir = "recommendation_models"
                if os.path.exists(rec_dir):
                    backup_path = f"{backup_dir}/recommendation_models_{timestamp}"
                    os.rename(rec_dir, backup_path)
                    logger.info(f"Recommendation models backed up to {backup_path}")
            
            elif model_type == 'supplier_analyzer':
                # Backup supplier models
                sup_dir = "supplier_models"
                if os.path.exists(sup_dir):
                    backup_path = f"{backup_dir}/supplier_models_{timestamp}"
                    os.rename(sup_dir, backup_path)
                    logger.info(f"Supplier models backed up to {backup_path}")
                    
        except Exception as e:
            logger.error(f"Model backup failed for {model_type}: {e}")
    
    def check_demand_model_performance(self) -> Dict:
        """Check demand forecasting model performance"""
        try:
            logger.info("Checking demand model performance...")
            
            # Get recent data for evaluation
            end_date = datetime.now()
            start_date = end_date - timedelta(days=self.config.get('performance_window_days', 30))
            
            recent_data = get_demand_data(
                start_date=start_date.strftime('%Y-%m-%d'),
                end_date=end_date.strftime('%Y-%m-%d')
            )
            
            if not recent_data or len(recent_data) < self.config.get('min_data_points', 100):
                logger.warning("Insufficient recent data for demand model evaluation")
                return {'status': 'insufficient_data', 'metrics': {}}
            
            # Load current model
            try:
                import joblib
                self.demand_model = joblib.load('demand_model.pkl')
            except:
                logger.warning("Demand model not found")
                return {'status': 'model_not_found', 'metrics': {}}
            
            # Evaluate model on recent data
            df = pd.DataFrame(recent_data)
            
            # Calculate performance metrics for each product-location combination
            all_metrics = []
            
            for (product, location), group in df.groupby(['product_name', 'location']):
                if len(group) < 10:  # Need minimum data points
                    continue
                
                try:
                    # Prepare data
                    group = group.sort_values('sales_date')
                    
                    # Split into eval periods
                    split_idx = int(len(group) * 0.7)
                    train_data = group.iloc[:split_idx]
                    test_data = group.iloc[split_idx:]
                    
                    if len(test_data) < 3:
                        continue
                    
                    # Make predictions
                    predictions = []
                    actuals = []
                    
                    for _, row in test_data.iterrows():
                        pred_input = {
                            'product_name': row['product_name'],
                            'location': row['location'],
                            'unit_price': row['unit_price'],
                            'sales_date': row['sales_date']
                        }
                        
                        try:
                            pred = self.demand_model.predict(pd.DataFrame([pred_input]))[0]
                            predictions.append(pred)
                            actuals.append(row['demand'])
                        except:
                            continue
                    
                    if len(predictions) >= 3:
                        # Calculate metrics
                        mape = mean_absolute_percentage_error(actuals, predictions)
                        rmse = np.sqrt(mean_squared_error(actuals, predictions))
                        r2 = r2_score(actuals, predictions) if len(actuals) > 1 else 0
                        
                        all_metrics.append({
                            'product_location': f"{product}_{location}",
                            'mape': mape,
                            'rmse': rmse,
                            'r2': r2,
                            'sample_size': len(predictions)
                        })
                
                except Exception as e:
                    logger.warning(f"Evaluation failed for {product}_{location}: {e}")
                    continue
            
            if not all_metrics:
                return {'status': 'evaluation_failed', 'metrics': {}}
            
            # Aggregate metrics
            avg_metrics = {
                'mape': np.mean([m['mape'] for m in all_metrics]),
                'rmse': np.mean([m['rmse'] for m in all_metrics]),
                'r2': np.mean([m['r2'] for m in all_metrics]),
                'models_evaluated': len(all_metrics),
                'evaluation_date': datetime.now().isoformat()
            }
            
            # Record performance
            self.performance_history['demand_model'].append({
                'timestamp': datetime.now().isoformat(),
                'metrics': avg_metrics
            })
            
            # Check if retraining needed
            thresholds = self.performance_thresholds['demand_model']
            needs_retraining = (
                avg_metrics['mape'] > thresholds['mape_threshold'] or
                avg_metrics['r2'] < thresholds['r2_threshold']
            )
            
            # Check time since last retraining
            last_retrain = self.last_retrained.get('demand_model')
            if last_retrain:
                days_since_retrain = (datetime.now() - datetime.fromisoformat(last_retrain)).days
                if days_since_retrain < thresholds['min_days_between_retraining']:
                    needs_retraining = False
            
            result = {
                'status': 'evaluated',
                'metrics': avg_metrics,
                'needs_retraining': needs_retraining,
                'threshold_violations': []
            }
            
            if avg_metrics['mape'] > thresholds['mape_threshold']:
                result['threshold_violations'].append(f"MAPE {avg_metrics['mape']:.3f} > {thresholds['mape_threshold']}")
            
            if avg_metrics['r2'] < thresholds['r2_threshold']:
                result['threshold_violations'].append(f"R² {avg_metrics['r2']:.3f} < {thresholds['r2_threshold']}")
            
            logger.info(f"Demand model evaluation: MAPE={avg_metrics['mape']:.3f}, R²={avg_metrics['r2']:.3f}")
            
            return result
            
        except Exception as e:
            logger.error(f"Demand model performance check failed: {e}")
            return {'status': 'error', 'error': str(e), 'metrics': {}}
    
    def check_recommendation_system_performance(self) -> Dict:
        """Check recommendation system performance"""
        try:
            logger.info("Checking recommendation system performance...")
            
            # Initialize recommendation system if needed
            if not self.recommendation_system:
                self.recommendation_system = HybridRecommendationSystem()
            
            # Check if models are trained
            if not self.recommendation_system.models_trained:
                return {'status': 'models_not_trained', 'metrics': {}}
            
            # Get recent customer orders for evaluation
            end_date = datetime.now()
            start_date = end_date - timedelta(days=self.config.get('performance_window_days', 30))
            
            eval_query = """
            SELECT 
                co.customer_id,
                coi.item_id,
                co.created_at
            FROM customer_orders co
            JOIN customer_order_items coi ON co.id = coi.customer_order_id
            WHERE co.created_at BETWEEN %s AND %s
            AND co.status != 'cancelled'
            ORDER BY co.created_at DESC
            """
            
            recent_orders = execute_query(eval_query, (start_date, end_date))
            
            if not recent_orders or len(recent_orders) < 50:
                logger.warning("Insufficient recent order data for recommendation evaluation")
                return {'status': 'insufficient_data', 'metrics': {}}
            
            # Evaluate recommendation quality
            df = pd.DataFrame(recent_orders)
            
            # Sample customers for evaluation
            customers = df['customer_id'].unique()
            eval_customers = np.random.choice(customers, min(50, len(customers)), replace=False)
            
            precision_scores = []
            recall_scores = []
            
            for customer_id in eval_customers:
                try:
                    # Get customer's actual purchases
                    customer_items = set(df[df['customer_id'] == customer_id]['item_id'].values)
                    
                    if len(customer_items) < 2:  # Need at least 2 purchases
                        continue
                    
                    # Get recommendations
                    recommendations = self.recommendation_system.get_hybrid_recommendations(
                        customer_id, n_recommendations=10
                    )
                    
                    if not recommendations:
                        continue
                    
                    recommended_items = set([rec['item_id'] for rec in recommendations])
                    
                    # Calculate precision and recall
                    intersection = customer_items.intersection(recommended_items)
                    
                    precision = len(intersection) / len(recommended_items) if recommended_items else 0
                    recall = len(intersection) / len(customer_items) if customer_items else 0
                    
                    precision_scores.append(precision)
                    recall_scores.append(recall)
                    
                except Exception as e:
                    logger.warning(f"Evaluation failed for customer {customer_id}: {e}")
                    continue
            
            if not precision_scores:
                return {'status': 'evaluation_failed', 'metrics': {}}
            
            # Calculate average metrics
            avg_metrics = {
                'precision': np.mean(precision_scores),
                'recall': np.mean(recall_scores),
                'f1_score': 2 * np.mean(precision_scores) * np.mean(recall_scores) / (np.mean(precision_scores) + np.mean(recall_scores)) if (np.mean(precision_scores) + np.mean(recall_scores)) > 0 else 0,
                'customers_evaluated': len(precision_scores),
                'evaluation_date': datetime.now().isoformat()
            }
            
            # Record performance
            self.performance_history['recommendation_system'].append({
                'timestamp': datetime.now().isoformat(),
                'metrics': avg_metrics
            })
            
            # Check if retraining needed
            thresholds = self.performance_thresholds['recommendation_system']
            needs_retraining = (
                avg_metrics['precision'] < thresholds['precision_threshold'] or
                avg_metrics['recall'] < thresholds['recall_threshold']
            )
            
            # Check time since last retraining
            last_retrain = self.last_retrained.get('recommendation_system')
            if last_retrain:
                days_since_retrain = (datetime.now() - datetime.fromisoformat(last_retrain)).days
                if days_since_retrain < thresholds['min_days_between_retraining']:
                    needs_retraining = False
            
            result = {
                'status': 'evaluated',
                'metrics': avg_metrics,
                'needs_retraining': needs_retraining,
                'threshold_violations': []
            }
            
            if avg_metrics['precision'] < thresholds['precision_threshold']:
                result['threshold_violations'].append(f"Precision {avg_metrics['precision']:.3f} < {thresholds['precision_threshold']}")
            
            if avg_metrics['recall'] < thresholds['recall_threshold']:
                result['threshold_violations'].append(f"Recall {avg_metrics['recall']:.3f} < {thresholds['recall_threshold']}")
            
            logger.info(f"Recommendation system evaluation: Precision={avg_metrics['precision']:.3f}, Recall={avg_metrics['recall']:.3f}")
            
            return result
            
        except Exception as e:
            logger.error(f"Recommendation system performance check failed: {e}")
            return {'status': 'error', 'error': str(e), 'metrics': {}}
    
    def check_supplier_analyzer_performance(self) -> Dict:
        """Check supplier performance analyzer"""
        try:
            logger.info("Checking supplier analyzer performance...")
            
            # Initialize supplier analyzer if needed
            if not self.supplier_analyzer:
                self.supplier_analyzer = SupplierPerformanceAnalyzer()
            
            # Run basic analysis to check functionality
            try:
                performance_df, insights = self.supplier_analyzer.run_full_analysis()
                
                if performance_df.empty:
                    return {'status': 'no_data', 'metrics': {}}
                
                # Basic health metrics
                metrics = {
                    'suppliers_analyzed': len(performance_df),
                    'avg_performance_score': performance_df['overall_performance_score'].mean(),
                    'excellent_suppliers': len(performance_df[performance_df['overall_performance_score'] >= 85]),
                    'poor_suppliers': len(performance_df[performance_df['overall_performance_score'] < 40]),
                    'anomalies_detected': performance_df['is_anomaly'].sum() if 'is_anomaly' in performance_df.columns else 0,
                    'evaluation_date': datetime.now().isoformat()
                }
                
                # Record performance
                self.performance_history['supplier_analyzer'].append({
                    'timestamp': datetime.now().isoformat(),
                    'metrics': metrics
                })
                
                # Simple health check - no specific retraining criteria for now
                needs_retraining = False
                
                result = {
                    'status': 'evaluated',
                    'metrics': metrics,
                    'needs_retraining': needs_retraining,
                    'threshold_violations': []
                }
                
                logger.info(f"Supplier analyzer evaluation: {metrics['suppliers_analyzed']} suppliers analyzed")
                
                return result
                
            except Exception as e:
                return {'status': 'analysis_failed', 'error': str(e), 'metrics': {}}
            
        except Exception as e:
            logger.error(f"Supplier analyzer performance check failed: {e}")
            return {'status': 'error', 'error': str(e), 'metrics': {}}
    
    def retrain_model(self, model_type: str) -> bool:
        """Retrain a specific model"""
        try:
            logger.info(f"Starting retraining for {model_type}")
            
            # Backup current model
            self._backup_model(model_type)
            
            if model_type == 'demand_model':
                # Retrain demand forecasting model
                train_demand_model()
                self.last_retrained['demand_model'] = datetime.now().isoformat()
                
            elif model_type == 'recommendation_system':
                # Retrain recommendation system
                if not self.recommendation_system:
                    self.recommendation_system = HybridRecommendationSystem()
                
                success = self.recommendation_system.train_models()
                if success:
                    self.recommendation_system.save_models()
                    self.last_retrained['recommendation_system'] = datetime.now().isoformat()
                else:
                    logger.error("Recommendation system retraining failed")
                    return False
                
            elif model_type == 'supplier_analyzer':
                # Retrain supplier analyzer
                if not self.supplier_analyzer:
                    self.supplier_analyzer = SupplierPerformanceAnalyzer()
                
                performance_df, insights = self.supplier_analyzer.run_full_analysis()
                if not performance_df.empty:
                    self.supplier_analyzer.save_models()
                    self.last_retrained['supplier_analyzer'] = datetime.now().isoformat()
                else:
                    logger.error("Supplier analyzer retraining failed")
                    return False
            
            else:
                logger.error(f"Unknown model type: {model_type}")
                return False
            
            # Save updated history
            self._save_performance_history()
            
            logger.info(f"Retraining completed successfully for {model_type}")
            return True
            
        except Exception as e:
            logger.error(f"Retraining failed for {model_type}: {e}")
            return False
    
    def run_monitoring_cycle(self):
        """Run a complete monitoring cycle"""
        logger.info("Starting monitoring cycle...")
        
        results = {}
        
        # Check each model
        models_to_check = ['demand_model', 'recommendation_system', 'supplier_analyzer']
        
        for model_type in models_to_check:
            try:
                if model_type == 'demand_model':
                    result = self.check_demand_model_performance()
                elif model_type == 'recommendation_system':
                    result = self.check_recommendation_system_performance()
                elif model_type == 'supplier_analyzer':
                    result = self.check_supplier_analyzer_performance()
                
                results[model_type] = result
                
                # Auto-retrain if needed and enabled
                if (result.get('needs_retraining', False) and 
                    self.config.get('auto_retrain', True)):
                    
                    logger.info(f"Auto-retraining triggered for {model_type}")
                    retrain_success = self.retrain_model(model_type)
                    results[model_type]['retrain_attempted'] = True
                    results[model_type]['retrain_success'] = retrain_success
                
            except Exception as e:
                logger.error(f"Monitoring failed for {model_type}: {e}")
                results[model_type] = {'status': 'error', 'error': str(e)}
        
        # Save monitoring results
        self._save_monitoring_results(results)
        
        # Save performance history
        self._save_performance_history()
        
        logger.info("Monitoring cycle completed")
        
        return results
    
    def _save_monitoring_results(self, results: Dict):
        """Save monitoring results to file"""
        try:
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            results_file = f"monitoring_results_{timestamp}.json"
            
            with open(results_file, 'w') as f:
                json.dump(results, f, indent=2, default=str)
                
            # Also save latest results
            with open("latest_monitoring_results.json", 'w') as f:
                json.dump(results, f, indent=2, default=str)
                
            logger.info(f"Monitoring results saved to {results_file}")
            
        except Exception as e:
            logger.error(f"Failed to save monitoring results: {e}")
    
    def start_monitoring(self):
        """Start automated monitoring"""
        if self.monitoring_active:
            logger.warning("Monitoring is already active")
            return
        
        if not self.config.get('monitoring_enabled', True):
            logger.info("Monitoring is disabled in configuration")
            return
        
        def monitoring_loop():
            while self.monitoring_active:
                try:
                    self.run_monitoring_cycle()
                    
                    # Wait for next cycle
                    interval_hours = self.config.get('check_interval_hours', 24)
                    time.sleep(interval_hours * 3600)  # Convert to seconds
                    
                except Exception as e:
                    logger.error(f"Monitoring loop error: {e}")
                    time.sleep(300)  # Wait 5 minutes before retrying
        
        self.monitoring_active = True
        self.monitoring_thread = threading.Thread(target=monitoring_loop, daemon=True)
        self.monitoring_thread.start()
        
        logger.info(f"Model monitoring started (check interval: {self.config.get('check_interval_hours', 24)} hours)")
    
    def stop_monitoring(self):
        """Stop automated monitoring"""
        self.monitoring_active = False
        if self.monitoring_thread:
            self.monitoring_thread.join(timeout=5)
        
        logger.info("Model monitoring stopped")
    
    def get_monitoring_status(self) -> Dict:
        """Get current monitoring status"""
        return {
            'monitoring_active': self.monitoring_active,
            'config': self.config,
            'last_retrained': self.last_retrained,
            'performance_history_length': {
                model: len(history) for model, history in self.performance_history.items()
            }
        }
    
    def generate_monitoring_report(self) -> Dict:
        """Generate comprehensive monitoring report"""
        report = {
            'generated_at': datetime.now().isoformat(),
            'monitoring_status': self.get_monitoring_status(),
            'model_performance': {}
        }
        
        # Get latest performance for each model
        for model_type in ['demand_model', 'recommendation_system', 'supplier_analyzer']:
            history = self.performance_history.get(model_type, [])
            
            if history:
                latest = history[-1]
                report['model_performance'][model_type] = {
                    'latest_metrics': latest['metrics'],
                    'timestamp': latest['timestamp'],
                    'history_length': len(history)
                }
                
                # Performance trend (last 5 evaluations)
                if len(history) >= 2:
                    recent_history = history[-5:]
                    if model_type == 'demand_model':
                        mape_trend = [h['metrics']['mape'] for h in recent_history]
                        report['model_performance'][model_type]['mape_trend'] = mape_trend
                    elif model_type == 'recommendation_system':
                        precision_trend = [h['metrics']['precision'] for h in recent_history]
                        report['model_performance'][model_type]['precision_trend'] = precision_trend
            else:
                report['model_performance'][model_type] = {'status': 'no_history'}
        
        return report


def main():
    """Main function to run model monitoring"""
    monitor = ModelMonitor()
    
    try:
        # Run initial monitoring cycle
        logger.info("Running initial monitoring cycle...")
        results = monitor.run_monitoring_cycle()
        
        # Print results
        print("\n=== MODEL MONITORING RESULTS ===")
        for model_type, result in results.items():
            print(f"\n{model_type.upper()}:")
            print(f"  Status: {result.get('status', 'unknown')}")
            
            metrics = result.get('metrics', {})
            if metrics:
                for metric, value in metrics.items():
                    if isinstance(value, (int, float)):
                        print(f"  {metric}: {value:.3f}")
                    else:
                        print(f"  {metric}: {value}")
            
            if result.get('needs_retraining'):
                print(f"  ⚠️  RETRAINING NEEDED")
                if result.get('threshold_violations'):
                    for violation in result['threshold_violations']:
                        print(f"    - {violation}")
            
            if result.get('retrain_attempted'):
                status = "✅ SUCCESS" if result.get('retrain_success') else "❌ FAILED"
                print(f"  Retraining: {status}")
        
        # Generate report
        report = monitor.generate_monitoring_report()
        
        # Save report
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        report_file = f"monitoring_report_{timestamp}.json"
        
        with open(report_file, 'w') as f:
            json.dump(report, f, indent=2, default=str)
        
        print(f"\nDetailed report saved to: {report_file}")
        
        # Start continuous monitoring if enabled
        if monitor.config.get('monitoring_enabled', True):
            print(f"\nStarting continuous monitoring...")
            monitor.start_monitoring()
            
            try:
                # Keep the script running
                while True:
                    time.sleep(60)
            except KeyboardInterrupt:
                print("\nStopping monitoring...")
                monitor.stop_monitoring()
        
    except Exception as e:
        logger.error(f"Monitoring failed: {e}")
        raise


if __name__ == "__main__":
    main()