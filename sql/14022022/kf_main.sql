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
-- Database: `kf_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `accordions`
--

CREATE TABLE `accordions` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accordions`
--

INSERT INTO `accordions` (`id`, `menu_id`, `code`, `name`) VALUES
(1, 1, 'am_manajemen_user', 'Manajemen Akses'),
(2, 1, 'am_master_aplikasi', 'Master Aplikasi'),
(3, 2, 'hr_manajemen_karyawan', 'Manajemen Karyawan'),
(4, 4, 'other_umum', 'Umum'),
(5, 8, 'tnp_overtime_acc', 'Lembur'),
(6, 5, 'ga_other', 'Umum'),
(7, 4, 'other_lembur', 'Lembur'),
(8, 9, 'dashboard_performance', 'Performance'),
(9, 10, 'pm_timeline', 'Timeline');

-- --------------------------------------------------------

--
-- Table structure for table `acc_trees`
--

CREATE TABLE `acc_trees` (
  `id` int(11) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT 0,
  `code` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `file` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `acc_trees`
--

INSERT INTO `acc_trees` (`id`, `acc_id`, `parent`, `code`, `name`, `file`) VALUES
(1, 1, 0, 'am_user', 'User', ''),
(2, 1, 1, 'am_data_user', 'Data User', 'user.php'),
(3, 2, 0, 'am_kepegawaian', 'Kepegawaian', ''),
(4, 2, 3, 'am_data_lokasi', 'Data Lokasi', 'master_location.php'),
(5, 2, 3, 'am_data_departemen', 'Data Departemen', 'master_department.php'),
(6, 2, 3, 'am_data_jabatan', 'Data Jabatan', 'master_rank.php'),
(7, 2, 3, 'am_data_divisi', 'Data Divisi', 'master_division.php'),
(8, 2, 3, 'am_data_training', 'Data Training', 'master_training.php'),
(13, 3, 0, 'hr_karyawan', 'Karyawan', ''),
(14, 3, 13, 'hr_data_karyawan', 'Data Karyawan', 'employee.php'),
(15, 3, 0, 'hr_master_kepegawaian', 'Master Kepegawaian', ''),
(16, 3, 15, 'hr_data_departemen', 'Data Departemen', 'master_department.php'),
(17, 3, 15, 'hr_data_jabatan', 'Data Jabatan', 'master_rank.php'),
(18, 3, 15, 'hr_data_divisi', 'Data Divisi', 'master_division.php'),
(19, 3, 15, 'hr_data_training', 'Data Training', 'master_training.php'),
(20, 2, 0, 'am_fasilitas', 'Fasilitas', ''),
(21, 2, 20, 'am_ruang_meeting', 'Ruang Meeting', 'master_meeting_room.php'),
(22, 4, 0, 'other_ruang_meeting', 'Ruang Meeting', ''),
(23, 4, 22, 'other_reservasi_ruang_meeting', 'Reservasi Ruang Meeting', 'meeting_room_schedule.php'),
(24, 4, 22, 'other_daftar_ruang_meeting', 'Daftar Ruang Meeting', 'meeting_room_list.php'),
(25, 2, 3, 'am_data_sub_departemen', 'Data Sub Departemen', 'master_sub_department.php'),
(26, 3, 15, 'hr_data_sub_departemen', 'Data Sub Departemen', 'master_sub_department.php'),
(28, 2, 20, 'am_kendaraan_inventaris', 'Kendaraan Inventaris', 'master_vehicle.php'),
(29, 4, 0, 'other_kendaraan_inventaris', 'Kendaraan Inventaris', ''),
(30, 4, 29, 'other_reservasi_kendaraan', 'Reservasi Kendaraan', 'vehicle_schedule.php'),
(31, 4, 29, 'other_daftar_kendaraan', 'Daftar Kendaraan', 'vehicle_list.php'),
(32, 2, 0, 'am_mesin', 'Mesin', ''),
(33, 2, 32, 'am_mesin_produksi', 'Mesin Produksi', 'master_production_machine.php'),
(36, 2, 0, 'am_sipil', 'Sipil', ''),
(37, 2, 36, 'am_gedung', 'Data Gedung', 'master_building.php'),
(38, 2, 36, 'am_ruangan', 'Data Ruangan', 'master_building_room.php'),
(39, 7, 0, 'other_lembur', 'Lembur', ''),
(40, 7, 39, 'other_input_lembur', 'Input Lembur', 'input_overtime.php'),
(41, 2, 0, 'am_lembur', 'Lembur', ''),
(42, 3, 13, 'hr_data_gaji', 'Data Gaji Karyawan', 'basic_sallary.php'),
(43, 2, 0, 'am_pic', 'PIC', ''),
(44, 2, 43, 'am_data_pic', 'Data PIC', 'master_pic.php'),
(45, 7, 39, 'other_approval_lembur', 'Approval Lembur', 'approval_overtime.php'),
(46, 3, 0, 'hr_libur_nasional', 'Libur Nasional', ''),
(47, 3, 46, 'hr_data_libur_nasional', 'Libur Nasional', 'national_free.php'),
(48, 3, 13, 'hr_data_gaji_atasan', 'Data Gaji Atasan', 'superior_sallary.php'),
(49, 5, 0, 'tnp_overtime', 'Lembur', ''),
(50, 5, 49, 'tnp_request_lembur', 'Request Lembur Produksi', 'tnp_request_overtime.php'),
(51, 5, 49, 'tnp_input_lembur', 'Input Lembur', 'tnp_input_overtime.php'),
(52, 6, 0, 'ga_katering', 'Katering', ''),
(53, 6, 52, 'ga_vendor_katering', 'Vendor Katering', 'cathering.php'),
(56, 7, 39, 'other_report_lembur', 'Report Lembur', 'report_overtime.php'),
(57, 2, 41, 'am_kebutuhan_lembur', 'Kebutuhan Lembur', 'master_overtime_requirement.php'),
(58, 8, 0, 'dashboard_overtime_tree', 'Lembur', ''),
(59, 8, 58, 'dashboard_overtime_summary', 'Summary By Personil', 'overtime_summary.php,overtime_summary_data.php'),
(60, 8, 58, 'dashboard_overtime_summary_provider', 'Summary By Penyelenggara', 'overtime_summary_provider.php,overtime_summary_provider_data.php'),
(61, 8, 58, 'dashboard_overtime_comparation', 'Komparasi Lembur', 'overtime_comparation.php'),
(62, 3, 0, 'hr_lembur', 'Lembur', ''),
(63, 3, 62, 'hr_report_lembur', 'Report Lembur', 'hr_report_overtime.php'),
(64, 3, 62, 'hr_verified_lembur', 'Verified Lembur', 'hr_verified_overtime.php'),
(65, 7, 39, 'other_pengajuan_revisi_lembur', 'Pengajual Revisi Lembur', 'revision_request.php'),
(66, 3, 3, 'hr_data_pin_karyawan', 'Data PIN Karyawan', 'employee_pin.php'),
(67, 3, 62, 'hr_revisi_lembur', 'Request Revisi Lembur', 'hr_revision_overtime.php'),
(68, 6, 52, 'ga_snack_meeting', 'Meeting Snack', 'meeting_snack.php'),
(69, 6, 0, 'ga_meeting_rooms', 'Ruang Meeting', 'ga_meeting_rooms.php'),
(71, 6, 69, 'ga_meeting_room_reservation', 'Reservasi R. Meeting', 'ga_meeting_room_reservation.php'),
(72, 6, 69, 'ga_meeting_room_report', 'Report R. Meeting', 'ga_meeting_room_report.php'),
(73, 8, 0, 'dashboard_meeting_room', 'Ruang Meeting', ''),
(74, 8, 73, 'dashboard_meeting_room_summary', 'Summary', 'meeting_room_summary.php,meeting_room_summary_data.php'),
(75, 6, 0, 'ga_vehicle', 'Kendaraan Dinas', ''),
(76, 6, 75, 'ga_vehicles_reservation', 'Reservasi Kendaraan', 'ga_vehicle_reservation.php'),
(77, 6, 75, 'ga_vehicles_report', 'Report Kendaraan', 'ga_vehicle_report.php'),
(78, 6, 69, 'ga_meeting_room_monitoring', 'Monitoring R. Meeting', 'meeting_room_list.php'),
(79, 6, 75, 'ga_vehicle_monitoring', 'Monitoring Kendaraan', 'vehicle_list.php'),
(80, 8, 0, 'dashboard_vehicle', 'Kendaraan Dinas', ''),
(85, 8, 80, 'dashboard_vehicle_summary', 'Summary By Reservasi', 'vehicle_summary_data.php,vehicle_summary_data.php'),
(86, 8, 58, 'dashboard_overtime_machine', 'Waktu Penggunaan Mesin', 'overtime_summary_machine.php');

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE `email` (
  `id` int(11) NOT NULL,
  `alert_name` varchar(50) NOT NULL,
  `email_to` varchar(255) NOT NULL,
  `email_cc` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `filename` varchar(150) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `send_date` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `email`
--

INSERT INTO `email` (`id`, `alert_name`, `email_to`, `email_cc`, `subject`, `subject_name`, `message`, `filename`, `status`, `send_date`, `created_at`) VALUES
(1, 'OVERTIME_REVISION_REQUEST', 'elmy1335@gmail.com,iskandar@kimiafarma.co.id,mamiekbanu@yahoo.co.id,karuniasastraputra@gmai.com,sdmkf01@gmail.com', '', 'Request Revisi Lembur Produksi (Task ID: 001/OT-REV/KF-JKT/II/2022)', 'Spekta Alert: Request Revisi Lembur Produksi (Task ID: 001/OT-REV/KF-JKT/II/2022)', '\n<div>\n    <div style=\"padding: 5px 0px 0px 10px;text-align:center;\">\n        <img style=\"width: 220px;height: auto;\" src=\"https://goreklame.com/assets/logo-kf.png\" alt=\"kf\">\n        <hr style=\"border: 1px solid #422800\">\n        <p><b>PT Kimia Farma Tbk. Plant Jakarta</b></p>\n    </div>\n\n            <p>Dear Team <b>SDM</b>,</p>\n        <p>Berikut ini adalah <b>Permintaan Revisi Lembur</b> dari Bagian <b>Teknik & Pemeliharaan</b> dengan Nomor: <b>001/OT-REV/KF-JKT/II/2022</b></p>\n        <p>Instruksi Revisi:</p>\n        <br />\n        <p style=\"text-align:center\">002/OT-EMP/KF-JKT/II/2022 Revisi, Kurangi 1 Jam</p>\n        <br />\n        <p>Adapun lemburan yang hendak di revisi adalah sebagai berikut:</p>\n    <table style=\"font-family:sans-serif;border-collapse: collapse;width:100%;\">\n        <tr>\n            <th style=\"border: 1px solid #422800;padding: 8px;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #39c;color: #422800;\" colspan=\"2\">Detail Lembur</th>\n        </tr>\n                <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\" colspan=\"2\">#1</td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Task ID</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\"><b>002/OT/KF-JKT/II/2022</b></td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Sub Unit</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Produksi</td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Bagian</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Teknik & Pemeliharaan</td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Sub Unit</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Hardware & Network</td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Waktu Lembur</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">19 Februari 2022 07:30:00 - 19 Februari 2022 19:30:00</td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Tugas</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Tes</td>\n        </tr>\n            </table>\n</div>', '', 0, '0000-00-00 00:00:00', '2022-02-11 13:38:07'),
(2, 'OVERTIME_REVISION_CLOSED', 'elmy1335@gmail.com,iskandar@kimiafarma.co.id,mamiekbanu@yahoo.co.id,karuniasastraputra@gmai.com,sdmkf01@gmail.com', '', 'Closed Revisi Lembur Produksi (Task ID: 001/OT-REV/KF-JKT/II/2022)', 'Spekta Alert: Closed Revisi Lembur Produksi (Task ID: 001/OT-REV/KF-JKT/II/2022)', '\n<div>\n    <div style=\"padding: 5px 0px 0px 10px;text-align:center;\">\n        <img style=\"width: 220px;height: auto;\" src=\"https://goreklame.com/assets/logo-kf.png\" alt=\"kf\">\n        <hr style=\"border: 1px solid #422800\">\n        <p><b>PT Kimia Farma Tbk. Plant Jakarta</b></p>\n    </div>\n\n            <p>Dear Team <b>ASMAN Teknik & Pemeliharaan</b>,</p>\n        <p><b>Permintaan Revisi Lembur</b> dengan Nomor: <b>001/OT-REV/KF-JKT/II/2022</b> sudah di proses oleh <b>SDM</b> dengan status:</p>\n        <br />\n        <p style=\"text-align:center\"><b>CLOSED</b></p>\n        <br />\n        <p>Adapun lemburan yang hendak di revisi adalah sebagai berikut:</p>\n    <table style=\"font-family:sans-serif;border-collapse: collapse;width:100%;\">\n        <tr>\n            <th style=\"border: 1px solid #422800;padding: 8px;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #39c;color: #422800;\" colspan=\"2\">Detail Lembur</th>\n        </tr>\n                <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\" colspan=\"2\">#1</td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Task ID</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\"><b>002/OT/KF-JKT/II/2022</b></td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Sub Unit</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Produksi</td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Bagian</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Teknik & Pemeliharaan</td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Sub Unit</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Hardware & Network</td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Waktu Lembur</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">19 Februari 2022 07:30:00 - 19 Februari 2022 16:00:00</td>\n        </tr>\n        <tr>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Tugas</td>\n            <td style=\"border: 1px solid #422800;padding: 8px;text-align:left\">Tes</td>\n        </tr>\n            </table>\n</div>', '', 0, '0000-00-00 00:00:00', '2022-02-11 13:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `code` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `code`, `name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'KF-JKT', 'PT Kimia Farma Tbk. Plant Jakarta', 1, 1, '2021-12-01 22:42:59', '2022-01-20 10:51:22');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `icon` varchar(15) NOT NULL,
  `main_folder` text NOT NULL,
  `subfolder_1` text NOT NULL,
  `subfolder_2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `icon`, `main_folder`, `subfolder_1`, `subfolder_2`) VALUES
(1, 'Akses & Master', 'key', 'appmaster,user', 'appmaster:access,appmaster:employment,appmaster:facility,appmaster:machine,appmaster:overtime', ''),
(2, 'Human Resource', 'hrd', 'appmaster,document,hr', 'hr:emp,appmaster:employment', ''),
(3, 'Dokumen Kontrol', 'doc', 'document', '', ''),
(4, 'Others', 'others', 'other', 'other:meeting_room,other:vehicle,other:overtime', ''),
(5, 'General Affair', 'building', 'g_affair', 'g_affair:cathering,g_affair:meeting_room,g_affair:vehicle', ''),
(6, 'Production', 'production', 'production', '', ''),
(7, 'Warehouse', 'warehouse', 'warehouse', '', ''),
(8, 'Teknik & Pemeliharaan', 'tools', 'tnp', 'tnp:overtime', ''),
(9, 'Dashboard', 'dashboard', 'dashboard', 'dashboard:overtime,dashboard:meeting_room,dashboard:vehicle', ''),
(10, 'Proyek Manajemen', 'timeline', 'project_manager', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pics`
--

CREATE TABLE `pics` (
  `id` int(11) NOT NULL,
  `location` varchar(15) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `pic_emails` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pics`
--

INSERT INTO `pics` (`id`, `location`, `code`, `name`, `sub_department_id`, `department_id`, `pic_emails`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(8, 'KF-JKT', 'overtime', 'PIC Overtime Teknik & Pemeliharaan', 5, 1, 'dindaalza@gmail.com,teknik.kfupj@kimiafarma.co.id', 1, 1, '2022-01-20 08:20:10', '2022-02-10 10:23:24'),
(9, 'KF-JKT', 'overtime', 'PIC Overtime Produksi I', 1, 1, 'innekeputri92@gmail.com', 1, 1, '2022-01-22 22:25:52', '2022-02-10 13:33:02'),
(10, 'KF-JKT', 'meeting_rooms', 'PIC Reservasi Ruang Meeting', 12, 3, 'dhnnesaka@gmail.com,siregar.evendy@kimiafarma.co.id', 1, 1, '2022-01-28 08:15:07', '2022-02-10 10:21:02'),
(11, 'KF-JKT', 'vehicles', 'PIC Reservasi Kendaraan Dinas', 12, 3, 'siregar.evendy@kimiafarma.co.id,tafriaji.2595@gmail.com', 1, 1, '2022-01-28 08:16:02', '2022-02-10 10:17:40'),
(12, 'KF-JKT', 'overtime', 'PIC Overtime Produksi II', 2, 1, 'admproduksi2.kfupj@gmail.com,febynuraeni11@gmail.com', 1, 1, '2022-02-10 10:26:30', '2022-02-10 13:30:55'),
(13, 'KF-JKT', 'overtime', 'PIC Overtime Produksi III', 3, 1, 'kfpj.arv@gmail.com', 1, 1, '2022-02-10 10:27:00', '2022-02-10 13:28:46'),
(14, 'KF-JKT', 'overtime', 'PIC Overtime Pengemas Primer & Pengemas Sekunder', 4, 1, 'pengemasprimer.kfupj@gmail.com', 1, 1, '2022-02-10 10:31:41', '2022-02-10 13:51:30'),
(15, 'KF-JKT', 'overtime', 'PIC Overtime Umum & K3L', 12, 3, 'k3l.plantjakarta@gmail.com,dhnnesaka@gmail.com', 1, 1, '2022-02-10 13:23:15', '2022-02-10 14:00:35'),
(16, 'KF-JKT', 'overtime', 'PIC Overtime IR', 14, 3, 'capex.upj@kimiafarma.co.id', 1, 1, '2022-02-10 13:38:05', '2022-02-10 13:38:05'),
(17, 'KF-JKT', 'overtime', 'PIC Overtime SDM & Akuntansi', 11, 3, 'sdmkf01@gmail.com', 1, 1, '2022-02-10 13:40:24', '2022-02-10 13:40:24'),
(18, 'KF-JKT', 'overtime', 'PIC Overtime Gudang Bahan Baku & Penandaan', 13, 3, 'penandaanprinting@gmail.com,bahanbaku20@gmail.com,ricenovianti19@gmail.com', 1, 1, '2022-02-10 13:43:35', '2022-02-10 13:51:07'),
(19, 'KF-JKT', 'overtime', 'PIC Overtime Pengawasan Mutu', 8, 3, 'qc.kfplantjakarta@gmail.com', 1, 1, '2022-02-10 13:54:58', '2022-02-10 13:54:58'),
(20, 'KF-JKT', 'overtime', 'PIC Overtime Sistem Mutu', 7, 2, 'mutia757@gmail.com', 1, 1, '2022-02-10 13:56:54', '2022-02-10 13:56:54'),
(21, 'KF-JKT', 'overtime', 'PIC Overtime Pengembangan Produk', 6, 2, 'nramdanimega5@gmail.com', 1, 1, '2022-02-10 14:02:55', '2022-02-10 14:03:39'),
(22, 'KF-JKT', 'overtime', 'PIC Overtime Pembelian Barang Operasional', 10, 3, 'purchasingkfpj@gmail.com', 1, 1, '2022-02-10 14:09:59', '2022-02-10 14:09:59');

-- --------------------------------------------------------

--
-- Table structure for table `projects_link`
--

CREATE TABLE `projects_link` (
  `id` varchar(30) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `source` varchar(30) NOT NULL,
  `target` varchar(30) NOT NULL,
  `type` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projects_link`
--

INSERT INTO `projects_link` (`id`, `sub_department_id`, `division_id`, `source`, `target`, `type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('1643984592582', 5, 18, '1643984592579', '1643984592581', 1, 1, 1, '2022-02-04 21:23:48', '2022-02-04 21:23:49'),
('1643984592583', 5, 18, '1643984592580', '1643984592581', 2, 1, 1, '2022-02-04 21:23:53', '2022-02-04 21:23:53'),
('1643984592585', 5, 18, '1643984592581', '1643984592584', 1, 1, 1, '2022-02-04 21:24:07', '2022-02-04 21:24:07'),
('1644427233019', 5, 18, '1644427233013', '1644427233018', 1, 1, 1, '2022-02-10 00:19:40', '2022-02-10 00:19:40'),
('1644427351518', 5, 18, '1644427233013', '1644427351513', 1, 1, 1, '2022-02-10 00:21:45', '2022-02-10 00:21:45'),
('1644561458020', 5, 18, '1644427233013', '1644561458015', 1, 1, 1, '2022-02-11 13:36:38', '2022-02-11 13:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `projects_task`
--

CREATE TABLE `projects_task` (
  `id` varchar(50) NOT NULL,
  `location` varchar(15) NOT NULL,
  `sub_department_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration` int(11) NOT NULL,
  `progress` double(10,2) NOT NULL,
  `parent` varchar(50) NOT NULL,
  `task_id` varchar(30) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projects_task`
--

INSERT INTO `projects_task` (`id`, `location`, `sub_department_id`, `division_id`, `text`, `start_date`, `end_date`, `duration`, `progress`, `parent`, `task_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('1644054644692', 'KF-JKT', 5, 18, 'New Task', '2022-03-04', '2022-03-05', 1, 0.00, '0', '1644054644692', 1, 1, '2022-02-05 16:51:14', '2022-02-05 16:51:14'),
('1644427233013', 'KF-JKT', 5, 18, 'New Task', '2022-02-10', '2022-02-11', 1, 0.00, '0', '1644427233013', 1, 1, '2022-02-10 00:19:29', '2022-02-10 00:19:33'),
('1644427233018', 'KF-JKT', 5, 18, 'New Task', '2022-02-11', '2022-02-12', 1, 0.00, '1644427233013', '1644427233013', 1, 1, '2022-02-10 00:19:37', '2022-02-10 00:19:38'),
('1644427351513', 'KF-JKT', 5, 18, 'New Task', '2022-02-12', '2022-02-13', 1, 0.00, '1644427233018', '1644427233013', 1, 1, '2022-02-10 00:21:36', '2022-02-10 00:21:47'),
('1644561458015', 'KF-JKT', 5, 18, 'New Task', '2022-02-10', '2022-02-13', 3, 0.00, '1644427351513', '1644427233013', 1, 1, '2022-02-11 13:36:33', '2022-02-11 13:36:54');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `display_name` varchar(25) NOT NULL,
  `created-at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `created-at`) VALUES
(1, 'admin', 'Admin', '2021-11-23 21:33:58'),
(2, 'emp', 'Employee', '2021-12-04 20:02:26');

-- --------------------------------------------------------

--
-- Table structure for table `temp_files`
--

CREATE TABLE `temp_files` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `action` varchar(30) NOT NULL,
  `doc_name` varchar(50) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL,
  `size` double NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `trees`
--

CREATE TABLE `trees` (
  `id` int(11) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nip` varchar(15) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `bypass_password` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL,
  `password_created` date NOT NULL,
  `password_updated` date NOT NULL,
  `verified` int(11) NOT NULL DEFAULT 0,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nip`, `username`, `password`, `bypass_password`, `role_id`, `password_created`, `password_updated`, `verified`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '9999', 'superuser', '$2a$08$igPA03AhhurICd07d1kNoefqxbtwSaW3guovuH6K3CpgCshd1zFx.', '$2a$08$igPA03AhhurICd07d1kNoefqxbtwSaW3guovuH6K3CpgCshd1zFx.', 1, '2021-12-01', '2021-12-30', 1, 'ACTIVE', 1, 1, '2021-12-01 06:08:22', '2021-12-01 06:07:52'),
(10, '19860115A', 'edfano', '$2a$08$Ir1S3n65/Iu3FozbstQQq.J7LT9Wtsba8B7t4cE.fqV00ea8/NPPO', '$2a$08$igPA03AhhurICd07d1kNoefqxbtwSaW3guovuH6K3CpgCshd1zFx.', 2, '2022-02-07', '2022-02-07', 1, 'ACTIVE', 1, 1, '2022-02-07 23:04:16', '2022-02-07 23:04:16'),
(11, '19901125A', 'asep.diki', '$2a$08$UhjpWhIVrYifV7h7IANCe.ToOXqHbuUepxlM72.syz5bfEEWJtxoi', '$2a$08$igPA03AhhurICd07d1kNoefqxbtwSaW3guovuH6K3CpgCshd1zFx.', 2, '2022-02-07', '2022-02-07', 1, 'ACTIVE', 1, 1, '2022-02-07 23:21:14', '2022-02-07 23:21:14'),
(12, '60000153', 'fikri.agil', '$2a$08$DIRdFCcofz.129ghd4p/EO2ebyTnrvf2DMh6PSqrX0c3Jk/.P9n.e', '$2a$08$igPA03AhhurICd07d1kNoefqxbtwSaW3guovuH6K3CpgCshd1zFx.', 2, '2022-02-08', '2022-02-08', 1, 'ACTIVE', 1, 1, '2022-02-08 00:05:47', '2022-02-08 00:05:47'),
(13, '60000244', 'arman', '$2a$08$7woyJ4e.oysxwQ6fU5w7quvwCCvSrg2V9jPKfskmBTTexX5MCCIwa', '$2a$08$igPA03AhhurICd07d1kNoefqxbtwSaW3guovuH6K3CpgCshd1zFx.', 2, '2022-02-08', '2022-02-08', 1, 'ACTIVE', 1, 1, '2022-02-08 00:43:32', '2022-02-08 00:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `users_accordion`
--

CREATE TABLE `users_accordion` (
  `id` int(11) NOT NULL,
  `user_menu_id` int(11) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `trees` text NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_accordion`
--

INSERT INTO `users_accordion` (`id`, `user_menu_id`, `acc_id`, `trees`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-07 23:05:11', '2022-02-11 11:14:48'),
(2, 1, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-07 23:05:15', '2022-02-11 11:14:54'),
(3, 2, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-07 23:05:25', '2022-02-11 11:15:17'),
(4, 3, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-07 23:21:37', '2022-02-10 14:55:51'),
(5, 4, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-07 23:21:46', '2022-02-10 14:54:47'),
(6, 5, 7, 'other_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-07 23:25:39', '2022-02-11 10:24:01'),
(7, 6, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-08 00:18:31', '2022-02-10 14:56:28'),
(8, 7, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-08 00:18:39', '2022-02-11 10:07:28'),
(9, 8, 8, '', 'INACTIVE', 1, 1, '2022-02-10 14:44:06', '2022-02-10 14:52:56'),
(10, 9, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-10 14:46:34', '2022-02-10 14:47:50'),
(11, 9, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-10 14:46:37', '2022-02-10 14:46:43'),
(12, 10, 3, '', 'INACTIVE', 1, 1, '2022-02-10 14:48:13', '2022-02-10 14:48:30'),
(13, 11, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-10 14:49:21', '2022-02-10 14:50:47'),
(14, 11, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-10 14:49:22', '2022-02-10 14:49:29'),
(15, 12, 8, '', 'INACTIVE', 1, 1, '2022-02-10 14:51:05', '2022-02-10 14:52:14'),
(16, 13, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-10 14:53:17', '2022-02-10 14:53:38'),
(17, 13, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-10 14:53:18', '2022-02-10 14:53:27'),
(18, 4, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-10 14:54:35', '2022-02-10 14:54:40'),
(19, 14, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-10 14:54:57', '2022-02-10 14:55:01'),
(20, 15, 9, '', 'ACTIVE', 1, 1, '2022-02-10 14:55:07', '2022-02-10 14:55:07'),
(21, 3, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-10 14:55:43', '2022-02-10 14:55:48'),
(22, 17, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-10 14:55:56', '2022-02-10 14:56:02'),
(23, 18, 9, '', 'ACTIVE', 1, 1, '2022-02-10 14:56:09', '2022-02-10 14:56:09'),
(24, 6, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-10 14:56:18', '2022-02-10 14:56:24'),
(25, 20, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-10 14:56:36', '2022-02-10 14:56:55'),
(26, 21, 9, '', 'ACTIVE', 1, 1, '2022-02-10 14:57:04', '2022-02-10 14:57:04'),
(27, 7, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-10 14:57:24', '2022-02-10 14:57:30'),
(28, 24, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-10 14:58:11', '2022-02-10 14:58:16'),
(29, 25, 9, '', 'ACTIVE', 1, 1, '2022-02-10 14:58:20', '2022-02-10 14:58:20'),
(30, 26, 3, 'hr_karyawan,hr_data_karyawan,hr_data_gaji,hr_data_gaji_atasan,hr_master_kepegawaian,hr_data_departemen,hr_data_jabatan,hr_data_divisi,hr_data_training,hr_data_sub_departemen,hr_libur_nasional,hr_data_libur_nasional,hr_lembur,hr_report_lembur,hr_verified_lembur,hr_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-10 15:02:28', '2022-02-11 13:05:45'),
(31, 28, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-10 15:02:48', '2022-02-10 15:02:53'),
(32, 28, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-10 15:02:56', '2022-02-11 13:06:34'),
(33, 29, 6, 'ga_katering,ga_vendor_katering,ga_snack_meeting,ga_meeting_rooms,ga_meeting_room_reservation,ga_meeting_room_report,ga_meeting_room_monitoring,ga_vehicle,ga_vehicles_reservation,ga_vehicles_report,ga_vehicle_monitoring', 'ACTIVE', 1, 1, '2022-02-10 19:25:52', '2022-02-11 13:00:13'),
(34, 30, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:07:51', '2022-02-11 10:07:59'),
(35, 30, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:08:00', '2022-02-11 10:08:08'),
(36, 31, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:08:29', '2022-02-11 10:08:36'),
(37, 31, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:08:37', '2022-02-11 10:08:48'),
(38, 32, 4, '', 'ACTIVE', 1, 1, '2022-02-11 10:09:27', '2022-02-11 10:09:27'),
(39, 32, 7, '', 'ACTIVE', 1, 1, '2022-02-11 10:09:34', '2022-02-11 10:09:34'),
(40, 34, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:10:08', '2022-02-11 10:10:14'),
(41, 34, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:10:16', '2022-02-11 10:10:27'),
(42, 35, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:10:32', '2022-02-11 10:10:44'),
(43, 36, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:10:47', '2022-02-11 10:10:49'),
(44, 38, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:10:57', '2022-02-11 10:11:04'),
(45, 38, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:11:06', '2022-02-11 10:11:12'),
(46, 39, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:11:17', '2022-02-11 10:11:25'),
(47, 40, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:11:28', '2022-02-11 10:11:28'),
(48, 42, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:11:35', '2022-02-11 10:11:41'),
(49, 42, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:11:42', '2022-02-11 10:11:59'),
(50, 43, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:12:04', '2022-02-11 10:12:14'),
(51, 44, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:12:17', '2022-02-11 10:12:17'),
(52, 46, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:12:24', '2022-02-11 10:12:30'),
(53, 46, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:12:31', '2022-02-11 10:12:38'),
(54, 47, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:12:43', '2022-02-11 10:12:48'),
(55, 48, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:12:52', '2022-02-11 10:12:52'),
(56, 49, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:13:03', '2022-02-11 10:13:10'),
(57, 49, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:13:12', '2022-02-11 10:13:17'),
(58, 50, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:13:28', '2022-02-11 10:13:34'),
(59, 50, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:13:35', '2022-02-11 10:13:40'),
(60, 51, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:13:46', '2022-02-11 10:13:53'),
(61, 51, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:13:55', '2022-02-11 10:14:00'),
(62, 53, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:14:11', '2022-02-11 10:14:17'),
(63, 53, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:14:18', '2022-02-11 10:14:28'),
(64, 54, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:14:30', '2022-02-11 10:14:37'),
(65, 55, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:14:39', '2022-02-11 10:14:39'),
(66, 57, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:14:47', '2022-02-11 10:14:52'),
(67, 57, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:14:53', '2022-02-11 10:15:01'),
(68, 58, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:15:05', '2022-02-11 10:15:15'),
(69, 59, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:15:17', '2022-02-11 10:15:17'),
(70, 61, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:16:23', '2022-02-11 10:16:31'),
(71, 61, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:16:32', '2022-02-11 10:16:49'),
(72, 62, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:16:55', '2022-02-11 10:17:00'),
(73, 63, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:17:03', '2022-02-11 10:17:03'),
(74, 65, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:17:07', '2022-02-11 10:17:14'),
(75, 65, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:17:15', '2022-02-11 10:17:24'),
(76, 66, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:17:27', '2022-02-11 10:17:34'),
(77, 67, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:17:36', '2022-02-11 10:17:36'),
(78, 68, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:17:45', '2022-02-11 10:17:52'),
(79, 68, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:17:53', '2022-02-11 10:18:01'),
(80, 69, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:18:07', '2022-02-11 10:18:13'),
(81, 69, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:18:14', '2022-02-11 10:18:31'),
(82, 70, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:18:35', '2022-02-11 10:18:40'),
(83, 70, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:18:41', '2022-02-11 10:18:47'),
(84, 72, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:18:53', '2022-02-11 10:18:58'),
(85, 72, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:18:59', '2022-02-11 10:19:06'),
(86, 73, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:19:11', '2022-02-11 10:19:16'),
(87, 74, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:19:19', '2022-02-11 10:19:19'),
(88, 76, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:19:26', '2022-02-11 10:19:31'),
(89, 76, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:19:32', '2022-02-11 10:19:37'),
(90, 77, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:19:40', '2022-02-11 10:19:47'),
(91, 78, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:19:50', '2022-02-11 10:19:50'),
(92, 80, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:20:06', '2022-02-11 10:20:11'),
(93, 80, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:20:12', '2022-02-11 10:20:18'),
(94, 81, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:20:21', '2022-02-11 10:20:26'),
(95, 82, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:20:29', '2022-02-11 10:20:29'),
(96, 84, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:20:36', '2022-02-11 10:20:44'),
(97, 84, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:20:45', '2022-02-11 10:21:03'),
(98, 85, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:21:11', '2022-02-11 10:21:17'),
(99, 86, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:21:19', '2022-02-11 10:21:19'),
(100, 87, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:21:43', '2022-02-11 10:21:48'),
(101, 87, 7, 'other_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:21:49', '2022-02-11 10:22:47'),
(102, 88, 5, 'tnp_overtime,tnp_request_lembur,tnp_input_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:22:02', '2022-02-11 10:22:54'),
(103, 89, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:23:10', '2022-02-11 10:23:15'),
(104, 89, 7, 'other_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:23:16', '2022-02-11 10:23:22'),
(105, 90, 5, 'tnp_overtime,tnp_request_lembur,tnp_input_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:23:32', '2022-02-11 10:23:35'),
(106, 5, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:23:46', '2022-02-11 10:24:16'),
(107, 91, 5, 'tnp_overtime,tnp_request_lembur,tnp_input_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:24:05', '2022-02-11 10:24:09'),
(108, 92, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:24:21', '2022-02-11 10:24:29'),
(109, 92, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:24:31', '2022-02-11 10:24:39'),
(110, 93, 5, '', 'INACTIVE', 1, 1, '2022-02-11 10:24:46', '2022-02-11 10:24:50'),
(111, 95, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:24:55', '2022-02-11 10:25:07'),
(112, 96, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:25:09', '2022-02-11 10:25:09'),
(113, 98, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:25:15', '2022-02-11 10:25:21'),
(114, 98, 7, 'other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:25:33', '2022-02-11 10:25:44'),
(115, 100, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:25:47', '2022-02-11 10:25:52'),
(116, 101, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:26:10', '2022-02-11 10:26:10'),
(117, 103, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:26:33', '2022-02-11 10:26:39'),
(118, 103, 7, 'other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:26:45', '2022-02-11 10:28:26'),
(119, 104, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:26:57', '2022-02-11 10:27:01'),
(120, 105, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:27:05', '2022-02-11 10:27:05'),
(121, 106, 5, '', 'INACTIVE', 1, 1, '2022-02-11 10:27:15', '2022-02-11 10:27:33'),
(122, 108, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:27:47', '2022-02-11 10:27:52'),
(123, 108, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:27:54', '2022-02-11 10:28:04'),
(124, 109, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:28:08', '2022-02-11 10:28:13'),
(125, 110, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:28:15', '2022-02-11 10:28:15'),
(126, 112, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:30:19', '2022-02-11 10:30:25'),
(127, 112, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:30:26', '2022-02-11 10:30:31'),
(128, 113, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:30:40', '2022-02-11 10:30:44'),
(129, 113, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:30:45', '2022-02-11 10:30:49'),
(130, 114, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:30:55', '2022-02-11 10:31:00'),
(131, 114, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:31:02', '2022-02-11 10:31:06'),
(132, 116, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:31:14', '2022-02-11 10:31:19'),
(133, 116, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:31:20', '2022-02-11 10:31:25'),
(134, 117, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:31:29', '2022-02-11 10:31:54'),
(135, 119, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:32:01', '2022-02-11 10:32:06'),
(136, 119, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:32:16', '2022-02-11 10:32:26'),
(137, 120, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:32:30', '2022-02-11 10:32:38'),
(138, 121, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:32:41', '2022-02-11 10:32:41'),
(139, 123, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:32:46', '2022-02-11 10:32:51'),
(140, 123, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:32:52', '2022-02-11 10:32:58'),
(141, 124, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:33:02', '2022-02-11 10:33:07'),
(142, 125, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:33:10', '2022-02-11 10:33:10'),
(143, 127, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:33:17', '2022-02-11 10:33:22'),
(144, 127, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:33:22', '2022-02-11 10:33:27'),
(145, 128, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:33:31', '2022-02-11 10:33:35'),
(146, 129, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:33:38', '2022-02-11 10:33:38'),
(147, 130, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:33:51', '2022-02-11 10:33:56'),
(148, 130, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:33:51', '2022-02-11 10:34:00'),
(149, 131, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:34:06', '2022-02-11 10:34:11'),
(150, 131, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:34:30', '2022-02-11 10:34:36'),
(151, 132, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:34:40', '2022-02-11 10:34:45'),
(152, 132, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:34:46', '2022-02-11 10:34:51'),
(153, 134, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:35:01', '2022-02-11 10:35:06'),
(154, 134, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:35:07', '2022-02-11 10:35:12'),
(155, 135, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:35:15', '2022-02-11 10:35:21'),
(156, 136, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:35:23', '2022-02-11 10:35:23'),
(157, 138, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:35:30', '2022-02-11 10:35:35'),
(158, 138, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:35:36', '2022-02-11 10:35:46'),
(159, 139, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:35:50', '2022-02-11 10:35:56'),
(160, 140, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:35:59', '2022-02-11 10:35:59'),
(161, 142, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:36:04', '2022-02-11 10:36:09'),
(162, 142, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:36:10', '2022-02-11 10:36:17'),
(163, 143, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:36:20', '2022-02-11 10:36:25'),
(164, 144, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:36:28', '2022-02-11 10:36:28'),
(165, 146, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:36:37', '2022-02-11 10:36:42'),
(166, 146, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:36:37', '2022-02-11 10:36:49'),
(167, 147, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:36:52', '2022-02-11 10:36:58'),
(168, 149, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:37:12', '2022-02-11 10:37:19'),
(169, 149, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:37:14', '2022-02-11 10:37:24'),
(170, 150, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:37:30', '2022-02-11 10:37:36'),
(171, 150, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:37:30', '2022-02-11 10:37:42'),
(172, 151, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:37:46', '2022-02-11 10:38:00'),
(173, 151, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:37:47', '2022-02-11 10:38:10'),
(174, 153, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:38:21', '2022-02-11 10:38:27'),
(175, 153, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:38:21', '2022-02-11 10:38:31'),
(176, 154, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:38:34', '2022-02-11 10:38:39'),
(177, 155, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:38:43', '2022-02-11 10:38:43'),
(178, 157, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:40:16', '2022-02-11 10:40:21'),
(179, 157, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:40:16', '2022-02-11 10:40:35'),
(180, 158, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:40:41', '2022-02-11 10:40:47'),
(181, 159, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:40:50', '2022-02-11 10:40:50'),
(182, 161, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:40:55', '2022-02-11 10:41:01'),
(183, 161, 7, 'other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:40:56', '2022-02-11 10:41:52'),
(184, 162, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:41:04', '2022-02-11 10:41:09'),
(185, 163, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:41:12', '2022-02-11 10:41:12'),
(186, 165, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:41:17', '2022-02-11 10:41:23'),
(187, 165, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:41:18', '2022-02-11 10:41:28'),
(188, 166, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:41:31', '2022-02-11 10:41:36'),
(189, 167, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:41:40', '2022-02-11 10:41:40'),
(190, 168, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:46:01', '2022-02-11 10:46:06'),
(191, 168, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:46:01', '2022-02-11 10:46:14'),
(192, 169, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:46:21', '2022-02-11 10:46:27'),
(193, 169, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:46:22', '2022-02-11 10:46:31'),
(194, 170, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:46:36', '2022-02-11 10:46:41'),
(195, 170, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:46:36', '2022-02-11 10:46:47'),
(196, 172, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:46:51', '2022-02-11 10:46:56'),
(197, 172, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:46:52', '2022-02-11 10:47:02'),
(198, 173, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:47:05', '2022-02-11 10:47:11'),
(199, 174, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:47:15', '2022-02-11 10:47:15'),
(200, 176, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:47:20', '2022-02-11 10:47:25'),
(201, 176, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:47:20', '2022-02-11 10:47:29'),
(202, 177, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:47:33', '2022-02-11 10:47:37'),
(203, 178, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:47:40', '2022-02-11 10:47:40'),
(204, 180, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:47:49', '2022-02-11 10:47:54'),
(205, 180, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:47:49', '2022-02-11 10:48:02'),
(206, 181, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:48:04', '2022-02-11 10:48:08'),
(207, 182, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:48:14', '2022-02-11 10:48:14'),
(208, 183, 6, '', 'INACTIVE', 1, 1, '2022-02-11 10:48:23', '2022-02-11 10:48:28'),
(209, 185, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:48:37', '2022-02-11 10:48:42'),
(210, 185, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:48:38', '2022-02-11 10:48:48'),
(211, 186, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 10:48:50', '2022-02-11 10:48:55'),
(212, 187, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:48:58', '2022-02-11 10:48:58'),
(213, 188, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:53:24', '2022-02-11 10:53:30'),
(214, 188, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:53:25', '2022-02-11 10:53:37'),
(215, 189, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:53:40', '2022-02-11 10:53:46'),
(216, 189, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:53:41', '2022-02-11 10:53:50'),
(217, 190, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:53:54', '2022-02-11 10:53:58'),
(218, 190, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:53:54', '2022-02-11 10:54:02'),
(219, 192, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:54:16', '2022-02-11 10:54:21'),
(220, 192, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:54:16', '2022-02-11 10:54:26'),
(221, 193, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:54:36', '2022-02-11 10:54:46'),
(222, 194, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:54:48', '2022-02-11 10:54:48'),
(223, 196, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:54:52', '2022-02-11 10:54:58'),
(224, 196, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:54:53', '2022-02-11 10:55:10'),
(225, 197, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:55:26', '2022-02-11 10:55:32'),
(226, 198, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:55:35', '2022-02-11 10:55:35'),
(227, 200, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:56:33', '2022-02-11 10:59:01'),
(228, 200, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:56:33', '2022-02-11 10:59:08'),
(229, 201, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:59:14', '2022-02-11 10:59:20'),
(230, 202, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:59:23', '2022-02-11 10:59:23'),
(231, 204, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 10:59:28', '2022-02-11 10:59:33'),
(232, 204, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 10:59:28', '2022-02-11 10:59:37'),
(233, 205, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 10:59:41', '2022-02-11 10:59:45'),
(234, 206, 9, '', 'ACTIVE', 1, 1, '2022-02-11 10:59:47', '2022-02-11 10:59:47'),
(235, 207, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:00:06', '2022-02-11 11:00:11'),
(236, 207, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:00:07', '2022-02-11 11:00:16'),
(237, 208, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:00:20', '2022-02-11 11:00:25'),
(238, 208, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:00:21', '2022-02-11 11:00:28'),
(239, 209, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:00:32', '2022-02-11 11:00:37'),
(240, 209, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:00:33', '2022-02-11 11:00:44'),
(241, 211, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:04:13', '2022-02-11 11:04:19'),
(242, 211, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:04:13', '2022-02-11 11:04:28'),
(243, 212, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 11:04:31', '2022-02-11 11:04:38'),
(244, 213, 9, '', 'ACTIVE', 1, 1, '2022-02-11 11:04:40', '2022-02-11 11:04:40'),
(245, 215, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:04:46', '2022-02-11 11:04:53'),
(246, 215, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:04:46', '2022-02-11 11:05:23'),
(247, 216, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 11:05:29', '2022-02-11 11:05:33'),
(248, 217, 9, '', 'ACTIVE', 1, 1, '2022-02-11 11:05:35', '2022-02-11 11:05:35'),
(249, 219, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:05:40', '2022-02-11 11:05:45'),
(250, 219, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:05:40', '2022-02-11 11:05:50'),
(251, 220, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 11:05:53', '2022-02-11 11:05:58'),
(252, 221, 9, '', 'ACTIVE', 1, 1, '2022-02-11 11:06:00', '2022-02-11 11:06:00'),
(253, 223, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:06:05', '2022-02-11 11:06:12'),
(254, 223, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:06:05', '2022-02-11 11:06:20'),
(255, 224, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 11:06:24', '2022-02-11 11:06:29'),
(256, 225, 9, '', 'ACTIVE', 1, 1, '2022-02-11 11:06:31', '2022-02-11 11:06:31'),
(257, 226, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:06:42', '2022-02-11 11:06:47'),
(258, 226, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:06:42', '2022-02-11 11:06:54'),
(259, 227, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:06:58', '2022-02-11 11:07:04'),
(260, 227, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:06:59', '2022-02-11 11:07:09'),
(261, 228, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:07:12', '2022-02-11 11:07:17'),
(262, 228, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:07:13', '2022-02-11 11:07:23'),
(263, 230, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:07:27', '2022-02-11 11:07:32'),
(264, 230, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:07:27', '2022-02-11 11:07:45'),
(265, 231, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 11:07:51', '2022-02-11 11:08:02'),
(266, 232, 9, '', 'ACTIVE', 1, 1, '2022-02-11 11:08:05', '2022-02-11 11:08:05'),
(267, 234, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:08:10', '2022-02-11 11:08:15'),
(268, 234, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:08:10', '2022-02-11 11:08:20'),
(269, 235, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 11:08:23', '2022-02-11 11:08:27'),
(270, 236, 9, '', 'ACTIVE', 1, 1, '2022-02-11 11:08:30', '2022-02-11 11:08:30'),
(271, 238, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:08:36', '2022-02-11 11:08:41'),
(272, 238, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:08:36', '2022-02-11 11:08:53'),
(273, 239, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 11:08:58', '2022-02-11 11:09:02'),
(274, 240, 9, '', 'ACTIVE', 1, 1, '2022-02-11 11:09:04', '2022-02-11 11:09:04'),
(275, 242, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:09:08', '2022-02-11 11:09:14'),
(276, 242, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:09:09', '2022-02-11 11:09:18'),
(277, 243, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine', 'ACTIVE', 1, 1, '2022-02-11 11:09:21', '2022-02-11 11:09:26'),
(278, 244, 9, '', 'ACTIVE', 1, 1, '2022-02-11 11:09:28', '2022-02-11 11:09:28'),
(279, 245, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:13:00', '2022-02-11 11:13:05'),
(280, 245, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:13:01', '2022-02-11 11:13:13'),
(281, 246, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:13:18', '2022-02-11 11:13:23'),
(282, 246, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:13:18', '2022-02-11 11:13:26'),
(283, 247, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:13:30', '2022-02-11 11:13:35'),
(284, 247, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 11:13:30', '2022-02-11 11:13:39'),
(285, 249, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'INACTIVE', 1, 1, '2022-02-11 11:13:50', '2022-02-11 11:14:32'),
(286, 249, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'INACTIVE', 1, 1, '2022-02-11 11:13:51', '2022-02-11 11:14:26'),
(287, 250, 8, '', 'INACTIVE', 1, 1, '2022-02-11 11:14:10', '2022-02-11 11:14:16'),
(288, 253, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 11:14:57', '2022-02-11 11:15:03'),
(289, 254, 9, '', 'ACTIVE', 1, 1, '2022-02-11 11:15:05', '2022-02-11 11:15:05'),
(290, 2, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 11:15:10', '2022-02-11 11:15:15'),
(291, 256, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 11:15:20', '2022-02-11 11:15:24'),
(292, 257, 9, '', 'ACTIVE', 1, 1, '2022-02-11 11:15:26', '2022-02-11 11:15:26'),
(293, 259, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 12:56:34', '2022-02-11 12:56:48'),
(294, 259, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 12:56:35', '2022-02-11 12:56:55');
INSERT INTO `users_accordion` (`id`, `user_menu_id`, `acc_id`, `trees`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(295, 258, 6, 'ga_katering,ga_vendor_katering,ga_snack_meeting,ga_meeting_rooms,ga_meeting_room_reservation,ga_meeting_room_report,ga_meeting_room_monitoring,ga_vehicle,ga_vehicles_reservation,ga_vehicles_report,ga_vehicle_monitoring', 'ACTIVE', 1, 1, '2022-02-11 12:56:57', '2022-02-11 12:57:55'),
(296, 260, 8, 'dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-11 12:58:01', '2022-02-11 12:58:27'),
(297, 261, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 12:58:53', '2022-02-11 12:59:00'),
(298, 261, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 12:58:54', '2022-02-11 12:59:05'),
(299, 262, 6, 'ga_katering,ga_vendor_katering,ga_snack_meeting,ga_meeting_rooms,ga_meeting_room_reservation,ga_meeting_room_report,ga_meeting_room_monitoring,ga_vehicle,ga_vehicles_reservation,ga_vehicles_report,ga_vehicle_monitoring', 'ACTIVE', 1, 1, '2022-02-11 12:59:20', '2022-02-11 12:59:27'),
(300, 263, 8, 'dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-11 12:59:35', '2022-02-11 12:59:43'),
(301, 264, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 12:59:48', '2022-02-11 13:00:02'),
(302, 264, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 12:59:48', '2022-02-11 13:00:09'),
(303, 265, 8, 'dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-11 13:00:17', '2022-02-11 13:00:22'),
(304, 267, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:00:28', '2022-02-11 13:00:35'),
(305, 267, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:00:29', '2022-02-11 13:00:48'),
(306, 268, 6, 'ga_katering,ga_vendor_katering,ga_snack_meeting,ga_meeting_rooms,ga_meeting_room_reservation,ga_meeting_room_report,ga_meeting_room_monitoring,ga_vehicle,ga_vehicles_reservation,ga_vehicles_report,ga_vehicle_monitoring', 'ACTIVE', 1, 1, '2022-02-11 13:00:53', '2022-02-11 13:01:22'),
(307, 269, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-11 13:01:32', '2022-02-11 13:01:43'),
(308, 270, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:01:45', '2022-02-11 13:01:45'),
(309, 272, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:01:53', '2022-02-11 13:02:00'),
(310, 272, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:01:54', '2022-02-11 13:02:07'),
(311, 273, 6, 'ga_katering,ga_vendor_katering,ga_snack_meeting,ga_meeting_rooms,ga_meeting_room_reservation,ga_meeting_room_report,ga_meeting_room_monitoring,ga_vehicle,ga_vehicles_reservation,ga_vehicles_report,ga_vehicle_monitoring', 'ACTIVE', 1, 1, '2022-02-11 13:02:11', '2022-02-11 13:02:21'),
(312, 274, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-11 13:02:25', '2022-02-11 13:02:33'),
(313, 275, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:02:35', '2022-02-11 13:02:35'),
(314, 277, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:02:46', '2022-02-11 13:02:51'),
(315, 277, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:02:47', '2022-02-11 13:02:58'),
(316, 278, 6, 'ga_katering,ga_vendor_katering,ga_snack_meeting,ga_meeting_rooms,ga_meeting_room_reservation,ga_meeting_room_report,ga_meeting_room_monitoring,ga_vehicle,ga_vehicles_reservation,ga_vehicles_report,ga_vehicle_monitoring', 'ACTIVE', 1, 1, '2022-02-11 13:03:01', '2022-02-11 13:03:08'),
(317, 279, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-11 13:03:11', '2022-02-11 13:03:19'),
(318, 280, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:03:23', '2022-02-11 13:03:23'),
(319, 282, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:03:29', '2022-02-11 13:03:34'),
(320, 282, 7, 'other_lembur,other_approval_lembur,other_report_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:03:29', '2022-02-11 13:03:39'),
(321, 283, 6, 'ga_katering,ga_vendor_katering,ga_snack_meeting,ga_meeting_rooms,ga_meeting_room_reservation,ga_meeting_room_report,ga_meeting_room_monitoring,ga_vehicle,ga_vehicles_reservation,ga_vehicles_report,ga_vehicle_monitoring', 'ACTIVE', 1, 1, '2022-02-11 13:03:42', '2022-02-11 13:03:52'),
(322, 284, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-11 13:03:56', '2022-02-11 13:04:03'),
(323, 285, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:04:05', '2022-02-11 13:04:05'),
(324, 286, 8, '', 'INACTIVE', 1, 1, '2022-02-11 13:05:53', '2022-02-11 13:06:02'),
(325, 287, 6, '', 'INACTIVE', 1, 1, '2022-02-11 13:06:15', '2022-02-11 13:06:18'),
(326, 288, 3, 'hr_karyawan,hr_data_karyawan,hr_data_gaji,hr_data_gaji_atasan,hr_master_kepegawaian,hr_data_departemen,hr_data_jabatan,hr_data_divisi,hr_data_training,hr_data_sub_departemen,hr_libur_nasional,hr_data_libur_nasional,hr_lembur,hr_report_lembur,hr_verified_lembur,hr_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:06:45', '2022-02-11 13:06:59'),
(327, 289, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:07:01', '2022-02-11 13:07:07'),
(328, 289, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:07:02', '2022-02-11 13:07:52'),
(329, 290, 3, 'hr_karyawan,hr_data_karyawan,hr_data_gaji,hr_data_gaji_atasan,hr_master_kepegawaian,hr_data_departemen,hr_data_jabatan,hr_data_divisi,hr_data_training,hr_data_sub_departemen,hr_libur_nasional,hr_data_libur_nasional,hr_lembur,hr_report_lembur,hr_verified_lembur,hr_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:08:24', '2022-02-11 13:08:36'),
(330, 291, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:08:51', '2022-02-11 13:08:56'),
(331, 291, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:08:51', '2022-02-11 13:09:03'),
(332, 292, 3, 'hr_karyawan,hr_data_karyawan,hr_data_gaji,hr_data_gaji_atasan,hr_master_kepegawaian,hr_data_departemen,hr_data_jabatan,hr_data_divisi,hr_data_training,hr_data_sub_departemen,hr_libur_nasional,hr_data_libur_nasional,hr_lembur,hr_report_lembur,hr_verified_lembur,hr_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:09:59', '2022-02-11 13:10:10'),
(333, 293, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:10:13', '2022-02-11 13:10:18'),
(334, 293, 7, 'other_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:10:13', '2022-02-11 13:10:24'),
(335, 294, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 13:10:29', '2022-02-11 13:10:36'),
(336, 295, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:10:38', '2022-02-11 13:10:38'),
(337, 296, 3, 'hr_karyawan,hr_data_karyawan,hr_data_gaji,hr_data_gaji_atasan,hr_master_kepegawaian,hr_data_departemen,hr_data_jabatan,hr_data_divisi,hr_data_training,hr_data_sub_departemen,hr_libur_nasional,hr_data_libur_nasional,hr_lembur,hr_report_lembur,hr_verified_lembur,hr_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:10:43', '2022-02-11 13:10:54'),
(338, 297, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:10:56', '2022-02-11 13:11:01'),
(339, 297, 7, 'other_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:10:56', '2022-02-11 13:11:05'),
(340, 298, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 13:11:08', '2022-02-11 13:11:13'),
(341, 299, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:11:16', '2022-02-11 13:11:16'),
(342, 300, 3, 'hr_karyawan,hr_data_karyawan,hr_data_gaji,hr_data_gaji_atasan,hr_master_kepegawaian,hr_data_departemen,hr_data_jabatan,hr_data_divisi,hr_data_training,hr_data_sub_departemen,hr_libur_nasional,hr_data_libur_nasional,hr_lembur,hr_report_lembur,hr_verified_lembur,hr_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:11:37', '2022-02-11 13:11:49'),
(343, 301, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:11:53', '2022-02-11 13:12:18'),
(344, 301, 7, 'other_lembur,other_approval_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:11:53', '2022-02-11 13:12:23'),
(345, 303, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 13:12:28', '2022-02-11 13:12:34'),
(346, 304, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:12:36', '2022-02-11 13:12:36'),
(347, 307, 3, 'hr_karyawan,hr_data_karyawan,hr_data_gaji,hr_data_gaji_atasan,hr_master_kepegawaian,hr_data_departemen,hr_data_jabatan,hr_data_divisi,hr_data_training,hr_data_sub_departemen,hr_libur_nasional,hr_data_libur_nasional,hr_lembur,hr_report_lembur,hr_verified_lembur,hr_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:12:54', '2022-02-11 13:13:05'),
(348, 309, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:13:08', '2022-02-11 13:13:14'),
(349, 309, 7, 'other_lembur,other_approval_lembur,other_pengajuan_revisi_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:13:09', '2022-02-11 13:13:21'),
(350, 310, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation', 'ACTIVE', 1, 1, '2022-02-11 13:13:25', '2022-02-11 13:13:30'),
(351, 311, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:13:32', '2022-02-11 13:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `users_accordion_dept`
--

CREATE TABLE `users_accordion_dept` (
  `id` int(11) NOT NULL,
  `user_menu_id` int(11) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `trees` text NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_accordion_dept`
--

INSERT INTO `users_accordion_dept` (`id`, `user_menu_id`, `acc_id`, `trees`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-08 00:37:10', '2022-02-11 13:17:48'),
(2, 2, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-08 00:45:00', '2022-02-10 14:59:12'),
(3, 2, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-10 14:58:58', '2022-02-10 14:59:05'),
(4, 3, 6, 'ga_meeting_rooms,ga_meeting_room_report,ga_meeting_room_monitoring,ga_vehicle,ga_vehicles_report,ga_vehicle_monitoring', 'ACTIVE', 1, 1, '2022-02-10 14:59:26', '2022-02-10 14:59:39'),
(5, 4, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine,dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-10 14:59:46', '2022-02-10 15:00:12'),
(6, 5, 9, '', 'ACTIVE', 1, 1, '2022-02-10 15:00:18', '2022-02-10 15:00:18'),
(7, 7, 3, 'hr_karyawan,hr_data_karyawan', 'ACTIVE', 1, 1, '2022-02-10 15:00:31', '2022-02-10 15:01:22'),
(8, 8, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:14:27', '2022-02-11 13:14:32'),
(9, 8, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:14:27', '2022-02-11 13:14:37'),
(10, 9, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:14:42', '2022-02-11 13:14:46'),
(11, 9, 7, 'other_lembur,other_input_lembur,other_approval_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:14:42', '2022-02-11 13:14:51'),
(12, 11, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:15:07', '2022-02-11 13:15:12'),
(13, 11, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:15:13', '2022-02-11 13:15:30'),
(14, 12, 3, 'hr_karyawan,hr_data_karyawan', 'ACTIVE', 1, 1, '2022-02-11 13:15:34', '2022-02-11 13:15:49'),
(15, 14, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine,dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-11 13:16:01', '2022-02-11 13:16:11'),
(16, 15, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:16:13', '2022-02-11 13:16:13'),
(17, 17, 3, 'hr_karyawan,hr_data_karyawan', 'ACTIVE', 1, 1, '2022-02-11 13:16:29', '2022-02-11 13:16:32'),
(18, 18, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:16:36', '2022-02-11 13:16:41'),
(19, 18, 7, 'other_lembur,other_approval_lembur,other_report_lembur', 'ACTIVE', 1, 1, '2022-02-11 13:16:36', '2022-02-11 13:16:49'),
(20, 19, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine,dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-11 13:16:53', '2022-02-11 13:17:13'),
(21, 20, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:17:15', '2022-02-11 13:17:15'),
(22, 22, 3, 'hr_karyawan,hr_data_karyawan', 'ACTIVE', 1, 1, '2022-02-11 13:17:25', '2022-02-11 13:17:36'),
(23, 1, 4, 'other_ruang_meeting,other_reservasi_ruang_meeting,other_daftar_ruang_meeting,other_kendaraan_inventaris,other_reservasi_kendaraan,other_daftar_kendaraan', 'ACTIVE', 1, 1, '2022-02-11 13:17:40', '2022-02-11 13:17:45'),
(24, 23, 8, 'dashboard_overtime_tree,dashboard_overtime_summary,dashboard_overtime_summary_provider,dashboard_overtime_comparation,dashboard_overtime_machine,dashboard_meeting_room,dashboard_meeting_room_summary,dashboard_vehicle,dashboard_vehicle_summary', 'ACTIVE', 1, 1, '2022-02-11 13:17:52', '2022-02-11 13:17:58'),
(25, 24, 9, '', 'ACTIVE', 1, 1, '2022-02-11 13:18:00', '2022-02-11 13:18:00');

-- --------------------------------------------------------

--
-- Table structure for table `users_menu`
--

CREATE TABLE `users_menu` (
  `id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_menu`
--

INSERT INTO `users_menu` (`id`, `sub_id`, `rank_id`, `menu_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 14, 4, 4, 'ACTIVE', 1, 1, '2022-02-07 23:05:09', '2022-02-07 23:05:09'),
(2, 14, 3, 4, 'ACTIVE', 1, 1, '2022-02-07 23:05:22', '2022-02-07 23:05:22'),
(3, 1, 5, 4, 'ACTIVE', 1, 1, '2022-02-07 23:21:36', '2022-02-07 23:21:36'),
(4, 1, 6, 4, 'ACTIVE', 1, 1, '2022-02-07 23:21:44', '2022-02-07 23:21:44'),
(5, 5, 7, 4, 'ACTIVE', 1, 1, '2022-02-07 23:25:38', '2022-02-07 23:25:38'),
(6, 1, 4, 4, 'ACTIVE', 1, 1, '2022-02-08 00:18:30', '2022-02-08 00:18:30'),
(7, 1, 3, 4, 'ACTIVE', 1, 1, '2022-02-08 00:18:37', '2022-02-08 00:18:37'),
(8, 1, 9, 9, 'INACTIVE', 1, 1, '2022-02-10 14:44:03', '2022-02-10 14:53:03'),
(9, 1, 9, 4, 'ACTIVE', 1, 1, '2022-02-10 14:46:28', '2022-02-10 14:46:28'),
(10, 1, 9, 2, 'INACTIVE', 1, 1, '2022-02-10 14:48:11', '2022-02-10 14:48:31'),
(11, 1, 8, 4, 'ACTIVE', 1, 1, '2022-02-10 14:49:19', '2022-02-10 14:49:19'),
(12, 1, 8, 9, 'INACTIVE', 1, 1, '2022-02-10 14:51:03', '2022-02-10 14:52:11'),
(13, 1, 7, 4, 'ACTIVE', 1, 1, '2022-02-10 14:53:15', '2022-02-10 14:53:15'),
(14, 1, 6, 9, 'ACTIVE', 1, 1, '2022-02-10 14:54:54', '2022-02-10 14:54:54'),
(15, 1, 6, 10, 'ACTIVE', 1, 1, '2022-02-10 14:55:05', '2022-02-10 14:55:05'),
(16, 1, 6, 3, 'ACTIVE', 1, 1, '2022-02-10 14:55:16', '2022-02-10 14:55:16'),
(17, 1, 5, 9, 'ACTIVE', 1, 1, '2022-02-10 14:55:53', '2022-02-10 14:55:53'),
(18, 1, 5, 10, 'ACTIVE', 1, 1, '2022-02-10 14:56:04', '2022-02-10 14:56:04'),
(19, 1, 5, 3, 'ACTIVE', 1, 1, '2022-02-10 14:56:05', '2022-02-10 14:56:05'),
(20, 1, 4, 9, 'ACTIVE', 1, 1, '2022-02-10 14:56:33', '2022-02-10 14:56:33'),
(21, 1, 4, 10, 'ACTIVE', 1, 1, '2022-02-10 14:57:01', '2022-02-10 14:57:01'),
(22, 1, 4, 3, 'ACTIVE', 1, 1, '2022-02-10 14:57:16', '2022-02-10 14:57:16'),
(23, 1, 3, 3, 'ACTIVE', 1, 1, '2022-02-10 14:57:22', '2022-02-10 14:57:22'),
(24, 1, 3, 9, 'ACTIVE', 1, 1, '2022-02-10 14:58:08', '2022-02-10 14:58:08'),
(25, 1, 3, 10, 'ACTIVE', 1, 1, '2022-02-10 14:58:18', '2022-02-10 14:58:18'),
(26, 11, 9, 2, 'ACTIVE', 1, 1, '2022-02-10 15:02:26', '2022-02-10 15:02:26'),
(27, 11, 9, 3, 'INACTIVE', 1, 1, '2022-02-10 15:02:45', '2022-02-11 13:04:25'),
(28, 11, 9, 4, 'ACTIVE', 1, 1, '2022-02-10 15:02:47', '2022-02-10 15:02:47'),
(29, 12, 7, 5, 'ACTIVE', 1, 1, '2022-02-10 19:25:47', '2022-02-10 19:25:47'),
(30, 2, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 10:07:48', '2022-02-11 10:07:48'),
(31, 2, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 10:08:27', '2022-02-11 10:08:27'),
(32, 2, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 10:09:25', '2022-02-11 10:09:25'),
(33, 2, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 10:10:01', '2022-02-11 10:10:01'),
(34, 2, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 10:10:06', '2022-02-11 10:10:06'),
(35, 2, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 10:10:30', '2022-02-11 10:10:30'),
(36, 2, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 10:10:45', '2022-02-11 10:10:45'),
(37, 2, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 10:10:54', '2022-02-11 10:10:54'),
(38, 2, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 10:10:55', '2022-02-11 10:10:55'),
(39, 2, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 10:11:14', '2022-02-11 10:11:14'),
(40, 2, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 10:11:26', '2022-02-11 10:11:26'),
(41, 2, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 10:11:32', '2022-02-11 10:11:32'),
(42, 2, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 10:11:33', '2022-02-11 10:11:33'),
(43, 2, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 10:12:02', '2022-02-11 10:12:02'),
(44, 2, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 10:12:15', '2022-02-11 10:12:15'),
(45, 2, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 10:12:22', '2022-02-11 10:12:22'),
(46, 2, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 10:12:22', '2022-02-11 10:12:22'),
(47, 2, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 10:12:41', '2022-02-11 10:12:41'),
(48, 2, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 10:12:49', '2022-02-11 10:12:49'),
(49, 3, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 10:13:01', '2022-02-11 10:13:01'),
(50, 3, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 10:13:26', '2022-02-11 10:13:26'),
(51, 3, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 10:13:44', '2022-02-11 10:13:44'),
(52, 3, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 10:14:04', '2022-02-11 10:14:04'),
(53, 3, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 10:14:04', '2022-02-11 10:14:04'),
(54, 3, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 10:14:07', '2022-02-11 10:14:08'),
(55, 3, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 10:14:09', '2022-02-11 10:14:09'),
(56, 3, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 10:14:44', '2022-02-11 10:14:44'),
(57, 3, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 10:14:45', '2022-02-11 10:14:45'),
(58, 3, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 10:15:03', '2022-02-11 10:15:03'),
(59, 3, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 10:15:16', '2022-02-11 10:15:16'),
(60, 3, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 10:16:21', '2022-02-11 10:16:21'),
(61, 3, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 10:16:22', '2022-02-11 10:16:22'),
(62, 3, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 10:16:53', '2022-02-11 10:16:53'),
(63, 3, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 10:17:01', '2022-02-11 10:17:01'),
(64, 3, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 10:17:05', '2022-02-11 10:17:05'),
(65, 3, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 10:17:06', '2022-02-11 10:17:06'),
(66, 3, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 10:17:26', '2022-02-11 10:17:26'),
(67, 3, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 10:17:34', '2022-02-11 10:17:34'),
(68, 4, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 10:17:43', '2022-02-11 10:17:43'),
(69, 4, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 10:18:05', '2022-02-11 10:18:05'),
(70, 4, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 10:18:34', '2022-02-11 10:18:34'),
(71, 4, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 10:18:50', '2022-02-11 10:18:50'),
(72, 4, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 10:18:51', '2022-02-11 10:18:51'),
(73, 4, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 10:19:09', '2022-02-11 10:19:09'),
(74, 4, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 10:19:18', '2022-02-11 10:19:18'),
(75, 4, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 10:19:23', '2022-02-11 10:19:23'),
(76, 4, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 10:19:24', '2022-02-11 10:19:24'),
(77, 4, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 10:19:39', '2022-02-11 10:19:39'),
(78, 4, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 10:19:48', '2022-02-11 10:19:48'),
(79, 4, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 10:19:52', '2022-02-11 10:19:52'),
(80, 4, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 10:19:53', '2022-02-11 10:19:53'),
(81, 4, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 10:20:20', '2022-02-11 10:20:20'),
(82, 4, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 10:20:27', '2022-02-11 10:20:27'),
(83, 4, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 10:20:34', '2022-02-11 10:20:34'),
(84, 4, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 10:20:35', '2022-02-11 10:20:35'),
(85, 4, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 10:21:10', '2022-02-11 10:21:10'),
(86, 4, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 10:21:18', '2022-02-11 10:21:18'),
(87, 5, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 10:21:42', '2022-02-11 10:21:42'),
(88, 5, 9, 8, 'ACTIVE', 1, 1, '2022-02-11 10:22:00', '2022-02-11 10:22:00'),
(89, 5, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 10:23:09', '2022-02-11 10:23:09'),
(90, 5, 8, 8, 'ACTIVE', 1, 1, '2022-02-11 10:23:29', '2022-02-11 10:23:29'),
(91, 5, 7, 8, 'ACTIVE', 1, 1, '2022-02-11 10:24:03', '2022-02-11 10:24:03'),
(92, 5, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 10:24:19', '2022-02-11 10:24:19'),
(93, 5, 6, 8, 'INACTIVE', 1, 1, '2022-02-11 10:24:41', '2022-02-11 10:24:52'),
(94, 5, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 10:24:44', '2022-02-11 10:24:44'),
(95, 5, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 10:24:53', '2022-02-11 10:24:53'),
(96, 5, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 10:24:54', '2022-02-11 10:24:54'),
(97, 5, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 10:25:13', '2022-02-11 10:25:13'),
(98, 5, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 10:25:14', '2022-02-11 10:25:14'),
(99, 5, 5, 8, 'INACTIVE', 1, 1, '2022-02-11 10:25:27', '2022-02-11 10:25:31'),
(100, 5, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 10:25:45', '2022-02-11 10:25:45'),
(101, 5, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 10:26:08', '2022-02-11 10:26:08'),
(102, 5, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 10:26:30', '2022-02-11 10:26:30'),
(103, 5, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 10:26:31', '2022-02-11 10:26:31'),
(104, 5, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 10:26:55', '2022-02-11 10:26:55'),
(105, 5, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 10:27:03', '2022-02-11 10:27:03'),
(106, 5, 4, 8, 'INACTIVE', 1, 1, '2022-02-11 10:27:14', '2022-02-11 10:27:34'),
(107, 5, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 10:27:45', '2022-02-11 10:27:45'),
(108, 5, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 10:27:45', '2022-02-11 10:27:45'),
(109, 5, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 10:28:06', '2022-02-11 10:28:06'),
(110, 5, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 10:28:14', '2022-02-11 10:28:14'),
(111, 6, 9, 3, 'INACTIVE', 1, 1, '2022-02-11 10:30:14', '2022-02-11 10:30:15'),
(112, 6, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 10:30:17', '2022-02-11 10:30:17'),
(113, 6, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 10:30:38', '2022-02-11 10:30:38'),
(114, 6, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 10:30:53', '2022-02-11 10:30:53'),
(115, 6, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 10:31:12', '2022-02-11 10:31:12'),
(116, 6, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 10:31:13', '2022-02-11 10:31:13'),
(117, 6, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 10:31:27', '2022-02-11 10:31:27'),
(118, 6, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 10:32:00', '2022-02-11 10:32:00'),
(119, 6, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 10:32:00', '2022-02-11 10:32:00'),
(120, 6, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 10:32:28', '2022-02-11 10:32:28'),
(121, 6, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 10:32:39', '2022-02-11 10:32:39'),
(122, 6, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 10:32:44', '2022-02-11 10:32:44'),
(123, 6, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 10:32:45', '2022-02-11 10:32:45'),
(124, 6, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 10:32:59', '2022-02-11 10:32:59'),
(125, 6, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 10:33:08', '2022-02-11 10:33:08'),
(126, 6, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 10:33:15', '2022-02-11 10:33:15'),
(127, 6, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 10:33:15', '2022-02-11 10:33:15'),
(128, 6, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 10:33:29', '2022-02-11 10:33:29'),
(129, 6, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 10:33:36', '2022-02-11 10:33:36'),
(130, 7, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 10:33:49', '2022-02-11 10:33:49'),
(131, 7, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 10:34:04', '2022-02-11 10:34:04'),
(132, 7, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 10:34:39', '2022-02-11 10:34:39'),
(133, 7, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 10:34:59', '2022-02-11 10:34:59'),
(134, 7, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 10:34:59', '2022-02-11 10:34:59'),
(135, 7, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 10:35:14', '2022-02-11 10:35:14'),
(136, 7, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 10:35:22', '2022-02-11 10:35:22'),
(137, 7, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 10:35:28', '2022-02-11 10:35:28'),
(138, 7, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 10:35:29', '2022-02-11 10:35:29'),
(139, 7, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 10:35:49', '2022-02-11 10:35:49'),
(140, 7, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 10:35:58', '2022-02-11 10:35:58'),
(141, 7, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 10:36:02', '2022-02-11 10:36:02'),
(142, 7, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 10:36:03', '2022-02-11 10:36:03'),
(143, 7, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 10:36:19', '2022-02-11 10:36:19'),
(144, 7, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 10:36:27', '2022-02-11 10:36:27'),
(145, 7, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 10:36:35', '2022-02-11 10:36:35'),
(146, 7, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 10:36:35', '2022-02-11 10:36:35'),
(147, 7, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 10:36:51', '2022-02-11 10:36:51'),
(148, 7, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 10:36:59', '2022-02-11 10:36:59'),
(149, 15, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 10:37:10', '2022-02-11 10:37:10'),
(150, 15, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 10:37:28', '2022-02-11 10:37:28'),
(151, 15, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 10:37:45', '2022-02-11 10:37:45'),
(152, 15, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 10:38:19', '2022-02-11 10:38:19'),
(153, 15, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 10:38:19', '2022-02-11 10:38:19'),
(154, 15, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 10:38:33', '2022-02-11 10:38:33'),
(155, 15, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 10:38:40', '2022-02-11 10:38:40'),
(156, 15, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 10:40:14', '2022-02-11 10:40:14'),
(157, 15, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 10:40:14', '2022-02-11 10:40:14'),
(158, 15, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 10:40:39', '2022-02-11 10:40:39'),
(159, 15, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 10:40:48', '2022-02-11 10:40:48'),
(160, 15, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 10:40:53', '2022-02-11 10:40:53'),
(161, 15, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 10:40:54', '2022-02-11 10:40:54'),
(162, 15, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 10:41:02', '2022-02-11 10:41:02'),
(163, 15, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 10:41:11', '2022-02-11 10:41:11'),
(164, 15, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 10:41:16', '2022-02-11 10:41:16'),
(165, 15, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 10:41:16', '2022-02-11 10:41:16'),
(166, 15, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 10:41:29', '2022-02-11 10:41:29'),
(167, 15, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 10:41:39', '2022-02-11 10:41:39'),
(168, 8, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 10:45:59', '2022-02-11 10:45:59'),
(169, 8, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 10:46:20', '2022-02-11 10:46:20'),
(170, 8, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 10:46:35', '2022-02-11 10:46:35'),
(171, 8, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 10:46:49', '2022-02-11 10:46:49'),
(172, 8, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 10:46:50', '2022-02-11 10:46:50'),
(173, 8, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 10:47:04', '2022-02-11 10:47:04'),
(174, 8, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 10:47:12', '2022-02-11 10:47:12'),
(175, 8, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 10:47:18', '2022-02-11 10:47:18'),
(176, 8, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 10:47:19', '2022-02-11 10:47:19'),
(177, 8, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 10:47:31', '2022-02-11 10:47:31'),
(178, 8, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 10:47:38', '2022-02-11 10:47:38'),
(179, 8, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 10:47:47', '2022-02-11 10:47:47'),
(180, 8, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 10:47:47', '2022-02-11 10:47:47'),
(181, 8, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 10:48:03', '2022-02-11 10:48:03'),
(182, 8, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 10:48:12', '2022-02-11 10:48:12'),
(183, 8, 4, 5, 'INACTIVE', 1, 1, '2022-02-11 10:48:22', '2022-02-11 10:48:30'),
(184, 8, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 10:48:35', '2022-02-11 10:48:35'),
(185, 8, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 10:48:36', '2022-02-11 10:48:36'),
(186, 8, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 10:48:49', '2022-02-11 10:48:49'),
(187, 8, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 10:48:56', '2022-02-11 10:48:56'),
(188, 9, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 10:53:23', '2022-02-11 10:53:23'),
(189, 9, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 10:53:39', '2022-02-11 10:53:39'),
(190, 9, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 10:53:52', '2022-02-11 10:53:52'),
(191, 9, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 10:54:07', '2022-02-11 10:54:07'),
(192, 9, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 10:54:08', '2022-02-11 10:54:08'),
(193, 9, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 10:54:12', '2022-02-11 10:54:12'),
(194, 9, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 10:54:13', '2022-02-11 10:54:13'),
(195, 9, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 10:54:50', '2022-02-11 10:54:50'),
(196, 9, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 10:54:51', '2022-02-11 10:54:51'),
(197, 9, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 10:55:25', '2022-02-11 10:55:25'),
(198, 9, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 10:55:34', '2022-02-11 10:55:34'),
(199, 9, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 10:56:30', '2022-02-11 10:56:30'),
(200, 9, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 10:56:30', '2022-02-11 10:56:30'),
(201, 9, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 10:59:13', '2022-02-11 10:59:13'),
(202, 9, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 10:59:21', '2022-02-11 10:59:21'),
(203, 9, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 10:59:26', '2022-02-11 10:59:26'),
(204, 9, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 10:59:26', '2022-02-11 10:59:26'),
(205, 9, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 10:59:39', '2022-02-11 10:59:39'),
(206, 9, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 10:59:46', '2022-02-11 10:59:46'),
(207, 10, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 10:59:59', '2022-02-11 10:59:59'),
(208, 10, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 11:00:19', '2022-02-11 11:00:19'),
(209, 10, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 11:00:31', '2022-02-11 11:00:31'),
(210, 10, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 11:04:10', '2022-02-11 11:04:10'),
(211, 10, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 11:04:11', '2022-02-11 11:04:11'),
(212, 10, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 11:04:30', '2022-02-11 11:04:30'),
(213, 10, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 11:04:30', '2022-02-11 11:04:30'),
(214, 10, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 11:04:44', '2022-02-11 11:04:44'),
(215, 10, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 11:04:44', '2022-02-11 11:04:44'),
(216, 10, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 11:05:26', '2022-02-11 11:05:26'),
(217, 10, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 11:05:27', '2022-02-11 11:05:27'),
(218, 10, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 11:05:38', '2022-02-11 11:05:38'),
(219, 10, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 11:05:38', '2022-02-11 11:05:38'),
(220, 10, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 11:05:51', '2022-02-11 11:05:51'),
(221, 10, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 11:05:52', '2022-02-11 11:05:52'),
(222, 10, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 11:06:03', '2022-02-11 11:06:03'),
(223, 10, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 11:06:04', '2022-02-11 11:06:04'),
(224, 10, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 11:06:21', '2022-02-11 11:06:21'),
(225, 10, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 11:06:23', '2022-02-11 11:06:23'),
(226, 13, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 11:06:40', '2022-02-11 11:06:40'),
(227, 13, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 11:06:57', '2022-02-11 11:06:57'),
(228, 13, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 11:07:11', '2022-02-11 11:07:11'),
(229, 13, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 11:07:25', '2022-02-11 11:07:25'),
(230, 13, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 11:07:26', '2022-02-11 11:07:26'),
(231, 13, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 11:07:48', '2022-02-11 11:07:48'),
(232, 13, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 11:07:49', '2022-02-11 11:07:49'),
(233, 13, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 11:08:08', '2022-02-11 11:08:08'),
(234, 13, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 11:08:09', '2022-02-11 11:08:09'),
(235, 13, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 11:08:21', '2022-02-11 11:08:21'),
(236, 13, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 11:08:29', '2022-02-11 11:08:29'),
(237, 13, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 11:08:33', '2022-02-11 11:08:33'),
(238, 13, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 11:08:33', '2022-02-11 11:08:33'),
(239, 13, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 11:08:55', '2022-02-11 11:08:55'),
(240, 13, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 11:08:55', '2022-02-11 11:08:55'),
(241, 13, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 11:09:07', '2022-02-11 11:09:07'),
(242, 13, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 11:09:07', '2022-02-11 11:09:07'),
(243, 13, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 11:09:20', '2022-02-11 11:09:20'),
(244, 13, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 11:09:27', '2022-02-11 11:09:27'),
(245, 14, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 11:12:59', '2022-02-11 11:12:59'),
(246, 14, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 11:13:17', '2022-02-11 11:13:17'),
(247, 14, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 11:13:29', '2022-02-11 11:13:29'),
(248, 14, 6, 3, 'INACTIVE', 1, 1, '2022-02-11 11:13:48', '2022-02-11 11:14:34'),
(249, 14, 6, 4, 'INACTIVE', 1, 1, '2022-02-11 11:13:49', '2022-02-11 11:14:33'),
(250, 14, 6, 9, 'INACTIVE', 1, 1, '2022-02-11 11:14:06', '2022-02-11 11:14:18'),
(251, 14, 6, 10, 'INACTIVE', 1, 1, '2022-02-11 11:14:08', '2022-02-11 11:14:19'),
(252, 14, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 11:14:43', '2022-02-11 11:14:43'),
(253, 14, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 11:14:56', '2022-02-11 11:14:56'),
(254, 14, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 11:15:04', '2022-02-11 11:15:04'),
(255, 14, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 11:15:08', '2022-02-11 11:15:08'),
(256, 14, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 11:15:18', '2022-02-11 11:15:18'),
(257, 14, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 11:15:25', '2022-02-11 11:15:25'),
(258, 12, 9, 5, 'ACTIVE', 1, 1, '2022-02-11 12:56:30', '2022-02-11 12:56:30'),
(259, 12, 9, 4, 'ACTIVE', 1, 1, '2022-02-11 12:56:32', '2022-02-11 12:56:32'),
(260, 12, 9, 9, 'ACTIVE', 1, 1, '2022-02-11 12:57:57', '2022-02-11 12:58:00'),
(261, 12, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 12:58:50', '2022-02-11 12:58:50'),
(262, 12, 8, 5, 'ACTIVE', 1, 1, '2022-02-11 12:58:52', '2022-02-11 12:58:52'),
(263, 12, 8, 9, 'ACTIVE', 1, 1, '2022-02-11 12:59:33', '2022-02-11 12:59:33'),
(264, 12, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 12:59:46', '2022-02-11 12:59:46'),
(265, 12, 7, 9, 'ACTIVE', 1, 1, '2022-02-11 13:00:15', '2022-02-11 13:00:15'),
(266, 12, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 13:00:25', '2022-02-11 13:00:25'),
(267, 12, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 13:00:26', '2022-02-11 13:00:26'),
(268, 12, 6, 5, 'ACTIVE', 1, 1, '2022-02-11 13:00:51', '2022-02-11 13:00:51'),
(269, 12, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 13:01:29', '2022-02-11 13:01:29'),
(270, 12, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 13:01:30', '2022-02-11 13:01:30'),
(271, 12, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 13:01:51', '2022-02-11 13:01:51'),
(272, 12, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 13:01:52', '2022-02-11 13:01:52'),
(273, 12, 5, 5, 'ACTIVE', 1, 1, '2022-02-11 13:02:09', '2022-02-11 13:02:09'),
(274, 12, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 13:02:23', '2022-02-11 13:02:23'),
(275, 12, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 13:02:24', '2022-02-11 13:02:24'),
(276, 12, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 13:02:43', '2022-02-11 13:02:43'),
(277, 12, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 13:02:44', '2022-02-11 13:02:44'),
(278, 12, 4, 5, 'ACTIVE', 1, 1, '2022-02-11 13:02:44', '2022-02-11 13:02:44'),
(279, 12, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 13:03:10', '2022-02-11 13:03:10'),
(280, 12, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 13:03:20', '2022-02-11 13:03:20'),
(281, 12, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 13:03:27', '2022-02-11 13:03:27'),
(282, 12, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 13:03:27', '2022-02-11 13:03:27'),
(283, 12, 3, 5, 'ACTIVE', 1, 1, '2022-02-11 13:03:40', '2022-02-11 13:03:40'),
(284, 12, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 13:03:54', '2022-02-11 13:03:54'),
(285, 12, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 13:03:54', '2022-02-11 13:03:54'),
(286, 11, 9, 9, 'INACTIVE', 1, 1, '2022-02-11 13:05:52', '2022-02-11 13:06:03'),
(287, 11, 9, 5, 'INACTIVE', 1, 1, '2022-02-11 13:06:13', '2022-02-11 13:06:20'),
(288, 11, 8, 2, 'ACTIVE', 1, 1, '2022-02-11 13:06:42', '2022-02-11 13:06:42'),
(289, 11, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 13:06:43', '2022-02-11 13:06:43'),
(290, 11, 7, 2, 'ACTIVE', 1, 1, '2022-02-11 13:08:22', '2022-02-11 13:08:22'),
(291, 11, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 13:08:22', '2022-02-11 13:08:22'),
(292, 11, 6, 2, 'ACTIVE', 1, 1, '2022-02-11 13:09:56', '2022-02-11 13:09:56'),
(293, 11, 6, 4, 'ACTIVE', 1, 1, '2022-02-11 13:09:57', '2022-02-11 13:09:57'),
(294, 11, 6, 9, 'ACTIVE', 1, 1, '2022-02-11 13:10:26', '2022-02-11 13:10:26'),
(295, 11, 6, 10, 'ACTIVE', 1, 1, '2022-02-11 13:10:27', '2022-02-11 13:10:27'),
(296, 11, 5, 2, 'ACTIVE', 1, 1, '2022-02-11 13:10:41', '2022-02-11 13:10:41'),
(297, 11, 5, 4, 'ACTIVE', 1, 1, '2022-02-11 13:10:42', '2022-02-11 13:10:42'),
(298, 11, 5, 9, 'ACTIVE', 1, 1, '2022-02-11 13:11:07', '2022-02-11 13:11:07'),
(299, 11, 5, 10, 'ACTIVE', 1, 1, '2022-02-11 13:11:14', '2022-02-11 13:11:14'),
(300, 11, 4, 2, 'ACTIVE', 1, 1, '2022-02-11 13:11:28', '2022-02-11 13:11:28'),
(301, 11, 4, 4, 'ACTIVE', 1, 1, '2022-02-11 13:11:29', '2022-02-11 13:11:29'),
(302, 11, 4, 3, 'ACTIVE', 1, 1, '2022-02-11 13:11:30', '2022-02-11 13:11:30'),
(303, 11, 4, 9, 'ACTIVE', 1, 1, '2022-02-11 13:12:27', '2022-02-11 13:12:27'),
(304, 11, 4, 10, 'ACTIVE', 1, 1, '2022-02-11 13:12:27', '2022-02-11 13:12:27'),
(305, 11, 5, 3, 'ACTIVE', 1, 1, '2022-02-11 13:12:40', '2022-02-11 13:12:40'),
(306, 11, 6, 3, 'ACTIVE', 1, 1, '2022-02-11 13:12:44', '2022-02-11 13:12:44'),
(307, 11, 3, 2, 'ACTIVE', 1, 1, '2022-02-11 13:12:50', '2022-02-11 13:12:50'),
(308, 11, 3, 3, 'ACTIVE', 1, 1, '2022-02-11 13:12:50', '2022-02-11 13:12:50'),
(309, 11, 3, 4, 'ACTIVE', 1, 1, '2022-02-11 13:12:50', '2022-02-11 13:12:50'),
(310, 11, 3, 9, 'ACTIVE', 1, 1, '2022-02-11 13:13:22', '2022-02-11 13:13:22'),
(311, 11, 3, 10, 'ACTIVE', 1, 1, '2022-02-11 13:13:23', '2022-02-11 13:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `users_menu_dept`
--

CREATE TABLE `users_menu_dept` (
  `id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_menu_dept`
--

INSERT INTO `users_menu_dept` (`id`, `dept_id`, `rank_id`, `menu_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 4, 'ACTIVE', 1, 1, '2022-02-08 00:37:09', '2022-02-08 00:37:09'),
(2, 3, 1, 4, 'ACTIVE', 1, 1, '2022-02-08 00:44:59', '2022-02-08 00:44:59'),
(3, 3, 1, 5, 'ACTIVE', 1, 1, '2022-02-10 14:59:19', '2022-02-10 14:59:19'),
(4, 3, 1, 9, 'ACTIVE', 1, 1, '2022-02-10 14:59:44', '2022-02-10 14:59:44'),
(5, 3, 1, 10, 'ACTIVE', 1, 1, '2022-02-10 15:00:16', '2022-02-10 15:00:16'),
(6, 3, 1, 3, 'ACTIVE', 1, 1, '2022-02-10 15:00:24', '2022-02-10 15:00:24'),
(7, 3, 1, 2, 'ACTIVE', 1, 1, '2022-02-10 15:00:29', '2022-02-10 15:00:29'),
(8, 3, 8, 4, 'ACTIVE', 1, 1, '2022-02-11 13:14:21', '2022-02-11 13:14:21'),
(9, 3, 7, 4, 'ACTIVE', 1, 1, '2022-02-11 13:14:40', '2022-02-11 13:14:40'),
(10, 3, 2, 3, 'ACTIVE', 1, 1, '2022-02-11 13:15:02', '2022-02-11 13:15:02'),
(11, 3, 2, 4, 'ACTIVE', 1, 1, '2022-02-11 13:15:05', '2022-02-11 13:15:05'),
(12, 3, 2, 2, 'ACTIVE', 1, 1, '2022-02-11 13:15:33', '2022-02-11 13:15:33'),
(13, 3, 2, 5, 'INACTIVE', 1, 1, '2022-02-11 13:15:53', '2022-02-11 13:15:56'),
(14, 3, 2, 9, 'ACTIVE', 1, 1, '2022-02-11 13:15:59', '2022-02-11 13:15:59'),
(15, 3, 2, 10, 'ACTIVE', 1, 1, '2022-02-11 13:15:59', '2022-02-11 13:15:59'),
(16, 2, 2, 3, 'ACTIVE', 1, 1, '2022-02-11 13:16:27', '2022-02-11 13:16:27'),
(17, 2, 2, 2, 'ACTIVE', 1, 1, '2022-02-11 13:16:28', '2022-02-11 13:16:28'),
(18, 2, 2, 4, 'ACTIVE', 1, 1, '2022-02-11 13:16:35', '2022-02-11 13:16:35'),
(19, 2, 2, 9, 'ACTIVE', 1, 1, '2022-02-11 13:16:51', '2022-02-11 13:16:51'),
(20, 2, 2, 10, 'ACTIVE', 1, 1, '2022-02-11 13:16:52', '2022-02-11 13:16:52'),
(21, 1, 2, 3, 'ACTIVE', 1, 1, '2022-02-11 13:17:21', '2022-02-11 13:17:21'),
(22, 1, 2, 2, 'ACTIVE', 1, 1, '2022-02-11 13:17:22', '2022-02-11 13:17:22'),
(23, 1, 2, 9, 'ACTIVE', 1, 1, '2022-02-11 13:17:50', '2022-02-11 13:17:50'),
(24, 1, 2, 10, 'ACTIVE', 1, 1, '2022-02-11 13:17:51', '2022-02-11 13:17:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accordions`
--
ALTER TABLE `accordions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_trees`
--
ALTER TABLE `acc_trees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`,`name`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `pics`
--
ALTER TABLE `pics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `projects_link`
--
ALTER TABLE `projects_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source` (`source`);

--
-- Indexes for table `projects_task`
--
ALTER TABLE `projects_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_department_id` (`sub_department_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `temp_files`
--
ALTER TABLE `temp_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trees`
--
ALTER TABLE `trees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `password` (`password`);

--
-- Indexes for table `users_accordion`
--
ALTER TABLE `users_accordion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_accordion_dept`
--
ALTER TABLE `users_accordion_dept`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_menu`
--
ALTER TABLE `users_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_menu_dept`
--
ALTER TABLE `users_menu_dept`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accordions`
--
ALTER TABLE `accordions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `acc_trees`
--
ALTER TABLE `acc_trees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `email`
--
ALTER TABLE `email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pics`
--
ALTER TABLE `pics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `temp_files`
--
ALTER TABLE `temp_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trees`
--
ALTER TABLE `trees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users_accordion`
--
ALTER TABLE `users_accordion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=352;

--
-- AUTO_INCREMENT for table `users_accordion_dept`
--
ALTER TABLE `users_accordion_dept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users_menu`
--
ALTER TABLE `users_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;

--
-- AUTO_INCREMENT for table `users_menu_dept`
--
ALTER TABLE `users_menu_dept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
