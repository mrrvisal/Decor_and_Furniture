-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 01, 2026 at 11:09 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `decor_furniture`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `name`, `created_at`) VALUES
(1, 'admin', 'admin@decorfurniture.com', '$2y$10$hG12fpaEXJnI3muHLOMRS.zMfSyX.UdshB40c7aiFHnZa3AtOAhKq', 'Admin User', '2026-01-30 13:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `action` enum('create','update','delete','toggle_status','cancel_order','update_status','resolve_stock_alert') NOT NULL,
  `target_type` enum('product','order','user','category','admin','stock_alert') NOT NULL,
  `target_id` int(10) UNSIGNED NOT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `admin_id`, `admin_name`, `action`, `target_type`, `target_id`, `old_data`, `new_data`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'Admin User', 'update', 'product', 27, '{\"id\":27,\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"slug\":\"wooden-tabletop-vanity-mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":\"100.00\",\"stock\":0,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:01:38\",\"updated_at\":\"2026-05-31 01:28:55\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":100,\"stock\":20,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 18:41:56'),
(2, 1, 'Admin User', 'update_status', 'order', 9, '{\"status\":\"waiting_payment\"}', '{\"status\":\"paid\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 18:42:26'),
(3, 1, 'Admin User', 'toggle_status', 'user', 7, '{\"is_active\":0}', '{\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-30 18:50:12'),
(4, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 4, '{\"status\":\"resolved\"}', '{\"status\":\"resolved\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 13:53:42'),
(5, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 6, '{\"status\":\"pending\"}', '{\"status\":\"resolved\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 13:53:45'),
(6, 1, 'Admin User', 'update', 'product', 27, '{\"id\":27,\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"slug\":\"wooden-tabletop-vanity-mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":\"100.00\",\"stock\":20,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:01:38\",\"updated_at\":\"2026-05-31 01:41:56\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":100,\"stock\":10,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:03:19'),
(7, 1, 'Admin User', 'update', 'product', 27, '{\"id\":27,\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"slug\":\"wooden-tabletop-vanity-mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":\"100.00\",\"stock\":10,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:01:38\",\"updated_at\":\"2026-05-31 21:03:19\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":100,\"stock\":5,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:03:28'),
(8, 1, 'Admin User', 'toggle_status', 'user', 6, '{\"is_active\":1}', '{\"is_active\":0}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:03:49'),
(9, 1, 'Admin User', 'create', 'product', 30, NULL, '{\"category_id\":2,\"name\":\"huiop\",\"description\":\"ghjkl\",\"price\":6789,\"stock\":89,\"image\":null,\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:04:23'),
(10, 1, 'Admin User', 'toggle_status', 'product', 30, '{\"is_active\":1}', '{\"is_active\":0}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:04:35'),
(11, 1, 'Admin User', 'toggle_status', 'product', 30, '{\"is_active\":0}', '{\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:04:42'),
(12, 1, 'Admin User', 'update', 'user', 7, '{\"id\":7,\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"email\":\"eivisal617@gmail.com\",\"password\":\"$2y$10$FbtoJIr.SUpYlzYKMDCfyuS70xC9I6Z012FioZADiTQAO5jl1kR4a\",\"phone\":\"010584267\",\"email_verified_at\":\"2026-02-05 23:58:29\",\"is_active\":1,\"created_at\":\"2026-02-05 23:58:29\",\"updated_at\":\"2026-05-31 01:50:12\"}', '{\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"phone\":\"010584267\",\"is_active\":0,\"email_verified_at\":\"2026-05-31 21:04:54\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:04:54'),
(13, 1, 'Admin User', 'update', 'user', 7, '{\"id\":7,\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"email\":\"eivisal617@gmail.com\",\"password\":\"$2y$10$FbtoJIr.SUpYlzYKMDCfyuS70xC9I6Z012FioZADiTQAO5jl1kR4a\",\"phone\":\"010584267\",\"email_verified_at\":\"2026-05-31 21:04:54\",\"is_active\":0,\"created_at\":\"2026-02-05 23:58:29\",\"updated_at\":\"2026-05-31 21:04:54\"}', '{\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"phone\":\"010584267\",\"is_active\":1,\"email_verified_at\":\"2026-05-31 21:05:12\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:05:12'),
(14, 1, 'Admin User', 'toggle_status', 'user', 7, '{\"is_active\":1}', '{\"is_active\":0}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:05:26'),
(15, 1, 'Admin User', 'update', 'user', 7, '{\"id\":7,\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"email\":\"eivisal617@gmail.com\",\"password\":\"$2y$10$FbtoJIr.SUpYlzYKMDCfyuS70xC9I6Z012FioZADiTQAO5jl1kR4a\",\"phone\":\"010584267\",\"email_verified_at\":\"2026-05-31 21:05:12\",\"is_active\":0,\"created_at\":\"2026-02-05 23:58:29\",\"updated_at\":\"2026-05-31 21:05:26\"}', '{\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"phone\":\"010584267\",\"is_active\":1,\"email_verified_at\":\"2026-05-31 21:07:22\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:07:22'),
(16, 1, 'Admin User', 'toggle_status', 'product', 30, '{\"is_active\":1}', '{\"is_active\":0}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:07:35'),
(17, 1, 'Admin User', 'update_status', 'order', 9, '{\"status\":\"paid\"}', '{\"status\":\"cancelled\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:07:47'),
(18, 1, 'Admin User', 'update_status', 'order', 9, '{\"status\":\"cancelled\"}', '{\"status\":\"paid\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:08:01'),
(19, 1, 'Admin User', 'update_status', 'order', 8, '{\"status\":\"delivered\"}', '{\"status\":\"paid\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:08:11'),
(20, 1, 'Admin User', 'update_status', 'order', 7, '{\"status\":\"pending\"}', '{\"status\":\"processing\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:08:20'),
(21, 1, 'Admin User', 'update', 'product', 27, '{\"id\":27,\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"slug\":\"wooden-tabletop-vanity-mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":\"100.00\",\"stock\":5,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:01:38\",\"updated_at\":\"2026-05-31 21:03:28\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":100,\"stock\":2,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:25:11'),
(22, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 7, '{\"status\":\"pending\"}', '{\"status\":\"resolved\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:26:23'),
(23, 1, 'Admin User', 'update', 'product', 27, '{\"id\":27,\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"slug\":\"wooden-tabletop-vanity-mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":\"100.00\",\"stock\":2,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:01:38\",\"updated_at\":\"2026-05-31 21:25:11\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":100,\"stock\":1,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:28:30'),
(24, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 8, '{\"status\":\"pending\",\"product_stock\":1}', '{\"status\":\"resolved\",\"product_stock\":11}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:28:35'),
(25, 1, 'Admin User', 'update', 'product', 26, '{\"id\":26,\"category_id\":3,\"name\":\"wooden dining table set\",\"slug\":\"wooden-dining-table-set\",\"description\":\"This set is crafted from premium quality solid wood, likely featuring an antique or modern design. The table is rectangular, and the four chairs have slatted backs and cushioned seats. The set is noted for its durability and quality craftsmanship.\",\"price\":\"800.00\",\"stock\":0,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a24fc700d.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 20:59:43\",\"updated_at\":\"2026-05-31 01:40:28\",\"category_name\":\"Dining\",\"category_slug\":\"dining\"}', '{\"category_id\":3,\"name\":\"wooden dining table set\",\"description\":\"This set is crafted from premium quality solid wood, likely featuring an antique or modern design. The table is rectangular, and the four chairs have slatted backs and cushioned seats. The set is noted for its durability and quality craftsmanship.\",\"price\":800,\"stock\":0,\"image\":\"prod_6984a24fc700d.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:28:53'),
(26, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 9, '{\"status\":\"pending\",\"product_stock\":0}', '{\"status\":\"resolved\",\"product_stock\":10}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:28:57'),
(27, 1, 'Admin User', 'update', 'product', 26, '{\"id\":26,\"category_id\":3,\"name\":\"wooden dining table set\",\"slug\":\"wooden-dining-table-set\",\"description\":\"This set is crafted from premium quality solid wood, likely featuring an antique or modern design. The table is rectangular, and the four chairs have slatted backs and cushioned seats. The set is noted for its durability and quality craftsmanship.\",\"price\":\"800.00\",\"stock\":10,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a24fc700d.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 20:59:43\",\"updated_at\":\"2026-05-31 21:28:57\",\"category_name\":\"Dining\",\"category_slug\":\"dining\"}', '{\"category_id\":3,\"name\":\"wooden dining table set\",\"description\":\"This set is crafted from premium quality solid wood, likely featuring an antique or modern design. The table is rectangular, and the four chairs have slatted backs and cushioned seats. The set is noted for its durability and quality craftsmanship.\",\"price\":800,\"stock\":1,\"image\":\"prod_6984a24fc700d.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:29:10'),
(28, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 10, '{\"status\":\"pending\",\"product_stock\":1}', '{\"status\":\"resolved\",\"product_stock\":11}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:29:18'),
(29, 1, 'Admin User', 'update', 'product', 27, '{\"id\":27,\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"slug\":\"wooden-tabletop-vanity-mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":\"100.00\",\"stock\":11,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:01:38\",\"updated_at\":\"2026-05-31 21:28:35\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Wooden Tabletop Vanity Mirror\",\"description\":\"It features a natural, solid wood frame and base.\",\"price\":100,\"stock\":9,\"image\":\"prod_6984a2c24cc01.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:29:39'),
(30, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 11, '{\"status\":\"pending\",\"product_stock\":9,\"reorder_level\":10}', '{\"status\":\"resolved\",\"product_stock\":100,\"reorder_level\":100}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:32:44'),
(31, 1, 'Admin User', 'update', 'product', 30, '{\"id\":30,\"category_id\":2,\"name\":\"huiop\",\"slug\":\"huiop\",\"description\":\"ghjkl\",\"price\":\"6789.00\",\"stock\":89,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":null,\"is_active\":0,\"created_at\":\"2026-05-31 21:04:23\",\"updated_at\":\"2026-05-31 21:07:35\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"huiop\",\"description\":\"ghjkl\",\"price\":6789,\"stock\":5,\"image\":null,\"is_active\":0}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:33:06'),
(32, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 12, '{\"status\":\"pending\",\"product_stock\":5,\"reorder_level\":10}', '{\"status\":\"resolved\",\"product_stock\":100,\"reorder_level\":100}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:33:10'),
(33, 1, 'Admin User', 'update', 'product', 30, '{\"id\":30,\"category_id\":2,\"name\":\"huiop\",\"slug\":\"huiop\",\"description\":\"ghjkl\",\"price\":\"6789.00\",\"stock\":100,\"reorder_level\":100,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":null,\"is_active\":0,\"created_at\":\"2026-05-31 21:04:23\",\"updated_at\":\"2026-05-31 21:33:10\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"huiop\",\"description\":\"ghjkl\",\"price\":6789,\"stock\":2,\"image\":null,\"is_active\":0}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:36:11'),
(34, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 13, '{\"status\":\"pending\",\"product_stock\":2,\"reorder_level\":100}', '{\"status\":\"resolved\",\"product_stock\":102,\"reorder_level\":100}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:36:15'),
(35, 1, 'Admin User', 'update', 'product', 22, '{\"id\":22,\"category_id\":4,\"name\":\"Flexsteel Swift Hazelnut Power Recliner\",\"slug\":\"flexsteel-swift-hazelnut-power-recliner\",\"description\":\"The recliner is upholstered in hazelnut brown top-grain leather with sweeping contrast stitching details and welt trim. It features soft, layered padded arms and a high-divided back for support.\",\"price\":\"1800.00\",\"stock\":8,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a10bce627.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 20:54:19\",\"updated_at\":\"2026-02-05 20:54:19\",\"category_name\":\"Office\",\"category_slug\":\"office\"}', '{\"category_id\":4,\"name\":\"Flexsteel Swift Hazelnut Power Recliner\",\"description\":\"The recliner is upholstered in hazelnut brown top-grain leather with sweeping contrast stitching details and welt trim. It features soft, layered padded arms and a high-divided back for support.\",\"price\":1800,\"stock\":8,\"image\":\"prod_6984a10bce627.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:36:24'),
(36, 1, 'Admin User', 'update', 'product', 11, '{\"id\":11,\"category_id\":1,\"name\":\"Braxton Culler Durham Sofa\",\"slug\":\"braxton-culler-durham-sofa\",\"description\":\"The Durham Sofa combines casual comfort with a transitional style.\",\"price\":\"2199.00\",\"stock\":8,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_69849be8bafb5.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 20:32:24\",\"updated_at\":\"2026-05-31 01:26:18\",\"category_name\":\"Living Room\",\"category_slug\":\"living-room\"}', '{\"category_id\":1,\"name\":\"Braxton Culler Durham Sofa\",\"description\":\"The Durham Sofa combines casual comfort with a transitional style.\",\"price\":2199,\"stock\":8,\"image\":\"prod_69849be8bafb5.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:36:38'),
(37, 1, 'Admin User', 'update', 'product', 21, '{\"id\":21,\"category_id\":4,\"name\":\"Bulfinch chair\",\"slug\":\"bulfinch-chair\",\"description\":\"The chair is known for its comfort, making it suitable for long meetings or meals. It can be ordered with or without arms and features the proprietary \\\"Eustis Joint\\\" construction, backed by a 20-year warranty.\",\"price\":\"200.00\",\"stock\":9,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a0ab8c843.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 20:52:43\",\"updated_at\":\"2026-02-05 20:52:43\",\"category_name\":\"Office\",\"category_slug\":\"office\"}', '{\"category_id\":4,\"name\":\"Bulfinch chair\",\"description\":\"The chair is known for its comfort, making it suitable for long meetings or meals. It can be ordered with or without arms and features the proprietary \\\"Eustis Joint\\\" construction, backed by a 20-year warranty.\",\"price\":200,\"stock\":9,\"image\":\"prod_6984a0ab8c843.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:36:43'),
(38, 1, 'Admin User', 'delete', 'product', 30, '{\"id\":30,\"category_id\":2,\"name\":\"huiop\",\"slug\":\"huiop\",\"description\":\"ghjkl\",\"price\":\"6789.00\",\"stock\":102,\"reorder_level\":100,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":null,\"is_active\":0,\"created_at\":\"2026-05-31 21:04:23\",\"updated_at\":\"2026-05-31 21:36:15\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:36:54'),
(39, 1, 'Admin User', 'update', 'product', 29, '{\"id\":29,\"category_id\":2,\"name\":\"Sana Wooden Frame Standing Mirror\",\"slug\":\"sana-wooden-frame-standing-mirror\",\"description\":\"This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.\",\"price\":\"100.00\",\"stock\":109,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a35956581.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:04:09\",\"updated_at\":\"2026-05-31 01:26:18\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Sana Wooden Frame Standing Mirror\",\"description\":\"This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.\",\"price\":100,\"stock\":10,\"image\":\"prod_6984a35956581.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:37:01'),
(40, 1, 'Admin User', 'update_status', 'order', 10, '{\"status\":\"pending\"}', '{\"status\":\"delivered\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:38:02'),
(41, 1, 'Admin User', 'update_status', 'order', 10, '{\"status\":\"delivered\"}', '{\"status\":\"paid\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:38:12'),
(42, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 17, '{\"status\":\"pending\",\"product_stock\":9,\"reorder_level\":5}', '{\"status\":\"resolved\",\"product_stock\":109,\"reorder_level\":100}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:38:20'),
(43, 1, 'Admin User', 'update_status', 'order', 10, '{\"status\":\"paid\"}', '{\"status\":\"delivered\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:43:39'),
(44, 1, 'Admin User', 'update_status', 'order', 6, '{\"status\":\"delivered\"}', '{\"status\":\"pending\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 14:45:37'),
(45, 1, 'Admin User', 'create', 'admin', 2, NULL, '{\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:23:07'),
(46, 1, 'Admin User', 'create', 'admin', 3, NULL, '{\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"contact_name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"email\":\"user@example.com\",\"phone\":\"010584267\",\"address\":\"Reyleigh\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:23:18'),
(47, 1, 'Admin User', 'update', 'product', 29, '{\"id\":29,\"category_id\":2,\"name\":\"Sana Wooden Frame Standing Mirror\",\"slug\":\"sana-wooden-frame-standing-mirror\",\"description\":\"This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.\",\"price\":\"100.00\",\"stock\":109,\"reorder_level\":100,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a35956581.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:04:09\",\"updated_at\":\"2026-05-31 21:38:20\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Sana Wooden Frame Standing Mirror\",\"description\":\"This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.\",\"price\":100,\"stock\":109,\"supplier_id\":1,\"image\":\"prod_6984a35956581.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:23:29'),
(48, 1, 'Admin User', 'update', 'product', 28, '{\"id\":28,\"category_id\":2,\"name\":\"Ashley Brocky Mirror\",\"slug\":\"ashley-brocky-mirror\",\"description\":\"It is a simple, round wall mirror with a black metal frame. It is a versatile accent piece suitable for various rooms in a home.\",\"price\":\"199.00\",\"stock\":126,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a323824b0.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:03:15\",\"updated_at\":\"2026-05-31 21:51:46\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Ashley Brocky Mirror\",\"description\":\"It is a simple, round wall mirror with a black metal frame. It is a versatile accent piece suitable for various rooms in a home.\",\"price\":199,\"stock\":126,\"supplier_id\":3,\"image\":\"prod_6984a323824b0.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:23:35'),
(49, 1, 'Admin User', 'update', 'product', 29, '{\"id\":29,\"category_id\":2,\"name\":\"Sana Wooden Frame Standing Mirror\",\"slug\":\"sana-wooden-frame-standing-mirror\",\"description\":\"This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.\",\"price\":\"100.00\",\"stock\":109,\"reorder_level\":100,\"reorder_quantity\":10,\"supplier_id\":1,\"image\":\"prod_6984a35956581.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:04:09\",\"updated_at\":\"2026-05-31 22:23:29\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Sana Wooden Frame Standing Mirror\",\"description\":\"This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.\",\"price\":100,\"stock\":9,\"supplier_id\":1,\"image\":\"prod_6984a35956581.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:23:42'),
(50, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 18, '{\"status\":\"sent\",\"product_stock\":9,\"reorder_level\":100}', '{\"status\":\"resolved\",\"product_stock\":109,\"reorder_level\":100}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:24:02'),
(51, 1, 'Admin User', 'delete', 'admin', 2, '{\"id\":2,\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1,\"created_at\":\"2026-05-31 22:23:07\"}', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:25:41'),
(52, 1, 'Admin User', 'update', 'admin', 1, '{\"id\":1,\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1,\"created_at\":\"2026-05-31 22:19:24\"}', '{\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:25:53'),
(53, 1, 'Admin User', 'update', 'admin', 1, '{\"id\":1,\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1,\"created_at\":\"2026-05-31 22:19:24\"}', '{\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"010584267\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:25:58'),
(54, 1, 'Admin User', 'update', 'admin', 1, '{\"id\":1,\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"010584267\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1,\"created_at\":\"2026-05-31 22:19:24\"}', '{\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"010584567\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:26:05'),
(55, 1, 'Admin User', 'update', 'admin', 3, '{\"id\":3,\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"contact_name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"email\":\"user@example.com\",\"phone\":\"010584267\",\"address\":\"Reyleigh\",\"is_active\":1,\"created_at\":\"2026-05-31 22:23:18\"}', '{\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"contact_name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"email\":\"user@example.com\",\"phone\":\"01058678\",\"address\":\"Reyleigh\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:26:11'),
(56, 1, 'Admin User', 'toggle_status', 'product', 13, '{\"is_active\":1}', '{\"is_active\":0}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:26:44'),
(57, 1, 'Admin User', 'toggle_status', 'product', 29, '{\"is_active\":1}', '{\"is_active\":0}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:26:53'),
(58, 1, 'Admin User', 'update', 'product', 29, '{\"id\":29,\"category_id\":2,\"name\":\"Sana Wooden Frame Standing Mirror\",\"slug\":\"sana-wooden-frame-standing-mirror\",\"description\":\"This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.\",\"price\":\"100.00\",\"stock\":109,\"reorder_level\":100,\"reorder_quantity\":10,\"supplier_id\":1,\"image\":\"prod_6984a35956581.jpg\",\"is_active\":0,\"created_at\":\"2026-02-05 21:04:09\",\"updated_at\":\"2026-05-31 22:26:53\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Sana Wooden Frame Standing Mirror\",\"description\":\"This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.\",\"price\":100,\"stock\":109,\"supplier_id\":1,\"image\":\"prod_6984a35956581.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:27:04'),
(59, 1, 'Admin User', 'update', 'product', 29, '{\"id\":29,\"category_id\":2,\"name\":\"Sana Wooden Frame Standing Mirror\",\"slug\":\"sana-wooden-frame-standing-mirror\",\"description\":\"This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.\",\"price\":\"100.00\",\"stock\":109,\"reorder_level\":100,\"reorder_quantity\":10,\"supplier_id\":1,\"image\":\"prod_6984a35956581.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 21:04:09\",\"updated_at\":\"2026-05-31 22:27:04\",\"category_name\":\"Bedroom\",\"category_slug\":\"bedroom\"}', '{\"category_id\":2,\"name\":\"Sana Wooden Frame Standing Mirror\",\"description\":\"This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.\",\"price\":100,\"stock\":9,\"supplier_id\":1,\"image\":\"prod_6984a35956581.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:27:47'),
(60, 1, 'Admin User', 'update', 'product', 21, '{\"id\":21,\"category_id\":4,\"name\":\"Bulfinch chair\",\"slug\":\"bulfinch-chair\",\"description\":\"The chair is known for its comfort, making it suitable for long meetings or meals. It can be ordered with or without arms and features the proprietary \\\"Eustis Joint\\\" construction, backed by a 20-year warranty.\",\"price\":\"200.00\",\"stock\":9,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_6984a0ab8c843.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 20:52:43\",\"updated_at\":\"2026-02-05 20:52:43\",\"category_name\":\"Office\",\"category_slug\":\"office\"}', '{\"category_id\":4,\"name\":\"Bulfinch chair\",\"description\":\"The chair is known for its comfort, making it suitable for long meetings or meals. It can be ordered with or without arms and features the proprietary \\\"Eustis Joint\\\" construction, backed by a 20-year warranty.\",\"price\":200,\"stock\":9,\"supplier_id\":1,\"image\":\"prod_6984a0ab8c843.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:28:11'),
(61, 1, 'Admin User', 'update', 'product', 21, '{\"id\":21,\"category_id\":4,\"name\":\"Bulfinch chair\",\"slug\":\"bulfinch-chair\",\"description\":\"The chair is known for its comfort, making it suitable for long meetings or meals. It can be ordered with or without arms and features the proprietary \\\"Eustis Joint\\\" construction, backed by a 20-year warranty.\",\"price\":\"200.00\",\"stock\":9,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":1,\"image\":\"prod_6984a0ab8c843.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 20:52:43\",\"updated_at\":\"2026-05-31 22:28:11\",\"category_name\":\"Office\",\"category_slug\":\"office\"}', '{\"category_id\":4,\"name\":\"Bulfinch chair\",\"description\":\"The chair is known for its comfort, making it suitable for long meetings or meals. It can be ordered with or without arms and features the proprietary \\\"Eustis Joint\\\" construction, backed by a 20-year warranty.\",\"price\":200,\"stock\":8,\"supplier_id\":1,\"image\":\"prod_6984a0ab8c843.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:28:34'),
(62, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 16, '{\"status\":\"pending\",\"product_stock\":8,\"reorder_level\":5}', '{\"status\":\"resolved\",\"product_stock\":108,\"reorder_level\":100}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:28:49'),
(63, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 15, '{\"status\":\"pending\",\"product_stock\":8,\"reorder_level\":5}', '{\"status\":\"resolved\",\"product_stock\":108,\"reorder_level\":100}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:28:50'),
(64, 1, 'Admin User', 'resolve_stock_alert', 'stock_alert', 14, '{\"status\":\"pending\",\"product_stock\":8,\"reorder_level\":5}', '{\"status\":\"resolved\",\"product_stock\":108,\"reorder_level\":100}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:28:51'),
(65, 1, 'Admin User', 'update', 'product', 10, '{\"id\":10,\"category_id\":1,\"name\":\"Kariam 37\\\" Fabric Chair\",\"slug\":\"kariam-37-fabric-chair\",\"description\":\"This chair is designed to elevate any room. It features large welt detailing, a crisp box border, and is noted for its incredible comfort and scale. It is perfectly at home in any room setting.\",\"price\":\"649.00\",\"stock\":50,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_69849b4d855b2.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 20:29:49\",\"updated_at\":\"2026-02-05 20:29:49\",\"category_name\":\"Living Room\",\"category_slug\":\"living-room\"}', '{\"category_id\":1,\"name\":\"Kariam 37\\\" Fabric Chair\",\"description\":\"This chair is designed to elevate any room. It features large welt detailing, a crisp box border, and is noted for its incredible comfort and scale. It is perfectly at home in any room setting.\",\"price\":649,\"stock\":0,\"supplier_id\":3,\"image\":\"prod_69849b4d855b2.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 15:29:02'),
(66, 1, 'Admin User', 'delete', 'admin', 1, '{\"id\":1,\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"010584567\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1,\"created_at\":\"2026-05-31 22:19:24\"}', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 16:12:18'),
(67, 1, 'Admin User', 'delete', 'admin', 3, '{\"id\":3,\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"contact_name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"email\":\"user@example.com\",\"phone\":\"01058678\",\"address\":\"Reyleigh\",\"is_active\":1,\"created_at\":\"2026-05-31 22:23:18\"}', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 16:14:37'),
(68, 1, 'Admin User', 'create', 'admin', 4, NULL, '{\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"contact_name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"email\":\"user@example.com\",\"phone\":\"010584267\",\"address\":\"Reyleigh\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 16:15:02'),
(69, 1, 'Admin User', 'delete', 'admin', 4, '{\"id\":4,\"name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"contact_name\":\"\\u17a2\\u17bb\\u17b8 \\u179c\\u17b7\\u179f\\u17b6\\u179b\",\"email\":\"user@example.com\",\"phone\":\"010584267\",\"address\":\"Reyleigh\",\"is_active\":1,\"created_at\":\"2026-05-31 23:15:02\"}', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 16:15:04'),
(70, 1, 'Admin User', 'create', 'admin', 5, NULL, '{\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 16:15:58'),
(71, 1, 'Admin User', 'delete', 'admin', 5, '{\"id\":5,\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1,\"created_at\":\"2026-05-31 23:15:58\"}', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 16:18:01'),
(72, 1, 'Admin User', 'create', 'admin', 6, NULL, '{\"name\":\"Ei Visal\",\"contact_name\":\"Ei Visal\",\"email\":\"mrrvisal617@gmail.com\",\"phone\":\"\",\"address\":\"JQX5+X95, Phnom Penh\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-31 16:18:07'),
(73, 1, 'Admin User', 'update', 'product', 10, '{\"id\":10,\"category_id\":1,\"name\":\"Kariam 37\\\" Fabric Chair\",\"slug\":\"kariam-37-fabric-chair\",\"description\":\"This chair is designed to elevate any room. It features large welt detailing, a crisp box border, and is noted for its incredible comfort and scale. It is perfectly at home in any room setting.\",\"price\":\"649.00\",\"stock\":0,\"reorder_level\":5,\"reorder_quantity\":10,\"supplier_id\":null,\"image\":\"prod_69849b4d855b2.jpg\",\"is_active\":1,\"created_at\":\"2026-02-05 20:29:49\",\"updated_at\":\"2026-05-31 22:29:02\",\"category_name\":\"Living Room\",\"category_slug\":\"living-room\"}', '{\"category_id\":1,\"name\":\"Kariam 37\\\" Fabric Chair\",\"description\":\"This chair is designed to elevate any room. It features large welt detailing, a crisp box border, and is noted for its incredible comfort and scale. It is perfectly at home in any room setting.\",\"price\":649,\"stock\":0,\"supplier_id\":6,\"image\":\"prod_69849b4d855b2.jpg\",\"is_active\":1}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 07:58:48'),
(74, 1, 'Admin User', 'update_status', 'order', 11, '{\"status\":\"waiting_payment\"}', '{\"status\":\"delivered\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 08:05:46'),
(75, 1, 'Admin User', 'update_status', 'order', 12, '{\"delivery_status\":\"not_ready\",\"courier_name\":null,\"tracking_number\":null,\"estimated_delivery_date\":null}', '{\"delivery_status\":\"ready\",\"courier_name\":\"\",\"tracking_number\":\"\",\"estimated_delivery_date\":\"\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 08:36:49'),
(76, 1, 'Admin User', 'update_status', 'order', 12, '{\"delivery_status\":\"ready\",\"courier_name\":null,\"tracking_number\":null,\"estimated_delivery_date\":null}', '{\"delivery_status\":\"delivered\",\"courier_name\":\"\",\"tracking_number\":\"\",\"estimated_delivery_date\":\"2026-06-05\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 09:00:01'),
(77, 1, 'Admin User', 'update_status', 'order', 6, '{\"delivery_status\":\"not_ready\",\"courier_name\":null,\"tracking_number\":null,\"estimated_delivery_date\":null}', '{\"delivery_status\":\"delivered\",\"courier_name\":\"\",\"tracking_number\":\"10202\",\"estimated_delivery_date\":\"\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 09:01:21'),
(78, 1, 'Admin User', 'update_status', 'order', 6, '{\"delivery_status\":\"delivered\",\"courier_name\":null,\"tracking_number\":\"10202\",\"estimated_delivery_date\":null}', '{\"delivery_status\":\"out_for_delivery\",\"courier_name\":\"\",\"tracking_number\":\"10202\",\"estimated_delivery_date\":\"\"}', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 09:01:50');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`) VALUES
(1, 'Living Room', 'living-room', 'Sofas, coffee tables, TV stands', '2026-01-30 13:19:03'),
(2, 'Bedroom', 'bedroom', 'Beds, wardrobes, nightstands', '2026-01-30 13:19:03'),
(3, 'Dining', 'dining', 'Dining tables, chairs, sideboards', '2026-01-30 13:19:03'),
(4, 'Office', 'office', 'Desks, office chairs, shelves', '2026-01-30 13:19:03'),
(6, 'Lamp', 'lamp', 'lamp', '2026-02-03 13:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','waiting_payment','paid','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_method` enum('qr_code','pay_later') NOT NULL,
  `shipping_name` varchar(100) NOT NULL,
  `shipping_email` varchar(255) NOT NULL,
  `shipping_phone` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_postcode` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `delivery_method` enum('pickup','local','province') NOT NULL DEFAULT 'local',
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_status` enum('not_ready','ready','out_for_delivery','delivered') NOT NULL DEFAULT 'not_ready',
  `courier_name` varchar(100) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `estimated_delivery_date` date DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `status`, `payment_method`, `shipping_name`, `shipping_email`, `shipping_phone`, `shipping_address`, `shipping_city`, `shipping_postcode`, `notes`, `paid_at`, `created_at`, `updated_at`, `delivery_method`, `delivery_fee`, `delivery_status`, `courier_name`, `tracking_number`, `estimated_delivery_date`, `delivered_at`) VALUES
(6, 6, 'ORD-6984C9A254D80-1770310050', 995.00, 'delivered', 'qr_code', 'Visal', 'mrrvisal617@gmail.com', '010584267', 'https://www.google.com/maps?q=11.6498961,104.7584993', 'Phnom Penh', '123', '.....', NULL, '2026-02-05 16:47:30', '2026-06-01 09:01:50', 'local', 0.00, 'out_for_delivery', NULL, '10202', NULL, NULL),
(7, 6, 'ORD-6984CAED12738-1770310381', 100.00, 'processing', 'pay_later', 'Visal', 'mrrvisal617@gmail.com', '010584267', 'https://www.google.com/maps?q=11.6498961,104.7584993', 'Phnom Penh', '11111', '...', NULL, '2026-02-05 16:53:01', '2026-05-31 14:08:20', 'local', 0.00, 'not_ready', NULL, NULL, NULL, NULL),
(8, 6, 'ORD-6A1988FE54734-1780058366', 360.00, 'paid', 'qr_code', 'Visal', 'mrrvisal617@gmail.com', '010584267', 'https://www.google.com/maps?q=11.6498961,104.7584993', 'Phnom Penh', '11111', '', '2026-05-31 14:08:11', '2026-05-29 12:39:26', '2026-05-31 14:08:11', 'local', 0.00, 'not_ready', NULL, NULL, NULL, NULL),
(9, 6, 'ORD-6A1B2BCA792BC-1780165578', 4896.00, 'paid', 'qr_code', 'Visal', 'mrrvisal617@gmail.com', '010584267', 'https://www.google.com/maps?q=11.6498961,104.7584993', 'Phnom Penh', '11111', '', '2026-05-31 14:08:01', '2026-05-30 18:26:18', '2026-05-31 14:08:01', 'local', 0.00, 'not_ready', NULL, NULL, NULL, NULL),
(10, 6, 'ORD-6A1C479FF343B-1780238239', 299.00, 'delivered', 'pay_later', 'Visal', 'mrrvisal617@gmail.com', '010584267', 'https://www.google.com/maps?q=11.6498961,104.7584993', 'Phnom Penh', '11111', '', '2026-05-31 14:38:12', '2026-05-31 14:37:19', '2026-05-31 14:43:39', 'local', 0.00, 'not_ready', NULL, NULL, NULL, NULL),
(11, 6, 'ORD-6A1C4B026869A-1780239106', 199.00, 'delivered', 'qr_code', 'Visal', 'mrrvisal617@gmail.com', '010584267', 'https://www.google.com/maps?q=11.6498961,104.7584993', 'Phnom Penh', '11111', '', NULL, '2026-05-31 14:51:46', '2026-06-01 08:05:46', 'local', 0.00, 'not_ready', NULL, NULL, NULL, NULL),
(12, 6, 'ORD-6A1D440F4D139-1780302863', 100.00, 'delivered', 'qr_code', 'Visal', 'mrrvisal617@gmail.com', '010584267', 'https://www.google.com/maps?q=11.6498961,104.7584993', 'Phnom Penh', '11111', '', NULL, '2026-06-01 08:34:23', '2026-06-01 09:00:01', 'province', 0.00, 'delivered', NULL, NULL, '2026-06-05', '2026-06-01 09:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(10, 6, 28, 'Ashley Brocky Mirror', 199.00, 5, 995.00),
(11, 7, 27, 'Wooden Tabletop Vanity Mirror', 100.00, 1, 100.00),
(12, 8, 18, 'Kizzy Table Lamp', 260.00, 1, 260.00),
(13, 8, 27, 'Wooden Tabletop Vanity Mirror', 100.00, 1, 100.00),
(14, 9, 29, 'Sana Wooden Frame Standing Mirror', 100.00, 1, 100.00),
(15, 9, 11, 'Braxton Culler Durham Sofa', 2199.00, 2, 4398.00),
(16, 9, 28, 'Ashley Brocky Mirror', 199.00, 2, 398.00),
(17, 10, 28, 'Ashley Brocky Mirror', 199.00, 1, 199.00),
(18, 10, 29, 'Sana Wooden Frame Standing Mirror', 100.00, 1, 100.00),
(19, 11, 28, 'Ashley Brocky Mirror', 199.00, 1, 199.00),
(20, 12, 27, 'Wooden Tabletop Vanity Mirror', 100.00, 1, 100.00);

--
-- Triggers `order_items`
--
DELIMITER $$
CREATE TRIGGER `validate_order_item_subtotal` BEFORE INSERT ON `order_items` FOR EACH ROW BEGIN
    DECLARE expected_subtotal DECIMAL(10,2);
    SET expected_subtotal = NEW.price * NEW.quantity;
    IF NEW.subtotal != expected_subtotal THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Subtotal must equal price * quantity';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `otp_verifications`
--

CREATE TABLE `otp_verifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `type` enum('register','forgot_password') NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `otp_verifications`
--

INSERT INTO `otp_verifications` (`id`, `email`, `otp`, `type`, `expires_at`, `used`, `created_at`) VALUES
(8, 'mrrvisal617@gmail.com', '273818', 'forgot_password', '2026-05-29 12:38:56', 1, '2026-05-29 12:38:31');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(10) UNSIGNED DEFAULT 0,
  `reorder_level` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `reorder_quantity` int(10) UNSIGNED NOT NULL DEFAULT 10,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `reorder_level`, `reorder_quantity`, `supplier_id`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(10, 1, 'Kariam 37\" Fabric Chair', 'kariam-37-fabric-chair', 'This chair is designed to elevate any room. It features large welt detailing, a crisp box border, and is noted for its incredible comfort and scale. It is perfectly at home in any room setting.', 649.00, 0, 5, 10, 6, 'prod_69849b4d855b2.jpg', 1, '2026-02-05 13:29:49', '2026-06-01 07:58:48'),
(11, 1, 'Braxton Culler Durham Sofa', 'braxton-culler-durham-sofa', 'The Durham Sofa combines casual comfort with a transitional style.', 2199.00, 108, 100, 10, NULL, 'prod_69849be8bafb5.jpg', 1, '2026-02-05 13:32:24', '2026-05-31 15:28:50'),
(12, 1, 'Kooper 2 Seater Sofa', 'kooper-2-seater-sofa', 'The sofa features a modern, slightly quirky design with a dramatically shaped, curved backrest and an art deco style.', 300.00, 90, 5, 10, NULL, 'prod_69849c5caab3a.jpg', 1, '2026-02-05 13:34:20', '2026-02-05 13:34:20'),
(13, 1, 'Flex Blue Chair with Standard Arm', 'flex-blue-chair-with-standard-arm', 'It is a modular seating option designed for flexibility.', 1099.00, 50, 5, 10, NULL, 'prod_69849ccd56005.jpg', 0, '2026-02-05 13:36:13', '2026-05-31 15:26:44'),
(14, 6, 'modern Nordic wood and glass wall lamp', 'modern-nordic-wood-and-glass-wall-lamp', 'Modern and Nordic minimalist design.', 66.00, 150, 5, 10, NULL, 'prod_69849d30b02a2.jpg', 1, '2026-02-05 13:37:52', '2026-02-05 13:37:52'),
(15, 6, 'French Louis XVI', 'french-louis-xvi', 'The base is a detailed, baluster-shaped design, likely made of metal (such as brass or bronze) with an antique or golden finish. It features fluted and ribbed detailing.', 200.00, 120, 5, 10, NULL, 'prod_69849e788f3ff.jpg', 1, '2026-02-05 13:43:20', '2026-02-05 13:43:20'),
(16, 6, 'Antique Golden Elegant Table Lamp', 'antique-golden-elegant-table-lamp', 'This is a rustic and elegant table lamp produced in France in the 1950s. It is crafted from solid oakwood, showcasing a striking wood grain. The lamp is complemented by a new ivory shade in a tapered drum shape, designed to provide a soft and pleasant light. The wiring has been updated.', 700.00, 50, 5, 10, NULL, 'prod_69849eccb0108.jpg', 1, '2026-02-05 13:44:44', '2026-02-05 13:44:44'),
(17, 6, 'Glass Ball Wall Light', 'glass-ball-wall-light', 'This light fixture features a sleek, modern design with a spherical white glass shade mounted on a gold or brass-finished metal base and arm. It is often described as suitable for various indoor settings such as living rooms, bedrooms, hallways, and staircases. It uses an E27 or G9 light source and provides a warm, ambient glow.', 55.00, 220, 5, 10, NULL, 'prod_69849f31c638b.jpg', 1, '2026-02-05 13:46:25', '2026-02-05 13:46:25'),
(18, 6, 'Kizzy Table Lamp', 'kizzy-table-lamp', 'This modern art deco desk lamp features a unique design with a white metal shade, an arched white frame, a wooden base, and a small decorative bird figurine. It is a stylish piece suitable for various rooms.', 260.00, 39, 5, 10, NULL, 'prod_69849f73935c1.jpg', 1, '2026-02-05 13:47:31', '2026-05-29 12:39:26'),
(19, 4, 'Urbandale Arm Desk Chair', 'urbandale-arm-desk-chair', 'This chair is a traditional or mission-style office chair made of solid wood, often available in various wood types and finishes.', 1240.00, 10, 5, 10, NULL, 'prod_69849fed947f1.jpg', 1, '2026-02-05 13:49:33', '2026-02-05 13:49:33'),
(21, 4, 'Bulfinch chair', 'bulfinch-chair', 'The chair is known for its comfort, making it suitable for long meetings or meals. It can be ordered with or without arms and features the proprietary \"Eustis Joint\" construction, backed by a 20-year warranty.', 200.00, 108, 100, 10, NULL, 'prod_6984a0ab8c843.jpg', 1, '2026-02-05 13:52:43', '2026-05-31 15:28:49'),
(22, 4, 'Flexsteel Swift Hazelnut Power Recliner', 'flexsteel-swift-hazelnut-power-recliner', 'The recliner is upholstered in hazelnut brown top-grain leather with sweeping contrast stitching details and welt trim. It features soft, layered padded arms and a high-divided back for support.', 1800.00, 108, 100, 10, NULL, 'prod_6984a10bce627.jpg', 1, '2026-02-05 13:54:19', '2026-05-31 15:28:51'),
(23, 4, 'Power Headrest and Lumbar', 'power-headrest-and-lumbar', 'This contemporary recliner offers an extensive range of motion, including a power-adjustable headrest, lumbar support, and a lay-flat reclining mechanism.', 4000.00, 30, 5, 10, NULL, 'prod_6984a15fe8e42.jpg', 1, '2026-02-05 13:55:43', '2026-02-05 13:55:43'),
(24, 3, 'Charlotte Extendable Farmhouse Dining Table', 'charlotte-extendable-farmhouse-dining-table', 'This is a cross-leg, trestle base dining table designed for modern everyday living with a country twist.', 100.00, 20, 5, 10, NULL, 'prod_6984a1d676101.jpg', 1, '2026-02-05 13:57:42', '2026-02-05 13:57:42'),
(25, 3, 'Oval Solid Wood Narrow Side Table', 'oval-solid-wood-narrow-side-table', 'Features a modern minimalist design with a single, turned pedestal base and three curved legs.', 99.00, 28, 5, 10, NULL, 'prod_6984a21b379a2.jpg', 1, '2026-02-05 13:58:51', '2026-05-30 17:39:45'),
(26, 3, 'wooden dining table set', 'wooden-dining-table-set', 'This set is crafted from premium quality solid wood, likely featuring an antique or modern design. The table is rectangular, and the four chairs have slatted backs and cushioned seats. The set is noted for its durability and quality craftsmanship.', 800.00, 11, 5, 10, NULL, 'prod_6984a24fc700d.jpg', 1, '2026-02-05 13:59:43', '2026-05-31 14:29:18'),
(27, 2, 'Wooden Tabletop Vanity Mirror', 'wooden-tabletop-vanity-mirror', 'It features a natural, solid wood frame and base.', 100.00, 99, 100, 10, NULL, 'prod_6984a2c24cc01.jpg', 1, '2026-02-05 14:01:38', '2026-06-01 08:34:23'),
(28, 2, 'Ashley Brocky Mirror', 'ashley-brocky-mirror', 'It is a simple, round wall mirror with a black metal frame. It is a versatile accent piece suitable for various rooms in a home.', 199.00, 126, 5, 10, NULL, 'prod_6984a323824b0.jpg', 1, '2026-02-05 14:03:15', '2026-05-31 15:23:35'),
(29, 2, 'Sana Wooden Frame Standing Mirror', 'sana-wooden-frame-standing-mirror', 'This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.', 100.00, 9, 100, 10, NULL, 'prod_6984a35956581.jpg', 1, '2026-02-05 14:04:09', '2026-05-31 15:27:47'),
(30, 1, 'Elmwood Nesting Coffee Tables', 'elmwood-nesting-coffee-tables', 'A pair of round nesting coffee tables with warm elm veneer tops and slim black metal legs for compact living spaces.', 249.00, 35, 5, 10, 6, NULL, 1, '2026-06-01 10:15:00', '2026-06-01 10:15:00'),
(31, 1, 'Avery Linen Accent Armchair', 'avery-linen-accent-armchair', 'A soft linen accent chair with tapered oak legs, loose back cushion, and a clean profile for reading corners or lounge rooms.', 389.00, 24, 5, 10, 6, NULL, 1, '2026-06-01 10:16:00', '2026-06-01 10:16:00'),
(32, 2, 'Harbor Oak Queen Bed Frame', 'harbor-oak-queen-bed-frame', 'A queen bed frame with a paneled oak headboard, low footboard, and sturdy slat support for everyday bedroom use.', 699.00, 18, 5, 10, 6, NULL, 1, '2026-06-01 10:17:00', '2026-06-01 10:17:00'),
(33, 2, 'Mila Two Drawer Nightstand', 'mila-two-drawer-nightstand', 'A compact nightstand with two soft-close drawers, brushed metal pulls, and a natural wood finish.', 159.00, 42, 5, 10, 6, NULL, 1, '2026-06-01 10:18:00', '2026-06-01 10:18:00'),
(34, 3, 'Camden Round Dining Table', 'camden-round-dining-table', 'A round dining table with a pedestal base and durable walnut finish, sized for four comfortable seats.', 529.00, 16, 5, 10, 6, NULL, 1, '2026-06-01 10:19:00', '2026-06-01 10:19:00'),
(35, 3, 'Rattan Back Dining Chair Set', 'rattan-back-dining-chair-set', 'A set of two dining chairs with woven rattan backs, padded seats, and solid rubberwood frames.', 279.00, 30, 5, 10, 6, NULL, 1, '2026-06-01 10:20:00', '2026-06-01 10:20:00'),
(36, 4, 'Luna Compact Writing Desk', 'luna-compact-writing-desk', 'A compact writing desk with one storage drawer and an open shelf, designed for home offices and study rooms.', 219.00, 27, 5, 10, 6, NULL, 1, '2026-06-01 10:21:00', '2026-06-01 10:21:00'),
(37, 4, 'Ergo Mesh Task Chair', 'ergo-mesh-task-chair', 'An adjustable task chair with breathable mesh back, padded seat, tilt control, and smooth caster wheels.', 189.00, 33, 5, 10, 6, NULL, 1, '2026-06-01 10:22:00', '2026-06-01 10:22:00'),
(38, 6, 'Arden Ceramic Table Lamp', 'arden-ceramic-table-lamp', 'A ceramic table lamp with a textured white base and warm fabric shade for bedrooms, side tables, or console displays.', 89.00, 55, 5, 10, 6, NULL, 1, '2026-06-01 10:23:00', '2026-06-01 10:23:00'),
(39, 6, 'Linear Brass Floor Lamp', 'linear-brass-floor-lamp', 'A slim floor lamp with brushed brass finish, adjustable arm, and weighted base for focused ambient lighting.', 179.00, 21, 5, 10, 6, NULL, 1, '2026-06-01 10:24:00', '2026-06-01 10:24:00');

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `prevent_negative_stock` BEFORE UPDATE ON `products` FOR EACH ROW BEGIN
    IF NEW.stock < 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Stock cannot be negative';
    END IF;
    IF NEW.price < 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Price cannot be negative';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(1) UNSIGNED NOT NULL CHECK (`rating` between 1 and 5),
  `title` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `is_verified_purchase` tinyint(1) DEFAULT 1,
  `is_approved` tinyint(1) DEFAULT 0,
  `helpful_count` int(10) UNSIGNED DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `title`, `comment`, `is_verified_purchase`, `is_approved`, `helpful_count`, `created_at`, `updated_at`) VALUES
(1, 28, 6, 6, 5, '...', '.......', 1, 1, 1, '2026-05-30 16:33:38', '2026-05-30 16:36:47'),
(3, 29, 6, 10, 3, '....', '.....', 1, 0, 0, '2026-05-31 14:44:16', '2026-05-31 14:44:16');

-- --------------------------------------------------------

--
-- Table structure for table `review_helpful_votes`
--

CREATE TABLE `review_helpful_votes` (
  `id` int(10) UNSIGNED NOT NULL,
  `review_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review_helpful_votes`
--

INSERT INTO `review_helpful_votes` (`id`, `review_id`, `user_id`, `created_at`) VALUES
(1, 1, 6, '2026-05-30 16:36:47');

-- --------------------------------------------------------

--
-- Table structure for table `stock_alerts`
--

CREATE TABLE `stock_alerts` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `current_stock` int(11) NOT NULL,
  `reorder_level` int(11) NOT NULL,
  `alert_type` enum('low_stock','out_of_stock') NOT NULL,
  `status` enum('pending','sent','resolved') DEFAULT 'pending',
  `notified_at` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_alerts`
--

INSERT INTO `stock_alerts` (`id`, `product_id`, `current_stock`, `reorder_level`, `alert_type`, `status`, `notified_at`, `resolved_at`, `created_at`) VALUES
(1, 27, 0, 5, 'out_of_stock', 'resolved', NULL, '2026-05-30 18:40:34', '2026-05-30 18:38:54'),
(2, 11, 8, 5, 'low_stock', 'resolved', NULL, '2026-05-30 18:47:52', '2026-05-30 18:46:53'),
(3, 19, 10, 5, 'low_stock', 'resolved', NULL, '2026-05-30 18:48:22', '2026-05-30 18:46:53'),
(4, 21, 9, 5, 'low_stock', 'resolved', NULL, '2026-05-31 13:53:42', '2026-05-30 18:46:53'),
(5, 22, 8, 5, 'low_stock', 'resolved', NULL, '2026-05-31 13:44:58', '2026-05-30 18:46:53'),
(6, 26, 0, 5, 'out_of_stock', 'resolved', NULL, '2026-05-31 13:53:45', '2026-05-30 18:46:53'),
(7, 27, 2, 5, 'low_stock', 'resolved', NULL, '2026-05-31 14:26:23', '2026-05-31 14:25:11'),
(8, 27, 1, 5, 'low_stock', 'resolved', NULL, '2026-05-31 14:28:35', '2026-05-31 14:28:30'),
(9, 26, 0, 5, 'out_of_stock', 'resolved', NULL, '2026-05-31 14:28:57', '2026-05-31 14:28:53'),
(10, 26, 1, 5, 'low_stock', 'resolved', NULL, '2026-05-31 14:29:18', '2026-05-31 14:29:10'),
(11, 27, 9, 5, 'low_stock', 'resolved', NULL, '2026-05-31 14:32:44', '2026-05-31 14:29:39'),
(14, 22, 8, 5, 'low_stock', 'resolved', NULL, '2026-05-31 15:28:51', '2026-05-31 14:36:24'),
(15, 11, 8, 5, 'low_stock', 'resolved', NULL, '2026-05-31 15:28:50', '2026-05-31 14:36:38'),
(16, 21, 9, 5, 'low_stock', 'resolved', NULL, '2026-05-31 15:28:49', '2026-05-31 14:36:43'),
(17, 29, 10, 5, 'low_stock', 'resolved', NULL, '2026-05-31 14:38:20', '2026-05-31 14:37:01'),
(18, 29, 9, 100, 'low_stock', 'resolved', '2026-05-31 15:23:42', '2026-05-31 15:24:02', '2026-05-31 15:23:42'),
(19, 29, 9, 100, 'low_stock', 'sent', '2026-05-31 15:27:47', NULL, '2026-05-31 15:27:47'),
(20, 10, 0, 5, 'out_of_stock', 'sent', '2026-05-31 15:29:02', NULL, '2026-05-31 15:29:02');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_name`, `email`, `phone`, `address`, `is_active`, `created_at`) VALUES
(6, 'Ei Visal', 'Ei Visal', 'mrrvisal617@gmail.com', '', 'JQX5+X95, Phnom Penh', 1, '2026-05-31 16:18:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `email_verified_at`, `is_active`, `created_at`, `updated_at`) VALUES
(6, 'Visal', 'mrrvisal617@gmail.com', '$2y$10$b5dgyYGxDtFTq0.6N6MxCuC8U2e8g9qV8OcR0Z8rm8jAu4nkl8Tcy', '020282287', '2026-02-05 16:46:30', 0, '2026-02-05 16:46:31', '2026-05-31 16:30:16'),
(7, 'អុី វិសាល', 'eivisal617@gmail.com', '$2y$10$FbtoJIr.SUpYlzYKMDCfyuS70xC9I6Z012FioZADiTQAO5jl1kR4a', '010584267', '2026-05-31 14:07:22', 1, '2026-02-05 16:58:29', '2026-05-31 14:07:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_id` (`admin_id`),
  ADD KEY `idx_target` (`target_type`,`target_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_type` (`email`,`type`),
  ADD KEY `idx_expires` (`expires_at`),
  ADD KEY `idx_expires_used` (`expires_at`,`used`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_supplier` (`supplier_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product_order` (`user_id`,`product_id`,`order_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_approved_rating` (`is_approved`,`rating`),
  ADD KEY `product_reviews_ibfk_3` (`order_id`);

--
-- Indexes for table `review_helpful_votes`
--
ALTER TABLE `review_helpful_votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_review` (`review_id`,`user_id`),
  ADD KEY `review_helpful_votes_ibfk_2` (`user_id`);

--
-- Indexes for table `stock_alerts`
--
ALTER TABLE `stock_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `review_helpful_votes`
--
ALTER TABLE `review_helpful_votes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_alerts`
--
ALTER TABLE `stock_alerts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review_helpful_votes`
--
ALTER TABLE `review_helpful_votes`
  ADD CONSTRAINT `review_helpful_votes_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `product_reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_helpful_votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_alerts`
--
ALTER TABLE `stock_alerts`
  ADD CONSTRAINT `stock_alerts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
