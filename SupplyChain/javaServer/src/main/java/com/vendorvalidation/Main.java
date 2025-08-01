package com.vendorvalidation;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.Bean;
import org.springframework.web.servlet.config.annotation.CorsRegistry;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurer;

@SpringBootApplication
public class Main {

	public static void main(String[] args) {
		SpringApplication.run(Main.class, args);
	}
	
	@Bean
    public WebMvcConfigurer corsConfigurer() {
        return new WebMvcConfigurer() {
            @Override
            public void addCorsMappings(CorsRegistry registry) {
                registry
                  .addMapping("/**")                          // apply to all endpoints
                  .allowedOrigins("http://localhost:8000")    // allow your Laravel front‑end
                  .allowedMethods("GET", "POST", "PUT", "DELETE", "OPTIONS") 
                  .allowCredentials(true);
            }
        };
    }

}

