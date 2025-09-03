-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2025 at 11:00 AM
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
-- Table structure for table `trip`
--

CREATE TABLE `trip` (
  `tripId` int(11) NOT NULL,
  `packageId` int(11) NOT NULL,
  `employeeId` varchar(50) DEFAULT 'No Team OP',
  `tourGuideId` int(11) DEFAULT NULL,
  `busId` int(11) DEFAULT NULL,
  `driverId` int(11) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `landPrice` decimal(10,2) DEFAULT 0.00,
  `retailPrice` decimal(10,2) DEFAULT 0.00,
  `wholesalePrice` decimal(10,2) DEFAULT 0.00,
  `availableSeats` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trip`
--

INSERT INTO `trip` (`tripId`, `packageId`, `employeeId`, `tourGuideId`, `busId`, `driverId`, `startDate`, `endDate`, `landPrice`, `retailPrice`, `wholesalePrice`, `availableSeats`, `is_active`) VALUES
(1, 1, NULL, NULL, NULL, NULL, '2025-09-02', '2025-09-07', 20000.00, 30000.00, 39000.00, 40, 0),
(2, 1, 'SMT-E001', NULL, NULL, NULL, '2025-09-03', '2025-09-08', 21000.00, 31000.00, 40000.00, 40, 0),
(4, 1, 'SMT-E001', NULL, NULL, NULL, '2025-09-04', '2025-09-09', 20000.00, 30000.00, 39000.00, 40, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`tripId`),
  ADD KEY `tripPackageId` (`packageId`),
  ADD KEY `tripEmployeeId` (`employeeId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `trip`
--
ALTER TABLE `trip`
  MODIFY `tripId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `tripEmployeeId` FOREIGN KEY (`employeeId`) REFERENCES `employee` (`employeeId`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `tripPackageId` FOREIGN KEY (`packageId`) REFERENCES `package` (`packageId`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
