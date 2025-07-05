from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, Field
import joblib
import pandas as pd
import logging
from demand_model import preprocess_df  # Import your preprocessing function

logging.basicConfig(level=logging.INFO, format="%(asctime)s %(levelname)s:%(message)s")

app = FastAPI()

# Load the pre-trained model and feature columns
model = joblib.load('demand_model.pkl')
feature_columns = joblib.load('model_features.pkl')

class DemandInput(BaseModel):
    product_name: str
    sales_date: str #= Field(..., example="2024-06-25"])
    unit_price: float
    location: str

@app.post("/predict_demand/")
def predict_demand(input_data: DemandInput):
    try:
        logging.info(f"Received input: {input_data}")
        input_df = pd.DataFrame([input_data.dict()])
        # Use shared preprocessing
        input_df = preprocess_df(input_df, feature_columns)
        prediction = model.predict(input_df)[0]
        return {"predicted_demand": round(float(prediction), 2)}
    except Exception as e:
        logging.error(f"Prediction error: {e}")
        raise HTTPException(status_code=400, detail=f"Prediction failed: {e}")