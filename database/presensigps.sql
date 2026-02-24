-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2026 at 03:42 AM
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
(3, 'Direktur'),
(4, 'Security');

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
('10101010', 'Zikry Dwi Maulana', 'Magang', '082252127790', '10101010.png', 1, '$2y$10$T2adAURBPcjAkJdonm3G8ucHS85wgbSpF816xkDR2p7zS54A6WR.y', '5Xyl00VQERe694tLOZ1qmcZVYK697wCvzcieMJFMONFXtJIIoHgeU9I3mWti'),
('102023', 'Zekere', 'Magang', '0822222', '102023_face.jpg', 2, '$2y$10$i30CVsxRVvxWptEy4NkmC.ybhXXX6vhumREoY0tQoUOEOZXpqX0GK', 'Ajk5rHoFoYnSliNXYCUiBfs5AtIpxZhJTkcA9vFd12B0ME48KTKtvT8h31aC'),
('3432', 'Dwi', 'IT Consultant', '439043940', '3432.jpg', 1, '$2y$10$3eDBhB4HW8TxUGp.rS1mMuxz/mXv2EdqTXXzIlEIiMXDFfEBP8eSq', 'gglzWMsdB9SN4dmY3zN3kjZdrUvvR32W4JFrxQNRQerjgGJWG7kxy1i1kx80'),
('455', 'Raka Aditya Setyawan', 'Direktur Utama', '0843742290', '455.jpg', 3, '$2y$10$EZg/s/c7QWsil8HdvmiDfuiTzq.1VqT3zrjH1Uip4WfZDf8rK2tVG', ''),
('455555', 'Zikry Dwi Maulana', 'Magang', '082252121212', '455555.jpg', 1, '$2y$10$V8w1Uzl9iWRqpUy.Ios7wuLcD9TUkxN5ag6Iu2q2oqgCc/eVM8Kx2', 'T0BgkwySr9Jnh5AWWwSrXFRdNaV6AEGtx3sFjXPJpXBpyH6LSNvgZKsmhrkS'),
('4738', 'DDDD', 'IBLIS', '676767', '4738.png', 2, '$2y$10$T9hMJXm1madSpyI0ZChi1uET6DXHNBlex1Y3.vu5kKYWi2Bb9wzYm', 'bhULnEMcbF5hHDXF4eDjbL1p76ayGcLBitXqmLzWoHmI6s5m0ThpUwgF6tKg'),
('55', 'DICKHEAD', 'DEWA', '111111', '55.jpg', 3, '$2y$10$X9LJLGm2nhG6jJbQug.HJujxNt9qv/U428me0KG6lFHLyV6UHvO7q', 'MRVJ2s0HEVgwfcf0RuJ6MNceLqUPCUQlEWJzlmW4ciR1ftkIQCpu30eRUIhp'),
('676767', 'Ibnu Setyawan', 'Anggota TU', '0899999999', '676767.jpg', 2, '$2y$10$neeFsaSEKEIaS/UNWMfytuuI5kTpWx.4O8qYinMzInJBNOshBnNBO', 'CHIgwTlLO0c69HEFBBeTWLFm8edoSN0UIGKuSA77smnvJvZA4f1qyXnuuZS2'),
('789', 'niggaer', 'Hubungan Masyarakat', '4309409', '789.png', 2, '$2y$10$Ta8rkofatiHkhVeext3sXeMtV3M4.rrBBjz5VyA9C1JnvAIBhehtK', '0iOYiiAWJRbHo6gsIUCa9OKKXBUvold492w4DT9UyYLUU7eFNkggjtzPiyMl'),
('888888', 'Ucup', 'magang', '082252127790', '888888.png', 4, '$2y$10$09c.M7TLI2DD8MuMoxDmge.gL8xtuvjDg4759lng0kLoSuyggeIZm', 'mLmlmq4JrgvAfisoDRV9bxv8fmjUeebBxlqaSLFH1tuuzfUqurNeC8gvAkWg'),
('8999', 'Yuyunn', 'IT', '3429380', '8999.jpg', 3, '$2y$10$kWTTD/Q6YbGP5jVlvkpzPONoNibIiKpc19MuBsBqGIchlWGeaHlV2', 'HDlUhG8uGfrrL7mIh80DFfQ89riSUqQ10eXUtt6IsyIbA1fm20RfwXm5vO84'),
('99999', 'TEST1', 'Magang', '099889898', NULL, 2, '$2y$10$HOR6PpjK92khIOk6Ik940..Dvzhv.YdpD2H0xZlxajjZaIIGccXTy', 'lO1t3mvntq395XBD08C8M6zeM38CBifeB3BtUKfOa2w07gpp029nJ3WLNotc');

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
(1, '-7.005222794664115, 110.406529103442', 70);

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
(18, '12345', '2025-10-10', '1', 'Izin keluar', NULL, '2'),
(19, '12345', '2025-10-11', '1', 'Izin keluar Kota', NULL, '1'),
(20, '12345', '2025-10-09', '2', 'Demam', NULL, '1'),
(21, '12345', '2025-10-10', '3', 'Pulang Kampung', NULL, '1'),
(22, '12345', '2025-10-08', '1', 'Acara Keluarga', NULL, '1'),
(23, '12345', '2025-10-13', '2', 'Demam', NULL, '2'),
(24, '12345', '2025-10-16', '2', 'Diare', NULL, '1'),
(25, '12345', '2025-10-15', '2', 'Eek', NULL, '1'),
(26, '12345', '2025-10-17', '2', 'Tidak Enak badan,kunjungan kerumah Sakit', NULL, '1'),
(27, '12345', '2025-10-17', '3', 'Ingin Liburan', NULL, '1'),
(28, '12345', '2025-11-07', '1', 'pp', '12345-20251107095624.jpg', '1'),
(29, '12345', '2025-11-18', '1', 'wee', '12345-20251117120932.jpg', '1'),
(37, '455555', '2026-01-29', '1', 'Selamat Pagi,Bapak Kepala Bidang Tata Laksana,Saya ingin mengajukan Cuti libur,pada Esok hari\r\n\r\nSelama 2 hari', '455555-20260128161512.png', '0'),
(38, '455555', '2026-01-28', '2', 'Selamat Siang,Maaf sebelumnya saya ingin memberikan informasi bahwa hari ini saya tidak dapat berangkat kantor,dikarenakan lagi sakit demam.\r\nDibawah ini merupakan surat dokter', '455555-20260128224710.png', '1');

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
  `lokasi_out` text DEFAULT NULL,
  `status_lokasi_in` varchar(255) DEFAULT NULL,
  `jarak_in` varchar(255) DEFAULT NULL,
  `status_lokasi_out` varchar(255) DEFAULT NULL,
  `jarak_out` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `nik`, `tgl_presensi`, `jam_in`, `jam_out`, `foto_in`, `foto_out`, `lokasi_in`, `lokasi_out`, `status_lokasi_in`, `jarak_in`, `status_lokasi_out`, `jarak_out`) VALUES
(1, '12345', '2025-09-28', '18:43:20', '18:47:30', '12345-2025-09-28-1759059800.png', '12345-2025-09-28-1759060050.png', '-7.5280604,108.5938124', '-7.5280604,108.5938124', '', '', '', ''),
(2, '455', '2025-09-28', '19:02:38', '19:02:47', '455-2025-09-28-1759060958.png', '455-2025-09-28-1759060967.png', '-7.5280604,108.5938124', '-7.5280604,108.5938124', '', '', '', ''),
(4, '12345', '2025-09-29', '07:29:00', '21:20:53', '12345-2025-09-29-in.png', '12345-2025-09-29-out.png', '-7.0025216,110.411776', '-7.5280604,108.5938124', '', '', '', ''),
(5, '455', '2025-09-29', '15:30:40', '15:30:57', '455-2025-09-29-in.png', '455-2025-09-29-out.png', '-7.0025216,110.411776', '-7.0025216,110.411776', '', '', '', ''),
(6, '455', '2025-09-30', '08:13:14', NULL, '455-2025-09-30-in.png', NULL, '-6.9992448,110.4084992', NULL, '', '', '', ''),
(7, '12345', '2025-09-30', '11:29:53', '13:07:01', '12345-2025-09-30-in.png', '12345-2025-09-30-out.png', '-6.9992448,110.4084992', '-6.9992448,110.4084992', '', '', '', ''),
(8, '12345', '2025-10-01', '08:14:07', '19:51:13', '12345-2025-10-01-in.png', '12345-2025-10-01-out.png', '-6.9992448,110.4084992', '-6.3665237,106.8738909', '', '', '', ''),
(10, '455', '2025-10-02', '08:02:31', NULL, '455-2025-10-02-in.png', NULL, '-6.9992448,110.4084992', NULL, '', '', '', ''),
(11, '12345', '2025-10-02', '09:23:43', NULL, '12345-2025-10-02-in.png', NULL, '-6.9992448,110.4084992', NULL, '', '', '', ''),
(12, '12345', '2025-10-03', '16:38:54', '16:39:03', '12345-2025-10-03-in.png', '12345-2025-10-03-out.png', '-7.0025216,110.4150528', '-7.0025216,110.4150528', '', '', '', ''),
(13, '12345', '2025-10-06', '15:23:55', NULL, '12345-2025-10-06-in.png', NULL, '-7.004913,110.4066488', NULL, '', '', '', ''),
(14, '12345', '2025-10-07', '10:22:18', '22:08:04', '12345-2025-10-07-in.png', '12345-2025-10-07-out.png', '-7.005356750000001,110.40666475', '-6.975702519780233,110.39062756117211', '', '', '', ''),
(15, '12345', '2025-10-08', '08:51:15', '15:20:24', '12345-2025-10-08-in.png', '12345-2025-10-08-out.png', '-7.005349559611617,110.40667845688567', '-7.005341764711562,110.40672683151635', '', '', '', ''),
(16, '12345', '2025-10-09', '09:38:39', '15:15:51', '12345-2025-10-09-in.png', '12345-2025-10-09-out.png', '-7.0053488764316425,110.40672418845607', '-7.005353185812699,110.4066530204935', '', '', '', ''),
(17, '3432', '2025-10-09', '09:51:19', '10:01:26', '3432-2025-10-09-in.png', '3432-2025-10-09-out.png', '-7.005342572357265,110.40672877953646', '-7.005345738793402,110.40668352007782', '', '', '', ''),
(18, '12345', '2025-10-10', '15:14:16', '16:23:51', '12345-2025-10-10-in.png', '12345-2025-10-10-out.png', '-7.005349325181797,110.4067177472067', '-7.005346415870683,110.4067197024247', '', '', '', ''),
(19, '12345', '2025-10-13', '08:57:57', '16:04:18', '12345-2025-10-13-in.png', '12345-2025-10-13-out.png', '-7.005346415870683,110.4067197024247', '-7.005352797622142,110.40666518956252', '', '', '', ''),
(20, '12345', '2025-10-14', '09:31:13', NULL, '12345-2025-10-14-in.png', NULL, '-7.005340557220899,110.40673019144438', NULL, '', '', '', ''),
(21, '12345', '2025-10-15', '08:17:14', '16:03:52', '12345-2025-10-15-in.png', '12345-2025-10-15-out.png', '-7.0053492869720815,110.4067181968022', '-7.005349766392314,110.40672099475445', '', '', '', ''),
(22, '12345', '2025-10-16', '08:40:52', NULL, '12345-2025-10-16-in.png', NULL, '-7.005348692167095,110.40672056869023', NULL, '', '', '', ''),
(23, '12345', '2025-10-17', '08:18:09', NULL, '12345-2025-10-17-in.png', NULL, '-7.005354934969853,110.40663415503876', NULL, '', '', '', ''),
(27, '12345', '2025-11-13', '12:02:22', NULL, '12345-2025-11-13-in.png', NULL, '-7.0049346,110.406638', NULL, 'dalam_kantor', '34', NULL, NULL),
(28, '12345', '2025-11-17', '12:08:59', NULL, '12345-2025-11-17-in.png', NULL, '-7.0049375,110.4066453', NULL, 'dalam_kantor', '34', NULL, NULL),
(29, '12345', '2025-11-20', '14:33:46', '14:44:51', '12345-2025-11-20-in.png', '12345-2025-11-20-out.png', '-7.0049197,110.4066522', '-7.0049251,110.406648', 'dalam_kantor', '36', 'dalam_kantor', '36'),
(30, '12345', '2025-11-24', '09:07:20', NULL, '12345-2025-11-24-in.png', NULL, '-7.0049086,110.4066597', NULL, 'dalam_kantor', '38', NULL, NULL),
(31, '12345', '2025-11-27', '10:34:08', '12:37:19', '12345-2025-11-27-in.png', '12345-2025-11-27-out.png', '-6.9825789,110.4085163', '-6.9823115,110.4084513', 'luar_kantor', '2527', 'luar_kantor', '2556'),
(32, '12345', '2025-12-02', '11:36:35', NULL, '12345-2025-12-02-in.png', NULL, '-7.0049301,110.406634', NULL, 'dalam_kantor', '35', NULL, NULL),
(33, '455555', '2025-12-03', '15:58:36', '15:59:59', '455555-2025-12-03-in.png', '455555-2025-12-03-out.png', '-7.0049239,110.4066565', '-7.0049239,110.4066565', 'dalam_kantor', '36', 'dalam_kantor', '36'),
(34, '455555', '2025-12-04', '10:48:20', NULL, '455555-2025-12-04-in.png', NULL, '-6.982573,110.4084749', NULL, 'luar_kantor', '2528', NULL, NULL),
(35, 'A222023', '2025-12-04', '16:03:51', NULL, 'A222023-2025-12-04-in.png', NULL, '-6.971286,110.4031015', NULL, 'luar_kantor', '3792', NULL, NULL),
(36, '455555', '2026-01-06', '08:27:44', '08:31:28', '455555-2026-01-06-in.png', '455555-2026-01-06-out.png', '-6.9712952,110.4032064', '-6.971275,110.4031598', 'luar_kantor', '3790', 'luar_kantor', '3793'),
(37, '455555', '2026-01-08', '08:47:08', NULL, '455555-2026-01-08-in.png', NULL, '-7.00492,110.406646', NULL, 'dalam_kantor', '36', NULL, NULL),
(38, '455555', '2026-01-09', '14:56:22', NULL, '455555-2026-01-09-in.png', NULL, '-7.0049446,110.4066565', NULL, 'dalam_kantor', '34', NULL, NULL),
(39, '455555', '2026-01-11', '10:21:10', '11:15:34', '455555-2026-01-11-in.png', '455555-2026-01-11-out.png', '-6.9712748,110.40315', '-6.9712793,110.4031587', 'luar_kantor', '3793', 'luar_kantor', '3792'),
(40, '455555', '2026-01-13', '07:02:46', '15:16:35', '455555-2026-01-13-in.png', '455555-2026-01-13-out.png', '-6.9712681,110.4031412', '-6.9825853,110.4086152', 'luar_kantor', '3794', 'luar_kantor', '2528'),
(41, '676767', '2026-01-14', '06:57:39', NULL, '676767-2026-01-14-in.png', NULL, '-6.9712656,110.4031974', NULL, 'luar_kantor', '3794', NULL, NULL),
(42, '455555', '2026-01-14', '09:27:12', NULL, '455555-2026-01-14-in.png', NULL, '-6.9827439,110.4085291', NULL, 'luar_kantor', '2509', NULL, NULL),
(43, '455555', '2026-02-09', '12:13:31', NULL, '455555-2026-02-09-in.png', NULL, '-7.005352894052045,110.40671466356878', NULL, 'dalam_kantor', '25', NULL, NULL);

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
(4, 'admin', 'admin@gmail.com', NULL, '$2y$10$rgTTxSzK8f0.h7FPIOiT6uAVZr0GtznSO80bHnexpfwy6p4iAEv/m', NULL, '2025-10-13 17:26:33', '2025-10-17 09:07:50'),
(6, 'admin1', 'admin1@gmail.com', NULL, '$2y$10$tiFpZvzls7.LT4h74g/zSu790WbzenNx.DxOp7VGWLzUbyHzyB4qe', NULL, '2025-12-02 04:50:46', '2025-12-02 04:50:46');

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
  MODIFY `kode_dept` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
