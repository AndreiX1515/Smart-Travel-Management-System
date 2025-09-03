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
-- Table structure for table `tripflight`
--

CREATE TABLE `tripflight` (
  `tripFlightId` int(11) NOT NULL,
  `tripId` int(11) NOT NULL,
  `flightId` int(11) NOT NULL,
  `legOrder` int(11) NOT NULL,
  `legType` enum('OUTBOUND','CONNECTION','RETURN','ONE WAY','OPEN JAW') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tripflight`
--

INSERT INTO `tripflight` (`tripFlightId`, `tripId`, `flightId`, `legOrder`, `legType`) VALUES
(1, 1, 1, 1, 'OUTBOUND'),
(2, 1, 2, 2, 'RETURN'),
(3, 2, 3, 1, 'OUTBOUND'),
(4, 2, 4, 2, 'RETURN'),
(5, 4, 5, 1, 'OUTBOUND'),
(6, 4, 6, 2, 'RETURN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tripflight`
--
ALTER TABLE `tripflight`
  ADD PRIMARY KEY (`tripFlightId`),
  ADD KEY `tripId` (`tripId`),
  ADD KEY `flightId` (`flightId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tripflight`
--
ALTER TABLE `tripflight`
  MODIFY `tripFlightId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tripflight`
--
ALTER TABLE `tripflight`
  ADD CONSTRAINT `flightId` FOREIGN KEY (`flightId`) REFERENCES `flight` (`flightId`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `tripId` FOREIGN KEY (`tripId`) REFERENCES `trip` (`tripId`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
