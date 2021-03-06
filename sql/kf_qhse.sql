-- phpMyAdmin SQL Dump
-- version 4.9.10
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 03, 2022 at 10:55 AM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `id` int NOT NULL,
  `parent_id` int DEFAULT '0',
  `sub_id` int NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `type` varchar(5) NOT NULL,
  `size` double NOT NULL,
  `filename` varchar(50) NOT NULL,
  `effective_date` date NOT NULL,
  `revision` int NOT NULL DEFAULT '0',
  `edition` int NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_revisions`
--

CREATE TABLE `file_revisions` (
  `id` int NOT NULL,
  `file_id` int NOT NULL,
  `sub_id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `revision` int NOT NULL,
  `edition` int NOT NULL,
  `revised_by` int NOT NULL,
  `remark` text NOT NULL,
  `type` varchar(5) NOT NULL,
  `size` double NOT NULL,
  `filename` varchar(50) NOT NULL,
  `revision_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `main_folders`
--

CREATE TABLE `main_folders` (
  `id` int NOT NULL,
  `sub_department_id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_folders`
--

CREATE TABLE `sub_folders` (
  `id` int NOT NULL,
  `parent_id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int NOT NULL,
  `updated_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `file_revisions`
--
ALTER TABLE `file_revisions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `main_folders`
--
ALTER TABLE `main_folders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sub_folders`
--
ALTER TABLE `sub_folders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
