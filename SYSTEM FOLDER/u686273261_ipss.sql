-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 13, 2024 at 01:38 AM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u686273261_ipss`
--

-- --------------------------------------------------------

--
-- Table structure for table `delivery_locations`
--

CREATE TABLE `delivery_locations` (
  `id` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_locations`
--

INSERT INTO `delivery_locations` (`id`, `location_name`) VALUES
(1, 'MJIIT'),
(2, 'ANJUNG MENARA RAZAK'),
(3, 'STUDENT LOUNGE');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(50) NOT NULL,
  `BillCode` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `payment_status` enum('PAID','UNPAID','PENDING') NOT NULL DEFAULT 'UNPAID',
  `total_order_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `pickup_appointment` datetime DEFAULT NULL,
  `delivery_location_id` int(11) DEFAULT NULL,
  `delivery_time` datetime DEFAULT NULL,
  `delivery_method` enum('pickup','delivery') DEFAULT 'pickup',
  `payment_method` varchar(50) NOT NULL DEFAULT 'online',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `BillCode`, `user_id`, `payment_status`, `total_order_price`, `status`, `pickup_appointment`, `delivery_location_id`, `delivery_time`, `delivery_method`, `payment_method`, `created_at`) VALUES
('orderNum074760', 'jiyvsswr', 9, 'PAID', 1.50, 'pending', '2024-11-13 15:46:00', NULL, NULL, 'pickup', 'online', '2024-11-11 06:46:39'),
('orderNum233B88', 'wrxevgtd', 9, 'PAID', 1.50, 'completed', NULL, NULL, NULL, 'pickup', 'online', '2024-11-10 23:40:40'),
('orderNum2564F3', 'uky0xxa4', 41, 'PAID', 1.50, 'pending', '2024-11-11 11:50:00', NULL, NULL, 'pickup', 'online', '2024-11-11 03:50:14'),
('orderNum496F69', NULL, 9, 'UNPAID', 0.00, 'pending', NULL, NULL, NULL, 'pickup', 'online', '2024-11-12 15:49:00'),
('orderNum58822C', NULL, 9, 'PENDING', 16.50, 'in_progress', NULL, NULL, NULL, 'pickup', 'offline', '2024-11-11 00:41:45'),
('orderNum606B7B', '5sjgntxc', 9, 'PAID', 1.50, 'pending', '2024-11-11 11:48:00', NULL, NULL, 'pickup', 'online', '2024-11-11 03:47:50'),
('orderNum67BAE7', 'l91pnv83', 9, 'PAID', 1.50, 'pending', '2024-11-11 11:47:00', NULL, NULL, 'pickup', 'online', '2024-11-11 03:47:38'),
('orderNum763CF9', '024s2eed', 9, 'PAID', 4.50, 'pending', '2024-11-11 11:45:00', NULL, NULL, 'pickup', 'online', '2024-11-11 03:45:24'),
('orderNum7883BC', 'cs87a3av', 9, 'PAID', 1.50, 'pending', '2024-11-13 12:22:00', NULL, NULL, 'pickup', 'online', '2024-11-12 16:21:58'),
('orderNum9B17E9', 'm1ms0p5v', 9, 'PAID', 1.50, 'completed', '2024-11-11 12:54:00', NULL, NULL, 'pickup', 'online', '2024-11-11 00:54:08'),
('orderNum9F4E83', 'gh9tpfm0', 41, 'PAID', 7.10, 'pending', NULL, 2, '2024-11-13 12:10:00', 'delivery', 'online', '2024-11-11 04:09:14'),
('orderNumBE807C', '3jcuwawk', 9, 'PAID', 1.50, 'pending', '2024-11-11 13:39:00', NULL, NULL, 'pickup', 'online', '2024-11-10 17:38:58'),
('orderNumCABE11', NULL, 9, 'PENDING', 32.00, 'pending', NULL, 3, '2024-11-14 13:01:00', 'delivery', 'offline', '2024-11-11 01:01:28'),
('orderNumDADADB', 'hvqig91e', 9, 'UNPAID', 1.50, 'pending', '2024-11-12 10:01:00', NULL, NULL, 'pickup', 'online', '2024-11-11 08:01:27'),
('orderNumDF9E30', NULL, 9, 'UNPAID', 0.00, 'pending', NULL, NULL, NULL, 'pickup', 'online', '2024-11-11 08:08:50'),
('orderNumE1EF4D', '8yihc2bg', 9, 'PAID', 6.00, 'in_progress', NULL, NULL, NULL, 'pickup', 'online', '2024-11-10 23:05:22'),
('orderNumE2D87E', NULL, 9, 'PENDING', 0.60, 'pending', '2024-11-13 12:35:00', NULL, NULL, 'pickup', 'offline', '2024-11-12 16:35:20'),
('orderNumFD9CF4', 'm9a2cazo', 9, 'PAID', 3.00, 'pending', NULL, NULL, NULL, 'pickup', 'online', '2024-11-10 23:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `detail_id` int(11) NOT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `specification_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `page_count` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL,
  `doc_id` int(11) DEFAULT NULL,
  `document_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`detail_id`, `order_id`, `specification_id`, `price`, `quantity`, `page_count`, `total_price`, `doc_id`, `document_id`) VALUES
(1618, 'orderNumE1EF4D', 3, 0.50, 1, 1, 0.50, NULL, 22),
(1619, 'orderNumE1EF4D', 57, 0.00, 1, 1, 0.00, NULL, 22),
(1620, 'orderNumE1EF4D', 59, 1.00, 1, 1, 1.00, NULL, 22),
(1621, 'orderNumE1EF4D', 64, 0.00, 1, 1, 0.00, NULL, 22),
(1622, 'orderNumE1EF4D', 3, 0.50, 1, 0, 0.00, NULL, 23),
(1623, 'orderNumE1EF4D', 57, 0.00, 1, 0, 0.00, NULL, 23),
(1624, 'orderNumE1EF4D', 59, 1.00, 1, 0, 0.00, NULL, 23),
(1625, 'orderNumE1EF4D', 64, 0.00, 1, 0, 0.00, NULL, 23),
(1626, 'orderNumE1EF4D', 3, 0.50, 1, 1, 0.50, NULL, 22),
(1627, 'orderNumE1EF4D', 57, 0.00, 1, 1, 0.00, NULL, 22),
(1628, 'orderNumE1EF4D', 59, 1.00, 1, 1, 1.00, NULL, 22),
(1629, 'orderNumE1EF4D', 64, 0.00, 1, 1, 0.00, NULL, 22),
(1630, 'orderNumE1EF4D', 3, 0.50, 1, 0, 0.00, NULL, 23),
(1631, 'orderNumE1EF4D', 57, 0.00, 1, 0, 0.00, NULL, 23),
(1632, 'orderNumE1EF4D', 59, 1.00, 1, 0, 0.00, NULL, 23),
(1633, 'orderNumE1EF4D', 64, 0.00, 1, 0, 0.00, NULL, 23),
(1634, 'orderNumE1EF4D', 3, 0.50, 1, 3, 1.50, NULL, 22),
(1635, 'orderNumE1EF4D', 57, 0.00, 1, 3, 0.00, NULL, 22),
(1636, 'orderNumE1EF4D', 59, 1.00, 1, 3, 3.00, NULL, 22),
(1637, 'orderNumE1EF4D', 64, 0.00, 1, 3, 0.00, NULL, 22),
(1638, 'orderNumE1EF4D', 3, 0.50, 1, 1, 0.50, NULL, 23),
(1639, 'orderNumE1EF4D', 57, 0.00, 1, 1, 0.00, NULL, 23),
(1640, 'orderNumE1EF4D', 59, 1.00, 1, 1, 1.00, NULL, 23),
(1641, 'orderNumE1EF4D', 64, 0.00, 1, 1, 0.00, NULL, 23),
(1642, 'orderNumE1EF4D', 3, 0.50, 1, 3, 1.50, NULL, 22),
(1643, 'orderNumE1EF4D', 57, 0.00, 1, 3, 0.00, NULL, 22),
(1644, 'orderNumE1EF4D', 59, 1.00, 1, 3, 3.00, NULL, 22),
(1645, 'orderNumE1EF4D', 64, 0.00, 1, 3, 0.00, NULL, 22),
(1646, 'orderNumE1EF4D', 3, 0.50, 1, 1, 0.50, NULL, 23),
(1647, 'orderNumE1EF4D', 57, 0.00, 1, 1, 0.00, NULL, 23),
(1648, 'orderNumE1EF4D', 59, 1.00, 1, 1, 1.00, NULL, 23),
(1649, 'orderNumE1EF4D', 64, 0.00, 1, 1, 0.00, NULL, 23),
(1650, 'orderNumE1EF4D', 3, 0.50, 1, 3, 1.50, NULL, 22),
(1651, 'orderNumE1EF4D', 57, 0.00, 1, 3, 0.00, NULL, 22),
(1652, 'orderNumE1EF4D', 59, 1.00, 1, 3, 3.00, NULL, 22),
(1653, 'orderNumE1EF4D', 64, 0.00, 1, 3, 0.00, NULL, 22),
(1654, 'orderNumE1EF4D', 3, 0.50, 1, 1, 0.50, NULL, 23),
(1655, 'orderNumE1EF4D', 57, 0.00, 1, 1, 0.00, NULL, 23),
(1656, 'orderNumE1EF4D', 59, 1.00, 1, 1, 1.00, NULL, 23),
(1657, 'orderNumE1EF4D', 64, 0.00, 1, 1, 0.00, NULL, 23),
(1658, 'orderNumE1EF4D', 3, 0.50, 1, 3, 1.50, NULL, 22),
(1659, 'orderNumE1EF4D', 57, 0.00, 1, 3, 0.00, NULL, 22),
(1660, 'orderNumE1EF4D', 59, 1.00, 1, 3, 3.00, NULL, 22),
(1661, 'orderNumE1EF4D', 64, 0.00, 1, 3, 0.00, NULL, 22),
(1662, 'orderNumE1EF4D', 3, 0.50, 1, 1, 0.50, NULL, 23),
(1663, 'orderNumE1EF4D', 57, 0.00, 1, 1, 0.00, NULL, 23),
(1664, 'orderNumE1EF4D', 59, 1.00, 1, 1, 1.00, NULL, 23),
(1665, 'orderNumE1EF4D', 64, 0.00, 1, 1, 0.00, NULL, 23),
(1666, 'orderNumE1EF4D', 3, 0.50, 1, 3, 1.50, NULL, 22),
(1667, 'orderNumE1EF4D', 57, 0.00, 1, 3, 0.00, NULL, 22),
(1668, 'orderNumE1EF4D', 59, 1.00, 1, 3, 3.00, NULL, 22),
(1669, 'orderNumE1EF4D', 64, 0.00, 1, 3, 0.00, NULL, 22),
(1670, 'orderNumE1EF4D', 3, 0.50, 1, 1, 0.50, NULL, 23),
(1671, 'orderNumE1EF4D', 57, 0.00, 1, 1, 0.00, NULL, 23),
(1672, 'orderNumE1EF4D', 59, 1.00, 1, 1, 1.00, NULL, 23),
(1673, 'orderNumE1EF4D', 64, 0.00, 1, 1, 0.00, NULL, 23),
(1674, 'orderNumE1EF4D', 3, 0.50, 1, 3, 1.50, NULL, 22),
(1675, 'orderNumE1EF4D', 57, 0.00, 1, 3, 0.00, NULL, 22),
(1676, 'orderNumE1EF4D', 59, 1.00, 1, 3, 3.00, NULL, 22),
(1677, 'orderNumE1EF4D', 64, 0.00, 1, 3, 0.00, NULL, 22),
(1678, 'orderNumE1EF4D', 3, 0.50, 1, 1, 0.50, NULL, 23),
(1679, 'orderNumE1EF4D', 57, 0.00, 1, 1, 0.00, NULL, 23),
(1680, 'orderNumE1EF4D', 59, 1.00, 1, 1, 1.00, NULL, 23),
(1681, 'orderNumE1EF4D', 64, 0.00, 1, 1, 0.00, NULL, 23),
(1682, 'orderNumE1EF4D', 3, 0.50, 1, 3, 1.50, NULL, 22),
(1683, 'orderNumE1EF4D', 57, 0.00, 1, 3, 0.00, NULL, 22),
(1684, 'orderNumE1EF4D', 59, 1.00, 1, 3, 3.00, NULL, 22),
(1685, 'orderNumE1EF4D', 64, 0.00, 1, 3, 0.00, NULL, 22),
(1686, 'orderNumE1EF4D', 3, 0.50, 1, 1, 0.50, NULL, 23),
(1687, 'orderNumE1EF4D', 57, 0.00, 1, 1, 0.00, NULL, 23),
(1688, 'orderNumE1EF4D', 59, 1.00, 1, 1, 1.00, NULL, 23),
(1689, 'orderNumE1EF4D', 64, 0.00, 1, 1, 0.00, NULL, 23),
(1690, 'orderNumE1EF4D', 3, 0.50, 1, 3, 1.50, NULL, 22),
(1691, 'orderNumE1EF4D', 57, 0.00, 1, 3, 0.00, NULL, 22),
(1692, 'orderNumE1EF4D', 59, 1.00, 1, 3, 3.00, NULL, 22),
(1693, 'orderNumE1EF4D', 64, 0.00, 1, 3, 0.00, NULL, 22),
(1694, 'orderNumE1EF4D', 3, 0.50, 1, 1, 0.50, NULL, 23),
(1695, 'orderNumE1EF4D', 57, 0.00, 1, 1, 0.00, NULL, 23),
(1696, 'orderNumE1EF4D', 59, 1.00, 1, 1, 1.00, NULL, 23),
(1697, 'orderNumE1EF4D', 64, 0.00, 1, 1, 0.00, NULL, 23),
(1698, 'orderNumFD9CF4', 3, 0.50, 1, 1, 0.50, NULL, 24),
(1699, 'orderNumFD9CF4', 57, 0.00, 1, 1, 0.00, NULL, 24),
(1700, 'orderNumFD9CF4', 59, 1.00, 1, 1, 1.00, NULL, 24),
(1701, 'orderNumFD9CF4', 64, 0.00, 1, 1, 0.00, NULL, 24),
(1702, 'orderNumFD9CF4', 3, 0.50, 1, 1, 0.50, NULL, 25),
(1703, 'orderNumFD9CF4', 57, 0.00, 1, 1, 0.00, NULL, 25),
(1704, 'orderNumFD9CF4', 59, 1.00, 1, 1, 1.00, NULL, 25),
(1705, 'orderNumFD9CF4', 64, 0.00, 1, 1, 0.00, NULL, 25),
(1706, 'orderNum233B88', 3, 0.50, 1, 1, 0.50, NULL, 26),
(1707, 'orderNum233B88', 57, 0.00, 1, 1, 0.00, NULL, 26),
(1708, 'orderNum233B88', 59, 1.00, 1, 1, 1.00, NULL, 26),
(1709, 'orderNum233B88', 64, 0.00, 1, 1, 0.00, NULL, 26),
(1726, 'orderNum58822C', 3, 0.50, 1, 11, 5.50, NULL, 29),
(1727, 'orderNum58822C', 57, 0.00, 1, 11, 0.00, NULL, 29),
(1728, 'orderNum58822C', 59, 1.00, 1, 11, 11.00, NULL, 29),
(1729, 'orderNum58822C', 64, 0.00, 1, 11, 0.00, NULL, 29),
(1730, 'orderNum9B17E9', 3, 0.50, 1, 1, 0.50, NULL, 30),
(1731, 'orderNum9B17E9', 57, 0.00, 1, 1, 0.00, NULL, 30),
(1732, 'orderNum9B17E9', 59, 1.00, 1, 1, 1.00, NULL, 30),
(1733, 'orderNum9B17E9', 64, 0.00, 1, 1, 0.00, NULL, 30),
(1734, 'orderNumCABE11', 3, 0.50, 1, 20, 10.00, NULL, 31),
(1735, 'orderNumCABE11', 57, 0.00, 1, 20, 0.00, NULL, 31),
(1736, 'orderNumCABE11', 59, 1.00, 1, 20, 20.00, NULL, 31),
(1737, 'orderNumCABE11', 64, 0.00, 1, 20, 0.00, NULL, 31),
(1782, 'orderNumBE807C', 3, 0.50, 1, 1, 0.50, NULL, 37),
(1783, 'orderNumBE807C', 57, 0.00, 1, 1, 0.00, NULL, 37),
(1784, 'orderNumBE807C', 59, 1.00, 1, 1, 1.00, NULL, 37),
(1785, 'orderNumBE807C', 64, 0.00, 1, 1, 0.00, NULL, 37),
(1786, 'orderNumBE807C', 3, 0.50, 1, 1, 0.50, NULL, 37),
(1787, 'orderNumBE807C', 57, 0.00, 1, 1, 0.00, NULL, 37),
(1788, 'orderNumBE807C', 59, 1.00, 1, 1, 1.00, NULL, 37),
(1789, 'orderNumBE807C', 64, 0.00, 1, 1, 0.00, NULL, 37),
(1850, 'orderNum763CF9', 3, 0.50, 1, 3, 1.50, NULL, 53),
(1851, 'orderNum763CF9', 57, 0.00, 1, 3, 0.00, NULL, 53),
(1852, 'orderNum763CF9', 59, 1.00, 1, 3, 3.00, NULL, 53),
(1853, 'orderNum763CF9', 64, 0.00, 1, 3, 0.00, NULL, 53),
(1858, 'orderNum67BAE7', 3, 0.50, 1, 1, 0.50, NULL, 55),
(1859, 'orderNum67BAE7', 57, 0.00, 1, 1, 0.00, NULL, 55),
(1860, 'orderNum67BAE7', 59, 1.00, 1, 1, 1.00, NULL, 55),
(1861, 'orderNum67BAE7', 64, 0.00, 1, 1, 0.00, NULL, 55),
(1862, 'orderNum606B7B', 3, 0.50, 1, 1, 0.50, NULL, 56),
(1863, 'orderNum606B7B', 57, 0.00, 1, 1, 0.00, NULL, 56),
(1864, 'orderNum606B7B', 60, 0.10, 1, 1, 0.10, NULL, 56),
(1865, 'orderNum606B7B', 64, 0.00, 1, 1, 0.00, NULL, 56),
(1866, 'orderNum606B7B', 3, 0.50, 1, 1, 0.50, NULL, 56),
(1867, 'orderNum606B7B', 57, 0.00, 1, 1, 0.00, NULL, 56),
(1868, 'orderNum606B7B', 60, 0.10, 1, 1, 0.10, NULL, 56),
(1869, 'orderNum606B7B', 64, 0.00, 1, 1, 0.00, NULL, 56),
(1870, 'orderNum606B7B', 3, 0.50, 1, 1, 0.50, NULL, 56),
(1871, 'orderNum606B7B', 57, 0.00, 1, 1, 0.00, NULL, 56),
(1872, 'orderNum606B7B', 59, 1.00, 1, 1, 1.00, NULL, 56),
(1873, 'orderNum606B7B', 64, 0.00, 1, 1, 0.00, NULL, 56),
(1874, 'orderNum606B7B', 3, 0.50, 1, 0, 0.00, NULL, 57),
(1875, 'orderNum606B7B', 57, 0.00, 1, 0, 0.00, NULL, 57),
(1876, 'orderNum606B7B', 59, 1.00, 1, 0, 0.00, NULL, 57),
(1877, 'orderNum606B7B', 64, 0.00, 1, 0, 0.00, NULL, 57),
(1878, 'orderNum2564F3', 3, 0.50, 1, 1, 0.50, NULL, 58),
(1879, 'orderNum2564F3', 57, 0.00, 1, 1, 0.00, NULL, 58),
(1880, 'orderNum2564F3', 59, 1.00, 1, 1, 1.00, NULL, 58),
(1881, 'orderNum2564F3', 64, 0.00, 1, 1, 0.00, NULL, 58),
(1890, 'orderNum9F4E83', 41, 0.70, 3, 1, 2.10, NULL, 61),
(1891, 'orderNum9F4E83', 58, 0.00, 3, 1, 0.00, NULL, 61),
(1892, 'orderNum9F4E83', 59, 1.00, 3, 1, 3.00, NULL, 61),
(1893, 'orderNum9F4E83', 64, 0.00, 3, 1, 0.00, NULL, 61),
(1902, 'orderNum074760', 3, 0.50, 1, 1, 0.50, NULL, 64),
(1903, 'orderNum074760', 57, 0.00, 1, 1, 0.00, NULL, 64),
(1904, 'orderNum074760', 59, 1.00, 1, 1, 1.00, NULL, 64),
(1905, 'orderNum074760', 64, 0.00, 1, 1, 0.00, NULL, 64),
(1906, 'orderNumDADADB', 3, 0.50, 1, 1, 0.50, NULL, 65),
(1907, 'orderNumDADADB', 57, 0.00, 1, 1, 0.00, NULL, 65),
(1908, 'orderNumDADADB', 59, 1.00, 1, 1, 1.00, NULL, 65),
(1909, 'orderNumDADADB', 64, 0.00, 1, 1, 0.00, NULL, 65),
(1910, 'orderNumDADADB', 3, 0.50, 1, 1, 0.50, NULL, 65),
(1911, 'orderNumDADADB', 57, 0.00, 1, 1, 0.00, NULL, 65),
(1912, 'orderNumDADADB', 59, 1.00, 1, 1, 1.00, NULL, 65),
(1913, 'orderNumDADADB', 64, 0.00, 1, 1, 0.00, NULL, 65),
(1922, 'orderNum7883BC', 3, 0.50, 1, 1, 0.50, NULL, 70),
(1923, 'orderNum7883BC', 57, 0.00, 1, 1, 0.00, NULL, 70),
(1924, 'orderNum7883BC', 59, 1.00, 1, 1, 1.00, NULL, 70),
(1925, 'orderNum7883BC', 64, 0.00, 1, 1, 0.00, NULL, 70),
(1926, 'orderNum7883BC', 3, 0.50, 1, 1, 0.50, NULL, 70),
(1927, 'orderNum7883BC', 57, 0.00, 1, 1, 0.00, NULL, 70),
(1928, 'orderNum7883BC', 59, 1.00, 1, 1, 1.00, NULL, 70),
(1929, 'orderNum7883BC', 64, 0.00, 1, 1, 0.00, NULL, 70),
(1938, 'orderNumE2D87E', 3, 0.50, 1, 1, 0.50, NULL, 72),
(1939, 'orderNumE2D87E', 57, 0.00, 1, 1, 0.00, NULL, 72),
(1940, 'orderNumE2D87E', 59, 1.00, 1, 1, 1.00, NULL, 72),
(1941, 'orderNumE2D87E', 64, 0.00, 1, 1, 0.00, NULL, 72),
(1942, 'orderNumE2D87E', 3, 0.50, 1, 1, 0.50, NULL, 72),
(1943, 'orderNumE2D87E', 57, 0.00, 1, 1, 0.00, NULL, 72),
(1944, 'orderNumE2D87E', 60, 0.10, 1, 1, 0.10, NULL, 72),
(1945, 'orderNumE2D87E', 64, 0.00, 1, 1, 0.00, NULL, 72);

-- --------------------------------------------------------

--
-- Table structure for table `order_documents`
--

CREATE TABLE `order_documents` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `document_upload` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_documents`
--

INSERT INTO `order_documents` (`id`, `order_id`, `document_upload`, `uploaded_at`, `user_id`) VALUES
(22, 'orderNumE1EF4D', 'Admin_Dashboard/service/document_upload/orderNumE1EF4D_DAYANG_NUR_NAZIHAH_CV.pdf', '2024-11-10 15:05:22', 9),
(23, 'orderNumE1EF4D', 'Admin_Dashboard/service/document_upload/orderNumE1EF4D_4-up on 21-10-2024 at 11.00 AM (compiled).jpg', '2024-11-10 15:05:47', 9),
(24, 'orderNumFD9CF4', 'Admin_Dashboard/service/document_upload/orderNumFD9CF4_Borang LI-J_Pengesahan Penerimaan (1).pdf', '2024-11-10 15:38:32', 9),
(25, 'orderNumFD9CF4', 'Admin_Dashboard/service/document_upload/orderNumFD9CF4_SIJIL PELAJARAN MALAYSIA TAHUN 2021.pdf', '2024-11-10 15:38:49', 9),
(26, 'orderNum233B88', 'Admin_Dashboard/service/document_upload/orderNum233B88_Premium Vector _ Funny printer and scanner in kawaii doodle style.jpeg', '2024-11-10 15:40:40', 9),
(29, 'orderNum58822C', 'Admin_Dashboard/service/document_upload/orderNum58822C_Turnitin - Originality Report - wp-1614835935053.pdf', '2024-11-10 16:41:45', 9),
(30, 'orderNum9B17E9', 'Admin_Dashboard/service/document_upload/orderNum9B17E9_Premium Vector _ Funny printer and scanner in kawaii doodle style.jpeg', '2024-11-10 16:54:08', 9),
(31, 'orderNumCABE11', 'Admin_Dashboard/service/document_upload/orderNumCABE11_slide si.pdf', '2024-11-10 17:01:28', 9),
(37, 'orderNumBE807C', 'Admin_Dashboard/service/document_upload/orderNumBE807C__ (2).jpeg', '2024-11-10 17:38:58', 9),
(53, 'orderNum763CF9', 'Admin_Dashboard/service/document_upload/orderNum763CF9_LAB SKILL 1_DDWC2663.pdf', '2024-11-11 03:45:24', 9),
(55, 'orderNum67BAE7', 'Admin_Dashboard/service/document_upload/orderNum67BAE7_project member.jpg', '2024-11-11 03:47:38', 9),
(56, 'orderNum606B7B', 'Admin_Dashboard/service/document_upload/orderNum606B7B_ASSIGNMENT1.docx', '2024-11-11 03:47:50', 9),
(57, 'orderNum606B7B', 'Admin_Dashboard/service/document_upload/orderNum606B7B_EXTENSION_DDWD 3343  COMPUTER SECURITY_ANSWER.docx', '2024-11-11 03:48:31', 9),
(58, 'orderNum2564F3', 'Admin_Dashboard/service/document_upload/orderNum2564F3_test order.png', '2024-11-11 03:50:14', 41),
(61, 'orderNum9F4E83', 'Admin_Dashboard/service/document_upload/orderNum9F4E83_test order.png', '2024-11-11 04:09:14', 41),
(64, 'orderNum074760', 'Admin_Dashboard/service/document_upload/orderNum074760_Poster V1.1.pdf', '2024-11-11 06:46:39', 9),
(65, 'orderNumDADADB', 'Admin_Dashboard/service/document_upload/orderNumDADADB_Poster V1.1.pdf', '2024-11-11 08:01:27', 9),
(67, 'orderNumDF9E30', 'Admin_Dashboard/service/document_upload/orderNumDF9E30_Poster V1.1.pdf', '2024-11-11 08:08:50', 9),
(68, 'orderNumDF9E30', 'Admin_Dashboard/service/document_upload/orderNumDF9E30_STD VERSION 1.0 - G03_01(1.0).pdf', '2024-11-11 08:38:53', 9),
(69, 'orderNum496F69', 'Admin_Dashboard/service/document_upload/orderNum496F69_CS REPORT dania risa hannah nge.pdf', '2024-11-12 15:49:00', 9),
(70, 'orderNum7883BC', 'Admin_Dashboard/service/document_upload/orderNum7883BC_firework.png', '2024-11-12 16:21:58', 9),
(72, 'orderNumE2D87E', 'Admin_Dashboard/service/document_upload/orderNumE2D87E_4-up on 21-10-2024 at 10.58 AM #3 (compiled).jpg', '2024-11-12 16:35:20', 9);

-- --------------------------------------------------------

--
-- Table structure for table `printing_services`
--

CREATE TABLE `printing_services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `service_description` text NOT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `printing_services`
--

INSERT INTO `printing_services` (`service_id`, `service_name`, `service_description`, `status`, `image`) VALUES
(2, 'Binding', 'Get your documents professionally bound for a polished and durable finish. Choose from comb, wire, or perfect binding to keep your work organized and presentation-ready.', 'available', '../../assets/images/uploads/bind.png'),
(3, 'Printing', 'High-quality printing for documents, photos, and presentations. Choose from a range of paper types and sizes for a crisp, professional look every time.', 'available', '../../assets/images/uploads/printing.png'),
(4, 'Lamination', 'Protect and enhance your documents with our lamination service, offering a smooth, durable finish that resists wear and tear.', 'available', '../../assets/images/uploads/lamin.png');

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
(1, 'Document Upload', 'Got a last-minute print job you need by morning? No problem! Upload your documents anytime, day or night, and we\'ll ensure they\'re printed and ready for you when you need them. Fast, reliable, and hassle-free printing at your convenience.', 'available', '../../assets/images/uploads/upload.jpg'),
(2, 'Schedule Appointment', 'Need your document at your convenient time? Ask and you shall receive! We allow you to schedule an appointment for pickup within our working hours, just set it and we\'ll prepare your documents.', 'available', '../../assets/images/uploads/appointment.jpg'),
(5, 'Track Your Order', 'Get the latest updates on your order status! Whether you\'re waiting for your printing job to be completed or ready for pickup or delivery, simply enter your order number to receive real-time information. Stay informed every step of the way and never miss an update', 'available', '../../assets/images/uploads/track.jpg'),
(8, 'Delivery', 'Enjoy fast and reliable delivery within a 1km range from our location. Whether it\'s a large print order or a single document, we\'ll ensure your items reach you siwftly and securely. Just share your delivery details during checkout, and leave the rest to us.', 'available', '../../assets/images/uploads/delivery.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `specification`
--

CREATE TABLE `specification` (
  `id` int(11) NOT NULL,
  `spec_type` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `status` enum('available','unavailable') DEFAULT NULL,
  `spec_name_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specification`
--

INSERT INTO `specification` (`id`, `spec_type`, `price`, `status`, `spec_name_id`) VALUES
(3, 'A4', 0.5, 'available', 1),
(41, 'A3', 0.7, 'available', 1),
(57, 'Portrait', 0, 'available', 2),
(58, 'Landscape', 0, 'available', 2),
(59, 'Colour', 1, 'available', 3),
(60, 'Black and White', 0.1, 'available', 3),
(64, '1', 0, 'available', 4),
(65, '4', 0, 'available', 4),
(70, '16', 0, 'available', 4);

-- --------------------------------------------------------

--
-- Table structure for table `spec_names`
--

CREATE TABLE `spec_names` (
  `id` int(11) NOT NULL,
  `spec_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spec_names`
--

INSERT INTO `spec_names` (`id`, `spec_name`) VALUES
(1, 'Paper Size'),
(2, 'Orientation'),
(3, 'Colour'),
(4, 'Pages per sheet');

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
(50, 'staffn', 'baru', 'imaisarahm@gmail.com', '0164412020', '105 jalan sutera, 2/5 taman sutera', '$2y$10$.Zk9KWEg1/vYt3HEp4tPqe4L1m4LzYUN07dlUipIWPcjl440kumvi', NULL, NULL, NULL, 0, 'STAFF', '../assets/profile_pic/default-placeholder.png'),
(55, 'nurnazihah', NULL, 'dayangnurnazihah@graduate.utm.my', NULL, NULL, '$2y$10$QhJ86YsWPYAe4qZmviNvROMavBnP5c8vLL4bkK/Ef0jnGG1CMorFa', NULL, NULL, '22317342ee5ac19452530aaba3879e27', 0, 'user', '../assets/profile_pic/default-placeholder.png'),
(57, 'dayangzhr', NULL, 'dayangzhr@gmail.com', NULL, NULL, '$2y$10$7CnC0/D56kzEKNTQZa.OheBqtEi3BT.ujzRoqG./iPRUoZSd9yBDm', NULL, NULL, '5e6cb64b97e201f4cf459985655bda5f', 0, 'user', '../assets/profile_pic/default-placeholder.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `delivery_locations`
--
ALTER TABLE `delivery_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_ibfk_1` (`user_id`),
  ADD KEY `delivery_location_id` (`delivery_location_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `specification_id` (`specification_id`),
  ADD KEY `doc_id` (`doc_id`),
  ADD KEY `document_id` (`document_id`);

--
-- Indexes for table `order_documents`
--
ALTER TABLE `order_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `printing_services`
--
ALTER TABLE `printing_services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `specification`
--
ALTER TABLE `specification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_spec_name` (`spec_name_id`);

--
-- Indexes for table `spec_names`
--
ALTER TABLE `spec_names`
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
-- AUTO_INCREMENT for table `delivery_locations`
--
ALTER TABLE `delivery_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1946;

--
-- AUTO_INCREMENT for table `order_documents`
--
ALTER TABLE `order_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `printing_services`
--
ALTER TABLE `printing_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `specification`
--
ALTER TABLE `specification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `spec_names`
--
ALTER TABLE `spec_names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`delivery_location_id`) REFERENCES `delivery_locations` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`specification_id`) REFERENCES `specification` (`id`),
  ADD CONSTRAINT `order_details_ibfk_3` FOREIGN KEY (`doc_id`) REFERENCES `order_documents` (`id`),
  ADD CONSTRAINT `order_details_ibfk_4` FOREIGN KEY (`document_id`) REFERENCES `order_documents` (`id`);

--
-- Constraints for table `order_documents`
--
ALTER TABLE `order_documents`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_documents_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
