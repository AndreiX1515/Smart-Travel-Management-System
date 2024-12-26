-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2024 at 09:42 AM
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
(6, 'E001', 'password6', 345678, 'active', 'employee', '2024-10-30 00:00:00'),
(7, 'E002', 'password7', 567890, 'active', 'employee', '2024-10-30 00:00:00'),
(8, 'E003', 'password8', 901234, 'active', 'employee', '2024-10-30 00:00:00'),
(9, 'E004', 'password9', 246810, 'active', 'employee', '2024-10-30 00:00:00'),
(10, 'E005', 'password10', 135790, 'active', 'employee', '2024-10-30 00:00:00'),
(11, 'E006', 'password11', 864209, 'active', 'employee', '2024-10-30 00:00:00'),
(12, 'E007', 'password12', 975312, 'active', 'employee', '2024-10-30 00:00:00'),
(13, 'E008', 'password13', 108642, 'active', 'employee', '2024-10-30 00:00:00'),
(14, 'E009', 'password14', 246135, 'active', 'employee', '2024-10-30 00:00:00'),
(15, 'E010', 'password15', 369258, 'active', 'employee', '2024-10-30 00:00:00'),
(16, 'E011', 'password16', 741852, 'active', 'employee', '2024-10-30 00:00:00');

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
  `agentType` enum('Retailer','Wholeseller') NOT NULL,
  `commisionRate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`id`, `agentId`, `accountId`, `branchId`, `fName`, `lName`, `mName`, `countryCode`, `contactNo`, `agentType`, `commisionRate`) VALUES
(1, 'A001', 1, 1, 'Veronica', 'Hantazo', NULL, '+63', '9957563947', 'Retailer', NULL),
(2, 'A002', 2, 2, 'Amie', 'Demapindan', NULL, '+63', '9177149418', 'Retailer', NULL),
(3, 'A003', 3, 3, 'Julyanna', 'Francia', NULL, '+63', '9778127977', 'Wholeseller', 10),
(4, 'A004', 4, 4, 'Jonna', '', NULL, '+63', '9957501306', 'Wholeseller', 10),
(5, 'A005', 5, 5, 'Winie', 'Hantazo', NULL, '+63', '9', 'Retailer', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `agentcomission`
--

CREATE TABLE `agentcomission` (
  `comissionId` int(11) NOT NULL,
  `agentId` int(50) DEFAULT NULL,
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
(1, 'A001', 1, 5),
(2, 'A001', 2, 5),
(3, 'A001', 3, 5),
(4, 'A001', 4, 5),
(5, 'A001', 5, 5),
(6, 'A001', 6, 5),
(7, 'A001', 7, 5),
(8, 'A001', 8, 5),
(9, 'A001', 9, 5),
(10, 'A001', 10, 5),
(11, 'A001', 11, 5),
(12, 'A001', 12, 5),
(13, 'A001', 13, 5),
(14, 'A001', 14, 5),
(15, 'A001', 15, 5),
(16, 'A001', 16, 5),
(17, 'A001', 17, 5),
(18, 'A001', 18, 5),
(19, 'A001', 19, 5),
(20, 'A001', 20, 5),
(21, 'A001', 21, 5),
(22, 'A001', 22, 5),
(23, 'A001', 23, 5),
(24, 'A001', 24, 5),
(25, 'A001', 25, 5),
(26, 'A001', 26, 5),
(27, 'A001', 27, 5),
(28, 'A001', 28, 5),
(29, 'A001', 29, 5),
(30, 'A001', 30, 5),
(31, 'A001', 31, 5),
(32, 'A001', 32, 5),
(33, 'A001', 33, 5),
(34, 'A001', 34, 5),
(35, 'A001', 35, 5),
(36, 'A001', 36, 5),
(37, 'A001', 37, 5),
(38, 'A001', 38, 5),
(39, 'A001', 39, 5),
(40, 'A001', 40, 5),
(41, 'A001', 41, 5),
(42, 'A001', 42, 5),
(43, 'A001', 43, 5),
(44, 'A001', 44, 5),
(45, 'A001', 45, 5),
(46, 'A001', 46, 5),
(47, 'A001', 47, 5),
(48, 'A001', 48, 5),
(49, 'A001', 49, 5),
(50, 'A001', 50, 5),
(51, 'A001', 51, 5),
(52, 'A001', 52, 5),
(53, 'A001', 53, 5),
(54, 'A001', 54, 5),
(55, 'A001', 55, 5),
(56, 'A001', 56, 5),
(57, 'A001', 57, 5),
(58, 'A001', 58, 5),
(59, 'A001', 59, 5),
(60, 'A001', 60, 5),
(61, 'A001', 61, 5),
(62, 'A001', 62, 5),
(63, 'A001', 63, 5),
(64, 'A001', 64, 5),
(65, 'A001', 65, 5),
(66, 'A001', 66, 5),
(67, 'A001', 67, 5),
(68, 'A001', 68, 5),
(69, 'A001', 69, 5),
(70, 'A001', 70, 5),
(71, 'A001', 71, 5),
(72, 'A001', 72, 5),
(73, 'A001', 73, 5),
(74, 'A001', 74, 5),
(75, 'A001', 75, 5),
(76, 'A001', 76, 5),
(77, 'A001', 77, 5),
(78, 'A001', 78, 5),
(79, 'A001', 79, 5),
(80, 'A001', 80, 5),
(81, 'A001', 81, 5),
(82, 'A001', 82, 5),
(83, 'A001', 83, 5),
(84, 'A001', 84, 5),
(85, 'A001', 85, 5),
(86, 'A001', 86, 5),
(87, 'A001', 87, 5),
(88, 'A001', 88, 5),
(89, 'A001', 89, 5),
(90, 'A001', 90, 5),
(91, 'A001', 91, 5),
(92, 'A001', 92, 5),
(93, 'A001', 93, 5),
(94, 'A001', 94, 5),
(95, 'A001', 95, 5),
(96, 'A001', 96, 5),
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
(480, 'A005', 96, 5);

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
  `newValues` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `bookingType` enum('Package','Land','','') DEFAULT NULL,
  `flightDetails` text DEFAULT NULL,
  `status` enum('Pending','Cancelled','Confirmed','Reject') NOT NULL,
  `remarks` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `booking`
--
DELIMITER $$
CREATE TRIGGER `after_booking_insert` AFTER INSERT ON `booking` FOR EACH ROW BEGIN
    INSERT INTO auditBooking (
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
    INSERT INTO auditBooking (
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
        @current_user_id, -- Dynamic session variable for the current user
        CONCAT(
            'Update Transact No: ', OLD.transactNo, 
            ', status: ', OLD.status
        ),
        CONCAT(
            'Update Transact No: ', OLD.transactNo, 
            ', status: ', NEW.status
        )
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
(18, 5, 'One Way', 900.00),
(19, 5, 'Round Trip', 1800.00),
(20, 7, 'MNL - INC', 2950.00),
(21, 7, 'ICN - MNL', 3350.00),
(22, 7, 'Round Trip', 6800.00),
(23, 4, 'Single Supplement', 9280.00);

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
-- Triggers `payment`
--
DELIMITER $$
CREATE TRIGGER `after_payment_insert` AFTER INSERT ON `payment` FOR EACH ROW BEGIN
    INSERT INTO auditPayment (
        paymentId, transactNo, actionType, actionDate, performedBy, oldValues, newValues
    )
    VALUES (
        NEW.paymentId,
        NEW.transactNo,
        'INSERT',
        CURRENT_TIMESTAMP,
        NEW.accountId,
        NULL,
        JSON_OBJECT(
            'paymentTitle', NEW.paymentTitle,
            'paymentType', NEW.paymentType,
            'amount', NEW.amount,
            'paymentDate', NEW.paymentDate,
            'paymentStatus', NEW.paymentStatus,
            'paymentRemarks', NEW.paymentRemarks
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_payment_update` AFTER UPDATE ON `payment` FOR EACH ROW BEGIN
    INSERT INTO auditPayment (
        paymentId, transactNo, actionType, actionDate, performedBy, oldValues, newValues
    )
    VALUES (
        OLD.paymentId,
        OLD.transactNo,
        'UPDATE',
        CURRENT_TIMESTAMP,
        NEW.accountId,
        JSON_OBJECT(
            'paymentTitle', OLD.paymentTitle,
            'paymentType', OLD.paymentType,
            'amount', OLD.amount,
            'paymentDate', OLD.paymentDate,
            'paymentStatus', OLD.paymentStatus,
            'paymentRemarks', OLD.paymentRemarks
        ),
        JSON_OBJECT(
            'paymentTitle', NEW.paymentTitle,
            'paymentType', NEW.paymentType,
            'amount', NEW.amount,
            'paymentDate', NEW.paymentDate,
            'paymentStatus', NEW.paymentStatus,
            'paymentRemarks', NEW.paymentRemarks
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
  `pax` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `requestCost` decimal(10,2) DEFAULT NULL,
  `requestDate` datetime DEFAULT current_timestamp(),
  `requestStatus` enum('Submitted','Confirmed','Rejected','') NOT NULL,
  `requestRemarks` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `request`
--
DELIMITER $$
CREATE TRIGGER `after_request_insert` AFTER INSERT ON `request` FOR EACH ROW BEGIN
    INSERT INTO auditRequest (
        requestId, transactNo, actionType, actionDate, performedBy, oldValues, newValues
    )
    VALUES (
        NEW.requestId,
        NEW.transactNo,
        'INSERT',
        CURRENT_TIMESTAMP,
        NEW.accountId,
        NULL,
        JSON_OBJECT(
            'concernId', NEW.concernId,
            'concernDetailsId', NEW.concernDetailsId,
            'customRequest', NEW.customRequest,
            'customAmount', NEW.customAmount,
            'requestCost', NEW.requestCost,
            'requestDate', NEW.requestDate,
            'requestStatus', NEW.requestStatus,
            'requestRemarks', NEW.requestRemarks
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_request_update` AFTER UPDATE ON `request` FOR EACH ROW BEGIN
    INSERT INTO auditRequest (
        requestId, transactNo, actionType, actionDate, performedBy, oldValues, newValues
    )
    VALUES (
        OLD.requestId,
        OLD.transactNo,
        'UPDATE',
        CURRENT_TIMESTAMP,
        NEW.accountId,
        JSON_OBJECT(
            'concernId', OLD.concernId,
            'concernDetailsId', OLD.concernDetailsId,
            'customRequest', OLD.customRequest,
            'customAmount', OLD.customAmount,
            'requestCost', OLD.requestCost,
            'requestDate', OLD.requestDate,
            'requestStatus', OLD.requestStatus,
            'requestRemarks', OLD.requestRemarks
        ),
        JSON_OBJECT(
            'concernId', NEW.concernId,
            'concernDetailsId', NEW.concernDetailsId,
            'customRequest', NEW.customRequest,
            'customAmount', NEW.customAmount,
            'requestCost', NEW.requestCost,
            'requestDate', NEW.requestDate,
            'requestStatus', NEW.requestStatus,
            'requestRemarks', NEW.requestRemarks
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
('1b9vjpl0vi5r2p1uaiad9hpvfk', 3, '2024-11-29 02:56:16', '2024-11-29 02:56:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('5u317inuchcs506c59ct6osp9r', 5, '2024-11-28 08:48:42', '2024-11-28 08:48:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('9lfmog39ev31gcp0vrvl3b5hia', 6, '2024-12-03 09:17:29', '2024-12-03 09:17:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('aabagiqimi9m7u1r7o81djoedp', 2, '2024-11-29 06:31:41', '2024-11-29 06:31:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('kq42gjvkbmuacd8o47htugb8p8', 1, '2024-12-03 09:16:53', '2024-12-03 09:16:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
('llmlfsq9o3als684jf27q2pk7r', 4, '2024-11-28 08:17:30', '2024-11-28 08:17:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36');

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
  ADD PRIMARY KEY (`id`),
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
  ADD PRIMARY KEY (`auditId`);

--
-- Indexes for table `auditpayment`
--
ALTER TABLE `auditpayment`
  ADD PRIMARY KEY (`auditId`);

--
-- Indexes for table `auditrequest`
--
ALTER TABLE `auditrequest`
  ADD PRIMARY KEY (`auditId`);

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
  MODIFY `accountId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `agentcomission`
--
ALTER TABLE `agentcomission`
  MODIFY `comissionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agentflightseats`
--
ALTER TABLE `agentflightseats`
  MODIFY `flightSeatId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=481;

--
-- AUTO_INCREMENT for table `auditaccounts`
--
ALTER TABLE `auditaccounts`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditbooking`
--
ALTER TABLE `auditbooking`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditpayment`
--
ALTER TABLE `auditpayment`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditrequest`
--
ALTER TABLE `auditrequest`
  MODIFY `auditId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `concernDetailsId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
  MODIFY `packageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT;

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
