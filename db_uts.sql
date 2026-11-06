-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2026 at 01:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uts`
--

-- --------------------------------------------------------

--
-- Table structure for table `pendaftar`
--

CREATE TABLE `pendaftar` (
  `id` int(11) NOT NULL,
  `tgl_input` date NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jk` varchar(10) NOT NULL,
  `asal_sekolah` varchar(100) NOT NULL,
  `pekerjaan_ortu` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `mat` int(11) NOT NULL,
  `bing` int(11) NOT NULL,
  `umum` int(11) NOT NULL,
  `rata` decimal(5,2) NOT NULL,
  `keterangan` varchar(20) NOT NULL,
  `tempat_tes` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftar`
--

INSERT INTO `pendaftar` (`id`, `tgl_input`, `kode`, `nama`, `jk`, `asal_sekolah`, `pekerjaan_ortu`, `tempat_lahir`, `tgl_lahir`, `mat`, `bing`, `umum`, `rata`, `keterangan`, `tempat_tes`) VALUES
(1, '2026-10-30', 'B2', 'Diky', 'Laki-Laki', 'SMAN 11 OKU Timur', 'Wiraswasta', 'OKU Timur ', '2004-05-07', 100, 100, 100, 100.00, 'Lulus', 'Gedung B'),
(2, '2026-10-29', 'B3', 'rusdi', 'Laki-Laki', 'Tadika Mesra', 'Wiraswasta', 'serpong', '2006-03-14', 90, 90, 80, 86.67, 'Lulus', 'Gedung B');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pendaftar`
--
ALTER TABLE `pendaftar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
