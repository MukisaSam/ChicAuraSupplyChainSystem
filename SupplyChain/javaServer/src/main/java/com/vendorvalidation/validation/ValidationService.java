package com.vendorvalidation.validation;
import com.vendorvalidation.model.VendorApplication;

import java.util.ArrayList;
import java.util.List;

public class ValidationService {
 private final FinancialValidator financialValidator = new FinancialValidator();
 private final ReputationValidator reputationValidator = new ReputationValidator();
 private final RegulatoryValidator regulatoryValidator = new RegulatoryValidator();

 public ValidationReport validateAll(VendorApplication app) {
     List<String> failures = new ArrayList<>();

     ValidationResult res;

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
         return new ValidationReport(true, "All checks passed", null);
     } else {
         String combined = String.join("; ", failures);
         return new ValidationReport(false, "Validation failed", combined);
     }
 }

 // Helper class to encapsulate overall result
 public static class ValidationReport {
     private boolean valid;
     private String summary;
     private String details; // if invalid, details contains reasons

     public ValidationReport(boolean valid, String summary, String details) {
         this.valid = valid;
         this.summary = summary;
         this.details = details;
     }

     public boolean isValid() {
         return valid;
     }
     public String getSummary() {
         return summary;
     }
     public String getDetails() {
         return details;
     }
 }
}
