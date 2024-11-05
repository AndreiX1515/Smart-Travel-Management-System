-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2024 at 07:27 AM
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
  `accountId` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` int(11) DEFAULT NULL,
  `accountStatus` enum('active','inactive') NOT NULL,
  `accountType` enum('admin','agent','employee','guest') NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`accountId`, `email`, `password`, `otp`, `accountStatus`, `accountType`, `createdAt`) VALUES
(1, 'agent1@gmail.com', 'password1', 304553, 'active', 'agent', '2024-10-30 00:00:00'),
(2, 'agent2@gmail.com', 'password2', 987654, 'active', 'agent', '2024-10-30 00:00:00'),
(3, 'agent3@gmail.com', 'password3', 123456, 'active', 'agent', '2024-10-30 00:00:00'),
(4, 'agent4@gmail.com', 'password4', 654321, 'active', 'agent', '2024-10-30 00:00:00'),
(5, 'agent5@gmail.com', 'password5', 789012, 'active', 'agent', '2024-10-30 00:00:00'),
(6, 'employee1@gmail.com', 'password6', 345678, 'active', 'employee', '2024-10-30 00:00:00'),
(7, 'employee2@gmail.com', 'password7', 567890, 'active', 'employee', '2024-10-30 00:00:00'),
(8, 'employee3@gmail.com', 'password8', 901234, 'active', 'employee', '2024-10-30 00:00:00'),
(9, 'employee4@gmail.com', 'password9', 246810, 'active', 'employee', '2024-10-30 00:00:00'),
(10, 'employee5@gmail.com', 'password10', 135790, 'active', 'employee', '2024-10-30 00:00:00'),
(11, 'employee6@gmail.com', 'password11', 864209, 'active', 'employee', '2024-10-30 00:00:00'),
(12, 'employee7@gmail.com', 'password12', 975312, 'active', 'employee', '2024-10-30 00:00:00'),
(13, 'employee8@gmail.com', 'password13', 108642, 'active', 'employee', '2024-10-30 00:00:00'),
(14, 'employee9@gmail.com', 'password14', 246135, 'active', 'employee', '2024-10-30 00:00:00'),
(15, 'employee10@gmail.com', 'password15', 369258, 'active', 'employee', '2024-10-30 00:00:00'),
(16, 'employee11@gmail.com', 'password16', 741852, 'active', 'employee', '2024-10-30 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `id` int(11) NOT NULL,
  `agentId` varchar(50) NOT NULL,
  `accountId` int(11) DEFAULT NULL,
  `fName` varchar(50) DEFAULT NULL,
  `lName` varchar(50) DEFAULT NULL,
  `mName` varchar(50) DEFAULT NULL,
  `countryCode` varchar(10) DEFAULT NULL,
  `contactNo` varchar(20) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `agentType` enum('Retailer','Wholeseller') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`id`, `agentId`, `accountId`, `fName`, `lName`, `mName`, `countryCode`, `contactNo`, `branch`, `agentType`) VALUES
(1, 'A001', 1, 'Veronica', 'Hantazo', '', '+63', '9957563947', 'P91 Travel & Tours', 'Retailer'),
(2, 'A002', 2, 'Amie', 'Demapindan', '', '+63', '9177149418', 'APD Travel & Tours', 'Retailer'),
(3, 'A003', 3, 'Julyanna', 'Francia', '', '+63', '9778127977', 'FRANCIA Travel & Tours', 'Wholeseller'),
(4, 'A004', 4, 'Jonna', '', '', '+63', '9957501306', 'Travel Escape', 'Wholeseller'),
(5, 'A005', 5, 'Winie', 'Hantazo', '', '+63', '9', 'EWINER', 'Retailer');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `bookingId` int(11) NOT NULL,
  `accountId` int(11) DEFAULT NULL,
  `transactNo` varchar(50) NOT NULL,
  `agentId` varchar(50) DEFAULT NULL,
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
  `bookingDate` datetime DEFAULT current_timestamp(),
  `totalPrice` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Cancelled','Confirmed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`bookingId`, `accountId`, `transactNo`, `agentId`, `flightId`, `packageId`, `fName`, `lName`, `mName`, `suffix`, `countryCode`, `contactNo`, `email`, `pax`, `bookingDate`, `totalPrice`, `status`) VALUES
(1, 1, 'A001-000001', 'A001', 1, 1, 'sample', 'sample', 'sample', 'N/A', '+63', '9999999991', 'sample@gmail.com', 3, '2024-11-04 17:19:20', 90000.00, 'Pending'),
(11, 1, 'A001-000002', 'A001', 58, 3, 'John', 'Doe', 'Michael', 'N/A', '+63', '9999999992', 'john.doe@example.com', 2, '2024-11-05 10:05:15', 77385.52, 'Pending'),
(12, 2, 'A002-000003', 'A002', 58, 3, 'Jane', 'Smith', 'Emily', 'Sr.', '+63', '9999999993', 'jane.smith@example.com', 4, '2024-11-05 10:05:15', 154771.04, 'Pending'),
(13, 3, 'A003-000004', 'A003', 60, 4, 'Alice', 'Johnson', 'Marie', 'II', '+63', '9999999994', 'alice.johnson@example.com', 3, '2024-11-05 10:05:15', 107764.74, 'Pending'),
(14, 4, 'A004-000005', 'A004', 54, 2, 'Bob', 'Williams', 'James', 'N/A', '+63', '9999999995', 'bob.williams@example.com', 5, '2024-11-05 10:05:15', 184440.00, 'Pending'),
(15, 5, 'A005-000006', 'A005', 60, 4, 'Charlie', 'Brown', 'Alex', 'III', '+63', '9999999996', 'charlie.brown@example.com', 2, '2024-11-05 10:05:15', 71843.16, 'Pending'),
(16, 1, 'A001-000007', 'A001', 65, 5, 'Diana', 'Prince', 'Megan', 'IV', '+63', '9999999997', 'diana.prince@example.com', 3, '2024-11-05 10:05:15', 116097.51, 'Pending'),
(17, 2, 'A002-000008', 'A002', 54, 2, 'Ethan', 'Hunt', 'Lucas', 'N/A', '+63', '9999999998', 'ethan.hunt@example.com', 1, '2024-11-05 10:05:15', 36888.00, 'Cancelled'),
(18, 3, 'A003-000009', 'A003', 48, 1, 'Fiona', 'Green', 'Sophia', 'V', '+63', '9999999999', 'fiona.green@example.com', 4, '2024-11-05 10:05:15', 150792.88, 'Pending'),
(19, 4, 'A004-000010', 'A004', 66, 5, 'George', 'Miller', 'Jack', 'N/A', '+63', '9999999910', 'george.miller@example.com', 3, '2024-11-05 10:05:15', 110697.51, 'Pending'),
(20, 1, 'A001-000020', 'A001', 49, 2, 'test1', 'test1', 'test1', 'Jr.', '+63', '9999999911', 'testing1@gmail.com', 3, '2024-11-05 10:57:02', 113664.00, 'Pending'),
(21, 2, 'A002-000021', 'A002', 34, 1, 'test2', 'test2', 'test2', 'Jr.', '+63', '9999999912', 'testing2@gmail.com', 5, '2024-11-05 10:59:04', 184463.80, 'Confirmed'),
(22, 2, 'A002-000022', 'A002', 34, 1, 'test3', 'test3', 'test3', 'II', '+63', '9999999913', 'testing3@gmail.com', 3, '2024-11-05 11:02:01', 110678.28, 'Confirmed'),
(23, 2, 'A002-000023', 'A002', 52, 2, 'Emily', 'Thompson', 'Ava', 'Sr.', '+63', '9999999914', 'emily.thompson@example.com', 2, '2024-11-05 11:32:31', 73776.00, 'Pending'),
(24, 1, 'A001-000024', 'A001', 51, 2, 'Henry', 'Carter', 'James', 'N/A', '+63', '9999999915', 'henry.carter@example.com', 4, '2024-11-05 11:32:31', 159552.00, 'Confirmed'),
(25, 3, 'A003-000025', 'A003', 61, 4, 'Isabella', 'Wright', 'Ella', 'N/A', '+63', '9999999916', 'isabella.wright@example.com', 5, '2024-11-05 11:32:31', 190251.35, 'Cancelled'),
(26, 1, 'A002-000026', 'A002', 62, 4, 'Liam', 'Scott', 'Noah', 'Jr.', '+63', '9999999917', 'liam.scott@example.com', 1, '2024-11-05 11:32:31', 38950.27, 'Pending'),
(27, 4, 'A004-000027', 'A004', 63, 4, 'Mia', 'Lopez', 'Sophia', 'II', '+63', '9999999918', 'mia.lopez@example.com', 3, '2024-11-05 11:32:31', 107929.59, 'Confirmed'),
(28, 1, 'A001-000028', 'A001', 64, 4, 'Noah', 'Martinez', 'Lucas', 'N/A', '+63', '9999999919', 'noah.martinez@example.com', 2, '2024-11-05 11:32:31', 73798.34, 'Cancelled'),
(29, 3, 'A003-000029', 'A003', 65, 5, 'Olivia', 'Garcia', 'Emily', 'Sr.', '+63', '9999999920', 'olivia.garcia@example.com', 4, '2024-11-05 11:32:31', 154796.68, 'Pending'),
(30, 2, 'A002-000030', 'A002', 66, 5, 'Ethan', 'Hall', 'Ava', 'Jr.', '+63', '9999999921', 'ethan.hall@example.com', 1, '2024-11-05 11:32:31', 36899.17, 'Confirmed'),
(31, 1, 'A001-000031', 'A001', 67, 5, 'Sophia', 'King', 'N/A', 'N/A', '+63', '9999999922', 'sophia.king@example.com', 2, '2024-11-05 11:32:31', 71153.06, 'Pending'),
(32, 2, 'A002-000032', 'A002', 68, 5, 'Jackson', 'Harris', 'Jackson', 'N/A', '+63', '9999999923', 'jackson.harris@example.com', 5, '2024-11-05 11:32:31', 179884.05, 'Cancelled'),
(33, 3, 'A003-000033', 'A003', 69, 5, 'Ava', 'Robinson', 'Ava', 'II', '+63', '9999999924', 'ava.robinson@example.com', 3, '2024-11-05 11:32:31', 116850.81, 'Pending'),
(34, 4, 'A004-000034', 'A004', 70, 6, 'Lucas', 'Clark', 'Noah', 'Sr.', '+63', '9999999925', 'lucas.clark@example.com', 2, '2024-11-05 11:32:31', 71953.06, 'Confirmed'),
(35, 2, 'A002-000035', 'A002', 71, 6, 'Charlotte', 'Rodriguez', 'Sophia', 'N/A', '+63', '9999999926', 'charlotte.rodriguez@example.com', 4, '2024-11-05 11:32:31', 147592.80, 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `employeeId` varchar(50) NOT NULL,
  `accountId` int(11) DEFAULT NULL,
  `fName` varchar(50) DEFAULT NULL,
  `lName` varchar(50) DEFAULT NULL,
  `mName` varchar(50) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `countryCode` varchar(10) DEFAULT NULL,
  `contactNo` varchar(20) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `employeeId`, `accountId`, `fName`, `lName`, `mName`, `position`, `countryCode`, `contactNo`, `branch`) VALUES
(1, 'E001', 6, 'Elaine', 'Santoyo', '', 'Reservation Officer', '+63', '9055200900', 'Manila'),
(2, 'E002', 7, 'Myca', 'Mendoza', 'Agarin', 'Reservation Officer', '+63', '9168313207', 'Manila'),
(3, 'E003', 8, 'Jovelyn', 'Nofies', 'E', 'Reservation Officer', '+63', '9159150363', 'Manila'),
(4, 'E004', 9, 'Jasmin', 'Esparas', 'G', 'Reservation Officer', '+63', '9610357080', 'Manila'),
(5, 'E005', 10, 'Hyacinth', 'Datu', 'L', 'Liason Officer', '+63', '9609337401', 'Manila'),
(6, 'E006', 11, 'Lia', 'Park', '', 'Reservation Officer', '+82', '-10-9270-5487', 'Korea'),
(7, 'E007', 12, 'Gwen', 'Kim', '', 'Reservation Officer', '+82', '-10-9929-8767', 'Korea'),
(8, 'E008', 13, 'Anna', 'Im', '', 'Reservation Officer', '+82', '-10-6609-6471', 'Korea'),
(9, 'E009', 14, 'Emily', '', '', 'Reservation Officer', '', '', 'Korea'),
(10, 'E010', 15, 'Dorothy', '', '', 'Reservation Officer', '', '', 'Korea'),
(11, 'E011', 16, 'Hanmyung', 'Lee', '', 'Reservation Officer', '', '', 'Korea');

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `flightId` int(11) NOT NULL,
  `packageId` int(11) DEFAULT NULL,
  `employeeId` varchar(50) DEFAULT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `flightName` varchar(100) DEFAULT NULL,
  `flightCode` varchar(50) DEFAULT NULL,
  `flightDepartureDate` date DEFAULT NULL,
  `flightDepartureTime` time DEFAULT NULL,
  `flightArrivalDate` date DEFAULT NULL,
  `flightArrivalTime` time DEFAULT NULL,
  `returnFlightName` varchar(100) DEFAULT NULL,
  `returnFlightCode` varchar(50) DEFAULT NULL,
  `returnDepartureDate` date DEFAULT NULL,
  `returnDepartureTime` time DEFAULT NULL,
  `returnArrivalDate` date DEFAULT NULL,
  `returnArrivalTime` time DEFAULT NULL,
  `wholesalePrice` decimal(10,2) DEFAULT NULL,
  `flightPrice` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`flightId`, `packageId`, `employeeId`, `origin`, `flightName`, `flightCode`, `flightDepartureDate`, `flightDepartureTime`, `flightArrivalDate`, `flightArrivalTime`, `returnFlightName`, `returnFlightCode`, `returnDepartureDate`, `returnDepartureTime`, `returnArrivalDate`, `returnArrivalTime`, `wholesalePrice`, `flightPrice`) VALUES
(1, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-03', '15:40:00', '2024-10-03', '21:20:00', 'ICN - MNL', '5J188', '2024-10-07', '22:15:00', '2024-10-08', '01:55:00', NULL, 30000.00),
(2, 3, 'E006', 'Cebu', 'CEB - ICN', '5J128', '2024-10-04', '15:40:00', '2024-10-04', '21:20:00', 'ICN - CEB', '5J129', '2024-10-08', '22:15:00', '2024-10-09', '01:55:00', NULL, 30000.00),
(3, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-02', '15:40:00', '2024-10-02', '21:20:00', 'ICN - MNL', '5J188', '2024-10-07', '15:40:00', '2024-10-07', '21:20:00', NULL, 35888.00),
(4, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-04', '15:40:00', '2024-10-04', '21:20:00', 'ICN - MNL', '5J188', '2024-10-09', '15:40:00', '2024-10-09', '21:20:00', NULL, 35888.88),
(5, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-05', '15:40:00', '2024-10-05', '21:20:00', 'ICN - MNL', '5J188', '2024-10-10', '15:40:00', '2024-10-10', '21:20:00', NULL, 36321.04),
(6, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-08', '15:40:00', '2024-10-08', '21:20:00', 'ICN - MNL', '5J188', '2024-10-13', '15:40:00', '2024-10-13', '21:20:00', NULL, 37221.04),
(7, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-09', '15:40:00', '2024-10-09', '21:20:00', 'ICN - MNL', '5J188', '2024-10-14', '15:40:00', '2024-10-14', '21:20:00', NULL, 35888.00),
(8, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-10', '15:40:00', '2024-10-10', '21:20:00', 'ICN - MNL', '5J188', '2024-10-15', '15:40:00', '2024-10-15', '21:20:00', NULL, 36888.00),
(9, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-11', '15:40:00', '2024-10-11', '21:20:00', 'ICN - MNL', '5J188', '2024-10-16', '15:40:00', '2024-10-16', '21:20:00', NULL, 35888.00),
(10, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-16', '15:40:00', '2024-10-16', '21:20:00', 'ICN - MNL', '5J188', '2024-10-21', '15:40:00', '2024-10-21', '21:20:00', NULL, 36888.00),
(11, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-17', '15:40:00', '2024-10-17', '21:20:00', 'ICN - MNL', '5J188', '2024-10-22', '15:40:00', '2024-10-22', '21:20:00', NULL, 37888.00),
(12, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-18', '15:40:00', '2024-10-18', '21:20:00', 'ICN - MNL', '5J188', '2024-10-23', '15:40:00', '2024-10-23', '21:20:00', NULL, 36888.00),
(13, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-19', '15:40:00', '2024-10-19', '21:20:00', 'ICN - MNL', '5J188', '2024-10-24', '15:40:00', '2024-10-24', '21:20:00', NULL, 37121.04),
(14, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-20', '15:40:00', '2024-10-20', '21:20:00', 'ICN - MNL', '5J188', '2024-10-25', '15:40:00', '2024-10-25', '21:20:00', NULL, 36221.04),
(15, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-22', '15:40:00', '2024-10-22', '21:20:00', 'ICN - MNL', '5J188', '2024-10-27', '15:40:00', '2024-10-27', '21:20:00', NULL, 37888.00),
(16, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-23', '15:40:00', '2024-10-23', '21:20:00', 'ICN - MNL', '5J188', '2024-10-28', '15:40:00', '2024-10-28', '21:20:00', NULL, 37888.00),
(17, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-23', '15:40:00', '2024-10-23', '21:20:00', 'ICN - MNL', '5J188', '2024-10-28', '15:40:00', '2024-10-28', '21:20:00', NULL, 37888.00),
(18, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-24', '15:40:00', '2024-10-24', '21:20:00', 'ICN - MNL', '5J188', '2024-10-29', '15:40:00', '2024-10-29', '21:20:00', NULL, 40888.00),
(19, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-25', '15:40:00', '2024-10-25', '21:20:00', 'ICN - MNL', '5J188', '2024-10-30', '15:40:00', '2024-10-30', '21:20:00', NULL, 40888.00),
(20, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-10-28', '15:40:00', '2024-10-28', '21:20:00', 'ICN - MNL', '5J188', '2024-11-02', '15:40:00', '2024-11-02', '21:20:00', NULL, 44282.35),
(21, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-10-29', '15:40:00', '2024-10-29', '21:20:00', 'ICN - MNL', '5J188', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', NULL, 45332.35),
(22, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-10-30', '15:40:00', '2024-10-30', '21:20:00', 'ICN - MNL', '5J188', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', NULL, 40888.00),
(23, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-10-31', '15:40:00', '2024-10-31', '21:20:00', 'ICN - MNL', '5J188', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', NULL, 41888.00),
(24, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', 'ICN - MNL', '5J188', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', NULL, 37888.00),
(25, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', 'ICN - MNL', '5J188', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', NULL, 37888.00),
(26, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', 'ICN - MNL', '5J188', '2024-11-10', '15:40:00', '2024-11-10', '21:20:00', NULL, 39888.00),
(27, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-06', '15:40:00', '2024-11-06', '21:20:00', 'ICN - MNL', '5J188', '2024-11-11', '15:40:00', '2024-11-11', '21:20:00', NULL, 36888.00),
(28, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-07', '15:40:00', '2024-11-07', '21:20:00', 'ICN - MNL', '5J188', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', NULL, 38888.00),
(29, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 'ICN - MNL', '5J188', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', NULL, 36888.00),
(30, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 'ICN - MNL', '5J188', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', NULL, 38446.54),
(31, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 'ICN - MNL', '5J188', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', NULL, 36321.58),
(32, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 'ICN - MNL', '5J188', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', NULL, 36892.76),
(33, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 'ICN - MNL', '5J188', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', NULL, 38692.76),
(34, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-15', '15:40:00', '2024-11-15', '21:20:00', 'ICN - MNL', '5J188', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', NULL, 36892.76),
(35, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-16', '15:40:00', '2024-11-16', '21:20:00', 'ICN - MNL', '5J188', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', NULL, 35921.58),
(36, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 'ICN - MNL', '5J188', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', NULL, 38050.27),
(37, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 'ICN - MNL', '5J188', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', NULL, 38950.27),
(38, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 'ICN - MNL', '5J188', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', NULL, 35976.53),
(39, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 'ICN - MNL', '5J188', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', NULL, 36899.17),
(40, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 'ICN - MNL', '5J188', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', NULL, 38699.17),
(41, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 'ICN - MNL', '5J188', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', NULL, 36899.17),
(42, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 'ICN - MNL', '5J188', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', NULL, 35576.53),
(43, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 'ICN - MNL', '5J188', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', NULL, 35976.81),
(44, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 'ICN - MNL', '5J188', '2024-11-30', '15:40:00', '2024-11-30', '21:20:00', NULL, 38950.27),
(45, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 'ICN - MNL', '5J188', '2024-12-01', '15:40:00', '2024-12-01', '21:20:00', NULL, 35976.53),
(46, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 'ICN - MNL', '5J188', '2024-12-02', '15:40:00', '2024-12-02', '21:20:00', NULL, 36898.20),
(47, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 'ICN - MNL', '5J188', '2024-12-03', '15:40:00', '2024-12-03', '21:20:00', NULL, 38698.22),
(48, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 'ICN - MNL', '5J188', '2024-12-04', '15:40:00', '2024-12-04', '21:20:00', NULL, 37698.22),
(49, 2, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', 'ICN - MNL', '5J188', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', NULL, 37888.00),
(50, 2, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', 'ICN - MNL', '5J188', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', NULL, 37888.00),
(51, 2, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', 'ICN - MNL', '5J188', '2024-11-10', '15:40:00', '2024-11-10', '21:20:00', NULL, 39888.00),
(52, 2, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-06', '15:40:00', '2024-11-06', '21:20:00', 'ICN - MNL', '5J188', '2024-11-11', '15:40:00', '2024-11-11', '21:20:00', NULL, 36888.00),
(53, 2, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-07', '15:40:00', '2024-11-07', '21:20:00', 'ICN - MNL', '5J188', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', NULL, 38888.00),
(54, 2, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 'ICN - MNL', '5J188', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', NULL, 36888.00),
(55, 2, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 'ICN - MNL', '5J188', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', NULL, 38446.54),
(56, 3, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 'ICN - MNL', '5J188', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', NULL, 36321.58),
(57, 3, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 'ICN - MNL', '5J188', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', NULL, 368982.76),
(58, 3, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 'ICN - MNL', '5J188', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', NULL, 38692.76),
(59, 3, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-15', '15:40:00', '2024-11-15', '21:20:00', 'ICN - MNL', '5J188', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', NULL, 36892.76),
(60, 4, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-16', '15:40:00', '2024-11-16', '21:20:00', 'ICN - MNL', '5J188', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', NULL, 35921.58),
(61, 4, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 'ICN - MNL', '5J188', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', NULL, 38050.27),
(62, 4, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 'ICN - MNL', '5J188', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', NULL, 38950.27),
(63, 4, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 'ICN - MNL', '5J188', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', NULL, 35976.53),
(64, 4, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 'ICN - MNL', '5J188', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', NULL, 36899.17),
(65, 5, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 'ICN - MNL', '5J188', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', NULL, 38699.17),
(66, 5, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 'ICN - MNL', '5J188', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', NULL, 36899.17),
(67, 5, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 'ICN - MNL', '5J188', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', NULL, 35576.53),
(68, 5, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 'ICN - MNL', '5J188', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', NULL, 35976.81),
(69, 5, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 'ICN - MNL', '5J188', '2024-11-30', '15:40:00', '2024-11-30', '21:20:00', NULL, 38950.27),
(70, 6, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 'ICN - MNL', '5J188', '2024-12-01', '15:40:00', '2024-12-01', '21:20:00', NULL, 35976.53),
(71, 6, 'E011', 'Manila', 'MNL - ICN', '5J187', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 'ICN - MNL', '5J188', '2024-12-02', '15:40:00', '2024-12-02', '21:20:00', NULL, 36898.20),
(72, 6, 'E011', 'Manila', 'MNL - ICN', '5J187', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 'ICN - MNL', '5J188', '2024-12-03', '15:40:00', '2024-12-03', '21:20:00', NULL, 38698.22),
(73, 6, 'E011', 'Manila', 'MNL - ICN', '5J187', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 'ICN - MNL', '5J188', '2024-12-04', '15:40:00', '2024-12-04', '21:20:00', NULL, 37698.22),
(74, 2, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', 'ICN - CEB', '5J129', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', NULL, 37888.00),
(75, 3, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', 'ICN - CEB', '5J188', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', NULL, 37888.00),
(76, 4, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', 'ICN - CEB', '5J129', '2024-11-10', '15:40:00', '2024-11-10', '21:20:00', NULL, 39888.00),
(77, 5, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-06', '15:40:00', '2024-11-06', '21:20:00', 'ICN - CEB', '5J129', '2024-11-11', '15:40:00', '2024-11-11', '21:20:00', NULL, 36888.00),
(78, 6, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-07', '15:40:00', '2024-11-07', '21:20:00', 'ICN - CEB', '5J129', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', NULL, 38888.00),
(79, 2, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 'ICN - CEB', '5J129', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', NULL, 36888.00),
(80, 3, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 'ICN - CEB', '5J129', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', NULL, 38446.54),
(81, 4, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 'ICN - CEB', '5J129', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', NULL, 36321.58),
(82, 5, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 'ICN - CEB', '5J129', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', NULL, 368982.76),
(83, 6, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 'ICN - CEB', '5J129', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', NULL, 38692.76),
(84, 2, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-15', '15:40:00', '2024-11-15', '21:20:00', 'ICN - CEB', '5J129', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', NULL, 36892.76),
(85, 3, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-16', '15:40:00', '2024-11-16', '21:20:00', 'ICN - CEB', '5J129', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', NULL, 35921.58),
(86, 4, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 'ICN - CEB', '5J129', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', NULL, 38050.27),
(87, 5, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 'ICN - CEB', '5J129', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', NULL, 38950.27),
(88, 6, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 'ICN - CEB', '5J129', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', NULL, 35976.53),
(89, 4, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 'ICN - CEB', '5J129', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', NULL, 36899.17),
(90, 5, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 'ICN - CEB', '5J129', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', NULL, 38699.17),
(91, 6, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 'ICN - CEB', '5J129', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', NULL, 36899.17),
(92, 2, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 'ICN - CEB', '5J129', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', NULL, 35576.53),
(93, 3, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 'ICN - CEB', '5J129', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', NULL, 35976.81),
(94, 4, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 'ICN - CEB', '5J129', '2024-11-30', '15:40:00', '2024-11-30', '21:20:00', NULL, 38950.27),
(95, 5, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 'ICN - CEB', '5J129', '2024-12-01', '15:40:00', '2024-12-01', '21:20:00', NULL, 35976.53),
(96, 6, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 'ICN - CEB', '5J129', '2024-12-02', '15:40:00', '2024-12-02', '21:20:00', NULL, 36898.20),
(97, 2, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 'ICN - CEB', '5J129', '2024-12-03', '15:40:00', '2024-12-03', '21:20:00', NULL, 38698.22),
(98, 3, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 'ICN - CEB', '5J129', '2024-12-04', '15:40:00', '2024-12-04', '21:20:00', NULL, 37698.22);

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
  `passportExp` date DEFAULT NULL,
  `visaStatus` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `hotelId` int(11) NOT NULL,
  `hotelName` varchar(100) DEFAULT NULL,
  `hotelFullName` text DEFAULT NULL,
  `hotelAdd` text DEFAULT NULL,
  `hotelContactNo` varchar(20) DEFAULT NULL,
  `hotelEmailAdd` varchar(255) DEFAULT NULL,
  `hotelDescr` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`hotelId`, `hotelName`, `hotelFullName`, `hotelAdd`, `hotelContactNo`, `hotelEmailAdd`, `hotelDescr`) VALUES
(1, 'Airsky Hotel Incheon', 'Airsky Hotel Incheon', NULL, NULL, NULL, NULL),
(2, 'Blue Ocean', 'Blue Ocean Residence Hotel', NULL, NULL, NULL, NULL),
(3, 'Centum Hotel', 'Centum Mark Hotel Yangyang', NULL, NULL, NULL, NULL),
(4, 'Gangwon Area Hotel', NULL, NULL, NULL, NULL, NULL),
(5, 'Ibis Insadong Hotel', 'Ibis Ambassador Seoul Insadong Hotel', NULL, NULL, NULL, NULL),
(6, 'Marinabay Hotel Seoul', 'Hotel Marinabay Seoul', NULL, NULL, NULL, NULL),
(7, 'Ramada Encore Hotel', 'Ramada Encore by Wyndham Gimpo Han River Hotel', NULL, NULL, NULL, NULL),
(8, 'Recenz Hotel', 'The Recenz Dongdaemun Hotel', NULL, NULL, NULL, NULL),
(9, 'Royal Emporium Hotel', 'Royal Emporium Hotel', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `packageId` int(11) NOT NULL,
  `packageName` varchar(100) DEFAULT NULL,
  `packagePrice` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`packageId`, `packageName`, `packagePrice`) VALUES
(1, 'Autumn Tour Package', 31000.00),
(2, 'Busan Tour Package', 32000.00),
(3, 'Cherry Blossom Tour Package', 33000.00),
(4, 'Regular Tour Package', 34000.00),
(5, 'Spring Tour Package', 35000.00),
(6, 'Summer Tour Package', 36000.00);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `requestId` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `agentId` varchar(50) DEFAULT NULL,
  `concern` enum('Additional Baggage','Additional Headcount','Additional Meal','Hotel Room','Seat Selection','Visa') NOT NULL,
  `details` text DEFAULT NULL,
  `requestCost` decimal(10,2) DEFAULT NULL,
  `requestDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `totalcost`
--

CREATE TABLE `totalcost` (
  `totalCostId` int(11) NOT NULL,
  `transactionNo` varchar(50) DEFAULT NULL,
  `packagePrice` decimal(10,2) DEFAULT NULL,
  `requestCost` decimal(10,2) DEFAULT NULL,
  `totalCost` decimal(10,2) GENERATED ALWAYS AS (`packagePrice` + `requestCost`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `session_id` varchar(100) NOT NULL,
  `accountid` int(11) DEFAULT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `last_activity` datetime DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountId`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `accountId` (`accountId`);

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`agentId`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `agentAccountId` (`accountId`),
  ADD KEY `agentId` (`agentId`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bookingId`),
  ADD UNIQUE KEY `transactNo` (`transactNo`),
  ADD KEY `bookingAgentId` (`agentId`),
  ADD KEY `bookingFlightId` (`flightId`),
  ADD KEY `bookingPackageId` (`packageId`),
  ADD KEY `bookingAccountId` (`accountId`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employeeId`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `employeeAccountId` (`accountId`);

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`flightId`),
  ADD KEY `flightPackageId` (`packageId`),
  ADD KEY `flightEmployeeId` (`employeeId`);

--
-- Indexes for table `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`guestId`),
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
  ADD UNIQUE KEY `packageName` (`packageName`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`requestId`),
  ADD KEY `requestTransactNo` (`transactNo`),
  ADD KEY `requestAgentId` (`agentId`);

--
-- Indexes for table `totalcost`
--
ALTER TABLE `totalcost`
  ADD PRIMARY KEY (`totalCostId`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accountId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flightId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `guestId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `hotelId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `packageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `requestId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `totalcost`
--
ALTER TABLE `totalcost`
  MODIFY `totalCostId` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `agentAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `bookingAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingAgentId` FOREIGN KEY (`agentId`) REFERENCES `agent` (`agentId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingFlightId` FOREIGN KEY (`flightId`) REFERENCES `flight` (`flightId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingPackageId` FOREIGN KEY (`packageId`) REFERENCES `package` (`packageId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employeeAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `flight`
--
ALTER TABLE `flight`
  ADD CONSTRAINT `flightEmployeeId` FOREIGN KEY (`employeeId`) REFERENCES `employee` (`employeeId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `flightPackageId` FOREIGN KEY (`packageId`) REFERENCES `package` (`packageId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `guest`
--
ALTER TABLE `guest`
  ADD CONSTRAINT `guestTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `booking` (`transactNo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
