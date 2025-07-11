package com.vendorvalidation.validation;

import com.vendorvalidation.model.VendorApplication;

public class FinancialValidator {

 public ValidationResult validate(VendorApplication app) {
	 
	 //getters
	 int assets = app.getTotalAssets();
     double revenue = app.getRevenue();
     double profit = app.getProfit();
     int creditscore = app.getCreditScore();
     String bank = app.getBankReference();
     String type = app.getVendorType();
     
     //thresholds
     int thresholdAssets;
     double thresholdProfit;
     double thresholdRevenue;
     int thresholdCreditscore;
     
     
     if ("supplier".equalsIgnoreCase(type)) {
    	 thresholdAssets = 25000000;
         thresholdRevenue = 5000000;
         thresholdProfit = 2000000;
         thresholdCreditscore = 300;
     } else if ("manufacturer".equalsIgnoreCase(type)) {
    	 thresholdAssets = 50000000;
         thresholdRevenue = 20000000;
         thresholdProfit = 10000000;
         thresholdCreditscore = 250;
     } else if ("wholesaler".equalsIgnoreCase(type)) {
    	 thresholdAssets = 40000000;
         thresholdRevenue = 15000000;
         thresholdProfit = 10000000;
         thresholdCreditscore = 300;
     } else {
         return ValidationResult.fail("Unknown vendor type for financial validation: " + type);
     }
     
     //checking requirements
     if(assets < thresholdAssets) {
    	 return ValidationResult.fail(String.format("Your Total Assets %d are below threshold %d for type %s", assets, thresholdAssets, type));
     }else if (revenue < thresholdRevenue) {
         return ValidationResult.fail(String.format("Your Annual Revenue %.2f is below threshold %.2f for type %s", revenue, thresholdRevenue, type));
     }else if(profit < thresholdProfit) {
    	 return ValidationResult.fail(String.format("Your Profits %.2f are below threshold %.2f for type %s", profit, thresholdProfit, type));
     }else if (creditscore < thresholdCreditscore) {
    	 return ValidationResult.fail(String.format("Your Credit Score of %d is below threshold %d for type %s", creditscore, thresholdCreditscore, type));
     }else if(bank == null || bank.toLowerCase() == "none" || bank == "") {
    	 return ValidationResult.fail(String.format("Please provide a Bank Reference!"));
     }else {
    	 return ValidationResult.ok();
     }

 }
      
}

