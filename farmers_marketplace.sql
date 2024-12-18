-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 06:21 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `farmers_marketplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_name`, `user_email`, `address`, `total_price`, `order_date`) VALUES
(1, 'Syed Muhammad Umer Mujahid Hassni', 'syedumerhassni@gmail.com', 'Near Al Muslim Girls High School, Bazam-e-ale Hasan, Bhai Wala', '111.00', '2024-12-18 04:37:56'),
(2, 'Syed Muhammad Umer Mujahid Hassni', 'syedumerhassni@gmail.com', 'Near Al Muslim Girls High School Bazam e Ale Hasan, 202RB Bhaiwala, Faisalabad ', '30.00', '2024-12-18 04:41:08'),
(3, 'Syed Muhammad Umer Mujahid Hassni', 'syedumerhassni@gmail.com', 'Near Al Muslim Girls High School Bazam e Ale Hasan, 202RB Bhaiwala, Faisalabad ', '25.00', '2024-12-18 04:42:17'),
(4, 'Syed Muhammad Umer Mujahid Hassni', 'syedumerhassni@gmail.com', 'Near Al Muslim Girls High School Bazam e Ale Hasan, 202RB Bhaiwala, Faisalabad ', '2.00', '2024-12-18 04:47:12'),
(5, 'Syed Muhammad Umer Mujahid Hassni', 'syedumerhassni@gmail.com', 'Near Al Muslim Girls High School, Bazam-e-ale Hasan, Bhai Wala', '5.00', '2024-12-18 05:11:21'),
(6, 'Syed Muhammad Umer Mujahid Hassni', 'syedumerhassni@gmail.com', 'Near Al Muslim Girls High School, Bazam-e-ale Hasan, Bhai Wala', '24.00', '2024-12-18 05:15:04');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`detail_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 2, 2, '38.00'),
(2, 1, 1, 1, '35.00'),
(3, 2, 3, 15, '2.00'),
(4, 3, 7, 1, '25.00'),
(5, 4, 3, 1, '2.00'),
(6, 5, 4, 1, '5.00'),
(7, 6, 4, 2, '5.00'),
(8, 6, 3, 7, '2.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `farmer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `name`, `description`, `price`, `image`, `created_at`, `farmer_id`) VALUES
(1, 2, 'urea', '', '35.00', 'uploads/Screenshot 2024-11-29 191859.png', '2024-12-17 18:41:00', 0),
(2, 2, 'crops', '', '38.00', 'uploads/DCA9B01A-D203-49CC-9E83-018C7585B3BF.png', '2024-12-17 18:49:44', 0),
(3, 2, 'tomato seeds', '', '2.00', 'uploads/Screenshot 2024-11-21 213849.png', '2024-12-17 20:11:55', 0),
(4, 2, 'almond seed', '', '5.00', 'uploads/Screenshot 2024-11-21 213849.png', '2024-12-17 20:17:35', 0),
(5, 2, 'mangos', '0', '25.00', 'uploads/Screenshot 2024-11-21 213849.png', '2024-12-17 20:42:06', 0),
(6, 2, 'apple seeds', 'best quality', '25.00', 'uploads/Screenshot 2024-11-21 213849.png', '2024-12-17 20:43:49', 0),
(7, 4, 'Urea special', 'very unique product', '25.00', 'uploads/Screenshot 2024-11-21 213849.png', '2024-12-18 04:42:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, 5, 'wow', '2024-12-17 18:41:33'),
(2, 6, 1, 2, 'good', '2024-12-17 20:44:23'),
(3, 6, 3, 4, 'wow', '2024-12-17 20:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('farmer','buyer','admin') DEFAULT 'buyer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Syed Muhammad Umer Mujahid Hassni', 'syedumerhassni@gmail.com', '$2y$10$wZDLXwwdw/WhoVH9loCum.MPm8QIDh1/4a2OPQJMys51v1YeGisTy', 'buyer', '2024-12-17 17:55:43'),
(2, 'Rafay', 'abc@gmail.com', '$2y$10$tkAYYsiOI1yENf3vPTtOheCqUyz5KjSIH3a/SIIsclL89TjSysw62', 'farmer', '2024-12-17 18:22:29'),
(3, 'usama', 'xyz@gmail.com', '$2y$10$3EQx6JszPzpn8/qaFmAfEuizQCDCI2U9dfMaBZwcU936/wrsWZc5u', 'buyer', '2024-12-17 20:44:39'),
(4, 'Ft-umer', 'syedsb1122@gmail.com', '$2y$10$ZVXzxo4KVqj3N013O2XUb.O2YFXVVzeykkwERBrfnLVp7cUPsGkFq', 'farmer', '2024-12-18 04:41:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
