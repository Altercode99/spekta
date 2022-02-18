-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2022 at 03:50 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kf_maintenance`
--

-- --------------------------------------------------------

--
-- Table structure for table `production_machines`
--

CREATE TABLE `production_machines` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `building_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `dimension` varchar(25) NOT NULL,
  `filename` varchar(30) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `production_machines`
--

INSERT INTO `production_machines` (`id`, `location`, `name`, `department_id`, `sub_department_id`, `division_id`, `building_id`, `room_id`, `dimension`, `filename`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', 'Stripping Besar KSM 1', 1, 4, 11, 1, 2, '-', '1642661868_1_844.jpeg', 1, 1, '2022-01-20 13:53:23', '2022-01-21 10:30:08'),
(2, 'KF-JKT', 'Stripping Besar Indomach 1', 1, 4, 11, 1, 1, '-', '', 1, 1, '2022-01-21 10:30:53', '2022-01-21 10:30:53'),
(3, 'KF-JKT', 'Stripping Kecil Chen Tai', 1, 4, 11, 1, 3, '-', '', 1, 1, '2022-01-21 10:31:39', '2022-01-21 10:31:39'),
(4, 'KF-JKT', 'Blister DK 4000', 1, 4, 11, 1, 4, '-', '', 1, 1, '2022-01-21 10:32:09', '2022-01-21 10:32:09'),
(5, 'KF-JKT', 'Stripping Besar KSM 2', 1, 4, 11, 1, 5, '-', '', 1, 1, '2022-01-21 10:32:47', '2022-01-21 10:32:47'),
(6, 'KF-JKT', 'Stripping Besar Indomach 2', 1, 4, 11, 1, 6, '-', '', 1, 1, '2022-01-21 10:33:52', '2022-01-21 10:33:52'),
(7, 'KF-JKT', 'Stripping Kecil WUFU 321', 1, 4, 11, 1, 7, '-', '', 1, 1, '2022-01-21 10:34:57', '2022-01-21 10:34:57'),
(8, 'KF-JKT', 'Blister Hoonga', 1, 4, 11, 1, 8, '-', '', 1, 1, '2022-01-21 10:35:25', '2022-01-21 10:35:25'),
(9, 'KF-JKT', 'Stripping Besar KSM 3', 1, 4, 11, 1, 9, '-', '', 1, 1, '2022-01-21 10:35:55', '2022-01-21 10:35:55'),
(10, 'KF-JKT', 'Stripping Kecil Chuan Yung 303', 1, 4, 11, 1, 10, '-', '', 1, 1, '2022-01-21 10:36:43', '2022-01-21 10:36:43'),
(11, 'KF-JKT', 'Stripping Kecil WUFU 322', 1, 4, 11, 1, 11, '-', '', 1, 1, '2022-01-21 10:37:36', '2022-01-21 10:37:36'),
(12, 'KF-JKT', 'Counting Countec', 1, 4, 11, 1, 12, '-', '', 1, 1, '2022-01-21 10:38:00', '2022-01-21 10:38:00'),
(13, 'KF-JKT', 'Stripping Besar Sunco 2', 1, 4, 11, 1, 13, '-', '', 1, 1, '2022-01-21 10:38:33', '2022-01-21 10:38:33'),
(14, 'KF-JKT', 'Stripping Kecil Chuan Yung 359', 1, 4, 11, 1, 14, '-', '', 1, 1, '2022-01-21 10:39:13', '2022-01-21 10:39:13'),
(15, 'KF-JKT', 'Stripping Kecil Tai Chung', 1, 4, 11, 1, 15, '-', '', 1, 1, '2022-01-21 10:40:02', '2022-01-21 10:40:02'),
(16, 'KF-JKT', 'PP Cap Major Axis', 1, 4, 11, 1, 12, '-', '', 1, 1, '2022-01-21 10:40:35', '2022-01-21 10:40:35'),
(17, 'KF-JKT', 'Stripping Besar Sunco 1', 1, 4, 11, 1, 16, '-', '', 1, 1, '2022-01-21 10:41:16', '2022-01-21 10:41:16'),
(18, 'KF-JKT', 'Stripping Kecil Chuan Yung 360', 1, 4, 11, 1, 17, '-', '', 1, 1, '2022-01-21 10:41:42', '2022-01-21 10:41:42'),
(19, 'KF-JKT', 'Blister DK 40A', 1, 4, 11, 1, 17, '-', '', 1, 1, '2022-01-21 10:42:04', '2022-01-21 10:42:04'),
(20, 'KF-JKT', 'Cetak Killian Romaco', 1, 3, 8, 2, 19, '-', '', 1, 1, '2022-01-24 15:42:11', '2022-01-27 23:18:11'),
(21, 'KF-JKT', 'Coating Narong', 1, 3, 8, 2, 20, '-', '', 1, 1, '2022-01-24 15:42:53', '2022-01-27 23:50:01'),
(22, 'KF-JKT', 'Conveyor Besar Lokal', 1, 3, 9, 2, 21, '-', '', 1, 1, '2022-01-24 15:43:46', '2022-01-24 15:43:46'),
(23, 'KF-JKT', 'Comminuting Fitz Mill Yen Chen', 1, 3, 8, 2, 22, '-', '', 1, 1, '2022-01-24 15:44:20', '2022-01-24 15:44:20'),
(24, 'KF-JKT', 'Blow  Suct Emme Cam TSA1000', 1, 3, 8, 2, 23, '-', '', 1, 1, '2022-01-24 15:45:14', '2022-01-27 23:17:19'),
(25, 'KF-JKT', 'Conveyor Kecil Lokal', 1, 3, 9, 2, 24, '-', '', 1, 1, '2022-01-24 15:48:07', '2022-01-24 15:48:07'),
(26, 'KF-JKT', 'Thumbling Mixer Yen Chen', 1, 3, 8, 2, 22, '-', '', 1, 1, '2022-01-24 15:48:43', '2022-01-24 15:48:43'),
(27, 'KF-JKT', 'Counting Hope Win', 1, 3, 8, 2, 25, '-', '', 1, 1, '2022-01-24 15:49:12', '2022-01-27 23:50:15'),
(28, 'KF-JKT', 'Deduster Uphill', 1, 3, 8, 2, 26, '-', '', 1, 1, '2022-01-24 15:50:06', '2022-01-24 15:50:06'),
(29, 'KF-JKT', 'Super Mixer Yen Chen', 1, 3, 8, 2, 27, '-', '', 1, 1, '2022-01-24 15:50:37', '2022-01-24 15:50:37'),
(30, 'KF-JKT', 'Capsule Hong Hua / NJP 900', 1, 3, 8, 2, 19, '-', '', 1, 1, '2022-01-24 15:51:10', '2022-01-27 23:17:38'),
(31, 'KF-JKT', 'Metal Detector MET 30', 1, 3, 8, 2, 26, '-', '', 1, 1, '2022-01-24 15:51:55', '2022-01-24 15:51:55'),
(32, 'KF-JKT', 'Fluid Bed Dryer Lokal', 1, 3, 8, 2, 27, '-', '', 1, 1, '2022-01-24 15:52:21', '2022-01-24 15:52:21'),
(33, 'KF-JKT', 'Cetak BWI Manesty', 1, 3, 8, 2, 26, '-', '', 1, 1, '2022-01-24 15:52:45', '2022-01-27 23:17:57'),
(34, 'KF-JKT', 'Shrinking Thunel Chuen An 1', 1, 3, 9, 2, 21, '-', '', 1, 1, '2022-01-24 15:53:25', '2022-01-27 23:53:31'),
(35, 'KF-JKT', 'Shrinking Thunel Chuen An 2', 1, 3, 9, 2, 21, '-', '', 1, 1, '2022-01-24 15:54:14', '2022-01-27 23:53:56'),
(36, 'KF-JKT', 'Oscilating Granulator Yen Chen', 1, 1, 1, 1, 28, '-', '', 1, 1, '2022-01-27 21:55:35', '2022-01-27 21:55:35'),
(37, 'KF-JKT', 'Cetak Tablet JCMCO', 1, 1, 2, 1, 29, '-', '', 1, 1, '2022-01-27 21:56:27', '2022-01-27 23:48:36'),
(38, 'KF-JKT', 'Cetak Tablet BWI Manesty Unipress', 1, 1, 2, 1, 30, '-', '', 1, 1, '2022-01-27 21:57:17', '2022-01-27 23:48:07'),
(39, 'KF-JKT', 'Metal Detektor Insight', 1, 1, 2, 1, 31, '-', '', 1, 1, '2022-01-27 21:57:57', '2022-01-27 21:57:57'),
(40, 'KF-JKT', 'Metal Detektor THS', 1, 1, 2, 1, 32, '-', '', 1, 1, '2022-01-27 21:58:46', '2022-01-27 21:58:46'),
(41, 'KF-JKT', 'Tablet Deduster Local', 1, 1, 2, 1, 31, '-', '', 1, 1, '2022-01-27 21:59:50', '2022-01-27 21:59:50'),
(42, 'KF-JKT', 'Cetak Tablet Narong / NRT 25', 1, 1, 2, 1, 31, '-', '', 1, 1, '2022-01-27 22:00:23', '2022-01-27 23:49:19'),
(43, 'KF-JKT', 'Intensive Mixer Diosna', 1, 1, 1, 1, 28, '-', '', 1, 1, '2022-01-27 22:00:56', '2022-01-27 22:00:56'),
(44, 'KF-JKT', 'Comminuting Fitz Mill Manesty', 1, 1, 1, 1, 33, '-', '', 1, 1, '2022-01-27 22:01:30', '2022-01-27 22:01:30'),
(45, 'KF-JKT', 'Cylinder Granulator Hatta', 1, 1, 1, 1, 33, '-', '', 1, 1, '2022-01-27 22:02:19', '2022-01-27 22:02:19'),
(46, 'KF-JKT', 'Cetak Tablet Rimek / KED-4', 1, 1, 2, 1, 32, '-', '', 1, 1, '2022-01-27 22:03:15', '2022-01-27 23:49:33'),
(47, 'KF-JKT', 'Metal Detektor Local', 1, 1, 2, 1, 35, '-', '', 1, 1, '2022-01-27 22:04:00', '2022-01-27 22:04:00'),
(48, 'KF-JKT', 'Tablet Deduster Local', 1, 1, 2, 1, 32, '-', '', 1, 1, '2022-01-27 22:04:49', '2022-01-27 22:04:49'),
(49, 'KF-JKT', 'Tablet Deduster Tricore', 1, 1, 2, 1, 35, '-', '', 1, 1, '2022-01-27 22:05:21', '2022-01-27 22:05:21'),
(50, 'KF-JKT', 'Fluid Bed Dryer Yen Chen', 1, 1, 1, 1, 36, '-', '', 1, 1, '2022-01-27 22:05:52', '2022-01-27 22:05:52'),
(51, 'KF-JKT', 'Merumizer Hatta', 1, 1, 1, 1, 28, '-', '', 1, 1, '2022-01-27 22:06:23', '2022-01-27 22:06:23'),
(52, 'KF-JKT', 'Cetak Tablet Killian / RTS 32', 1, 1, 2, 1, 37, '-', '', 1, 1, '2022-01-27 22:07:01', '2022-01-27 23:49:05'),
(53, 'KF-JKT', 'Tablet Deduster Tricore', 1, 1, 2, 1, 37, '-', '', 1, 1, '2022-01-27 22:07:37', '2022-01-27 22:07:37'),
(54, 'KF-JKT', 'Cetak Tablet Sejong / GRE-21D', 1, 1, 14, 1, 38, '-', '', 1, 1, '2022-01-27 22:09:35', '2022-01-27 23:49:47'),
(55, 'KF-JKT', 'Roll Compactor Alexanderwerk', 1, 1, 14, 1, 39, '-', '', 1, 1, '2022-01-27 22:10:07', '2022-01-27 23:52:21'),
(56, 'KF-JKT', 'Super Mixer Yen Chen', 1, 1, 1, 1, 28, '-', '', 1, 1, '2022-01-27 22:10:40', '2022-01-27 22:10:40'),
(57, 'KF-JKT', 'V-mixer Chuan Yung', 1, 1, 1, 1, 40, '-', '', 1, 1, '2022-01-27 22:11:11', '2022-01-27 22:11:11'),
(58, 'KF-JKT', 'Metal Detektor Loma', 1, 1, 2, 1, 37, '-', '', 1, 1, '2022-01-27 22:11:50', '2022-01-27 22:11:50'),
(59, 'KF-JKT', 'Metal Detektor Insight', 1, 1, 2, 1, 30, '-', '', 1, 1, '2022-01-27 22:12:15', '2022-01-27 22:12:15'),
(60, 'KF-JKT', 'Tablet Deduster JCMCO', 1, 1, 2, 1, 35, '-', '', 1, 1, '2022-01-27 22:12:41', '2022-01-27 22:12:41'),
(61, 'KF-JKT', 'Tumbling Mixer Canaan / HGD 500', 1, 1, 14, 1, 41, '-', '', 1, 1, '2022-01-27 22:13:13', '2022-01-27 23:54:11'),
(62, 'KF-JKT', 'V-mixer Zanchetta', 1, 1, 1, 1, 40, '-', '', 1, 1, '2022-01-27 22:14:30', '2022-01-27 22:14:30'),
(63, 'KF-JKT', 'Comminuting Fitz Mill Yen Chen', 1, 1, 1, 1, 33, '-', '', 1, 1, '2022-01-27 22:16:46', '2022-01-27 22:16:46'),
(64, 'KF-JKT', 'Cetak Tablet Killian / RTS 21', 1, 1, 2, 1, 32, '-', '', 1, 1, '2022-01-27 22:17:11', '2022-01-27 23:48:52'),
(65, 'KF-JKT', 'Salut Film Accelacota Manesty', 1, 1, 3, 1, 42, '-', '', 1, 1, '2022-01-27 22:18:56', '2022-01-27 23:52:36'),
(66, 'KF-JKT', 'Salut Film Dongfang', 1, 1, 2, 1, 43, '-', '', 1, 1, '2022-01-27 22:19:44', '2022-01-27 23:53:16'),
(67, 'KF-JKT', 'Tablet Deduster Kramer', 1, 1, 2, 1, 31, '-', '', 1, 1, '2022-01-27 22:20:33', '2022-01-27 22:20:33'),
(68, 'KF-JKT', 'Mixing Tank 100 L Lokal', 1, 2, 4, 1, 44, '-', '', 1, 1, '2022-01-27 22:56:03', '2022-01-27 22:56:03'),
(69, 'KF-JKT', 'Mixing Tank Becomix', 1, 2, 4, 1, 45, '-', '', 1, 1, '2022-01-27 22:56:47', '2022-01-27 22:56:47'),
(70, 'KF-JKT', 'V-mixer Chuan Yung', 1, 2, 7, 1, 46, '-', '', 1, 1, '2022-01-27 22:57:45', '2022-01-27 22:57:45'),
(71, 'KF-JKT', 'Holding Tank 2 Lokal', 1, 2, 6, 1, 47, '-', '', 1, 1, '2022-01-27 22:58:37', '2022-01-27 22:58:37'),
(72, 'KF-JKT', 'Dry Heat DE LAMA', 1, 2, 6, 1, 48, '-', '', 1, 1, '2022-01-27 22:59:40', '2022-01-27 22:59:40'),
(73, 'KF-JKT', 'Filling Ampul Rota', 1, 2, 6, 1, 49, '-', '', 1, 1, '2022-01-27 23:00:25', '2022-01-27 23:50:34'),
(74, 'KF-JKT', 'Pengisian Kapsul Manual Zuma', 1, 2, 7, 1, 50, '-', '', 1, 1, '2022-01-27 23:01:51', '2022-01-27 23:01:51'),
(75, 'KF-JKT', 'Filling Liquid Jih Cheng ', 1, 2, 5, 1, 51, '-', '', 1, 1, '2022-01-27 23:02:31', '2022-01-27 23:02:31'),
(76, 'KF-JKT', 'Mixer Lokal', 1, 2, 5, 1, 52, '-', '', 1, 1, '2022-01-27 23:03:12', '2022-01-27 23:03:12'),
(77, 'KF-JKT', 'Auto Clave Product DE LAMA', 1, 2, 6, 1, 53, '-', '', 1, 1, '2022-01-27 23:03:54', '2022-01-27 23:03:54'),
(78, 'KF-JKT', 'Auto Clave Garment DE LAMA', 1, 2, 6, 1, 54, '-', '', 1, 1, '2022-01-27 23:04:30', '2022-01-27 23:04:30'),
(79, 'KF-JKT', 'Filling Capsule Bosch / GKF 700 S', 1, 2, 7, 1, 55, '-', '', 1, 1, '2022-01-27 23:05:12', '2022-01-27 23:50:48'),
(80, 'KF-JKT', 'Filling Liquid HD Pack ', 1, 2, 5, 1, 56, '-', '', 1, 1, '2022-01-27 23:05:53', '2022-01-27 23:05:53'),
(81, 'KF-JKT', 'Agitator Holding Tank Lokal', 1, 2, 5, 1, 52, '-', '', 1, 1, '2022-01-27 23:06:34', '2022-01-27 23:06:34'),
(82, 'KF-JKT', 'Holding Tank 1 Local', 1, 2, 6, 1, 57, '-', '', 1, 1, '2022-01-27 23:07:14', '2022-01-27 23:07:14'),
(83, 'KF-JKT', 'Mixer Tanki Penampungan Besar Local', 1, 2, 5, 1, 52, '-', '', 1, 1, '2022-01-27 23:08:16', '2022-01-27 23:08:16'),
(84, 'KF-JKT', 'Pengisian Tube Kalix', 1, 2, 4, 1, 58, '-', '', 1, 1, '2022-01-27 23:08:57', '2022-01-27 23:08:57'),
(85, 'KF-JKT', 'Filling Capsule PTK', 1, 2, 7, 1, 59, '-', '', 1, 1, '2022-01-27 23:09:38', '2022-01-27 23:51:03'),
(86, 'KF-JKT', 'Planetary Mixer Miralles / MPVD 200', 1, 2, 4, 1, 44, '-', '', 1, 1, '2022-01-27 23:10:18', '2022-01-27 23:10:18'),
(87, 'KF-JKT', 'Mixing Tank Local', 1, 2, 6, 1, 60, '-', '', 1, 1, '2022-01-27 23:11:11', '2022-01-27 23:11:11'),
(88, 'KF-JKT', 'Washing Ampul Cozzoli', 1, 2, 6, 1, 61, '-', '', 1, 1, '2022-01-27 23:12:16', '2022-01-27 23:54:37'),
(89, 'KF-JKT', 'Fillingbosch', 1, 2, 6, 1, 62, '-', '', 1, 1, '2022-01-27 23:12:54', '2022-01-27 23:51:17'),
(90, 'KF-JKT', 'Washing Bosch', 1, 2, 6, 1, 63, '-', '', 1, 1, '2022-01-27 23:13:38', '2022-01-27 23:55:25'),
(91, 'KF-JKT', 'Tunnel Bosch', 1, 2, 6, 1, 63, '-', '', 1, 1, '2022-01-27 23:14:10', '2022-01-27 23:54:23'),
(92, 'KF-JKT', 'Pengisian Tube Kalix', 1, 2, 4, 1, 64, '-', '', 1, 1, '2022-01-27 23:14:53', '2022-01-27 23:14:53'),
(93, 'KF-JKT', 'Holding Tank 2 Lokal', 1, 2, 6, 1, 65, '-', '', 1, 1, '2022-01-27 23:15:27', '2022-01-27 23:15:27'),
(94, 'KF-JKT', 'Pengayak Chuan Yung', 1, 2, 7, 1, 46, '-', '', 1, 1, '2022-01-27 23:16:05', '2022-01-27 23:51:34'),
(95, 'KF-JKT', 'Mixer Kecil Lokal', 1, 2, 5, 1, 52, '-', '', 1, 1, '2022-01-27 23:16:45', '2022-01-27 23:16:45'),
(96, 'KF-JKT', 'Labeling Botol Jih Cheng', 1, 4, 13, 1, 66, '-', '', 1, 1, '2022-01-28 13:48:03', '2022-01-28 13:48:03'),
(97, 'KF-JKT', 'Labeling Botol Jih Cheng', 1, 4, 13, 1, 67, '-', '', 1, 1, '2022-01-28 13:48:25', '2022-01-28 13:48:25'),
(98, 'KF-JKT', 'Conveyor Lokal', 3, 13, 45, 1, 68, '-', '', 1, 1, '2022-01-28 13:49:33', '2022-01-28 13:49:33'),
(99, 'KF-JKT', 'Labeling Ampul Marco Tech', 3, 13, 45, 1, 69, '-', '', 1, 1, '2022-01-28 13:49:59', '2022-01-28 13:49:59'),
(100, 'KF-JKT', 'Conveyor Lokal', 3, 13, 45, 1, 70, '-', '', 1, 1, '2022-01-28 13:50:30', '2022-01-28 13:50:30'),
(101, 'KF-JKT', 'Labeling Ampul KWT / 215 A', 3, 13, 45, 1, 71, '-', '', 1, 1, '2022-01-28 13:50:58', '2022-01-28 13:50:58'),
(102, 'KF-JKT', 'Conveyor Lokal', 3, 13, 45, 1, 72, '-', '', 1, 1, '2022-01-28 13:51:20', '2022-01-28 13:51:20'),
(103, 'KF-JKT', 'Conveyor Lokal', 3, 13, 45, 1, 73, '-', '', 1, 1, '2022-01-28 13:51:42', '2022-01-28 13:51:42'),
(104, 'KF-JKT', 'Labeling Ampul Chin Yen', 3, 13, 45, 1, 74, '-', '', 1, 1, '2022-01-28 13:52:03', '2022-01-28 13:52:03'),
(105, 'KF-JKT', 'Conveyor Lokal', 3, 13, 45, 1, 75, '-', '', 1, 1, '2022-01-28 13:52:25', '2022-01-28 13:52:25'),
(106, 'KF-JKT', 'Conveyor Lokal', 3, 13, 45, 1, 76, '-', '', 1, 1, '2022-01-28 13:52:52', '2022-01-28 13:52:52'),
(107, 'KF-JKT', 'Conveyor Lokal', 3, 13, 45, 1, 77, '-', '', 1, 1, '2022-01-28 13:53:16', '2022-01-28 13:53:16'),
(108, 'KF-JKT', 'Conveyor Lokal', 3, 13, 45, 1, 78, '-', '', 1, 1, '2022-01-28 13:53:51', '2022-01-28 13:53:51'),
(109, 'KF-JKT', 'Conveyor Lokal', 3, 13, 45, 1, 79, '-', '', 1, 1, '2022-01-28 13:54:17', '2022-01-28 13:54:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `production_machines`
--
ALTER TABLE `production_machines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`,`sub_department_id`,`division_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `production_machines`
--
ALTER TABLE `production_machines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
