-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 06, 2026 at 04:06 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `einvoice_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` int NOT NULL,
  `company_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `company_name`, `company_address`, `company_phone`, `company_email`, `company_logo`, `created_at`, `updated_at`) VALUES
(3, 'PT. TAJ WEB-DEV', 'Jl. Venezuela Oklahoma Blok C1 No. 10969', '082250504040', 'tajwebdev@gmail.com', 'uploads/company/logo_1769133638.png', '2026-01-22 19:00:38', '2026-02-01 22:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint NOT NULL,
  `customer_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci,
  `customer_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_name`, `customer_address`, `customer_email`, `customer_phone`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PT. TEGAP MITRA NUSANTARA', 'Desa Barunang', 'tegapmns@miningcoal.co.id', '082240401010', 1, '2026-01-21 00:46:50', '2026-01-22 00:31:02'),
(2, 'CV. KEDAI ANANG PERSADA', 'Jl. G. Obos Induk', 'kedaianang@persada.com', '085230304040', 1, '2026-01-21 00:56:50', '2026-01-21 01:03:26'),
(5, 'PT. SUSANTRI PERMAI', 'Desa Sei Hanyo', 'aseborneo@genting.com', '0812303040', 1, '2026-01-22 00:31:37', '2026-01-22 00:32:06'),
(6, 'PT. DWIE WARNA KARYA', 'Desa Sei Hanyo', 'dwkborneo@gmail.com', '08113054611', 1, '2026-01-22 00:51:17', NULL),
(15, 'PT. KAPUAS MAJU JAYA', 'Desa Sei Hanyo', 'kmjborneo@genting.com', '081350506060', 1, '2026-01-22 00:56:41', NULL),
(16, 'CV. BORNEO SMART SOLUTION', 'Jl. Wortel', 'cvbss@gmail.com', '082341412233', 1, '2026-01-22 00:57:14', NULL),
(17, 'PT. PAMA PERSADA NUSANTARA', 'Desa Barunang', 'pamapersada@mining.coal.co.id', '082120203030', 1, '2026-01-22 00:57:47', NULL),
(18, 'PT. KALIMANTAN PRIMA PERSADA', 'Desa Barunang', 'kppmining@coal.co.id', '083190908080', 1, '2026-01-22 00:58:27', '2026-01-27 00:06:12');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('PAID','UNPAID') COLLATE utf8mb4_unicode_ci DEFAULT 'UNPAID',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_ppn` decimal(15,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `customer_id`, `invoice_date`, `due_date`, `status`, `notes`, `subtotal`, `tax_ppn`, `grand_total`, `created_by`, `created_at`, `updated_at`) VALUES
(17, '0002/TAJWDV/I/2026', 1, '2026-01-23', '2026-02-23', 'PAID', 'OK - TMN', 38550000.00, 4240500.00, 42790500.00, 1, '2026-01-22 22:51:13', '2026-01-26 19:18:51'),
(18, '0003/TAJWDV/I/2026', 17, '2026-01-24', '2026-02-24', 'PAID', 'OK - PAMA', 33000000.00, 3630000.00, 36630000.00, 1, '2026-01-22 22:57:25', '2026-01-25 19:59:07'),
(19, '0004/TAJWDV/I/2026', 18, '2026-01-26', '2026-02-26', 'PAID', 'OK - KPP', 60450000.00, 6649500.00, 67099500.00, 1, '2026-01-22 23:24:01', '2026-01-25 20:27:16'),
(20, '0005/TAJWDV/I/2026', 2, '2026-01-26', '2026-02-26', 'PAID', 'OK - ANANG PERSADA DIAW', 8500000.00, 935000.00, 9435000.00, 1, '2026-01-25 21:40:30', '2026-01-27 18:46:03'),
(21, '0006/TAJWDV/I/2026', 6, '2026-01-27', '2026-02-27', 'PAID', 'OK - PT. DWK - UPGRADE PC FULL ENCHANTED SPEK DEWA ALMIGHTY', 106800000.00, 11748000.00, 118548000.00, 1, '2026-01-26 22:52:54', '2026-02-01 18:49:58'),
(22, '0007/TAJWDV/I/2026', 16, '2026-01-28', '2026-02-28', 'PAID', 'OK - CVBSS', 1150000.00, 126500.00, 1276500.00, 1, '2026-01-27 19:04:29', '2026-02-01 18:49:46'),
(23, '0008/TAJWDV/II/2026', 15, '2026-02-02', '2026-03-02', 'PAID', 'OK - KMJ', 1965000.00, 216150.00, 2181150.00, 1, '2026-02-01 18:53:14', '2026-02-01 19:02:34'),
(24, '0009/TAJWDV/II/2026', 15, '2026-02-02', '2026-03-02', 'PAID', 'OK - KMJ', 750000.00, 82500.00, 832500.00, 1, '2026-02-01 18:55:10', '2026-02-01 19:02:44'),
(25, '0010/TAJWDV/II/2026', 5, '2026-02-04', '2026-03-04', 'PAID', 'OK - ASE', 35500000.00, 3905000.00, 39405000.00, 1, '2026-02-01 18:58:02', '2026-02-02 00:14:38'),
(26, '0011/TAJWDV/II/2026', 5, '2026-02-04', '2026-03-04', 'PAID', 'OK - ASE', 4950000.00, 544500.00, 5494500.00, 1, '2026-02-01 18:59:47', '2026-02-02 00:14:30'),
(27, '0012/TAJWDV/II/2026', 6, '2026-02-05', '2026-03-05', 'UNPAID', 'OK - DWK', 12500000.00, 1375000.00, 13875000.00, 1, '2026-02-01 19:01:54', '2026-02-01 19:05:57'),
(28, '0013/TAJWDV/II/2026', 6, '2026-02-05', '2026-03-05', 'UNPAID', 'OK - DWK', 99250000.00, 10917500.00, 110167500.00, 1, '2026-02-01 19:05:42', '2026-02-01 22:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_counters`
--

CREATE TABLE `invoice_counters` (
  `id` int NOT NULL,
  `year` int NOT NULL,
  `last_number` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_counters`
--

INSERT INTO `invoice_counters` (`id`, `year`, `last_number`) VALUES
(16, 2026, 13);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `item_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `item_description`, `quantity`, `unit`, `price`, `total_price`, `created_at`) VALUES
(5, 17, 'LAPTOP GAMING HP', 3.00, 'UNI', 12500000.00, 37500000.00, '2026-01-22 23:14:25'),
(6, 17, 'MOUSE GAMING HP', 3.00, 'PCS', 350000.00, 1050000.00, '2026-01-22 23:14:25'),
(29, 18, 'JASA WEBSITE SYSTEM ABSENSI', 1.00, 'NULL', 15000000.00, 15000000.00, '2026-01-23 01:40:09'),
(30, 18, 'JASA WEBSITE SYSTEM PAYROLL', 1.00, 'NULL', 18000000.00, 18000000.00, '2026-01-23 01:40:09'),
(35, 19, 'PC GAMING SUPER', 3.00, 'UNI', 18000000.00, 54000000.00, '2026-01-25 19:55:44'),
(36, 19, 'KEYBOARD GAMING SUPER', 3.00, 'UNI', 350000.00, 1050000.00, '2026-01-25 19:55:44'),
(37, 19, 'MOUSE GAMING SUPER', 3.00, 'PCS', 300000.00, 900000.00, '2026-01-25 19:55:44'),
(38, 19, 'MONITOR GAMING SUPER', 3.00, 'UNI', 1500000.00, 4500000.00, '2026-01-25 19:55:44'),
(63, 21, 'RAM 64 GB DDR 5', 8.00, 'PCS', 850000.00, 6800000.00, '2026-01-26 22:54:21'),
(64, 21, 'SSD 2 TB', 8.00, 'PCS', 2500000.00, 20000000.00, '2026-01-26 22:54:21'),
(65, 21, 'CHIPSET INTEL CORE i9 Gen 20 10.0 Ghz', 8.00, 'PCS', 5500000.00, 44000000.00, '2026-01-26 22:54:21'),
(66, 21, 'VGA ON BOARD - PREDATOR AMD Z100', 8.00, 'UNI', 4500000.00, 36000000.00, '2026-01-26 22:54:21'),
(67, 20, 'MEJA MAKAN PREMIUM', 10.00, 'UNI', 500000.00, 5000000.00, '2026-01-27 00:07:17'),
(68, 20, 'KURSI MAKAN PREMIUM', 10.00, 'UNI', 350000.00, 3500000.00, '2026-01-27 00:07:17'),
(77, 22, 'PULPEN SNOWMAN V.5 BLACK', 5.00, 'KTK', 55000.00, 275000.00, '2026-01-27 19:07:12'),
(78, 22, 'PULPEN SNOWMAN V.5 BLUE', 5.00, 'KTK', 55000.00, 275000.00, '2026-01-27 19:07:12'),
(79, 22, 'PULPEN SNOWMAN V.5 GREEN', 5.00, 'KTK', 60000.00, 300000.00, '2026-01-27 19:07:12'),
(80, 22, 'PULPEN SNOWMAN V.5 RED', 5.00, 'KTK', 60000.00, 300000.00, '2026-01-27 19:07:12'),
(81, 23, 'PAPAN TULIS SPIDOL', 3.00, 'UNI', 650000.00, 1950000.00, '2026-02-01 18:53:14'),
(82, 23, 'PENGHAPUS PAPAN TULIS SPIDOL', 3.00, 'PCS', 5000.00, 15000.00, '2026-02-01 18:53:14'),
(87, 24, 'SPIDOL PERMANENT SNOWMAN BLACK', 3.00, 'KTK', 60000.00, 180000.00, '2026-02-01 18:55:15'),
(88, 24, 'SPIDOL PERMANENT SNOWMAN BLUE', 3.00, 'KTK', 60000.00, 180000.00, '2026-02-01 18:55:15'),
(89, 24, 'SPIDOL PERMANENT SNOWMAN GREEN', 3.00, 'KTK', 65000.00, 195000.00, '2026-02-01 18:55:15'),
(90, 24, 'SPIDOL PERMANENT SNOWMAN RED', 3.00, 'KTK', 65000.00, 195000.00, '2026-02-01 18:55:15'),
(96, 26, 'KONEKTOR RJ 45 CAT 6', 10.00, 'PACK', 350000.00, 3500000.00, '2026-02-01 19:00:14'),
(97, 26, 'TANG CRIMPING', 10.00, 'PCS', 85000.00, 850000.00, '2026-02-01 19:00:14'),
(98, 26, 'CUTTER KABEL LAN', 10.00, 'PCS', 60000.00, 600000.00, '2026-02-01 19:00:14'),
(100, 25, 'HUB 100/1000 TP - LINK', 10.00, 'PCS', 1250000.00, 12500000.00, '2026-02-01 19:03:00'),
(101, 25, 'KABEL LAN CAT 6 BELDEN 305 MTR', 10.00, 'ROLL', 2300000.00, 23000000.00, '2026-02-01 19:03:00'),
(106, 27, 'RAK SERVER', 5.00, 'UNI', 2500000.00, 12500000.00, '2026-02-01 19:05:57'),
(115, 28, 'PC SERVER SPEK DEWA', 5.00, 'UNI', 18000000.00, 90000000.00, '2026-02-01 22:45:02'),
(116, 28, 'MONITOR SERVER', 5.00, 'UNI', 1250000.00, 6250000.00, '2026-02-01 22:45:02'),
(117, 28, 'MOUSE SERVER', 5.00, 'PCS', 250000.00, 1250000.00, '2026-02-01 22:45:02'),
(118, 28, 'KEYBOARD SERVER', 5.00, 'UNI', 350000.00, 1750000.00, '2026-02-01 22:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'finance');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `role_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tertu Akikkuti Jordan', 'tertu', '$2y$12$OtjWU9rCs0rYrzqI27RVUeLNV6K16I1GtPlNMFubgxJ9qMkPrBu9C', 1, 1, '2026-01-20 21:04:09', NULL),
(2, 'Andrini Anugrahi Sinta Leluni', 'andrini', '$2y$12$Hin5bZNAXwdAA4tXQ8Cdlud2S49tQQ0HV3R.NzbyntZB9Wmn1oaU.', 2, 1, '2026-01-20 22:49:23', NULL),
(3, 'Kenneth Carl Tadeo', 'yue', '$2y$12$VY8LdONMCN/4RMsUerFbkOnK1vu0.7GK4wkNXgBkYtThRm7HYnP.S', 1, 1, '2026-01-27 01:36:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_customer_name` (`customer_name`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `fk_invoices_customers` (`customer_id`),
  ADD KEY `fk_invoices_users` (`created_by`),
  ADD KEY `idx_invoice_date` (`invoice_date`),
  ADD KEY `idx_invoice_status` (`status`);

--
-- Indexes for table `invoice_counters`
--
ALTER TABLE `invoice_counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year` (`year`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_items_invoice` (`invoice_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_users_roles` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `invoice_counters`
--
ALTER TABLE `invoice_counters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_customers` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `fk_invoices_users` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `fk_items_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
