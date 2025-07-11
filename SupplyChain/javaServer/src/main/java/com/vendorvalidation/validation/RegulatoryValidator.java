package com.vendorvalidation.validation;

import com.vendorvalidation.model.VendorApplication;

public class RegulatoryValidator {

 public ValidationResult validate(VendorApplication app) {
	 
	 String licenses = app.getHasValidLicenses();
	 String auditdate = app.getLastAuditDate();
	 String penalties = app.getHasPenalties();
	 
	 if (!(licenses != null && licenses.toLowerCase().contains("iso"))) {
		 return ValidationResult.fail("Invalid or Missing Licenses");
	 }else if(auditdate == null) {
		 return ValidationResult.fail("Missing Audit Dates");
	 }else if (!(penalties == null || penalties.equalsIgnoreCase("none"))) {
		    return ValidationResult.fail("Presence of penalties");
	 }else {
		 return ValidationResult.ok();
	 }
	 
}
}
