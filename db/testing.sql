-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 03:47 AM
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
(6, 'asdasdas', 'asdasdasd', 'asdasd', 'deguzmanandreivincent@gmail.com', '58073af4306fa0d9827904ce8237a500', '391916', 'active', '2024-10-04 10:04:40'),
(9, '', '', '', 'dreideguzman333@gmail.com', 'c93ccd78b2076528346216b3b2f701e6', '271881', 'inactive', '2024-10-07 05:10:32');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `bookingId` int(11) NOT NULL,
  `transactNo` varchar(50) NOT NULL,
  `fName` varchar(50) NOT NULL,
  `lName` varchar(50) NOT NULL,
  `mName` varchar(50) NOT NULL,
  `suffix` varchar(10) NOT NULL,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `contactNo` varchar(20) NOT NULL,
  `emailAdd` varchar(50) NOT NULL,
  `houseNo` varchar(20) NOT NULL,
  `street` varchar(50) NOT NULL,
  `subdivision` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `passportNo` varchar(50) NOT NULL,
  `passportExp` date NOT NULL,
  `flightId` int(11) NOT NULL,
  `visaStatus` varchar(50) NOT NULL,
  `totalPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`bookingId`, `transactNo`, `fName`, `lName`, `mName`, `suffix`, `birthdate`, `age`, `sex`, `nationality`, `contactNo`, `emailAdd`, `houseNo`, `street`, `subdivision`, `barangay`, `city`, `country`, `passportNo`, `passportExp`, `flightId`, `visaStatus`, `totalPrice`) VALUES
(1, 'TRANS_66fcb28335d81', 'test1', 'test1', 'test1', 'Jr.', '2002-01-02', 22, 'Male', 'Filipino', '09999999991', 'test1@gmail.com', 'blk 1 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000001a', '2024-10-31', 1, '', 0),
(2, 'TRANS_66fcdceed393d', 'Andrei', 'De Guzman', 'Lacorum', 'None', '2013-01-02', 22, 'Male', 'Filipino', '09936081235', 'deguzmanandreivincent@gmail.com', '123', 'Santos Drive', '', 'Zapote', 'Las Piñas', 'Philippines', '23123123asdasd123', '2024-10-09', 3, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `flightId` int(11) NOT NULL,
  `origin` varchar(50) NOT NULL,
  `flightName` varchar(50) NOT NULL,
  `flightCode` varchar(50) NOT NULL,
  `flightDepartureDate` date NOT NULL DEFAULT current_timestamp(),
  `flightDepartureTime` time NOT NULL DEFAULT current_timestamp(),
  `flightArrivalDate` date NOT NULL DEFAULT current_timestamp(),
  `flightArrivalTime` time NOT NULL,
  `returnFlightName` varchar(50) NOT NULL,
  `returnFlightCode` varchar(50) NOT NULL,
  `returnDepartureDate` date NOT NULL DEFAULT current_timestamp(),
  `returnDepartureTime` time NOT NULL DEFAULT current_timestamp(),
  `returnArrivalDate` date NOT NULL DEFAULT current_timestamp(),
  `returnArrivalTime` time NOT NULL DEFAULT current_timestamp(),
  `flightPrice` double NOT NULL,
  `packageId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`flightId`, `origin`, `flightName`, `flightCode`, `flightDepartureDate`, `flightDepartureTime`, `flightArrivalDate`, `flightArrivalTime`, `returnFlightName`, `returnFlightCode`, `returnDepartureDate`, `returnDepartureTime`, `returnArrivalDate`, `returnArrivalTime`, `flightPrice`, `packageId`) VALUES
(1, 'Manila', 'MNL - ICN', '5J188', '2024-09-30', '14:15:00', '2024-09-30', '00:00:00', 'ICN - MNL', '5J187', '2024-09-30', '14:25:00', '2024-09-30', '14:25:00', 0, 4),
(3, 'Cebu', '', '', '2024-09-30', '00:00:00', '2024-09-30', '00:00:00', '', '', '2024-09-30', '00:00:00', '2024-09-30', '00:00:00', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `hotelId` int(11) NOT NULL,
  `hotelName` varchar(50) NOT NULL,
  `hotelAdd` text NOT NULL,
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
(4, 'Marinabay Hotel Seoul', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `packageId` int(11) NOT NULL,
  `packageName` varchar(50) NOT NULL,
  `hotelId` int(11) NOT NULL,
  `packagePrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`packageId`, `packageName`, `hotelId`, `packagePrice`) VALUES
(2, 'Autumn Tour Package', 1, 0),
(4, 'Busan Tour Package', 3, 0),
(5, 'Cherry Blossom Tour Package', 2, 0),
(6, 'Regular Tour Package', 4, 0),
(7, 'Spring Tour Package', 1, 0),
(8, 'Summer Tour Package', 3, 0),
(9, 'Winter Tour Package', 2, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bookingId`),
  ADD KEY `bookingFlightId` (`flightId`);

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`flightId`),
  ADD KEY `flightPackageId` (`packageId`);

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
  MODIFY `accountid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flightId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `hotelId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `packageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `bookingFlightId` FOREIGN KEY (`flightId`) REFERENCES `flight` (`flightId`) ON UPDATE CASCADE;

--
-- Constraints for table `flight`
--
ALTER TABLE `flight`
  ADD CONSTRAINT `flightPackageId` FOREIGN KEY (`packageId`) REFERENCES `package` (`packageId`) ON UPDATE CASCADE;

--
-- Constraints for table `package`
--
ALTER TABLE `package`
  ADD CONSTRAINT `packageHotelId` FOREIGN KEY (`hotelId`) REFERENCES `hotel` (`hotelId`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
