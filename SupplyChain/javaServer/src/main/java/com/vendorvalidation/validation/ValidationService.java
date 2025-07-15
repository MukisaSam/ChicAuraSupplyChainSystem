package com.vendorvalidation.validation;
import com.vendorvalidation.model.VendorApplication;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class ValidationService {
 private final FinancialValidator financialValidator = new FinancialValidator();
 private final ReputationValidator reputationValidator = new ReputationValidator();
 private final RegulatoryValidator regulatoryValidator = new RegulatoryValidator();
 private final InformationValidator infoValidator = new InformationValidator();

 public ValidationReport validateAll(VendorApplication app) {
     List<String> failures = new ArrayList<>();
     Map<String , Object> records = new HashMap<>();
     
     //Get records
     records = extractRecords(app);

     ValidationResult res;

     res = infoValidator.validate(app);
     if (!res.isValid()) {
         failures.add("Information Details: " + res.getMessage());
     }
     
     res = financialValidator.validate(app);
     if (!res.isValid()) {
         failures.add("Financial: " + res.getMessage());
     }

     res = reputationValidator.validate(app);
     if (!res.isValid()) {
         failures.add("Reputation: " + res.getMessage());
     }

     res = regulatoryValidator.validate(app);
     if (!res.isValid()) {
         failures.add("Regulatory: " + res.getMessage());
     }

     if (failures.isEmpty()) {
         return new ValidationReport(true, "All checks passed", null, records);
     } else {
         String combined = String.join("; ", failures);
         return new ValidationReport(false, "Validation failed", combined, null);
     }
 }

 // Helper class to encapsulate overall result
 public static class ValidationReport {
     private boolean valid;
     private String summary;
     private String errorDetails; // if invalid, details contains reasons
     private Map<String , Object> records;

     public ValidationReport(boolean valid, String summary, String errorDetails, Map<String , Object> records) {
         this.valid = valid;
         this.summary = summary;
         this.errorDetails = errorDetails;
         this.records = records;
     }

     public boolean isValid() {
         return valid;
     }
     public String getSummary() {
         return summary;
     }
     public String getDetails() {
         return errorDetails;
     }
     public Map<String , Object> getRecords() {
         return records;
     }
     
 }
 
 public Map<String , Object> extractRecords(VendorApplication app){
	 Map<String , Object> records = new HashMap<>();
	 
		    records.put("name", app.getCompanyName());
		    records.put("email", app.getCompanyEmail());
		    records.put("role", app.getVendorType());
		    records.put("business_address",app.getCompanyAddress());
		    records.put("phone", app.getCompanyPhone());
		    records.put("monthly_order_volume", app.getMinOrderVolume());
		    records.put("materials_supplied", app.getMaterialSupplied());
	
	 return records;
 }
}
