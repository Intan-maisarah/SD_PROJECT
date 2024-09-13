-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 13, 2024 at 02:44 AM
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
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiration` varchar(255) DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `userType` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `contact`, `address`, `password`, `reset_token`, `reset_token_expiration`, `verification_token`, `is_verified`, `userType`) VALUES
(9, 'dayangnurnazihah', '', 'dayangziha@gmail.com', '', NULL, '$2y$10$WlLtp6w..wW3dc.CS5d/FO257FtHcUfZmIefvvhkwqfU99XlllyTm', '', '', '', 0, ''),
(17, 'hannah', NULL, 'nrhannahndhrh@gmail.com', NULL, NULL, '$2y$10$3/w1TTjiYzAW3yyhJlEVpOwla/MjmUejNVOYtC8HJYnPHY71GQ60u', NULL, NULL, '27d0edd15d2d0a8076f87d319cc08050', 0, 'user'),
(20, 'ADMIN01', 'dayangnurnazihah', 'dayangnurnazihah.m@gmail.com', '0105190074', 'seri kembangan', '$2y$10$dXOkoXvje6klz3mNf3Fz9OXnkby//YO5NtUnlSKbZMRtC99SfBSaO', NULL, NULL, NULL, 1, 'ADMIN'),
(30, 'yaesh', 'YAESHVAANT', 'yaeshvaant123@gmail.com', '1234231232', 'RAWANG', '$2y$10$hrG4u3fSu79aqn7Nu.cbsuNfJg3mah2I.PZJu7c4LErPIDz0d/3/q', NULL, NULL, NULL, 1, 'user'),
(31, 'hakimee', 'HAKIMEE', 'ksskso07@gmail.com', '0177564868', 'johor', '$2y$10$b9p/camnOnBDzjLwgXt5/.zbvzeVeLX15UDtMg1rJLYhPVqDzOavK', NULL, NULL, '35c020b14c10c44b420251cc3dcc0e60', 0, 'STAFF'),
(32, 'ADMIN02', NULL, 'ADMIN02@gmail.com', NULL, NULL, '$2y$10$W29d357TpZo.Cj8oVrFsm.UtH5BPDJFmTTiapjpYpMR8gQzk2qghK', NULL, NULL, '18866de5b6744a73f80a6f3ef83ea44c', 0, 'ADMIN\r\n');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
