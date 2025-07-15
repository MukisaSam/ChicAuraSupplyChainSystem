package com.vendorvalidation.validation;

import java.time.LocalDate;
import java.time.Period;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;
import java.util.Locale;

import com.vendorvalidation.model.VendorApplication;

public class ReputationValidator {

 public ValidationResult validate(VendorApplication app) {
	 String dateEstablished = app.getDateEstablished();
	 int numComplaints = app.getNumComplaints();
	 int employeeNo = app.getEmployeeNo();
	 
	 //Calculate years past
	 String[] datePatterns = {"MMMM d, yyyy", "MMM d, yyyy", "yyyy-MM-dd", "MM/dd/yyyy", "M/d/yyyy", "yyyy/MM/dd", "dd-MM-yyyy", "d-M-yyyy",
			                  "dd/MM/yyyy", "d/M/yyyy",  "EEEE, MMM d, yyyy", "MMM d yyyy", "MMMM d yyyy", "d MMMM yyyy", "yyyyMMdd", "yyyy.MM.dd",        // 2010.01.15
			                  "MMM dd, yyyy",  "dd MMM yyyy", "d MMM yyyy", "yyyyMMdd'T'HHmmss" };
	 
	 
	 LocalDate parsedDate = null;
	 for (String pattern : datePatterns) {
		 try {
			 DateTimeFormatter formatter = DateTimeFormatter.ofPattern(pattern, Locale.ENGLISH);
			 parsedDate = LocalDate.parse(dateEstablished, formatter);
			 break;
		 }catch (DateTimeParseException e) {
			  // Try the next pattern
		 }
	 }
	 
	 int yearsPassed = 0;
	 if (parsedDate == null) {
		 System.out.println("Could not parse the date: " + dateEstablished);
	 }else {
		 LocalDate today = LocalDate.now();
		 Period period = Period.between(parsedDate, today);
		 yearsPassed = period.getYears();
	 }
     
	 
	 //Check details
     if(yearsPassed < 6) {
    	 return ValidationResult.fail(String.format("Your Years in Business(%d) are below the 6 year threshold", yearsPassed));
     }else if (numComplaints > 5) {
    	 return ValidationResult.fail("The number of complaints your establishment has is worrying");
     }else if (employeeNo < 0) {
    	 return ValidationResult.fail("Please provide Number of employees");
     }else {
         return ValidationResult.ok();
     }
 }
}

