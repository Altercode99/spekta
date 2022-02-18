-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2022 at 03:49 PM
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
-- Database: `kf_general`
--

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `location`, `name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', 'Non Betalaktam', 1, 1, '2022-01-20 13:20:26', '2022-01-21 09:21:09'),
(2, 'KF-JKT', 'Anti Retroviral', 1, 1, '2022-01-21 09:21:20', '2022-01-21 09:21:20');

-- --------------------------------------------------------

--
-- Table structure for table `building_rooms`
--

CREATE TABLE `building_rooms` (
  `id` int(11) NOT NULL,
  `building_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `building_rooms`
--

INSERT INTO `building_rooms` (`id`, `building_id`, `name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Primer Line 12', 1, 1, '2022-01-20 13:20:34', '2022-01-21 10:04:32'),
(2, 1, 'Primer Line 15', 1, 1, '2022-01-21 10:04:10', '2022-01-21 10:04:10'),
(3, 1, 'Primer Line 16', 1, 1, '2022-01-21 10:04:51', '2022-01-21 10:04:51'),
(4, 1, 'Primer Line 3', 1, 1, '2022-01-21 10:05:05', '2022-01-21 10:05:05'),
(5, 1, 'Primer Line 13', 1, 1, '2022-01-21 10:06:24', '2022-01-21 10:06:24'),
(6, 1, 'Primer Line 10', 1, 1, '2022-01-21 10:06:36', '2022-01-21 10:06:36'),
(7, 1, 'Primer Line 18', 1, 1, '2022-01-21 10:06:56', '2022-01-21 10:06:56'),
(8, 1, 'Primer Line 9', 1, 1, '2022-01-21 10:07:07', '2022-01-21 10:07:07'),
(9, 1, 'Primer Line 4', 1, 1, '2022-01-21 10:07:17', '2022-01-21 10:07:17'),
(10, 1, 'Primer Line 6', 1, 1, '2022-01-21 10:07:31', '2022-01-21 10:07:31'),
(11, 1, 'Primer Line 17', 1, 1, '2022-01-21 10:07:42', '2022-01-21 10:07:42'),
(12, 1, 'Primer Line 11', 1, 1, '2022-01-21 10:07:54', '2022-01-21 10:07:54'),
(13, 1, 'Primer Line 19', 1, 1, '2022-01-21 10:08:03', '2022-01-21 10:08:03'),
(14, 1, 'Primer Line 5', 1, 1, '2022-01-21 10:08:24', '2022-01-21 10:08:24'),
(15, 1, 'Primer Line 2', 1, 1, '2022-01-21 10:08:34', '2022-01-21 10:08:34'),
(16, 1, 'Primer Line 14', 1, 1, '2022-01-21 10:28:04', '2022-01-21 10:28:04'),
(17, 1, 'Primer Line 7', 1, 1, '2022-01-21 10:28:14', '2022-01-21 10:28:14'),
(18, 1, 'Primer Line 8', 1, 1, '2022-01-21 10:28:22', '2022-01-21 10:28:22'),
(19, 2, 'Compressing 2', 1, 1, '2022-01-24 15:38:32', '2022-01-24 15:38:32'),
(20, 2, 'Coating', 1, 1, '2022-01-24 15:38:51', '2022-01-24 15:38:51'),
(21, 2, 'Pengemas Sekunder', 1, 1, '2022-01-24 15:39:04', '2022-01-24 15:39:04'),
(22, 2, 'Sieving Dan Mixing', 1, 1, '2022-01-24 15:39:13', '2022-01-24 15:45:55'),
(23, 2, 'Blow Dan Suct', 1, 1, '2022-01-24 15:39:23', '2022-01-24 15:45:40'),
(24, 2, 'Printing', 1, 1, '2022-01-24 15:39:36', '2022-01-24 15:39:36'),
(25, 2, 'Counting', 1, 1, '2022-01-24 15:40:23', '2022-01-24 15:40:23'),
(26, 2, 'Compressing 1', 1, 1, '2022-01-24 15:40:41', '2022-01-24 15:40:41'),
(27, 2, 'Granulating Dan Drying', 1, 1, '2022-01-24 15:40:57', '2022-01-24 15:46:58'),
(28, 1, 'Granulasi', 1, 1, '2022-01-27 13:44:37', '2022-01-27 13:44:37'),
(29, 1, 'Cetak Tablet 6', 1, 1, '2022-01-27 13:45:08', '2022-01-27 13:45:08'),
(30, 1, 'Cetak Tablet 5', 1, 1, '2022-01-27 13:45:19', '2022-01-27 13:45:19'),
(31, 1, 'Cetak Tablet 1', 1, 1, '2022-01-27 13:45:29', '2022-01-27 13:45:29'),
(32, 1, 'Cetak Tablet 2', 1, 1, '2022-01-27 13:45:41', '2022-01-27 13:45:41'),
(33, 1, 'Sieving Granulasi', 1, 1, '2022-01-27 13:45:59', '2022-01-27 21:43:08'),
(34, 1, 'Cetak Tablet 3', 1, 1, '2022-01-27 13:51:39', '2022-01-27 13:51:39'),
(35, 1, 'Cetak Tablet ', 1, 1, '2022-01-27 13:51:59', '2022-01-27 13:51:59'),
(36, 1, 'Drying Granulasi', 1, 1, '2022-01-27 13:52:27', '2022-01-27 21:43:31'),
(37, 1, 'Cetak Tablet 4', 1, 1, '2022-01-27 13:52:41', '2022-01-27 13:52:41'),
(38, 1, 'Cetak FDC', 1, 1, '2022-01-27 13:53:06', '2022-01-27 13:53:06'),
(39, 1, 'Mixing & Slugging FDC', 1, 1, '2022-01-27 13:53:27', '2022-01-27 13:53:27'),
(40, 1, 'Mixing Granulasi', 1, 1, '2022-01-27 21:44:14', '2022-01-27 21:44:14'),
(41, 1, 'Sieving FDC', 1, 1, '2022-01-27 21:44:49', '2022-01-27 21:44:49'),
(42, 1, 'Film Coating 1', 1, 1, '2022-01-27 21:45:53', '2022-01-27 21:45:53'),
(43, 1, 'Film Coating 2', 1, 1, '2022-01-27 21:46:02', '2022-01-27 21:46:02'),
(44, 1, 'Mixing 1 Krim', 1, 1, '2022-01-27 21:46:47', '2022-01-27 21:46:47'),
(45, 1, 'Mixing 2 Krim', 1, 1, '2022-01-27 21:46:56', '2022-01-27 21:46:56'),
(46, 1, 'Mixing Kapsul', 1, 1, '2022-01-27 21:47:08', '2022-01-27 21:47:08'),
(47, 1, 'Mixing 2 Injeksi', 1, 1, '2022-01-27 21:47:23', '2022-01-27 21:47:23'),
(48, 1, 'Dryheat Injeksi', 1, 1, '2022-01-27 21:47:39', '2022-01-27 21:47:39'),
(49, 1, 'Filling 2 Injeksi', 1, 1, '2022-01-27 21:47:52', '2022-01-27 21:47:52'),
(50, 1, 'Kapsul Filling 1', 1, 1, '2022-01-27 21:48:29', '2022-01-27 21:48:29'),
(51, 1, 'Filling 2 Liquida', 1, 1, '2022-01-27 21:48:57', '2022-01-27 21:48:57'),
(52, 1, 'Mixer Liquida', 1, 1, '2022-01-27 21:49:09', '2022-01-27 21:49:09'),
(53, 1, 'Autoclave Product Injeksi', 1, 1, '2022-01-27 21:49:28', '2022-01-27 21:49:28'),
(54, 1, 'Autoclave Garment Injeksi', 1, 1, '2022-01-27 21:49:43', '2022-01-27 21:49:43'),
(55, 1, 'Kapsul Filling 2', 1, 1, '2022-01-27 21:49:56', '2022-01-27 21:49:56'),
(56, 1, 'Filling 1 Liquida', 1, 1, '2022-01-27 21:50:25', '2022-01-27 21:50:25'),
(57, 1, 'Holding 1 Injeksi', 1, 1, '2022-01-27 21:50:42', '2022-01-27 21:50:42'),
(58, 1, 'Filling 1 Krim', 1, 1, '2022-01-27 21:51:14', '2022-01-27 21:51:14'),
(59, 1, 'Kapsul Filling 3', 1, 1, '2022-01-27 21:51:29', '2022-01-27 21:51:29'),
(60, 1, 'Mixing Injeksi', 1, 1, '2022-01-27 21:52:06', '2022-01-27 21:52:06'),
(61, 1, 'Washing 2 Injeksi', 1, 1, '2022-01-27 21:52:28', '2022-01-27 21:52:28'),
(62, 1, 'Filling 1 Injeksi', 1, 1, '2022-01-27 21:52:45', '2022-01-27 21:52:45'),
(63, 1, 'Washing & Tunnel Injeksi', 1, 1, '2022-01-27 21:53:01', '2022-01-27 21:53:01'),
(64, 1, 'Filling 2 Krim', 1, 1, '2022-01-27 21:53:41', '2022-01-27 21:53:41'),
(65, 1, 'Holding 2 Injeksi', 1, 1, '2022-01-27 21:53:58', '2022-01-27 21:53:58'),
(66, 1, 'Sekunder / Line 10', 1, 1, '2022-01-28 13:43:44', '2022-01-28 13:43:44'),
(67, 1, 'Sekunder / Line 11', 1, 1, '2022-01-28 13:43:51', '2022-01-28 13:43:51'),
(68, 1, 'Penandaan / Line 10', 1, 1, '2022-01-28 13:44:08', '2022-01-28 13:44:08'),
(69, 1, 'Penandaan / Line 1', 1, 1, '2022-01-28 13:44:14', '2022-01-28 13:44:14'),
(70, 1, 'Penandaan / Line 11', 1, 1, '2022-01-28 13:44:21', '2022-01-28 13:44:21'),
(71, 1, 'Penandaan / Line 2', 1, 1, '2022-01-28 13:44:35', '2022-01-28 13:44:35'),
(72, 1, 'Penandaan / Line 6', 1, 1, '2022-01-28 13:45:21', '2022-01-28 13:45:21'),
(73, 1, 'Penandaan / Line 3', 1, 1, '2022-01-28 13:45:28', '2022-01-28 13:45:28'),
(74, 1, 'Penandaan / Line 12', 1, 1, '2022-01-28 13:45:34', '2022-01-28 13:45:34'),
(75, 1, 'Penandaan / Line 4', 1, 1, '2022-01-28 13:45:40', '2022-01-28 13:45:40'),
(76, 1, 'Penandaan / Line 8', 1, 1, '2022-01-28 13:45:46', '2022-01-28 13:45:46'),
(77, 1, 'Penandaan / Line 7', 1, 1, '2022-01-28 13:45:53', '2022-01-28 13:45:53'),
(78, 1, 'Penandaan / Line 5', 1, 1, '2022-01-28 13:46:00', '2022-01-28 13:46:00'),
(79, 1, 'Penandaan / Line 9', 1, 1, '2022-01-28 13:46:08', '2022-01-28 13:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `catherings`
--

CREATE TABLE `catherings` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `vendor_name` varchar(50) NOT NULL,
  `price` double(10,2) NOT NULL,
  `status` enum('ACTIVE','NONACTIVE') NOT NULL DEFAULT 'NONACTIVE',
  `expired` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catherings`
--

INSERT INTO `catherings` (`id`, `location`, `vendor_name`, `price`, `status`, `expired`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', 'Vendor 1', 20000.00, 'ACTIVE', '2022-01-31', 1, 1, '2022-01-20 13:19:38', '2022-01-20 13:19:38'),
(2, 'KF-JKT', 'Vendor 2', 25000.00, 'NONACTIVE', '2022-01-31', 1, 1, '2022-01-25 12:02:34', '2022-01-25 12:02:34');

-- --------------------------------------------------------

--
-- Table structure for table `guest_books`
--

CREATE TABLE `guest_books` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guest_books`
--

INSERT INTO `guest_books` (`id`, `name`, `email`, `company`, `created_by`, `updated_by`, `created_at`) VALUES
(11, 'Arman Septian', 'arman@gmail.com', 'PT. Wijaya', 1, 1, '2022-01-28 07:23:58'),
(12, 'Asep Diki Ariyanto', 'asep.diki@gmail.com', 'PT. Wijoyo', 1, 1, '2022-01-28 07:23:58'),
(13, 'Fikri Agil', 'fikri.agil@gmail.com', 'PT. Sejahtera', 1, 1, '2022-01-28 07:24:28'),
(14, 'Dinda', 'dinda@gmail.com', 'PT. Kimia Farma', 1, 1, '2022-01-28 07:26:27'),
(15, 'Arafah', 'arafah@gmail.com', 'P. Kimia Farma', 1, 1, '2022-01-28 07:26:27');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_participants`
--

CREATE TABLE `meeting_participants` (
  `id` int(11) NOT NULL,
  `meeting_id` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `status` enum('HADIR','TIDAK HADIR','BELUM MEMUTUSKAN') NOT NULL DEFAULT 'BELUM MEMUTUSKAN',
  `comfirm_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meeting_participants`
--

INSERT INTO `meeting_participants` (`id`, `meeting_id`, `name`, `email`, `company`, `status`, `comfirm_date`) VALUES
(1, '1644513139783', 'ABDUL ANAM', 'abdulanam51@gmail.com', 'KF-JKT', 'HADIR', '0000-00-00 00:00:00'),
(2, '1644513139783', 'AAN FATUR RAHMAN', 'vaturrasta46@gmail.com', 'KF-JKT', 'HADIR', '0000-00-00 00:00:00'),
(3, '1644513139783', 'Dinda', 'dinda@gmail.com', 'PT. Kimia Farma', 'TIDAK HADIR', '0000-00-00 00:00:00'),
(4, '1644513139783', 'Arafah', 'arafah@gmail.com', 'P. Kimia Farma', 'HADIR', '0000-00-00 00:00:00'),
(5, '1644513139783', 'ABDUL ANAM', 'abdulanam51@gmail.com', 'KF-JKT', 'HADIR', '0000-00-00 00:00:00'),
(6, '1644513139783', 'AAN FATUR RAHMAN', 'vaturrasta46@gmail.com', 'KF-JKT', 'HADIR', '0000-00-00 00:00:00'),
(7, '1644513139783', 'Dinda', 'dinda@gmail.com', 'PT. Kimia Farma', 'TIDAK HADIR', '0000-00-00 00:00:00'),
(8, '1644513139783', 'Arafah', 'arafah@gmail.com', 'P. Kimia Farma', 'HADIR', '0000-00-00 00:00:00'),
(9, '16445131397831', 'ABDUL ANAM', 'abdulanam51@gmail.com', 'KF-JKT', 'HADIR', '0000-00-00 00:00:00'),
(10, '16445131397831', 'AAN FATUR RAHMAN', 'vaturrasta46@gmail.com', 'KF-JKT', 'HADIR', '0000-00-00 00:00:00'),
(11, '16445131397831', 'Dinda', 'dinda@gmail.com', 'PT. Kimia Farma', 'BELUM MEMUTUSKAN', '0000-00-00 00:00:00'),
(12, '16445131397831', 'Arafah', 'arafah@gmail.com', 'P. Kimia Farma', 'BELUM MEMUTUSKAN', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_rooms`
--

CREATE TABLE `meeting_rooms` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `name` varchar(30) NOT NULL,
  `capacity` int(11) NOT NULL,
  `building` varchar(25) NOT NULL,
  `on_floor` varchar(5) NOT NULL,
  `facility` text NOT NULL,
  `filename` varchar(30) NOT NULL,
  `color` varchar(10) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meeting_rooms`
--

INSERT INTO `meeting_rooms` (`id`, `location`, `name`, `capacity`, `building`, `on_floor`, `facility`, `filename`, `color`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', 'Calcidol', 10, 'Perkantoran', '2', '-', '1642661656_1_280.jpeg', '#a26e06', 1, 1, '2022-01-20 13:51:56', '2022-01-28 23:26:13'),
(2, 'KF-JKT', 'Lamivudin', 6, 'Perkantoran', '2', '-', '1643099208_1_719.jpeg', '#3f3f3f', 1, 1, '2022-01-25 15:27:11', '2022-01-28 23:26:13'),
(3, 'KF-JKT', 'Zidovudin', 6, 'Perkantoran', '2', '-', '1643099276_1_814.jpeg', '#7f3f3f', 1, 1, '2022-01-25 15:27:56', '2022-01-28 23:26:13'),
(4, 'KF-JKT', 'Magasida', 50, 'Perkantoran', '2', '-', '1643099363_1_780.jpeg', '#007f3f', 1, 1, '2022-01-25 15:29:23', '2022-01-28 23:26:13');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_rooms_reservation`
--

CREATE TABLE `meeting_rooms_reservation` (
  `id` varchar(30) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `location` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `meeting_type` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `room_id` int(11) NOT NULL DEFAULT 0,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `duration` double(10,2) NOT NULL,
  `participants` text NOT NULL,
  `guests` text NOT NULL,
  `meal` int(11) NOT NULL,
  `snack_id` int(11) NOT NULL,
  `total_participant` int(11) NOT NULL,
  `participant_confirmed` int(11) NOT NULL,
  `participant_rejected` int(11) NOT NULL,
  `status` enum('CREATED','APPROVED','REJECTED','CLOSED') NOT NULL DEFAULT 'CREATED',
  `reason` text NOT NULL,
  `repeat_meet` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meeting_rooms_reservation`
--

INSERT INTO `meeting_rooms_reservation` (`id`, `ref`, `location`, `name`, `meeting_type`, `description`, `room_id`, `start_date`, `end_date`, `duration`, `participants`, `guests`, `meal`, `snack_id`, `total_participant`, `participant_confirmed`, `participant_rejected`, `status`, `reason`, `repeat_meet`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('1644513139783', '', 'KF-JKT', 'Tes', 'external', 'Tes', 1, '2022-02-01 08:00:00', '2022-02-01 10:00:00', 2.00, 'vaturrasta46@gmail.com,abdulanam51@gmail.com', 'arafah@gmail.com,dinda@gmail.com', 1, 0, 4, 3, 1, 'CLOSED', '', 2, 1, 215, '2022-02-11 00:11:27', '2022-02-11 07:17:04'),
('16445131397831', '1644513139783', 'KF-JKT', 'Tes', 'external', 'Tes', 1, '2022-02-02 08:00:00', '2022-02-02 10:00:00', 2.00, 'vaturrasta46@gmail.com,abdulanam51@gmail.com', 'arafah@gmail.com,dinda@gmail.com', 1, 4, 4, 4, 0, 'CLOSED', '', 0, 1, 215, '2022-02-11 00:11:28', '2022-02-11 07:16:59');

-- --------------------------------------------------------

--
-- Table structure for table `snacks`
--

CREATE TABLE `snacks` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` double(10,2) NOT NULL,
  `filename` varchar(30) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `snacks`
--

INSERT INTO `snacks` (`id`, `location`, `name`, `price`, `filename`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(0, 'KF-JKT', '-', 0.00, '', 1, 1, '2022-01-29 15:13:46', '2022-01-29 15:13:29'),
(2, 'KF-JKT', 'Snack #1', 15000.00, '1643419936_1_875.jpeg', 1, 1, '2022-01-29 08:32:16', '2022-01-29 14:18:16'),
(4, 'KF-JKT', 'Snack #2', 20000.00, '1643508041_1_721.jpeg', 1, 1, '2022-01-30 09:00:41', '2022-01-30 09:00:41');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `name` varchar(30) NOT NULL,
  `brand` varchar(25) NOT NULL,
  `type` varchar(25) NOT NULL,
  `police_no` varchar(10) NOT NULL,
  `bpkb_no` varchar(25) NOT NULL,
  `stnk_no` varchar(25) NOT NULL,
  `machine_no` varchar(25) NOT NULL,
  `machine_capacity` int(11) NOT NULL,
  `passenger_capacity` int(11) NOT NULL,
  `last_km` double NOT NULL,
  `last_service_date` date NOT NULL,
  `filename` varchar(30) NOT NULL,
  `color` varchar(10) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `location`, `name`, `brand`, `type`, `police_no`, `bpkb_no`, `stnk_no`, `machine_no`, `machine_capacity`, `passenger_capacity`, `last_km`, `last_service_date`, `filename`, `color`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', 'Kendaraan Dinas #1', 'Toyota', 'Avanza', 'B 2506 TII', 'P07468507', '09858241', '1NRGO72546', 1300, 6, 31153, '2021-08-07', '1642662416_1_567.jpeg', '#e75a5a', 1, 1, '2022-01-20 13:26:38', '2022-01-25 15:08:33'),
(2, 'KF-JKT', 'Kendaraan Dinas #2', 'Toyota', 'Kijang Innova', 'B 2263 TYN', 'P03653533', '18113230', '1TRA623291', 2000, 8, 41263, '2021-11-06', '1642662447_1_740.jpeg', '#6db7ba', 1, 1, '2022-01-20 14:05:40', '2022-02-02 13:33:22'),
(3, 'KF-JKT', 'Kendaraan Dinas #3', 'Toyota', 'Kijang Innova', 'B 2543 TKM', 'M13239767', '03887624', '1TRA174140', 2000, 8, 94939, '2021-10-19', '1642662706_1_251.jpeg', '#bd65ad', 1, 1, '2022-01-20 14:11:46', '2022-01-25 15:05:17'),
(4, 'KF-JKT', 'Kendaraan Dinas #4', 'Suzuki', 'Ertiga', 'B 2042 TKY', 'N00679422', '08077720', 'K14BT1202261', 1373, 7, 82672, '2021-11-04', '1642663116_1_843.jpeg', '#cdcb37', 1, 1, '2022-01-20 14:14:50', '2022-01-25 15:09:29');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles_reservation`
--

CREATE TABLE `vehicles_reservation` (
  `id` varchar(30) NOT NULL,
  `location` varchar(15) NOT NULL,
  `destination` varchar(50) NOT NULL,
  `trip_type` varchar(5) NOT NULL,
  `description` text NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `duration` double(10,2) NOT NULL,
  `driver` varchar(50) NOT NULL,
  `driver_confirmed` enum('DISETUJUI','MENOLAK','BELUM MEMUTUSKAN') NOT NULL DEFAULT 'BELUM MEMUTUSKAN',
  `passenger` text NOT NULL,
  `total_passenger` int(11) NOT NULL,
  `start_km` int(11) NOT NULL DEFAULT 0,
  `end_km` int(11) NOT NULL DEFAULT 0,
  `distance` double(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('CREATED','APPROVED','REJECTED','CLOSED') NOT NULL DEFAULT 'CREATED',
  `reason` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vehicles_reservation`
--

INSERT INTO `vehicles_reservation` (`id`, `location`, `destination`, `trip_type`, `description`, `vehicle_id`, `start_date`, `end_date`, `duration`, `driver`, `driver_confirmed`, `passenger`, `total_passenger`, `start_km`, `end_km`, `distance`, `status`, `reason`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('1644539552016', 'KF-JKT', 'Tes', 'drop', 'Tes', 1, '2022-02-01 08:00:00', '2022-02-01 10:00:00', 2.00, 'nurul.anwar@gmail.com', 'DISETUJUI', 'vaturrasta46@gmail.com', 4, 10000, 10250, 250.00, 'CLOSED', '', 1, 1, '2022-02-11 07:31:37', '2022-02-11 07:37:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `building_rooms`
--
ALTER TABLE `building_rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `catherings`
--
ALTER TABLE `catherings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_name` (`vendor_name`,`price`);

--
-- Indexes for table `guest_books`
--
ALTER TABLE `guest_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_id` (`meeting_id`,`email`);

--
-- Indexes for table `meeting_rooms`
--
ALTER TABLE `meeting_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `meeting_rooms_reservation`
--
ALTER TABLE `meeting_rooms_reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `start_date` (`start_date`,`end_date`),
  ADD KEY `name` (`name`,`room_id`);

--
-- Indexes for table `snacks`
--
ALTER TABLE `snacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`,`police_no`);

--
-- Indexes for table `vehicles_reservation`
--
ALTER TABLE `vehicles_reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `start_date` (`start_date`,`end_date`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `building_rooms`
--
ALTER TABLE `building_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `catherings`
--
ALTER TABLE `catherings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `guest_books`
--
ALTER TABLE `guest_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `meeting_rooms`
--
ALTER TABLE `meeting_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `snacks`
--
ALTER TABLE `snacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
