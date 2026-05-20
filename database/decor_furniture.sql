-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 05, 2026 at 06:03 PM
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
(1, 'admin', 'admin@decorfurniture.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', '2026-01-30 13:19:03');

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

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(15, 6, 18, 1, '2026-02-05 16:56:21');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `status`, `payment_method`, `shipping_name`, `shipping_email`, `shipping_phone`, `shipping_address`, `shipping_city`, `shipping_postcode`, `notes`, `paid_at`, `created_at`, `updated_at`) VALUES
(6, 6, 'ORD-6984C9A254D80-1770310050', 995.00, 'delivered', 'qr_code', 'Visal', 'mrrvisal617@gmail.com', '010584267', 'https://www.google.com/maps?q=11.6498961,104.7584993', 'Phnom Penh', '123', '.....', NULL, '2026-02-05 16:47:30', '2026-02-05 16:52:09'),
(7, 6, 'ORD-6984CAED12738-1770310381', 100.00, 'pending', 'pay_later', 'Visal', 'mrrvisal617@gmail.com', '010584267', 'https://www.google.com/maps?q=11.6498961,104.7584993', 'Phnom Penh', '11111', '...', NULL, '2026-02-05 16:53:01', '2026-02-05 16:53:01');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(10, 6, 28, 'Ashley Brocky Mirror', 199.00, 5, 995.00),
(11, 7, 27, 'Wooden Tabletop Vanity Mirror', 100.00, 1, 100.00);

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
(6, 'mrrvisal617@gmail.com', '425407', 'register', '2026-02-05 16:45:58', 1, '2026-02-05 16:45:54'),
(7, 'mrrvisal617@gmail.com', '998256', 'register', '2026-02-05 16:46:30', 1, '2026-02-05 16:45:58');

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
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(10, 1, 'Kariam 37\" Fabric Chair', 'kariam-37-fabric-chair', 'This chair is designed to elevate any room. It features large welt detailing, a crisp box border, and is noted for its incredible comfort and scale. It is perfectly at home in any room setting.', 649.00, 50, 'prod_69849b4d855b2.jpg', 1, '2026-02-05 13:29:49', '2026-02-05 13:29:49'),
(11, 1, 'Braxton Culler Durham Sofa', 'braxton-culler-durham-sofa', 'The Durham Sofa combines casual comfort with a transitional style.', 2199.00, 10, 'prod_69849be8bafb5.jpg', 1, '2026-02-05 13:32:24', '2026-02-05 13:32:24'),
(12, 1, 'Kooper 2 Seater Sofa', 'kooper-2-seater-sofa', 'The sofa features a modern, slightly quirky design with a dramatically shaped, curved backrest and an art deco style.', 300.00, 90, 'prod_69849c5caab3a.jpg', 1, '2026-02-05 13:34:20', '2026-02-05 13:34:20'),
(13, 1, 'Flex Blue Chair with Standard Arm', 'flex-blue-chair-with-standard-arm', 'It is a modular seating option designed for flexibility.', 1099.00, 50, 'prod_69849ccd56005.jpg', 1, '2026-02-05 13:36:13', '2026-02-05 13:36:13'),
(14, 6, 'modern Nordic wood and glass wall lamp', 'modern-nordic-wood-and-glass-wall-lamp', 'Modern and Nordic minimalist design.', 66.00, 150, 'prod_69849d30b02a2.jpg', 1, '2026-02-05 13:37:52', '2026-02-05 13:37:52'),
(15, 6, 'French Louis XVI', 'french-louis-xvi', 'The base is a detailed, baluster-shaped design, likely made of metal (such as brass or bronze) with an antique or golden finish. It features fluted and ribbed detailing.', 200.00, 120, 'prod_69849e788f3ff.jpg', 1, '2026-02-05 13:43:20', '2026-02-05 13:43:20'),
(16, 6, 'Antique Golden Elegant Table Lamp', 'antique-golden-elegant-table-lamp', 'This is a rustic and elegant table lamp produced in France in the 1950s. It is crafted from solid oakwood, showcasing a striking wood grain. The lamp is complemented by a new ivory shade in a tapered drum shape, designed to provide a soft and pleasant light. The wiring has been updated.', 700.00, 50, 'prod_69849eccb0108.jpg', 1, '2026-02-05 13:44:44', '2026-02-05 13:44:44'),
(17, 6, 'Glass Ball Wall Light', 'glass-ball-wall-light', 'This light fixture features a sleek, modern design with a spherical white glass shade mounted on a gold or brass-finished metal base and arm. It is often described as suitable for various indoor settings such as living rooms, bedrooms, hallways, and staircases. It uses an E27 or G9 light source and provides a warm, ambient glow.', 55.00, 220, 'prod_69849f31c638b.jpg', 1, '2026-02-05 13:46:25', '2026-02-05 13:46:25'),
(18, 6, 'Kizzy Table Lamp', 'kizzy-table-lamp', 'This modern art deco desk lamp features a unique design with a white metal shade, an arched white frame, a wooden base, and a small decorative bird figurine. It is a stylish piece suitable for various rooms.', 260.00, 40, 'prod_69849f73935c1.jpg', 1, '2026-02-05 13:47:31', '2026-02-05 13:47:31'),
(19, 4, 'Urbandale Arm Desk Chair', 'urbandale-arm-desk-chair', 'This chair is a traditional or mission-style office chair made of solid wood, often available in various wood types and finishes.', 1240.00, 10, 'prod_69849fed947f1.jpg', 1, '2026-02-05 13:49:33', '2026-02-05 13:49:33'),
(20, 4, 'Chair Opera', 'chair-opera', 'This is a contemporary-style dining chair typically handcrafted from solid hardwood like Sheesham wood. It features a low or mid-back design with horizontal slats and a solid wood seat. The chair shown has a rich, dark wood finish (e.g., mahogany finish). It is described as sturdy, versatile, and designed with a transitional feel that fits various home styles.', 319.00, 5, 'prod_6984a0597fc5d.jpg', 1, '2026-02-05 13:51:21', '2026-02-05 13:51:21'),
(21, 4, 'Bulfinch chair', 'bulfinch-chair', 'The chair is known for its comfort, making it suitable for long meetings or meals. It can be ordered with or without arms and features the proprietary \"Eustis Joint\" construction, backed by a 20-year warranty.', 200.00, 9, 'prod_6984a0ab8c843.jpg', 1, '2026-02-05 13:52:43', '2026-02-05 13:52:43'),
(22, 4, 'Flexsteel Swift Hazelnut Power Recliner', 'flexsteel-swift-hazelnut-power-recliner', 'The recliner is upholstered in hazelnut brown top-grain leather with sweeping contrast stitching details and welt trim. It features soft, layered padded arms and a high-divided back for support.', 1800.00, 8, 'prod_6984a10bce627.jpg', 1, '2026-02-05 13:54:19', '2026-02-05 13:54:19'),
(23, 4, 'Power Headrest and Lumbar', 'power-headrest-and-lumbar', 'This contemporary recliner offers an extensive range of motion, including a power-adjustable headrest, lumbar support, and a lay-flat reclining mechanism.', 4000.00, 30, 'prod_6984a15fe8e42.jpg', 1, '2026-02-05 13:55:43', '2026-02-05 13:55:43'),
(24, 3, 'Charlotte Extendable Farmhouse Dining Table', 'charlotte-extendable-farmhouse-dining-table', 'This is a cross-leg, trestle base dining table designed for modern everyday living with a country twist.', 100.00, 20, 'prod_6984a1d676101.jpg', 1, '2026-02-05 13:57:42', '2026-02-05 13:57:42'),
(25, 3, 'Oval Solid Wood Narrow Side Table', 'oval-solid-wood-narrow-side-table', 'Features a modern minimalist design with a single, turned pedestal base and three curved legs.', 99.00, 0, 'prod_6984a21b379a2.jpg', 1, '2026-02-05 13:58:51', '2026-02-05 13:58:51'),
(26, 3, 'wooden dining table set', 'wooden-dining-table-set', 'This set is crafted from premium quality solid wood, likely featuring an antique or modern design. The table is rectangular, and the four chairs have slatted backs and cushioned seats. The set is noted for its durability and quality craftsmanship.', 800.00, 7, 'prod_6984a24fc700d.jpg', 1, '2026-02-05 13:59:43', '2026-02-05 13:59:43'),
(27, 2, 'Wooden Tabletop Vanity Mirror', 'wooden-tabletop-vanity-mirror', 'It features a natural, solid wood frame and base.', 100.00, 5, 'prod_6984a2c24cc01.jpg', 1, '2026-02-05 14:01:38', '2026-02-05 16:53:01'),
(28, 2, 'Ashley Brocky Mirror', 'ashley-brocky-mirror', 'It is a simple, round wall mirror with a black metal frame. It is a versatile accent piece suitable for various rooms in a home.', 199.00, 130, 'prod_6984a323824b0.jpg', 1, '2026-02-05 14:03:15', '2026-02-05 16:47:30'),
(29, 2, 'Sana Wooden Frame Standing Mirror', 'sana-wooden-frame-standing-mirror', 'This standing mirror features a sleek wooden frame that adds a touch of contemporary style to any room. It is aptly sized to provide ample reflection space, and its sturdy construction ensures long-lasting durability.', 100.00, 220, 'prod_6984a35956581.jpg', 1, '2026-02-05 14:04:09', '2026-02-05 14:04:09');

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
(6, 'Visal', 'mrrvisal617@gmail.com', '$2y$10$T70BvyLS./2LU.ER23xQU.gSOebjq3CNEUcYIkCNhdDBbmk0TYY5i', '020282287', '2026-02-05 16:46:30', 1, '2026-02-05 16:46:31', '2026-02-05 16:57:47'),
(7, 'អុី វិសាល', 'eivisal617@gmail.com', '$2y$10$FbtoJIr.SUpYlzYKMDCfyuS70xC9I6Z012FioZADiTQAO5jl1kR4a', '010584267', '2026-02-05 16:58:29', 1, '2026-02-05 16:58:29', '2026-02-05 16:58:29');

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
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

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
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

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
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
