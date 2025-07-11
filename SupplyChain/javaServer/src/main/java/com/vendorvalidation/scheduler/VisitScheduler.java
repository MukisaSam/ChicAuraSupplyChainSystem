package com.vendorvalidation.scheduler;

import java.time.DayOfWeek;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.Random;

public class VisitScheduler {

 /**
  * Schedules a visit, e.g., 7 days from now at 10:00.
  * Returns the scheduled date-time as a formatted string.
  */
 public String scheduleVisit() {
     LocalDateTime now = LocalDateTime.now();
     
     // Generate a random visit hour between 9 AM and 2 PM
     Random rand = new Random();
     int visitHour = rand.nextInt(6) + 9;
     
     
     LocalDateTime initialDate = now.plusDays(7).withHour(visitHour).withMinute(0).withSecond(0).withNano(0);
     
     
     // Adjust to next Monday if the initial date is on a weekend
     LocalDateTime visitDate = initialDate;
     if (initialDate.getDayOfWeek() == DayOfWeek.SATURDAY) {
         visitDate = initialDate.plusDays(2);
     } else if (initialDate.getDayOfWeek() == DayOfWeek.SUNDAY) {
         visitDate = initialDate.plusDays(1);
     }
     
     
     // Format as ISO or human-readable:
     DateTimeFormatter formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm");
     return visitDate.format(formatter);
 }

}

