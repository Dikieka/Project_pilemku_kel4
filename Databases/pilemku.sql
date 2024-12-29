-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Des 2024 pada 09.43
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
  `genre` varchar(150) DEFAULT NULL,
  `tanggal_rilis` date DEFAULT NULL,
  `sutradara` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar_poster` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `movies`
--

INSERT INTO `movies` (`id`, `judul`, `genre`, `tanggal_rilis`, `sutradara`, `deskripsi`, `gambar_poster`) VALUES
(9, 'Justice League', 'Action, Thriller, Sci-Fi, Zombie', '2024-12-22', 'Berun', 'Film Elek', '../uploads/posters/0f84e7ac61f068cf2ee650e321fb3545.png'),
(10, 'Barbie', 'Comedy, Drama, Fantasi, Romance, Adventure, Animation', '2024-12-23', 'Berun', 'Gayyyy', '../uploads/posters/39279fb41e733ee819e7a319496b45b1.png'),
(12, 'Laskar Pelangi', 'Comedy, Drama, Adventure, Documentary', '2024-12-26', 'Rika Eri', 'okeii', '../uploads/posters/15d822eea9f7db94fd07be9887f55f70.jpg'),
(13, 'The Conjuring', 'Horror', '2024-12-11', 'James Wan', 'Pada tahun 1971, Carolyn dan Roger Perron pindah ke sebuah rumah pertanian tua di Harrisville, Rhode Island, bersama kelima putri mereka. Selama hari pertama, kepindahan keluarga ini berjalan lancar, meskipun anjing mereka, Sadie, menolak memasuki rumah dan salah satu anak perempuan mereka menemukan sebuah pintu masuk ke ruang bawah tanah.', '../uploads/posters/873e6674e10a69a69f48dd719b043d15.jpg'),
(14, 'Gladiator', 'Thriller, Adventure', '2024-12-24', 'jhon doe', 'good', '../uploads/posters/9db51a857ee566adfe813e82911d987e.jpg'),
(15, 'Sekawan Limo', 'Comedy, Drama, Romance, Horror', '2024-12-09', 'Bayu Skak', 'Hiii medenii', '../uploads/posters/ec6f2b8937e776be2fef96dc0ea819b2.jpg'),
(16, 'Train to Busan', 'Action, Thriller, Zombie', '2024-12-02', 'Soo Lee Hin', 'takutnyaa', '../uploads/posters/afaab1d0f9a2db4e89d8a4169b1bc974.png'),
(17, 'Cek Toko Sebelah 2', 'Comedy, Drama, Romance', '2024-12-03', 'Bambang', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/c4f54f6a7ef55fff34e79594a770e093.jpg'),
(18, 'Doraemon Movie', 'Action, Comedy, Drama, Fantasi, Adventure, Animation', '2024-12-04', 'Soo Hun', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/5c35acc540cdc215429f665f1308c181.png'),
(19, 'Mission Imposible', 'Action, Thriller, Fantasi', '2024-12-04', 'jhon doe', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/75d25a64210c3c40f39240796b1b09d5.png'),
(20, 'Matrix', 'Action, Thriller, Sci-Fi, Fant', '2024-12-06', 'Jhon wang', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/ca67ddbcf0147c1c60337dd25db74dbc.png'),
(21, 'Your Name', 'Fantasi, Romance, Animation', '2024-12-16', 'Shui', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/7acc20543bdfa694ebaf8fe6d91b16d6.png'),
(22, 'Agak Laen', 'Comedy, Drama', '2024-12-17', 'Erik', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/6ee89fbdc68318a5ee9746ffd60974a4.jpg'),
(23, 'Transformers', 'Action, Thriller, Sci-Fi, Fantasi', '2025-01-27', 'Jhon no', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/cdf275528bc6c0de5655296cd08fe4f8.png'),
(24, 'Star Wars', 'Action, Thriller, Sci-Fi, Fant', '2024-12-22', 'Mogus', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/5c6c87561274f15595ebcc71444ecb4b.png'),
(25, 'The Hunger Games', 'Action, Thriller, Sci-Fi, Fant', '2024-12-29', 'Moas', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/2e001f6a61e160d245694728b1581fb1.jpg'),
(26, 'Resident Evil', 'Thriller, Sci-Fi, Fantasi, Horror, Zombie', '2024-12-31', 'mong', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/b4c5bd35b614b33754cab0a48f7faf0e.jpg'),
(27, 'Matt & Mou', 'Romance', '2025-01-07', 'Anang', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/b00bd04c7a36ea6f3109ada619fae61c.jpg'),
(28, 'Habibie & Ainun', 'Romance', '2025-01-31', 'moanad', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/57e194f94dcb09374f098b31045a72d2.png'),
(29, 'Insidious', 'Horror', '2024-06-23', 'megan', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/a678d14b838a06545a2a84567a897a61.jpg'),
(30, 'Sijjin', 'Horror', '2024-10-29', 'Anji', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/025efb8d3e737f792e15ba14cab0fae0.png'),
(31, 'A Quiet Place', 'Horror', '2024-12-02', 'Morgan', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/501a1a91b342b1e051d5a215bf5e5e3b.png'),
(32, 'Alice in Wonderland', 'Adventure', '2024-11-17', 'megan', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/3d71e19da4cc242daeb9e4dafeb7c0a4.jpg'),
(33, 'Jumanji', 'Adventure', '2024-11-12', 'Aint', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/e4733672cf5c744cd21fd60a2352a529.jpg'),
(34, 'The Incredibles', 'Animation', '2024-11-07', 'mia', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/596d1e2ea616c429aa30e340f7e84be0.png'),
(35, 'Up', 'Animation', '2024-11-26', 'mean', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/e979cafd9f705882b81e3dec80d0bbfc.jpg'),
(36, 'Toy Story', 'Animation', '2024-10-29', 'Andy', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', '../uploads/posters/6f22ed267e469f8127edc4a0dfd0ec87.png'),
(37, 'Dirty Vote', 'Documentary', '2021-06-07', 'Mulyono', 'Lorem ipsum', '../uploads/posters/d6a92e1ff5f3691d7e80d3743b597dcb.png');

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
(1, 'admin1', '$2y$10$foPj4jR0nZVuKaixfSEYQ.cE3SEhX1LEC1tC1sAP4yWeWniQeqVha', 'admin@gmail.com', 'admin', '../profil/uploads/fotoProfile/676e3547bdc06_rusdy.jpeg', NULL, '2024-12-13 11:54:58'),
(2, 'budi', '$2y$10$gVU6VB9wbGrrVciRypiFeOHIMEpUXzh.k1sq8gcSWWDXVHSg8ZTdO', 'budi@gmail.com', 'user', '../profil/uploads/fotoProfile/67710895045b6_profile.jpg', 'budi', '2024-12-13 11:55:46'),
(9, 'admin2', '$2y$10$O3VdxsMOO4Q2UWQvFs1TJeAJGNTUDV3v7HCK8vLOs4Tea5wWHjFam', 'admin2@gmail.com', 'admin', 'default.jpg', NULL, '2024-12-18 03:23:33'),
(12, 'agus', '$2y$10$QdUg.QyN9LC/naLN7yJWde7v.vdwPK5SSsI0sTf436VO6wv6hCfXC', 'agus@gmail.com', 'user', 'default.jpg', NULL, '2024-12-18 14:29:43');

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
(2, 11, 9, 'watching'),
(2, 11, 9, 'watching'),
(2, 11, 9, 'watching'),
(2, 11, 9, 'watching'),
(2, 11, 9, 'watching'),
(2, 11, 9, 'watching'),
(2, 11, 9, 'watching'),
(0, 2, 10, 'completed'),
(0, 2, 9, 'completed'),
(0, 2, 14, 'completed'),
(0, 2, 19, 'completed'),
(0, 2, 20, 'completed'),
(0, 2, 23, 'completed'),
(0, 2, 12, 'completed'),
(0, 2, 18, 'completed'),
(0, 2, 9, 'completed'),
(0, 2, 9, 'completed'),
(0, 2, 9, 'completed'),
(0, 2, 17, 'completed'),
(0, 2, 25, 'completed'),
(0, 2, 20, 'completed'),
(0, 2, 26, 'completed'),
(0, 2, 29, 'completed'),
(0, 2, 18, 'completed');

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
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
