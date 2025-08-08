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
-- Table structure for table `guest`
--

CREATE TABLE `guest` (
  `guestId` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `fName` varchar(50) DEFAULT NULL,
  `lName` varchar(50) DEFAULT NULL,
  `mName` varchar(50) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `countryCode` varchar(10) DEFAULT NULL,
  `contactNo` varchar(20) DEFAULT NULL,
  `countryCode2` varchar(10) DEFAULT NULL,
  `contactNo2` varchar(20) DEFAULT NULL,
  `emailAdd` varchar(255) DEFAULT NULL,
  `addressLine1` varchar(255) DEFAULT NULL,
  `addressLine2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zipCode` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `passportNo` varchar(50) DEFAULT NULL,
  `passportIssuedDate` date DEFAULT NULL,
  `passportExp` date DEFAULT NULL,
  `isInfant` tinyint(4) NOT NULL DEFAULT 0,
  `visaStatus` varchar(50) DEFAULT NULL,
  `visaRemarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`guestId`, `transactNo`, `fName`, `lName`, `mName`, `suffix`, `birthdate`, `age`, `sex`, `nationality`, `countryCode`, `contactNo`, `countryCode2`, `contactNo2`, `emailAdd`, `addressLine1`, `addressLine2`, `city`, `state`, `zipCode`, `country`, `passportNo`, `passportIssuedDate`, `passportExp`, `isInfant`, `visaStatus`, `visaRemarks`) VALUES
(10, 'BU1-000002', 'TEST20', 'TEST20', 'TEST20', 'Jr.', '2007-01-01', 18, 'Male', 'Filipino', '+63', '9999999991', NULL, NULL, 'test1@gmail.com', 'blk 38 lot 1 marosa st. naga road', NULL, 'Muntinlupa', 'NCR', '1742', 'Philippines', 'A0000001A', NULL, '2027-10-12', 0, NULL, NULL),
(11, 'BU1-000002', 'TEST2', 'TEST2', 'TEST2', 'N/A', '2005-03-09', 20, 'Male', 'Filipino', '+63', '9999999991', NULL, NULL, 'test1@gmail.com', 'blk 51 lot 1 marosa st. naga road', NULL, 'Parañaque', 'NCR', '1742', 'Philippines', 'A0000001A', NULL, '2028-10-18', 0, NULL, NULL),
(12, 'BU1-000001', 'TEST1', 'TEST1', 'TEST1', 'N/A', '1996-01-30', 29, 'Male', 'Filipino', '+63', '9999999991', NULL, NULL, 'test1@gmail.com', 'blk 42 lot 1 marosa st. naga road', NULL, 'Parañaque', 'NCR', '1742', 'Philippines', 'A0000001A', NULL, '2027-06-19', 0, NULL, NULL),
(13, 'BU1-000001', 'TEST2', 'TEST2', 'TEST2', 'Sr.', '2018-01-30', 7, 'Male', 'Filipino', '+63', '9999999991', NULL, NULL, 'test1@gmail.com', 'blk 38 lot 1 marosa st. naga road', NULL, 'Manila', 'NCR', '1742', 'Philippines', 'A0000001A', NULL, '2029-06-13', 0, NULL, NULL),
(14, 'BU1-000002', 'TEST3', 'TEST3', 'TEST3', 'N/A', '2009-06-02', 16, 'Male', 'Filipino', '+63', '9999999993', NULL, NULL, 'test3@gmail.com', 'blk 1 lot 1 marosa st. naga road', NULL, 'Caloocan', 'NCR', '1742', 'Philippines', 'A0000003A', NULL, '2029-06-21', 0, NULL, NULL),
(15, 'BU1-000002', 'TEST4', 'TEST4', 'TEST1', 'N/A', '2012-02-09', 13, 'Female', 'Filipino', '+63', '9999999994', NULL, NULL, 'test1@gmail.com', 'blk 51 lot 1 marosa st. naga road', 'balay uno', 'Manila', 'NCR', '1742', 'Philippines', 'A0000004A', NULL, '2032-02-05', 0, NULL, NULL),
(16, 'BU1-000002', 'JOAQUIN', 'RUINATA', 'N/A', 'N/A', '1998-03-12', 27, 'Male', 'Filipino', '+63', '9999999995', NULL, NULL, 'test5@gmail.com', 'blk 49 lot 1 marosa st. naga road', NULL, 'Parañaque', 'NCR', '1742', 'Philippines', 'A0000004A', NULL, '2028-02-08', 0, NULL, NULL),
(17, 'BU1-000012', 'TEST1', 'TEST1', 'TEST1', 'N/A', '2001-06-06', 24, 'Male', 'Filipino', '+63', '9999999991', NULL, NULL, 'test16@gmail.com', 'blk 42 lot 1 marosa st. naga road', NULL, 'Caloocan', 'NCR', '1742', 'Argentina', 'A0000001A', '2025-06-03', '2028-07-12', 0, NULL, NULL),
(18, 'BU1-000001', 'TEST1', 'TEST1', 'TEST1', 'N/A', '2000-02-02', 25, 'Male', 'Filipino', '+63', '9999999991', NULL, NULL, 'test16@gmail.com', 'blk 38 lot 1 marosa st. naga road', NULL, 'Muntinlupa', 'NCR', '1742', 'Philippines', 'A0000001A', '2025-06-10', '2028-06-15', 0, NULL, NULL),
(19, 'BU1-000002', 'TEST1', 'TEST1', 'TEST1', 'Sr.', '2013-01-30', 12, 'Male', 'Afghan', '+63', '9999999991', NULL, NULL, 'test16@gmail.com', 'blk 42 lot 1 marosa st. naga road', NULL, 'Makati', 'NCR', '1742', 'Philippines', 'A0000001A', '2025-06-05', '2029-06-05', 0, NULL, NULL),
(20, 'BU1-000002', 'TEST1', 'TEST1', 'TEST1', 'Sr.', '2013-01-22', 12, 'Male', 'Afghan', '+63', '9999999991', NULL, NULL, 'test16@gmail.com', 'blk 42 lot 1 marosa st. naga road', NULL, 'Makati', 'NCR', '1742', 'Philippines', 'A0000001A', '2025-06-05', '2029-06-05', 0, NULL, NULL),
(21, 'BU1-000002', 'TEST1', 'TEST1', 'TEST1', 'Sr.', '2014-01-30', 11, 'Male', 'Afghan', '+63', '9999999991', NULL, NULL, 'test16@gmail.com', 'blk 1 lot 1 marosa st. naga road', NULL, 'Makati', 'NCR', '1742', 'Armenia', 'A0000001A', '2025-06-24', '2028-10-17', 0, NULL, NULL),
(22, 'BU1-000113', 'TEST1', 'TEST1', 'TEST1', 'Jr.', '2025-07-27', 0, 'Male', 'Filipino', '+63', '9999999991', NULL, NULL, 'test16@gmail.com', 'blk 51 lot 1 marosa st. naga road', NULL, 'Muntinlupa', 'NCR', '1742', 'Philippines', 'A0000001A', '2025-06-30', '2029-11-16', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`guestId`),
  ADD KEY `guestTransactNo` (`transactNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `guestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `guest`
--
ALTER TABLE `guest`
  ADD CONSTRAINT `guestTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `booking` (`transactNo`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
