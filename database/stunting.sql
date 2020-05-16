-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Bulan Mei 2020 pada 17.53
-- Versi server: 10.1.30-MariaDB
-- Versi PHP: 7.2.2

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
-- Struktur dari tabel `bulanan_anak`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `ibu_hamil`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `kia`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `posyandu`
--

CREATE TABLE `posyandu` (
  `id_posyandu` int(11) NOT NULL,
  `nama_posyandu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_posyandu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `posyandu`
--

INSERT INTO `posyandu` (`id_posyandu`, `nama_posyandu`, `alamat_posyandu`, `created_at`, `updated_at`) VALUES
(1, 'Posyandu Pusat', 'Alamat Posyandu Pusat', '2019-10-24 02:25:32', '2020-05-16 22:49:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sasaran_paud`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
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
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `id_posyandu`, `nama_lengkap`, `username`, `nomor_hp`, `alamat`, `password`, `level`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin Pusat', 'admin', '085726096515', 'Jalan Jalan', 'f5bb0c8de146c67b44babbf4e6584cc0', 'super_admin', '2019-10-26 20:22:22', '2020-05-16 22:47:57'),
(21, 1, 'Rafli Firdausy Irawan', 'rafly', NULL, NULL, 'f5bb0c8de146c67b44babbf4e6584cc0', 'admin', '2020-05-16 22:49:48', '2020-05-16 22:49:48');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bulanan_anak`
--
ALTER TABLE `bulanan_anak`
  ADD PRIMARY KEY (`id_bulanan_anak`),
  ADD KEY `no_kia` (`no_kia`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_posyandu` (`id_posyandu`),
  ADD KEY `id_user_2` (`id_user`);

--
-- Indeks untuk tabel `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  ADD PRIMARY KEY (`id_ibu_hamil`),
  ADD KEY `no_kia` (`no_kia`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_posyandu` (`id_posyandu`);

--
-- Indeks untuk tabel `kia`
--
ALTER TABLE `kia`
  ADD PRIMARY KEY (`no_kia`);

--
-- Indeks untuk tabel `posyandu`
--
ALTER TABLE `posyandu`
  ADD PRIMARY KEY (`id_posyandu`);

--
-- Indeks untuk tabel `sasaran_paud`
--
ALTER TABLE `sasaran_paud`
  ADD PRIMARY KEY (`id_sasaran_paud`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_posyandu` (`id_posyandu`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`username`),
  ADD KEY `id_posyandu` (`id_posyandu`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bulanan_anak`
--
ALTER TABLE `bulanan_anak`
  MODIFY `id_bulanan_anak` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  MODIFY `id_ibu_hamil` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `posyandu`
--
ALTER TABLE `posyandu`
  MODIFY `id_posyandu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `sasaran_paud`
--
ALTER TABLE `sasaran_paud`
  MODIFY `id_sasaran_paud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bulanan_anak`
--
ALTER TABLE `bulanan_anak`
  ADD CONSTRAINT `bulanan_anak_ibfk_1` FOREIGN KEY (`no_kia`) REFERENCES `kia` (`no_kia`),
  ADD CONSTRAINT `bulanan_anak_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `bulanan_anak_ibfk_3` FOREIGN KEY (`id_posyandu`) REFERENCES `posyandu` (`id_posyandu`);

--
-- Ketidakleluasaan untuk tabel `ibu_hamil`
--
ALTER TABLE `ibu_hamil`
  ADD CONSTRAINT `ibu_hamil_ibfk_1` FOREIGN KEY (`no_kia`) REFERENCES `kia` (`no_kia`),
  ADD CONSTRAINT `ibu_hamil_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `ibu_hamil_ibfk_3` FOREIGN KEY (`id_posyandu`) REFERENCES `posyandu` (`id_posyandu`);

--
-- Ketidakleluasaan untuk tabel `sasaran_paud`
--
ALTER TABLE `sasaran_paud`
  ADD CONSTRAINT `sasaran_paud_ibfk_1` FOREIGN KEY (`id_posyandu`) REFERENCES `posyandu` (`id_posyandu`),
  ADD CONSTRAINT `sasaran_paud_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_posyandu`) REFERENCES `posyandu` (`id_posyandu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
