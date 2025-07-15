-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2025 at 12:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mukisa`
--

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
(1, 1, 'T-shirt', 'finished_product', 'Basic cotton t-shirt for daily wear', 'Clothing', 'Cotton', 8.50, 'XS,S,M,L,XL,XXL', 'White,Black,Blue,Red,Green,Yellow', 150, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(2, 1, 'Polo Shirt', 'finished_product', 'Casual polo shirt with collar', 'Clothing', 'Cotton Blend', 12.00, 'S,M,L,XL,XXL', 'Navy,White,Grey,Black,Blue', 100, 'https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(3, 1, 'Dress Shirt', 'finished_product', 'Formal dress shirt for business', 'Clothing', 'Cotton', 15.00, 'S,M,L,XL,XXL', 'White,Blue,Light Blue,Striped', 80, 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(4, 2, 'Casual Shirt', 'finished_product', 'Everyday casual shirt', 'Clothing', 'Cotton', 11.00, 'S,M,L,XL,XXL', 'Blue,Green,Brown,Grey,Checked', 120, 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(5, 2, 'Jeans', 'finished_product', 'Denim jeans for casual wear', 'Clothing', 'Denim', 25.00, '28,30,32,34,36,38,40', 'Blue,Black,Grey,Dark Blue', 90, 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(6, 2, 'Khaki Pants', 'finished_product', 'Casual khaki trousers', 'Clothing', 'Cotton Twill', 18.00, '28,30,32,34,36,38,40', 'Khaki,Navy,Black,Brown', 75, 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(7, 3, 'Skirt', 'finished_product', 'Casual knee-length skirt', 'Clothing', 'Cotton', 14.00, 'XS,S,M,L,XL', 'Black,Navy,Grey,Brown,Floral', 85, 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(8, 3, 'Blazer', 'finished_product', 'Professional blazer jacket', 'Clothing', 'Wool Blend', 45.00, 'S,M,L,XL,XXL', 'Navy,Black,Grey,Brown', 40, 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(9, 4, 'Cardigan', 'finished_product', 'Knitted cardigan sweater', 'Clothing', 'Wool', 22.00, 'S,M,L,XL,XXL', 'Grey,Navy,Black,Brown,Cream', 65, 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(10, 4, 'Hoodie', 'finished_product', 'Casual hooded sweatshirt', 'Clothing', 'Cotton Blend', 20.00, 'S,M,L,XL,XXL', 'Grey,Black,Navy,Red,Green', 95, 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(11, 5, 'School Uniform Shirt', 'finished_product', 'Standard school uniform shirt', 'Uniforms', 'Cotton', 9.50, 'S,M,L,XL', 'White,Light Blue', 200, 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(12, 5, 'Work Uniform', 'finished_product', 'Professional work uniform', 'Uniforms', 'Cotton Blend', 16.00, 'S,M,L,XL,XXL', 'Navy,Grey,Khaki', 120, 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(13, 1, 'Cotton Fabric', 'raw_material', 'High quality cotton fabric for clothing production', 'Raw Material', 'Cotton', 3.50, 'Per Yard', 'White,Natural,Bleached', 500, 'https://images.unsplash.com/photo-1586769852836-bc069f19e1b6?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(14, 1, 'Denim Fabric', 'raw_material', 'Premium denim fabric for jeans production', 'Raw Material', 'Cotton Denim', 4.25, 'Per Yard', 'Blue,Black,Grey', 300, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(15, 2, 'Wool Fabric', 'raw_material', 'Premium wool fabric for blazers and cardigans', 'Raw Material', 'Wool', 8.00, 'Per Yard', 'Grey,Navy,Black,Brown', 200, 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(16, 2, 'Cotton Blend Fabric', 'raw_material', 'Cotton polyester blend fabric', 'Raw Material', 'Cotton Blend', 3.75, 'Per Yard', 'Various Colors', 400, 'https://images.unsplash.com/photo-1586769852836-bc069f19e1b6?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(17, 3, 'Polyester Thread', 'raw_material', 'Strong polyester thread for stitching', 'Raw Material', 'Polyester', 0.75, 'Per Spool', 'Various Colors', 1000, 'https://images.unsplash.com/photo-1558618666-fbd6c4cd3d0b?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(18, 3, 'Cotton Thread', 'raw_material', 'Natural cotton thread for garments', 'Raw Material', 'Cotton', 0.65, 'Per Spool', 'Various Colors', 800, 'https://images.unsplash.com/photo-1558618666-fbd6c4cd3d0b?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(19, 4, 'Zippers', 'raw_material', 'Metal and plastic zippers for garments', 'Raw Material', 'Metal/Plastic', 1.25, 'Various Sizes', 'Black,Navy,Brown,Silver', 800, 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(20, 4, 'Buttons', 'raw_material', 'Plastic and metal buttons for shirts', 'Raw Material', 'Plastic/Metal', 0.15, 'Per Piece', 'White,Black,Brown,Clear', 5000, 'https://images.unsplash.com/photo-1607734834519-d8576ae60ea4?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(21, 5, 'Elastic Band', 'raw_material', 'Elastic band for waistbands and cuffs', 'Raw Material', 'Elastic', 2.50, 'Per Yard', 'Black,White,Navy', 300, 'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(22, 5, 'Labels', 'raw_material', 'Clothing labels and tags', 'Raw Material', 'Fabric/Paper', 0.05, 'Per Piece', 'White,Cream', 2000, 'https://images.unsplash.com/photo-1607734834519-d8576ae60ea4?w=400&h=400&fit=crop', 1, '2024-01-01 07:00:00', '2024-01-01 07:00:00');

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
(1, 1, 'Nakawa Industrial Area, Plot 15-17, Kampala, Uganda', '+256706789012', 'mfg_license_001.pdf', 'documents/mfg_license_001.pdf', 5000, '[\"Casual Wear\", \"Formal Wear\", \"Sportswear\", \"Business Attire\", \"School Uniforms\", \"Work Uniforms\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00');

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
(33, '2025_07_12_100000_add_warehouse_id_to_items_table', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `ml_demand_data`
-- (See below for the actual view)
--
CREATE TABLE `ml_demand_data` (
`product_name` varchar(255)
,`location` varchar(11)
,`sales_date` datetime
,`unit_price` decimal(10,2)
,`units_sold` int(11)
);

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
(1, 1, 1, 'ORD-2024-001', 'delivered', '2024-01-05 10:00:00', 1250.00, 'bank_transfer', 'Kampala', 'New year restocking order', '2024-01-15 10:00:00', '2024-01-14 14:30:00', '2024-01-05 07:00:00', '2024-01-14 11:30:00'),
(2, 2, 1, 'ORD-2024-002', 'delivered', '2024-01-08 14:30:00', 890.00, 'mobile money', 'Kampala', 'Formal wear for Q1', '2024-01-18 14:30:00', '2024-01-17 11:15:00', '2024-01-08 11:30:00', '2024-01-17 08:15:00'),
(3, 3, 1, 'ORD-2024-003', 'delivered', '2024-01-12 09:15:00', 1680.00, 'bank_transfer', 'Kampala', 'Bulk casual wear order', '2024-01-22 09:15:00', '2024-01-20 16:45:00', '2024-01-12 06:15:00', '2024-01-20 13:45:00'),
(4, 5, 1, 'ORD-2024-004', 'delivered', '2024-01-15 11:30:00', 1150.00, 'cash on delivery', 'Jinja', 'Regional distribution order', '2024-01-25 11:30:00', '2024-01-24 13:20:00', '2024-01-15 08:30:00', '2024-01-24 10:20:00'),
(5, 7, 1, 'ORD-2024-005', 'delivered', '2024-01-20 15:45:00', 975.00, 'mobile money', 'Gulu', 'Northern region restocking', '2024-01-30 15:45:00', '2024-01-29 10:30:00', '2024-01-20 12:45:00', '2024-01-29 07:30:00'),
(6, 4, 1, 'ORD-2024-006', 'delivered', '2024-02-02 12:00:00', 1420.00, 'bank_transfer', 'Kampala', 'Valentine season fashion', '2024-02-12 12:00:00', '2024-02-11 09:15:00', '2024-02-02 09:00:00', '2024-02-11 06:15:00'),
(7, 6, 1, 'ORD-2024-007', 'delivered', '2024-02-06 14:20:00', 850.00, 'mobile money', 'Mbarara', 'Work uniforms order', '2024-02-16 14:20:00', '2024-02-15 11:30:00', '2024-02-06 11:20:00', '2024-02-15 08:30:00'),
(8, 8, 1, 'ORD-2024-008', 'delivered', '2024-02-10 10:30:00', 1125.00, 'cash on delivery', 'Masaka', 'Mid-month restocking', '2024-02-20 10:30:00', '2024-02-19 16:45:00', '2024-02-10 07:30:00', '2024-02-19 13:45:00'),
(9, 10, 1, 'ORD-2024-009', 'delivered', '2024-02-14 16:15:00', 1650.00, 'bank_transfer', 'Mbale', 'Valentine special collection', '2024-02-24 16:15:00', '2024-02-23 14:20:00', '2024-02-14 13:15:00', '2024-02-23 11:20:00'),
(10, 13, 1, 'ORD-2024-010', 'delivered', '2024-02-18 13:45:00', 960.00, 'mobile money', 'Arua', 'Border trade shipment', '2024-02-28 13:45:00', '2024-02-27 11:00:00', '2024-02-18 10:45:00', '2024-02-27 08:00:00'),
(11, 9, 1, 'ORD-2024-011', 'delivered', '2024-03-03 09:00:00', 735.00, 'cash on delivery', 'Lira', 'Spring season preparation', '2024-03-13 09:00:00', '2024-03-12 15:30:00', '2024-03-03 06:00:00', '2024-03-12 12:30:00'),
(12, 12, 1, 'ORD-2024-012', 'delivered', '2024-03-07 11:20:00', 580.00, 'mobile money', 'Soroti', 'Traditional wear mix', '2024-03-17 11:20:00', '2024-03-16 14:45:00', '2024-03-07 08:20:00', '2024-03-16 11:45:00'),
(13, 15, 1, 'ORD-2024-013', 'delivered', '2024-03-11 14:50:00', 1590.00, 'bank_transfer', 'Hoima', 'Industrial clothing order', '2024-03-21 14:50:00', '2024-03-20 10:15:00', '2024-03-11 11:50:00', '2024-03-20 07:15:00'),
(14, 17, 1, 'ORD-2024-014', 'delivered', '2024-03-15 16:30:00', 1045.00, 'cash on delivery', 'Mityana', 'Everyday clothing restock', '2024-03-25 16:30:00', '2024-03-24 12:45:00', '2024-03-15 13:30:00', '2024-03-24 09:45:00'),
(15, 19, 1, 'ORD-2024-015', 'delivered', '2024-03-20 12:15:00', 895.00, 'mobile money', 'Entebbe', 'Travel wear collection', '2024-03-30 12:15:00', '2024-03-29 16:20:00', '2024-03-20 09:15:00', '2024-03-29 13:20:00'),
(16, 1, 1, 'ORD-2024-016', 'delivered', '2024-04-01 10:45:00', 1765.00, 'bank_transfer', 'Kampala', 'Easter holiday collection', '2024-04-11 10:45:00', '2024-04-10 14:30:00', '2024-04-01 07:45:00', '2024-04-10 11:30:00'),
(17, 3, 1, 'ORD-2024-017', 'delivered', '2024-04-05 15:20:00', 2135.00, 'cash on delivery', 'Kampala', 'Holiday season bulk order', '2024-04-15 15:20:00', '2024-04-14 11:45:00', '2024-04-05 12:20:00', '2024-04-14 08:45:00'),
(18, 5, 1, 'ORD-2024-018', 'delivered', '2024-04-08 13:30:00', 1225.00, 'mobile money', 'Jinja', 'Easter formal wear', '2024-04-18 13:30:00', '2024-04-17 09:15:00', '2024-04-08 10:30:00', '2024-04-17 06:15:00'),
(19, 2, 1, 'ORD-2024-019', 'delivered', '2024-04-12 11:00:00', 1485.00, 'bank_transfer', 'Kampala', 'Business attire collection', '2024-04-22 11:00:00', '2024-04-21 15:30:00', '2024-04-12 08:00:00', '2024-04-21 12:30:00'),
(20, 4, 1, 'ORD-2024-020', 'delivered', '2024-04-16 14:15:00', 1995.00, 'cash on delivery', 'Kampala', 'Premium fashion line', '2024-04-26 14:15:00', '2024-04-25 12:20:00', '2024-04-16 11:15:00', '2024-04-25 09:20:00'),
(21, 11, 1, 'ORD-2024-021', 'delivered', '2024-05-02 09:30:00', 685.00, 'mobile money', 'Kasese', 'Basic wear restocking', '2024-05-12 09:30:00', '2024-05-11 16:45:00', '2024-05-02 06:30:00', '2024-05-11 13:45:00'),
(22, 14, 1, 'ORD-2024-022', 'delivered', '2024-05-06 16:45:00', 1145.00, 'bank_transfer', 'Fort Portal', 'Formal business clothing', '2024-05-16 16:45:00', '2024-05-15 13:10:00', '2024-05-06 13:45:00', '2024-05-15 10:10:00'),
(23, 16, 1, 'ORD-2024-023', 'delivered', '2024-05-10 12:20:00', 845.00, 'cash on delivery', 'Kabale', 'Warm clothing collection', '2024-05-20 12:20:00', '2024-05-19 10:30:00', '2024-05-10 09:20:00', '2024-05-19 07:30:00'),
(24, 18, 1, 'ORD-2024-024', 'delivered', '2024-05-14 14:50:00', 1175.00, 'mobile money', 'Mukono', 'Student fashion collection', '2024-05-24 14:50:00', '2024-05-23 11:15:00', '2024-05-14 11:50:00', '2024-05-23 08:15:00'),
(25, 20, 1, 'ORD-2024-025', 'delivered', '2024-05-18 10:15:00', 1825.00, 'bank_transfer', 'Wakiso', 'Mixed categories order', '2024-05-28 10:15:00', '2024-05-27 14:40:00', '2024-05-18 07:15:00', '2024-05-27 11:40:00'),
(26, 6, 1, 'ORD-2024-026', 'delivered', '2024-06-01 11:30:00', 1320.00, 'mobile money', 'Mbarara', 'Mid-year uniform order', '2024-06-11 11:30:00', '2024-06-10 15:20:00', '2024-06-01 08:30:00', '2024-06-10 12:20:00'),
(27, 8, 1, 'ORD-2024-027', 'delivered', '2024-06-05 15:45:00', 965.00, 'cash on delivery', 'Masaka', 'Summer casual collection', '2024-06-15 15:45:00', '2024-06-14 12:30:00', '2024-06-05 12:45:00', '2024-06-14 09:30:00'),
(28, 10, 1, 'ORD-2024-028', 'delivered', '2024-06-10 13:20:00', 1450.00, 'bank_transfer', 'Mbale', 'Youth summer fashion', '2024-06-20 13:20:00', '2024-06-19 09:45:00', '2024-06-10 10:20:00', '2024-06-19 06:45:00'),
(29, 12, 1, 'ORD-2024-029', 'delivered', '2024-06-14 16:10:00', 725.00, 'mobile money', 'Soroti', 'Traditional summer wear', '2024-06-24 16:10:00', '2024-06-23 13:55:00', '2024-06-14 13:10:00', '2024-06-23 10:55:00'),
(30, 15, 1, 'ORD-2024-030', 'delivered', '2024-06-18 09:40:00', 1595.00, 'cash on delivery', 'Hoima', 'Work wear summer line', '2024-06-28 09:40:00', '2024-06-27 16:15:00', '2024-06-18 06:40:00', '2024-06-27 13:15:00'),
(31, 1, 1, 'ORD-2024-031', 'delivered', '2024-07-02 14:25:00', 2185.00, 'bank_transfer', 'Kampala', 'Summer peak season order', '2024-07-12 14:25:00', '2024-07-11 11:30:00', '2024-07-02 11:25:00', '2024-07-11 08:30:00'),
(32, 3, 1, 'ORD-2024-032', 'delivered', '2024-07-06 12:15:00', 1875.00, 'mobile money', 'Kampala', 'Bulk summer collection', '2024-07-16 12:15:00', '2024-07-15 14:45:00', '2024-07-06 09:15:00', '2024-07-15 11:45:00'),
(33, 7, 1, 'ORD-2024-033', 'delivered', '2024-07-10 10:50:00', 1125.00, 'cash on delivery', 'Gulu', 'Northern summer demand', '2024-07-20 10:50:00', '2024-07-19 16:20:00', '2024-07-10 07:50:00', '2024-07-19 13:20:00'),
(34, 9, 1, 'ORD-2024-034', 'delivered', '2024-07-14 16:35:00', 1650.00, 'bank_transfer', 'Lira', 'Work uniform summer order', '2024-07-24 16:35:00', '2024-07-23 13:10:00', '2024-07-14 13:35:00', '2024-07-23 10:10:00'),
(35, 13, 1, 'ORD-2024-035', 'delivered', '2024-07-18 11:20:00', 1395.00, 'mobile money', 'Arua', 'Cross-border summer trade', '2024-07-28 11:20:00', '2024-07-27 15:40:00', '2024-07-18 08:20:00', '2024-07-27 12:40:00'),
(36, 17, 1, 'ORD-2024-036', 'delivered', '2024-08-01 13:45:00', 945.00, 'cash on delivery', 'Mityana', 'Back to school basics', '2024-08-11 13:45:00', '2024-08-10 10:15:00', '2024-08-01 10:45:00', '2024-08-10 07:15:00'),
(37, 18, 1, 'ORD-2024-037', 'delivered', '2024-08-05 15:30:00', 1685.00, 'bank_transfer', 'Mukono', 'Student clothing collection', '2024-08-15 15:30:00', '2024-08-14 12:45:00', '2024-08-05 12:30:00', '2024-08-14 09:45:00'),
(38, 2, 1, 'ORD-2024-038', 'delivered', '2024-08-08 09:15:00', 1245.00, 'mobile money', 'Kampala', 'School formal uniforms', '2024-08-18 09:15:00', '2024-08-17 14:30:00', '2024-08-08 06:15:00', '2024-08-17 11:30:00'),
(39, 4, 1, 'ORD-2024-039', 'delivered', '2024-08-12 14:40:00', 1565.00, 'cash on delivery', 'Kampala', 'Premium school attire', '2024-08-22 14:40:00', '2024-08-21 11:25:00', '2024-08-12 11:40:00', '2024-08-21 08:25:00'),
(40, 5, 1, 'ORD-2024-040', 'delivered', '2024-08-16 11:55:00', 1385.00, 'bank_transfer', 'Jinja', 'Regional school supplies', '2024-08-26 11:55:00', '2024-08-25 16:10:00', '2024-08-16 08:55:00', '2024-08-25 13:10:00'),
(41, 11, 1, 'ORD-2024-041', 'delivered', '2024-09-02 16:20:00', 825.00, 'mobile money', 'Kasese', 'Autumn transition wear', '2024-09-12 16:20:00', '2024-09-11 13:35:00', '2024-09-02 13:20:00', '2024-09-11 10:35:00'),
(42, 14, 1, 'ORD-2024-042', 'delivered', '2024-09-06 12:10:00', 1195.00, 'cash on delivery', 'Fort Portal', 'Business quarter clothing', '2024-09-16 12:10:00', '2024-09-15 09:50:00', '2024-09-06 09:10:00', '2024-09-15 06:50:00'),
(43, 16, 1, 'ORD-2024-043', 'delivered', '2024-09-10 14:35:00', 965.00, 'bank_transfer', 'Kabale', 'Early winter preparation', '2024-09-20 14:35:00', '2024-09-19 11:20:00', '2024-09-10 11:35:00', '2024-09-19 08:20:00'),
(44, 19, 1, 'ORD-2024-044', 'delivered', '2024-09-14 10:25:00', 1425.00, 'mobile money', 'Entebbe', 'Travel comfort collection', '2024-09-24 10:25:00', '2024-09-23 15:45:00', '2024-09-14 07:25:00', '2024-09-23 12:45:00'),
(45, 20, 1, 'ORD-2024-045', 'delivered', '2024-09-18 16:50:00', 1945.00, 'cash on delivery', 'Wakiso', 'Comprehensive fall order', '2024-09-28 16:50:00', '2024-09-27 14:15:00', '2024-09-18 13:50:00', '2024-09-27 11:15:00'),
(46, 6, 1, 'ORD-2024-046', 'delivered', '2024-10-01 13:15:00', 1285.00, 'bank_transfer', 'Mbarara', 'Corporate uniform refresh', '2024-10-11 13:15:00', '2024-10-10 16:40:00', '2024-10-01 10:15:00', '2024-10-10 13:40:00'),
(47, 8, 1, 'ORD-2024-047', 'delivered', '2024-10-05 11:40:00', 1075.00, 'mobile money', 'Masaka', 'Pre-holiday stocking', '2024-10-15 11:40:00', '2024-10-14 14:25:00', '2024-10-05 08:40:00', '2024-10-14 11:25:00'),
(48, 10, 1, 'ORD-2024-048', 'delivered', '2024-10-09 15:05:00', 1535.00, 'cash on delivery', 'Mbale', 'Youth autumn collection', '2024-10-19 15:05:00', '2024-10-18 12:30:00', '2024-10-09 12:05:00', '2024-10-18 09:30:00'),
(49, 12, 1, 'ORD-2024-049', 'delivered', '2024-10-13 09:30:00', 785.00, 'bank_transfer', 'Soroti', 'Traditional fall wear', '2024-10-23 09:30:00', '2024-10-22 11:15:00', '2024-10-13 06:30:00', '2024-10-22 08:15:00'),
(50, 15, 1, 'ORD-2024-050', 'delivered', '2024-10-17 14:20:00', 1645.00, 'mobile money', 'Hoima', 'Industrial winter prep', '2024-10-27 14:20:00', '2024-10-26 16:50:00', '2024-10-17 11:20:00', '2024-10-26 13:50:00'),
(51, 1, 1, 'ORD-2024-051', 'delivered', '2024-11-01 12:45:00', 2345.00, 'cash on delivery', 'Kampala', 'Holiday season preparation', '2024-11-11 12:45:00', '2024-11-10 15:20:00', '2024-11-01 09:45:00', '2024-11-10 12:20:00'),
(52, 3, 1, 'ORD-2024-052', 'delivered', '2024-11-05 16:10:00', 2125.00, 'bank_transfer', 'Kampala', 'Christmas collection prep', '2024-11-15 16:10:00', '2024-11-14 13:45:00', '2024-11-05 13:10:00', '2024-11-14 10:45:00'),
(53, 7, 1, 'ORD-2024-053', 'delivered', '2024-11-08 10:35:00', 1385.00, 'mobile money', 'Gulu', 'Northern holiday demand', '2024-11-18 10:35:00', '2024-11-17 12:10:00', '2024-11-08 07:35:00', '2024-11-17 09:10:00'),
(54, 9, 1, 'ORD-2024-054', 'delivered', '2024-11-12 14:55:00', 1785.00, 'cash on delivery', 'Lira', 'Holiday work uniforms', '2024-11-22 14:55:00', '2024-11-21 16:25:00', '2024-11-12 11:55:00', '2024-11-21 13:25:00'),
(55, 13, 1, 'ORD-2024-055', 'delivered', '2024-11-16 11:20:00', 1565.00, 'bank_transfer', 'Arua', 'Cross-border holiday trade', '2024-11-26 11:20:00', '2024-11-25 14:40:00', '2024-11-16 08:20:00', '2024-11-25 11:40:00'),
(56, 2, 1, 'ORD-2024-056', 'delivered', '2024-12-01 15:30:00', 2685.00, 'mobile money', 'Kampala', 'Christmas formal collection', '2024-12-11 15:30:00', '2024-12-10 11:15:00', '2024-12-01 12:30:00', '2024-12-10 08:15:00'),
(57, 4, 1, 'ORD-2024-057', 'delivered', '2024-12-05 13:15:00', 2485.00, 'cash on delivery', 'Kampala', 'Premium holiday fashion', '2024-12-15 13:15:00', '2024-12-14 16:30:00', '2024-12-05 10:15:00', '2024-12-14 13:30:00'),
(58, 5, 1, 'ORD-2024-058', 'delivered', '2024-12-08 09:45:00', 1945.00, 'bank_transfer', 'Jinja', 'Regional holiday shipment', '2024-12-18 09:45:00', '2024-12-17 13:20:00', '2024-12-08 06:45:00', '2024-12-17 10:20:00'),
(59, 17, 1, 'ORD-2024-059', 'delivered', '2024-12-12 16:25:00', 1285.00, 'mobile money', 'Mityana', 'Holiday casual wear', '2024-12-22 16:25:00', '2024-12-21 10:50:00', '2024-12-12 13:25:00', '2024-12-21 07:50:00'),
(60, 20, 1, 'ORD-2024-060', 'delivered', '2024-12-16 12:40:00', 2785.00, 'cash on delivery', 'Wakiso', 'Massive holiday order', '2024-12-26 12:40:00', '2024-12-24 15:15:00', '2024-12-16 09:40:00', '2024-12-24 12:15:00'),
(61, 11, 1, 'ORD-2025-001', 'delivered', '2025-01-03 14:20:00', 965.00, 'bank_transfer', 'Kasese', 'New year basic restocking', '2025-01-13 14:20:00', '2025-01-12 16:45:00', '2025-01-03 11:20:00', '2025-01-12 13:45:00'),
(62, 14, 1, 'ORD-2025-002', 'delivered', '2025-01-07 11:35:00', 1385.00, 'mobile money', 'Fort Portal', 'Q1 business attire', '2025-01-17 11:35:00', '2025-01-16 14:10:00', '2025-01-07 08:35:00', '2025-01-16 11:10:00'),
(63, 16, 1, 'ORD-2025-003', 'delivered', '2025-01-10 16:50:00', 875.00, 'cash on delivery', 'Kabale', 'Winter clearance order', '2025-01-20 16:50:00', '2025-01-19 12:25:00', '2025-01-10 13:50:00', '2025-01-19 09:25:00'),
(64, 18, 1, 'ORD-2025-004', 'delivered', '2025-01-14 13:15:00', 1245.00, 'bank_transfer', 'Mukono', 'Student new term wear', '2025-01-24 13:15:00', '2025-01-23 15:40:00', '2025-01-14 10:15:00', '2025-01-23 12:40:00'),
(65, 19, 1, 'ORD-2025-005', 'delivered', '2025-01-18 10:30:00', 1565.00, 'mobile money', 'Entebbe', 'Travel wear new year', '2025-01-28 10:30:00', '2025-01-27 13:55:00', '2025-01-18 07:30:00', '2025-01-27 10:55:00'),
(66, 6, 1, 'ORD-2025-006', 'delivered', '2025-02-01 15:45:00', 1425.00, 'cash on delivery', 'Mbarara', 'Valentine corporate wear', '2025-02-11 15:45:00', '2025-02-10 11:20:00', '2025-02-01 12:45:00', '2025-02-10 08:20:00'),
(67, 8, 1, 'ORD-2025-007', 'delivered', '2025-02-05 12:20:00', 1185.00, 'bank_transfer', 'Masaka', 'Love season casual wear', '2025-02-15 12:20:00', '2025-02-14 16:35:00', '2025-02-05 09:20:00', '2025-02-14 13:35:00'),
(68, 10, 1, 'ORD-2025-008', 'delivered', '2025-02-08 14:40:00', 1685.00, 'mobile money', 'Mbale', 'Valentine youth collection', '2025-02-18 14:40:00', '2025-02-17 10:15:00', '2025-02-08 11:40:00', '2025-02-17 07:15:00'),
(69, 12, 1, 'ORD-2025-009', 'delivered', '2025-02-12 09:55:00', 945.00, 'cash on delivery', 'Soroti', 'Traditional valentine wear', '2025-02-22 09:55:00', '2025-02-21 14:30:00', '2025-02-12 06:55:00', '2025-02-21 11:30:00'),
(70, 15, 1, 'ORD-2025-010', 'delivered', '2025-02-16 16:25:00', 1745.00, 'bank_transfer', 'Hoima', 'Work valentine special', '2025-02-26 16:25:00', '2025-02-25 12:50:00', '2025-02-16 13:25:00', '2025-02-25 09:50:00'),
(71, 1, 1, 'ORD-2025-011', 'delivered', '2025-03-01 11:10:00', 2085.00, 'mobile money', 'Kampala', 'Spring collection launch', '2025-03-11 11:10:00', '2025-03-10 15:25:00', '2025-03-01 08:10:00', '2025-03-10 12:25:00'),
(72, 3, 1, 'ORD-2025-012', 'delivered', '2025-03-05 13:35:00', 1885.00, 'cash on delivery', 'Kampala', 'Bulk spring restocking', '2025-03-15 13:35:00', '2025-03-14 17:00:00', '2025-03-05 10:35:00', '2025-03-14 14:00:00'),
(73, 7, 1, 'ORD-2025-013', 'delivered', '2025-03-08 15:50:00', 1325.00, 'bank_transfer', 'Gulu', 'Northern spring demand', '2025-03-18 15:50:00', '2025-03-17 13:15:00', '2025-03-08 12:50:00', '2025-03-17 10:15:00'),
(74, 9, 1, 'ORD-2025-014', 'delivered', '2025-03-12 10:15:00', 1565.00, 'mobile money', 'Lira', 'Spring work uniforms', '2025-03-22 10:15:00', '2025-03-21 14:40:00', '2025-03-12 07:15:00', '2025-03-21 11:40:00'),
(75, 13, 1, 'ORD-2025-015', 'delivered', '2025-03-16 14:30:00', 1445.00, 'cash on delivery', 'Arua', 'Spring border trade', '2025-03-26 14:30:00', '2025-03-25 11:55:00', '2025-03-16 11:30:00', '2025-03-25 08:55:00'),
(76, 2, 1, 'ORD-2025-016', 'delivered', '2025-04-01 12:00:00', 2485.00, 'bank_transfer', 'Kampala', 'Easter formal collection', '2025-04-11 12:00:00', '2025-04-10 16:25:00', '2025-04-01 09:00:00', '2025-04-10 13:25:00'),
(77, 4, 1, 'ORD-2025-017', 'delivered', '2025-04-05 16:45:00', 2785.00, 'mobile money', 'Kampala', 'Premium Easter fashion', '2025-04-15 16:45:00', '2025-04-14 12:10:00', '2025-04-05 13:45:00', '2025-04-14 09:10:00'),
(78, 5, 1, 'ORD-2025-018', 'delivered', '2025-04-08 11:20:00', 2185.00, 'cash on delivery', 'Jinja', 'Regional Easter shipment', '2025-04-18 11:20:00', '2025-04-17 15:35:00', '2025-04-08 08:20:00', '2025-04-17 12:35:00'),
(79, 17, 1, 'ORD-2025-019', 'delivered', '2025-04-12 14:55:00', 1685.00, 'bank_transfer', 'Mityana', 'Easter casual collection', '2025-04-22 14:55:00', '2025-04-21 13:20:00', '2025-04-12 11:55:00', '2025-04-21 10:20:00'),
(80, 20, 1, 'ORD-2025-020', 'delivered', '2025-04-16 09:30:00', 2985.00, 'mobile money', 'Wakiso', 'Massive Easter order', '2025-04-26 09:30:00', '2025-04-25 14:45:00', '2025-04-16 06:30:00', '2025-04-25 11:45:00'),
(81, 11, 1, 'ORD-2025-021', 'delivered', '2025-05-01 13:40:00', 1145.00, 'cash on delivery', 'Kasese', 'May basic restocking', '2025-05-11 13:40:00', '2025-05-10 17:05:00', '2025-05-01 10:40:00', '2025-05-10 14:05:00'),
(82, 14, 1, 'ORD-2025-022', 'delivered', '2025-05-05 16:15:00', 1485.00, 'bank_transfer', 'Fort Portal', 'Mid-year business wear', '2025-05-15 16:15:00', '2025-05-14 11:40:00', '2025-05-05 13:15:00', '2025-05-14 08:40:00'),
(83, 16, 1, 'ORD-2025-023', 'delivered', '2025-05-08 10:25:00', 1025.00, 'mobile money', 'Kabale', 'Spring to summer transition', '2025-05-18 10:25:00', '2025-05-17 15:50:00', '2025-05-08 07:25:00', '2025-05-17 12:50:00'),
(84, 18, 1, 'ORD-2025-024', 'delivered', '2025-05-12 15:35:00', 1365.00, 'cash on delivery', 'Mukono', 'Student summer prep', '2025-05-22 15:35:00', '2025-05-21 13:00:00', '2025-05-12 12:35:00', '2025-05-21 10:00:00'),
(85, 19, 1, 'ORD-2025-025', 'delivered', '2025-05-16 12:50:00', 1585.00, 'bank_transfer', 'Entebbe', 'Travel summer collection', '2025-05-26 12:50:00', '2025-05-25 16:15:00', '2025-05-16 09:50:00', '2025-05-25 13:15:00'),
(86, 6, 1, 'ORD-2025-026', 'delivered', '2025-06-01 14:05:00', 1665.00, 'mobile money', 'Mbarara', 'Summer uniform collection', '2025-06-11 14:05:00', '2025-06-10 12:30:00', '2025-06-01 11:05:00', '2025-06-10 09:30:00'),
(87, 8, 1, 'ORD-2025-027', 'delivered', '2025-06-05 11:30:00', 1285.00, 'cash on delivery', 'Masaka', 'Summer casual peak', '2025-06-15 11:30:00', '2025-06-14 15:45:00', '2025-06-05 08:30:00', '2025-06-14 12:45:00'),
(88, 10, 1, 'ORD-2025-028', 'delivered', '2025-06-08 16:20:00', 1785.00, 'bank_transfer', 'Mbale', 'Youth summer peak', '2025-06-18 16:20:00', '2025-06-17 14:55:00', '2025-06-08 13:20:00', '2025-06-17 11:55:00'),
(89, 12, 1, 'ORD-2025-029', 'shipped', '2025-06-12 13:15:00', 1125.00, 'mobile money', 'Soroti', 'Summer traditional wear', '2025-06-22 13:15:00', NULL, '2025-06-12 10:15:00', '2025-06-18 07:20:00'),
(90, 15, 1, 'ORD-2025-030', 'shipped', '2025-06-16 10:40:00', 1845.00, 'cash on delivery', 'Hoima', 'Industrial summer wear', '2025-06-26 10:40:00', NULL, '2025-06-16 07:40:00', '2025-06-20 11:35:00'),
(91, 1, 1, 'ORD-2025-031', 'in_production', '2025-06-20 15:25:00', 2285.00, 'bank_transfer', 'Kampala', 'Mid-year major restocking', '2025-06-30 15:25:00', NULL, '2025-06-20 12:25:00', '2025-06-25 06:15:00'),
(92, 3, 1, 'ORD-2025-032', 'in_production', '2025-06-24 12:10:00', 2085.00, 'mobile money', 'Kampala', 'Summer peak demand', '2025-07-04 12:10:00', NULL, '2025-06-24 09:10:00', '2025-06-28 13:40:00'),
(93, 7, 1, 'ORD-2025-033', 'in_production', '2025-06-28 14:35:00', 1565.00, 'cash on delivery', 'Gulu', 'Northern summer collection', '2025-07-08 14:35:00', NULL, '2025-06-28 11:35:00', '2025-07-02 08:50:00'),
(94, 9, 1, 'ORD-2025-034', 'confirmed', '2025-07-01 11:50:00', 1745.00, 'bank_transfer', 'Lira', 'July work wear order', '2025-07-11 11:50:00', NULL, '2025-07-01 08:50:00', '2025-07-05 10:25:00'),
(95, 13, 1, 'ORD-2025-035', 'confirmed', '2025-07-03 16:05:00', 1625.00, 'mobile money', 'Arua', 'Cross-border summer trade', '2025-07-13 16:05:00', NULL, '2025-07-03 13:05:00', '2025-07-07 07:30:00'),
(96, 2, 1, 'ORD-2025-036', 'confirmed', '2025-07-06 13:20:00', 2385.00, 'cash on delivery', 'Kampala', 'Premium summer formal', '2025-07-16 13:20:00', NULL, '2025-07-06 10:20:00', '2025-07-09 12:45:00'),
(97, 4, 1, 'ORD-2025-037', 'pending', '2025-07-08 10:15:00', 2585.00, 'bank_transfer', 'Kampala', 'High-end summer collection', '2025-07-18 10:15:00', NULL, '2025-07-08 07:15:00', '2025-07-08 07:15:00'),
(98, 5, 1, 'ORD-2025-038', 'pending', '2025-07-09 14:40:00', 2185.00, 'mobile money', 'Jinja', 'Regional summer demand', '2025-07-19 14:40:00', NULL, '2025-07-09 11:40:00', '2025-07-09 11:40:00'),
(99, 17, 1, 'ORD-2025-039', 'pending', '2025-07-10 12:25:00', 1485.00, 'cash on delivery', 'Mityana', 'Summer basics order', '2025-07-20 12:25:00', NULL, '2025-07-10 09:25:00', '2025-07-10 09:25:00'),
(100, 20, 1, 'ORD-2025-040', 'pending', '2025-07-11 16:30:00', 2885.00, 'bank_transfer', 'Wakiso', 'Massive summer order', '2025-07-21 16:30:00', NULL, '2025-07-11 13:30:00', '2025-07-11 13:30:00');

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
(1, 1, 1, 50, 8.50, 425.00, 'T-shirts - mixed colors and sizes', '2024-01-05 07:00:00', '2024-01-05 07:00:00'),
(2, 1, 2, 30, 12.00, 360.00, 'Polo shirts - corporate colors', '2024-01-05 07:00:00', '2024-01-05 07:00:00'),
(3, 1, 7, 33, 14.00, 462.00, 'Skirts - various sizes', '2024-01-05 07:00:00', '2024-01-05 07:00:00'),
(4, 2, 3, 25, 15.00, 375.00, 'Formal dress shirts', '2024-01-08 11:30:00', '2024-01-08 11:30:00'),
(5, 2, 8, 10, 45.00, 450.00, 'Professional blazers', '2024-01-08 11:30:00', '2024-01-08 11:30:00'),
(6, 2, 6, 8, 18.00, 144.00, 'Khaki pants', '2024-01-08 11:30:00', '2024-01-08 11:30:00'),
(7, 3, 1, 80, 8.25, 660.00, 'Summer t-shirts bulk', '2024-01-12 06:15:00', '2024-01-12 06:15:00'),
(8, 3, 4, 45, 11.00, 495.00, 'Casual shirts', '2024-01-12 06:15:00', '2024-01-12 06:15:00'),
(9, 3, 10, 25, 20.00, 500.00, 'Hoodies for cool mornings', '2024-01-12 06:15:00', '2024-01-12 06:15:00'),
(10, 3, 2, 10, 12.50, 125.00, 'Polo shirts supplement', '2024-01-12 06:15:00', '2024-01-12 06:15:00'),
(11, 4, 4, 35, 11.00, 385.00, 'Regional casual shirts', '2024-01-15 08:30:00', '2024-01-15 08:30:00'),
(12, 4, 6, 25, 18.00, 450.00, 'Khaki pants for offices', '2024-01-15 08:30:00', '2024-01-15 08:30:00'),
(13, 4, 1, 37, 8.50, 314.50, 'Basic t-shirts', '2024-01-15 08:30:00', '2024-01-15 08:30:00'),
(14, 5, 1, 45, 8.00, 360.00, 'Northern region t-shirts', '2024-01-20 12:45:00', '2024-01-20 12:45:00'),
(15, 5, 4, 28, 11.00, 308.00, 'Casual shirts for Gulu market', '2024-01-20 12:45:00', '2024-01-20 12:45:00'),
(16, 5, 11, 32, 9.50, 304.00, 'School uniform shirts', '2024-01-20 12:45:00', '2024-01-20 12:45:00'),
(17, 6, 3, 40, 15.50, 620.00, 'Valentine formal shirts', '2024-02-02 09:00:00', '2024-02-02 09:00:00'),
(18, 6, 8, 15, 46.00, 690.00, 'Premium blazers for Valentine', '2024-02-02 09:00:00', '2024-02-02 09:00:00'),
(19, 6, 6, 6, 18.50, 111.00, 'Formal pants to match', '2024-02-02 09:00:00', '2024-02-02 09:00:00'),
(20, 7, 12, 35, 16.00, 560.00, 'Work uniforms for companies', '2024-02-06 11:20:00', '2024-02-06 11:20:00'),
(21, 7, 4, 15, 11.25, 168.75, 'Casual work shirts', '2024-02-06 11:20:00', '2024-02-06 11:20:00'),
(22, 7, 6, 7, 17.50, 122.50, 'Work pants', '2024-02-06 11:20:00', '2024-02-06 11:20:00'),
(23, 8, 1, 55, 8.25, 453.75, 'Mid-month t-shirt restock', '2024-02-10 07:30:00', '2024-02-10 07:30:00'),
(24, 8, 7, 30, 14.25, 427.50, 'Skirts for women', '2024-02-10 07:30:00', '2024-02-10 07:30:00'),
(25, 8, 2, 20, 12.25, 245.00, 'Polo shirts', '2024-02-10 07:30:00', '2024-02-10 07:30:00'),
(26, 9, 1, 65, 8.75, 568.75, 'Valentine youth t-shirts', '2024-02-14 13:15:00', '2024-02-14 13:15:00'),
(27, 9, 10, 35, 20.50, 717.50, 'Trendy hoodies for youth', '2024-02-14 13:15:00', '2024-02-14 13:15:00'),
(28, 9, 5, 14, 25.75, 360.50, 'Fashionable jeans', '2024-02-14 13:15:00', '2024-02-14 13:15:00'),
(29, 10, 1, 48, 8.00, 384.00, 'Border trade basics', '2024-02-18 10:45:00', '2024-02-18 10:45:00'),
(30, 10, 4, 32, 11.00, 352.00, 'Cross-border casual shirts', '2024-02-18 10:45:00', '2024-02-18 10:45:00'),
(31, 10, 6, 12, 18.50, 222.00, 'Quality pants for export', '2024-02-18 10:45:00', '2024-02-18 10:45:00'),
(32, 11, 12, 25, 16.25, 406.25, 'Spring work uniforms', '2024-03-03 06:00:00', '2024-03-03 06:00:00'),
(33, 11, 1, 28, 8.25, 231.00, 'Basic spring t-shirts', '2024-03-03 06:00:00', '2024-03-03 06:00:00'),
(34, 11, 4, 9, 10.75, 96.75, 'Casual spring shirts', '2024-03-03 06:00:00', '2024-03-03 06:00:00'),
(35, 12, 1, 35, 8.00, 280.00, 'Traditional market t-shirts', '2024-03-07 08:20:00', '2024-03-07 08:20:00'),
(36, 12, 4, 20, 11.25, 225.00, 'Traditional casual shirts', '2024-03-07 08:20:00', '2024-03-07 08:20:00'),
(37, 12, 7, 5, 15.00, 75.00, 'Traditional skirts', '2024-03-07 08:20:00', '2024-03-07 08:20:00'),
(38, 13, 12, 60, 16.50, 990.00, 'Industrial work uniforms', '2024-03-11 11:50:00', '2024-03-11 11:50:00'),
(39, 13, 6, 20, 18.75, 375.00, 'Industrial work pants', '2024-03-11 11:50:00', '2024-03-11 11:50:00'),
(40, 13, 4, 20, 11.25, 225.00, 'Casual work shirts', '2024-03-11 11:50:00', '2024-03-11 11:50:00'),
(41, 14, 1, 60, 8.50, 510.00, 'Everyday basic t-shirts', '2024-03-15 13:30:00', '2024-03-15 13:30:00'),
(42, 14, 4, 35, 11.00, 385.00, 'Everyday casual shirts', '2024-03-15 13:30:00', '2024-03-15 13:30:00'),
(43, 14, 6, 8, 18.75, 150.00, 'Everyday pants', '2024-03-15 13:30:00', '2024-03-15 13:30:00'),
(44, 15, 1, 45, 8.25, 371.25, 'Travel comfortable t-shirts', '2024-03-20 09:15:00', '2024-03-20 09:15:00'),
(45, 15, 4, 25, 11.50, 287.50, 'Travel casual shirts', '2024-03-20 09:15:00', '2024-03-20 09:15:00'),
(46, 15, 6, 13, 18.25, 236.25, 'Travel pants', '2024-03-20 09:15:00', '2024-03-20 09:15:00'),
(47, 16, 3, 50, 16.25, 812.50, 'Easter formal shirts', '2024-04-01 07:45:00', '2024-04-01 07:45:00'),
(48, 16, 8, 18, 47.50, 855.00, 'Easter premium blazers', '2024-04-01 07:45:00', '2024-04-01 07:45:00'),
(49, 16, 6, 5, 19.50, 97.50, 'Easter formal pants', '2024-04-01 07:45:00', '2024-04-01 07:45:00'),
(50, 17, 1, 100, 8.50, 850.00, 'Easter holiday bulk t-shirts', '2024-04-05 12:20:00', '2024-04-05 12:20:00'),
(51, 17, 4, 60, 11.75, 705.00, 'Holiday casual shirts', '2024-04-05 12:20:00', '2024-04-05 12:20:00'),
(52, 17, 7, 35, 14.50, 507.50, 'Holiday skirts', '2024-04-05 12:20:00', '2024-04-05 12:20:00'),
(53, 17, 2, 6, 12.25, 73.50, 'Holiday polo shirts', '2024-04-05 12:20:00', '2024-04-05 12:20:00'),
(54, 18, 3, 35, 15.75, 551.25, 'Easter regional formal shirts', '2024-04-08 10:30:00', '2024-04-08 10:30:00'),
(55, 18, 8, 12, 46.25, 555.00, 'Easter regional blazers', '2024-04-08 10:30:00', '2024-04-08 10:30:00'),
(56, 18, 6, 7, 17.00, 119.00, 'Easter formal pants', '2024-04-08 10:30:00', '2024-04-08 10:30:00'),
(57, 19, 3, 45, 16.00, 720.00, 'Business quarter formal shirts', '2024-04-12 08:00:00', '2024-04-12 08:00:00'),
(58, 19, 8, 15, 47.00, 705.00, 'Business quarter blazers', '2024-04-12 08:00:00', '2024-04-12 08:00:00'),
(59, 19, 6, 10, 18.00, 180.00, 'Business formal pants', '2024-04-12 08:00:00', '2024-04-12 08:00:00'),
(60, 20, 3, 55, 16.50, 907.50, 'Premium fashion formal shirts', '2024-04-16 11:15:00', '2024-04-16 11:15:00'),
(61, 20, 8, 20, 48.50, 970.00, 'Premium fashion blazers', '2024-04-16 11:15:00', '2024-04-16 11:15:00'),
(62, 20, 5, 4, 29.25, 117.00, 'Premium fashion jeans', '2024-04-16 11:15:00', '2024-04-16 11:15:00'),
(63, 21, 1, 40, 8.00, 320.00, 'Basic mountain region t-shirts', '2024-05-02 06:30:00', '2024-05-02 06:30:00'),
(64, 21, 4, 22, 11.00, 242.00, 'Mountain casual shirts', '2024-05-02 06:30:00', '2024-05-02 06:30:00'),
(65, 21, 12, 7, 17.50, 122.50, 'Mountain work uniforms', '2024-05-02 06:30:00', '2024-05-02 06:30:00'),
(66, 22, 3, 38, 15.25, 579.50, 'Fort Portal business shirts', '2024-05-06 13:45:00', '2024-05-06 13:45:00'),
(67, 22, 8, 11, 45.50, 500.50, 'Fort Portal business blazers', '2024-05-06 13:45:00', '2024-05-06 13:45:00'),
(68, 22, 6, 4, 16.25, 65.00, 'Fort Portal business pants', '2024-05-06 13:45:00', '2024-05-06 13:45:00'),
(69, 23, 9, 25, 22.75, 568.75, 'Kabale warm cardigans', '2024-05-10 09:20:00', '2024-05-10 09:20:00'),
(70, 23, 10, 8, 21.50, 172.00, 'Kabale warm hoodies', '2024-05-10 09:20:00', '2024-05-10 09:20:00'),
(71, 23, 1, 12, 8.75, 105.00, 'Kabale basic t-shirts', '2024-05-10 09:20:00', '2024-05-10 09:20:00'),
(72, 24, 1, 55, 8.50, 467.50, 'Student fashion t-shirts', '2024-05-14 11:50:00', '2024-05-14 11:50:00'),
(73, 24, 5, 16, 26.00, 416.00, 'Student fashion jeans', '2024-05-14 11:50:00', '2024-05-14 11:50:00'),
(74, 24, 10, 14, 20.75, 290.50, 'Student fashion hoodies', '2024-05-14 11:50:00', '2024-05-14 11:50:00'),
(75, 25, 1, 75, 8.75, 656.25, 'Wakiso mixed t-shirts', '2024-05-18 07:15:00', '2024-05-18 07:15:00'),
(76, 25, 4, 45, 11.50, 517.50, 'Wakiso mixed casual shirts', '2024-05-18 07:15:00', '2024-05-18 07:15:00'),
(77, 25, 7, 28, 14.75, 413.00, 'Wakiso mixed skirts', '2024-05-18 07:15:00', '2024-05-18 07:15:00'),
(78, 25, 2, 20, 12.00, 240.00, 'Wakiso mixed polo shirts', '2024-05-18 07:15:00', '2024-05-18 07:15:00'),
(79, 26, 12, 50, 16.75, 837.50, 'Mbarara mid-year uniforms', '2024-06-01 08:30:00', '2024-06-01 08:30:00'),
(80, 26, 6, 15, 18.50, 277.50, 'Mbarara uniform pants', '2024-06-01 08:30:00', '2024-06-01 08:30:00'),
(81, 26, 4, 18, 11.25, 202.50, 'Mbarara casual work shirts', '2024-06-01 08:30:00', '2024-06-01 08:30:00'),
(82, 27, 1, 58, 8.25, 478.50, 'Masaka summer casual t-shirts', '2024-06-05 12:45:00', '2024-06-05 12:45:00'),
(83, 27, 4, 28, 11.00, 308.00, 'Masaka summer casual shirts', '2024-06-05 12:45:00', '2024-06-05 12:45:00'),
(84, 27, 7, 12, 14.50, 174.00, 'Masaka summer skirts', '2024-06-05 12:45:00', '2024-06-05 12:45:00'),
(85, 28, 1, 65, 9.00, 585.00, 'Mbale youth summer t-shirts', '2024-06-10 10:20:00', '2024-06-10 10:20:00'),
(86, 28, 10, 25, 21.25, 531.25, 'Mbale youth summer hoodies', '2024-06-10 10:20:00', '2024-06-10 10:20:00'),
(87, 28, 5, 12, 27.75, 333.00, 'Mbale youth summer jeans', '2024-06-10 10:20:00', '2024-06-10 10:20:00'),
(88, 29, 1, 42, 8.00, 336.00, 'Soroti traditional summer t-shirts', '2024-06-14 13:10:00', '2024-06-14 13:10:00'),
(89, 29, 4, 25, 11.25, 281.25, 'Soroti traditional summer shirts', '2024-06-14 13:10:00', '2024-06-14 13:10:00'),
(90, 29, 7, 7, 15.25, 106.75, 'Soroti traditional summer skirts', '2024-06-14 13:10:00', '2024-06-14 13:10:00'),
(91, 30, 12, 65, 17.00, 1105.00, 'Hoima work wear summer line', '2024-06-18 06:40:00', '2024-06-18 06:40:00'),
(92, 30, 6, 18, 19.25, 346.50, 'Hoima work pants summer', '2024-06-18 06:40:00', '2024-06-18 06:40:00'),
(93, 30, 4, 12, 11.75, 141.00, 'Hoima casual work shirts', '2024-06-18 06:40:00', '2024-06-18 06:40:00'),
(94, 31, 1, 95, 9.25, 878.75, 'Summer peak t-shirts bulk', '2024-07-02 11:25:00', '2024-07-02 11:25:00'),
(95, 31, 4, 65, 12.00, 780.00, 'Summer peak casual shirts', '2024-07-02 11:25:00', '2024-07-02 11:25:00'),
(96, 31, 7, 35, 15.25, 533.75, 'Summer peak skirts', '2024-07-02 11:25:00', '2024-07-02 11:25:00'),
(97, 40, 11, 85, 9.75, 828.75, 'School uniform shirts bulk', '2024-08-16 08:55:00', '2024-08-16 08:55:00'),
(98, 40, 3, 25, 15.50, 387.50, 'School formal shirts', '2024-08-16 08:55:00', '2024-08-16 08:55:00'),
(99, 40, 6, 9, 18.75, 168.75, 'School formal pants', '2024-08-16 08:55:00', '2024-08-16 08:55:00'),
(100, 60, 1, 120, 9.50, 1140.00, 'Holiday massive t-shirt order', '2024-12-16 09:40:00', '2024-12-16 09:40:00'),
(101, 60, 3, 55, 17.25, 948.75, 'Holiday formal shirts', '2024-12-16 09:40:00', '2024-12-16 09:40:00'),
(102, 60, 8, 13, 51.00, 663.00, 'Holiday premium blazers', '2024-12-16 09:40:00', '2024-12-16 09:40:00'),
(103, 60, 7, 2, 16.75, 33.50, 'Holiday skirts sample', '2024-12-16 09:40:00', '2024-12-16 09:40:00'),
(104, 32, 1, 85, 8.75, 743.75, 'Owino summer bulk t-shirts', '2024-07-06 09:15:00', '2024-07-06 09:15:00'),
(105, 32, 4, 55, 11.50, 632.50, 'Owino summer casual shirts', '2024-07-06 09:15:00', '2024-07-06 09:15:00'),
(106, 32, 2, 40, 12.50, 500.00, 'Owino summer polo shirts', '2024-07-06 09:15:00', '2024-07-06 09:15:00'),
(107, 33, 1, 70, 8.25, 577.50, 'Gulu northern summer t-shirts', '2024-07-10 07:50:00', '2024-07-10 07:50:00'),
(108, 33, 4, 35, 11.00, 385.00, 'Gulu northern casual shirts', '2024-07-10 07:50:00', '2024-07-10 07:50:00'),
(109, 33, 7, 11, 14.75, 162.25, 'Gulu northern skirts', '2024-07-10 07:50:00', '2024-07-10 07:50:00'),
(110, 34, 12, 75, 16.50, 1237.50, 'Lira summer work uniforms', '2024-07-14 13:35:00', '2024-07-14 13:35:00'),
(111, 34, 6, 22, 18.75, 412.50, 'Lira work uniform pants', '2024-07-14 13:35:00', '2024-07-14 13:35:00'),
(112, 35, 1, 80, 8.50, 680.00, 'Arua cross-border t-shirts', '2024-07-18 08:20:00', '2024-07-18 08:20:00'),
(113, 35, 4, 42, 11.25, 472.50, 'Arua cross-border shirts', '2024-07-18 08:20:00', '2024-07-18 08:20:00'),
(114, 35, 6, 13, 18.50, 240.50, 'Arua cross-border pants', '2024-07-18 08:20:00', '2024-07-18 08:20:00'),
(115, 36, 11, 65, 9.50, 617.50, 'Mityana school uniform shirts', '2024-08-01 10:45:00', '2024-08-01 10:45:00'),
(116, 36, 1, 25, 8.25, 206.25, 'Mityana basic school t-shirts', '2024-08-01 10:45:00', '2024-08-01 10:45:00'),
(117, 36, 6, 7, 17.25, 120.75, 'Mityana school pants', '2024-08-01 10:45:00', '2024-08-01 10:45:00'),
(118, 37, 1, 75, 8.75, 656.25, 'Mukono student t-shirts', '2024-08-05 12:30:00', '2024-08-05 12:30:00'),
(119, 37, 5, 25, 26.50, 662.50, 'Mukono student jeans', '2024-08-05 12:30:00', '2024-08-05 12:30:00'),
(120, 37, 10, 18, 20.50, 369.00, 'Mukono student hoodies', '2024-08-05 12:30:00', '2024-08-05 12:30:00'),
(121, 38, 11, 85, 9.75, 828.75, 'Nakasero school formal shirts', '2024-08-08 06:15:00', '2024-08-08 06:15:00'),
(122, 38, 3, 22, 15.75, 346.50, 'Nakasero school dress shirts', '2024-08-08 06:15:00', '2024-08-08 06:15:00'),
(123, 38, 6, 4, 17.50, 70.00, 'Nakasero school formal pants', '2024-08-08 06:15:00', '2024-08-08 06:15:00'),
(124, 39, 11, 75, 10.25, 768.75, 'Premium school uniform shirts', '2024-08-12 11:40:00', '2024-08-12 11:40:00'),
(125, 39, 3, 35, 16.50, 577.50, 'Premium school dress shirts', '2024-08-12 11:40:00', '2024-08-12 11:40:00'),
(126, 39, 8, 4, 54.75, 219.00, 'Premium school blazers', '2024-08-12 11:40:00', '2024-08-12 11:40:00'),
(127, 41, 9, 22, 23.25, 511.50, 'Kasese autumn cardigans', '2024-09-02 13:20:00', '2024-09-02 13:20:00'),
(128, 41, 10, 12, 21.00, 252.00, 'Kasese autumn hoodies', '2024-09-02 13:20:00', '2024-09-02 13:20:00'),
(129, 41, 1, 7, 8.75, 61.25, 'Kasese basic t-shirts', '2024-09-02 13:20:00', '2024-09-02 13:20:00'),
(130, 42, 3, 42, 15.75, 661.50, 'Fort Portal business shirts', '2024-09-06 09:10:00', '2024-09-06 09:10:00'),
(131, 42, 8, 10, 46.50, 465.00, 'Fort Portal business blazers', '2024-09-06 09:10:00', '2024-09-06 09:10:00'),
(132, 42, 6, 4, 17.00, 68.00, 'Fort Portal business pants', '2024-09-06 09:10:00', '2024-09-06 09:10:00'),
(133, 43, 9, 28, 23.50, 658.00, 'Kabale winter cardigans', '2024-09-10 11:35:00', '2024-09-10 11:35:00'),
(134, 43, 10, 14, 21.75, 304.50, 'Kabale winter hoodies', '2024-09-10 11:35:00', '2024-09-10 11:35:00'),
(135, 44, 1, 65, 8.50, 552.50, 'Entebbe travel t-shirts', '2024-09-14 07:25:00', '2024-09-14 07:25:00'),
(136, 44, 4, 45, 11.25, 506.25, 'Entebbe travel casual shirts', '2024-09-14 07:25:00', '2024-09-14 07:25:00'),
(137, 44, 5, 13, 28.25, 367.25, 'Entebbe travel jeans', '2024-09-14 07:25:00', '2024-09-14 07:25:00'),
(138, 45, 1, 85, 9.00, 765.00, 'Wakiso fall t-shirts', '2024-09-18 13:50:00', '2024-09-18 13:50:00'),
(139, 45, 4, 55, 11.75, 646.25, 'Wakiso fall casual shirts', '2024-09-18 13:50:00', '2024-09-18 13:50:00'),
(140, 45, 7, 32, 15.25, 488.00, 'Wakiso fall skirts', '2024-09-18 13:50:00', '2024-09-18 13:50:00'),
(141, 45, 2, 4, 11.50, 46.00, 'Wakiso fall polo shirts', '2024-09-18 13:50:00', '2024-09-18 13:50:00'),
(142, 46, 12, 55, 17.25, 948.75, 'Mbarara corporate uniforms', '2024-10-01 10:15:00', '2024-10-01 10:15:00'),
(143, 46, 6, 18, 18.75, 337.50, 'Mbarara corporate pants', '2024-10-01 10:15:00', '2024-10-01 10:15:00'),
(144, 47, 1, 65, 8.50, 552.50, 'Masaka pre-holiday t-shirts', '2024-10-05 08:40:00', '2024-10-05 08:40:00'),
(145, 47, 4, 35, 11.50, 402.50, 'Masaka pre-holiday shirts', '2024-10-05 08:40:00', '2024-10-05 08:40:00'),
(146, 47, 7, 8, 15.00, 120.00, 'Masaka pre-holiday skirts', '2024-10-05 08:40:00', '2024-10-05 08:40:00'),
(147, 48, 1, 75, 9.25, 693.75, 'Mbale youth autumn t-shirts', '2024-10-09 12:05:00', '2024-10-09 12:05:00'),
(148, 48, 10, 28, 21.50, 602.00, 'Mbale youth autumn hoodies', '2024-10-09 12:05:00', '2024-10-09 12:05:00'),
(149, 48, 5, 8, 29.75, 238.00, 'Mbale youth autumn jeans', '2024-10-09 12:05:00', '2024-10-09 12:05:00'),
(150, 49, 1, 45, 8.25, 371.25, 'Soroti traditional fall t-shirts', '2024-10-13 06:30:00', '2024-10-13 06:30:00'),
(151, 49, 4, 25, 11.50, 287.50, 'Soroti traditional fall shirts', '2024-10-13 06:30:00', '2024-10-13 06:30:00'),
(152, 49, 7, 8, 15.75, 126.00, 'Soroti traditional fall skirts', '2024-10-13 06:30:00', '2024-10-13 06:30:00'),
(153, 50, 12, 75, 17.50, 1312.50, 'Hoima industrial winter uniforms', '2024-10-17 11:20:00', '2024-10-17 11:20:00'),
(154, 50, 6, 18, 18.50, 333.00, 'Hoima industrial winter pants', '2024-10-17 11:20:00', '2024-10-17 11:20:00'),
(155, 51, 1, 115, 9.50, 1092.50, 'Owino holiday prep t-shirts', '2024-11-01 09:45:00', '2024-11-01 09:45:00'),
(156, 51, 3, 45, 16.75, 753.75, 'Owino holiday formal shirts', '2024-11-01 09:45:00', '2024-11-01 09:45:00'),
(157, 51, 8, 9, 55.25, 497.25, 'Owino holiday blazers', '2024-11-01 09:45:00', '2024-11-01 09:45:00'),
(158, 52, 1, 95, 9.25, 878.75, 'Owino Christmas t-shirts', '2024-11-05 13:10:00', '2024-11-05 13:10:00'),
(159, 52, 4, 65, 11.75, 763.75, 'Owino Christmas casual shirts', '2024-11-05 13:10:00', '2024-11-05 13:10:00'),
(160, 52, 7, 32, 15.00, 480.00, 'Owino Christmas skirts', '2024-11-05 13:10:00', '2024-11-05 13:10:00'),
(161, 53, 1, 78, 8.75, 682.50, 'Gulu northern holiday t-shirts', '2024-11-08 07:35:00', '2024-11-08 07:35:00'),
(162, 53, 4, 42, 11.50, 483.00, 'Gulu northern holiday shirts', '2024-11-08 07:35:00', '2024-11-08 07:35:00'),
(163, 53, 9, 9, 24.50, 220.50, 'Gulu northern holiday cardigans', '2024-11-08 07:35:00', '2024-11-08 07:35:00'),
(164, 54, 12, 85, 17.75, 1508.75, 'Lira holiday work uniforms', '2024-11-12 11:55:00', '2024-11-12 11:55:00'),
(165, 54, 6, 15, 18.50, 277.50, 'Lira holiday work pants', '2024-11-12 11:55:00', '2024-11-12 11:55:00'),
(166, 55, 1, 85, 8.50, 722.50, 'Arua holiday cross-border t-shirts', '2024-11-16 08:20:00', '2024-11-16 08:20:00'),
(167, 55, 4, 48, 11.75, 564.00, 'Arua holiday cross-border shirts', '2024-11-16 08:20:00', '2024-11-16 08:20:00'),
(168, 55, 6, 15, 18.50, 277.50, 'Arua holiday cross-border pants', '2024-11-16 08:20:00', '2024-11-16 08:20:00'),
(169, 56, 3, 95, 17.50, 1662.50, 'Nakasero Christmas formal shirts', '2024-12-01 12:30:00', '2024-12-01 12:30:00'),
(170, 56, 8, 18, 52.50, 945.00, 'Nakasero Christmas blazers', '2024-12-01 12:30:00', '2024-12-01 12:30:00'),
(171, 56, 6, 4, 19.50, 78.00, 'Nakasero Christmas formal pants', '2024-12-01 12:30:00', '2024-12-01 12:30:00'),
(172, 57, 3, 85, 18.00, 1530.00, 'Premium holiday formal shirts', '2024-12-05 10:15:00', '2024-12-05 10:15:00'),
(173, 57, 8, 17, 54.00, 918.00, 'Premium holiday blazers', '2024-12-05 10:15:00', '2024-12-05 10:15:00'),
(174, 57, 5, 1, 37.00, 37.00, 'Premium holiday jeans sample', '2024-12-05 10:15:00', '2024-12-05 10:15:00'),
(175, 58, 1, 95, 9.00, 855.00, 'Jinja regional holiday t-shirts', '2024-12-08 06:45:00', '2024-12-08 06:45:00'),
(176, 58, 3, 45, 16.25, 731.25, 'Jinja regional holiday formal', '2024-12-08 06:45:00', '2024-12-08 06:45:00'),
(177, 58, 8, 6, 59.75, 358.50, 'Jinja regional holiday blazers', '2024-12-08 06:45:00', '2024-12-08 06:45:00'),
(178, 59, 1, 75, 8.75, 656.25, 'Mityana holiday casual t-shirts', '2024-12-12 13:25:00', '2024-12-12 13:25:00'),
(179, 59, 4, 42, 11.50, 483.00, 'Mityana holiday casual shirts', '2024-12-12 13:25:00', '2024-12-12 13:25:00'),
(180, 59, 7, 9, 16.25, 146.25, 'Mityana holiday casual skirts', '2024-12-12 13:25:00', '2024-12-12 13:25:00'),
(181, 61, 1, 55, 8.00, 440.00, 'Kasese new year basics t-shirts', '2025-01-03 11:20:00', '2025-01-03 11:20:00'),
(182, 61, 4, 32, 11.00, 352.00, 'Kasese new year basics shirts', '2025-01-03 11:20:00', '2025-01-03 11:20:00'),
(183, 61, 12, 10, 17.25, 172.50, 'Kasese new year work uniforms', '2025-01-03 11:20:00', '2025-01-03 11:20:00'),
(184, 62, 3, 52, 15.50, 806.00, 'Fort Portal Q1 business shirts', '2025-01-07 08:35:00', '2025-01-07 08:35:00'),
(185, 62, 8, 11, 47.25, 519.75, 'Fort Portal Q1 business blazers', '2025-01-07 08:35:00', '2025-01-07 08:35:00'),
(186, 62, 6, 3, 19.75, 59.25, 'Fort Portal Q1 business pants', '2025-01-07 08:35:00', '2025-01-07 08:35:00'),
(187, 76, 3, 85, 17.25, 1466.25, 'Nakasero Easter formal shirts', '2025-04-01 09:00:00', '2025-04-01 09:00:00'),
(188, 76, 8, 18, 53.50, 963.00, 'Nakasero Easter blazers', '2025-04-01 09:00:00', '2025-04-01 09:00:00'),
(189, 76, 6, 3, 18.75, 56.25, 'Nakasero Easter formal pants', '2025-04-01 09:00:00', '2025-04-01 09:00:00'),
(190, 80, 1, 135, 9.75, 1316.25, 'Wakiso massive Easter t-shirts', '2025-04-16 06:30:00', '2025-04-16 06:30:00'),
(191, 80, 3, 65, 17.50, 1137.50, 'Wakiso massive Easter formal', '2025-04-16 06:30:00', '2025-04-16 06:30:00'),
(192, 80, 7, 32, 16.00, 512.00, 'Wakiso massive Easter skirts', '2025-04-16 06:30:00', '2025-04-16 06:30:00'),
(193, 80, 2, 2, 9.50, 19.00, 'Wakiso Easter polo sample', '2025-04-16 06:30:00', '2025-04-16 06:30:00'),
(194, 91, 1, 105, 9.25, 971.25, 'Owino mid-year major t-shirts', '2025-06-20 12:25:00', '2025-06-20 12:25:00'),
(195, 91, 4, 72, 12.00, 864.00, 'Owino mid-year major shirts', '2025-06-20 12:25:00', '2025-06-20 12:25:00'),
(196, 91, 7, 28, 16.25, 455.00, 'Owino mid-year major skirts', '2025-06-20 12:25:00', '2025-06-20 12:25:00'),
(197, 100, 1, 125, 9.50, 1187.50, 'Wakiso massive summer t-shirts', '2025-07-11 13:30:00', '2025-07-11 13:30:00'),
(198, 100, 4, 85, 12.25, 1041.25, 'Wakiso massive summer shirts', '2025-07-11 13:30:00', '2025-07-11 13:30:00'),
(199, 100, 7, 38, 16.50, 627.00, 'Wakiso massive summer skirts', '2025-07-11 13:30:00', '2025-07-11 13:30:00'),
(200, 100, 2, 12, 12.75, 153.00, 'Wakiso summer polo shirts', '2025-07-11 13:30:00', '2025-07-11 13:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `pending_users`
--

INSERT INTO `pending_users` (`id`, `name`, `email`, `password`, `role`, `visitDate`, `business_address`, `phone`, `license_document`, `document_path`, `business_type`, `monthly_order_volume`, `production_capacity`, `preferred_categories`, `specialization`, `materials_supplied`, `created_at`, `updated_at`) VALUES
(1, 'New Textile Ventures', 'info@newtextile.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', '2025-07-15 10:00:00', 'Industrial Zone, Mukono, Uganda', '+256751234567', 'license_pending_001.pdf', 'documents/pending/license_pending_001.pdf', NULL, NULL, NULL, NULL, '[\"Cotton Fabrics\", \"Synthetic Materials\"]', '[\"Cotton\", \"Polyester\", \"Nylon\"]', '2025-07-01 07:00:00', '2025-07-01 07:00:00'),
(2, 'Modern Fabrics Uganda', 'contact@modernfabrics.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', '2025-07-18 14:00:00', 'Commercial District, Entebbe, Uganda', '+256752345678', 'license_pending_002.pdf', 'documents/pending/license_pending_002.pdf', NULL, NULL, NULL, NULL, '[\"Premium Fabrics\", \"Specialty Materials\"]', '[\"Silk\", \"Linen\", \"Hemp\"]', '2025-07-05 11:00:00', '2025-07-05 11:00:00'),
(3, 'Premium Cotton Suppliers', 'sales@premiumcotton.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', '2025-07-20 11:30:00', 'Agricultural Zone, Luwero, Uganda', '+256753456789', 'license_pending_003.pdf', 'documents/pending/license_pending_003.pdf', NULL, NULL, NULL, NULL, '[\"Organic Cotton\", \"Natural Fibers\"]', '[\"Organic Cotton\", \"Bamboo Fiber\", \"Linen\"]', '2025-07-08 13:30:00', '2025-07-08 13:30:00'),
(4, 'New Market Ventures', 'info@newmarket.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', '2025-07-16 13:30:00', 'Central Market, Tororo, Uganda', '+256756789012', 'wholesale_pending_001.pdf', 'documents/pending/wholesale_pending_001.pdf', 'Retail Distribution', 1200, NULL, '[\"Casual Wear\", \"Formal Wear\"]', NULL, NULL, '2025-07-02 06:00:00', '2025-07-02 06:00:00'),
(5, 'Fresh Fashion Wholesale', 'sales@freshfashion.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', '2025-07-19 10:15:00', 'Fashion District, Ntungamo, Uganda', '+256757890123', 'wholesale_pending_002.pdf', 'documents/pending/wholesale_pending_002.pdf', 'Fashion Retail', 800, NULL, '[\"Youth Fashion\", \"Trendy Clothing\"]', NULL, NULL, '2025-07-06 11:30:00', '2025-07-06 11:30:00'),
(6, 'Modern Trade Solutions', 'contact@moderntrade.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', '2025-07-21 16:00:00', 'Business Park, Busia, Uganda', '+256758901234', 'wholesale_pending_003.pdf', 'documents/pending/wholesale_pending_003.pdf', 'B2B Distribution', 2000, NULL, '[\"Business Attire\", \"Corporate Wear\"]', NULL, NULL, '2025-07-09 08:15:00', '2025-07-09 08:15:00');

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

--
-- Dumping data for table `supplied_items`
--

INSERT INTO `supplied_items` (`id`, `supplier_id`, `item_id`, `price`, `delivered_quantity`, `delivery_date`, `quality_rating`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 13, 3.50, 450, '2024-01-03 09:00:00', 4, 'delivered', '2024-01-03 06:00:00', '2024-01-03 06:00:00'),
(2, 2, 14, 4.25, 320, '2024-01-05 14:30:00', 5, 'delivered', '2024-01-05 11:30:00', '2024-01-05 11:30:00'),
(3, 3, 15, 8.00, 180, '2024-01-08 11:15:00', 4, 'delivered', '2024-01-08 08:15:00', '2024-01-08 08:15:00'),
(4, 4, 16, 3.75, 385, '2024-01-12 16:45:00', 5, 'delivered', '2024-01-12 13:45:00', '2024-01-12 13:45:00'),
(5, 5, 17, 0.75, 850, '2024-01-15 10:30:00', 4, 'delivered', '2024-01-15 07:30:00', '2024-01-15 07:30:00'),
(6, 6, 18, 0.65, 720, '2024-01-18 13:20:00', 5, 'delivered', '2024-01-18 10:20:00', '2024-01-18 10:20:00'),
(7, 7, 19, 1.25, 650, '2024-01-22 15:10:00', 4, 'delivered', '2024-01-22 12:10:00', '2024-01-22 12:10:00'),
(8, 8, 20, 0.15, 4200, '2024-01-25 09:45:00', 5, 'delivered', '2024-01-25 06:45:00', '2024-01-25 06:45:00'),
(9, 9, 21, 2.50, 285, '2024-01-28 12:00:00', 4, 'delivered', '2024-01-28 09:00:00', '2024-01-28 09:00:00'),
(10, 10, 22, 0.05, 1850, '2024-01-30 14:25:00', 5, 'delivered', '2024-01-30 11:25:00', '2024-01-30 11:25:00'),
(11, 1, 13, 3.60, 520, '2024-02-02 10:15:00', 4, 'delivered', '2024-02-02 07:15:00', '2024-02-02 07:15:00'),
(12, 2, 14, 4.30, 380, '2024-02-05 13:30:00', 5, 'delivered', '2024-02-05 10:30:00', '2024-02-05 10:30:00'),
(13, 3, 15, 8.25, 220, '2024-02-08 11:45:00', 4, 'delivered', '2024-02-08 08:45:00', '2024-02-08 08:45:00'),
(14, 4, 16, 3.80, 425, '2024-02-12 16:20:00', 5, 'delivered', '2024-02-12 13:20:00', '2024-02-12 13:20:00'),
(15, 5, 17, 0.78, 920, '2024-02-14 09:30:00', 4, 'delivered', '2024-02-14 06:30:00', '2024-02-14 06:30:00'),
(16, 6, 18, 0.67, 780, '2024-02-18 14:15:00', 5, 'delivered', '2024-02-18 11:15:00', '2024-02-18 11:15:00'),
(17, 7, 19, 1.28, 710, '2024-02-22 12:45:00', 4, 'delivered', '2024-02-22 09:45:00', '2024-02-22 09:45:00'),
(18, 8, 20, 0.16, 4600, '2024-02-25 10:00:00', 5, 'delivered', '2024-02-25 07:00:00', '2024-02-25 07:00:00'),
(19, 9, 21, 2.55, 320, '2024-02-28 15:30:00', 4, 'delivered', '2024-02-28 12:30:00', '2024-02-28 12:30:00'),
(20, 10, 22, 0.05, 2100, '2024-03-03 11:20:00', 5, 'delivered', '2024-03-03 08:20:00', '2024-03-03 08:20:00'),
(21, 1, 13, 3.45, 580, '2024-03-07 13:15:00', 4, 'delivered', '2024-03-07 10:15:00', '2024-03-07 10:15:00'),
(22, 2, 14, 4.15, 420, '2024-03-10 10:45:00', 5, 'delivered', '2024-03-10 07:45:00', '2024-03-10 07:45:00'),
(23, 3, 15, 7.95, 240, '2024-03-14 16:30:00', 4, 'delivered', '2024-03-14 13:30:00', '2024-03-14 13:30:00'),
(24, 4, 16, 3.70, 465, '2024-03-18 09:15:00', 5, 'delivered', '2024-03-18 06:15:00', '2024-03-18 06:15:00'),
(25, 5, 17, 0.74, 980, '2024-03-22 14:00:00', 4, 'delivered', '2024-03-22 11:00:00', '2024-03-22 11:00:00'),
(26, 6, 18, 0.64, 840, '2024-03-25 11:30:00', 5, 'delivered', '2024-03-25 08:30:00', '2024-03-25 08:30:00'),
(27, 7, 19, 1.22, 760, '2024-03-28 15:45:00', 4, 'delivered', '2024-03-28 12:45:00', '2024-03-28 12:45:00'),
(28, 8, 20, 0.14, 5200, '2024-04-02 10:30:00', 5, 'delivered', '2024-04-02 07:30:00', '2024-04-02 07:30:00'),
(29, 9, 21, 2.48, 380, '2024-04-05 12:15:00', 4, 'delivered', '2024-04-05 09:15:00', '2024-04-05 09:15:00'),
(30, 10, 22, 0.05, 2400, '2024-04-08 14:45:00', 5, 'delivered', '2024-04-08 11:45:00', '2024-04-08 11:45:00'),
(31, 1, 13, 3.65, 650, '2024-04-12 09:20:00', 4, 'delivered', '2024-04-12 06:20:00', '2024-04-12 06:20:00'),
(32, 2, 14, 4.35, 480, '2024-04-15 16:10:00', 5, 'delivered', '2024-04-15 13:10:00', '2024-04-15 13:10:00'),
(33, 3, 15, 8.15, 280, '2024-04-18 11:45:00', 4, 'delivered', '2024-04-18 08:45:00', '2024-04-18 08:45:00'),
(34, 4, 16, 3.85, 520, '2024-04-22 13:30:00', 5, 'delivered', '2024-04-22 10:30:00', '2024-04-22 10:30:00'),
(35, 5, 17, 0.80, 1120, '2024-04-25 10:15:00', 4, 'delivered', '2024-04-25 07:15:00', '2024-04-25 07:15:00'),
(36, 6, 18, 0.68, 950, '2024-04-28 15:00:00', 5, 'delivered', '2024-04-28 12:00:00', '2024-04-28 12:00:00'),
(37, 7, 19, 1.20, 680, '2024-05-03 12:30:00', 4, 'delivered', '2024-05-03 09:30:00', '2024-05-03 09:30:00'),
(38, 8, 20, 0.15, 4800, '2024-05-07 14:15:00', 5, 'delivered', '2024-05-07 11:15:00', '2024-05-07 11:15:00'),
(39, 9, 21, 2.45, 320, '2024-05-10 10:45:00', 4, 'delivered', '2024-05-10 07:45:00', '2024-05-10 07:45:00'),
(40, 10, 22, 0.05, 2000, '2024-05-14 16:20:00', 5, 'delivered', '2024-05-14 13:20:00', '2024-05-14 13:20:00'),
(41, 1, 13, 3.55, 540, '2024-05-18 09:30:00', 4, 'delivered', '2024-05-18 06:30:00', '2024-05-18 06:30:00'),
(42, 2, 14, 4.20, 390, '2024-05-22 13:45:00', 5, 'delivered', '2024-05-22 10:45:00', '2024-05-22 10:45:00'),
(43, 3, 15, 7.85, 210, '2024-05-25 11:15:00', 4, 'delivered', '2024-05-25 08:15:00', '2024-05-25 08:15:00'),
(44, 4, 16, 3.72, 445, '2024-05-28 15:30:00', 5, 'delivered', '2024-05-28 12:30:00', '2024-05-28 12:30:00'),
(45, 5, 17, 0.76, 1050, '2024-06-02 10:00:00', 4, 'delivered', '2024-06-02 07:00:00', '2024-06-02 07:00:00'),
(46, 6, 18, 0.66, 880, '2024-06-05 13:20:00', 5, 'delivered', '2024-06-05 10:20:00', '2024-06-05 10:20:00'),
(47, 7, 19, 1.24, 720, '2024-06-08 11:30:00', 4, 'delivered', '2024-06-08 08:30:00', '2024-06-08 08:30:00'),
(48, 8, 20, 0.15, 4500, '2024-06-12 16:15:00', 5, 'delivered', '2024-06-12 13:15:00', '2024-06-12 13:15:00'),
(49, 9, 21, 2.52, 340, '2024-06-15 09:45:00', 4, 'delivered', '2024-06-15 06:45:00', '2024-06-15 06:45:00'),
(50, 10, 22, 0.05, 2200, '2024-06-18 14:30:00', 5, 'delivered', '2024-06-18 11:30:00', '2024-06-18 11:30:00'),
(51, 1, 13, 3.70, 620, '2024-06-22 12:00:00', 4, 'delivered', '2024-06-22 09:00:00', '2024-06-22 09:00:00'),
(52, 2, 14, 4.40, 450, '2024-06-25 10:45:00', 5, 'delivered', '2024-06-25 07:45:00', '2024-06-25 07:45:00'),
(53, 3, 15, 8.20, 260, '2024-06-28 15:15:00', 4, 'delivered', '2024-06-28 12:15:00', '2024-06-28 12:15:00'),
(54, 4, 16, 3.90, 580, '2024-07-02 11:30:00', 5, 'delivered', '2024-07-02 08:30:00', '2024-07-02 08:30:00'),
(55, 5, 17, 0.82, 1180, '2024-07-05 13:45:00', 4, 'delivered', '2024-07-05 10:45:00', '2024-07-05 10:45:00'),
(56, 6, 18, 0.70, 1020, '2024-07-08 10:15:00', 5, 'delivered', '2024-07-08 07:15:00', '2024-07-08 07:15:00'),
(57, 7, 19, 1.30, 820, '2024-07-12 16:30:00', 4, 'delivered', '2024-07-12 13:30:00', '2024-07-12 13:30:00'),
(58, 8, 20, 0.16, 5400, '2024-07-15 09:00:00', 5, 'delivered', '2024-07-15 06:00:00', '2024-07-15 06:00:00'),
(59, 9, 21, 2.58, 420, '2024-07-18 14:45:00', 4, 'delivered', '2024-07-18 11:45:00', '2024-07-18 11:45:00'),
(60, 10, 22, 0.05, 2800, '2024-07-22 12:30:00', 5, 'delivered', '2024-07-22 09:30:00', '2024-07-22 09:30:00'),
(61, 1, 13, 3.85, 720, '2024-07-25 11:00:00', 4, 'delivered', '2024-07-25 08:00:00', '2024-07-25 08:00:00'),
(62, 2, 14, 4.50, 520, '2024-07-28 15:45:00', 5, 'delivered', '2024-07-28 12:45:00', '2024-07-28 12:45:00'),
(63, 3, 15, 8.35, 320, '2024-08-02 10:30:00', 4, 'delivered', '2024-08-02 07:30:00', '2024-08-02 07:30:00'),
(64, 4, 16, 3.95, 640, '2024-08-05 13:15:00', 5, 'delivered', '2024-08-05 10:15:00', '2024-08-05 10:15:00'),
(65, 5, 17, 0.85, 1250, '2024-08-08 11:45:00', 4, 'delivered', '2024-08-08 08:45:00', '2024-08-08 08:45:00'),
(66, 6, 18, 0.72, 1080, '2024-08-12 16:00:00', 5, 'delivered', '2024-08-12 13:00:00', '2024-08-12 13:00:00'),
(67, 7, 19, 1.32, 880, '2024-08-15 09:30:00', 4, 'delivered', '2024-08-15 06:30:00', '2024-08-15 06:30:00'),
(68, 8, 20, 0.16, 5800, '2024-08-18 14:20:00', 5, 'delivered', '2024-08-18 11:20:00', '2024-08-18 11:20:00'),
(69, 9, 21, 2.60, 460, '2024-08-22 12:15:00', 4, 'delivered', '2024-08-22 09:15:00', '2024-08-22 09:15:00'),
(70, 10, 22, 0.05, 3000, '2024-08-25 10:45:00', 5, 'delivered', '2024-08-25 07:45:00', '2024-08-25 07:45:00'),
(71, 1, 13, 3.75, 680, '2024-08-28 15:30:00', 4, 'delivered', '2024-08-28 12:30:00', '2024-08-28 12:30:00'),
(72, 2, 14, 4.45, 480, '2024-08-30 13:00:00', 5, 'delivered', '2024-08-30 10:00:00', '2024-08-30 10:00:00'),
(73, 3, 15, 8.25, 280, '2024-09-03 11:15:00', 4, 'delivered', '2024-09-03 08:15:00', '2024-09-03 08:15:00'),
(74, 4, 16, 3.88, 560, '2024-09-06 14:30:00', 5, 'delivered', '2024-09-06 11:30:00', '2024-09-06 11:30:00'),
(75, 5, 17, 0.78, 1150, '2024-09-09 10:45:00', 4, 'delivered', '2024-09-09 07:45:00', '2024-09-09 07:45:00'),
(76, 6, 18, 0.69, 980, '2024-09-13 16:20:00', 5, 'delivered', '2024-09-13 13:20:00', '2024-09-13 13:20:00'),
(77, 7, 19, 1.28, 750, '2024-09-16 09:15:00', 4, 'delivered', '2024-09-16 06:15:00', '2024-09-16 06:15:00'),
(78, 8, 20, 0.15, 5200, '2024-09-19 13:45:00', 5, 'delivered', '2024-09-19 10:45:00', '2024-09-19 10:45:00'),
(79, 9, 21, 2.55, 390, '2024-09-23 11:30:00', 4, 'delivered', '2024-09-23 08:30:00', '2024-09-23 08:30:00'),
(80, 10, 22, 0.05, 2600, '2024-09-26 15:00:00', 5, 'delivered', '2024-09-26 12:00:00', '2024-09-26 12:00:00'),
(81, 1, 13, 3.80, 620, '2024-09-29 12:45:00', 4, 'delivered', '2024-09-29 09:45:00', '2024-09-29 09:45:00'),
(82, 2, 14, 4.35, 420, '2024-10-02 10:00:00', 5, 'delivered', '2024-10-02 07:00:00', '2024-10-02 07:00:00'),
(83, 3, 15, 8.40, 300, '2024-10-05 13:30:00', 4, 'delivered', '2024-10-05 10:30:00', '2024-10-05 10:30:00'),
(84, 4, 16, 3.92, 580, '2024-10-08 11:20:00', 5, 'delivered', '2024-10-08 08:20:00', '2024-10-08 08:20:00'),
(85, 5, 17, 0.80, 1200, '2024-10-12 16:45:00', 4, 'delivered', '2024-10-12 13:45:00', '2024-10-12 13:45:00'),
(86, 6, 18, 0.71, 1050, '2024-10-15 09:00:00', 5, 'delivered', '2024-10-15 06:00:00', '2024-10-15 06:00:00'),
(87, 7, 19, 1.35, 820, '2024-10-18 14:15:00', 4, 'delivered', '2024-10-18 11:15:00', '2024-10-18 11:15:00'),
(88, 8, 20, 0.17, 5600, '2024-10-22 12:30:00', 5, 'delivered', '2024-10-22 09:30:00', '2024-10-22 09:30:00'),
(89, 9, 21, 2.62, 440, '2024-10-25 10:15:00', 4, 'delivered', '2024-10-25 07:15:00', '2024-10-25 07:15:00'),
(90, 10, 22, 0.05, 2900, '2024-10-28 15:45:00', 5, 'delivered', '2024-10-28 12:45:00', '2024-10-28 12:45:00'),
(91, 1, 13, 3.85, 680, '2024-10-31 13:00:00', 4, 'delivered', '2024-10-31 10:00:00', '2024-10-31 10:00:00'),
(92, 2, 14, 4.55, 520, '2024-11-03 11:45:00', 5, 'delivered', '2024-11-03 08:45:00', '2024-11-03 08:45:00'),
(93, 3, 15, 8.60, 380, '2024-11-06 14:00:00', 4, 'delivered', '2024-11-06 11:00:00', '2024-11-06 11:00:00'),
(94, 4, 16, 4.05, 650, '2024-11-09 10:30:00', 5, 'delivered', '2024-11-09 07:30:00', '2024-11-09 07:30:00'),
(95, 5, 17, 0.88, 1350, '2024-11-13 16:15:00', 4, 'delivered', '2024-11-13 13:15:00', '2024-11-13 13:15:00'),
(96, 6, 18, 0.75, 1150, '2024-11-16 09:30:00', 5, 'delivered', '2024-11-16 06:30:00', '2024-11-16 06:30:00'),
(97, 7, 19, 1.40, 920, '2024-11-19 13:15:00', 4, 'delivered', '2024-11-19 10:15:00', '2024-11-19 10:15:00'),
(98, 8, 20, 0.18, 6200, '2024-11-23 11:00:00', 5, 'delivered', '2024-11-23 08:00:00', '2024-11-23 08:00:00'),
(99, 9, 21, 2.70, 520, '2024-11-26 15:30:00', 4, 'delivered', '2024-11-26 12:30:00', '2024-11-26 12:30:00'),
(100, 10, 22, 0.05, 3500, '2024-11-29 12:45:00', 5, 'delivered', '2024-11-29 09:45:00', '2024-11-29 09:45:00'),
(101, 1, 13, 4.10, 850, '2024-12-02 10:20:00', 4, 'delivered', '2024-12-02 07:20:00', '2024-12-02 07:20:00'),
(102, 2, 14, 4.75, 680, '2024-12-05 13:45:00', 5, 'delivered', '2024-12-05 10:45:00', '2024-12-05 10:45:00'),
(103, 3, 15, 8.85, 450, '2024-12-08 11:30:00', 4, 'delivered', '2024-12-08 08:30:00', '2024-12-08 08:30:00'),
(104, 4, 16, 4.20, 750, '2024-12-12 16:00:00', 5, 'delivered', '2024-12-12 13:00:00', '2024-12-12 13:00:00'),
(105, 5, 17, 0.92, 1520, '2024-12-15 09:15:00', 4, 'delivered', '2024-12-15 06:15:00', '2024-12-15 06:15:00'),
(106, 6, 18, 0.78, 1280, '2024-12-18 14:30:00', 5, 'delivered', '2024-12-18 11:30:00', '2024-12-18 11:30:00'),
(107, 7, 19, 1.45, 1050, '2024-12-21 12:15:00', 4, 'delivered', '2024-12-21 09:15:00', '2024-12-21 09:15:00'),
(108, 8, 20, 0.19, 7200, '2024-12-24 10:45:00', 5, 'delivered', '2024-12-24 07:45:00', '2024-12-24 07:45:00'),
(109, 9, 21, 2.85, 620, '2024-12-27 15:00:00', 4, 'delivered', '2024-12-27 12:00:00', '2024-12-27 12:00:00'),
(110, 10, 22, 0.05, 4200, '2024-12-30 13:30:00', 5, 'delivered', '2024-12-30 10:30:00', '2024-12-30 10:30:00'),
(111, 1, 13, 3.65, 520, '2025-01-03 09:30:00', 4, 'delivered', '2025-01-03 06:30:00', '2025-01-03 06:30:00'),
(112, 2, 14, 4.25, 380, '2025-01-06 14:15:00', 5, 'delivered', '2025-01-06 11:15:00', '2025-01-06 11:15:00'),
(113, 3, 15, 8.10, 240, '2025-01-09 11:00:00', 4, 'delivered', '2025-01-09 08:00:00', '2025-01-09 08:00:00'),
(114, 4, 16, 3.78, 420, '2025-01-12 16:45:00', 5, 'delivered', '2025-01-12 13:45:00', '2025-01-12 13:45:00'),
(115, 5, 17, 0.76, 950, '2025-01-15 10:15:00', 4, 'delivered', '2025-01-15 07:15:00', '2025-01-15 07:15:00'),
(116, 6, 18, 0.68, 820, '2025-01-18 13:30:00', 5, 'delivered', '2025-01-18 10:30:00', '2025-01-18 10:30:00'),
(117, 7, 19, 1.22, 680, '2025-01-21 15:00:00', 4, 'delivered', '2025-01-21 12:00:00', '2025-01-21 12:00:00'),
(118, 8, 20, 0.15, 4400, '2025-01-24 09:45:00', 5, 'delivered', '2025-01-24 06:45:00', '2025-01-24 06:45:00'),
(119, 9, 21, 2.48, 350, '2025-01-27 12:30:00', 4, 'delivered', '2025-01-27 09:30:00', '2025-01-27 09:30:00'),
(120, 10, 22, 0.05, 2200, '2025-01-30 14:00:00', 5, 'delivered', '2025-01-30 11:00:00', '2025-01-30 11:00:00'),
(121, 1, 13, 3.70, 580, '2025-02-02 10:45:00', 4, 'delivered', '2025-02-02 07:45:00', '2025-02-02 07:45:00'),
(122, 2, 14, 4.32, 420, '2025-02-05 13:20:00', 5, 'delivered', '2025-02-05 10:20:00', '2025-02-05 10:20:00'),
(123, 3, 15, 8.15, 280, '2025-02-08 11:15:00', 4, 'delivered', '2025-02-08 08:15:00', '2025-02-08 08:15:00'),
(124, 4, 16, 3.82, 480, '2025-02-11 16:30:00', 5, 'delivered', '2025-02-11 13:30:00', '2025-02-11 13:30:00'),
(125, 5, 17, 0.79, 1020, '2025-02-14 09:00:00', 4, 'delivered', '2025-02-14 06:00:00', '2025-02-14 06:00:00'),
(126, 6, 18, 0.70, 880, '2025-02-17 14:45:00', 5, 'delivered', '2025-02-17 11:45:00', '2025-02-17 11:45:00'),
(127, 7, 19, 1.26, 720, '2025-02-20 12:00:00', 4, 'delivered', '2025-02-20 09:00:00', '2025-02-20 09:00:00'),
(128, 8, 20, 0.16, 4800, '2025-02-23 10:30:00', 5, 'delivered', '2025-02-23 07:30:00', '2025-02-23 07:30:00'),
(129, 9, 21, 2.52, 380, '2025-02-26 15:15:00', 4, 'delivered', '2025-02-26 12:15:00', '2025-02-26 12:15:00'),
(130, 10, 22, 0.05, 2400, '2025-03-01 11:30:00', 5, 'delivered', '2025-03-01 08:30:00', '2025-03-01 08:30:00'),
(131, 1, 13, 3.75, 620, '2025-03-04 14:00:00', 4, 'delivered', '2025-03-04 11:00:00', '2025-03-04 11:00:00'),
(132, 2, 14, 4.28, 450, '2025-03-07 10:45:00', 5, 'delivered', '2025-03-07 07:45:00', '2025-03-07 07:45:00'),
(133, 3, 15, 8.05, 320, '2025-03-10 16:20:00', 4, 'delivered', '2025-03-10 13:20:00', '2025-03-10 13:20:00'),
(134, 4, 16, 3.85, 520, '2025-03-13 09:15:00', 5, 'delivered', '2025-03-13 06:15:00', '2025-03-13 06:15:00'),
(135, 5, 17, 0.77, 1080, '2025-03-16 13:45:00', 4, 'delivered', '2025-03-16 10:45:00', '2025-03-16 10:45:00'),
(136, 6, 18, 0.69, 920, '2025-03-19 11:00:00', 5, 'delivered', '2025-03-19 08:00:00', '2025-03-19 08:00:00'),
(137, 7, 19, 1.24, 760, '2025-03-22 15:30:00', 4, 'delivered', '2025-03-22 12:30:00', '2025-03-22 12:30:00'),
(138, 8, 20, 0.15, 5000, '2025-03-25 12:15:00', 5, 'delivered', '2025-03-25 09:15:00', '2025-03-25 09:15:00'),
(139, 9, 21, 2.50, 420, '2025-03-28 14:45:00', 4, 'delivered', '2025-03-28 11:45:00', '2025-03-28 11:45:00'),
(140, 10, 22, 0.05, 2600, '2025-04-01 10:00:00', 5, 'delivered', '2025-04-01 07:00:00', '2025-04-01 07:00:00'),
(141, 1, 13, 3.90, 720, '2025-04-04 13:15:00', 4, 'delivered', '2025-04-04 10:15:00', '2025-04-04 10:15:00'),
(142, 2, 14, 4.45, 520, '2025-04-07 11:45:00', 5, 'delivered', '2025-04-07 08:45:00', '2025-04-07 08:45:00'),
(143, 3, 15, 8.30, 380, '2025-04-10 16:00:00', 4, 'delivered', '2025-04-10 13:00:00', '2025-04-10 13:00:00'),
(144, 4, 16, 3.95, 580, '2025-04-13 09:30:00', 5, 'delivered', '2025-04-13 06:30:00', '2025-04-13 06:30:00'),
(145, 5, 17, 0.85, 1200, '2025-04-16 14:15:00', 4, 'delivered', '2025-04-16 11:15:00', '2025-04-16 11:15:00'),
(146, 6, 18, 0.73, 1020, '2025-04-19 12:30:00', 5, 'delivered', '2025-04-19 09:30:00', '2025-04-19 09:30:00'),
(147, 7, 19, 1.32, 850, '2025-04-22 10:15:00', 4, 'delivered', '2025-04-22 07:15:00', '2025-04-22 07:15:00'),
(148, 8, 20, 0.17, 5500, '2025-04-25 15:45:00', 5, 'delivered', '2025-04-25 12:45:00', '2025-04-25 12:45:00'),
(149, 9, 21, 2.58, 480, '2025-04-28 13:00:00', 4, 'delivered', '2025-04-28 10:00:00', '2025-04-28 10:00:00'),
(150, 10, 22, 0.05, 2800, '2025-05-01 11:20:00', 5, 'delivered', '2025-05-01 08:20:00', '2025-05-01 08:20:00'),
(151, 1, 13, 3.68, 580, '2025-05-04 14:30:00', 4, 'delivered', '2025-05-04 11:30:00', '2025-05-04 11:30:00'),
(152, 2, 14, 4.22, 420, '2025-05-07 10:15:00', 5, 'delivered', '2025-05-07 07:15:00', '2025-05-07 07:15:00'),
(153, 3, 15, 7.95, 290, '2025-05-10 16:45:00', 4, 'delivered', '2025-05-10 13:45:00', '2025-05-10 13:45:00'),
(154, 4, 16, 3.80, 480, '2025-05-13 09:00:00', 5, 'delivered', '2025-05-13 06:00:00', '2025-05-13 06:00:00'),
(155, 5, 17, 0.75, 1050, '2025-05-16 13:30:00', 4, 'delivered', '2025-05-16 10:30:00', '2025-05-16 10:30:00'),
(156, 6, 18, 0.67, 890, '2025-05-19 11:45:00', 5, 'delivered', '2025-05-19 08:45:00', '2025-05-19 08:45:00'),
(157, 7, 19, 1.20, 720, '2025-05-22 15:00:00', 4, 'delivered', '2025-05-22 12:00:00', '2025-05-22 12:00:00'),
(158, 8, 20, 0.15, 4800, '2025-05-25 12:45:00', 5, 'delivered', '2025-05-25 09:45:00', '2025-05-25 09:45:00'),
(159, 9, 21, 2.45, 390, '2025-05-28 14:00:00', 4, 'delivered', '2025-05-28 11:00:00', '2025-05-28 11:00:00'),
(160, 10, 22, 0.05, 2500, '2025-06-01 10:30:00', 5, 'delivered', '2025-06-01 07:30:00', '2025-06-01 07:30:00'),
(161, 1, 13, 3.80, 650, '2025-06-04 13:45:00', 4, 'delivered', '2025-06-04 10:45:00', '2025-06-04 10:45:00'),
(162, 2, 14, 4.38, 480, '2025-06-07 11:30:00', 5, 'delivered', '2025-06-07 08:30:00', '2025-06-07 08:30:00'),
(163, 3, 15, 8.20, 340, '2025-06-10 16:15:00', 4, 'delivered', '2025-06-10 13:15:00', '2025-06-10 13:15:00'),
(164, 4, 16, 3.88, 520, '2025-06-13 09:45:00', 5, 'delivered', '2025-06-13 06:45:00', '2025-06-13 06:45:00'),
(165, 5, 17, 0.78, 1120, '2025-06-16 14:00:00', 4, 'delivered', '2025-06-16 11:00:00', '2025-06-16 11:00:00'),
(166, 6, 18, 0.71, 950, '2025-06-19 12:15:00', 5, 'delivered', '2025-06-19 09:15:00', '2025-06-19 09:15:00'),
(167, 7, 19, 1.28, 780, '2025-06-22 10:45:00', 4, 'delivered', '2025-06-22 07:45:00', '2025-06-22 07:45:00'),
(168, 8, 20, 0.16, 5200, '2025-06-25 15:30:00', 5, 'delivered', '2025-06-25 12:30:00', '2025-06-25 12:30:00'),
(169, 9, 21, 2.55, 450, '2025-06-28 13:15:00', 4, 'delivered', '2025-06-28 10:15:00', '2025-06-28 10:15:00'),
(170, 10, 22, 0.05, 2700, '2025-07-01 11:00:00', 5, 'delivered', '2025-07-01 08:00:00', '2025-07-01 08:00:00'),
(171, 1, 13, 3.85, 680, '2025-07-04 14:20:00', 4, 'delivered', '2025-07-04 11:20:00', '2025-07-04 11:20:00'),
(172, 2, 14, 4.42, 510, '2025-07-07 10:30:00', 5, 'delivered', '2025-07-07 07:30:00', '2025-07-07 07:30:00'),
(173, 3, 15, 8.25, 360, '2025-07-10 16:45:00', 4, 'delivered', '2025-07-10 13:45:00', '2025-07-10 13:45:00'),
(174, 4, 16, 3.92, 540, '2025-07-12 09:30:00', NULL, 'pending', '2025-07-08 07:00:00', '2025-07-08 07:00:00'),
(175, 5, 17, 0.82, 1180, '2025-07-14 14:15:00', NULL, 'pending', '2025-07-09 08:30:00', '2025-07-09 08:30:00'),
(176, 6, 18, 0.69, 150, '2025-06-20 12:00:00', 2, 'returned', '2025-06-20 09:00:00', '2025-06-25 12:30:00'),
(177, 7, 19, 1.25, 80, '2025-06-15 10:30:00', 2, 'returned', '2025-06-15 07:30:00', '2025-06-22 11:45:00');

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
(1, 2, 'Industrial Area, Plot 45-47, Kampala, Uganda', '+256701234567', 'license_001.pdf', 'documents/license_001.pdf', '[\"Cotton\", \"Polyester\", \"Denim\", \"Wool\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(2, 3, 'Jinja Road, Industrial Zone, Kampala, Uganda', '+256702345678', 'license_002.pdf', 'documents/license_002.pdf', '[\"Cotton\", \"Cotton Blend\", \"Linen\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(3, 4, 'Main Street, Commercial District, Jinja, Uganda', '+256703456789', 'license_003.pdf', 'documents/license_003.pdf', '[\"Denim\", \"Cotton Twill\", \"Canvas\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(4, 5, 'High Street, Trading Center, Mbarara, Uganda', '+256704567890', 'license_004.pdf', 'documents/license_004.pdf', '[\"Wool\", \"Wool Blend\", \"Cashmere\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(5, 6, 'Commercial District, Central Plaza, Gulu, Uganda', '+256705678901', 'license_005.pdf', 'documents/license_005.pdf', '[\"Cotton\", \"Polyester\", \"Cotton Blend\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(6, 7, 'Industrial Zone, Plot 12-14, Masaka, Uganda', '+256706789012', 'license_006.pdf', 'documents/license_006.pdf', '[\"Cotton\", \"Synthetic Blends\", \"Elastic Materials\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(7, 8, 'Trading Center, Main Road, Lira, Uganda', '+256707890123', 'license_007.pdf', 'documents/license_007.pdf', '[\"Cotton\", \"Natural Fibers\", \"Threads\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(8, 9, 'Commercial District, Business Park, Mbale, Uganda', '+256708901234', 'license_008.pdf', 'documents/license_008.pdf', '[\"Cotton\", \"Polyester\", \"Buttons\", \"Zippers\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(9, 10, 'Industrial Area, Manufacturing Zone, Kasese, Uganda', '+256709012345', 'license_009.pdf', 'documents/license_009.pdf', '[\"Raw Materials\", \"Accessories\", \"Labels\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(10, 11, 'Trading Post, Commercial Street, Soroti, Uganda', '+256710123456', 'license_010.pdf', 'documents/license_010.pdf', '[\"Cotton\", \"Denim\", \"Specialty Fabrics\"]', '2024-01-01 07:00:00', '2024-01-01 07:00:00');

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
(1, 'ChicAura Fashion Manufacturing', 'production@chicaura.ug', 'manufacturer1.jpg', '2024-01-01 07:00:00', '', 'manufacturer', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 09:30:00', 'approved'),
(2, 'Uganda Textile Suppliers Ltd', 'supplies@ugandatextile.com', 'supplier1.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 07:00:00', 'approved'),
(3, 'Kampala Cotton Mills', 'info@kampalacotton.com', 'supplier2.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 06:30:00', 'approved'),
(4, 'Jinja Textile Works', 'sales@jinjatextile.com', 'supplier3.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 08:15:00', 'approved'),
(5, 'Mbarara Fabrics Co', 'contact@mbararafabrics.com', 'supplier4.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 05:45:00', 'approved'),
(6, 'Gulu Garments Ltd', 'orders@gululgarments.com', 'supplier5.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 11:20:00', 'approved'),
(7, 'Masaka Textile Hub', 'info@masakatextile.ug', 'supplier6.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 10:20:00', 'approved'),
(8, 'Lira Cotton Suppliers', 'sales@liracotton.com', 'supplier7.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 08:45:00', 'approved'),
(9, 'Mbale Fabric Works', 'contact@mbalefabrics.ug', 'supplier8.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 13:30:00', 'approved'),
(10, 'Kasese Materials Ltd', 'orders@kasesemat.com', 'supplier9.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 06:15:00', 'approved'),
(11, 'Soroti Textile Co', 'info@sorotitextile.ug', 'supplier10.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 11:50:00', 'approved'),
(12, 'Low Quality Fabrics Ltd', 'lowquality@fabrics.com', 'supplier11.jpg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-06-01 07:00:00', '2024-06-15 11:30:00', NULL, 'rejected'),
(13, 'Unreliable Supplies Co', 'unreliable@supplies.ug', 'supplier12.jpg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2024-05-15 06:00:00', '2024-05-30 13:45:00', NULL, 'rejected'),
(14, 'New Textile Ventures', 'info@newtextile.ug', 'supplier13.jpg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2025-07-01 07:00:00', '2025-07-01 07:00:00', '2025-07-10 05:30:00', 'pending'),
(15, 'Modern Fabrics Uganda', 'contact@modernfabrics.com', 'supplier14.jpg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2025-07-05 11:00:00', '2025-07-05 11:00:00', '2025-07-10 09:15:00', 'pending'),
(16, 'Premium Cotton Suppliers', 'sales@premiumcotton.ug', 'supplier15.jpg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NULL, '2025-07-08 13:30:00', '2025-07-08 13:30:00', '2025-07-10 12:45:00', 'pending'),
(17, 'Kampala Fashion Wholesale', 'orders@kampalafashion.ug', 'wholesaler1.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 10:45:00', 'approved'),
(18, 'East Africa Textile Wholesale', 'east@eatw.com', 'wholesaler2.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 12:10:00', 'approved'),
(19, 'Owino Market Distributors', 'info@owinodist.ug', 'wholesaler3.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 08:20:00', 'approved'),
(20, 'Nakasero Fashion Hub', 'sales@nakaserofashion.com', 'wholesaler4.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 11:30:00', 'approved'),
(21, 'Jinja Wholesale Centre', 'wholesale@jinjacenter.ug', 'wholesaler5.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 07:45:00', 'approved'),
(22, 'Mbarara Clothing Market', 'orders@mbararaclothing.com', 'wholesaler6.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 13:15:00', 'approved'),
(23, 'Gulu Fashion Distributors', 'info@gulufashion.ug', 'wholesaler7.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 06:30:00', 'approved'),
(24, 'Masaka Trade Center', 'sales@masakatrade.com', 'wholesaler8.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 09:50:00', 'approved'),
(25, 'Lira Commercial Hub', 'wholesale@liracommercial.ug', 'wholesaler9.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 12:25:00', 'approved'),
(26, 'Mbale Fashion Market', 'contact@mbalefashion.com', 'wholesaler10.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 08:40:00', 'approved'),
(27, 'Kasese Wholesale Plaza', 'orders@kaseseplaza.ug', 'wholesaler11.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 10:15:00', 'approved'),
(28, 'Soroti Trading Post', 'info@sorotitrading.com', 'wholesaler12.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 11:55:00', 'approved'),
(29, 'Arua Fashion Wholesale', 'sales@aruafashion.ug', 'wholesaler13.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 07:20:00', 'approved'),
(30, 'Fort Portal Distributors', 'wholesale@fortportal.com', 'wholesaler14.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 13:40:00', 'approved'),
(31, 'Hoima Commercial Center', 'orders@hoimacommercial.ug', 'wholesaler15.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 09:10:00', 'approved'),
(32, 'Kabale Fashion Hub', 'info@kabalefashion.com', 'wholesaler16.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 06:55:00', 'approved'),
(33, 'Mityana Trade Plaza', 'sales@mityanaplaza.ug', 'wholesaler17.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 11:05:00', 'approved'),
(34, 'Mukono Fashion Market', 'wholesale@mukonofashion.com', 'wholesaler18.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 08:35:00', 'approved'),
(35, 'Entebbe Wholesale Centre', 'contact@entebbewhol esale.ug', 'wholesaler19.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 12:50:00', 'approved'),
(36, 'Wakiso Fashion Distributors', 'orders@wakisofashion.com', 'wholesaler20.jpg', '2024-01-01 07:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-01-01 07:00:00', '2024-01-01 07:00:00', '2025-07-10 10:30:00', 'approved'),
(37, 'Poor Credit Wholesale', 'bad@credit.com', 'wholesaler21.jpg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-03-01 07:00:00', '2024-03-15 11:00:00', NULL, 'rejected'),
(38, 'Unreliable Distributors', 'unreliable@dist.ug', 'wholesaler22.jpg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2024-04-10 08:00:00', '2024-04-25 13:30:00', NULL, 'rejected'),
(39, 'New Market Ventures', 'info@newmarket.ug', 'wholesaler23.jpg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2025-07-02 06:00:00', '2025-07-02 06:00:00', '2025-07-10 07:30:00', 'pending'),
(40, 'Fresh Fashion Wholesale', 'sales@freshfashion.com', 'wholesaler24.jpg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2025-07-06 11:30:00', '2025-07-06 11:30:00', '2025-07-10 13:20:00', 'pending'),
(41, 'Modern Trade Solutions', 'contact@moderntrade.ug', 'wholesaler25.jpg', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wholesaler', NULL, '2025-07-09 08:15:00', '2025-07-09 08:15:00', '2025-07-10 11:45:00', 'pending');

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
(1, 1, 'Nakawa Industrial Area, Kampala', 10000, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(2, 1, 'Jinja Distribution Center, Jinja', 7500, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(3, 1, 'Mbarara Regional Hub, Mbarara', 6000, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(4, 1, 'Gulu Northern Branch, Gulu', 5000, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(5, 1, 'Masaka Central Store, Masaka', 4500, '2024-01-01 07:00:00', '2024-01-01 07:00:00');

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
(1, 17, 'Owino Market, Stall 45-50, Kampala, Uganda', '+256708901234', 'wholesale_license_001.pdf', 'documents/wholesale_license_001.pdf', 'Retail Distribution', '[\"Clothing\", \"Accessories\"]', 2000, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(2, 18, 'Nakasero Market, Block A, Kampala, Uganda', '+256709012345', 'wholesale_license_002.pdf', 'documents/wholesale_license_002.pdf', 'Bulk Sales', '[\"Clothing\", \"Textiles\"]', 1500, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(3, 19, 'Owino Market, Section B, Kampala, Uganda', '+256710123456', 'wholesale_license_003.pdf', 'documents/wholesale_license_003.pdf', 'Market Distribution', '[\"Casual Wear\", \"Formal Wear\"]', 1800, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(4, 20, 'Nakasero Fashion District, Kampala, Uganda', '+256711234567', 'wholesale_license_004.pdf', 'documents/wholesale_license_004.pdf', 'Fashion Retail', '[\"Business Attire\", \"Designer Wear\"]', 1200, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(5, 21, 'Main Street, Commercial Center, Jinja, Uganda', '+256712345678', 'wholesale_license_005.pdf', 'documents/wholesale_license_005.pdf', 'Regional Distribution', '[\"All Categories\"]', 1600, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(6, 22, 'High Street, Trading Plaza, Mbarara, Uganda', '+256713456789', 'wholesale_license_006.pdf', 'documents/wholesale_license_006.pdf', 'Retail Chain', '[\"Casual Wear\", \"Work Uniforms\"]', 1400, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(7, 23, 'Commercial District, Market Square, Gulu, Uganda', '+256714567890', 'wholesale_license_007.pdf', 'documents/wholesale_license_007.pdf', 'Regional Wholesale', '[\"Affordable Clothing\", \"Basic Wear\"]', 1000, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(8, 24, 'Central Market, Trading Hub, Masaka, Uganda', '+256715678901', 'wholesale_license_008.pdf', 'documents/wholesale_license_008.pdf', 'Market Retail', '[\"Casual Wear\", \"Accessories\"]', 900, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(9, 25, 'Commercial Road, Business Center, Lira, Uganda', '+256716789012', 'wholesale_license_009.pdf', 'documents/wholesale_license_009.pdf', 'Bulk Distribution', '[\"Work Wear\", \"Uniforms\"]', 1100, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(10, 26, 'Fashion Street, Market Plaza, Mbale, Uganda', '+256717890123', 'wholesale_license_010.pdf', 'documents/wholesale_license_010.pdf', 'Fashion Wholesale', '[\"Youth Fashion\", \"Trendy Wear\"]', 800, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(11, 27, 'Trading Center, Commercial Zone, Kasese, Uganda', '+256718901234', 'wholesale_license_011.pdf', 'documents/wholesale_license_011.pdf', 'Regional Trading', '[\"Basic Clothing\", \"Work Wear\"]', 700, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(12, 28, 'Market Square, Business District, Soroti, Uganda', '+256719012345', 'wholesale_license_012.pdf', 'documents/wholesale_license_012.pdf', 'Local Distribution', '[\"Casual Wear\", \"Traditional Wear\"]', 600, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(13, 29, 'Commercial Street, Trading Post, Arua, Uganda', '+256720123456', 'wholesale_license_013.pdf', 'documents/wholesale_license_013.pdf', 'Border Trade', '[\"All Categories\"]', 1300, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(14, 30, 'Central Plaza, Business Hub, Fort Portal, Uganda', '+256721234567', 'wholesale_license_014.pdf', 'documents/wholesale_license_014.pdf', 'Regional Retail', '[\"Formal Wear\", \"Business Attire\"]', 950, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(15, 31, 'Trading Avenue, Commercial Center, Hoima, Uganda', '+256722345678', 'wholesale_license_015.pdf', 'documents/wholesale_license_015.pdf', 'Wholesale Distribution', '[\"Industrial Wear\", \"Safety Clothing\"]', 850, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(16, 32, 'Market Road, Fashion District, Kabale, Uganda', '+256723456789', 'wholesale_license_016.pdf', 'documents/wholesale_license_016.pdf', 'Fashion Retail', '[\"Winter Wear\", \"Warm Clothing\"]', 750, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(17, 33, 'Commercial Plaza, Trading Center, Mityana, Uganda', '+256724567890', 'wholesale_license_017.pdf', 'documents/wholesale_license_017.pdf', 'Local Wholesale', '[\"Casual Wear\", \"Everyday Clothing\"]', 650, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(18, 34, 'Business Park, Market Square, Mukono, Uganda', '+256725678901', 'wholesale_license_018.pdf', 'documents/wholesale_license_018.pdf', 'Suburban Retail', '[\"Student Wear\", \"Youth Fashion\"]', 1050, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(19, 35, 'Airport Road, Commercial Zone, Entebbe, Uganda', '+256726789012', 'wholesale_license_019.pdf', 'documents/wholesale_license_019.pdf', 'Tourist Retail', '[\"Casual Wear\", \"Travel Clothing\"]', 1250, '2024-01-01 07:00:00', '2024-01-01 07:00:00'),
(20, 36, 'Central Market, Trading Hub, Wakiso, Uganda', '+256727890123', 'wholesale_license_020.pdf', 'documents/wholesale_license_020.pdf', 'Metropolitan Distribution', '[\"All Categories\"]', 1750, '2024-01-01 07:00:00', '2024-01-01 07:00:00');

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

-- --------------------------------------------------------

--
-- Structure for view `ml_demand_data`
--
DROP TABLE IF EXISTS `ml_demand_data`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ml_demand_data`  AS SELECT `i`.`name` AS `product_name`, CASE WHEN `si`.`id` MOD 20 = 1 THEN 'Kampala' WHEN `si`.`id` MOD 20 = 2 THEN 'Wakiso' WHEN `si`.`id` MOD 20 = 3 THEN 'Jinja' WHEN `si`.`id` MOD 20 = 4 THEN 'Mbarara' WHEN `si`.`id` MOD 20 = 5 THEN 'Gulu' WHEN `si`.`id` MOD 20 = 6 THEN 'Lira' WHEN `si`.`id` MOD 20 = 7 THEN 'Mbale' WHEN `si`.`id` MOD 20 = 8 THEN 'Kasese' WHEN `si`.`id` MOD 20 = 9 THEN 'Soroti' WHEN `si`.`id` MOD 20 = 10 THEN 'Masaka' WHEN `si`.`id` MOD 20 = 11 THEN 'Mityana' WHEN `si`.`id` MOD 20 = 12 THEN 'Mukono' WHEN `si`.`id` MOD 20 = 13 THEN 'Mpigi' WHEN `si`.`id` MOD 20 = 14 THEN 'Rakai' WHEN `si`.`id` MOD 20 = 15 THEN 'Bushenyi' WHEN `si`.`id` MOD 20 = 16 THEN 'Nakasongola' WHEN `si`.`id` MOD 20 = 17 THEN 'Kalangala' WHEN `si`.`id` MOD 20 = 18 THEN 'Kayunga' WHEN `si`.`id` MOD 20 = 19 THEN 'Luwero' ELSE 'Sembabule' END AS `location`, `si`.`delivery_date` AS `sales_date`, `si`.`price` AS `unit_price`, `si`.`delivered_quantity` AS `units_sold` FROM (`supplied_items` `si` join `items` `i` on(`si`.`item_id` = `i`.`id`)) WHERE `si`.`status` = 'delivered' AND `i`.`type` = 'finished_product' ORDER BY `si`.`delivery_date` ASC ;

--
-- Indexes for dumped tables
--

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `pending_users`
--
ALTER TABLE `pending_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supply_requests`
--
ALTER TABLE `supply_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `warehouse_workforce`
--
ALTER TABLE `warehouse_workforce`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wholesalers`
--
ALTER TABLE `wholesalers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `workforces`
--
ALTER TABLE `workforces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
