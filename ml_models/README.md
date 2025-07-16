# Enhanced ML Analytics System

## Overview

This enhanced machine learning analytics system provides comprehensive demand forecasting, product recommendations, and supplier performance analytics for the ChicAura Supply Chain System.

## 🚀 New Features & Enhancements

### 1. **Enhanced Demand Forecasting** (`demand_model.py`)
- **Hyperparameter Optimization**: Automated tuning using Optuna
- **External Factors**: Holiday effects, weather patterns, seasonal trends
- **Advanced Feature Engineering**: Lag features, rolling statistics, trend analysis
- **Model Monitoring**: Drift detection and automatic retraining
- **Interactive Visualizations**: Plotly-based dashboards
- **Multiple Frequencies**: Support for daily and monthly predictions

### 2. **Hybrid Recommendation System** (`recommendation_system.py`)
- **Collaborative Filtering**: Matrix factorization (SVD, NMF) + Neural Collaborative Filtering
- **Content-Based Filtering**: TF-IDF + product attributes + demographic profiling
- **Demographic Recommendations**: Age, gender, income-based suggestions
- **Seasonal Recommendations**: Time-aware product suggestions
- **RFM Analysis**: Customer segmentation (Recency, Frequency, Monetary)
- **Real-time Recommendations**: On-demand personalized suggestions

### 3. **Supplier Performance Analytics** (`supplier_performance.py`)
- **Comprehensive KPIs**: Delivery, quality, price competitiveness, reliability
- **Performance Scoring**: Weighted composite scores with tier classification
- **Anomaly Detection**: Unusual performance pattern identification
- **Predictive Analytics**: Future performance forecasting
- **Supplier Clustering**: Performance-based segmentation
- **Automated Alerts**: Performance degradation warnings

### 4. **Advanced Feature Engineering** (`enhanced_features.py`)
- **Time Features**: Cyclical encoding, business calendars, seasonality
- **Lag & Rolling Features**: Historical pattern capture
- **Customer Features**: Demographic encoding, behavioral metrics
- **Product Features**: Category encoding, price tiers, popularity scores
- **Interaction Features**: Cross-feature relationships

### 5. **FastAPI ML Services** (`api_server.py`)
- **RESTful API**: Unified interface for all ML services
- **Authentication**: Token-based security
- **Async Processing**: Background model training
- **Health Monitoring**: Service status and model health checks
- **Error Handling**: Comprehensive error management

### 6. **Model Monitoring & Retraining** (`model_monitoring.py`)
- **Performance Tracking**: Automated model performance monitoring
- **Drift Detection**: Concept drift identification
- **Auto-Retraining**: Triggered retraining based on performance thresholds
- **Model Backup**: Automatic backup before retraining
- **Scheduling**: Configurable monitoring intervals

## 📁 File Structure

```
ml_models/
├── demand_model.py              # Enhanced Prophet demand forecasting
├── enhanced_demand_model.py     # Legacy ensemble model (maintained)
├── recommendation_system.py     # Hybrid recommendation engine
├── supplier_performance.py      # Supplier analytics and KPIs
├── enhanced_features.py         # Advanced feature engineering utilities
├── db_config.py                # Enhanced database connectivity
├── api_server.py               # FastAPI ML services server
├── model_monitoring.py         # Model monitoring and retraining
├── requirements.txt            # Updated dependencies
├── README.md                   # This documentation
│
├── enhanced_demand_models/     # Enhanced model storage
├── recommendation_models/      # Recommendation model storage
├── supplier_models/           # Supplier performance models
├── model_backups/             # Model backups
└── forecast_plots/            # Generated visualizations
```

## 🔧 Installation & Setup

### 1. Install Dependencies
```bash
cd ml_models
pip install -r requirements.txt
```

### 2. Database Configuration
Set environment variables or update `db_config.py`:
```bash
export DB_HOST=localhost
export DB_USER=root
export DB_PASSWORD=your_password
export DB_NAME=mukisa
```

### 3. Train Initial Models
```bash
# Train demand forecasting model
python demand_model.py

# Train recommendation system
python recommendation_system.py

# Analyze supplier performance
python supplier_performance.py
```

### 4. Start API Server
```bash
python api_server.py
# API will be available at http://localhost:8000
# Documentation at http://localhost:8000/ml-docs
```

### 5. Start Model Monitoring
```bash
python model_monitoring.py
```

## 🌐 API Endpoints

### **Demand Forecasting**
- `POST /api/v1/forecast/demand` - Generate demand predictions
- `GET /api/v1/forecast/models` - Get model information

### **Recommendations**
- `POST /api/v1/recommendations` - Get customer recommendations
- `POST /api/v1/recommendations/train` - Train recommendation models

### **Supplier Performance**
- `POST /api/v1/suppliers/analysis` - Analyze supplier performance
- `GET /api/v1/suppliers/{supplier_id}/performance` - Get specific supplier performance

### **Model Management**
- `POST /api/v1/models/retrain` - Retrain models
- `GET /api/v1/models/status` - Get model status
- `GET /api/v1/data/summary` - Get data summary

### **Health & Monitoring**
- `GET /health` - Health check
- `GET /api/v1/data/summary` - Data availability summary

## 🔑 Authentication

All API endpoints require Bearer token authentication:
```bash
curl -H "Authorization: Bearer ml-api-your-token-here" \
     http://localhost:8000/api/v1/forecast/demand
```

## 📊 Usage Examples

### **1. Demand Forecasting**
```python
import requests

response = requests.post(
    "http://localhost:8000/api/v1/forecast/demand",
    headers={"Authorization": "Bearer ml-api-token"},
    json={
        "product_name": "Cotton T-Shirt",
        "location": "New York",
        "unit_price": 25.99,
        "prediction_frequency": "D",
        "periods": 30
    }
)

predictions = response.json()
print(f"30-day demand forecast: {predictions['predictions']}")
```

### **2. Product Recommendations**
```python
response = requests.post(
    "http://localhost:8000/api/v1/recommendations",
    headers={"Authorization": "Bearer ml-api-token"},
    json={
        "customer_id": 123,
        "n_recommendations": 10,
        "include_reasons": True
    }
)

recommendations = response.json()
for rec in recommendations['recommendations']:
    print(f"{rec['name']}: ${rec['price']} - {rec['reasons']}")
```

### **3. Supplier Performance Analysis**
```python
response = requests.post(
    "http://localhost:8000/api/v1/suppliers/analysis",
    headers={"Authorization": "Bearer ml-api-token"},
    json={
        "include_predictions": True,
        "analysis_period_days": 90
    }
)

analysis = response.json()
for supplier in analysis['supplier_performance']:
    print(f"{supplier['supplier_name']}: {supplier['overall_performance_score']:.1f} ({supplier['performance_tier']})")
```

## 📈 Performance Metrics

### **Demand Forecasting**
- **MAPE**: Mean Absolute Percentage Error < 15%
- **R²**: Coefficient of determination > 0.8
- **Coverage**: 95% of predictions within confidence intervals

### **Recommendations**
- **Precision@10**: > 30%
- **Recall@10**: > 40%
- **Diversity**: Balanced across categories

### **Supplier Performance**
- **Delivery Performance**: On-time delivery rate tracking
- **Quality Score**: Average rating and consistency
- **Price Competitiveness**: Market comparison
- **Reliability**: Fulfillment rate and consistency

## 🔄 Model Retraining

### **Automatic Retraining Triggers**
- **Demand Model**: MAPE > 25% or R² < 0.6
- **Recommendations**: Precision < 25% or Recall < 20%
- **Supplier Analytics**: Significant performance changes

### **Manual Retraining**
```bash
# Retrain specific model
curl -X POST "http://localhost:8000/api/v1/models/retrain" \
     -H "Authorization: Bearer ml-api-token" \
     -H "Content-Type: application/json" \
     -d '{"model_type": "demand", "force_retrain": true}'
```

## 🚨 Monitoring & Alerts

The monitoring system tracks:
- **Model Performance**: Accuracy degradation detection
- **Data Drift**: Input distribution changes
- **System Health**: API response times and error rates
- **Resource Usage**: Memory and CPU utilization

### **Alert Types**
- Performance degradation warnings
- Anomalous supplier behavior
- Model retraining notifications
- System health alerts

## 🛠️ Configuration

### **Monitoring Configuration** (`monitoring_config.json`)
```json
{
  "monitoring_enabled": true,
  "check_interval_hours": 24,
  "auto_retrain": true,
  "backup_models": true,
  "performance_window_days": 30
}
```

### **Model Parameters**
Models support extensive hyperparameter customization through configuration files and environment variables.

## 🔧 Troubleshooting

### **Common Issues**

1. **Database Connection Errors**
   - Verify database credentials
   - Check network connectivity
   - Ensure database server is running

2. **Model Training Failures**
   - Check data availability (minimum 30 days)
   - Verify sufficient memory allocation
   - Review logs for specific errors

3. **API Authentication Issues**
   - Ensure Bearer token format: `Bearer ml-api-your-token`
   - Check token validity and permissions

4. **Performance Issues**
   - Monitor system resources
   - Consider model complexity reduction
   - Implement caching for frequent requests

### **Logs & Debugging**
- Application logs: Check console output
- Model training logs: Available in model directories
- API logs: FastAPI automatic logging
- Monitoring logs: `model_monitoring.py` output

## 📝 Development Notes

### **Adding New Features**
1. Extend base classes in respective modules
2. Update API endpoints in `api_server.py`
3. Add monitoring checks in `model_monitoring.py`
4. Update documentation and tests

### **Database Schema Dependencies**
The system expects specific table structures:
- `customers`, `customer_orders`, `customer_order_items`
- `suppliers`, `supply_requests`, `supplied_items`
- `items`, `orders`, `order_items`
- `chat_messages` (for communication analysis)

### **Performance Optimization**
- Use connection pooling for database access
- Implement caching for frequent predictions
- Consider model quantization for memory efficiency
- Batch predictions when possible

## 📞 Support

For technical support or feature requests:
1. Check logs for error details
2. Review configuration files
3. Consult API documentation at `/ml-docs`
4. Monitor system health at `/health`

## 🔮 Future Enhancements

Planned improvements:
- **Real-time Streaming**: Live data processing
- **Multi-model Ensembles**: Advanced model combinations
- **Explainable AI**: Model interpretation features
- **A/B Testing**: Recommendation algorithm testing
- **Advanced Visualizations**: Interactive dashboards
- **Mobile API**: Optimized mobile endpoints

---

This enhanced ML system provides enterprise-grade analytics capabilities with production-ready monitoring, automated retraining, and comprehensive APIs for seamless integration with the ChicAura Supply Chain System.