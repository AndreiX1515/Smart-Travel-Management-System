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
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `flightId` int(11) NOT NULL,
  `airlineId` int(11) NOT NULL,
  `flightNumber` int(11) NOT NULL,
  `origin` varchar(50) DEFAULT NULL,
  `flightName` varchar(50) DEFAULT NULL,
  `departureDate` date DEFAULT NULL,
  `departureTime` time DEFAULT NULL,
  `arrivalDate` date DEFAULT NULL,
  `arrivalTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`flightId`, `airlineId`, `flightNumber`, `origin`, `flightName`, `departureDate`, `departureTime`, `arrivalDate`, `arrivalTime`) VALUES
(1, 1, 188, 'Manila', 'MNL - ICN', '2025-09-02', '18:35:00', '2025-09-02', '22:45:00'),
(2, 1, 187, 'Incheon', 'ICN - MNL', '2025-09-07', '01:15:00', '2025-09-07', '04:35:00'),
(3, 1, 188, 'Manila', 'MNL - ICN', '2025-09-03', '18:35:00', '2025-09-03', '22:45:00'),
(4, 1, 187, 'Incheon', 'ICN - MNL', '2025-09-08', '01:15:00', '2025-09-08', '04:35:00'),
(5, 2, 188, 'Manila', 'MNL - ICN', '2025-09-04', '18:15:00', '2025-09-04', '22:45:00'),
(6, 1, 187, 'Incheon', 'ICN - MNL', '2025-09-09', '01:15:00', '2025-09-09', '04:35:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`flightId`),
  ADD KEY `flightAirlineId` (`airlineId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flightId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `flight`
--
ALTER TABLE `flight`
  ADD CONSTRAINT `flightAirlineId` FOREIGN KEY (`airlineId`) REFERENCES `airline` (`airlineId`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
