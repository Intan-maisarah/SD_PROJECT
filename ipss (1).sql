-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 05:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
  `User_id` int(255) NOT NULL,
  `service_id` int(11) NOT NULL,
  `specification_id` int(11) NOT NULL,
  `document_upload` int(11) NOT NULL,
  `order_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_description` text NOT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `service_description`, `status`, `image`) VALUES
(1, 'Document Upload', 'Got a last-minute print job you need by morning? No problem! Upload your documents anytime, day or night, and we\'ll ensure they\'re printed and ready for you when you need them. Fast, reliable, and hassle-free printing at your convenience.', 'unavailable', '../../assets/images/uploads/admin.jpeg'),
(2, 'Schedule Appointment', 'Need your document at your convenient time? Ask and you shall receive! We allow you to schedule an appointment for pickup within our working hours, just set it and we\'ll prepare your documents.', 'available', '../../assets/images/uploads/appointment.jpg'),
(5, 'Track Your Order', 'Get the latest updates on your order status! Whether you\'re waiting for your printing job to be completed or ready for pickup or delivery, simply enter your order number to receive real-time information. Stay informed every step of the way and never miss an update', 'available', '../../assets/images/uploads/track.jpg'),
(8, 'Delivery', 'Enjoy fast and reliable delivery within a 1km range from our location. Whether it\'s a large print order or a single document, we\'ll ensure your items reach you siwftly and securely. Just share your delivery details during checkout, and leave the rest to us.', 'available', '../../assets/images/uploads/delivery.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `specification`
--

CREATE TABLE `specification` (
  `id` int(11) NOT NULL,
  `spec_name` varchar(255) NOT NULL,
  `spec_type` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `status` enum('available','unavailable') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specification`
--

INSERT INTO `specification` (`id`, `spec_name`, `spec_type`, `price`, `status`) VALUES
(3, 'Paper Size', 'A4', 0.5, 'available'),
(41, 'Paper Size', 'A3', 0.5, 'available'),
(57, 'Orientation', 'Portrait', 0, 'available'),
(58, 'Orientation', 'Landscape', 0, 'available'),
(59, 'Colour', 'Colour', 1, 'available'),
(60, 'Colour', 'Black and White', 0.1, 'available');

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
  `userType` varchar(255) NOT NULL DEFAULT 'user',
  `profile_pic` varchar(255) DEFAULT '../assets/profile_pic/default-placeholder.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `contact`, `address`, `password`, `reset_token`, `reset_token_expiration`, `verification_token`, `is_verified`, `userType`, `profile_pic`) VALUES
(9, 'dayangnurnazihah', 'DAYANG NUR NAZIHAH', 'dayangziha@gmail.com', '60105190074', 'serdang', '$2y$10$rkd.DhNocSMlWA7euxNVdubm1fhhfrTubWj70v/zIc819W15SiGNm', NULL, NULL, '', 0, '', '../assets/profile_pic/default-placeholder.png'),
(17, 'hannah', 'hannah', 'nrhannahndhrh@gmail.com', '0104392835', 'selangor', '$2y$10$3/w1TTjiYzAW3yyhJlEVpOwla/MjmUejNVOYtC8HJYnPHY71GQ60u', NULL, NULL, '27d0edd15d2d0a8076f87d319cc08050', 0, 'user', '../assets/profile_pic/default-placeholder.png'),
(20, 'ADMIN01', 'dayangnurnazihah', 'dayangnurnazihah.m@gmail.com', '0105190074', 'seri kembangan', '$2y$10$YRkmOWjFq2c3.ER0TTwc2uL5BRNVPLpSk2.M2WSytq9SoDk0jqSf.', '288293d24ad7df8bb993e9dba99626a6e5a7dd359ec7e4bfdd06083211f2df52a730bae457c9006f9eec16059aaf729d2d39', '2024-10-08 04:45:53', NULL, 1, 'ADMIN', '../assets/profile_pic/_ (1).jpeg'),
(30, 'yaesh', 'YAESHVAANT', 'yaeshvaant123@gmail.com', '1234231232', 'RAWANG!', '$2y$10$hrG4u3fSu79aqn7Nu.cbsuNfJg3mah2I.PZJu7c4LErPIDz0d/3/q', NULL, NULL, NULL, 1, 'user', '../assets/profile_pic/default-placeholder.png'),
(31, 'hakimee', 'HAKIMEE', 'ksskso07@gmail.com', '0177564868', 'pasir gudang', '$2y$10$WJ5fZhaz5kDtt.cfqqkwi.haExnkdRtcUtcAxhVE5Z8RuyJ5UJnCK', NULL, NULL, '35c020b14c10c44b420251cc3dcc0e60', 0, 'STAFF', '../assets/profile_pic/IMG_6079 Small.jpeg'),
(32, 'ADMIN02', NULL, 'ADMIN02@gmail.com', NULL, NULL, '$2y$10$W29d357TpZo.Cj8oVrFsm.UtH5BPDJFmTTiapjpYpMR8gQzk2qghK', NULL, NULL, '18866de5b6744a73f80a6f3ef83ea44c', 0, 'ADMIN\r\n', '../assets/profile_pic/default-placeholder.png'),
(41, 'yash', 'yashweni', 'yashweni5@gmail.com', '0193238423', 'pahang', '$2y$10$lt10e1Nq37SidxBiYiE4jui85XJ1d43s9/pEuiOAhLwOaSEEcGabW', NULL, NULL, 'd70b7a554c458b5a0424c23236b3de00', 0, 'user', '../assets/profile_pic/default-placeholder.png'),
(42, 'daniaqeelah', 'dania', 'daniaqeelah@gmail.com', '0139284382', 'putrajaya', '$2y$10$93ATEOXQVnbWBPxVQZszZOn1TfomiGU5ThXQYVxWcnxjb4pJDRHaO', NULL, NULL, 'd26e029eb0ca0f449d1244d75d9954e7', 0, 'STAFF', '../assets/profile_pic/default-placeholder.png'),
(43, 'dania', 'dani', 'daniaqeelah0606@gmail.com', '0193728372', 'putrajaya', '$2y$10$FaGlzmN7XHBQZuxMH0wPTuPRDyv57We2QUi28W0p9HZJ2.Lqnyr1C', NULL, NULL, 'c542e236d7192b4aff5cc06541434b93', 0, 'user', '../assets/profile_pic/default-placeholder.png'),
(45, 'nadhirah', 'hannah', 'nurhannahnaddhirah@gmail.com', '0103284928', 'kajang', '$2y$10$dNNsE.khycVFw305bfqZauH067Bv.BBGzQpqTNbSpU2Nx3sHoTZRS', NULL, NULL, 'fb3d329713016d160a8183e2d102ed1e', 0, 'STAFF', '../assets/profile_pic/default-placeholder.png'),
(46, 'dygnzh', 'daye', 'dygnzh@gmail.com', '60105190074', 'seri kembangan', '$2y$10$xZUXhJ/D30JPgti4dtOceOfnUubeLTN5b0MVi.zG62dA5QOoA2j3S', NULL, NULL, NULL, 0, 'STAFF', '../assets/profile_pic/default-placeholder.png'),
(50, 'staffn', 'baru', 'imaisarahm@gmail.com', '0164412020', '105 jalan sutera, 2/5 taman sutera', '$2y$10$.Zk9KWEg1/vYt3HEp4tPqe4L1m4LzYUN07dlUipIWPcjl440kumvi', NULL, NULL, NULL, 0, 'STAFF', '../assets/profile_pic/default-placeholder.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `User_id` (`User_id`,`service_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `specification_id` (`specification_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `specification`
--
ALTER TABLE `specification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `specification`
--
ALTER TABLE `specification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`),
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`User_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`specification_id`) REFERENCES `specification` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
