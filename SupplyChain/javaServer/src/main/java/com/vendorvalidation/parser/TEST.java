package com.vendorvalidation.parser;

import java.io.FileInputStream;
import java.io.InputStream;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;


public class TEST {
	
	public static String value1,value2,value3,value4,value5,value6,value7,value8,value9,value10,value11,value12,value13,value20,value22,value23,value24,value25,value26,value27,value28;
	
	public static int value14,value15,value16,value17,value18,value19;
	public static double value21;

	public String parse(InputStream pdfInputStream) throws Exception {
		 String text;
		 
	     // Load PDF from InputStream
	     try (PDDocument document = PDDocument.load(pdfInputStream)) {
	         PDFTextStripper stripper = new PDFTextStripper();
	         text = stripper.getText(document);
	       
	     }
	     
	     return text;
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
	
	public static void main(String[] args) throws Exception {
		InputStream pdfile = new FileInputStream("C:/Users/HP PAVILION/Desktop/WHOLESALER APPLICATION FORM 1.2.pdf");
		String text;
		
		TEST t1 = new TEST();
		
		text = t1.parse(pdfile);
		  
	       //vendor  and contact info 
		
	         value1 = t1.extractString(text, "COMPANY / FIRM NAME\\s+(.+)\\s+BUSINESS EMAIL");
	         value2 = t1.extractString(text, "(.+)\\s*APPLICATION FORM").toLowerCase();
	         value3 = t1.extractString(text, "BUSINESS EMAIL\\s+(.+)\\s+ADDRESS");
	         value4 = t1.extractString(text, "ADDRESS\\s+(.+)\\s+PHONE NUMBER");
	         value5 = t1.extractString(text, "PHONE NUMBER\\s+(.+)\\s+DATE ESTABLISHED");
	         value6 = t1.extractString(text, "DATE ESTABLISHED\\s+(.+)");
	         value7 = t1.extractString(text, "URSB REGISTRATION NUMBER\\s+(.+)\\s+TAX IDENTIFICATION NUMBER");
	         value8 = t1.extractString(text, "TAX IDENTIFICATION NUMBER \\(TIN\\)\\s+(.+)\\s+TRADING LICENSE NUMBER");
	         value9 = t1.extractString(text, "TRADING LICENSE NUMBER\\s+(.+)\\s+CHICAURA CLOTHING");
	         value10 = t1.extractString(text, "TITLE\\s+(.+)\\s+EMAIL ADDRESS");
	         value11 = t1.extractString(text, "FULL NAME\\s+(.+)\\s+TITLE");
	         value12 = t1.extractString(text, "CONTACT NUMBER\\s+(.+)\\s+SECTION C");
	         value13 = t1.extractString(text, "EMAIL ADDRESS\\s+(.+)\\s+CONTACT NUMBER");
	         value14 = t1.extractInteger(text, "INVENTORY CAPACITY \\(warehouse sq. ft\\)\\s+(.+)\\s+URSB REGISTRATION NUMBER");
	         value15 = t1.extractInteger(text, "NET ASSETS\\s*([\\d,]+)\\s*ANNUAL TURNOVER");
	         value16 = t1.extractInteger(text, "MINIMUM ORDER VALUE \\(units\\)\\s*([\\d,]+)\\s*NUMBER OF EMPLOYEES");
	         value17 = t1.extractInteger(text, "MINIMUM SUPPLY QUANTITY\\s*([\\d,]+)\\s*NUMBER OF EMPLOYEES");
	         value18 = t1.extractInteger(text, "ANNUAL SALES VOLUME\\s*([\\d,]+)\\s*NUMBER OF RETAIL OUTLETS");
	         value19 = t1.extractInteger(text, "NUMBER OF RETAIL OUTLETS\\s*([\\d,]+)\\s*MINIMUM ORDER VALUE");
	         value20 = t1.extractString(text, "BANK REFERENCE\\s+(.+)\\s+SECTION E: REPUTATION AND REGULATORY COMPLAINCE");
	         value21 = t1.extractDouble(text, "ANNUAL TURNOVER\\s*([\\d,]+)\\s*BANK REFERENCE");
	         value22 = t1.extractString(text, "DATE ESTABLISHED\\s+(.+)");
	         value23 = t1.extractString(text, "COMPLAINTS\\s+(\\d+)\\s+SECTION F");
	         value24 = t1.extractString(text, "AWARDS\\s+(.+)\\s+COMPLAINTS");
	         value25 = t1.extractString(text, "NUMBER OF EMPLOYEES\\s+(.+)\\s+QUALITY STANDARDS & CERTIFICATIONS");
	         value26 = t1.extractString(text, "QUALITY STANDARDS & CERTIFICATIONS\\s+([\\s\\S]+?)\\s+CHICAURA CLOTHING");
	         value27 = t1.extractString(text, "MATERIAL SUPPLIED\\s+([\\s\\S]+?)\\s+LEAD TIME");
	         value28 = t1.extractString(text, "LEAD TIME \\(days\\)\\s+(\\w+)\\s+MINIMUM SUPPLY QUANTITY");
	         
	         
	          //System.out.println(text);
	          
	         
	         System.out.println("Value1:" +value1);
	         System.out.println("Value2:" +value2);
	         System.out.println("Value3:" +value3);
	         System.out.println("Value4:" +value4);
	         System.out.println("Value5:" +value5);
	         System.out.println("Value6:" +value6);
	         System.out.println("Value7:" +value7);
	         System.out.println("Value8:" +value8);
	         System.out.println("Value9:" +value9);
	         System.out.println("Value10:" +value10); 
	         System.out.println("Value11:" +value11); 
	         System.out.println("Value12:" +value12); 
	         System.out.println("Value13:" +value13); 
	         System.out.println("Value14(w):" +value14); 
	         System.out.println("Value15:" +value15); 
	         System.out.println("Value16(w):" +value16); 
	         System.out.println("Value17:" +value17); 
	         System.out.println("Value18(w):" +value18); 
	         System.out.println("Value19(w):" +value19); 
	         System.out.println("Value20:" +value20); 
	         System.out.println("Value21:" +value21); 
	         System.out.println("Value22:" +value22); 
	         System.out.println("Value23:" +value23); 
	         System.out.println("Value24:" +value24); 
	         System.out.println("Value25:" +value25); 
	         System.out.println("Value26:" +value26); 
	         System.out.println("Value27(w):" +value27); 
	         System.out.println("Value28:(w)" +value28); 
	         
	}
}
