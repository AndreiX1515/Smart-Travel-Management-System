-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 10:57 AM
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
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branchId` int(11) NOT NULL,
  `companyId` int(11) DEFAULT NULL,
  `branchName` varchar(100) DEFAULT NULL,
  `branchLocation` varchar(100) DEFAULT NULL,
  `branchAdd` text DEFAULT NULL,
  `branchContactP` varchar(50) DEFAULT NULL,
  `branchAgentCode` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branchId`, `companyId`, `branchName`, `branchLocation`, `branchAdd`, `branchContactP`, `branchAgentCode`) VALUES
(1, 4, 'Branch 1', NULL, NULL, NULL, 'BU1'),
(2, 1, 'Branch 2', NULL, NULL, NULL, 'BU2'),
(3, 2, 'Branch 3', NULL, NULL, NULL, 'BU3'),
(4, 5, 'E Winner', NULL, NULL, NULL, 'BU4'),
(5, 6, 'FRANCIA', NULL, NULL, 'BU1-A003', 'BU5'),
(6, 8, 'ESCAPE', NULL, NULL, NULL, 'BU6'),
(7, 7, 'APD', NULL, NULL, 'BU7-A001', 'BU7'),
(8, 3, 'Branch 8', NULL, NULL, NULL, 'BU8'),
(9, 9, 'Branch 9', NULL, NULL, NULL, 'BU9'),
(10, NULL, 'Branch 10', NULL, NULL, NULL, 'BU10'),
(11, NULL, 'Branch 11', NULL, NULL, NULL, 'BU11'),
(12, NULL, 'Branch 12', NULL, NULL, NULL, 'BU12'),
(13, NULL, 'Branch 13', NULL, NULL, NULL, 'BU13'),
(14, NULL, 'Branch 14', NULL, NULL, NULL, 'BU14'),
(15, NULL, 'Branch 15', NULL, NULL, NULL, 'BU15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branchId`),
  ADD UNIQUE KEY `branchAgentCode` (`branchAgentCode`),
  ADD KEY `branchContactP` (`branchContactP`),
  ADD KEY `branchCompanyId` (`companyId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branchId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branch`
--
ALTER TABLE `branch`
  ADD CONSTRAINT `branchCompanyId` FOREIGN KEY (`companyId`) REFERENCES `company` (`companyId`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `branchContactP` FOREIGN KEY (`branchContactP`) REFERENCES `agent` (`agentId`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
