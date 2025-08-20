-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2025 at 08:13 AM
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
  `fileType` enum('passport','permit','validId','certificate','guaranteedLetter','visaApplicationForm','picture','itinerary','others') DEFAULT NULL,
  `docSubType` varchar(50) DEFAULT NULL,
  `filePath` text DEFAULT NULL,
  `dateSubmitted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visarequirements`
--

INSERT INTO `visarequirements` (`requirementId`, `transactNo`, `accId`, `guestId`, `fileType`, `docSubType`, `filePath`, `dateSubmitted`) VALUES
(1, 'BU1-000001', 23, 1, 'visaApplicationForm', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads/BU1-000001/1\\VisaApplicationForm_1 _ 2025-08-19 - 15-18-04_68a4252c84e012.41671944.png', '2025-08-19 15:18:04'),
(2, 'BU1-000001', 23, 1, 'others', 'test1', 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads/BU1-000001/1\\Others_1 _ 2025-08-19 - 16-03-08_68a42fbc5c9215.71763008.png', '2025-08-19 16:03:08'),
(3, 'BU1-000001', 23, 1, 'picture', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads/BU1-000001/1\\Picture_1 _ 2025-08-20 - 08-58-22_68a51dae4cece7.91045287.png', '2025-08-20 08:58:22'),
(4, 'BU1-000001', 23, 1, 'itinerary', NULL, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Visa Requirements Uploads/BU1-000001/1\\Itinerary_1 _ 2025-08-20 - 08-58-35_68a51dbba58361.22592147.png', '2025-08-20 08:58:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `visarequirements`
--
ALTER TABLE `visarequirements`
  ADD PRIMARY KEY (`requirementId`),
  ADD KEY `visaGuestId` (`guestId`),
  ADD KEY `visaTransactNo` (`transactNo`),
  ADD KEY `accId` (`accId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `visarequirements`
--
ALTER TABLE `visarequirements`
  MODIFY `requirementId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `visarequirements`
--
ALTER TABLE `visarequirements`
  ADD CONSTRAINT `visaAccountId` FOREIGN KEY (`accId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `visaGuestId` FOREIGN KEY (`guestId`) REFERENCES `guest` (`guestId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `visaTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `guest` (`transactNo`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
