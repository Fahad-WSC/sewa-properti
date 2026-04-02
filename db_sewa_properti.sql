-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2026 at 02:32 AM
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
-- Table structure for table `properti`
--

CREATE TABLE `properti` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `nama_properti` varchar(255) NOT NULL,
  `tipe` enum('rumah','apartemen','kos') NOT NULL,
  `kamar` int(2) NOT NULL,
  `kamar_mandi` enum('dalam','luar') NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properti`
--

INSERT INTO `properti` (`id`, `owner_id`, `nama_properti`, `tipe`, `kamar`, `kamar_mandi`, `harga`, `deskripsi`, `foto`, `created_at`) VALUES
(1, 2, 'Kos Eksklusif Dago', 'kos', 1, 'dalam', 1000000, 'Full furnitur', 'default.png', '2026-04-02 00:26:38');

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
(4, 'Fahad Owner', 'Owners@gmail.com', 'owner', '$2y$10$MAhAZeZZGdHKAcIqtRF/R.w7OibLCTmTZXMwXDZsVFfJDKERjO2SO', '2026-04-02 00:22:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `properti`
--
ALTER TABLE `properti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

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
-- AUTO_INCREMENT for table `properti`
--
ALTER TABLE `properti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
