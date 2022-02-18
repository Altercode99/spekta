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
-- Database: `kf_hr`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `location`, `name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', 'Produksi', 1, 1, '2022-01-19 15:03:32', '2022-01-19 15:03:32'),
(2, 'KF-JKT', 'Pemastian Mutu', 1, 1, '2022-01-19 15:03:50', '2022-01-19 15:03:50'),
(3, 'KF-JKT', 'Plant Manager', 1, 1, '2022-01-19 15:04:05', '2022-01-19 15:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `department_id` int(11) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `location`, `department_id`, `sub_department_id`, `name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(0, 'KF-JKT', 0, 0, '-', 1, 1, '2022-01-20 09:46:36', '2022-01-20 09:46:20'),
(1, 'KF-JKT', 1, 1, 'Granulasi Tablet', 1, 1, '2022-01-19 15:08:29', '2022-01-19 15:08:29'),
(2, 'KF-JKT', 1, 1, 'Pencetakan Tablet', 1, 1, '2022-01-19 15:08:39', '2022-01-19 15:08:39'),
(3, 'KF-JKT', 1, 1, 'Penyalutan Tablet', 1, 1, '2022-01-19 15:08:51', '2022-01-19 15:08:51'),
(4, 'KF-JKT', 1, 2, 'Pengolahan Krim', 1, 1, '2022-01-19 15:18:39', '2022-01-19 15:18:39'),
(5, 'KF-JKT', 1, 2, 'Pengolahan Cairan', 1, 1, '2022-01-19 15:18:47', '2022-01-19 15:18:47'),
(6, 'KF-JKT', 1, 2, 'Pengolahan Injeksi', 1, 1, '2022-01-19 15:18:58', '2022-01-19 15:18:58'),
(7, 'KF-JKT', 1, 2, 'Pengolahan Kapsul & Sirup Kering', 1, 1, '2022-01-19 15:19:13', '2022-01-26 13:30:11'),
(8, 'KF-JKT', 1, 3, 'Pengolahan ARV', 1, 1, '2022-01-19 15:19:29', '2022-01-19 15:19:29'),
(9, 'KF-JKT', 1, 3, 'Pengemasan ARV', 1, 1, '2022-01-19 15:19:49', '2022-01-19 15:19:49'),
(10, 'KF-JKT', 1, 4, 'Karantina Dalam Proses', 1, 1, '2022-01-19 15:20:01', '2022-01-19 15:20:01'),
(11, 'KF-JKT', 1, 4, 'Pengemasan Primer', 1, 1, '2022-01-19 15:20:09', '2022-01-19 15:20:09'),
(12, 'KF-JKT', 1, 4, 'Pengemasan Sekunder Tablet & Kapsul', 1, 1, '2022-01-19 15:20:25', '2022-01-26 13:30:25'),
(13, 'KF-JKT', 1, 4, 'Pengemasan Sekunder Non Tablet & Kapsul', 1, 1, '2022-01-19 15:20:58', '2022-02-10 12:58:35'),
(14, 'KF-JKT', 1, 1, 'Rifampicin', 1, 1, '2022-01-19 15:21:43', '2022-01-19 15:21:43'),
(15, 'KF-JKT', 1, 5, 'Mekanik', 1, 1, '2022-01-19 15:21:54', '2022-01-30 13:36:55'),
(16, 'KF-JKT', 1, 5, 'Utility', 1, 1, '2022-01-19 15:22:01', '2022-01-30 13:39:53'),
(17, 'KF-JKT', 1, 5, 'Listrik', 1, 1, '2022-01-19 15:22:13', '2022-01-19 15:22:13'),
(18, 'KF-JKT', 1, 5, 'Hardware & Network', 1, 1, '2022-01-19 15:22:22', '2022-01-26 13:25:36'),
(19, 'KF-JKT', 3, 8, 'Pemeriksaan Bahan Baku', 1, 1, '2022-01-19 15:23:04', '2022-01-19 15:23:04'),
(20, 'KF-JKT', 3, 8, 'Pemeriksaan Bahan Pengemas', 1, 1, '2022-01-19 15:23:17', '2022-01-19 15:23:17'),
(21, 'KF-JKT', 3, 8, 'Pemeriksaan Produk Antara & Ruahan', 1, 1, '2022-01-19 15:23:47', '2022-01-26 13:31:39'),
(22, 'KF-JKT', 3, 8, 'Pemeriksaan Mikrobiologi & Limbah', 1, 1, '2022-01-19 15:24:16', '2022-01-26 13:33:10'),
(23, 'KF-JKT', 3, 8, 'Pemeriksaan Produk Jadi', 1, 1, '2022-01-19 15:24:36', '2022-01-19 15:24:36'),
(24, 'KF-JKT', 3, 8, 'Pengawasan Proses Produksi', 1, 1, '2022-01-19 15:24:48', '2022-01-19 15:24:48'),
(25, 'KF-JKT', 2, 6, 'Pengembangan Desain & Formula Bahan Pengemas', 1, 1, '2022-01-19 15:25:13', '2022-01-26 13:28:41'),
(26, 'KF-JKT', 2, 6, 'Pengembangan Formula Produk', 1, 1, '2022-01-19 15:25:29', '2022-01-19 15:25:29'),
(27, 'KF-JKT', 2, 7, 'Pengendalian Dokumen, Regulasi & Penanganan Keluhan', 1, 1, '2022-01-19 15:25:55', '2022-01-26 13:31:09'),
(28, 'KF-JKT', 2, 7, 'Validasi', 1, 1, '2022-01-19 15:26:36', '2022-01-19 15:26:36'),
(29, 'KF-JKT', 2, 7, 'Stabilitas', 1, 1, '2022-01-19 15:26:48', '2022-01-19 15:26:48'),
(30, 'KF-JKT', 2, 7, 'Inspeksi & Audit', 1, 1, '2022-01-19 15:26:58', '2022-01-26 13:31:53'),
(31, 'KF-JKT', 2, 7, 'Kualifikasi & Kalibrasi', 1, 1, '2022-01-19 15:27:11', '2022-01-26 13:32:58'),
(33, 'KF-JKT', 3, 9, 'Pengendalian Proses Produksi', 1, 1, '2022-01-19 15:28:10', '2022-01-19 15:28:10'),
(34, 'KF-JKT', 3, 12, 'Umum', 1, 1, '2022-01-19 15:28:32', '2022-01-19 15:28:32'),
(35, 'KF-JKT', 3, 12, 'K3', 1, 1, '2022-01-19 15:28:40', '2022-01-19 15:29:01'),
(36, 'KF-JKT', 3, 12, 'Lingkungan', 1, 1, '2022-01-19 15:28:49', '2022-01-19 15:28:49'),
(37, 'KF-JKT', 3, 10, 'Pembelian Sparepart Mesin Produksi', 1, 1, '2022-01-19 15:29:23', '2022-01-19 15:29:23'),
(38, 'KF-JKT', 3, 11, 'Administrasi Personalia', 1, 1, '2022-01-19 15:29:43', '2022-01-19 15:29:43'),
(39, 'KF-JKT', 3, 11, 'Pelatihan & Kinerja Pegawai', 1, 1, '2022-01-19 15:29:57', '2022-01-26 13:33:22'),
(40, 'KF-JKT', 3, 11, 'Akuntansi', 1, 1, '2022-01-19 15:30:10', '2022-01-19 15:30:10'),
(41, 'KF-JKT', 3, 11, 'Keuangan', 1, 1, '2022-01-19 15:30:18', '2022-01-19 15:30:18'),
(42, 'KF-JKT', 3, 13, 'Gudang Bahan Baku', 1, 1, '2022-01-19 15:30:34', '2022-01-19 15:30:34'),
(43, 'KF-JKT', 3, 13, 'Gudang Bahan Pengemas', 1, 1, '2022-01-19 15:30:44', '2022-01-19 15:30:44'),
(44, 'KF-JKT', 3, 13, 'Penimbangan Sentral', 1, 1, '2022-01-19 15:30:59', '2022-01-19 15:30:59'),
(45, 'KF-JKT', 3, 13, 'Penandaan Kemasan Sekunder', 1, 1, '2022-01-19 15:31:15', '2022-01-19 15:31:15'),
(46, 'KF-JKT', 1, 5, 'Sipil', 1, 1, '2022-01-20 11:45:49', '2022-01-20 11:46:24');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `nip` varchar(15) NOT NULL,
  `location` varchar(15) NOT NULL,
  `sap_id` varchar(15) NOT NULL,
  `parent_nik` varchar(15) NOT NULL,
  `nik` varchar(25) NOT NULL,
  `npwp` varchar(25) NOT NULL,
  `employee_name` varchar(35) NOT NULL,
  `birth_place` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('Laki-Laki','Perempuan') NOT NULL,
  `religion` enum('Islam','Kristen','Katholik','Budha','Hindu','Konghucu','Penganut Kepercayaan') NOT NULL,
  `age` int(11) NOT NULL,
  `employee_status` varchar(15) NOT NULL,
  `os_name` varchar(30) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `division_id` int(11) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `sk_number` varchar(25) NOT NULL,
  `sk_date` date NOT NULL,
  `sk_start_date` date NOT NULL,
  `sk_end_date` date NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `overtime` int(11) NOT NULL DEFAULT 0,
  `direct_spv` varchar(15) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `nip`, `location`, `sap_id`, `parent_nik`, `nik`, `npwp`, `employee_name`, `birth_place`, `birth_date`, `gender`, `religion`, `age`, `employee_status`, `os_name`, `address`, `phone`, `mobile`, `email`, `division_id`, `sub_department_id`, `department_id`, `rank_id`, `location_id`, `user_id`, `sk_number`, `sk_date`, `sk_start_date`, `sk_end_date`, `status`, `overtime`, `direct_spv`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '9999', 'KF-JKT', '9999', '9999', '9999', '9999', 'Administrator', 'Bekasi', '1993-09-28', 'Laki-Laki', 'Penganut Kepercayaan', 28, 'Kontrak OS', 'PT. Kreasiboga Primatama', 'Bekasi', '02199998888', '089517227009', 'alternate.septian@gmail.com', 18, 5, 1, 7, 1, 1, 'SK01', '2022-01-02', '2022-01-02', '2022-12-31', 'ACTIVE', 0, '', 1, 1, '2022-01-19 15:42:34', '2022-01-19 15:42:34'),
(18, '19890703B', 'KF-JKT', '10000106', '', '3328150307890007', '', 'ANGGA SAENAGRI', 'Pemalang', '1989-07-03', 'Laki-Laki', 'Islam', 33, 'Permanen', '-', 'TRANSBOGE VILLAGE BLOK J 26', '', '0811818758', 'angga.saenagri@kimiafarma.co.id', 0, 0, 2, 2, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000119', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(19, '19870410B', 'KF-JKT', '10000113', '', '3204110101800094', '462887159111000', 'YOGI SUGIANTO', 'Tj. Sarang Elang', '1987-04-10', 'Laki-Laki', 'Islam', 35, 'Permanen', '-', 'PERUMAHAN PONDOK NUSANTARA BLOK F NO 20', '', '081396000128', 'yogi.sugianto@kimiafarma.co.id', 0, 0, 1, 2, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000119', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(20, '19880210A', 'KF-JKT', '10000119', '', '3404021002880001', '', 'YURISTA GILANG IKHTIARSYAH', 'Sleman', '1988-02-02', 'Laki-Laki', 'Islam', 34, 'Permanen', '-', 'NGLARANG RT 05 RW 11 SIDOARUM GODEAN', '', '08998281486', 'yurista.gilang@kimiafarma.co.id', 0, 0, 3, 1, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:40:36'),
(21, '19690112A', 'KF-JKT', '10000507', '', '3171031201690004', '071779060027000', 'BUDI PRAYITNO', 'Jakarta', '1969-01-12', 'Laki-Laki', 'Islam', 53, 'Permanen', '-', 'JL. BENDUNGAN JAGO NO.13 RT.17 RW.03', '', '082249327757', 'budiprayitno1201@yahoo.co.id', 37, 10, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000195', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(22, '19700101A', 'KF-JKT', '10000195', '', '3171030101700024', '689205680027000', 'MUHAMAD ISAPUDIN JANUAR', 'Jakarta', '1970-01-01', 'Laki-Laki', 'Islam', 52, 'Permanen', '-', 'JL. BENDUNGAN JAGO RT.17 RW.03', '', '08161341748', 'isapudin.januar@kimiafarma.co.id', 37, 10, 3, 6, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000057', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(23, '19660911A', 'KF-JKT', '10000057', '', '3171055109660002', '87826723024000', 'YANTI HARDIYANTI', 'Jakarta', '1966-09-11', 'Perempuan', 'Islam', 55, 'Permanen', '-', 'JL. TAMAN MALAKA BARAT BLOK E3/19.RT 001/09', '', '08111777264', 'yanti.hardiyanti@kimiafarma.co.id', 0, 10, 3, 3, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000119', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(24, '19940525A', 'KF-JKT', '10001206', '', '3275062505940004', '748082765427000', 'ABDUL ANAM', 'Bekasi', '1994-05-25', 'Laki-Laki', 'Islam', 28, 'Permanen', '-', 'KP. TUNAS JAYA JL. WIJAYA KUSUMA I', '', '085780509919', 'abdulanam51@gmail.com', 22, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000483', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(25, '19891203A', 'KF-JKT', '10000483', '', '3175034312890001', '269460929002000', 'AFIFI HILMIYANTI', 'Jakarta', '1989-12-03', 'Perempuan', 'Islam', 32, 'Permanen', '-', 'Jl. PANCA WARGA 9 NO.17 RT.02 RW.01', '', '087886588786', 'afifi.hilmiyanti@kimiafarma.co.id', 22, 8, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000222', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:42:26'),
(26, '19931122B', 'KF-JKT', '10001188', '', '3209272211930001', '911409407435000', 'AKHMAD MUJANI', 'Cirebon', '1993-11-22', 'Laki-Laki', 'Islam', 28, 'Permanen', '-', 'DUSUN MAJASRI RT.025 RW.005', '', '085775408592', 'akhmadmujani1122@gmail.com', 24, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000639', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(27, '19950315A', 'KF-JKT', '10001205', '', '3271065503950004', '725105571404000', 'AMELIA NURFAJRINA', 'Jakarta', '1995-03-15', 'Perempuan', 'Islam', 27, 'Permanen', '-', 'PONDOK RUMPUT', '', '081517074545', 'amelianurfajrina48@gmail.com', 21, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000190', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(28, '19920508A', 'KF-JKT', '10000495', '', '3573020805920002', '725077648008000', 'ANDI ANDARA PRAYOGA', 'Jakarta', '1992-05-08', 'Laki-Laki', 'Islam', 30, 'Permanen', '-', 'Jl. Nyai Putu Bawah No. 71 RT/RW 010/005', '', '081312382922', 'kaliztix@yahoo.com', 24, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000639', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(29, '19880925A', 'KF-JKT', '10000484', '', '3275036509880015', '683647309407000', 'ARINI SEPRIANTY', 'Jakarta', '1988-09-25', 'Perempuan', 'Islam', 33, 'Permanen', '-', 'PERUM DUTA KRANJI C 245', '', '081295675970', 'arinianty88@gmail.com', 20, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000214', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(30, '19970130A', 'KF-JKT', '10001191', '', '3402153001970001', '745746222543000', 'BAGUS GALIH WICAKSANA', 'Bantul', '1997-01-30', 'Laki-Laki', 'Islam', 25, 'Permanen', '-', 'DAGEN RT.003', '', '08993146389', 'bagusgalih059@gmail.com', 19, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000480', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(31, '19940706A', 'KF-JKT', '10001193', '', '3175060607940003', '908069487006000', 'BAYU ADJI PRASETYO', 'Mojokerto', '1994-07-06', 'Laki-Laki', 'Islam', 28, 'Permanen', '-', 'JALAN TAMBUN RENGAS RT 001/08, NO. 10, CAKUNG, JAKARTA', '', '081281969021', 'bayuadjip20@gmail.com', 23, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000492', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(32, '19921202A', 'KF-JKT', '10000479', '', '3172044212920009', '464557712045000', 'DESY DWI PURWANTI', 'Jakarta', '1992-12-02', 'Perempuan', 'Islam', 29, 'Permanen', '-', 'JL. ANANTA 3 NO.HH4 RT.007  RW.002 SEMPER BARAT CILINCING', '', '081280330533', 'desy.dwipurwanti@rocketmail.com', 22, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000483', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(33, '19880726B', 'KF-JKT', '10000480', '', '3275092607880012', '468162722432000', 'DIMAS PRASETYA KUSUMA', 'Jakarta', '1988-07-26', 'Laki-Laki', 'Islam', 34, 'Permanen', '-', 'KP. RAWA AREN NO.30 RT.002 RW.012', '', '085710194192', 'dimas.p@kimiafarma.co.id', 19, 8, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000222', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(34, '19770725A', 'KF-JKT', '10000214', '', '3216066507770015', '473926590413000', 'GETA SETYOWATI', 'Sukoharjo', '1977-07-25', 'Perempuan', 'Katholik', 45, 'Permanen', '-', 'JL. CIBULAN III BLOK B NO.21 RT.02 RW.13', '', '082218186007', 'geta.setyowati@kimiafarma.co.id', 20, 8, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000222', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(35, '19890620B', 'KF-JKT', '10000639', '', '3175072006890005', '451205751008000', 'IRVAN BASTIAN', 'Jakarta', '1989-06-20', 'Laki-Laki', 'Islam', 33, 'Permanen', '-', 'JL. PONDOK KELAPA SELATAN', '', '081310575539', 'irvan.bastian@kimiafarma.co.id', 24, 8, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000222', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(36, '19910224B', 'KF-JKT', '10000222', '', '3507246402910004', '711428011657000', 'LARASTUTI JAMI MUKTI SABATANI', 'Malang', '1991-02-24', 'Perempuan', 'Islam', 31, 'Permanen', '-', 'JALAN DELMAN RAYA NO.412A TANAH KUSIR, KEBAYORAN LAMA, JAKARTA SELATAN', '', '085645298575', 'larastuti@kimiafarma.co.id', 0, 8, 3, 3, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000119', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(37, '19670808B', 'KF-JKT', '10000493', '', '3216024808670004', '473926673413000', 'LELAH HOLILAH', 'Bogor', '1967-08-08', 'Perempuan', 'Islam', 55, 'Permanen', '-', 'PONDOK UNGU PERMAI BLOK AL8 NO.26 RT.04 RW.11', '', '085890982069', 'lelaholilah56@gmail.com', 20, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000214', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(38, '19940922A', 'KF-JKT', '10000488', '', '3275032209940015', '724876024407000', 'MAULANA FIRDAUS', 'Bekasi', '1994-09-22', 'Laki-Laki', 'Islam', 27, 'Permanen', '-', 'PERUMAHAN SBS JALAN DANAU TOBA V BLOK CD11 NO.2 RT.008 RW.011', '', '082231317171', 'maulana22.mf@gmail.com', 21, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000190', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(39, '19871008A', 'KF-JKT', '10000492', '', '3173020811870006', '453055345036000', 'MUHAMAD ZEFRI', 'Kuningan', '1987-10-08', 'Laki-Laki', 'Islam', 34, 'Permanen', '-', 'BINTARA 3 GG H.MAMAD NO 10E RT 005 RW 009 BINTARA JAYA BEKASI BARAT', '', '08994373358', 'muhamad.zefri@kimiafarma.co.id', 23, 8, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000222', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(40, '19760103B', 'KF-JKT', '10000190', '', '3275030301760015', '72860331407000', 'MUSLIM', 'Jakarta', '1976-01-03', 'Laki-Laki', 'Islam', 46, 'Permanen', '-', 'JL. TELAGA MAS XI BLOK H1 NO.34 RT.012 RW.014', '', '08561058276', 'muslim.mz@kimiafarma.co.id', 21, 8, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000222', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(41, '19911104A', 'KF-JKT', '10000496', '', '3402120411910004', '724872882543000', 'NOFA EKO NURDIYANTO', 'Bantul', '1991-11-04', 'Laki-Laki', 'Islam', 30, 'Permanen', '-', 'JL. SAMPANGAN', '', '085743177847', 'nofaekonurdiyanto04@gmail.com', 23, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000214', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(42, '19680725A', 'KF-JKT', '10000486', '', '3173022507680002', '076400720031000', 'NUR RACHMAN', 'Jakarta', '1968-07-25', 'Laki-Laki', 'Islam', 54, 'Permanen', '-', 'PERUM. PAPAN INDAH 1 BLOK i13A NO. 22 RT. 04, RW. 24', '', '081282027679', 'nurrachman2507@gmail.com', 23, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000492', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(43, '19901016A', 'KF-JKT', '10001202', '', '3603125610900001', '368830634418000', 'OCTAVIA RATNA SARI', 'Jakarta', '1990-10-16', 'Perempuan', 'Islam', 31, 'Permanen', '-', 'Kp. Rawa Sapi RT003/RW010', '', '085724148118', 'octaviaratnas@yahoo.com', 21, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000190', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(44, '19891101A', 'KF-JKT', '10000491', '', '3276084111890001', '725016505421000', 'ROFI ALIZAH', 'Jakarta', '1989-11-01', 'Perempuan', 'Islam', 32, 'Permanen', '-', 'JL. MASJID AL-MUJAHIDIN RT.02 RW.05 NO.56 KEL.MERUYUNG KEC.LIMO KOTA DEPOK', '', '081293924564', 'rofializah1989@gmail.com', 20, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000214', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(45, '19930624A', 'KF-JKT', '10000482', '', '3275036406930019', '725134092407000', 'SITI SYAMSINA WAHIDIAH', 'Bekasi', '1993-06-24', 'Perempuan', 'Islam', 29, 'Permanen', '-', 'JL. PERJUANGAN DALAM TELUK BUYUNG NO.39 RT.003 RW.011', '', '081381943452', 'tisyaam@gmail.com', 19, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000214', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(46, '19690101C', 'KF-JKT', '10000487', '', '3215250101690015', '075499566004000', 'WAGINO', 'Jakarta', '1969-01-01', 'Laki-Laki', 'Islam', 53, 'Permanen', '-', 'Perumahan Bumi Pucung Baru Blok S. No. 7 Rt. 003.Rw.011', '', '081283376038', 'waginozino03@gmail.com', 20, 8, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000214', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(47, '19961117A', 'KF-JKT', '10001270', '', '3402151711960002', '835816828543000', 'AHMAD NURLY ROHMAN', 'Bantul', '1996-11-17', 'Laki-Laki', 'Islam', 25, 'Permanen', '-', 'NGOTO RT.001 BANGUNHARJO, SEWON', '', '081779406571', 'ahmadnurly@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(48, '19790925A', 'KF-JKT', '10000605', '', '3173072509790003', '689205813031000', 'ANDRIYAN', 'Jakarta', '1979-09-25', 'Laki-Laki', 'Islam', 42, 'Permanen', '-', 'DARMAWANGSA RESIDENCE BEKASI (PRAMBANAN) BP7 NO.27', '', '081808160393', 'andriyandiden@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(49, '19740619B', 'KF-JKT', '10000606', '', '3275121906740004', '689205847432000', 'ARIANTO', 'Jakarta', '1974-06-19', 'Laki-Laki', 'Islam', 48, 'Permanen', '-', 'JL.H.HARUN 5 NO.16 RT.03 RW.10', '', '081386751703', 'ariaanto8@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(50, '19711117A', 'KF-JKT', '10000595', '', '3175061711710018', '689206365432000', 'BARLI RAMANDANI', 'Jakarta', '1971-11-17', 'Laki-Laki', 'Islam', 50, 'Permanen', '-', 'KAMPUNG LIO RT.08 RW.03', '', '083898739563', 'bramandani8@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(51, '19690906B', 'KF-JKT', '10000607', '', '3175060609690013', '689205532004000', 'DANI HASYIM AS\'ARI', 'Tasikmalaya', '1969-09-06', 'Laki-Laki', 'Islam', 52, 'Permanen', '-', 'KP.PENGARENGAN RT.06 RW.12 JATINEGARA CAKUNG', '', '085714486342', 'dani.hasyim69@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(52, '19670824B', 'KF-JKT', '10000588', '', '3175036408670005', '072860281002000', 'DJAMILAH', 'Jakarta', '1967-08-24', 'Perempuan', 'Islam', 54, 'Permanen', '-', 'JL.PANCA WARGA IV GG.03 NO.2. RT.03 RW.04', '', '08561824127', 'missdjamilah@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(53, '19730328B', 'KF-JKT', '10000209', '', '3216022803730006', '473932077413000', 'FAHRIADI', 'Maninjau', '1973-03-28', 'Laki-Laki', 'Islam', 49, 'Permanen', '-', 'PUP SEKTOR V BLOK M4/11 RT.09 RW.29', '', '081382656963', 'fahriadi@kimiafarma.co.id', 11, 4, 1, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000066', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:52:58'),
(54, '19800721B', 'KF-JKT', '10000216', '', '3275116107800005', '689206472006000', 'GITA MAHDI TIARA', 'Jakarta', '1980-07-21', 'Perempuan', 'Islam', 42, 'Permanen', '-', 'PERUM GRAND REGENCY BLOK C5/5 RT.02 RW.22', '', '085718487633', 'gita.mt@kimiafarma.co.id', 13, 4, 1, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000066', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(55, '19660721A', 'KF-JKT', '10000596', '', '3175022107660003', '075398347001000', 'HARY SULISTIYO', 'Jakarta', '1966-07-21', 'Laki-Laki', 'Islam', 56, 'Permanen', '-', 'JL.PISANGAN LAMA III NO.26 RT.05 RW.06', '', '085695417890', 'haryysulistyo23@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(56, '19841031B', 'KF-JKT', '10000597', '', '3171033110841001', '090558156027000', 'ILHAM SATRIA LUBIS', 'Medan', '1984-10-31', 'Laki-Laki', 'Islam', 37, 'Permanen', '-', 'GG. CEMPAKA BARU TIMUR II RT.01 RW.05', '', '087707721227', 'ilhamlubis311084@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(57, '19660126A', 'KF-JKT', '10000635', '', '3175016801660003', '473921211001000', 'IMASIH', 'Jakarta', '1966-01-28', 'Perempuan', 'Islam', 56, 'Permanen', '-', 'JL. SALEMBA UTAN BARAT RT.11 RW.07', '', '089605556810', 'imasihkimiafarma@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(58, '19870730A', 'KF-JKT', '10000066', '', '3515183007870002', '361422801643000', 'IRVING WIDYAWAN', 'Yogyakarta', '1987-07-30', 'Laki-Laki', 'Islam', 35, 'Permanen', '-', 'JL.MERAK 1B /25 BLOK P.66 RT.02 RW.09', '', '085850603365', 'irving@kimiafarma.co.id', 0, 4, 1, 3, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000113', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(59, '19670717B', 'KF-JKT', '10000575', '', '3275031707670009', '473931806407000', 'JUANDIE', 'Jakarta', '1967-07-17', 'Laki-Laki', 'Islam', 55, 'Permanen', '-', 'KP. BARU JL. SWADAYA 6. RT.08 RW.17', '', '089602548609', 'jjuandi043@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(60, '19670725A', 'KF-JKT', '10001121', '', '1271096507670003', '', 'JULI DARMI PILIANG', 'Medan', '1967-07-25', 'Perempuan', 'Islam', 55, 'Permanen', '-', 'JL. SM. RAJA XII KM. 9 NO.4', '', '085262982614', 'julidarmi16122016@gmail.com', 12, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000211', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(61, '19890617A', 'KF-JKT', '10000211', '', '3275015706890017', '784593923407000', 'KURNIAWATI HIDAYAH', 'Jakarta', '1992-10-22', 'Perempuan', 'Islam', 29, 'Permanen', '-', 'TAMAN WISMA ASRI 2 BLOK AA23 No. 6 Jl. GANDARIA UTARA RT06 RW 022 TELUK PUCUNG BEKASI UATARA', '', '087881456923', 'kurniawati.hidayah@kimiafarma.co.id', 12, 4, 1, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000066', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:53:35'),
(62, '19760414A', 'KF-JKT', '10000609', '', '3175031404760008', '689205854002000', 'M. BAYU PUJO H.', 'Jakarta', '1976-04-14', 'Laki-Laki', 'Islam', 46, 'Permanen', '-', 'JL. CATUR TUNGGAL RT.14 RW.01', '', '08980475960', 'bayuar64@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(63, '19940502A', 'KF-JKT', '10001274', '', '3172040205940008', '547985499045000', 'MUHAMMAD REZA FIRDAUS', 'Jakarta', '1994-05-02', 'Laki-Laki', 'Islam', 28, 'Permanen', '-', 'JL. SUKAPURA JAYA RT.005 RW.010', '', '081272000260', 'rezafirdaus60@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(64, '19700717A', 'KF-JKT', '10000603', '', '3175085707700003', '689205821005000', 'MUNINGSIH', 'Kebumen', '1970-07-17', 'Perempuan', 'Islam', 52, 'Permanen', '-', 'KP. BARU I RT.03 RW.05', '', '085693779562', 'muningsihsuheri@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(65, '19660409B', 'KF-JKT', '10000598', '', '3201130904660001', '75798868015000', 'NGADIONO', 'Jakarta', '1966-04-09', 'Laki-Laki', 'Islam', 56, 'Permanen', '-', 'KP. GEDONG RT.02 RW.24', '', '081511465586', 'tehbotol123321@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(66, '19680428B', 'KF-JKT', '10000623', '', '3174036804680007', '095255873014000', 'NURDIANAH', 'Jakarta', '1968-04-28', 'Perempuan', 'Islam', 54, 'Permanen', '-', 'JL.MAMPANG PRAPATAN VI NO.32 RT.03 RW.02', '', '089609358161', 'nurdianah2868@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(67, '19720816A', 'KF-JKT', '10000610', '', '3175061608720034', '689205839006000', 'PURNOMO', 'Pacitan', '1972-08-16', 'Laki-Laki', 'Islam', 50, 'Permanen', '-', 'KAYU TINGGI RT.04 RTW.04', '', '081385175930', 'pur160872@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(68, '19670202B', 'KF-JKT', '10000584', '', '3175064202670001', '473932135006000', 'RATIH HANDAYANI', 'Jakarta', '1967-02-02', 'Perempuan', 'Islam', 55, 'Permanen', '-', 'JL. RAWABEBEK RT.11 RW.01', '', '081919660202', 'ratihhandayani1967@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(69, '19840527B', 'KF-JKT', '10000600', '', '3175012705840002', '689206373001000', 'RICAT SETIAWAN', 'Jakarta', '1984-05-27', 'Laki-Laki', 'Islam', 38, 'Permanen', '-', 'JL.KEBON KELAPA RT.010 RW.09', '', '085892650585', 'albaunited84@gmail.com', 11, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(70, '19900511B', 'KF-JKT', '10001282', '', '3203041105900013', '726303324406000', 'RIDWAN MUHAMAD TAUFIK', 'Cianjur', '1990-05-11', 'Laki-Laki', 'Islam', 32, 'Permanen', '-', 'KP. NOLED', '', '085880393471', 'dizta788@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(71, '19950130A', 'KF-JKT', '10001750', '', '3216033001950002', '641502950435000', 'RIZA KUSMAYADI', 'Jakarta', '1995-01-30', 'Laki-Laki', 'Islam', 27, 'Permanen', '-', 'KP. PERTANIAN RT RW 001 / 003 KEL KLENDER', '', '085710355510', 'rizakusmayadi@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(72, '19740517A', 'KF-JKT', '10000611', '', '3175071705740019', '689206332008000', 'ROHADI', 'Jakarta', '1974-05-17', 'Laki-Laki', 'Islam', 48, 'Permanen', '-', 'KP. MALAKA RT.03 RW.02', '', '087804126766', 'chelseaadifit2@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(73, '19680131B', 'KF-JKT', '10001109', '', '1271183101680003', '097148795122000', 'RUSDIANSYAH', 'Medan', '1968-01-31', 'Laki-Laki', 'Islam', 54, 'Permanen', '-', 'KOMP.GRIYA PESONA MINIMALIS BLOK BB NO 63 LK 09', '', '085282302579', 'rusdiansyahkfplant@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(74, '19680319B', 'KF-JKT', '10000613', '', '3275041903680008', '689206605407000', 'SARI MURDIN', 'Bekasi', '1968-03-19', 'Laki-Laki', 'Islam', 54, 'Permanen', '-', 'JL. BINTARA 8 NO.48 RT.05 RW.03', '', '083806835845', 'sarimurdin567@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(75, '19711101A', 'KF-JKT', '10000627', '', '3519014501770004', '473911949041000', 'SITI INDIAWATI', 'Jakarta', '1971-11-01', 'Perempuan', 'Islam', 50, 'Permanen', '-', 'JL. TANAH PASIR RT.03 RW.09', '', '08988753255', 'siti.indiawati@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(76, '19680515B', 'KF-JKT', '10000628', '', '3175035505680002', '689205581002000', 'SITI MAESAROH', 'Jakarta', '1968-05-15', 'Perempuan', 'Islam', 54, 'Permanen', '-', 'JL. PEMBINA I RT.12 RW.02', '', '081323407764', 'smaisaroh707@gmail.com', 12, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000211', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(77, '19671122A', 'KF-JKT', '10001113', '', '1271146211670004', '', 'SUMARNI', 'Medan', '1967-11-22', 'Perempuan', 'Islam', 54, 'Permanen', '-', 'JL. LETDA SUJONO GG. JAWA NO 3B', '', '082362307705', 'marnisum968@gmail.com', 13, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(78, '19690202A', 'KF-JKT', '10000619', '', '3275094202690007', '075576835005000', 'SURYAMAH', 'Ciamis', '1969-02-02', 'Perempuan', 'Islam', 53, 'Permanen', '-', 'JL.TANAH 80 NO.22H', '', '081224083180', 'suryamahtea@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(79, '19771113A', 'KF-JKT', '10000550', '', '3174091311770003', '689205862017000', 'SUWANDI', 'Blitar', '1977-11-13', 'Laki-Laki', 'Islam', 44, 'Permanen', '-', 'GG. GURU RT 06 RW.02 No 26  Lenteng Agung Jakarta selatan', '', '081229520142', 'wandi.aten77@gmail.com', 10, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001833', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(80, '19711012A', 'KF-JKT', '10000631', '', '3201135210710008', '473926079015000', 'TIHAWA', 'Jakarta', '1971-10-12', 'Perempuan', 'Islam', 50, 'Permanen', '-', 'KAMPUNG PABUARAN RT.04 RW.05', '', '085777693821', 'tihawaawe1210@gmail.com', 12, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000211', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(81, '19770109A', 'KF-JKT', '10000576', '', '3216020901770007', '689206415006000', 'UNANG HERNAWAN', 'Sumedang', '1977-01-09', 'Laki-Laki', 'Islam', 45, 'Permanen', '-', 'PUP. SEKTOR V BLOK D14/20 RT.12 RW.22', '', '081383991208', 'unanghernawan6@gmail.com', 11, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(82, '19711015A', 'KF-JKT', '10000601', '', '3172041510710015', '689206340045000', 'WACHYUDIN', 'Jakarta', '1971-10-15', 'Laki-Laki', 'Islam', 50, 'Permanen', '-', 'JLN. KALIBARU TIMUR III RT 006 RW 003', '', '08128601546', 'didin0747@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(83, '19740905B', 'KF-JKT', '10000614', '', '3175060509740003', '473922599002000', 'WAHYUDI', 'Jakarta', '1974-09-05', 'Laki-Laki', 'Islam', 47, 'Permanen', '-', 'JL.GAMPRIT 1 RT06/06 NO 2', '', '081314011745', 'joyyudi74@gmail.com', 10, 4, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001833', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(84, '19930317A', 'KF-JKT', '10001833', '', '6471051703930007', '833778814721000', 'WIEDITHA YUDHA RAMADHAN', 'Balikpapan', '1993-03-17', 'Laki-Laki', 'Islam', 29, 'Permanen', '-', 'BUKIT DAMAI INDAH BLOK G/12 RT. 082 RW. 000', '', '085753041485', 'wieditha.yudha@kimiafarma.co.id', 10, 4, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000066', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(85, '19680605C', 'KF-JKT', '10000577', '', '3175030506680009', '689206399002000', 'ZUHERMAN', 'Medan', '1968-06-05', 'Laki-Laki', 'Islam', 54, 'Permanen', '-', 'JL. BEKASI TIMUR IV RT.08 RW.06 NO.30', '', '081298613991', 'zuherman.lay50@gmail.com', 13, 4, 1, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(86, '19940420C', 'KF-JKT', '10001731', '', '1376036004940003', '818266900204000', 'ANITA ELSYA UTARI', 'Padang', '1994-04-20', 'Perempuan', 'Islam', 28, 'Permanen', '-', 'SICINCIN HILIR  RT. 1 RW. 2', '', '085374716836', 'anita.eu@kimiafarma.co.id', 0, 6, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000106', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(87, '19910902A', 'KF-JKT', '10000638', '', '3275034209910027', '725364939407000', 'AWANDA MUSTIKA DEWI', 'Jakarta', '1991-09-02', 'Perempuan', 'Islam', 30, 'Permanen', '-', 'CIKIWUL RT 005 RW 003', '', '085719518615', 'awanda.mustika@kimiafarma.co.id', 25, 6, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001731', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:44:52'),
(88, '19810929B', 'KF-JKT', '10000224', '', '3175066909810027', '473921328004000', 'NURUL IKHSANI LUAYA', 'Jakarta', '1981-09-29', 'Perempuan', 'Islam', 40, 'Permanen', '-', 'KP.RAWATERATE RT.07 RW.11', '', '081310904524', 'nurul.ikhsani@kimiafarma.co.id', 26, 6, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001731', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(89, '19951101A', 'KF-JKT', '10001313', '', '3329124111950004', '712747252501000', 'NUR KHOLIFAH', 'Brebes', '1995-11-01', 'Perempuan', 'Islam', 26, 'Permanen', '-', 'GG.Nangka no.55 Rt 04 Rw.010 kel tengah,kec karamat jati', '', '081807116100', 'kholifah.kk@gmail.com', 33, 9, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000589', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(90, '19670324B', 'KF-JKT', '10000230', '', '3174016403670003', '075448258003000', 'SUMARSIH', 'Jakarta', '1967-03-24', 'Perempuan', 'Islam', 55, 'Permanen', '-', 'Indonesia', '', '62', 'sumarsih@kimiafarma.co.id', 0, 9, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:41:14'),
(91, '19690321A', 'KF-JKT', '10000067', '', '3171066103690004', '473921278071000', 'TIN RAHAYU SRI MULYAWATI', 'Jakarta', '1969-03-21', 'Perempuan', 'Islam', 53, 'Permanen', '-', 'JL. MATRAMAN DALAM I NO.7 RT.05 RW.08', '', '085780687303', 'tien@kimiafarma.co.id', 0, 9, 3, 3, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000119', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(92, '19930405B', 'KF-JKT', '10000589', '', '7504044504930001', '722645702009000', 'YUSTIKA SYAMSU', 'Gorontalo', '1993-04-05', 'Perempuan', 'Islam', 29, 'Permanen', '-', 'JL. TMII 2 GG. BERKAH RT.015 RW.003', '', '082110939434', 'yustika.syamsu@kimiafarma.co.id', 33, 9, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000067', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(93, '19920430D', 'KF-JKT', '10001326', '', '3175073004920005', '666874391008000', 'BAMBANG PRIATNA', 'Jakarta', '1992-04-30', 'Laki-Laki', 'Islam', 30, 'Permanen', '-', 'JL. PERTANIAN SELATAN NO.22 RT.002 RW.003', '', '085811049493', 'bambangpriatna30@gmail.com', 42, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000212', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(94, '19661231B', 'KF-JKT', '10000651', '', '3133033112661433', '079088373402000', 'DANIEL SUPRATNO', 'P. Brandan ', '1966-12-31', 'Laki-Laki', 'Islam', 55, 'Permanen', '-', 'KP. BARU RT.01 RW.03', '', '81317654175', 'danielsupratno@gmail.com', 42, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000212', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(95, '19750227B', 'KF-JKT', '10000615', '', '3216026702750001', '689205805435000', 'HALIMATUS SA\'DIAH', 'Jakarta', '1975-02-27', 'Perempuan', 'Islam', 47, 'Permanen', '-', 'JL.BALINDA C 153 RT.04 RW.10', '', '081315218583', 'halimatus766@gmail.com', 43, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000215', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(96, '19721201A', 'KF-JKT', '10000571', '', '3175060112720014', '689205664004000', 'HASBULOH', 'Jakarta', '1972-12-01', 'Laki-Laki', 'Islam', 49, 'Permanen', '-', 'KP.PULO JAHE RT.05 RW.10', '', '085225275853', 'hasbulloh@gmail.com', 42, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000212', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(97, '19950401A', 'KF-JKT', '10001328', '', '3175024104951001', '725096820003000', 'ICHSANY AUFA', 'Jakarta', '1995-04-01', 'Perempuan', 'Islam', 27, 'Permanen', '-', 'JL.TANAH KOJA II RT.002.RW002', '', '085776319933', 'aufaichsany@gmail.com', 43, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(98, '19970209A', 'KF-JKT', '10001323', '', '3260569874562125', '810055574444000', 'KANIA PUTRI RAHAYU', 'Bandung', '1997-02-09', 'Perempuan', 'Islam', 25, 'Permanen', '-', 'KP. CIKUYA RT.001 RW.005', '', '081294289742', 'kaniarahayu09@gmail.com', 44, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(99, '19970924A', 'KF-JKT', '10001334', '', '3600000000001497', '746797328004000', 'MITA TRI RAHAYU', 'Jakarta', '1997-09-24', 'Perempuan', 'Islam', 24, 'Permanen', '-', 'CLUSTER LASEINE F10/53 JAKARTA GARDEN CITY', '', '081324463821', 'mitatrirahayu@gmail.com', 44, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(100, '19700105A', 'KF-JKT', '10000212', '', '3276054501700006', '077706778412000', 'MUJI SULIATINI', 'Jakarta', '1970-01-05', 'Perempuan', 'Islam', 52, 'Permanen', '-', 'JL. REBANA III NO.325 RT.12 RW.07', '', '085694311588', 'muji.s@kimiafarma.co.id', 42, 13, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000110', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(101, '19671017A', 'KF-JKT', '10000653', '', '3275031710670004', '689205623407000', 'MULKAT', 'Jakarta', '1967-10-17', 'Laki-Laki', 'Islam', 54, 'Permanen', '-', 'KAV. HARAPAN KITA  JL. MERPATI 3 RT.08 RW.09. NO.30', '', '087886190100', 'mulkatmulkat7264@gmail.com', 43, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000215', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(102, '19730720A', 'KF-JKT', '10000604', '', '3172046007730004', '689205656045000', 'NURKOMALA', 'Jakarta', '1973-07-20', 'Perempuan', 'Islam', 49, 'Permanen', '-', 'UTAN PANJANG III NO.35 RT.11 RW.7', '', '089678192490', 'nurkomala7443@gmail.com', 45, 13, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000194', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(103, '19770122A', 'KF-JKT', '10000229', '', '3275036201770014', '087826673024000', 'NURLELI', 'Jakarta', '1977-01-22', 'Perempuan', 'Islam', 45, 'Permanen', '-', 'TELAGA MAS BLOK H11/27 RT 02 RW 14', '', '287881798782', 'nurleli@kimiafarma.co.id', 44, 13, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000110', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(104, '19930929C', 'KF-JKT', '10001281', '', '3175082909930008', '169217957005000', 'OKKY PUTRA PRATAMA', 'Jakarta', '1993-09-29', 'Laki-Laki', 'Islam', 28, 'Permanen', '-', 'JL JENGKI GG TK SAHABAT  NO.1 RT.005 RW.002 KEBON PALA', '', '087887860209', 'okkyikky@gmail.com', 44, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(105, '19780812B', 'KF-JKT', '10000599', '', '3174031208780002', '689206381014000', 'RAHMAT HIDAYAT', 'Jakarta', '1978-08-12', 'Laki-Laki', 'Islam', 44, 'Permanen', '-', 'JL. TEGAL PARANG UTAMA III NO.2 RT.11 RW.04', '', '081290385542', 'therizbrothers920@gmail.com', 45, 13, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000194', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(106, '19671125A', 'KF-JKT', '10000548', '', '3171036511670003', '689205912027000', 'RICE NOVIANTI', 'Jakarta', '1967-11-25', 'Perempuan', 'Islam', 54, 'Permanen', '-', 'KEMAYORAN GEMPOL RT.10 RW.06', '', '087889375568', 'ricenovianti19@gmail.com', 42, 13, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000212', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(107, '19720410A', 'KF-JKT', '10000215', '', '3275035004720049', '473912152024000', 'RIMA PRIMAGDA', 'Jakarta', '1972-04-10', 'Perempuan', 'Islam', 50, 'Permanen', '-', 'PERUM TELAGA MAS BLOK K2/64 RT.05 RW.13', '', '081210869956', 'rima.primagda@kimiafarma.co.id', 43, 13, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000068', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(108, '19700315A', 'KF-JKT', '10000515', '', '3674041503700004', '071132658016000', 'ROCHMAN', 'Garut', '1970-03-15', 'Laki-Laki', 'Islam', 52, 'Permanen', '-', 'JL. SUKA MULYA RT.001 RW.007', '', '083898385797', 'rohmangbk70@gmail.com', 43, 13, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000215', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(109, '19700514A', 'KF-JKT', '10001155', '', '3275081405700012', '072986888407000', 'RUDI DWIWANTO', 'Jakarta', '1970-05-14', 'Laki-Laki', 'Islam', 52, 'Permanen', '-', 'JL. GAMPRIT I GG. MANGGA 3 RT.02 RW.14', '', '085732229539', 'rudi.dwiwanto@kimiafarma.co.id', 42, 13, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000212', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(110, '19661211C', 'KF-JKT', '10000647', '', '3216065112660014', '473921286413000', 'RUSLAN LUBIS', 'Tapanuli Utara', '1966-12-11', 'Perempuan', '', 55, 'Permanen', '-', 'PERUM BUMI LESTARI BLOK H7 NO.14A RT.03 RW.14', '', '085697788174', 'ruslanlubiss@gmail.com', 44, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(111, '19701111A', 'KF-JKT', '10000194', '', '3275035111700020', '473912244407000', 'SITI ASMAWIYAH', 'Kutoarjo', '1970-11-11', 'Perempuan', 'Islam', 51, 'Permanen', '-', 'PONDOK UNGU PERMAI BLOK F13 RT.04 RW.12 N0.15', '', '081310900442', 'siti_as@kimiafarma.co.id', 45, 13, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000110', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(112, '19671008A', 'KF-JKT', '10001141', '', '1271094810670007', '097148225122000', 'SITI ZAHARA LUBIS', 'Medan', '1967-10-08', 'Perempuan', 'Islam', 54, 'Permanen', '-', 'Jl HR Ismail no 12 - 14 RT 16/3 7Jatinegara Cakung jakarta timur', '', '081376676566', 'ibuzahra334@gmail.com', 43, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000215', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(113, '19711027A', 'KF-JKT', '10000110', '', '3275086710710013', '473922557407000', 'SRI ASTUTI', 'Jakarta', '1971-10-27', 'Perempuan', 'Islam', 50, 'Permanen', '-', 'JL. GAMPRIT II RT. 002 RW. 014', '', '085799906311', 'sri.astuti@kimiafarma.co.id', 0, 13, 3, 3, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000119', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(114, '19720128A', 'KF-JKT', '10000650', '', '3173072801720001', '689205672031000', 'SUCO UJIANTO AJI', 'Jakarta', '1972-01-28', 'Laki-Laki', 'Islam', 50, 'Permanen', '-', 'JL. PONDOK BANDUNG RT.09 RW.02', '', '087882192241', 'sucoujiantoaji@gmail.com', 42, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000212', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(115, '19680409A', 'KF-JKT', '10000661', '', '3171030904680001', '473926541027000', 'SUHARTONO', 'Jombang', '1968-04-09', 'Laki-Laki', 'Islam', 54, 'Permanen', '-', 'JL. TARUNA JAYA II NO.06 RT.17 RW.02', '', '082210095164', 'suhart536@gmail.com', 44, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(116, '19710903A', 'KF-JKT', '10000654', '', '3173020309710009', '689205706036000', 'SUPRIYANTO', 'Jakarta', '1971-09-03', 'Laki-Laki', 'Islam', 50, 'Permanen', '-', 'JL. JELAMBAR JAYA RT.05 RW.02. NO.85', '', '085776214827', 'suprikemasan@gmail.com', 43, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000215', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(117, '19700113A', 'KF-JKT', '10000663', '', '3133031301704977', '473912004001000', 'TRI KARYATI', 'Jakarta', '1970-01-13', 'Perempuan', 'Islam', 52, 'Permanen', '-', 'JL.KELAPA TUNGGAL RT.09 RW.09 NO.3', '', '085819876020', 'tri.karyati2017@gmail.com', 44, 13, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(118, '19750515B', 'KF-JKT', '10000602', '', '3275031505750035', '498610146005000', 'WARSIYO', 'Kebumen', '1975-05-15', 'Laki-Laki', 'Islam', 47, 'Permanen', '-', 'KP. RAWA SILAM 1 KAV. H  AMSORI  RT 012 RW 06', '', '081299209148', 'warsiyoiyo80@gmail.com', 45, 13, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000194', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(119, '19690310A', 'KF-JKT', '10000207', '', '3275011003690029', '072987084407000', 'AHMAD SARMALI', 'Jakarta', '1969-03-10', 'Laki-Laki', 'Islam', 53, 'Permanen', '-', 'JL.P.YAPEN 14 RT.03 RW.08 NO.202', '', '081314499011', 'ahmad.sarmali1003@kimiafarma.co.id', 1, 1, 1, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000064', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(120, '19900213A', 'KF-JKT', '10001228', '', '3175061302901002', '892711755501000', 'ANDI SETIAWAN', 'Brebes', '1990-02-13', 'Laki-Laki', 'Islam', 32, 'Permanen', '-', 'KP. PULO JAHE RT.006 RW.014', '', '085640782784', 'andiset290@gmail.com', 2, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(121, '19700202A', 'KF-JKT', '10000532', '', '3175020202700001', '689206548003000', 'ARI SUSWANTO', 'Kulon Progo', '1970-02-02', 'Laki-Laki', 'Islam', 52, 'Permanen', '-', 'JATINEGARA KAUM', '', '081298613991', 'arisuswantokimia@gmail.com', 2, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(122, '19890318A', 'KF-JKT', '10001327', '', '3203151803890004', '746855295406000', 'ERUS RUSMANA', 'Sukabumi', '1989-03-18', 'Laki-Laki', 'Islam', 33, 'Permanen', '-', 'KP. CIRANCA RT.005 RW.004', '', '08121935355', 'rusmanaerus89@gmail.com', 1, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000207', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(123, '19920114A', 'KF-JKT', '10001225', '', '3216051401920003', '580305316435000', 'HARBY JANUAR FIDI', 'Jakarta', '1992-01-14', 'Laki-Laki', 'Islam', 30, 'Permanen', '-', 'JEJALENJAYA', '', '085780892549', 'harbyjanuar@gmail.com', 3, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(124, '19890721A', 'KF-JKT', '10001226', '', '3175062107891001', '543773634004000', 'IMAM ROSIT ILMAWAN', 'Klaten', '1989-07-21', 'Laki-Laki', 'Islam', 33, 'Permanen', '-', 'KP. JEMBATAN RT.009 RW.001,No.54', '', '081337477150', 'imamilmawan@gmail.com', 1, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000207', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(125, '19761122A', 'KF-JKT', '10000213', '', '3212032211760004', '473932242004000', 'IVAN SANTOSO', 'Bantul', '1976-11-22', 'Laki-Laki', 'Islam', 45, 'Permanen', '-', 'GRIYA BUKIT JAYA S2 NO.24', '', '081806158858', 'ivan.santoso@kimiafarma.co.id', 2, 1, 1, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(126, '19700301B', 'KF-JKT', '10000527', '', '3201020103700012', '075559583005000', 'KADARIYO', 'Gunung Kidul', '1970-03-01', 'Laki-Laki', 'Islam', 52, 'Permanen', '-', 'PERUM BUMI MUTIARA BLOK JJ5 NO.30 RT.04 RW.34', '', '081382658826', 'kadariyo03@gmail.com', 1, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(127, '19700424C', 'KF-JKT', '10000540', '', '3175062404700014', '473922813004000', 'KARSONO', 'Banyumas', '1970-04-24', 'Laki-Laki', 'Islam', 52, 'Permanen', '-', 'KAMPUNG WARU DOYONG RT.08 RW.08', '', '085890324975', 'karsonokarsono22@gmail.com', 3, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(128, '19780512A', 'KF-JKT', '10000541', '', '3175061205780036', '689206498004000', 'MAT SOLEH', 'Jakarta', '1978-05-12', 'Laki-Laki', 'Islam', 44, 'Permanen', '-', 'JATINEGARA LIO RT.03 RW.04', '', '081318529547', 'mat528587@gmail.com', 2, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(129, '19950522A', 'KF-JKT', '10001230', '', '3175072205950008', '169507035008000', 'MUHAMMAD SOFYAN ARDIANSYAH', 'Jakarta', '1995-05-22', 'Laki-Laki', 'Islam', 27, 'Permanen', '-', 'KP. BOJONG RANGKONG RT.008 RW.011 NO.17', '', '087786260337', 'msofyan342@gmail.com', 2, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(130, '19680902A', 'KF-JKT', '10000534', '', '3171030209680010', '689206530031000', 'RADEN SATRIONO', 'Jakarta', '1968-09-02', 'Laki-Laki', 'Islam', 53, 'Permanen', '-', 'KEMAYORAN GEMPOL RT.06 RW.06', '', '087783856156', 'satrionokf101@gmail.com', 2, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(131, '19751216B', 'KF-JKT', '10000535', '', '3173071612750008', '689206506027000', 'RAHMAD FADOLI', 'Jakarta', '1975-12-16', 'Laki-Laki', 'Islam', 46, 'Permanen', '-', 'JL. KOTA BAMBU UTARA RT.08 RW.04', '', '0895334137436', 'fadolirahmat@gmail.com', 2, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(132, '19690606B', 'KF-JKT', '10000537', '', '3215250606690005', '473922763027000', 'SARINO', 'Jakarta', '1969-06-06', 'Laki-Laki', 'Islam', 53, 'Permanen', '-', 'P. TAMAN GIYA PERMAI BLOK B23/19 RT.01 RW.04', '', '085716914907', 'sarinorino@gmail.com', 2, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(133, '19691109B', 'KF-JKT', '10000542', '', '3216020911690007', '473911907407000', 'SUDIYONO AD', 'Purworejo', '1969-11-09', 'Laki-Laki', 'Islam', 52, 'Permanen', '-', 'BULAK INDAH RT.06 RW.12', '', '081385175930', 'sudiyonoade1@gmail.com', 2, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000207', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(134, '19750203A', 'KF-JKT', '10000544', '', '3310040302750001', '689206571008000', 'SUPRIYONO', 'Purworejo', '1975-02-03', 'Laki-Laki', 'Islam', 47, 'Permanen', '-', 'KAMPUNG MALAKA RT.11 RW.08', '', '087783856156', 'priyono75bagus@gmail.com', 3, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(135, '19720407A', 'KF-JKT', '10000539', '', '3175040704720011', '689206407005000', 'TRI WAHYUDADI', 'Jakarta', '1972-04-07', 'Laki-Laki', 'Islam', 50, 'Permanen', '-', 'BATU SARI RT.09 RW.02', '', '085887880424', 'triwahyudadi@gmail.com', 2, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(136, '19770618B', 'KF-JKT', '10000545', '', '3216011806770011', '689206522435000', 'UNTUNG SUPRIYADI', 'Jakarta', '1977-06-18', 'Laki-Laki', 'Islam', 45, 'Permanen', '-', 'Kp. TANAH TINGGI RT.01 RW.05', '', '081519218394', 'oen2nk@gmail.com', 3, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(137, '19690610C', 'KF-JKT', '10000546', '', '3175071006690009', '689206514008000', 'USMAN', 'Jakarta', '1969-06-10', 'Laki-Laki', 'Islam', 53, 'Permanen', '-', 'KP. MALAKA RT.03 RW.02', '', '085779741618', 'chillausman69@gmail.com', 3, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(138, '19920711A', 'KF-JKT', '10001325', '', '3171051107920002', '714632601024000', 'ANDRI HARTO SETIAWAN', 'Jakarta', '1992-07-11', 'Laki-Laki', 'Islam', 30, 'Permanen', '-', 'CEMPAKA WARNA RT.013 RW.004', '', '085692415858', 'andrihs.kf@gmail.com', 3, 1, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000064', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(139, '19750706A', 'KF-JKT', '10000553', '', '3276050607750014', '689205896412000', 'ANDRIANSYAH', 'Jakarta', '1975-07-06', 'Laki-Laki', 'Islam', 47, 'Permanen', '-', 'KP. SAWAH RT.03 RW.04', '', '087874624255', 'farhanfajar48@gmail.com', 5, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(140, '19930413A', 'KF-JKT', '10001214', '', '3175071304930005', '788148542008000', 'DANY WICAKSONO', 'Jakarta', '1993-05-13', 'Laki-Laki', 'Islam', 29, 'Permanen', '-', 'KP. SUMUR NO.49 RT.001 RW.010', '', '08988330060', 'korma.bonser@gmail.com', 7, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000204', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(141, '19780503A', 'KF-JKT', '10000558', '', '3216060305780016', '689206316004000', 'DIDI SUTANTO', 'Purwokerto', '1978-05-03', 'Laki-Laki', 'Islam', 44, 'Permanen', '-', 'PERUMAHAN GRIYA ASRI II BLOK C 6/16 RT.10 RW.32', '', '08557026466', 'dsutanto78@gmail.com', 7, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000204', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00');
INSERT INTO `employees` (`id`, `nip`, `location`, `sap_id`, `parent_nik`, `nik`, `npwp`, `employee_name`, `birth_place`, `birth_date`, `gender`, `religion`, `age`, `employee_status`, `os_name`, `address`, `phone`, `mobile`, `email`, `division_id`, `sub_department_id`, `department_id`, `rank_id`, `location_id`, `user_id`, `sk_number`, `sk_date`, `sk_start_date`, `sk_end_date`, `status`, `overtime`, `direct_spv`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(142, '19670107C', 'KF-JKT', '10000549', '', '3171070701670005', '689205904072000', 'DJUNAEDI', 'Jakarta', '1967-01-07', 'Laki-Laki', 'Islam', 55, 'Permanen', '-', 'JL.DUKUH PINGGIR IV NO.2 RT.09 RW.06', '', '087783023532', 'djunaedijuned7@gmail.com', 4, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(143, '19890122B', 'KF-JKT', '10001644', '', '3175062201890006', '574034625004000', 'EDI SUPRIATNA', 'Jakarta', '1989-01-22', 'Laki-Laki', 'Islam', 33, 'Permanen', '-', 'KP LIO RT 007/ RW 004', '', '08568786705', 'edisupriatna221@gmail.com', 4, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(144, '19710625B', 'KF-JKT', '10000554', '', '3276052506710003', '689205888412000', 'KATIMAN', 'Sukoharjo', '1971-06-25', 'Laki-Laki', 'Islam', 51, 'Permanen', '-', 'JL. ANGSANA II NO.284 RT.10 RW.06', '', '081311417159', 'mazkat71@gmail.com', 5, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(145, '19690911C', 'KF-JKT', '10000555', '', '3216061109690018', '072987282413000', 'KRISTIONO', 'Jakarta', '1969-09-11', 'Laki-Laki', 'Islam', 52, 'Permanen', '-', 'PERUM GRIYA ASRI I BLOK A II NO.17 RT01 RW.02', '', '081288609443', 'kristionotiono@gmail.com', 5, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(146, '19670504B', 'KF-JKT', '10000559', '', '3175060405670012', '473912178004000', 'MARTONO', 'Wates', '1967-05-04', 'Laki-Laki', 'Islam', 55, 'Permanen', '-', 'JATINEGARA LIO RT.02 RW.04', '', '085664144032', 'martonokimiafarma@gmail.com', 42, 13, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000212', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(147, '19920621A', 'KF-JKT', '10001666', '', '3277012106920004', '817088164421000', 'MUKHARIJ NUR ALAM', 'Bandung', '1992-06-21', 'Laki-Laki', 'Islam', 30, 'Permanen', '-', 'KOMP MGG JL. BOMBER VIII NO. 8 RT. 6 RW. 29', '', '085781536638', 'nur.alam@kimiafarma.co.id', 0, 2, 1, 4, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000113', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(148, '19700511C', 'KF-JKT', '10000551', '', '3175025105700003', '075448191003000', 'NENENG SUHENI', 'Jakarta', '1970-05-11', 'Perempuan', 'Islam', 52, 'Permanen', '-', 'JL. PISANGAN LAMA II RT.02 RW.04 NO.38', '', '081310363558', 'nenengsun12@gmail.com', 4, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(149, '19921022A', 'KF-JKT', '10000233', '', '3175066210920003', '094545308004000', 'RIA HASANAH PUTRI', 'Jakarta', '1989-06-17', 'Perempuan', 'Islam', 33, 'Permanen', '-', 'JL.JATINEGARA ILIR RT.06 RW.11', '', '08567861611', 'ria.hasanah.putri@kimiafarma.co.id', 4, 2, 1, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001666', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(150, '19950720B', 'KF-JKT', '10001250', '', '3172042007950002', '096646187045000', 'RYAN PRAJULY ', 'Jakarta', '1995-07-20', 'Laki-Laki', 'Islam', 27, 'Permanen', '-', 'JL. KALIBARU TIMUR GG. III/5 RT.006 RW.003', '', '087777359617', 'ryan_prajuli@yahoo.com', 4, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(151, '19820216A', 'KF-JKT', '10000561', '', '3173051602820004', '091288837035000', 'SIGIT MAULANA', 'Jakarta', '1982-02-16', 'Laki-Laki', 'Islam', 40, 'Permanen', '-', 'JL.BOJONG RANGKONG RT 07 RW 07 NO 04', '', '081212226860', 'sigit160282@gmail.com', 7, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000204', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(152, '19731013B', 'KF-JKT', '10000562', '', '3304061310730002', '689205870033000', 'SUMARNO', 'Jakarta', '1973-10-13', 'Laki-Laki', 'Islam', 48, 'Permanen', '-', 'JL. DURI BANGKIT RT.03 RW.09', '', '085892645613', 'nanosumarno747@gmail.com', 7, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000204', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(153, '19941027A', 'KF-JKT', '10001331', '', '3172046710941003', '725682769522000', 'SUSANTO', 'Cilacap', '1994-10-27', 'Laki-Laki', 'Islam', 27, 'Permanen', '-', 'DUSUN WATESARI RT.002 RW.001', '', '085776341529', 'santoato63@gmail.com', 5, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(154, '19750623C', 'KF-JKT', '10000563', '', '3276052306750013', '689206449412000', 'SYAHRIN FAJRI', 'Bogor', '1975-06-23', 'Laki-Laki', 'Islam', 47, 'Permanen', '-', 'GG. MASJID ALISHLAH RT.04 RW.04', '', '085776354869', 'syahrinfajri@gmail.com', 7, 2, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000204', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(155, '19760111A', 'KF-JKT', '10000204', '', '3275025101760013', '072987449407000', 'THERESIA NINDYA PURBORINI', 'Jakarta', '1976-01-11', 'Perempuan', 'Katholik', 46, 'Permanen', '-', 'JL. FLAMBOYAN NO.06 RT.02 RW.03', '', '08558663161', 'theresia.nindya@kimiafarma.co.id', 7, 2, 1, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001666', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(156, '19940319B', 'KF-JKT', '10001837', '', '3202011903940004', '833410863405000', 'WILDAN ANDIANA', 'Sukabumi', '1994-03-19', 'Laki-Laki', 'Islam', 28, 'Permanen', '-', 'JL. OTISTA RT. 04 RW. 05, KEC. PALABUHAN RATU', '', '082216797832', 'wildan.andiana@kimiafarma.co.id', 6, 2, 1, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001666', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(157, '19890417C', 'KF-JKT', '10000291', '', '3275011704890019', '', 'BRAM HIK ANUGRAHA', 'Bekasi', '1989-04-17', 'Laki-Laki', 'Islam', 33, 'Permanen', '-', 'PERUM MARGAHAYU JAYA BLOK A JALAN CEMARA II NO.341', '', '081316561862', 'bramhik@kimiafarma.co.id', 0, 3, 1, 4, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000113', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(158, '19920106A', 'KF-JKT', '10000560', '', '3171060601920005', '896105145071000', 'FADIS RULIANSYAH', 'Jakarta', '1992-01-06', 'Laki-Laki', 'Islam', 30, 'Permanen', '-', 'JL. KALASAN DALAM RT.003 RW.002', '', '081212683170', 'fadis7010@gmail.com', 8, 3, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000291', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(159, '19970505A', 'KF-JKT', '10001273', '', '3402150505970004', '732099072402000', 'KAFI MUSYAFA', 'Bantul', '1997-05-05', 'Laki-Laki', 'Islam', 25, 'Permanen', '-', 'GATAK RT.003 BANGUNHARJO, SEWON', '', '082144005735', 'kafimusyafa@gmail.com', 9, 3, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(160, '19900927A', 'KF-JKT', '10001237', '', '3175062709900024', '676686397006000', 'MUHAMAD ARIEF TANJUNG', 'Jakarta', '1990-09-27', 'Laki-Laki', 'Islam', 31, 'Permanen', '-', 'JL. KAYU TINGGI RT.007 RW.009', '', '082113822428', 'arieftanjung616@gmail.com', 8, 3, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(161, '19910824A', 'KF-JKT', '10001231', '', '3175062408910008', '721339620004000', 'MUHAMMAD SYARIF', 'Jakarta', '1991-08-24', 'Laki-Laki', 'Islam', 30, 'Permanen', '-', 'KP. BARU KLENDER RT.013 RW.001 NO.46', '', '087886780329', 'syarifmiar8@gmail.com', 8, 3, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(162, '19960916A', 'KF-JKT', '10001275', '', '3471131609360002', '836596080541000', 'NURINDRAYANA', 'Yogyakarta', '1996-09-16', 'Laki-Laki', 'Islam', 25, 'Permanen', '-', 'SOROSUTAN UH 6 NO. 853 RT 15 RW 04', '', '081216894907', 'nurindray16@gmail.com', 8, 3, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(163, '19720913A', 'KF-JKT', '10000536', '', '3175061309720008', '689206423004000', 'SARIFULLOH', 'Jakarta', '1972-09-13', 'Laki-Laki', 'Islam', 49, 'Permanen', '-', 'KP. BUARAN RT.03 RW.08', '', '0895365418555', 'sulthan.stn@gmail.com', 8, 3, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(164, '19700511B', 'KF-JKT', '10000205', '', '3172051105700003', '073223927044000', 'SUDARMADI', 'Jakarta', '1970-05-11', 'Laki-Laki', 'Islam', 52, 'Permanen', '-', 'jl rawakuning rt 016/06, no 97 c ,gang mekar', '', '081294568456', 'sudarmadi@kimiafarma.co.id', 9, 3, 1, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000291', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(165, '19660202B', 'KF-JKT', '10000543', '', '3175060202660006', '689206183006000', 'SUPARYANTO', 'Purworejo', '1966-02-02', 'Laki-Laki', 'Islam', 56, 'Permanen', '-', 'JL. RAWA BEBEK RT.11 RW.01', '', '081319272619', 'suparyanto0202@gmail.com', 8, 3, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(166, '19900804A', 'KF-JKT', '10000977', '', '3204290408900008', '', 'AGUNG GUSTIMAN', 'Bandung', '1990-08-04', 'Laki-Laki', 'Islam', 32, 'Permanen', '-', 'KOMP. BUMI KARYA C4 NO.5 RT. 001 RW. 005', '', '82118497477', 'agung.gustiman@kimiafarma.co.id', 40, 11, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '20002096', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(167, '19710209A', 'KF-JKT', '10000509', '', '3175014902710007', '689205698001000', 'ELMIYATI', 'Purworejo', '1971-02-09', 'Perempuan', 'Islam', 51, 'Permanen', '-', 'JL.PISANGAN BARU UTARA NO.20 RT.09 RW.12', '', '083878794885', 'elmy1335@gmail.com', 38, 11, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000646', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(168, '19780515A', 'KF-JKT', '10000646', '', '3174031505780004', '689206217014000', 'ISKANDAR', 'Jakarta', '1978-05-15', 'Laki-Laki', 'Islam', 44, 'Permanen', '-', 'JL. MAMPANG PRAPATAN VIII RT.02 RW.02 NO.51 C', '', '081288267897', 'iskandar@kimiafarma.co.id', 38, 11, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '20002096', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(169, '19731218D', 'KF-JKT', '10000510', '', '3275085812730024', '692062771024000', 'MAMIEK BANU WINARTI', 'Jakarta', '1973-12-18', 'Perempuan', 'Islam', 48, 'Permanen', '-', 'Perum Terangsari BlokB5/1', '', '087776051441', 'mamiekbanu@yahoo.co.id', 38, 11, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000646', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(170, '19710805B', 'KF-JKT', '10000573', '', '3175010508710010', '689206431001000', 'MUHAMMAD ARIEF REZEKI', 'Jakarta', '1971-08-05', 'Laki-Laki', 'Islam', 51, 'Permanen', '-', 'Jl.P Komarudin no.70 Rt.001/005 Pulogebang Cakung', '', '085920750822', 'artie.mar@gmail.com', 41, 11, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000519', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(171, '19910504A', 'KF-JKT', '10000518', '', '3175034405910001', '789955606002000', 'NURHAYATI', 'Jakarta', '1991-05-04', 'Perempuan', 'Islam', 31, 'Permanen', '-', 'JL  KEBON NANAS UTARA NO.7, JAKARTA TIMUR', '', '082225351991', 'nur21707@gmail.com', 41, 11, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000519', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(172, '19740711A', 'KF-JKT', '10000519', '', '3172065107740008', '689206159043000', 'NURYESI', 'Jambi', '1974-07-11', 'Perempuan', 'Islam', 48, 'Permanen', '-', 'JL.WIRA PERTIWI NO.2 KELAPA GADING RT.03 RW.06', '', '085697300954', 'nuryesi@kimiafarma.co.id', 41, 11, 3, 6, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '20002096', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(173, '19730819A', 'KF-JKT', '10000202', '', '3175011908730010', '075398297001000', 'RONI SOFYAR', 'Jakarta', '1973-08-19', 'Laki-Laki', 'Islam', 48, 'Permanen', '-', 'JL. KAYU MANSI III BARU NO.8 RT.03 RW.03', '', '081385124898', 'roni.sofyar@kimiafarma.co.id', 39, 11, 3, 6, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '20002096', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(174, '19680813A', 'KF-JKT', '10000517', '', '3313047112690093', '071779086027000', 'SUMIYATI', 'Jakarta', '1968-08-13', 'Perempuan', 'Islam', 54, 'Permanen', '-', 'JL. SUMUR BATU RT.10 RW.05', '', '089658891522', 'ncum1968@gmail.com', 41, 11, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000519', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(175, '19721118A', 'KF-JKT', '10000489', '', '3174071811720005', '71822225019000', 'TRULY PUDYA UTAMA', 'Jakarta', '1972-11-18', 'Laki-Laki', 'Islam', 49, 'Permanen', '-', 'JL. H. RAYA NO.11 RT.08 RW.10', '', '085811769957', 'trulypudyautama@gmail.com', 41, 11, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000519', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(176, '19950521A', 'KF-JKT', '10001848', '', '3175026105950002', '842678971003000', 'ANINDYA HANA IRADHATI', 'Jakarta', '1995-05-21', 'Perempuan', 'Islam', 27, 'Permanen', '-', 'JL. MELATI DALAM NO. 22 RT. 004 RW. 008', '', '082210375274', 'anindya.hana@kimiafarma.co.id', 30, 7, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001291', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(177, '19941204C', 'KF-JKT', '10001745', '', '3674074412940003', '84739995411000', 'ASTRID PERMATASARI ISNAN', 'Saarbruecken', '1994-12-04', 'Perempuan', 'Islam', 27, 'Permanen', '-', 'DE LATINOS CLUSTER MEXICANO BLOK C - 2 NO 2', '', '082112658104', 'astrid.permatasari@kimiafarma.co.id', 0, 15, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000106', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(178, '19930627B', 'KF-JKT', '10000644', '', '3204366707930004', '459374260444000', 'DENA FANANDRA ALSAKINA', 'Bandung', '1993-06-27', 'Perempuan', 'Islam', 29, 'Permanen', '-', 'Jl. PANAITAN RAYA No. 2 RT 01 RW 14 PERUMNAS III', '', '085222482911', 'dena.fanandra@kimiafarma.co.id', 29, 7, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001291', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:46:57'),
(179, '19931203D', 'KF-JKT', '10002520', '', '3175084312930001', '817517725005000', 'DESTRIA RAHMADINI', 'Jakarta', '1993-12-03', 'Perempuan', 'Islam', 28, 'Permanen', '-', 'JL HARAPAN INDAH X NO 60C RT 4 RW 12', '', '081221687187', 'destria.rahmadini@kimiafarma.co.id', 0, 15, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000106', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:49:52'),
(180, '19901030A', 'KF-JKT', '10001297', '', '3175087010900003', '469920680005000', 'DWI OKTARINI', 'Jakarta', '1990-10-30', 'Perempuan', 'Islam', 31, 'Permanen', '-', 'Jl. H. AMSIR RT.002 RW.004 NO.54 CIPINANG MELAYU', '', '08561902602', 'dwi.oktarini@kimiafarma.co.id', 28, 7, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001291', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:54:41'),
(181, '19910405A', 'KF-JKT', '10000641', '', '3201024504910003', '886422849403000', 'GIAN SYAHFITRIA', 'Jakarta', '1991-04-05', 'Perempuan', 'Islam', 31, 'Permanen', '-', 'JL. RW MONGINSIDI PERUM KALIDONI INDAH PERMAI BL', '', '081290044135', 'gian.syahfitria@kimiafarma.co.id', 31, 7, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001291', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(182, '19920607A', 'KF-JKT', '10000645', '', '3276104706920001', '725069132412000', 'INDA WIDYASTUTI', 'Bogor', '1992-06-07', 'Perempuan', 'Islam', 30, 'Permanen', '-', 'JL. BHAKTI ABRI GG.SIRIN RT.003 RW.009 NO.55', '', '085870169529', 'indawidyastuti@gmail.com', 31, 7, 2, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000641', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(183, '19950726A', 'KF-JKT', '10001301', '', '3201016607950006', '724413455403000', 'JENETTE CECELIA CHRISTANDI', 'Bogor', '1995-07-26', 'Perempuan', 'Kristen', 27, 'Permanen', '-', 'PERUMAHAN CILEBUT BUMI PERTIWI BLOK AZ NO. 7', '', '08119188816', 'jenettecc@gmail.com', 27, 7, 2, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001839', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(184, '19970120D', 'KF-JKT', '10002646', '', '1376036001970002', '945264604204000', 'KATRIN DAYATRI', 'Payakumbuh', '1997-01-20', 'Perempuan', 'Islam', 25, 'Permanen', '-', 'JALAN PAHLAWAN NO 69 Rt/rw 001/002', '', '082284446324', 'katrin.dayatri@kimiafarma.co.id', 0, 15, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000106', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:50:30'),
(185, '19940516A', 'KF-JKT', '10001839', '', '3518011605940001', '837929702655000', 'KRESMA OKY KURNIAWAN', 'Nganjuk', '1994-05-16', 'Laki-Laki', 'Islam', 28, 'Permanen', '-', 'PUTUK RT. 004 RW. 003', '', '081351147277', 'kresma.oky@kimiafarma.co.id', 27, 7, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10001291', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(186, '19960807D', 'KF-JKT', '10002647', '', '3311094708960001', '935039792532000', 'LUTFI RATNA WAHYUNINGTYAS', 'Sukoharjo', '1996-08-07', 'Perempuan', 'Islam', 26, 'Permanen', '-', 'PABRIK RT 03/04, Pondok, Grogol, Sukoharjo, JAWA TENGAH', '', '082314868656', 'lutfi.ratna@kimiafarma.co.id', 0, 15, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000106', 0, 1, '2022-02-03 14:41:24', '2022-02-10 10:51:22'),
(187, '19930606A', 'KF-JKT', '10001295', '', '3209334606930004', '724923255426000', 'RATNA SETIANINGSIH', 'Cirebon', '1993-06-06', 'Perempuan', 'Islam', 29, 'Permanen', '-', 'JL.BLOK 02 TR.002/003', '', '085211127481', 'ratnasetaningsih54@gmail.com', 29, 7, 2, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000644', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(188, '19940625B', 'KF-JKT', '10002522', '', '3522092506940005', '932467616601000', 'SANDY ARISKA ALI FANDI', 'Bojonegoro', '1994-06-25', 'Laki-Laki', 'Islam', 28, 'Permanen', '-', 'DSN. TENGGER, RT.07/RW. 02, DS. TLOGOREJO, KEC. KEPOHBARU KAB. BOJONEGORO', '', '081554130083', 'sandy.ariska@kimiafarma.co.id', 0, 15, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000106', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(189, '19960422A', 'KF-JKT', '10001308', '', '3202116204960006', '760338269434000', 'TINA WIDIANA LATURIUW', 'Sukabumi', '1996-04-22', 'Perempuan', 'Islam', 26, 'Permanen', '-', 'KOMPLEK IPB II JALAN TITAN BLOK S NOMOR 9 CIHERANG, DRAMAGA', '', '081807720297', 'tinawidiana@gmail.com', 30, 7, 2, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001848', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(190, '19911101A', 'KF-JKT', '10001291', '', '3275104111910003', '750462335447000', 'TITIS DANASTRI', 'Jakarta', '1991-11-01', 'Perempuan', 'Islam', 30, 'Permanen', '-', 'JL. MELATI 12 NO.30 BS 46 RT.009 RW.013 KRANGGAN PERMAI JATISAMPURNA', '', '08118181965', 'titis.danastri@kimiafarma.co.id', 0, 7, 2, 3, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000106', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(191, '19860115A', 'KF-JKT', '10000064', '', '1471071501860041', '684481724412000', 'EDFANO STIAWAN ARMAY', 'Tanjung Pinang', '1986-01-15', 'Laki-Laki', 'Islam', 36, 'Permanen', '-', 'PERUM GREEN JATIMULYA NO. A8, JL. ARIDO, RT 01/RW 04, KP. SAWAH', '', '08121910190', 'armay.edfano@kimiafarma.co.id', 0, 14, 3, 3, 1, 10, 'SK1', '2022-02-01', '2022-02-01', '2022-12-31', 'ACTIVE', 1, '10000119', 0, 1, '2022-02-03 14:41:24', '2022-02-07 14:53:51'),
(192, '19780424A', 'KF-JKT', '10001186', '', '3216052404790003', '244200689009000', 'INDRATNO HERY PRABOWO', 'Madiun', '1978-04-24', 'Laki-Laki', 'Islam', 44, 'Permanen', '-', 'PERUM. VILLA MUTIARA GADING 2 BLOK C 16/9', '', '087888552544', 'indratno608@gmail.com', 0, 14, 3, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000064', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(193, '19690515A', 'KF-JKT', '10000221', '', '3216061505690031', '072987670413000', 'UDIN MUJAHIDIN', 'Kuningan', '1969-05-15', 'Laki-Laki', 'Islam', 53, 'Permanen', '-', 'PERUM BUMI SANI PERMAI BLOK C4 NO.33 RT.16 RW.14', '', '081285518025', 'mujahidin@kimiafarma.co.id', 0, 15, 2, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000106', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(194, '19731002A', 'KF-JKT', '10000500', '', '3175020210730012', '689205946003000', 'AGUS ISWANTO', 'Brebes', '1973-10-02', 'Laki-Laki', 'Islam', 48, 'Permanen', '-', 'JL. JATI UNGGUL NO.35 RT.05 RW.03', '', '085780441755', 'ghost15@ymail.com', 16, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001946', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(195, '19851230A', 'KF-JKT', '10001105', '', '1207283012850003', '970540761125000', 'ANDA VICTORIA', 'Jakarta', '1995-12-30', 'Laki-Laki', 'Islam', 26, 'Permanen', '-', 'DUSUN KARYA', '', '082365649911', 'anda.victoria@kimiafarma.co.id', 17, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000193', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(196, '19901125A', 'KF-JKT', '10001210', '', '3175062511900012', '870954112004000', 'ASEP DIKI ARIYANTO', 'Kuningan', '1990-11-25', 'Laki-Laki', 'Islam', 31, 'Permanen', '-', 'KP PENGGILINGAN NO.7A, JAKARTA TIMUR', '', '081286374654', 'asep.diki@kimiafarma.co.id', 18, 5, 1, 7, 1, 11, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000192', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(197, '19860914A', 'KF-JKT', '10001216', '', '3175071409860007', '746975903008000', 'ASEP SUSANTO', 'Jakarta', '1986-09-14', 'Laki-Laki', 'Islam', 35, 'Permanen', '-', 'JL. RAWADAS RT.005 RW.003', '', '081289562974', 'asepsusanto98018@gmail.com', 17, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000193', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(198, '19940807A', 'KF-JKT', '10001211', '', '3403090708940002', '835416470545000', 'BAGUS TRI SUGIARTO', 'Gunung Kidul', '1994-08-07', 'Laki-Laki', 'Islam', 28, 'Permanen', '-', 'GELARAN 2, RT.0 01 RW.016', '', '087839872002', 'bagustrisugiarto@gmail.com', 15, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000498', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(199, '19911204A', 'KF-JKT', '10001213', '', '3175020412910008', '464594605003000', 'BUDIYANSAH', 'Jakarta', '1991-12-04', 'Laki-Laki', 'Islam', 30, 'Permanen', '-', 'JL CIPINANG KEBEMBEM NO 20 RT. 004 RW. 013', '', '085811272670', 'budii041291@gmail.com', 16, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001946', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(200, '10001946', 'KF-JKT', '10001946', '', '1371061607930007', '849026000205000', 'DEVISCAR TITO', 'Padang', '1993-07-16', 'Laki-Laki', 'Islam', 29, 'Permanen', '-', 'JL.DR KRT RAJIMAN WIDYODININGRAT NO.48A RT 09/RW10', '', '082284548545', 'deviscar.tito@kimiafarma.co.id', 16, 5, 1, 6, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000192', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(201, '19860424A', 'KF-JKT', '10000192', '', '3175072404860008', '689206597008000', 'FAJAR FIRMANSYAH', 'Jakarta', '1986-04-24', 'Laki-Laki', 'Islam', 36, 'Permanen', '-', 'JL. TAMAN MALAKA UTARA VIII BLOK C8 NO.17 RT.03 RW.11', '', '081289624240', 'fajar.firmansyah@kimiafarma.co.id', 0, 5, 1, 4, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000113', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(202, '19940809A', 'KF-JKT', '10001215', '', '1807100908940002', '746682426004000', 'FIQIH IKSANUDIN', 'Margototo', '1994-08-09', 'Laki-Laki', 'Islam', 28, 'Permanen', '-', 'JL.SALEMBA TENGAH 7', '', '085774338349', 'fiqihiksanudin@gmail.co', 16, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001946', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(203, '19671111A', 'KF-JKT', '10000497', '', '3175021111670003', '179772918003000', 'HERNADI', 'Jakarta', '1967-11-11', 'Laki-Laki', 'Islam', 54, 'Permanen', '-', 'JL. PISANGAN LAMA II NO.36 RT.02 RW.04', '', '087788694780', 'hernadi671@gmail.com', 15, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000498', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(204, '19721103C', 'KF-JKT', '10000505', '', '3171080311720001', '591014105024000', 'JOKO HARYANTO', 'Jakarta', '1972-11-03', 'Laki-Laki', 'Islam', 49, 'Permanen', '-', 'MUTIARA GADING TIMUR BLOK J 5/2 RT.02 RW.25', '', '08129488971', 'jokoharyanto92@gmail.com', 46, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000192', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(205, '19780625A', 'KF-JKT', '10000501', '', '3216062506780016', '689205938406000', 'MOHAMMAD MUHTAR', 'Cianjur', '1978-06-25', 'Laki-Laki', 'Islam', 44, 'Permanen', '-', 'PERUM PESONA MUTIARA TAMBUN 1 BLOK C3 NO 30 RT 008 RW 052', '', '081214146175', 'muhtarzidan@gmail.com', 17, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000193', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(206, '19680516B', 'KF-JKT', '10000506', '', '3275121605680004', '72986789407000', 'MUHAMAD SYAHRUDIN', 'Jakarta', '1968-05-16', 'Laki-Laki', 'Islam', 54, 'Permanen', '-', 'PONDOK MELATI RT.004 RW.005', '', '0895332090221', 'muhamadsyahrudin2393@gmail.com', 46, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000192', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(207, '19670820A', 'KF-JKT', '10000502', '', '3172012008670009', '473911857041000', 'MULYANTO', 'Jakarta', '1967-08-20', 'Laki-Laki', 'Islam', 54, 'Permanen', '-', 'TANAH PASIR RT.03 RW.13', '', '085210120211', 'mulyantoteknikkaef@gmail.com', 17, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000193', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(208, '19661209A', 'KF-JKT', '10000498', '', '3275110912660005', '473911774407000', 'NURDIN SUGANDI', 'Jakarta', '1966-12-09', 'Laki-Laki', 'Islam', 55, 'Permanen', '-', 'BEKASI TIMUR REGENCY BLOK E16 NO.10 RT.06 RW.13', '', '082113822428', 'sarananurdin@gmail.com', 15, 5, 1, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000192', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(209, '19720302A', 'KF-JKT', '10000499', '', '3171030203720004', '473911592027000', 'SLAMET', 'Jakarta', '1972-03-02', 'Laki-Laki', 'Islam', 50, 'Permanen', '-', 'GRIYA ASRI II BLOK DA 1 NO.7 RT.05 RW.49', '', '089601717570', 'mameslamet1@gmail.com', 15, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000498', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(210, '19660408B', 'KF-JKT', '10000503', '', '3172030804660005', '473911618413000', 'SUGIYONO', 'Purworejo', '1966-04-08', 'Laki-Laki', 'Islam', 56, 'Permanen', '-', 'JLNLINGGAWASTU327/25 RT04/16', '', '081221636942', 'dedisekunder@gmail.com', 16, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001946', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(211, '19950721A', 'KF-JKT', '10001770', '', '3175052107950002', '800024093009000', 'TRI JATMIKO', 'Jakarta', '1995-07-21', 'Laki-Laki', 'Islam', 27, 'Permanen', '-', 'JL PUSKESMAS RT.010 RW.011 KALISARI PASAR REBO JAKARTA TIMUR', '', '082110865857', 'jatmiko210795@gmail.com', 17, 5, 1, 7, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000193', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(212, '19880818A', 'KF-JKT', '10000193', '', '3175031808880007', '815837653002000', 'WAHYUDIN', 'Jakarta', '1988-08-18', 'Laki-Laki', 'Islam', 33, 'Permanen', '-', 'Jl. BASUKI RAHMAT NO.10 RT.02 RW.09', '', '081218048388', 'wahyudin@kimiafarma.co.id', 17, 5, 1, 6, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000192', 0, 1, '2022-02-03 14:41:24', '2022-02-07 12:09:38'),
(213, '19710415B', 'KF-JKT', '10000593', '', '3175011504710006', '473925964001000', 'ACHMAD', 'Jakarta', '1971-04-15', 'Laki-Laki', 'Islam', 51, 'Permanen', '-', 'JLN KAYUMANIS 5 LAMA RT 002 RW 01 NM 6', '', '0895365418555', 'achmadroy71milano@gmail.com', 35, 12, 3, 8, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000523', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(214, '19760123A', 'KF-JKT', '10000196', '', '3175062301760008', '689206308004000', 'ARI GUNAWAN', 'Jakarta', '1976-01-23', 'Laki-Laki', 'Islam', 46, 'Permanen', '-', 'PULO JAHE RT.07 RW.14', '', '081210207276', 'ari.gunawan@kimiafarma.co.id', 36, 12, 3, 6, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000152', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(215, '19710621A', 'KF-JKT', '10000363', '', '1207212106710004', '097147987122000', 'EVENDY SIREGAR', 'Tebing Tinggi', '1971-06-21', 'Laki-Laki', 'Kristen', 51, 'Permanen', '-', 'JL.PERTAHANAN GG.SETIA DUSUN II PATUMBAK KAMPUNG DELI SERDANG', '', '081397235845', 'siregar.evendy@kimiafarma.co.id', 34, 12, 3, 5, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000152', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(216, '19690314A', 'KF-JKT', '10000152', '', '3173061403690008', '', 'FIKRIANTO', 'Gunung Megang', '1969-03-14', 'Laki-Laki', 'Islam', 53, 'Permanen', '-', 'KEBON KELAPA RT 05/02 NO. 22', '', '08128444137', 'fikrianto@kimiafarma.co.id', 0, 12, 3, 4, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000119', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(217, '19700629A', 'KF-JKT', '10000523', '', '3171056906700001', '689205557024000', 'SRI SUSILOWATI', 'Jakarta', '1970-06-29', 'Perempuan', 'Islam', 52, 'Permanen', '-', 'CEMPAKA WARNA NO.19 RT.10 RW.04', '', '085693328322', 'usilowatis@gmail.com', 35, 12, 3, 6, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '10000152', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(218, '19950120D', 'KF-JKT', '10002577', '', '3275032001950027', '767256308407000', 'RIZKY ALAMSYAH PUTRA', 'Sragen', '1995-01-20', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'KP. SAWAH INDAH HARAPAN JAYA RT.005/RW.001', '', '082246672229', 'rizkyaltra20@gmail.com', 37, 10, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000195', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(219, '19980526C', 'KF-JKT', '10001824', '', '3275062605980018', '817107477427000', 'ANDREY TIYANTORO', 'Bekasi', '1998-05-26', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'JL. PEJUANG IV BLOK B239, BEKASI', '', '083897418818', 'andrey.tiyantoro@gmail.com', 23, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000492', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(220, '19880414A', 'KF-JKT', '10002708', '', '3275081404880016', '', 'ASEP RAHAYU SETIAWAN', 'Bekasi', '1988-04-14', 'Laki-Laki', 'Islam', 34, 'PKWT', '-', 'KP CIBENING JATIBENING RT 002 / RW 003', '', '081316989414', 'asep.chanu@gmail.com', 24, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000639', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(221, '19961130E', 'KF-JKT', '10002586', '', '3276107011960001', '825716160412000', 'DEWI PERMATASARI', 'Jakarta', '1996-11-30', 'Perempuan', 'Islam', 25, 'PKWT', '-', 'KP.CILANGKAP, RT003/014', '', '081259313681', 'dewipermatasa30@gmail.com', 22, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000483', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(222, '20000710A', 'KF-JKT', '10002587', '', '3175085007000001', '856018403005000', 'DINIYUL MARDHATILLAH', 'Jakarta', '2000-07-10', 'Perempuan', 'Islam', 22, 'PKWT', '-', 'JL. KOMODOR HALIM GG.SERUNI RT 08 RW 01 NO.91', '', '089647090551', 'diniyulmardhatillah@gmail.com', 22, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000483', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(223, '19971105B', 'KF-JKT', '10002588', '', '3204274511970020', '943574137444000', 'EVA PUSPITA', 'Bandung', '1997-11-05', 'Perempuan', 'Islam', 24, 'PKWT', '-', 'KP. SAYANG TENGAH RT 011/RW 003', '', '082317724719', 'eva.puspita0505@gmail.com', 24, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000639', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(224, '19950502F', 'KF-JKT', '10001764', '', '3275010205950013', '703366179407000', 'FARDY DARMAWAN', 'Bekasi', '1995-05-02', 'Laki-Laki', 'Katholik', 27, 'PKWT', '-', 'JL. PROF. MOCH. YAMIN NO. 49 RT 03/02, DUREN JAYA', '', '081291999191', 'goparspin@yahoo.com', 23, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000492', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(225, '19990824B', 'KF-JKT', '10002424', '', '3175052408990003', '867576852009000', 'FATHUR GERALDINE RAFANDI', 'Jakarta', '1999-08-24', 'Laki-Laki', 'Islam', 22, 'PKWT', '-', 'JL. DANA KARYA RT 004 RW 008', '', '081310613446', 'fthrgrldn24@gmail.com', 24, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000639', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(226, '19970422A', 'KF-JKT', '10002346', '', '3172046204971001', '902754704045000', 'KLARENCIA APRILIANTI', 'Jakarta', '1997-04-22', 'Perempuan', 'Islam', 25, 'PKWT', '-', 'JL. KENANGA NO. 28 RT 003 RW 010', '', '085709463572', 'klarenciaprilianti@gmail.com', 21, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000190', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(227, '19980904A', 'KF-JKT', '10002569', '', '3671040409990003', '', 'M. FIRDAUS', 'Payakumbuh', '1998-09-04', 'Laki-Laki', 'Islam', 23, 'PKWT', '-', 'PERUMAHAN PARK PLACE CLUSTER NAVARA BLOK D6 NO.10', '', '081394012017', 'mmfdaus10@gmail.com', 22, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000483', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(228, '19960306D', 'KF-JKT', '10002775', '', '3273024603960003', '826070054423000', 'MARWITA WAHYUNI', 'Sawahlunto', '1996-03-06', 'Perempuan', 'Islam', 26, 'PKWT', '-', 'HAUR MEKAR A15 RT./RW. 001/001', '', '085700186088', 'marwitaw@gmail.com', 19, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000480', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(229, '19950522E', 'KF-JKT', '10002779', '', '1306032205950002', '803933670432000', 'MIFTAHUSSYAKIR', 'Koto Kecil', '1995-05-22', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'JL. ENGGANG RAYA NO 63 RT 004 RW 003 KELURAHAN KAYURINGIN JAYA BEKASI SELATAN', '', '081219424625', 'miftahsyakir29@gmail.com', 24, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000639', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(230, '19980429B', 'KF-JKT', '10002778', '', '3271042904980011', '824688360404000', 'MUHAMAD ESA ZULFIKAR', 'Bogor', '1998-04-29', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'JL. KAV PANORAMA RT./RW. 005/005', '', '081310486341', 'esazulfikar@gmail.com', 24, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000639', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(231, '19980707A', 'KF-JKT', '10002570', '', '3175022307980009', '863914693003000', 'MUHAMAD IQBAL ANGGRIAWAN', 'Jakarta', '1998-07-07', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'JL.BANGUNAN BARAT NO.35', '', '085887880704', 'iqbalkarat07@gmail.com', 19, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000480', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(232, '19950925D', 'KF-JKT', '10002330', '', '3171062509950001', '754060044071000', 'MUHAMMAD FAHMI SYAUQI', 'Jakarta', '1995-09-25', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'JL. MENTENG JAYA NO. 59 RT. 005 RW. 001', '', '08990370128', 'fahmisyauqi6@gmail.com', 21, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000190', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(233, '19980322A', 'KF-JKT', '10001821', '', '3275032203980032', '823545579407000', 'MUHAMMAD IQBAL RUBABARA', 'Purworejo', '1998-03-22', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'TAMAN WISMA ASRI BLOK AA 15 NO. 59', '', '081315921540', 'iqbalrbbr98@gmail.com', 23, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000492', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(234, '19980702D', 'KF-JKT', '10002766', '', '3216020207980017', '842785594435000', 'NAUFAL RAHMAT WISUDAWAN', 'Jakarta', '1998-07-02', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'PD. UNGU PERMAI RT./RW. 001/021', '', '085389692832', 'rahmatn81@gmail.com', 22, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000480', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(235, '19960714A', 'KF-JKT', '10002347', '', '3201135407960003', '729674762403000', 'NIKEN GHAISANI RAZAN', 'Bekasi', '1996-07-14', 'Perempuan', 'Islam', 26, 'PKWT', '-', 'PERUM. PUSPA RAYA BLOK FB NO. 28 RT 004 RW 009', '', '082210298842', 'niken.grazan1407@gmail.com', 22, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000483', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(236, '19940718D', 'KF-JKT', '10002348', '', '3175055807940002', '737005876009000', 'NINDYA PUTRI', 'Jakarta', '1994-07-18', 'Perempuan', 'Islam', 28, 'PKWT', '-', 'JL. TRIKORA I RT 007 RW 009', '', '08569872641', 'nndyaputri1807@gmail.com', 22, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000483', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(237, '19950714B', 'KF-JKT', '10002590', '', '3216075407950011', '710578535435000', 'NURJANAH', 'Pemalang', '1995-07-14', 'Perempuan', 'Islam', 27, 'PKWT', '-', 'Kp. UTAN NO.76 RT.004/RW.029', '', '083891171004', 'qc.kfplantjakarta@gmail.com', 21, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000190', 0, 1, '2022-02-03 14:41:24', '2022-02-10 13:54:16'),
(238, '19981011E', 'KF-JKT', '10002776', '', '3171031110980004', '818826422027000', 'PRASETYO ARI WIBOWO', 'Jakarta', '1998-10-11', 'Laki-Laki', 'Islam', 23, 'PKWT', '-', 'KEMAYORAN GEMPOL RT./RW. 012/006', '', '08118199808', 'prasetyoari192@gmail.com', 21, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000190', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(239, '19940813D', 'KF-JKT', '10002589', '', '3275015308940007', '738483320407000', 'RADEN ANNISA ARINAL HAQ DADANG PADM', 'Bandung', '1994-08-13', 'Perempuan', 'Islam', 27, 'PKWT', '-', 'JL P SANGIHE 1 NO 58 RT 005/RW 018', '', '082298586774', 'raden.annisaarinal@gmail.com', 23, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000492', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(240, '19960529A', 'KF-JKT', '10002425', '', '1371092905960007', '867573586201000', 'RAFKI MIZAR', 'Padang', '1996-05-29', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'SURAU BALAI NO 8 RT 003 RW 004', '', '085363340921', 'rafkimizard04@gmail.com', 19, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000480', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(241, '19980127B', 'KF-JKT', '10002539', '', '3175066701981001', '839564606004000', 'TAMARIN RIZKI RAMADHANTI', 'Jakarta', '1998-01-27', 'Perempuan', 'Islam', 24, 'PKWT', '-', 'JALAN HR ISMAIL KAVLING BLOK O', '', '081291889811', 'tmrnrzkrmdnt@yahoo.com', 19, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000480', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(242, '19961019A', 'KF-JKT', '10002349', '', '3175055910960004', '867575524009000', 'TIAS OKTAVIANI NURINDA', 'Cilacap', '1996-10-19', 'Perempuan', 'Islam', 25, 'PKWT', '-', 'JL. KIRAI INDAH NO. 33 RT 006 RW 010', '', '085693858399', 'tiasoktav@gmail.com', 23, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000492', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(243, '19980517C', 'KF-JKT', '10002777', '', '3216011705980005', '842504524435000', 'YOGA EKA LESMANA', 'Jakarta', '1998-05-17', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'KP. PENGGARUTAN RT./RW. 003/007', '', '081290776505', 'yogaeka1806@gmail.com', 21, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000190', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(244, '19971211C', 'KF-JKT', '10002765', '', '1305041112970002', '848224895201000', 'YOGA PRATAMA', 'Kayu Tanam', '1997-12-11', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'PS. GLOMBANG KAYU RT./RW. 000/000', '', '082386534977', 'yoga135965@gmail.com', 24, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000639', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(245, '19940516E', 'KF-JKT', '10001732', '', '3278065605940009', '739323798425000', 'YOVA YUVITASARI', 'Tasikmalaya', '1994-05-16', 'Perempuan', 'Islam', 28, 'PKWT', '-', 'JL. JATINEGARA LIO RT 15 RW 03 NO. 15', '', '085719036349', 'yovayuvitasari@gmail.com', 23, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000492', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(246, '19930517B', 'KF-JKT', '10002458', '', '3216051705930006', '812972123435000', 'ACHMAD NURHAKIM', 'Bekasi', '1993-05-17', 'Laki-Laki', 'Islam', 29, 'PKWT', '-', 'KP GEBANG  RT 02/09', '', '089630339671', 'ahmadnurhakim101@gmail.com', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000211', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(247, '19950520C', 'KF-JKT', '10002543', '', '3275112005950008', '839882396432000', 'ADE PRIHANTO', 'Wonogiri', '1995-05-20', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'KP.CIKETING RT 001 RW 012 , MUSTIKA JAYA', '', '085717478511', 'prihantoade@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(248, '19950816E', 'KF-JKT', '10001771', '', '3208121608950005', '702243049438000', 'AGUSTINA', 'Kuningan', '1995-08-16', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'DUSUN MANIS RT 01/01', '', '081322019231', 'aagustin1624@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(249, '19970212C', 'KF-JKT', '10001804', '', '3175011202971001', '753338516001000', 'AKBAR FIKRI', 'Jakarta', '1997-02-12', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'GG. KEMBANG NO. 14 RT. 014/05, PISANGAN BARU, MATRAMAN, JAKA', '', '081385927522', 'akbarfkri@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(250, '19940404C', 'KF-JKT', '10001730', '', '3306140404940001', '700202062531000', 'AMAT SOLIKHIN', 'Purworejo', '1994-04-04', 'Laki-Laki', 'Islam', 28, 'PKWT', '-', 'KROYO KIDUL', '', '085720241527', 'amatsolikhin17@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(251, '19960318C', 'KF-JKT', '10002422', '', '3172041803960005', '750059669045000', 'ANDRIE SUTRISNO', 'Subang', '1996-03-18', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'JL. ROROTAN IX RT 016 RW 007', '', '083898385797', 'andriesutrisno33@gmail.com', 11, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(252, '19970721B', 'KF-JKT', '10002761', '', '3209246107970008', '844558247426000', 'AROFATUL JANNAH', 'Cirebon', '1997-07-21', 'Perempuan', 'Islam', 25, 'PKWT', '-', 'DS.JUNGJANG WETAN BLOK 2 007/002 KEC.ARJAWINANGUN KAB.CIREBON', '', '08161623200', 'ar.arofatuljannah@gmail.com', 10, 4, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001833', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(253, '20010101B', 'KF-JKT', '10002571', '', '3201030101000005', '915077002436000', 'EKA MAULANA JAMIL', 'Bogor', '2001-01-01', 'Laki-Laki', 'Islam', 21, 'PKWT', '-', 'JL.ATENG ILYAS KP.MUHARA RT 001/007 CITEUREUP BOGOR', '', '088212903230', 'ekamaulanajamil@gmail.com', 10, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(254, '19990110B', 'KF-JKT', '10001829', '', '3275021001990012', '818678203427000', 'FAJAR RAMADHAN', 'Bekasi', '1999-01-10', 'Laki-Laki', 'Islam', 23, 'PKWT', '-', 'KP. SETU BINTARA JAYA RT 08/01 NO. 43', '', '0895332090221', 'fajarramadhannnn@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(255, '19940412C', 'KF-JKT', '10002764', '', '3275031204940025', '843108242407000', 'I PUTU PANDE SAKA PERDANA PUTRA', 'Jakarta', '1994-04-12', 'Laki-Laki', 'Hindu', 28, 'PKWT', '-', 'KP. HARAPAN KITA RT./RW. 011/009', '', '081286261615', 'pandeputu703@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(256, '19950910A', 'KF-JKT', '10001772', '', '3328051009950001', '812576767501000', 'KASNURI NUGROHO', 'Tegal', '1995-09-10', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'DS.SRENGSENG 004/001', '', '081385153219', 'kasnurin@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(257, '19960829B', 'KF-JKT', '10001795', '', '3175022908960005', '640098000003000', 'KHOERUM MUSTAKIM', 'Jakarta', '1996-08-29', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'JL. KAHURIPAN BLOK AF 12 RT 17/22 NO.20, PERUMAHAN VGH 1, BABELAN BEKASI', '', '089605556810', 'mustakimkhoerum@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(258, '19961216A', 'KF-JKT', '10002423', '', '3329071612960005', '808219141501000', 'M. ADE RIYAN', 'Brebes', '1996-12-16', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'DESA KRAMAT RT 002 RW 001', '', '081386044696', 'aderyanm1@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(259, '19960504D', 'KF-JKT', '10001785', '', '3175060405960003', '736530155004000', 'MIFTAHUDIN', 'Jakarta', '1996-05-04', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'JL. MERPATI 11 RT010/RW009', '', '08111549945', 'hudinkito@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(260, '20000415A', 'KF-JKT', '10002591', '', '3302015504000003', '848582565521000', 'MITHA WULAN PRADANI', 'Banyumas', '2000-04-15', 'Perempuan', 'Islam', 22, 'PKWT', '-', 'DESA KARANGGAYAM RT 003 RW 004 KEC. LUMBIR, KAB. BANYUMAS -', '', '087736870737', 'mithawulan.prdni15@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(261, '19940627D', 'KF-JKT', '10002434', '', '3306142706940002', '736618836036000', 'MUHAMAD RIDWAN', 'Purworejo', '1994-06-27', 'Laki-Laki', 'Islam', 28, 'PKWT', '-', 'BETENG RT. 001 RW. 005', '', '08562939716', 'ridwangalaxysusu@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(262, '19930528D', 'KF-JKT', '10002707', '', '3329032805930001', '', 'MUHAMAD SOLEHUDIN', 'Brebes', '1993-05-28', 'Laki-Laki', 'Islam', 29, 'PKWT', '-', 'LANGKAP KRAJAN RT003/RW001', '', '082310025448', 'masoleh1699@gmail.com', 10, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001833', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(263, '19931110B', 'KF-JKT', '10001713', '', '3275021011930012', '660914524407000', 'MUHAMMAD HASRI AL HASAN', 'Jakarta', '1993-11-10', 'Laki-Laki', 'Islam', 28, 'PKWT', '-', 'KOMP. LKBN ANTARA I NO. 42 RT. 04/10, BINTARA JAYA', '', '089601717570', 'muhammadhasrialhasan@gmail.com', 10, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(264, '19970112C', 'KF-JKT', '10002550', '', '3175071201970001', '838702785008000', 'MUHAMMAD RIDWAN', 'Jakarta', '1997-01-12', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'JL KP KAPUK3 RT009/05 NO :14 KLE:KLENDER KEC:DUREN SAWIT', '', '081387734265', 'ridwandirga12@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00');
INSERT INTO `employees` (`id`, `nip`, `location`, `sap_id`, `parent_nik`, `nik`, `npwp`, `employee_name`, `birth_place`, `birth_date`, `gender`, `religion`, `age`, `employee_status`, `os_name`, `address`, `phone`, `mobile`, `email`, `division_id`, `sub_department_id`, `department_id`, `rank_id`, `location_id`, `user_id`, `sk_number`, `sk_date`, `sk_start_date`, `sk_end_date`, `status`, `overtime`, `direct_spv`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(265, '19970211D', 'KF-JKT', '10001803', '', '3175061102970009', '838870830004000', 'MUSTAQIM', 'Jakarta', '1997-02-11', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'JL. PISANGAN BULAK RT 019/05 NO. 102', '', '085943174053', 'mustaqim.kimiafarma@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000209', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(266, '19960616C', 'KF-JKT', '10002881', '', '3175091606960004', '', 'TEDI SYARIF HIDAYAT', 'Jakarta', '1996-06-16', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'GG. H. SIIN RT 014/002', '', '081289811555', 'tedysyarifhidayat@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(267, '19981005B', 'KF-JKT', '10002549', '', '3175060510980005', '838603083004000', 'WIDYA ANNASRULLOH', 'Jakarta', '1998-05-20', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'KP. PEDURENAN RT013/006', '', '081511465586', 'widya.annasrulloh@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000216', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(268, '19920731B', 'KF-JKT', '10002353', '', '3175087107920001', '862773181005000', 'ANGGI RIA PUSPITA SARI SUSILO', 'Gunung Kidul', '1992-07-31', 'Perempuan', 'Islam', 30, 'PKWT', '-', 'JL. PELANGI NO. 706 RT 007 RW 009 KOMPLEK ANGKASA HALIM PERDANAKUSUMAH', '', '081382984722', 'queengie92@gmail.com', 25, 6, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000638', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(269, '19980829C', 'KF-JKT', '10002354', '', '3275062908980005', '863394441427000', 'IBNU HAJAR', 'Bekasi', '1998-08-29', 'Laki-Laki', 'Islam', 23, 'PKWT', '-', 'PONDOK ALAM INDAH RT 004 RW 031', '', '085719032334', 'ibnuhajar249@gmail.com', 26, 6, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000224', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(270, '19920121C', 'KF-JKT', '10001657', '', '1271206101920003', '704252162113000', 'JIHAN HUSNA', 'Medan', '1992-01-22', 'Perempuan', 'Islam', 30, 'PKWT', '-', 'JL. KARIKATUR NO. 36, KOMP. WARTAWAN', '', '082276225822', 'jihanhusna21@gmail.com', 26, 6, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000224', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(271, '19970507B', 'KF-JKT', '10001808', '', '3175064705970010', '755522299407000', 'MEGA NUR AMDANI', 'Jakarta', '1997-05-07', 'Perempuan', 'Islam', 25, 'PKWT', '-', 'Jl. P. SAPARUA 6 No. 257 RT 011/008,', '', '0895636738044', 'nramdanimega5@gmail.com', 26, 6, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000224', 0, 1, '2022-02-03 14:41:24', '2022-02-10 14:03:14'),
(272, '19990706A', 'KF-JKT', '10002762', '', '3175064607991004', '842472870004000', 'CANTIKA DEWI NURFARAHAH', 'Cianjur', '1999-07-06', 'Perempuan', 'Islam', 23, 'PKWT', '-', 'JL PENDIDIKAN NO 28 K, RT/RW 009/005', '', '087886801032', 'ncantikadewi@gmail.com', 33, 9, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000589', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(273, '19951119C', 'KF-JKT', '10002495', '', '1703165911950001', '', 'LINA SHARFINA', 'Bengkulu Utara', '1995-11-19', 'Perempuan', 'Islam', 26, 'PKWT', '-', 'DESA TEPI LAUT', '', '081747144498', 'sharfinasfar@gmail.com', 33, 9, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000589', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(274, '19840726A', 'KF-JKT', '10002455', '', '3205172607840003', '692088396004000', 'WANDI HADIN', 'Garut', '1984-07-26', 'Laki-Laki', 'Islam', 38, 'PKWT', '-', 'KAMPUNG LIO RT 013 RW 03,', '', '081311507725', 'wandihadin8488@gmail.com', 33, 9, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000589', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(275, '19950524E', 'KF-JKT', '10002542', '', '3207102405950001', '726386527407000', 'ADI PRASETIO', 'Ciamis', '1995-05-24', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'Jl tepi sungai mahakam no.27 rt.001/010 harapan mulya kec. Medan satria kota Bekasi', '', '082114591896', 'adipras967@gmail.com', 44, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(276, '19961119A', 'KF-JKT', '10001797', '', '3175071910960002', '712624444008000', 'ADITYA PRATAMA', 'Bekasi', '1996-11-19', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'JL. KP. KAPUK II RT 009/005 NO. 53', '', '08558078324', 'aditalkadjanii@gmail.com', 44, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(277, '19961127A', 'KF-JKT', '10001796', '', '3172022711960007', '724110150048000', 'ALI MUSTAHKIM', 'Brebes', '1996-11-27', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'JL. PAPANGGO II A NO. 47 RT 003/03', '', '081296753255', 'ali.mustakim31@gmail.com', 44, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(278, '19970226B', 'KF-JKT', '10002456', '', '3175062602970017', '926380809006000', 'ANDIKA SUKARNO PUTRA', 'Jakarta', '1997-02-26', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'KAMPUNG BUARAN RT 01 / RW 03', '', '087777861002', 'andikasukarno124@gmail.com', 42, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000212', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(279, '19940717E', 'KF-JKT', '10002704', '', '3175071707940001', '846638963008000', 'BANNIE HANNIFAH', 'Jakarta', '1994-07-17', 'Laki-Laki', 'Islam', 28, 'PKWT', '-', 'KP. MALAKA NO. 57 RT.03 RW.02', '', '081311377677', 'bannie.hannifah@gmail.com', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000194', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(280, '19941205E', 'KF-JKT', '10002638', '', '3275124512940001', '745829788447000', 'DESI WIRAWATI', 'Bekasi', '1994-12-05', 'Perempuan', 'Islam', 27, 'PKWT', '-', 'KP PONDOK RANGGON, RT 04 RT 03 NO. 59', '', '085651009677', 'desiwirawati@gmail.com', 44, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(281, '19880524B', 'KF-JKT', '10002327', '', '3275082405880027', '846869410502000', 'DIDI SUPRIYANTO', 'Pekalongan', '1988-05-24', 'Laki-Laki', 'Islam', 34, 'PKWT', '-', 'TAMBAKROTO RT. 005 RW. 001', '', '081549640730', 'didisupriyanto24@gmail.com', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000194', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(282, '19980526D', 'KF-JKT', '10002538', '', '3275032605980021', '839224474407000', 'DIMAS PRAYOGA', 'Madiun', '1998-05-26', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'kav. kaliabang tengah rt. 007/004 bekasi utara kota bekasi', '', '081213705096', 'ydimas151@gmail.com', 43, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000215', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(283, '19980323B', 'KF-JKT', '10002767', '', '3175062303980010', '766417216004000', 'FAQIH ABDILLAH MOHAMMAD', 'Jakarta', '1998-03-23', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'KP. RAWA GELAM RT./RW. 001/006', '', '081314187371', 'faqihabdllh@gmail.com', 44, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(284, '19990801A', 'KF-JKT', '10002540', '', '3175060108991001', '833569569004000', 'GILANG PERKASA SOULSAL', 'Jakarta', '1999-08-01', 'Laki-Laki', 'Islam', 23, 'PKWT', '-', 'Gg. Hasan 4 RT 03 RW 04 No 39B Kel. Penggilingan, Kec. Cakung, Jakarta Timur, DKI Jakarta Kode pos 13940', '', '081293181376', 'gsoulsal@gmail.com', 44, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(285, '19950408A', 'KF-JKT', '10002768', '', '3329160804950004', '669041253501000', 'IRFAN IBRAHIM', 'Brebes', '1995-04-08', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'KARANGMALANG RT./RW. 001/001', '', '081906754588', 'irfan08.com@gmail.com', 44, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(286, '19941021B', 'KF-JKT', '10002454', '', '3172042110940003', '463431106045000', 'LUTHFI FAHMI', 'Jakarta', '1994-10-21', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'JL. MARUNDA BARU NO 1 RT 3 RW 3', '', '082124332424', 'lutfifahmi439@gmail.com', 42, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000212', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(287, '19970820B', 'KF-JKT', '10002572', '', '3275062008970016', '739327815407000', 'MUHAMAD HAIQAL', 'Bekasi', '1997-08-20', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'PONDOK UNGU GG H MAT SOLEH RT 02/04 MEDAN SATRIA KOTA BEKASI', '', '081281582822', 'haiqalm303@gmail.com', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000194', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(288, '19920410B', 'KF-JKT', '10002329', '', '3175071004920008', '810565788008000', 'MUHAMMAD IQBAL', 'Jakarta', '1992-04-10', 'Laki-Laki', 'Islam', 30, 'PKWT', '-', 'JL. KAMPUNG MALAKA 4 RT. 001/011', '', '081517041680', 'iqbalmuhammad100492@gmail.com', 43, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000215', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(289, '19950415F', 'KF-JKT', '10002435', '', '3175095504950006', '169694874009000', 'NUR APRILIANY', 'Jakarta', '1995-04-15', 'Perempuan', 'Islam', 27, 'PKWT', '-', 'JL. MANUNGGAL II RT. 011 RW. 006', '', '085766316017', 'namanurapriliany@gmail.com', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000194', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(290, '19950213B', 'KF-JKT', '10001756', '', '3175071302950005', '547764498008000', 'NUR HIDAYAT ROMADHON', 'Jakarta', '1995-02-13', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'JL. BOJONG INDAH RT 07/06', '', '085693823054', 'hidayatsullivan@gmail.com', 43, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000215', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(291, '19950509D', 'KF-JKT', '10002541', '', '3208300905950003', '554066852438000', 'PONIMAN', 'Kuningan', '1995-05-09', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'Jl walang sari raya no 26. Rt. 003/002\nKel.  Tugu utara kec. Koja', '', '082113577574', 'pmeidiantoro@gmail.con', 44, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000229', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(292, '19990825B', 'KF-JKT', '10002580', '', '3275112508990005', '828985077432000', 'RAHMAT PRADITO SETIAJI', 'Jakarta', '1999-08-25', 'Laki-Laki', 'Islam', 22, 'PKWT', '-', 'JL.MURAI 7 BLOK H16/41 BTR RT 05 RW 016', '', '082310745881', 'rahmatpradito@gmail.com', 43, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000215', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(293, '19900913B', 'KF-JKT', '10002328', '', '3276061309900004', '704247865412000', 'SAIFUL NAHRAWI', 'Jakarta', '1990-09-13', 'Laki-Laki', 'Islam', 31, 'PKWT', '-', 'KP. PENGARENGAN RT. 011 RW. 006', '', '081584994008', 'saifulnahrawi@gmail.com', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000194', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(294, '19921209B', 'KF-JKT', '10001680', '', '3175020912920005', '442066890003000', 'WIJAYANTO', 'Jakarta', '1992-12-09', 'Laki-Laki', 'Islam', 29, 'PKWT', '-', 'JL. PERHUBUNGAN INDAH NO. 7 RT 015/007', '', '085100813682', 'jayacodeine@gmail.com', 43, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000215', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(295, '19940709B', 'KF-JKT', '10002341', '', '3312124907940002', '550884522532000', 'WINDA', 'Wonogiri', '1994-07-09', 'Perempuan', 'Islam', 28, 'PKWT', '-', 'NGASEM TENGAH RT 002 RW 002', '', '082284540064', 'winda.angelia09@gmail.com', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000194', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(296, '19931223G', 'KF-JKT', '10002436', '', '3303182312930001', '973244866529000', 'AAN FATUR RAHMAN', 'Purbalingga', '1993-12-23', 'Laki-Laki', 'Islam', 28, 'PKWT', '-', 'MERGASANA Rt. 009 Rw. 003', '', '085855329484', 'vaturrasta46@gmail.com', 2, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 1, '2022-02-03 14:41:24', '2022-02-10 20:40:02'),
(297, '20001220A', 'KF-JKT', '10002334', '', '3201026112020007', '869322644403000', 'AL FITRIYANAH', 'Brebes', '2000-12-20', 'Perempuan', 'Islam', 21, 'PKWT', '-', 'KP. TLAJUNG RT 005 RW 002', '', '082110724424', 'alfitri.yanah20@gmail.com', 3, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(298, '19950524C', 'KF-JKT', '10002437', '', '3276022405950015', '552617318412000', 'ANAS NUGROHO', 'Pemalang', '1995-05-24', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'PEDURENAN DEPOK RT. 003 RW. 001 BLOK A NO 5', '', '085776948616', 'anasnugroho486@gmail.com', 2, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(299, '19960321B', 'KF-JKT', '10001782', '', '3171052103960001', '643740236024000', 'ANDIKA SETIAWAN', 'Jakarta', '1996-03-21', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'JL. CEMPAKA PUTIH TIMUR XVII, RT 008/03 NO.7A, JAKARTA PUSAT', '', '082213710775', 'dikawan30@gmail.com', 2, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000213', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(300, '19980303B', 'KF-JKT', '10002592', '', '3175104303980001', '855072351009000', 'ANNISA NURFAZILAH', 'Jakarta', '1998-03-03', 'Perempuan', 'Islam', 24, 'PKWT', '-', 'JLN. SPG VII RT 008/ RW 009 NO. 76', '', '085694158021', 'annisanurfazilah98@gmail.com', 1, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(301, '20000923A', 'KF-JKT', '10002335', '', '3175066309000008', '869475368004000', 'FAUZIAH ALIYAH', 'Jakarta', '2000-09-23', 'Perempuan', 'Islam', 21, 'PKWT', '-', 'JATINEGARA LIO RT 013 RW 003', '', '081280127380', 'fauziahaliyah05@gmail.com', 3, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(302, '19951120C', 'KF-JKT', '10002598', '', '3305122011950001', '710539669523000', 'HERWIN HERNANDI', 'Kebumen', '1995-11-20', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'DESA WONOSARI, RT02 RW03, KEC. KEBUMEN, KAB. KEBUMEN, JAWA T', '', '081392122167', 'herwinhernandi@gmail.com', 2, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(303, '19951104C', 'KF-JKT', '10002336', '', '3207124411950001', '868377680442000', 'HIRMAWATY', 'Jakarta', '1995-11-04', 'Perempuan', 'Islam', 26, 'PKWT', '-', 'Kp. Rawa Badung, Rt/Rw 008/007', '', '085641255331', 'hirmawaty04@gmail.com', 3, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(304, '19990102A', 'KF-JKT', '10002582', '', '3175064201990008', '830220489006000', 'INNEKE PUTRI', 'Jakarta', '1999-01-02', 'Perempuan', 'Islam', 23, 'PKWT', '-', 'JL. PULO GEBANG RT.001 RW.04 NO.36C', '', '081310376956', 'innekeputri92@gmail.com', 2, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(305, '19981126A', 'KF-JKT', '10002337', '', '3275026611980014', '867910374427000', 'KIKI NOVIYANTI', 'Jakarta', '1998-11-26', 'Perempuan', 'Islam', 23, 'PKWT', '-', 'KP. RAWA BEBEK RT 005 RW 015', '', '082210898513', 'kikinoviyanti26@gmail.com', 3, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(306, '19930418C', 'KF-JKT', '10002705', '', '3175071804930013', '', 'MUHCLIS', 'Bantul', '1993-04-18', 'Laki-Laki', 'Islam', 29, 'PKWT', '-', 'KP. GANDARIA RT 002/07', '', '08561103175', 'muhclisdimedjo.raden@gmail.com', 1, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000207', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(307, '19960526B', 'KF-JKT', '10002338', '', '3311062605960001', '703216812532000', 'PRASETIYO', 'Sukoharjo', '1996-05-26', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'CABEYAN RT 002 RW 003', '', '081387502477', 'prast3030@gmail.com', 3, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(308, '19980112B', 'KF-JKT', '10001817', '', '3175061201980001', '824367247006000', 'RAMDANI', 'Jakarta', '1998-01-12', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'JL. RAWA KUNING, GG. DAMAI RT. 003/016 NO. 3F', '', '083821995932', 'ramadanidhany12@gmail.com', 3, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(309, '20000719A', 'KF-JKT', '10002418', '', '3175061907000007', '908741937006000', 'WAHYU ARDIANSYAH', 'Jakarta', '2000-07-19', 'Laki-Laki', 'Islam', 22, 'PKWT', '-', 'JL. DAMAI NO. 94 RT 002 RW 008', '', '081295893694', 'wahyuar223@gmail.com', 1, 1, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000207', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(310, '19980709B', 'KF-JKT', '10002419', '', '3275064907980005', '806913513427000', 'ALFI CRISSNIAN DANA', 'Bekasi', '1998-07-09', 'Perempuan', 'Islam', 24, 'PKWT', '-', 'Jl. KA ROROTAN Kav. HARAPAN RT 001 RW 031', '', '08988271937', 'admproduksi2.kfupj@gmail.com', 7, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000204', 0, 1, '2022-02-03 14:41:24', '2022-02-10 13:30:30'),
(311, '19960531B', 'KF-JKT', '10002438', '', '3306073105960001', '700961717531000', 'ALVIAN DENI PRIYAMBUDI', 'Purworejo', '1996-05-31', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'BORO TAWANG RT. 004 RW. 002', '', '081322736094', 'alvianpriyambudi@gmail.com', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001837', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(312, '19970226A', 'KF-JKT', '10002339', '', '3175062602970014', '861379618006000', 'ANDRE PERWIRA', 'Jakarta', '1997-02-26', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'UJUNG KRAWANG RT 014 RW 005', '', '081806871537', 'andreperwira97@gmail.com', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001837', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(313, '19941218D', 'KF-JKT', '10001749', '', '3305031812940003', '748366390523000', 'ARIS ANDRIANTO', 'Purbalingga', '1994-12-18', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'Tambakmulyo', '', '081399917499', 'arisandrianto1994@gmail.com', 5, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(314, '19920809C', 'KF-JKT', '10002439', '', '3302230908920002', '365117159521000', 'DANU PURNIAWAN', 'Banyumas', '1992-08-09', 'Laki-Laki', 'Islam', 30, 'PKWT', '-', 'KARANGNANGKA RT. 001 RW. 001', '', '085732270797', 'purniawandanu@gmail.com', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001837', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(315, '19941216C', 'KF-JKT', '10002440', '', '3327081612940003', '986025336502000', 'DEDI SAPUTRA', 'Pemalang', '1994-12-16', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'BOJONGBATA RT/RW 02/03 PEMALANG', '', '081383498855', 'dsputra61@gmail.com', 4, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(316, '20010508A', 'KF-JKT', '10002575', '', '3402170805010001', '916415524543000', 'ERVIN MERDIANTO', 'Bantul', '2001-05-08', 'Laki-Laki', 'Islam', 21, 'PKWT', '-', 'SELOGEDONG, RT 060, ARGODADI, SEDAYU, BANTUL, YOGYAKARTA', '', '087889310089', 'ervinmerdianto2018@gmail.com', 4, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(317, '19950723C', 'KF-JKT', '10002599', '', '3302022307950004', '704857556521000', 'FAIZAL ROZAQI', 'Banyumas', '1995-07-13', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'KLAGADING RT 001 RW 008', '', '085875316638', 'faizalrozaqi49@gmail.com', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001837', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(318, '19990215A', 'KF-JKT', '10002420', '', '3273165502990003', '902926543424000', 'FEBY NURAENI', 'Bandung', '1999-02-15', 'Perempuan', 'Islam', 23, 'PKWT', '-', 'HANTAP RT 009 RW 014', '', '081311086634', 'febynuraeni11@gmail.com', 7, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000204', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(319, '19970908A', 'KF-JKT', '10002340', '', '3216020809970010', '861477776435000', 'HIMAWAN PRABOWO', 'Jakarta', '1997-09-08', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'PONDOK UNGU PERMAI SEKTOR V BLOK R6 NO. 11 RT 005 RW 029', '', '081380629882', 'himawanprbw@gmail.com', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001837', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(320, '20000829B', 'KF-JKT', '10002576', '', '3404072908000006', '916205842542000', 'MUHAMMAD USNAN AGISTA', 'Sleman', '2000-08-29', 'Laki-Laki', 'Islam', 21, 'PKWT', '-', 'TLUKAN SAMBILEGI KIDUL RT 007/RW058, MAGUWOHARJO, DEPOK, SLE', '', '087838224661', 'usnanagista@gmail.com', 7, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000204', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(321, '19921005D', 'KF-JKT', '10001674', '', '3522250510920001', '979189644601000', 'PIPIT PUJI UTOMO', 'Bojonegoro', '1992-10-05', 'Laki-Laki', 'Islam', 29, 'PKWT', '-', 'DS.HARGOMULYO, RT-04/RW-01 NO.125,KEC.KEDEWAN, KAB.BOJONEGORO, JAWA TIMUR', '', '085225275853', 'pipitpujiutomo@gmail.com', 4, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000233', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(322, '19971104C', 'KF-JKT', '10002593', '', '3175074411971002', '749964672008000', 'SITI MAISAROH', 'Banten', '1997-11-04', 'Perempuan', 'Islam', 24, 'PKWT', '-', 'JL. TALANG RT 10 RW 04', '', '089607146594', 'maes47974@gmail.com', 7, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000204', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(323, '19961026A', 'KF-JKT', '10002573', '', '3301011609960005', '713081214004000', 'WAHYU PRATAMA', 'Cilacap', '1996-10-26', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'KP. RAWA BADUNG RT 006/013', '', '081213191282', 'wahyu.pr1878@gmail.com', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001837', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(324, '19960209E', 'KF-JKT', '10002574', '', '3275060902960009', '713764124407000', 'WAWAN DARMAWAN', 'Bekasi', '1996-02-09', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'UJUNG MENTENG', '', '087870143859', 'rendydarmawan56@gmail.com', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001837', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(325, '19981117A', 'KF-JKT', '10002395', '', '3303125711980002', '903661247529000', 'WIKA PRATIWI', 'Purbalingga', '1998-11-17', 'Perempuan', 'Islam', 23, 'PKWT', '-', 'TUNJUNGMULI KARANGMONCOL PURBALINGGA', '', '085870407227', 'wikapratiwi71198@gmail.com', 7, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000204', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(326, '19970804A', 'KF-JKT', '10002396', '', '3327010408970001', '745058990004000', 'AKHMAD ZUHDAN', 'Pemalang', '1997-08-04', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'JALAN BAKUNG MOGA', '', '087764247165', 'zuhdanachmad21@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(327, '20000218A', 'KF-JKT', '10002594', '', '3275065802000019', '857718720407000', 'ALIN NUR AFIFAH', 'Bekasi', '2000-02-18', 'Perempuan', 'Islam', 22, 'PKWT', '-', 'JL. SILLI III RAWA BUGEL RT005/026 NO.5', '', '08119148943', 'alinafifah18@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000205', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(328, '19951207E', 'KF-JKT', '10002397', '', '3175064712950003', '711172544004000', 'AMELIA', 'Jakarta', '1995-12-07', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'Kp. JEMBATAN RT 009 RW 017 No. 57', '', '081548019392', 'kfpj.arv@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000205', 0, 1, '2022-02-03 14:41:24', '2022-02-10 13:28:23'),
(329, '19980104C', 'KF-JKT', '10002398', '', '3311060401980002', '909579864435000', 'ANWAR ANSHORI', 'Sukoharjo', '1998-01-04', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'bendosari, sukoharjo, jawa tengah', '', '085721105536', 'anwar29anshori@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(330, '20000110A', 'KF-JKT', '10002399', '', '3275025001000021', '902397496427000', 'AYU FAUZIAH', 'Jakarta', '2000-01-10', 'Perempuan', 'Islam', 22, 'PKWT', '-', 'JL. BINTARA 1 RT.001/08', '', '085716169133', 'fauziahayu2@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000205', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(331, '19991031A', 'KF-JKT', '10002400', '', '3671127110990004', '903967487008000', 'DHIYA SALSABILA', 'Padang Panjang', '1999-10-31', 'Perempuan', 'Islam', 22, 'PKWT', '-', 'JL. BUARAN 2 RT. 002 RW. 013 NO 40 C', '', '085725440750', 'dhiyasalsabila9@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(332, '19900918C', 'KF-JKT', '10001650', '', '3175021809901001', '443124466003000', 'LAZIM MA\'MUN', 'Jakarta', '1990-09-18', 'Laki-Laki', 'Islam', 31, 'PKWT', '-', 'KP. PENGGILINGAN BARU, RT.001 RW.008 NO.20', '', '085693779562', 'lazimpc@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(333, '19970626B', 'KF-JKT', '10002401', '', '3175062606970019', '803539915004000', 'MOHAMAD AKBAR', 'Jakarta', '1997-06-26', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'KP. rawa gelam rt004/006', '', '08129062389', 'makbarchaniago6@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(334, '19931217B', 'KF-JKT', '10001719', '', '3275061712930013', '447104688407000', 'NIDE MUHAMMAD NIYAZI', 'Bekasi', '1993-12-17', 'Laki-Laki', 'Islam', 28, 'PKWT', '-', 'PONDOK UNGU, JL. H. MAT SOLEH NO. 16 RT 003/004', '', '087804126766', 'nidemuhammadniyazi@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(335, '19981216A', 'KF-JKT', '10002402', '', '3175041612980009', '908402738432000', 'RAFLI ALIF DHARMAWAN', 'Jakarta', '1998-12-16', 'Laki-Laki', 'Islam', 23, 'PKWT', '-', 'JL. BAKUNG MOGA RT 003 RW 007', '', '082134900588', 'letoylemot272@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(336, '19940628E', 'KF-JKT', '10001738', '', '3216066806940019', '744687468435000', 'RESNA YUNITA SARI', 'Bekasi', '1994-06-28', 'Perempuan', 'Islam', 28, 'PKWT', '-', 'GRAND WISATA CLUSTER AQUATIC GARDEN BG 02  NO.36 TAMBUN SELATAN', '', '081382101267', 'resna.yunitasari@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(337, '19960529B', 'KF-JKT', '10001788', '', '3201022905960008', '706879442403000', 'RINALDI ANJAS PRASETYA', 'Bogor', '1996-05-29', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'jl.mandor lipin villa mahual blok B.4', '', '087823233572', 'rinaldianjas.p@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000560', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(338, '19980218B', 'KF-JKT', '10002403', '', '3175061802980012', '902358365006000', 'SIGIT SYAMSUL FAKHRUDI', 'Demak', '1998-02-18', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'JL RAWA KUNING RT03/016', '', '082216022300', 'syamsulsigit18@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(339, '19981219B', 'KF-JKT', '10002404', '', '3303161912980001', '828986919529000', 'TOFAN FIRMANSYAH', 'Purbalingga', '1998-12-19', 'Laki-Laki', 'Islam', 23, 'PKWT', '-', 'DESA PASUNGGINGAN RT 024 RW 010', '', '085799906311', 'firmansyahtofan91@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(340, '19970405C', 'KF-JKT', '10002406', '', '1812060504970006', '735123846326000', 'YUNUS PRASETYA', 'Rawa Jitu', '1997-04-05', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'INDRALOKA 1,RT/RW 001/001,TUBABAR,LAMPUNG', '', '085770446815', 'prasetyayunus305@gmail.com', 8, 3, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001325', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(341, '19950720D', 'KF-JKT', '10002600', '', '3175032007950007', '947069589002000', 'KARUNIA SASTRA SAPUTRA', 'Jakarta', '1995-07-20', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'JL. PANCAWARGA VI RT 011/001', '', '083893799286', 'karuniasastraputra@gmai.com', 38, 11, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000646', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(342, '19960416D', 'KF-JKT', '10002548', '', '3175065604961005', '707122131006000', 'NURFAH MADINI', 'Jakarta', '1996-04-16', 'Perempuan', 'Islam', 26, 'PKWT', '-', 'JL. BUDI MULIA RT 007/004', '', '08977124273', 'nurfah16@gmail.com', 39, 11, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000202', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(343, '19951216F', 'KF-JKT', '10002547', '', '3275061612950007', '703179903407000', 'SYAIKHUL ICHWAN', 'Bekasi', '1995-12-16', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'Jl. MAT SOLEH PONDOK UNGU RT 004 RW 004', '', '087871107260', 'sdmkf01@gmail.com', 38, 11, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000646', 0, 1, '2022-02-03 14:41:24', '2022-02-10 13:40:09'),
(344, '19920521A', 'KF-JKT', '10002578', '', '3174022105920001', '914531629011000', 'TOMMY ALDY RAMAWANSYAH', 'Jakarta', '1992-05-21', 'Laki-Laki', 'Islam', 30, 'PKWT', '-', 'JL KARET BELAKANG', '', '082122269705', 'tommyaldy@gmail.com', 40, 11, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000977', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(345, '19960712A', 'KF-JKT', '10002326', '', '3175011207960004', '846865061001000', 'ARIF TRI MULYANTO', 'Bekasi', '1996-07-12', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'JL. CEMPAKA RT. 003 RW. 02 NO. 142 JATIBENING BARU', '', '081385709143', 'ariftrisakti@gmail.com', 31, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000641', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(346, '19970101C', 'KF-JKT', '10002568', '', '3275020101970016', '732003066407000', 'ARY SINGGIH GANITA PUTRA', 'Cilacap', '1997-01-01', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'JL BINTARA NO 14', '', '08123636967', 'arysinggih91@gmail.com', 31, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000641', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(347, '19950720C', 'KF-JKT', '10002342', '', '3175076007951002', '801312331008000', 'AYU DAHLIA ARISTA', 'Jakarta', '1995-07-20', 'Perempuan', 'Islam', 27, 'PKWT', '-', 'JL. H. AHYAR NO. 1 RT 006 RW 005', '', '081381378739', 'ayudahliaarista@gmail.com', 31, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000641', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(348, '19971223A', 'KF-JKT', '10002426', '', '3277036312970010', '858699416421000', 'DESSY CARTIKA', 'Cimahi', '1997-12-23', 'Perempuan', 'Islam', 24, 'PKWT', '-', 'JL. CIHANJUANG KP. BABUT GIRANG RT 005 RW 011', '', '08987889688', 'dessycartika@gmail.com', 27, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001839', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(349, '19930613B', 'KF-JKT', '10002441', '', '3276011306930010', '808193205448000', 'DWI GUNAWAN', 'Depok', '1993-06-13', 'Laki-Laki', 'Islam', 29, 'PKWT', '-', 'JL. RAYA TANAH BARU GANG SWADAYA 6 KP. JEMBLONGAN RT. 005RW.', '', '087775715287', 'dwigunawanq@gmail.com', 31, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000641', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(350, '19930421E', 'KF-JKT', '10002772', '', '1609172104930002', '710966037302000', 'ERDIAN HUDAYA', 'Baturaja', '1993-04-21', 'Laki-Laki', 'Islam', 29, 'PKWT', '-', 'PERUM GRAHA BAGASASI RT./RW. 006/013', '', '082312078089', 'erdian.hudaya@gmail.com', 27, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001839', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(351, '19960219D', 'KF-JKT', '10001780', '', '3273051902960004', '710124066428000', 'FAJAR BUANA', 'Bandung', '1996-02-19', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'JL. ANDIR RI WINATA NO. 217B/79 RT 07/08', '', '08569010458', 'fajarbuana13@gmail.com', 28, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001297', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(352, '19930210A', 'KF-JKT', '10001681', '', '3216061002930019', '738703933435000', 'FAJAR WAHYUTOMO', 'Jakarta', '1993-02-10', 'Laki-Laki', 'Islam', 29, 'PKWT', '-', 'TRIDAYA INDAH III BLOK D15/5 RT. 001/005', '', '081282727792', 'fwahyutomo@gmail.com', 29, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000644', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(353, '19961202D', 'KF-JKT', '10002763', '', '3277014212960017', '842998528421000', 'HERA DECISHAERA CANDRIH', 'Bandung', '1996-12-02', 'Perempuan', 'Islam', 25, 'PKWT', '-', 'JL. IBU GANIRAH NO. 65 RT./RW. 002/005', '', '087822447554', 'heradecishaera@gmail.com', 29, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000644', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(354, '19970427F', 'KF-JKT', '10002692', '', '3216116704970002', '', 'LIA ANGGRAENI', 'Bekasi', '1997-04-27', 'Perempuan', 'Islam', 25, 'PKWT', '-', 'RAWA GEBANG RT 01/02', '', '085215021124', 'anggraenilia2704@gmail.com', 31, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000641', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(355, '19960113F', 'KF-JKT', '10002770', '', '3204351301960002', '831018239444000', 'MIZANUL ISLAM', 'Bandung', '1996-01-13', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'KP. LEGOK TEUREUP RT./RW. 004/006', '', '082214123185', 'mizanul.islam07@gmail.com', 27, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001839', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(356, '19940528C', 'KF-JKT', '10001734', '', '3201132805940002', '861219345403000', 'MUHAMMAD ADAM', 'Jakarta', '1998-05-28', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'KP. SETU RT.008 RW002', '', '082128744234', 'mhmmad.adam@gmail.com', 31, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000641', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(357, '19961220C', 'KF-JKT', '10002394', '', '3604166006980001', '830788196401000', 'MUIDOTUL MUNAWAROH', 'Serang', '1996-12-20', 'Perempuan', 'Islam', 25, 'PKWT', '-', 'KP. LAES NAMBO RT 005 / RW 002', '', '085885501171', 'muidotulm@gmail.com', 30, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001848', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(358, '19960114E', 'KF-JKT', '10002602', '', '3209245401960006', '842547788426000', 'NUR FITRI YULIYANTI', 'Cirebon', '1996-01-14', 'Perempuan', 'Islam', 26, 'PKWT', '-', 'PERUM GBA 2 BLOK J5 NO. 19', '', '081224233427', 'nurfitriyulii@gmail.com', 27, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001839', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(359, '19950508C', 'KF-JKT', '10002343', '', '3275034805950016', '803313451407000', 'RATRI NIRMALA PUSPA', 'Bekasi', '1995-05-08', 'Perempuan', 'Islam', 27, 'PKWT', '-', 'TAMAN WISMA ASRI CC 33 NO. 27 RT 005 RW 026', '', '089681192678', 'ratrin895@gmail.com', 27, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001839', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(360, '19980903A', 'KF-JKT', '10002677', '', '3216064309980019', '937158962435000', 'REVIAN PRISCA ERNINDA', 'Jakarta', '1998-09-03', 'Perempuan', 'Islam', 23, 'PKWT', '-', 'GRIYA ASRI 2 BLOK E 11 NO. 33 JALAN NAKULA', '', '081284723335', 'revianprisca3998@gmail.com', 0, 15, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001731', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(361, '19950205E', 'KF-JKT', '10002457', '', '3275020502950015', '846585602427000', 'REZKI FEBRI RAMADHAN', 'Jakarta', '1995-02-05', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'RAWA BEBEK RT 01/RW 11', '', '081299362523', 'rezkifebrir@gmail.com', 27, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001839', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(362, '19941023E', 'KF-JKT', '10002427', '', '3202396310940002', '804625085405000', 'WISAUDA\'A WARDAH', 'Sukabumi', '1994-10-23', 'Perempuan', 'Islam', 27, 'PKWT', '-', 'KP. CISAYAR RT 004 RW 008', '', '089678067435', 'wardahstar@gmail.com', 28, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001297', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(363, '19960422D', 'KF-JKT', '10002676', '', '3208146204960001', '827917477438000', 'YATI CAHAYATI', 'Kuningan', '1996-04-22', 'Perempuan', 'Islam', 26, 'PKWT', '-', 'DUSUN JATISARI RT/RW 004/001', '', '081321980519', 'y.cahayati@gmail.com', 28, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001297', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(364, '19971018D', 'KF-JKT', '10002702', '', '3275091810970018', '947094645447000', 'ABI TONJO BUONO', 'Jakarta', '1997-10-18', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'KP KEBANTENAN RT002 RW007', '', '081286458017', 'abitonjobuono@gmail.com', 16, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001946', 0, 1, '2022-02-03 14:41:24', '2022-02-10 14:01:06'),
(365, '19950203B', 'KF-JKT', '10002546', '', '3275120302950001', '835931577447000', 'ANSGARIO BONDHAN DIGDYARHAMA', 'Jakarta', '1995-02-03', 'Laki-Laki', 'Kristen', 27, 'PKWT', '-', 'JALAN MELATI TENGAH NO.18 RT 008/ RW 006', '', '081281702093', 'ansgariobondhan@gmail.com', 16, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001946', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(366, '19980204H', 'KF-JKT', '10002703', '', '3276014402980002', '862425972068000', 'ARIFAH BUDIARTI NURFITRI', 'Gunung Kidul', '1998-02-04', 'Perempuan', 'Islam', 24, 'PKWT', '-', 'JL. CIPEDAK 1 NO.65A RT 003/RW009, KELURAHAN SRENGSENG SAWAH, KECAMATAN JAGAKARSA, JAKARTA SELATAN', '', '081212052341', 'arifah.budiarti8@gmail.com', 46, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000192', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(367, '19971227B', 'KF-JKT', '10002350', '', '3175062712970013', '863601688004000', 'AULIA TRISNADI PUTRA', 'Jakarta', '1997-12-27', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'KP. JEMBATAN RT 015 RW 012', '', '081818738025', 'aulia.trisnadi27@gmail.com', 15, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000498', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(368, '19971105D', 'KF-JKT', '10002701', '', '3603180511970014', '956503460452000', 'DENY PRAKARSA', 'Subang', '1997-11-05', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'BUKIT TIARA BLK F1/3 RT/RW 20/4', '', '081294440801', 'denyprakarsa@gmail.com', 16, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001946', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(369, '19950917C', 'KF-JKT', '10001773', '', '3175025709950002', '835567959003000', 'DINDA ALINA ZAHRA', 'Jakarta', '1995-09-17', 'Perempuan', 'Islam', 26, 'PKWT', '-', 'JL. CIPINANG TIMUR V NO. 16, JAKARTA 13240', '', '081289133717', 'dindaalza@gmail.com', 18, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001210', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(370, '19960420G', 'KF-JKT', '10002771', '', '3174012004960001', '820741338015000', 'FADHIL HAFIYANTAMA', 'Jakarta', '1996-04-20', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'BUKIT DURI TANJAKAN RT./RW. 004/009', '', '081314589195', 'hafiyantamafadhil@gmail.com', 0, 14, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000064', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(371, '19950929E', 'KF-JKT', '10002545', '', '3205032909950001', '837698836443000', 'FAJAR RACHMAN FAUZI', 'Garut', '1995-09-29', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'KP. SAMANGGEN', '', '08111773707', 'fajarracmanf.95@gmai.com', 16, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001946', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(372, '19980428C', 'KF-JKT', '10002700', '', '3205292804980001', '908280134443000', 'ILYAS SALMAN NUGRAHA', 'Garut', '1998-04-28', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'JL. GATOT SUBROTO NO.81 RT/RW 003/009', '', '081380161160', 'ilyas.salman.n28@gmail.com', 16, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10001946', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(373, '19970518B', 'KF-JKT', '10002351', '', '3201071805970002', '865603864436000', 'MUHAMAD IQBAL FERNANDA', 'Jakarta', '1997-05-18', 'Laki-Laki', 'Islam', 25, 'PKWT', '-', 'METLAND CILEUNGSI AB 6/25 RT 004 RW 010', '', '08567844075', 'miqbalfernanda17@gmail.com', 0, 14, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000064', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(374, '19960314B', 'KF-JKT', '10002544', '', '3175061403960005', '834115495004000', 'ZAENAL MUSTOFA', 'Jakarta', '1996-03-14', 'Laki-Laki', 'Islam', 26, 'PKWT', '-', 'KAMPUNG RAWA GELAM RT 002 RW 006 A 33', '', '085157544648', 'zennmusss14@gmail.com', 15, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000498', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(375, '19960128C', 'KF-JKT', '10002558', '', '9206016802960002', '947209128955000', 'FLORENSIA KLARA KINHO TITIT', 'Teluk Bintuni', '1996-01-28', 'Perempuan', 'Kristen', 26, 'PKWT', '-', 'KAMPUNG FRUATA', '', '085244484693', 'rensi.kinho@gmail.com', 35, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000523', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(376, '19920610C', 'KF-JKT', '10002780', '', '3507221006920002', '833570955657000', 'GAMAYAZID ABDILLAH', 'Magetan', '1992-06-10', 'Laki-Laki', 'Islam', 30, 'PKWT', '-', 'JL. TENES MEJA NO.47', '', '08111549885', 'gamayazid@gmail.com', 36, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000196', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(377, '19980720A', 'KF-JKT', '10002325', '', '3175062007980001', '905743852004000', 'JULIAN PRAKASIWI', 'Kebumen', '1998-07-20', 'Laki-Laki', 'Islam', 24, 'PKWT', '-', 'KP. PEDAENGAN', '', '081340040001', 'julianprakasiwi@gmail.com', 36, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000196', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(378, '19830602B', 'KF-JKT', '10002706', '', '3175020206830007', '169505914003000', 'RIDWAN ZULKARNAEN', 'Jakarta', '1983-06-02', 'Laki-Laki', 'Islam', 39, 'PKWT', '-', 'PULO ASEM 6 NO.29 C RT 04 RW 01', '', '081289254301', 'ridwan.zulkarnaen83@gmail.com', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000363', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(379, '19950425D', 'KF-JKT', '10002579', '', '3275112504950002', '200101476176000', 'TAFRI JIAH AJI SAHROJI', 'Bekasi', '1995-04-25', 'Laki-Laki', 'Islam', 27, 'PKWT', '-', 'KP.CIKETING RT 001 RW 009', '', '082213972603', 'tafriaji.2595@gmail.com', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '10000363', 0, 0, '2022-02-03 14:41:24', '0000-00-00 00:00:00'),
(380, '30000151', 'KF-JKT', '30000151', '', '3175051902960002', '', 'BAGUES DWIATMIKO', '', '1996-02-19', 'Laki-Laki', 'Islam', 26, 'Kontrak OS', 'PT. BANGUN SINAR INDONESIA', '', '', '', '', 23, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(381, '30000150', 'KF-JKT', '30000150', '', '3275030701000032', '', 'BAGUS RAMDANI', '', '2000-01-07', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. BANGUN SINAR INDONESIA', '', '', '', '', 20, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(382, '50000173', 'KF-JKT', '50000173', '', '3175010809020001', '', 'DIFATAMA HIZKIA PUTRA ELYADA', '', '2002-09-08', 'Laki-Laki', 'Kristen', 19, 'Kontrak OS', 'PT. INDOPSIKO INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(383, '50000149', 'KF-JKT', '50000149', '', '3175026910991001', '', 'LIA OKTAVIANI', '', '1999-10-29', 'Perempuan', 'Islam', 22, 'Kontrak OS', 'PT. INDOPSIKO INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(384, '60000100', 'KF-JKT', '60000100', '', '3275016404970010', '', 'RULIKA APRIANTI', 'Indonesia', '1997-04-24', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', 'Indonesia', '', '62', 'purchasingkfpj@gmail.com', 37, 10, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-10 14:09:43'),
(385, '60000195', 'KF-JKT', '60000195', '', '3275010907000013', '', 'ANDIS PAHREZI', '', '2000-07-09', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 21, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(386, '60000101', 'KF-JKT', '60000101', '', '3216013112970001', '', 'ANDRE SETIAWAN', '', '1997-12-31', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 22, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(387, '60000192', 'KF-JKT', '60000192', '', '3674060511980002', '', 'ARIA MANDALIKA PUTRA', '', '1998-11-05', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 19, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(388, '60000103', 'KF-JKT', '60000103', '', '3216062904000017', '', 'DICKY NURRAHMAN', '', '1999-04-29', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 22, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(389, '60000209', 'KF-JKT', '60000209', '', '3212156105000002', '', 'FADILLAH ISNAENI', '', '2000-05-21', 'Perempuan', 'Islam', 22, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 23, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(390, '60000184', 'KF-JKT', '60000184', '', '3201132206980005', '', 'HARIS FADHILAH', '', '1998-06-22', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 21, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(391, '60000194', 'KF-JKT', '60000194', '', '3275062005980014', '', 'ILHAM AKBAR', '', '1998-05-20', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 22, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00');
INSERT INTO `employees` (`id`, `nip`, `location`, `sap_id`, `parent_nik`, `nik`, `npwp`, `employee_name`, `birth_place`, `birth_date`, `gender`, `religion`, `age`, `employee_status`, `os_name`, `address`, `phone`, `mobile`, `email`, `division_id`, `sub_department_id`, `department_id`, `rank_id`, `location_id`, `user_id`, `sk_number`, `sk_date`, `sk_start_date`, `sk_end_date`, `status`, `overtime`, `direct_spv`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(392, '60000202', 'KF-JKT', '60000202', '', '3175050111010001', '', 'ILHAM FIRMANSYAH WAHYUDI', '', '2001-11-01', 'Laki-Laki', 'Islam', 20, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 22, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(393, '60000163', 'KF-JKT', '60000163', '', '3175082206970004', '', 'IMAM AGUNG SAPUTRO', '', '1997-06-22', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 21, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(394, '60000240', 'KF-JKT', '60000240', '', '3672072802000002', '', 'IQBAL RIZKI TRISANTOSO', '', '2000-02-28', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 19, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(395, '60000104', 'KF-JKT', '60000104', '', '3172020404970008', '', 'IYAS NUR HAKIM', '', '1997-04-04', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 21, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(396, '60000241', 'KF-JKT', '60000241', '', '3271022708000008', '', 'MUHAMMAD FIKRI FIRDAUS', '', '2000-08-27', 'Laki-Laki', 'Islam', 21, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 20, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(397, '60000106', 'KF-JKT', '60000106', '', '1271112311980002', '', 'MUHAMMAD RIZAL ARDIANSYAH SIREGAR', '', '1998-04-13', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 23, 8, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(398, '10002239', 'KF-JKT', '10002239', '', '3275020402970014', '', 'AHMAD KHOIRUDDIN', '', '1997-02-04', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(399, '60000137', 'KF-JKT', '60000137', '', '3275022407000016', '', 'ARI JULIANTO', '', '2000-07-24', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(400, '10002242', 'KF-JKT', '10002242', '', '3329020406970001', '', 'DIMAS IMADUDIN', '', '1997-06-04', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(401, '60000204', 'KF-JKT', '60000204', '', '3275022303980019', '', 'ERIYANTO RAHMAT MAULANA', '', '1998-03-23', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(402, '60000110', 'KF-JKT', '60000110', '', '1305034103980001', '', 'MELLA PRANIA SAPUTRI', '', '1998-03-01', 'Perempuan', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(403, '10002245', 'KF-JKT', '10002245', '', '3212285810970002', '', 'NUR AFIKA', '', '1997-10-18', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(404, '10002244', 'KF-JKT', '10002244', '', '3209231010940015', '', 'NURSANDI', '', '1994-10-17', 'Laki-Laki', 'Islam', 27, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(405, '60000113', 'KF-JKT', '60000113', '', '3275115205010013', '', 'SITI NURSYAHLILA HASIBUAN', '', '2001-05-12', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(406, '60000114', 'KF-JKT', '60000114', '', '3201071402010008', '', 'VALENTINO ANDREA KERAF', '', '2001-02-14', 'Laki-Laki', 'Kristen', 21, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(407, '60000115', 'KF-JKT', '60000115', '', '3175104603980003', '', 'WIDIYANTI', 'Indonesia', '1998-03-06', 'Perempuan', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', 'Indonesia', '', '0895', 'pengemasprimer.kfupj@gmail.com', 11, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-10 10:38:05'),
(408, '60000208', 'KF-JKT', '60000208', '', '3172034205091006', '', 'INDAH SUCIATI', '', '1999-05-02', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 33, 9, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(409, '60000118', 'KF-JKT', '60000118', '', '3172043011981001', '', 'ANDRIYANTO', '', '1998-11-30', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 43, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(410, '60000119', 'KF-JKT', '60000119', '', '3216021508970014', '', 'BHIMA MAHARDHIKA DEWA', '', '1997-08-15', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 43, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(411, '60000121', 'KF-JKT', '60000121', '', '3175022909971001', '', 'MUHAMMAD IKHWAN', '', '1997-09-29', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 43, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(412, '60000122', 'KF-JKT', '60000122', '', '3305122106980008', '', 'MUSTAFANGANI', '', '1998-06-21', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 42, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(413, '60000210', 'KF-JKT', '60000210', '', '3275042504010015', '', 'TARJONO', '', '2001-04-25', 'Laki-Laki', 'Islam', 21, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 43, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(414, '60000138', 'KF-JKT', '60000138', '', '3303110406960004', '', 'ARIF FAZALULOH', '', '1996-06-04', 'Laki-Laki', 'Islam', 26, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(415, '60000139', 'KF-JKT', '60000139', '', '3327121402970001', '', 'DUKHAERI', '', '1997-02-14', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(416, '60000140', 'KF-JKT', '60000140', '', '3471070606010001', '', 'FAJAR ANDRIVAL', '', '2001-06-06', 'Laki-Laki', 'Islam', 21, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(417, '60000142', 'KF-JKT', '60000142', '', '3312200503960003', '', 'NUR KHOLISH AZHARI', '', '1996-03-05', 'Laki-Laki', 'Islam', 26, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(418, '60000199', 'KF-JKT', '60000199', '', '3206364801960003', '', 'SARAH SARBILA', '', '1996-01-08', 'Perempuan', 'Islam', 26, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(419, '60000198', 'KF-JKT', '60000198', '', '3216067003970004', '', 'SISKA PUTRI UTAMI', '', '1997-03-30', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(420, '60000144', 'KF-JKT', '60000144', '', '3329020905990001', '', 'YUDI AKBAR MAULANA', '', '1997-05-09', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 6, 2, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(421, '60000242', 'KF-JKT', '60000242', '', '3204084512980008', '', 'WIDYA SAVIERA PUTRI', '', '1998-12-05', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 41, 11, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(422, '60000193', 'KF-JKT', '60000193', '', '3175074708980009', '', 'CHYNTIA YOANE PUTRI', '', '1998-08-07', 'Perempuan', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 27, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(423, '60000145', 'KF-JKT', '60000145', '', '3216066810960018', '', 'DEYA OCTAVIA', '', '1996-10-28', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 29, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(424, '60000146', 'KF-JKT', '60000146', '', '3275091505020006', '', 'ERICK SETIAWAN', '', '2001-05-15', 'Laki-Laki', 'Islam', 21, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 27, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(425, '60000196', 'KF-JKT', '60000196', '', '3175071009980009', '', 'IMADDUDIN HARIZ ARRASYID', '', '1998-09-10', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 28, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(426, '60000148', 'KF-JKT', '60000148', '', '3175036806971001', '', 'MUTIA', 'Indonesia', '1997-06-28', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', 'Indonesia', '', '62', 'mutia757@gmail.com', 0, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-10 13:56:38'),
(427, '60000243', 'KF-JKT', '60000243', '', '3217065707980006', '', 'RANA AULIA YUPITASARY', '', '1998-07-17', 'Perempuan', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 27, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(428, '60000201', 'KF-JKT', '60000201', '', '3175075904950009', '', 'RESTIA PUTRI RAMADHAN', '', '1997-01-18', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 31, 7, 2, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(429, '60000151', 'KF-JKT', '60000151', '', '3175060708970004', '', 'AGUS PRIYANTO', '', '1997-08-17', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 16, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(430, '60000152', 'KF-JKT', '60000152', '', '3520152606920001', '', 'AMIRUL ALHADI WIJAYA', '', '1992-06-26', 'Laki-Laki', 'Islam', 30, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 46, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(431, '60000244', 'KF-JKT', '60000244', '', '3275112809930002', '', 'ARMAN SEPTIAN', 'Bekasi', '1993-09-28', 'Laki-Laki', 'Islam', 28, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', 'Jl. Kh. Agus Salim No.8 Kp. Bulak Slamet RT6 RW8 Bekasi Jaya Bekasi Timur', '', '089517227009', 'septian.arman009@gmail.com', 18, 5, 1, 9, 1, 13, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-05 01:20:17'),
(432, '60000153', 'KF-JKT', '60000153', '', '3216062003950020', '', 'FIKRI AGIL ROIHAN MURTADHO', '', '1995-03-20', 'Laki-Laki', 'Islam', 27, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 18, 5, 1, 9, 1, 12, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(433, '60000245', 'KF-JKT', '60000245', '', '3276030308000004', '', 'MUHAMMAD HAIKAL ABDI', '', '2000-08-03', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 46, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 1, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(434, '60000211', 'KF-JKT', '60000211', '', '330122203980004', '', 'SAMSUL ANAM', '', '1998-03-22', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 16, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(435, '60000154', 'KF-JKT', '60000154', '', '3328095001960002', '', 'SITI NURAZIZAH', 'Indonesia', '1996-01-10', 'Perempuan', 'Islam', 26, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', 'Indonesia', '', '085325461244', 'teknik.kfupj@kimiafarma.co.id', 0, 5, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-10 10:22:49'),
(436, '60000200', 'KF-JKT', '60000200', '', '3175077108990010', '', 'SYADZA SORAYA', 'Indonesia', '1999-08-31', 'Perempuan', 'Islam', 22, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', 'Indonesia', '', '08970667107', 'capex.upj@kimiafarma.co.id', 0, 14, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-10 13:37:50'),
(437, '60000156', 'KF-JKT', '60000156', '', '3310064105980001', '', 'CINDY PUSPITAWATI', 'Indonesia', '1998-05-01', 'Perempuan', 'Islam', 24, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', 'Indonesia', '', '62', 'k3l.plantjakarta@gmail.com', 35, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-10 13:59:05'),
(438, '70000169', 'KF-JKT', '70000169', '', '3214013003890002', '', 'DENI HAMDAN', 'Indonesia', '1989-03-30', 'Laki-Laki', 'Islam', 33, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', 'Indonesia', '', '087883464367', 'dhnnesaka@gmail.com', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-10 10:20:42'),
(439, '60000158', 'KF-JKT', '60000158', '', '3175061111700009', '', 'NURUL ANWAR', 'Indonesia', '1970-11-11', 'Laki-Laki', 'Islam', 51, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', 'Indonesia', '', '0895', 'nurul.anwar@gmail.com', 34, 12, 3, 10, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-11 07:25:51'),
(440, '50000153', 'KF-JKT', '50000153', '', '3175102412810009', '', 'SYAMSUDIN', '', '1981-12-24', 'Laki-Laki', 'Islam', 40, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 34, 12, 3, 10, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(441, '60000159', 'KF-JKT', '60000159', '', '3175061408730002', '', 'TARKO', '', '1973-08-14', 'Laki-Laki', 'Islam', 49, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 34, 12, 3, 10, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(442, '60000160', 'KF-JKT', '60000160', '', '3201340309850002', '', 'YOKI SUPRIADI', '', '1985-09-03', 'Laki-Laki', 'Islam', 36, 'Kontrak OS', 'PT. KREASIBOGA PRIMATAMA', '', '', '', '', 34, 12, 3, 10, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(443, '70000215', 'KF-JKT', '70000215', '', '3175061410960010', '', 'ACHMAD FADLI', '', '1996-10-14', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(444, '70000100', 'KF-JKT', '70000100', '', '3216072404990008', '', 'ACHMAD MIFTAHUDIN', '', '1999-04-24', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(445, '70000214', 'KF-JKT', '70000214', '', '3175030205010003', '', 'ADAM RIZKY HIDAYAT', '', '2001-05-02', 'Laki-Laki', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(446, '70000195', 'KF-JKT', '70000195', '', '3174044403010007', '', 'ADELA SALSA FADILLAH', '', '2001-03-04', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(447, '70000102', 'KF-JKT', '70000102', '', '3209281412990007', '', 'ADI SETIADI', '', '1999-12-14', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(448, '70000247', 'KF-JKT', '70000247', '', '3171022707971002', '', 'ADITYA BRILIANDI', '', '1997-07-27', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(449, '70000222', 'KF-JKT', '70000222', '', '3328114306970008', '', 'AFRIANI SUDRAJAT', '', '1997-06-03', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(450, '70000290', 'KF-JKT', '70000290', '', '3175061209010001', '', 'AGUNG DZULKARNAIN', '', '2001-09-12', 'Laki-Laki', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(451, '70000241', 'KF-JKT', '70000241', '', '3202052105990003', '', 'AGUNG GUNAWAN', '', '1999-05-21', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(452, '70000103', 'KF-JKT', '70000103', '', '3207312403010006', '', 'AGUNG SETIADI', '', '2001-02-24', 'Laki-Laki', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(453, '70000191', 'KF-JKT', '70000191', '', '3175066601010002', '', 'AIDA SAFITRI', '', '2001-01-26', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(454, '70000104', 'KF-JKT', '70000104', '', '3275115407990007', '', 'AJENG GITA MEGANTARA', '', '1999-07-14', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(455, '70000236', 'KF-JKT', '70000236', '', '3275075109010012', '', 'ALIFAH RUSWANTO', '', '2001-09-11', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(456, '70000225', 'KF-JKT', '70000225', '', '3276055107010004', '', 'ALIFIAH AL RAFA', '', '2001-07-11', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(457, '70000197', 'KF-JKT', '70000197', '', '3306095008020001', '', 'ANIS ZULAEHA', '', '2002-08-10', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(458, '70000106', 'KF-JKT', '70000106', '', '3275045610000017', '', 'ANNISA MEYDIANA', '', '2000-11-16', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(459, '70000234', 'KF-JKT', '70000234', '', '3275054904020015', '', 'APRILIANI NUR PRAYITNO', '', '2002-04-09', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(460, '70000107', 'KF-JKT', '70000107', '', '3329040508960007', '', 'ARI AGUS PRIYANTO', '', '1996-08-05', 'Laki-Laki', 'Islam', 26, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(461, '70000291', 'KF-JKT', '70000291', '', '3201291107020001', '', 'ARKAN RIZQI PURNOMO', '', '2002-07-11', 'Laki-Laki', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(462, '70000223', 'KF-JKT', '70000223', '', '3201375701010002', '', 'AULYA WULANDARI', '', '2001-01-17', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(463, '70000110', 'KF-JKT', '70000110', '', '3175075606010007', '', 'AYU NUR TALLIDA', '', '2001-06-15', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(464, '70000111', 'KF-JKT', '70000111', '', '321075105010011', '', 'AZARINE PRANITHA', '', '2001-05-11', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(465, '70000112', 'KF-JKT', '70000112', '', '317506441100008', '', 'BELLA SETYOWATI', '', '2000-11-04', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(466, '70000220', 'KF-JKT', '70000220', '', '1809014108020003', '', 'CANTIKA NOVITA SARI', '', '2002-08-01', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(467, '70000228', 'KF-JKT', '70000228', '', '3175035001970011', '', 'DIAN NURDIANA', '', '1997-01-10', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(468, '70000288', 'KF-JKT', '70000288', '', '3175030512960012', '', 'DICKY DIRGANTARA', '', '1996-12-05', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(469, '70000114', 'KF-JKT', '70000114', '', '3175065007000011', '', 'DITHA AULIA', '', '2000-07-10', 'Perempuan', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(470, '70000115', 'KF-JKT', '70000115', '', '3302104212980001', '', 'DIYAN RISNAWATI DEWI', '', '1998-12-02', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(471, '70000224', 'KF-JKT', '70000224', '', '3329035108020003', '', 'ELIS AGISTIANA', '', '2002-08-11', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(472, '70000116', 'KF-JKT', '70000116', '', '3216026604990002', '', 'ELSA INDRASARI DEWI', '', '1999-04-25', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(473, '70000237', 'KF-JKT', '70000237', '', '1809017108000005', '', 'ERLINDA', '', '2000-08-31', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(474, '70000292', 'KF-JKT', '70000292', '', '3175072805980004', '', 'FAHMI ALDI', '', '1998-05-28', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(475, '70000235', 'KF-JKT', '70000235', '', '3216065301020014', '', 'FARIDA NURHIDAYAH', '', '2002-01-13', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(476, '70000286', 'KF-JKT', '70000286', '', '3175066506001002', '', 'FAUDIA NERCHALIZA ', '', '2000-06-25', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(477, '70000285', 'KF-JKT', '70000285', '', '3275027004990017', '', 'FEBIOLA NUR APRILIA ', '', '1999-04-30', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(478, '70000293', 'KF-JKT', '70000293', '', '3175061304020010', '', 'FERDI AZLY', '', '2002-04-13', 'Laki-Laki', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(479, '70000119', 'KF-JKT', '70000119', '', '3175065609000005', '', 'FIRDA FAUZIAH', '', '2000-09-16', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(480, '70000229', 'KF-JKT', '70000229', '', '3175104612020001', '', 'FITRA KURNIA DESTIANA', '', '2002-12-06', 'Perempuan', 'Islam', 19, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(481, '70000120', 'KF-JKT', '70000120', '', '3175020908000007', '', 'GILANG FIRDAUSI', '', '2000-08-09', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(482, '70000226', 'KF-JKT', '70000226', '', '3275115007000005', '', 'GITA SYAFINA UMRI', '', '2000-07-10', 'Perempuan', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(483, '70000245', 'KF-JKT', '70000245', '', '3173031104010003', '', 'HAMJAS ALWI', '', '2001-04-11', 'Laki-Laki', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(484, '70000242', 'KF-JKT', '70000242', '', '3175091508980003', '', 'HERMAWAN', '', '1998-08-15', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(485, '70000124', 'KF-JKT', '70000124', '', '3175070206980004', '', 'ILHAM DWI SETIO', '', '1998-06-02', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(486, '70000289', 'KF-JKT', '70000289', '', '3275042509020015', '', 'ILHAM VIRGIAWAN', '', '2002-09-25', 'Laki-Laki', 'Islam', 19, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(487, '70000240', 'KF-JKT', '70000240', '', '3203175203020001', '', 'INDRIYANTI', '', '2002-03-12', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(488, '70000217', 'KF-JKT', '70000217', '', '32030401079990537', '', 'IRVAN', '', '2000-04-05', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(489, '70000230', 'KF-JKT', '70000230', '', '3329105111000004', '', 'KHUSNUL MUSTAQIYAH', '', '2000-11-11', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(490, '70000192', 'KF-JKT', '70000192', '', '3275026905010008', '', 'MELANI NURUL SAFITRI', '', '2000-05-29', 'Perempuan', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(491, '70000131', 'KF-JKT', '70000131', '', '33227071110598007', '', 'MUHAMAD FATKHAN', '', '1998-05-11', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(492, '70000244', 'KF-JKT', '70000244', '', '3175070805021004', '', 'MUHAMAD KAPIOLAN', '', '2002-05-08', 'Laki-Laki', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(493, '70000209', 'KF-JKT', '70000209', '', '3275051706000015', '', 'MUHAMMAD DAFFA', '', '2000-06-17', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(494, '70000210', 'KF-JKT', '70000210', '', '3275112311980003', '', 'MUHAMMAD RIFKI FIRDAUS PUTRA', '', '1998-11-23', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(495, '70000206', 'KF-JKT', '70000206', '', '3275026407990015', '', 'MUTIA SARI', '', '1999-07-24', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(496, '70000204', 'KF-JKT', '70000204', '', '3175106610010001', '', 'NESRINA LISDAYANI', '', '2001-10-26', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(497, '70000137', 'KF-JKT', '70000137', '', '317503681100005', '', 'NOVIA RAHMADHANI', '', '2000-02-28', 'Perempuan', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(498, '70000231', 'KF-JKT', '70000231', '', '3175046508010002', '', 'NUR NISA', '', '2001-08-25', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(499, '70000193', 'KF-JKT', '70000193', '', '3171036602010005', '', 'NURUL KHOTIMAH', '', '2001-02-26', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(500, '70000138', 'KF-JKT', '70000138', '', '3175065105001003', '', 'PUJA AZIZAH', '', '2000-05-11', 'Perempuan', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(501, '70000139', 'KF-JKT', '70000139', '', '3175066912000011', '', 'PUTRI RACHMAWATI MULYADI', '', '2000-12-29', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(502, '70000227', 'KF-JKT', '70000227', '', '3175065709970011', '', 'QONITAH ABDILA', '', '1999-09-17', 'Perempuan', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(503, '70000141', 'KF-JKT', '70000141', '', '3171061908010006', '', 'RAIHAN ANANDA PRATAMA', '', '2001-08-19', 'Laki-Laki', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(504, '70000213', 'KF-JKT', '70000213', '', '3275050807000018', '', 'REZA SANDI AKBAR', '', '1996-07-06', 'Laki-Laki', 'Islam', 26, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(505, '70000212', 'KF-JKT', '70000212', '', '3175070808970004', '', 'REZA SOFYAN FAUJI', '', '1997-06-06', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(506, '70000202', 'KF-JKT', '70000202', '', '3175036707970007', '', 'RURI YULIANA', '', '1997-07-27', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(507, '70000232', 'KF-JKT', '70000232', '', '3175095111990009', '', 'SHAVILLA ARYANTO', '', '1999-11-11', 'Perempuan', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(508, '70000203', 'KF-JKT', '70000203', '', '3216065502020013', '', 'SITI ROHMAH CAHAYA NINGSIH', '', '2002-02-15', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(509, '70000207', 'KF-JKT', '70000207', '', '3275117108020003', '', 'SRI DEVI YUNAENI', '', '2002-08-31', 'Perempuan', 'Islam', 19, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(510, '70000216', 'KF-JKT', '70000216', '', '3216185106990005', '', 'SUCI RAHMAWATI', '', '1999-06-11', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(511, '70000208', 'KF-JKT', '70000208', '', '3173035408010002', '', 'TANIA DEA REVIANA', '', '2001-08-14', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(512, '70000219', 'KF-JKT', '70000219', '', '1801176006020001', '', 'TRI SARI HANDAYANI', '', '2002-06-20', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(513, '70000149', 'KF-JKT', '70000149', '', '3175066806970008', '', 'WAHYUNINGSIH', '', '1997-06-28', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(514, '70000150', 'KF-JKT', '70000150', '', '3403086501970001', '', 'WEDARINGTYAS DEWI SITARESMI', '', '1997-01-25', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(515, '70000205', 'KF-JKT', '70000205', '', '3172026006021002', '', 'YUNITA CORNELIA', '', '2002-06-20', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 12, 4, 1, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(516, '60000203', 'KF-JKT', '60000203', '', '3275095910010015', '', 'AFIFAH SAFIRA BILQIS', '', '2001-10-19', 'Perempuan', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(517, '70000156', 'KF-JKT', '70000156', '', '3175036110980002', '', 'DWI UTARI', '', '1998-10-21', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(518, '70000128', 'KF-JKT', '70000128', '', '3175062403000007', '', 'IZAMIL ARDHO IBRAHIM', '', '2000-03-24', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(519, '70000246', 'KF-JKT', '70000246', '', '3301121303980001', '', 'JERY PRIYANTO', '', '1998-03-13', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(520, '70000254', 'KF-JKT', '70000254', '', '3175072804020004', '', 'MOH RAFI ADRIANSYAH', '', '2002-04-28', 'Laki-Laki', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(521, '70000218', 'KF-JKT', '70000218', '', '3205201010970006', '', 'MUHAMAD HUSNI EPENDY', '', '1997-10-10', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(522, '70000257', 'KF-JKT', '70000257', '', '3275022312000010', '', 'MUHAMMAD FATHUR RIZKY', '', '2000-12-23', 'Laki-Laki', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(523, '70000158', 'KF-JKT', '70000158', '', '3175076304010006', '', 'NABILAH AZZAHRA', '', '2001-04-23', 'Perempuan', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(524, '30000149', 'KF-JKT', '30000149', '', '3309025711970002', '', 'NUR KHAYATI', 'Indonesia', '1997-11-17', 'Perempuan', 'Islam', 24, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', 'Indonesia', '', '62', 'penandaanprinting@gmail.com', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-10 13:45:08'),
(525, '70000159', 'KF-JKT', '70000159', '', '3175066705980001', '', 'NURAFIA RAHMA MAULIA', 'Indonesia', '1998-05-27', 'Perempuan', 'Islam', 24, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', 'Indonesia', '', '62', 'bahanbaku20@gmail.com', 42, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 1, '2022-02-05 01:13:17', '2022-02-10 13:43:07'),
(526, '70000221', 'KF-JKT', '70000221', '', '3314104310980006', '', 'OCTAVIA INDAH', '', '1998-10-03', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 42, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(527, '70000270', 'KF-JKT', '70000270', '', '3175075805001003', '', 'RENI RAHMAWATI', '', '2000-05-18', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 43, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(528, '70000260', 'KF-JKT', '70000260', '', '3275020511010023', '', 'RISAF KHALIK S', '', '2001-11-05', 'Laki-Laki', 'Islam', 20, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 42, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(529, '70000161', 'KF-JKT', '70000161', '', '3175071203950003', '', 'RIZKI MAULANA', '', '1995-03-12', 'Laki-Laki', 'Islam', 27, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(530, '70000255', 'KF-JKT', '70000255', '', '3520050704980005', '', 'SIDIQ KURNIAWAN', '', '1998-04-07', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(531, '70000162', 'KF-JKT', '70000162', '', '3328146211980006', '', 'SITI AMALIAH', '', '1998-11-22', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 45, 13, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(532, '50000145', 'KF-JKT', '50000145', '', '3175061210940007', '', 'ABDUL AZIS MUSLIM', '', '1994-10-12', 'Laki-Laki', 'Islam', 27, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(533, '70000163', 'KF-JKT', '70000163', '', '3171032012890003', '', 'ABDUL ROHMAN', '', '1989-12-20', 'Laki-Laki', 'Islam', 32, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(534, '70000298', 'KF-JKT', '70000298', '', '3172052511830001', '', 'ABDURAHMAN ISANUR', '', '1982-11-25', 'Laki-Laki', 'Islam', 39, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(535, '70000164', 'KF-JKT', '70000164', '', '3175062602910017', '', 'ANGGA IRAWAN', '', '1991-02-26', 'Laki-Laki', 'Islam', 31, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(536, '70000165', 'KF-JKT', '70000165', '', '3175062602910017', '', 'ANGGI PRADITIYA', '', '2000-03-27', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(537, '70000166', 'KF-JKT', '70000166', '', '3171082102900001', '', 'ARIF NOVAL PRIBADI', '', '1990-02-21', 'Laki-Laki', 'Islam', 32, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(538, '70000167', 'KF-JKT', '70000167', '', '3302020908950002', '', 'BINGAR AGUS PRAHARI', '', '1989-08-09', 'Laki-Laki', 'Islam', 33, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(539, '70000168', 'KF-JKT', '70000168', '', '3275091712870005', '', 'DENDI SANIM', '', '1989-03-26', 'Laki-Laki', 'Islam', 33, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(540, '70000300', 'KF-JKT', '70000300', '', '3204041909850007', '', 'DIKA FAHRIYAH ANDRISTA', '', '1999-01-13', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(541, '70000170', 'KF-JKT', '70000170', '', '3275010609780007', '', 'DWI SEPTIO ANGGRAITO', '', '1989-03-30', 'Laki-Laki', 'Islam', 33, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00');
INSERT INTO `employees` (`id`, `nip`, `location`, `sap_id`, `parent_nik`, `nik`, `npwp`, `employee_name`, `birth_place`, `birth_date`, `gender`, `religion`, `age`, `employee_status`, `os_name`, `address`, `phone`, `mobile`, `email`, `division_id`, `sub_department_id`, `department_id`, `rank_id`, `location_id`, `user_id`, `sk_number`, `sk_date`, `sk_start_date`, `sk_end_date`, `status`, `overtime`, `direct_spv`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(542, '70000171', 'KF-JKT', '70000171', '', '3175061312950008', '', 'FATUR ROHMAN', '', '1995-12-13', 'Laki-Laki', 'Islam', 26, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(543, '70000194', 'KF-JKT', '70000194', '', '3275015502980009', '', 'FUJI FEBRIANTI', '', '1998-02-15', 'Perempuan', 'Islam', 24, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(544, '70000172', 'KF-JKT', '70000172', '', '3175020912700004', '', 'HADI KUSMOYO', '', '1970-12-09', 'Laki-Laki', 'Islam', 51, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(545, '50000148', 'KF-JKT', '50000148', '', '3175070704981003', '', 'HASAN', '', '1998-04-07', 'Laki-Laki', 'Islam', 24, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(546, '70000248', 'KF-JKT', '70000248', '', '3175020705970008', '', 'INDRA MAULANA SANJAYA', '', '1997-05-07', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(547, '70000174', 'KF-JKT', '70000174', '', '3275081809810018', '', 'KARMITA', '', '1990-09-18', 'Laki-Laki', 'Islam', 31, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(548, '70000176', 'KF-JKT', '70000176', '', '1609011111940004', '', 'LEKO NOPISAL', '', '1987-04-27', 'Laki-Laki', 'Islam', 35, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(549, '70000249', 'KF-JKT', '70000249', '', '36013020010110002', '', 'M. RIFKY RAMADANI', '', '2001-01-20', 'Laki-Laki', 'Islam', 21, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(550, '70000178', 'KF-JKT', '70000178', '', '3201171909910002', '', 'MEKO JASRIAL', '', '1992-07-20', 'Laki-Laki', 'Islam', 30, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(551, '70000284', 'KF-JKT', '70000284', '', '3208113010990004', '', 'MUHAMAD ATHUUR PAJRI', '', '1999-10-30', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(552, '70000179', 'KF-JKT', '70000179', '', '3172023007810009', '', 'MUHAMMAD AGUNG', '', '1981-07-30', 'Laki-Laki', 'Islam', 41, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(553, '50000150', 'KF-JKT', '50000150', '', '3175071510970011', '', 'MUNJIR TAMAN', '', '1991-10-15', 'Laki-Laki', 'Islam', 30, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(554, '70000180', 'KF-JKT', '70000180', '', '3216061811880011', '', 'NOPAN ANDREAS', '', '1988-11-18', 'Laki-Laki', 'Islam', 33, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(555, '70000182', 'KF-JKT', '70000182', '', '3601300603970001', '', 'OMAN ABDUL ROHMAN', '', '1997-03-06', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(556, '70000297', 'KF-JKT', '70000297', '', '3275030405910008', '', 'PURWANTO', '', '1991-05-04', 'Laki-Laki', 'Islam', 31, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(557, '70000183', 'KF-JKT', '70000183', '', '3202175103900001', '', 'PUTRI SILVIANI', '', '1990-03-11', 'Perempuan', 'Islam', 32, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(558, '70000185', 'KF-JKT', '70000185', '', '3301120812960001', '', 'RESTU PUJIONO', '', '1996-12-08', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(559, '70000186', 'KF-JKT', '70000186', '', '3328021103910006', '', 'RONALDO PRAJO', '', '1996-04-21', 'Laki-Laki', 'Islam', 26, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(560, '70000187', 'KF-JKT', '70000187', '', '3175020203950011', '', 'RUDI HIDAYAT', '', '1995-03-02', 'Laki-Laki', 'Islam', 27, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(561, '70000299', 'KF-JKT', '70000299', '', '3276015504950003', '', 'SANTI LESTARI ', '', '1995-04-15', 'Perempuan', 'Islam', 27, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(562, '70000188', 'KF-JKT', '70000188', '', '3203171302940003', '', 'SUNTORO', '', '1999-01-19', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(563, '70000296', 'KF-JKT', '70000296', '', '3175071609880016', '', 'WAHYUDIN', '', '1988-09-16', 'Laki-Laki', 'Islam', 33, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(564, '70000190', 'KF-JKT', '70000190', '', '3204041909850005', '', 'WARJO HARYANTO', '', '1985-09-19', 'Laki-Laki', 'Islam', 36, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(565, '70000177', 'KF-JKT', '70000177', '', '3201222908000002', '', 'YOGI P', '', '1995-03-10', 'Laki-Laki', 'Islam', 27, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(566, '70000294', 'KF-JKT', '70000294', '', '3175075904950009', '', 'ZIHAN AFRIANI', '', '1995-04-19', 'Perempuan', 'Islam', 27, 'Kontrak OS', 'PT. SINERGI INTEGRA SERVICES', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(567, '40000106', 'KF-JKT', '40000106', '', '3175032104781001', '', 'LUKMAN H', '', '1978-04-21', 'Laki-Laki', 'Islam', 44, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(568, '40000119', 'KF-JKT', '40000119', '', '3201210811000005', '', 'ENCEP F', '', '2000-04-07', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(569, '40000104', 'KF-JKT', '40000104', '', '3201222203950001', '', 'ILMAN ALFIANA', '', '1993-06-05', 'Laki-Laki', 'Islam', 29, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(570, '40000114', 'KF-JKT', '40000114', '', '3175024509960007', '', 'TRI RAHMAWATI', '', '1996-09-05', 'Perempuan', 'Islam', 25, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(571, '40000113', 'KF-JKT', '40000113', '', '3175060311980002', '', 'NUR SALAM', '', '1998-11-03', 'Laki-Laki', 'Islam', 23, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(572, '40000116', 'KF-JKT', '40000116', '', '3201390802000006', '', 'KHODAM MAULANA', '', '2000-02-08', 'Laki-Laki', 'Islam', 22, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(573, '40000110', 'KF-JKT', '40000110', '', '3316135010930001', '', 'YATWINASIH', '', '1993-10-10', 'Perempuan', 'Islam', 28, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(574, '40000118', 'KF-JKT', '40000118', '', '3175072910850008', '', 'ANJAR PURNOMO YAHDI', '', '1985-10-29', 'Laki-Laki', 'Islam', 36, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(575, '40000111', 'KF-JKT', '40000111', '', '3329126902920006', '', 'SITI SUPRIYATUN', '', '1992-02-29', 'Perempuan', 'Islam', 30, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(576, '40000115', 'KF-JKT', '40000115', '', '3328056105920002', '', 'SUSILOWATI', '', '1992-05-21', 'Perempuan', 'Islam', 30, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(577, '40000108', 'KF-JKT', '40000108', '', '3175062712860002', '', 'AHMAD SIDIK', '', '1986-12-27', 'Laki-Laki', 'Islam', 35, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(578, '40000101', 'KF-JKT', '40000101', '', '3311061309910003', '', 'EDI SETIAWAN', '', '1991-09-13', 'Laki-Laki', 'Islam', 30, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(579, '40000102', 'KF-JKT', '40000102', '', '3175030905970005', '', 'RIDWAN MARIYANTO', '', '1997-06-09', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(580, '40000103', 'KF-JKT', '40000103', '', '3317081310930001', '', 'ABDUL MUSTOFA', '', '1993-10-03', 'Laki-Laki', 'Islam', 28, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(581, '40000117', 'KF-JKT', '40000117', '', '3175011007840014', '', 'LUTFI PRADIBTIYO', '', '1984-07-10', 'Laki-Laki', 'Islam', 38, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(582, '40000112', 'KF-JKT', '40000112', '', '3201241102870004', '', 'NANDA YUVIKO', '', '1987-02-11', 'Laki-Laki', 'Islam', 35, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(583, '40000109', 'KF-JKT', '40000109', '', '1801055012980003', '', 'TIKA DEWI SUSANTI', '', '1998-12-10', 'Perempuan', 'Islam', 23, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(584, '40000107', 'KF-JKT', '40000107', '', '3175061012880004', '', 'DEPI RUSDIANTO', '', '1996-12-10', 'Laki-Laki', 'Islam', 25, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(585, '40000121', 'KF-JKT', '40000121', '', '3175061704870014', '', 'APRIWANDI', '', '1987-04-17', 'Laki-Laki', 'Islam', 35, 'Kontrak OS', 'PT. ISS INDONESIA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(586, '80000100', 'KF-JKT', '80000100', '', '3175080803690001', '', 'DADANG', '', '1969-03-08', 'Laki-Laki', 'Islam', 53, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(587, '80000115', 'KF-JKT', '80000115', '', '3275021511820039', '', 'MARODI', '', '1982-11-15', 'Laki-Laki', 'Islam', 39, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(588, '80000116', 'KF-JKT', '80000116', '', '3327102511900001', '', 'LUQNI H.', '', '1990-11-25', 'Laki-Laki', 'Islam', 31, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(589, '80000103', 'KF-JKT', '80000103', '', '3175091803800016', '', 'DARMAN', '', '1980-03-18', 'Laki-Laki', 'Islam', 42, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(590, '80000117', 'KF-JKT', '80000117', '', '1801172608810001', '', 'HASMAT SOPYAN', '', '1981-08-25', 'Laki-Laki', 'Islam', 40, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(591, '80000118', 'KF-JKT', '80000118', '', '3271030809870002', '', 'MARTON M', '', '1987-09-08', 'Laki-Laki', 'Islam', 34, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(592, '80000106', 'KF-JKT', '80000106', '', '3175080605760004', '', 'EKO SATRIO', '', '1976-05-06', 'Laki-Laki', 'Islam', 46, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(593, '80000119', 'KF-JKT', '80000119', '', '3175041310840014', '', 'M. KAMIL', '', '1984-10-13', 'Laki-Laki', 'Islam', 37, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(594, '80000108', 'KF-JKT', '80000108', '', '3276073112750004', '', 'CRISTIAN', '', '1975-12-31', 'Laki-Laki', 'Islam', 46, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(595, '80000120', 'KF-JKT', '80000120', '', '3175102301890001', '', 'ILYAS', '', '1989-01-23', 'Laki-Laki', 'Islam', 33, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(596, '90000100', 'KF-JKT', '90000100', '', '3671131504810007', '', 'SARTONO', '', '1981-04-15', 'Laki-Laki', 'Islam', 41, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(597, '80000111', 'KF-JKT', '80000111', '', '1603212509940001', '', 'HARRI SAPUTRA', '', '1994-09-25', 'Laki-Laki', 'Islam', 27, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(598, '80000112', 'KF-JKT', '80000112', '', '3275121803790005', '', 'BUDHI SETIAWAN', '', '1979-03-18', 'Laki-Laki', 'Islam', 43, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(599, '80000113', 'KF-JKT', '80000113', '', '3175025510820008', '', 'SRIANA', '', '1982-10-15', 'Perempuan', 'Islam', 39, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00'),
(600, '80000114', 'KF-JKT', '80000114', '', '3175036910820002', '', 'SISKA DEWI U', '', '1976-08-07', 'Perempuan', 'Islam', 46, 'Kontrak OS', 'PT. SINAR PRAPANCA', '', '', '', '', 34, 12, 3, 9, 1, 0, '', '0000-00-00', '0000-00-00', '0000-00-00', 'ACTIVE', 0, '', 0, 0, '2022-02-05 01:13:17', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `employee_educations`
--

CREATE TABLE `employee_educations` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `level` varchar(30) NOT NULL,
  `major` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `is_technique` varchar(15) NOT NULL,
  `school_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `sttb_number` varchar(20) NOT NULL,
  `sttb_date` date NOT NULL,
  `graduation_year` varchar(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employee_families`
--

CREATE TABLE `employee_families` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `family_name` varchar(30) NOT NULL,
  `relation` enum('Suami','Istri','Putra','Putri','Ayah','Ibu') NOT NULL,
  `birth_date` date NOT NULL,
  `martial_status` enum('Belum Kawin','Kawin','Cerai Hidup','Cerai Mati') NOT NULL,
  `wedding_date` date NOT NULL,
  `description` text NOT NULL,
  `divorce_date` date NOT NULL,
  `profession` varchar(30) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employee_overtimes`
--

CREATE TABLE `employee_overtimes` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `task_id` varchar(30) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `department_id` int(11) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `personil` int(11) NOT NULL,
  `machine_ids` varchar(255) NOT NULL,
  `overtime_date` date NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status_day` varchar(15) NOT NULL,
  `notes` text NOT NULL,
  `revision_note` text NOT NULL,
  `rejection_note` text NOT NULL,
  `makan` int(11) NOT NULL DEFAULT 0,
  `steam` int(11) NOT NULL DEFAULT 0,
  `ahu` int(11) NOT NULL DEFAULT 0,
  `compressor` int(11) NOT NULL DEFAULT 0,
  `pw` int(11) NOT NULL DEFAULT 0,
  `jemputan` int(11) NOT NULL DEFAULT 0,
  `dust_collector` int(11) NOT NULL DEFAULT 0,
  `wfi` int(11) NOT NULL,
  `mechanic` int(11) NOT NULL,
  `electric` int(11) NOT NULL,
  `hnn` int(11) NOT NULL,
  `status` enum('CREATED','CANCELED','CLOSED','REJECTED','PROCESS') NOT NULL,
  `change_time` varchar(50) NOT NULL,
  `apv_spv` enum('APPROVED','REJECTED','CREATED','BY PASS') NOT NULL,
  `apv_spv_nip` varchar(15) NOT NULL,
  `apv_spv_date` datetime NOT NULL,
  `apv_asman` enum('APPROVED','REJECTED','CREATED','BY PASS') NOT NULL,
  `apv_asman_nip` varchar(15) NOT NULL,
  `apv_asman_date` datetime NOT NULL,
  `apv_mgr` enum('APPROVED','REJECTED','CREATED','BY PASS') NOT NULL,
  `apv_mgr_nip` varchar(15) NOT NULL,
  `apv_mgr_date` datetime NOT NULL,
  `apv_head` enum('APPROVED','REJECTED','CREATED','BY PASS') NOT NULL,
  `apv_head_nip` varchar(15) NOT NULL,
  `apv_head_date` datetime NOT NULL,
  `overtime_review` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_overtimes`
--

INSERT INTO `employee_overtimes` (`id`, `location`, `task_id`, `ref`, `department_id`, `sub_department_id`, `division_id`, `personil`, `machine_ids`, `overtime_date`, `start_date`, `end_date`, `status_day`, `notes`, `revision_note`, `rejection_note`, `makan`, `steam`, `ahu`, `compressor`, `pw`, `jemputan`, `dust_collector`, `wfi`, `mechanic`, `electric`, `hnn`, `status`, `change_time`, `apv_spv`, `apv_spv_nip`, `apv_spv_date`, `apv_asman`, `apv_asman_nip`, `apv_asman_date`, `apv_mgr`, `apv_mgr_nip`, `apv_mgr_date`, `apv_head`, `apv_head_nip`, `apv_head_date`, `overtime_review`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', '001/OT/KF-JKT/II/2022', '', 3, 12, 34, 1, '', '2022-02-19', '2022-02-19 07:30:00', '2022-02-19 16:00:00', 'Hari Libur', 'Tes', '', '', 1, 0, 0, 0, 0, 0, 0, 0, 9, 10, 11, 'CLOSED', '', 'APPROVED', '19710621A', '2022-02-11 10:17:29', 'APPROVED', '19690314A', '2022-02-11 10:18:07', 'BY PASS', '-', '2022-02-11 10:16:38', 'APPROVED', '19880210A', '2022-02-11 10:19:05', '', 1, 20, '2022-02-11 10:16:18', '2022-02-11 10:19:05'),
(2, 'KF-JKT', '002/OT/KF-JKT/II/2022', '001/OT/KF-JKT/II/2022', 1, 5, 0, 1, '', '2022-02-19', '2022-02-19 07:30:00', '2022-02-19 19:30:00', 'Hari Libur', 'Tes', '', '', 1, 0, 0, 0, 0, 0, 0, 0, 9, 10, 11, 'CLOSED', 'Revised By Administrator @Jumat, 11 Februari 2022 ', 'BY PASS', '-', '2022-02-11 10:21:29', 'APPROVED', '19860424A', '2022-02-11 10:30:57', 'APPROVED', '19870410B', '2022-02-11 10:32:31', 'APPROVED', '19880210A', '2022-02-11 10:33:21', '', 369, 20, '2022-02-11 10:20:04', '2022-02-11 10:33:21'),
(3, 'KF-JKT', '003/OT/KF-JKT/II/2022', '-', 1, 5, 18, 1, '', '2022-02-13', '2022-02-13 07:30:00', '2022-02-13 21:00:00', 'Hari Libur', 'Tes', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'CREATED', '', 'CREATED', '', '0000-00-00 00:00:00', 'CREATED', '', '0000-00-00 00:00:00', 'CREATED', '', '0000-00-00 00:00:00', 'CREATED', '', '0000-00-00 00:00:00', '', 1, 1, '2022-02-11 13:18:47', '2022-02-11 13:18:47');

-- --------------------------------------------------------

--
-- Table structure for table `employee_overtimes_detail`
--

CREATE TABLE `employee_overtimes_detail` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `task_id` varchar(30) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `emp_task_id` varchar(30) NOT NULL,
  `department_id` int(11) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `ovt_sub_department` int(11) NOT NULL,
  `ovt_division` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `machine_1` int(11) NOT NULL,
  `machine_2` int(11) NOT NULL,
  `requirements` varchar(255) NOT NULL,
  `overtime_date` date NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status_day` varchar(15) NOT NULL,
  `effective_hour` double(5,2) NOT NULL,
  `break_hour` double(5,2) NOT NULL,
  `real_hour` double(5,2) NOT NULL,
  `overtime_hour` double(5,2) NOT NULL,
  `premi_overtime` double(10,2) NOT NULL,
  `overtime_value` double(10,2) NOT NULL,
  `meal` double(10,2) NOT NULL,
  `total_meal` int(11) NOT NULL,
  `notes` text NOT NULL,
  `status` enum('CREATED','CANCELED','CLOSED','REJECTED','PROCESS') NOT NULL,
  `payment_status` enum('VERIFIED','PENDING','CREATED') NOT NULL DEFAULT 'CREATED',
  `payment_status_by` varchar(15) NOT NULL,
  `status_by` varchar(15) NOT NULL,
  `change_time` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_overtimes_detail`
--

INSERT INTO `employee_overtimes_detail` (`id`, `location`, `task_id`, `emp_id`, `emp_task_id`, `department_id`, `sub_department_id`, `ovt_sub_department`, `ovt_division`, `division_id`, `machine_1`, `machine_2`, `requirements`, `overtime_date`, `start_date`, `end_date`, `status_day`, `effective_hour`, `break_hour`, `real_hour`, `overtime_hour`, `premi_overtime`, `overtime_value`, `meal`, `total_meal`, `notes`, `status`, `payment_status`, `payment_status_by`, `status_by`, `change_time`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', '001/OT/KF-JKT/II/2022', 532, '001/OT-EMP/KF-JKT/II/2022', 3, 12, 12, 34, 34, 0, 0, '-', '2022-02-19', '2022-02-19 07:30:00', '2022-02-19 16:00:00', 'Hari Libur', 8.50, 1.00, 7.50, 14.50, 0.00, 0.00, 20000.00, 1, 'Tes', 'CLOSED', 'CREATED', '', '', 0, 1, 20, '2022-02-11 10:16:34', '2022-02-11 10:19:05'),
(2, 'KF-JKT', '002/OT/KF-JKT/II/2022', 431, '002/OT-EMP/KF-JKT/II/2022', 1, 5, 5, 0, 18, 0, 0, 'Hardware & Network', '2022-02-19', '2022-02-19 07:30:00', '2022-02-19 16:00:00', 'Hari Libur', 8.50, 1.00, 7.50, 14.50, 26831.53, 389057.18, 20000.00, 1, 'Tes', 'CLOSED', 'VERIFIED', '9999', '9999', 1, 1, 1, '2022-02-11 10:21:01', '2022-02-11 13:39:40'),
(3, 'KF-JKT', '003/OT/KF-JKT/II/2022', 432, '003/OT-EMP/KF-JKT/II/2022', 1, 5, 5, 18, 18, 0, 0, '-', '2022-02-13', '2022-02-13 07:30:00', '2022-02-13 21:00:00', 'Hari Libur', 13.50, 2.00, 11.50, 31.00, 26831.53, 831777.43, 40000.00, 2, 'Tes', 'CREATED', 'CREATED', '', '', 0, 1, 1, '2022-02-11 13:19:11', '2022-02-11 13:19:11');

-- --------------------------------------------------------

--
-- Table structure for table `employee_pins`
--

CREATE TABLE `employee_pins` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `pin` varchar(6) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_pins`
--

INSERT INTO `employee_pins` (`id`, `location`, `emp_id`, `pin`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(6, 'KF-JKT', 18, '528563', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(7, 'KF-JKT', 19, '850078', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(8, 'KF-JKT', 20, '003190', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(9, 'KF-JKT', 22, '780329', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(10, 'KF-JKT', 23, '651274', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(11, 'KF-JKT', 25, '051341', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(12, 'KF-JKT', 33, '036914', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(13, 'KF-JKT', 34, '945385', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(14, 'KF-JKT', 35, '625446', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(15, 'KF-JKT', 36, '263943', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(16, 'KF-JKT', 39, '917889', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(17, 'KF-JKT', 40, '944483', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(18, 'KF-JKT', 53, '278510', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(19, 'KF-JKT', 54, '377873', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(20, 'KF-JKT', 58, '171363', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(21, 'KF-JKT', 61, '552577', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(22, 'KF-JKT', 84, '424659', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(23, 'KF-JKT', 86, '341672', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(24, 'KF-JKT', 87, '532934', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(25, 'KF-JKT', 88, '158149', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(26, 'KF-JKT', 90, '113052', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(27, 'KF-JKT', 91, '667437', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(28, 'KF-JKT', 92, '834664', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(29, 'KF-JKT', 100, '437684', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(30, 'KF-JKT', 103, '890775', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(31, 'KF-JKT', 107, '257100', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(32, 'KF-JKT', 111, '070203', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(33, 'KF-JKT', 113, '106231', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(34, 'KF-JKT', 119, '438425', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(35, 'KF-JKT', 125, '374209', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(36, 'KF-JKT', 147, '175353', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(37, 'KF-JKT', 149, '017778', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(38, 'KF-JKT', 155, '547155', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(39, 'KF-JKT', 156, '537930', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(40, 'KF-JKT', 157, '319590', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(41, 'KF-JKT', 164, '301108', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(42, 'KF-JKT', 166, '108099', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(43, 'KF-JKT', 168, '209539', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(44, 'KF-JKT', 172, '389974', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(45, 'KF-JKT', 173, '127809', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(46, 'KF-JKT', 176, '343317', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(47, 'KF-JKT', 177, '346298', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(48, 'KF-JKT', 178, '072530', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(49, 'KF-JKT', 179, '226093', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(50, 'KF-JKT', 180, '367733', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(51, 'KF-JKT', 181, '655894', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(52, 'KF-JKT', 184, '968744', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(53, 'KF-JKT', 185, '036660', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(54, 'KF-JKT', 186, '828660', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(55, 'KF-JKT', 188, '462409', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(56, 'KF-JKT', 190, '979197', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(57, 'KF-JKT', 191, '713172', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(58, 'KF-JKT', 193, '181620', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(59, 'KF-JKT', 200, '453992', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(60, 'KF-JKT', 201, '444555', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-10 21:54:31'),
(61, 'KF-JKT', 208, '869550', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(62, 'KF-JKT', 212, '134785', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(63, 'KF-JKT', 214, '451186', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(64, 'KF-JKT', 215, '857572', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(65, 'KF-JKT', 216, '023315', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(66, 'KF-JKT', 217, '435877', 'ACTIVE', 1, 1, '2022-02-09 12:20:54', '2022-02-09 12:20:54'),
(67, 'KF-JKT', 196, '123456', 'ACTIVE', 1, 1, '2022-02-09 12:42:31', '2022-02-09 12:42:31'),
(68, 'KF-JKT', 369, '333444', 'ACTIVE', 1, 1, '2022-02-11 09:28:13', '2022-02-11 09:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `employee_ranks`
--

CREATE TABLE `employee_ranks` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  `sk_number` varchar(25) NOT NULL,
  `sk_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('ACTIVE','NONACTIVE','CURRENT') NOT NULL DEFAULT 'NONACTIVE',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_ranks`
--

INSERT INTO `employee_ranks` (`id`, `emp_id`, `department_id`, `sub_department_id`, `division_id`, `rank_id`, `sk_number`, `sk_date`, `start_date`, `end_date`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 191, 3, 14, 0, 3, 'SK1', '2022-02-01', '2022-02-01', '2022-12-31', 'CURRENT', 1, 1, '2022-02-07 14:53:51', '2022-02-07 14:53:51'),
(2, 191, 1, 1, 0, 3, 'SK2', '2022-02-01', '2022-02-01', '2022-12-31', 'ACTIVE', 1, 1, '2022-02-07 14:54:17', '2022-02-07 14:54:17'),
(6, 36, 3, 11, 0, 4, '-', '2022-02-01', '2022-02-01', '2030-02-28', 'ACTIVE', 1, 1, '2022-02-09 11:49:36', '2022-02-09 11:50:28'),
(7, 88, 2, 6, 25, 6, '-', '2022-02-01', '2022-02-01', '2030-02-28', 'ACTIVE', 1, 1, '2022-02-09 11:53:27', '2022-02-09 11:56:38'),
(8, 149, 1, 2, 5, 6, '-', '2022-02-01', '2022-02-01', '2030-02-28', 'ACTIVE', 1, 1, '2022-02-09 12:01:49', '2022-02-09 12:01:56'),
(9, 158, 1, 3, 8, 6, '-', '2022-02-01', '2022-02-01', '2030-02-28', 'ACTIVE', 1, 1, '2022-02-09 12:02:46', '2022-02-09 12:02:51'),
(10, 196, 1, 5, 18, 6, '-', '2022-02-01', '2022-02-01', '2030-02-28', 'ACTIVE', 1, 1, '2022-02-09 12:03:35', '2022-02-09 12:03:42'),
(11, 138, 1, 1, 3, 6, '-', '2022-02-01', '2022-02-01', '2030-02-28', 'ACTIVE', 1, 1, '2022-02-09 12:04:39', '2022-02-09 12:04:45'),
(12, 86, 2, 6, 0, 4, '-', '2022-02-01', '2022-02-01', '2030-02-28', 'ACTIVE', 1, 1, '2022-02-09 12:05:31', '2022-02-09 12:05:38');

-- --------------------------------------------------------

--
-- Table structure for table `employee_sallary`
--

CREATE TABLE `employee_sallary` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `sap_id` varchar(15) NOT NULL,
  `basic_sallary` double(10,2) NOT NULL,
  `total_sallary` double(10,2) NOT NULL,
  `premi_overtime` double(10,2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_sallary`
--

INSERT INTO `employee_sallary` (`id`, `emp_id`, `sap_id`, `basic_sallary`, `total_sallary`, `premi_overtime`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 18, '10000106', 11264000.00, 11264000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(2, 19, '10000113', 10452500.00, 10452500.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(3, 20, '10000119', 13017000.00, 13017000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(4, 21, '10000507', 8635000.00, 8635000.00, 49913.29, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(5, 22, '10000195', 7305000.00, 7305000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(6, 23, '10000057', 17257000.00, 17257000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(7, 24, '10001206', 5223000.00, 5223000.00, 30190.75, 1, 1, '2022-02-05 01:28:19', '2022-02-10 21:47:14'),
(8, 25, '10000483', 7001000.00, 7001000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-10 21:48:18'),
(9, 26, '10001188', 5170000.00, 5170000.00, 29884.39, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(10, 27, '10001205', 5223000.00, 5223000.00, 30190.75, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(11, 28, '10000495', 6075000.00, 6075000.00, 35115.61, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(12, 29, '10000484', 6455000.00, 6455000.00, 37312.14, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(13, 30, '10001191', 5041000.00, 5041000.00, 29138.73, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(14, 31, '10001193', 5041000.00, 5041000.00, 29138.73, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(15, 32, '10000479', 5463000.00, 5463000.00, 31578.03, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(16, 33, '10000480', 6385000.00, 6385000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(17, 34, '10000214', 9682000.00, 9682000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(18, 35, '10000639', 6182000.00, 6182000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(19, 36, '10000222', 9037000.00, 9037000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(20, 37, '10000493', 9917000.00, 9917000.00, 57323.70, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(21, 38, '10000488', 5568000.00, 5568000.00, 32184.97, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(22, 39, '10000492', 6285000.00, 6285000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(23, 40, '10000190', 11155000.00, 11155000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(24, 41, '10000496', 6075000.00, 6075000.00, 35115.61, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(25, 42, '10000486', 8777000.00, 8777000.00, 50734.10, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(26, 43, '10001202', 5484000.00, 5484000.00, 31699.42, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(27, 44, '10000491', 5568000.00, 5568000.00, 32184.97, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(28, 45, '10000482', 5568000.00, 5568000.00, 32184.97, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(29, 46, '10000487', 8364000.00, 8364000.00, 48346.82, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(30, 47, '10001270', 4765000.00, 4765000.00, 27543.35, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(31, 48, '10000605', 6852000.00, 6852000.00, 39606.94, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(32, 49, '10000606', 6669000.00, 6669000.00, 38549.13, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(33, 50, '10000595', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(34, 51, '10000607', 6852000.00, 6852000.00, 39606.94, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(35, 52, '10000588', 8933000.00, 8933000.00, 51635.84, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(36, 53, '10000209', 11113000.00, 11113000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(37, 54, '10000216', 8085000.00, 8085000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(38, 55, '10000596', 8815000.00, 8815000.00, 50953.76, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(39, 56, '10000597', 6165000.00, 6165000.00, 35635.84, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(40, 57, '10000635', 8695000.00, 8695000.00, 50260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(41, 58, '10000066', 9649000.00, 9649000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(42, 59, '10000575', 8592000.00, 8592000.00, 49664.74, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(43, 60, '10001121', 8057000.00, 8057000.00, 46572.25, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(44, 61, '10000211', 7497000.00, 7497000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(45, 62, '10000609', 6658000.00, 6658000.00, 38485.55, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(46, 63, '10001274', 4638000.00, 4638000.00, 26809.25, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(47, 64, '10000603', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(48, 65, '10000598', 9382000.00, 9382000.00, 54231.21, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(49, 66, '10000623', 8588000.00, 8588000.00, 49641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(50, 67, '10000610', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(51, 68, '10000584', 8771000.00, 8771000.00, 50699.42, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(52, 69, '10000600', 6705000.00, 6705000.00, 38757.23, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(53, 70, '10001282', 5092000.00, 5092000.00, 29433.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(54, 71, '10001750', 4788000.00, 4788000.00, 27676.30, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(55, 72, '10000611', 6748000.00, 6748000.00, 39005.78, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(56, 73, '10001109', 7595000.00, 7595000.00, 43901.73, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(57, 74, '10000613', 6799000.00, 6799000.00, 39300.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(58, 75, '10000627', 8456000.00, 8456000.00, 48878.61, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(59, 76, '10000628', 8445000.00, 8445000.00, 48815.03, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(60, 77, '10001113', 8295000.00, 8295000.00, 47947.98, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(61, 78, '10000619', 8475000.00, 8475000.00, 48988.44, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(62, 79, '10000550', 6886000.00, 6886000.00, 39803.47, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(63, 80, '10000631', 9066000.00, 9066000.00, 52404.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(64, 81, '10000576', 6909000.00, 6909000.00, 39936.42, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(65, 82, '10000601', 6910000.00, 6910000.00, 39942.20, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(66, 83, '10000614', 6849000.00, 6849000.00, 39589.60, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(67, 84, '10001833', 6348000.00, 6348000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(68, 85, '10000577', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(69, 86, '10001731', 6896000.00, 6896000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(70, 87, '10000638', 6456000.00, 6456000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(71, 88, '10000224', 10656000.00, 10656000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(72, 89, '10001313', 4644000.00, 4644000.00, 26843.93, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(73, 90, '10000230', 9838000.00, 9838000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(74, 91, '10000067', 13420000.00, 13420000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(75, 92, '10000589', 6293000.00, 6293000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(76, 93, '10001326', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(77, 94, '10000651', 8894000.00, 8894000.00, 51410.40, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(78, 95, '10000615', 6691000.00, 6691000.00, 38676.30, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(79, 96, '10000571', 6910000.00, 6910000.00, 39942.20, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(80, 97, '10001328', 5340000.00, 5340000.00, 30867.05, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(81, 98, '10001323', 5041000.00, 5041000.00, 29138.73, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(82, 99, '10001334', 5223000.00, 5223000.00, 30190.75, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(83, 100, '10000212', 11460000.00, 11460000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(84, 101, '10000653', 9183000.00, 9183000.00, 53080.92, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(85, 102, '10000604', 6799000.00, 6799000.00, 39300.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(86, 103, '10000229', 11929000.00, 11929000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(87, 104, '10001281', 5223000.00, 5223000.00, 30190.75, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(88, 105, '10000599', 6866000.00, 6866000.00, 39687.86, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(89, 106, '10000548', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(90, 107, '10000215', 11459000.00, 11459000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(91, 108, '10000515', 8618000.00, 8618000.00, 49815.03, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(92, 109, '10001155', 8481000.00, 8481000.00, 49023.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(93, 110, '10000647', 8802000.00, 8802000.00, 50878.61, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(94, 111, '10000194', 11956000.00, 11956000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(95, 112, '10001141', 8253000.00, 8253000.00, 47705.20, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(96, 113, '10000110', 12774000.00, 12774000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(97, 114, '10000650', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(98, 115, '10000661', 8236000.00, 8236000.00, 47606.94, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(99, 116, '10000654', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(100, 117, '10000663', 8819000.00, 8819000.00, 50976.88, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(101, 118, '10000602', 8158000.00, 8158000.00, 47156.07, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(102, 119, '10000207', 11577000.00, 11577000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(103, 120, '10001228', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(104, 121, '10000532', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(105, 122, '10001327', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(106, 123, '10001225', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(107, 124, '10001226', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(108, 125, '10000213', 11100000.00, 11100000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(109, 126, '10000527', 8438000.00, 8438000.00, 48774.57, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(110, 127, '10000540', 8299000.00, 8299000.00, 47971.10, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(111, 128, '10000541', 6751000.00, 6751000.00, 39023.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(112, 129, '10001230', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(113, 130, '10000534', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(114, 131, '10000535', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(115, 132, '10000537', 8596000.00, 8596000.00, 49687.86, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(116, 133, '10000542', 6162000.00, 6162000.00, 35618.50, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(117, 134, '10000544', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(118, 135, '10000539', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(119, 136, '10000545', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(120, 137, '10000546', 6799000.00, 6799000.00, 39300.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(121, 138, '10001325', 5340000.00, 5340000.00, 30867.05, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(122, 139, '10000553', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(123, 140, '10001214', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(124, 141, '10000558', 6910000.00, 6910000.00, 39942.20, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(125, 142, '10000549', 6867000.00, 6867000.00, 39693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(126, 143, '10001644', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(127, 144, '10000554', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(128, 145, '10000555', 8819000.00, 8819000.00, 50976.88, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(129, 146, '10000559', 8866000.00, 8866000.00, 51248.55, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(130, 147, '10001666', 6855000.00, 6855000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(131, 148, '10000551', 8377000.00, 8377000.00, 48421.97, 1, 1, '2022-02-05 01:28:19', '2022-02-07 14:46:13'),
(132, 149, '10000233', 7341000.00, 7341000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(133, 150, '10001250', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(134, 151, '10000561', 6165000.00, 6165000.00, 35635.84, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(135, 152, '10000562', 6726000.00, 6726000.00, 38878.61, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(136, 153, '10001331', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(137, 154, '10000563', 6756000.00, 6756000.00, 39052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(138, 155, '10000204', 11198000.00, 11198000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(139, 156, '10001837', 6348000.00, 6348000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(140, 157, '10000291', 8004000.00, 8004000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(141, 158, '10000560', 5901000.00, 5901000.00, 34109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(142, 159, '10001273', 5041000.00, 5041000.00, 29138.73, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(143, 160, '10001237', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(144, 161, '10001231', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(145, 162, '10001275', 5041000.00, 5041000.00, 29138.73, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(146, 163, '10000536', 6915000.00, 6915000.00, 39971.10, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(147, 164, '10000205', 12135000.00, 12135000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(148, 165, '10000543', 9183000.00, 9183000.00, 53080.92, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(149, 166, '10000977', 6359000.00, 6359000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-10 21:48:18'),
(150, 167, '10000509', 6906000.00, 6906000.00, 39919.08, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(151, 168, '10000646', 7659000.00, 7659000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(152, 169, '10000510', 6158000.00, 6158000.00, 35595.38, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(153, 170, '10000573', 6913000.00, 6913000.00, 39959.54, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(154, 171, '10000518', 5728000.00, 5728000.00, 33109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(155, 172, '10000519', 7559000.00, 7559000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(156, 173, '10000202', 11315000.00, 11315000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(157, 174, '10000517', 9239000.00, 9239000.00, 53404.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(158, 175, '10000489', 9669000.00, 9669000.00, 55890.17, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(159, 176, '10001848', 6348000.00, 6348000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(160, 177, '10001745', 6896000.00, 6896000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(161, 178, '10000644', 6219000.00, 6219000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(162, 179, '10002520', 5863000.00, 5863000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(163, 180, '10001297', 5832000.00, 5832000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(164, 181, '10000641', 7157000.00, 7157000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(165, 182, '10000645', 5778000.00, 5778000.00, 33398.84, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(166, 183, '10001301', 5223000.00, 5223000.00, 30190.75, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(167, 184, '10002646', 5863000.00, 5863000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(168, 185, '10001839', 6348000.00, 6348000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(169, 186, '10002647', 5863000.00, 5863000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(170, 187, '10001295', 5340000.00, 5340000.00, 30867.05, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(171, 188, '10002522', 5863000.00, 5863000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(172, 189, '10001308', 5041000.00, 5041000.00, 29138.73, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(173, 190, '10001291', 8300000.00, 8300000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(174, 191, '10000064', 9553000.00, 9553000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(175, 192, '10001186', 6204000.00, 6204000.00, 35861.27, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(176, 193, '10000221', 10235000.00, 10235000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(177, 194, '10000500', 6849000.00, 6849000.00, 39589.60, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(178, 195, '10001105', 6323000.00, 6323000.00, 36549.13, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(179, 196, '10001210', 5092000.00, 5092000.00, 29433.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(180, 197, '10001216', 5199000.00, 5199000.00, 30052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(181, 198, '10001211', 5170000.00, 5170000.00, 29884.39, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(182, 199, '10001213', 5092000.00, 5092000.00, 29433.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(183, 200, '10001946', 5679000.00, 5679000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(184, 201, '10000192', 8583000.00, 8583000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(185, 202, '10001215', 5092000.00, 5092000.00, 29433.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(186, 203, '10000497', 8392000.00, 8392000.00, 48508.67, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(187, 204, '10000505', 6008000.00, 6008000.00, 34728.32, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(188, 205, '10000501', 6721000.00, 6721000.00, 38849.71, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(189, 206, '10000506', 8659000.00, 8659000.00, 50052.02, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(190, 207, '10000502', 8776000.00, 8776000.00, 50728.32, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(191, 208, '10000498', 9837000.00, 9837000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(192, 209, '10000499', 8260000.00, 8260000.00, 47745.66, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(193, 210, '10000503', 8861000.00, 8861000.00, 51219.65, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(194, 211, '10001770', 5170000.00, 5170000.00, 29884.39, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(195, 212, '10000193', 7225000.00, 7225000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-07 11:51:34'),
(196, 213, '10000593', 8260000.00, 8260000.00, 47745.66, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(197, 214, '10000196', 7760000.00, 7760000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(198, 215, '10000363', 9226000.00, 9226000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(199, 216, '10000152', 10593000.00, 10593000.00, 80000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(200, 217, '10000523', 9947000.00, 9947000.00, 70000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(201, 218, '10002577', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(202, 219, '10001824', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(203, 220, '10002708', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(204, 221, '10002586', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(205, 222, '10002587', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(206, 223, '10002588', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(207, 224, '10001764', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(208, 225, '10002424', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(209, 226, '10002346', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(210, 227, '10002569', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(211, 228, '10002775', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(212, 229, '10002779', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(213, 230, '10002778', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(214, 231, '10002570', 4417000.00, 4417000.00, 25531.79, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(215, 232, '10002330', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(216, 233, '10001821', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(217, 234, '10002766', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(218, 235, '10002347', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(219, 236, '10002348', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(220, 237, '10002590', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(221, 238, '10002776', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(222, 239, '10002589', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(223, 240, '10002425', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(224, 241, '10002539', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(225, 242, '10002349', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(226, 243, '10002777', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(227, 244, '10002765', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(228, 245, '10001732', 4816000.00, 4816000.00, 27838.15, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(229, 246, '10002458', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(230, 247, '10002543', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(231, 248, '10001771', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(232, 249, '10001804', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(233, 250, '10001730', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(234, 251, '10002422', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(235, 252, '10002761', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(236, 253, '10002571', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(237, 254, '10001829', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(238, 255, '10002764', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(239, 256, '10001772', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(240, 257, '10001795', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(241, 258, '10002423', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(242, 259, '10001785', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(243, 260, '10002591', 4417000.00, 4417000.00, 25531.79, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(244, 261, '10002434', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(245, 262, '10002707', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(246, 263, '10001713', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(247, 264, '10002550', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(248, 265, '10001803', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(249, 266, '10002881', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(250, 267, '10002549', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(251, 268, '10002353', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(252, 269, '10002354', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(253, 270, '10001657', 4816000.00, 4816000.00, 27838.15, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(254, 271, '10001808', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(255, 272, '10002762', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(256, 273, '10002495', 5819000.00, 5819000.00, 33635.84, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(257, 274, '10002455', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(258, 275, '10002542', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(259, 276, '10001797', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(260, 277, '10001796', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(261, 278, '10002456', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(262, 279, '10002704', 4417000.00, 4417000.00, 25531.79, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(263, 280, '10002638', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(264, 281, '10002327', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(265, 282, '10002538', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(266, 283, '10002767', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(267, 284, '10002540', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(268, 285, '10002768', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(269, 286, '10002454', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(270, 287, '10002572', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(271, 288, '10002329', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(272, 289, '10002435', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(273, 290, '10001756', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(274, 291, '10002541', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(275, 292, '10002580', 4417000.00, 4417000.00, 25531.79, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(276, 293, '10002328', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(277, 294, '10001680', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(278, 295, '10002341', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(279, 296, '10002436', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-10 21:47:14'),
(280, 297, '10002334', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(281, 298, '10002437', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(282, 299, '10001782', 4816000.00, 4816000.00, 27838.15, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(283, 300, '10002592', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(284, 301, '10002335', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(285, 302, '10002598', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(286, 303, '10002336', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(287, 304, '10002582', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(288, 305, '10002337', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(289, 306, '10002705', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(290, 307, '10002338', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(291, 308, '10001817', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(292, 309, '10002418', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(293, 310, '10002419', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(294, 311, '10002438', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(295, 312, '10002339', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(296, 313, '10001749', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(297, 314, '10002439', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(298, 315, '10002440', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(299, 316, '10002575', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(300, 317, '10002599', 4417000.00, 4417000.00, 25531.79, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(301, 318, '10002420', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(302, 319, '10002340', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(303, 320, '10002576', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(304, 321, '10001674', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(305, 322, '10002593', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(306, 323, '10002573', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(307, 324, '10002574', 4417000.00, 4417000.00, 25531.79, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(308, 325, '10002395', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(309, 326, '10002396', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(310, 327, '10002594', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(311, 328, '10002397', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(312, 329, '10002398', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(313, 330, '10002399', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(314, 331, '10002400', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(315, 332, '10001650', 4816000.00, 4816000.00, 27838.15, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(316, 333, '10002401', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(317, 334, '10001719', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(318, 335, '10002402', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(319, 336, '10001738', 4816000.00, 4816000.00, 27838.15, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(320, 337, '10001788', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(321, 338, '10002403', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(322, 339, '10002404', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(323, 340, '10002406', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(324, 341, '10002600', 4417000.00, 4417000.00, 25531.79, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(325, 342, '10002548', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(326, 343, '10002547', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(327, 344, '10002578', 5043000.00, 5043000.00, 29150.29, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(328, 345, '10002326', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(329, 346, '10002568', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(330, 347, '10002342', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(331, 348, '10002426', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(332, 349, '10002441', 4816000.00, 4816000.00, 27838.15, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(333, 350, '10002772', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(334, 351, '10001780', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(335, 352, '10001681', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(336, 353, '10002763', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(337, 354, '10002692', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(338, 355, '10002770', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(339, 356, '10001734', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(340, 357, '10002394', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(341, 358, '10002602', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(342, 359, '10002343', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(343, 360, '10002677', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(344, 361, '10002457', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(345, 362, '10002427', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(346, 363, '10002676', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(347, 364, '10002702', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(348, 365, '10002546', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(349, 366, '10002703', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(350, 367, '10002350', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(351, 368, '10002701', 4618000.00, 4618000.00, 26693.64, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(352, 369, '10001773', 4609000.00, 4609000.00, 26641.62, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(353, 370, '10002771', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(354, 371, '10002545', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(355, 372, '10002700', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(356, 373, '10002351', 4718000.00, 4718000.00, 27271.68, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(357, 374, '10002544', 4716000.00, 4716000.00, 27260.12, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(358, 375, '10002558', 5043000.00, 5043000.00, 29150.29, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(359, 376, '10002780', 5287000.00, 5287000.00, 30560.69, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(360, 377, '10002325', 4517000.00, 4517000.00, 26109.83, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(361, 378, '10002706', 4509000.00, 4509000.00, 26063.58, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(362, 379, '10002579', 4417000.00, 4417000.00, 25531.79, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(363, 380, '30000151', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(364, 381, '30000150', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(365, 382, '50000173', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(366, 383, '50000149', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(367, 384, '60000100', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(368, 385, '60000195', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(369, 386, '60000101', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(370, 387, '60000192', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(371, 388, '60000103', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(372, 389, '60000209', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(373, 390, '60000184', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(374, 391, '60000194', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(375, 392, '60000202', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(376, 393, '60000163', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(377, 394, '60000240', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(378, 395, '60000104', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(379, 396, '60000241', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(380, 397, '60000106', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(381, 398, '10002239', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(382, 399, '60000137', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(383, 400, '10002242', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(384, 401, '60000204', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(385, 402, '60000110', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(386, 403, '10002245', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(387, 404, '10002244', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(388, 405, '60000113', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(389, 406, '60000114', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(390, 407, '60000115', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(391, 408, '60000208', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(392, 409, '60000118', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(393, 410, '60000119', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(394, 411, '60000121', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(395, 412, '60000122', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(396, 413, '60000210', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(397, 414, '60000138', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(398, 415, '60000139', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(399, 416, '60000140', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(400, 417, '60000142', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(401, 418, '60000199', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(402, 419, '60000198', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(403, 420, '60000144', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(404, 421, '60000242', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(405, 422, '60000193', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(406, 423, '60000145', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(407, 424, '60000146', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(408, 425, '60000196', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(409, 426, '60000148', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(410, 427, '60000243', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(411, 428, '60000201', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(412, 429, '60000151', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(413, 430, '60000152', 7785000.00, 7785000.00, 45000.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(414, 431, '60000244', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(415, 432, '60000153', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(416, 433, '60000245', 5597069.00, 5597069.00, 32353.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(417, 434, '60000211', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(418, 435, '60000154', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(419, 436, '60000200', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(420, 437, '60000156', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(421, 438, '70000169', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(422, 439, '60000158', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(423, 440, '50000153', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(424, 441, '60000159', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(425, 442, '60000160', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(426, 443, '70000215', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(427, 444, '70000100', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(428, 445, '70000214', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(429, 446, '70000195', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(430, 447, '70000102', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(431, 448, '70000247', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(432, 449, '70000222', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(433, 450, '70000290', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(434, 451, '70000241', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(435, 452, '70000103', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(436, 453, '70000191', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(437, 454, '70000104', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(438, 455, '70000236', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(439, 456, '70000225', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(440, 457, '70000197', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(441, 458, '70000106', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(442, 459, '70000234', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(443, 460, '70000107', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(444, 461, '70000291', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(445, 462, '70000223', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(446, 463, '70000110', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(447, 464, '70000111', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(448, 465, '70000112', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(449, 466, '70000220', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(450, 467, '70000228', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(451, 468, '70000288', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(452, 469, '70000114', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(453, 470, '70000115', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(454, 471, '70000224', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(455, 472, '70000116', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(456, 473, '70000237', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(457, 474, '70000292', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(458, 475, '70000235', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(459, 476, '70000286', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(460, 477, '70000285', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(461, 478, '70000293', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(462, 479, '70000119', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19');
INSERT INTO `employee_sallary` (`id`, `emp_id`, `sap_id`, `basic_sallary`, `total_sallary`, `premi_overtime`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(463, 480, '70000229', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(464, 481, '70000120', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(465, 482, '70000226', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(466, 483, '70000245', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(467, 484, '70000242', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(468, 485, '70000124', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(469, 486, '70000289', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(470, 487, '70000240', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(471, 488, '70000217', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(472, 489, '70000230', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(473, 490, '70000192', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(474, 491, '70000131', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(475, 492, '70000244', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(476, 493, '70000209', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(477, 494, '70000210', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(478, 495, '70000206', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(479, 496, '70000204', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(480, 497, '70000137', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(481, 498, '70000231', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(482, 499, '70000193', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(483, 500, '70000138', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(484, 501, '70000139', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(485, 502, '70000227', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(486, 503, '70000141', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(487, 504, '70000213', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(488, 505, '70000212', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(489, 506, '70000202', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(490, 507, '70000232', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(491, 508, '70000203', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(492, 509, '70000207', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(493, 510, '70000216', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(494, 511, '70000208', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(495, 512, '70000219', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(496, 513, '70000149', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(497, 514, '70000150', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(498, 515, '70000205', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(499, 516, '60000203', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(500, 517, '70000156', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(501, 518, '70000128', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(502, 519, '70000246', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(503, 520, '70000254', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(504, 521, '70000218', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(505, 522, '70000257', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(506, 523, '70000158', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(507, 524, '30000149', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(508, 525, '70000159', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(509, 526, '70000221', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(510, 527, '70000270', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(511, 528, '70000260', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(512, 529, '70000161', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(513, 530, '70000255', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(514, 531, '70000162', 4641854.00, 4641854.00, 26831.53, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(515, 532, '50000145', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(516, 533, '70000163', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(517, 534, '70000298', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(518, 535, '70000164', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(519, 536, '70000165', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(520, 537, '70000166', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(521, 538, '70000167', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(522, 539, '70000168', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(523, 540, '70000300', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(524, 541, '70000170', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(525, 542, '70000171', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(526, 543, '70000194', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(527, 544, '70000172', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(528, 545, '50000148', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(529, 546, '70000248', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(530, 547, '70000174', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(531, 548, '70000176', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(532, 549, '70000249', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(533, 550, '70000178', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(534, 551, '70000284', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(535, 552, '70000179', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(536, 553, '50000150', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(537, 554, '70000180', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(538, 555, '70000182', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(539, 556, '70000297', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(540, 557, '70000183', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(541, 558, '70000185', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(542, 559, '70000186', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(543, 560, '70000187', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(544, 561, '70000299', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(545, 562, '70000188', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(546, 563, '10000193', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(547, 564, '70000190', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(548, 565, '70000177', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(549, 566, '70000294', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(550, 567, '40000106', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(551, 568, '40000119', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(552, 569, '40000104', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(553, 570, '40000114', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(554, 571, '40000113', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(555, 572, '40000116', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(556, 573, '40000110', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(557, 574, '40000118', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(558, 575, '40000111', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(559, 576, '40000115', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(560, 577, '40000108', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(561, 578, '40000101', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(562, 579, '40000102', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(563, 580, '40000103', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(564, 581, '40000117', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(565, 582, '40000112', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(566, 583, '40000109', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(567, 584, '40000107', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(568, 585, '40000121', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(569, 586, '80000100', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(570, 587, '80000115', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(571, 588, '80000116', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(572, 589, '80000103', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(573, 590, '80000117', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(574, 591, '80000118', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(575, 592, '80000106', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(576, 593, '80000119', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(577, 594, '80000108', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(578, 595, '80000120', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(579, 596, '90000100', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(580, 597, '80000111', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(581, 598, '80000112', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(582, 599, '80000113', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19'),
(583, 600, '80000114', 4641854.00, 4641854.00, 0.00, 1, 1, '2022-02-05 01:28:19', '2022-02-05 01:28:19');

-- --------------------------------------------------------

--
-- Table structure for table `employee_trainings`
--

CREATE TABLE `employee_trainings` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(30) NOT NULL,
  `certificate_date` date NOT NULL,
  `total_hour` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee_trainings`
--

INSERT INTO `employee_trainings` (`id`, `emp_id`, `training_id`, `description`, `location`, `certificate_date`, `total_hour`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 296, 0, '1', '1', '2022-02-22', 1, 1, 1, '0000-00-00 00:00:00', '2022-02-10 20:43:42');

-- --------------------------------------------------------

--
-- Table structure for table `gates`
--

CREATE TABLE `gates` (
  `id` int(11) NOT NULL,
  `gate` varchar(10) NOT NULL,
  `gate_name` varchar(15) NOT NULL,
  `token` varchar(30) NOT NULL,
  `before_token` varchar(30) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gates`
--

INSERT INTO `gates` (`id`, `gate`, `gate_name`, `token`, `before_token`, `updated_at`) VALUES
(1, 'g1', 'Gate 1', '1644813093-g1', '1644812893-g1', '2022-02-14 07:31:24'),
(2, 'g2', 'Gate 2', '1644806759-g2', '1644806725-g2', '2022-02-14 07:31:24'),
(3, 'g3', 'Gate 3', '1644806759-g3', '1644806725-g3', '2022-02-14 07:31:47'),
(4, 'g4', 'Gate 4', '1644806755-g4', '1644806723-g4', '2022-02-14 07:31:47'),
(5, 'g5', 'Gate 5', '1644806731-g5', '1644806697-g5', '2022-02-14 07:32:06'),
(6, 'g6', 'Gate 6', '1644806760-g6', '1644806726-g6', '2022-02-14 07:32:06'),
(7, 'g7', 'Gate 7', '1644825052-g7', '1644814014-g7', '2022-02-14 07:32:18');

-- --------------------------------------------------------

--
-- Table structure for table `national_days`
--

CREATE TABLE `national_days` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `national_days`
--

INSERT INTO `national_days` (`id`, `location`, `date`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(3, 'KF-JKT', '2022-01-01', 'Tahun Baru Masehi 2022', 1, 1, '2022-01-13 13:47:47', '2022-01-13 23:03:24'),
(4, 'KF-JKT', '2022-02-01', 'Tahun Baru Imlek 2573 Kongzli', 1, 1, '2022-01-13 13:49:27', '2022-01-13 13:49:27'),
(5, 'KF-JKT', '2022-01-28', 'Isra\' Mi\'raj Nabi Muhammad SAW', 1, 1, '2022-01-13 13:51:20', '2022-01-13 13:51:20'),
(6, 'KF-JKT', '2022-03-03', 'Hari Suci Nyepi Tahun Baru Saka 1944', 1, 1, '2022-01-13 13:52:29', '2022-01-13 13:52:29'),
(7, 'KF-JKT', '2022-04-15', 'Wafat Isa Al Masih', 1, 1, '2022-01-13 13:52:49', '2022-01-13 13:52:49'),
(8, 'KF-JKT', '2022-05-01', 'Hari Buruh Internasional', 1, 1, '2022-01-13 13:53:08', '2022-01-13 13:53:08'),
(9, 'KF-JKT', '2022-05-02', 'Hari Raya Idul Fitri 1443h', 1, 1, '2022-01-13 13:53:38', '2022-01-13 13:53:38'),
(10, 'KF-JKT', '2022-05-03', 'Hari Raya Idul Fitri 1443h', 1, 1, '2022-01-13 13:53:49', '2022-01-13 13:53:49'),
(11, 'KF-JKT', '2022-05-16', 'Hari Raya Waisak 2566 BE', 1, 1, '2022-01-13 13:54:12', '2022-01-13 13:54:12'),
(12, 'KF-JKT', '2022-05-26', 'Kenaikan Yesus Kristus', 1, 1, '2022-01-13 13:54:27', '2022-01-13 13:54:27'),
(13, 'KF-JKT', '2022-06-01', 'Hari Lahir Pancasila', 1, 1, '2022-01-13 13:54:44', '2022-01-13 13:54:44'),
(14, 'KF-JKT', '2022-07-09', 'Hari Raya Idul Adha 1443h', 1, 1, '2022-01-13 13:55:12', '2022-01-13 13:55:12'),
(15, 'KF-JKT', '2022-07-30', 'Tahun Baru Islam 1444h', 1, 1, '2022-01-13 13:55:28', '2022-01-13 13:55:28'),
(16, 'KF-JKT', '2022-08-17', 'Hari Kemerdekaan RI', 1, 1, '2022-01-13 13:55:51', '2022-01-13 13:55:51'),
(17, 'KF-JKT', '2022-10-08', 'Maulid Nabi Muhammad SAW', 1, 1, '2022-01-13 13:56:18', '2022-01-13 13:56:18'),
(18, 'KF-JKT', '2022-12-25', 'Hari Raya Natal', 1, 1, '2022-01-13 13:56:39', '2022-01-13 13:56:39');

-- --------------------------------------------------------

--
-- Table structure for table `overtime_requirement`
--

CREATE TABLE `overtime_requirement` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `table_code` varchar(15) NOT NULL,
  `name` varchar(30) NOT NULL,
  `category` varchar(20) NOT NULL,
  `department_id` int(11) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `pic_emails` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `overtime_requirement`
--

INSERT INTO `overtime_requirement` (`id`, `location`, `table_code`, `name`, `category`, `department_id`, `sub_department_id`, `division_id`, `pic_emails`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', 'makan', 'Makan', 'Fasilitas', 3, 12, 34, 'achmadroy71milano@gmail.com,ari.gunawan@kimiafarma.co.id', 1, 1, '2022-01-08 00:11:41', '2022-02-05 00:31:06'),
(2, 'KF-JKT', 'jemputan', 'Jemputan', 'Fasilitas', 3, 12, 34, 'achmadroy71milano@gmail.com,ari.gunawan@kimiafarma.co.id', 1, 1, '2022-01-08 00:22:42', '2022-02-05 00:30:07'),
(3, 'KF-JKT', 'ahu', 'AHU', 'Tenaga Kerja', 1, 5, 16, 'dindaalza@gmail.com', 1, 1, '2022-01-08 00:21:53', '2022-02-05 00:27:26'),
(4, 'KF-JKT', 'compressor', 'Compressor', 'Tenaga Kerja', 1, 5, 16, 'dindaalza@gmail.com', 1, 1, '2022-01-08 00:22:08', '2022-02-05 00:27:56'),
(5, 'KF-JKT', 'pw', 'PW', 'Tenaga Kerja', 1, 5, 16, 'dindaalza@gmail.com', 1, 1, '2022-01-08 00:22:26', '2022-02-05 00:31:29'),
(6, 'KF-JKT', 'steam', 'Steam', 'Tenaga Kerja', 1, 5, 16, 'dindaalza@gmail.com', 1, 1, '2022-01-08 00:21:39', '2022-02-05 00:31:42'),
(7, 'KF-JKT', 'dust_collector', 'Dust Collector', 'Tenaga Kerja', 1, 5, 16, 'dindaalza@gmail.com', 1, 1, '2022-01-08 00:23:04', '2022-02-05 00:28:19'),
(8, 'KF-JKT', 'wfi', 'Water For Injection', 'Tenaga Kerja', 1, 5, 16, 'dindaalza@gmail.com', 1, 1, '2022-01-18 14:15:31', '2022-02-05 00:31:51'),
(9, 'KF-JKT', 'mechanic', 'Mekanik', 'Tenaga Kerja', 1, 5, 15, 'dindaalza@gmail.com', 1, 1, '2022-01-18 21:57:06', '2022-02-05 00:31:18'),
(10, 'KF-JKT', 'electric', 'Listrik', 'Tenaga Kerja', 1, 5, 17, 'dindaalza@gmail.com', 1, 1, '2022-01-18 21:57:51', '2022-02-05 00:30:18'),
(11, 'KF-JKT', 'hnn', 'Hardware & Network', 'Tenaga Kerja', 1, 5, 18, 'dindaalza@gmail.com', 1, 1, '2022-01-19 15:14:19', '2022-02-05 00:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `overtime_revision_requests`
--

CREATE TABLE `overtime_revision_requests` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `task_id` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `response` text NOT NULL,
  `department_id` int(11) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `status` enum('CREATED','CANCELED','REJECTED','CLOSED','PROCESS') NOT NULL DEFAULT 'CREATED',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `overtime_revision_requests`
--

INSERT INTO `overtime_revision_requests` (`id`, `location`, `task_id`, `description`, `response`, `department_id`, `sub_department_id`, `filename`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', '001/OT-REV/KF-JKT/II/2022', '002/OT-EMP/KF-JKT/II/2022 Revisi, Kurangi 1 Jam', 'Oke Sudah Di Kurangi', 1, 5, '1644561487_1_159.jpeg', 'CLOSED', 1, 1, '2022-02-11 13:38:07', '2022-02-11 13:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `overtime_revision_requests_detail`
--

CREATE TABLE `overtime_revision_requests_detail` (
  `id` int(11) NOT NULL,
  `task_id` varchar(30) NOT NULL,
  `emp_task_id` varchar(30) NOT NULL,
  `status` enum('CREATED','CLOSED','REJECTED','CANCELED') NOT NULL DEFAULT 'CREATED',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `overtime_revision_requests_detail`
--

INSERT INTO `overtime_revision_requests_detail` (`id`, `task_id`, `emp_task_id`, `status`, `created_at`, `updated_at`) VALUES
(1, '001/OT-REV/KF-JKT/II/2022', '002/OT-EMP/KF-JKT/II/2022', 'CLOSED', '2022-02-11 13:38:07', '2022-02-11 13:38:07');

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE `ranks` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`id`, `location`, `name`, `grade`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', 'General Manager', '18-19', 1, 1, '2021-12-25 06:28:10', '2021-12-25 07:26:39'),
(2, 'KF-JKT', 'Manager', '15-17', 1, 1, '2021-12-25 06:28:17', '2021-12-25 07:26:50'),
(3, 'KF-JKT', 'Asisten Manager', '13-14', 1, 1, '2021-12-25 06:28:27', '2022-01-06 21:50:45'),
(4, 'KF-JKT', 'Asisten Manager', '11-12', 1, 1, '2021-12-25 06:40:03', '2021-12-25 07:36:33'),
(5, 'KF-JKT', 'Supervisor', '9-10', 1, 1, '2021-12-25 06:40:43', '2021-12-28 23:52:32'),
(6, 'KF-JKT', 'Supervisor', '7-8', 1, 1, '2021-12-25 06:40:55', '2021-12-25 07:27:19'),
(7, 'KF-JKT', 'Pelaksana', '5-6', 1, 1, '2021-12-25 06:41:07', '2021-12-25 07:27:11'),
(8, 'KF-JKT', 'Pelaksana', '3-4', 1, 1, '2021-12-25 06:41:15', '2021-12-25 07:27:01'),
(9, 'KF-JKT', 'Pegawai Tidak Tetap', '0-0', 1, 1, '2021-12-25 06:41:24', '2021-12-25 06:41:24'),
(10, 'KF-JKT', 'Driver', '0-0', 1, 1, '2022-01-04 13:38:33', '2022-01-04 13:38:33');

-- --------------------------------------------------------

--
-- Table structure for table `sub_departments`
--

CREATE TABLE `sub_departments` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `file_limit` double NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sub_departments`
--

INSERT INTO `sub_departments` (`id`, `location`, `department_id`, `name`, `file_limit`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(0, 'KF-JKT', 0, '-', 100000000, 1, 1, '2022-01-20 09:36:06', '2022-01-20 09:35:28'),
(1, 'KF-JKT', 1, 'Produksi I', 100000000, 1, 1, '2022-01-19 15:04:16', '2022-01-31 11:03:07'),
(2, 'KF-JKT', 1, 'Produksi II', 100000000, 1, 1, '2022-01-19 15:04:23', '2022-01-19 15:04:23'),
(3, 'KF-JKT', 1, 'Produksi III', 100000000, 1, 1, '2022-01-19 15:04:30', '2022-01-19 15:04:30'),
(4, 'KF-JKT', 1, 'Pengemasan', 100000000, 1, 1, '2022-01-19 15:04:37', '2022-01-20 09:16:52'),
(5, 'KF-JKT', 1, 'Teknik & Pemeliharaan', 100000000, 1, 1, '2022-01-19 15:04:45', '2022-01-26 13:27:44'),
(6, 'KF-JKT', 2, 'Pengembangan Produk', 100000000, 1, 1, '2022-01-19 15:05:09', '2022-01-19 15:05:09'),
(7, 'KF-JKT', 2, 'Sistem Mutu', 100000000, 1, 1, '2022-01-19 15:05:19', '2022-01-19 15:05:19'),
(8, 'KF-JKT', 3, 'Pengawasan Mutu', 100000000, 1, 1, '2022-01-19 15:05:42', '2022-01-19 15:05:42'),
(9, 'KF-JKT', 3, 'Pengendalian Proses Produksi', 100000000, 1, 1, '2022-01-19 15:05:55', '2022-01-19 15:05:55'),
(10, 'KF-JKT', 3, 'Pembelian Barang Operasional', 100000000, 1, 1, '2022-01-19 15:06:15', '2022-01-19 15:06:15'),
(11, 'KF-JKT', 3, 'SDM & Akuntansi', 100000000, 1, 1, '2022-01-19 15:06:37', '2022-01-26 13:34:03'),
(12, 'KF-JKT', 3, 'Umum & K3L', 100000000, 1, 1, '2022-01-19 15:06:55', '2022-01-26 13:34:13'),
(13, 'KF-JKT', 3, 'Penyimpanan', 100000000, 1, 1, '2022-01-19 15:07:51', '2022-01-19 15:07:51'),
(14, 'KF-JKT', 3, 'Investasi & Rehabilitasi', 100000000, 1, 1, '2022-01-19 15:08:07', '2022-01-26 13:33:52'),
(15, 'KF-JKT', 2, 'Regulatory', 100000000, 1, 1, '2022-01-19 15:47:56', '2022-01-19 15:47:56');

-- --------------------------------------------------------

--
-- Table structure for table `trainings`
--

CREATE TABLE `trainings` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nip` (`nip`),
  ADD KEY `employee_name` (`employee_name`);

--
-- Indexes for table `employee_educations`
--
ALTER TABLE `employee_educations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`,`school_name`);

--
-- Indexes for table `employee_families`
--
ALTER TABLE `employee_families`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `family_name` (`family_name`);

--
-- Indexes for table `employee_overtimes`
--
ALTER TABLE `employee_overtimes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `task_id` (`task_id`),
  ADD KEY `task_id_2` (`task_id`),
  ADD KEY `sub_department_id` (`sub_department_id`,`division_id`);

--
-- Indexes for table `employee_overtimes_detail`
--
ALTER TABLE `employee_overtimes_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_task_id_2` (`emp_task_id`),
  ADD KEY `task_id_2` (`task_id`),
  ADD KEY `emp_task_id` (`emp_task_id`),
  ADD KEY `sub_department_id` (`sub_department_id`,`division_id`);

--
-- Indexes for table `employee_pins`
--
ALTER TABLE `employee_pins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pin` (`pin`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `employee_ranks`
--
ALTER TABLE `employee_ranks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`,`sk_number`);

--
-- Indexes for table `employee_sallary`
--
ALTER TABLE `employee_sallary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `employee_trainings`
--
ALTER TABLE `employee_trainings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`,`training_id`);

--
-- Indexes for table `gates`
--
ALTER TABLE `gates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `national_days`
--
ALTER TABLE `national_days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `overtime_requirement`
--
ALTER TABLE `overtime_requirement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `overtime_revision_requests`
--
ALTER TABLE `overtime_revision_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`,`sub_department_id`);

--
-- Indexes for table `overtime_revision_requests_detail`
--
ALTER TABLE `overtime_revision_requests_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`task_id`,`emp_task_id`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `sub_departments`
--
ALTER TABLE `sub_departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=601;

--
-- AUTO_INCREMENT for table `employee_educations`
--
ALTER TABLE `employee_educations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_families`
--
ALTER TABLE `employee_families`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_overtimes`
--
ALTER TABLE `employee_overtimes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_overtimes_detail`
--
ALTER TABLE `employee_overtimes_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_pins`
--
ALTER TABLE `employee_pins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `employee_ranks`
--
ALTER TABLE `employee_ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `employee_sallary`
--
ALTER TABLE `employee_sallary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=584;

--
-- AUTO_INCREMENT for table `employee_trainings`
--
ALTER TABLE `employee_trainings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gates`
--
ALTER TABLE `gates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `national_days`
--
ALTER TABLE `national_days`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `overtime_requirement`
--
ALTER TABLE `overtime_requirement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `overtime_revision_requests`
--
ALTER TABLE `overtime_revision_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `overtime_revision_requests_detail`
--
ALTER TABLE `overtime_revision_requests_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ranks`
--
ALTER TABLE `ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sub_departments`
--
ALTER TABLE `sub_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `trainings`
--
ALTER TABLE `trainings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
