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
-- Database: `kf_qhse`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT 0,
  `sub_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL,
  `type` varchar(5) NOT NULL,
  `size` double NOT NULL,
  `filename` varchar(50) NOT NULL,
  `effective_date` date NOT NULL,
  `revision` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `parent_id`, `sub_id`, `name`, `type`, `size`, `filename`, `effective_date`, `revision`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 'File #1', 'doc', 43008, '1643352627_1_103.doc', '2022-01-28', 2, 1, 1, '2022-01-28 13:49:23', '2022-01-28 13:50:27'),
(2, 2, 0, 'Tes', 'pdf', 87862, '1643514216_1_186.pdf', '2022-01-20', 1, 1, 1, '2022-01-30 10:43:39', '2022-01-30 10:43:39');

-- --------------------------------------------------------

--
-- Table structure for table `file_revisions`
--

CREATE TABLE `file_revisions` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `revision` int(11) NOT NULL,
  `revised_by` int(11) NOT NULL,
  `remark` text NOT NULL,
  `type` varchar(5) NOT NULL,
  `size` double NOT NULL,
  `filename` varchar(50) NOT NULL,
  `revision_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `file_revisions`
--

INSERT INTO `file_revisions` (`id`, `file_id`, `sub_id`, `name`, `revision`, `revised_by`, `remark`, `type`, `size`, `filename`, `revision_date`, `created_at`) VALUES
(1, 1, 1, 'File #1', 1, 1, 'First Upload', 'pdf', 864689, '1643352561_1_929.pdf', '2022-01-28', '2022-01-28 13:49:23'),
(2, 1, 1, 'File #1', 2, 1, 'tes', 'doc', 43008, '1643352627_1_103.doc', '2022-01-28', '2022-01-28 13:50:27'),
(3, 2, 1, 'Tes', 1, 1, 'First Upload', 'pdf', 87862, '1643514216_1_186.pdf', '2022-01-30', '2022-01-30 10:43:39');

-- --------------------------------------------------------

--
-- Table structure for table `main_folders`
--

CREATE TABLE `main_folders` (
  `id` int(11) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `main_folders`
--

INSERT INTO `main_folders` (`id`, `sub_department_id`, `name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Folder 1', 1, 1, '2022-01-28 13:35:50', '2022-01-28 13:35:50'),
(2, 1, 'Folder 2', 1, 1, '2022-01-28 13:37:14', '2022-01-28 13:37:14'),
(3, 1, 'Folder 3', 1, 1, '2022-01-28 13:38:41', '2022-01-28 13:38:41'),
(4, 1, 'Folder 4', 1, 1, '2022-01-28 13:41:46', '2022-01-28 13:41:46'),
(5, 1, 'Folder 5', 1, 1, '2022-01-28 13:42:13', '2022-01-28 13:42:13'),
(6, 5, 'Folder #1', 1, 1, '2022-02-11 07:38:06', '2022-02-11 00:00:00'),
(7, 5, 'Folder #2', 1, 1, '2022-02-11 07:38:12', '2022-02-11 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sub_folders`
--

CREATE TABLE `sub_folders` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sub_folders`
--

INSERT INTO `sub_folders` (`id`, `parent_id`, `name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sub Folder #1', 1, 1, '2022-01-28 13:37:23', '2022-01-28 13:37:23'),
(2, 2, 'Sub Folder #1', 1, 1, '2022-01-28 13:41:24', '2022-01-28 13:41:24'),
(3, 3, 'Sub Folder #1', 1, 1, '2022-01-28 13:41:31', '2022-01-28 13:41:31'),
(4, 4, 'Sub Folder #1', 1, 1, '2022-01-28 13:42:22', '2022-01-28 13:42:22'),
(5, 5, 'Sub Folder #1', 1, 1, '2022-01-28 13:42:31', '2022-01-28 13:42:31'),
(6, 6, 'Sub.1 Folder #1 ', 1, 1, '2022-02-11 07:38:33', '2022-02-11 00:00:00'),
(7, 6, 'Sub.2 Folder #2', 1, 1, '2022-02-11 07:38:42', '2022-02-11 00:00:00'),
(8, 7, 'Sub.1 Folder #2', 1, 1, '2022-02-11 07:38:51', '2022-02-11 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `department_id` (`parent_id`),
  ADD KEY `sub_id` (`sub_id`);

--
-- Indexes for table `file_revisions`
--
ALTER TABLE `file_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`,`filename`);

--
-- Indexes for table `main_folders`
--
ALTER TABLE `main_folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `department_id` (`sub_department_id`);

--
-- Indexes for table `sub_folders`
--
ALTER TABLE `sub_folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `department_id` (`parent_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `file_revisions`
--
ALTER TABLE `file_revisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `main_folders`
--
ALTER TABLE `main_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sub_folders`
--
ALTER TABLE `sub_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
