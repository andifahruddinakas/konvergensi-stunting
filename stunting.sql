-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2020 at 04:03 AM
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
-- Database: `stunting`
--

-- --------------------------------------------------------

--
-- Table structure for table `bulanan_anak`
--

CREATE TABLE `bulanan_anak` (
  `id_bulanan_anak` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_posyandu` int(11) NOT NULL,
  `no_kia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_gizi` enum('N','GK','GB','S') COLLATE utf8mb4_unicode_ci NOT NULL,
  `umur_bulan` int(3) NOT NULL,
  `status_tikar` enum('TD','M','K','H') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pemberian_imunisasi_dasar` enum('v','x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pemberian_imunisasi_campak` enum('x','v') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bulanan_anak`
--

INSERT INTO `bulanan_anak` (`id_bulanan_anak`, `id_user`, `id_posyandu`, `no_kia`, `status_gizi`, `umur_bulan`, `status_tikar`, `pemberian_imunisasi_dasar`, `pemberian_imunisasi_campak`, `pengukuran_berat_badan`, `pengukuran_tinggi_badan`, `konseling_gizi_ayah`, `konseling_gizi_ibu`, `kunjungan_rumah`, `air_bersih`, `kepemilikan_jamban`, `akta_lahir`, `jaminan_kesehatan`, `pengasuhan_paud`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '123', 'N', 1, 'TD', 'v', NULL, 'x', 'v', 'v', 'v', 'v', 'x', 'v', 'v', 'v', 'x', '2019-11-26 18:40:38', '2019-11-26 18:40:38'),
(2, 1, 1, '321', 'N', 4, 'K', 'v', NULL, 'v', 'x', 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', '2019-11-26 18:59:47', '2019-11-26 18:59:47'),
(3, 18, 2, '123', 'GB', 2, 'M', 'v', NULL, 'x', 'x', 'x', 'v', 'v', 'v', 'v', 'v', 'v', 'v', '2019-12-27 23:24:17', '2019-12-27 23:24:17'),
(4, 18, 2, '444', 'N', 1, 'M', 'x', NULL, 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', 'v', '2019-12-31 01:38:34', '2019-12-31 01:38:34'),
(5, 18, 2, '123', 'N', 1, 'M', 'v', NULL, 'x', 'v', 'v', 'x', 'v', 'x', 'v', 'v', 'v', 'v', '2020-01-18 01:25:30', '2020-01-18 01:25:30');

-- --------------------------------------------------------

--
-- Table structure for table `ibu_hamil`
--

CREATE TABLE `ibu_hamil` (
  `id_ibu_hamil` int(11) NOT NULL,
  `no_kia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_posyandu` int(11) NOT NULL,
  `status_kehamilan` enum('NORMAL','RISTI','KEK','') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usia_kehamilan` int(2) DEFAULT NULL,
  `tanggal_melahirkan` date DEFAULT NULL,
  `pemeriksaan_kehamilan` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konsumsi_pil_fe` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `butir_pil_fe` int(10) DEFAULT NULL,
  `pemeriksaan_nifas` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konseling_gizi` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kunjungan_rumah` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akses_air_bersih` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kepemilikan_jamban` set('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jaminan_kesehatan` enum('v','x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ibu_hamil`
--

INSERT INTO `ibu_hamil` (`id_ibu_hamil`, `no_kia`, `id_user`, `id_posyandu`, `status_kehamilan`, `usia_kehamilan`, `tanggal_melahirkan`, `pemeriksaan_kehamilan`, `konsumsi_pil_fe`, `butir_pil_fe`, `pemeriksaan_nifas`, `konseling_gizi`, `kunjungan_rumah`, `akses_air_bersih`, `kepemilikan_jamban`, `jaminan_kesehatan`, `created_at`, `updated_at`) VALUES
(1, '123', 1, 1, 'NORMAL', 0, NULL, 'v', 'x', NULL, 'v', 'v', 'v', 'v', 'v', 'v', '2019-11-26 18:36:10', '2019-11-26 18:36:10'),
(2, '321', 1, 1, 'RISTI', 1, '2019-11-29', 'x', 'v', 23, 'x', 'x', 'x', 'v', 'v', 'v', '2019-11-26 18:39:26', '2019-11-26 18:39:26'),
(3, '123', 1, 1, 'NORMAL', 1, NULL, 'v', 'x', NULL, 'v', 'v', 'v', 'x', 'v', 'x', '2019-12-21 20:28:25', '2019-12-21 20:28:25'),
(4, '1234567', 1, 1, 'RISTI', 1, NULL, 'v', 'v', 45, 'v', 'v', 'v', 'v', 'v', 'x', '2019-12-26 01:52:09', '2019-12-26 01:52:09'),
(6, '321', 18, 2, 'NORMAL', 12, NULL, 'x', 'x', NULL, 'v', 'v', 'v', 'v', 'v', 'v', '2019-12-28 00:48:46', '2019-12-28 00:48:46'),
(7, '1111', 18, 2, 'RISTI', 1, NULL, 'v', 'x', NULL, 'v', 'x', 'v', 'v', 'v', 'v', '2019-12-30 22:03:44', '2020-01-05 11:36:07'),
(8, '123', 18, 2, 'NORMAL', 1, '2020-01-17', 'v', 'x', NULL, 'v', 'v', 'v', 'v', 'v', 'x', '2020-01-18 01:02:16', '2020-01-18 01:02:16'),
(9, '321', 18, 2, 'NORMAL', 2, NULL, 'v', 'v', 45, 'x', 'v', 'x', 'x', 'v', 'v', '2020-01-18 01:02:56', '2020-01-18 01:02:56');

-- --------------------------------------------------------

--
-- Table structure for table `kia`
--

CREATE TABLE `kia` (
  `no_kia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ibu` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_anak` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin_anak` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hari_perkiraan_lahir` date DEFAULT NULL,
  `tanggal_lahir_anak` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kia`
--

INSERT INTO `kia` (`no_kia`, `nama_ibu`, `nama_anak`, `jenis_kelamin_anak`, `hari_perkiraan_lahir`, `tanggal_lahir_anak`, `created_at`, `updated_at`) VALUES
('1111', 'Ervina Nadia Salsabila', NULL, NULL, NULL, NULL, '2019-12-30 22:03:44', '2019-12-30 22:03:44'),
('123', 'Siti Nurwati', 'Firdausy', 'L', '2019-12-27', '2019-02-27', '2019-11-26 18:36:10', '2020-01-18 01:25:30'),
('1234567', 'Fadila Rakhma', NULL, NULL, NULL, NULL, '2019-12-26 01:52:09', '2019-12-26 01:52:09'),
('321', 'Farah Aurelia', 'Dida', 'P', '2019-12-27', '2019-02-21', '2019-11-26 18:39:26', '2019-12-27 22:56:53'),
('444', NULL, 'Trisna Saputri', 'P', NULL, '2019-12-04', '2019-12-31 01:38:34', '2019-12-31 01:38:34');

-- --------------------------------------------------------

--
-- Table structure for table `posyandu`
--

CREATE TABLE `posyandu` (
  `id_posyandu` int(11) NOT NULL,
  `nama_posyandu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_posyandu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posyandu`
--

INSERT INTO `posyandu` (`id_posyandu`, `nama_posyandu`, `alamat_posyandu`, `created_at`, `updated_at`) VALUES
(1, 'Posyandu RT 01', 'Klahang RT 05/02', '2019-10-24 02:25:32', '2020-01-16 12:48:25'),
(2, 'Posyandu A', 'Alamat Posyandu A', '2019-10-24 02:25:32', '2019-10-24 02:25:32'),
(3, 'Posyandu Demo 1', 'Posyandu Demo 1', '2019-11-08 02:04:13', '2019-11-08 02:04:13'),
(4, 'Posyandu Demo 2', 'Posyandu Demo 2', '2019-11-08 02:04:13', '2019-11-08 02:04:13'),
(5, 'Posyandu Demo 3', 'Posyandu Demo 3', '2019-11-08 02:04:34', '2019-11-08 02:04:34'),
(6, 'Posyandu Demo 4', 'Posyandu Demo 4', '2019-11-08 02:04:34', '2019-11-08 02:04:34'),
(7, 'Posyandu Demo 5', 'Posyandu Demo 5', '2019-11-08 02:04:46', '2019-11-08 02:04:46');

-- --------------------------------------------------------

--
-- Table structure for table `sasaran_paud`
--

CREATE TABLE `sasaran_paud` (
  `id_sasaran_paud` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_posyandu` int(11) NOT NULL,
  `no_rt` int(5) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_anak` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usia_menurut_kategori` enum('a','b') COLLATE utf8mb4_unicode_ci NOT NULL,
  `januari` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `februari` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `maret` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `april` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `mei` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `juni` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `juli` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `agustus` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `september` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `oktober` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `november` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `desember` enum('v','x','belum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sasaran_paud`
--

INSERT INTO `sasaran_paud` (`id_sasaran_paud`, `id_user`, `id_posyandu`, `no_rt`, `jenis_kelamin`, `nama_anak`, `usia_menurut_kategori`, `januari`, `februari`, `maret`, `april`, `mei`, `juni`, `juli`, `agustus`, `september`, `oktober`, `november`, `desember`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 'L', 'Firdausy', 'a', 'v', 'v', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', '2019-11-26 19:00:29', '2019-11-26 19:00:39'),
(2, 1, 1, 5, 'P', 'Dida', 'b', 'v', 'v', 'v', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', '2019-11-26 19:01:03', '2019-11-26 19:01:03'),
(3, 18, 2, 5, 'L', 'Firdausy', 'a', 'v', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', '2019-12-30 22:24:24', '2019-12-30 22:24:24'),
(4, 18, 2, 2, 'P', 'Trisna Saputri', 'b', 'v', 'v', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', 'belum', '2019-12-31 01:39:47', '2019-12-31 01:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `id_posyandu` int(11) NOT NULL,
  `nama_lengkap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` enum('admin','super_admin','','') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `id_posyandu`, `nama_lengkap`, `username`, `nomor_hp`, `alamat`, `password`, `level`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin Pusat', 'admin', '085726096515', 'Klahang RT 05/02', 'f5bb0c8de146c67b44babbf4e6584cc0', 'super_admin', '2019-10-26 20:22:22', '2019-12-28 02:39:22'),
(4, 2, 'User Pertama', 'user_demo', NULL, NULL, 'f5bb0c8de146c67b44babbf4e6584cc0', 'admin', '2019-11-08 02:06:09', '2019-12-27 22:11:25'),
(18, 2, 'Rafli Firdausy Irawan', 'sulis', '08512376123', 'Mana Aja Boleh', 'f5bb0c8de146c67b44babbf4e6584cc0', 'admin', '2019-12-27 20:53:45', '2020-01-05 21:17:16'),
(19, 7, 'Senia Trisna Saputri', 'senia', NULL, NULL, 'f5bb0c8de146c67b44babbf4e6584cc0', 'admin', '2019-12-28 12:52:28', '2019-12-28 12:52:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bulanan_anak`
--
ALTER TABLE `bulanan_anak`
  ADD PRIMARY KEY (`id_bulanan_anak`),
  ADD KEY `no_kia` (`no_kia`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_posyandu` (`id_posyandu`),
  ADD KEY `id_user_2` (`id_user`);

--
-- Indexes for table `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  ADD PRIMARY KEY (`id_ibu_hamil`),
  ADD KEY `no_kia` (`no_kia`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_posyandu` (`id_posyandu`);

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
-- Indexes for table `sasaran_paud`
--
ALTER TABLE `sasaran_paud`
  ADD PRIMARY KEY (`id_sasaran_paud`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_posyandu` (`id_posyandu`);

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
  MODIFY `id_bulanan_anak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  MODIFY `id_ibu_hamil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `posyandu`
--
ALTER TABLE `posyandu`
  MODIFY `id_posyandu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sasaran_paud`
--
ALTER TABLE `sasaran_paud`
  MODIFY `id_sasaran_paud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bulanan_anak`
--
ALTER TABLE `bulanan_anak`
  ADD CONSTRAINT `bulanan_anak_ibfk_1` FOREIGN KEY (`no_kia`) REFERENCES `kia` (`no_kia`),
  ADD CONSTRAINT `bulanan_anak_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `bulanan_anak_ibfk_3` FOREIGN KEY (`id_posyandu`) REFERENCES `posyandu` (`id_posyandu`);

--
-- Constraints for table `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  ADD CONSTRAINT `ibu_hamil_ibfk_1` FOREIGN KEY (`no_kia`) REFERENCES `kia` (`no_kia`),
  ADD CONSTRAINT `ibu_hamil_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `ibu_hamil_ibfk_3` FOREIGN KEY (`id_posyandu`) REFERENCES `posyandu` (`id_posyandu`);

--
-- Constraints for table `sasaran_paud`
--
ALTER TABLE `sasaran_paud`
  ADD CONSTRAINT `sasaran_paud_ibfk_1` FOREIGN KEY (`id_posyandu`) REFERENCES `posyandu` (`id_posyandu`),
  ADD CONSTRAINT `sasaran_paud_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_posyandu`) REFERENCES `posyandu` (`id_posyandu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
