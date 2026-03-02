-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 10, 2026 at 12:58 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u802091730_seg`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `cefr` varchar(20) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `base_price` decimal(10,2) DEFAULT 150000.00,
  `stock` int(11) DEFAULT 1000,
  `is_featured` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `isbn`, `category`, `cefr`, `cover_image`, `base_price`, `stock`, `is_featured`) VALUES
(1, 'Odyssey 1', '9781640155978', 'Course Books', 'A1', 'https://www.compasspub.com/userfiles/item/20200903161644_itm.jpg', 150000.00, 1000, 1),
(2, 'Odyssey 2', '9781640155985', 'Course Books', 'A1+', 'https://www.compasspub.com/userfiles/item/20200903161657_itm.jpg', 150000.00, 1000, 1),
(3, 'Boost English 1', '9781685912758', 'Course Books', 'Pre A1', 'https://www.compasspub.com/userfiles/item/20230508105235_itm.png', 150000.00, 1000, 1),
(4, 'Boost English 2', '9781685912772', 'Course Books', 'A1', 'https://www.compasspub.com/userfiles/item/20230508111250_itm.png', 150000.00, 1000, 1),
(5, 'New Frontiers 1', '9781640152113', 'Course Books', 'A1', 'https://www.compasspub.com/userfiles/item/20200922134819_itm.jpg', 150000.00, 1000, 1),
(6, 'Sounds Great 1', '9781599665771', 'Phonics', 'Pre A1', 'https://www.compasspub.com/userfiles/item/2010052791452_itm.PNG', 150000.00, 1000, 1),
(7, 'Big Show 1', '9781640151246', 'Course Books', 'Pre A1', 'https://www.compasspub.com/userfiles/item/2018032617514_itm.jpg', 150000.00, 1000, 1),
(8, 'Ni Hao Chinese 1', '9781685911492', 'Chinese', '', 'https://www.compasspub.com/userfiles/item/2022040811442_itm.jpg', 150000.00, 1000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `interest_forms`
--

CREATE TABLE `interest_forms` (
  `id` int(11) NOT NULL,
  `school_name` varchar(150) DEFAULT NULL,
  `participant_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `student_count` int(11) DEFAULT NULL,
  `programs` text DEFAULT NULL,
  `visit_consent` varchar(20) DEFAULT NULL,
  `interest_level` varchar(100) DEFAULT NULL,
  `status` enum('new','contacted') DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `interest_forms`
--

INSERT INTO `interest_forms` (`id`, `school_name`, `participant_name`, `email`, `phone`, `city`, `contact`, `position`, `student_count`, `programs`, `visit_consent`, `interest_level`, `status`, `created_at`) VALUES
(1, 'SMA 1 Jakarta', 'Budi', NULL, NULL, 'Jakarta Selatan', NULL, NULL, 500, '[\"Odyssey\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(2, 'SMP 5 Bandung', 'Siti', NULL, NULL, 'Bandung', NULL, NULL, 200, '[\"Boost English\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(3, 'SD Al-Azhar', 'Ahmad', NULL, NULL, 'Jakarta Selatan', NULL, NULL, 300, '[\"Tree House\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(4, 'Surabaya Inter', 'John', NULL, NULL, 'Surabaya', NULL, NULL, 150, '[\"New Frontiers\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(5, 'Bali Public', 'Wayan', NULL, NULL, 'Denpasar', NULL, NULL, 100, '[\"Big Show\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(6, 'Jakarta High', 'Dewi', NULL, NULL, 'Jakarta Selatan', NULL, NULL, 600, '[\"Odyssey\"]', NULL, NULL, 'new', '2026-02-08 11:58:58'),
(7, 'Compass HQ', 'Admin Staff', 'admin@compass.com', '0812-3456-7890', 'Sukabumi', NULL, 'Engineer', 100, '[\"Lainnya\"]', 'Ya', 'Tertarik', 'new', '2026-02-08 22:05:50');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `reference_id` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_snapshot` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `payment_status` enum('unpaid','deposit_pending','partial','paid') DEFAULT 'unpaid',
  `tier_level` int(11) DEFAULT 1,
  `is_early_bird` tinyint(1) DEFAULT 0,
  `total_amount` decimal(15,2) DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `payment_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `shipping_status` enum('processing','shipping','completed','cancelled') DEFAULT 'processing',
  `tracking_number` varchar(100) DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `confirmed_arrival` datetime DEFAULT NULL,
  `arrival_proof` varchar(255) DEFAULT NULL,
  `arrival_date` datetime DEFAULT NULL,
  `shipment_status` enum('processing','packing','delivering','completed','cancelled') DEFAULT 'processing',
  `next_payment_due` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `reference_id`, `user_id`, `shipping_city`, `shipping_snapshot`, `status`, `payment_status`, `tier_level`, `is_early_bird`, `total_amount`, `paid_amount`, `payment_proof`, `created_at`, `shipping_status`, `tracking_number`, `estimated_delivery`, `confirmed_arrival`, `arrival_proof`, `arrival_date`, `shipment_status`, `next_payment_due`) VALUES
(1, 'ORD-2026-000001', 2, 'Jakarta Selatan', NULL, 'rejected', 'unpaid', 1, 0, 450000.00, 0.00, NULL, '2026-02-03 11:58:58', 'processing', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(2, 'ORD-2026-000002', 3, 'Jakarta Selatan', NULL, 'rejected', 'unpaid', 3, 1, 24000000.00, 0.00, NULL, '2026-02-06 11:58:58', 'processing', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(3, 'ORD-2026-000003', 2, 'Jakarta Selatan', NULL, 'rejected', 'unpaid', 2, 1, 2700000.00, 0.00, NULL, '2026-01-29 11:58:58', 'processing', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(4, 'ORD-2026-000004', 5, 'Jakarta Selatan', NULL, 'confirmed', 'partial', 3, 1, 22500000.00, 400000.00, 'proof_4_1770553604.jpeg', '2026-02-08 12:23:34', 'processing', NULL, '2026-02-10', NULL, NULL, NULL, 'packing', NULL),
(5, 'ORD-2026-000005', 1, 'Jakarta Selatan', '{\"id\":1,\"user_id\":1,\"label\":\"Main Address\",\"recipient_name\":\"Admin\",\"phone\":\"08123456789\",\"address_line\":\"Headquarters\",\"city\":\"Jakarta Selatan\",\"postal_code\":null,\"is_default\":1,\"created_at\":\"2026-02-08 20:31:39\"}', 'cancelled', 'unpaid', 1, 1, 285000.00, 0.00, NULL, '2026-02-08 14:18:17', 'processing', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(6, 'ORD-2026-000006', 1, 'Sukabumi', '{\"id\": 6, \"city\": \"Sukabumi\", \"label\": \"SMK Islam Penguji\", \"phone\": \"085174448002\", \"user_id\": 1, \"created_at\": \"2026-02-08 21:31:36\", \"is_default\": 1, \"est_arrival\": \"2026-02-11\", \"postal_code\": \"43141\", \"address_line\": \"Jl. Pelabuhan II, Gg. Nakula No. 76\\nRT. 002/RW. 001, Kel. Tipar, Kec. Citamiang\", \"recipient_name\": \"Aldi Alfiandi\"}', 'confirmed', 'partial', 1, 1, 142500.00, 2500.00, NULL, '2026-02-08 14:32:58', 'completed', NULL, '2026-02-10', NULL, NULL, NULL, 'packing', NULL),
(7, NULL, 1, 'Sukabumi', '{\"id\":6,\"user_id\":1,\"label\":\"SMK Islam Penguji\",\"recipient\":\"Unknown\",\"recipient_name\":\"Aldi Alfiandi\",\"phone\":\"085174448002\",\"address_line\":\"Jl. Pelabuhan II, Gg. Nakula No. 76\\nRT. 002\\/RW. 001, Kel. Tipar, Kec. Citamiang\",\"city\":\"Sukabumi\",\"postal_code\":\"43141\",\"is_default\":1,\"created_at\":\"2026-02-08 21:31:36\"}', 'rejected', 'unpaid', 1, 1, 570000.00, 0.00, NULL, '2026-02-08 23:32:49', 'processing', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(8, NULL, 1, 'Sukabumi', '{\"id\":6,\"user_id\":1,\"label\":\"SMK Islam Penguji\",\"recipient\":\"Unknown\",\"recipient_name\":\"Aldi Alfiandi\",\"phone\":\"085174448002\",\"address_line\":\"Jl. Pelabuhan II, Gg. Nakula No. 76\\nRT. 002\\/RW. 001, Kel. Tipar, Kec. Citamiang\",\"city\":\"Sukabumi\",\"postal_code\":\"43141\",\"is_default\":1,\"created_at\":\"2026-02-08 21:31:36\"}', 'confirmed', 'paid', 1, 1, 285000.00, 285000.00, NULL, '2026-02-08 23:36:10', 'processing', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(9, NULL, 1, 'Sukabumi', '{\"id\":6,\"user_id\":1,\"label\":\"SMK Islam Penguji\",\"recipient\":\"Unknown\",\"recipient_name\":\"Aldi Alfiandi\",\"phone\":\"085174448002\",\"address_line\":\"Jl. Pelabuhan II, Gg. Nakula No. 76\\nRT. 002\\/RW. 001, Kel. Tipar, Kec. Citamiang\",\"city\":\"Sukabumi\",\"postal_code\":\"43141\",\"is_default\":1,\"created_at\":\"2026-02-08 21:31:36\"}', 'confirmed', 'partial', 3, 1, 28125000.00, 125000.00, NULL, '2026-02-09 00:16:36', 'processing', NULL, NULL, NULL, NULL, NULL, 'processing', NULL),
(10, NULL, 1, 'Sukabumi', '{\"id\": 6, \"user_id\": 1, \"label\": \"SMK Islam Penguji\", \"recipient\": \"Unknown\", \"recipient_name\": \"Aldi Alfiandi\", \"phone\": \"085174448002\", \"address_line\": \"Jl. Pelabuhan II, Gg. Nakula No. 76\\nRT. 002\\/RW. 001, Kel. Tipar, Kec. Citamiang\", \"city\": \"Sukabumi\", \"postal_code\": \"43141\", \"is_default\": 1, \"created_at\": \"2026-02-08 14:31:36\", \"est_arrival\": \"2026-02-13\"}', 'cancelled', 'unpaid', 1, 0, 6156000.00, 0.00, NULL, '2026-02-10 06:27:54', 'cancelled', NULL, NULL, NULL, NULL, NULL, 'processing', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `book_id`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 1, 3, 150000.00, 450000.00),
(2, 2, 3, 200, 120000.00, 24000000.00),
(3, 3, 5, 20, 135000.00, 2700000.00),
(4, 4, 1, 200, 112500.00, 22500000.00),
(5, 5, 7, 2, 142500.00, 285000.00),
(6, 6, 7, 1, 142500.00, 142500.00),
(7, 7, 7, 1, 142500.00, 142500.00),
(8, 7, 3, 1, 142500.00, 142500.00),
(9, 7, 4, 1, 142500.00, 142500.00),
(10, 7, 5, 1, 142500.00, 142500.00),
(11, 8, 7, 1, 142500.00, 142500.00),
(12, 8, 3, 1, 142500.00, 142500.00),
(13, 9, 7, 250, 112500.00, 28125000.00),
(14, 10, 3, 10, 142500.00, 1425000.00),
(15, 10, 4, 11, 142500.00, 1567500.00),
(16, 10, 5, 12, 142500.00, 1710000.00),
(17, 10, 7, 15, 142500.00, 2137500.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_payments`
--

CREATE TABLE `order_payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `proof_image` varchar(255) NOT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT current_timestamp(),
  `admin_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_payments`
--

INSERT INTO `order_payments` (`id`, `order_id`, `amount`, `proof_image`, `status`, `payment_date`, `admin_note`) VALUES
(572, 6, 2500.00, 'proof_6_1770590908.jpg', 'verified', '2026-02-08 22:48:28', NULL),
(573, 8, 285000.00, 'proof_8_1770594650.jpg', 'verified', '2026-02-08 23:50:50', NULL),
(574, 9, 125000.00, 'proof_9_1770596213.jpg', 'verified', '2026-02-09 00:16:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`) VALUES
('benefits_list', '[\"Native School Visit\"]'),
('csrf_token', '5c3112982e26639bede6c8c9a929806f0418635de83129848113943fae31324b'),
('discount_tier_2', '10'),
('discount_tier_3', '20'),
('early_bird_deadline', '2026-04-30'),
('moq_tier_2', '20'),
('moq_tier_3', '200'),
('programs_list', '[\"Tree House for kindergarten (KB/TK)\",\"Boost English for Primary (SD/MI)\",\"Odyssey (SMP-SMA/MTS/MA)\",\"Digital Literacy Reading Ocean Plus (TK-SMA)\"]');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `ai_reply` text DEFAULT NULL,
  `admin_reply` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `subject`, `message`, `status`, `created_at`, `ai_reply`, `admin_reply`) VALUES
(1, 1, 'Order Delay Inquiry', 'I ordered 2 weeks ago, where is my book?', 'open', '2026-02-08 20:08:02', NULL, NULL),
(2, 1, 'Wrong Item Received', 'I got the wrong ISBN.', 'closed', '2026-02-08 20:08:02', NULL, NULL),
(3, 1, 'Wrong Price', 'I got wrong items with wrong price', 'open', '2026-02-10 10:31:22', 'Thank you for contacting us. Our AI system is currently offline, but a human agent has been notified and will review your ticket regarding \'Wrong Price\' shortly. Please check your Order History for status updates.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tier_rules`
--

CREATE TABLE `tier_rules` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `min_qty` int(11) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `color` varchar(20) DEFAULT 'gray'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tier_rules`
--

INSERT INTO `tier_rules` (`id`, `name`, `min_qty`, `discount_percent`, `color`) VALUES
(1, 'Retail', 0, 0.00, 'gray'),
(2, 'Class Set', 20, 10.00, 'blue'),
(3, 'School License', 100, 20.00, 'green'),
(4, 'District Partner', 500, 30.00, 'gold');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `admin_note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `note` varchar(100) DEFAULT 'Installment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `user_id`, `amount`, `proof_image`, `status`, `admin_note`, `created_at`, `note`) VALUES
(1, 4, 5, 200000.00, 'pay_4_1770554600.jpeg', 'verified', NULL, '2026-02-08 12:43:20', 'Installment'),
(2, 4, 5, 200000.00, 'pay_4_1770555520.jpeg', 'verified', NULL, '2026-02-08 12:58:40', 'Installment');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('public','school','admin') DEFAULT 'public',
  `position` varchar(100) DEFAULT NULL,
  `institution` varchar(150) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_banned` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `position`, `institution`, `city`, `phone`, `address`, `is_banned`, `created_at`) VALUES
(1, 'Admin', 'admin@compass.com', '$2y$12$z2epecELzAsm.Qg2grFF6u4ocEqsyDxR66Nl0fcTIRzwUGScPUEdG', 'admin', 'Staff', 'PT SEG', NULL, '08123456789', 'Headquarters', 0, '2026-02-08 11:58:58'),
(2, 'Budi Santoso', 'budi@school.id', '$2y$12$ZrhfeA4XikDqGOSUNxC6H.gyL4KUOUM4Qe4hM4yiYwP1VwlIb2aNa', 'school', NULL, NULL, NULL, '08129999888', 'SMA Negeri 1 Jakarta', 0, '2026-02-08 11:58:58'),
(3, 'Siti Aminah', 'siti@gmail.com', '$2y$12$ZrhfeA4XikDqGOSUNxC6H.gyL4KUOUM4Qe4hM4yiYwP1VwlIb2aNa', 'public', NULL, NULL, NULL, '08127777666', 'Private Tutor, Bandung', 0, '2026-02-08 11:58:58'),
(4, 'John Doe', 'john@intl.sch.id', '$2y$12$ZrhfeA4XikDqGOSUNxC6H.gyL4KUOUM4Qe4hM4yiYwP1VwlIb2aNa', 'school', NULL, NULL, NULL, '08125555444', 'Inter School Surabaya', 0, '2026-02-08 11:58:58'),
(5, 'Abu Bakar', 'bakar@ceg.com', '$2y$12$z2epecELzAsm.Qg2grFF6u4ocEqsyDxR66Nl0fcTIRzwUGScPUEdG', 'public', NULL, NULL, NULL, NULL, 'No address on file', 0, '2026-02-08 12:10:22');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `label` varchar(50) DEFAULT 'School',
  `recipient` varchar(100) DEFAULT '',
  `recipient_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address_line` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `label`, `recipient`, `recipient_name`, `phone`, `address_line`, `city`, `postal_code`, `is_default`, `created_at`) VALUES
(2, 2, 'Main Address', 'Unknown', 'Budi Santoso', '08129999888', 'SMA Negeri 1 Jakarta', 'Jakarta Selatan', NULL, 1, '2026-02-08 13:31:39'),
(3, 3, 'Main Address', 'Unknown', 'Siti Aminah', '08127777666', 'Private Tutor, Bandung', 'Jakarta Selatan', NULL, 1, '2026-02-08 13:31:39'),
(4, 4, 'Main Address', 'Unknown', 'John Doe', '08125555444', 'Inter School Surabaya', 'Jakarta Selatan', NULL, 1, '2026-02-08 13:31:39'),
(6, 1, 'SMK Islam Penguji', 'Unknown', 'Aldi Alfiandi', '085174448002', 'Jl. Pelabuhan II, Gg. Nakula No. 76\nRT. 002/RW. 001, Kel. Tipar, Kec. Citamiang', 'Sukabumi', '43141', 1, '2026-02-08 14:31:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interest_forms`
--
ALTER TABLE `interest_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ref_id` (`reference_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_payments`
--
ALTER TABLE `order_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tier_rules`
--
ALTER TABLE `tier_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `interest_forms`
--
ALTER TABLE `interest_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order_payments`
--
ALTER TABLE `order_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=575;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tier_rules`
--
ALTER TABLE `tier_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
