package com.vendorvalidation.validation;

import com.vendorvalidation.model.VendorApplication;

public class RegulatoryValidator {

 public ValidationResult validate(VendorApplication app) {
	 
	 String type = app.getVendorType();
	 String qualityLicenses = app.getQualityLicenses();
	 String materialSupplied = app.getMaterialSupplied();
	 String leadTimes = app.getLeadTimes();
	 
	 if (!(qualityLicenses != "" && qualityLicenses.toLowerCase().contains("iso"))) {
		 return ValidationResult.fail("Invalid or Missing Licenses");
	 }
	 if(type == "supplier") {
		 if(materialSupplied == "") {
			 return ValidationResult.fail("Materials Supplied missing");
		 }else if(leadTimes == "") {
			 return ValidationResult.fail("LeadTimes missing");
		 }
	 }
	return ValidationResult.ok();

	 
}
}
