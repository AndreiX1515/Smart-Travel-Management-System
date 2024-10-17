-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2024 at 09:49 AM
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
(2, 'Clarence', 'Cordova', '', 'cordova.clarence09@gmail.com', '6b7330782b2feb4924020cc4a57782a9', '319973', 'active', '2024-10-08 03:49:34'),
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
  `contactNo` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agentId`, `fName`, `lName`, `mName`, `contactNo`, `username`, `password`) VALUES
(1, 'Clarence', 'Cordova', '', '', 'cordova.clarence09@gmail.com', 'testing1'),
(2, 'Andrei Vincent', 'De Guzman', 'Lacorum', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `bookingId` int(11) NOT NULL,
  `accountId` int(11) NOT NULL,
  `transactNo` varchar(30) NOT NULL,
  `agentId` int(11) DEFAULT NULL,
  `flightId` int(11) NOT NULL,
  `packageId` int(11) NOT NULL,
  `pax` int(11) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `bookingDate` datetime NOT NULL DEFAULT current_timestamp(),
  `downpaymentAmount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Downpayment','Paid','Partially Paid') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`bookingId`, `accountId`, `transactNo`, `agentId`, `flightId`, `packageId`, `pax`, `totalPrice`, `bookingDate`, `downpaymentAmount`, `status`) VALUES
(1, 1, 'TRANS-0000001', 1, 0, 0, 2, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(2, 1, 'TRANS-0000002', 1, 0, 0, 1, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(3, 1, 'TRANS-0000003', 1, 0, 0, 1, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(7, 1, 'TRANS-0000004', 1, 0, 0, 1, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(11, 1, 'TRANS-0000008', 1, 0, 0, 1, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(16, 1, 'TRANS-0000012', 1, 0, 0, 1, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(17, 1, 'TRANS-0000017', 1, 0, 0, 1, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(19, 1, 'TRANS-0000018', 1, 0, 0, 1, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(21, 1, 'TRANS-0000020', 1, 0, 0, 1, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(22, 1, 'TRANS-0000022', 1, 0, 0, 1, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(31, 1, 'TRANS-0000023', 1, 0, 0, 2, 0.00, '2024-10-07 09:58:06', 0.00, 'Pending'),
(32, 1, 'TRANS-0000032', 1, 0, 0, 2, 60000.00, '2024-10-07 15:41:45', 0.00, 'Pending'),
(33, 2, 'TRANS-0000033', 1, 0, 0, 1, 30000.00, '2024-10-08 06:51:16', 0.00, 'Pending'),
(34, 2, 'TRANS-0000034', 1, 0, 0, 2, 30000.00, '2024-10-08 17:19:16', 0.00, 'Pending'),
(35, 2, 'TRANS-0000035', 1, 0, 0, 2, 60000.00, '2024-10-09 11:28:13', 0.00, 'Pending'),
(36, 2, 'TRANS-0000036', 1, 0, 0, 1, 35888.00, '2024-10-11 16:00:46', 0.00, 'Pending'),
(37, 2, 'TRANS-0000037', 1, 0, 0, 2, 71777.76, '2024-10-11 16:45:39', 0.00, 'Pending'),
(38, 2, 'TRANS-0000038', 1, 0, 0, 1, 38699.17, '2024-10-15 11:42:31', 0.00, 'Pending'),
(39, 2, 'TRANS-0000039', 1, 12, 1, 1, 36888.00, '2024-10-15 11:50:47', 0.00, 'Pending'),
(40, 2, 'TRANS-0000040', 1, 51, 2, 1, 39888.00, '2024-10-15 15:06:51', 0.00, 'Pending'),
(41, 2, 'TRANS-0000041', 1, 4, 1, 1, 35888.88, '2024-10-16 10:26:37', 0.00, 'Pending'),
(42, 2, 'TRANS-0000042', 1, 3, 1, 1, 35888.00, '2024-10-16 15:13:24', 0.00, 'Pending'),
(43, 2, 'TRANS-0000043', 1, 3, 1, 1, 35888.00, '2024-10-16 15:32:56', 0.00, 'Pending'),
(44, 2, 'TRANS-0000044', 1, 14, 1, 1, 36221.04, '2024-10-16 16:13:37', 0.00, 'Pending'),
(45, 2, 'TRANS-0000045', 1, 54, 2, 1, 36888.00, '2024-10-16 16:24:14', 0.00, 'Pending'),
(46, 2, 'TRANS-0000046', 1, 3, 1, 1, 35888.00, '2024-10-16 16:39:35', 0.00, 'Pending');

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
(3, 'Manila', 'MNL - ICN', '5J187', '2024-10-02', '15:40:00', '2024-10-02', '21:20:00', 'ICN - MNL', '5J188', '2024-10-07', '15:40:00', '2024-10-07', '21:20:00', 35888.00, 1),
(4, 'Manila', 'MNL - ICN', '5J187', '2024-10-04', '15:40:00', '2024-10-04', '21:20:00', 'ICN - MNL', '5J188', '2024-10-09', '15:40:00', '2024-10-09', '21:20:00', 35888.88, 1),
(5, 'Manila', 'MNL - ICN', '5J187', '2024-10-05', '15:40:00', '2024-10-05', '21:20:00', 'ICN - MNL', '5J188', '2024-10-10', '15:40:00', '2024-10-10', '21:20:00', 36321.04, 1),
(6, 'Manila', 'MNL - ICN', '5J187', '2024-10-08', '15:40:00', '2024-10-08', '21:20:00', 'ICN - MNL', '5J188', '2024-10-13', '15:40:00', '2024-10-13', '21:20:00', 37221.04, 1),
(7, 'Manila', 'MNL - ICN', '5J187', '2024-10-09', '15:40:00', '2024-10-09', '21:20:00', 'ICN - MNL', '5J188', '2024-10-14', '15:40:00', '2024-10-14', '21:20:00', 35888.00, 1),
(8, 'Manila', 'MNL - ICN', '5J187', '2024-10-10', '15:40:00', '2024-10-10', '21:20:00', 'ICN - MNL', '5J188', '2024-10-15', '15:40:00', '2024-10-15', '21:20:00', 36888.00, 1),
(9, 'Manila', 'MNL - ICN', '5J187', '2024-10-11', '15:40:00', '2024-10-11', '21:20:00', 'ICN - MNL', '5J188', '2024-10-16', '15:40:00', '2024-10-16', '21:20:00', 35888.00, 1),
(10, 'Manila', 'MNL - ICN', '5J187', '2024-10-16', '15:40:00', '2024-10-16', '21:20:00', 'ICN - MNL', '5J188', '2024-10-21', '15:40:00', '2024-10-21', '21:20:00', 36888.00, 1),
(11, 'Manila', 'MNL - ICN', '5J187', '2024-10-17', '15:40:00', '2024-10-17', '21:20:00', 'ICN - MNL', '5J188', '2024-10-22', '15:40:00', '2024-10-22', '21:20:00', 37888.00, 1),
(12, 'Manila', 'MNL - ICN', '5J187', '2024-10-18', '15:40:00', '2024-10-18', '21:20:00', 'ICN - MNL', '5J188', '2024-10-23', '15:40:00', '2024-10-23', '21:20:00', 36888.00, 1),
(13, 'Manila', 'MNL - ICN', '5J187', '2024-10-19', '15:40:00', '2024-10-19', '21:20:00', 'ICN - MNL', '5J188', '2024-10-24', '15:40:00', '2024-10-24', '21:20:00', 37121.04, 1),
(14, 'Manila', 'MNL - ICN', '5J187', '2024-10-20', '15:40:00', '2024-10-20', '21:20:00', 'ICN - MNL', '5J188', '2024-10-25', '15:40:00', '2024-10-25', '21:20:00', 36221.04, 1),
(15, 'Manila', 'MNL - ICN', '5J187', '2024-10-22', '15:40:00', '2024-10-22', '21:20:00', 'ICN - MNL', '5J188', '2024-10-27', '15:40:00', '2024-10-27', '21:20:00', 37888.00, 1),
(16, 'Manila', 'MNL - ICN', '5J187', '2024-10-23', '15:40:00', '2024-10-23', '21:20:00', 'ICN - MNL', '5J188', '2024-10-28', '15:40:00', '2024-10-28', '21:20:00', 37888.00, 1),
(17, 'Manila', 'MNL - ICN', '5J187', '2024-10-23', '15:40:00', '2024-10-23', '21:20:00', 'ICN - MNL', '5J188', '2024-10-28', '15:40:00', '2024-10-28', '21:20:00', 37888.00, 1),
(18, 'Manila', 'MNL - ICN', '5J187', '2024-10-24', '15:40:00', '2024-10-24', '21:20:00', 'ICN - MNL', '5J188', '2024-10-29', '15:40:00', '2024-10-29', '21:20:00', 40888.00, 1),
(19, 'Manila', 'MNL - ICN', '5J187', '2024-10-25', '15:40:00', '2024-10-25', '21:20:00', 'ICN - MNL', '5J188', '2024-10-30', '15:40:00', '2024-10-30', '21:20:00', 40888.00, 1),
(20, 'Manila', 'MNL - ICN', '5J187', '2024-10-28', '15:40:00', '2024-10-28', '21:20:00', 'ICN - MNL', '5J188', '2024-11-02', '15:40:00', '2024-11-02', '21:20:00', 44282.35, 1),
(21, 'Manila', 'MNL - ICN', '5J187', '2024-10-29', '15:40:00', '2024-10-29', '21:20:00', 'ICN - MNL', '5J188', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', 45332.35, 1),
(22, 'Manila', 'MNL - ICN', '5J187', '2024-10-30', '15:40:00', '2024-10-30', '21:20:00', 'ICN - MNL', '5J188', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', 40888.00, 1),
(23, 'Manila', 'MNL - ICN', '5J187', '2024-10-31', '15:40:00', '2024-10-31', '21:20:00', 'ICN - MNL', '5J188', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', 41888.00, 1),
(24, 'Manila', 'MNL - ICN', '5J187', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', 'ICN - MNL', '5J188', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 37888.00, 1),
(25, 'Manila', 'MNL - ICN', '5J187', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', 'ICN - MNL', '5J188', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 37888.00, 1),
(26, 'Manila', 'MNL - ICN', '5J187', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', 'ICN - MNL', '5J188', '2024-11-10', '15:40:00', '2024-11-10', '21:20:00', 39888.00, 1),
(27, 'Manila', 'MNL - ICN', '5J187', '2024-11-06', '15:40:00', '2024-11-06', '21:20:00', 'ICN - MNL', '5J188', '2024-11-11', '15:40:00', '2024-11-11', '21:20:00', 36888.00, 1),
(28, 'Manila', 'MNL - ICN', '5J187', '2024-11-07', '15:40:00', '2024-11-07', '21:20:00', 'ICN - MNL', '5J188', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 38888.00, 1),
(29, 'Manila', 'MNL - ICN', '5J187', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 'ICN - MNL', '5J188', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 36888.00, 1),
(30, 'Manila', 'MNL - ICN', '5J187', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 'ICN - MNL', '5J188', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 38446.54, 1),
(31, 'Manila', 'MNL - ICN', '5J187', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 'ICN - MNL', '5J188', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 36321.58, 1),
(32, 'Manila', 'MNL - ICN', '5J187', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 'ICN - MNL', '5J188', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 368982.76, 1),
(33, 'Manila', 'MNL - ICN', '5J187', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 'ICN - MNL', '5J188', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 38692.76, 1),
(34, 'Manila', 'MNL - ICN', '5J187', '2024-11-15', '15:40:00', '2024-11-15', '21:20:00', 'ICN - MNL', '5J188', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 36892.76, 1),
(35, 'Manila', 'MNL - ICN', '5J187', '2024-11-16', '15:40:00', '2024-11-16', '21:20:00', 'ICN - MNL', '5J188', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 35921.58, 1),
(36, 'Manila', 'MNL - ICN', '5J187', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 'ICN - MNL', '5J188', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 38050.27, 1),
(37, 'Manila', 'MNL - ICN', '5J187', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 'ICN - MNL', '5J188', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 38950.27, 1),
(38, 'Manila', 'MNL - ICN', '5J187', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 'ICN - MNL', '5J188', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 35976.53, 1),
(39, 'Manila', 'MNL - ICN', '5J187', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 'ICN - MNL', '5J188', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 36899.17, 1),
(40, 'Manila', 'MNL - ICN', '5J187', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 'ICN - MNL', '5J188', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 38699.17, 1),
(41, 'Manila', 'MNL - ICN', '5J187', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 'ICN - MNL', '5J188', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 36899.17, 1),
(42, 'Manila', 'MNL - ICN', '5J187', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 'ICN - MNL', '5J188', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 35576.53, 1),
(43, 'Manila', 'MNL - ICN', '5J187', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 'ICN - MNL', '5J188', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 35976.81, 1),
(44, 'Manila', 'MNL - ICN', '5J187', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 'ICN - MNL', '5J188', '2024-11-30', '15:40:00', '2024-11-30', '21:20:00', 38950.27, 1),
(45, 'Manila', 'MNL - ICN', '5J187', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 'ICN - MNL', '5J188', '2024-12-01', '15:40:00', '2024-12-01', '21:20:00', 35976.53, 1),
(46, 'Manila', 'MNL - ICN', '5J187', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 'ICN - MNL', '5J188', '2024-12-02', '15:40:00', '2024-12-02', '21:20:00', 36898.20, 1),
(47, 'Manila', 'MNL - ICN', '5J187', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 'ICN - MNL', '5J188', '2024-12-03', '15:40:00', '2024-12-03', '21:20:00', 38698.22, 1),
(48, 'Manila', 'MNL - ICN', '5J187', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 'ICN - MNL', '5J188', '2024-12-04', '15:40:00', '2024-12-04', '21:20:00', 37698.22, 1),
(49, 'Manila', 'MNL - ICN', '5J187', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', 'ICN - MNL', '5J188', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 37888.00, 2),
(50, 'Manila', 'MNL - ICN', '5J187', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', 'ICN - MNL', '5J188', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 37888.00, 2),
(51, 'Manila', 'MNL - ICN', '5J187', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', 'ICN - MNL', '5J188', '2024-11-10', '15:40:00', '2024-11-10', '21:20:00', 39888.00, 2),
(52, 'Manila', 'MNL - ICN', '5J187', '2024-11-06', '15:40:00', '2024-11-06', '21:20:00', 'ICN - MNL', '5J188', '2024-11-11', '15:40:00', '2024-11-11', '21:20:00', 36888.00, 2),
(53, 'Manila', 'MNL - ICN', '5J187', '2024-11-07', '15:40:00', '2024-11-07', '21:20:00', 'ICN - MNL', '5J188', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 38888.00, 2),
(54, 'Manila', 'MNL - ICN', '5J187', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 'ICN - MNL', '5J188', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 36888.00, 2),
(55, 'Manila', 'MNL - ICN', '5J187', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 'ICN - MNL', '5J188', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 38446.54, 2),
(56, 'Manila', 'MNL - ICN', '5J187', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 'ICN - MNL', '5J188', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 36321.58, 3),
(57, 'Manila', 'MNL - ICN', '5J187', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 'ICN - MNL', '5J188', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 368982.76, 3),
(58, 'Manila', 'MNL - ICN', '5J187', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 'ICN - MNL', '5J188', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 38692.76, 3),
(59, 'Manila', 'MNL - ICN', '5J187', '2024-11-15', '15:40:00', '2024-11-15', '21:20:00', 'ICN - MNL', '5J188', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 36892.76, 3),
(60, 'Manila', 'MNL - ICN', '5J187', '2024-11-16', '15:40:00', '2024-11-16', '21:20:00', 'ICN - MNL', '5J188', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 35921.58, 4),
(61, 'Manila', 'MNL - ICN', '5J187', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 'ICN - MNL', '5J188', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 38050.27, 4),
(62, 'Manila', 'MNL - ICN', '5J187', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 'ICN - MNL', '5J188', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 38950.27, 4),
(63, 'Manila', 'MNL - ICN', '5J187', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 'ICN - MNL', '5J188', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 35976.53, 4),
(64, 'Manila', 'MNL - ICN', '5J187', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 'ICN - MNL', '5J188', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 36899.17, 4),
(65, 'Manila', 'MNL - ICN', '5J187', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 'ICN - MNL', '5J188', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 38699.17, 5),
(66, 'Manila', 'MNL - ICN', '5J187', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 'ICN - MNL', '5J188', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 36899.17, 5),
(67, 'Manila', 'MNL - ICN', '5J187', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 'ICN - MNL', '5J188', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 35576.53, 5),
(68, 'Manila', 'MNL - ICN', '5J187', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 'ICN - MNL', '5J188', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 35976.81, 5),
(69, 'Manila', 'MNL - ICN', '5J187', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 'ICN - MNL', '5J188', '2024-11-30', '15:40:00', '2024-11-30', '21:20:00', 38950.27, 5),
(70, 'Manila', 'MNL - ICN', '5J187', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 'ICN - MNL', '5J188', '2024-12-01', '15:40:00', '2024-12-01', '21:20:00', 35976.53, 6),
(71, 'Manila', 'MNL - ICN', '5J187', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 'ICN - MNL', '5J188', '2024-12-02', '15:40:00', '2024-12-02', '21:20:00', 36898.20, 6),
(72, 'Manila', 'MNL - ICN', '5J187', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 'ICN - MNL', '5J188', '2024-12-03', '15:40:00', '2024-12-03', '21:20:00', 38698.22, 6),
(73, 'Manila', 'MNL - ICN', '5J187', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 'ICN - MNL', '5J188', '2024-12-04', '15:40:00', '2024-12-04', '21:20:00', 37698.22, 6),
(74, 'Cebu', 'CEB - ICN', '5J128', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', 'ICN - CEB', '5J129', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 37888.00, 2),
(75, 'Cebu', 'CEB - ICN', '5J128', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', 'ICN - CEB', '5J188', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 37888.00, 3),
(76, 'Cebu', 'CEB - ICN', '5J128', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', 'ICN - CEB', '5J129', '2024-11-10', '15:40:00', '2024-11-10', '21:20:00', 39888.00, 4),
(77, 'Cebu', 'CEB - ICN', '5J128', '2024-11-06', '15:40:00', '2024-11-06', '21:20:00', 'ICN - CEB', '5J129', '2024-11-11', '15:40:00', '2024-11-11', '21:20:00', 36888.00, 5),
(78, 'Cebu', 'CEB - ICN', '5J128', '2024-11-07', '15:40:00', '2024-11-07', '21:20:00', 'ICN - CEB', '5J129', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 38888.00, 6),
(79, 'Cebu', 'CEB - ICN', '5J128', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 'ICN - CEB', '5J129', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 36888.00, 2),
(80, 'Cebu', 'CEB - ICN', '5J128', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 'ICN - CEB', '5J129', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 38446.54, 3),
(81, 'Cebu', 'CEB - ICN', '5J128', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 'ICN - CEB', '5J129', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 36321.58, 4),
(82, 'Cebu', 'CEB - ICN', '5J128', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 'ICN - CEB', '5J129', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 368982.76, 5),
(83, 'Cebu', 'CEB - ICN', '5J128', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 'ICN - CEB', '5J129', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 38692.76, 6),
(84, 'Cebu', 'CEB - ICN', '5J128', '2024-11-15', '15:40:00', '2024-11-15', '21:20:00', 'ICN - CEB', '5J129', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 36892.76, 2),
(85, 'Cebu', 'CEB - ICN', '5J128', '2024-11-16', '15:40:00', '2024-11-16', '21:20:00', 'ICN - CEB', '5J129', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 35921.58, 3),
(86, 'Cebu', 'CEB - ICN', '5J128', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 'ICN - CEB', '5J129', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 38050.27, 4),
(87, 'Cebu', 'CEB - ICN', '5J128', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 'ICN - CEB', '5J129', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 38950.27, 5),
(88, 'Cebu', 'CEB - ICN', '5J128', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 'ICN - CEB', '5J129', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 35976.53, 6),
(89, 'Cebu', 'CEB - ICN', '5J128', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 'ICN - CEB', '5J129', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 36899.17, 4),
(90, 'Cebu', 'CEB - ICN', '5J128', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 'ICN - CEB', '5J129', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 38699.17, 5),
(91, 'Cebu', 'CEB - ICN', '5J128', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 'ICN - CEB', '5J129', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 36899.17, 6),
(92, 'Cebu', 'CEB - ICN', '5J128', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 'ICN - CEB', '5J129', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 35576.53, 2),
(93, 'Cebu', 'CEB - ICN', '5J128', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 'ICN - CEB', '5J129', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 35976.81, 3),
(94, 'Cebu', 'CEB - ICN', '5J128', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 'ICN - CEB', '5J129', '2024-11-30', '15:40:00', '2024-11-30', '21:20:00', 38950.27, 4),
(95, 'Cebu', 'CEB - ICN', '5J128', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 'ICN - CEB', '5J129', '2024-12-01', '15:40:00', '2024-12-01', '21:20:00', 35976.53, 5),
(96, 'Cebu', 'CEB - ICN', '5J128', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 'ICN - CEB', '5J129', '2024-12-02', '15:40:00', '2024-12-02', '21:20:00', 36898.20, 6),
(97, 'Cebu', 'CEB - ICN', '5J128', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 'ICN - CEB', '5J129', '2024-12-03', '15:40:00', '2024-12-03', '21:20:00', 38698.22, 2),
(98, 'Cebu', 'CEB - ICN', '5J128', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 'ICN - CEB', '5J129', '2024-12-04', '15:40:00', '2024-12-04', '21:20:00', 37698.22, 3);

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
(40, 'TRANS-0000035', 1, 'test20', 'test20', 'test20', 'III', '2004-06-15', 20, 'Male', 'Japanese', '09999999920', 'test20@gmail.com', 'blk 20 lot 1', 'sample', 'sample', 'sample', 'Caloocan', 'Korea', 'a0000020a', '2024-10-31', ''),
(41, 'TRANS-0000036', 3, 'test21', 'test21', 'test21', 'Sr.', '2004-06-08', 20, 'Male', 'Chinese', '09999999921', 'test21@gmail.com', 'blk 21 lot 1', 'sample', 'sample', 'sample', 'Manila', 'China', 'a0000021a', '2024-10-31', ''),
(42, 'TRANS-0000037', 4, 'test22', 'test22', 'test22', 'Sr.', '2004-06-16', 20, 'Male', 'Filipino', '09999999922', 'test22@gmail.com', 'blk 22 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Philippines', 'a0000022a', '2024-10-31', ''),
(43, 'TRANS-0000037', 4, 'test23', 'test23', 'test23', 'Sr.', '2004-02-12', 20, 'Male', 'Filipino', '09999999923', 'test23@gmail.com', 'blk 23 lot 1', 'sample', 'sample', 'sample', 'Makati', 'Philippines', 'a0000023a', '2024-10-31', ''),
(44, 'TRANS-0000038', 40, 'test24', 'test24', 'test24', 'II', '2002-06-15', 22, 'Male', 'Chinese', '09999999924', 'test24@gmail.com', 'blk 24 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Philippines', 'a0000024a', '2024-10-31', ''),
(45, 'TRANS-0000039', 12, 'test25', 'test25', 'test25', 'Sr.', '2004-02-10', 20, 'Male', 'Chinese', '09999999925', 'test25@gmail.com', 'blk 25 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'China', 'a0000025a', '2024-10-31', ''),
(46, 'TRANS-0000040', 51, 'test26', 'test26', 'test26', 'II', '2002-01-30', 22, 'Male', 'Filipino', '09999999926', 'test26@gmail.com', 'blk 26 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000026a', '2024-10-31', ''),
(47, 'TRANS-0000041', 4, 'test27', 'test27', 'test27', 'Sr.', '2004-02-09', 20, 'Male', 'Filipino', '09999999927', 'test27@gmail.com', 'blk 27 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Philippines', 'a0000027a', '2024-10-31', ''),
(48, 'TRANS-0000042', 3, 'test28', 'test28', 'test28', 'Jr.', '2004-02-04', 20, 'Male', 'Filipino', '09999999928', 'test28@gmail.com', 'blk 28 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Philippines', 'a0000028a', '2024-10-31', ''),
(49, 'TRANS-0000043', 3, 'test29', 'test29', 'test29', 'Jr.', '2004-06-02', 20, 'Male', 'Filipino', '09999999929', 'test29@gmail.com', 'blk 29 lot 1', 'sample', 'sample', 'sample', 'Muntinlupa', 'China', 'a0000029a', '2024-10-31', ''),
(50, 'TRANS-0000044', 14, 'test30', 'test30', 'test30', 'Sr.', '2004-06-16', 20, 'Male', 'Filipino', '09999999930', 'test30@gmail.com', 'blk 30 lot 1', 'sample', 'sample', 'sample', 'Manila', 'Philippines', 'a0000030a', '2024-10-31', ''),
(51, 'TRANS-0000045', 54, 'test31', 'test31', 'test31', 'Sr.', '2004-06-16', 20, 'Male', 'Filipino', '09999999931', 'test31@gmail.com', 'blk 31 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Japan', 'a0000031a', '2024-10-30', ''),
(52, 'TRANS-0000046', 3, 'test32', 'test32', 'test32', 'Jr.', '2004-06-16', 20, 'Male', 'Filipino', '09999999932', 'test32@gmail.com', 'blk 32 lot 1', 'sample', 'sample', 'sample', 'Parañaque', 'Philippines', 'a0000032a', '2024-10-31', '');

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
-- Table structure for table `inquiry`
--

CREATE TABLE `inquiry` (
  `inquiryId` int(11) NOT NULL,
  `transactNo` varchar(30) NOT NULL,
  `agentId` int(11) NOT NULL,
  `concern` enum('Additional Baggage','Additional Headcount','Additional Meal','Hotel Room','Package Only','Seat Selection','Visa') NOT NULL,
  `details` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiry`
--

INSERT INTO `inquiry` (`inquiryId`, `transactNo`, `agentId`, `concern`, `details`, `date`) VALUES
(1, 'TRANS-0000033', 1, 'Additional Baggage', 'testing', '2024-10-17 11:32:59');

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `packageId` int(11) NOT NULL,
  `packageName` varchar(50) NOT NULL,
  `packagePrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`packageId`, `packageName`, `packagePrice`) VALUES
(1, 'Autumn Tour Package', 0.00),
(2, 'Busan Tour Package', 0.00),
(3, 'Cherry Blossom Tour Package', 0.00),
(4, 'Regular Tour Package', 0.00),
(5, 'Spring Tour Package', 0.00),
(6, 'Summer Tour Package', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentId` int(11) NOT NULL,
  `transactNo` varchar(30) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paymentDate` datetime NOT NULL,
  `proof` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`inquiryId`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`packageId`),
  ADD UNIQUE KEY `packageName` (`packageName`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentId`);

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
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flightId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `guestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `hotelId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inquiry`
--
ALTER TABLE `inquiry`
  MODIFY `inquiryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `packageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
