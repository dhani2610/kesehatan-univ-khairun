-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2024 at 06:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_prak`
--

-- --------------------------------------------------------

--
-- Table structure for table `datamhs`
--

CREATE TABLE `datamhs` (
  `id` int(11) NOT NULL,
  `fakultas` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `npm` varchar(50) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `sudah_pemeriksaan` enum('ya','tidak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id` int(11) NOT NULL,
  `id_mhs` int(11) NOT NULL,
  `password` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `lihatData` varchar(50) DEFAULT NULL,
  `editData` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nama_mahasiswa` varchar(50) NOT NULL,
  `nim` varchar(50) NOT NULL,
  `fakultas` varchar(25) NOT NULL,
  `jurusan` varchar(25) NOT NULL,
  `bukti_pembayaran` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nama_mahasiswa`, `nim`, `fakultas`, `jurusan`, `bukti_pembayaran`) VALUES
(1, 'abel', '07352211182', 'Fakultas Teknik', 'Informatika', 'uploads/acaa.pdf'),
(2, 'nuku', '07352211188', 'Fakultas Ekonomi', 'Akuntansi', 'uploads/gambar 2.jpeg'),
(3, 'put', '07352211176', 'Fakultas Teknik', 'Informatika', 'uploads/UTS SOBJ.pdf'),
(4, 'putri', '07352211182', 'Fakultas Hukum', 'Hukum Perdata', 'uploads/Teks paragraf And'),
(5, 'res', '0735221111', 'Fakultas Kedokteran', 'Kedokteran Gigi', 'uploads/Rabiatul adharia '),
(6, 'riska', '07352211170', 'Fakultas Ekonomi', 'Manajemen', 'uploads/IMG_5796.jpg'),
(7, 'acaa', '07342516', 'Fakultas Hukum', 'Hukum Perdata', 'uploads/gambar 1.jpeg'),
(8, 'bel', '07342516', 'Fakultas Teknik', 'Teknik Sipil', 'uploads/f2ef5178d4aafee7c'),
(9, 'bel', '07342516', 'Fakultas Teknik', 'Teknik Sipil', 'uploads/f2ef5178d4aafee7c'),
(10, 'chan', '0735222222', 'Fakultas Hukum', 'Hukum Perdata', 'uploads/Rabiatul adharia '),
(11, 'bela', '07352211180', 'Fakultas Hukum', 'Hukum Pidana', 'uploads/UTS SOBJ.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` enum('user','admin','dokter') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `username`, `password`, `email`, `role`) VALUES
(8, 'riska', '$2y$10$h4aZYEm5qnknNSWbL7iWaen7W1gSTHrGVZAaO1BRBMs', 'riskalatif@gmail.com', 'user'),
(9, '123', '$2y$10$UmujWPfbTv4D9cz7xcAFE.dyIX6V7LOF4xinrZQfwSl', 'email@gmail.com', 'user'),
(10, 'arhy', '123', 'email@gmail.com', 'user'),
(11, 'dokter', '123', 'email@gmail.com', 'dokter'),
(13, 'dokter1', '123', 'email@gmail.com', 'dokter'),
(14, 'dokter2', '123', 'email@gmail.com', 'dokter'),
(16, 'riska', 'admin', 'riska@gmail.com', 'admin'),
(17, 'bela', '123', 'bela@gmail.com', 'user'),
(18, 'alea', '$2y$10$O8Evm9.IfsNGGu8s4DvNpODZOGCX.QqDV3OKz/84tDu', 'alea@gmail.com', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `datamhs`
--
ALTER TABLE `datamhs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mhs` (`id_mhs`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `datamhs`
--
ALTER TABLE `datamhs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `dokter_ibfk_1` FOREIGN KEY (`id_mhs`) REFERENCES `mahasiswa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
