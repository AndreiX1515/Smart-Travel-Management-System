-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2024 at 10:27 AM
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
  `branchId` int(11) DEFAULT NULL,
  `fName` varchar(50) DEFAULT NULL,
  `lName` varchar(50) DEFAULT NULL,
  `mName` varchar(50) DEFAULT NULL,
  `countryCode` varchar(10) DEFAULT NULL,
  `contactNo` varchar(20) DEFAULT NULL,
  `agentType` enum('Retailer','Wholeseller') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`id`, `agentId`, `accountId`, `branchId`, `fName`, `lName`, `mName`, `countryCode`, `contactNo`, `agentType`) VALUES
(1, 'A001', 1, 1, 'Veronica', 'Hantazo', '', '+63', '9957563947', 'Retailer'),
(2, 'A002', 2, 2, 'Amie', 'Demapindan', '', '+63', '9177149418', 'Retailer'),
(3, 'A003', 3, 3, 'Julyanna', 'Francia', '', '+63', '9778127977', 'Wholeseller'),
(4, 'A004', 4, 4, 'Jonna', '', '', '+63', '9957501306', 'Wholeseller'),
(5, 'A005', 5, 5, 'Winie', 'Hantazo', '', '+63', '9', 'Retailer');

-- --------------------------------------------------------

--
-- Table structure for table `agentflightseats`
--

CREATE TABLE `agentflightseats` (
  `flightSeatId` int(11) NOT NULL,
  `agentId` varchar(50) DEFAULT NULL,
  `flightId` int(11) DEFAULT NULL,
  `maxSeats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agentflightseats`
--

INSERT INTO `agentflightseats` (`flightSeatId`, `agentId`, `flightId`, `maxSeats`) VALUES
(1, 'A001', 71, 5);

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
(35, 2, 'A002-000035', 'A002', 71, 6, 'Charlotte', 'Rodriguez', 'Sophia', 'N/A', '+63', '9999999926', 'charlotte.rodriguez@example.com', 4, '2024-11-05 11:32:31', 147592.80, 'Cancelled'),
(36, 3, 'A003-000036', 'A003', 71, 6, 'Ethan', 'Santiago', 'Michael', 'Jr.', '+63', '9999999932', 'ethan.santiago@example.com', 2, '2024-11-05 14:10:42', 73796.40, 'Confirmed'),
(37, 4, 'A004-000037', 'A004', 71, 6, 'Olivia', 'Ramos', 'Marie', 'N/A', '+63', '9999999933', 'olivia.ramos@example.com', 3, '2024-11-05 09:15:31', 110694.60, 'Pending'),
(38, 5, 'A005-000038', 'A005', 72, 6, 'Noah', 'Fernandez', 'Gabriel', 'Sr.', '+63', '9999999934', 'noah.fernandez@example.com', 1, '2024-11-05 12:45:16', 57398.70, 'Cancelled'),
(39, 1, 'A001-000039', 'A001', 72, 6, 'Ava', 'Gutierrez', 'Anne', 'III', '+63', '9999999935', 'ava.gutierrez@example.com', 4, '2024-11-05 15:20:10', 229594.80, 'Confirmed'),
(40, 2, 'A002-000040', 'A002', 73, 6, 'William', 'Reyes', 'Carlos', 'N/A', '+63', '9999999936', 'william.reyes@example.com', 5, '2024-11-05 08:05:00', 307165.50, 'Pending'),
(41, 3, 'A003-000041', 'A003', 71, 6, 'Isabella', 'Torres', 'Mae', 'N/A', '+63', '9999999937', 'isabella.torres@example.com', 3, '2024-11-05 16:20:30', 110694.60, 'Confirmed'),
(42, 4, 'A004-000042', 'A004', 73, 6, 'James', 'Garcia', 'Lee', 'II', '+63', '9999999938', 'james.garcia@example.com', 2, '2024-11-05 13:45:19', 122866.20, 'Pending'),
(43, 5, 'A005-000043', 'A005', 72, 6, 'Sophia', 'Mendoza', 'Anne', 'IV', '+63', '9999999939', 'sophia.mendoza@example.com', 3, '2024-11-05 09:32:11', 172196.10, 'Cancelled'),
(44, 1, 'A001-000044', 'A001', 71, 6, 'Liam', 'Delacruz', 'John', 'Jr.', '+63', '9999999940', 'liam.delacruz@example.com', 4, '2024-11-05 11:47:23', 147592.80, 'Confirmed'),
(45, 2, 'A002-000045', 'A002', 73, 6, 'Amelia', 'Flores', 'Grace', 'N/A', '+63', '9999999941', 'amelia.flores@example.com', 1, '2024-11-05 14:55:41', 61433.10, 'Confirmed'),
(46, 3, 'A003-000046', 'A003', 72, 6, 'Logan', 'Martinez', 'James', 'Sr.', '+63', '9999999942', 'logan.martinez@example.com', 5, '2024-11-05 10:15:37', 286993.50, 'Confirmed'),
(47, 4, 'A004-000047', 'A004', 73, 6, 'Mia', 'Nguyen', 'Anna', 'N/A', '+63', '9999999943', 'mia.nguyen@example.com', 2, '2024-11-05 12:05:29', 122866.20, 'Cancelled'),
(48, 5, 'A005-000048', 'A005', 71, 6, 'Lucas', 'Perez', 'Gabriel', 'III', '+63', '9999999944', 'lucas.perez@example.com', 5, '2024-11-05 15:27:05', 184491.00, 'Confirmed'),
(49, 4, 'A004-000049', 'A004', 72, 6, 'Emma', 'Reyes', 'Rose', 'N/A', '+63', '9999999945', 'emma.reyes@example.com', 4, '2024-11-05 08:45:56', 229594.80, 'Pending'),
(50, 5, 'A005-000050', 'A005', 73, 6, 'Alexander', 'Ortiz', 'Jack', 'II', '+63', '9999999946', 'alexander.ortiz@example.com', 3, '2024-11-05 16:59:17', 184299.30, 'Confirmed'),
(51, 2, 'A002-000051', 'A002', 32, 1, 'test51', 'test51', 'test51', 'Jr.', '+63', '9999999951', 'testing51@gmail.com', 2, '2024-11-08 16:21:08', 73785.52, 'Pending'),
(52, 2, 'A002-000052', 'A002', NULL, 1, 'test52', 'test52', 'test52', 'Sr.', '+63', '9999999952', 'testing52@gmail.com', 2, '2024-11-08 16:25:30', 62000.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branchId` int(11) NOT NULL,
  `branchName` varchar(100) DEFAULT NULL,
  `branchLocation` varchar(100) DEFAULT NULL,
  `branchAdd` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branchId`, `branchName`, `branchLocation`, `branchAdd`) VALUES
(1, 'P91 Travel & Tours', NULL, NULL),
(2, 'APD Travel & Tours', NULL, NULL),
(3, 'FRANCIA Travel & Tours', NULL, NULL),
(4, 'Travel Escape', NULL, NULL),
(5, 'EWINER', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clientflight`
--

CREATE TABLE `clientflight` (
  `clientFlightId` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `flightName` varchar(100) DEFAULT NULL,
  `flightCode` varchar(50) DEFAULT NULL,
  `flightDepartureDate` date DEFAULT NULL,
  `flightDepartureTime` time DEFAULT NULL,
  `returnFlightName` varchar(100) DEFAULT NULL,
  `returnFlightCode` varchar(50) DEFAULT NULL,
  `returnDepartureDate` date DEFAULT NULL,
  `returnDepartureTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientflight`
--

INSERT INTO `clientflight` (`clientFlightId`, `transactNo`, `flightName`, `flightCode`, `flightDepartureDate`, `flightDepartureTime`, `returnFlightName`, `returnFlightCode`, `returnDepartureDate`, `returnDepartureTime`) VALUES
(1, 'A002-000052', 'LGP - MNL', '5J328', '2024-11-14', '16:00:00', 'MNL - LGP', '5J329', '2024-11-17', '16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `concern`
--

CREATE TABLE `concern` (
  `concernId` int(11) NOT NULL,
  `concernTitle` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `concern`
--

INSERT INTO `concern` (`concernId`, `concernTitle`) VALUES
(1, 'Additional Meal'),
(2, 'Additional Baggage'),
(3, 'Additional Headcount'),
(4, 'Hotel Room'),
(5, 'Seat Selection'),
(6, 'Visa'),
(7, 'Infant');

-- --------------------------------------------------------

--
-- Table structure for table `concerndetails`
--

CREATE TABLE `concerndetails` (
  `concernDetailsId` int(11) NOT NULL,
  `concernId` int(11) DEFAULT NULL,
  `details` varchar(100) DEFAULT NULL,
  `price` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `concerndetails`
--

INSERT INTO `concerndetails` (`concernDetailsId`, `concernId`, `details`, `price`) VALUES
(1, 1, 'Lechon Manok Burrito', 200.00),
(2, 1, 'Fish Pesto Sandwich', 200.00),
(3, 1, 'Deluxe Egg Sandwich', 200.00),
(4, 1, 'Spam Nori', 320.00),
(5, 1, 'Pinoy Spaghetti', 320.00),
(6, 1, 'Chicken Mushroom Noodles', 320.00),
(7, 1, 'Squid in Oyster Sauce', 320.00),
(8, 1, 'Char Siu Chicken', 320.00),
(9, 1, 'Beef Adobo', 320.00),
(10, 2, '20 KG One Way', 1100.00),
(11, 2, '20 KG RT', 2200.00),
(12, 2, '20KG/32KG', 3400.00),
(13, 2, '20KG/40KG', 4000.00),
(14, 2, '32 KG RT', 4600.00),
(15, 2, '40 KG RT ', 5800.00),
(16, 6, 'Single', 2000.00),
(17, 6, 'Group', 1000.00),
(18, 5, 'One Way', 1800.00),
(19, 5, 'Round Trip', 900.00),
(20, 7, 'MNL - INC', 2950.00),
(21, 7, 'ICN - MNL', 3350.00),
(22, 7, 'Round Trip', 6800.00);

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
(1, 'E001', 6, 'Elaine', 'Santoyo', NULL, 'Reservation Officer', '+63', '9055200900', 'Manila'),
(2, 'E002', 7, 'Myca', 'Mendoza', 'Agarin', 'Reservation Officer', '+63', '9168313207', 'Manila'),
(3, 'E003', 8, 'Jovelyn', 'Nofies', 'E', 'Reservation Officer', '+63', '9159150363', 'Manila'),
(4, 'E004', 9, 'Jasmin', 'Esparas', 'G', 'Reservation Officer', '+63', '9610357080', 'Manila'),
(5, 'E005', 10, 'Hyacinth', 'Datu', 'L', 'Liason Officer', '+63', '9609337401', 'Manila'),
(6, 'E006', 11, 'Lia', 'Park', NULL, 'Reservation Officer', '+82', '-10-9270-5487', 'Korea'),
(7, 'E007', 12, 'Gwen', 'Kim', NULL, 'Reservation Officer', '+82', '-10-9929-8767', 'Korea'),
(8, 'E008', 13, 'Anna', 'Lm', NULL, 'Reservation Officer', '+82', '-10-6609-6471', 'Korea'),
(9, 'E009', 14, 'Emily', 'Geong', NULL, 'Reservation Officer', NULL, NULL, 'Korea'),
(10, 'E010', 15, 'Dorothy', NULL, NULL, 'Reservation Officer', NULL, NULL, 'Korea'),
(11, 'E011', 16, 'Hanmyung', 'Lee', NULL, 'Reservation Officer', NULL, NULL, 'Korea'),
(12, 'E012', NULL, 'Vicky', 'Heo', NULL, NULL, NULL, NULL, 'Korea'),
(13, 'E013', NULL, 'James', 'Yoon', NULL, NULL, NULL, NULL, 'Korea'),
(14, 'E014', NULL, 'Jenny', 'Lee', NULL, NULL, NULL, NULL, 'Korea');

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
  `flightPrice` decimal(10,2) DEFAULT NULL,
  `availSeats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`flightId`, `packageId`, `employeeId`, `origin`, `flightName`, `flightCode`, `flightDepartureDate`, `flightDepartureTime`, `flightArrivalDate`, `flightArrivalTime`, `returnFlightName`, `returnFlightCode`, `returnDepartureDate`, `returnDepartureTime`, `returnArrivalDate`, `returnArrivalTime`, `wholesalePrice`, `flightPrice`, `availSeats`) VALUES
(1, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-03', '15:40:00', '2024-10-03', '21:20:00', 'ICN - MNL', '5J188', '2024-10-07', '22:15:00', '2024-10-08', '01:55:00', NULL, 30000.00, NULL),
(2, 3, 'E006', 'Cebu', 'CEB - ICN', '5J128', '2024-10-04', '15:40:00', '2024-10-04', '21:20:00', 'ICN - CEB', '5J129', '2024-10-08', '22:15:00', '2024-10-09', '01:55:00', NULL, 30000.00, NULL),
(3, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-02', '15:40:00', '2024-10-02', '21:20:00', 'ICN - MNL', '5J188', '2024-10-07', '15:40:00', '2024-10-07', '21:20:00', NULL, 35888.00, NULL),
(4, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-04', '15:40:00', '2024-10-04', '21:20:00', 'ICN - MNL', '5J188', '2024-10-09', '15:40:00', '2024-10-09', '21:20:00', NULL, 35888.88, NULL),
(5, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-05', '15:40:00', '2024-10-05', '21:20:00', 'ICN - MNL', '5J188', '2024-10-10', '15:40:00', '2024-10-10', '21:20:00', NULL, 36321.04, NULL),
(6, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-08', '15:40:00', '2024-10-08', '21:20:00', 'ICN - MNL', '5J188', '2024-10-13', '15:40:00', '2024-10-13', '21:20:00', NULL, 37221.04, NULL),
(7, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-09', '15:40:00', '2024-10-09', '21:20:00', 'ICN - MNL', '5J188', '2024-10-14', '15:40:00', '2024-10-14', '21:20:00', NULL, 35888.00, NULL),
(8, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-10', '15:40:00', '2024-10-10', '21:20:00', 'ICN - MNL', '5J188', '2024-10-15', '15:40:00', '2024-10-15', '21:20:00', NULL, 36888.00, NULL),
(9, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-11', '15:40:00', '2024-10-11', '21:20:00', 'ICN - MNL', '5J188', '2024-10-16', '15:40:00', '2024-10-16', '21:20:00', NULL, 35888.00, NULL),
(10, 1, 'E006', 'Manila', 'MNL - ICN', '5J187', '2024-10-16', '15:40:00', '2024-10-16', '21:20:00', 'ICN - MNL', '5J188', '2024-10-21', '15:40:00', '2024-10-21', '21:20:00', NULL, 36888.00, NULL),
(11, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-17', '15:40:00', '2024-10-17', '21:20:00', 'ICN - MNL', '5J188', '2024-10-22', '15:40:00', '2024-10-22', '21:20:00', NULL, 37888.00, NULL),
(12, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-18', '15:40:00', '2024-10-18', '21:20:00', 'ICN - MNL', '5J188', '2024-10-23', '15:40:00', '2024-10-23', '21:20:00', NULL, 36888.00, NULL),
(13, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-19', '15:40:00', '2024-10-19', '21:20:00', 'ICN - MNL', '5J188', '2024-10-24', '15:40:00', '2024-10-24', '21:20:00', NULL, 37121.04, NULL),
(14, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-20', '15:40:00', '2024-10-20', '21:20:00', 'ICN - MNL', '5J188', '2024-10-25', '15:40:00', '2024-10-25', '21:20:00', NULL, 36221.04, NULL),
(15, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-22', '15:40:00', '2024-10-22', '21:20:00', 'ICN - MNL', '5J188', '2024-10-27', '15:40:00', '2024-10-27', '21:20:00', NULL, 37888.00, NULL),
(16, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-23', '15:40:00', '2024-10-23', '21:20:00', 'ICN - MNL', '5J188', '2024-10-28', '15:40:00', '2024-10-28', '21:20:00', NULL, 37888.00, NULL),
(17, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-23', '15:40:00', '2024-10-23', '21:20:00', 'ICN - MNL', '5J188', '2024-10-28', '15:40:00', '2024-10-28', '21:20:00', NULL, 37888.00, NULL),
(18, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-24', '15:40:00', '2024-10-24', '21:20:00', 'ICN - MNL', '5J188', '2024-10-29', '15:40:00', '2024-10-29', '21:20:00', NULL, 40888.00, NULL),
(19, 1, 'E007', 'Manila', 'MNL - ICN', '5J187', '2024-10-25', '15:40:00', '2024-10-25', '21:20:00', 'ICN - MNL', '5J188', '2024-10-30', '15:40:00', '2024-10-30', '21:20:00', NULL, 40888.00, NULL),
(20, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-10-28', '15:40:00', '2024-10-28', '21:20:00', 'ICN - MNL', '5J188', '2024-11-02', '15:40:00', '2024-11-02', '21:20:00', NULL, 44282.35, NULL),
(21, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-10-29', '15:40:00', '2024-10-29', '21:20:00', 'ICN - MNL', '5J188', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', NULL, 45332.35, NULL),
(22, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-10-30', '15:40:00', '2024-10-30', '21:20:00', 'ICN - MNL', '5J188', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', NULL, 40888.00, NULL),
(23, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-10-31', '15:40:00', '2024-10-31', '21:20:00', 'ICN - MNL', '5J188', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', NULL, 41888.00, NULL),
(24, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', 'ICN - MNL', '5J188', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', NULL, 37888.00, NULL),
(25, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', 'ICN - MNL', '5J188', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', NULL, 37888.00, NULL),
(26, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', 'ICN - MNL', '5J188', '2024-11-10', '15:40:00', '2024-11-10', '21:20:00', NULL, 39888.00, NULL),
(27, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-06', '15:40:00', '2024-11-06', '21:20:00', 'ICN - MNL', '5J188', '2024-11-11', '15:40:00', '2024-11-11', '21:20:00', NULL, 36888.00, NULL),
(28, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-07', '15:40:00', '2024-11-07', '21:20:00', 'ICN - MNL', '5J188', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', NULL, 38888.00, NULL),
(29, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 'ICN - MNL', '5J188', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', NULL, 36888.00, NULL),
(30, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 'ICN - MNL', '5J188', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', NULL, 38446.54, NULL),
(31, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 'ICN - MNL', '5J188', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', NULL, 36321.58, NULL),
(32, 1, 'E008', 'Manila', 'MNL - ICN', '5J187', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 'ICN - MNL', '5J188', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', NULL, 36892.76, NULL),
(33, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 'ICN - MNL', '5J188', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', NULL, 38692.76, NULL),
(34, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-15', '15:40:00', '2024-11-15', '21:20:00', 'ICN - MNL', '5J188', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', NULL, 36892.76, NULL),
(35, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-16', '15:40:00', '2024-11-16', '21:20:00', 'ICN - MNL', '5J188', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', NULL, 35921.58, NULL),
(36, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 'ICN - MNL', '5J188', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', NULL, 38050.27, NULL),
(37, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 'ICN - MNL', '5J188', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', NULL, 38950.27, NULL),
(38, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 'ICN - MNL', '5J188', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', NULL, 35976.53, NULL),
(39, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 'ICN - MNL', '5J188', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', NULL, 36899.17, NULL),
(40, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 'ICN - MNL', '5J188', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', NULL, 38699.17, NULL),
(41, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 'ICN - MNL', '5J188', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', NULL, 36899.17, NULL),
(42, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 'ICN - MNL', '5J188', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', NULL, 35576.53, NULL),
(43, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 'ICN - MNL', '5J188', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', NULL, 35976.81, NULL),
(44, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 'ICN - MNL', '5J188', '2024-11-30', '15:40:00', '2024-11-30', '21:20:00', NULL, 38950.27, NULL),
(45, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 'ICN - MNL', '5J188', '2024-12-01', '15:40:00', '2024-12-01', '21:20:00', NULL, 35976.53, NULL),
(46, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 'ICN - MNL', '5J188', '2024-12-02', '15:40:00', '2024-12-02', '21:20:00', NULL, 36898.20, NULL),
(47, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 'ICN - MNL', '5J188', '2024-12-03', '15:40:00', '2024-12-03', '21:20:00', NULL, 38698.22, NULL),
(48, 1, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 'ICN - MNL', '5J188', '2024-12-04', '15:40:00', '2024-12-04', '21:20:00', NULL, 37698.22, NULL),
(49, 2, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', 'ICN - MNL', '5J188', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', NULL, 37888.00, NULL),
(50, 2, 'E009', 'Manila', 'MNL - ICN', '5J187', '2024-12-04', '15:40:00', '2024-11-04', '21:20:00', 'ICN - MNL', '5J188', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', NULL, 37888.00, NULL),
(51, 2, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', 'ICN - MNL', '5J188', '2024-11-10', '15:40:00', '2024-11-10', '21:20:00', NULL, 39888.00, NULL),
(52, 2, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-06', '15:40:00', '2024-11-06', '21:20:00', 'ICN - MNL', '5J188', '2024-11-11', '15:40:00', '2024-11-11', '21:20:00', NULL, 36888.00, NULL),
(53, 2, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-07', '15:40:00', '2024-11-07', '21:20:00', 'ICN - MNL', '5J188', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', NULL, 38888.00, NULL),
(54, 2, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 'ICN - MNL', '5J188', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', NULL, 36888.00, NULL),
(55, 2, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 'ICN - MNL', '5J188', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', NULL, 38446.54, NULL),
(56, 3, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 'ICN - MNL', '5J188', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', NULL, 36321.58, NULL),
(57, 3, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 'ICN - MNL', '5J188', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', NULL, 368982.76, NULL),
(58, 3, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 'ICN - MNL', '5J188', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', NULL, 38692.76, NULL),
(59, 3, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-15', '15:40:00', '2024-11-15', '21:20:00', 'ICN - MNL', '5J188', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', NULL, 36892.76, NULL),
(60, 4, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-16', '15:40:00', '2024-11-16', '21:20:00', 'ICN - MNL', '5J188', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', NULL, 35921.58, NULL),
(61, 4, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 'ICN - MNL', '5J188', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', NULL, 38050.27, NULL),
(62, 4, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 'ICN - MNL', '5J188', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', NULL, 38950.27, NULL),
(63, 4, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 'ICN - MNL', '5J188', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', NULL, 35976.53, NULL),
(64, 4, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 'ICN - MNL', '5J188', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', NULL, 36899.17, NULL),
(65, 5, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 'ICN - MNL', '5J188', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', NULL, 38699.17, NULL),
(66, 5, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 'ICN - MNL', '5J188', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', NULL, 36899.17, NULL),
(67, 5, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 'ICN - MNL', '5J188', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', NULL, 35576.53, NULL),
(68, 5, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 'ICN - MNL', '5J188', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', NULL, 35976.81, NULL),
(69, 5, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 'ICN - MNL', '5J188', '2024-11-30', '15:40:00', '2024-11-30', '21:20:00', NULL, 38950.27, NULL),
(70, 6, 'E010', 'Manila', 'MNL - ICN', '5J187', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 'ICN - MNL', '5J188', '2024-12-01', '15:40:00', '2024-12-01', '21:20:00', NULL, 35976.53, NULL),
(71, 6, 'E011', 'Manila', 'MNL - ICN', '5J187', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 'ICN - MNL', '5J188', '2024-12-02', '15:40:00', '2024-12-02', '21:20:00', NULL, 36898.20, NULL),
(72, 6, 'E011', 'Manila', 'MNL - ICN', '5J187', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 'ICN - MNL', '5J188', '2024-12-03', '15:40:00', '2024-12-03', '21:20:00', NULL, 38698.22, NULL),
(73, 6, 'E011', 'Manila', 'MNL - ICN', '5J187', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 'ICN - MNL', '5J188', '2024-12-04', '15:40:00', '2024-12-04', '21:20:00', NULL, 37698.22, NULL),
(74, 2, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-03', '15:40:00', '2024-11-03', '21:20:00', 'ICN - CEB', '5J129', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', NULL, 37888.00, NULL),
(75, 3, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-04', '15:40:00', '2024-11-04', '21:20:00', 'ICN - CEB', '5J188', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', NULL, 37888.00, NULL),
(76, 4, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-05', '15:40:00', '2024-11-05', '21:20:00', 'ICN - CEB', '5J129', '2024-11-10', '15:40:00', '2024-11-10', '21:20:00', NULL, 39888.00, NULL),
(77, 5, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-06', '15:40:00', '2024-11-06', '21:20:00', 'ICN - CEB', '5J129', '2024-11-11', '15:40:00', '2024-11-11', '21:20:00', NULL, 36888.00, NULL),
(78, 6, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-07', '15:40:00', '2024-11-07', '21:20:00', 'ICN - CEB', '5J129', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', NULL, 38888.00, NULL),
(79, 2, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-08', '15:40:00', '2024-11-08', '21:20:00', 'ICN - CEB', '5J129', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', NULL, 36888.00, NULL),
(80, 3, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-09', '15:40:00', '2024-11-09', '21:20:00', 'ICN - CEB', '5J129', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', NULL, 38446.54, NULL),
(81, 4, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-12', '15:40:00', '2024-11-12', '21:20:00', 'ICN - CEB', '5J129', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', NULL, 36321.58, NULL),
(82, 5, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-13', '15:40:00', '2024-11-13', '21:20:00', 'ICN - CEB', '5J129', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', NULL, 368982.76, NULL),
(83, 6, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-14', '15:40:00', '2024-11-14', '21:20:00', 'ICN - CEB', '5J129', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', NULL, 38692.76, NULL),
(84, 2, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-15', '15:40:00', '2024-11-15', '21:20:00', 'ICN - CEB', '5J129', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', NULL, 36892.76, NULL),
(85, 3, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-16', '15:40:00', '2024-11-16', '21:20:00', 'ICN - CEB', '5J129', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', NULL, 35921.58, NULL),
(86, 4, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-17', '15:40:00', '2024-11-17', '21:20:00', 'ICN - CEB', '5J129', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', NULL, 38050.27, NULL),
(87, 5, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-18', '15:40:00', '2024-11-18', '21:20:00', 'ICN - CEB', '5J129', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', NULL, 38950.27, NULL),
(88, 6, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-19', '15:40:00', '2024-11-19', '21:20:00', 'ICN - CEB', '5J129', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', NULL, 35976.53, NULL),
(89, 4, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-20', '15:40:00', '2024-11-20', '21:20:00', 'ICN - CEB', '5J129', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', NULL, 36899.17, NULL),
(90, 5, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-21', '15:40:00', '2024-11-21', '21:20:00', 'ICN - CEB', '5J129', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', NULL, 38699.17, NULL),
(91, 6, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-22', '15:40:00', '2024-11-22', '21:20:00', 'ICN - CEB', '5J129', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', NULL, 36899.17, NULL),
(92, 2, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-23', '15:40:00', '2024-11-23', '21:20:00', 'ICN - CEB', '5J129', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', NULL, 35576.53, NULL),
(93, 3, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-24', '15:40:00', '2024-11-24', '21:20:00', 'ICN - CEB', '5J129', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', NULL, 35976.81, NULL),
(94, 4, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-25', '15:40:00', '2024-11-25', '21:20:00', 'ICN - CEB', '5J129', '2024-11-30', '15:40:00', '2024-11-30', '21:20:00', NULL, 38950.27, NULL),
(95, 5, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-26', '15:40:00', '2024-11-26', '21:20:00', 'ICN - CEB', '5J129', '2024-12-01', '15:40:00', '2024-12-01', '21:20:00', NULL, 35976.53, NULL),
(96, 6, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-27', '15:40:00', '2024-11-27', '21:20:00', 'ICN - CEB', '5J129', '2024-12-02', '15:40:00', '2024-12-02', '21:20:00', NULL, 36898.20, NULL),
(97, 2, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-28', '15:40:00', '2024-11-28', '21:20:00', 'ICN - CEB', '5J129', '2024-12-03', '15:40:00', '2024-12-03', '21:20:00', NULL, 38698.22, NULL),
(98, 3, 'E011', 'Cebu', 'CEB - ICN', '5J128', '2024-11-29', '15:40:00', '2024-11-29', '21:20:00', 'ICN - CEB', '5J129', '2024-12-04', '15:40:00', '2024-12-04', '21:20:00', NULL, 37698.22, NULL);

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

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`guestId`, `transactNo`, `fName`, `lName`, `mName`, `suffix`, `birthdate`, `age`, `sex`, `nationality`, `countryCode`, `contactNo`, `countryCode2`, `contactNo2`, `emailAdd`, `addressLine1`, `addressLine2`, `city`, `state`, `zipCode`, `country`, `passportNo`, `passportExp`, `visaStatus`) VALUES
(3, 'A002-000045', 'test1', 'test1', 'test1', 'N/A', '2017-02-08', 7, 'Male', 'Filipino', '+63', '9999999991', NULL, NULL, 'test1@gmail.com', 'blk 1 lot 1 marosa st. naga road', NULL, 'Caloocan', 'NCR', '1742', 'Philippines', 'a0000001a', '2024-11-30', NULL),
(4, 'A002-000023', 'test2', 'test2', 'test2', 'N/A', '2015-02-11', 9, 'Male', 'Filipino', '+63', '9999999902', NULL, NULL, 'test2@gmail.com', 'blk 2 lot 1 marosa st. naga road', NULL, 'Manila', 'NCR', '1742', 'Philippines', 'a0000002a', '2024-11-30', NULL),
(5, 'A002-000023', 'test3', 'test3', 'test3', 'Sr.', '2015-02-11', 9, 'Male', 'Filipino', '+63', '9999999903', NULL, NULL, 'test3@gmail.com', 'blk 3 lot 1 marosa st. naga road', NULL, 'Parañaque', 'NCR', '1742', 'Philippines', 'a0000003a', '2024-12-01', NULL),
(6, 'A002-000052', 'testing101', 'testing101', 'testing101', 'Jr.', '2022-07-07', 2, 'Male', 'Filipino', '+63', '9999999101', NULL, NULL, 'test101@gmail.com', 'blk 101 lot 1 marosa st. naga road', 'balay uno', 'Muntinlupa', 'NCR', '1742', 'Philippines', 'a0000101a', '2024-11-30', NULL),
(7, 'A002-000052', 'testing102', 'testing102', 'testing102', 'II', '2016-06-08', 8, 'Male', 'Filipino', '+63', '9999999102', NULL, NULL, 'test102@gmail.com', 'blk 102 lot 1 marosa st. naga road', 'balay uno', 'Caloocan', 'NCR', '1742', 'Philippines', 'a0000102a', '2024-11-30', NULL);

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
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentId` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `accountId` int(11) DEFAULT NULL,
  `paymentTitle` enum('Package Payment','Request Payment') DEFAULT NULL,
  `paymentType` enum('Downpayment','Partial Payment','Full Payment') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `filePath` varchar(99) DEFAULT NULL,
  `paymentDate` datetime DEFAULT NULL,
  `paymentStatus` enum('Submitted','Approved') DEFAULT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentId`, `transactNo`, `accountId`, `paymentTitle`, `paymentType`, `amount`, `filePath`, `paymentDate`, `paymentStatus`, `details`) VALUES
(1, 'A002-000045', 2, 'Package Payment', 'Downpayment', 1000.00, '../../uploads/A002-000045-11-06-2024_10-07.png', '2024-11-06 17:07:28', 'Approved', NULL),
(2, 'A002-000052', 2, 'Package Payment', 'Downpayment', 1000.00, 'uploads\\A002-000052\\A002-000052-11-11-2024_09-48-6731627053f0c.png', '2024-11-11 09:48:32', 'Submitted', NULL),
(3, 'A002-000051', 2, 'Package Payment', 'Partial Payment', 2000.00, 'uploads\\A002-000051\\A002-000051-11-11-2024_09-50-673162e118cac.png', '2024-11-11 09:50:25', 'Submitted', NULL),
(4, 'A002-000052', 2, 'Package Payment', 'Partial Payment', 20000.00, 'uploads\\A002-000052\\A002-000052-11-14-2024_10-20-67355e799c409.png', '2024-11-14 10:20:41', 'Submitted', NULL),
(5, 'A002-000045', 2, 'Package Payment', 'Partial Payment', 50000.00, 'uploads\\A002-000045\\A002-000045-11-14-2024_15-36-6735a878913bd.png', '2024-11-14 15:36:24', 'Approved', NULL),
(6, 'A002-000052', 2, 'Package Payment', 'Partial Payment', 20000.00, 'uploads\\A002-000052\\A002-000052-11-15-2024_13-05-6736d68b92e71.png', '2024-11-15 13:05:15', 'Submitted', NULL),
(7, 'A002-000052', 2, 'Package Payment', 'Partial Payment', 1000.00, 'uploads\\A002-000052\\A002-000052-11-15-2024_13-16-6736d9448027b.png', '2024-11-15 13:16:52', 'Submitted', NULL),
(8, 'A002-000052', 2, 'Package Payment', 'Partial Payment', 500.00, 'uploads\\A002-000052\\A002-000052-11-15-2024_13-17-6736d9650be1d.png', '2024-11-15 13:17:25', 'Submitted', NULL),
(9, 'A002-000052', 2, 'Package Payment', 'Partial Payment', 250.00, 'uploads\\A002-000052\\A002-000052-11-15-2024_13-17-6736d9754b054.png', '2024-11-15 13:17:41', 'Submitted', NULL),
(10, 'A002-000052', 2, 'Package Payment', 'Partial Payment', 100.00, 'uploads\\A002-000052\\A002-000052-11-15-2024_13-18-6736d9a0df2ae.png', '2024-11-15 13:18:24', 'Submitted', NULL),
(11, 'A002-000052', 2, 'Package Payment', 'Partial Payment', 100.00, 'uploads\\A002-000052\\A002-000052-11-15-2024_13-18-6736d9b414759.png', '2024-11-15 13:18:44', 'Submitted', NULL),
(12, 'A002-000052', 2, 'Package Payment', 'Partial Payment', 100.00, 'uploads\\A002-000052\\A002-000052-11-15-2024_13-19-6736d9cca871d.png', '2024-11-15 13:19:08', 'Submitted', NULL),
(13, 'A002-000052', 2, 'Package Payment', 'Partial Payment', 100.00, 'uploads\\A002-000052\\A002-000052-11-15-2024_13-24-6736db0730ef3.png', '2024-11-15 13:24:23', 'Submitted', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `requestId` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `accountId` int(11) DEFAULT NULL,
  `concernId` int(11) DEFAULT NULL,
  `concernDetailsId` int(11) DEFAULT NULL,
  `pax` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `requestCost` decimal(10,2) DEFAULT NULL,
  `requestDate` datetime DEFAULT current_timestamp(),
  `requestStatus` enum('Pending','Confirmed','Cancelled','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`requestId`, `transactNo`, `accountId`, `concernId`, `concernDetailsId`, `pax`, `details`, `requestCost`, `requestDate`, `requestStatus`) VALUES
(1, 'A002-000045', 2, 2, 10, 2, '20 kg one way for 2', 2200.00, '2024-11-06 16:18:54', 'Confirmed'),
(2, 'A002-000045', 2, 1, 4, 2, '2', 640.00, '2024-11-06 16:19:27', 'Confirmed'),
(3, 'A002-000040', 2, 2, 11, 2, 'N/A', 4400.00, '2024-11-08 14:11:33', 'Pending'),
(4, 'A002-000045', 2, 2, 10, 1, '', 1100.00, '2024-11-14 11:31:57', 'Confirmed'),
(5, 'A002-000052', 2, 2, 10, 2, 'testing ulit', 2200.00, '2024-11-15 14:02:42', 'Pending');

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
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`session_id`, `accountid`, `login_time`, `last_activity`, `ip_address`, `user_agent`) VALUES
('ip4jdpokfkut5rls2nj5a79m09', 2, '2024-11-15 09:05:54', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'),
('iq4fpapsd9mfh7qlt2897dnb0u', 1, '2024-11-07 17:16:01', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36');

-- --------------------------------------------------------

--
-- Table structure for table `visarequirements`
--

CREATE TABLE `visarequirements` (
  `requirementId` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `agentId` varchar(50) DEFAULT NULL,
  `guestId` int(11) DEFAULT NULL,
  `passport` varchar(100) DEFAULT NULL,
  `permit` varchar(100) DEFAULT NULL,
  `validId` varchar(100) DEFAULT NULL,
  `certificate` varchar(100) DEFAULT NULL,
  `dateSubmitted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visarequirements`
--

INSERT INTO `visarequirements` (`requirementId`, `transactNo`, `agentId`, `guestId`, `passport`, `permit`, `validId`, `certificate`, `dateSubmitted`) VALUES
(1, 'A002-000023', 'A002', 4, 'uploads-visa-requirements\\A002-000023\\4\\Passport_ 4 _ 2024-11-13 - 09-42-48.pdf', 'uploads-visa-requirements\\A002-000023\\4\\Permit_ 4 _ 2024-11-13 - 09-42-48.pdf', 'uploads-visa-requirements\\A002-000023\\4\\ValidID_ 4 _ 2024-11-13 - 09-42-48.pdf', 'uploads-visa-requirements\\A002-000023\\4\\Certificate_ 4 _ 2024-11-13 - 09-42-48.pdf', '2024-11-13 16:42:48'),
(2, 'A002-000023', 'A002', 5, 'uploads-visa-requirements\\A002-000023\\5\\Passport_ 5 _ 2024-11-13 - 09-42-48.pdf', 'uploads-visa-requirements\\A002-000023\\5\\Permit_ 5 _ 2024-11-13 - 09-42-48.pdf', 'uploads-visa-requirements\\A002-000023\\5\\ValidID_ 5 _ 2024-11-13 - 09-42-48.pdf', 'uploads-visa-requirements\\A002-000023\\5\\Certificate_ 5 _ 2024-11-13 - 09-42-48.pdf', '2024-11-13 16:42:49');

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
  ADD KEY `agentId` (`agentId`),
  ADD KEY `agentBranchId` (`branchId`);

--
-- Indexes for table `agentflightseats`
--
ALTER TABLE `agentflightseats`
  ADD PRIMARY KEY (`flightSeatId`),
  ADD KEY `afFlightId` (`flightId`),
  ADD KEY `afAgentId` (`agentId`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bookingId`),
  ADD UNIQUE KEY `transactNo` (`transactNo`),
  ADD KEY `bookingAccountId` (`accountId`),
  ADD KEY `bookingFlightId` (`flightId`),
  ADD KEY `bookingAgentId` (`agentId`),
  ADD KEY `bookingPackageId` (`packageId`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branchId`);

--
-- Indexes for table `clientflight`
--
ALTER TABLE `clientflight`
  ADD PRIMARY KEY (`clientFlightId`),
  ADD KEY `cfTransactNo` (`transactNo`);

--
-- Indexes for table `concern`
--
ALTER TABLE `concern`
  ADD PRIMARY KEY (`concernId`);

--
-- Indexes for table `concerndetails`
--
ALTER TABLE `concerndetails`
  ADD PRIMARY KEY (`concernDetailsId`),
  ADD KEY `cdConcernId` (`concernId`);

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
  ADD KEY `flightEmployeeId` (`employeeId`),
  ADD KEY `flightPackageId` (`packageId`);

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
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentId`),
  ADD KEY `paymentTransactNo` (`transactNo`),
  ADD KEY `paymentAccountId` (`accountId`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`requestId`),
  ADD KEY `requestTransactNo` (`transactNo`),
  ADD KEY `requestAccountId` (`accountId`),
  ADD KEY `requestConcernId` (`concernId`),
  ADD KEY `requestConcernDetailsId` (`concernDetailsId`);

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
-- Indexes for table `visarequirements`
--
ALTER TABLE `visarequirements`
  ADD PRIMARY KEY (`requirementId`);

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
-- AUTO_INCREMENT for table `agentflightseats`
--
ALTER TABLE `agentflightseats`
  MODIFY `flightSeatId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branchId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `clientflight`
--
ALTER TABLE `clientflight`
  MODIFY `clientFlightId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `concern`
--
ALTER TABLE `concern`
  MODIFY `concernId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `concerndetails`
--
ALTER TABLE `concerndetails`
  MODIFY `concernDetailsId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flightId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `guestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `requestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `totalcost`
--
ALTER TABLE `totalcost`
  MODIFY `totalCostId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visarequirements`
--
ALTER TABLE `visarequirements`
  MODIFY `requirementId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `agentAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `agentBranchId` FOREIGN KEY (`branchId`) REFERENCES `branch` (`branchId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `agentflightseats`
--
ALTER TABLE `agentflightseats`
  ADD CONSTRAINT `afAgentId` FOREIGN KEY (`agentId`) REFERENCES `agent` (`agentId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `afFlightId` FOREIGN KEY (`flightId`) REFERENCES `flight` (`flightId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `bookingAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingAgentId` FOREIGN KEY (`agentId`) REFERENCES `agent` (`agentId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingFlightId` FOREIGN KEY (`flightId`) REFERENCES `flight` (`flightId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingPackageId` FOREIGN KEY (`packageId`) REFERENCES `package` (`packageId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `clientflight`
--
ALTER TABLE `clientflight`
  ADD CONSTRAINT `cfTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `booking` (`transactNo`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `concerndetails`
--
ALTER TABLE `concerndetails`
  ADD CONSTRAINT `cdConcernId` FOREIGN KEY (`concernId`) REFERENCES `concern` (`concernId`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  ADD CONSTRAINT `guestTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `booking` (`transactNo`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `paymentAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `paymentTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `booking` (`transactNo`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `requestAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `requestConcernDetailsId` FOREIGN KEY (`concernDetailsId`) REFERENCES `concerndetails` (`concernDetailsId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `requestConcernId` FOREIGN KEY (`concernId`) REFERENCES `concern` (`concernId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `requestTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `booking` (`transactNo`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
