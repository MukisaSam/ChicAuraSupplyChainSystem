-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 06:40 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supply`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_of_materials`
--

CREATE TABLE `bill_of_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_of_material_components`
--

CREATE TABLE `bill_of_material_components` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bom_id` bigint(20) UNSIGNED NOT NULL,
  `raw_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `message_type` enum('text','file','image') NOT NULL DEFAULT 'text',
  `file_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `age_group` enum('18-25','26-35','36-45','46-55','56-65','65+') DEFAULT NULL,
  `gender` enum('male','female','other','prefer-not-to-say') DEFAULT NULL,
  `income_bracket` enum('low','middle-low','middle','middle-high','high','prefer-not-to-say') DEFAULT NULL,
  `shopping_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`shopping_preferences`)),
  `purchase_frequency` enum('weekly','monthly','quarterly','yearly','occasional') DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_seen` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `city`, `address`, `age_group`, `gender`, `income_bracket`, `shopping_preferences`, `purchase_frequency`, `is_active`, `remember_token`, `created_at`, `updated_at`, `last_seen`) VALUES
(1, 'Nakato Jennifer', 'jennifer.nakato@gmail.com', '2023-01-15 07:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256772345678', 'Kampala', 'Ntinda, Stretcher Road', '26-35', 'female', 'middle', '{\"preferred_categories\": [\"fashion_wear\", \"office_wear\", \"accessories\"], \"brand_conscious\": true, \"price_sensitivity\": \"medium\", \"preferred_fabrics\": [\"cotton\", \"silk_blend\"], \"shopping_time\": \"evenings\", \"online_shopping\": true}', 'monthly', 1, NULL, '2023-01-15 07:00:00', '2025-07-18 08:08:06', '2025-07-18 08:08:06'),
(2, 'Okello David', 'david.okello@yahoo.com', '2023-01-20 11:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256701234567', 'Gulu', 'Layibi Division', '18-25', 'male', 'middle-low', '{\"preferred_categories\": [\"casual_wear\", \"traditional_wear\"], \"brand_conscious\": false, \"price_sensitivity\": \"high\", \"preferred_fabrics\": [\"cotton\", \"polyester\"], \"shopping_time\": \"weekends\", \"bulk_buyer\": true}', 'quarterly', 1, NULL, '2023-01-20 10:30:00', '2025-07-16 19:00:00', '2025-07-16 19:00:00'),
(3, 'Namusisi Grace', 'grace.namusisi@outlook.com', '2023-02-01 06:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256759876543', 'Mbarara', 'Kakoba Division', '36-45', 'female', 'middle-high', '{\"preferred_categories\": [\"home_textiles\", \"children_wear\", \"fashion_wear\"], \"brand_conscious\": true, \"price_sensitivity\": \"low\", \"preferred_fabrics\": [\"organic_cotton\", \"linen\"], \"shopping_time\": \"mornings\", \"family_shopper\": true}', 'monthly', 1, NULL, '2023-02-01 05:30:00', '2025-07-16 22:30:00', '2025-07-16 22:30:00'),
(4, 'Mugisha Robert', 'robert.mugisha@gmail.com', '2023-02-10 08:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256782456789', 'Kampala', 'Kololo, Upper Naguru', '46-55', 'male', 'high', '{\"preferred_categories\": [\"luxury_wear\", \"business_suits\", \"premium_home_textiles\"], \"brand_conscious\": true, \"price_sensitivity\": \"very_low\", \"preferred_fabrics\": [\"silk\", \"premium_cotton\", \"wool\"], \"custom_tailoring\": true}', 'weekly', 1, NULL, '2023-02-10 08:00:00', '2025-07-18 07:49:29', '2025-07-18 07:49:29'),
(5, 'Achieng Florence', 'florence.achieng@gmail.com', '2023-02-15 13:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256703987654', 'Jinja', 'Main Street', '26-35', 'female', 'middle', '{\"preferred_categories\": [\"fashion_wear\", \"sportswear\", \"casual_wear\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"preferred_fabrics\": [\"cotton_blend\", \"polyester\"], \"fitness_enthusiast\": true}', 'monthly', 1, NULL, '2023-02-15 12:30:00', '2025-07-16 11:15:00', '2025-07-16 11:15:00'),
(6, 'Byaruhanga Peter', 'peter.byaruhanga@hotmail.com', '2023-03-01 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256788654321', 'Fort Portal', 'Buhinga', '56-65', 'male', 'middle', '{\"preferred_categories\": [\"traditional_wear\", \"casual_wear\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"preferred_fabrics\": [\"cotton\", \"bark_cloth\"], \"cultural_enthusiast\": true}', 'occasional', 1, NULL, '2023-03-01 06:30:00', '2025-07-16 23:00:00', '2025-07-16 23:00:00'),
(7, 'Nabirye Fatuma', 'fatuma.nabirye@gmail.com', '2023-03-10 09:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256771234890', 'Mbale', 'Wanale', '18-25', 'female', 'low', '{\"preferred_categories\": [\"budget_wear\", \"second_hand\", \"basic_essentials\"], \"brand_conscious\": false, \"price_sensitivity\": \"very_high\", \"preferred_fabrics\": [\"polyester\", \"cotton_blend\"], \"student\": true}', 'occasional', 1, NULL, '2023-03-10 08:30:00', '2025-07-18 07:58:17', '2025-07-18 07:58:17'),
(8, 'Ssemanda Richard', 'richard.ssemanda@gmail.com', '2023-03-15 05:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256757890123', 'Wakiso', 'Nansana', '36-45', 'male', 'middle', '{\"preferred_categories\": [\"children_wear\", \"school_uniforms\", \"family_clothing\"], \"brand_conscious\": false, \"price_sensitivity\": \"high\", \"bulk_purchases\": true, \"school_parent\": true}', 'quarterly', 1, NULL, '2023-03-15 05:00:00', '2025-07-16 17:45:00', '2025-07-16 17:45:00'),
(9, 'Atim Betty', 'betty.atim@yahoo.com', '2023-03-20 12:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256702345678', 'Lira', 'Senior Quarters', '46-55', 'female', 'middle-low', '{\"preferred_categories\": [\"traditional_wear\", \"church_wear\", \"modest_fashion\"], \"brand_conscious\": false, \"price_sensitivity\": \"high\", \"preferred_fabrics\": [\"cotton\", \"kitenge\"], \"religious_dresser\": true}', 'monthly', 1, NULL, '2023-03-20 11:30:00', '2025-07-16 21:15:00', '2025-07-16 21:15:00'),
(10, 'Kato Joseph', 'joseph.kato@gmail.com', NULL, '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256704567890', 'Kampala', 'Kawempe', '26-35', 'male', 'middle-low', '{\"preferred_categories\": [\"casual_wear\", \"work_uniforms\"], \"brand_conscious\": false, \"price_sensitivity\": \"high\", \"preferred_fabrics\": [\"cotton\", \"polyester\"], \"industrial_worker\": true}', 'quarterly', 1, NULL, '2023-04-01 07:00:00', '2025-07-15 13:00:00', '2025-07-15 13:00:00'),
(11, 'Nalubega Prossy', 'prossy.nalubega@gmail.com', '2023-04-10 08:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256778901234', 'Entebbe', 'Kitoro', '36-45', 'female', 'high', '{\"preferred_categories\": [\"luxury_fashion\", \"designer_wear\", \"imported_fabrics\"], \"brand_conscious\": true, \"price_sensitivity\": \"very_low\", \"preferred_fabrics\": [\"silk\", \"cashmere\", \"designer_cotton\"], \"fashion_influencer\": true}', 'weekly', 1, NULL, '2023-04-10 07:30:00', '2025-07-13 09:30:00', '2025-07-13 09:30:00'),
(12, 'Odongo Charles', 'charles.odongo@outlook.com', '2023-04-15 11:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256789012345', 'Arua', 'Oli Division', '56-65', 'male', 'middle', '{\"preferred_categories\": [\"traditional_wear\", \"casual_wear\", \"cultural_items\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"cross_border_shopper\": true}', 'monthly', 1, NULL, '2023-04-15 10:30:00', '2025-07-16 05:20:00', '2025-07-16 05:20:00'),
(13, 'Namanya Catherine', 'catherine.namanya@gmail.com', '2023-05-01 06:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256756234567', 'Kampala', 'Wandegeya', '26-35', 'female', 'middle', '{\"preferred_categories\": [\"office_wear\", \"fashion_accessories\", \"weekend_wear\"], \"brand_conscious\": true, \"price_sensitivity\": \"medium\", \"career_focused\": true}', 'monthly', 1, NULL, '2023-05-01 05:30:00', '2025-07-17 00:15:00', '2025-07-17 00:15:00'),
(14, 'Tumusiime Emmanuel', 'emmanuel.tumusiime@gmail.com', '2023-05-10 13:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256789234567', 'Kabale', 'Makanga', '36-45', 'male', 'middle-high', '{\"preferred_categories\": [\"business_wear\", \"casual_friday\", \"outdoor_wear\"], \"brand_conscious\": true, \"price_sensitivity\": \"low\", \"preferred_fabrics\": [\"wool_blend\", \"premium_cotton\"], \"climate_conscious\": true}', 'monthly', 1, NULL, '2023-05-10 12:30:00', '2025-07-16 16:45:00', '2025-07-16 16:45:00'),
(15, 'Auma Josephine', 'josephine.auma@yahoo.com', '2023-05-20 07:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256702567890', 'Soroti', 'Opuyo', '46-55', 'female', 'low', '{\"preferred_categories\": [\"basic_wear\", \"market_clothes\", \"agricultural_wear\"], \"brand_conscious\": false, \"price_sensitivity\": \"very_high\", \"farmer\": true, \"bulk_buyer_groups\": true}', 'quarterly', 1, NULL, '2023-05-20 07:00:00', '2025-07-15 11:30:00', '2025-07-15 11:30:00'),
(16, 'Kaweesi Michael', 'michael.kaweesi@gmail.com', '2023-06-01 08:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256783456789', 'Kampala', 'Namugongo', '18-25', 'male', 'middle-low', '{\"preferred_categories\": [\"trendy_wear\", \"urban_fashion\", \"sneaker_culture\"], \"brand_conscious\": true, \"price_sensitivity\": \"medium\", \"social_media_influenced\": true}', 'monthly', 1, NULL, '2023-06-01 07:30:00', '2025-07-16 22:00:00', '2025-07-16 22:00:00'),
(17, 'Nalwanga Harriet', 'harriet.nalwanga@gmail.com', '2023-06-10 11:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256758012345', 'Masaka', 'Nyendo', '36-45', 'female', 'middle', '{\"preferred_categories\": [\"church_wear\", \"wedding_guest\", \"cultural_events\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"event_dresser\": true}', 'occasional', 1, NULL, '2023-06-10 10:30:00', '2025-07-16 19:30:00', '2025-07-16 19:30:00'),
(18, 'Opolot Simon', 'simon.opolot@hotmail.com', '2023-06-20 06:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256701890123', 'Tororo', 'Western Division', '26-35', 'male', 'middle', '{\"preferred_categories\": [\"sportswear\", \"gym_wear\", \"athletic_shoes\"], \"brand_conscious\": true, \"price_sensitivity\": \"medium\", \"fitness_enthusiast\": true}', 'monthly', 1, NULL, '2023-06-20 05:30:00', '2025-07-17 01:00:00', '2025-07-17 01:00:00'),
(19, 'Kyagulanyi Moses', 'moses.kyagulanyi@gmail.com', '2023-07-01 09:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256778345678', 'Kampala', 'Rubaga', '36-45', 'male', 'middle-high', '{\"preferred_categories\": [\"designer_wear\", \"african_prints\", \"custom_tailoring\"], \"brand_conscious\": true, \"price_sensitivity\": \"low\", \"supports_local_designers\": true}', 'weekly', 1, NULL, '2023-07-01 08:30:00', '2025-07-16 13:45:00', '2025-07-16 13:45:00'),
(20, 'Nakimuli Agnes', 'agnes.nakimuli@gmail.com', '2023-07-10 12:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256704901234', 'Kampala', 'Makindye', '65+', 'female', 'middle', '{\"preferred_categories\": [\"comfort_wear\", \"traditional_dress\", \"modest_fashion\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"senior_citizen\": true}', 'occasional', 0, NULL, '2023-07-10 11:30:00', '2024-12-31 14:00:00', '2024-12-31 14:00:00'),
(21, 'Mutebi David', 'david.mutebi@gmail.com', '2023-07-20 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256775123456', 'Mukono', 'Seeta', '26-35', 'male', 'middle', '{\"preferred_categories\": [\"casual_wear\", \"office_casual\", \"weekend_wear\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"suburban_lifestyle\": true}', 'monthly', 1, NULL, '2023-07-20 06:30:00', '2025-07-14 10:20:00', '2025-07-14 10:20:00'),
(22, 'Nassozi Grace', 'grace.nassozi@yahoo.com', '2023-08-01 08:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256782456123', 'Kampala', 'Bukoto', '36-45', 'female', 'high', '{\"preferred_categories\": [\"luxury_fashion\", \"international_brands\", \"seasonal_collections\"], \"brand_conscious\": true, \"price_sensitivity\": \"very_low\", \"travels_abroad\": true}', 'weekly', 1, NULL, '2023-08-01 08:00:00', '2025-07-16 23:30:00', '2025-07-16 23:30:00'),
(23, 'Okot Geoffrey', 'geoffrey.okot@gmail.com', NULL, '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256701345678', 'Gulu', 'Bardege', '46-55', 'male', 'middle-low', '{\"preferred_categories\": [\"work_clothes\", \"durable_wear\", \"value_for_money\"], \"brand_conscious\": false, \"price_sensitivity\": \"high\", \"practical_buyer\": true}', 'quarterly', 1, NULL, '2023-08-10 05:00:00', '2025-07-15 06:00:00', '2025-07-15 06:00:00'),
(24, 'Namale Susan', 'susan.namale@gmail.com', '2023-08-20 11:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256759012678', 'Jinja', 'Bugembe', '26-35', 'female', 'middle', '{\"preferred_categories\": [\"maternity_wear\", \"baby_clothes\", \"nursing_friendly\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"new_mother\": true}', 'monthly', 1, NULL, '2023-08-20 10:30:00', '2025-07-18 08:00:39', '2025-07-18 08:00:39'),
(25, 'Bwambale Isaac', 'isaac.bwambale@outlook.com', '2023-09-01 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256771890456', 'Kasese', 'Central Division', '36-45', 'male', 'low', '{\"preferred_categories\": [\"work_uniforms\", \"protective_wear\", \"durable_basics\"], \"brand_conscious\": false, \"price_sensitivity\": \"very_high\", \"mining_sector\": true}', 'quarterly', 1, NULL, '2023-09-01 06:30:00', '2025-07-13 15:30:00', '2025-07-13 15:30:00'),
(26, 'Akello Sharon', 'sharon.akello@gmail.com', '2023-09-10 09:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256703456890', 'Mbarara', 'Kamukuzi', '18-25', 'female', 'middle-low', '{\"preferred_categories\": [\"campus_fashion\", \"party_wear\", \"trendy_accessories\"], \"brand_conscious\": true, \"price_sensitivity\": \"medium\", \"university_student\": true}', 'monthly', 1, NULL, '2023-09-10 08:30:00', '2025-07-16 21:45:00', '2025-07-16 21:45:00'),
(27, 'Mugabe John', 'john.mugabe@gmail.com', '2023-09-20 12:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256705678901', 'Kampala', 'Kyambogo', '46-55', 'male', 'middle-high', '{\"preferred_categories\": [\"golf_wear\", \"country_club\", \"premium_casual\"], \"brand_conscious\": true, \"price_sensitivity\": \"low\", \"recreational_shopper\": true}', 'monthly', 1, NULL, '2023-09-20 11:30:00', '2025-07-16 02:20:00', '2025-07-16 02:20:00'),
(28, 'Nansubuga Olivia', 'olivia.nansubuga@gmail.com', '2023-10-01 06:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256788567890', 'Kampala', 'Nakasero', '26-35', 'female', 'high', '{\"preferred_categories\": [\"corporate_fashion\", \"power_dressing\", \"luxury_accessories\"], \"brand_conscious\": true, \"price_sensitivity\": \"very_low\", \"c_suite_executive\": true}', 'weekly', 1, NULL, '2023-10-01 06:00:00', '2025-07-15 18:00:00', '2025-07-15 18:00:00'),
(29, 'Kasozi Patrick', 'patrick.kasozi@yahoo.com', '2023-10-10 08:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256702890456', 'Hoima', 'Bujumbura', '36-45', 'male', 'middle-high', '{\"preferred_categories\": [\"business_casual\", \"oil_sector_appropriate\", \"quality_basics\"], \"brand_conscious\": false, \"price_sensitivity\": \"low\", \"oil_industry_employee\": true}', 'monthly', 1, NULL, '2023-10-10 07:30:00', '2025-07-14 04:45:00', '2025-07-14 04:45:00'),
(30, 'Kemigisa Juliet', 'juliet.kemigisa@gmail.com', '2023-10-20 11:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256774567123', 'Fort Portal', 'Kabarole', '56-65', 'female', 'middle', '{\"preferred_categories\": [\"comfort_wear\", \"garden_clothes\", \"practical_fashion\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"retiree\": true}', 'occasional', 1, NULL, '2023-10-20 10:30:00', '2025-07-16 14:30:00', '2025-07-16 14:30:00'),
(31, 'Nsubuga Daniel', 'daniel.nsubuga@gmail.com', '2023-11-01 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256701234890', 'Kampala', 'Bwaise', '26-35', 'male', 'low', '{\"preferred_categories\": [\"second_hand\", \"budget_wear\", \"work_clothes\"], \"brand_conscious\": false, \"price_sensitivity\": \"very_high\", \"informal_sector\": true}', 'occasional', 1, NULL, '2023-11-01 06:30:00', '2025-07-12 12:15:00', '2025-07-12 12:15:00'),
(32, 'Ayebare Patience', 'patience.ayebare@gmail.com', '2023-11-10 09:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256788234567', 'Kabale', 'Northern Division', '36-45', 'female', 'middle', '{\"preferred_categories\": [\"warm_clothing\", \"sweaters\", \"seasonal_wear\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"climate_appropriate\": true}', 'quarterly', 1, NULL, '2023-11-10 08:30:00', '2025-07-17 00:45:00', '2025-07-17 00:45:00'),
(33, 'Ochieng Brian', 'brian.ochieng@hotmail.com', '2023-11-20 05:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256775890123', 'Tororo', 'Eastern Division', '18-25', 'male', 'middle-low', '{\"preferred_categories\": [\"sports_jerseys\", \"team_merchandise\", \"casual_wear\"], \"brand_conscious\": true, \"price_sensitivity\": \"high\", \"sports_fan\": true}', 'monthly', 1, NULL, '2023-11-20 05:00:00', '2025-07-10 09:00:00', '2025-07-10 09:00:00'),
(34, 'Nakabuye Stella', 'stella.nakabuye@gmail.com', '2023-12-01 11:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256703456123', 'Entebbe', 'Central Division', '46-55', 'female', 'high', '{\"preferred_categories\": [\"resort_wear\", \"vacation_clothes\", \"swimwear\"], \"brand_conscious\": true, \"price_sensitivity\": \"low\", \"frequent_traveler\": true}', 'monthly', 1, NULL, '2023-12-01 10:30:00', '2025-07-16 11:00:00', '2025-07-16 11:00:00'),
(35, 'Mwesigye Albert', 'albert.mwesigye@gmail.com', '2023-12-10 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256782345890', 'Mbarara', 'Nyamitanga', '36-45', 'male', 'middle', '{\"preferred_categories\": [\"smart_casual\", \"church_appropriate\", \"family_events\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"community_leader\": true}', 'quarterly', 1, NULL, '2023-12-10 06:30:00', '2025-07-15 05:45:00', '2025-07-15 05:45:00'),
(36, 'Nalongo Sarah', 'sarah.nalongo@yahoo.com', '2023-12-20 08:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256759876123', 'Kampala', 'Nateete', '36-45', 'female', 'middle-low', '{\"preferred_categories\": [\"children_bulk\", \"school_uniforms\", \"twins_clothing\"], \"brand_conscious\": false, \"price_sensitivity\": \"high\", \"mother_of_twins\": true}', 'monthly', 1, NULL, '2023-12-20 08:00:00', '2025-07-16 23:15:00', '2025-07-16 23:15:00'),
(37, 'Ojok Peter', 'peter.ojok@gmail.com', '2024-01-05 06:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256771234567', 'Lira', 'Railway', '26-35', 'male', 'middle', '{\"preferred_categories\": [\"modern_traditional\", \"cultural_fusion\", \"african_prints\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"cultural_ambassador\": true}', 'monthly', 1, NULL, '2024-01-05 05:30:00', '2025-07-18 08:08:32', '2025-07-18 08:08:32'),
(38, 'Namara Diana', 'diana.namara@gmail.com', '2024-01-15 11:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256704567234', 'Kampala', 'Ntinda', '26-35', 'female', 'middle-high', '{\"preferred_categories\": [\"yoga_wear\", \"athleisure\", \"wellness_fashion\"], \"brand_conscious\": true, \"price_sensitivity\": \"low\", \"wellness_instructor\": true}', 'weekly', 1, NULL, '2024-01-15 10:30:00', '2025-07-17 01:00:00', '2025-07-17 01:00:00'),
(39, 'Ssekito Ronald', 'ronald.ssekito@outlook.com', '2024-01-25 07:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256788901567', 'Masaka', 'Kimanya', '46-55', 'male', 'middle', '{\"preferred_categories\": [\"classic_styles\", \"timeless_fashion\", \"quality_basics\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"value_shopper\": true}', 'quarterly', 1, NULL, '2024-01-25 07:00:00', '2025-07-15 16:00:00', '2025-07-15 16:00:00'),
(40, 'Akurut Mary', 'mary.akurut@gmail.com', '2024-02-01 09:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256702345901', 'Soroti', 'Arapai', '56-65', 'female', 'low', '{\"preferred_categories\": [\"practical_wear\", \"market_day_clothes\", \"agricultural_appropriate\"], \"brand_conscious\": false, \"price_sensitivity\": \"very_high\", \"rural_trader\": true}', 'occasional', 1, NULL, '2024-02-01 08:30:00', '2025-07-14 07:20:00', '2025-07-14 07:20:00'),
(41, 'Babirye Joan', 'joan.babirye@gmail.com', '2024-06-15 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256775678234', 'Kampala', 'Kansanga', '26-35', 'female', 'middle', '{\"preferred_categories\": [\"sustainable_fashion\", \"eco_friendly\", \"ethical_brands\"], \"brand_conscious\": true, \"price_sensitivity\": \"medium\", \"environmental_conscious\": true}', 'monthly', 1, NULL, '2024-06-15 06:30:00', '2025-07-16 22:45:00', '2025-07-16 22:45:00'),
(42, 'Waiswa James', 'james.waiswa@gmail.com', '2024-07-20 11:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256701890234', 'Jinja', 'Walukuba', '36-45', 'male', 'middle-high', '{\"preferred_categories\": [\"business_formal\", \"conference_wear\", \"professional_attire\"], \"brand_conscious\": true, \"price_sensitivity\": \"low\", \"consultant\": true}', 'monthly', 1, NULL, '2024-07-20 10:30:00', '2025-07-16 15:00:00', '2025-07-16 15:00:00'),
(43, 'Nakayiza Ruth', 'ruth.nakayiza@yahoo.com', '2024-08-10 08:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256759012890', 'Wakiso', 'Nsangi', '46-55', 'female', 'middle', '{\"preferred_categories\": [\"church_choir_uniforms\", \"group_orders\", \"event_clothing\"], \"brand_conscious\": false, \"price_sensitivity\": \"high\", \"choir_coordinator\": true, \"bulk_order_organizer\": true}', 'quarterly', 1, NULL, '2024-08-10 07:30:00', '2025-07-16 10:15:00', '2025-07-16 10:15:00'),
(44, 'Okello Samuel', 'samuel.okello@gmail.com', '2024-09-05 06:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256788345901', 'Gulu', 'Pece', '18-25', 'male', 'low', '{\"preferred_categories\": [\"student_fashion\", \"budget_trendy\", \"campus_wear\"], \"brand_conscious\": true, \"price_sensitivity\": \"very_high\", \"university_student\": true, \"part_time_worker\": true}', 'occasional', 1, NULL, '2024-09-05 05:30:00', '2025-07-15 08:45:00', '2025-07-15 08:45:00'),
(45, 'Namukasa Gladys', 'gladys.namukasa@gmail.com', '2024-10-12 12:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256703890567', 'Kampala', 'Nansana', '36-45', 'female', 'middle-low', '{\"preferred_categories\": [\"plus_size_fashion\", \"comfortable_fits\", \"inclusive_sizing\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"size_inclusive_shopper\": true}', 'monthly', 1, NULL, '2024-10-12 11:30:00', '2025-07-16 21:00:00', '2025-07-16 21:00:00'),
(46, 'Mugisha Fred', 'fred.mugisha@outlook.com', '2024-11-20 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256772890456', 'Mbarara', 'Ruharo', '26-35', 'male', 'middle-high', '{\"preferred_categories\": [\"tech_wear\", \"smart_clothing\", \"innovative_fabrics\"], \"brand_conscious\": true, \"price_sensitivity\": \"low\", \"tech_professional\": true, \"early_adopter\": true}', 'monthly', 1, NULL, '2024-11-20 06:30:00', '2025-07-16 06:30:00', '2025-07-16 06:30:00'),
(47, 'Atuhaire Peace', 'peace.atuhaire@gmail.com', '2024-12-15 09:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256701567890', 'Fort Portal', 'South Division', '36-45', 'female', 'middle', '{\"preferred_categories\": [\"teacher_appropriate\", \"modest_professional\", \"school_events\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"educator\": true}', 'quarterly', 1, NULL, '2024-12-15 08:30:00', '2025-07-15 19:45:00', '2025-07-15 19:45:00'),
(48, 'Kizza Robert', 'robert.kizza@gmail.com', '2025-01-10 06:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256759234678', 'Kampala', 'Kisaasi', '46-55', 'male', 'high', '{\"preferred_categories\": [\"golf_apparel\", \"country_club_wear\", \"premium_sportswear\"], \"brand_conscious\": true, \"price_sensitivity\": \"very_low\", \"golf_club_member\": true}', 'weekly', 1, NULL, '2025-01-10 06:00:00', '2025-07-17 00:00:00', '2025-07-17 00:00:00'),
(49, 'Nabukenya Esther', 'esther.nabukenya@yahoo.com', '2025-02-20 11:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256788012345', 'Mbale', 'Namatala', '26-35', 'female', 'middle-low', '{\"preferred_categories\": [\"market_vendor_wear\", \"durable_clothing\", \"weather_appropriate\"], \"brand_conscious\": false, \"price_sensitivity\": \"high\", \"market_vendor\": true}', 'quarterly', 1, NULL, '2025-02-20 10:30:00', '2025-07-16 04:20:00', '2025-07-16 04:20:00'),
(50, 'Mutama Kenneth', 'kenneth.mutama@gmail.com', '2025-03-15 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256704890123', 'Hoima', 'Mparo', '36-45', 'male', 'high', '{\"preferred_categories\": [\"executive_wear\", \"oil_sector_appropriate\", \"international_brands\"], \"brand_conscious\": true, \"price_sensitivity\": \"very_low\", \"expatriate\": true}', 'weekly', 1, NULL, '2025-03-15 06:30:00', '2025-07-16 20:50:00', '2025-07-16 20:50:00'),
(51, 'Achan Betty', 'betty.achan@gmail.com', '2025-04-01 08:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256775012890', 'Arua', 'Ayivu', '56-65', 'female', 'middle-low', '{\"preferred_categories\": [\"traditional_modern_mix\", \"comfortable_age_appropriate\", \"cultural_preservation\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"cultural_group_member\": true}', 'occasional', 1, NULL, '2025-04-01 08:00:00', '2025-07-15 13:30:00', '2025-07-15 13:30:00'),
(52, 'Ssenyonga Paul', 'paul.ssenyonga@gmail.com', '2025-04-20 05:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256702678901', 'Kampala', 'Namirembe', '26-35', 'male', 'middle', '{\"preferred_categories\": [\"musician_fashion\", \"stage_wear\", \"unique_styles\"], \"brand_conscious\": true, \"price_sensitivity\": \"medium\", \"performing_artist\": true}', 'monthly', 1, NULL, '2025-04-20 04:30:00', '2025-07-16 23:45:00', '2025-07-16 23:45:00'),
(53, 'Nakubulwa Joyce', 'joyce.nakubulwa@gmail.com', '2025-05-10 10:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256788890456', 'Wakiso', 'Busabala', '36-45', 'female', 'high', '{\"preferred_categories\": [\"beach_resort_wear\", \"luxury_casual\", \"designer_swimwear\"], \"brand_conscious\": true, \"price_sensitivity\": \"very_low\", \"beach_property_owner\": true}', 'monthly', 1, NULL, '2025-05-10 09:30:00', '2025-07-16 16:00:00', '2025-07-16 16:00:00'),
(54, 'Opio Martin', 'martin.opio@hotmail.com', '2025-05-25 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256701234012', 'Lira', 'Adyel', '18-25', 'male', 'low', '{\"preferred_categories\": [\"youth_fashion\", \"street_wear\", \"affordable_trends\"], \"brand_conscious\": true, \"price_sensitivity\": \"very_high\", \"fresh_graduate\": true}', 'occasional', 1, NULL, '2025-05-25 06:30:00', '2025-07-15 11:00:00', '2025-07-15 11:00:00'),
(55, 'Nankabirwa Gloria', 'gloria.nankabirwa@gmail.com', '2025-06-05 12:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256759345012', 'Kampala', 'Munyonyo', '46-55', 'female', 'high', '{\"preferred_categories\": [\"gala_wear\", \"charity_event_fashion\", \"exclusive_designs\"], \"brand_conscious\": true, \"price_sensitivity\": \"very_low\", \"philanthropist\": true}', 'weekly', 1, NULL, '2025-06-05 11:30:00', '2025-07-16 22:15:00', '2025-07-16 22:15:00'),
(56, 'Kasule Ivan', 'ivan.kasule@gmail.com', '2025-06-15 06:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256772012345', 'Mukono', 'Central Division', '36-45', 'male', 'middle', '{\"preferred_categories\": [\"smart_casual\", \"weekend_family\", \"church_appropriate\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"family_man\": true}', 'monthly', 1, NULL, '2025-06-15 05:30:00', '2025-07-16 08:00:00', '2025-07-16 08:00:00'),
(57, 'Amoding Sarah', 'sarah.amoding@gmail.com', '2025-06-25 09:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256703012678', 'Soroti', 'Central', '26-35', 'female', 'middle-low', '{\"preferred_categories\": [\"office_budget\", \"mix_and_match\", \"versatile_pieces\"], \"brand_conscious\": false, \"price_sensitivity\": \"high\", \"government_employee\": true}', 'monthly', 1, NULL, '2025-06-25 08:30:00', '2025-07-15 17:15:00', '2025-07-15 17:15:00'),
(58, 'Barigye Moses', 'moses.barigye@outlook.com', '2025-07-01 07:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256788234012', 'Kabale', 'Central', '56-65', 'male', 'middle', '{\"preferred_categories\": [\"retirement_comfort\", \"travel_wear\", \"grandparent_appropriate\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"retired_civil_servant\": true}', 'occasional', 1, NULL, '2025-07-01 07:00:00', '2025-07-16 01:45:00', '2025-07-16 01:45:00'),
(59, 'Nakimera Cynthia', 'cynthia.nakimera@gmail.com', '2025-07-10 11:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256701890567', 'Kampala', 'Kamwokya', '26-35', 'female', 'middle-high', '{\"preferred_categories\": [\"social_media_trends\", \"influencer_styles\", \"photo_ready_fashion\"], \"brand_conscious\": true, \"price_sensitivity\": \"low\", \"content_creator\": true}', 'weekly', 1, NULL, '2025-07-10 10:30:00', '2025-07-17 00:30:00', '2025-07-17 00:30:00'),
(60, 'Twikirize John', 'john.twikirize@gmail.com', '2025-07-15 05:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', '+256775890678', 'Mbarara', 'Katete', '36-45', 'male', 'middle', '{\"preferred_categories\": [\"medical_professional\", \"scrubs_and_casual\", \"comfortable_professional\"], \"brand_conscious\": false, \"price_sensitivity\": \"medium\", \"healthcare_worker\": true}', 'quarterly', 1, NULL, '2025-07-15 05:00:00', '2025-07-16 19:00:00', '2025-07-16 19:00:00'),
(61, 'Mukisa Samuel', 'mukisasamuel2020@gmail.com', NULL, '$2y$10$YOm7i.gNCmffxEyvEilnJu7olsuuGqDjIwFAXKXA/6JvcH5fGv0w6', '0757429284', 'Wakiso', 'hds', NULL, NULL, NULL, '[]', NULL, 1, NULL, '2025-07-18 08:02:15', '2025-07-18 08:03:11', '2025-07-18 08:03:11'),
(62, 'Mukisa Samuel', 'mukisasamuel202@gmail.com', NULL, '$2y$10$.BrkzePfVUDpQ.DOEzDjOeIKGAnTxtZUEHOkmwg/WWEm8aY1VuImm', '0757429284', 'Wakiso', 'hds', '18-25', 'male', 'low', '[\"formal_wear\",\"latest_trends\"]', 'monthly', 1, NULL, '2025-07-18 08:04:25', '2025-07-18 08:06:54', '2025-07-18 08:06:54'),
(63, 'Mukisa Samuel', 'mukisasamuel20@gmail.com', NULL, '$2y$10$OmnBnzdS1ycxNe5ThQcntu2WSzVHbWWKsfuZjEjyn03.aRyFeGT0C', '0757429284', 'Wakiso', 'MABOMBWE', '26-35', 'male', 'low', '[\"casual_wear\",\"formal_wear\"]', 'monthly', 1, 'nRx2EHri6IWBfQcmd2yp5bGHQROxkqpCcJuEyZEPYMrutbiypxlYLPXdWbT6', '2025-07-19 00:29:40', '2025-07-19 05:32:44', '2025-07-19 05:32:44');

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('credit_card','debit_card','paypal','bank_transfer','cash_on_delivery','mobile_money') DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `shipping_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`shipping_address`)),
  `billing_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`billing_address`)),
  `notes` text DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `actual_delivery` date DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_orders`
--

INSERT INTO `customer_orders` (`id`, `customer_id`, `order_number`, `status`, `total_amount`, `payment_method`, `payment_status`, `shipping_address`, `billing_address`, `notes`, `estimated_delivery`, `actual_delivery`, `tracking_number`, `created_at`, `updated_at`) VALUES
(1, 1, 'CO-2023-001', 'delivered', 125000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Ntinda\", \"street\": \"Stretcher Road\", \"landmark\": \"Near Shell Petrol Station\"}', '{\"district\": \"Kampala\", \"area\": \"Ntinda\", \"street\": \"Stretcher Road\"}', 'Office wear collection', '2023-06-20', '2023-06-19', 'TRK20230615001', '2023-06-15 11:30:00', '2023-06-19 13:00:00'),
(2, 4, 'CO-2023-002', 'delivered', 450000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Kololo\", \"street\": \"Upper Naguru\", \"building\": \"Acacia Mall\"}', NULL, 'Premium business suits', '2023-06-25', '2023-06-24', 'TRK20230620001', '2023-06-20 07:00:00', '2023-06-24 08:00:00'),
(3, 3, 'CO-2023-003', 'delivered', 280000.00, 'mobile_money', 'paid', '{\"district\": \"Mbarara\", \"area\": \"Kakoba\", \"street\": \"Kakoba Hill\", \"landmark\": \"Near Kakoba Market\"}', '{\"district\": \"Mbarara\", \"area\": \"Kakoba\"}', 'Family shopping - children clothes', '2023-07-05', '2023-07-06', 'TRK20230701001', '2023-07-01 06:00:00', '2023-07-06 11:00:00'),
(4, 7, 'CO-2023-004', 'delivered', 45000.00, 'cash_on_delivery', 'paid', '{\"district\": \"Mbale\", \"area\": \"Wanale\", \"street\": \"Market Street\"}', NULL, 'Student essentials', '2023-07-15', '2023-07-17', NULL, '2023-07-10 08:30:00', '2023-07-17 07:00:00'),
(5, 8, 'CO-2023-005', 'delivered', 180000.00, 'mobile_money', 'paid', '{\"district\": \"Wakiso\", \"area\": \"Nansana\", \"street\": \"Hoima Road\", \"stage\": \"Nansana Trading Centre\"}', '{\"district\": \"Wakiso\", \"area\": \"Nansana\"}', 'School uniforms - bulk order', '2023-08-10', '2023-08-09', 'TRK20230805001', '2023-08-05 12:00:00', '2023-08-09 09:00:00'),
(6, 2, 'CO-2023-006', 'delivered', 95000.00, 'mobile_money', 'paid', '{\"district\": \"Gulu\", \"area\": \"Layibi\", \"street\": \"Acholi Road\", \"landmark\": \"Near Layibi Primary School\"}', NULL, 'Traditional wear for ceremony', '2023-08-20', '2023-08-22', 'TRK20230815001', '2023-08-15 05:30:00', '2023-08-22 06:00:00'),
(7, 5, 'CO-2023-007', 'delivered', 120000.00, 'debit_card', 'paid', '{\"district\": \"Jinja\", \"area\": \"Main Street\", \"building\": \"Jinja City Mall\"}', '{\"district\": \"Jinja\", \"area\": \"Main Street\"}', 'Sportswear collection', '2023-09-05', '2023-09-04', 'TRK20230901001', '2023-09-01 09:00:00', '2023-09-04 12:00:00'),
(8, 11, 'CO-2023-008', 'delivered', 580000.00, 'credit_card', 'paid', '{\"district\": \"Entebbe\", \"area\": \"Kitoro\", \"street\": \"Airport Road\", \"building\": \"Victoria Mall\"}', NULL, 'Luxury fashion items', '2023-09-15', '2023-09-14', 'TRK20230910001', '2023-09-10 11:00:00', '2023-09-14 08:00:00'),
(9, 9, 'CO-2023-009', 'cancelled', 75000.00, 'mobile_money', 'refunded', '{\"district\": \"Lira\", \"area\": \"Senior Quarters\", \"street\": \"Obote Avenue\"}', NULL, 'Church wear - order cancelled', '2023-09-25', NULL, NULL, '2023-09-20 07:00:00', '2023-09-22 05:00:00'),
(10, 13, 'CO-2023-010', 'delivered', 165000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Wandegeya\", \"street\": \"Bombo Road\", \"landmark\": \"Near Wandegeya Market\"}', '{\"district\": \"Kampala\", \"area\": \"Wandegeya\"}', 'Office wear - career woman', '2023-10-10', '2023-10-09', 'TRK20231005001', '2023-10-05 08:00:00', '2023-10-09 13:00:00'),
(11, 6, 'CO-2023-011', 'delivered', 88000.00, 'cash_on_delivery', 'paid', '{\"district\": \"Fort Portal\", \"area\": \"Buhinga\", \"street\": \"Lugard Road\"}', NULL, 'Traditional and casual mix', '2023-10-20', '2023-10-23', NULL, '2023-10-15 06:00:00', '2023-10-23 11:00:00'),
(12, 16, 'CO-2023-012', 'delivered', 140000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Namugongo\", \"street\": \"Namugongo Road\", \"landmark\": \"Near Martyrs Shrine\"}', '{\"district\": \"Kampala\", \"area\": \"Namugongo\"}', 'Trendy urban fashion', '2023-11-05', '2023-11-04', 'TRK20231101001', '2023-11-01 10:00:00', '2023-11-04 07:00:00'),
(13, 14, 'CO-2023-013', 'delivered', 220000.00, 'bank_transfer', 'paid', '{\"district\": \"Kabale\", \"area\": \"Makanga\", \"street\": \"Kabale-Mbarara Road\"}', NULL, 'Business wear - cold climate appropriate', '2023-11-15', '2023-11-17', 'TRK20231110001', '2023-11-10 07:30:00', '2023-11-17 08:00:00'),
(14, 19, 'CO-2023-014', 'delivered', 350000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Rubaga\", \"street\": \"Rubaga Road\", \"building\": \"Rubaga Cathedral Vicinity\"}', '{\"district\": \"Kampala\", \"area\": \"Rubaga\"}', 'Designer African prints collection', '2023-12-01', '2023-11-30', 'TRK20231125001', '2023-11-25 12:00:00', '2023-11-30 09:00:00'),
(15, 22, 'CO-2023-015', 'delivered', 680000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Bukoto\", \"street\": \"Ntinda-Kisaasi Road\", \"building\": \"Quality Shopping Mall\"}', NULL, 'International brands - luxury collection', '2023-12-10', '2023-12-09', 'TRK20231205001', '2023-12-05 11:30:00', '2023-12-09 07:00:00'),
(16, 10, 'CO-2024-001', 'delivered', 55000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Kawempe\", \"street\": \"Bombo Road\", \"stage\": \"Kawempe Stage\"}', NULL, 'Work uniforms', '2024-01-15', '2024-01-16', 'TRK20240110001', '2024-01-10 05:00:00', '2024-01-16 11:00:00'),
(17, 15, 'CO-2024-002', 'delivered', 38000.00, 'cash_on_delivery', 'paid', '{\"district\": \"Soroti\", \"area\": \"Opuyo\", \"street\": \"Soroti-Mbale Road\"}', NULL, 'Agricultural work clothes', '2024-01-25', '2024-01-28', NULL, '2024-01-20 07:00:00', '2024-01-28 06:00:00'),
(18, 24, 'CO-2024-003', 'delivered', 145000.00, 'mobile_money', 'paid', '{\"district\": \"Jinja\", \"area\": \"Bugembe\", \"street\": \"Main Street\", \"landmark\": \"Near Bugembe Blue Primary School\"}', '{\"district\": \"Jinja\", \"area\": \"Bugembe\"}', 'Maternity and baby clothes', '2024-02-10', '2024-02-09', 'TRK20240205001', '2024-02-05 08:30:00', '2024-02-09 12:00:00'),
(19, 26, 'CO-2024-004', 'delivered', 78000.00, 'mobile_money', 'paid', '{\"district\": \"Mbarara\", \"area\": \"Kamukuzi\", \"street\": \"University Road\", \"landmark\": \"Near MUST\"}', NULL, 'Campus fashion collection', '2024-02-20', '2024-02-21', 'TRK20240215001', '2024-02-15 11:00:00', '2024-02-21 08:00:00'),
(20, 28, 'CO-2024-005', 'delivered', 450000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Nakasero\", \"street\": \"Parliamentary Avenue\", \"building\": \"Workers House\"}', NULL, 'Executive wardrobe upgrade', '2024-03-05', '2024-03-04', 'TRK20240301001', '2024-03-01 06:00:00', '2024-03-04 09:00:00'),
(21, 12, 'CO-2024-006', 'delivered', 125000.00, 'mobile_money', 'paid', '{\"district\": \"Arua\", \"area\": \"Oli Division\", \"street\": \"Arua Avenue\", \"landmark\": \"Near Arua Hill\"}', '{\"district\": \"Arua\", \"area\": \"Oli Division\"}', 'Cross-border appropriate fashion', '2024-03-15', '2024-03-17', 'TRK20240310001', '2024-03-10 07:30:00', '2024-03-17 11:00:00'),
(22, 32, 'CO-2024-007', 'delivered', 165000.00, 'bank_transfer', 'paid', '{\"district\": \"Kabale\", \"area\": \"Northern Division\", \"street\": \"Kikungiri Road\"}', NULL, 'Warm clothing for cold climate', '2024-04-01', '2024-04-03', 'TRK20240328001', '2024-03-28 08:00:00', '2024-04-03 07:00:00'),
(23, 18, 'CO-2024-008', 'delivered', 195000.00, 'debit_card', 'paid', '{\"district\": \"Tororo\", \"area\": \"Western Division\", \"street\": \"Mbale Road\", \"building\": \"Rock Hotel Area\"}', '{\"district\": \"Tororo\", \"area\": \"Western Division\"}', 'Gym and sportswear', '2024-04-15', '2024-04-16', 'TRK20240410001', '2024-04-10 09:00:00', '2024-04-16 06:00:00'),
(24, 29, 'CO-2024-009', 'delivered', 280000.00, 'bank_transfer', 'paid', '{\"district\": \"Hoima\", \"area\": \"Bujumbura\", \"street\": \"Fort Portal Road\", \"landmark\": \"Near Oil City Mall\"}', NULL, 'Business casual - oil sector', '2024-05-01', '2024-05-02', 'TRK20240426001', '2024-04-26 10:00:00', '2024-05-02 08:00:00'),
(25, 34, 'CO-2024-010', 'delivered', 520000.00, 'credit_card', 'paid', '{\"district\": \"Entebbe\", \"area\": \"Central Division\", \"street\": \"Entebbe Road\", \"building\": \"Lake Victoria Shopping Mall\"}', NULL, 'Resort wear collection', '2024-05-20', '2024-05-19', 'TRK20240515001', '2024-05-15 12:00:00', '2024-05-19 07:00:00'),
(26, 21, 'CO-2024-011', 'delivered', 98000.00, 'mobile_money', 'paid', '{\"district\": \"Mukono\", \"area\": \"Seeta\", \"street\": \"Jinja Road\", \"stage\": \"Seeta Market\"}', '{\"district\": \"Mukono\", \"area\": \"Seeta\"}', 'Smart casual collection', '2024-06-05', '2024-06-06', 'TRK20240601001', '2024-06-01 06:30:00', '2024-06-06 11:00:00'),
(27, 38, 'CO-2024-012', 'delivered', 245000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Ntinda\", \"street\": \"Ntinda Road\", \"building\": \"Capital Shoppers\"}', NULL, 'Yoga and wellness wear', '2024-06-20', '2024-06-19', 'TRK20240615001', '2024-06-15 08:00:00', '2024-06-19 12:00:00'),
(28, 17, 'CO-2024-013', 'delivered', 115000.00, 'mobile_money', 'paid', '{\"district\": \"Masaka\", \"area\": \"Nyendo\", \"street\": \"Kampala Road\", \"landmark\": \"Near Nyendo Market\"}', NULL, 'Wedding guest outfits', '2024-07-10', '2024-07-12', 'TRK20240705001', '2024-07-05 11:00:00', '2024-07-12 07:00:00'),
(29, 35, 'CO-2024-014', 'delivered', 155000.00, 'mobile_money', 'paid', '{\"district\": \"Mbarara\", \"area\": \"Nyamitanga\", \"street\": \"Mbarara-Kabale Road\"}', '{\"district\": \"Mbarara\", \"area\": \"Nyamitanga\"}', 'Church and community event wear', '2024-07-25', '2024-07-26', 'TRK20240720001', '2024-07-20 07:00:00', '2024-07-26 08:00:00'),
(30, 37, 'CO-2024-015', 'delivered', 180000.00, 'bank_transfer', 'paid', '{\"district\": \"Lira\", \"area\": \"Railway\", \"street\": \"Soroti Road\", \"landmark\": \"Near Railway Market\"}', NULL, 'Modern traditional fusion', '2024-08-10', '2024-08-12', 'TRK20240805001', '2024-08-05 09:30:00', '2024-08-12 06:00:00'),
(31, 41, 'CO-2024-016', 'delivered', 165000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Kansanga\", \"street\": \"Ggaba Road\", \"landmark\": \"Near Kansanga Market\"}', '{\"district\": \"Kampala\", \"area\": \"Kansanga\"}', 'Sustainable fashion pieces', '2024-08-25', '2024-08-24', 'TRK20240820001', '2024-08-20 12:00:00', '2024-08-24 10:00:00'),
(32, 42, 'CO-2024-017', 'delivered', 385000.00, 'credit_card', 'paid', '{\"district\": \"Jinja\", \"area\": \"Walukuba\", \"street\": \"Clive Road\", \"building\": \"Source of the Nile Hotel\"}', NULL, 'Business formal collection', '2024-09-10', '2024-09-09', 'TRK20240905001', '2024-09-05 07:30:00', '2024-09-09 08:00:00'),
(33, 25, 'CO-2024-018', 'delivered', 42000.00, 'cash_on_delivery', 'paid', '{\"district\": \"Kasese\", \"area\": \"Central Division\", \"street\": \"Stanley Street\"}', NULL, 'Mining sector work clothes', '2024-09-25', '2024-09-28', NULL, '2024-09-20 05:00:00', '2024-09-28 11:00:00'),
(34, 44, 'CO-2024-019', 'delivered', 58000.00, 'mobile_money', 'paid', '{\"district\": \"Gulu\", \"area\": \"Pece\", \"street\": \"Pece Road\", \"landmark\": \"Near Gulu University\"}', NULL, 'Student budget fashion', '2024-10-05', '2024-10-07', 'TRK20241001001', '2024-10-01 08:00:00', '2024-10-07 07:00:00'),
(35, 45, 'CO-2024-020', 'delivered', 198000.00, 'debit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Nansana\", \"street\": \"Hoima Road\", \"stage\": \"Nansana-Yesu Amala\"}', '{\"district\": \"Kampala\", \"area\": \"Nansana\"}', 'Plus-size fashion collection', '2024-10-20', '2024-10-19', 'TRK20241015001', '2024-10-15 10:30:00', '2024-10-19 12:00:00'),
(36, 33, 'CO-2024-021', 'delivered', 125000.00, 'mobile_money', 'paid', '{\"district\": \"Tororo\", \"area\": \"Eastern Division\", \"street\": \"Mbale Road\"}', NULL, 'Sports team merchandise', '2024-11-05', '2024-11-07', 'TRK20241101001', '2024-11-01 06:00:00', '2024-11-07 08:00:00'),
(37, 46, 'CO-2024-022', 'delivered', 295000.00, 'credit_card', 'paid', '{\"district\": \"Mbarara\", \"area\": \"Ruharo\", \"street\": \"Mbarara Bypass\"}', NULL, 'Tech-wear collection', '2024-11-20', '2024-11-19', 'TRK20241115001', '2024-11-15 11:00:00', '2024-11-19 07:00:00'),
(38, 39, 'CO-2024-023', 'delivered', 135000.00, 'mobile_money', 'paid', '{\"district\": \"Masaka\", \"area\": \"Kimanya\", \"street\": \"Edward Avenue\"}', '{\"district\": \"Masaka\", \"area\": \"Kimanya\"}', 'Classic timeless pieces', '2024-12-05', '2024-12-06', 'TRK20241201001', '2024-12-01 07:00:00', '2024-12-06 09:00:00'),
(39, 47, 'CO-2024-024', 'delivered', 110000.00, 'bank_transfer', 'paid', '{\"district\": \"Fort Portal\", \"area\": \"South Division\", \"street\": \"Lugard Road\"}', NULL, 'Teacher professional wear', '2024-12-15', '2024-12-17', 'TRK20241210001', '2024-12-10 08:30:00', '2024-12-17 06:00:00'),
(40, 30, 'CO-2024-025', 'cancelled', 95000.00, 'mobile_money', 'refunded', '{\"district\": \"Fort Portal\", \"area\": \"Kabarole\", \"street\": \"Kabarole Road\"}', NULL, 'Order cancelled - wrong size', '2024-12-25', NULL, NULL, '2024-12-20 12:00:00', '2024-12-22 05:00:00'),
(41, 48, 'CO-2025-001', 'delivered', 580000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Kisaasi\", \"street\": \"Kisaasi Road\", \"building\": \"Northern Bypass\"}', NULL, 'Golf apparel collection', '2025-01-15', '2025-01-14', 'TRK20250110001', '2025-01-10 09:00:00', '2025-01-14 07:00:00'),
(42, 36, 'CO-2025-002', 'delivered', 245000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Nateete\", \"street\": \"Masaka Road\", \"landmark\": \"Near Nateete Market\"}', '{\"district\": \"Kampala\", \"area\": \"Nateete\"}', 'Twins clothing - bulk order', '2025-01-25', '2025-01-26', 'TRK20250120001', '2025-01-20 06:30:00', '2025-01-26 11:00:00'),
(43, 49, 'CO-2025-003', 'delivered', 68000.00, 'cash_on_delivery', 'paid', '{\"district\": \"Mbale\", \"area\": \"Namatala\", \"street\": \"Tororo Road\", \"landmark\": \"Near Namatala Market\"}', NULL, 'Market vendor appropriate wear', '2025-02-10', '2025-02-12', NULL, '2025-02-05 05:00:00', '2025-02-12 06:00:00'),
(44, 50, 'CO-2025-004', 'delivered', 750000.00, 'credit_card', 'paid', '{\"district\": \"Hoima\", \"area\": \"Mparo\", \"street\": \"Hoima-Kampala Road\", \"building\": \"Hoima Resort Hotel\"}', NULL, 'Executive international brands', '2025-02-20', '2025-02-19', 'TRK20250215001', '2025-02-15 08:00:00', '2025-02-19 07:00:00'),
(45, 43, 'CO-2025-005', 'delivered', 185000.00, 'mobile_money', 'paid', '{\"district\": \"Wakiso\", \"area\": \"Nsangi\", \"street\": \"Busabala Road\"}', NULL, 'Church choir uniforms - group order', '2025-03-05', '2025-03-06', 'TRK20250301001', '2025-03-01 11:00:00', '2025-03-06 08:00:00'),
(46, 51, 'CO-2025-006', 'delivered', 92000.00, 'mobile_money', 'paid', '{\"district\": \"Arua\", \"area\": \"Ayivu\", \"street\": \"Arua-Koboko Road\"}', '{\"district\": \"Arua\", \"area\": \"Ayivu\"}', 'Traditional-modern fusion', '2025-03-20', '2025-03-22', 'TRK20250315001', '2025-03-15 07:00:00', '2025-03-22 10:00:00'),
(47, 52, 'CO-2025-007', 'delivered', 215000.00, 'debit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Namirembe\", \"street\": \"Namirembe Road\", \"landmark\": \"Near Owino Market\"}', NULL, 'Stage performance outfits', '2025-04-01', '2025-03-31', 'TRK20250328001', '2025-03-28 10:00:00', '2025-03-31 12:00:00'),
(48, 53, 'CO-2025-008', 'delivered', 485000.00, 'credit_card', 'paid', '{\"district\": \"Wakiso\", \"area\": \"Busabala\", \"street\": \"Entebbe Road\", \"building\": \"Beach Houses\"}', NULL, 'Beach resort luxury collection', '2025-04-15', '2025-04-14', 'TRK20250410001', '2025-04-10 08:30:00', '2025-04-14 07:00:00'),
(49, 27, 'CO-2025-009', 'delivered', 320000.00, 'bank_transfer', 'paid', '{\"district\": \"Kampala\", \"area\": \"Kyambogo\", \"street\": \"Kyambogo Road\", \"building\": \"Banda\"}', '{\"district\": \"Kampala\", \"area\": \"Kyambogo\"}', 'Golf and country club wear', '2025-05-01', '2025-04-30', 'TRK20250426001', '2025-04-26 12:00:00', '2025-04-30 09:00:00'),
(50, 54, 'CO-2025-010', 'delivered', 75000.00, 'mobile_money', 'paid', '{\"district\": \"Lira\", \"area\": \"Adyel\", \"street\": \"Kitgum Road\"}', NULL, 'Youth street fashion', '2025-05-15', '2025-05-17', 'TRK20250510001', '2025-05-10 06:00:00', '2025-05-17 07:00:00'),
(51, 55, 'CO-2025-011', 'delivered', 980000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Munyonyo\", \"street\": \"Munyonyo Road\", \"building\": \"Commonwealth Resort\"}', NULL, 'Gala event exclusive designs', '2025-05-25', '2025-05-24', 'TRK20250520001', '2025-05-20 11:00:00', '2025-05-24 08:00:00'),
(52, 40, 'CO-2025-012', 'delivered', 48000.00, 'cash_on_delivery', 'paid', '{\"district\": \"Soroti\", \"area\": \"Arapai\", \"street\": \"Soroti-Kaberamaido Road\"}', NULL, 'Market day practical wear', '2025-06-05', '2025-06-08', NULL, '2025-06-01 04:30:00', '2025-06-08 06:00:00'),
(53, 56, 'CO-2025-013', 'delivered', 155000.00, 'mobile_money', 'paid', '{\"district\": \"Mukono\", \"area\": \"Central Division\", \"street\": \"Kampala Road\"}', '{\"district\": \"Mukono\", \"area\": \"Central Division\"}', 'Family weekend outfits', '2025-06-15', '2025-06-16', 'TRK20250610001', '2025-06-10 09:00:00', '2025-06-16 11:00:00'),
(54, 57, 'CO-2025-014', 'delivered', 112000.00, 'mobile_money', 'paid', '{\"district\": \"Soroti\", \"area\": \"Central\", \"street\": \"Gweri Road\"}', NULL, 'Government employee smart casual', '2025-06-25', '2025-06-27', 'TRK20250620001', '2025-06-20 07:30:00', '2025-06-27 08:00:00'),
(55, 59, 'CO-2025-015', 'shipped', 425000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Kamwokya\", \"street\": \"Kira Road\", \"building\": \"Kamwokya Mall\"}', NULL, 'Influencer photo shoot collection', '2025-07-20', NULL, 'TRK20250710001', '2025-07-10 13:00:00', '2025-07-15 06:00:00'),
(56, 1, 'CO-2025-016', 'processing', 185000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Ntinda\", \"street\": \"Stretcher Road\", \"landmark\": \"Near Capital Shoppers\"}', '{\"district\": \"Kampala\", \"area\": \"Ntinda\"}', 'Summer office collection', '2025-07-25', NULL, NULL, '2025-07-12 08:00:00', '2025-07-16 05:00:00'),
(57, 22, 'CO-2025-017', 'confirmed', 890000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Bukoto\", \"street\": \"Ntinda-Kisaasi Road\"}', NULL, 'Luxury travel wardrobe', '2025-07-28', NULL, NULL, '2025-07-14 12:00:00', '2025-07-15 07:00:00'),
(58, 60, 'CO-2025-018', 'confirmed', 165000.00, 'mobile_money', 'paid', '{\"district\": \"Mbarara\", \"area\": \"Katete\", \"street\": \"Mbarara-Bushenyi Road\"}', NULL, 'Medical professional wardrobe', '2025-07-30', NULL, NULL, '2025-07-15 10:30:00', '2025-07-16 06:00:00'),
(59, 4, 'CO-2025-019', 'pending', 520000.00, 'credit_card', 'pending', '{\"district\": \"Kampala\", \"area\": \"Kololo\", \"street\": \"Upper Naguru\", \"building\": \"Acacia Mall\"}', NULL, 'Quarterly wardrobe refresh', '2025-08-05', NULL, NULL, '2025-07-16 07:00:00', '2025-07-16 07:00:00'),
(60, 13, 'CO-2025-020', 'pending', 195000.00, 'mobile_money', 'pending', '{\"district\": \"Kampala\", \"area\": \"Wandegeya\", \"street\": \"Bombo Road\"}', '{\"district\": \"Kampala\", \"area\": \"Wandegeya\"}', 'New season office wear', '2025-08-01', NULL, NULL, '2025-07-16 11:30:00', '2025-07-16 11:30:00'),
(61, 19, 'CO-2025-021', 'pending', 380000.00, 'bank_transfer', 'pending', '{\"district\": \"Kampala\", \"area\": \"Rubaga\", \"street\": \"Rubaga Road\"}', NULL, 'African heritage collection', '2025-08-10', NULL, NULL, '2025-07-17 05:00:00', '2025-07-17 05:00:00'),
(62, 31, 'CO-2024-030', 'cancelled', 65000.00, 'mobile_money', 'failed', '{\"district\": \"Kampala\", \"area\": \"Bwaise\", \"street\": \"Nabweru Road\"}', NULL, 'Payment failed - insufficient funds', '2024-11-25', NULL, NULL, '2024-11-20 09:00:00', '2024-11-21 05:00:00'),
(63, 23, 'CO-2024-031', 'cancelled', 85000.00, 'debit_card', 'failed', '{\"district\": \"Gulu\", \"area\": \"Bardege\", \"street\": \"Gulu-Kampala Road\"}', NULL, 'Card declined', '2024-10-15', NULL, NULL, '2024-10-10 06:00:00', '2024-10-11 07:00:00'),
(64, 2, 'CO-2025-022', 'delivered', 145000.00, 'mobile_money', 'paid', '{\"district\": \"Gulu\", \"area\": \"Layibi\", \"street\": \"Acholi Road\"}', NULL, 'Cultural event wear', '2025-05-30', '2025-05-31', 'TRK20250525001', '2025-05-25 05:30:00', '2025-05-31 07:00:00'),
(65, 5, 'CO-2025-023', 'delivered', 178000.00, 'credit_card', 'paid', '{\"district\": \"Jinja\", \"area\": \"Main Street\", \"building\": \"Jinja Mall\"}', '{\"district\": \"Jinja\", \"area\": \"Main Street\"}', 'Fitness wardrobe upgrade', '2025-06-10', '2025-06-09', 'TRK20250605001', '2025-06-05 11:00:00', '2025-06-09 08:00:00'),
(66, 8, 'CO-2025-024', 'delivered', 225000.00, 'mobile_money', 'paid', '{\"district\": \"Wakiso\", \"area\": \"Nansana\", \"street\": \"Hoima Road\"}', NULL, 'Back to school shopping', '2025-01-08', '2025-01-07', 'TRK20250103001', '2025-01-03 07:00:00', '2025-01-07 10:00:00'),
(67, 11, 'CO-2025-025', 'delivered', 750000.00, 'credit_card', 'paid', '{\"district\": \"Entebbe\", \"area\": \"Kitoro\", \"street\": \"Airport Road\"}', NULL, 'Designer collection purchase', '2025-03-25', '2025-03-24', 'TRK20250320001', '2025-03-20 13:00:00', '2025-03-24 07:00:00'),
(68, 14, 'CO-2025-026', 'delivered', 295000.00, 'bank_transfer', 'paid', '{\"district\": \"Kabale\", \"area\": \"Makanga\", \"street\": \"Kabale Road\"}', NULL, 'Winter collection', '2025-04-20', '2025-04-22', 'TRK20250415001', '2025-04-15 08:00:00', '2025-04-22 06:00:00'),
(69, 16, 'CO-2025-027', 'delivered', 168000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Namugongo\", \"street\": \"Kyaliwajjala Road\"}', '{\"district\": \"Kampala\", \"area\": \"Namugongo\"}', 'Trendy casual collection', '2025-02-28', '2025-02-27', 'TRK20250223001', '2025-02-23 09:30:00', '2025-02-27 11:00:00'),
(70, 18, 'CO-2025-028', 'delivered', 235000.00, 'debit_card', 'paid', '{\"district\": \"Tororo\", \"area\": \"Western Division\", \"street\": \"Mbale Road\"}', NULL, 'Athletic wear collection', '2025-05-05', '2025-05-06', 'TRK20250430001', '2025-04-30 10:00:00', '2025-05-06 07:00:00'),
(71, 21, 'CO-2025-029', 'delivered', 125000.00, 'mobile_money', 'paid', '{\"district\": \"Mukono\", \"area\": \"Seeta\", \"street\": \"Kampala Road\"}', NULL, 'Weekend casual wear', '2025-03-10', '2025-03-11', 'TRK20250305001', '2025-03-05 06:00:00', '2025-03-11 12:00:00'),
(72, 24, 'CO-2025-030', 'delivered', 198000.00, 'mobile_money', 'paid', '{\"district\": \"Jinja\", \"area\": \"Bugembe\", \"street\": \"Owen Falls\"}', '{\"district\": \"Jinja\", \"area\": \"Bugembe\"}', 'Baby shower shopping', '2025-04-05', '2025-04-06', 'TRK20250401001', '2025-04-01 12:00:00', '2025-04-06 08:00:00'),
(73, 26, 'CO-2025-031', 'delivered', 95000.00, 'mobile_money', 'paid', '{\"district\": \"Mbarara\", \"area\": \"Kamukuzi\", \"street\": \"University Road\"}', NULL, 'End of semester fashion', '2025-05-20', '2025-05-22', 'TRK20250515001', '2025-05-15 08:30:00', '2025-05-22 06:00:00'),
(74, 28, 'CO-2025-032', 'delivered', 585000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Nakasero\", \"street\": \"Kampala Road\"}', NULL, 'Quarterly executive update', '2025-06-30', '2025-06-29', 'TRK20250625001', '2025-06-25 07:00:00', '2025-06-29 09:00:00'),
(75, 32, 'CO-2025-033', 'delivered', 210000.00, 'bank_transfer', 'paid', '{\"district\": \"Kabale\", \"area\": \"Northern Division\", \"street\": \"Kikungiri\"}', NULL, 'Seasonal wardrobe change', '2025-06-18', '2025-06-20', 'TRK20250613001', '2025-06-13 11:00:00', '2025-06-20 07:00:00'),
(76, 1, 'CO-2024-040', 'delivered', 165000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Ntinda\", \"street\": \"Stretcher Road\"}', '{\"district\": \"Kampala\", \"area\": \"Ntinda\"}', 'Repeat customer - office wear', '2024-09-15', '2024-09-14', 'TRK20240910002', '2024-09-10 08:00:00', '2024-09-14 12:00:00'),
(77, 1, 'CO-2024-041', 'delivered', 198000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Ntinda\", \"street\": \"Stretcher Road\"}', '{\"district\": \"Kampala\", \"area\": \"Ntinda\"}', 'Repeat customer - party wear', '2024-12-20', '2024-12-19', 'TRK20241215002', '2024-12-15 11:00:00', '2024-12-19 08:00:00'),
(78, 4, 'CO-2023-050', 'delivered', 480000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Kololo\", \"street\": \"Upper Naguru\"}', NULL, 'VIP customer - seasonal', '2023-09-25', '2023-09-24', 'TRK20230920002', '2023-09-20 07:00:00', '2023-09-24 07:00:00'),
(79, 4, 'CO-2024-042', 'delivered', 520000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Kololo\", \"street\": \"Upper Naguru\"}', NULL, 'VIP customer - quarterly', '2024-03-30', '2024-03-29', 'TRK20240325002', '2024-03-25 06:00:00', '2024-03-29 08:00:00'),
(80, 4, 'CO-2024-043', 'delivered', 495000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Kololo\", \"street\": \"Upper Naguru\"}', NULL, 'VIP customer - regular', '2024-06-28', '2024-06-27', 'TRK20240623002', '2024-06-23 08:00:00', '2024-06-27 07:00:00'),
(81, 9, 'CO-2024-044', 'delivered', 320000.00, 'mobile_money', 'paid', '{\"district\": \"Lira\", \"area\": \"Senior Quarters\", \"street\": \"Obote Avenue\"}', NULL, 'Church group order - choir uniforms', '2024-04-20', '2024-04-22', 'TRK20240415002', '2024-04-15 05:00:00', '2024-04-22 07:00:00'),
(82, 43, 'CO-2025-034', 'delivered', 450000.00, 'bank_transfer', 'paid', '{\"district\": \"Wakiso\", \"area\": \"Nsangi\", \"street\": \"Busabala Road\"}', NULL, 'Choir uniforms - 30 pieces', '2025-02-15', '2025-02-17', 'TRK20250210002', '2025-02-10 07:00:00', '2025-02-17 08:00:00'),
(83, 20, 'CO-2023-051', 'cancelled', 125000.00, 'mobile_money', 'failed', '{\"district\": \"Kampala\", \"area\": \"Makindye\", \"street\": \"Salaama Road\"}', NULL, 'Cancelled - out of stock', '2023-12-22', NULL, NULL, '2023-12-18 11:00:00', '2023-12-19 06:00:00'),
(84, 15, 'CO-2024-045', 'delivered', 52000.00, 'cash_on_delivery', 'paid', '{\"district\": \"Soroti\", \"area\": \"Opuyo\", \"street\": \"Mbale Road\"}', NULL, 'Harvest season clothing', '2024-07-15', '2024-07-18', NULL, '2024-07-10 04:00:00', '2024-07-18 05:00:00'),
(85, 25, 'CO-2024-046', 'delivered', 48000.00, 'cash_on_delivery', 'paid', '{\"district\": \"Kasese\", \"area\": \"Central Division\", \"street\": \"Stanley Street\"}', NULL, 'Protective work gear', '2024-08-20', '2024-08-23', NULL, '2024-08-15 06:00:00', '2024-08-23 07:00:00'),
(86, 12, 'CO-2025-035', 'delivered', 168000.00, 'mobile_money', 'paid', '{\"district\": \"Arua\", \"area\": \"Oli Division\", \"street\": \"Arua Avenue\"}', '{\"district\": \"Arua\", \"area\": \"Oli Division\"}', 'Cross-border fashion items', '2025-01-30', '2025-02-01', 'TRK20250125002', '2025-01-25 07:00:00', '2025-02-01 06:00:00'),
(87, 6, 'CO-2025-036', 'delivered', 98000.00, 'mobile_money', 'paid', '{\"district\": \"Fort Portal\", \"area\": \"Buhinga\", \"street\": \"Lugard Road\"}', NULL, 'Tourism sector uniforms', '2025-03-15', '2025-03-18', 'TRK20250310002', '2025-03-10 08:00:00', '2025-03-18 07:00:00'),
(88, 30, 'CO-2025-037', 'delivered', 115000.00, 'mobile_money', 'paid', '{\"district\": \"Fort Portal\", \"area\": \"Kabarole\", \"street\": \"Kabarole Road\"}', NULL, 'Retirement comfort wear', '2025-04-25', '2025-04-27', 'TRK20250420002', '2025-04-20 10:00:00', '2025-04-27 08:00:00'),
(89, 10, 'CO-2025-038', 'delivered', 68000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Kawempe\", \"street\": \"Bombo Road\"}', NULL, 'Work uniforms - monthly', '2025-04-30', '2025-05-01', 'TRK20250425002', '2025-04-25 05:30:00', '2025-05-01 11:00:00'),
(90, 7, 'CO-2025-039', 'delivered', 55000.00, 'mobile_money', 'paid', '{\"district\": \"Mbale\", \"area\": \"Wanale\", \"street\": \"Market Street\"}', NULL, 'Student shopping', '2025-05-18', '2025-05-20', 'TRK20250513002', '2025-05-13 07:00:00', '2025-05-20 06:00:00'),
(91, 11, 'CO-2025-040', 'delivered', 880000.00, 'credit_card', 'paid', '{\"district\": \"Entebbe\", \"area\": \"Kitoro\", \"street\": \"Airport Road\"}', NULL, 'International travel wardrobe', '2025-06-20', '2025-06-19', 'TRK20250615002', '2025-06-15 12:00:00', '2025-06-19 07:00:00'),
(92, 22, 'CO-2025-041', 'delivered', 720000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Bukoto\", \"street\": \"Ntinda-Kisaasi Road\"}', NULL, 'Seasonal luxury update', '2025-07-05', '2025-07-04', 'TRK20250630001', '2025-06-30 11:00:00', '2025-07-04 08:00:00'),
(93, 38, 'CO-2025-042', 'delivered', 285000.00, 'credit_card', 'paid', '{\"district\": \"Kampala\", \"area\": \"Ntinda\", \"street\": \"Ntinda Road\"}', NULL, 'Wellness collection - complete', '2025-07-08', '2025-07-07', 'TRK20250703001', '2025-07-03 08:00:00', '2025-07-07 12:00:00'),
(94, 42, 'CO-2025-043', 'delivered', 420000.00, 'bank_transfer', 'paid', '{\"district\": \"Jinja\", \"area\": \"Walukuba\", \"street\": \"Clive Road\"}', NULL, 'Conference wardrobe prep', '2025-07-12', '2025-07-11', 'TRK20250707001', '2025-07-07 07:00:00', '2025-07-11 09:00:00'),
(95, 20, 'CO-2025-044', 'processing', 0.00, 'cash_on_delivery', 'pending', '{\"district\": \"Kampala\", \"area\": \"Makindye\", \"street\": \"Salaama Road\"}', NULL, 'Gift voucher redemption', '2025-07-22', NULL, NULL, '2025-07-13 13:00:00', '2025-07-16 07:00:00'),
(96, 31, 'CO-2025-045', 'confirmed', 42000.00, 'mobile_money', 'paid', '{\"district\": \"Kampala\", \"area\": \"Bwaise\", \"street\": \"Nabweru Road\"}', NULL, 'Budget shopping - essentials', '2025-07-23', NULL, NULL, '2025-07-14 05:00:00', '2025-07-15 06:00:00'),
(97, 17, 'CO-2025-046', 'delivered', 158000.00, 'mobile_money', 'paid', '{\"district\": \"Masaka\", \"area\": \"Nyendo\", \"street\": \"Kampala Road\"}', NULL, 'Wedding season shopping', '2025-06-28', '2025-06-29', 'TRK20250623001', '2025-06-23 10:00:00', '2025-06-29 07:00:00'),
(98, 35, 'CO-2025-047', 'delivered', 175000.00, 'bank_transfer', 'paid', '{\"district\": \"Mbarara\", \"area\": \"Nyamitanga\", \"street\": \"Mbarara Road\"}', '{\"district\": \"Mbarara\", \"area\": \"Nyamitanga\"}', 'Community event uniforms', '2025-07-02', '2025-07-03', 'TRK20250627001', '2025-06-27 07:30:00', '2025-07-03 08:00:00'),
(99, 29, 'CO-2025-048', 'delivered', 325000.00, 'bank_transfer', 'paid', '{\"district\": \"Hoima\", \"area\": \"Bujumbura\", \"street\": \"Fort Portal Road\"}', NULL, 'Oil sector corporate wear', '2025-07-10', '2025-07-09', 'TRK20250705001', '2025-07-05 08:00:00', '2025-07-09 07:00:00'),
(100, 34, 'CO-2025-049', 'pending', 650000.00, 'credit_card', 'pending', '{\"district\": \"Entebbe\", \"area\": \"Central Division\", \"street\": \"Entebbe Road\"}', NULL, 'Resort collection pre-order', '2025-08-15', NULL, NULL, '2025-07-17 09:00:00', '2025-07-17 09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `customer_order_items`
--

CREATE TABLE `customer_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_order_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_order_items`
--

INSERT INTO `customer_order_items` (`id`, `customer_order_id`, `item_id`, `quantity`, `unit_price`, `total_price`, `size`, `color`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 1, 65000.00, 65000.00, 'M', 'Navy Blue Print', 'Office appropriate dress', '2023-06-15 11:30:00', '2023-06-15 11:30:00'),
(2, 1, 28, 2, 30000.00, 60000.00, 'M', 'White', 'Corporate polo shirts', '2023-06-15 11:30:00', '2023-06-15 11:30:00'),
(3, 2, 13, 2, 180000.00, 360000.00, 'L', 'Charcoal Grey', 'Premium business suits', '2023-06-20 07:00:00', '2023-06-20 07:00:00'),
(4, 2, 28, 3, 30000.00, 90000.00, 'L', 'White', 'Dress shirts', '2023-06-20 07:00:00', '2023-06-20 07:00:00'),
(5, 3, 16, 4, 15000.00, 60000.00, '8-10 years', 'Navy', 'Children school shorts', '2023-07-01 06:00:00', '2023-07-01 06:00:00'),
(6, 3, 24, 3, 35000.00, 105000.00, '6-12 months', 'Mixed Colors', 'Baby clothes sets', '2023-07-01 06:00:00', '2023-07-01 06:00:00'),
(7, 3, 12, 1, 115000.00, 115000.00, 'Queen', 'Cream', 'Luxury bedsheet set', '2023-07-01 06:00:00', '2023-07-01 06:00:00'),
(8, 4, 9, 2, 22500.00, 45000.00, 'S', 'Black', 'Basic t-shirts for campus', '2023-07-10 08:30:00', '2023-07-10 08:30:00'),
(9, 5, 11, 4, 45000.00, 180000.00, 'Age 10-12', 'Navy/White', 'School uniform sets', '2023-08-05 12:00:00', '2023-08-05 12:00:00'),
(10, 6, 19, 2, 30000.00, 60000.00, '6 yards', 'Traditional Print', 'Kitenge fabric', '2023-08-15 05:30:00', '2023-08-15 05:30:00'),
(11, 6, 9, 1, 20000.00, 20000.00, 'M', 'White', 'Cotton shirt', '2023-08-15 05:30:00', '2023-08-15 05:30:00'),
(12, 6, 16, 1, 15000.00, 15000.00, 'M', 'Black', 'Formal shorts', '2023-08-15 05:30:00', '2023-08-15 05:30:00'),
(13, 7, 9, 3, 25000.00, 75000.00, 'M', 'Mixed', 'Athletic t-shirts', '2023-09-01 09:00:00', '2023-09-01 09:00:00'),
(14, 7, 16, 2, 22500.00, 45000.00, 'M', 'Black', 'Sports shorts', '2023-09-01 09:00:00', '2023-09-01 09:00:00'),
(15, 8, 10, 3, 120000.00, 360000.00, 'L', 'Exclusive Prints', 'Designer dresses', '2023-09-10 11:00:00', '2023-09-10 11:00:00'),
(16, 8, 17, 4, 35000.00, 140000.00, 'One Size', 'Assorted', 'Luxury scarves', '2023-09-10 11:00:00', '2023-09-10 11:00:00'),
(17, 8, 12, 1, 80000.00, 80000.00, 'King', 'White', 'Premium bedding', '2023-09-10 11:00:00', '2023-09-10 11:00:00'),
(18, 10, 10, 1, 75000.00, 75000.00, 'M', 'Burgundy', 'Power dress', '2023-10-05 08:00:00', '2023-10-05 08:00:00'),
(19, 10, 28, 2, 35000.00, 70000.00, 'M', 'Light Blue', 'Office blouses', '2023-10-05 08:00:00', '2023-10-05 08:00:00'),
(20, 10, 17, 1, 20000.00, 20000.00, 'One Size', 'Navy', 'Office scarf', '2023-10-05 08:00:00', '2023-10-05 08:00:00'),
(21, 11, 27, 1, 28000.00, 28000.00, '2 meters', 'Natural Brown', 'Bark cloth', '2023-10-15 06:00:00', '2023-10-15 06:00:00'),
(22, 11, 9, 2, 22000.00, 44000.00, 'XL', 'Grey', 'Casual t-shirts', '2023-10-15 06:00:00', '2023-10-15 06:00:00'),
(23, 11, 16, 1, 16000.00, 16000.00, 'XL', 'Khaki', 'Casual shorts', '2023-10-15 06:00:00', '2023-10-15 06:00:00'),
(24, 12, 9, 3, 28000.00, 84000.00, 'M', 'Black/White/Red', 'Trendy graphic tees', '2023-11-01 10:00:00', '2023-11-01 10:00:00'),
(25, 12, 16, 2, 28000.00, 56000.00, 'M', 'Denim Blue', 'Trendy shorts', '2023-11-01 10:00:00', '2023-11-01 10:00:00'),
(26, 13, 13, 1, 85000.00, 85000.00, 'L', 'Navy', 'Wool blend suit', '2023-11-10 07:30:00', '2023-11-10 07:30:00'),
(27, 13, 28, 3, 32000.00, 96000.00, 'L', 'White/Blue', 'Business shirts', '2023-11-10 07:30:00', '2023-11-10 07:30:00'),
(28, 13, 30, 1, 39000.00, 39000.00, '110cm', 'Black', 'Leather belt', '2023-11-10 07:30:00', '2023-11-10 07:30:00'),
(29, 14, 10, 2, 125000.00, 250000.00, 'L', 'Ankara Special', 'Designer African dresses', '2023-11-25 12:00:00', '2023-11-25 12:00:00'),
(30, 14, 19, 3, 30000.00, 90000.00, '6 yards', 'Premium Prints', 'Designer kitenge', '2023-11-25 12:00:00', '2023-11-25 12:00:00'),
(31, 14, 17, 1, 10000.00, 10000.00, 'One Size', 'Gold Pattern', 'Matching headwrap', '2023-11-25 12:00:00', '2023-11-25 12:00:00'),
(32, 15, 10, 3, 180000.00, 540000.00, 'M', 'Exclusive Collection', 'International designer wear', '2023-12-05 11:30:00', '2023-12-05 11:30:00'),
(33, 15, 12, 1, 140000.00, 140000.00, 'King', 'Egyptian Cotton White', 'Ultra-luxury bedding', '2023-12-05 11:30:00', '2023-12-05 11:30:00'),
(34, 16, 13, 1, 55000.00, 55000.00, 'L', 'Navy', 'Industrial overall', '2024-01-10 05:00:00', '2024-01-10 05:00:00'),
(35, 17, 9, 2, 19000.00, 38000.00, 'L', 'Brown', 'Farm work shirts', '2024-01-20 07:00:00', '2024-01-20 07:00:00'),
(36, 18, 10, 2, 45000.00, 90000.00, 'Maternity L', 'Floral', 'Maternity dresses', '2024-02-05 08:30:00', '2024-02-05 08:30:00'),
(37, 18, 24, 1, 40000.00, 40000.00, 'Newborn Set', 'Yellow/Green', 'Newborn essentials', '2024-02-05 08:30:00', '2024-02-05 08:30:00'),
(38, 18, 9, 1, 15000.00, 15000.00, 'Nursing XL', 'Black', 'Nursing-friendly top', '2024-02-05 08:30:00', '2024-02-05 08:30:00'),
(39, 20, 28, 5, 45000.00, 225000.00, 'S', 'White/Blue/Pink', 'Executive blouses', '2024-03-01 06:00:00', '2024-03-01 06:00:00'),
(40, 20, 10, 2, 95000.00, 190000.00, 'S', 'Black/Navy', 'Power dresses', '2024-03-01 06:00:00', '2024-03-01 06:00:00'),
(41, 20, 17, 2, 17500.00, 35000.00, 'One Size', 'Silk', 'Executive scarves', '2024-03-01 06:00:00', '2024-03-01 06:00:00'),
(42, 25, 10, 3, 120000.00, 360000.00, 'L', 'Resort Prints', 'Beach resort dresses', '2024-05-15 12:00:00', '2024-05-15 12:00:00'),
(43, 25, 17, 2, 45000.00, 90000.00, 'One Size', 'Tropical', 'Beach scarves', '2024-05-15 12:00:00', '2024-05-15 12:00:00'),
(44, 25, 9, 2, 35000.00, 70000.00, 'L', 'White', 'Resort casual tops', '2024-05-15 12:00:00', '2024-05-15 12:00:00'),
(45, 41, 28, 4, 85000.00, 340000.00, 'XL', 'Club Colors', 'Golf polo shirts', '2025-01-10 09:00:00', '2025-01-10 09:00:00'),
(46, 41, 16, 3, 65000.00, 195000.00, 'XL', 'Khaki/Navy', 'Golf shorts', '2025-01-10 09:00:00', '2025-01-10 09:00:00'),
(47, 41, 30, 1, 45000.00, 45000.00, '120cm', 'Brown', 'Premium golf belt', '2025-01-10 09:00:00', '2025-01-10 09:00:00'),
(48, 42, 11, 4, 48000.00, 192000.00, 'Age 6-8', 'Navy/White', 'Twin school uniforms', '2025-01-20 06:30:00', '2025-01-20 06:30:00'),
(49, 42, 16, 4, 13250.00, 53000.00, 'Age 6-8', 'Matching', 'Twin play clothes', '2025-01-20 06:30:00', '2025-01-20 06:30:00'),
(50, 44, 28, 5, 95000.00, 475000.00, 'L', 'White/Blue', 'Executive shirts', '2025-02-15 08:00:00', '2025-02-15 08:00:00'),
(51, 44, 13, 1, 185000.00, 185000.00, 'L', 'Italian Navy', 'Premium suit', '2025-02-15 08:00:00', '2025-02-15 08:00:00'),
(52, 44, 30, 2, 45000.00, 90000.00, '115cm', 'Black/Brown', 'Designer belts', '2025-02-15 08:00:00', '2025-02-15 08:00:00'),
(53, 48, 10, 2, 145000.00, 290000.00, 'M', 'Designer Beach', 'Luxury beach dresses', '2025-04-10 08:30:00', '2025-04-10 08:30:00'),
(54, 48, 25, 3, 45000.00, 135000.00, 'M', 'Designer Prints', 'Beach cover-ups', '2025-04-10 08:30:00', '2025-04-10 08:30:00'),
(55, 48, 17, 2, 30000.00, 60000.00, 'One Size', 'Sunset Colors', 'Beach accessories', '2025-04-10 08:30:00', '2025-04-10 08:30:00'),
(56, 51, 15, 1, 480000.00, 480000.00, 'Custom', 'Royal Purple', 'Exclusive gala gown', '2025-05-20 11:00:00', '2025-05-20 11:00:00'),
(57, 51, 10, 2, 180000.00, 360000.00, 'M', 'Gold/Silver', 'Evening dresses', '2025-05-20 11:00:00', '2025-05-20 11:00:00'),
(58, 51, 17, 2, 70000.00, 140000.00, 'One Size', 'Matching', 'Designer wraps', '2025-05-20 11:00:00', '2025-05-20 11:00:00'),
(59, 55, 10, 3, 110000.00, 330000.00, 'S', 'Instagram Trending', 'Influencer collection', '2025-07-10 13:00:00', '2025-07-10 13:00:00'),
(60, 55, 17, 3, 25000.00, 75000.00, 'One Size', 'Matching Set', 'Photo shoot accessories', '2025-07-10 13:00:00', '2025-07-10 13:00:00'),
(61, 55, 9, 2, 10000.00, 20000.00, 'S', 'White', 'Basic tops for layering', '2025-07-10 13:00:00', '2025-07-10 13:00:00'),
(62, 56, 28, 3, 40000.00, 120000.00, 'M', 'Pastel', 'Summer office collection', '2025-07-12 08:00:00', '2025-07-12 08:00:00'),
(63, 56, 10, 1, 65000.00, 65000.00, 'M', 'Coral', 'Summer dress', '2025-07-12 08:00:00', '2025-07-12 08:00:00'),
(64, 57, 10, 4, 185000.00, 740000.00, 'M', 'Travel Collection', 'Luxury travel wardrobe', '2025-07-14 12:00:00', '2025-07-14 12:00:00'),
(65, 57, 17, 3, 50000.00, 150000.00, 'One Size', 'Coordinated', 'Travel accessories', '2025-07-14 12:00:00', '2025-07-14 12:00:00'),
(66, 26, 9, 2, 25000.00, 50000.00, 'L', 'Black', 'Weekend casual', '2024-06-01 06:30:00', '2024-06-01 06:30:00'),
(67, 26, 16, 1, 28000.00, 28000.00, 'L', 'Denim', 'Casual shorts', '2024-06-01 06:30:00', '2024-06-01 06:30:00'),
(68, 26, 17, 1, 20000.00, 20000.00, 'One Size', 'Pattern', 'Weekend scarf', '2024-06-01 06:30:00', '2024-06-01 06:30:00'),
(69, 31, 10, 1, 78000.00, 78000.00, 'UK 12', 'Sustainable Green', 'Eco-friendly dress', '2024-08-20 12:00:00', '2024-08-20 12:00:00'),
(70, 31, 9, 2, 35000.00, 70000.00, 'EU 38', 'Organic White', 'Sustainable t-shirts', '2024-08-20 12:00:00', '2024-08-20 12:00:00'),
(71, 31, 17, 1, 17000.00, 17000.00, 'Universal', 'Natural Dye', 'Eco scarf', '2024-08-20 12:00:00', '2024-08-20 12:00:00'),
(72, 35, 10, 2, 68000.00, 136000.00, 'XXL', 'Navy/Black', 'Plus size dresses', '2024-10-15 10:30:00', '2024-10-15 10:30:00'),
(73, 35, 9, 2, 31000.00, 62000.00, '3XL', 'Mixed', 'Plus size tops', '2024-10-15 10:30:00', '2024-10-15 10:30:00'),
(74, 66, 11, 3, 50000.00, 150000.00, '8-10 years', 'School Colors', 'Back to school', '2025-01-03 07:00:00', '2025-01-03 07:00:00'),
(75, 66, 16, 3, 18333.33, 55000.00, '8-10 years', 'Navy', 'School sports', '2025-01-03 07:00:00', '2025-01-03 07:00:00'),
(76, 66, 26, 2, 10000.00, 20000.00, 'Single', 'White', 'School bedding', '2025-01-03 07:00:00', '2025-01-03 07:00:00'),
(77, 45, 28, 15, 10000.00, 150000.00, 'Mixed S-XL', 'White', 'Choir shirts - bulk', '2025-03-01 11:00:00', '2025-03-01 11:00:00'),
(78, 45, 17, 15, 2333.33, 35000.00, 'One Size', 'Purple', 'Choir scarves', '2025-03-01 11:00:00', '2025-03-01 11:00:00'),
(79, 70, 9, 3, 38000.00, 114000.00, 'Athletic M', 'Performance Black', 'Gym shirts', '2025-04-30 10:00:00', '2025-04-30 10:00:00'),
(80, 70, 16, 3, 40333.33, 121000.00, 'Athletic M', 'Grey/Black', 'Training shorts', '2025-04-30 10:00:00', '2025-04-30 10:00:00'),
(81, 64, 15, 1, 125000.00, 125000.00, 'Custom Fit', 'Traditional Blue', 'Gomesi for event', '2025-05-25 05:30:00', '2025-05-25 05:30:00'),
(82, 64, 19, 1, 20000.00, 20000.00, '6 yards', 'Matching Print', 'Kitenge for sash', '2025-05-25 05:30:00', '2025-05-25 05:30:00'),
(83, 68, 13, 1, 120000.00, 120000.00, 'XL', 'Winter Grey', 'Warm suit', '2025-04-15 08:00:00', '2025-04-15 08:00:00'),
(84, 68, 28, 3, 38333.33, 115000.00, 'XL', 'Warm Colors', 'Winter shirts', '2025-04-15 08:00:00', '2025-04-15 08:00:00'),
(85, 68, 30, 2, 30000.00, 60000.00, '125cm', 'Brown/Black', 'Winter accessories', '2025-04-15 08:00:00', '2025-04-15 08:00:00'),
(86, 76, 28, 2, 42500.00, 85000.00, 'M', 'New Colors', 'Repeat order - liked previous', '2024-09-10 08:00:00', '2024-09-10 08:00:00'),
(87, 76, 10, 1, 80000.00, 80000.00, 'M', 'Autumn Print', 'Seasonal update', '2024-09-10 08:00:00', '2024-09-10 08:00:00'),
(88, 77, 10, 2, 68000.00, 136000.00, 'M', 'Party Colors', 'Event dresses', '2024-12-15 11:00:00', '2024-12-15 11:00:00'),
(89, 77, 17, 2, 31000.00, 62000.00, 'One Size', 'Metallic', 'Party accessories', '2024-12-15 11:00:00', '2024-12-15 11:00:00'),
(90, 78, 13, 2, 190000.00, 380000.00, 'XL', 'Premium Navy', 'VIP suits', '2023-09-20 07:00:00', '2023-09-20 07:00:00'),
(91, 78, 28, 4, 25000.00, 100000.00, 'XL', 'White', 'Premium shirts', '2023-09-20 07:00:00', '2023-09-20 07:00:00'),
(92, 84, 9, 2, 18000.00, 36000.00, 'L', 'Basic Colors', 'Essential t-shirts', '2024-07-10 04:00:00', '2024-07-10 04:00:00'),
(93, 84, 16, 1, 16000.00, 16000.00, 'L', 'Khaki', 'Work shorts', '2024-07-10 04:00:00', '2024-07-10 04:00:00'),
(94, 58, 20, 2, 58000.00, 116000.00, 'L', 'Light Blue', 'Medical scrubs', '2025-07-15 10:30:00', '2025-07-15 10:30:00'),
(95, 58, 28, 2, 24500.00, 49000.00, 'L', 'White', 'Under-scrub shirts', '2025-07-15 10:30:00', '2025-07-15 10:30:00'),
(96, 59, 13, 2, 185000.00, 370000.00, 'XL', 'Executive Grey', 'New season suits', '2025-07-16 07:00:00', '2025-07-16 07:00:00'),
(97, 59, 12, 1, 150000.00, 150000.00, 'King', 'Premium White', 'Luxury bedding', '2025-07-16 07:00:00', '2025-07-16 07:00:00'),
(98, 60, 28, 3, 38000.00, 114000.00, 'M', 'Office Pastels', 'New office wardrobe', '2025-07-16 11:30:00', '2025-07-16 11:30:00'),
(99, 60, 10, 1, 81000.00, 81000.00, 'M', 'Professional Print', 'Office dress', '2025-07-16 11:30:00', '2025-07-16 11:30:00'),
(100, 61, 10, 2, 125000.00, 250000.00, 'L', 'Heritage Prints', 'African heritage dresses', '2025-07-17 05:00:00', '2025-07-17 05:00:00'),
(101, 61, 19, 3, 35000.00, 105000.00, '6 yards', 'Premium Ankara', 'Heritage fabrics', '2025-07-17 05:00:00', '2025-07-17 05:00:00'),
(102, 61, 17, 1, 25000.00, 25000.00, 'One Size', 'Gold Accent', 'Heritage headwrap', '2025-07-17 05:00:00', '2025-07-17 05:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `downtime_logs`
--

CREATE TABLE `downtime_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_id` bigint(20) UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `reason` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) DEFAULT NULL,
  `max_stock` int(11) DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `due_date` date NOT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('raw_material','finished_product') NOT NULL DEFAULT 'raw_material',
  `description` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `material` varchar(255) NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `size_range` varchar(255) DEFAULT NULL,
  `color_options` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `warehouse_id`, `name`, `type`, `description`, `category`, `material`, `base_price`, `size_range`, `color_options`, `stock_quantity`, `image_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Premium Grade A Cotton Bales', 'raw_material', 'High-quality cotton bales with 28-30mm staple length, suitable for fine yarn production', 'Cotton', '100% Raw Cotton', 850000.00, '185kg bales', 'Natural White', 150, 'https://img1.exportersindia.com/product_images/bc-full/2022/6/7241253/shanker-6-premium-raw-cotton-bales-1655874943-6403509.jpeg', 1, '2023-01-20 07:00:00', '2025-07-17 01:00:00'),
(2, 7, 'Organic Cotton Fiber', 'raw_material', 'GOTS certified organic cotton fiber, hand-picked, minimal contamination', 'Cotton', '100% Organic Cotton', 6800.00, 'Per kg', 'Natural Cream', 25000, 'https://www.heddels.com/wp-content/uploads/2018/05/what-is-organic-cotton-and-is-it-worth-it.jpg', 1, '2023-01-25 08:00:00', '2025-07-16 19:30:00'),
(3, 2, 'Reactive Dyes - Blue Series', 'raw_material', 'High-quality reactive dyes for cotton dyeing, excellent colorfastness rating 4-5', 'Chemicals', 'Reactive Dye Compound', 25000.00, '25kg drums', 'Navy, Royal, Sky, Turquoise', 200, 'https://5.imimg.com/data5/PH/TB/ES/SELLER-726042/reactive-blue-49-dyes.jpg', 1, '2023-02-01 06:30:00', '2025-07-17 00:45:00'),
(4, 1, 'Polyester Sewing Thread 40/2', 'raw_material', 'High tenacity polyester thread for industrial sewing, 5000m per cone', 'Thread', '100% Polyester', 12000.00, '5000m cones', 'White, Black, Navy, Red, Green', 5000, 'https://ae01.alicdn.com/kf/HTB15evqdfjM8KJjSZFyq6xdzVXaG/8000-yards-Sewing-thread-polyester-sewing-thread-40-2-High-speed-polyester-sewing-thread.jpg', 1, '2023-02-10 07:30:00', '2025-07-16 15:15:00'),
(5, 10, 'Metal Zippers #5', 'raw_material', 'Heavy-duty metal zippers with auto-lock slider, suitable for jeans and jackets', 'Accessories', 'Brass-plated Metal', 1500.00, '10cm-80cm', 'Gold, Silver, Antique Brass, Black', 20000, 'https://static.fibre2fashion.com/articleresources/images/79/7818/zipper1-big_Big.jpg', 1, '2023-02-15 08:00:00', '2025-07-16 22:15:00'),
(6, 3, 'Cotton Yarn 30s Combed', 'raw_material', 'Premium combed cotton yarn for weaving, consistent quality, low impurities', 'Yarn', '100% Combed Cotton', 8500.00, 'Per kg', 'Natural, Bleached White', 15000, 'https://www.cnhago.com/uploads/202333024/30s-compact-combed-cotton-yarndb87857d-0ee9-4472-bdde-3394251eff7a.jpg', 1, '2023-03-01 05:45:00', '2025-07-15 11:20:00'),
(7, 1, 'Interlining Fusible Medium', 'raw_material', 'Non-woven fusible interlining for collar and cuff reinforcement', 'Interlining', 'Polyester Non-woven', 3500.00, '90cm, 150cm width', 'White, Black, Grey', 10000, 'https://interfacingfabric.com/image/cache/catalog/products/woven-fusible-interlining-01-550x550.jpg', 1, '2023-03-10 06:00:00', '2025-07-16 06:00:00'),
(8, 8, 'Raw Silk Yarn Grade 2A', 'raw_material', 'Fine quality mulberry silk yarn, suitable for luxury fabric production', 'Silk', '100% Mulberry Silk', 85000.00, 'Per kg', 'Natural Ivory', 500, 'https://static6.depositphotos.com/1016824/582/i/950/depositphotos_5825209-stock-photo-raw-silk-yarn-for-weaving.jpg', 1, '2023-03-20 07:30:00', '2025-07-15 08:30:00'),
(9, 1, 'Men\'s Cotton T-Shirt Basic', 'finished_product', 'Premium quality 180 GSM cotton t-shirt, pre-shrunk, comfortable fit for everyday wear', 'Garments', '100% Cotton', 25000.00, 'S, M, L, XL, XXL', 'White, Black, Navy, Grey, Maroon', 5000, 'https://5.imimg.com/data5/FR/JD/MY-1808887/basic-tshirts.jpg', 1, '2023-04-01 08:00:00', '2025-07-17 00:30:00'),
(10, 2, 'Ladies African Print Dress', 'finished_product', 'Stylish knee-length dress with traditional African patterns, perfect for casual and formal occasions', 'Garments', 'Cotton Blend', 65000.00, 'XS, S, M, L, XL', 'Multiple African Print Patterns', 2000, 'https://i.pinimg.com/736x/a0/27/9d/a0279d7b41bd274201efcca3cff457d6--african-print-dresses-african-prints.jpg', 1, '2023-04-10 07:00:00', '2025-07-16 09:30:00'),
(11, 1, 'School Uniform Set - Primary', 'finished_product', 'Complete primary school uniform including shirt and shorts/skirt, durable and easy care', 'Uniforms', 'Poly-Cotton Blend', 45000.00, 'Age 6-16', 'Navy/White, Green/White, Maroon/Grey', 8000, 'https://www.wickprimaryschool.co.uk/_site/data/files/images/uniform/1C089EC388C59F0F46F188BB94DDCF48.JPG', 1, '2023-04-15 06:30:00', '2025-07-16 22:00:00'),
(12, 10, 'Luxury Hotel Bedsheet Set', 'finished_product', 'Thread count 400, includes fitted sheet, flat sheet, and 2 pillow cases, hotel quality', 'Home Textiles', '100% Egyptian Cotton', 120000.00, 'Single, Double, Queen, King', 'White, Cream, Light Blue, Grey', 1500, 'https://hiltonenterprises.com.pk/wp-content/uploads/2023/10/bed-sheets-2.webp', 1, '2023-05-01 08:30:00', '2025-07-16 17:00:00'),
(13, 3, 'Industrial Work Overall', 'finished_product', 'Heavy-duty work overall with multiple pockets, reinforced knees, suitable for factory workers', 'Workwear', 'Cotton Twill', 75000.00, 'S, M, L, XL, XXL, 3XL', 'Navy, Khaki, Orange (Hi-Vis)', 3000, 'https://media.rs-online.com/image/upload/w_620,h_413,c_crop,c_pad,b_white,f_auto,q_auto/dpr_auto/v1494952711/R0754189-01.jpg', 1, '2023-05-10 07:00:00', '2025-07-14 13:45:00'),
(14, 2, 'Cotton Kitchen Towel Set', 'finished_product', 'Set of 6 absorbent cotton kitchen towels, 50x70cm, machine washable', 'Home Textiles', '100% Cotton Terry', 35000.00, '50x70cm', 'White with colored borders', 4000, 'https://i5.walmartimages.com/asr/bda5da7a-22c3-4ad4-9cf6-4392b2a506a2_1.612b2ca20e57c3814e411ccabe5c06a9.jpeg', 1, '2023-05-20 05:30:00', '2025-07-16 23:15:00'),
(15, 5, 'Traditional Gomesi - Ladies', 'finished_product', 'Elegant traditional Ugandan ladies wear, floor-length with puffed sleeves and sash', 'Traditional Wear', 'Cotton/Silk Blend', 180000.00, 'Custom sizing', 'Various colors and patterns', 500, 'https://i.ebayimg.com/images/g/I2gAAOSwnepmGnyk/s-l500.jpg', 1, '2023-06-01 06:00:00', '2025-07-15 15:20:00'),
(16, 6, 'Children\'s Cotton Shorts', 'finished_product', 'Comfortable cotton shorts for children, elastic waistband, suitable for play and school', 'Children\'s Wear', '100% Cotton', 15000.00, 'Age 2-12', 'Navy, Khaki, Black, Grey', 6000, 'https://ae01.alicdn.com/kf/HTB10eDxFuGSBuNjSspbq6AiipXap/Unisex-Summer-Kids-cotton-Shorts-Children-Candy-Solid-Color-Short-Baby-Clothing-Fashion-Pants-Children-Shorts.jpg', 1, '2023-06-10 07:30:00', '2025-07-16 06:45:00'),
(17, 12, 'Fashion Scarf Collection', 'finished_product', 'Lightweight fashion scarves with contemporary African prints, 180x70cm', 'Accessories', 'Cotton Voile', 25000.00, '180x70cm', 'Multiple print designs', 2500, 'https://i.pinimg.com/originals/8a/b8/75/8ab875eb4db03978a817eeb31cf822a4.jpg', 1, '2023-06-20 08:00:00', '2025-07-15 16:00:00'),
(18, 4, 'Heavy Canvas Tarpaulin', 'finished_product', 'Waterproof heavy-duty canvas tarpaulin, reinforced edges with grommets', 'Industrial Textiles', 'Canvas PVC Coated', 45000.00, '3x4m, 4x6m, 6x8m', 'Green, Blue, Grey', 1000, 'https://www.thetarpaulincompany.co.uk/wp-content/uploads/2018/07/IMG_72811.jpg', 1, '2023-07-01 05:00:00', '2025-07-16 15:15:00'),
(19, 11, 'Printed Kitenge Fabric', 'finished_product', 'Authentic East African printed fabric, 6 yards per piece, suitable for various garments', 'Fabric', '100% Cotton', 30000.00, '6 yards/piece', 'Multiple traditional and modern prints', 5000, 'https://d17a17kld06uk8.cloudfront.net/products/6YX1AAL/AI99C19I-original.jpg', 1, '2023-07-10 06:30:00', '2025-07-16 04:30:00'),
(20, 1, 'Medical Scrubs Set', 'finished_product', 'Professional medical uniform set including top and bottom, easy care fabric', 'Medical Wear', 'Poly-Cotton Blend', 55000.00, 'XS, S, M, L, XL, XXL', 'Light Blue, Green, Navy, Wine', 2000, 'https://www.phchefswear.co.uk/wp-content/uploads/2021/05/Main-Image.jpg', 1, '2023-07-20 07:00:00', '2025-07-16 21:30:00'),
(21, 9, 'Cotton Seeds for Oil', 'raw_material', 'High-quality cotton seeds suitable for oil extraction and animal feed production', 'By-products', 'Cotton Seeds', 800.00, 'Per kg', 'Natural', 30000, 'https://www.onlyfoods.net/wp-content/uploads/2011/08/Cottonseed-Oil-Images.jpg', 1, '2023-08-01 08:00:00', '2025-07-13 17:00:00'),
(22, 2, 'Plastic Buttons Assorted', 'raw_material', 'Mixed sizes plastic buttons for garment manufacturing, 4-hole design', 'Accessories', 'ABS Plastic', 15000.00, '18L, 24L, 32L', 'Black, White, Navy, Brown', 10000, 'https://cdn11.bigcommerce.com/s-st781muxzm/images/stencil/1280x1280/products/1311/8582/btn-587-2__29165.1651585233.jpg?c=1', 1, '2023-08-10 06:00:00', '2025-07-17 00:00:00'),
(23, 13, 'Elastic Band 20mm', 'raw_material', 'High-quality elastic band for waistbands and general use', 'Accessories', 'Polyester/Rubber', 1200.00, '20mm width', 'White, Black', 15000, 'https://5.imimg.com/data5/DZ/JB/KR/SELLER-15267430/colorful-font-b-elastic-b-font-font-b-bands-b-font-20mm-flat-sewing-font-b-500x500.jpg', 1, '2023-08-20 07:30:00', '2025-07-14 09:45:00'),
(24, 14, 'Baby Clothes Set', 'finished_product', 'Soft cotton baby clothing set including bodysuit, pants, and bib', 'Baby Wear', '100% Organic Cotton', 35000.00, '0-3m, 3-6m, 6-12m, 12-18m', 'Pastel colors, Prints', 3000, 'https://rukminim2.flixcart.com/image/612/612/xif0q/kids-apparel-combo/c/o/b/0-3-months-presents-new-born-baby-winter-wear-keep-warm-baby-original-imah89h2mdre3agh.jpeg?q=70', 1, '2023-09-01 05:30:00', '2025-07-13 05:30:00'),
(25, 15, 'Mosquito Net - Family Size', 'finished_product', 'Large family-size mosquito net, treated with insecticide, WHO approved', 'Protective Textiles', 'Polyester Mesh', 25000.00, '180x200x150cm', 'White, Light Blue', 5000, 'https://ug.jumia.is/unsafe/fit-in/500x500/filters:fill(white)/product/65/86173/1.jpg?9782', 1, '2023-09-10 08:00:00', '2025-07-12 12:20:00'),
(26, 1, 'Cotton Bedsheet Single', 'finished_product', 'Soft and durable cotton bedsheet for single beds, 180 thread count', 'Home Textiles', '100% Cotton', 35000.00, '90x190cm', 'White, Cream, Light Blue, Pink', 4000, 'https://5.imimg.com/data5/SELLER/Default/2024/4/411483355/RD/YV/GG/2512863/1-2-1000x1000.jpg', 1, '2023-09-20 06:30:00', '2025-07-17 01:00:00'),
(27, 7, 'Bark Cloth Traditional', 'raw_material', 'UNESCO heritage Ugandan bark cloth, naturally processed from Mutuba trees', 'Specialty Material', 'Natural Bark Fiber', 25000.00, 'Per meter', 'Natural Brown shades', 200, 'https://bmastories-uploads.s3.amazonaws.com/1993.194_o4-1024x919.jpg', 1, '2023-10-01 07:00:00', '2025-07-16 19:30:00'),
(28, 2, 'Corporate Polo Shirt', 'finished_product', 'Professional polo shirt suitable for corporate branding, pique cotton', 'Corporate Wear', 'Cotton Pique', 40000.00, 'S, M, L, XL, XXL', 'White, Black, Navy, Royal Blue, Red', 3500, 'https://i.pinimg.com/originals/10/c8/33/10c83374989da68a533c660bc448eb93.jpg', 1, '2023-10-10 08:30:00', '2025-07-17 00:45:00'),
(29, 1, 'Denim Fabric - 12oz', 'raw_material', 'Heavy weight denim fabric for jeans production, sanforized', 'Fabric', '100% Cotton Denim', 18000.00, '150cm width', 'Indigo, Black, Light Blue', 8000, 'https://www.zevadenim.com/wp-content/uploads/2023/12/12oz-Rigid-Denim-Fabric-for-Workwear-Boyfriend-3.webp', 1, '2023-10-20 05:00:00', '2025-07-16 23:00:00'),
(30, 10, 'Leather Handbag Straps', 'raw_material', 'Genuine leather straps for handbag manufacturing, various widths', 'Accessories', 'Genuine Leather', 12000.00, '1.5cm, 2cm, 2.5cm width', 'Brown, Black, Tan', 2000, 'https://i.etsystatic.com/18592262/r/il/ea6ce4/2952674316/il_570xN.2952674316_ohss.jpg', 1, '2023-11-01 06:00:00', '2025-07-16 22:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `business_address` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `license_document` varchar(255) NOT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `production_capacity` int(11) NOT NULL,
  `specialization` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`specialization`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `manufacturers`
--

INSERT INTO `manufacturers` (`id`, `user_id`, `business_address`, `phone`, `license_document`, `document_path`, `production_capacity`, `specialization`, `created_at`, `updated_at`) VALUES
(1, 1, 'Plot 45-50, First Industrial Area, Kampala-Jinja Highway, Namanve Industrial Park, Mukono District, P.O. Box 7865, Kampala, Uganda', '+256414670800', 'URSB/MFG/2023/000145', '/documents/licenses/manufacturer_1_license.pdf', 50000, '{\r\n  \"product_categories\": [\r\n    {\r\n      \"category\": \"Cotton Fabrics\",\r\n      \"types\": [\"Plain Cotton\", \"Printed Cotton\", \"Denim\", \"Canvas\", \"Twill\"],\r\n      \"monthly_capacity\": 20000,\r\n      \"expertise_level\": \"Advanced\",\r\n      \"certifications\": [\"GOTS Organic\", \"OEKO-TEX Standard 100\"]\r\n    },\r\n    {\r\n      \"category\": \"Garments\",\r\n      \"types\": [\"T-Shirts\", \"Shirts\", \"Dresses\", \"School Uniforms\", \"Work Uniforms\"],\r\n      \"monthly_capacity\": 15000,\r\n      \"expertise_level\": \"Expert\",\r\n      \"export_ready\": true\r\n    },\r\n    {\r\n      \"category\": \"Home Textiles\",\r\n      \"types\": [\"Bed Sheets\", \"Pillow Cases\", \"Curtains\", \"Kitchen Textiles\", \"Towels\"],\r\n      \"monthly_capacity\": 10000,\r\n      \"expertise_level\": \"Advanced\"\r\n    },\r\n    {\r\n      \"category\": \"Industrial Textiles\",\r\n      \"types\": [\"Medical Textiles\", \"Protective Clothing\", \"Canvas Products\", \"Tarpaulins\"],\r\n      \"monthly_capacity\": 5000,\r\n      \"expertise_level\": \"Intermediate\",\r\n      \"b2b_focused\": true\r\n    }\r\n  ],\r\n  \"manufacturing_capabilities\": {\r\n    \"spinning\": {\r\n      \"capacity\": \"50 tons/month\",\r\n      \"yarn_counts\": [\"20s\", \"30s\", \"40s\", \"60s\"],\r\n      \"fiber_types\": [\"Cotton\", \"Polyester\", \"Blended\"]\r\n    },\r\n    \"weaving\": {\r\n      \"looms\": 45,\r\n      \"loom_types\": [\"Shuttle\", \"Projectile\", \"Air-jet\"],\r\n      \"fabric_width\": \"36-120 inches\",\r\n      \"patterns\": [\"Plain\", \"Twill\", \"Satin\", \"Jacquard\"]\r\n    },\r\n    \"dyeing_printing\": {\r\n      \"capacity\": \"30000 meters/day\",\r\n      \"techniques\": [\"Reactive Dyeing\", \"Vat Dyeing\", \"Digital Printing\", \"Screen Printing\"],\r\n      \"color_matching\": \"Pantone certified\"\r\n    },\r\n    \"finishing\": {\r\n      \"processes\": [\"Sanforizing\", \"Mercerizing\", \"Calendering\", \"Anti-bacterial\", \"Water-repellent\"],\r\n      \"quality_control\": \"ISO 9001:2015 certified\"\r\n    },\r\n    \"cutting_sewing\": {\r\n      \"production_lines\": 8,\r\n      \"workers\": 450,\r\n      \"cad_cam_enabled\": true,\r\n      \"embroidery_units\": 5\r\n    }\r\n  },\r\n  \"equipment_details\": {\r\n    \"machinery_origin\": [\"Japan\", \"Germany\", \"Italy\", \"India\"],\r\n    \"automation_level\": \"Semi-automated\",\r\n    \"maintenance_schedule\": \"Preventive maintenance program\",\r\n    \"average_machine_age\": \"5 years\"\r\n  },\r\n  \"market_focus\": {\r\n    \"domestic_market\": 60,\r\n    \"export_market\": 40,\r\n    \"export_destinations\": [\"Kenya\", \"Tanzania\", \"Rwanda\", \"South Sudan\", \"DRC\"],\r\n    \"major_clients\": [\"Schools\", \"Hospitals\", \"Hotels\", \"Retail Chains\", \"Government\"]\r\n  },\r\n  \"sustainability\": {\r\n    \"water_recycling\": true,\r\n    \"solar_power_percentage\": 30,\r\n    \"waste_management\": \"Zero liquid discharge\",\r\n    \"fair_trade_certified\": true,\r\n    \"carbon_footprint_tracking\": true\r\n  },\r\n  \"workforce\": {\r\n    \"total_employees\": 650,\r\n    \"skilled_workers\": 450,\r\n    \"technical_staff\": 80,\r\n    \"management\": 50,\r\n    \"quality_control\": 70,\r\n    \"gender_ratio\": {\r\n      \"female\": 65,\r\n      \"male\": 35\r\n    },\r\n    \"training_programs\": [\"Skills development\", \"Safety training\", \"Quality management\"]\r\n  },\r\n  \"quality_standards\": {\r\n    \"certifications\": [\"ISO 9001:2015\", \"ISO 14001:2015\", \"SA8000\"],\r\n    \"testing_lab\": \"In-house NABL accredited lab\",\r\n    \"defect_rate\": \"< 2%\",\r\n    \"on_time_delivery\": \"95%\"\r\n  },\r\n  \"supply_chain_integration\": {\r\n    \"backward_integration\": [\"Cotton sourcing contracts\", \"Yarn production\"],\r\n    \"forward_integration\": [\"Direct retail outlets\", \"E-commerce platform\"],\r\n    \"vendor_partnerships\": 33,\r\n    \"lead_time\": \"15-45 days depending on product\"\r\n  }\r\n}', '2023-01-15 06:30:00', '2025-07-17 01:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_03_19_000001_create_suppliers_table', 1),
(6, '2024_03_19_000002_create_manufacturers_table', 1),
(7, '2024_03_19_000003_create_wholesalers_table', 1),
(8, '2024_07_10_000001_add_last_seen_to_users_table', 1),
(9, '2025_06_17_025144_create_items_table', 1),
(10, '2025_06_17_025236_create_supply_requests_table', 1),
(11, '2025_06_17_025300_create_supplied_items_table', 1),
(12, '2025_06_17_025336_create_price_negotiations_table', 1),
(13, '2025_06_17_082456_add_is_verified_to_users_table', 1),
(14, '2025_06_18_044146_add_profile_picture_to_users_table', 1),
(15, '2025_06_18_054611_create_orders_table', 1),
(16, '2025_06_18_054612_create_order_items_table', 1),
(17, '2025_06_18_080122_create_chat_messages_table', 1),
(18, '2025_06_19_000001_create_workforces_table', 1),
(19, '2025_06_19_000002_create_warehouses_table', 1),
(20, '2025_06_25_112651_create_pending_users_table', 1),
(21, '2025_07_01_000001_create_work_orders_table', 1),
(22, '2025_07_01_000002_create_bill_of_materials_table', 1),
(23, '2025_07_01_000003_create_bill_of_material_components_table', 1),
(24, '2025_07_01_000004_create_production_schedules_table', 1),
(25, '2025_07_01_000005_create_quality_checks_table', 1),
(26, '2025_07_01_000006_create_work_order_assignments_table', 1),
(27, '2025_07_01_000007_create_downtime_logs_table', 1),
(28, '2025_07_01_000008_create_production_costs_table', 1),
(29, '2025_07_08_090902_add_type_to_items_table', 1),
(30, '2025_07_09_062523_create_invoices_table', 1),
(31, '2025_07_11_054219_create_inventory_items_table', 1),
(32, '2025_07_12_000000_create_warehouse_workforce_table', 1),
(33, '2025_07_12_100000_add_warehouse_id_to_items_table', 1),
(34, '2025_07_15_000001_create_customers_table', 1),
(35, '2025_07_15_000002_create_customer_orders_table', 1),
(36, '2025_07_15_000003_create_customer_order_items_table', 1),
(37, '2025_07_15_100000_add_last_seen_to_customers_table', 1),
(38, '2025_07_15_174847_create_system_settings_table', 1),
(39, '2025_07_15_180247_create_audit_logs_table', 1),
(40, '2025_07_15_182510_create_notifications_table2', 1),
(41, '2025_07_15_180250_create_notification_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wholesaler_id` bigint(20) UNSIGNED NOT NULL,
  `manufacturer_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `status` enum('pending','confirmed','in_production','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `order_date` datetime NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `payment_method` enum('cash on delivery','mobile money','bank_transfer') NOT NULL DEFAULT 'cash on delivery',
  `delivery_address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `estimated_delivery` datetime DEFAULT NULL,
  `actual_delivery` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `wholesaler_id`, `manufacturer_id`, `order_number`, `status`, `order_date`, `total_amount`, `payment_method`, `delivery_address`, `notes`, `estimated_delivery`, `actual_delivery`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'ORD-2023-001', 'delivered', '2023-06-15 09:00:00', 12500000.00, 'bank_transfer', 'Kampala', 'First bulk order - school uniforms for new term', '2023-06-30 10:00:00', '2023-06-28 14:30:00', '2023-06-15 06:30:00', '2023-06-28 11:30:00'),
(2, 2, 1, 'ORD-2023-002', 'delivered', '2023-06-20 10:30:00', 8750000.00, 'mobile money', 'Jinja', 'Industrial textiles - canvas and workwear', '2023-07-05 09:00:00', '2023-07-06 11:00:00', '2023-06-20 08:00:00', '2023-07-06 08:00:00'),
(3, 3, 1, 'ORD-2023-003', 'delivered', '2023-07-01 11:00:00', 6200000.00, 'cash on delivery', 'Mbarara', 'Fashion garments - ladies wear collection', '2023-07-20 10:00:00', '2023-07-19 15:00:00', '2023-07-01 08:30:00', '2023-07-19 12:00:00'),
(4, 4, 1, 'ORD-2023-004', 'delivered', '2023-07-10 08:45:00', 4500000.00, 'bank_transfer', 'Gulu', 'Mixed order - traditional and general fabrics', '2023-07-25 09:00:00', '2023-07-26 12:00:00', '2023-07-10 06:00:00', '2023-07-26 09:00:00'),
(5, 5, 1, 'ORD-2023-005', 'delivered', '2023-07-15 14:00:00', 3800000.00, 'mobile money', 'Masaka', 'Wedding fabrics and accessories', '2023-08-01 10:00:00', '2023-07-31 16:00:00', '2023-07-15 11:30:00', '2023-07-31 13:00:00'),
(6, 1, 1, 'ORD-2023-006', 'delivered', '2023-08-05 09:30:00', 15000000.00, 'bank_transfer', 'Kampala', 'Back-to-school campaign - uniforms bulk', '2023-08-20 09:00:00', '2023-08-18 10:00:00', '2023-08-05 07:00:00', '2023-08-18 07:00:00'),
(7, 7, 1, 'ORD-2023-007', 'delivered', '2023-08-12 10:00:00', 9500000.00, 'bank_transfer', 'Mbale', 'School uniforms - Eastern region schools', '2023-08-25 10:00:00', '2023-08-24 14:00:00', '2023-08-12 07:30:00', '2023-08-24 11:00:00'),
(8, 6, 1, 'ORD-2023-008', 'cancelled', '2023-08-20 11:30:00', 3200000.00, 'mobile money', 'Fort Portal', 'Hotel linens order', '2023-09-05 09:00:00', NULL, '2023-08-20 09:00:00', '2023-08-25 06:00:00'),
(9, 8, 1, 'ORD-2023-009', 'delivered', '2023-09-01 08:00:00', 5600000.00, 'cash on delivery', 'Arua', 'Cross-border trade goods', '2023-09-20 10:00:00', '2023-09-22 11:00:00', '2023-09-01 05:30:00', '2023-09-22 08:00:00'),
(10, 9, 1, 'ORD-2023-010', 'delivered', '2023-09-10 13:00:00', 11000000.00, 'bank_transfer', 'Entebbe', 'Export quality garments - AGOA compliant', '2023-09-25 09:00:00', '2023-09-23 15:00:00', '2023-09-10 10:30:00', '2023-09-23 12:00:00'),
(11, 10, 1, 'ORD-2023-011', 'delivered', '2023-09-15 10:30:00', 3000000.00, 'mobile money', 'Lira', 'Cotton products for local market', '2023-10-01 10:00:00', '2023-10-02 09:00:00', '2023-09-15 08:00:00', '2023-10-02 06:00:00'),
(12, 11, 1, 'ORD-2023-012', 'delivered', '2023-10-05 09:00:00', 6800000.00, 'bank_transfer', 'Hoima', 'Industrial workwear - oil sector', '2023-10-20 09:00:00', '2023-10-19 12:00:00', '2023-10-05 06:30:00', '2023-10-19 09:00:00'),
(13, 12, 1, 'ORD-2023-013', 'delivered', '2023-10-12 11:00:00', 7200000.00, 'mobile money', 'Wakiso', 'Urban fashion and home textiles', '2023-10-27 10:00:00', '2023-10-26 14:00:00', '2023-10-12 08:30:00', '2023-10-26 11:00:00'),
(14, 2, 1, 'ORD-2023-014', 'delivered', '2023-10-20 14:00:00', 9200000.00, 'bank_transfer', 'Jinja', 'Year-end industrial supplies', '2023-11-05 09:00:00', '2023-11-04 10:00:00', '2023-10-20 11:30:00', '2023-11-04 07:00:00'),
(15, 3, 1, 'ORD-2023-015', 'delivered', '2023-11-01 10:00:00', 5500000.00, 'cash on delivery', 'Mbarara', 'Christmas season fashion items', '2023-11-20 10:00:00', '2023-11-21 15:00:00', '2023-11-01 07:30:00', '2023-11-21 12:00:00'),
(16, 1, 1, 'ORD-2023-016', 'delivered', '2023-11-10 08:30:00', 18000000.00, 'bank_transfer', 'Kampala', 'End of year bulk order - mixed products', '2023-11-25 09:00:00', '2023-11-24 11:00:00', '2023-11-10 06:00:00', '2023-11-24 08:00:00'),
(17, 4, 1, 'ORD-2023-017', 'delivered', '2023-11-15 09:45:00', 4200000.00, 'mobile money', 'Gulu', 'Traditional wear for festive season', '2023-12-01 10:00:00', '2023-11-30 14:00:00', '2023-11-15 07:00:00', '2023-11-30 11:00:00'),
(18, 5, 1, 'ORD-2023-018', 'delivered', '2023-12-01 11:00:00', 4500000.00, 'bank_transfer', 'Masaka', 'Wedding season special fabrics', '2023-12-15 10:00:00', '2023-12-14 16:00:00', '2023-12-01 08:30:00', '2023-12-14 13:00:00'),
(19, 7, 1, 'ORD-2023-019', 'delivered', '2023-12-10 10:00:00', 7800000.00, 'mobile money', 'Mbale', 'January school preparation', '2023-12-28 09:00:00', '2023-12-27 12:00:00', '2023-12-10 07:30:00', '2023-12-27 09:00:00'),
(20, 1, 1, 'ORD-2023-020', 'delivered', '2023-12-20 13:00:00', 14500000.00, 'bank_transfer', 'Kampala', 'New year stock preparation', '2024-01-05 10:00:00', '2024-01-04 15:00:00', '2023-12-20 10:30:00', '2024-01-04 12:00:00'),
(21, 2, 1, 'ORD-2024-001', 'delivered', '2024-01-10 09:00:00', 10500000.00, 'bank_transfer', 'Jinja', 'Q1 industrial textiles order', '2024-01-25 09:00:00', '2024-01-24 11:00:00', '2024-01-10 06:30:00', '2024-01-24 08:00:00'),
(22, 6, 1, 'ORD-2024-002', 'delivered', '2024-01-15 10:30:00', 4200000.00, 'mobile money', 'Fort Portal', 'Hotel sector recovery order', '2024-02-01 10:00:00', '2024-01-31 14:00:00', '2024-01-15 08:00:00', '2024-01-31 11:00:00'),
(23, 8, 1, 'ORD-2024-003', 'delivered', '2024-01-20 08:00:00', 6300000.00, 'cash on delivery', 'Arua', 'Cross-border January trade', '2024-02-05 09:00:00', '2024-02-06 10:00:00', '2024-01-20 05:30:00', '2024-02-06 07:00:00'),
(24, 1, 1, 'ORD-2024-004', 'delivered', '2024-02-01 11:00:00', 16000000.00, 'bank_transfer', 'Kampala', 'School term 1 mega order', '2024-02-15 09:00:00', '2024-02-13 12:00:00', '2024-02-01 08:30:00', '2024-02-13 09:00:00'),
(25, 3, 1, 'ORD-2024-005', 'delivered', '2024-02-10 14:00:00', 7500000.00, 'bank_transfer', 'Mbarara', 'Valentine season fashion', '2024-02-25 10:00:00', '2024-02-24 15:00:00', '2024-02-10 11:30:00', '2024-02-24 12:00:00'),
(26, 9, 1, 'ORD-2024-006', 'delivered', '2024-02-20 09:30:00', 13000000.00, 'bank_transfer', 'Entebbe', 'Export shipment - EU market', '2024-03-05 09:00:00', '2024-03-04 11:00:00', '2024-02-20 07:00:00', '2024-03-04 08:00:00'),
(27, 11, 1, 'ORD-2024-007', 'delivered', '2024-03-01 10:00:00', 8500000.00, 'bank_transfer', 'Hoima', 'Oil sector quarterly order', '2024-03-15 10:00:00', '2024-03-14 13:00:00', '2024-03-01 07:30:00', '2024-03-14 10:00:00'),
(28, 4, 1, 'ORD-2024-008', 'delivered', '2024-03-10 08:45:00', 5200000.00, 'mobile money', 'Gulu', 'Easter preparations - traditional wear', '2024-03-25 09:00:00', '2024-03-26 12:00:00', '2024-03-10 06:00:00', '2024-03-26 09:00:00'),
(29, 12, 1, 'ORD-2024-009', 'delivered', '2024-03-20 11:30:00', 8900000.00, 'mobile money', 'Wakiso', 'Q1 closing fashion order', '2024-04-05 10:00:00', '2024-04-04 14:00:00', '2024-03-20 09:00:00', '2024-04-04 11:00:00'),
(30, 5, 1, 'ORD-2024-010', 'delivered', '2024-04-01 13:00:00', 5600000.00, 'bank_transfer', 'Masaka', 'Wedding season start order', '2024-04-15 10:00:00', '2024-04-14 16:00:00', '2024-04-01 10:30:00', '2024-04-14 13:00:00'),
(31, 7, 1, 'ORD-2024-011', 'delivered', '2024-04-10 09:00:00', 11000000.00, 'bank_transfer', 'Mbale', 'School term 2 preparation', '2024-04-25 09:00:00', '2024-04-24 11:00:00', '2024-04-10 06:30:00', '2024-04-24 08:00:00'),
(32, 1, 1, 'ORD-2024-012', 'delivered', '2024-04-20 10:30:00', 19500000.00, 'bank_transfer', 'Kampala', 'Q2 opening bulk order', '2024-05-05 09:00:00', '2024-05-03 12:00:00', '2024-04-20 08:00:00', '2024-05-03 09:00:00'),
(33, 2, 1, 'ORD-2024-013', 'delivered', '2024-05-01 08:00:00', 12000000.00, 'bank_transfer', 'Jinja', 'Industrial expansion order', '2024-05-15 09:00:00', '2024-05-14 10:00:00', '2024-05-01 05:30:00', '2024-05-14 07:00:00'),
(34, 10, 1, 'ORD-2024-014', 'delivered', '2024-05-10 11:00:00', 4000000.00, 'mobile money', 'Lira', 'Cotton season special', '2024-05-25 10:00:00', '2024-05-26 09:00:00', '2024-05-10 08:30:00', '2024-05-26 06:00:00'),
(35, 8, 1, 'ORD-2024-015', 'delivered', '2024-05-20 09:30:00', 7200000.00, 'cash on delivery', 'Arua', 'Regional market expansion', '2024-06-05 10:00:00', '2024-06-06 11:00:00', '2024-05-20 07:00:00', '2024-06-06 08:00:00'),
(36, 3, 1, 'ORD-2024-016', 'delivered', '2024-06-01 14:00:00', 8800000.00, 'bank_transfer', 'Mbarara', 'Mid-year fashion collection', '2024-06-15 10:00:00', '2024-06-14 15:00:00', '2024-06-01 11:30:00', '2024-06-14 12:00:00'),
(37, 6, 1, 'ORD-2024-017', 'delivered', '2024-06-10 10:00:00', 5500000.00, 'mobile money', 'Fort Portal', 'Tourism season hospitality textiles', '2024-06-25 09:00:00', '2024-06-24 13:00:00', '2024-06-10 07:30:00', '2024-06-24 10:00:00'),
(38, 9, 1, 'ORD-2024-018', 'delivered', '2024-06-20 08:30:00', 15000000.00, 'bank_transfer', 'Entebbe', 'Major export order - USA AGOA', '2024-07-05 09:00:00', '2024-07-03 14:00:00', '2024-06-20 06:00:00', '2024-07-03 11:00:00'),
(39, 11, 1, 'ORD-2024-019', 'delivered', '2024-07-01 11:00:00', 9500000.00, 'bank_transfer', 'Hoima', 'Oil sector mid-year order', '2024-07-15 10:00:00', '2024-07-14 12:00:00', '2024-07-01 08:30:00', '2024-07-14 09:00:00'),
(40, 1, 1, 'ORD-2024-020', 'delivered', '2024-07-10 09:00:00', 21000000.00, 'bank_transfer', 'Kampala', 'School term 3 mega preparation', '2024-07-25 09:00:00', '2024-07-23 10:00:00', '2024-07-10 06:30:00', '2024-07-23 07:00:00'),
(41, 7, 1, 'ORD-2024-021', 'delivered', '2024-08-01 10:00:00', 13500000.00, 'bank_transfer', 'Mbale', 'Back to school campaign', '2024-08-15 09:00:00', '2024-08-14 11:00:00', '2024-08-01 07:30:00', '2024-08-14 08:00:00'),
(42, 4, 1, 'ORD-2024-022', 'delivered', '2024-08-10 08:45:00', 6000000.00, 'mobile money', 'Gulu', 'Independence celebrations textiles', '2024-08-25 09:00:00', '2024-08-26 14:00:00', '2024-08-10 06:00:00', '2024-08-26 11:00:00'),
(43, 12, 1, 'ORD-2024-023', 'delivered', '2024-08-20 11:30:00', 10200000.00, 'mobile money', 'Wakiso', 'Urban market expansion', '2024-09-05 10:00:00', '2024-09-04 13:00:00', '2024-08-20 09:00:00', '2024-09-04 10:00:00'),
(44, 2, 1, 'ORD-2024-024', 'delivered', '2024-09-01 09:00:00', 14000000.00, 'bank_transfer', 'Jinja', 'Q3 industrial mega order', '2024-09-15 09:00:00', '2024-09-14 10:00:00', '2024-09-01 06:30:00', '2024-09-14 07:00:00'),
(45, 5, 1, 'ORD-2024-025', 'delivered', '2024-09-10 13:00:00', 6800000.00, 'bank_transfer', 'Masaka', 'Wedding peak season order', '2024-09-25 10:00:00', '2024-09-24 15:00:00', '2024-09-10 10:30:00', '2024-09-24 12:00:00'),
(46, 8, 1, 'ORD-2024-026', 'delivered', '2024-09-20 08:00:00', 8500000.00, 'cash on delivery', 'Arua', 'Cross-border festive goods', '2024-10-05 09:00:00', '2024-10-06 11:00:00', '2024-09-20 05:30:00', '2024-10-06 08:00:00'),
(47, 3, 1, 'ORD-2024-027', 'delivered', '2024-10-01 14:00:00', 11000000.00, 'bank_transfer', 'Mbarara', 'October fashion trends', '2024-10-15 10:00:00', '2024-10-14 14:00:00', '2024-10-01 11:30:00', '2024-10-14 11:00:00'),
(48, 9, 1, 'ORD-2024-028', 'delivered', '2024-10-10 09:30:00', 17500000.00, 'bank_transfer', 'Entebbe', 'Q4 export preparation', '2024-10-25 09:00:00', '2024-10-23 12:00:00', '2024-10-10 07:00:00', '2024-10-23 09:00:00'),
(49, 1, 1, 'ORD-2024-029', 'delivered', '2024-10-20 10:00:00', 23000000.00, 'bank_transfer', 'Kampala', 'Year-end stock buildup', '2024-11-05 09:00:00', '2024-11-03 11:00:00', '2024-10-20 07:30:00', '2024-11-03 08:00:00'),
(50, 11, 1, 'ORD-2024-030', 'delivered', '2024-11-01 11:00:00', 12000000.00, 'bank_transfer', 'Hoima', 'Oil sector year-end order', '2024-11-15 10:00:00', '2024-11-14 13:00:00', '2024-11-01 08:30:00', '2024-11-14 10:00:00'),
(51, 6, 1, 'ORD-2024-031', 'delivered', '2024-11-10 10:00:00', 7200000.00, 'mobile money', 'Fort Portal', 'Holiday season hospitality', '2024-11-25 09:00:00', '2024-11-24 14:00:00', '2024-11-10 07:30:00', '2024-11-24 11:00:00'),
(52, 7, 1, 'ORD-2024-032', 'delivered', '2024-11-20 09:00:00', 15500000.00, 'bank_transfer', 'Mbale', 'Christmas & new year preparation', '2024-12-05 09:00:00', '2024-12-04 10:00:00', '2024-11-20 06:30:00', '2024-12-04 07:00:00'),
(53, 4, 1, 'ORD-2024-033', 'delivered', '2024-12-01 08:45:00', 7500000.00, 'mobile money', 'Gulu', 'Festive traditional wear', '2024-12-15 09:00:00', '2024-12-14 12:00:00', '2024-12-01 06:00:00', '2024-12-14 09:00:00'),
(54, 2, 1, 'ORD-2024-034', 'delivered', '2024-12-10 09:00:00', 16000000.00, 'bank_transfer', 'Jinja', '2025 preparation order', '2024-12-25 09:00:00', '2024-12-23 11:00:00', '2024-12-10 06:30:00', '2024-12-23 08:00:00'),
(55, 12, 1, 'ORD-2024-035', 'delivered', '2024-12-20 11:30:00', 13000000.00, 'mobile money', 'Wakiso', 'New year fashion stock', '2025-01-05 10:00:00', '2025-01-04 14:00:00', '2024-12-20 09:00:00', '2025-01-04 11:00:00'),
(56, 1, 1, 'ORD-2025-001', 'delivered', '2025-01-05 09:00:00', 25000000.00, 'bank_transfer', 'Kampala', 'New year opening mega order', '2025-01-20 09:00:00', '2025-01-18 10:00:00', '2025-01-05 06:30:00', '2025-01-18 07:00:00'),
(57, 3, 1, 'ORD-2025-002', 'delivered', '2025-01-10 14:00:00', 9500000.00, 'bank_transfer', 'Mbarara', 'Valentine collection early order', '2025-01-25 10:00:00', '2025-01-24 15:00:00', '2025-01-10 11:30:00', '2025-01-24 12:00:00'),
(58, 8, 1, 'ORD-2025-003', 'delivered', '2025-01-15 08:00:00', 10000000.00, 'cash on delivery', 'Arua', 'Q1 cross-border trade', '2025-01-30 09:00:00', '2025-01-31 11:00:00', '2025-01-15 05:30:00', '2025-01-31 08:00:00'),
(59, 9, 1, 'ORD-2025-004', 'delivered', '2025-01-20 09:30:00', 19000000.00, 'bank_transfer', 'Entebbe', 'Major EU export shipment', '2025-02-05 09:00:00', '2025-02-03 12:00:00', '2025-01-20 07:00:00', '2025-02-03 09:00:00'),
(60, 5, 1, 'ORD-2025-005', 'delivered', '2025-02-01 13:00:00', 8200000.00, 'bank_transfer', 'Masaka', 'Wedding season 2025 start', '2025-02-15 10:00:00', '2025-02-14 16:00:00', '2025-02-01 10:30:00', '2025-02-14 13:00:00'),
(61, 11, 1, 'ORD-2025-006', 'delivered', '2025-02-10 11:00:00', 14500000.00, 'bank_transfer', 'Hoima', 'Oil sector Q1 major order', '2025-02-25 10:00:00', '2025-02-24 13:00:00', '2025-02-10 08:30:00', '2025-02-24 10:00:00'),
(62, 7, 1, 'ORD-2025-007', 'delivered', '2025-02-20 10:00:00', 17000000.00, 'bank_transfer', 'Mbale', 'School term 1 2025 bulk', '2025-03-05 09:00:00', '2025-03-04 11:00:00', '2025-02-20 07:30:00', '2025-03-04 08:00:00'),
(63, 2, 1, 'ORD-2025-008', 'delivered', '2025-03-01 09:00:00', 18500000.00, 'bank_transfer', 'Jinja', 'Q1 closing industrial order', '2025-03-15 09:00:00', '2025-03-14 10:00:00', '2025-03-01 06:30:00', '2025-03-14 07:00:00'),
(64, 4, 1, 'ORD-2025-009', 'delivered', '2025-03-10 08:45:00', 8800000.00, 'mobile money', 'Gulu', 'Easter 2025 preparations', '2025-03-25 09:00:00', '2025-03-24 12:00:00', '2025-03-10 06:00:00', '2025-03-24 09:00:00'),
(65, 12, 1, 'ORD-2025-010', 'delivered', '2025-03-20 11:30:00', 15500000.00, 'mobile money', 'Wakiso', 'Q2 opening fashion order', '2025-04-05 10:00:00', '2025-04-04 14:00:00', '2025-03-20 09:00:00', '2025-04-04 11:00:00'),
(66, 1, 1, 'ORD-2025-011', 'delivered', '2025-04-01 09:00:00', 28000000.00, 'bank_transfer', 'Kampala', 'Q2 2025 mega stock order', '2025-04-15 09:00:00', '2025-04-14 10:00:00', '2025-04-01 06:30:00', '2025-04-14 07:00:00'),
(67, 6, 1, 'ORD-2025-012', 'delivered', '2025-04-10 10:00:00', 9800000.00, 'mobile money', 'Fort Portal', 'Tourism season preparation', '2025-04-25 09:00:00', '2025-04-24 13:00:00', '2025-04-10 07:30:00', '2025-04-24 10:00:00'),
(68, 3, 1, 'ORD-2025-013', 'delivered', '2025-04-20 14:00:00', 12500000.00, 'bank_transfer', 'Mbarara', 'Labour Day fashion specials', '2025-05-05 10:00:00', '2025-05-04 15:00:00', '2025-04-20 11:30:00', '2025-05-04 12:00:00'),
(69, 10, 1, 'ORD-2025-014', 'delivered', '2025-05-01 11:00:00', 5500000.00, 'mobile money', 'Lira', 'Cotton harvest season order', '2025-05-15 10:00:00', '2025-05-16 09:00:00', '2025-05-01 08:30:00', '2025-05-16 06:00:00'),
(70, 9, 1, 'ORD-2025-015', 'delivered', '2025-05-10 09:30:00', 22000000.00, 'bank_transfer', 'Entebbe', 'USA AGOA compliance shipment', '2025-05-25 09:00:00', '2025-05-23 12:00:00', '2025-05-10 07:00:00', '2025-05-23 09:00:00'),
(71, 7, 1, 'ORD-2025-016', 'delivered', '2025-05-20 10:00:00', 19500000.00, 'bank_transfer', 'Mbale', 'Mid-year school supplies', '2025-06-05 09:00:00', '2025-06-04 11:00:00', '2025-05-20 07:30:00', '2025-06-04 08:00:00'),
(72, 2, 1, 'ORD-2025-017', 'delivered', '2025-06-01 09:00:00', 21000000.00, 'bank_transfer', 'Jinja', 'June industrial expansion', '2025-06-15 09:00:00', '2025-06-14 10:00:00', '2025-06-01 06:30:00', '2025-06-14 07:00:00'),
(73, 8, 1, 'ORD-2025-018', 'shipped', '2025-06-10 08:00:00', 13500000.00, 'cash on delivery', 'Arua', 'Independence preparation order', '2025-06-25 09:00:00', NULL, '2025-06-10 05:30:00', '2025-06-20 11:00:00'),
(74, 11, 1, 'ORD-2025-019', 'shipped', '2025-06-20 11:00:00', 18000000.00, 'bank_transfer', 'Hoima', 'Oil sector mid-year order', '2025-07-05 10:00:00', NULL, '2025-06-20 08:30:00', '2025-07-01 06:00:00'),
(75, 5, 1, 'ORD-2025-020', 'in_production', '2025-06-25 13:00:00', 11200000.00, 'bank_transfer', 'Masaka', 'July wedding season rush', '2025-07-10 10:00:00', NULL, '2025-06-25 10:30:00', '2025-07-05 07:00:00'),
(76, 1, 1, 'ORD-2025-021', 'in_production', '2025-07-01 09:00:00', 32000000.00, 'bank_transfer', 'Kampala', 'Q3 opening mega order - school preparation', '2025-07-20 09:00:00', NULL, '2025-07-01 06:30:00', '2025-07-15 05:00:00'),
(77, 4, 1, 'ORD-2025-022', 'in_production', '2025-07-05 08:45:00', 10500000.00, 'mobile money', 'Gulu', 'Cultural festival textiles', '2025-07-25 09:00:00', NULL, '2025-07-05 06:00:00', '2025-07-15 07:00:00'),
(78, 12, 1, 'ORD-2025-023', 'confirmed', '2025-07-08 11:30:00', 17800000.00, 'mobile money', 'Wakiso', 'Urban fashion July collection', '2025-07-28 10:00:00', NULL, '2025-07-08 09:00:00', '2025-07-10 11:00:00'),
(79, 3, 1, 'ORD-2025-024', 'confirmed', '2025-07-10 14:00:00', 14500000.00, 'bank_transfer', 'Mbarara', 'August fashion pre-order', '2025-07-30 10:00:00', NULL, '2025-07-10 11:30:00', '2025-07-12 06:00:00'),
(80, 9, 1, 'ORD-2025-025', 'confirmed', '2025-07-12 09:30:00', 26000000.00, 'bank_transfer', 'Entebbe', 'Q3 export preparation - EU market', '2025-08-01 09:00:00', NULL, '2025-07-12 07:00:00', '2025-07-13 08:00:00'),
(81, 2, 1, 'ORD-2025-026', 'pending', '2025-07-14 09:00:00', 24000000.00, 'bank_transfer', 'Jinja', 'Industrial textiles Q3 bulk', '2025-08-05 09:00:00', NULL, '2025-07-14 06:30:00', '2025-07-14 06:30:00'),
(82, 7, 1, 'ORD-2025-027', 'pending', '2025-07-15 10:00:00', 22500000.00, 'bank_transfer', 'Mbale', 'Back to school campaign 2025', '2025-08-10 09:00:00', NULL, '2025-07-15 07:30:00', '2025-07-15 07:30:00'),
(83, 6, 1, 'ORD-2025-028', 'pending', '2025-07-16 10:00:00', 12000000.00, 'mobile money', 'Fort Portal', 'Peak tourism season order', '2025-08-15 09:00:00', NULL, '2025-07-16 07:30:00', '2025-07-16 07:30:00'),
(84, 8, 1, 'ORD-2025-029', 'pending', '2025-07-16 14:00:00', 15500000.00, 'cash on delivery', 'Arua', 'Cross-border August trade', '2025-08-20 10:00:00', NULL, '2025-07-16 11:30:00', '2025-07-16 11:30:00'),
(85, 11, 1, 'ORD-2025-030', 'pending', '2025-07-17 11:00:00', 20000000.00, 'bank_transfer', 'Hoima', 'Oil sector Q3 requirements', '2025-08-25 10:00:00', NULL, '2025-07-17 08:30:00', '2025-07-17 08:30:00'),
(86, 5, 1, 'ORD-2025-031', 'cancelled', '2025-07-02 13:00:00', 7500000.00, 'bank_transfer', 'Masaka', 'Duplicate order - cancelled', '2025-07-20 10:00:00', NULL, '2025-07-02 10:30:00', '2025-07-03 06:00:00'),
(87, 10, 1, 'ORD-2024-040', 'cancelled', '2024-11-25 11:00:00', 3500000.00, 'mobile money', 'Lira', 'Insufficient stock - cancelled', '2024-12-10 10:00:00', NULL, '2024-11-25 08:30:00', '2024-11-26 05:00:00'),
(88, 4, 1, 'ORD-2024-041', 'cancelled', '2024-09-05 08:45:00', 4200000.00, 'mobile money', 'Gulu', 'Customer changed requirements', '2024-09-20 09:00:00', NULL, '2024-09-05 06:00:00', '2024-09-06 07:00:00'),
(89, 1, 1, 'ORD-2023-025', 'delivered', '2023-12-28 09:00:00', 8500000.00, 'bank_transfer', 'Kampala', 'Year-end clearance order', '2024-01-10 09:00:00', '2024-01-09 15:00:00', '2023-12-28 06:30:00', '2024-01-09 12:00:00'),
(90, 2, 1, 'ORD-2024-042', 'delivered', '2024-02-14 09:00:00', 6200000.00, 'mobile money', 'Jinja', 'Valentine special fabrics', '2024-02-25 09:00:00', '2024-02-24 12:00:00', '2024-02-14 06:30:00', '2024-02-24 09:00:00'),
(91, 3, 1, 'ORD-2024-043', 'delivered', '2024-04-15 14:00:00', 9800000.00, 'bank_transfer', 'Mbarara', 'Easter fashion collection', '2024-04-30 10:00:00', '2024-04-29 16:00:00', '2024-04-15 11:30:00', '2024-04-29 13:00:00'),
(92, 7, 1, 'ORD-2024-044', 'delivered', '2024-05-25 10:00:00', 7500000.00, 'mobile money', 'Mbale', 'Mid-term school supplies', '2024-06-10 09:00:00', '2024-06-09 11:00:00', '2024-05-25 07:30:00', '2024-06-09 08:00:00'),
(93, 8, 1, 'ORD-2024-045', 'delivered', '2024-07-15 08:00:00', 5800000.00, 'cash on delivery', 'Arua', 'Regional market supplies', '2024-07-30 09:00:00', '2024-07-31 10:00:00', '2024-07-15 05:30:00', '2024-07-31 07:00:00'),
(94, 9, 1, 'ORD-2024-046', 'delivered', '2024-08-25 09:30:00', 12500000.00, 'bank_transfer', 'Entebbe', 'Export quality control batch', '2024-09-10 09:00:00', '2024-09-08 14:00:00', '2024-08-25 07:00:00', '2024-09-08 11:00:00'),
(95, 11, 1, 'ORD-2024-047', 'delivered', '2024-09-30 11:00:00', 10800000.00, 'bank_transfer', 'Hoima', 'Q3 closing oil sector order', '2024-10-15 10:00:00', '2024-10-14 12:00:00', '2024-09-30 08:30:00', '2024-10-14 09:00:00'),
(96, 12, 1, 'ORD-2024-048', 'delivered', '2024-10-25 11:30:00', 8900000.00, 'mobile money', 'Wakiso', 'Suburban market expansion', '2024-11-10 10:00:00', '2024-11-09 13:00:00', '2024-10-25 09:00:00', '2024-11-09 10:00:00'),
(97, 4, 1, 'ORD-2024-049', 'delivered', '2024-11-30 08:45:00', 6600000.00, 'mobile money', 'Gulu', 'December traditional wear', '2024-12-15 09:00:00', '2024-12-14 11:00:00', '2024-11-30 06:00:00', '2024-12-14 08:00:00'),
(98, 5, 1, 'ORD-2025-032', 'delivered', '2025-01-25 13:00:00', 7800000.00, 'bank_transfer', 'Masaka', 'February wedding preparations', '2025-02-10 10:00:00', '2025-02-09 15:00:00', '2025-01-25 10:30:00', '2025-02-09 12:00:00'),
(99, 6, 1, 'ORD-2025-033', 'delivered', '2025-02-28 10:00:00', 6500000.00, 'mobile money', 'Fort Portal', 'March hospitality textiles', '2025-03-15 09:00:00', '2025-03-14 14:00:00', '2025-02-28 07:30:00', '2025-03-14 11:00:00'),
(100, 10, 1, 'ORD-2025-034', 'delivered', '2025-03-30 11:00:00', 4800000.00, 'mobile money', 'Lira', 'Q1 closing cotton products', '2025-04-15 10:00:00', '2025-04-16 09:00:00', '2025-03-30 08:30:00', '2025-04-16 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_id`, `quantity`, `unit_price`, `total_price`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 11, 200, 42000.00, 8400000.00, 'School uniform sets - bulk discount applied', '2023-06-15 06:30:00', '2023-06-15 06:30:00'),
(2, 1, 9, 150, 22000.00, 3300000.00, 'Cotton t-shirts for sports uniform', '2023-06-15 06:30:00', '2023-06-15 06:30:00'),
(3, 1, 16, 100, 8000.00, 800000.00, 'Children shorts - school sports', '2023-06-15 06:30:00', '2023-06-15 06:30:00'),
(4, 2, 13, 80, 68000.00, 5440000.00, 'Industrial overalls - oil sector', '2023-06-20 08:00:00', '2023-06-20 08:00:00'),
(5, 2, 18, 50, 42000.00, 2100000.00, 'Heavy canvas tarpaulins', '2023-06-20 08:00:00', '2023-06-20 08:00:00'),
(6, 2, 29, 100, 12100.00, 1210000.00, 'Denim fabric for workwear', '2023-06-20 08:00:00', '2023-06-20 08:00:00'),
(7, 3, 10, 60, 58000.00, 3480000.00, 'African print dresses - new collection', '2023-07-01 08:30:00', '2023-07-01 08:30:00'),
(8, 3, 17, 80, 22000.00, 1760000.00, 'Fashion scarves - assorted prints', '2023-07-01 08:30:00', '2023-07-01 08:30:00'),
(9, 3, 19, 40, 24000.00, 960000.00, 'Kitenge fabric - 6 yard pieces', '2023-07-01 08:30:00', '2023-07-01 08:30:00'),
(10, 4, 15, 15, 160000.00, 2400000.00, 'Traditional Gomesi - various sizes', '2023-07-10 06:00:00', '2023-07-10 06:00:00'),
(11, 4, 19, 50, 26000.00, 1300000.00, 'Kitenge fabric for traditional wear', '2023-07-10 06:00:00', '2023-07-10 06:00:00'),
(12, 4, 9, 40, 20000.00, 800000.00, 'Cotton t-shirts - basic', '2023-07-10 06:00:00', '2023-07-10 06:00:00'),
(13, 5, 15, 8, 165000.00, 1320000.00, 'Gomesi for wedding parties', '2023-07-15 11:30:00', '2023-07-15 11:30:00'),
(14, 5, 10, 25, 55000.00, 1375000.00, 'Special occasion dresses', '2023-07-15 11:30:00', '2023-07-15 11:30:00'),
(15, 5, 17, 50, 22100.00, 1105000.00, 'Wedding decoration scarves', '2023-07-15 11:30:00', '2023-07-15 11:30:00'),
(16, 6, 11, 300, 40000.00, 12000000.00, 'School uniforms - term 3 bulk order', '2023-08-05 07:00:00', '2023-08-05 07:00:00'),
(17, 6, 9, 100, 21000.00, 2100000.00, 'PE t-shirts', '2023-08-05 07:00:00', '2023-08-05 07:00:00'),
(18, 6, 16, 100, 9000.00, 900000.00, 'Sports shorts for children', '2023-08-05 07:00:00', '2023-08-05 07:00:00'),
(19, 7, 11, 180, 41000.00, 7380000.00, 'School uniforms - Eastern region', '2023-08-12 07:30:00', '2023-08-12 07:30:00'),
(20, 7, 9, 80, 21500.00, 1720000.00, 'School t-shirts', '2023-08-12 07:30:00', '2023-08-12 07:30:00'),
(21, 7, 26, 20, 20000.00, 400000.00, 'Bedsheets for boarding schools', '2023-08-12 07:30:00', '2023-08-12 07:30:00'),
(22, 9, 19, 100, 25000.00, 2500000.00, 'Kitenge for DRC market', '2023-09-01 05:30:00', '2023-09-01 05:30:00'),
(23, 9, 9, 100, 19000.00, 1900000.00, 'Basic t-shirts for export', '2023-09-01 05:30:00', '2023-09-01 05:30:00'),
(24, 9, 25, 60, 20000.00, 1200000.00, 'Mosquito nets - humanitarian', '2023-09-01 05:30:00', '2023-09-01 05:30:00'),
(25, 10, 10, 100, 60000.00, 6000000.00, 'African dresses - AGOA compliant', '2023-09-10 10:30:00', '2023-09-10 10:30:00'),
(26, 10, 28, 80, 35000.00, 2800000.00, 'Corporate polo shirts for export', '2023-09-10 10:30:00', '2023-09-10 10:30:00'),
(27, 10, 12, 20, 110000.00, 2200000.00, 'Luxury bedsheet sets', '2023-09-10 10:30:00', '2023-09-10 10:30:00'),
(28, 11, 9, 80, 21000.00, 1680000.00, 'Cotton t-shirts - local market', '2023-09-15 08:00:00', '2023-09-15 08:00:00'),
(29, 11, 19, 30, 24000.00, 720000.00, 'Local print fabrics', '2023-09-15 08:00:00', '2023-09-15 08:00:00'),
(30, 11, 14, 20, 30000.00, 600000.00, 'Cotton kitchen towel sets', '2023-09-15 08:00:00', '2023-09-15 08:00:00'),
(31, 12, 13, 60, 70000.00, 4200000.00, 'Industrial overalls - fire resistant', '2023-10-05 06:30:00', '2023-10-05 06:30:00'),
(32, 12, 28, 50, 36000.00, 1800000.00, 'Corporate uniforms - oil companies', '2023-10-05 06:30:00', '2023-10-05 06:30:00'),
(33, 12, 20, 20, 40000.00, 800000.00, 'Medical scrubs for clinics', '2023-10-05 06:30:00', '2023-10-05 06:30:00'),
(34, 13, 10, 50, 58000.00, 2900000.00, 'Fashion dresses - urban market', '2023-10-12 08:30:00', '2023-10-12 08:30:00'),
(35, 13, 24, 60, 32000.00, 1920000.00, 'Baby clothes sets', '2023-10-12 08:30:00', '2023-10-12 08:30:00'),
(36, 13, 12, 25, 95200.00, 2380000.00, 'Home textile sets', '2023-10-12 08:30:00', '2023-10-12 08:30:00'),
(37, 16, 11, 250, 41000.00, 10250000.00, 'School uniforms - 2024 preparation', '2023-11-10 06:00:00', '2023-11-10 06:00:00'),
(38, 16, 9, 200, 20500.00, 4100000.00, 'Cotton t-shirts - bulk', '2023-11-10 06:00:00', '2023-11-10 06:00:00'),
(39, 16, 10, 50, 57000.00, 2850000.00, 'African print dresses', '2023-11-10 06:00:00', '2023-11-10 06:00:00'),
(40, 16, 26, 40, 20000.00, 800000.00, 'Cotton bedsheets', '2023-11-10 06:00:00', '2023-11-10 06:00:00'),
(41, 24, 11, 280, 40500.00, 11340000.00, 'Term 1 school uniforms', '2024-02-01 08:30:00', '2024-02-01 08:30:00'),
(42, 24, 9, 150, 21000.00, 3150000.00, 'School sports shirts', '2024-02-01 08:30:00', '2024-02-01 08:30:00'),
(43, 24, 16, 120, 12583.33, 1510000.00, 'Children shorts - volume discount', '2024-02-01 08:30:00', '2024-02-01 08:30:00'),
(44, 32, 11, 200, 42000.00, 8400000.00, 'School uniforms', '2024-04-20 08:00:00', '2024-04-20 08:00:00'),
(45, 32, 10, 80, 59000.00, 4720000.00, 'Fashion garments', '2024-04-20 08:00:00', '2024-04-20 08:00:00'),
(46, 32, 12, 35, 112000.00, 3920000.00, 'Luxury bedding', '2024-04-20 08:00:00', '2024-04-20 08:00:00'),
(47, 32, 13, 40, 61500.00, 2460000.00, 'Work uniforms', '2024-04-20 08:00:00', '2024-04-20 08:00:00'),
(48, 40, 11, 350, 40000.00, 14000000.00, 'Bulk school uniforms - term 3', '2024-07-10 06:30:00', '2024-07-10 06:30:00'),
(49, 40, 9, 200, 20500.00, 4100000.00, 'School t-shirts', '2024-07-10 06:30:00', '2024-07-10 06:30:00'),
(50, 40, 16, 150, 12000.00, 1800000.00, 'Sports shorts', '2024-07-10 06:30:00', '2024-07-10 06:30:00'),
(51, 40, 26, 60, 18333.33, 1100000.00, 'Boarding school bedsheets', '2024-07-10 06:30:00', '2024-07-10 06:30:00'),
(52, 49, 11, 300, 41500.00, 12450000.00, 'School uniforms - 2025 prep', '2024-10-20 07:30:00', '2024-10-20 07:30:00'),
(53, 49, 10, 100, 58500.00, 5850000.00, 'Fashion collection', '2024-10-20 07:30:00', '2024-10-20 07:30:00'),
(54, 49, 12, 30, 115000.00, 3450000.00, 'Premium bedding sets', '2024-10-20 07:30:00', '2024-10-20 07:30:00'),
(55, 49, 28, 40, 31250.00, 1250000.00, 'Corporate wear', '2024-10-20 07:30:00', '2024-10-20 07:30:00'),
(56, 56, 11, 400, 40000.00, 16000000.00, 'New year school uniforms mega order', '2025-01-05 06:30:00', '2025-01-05 06:30:00'),
(57, 56, 9, 250, 20000.00, 5000000.00, 'Cotton t-shirts - bulk', '2025-01-05 06:30:00', '2025-01-05 06:30:00'),
(58, 56, 10, 50, 60000.00, 3000000.00, 'New fashion line', '2025-01-05 06:30:00', '2025-01-05 06:30:00'),
(59, 56, 13, 20, 50000.00, 1000000.00, 'Industrial workwear', '2025-01-05 06:30:00', '2025-01-05 06:30:00'),
(60, 59, 10, 150, 62000.00, 9300000.00, 'Export quality dresses', '2025-01-20 07:00:00', '2025-01-20 07:00:00'),
(61, 59, 28, 120, 38000.00, 4560000.00, 'Corporate polo - EU standards', '2025-01-20 07:00:00', '2025-01-20 07:00:00'),
(62, 59, 12, 35, 118285.71, 4140000.00, 'Luxury hotel linens', '2025-01-20 07:00:00', '2025-01-20 07:00:00'),
(63, 59, 17, 50, 20000.00, 1000000.00, 'Fashion accessories', '2025-01-20 07:00:00', '2025-01-20 07:00:00'),
(64, 66, 11, 450, 40000.00, 18000000.00, 'Q2 school uniforms', '2025-04-01 06:30:00', '2025-04-01 06:30:00'),
(65, 66, 10, 100, 60000.00, 6000000.00, 'Fashion garments', '2025-04-01 06:30:00', '2025-04-01 06:30:00'),
(66, 66, 9, 150, 20000.00, 3000000.00, 'Basic cotton wear', '2025-04-01 06:30:00', '2025-04-01 06:30:00'),
(67, 66, 26, 60, 16666.67, 1000000.00, 'Bedsheets', '2025-04-01 06:30:00', '2025-04-01 06:30:00'),
(68, 70, 10, 180, 63000.00, 11340000.00, 'AGOA compliant dresses', '2025-05-10 07:00:00', '2025-05-10 07:00:00'),
(69, 70, 28, 150, 39000.00, 5850000.00, 'Export polo shirts', '2025-05-10 07:00:00', '2025-05-10 07:00:00'),
(70, 70, 9, 200, 19050.00, 3810000.00, 'Basic t-shirts for US market', '2025-05-10 07:00:00', '2025-05-10 07:00:00'),
(71, 70, 17, 60, 16666.67, 1000000.00, 'Fashion scarves', '2025-05-10 07:00:00', '2025-05-10 07:00:00'),
(72, 76, 11, 500, 40000.00, 20000000.00, 'July school preparation - largest order', '2025-07-01 06:30:00', '2025-07-15 05:00:00'),
(73, 76, 9, 300, 20000.00, 6000000.00, 'School sports uniforms', '2025-07-01 06:30:00', '2025-07-15 05:00:00'),
(74, 76, 10, 80, 62500.00, 5000000.00, 'New fashion line', '2025-07-01 06:30:00', '2025-07-15 05:00:00'),
(75, 76, 16, 125, 8000.00, 1000000.00, 'Children sportswear', '2025-07-01 06:30:00', '2025-07-15 05:00:00'),
(76, 71, 11, 320, 41000.00, 13120000.00, 'Mbale schools order', '2025-05-20 07:30:00', '2025-06-04 08:00:00'),
(77, 71, 9, 180, 21000.00, 3780000.00, 'School t-shirts', '2025-05-20 07:30:00', '2025-06-04 08:00:00'),
(78, 71, 20, 60, 45000.00, 2700000.00, 'Medical uniforms for clinics', '2025-05-20 07:30:00', '2025-06-04 08:00:00'),
(79, 72, 13, 120, 72000.00, 8640000.00, 'Industrial workwear - Jinja factories', '2025-06-01 06:30:00', '2025-06-14 07:00:00'),
(80, 72, 18, 80, 43000.00, 3440000.00, 'Heavy duty tarpaulins', '2025-06-01 06:30:00', '2025-06-14 07:00:00'),
(81, 72, 28, 150, 37000.00, 5550000.00, 'Corporate uniforms', '2025-06-01 06:30:00', '2025-06-14 07:00:00'),
(82, 72, 29, 200, 17000.00, 3400000.00, 'Denim fabric rolls', '2025-06-01 06:30:00', '2025-06-14 07:00:00'),
(83, 44, 13, 100, 70000.00, 7000000.00, 'Q3 industrial uniforms', '2024-09-01 06:30:00', '2024-09-14 07:00:00'),
(84, 44, 20, 80, 48750.00, 3900000.00, 'Medical sector uniforms', '2024-09-01 06:30:00', '2024-09-14 07:00:00'),
(85, 44, 28, 100, 31000.00, 3100000.00, 'Corporate polo shirts', '2024-09-01 06:30:00', '2024-09-14 07:00:00'),
(86, 38, 10, 150, 62000.00, 9300000.00, 'AGOA export dresses', '2024-06-20 06:00:00', '2024-07-03 11:00:00'),
(87, 38, 9, 200, 19500.00, 3900000.00, 'Export t-shirts', '2024-06-20 06:00:00', '2024-07-03 11:00:00'),
(88, 38, 17, 100, 18000.00, 1800000.00, 'Fashion accessories for export', '2024-06-20 06:00:00', '2024-07-03 11:00:00'),
(89, 26, 10, 120, 61000.00, 7320000.00, 'EU market dresses', '2024-02-20 07:00:00', '2024-03-04 08:00:00'),
(90, 26, 28, 100, 36800.00, 3680000.00, 'EU standard polo shirts', '2024-02-20 07:00:00', '2024-03-04 08:00:00'),
(91, 26, 12, 20, 100000.00, 2000000.00, 'Luxury bedding for hotels', '2024-02-20 07:00:00', '2024-03-04 08:00:00'),
(92, 17, 15, 12, 170000.00, 2040000.00, 'Festive Gomesi', '2023-11-15 07:00:00', '2023-11-30 11:00:00'),
(93, 17, 19, 40, 26000.00, 1040000.00, 'Traditional print fabric', '2023-11-15 07:00:00', '2023-11-30 11:00:00'),
(94, 17, 17, 60, 19333.33, 1160000.00, 'Festive scarves', '2023-11-15 07:00:00', '2023-11-30 11:00:00'),
(95, 25, 10, 80, 59000.00, 4720000.00, 'Valentine special dresses', '2024-02-10 11:30:00', '2024-02-24 12:00:00'),
(96, 25, 17, 100, 18000.00, 1800000.00, 'Valentine scarves', '2024-02-10 11:30:00', '2024-02-24 12:00:00'),
(97, 25, 28, 30, 32666.67, 980000.00, 'Corporate gifts', '2024-02-10 11:30:00', '2024-02-24 12:00:00'),
(98, 34, 9, 120, 20000.00, 2400000.00, 'Cotton basics', '2024-05-10 08:30:00', '2024-05-26 06:00:00'),
(99, 34, 19, 40, 25000.00, 1000000.00, 'Local fabrics', '2024-05-10 08:30:00', '2024-05-26 06:00:00'),
(100, 34, 14, 30, 20000.00, 600000.00, 'Kitchen textiles', '2024-05-10 08:30:00', '2024-05-26 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('info@ugandatextiles.co.ug', '$2y$10$Q3zAuz6YEzez11ZSL7prDuXKmGM59Md4kyHf6lbYxuRBStnapnG1W', '2025-07-19 10:27:31');

-- --------------------------------------------------------

--
-- Table structure for table `pending_users`
--

CREATE TABLE `pending_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `visitDate` datetime DEFAULT NULL,
  `business_address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `license_document` varchar(255) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `business_type` varchar(255) DEFAULT NULL,
  `monthly_order_volume` int(11) DEFAULT NULL,
  `production_capacity` int(11) DEFAULT NULL,
  `preferred_categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferred_categories`)),
  `specialization` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specialization`)),
  `materials_supplied` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`materials_supplied`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_negotiations`
--

CREATE TABLE `price_negotiations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supply_request_id` bigint(20) UNSIGNED NOT NULL,
  `proposed_price` decimal(10,2) NOT NULL,
  `counter_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','accepted','rejected','counter_offered') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_costs`
--

CREATE TABLE `production_costs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_id` bigint(20) UNSIGNED NOT NULL,
  `material_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `labor_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `overhead_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_schedules`
--

CREATE TABLE `production_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('Planned','InProgress','Completed') NOT NULL DEFAULT 'Planned',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quality_checks`
--

CREATE TABLE `quality_checks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_id` bigint(20) UNSIGNED NOT NULL,
  `stage` varchar(255) NOT NULL,
  `result` enum('Pass','Fail','Rework') NOT NULL DEFAULT 'Pass',
  `checked_by` bigint(20) UNSIGNED NOT NULL,
  `checked_at` datetime NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplied_items`
--

CREATE TABLE `supplied_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `delivered_quantity` int(11) NOT NULL,
  `delivery_date` datetime NOT NULL,
  `quality_rating` int(11) DEFAULT NULL,
  `status` enum('delivered','returned','pending') NOT NULL DEFAULT 'delivered',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `business_address` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `license_document` varchar(255) NOT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `materials_supplied` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`materials_supplied`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `user_id`, `business_address`, `phone`, `license_document`, `document_path`, `materials_supplied`, `created_at`, `updated_at`) VALUES
(1, 14, 'Plot 23, Cotton House, Kasese Road, P.O. Box 234, Kasese District', '+256774123456', 'URSB/SUP/2023/000234', '/documents/licenses/supplier_14_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Raw Cotton Grade A\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 4500,\r\n    \"monthly_capacity\": 50000,\r\n    \"quality_grade\": \"A\",\r\n    \"moisture_content\": \"7-8%\",\r\n    \"staple_length\": \"28-30mm\",\r\n    \"certifications\": [\"Uganda Cotton Development Organisation\", \"Organic Certified\"]\r\n  },\r\n  {\r\n    \"name\": \"Raw Cotton Grade B\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 3800,\r\n    \"monthly_capacity\": 30000,\r\n    \"quality_grade\": \"B\",\r\n    \"moisture_content\": \"8-9%\",\r\n    \"staple_length\": \"26-28mm\"\r\n  }\r\n]}', '2023-02-20 07:00:00', '2025-07-17 00:15:00'),
(2, 15, 'Gulu Cotton Cooperative Building, Plot 45, Industrial Area, P.O. Box 567, Gulu', '+256771234567', 'URSB/SUP/2023/000235', '/documents/licenses/supplier_15_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Premium Organic Cotton\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 5200,\r\n    \"monthly_capacity\": 40000,\r\n    \"quality_grade\": \"Premium\",\r\n    \"moisture_content\": \"6-7%\",\r\n    \"staple_length\": \"30-32mm\",\r\n    \"certifications\": [\"GOTS Organic\", \"Fair Trade\"],\r\n    \"harvest_seasons\": [\"March-May\", \"September-November\"]\r\n  },\r\n  {\r\n    \"name\": \"Cotton Seeds\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 800,\r\n    \"monthly_capacity\": 10000,\r\n    \"usage\": \"Oil extraction, Animal feed\"\r\n  }\r\n]}', '2023-02-25 08:00:00', '2025-07-16 16:45:00'),
(3, 16, 'Soroti Fiber Center, Plot 12, Market Street, P.O. Box 890, Soroti District', '+256703456789', 'URSB/SUP/2023/000236', NULL, '{\"materials\": [\r\n  {\r\n    \"name\": \"Cotton Fiber Waste\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 1200,\r\n    \"monthly_capacity\": 20000,\r\n    \"usage\": \"Padding, Insulation, Non-woven fabrics\"\r\n  },\r\n  {\r\n    \"name\": \"Combed Cotton Sliver\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 6500,\r\n    \"monthly_capacity\": 15000,\r\n    \"quality\": \"Combed, Ready for spinning\"\r\n  }\r\n]}', '2023-03-05 06:00:00', '2025-07-15 11:30:00'),
(4, 17, 'Plot 78, Masindi Port Road, Cotton Ginnery, P.O. Box 345, Masindi', '+256782345678', 'URSB/SUP/2023/000237', '/documents/licenses/supplier_17_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Ginned Cotton Lint\",\r\n    \"unit\": \"bale\",\r\n    \"price_per_unit\": 850000,\r\n    \"monthly_capacity\": 200,\r\n    \"weight_per_bale\": \"185kg\",\r\n    \"quality_parameters\": {\r\n      \"trash_content\": \"<2%\",\r\n      \"color_grade\": \"White/Light Spotted\",\r\n      \"strength\": \"28-30 g/tex\"\r\n    }\r\n  }\r\n]}', '2023-03-10 09:00:00', '2025-07-16 22:00:00'),
(5, 18, 'Lira Agricultural Complex, Plot 34, Warehouse Zone, P.O. Box 678, Lira', '+256759876543', 'URSB/SUP/2023/000238', '/documents/licenses/supplier_18_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Cotton Bales - Medium Staple\",\r\n    \"unit\": \"bale\",\r\n    \"price_per_unit\": 780000,\r\n    \"monthly_capacity\": 250,\r\n    \"specifications\": {\r\n      \"staple_length\": \"25-27mm\",\r\n      \"micronaire\": \"3.8-4.2\",\r\n      \"uniformity\": \">80%\"\r\n    }\r\n  },\r\n  {\r\n    \"name\": \"Cotton Linters\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 2200,\r\n    \"monthly_capacity\": 8000,\r\n    \"usage\": \"Medical cotton, Filter material\"\r\n  }\r\n]}', '2023-03-15 07:00:00', '2025-07-16 19:30:00'),
(6, 19, 'Kampala Industrial Area, 6th Street, Plot 123-125, P.O. Box 7890, Kampala', '+256701234890', 'URSB/SUP/2023/000239', '/documents/licenses/supplier_19_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Reactive Dyes\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 25000,\r\n    \"monthly_capacity\": 5000,\r\n    \"color_range\": \"120+ shades\",\r\n    \"fastness_rating\": \"4-5\",\r\n    \"brands\": [\"Dystar\", \"Huntsman\", \"Local production\"]\r\n  },\r\n  {\r\n    \"name\": \"Textile Auxiliaries\",\r\n    \"unit\": \"liter\",\r\n    \"price_per_unit\": 8500,\r\n    \"monthly_capacity\": 10000,\r\n    \"types\": [\"Wetting agents\", \"Leveling agents\", \"Fixing agents\", \"Softeners\"]\r\n  },\r\n  {\r\n    \"name\": \"Printing Paste\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 12000,\r\n    \"monthly_capacity\": 3000,\r\n    \"types\": [\"Pigment paste\", \"Reactive paste\", \"Discharge paste\"]\r\n  },\r\n  {\r\n    \"name\": \"Bleaching Powder\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 3500,\r\n    \"monthly_capacity\": 8000,\r\n    \"concentration\": \"35% active chlorine\"\r\n  }\r\n]}', '2023-03-20 08:00:00', '2025-07-17 01:00:00'),
(7, 20, 'Jinja Industrial Park, Block B, Plot 45-47, P.O. Box 456, Jinja', '+256775678901', 'URSB/SUP/2023/000240', NULL, '{\"materials\": [\r\n  {\r\n    \"name\": \"Cotton Sewing Thread\",\r\n    \"unit\": \"cone\",\r\n    \"price_per_unit\": 15000,\r\n    \"monthly_capacity\": 10000,\r\n    \"specifications\": {\r\n      \"counts\": [\"20/2\", \"30/2\", \"40/2\", \"50/2\"],\r\n      \"length_per_cone\": \"5000m\",\r\n      \"colors\": \"200+ shades\"\r\n    }\r\n  },\r\n  {\r\n    \"name\": \"Polyester Thread\",\r\n    \"unit\": \"cone\",\r\n    \"price_per_unit\": 12000,\r\n    \"monthly_capacity\": 15000,\r\n    \"strength\": \"High tenacity\",\r\n    \"usage\": \"Heavy duty sewing\"\r\n  },\r\n  {\r\n    \"name\": \"Embroidery Thread\",\r\n    \"unit\": \"spool\",\r\n    \"price_per_unit\": 8000,\r\n    \"monthly_capacity\": 5000,\r\n    \"type\": \"Rayon/Polyester blend\"\r\n  }\r\n]}', '2023-03-25 06:00:00', '2025-07-16 13:45:00'),
(8, 21, 'Tororo Border Town, Silk Processing Zone, P.O. Box 123, Tororo', '+256704567123', 'URSB/SUP/2023/000241', '/documents/licenses/supplier_21_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Raw Silk Yarn\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 85000,\r\n    \"monthly_capacity\": 500,\r\n    \"grade\": \"2A/3A\",\r\n    \"origin\": \"Local sericulture + Import\"\r\n  },\r\n  {\r\n    \"name\": \"Silk Fabric\",\r\n    \"unit\": \"meter\",\r\n    \"price_per_unit\": 35000,\r\n    \"monthly_capacity\": 2000,\r\n    \"types\": [\"Habotai\", \"Crepe de Chine\", \"Dupioni\"]\r\n  }\r\n]}', '2023-04-01 09:00:00', '2025-07-14 10:20:00'),
(9, 22, 'Mukono Industrial Area, Plot 67, Accessories Lane, P.O. Box 234, Mukono', '+256788901234', 'URSB/SUP/2023/000242', '/documents/licenses/supplier_22_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Metal Zippers\",\r\n    \"unit\": \"piece\",\r\n    \"price_per_unit\": 1500,\r\n    \"monthly_capacity\": 50000,\r\n    \"sizes\": [\"10cm\", \"15cm\", \"20cm\", \"25cm\", \"30cm\"],\r\n    \"colors\": [\"Gold\", \"Silver\", \"Antique\", \"Black\"]\r\n  },\r\n  {\r\n    \"name\": \"Plastic Zippers\",\r\n    \"unit\": \"piece\",\r\n    \"price_per_unit\": 800,\r\n    \"monthly_capacity\": 80000,\r\n    \"types\": [\"Coil\", \"Molded\", \"Invisible\"]\r\n  },\r\n  {\r\n    \"name\": \"Velcro Tape\",\r\n    \"unit\": \"meter\",\r\n    \"price_per_unit\": 2500,\r\n    \"monthly_capacity\": 10000,\r\n    \"widths\": [\"20mm\", \"50mm\", \"100mm\"]\r\n  }\r\n]}', '2023-04-05 07:30:00', '2025-07-16 23:30:00'),
(10, 23, 'Iganga Town, Factory Road, Plot 23, P.O. Box 567, Iganga', '+256702345890', 'URSB/SUP/2023/000243', NULL, '{\"materials\": [\r\n  {\r\n    \"name\": \"Plastic Buttons\",\r\n    \"unit\": \"gross\",\r\n    \"price_per_unit\": 15000,\r\n    \"monthly_capacity\": 5000,\r\n    \"sizes\": [\"18L\", \"24L\", \"32L\", \"36L\"],\r\n    \"colors\": \"150+ colors\",\r\n    \"finish\": [\"Matte\", \"Glossy\", \"Pearl\"]\r\n  },\r\n  {\r\n    \"name\": \"Metal Buttons\",\r\n    \"unit\": \"gross\",\r\n    \"price_per_unit\": 25000,\r\n    \"monthly_capacity\": 2000,\r\n    \"types\": [\"Snap\", \"Jeans\", \"Decorative\"]\r\n  },\r\n  {\r\n    \"name\": \"Wooden Buttons\",\r\n    \"unit\": \"gross\",\r\n    \"price_per_unit\": 18000,\r\n    \"monthly_capacity\": 1000,\r\n    \"eco_friendly\": true\r\n  }\r\n]}', '2023-04-10 08:00:00', '2025-07-15 06:00:00'),
(11, 24, 'Bushenyi Organic Farms, Ishaka Road, P.O. Box 890, Bushenyi', '+256756789012', 'URSB/SUP/2023/000244', NULL, '{\"materials\": [\r\n  {\r\n    \"name\": \"Certified Organic Cotton\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 6800,\r\n    \"monthly_capacity\": 15000,\r\n    \"certifications\": [\"EU Organic\", \"USDA Organic\", \"GOTS\"],\r\n    \"traceability\": \"Full farm to fiber tracking\"\r\n  },\r\n  {\r\n    \"name\": \"Naturally Colored Cotton\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 8500,\r\n    \"monthly_capacity\": 3000,\r\n    \"colors\": [\"Natural Brown\", \"Natural Green\"],\r\n    \"chemical_free\": true\r\n  }\r\n]}', '2023-04-10 09:00:00', '2025-07-16 08:15:00'),
(12, 25, 'Kabale Highland Farms, Wool Market, P.O. Box 345, Kabale', '+256771890123', 'URSB/SUP/2023/000245', '/documents/licenses/supplier_25_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Merino Wool\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 32000,\r\n    \"monthly_capacity\": 2000,\r\n    \"micron\": \"19-23\",\r\n    \"source\": \"Local sheep farms\"\r\n  },\r\n  {\r\n    \"name\": \"Wool Tops\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 45000,\r\n    \"monthly_capacity\": 1000,\r\n    \"processing\": \"Combed and ready for spinning\"\r\n  }\r\n]}', '2023-04-20 06:00:00', '2025-07-13 15:30:00'),
(13, 26, 'Apac District Farmers Building, Cotton Market Road, P.O. Box 678, Apac', '+256703456123', 'URSB/SUP/2023/000246', NULL, '{\"materials\": [\r\n  {\r\n    \"name\": \"Hand-picked Cotton\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 4800,\r\n    \"monthly_capacity\": 35000,\r\n    \"quality\": \"Premium hand-picked\",\r\n    \"contamination\": \"Minimal\"\r\n  }\r\n]}', '2023-04-25 08:00:00', '2025-07-16 21:45:00'),
(14, 27, 'Kitgum Processing Center, Industrial Zone, P.O. Box 901, Kitgum', '+256785678901', 'URSB/SUP/2023/000247', '/documents/licenses/supplier_27_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Cotton Fiber - Long Staple\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 5500,\r\n    \"monthly_capacity\": 25000,\r\n    \"staple_length\": \"32-35mm\",\r\n    \"suitable_for\": \"Fine count yarns\"\r\n  }\r\n]}', '2023-05-01 07:00:00', '2025-07-16 02:20:00'),
(15, 28, 'Pallisa Cotton Union Building, Main Street, P.O. Box 234, Pallisa', '+256702890123', 'URSB/SUP/2023/000248', '/documents/licenses/supplier_28_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Cotton Bales - Standard\",\r\n    \"unit\": \"bale\",\r\n    \"price_per_unit\": 820000,\r\n    \"monthly_capacity\": 180,\r\n    \"compression\": \"Universal density\"\r\n  }\r\n]}', '2023-05-05 09:00:00', '2025-07-15 18:00:00'),
(16, 29, 'Rakai Trading Center, Textile Supplies, P.O. Box 567, Rakai', '+256759012345', 'URSB/SUP/2023/000249', NULL, '{\"materials\": [\r\n  {\r\n    \"name\": \"Interlining Material\",\r\n    \"unit\": \"meter\",\r\n    \"price_per_unit\": 3500,\r\n    \"monthly_capacity\": 20000,\r\n    \"types\": [\"Fusible\", \"Non-fusible\", \"Knitted\", \"Woven\"]\r\n  },\r\n  {\r\n    \"name\": \"Elastic Bands\",\r\n    \"unit\": \"meter\",\r\n    \"price_per_unit\": 1200,\r\n    \"monthly_capacity\": 30000,\r\n    \"widths\": [\"6mm\", \"10mm\", \"20mm\", \"30mm\", \"50mm\"]\r\n  }\r\n]}', '2023-05-10 06:30:00', '2025-07-14 04:45:00'),
(17, 30, 'Moroto Leather Works, Karamoja Road, P.O. Box 123, Moroto', '+256774567890', 'URSB/SUP/2023/000250', '/documents/licenses/supplier_30_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Genuine Leather\",\r\n    \"unit\": \"sq.foot\",\r\n    \"price_per_unit\": 12000,\r\n    \"monthly_capacity\": 5000,\r\n    \"types\": [\"Full grain\", \"Top grain\", \"Split\"],\r\n    \"thickness\": [\"1.0mm\", \"1.2mm\", \"1.5mm\", \"2.0mm\"]\r\n  },\r\n  {\r\n    \"name\": \"Synthetic Leather\",\r\n    \"unit\": \"meter\",\r\n    \"price_per_unit\": 18000,\r\n    \"monthly_capacity\": 8000,\r\n    \"backing\": \"Textile backed\"\r\n  }\r\n]}', '2023-05-15 08:00:00', '2025-07-16 14:30:00'),
(18, 31, 'Nebbi Cotton Farms Cooperative, Ginnery Road, P.O. Box 456, Nebbi', '+256701234567', 'URSB/SUP/2023/000251', '/documents/licenses/supplier_31_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"West Nile Cotton\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 4600,\r\n    \"monthly_capacity\": 30000,\r\n    \"region_specific\": \"West Nile variety\",\r\n    \"harvest_months\": [\"December\", \"January\", \"April\", \"May\"]\r\n  }\r\n]}', '2023-05-20 07:30:00', '2025-07-12 12:15:00'),
(19, 32, 'Kamuli Industrial Park, Finishing Section, P.O. Box 789, Kamuli', '+256788234567', 'URSB/SUP/2023/000252', NULL, '{\"materials\": [\r\n  {\r\n    \"name\": \"Fabric Stiffeners\",\r\n    \"unit\": \"liter\",\r\n    \"price_per_unit\": 6500,\r\n    \"monthly_capacity\": 5000,\r\n    \"types\": [\"Starch based\", \"Synthetic\", \"Semi-permanent\"]\r\n  },\r\n  {\r\n    \"name\": \"Anti-static Agents\",\r\n    \"unit\": \"liter\",\r\n    \"price_per_unit\": 9500,\r\n    \"monthly_capacity\": 2000\r\n  }\r\n]}', '2023-05-20 09:00:00', '2025-07-17 00:45:00'),
(20, 33, 'Kyenjojo Natural Fibers Ltd, Fort Portal Road, P.O. Box 345, Kyenjojo', '+256775890123', 'URSB/SUP/2023/000253', '/documents/licenses/supplier_33_license.pdf', '{\"materials\": [\r\n  {\r\n    \"name\": \"Banana Fiber\",\r\n    \"unit\": \"kg\",\r\n    \"price_per_unit\": 15000,\r\n    \"monthly_capacity\": 1000,\r\n    \"processing\": \"Degummed and processed\",\r\n    \"eco_rating\": \"100% biodegradable\"\r\n  },\r\n  {\r\n    \"name\": \"Bark Cloth\",\r\n    \"unit\": \"meter\",\r\n    \"price_per_unit\": 25000,\r\n    \"monthly_capacity\": 500,\r\n    \"cultural_significance\": \"Traditional Ugandan material\",\r\n    \"UNESCO_heritage\": true\r\n  }\r\n]}', '2023-05-25 06:00:00', '2025-07-10 09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `supply_requests`
--

CREATE TABLE `supply_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `due_date` datetime NOT NULL,
  `status` enum('pending','approved','rejected','in_progress','completed') NOT NULL DEFAULT 'pending',
  `payment_type` enum('cash','credit','bank_transfer') NOT NULL DEFAULT 'cash',
  `delivery_method` enum('pickup','delivery') NOT NULL DEFAULT 'delivery',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supply_requests`
--

INSERT INTO `supply_requests` (`id`, `supplier_id`, `item_id`, `quantity`, `due_date`, `status`, `payment_type`, `delivery_method`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 50, '2023-06-20 10:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton bales for Q3 production', '2023-06-05 06:00:00', '2023-06-20 11:00:00'),
(2, 2, 2, 10000, '2023-06-25 09:00:00', 'completed', 'credit', 'delivery', 'Organic cotton for premium line', '2023-06-10 07:30:00', '2023-06-25 08:00:00'),
(3, 6, 3, 100, '2023-07-01 08:00:00', 'completed', 'bank_transfer', 'pickup', 'Blue dye series for school uniforms', '2023-06-15 08:00:00', '2023-07-01 06:00:00'),
(4, 7, 4, 3000, '2023-07-05 14:00:00', 'completed', 'credit', 'delivery', 'Thread stock for July production', '2023-06-20 10:00:00', '2023-07-05 12:30:00'),
(5, 9, 5, 5000, '2023-07-10 10:00:00', 'completed', 'cash', 'pickup', 'Metal zippers - back to school', '2023-06-25 05:30:00', '2023-07-10 08:00:00'),
(6, 3, 6, 5000, '2023-07-15 11:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton yarn for weaving', '2023-07-01 06:00:00', '2023-07-15 09:00:00'),
(7, 1, 1, 80, '2023-08-01 09:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton bales - school uniform peak', '2023-07-15 07:00:00', '2023-08-01 07:30:00'),
(8, 4, 2, 15000, '2023-08-05 08:00:00', 'completed', 'credit', 'delivery', 'Premium cotton for export orders', '2023-07-20 08:00:00', '2023-08-05 06:00:00'),
(9, 10, 22, 5000, '2023-08-10 14:00:00', 'completed', 'cash', 'pickup', 'Plastic buttons for uniforms', '2023-07-25 09:00:00', '2023-08-10 12:00:00'),
(10, 7, 7, 3000, '2023-08-15 10:00:00', 'completed', 'credit', 'delivery', 'Interlining for collar reinforcement', '2023-08-01 05:00:00', '2023-08-15 08:00:00'),
(11, 5, 1, 100, '2023-09-01 09:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton lint bales - regular supply', '2023-08-15 07:30:00', '2023-09-01 07:00:00'),
(12, 8, 8, 200, '2023-09-10 11:00:00', 'completed', 'credit', 'delivery', 'Silk yarn for luxury line', '2023-08-25 10:00:00', '2023-09-10 09:30:00'),
(13, 6, 3, 150, '2023-09-15 08:00:00', 'completed', 'bank_transfer', 'pickup', 'Reactive dyes - fall colors', '2023-09-01 06:00:00', '2023-09-15 06:00:00'),
(14, 11, 2, 8000, '2023-09-20 14:00:00', 'completed', 'credit', 'delivery', 'Organic cotton for export', '2023-09-05 08:00:00', '2023-09-20 12:00:00'),
(15, 9, 5, 8000, '2023-10-01 10:00:00', 'completed', 'cash', 'pickup', 'Zippers - Q4 stock buildup', '2023-09-15 05:30:00', '2023-10-01 08:00:00'),
(16, 1, 1, 120, '2023-10-10 09:00:00', 'completed', 'bank_transfer', 'delivery', 'Q4 cotton supply - peak season', '2023-09-25 07:00:00', '2023-10-10 07:30:00'),
(17, 10, 23, 10000, '2023-10-15 11:00:00', 'completed', 'cash', 'pickup', 'Elastic bands for garments', '2023-10-01 09:00:00', '2023-10-15 09:00:00'),
(18, 7, 4, 5000, '2023-10-20 14:00:00', 'completed', 'credit', 'delivery', 'Thread - holiday production prep', '2023-10-05 06:00:00', '2023-10-20 12:30:00'),
(19, 18, 27, 50, '2023-11-01 10:00:00', 'completed', 'cash', 'pickup', 'Bark cloth for cultural items', '2023-10-15 08:00:00', '2023-11-01 08:00:00'),
(20, 12, 8, 300, '2023-11-10 09:00:00', 'completed', 'credit', 'delivery', 'Premium silk for festive wear', '2023-10-25 10:00:00', '2023-11-10 07:00:00'),
(21, 2, 2, 12000, '2024-01-15 10:00:00', 'completed', 'credit', 'delivery', 'Q1 organic cotton supply', '2024-01-02 06:00:00', '2024-01-15 08:00:00'),
(22, 1, 1, 150, '2024-01-20 09:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton bales - new year production', '2024-01-05 07:00:00', '2024-01-20 07:30:00'),
(23, 6, 3, 200, '2024-02-01 08:00:00', 'completed', 'bank_transfer', 'pickup', 'Valentine colors dye series', '2024-01-15 08:00:00', '2024-02-01 06:00:00'),
(24, 7, 4, 6000, '2024-02-10 14:00:00', 'completed', 'credit', 'delivery', 'Thread stock - school term prep', '2024-01-25 05:00:00', '2024-02-10 12:00:00'),
(25, 9, 5, 10000, '2024-02-15 10:00:00', 'completed', 'cash', 'pickup', 'Zippers - term 1 uniforms', '2024-02-01 06:00:00', '2024-02-15 08:00:00'),
(26, 4, 1, 100, '2024-03-01 09:00:00', 'completed', 'bank_transfer', 'delivery', 'Ginned cotton lint - Q1', '2024-02-15 07:00:00', '2024-03-01 07:00:00'),
(27, 10, 22, 8000, '2024-03-10 11:00:00', 'completed', 'cash', 'pickup', 'Button variety pack', '2024-02-25 09:00:00', '2024-03-10 09:00:00'),
(28, 3, 6, 8000, '2024-03-15 14:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton yarn - Easter production', '2024-03-01 06:00:00', '2024-03-15 12:00:00'),
(29, 13, 2, 10000, '2024-04-01 10:00:00', 'completed', 'credit', 'delivery', 'Hand-picked cotton premium', '2024-03-15 08:00:00', '2024-04-01 08:00:00'),
(30, 8, 8, 150, '2024-04-10 09:00:00', 'completed', 'credit', 'delivery', 'Silk for wedding season', '2024-03-25 10:00:00', '2024-04-10 07:00:00'),
(31, 1, 1, 200, '2024-05-01 10:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton bales - Q2 major order', '2024-04-15 06:00:00', '2024-05-01 08:00:00'),
(32, 7, 4, 8000, '2024-05-10 14:00:00', 'completed', 'credit', 'delivery', 'Thread variety - all colors', '2024-04-25 07:00:00', '2024-05-10 12:30:00'),
(33, 6, 3, 250, '2024-05-15 08:00:00', 'completed', 'bank_transfer', 'pickup', 'Summer color dyes', '2024-05-01 08:00:00', '2024-05-15 06:00:00'),
(34, 14, 6, 6000, '2024-06-01 09:00:00', 'completed', 'credit', 'delivery', 'Long staple cotton fiber', '2024-05-15 09:00:00', '2024-06-01 07:00:00'),
(35, 9, 5, 12000, '2024-06-10 10:00:00', 'completed', 'cash', 'pickup', 'Zippers - mid-year stock', '2024-05-25 05:30:00', '2024-06-10 08:00:00'),
(36, 2, 2, 15000, '2024-07-01 09:00:00', 'completed', 'credit', 'delivery', 'Organic cotton - Q3 supply', '2024-06-15 07:00:00', '2024-07-01 07:00:00'),
(37, 10, 23, 15000, '2024-07-10 11:00:00', 'completed', 'cash', 'pickup', 'Elastic bands bulk order', '2024-06-25 09:00:00', '2024-07-10 09:00:00'),
(38, 1, 1, 250, '2024-08-01 10:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton - back to school peak', '2024-07-15 06:00:00', '2024-08-01 08:00:00'),
(39, 7, 4, 10000, '2024-08-10 14:00:00', 'completed', 'credit', 'delivery', 'Thread - school uniform season', '2024-07-25 05:00:00', '2024-08-10 12:00:00'),
(40, 6, 3, 300, '2024-08-15 08:00:00', 'completed', 'bank_transfer', 'pickup', 'Dyes - school colors bulk', '2024-08-01 08:00:00', '2024-08-15 06:00:00'),
(41, 5, 1, 150, '2024-09-01 09:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton bales regular supply', '2024-08-15 07:00:00', '2024-09-01 07:00:00'),
(42, 9, 5, 15000, '2024-09-10 10:00:00', 'completed', 'cash', 'pickup', 'Zippers - Q3 closing stock', '2024-08-25 06:00:00', '2024-09-10 08:00:00'),
(43, 15, 21, 5000, '2024-09-15 11:00:00', 'completed', 'cash', 'delivery', 'Cotton seeds for oil extraction', '2024-09-01 09:00:00', '2024-09-15 09:00:00'),
(44, 3, 6, 10000, '2024-10-01 14:00:00', 'completed', 'bank_transfer', 'delivery', 'Yarn for Q4 production', '2024-09-15 06:00:00', '2024-10-01 12:00:00'),
(45, 1, 1, 300, '2024-10-10 09:00:00', 'completed', 'bank_transfer', 'delivery', 'Q4 cotton mega supply', '2024-09-25 07:00:00', '2024-10-10 07:30:00'),
(46, 7, 4, 12000, '2024-11-01 14:00:00', 'completed', 'credit', 'delivery', 'Thread - festive season prep', '2024-10-15 05:00:00', '2024-11-01 12:00:00'),
(47, 6, 3, 200, '2024-11-10 08:00:00', 'completed', 'bank_transfer', 'pickup', 'Holiday season dyes', '2024-10-25 08:00:00', '2024-11-10 06:00:00'),
(48, 16, 23, 8000, '2024-11-15 10:00:00', 'completed', 'cash', 'pickup', 'Interlining year-end stock', '2024-11-01 09:00:00', '2024-11-15 08:00:00'),
(49, 2, 2, 20000, '2024-12-01 09:00:00', 'completed', 'credit', 'delivery', 'Organic cotton - 2025 prep', '2024-11-15 07:00:00', '2024-12-01 07:00:00'),
(50, 1, 1, 200, '2024-12-15 10:00:00', 'completed', 'bank_transfer', 'delivery', 'Year-end cotton supply', '2024-12-01 06:00:00', '2024-12-15 08:00:00'),
(51, 4, 1, 150, '2025-01-10 09:00:00', 'completed', 'bank_transfer', 'delivery', 'New year cotton supply', '2024-12-26 07:00:00', '2025-01-10 07:00:00'),
(52, 7, 4, 15000, '2025-01-20 14:00:00', 'completed', 'credit', 'delivery', 'Thread - Q1 2025 bulk', '2025-01-05 05:00:00', '2025-01-20 12:00:00'),
(53, 9, 5, 18000, '2025-02-01 10:00:00', 'completed', 'cash', 'pickup', 'Zippers - school term prep', '2025-01-15 06:00:00', '2025-02-01 08:00:00'),
(54, 6, 3, 350, '2025-02-10 08:00:00', 'completed', 'bank_transfer', 'pickup', 'Spring color dye series', '2025-01-25 08:00:00', '2025-02-10 06:00:00'),
(55, 2, 2, 25000, '2025-02-20 09:00:00', 'completed', 'credit', 'delivery', 'Q1 organic cotton major order', '2025-02-05 07:00:00', '2025-02-20 07:00:00'),
(56, 1, 1, 350, '2025-03-01 10:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton bales - Q1 closing', '2025-02-15 06:00:00', '2025-03-01 08:00:00'),
(57, 10, 22, 12000, '2025-03-10 11:00:00', 'completed', 'cash', 'pickup', 'Button variety - Easter', '2025-02-25 09:00:00', '2025-03-10 09:00:00'),
(58, 3, 6, 12000, '2025-03-20 14:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton yarn Q2 prep', '2025-03-05 06:00:00', '2025-03-20 12:00:00'),
(59, 8, 8, 250, '2025-04-01 09:00:00', 'completed', 'credit', 'delivery', 'Silk - wedding season start', '2025-03-15 10:00:00', '2025-04-01 07:00:00'),
(60, 7, 4, 20000, '2025-04-10 14:00:00', 'completed', 'credit', 'delivery', 'Thread mega order - Q2', '2025-03-25 05:00:00', '2025-04-10 12:30:00'),
(61, 5, 1, 200, '2025-05-01 09:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton lint bales', '2025-04-15 07:00:00', '2025-05-01 07:00:00'),
(62, 9, 5, 20000, '2025-05-10 10:00:00', 'completed', 'cash', 'pickup', 'Zippers - mid-year buildup', '2025-04-25 06:00:00', '2025-05-10 08:00:00'),
(63, 6, 3, 400, '2025-05-20 08:00:00', 'completed', 'bank_transfer', 'pickup', 'Summer dye collection', '2025-05-05 08:00:00', '2025-05-20 06:00:00'),
(64, 17, 30, 1000, '2025-06-01 11:00:00', 'completed', 'credit', 'delivery', 'Leather straps for bags', '2025-05-15 09:00:00', '2025-06-01 09:00:00'),
(65, 1, 1, 400, '2025-06-10 10:00:00', 'completed', 'bank_transfer', 'delivery', 'Cotton - mid-year major order', '2025-05-25 06:00:00', '2025-06-10 08:00:00'),
(66, 2, 2, 30000, '2025-06-20 09:00:00', 'completed', 'credit', 'delivery', 'Organic cotton Q3 prep', '2025-06-05 07:00:00', '2025-06-20 07:00:00'),
(67, 7, 4, 25000, '2025-07-01 14:00:00', 'completed', 'credit', 'delivery', 'Thread - back to school prep', '2025-06-15 05:00:00', '2025-07-01 12:00:00'),
(68, 9, 5, 25000, '2025-07-10 10:00:00', 'in_progress', 'cash', 'pickup', 'Zippers - school season bulk', '2025-06-25 06:00:00', '2025-07-15 08:00:00'),
(69, 6, 3, 500, '2025-07-20 08:00:00', 'in_progress', 'bank_transfer', 'pickup', 'School uniform dyes - bulk', '2025-07-05 08:00:00', '2025-07-16 06:00:00'),
(70, 1, 1, 500, '2025-07-25 10:00:00', 'approved', 'bank_transfer', 'delivery', 'Cotton - peak season order', '2025-07-10 06:00:00', '2025-07-15 07:00:00'),
(71, 10, 22, 15000, '2025-07-30 11:00:00', 'approved', 'cash', 'pickup', 'Buttons - uniform season', '2025-07-15 09:00:00', '2025-07-16 09:00:00'),
(72, 3, 6, 15000, '2025-08-05 14:00:00', 'approved', 'bank_transfer', 'delivery', 'Yarn for August production', '2025-07-20 06:00:00', '2025-07-20 06:00:00'),
(73, 7, 4, 30000, '2025-08-10 14:00:00', 'pending', 'credit', 'delivery', 'Thread - largest order 2025', '2025-07-25 05:00:00', '2025-07-25 05:00:00'),
(74, 2, 2, 35000, '2025-08-15 09:00:00', 'pending', 'credit', 'delivery', 'Organic cotton - Q3 peak', '2025-07-30 07:00:00', '2025-07-30 07:00:00'),
(75, 9, 5, 30000, '2025-08-20 10:00:00', 'pending', 'cash', 'pickup', 'Zippers - term 3 schools', '2025-08-05 06:00:00', '2025-08-05 06:00:00'),
(76, 20, 27, 100, '2024-10-20 10:00:00', 'rejected', 'cash', 'delivery', 'Bark cloth - quality issues', '2024-10-05 08:00:00', '2024-10-06 06:00:00'),
(77, 19, 29, 5000, '2024-08-25 14:00:00', 'rejected', 'credit', 'delivery', 'Denim - wrong specifications', '2024-08-10 06:00:00', '2024-08-11 07:00:00'),
(78, 11, 2, 5000, '2025-01-30 09:00:00', 'rejected', 'credit', 'delivery', 'Organic cotton - certification expired', '2025-01-15 07:00:00', '2025-01-16 05:00:00'),
(79, 14, 6, 8000, '2025-06-15 09:00:00', 'completed', 'cash', 'delivery', 'Cotton fiber - remote delivery', '2025-06-01 07:00:00', '2025-06-15 07:00:00'),
(80, 15, 1, 100, '2025-05-25 10:00:00', 'completed', 'bank_transfer', 'pickup', 'Cotton bales - self collection', '2025-05-10 08:00:00', '2025-05-25 08:00:00'),
(81, 1, 1, 100, '2023-12-20 10:00:00', 'completed', 'bank_transfer', 'delivery', 'Low season order', '2023-12-05 06:00:00', '2023-12-20 08:00:00'),
(82, 1, 1, 600, '2024-07-20 10:00:00', 'completed', 'bank_transfer', 'delivery', 'Peak season mega order', '2024-07-05 06:00:00', '2024-07-20 08:00:00'),
(83, 2, 2, 50000, '2025-09-01 09:00:00', 'pending', 'credit', 'delivery', 'Q3-Q4 organic cotton contract', '2025-07-15 07:00:00', '2025-07-15 07:00:00'),
(84, 7, 4, 40000, '2025-09-15 14:00:00', 'pending', 'credit', 'delivery', 'Annual thread contract - Q4', '2025-07-20 05:00:00', '2025-07-20 05:00:00'),
(85, 6, 3, 50, '2024-06-18 08:00:00', 'completed', 'cash', 'pickup', 'URGENT: Dye shortage', '2024-06-17 13:00:00', '2024-06-18 06:00:00'),
(86, 9, 5, 2000, '2024-08-08 10:00:00', 'completed', 'cash', 'pickup', 'RUSH: Zipper stock out', '2024-08-07 12:00:00', '2024-08-08 08:00:00'),
(87, 12, 8, 10, '2025-03-25 11:00:00', 'completed', 'credit', 'delivery', 'Silk yarn for premium line', '2025-03-10 09:00:00', '2025-03-25 09:00:00'),
(88, 18, 2, 3000, '2025-04-20 10:00:00', 'completed', 'cash', 'delivery', 'West Nile cotton variety', '2025-04-05 08:00:00', '2025-04-20 08:00:00'),
(89, 20, 27, 30, '2025-05-05 09:00:00', 'completed', 'cash', 'pickup', 'Bark cloth - cultural collection', '2025-04-20 07:00:00', '2025-05-05 07:00:00'),
(90, 1, 1, 200, '2024-09-05 10:00:00', 'completed', 'bank_transfer', 'delivery', 'Regular supplier - on time', '2024-08-20 06:00:00', '2024-09-05 07:00:00'),
(91, 4, 1, 150, '2024-09-15 09:00:00', 'completed', 'bank_transfer', 'delivery', 'Alternative supplier test', '2024-09-01 07:00:00', '2024-09-15 07:00:00'),
(92, 5, 1, 180, '2024-09-25 10:00:00', 'completed', 'bank_transfer', 'delivery', 'Supplier comparison order', '2024-09-10 08:00:00', '2024-09-25 08:00:00'),
(93, 11, 2, 5000, '2025-02-15 09:00:00', 'completed', 'credit', 'delivery', 'Premium organic - certified', '2025-02-01 07:00:00', '2025-02-15 07:00:00'),
(94, 2, 2, 8000, '2025-03-05 10:00:00', 'completed', 'credit', 'delivery', 'Standard organic cotton', '2025-02-20 08:00:00', '2025-03-05 08:00:00'),
(95, 1, 1, 20, '2024-05-05 10:00:00', 'completed', 'cash', 'pickup', 'Small trial order', '2024-04-20 06:00:00', '2024-05-05 08:00:00'),
(96, 1, 1, 800, '2024-08-20 10:00:00', 'completed', 'credit', 'delivery', 'Bulk annual order', '2024-08-05 06:00:00', '2024-08-20 08:00:00'),
(97, 6, 3, 600, '2025-07-12 08:00:00', 'in_progress', 'bank_transfer', 'pickup', 'School color dyes - variety', '2025-06-28 08:00:00', '2025-07-15 06:00:00'),
(98, 10, 23, 20000, '2025-07-18 11:00:00', 'approved', 'cash', 'pickup', 'Elastic bands - peak demand', '2025-07-03 09:00:00', '2025-07-16 09:00:00'),
(99, 16, 7, 5000, '2025-07-22 10:00:00', 'pending', 'cash', 'delivery', 'Interlining materials bulk', '2025-07-08 08:00:00', '2025-07-08 08:00:00'),
(100, 7, 7, 10000, '2025-07-28 14:00:00', 'pending', 'credit', 'delivery', 'Fusible interlining - Q3', '2025-07-13 05:00:00', '2025-07-13 05:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_seen` timestamp NULL DEFAULT NULL,
  `is_verified` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_picture`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `last_seen`, `is_verified`) VALUES
(1, 'Uganda Textiles Manufacturing Ltd', 'info@ugandatextiles.co.ug', '/uploads/profiles/manufacturer_1.jpg', '2023-01-15 05:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'manufacturer', NULL, '2023-01-10 06:00:00', '2025-07-19 13:08:09', '2025-07-19 13:08:09', 'approved'),
(2, 'Kampala Fabrics Wholesale', 'sales@kampalafabrics.co.ug', '/uploads/profiles/wholesaler_2.jpg', '2023-02-01 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-01-20 08:00:00', '2025-07-17 00:45:00', '2025-07-17 00:45:00', 'approved'),
(3, 'Jinja Textile Traders', 'info@jinjatextiles.co.ug', '/uploads/profiles/wholesaler_3.jpg', '2023-02-05 06:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-01-25 07:30:00', '2025-07-16 19:00:00', '2025-07-16 19:00:00', 'approved'),
(4, 'Mbarara Cloth House', 'contact@mbararacloth.co.ug', NULL, '2023-02-10 08:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-02-01 09:00:00', '2025-07-16 22:30:00', '2025-07-16 22:30:00', 'approved'),
(5, 'Gulu Northern Fabrics', 'sales@gulufabrics.co.ug', '/uploads/profiles/wholesaler_5.jpg', '2023-02-15 05:45:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-02-10 06:00:00', '2025-07-15 15:20:00', '2025-07-15 15:20:00', 'approved'),
(6, 'Masaka Textile Distributors', 'info@masakatextile.co.ug', '/uploads/profiles/wholesaler_6.jpg', '2023-03-01 07:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-02-20 08:00:00', '2025-07-16 11:15:00', '2025-07-16 11:15:00', 'approved'),
(7, 'Fort Portal Garments Wholesale', 'wholesale@fortportalgarments.co.ug', NULL, '2023-03-10 06:15:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-03-05 07:00:00', '2025-07-16 23:00:00', '2025-07-16 23:00:00', 'approved'),
(8, 'Mbale Eastern Textiles', 'orders@mbaletextiles.co.ug', '/uploads/profiles/wholesaler_8.jpg', '2023-03-15 08:45:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-03-10 09:00:00', '2025-07-14 06:30:00', '2025-07-14 06:30:00', 'approved'),
(9, 'Arua West Nile Fabrics', 'info@aruafabrics.co.ug', '/uploads/profiles/wholesaler_9.jpg', '2023-03-20 05:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-03-15 06:00:00', '2025-07-16 17:45:00', '2025-07-16 17:45:00', 'approved'),
(10, 'Entebbe Lakeside Textiles', 'sales@entebbelakeside.co.ug', NULL, '2023-04-01 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-03-25 08:00:00', '2025-07-16 21:15:00', '2025-07-16 21:15:00', 'approved'),
(11, 'Lira Central Cloth Market', 'orders@liracloth.co.ug', '/uploads/profiles/wholesaler_11.jpg', NULL, '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-04-05 07:30:00', '2025-07-15 13:00:00', '2025-07-15 13:00:00', 'pending'),
(12, 'Hoima Oil City Fabrics', 'info@hoimafabrics.co.ug', '/uploads/profiles/wholesaler_12.jpg', '2023-04-15 06:45:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-04-10 08:00:00', '2025-07-13 09:30:00', '2025-07-13 09:30:00', 'approved'),
(13, 'Wakiso Urban Textiles', 'contact@wakisotextiles.co.ug', NULL, '2023-04-20 08:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'wholesaler', NULL, '2023-04-15 09:00:00', '2025-07-16 05:20:00', '2025-07-16 05:20:00', 'approved'),
(14, 'Kasese Cotton Suppliers', 'supply@kasesecotton.co.ug', '/uploads/profiles/supplier_14.jpg', '2023-02-20 06:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-02-15 07:00:00', '2025-07-17 00:15:00', '2025-07-17 00:15:00', 'approved'),
(15, 'Gulu Cotton Growers Coop', 'info@gulucotton.co.ug', '/uploads/profiles/supplier_15.jpg', '2023-02-25 07:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-02-20 08:30:00', '2025-07-16 16:45:00', '2025-07-16 16:45:00', 'approved'),
(16, 'Soroti Fiber Traders', 'sales@sorotifiber.co.ug', NULL, '2023-03-05 05:45:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-03-01 06:00:00', '2025-07-15 11:30:00', '2025-07-15 11:30:00', 'approved'),
(17, 'Masindi Cotton Merchants', 'cotton@masindimerchants.co.ug', '/uploads/profiles/supplier_17.jpg', '2023-03-10 08:15:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-03-05 09:00:00', '2025-07-16 22:00:00', '2025-07-16 22:00:00', 'approved'),
(18, 'Lira Agricultural Supplies', 'agric@lirasupplies.co.ug', '/uploads/profiles/supplier_18.jpg', '2023-03-15 06:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-03-10 07:00:00', '2025-07-16 19:30:00', '2025-07-16 19:30:00', 'approved'),
(19, 'Kampala Dye & Chemicals Ltd', 'chemicals@kampaladye.co.ug', '/uploads/profiles/supplier_19.jpg', '2023-03-20 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-03-15 08:00:00', '2025-07-17 01:00:00', '2025-07-17 01:00:00', 'approved'),
(20, 'Jinja Thread Industries', 'threads@jinjathreads.co.ug', NULL, '2023-03-25 05:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-03-20 06:00:00', '2025-07-16 13:45:00', '2025-07-16 13:45:00', 'approved'),
(21, 'Tororo Silk Suppliers', 'silk@tororosilk.co.ug', '/uploads/profiles/supplier_21.jpg', '2023-04-01 08:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-03-25 09:00:00', '2025-07-14 10:20:00', '2025-07-14 10:20:00', 'approved'),
(22, 'Mukono Zipper & Accessories', 'accessories@mukonozippers.co.ug', '/uploads/profiles/supplier_22.jpg', '2023-04-05 06:45:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-04-01 07:30:00', '2025-07-16 23:30:00', '2025-07-16 23:30:00', 'approved'),
(23, 'Iganga Button Factory', 'buttons@igangafactory.co.ug', NULL, '2023-04-10 07:15:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-04-05 08:00:00', '2025-07-15 06:00:00', '2025-07-15 06:00:00', 'approved'),
(24, 'Bushenyi Organic Cotton', 'organic@bushenyicotton.co.ug', '/uploads/profiles/supplier_24.jpg', NULL, '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-04-10 09:00:00', '2025-07-16 08:15:00', '2025-07-16 08:15:00', 'pending'),
(25, 'Kabale Wool Traders', 'wool@kabalewool.co.ug', '/uploads/profiles/supplier_25.jpg', '2023-04-20 05:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-04-15 06:00:00', '2025-07-13 15:30:00', '2025-07-13 15:30:00', 'approved'),
(26, 'Apac Cotton Collective', 'cotton@apaccollective.co.ug', NULL, '2023-04-25 07:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-04-20 08:00:00', '2025-07-16 21:45:00', '2025-07-16 21:45:00', 'approved'),
(27, 'Kitgum Fiber Processing', 'fiber@kitgumprocessing.co.ug', '/uploads/profiles/supplier_27.jpg', '2023-05-01 06:15:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-04-25 07:00:00', '2025-07-16 02:20:00', '2025-07-16 02:20:00', 'approved'),
(28, 'Pallisa Cotton Union', 'union@pallisacotton.co.ug', '/uploads/profiles/supplier_28.jpg', '2023-05-05 08:45:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-05-01 09:00:00', '2025-07-15 18:00:00', '2025-07-15 18:00:00', 'approved'),
(29, 'Rakai Textile Materials', 'materials@rakaitextile.co.ug', NULL, '2023-05-10 05:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-05-05 06:30:00', '2025-07-14 04:45:00', '2025-07-14 04:45:00', 'approved'),
(30, 'Moroto Leather Suppliers', 'leather@morotoleather.co.ug', '/uploads/profiles/supplier_30.jpg', '2023-05-15 07:00:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-05-10 08:00:00', '2025-07-16 14:30:00', '2025-07-16 14:30:00', 'approved'),
(31, 'Nebbi Cotton Farms', 'farms@nebbicotton.co.ug', '/uploads/profiles/supplier_31.jpg', '2023-05-20 06:30:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-05-15 07:30:00', '2025-07-12 12:15:00', '2025-07-12 12:15:00', 'approved'),
(32, 'Kamuli Fabric Finishers', 'finishing@kamulifabric.co.ug', NULL, NULL, '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-05-20 09:00:00', '2025-07-17 00:45:00', '2025-07-17 00:45:00', 'rejected'),
(33, 'Kyenjojo Natural Fibers', 'natural@kyenjojofibers.co.ug', '/uploads/profiles/supplier_33.jpg', '2023-06-01 05:45:00', '$2y$10$8Uk/8jlrCQi91xO1IXxjU.VsQtO5MCna8wGzjF2A1BHrsvTDLeAte', 'supplier', NULL, '2023-05-25 06:00:00', '2025-07-10 09:00:00', '2025-07-10 09:00:00', 'approved'),
(34, 'Demo Admin', 'admin_demo@chicaura.com', NULL, NULL, '$2y$10$gKO3IuJS.P3Vbt2a.IE9z.8uFPdq4wWF31upEVn0miuol4gOOP6XW', 'admin', NULL, '2025-07-18 11:00:21', '2025-07-18 11:00:21', NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `manufacturer_id` bigint(20) UNSIGNED NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `manufacturer_id`, `location`, `capacity`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mukono', 15000, '2023-01-20 07:00:00', '2025-07-17 01:00:00'),
(2, 1, 'Kampala', 12000, '2023-01-25 08:30:00', '2025-07-17 00:45:00'),
(3, 1, 'Jinja', 8000, '2023-02-10 06:00:00', '2025-07-16 19:30:00'),
(4, 1, 'Mbarara', 6000, '2023-02-15 07:30:00', '2025-07-16 15:15:00'),
(5, 1, 'Gulu', 5000, '2023-03-01 05:45:00', '2025-07-15 11:20:00'),
(6, 1, 'Mbale', 4500, '2023-03-20 08:00:00', '2025-07-16 06:00:00'),
(7, 1, 'Kasese', 3500, '2023-04-05 06:30:00', '2025-07-14 13:45:00'),
(8, 1, 'Lira', 3000, '2023-04-15 07:00:00', '2025-07-15 08:30:00'),
(9, 1, 'Masindi', 2800, '2023-05-01 05:00:00', '2025-07-13 17:00:00'),
(10, 1, 'Entebbe', 7000, '2023-05-10 08:30:00', '2025-07-16 22:15:00'),
(11, 1, 'Tororo', 4000, '2023-06-01 06:45:00', '2025-07-16 04:30:00'),
(12, 1, 'Wakiso', 5500, '2023-06-15 07:30:00', '2025-07-15 16:00:00'),
(13, 1, 'Soroti', 2500, '2023-07-01 05:30:00', '2025-07-14 09:45:00'),
(14, 1, 'Arua', 2200, '2023-07-20 08:00:00', '2025-07-13 05:30:00'),
(15, 1, 'Fort Portal', 2000, '2023-08-05 06:00:00', '2025-07-12 12:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_workforce`
--

CREATE TABLE `warehouse_workforce` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `workforce_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouse_workforce`
--

INSERT INTO `warehouse_workforce` (`id`, `warehouse_id`, `workforce_id`, `created_at`, `updated_at`) VALUES
(1, 1, 13, '2023-04-10 06:30:00', '2025-07-16 23:15:00'),
(2, 1, 15, '2023-04-20 07:30:00', '2025-07-15 03:45:00'),
(3, 1, 5, '2023-02-01 06:00:00', '2025-07-16 19:15:00'),
(4, 1, 7, '2023-02-15 06:15:00', '2025-07-16 22:00:00'),
(5, 1, 16, '2023-05-01 05:30:00', '2025-07-14 16:20:00'),
(6, 1, 23, '2023-07-01 06:30:00', '2025-07-17 00:00:00'),
(7, 2, 14, '2023-04-15 06:00:00', '2025-07-16 09:30:00'),
(8, 2, 1, '2023-01-15 06:30:00', '2025-07-17 01:00:00'),
(9, 2, 19, '2023-05-20 06:00:00', '2025-07-15 18:15:00'),
(10, 2, 9, '2023-03-10 05:30:00', '2025-07-17 00:30:00'),
(11, 2, 11, '2023-03-20 06:00:00', '2025-07-16 17:00:00'),
(12, 3, 6, '2023-02-10 06:30:00', '2025-07-14 15:30:00'),
(13, 4, 21, '2023-06-10 05:30:00', '2025-07-13 11:30:00'),
(14, 5, 12, '2023-04-01 07:30:00', '2025-07-10 05:00:00'),
(15, 6, 17, '2023-05-10 06:30:00', '2025-07-16 13:00:00'),
(16, 10, 3, '2023-01-10 05:30:00', '2025-07-17 00:45:00'),
(17, 10, 2, '2023-01-15 07:00:00', '2025-07-16 12:30:00'),
(18, 10, 18, '2023-05-15 07:30:00', '2025-07-16 21:30:00'),
(19, 12, 8, '2023-03-01 07:30:00', '2025-07-16 06:45:00'),
(20, 12, 10, '2023-03-15 06:30:00', '2025-07-15 11:00:00'),
(21, 8, 25, '2023-07-15 07:30:00', '2024-12-31 14:00:00'),
(22, 14, 22, '2023-06-15 07:30:00', '2025-07-16 02:45:00'),
(23, 1, 3, '2023-01-10 06:00:00', '2025-07-17 00:45:00'),
(24, 7, 4, '2023-01-20 07:30:00', '2025-07-15 08:20:00'),
(25, 1, 24, '2023-07-10 06:00:00', '2025-07-15 07:15:00'),
(26, 2, 24, '2023-07-10 06:30:00', '2025-07-15 07:15:00'),
(27, 3, 2, '2023-01-15 07:30:00', '2025-07-16 12:30:00'),
(28, 5, 4, '2023-01-20 08:00:00', '2025-07-15 08:20:00'),
(29, 13, 16, '2023-05-01 06:00:00', '2025-07-14 16:20:00'),
(30, 15, 17, '2023-05-10 07:00:00', '2025-07-16 13:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `wholesalers`
--

CREATE TABLE `wholesalers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `business_address` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `license_document` varchar(255) NOT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `business_type` varchar(255) NOT NULL,
  `preferred_categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`preferred_categories`)),
  `monthly_order_volume` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wholesalers`
--

INSERT INTO `wholesalers` (`id`, `user_id`, `business_address`, `phone`, `license_document`, `document_path`, `business_type`, `preferred_categories`, `monthly_order_volume`, `created_at`, `updated_at`) VALUES
(1, 2, 'Plot 125-130, Nakivubo Road, Container Village, P.O. Box 12345, Kampala', '+256414252525', 'URSB/WHL/2023/000101', '/documents/licenses/wholesaler_2_license.pdf', 'General Textile Distributor', '{\"categories\": [\n  {\n    \"name\": \"Industrial Textiles\",\n    \"percentage\": 45,\n    \"subcategories\": [\"Canvas\", \"Tarpaulins\", \"Industrial uniforms\", \"Safety wear\"],\n    \"key_clients\": [\"Factories\", \"Construction companies\", \"Agricultural sector\"]\n  },\n  {\n    \"name\": \"Cotton Fabrics\",\n    \"percentage\": 30,\n    \"subcategories\": [\"Denim\", \"Twill\", \"Canvas\", \"Duck cloth\"],\n    \"specialization\": \"Heavy-duty fabrics\"\n  },\n  {\n    \"name\": \"Uniform Materials\",\n    \"percentage\": 25,\n    \"subcategories\": [\"School uniform fabric\", \"Corporate wear\", \"Medical uniforms\"],\n    \"services\": [\"Bulk ordering\", \"Custom colors\"]\n  }\n],\n\"warehouse_capacity\": \"2000sqm\",\n\"credit_facility\": true,\n\"export_capable\": true}', 8500, '2023-01-20 08:30:00', '2025-07-17 00:45:00'),
(2, 3, 'Jinja Commercial Plaza, Main Street, Plot 45-47, P.O. Box 2345, Jinja', '+256434120120', 'URSB/WHL/2023/000102', '/documents/licenses/wholesaler_3_license.pdf', 'Regional Fabric Wholesaler', '{\"categories\": [\n  {\n    \"name\": \"Industrial Textiles\",\n    \"percentage\": 45,\n    \"subcategories\": [\"Canvas\", \"Tarpaulins\", \"Industrial uniforms\", \"Safety wear\"],\n    \"key_clients\": [\"Factories\", \"Construction companies\", \"Agricultural sector\"]\n  },\n  {\n    \"name\": \"Cotton Fabrics\",\n    \"percentage\": 30,\n    \"subcategories\": [\"Denim\", \"Twill\", \"Canvas\", \"Duck cloth\"],\n    \"specialization\": \"Heavy-duty fabrics\"\n  },\n  {\n    \"name\": \"Uniform Materials\",\n    \"percentage\": 25,\n    \"subcategories\": [\"School uniform fabric\", \"Corporate wear\", \"Medical uniforms\"],\n    \"services\": [\"Bulk ordering\", \"Custom colors\"]\n  }\n],\n\"warehouse_capacity\": \"2000sqm\",\n\"credit_facility\": true,\n\"export_capable\": true}', 6200, '2023-01-25 08:00:00', '2025-07-16 19:00:00'),
(3, 4, 'Mbarara Central Market, Shop Block C, Plot 234, P.O. Box 3456, Mbarara', '+256485201201', 'URSB/WHL/2023/000103', NULL, 'Fashion and Garment Wholesaler', '{\"categories\": [\r\n  {\r\n    \"name\": \"Fashion Garments\",\r\n    \"percentage\": 50,\r\n    \"subcategories\": [\"Ladies wear\", \"Men\'s clothing\", \"Children\'s wear\", \"Traditional wear\"],\r\n    \"style_focus\": \"Contemporary African fashion\"\r\n  },\r\n  {\r\n    \"name\": \"Accessories\",\r\n    \"percentage\": 20,\r\n    \"subcategories\": [\"Scarves\", \"Belts\", \"Fashion jewelry\", \"Handbags\"],\r\n    \"sourcing\": \"Local artisans + imports\"\r\n  },\r\n  {\r\n    \"name\": \"Fabric Retail\",\r\n    \"percentage\": 30,\r\n    \"subcategories\": [\"Ankara prints\", \"Lace\", \"Silk\", \"Chiffon\"],\r\n    \"customer_base\": \"Fashion boutiques\"\r\n  }\r\n],\r\n\"market_coverage\": [\"Mbarara\", \"Bushenyi\", \"Kasese\", \"Kabale\"],\r\n\"fashion_seasons\": 4,\r\n\"social_media_sales\": true}', 4800, '2023-02-01 09:30:00', '2025-07-16 22:30:00'),
(4, 5, 'Gulu Main Market, Northern Textiles House, P.O. Box 4567, Gulu', '+256471303030', 'URSB/WHL/2023/000104', '/documents/licenses/wholesaler_5_license.pdf', 'Northern Region Distributor', '{\"categories\": [\r\n  {\r\n    \"name\": \"Traditional Textiles\",\r\n    \"percentage\": 35,\r\n    \"subcategories\": [\"Acholi patterns\", \"Cultural wear\", \"Ceremonial cloth\"],\r\n    \"cultural_significance\": \"High\",\r\n    \"artisan_network\": true\r\n  },\r\n  {\r\n    \"name\": \"General Fabrics\",\r\n    \"percentage\": 40,\r\n    \"subcategories\": [\"Cotton prints\", \"Plain fabrics\", \"Polyester blends\"],\r\n    \"price_point\": \"Affordable\"\r\n  },\r\n  {\r\n    \"name\": \"Agricultural Textiles\",\r\n    \"percentage\": 25,\r\n    \"subcategories\": [\"Grain bags\", \"Shade nets\", \"Ground covers\"],\r\n    \"seasonal_demand\": true\r\n  }\r\n],\r\n\"distribution_network\": [\"Gulu\", \"Kitgum\", \"Pader\", \"Lira\"],\r\n\"NGO_partnerships\": true,\r\n\"bulk_discount_tiers\": [5, 10, 15, 20]}', 3500, '2023-02-10 06:30:00', '2025-07-15 15:20:00'),
(5, 6, 'Masaka Industrial Area, Textile Quarter, Plot 56, P.O. Box 5678, Masaka', '+256481404040', 'URSB/WHL/2023/000105', '/documents/licenses/wholesaler_6_license.pdf', 'Specialized Fabric Trader', '{\"categories\": [\r\n  {\r\n    \"name\": \"Premium Fabrics\",\r\n    \"percentage\": 45,\r\n    \"subcategories\": [\"Silk\", \"Wool blends\", \"High-end cotton\", \"Imported fabrics\"],\r\n    \"target_market\": \"High-end tailors and designers\"\r\n  },\r\n  {\r\n    \"name\": \"Wedding & Events\",\r\n    \"percentage\": 30,\r\n    \"subcategories\": [\"Bridal fabrics\", \"Decoration textiles\", \"Traditional ceremony wear\"],\r\n    \"services\": [\"Color matching\", \"Special orders\"]\r\n  },\r\n  {\r\n    \"name\": \"Tailoring Supplies\",\r\n    \"percentage\": 25,\r\n    \"subcategories\": [\"Threads\", \"Linings\", \"Interfacing\", \"Notions\"],\r\n    \"one_stop_shop\": true\r\n  }\r\n],\r\n\"showroom_size\": \"500sqm\",\r\n\"appointment_viewing\": true,\r\n\"custom_import_service\": true}', 3200, '2023-02-20 08:30:00', '2025-07-16 11:15:00'),
(6, 7, 'Fort Portal Town, Market Street, Plot 78, P.O. Box 6789, Fort Portal', '+256483505050', 'URSB/WHL/2023/000106', NULL, 'Western Textiles Distributor', '{\"categories\": [\r\n  {\r\n    \"name\": \"Hospitality Textiles\",\r\n    \"percentage\": 40,\r\n    \"subcategories\": [\"Hotel linens\", \"Restaurant textiles\", \"Spa towels\"],\r\n    \"clients\": [\"Hotels\", \"Lodges\", \"Tourism sector\"],\r\n    \"quality_standards\": \"International\"\r\n  },\r\n  {\r\n    \"name\": \"Household Textiles\",\r\n    \"percentage\": 35,\r\n    \"subcategories\": [\"Bedding\", \"Kitchen textiles\", \"Bathroom sets\"],\r\n    \"brands\": [\"Local production\", \"Imported lines\"]\r\n  },\r\n  {\r\n    \"name\": \"Eco-Textiles\",\r\n    \"percentage\": 25,\r\n    \"subcategories\": [\"Organic cotton\", \"Bamboo fabric\", \"Recycled textiles\"],\r\n    \"sustainability_certified\": true\r\n  }\r\n],\r\n\"tourism_sector_focus\": true,\r\n\"delivery_to_remote_areas\": true,\r\n\"crater_lakes_region_coverage\": true}', 2800, '2023-03-05 07:30:00', '2025-07-16 23:00:00'),
(7, 8, 'Mbale Industrial Park, Eastern Plaza, Plot 90, P.O. Box 7890, Mbale', '+256454606060', 'URSB/WHL/2023/000107', '/documents/licenses/wholesaler_8_license.pdf', 'Eastern Hub Wholesaler', '{\"categories\": [\r\n  {\r\n    \"name\": \"School Supplies\",\r\n    \"percentage\": 50,\r\n    \"subcategories\": [\"School uniforms\", \"Sports wear\", \"School bags\", \"Shoes\"],\r\n    \"education_sector_specialist\": true,\r\n    \"tender_capable\": true\r\n  },\r\n  {\r\n    \"name\": \"Casual Wear\",\r\n    \"percentage\": 30,\r\n    \"subcategories\": [\"Jeans\", \"T-shirts\", \"Casual dresses\", \"Sportswear\"],\r\n    \"age_groups\": [\"Children\", \"Youth\", \"Adults\"]\r\n  },\r\n  {\r\n    \"name\": \"Work Wear\",\r\n    \"percentage\": 20,\r\n    \"subcategories\": [\"Overalls\", \"Safety boots\", \"Reflective vests\", \"Helmets\"],\r\n    \"safety_standards_compliant\": true\r\n  }\r\n],\r\n\"government_supplier_registered\": true,\r\n\"bulk_order_specialist\": true,\r\n\"regional_distribution_centers\": 3}', 5500, '2023-03-10 09:30:00', '2025-07-14 06:30:00'),
(8, 9, 'Arua Central Business District, Plot 123, P.O. Box 8901, Arua', '+256476707070', 'URSB/WHL/2023/000108', '/documents/licenses/wholesaler_9_license.pdf', 'West Nile Trading Company', '{\"categories\": [\r\n  {\r\n    \"name\": \"Cross-Border Trade\",\r\n    \"percentage\": 40,\r\n    \"subcategories\": [\"DRC market fabrics\", \"South Sudan preferences\", \"Regional styles\"],\r\n    \"currency_accepted\": [\"UGX\", \"USD\", \"CDF\", \"SSP\"]\r\n  },\r\n  {\r\n    \"name\": \"General Merchandise\",\r\n    \"percentage\": 35,\r\n    \"subcategories\": [\"Mixed fabrics\", \"Ready-made clothes\", \"Shoes\", \"Accessories\"],\r\n    \"market_days_focus\": true\r\n  },\r\n  {\r\n    \"name\": \"Relief Supplies\",\r\n    \"percentage\": 25,\r\n    \"subcategories\": [\"Blankets\", \"Tarpaulins\", \"Mosquito nets\", \"Basic clothing\"],\r\n    \"humanitarian_partnerships\": [\"UNHCR\", \"Red Cross\", \"NGOs\"]\r\n  }\r\n],\r\n\"border_town_advantages\": true,\r\n\"wholesale_and_retail\": true,\r\n\"mobile_money_agent\": true}', 4200, '2023-03-15 06:30:00', '2025-07-16 17:45:00'),
(9, 10, 'Entebbe Airport Road, Logistics Center, Plot 45, P.O. Box 9012, Entebbe', '+256414808080', 'URSB/WHL/2023/000109', NULL, 'Import-Export Specialist', '{\"categories\": [\r\n  {\r\n    \"name\": \"Imported Textiles\",\r\n    \"percentage\": 60,\r\n    \"subcategories\": [\"Asian fabrics\", \"European textiles\", \"Specialty materials\"],\r\n    \"import_licenses\": [\"Dubai\", \"China\", \"India\", \"Turkey\"],\r\n    \"customs_clearance_service\": true\r\n  },\r\n  {\r\n    \"name\": \"Export Ready Garments\",\r\n    \"percentage\": 25,\r\n    \"subcategories\": [\"AGOA compliant\", \"EU standards\", \"Organic certified\"],\r\n    \"export_destinations\": [\"USA\", \"EU\", \"Middle East\"]\r\n  },\r\n  {\r\n    \"name\": \"Luxury Textiles\",\r\n    \"percentage\": 15,\r\n    \"subcategories\": [\"Designer fabrics\", \"High-end furnishings\", \"Exclusive prints\"],\r\n    \"showroom_appointments\": true\r\n  }\r\n],\r\n\"bonded_warehouse\": true,\r\n\"air_freight_capable\": true,\r\n\"minimum_import_order\": \"1 container\"}', 7500, '2023-03-25 08:30:00', '2025-07-16 21:15:00'),
(10, 11, 'Lira Town Center, Market Complex, Shop 234, P.O. Box 1234, Lira', '+256473909090', 'URSB/WHL/2023/000110', '/documents/licenses/wholesaler_11_license.pdf', 'Central North Distributor', '{\"categories\": [\r\n  {\r\n    \"name\": \"Cotton Products\",\r\n    \"percentage\": 55,\r\n    \"subcategories\": [\"Locally sourced cotton fabrics\", \"Cotton garments\", \"Raw cotton\"],\r\n    \"support_local_farmers\": true,\r\n    \"cotton_season_stockist\": true\r\n  },\r\n  {\r\n    \"name\": \"Rural Market Goods\",\r\n    \"percentage\": 30,\r\n    \"subcategories\": [\"Simple clothing\", \"Agricultural wear\", \"Basic textiles\"],\r\n    \"mobile_van_service\": true\r\n  },\r\n  {\r\n    \"name\": \"Institution Supplies\",\r\n    \"percentage\": 15,\r\n    \"subcategories\": [\"Hospital linens\", \"School materials\", \"Church fabrics\"],\r\n    \"credit_terms\": \"Net 60 days\"\r\n  }\r\n],\r\n\"rural_outreach_program\": true,\r\n\"farmer_group_partnerships\": 15,\r\n\"seasonal_credit_facility\": true}', 2500, '2023-04-05 08:00:00', '2025-07-15 13:00:00'),
(11, 12, 'Hoima New City, Oil Road, Plot 567, P.O. Box 2345, Hoima', '+256465101010', 'URSB/WHL/2023/000111', '/documents/licenses/wholesaler_12_license.pdf', 'Oil Region Supplier', '{\"categories\": [\r\n  {\r\n    \"name\": \"Industrial Workwear\",\r\n    \"percentage\": 50,\r\n    \"subcategories\": [\"Oil industry uniforms\", \"Safety gear\", \"Fire resistant clothing\"],\r\n    \"certifications\": [\"ISO\", \"API\", \"NEMA approved\"],\r\n    \"oil_sector_registered\": true\r\n  },\r\n  {\r\n    \"name\": \"Corporate Wear\",\r\n    \"percentage\": 30,\r\n    \"subcategories\": [\"Office uniforms\", \"Branded clothing\", \"Executive wear\"],\r\n    \"embroidery_service\": true\r\n  },\r\n  {\r\n    \"name\": \"Hospitality Uniforms\",\r\n    \"percentage\": 20,\r\n    \"subcategories\": [\"Hotel staff uniforms\", \"Security wear\", \"Cleaning staff attire\"],\r\n    \"growing_market\": true\r\n  }\r\n],\r\n\"oil_boom_beneficiary\": true,\r\n\"rapid_delivery_service\": true,\r\n\"international_standards\": true}', 4500, '2023-04-10 08:30:00', '2025-07-13 09:30:00'),
(12, 13, 'Wakiso District, Nansana, Hoima Road, Plot 890, P.O. Box 3456, Wakiso', '+256414111111', 'URSB/WHL/2023/000112', NULL, 'Metropolitan Distributor', '{\"categories\": [\r\n  {\r\n    \"name\": \"Urban Fashion\",\r\n    \"percentage\": 40,\r\n    \"subcategories\": [\"Trendy clothing\", \"Fast fashion\", \"Youth styles\", \"Accessories\"],\r\n    \"instagram_marketing\": true,\r\n    \"influencer_partnerships\": true\r\n  },\r\n  {\r\n    \"name\": \"Middle-Class Home Textiles\",\r\n    \"percentage\": 35,\r\n    \"subcategories\": [\"Modern bedding\", \"Contemporary curtains\", \"Designer cushions\"],\r\n    \"home_delivery\": true,\r\n    \"interior_design_consultation\": true\r\n  },\r\n  {\r\n    \"name\": \"Baby & Maternity\",\r\n    \"percentage\": 25,\r\n    \"subcategories\": [\"Baby clothes\", \"Maternity wear\", \"Nursery textiles\", \"Diapers\"],\r\n    \"specialized_market\": true,\r\n    \"online_catalog\": true\r\n  }\r\n],\r\n\"suburban_focus\": true,\r\n\"middle_income_targeting\": true,\r\n\"e_commerce_enabled\": true}', 5800, '2023-04-15 09:30:00', '2025-07-16 05:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `workforces`
--

CREATE TABLE `workforces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `manufacturer_id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `job_role` varchar(255) NOT NULL,
  `status` enum('Active','Inactive','On Leave','Terminated') NOT NULL DEFAULT 'Active',
  `hire_date` date DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workforces`
--

INSERT INTO `workforces` (`id`, `manufacturer_id`, `fullname`, `email`, `contact_info`, `address`, `job_role`, `status`, `hire_date`, `salary`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nakato Sarah Mugisha', 'sarah.mugisha@ugandatextiles.co.ug', '+256772345601', 'Kololo, Kampala', 'Production Manager', 'Active', '2023-01-15', 8500000, '2023-01-15 06:00:00', '2025-07-17 01:00:00'),
(2, 1, 'Okello James Ochieng', 'james.ochieng@ugandatextiles.co.ug', '+256701234567', 'Ntinda, Kampala', 'Quality Control Manager', 'Active', '2023-01-15', 7500000, '2023-01-15 06:30:00', '2025-07-16 12:30:00'),
(3, 1, 'Kamugisha Robert', 'robert.kamugisha@ugandatextiles.co.ug', '+256759876543', 'Muyenga, Kampala', 'Operations Director', 'Active', '2023-01-10', 12000000, '2023-01-10 05:00:00', '2025-07-17 00:45:00'),
(4, 1, 'Nassozi Grace Lubega', 'grace.lubega@ugandatextiles.co.ug', '+256782456789', 'Bukoto, Kampala', 'HR Manager', 'Active', '2023-01-20', 6500000, '2023-01-20 07:00:00', '2025-07-15 08:20:00'),
(5, 1, 'Mutebi David Ssentongo', 'david.ssentongo@ugandatextiles.co.ug', '+256775123456', 'Mukono Town', 'Textile Engineer', 'Active', '2023-02-01', 4500000, '2023-02-01 05:30:00', '2025-07-16 19:15:00'),
(6, 1, 'Achieng Florence', 'florence.achieng@ugandatextiles.co.ug', '+256703987654', 'Jinja', 'Dyeing Technician', 'Active', '2023-02-10', 3200000, '2023-02-10 06:00:00', '2025-07-14 15:30:00'),
(7, 1, 'Byaruhanga Peter', 'peter.byaruhanga@ugandatextiles.co.ug', '+256788654321', 'Entebbe', 'Weaving Supervisor', 'Active', '2023-02-15', 3800000, '2023-02-15 05:45:00', '2025-07-16 22:00:00'),
(8, 1, 'Namutebi Stella', 'stella.namutebi@ugandatextiles.co.ug', '+256756789012', 'Nansana, Wakiso', 'Pattern Designer', 'Active', '2023-03-01', 4200000, '2023-03-01 07:00:00', '2025-07-16 06:45:00'),
(9, 1, 'Kato Joseph Musoke', 'joseph.musoke@ugandatextiles.co.ug', '+256704567890', 'Kawempe, Kampala', 'Machine Operator', 'Active', '2023-03-10', 1800000, '2023-03-10 05:00:00', '2025-07-17 00:30:00'),
(10, 1, 'Nabirye Fatuma', 'fatuma.nabirye@ugandatextiles.co.ug', '+256771234890', 'Banda, Kampala', 'Sewing Machine Operator', 'Active', '2023-03-15', 1500000, '2023-03-15 06:00:00', '2025-07-15 11:00:00'),
(11, 1, 'Odongo Charles', 'charles.odongo@ugandatextiles.co.ug', '+256789012345', 'Kisasi, Kampala', 'Cutting Specialist', 'Active', '2023-03-20', 2200000, '2023-03-20 05:30:00', '2025-07-16 17:00:00'),
(12, 1, 'Atim Betty Lakot', 'betty.lakot@ugandatextiles.co.ug', '+256702345678', 'Gulu', 'Quality Inspector', 'On Leave', '2023-04-01', 2500000, '2023-04-01 07:00:00', '2025-07-10 05:00:00'),
(13, 1, 'Ssemanda Richard', 'richard.ssemanda@ugandatextiles.co.ug', '+256757890123', 'Mukono', 'Warehouse Supervisor', 'Active', '2023-04-10', 3500000, '2023-04-10 06:00:00', '2025-07-16 23:15:00'),
(14, 1, 'Nalubega Prossy', 'prossy.nalubega@ugandatextiles.co.ug', '+256778901234', 'Kira, Wakiso', 'Inventory Controller', 'Active', '2023-04-15', 2800000, '2023-04-15 05:45:00', '2025-07-16 09:30:00'),
(15, 1, 'Mugabe John Baptist', 'john.mugabe@ugandatextiles.co.ug', '+256705678901', 'Kyambogo', 'Forklift Operator', 'Active', '2023-04-20', 1600000, '2023-04-20 07:00:00', '2025-07-15 03:45:00'),
(16, 1, 'Kaweesi Michael', 'michael.kaweesi@ugandatextiles.co.ug', '+256783456789', 'Namugongo', 'Maintenance Engineer', 'Active', '2023-05-01', 4000000, '2023-05-01 05:00:00', '2025-07-14 16:20:00'),
(17, 1, 'Opolot Simon Peter', 'simon.opolot@ugandatextiles.co.ug', '+256701890123', 'Mbale', 'Electrician', 'Active', '2023-05-10', 2400000, '2023-05-10 06:00:00', '2025-07-16 13:00:00'),
(18, 1, 'Namanya Catherine', 'catherine.namanya@ugandatextiles.co.ug', '+256756234567', 'Wandegeya, Kampala', 'Accountant', 'Active', '2023-05-15', 3600000, '2023-05-15 07:00:00', '2025-07-16 21:30:00'),
(19, 1, 'Kyagulanyi Moses', 'moses.kyagulanyi@ugandatextiles.co.ug', '+256778345678', 'Rubaga, Kampala', 'Sales Coordinator', 'Active', '2023-05-20', 3200000, '2023-05-20 05:30:00', '2025-07-15 18:15:00'),
(20, 1, 'Nakimuli Agnes', 'agnes.nakimuli@ugandatextiles.co.ug', '+256704901234', 'Makindye, Kampala', 'Admin Assistant', 'Inactive', '2023-06-01', 1800000, '2023-06-01 06:00:00', '2025-06-30 14:00:00'),
(21, 1, 'Tumusiime Emmanuel', 'emmanuel.tumusiime@ugandatextiles.co.ug', '+256789234567', 'Kabale', 'Color Matching Specialist', 'Active', '2023-06-10', 3400000, '2023-06-10 05:00:00', '2025-07-13 11:30:00'),
(22, 1, 'Auma Josephine', 'josephine.auma@ugandatextiles.co.ug', '+256702567890', 'Arua', 'Embroidery Designer', 'Active', '2023-06-15', 2900000, '2023-06-15 07:00:00', '2025-07-16 02:45:00'),
(23, 1, 'Ssekandi Paul', 'paul.ssekandi@ugandatextiles.co.ug', '+256775678901', 'Seeta, Mukono', 'Lab Technician', 'Active', '2023-07-01', 3100000, '2023-07-01 06:00:00', '2025-07-17 00:00:00'),
(24, 1, 'Nalwanga Harriet', 'harriet.nalwanga@ugandatextiles.co.ug', '+256758012345', 'Bweyogerere', 'Safety Officer', 'Active', '2023-07-10', 2700000, '2023-07-10 05:30:00', '2025-07-15 07:15:00'),
(25, 1, 'Okot Geoffrey', 'geoffrey.okot@ugandatextiles.co.ug', '+256701345678', 'Lira', 'Training Coordinator', 'Terminated', '2023-07-15', 3000000, '2023-07-15 07:00:00', '2024-12-31 14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `work_orders`
--

CREATE TABLE `work_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('Planned','InProgress','Completed','Paused','Cancelled') NOT NULL DEFAULT 'Planned',
  `scheduled_start` datetime DEFAULT NULL,
  `scheduled_end` datetime DEFAULT NULL,
  `actual_start` datetime DEFAULT NULL,
  `actual_end` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `work_order_assignments`
--

CREATE TABLE `work_order_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_id` bigint(20) UNSIGNED NOT NULL,
  `workforce_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `assigned_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_of_materials`
--
ALTER TABLE `bill_of_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_of_materials_product_id_foreign` (`product_id`);

--
-- Indexes for table `bill_of_material_components`
--
ALTER TABLE `bill_of_material_components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_of_material_components_bom_id_foreign` (`bom_id`),
  ADD KEY `bill_of_material_components_raw_item_id_foreign` (`raw_item_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_sender_id_receiver_id_index` (`sender_id`,`receiver_id`),
  ADD KEY `chat_messages_receiver_id_is_read_index` (`receiver_id`,`is_read`),
  ADD KEY `chat_messages_created_at_index` (`created_at`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_orders_order_number_unique` (`order_number`),
  ADD KEY `customer_orders_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `customer_order_items`
--
ALTER TABLE `customer_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_order_items_customer_order_id_foreign` (`customer_order_id`),
  ADD KEY `customer_order_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `downtime_logs`
--
ALTER TABLE `downtime_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `downtime_logs_work_order_id_foreign` (`work_order_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_items_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_order_id_foreign` (`order_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manufacturers_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_wholesaler_id_foreign` (`wholesaler_id`),
  ADD KEY `orders_manufacturer_id_foreign` (`manufacturer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pending_users`
--
ALTER TABLE `pending_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pending_users_email_unique` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `price_negotiations`
--
ALTER TABLE `price_negotiations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_negotiations_supply_request_id_foreign` (`supply_request_id`);

--
-- Indexes for table `production_costs`
--
ALTER TABLE `production_costs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_costs_work_order_id_foreign` (`work_order_id`);

--
-- Indexes for table `production_schedules`
--
ALTER TABLE `production_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_schedules_work_order_id_foreign` (`work_order_id`);

--
-- Indexes for table `quality_checks`
--
ALTER TABLE `quality_checks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quality_checks_work_order_id_foreign` (`work_order_id`),
  ADD KEY `quality_checks_checked_by_foreign` (`checked_by`);

--
-- Indexes for table `supplied_items`
--
ALTER TABLE `supplied_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplied_items_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplied_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suppliers_user_id_foreign` (`user_id`);

--
-- Indexes for table `supply_requests`
--
ALTER TABLE `supply_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supply_requests_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supply_requests_item_id_foreign` (`item_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouses_manufacturer_id_foreign` (`manufacturer_id`);

--
-- Indexes for table `warehouse_workforce`
--
ALTER TABLE `warehouse_workforce`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_workforce_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `warehouse_workforce_workforce_id_foreign` (`workforce_id`);

--
-- Indexes for table `wholesalers`
--
ALTER TABLE `wholesalers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wholesalers_user_id_foreign` (`user_id`);

--
-- Indexes for table `workforces`
--
ALTER TABLE `workforces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `workforces_email_unique` (`email`),
  ADD KEY `workforces_manufacturer_id_foreign` (`manufacturer_id`);

--
-- Indexes for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_orders_product_id_foreign` (`product_id`);

--
-- Indexes for table `work_order_assignments`
--
ALTER TABLE `work_order_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_order_assignments_work_order_id_foreign` (`work_order_id`),
  ADD KEY `work_order_assignments_workforce_id_foreign` (`workforce_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_of_materials`
--
ALTER TABLE `bill_of_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_of_material_components`
--
ALTER TABLE `bill_of_material_components`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `customer_order_items`
--
ALTER TABLE `customer_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `downtime_logs`
--
ALTER TABLE `downtime_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `pending_users`
--
ALTER TABLE `pending_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_negotiations`
--
ALTER TABLE `price_negotiations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_costs`
--
ALTER TABLE `production_costs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_schedules`
--
ALTER TABLE `production_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quality_checks`
--
ALTER TABLE `quality_checks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplied_items`
--
ALTER TABLE `supplied_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `supply_requests`
--
ALTER TABLE `supply_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `warehouse_workforce`
--
ALTER TABLE `warehouse_workforce`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `wholesalers`
--
ALTER TABLE `wholesalers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `workforces`
--
ALTER TABLE `workforces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `work_orders`
--
ALTER TABLE `work_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_order_assignments`
--
ALTER TABLE `work_order_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill_of_materials`
--
ALTER TABLE `bill_of_materials`
  ADD CONSTRAINT `bill_of_materials_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bill_of_material_components`
--
ALTER TABLE `bill_of_material_components`
  ADD CONSTRAINT `bill_of_material_components_bom_id_foreign` FOREIGN KEY (`bom_id`) REFERENCES `bill_of_materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_of_material_components_raw_item_id_foreign` FOREIGN KEY (`raw_item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD CONSTRAINT `customer_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_order_items`
--
ALTER TABLE `customer_order_items`
  ADD CONSTRAINT `customer_order_items_customer_order_id_foreign` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `downtime_logs`
--
ALTER TABLE `downtime_logs`
  ADD CONSTRAINT `downtime_logs_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD CONSTRAINT `inventory_items_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD CONSTRAINT `manufacturers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_manufacturer_id_foreign` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_wholesaler_id_foreign` FOREIGN KEY (`wholesaler_id`) REFERENCES `wholesalers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `price_negotiations`
--
ALTER TABLE `price_negotiations`
  ADD CONSTRAINT `price_negotiations_supply_request_id_foreign` FOREIGN KEY (`supply_request_id`) REFERENCES `supply_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_costs`
--
ALTER TABLE `production_costs`
  ADD CONSTRAINT `production_costs_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_schedules`
--
ALTER TABLE `production_schedules`
  ADD CONSTRAINT `production_schedules_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quality_checks`
--
ALTER TABLE `quality_checks`
  ADD CONSTRAINT `quality_checks_checked_by_foreign` FOREIGN KEY (`checked_by`) REFERENCES `workforces` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quality_checks_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplied_items`
--
ALTER TABLE `supplied_items`
  ADD CONSTRAINT `supplied_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplied_items_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supply_requests`
--
ALTER TABLE `supply_requests`
  ADD CONSTRAINT `supply_requests_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supply_requests_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `warehouses_manufacturer_id_foreign` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouse_workforce`
--
ALTER TABLE `warehouse_workforce`
  ADD CONSTRAINT `warehouse_workforce_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warehouse_workforce_workforce_id_foreign` FOREIGN KEY (`workforce_id`) REFERENCES `workforces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wholesalers`
--
ALTER TABLE `wholesalers`
  ADD CONSTRAINT `wholesalers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workforces`
--
ALTER TABLE `workforces`
  ADD CONSTRAINT `workforces_manufacturer_id_foreign` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD CONSTRAINT `work_orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_order_assignments`
--
ALTER TABLE `work_order_assignments`
  ADD CONSTRAINT `work_order_assignments_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_order_assignments_workforce_id_foreign` FOREIGN KEY (`workforce_id`) REFERENCES `workforces` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
