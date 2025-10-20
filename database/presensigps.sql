-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2025 at 07:11 AM
-- Server version: 10.4.28-MariaDB-log
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensigps`
--

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `kode_dept` int(11) NOT NULL,
  `nama_dept` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`kode_dept`, `nama_dept`) VALUES
(1, 'IT'),
(2, 'HRD'),
(3, 'Direktur');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `nik` char(10) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `jabatan` varchar(20) DEFAULT NULL,
  `no_hp` varchar(13) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kode_dept` int(11) NOT NULL,
  `password` varchar(250) DEFAULT NULL,
  `remember_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`nik`, `nama_lengkap`, `jabatan`, `no_hp`, `foto`, `kode_dept`, `password`, `remember_token`) VALUES
('0000000001', 'Admin', 'Admin', '08728887878', '0000000001.png', 3, '$2y$10$NAZmnhal5uZH0SFFTjSpn.zM5/0OurjgRlN/XcSvnKlMCBN35I3Uu', 'CDH86RsjdKaF5udCINFhqSUkOyiJ87GlPBVAJxuYsR65WaheqV9Yh7GpbMHX'),
('12345', 'Zikry', 'Head of IT', '082252127790', '12345.jpg', 1, '$2y$10$8NHqNXwemQG4vXHfHe7uiOV/xCzYHxqrqlU.EDBsCSvWNTwoqmVSe', ''),
('3432', 'Dwi', 'IT Consultant', '439043940', '3432.jpg', 1, '$2y$10$3eDBhB4HW8TxUGp.rS1mMuxz/mXv2EdqTXXzIlEIiMXDFfEBP8eSq', 'gglzWMsdB9SN4dmY3zN3kjZdrUvvR32W4JFrxQNRQerjgGJWG7kxy1i1kx80'),
('455', 'Raka Aditya Setyawan', 'Direktur Utama', '0843742290', '455.jpg', 3, '$2y$10$EZg/s/c7QWsil8HdvmiDfuiTzq.1VqT3zrjH1Uip4WfZDf8rK2tVG', ''),
('4738', 'DDDD', 'IBLIS', '676767', '4738.png', 2, '$2y$10$T9hMJXm1madSpyI0ZChi1uET6DXHNBlex1Y3.vu5kKYWi2Bb9wzYm', 'bhULnEMcbF5hHDXF4eDjbL1p76ayGcLBitXqmLzWoHmI6s5m0ThpUwgF6tKg'),
('55', 'DICKHEAD', 'DEWA', '111111', '55.jpg', 3, '$2y$10$X9LJLGm2nhG6jJbQug.HJujxNt9qv/U428me0KG6lFHLyV6UHvO7q', 'MRVJ2s0HEVgwfcf0RuJ6MNceLqUPCUQlEWJzlmW4ciR1ftkIQCpu30eRUIhp'),
('789', 'niggaer', 'Hubungan Masyarakat', '4309409', '789.png', 2, '$2y$10$Ta8rkofatiHkhVeext3sXeMtV3M4.rrBBjz5VyA9C1JnvAIBhehtK', '0iOYiiAWJRbHo6gsIUCa9OKKXBUvold492w4DT9UyYLUU7eFNkggjtzPiyMl'),
('8999', 'Yuyunn', 'IT', '3429380', '8999.jpg', 3, '$2y$10$kWTTD/Q6YbGP5jVlvkpzPONoNibIiKpc19MuBsBqGIchlWGeaHlV2', 'HDlUhG8uGfrrL7mIh80DFfQ89riSUqQ10eXUtt6IsyIbA1fm20RfwXm5vO84');

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi_lokasi`
--

CREATE TABLE `konfigurasi_lokasi` (
  `id` int(11) NOT NULL,
  `lokasi_kantor` varchar(225) NOT NULL,
  `radius` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konfigurasi_lokasi`
--

INSERT INTO `konfigurasi_lokasi` (`id`, `lokasi_kantor`, `radius`) VALUES
(1, '-7.005222794664115, 110.406529103442', 80);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `id` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `tgl_izin` date NOT NULL,
  `status` char(2) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `bukti_surat` varchar(255) DEFAULT NULL,
  `status_approved` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan_izin`
--

INSERT INTO `pengajuan_izin` (`id`, `nik`, `tgl_izin`, `status`, `keterangan`, `bukti_surat`, `status_approved`) VALUES
(12, '455', '2025-10-01', '1', 'Izin duluu', NULL, '1'),
(13, '455', '2025-10-01', '2', 'Batuk', NULL, '1'),
(14, '455', '2025-10-01', '3', 'Cutii', NULL, '1'),
(15, '455', '2025-10-02', '1', 'Touring jauh', NULL, '1'),
(16, '12345', '2025-10-06', '2', 'Sakit', NULL, '1'),
(17, '12345', '2025-10-09', '3', 'Pengen Cuti Liburan', NULL, '1'),
(18, '12345', '2025-10-10', '1', 'Izin keluar', NULL, '0'),
(19, '12345', '2025-10-11', '1', 'Izin keluar Kota', NULL, '0'),
(20, '12345', '2025-10-09', '2', 'Demam', NULL, '1'),
(21, '12345', '2025-10-10', '3', 'Pulang Kampung', NULL, '1'),
(22, '12345', '2025-10-08', '1', 'Acara Keluarga', NULL, '1'),
(23, '12345', '2025-10-13', '2', 'Demam', NULL, '0'),
(24, '12345', '2025-10-16', '2', 'Diare', NULL, '0'),
(25, '12345', '2025-10-15', '2', 'Eek', NULL, '0'),
(26, '12345', '2025-10-17', '2', 'Tidak Enak badan,kunjungan kerumah Sakit', NULL, '0'),
(27, '12345', '2025-10-17', '3', 'Ingin Liburan', NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(7, '12345', '2025-09-30', '11:29:53', '13:07:01', '12345-2025-09-30-in.png', '12345-2025-09-30-out.png', '-6.9992448,110.4084992', '-6.9992448,110.4084992'),
(8, '12345', '2025-10-01', '08:14:07', '19:51:13', '12345-2025-10-01-in.png', '12345-2025-10-01-out.png', '-6.9992448,110.4084992', '-6.3665237,106.8738909'),
(9, '455', '2025-10-01', '10:49:27', NULL, '455-2025-10-01-in.png', NULL, '-6.9992448,110.4084992', NULL),
(10, '455', '2025-10-02', '08:02:31', NULL, '455-2025-10-02-in.png', NULL, '-6.9992448,110.4084992', NULL),
(11, '12345', '2025-10-02', '09:23:43', NULL, '12345-2025-10-02-in.png', NULL, '-6.9992448,110.4084992', NULL),
(12, '12345', '2025-10-03', '16:38:54', '16:39:03', '12345-2025-10-03-in.png', '12345-2025-10-03-out.png', '-7.0025216,110.4150528', '-7.0025216,110.4150528'),
(13, '12345', '2025-10-06', '15:23:55', NULL, '12345-2025-10-06-in.png', NULL, '-7.004913,110.4066488', NULL),
(14, '12345', '2025-10-07', '10:22:18', '22:08:04', '12345-2025-10-07-in.png', '12345-2025-10-07-out.png', '-7.005356750000001,110.40666475', '-6.975702519780233,110.39062756117211'),
(15, '12345', '2025-10-08', '08:51:15', '15:20:24', '12345-2025-10-08-in.png', '12345-2025-10-08-out.png', '-7.005349559611617,110.40667845688567', '-7.005341764711562,110.40672683151635'),
(16, '12345', '2025-10-09', '09:38:39', '15:15:51', '12345-2025-10-09-in.png', '12345-2025-10-09-out.png', '-7.0053488764316425,110.40672418845607', '-7.005353185812699,110.4066530204935'),
(17, '3432', '2025-10-09', '09:51:19', '10:01:26', '3432-2025-10-09-in.png', '3432-2025-10-09-out.png', '-7.005342572357265,110.40672877953646', '-7.005345738793402,110.40668352007782'),
(18, '12345', '2025-10-10', '15:14:16', '16:23:51', '12345-2025-10-10-in.png', '12345-2025-10-10-out.png', '-7.005349325181797,110.4067177472067', '-7.005346415870683,110.4067197024247'),
(19, '12345', '2025-10-13', '08:57:57', '16:04:18', '12345-2025-10-13-in.png', '12345-2025-10-13-out.png', '-7.005346415870683,110.4067197024247', '-7.005352797622142,110.40666518956252'),
(20, '12345', '2025-10-14', '09:31:13', NULL, '12345-2025-10-14-in.png', NULL, '-7.005340557220899,110.40673019144438', NULL),
(21, '12345', '2025-10-15', '08:17:14', '16:03:52', '12345-2025-10-15-in.png', '12345-2025-10-15-out.png', '-7.0053492869720815,110.4067181968022', '-7.005349766392314,110.40672099475445'),
(22, '12345', '2025-10-16', '08:40:52', NULL, '12345-2025-10-16-in.png', NULL, '-7.005348692167095,110.40672056869023', NULL),
(23, '12345', '2025-10-17', '08:18:09', NULL, '12345-2025-10-17-in.png', NULL, '-7.005354934969853,110.40663415503876', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Raka', 'kara21@gmail.com', NULL, '$2y$10$EZg/s/c7QWsil8HdvmiDfuiTzq.1VqT3zrjH1Uip4WfZDf8rK2tVG', NULL, NULL, NULL),
(4, 'admin', 'admin@gmail.com', NULL, '$2y$10$rgTTxSzK8f0.h7FPIOiT6uAVZr0GtznSO80bHnexpfwy6p4iAEv/m', NULL, '2025-10-13 17:26:33', '2025-10-17 09:07:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`kode_dept`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `konfigurasi_lokasi`
--
ALTER TABLE `konfigurasi_lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departemen`
--
ALTER TABLE `departemen`
  MODIFY `kode_dept` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
