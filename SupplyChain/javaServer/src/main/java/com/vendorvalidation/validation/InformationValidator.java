package com.vendorvalidation.validation;

import com.vendorvalidation.model.VendorApplication;

public class InformationValidator {
	public ValidationResult validate(VendorApplication app) {
		
		//getters
		String companyName = app.getCompanyName();
		String type = app.getVendorType();
		String companyEmail = app.getCompanyEmail();
		String companyAddress = app.getCompanyAddress();
		String companyPhone = app.getCompanyPhone();
		String URSBNo = app.getURSBNo();
		String TIN = app.getTIN();
		String licenseNo = app.getLicenseNo();
		String contactName = app.getContactName();
		String contactEmail = app.getContactEmail();
		String contactTitle = app.getContactTitle();
		String contactNo = app.getContactNo();
		int inventoryCapacity = app.getInventoryCapacity();
		
		if(type == "wholesaler") {
			if (inventoryCapacity <= 0) {
				return ValidationResult.fail("Inventory Capacity missing");
			}
		}
		
		if(companyName == "" || contactName == "") {
			return ValidationResult.fail("Either Company or Contact Name missing");
		}else if(companyEmail == "" || contactEmail == "") {
			return ValidationResult.fail("Either Company or Contact Email missing");
		}else if(companyPhone == "" || contactNo == "" ) {
			return ValidationResult.fail("Either Company or Contact Phone Number missing");
		}else if (companyAddress == "") {
			return ValidationResult.fail("Company Physical Address missing");
		}else if (URSBNo == "") {
			return ValidationResult.fail("URSB Number missing");
		}else if (TIN == "") {
			return ValidationResult.fail("TIN Number missing");
		}else if (licenseNo == "") {
			return ValidationResult.fail("License Number missing");
		}else if (contactTitle == "") {
			return ValidationResult.fail("Contact Title missing");
		}else {
			return ValidationResult.ok();
		}
	}

}
