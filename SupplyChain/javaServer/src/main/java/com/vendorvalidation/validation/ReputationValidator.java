package com.vendorvalidation.validation;

import com.vendorvalidation.model.VendorApplication;

public class ReputationValidator {

 public ValidationResult validate(VendorApplication app) {
     int complaints = app.getNumComplaints();
     int years = app.getBusinessYears();
     
     if(years < 6) {
    	 return ValidationResult.fail(String.format("Your Years in Business(%d) are below the 6 year threshold", years));
     }else if (complaints > 5) {
    	 return ValidationResult.fail("The number of complaints your establishment has is worrying");
     }else {
         return ValidationResult.ok();
     }
 }
}

