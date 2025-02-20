-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2025 at 04:05 AM
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


-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `id` int(11) NOT NULL,
  `agentId` varchar(50) NOT NULL,
  `agentCode` varchar(50) DEFAULT NULL,
  `accountId` int(11) DEFAULT NULL,
  `branchId` int(11) DEFAULT NULL,
  `fName` varchar(50) DEFAULT NULL,
  `lName` varchar(50) DEFAULT NULL,
  `mName` varchar(50) DEFAULT NULL,
  `countryCode` varchar(10) DEFAULT NULL,
  `contactNo` varchar(20) DEFAULT NULL,
  `agentType` enum('Retailer','Wholeseller') NOT NULL,
  `agentRole` enum('Head Agent','Sub Agent','Sub Agent2','') DEFAULT NULL,
  `comissionRate` int(11) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

--
-- Triggers `agent`
--
DELIMITER $$
CREATE TRIGGER `after_agent_insert` AFTER INSERT ON `agent` FOR EACH ROW BEGIN
    -- Insert a record in agentFlightSeats for each existing flight
    INSERT INTO agentflightseats (agentId, flightId, maxSeats)
    SELECT NEW.agentId, f.flightId, 10
    FROM flight f;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `agentcomission`
--

CREATE TABLE `agentcomission` (
  `comissionId` int(11) NOT NULL,
  `agentId` varchar(50) DEFAULT NULL,
  `accountId` int(11) DEFAULT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `totalPrice` double(10,2) DEFAULT NULL,
  `comissionAmount` double(10,2) DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agentcomission`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `auditaccounts`
--

CREATE TABLE `auditaccounts` (
  `auditId` int(11) NOT NULL,
  `accountId` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `actionType` enum('INSERT','UPDATE','DELETE','') NOT NULL,
  `actionDate` datetime NOT NULL,
  `performedBy` int(11) NOT NULL,
  `oldValues` text DEFAULT NULL,
  `newValues` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auditbooking`
--

CREATE TABLE `auditbooking` (
  `auditId` int(11) NOT NULL,
  `bookingId` int(11) NOT NULL,
  `transactNo` varchar(50) NOT NULL,
  `actionType` enum('INSERT','UPDATE','DELETE','') NOT NULL,
  `actionDate` datetime NOT NULL,
  `performedBy` int(11) NOT NULL,
  `oldValues` text DEFAULT NULL,
  `newValues` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auditbooking`
--



-- --------------------------------------------------------

--
-- Table structure for table `auditpayment`
--

CREATE TABLE `auditpayment` (
  `auditId` int(11) NOT NULL,
  `paymentId` int(11) NOT NULL,
  `transactNo` varchar(50) NOT NULL,
  `actionType` enum('INSERT','UPDATE','DELETE','') NOT NULL,
  `actionDate` datetime NOT NULL,
  `performedBy` int(11) DEFAULT NULL,
  `oldValues` text DEFAULT NULL,
  `newValues` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auditpayment`
--

-- --------------------------------------------------------

--
-- Table structure for table `auditrequest`
--

CREATE TABLE `auditrequest` (
  `auditId` int(11) NOT NULL,
  `requestId` int(11) NOT NULL,
  `transactNo` varchar(50) NOT NULL,
  `actionType` enum('INSERT','UPDATE','DELETE','') NOT NULL,
  `actionDate` datetime NOT NULL,
  `performedBy` int(11) NOT NULL,
  `oldValues` text DEFAULT NULL,
  `newValues` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auditrequest`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `bookingId` int(11) NOT NULL,
  `accountId` int(11) DEFAULT NULL,
  `transactNo` varchar(50) NOT NULL,
  `agentId` varchar(50) DEFAULT NULL,
  `agentCode` varchar(50) DEFAULT NULL,
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
  `bookingType` enum('Package','Land') DEFAULT NULL,
  `flightDetails` text DEFAULT NULL,
  `status` enum('Pending','Cancelled','Confirmed','Complete','Reserved') NOT NULL,
  `remarks` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

--
-- Triggers `booking`
--
DELIMITER $$
CREATE TRIGGER `after_booking_completed` AFTER UPDATE ON `booking` FOR EACH ROW BEGIN
	-- Ensure @current_user_id is set if it's NULL
    IF @current_user_id IS NULL THEN
        SET @current_user_id = 'SYSTEM'; 
    END IF;

    -- Check if the status has been updated to 'Completed' and the agentType is 'Wholeseller'
    IF NEW.status = 'Confirmed' AND OLD.status != 'Confirmed' THEN
        -- Ensure the agentType is 'Wholeseller'
        IF (SELECT agentType FROM agent WHERE accountId = OLD.accountId) = 'Wholeseller' THEN
            INSERT INTO agentComission (
                agentId, accountId, transactNo, totalPrice, comissionAmount, createdAt
            ) VALUES (
                OLD.agentId,
                OLD.accountId,
                OLD.transactNo,
                OLD.totalPrice,
                OLD.totalPrice * (SELECT comissionRate FROM agent WHERE accountId = OLD.accountId) / 100, -- Calculate commission
                NOW()
            );
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_booking_insert` AFTER INSERT ON `booking` FOR EACH ROW BEGIN
	-- Ensure @current_user_id is set if it's NULL
    IF @current_user_id IS NULL THEN
        SET @current_user_id = 'SYSTEM'; 
    END IF;

    INSERT INTO auditbooking (
        bookingId, 
        transactNo, 
        actionType, 
        actionDate, 
        performedBy, 
        oldValues, 
        newValues
    )
    VALUES (
        NEW.bookingId,
        NEW.transactNo,
        'INSERT',
        CURRENT_TIMESTAMP,
        @current_user_id, -- Dynamic session variable for the current user
        NULL,
        CONCAT('Inserted Transact No: ', NEW.transactNo, ', status: ', NEW.status)
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_booking_update` AFTER UPDATE ON `booking` FOR EACH ROW BEGIN
	-- Ensure @current_user_id is set if it's NULL
    IF @current_user_id IS NULL THEN
        SET @current_user_id = 'SYSTEM'; 
    END IF;

    INSERT INTO auditbooking (
        bookingId, 
        transactNo, 
        actionType, 
        actionDate, 
        performedBy, 
        oldValues, 
        newValues
    ) 
    VALUES (
        OLD.bookingId, 
        OLD.transactNo, 
        'UPDATE', 
        CURRENT_TIMESTAMP, 
        @current_user_id,  -- Replace with actual session variable or method for tracking the user
        CONCAT('BookingId: ', OLD.bookingId, ', TransactNo: ', OLD.transactNo, ', Status: ', OLD.status), 
        CONCAT('BookingId: ', NEW.bookingId, ', TransactNo: ', NEW.transactNo, ', Status: ', NEW.status)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookingcomments`
--

CREATE TABLE `bookingcomments` (
  `id` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branchId` int(11) NOT NULL,
  `branchName` varchar(100) DEFAULT NULL,
  `branchLocation` varchar(100) DEFAULT NULL,
  `branchAdd` text DEFAULT NULL,
  `branchContactP` varchar(50) DEFAULT NULL,
  `branchAgentCode` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branchId`, `branchName`, `branchLocation`, `branchAdd`, `branchContactP`, `branchAgentCode`) VALUES
(1, 'Branch 1', NULL, NULL, 'A006', 'BU1'),
(2, 'Branch 2', NULL, NULL, 'A001', 'BU2'),
(3, 'Branch 3', NULL, NULL, 'A007', 'BU3'),
(4, 'E Winner', NULL, NULL, 'A005', 'BU4'),
(5, 'FRANCIA', NULL, NULL, 'A003', 'BU5'),
(6, 'ESCAPE', NULL, NULL, 'A004', 'BU6'),
(7, 'APD', NULL, NULL, 'A002', 'BU7');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `clientId` int(11) NOT NULL,
  `accountId` int(11) DEFAULT NULL,
  `branchId` int(11) DEFAULT NULL,
  `fName` varchar(50) DEFAULT NULL,
  `lName` varchar(50) DEFAULT NULL,
  `mName` varchar(50) DEFAULT NULL,
  `countryCode` varchar(10) DEFAULT NULL,
  `contactNo` varchar(20) DEFAULT NULL,
  `clientType` enum('Retailer','Wholeseller','','') DEFAULT NULL,
  `clientRole` enum('Head Agent','Sub Agent','Sub Agent2','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(8, 'FOC'),
(9, 'Cake'),
(10, 'Cancellation Fee'),
(11, 'Wifi Rental'),
(12, 'Denied Visa/ Airfare Only'),
(13, 'Shopping Penalty');

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
(18, 5, 'Standard', 350.00),
(19, 5, 'Standard Plus', 600.00),
(23, 4, 'Single Supplement', 140.00),
(24, 5, 'Premium', 900.00),
(25, 9, 'Cake', 0.00),
(26, 8, 'Free of Charge', 0.00),
(27, 10, 'Cancellation Fee', 5000.00),
(28, 11, 'Wifi Rental', 25.00),
(29, 12, 'Denied Visa/Airfare Only', 0.00),
(30, 13, 'Shopping Penalty', 140.00),
(31, 1, 'testing', 100.00),
(32, 1, 'testing', 100.00);

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

-- --------------------------------------------------------

--
-- Table structure for table `fit`
--

CREATE TABLE `fit` (
  `bookingId` int(11) NOT NULL,
  `transactionNo` varchar(50) NOT NULL,
  `accountId` int(11) DEFAULT NULL,
  `agentId` varchar(50) DEFAULT NULL,
  `agentCode` varchar(50) DEFAULT NULL,
  `packageId` int(11) DEFAULT NULL,
  `nights` int(11) NOT NULL,
  `hotelId` int(11) DEFAULT NULL,
  `roomId` int(11) DEFAULT NULL,
  `rooms` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `returnDate` date NOT NULL,
  `pax` int(11) NOT NULL,
  `phpPrice` double(10,2) NOT NULL,
  `usdPrice` double(10,2) NOT NULL,
  `fName` varchar(50) NOT NULL,
  `mName` varchar(50) NOT NULL,
  `lName` varchar(50) NOT NULL,
  `suffix` enum('N/A','Jr.','Sr.','II','III','IV','V') NOT NULL,
  `countryCode` varchar(10) NOT NULL,
  `contactNo` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `bookingDate` datetime NOT NULL,
  `status` enum('Pending','Cancelled','Confirmed','Reject','Completed','') NOT NULL,
  `remarks` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fit`
--

-- --------------------------------------------------------

--
-- Table structure for table `fithotel`
--

CREATE TABLE `fithotel` (
  `hotelId` int(11) NOT NULL,
  `hotelName` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fithotel`
--

INSERT INTO `fithotel` (`hotelId`, `hotelName`, `price`) VALUES
(1, 'Smart Travel Hotel', 80),
(2, 'Marina Bay Hotel Seoul', 100);

-- --------------------------------------------------------

--
-- Table structure for table `fitpackage`
--

CREATE TABLE `fitpackage` (
  `packageId` int(11) NOT NULL,
  `packageName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fitpackage`
--

INSERT INTO `fitpackage` (`packageId`, `packageName`) VALUES
(1, 'FIT');

-- --------------------------------------------------------

--
-- Table structure for table `fitpayment`
--

CREATE TABLE `fitpayment` (
  `paymentId` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `accountId` int(11) DEFAULT NULL,
  `paymentType` enum('Downpayment','Partial Payment','Full Payment','') NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `filePath` text DEFAULT NULL,
  `paymentDate` datetime DEFAULT NULL,
  `paymentStatus` enum('Submitted','Approved','Rejected','') DEFAULT NULL,
  `paymentRemarks` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fitpayment`
--

--
-- Triggers `fitpayment`
--
DELIMITER $$
CREATE TRIGGER `update_fit_status` AFTER UPDATE ON `fitpayment` FOR EACH ROW BEGIN
    DECLARE approvedPaymentCount INT;
    DECLARE currentStatus VARCHAR(50);

    -- Fetch the current status of the fit table
    SELECT status
    INTO currentStatus
    FROM fit
    WHERE transactionNo = NEW.transactNo;

    -- Count the number of approved payments for this transactNo
    SELECT COUNT(*)
    INTO approvedPaymentCount
    FROM fitpayment
    WHERE transactNo = NEW.transactNo AND paymentStatus = 'Approved';

    -- Update the status in the fit table if it's the first approved payment
    IF approvedPaymentCount = 1 AND currentStatus != 'Confirmed' THEN
        UPDATE fit
        SET status = 'Confirmed'
        WHERE transactionNo = NEW.transactNo;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `fitrooms`
--

CREATE TABLE `fitrooms` (
  `roomId` int(11) NOT NULL,
  `hotelId` int(11) DEFAULT NULL,
  `rooms` varchar(50) DEFAULT NULL,
  `availRooms` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fitrooms`
--

INSERT INTO `fitrooms` (`roomId`, `hotelId`, `rooms`, `availRooms`, `price`) VALUES
(1, 1, 'Twin', 5, 80),
(2, 1, 'Double', 3, 80),
(3, 1, 'Triple', 2, 120),
(4, 2, 'Twin', 50, 90),
(5, 2, 'Double', 30, 90),
(6, 2, 'Triple', 10, 135);

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
  `landPrice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `availSeats` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`flightId`, `packageId`, `employeeId`, `origin`, `flightName`, `flightCode`, `flightDepartureDate`, `flightDepartureTime`, `flightArrivalDate`, `flightArrivalTime`, `returnFlightName`, `returnFlightCode`, `returnDepartureDate`, `returnDepartureTime`, `returnArrivalDate`, `returnArrivalTime`, `wholesalePrice`, `flightPrice`, `landPrice`, `availSeats`, `is_active`) VALUES
(1, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-01-01', '05:45:00', '2024-01-01', '10:45:00', 'ICN - MNL', '5J187', '2024-01-06', '12:45:00', '2024-01-06', '04:00:00', 34296.25, 40296.25, 0.00, 40, 0),
(2, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-01-01', '05:45:00', '2024-01-01', '10:45:00', 'ICN - MNL', '5J187', '2024-01-06', '12:45:00', '2024-01-06', '04:00:00', 39924.02, 45964.02, 0.00, 40, 0),
(3, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-01-02', '05:45:00', '2024-01-02', '10:45:00', 'ICN - MNL', '5J187', '2024-01-07', '12:45:00', '2024-01-07', '04:00:00', 33614.86, 39614.86, 0.00, 40, 0),
(4, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-01-03', '05:45:00', '2024-01-03', '10:45:00', 'ICN - MNL', '5J187', '2024-01-08', '12:45:00', '2024-01-08', '04:00:00', 33414.86, 39414.86, 0.00, 40, 0),
(5, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-01-04', '05:45:00', '2024-01-04', '10:45:00', 'ICN - MNL', '5J187', '2024-01-09', '12:45:00', '2024-01-09', '04:00:00', 29774.86, 35774.86, 0.00, 40, 0),
(6, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-01-06', '05:45:00', '2024-01-06', '10:45:00', 'ICN - MNL', '5J187', '2024-01-11', '12:45:00', '2024-01-11', '04:00:00', 29625.30, 35625.30, 0.00, 40, 0),
(7, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-01-07', '05:45:00', '2024-01-07', '10:45:00', 'ICN - MNL', '5J187', '2024-01-12', '12:45:00', '2024-01-12', '04:00:00', 29774.86, 35774.86, 0.00, 40, 0),
(8, 7, 'E015', 'Manila', 'MNL - INC', '5J188', '2025-01-07', '05:45:00', '2024-01-07', '10:45:00', 'ICN - MNL', '5J187', '2024-01-12', '12:45:00', '2024-01-12', '04:00:00', 36270.86, 42270.86, 0.00, 40, 0),
(9, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-01-08', '05:45:00', '2024-01-08', '10:45:00', 'ICN - MNL', '5J187', '2024-01-13', '12:45:00', '2024-01-13', '04:00:00', 30074.86, 36074.86, 0.00, 40, 0),
(10, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-01-08', '05:45:00', '2024-01-08', '10:45:00', 'ICN - MNL', '5J187', '2024-01-13', '12:45:00', '2024-01-13', '04:00:00', 32650.63, 38650.63, 0.00, 40, 0),
(11, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-01-09', '05:45:00', '2024-01-09', '10:45:00', 'ICN - MNL', '5J187', '2024-01-14', '12:45:00', '2024-01-14', '04:00:00', 28574.86, 34574.86, 0.00, 40, 0),
(12, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-01-09', '05:45:00', '2024-01-09', '10:45:00', 'ICN - MNL', '5J187', '2024-01-14', '12:45:00', '2024-01-14', '04:00:00', 30330.63, 36330.63, 0.00, 40, 0),
(13, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-01-09', '05:45:00', '2024-01-09', '10:45:00', 'ICN - MNL', '5J187', '2024-01-14', '12:45:00', '2024-01-14', '04:00:00', 33330.63, 39330.63, 0.00, 40, 0),
(14, 7, 'E015', 'Manila', 'MNL - INC', '5J188', '2025-01-10', '05:45:00', '2024-01-10', '10:45:00', 'ICN - MNL', '5J187', '2024-01-15', '12:45:00', '2024-01-15', '04:00:00', 28925.30, 34925.30, 0.00, 40, 0),
(15, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-01-11', '05:45:00', '2024-01-11', '10:45:00', 'ICN - MNL', '5J187', '2024-01-16', '12:45:00', '2024-01-16', '04:00:00', 28925.30, 34925.30, 0.00, 40, 0),
(16, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-01-14', '05:45:00', '2024-01-14', '10:45:00', 'ICN - MNL', '5J187', '2024-01-19', '12:45:00', '2024-01-19', '04:00:00', 29423.42, 35423.42, 0.00, 40, 0),
(17, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-01-15', '05:45:00', '2024-01-15', '10:45:00', 'ICN - MNL', '5J187', '2024-01-20', '12:45:00', '2024-01-20', '04:00:00', 28925.30, 34925.30, 0.00, 40, 0),
(18, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-01-15', '05:45:00', '2024-01-15', '10:45:00', 'ICN - MNL', '5J187', '2024-01-20', '12:45:00', '2024-01-20', '04:00:00', 31925.30, 37925.30, 0.00, 40, 0),
(19, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-01-16', '05:45:00', '2024-01-16', '10:45:00', 'ICN - MNL', '5J187', '2024-01-21', '12:45:00', '2024-01-21', '04:00:00', 28925.30, 34925.30, 0.00, 40, 0),
(20, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-01-17', '05:45:00', '2024-01-17', '10:45:00', 'ICN - MNL', '5J187', '2024-01-22', '12:45:00', '2024-01-22', '04:00:00', 28925.30, 34925.30, 0.00, 40, 0),
(21, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-01-18', '05:45:00', '2024-01-18', '10:45:00', 'ICN - MNL', '5J187', '2024-01-23', '12:45:00', '2024-01-23', '04:00:00', 32925.30, 38925.30, 0.00, 40, 0),
(22, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-01-19', '05:45:00', '2024-01-19', '10:45:00', 'ICN - MNL', '5J187', '2024-01-24', '12:45:00', '2024-01-24', '04:00:00', 31925.30, 37925.30, 0.00, 40, 0),
(23, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-01-21', '05:45:00', '2024-01-21', '10:45:00', 'ICN - MNL', '5J187', '2024-01-26', '12:45:00', '2024-01-26', '04:00:00', 31023.42, 37023.42, 0.00, 40, 0),
(24, 7, 'E015', 'Manila', 'MNL - INC', '5J188', '2025-01-22', '05:45:00', '2024-01-22', '10:45:00', 'ICN - MNL', '5J187', '2024-01-27', '12:45:00', '2024-01-27', '04:00:00', 28925.30, 34925.30, 0.00, 40, 0),
(25, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-01-23', '05:45:00', '2024-01-23', '10:45:00', 'ICN - MNL', '5J187', '2024-01-28', '12:45:00', '2024-01-28', '04:00:00', 28925.30, 34925.30, 0.00, 40, 0),
(26, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-01-24', '05:45:00', '2024-01-24', '10:45:00', 'ICN - MNL', '5J187', '2024-01-29', '12:45:00', '2024-01-29', '04:00:00', 28925.30, 34925.30, 0.00, 40, 0),
(27, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-01-25', '05:45:00', '2024-01-25', '10:45:00', 'ICN - MNL', '5J187', '2024-01-30', '12:45:00', '2024-01-30', '04:00:00', 33093.42, 39093.42, 0.00, 40, 0),
(28, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-01-26', '05:45:00', '2024-01-26', '10:45:00', 'ICN - MNL', '5J187', '2024-01-31', '12:45:00', '2024-01-31', '04:00:00', 29923.42, 35923.42, 0.00, 40, 0),
(29, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-01-27', '05:45:00', '2024-01-27', '10:45:00', 'ICN - MNL', '5J187', '2024-02-01', '12:45:00', '2024-02-01', '04:00:00', 30423.42, 36423.42, 0.00, 40, 0),
(30, 7, 'E015', 'Manila', 'MNL - INC', '5J188', '2025-01-28', '05:45:00', '2024-01-28', '10:45:00', 'ICN - MNL', '5J187', '2024-02-02', '12:45:00', '2024-02-02', '04:00:00', 31023.42, 37023.42, 0.00, 40, 0),
(31, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-01-29', '05:45:00', '2024-01-29', '10:45:00', 'ICN - MNL', '5J187', '2024-02-03', '12:45:00', '2024-02-03', '04:00:00', 31023.42, 37023.42, 0.00, 40, 0),
(32, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-01-30', '05:45:00', '2024-01-30', '10:45:00', 'ICN - MNL', '5J187', '2024-02-04', '12:45:00', '2024-02-04', '04:00:00', 30523.42, 36523.42, 0.00, 40, 0),
(33, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-01-30', '05:45:00', '2024-01-30', '10:45:00', 'ICN - MNL', '5J187', '2024-02-04', '12:45:00', '2024-02-04', '04:00:00', 31935.11, 37935.11, 0.00, 40, 0),
(34, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-01-31', '05:45:00', '2024-01-31', '10:45:00', 'ICN - MNL', '5J187', '2024-02-05', '12:45:00', '2024-02-05', '04:00:00', 30423.42, 36423.42, 0.00, 40, 0),
(35, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-02-04', '05:45:00', '2025-02-04', '10:45:00', 'ICN - MNL', '5J187', '2025-02-09', '12:45:00', '2025-02-09', '04:00:00', 29423.42, 35423.42, 0.00, 40, 0),
(36, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-02-05', '05:45:00', '2025-02-05', '10:45:00', 'ICN - MNL', '5J187', '2025-02-10', '12:45:00', '2025-02-10', '04:00:00', 28923.42, 34923.42, 0.00, 40, 0),
(37, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-02-05', '05:45:00', '2025-02-05', '10:45:00', 'ICN - MNL', '5J187', '2025-02-10', '12:45:00', '2025-02-10', '04:00:00', 31923.42, 37923.42, 0.00, 40, 0),
(38, 7, 'E015', 'Manila', 'MNL - INC', '5J188', '2025-02-06', '05:45:00', '2025-02-06', '10:45:00', 'ICN - MNL', '5J187', '2025-02-11', '12:45:00', '2025-02-11', '04:00:00', 28923.42, 34923.42, 0.00, 40, 0),
(39, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-02-07', '05:45:00', '2025-02-07', '10:45:00', 'ICN - MNL', '5J187', '2025-02-12', '12:45:00', '2025-02-12', '04:00:00', 29923.42, 35923.42, 0.00, 40, 0),
(40, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-02-08', '05:45:00', '2025-02-08', '10:45:00', 'ICN - MNL', '5J187', '2025-02-13', '12:45:00', '2025-02-13', '04:00:00', 30923.42, 36923.42, 0.00, 40, 0),
(41, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-02-09', '05:45:00', '2025-02-09', '10:45:00', 'ICN - MNL', '5J187', '2025-02-14', '12:45:00', '2025-02-14', '04:00:00', 30923.42, 36923.42, 0.00, 40, 0),
(42, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-02-10', '05:45:00', '2025-02-10', '10:45:00', 'ICN - MNL', '5J187', '2025-02-15', '12:45:00', '2025-02-15', '04:00:00', 30923.42, 36923.42, 0.00, 40, 0),
(43, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-02-11', '05:45:00', '2025-02-11', '10:45:00', 'ICN - MNL', '5J187', '2025-02-16', '12:45:00', '2025-02-16', '04:00:00', 29423.42, 35423.42, 0.00, 40, 1),
(44, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-02-11', '05:45:00', '2025-02-11', '10:45:00', 'ICN - MNL', '5J187', '2025-02-16', '12:45:00', '2025-02-16', '04:00:00', 32335.11, 38335.11, 0.00, 40, 1),
(45, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-02-12', '05:45:00', '2025-02-12', '10:45:00', 'ICN - MNL', '5J187', '2025-02-17', '12:45:00', '2025-02-17', '04:00:00', 29423.42, 35423.42, 0.00, 40, 1),
(46, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-02-12', '05:45:00', '2025-02-12', '10:45:00', 'ICN - MNL', '5J187', '2025-02-17', '12:45:00', '2025-02-17', '04:00:00', 30423.42, 36423.42, 0.00, 40, 1),
(47, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-02-13', '05:45:00', '2025-02-13', '10:45:00', 'ICN - MNL', '5J187', '2025-02-18', '12:45:00', '2025-02-18', '04:00:00', 29423.42, 35423.42, 0.00, 40, 0),
(48, 7, 'E015', 'Manila', 'MNL - INC', '5J188', '2025-02-14', '05:45:00', '2025-02-14', '10:45:00', 'ICN - MNL', '5J187', '2025-02-19', '12:45:00', '2025-02-19', '04:00:00', 29923.42, 35923.42, 0.00, 40, 1),
(49, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-02-14', '05:45:00', '2025-02-14', '10:45:00', 'ICN - MNL', '5J187', '2025-02-19', '12:45:00', '2025-02-19', '04:00:00', 30923.42, 36923.42, 0.00, 40, 0),
(50, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-02-18', '05:45:00', '2025-02-18', '10:45:00', 'ICN - MNL', '5J187', '2025-02-23', '12:45:00', '2025-02-23', '04:00:00', 29423.42, 35423.42, 0.00, 40, 0),
(51, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-02-19', '05:45:00', '2025-02-19', '10:45:00', 'ICN - MNL', '5J187', '2025-02-24', '12:45:00', '2025-02-24', '04:00:00', 29423.42, 35423.42, 0.00, 40, 0),
(52, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-02-19', '05:45:00', '2025-02-19', '10:45:00', 'ICN - MNL', '5J187', '2025-02-24', '12:45:00', '2025-02-24', '04:00:00', 32423.42, 37423.42, 0.00, 40, 0),
(53, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-02-20', '05:45:00', '2025-02-20', '10:45:00', 'ICN - MNL', '5J187', '2025-02-25', '12:45:00', '2025-02-25', '04:00:00', 28923.42, 34923.42, 0.00, 40, 1),
(54, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-02-21', '05:45:00', '2025-02-21', '10:45:00', 'ICN - MNL', '5J187', '2025-02-26', '12:45:00', '2025-02-26', '04:00:00', 29423.42, 35423.42, 0.00, 40, 0),
(55, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-02-25', '05:45:00', '2025-02-25', '10:45:00', 'ICN - MNL', '5J187', '2025-03-02', '12:45:00', '2025-03-02', '04:00:00', 29413.95, 35413.95, 0.00, 40, 0),
(56, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-02-26', '05:45:00', '2025-02-26', '10:45:00', 'ICN - MNL', '5J187', '2025-03-03', '12:45:00', '2025-03-03', '04:00:00', 28913.95, 34913.95, 0.00, 40, 0),
(57, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-02-26', '05:45:00', '2025-02-26', '10:45:00', 'ICN - MNL', '5J187', '2025-03-03', '12:45:00', '2025-03-03', '04:00:00', 30335.11, 36335.11, 0.00, 40, 0),
(58, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-02-27', '05:45:00', '2025-02-27', '10:45:00', 'ICN - MNL', '5J187', '2025-03-04', '12:45:00', '2025-03-04', '04:00:00', 28513.95, 34513.95, 0.00, 40, 0),
(59, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-02-28', '05:45:00', '2025-02-28', '10:45:00', 'ICN - MNL', '5J187', '2025-03-05', '12:45:00', '2025-03-05', '04:00:00', 28513.95, 34513.95, 0.00, 40, 0),
(60, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-03-01', '05:45:00', '2025-03-01', '10:45:00', 'ICN - MNL', '5J187', '2025-03-06', '12:45:00', '2025-03-06', '04:00:00', 30623.91, 36623.91, 0.00, 40, 0),
(61, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-03-02', '05:45:00', '2025-03-02', '10:45:00', 'ICN - MNL', '5J187', '2025-03-07', '12:45:00', '2025-03-07', '04:00:00', 30623.91, 36623.91, 0.00, 40, 0),
(62, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-03-03', '05:45:00', '2025-03-03', '10:45:00', 'ICN - MNL', '5J187', '2025-03-08', '12:45:00', '2025-03-08', '04:00:00', 30623.91, 36623.91, 0.00, 40, 0),
(63, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-03-04', '05:45:00', '2025-03-04', '10:45:00', 'ICN - MNL', '5J187', '2025-03-09', '12:45:00', '2025-03-09', '04:00:00', 30623.91, 36623.91, 0.00, 40, 0),
(64, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-03-05', '05:45:00', '2025-03-05', '10:45:00', 'ICN - MNL', '5J187', '2025-03-10', '12:45:00', '2025-03-10', '04:00:00', 27903.91, 33903.91, 0.00, 40, 0),
(65, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-03-06', '05:45:00', '2025-03-06', '10:45:00', 'ICN - MNL', '5J187', '2025-03-11', '12:45:00', '2025-03-11', '04:00:00', 27692.91, 33692.91, 0.00, 40, 0),
(66, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-03-06', '05:45:00', '2025-03-06', '10:45:00', 'ICN - MNL', '5J187', '2025-03-11', '12:45:00', '2025-03-11', '04:00:00', 30435.11, 36435.11, 0.00, 40, 0),
(67, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-03-07', '05:45:00', '2025-03-07', '10:45:00', 'ICN - MNL', '5J187', '2025-03-12', '12:45:00', '2025-03-12', '04:00:00', 28303.91, 34303.91, 0.00, 40, 0),
(68, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-03-08', '05:45:00', '2025-03-08', '10:45:00', 'ICN - MNL', '5J187', '2025-03-13', '12:45:00', '2025-03-13', '04:00:00', 27903.91, 33903.91, 0.00, 40, 0),
(69, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-03-09', '05:45:00', '2025-03-09', '10:45:00', 'ICN - MNL', '5J187', '2025-03-14', '12:45:00', '2025-03-14', '04:00:00', 29303.91, 35303.91, 0.00, 40, 0),
(70, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-03-11', '05:45:00', '2025-03-11', '10:45:00', 'ICN - MNL', '5J187', '2025-03-16', '12:45:00', '2025-03-16', '04:00:00', 29303.91, 35303.91, 0.00, 40, 0),
(71, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-03-12', '05:45:00', '2025-03-12', '10:45:00', 'ICN - MNL', '5J187', '2025-03-17', '12:45:00', '2025-03-17', '04:00:00', 27903.91, 33903.91, 0.00, 40, 0),
(72, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-03-12', '05:45:00', '2025-03-12', '10:45:00', 'ICN - MNL', '5J187', '2025-03-17', '12:45:00', '2025-03-17', '04:00:00', 29724.11, 35724.11, 0.00, 40, 0),
(73, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-03-13', '05:45:00', '2025-03-13', '10:45:00', 'ICN - MNL', '5J187', '2025-03-18', '12:45:00', '2025-03-18', '04:00:00', 27692.91, 33692.91, 0.00, 40, 0),
(74, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-03-14', '05:45:00', '2025-03-14', '10:45:00', 'ICN - MNL', '5J187', '2025-03-19', '12:45:00', '2025-03-19', '04:00:00', 27903.91, 33903.91, 0.00, 40, 0),
(75, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-03-15', '05:45:00', '2025-03-15', '10:45:00', 'ICN - MNL', '5J187', '2025-03-20', '12:45:00', '2025-03-20', '04:00:00', 27692.91, 33692.91, 0.00, 40, 0),
(76, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-03-16', '05:45:00', '2025-03-16', '10:45:00', 'ICN - MNL', '5J187', '2025-03-21', '12:45:00', '2025-03-21', '04:00:00', 28912.25, 34912.25, 0.00, 40, 0),
(77, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-03-18', '05:45:00', '2025-03-18', '10:45:00', 'ICN - MNL', '5J187', '2025-03-23', '12:45:00', '2025-03-23', '04:00:00', 28912.25, 34912.25, 0.00, 40, 0),
(78, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-03-19', '05:45:00', '2025-03-19', '10:45:00', 'ICN - MNL', '5J187', '2025-03-24', '12:45:00', '2025-03-24', '04:00:00', 27903.91, 33903.91, 0.00, 40, 0),
(79, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-03-19', '05:45:00', '2025-03-19', '10:45:00', 'ICN - MNL', '5J187', '2025-03-24', '12:45:00', '2025-03-24', '04:00:00', 31935.11, 37935.11, 0.00, 40, 0),
(80, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-03-20', '05:45:00', '2025-03-20', '10:45:00', 'ICN - MNL', '5J187', '2025-03-25', '12:45:00', '2025-03-25', '04:00:00', 27692.91, 33692.91, 0.00, 40, 0),
(81, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-03-21', '05:45:00', '2025-03-21', '10:45:00', 'ICN - MNL', '5J187', '2025-03-26', '12:45:00', '2025-03-26', '04:00:00', 27903.91, 33903.91, 0.00, 40, 0),
(82, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-03-21', '05:45:00', '2025-03-21', '10:45:00', 'ICN - MNL', '5J187', '2025-03-26', '12:45:00', '2025-03-26', '04:00:00', 28903.91, 34903.91, 0.00, 40, 0),
(83, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-03-22', '05:45:00', '2025-03-22', '10:45:00', 'ICN - MNL', '5J187', '2025-03-27', '12:45:00', '2025-03-27', '04:00:00', 27692.91, 33692.91, 0.00, 40, 0),
(84, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-03-23', '05:45:00', '2025-03-23', '10:45:00', 'ICN - MNL', '5J187', '2025-03-28', '12:45:00', '2025-03-28', '04:00:00', 29692.91, 35692.91, 0.00, 40, 0),
(85, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-03-26', '05:45:00', '2025-03-26', '10:45:00', 'ICN - MNL', '5J187', '2025-03-31', '12:45:00', '2025-03-31', '04:00:00', 27915.00, 33915.00, 0.00, 40, 0),
(86, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-03-26', '05:45:00', '2025-03-26', '10:45:00', 'ICN - MNL', '5J187', '2025-03-31', '12:45:00', '2025-03-31', '04:00:00', 30435.11, 36435.11, 0.00, 40, 0),
(87, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-03-27', '05:45:00', '2025-03-27', '10:45:00', 'ICN - MNL', '5J187', '2025-04-01', '12:45:00', '2025-04-01', '04:00:00', 27704.00, 33704.00, 0.00, 40, 0),
(88, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-03-28', '05:45:00', '2025-03-28', '10:45:00', 'ICN - MNL', '5J187', '2025-04-02', '12:45:00', '2025-04-02', '04:00:00', 28526.00, 34526.00, 0.00, 40, 0),
(89, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-03-29', '05:45:00', '2025-03-29', '10:45:00', 'ICN - MNL', '5J187', '2025-04-03', '12:45:00', '2025-04-03', '04:00:00', 28912.25, 34912.25, 0.00, 40, 0),
(90, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-03-30', '05:45:00', '2025-03-30', '10:45:00', 'ICN - MNL', '5J187', '2025-04-04', '12:45:00', '2025-04-04', '04:00:00', 30513.68, 36513.68, 0.00, 40, 0),
(91, 5, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-04-01', '05:45:00', '2025-04-01', '10:45:00', 'ICN - MNL', '5J187', '2025-04-06', '12:45:00', '2025-04-06', '04:00:00', 32339.47, 38839.47, 0.00, 40, 0),
(92, 5, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-04-02', '05:45:00', '2025-04-02', '10:45:00', 'ICN - MNL', '5J187', '2025-04-07', '12:45:00', '2025-04-07', '04:00:00', 32340.12, 38840.12, 0.00, 40, 0),
(93, 5, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-04-03', '05:45:00', '2025-04-03', '10:45:00', 'ICN - MNL', '5J187', '2025-04-08', '12:45:00', '2025-04-08', '04:00:00', 31040.12, 37540.12, 0.00, 40, 0),
(94, 5, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-04-03', '05:45:00', '2025-04-03', '10:45:00', 'ICN - MNL', '5J187', '2025-04-08', '12:45:00', '2025-04-08', '04:00:00', 33040.12, 39540.12, 0.00, 40, 0),
(95, 5, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-04-04', '05:45:00', '2025-04-04', '10:45:00', 'ICN - MNL', '5J187', '2025-04-09', '12:45:00', '2025-04-09', '04:00:00', 33039.47, 39539.47, 0.00, 40, 0),
(96, 5, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-04-04', '05:45:00', '2025-04-04', '10:45:00', 'ICN - MNL', '5J187', '2025-04-09', '12:45:00', '2025-04-09', '04:00:00', 35039.47, 41539.47, 0.00, 40, 0),
(97, 5, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-04-05', '05:45:00', '2025-04-05', '10:45:00', 'ICN - MNL', '5J187', '2025-04-10', '12:45:00', '2025-04-10', '04:00:00', 31036.90, 37536.90, 0.00, 40, 0),
(98, 5, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-04-06', '05:45:00', '2025-04-06', '10:45:00', 'ICN - MNL', '5J187', '2025-04-11', '12:45:00', '2025-04-11', '04:00:00', 32337.75, 38837.75, 0.00, 40, 0),
(99, 5, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-04-06', '05:45:00', '2025-04-06', '10:45:00', 'ICN - MNL', '5J187', '2025-04-11', '12:45:00', '2025-04-11', '04:00:00', 32337.75, 38837.75, 0.00, 40, 0),
(100, 5, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-04-06', '05:45:00', '2025-04-06', '10:45:00', 'ICN - MNL', '5J187', '2025-04-11', '12:45:00', '2025-04-11', '04:00:00', 32837.75, 39337.75, 0.00, 40, 0),
(101, 5, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-04-06', '05:45:00', '2025-04-06', '10:45:00', 'ICN - MNL', '5J187', '2025-04-11', '12:45:00', '2025-04-11', '04:00:00', 34837.75, 40337.75, 0.00, 40, 0),
(102, 5, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-04-08', '05:45:00', '2025-04-08', '10:45:00', 'ICN - MNL', '5J187', '2025-04-13', '12:45:00', '2025-04-13', '04:00:00', 32330.96, 38830.96, 0.00, 40, 0),
(103, 5, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-04-08', '05:45:00', '2025-04-08', '10:45:00', 'ICN - MNL', '5J187', '2025-04-13', '12:45:00', '2025-04-13', '04:00:00', 32830.96, 39330.96, 0.00, 40, 0),
(104, 5, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-04-09', '05:45:00', '2025-04-09', '10:45:00', 'ICN - MNL', '5J187', '2025-04-14', '12:45:00', '2025-04-14', '04:00:00', 33023.77, 39523.77, 0.00, 40, 0),
(105, 5, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-04-09', '05:45:00', '2025-04-09', '10:45:00', 'ICN - MNL', '5J187', '2025-04-14', '12:45:00', '2025-04-14', '04:00:00', 34650.96, 41150.96, 0.00, 40, 0),
(106, 5, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-04-10', '05:45:00', '2025-04-10', '10:45:00', 'ICN - MNL', '5J187', '2025-04-15', '12:45:00', '2025-04-15', '04:00:00', 33743.77, 40243.77, 0.00, 40, 0),
(107, 5, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-04-10', '05:45:00', '2025-04-10', '10:45:00', 'ICN - MNL', '5J187', '2025-04-15', '12:45:00', '2025-04-15', '04:00:00', 34993.77, 41493.77, 0.00, 40, 0),
(108, 5, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-04-11', '05:45:00', '2025-04-11', '10:45:00', 'ICN - MNL', '5J187', '2025-04-16', '12:45:00', '2025-04-16', '04:00:00', 34145.06, 40645.06, 0.00, 40, 0),
(109, 5, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-04-12', '05:45:00', '2025-04-12', '10:45:00', 'ICN - MNL', '5J187', '2025-04-17', '12:45:00', '2025-04-17', '04:00:00', 34995.06, 41495.06, 0.00, 40, 0),
(110, 5, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-04-13', '05:45:00', '2025-04-13', '10:45:00', 'ICN - MNL', '5J187', '2025-04-18', '12:45:00', '2025-04-18', '04:00:00', 34982.28, 41482.28, 0.00, 40, 0),
(111, 5, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-04-14', '05:45:00', '2025-04-14', '10:45:00', 'ICN - MNL', '5J187', '2025-04-19', '12:45:00', '2025-04-19', '04:00:00', 36452.28, 42952.28, 0.00, 40, 0),
(112, 5, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-04-15', '05:45:00', '2025-04-15', '10:45:00', 'ICN - MNL', '5J187', '2025-04-20', '12:45:00', '2025-04-20', '04:00:00', 37315.06, 43815.06, 0.00, 40, 0),
(113, 5, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-04-15', '05:45:00', '2025-04-15', '10:45:00', 'ICN - MNL', '5J187', '2025-04-20', '12:45:00', '2025-04-20', '04:00:00', 40365.06, 46865.06, 0.00, 40, 0),
(114, 5, 'E010', 'Manila', 'MNL - INC', '5J188', '2025-04-16', '05:45:00', '2025-04-16', '10:45:00', 'ICN - MNL', '5J187', '2025-04-21', '12:45:00', '2025-04-21', '04:00:00', 36470.63, 42970.63, 0.00, 40, 0),
(115, 5, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-04-17', '05:45:00', '2025-04-17', '10:45:00', 'ICN - MNL', '5J187', '2025-04-22', '12:45:00', '2025-04-22', '04:00:00', 36470.63, 42970.63, 0.00, 40, 0),
(116, 5, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-04-18', '05:45:00', '2025-04-18', '10:45:00', 'ICN - MNL', '5J187', '2025-04-23', '12:45:00', '2025-04-23', '04:00:00', 35155.11, 41655.11, 0.00, 40, 0),
(117, 5, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-04-19', '05:45:00', '2025-04-19', '10:45:00', 'ICN - MNL', '5J187', '2025-04-24', '12:45:00', '2025-04-24', '04:00:00', 35155.11, 41655.11, 0.00, 40, 0),
(118, 5, 'E008', 'Manila', 'MNL - INC', '5J188', '2025-04-23', '05:45:00', '2025-04-23', '10:45:00', 'ICN - MNL', '5J187', '2025-04-28', '12:45:00', '2025-04-28', '04:00:00', 30834.07, 37334.07, 0.00, 40, 0),
(119, 5, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-04-24', '05:45:00', '2025-04-24', '10:45:00', 'ICN - MNL', '5J187', '2025-04-29', '12:45:00', '2025-04-29', '04:00:00', 30623.07, 37123.07, 0.00, 40, 0),
(120, 5, 'E007', 'Manila', 'MNL - INC', '5J188', '2025-04-25', '05:45:00', '2025-04-25', '10:45:00', 'ICN - MNL', '5J187', '2025-04-30', '12:45:00', '2025-04-30', '04:00:00', 30830.01, 37330.01, 0.00, 40, 0),
(121, 5, 'E006', 'Manila', 'MNL - INC', '5J188', '2025-04-26', '05:45:00', '2025-04-26', '10:45:00', 'ICN - MNL', '5J187', '2025-05-01', '12:45:00', '2025-05-01', '04:00:00', 31233.51, 37233.51, 0.00, 40, 0),
(122, 5, 'E012', 'Manila', 'MNL - INC', '5J188', '2025-04-30', '05:45:00', '2025-04-30', '10:45:00', 'ICN - MNL', '5J187', '2025-05-05', '12:45:00', '2025-05-05', '04:00:00', 30844.51, 36844.51, 0.00, 40, 0),
(123, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-01', '05:45:00', '2025-05-01', '10:45:00', 'ICN - MNL', '5J187', '2025-05-06', '12:45:00', '2025-05-06', '04:00:00', 29819.73, 35819.73, 0.00, 40, 0),
(124, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-02', '05:45:00', '2025-05-02', '10:45:00', 'ICN - MNL', '5J187', '2025-05-07', '12:45:00', '2025-05-07', '04:00:00', 28919.73, 34919.73, 0.00, 40, 0),
(125, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-03', '05:45:00', '2025-05-03', '10:45:00', 'ICN - MNL', '5J187', '2025-05-08', '12:45:00', '2025-05-08', '04:00:00', 29319.73, 35319.73, 0.00, 40, 0),
(126, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-03', '05:45:00', '2025-05-03', '10:45:00', 'ICN - MNL', '5J187', '2025-05-08', '12:45:00', '2025-05-08', '04:00:00', 27638.00, 34912.33, 0.00, 40, 0),
(127, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-07', '05:45:00', '2025-05-07', '10:45:00', 'ICN - MNL', '5J187', '2025-05-12', '12:45:00', '2025-04-12', '04:00:00', 28919.73, 34919.73, 0.00, 40, 0),
(128, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-08', '05:45:00', '2025-05-08', '10:45:00', 'ICN - MNL', '5J187', '2025-05-13', '12:45:00', '2025-05-13', '04:00:00', 28708.73, 34708.73, 0.00, 40, 0),
(129, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-09', '05:45:00', '2025-05-09', '10:45:00', 'ICN - MNL', '5J187', '2025-05-14', '12:45:00', '2025-05-14', '04:00:00', 28919.73, 34919.73, 0.00, 40, 0),
(130, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-09', '05:45:00', '2025-05-09', '10:45:00', 'ICN - MNL', '5J187', '2025-05-14', '12:45:00', '2025-05-14', '04:00:00', 29919.73, 35919.73, 0.00, 40, 0),
(131, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-10', '05:45:00', '2025-05-10', '10:45:00', 'ICN - MNL', '5J187', '2025-05-15', '12:45:00', '2025-05-15', '04:00:00', 28712.29, 34712.29, 0.00, 40, 0),
(132, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-11', '05:45:00', '2025-05-11', '10:45:00', 'ICN - MNL', '5J187', '2025-05-16', '12:45:00', '2025-05-16', '04:00:00', 26803.00, 34912.36, 0.00, 40, 0),
(133, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-14', '05:45:00', '2025-05-14', '10:45:00', 'ICN - MNL', '5J187', '2025-05-19', '12:45:00', '2025-05-19', '04:00:00', 28930.27, 34930.27, 0.00, 40, 0),
(134, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-15', '05:45:00', '2025-05-15', '10:45:00', 'ICN - MNL', '5J187', '2025-05-20', '12:45:00', '2025-05-20', '04:00:00', 28719.27, 34719.27, 0.00, 40, 0),
(135, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-16', '05:45:00', '2025-05-16', '10:45:00', 'ICN - MNL', '5J187', '2025-05-21', '12:45:00', '2025-05-21', '04:00:00', 28927.42, 34927.42, 0.00, 40, 0),
(136, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-16', '05:45:00', '2025-05-16', '10:45:00', 'ICN - MNL', '5J187', '2025-05-21', '12:45:00', '2025-05-21', '04:00:00', 29927.42, 35927.42, 0.00, 40, 0),
(137, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-17', '05:45:00', '2025-05-17', '10:45:00', 'ICN - MNL', '5J187', '2025-05-22', '12:45:00', '2025-05-22', '04:00:00', 28716.42, 34716.42, 0.00, 40, 0),
(138, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-18', '05:45:00', '2025-05-18', '10:45:00', 'ICN - MNL', '5J187', '2025-05-23', '12:45:00', '2025-05-23', '04:00:00', 27438.00, 34912.38, 0.00, 40, 0),
(139, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-19', '05:45:00', '2025-05-19', '10:45:00', 'ICN - MNL', '5J187', '2025-05-24', '12:45:00', '2025-05-24', '04:00:00', 27438.00, 34912.39, 0.00, 40, 0),
(140, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-21', '05:45:00', '2025-05-21', '10:45:00', 'ICN - MNL', '5J187', '2025-05-26', '12:45:00', '2025-05-26', '04:00:00', 28927.42, 34927.42, 0.00, 40, 0),
(141, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-22', '05:45:00', '2025-05-22', '10:45:00', 'ICN - MNL', '5J187', '2025-05-27', '12:45:00', '2025-05-27', '04:00:00', 28716.42, 34716.42, 0.00, 40, 0),
(142, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-23', '05:45:00', '2025-05-23', '10:45:00', 'ICN - MNL', '5J187', '2025-05-28', '12:45:00', '2025-05-28', '04:00:00', 28927.42, 34927.42, 0.00, 40, 0),
(143, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-24', '05:45:00', '2025-05-24', '10:45:00', 'ICN - MNL', '5J187', '2025-05-29', '12:45:00', '2025-05-29', '04:00:00', 28716.42, 34716.42, 0.00, 40, 0),
(144, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-26', '05:45:00', '2025-05-26', '10:45:00', 'ICN - MNL', '5J187', '2025-05-31', '12:45:00', '2025-05-31', '04:00:00', 27438.00, 34912.40, 0.00, 40, 0),
(145, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-28', '05:45:00', '2025-05-28', '10:45:00', 'ICN - MNL', '5J187', '2025-06-02', '12:45:00', '2025-06-02', '04:00:00', 28926.76, 34926.76, 0.00, 40, 0),
(146, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-29', '05:45:00', '2025-05-29', '10:45:00', 'ICN - MNL', '5J187', '2025-06-03', '12:45:00', '2025-06-03', '04:00:00', 28233.47, 34233.47, 0.00, 40, 0),
(147, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-30', '05:45:00', '2025-05-30', '10:45:00', 'ICN - MNL', '5J187', '2025-06-04', '12:45:00', '2025-06-04', '04:00:00', 28944.47, 34444.47, 0.00, 40, 0),
(148, 5, NULL, 'Manila', 'MNL - INC', '5J188', '2025-05-31', '05:45:00', '2025-05-31', '10:45:00', 'ICN - MNL', '5J187', '2025-06-05', '12:45:00', '2025-06-05', '04:00:00', 28233.47, 34233.47, 0.00, 40, 0),
(149, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-03', '05:45:00', '2025-06-03', '10:45:00', 'ICN - MNL', '5J187', '2025-06-08', '12:45:00', '2025-06-08', '04:00:00', 28444.47, 34444.47, 0.00, 40, 0),
(150, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-04', '05:45:00', '2025-06-04', '10:45:00', 'ICN - MNL', '5J187', '2025-06-09', '12:45:00', '2025-06-09', '04:00:00', 29844.47, 35844.47, 0.00, 40, 0),
(151, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-05', '05:45:00', '2025-06-05', '10:45:00', 'ICN - MNL', '5J187', '2025-06-10', '12:45:00', '2025-06-10', '04:00:00', 28233.47, 34233.47, 0.00, 40, 0),
(152, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-05', '05:45:00', '2025-06-05', '10:45:00', 'ICN - MNL', '5J187', '2025-06-10', '12:45:00', '2025-06-10', '04:00:00', 29233.47, 35233.47, 0.00, 40, 0),
(153, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-06', '05:45:00', '2025-06-06', '10:45:00', 'ICN - MNL', '5J187', '2025-06-11', '12:45:00', '2025-06-11', '04:00:00', 28444.47, 34444.47, 0.00, 40, 0),
(154, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-07', '05:45:00', '2025-06-07', '10:45:00', 'ICN - MNL', '5J187', '2025-06-12', '12:45:00', '2025-06-12', '04:00:00', 29151.69, 35051.69, 0.00, 40, 0),
(155, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-10', '05:45:00', '2025-06-10', '10:45:00', 'ICN - MNL', '5J187', '2025-06-15', '12:45:00', '2025-06-15', '04:00:00', 29959.99, 35959.99, 0.00, 40, 0),
(156, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-11', '05:45:00', '2025-06-11', '10:45:00', 'ICN - MNL', '5J187', '2025-06-16', '12:45:00', '2025-06-16', '04:00:00', 28659.99, 34659.99, 0.00, 40, 0),
(157, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-11', '05:45:00', '2025-06-11', '10:45:00', 'ICN - MNL', '5J187', '2025-06-16', '12:45:00', '2025-06-16', '04:00:00', 28959.99, 34959.99, 0.00, 40, 0),
(158, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-11', '05:45:00', '2025-06-11', '10:45:00', 'ICN - MNL', '5J187', '2025-06-16', '12:45:00', '2025-06-16', '04:00:00', 34959.99, 38959.99, 0.00, 40, 0),
(159, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-12', '05:45:00', '2025-06-12', '10:45:00', 'ICN - MNL', '5J187', '2025-06-17', '12:45:00', '2025-06-17', '04:00:00', 28326.76, 35326.76, 0.00, 40, 0),
(160, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-12', '05:45:00', '2025-06-12', '10:45:00', 'ICN - MNL', '5J187', '2025-06-17', '12:45:00', '2025-06-17', '04:00:00', 29326.76, 36326.76, 0.00, 40, 0),
(161, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-13', '05:45:00', '2025-06-13', '10:45:00', 'ICN - MNL', '5J187', '2025-06-18', '12:45:00', '2025-06-18', '04:00:00', 28447.22, 34447.22, 0.00, 40, 0),
(162, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-14', '05:45:00', '2025-06-14', '10:45:00', 'ICN - MNL', '5J187', '2025-06-19', '12:45:00', '2025-06-19', '04:00:00', 28236.22, 34236.22, 0.00, 40, 0),
(163, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-14', '05:45:00', '2025-06-14', '10:45:00', 'ICN - MNL', '5J187', '2025-06-19', '12:45:00', '2025-06-19', '04:00:00', 26038.00, 34912.43, 0.00, 40, 0),
(164, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-15', '05:45:00', '2025-06-15', '10:45:00', 'ICN - MNL', '5J187', '2025-06-20', '12:45:00', '2025-06-20', '04:00:00', 27438.00, 34912.44, 0.00, 40, 0),
(165, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-16', '05:45:00', '2025-06-16', '10:45:00', 'ICN - MNL', '5J187', '2025-06-21', '12:45:00', '2025-06-21', '04:00:00', 27438.00, 34912.45, 0.00, 40, 0),
(166, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-18', '05:45:00', '2025-06-18', '10:45:00', 'ICN - MNL', '5J187', '2025-06-23', '12:45:00', '2025-06-23', '04:00:00', 28447.22, 34447.22, 0.00, 40, 0),
(167, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-19', '05:45:00', '2025-06-19', '10:45:00', 'ICN - MNL', '5J187', '2025-06-24', '12:45:00', '2025-06-24', '04:00:00', 28236.22, 34236.22, 0.00, 40, 0),
(168, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-20', '05:45:00', '2025-06-20', '10:45:00', 'ICN - MNL', '5J187', '2025-06-25', '12:45:00', '2025-06-25', '04:00:00', 28445.66, 34445.66, 0.00, 40, 0),
(169, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-21', '05:45:00', '2025-06-21', '10:45:00', 'ICN - MNL', '5J187', '2025-06-26', '12:45:00', '2025-06-26', '04:00:00', 28232.63, 34232.63, 0.00, 40, 0),
(170, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-25', '05:45:00', '2025-06-25', '10:45:00', 'ICN - MNL', '5J187', '2025-06-30', '12:45:00', '2025-06-30', '04:00:00', 28443.63, 34443.63, 0.00, 40, 0),
(171, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-25', '05:45:00', '2025-06-25', '10:45:00', 'ICN - MNL', '5J187', '2025-06-30', '12:45:00', '2025-06-30', '04:00:00', 29443.63, 34443.63, 0.00, 40, 0),
(172, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-26', '05:45:00', '2025-06-26', '10:45:00', 'ICN - MNL', '5J187', '2025-07-01', '12:45:00', '2025-07-01', '04:00:00', 29429.57, 17429.57, 0.00, 40, 0),
(173, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-27', '05:45:00', '2025-06-27', '10:45:00', 'ICN - MNL', '5J187', '2025-07-02', '12:45:00', '2025-07-02', '04:00:00', 29429.57, 17429.57, 0.00, 40, 0),
(174, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-06-28', '05:45:00', '2025-06-28', '10:45:00', 'ICN - MNL', '5J187', '2025-07-03', '12:45:00', '2025-07-03', '04:00:00', 29429.57, 17429.57, 0.00, 40, 0),
(175, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-02', '05:45:00', '2025-07-02', '10:45:00', 'ICN - MNL', '5J187', '2025-07-07', '12:45:00', '2025-07-07', '04:00:00', 29640.57, 35640.57, 0.00, 40, 0),
(176, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-03', '05:45:00', '2025-07-03', '10:45:00', 'ICN - MNL', '5J187', '2025-07-08', '12:45:00', '2025-07-08', '04:00:00', 29429.57, 35429.57, 0.00, 40, 0),
(177, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-04', '05:45:00', '2025-07-04', '10:45:00', 'ICN - MNL', '5J187', '2025-07-09', '12:45:00', '2025-07-09', '04:00:00', 29640.57, 35640.57, 0.00, 40, 0),
(178, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-09', '05:45:00', '2025-07-09', '10:45:00', 'ICN - MNL', '5J187', '2025-07-14', '12:45:00', '2025-07-14', '04:00:00', 29640.57, 35640.57, 0.00, 40, 0),
(179, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-10', '05:45:00', '2025-07-10', '10:45:00', 'ICN - MNL', '5J187', '2025-07-15', '12:45:00', '2025-07-15', '04:00:00', 29429.57, 35429.57, 0.00, 40, 0),
(180, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-12', '05:45:00', '2025-07-12', '10:45:00', 'ICN - MNL', '5J187', '2025-07-17', '12:45:00', '2025-07-17', '04:00:00', 29429.57, 35429.57, 0.00, 40, 0),
(181, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-17', '05:45:00', '2025-07-17', '10:45:00', 'ICN - MNL', '5J187', '2025-07-22', '12:45:00', '2025-07-22', '04:00:00', 29429.57, 35429.57, 0.00, 40, 0),
(182, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-18', '05:45:00', '2025-07-18', '10:45:00', 'ICN - MNL', '5J187', '2025-07-23', '12:45:00', '2025-07-23', '04:00:00', 32560.57, 38560.57, 0.00, 40, 0),
(183, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-19', '05:45:00', '2025-07-19', '10:45:00', 'ICN - MNL', '5J187', '2025-07-24', '12:45:00', '2025-07-24', '04:00:00', 31840.57, 37840.57, 0.00, 40, 0),
(184, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-23', '05:45:00', '2025-07-23', '10:45:00', 'ICN - MNL', '5J187', '2025-07-28', '12:45:00', '2025-07-28', '04:00:00', 32871.57, 38871.57, 0.00, 40, 0),
(185, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-24', '05:45:00', '2025-07-24', '10:45:00', 'ICN - MNL', '5J187', '2025-07-29', '12:45:00', '2025-07-29', '04:00:00', 32151.57, 38151.57, 0.00, 40, 0),
(186, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-25', '05:45:00', '2025-07-25', '10:45:00', 'ICN - MNL', '5J187', '2025-07-30', '12:45:00', '2025-07-30', '04:00:00', 32871.57, 38871.57, 0.00, 40, 0),
(187, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-07-31', '05:45:00', '2025-07-31', '10:45:00', 'ICN - MNL', '5J187', '2025-08-05', '12:45:00', '2025-08-05', '04:00:00', 32771.57, 38771.57, 0.00, 40, 0),
(188, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-01', '05:45:00', '2025-08-01', '10:45:00', 'ICN - MNL', '5J187', '2025-08-06', '12:45:00', '2025-08-06', '04:00:00', 32046.64, 38064.64, 0.00, 40, 0),
(189, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-02', '05:45:00', '2025-08-02', '10:45:00', 'ICN - MNL', '5J187', '2025-08-07', '12:45:00', '2025-08-07', '04:00:00', 32046.64, 38064.64, 0.00, 40, 0),
(190, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-07', '05:45:00', '2025-08-07', '10:45:00', 'ICN - MNL', '5J187', '2025-08-12', '12:45:00', '2025-08-12', '04:00:00', 30251.57, 36251.57, 0.00, 40, 0),
(191, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-07', '05:45:00', '2025-08-07', '10:45:00', 'ICN - MNL', '5J187', '2025-08-12', '12:45:00', '2025-08-12', '04:00:00', 26738.00, 34912.51, 0.00, 40, 0),
(192, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-08', '05:45:00', '2025-08-08', '10:45:00', 'ICN - MNL', '5J187', '2025-08-13', '12:45:00', '2025-08-13', '04:00:00', 32971.57, 38971.57, 0.00, 40, 0),
(193, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-14', '05:45:00', '2025-08-14', '10:45:00', 'ICN - MNL', '5J187', '2025-08-19', '12:45:00', '2025-08-19', '04:00:00', 29851.57, 35851.57, 0.00, 40, 0),
(194, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-14', '05:45:00', '2025-08-14', '10:45:00', 'ICN - MNL', '5J187', '2025-08-19', '12:45:00', '2025-08-19', '04:00:00', 26738.00, 34912.52, 0.00, 40, 0),
(195, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-15', '05:45:00', '2025-08-15', '10:45:00', 'ICN - MNL', '5J187', '2025-08-20', '12:45:00', '2025-08-20', '04:00:00', 30251.57, 36251.57, 0.00, 40, 0),
(196, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-20', '05:45:00', '2025-08-20', '10:45:00', 'ICN - MNL', '5J187', '2025-08-25', '12:45:00', '2025-08-25', '04:00:00', 29851.57, 35851.57, 0.00, 40, 0),
(197, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-20', '05:45:00', '2025-08-20', '10:45:00', 'ICN - MNL', '5J187', '2025-08-25', '12:45:00', '2025-08-25', '04:00:00', 32851.57, 38851.57, 0.00, 40, 0),
(198, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-20', '05:45:00', '2025-08-20', '10:45:00', 'ICN - MNL', '5J187', '2025-08-25', '12:45:00', '2025-08-25', '04:00:00', 29758.00, 34912.53, 0.00, 40, 0),
(199, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-21', '05:45:00', '2025-08-21', '10:45:00', 'ICN - MNL', '5J187', '2025-08-26', '12:45:00', '2025-08-26', '04:00:00', 29851.57, 35851.57, 0.00, 40, 0),
(200, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-21', '05:45:00', '2025-08-21', '10:45:00', 'ICN - MNL', '5J187', '2025-08-26', '12:45:00', '2025-08-26', '04:00:00', 32851.57, 38851.57, 0.00, 40, 0),
(201, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-22', '05:45:00', '2025-08-22', '10:45:00', 'ICN - MNL', '5J187', '2025-08-27', '12:45:00', '2025-08-27', '04:00:00', 29851.57, 35851.57, 0.00, 40, 0),
(202, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-28', '05:45:00', '2025-08-28', '10:45:00', 'ICN - MNL', '5J187', '2025-09-02', '12:45:00', '2025-09-02', '04:00:00', 29188.94, 35188.94, 0.00, 40, 0),
(203, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-28', '05:45:00', '2025-08-28', '10:45:00', 'ICN - MNL', '5J187', '2025-09-02', '12:45:00', '2025-09-02', '04:00:00', 30188.94, 36188.94, 0.00, 40, 0),
(204, 6, NULL, 'Manila', 'MNL - INC', '5J188', '2025-08-30', '05:45:00', '2025-08-30', '10:45:00', 'ICN - MNL', '5J187', '2025-09-04', '12:45:00', '2025-09-04', '04:00:00', 29188.94, 35188.94, 0.00, 40, 0),
(205, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-04', '05:45:00', '2025-09-04', '10:45:00', 'ICN - MNL', '5J187', '2025-09-09', '12:45:00', '2025-09-09', '04:00:00', 29188.94, 35188.94, 0.00, 40, 0),
(206, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-05', '05:45:00', '2025-09-05', '10:45:00', 'ICN - MNL', '5J187', '2025-09-10', '12:45:00', '2025-09-10', '04:00:00', 29799.94, 35799.94, 0.00, 40, 0),
(207, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-06', '05:45:00', '2025-09-06', '10:45:00', 'ICN - MNL', '5J187', '2025-09-11', '12:45:00', '2025-09-11', '04:00:00', 29188.94, 35188.94, 0.00, 40, 0),
(208, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-11', '05:45:00', '2025-09-11', '10:45:00', 'ICN - MNL', '5J187', '2025-09-16', '12:45:00', '2025-09-16', '04:00:00', 30010.94, 36010.94, 0.00, 40, 0),
(209, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-12', '05:45:00', '2025-09-12', '10:45:00', 'ICN - MNL', '5J187', '2025-09-17', '12:45:00', '2025-09-17', '04:00:00', 29799.94, 35799.94, 0.00, 40, 0),
(210, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-13', '05:45:00', '2025-09-13', '10:45:00', 'ICN - MNL', '5J187', '2025-09-18', '12:45:00', '2025-09-18', '04:00:00', 29188.94, 35188.94, 0.00, 40, 0),
(211, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-18', '05:45:00', '2025-09-18', '10:45:00', 'ICN - MNL', '5J187', '2025-09-23', '12:45:00', '2025-09-23', '04:00:00', 30010.94, 36010.94, 0.00, 40, 0),
(212, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-19', '05:45:00', '2025-09-19', '10:45:00', 'ICN - MNL', '5J187', '2025-09-24', '12:45:00', '2025-09-24', '04:00:00', 29799.94, 35799.94, 0.00, 40, 0),
(213, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-20', '05:45:00', '2025-09-20', '10:45:00', 'ICN - MNL', '5J187', '2025-09-25', '12:45:00', '2025-09-25', '04:00:00', 29188.94, 35188.94, 0.00, 40, 0),
(214, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-20', '05:45:00', '2025-09-20', '10:45:00', 'ICN - MNL', '5J187', '2025-09-25', '12:45:00', '2025-09-25', '04:00:00', 30688.94, 36688.94, 0.00, 40, 0),
(215, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-25', '05:45:00', '2025-09-25', '10:45:00', 'ICN - MNL', '5J187', '2025-09-30', '12:45:00', '2025-09-30', '04:00:00', 29399.94, 35399.94, 0.00, 40, 0),
(216, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-26', '05:45:00', '2025-09-26', '10:45:00', 'ICN - MNL', '5J187', '2025-10-01', '12:45:00', '2025-10-01', '04:00:00', 29499.94, 35499.94, 0.00, 40, 0),
(217, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-26', '05:45:00', '2025-09-26', '10:45:00', 'ICN - MNL', '5J187', '2025-10-01', '12:45:00', '2025-10-01', '04:00:00', 29958.00, 34912.60, 0.00, 40, 0),
(218, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-27', '05:45:00', '2025-09-27', '10:45:00', 'ICN - MNL', '5J187', '2025-10-02', '12:45:00', '2025-10-02', '04:00:00', 29288.94, 35288.94, 0.00, 40, 0),
(219, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-27', '05:45:00', '2025-09-27', '10:45:00', 'ICN - MNL', '5J187', '2025-10-02', '12:45:00', '2025-10-02', '04:00:00', 31288.94, 37288.94, 0.00, 40, 0),
(220, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-09-30', '05:45:00', '2025-09-30', '10:45:00', 'ICN - MNL', '5J187', '2025-10-05', '12:45:00', '2025-10-05', '04:00:00', 30487.68, 36487.68, 0.00, 40, 0),
(221, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-01', '05:45:00', '2025-10-01', '10:45:00', 'ICN - MNL', '5J187', '2025-10-06', '12:45:00', '2025-10-06', '04:00:00', 30587.68, 36587.68, 0.00, 40, 0),
(222, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-02', '05:45:00', '2025-10-02', '10:45:00', 'ICN - MNL', '5J187', '2025-10-07', '12:45:00', '2025-10-07', '04:00:00', 31298.68, 37298.68, 0.00, 40, 0),
(223, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-02', '05:45:00', '2025-10-02', '10:45:00', 'ICN - MNL', '5J187', '2025-10-07', '12:45:00', '2025-10-07', '04:00:00', 32298.68, 38298.68, 0.00, 40, 0),
(224, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-03', '05:45:00', '2025-10-03', '10:45:00', 'ICN - MNL', '5J187', '2025-10-08', '12:45:00', '2025-10-08', '04:00:00', 31798.68, 37798.68, 0.00, 40, 0),
(225, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-04', '05:45:00', '2025-10-04', '10:45:00', 'ICN - MNL', '5J187', '2025-10-09', '12:45:00', '2025-10-09', '04:00:00', 31299.03, 37299.03, 0.00, 40, 0),
(226, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-07', '05:45:00', '2025-10-07', '10:45:00', 'ICN - MNL', '5J187', '2025-10-12', '12:45:00', '2025-10-12', '04:00:00', 30588.03, 36588.03, 0.00, 40, 0),
(227, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-08', '05:45:00', '2025-10-08', '10:45:00', 'ICN - MNL', '5J187', '2025-10-13', '12:45:00', '2025-10-13', '04:00:00', 30588.03, 36588.03, 0.00, 40, 0),
(228, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-09', '05:45:00', '2025-10-09', '10:45:00', 'ICN - MNL', '5J187', '2025-10-14', '12:45:00', '2025-10-14', '04:00:00', 31199.03, 37199.03, 0.00, 40, 0),
(229, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-09', '05:45:00', '2025-10-09', '10:45:00', 'ICN - MNL', '5J187', '2025-10-14', '12:45:00', '2025-10-14', '04:00:00', 31199.03, 37199.03, 0.00, 40, 0),
(230, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-10', '05:45:00', '2025-10-10', '10:45:00', 'ICN - MNL', '5J187', '2025-10-15', '12:45:00', '2025-10-15', '04:00:00', 31988.03, 37988.03, 0.00, 40, 0),
(231, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-11', '05:45:00', '2025-10-11', '10:45:00', 'ICN - MNL', '5J187', '2025-10-16', '12:45:00', '2025-10-16', '04:00:00', 31277.03, 37277.03, 0.00, 40, 0),
(232, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-14', '05:45:00', '2025-10-14', '10:45:00', 'ICN - MNL', '5J187', '2025-10-19', '12:45:00', '2025-10-19', '04:00:00', 31177.03, 37177.03, 0.00, 40, 0),
(233, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-15', '05:45:00', '2025-10-15', '10:45:00', 'ICN - MNL', '5J187', '2025-10-20', '12:45:00', '2025-10-20', '04:00:00', 31177.03, 37177.03, 0.00, 40, 0),
(234, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-15', '05:45:00', '2025-10-15', '10:45:00', 'ICN - MNL', '5J187', '2025-10-20', '12:45:00', '2025-10-20', '04:00:00', 33177.03, 39199.03, 0.00, 40, 0),
(235, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-16', '05:45:00', '2025-10-16', '10:45:00', 'ICN - MNL', '5J187', '2025-10-21', '12:45:00', '2025-10-21', '04:00:00', 31999.03, 37999.03, 0.00, 40, 0),
(236, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-17', '05:45:00', '2025-10-17', '10:45:00', 'ICN - MNL', '5J187', '2025-10-22', '12:45:00', '2025-10-22', '04:00:00', 31177.03, 37177.03, 0.00, 40, 0),
(237, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-18', '05:45:00', '2025-10-18', '10:45:00', 'ICN - MNL', '5J187', '2025-10-23', '12:45:00', '2025-10-23', '04:00:00', 31177.03, 37177.03, 0.00, 40, 0),
(238, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-21', '05:45:00', '2025-10-21', '10:45:00', 'ICN - MNL', '5J187', '2025-10-26', '12:45:00', '2025-10-26', '04:00:00', 31180.91, 37180.91, 0.00, 40, 0),
(239, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-21', '05:45:00', '2025-10-21', '10:45:00', 'ICN - MNL', '5J187', '2025-10-26', '12:45:00', '2025-10-26', '04:00:00', 27938.00, 34912.67, 0.00, 40, 0),
(240, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-22', '05:45:00', '2025-10-22', '10:45:00', 'ICN - MNL', '5J187', '2025-10-27', '12:45:00', '2025-10-27', '04:00:00', 31280.91, 37280.91, 0.00, 40, 0),
(241, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-23', '05:45:00', '2025-10-23', '10:45:00', 'ICN - MNL', '5J187', '2025-10-28', '12:45:00', '2025-10-28', '04:00:00', 32102.91, 38102.91, 0.00, 40, 0);
INSERT INTO `flight` (`flightId`, `packageId`, `employeeId`, `origin`, `flightName`, `flightCode`, `flightDepartureDate`, `flightDepartureTime`, `flightArrivalDate`, `flightArrivalTime`, `returnFlightName`, `returnFlightCode`, `returnDepartureDate`, `returnDepartureTime`, `returnArrivalDate`, `returnArrivalTime`, `wholesalePrice`, `flightPrice`, `landPrice`, `availSeats`, `is_active`) VALUES
(242, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-24', '05:45:00', '2025-10-24', '10:45:00', 'ICN - MNL', '5J187', '2025-10-29', '12:45:00', '2025-10-29', '04:00:00', 31280.91, 37280.91, 0.00, 40, 0),
(243, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-25', '05:45:00', '2025-10-25', '10:45:00', 'ICN - MNL', '5J187', '2025-10-30', '12:45:00', '2025-10-30', '04:00:00', 31279.57, 37279.57, 0.00, 40, 0),
(244, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-28', '05:45:00', '2025-10-28', '10:45:00', 'ICN - MNL', '5J187', '2025-11-02', '12:45:00', '2025-11-02', '04:00:00', 31807.57, 37807.57, 0.00, 40, 0),
(245, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-29', '05:45:00', '2025-10-29', '10:45:00', 'ICN - MNL', '5J187', '2025-11-03', '12:45:00', '2025-11-03', '04:00:00', 31807.57, 37807.57, 0.00, 40, 0),
(246, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-30', '05:45:00', '2025-10-30', '10:45:00', 'ICN - MNL', '5J187', '2025-11-04', '12:45:00', '2025-11-04', '04:00:00', 33307.57, 39307.57, 0.00, 40, 0),
(247, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-10-31', '05:45:00', '2025-10-31', '10:45:00', 'ICN - MNL', '5J187', '2025-11-05', '12:45:00', '2025-11-05', '04:00:00', 31807.57, 37807.57, 0.00, 40, 0),
(248, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-01', '05:45:00', '2025-11-01', '10:45:00', 'ICN - MNL', '5J187', '2025-11-06', '12:45:00', '2025-11-06', '04:00:00', 29211.80, 34211.80, 0.00, 40, 0),
(249, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-04', '05:45:00', '2025-11-04', '10:45:00', 'ICN - MNL', '5J187', '2025-11-09', '12:45:00', '2025-11-09', '04:00:00', 29894.51, 34894.51, 0.00, 40, 0),
(250, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-05', '05:45:00', '2025-11-05', '10:45:00', 'ICN - MNL', '5J187', '2025-11-10', '12:45:00', '2025-11-10', '04:00:00', 29194.51, 34194.51, 0.00, 40, 0),
(251, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-06', '05:45:00', '2025-11-06', '10:45:00', 'ICN - MNL', '5J187', '2025-11-11', '12:45:00', '2025-11-11', '04:00:00', 30294.51, 35294.51, 0.00, 40, 0),
(252, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-07', '05:45:00', '2025-11-07', '10:45:00', 'ICN - MNL', '5J187', '2025-11-12', '12:45:00', '2025-11-12', '04:00:00', 29794.51, 34794.51, 0.00, 40, 0),
(253, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-08', '05:45:00', '2025-11-08', '10:45:00', 'ICN - MNL', '5J187', '2025-11-13', '12:45:00', '2025-11-13', '04:00:00', 29794.51, 34794.51, 0.00, 40, 0),
(254, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-11', '05:45:00', '2025-11-11', '10:45:00', 'ICN - MNL', '5J187', '2025-11-16', '12:45:00', '2025-11-16', '04:00:00', 29794.51, 34794.51, 0.00, 40, 0),
(255, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-12', '05:45:00', '2025-11-12', '10:45:00', 'ICN - MNL', '5J187', '2025-11-17', '12:45:00', '2025-11-17', '04:00:00', 29094.51, 34094.51, 0.00, 40, 0),
(256, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-13', '05:45:00', '2025-11-13', '10:45:00', 'ICN - MNL', '5J187', '2025-11-18', '12:45:00', '2025-11-18', '04:00:00', 29794.51, 34794.51, 0.00, 40, 0),
(257, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-14', '05:45:00', '2025-11-14', '10:45:00', 'ICN - MNL', '5J187', '2025-11-19', '12:45:00', '2025-11-19', '04:00:00', 30794.51, 35794.51, 0.00, 40, 0),
(258, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-15', '05:45:00', '2025-11-15', '10:45:00', 'ICN - MNL', '5J187', '2025-11-20', '12:45:00', '2025-11-20', '04:00:00', 29094.51, 34094.51, 0.00, 40, 0),
(259, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-18', '05:45:00', '2025-11-18', '10:45:00', 'ICN - MNL', '5J187', '2025-11-23', '12:45:00', '2025-11-23', '04:00:00', 29794.51, 34794.51, 0.00, 40, 0),
(260, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-19', '05:45:00', '2025-11-19', '10:45:00', 'ICN - MNL', '5J187', '2025-11-24', '12:45:00', '2025-11-24', '04:00:00', 29794.51, 34794.51, 0.00, 40, 0),
(261, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-20', '05:45:00', '2025-11-20', '10:45:00', 'ICN - MNL', '5J187', '2025-11-25', '12:45:00', '2025-11-25', '04:00:00', 30494.51, 35494.51, 0.00, 40, 0),
(262, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-21', '05:45:00', '2025-11-21', '10:45:00', 'ICN - MNL', '5J187', '2025-11-26', '12:45:00', '2025-11-26', '04:00:00', 29794.51, 34794.51, 0.00, 40, 0),
(263, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-22', '05:45:00', '2025-11-22', '10:45:00', 'ICN - MNL', '5J187', '2025-11-27', '12:45:00', '2025-11-27', '04:00:00', 31494.51, 36494.51, 0.00, 40, 0),
(264, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-25', '05:45:00', '2025-11-25', '10:45:00', 'ICN - MNL', '5J187', '2025-11-30', '12:45:00', '2025-11-30', '04:00:00', 29794.51, 34794.51, 0.00, 40, 0),
(265, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-26', '05:45:00', '2025-11-26', '10:45:00', 'ICN - MNL', '5J187', '2025-12-01', '12:45:00', '2025-12-01', '04:00:00', 30475.00, 35474.00, 0.00, 40, 0),
(266, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-27', '05:45:00', '2025-11-27', '10:45:00', 'ICN - MNL', '5J187', '2025-12-02', '12:45:00', '2025-12-02', '04:00:00', 31475.00, 36475.00, 0.00, 40, 0),
(267, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-28', '05:45:00', '2025-11-28', '10:45:00', 'ICN - MNL', '5J187', '2025-12-03', '12:45:00', '2025-12-03', '04:00:00', 30475.00, 35475.00, 0.00, 40, 0),
(268, 1, NULL, 'Manila', 'MNL - INC', '5J188', '2025-11-29', '05:45:00', '2025-11-29', '10:45:00', 'ICN - MNL', '5J187', '2025-12-04', '12:45:00', '2025-12-04', '04:00:00', 30475.00, 35475.00, 0.00, 40, 0),
(269, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-02', '05:45:00', '2025-12-02', '10:45:00', 'ICN - MNL', '5J187', '2025-12-07', '12:45:00', '2025-12-07', '04:00:00', 30475.00, 35475.00, 0.00, 40, 0),
(270, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-03', '05:45:00', '2025-12-03', '10:45:00', 'ICN - MNL', '5J187', '2025-12-08', '12:45:00', '2025-12-08', '04:00:00', 30475.00, 35475.00, 0.00, 40, 0),
(271, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-04', '05:45:00', '2025-12-04', '10:45:00', 'ICN - MNL', '5J187', '2025-12-09', '12:45:00', '2025-12-09', '04:00:00', 31475.00, 36475.00, 0.00, 40, 0),
(272, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-05', '05:45:00', '2025-12-05', '10:45:00', 'ICN - MNL', '5J187', '2025-12-10', '12:45:00', '2025-12-10', '04:00:00', 30475.00, 35475.00, 0.00, 40, 0),
(273, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-06', '05:45:00', '2025-12-06', '10:45:00', 'ICN - MNL', '5J187', '2025-12-11', '12:45:00', '2025-12-11', '04:00:00', 30475.00, 35475.00, 0.00, 40, 0),
(274, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-09', '05:45:00', '2025-12-09', '10:45:00', 'ICN - MNL', '5J187', '2025-12-14', '12:45:00', '2025-12-14', '04:00:00', 30975.00, 35975.00, 0.00, 40, 0),
(275, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-10', '05:45:00', '2025-12-10', '10:45:00', 'ICN - MNL', '5J187', '2025-12-15', '12:45:00', '2025-12-15', '04:00:00', 30975.00, 35975.00, 0.00, 40, 0),
(276, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-11', '05:45:00', '2025-12-11', '10:45:00', 'ICN - MNL', '5J187', '2025-12-16', '12:45:00', '2025-12-16', '04:00:00', 31475.00, 36475.00, 0.00, 40, 0),
(277, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-12', '05:45:00', '2025-12-12', '10:45:00', 'ICN - MNL', '5J187', '2025-12-17', '12:45:00', '2025-12-17', '04:00:00', 31475.00, 36475.00, 0.00, 40, 0),
(278, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-13', '05:45:00', '2025-12-13', '10:45:00', 'ICN - MNL', '5J187', '2025-12-18', '12:45:00', '2025-12-18', '04:00:00', 31675.00, 36675.00, 0.00, 40, 0),
(279, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-16', '05:45:00', '2025-12-16', '10:45:00', 'ICN - MNL', '5J187', '2025-12-21', '12:45:00', '2025-12-21', '04:00:00', 33769.51, 38769.51, 0.00, 40, 0),
(280, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-17', '05:45:00', '2025-12-17', '10:45:00', 'ICN - MNL', '5J187', '2025-12-22', '12:45:00', '2025-12-22', '04:00:00', 35269.51, 40269.51, 0.00, 40, 0),
(281, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-18', '05:45:00', '2025-12-18', '10:45:00', 'ICN - MNL', '5J187', '2025-12-23', '12:45:00', '2025-12-23', '04:00:00', 40009.51, 45009.51, 0.00, 40, 0),
(282, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-19', '05:45:00', '2025-12-19', '10:45:00', 'ICN - MNL', '5J187', '2025-12-24', '12:45:00', '2025-12-24', '04:00:00', 40788.44, 45788.44, 0.00, 40, 0),
(283, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-20', '05:45:00', '2025-12-20', '10:45:00', 'ICN - MNL', '5J187', '2025-12-25', '12:45:00', '2025-12-25', '04:00:00', 42501.03, 47501.03, 0.00, 40, 0),
(284, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-21', '05:45:00', '2025-12-21', '10:45:00', 'ICN - MNL', '5J187', '2025-12-26', '12:45:00', '2025-12-26', '04:00:00', 41501.03, 46501.03, 0.00, 40, 0),
(285, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-22', '05:45:00', '2025-12-22', '10:45:00', 'ICN - MNL', '5J187', '2025-12-27', '12:45:00', '2025-12-27', '04:00:00', 41501.03, 46501.03, 0.00, 40, 0),
(286, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-23', '05:45:00', '2025-12-23', '10:45:00', 'ICN - MNL', '5J187', '2025-12-28', '12:45:00', '2025-12-28', '04:00:00', 43181.12, 48181.12, 0.00, 40, 0),
(287, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-25', '05:45:00', '2025-12-25', '10:45:00', 'ICN - MNL', '5J187', '2025-12-30', '12:45:00', '2025-12-30', '04:00:00', 41481.12, 46481.12, 0.00, 40, 0),
(288, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-26', '05:45:00', '2025-12-26', '10:45:00', 'ICN - MNL', '5J187', '2025-12-31', '12:45:00', '2025-12-31', '04:00:00', 41476.27, 46476.27, 0.00, 40, 0),
(289, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-27', '05:45:00', '2025-12-27', '10:45:00', 'ICN - MNL', '5J187', '2026-01-01', '12:45:00', '2026-01-01', '04:00:00', 41475.03, 46475.03, 0.00, 40, 0),
(290, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-28', '05:45:00', '2025-12-28', '10:45:00', 'ICN - MNL', '5J187', '2026-01-02', '12:45:00', '2026-01-02', '04:00:00', 39975.03, 44975.03, 0.00, 40, 0),
(291, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-29', '05:45:00', '2025-12-29', '10:45:00', 'ICN - MNL', '5J187', '2026-01-03', '12:45:00', '2026-01-03', '04:00:00', 38475.34, 43475.34, 0.00, 40, 0),
(292, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-30', '05:45:00', '2025-12-30', '10:45:00', 'ICN - MNL', '5J187', '2026-01-04', '12:45:00', '2026-01-04', '04:00:00', 36974.82, 41974.82, 0.00, 40, 0),
(293, 7, NULL, 'Manila', 'MNL - INC', '5J188', '2025-12-31', '05:45:00', '2025-12-31', '10:45:00', 'ICN - MNL', '5J187', '2026-01-05', '12:45:00', '2026-01-05', '04:00:00', 35474.82, 40474.82, 0.00, 40, 0);

--
-- Triggers `flight`
--
DELIMITER $$
CREATE TRIGGER `after_flight_insert` AFTER INSERT ON `flight` FOR EACH ROW BEGIN
    -- Insert a record in agentFlightSeats for each existing agent
    INSERT INTO agentflightseats (agentId, flightId, maxSeats)
    SELECT a.agentId, NEW.flightId, 10
    FROM agent a;
END
$$
DELIMITER ;

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
  `visaStatus` varchar(50) DEFAULT NULL,
  `visaRemarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest`
--

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
(1, 'Autumn Tour Package', 19000.00),
(2, 'Busan Tour Package', 19000.00),
(3, 'Cherry Blossom Tour Package', 19000.00),
(4, 'Regular Tour Package', 19000.00),
(5, 'Spring Tour Package', 19000.00),
(6, 'Summer Tour Package', 19000.00),
(7, 'Winter Package', 19000.00);

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
  `filePath` text DEFAULT NULL,
  `paymentDate` datetime DEFAULT NULL,
  `paymentStatus` enum('Submitted','Approved','Rejected') DEFAULT NULL,
  `paymentRemarks` varchar(100) DEFAULT NULL,
  `performedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

--
-- Triggers `payment`
--
DELIMITER $$
CREATE TRIGGER `after_payment_insert` AFTER INSERT ON `payment` FOR EACH ROW BEGIN
    INSERT INTO auditpayment (
        paymentId, transactNo, actionType, actionDate, performedBy, oldValues, newValues
    )
    VALUES (
        NEW.paymentId,
        NEW.transactNo,
        'INSERT',
        CURRENT_TIMESTAMP,
        @current_user_id, -- Use the session variable for the user
        NULL,
        CONCAT(
            'New Payment Details - Transact No: ', NEW.transactNo, ', Title: ', NEW.paymentTitle, 
            ', Type: ', NEW.paymentType, ', Amount: ₱', NEW.amount, ', Status: ', NEW.paymentStatus
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_payment_update` AFTER UPDATE ON `payment` FOR EACH ROW BEGIN
    DECLARE currentStatus VARCHAR(50);
    DECLARE paymentCount INT;
    DECLARE bookingId INT;

    -- Fetch the current booking status
    SELECT b.bookingId, b.status
    INTO bookingId, currentStatus
    FROM booking b
    WHERE b.transactNo = NEW.transactNo;

    -- Count the number of approved payments
    SELECT COUNT(*) INTO paymentCount
    FROM payment
    WHERE transactNo = NEW.transactNo AND paymentStatus = 'Approved';

    -- If this is the first approved payment, update booking status
    IF paymentCount = 1 AND currentStatus != 'Confirmed' THEN
        UPDATE booking
        SET status = 'Confirmed'
        WHERE transactNo = NEW.transactNo;
    END IF;

    -- Insert audit entry for the payment update
    INSERT INTO auditpayment (
        paymentId, 
        transactNo, 
        actionType, 
        actionDate, 
        performedBy, 
        oldValues, 
        newValues
    )
    VALUES (
        OLD.paymentId,
        OLD.transactNo,
        'UPDATE',
        CURRENT_TIMESTAMP,
        NEW.performedBy, -- Ensure `updatedBy` column exists in `payment` table
        CONCAT(
            'Old Payment - Transact No: ', OLD.transactNo, ', Title: ', OLD.paymentTitle, 
            ', Type: ', OLD.paymentType, ', Amount: ₱', OLD.amount, ', Status: ', OLD.paymentStatus
        ),
        CONCAT(
            'New Payment - Transact No: ', NEW.transactNo, ', Title: ', NEW.paymentTitle, 
            ', Type: ', NEW.paymentType, ', Amount: ₱', NEW.amount, ', Status: ', NEW.paymentStatus
        )
    );
END
$$
DELIMITER ;

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
  `customRequest` varchar(100) DEFAULT NULL,
  `customAmount` decimal(10,2) DEFAULT NULL,
  `handlingFee` decimal(10,2) DEFAULT NULL,
  `pax` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `requestCost` decimal(10,2) DEFAULT NULL,
  `requestDate` datetime DEFAULT current_timestamp(),
  `requestStatus` enum('Submitted','Confirmed','Rejected','') NOT NULL,
  `requestRemarks` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

--
-- Triggers `request`
--
DELIMITER $$
CREATE TRIGGER `after_request_insert` AFTER INSERT ON `request` FOR EACH ROW BEGIN
    INSERT INTO auditrequest (
        requestId, transactNo, actionType, actionDate, performedBy, oldValues, newValues
    )
    VALUES (
        NEW.requestId,
        NEW.transactNo,
        'INSERT',
        CURRENT_TIMESTAMP,
        @current_user_id, -- Use the session variable for the user
        NULL,
        CONCAT(
            'Inserted Request for Transact No: ', NEW.transactNo, 
            ', for ', NEW.pax, ' Pax',
            IF(NEW.customAmount IS NOT NULL, CONCAT(', Custom Amount: ₱', NEW.customAmount), CONCAT(', Request Cost: ₱', NEW.requestCost)),
            ', Status: ', NEW.requestStatus
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_request_update` AFTER UPDATE ON `request` FOR EACH ROW BEGIN
    INSERT INTO auditrequest (
        requestId, transactNo, actionType, actionDate, performedBy, oldValues, newValues
    )
    VALUES (
        OLD.requestId,
        OLD.transactNo,
        'UPDATE',
        CURRENT_TIMESTAMP,
        @current_user_id, -- Dynamic session variable for the current user
        CONCAT(
            'Updated Request for Transact No: ', OLD.transactNo, 
            ', for ', OLD.pax, ' Pax',
            IF(OLD.customAmount IS NOT NULL, CONCAT(', Custom Amount: ₱', OLD.customAmount), CONCAT(', Request Cost: ₱', OLD.requestCost)),
            ', Status: ', OLD.requestStatus
        ),
        CONCAT(
            'Updated Request for Transact No: ', NEW.transactNo, 
            ', for ', NEW.pax, ' Pax',
            IF(NEW.customAmount IS NOT NULL, CONCAT(', Custom Amount: ₱', NEW.customAmount), CONCAT(', Request Cost: ₱', NEW.requestCost)),
            ', Status: ', NEW.requestStatus
        )
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `soa`
--

CREATE TABLE `soa` (
  `id` int(11) NOT NULL,
  `soaNo` varchar(50) NOT NULL,
  `branchId` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `flightId` int(11) DEFAULT NULL,
  `dateGenerated` datetime DEFAULT NULL,
  `status` enum('Unpaid','Partially Paid','Fully Paid','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `soafit`
--

CREATE TABLE `soafit` (
  `id` int(11) NOT NULL,
  `soaNo` varchar(50) NOT NULL,
  `branchId` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `dateGenerated` datetime DEFAULT NULL,
  `status` enum('Unpaid','Partially Paid','Fully Paid','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soafit`
--

INSERT INTO `soafit` (`id`, `soaNo`, `branchId`, `month`, `dateGenerated`, `status`) VALUES
(1, 'SMT-2025-00001', 1, 1, '2025-01-30 03:04:30', 'Partially Paid'),
(2, 'SMT-2025-00002', 1, 1, '2025-01-30 03:06:25', 'Partially Paid'),
(3, 'SMT-2025-00003', 1, 1, '2025-01-30 03:07:46', 'Partially Paid'),
(4, 'SMT-2025-00004', 1, 1, '2025-01-30 03:09:18', 'Partially Paid'),
(5, 'SMT-2025-00005', 1, 1, '2025-01-30 03:12:11', 'Partially Paid'),
(6, 'SMT-2025-00006', 1, 1, '2025-01-30 03:27:00', 'Partially Paid'),
(7, 'SMT-2025-00007', 1, 1, '2025-01-30 03:34:02', 'Partially Paid'),
(8, 'SMT-2025-00008', 1, 1, '2025-01-30 03:46:19', 'Partially Paid'),
(9, 'SMT-2025-00009', 1, 1, '2025-01-30 03:51:19', 'Partially Paid'),
(10, 'SMT-2025-00010', 1, 1, '2025-01-30 03:55:14', 'Partially Paid'),
(11, 'SMT-2025-00011', 1, 1, '2025-01-30 04:05:15', 'Partially Paid'),
(12, 'SMT-2025-00012', 1, 1, '2025-01-30 04:06:13', 'Partially Paid'),
(13, 'SMT-2025-00013', 1, 1, '2025-01-30 04:11:19', 'Partially Paid'),
(14, 'SMT-2025-00014', 1, 1, '2025-01-30 04:13:09', 'Partially Paid'),
(15, 'SMT-2025-00015', 1, 1, '2025-01-30 04:13:39', 'Partially Paid'),
(16, 'SMT-2025-00016', 1, 1, '2025-01-30 04:19:31', 'Partially Paid'),
(17, 'SMT-2025-00017', 1, 1, '2025-01-30 04:20:22', 'Partially Paid'),
(18, 'SMT-2025-00018', 1, 1, '2025-01-30 04:41:36', 'Partially Paid'),
(19, 'SMT-2025-00019', 1, 1, '2025-01-30 05:00:01', 'Partially Paid'),
(20, 'SMT-2025-00020', 1, 1, '2025-01-31 07:37:06', 'Partially Paid'),
(21, 'SMT-2025-00021', 1, 1, '2025-01-31 07:46:48', 'Partially Paid'),
(22, 'SMT-2025-00022', 1, 1, '2025-01-31 07:48:16', 'Partially Paid'),
(23, 'SMT-2025-00023', 1, 1, '2025-01-31 07:49:42', 'Partially Paid'),
(24, 'SMT-2025-00024', 1, 1, '2025-02-03 03:54:23', 'Partially Paid'),
(25, 'SMT-2025-00025', 1, 1, '2025-02-03 06:50:43', 'Partially Paid');

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

-- --------------------------------------------------------

--
-- Table structure for table `visarequirements`
--

CREATE TABLE `visarequirements` (
  `requirementId` int(11) NOT NULL,
  `transactNo` varchar(50) DEFAULT NULL,
  `accId` int(11) DEFAULT NULL,
  `guestId` int(11) DEFAULT NULL,
  `passport` text DEFAULT NULL,
  `permit` text DEFAULT NULL,
  `validId` text DEFAULT NULL,
  `certificate` text DEFAULT NULL,
  `guaranteedLetter` text DEFAULT NULL,
  `dateSubmitted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visarequirements`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`agentId`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `agentAccountId` (`accountId`),
  ADD KEY `agentId` (`agentId`),
  ADD KEY `branchId` (`branchId`),
  ADD KEY `agentCode` (`agentCode`);

--
-- Indexes for table `agentcomission`
--
ALTER TABLE `agentcomission`
  ADD PRIMARY KEY (`comissionId`);

--
-- Indexes for table `agentflightseats`
--
ALTER TABLE `agentflightseats`
  ADD PRIMARY KEY (`flightSeatId`),
  ADD KEY `afFlightId` (`flightId`),
  ADD KEY `afAgentId` (`agentId`);

--
-- Indexes for table `auditaccounts`
--
ALTER TABLE `auditaccounts`
  ADD PRIMARY KEY (`auditId`);

--
-- Indexes for table `auditbooking`
--
ALTER TABLE `auditbooking`
  ADD PRIMARY KEY (`auditId`),
  ADD KEY `auditBookingAccountId` (`performedBy`);

--
-- Indexes for table `auditpayment`
--
ALTER TABLE `auditpayment`
  ADD PRIMARY KEY (`auditId`),
  ADD KEY `auditPaymentAccountId` (`performedBy`);

--
-- Indexes for table `auditrequest`
--
ALTER TABLE `auditrequest`
  ADD PRIMARY KEY (`auditId`),
  ADD KEY `auditRequestAccountId` (`performedBy`);

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
-- Indexes for table `bookingcomments`
--
ALTER TABLE `bookingcomments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branchId`),
  ADD UNIQUE KEY `branchAgentCode` (`branchAgentCode`),
  ADD KEY `branchContactP` (`branchContactP`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`clientId`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `accountId` (`accountId`),
  ADD KEY `clientBranchId` (`branchId`);

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
-- Indexes for table `fit`
--
ALTER TABLE `fit`
  ADD PRIMARY KEY (`transactionNo`),
  ADD UNIQUE KEY `bookingId` (`bookingId`),
  ADD KEY `accountId` (`accountId`,`agentId`,`agentCode`),
  ADD KEY `fitAgentId` (`agentId`),
  ADD KEY `fitPackageId` (`packageId`),
  ADD KEY `fitHotelId` (`hotelId`),
  ADD KEY `fitRoomId` (`roomId`);

--
-- Indexes for table `fithotel`
--
ALTER TABLE `fithotel`
  ADD PRIMARY KEY (`hotelId`);

--
-- Indexes for table `fitpackage`
--
ALTER TABLE `fitpackage`
  ADD PRIMARY KEY (`packageId`);

--
-- Indexes for table `fitpayment`
--
ALTER TABLE `fitpayment`
  ADD PRIMARY KEY (`paymentId`),
  ADD KEY `fitPaymentAccountId` (`accountId`),
  ADD KEY `fitPaymentTransactNo` (`transactNo`);

--
-- Indexes for table `fitrooms`
--
ALTER TABLE `fitrooms`
  ADD PRIMARY KEY (`roomId`),
  ADD KEY `roomsHotelId` (`hotelId`);

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
  ADD PRIMARY KEY (`paymentId`) USING BTREE,
  ADD KEY `paymentAccountId` (`accountId`),
  ADD KEY `transactNo` (`transactNo`);

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
-- Indexes for table `soa`
--
ALTER TABLE `soa`
  ADD PRIMARY KEY (`soaNo`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `soafit`
--
ALTER TABLE `soafit`
  ADD PRIMARY KEY (`soaNo`),
  ADD UNIQUE KEY `id` (`id`);

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
  ADD PRIMARY KEY (`requirementId`),
  ADD KEY `visaGuestId` (`guestId`),
  ADD KEY `visaTransactNo` (`transactNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accountId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `agentcomission`
--
ALTER TABLE `agentcomission`
  MODIFY `comissionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `agentflightseats`
--
ALTER TABLE `agentflightseats`
  MODIFY `flightSeatId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4393;

--
-- AUTO_INCREMENT for table `auditaccounts`
--
ALTER TABLE `auditaccounts`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditbooking`
--
ALTER TABLE `auditbooking`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `auditpayment`
--
ALTER TABLE `auditpayment`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `auditrequest`
--
ALTER TABLE `auditrequest`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `bookingcomments`
--
ALTER TABLE `bookingcomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branchId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clientflight`
--
ALTER TABLE `clientflight`
  MODIFY `clientFlightId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `concern`
--
ALTER TABLE `concern`
  MODIFY `concernId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `concerndetails`
--
ALTER TABLE `concerndetails`
  MODIFY `concernDetailsId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `fit`
--
ALTER TABLE `fit`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `fithotel`
--
ALTER TABLE `fithotel`
  MODIFY `hotelId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fitpackage`
--
ALTER TABLE `fitpackage`
  MODIFY `packageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fitpayment`
--
ALTER TABLE `fitpayment`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fitrooms`
--
ALTER TABLE `fitrooms`
  MODIFY `roomId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flightId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `guestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `hotelId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `packageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `requestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `soa`
--
ALTER TABLE `soa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `soafit`
--
ALTER TABLE `soafit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `totalcost`
--
ALTER TABLE `totalcost`
  MODIFY `totalCostId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visarequirements`
--
ALTER TABLE `visarequirements`
  MODIFY `requirementId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `agentAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `agentBranchId` FOREIGN KEY (`branchId`) REFERENCES `branch` (`branchId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `branchAgentCode` FOREIGN KEY (`agentCode`) REFERENCES `branch` (`branchAgentCode`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `agentflightseats`
--
ALTER TABLE `agentflightseats`
  ADD CONSTRAINT `afAgentId` FOREIGN KEY (`agentId`) REFERENCES `agent` (`agentId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `afFlightId` FOREIGN KEY (`flightId`) REFERENCES `flight` (`flightId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auditbooking`
--
ALTER TABLE `auditbooking`
  ADD CONSTRAINT `auditBookingAccountId` FOREIGN KEY (`performedBy`) REFERENCES `accounts` (`accountId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `auditpayment`
--
ALTER TABLE `auditpayment`
  ADD CONSTRAINT `auditPaymentAccountId` FOREIGN KEY (`performedBy`) REFERENCES `accounts` (`accountId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `auditrequest`
--
ALTER TABLE `auditrequest`
  ADD CONSTRAINT `auditRequestAccountId` FOREIGN KEY (`performedBy`) REFERENCES `accounts` (`accountId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `bookingAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingAgentId` FOREIGN KEY (`agentId`) REFERENCES `agent` (`agentId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingFlightId` FOREIGN KEY (`flightId`) REFERENCES `flight` (`flightId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookingPackageId` FOREIGN KEY (`packageId`) REFERENCES `package` (`packageId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `branch`
--
ALTER TABLE `branch`
  ADD CONSTRAINT `branchContactP` FOREIGN KEY (`branchContactP`) REFERENCES `agent` (`agentId`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `clientAccId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `clientBranchId` FOREIGN KEY (`branchId`) REFERENCES `branch` (`branchId`) ON DELETE SET NULL ON UPDATE CASCADE;

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
-- Constraints for table `fit`
--
ALTER TABLE `fit`
  ADD CONSTRAINT `fitAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fitAgentId` FOREIGN KEY (`agentId`) REFERENCES `agent` (`agentId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fitHotelId` FOREIGN KEY (`hotelId`) REFERENCES `fithotel` (`hotelId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fitPackageId` FOREIGN KEY (`packageId`) REFERENCES `fitpackage` (`packageId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fitRoomId` FOREIGN KEY (`roomId`) REFERENCES `fitrooms` (`roomId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fitpayment`
--
ALTER TABLE `fitpayment`
  ADD CONSTRAINT `fitPaymentAccountId` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`accountId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fitPaymentTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `fit` (`transactionNo`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fitrooms`
--
ALTER TABLE `fitrooms`
  ADD CONSTRAINT `roomsHotelId` FOREIGN KEY (`hotelId`) REFERENCES `fithotel` (`hotelId`) ON DELETE SET NULL ON UPDATE CASCADE;

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

--
-- Constraints for table `visarequirements`
--
ALTER TABLE `visarequirements`
  ADD CONSTRAINT `visaGuestId` FOREIGN KEY (`guestId`) REFERENCES `guest` (`guestId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `visaTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `guest` (`transactNo`) ON DELETE SET NULL ON UPDATE CASCADE;

-- DELIMITER $$
-- --
-- -- Events
-- --
-- CREATE DEFINER=`root`@`localhost` EVENT `auto_cancel_unpaid_bookings` ON SCHEDULE EVERY 1 DAY STARTS '2025-02-17 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
--     UPDATE booking
--     SET status = 'Cancelled'
--     WHERE status = 'Reserved'
--     AND bookingDate < DATE_SUB(NOW(), INTERVAL 3 DAY)
--     AND NOT EXISTS (
--         SELECT 1 FROM payment 
--         WHERE payment.transactNo = booking.transactNo 
--         AND payment.paymentTitle = 'Package Payment'
--         AND payment.paymentType = 'Downpayment'
--         AND payment.paymentStatus = 'Approved'
--     );
-- END$$

-- CREATE DEFINER=`root`@`localhost` EVENT `update_booking_status` ON SCHEDULE EVERY 1 DAY STARTS '2025-02-17 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE booking b
--     JOIN flight f ON b.flightId = f.flightId
--     SET b.status = 'Complete'
--     WHERE DATE(f.returnDepartureDate) <= CURDATE()
--     AND b.status NOT IN ('Complete', 'Cancelled')$$

-- DELIMITER ;
-- COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
