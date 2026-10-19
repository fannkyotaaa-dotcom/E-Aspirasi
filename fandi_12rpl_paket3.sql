-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2026 at 10:28 AM
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
-- Database: `fandi_12rpl_paket3`
--

-- --------------------------------------------------------

--
-- Table structure for table `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id_aspirasi` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `isi_aspirasi` text NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `foto_bukti` varchar(255) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','proses','selesai') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aspirasi`
--

INSERT INTO `aspirasi` (`id_aspirasi`, `nis`, `id_kategori`, `isi_aspirasi`, `lokasi`, `foto_bukti`, `tanggal`, `status`) VALUES
(2, '28178', 1, 'Kursi kakipatah', 'Kelas XII AKL 1', NULL, '2026-09-20 17:00:00', 'selesai'),
(3, '28178', 2, 'Meja rusak', 'lapang sangkur', NULL, '2026-12-11 17:00:00', 'selesai'),
(4, '43632466463', 1, 'buku hilang', 'cipameutingan', NULL, '2025-11-09 17:00:00', 'selesai'),
(5, '766676767676', 2, 'Si Ican gandeng wae, kick lah.', 'XII RPL 1', NULL, '2026-09-21 17:00:00', 'selesai');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Fasilitas'),
(2, 'Pembelajaran'),
(3, 'Bullying');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `level` enum('admin','siswa') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nis`, `username`, `password`, `nama_lengkap`, `level`, `created_at`) VALUES
(1, '', 'admin', '$2y$10$NF7qOHxskci3Mhyv8s0tsOuAfM008y3zbaFW/yQY2VAyfUEhFnKf.', 'administrator', 'admin', '0000-00-00 00:00:00'),
(2, '12345', 'lulu', '202cb962ac59075b964b07152d234b70', 'Lulu', 'siswa', '2026-09-14 06:33:21'),
(3, '12346', 'asep', '202cb962ac59075b964b07152d234b70', 'Asep', 'siswa', '2026-09-14 06:33:21'),
(5, '28178', 'Egi', '$2y$10$uvohzISRx8rpnDkxb6GBUOAYX6tiYrF5jt1QnuOBZz7SL3WTnwcuq', 'Egi Semvak', 'siswa', '2026-09-21 07:10:14'),
(6, '25161', 'Ariana', '$2y$10$GNoR4Z.LS6gF/OSbXWzuieQb0fUEbYd.dvs3ukAREJxIUFo2KqGwC', 'Ariana', 'siswa', '2026-09-22 00:56:07'),
(7, '43632466463', 'DAFFA', '$2y$10$ttW8BmAZXtXQH2uL/XYuH.ydDk1QJbJFmNivLfedZAcHUBPAYNRwW', 'Daffa Mulyansyah', 'siswa', '2026-09-22 01:07:29'),
(8, '766676767676', 'ahmad', '$2y$10$EvxMXF0EtoXSp4tcpuuuiuSzEbtNFQIOrg0JvlBKX4.FgKQiIbpyu', 'Ahmed Cuki', 'siswa', '2026-09-22 01:11:47'),
(9, '29182', 'alex', '$2y$10$1eEOaMPRkA/cXLrY4pDaR.hBjOh0VAYgJ2r6Ih6RRkcevaRMs19uq', 'alex', 'siswa', '2026-09-28 01:31:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD PRIMARY KEY (`id_aspirasi`),
  ADD KEY `nis` (`nis`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_nis` (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aspirasi`
--
ALTER TABLE `aspirasi`
  MODIFY `id_aspirasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD CONSTRAINT `aspirasi_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `users` (`nis`) ON DELETE CASCADE,
  ADD CONSTRAINT `aspirasi_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
