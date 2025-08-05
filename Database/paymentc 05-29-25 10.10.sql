-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2025 at 04:10 AM
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
-- Table structure for table `paymentc`
--

CREATE TABLE `paymentc` (
  `paymentId` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `accountId` int(11) DEFAULT NULL,
  `paymentTitle` enum('Package Payment','Request Payment') DEFAULT NULL,
  `paymentType` enum('Downpayment','Partial Payment','Full Payment') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `filePath` text DEFAULT NULL,
  `paymentDate` datetime DEFAULT NULL,
  `paymentStatus` enum('Submitted','Approved','Rejected') DEFAULT NULL,
  `paymentRemarks` varchar(100) DEFAULT NULL,
  `performedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paymentc`
--

INSERT INTO `paymentc` (`paymentId`, `transactNo`, `accountId`, `paymentTitle`, `paymentType`, `amount`, `filePath`, `paymentDate`, `paymentStatus`, `paymentRemarks`, `performedBy`) VALUES
(1, 'BU1-000004', 32, 'Package Payment', 'Downpayment', 9000.00, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Client Payment Uploads\\BU1-000004\\BU1-000004-05-28-2025_11-40-68368592234b7.png', '2025-05-28 11:40:02', 'Approved', 'testing', 26),
(2, 'BU1-000004', 32, 'Package Payment', 'Partial Payment', 10000.00, 'C:/xampp/htdocs/SMART-TRAVEL-MANAGEMENT-SYSTEM/Files Uploads/Client Payment Uploads\\BU1-000004\\BU1-000004-05-28-2025_13-09-68369a86a48b5.png', '2025-05-28 13:09:26', 'Rejected', 'test', 26);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `paymentc`
--
ALTER TABLE `paymentc`
  ADD PRIMARY KEY (`paymentId`) USING BTREE,
  ADD KEY `paymentAccountId` (`accountId`),
  ADD KEY `transactNo` (`transactNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `paymentc`
--
ALTER TABLE `paymentc`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
