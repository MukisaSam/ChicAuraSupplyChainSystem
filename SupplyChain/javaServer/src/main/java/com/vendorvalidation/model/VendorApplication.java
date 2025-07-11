package com.vendorvalidation.model;

public class VendorApplication {
	
	//Vendor info
	private String vendorName;
	private String vendorType;
	
	
	//Financial info
	private int totalAssets;
	private double revenue;
	private double profit;
	private int creditScore;
	private String bankReference;
	
	//Reputation
	private int businessYears;
	private int numComplaints;
	
	//Regulatory compliance
	private String hasValidLicenses;
	private String lastAuditDate;
	private String hasPenalties;
	
	
	//Getters and Setters
	public String getVendorName() {
		return vendorName;
	}
	public void setVendorName(String vendorName) {
		this.vendorName = vendorName;
	}
	public String getVendorType() {
		return vendorType;
	}
	public void setVendorType(String vendorType) {
		this.vendorType = vendorType;
	}
	public int getTotalAssets() {
		return totalAssets;
	}
	public void setTotalAssets(int totalAssets) {
		this.totalAssets = totalAssets;
	}
	public double getRevenue() {
		return revenue;
	}
	public void setRevenue(double revenue) {
		this.revenue = revenue;
	}
	public double getProfit() {
		return profit;
	}
	public void setProfit(double profit) {
		this.profit = profit;
	}
	public int getCreditScore() {
		return creditScore;
	}
	public void setCreditScore(int creditScore) {
		this.creditScore = creditScore;
	}
	public String getBankReference() {
		return bankReference;
	}
	public void setBankReference(String bankReference) {
		this.bankReference = bankReference;
	}
	public int getBusinessYears() {
		return businessYears;
	}
	public void setBusinessYears(int businessYears) {
		this.businessYears = businessYears;
	}
	public int getNumComplaints() {
		return numComplaints;
	}
	public void setNumComplaints(int numComplaints) {
		this.numComplaints = numComplaints;
	}
	public String getHasValidLicenses() {
		return hasValidLicenses;
	}
	public void setHasValidLicenses(String hasValidLicenses) {
		this.hasValidLicenses = hasValidLicenses;
	}
	public String getLastAuditDate() {
		return lastAuditDate;
	}
	public void setLastAuditDate(String lastAuditDate) {
		this.lastAuditDate = lastAuditDate;
	}
	public String getHasPenalties() {
		return hasPenalties;
	}
	public void setHasPenalties(String hasPenalties) {
		this.hasPenalties = hasPenalties;
	}
	

}