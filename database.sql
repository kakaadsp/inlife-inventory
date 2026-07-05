-- Inlife Inventory Database Dump
-- Generated: 2026-07-04 16:33:02

SET FOREIGN_KEY_CHECKS=0;

-- ------------------------------------------------------
-- Table structure for table `borrowing_details`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `borrowing_details`;
CREATE TABLE `borrowing_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `borrowing_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL DEFAULT '1',
  `condition_before` enum('good','fair','damaged') NOT NULL DEFAULT 'good',
  `condition_after` enum('good','fair','damaged') DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowing_details_borrowing_id_item_id_index` (`borrowing_id`,`item_id`),
  KEY `borrowing_details_item_id_index` (`item_id`),
  CONSTRAINT `borrowing_details_borrowing_id_foreign` FOREIGN KEY (`borrowing_id`) REFERENCES `borrowings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `borrowing_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `borrowing_details`
INSERT INTO `borrowing_details` (`id`, `borrowing_id`, `item_id`, `quantity`, `condition_before`, `condition_after`, `notes`, `created_at`, `updated_at`) VALUES
('1', '1', '1', '1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('2', '1', '2', '1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('3', '2', '8', '1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('4', '2', '17', '2', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('5', '3', '4', '1', 'fair', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('6', '4', '19', '1', 'good', 'good', NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('7', '5', '13', '2', 'good', 'good', NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('8', '5', '14', '1', 'good', 'good', NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('9', '6', '17', '1', 'good', NULL, NULL, '2026-07-04 16:12:45', '2026-07-04 16:12:45'),
('10', '6', '19', '1', 'good', NULL, NULL, '2026-07-04 16:12:45', '2026-07-04 16:12:45');

-- ------------------------------------------------------
-- Table structure for table `borrowings`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `borrowings`;
CREATE TABLE `borrowings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint unsigned NOT NULL,
  `returned_by` bigint unsigned DEFAULT NULL,
  `borrowing_code` varchar(30) NOT NULL COMMENT 'BRW-{YYYYMMDD}-{0001}',
  `borrower_name` varchar(200) NOT NULL,
  `borrower_department` varchar(200) DEFAULT NULL,
  `borrower_phone` varchar(20) DEFAULT NULL,
  `borrower_email` varchar(100) DEFAULT NULL,
  `borrow_date` date NOT NULL,
  `expected_return_date` date NOT NULL,
  `actual_return_date` date DEFAULT NULL,
  `status` enum('borrowed','returned','overdue') NOT NULL DEFAULT 'borrowed',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `borrowings_borrowing_code_unique` (`borrowing_code`),
  KEY `borrowings_created_by_foreign` (`created_by`),
  KEY `borrowings_returned_by_foreign` (`returned_by`),
  KEY `borrowings_status_index` (`status`),
  KEY `borrowings_borrow_date_index` (`borrow_date`),
  KEY `borrowings_expected_return_date_index` (`expected_return_date`),
  KEY `borrowings_borrower_name_index` (`borrower_name`),
  CONSTRAINT `borrowings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `borrowings_returned_by_foreign` FOREIGN KEY (`returned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `borrowings`
INSERT INTO `borrowings` (`id`, `created_by`, `returned_by`, `borrowing_code`, `borrower_name`, `borrower_department`, `borrower_phone`, `borrower_email`, `borrow_date`, `expected_return_date`, `actual_return_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
('1', '2', NULL, 'BRW-20260610-0001', 'Rizki Pratama', 'Divisi Marketing', '081234500001', NULL, '2026-06-10', '2026-07-10', NULL, 'borrowed', NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('2', '3', NULL, 'BRW-20260615-0001', 'Siti Rahayu', 'Divisi HR', '082345600001', NULL, '2026-06-15', '2026-07-15', NULL, 'borrowed', NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('3', '2', NULL, 'BRW-20260618-0001', 'Ahmad Fauzi', 'Divisi IT', '083456700001', NULL, '2026-06-18', '2026-07-05', NULL, 'overdue', NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('4', '2', '2', 'BRW-20260520-0001', 'Dewi Lestari', 'Divisi Finance', '084567800001', NULL, '2026-05-20', '2026-06-01', '2026-06-01', 'returned', NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('5', '3', '3', 'BRW-20260525-0001', 'Hendra Kusuma', 'Divisi Operations', '085678900001', NULL, '2026-05-25', '2026-06-05', '2026-06-03', 'returned', NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('6', '1', NULL, 'BRW-20260704-0001', 'Kaka Dimas Soehendra Putra', 'IT Division', '085732101644', 'kaka@inlife.co.id', '2026-07-04', '2026-07-13', NULL, 'borrowed', '- Jangan dibanting', '2026-07-04 16:12:45', '2026-07-04 16:12:45');

-- ------------------------------------------------------
-- Table structure for table `cache`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `cache`
-- ------------------------------------------------------
-- Table structure for table `cache_locks`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `cache_locks`
-- ------------------------------------------------------
-- Table structure for table `categories`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL COMMENT 'Kode singkat: ELK, PKN, FRN',
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `categories`
INSERT INTO `categories` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', 'ELK', 'Elektronik', 'Perangkat elektronik seperti laptop, monitor, printer, dll.', '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('2', 'PKN', 'Peralatan Kantor', 'Peralatan dan perlengkapan kebutuhan kantor sehari-hari.', '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('3', 'FRN', 'Furnitur', 'Meja, kursi, lemari, dan perabot kantor lainnya.', '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('4', 'KMN', 'Komunikasi', 'Perangkat komunikasi seperti telepon, headset, radio, dll.', '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('5', 'LIN', 'Lainnya', 'Barang inventaris yang tidak termasuk kategori di atas.', '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL);

-- ------------------------------------------------------
-- Table structure for table `failed_jobs`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `failed_jobs`
-- ------------------------------------------------------
-- Table structure for table `items`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `code` varchar(30) NOT NULL COMMENT 'INV-{CAT_CODE}-{0001}',
  `name` varchar(200) NOT NULL,
  `description` text,
  `stock` int unsigned NOT NULL DEFAULT '0',
  `min_stock` int unsigned NOT NULL DEFAULT '5' COMMENT 'Low stock threshold',
  `location` varchar(200) DEFAULT NULL,
  `condition` enum('good','fair','damaged') NOT NULL DEFAULT 'good',
  `image` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_code_unique` (`code`),
  KEY `items_created_by_foreign` (`created_by`),
  KEY `items_updated_by_foreign` (`updated_by`),
  KEY `items_stock_index` (`stock`),
  KEY `items_condition_index` (`condition`),
  KEY `items_category_id_index` (`category_id`),
  CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `items_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `items_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `items`
INSERT INTO `items` (`id`, `category_id`, `created_by`, `updated_by`, `code`, `name`, `description`, `stock`, `min_stock`, `location`, `condition`, `image`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', '1', '1', NULL, 'INV-ELK-0001', 'Laptop Lenovo ThinkPad X1 Carbon', NULL, '8', '3', 'Gudang A - Rak 1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('2', '1', '1', NULL, 'INV-ELK-0002', 'Monitor LG UltraWide 34 inch', NULL, '5', '2', 'Gudang A - Rak 1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('3', '1', '1', NULL, 'INV-ELK-0003', 'Printer Canon PIXMA G2020', NULL, '3', '2', 'Gudang A - Rak 2', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('4', '1', '1', NULL, 'INV-ELK-0004', 'Proyektor Epson EB-X51', NULL, '2', '1', 'Gudang A - Rak 2', 'fair', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('5', '1', '2', NULL, 'INV-ELK-0005', 'UPS APC Smart 1500VA', NULL, '4', '2', 'Gudang B - Rak 1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('6', '1', '2', NULL, 'INV-ELK-0006', 'Mouse Wireless Logitech MX Master', NULL, '2', '5', 'Gudang B - Rak 2', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('7', '1', '2', NULL, 'INV-ELK-0007', 'Keyboard Mechanical Keychron K2', NULL, '1', '3', 'Gudang B - Rak 2', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('8', '1', '2', NULL, 'INV-ELK-0008', 'Webcam Logitech C920 HD Pro', NULL, '6', '2', 'Gudang B - Rak 3', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('9', '2', '1', NULL, 'INV-PKN-0001', 'Stapler Besar Kangaro HD-23', NULL, '10', '3', 'Gudang C - Rak 1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('10', '2', '1', NULL, 'INV-PKN-0002', 'Penghancur Kertas Fujika FJ-888', NULL, '3', '2', 'Gudang C - Rak 1', 'fair', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('11', '2', '2', NULL, 'INV-PKN-0003', 'Whiteboard 120x180 cm', NULL, '4', '2', 'Gudang C - Rak 2', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('12', '2', '2', NULL, 'INV-PKN-0004', 'Extension Kabel 5 Meter 6 Lubang', NULL, '1', '4', 'Gudang C - Rak 2', 'damaged', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('13', '3', '1', NULL, 'INV-FRN-0001', 'Kursi Ergonomis Herman Miller', NULL, '5', '2', 'Gudang D - Bagian 1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('14', '3', '1', NULL, 'INV-FRN-0002', 'Meja Rapat Oval 8 Orang', NULL, '2', '1', 'Gudang D - Bagian 1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('15', '3', '2', NULL, 'INV-FRN-0003', 'Lemari Arsip 4 Laci', NULL, '3', '1', 'Gudang D - Bagian 2', 'fair', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('16', '4', '1', NULL, 'INV-KMN-0001', 'Telephone IP Cisco 7945G', NULL, '7', '3', 'Gudang E - Rak 1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('17', '4', '1', NULL, 'INV-KMN-0002', 'Headset Plantronics CS540', NULL, '3', '3', 'Gudang E - Rak 1', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-04 16:12:45', NULL),
('18', '4', '2', NULL, 'INV-KMN-0003', 'Router WiFi TP-Link Archer AX50', NULL, '3', '1', 'Gudang E - Rak 2', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL),
('19', '5', '1', NULL, 'INV-LIN-0001', 'Kamera Mirrorless Sony A6400', NULL, '1', '1', 'Brankas Utama', 'good', NULL, NULL, '2026-07-03 07:23:11', '2026-07-04 16:12:45', NULL),
('20', '5', '1', NULL, 'INV-LIN-0002', 'Tripod Manfrotto MT190', NULL, '1', '1', 'Brankas Utama', 'fair', NULL, NULL, '2026-07-03 07:23:11', '2026-07-03 07:23:11', NULL);

-- ------------------------------------------------------
-- Table structure for table `job_batches`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `job_batches`
-- ------------------------------------------------------
-- Table structure for table `jobs`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `jobs`
-- ------------------------------------------------------
-- Table structure for table `migrations`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
('1', '0000_01_01_000000_create_roles_table', '1'),
('2', '0001_01_01_000000_create_users_table', '1'),
('3', '0001_01_01_000001_create_cache_table', '1'),
('4', '0001_01_01_000002_create_jobs_table', '1'),
('5', '2024_01_01_000000_create_roles_table', '1'),
('6', '2024_01_01_000001_create_categories_table', '1'),
('7', '2024_01_01_000002_create_items_table', '1'),
('8', '2024_01_01_000003_create_borrowings_table', '1'),
('9', '2024_01_01_000004_create_borrowing_details_table', '1'),
('10', '2024_01_01_000005_create_settings_table', '1'),
('11', '2024_01_01_000006_create_personal_access_tokens_table', '2');

-- ------------------------------------------------------
-- Table structure for table `password_reset_tokens`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `password_reset_tokens`
-- ------------------------------------------------------
-- Table structure for table `personal_access_tokens`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `personal_access_tokens`
-- ------------------------------------------------------
-- Table structure for table `roles`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'slug: admin, staff, manager',
  `display_name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `roles`
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
('1', 'admin', 'Administrator', 'Akses penuh ke seluruh sistem', '2026-07-03 07:23:09', '2026-07-03 07:23:09'),
('2', 'staff', 'Staff Inventaris', 'Kelola barang dan transaksi peminjaman', '2026-07-03 07:23:09', '2026-07-03 07:23:09'),
('3', 'manager', 'Manager', 'Akses laporan dan dashboard (read-only)', '2026-07-03 07:23:09', '2026-07-03 07:23:09');

-- ------------------------------------------------------
-- Table structure for table `sessions`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `sessions`
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1lg7qdwIO9QDOC8oSdRoslkwAJ1LE2pnoHsxBfvh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidWpEckFBWjB6R1M5QXJXSG1IdXI5VnJZRjY4RFlLTml4QUlsSWd5SiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2JvcnJvd2luZ3MvaGlzdG9yeS9hbGwiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2JvcnJvd2luZ3MvaGlzdG9yeS9hbGwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1783155275'),
('vOMiAs4aksQmWLFbY5Ry8ZSvLiBB0gCb14Ea7BWW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSGg4cVV3OTJwOWt2MGF4RzhxaXdwWkVWUXROZVI3WXBVWE92M1dQZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=', '1783157476');

-- ------------------------------------------------------
-- Table structure for table `settings`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `settings`
INSERT INTO `settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES
('1', 'app_name', 'Inlife Inventory', 'Nama aplikasi yang ditampilkan di sistem', '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('2', 'company_name', 'Inlife', 'Nama perusahaan', '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('3', 'app_version', '1.0.0', 'Versi aplikasi saat ini', '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('4', 'low_stock_threshold', '5', 'Batas minimum stok sebelum alert ditampilkan', '2026-07-03 07:23:11', '2026-07-03 07:23:11'),
('5', 'items_per_page', '15', 'Jumlah data per halaman pada tabel', '2026-07-03 07:23:11', '2026-07-03 07:23:11');

-- ------------------------------------------------------
-- Table structure for table `users`
-- ------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_is_active_index` (`is_active`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `avatar`, `phone`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', '1', 'Administrator Inlife', 'admin@inlife.co.id', NULL, '$2y$12$CbR4NF2E57tSo5mKJp04G.33ZDwTDjNkULpGFuu7HkpyTlyk9DUj2', NULL, '081234567890', '1', NULL, '2026-07-03 07:23:11', '2026-07-04 12:47:47', NULL),
('2', '2', 'Budi Santoso', 'staff@inlife.co.id', NULL, '$2y$12$KiTKlhHtJJ.C8d6BHi4oH.m7MM5nNIsGTbd2AtwEdAyzLiLpanUWC', NULL, '081298765432', '1', NULL, '2026-07-03 07:23:11', '2026-07-04 12:47:47', NULL),
('3', '2', 'Sari Dewi', 'staff2@inlife.co.id', NULL, '$2y$12$Jc/gywkeCZxW5xhQEEyd9.ixIyCSWkHxhUX10FQwq6lyI7ELTh9KW', NULL, '082112345678', '1', NULL, '2026-07-03 07:23:11', '2026-07-04 12:47:47', NULL),
('4', '3', 'Andi Wijaya', 'manager@inlife.co.id', NULL, '$2y$12$maN8GPbkU/yz/JW/jcKIYeEIRp1hO4iRFXo/sqmcY82R5BAtBlHNq', NULL, '081356789012', '1', NULL, '2026-07-03 07:23:11', '2026-07-04 12:47:47', NULL);

SET FOREIGN_KEY_CHECKS=1;
