package com.vendorvalidation.parser;

import com.vendorvalidation.model.VendorApplication;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;

import java.io.InputStream;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class PdfParser {

 public VendorApplication parse(InputStream pdfInputStream) throws Exception {
	 VendorApplication app = new VendorApplication();
	 
     // Load PDF from InputStream
     try (PDDocument document = PDDocument.load(pdfInputStream)) {
         PDFTextStripper stripper = new PDFTextStripper();
         String text = stripper.getText(document);
         
         //vendor info
         app.setVendorName(extractString(text, "COMPANY / FIRM NAME\\s+(.+)"));
         app.setVendorType(extractString(text, "(.+)\\s*APPLICATION FORM").toLowerCase());
         
         //financial info
         app.setTotalAssets(extractInteger(text, "TOTAL ASSETS\\s*([\\d,]+)"));
         app.setRevenue(extractDouble(text, "REVENUE\\s+([\\d,]+)"));
         app.setProfit(extractDouble(text, "PROFIT\\s*([\\d,]+)"));
         app.setCreditScore(extractInteger(text, "CREDIT SCORE\\s+(\\d+)"));
         app.setBankReference(extractString(text, "BANK REFERENCE\\s+(.+)"));
         
         //reputation
         app.setBusinessYears(extractInteger(text, "YEARS IN BUSINESS\\s+(.+)"));
         app.setNumComplaints(extractInteger(text, "NUMBER OF COMPLAINTS\\s+(\\d+)"));
         
         //regulatory compliance
         app.setHasValidLicenses(extractString(text, "LICENSES\\s+(.+)"));
         app.setLastAuditDate(extractString(text, "LAST AUDIT DATE\\s+(\\d{4}-\\d{2}-\\d{2})"));
         app.setHasPenalties(extractString(text, "PENALTIES\\s+(\\w+)"));
     }
     
     return app;
 }
 
private String extractString(String text, String pattern) {
	Pattern p = Pattern.compile(pattern);
	Matcher m = p.matcher(text);
	if (m.find()) {
        return m.group(1).trim();
    }
    return null;
}

private Double extractDouble(String text, String pattern) {
	String value = extractString(text, pattern);
    return Double.parseDouble(value);
}

private Integer extractInteger(String text, String pattern) {
	String value = extractString(text, pattern);
    return Integer.parseInt(value);
}

}