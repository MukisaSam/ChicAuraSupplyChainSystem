"""
FastAPI Server for Machine Learning Services
Unified API for demand forecasting, recommendations, and supplier performance analytics
"""

from fastapi import FastAPI, HTTPException, BackgroundTasks, Depends, Security
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field
from typing import Dict, List, Optional, Union
import asyncio
import logging
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import json
import os
import traceback

# Import ML modules
from demand_model import EnhancedProphetDemandModel, predict_demand, load_model
from recommendation_system import HybridRecommendationSystem
from supplier_performance import SupplierPerformanceAnalyzer
from db_config import get_demand_data, get_customer_data, get_supplier_performance_data

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# FastAPI app setup
app = FastAPI(
    title="ChicAura ML Services API",
    description="Machine Learning services for demand forecasting, recommendations, and supplier analytics",
    version="1.0.0",
    docs_url="/ml-docs",
    redoc_url="/ml-redoc"
)

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Configure based on your needs
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Security
security = HTTPBearer()

# Global model instances
demand_model = None
recommendation_system = None
supplier_analyzer = None

# Pydantic models for API
class DemandPredictionRequest(BaseModel):
    product_name: str = Field(..., description="Name of the product")
    location: str = Field(..., description="Location/address for delivery")
    unit_price: float = Field(..., gt=0, description="Unit price of the product")
    sales_date: Optional[str] = Field(None, description="Date for prediction (YYYY-MM-DD)")
    prediction_frequency: Optional[str] = Field("D", description="Prediction frequency: 'D' for daily, 'M' for monthly")
    periods: Optional[int] = Field(30, ge=1, le=365, description="Number of periods to forecast")

class DemandPredictionResponse(BaseModel):
    predictions: List[float]
    dates: List[str]
    lower_bounds: List[float]
    upper_bounds: List[float]
    model_metadata: Dict
    confidence_interval: float = 0.8

class RecommendationRequest(BaseModel):
    customer_id: int = Field(..., gt=0, description="Customer ID")
    n_recommendations: Optional[int] = Field(10, ge=1, le=50, description="Number of recommendations")
    include_reasons: Optional[bool] = Field(True, description="Include recommendation reasons")

class RecommendationResponse(BaseModel):
    customer_id: int
    recommendations: List[Dict]
    algorithm_weights: Dict
    generated_at: str

class SupplierAnalysisRequest(BaseModel):
    supplier_id: Optional[int] = Field(None, description="Specific supplier ID (optional)")
    include_predictions: Optional[bool] = Field(True, description="Include future performance predictions")
    analysis_period_days: Optional[int] = Field(90, ge=30, le=365, description="Analysis period in days")

class SupplierAnalysisResponse(BaseModel):
    analysis_date: str
    total_suppliers: int
    supplier_performance: List[Dict]
    insights: Dict
    alerts: List[str]

class ModelRetrainingRequest(BaseModel):
    model_type: str = Field(..., description="Model type: 'demand', 'recommendation', 'supplier'")
    force_retrain: Optional[bool] = Field(False, description="Force retraining even if not needed")

class HealthResponse(BaseModel):
    status: str
    timestamp: str
    models_loaded: Dict[str, bool]
    database_connection: bool
    uptime_seconds: float

# Startup event
@app.on_event("startup")
async def startup_event():
    """Initialize ML models on startup"""
    global demand_model, recommendation_system, supplier_analyzer
    
    logger.info("Starting ML Services API...")
    
    try:
        # Load demand forecasting model
        logger.info("Loading demand forecasting model...")
        demand_model = load_model()
        if demand_model is None:
            logger.warning("Demand model not found, will create new one if needed")
        
        # Initialize recommendation system
        logger.info("Initializing recommendation system...")
        recommendation_system = HybridRecommendationSystem()
        
        # Initialize supplier analyzer
        logger.info("Initializing supplier performance analyzer...")
        supplier_analyzer = SupplierPerformanceAnalyzer()
        
        logger.info("ML Services API startup completed successfully")
        
    except Exception as e:
        logger.error(f"Startup failed: {e}")
        logger.error(traceback.format_exc())

# Authentication dependency
async def verify_token(credentials: HTTPAuthorizationCredentials = Security(security)):
    """Verify API token (implement your authentication logic)"""
    # For demo purposes, accept any token
    # In production, implement proper JWT verification
    if credentials.scheme != "Bearer":
        raise HTTPException(status_code=401, detail="Invalid authentication scheme")
    
    # Add your token verification logic here
    # For now, we'll accept any token that starts with "ml-api-"
    if not credentials.credentials.startswith("ml-api-"):
        raise HTTPException(status_code=401, detail="Invalid token")
    
    return credentials.credentials

# Health check endpoint
@app.get("/health", response_model=HealthResponse)
async def health_check():
    """Health check endpoint"""
    from db_config import db
    
    start_time = getattr(app.state, 'start_time', datetime.now())
    uptime = (datetime.now() - start_time).total_seconds()
    
    # Check database connection
    db_connected = db.check_connection()
    
    # Check model status
    models_status = {
        "demand_model": demand_model is not None,
        "recommendation_system": recommendation_system is not None and recommendation_system.models_trained,
        "supplier_analyzer": supplier_analyzer is not None
    }
    
    status = "healthy" if all([db_connected] + list(models_status.values())) else "degraded"
    
    return HealthResponse(
        status=status,
        timestamp=datetime.now().isoformat(),
        models_loaded=models_status,
        database_connection=db_connected,
        uptime_seconds=uptime
    )

# Demand forecasting endpoints
@app.post("/api/v1/forecast/demand", response_model=DemandPredictionResponse)
async def predict_demand_api(
    request: DemandPredictionRequest,
    token: str = Depends(verify_token)
):
    """Generate demand predictions"""
    try:
        logger.info(f"Demand prediction request: {request.product_name} at {request.location}")
        
        # Prepare input data
        sales_date = datetime.fromisoformat(request.sales_date) if request.sales_date else datetime.now()
        
        input_data = {
            'product_name': request.product_name,
            'location': request.location,
            'unit_price': request.unit_price,
            'sales_date': sales_date
        }
        
        # Generate predictions for multiple periods
        predictions = []
        dates = []
        lower_bounds = []
        upper_bounds = []
        
        for i in range(request.periods):
            if request.prediction_frequency == 'M':
                pred_date = sales_date + timedelta(days=30*i)
            else:
                pred_date = sales_date + timedelta(days=i)
            
            input_data['sales_date'] = pred_date
            prediction = predict_demand(input_data, request.prediction_frequency)
            
            predictions.append(float(prediction))
            dates.append(pred_date.strftime('%Y-%m-%d'))
            
            # Simple confidence intervals (±20%)
            lower_bounds.append(float(prediction * 0.8))
            upper_bounds.append(float(prediction * 1.2))
        
        # Get model metadata
        model_metadata = {
            "model_type": "Enhanced Prophet",
            "prediction_frequency": request.prediction_frequency,
            "features_used": ["product_name", "location", "unit_price", "sales_date"],
            "last_trained": "Unknown"
        }
        
        if demand_model and hasattr(demand_model, 'model_metadata'):
            model_key = f"{request.product_name}_{request.location}"
            if model_key in demand_model.model_metadata:
                model_metadata.update(demand_model.model_metadata[model_key])
        
        return DemandPredictionResponse(
            predictions=predictions,
            dates=dates,
            lower_bounds=lower_bounds,
            upper_bounds=upper_bounds,
            model_metadata=model_metadata
        )
        
    except Exception as e:
        logger.error(f"Demand prediction failed: {e}")
        raise HTTPException(status_code=500, detail=f"Prediction failed: {str(e)}")

@app.get("/api/v1/forecast/models")
async def get_demand_models(token: str = Depends(verify_token)):
    """Get information about trained demand models"""
    try:
        if not demand_model:
            return {"models": [], "total": 0}
        
        summary = demand_model.get_model_summary() if hasattr(demand_model, 'get_model_summary') else {}
        
        return {
            "models": summary.get('models', []),
            "total": summary.get('total_models', 0),
            "prediction_frequency": summary.get('prediction_frequency', 'Unknown'),
            "evaluation_results": getattr(demand_model, 'evaluation_results', {})
        }
        
    except Exception as e:
        logger.error(f"Failed to get model information: {e}")
        raise HTTPException(status_code=500, detail=str(e))

# Recommendation endpoints
@app.post("/api/v1/recommendations", response_model=RecommendationResponse)
async def get_recommendations(
    request: RecommendationRequest,
    token: str = Depends(verify_token)
):
    """Get product recommendations for a customer"""
    try:
        logger.info(f"Recommendation request for customer {request.customer_id}")
        
        if not recommendation_system:
            raise HTTPException(status_code=503, detail="Recommendation system not available")
        
        # Train models if not already trained
        if not recommendation_system.models_trained:
            logger.info("Training recommendation models...")
            success = recommendation_system.train_models()
            if not success:
                raise HTTPException(status_code=503, detail="Failed to train recommendation models")
        
        # Get recommendations
        recommendations = recommendation_system.get_hybrid_recommendations(
            request.customer_id, 
            request.n_recommendations
        )
        
        # Format response
        formatted_recs = []
        for rec in recommendations:
            formatted_rec = {
                "item_id": rec["item_id"],
                "name": rec["name"],
                "description": rec.get("description", ""),
                "category": rec.get("category", ""),
                "price": rec["price"],
                "recommendation_score": rec["recommendation_score"]
            }
            
            if request.include_reasons:
                formatted_rec["reasons"] = rec.get("recommendation_reasons", [])
            
            formatted_recs.append(formatted_rec)
        
        return RecommendationResponse(
            customer_id=request.customer_id,
            recommendations=formatted_recs,
            algorithm_weights=recommendation_system.weights,
            generated_at=datetime.now().isoformat()
        )
        
    except Exception as e:
        logger.error(f"Recommendation failed: {e}")
        raise HTTPException(status_code=500, detail=f"Recommendation failed: {str(e)}")

@app.post("/api/v1/recommendations/train")
async def train_recommendation_models(
    background_tasks: BackgroundTasks,
    token: str = Depends(verify_token)
):
    """Train recommendation models in background"""
    
    async def train_models():
        try:
            logger.info("Starting recommendation model training...")
            if recommendation_system:
                success = recommendation_system.train_models()
                if success:
                    recommendation_system.save_models()
                    logger.info("Recommendation models trained and saved successfully")
                else:
                    logger.error("Recommendation model training failed")
        except Exception as e:
            logger.error(f"Background training failed: {e}")
    
    background_tasks.add_task(train_models)
    
    return {"message": "Recommendation model training started in background"}

# Supplier performance endpoints
@app.post("/api/v1/suppliers/analysis", response_model=SupplierAnalysisResponse)
async def analyze_supplier_performance(
    request: SupplierAnalysisRequest,
    token: str = Depends(verify_token)
):
    """Analyze supplier performance"""
    try:
        logger.info("Starting supplier performance analysis...")
        
        if not supplier_analyzer:
            raise HTTPException(status_code=503, detail="Supplier analyzer not available")
        
        # Run analysis
        performance_df, insights = supplier_analyzer.run_full_analysis()
        
        if performance_df.empty:
            return SupplierAnalysisResponse(
                analysis_date=datetime.now().isoformat(),
                total_suppliers=0,
                supplier_performance=[],
                insights={"summary": {"total_suppliers": 0}},
                alerts=["No supplier data available for analysis"]
            )
        
        # Filter by supplier_id if specified
        if request.supplier_id:
            performance_df = performance_df[performance_df.index == request.supplier_id]
        
        # Convert to list of dictionaries
        supplier_performance = []
        for idx, row in performance_df.iterrows():
            supplier_data = {
                "supplier_id": int(idx),
                "supplier_name": row.get("supplier_name", "Unknown"),
                "overall_performance_score": float(row.get("overall_performance_score", 0)),
                "performance_tier": row.get("performance_tier", "Unknown"),
                "delivery_performance_score": float(row.get("delivery_performance_score", 0)),
                "quality_score": float(row.get("quality_score", 0)),
                "price_competitiveness_score": float(row.get("price_competitiveness_score", 0)),
                "reliability_score": float(row.get("reliability_score", 0)),
                "communication_score": float(row.get("communication_score", 0)),
                "is_anomaly": bool(row.get("is_anomaly", False)),
                "cluster_name": row.get("cluster_name", "Unknown")
            }
            
            if request.include_predictions:
                supplier_data["predicted_performance"] = float(row.get("predicted_performance", 0))
            
            supplier_performance.append(supplier_data)
        
        return SupplierAnalysisResponse(
            analysis_date=datetime.now().isoformat(),
            total_suppliers=len(supplier_performance),
            supplier_performance=supplier_performance,
            insights=insights,
            alerts=insights.get("alerts", [])
        )
        
    except Exception as e:
        logger.error(f"Supplier analysis failed: {e}")
        raise HTTPException(status_code=500, detail=f"Analysis failed: {str(e)}")

@app.get("/api/v1/suppliers/{supplier_id}/performance")
async def get_supplier_performance(
    supplier_id: int,
    token: str = Depends(verify_token)
):
    """Get performance data for a specific supplier"""
    try:
        # Use the analysis endpoint with supplier filter
        request = SupplierAnalysisRequest(supplier_id=supplier_id)
        result = await analyze_supplier_performance(request, token)
        
        if not result.supplier_performance:
            raise HTTPException(status_code=404, detail="Supplier not found")
        
        return result.supplier_performance[0]
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Failed to get supplier performance: {e}")
        raise HTTPException(status_code=500, detail=str(e))

# Model management endpoints
@app.post("/api/v1/models/retrain")
async def retrain_models(
    request: ModelRetrainingRequest,
    background_tasks: BackgroundTasks,
    token: str = Depends(verify_token)
):
    """Retrain ML models"""
    
    async def retrain_task():
        try:
            logger.info(f"Starting retraining for {request.model_type}")
            
            if request.model_type == "demand":
                from demand_model import main as train_demand
                train_demand()
                
            elif request.model_type == "recommendation":
                if recommendation_system:
                    recommendation_system.train_models()
                    recommendation_system.save_models()
                    
            elif request.model_type == "supplier":
                if supplier_analyzer:
                    supplier_analyzer.run_full_analysis()
                    supplier_analyzer.save_models()
                    
            elif request.model_type == "all":
                # Retrain all models
                from demand_model import main as train_demand
                train_demand()
                
                if recommendation_system:
                    recommendation_system.train_models()
                    recommendation_system.save_models()
                
                if supplier_analyzer:
                    supplier_analyzer.run_full_analysis()
                    supplier_analyzer.save_models()
            
            else:
                logger.error(f"Unknown model type: {request.model_type}")
                return
            
            logger.info(f"Retraining completed for {request.model_type}")
            
        except Exception as e:
            logger.error(f"Retraining failed for {request.model_type}: {e}")
    
    background_tasks.add_task(retrain_task)
    
    return {
        "message": f"Retraining started for {request.model_type}",
        "status": "in_progress",
        "timestamp": datetime.now().isoformat()
    }

@app.get("/api/v1/models/status")
async def get_models_status(token: str = Depends(verify_token)):
    """Get status of all ML models"""
    
    status = {
        "demand_model": {
            "loaded": demand_model is not None,
            "type": "Enhanced Prophet",
            "last_updated": "Unknown"
        },
        "recommendation_system": {
            "loaded": recommendation_system is not None,
            "trained": recommendation_system.models_trained if recommendation_system else False,
            "type": "Hybrid Collaborative + Content-based"
        },
        "supplier_analyzer": {
            "loaded": supplier_analyzer is not None,
            "trained": supplier_analyzer.models_trained if supplier_analyzer else False,
            "type": "Performance Analytics"
        }
    }
    
    return status

# Utility endpoints
@app.get("/api/v1/data/summary")
async def get_data_summary(token: str = Depends(verify_token)):
    """Get summary of available data"""
    try:
        # Get data counts
        demand_data = get_demand_data()
        customer_data = get_customer_data()
        supplier_data = get_supplier_performance_data()
        
        summary = {
            "demand_records": len(demand_data) if demand_data else 0,
            "customers": len(customer_data) if customer_data else 0,
            "supplier_transactions": len(supplier_data) if supplier_data else 0,
            "last_updated": datetime.now().isoformat()
        }
        
        return summary
        
    except Exception as e:
        logger.error(f"Failed to get data summary: {e}")
        raise HTTPException(status_code=500, detail=str(e))

# Error handlers
@app.exception_handler(404)
async def not_found_handler(request, exc):
    return {"error": "Endpoint not found", "detail": str(exc)}

@app.exception_handler(500)
async def internal_error_handler(request, exc):
    logger.error(f"Internal server error: {exc}")
    return {"error": "Internal server error", "detail": "Please check logs for details"}

# Set startup time for uptime calculation
@app.on_event("startup")
async def set_startup_time():
    app.state.start_time = datetime.now()

if __name__ == "__main__":
    import uvicorn
    
    # Run the server
    uvicorn.run(
        "api_server:app",
        host="0.0.0.0",
        port=8000,
        reload=True,
        log_level="info"
    )