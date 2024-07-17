-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Jul 2024 pada 11.16
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penjualanbaru`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `idpelanggan` int(11) NOT NULL,
  `namapelanggan` varchar(30) NOT NULL,
  `totalharga` int(15) NOT NULL,
  `tanggal` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`idpelanggan`, `namapelanggan`, `totalharga`, `tanggal`) VALUES
(43, 'haga', 90000, '15 Jul 2024'),
(44, 'haga', 270000, '15 Jul 2024'),
(45, 'hahah', 18000, '15 Jul 2024'),
(46, 'hagia', 54000, '15 Jul 2024'),
(47, 'aman', 90000, '15 Jul 2024'),
(48, 'aman', 180000, '15 Jul 2024'),
(49, 'aman', 180000, '15 Jul 2024'),
(50, 'haga', 540000, '15 Jul 2024'),
(51, 'haga', 540000, '15 Jul 2024'),
(52, 'haga', 540000, '15 Jul 2024'),
(53, 'sm', 28000, '15 Jul 2024'),
(54, 'sm', 112000, '15 Jul 2024'),
(55, 'sm', 112000, '15 Jul 2024'),
(61, 'aman', 150000, '15 Jul 2024'),
(62, 'aman', 150000, '15 Jul 2024'),
(63, 'aman', 450000, '15 Jul 2024'),
(64, 'yona', 30000, '15 Jul 2024'),
(65, 'hagai', 60000, '15 Jul 2024'),
(66, 'haga', 180000, '15 Jul 2024'),
(67, 'hagai', 100000, '15 Jul 2024'),
(68, 'na', 120000, '15 Jul 2024'),
(69, 'amn', 210000, '16 Jul 2024'),
(70, 'jh', 50000, '16 Jul 2024'),
(71, 'hagi', 10000, '16 Jul 2024'),
(72, 'aman', 180000, '16 Jul 2024'),
(73, 'aman', 180000, '16 Jul 2024'),
(74, '', 0, '16 Jul 2024');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `idpenjualan` int(11) NOT NULL,
  `idBarang` int(20) NOT NULL,
  `id` int(20) NOT NULL,
  `idpelanggan` int(15) NOT NULL,
  `jumlahBarang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`idpenjualan`, `idBarang`, `id`, `idpelanggan`, `jumlahBarang`) VALUES
(25, 7, 2, 49, 1),
(33, 6, 2, 61, 1),
(34, 6, 2, 62, 1),
(35, 7, 2, 63, 1),
(36, 16, 2, 64, 1),
(37, 16, 2, 64, 1),
(38, 7, 2, 65, 1),
(39, 7, 2, 66, 1),
(40, 6, 2, 67, 1),
(41, 7, 2, 68, 4),
(42, 7, 2, 69, 7),
(43, 7, 2, 69, 7),
(44, 7, 2, 69, 7),
(45, 7, 2, 69, 7),
(46, 7, 2, 69, 7),
(47, 6, 2, 70, 1),
(48, 16, 2, 71, 2),
(49, 7, 2, 72, 6),
(50, 7, 2, 73, 6),
(51, 16, 2, 74, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `idBarang` int(11) NOT NULL,
  `namaBarang` varchar(30) NOT NULL,
  `harga` int(15) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`idBarang`, `namaBarang`, `harga`, `deskripsi`, `foto`) VALUES
(3, 'Jahe Putih', 15000, 'Jahe Putih yang sudah dibersihkan', '669521da9e58b.jpg'),
(6, 'Cabai merah', 50000, 'Cabai merah', '669522240a667.jpg'),
(7, 'Cabai Rawit', 30000, 'Cabai Rawit', '66952247ba8e7.jpg'),
(8, 'Tomat', 5000, 'Tomat Merah', '66952270d1009.jpg'),
(11, 'Wortel', 6000, 'Wortel', '66952320549f0.jpg'),
(15, 'Alvukat', 7000, 'Alvukat', '6695233a4cea0.jpg'),
(16, 'Sayur kol', 5000, 'Sayur kol', '6695239ed919e.jpg'),
(17, 'Jambu Kelutut', 10000, 'Jambu Kelutut Merah', '6695244947fd8.jpg'),
(18, 'Kentang', 6000, 'Kentang ', '6695246e1b2ae.jpg'),
(19, 'Kemiri', 50000, 'Kemiri yang dikelupas', '6695248ce95fb.jpg'),
(20, 'Bawang Merah', 6000, 'Bawang Merah', '66952508e7d4e.jpg'),
(21, 'Jagung', 5500, 'Jagung kering', '6695252f59652.jpg'),
(24, 'hha', 98721, 'jhsj', '6695f5c18b10b.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir') NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nama`, `email`) VALUES
(1, 'admin', 'admin123', 'admin', '', ''),
(2, 'aman', '123', 'kasir', 'Aman Hutabarat', 'hutabarataman21@gmail.com'),
(4, 'man', 'man', 'kasir', 'man', 'man');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`idpelanggan`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`idpenjualan`),
  ADD KEY `id` (`id`),
  ADD KEY `idBarang` (`idBarang`),
  ADD KEY `idpelanggan` (`idpelanggan`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`idBarang`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `idpelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `idpenjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `idBarang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `penjualan_ibfk_2` FOREIGN KEY (`idBarang`) REFERENCES `produk` (`idBarang`),
  ADD CONSTRAINT `penjualan_ibfk_3` FOREIGN KEY (`idpelanggan`) REFERENCES `pelanggan` (`idpelanggan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
