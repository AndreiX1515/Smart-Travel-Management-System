-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2025 at 04:09 AM
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
-- Database: `testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `bookingId` int(11) NOT NULL,
  `accountId` int(11) DEFAULT NULL,
  `transactNo` varchar(50) NOT NULL,
  `accountType` varchar(50) DEFAULT NULL,
  `agentCode` varchar(50) DEFAULT NULL,
  `flightId` int(11) DEFAULT NULL,
  `packageId` int(11) DEFAULT NULL,
  `fName` varchar(50) DEFAULT NULL,
  `lName` varchar(50) DEFAULT NULL,
  `mName` varchar(50) DEFAULT NULL,
  `suffix` enum('N/A','Jr.','Sr.','II','III','IV','V') DEFAULT NULL,
  `countryCode` varchar(10) DEFAULT NULL,
  `contactNo` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pax` int(11) DEFAULT NULL,
  `infantPax` int(11) NOT NULL DEFAULT 0,
  `bookingDate` datetime DEFAULT current_timestamp(),
  `totalPrice` decimal(10,2) DEFAULT NULL,
  `bookingType` enum('Package','Land') DEFAULT NULL,
  `flightDetails` text DEFAULT NULL,
  `status` enum('Pending','Cancelled','Confirmed','Complete','Reserved') NOT NULL,
  `remarks` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`bookingId`, `accountId`, `transactNo`, `accountType`, `agentCode`, `flightId`, `packageId`, `fName`, `lName`, `mName`, `suffix`, `countryCode`, `contactNo`, `email`, `pax`, `infantPax`, `bookingDate`, `totalPrice`, `bookingType`, `flightDetails`, `status`, `remarks`) VALUES
(1, 32, 'BU1-000001', 'Client', 'BU1', 91, 5, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 4, 0, '2025-03-03 09:59:42', 155357.88, 'Package', NULL, 'Confirmed', NULL),
(2, 23, 'BU1-000002', 'Agent', 'BU1', 92, 5, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 10, 0, '2025-03-03 10:24:52', 388401.20, 'Package', NULL, 'Confirmed', NULL),
(3, 87, 'BU1-000003', 'Agent', 'BU1', 92, 5, 'testing1', 'testing1', 'testing1', 'Sr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-03-03 10:59:33', 77680.24, 'Package', NULL, 'Confirmed', NULL),
(4, 24, 'BU1-000004', 'Agent', 'BU1', 91, 5, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-03-03 11:32:06', 77678.94, 'Package', NULL, 'Confirmed', NULL),
(5, 35, 'BU4-000005', 'Agent', 'BU4', 91, 5, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-03-04 10:01:34', 77678.94, 'Package', NULL, 'Confirmed', NULL),
(6, 181, 'BU4-000006', 'Client', 'BU4', 91, 5, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 5, 0, '2025-03-04 10:03:48', 161697.35, 'Package', NULL, 'Confirmed', NULL),
(7, 182, 'BU5-000007', 'Client', 'BU5', 91, 5, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-03-04 10:04:32', 77678.94, 'Package', NULL, 'Confirmed', NULL),
(8, 182, 'BU5-000008', 'Client', 'BU5', 91, 5, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-03-04 10:05:37', 77678.94, 'Package', NULL, 'Confirmed', NULL),
(9, 23, 'BU1-000009', 'Agent', 'BU1', 94, 5, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-03-04 11:47:20', 118620.36, 'Package', NULL, 'Confirmed', NULL),
(10, 23, 'BU1-000010', 'Agent', 'BU1', 64, 7, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-03-04 14:16:54', 101711.73, 'Package', NULL, 'Cancelled', NULL),
(11, 24, 'BU1-000011', 'Agent', 'BU1', 66, 7, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-03-04 14:29:15', 109305.33, 'Package', NULL, 'Confirmed', NULL),
(12, 32, 'BU1-000012', 'Client', 'BU1', 66, 7, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-03-04 14:35:59', 109305.33, 'Package', NULL, 'Confirmed', NULL),
(13, 32, 'BU1-000013', 'Client', 'BU1', 66, 7, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-03-04 14:36:51', 109305.33, 'Package', NULL, 'Confirmed', NULL),
(14, 35, 'BU4-000014', 'Agent', 'BU4', 72, 7, 'testing1', 'testing1', 'testing1', 'Sr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-03-05 09:55:05', 71448.22, 'Package', NULL, 'Confirmed', NULL),
(15, 41, 'BU6-000015', 'Agent', 'BU6', 67, 7, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 1, 0, '2025-03-05 09:56:25', 34303.91, 'Package', NULL, 'Confirmed', NULL),
(16, 35, 'BU4-000016', 'Agent', 'BU4', 90, 7, 'testing1', 'testing1', 'testing1', 'Sr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-03-05 09:58:54', 73027.36, 'Package', NULL, 'Confirmed', NULL),
(17, 23, 'BU1-000017', 'Agent', 'BU1', 94, 5, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-03-07 09:27:30', 112620.36, 'Package', NULL, 'Confirmed', NULL),
(18, 23, 'BU1-000018', 'Agent', 'BU1', 94, 5, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-03-07 09:38:02', 112620.36, 'Package', NULL, 'Confirmed', NULL),
(19, 23, 'BU1-000019', 'Agent', 'BU1', 93, 7, 'testing1', 'testing1', 'testing1', 'Sr.', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-03-07 10:49:12', 109541.04, 'Package', NULL, 'Confirmed', NULL),
(20, 23, 'BU1-000020', 'Agent', 'BU1', 79, 7, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-03-19 14:45:00', 75870.22, 'Package', NULL, 'Reserved', NULL),
(21, 23, 'BU1-000021', 'Agent', 'BU1', 114, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-16 10:40:48', 42970.63, 'Package', NULL, 'Pending', NULL),
(22, 23, 'BU1-000022', 'Agent', 'BU1', 114, 5, 'test', 'test', 'tet', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-16 11:00:10', 42970.63, 'Package', NULL, 'Pending', NULL),
(23, 23, 'BU1-000023', 'Agent', 'BU1', 95, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-16 11:00:42', 42970.63, 'Package', NULL, 'Pending', NULL),
(24, 23, 'BU1-000024', 'Agent', 'BU1', 97, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-16 11:01:11', 41655.11, 'Package', NULL, 'Pending', NULL),
(25, 23, 'BU1-000025', 'Agent', 'BU1', 100, 5, 'test', 'test', 'test', 'N/A', '+63', '99999', 'test@gmail.com', 1, 0, '2025-04-16 11:02:39', 37123.07, 'Package', NULL, 'Pending', NULL),
(26, 23, 'BU1-000026', 'Agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '99999999', 'test@gmail.com', 1, 0, '2025-04-16 11:03:08', 37123.07, 'Package', NULL, 'Pending', NULL),
(27, 23, 'BU1-000027', 'Agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-16 11:03:46', 37123.07, 'Package', NULL, 'Pending', NULL),
(28, 23, 'BU1-000028', 'Agent', 'BU1', 114, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-16 11:05:47', 42970.63, 'Package', NULL, 'Pending', NULL),
(29, 23, 'BU1-000029', 'Agent', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-21 09:15:11', 37334.07, 'Package', NULL, 'Reserved', NULL),
(30, 23, 'BU1-000030', 'agent', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-21 09:26:12', 0.00, 'Package', NULL, 'Reserved', NULL),
(31, 23, 'BU1-000031', 'agent', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-21 09:26:46', 0.00, 'Package', NULL, 'Reserved', NULL),
(32, 23, 'BU1-000032', 'agent', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-21 10:59:54', 37334.07, 'Package', NULL, 'Reserved', NULL),
(33, 24, 'BU1-000033', 'agent', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-21 13:31:33', 37334.07, 'Package', NULL, 'Reserved', NULL),
(34, 23, 'BU1-000034', 'Agent', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '99999', 'test@gmail.com', 1, 0, '2025-04-21 13:57:04', 37334.07, 'Package', NULL, 'Reserved', NULL),
(35, 23, 'BU1-000035', 'Agent', 'BU1', 127, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-21 14:35:09', 34919.73, 'Package', NULL, 'Pending', NULL),
(36, 23, 'BU1-000036', 'agent', 'BU1', 118, 5, 'test', 'testte', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-21 14:40:13', 37334.07, 'Package', NULL, 'Reserved', NULL),
(37, 23, 'BU1-000037', 'Agent', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-21 15:23:43', 37334.07, 'Package', NULL, 'Reserved', NULL),
(38, 23, 'BU1-000038', 'Agent', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-21 15:28:43', 37334.07, 'Package', NULL, 'Reserved', NULL),
(39, 23, 'BU1-000039', 'Agent', 'BU1', 118, 5, 'test', 'testt', 'est', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-21 15:30:27', 37334.07, 'Package', NULL, 'Reserved', NULL),
(40, 23, 'BU1-000040', 'Agent', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-21 15:35:22', 37334.07, 'Package', NULL, 'Reserved', NULL),
(41, 23, 'BU1-000041', 'Agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999999', 'test@gmail.com', 1, 0, '2025-04-21 15:38:55', 37123.07, 'Package', NULL, 'Reserved', NULL),
(42, 23, 'BU1-000042', 'Agent', 'BU1', 120, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-21 15:39:19', 37330.01, 'Package', NULL, 'Reserved', NULL),
(43, 23, 'BU1-000043', 'Agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-21 15:39:52', 37123.07, 'Package', NULL, 'Pending', NULL),
(44, 23, 'BU1-000044', 'Agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-21 15:54:35', 37123.07, 'Package', NULL, 'Pending', NULL),
(45, 23, 'BU1-000045', 'Agent', 'BU1', 120, 5, 'testt', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-22 09:14:12', 37330.01, 'Package', NULL, 'Pending', NULL),
(46, 23, 'BU1-000046', 'Agent', 'BU1', 121, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-22 09:25:26', 37233.51, 'Package', NULL, 'Reserved', NULL),
(47, 32, 'BU1-000047', 'Client', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-22 09:35:12', 37334.07, 'Package', NULL, 'Reserved', NULL),
(48, 32, 'BU1-000048', 'Client', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-22 09:55:59', 37334.07, 'Package', NULL, 'Reserved', NULL),
(49, 32, 'BU1-000049', 'Client', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-22 10:00:07', 37334.07, 'Package', NULL, 'Reserved', NULL),
(50, 32, 'BU1-000050', 'Client', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '99999', 'test@gmail.com', 1, 0, '2025-04-22 10:01:00', 37334.07, 'Package', NULL, 'Reserved', NULL),
(51, 32, 'BU1-000051', 'Client', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '99999', 'test@gmail.com', 1, 0, '2025-04-22 10:13:01', 0.00, 'Package', NULL, 'Reserved', NULL),
(52, 32, 'BU1-000052', 'Client', 'BU1', 118, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-22 10:23:57', 37334.07, 'Package', NULL, 'Reserved', NULL),
(53, 32, 'BU1-000053', 'Client', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-22 13:11:52', 37123.07, 'Package', NULL, 'Reserved', NULL),
(55, 32, 'BU1-000054', 'Client', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-22 13:28:56', 37123.07, 'Package', NULL, 'Pending', NULL),
(57, 23, 'BU1-000056', 'agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-24 11:15:25', 37123.07, 'Package', NULL, 'Reserved', NULL),
(58, 32, 'BU1-000058', 'guest', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-24 11:28:19', 0.00, 'Package', NULL, 'Reserved', NULL),
(59, 32, 'BU1-000059', 'guest', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-24 11:32:59', 0.00, 'Package', NULL, 'Reserved', NULL),
(60, 23, 'BU1-000060', 'agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-24 11:37:37', 37123.07, 'Package', NULL, 'Pending', NULL),
(61, 32, 'BU1-000061', 'guest', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-24 11:38:31', 37123.07, 'Package', NULL, 'Pending', NULL),
(62, 23, 'BU1-000062', 'agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-24 13:13:48', 37123.07, 'Package', NULL, 'Reserved', NULL),
(63, 23, 'BU1-000063', 'agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-24 13:18:40', 37123.07, 'Package', NULL, 'Pending', NULL),
(64, 32, 'BU1-000064', 'guest', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-24 13:19:24', 37123.07, 'Package', NULL, 'Pending', NULL),
(65, 23, 'BU1-000065', 'Agent', 'BU1', 120, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-24 13:21:00', 37330.01, 'Package', NULL, 'Reserved', NULL),
(66, 23, 'BU1-000066', 'Agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-24 13:53:16', 37123.07, 'Package', NULL, 'Reserved', NULL),
(67, 23, 'BU1-000067', 'Agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-24 13:57:38', 37123.07, 'Package', NULL, 'Pending', NULL),
(68, 23, 'BU1-000068', 'Agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-24 14:20:47', 37123.07, 'Package', NULL, 'Reserved', NULL),
(69, 23, 'BU1-000069', 'Agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-24 14:42:48', 37123.07, 'Package', NULL, 'Reserved', NULL),
(70, 32, 'BU1-000070', 'Client', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '999999', 'test@gmail.com', 1, 0, '2025-04-24 14:43:06', 37123.07, 'Package', NULL, 'Reserved', NULL),
(71, 23, 'BU1-000071', 'Agent', 'BU1', 119, 5, 'test', 'test', 'test', 'N/A', '+63', '9999999', 'test@gmail.com', 1, 0, '2025-04-24 07:20:28', 37123.07, 'Package', NULL, 'Pending', NULL),
(72, 23, 'BU1-000072', 'Agent', 'BU1', 127, 5, 'Jov', 'E', 'N/A', 'N/A', '+63', '3456789876', 'jov@gmail.com', 6, 0, '2025-04-25 07:47:16', 209518.38, 'Package', NULL, 'Reserved', NULL),
(73, 41, 'BU6-000073', 'Agent', 'BU6', 128, 5, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'jovelynnofies179@gmail.com', 1, 0, '2025-05-08 07:37:40', 34708.73, 'Package', NULL, 'Pending', NULL),
(74, 23, 'BU1-000074', 'Agent', 'BU1', 133, 5, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'jovelynnofies179@gmail.com', 3, 0, '2025-05-14 01:53:06', 104790.81, 'Package', NULL, 'Pending', NULL),
(75, 23, 'BU1-000075', 'Agent', 'BU1', 184, 6, 'Dave', 'Baluso ', 'N/A', 'N/A', '+63', '9159150363', 'jovelynnofies179@gmail.com', 1, 0, '2025-05-14 01:59:47', 38871.57, 'Package', NULL, 'Pending', NULL),
(76, 23, 'BU1-000076', 'Agent', 'BU1', 145, 5, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'smarttravelmanila04@gmail.com', 1, 0, '2025-05-14 02:26:18', 34926.76, 'Package', NULL, 'Pending', NULL),
(77, 23, 'BU1-000077', 'Agent', 'BU1', 171, 6, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'smarttravelmanila04@gmail.com', 2, 0, '2025-05-14 02:28:12', 68887.26, 'Package', NULL, 'Pending', NULL),
(78, 23, 'BU1-000078', 'agent', 'BU1', 133, 5, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'jovelynnofies179@gmail.com', 1, 0, '2025-05-14 06:43:51', 34930.27, 'Package', NULL, 'Reserved', NULL),
(79, 23, 'BU1-000079', 'agent', 'BU1', 133, 5, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'jovelynnofies179@gmail.com', 1, 0, '2025-05-14 06:43:51', 34930.27, 'Package', NULL, 'Reserved', NULL),
(80, 23, 'BU1-000080', 'agent', 'BU1', 133, 5, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'jovelynnofies179@gmail.com', 1, 0, '2025-05-14 06:44:00', 34930.27, 'Package', NULL, 'Pending', NULL),
(81, 23, 'BU1-000081', 'Agent', 'BU1', 136, 5, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'smarttravelmanila04@gmail.com', 10, 0, '2025-05-16 01:32:28', 349274.20, 'Package', NULL, 'Pending', NULL),
(82, 23, 'BU1-000082', 'Agent', 'BU1', 136, 5, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'jovelynnofies179@gmail.com', 1, 0, '2025-05-16 08:49:32', 34927.42, 'Package', NULL, 'Pending', NULL),
(83, 23, 'BU1-000083', 'Agent', 'BU1', 232, 1, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'smarttravelmanila04@gmail.com', 1, 0, '2025-05-23 03:38:16', 37177.03, 'Package', NULL, 'Pending', NULL),
(84, 23, 'BU1-000084', 'Agent', 'BU1', 287, 7, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'smarttravelmanila04@gmail.com', 10, 0, '2025-05-26 07:32:31', 464811.20, 'Package', NULL, 'Pending', NULL),
(85, 23, 'BU1-000085', 'Agent', 'BU1', 144, 5, 'asdasd', 'asdasd', 'asdasdasd', 'N/A', '+63', '999999', 'adasdasdasd@gmail.com', 1, 0, '2025-05-26 07:34:19', 34912.40, 'Package', NULL, 'Reserved', NULL),
(86, 23, 'BU1-000086', 'Agent', 'BU1', 279, 7, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'smarttravelmanila04@gmail.com', 1, 0, '2025-05-26 07:45:45', 38769.51, 'Package', NULL, 'Pending', NULL),
(87, 23, 'BU1-000087', 'Agent', 'BU1', 258, 1, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'smarttravelmanila04@gmail.com', 2, 0, '2025-05-27 02:08:06', 68189.02, 'Package', NULL, 'Pending', NULL),
(88, 23, 'BU1-000088', 'Agent', 'BU1', 204, 6, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'smarttravelmanila04@gmail.com', 10, 0, '2025-05-27 02:50:57', 351889.40, 'Package', NULL, 'Pending', NULL),
(89, 23, 'BU1-000089', 'Agent', 'BU1', 288, 7, 'Jovelyn', 'Nofies', 'EBAYAN', 'N/A', '+63', '9159150363', 'smarttravelmanila04@gmail.com', 2, 0, '2025-05-29 07:33:34', 92952.54, 'Package', NULL, 'Pending', NULL),
(90, 23, 'BU1-000090', 'Agent', 'BU1', 187, 6, 'Jov', 'A', 'N/A', 'N/A', '+63', '1234567890', 'jov@gmail.com', 3, 0, '2025-05-29 08:32:44', 57000.00, 'Land', 'iesmd,cld s ', 'Reserved', NULL),
(91, 23, 'BU1-000091', 'Agent', 'BU1', 1129, 4, 'Alex', 'E', 'N/A', 'N/A', '+63', '3456789876', 'olic.dumlao@gmail.com', 5, 0, '2025-05-29 09:02:08', 205001.65, 'Package', NULL, 'Pending', NULL),
(92, 23, 'BU1-000092', 'Agent', 'BU1', 204, 6, 'Jov', 'Ne', 'N/A', 'N/A', '+63', '23465433221', 'jov@gmail.com', 4, 0, '2025-05-30 06:23:05', 140755.76, 'Package', NULL, 'Pending', NULL),
(93, 25, 'BU1-000093', 'Agent', 'BU1', 182, 6, 'testing1', 'testing1', 'testing1', 'N/A', '+63', '9999999991', 'testing1@gmail.com', 1, 0, '2025-05-30 09:09:50', 38560.57, 'Package', NULL, 'Pending', NULL),
(94, 25, 'BU1-000094', 'Agent', 'BU1', 183, 6, 'testing2', 'testing2', 'testing2', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-05-30 09:12:34', 75681.14, 'Package', NULL, 'Pending', NULL),
(95, 25, 'BU1-000095', 'Agent', 'BU1', 183, 6, 'testing3', 'testing3', 'testing3', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-05-30 09:15:05', 113521.71, 'Package', NULL, 'Pending', NULL),
(96, 81, 'BU1-000096', 'Agent', 'BU1', 196, 6, 'testing1', 'testing1', 'testing1', 'Sr.', '+63', '9999999991', 'testing1@gmail.com', 1, 0, '2025-06-02 01:28:40', 34912.53, 'Package', NULL, 'Confirmed', NULL),
(97, 146, 'BU1-000097', 'Client', 'BU1', 191, 6, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 1, 0, '2025-06-04 02:00:34', 34912.51, 'Package', NULL, 'Pending', NULL),
(98, 32, 'BU1-000098', 'Client', 'BU1', 286, 7, 'JOVELYN ', 'NOFIES', 'EBAYAN', 'N/A', '+63', '9159150363', 'jovelynnofies179@gmail.com', 1, 0, '2025-06-04 02:03:31', 48181.12, 'Package', NULL, 'Pending', NULL),
(99, 146, 'BU1-000099', 'Client', 'BU1', 181, 6, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-06-04 02:38:59', 70859.14, 'Package', NULL, 'Pending', NULL),
(100, 26, 'BU1-000100', 'Agent', 'BU1', 210, 1, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-06-09 14:45:10', 87566.82, 'Package', NULL, 'Pending', NULL),
(101, 26, 'BU1-000101', 'Agent', 'BU1', 256, 1, 'testing1', 'testing1', 'testing1', 'N/A', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-06-09 15:19:06', 59589.02, 'Package', NULL, 'Reserved', NULL),
(102, 23, 'BU1-000102', 'Agent', 'BU1', 199, 6, 'testing1', 'testing1', 'testing1', 'N/A', '+63', '9999999991', 'testing2@gmail.com', 2, 0, '2025-06-10 09:59:06', 77703.14, 'Package', NULL, 'Confirmed', NULL),
(103, 27, 'BU2-000103', 'Agent', 'BU2', 193, 6, 'testing1', 'testing1', 'testing1', 'N/A', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-06-17 10:12:31', 104737.56, 'Package', NULL, 'Confirmed', NULL),
(104, 27, 'BU2-000104', 'Agent', 'BU2', 199, 6, 'testing2', 'testing2', 'testing2', 'Sr.', '+63', '9999999991', 'testing1@gmail.com', 10, 0, '2025-06-17 10:16:31', 358515.70, 'Package', NULL, 'Confirmed', NULL),
(105, 23, 'BU1-000105', 'Agent', 'BU1', 201, 6, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999993', 'testing1@gmail.com', 3, 0, '2025-06-20 13:00:50', 107554.71, 'Package', NULL, 'Pending', NULL),
(106, 23, 'BU1-000106', 'Agent', 'BU1', 235, 1, 'testing1', 'testing1', 'testing1', 'N/A', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-07-29 09:38:26', 38000.00, 'Land', 'testing', 'Confirmed', NULL),
(107, 23, 'BU1-000107', 'Agent', 'BU1', 194, 6, 'testing3', 'testing3', 'testing3', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-07-29 11:50:23', 40000.00, 'Land', 'testing 2 flight details', 'Confirmed', NULL),
(108, 23, 'BU1-000108', 'Agent', 'BU1', 193, 6, 'testing4', 'testing4', 'testing4', 'Jr.', '+63', '9999999991', 'testing4@gmail.com', 3, 0, '2025-07-29 14:03:10', 104737.56, 'Package', NULL, 'Confirmed', NULL),
(109, 32, 'BU1-000109', 'Client', 'BU1', 194, 6, 'testing5', 'testing5', 'testing5', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 5, 0, '2025-07-29 14:50:20', 174562.60, 'Package', NULL, 'Confirmed', NULL),
(110, 32, 'BU1-000110', 'Client', 'BU1', 194, 6, 'testing5', 'testing5', 'testing5', 'N/A', '+63', '9999999991', 'testing1@gmail.com', 4, 0, '2025-07-29 15:39:40', 80000.00, 'Land', 'testing client booking land only', 'Confirmed', NULL),
(111, 23, 'BU1-000111', 'Agent', 'BU1', 193, 6, 'testing1', 'testing1', 'testing1', 'II', '+63', '9999999991', 'testing1@gmail.com', 3, 0, '2025-07-30 15:47:22', 107554.71, 'Package', NULL, 'Reserved', NULL),
(112, 23, 'BU1-000112', 'Agent', 'BU1', 193, 6, 'testing1', 'testing1', 'testing1', 'N/A', '+63', '9999999991', 'testing1@gmail.com', 2, 0, '2025-07-30 16:13:01', 69825.04, 'Package', NULL, 'Pending', NULL),
(113, 23, 'BU1-000113', 'Agent', 'BU1', 193, 6, 'testing1', 'testing1', 'testing1', 'Jr.', '+63', '9999999991', 'testing1@gmail.com', 2, 5, '2025-08-07 14:56:15', 69825.04, 'Package', NULL, 'Reserved', NULL);

--
-- Triggers `booking`
--
DELIMITER $$
CREATE TRIGGER `after_booking_insert` AFTER INSERT ON `booking` FOR EACH ROW BEGIN
    -- Ensure @current_user_id is set if it's NULL
    IF @current_user_id IS NULL THEN
        SET @current_user_id = 'SYSTEM'; 
    END IF;

    INSERT INTO auditbooking (
        bookingId, 
        transactNo, 
        actionType, 
        actionDate, 
        performedBy, 
        oldValues, 
        newValues
    )
    VALUES (
        NEW.bookingId,
        NEW.transactNo,
        'INSERT',
        CURRENT_TIMESTAMP,
        @current_user_id,
        NULL,
        CONCAT('Inserted Transact No: ', NEW.transactNo, ', status: ', NEW.status)
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_booking_update` AFTER UPDATE ON `booking` FOR EACH ROW BEGIN
    -- Ensure @current_user_id is set if it's NULL
    IF @current_user_id IS NULL THEN
        SET @current_user_id = 'SYSTEM'; 
    END IF;

    INSERT INTO auditbooking (
        bookingId, 
        transactNo, 
        actionType, 
        actionDate, 
        performedBy, 
        oldValues, 
        newValues
    ) 
    VALUES (
        OLD.bookingId, 
        OLD.transactNo, 
        'UPDATE', 
        CURRENT_TIMESTAMP, 
        @current_user_id,
        CONCAT('BookingId: ', OLD.bookingId, ', TransactNo: ', OLD.transactNo, ', Status: ', OLD.status), 
        CONCAT('BookingId: ', NEW.bookingId, ', TransactNo: ', NEW.transactNo, ', Status: ', NEW.status)
    );
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bookingId`),
  ADD UNIQUE KEY `transactNo` (`transactNo`),
  ADD KEY `bookingAccountId` (`accountId`),
  ADD KEY `bookingFlightId` (`flightId`),
  ADD KEY `bookingPackageId` (`packageId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `bookingAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingFlightId` FOREIGN KEY (`flightId`) REFERENCES `flight` (`flightId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingPackageId` FOREIGN KEY (`packageId`) REFERENCES `package` (`packageId`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
