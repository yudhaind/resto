-- phpMyAdmin SQL Dump
-- version 5.2.3deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 15, 2026 at 08:34 PM
-- Server version: 8.4.9-0ubuntu0.26.04.1
-- PHP Version: 8.5.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_resto`
--

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

CREATE TABLE `global_settings` (
  `id` int NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `global_settings`
--

INSERT INTO `global_settings` (`id`, `label`, `value`) VALUES
(1, 'nama_toko', 'Restoran Enak');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `table_id` int NOT NULL,
  `waiter_id` int DEFAULT NULL,
  `customer_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT '0.00',
  `order_status` enum('pending','completed','cancelled') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `table_id`, `waiter_id`, `customer_name`, `total_amount`, `order_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Yudha', 48000.00, 'completed', '2026-06-07 08:01:16', '2026-06-07 08:01:16'),
(2, 2, 1, 'Faras', 81000.00, 'completed', '2026-06-07 08:06:09', '2026-06-07 08:06:09'),
(3, 1, 1, '', 33000.00, 'completed', '2026-06-14 05:41:29', '2026-06-14 05:41:29'),
(4, 1, 1, '', 86000.00, 'completed', '2026-06-14 08:48:17', '2026-06-14 08:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_at_order` decimal(10,2) NOT NULL,
  `notes` text,
  `cooking_status` enum('pending','cooking','served') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price_at_order`, `notes`, `cooking_status`, `created_at`) VALUES
(1, 1, 1, 1, 25000.00, NULL, 'served', '2026-06-07 08:01:16'),
(2, 1, 2, 1, 8000.00, NULL, 'served', '2026-06-07 08:01:16'),
(3, 1, 3, 1, 15000.00, NULL, 'served', '2026-06-07 08:01:16'),
(4, 2, 1, 2, 25000.00, NULL, 'served', '2026-06-07 08:06:09'),
(5, 2, 2, 2, 8000.00, NULL, 'served', '2026-06-07 08:06:09'),
(6, 2, 3, 1, 15000.00, NULL, 'served', '2026-06-07 08:06:09'),
(7, 3, 1, 1, 25000.00, '', 'served', '2026-06-14 05:41:29'),
(8, 3, 2, 1, 8000.00, '', 'served', '2026-06-14 05:41:29'),
(9, 4, 1, 2, 25000.00, '', 'pending', '2026-06-14 08:48:17'),
(10, 4, 2, 2, 8000.00, '', 'pending', '2026-06-14 08:48:17'),
(11, 4, 3, 1, 15000.00, '', 'pending', '2026-06-14 08:48:17'),
(12, 4, 4, 1, 5000.00, '', 'pending', '2026-06-14 08:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `cashier_id` int NOT NULL,
  `payment_method` enum('cash','qris','debit') NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `change_amount` decimal(10,2) DEFAULT '0.00',
  `paid_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `cashier_id`, `payment_method`, `amount_paid`, `change_amount`, `paid_at`) VALUES
(1, 1, 1, 'cash', 100000.00, 52000.00, '2026-06-07 08:01:16'),
(2, 2, 1, 'cash', 100000.00, 19000.00, '2026-06-07 08:06:09'),
(3, 3, 1, 'cash', 50000.00, 17000.00, '2026-06-14 05:41:29'),
(4, 4, 1, 'cash', 100000.00, 14000.00, '2026-06-14 08:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` enum('food','drink','snack','dessert') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `images` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `is_available`, `created_at`, `updated_at`, `images`) VALUES
(1, 'Nasi Goreng', 'food', 25000.00, 1, '2026-06-05 21:48:07', '2026-06-05 21:48:07', ''),
(2, 'Es Jeruk', 'drink', 8000.00, 1, '2026-06-05 21:48:24', '2026-06-05 21:48:24', ''),
(3, 'Kentang Goreng', 'snack', 15000.00, 1, '2026-06-05 21:48:41', '2026-06-05 21:48:41', ''),
(4, 'Puding', 'dessert', 5000.00, 1, '2026-06-05 21:49:04', '2026-06-05 21:49:04', '');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int NOT NULL,
  `table_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `capacity` int DEFAULT '2',
  `status` enum('available','occupied','dirty') DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `table_number`, `capacity`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Meja 1', 2, 'occupied', '2026-06-05 21:49:24', '2026-06-14 08:48:17'),
(2, 'Meja 2', 2, 'available', '2026-06-05 21:49:33', '2026-06-14 05:40:10'),
(3, 'Meja 3', 2, 'available', '2026-06-05 21:49:43', '2026-06-14 05:39:40'),
(4, 'Meja 4', 2, 'available', '2026-06-05 21:49:52', '2026-06-14 05:39:39'),
(5, 'Meja 5', 4, 'available', '2026-06-05 21:50:09', '2026-06-14 05:39:39'),
(6, 'Meja 6', 4, 'available', '2026-06-14 05:36:22', '2026-06-14 05:39:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `role` enum('admin','cashier','waiter','kitchen','owner') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$zIfiyx70sE3P0teNv6BFIOOp2B.MOD5vG4dJzB2ygWvr7.UvXW7Vm', 'Admin', 'admin', 1, '2026-05-21 06:34:20', '2026-05-21 06:34:20'),
(2, 'kasir', '$2y$12$kA2e8MPWaFI.OdqhTHQsheLL6pdMVDzu0Gb6YXnYPzwG1uGcXeFt6', 'Nama Kasir', 'cashier', 1, '2026-06-06 13:34:04', '2026-06-06 13:35:20'),
(3, 'dapur', '$2y$12$acQq3jN3kRwzruxKtn.jmOF9kMHYGFSSrMJV/KNB05BVAt.LK1hZS', 'Nama Dapur', 'kitchen', 1, '2026-06-13 21:54:08', '2026-06-13 21:54:08'),
(4, 'pelayan', '$2y$12$iMwnhwjhIQHfyqmFzl.8Q.cmXkoJHlF5XZMIsMyQAUY2GusCVQwBy', 'Nama Pelayan', 'waiter', 1, '2026-06-13 21:54:44', '2026-06-13 21:54:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `global_settings`
--
ALTER TABLE `global_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_table` (`table_id`),
  ADD KEY `fk_orders_waiter` (`waiter_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_details_order` (`order_id`),
  ADD KEY `fk_details_product` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payments_order` (`order_id`),
  ADD KEY `fk_payments_cashier` (`cashier_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table_number` (`table_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `global_settings`
--
ALTER TABLE `global_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_orders_waiter` FOREIGN KEY (`waiter_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_details_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_details_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_cashier` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
