-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Des 2024 pada 08.22
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
-- Database: `pilemku`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `tanggal_rilis` date DEFAULT NULL,
  `sutradara` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar_poster` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `movies`
--

INSERT INTO `movies` (`id`, `judul`, `tanggal_rilis`, `sutradara`, `deskripsi`, `gambar_poster`) VALUES
(8, 'Barbie', '2024-12-22', 'Berun', 'gayyyyyy', '../uploads/posters/29ee1ec9a1af529023fab2797845ffd2.jpg'),
(9, 'Justice League', '2024-12-22', 'Berun', 'Film Elek', '../uploads/posters/d47c53a4ef367f76678eb129ced78dd1.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `profile_picture` varchar(255) DEFAULT 'default.jpg',
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `profile_picture`, `name`, `created_at`) VALUES
(1, 'admin1', '$2y$10$foPj4jR0nZVuKaixfSEYQ.cE3SEhX1LEC1tC1sAP4yWeWniQeqVha', 'admin@gmail.com', 'admin', 'uploads/profile.jpg', NULL, '2024-12-13 11:54:58'),
(2, 'budi', '$2y$10$gVU6VB9wbGrrVciRypiFeOHIMEpUXzh.k1sq8gcSWWDXVHSg8ZTdO', 'budi@gmail.com', 'user', 'uploads/dupir.jpeg', 'budi', '2024-12-13 11:55:46'),
(4, 'arun', '$2y$10$cTuWPKJtN2hEYJn1RdTcLOCRIsSkuO28CI1ldTkUgjg/HuMojqpLa', 'arun@gmail.com', 'user', 'default.jpg', NULL, '2024-12-13 14:07:59'),
(9, 'admin2', '$2y$10$SnfDQHV9DZatXKP7Ljk2puz3rWkZfSaS6W8ZpaiLsNYkZEppJ0HCy', 'admin2@gmail.com', 'admin', 'default.jpg', NULL, '2024-12-21 07:34:37'),
(11, 'reygo', '$2y$10$SiKF3WoK29ilYF2QVfVA7.ix0ik3w.g/CSMp1OWLaj378BWoGUbB2', 'reygo@gmail.com', 'user', 'default.jpg', NULL, '2024-12-21 07:43:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `watchlist`
--

CREATE TABLE `watchlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `tipe_watchlist` enum('watching','completed','on-hold','dropped','plan-to-watch') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `watchlist`
--

INSERT INTO `watchlist` (`id`, `user_id`, `movie_id`, `tipe_watchlist`) VALUES
(1, 11, 8, 'watching'),
(2, 11, 9, 'completed');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `watchlist_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
