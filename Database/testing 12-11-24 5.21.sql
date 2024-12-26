-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 10:21 AM
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
(1, 'A001', 'password1', 304553, 'active', 'agent', '2024-10-30 00:00:00'),
(2, 'A002', 'password2', 987654, 'active', 'agent', '2024-10-30 00:00:00'),
(3, 'A003', 'password3', 123456, 'active', 'agent', '2024-10-30 00:00:00'),
(4, 'A004', 'password4', 654321, 'active', 'agent', '2024-10-30 00:00:00'),
(5, 'A005', 'password5', 789012, 'active', 'agent', '2024-10-30 00:00:00'),
(6, 'E001', 'password1', 345678, 'active', 'employee', '2024-10-30 00:00:00'),
(7, 'E002', 'password2', 567890, 'active', 'employee', '2024-10-30 00:00:00'),
(8, 'E003', 'password3', 901234, 'active', 'employee', '2024-10-30 00:00:00'),
(9, 'E004', 'password4', 246810, 'active', 'employee', '2024-10-30 00:00:00'),
(10, 'E005', 'password5', 135790, 'active', 'employee', '2024-10-30 00:00:00'),
(11, 'E006', 'password6', 864209, 'active', 'employee', '2024-10-30 00:00:00'),
(12, 'E007', 'password7', 975312, 'active', 'employee', '2024-10-30 00:00:00'),
(13, 'E008', 'password8', 108642, 'active', 'employee', '2024-10-30 00:00:00'),
(14, 'E009', 'password9', 246135, 'active', 'employee', '2024-10-30 00:00:00'),
(15, 'E010', 'password10', 369258, 'active', 'employee', '2024-10-30 00:00:00'),
(16, 'E011', 'password11', 741852, 'active', 'employee', '2024-10-30 00:00:00'),
(17, 'A006', 'password6', 123456, 'active', 'agent', '2024-12-09 00:00:00'),
(18, 'A007', 'password7', 654321, 'active', 'agent', '2024-12-09 00:00:00'),
(19, 'A008', 'password8', 789123, 'active', 'agent', '2024-12-09 00:00:00'),
(20, 'A009', 'password9', 321987, 'active', 'agent', '2024-12-09 00:00:00'),
(21, 'A010', 'password10', 456789, 'active', 'agent', '2024-12-09 00:00:00');

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
  `agentRole` varchar(50) DEFAULT NULL,
  `comissionRate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`id`, `agentId`, `agentCode`, `accountId`, `branchId`, `fName`, `lName`, `mName`, `countryCode`, `contactNo`, `agentType`, `agentRole`, `comissionRate`) VALUES
(1, 'A001', 'TU1', 1, 1, 'Veronica', 'Hantazo', NULL, '+63', '9957563947', 'Retailer', 'Head Agent', NULL),
(2, 'A002', 'TU2', 2, 2, 'Amie', 'Demapindan', NULL, '+63', '9177149418', 'Retailer', 'Head Agent', NULL),
(3, 'A003', 'TU3', 3, 3, 'Julyanna', 'Francia', NULL, '+63', '9778127977', 'Wholeseller', 'Head Agent', 10),
(4, 'A004', 'TU4', 4, 4, 'Jonna', '', NULL, '+63', '9957501306', 'Wholeseller', 'Head Agent', 10),
(5, 'A005', 'TU5', 5, 5, 'Winie', 'Hantazo', NULL, '+63', '9', 'Retailer', 'Head Agent', NULL),
(6, 'A006', 'TU1', 17, NULL, 'Juan', 'Cruz', 'Andres', '+63', '9876543210', 'Retailer', 'Sub-Agent', NULL),
(7, 'A007', 'TU1', 18, NULL, 'Maria', 'Santos', 'Beatriz', '+63', '9765432101', 'Retailer', 'Sub-Agent', NULL),
(8, 'A008', 'TU1', 19, NULL, 'Jose', 'Reyes', 'Carlos', '+63', '9654321092', 'Retailer', 'Sub-Agent', NULL),
(9, 'A009', 'TU1', 20, NULL, 'Ana', 'Garcia', 'Dalisay', '+63', '9543210983', 'Retailer', 'Sub-Agent', NULL),
(10, 'A010', 'TU1', 21, NULL, 'Carlos', 'Lopez', 'Emilio', '+63', '9432109874', 'Retailer', 'Sub-Agent', NULL);

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
(1, 'A001', 1, 50),
(2, 'A001', 2, 50),
(3, 'A001', 3, 50),
(4, 'A001', 4, 50),
(5, 'A001', 5, 50),
(6, 'A001', 6, 50),
(7, 'A001', 7, 50),
(8, 'A001', 8, 50),
(9, 'A001', 9, 50),
(10, 'A001', 10, 50),
(11, 'A001', 11, 50),
(12, 'A001', 12, 50),
(13, 'A001', 13, 50),
(14, 'A001', 14, 50),
(15, 'A001', 15, 50),
(16, 'A001', 16, 50),
(17, 'A001', 17, 50),
(18, 'A001', 18, 50),
(19, 'A001', 19, 50),
(20, 'A001', 20, 50),
(21, 'A001', 21, 50),
(22, 'A001', 22, 50),
(23, 'A001', 23, 50),
(24, 'A001', 24, 50),
(25, 'A001', 25, 50),
(26, 'A001', 26, 50),
(27, 'A001', 27, 50),
(28, 'A001', 28, 50),
(29, 'A001', 29, 50),
(30, 'A001', 30, 50),
(31, 'A001', 31, 50),
(32, 'A001', 32, 50),
(33, 'A001', 33, 50),
(34, 'A001', 34, 50),
(35, 'A001', 35, 50),
(36, 'A001', 36, 50),
(37, 'A001', 37, 50),
(38, 'A001', 38, 50),
(39, 'A001', 39, 50),
(40, 'A001', 40, 50),
(41, 'A001', 41, 50),
(42, 'A001', 42, 50),
(43, 'A001', 43, 50),
(44, 'A001', 44, 50),
(45, 'A001', 45, 50),
(46, 'A001', 46, 50),
(47, 'A001', 47, 50),
(48, 'A001', 48, 50),
(49, 'A001', 49, 50),
(50, 'A001', 50, 50),
(51, 'A001', 51, 50),
(52, 'A001', 52, 50),
(53, 'A001', 53, 50),
(54, 'A001', 54, 50),
(55, 'A001', 55, 50),
(56, 'A001', 56, 50),
(57, 'A001', 57, 50),
(58, 'A001', 58, 50),
(59, 'A001', 59, 50),
(60, 'A001', 60, 50),
(61, 'A001', 61, 50),
(62, 'A001', 62, 50),
(63, 'A001', 63, 50),
(64, 'A001', 64, 50),
(65, 'A001', 65, 50),
(66, 'A001', 66, 50),
(67, 'A001', 67, 50),
(68, 'A001', 68, 50),
(69, 'A001', 69, 50),
(70, 'A001', 70, 50),
(71, 'A001', 71, 50),
(72, 'A001', 72, 50),
(73, 'A001', 73, 50),
(74, 'A001', 74, 50),
(75, 'A001', 75, 50),
(76, 'A001', 76, 50),
(77, 'A001', 77, 50),
(78, 'A001', 78, 50),
(79, 'A001', 79, 50),
(80, 'A001', 80, 50),
(81, 'A001', 81, 50),
(82, 'A001', 82, 50),
(83, 'A001', 83, 50),
(84, 'A001', 84, 50),
(85, 'A001', 85, 50),
(86, 'A001', 86, 50),
(87, 'A001', 87, 50),
(88, 'A001', 88, 50),
(89, 'A001', 89, 50),
(90, 'A001', 90, 50),
(91, 'A001', 91, 50),
(92, 'A001', 92, 50),
(93, 'A001', 93, 50),
(94, 'A001', 94, 50),
(95, 'A001', 95, 50),
(96, 'A001', 96, 50),
(97, 'A002', 1, 5),
(98, 'A002', 2, 5),
(99, 'A002', 3, 5),
(100, 'A002', 4, 5),
(101, 'A002', 5, 5),
(102, 'A002', 6, 5),
(103, 'A002', 7, 5),
(104, 'A002', 8, 5),
(105, 'A002', 9, 5),
(106, 'A002', 10, 5),
(107, 'A002', 11, 5),
(108, 'A002', 12, 5),
(109, 'A002', 13, 5),
(110, 'A002', 14, 5),
(111, 'A002', 15, 5),
(112, 'A002', 16, 5),
(113, 'A002', 17, 5),
(114, 'A002', 18, 5),
(115, 'A002', 19, 5),
(116, 'A002', 20, 5),
(117, 'A002', 21, 5),
(118, 'A002', 22, 5),
(119, 'A002', 23, 5),
(120, 'A002', 24, 5),
(121, 'A002', 25, 5),
(122, 'A002', 26, 5),
(123, 'A002', 27, 5),
(124, 'A002', 28, 5),
(125, 'A002', 29, 5),
(126, 'A002', 30, 5),
(127, 'A002', 31, 5),
(128, 'A002', 32, 5),
(129, 'A002', 33, 5),
(130, 'A002', 34, 5),
(131, 'A002', 35, 5),
(132, 'A002', 36, 5),
(133, 'A002', 37, 5),
(134, 'A002', 38, 5),
(135, 'A002', 39, 5),
(136, 'A002', 40, 5),
(137, 'A002', 41, 5),
(138, 'A002', 42, 5),
(139, 'A002', 43, 5),
(140, 'A002', 44, 5),
(141, 'A002', 45, 5),
(142, 'A002', 46, 5),
(143, 'A002', 47, 5),
(144, 'A002', 48, 5),
(145, 'A002', 49, 5),
(146, 'A002', 50, 5),
(147, 'A002', 51, 5),
(148, 'A002', 52, 5),
(149, 'A002', 53, 5),
(150, 'A002', 54, 5),
(151, 'A002', 55, 5),
(152, 'A002', 56, 5),
(153, 'A002', 57, 5),
(154, 'A002', 58, 5),
(155, 'A002', 59, 5),
(156, 'A002', 60, 5),
(157, 'A002', 61, 5),
(158, 'A002', 62, 5),
(159, 'A002', 63, 5),
(160, 'A002', 64, 5),
(161, 'A002', 65, 5),
(162, 'A002', 66, 5),
(163, 'A002', 67, 5),
(164, 'A002', 68, 5),
(165, 'A002', 69, 5),
(166, 'A002', 70, 5),
(167, 'A002', 71, 5),
(168, 'A002', 72, 5),
(169, 'A002', 73, 5),
(170, 'A002', 74, 5),
(171, 'A002', 75, 5),
(172, 'A002', 76, 5),
(173, 'A002', 77, 5),
(174, 'A002', 78, 5),
(175, 'A002', 79, 5),
(176, 'A002', 80, 5),
(177, 'A002', 81, 5),
(178, 'A002', 82, 5),
(179, 'A002', 83, 5),
(180, 'A002', 84, 5),
(181, 'A002', 85, 5),
(182, 'A002', 86, 5),
(183, 'A002', 87, 5),
(184, 'A002', 88, 5),
(185, 'A002', 89, 5),
(186, 'A002', 90, 5),
(187, 'A002', 91, 5),
(188, 'A002', 92, 5),
(189, 'A002', 93, 5),
(190, 'A002', 94, 5),
(191, 'A002', 95, 5),
(192, 'A002', 96, 5),
(193, 'A003', 1, 5),
(194, 'A003', 2, 5),
(195, 'A003', 3, 5),
(196, 'A003', 4, 5),
(197, 'A003', 5, 5),
(198, 'A003', 6, 5),
(199, 'A003', 7, 5),
(200, 'A003', 8, 5),
(201, 'A003', 9, 5),
(202, 'A003', 10, 5),
(203, 'A003', 11, 5),
(204, 'A003', 12, 5),
(205, 'A003', 13, 5),
(206, 'A003', 14, 5),
(207, 'A003', 15, 5),
(208, 'A003', 16, 5),
(209, 'A003', 17, 5),
(210, 'A003', 18, 5),
(211, 'A003', 19, 5),
(212, 'A003', 20, 5),
(213, 'A003', 21, 5),
(214, 'A003', 22, 5),
(215, 'A003', 23, 5),
(216, 'A003', 24, 5),
(217, 'A003', 25, 5),
(218, 'A003', 26, 5),
(219, 'A003', 27, 5),
(220, 'A003', 28, 5),
(221, 'A003', 29, 5),
(222, 'A003', 30, 5),
(223, 'A003', 31, 5),
(224, 'A003', 32, 5),
(225, 'A003', 33, 5),
(226, 'A003', 34, 5),
(227, 'A003', 35, 5),
(228, 'A003', 36, 5),
(229, 'A003', 37, 5),
(230, 'A003', 38, 5),
(231, 'A003', 39, 5),
(232, 'A003', 40, 5),
(233, 'A003', 41, 5),
(234, 'A003', 42, 5),
(235, 'A003', 43, 5),
(236, 'A003', 44, 5),
(237, 'A003', 45, 5),
(238, 'A003', 46, 5),
(239, 'A003', 47, 5),
(240, 'A003', 48, 5),
(241, 'A003', 49, 5),
(242, 'A003', 50, 5),
(243, 'A003', 51, 5),
(244, 'A003', 52, 5),
(245, 'A003', 53, 5),
(246, 'A003', 54, 5),
(247, 'A003', 55, 5),
(248, 'A003', 56, 5),
(249, 'A003', 57, 5),
(250, 'A003', 58, 5),
(251, 'A003', 59, 5),
(252, 'A003', 60, 5),
(253, 'A003', 61, 5),
(254, 'A003', 62, 5),
(255, 'A003', 63, 5),
(256, 'A003', 64, 5),
(257, 'A003', 65, 5),
(258, 'A003', 66, 5),
(259, 'A003', 67, 5),
(260, 'A003', 68, 5),
(261, 'A003', 69, 5),
(262, 'A003', 70, 5),
(263, 'A003', 71, 5),
(264, 'A003', 72, 5),
(265, 'A003', 73, 5),
(266, 'A003', 74, 5),
(267, 'A003', 75, 5),
(268, 'A003', 76, 5),
(269, 'A003', 77, 5),
(270, 'A003', 78, 5),
(271, 'A003', 79, 5),
(272, 'A003', 80, 5),
(273, 'A003', 81, 5),
(274, 'A003', 82, 5),
(275, 'A003', 83, 5),
(276, 'A003', 84, 5),
(277, 'A003', 85, 5),
(278, 'A003', 86, 5),
(279, 'A003', 87, 5),
(280, 'A003', 88, 5),
(281, 'A003', 89, 5),
(282, 'A003', 90, 5),
(283, 'A003', 91, 5),
(284, 'A003', 92, 5),
(285, 'A003', 93, 5),
(286, 'A003', 94, 5),
(287, 'A003', 95, 5),
(288, 'A003', 96, 5),
(289, 'A004', 1, 5),
(290, 'A004', 2, 5),
(291, 'A004', 3, 5),
(292, 'A004', 4, 5),
(293, 'A004', 5, 5),
(294, 'A004', 6, 5),
(295, 'A004', 7, 5),
(296, 'A004', 8, 5),
(297, 'A004', 9, 5),
(298, 'A004', 10, 5),
(299, 'A004', 11, 5),
(300, 'A004', 12, 5),
(301, 'A004', 13, 5),
(302, 'A004', 14, 5),
(303, 'A004', 15, 5),
(304, 'A004', 16, 5),
(305, 'A004', 17, 5),
(306, 'A004', 18, 5),
(307, 'A004', 19, 5),
(308, 'A004', 20, 5),
(309, 'A004', 21, 5),
(310, 'A004', 22, 5),
(311, 'A004', 23, 5),
(312, 'A004', 24, 5),
(313, 'A004', 25, 5),
(314, 'A004', 26, 5),
(315, 'A004', 27, 5),
(316, 'A004', 28, 5),
(317, 'A004', 29, 5),
(318, 'A004', 30, 5),
(319, 'A004', 31, 5),
(320, 'A004', 32, 5),
(321, 'A004', 33, 5),
(322, 'A004', 34, 5),
(323, 'A004', 35, 5),
(324, 'A004', 36, 5),
(325, 'A004', 37, 5),
(326, 'A004', 38, 5),
(327, 'A004', 39, 5),
(328, 'A004', 40, 5),
(329, 'A004', 41, 5),
(330, 'A004', 42, 5),
(331, 'A004', 43, 5),
(332, 'A004', 44, 5),
(333, 'A004', 45, 5),
(334, 'A004', 46, 5),
(335, 'A004', 47, 5),
(336, 'A004', 48, 5),
(337, 'A004', 49, 5),
(338, 'A004', 50, 5),
(339, 'A004', 51, 5),
(340, 'A004', 52, 5),
(341, 'A004', 53, 5),
(342, 'A004', 54, 5),
(343, 'A004', 55, 5),
(344, 'A004', 56, 5),
(345, 'A004', 57, 5),
(346, 'A004', 58, 5),
(347, 'A004', 59, 5),
(348, 'A004', 60, 5),
(349, 'A004', 61, 5),
(350, 'A004', 62, 5),
(351, 'A004', 63, 5),
(352, 'A004', 64, 5),
(353, 'A004', 65, 5),
(354, 'A004', 66, 5),
(355, 'A004', 67, 5),
(356, 'A004', 68, 5),
(357, 'A004', 69, 5),
(358, 'A004', 70, 5),
(359, 'A004', 71, 5),
(360, 'A004', 72, 5),
(361, 'A004', 73, 5),
(362, 'A004', 74, 5),
(363, 'A004', 75, 5),
(364, 'A004', 76, 5),
(365, 'A004', 77, 5),
(366, 'A004', 78, 5),
(367, 'A004', 79, 5),
(368, 'A004', 80, 5),
(369, 'A004', 81, 5),
(370, 'A004', 82, 5),
(371, 'A004', 83, 5),
(372, 'A004', 84, 5),
(373, 'A004', 85, 5),
(374, 'A004', 86, 5),
(375, 'A004', 87, 5),
(376, 'A004', 88, 5),
(377, 'A004', 89, 5),
(378, 'A004', 90, 5),
(379, 'A004', 91, 5),
(380, 'A004', 92, 5),
(381, 'A004', 93, 5),
(382, 'A004', 94, 5),
(383, 'A004', 95, 5),
(384, 'A004', 96, 5),
(385, 'A005', 1, 5),
(386, 'A005', 2, 5),
(387, 'A005', 3, 5),
(388, 'A005', 4, 5),
(389, 'A005', 5, 5),
(390, 'A005', 6, 5),
(391, 'A005', 7, 5),
(392, 'A005', 8, 5),
(393, 'A005', 9, 5),
(394, 'A005', 10, 5),
(395, 'A005', 11, 5),
(396, 'A005', 12, 5),
(397, 'A005', 13, 5),
(398, 'A005', 14, 5),
(399, 'A005', 15, 5),
(400, 'A005', 16, 5),
(401, 'A005', 17, 5),
(402, 'A005', 18, 5),
(403, 'A005', 19, 5),
(404, 'A005', 20, 5),
(405, 'A005', 21, 5),
(406, 'A005', 22, 5),
(407, 'A005', 23, 5),
(408, 'A005', 24, 5),
(409, 'A005', 25, 5),
(410, 'A005', 26, 5),
(411, 'A005', 27, 5),
(412, 'A005', 28, 5),
(413, 'A005', 29, 5),
(414, 'A005', 30, 5),
(415, 'A005', 31, 5),
(416, 'A005', 32, 5),
(417, 'A005', 33, 5),
(418, 'A005', 34, 5),
(419, 'A005', 35, 5),
(420, 'A005', 36, 5),
(421, 'A005', 37, 5),
(422, 'A005', 38, 5),
(423, 'A005', 39, 5),
(424, 'A005', 40, 5),
(425, 'A005', 41, 5),
(426, 'A005', 42, 5),
(427, 'A005', 43, 5),
(428, 'A005', 44, 5),
(429, 'A005', 45, 5),
(430, 'A005', 46, 5),
(431, 'A005', 47, 5),
(432, 'A005', 48, 5),
(433, 'A005', 49, 5),
(434, 'A005', 50, 5),
(435, 'A005', 51, 5),
(436, 'A005', 52, 5),
(437, 'A005', 53, 5),
(438, 'A005', 54, 5),
(439, 'A005', 55, 5),
(440, 'A005', 56, 5),
(441, 'A005', 57, 5),
(442, 'A005', 58, 5),
(443, 'A005', 59, 5),
(444, 'A005', 60, 5),
(445, 'A005', 61, 5),
(446, 'A005', 62, 5),
(447, 'A005', 63, 5),
(448, 'A005', 64, 5),
(449, 'A005', 65, 5),
(450, 'A005', 66, 5),
(451, 'A005', 67, 5),
(452, 'A005', 68, 5),
(453, 'A005', 69, 5),
(454, 'A005', 70, 5),
(455, 'A005', 71, 5),
(456, 'A005', 72, 5),
(457, 'A005', 73, 5),
(458, 'A005', 74, 5),
(459, 'A005', 75, 5),
(460, 'A005', 76, 5),
(461, 'A005', 77, 5),
(462, 'A005', 78, 5),
(463, 'A005', 79, 5),
(464, 'A005', 80, 5),
(465, 'A005', 81, 5),
(466, 'A005', 82, 5),
(467, 'A005', 83, 5),
(468, 'A005', 84, 5),
(469, 'A005', 85, 5),
(470, 'A005', 86, 5),
(471, 'A005', 87, 5),
(472, 'A005', 88, 5),
(473, 'A005', 89, 5),
(474, 'A005', 90, 5),
(475, 'A005', 91, 5),
(476, 'A005', 92, 5),
(477, 'A005', 93, 5),
(478, 'A005', 94, 5),
(479, 'A005', 95, 5),
(480, 'A005', 96, 5),
(481, 'A006', 1, 10),
(482, 'A006', 2, 10),
(483, 'A006', 3, 10),
(484, 'A006', 4, 10),
(485, 'A006', 5, 10),
(486, 'A006', 6, 10),
(487, 'A006', 7, 10),
(488, 'A006', 8, 10),
(489, 'A006', 9, 10),
(490, 'A006', 10, 10),
(491, 'A006', 11, 10),
(492, 'A006', 12, 10),
(493, 'A006', 13, 10),
(494, 'A006', 14, 10),
(495, 'A006', 15, 10),
(496, 'A006', 16, 10),
(497, 'A006', 17, 10),
(498, 'A006', 18, 10),
(499, 'A006', 19, 10),
(500, 'A006', 20, 10),
(501, 'A006', 21, 10),
(502, 'A006', 22, 10),
(503, 'A006', 23, 10),
(504, 'A006', 24, 10),
(505, 'A006', 25, 10),
(506, 'A006', 26, 10),
(507, 'A006', 27, 10),
(508, 'A006', 28, 10),
(509, 'A006', 29, 10),
(510, 'A006', 30, 10),
(511, 'A006', 31, 10),
(512, 'A006', 32, 10),
(513, 'A006', 33, 10),
(514, 'A006', 34, 10),
(515, 'A006', 35, 10),
(516, 'A006', 36, 10),
(517, 'A006', 37, 10),
(518, 'A006', 38, 10),
(519, 'A006', 39, 10),
(520, 'A006', 40, 10),
(521, 'A006', 41, 10),
(522, 'A006', 42, 10),
(523, 'A006', 43, 10),
(524, 'A006', 44, 10),
(525, 'A006', 45, 10),
(526, 'A006', 46, 10),
(527, 'A006', 47, 10),
(528, 'A006', 48, 10),
(529, 'A006', 49, 10),
(530, 'A006', 50, 10),
(531, 'A006', 51, 10),
(532, 'A006', 52, 10),
(533, 'A006', 53, 10),
(534, 'A006', 54, 10),
(535, 'A006', 55, 10),
(536, 'A006', 56, 10),
(537, 'A006', 57, 10),
(538, 'A006', 58, 10),
(539, 'A006', 59, 10),
(540, 'A006', 60, 10),
(541, 'A006', 61, 10),
(542, 'A006', 62, 10),
(543, 'A006', 63, 10),
(544, 'A006', 64, 10),
(545, 'A006', 65, 10),
(546, 'A006', 66, 10),
(547, 'A006', 67, 10),
(548, 'A006', 68, 10),
(549, 'A006', 69, 10),
(550, 'A006', 70, 10),
(551, 'A006', 71, 10),
(552, 'A006', 72, 10),
(553, 'A006', 73, 10),
(554, 'A006', 74, 10),
(555, 'A006', 75, 10),
(556, 'A006', 76, 10),
(557, 'A006', 77, 10),
(558, 'A006', 78, 10),
(559, 'A006', 79, 10),
(560, 'A006', 80, 10),
(561, 'A006', 81, 10),
(562, 'A006', 82, 10),
(563, 'A006', 83, 10),
(564, 'A006', 84, 10),
(565, 'A006', 85, 10),
(566, 'A006', 86, 10),
(567, 'A006', 87, 10),
(568, 'A006', 88, 10),
(569, 'A006', 89, 10),
(570, 'A006', 90, 10),
(571, 'A006', 91, 10),
(572, 'A006', 92, 10),
(573, 'A006', 93, 10),
(574, 'A006', 94, 10),
(575, 'A006', 95, 10),
(576, 'A006', 96, 10),
(577, 'A007', 1, 10),
(578, 'A007', 2, 10),
(579, 'A007', 3, 10),
(580, 'A007', 4, 10),
(581, 'A007', 5, 10),
(582, 'A007', 6, 10),
(583, 'A007', 7, 10),
(584, 'A007', 8, 10),
(585, 'A007', 9, 10),
(586, 'A007', 10, 10),
(587, 'A007', 11, 10),
(588, 'A007', 12, 10),
(589, 'A007', 13, 10),
(590, 'A007', 14, 10),
(591, 'A007', 15, 10),
(592, 'A007', 16, 10),
(593, 'A007', 17, 10),
(594, 'A007', 18, 10),
(595, 'A007', 19, 10),
(596, 'A007', 20, 10),
(597, 'A007', 21, 10),
(598, 'A007', 22, 10),
(599, 'A007', 23, 10),
(600, 'A007', 24, 10),
(601, 'A007', 25, 10),
(602, 'A007', 26, 10),
(603, 'A007', 27, 10),
(604, 'A007', 28, 10),
(605, 'A007', 29, 10),
(606, 'A007', 30, 10),
(607, 'A007', 31, 10),
(608, 'A007', 32, 10),
(609, 'A007', 33, 10),
(610, 'A007', 34, 10),
(611, 'A007', 35, 10),
(612, 'A007', 36, 10),
(613, 'A007', 37, 10),
(614, 'A007', 38, 10),
(615, 'A007', 39, 10),
(616, 'A007', 40, 10),
(617, 'A007', 41, 10),
(618, 'A007', 42, 10),
(619, 'A007', 43, 10),
(620, 'A007', 44, 10),
(621, 'A007', 45, 10),
(622, 'A007', 46, 10),
(623, 'A007', 47, 10),
(624, 'A007', 48, 10),
(625, 'A007', 49, 10),
(626, 'A007', 50, 10),
(627, 'A007', 51, 10),
(628, 'A007', 52, 10),
(629, 'A007', 53, 10),
(630, 'A007', 54, 10),
(631, 'A007', 55, 10),
(632, 'A007', 56, 10),
(633, 'A007', 57, 10),
(634, 'A007', 58, 10),
(635, 'A007', 59, 10),
(636, 'A007', 60, 10),
(637, 'A007', 61, 10),
(638, 'A007', 62, 10),
(639, 'A007', 63, 10),
(640, 'A007', 64, 10),
(641, 'A007', 65, 10),
(642, 'A007', 66, 10),
(643, 'A007', 67, 10),
(644, 'A007', 68, 10),
(645, 'A007', 69, 10),
(646, 'A007', 70, 10),
(647, 'A007', 71, 10),
(648, 'A007', 72, 10),
(649, 'A007', 73, 10),
(650, 'A007', 74, 10),
(651, 'A007', 75, 10),
(652, 'A007', 76, 10),
(653, 'A007', 77, 10),
(654, 'A007', 78, 10),
(655, 'A007', 79, 10),
(656, 'A007', 80, 10),
(657, 'A007', 81, 10),
(658, 'A007', 82, 10),
(659, 'A007', 83, 10),
(660, 'A007', 84, 10),
(661, 'A007', 85, 10),
(662, 'A007', 86, 10),
(663, 'A007', 87, 10),
(664, 'A007', 88, 10),
(665, 'A007', 89, 10),
(666, 'A007', 90, 10),
(667, 'A007', 91, 10),
(668, 'A007', 92, 10),
(669, 'A007', 93, 10),
(670, 'A007', 94, 10),
(671, 'A007', 95, 10),
(672, 'A007', 96, 10),
(673, 'A010', 1, 10),
(674, 'A010', 2, 10),
(675, 'A010', 3, 10),
(676, 'A010', 4, 10),
(677, 'A010', 5, 10),
(678, 'A010', 6, 10),
(679, 'A010', 7, 10),
(680, 'A010', 8, 10),
(681, 'A010', 9, 10),
(682, 'A010', 10, 10),
(683, 'A010', 11, 10),
(684, 'A010', 12, 10),
(685, 'A010', 13, 10),
(686, 'A010', 14, 10),
(687, 'A010', 15, 10),
(688, 'A010', 16, 10),
(689, 'A010', 17, 10),
(690, 'A010', 18, 10),
(691, 'A010', 19, 10),
(692, 'A010', 20, 10),
(693, 'A010', 21, 10),
(694, 'A010', 22, 10),
(695, 'A010', 23, 10),
(696, 'A010', 24, 10),
(697, 'A010', 25, 10),
(698, 'A010', 26, 10),
(699, 'A010', 27, 10),
(700, 'A010', 28, 10),
(701, 'A010', 29, 10),
(702, 'A010', 30, 10),
(703, 'A010', 31, 10),
(704, 'A010', 32, 10),
(705, 'A010', 33, 10),
(706, 'A010', 34, 10),
(707, 'A010', 35, 10),
(708, 'A010', 36, 10),
(709, 'A010', 37, 10),
(710, 'A010', 38, 10),
(711, 'A010', 39, 10),
(712, 'A010', 40, 10),
(713, 'A010', 41, 10),
(714, 'A010', 42, 10),
(715, 'A010', 43, 10),
(716, 'A010', 44, 10),
(717, 'A010', 45, 10),
(718, 'A010', 46, 10),
(719, 'A010', 47, 10),
(720, 'A010', 48, 10),
(721, 'A010', 49, 10),
(722, 'A010', 50, 10),
(723, 'A010', 51, 10),
(724, 'A010', 52, 10),
(725, 'A010', 53, 10),
(726, 'A010', 54, 10),
(727, 'A010', 55, 10),
(728, 'A010', 56, 10),
(729, 'A010', 57, 10),
(730, 'A010', 58, 10),
(731, 'A010', 59, 10),
(732, 'A010', 60, 10),
(733, 'A010', 61, 10),
(734, 'A010', 62, 10),
(735, 'A010', 63, 10),
(736, 'A010', 64, 10),
(737, 'A010', 65, 10),
(738, 'A010', 66, 10),
(739, 'A010', 67, 10),
(740, 'A010', 68, 10),
(741, 'A010', 69, 10),
(742, 'A010', 70, 10),
(743, 'A010', 71, 10),
(744, 'A010', 72, 10),
(745, 'A010', 73, 10),
(746, 'A010', 74, 10),
(747, 'A010', 75, 10),
(748, 'A010', 76, 10),
(749, 'A010', 77, 10),
(750, 'A010', 78, 10),
(751, 'A010', 79, 10),
(752, 'A010', 80, 10),
(753, 'A010', 81, 10),
(754, 'A010', 82, 10),
(755, 'A010', 83, 10),
(756, 'A010', 84, 10),
(757, 'A010', 85, 10),
(758, 'A010', 86, 10),
(759, 'A010', 87, 10),
(760, 'A010', 88, 10),
(761, 'A010', 89, 10),
(762, 'A010', 90, 10),
(763, 'A010', 91, 10),
(764, 'A010', 92, 10),
(765, 'A010', 93, 10),
(766, 'A010', 94, 10),
(767, 'A010', 95, 10),
(768, 'A010', 96, 10),
(769, 'A008', 1, 10),
(770, 'A008', 2, 10),
(771, 'A008', 3, 10),
(772, 'A008', 4, 10),
(773, 'A008', 5, 10),
(774, 'A008', 6, 10),
(775, 'A008', 7, 10),
(776, 'A008', 8, 10),
(777, 'A008', 9, 10),
(778, 'A008', 10, 10),
(779, 'A008', 11, 10),
(780, 'A008', 12, 10),
(781, 'A008', 13, 10),
(782, 'A008', 14, 10),
(783, 'A008', 15, 10),
(784, 'A008', 16, 10),
(785, 'A008', 17, 10),
(786, 'A008', 18, 10),
(787, 'A008', 19, 10),
(788, 'A008', 20, 10),
(789, 'A008', 21, 10),
(790, 'A008', 22, 10),
(791, 'A008', 23, 10),
(792, 'A008', 24, 10),
(793, 'A008', 25, 10),
(794, 'A008', 26, 10),
(795, 'A008', 27, 10),
(796, 'A008', 28, 10),
(797, 'A008', 29, 10),
(798, 'A008', 30, 10),
(799, 'A008', 31, 10),
(800, 'A008', 32, 10),
(801, 'A008', 33, 10),
(802, 'A008', 34, 10),
(803, 'A008', 35, 10),
(804, 'A008', 36, 10),
(805, 'A008', 37, 10),
(806, 'A008', 38, 10),
(807, 'A008', 39, 10),
(808, 'A008', 40, 10),
(809, 'A008', 41, 10),
(810, 'A008', 42, 10),
(811, 'A008', 43, 10),
(812, 'A008', 44, 10),
(813, 'A008', 45, 10),
(814, 'A008', 46, 10),
(815, 'A008', 47, 10),
(816, 'A008', 48, 10),
(817, 'A008', 49, 10),
(818, 'A008', 50, 10),
(819, 'A008', 51, 10),
(820, 'A008', 52, 10),
(821, 'A008', 53, 10),
(822, 'A008', 54, 10),
(823, 'A008', 55, 10),
(824, 'A008', 56, 10),
(825, 'A008', 57, 10),
(826, 'A008', 58, 10),
(827, 'A008', 59, 10),
(828, 'A008', 60, 10),
(829, 'A008', 61, 10),
(830, 'A008', 62, 10),
(831, 'A008', 63, 10),
(832, 'A008', 64, 10),
(833, 'A008', 65, 10),
(834, 'A008', 66, 10),
(835, 'A008', 67, 10),
(836, 'A008', 68, 10),
(837, 'A008', 69, 10),
(838, 'A008', 70, 10),
(839, 'A008', 71, 10),
(840, 'A008', 72, 10),
(841, 'A008', 73, 10),
(842, 'A008', 74, 10),
(843, 'A008', 75, 10),
(844, 'A008', 76, 10),
(845, 'A008', 77, 10),
(846, 'A008', 78, 10),
(847, 'A008', 79, 10),
(848, 'A008', 80, 10),
(849, 'A008', 81, 10),
(850, 'A008', 82, 10),
(851, 'A008', 83, 10),
(852, 'A008', 84, 10),
(853, 'A008', 85, 10),
(854, 'A008', 86, 10),
(855, 'A008', 87, 10),
(856, 'A008', 88, 10),
(857, 'A008', 89, 10),
(858, 'A008', 90, 10),
(859, 'A008', 91, 10),
(860, 'A008', 92, 10),
(861, 'A008', 93, 10),
(862, 'A008', 94, 10),
(863, 'A008', 95, 10),
(864, 'A008', 96, 10),
(865, 'A009', 1, 10),
(866, 'A009', 2, 10),
(867, 'A009', 3, 10),
(868, 'A009', 4, 10),
(869, 'A009', 5, 10),
(870, 'A009', 6, 10),
(871, 'A009', 7, 10),
(872, 'A009', 8, 10),
(873, 'A009', 9, 10),
(874, 'A009', 10, 10),
(875, 'A009', 11, 10),
(876, 'A009', 12, 10),
(877, 'A009', 13, 10),
(878, 'A009', 14, 10),
(879, 'A009', 15, 10),
(880, 'A009', 16, 10),
(881, 'A009', 17, 10),
(882, 'A009', 18, 10),
(883, 'A009', 19, 10),
(884, 'A009', 20, 10),
(885, 'A009', 21, 10),
(886, 'A009', 22, 10),
(887, 'A009', 23, 10),
(888, 'A009', 24, 10),
(889, 'A009', 25, 10),
(890, 'A009', 26, 10),
(891, 'A009', 27, 10),
(892, 'A009', 28, 10),
(893, 'A009', 29, 10),
(894, 'A009', 30, 10),
(895, 'A009', 31, 10),
(896, 'A009', 32, 10),
(897, 'A009', 33, 10),
(898, 'A009', 34, 10),
(899, 'A009', 35, 10),
(900, 'A009', 36, 10),
(901, 'A009', 37, 10),
(902, 'A009', 38, 10),
(903, 'A009', 39, 10),
(904, 'A009', 40, 10),
(905, 'A009', 41, 10),
(906, 'A009', 42, 10),
(907, 'A009', 43, 10),
(908, 'A009', 44, 10),
(909, 'A009', 45, 10),
(910, 'A009', 46, 10),
(911, 'A009', 47, 10),
(912, 'A009', 48, 10),
(913, 'A009', 49, 10),
(914, 'A009', 50, 10),
(915, 'A009', 51, 10),
(916, 'A009', 52, 10),
(917, 'A009', 53, 10),
(918, 'A009', 54, 10),
(919, 'A009', 55, 10),
(920, 'A009', 56, 10),
(921, 'A009', 57, 10),
(922, 'A009', 58, 10),
(923, 'A009', 59, 10),
(924, 'A009', 60, 10),
(925, 'A009', 61, 10),
(926, 'A009', 62, 10),
(927, 'A009', 63, 10),
(928, 'A009', 64, 10),
(929, 'A009', 65, 10),
(930, 'A009', 66, 10),
(931, 'A009', 67, 10),
(932, 'A009', 68, 10),
(933, 'A009', 69, 10),
(934, 'A009', 70, 10),
(935, 'A009', 71, 10),
(936, 'A009', 72, 10),
(937, 'A009', 73, 10),
(938, 'A009', 74, 10),
(939, 'A009', 75, 10),
(940, 'A009', 76, 10),
(941, 'A009', 77, 10),
(942, 'A009', 78, 10),
(943, 'A009', 79, 10),
(944, 'A009', 80, 10),
(945, 'A009', 81, 10),
(946, 'A009', 82, 10),
(947, 'A009', 83, 10),
(948, 'A009', 84, 10),
(949, 'A009', 85, 10),
(950, 'A009', 86, 10),
(951, 'A009', 87, 10),
(952, 'A009', 88, 10),
(953, 'A009', 89, 10),
(954, 'A009', 90, 10),
(955, 'A009', 91, 10),
(956, 'A009', 92, 10),
(957, 'A009', 93, 10),
(958, 'A009', 94, 10),
(959, 'A009', 95, 10),
(960, 'A009', 96, 10);

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

INSERT INTO `auditbooking` (`auditId`, `bookingId`, `transactNo`, `actionType`, `actionDate`, `performedBy`, `oldValues`, `newValues`) VALUES
(1, 1, 'TU1-000001', 'INSERT', '2024-12-09 15:36:23', 17, NULL, 'Inserted Transact No: TU1-000001, status: Pending'),
(2, 2, 'TU1-000002', 'INSERT', '2024-12-09 15:37:20', 18, NULL, 'Inserted Transact No: TU1-000002, status: Pending'),
(3, 2, 'TU1-000002', 'UPDATE', '2024-12-09 15:37:45', 7, 'Update Transact No: TU1-000002, status: Pending', 'Update Transact No: TU1-000002, status: Confirmed'),
(4, 1, 'TU1-000001', 'UPDATE', '2024-12-09 15:37:48', 7, 'Update Transact No: TU1-000001, status: Pending', 'Update Transact No: TU1-000001, status: Confirmed'),
(5, 3, 'TU1-000003', 'INSERT', '2024-12-09 16:56:38', 18, NULL, 'Inserted Transact No: TU1-000003, status: Pending'),
(6, 4, 'TU1-000004', 'INSERT', '2024-12-09 16:57:11', 18, NULL, 'Inserted Transact No: TU1-000004, status: Pending'),
(7, 3, 'TU1-000003', 'UPDATE', '2024-12-09 16:58:06', 11, 'Update Transact No: TU1-000003, status: Pending', 'Update Transact No: TU1-000003, status: Reject'),
(8, 3, 'TU1-000003', 'UPDATE', '2024-12-09 17:09:15', 6, 'Update Transact No: TU1-000003, status: Reject', 'Update Transact No: TU1-000003, status: Confirmed'),
(9, 5, 'TU1-000005', 'INSERT', '2024-12-09 17:25:02', 19, NULL, 'Inserted Transact No: TU1-000005, status: Pending'),
(10, 5, 'TU1-000005', 'UPDATE', '2024-12-09 17:25:35', 6, 'Update Transact No: TU1-000005, status: Pending', 'Update Transact No: TU1-000005, status: Confirmed'),
(11, 6, 'TU2-000006', 'INSERT', '2024-12-10 15:01:49', 2, NULL, 'Inserted Transact No: TU2-000006, status: Pending'),
(12, 4, 'TU1-000004', 'UPDATE', '2024-12-10 15:05:31', 9, 'Update Transact No: TU1-000004, status: Pending', 'Update Transact No: TU1-000004, status: Confirmed'),
(13, 6, 'TU2-000006', 'UPDATE', '2024-12-10 15:05:34', 9, 'Update Transact No: TU2-000006, status: Pending', 'Update Transact No: TU2-000006, status: Confirmed'),
(14, 7, 'TU1-000007', 'INSERT', '2024-12-11 16:46:18', 17, NULL, 'Inserted Transact No: TU1-000007, status: Pending');

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
  `performedBy` int(11) NOT NULL,
  `oldValues` text DEFAULT NULL,
  `newValues` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auditpayment`
--

INSERT INTO `auditpayment` (`auditId`, `paymentId`, `transactNo`, `actionType`, `actionDate`, `performedBy`, `oldValues`, `newValues`) VALUES
(1, 1, 'TU1-000001', 'INSERT', '2024-12-09 15:36:35', 17, NULL, 'New Payment Details - Transact No: TU1-000001, Title: Package Payment, Type: Downpayment, Amount: ₱5500.00, Status: Submitted'),
(2, 2, 'TU1-000002', 'INSERT', '2024-12-09 15:37:28', 18, NULL, 'New Payment Details - Transact No: TU1-000002, Title: Package Payment, Type: Downpayment, Amount: ₱2100.00, Status: Submitted'),
(3, 1, 'TU1-000001', 'UPDATE', '2024-12-09 15:37:54', 7, 'Old Payment Details - Transact No: TU1-000001, Title: Package Payment, Type: Downpayment, Amount: ₱5500.00, Status: Submitted', 'New Payment Details - Transact No: TU1-000001, Title: Package Payment, Type: Downpayment, Amount: ₱5500.00, Status: Approved'),
(4, 2, 'TU1-000002', 'UPDATE', '2024-12-09 15:37:56', 7, 'Old Payment Details - Transact No: TU1-000002, Title: Package Payment, Type: Downpayment, Amount: ₱2100.00, Status: Submitted', 'New Payment Details - Transact No: TU1-000002, Title: Package Payment, Type: Downpayment, Amount: ₱2100.00, Status: Approved'),
(5, 3, 'TU1-000003', 'INSERT', '2024-12-09 16:56:48', 18, NULL, 'New Payment Details - Transact No: TU1-000003, Title: Package Payment, Type: Downpayment, Amount: ₱2100.00, Status: Submitted'),
(6, 4, 'TU1-000004', 'INSERT', '2024-12-09 16:57:20', 18, NULL, 'New Payment Details - Transact No: TU1-000004, Title: Package Payment, Type: Downpayment, Amount: ₱5200.00, Status: Submitted'),
(7, 3, 'TU1-000003', 'UPDATE', '2024-12-09 17:09:26', 6, 'Old Payment Details - Transact No: TU1-000003, Title: Package Payment, Type: Downpayment, Amount: ₱2100.00, Status: Submitted', 'New Payment Details - Transact No: TU1-000003, Title: Package Payment, Type: Downpayment, Amount: ₱2100.00, Status: Approved'),
(8, 5, 'TU1-000005', 'INSERT', '2024-12-09 17:25:11', 19, NULL, 'New Payment Details - Transact No: TU1-000005, Title: Package Payment, Type: Downpayment, Amount: ₱6600.00, Status: Submitted'),
(9, 5, 'TU1-000005', 'UPDATE', '2024-12-09 17:25:43', 6, 'Old Payment Details - Transact No: TU1-000005, Title: Package Payment, Type: Downpayment, Amount: ₱6600.00, Status: Submitted', 'New Payment Details - Transact No: TU1-000005, Title: Package Payment, Type: Downpayment, Amount: ₱6600.00, Status: Approved'),
(10, 6, 'TU2-000006', 'INSERT', '2024-12-10 15:01:58', 2, NULL, 'New Payment Details - Transact No: TU2-000006, Title: Package Payment, Type: Downpayment, Amount: ₱3600.00, Status: Submitted'),
(11, 6, 'TU2-000006', 'UPDATE', '2024-12-10 15:05:17', 9, 'Old Payment Details - Transact No: TU2-000006, Title: Package Payment, Type: Downpayment, Amount: ₱3600.00, Status: Submitted', 'New Payment Details - Transact No: TU2-000006, Title: Package Payment, Type: Downpayment, Amount: ₱3600.00, Status: Approved'),
(12, 4, 'TU1-000004', 'UPDATE', '2024-12-10 15:05:19', 9, 'Old Payment Details - Transact No: TU1-000004, Title: Package Payment, Type: Downpayment, Amount: ₱5200.00, Status: Submitted', 'New Payment Details - Transact No: TU1-000004, Title: Package Payment, Type: Downpayment, Amount: ₱5200.00, Status: Approved'),
(13, 7, 'TU1-000001', 'INSERT', '2024-12-10 15:45:16', 17, NULL, 'New Payment Details - Transact No: TU1-000001, Title: Package Payment, Type: Full Payment, Amount: ₱140000.00, Status: Submitted'),
(14, 7, 'TU1-000001', 'UPDATE', '2024-12-11 10:56:08', 6, 'Old Payment Details - Transact No: TU1-000001, Title: Package Payment, Type: Full Payment, Amount: ₱140000.00, Status: Submitted', 'New Payment Details - Transact No: TU1-000001, Title: Package Payment, Type: Full Payment, Amount: ₱140000.00, Status: Rejected'),
(15, 8, 'TU1-000001', 'INSERT', '2024-12-11 16:43:17', 17, NULL, 'New Payment Details - Transact No: TU1-000001, Title: Request Payment, Type: Downpayment, Amount: ₱4400.00, Status: Submitted'),
(16, 9, 'TU1-000007', 'INSERT', '2024-12-11 16:46:26', 17, NULL, 'New Payment Details - Transact No: TU1-000007, Title: Package Payment, Type: Downpayment, Amount: ₱4400.00, Status: Submitted');

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

INSERT INTO `auditrequest` (`auditId`, `requestId`, `transactNo`, `actionType`, `actionDate`, `performedBy`, `oldValues`, `newValues`) VALUES
(1, 1, 'TU1-000001', 'INSERT', '2024-12-10 11:06:00', 17, NULL, 'Updated Request for Transact No: TU1-000001, for 4 Pax, Request Cost: ₱40000.00, Status: Submitted'),
(2, 2, 'TU1-000001', 'INSERT', '2024-12-10 13:42:28', 17, NULL, 'Inserted Request for Transact No: TU1-000001, for 2 Pax, Custom Amount: ₱8000.00, Status: Submitted'),
(3, 1, 'TU1-000001', 'UPDATE', '2024-12-10 14:37:39', 6, 'Updated Request for Transact No: TU1-000001, for 4 Pax, Request Cost: ₱40000.00, Status: Submitted', 'Updated Request for Transact No: TU1-000001, for 4 Pax, Request Cost: ₱40000.00, Status: Confirmed'),
(4, 2, 'TU1-000001', 'UPDATE', '2024-12-10 14:37:44', 6, 'Updated Request for Transact No: TU1-000001, for 2 Pax, Custom Amount: ₱8000.00, Status: Submitted', 'Updated Request for Transact No: TU1-000001, for 2 Pax, Custom Amount: ₱8000.00, Status: Confirmed'),
(5, 3, 'TU1-000001', 'INSERT', '2024-12-10 14:39:42', 17, NULL, 'Inserted Request for Transact No: TU1-000001, for 2 Pax, Request Cost: ₱2200.00, Status: Submitted'),
(6, 3, 'TU1-000001', 'UPDATE', '2024-12-10 14:40:05', 6, 'Updated Request for Transact No: TU1-000001, for 2 Pax, Request Cost: ₱2200.00, Status: Submitted', 'Updated Request for Transact No: TU1-000001, for 2 Pax, Request Cost: ₱2200.00, Status: Confirmed'),
(7, 4, 'TU1-000001', 'INSERT', '2024-12-10 15:46:01', 17, NULL, 'Inserted Request for Transact No: TU1-000001, for 2 Pax, Request Cost: ₱640.00, Status: Submitted'),
(8, 4, 'TU1-000001', 'UPDATE', '2024-12-11 10:55:56', 6, 'Updated Request for Transact No: TU1-000001, for 2 Pax, Request Cost: ₱640.00, Status: Submitted', 'Updated Request for Transact No: TU1-000001, for 2 Pax, Request Cost: ₱640.00, Status: Confirmed'),
(9, 5, 'TU1-000001', 'INSERT', '2024-12-11 14:13:47', 17, NULL, 'Inserted Request for Transact No: TU1-000001, for 2 Pax, Request Cost: ₱2200.00, Status: Submitted'),
(10, 5, 'TU1-000001', 'UPDATE', '2024-12-11 14:30:53', 6, 'Updated Request for Transact No: TU1-000001, for 2 Pax, Request Cost: ₱2200.00, Status: Submitted', 'Updated Request for Transact No: TU1-000001, for 2 Pax, Request Cost: ₱2200.00, Status: Confirmed'),
(11, 1, 'TU1-000001', 'UPDATE', '2024-12-11 14:31:46', 6, 'Updated Request for Transact No: TU1-000001, for 4 Pax, Request Cost: ₱40000.00, Status: Confirmed', 'Updated Request for Transact No: TU1-000001, for 4 Pax, Request Cost: ₱40000.00, Status: '),
(12, 1, 'TU1-000001', 'UPDATE', '2024-12-11 14:32:02', 6, 'Updated Request for Transact No: TU1-000001, for 4 Pax, Request Cost: ₱40000.00, Status: ', 'Updated Request for Transact No: TU1-000001, for 4 Pax, Request Cost: ₱40000.00, Status: Confirmed'),
(13, 2, 'TU1-000001', 'UPDATE', '2024-12-11 14:32:37', 6, 'Updated Request for Transact No: TU1-000001, for 2 Pax, Custom Amount: ₱8000.00, Status: Confirmed', 'Updated Request for Transact No: TU1-000001, for 2 Pax, Custom Amount: ₱8000.00, Status: Confirmed'),
(14, 6, 'TU1-000001', 'INSERT', '2024-12-11 16:42:41', 17, NULL, 'Inserted Request for Transact No: TU1-000001, for 3 Pax, Request Cost: ₱960.00, Status: Submitted');

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
  `bookingType` enum('Package','Land','','') DEFAULT NULL,
  `flightDetails` text DEFAULT NULL,
  `status` enum('Pending','Cancelled','Confirmed','Reject') NOT NULL,
  `remarks` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`bookingId`, `accountId`, `transactNo`, `agentId`, `agentCode`, `flightId`, `packageId`, `fName`, `lName`, `mName`, `suffix`, `countryCode`, `contactNo`, `email`, `pax`, `bookingDate`, `totalPrice`, `bookingType`, `flightDetails`, `status`, `remarks`) VALUES
(1, 17, 'TU1-000001', 'A006', 'TU1', 39, 1, 'testing1', 'testing1', 'testing1', 'Sr.', '+63', '9999999991', 'testing1@gmail.com', 4, '2024-12-09 15:36:23', 147506.12, 'Package', NULL, 'Confirmed', NULL),
(2, 18, 'TU1-000002', 'A007', 'TU1', 39, 1, 'testing2', 'testing2', 'testing2', 'Sr.', '+63', '9999999991', 'testing2@gmail.com', 2, '2024-12-09 15:37:20', 73753.06, 'Package', NULL, 'Confirmed', NULL),
(3, 18, 'TU1-000003', 'A007', 'TU1', 71, 1, 'testing2', 'testing2', 'testing2', 'II', '+63', '9999999992', 'testing2@gmail.com', 2, '2024-12-09 16:56:38', 73753.06, 'Package', NULL, 'Confirmed', NULL),
(4, 18, 'TU1-000004', 'A007', 'TU1', 71, 1, 'testing3', 'testing3', 'testing3', 'II', '+63', '9999999993', 'testing3@gmail.com', 5, '2024-12-09 16:57:11', 184382.65, 'Package', NULL, 'Confirmed', NULL),
(5, 19, 'TU1-000005', 'A008', 'TU1', 71, 1, 'testing4', 'testing4', 'testing4', 'II', '+63', '9999999994', 'testing4@gmail.com', 6, '2024-12-09 17:25:02', 221259.18, 'Package', NULL, 'Confirmed', NULL),
(6, 2, 'TU2-000006', 'A002', 'TU2', 71, 1, 'testing1', 'testing1', 'testing1', 'Sr.', '+63', '9999999991', 'testing1@gmail.com', 3, '2024-12-10 15:01:49', 110629.59, 'Package', NULL, 'Confirmed', NULL),
(7, 17, 'TU1-000007', 'A006', 'TU1', 71, 1, 'testing2', 'testing2', 'testing2', 'II', '+63', '9999999992', 'testing2@gmail.com', 2, '2024-12-11 16:46:18', 73753.06, 'Package', NULL, 'Pending', NULL);

--
-- Triggers `booking`
--
DELIMITER $$
CREATE TRIGGER `after_booking_completed` AFTER UPDATE ON `booking` FOR EACH ROW BEGIN
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
        @current_user_id,  -- Dynamic session variable for the current user
        CONCAT('Update Transact No: ', OLD.transactNo, ', status: ', OLD.status), 
        CONCAT('Update Transact No: ', OLD.transactNo, ', status: ', NEW.status)
    );
END
$$
DELIMITER ;

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
(6, 'Visa');

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
(24, 5, 'Premium', 900.00);

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
(10, 'E010', 15, 'Dorothy', 'Sample', NULL, 'Reservation Officer', NULL, NULL, 'Korea'),
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
(1, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-04', '05:45:00', '2024-12-04', '10:45:00', 'ICN - MNL', '5J187', '2024-12-09', '12:45:00', '2024-12-09', '04:00:00', 28734.88, 36234.88, 40),
(2, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2024-12-05', '05:45:00', '2024-12-05', '10:45:00', 'ICN - MNL', '5J187', '2024-12-10', '12:45:00', '2024-12-10', '04:00:00', 28876.53, 36376.53, 40),
(3, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-06', '05:45:00', '2024-12-06', '10:45:00', 'ICN - MNL', '5J187', '2024-12-11', '12:45:00', '2024-12-11', '04:00:00', 28734.88, 36234.88, 40),
(4, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-07', '05:45:00', '2024-12-07', '10:45:00', 'ICN - MNL', '5J187', '2024-12-12', '12:45:00', '2024-12-12', '04:00:00', 28734.88, 36234.88, 40),
(5, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-10', '05:45:00', '2024-12-10', '10:45:00', 'ICN - MNL', '5J187', '2024-12-15', '12:45:00', '2024-12-15', '04:00:00', 28876.53, 36376.53, 40),
(6, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-11', '05:45:00', '2024-12-11', '10:45:00', 'ICN - MNL', '5J187', '2024-12-16', '12:45:00', '2024-12-16', '04:00:00', 29134.88, 36634.88, 40),
(7, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2024-12-12', '05:45:00', '2024-12-12', '10:45:00', 'ICN - MNL', '5J187', '2024-12-17', '12:45:00', '2024-12-17', '04:00:00', 29376.53, 36876.53, 40),
(8, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-13', '05:45:00', '2024-12-13', '10:45:00', 'ICN - MNL', '5J187', '2024-12-18', '12:45:00', '2024-12-18', '04:00:00', 29634.88, 37134.88, 40),
(9, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-14', '05:45:00', '2024-12-14', '10:45:00', 'ICN - MNL', '5J187', '2024-12-19', '12:45:00', '2024-12-19', '04:00:00', 32290.27, 43290.27, 40),
(10, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-14', '05:45:00', '2024-12-14', '10:45:00', 'ICN - MNL', '5J187', '2024-12-19', '12:45:00', '2024-12-19', '04:00:00', 30534.88, 38034.88, 40),
(11, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-16', '05:45:00', '2024-12-16', '10:45:00', 'ICN - MNL', '5J187', '2024-12-21', '12:45:00', '2024-12-21', '04:00:00', 30076.53, 37576.53, 40),
(12, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2024-12-17', '05:45:00', '2024-12-17', '10:45:00', 'ICN - MNL', '5J187', '2024-12-22', '12:45:00', '2024-12-22', '04:00:00', 32354.88, 39854.88, 40),
(13, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2024-12-18', '05:45:00', '2024-12-18', '10:45:00', 'ICN - MNL', '5J187', '2024-12-23', '12:45:00', '2024-12-23', '04:00:00', 41280.27, 47280.27, 40),
(14, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-18', '05:45:00', '2024-12-18', '10:45:00', 'ICN - MNL', '5J187', '2024-12-23', '12:45:00', '2024-12-23', '04:00:00', 32354.88, 39854.88, 40),
(15, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-19', '05:45:00', '2024-12-19', '10:45:00', 'ICN - MNL', '5J187', '2024-12-24', '12:45:00', '2024-12-24', '04:00:00', 35079.88, 42579.88, 40),
(16, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2024-12-20', '05:45:00', '2024-12-20', '10:45:00', 'ICN - MNL', '5J187', '2024-12-25', '12:45:00', '2024-12-25', '04:00:00', 34284.88, 41784.88, 40),
(17, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-20', '05:45:00', '2024-12-20', '10:45:00', 'ICN - MNL', '5J187', '2024-12-25', '12:45:00', '2024-12-25', '04:00:00', 43763.77, 49763.77, 40),
(18, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-21', '05:45:00', '2024-12-21', '10:45:00', 'ICN - MNL', '5J187', '2024-12-26', '12:45:00', '2024-12-26', '04:00:00', 40249.88, 47749.88, 40),
(19, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-21', '05:45:00', '2024-12-21', '10:45:00', 'ICN - MNL', '5J187', '2024-12-26', '12:45:00', '2024-12-26', '04:00:00', 45163.77, 51163.77, 40),
(20, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2024-12-21', '05:45:00', '2024-12-21', '10:45:00', 'ICN - MNL', '5J187', '2024-12-26', '12:45:00', '2024-12-26', '04:00:00', 45163.77, 51163.77, 40),
(21, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-23', '05:45:00', '2024-12-23', '10:45:00', 'ICN - MNL', '5J187', '2024-12-28', '12:45:00', '2024-12-28', '04:00:00', 44313.77, 50313.77, 40),
(22, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2024-12-24', '05:45:00', '2024-12-24', '10:45:00', 'ICN - MNL', '5J187', '2024-12-29', '12:45:00', '2024-12-29', '04:00:00', 42004.88, 49504.88, 40),
(23, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2024-12-24', '05:45:00', '2024-12-24', '10:45:00', 'ICN - MNL', '5J187', '2024-12-29', '12:45:00', '2024-12-29', '04:00:00', 44313.77, 50313.77, 40),
(24, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-26', '05:45:00', '2024-12-26', '10:45:00', 'ICN - MNL', '5J187', '2024-12-31', '12:45:00', '2024-12-31', '04:00:00', 43574.88, 51074.88, 40),
(25, 7, 'E010', 'Manila', 'MNL - INC', '5J188', '2024-12-26', '05:45:00', '2024-12-26', '10:45:00', 'ICN - MNL', '5J187', '2024-12-31', '12:45:00', '2024-12-31', '04:00:00', 45163.77, 51163.77, 40),
(26, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-27', '05:45:00', '2024-12-27', '10:45:00', 'ICN - MNL', '5J187', '2025-01-01', '12:45:00', '2025-01-01', '04:00:00', 44554.88, 52054.88, 40),
(27, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-27', '05:45:00', '2024-12-27', '10:45:00', 'ICN - MNL', '5J187', '2025-01-01', '12:45:00', '2025-01-01', '04:00:00', 46351.28, 52351.28, 40),
(28, 7, 'E012', 'Manila', 'MNL - INC', '5J188', '2024-12-28', '05:45:00', '2024-12-28', '10:45:00', 'ICN - MNL', '5J187', '2025-01-02', '12:45:00', '2025-01-02', '04:00:00', 35079.88, 42579.88, 40),
(29, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-29', '05:45:00', '2024-12-29', '10:45:00', 'ICN - MNL', '5J187', '2025-01-03', '12:45:00', '2025-01-03', '04:00:00', 35079.88, 42579.88, 40),
(30, 7, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-30', '05:45:00', '2024-12-30', '10:45:00', 'ICN - MNL', '5J187', '2025-01-04', '12:45:00', '2025-01-04', '04:00:00', 39766.53, 45766.53, 40),
(31, 7, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-30', '05:45:00', '2024-12-30', '10:45:00', 'ICN - MNL', '5J187', '2025-01-04', '12:45:00', '2025-01-04', '04:00:00', 45770.63, 51770.63, 40),
(32, 7, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-31', '05:45:00', '2024-12-31', '10:45:00', 'ICN - MNL', '5J187', '2025-01-05', '12:45:00', '2025-01-05', '04:00:00', 35696.53, 41696.53, 40),
(33, 1, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-04', '05:45:00', '2024-12-04', '10:45:00', 'ICN - MNL', '5J187', '2024-12-09', '12:45:00', '2024-12-09', '04:00:00', 28734.88, 36234.88, 40),
(34, 2, 'E010', 'Manila', 'MNL - INC', '5J188', '2024-12-05', '05:45:00', '2024-12-05', '10:45:00', 'ICN - MNL', '5J187', '2024-12-10', '12:45:00', '2024-12-10', '04:00:00', 28876.53, 36376.53, 40),
(35, 3, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-06', '05:45:00', '2024-12-06', '10:45:00', 'ICN - MNL', '5J187', '2024-12-11', '12:45:00', '2024-12-11', '04:00:00', 28734.88, 36234.88, 40),
(36, 4, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-07', '05:45:00', '2024-12-07', '10:45:00', 'ICN - MNL', '5J187', '2024-12-12', '12:45:00', '2024-12-12', '04:00:00', 28734.88, 36234.88, 40),
(37, 5, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-10', '05:45:00', '2024-12-10', '10:45:00', 'ICN - MNL', '5J187', '2024-12-15', '12:45:00', '2024-12-15', '04:00:00', 28876.53, 36376.53, 40),
(38, 6, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-11', '05:45:00', '2024-12-11', '10:45:00', 'ICN - MNL', '5J187', '2024-12-16', '12:45:00', '2024-12-16', '04:00:00', 29134.88, 36634.88, 40),
(39, 1, 'E012', 'Manila', 'MNL - INC', '5J188', '2024-12-12', '05:45:00', '2024-12-12', '10:45:00', 'ICN - MNL', '5J187', '2024-12-17', '12:45:00', '2024-12-17', '04:00:00', 29376.53, 36876.53, 40),
(40, 2, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-13', '05:45:00', '2024-12-13', '10:45:00', 'ICN - MNL', '5J187', '2024-12-18', '12:45:00', '2024-12-18', '04:00:00', 29634.88, 37134.88, 40),
(41, 3, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-14', '05:45:00', '2024-12-14', '10:45:00', 'ICN - MNL', '5J187', '2024-12-19', '12:45:00', '2024-12-19', '04:00:00', 32290.27, 43290.27, 40),
(42, 4, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-14', '05:45:00', '2024-12-14', '10:45:00', 'ICN - MNL', '5J187', '2024-12-19', '12:45:00', '2024-12-19', '04:00:00', 30534.88, 38034.88, 40),
(43, 5, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-16', '05:45:00', '2024-12-16', '10:45:00', 'ICN - MNL', '5J187', '2024-12-21', '12:45:00', '2024-12-21', '04:00:00', 30076.53, 37576.53, 40),
(44, 6, 'E010', 'Manila', 'MNL - INC', '5J188', '2024-12-17', '05:45:00', '2024-12-17', '10:45:00', 'ICN - MNL', '5J187', '2024-12-22', '12:45:00', '2024-12-22', '04:00:00', 32354.88, 39854.88, 40),
(45, 1, 'E012', 'Manila', 'MNL - INC', '5J188', '2024-12-18', '05:45:00', '2024-12-18', '10:45:00', 'ICN - MNL', '5J187', '2024-12-23', '12:45:00', '2024-12-23', '04:00:00', 41280.27, 47280.27, 40),
(46, 2, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-18', '05:45:00', '2024-12-18', '10:45:00', 'ICN - MNL', '5J187', '2024-12-23', '12:45:00', '2024-12-23', '04:00:00', 32354.88, 39854.88, 40),
(47, 3, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-19', '05:45:00', '2024-12-19', '10:45:00', 'ICN - MNL', '5J187', '2024-12-24', '12:45:00', '2024-12-24', '04:00:00', 35079.88, 42579.88, 40),
(48, 4, 'E010', 'Manila', 'MNL - INC', '5J188', '2024-12-20', '05:45:00', '2024-12-20', '10:45:00', 'ICN - MNL', '5J187', '2024-12-25', '12:45:00', '2024-12-25', '04:00:00', 34284.88, 41784.88, 40),
(49, 5, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-20', '05:45:00', '2024-12-20', '10:45:00', 'ICN - MNL', '5J187', '2024-12-25', '12:45:00', '2024-12-25', '04:00:00', 43763.77, 49763.77, 40),
(50, 6, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-21', '05:45:00', '2024-12-21', '10:45:00', 'ICN - MNL', '5J187', '2024-12-26', '12:45:00', '2024-12-26', '04:00:00', 40249.88, 47749.88, 40),
(51, 1, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-21', '05:45:00', '2024-12-21', '10:45:00', 'ICN - MNL', '5J187', '2024-12-26', '12:45:00', '2024-12-26', '04:00:00', 45163.77, 51163.77, 40),
(52, 2, 'E012', 'Manila', 'MNL - INC', '5J188', '2024-12-21', '05:45:00', '2024-12-21', '10:45:00', 'ICN - MNL', '5J187', '2024-12-26', '12:45:00', '2024-12-26', '04:00:00', 45163.77, 51163.77, 40),
(53, 3, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-23', '05:45:00', '2024-12-23', '10:45:00', 'ICN - MNL', '5J187', '2024-12-28', '12:45:00', '2024-12-28', '04:00:00', 44313.77, 50313.77, 40),
(54, 4, 'E010', 'Manila', 'MNL - INC', '5J188', '2024-12-24', '05:45:00', '2024-12-24', '10:45:00', 'ICN - MNL', '5J187', '2024-12-29', '12:45:00', '2024-12-29', '04:00:00', 42004.88, 49504.88, 40),
(55, 5, 'E012', 'Manila', 'MNL - INC', '5J188', '2024-12-24', '05:45:00', '2024-12-24', '10:45:00', 'ICN - MNL', '5J187', '2024-12-29', '12:45:00', '2024-12-29', '04:00:00', 44313.77, 50313.77, 40),
(56, 6, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-26', '05:45:00', '2024-12-26', '10:45:00', 'ICN - MNL', '5J187', '2024-12-31', '12:45:00', '2024-12-31', '04:00:00', 43574.88, 51074.88, 40),
(57, 1, 'E010', 'Manila', 'MNL - INC', '5J188', '2024-12-26', '05:45:00', '2024-12-26', '10:45:00', 'ICN - MNL', '5J187', '2024-12-31', '12:45:00', '2024-12-31', '04:00:00', 45163.77, 51163.77, 40),
(58, 2, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-27', '05:45:00', '2024-12-27', '10:45:00', 'ICN - MNL', '5J187', '2025-01-01', '12:45:00', '2025-01-01', '04:00:00', 44554.88, 52054.88, 40),
(59, 3, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-27', '05:45:00', '2024-12-27', '10:45:00', 'ICN - MNL', '5J187', '2025-01-01', '12:45:00', '2025-01-01', '04:00:00', 46351.28, 52351.28, 40),
(60, 4, 'E012', 'Manila', 'MNL - INC', '5J188', '2024-12-28', '05:45:00', '2024-12-28', '10:45:00', 'ICN - MNL', '5J187', '2025-01-02', '12:45:00', '2025-01-02', '04:00:00', 35079.88, 42579.88, 40),
(61, 5, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-29', '05:45:00', '2024-12-29', '10:45:00', 'ICN - MNL', '5J187', '2025-01-03', '12:45:00', '2025-01-03', '04:00:00', 35079.88, 42579.88, 40),
(62, 6, 'E008', 'Manila', 'MNL - INC', '5J188', '2024-12-30', '05:45:00', '2024-12-30', '10:45:00', 'ICN - MNL', '5J187', '2025-01-04', '12:45:00', '2025-01-04', '04:00:00', 39766.53, 45766.53, 40),
(63, 1, 'E006', 'Manila', 'MNL - INC', '5J188', '2024-12-30', '05:45:00', '2024-12-30', '10:45:00', 'ICN - MNL', '5J187', '2025-01-04', '12:45:00', '2025-01-04', '04:00:00', 45770.63, 51770.63, 40),
(64, 2, 'E007', 'Manila', 'MNL - INC', '5J188', '2024-12-31', '05:45:00', '2024-12-31', '10:45:00', 'ICN - MNL', '5J187', '2025-01-05', '12:45:00', '2025-01-05', '04:00:00', 35696.53, 41696.53, 40),
(65, 1, 'E008', 'Cebu', 'CEB - ICN', '5J128', '2024-12-04', '05:45:00', '2024-12-04', '10:45:00', 'ICN - CEB', '5J129', '2024-12-09', '12:45:00', '2024-12-09', '04:00:00', 28734.88, 36234.88, 40),
(66, 2, 'E010', 'Cebu', 'CEB - ICN', '5J128', '2024-12-05', '05:45:00', '2024-12-05', '10:45:00', 'ICN - CEB', '5J129', '2024-12-10', '12:45:00', '2024-12-10', '04:00:00', 28876.53, 36376.53, 40),
(67, 3, 'E006', 'Cebu', 'CEB - INC', '5J128', '2024-12-06', '05:45:00', '2024-12-06', '10:45:00', 'INC - CEB', '5J129', '2024-12-11', '12:45:00', '2024-12-11', '04:00:00', 28734.88, 36234.88, 40),
(68, 4, 'E008', 'Cebu', 'CEB - INC', '5J128', '2024-12-07', '05:45:00', '2024-12-07', '10:45:00', 'INC - CEB', '5J129', '2024-12-12', '12:45:00', '2024-12-12', '04:00:00', 28734.88, 36234.88, 40),
(69, 5, 'E006', 'Cebu', 'CEB - INC', '5J128', '2024-12-10', '05:45:00', '2024-12-10', '10:45:00', 'ICN - CEB', '5J129', '2024-12-15', '12:45:00', '2024-12-15', '04:00:00', 28876.53, 36376.53, 40),
(70, 6, 'E007', 'Cebu', 'CEB - INC', '5J128', '2024-12-11', '05:45:00', '2024-12-11', '10:45:00', 'ICN - CEB', '5J129', '2024-12-16', '12:45:00', '2024-12-16', '04:00:00', 29134.88, 36634.88, 40),
(71, 1, 'E012', 'Cebu', 'CEB - INC', '5J128', '2024-12-12', '05:45:00', '2024-12-12', '10:45:00', 'ICN - CEB', '5J129', '2024-12-17', '12:45:00', '2024-12-17', '04:00:00', 29376.53, 36876.53, 40),
(72, 2, 'E008', 'Cebu', 'CEB - INC', '5J128', '2024-12-13', '05:45:00', '2024-12-13', '10:45:00', 'ICN - CEB', '5J129', '2024-12-18', '12:45:00', '2024-12-18', '04:00:00', 29634.88, 37134.88, 40),
(73, 3, 'E006', 'Cebu', 'CEB - INC', '5J128', '2024-12-14', '05:45:00', '2024-12-14', '10:45:00', 'ICN - CEB', '5J129', '2024-12-19', '12:45:00', '2024-12-19', '04:00:00', 32290.27, 43290.27, 40),
(74, 4, 'E007', 'Cebu', 'CEB - INC', '5J128', '2024-12-14', '05:45:00', '2024-12-14', '10:45:00', 'ICN - CEB', '5J129', '2024-12-19', '12:45:00', '2024-12-19', '04:00:00', 30534.88, 38034.88, 40),
(75, 5, 'E008', 'Cebu', 'CEB - INC', '5J128', '2024-12-16', '05:45:00', '2024-12-16', '10:45:00', 'ICN - CEB', '5J129', '2024-12-21', '12:45:00', '2024-12-21', '04:00:00', 30076.53, 37576.53, 40),
(76, 6, 'E010', 'Cebu', 'CEB - INC', '5J128', '2024-12-17', '05:45:00', '2024-12-17', '10:45:00', 'ICN - CEB', '5J129', '2024-12-22', '12:45:00', '2024-12-22', '04:00:00', 32354.88, 39854.88, 40),
(77, 1, 'E012', 'Cebu', 'CEB - INC', '5J128', '2024-12-18', '05:45:00', '2024-12-18', '10:45:00', 'ICN - CEB', '5J129', '2024-12-23', '12:45:00', '2024-12-23', '04:00:00', 41280.27, 47280.27, 40),
(78, 2, 'E007', 'Cebu', 'CEB - INC', '5J128', '2024-12-18', '05:45:00', '2024-12-18', '10:45:00', 'ICN - CEB', '5J129', '2024-12-23', '12:45:00', '2024-12-23', '04:00:00', 32354.88, 39854.88, 40),
(79, 3, 'E006', 'Cebu', 'CEB - INC', '5J128', '2024-12-19', '05:45:00', '2024-12-19', '10:45:00', 'ICN - CEB', '5J129', '2024-12-24', '12:45:00', '2024-12-24', '04:00:00', 35079.88, 42579.88, 40),
(80, 4, 'E010', 'Cebu', 'CEB - INC', '5J128', '2024-12-20', '05:45:00', '2024-12-20', '10:45:00', 'ICN - CEB', '5J129', '2024-12-25', '12:45:00', '2024-12-25', '04:00:00', 34284.88, 41784.88, 40),
(81, 5, 'E008', 'Cebu', 'CEB - INC', '5J128', '2024-12-20', '05:45:00', '2024-12-20', '10:45:00', 'ICN - CEB', '5J129', '2024-12-25', '12:45:00', '2024-12-25', '04:00:00', 43763.77, 49763.77, 40),
(82, 6, 'E007', 'Cebu', 'CEB - INC', '5J128', '2024-12-21', '05:45:00', '2024-12-21', '10:45:00', 'ICN - CEB', '5J129', '2024-12-26', '12:45:00', '2024-12-26', '04:00:00', 40249.88, 47749.88, 40),
(83, 1, 'E006', 'Cebu', 'CEB - INC', '5J128', '2024-12-21', '05:45:00', '2024-12-21', '10:45:00', 'ICN - CEB', '5J129', '2024-12-26', '12:45:00', '2024-12-26', '04:00:00', 45163.77, 51163.77, 40),
(84, 2, 'E012', 'Cebu', 'CEB - INC', '5J128', '2024-12-21', '05:45:00', '2024-12-21', '10:45:00', 'ICN - CEB', '5J129', '2024-12-26', '12:45:00', '2024-12-26', '04:00:00', 45163.77, 51163.77, 40),
(85, 3, 'E008', 'Cebu', 'CEB - INC', '5J128', '2024-12-23', '05:45:00', '2024-12-23', '10:45:00', 'ICN - CEB', '5J129', '2024-12-28', '12:45:00', '2024-12-28', '04:00:00', 44313.77, 50313.77, 40),
(86, 4, 'E010', 'Cebu', 'CEB - INC', '5J128', '2024-12-24', '05:45:00', '2024-12-24', '10:45:00', 'ICN - CEB', '5J129', '2024-12-29', '12:45:00', '2024-12-29', '04:00:00', 42004.88, 49504.88, 40),
(87, 5, 'E012', 'Cebu', 'CEB - INC', '5J128', '2024-12-24', '05:45:00', '2024-12-24', '10:45:00', 'ICN - CEB', '5J129', '2024-12-29', '12:45:00', '2024-12-29', '04:00:00', 44313.77, 50313.77, 40),
(88, 6, 'E007', 'Cebu', 'CEB - INC', '5J128', '2024-12-26', '05:45:00', '2024-12-26', '10:45:00', 'ICN - CEB', '5J129', '2024-12-31', '12:45:00', '2024-12-31', '04:00:00', 43574.88, 51074.88, 40),
(89, 1, 'E010', 'Cebu', 'CEB - INC', '5J128', '2024-12-26', '05:45:00', '2024-12-26', '10:45:00', 'ICN - CEB', '5J129', '2024-12-31', '12:45:00', '2024-12-31', '04:00:00', 45163.77, 51163.77, 40),
(90, 2, 'E008', 'Cebu', 'CEB - INC', '5J128', '2024-12-27', '05:45:00', '2024-12-27', '10:45:00', 'ICN - CEB', '5J129', '2025-01-01', '12:45:00', '2025-01-01', '04:00:00', 44554.88, 52054.88, 40),
(91, 3, 'E006', 'Cebu', 'CEB - INC', '5J128', '2024-12-27', '05:45:00', '2024-12-27', '10:45:00', 'ICN - CEB', '5J129', '2025-01-01', '12:45:00', '2025-01-01', '04:00:00', 46351.28, 52351.28, 40),
(92, 4, 'E012', 'Cebu', 'CEB - INC', '5J128', '2024-12-28', '05:45:00', '2024-12-28', '10:45:00', 'ICN - CEB', '5J129', '2025-01-02', '12:45:00', '2025-01-02', '04:00:00', 35079.88, 42579.88, 40),
(93, 5, 'E007', 'Cebu', 'CEB - INC', '5J128', '2024-12-29', '05:45:00', '2024-12-29', '10:45:00', 'ICN - CEB', '5J129', '2025-01-03', '12:45:00', '2025-01-03', '04:00:00', 35079.88, 42579.88, 40),
(94, 6, 'E008', 'Cebu', 'CEB - INC', '5J128', '2024-12-30', '05:45:00', '2024-12-30', '10:45:00', 'ICN - CEB', '5J129', '2025-01-04', '12:45:00', '2025-01-04', '04:00:00', 39766.53, 45766.53, 40),
(95, 1, 'E006', 'Cebu', 'CEB - INC', '5J128', '2024-12-30', '05:45:00', '2024-12-30', '10:45:00', 'ICN - CEB', '5J129', '2025-01-04', '12:45:00', '2025-01-04', '04:00:00', 45770.63, 51770.63, 40),
(96, 2, 'E007', 'Cebu', 'CEB - INC', '5J128', '2024-12-31', '05:45:00', '2024-12-31', '10:45:00', 'ICN - CEB', '5J129', '2025-01-05', '12:45:00', '2025-01-05', '04:00:00', 35696.53, 41696.53, 40);

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
(1, 'TU1-000001', 'testing1', 'testing1', 'testing1', 'Jr.', '2016-02-10', 8, 'Male', 'Filipino', '+63', '09999999991', NULL, NULL, 'test1@gmail.com', 'blk 51 lot 1 marosa st. naga road', NULL, 'Muntinlupa', 'NCR', '1742', 'Philippines', 'a0000001a', '2024-12-31', NULL),
(2, 'TU1-000001', 'test2', 'test2', 'test2', 'III', '2011-02-10', 13, 'Male', 'Filipino', '+63', '9999999992', NULL, NULL, 'test1@gmail.com', 'blk 1 lot 1 marosa st. naga road', NULL, 'Muntinlupa', 'NCR', '1742', 'Philippines', 'a0000002a', '2024-12-31', NULL);

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
  `filePath` varchar(99) DEFAULT NULL,
  `paymentDate` datetime DEFAULT NULL,
  `paymentStatus` enum('Submitted','Approved','Rejected') DEFAULT NULL,
  `paymentRemarks` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentId`, `transactNo`, `accountId`, `paymentTitle`, `paymentType`, `amount`, `filePath`, `paymentDate`, `paymentStatus`, `paymentRemarks`) VALUES
(1, 'TU1-000001', 17, 'Package Payment', 'Downpayment', 5500.00, 'uploads\\TU1-000001\\TU1-000001-12-09-2024_15-36-67569e03c7567.png', '2024-12-09 15:36:35', 'Approved', NULL),
(2, 'TU1-000002', 18, 'Package Payment', 'Downpayment', 2100.00, 'uploads\\TU1-000002\\TU1-000002-12-09-2024_15-37-67569e38e7231.png', '2024-12-09 15:37:28', 'Approved', NULL),
(3, 'TU1-000003', 18, 'Package Payment', 'Downpayment', 2100.00, 'uploads\\TU1-000003\\TU1-000003-12-09-2024_16-56-6756b0d0e39f1.png', '2024-12-09 16:56:48', 'Approved', NULL),
(4, 'TU1-000004', 18, 'Package Payment', 'Downpayment', 5200.00, 'uploads\\TU1-000004\\TU1-000004-12-09-2024_16-57-6756b0f000bb7.png', '2024-12-09 16:57:20', 'Approved', NULL),
(5, 'TU1-000005', 19, 'Package Payment', 'Downpayment', 6600.00, 'uploads\\TU1-000005\\TU1-000005-12-09-2024_17-25-6756b77733545.png', '2024-12-09 17:25:11', 'Approved', NULL),
(6, 'TU2-000006', 2, 'Package Payment', 'Downpayment', 3600.00, 'uploads\\TU2-000006\\TU2-000006-12-10-2024_15-01-6757e7669c04e.png', '2024-12-10 15:01:58', 'Approved', NULL),
(7, 'TU1-000001', 17, 'Package Payment', 'Full Payment', 140000.00, 'uploads\\TU1-000001\\TU1-000001-12-10-2024_15-45-6757f18caaa6f.png', '2024-12-10 15:45:16', 'Rejected', 'Not yet full payment'),
(8, 'TU1-000001', 17, 'Request Payment', 'Downpayment', 4400.00, 'uploads\\TU1-000001\\TU1-000001-12-11-2024_16-43-675950a5c1127.png', '2024-12-11 16:43:17', 'Submitted', NULL),
(9, 'TU1-000007', 17, 'Package Payment', 'Downpayment', 4400.00, 'uploads\\TU1-000007\\TU1-000007-12-11-2024_16-46-67595162c286e.png', '2024-12-11 16:46:26', 'Submitted', NULL);

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
        @current_user_id, -- Use the session variable for the user
        CONCAT(
            'Old Payment Details - Transact No: ', OLD.transactNo, ', Title: ', OLD.paymentTitle, 
            ', Type: ', OLD.paymentType, ', Amount: ₱', OLD.amount, ', Status: ', OLD.paymentStatus
        ),
        CONCAT(
            'New Payment Details - Transact No: ', NEW.transactNo, ', Title: ', NEW.paymentTitle, 
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

INSERT INTO `request` (`requestId`, `transactNo`, `accountId`, `concernId`, `concernDetailsId`, `customRequest`, `customAmount`, `handlingFee`, `pax`, `details`, `requestCost`, `requestDate`, `requestStatus`, `requestRemarks`) VALUES
(1, 'TU1-000001', 17, 3, NULL, NULL, NULL, 100.00, 4, 'samae flight', 40000.00, '2024-12-10 11:06:00', 'Confirmed', NULL),
(2, 'TU1-000001', 17, NULL, NULL, 'Infant RT', 8000.00, 400.00, 2, '', 16000.00, '2024-12-10 13:42:28', 'Confirmed', 'testing 200 handling fee'),
(3, 'TU1-000001', 17, 2, 10, NULL, NULL, NULL, 2, '', 2200.00, '2024-12-10 14:39:42', 'Confirmed', NULL),
(4, 'TU1-000001', 17, 1, 9, NULL, NULL, NULL, 2, 'testing', 640.00, '2024-12-10 15:46:01', 'Confirmed', NULL),
(5, 'TU1-000001', 17, 2, 10, NULL, NULL, 0.00, 2, 'testing', 2200.00, '2024-12-11 14:13:47', 'Confirmed', 'testing no handling fee'),
(6, 'TU1-000001', 17, 1, 8, NULL, NULL, NULL, 3, 'testing', 960.00, '2024-12-11 16:42:41', 'Submitted', NULL);

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
('07asuv3ie7aqaftk7l430hb45o', 7, '2024-12-09 08:37:37', '2024-12-09 08:37:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('0lumcgtf6ge50h45nnkcotfsm4', 2, '2024-12-11 09:20:50', '2024-12-11 09:20:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('1shj5jmrr9naak135vu550jt5q', 6, '2024-12-11 10:09:20', '2024-12-11 10:09:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('6sfhauilb0usthv1qnnbbfmipt', 18, '2024-12-11 10:06:41', '2024-12-11 10:06:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('7a4u3ch424k6uqtp1jl4sri8cu', 8, '2024-12-06 09:21:43', '2024-12-06 09:21:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('b544qr8tg8k9j75916qntlnm5l', 5, '2024-12-06 06:26:48', '2024-12-06 06:26:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('ch1ts974aon4mjsci3lhn90op9', 19, '2024-12-09 10:23:58', '2024-12-09 10:23:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('g3jokh7t7gls9q9p66vrb9s1vi', 4, '2024-12-06 06:00:56', '2024-12-06 06:00:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('gak62p2rdrc2bim4o98diiblv0', 11, '2024-12-09 09:57:57', '2024-12-09 09:57:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('l832dn3mtqbq86cl69tnouf7v9', 10, '2024-12-06 09:10:32', '2024-12-06 09:10:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('lpnl5na81brs6p7213332is8oa', 17, '2024-12-11 10:06:28', '2024-12-11 10:06:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('og2ij0btju1rm6idv24eccn9ve', 21, '2024-12-09 08:29:51', '2024-12-09 08:29:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('so8ghbu5jug35kcoq9259eodoc', 9, '2024-12-10 08:02:12', '2024-12-10 08:02:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('vd5c8j4bv8l76bbrpbbu8pd28k', 1, '2024-12-11 10:11:53', '2024-12-11 10:11:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('vegl3qln7pf4l2q7pv127r0k5j', 3, '2024-12-11 09:21:01', '2024-12-11 09:21:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36');

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
  ADD KEY `branchId` (`branchId`);

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
  ADD PRIMARY KEY (`requirementId`),
  ADD KEY `visaGuestId` (`guestId`),
  ADD KEY `visaAgentId` (`agentId`),
  ADD KEY `visaTransactNo` (`transactNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accountId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `agentcomission`
--
ALTER TABLE `agentcomission`
  MODIFY `comissionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agentflightseats`
--
ALTER TABLE `agentflightseats`
  MODIFY `flightSeatId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=961;

--
-- AUTO_INCREMENT for table `auditaccounts`
--
ALTER TABLE `auditaccounts`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditbooking`
--
ALTER TABLE `auditbooking`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `auditpayment`
--
ALTER TABLE `auditpayment`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `auditrequest`
--
ALTER TABLE `auditrequest`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branchId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `clientflight`
--
ALTER TABLE `clientflight`
  MODIFY `clientFlightId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `concern`
--
ALTER TABLE `concern`
  MODIFY `concernId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `concerndetails`
--
ALTER TABLE `concerndetails`
  MODIFY `concernDetailsId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flightId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `guestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `requestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `totalcost`
--
ALTER TABLE `totalcost`
  MODIFY `totalCostId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visarequirements`
--
ALTER TABLE `visarequirements`
  MODIFY `requirementId` int(11) NOT NULL AUTO_INCREMENT;

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

--
-- Constraints for table `visarequirements`
--
ALTER TABLE `visarequirements`
  ADD CONSTRAINT `visaAgentId` FOREIGN KEY (`agentId`) REFERENCES `agent` (`agentId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `visaGuestId` FOREIGN KEY (`guestId`) REFERENCES `guest` (`guestId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `visaTransactNo` FOREIGN KEY (`transactNo`) REFERENCES `guest` (`transactNo`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
