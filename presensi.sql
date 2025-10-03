-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 08:10 AM
-- Server version: 10.4.32-MariaDB-log
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL,
  `nik` char(10) DEFAULT NULL,
  `tgl_presensi` date DEFAULT NULL,
  `jam_in` time DEFAULT NULL,
  `jam_out` time DEFAULT NULL,
  `foto_in` varchar(255) DEFAULT NULL,
  `foto_out` varchar(255) DEFAULT NULL,
  `lokasi_in` text DEFAULT NULL,
  `lokasi_out` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `nik`, `tgl_presensi`, `jam_in`, `jam_out`, `foto_in`, `foto_out`, `lokasi_in`, `lokasi_out`) VALUES
(1, '12345', '2025-09-28', '18:43:20', '18:47:30', '12345-2025-09-28-1759059800.png', '12345-2025-09-28-1759060050.png', '-7.5280604,108.5938124', '-7.5280604,108.5938124'),
(2, '455', '2025-09-28', '19:02:38', '19:02:47', '455-2025-09-28-1759060958.png', '455-2025-09-28-1759060967.png', '-7.5280604,108.5938124', '-7.5280604,108.5938124'),
(4, '12345', '2025-09-29', '07:29:00', '21:20:53', '12345-2025-09-29-in.png', '12345-2025-09-29-out.png', '-7.0025216,110.411776', '-7.5280604,108.5938124'),
(5, '455', '2025-09-29', '15:30:40', '15:30:57', '455-2025-09-29-in.png', '455-2025-09-29-out.png', '-7.0025216,110.411776', '-7.0025216,110.411776'),
(6, '455', '2025-09-30', '08:13:14', NULL, '455-2025-09-30-in.png', NULL, '-6.9992448,110.4084992', NULL),
(7, '12345', '2025-09-30', '11:29:53', '13:07:01', '12345-2025-09-30-in.png', '12345-2025-09-30-out.png', '-6.9992448,110.4084992', '-6.9992448,110.4084992');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
