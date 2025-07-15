package com.vendorvalidation.validation;

import com.vendorvalidation.model.VendorApplication;

public class FinancialValidator {

 public ValidationResult validate(VendorApplication app) {
	 
	 //getters
	 String type = app.getVendorType();
	 double totalAssets = app.getTotalAssets();
	 double annualTurnover = app.getAnnualTurnover();
	 String bankReference = app.getBankReference();
	 int minSupplyQuality = app.getMinSupplyQuality();
	 int salesVolume = app.getSalesVolume();
	 int retailerOutletNo = app.getRetailerOutletNo();
	 int minOrderVolume = app.getMinOrderVolume();
	 
     
     //thresholds
     double thresholdAssets;
     double thresholdTurnOver;
     int thresholdSupplyQuality;
     int thresholdsalesVolume;
     int thresholdOrderVolume;
     
     
     if ("supplier".equalsIgnoreCase(type)) {
    	 thresholdAssets = 250000000;
    	 thresholdTurnOver = 100000000;
    	 thresholdSupplyQuality = 85;
    	 
    	 if(totalAssets < thresholdAssets) {
        	 return ValidationResult.fail(String.format("Your Total Assets %d are below threshold %d for type %s", totalAssets, thresholdAssets, type));
         }else if (annualTurnover < thresholdTurnOver) {
             return ValidationResult.fail(String.format("Your Annual TurnOver %.2f is below threshold %.2f for type %s", annualTurnover, thresholdTurnOver, type));
         }else if(minSupplyQuality < thresholdSupplyQuality) {
        	 return ValidationResult.fail(String.format("Your Minumum Supply Quantity %d are below threshold %d for type %s", minSupplyQuality, thresholdSupplyQuality, type));
         }
    	 
    	 
     } else if ("wholesaler".equalsIgnoreCase(type)) {
    	 thresholdAssets = 400000000;
    	 thresholdTurnOver = 200000000;
    	 thresholdsalesVolume = 100000;
    	 thresholdOrderVolume = 200;
    	 
    	 if(totalAssets < thresholdAssets) {
        	 return ValidationResult.fail(String.format("Your Total Assets %d are below threshold %d for type %s", totalAssets, thresholdAssets, type));
         }else if (annualTurnover < thresholdTurnOver) {
             return ValidationResult.fail(String.format("Your Annual TurnOver %.2f is below threshold %.2f for type %s", annualTurnover, thresholdTurnOver, type));
         }else if (salesVolume < thresholdsalesVolume) {
        	 return ValidationResult.fail(String.format("Your Sales Volume of %d is below threshold %d for type %s", salesVolume, thresholdsalesVolume, type));
         }else if (minOrderVolume < thresholdOrderVolume) {
        	 return ValidationResult.fail(String.format("Your Minumum Order Volume of %d is below threshold %d for type %s", minOrderVolume, thresholdOrderVolume, type));
         }
     } else {
         return ValidationResult.fail("Unknown vendor type for financial validation: " + type);
     }
     
     //checking requirements
     if(bankReference.toLowerCase() == "none" || bankReference == "") {
    	 return ValidationResult.fail("Please provide a Bank Reference!");
     }else if(retailerOutletNo < 0) {
    	 return ValidationResult.fail("Please provide a Retailer Outlet Number!");
     }
     else {
    	 return ValidationResult.ok();
     }

 }
      
}

