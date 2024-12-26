-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2024 at 08:55 AM
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
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `accountid` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `upassword` char(32) NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `account_status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`accountid`, `first_name`, `last_name`, `middle_name`, `email`, `upassword`, `otp`, `account_status`, `created_at`) VALUES
(1, '', '', '', 'deguzmanandreivincent@gmail.com', 'c93ccd78b2076528346216b3b2f701e6', '368535', 'active', '2024-10-08 02:52:57'),
(2, '', '', '', 'cordova.clarence09@gmail.com', '6b7330782b2feb4924020cc4a57782a9', '319973', 'active', '2024-10-08 03:49:34'),
(3, '', '', '', 'olicdumlao9@gmail.com', '1f4edce67bfe20bcb00d9cb24a91da7e', '111469', 'active', '2024-10-08 07:31:21');

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `agentId` int(11) NOT NULL,
  `fName` varchar(15) NOT NULL,
  `lName` varchar(15) NOT NULL,
  `mName` varchar(15) NOT NULL,
  `comission` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agentId`, `fName`, `lName`, `mName`, `comission`) VALUES
(1, 'Clarence', 'Cordova', '', 0.00),
(2, 'Andrei Vincent', 'De Guzman', 'Lacorum', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `bookingId` int(11) NOT NULL,
  `accountId` int(11) NOT NULL,
  `transactNo` varchar(30) NOT NULL,
  `agentId` int(11) NOT NULL,
  `pax` int(11) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `bookingDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`bookingId`, `accountId`, `transactNo`, `agentId`, `pax`, `totalPrice`, `bookingDate`) VALUES
(1, 1, 'TRANS-0000001', 1, 2, 0.00, '2024-10-07 09:58:06'),
(2, 1, 'TRANS-0000002', 1, 1, 0.00, '2024-10-07 09:58:06'),
(3, 1, 'TRANS-0000003', 1, 1, 0.00, '2024-10-07 09:58:06'),
(7, 1, 'TRANS-0000004', 1, 1, 0.00, '2024-10-07 09:58:06'),
(11, 1, 'TRANS-0000008', 1, 1, 0.00, '2024-10-07 09:58:06'),
(16, 1, 'TRANS-0000012', 1, 1, 0.00, '2024-10-07 09:58:06'),
(17, 1, 'TRANS-0000017', 1, 1, 0.00, '2024-10-07 09:58:06'),
(19, 1, 'TRANS-0000018', 1, 1, 0.00, '2024-10-07 09:58:06'),
(21, 1, 'TRANS-0000020', 1, 1, 0.00, '2024-10-07 09:58:06'),
(22, 1, 'TRANS-0000022', 1, 1, 0.00, '2024-10-07 09:58:06'),
(31, 1, 'TRANS-0000023', 1, 2, 0.00, '2024-10-07 09:58:06'),
(32, 1, 'TRANS-0000032', 1, 2, 60000.00, '2024-10-07 15:41:45'),
(33, 2, 'TRANS-0000033', 1, 1, 30000.00, '2024-10-08 06:51:16'),
(34, 2, 'TRANS-0000034', 1, 2, 30000.00, '2024-10-08 17:19:16'),
(35, 2, 'TRANS-0000035', 1, 2, 60000.00, '2024-10-09 11:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `flightId` int(11) NOT NULL,
  `origin` varchar(30) NOT NULL,
  `flightName` varchar(30) NOT NULL,
  `flightCode` varchar(30) NOT NULL,
  `flightDepartureDate` date NOT NULL,
  `flightDepartureTime` time NOT NULL,
  `flightArrivalDate` date NOT NULL,
  `flightArrivalTime` time NOT NULL,
  `returnFlightName` varchar(30) NOT NULL,
  `returnFlightCode` varchar(30) NOT NULL,
  `returnDepartureDate` date NOT NULL,
  `returnDepartureTime` time NOT NULL,
  `returnArrivalDate` date NOT NULL,
  `returnArrivalTime` time NOT NULL,
  `flightPrice` decimal(10,2) NOT NULL,
  `packageId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`flightId`, `origin`, `flightName`, `flightCode`, `flightDepartureDate`, `flightDepartureTime`, `flightArrivalDate`, `flightArrivalTime`, `returnFlightName`, `returnFlightCode`, `returnDepartureDate`, `returnDepartureTime`, `returnArrivalDate`, `returnArrivalTime`, `flightPrice`, `packageId`) VALUES
(1, 'Manila', 'MNL - ICN', '5J187', '2024-10-03', '15:40:00', '2024-10-03', '21:20:00', 'ICN - MNL', '5J188', '2024-10-07', '22:15:00', '2024-10-08', '01:55:00', 30000.00, 1),
(2, 'Cebu', 'CEB - ICN', '5J128', '2024-10-04', '15:40:00', '2024-10-04', '21:20:00', 'ICN - CEB', '5J129', '2024-10-08', '22:15:00', '2024-10-09', '01:55:00', 30000.00, 3),
(3, 'Manila', 'MNL - ICN', '5J187', '2024-10-02', '15:40:00', '2024-10-02', '21:20:00', 'ICN - MNL', '5J188', '2024-10-07', '15:40:00', '2024-10-07', '21:20:00', 35888.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE `guest` (
  `guestId` int(11) NOT NULL,
  `transactNo` varchar(30) NOT NULL,
  `flightId` int(11) NOT NULL,
  `fName` varchar(15) NOT NULL,
  `lName` varchar(15) NOT NULL,
  `mName` varchar(15) NOT NULL,
  `suffix` varchar(10) NOT NULL,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `contactNo` varchar(20) NOT NULL,
  `emailAdd` varchar(50) NOT NULL,
  `houseNo` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `subdivision` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `passportNo` varchar(50) NOT NULL,
  `passportExp` date NOT NULL,
  `visaStatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`guestId`, `transactNo`, `flightId`, `fName`, `lName`, `mName`, `suffix`, `birthdate`, `age`, `sex`, `nationality`, `contactNo`, `emailAdd`, `houseNo`, `street`, `subdivision`, `barangay`, `city`, `country`, `passportNo`, `passportExp`, `visaStatus`) VALUES
(1, 'TRANS-0000001', 2, 'test1', 'test1', 'test1', 'Jr.', '2000-02-03', 24, 'Male', 'Filipino', '09999999991', 'test1@gmail.com', 'blk 1 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000001a', '2024-10-31', ''),
(2, 'TRANS-0000001', 1, 'test2', 'test2', 'test2', 'Sr.', '2000-01-03', 24, 'Male', 'Filipino', '09999999992', 'test2@gmail.com', 'blk 2 lot 1', 'sample', 'sample', 'sample', 'Makati', 'Philippines', 'a0000002a', '2024-10-31', ''),
(3, 'TRANS-0000002', 2, 'test3', 'test3', 'test3', 'Sr.', '2002-01-03', 22, 'Male', 'Filipino', '09999999993', 'test3@gmail.com', 'blk 3 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Philippines', 'a0000003a', '2024-10-31', ''),
(4, 'TRANS-0000003', 1, 'test4', 'test4', 'test4', 'Sr.', '2003-02-04', 21, 'Male', 'American', '09999999994', 'test4@gmail.com', 'blk 4 lot 1', 'sample', 'sample', 'sample', 'Caloocan', 'Philippines', 'a0000004a', '2024-10-01', ''),
(8, 'TRANS-0000004', 1, 'test5', 'test5', 'test5', 'Jr.', '2002-02-04', 22, 'Male', 'Filipino', '09999999995', 'test5@gmail.com', 'blk 5 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000005a', '2024-11-01', ''),
(12, 'TRANS-0000008', 1, 'test6', 'test6', 'test6', 'V', '2002-01-04', 22, 'Male', 'Filipino', '09999999996', 'test6@gmail.com', 'blk 6 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Philippines', 'a0000006a', '2024-11-09', ''),
(17, 'TRANS-0000012', 1, 'test7', 'test7', 'test7', 'IV', '2004-03-10', 20, 'Male', 'Filipino', '09999999997', 'test7@gmail.com', 'blk 7 lot 1', 'sample', 'sample', 'sample', 'Caloocan', 'Philippines', 'a0000007a', '2024-11-03', ''),
(18, 'TRANS-0000017', 1, 'test8', 'test8', 'test8', 'Jr.', '2004-03-11', 20, 'Male', 'Filipino', '09999999998', 'test8@gmail.com', 'blk 8 lot 1', 'sample', 'sample', 'sample', 'Muntinlupa', 'Philippines', 'a0000008a', '2024-10-31', ''),
(20, 'TRANS-0000018', 1, 'test9', 'test9', 'test9', 'Jr.', '2004-02-03', 20, 'Male', 'Filipino', '09999999999', 'test9@gmail.com', 'blk 9 lot 1', 'sample', 'sample', 'sample', 'Caloocan', 'Philippines', 'a0000009a', '2024-10-31', ''),
(22, 'TRANS-0000020', 1, 'test10', 'test10', 'test10', 'II', '2004-01-04', 20, 'Male', 'Filipino', '09999999910', 'test10@gmail.com', 'blk 10 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Philippines', 'a0000010a', '2024-10-15', ''),
(23, 'TRANS-0000022', 1, 'test11', 'test11', 'test11', 'Jr.', '2004-06-05', 20, 'Male', 'Filipino', '09999999911', 'test11@gmail.com', 'blk 11 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000011a', '2024-10-24', ''),
(32, 'TRANS-0000023', 1, 'test12', 'test12', 'test12', 'Jr.', '2004-02-03', 20, 'Male', 'Filipino', '09999999912', 'test12@gmail.com', 'blk 12 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000012a', '2024-10-31', ''),
(33, 'TRANS-0000023', 1, 'test13', 'test13', 'test13', 'II', '2024-10-08', 20, 'Male', 'Filipino', '09999999913', 'test13@gmail.com', 'blk 13 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000013a', '2024-10-31', ''),
(34, 'TRANS-0000032', 1, 'test14', 'test14', 'test14', 'Jr.', '2002-02-07', 22, 'Male', 'Filipino', '09999999914', 'test14@gmail.com', 'blk 14 lot 1', 'sample', 'sample', 'sample', 'Caloocan', 'Philippines', 'a0000014a', '2024-10-31', ''),
(35, 'TRANS-0000032', 1, 'test15', 'test15', 'test15', 'IV', '2004-02-10', 20, 'Male', 'Filipino', '09999999915', 'test15@gmail.com', 'blk 15 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000015a', '2024-10-31', ''),
(36, 'TRANS-0000033', 1, 'test16', 'test16', 'test16', 'Jr.', '2004-02-10', 20, 'Male', 'Filipino', '09999999916', 'test16@gmail.com', 'blk 16 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000016a', '2024-10-31', ''),
(37, 'TRANS-0000034', 1, 'test17', 'test17', 'test17', 'Jr.', '2002-02-07', 22, 'Male', 'Filipino', '09999999917', 'test17@gmail.com', 'blk 17 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Philippines', 'a0000017a', '2024-10-31', ''),
(38, 'TRANS-0000034', 1, 'test18', 'test18', 'test18', 'II', '2004-02-10', 20, 'Male', 'Filipino', '09999999918', 'test18@gmail.com', 'blk 18 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000018a', '2024-10-31', ''),
(39, 'TRANS-0000035', 1, 'test19', 'test19', '', '', '2005-10-02', 19, 'Male', 'Filipino', '09999999919', 'test19@gmail.com', 'blk 19 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Philippines', 'a0000019a', '2024-10-31', ''),
(40, 'TRANS-0000035', 1, 'test20', 'test20', 'test20', 'III', '2004-06-15', 20, 'Male', 'Japanese', '09999999920', 'test20@gmail.com', 'blk 20 lot 1', 'sample', 'sample', 'sample', 'Caloocan', 'Korea', 'a0000020a', '2024-10-31', '');

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `hotelId` int(11) NOT NULL,
  `hotelName` varchar(50) NOT NULL,
  `hotelAdd` varchar(255) NOT NULL,
  `hotelContactNo` varchar(20) NOT NULL,
  `hotelEmailAdd` varchar(50) NOT NULL,
  `hotelDescr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`hotelId`, `hotelName`, `hotelAdd`, `hotelContactNo`, `hotelEmailAdd`, `hotelDescr`) VALUES
(1, 'AirSky Hotel', '', '', '', ''),
(2, 'Insadong Ibis Hotel', '', '', '', ''),
(3, 'Centum Hotel', '', '', '', ''),
(4, 'Marinabay Hotel Seoul', '', '', '', ''),
(5, 'Royal Emporium Hotel', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `packageId` int(11) NOT NULL,
  `packageName` varchar(50) NOT NULL,
  `hotelId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`packageId`, `packageName`, `hotelId`) VALUES
(1, 'Autumn Tour Package', 1),
(2, 'Busan Tour Package', 3),
(3, 'Cherry Blossom Tour Package', 2),
(4, 'Regular Tour Package', 4),
(5, 'Spring Tour Package', 1),
(6, 'Summer Tour Package', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `session_id` varchar(255) NOT NULL,
  `accountid` int(11) NOT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `last_activity` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`session_id`, `accountid`, `login_time`, `last_activity`, `ip_address`, `user_agent`) VALUES
('k4aartpubsbtjid1a0u1pkeu2d', 2, '2024-10-09 14:46:03', '2024-10-09 14:46:04', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Mobile Safari/537.36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountid`);

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`agentId`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bookingId`),
  ADD UNIQUE KEY `transactNo` (`transactNo`),
  ADD KEY `bookingAccountId` (`accountId`),
  ADD KEY `bookingAgentId` (`agentId`);

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`flightId`),
  ADD KEY `flightPackageId` (`packageId`);

--
-- Indexes for table `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`guestId`),
  ADD KEY `guestFlightId` (`flightId`),
  ADD KEY `guestTransactNo` (`transactNo`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`hotelId`),
  ADD UNIQUE KEY `hotelName` (`hotelName`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`packageId`),
  ADD UNIQUE KEY `packageName` (`packageName`),
  ADD KEY `packageHotelId` (`hotelId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accountid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `agentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flightId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `guestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `hotelId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `packageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `bookingAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingAgentId` FOREIGN KEY (`agentId`) REFERENCES `agent` (`agentId`) ON UPDATE CASCADE;

--
-- Constraints for table `flight`
--
ALTER TABLE `flight`
  ADD CONSTRAINT `flightPackageId` FOREIGN KEY (`packageId`) REFERENCES `package` (`packageId`) ON UPDATE CASCADE;

--
-- Constraints for table `guest`
--
ALTER TABLE `guest`
  ADD CONSTRAINT `guestFlightId` FOREIGN KEY (`flightId`) REFERENCES `flight` (`flightId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `guestTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `booking` (`transactNo`) ON UPDATE CASCADE;

--
-- Constraints for table `package`
--
ALTER TABLE `package`
  ADD CONSTRAINT `packageHotelId` FOREIGN KEY (`hotelId`) REFERENCES `hotel` (`hotelId`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
