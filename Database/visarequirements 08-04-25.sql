-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2025 at 10:32 AM
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
-- Table structure for table `visarequirements`
--

CREATE TABLE `visarequirements` (
  `requirementId` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `accId` int(11) DEFAULT NULL,
  `guestId` int(11) DEFAULT NULL,
  `fileType` enum('passport','permit','validId','certificate','guaranteedLetter') DEFAULT NULL,
  `docSubType` varchar(50) DEFAULT NULL,
  `filePath` text DEFAULT NULL,
  `dateSubmitted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visarequirements`
--

INSERT INTO `visarequirements` (`requirementId`, `transactNo`, `accId`, `guestId`, `fileType`, `docSubType`, `filePath`, `dateSubmitted`) VALUES
(5, 'BU2-000033', 6, 7, NULL, NULL, NULL, '2025-02-21 13:47:03'),
(6, 'BU2-000003', 23, 8, NULL, NULL, NULL, '2025-02-27 14:38:58'),
(7, 'BU1-000005', 32, 9, NULL, NULL, NULL, '2025-02-27 15:11:35'),
(9, 'BU1-000002', 23, 10, 'passport', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\10\\Passport_ 10 _ 2025-03-11 - 16-33-51.png', '2025-03-11 16:33:51'),
(17, 'BU1-000002', 23, 10, 'permit', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\10\\Permit_ 10 _ 2025-03-12 - 04-54-40.png', '2025-03-12 00:00:00'),
(18, 'BU1-000002', 23, 10, 'validId', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\10\\ValidId_ 10 _ 2025-03-12 - 04-54-48.png', '2025-03-12 00:00:00'),
(20, 'BU1-000002', 23, 10, 'certificate', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\10\\Certificate_ 10 _ 2025-03-12 - 04-59-57.png', '2025-03-12 00:00:00'),
(21, 'BU1-000002', 23, 10, 'guaranteedLetter', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\10\\GuaranteedLetter_ 10 _ 2025-03-12 - 05-22-03.png', '2025-03-12 00:00:00'),
(24, 'BU1-000002', 23, 11, 'passport', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\11\\Passport_ 11 _ 2025-03-12 - 12-50-15.png', '2025-03-12 12:50:15'),
(25, 'BU1-000002', 23, 11, 'permit', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\11\\Permit_ 11 _ 2025-03-12 - 12-57-45.png', '2025-03-12 12:57:45'),
(27, 'BU1-000001', 32, 12, 'passport', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000001\\12\\Passport_ 12 _ 2025-03-12 - 10-21-53.png', '2025-03-12 00:00:00'),
(29, 'BU1-000001', 32, 12, 'guaranteedLetter', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000001\\12\\GuaranteedLetter_ 12 _ 2025-03-12 - 10-39-54.png', '2025-03-12 00:00:00'),
(30, 'BU1-000001', 32, 12, 'permit', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000001\\12\\Permit_ 12 _ 2025-03-12 - 17-40-18.png', '2025-03-12 17:40:18'),
(31, 'BU1-000001', 32, 13, 'permit', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000001\\13\\Permit_ 13 _ 2025-03-12 - 17-40-18.png', '2025-03-12 17:40:18'),
(32, 'BU1-000002', 23, 10, 'passport', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\10\\Passport_ 10 _ 2025-03-14 - 15-31-53.png', '2025-03-14 15:31:53'),
(34, 'BU1-000002', 23, 11, 'validId', NULL, '/home/u528957090/domains/clarencetesting.smart-travelkorea.com/public_html/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads/BU1-000002/11/ValidId_ 11 _ 2025-05-14 - 06-55-51.jpg', '2025-05-14 00:00:00'),
(35, 'BU1-000002', 23, 10, 'passport', NULL, '/home/u528957090/domains/clarencetesting.smart-travelkorea.com/public_html/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads/BU1-000002/10/Passport_ 10 _ 2025-05-14 - 15-02-55.jpg', '2025-05-14 15:02:55'),
(36, 'BU1-000002', 23, 10, 'passport', NULL, '/home/u528957090/domains/clarencetesting.smart-travelkorea.com/public_html/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads/BU1-000002/10/Passport_ 10 _ 2025-05-15 - 09-24-38.jpg', '2025-05-15 09:24:38'),
(37, 'BU1-000002', 23, 10, 'guaranteedLetter', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\10\\GuaranteedLetter_ 10 _ 2025-08-01 - 15-04-37.png', '2025-08-01 15:04:37'),
(38, 'BU1-000002', 23, 10, 'permit', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\10\\Permit_ 10 _ 2025-08-01 - 15-12-34.png', '2025-08-01 15:12:34'),
(39, 'BU1-000002', 23, 10, 'permit', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\10\\Permit_ 10 _ 2025-08-01 - 15-26-15.png', '2025-08-01 15:26:15'),
(40, 'BU1-000002', 23, 14, 'permit', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\14\\Permit_ 14 _ 2025-08-01 - 15-26-34.png', '2025-08-01 15:26:34'),
(41, 'BU1-000002', 23, 14, 'certificate', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\14\\Certificate_ 14 _ 2025-08-01 - 15-28-21.png', '2025-08-01 15:28:21'),
(43, 'BU1-000002', 23, 15, 'passport', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\15\\Passport_15 _ 2025-08-04 - 14-11-38_68904f1a6983f2.36890955.png', '2025-08-04 14:11:38'),
(44, 'BU1-000002', 23, 10, 'validId', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\10\\ValidId_10 _ 2025-08-04 - 14-26-28_68905294cad8c8.10932342.png', '2025-08-04 14:26:28'),
(45, 'BU1-000002', 23, 15, 'permit', 'businessPermit', 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\15\\Permit_15 _ 2025-08-04 - 14-28-13_689052fd285303.94223290.png', '2025-08-04 14:28:13'),
(46, 'BU1-000002', 23, 15, 'certificate', 'coe', 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\15\\Certificate_15 _ 2025-08-04 - 14-28-36_68905314c61522.44184265.png', '2025-08-04 14:28:36'),
(47, 'BU1-000002', 23, 15, 'certificate', 'bankCert', 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads\\BU1-000002\\15\\Certificate_15 _ 2025-08-04 - 16-29-47_68906f7be2bce9.29624155.png', '2025-08-04 16:29:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `visarequirements`
--
ALTER TABLE `visarequirements`
  ADD PRIMARY KEY (`requirementId`),
  ADD KEY `visaGuestId` (`guestId`),
  ADD KEY `visaTransactNo` (`transactNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `visarequirements`
--
ALTER TABLE `visarequirements`
  MODIFY `requirementId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `visarequirements`
--
ALTER TABLE `visarequirements`
  ADD CONSTRAINT `visaGuestId` FOREIGN KEY (`guestId`) REFERENCES `guest` (`guestId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `visaTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `guest` (`transactNo`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
