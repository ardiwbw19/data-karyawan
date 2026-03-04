-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2026 at 07:37 AM
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
-- Database: `db_karyawan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jabatan`
--

CREATE TABLE `tbl_jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_jabatan`
--

INSERT INTO `tbl_jabatan` (`id_jabatan`, `nama_jabatan`) VALUES
(7, 'Manajer'),
(8, 'Sekretaris'),
(9, 'Bendahara'),
(10, 'Admin'),
(11, 'IT'),
(12, 'Staff');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_karyawan`
--

CREATE TABLE `tbl_karyawan` (
  `id` int(11) NOT NULL COMMENT 'ID Unik',
  `nama` varchar(100) NOT NULL COMMENT 'Nama Karyawan',
  `id_jabatan` int(11) NOT NULL COMMENT 'Jabatan',
  `alamat` text NOT NULL COMMENT 'Alamat',
  `foto` varchar(255) NOT NULL COMMENT 'Nama File Foto',
  `status` enum('active','inactive','','') NOT NULL COMMENT 'Status Kerja',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_karyawan`
--

INSERT INTO `tbl_karyawan` (`id`, `nama`, `id_jabatan`, `alamat`, `foto`, `status`, `created_at`, `updated_at`) VALUES
(7, 'Deni', 11, 'Sutoyo', '', 'active', '2026-03-03 09:07:04', '0000-00-00 00:00:00'),
(8, 'Ardi Wibowo', 11, 'Tangkiing', 'karyawan_20260303100746_cb8d5444.png', 'active', '2026-03-03 09:07:46', '0000-00-00 00:00:00'),
(10, 'Likha', 12, 'Panarung', '', 'active', '2026-03-03 09:08:21', '0000-00-00 00:00:00'),
(11, 'Anna', 7, 'Tangkiling', '', 'active', '2026-03-03 09:08:38', '0000-00-00 00:00:00'),
(12, 'Ilham', 9, 'Rajawali', '', 'active', '2026-03-03 09:09:01', '0000-00-00 00:00:00'),
(13, 'Annisa', 12, 'Banjarbaru', '', 'active', '2026-03-03 09:09:27', '0000-00-00 00:00:00'),
(14, 'Joko wi', 12, 'Kediaman Solo', '', 'active', '2026-03-03 09:11:02', '0000-00-00 00:00:00'),
(15, 'Bowo', 12, 'Tangerang', '', 'active', '2026-03-03 09:11:28', '0000-00-00 00:00:00'),
(16, 'Dedy', 8, 'Bandung', '', 'active', '2026-03-03 09:11:49', '0000-00-00 00:00:00'),
(17, 'Andy', 10, 'Seruyan', '', 'active', '2026-03-04 02:48:07', '0000-00-00 00:00:00'),
(18, 'deni siburian', 11, 'banjarmasin', '', 'active', '2026-03-04 06:17:11', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `tbl_karyawan`
--
ALTER TABLE `tbl_karyawan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jabatan` (`id_jabatan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_karyawan`
--
ALTER TABLE `tbl_karyawan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Unik', AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_karyawan`
--
ALTER TABLE `tbl_karyawan`
  ADD CONSTRAINT `tbl_karyawan_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `tbl_jabatan` (`id_jabatan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
