-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2019 at 06:59 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `konvergensi_stunting`
--

-- --------------------------------------------------------

--
-- Table structure for table `bulanan_anak`
--

CREATE TABLE `bulanan_anak` (
  `id_bulanan_anak` int(11) NOT NULL,
  `no_kia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_gizi` enum('N','GK','GB','S') COLLATE utf8mb4_unicode_ci NOT NULL,
  `umur_bulan` int(3) NOT NULL,
  `status_tikar` enum('TD','M','K','H') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pemberian_imunisasi_dasar` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengukuran_berat_badan` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengukuran_tinggi_badan` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `konseling_gizi_ayah` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `konseling_gizi_ibu` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kunjungan_rumah` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `air_bersih` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kepemilikan_jamban` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `akta_lahir` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jaminan_kesehatan` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengasuhan_paud` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bulanan_anak`
--

INSERT INTO `bulanan_anak` (`id_bulanan_anak`, `no_kia`, `status_gizi`, `umur_bulan`, `status_tikar`, `pemberian_imunisasi_dasar`, `pengukuran_berat_badan`, `pengukuran_tinggi_badan`, `konseling_gizi_ayah`, `konseling_gizi_ibu`, `kunjungan_rumah`, `air_bersih`, `kepemilikan_jamban`, `akta_lahir`, `jaminan_kesehatan`, `pengasuhan_paud`, `created_at`, `updated_at`) VALUES
(3, '444', 'GB', 1, 'TD', 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', '2019-10-26 01:48:29', '2019-10-26 01:48:29'),
(6, '123', 'S', 3, 'K', 'x', 'v', 'x', 'v', 'x', 'v', 'v', 'v', 'v', 'v', 'v', '2019-10-26 22:03:39', '2019-10-26 22:03:39'),
(7, '222', 'GK', 12, 'M', 'x', 'v', 'x', 'v', 'x', 'v', 'x', 'v', 'x', 'v', 'x', '2019-10-26 22:28:52', '2019-10-26 22:43:20');

-- --------------------------------------------------------

--
-- Table structure for table `ibu_hamil`
--

CREATE TABLE `ibu_hamil` (
  `id_ibu_hamil` int(11) NOT NULL,
  `no_kia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_kehamilan` enum('NORMAL','RISTI','KEK','') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hari_perkiraan_lahir` date DEFAULT NULL,
  `usia_kehamilan` int(2) DEFAULT NULL,
  `tanggal_melahirkan` date DEFAULT NULL,
  `pemeriksaan_kehamilan` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konsumsi_pil_fe` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pemeriksaan_nifas` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konseling_gizi` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kunjungan_rumah` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akses_air_bersih` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kepemilikan_jamban` set('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jaminan_kesehatan` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ibu_hamil`
--

INSERT INTO `ibu_hamil` (`id_ibu_hamil`, `no_kia`, `status_kehamilan`, `hari_perkiraan_lahir`, `usia_kehamilan`, `tanggal_melahirkan`, `pemeriksaan_kehamilan`, `konsumsi_pil_fe`, `pemeriksaan_nifas`, `konseling_gizi`, `kunjungan_rumah`, `akses_air_bersih`, `kepemilikan_jamban`, `jaminan_kesehatan`, `created_at`, `updated_at`) VALUES
(8, '123', 'NORMAL', '2019-10-19', 1, NULL, 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', '2019-10-24 20:50:56', '2019-10-24 20:50:56'),
(9, '12345', 'KEK', '2019-10-05', 3, '2019-10-26', 'x', 'x', 'x', 'x', 'x', 'x', 'x', 'x', '2019-10-24 21:06:30', '2019-10-24 21:06:30'),
(10, '111', 'NORMAL', '2019-10-26', 1, NULL, 'v', 'x', 'v', 'x', 'v', 'v', 'v', 'v', '2019-10-25 09:22:14', '2019-10-25 09:22:14'),
(11, '333', 'KEK', '2019-10-31', 1, NULL, 'x', 'x', 'x', 'x', 'x', 'x', 'x', 'x', '2019-10-25 09:27:36', '2019-10-25 09:27:36'),
(12, '666', 'RISTI', '2019-10-01', 3, '2019-10-02', 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', '2019-10-25 09:58:45', '2019-10-25 09:58:45'),
(13, '222', 'RISTI', '2019-10-19', 1, '2019-10-26', 'x', 'x', 'x', 'x', 'x', 'x', 'x', 'x', '2019-10-25 16:02:54', '2019-10-26 01:19:48'),
(14, '000', 'NORMAL', '2019-10-17', 1, '2019-10-05', 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', '2019-10-26 01:20:24', '2019-10-26 01:20:24'),
(15, '888', 'NORMAL', '2019-10-12', 1, '2019-10-24', 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', '2019-10-26 01:33:38', '2019-10-26 01:33:38'),
(16, '777', 'RISTI', '2019-10-31', 2, NULL, 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', '2019-10-26 01:42:36', '2019-10-26 01:42:36');

-- --------------------------------------------------------

--
-- Table structure for table `kia`
--

CREATE TABLE `kia` (
  `no_kia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ibu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_anak` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin_anak` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir_anak` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kia`
--

INSERT INTO `kia` (`no_kia`, `nama_ibu`, `nama_anak`, `jenis_kelamin_anak`, `tanggal_lahir_anak`, `created_at`, `updated_at`) VALUES
('000', 'Farah Aurelia', NULL, NULL, NULL, '2019-10-26 01:20:24', '2019-10-26 01:20:24'),
('111', 'Farah Nisrina', NULL, NULL, NULL, '2019-10-24 18:26:06', '2019-10-25 04:22:14'),
('123', 'Senia Trisna', 'Sani', 'L', '2019-10-31', '2019-10-24 14:09:49', '2019-10-26 22:03:39'),
('123123', 'Desti Sandra', NULL, NULL, NULL, '2019-10-24 15:36:27', '2019-10-24 15:36:27'),
('12345', 'SITI', 'RAFLI FIRDAUSY', 'L', '2019-10-24', '2019-10-24 02:28:56', '2019-10-24 16:06:30'),
('222', 'Fadila Rakhma', 'Dida', 'P', '2019-10-31', '2019-10-24 18:28:35', '2019-10-26 22:28:52'),
('321', 'Desty Sandra', NULL, NULL, NULL, '2019-10-24 16:13:40', '2019-10-24 16:13:40'),
('333', 'Rafli Firdausy Irawan', 'Firdausy', 'L', '2019-10-18', '2019-10-24 19:13:14', '2019-10-26 02:10:27'),
('444', 'Mbuuuh', 'RAFLI GANTENG', 'L', '2019-11-02', '2019-10-26 01:48:29', '2019-10-26 01:49:07'),
('555', 'Trisna Saputri', NULL, NULL, NULL, '2019-10-24 19:14:40', '2019-10-24 19:14:40'),
('666', 'Hida', NULL, NULL, NULL, '2019-10-25 09:58:45', '2019-10-25 09:58:45'),
('777', 'hehehe', NULL, NULL, NULL, '2019-10-26 01:42:36', '2019-10-26 01:42:36'),
('888', 'Orang Lewat', NULL, NULL, NULL, '2019-10-26 01:33:38', '2019-10-26 01:33:38');

-- --------------------------------------------------------

--
-- Table structure for table `posyandu`
--

CREATE TABLE `posyandu` (
  `id_posyandu` int(11) NOT NULL,
  `nama_posyandu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_posyandu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posyandu`
--

INSERT INTO `posyandu` (`id_posyandu`, `nama_posyandu`, `alamat_posyandu`, `created_at`, `updated_at`) VALUES
(1, 'PUSAT', 'JALAN JALAN YUK', '2019-10-24 02:25:32', '2019-10-24 02:25:32'),
(2, 'Posyandu A', 'Alamat Posyandu A', '2019-10-24 02:25:32', '2019-10-24 02:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `id_posyandu` int(11) NOT NULL,
  `nama_lengkap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` enum('admin','super_admin','','') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `id_posyandu`, `nama_lengkap`, `username`, `password`, `level`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin Pusat', 'admin', 'admin123', 'super_admin', '2019-10-26 20:22:22', '2019-10-26 20:22:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bulanan_anak`
--
ALTER TABLE `bulanan_anak`
  ADD PRIMARY KEY (`id_bulanan_anak`),
  ADD KEY `no_kia` (`no_kia`);

--
-- Indexes for table `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  ADD PRIMARY KEY (`id_ibu_hamil`),
  ADD KEY `no_kia` (`no_kia`);

--
-- Indexes for table `kia`
--
ALTER TABLE `kia`
  ADD PRIMARY KEY (`no_kia`);

--
-- Indexes for table `posyandu`
--
ALTER TABLE `posyandu`
  ADD PRIMARY KEY (`id_posyandu`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`username`),
  ADD KEY `id_posyandu` (`id_posyandu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bulanan_anak`
--
ALTER TABLE `bulanan_anak`
  MODIFY `id_bulanan_anak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  MODIFY `id_ibu_hamil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `posyandu`
--
ALTER TABLE `posyandu`
  MODIFY `id_posyandu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bulanan_anak`
--
ALTER TABLE `bulanan_anak`
  ADD CONSTRAINT `bulanan_anak_ibfk_1` FOREIGN KEY (`no_kia`) REFERENCES `kia` (`no_kia`);

--
-- Constraints for table `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  ADD CONSTRAINT `ibu_hamil_ibfk_1` FOREIGN KEY (`no_kia`) REFERENCES `kia` (`no_kia`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_posyandu`) REFERENCES `posyandu` (`id_posyandu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
