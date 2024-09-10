-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 10, 2024 at 09:47 AM
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
-- Database: `ipss`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `customer_FirstName` varchar(255) NOT NULL,
  `customer_LastName` varchar(255) NOT NULL,
  `contact` varchar(30) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `document_upload` int(11) NOT NULL,
  `order_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `print_color` varchar(30) NOT NULL,
  `layout` varchar(30) NOT NULL,
  `pages_per_sheet` int(100) NOT NULL,
  `copies` int(100) NOT NULL,
  `material` varchar(30) NOT NULL,
  `duplex_printing` varchar(30) NOT NULL,
  `deliver_type` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(200) NOT NULL,
  `userType` varchar(255) DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiration` datetime DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `userType`, `reset_token`, `reset_token_expiration`, `verification_token`, `is_verified`) VALUES
(7, 'imaisarahm', 'imaisarahm@gmail.com', 'project1', '', NULL, NULL, NULL, 0),
(8, 'nui', 'nu@gmail.com', 'nui89', '', NULL, NULL, NULL, 0),
(9, 'dayangnurnazihah', 'dayangziha@gmail.com', '$2y$10$WlLtp6w..wW3dc.CS5d/FO257FtHcUfZmIefvvhkwqfU99XlllyTm', '', '1b56dd887071456e007dc4faa7bce07793f4b331f4518276ad545791c369324003ea6666d0b6ad3916d14897f200a03cc2cf', '2024-09-10 08:36:14', NULL, 0),
(10, 'dayang', 'dayangnurnazihah.m@gmail.com', '$2y$10$phllKQPmLowMaSxknOqqB.frtL15FoD4SJxAmk4Rrg5Oy2S.rBFTm', '', NULL, NULL, NULL, 0),
(11, 'ADMIN01', 'admin01@gmail.com', '$2y$10$QHtJGtHIjclbGxSeQgLwaO6xycM3NrABnYwHyl0lENmKbnir1GVpG', 'ADMIN', NULL, NULL, NULL, 0),
(12, 'dyg', 'dayangnurnazihah@graduate.utm.my', '$2y$10$fsUnHpiKxOEsJ1SQ1M.1EOqm0wk6s4SmcG/7eQVpTJPUNxlaGijEe', 'user', '2f34103baaabd338713ca369bf657873cab95c8c8b033dd76894b1c36e74afee389aab2036feac2150077d27fb9a4b1c8428', '2024-09-10 08:35:16', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_main`
--

CREATE TABLE `user_main` (
  `id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_main`
--
ALTER TABLE `user_main`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_main`
--
ALTER TABLE `user_main`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
