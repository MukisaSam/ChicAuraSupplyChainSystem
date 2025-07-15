package com.vendorvalidation.controller;

import com.vendorvalidation.model.VendorApplication;
import com.vendorvalidation.parser.PdfParser;
import com.vendorvalidation.scheduler.VisitScheduler;
import com.vendorvalidation.validation.ValidationService;
import com.vendorvalidation.validation.ValidationService.ValidationReport;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;

import java.io.InputStream;
import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/application")
public class ApplicationController {

 private final PdfParser pdfParser = new PdfParser();
 private final ValidationService validationService = new ValidationService();
 private final VisitScheduler visitScheduler = new VisitScheduler();

 @PostMapping(path = "/validate", consumes = MediaType.MULTIPART_FORM_DATA_VALUE)
 public ResponseEntity<Map<String, Object>> validateVendor(@RequestParam("file") MultipartFile file) {
     Map<String, Object> response = new HashMap<>();
     if (file.isEmpty()) {
         response.put("status", "error");
         response.put("message", "No file uploaded");
         return ResponseEntity.badRequest().body(response);
     }

     try (InputStream is = file.getInputStream()) {
         VendorApplication app = pdfParser.parse(is);

         
         // Validate:
         ValidationReport report = validationService.validateAll(app);
         if (!report.isValid()) {
             response.put("status", "error");
             response.put("message", "Validation failed");
             response.put("details", report.getDetails());
             return ResponseEntity.ok(response); // or 400? Here we choose 200 with status=error
         }

         // If passed, schedule a visit
         String scheduledDate = visitScheduler.scheduleVisit();
         response.put("status", "success");
         response.put("message", "Valid. Facility visit scheduled.");
         response.put("visitDate", scheduledDate);
         response.putAll(report.getRecords());
         return ResponseEntity.ok(response);

     } catch (Exception e) {
         e.printStackTrace();
         response.put("status", "error");
         response.put("message", "Failed to process PDF: " + e.getMessage());
         return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body(response);
     }
 }
}
