package com.vendorvalidation.parser;

import com.vendorvalidation.model.VendorApplication;
import net.sourceforge.tess4j.Tesseract;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.rendering.PDFRenderer;

import java.awt.image.BufferedImage;
import java.io.InputStream;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class PdfParser {
	
	//OCR
	private final Tesseract tesseract;
	public PdfParser() {
        tesseract = new Tesseract();
        tesseract.setLanguage("eng");
        tesseract.setDatapath("C:/Program Files/Tesseract-OCR/tessdata");
        
    }

 public VendorApplication parse(InputStream pdfInputStream) throws Exception {
	 VendorApplication app = new VendorApplication();
	 
     // Load PDF from InputStream
     try (PDDocument document = PDDocument.load(pdfInputStream)) {
    	 
    	 
    	 //Apache Box
    	 /*PDFTextStripper stripper = new PDFTextStripper();
         String text = stripper.getText(document);
    	 */
    	 
    	 
    	 PDFRenderer renderer = new PDFRenderer(document);
         StringBuilder textBuilder = new StringBuilder();

         for (int i = 0; i < document.getNumberOfPages(); i++) {
             BufferedImage image = renderer.renderImageWithDPI(i, 300);
             textBuilder.append(tesseract.doOCR(image)).append("\n");
         }

         String text = textBuilder.toString();

         
         //vendor  and contact info
         app.setCompanyName(extractString(text, "COMPANY / FIRM NAME\\s+(.+)\\s+BUSINESS EMAIL"));
         app.setVendorType(extractString(text, "(.+)\\s*APPLICATION FORM").toLowerCase());
         app.setCompanyEmail(extractString(text, "BUSINESS EMAIL\\s+(.+)\\s+ADDRESS"));
         app.setCompanyAddress(extractString(text, "ADDRESS\\s+(.+)\\s+PHONE NUMBER"));
         app.setCompanyPhone(extractString(text, "PHONE NUMBER\\s+(.+)\\s+DATE ESTABLISHED"));
         app.setDateEstablished(extractString(text, "DATE ESTABLISHED\\s+(.+)"));
         app.setURSBNo(extractString(text, "URSB REGISTRATION NUMBER\\s+(.+)\\s+TAX IDENTIFICATION NUMBER"));
         app.setTIN(extractString(text, "TAX IDENTIFICATION NUMBER \\(TIN\\)\\s+(.+)\\s+TRADING LICENSE NUMBER"));
         app.setLicenseNo(extractString(text, "TRADING LICENSE NUMBER\\s+(.+)\\s+CHICAURA CLOTHING"));
         app.setContactTitle(extractString(text, "TITLE\\s+(.+)\\s+EMAIL ADDRESS"));
         app.setContactName(extractString(text, "FULL NAME\\s+(.+)\\s+TITLE"));
         app.setContactNo(extractString(text, "CONTACT NUMBER\\s+(.+)\\s+SECTION C"));
         app.setContactEmail(extractString(text, "EMAIL ADDRESS\\s+(.+)\\s+CONTACT NUMBER"));
         app.setInventoryCapacity(extractInteger(text, "INVENTORY CAPACITY \\(warehouse sq. ft\\)\\s+(.+)\\s+URSB REGISTRATION NUMBER"));
         
         //financial info
         app.setTotalAssets(extractDouble(text, "NET ASSETS\\s*([\\d,]+)\\s*ANNUAL TURNOVER"));
         app.setMinOrderVolume(extractInteger(text, "MINIMUM ORDER VALUE \\(units\\)\\s*([\\d,]+)\\s*NUMBER OF EMPLOYEES"));
         app.setMinSupplyQuality(extractInteger(text, "MINIMUM SUPPLY QUANTITY\\s*([\\d,]+)\\s*NUMBER OF EMPLOYEES"));
         app.setSalesVolume(extractInteger(text, "ANNUAL SALES VOLUME\\s*([\\d,]+)\\s*NUMBER OF RETAIL OUTLETS"));
         app.setRetailerOutletNo(extractInteger(text, "NUMBER OF RETAIL OUTLETS\\s*([\\d,]+)\\s*MINIMUM ORDER VALUE"));
         app.setBankReference(extractString(text, "BANK REFERENCE\\s+(.+)\\s+SECTION E: REPUTATION AND REGULATORY COMPLAINCE"));
         app.setAnnualTurnover(extractDouble(text, "ANNUAL TURNOVER\\s*([\\d,]+)\\s*BANK REFERENCE"));
         
         //reputation
         app.setDateEstablished(extractString(text, "DATE ESTABLISHED\\s+(.+)"));
         app.setNumComplaints(extractInteger(text, "COMPLAINTS\\s+(\\d+)\\s+SECTION F"));
         app.setAwards(extractString(text, "AWARDS\\s+(.+)\\s+COMPLAINTS"));
         app.setEmployeeNo(extractInteger(text, "NUMBER OF EMPLOYEES\\s+(.+)\\s+QUALITY STANDARDS & CERTIFICATIONS"));
         
         //regulatory compliance
         app.setQualityLicenses(extractString(text, "QUALITY STANDARDS & CERTIFICATIONS\\s+([\\s\\S]+?)\\s+CHICAURA CLOTHING"));
         app.setMaterialSupplied(extractString(text, "MATERIAL SUPPLIED\\s+([\\s\\S]+?)\\s+LEAD TIME"));
         app.setLeadTimes(extractString(text, "LEAD TIME \\(days\\)\\s+(\\w+)\\s+MINIMUM SUPPLY QUANTITY"));
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
	if(value == null) {
		return Double.parseDouble("0");
	}
	value = value.replaceAll(",", "");
    return Double.parseDouble(value);
}

private Integer extractInteger(String text, String pattern) {
	String value = extractString(text, pattern);
	if(value == null) {
		return Integer.parseInt("0");
	}
	value = value.replaceAll(",", "");
    return Integer.parseInt(value);
}

}