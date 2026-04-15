-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2026 at 11:27 AM
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
-- Database: `db_sewa_properti`
--

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `properti_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_penyewa` varchar(100) NOT NULL,
  `tanggal_pesan` date NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `status` enum('pending','berhasil','batal') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properti`
--

CREATE TABLE `properti` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `nama_properti` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `tipe` enum('rumah','apartemen','kos') NOT NULL,
  `kamar` int(2) NOT NULL,
  `kamar_mandi` enum('dalam','luar') NOT NULL,
  `harga` int(11) NOT NULL,
  `status` enum('TERSEDIA','TIDAK TERSEDIA') DEFAULT 'TERSEDIA',
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properti`
--

INSERT INTO `properti` (`id`, `owner_id`, `nama_properti`, `alamat`, `tipe`, `kamar`, `kamar_mandi`, `harga`, `status`, `deskripsi`, `foto`, `created_at`) VALUES
(1, 2, 'Kos Eksklusif Dago', NULL, 'kos', 1, 'dalam', 1000000, 'TERSEDIA', 'Full furnitur', 'default.png', '2026-04-02 00:26:38'),
(2, 5, 'Kos Bandungs', NULL, 'kos', 2, 'dalam', 2500000, 'TERSEDIA', 'Full', 'default.png', '2026-04-08 10:34:37'),
(3, 5, 'Apartemen Dipatiukur', NULL, 'apartemen', 3, 'dalam', 3000000, 'TERSEDIA', 'Lantai 5', 'default.png', '2026-04-08 10:41:43'),
(4, 5, 'Rumah Dago', NULL, 'rumah', 4, 'dalam', 50000000, 'TERSEDIA', 'Nyaman', 'default.png', '2026-04-08 10:42:22'),
(5, 12, 'Kos Cibaduyut', 'Jalan cibaduyut 1', 'kos', 1, 'dalam', 1500000, 'TIDAK TERSEDIA', 'nyaman dan enak', 'default.png', '2026-04-15 07:54:35'),
(6, 12, 'Rumah cimahi', 'Jalan cimahi 1', 'rumah', 4, 'dalam', 2000000, 'TERSEDIA', 'Bagus', 'default.png', '2026-04-15 08:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `properti_id` int(11) NOT NULL,
  `tanggal_sewa` date NOT NULL,
  `status` enum('Menunggu Konfirmasi','Disetujui','Ditolak') DEFAULT 'Menunggu Konfirmasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `tenant_id`, `properti_id`, `tanggal_sewa`, `status`) VALUES
(1, 3, 4, '2026-04-08', 'Disetujui'),
(2, 13, 5, '2026-04-15', 'Disetujui'),
(3, 13, 4, '2026-04-15', 'Menunggu Konfirmasi'),
(4, 13, 6, '2026-04-15', 'Ditolak');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('tenant','owner','admin') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `role`, `password`, `created_at`) VALUES
(1, 'Pahad ws', 'fahad@gmail.com', 'tenant', '$2y$10$nQYbZH.Vl0vcihEas.gzQer4FgPaXu8bYWoMN8OKdleRU.AhEJmU.', '2026-03-26 02:04:32'),
(2, 'Fahad', 'if-24048@students.ithb.ac.id', 'owner', '$2y$10$cQsh3Jx9aSZrLOF3jk1YROumYv836SNs2y0z/qP.5voPuDgiBNL8y', '2026-03-26 02:12:47'),
(3, 'Fahad', 'r3dalpha91@gmail.com', 'tenant', '$2y$10$5ocYApBxHuEhXefZ1csB5eKFhNtfAjMjb4e.QNkUp9KShbGkJg6Ta', '2026-04-02 00:06:01'),
(5, 'Fahad Owners', 'gendut@gmail.com', 'owner', '$2y$10$bIb52HSQtkXY9I1wvPQDIu2EajJLAgmsJnQ1PV0MqqjDYLCxr5fgS', '2026-04-08 10:34:00'),
(6, 'Super Admin', 'admin@sewa.com', 'admin', '$2y$10$NDfn.oPmlqkD7tA/3EGZZ.6uvJia.bVRIUwwk8fejnPmcDKRRfave', '2026-04-08 11:01:47'),
(12, 'owner1', 'owner1@gmail.com', 'owner', '$2y$10$HiIKIgO.LCAf2//jM8Nxne76Y0tIir/k.Fkp0s5HcTQAU0pd.Wao.', '2026-04-15 07:53:41'),
(13, 'sewa1', 'sewa1@gmail.com', 'tenant', '$2y$10$4tWZYbQc3Q3o6EK/jPln6uWkmfNlZsK5Uq266CJZM8xTSLKVlqOCC', '2026-04-15 07:55:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properti`
--
ALTER TABLE `properti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properti`
--
ALTER TABLE `properti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `properti`
--
ALTER TABLE `properti`
  ADD CONSTRAINT `properti_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
