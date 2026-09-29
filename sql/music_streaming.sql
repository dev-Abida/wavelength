-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 09, 2026 at 03:35 PM
-- Server version: 8.0.46
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `music_streaming`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `album_id` int NOT NULL,
  `artist_id` int DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `release_year` year DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `artist_id`, `title`, `release_year`, `genre`) VALUES
(1, 1, 'A Better Time', '2020', 'Afrobeats'),
(2, 1, 'Timeless', '2023', 'Afrobeats'),
(3, 2, 'Made in Lagos', '2020', 'Afrobeats'),
(4, 2, 'More Love, Less Ego', '2022', 'Afrobeats'),
(5, 3, 'Rave & Roses', '2022', 'Afrobeats'),
(6, 3, 'HEIS', '2024', 'Afrobeats'),
(7, 4, '1989', '2014', 'Pop'),
(8, 4, 'Lover', '2019', 'Pop');

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `artist_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `country` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`artist_id`, `name`, `country`) VALUES
(1, 'Davido', 'Nigeria'),
(2, 'Wizkid', 'Nigeria'),
(3, 'Rema', 'Nigeria'),
(4, 'T. Swift', 'USA');

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `playlist_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `playlist_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `playlists`
--

INSERT INTO `playlists` (`playlist_id`, `user_id`, `playlist_name`) VALUES
(1, 1, 'Afrobeats Vibes'),
(2, 1, 'Chill Evenings'),
(4, 3, 'Workout Mix'),
(5, 3, 'Road Trip'),
(6, 4, 'Naija Hits'),
(7, 5, 'Swiftie Forever'),
(8, 5, 'Late Night Feels');

-- --------------------------------------------------------

--
-- Table structure for table `playlist_tracks`
--

CREATE TABLE `playlist_tracks` (
  `playlist_id` int NOT NULL,
  `track_id` int NOT NULL,
  `added_date` date DEFAULT (curdate())
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `playlist_tracks`
--

INSERT INTO `playlist_tracks` (`playlist_id`, `track_id`, `added_date`) VALUES
(1, 1, '2023-04-01'),
(1, 2, '2023-04-01'),
(1, 9, '2023-04-02'),
(1, 16, '2023-04-02'),
(1, 20, '2023-04-03'),
(2, 5, '2023-04-05'),
(2, 8, '2023-04-05'),
(2, 27, '2023-04-06'),
(4, 16, '2023-04-09'),
(4, 17, '2023-04-09'),
(4, 20, '2023-04-10'),
(4, 23, '2023-04-10'),
(5, 6, '2023-04-11'),
(5, 10, '2023-04-11'),
(5, 13, '2023-04-12'),
(5, 18, '2023-04-12'),
(6, 9, '2023-04-13'),
(6, 11, '2023-04-13'),
(6, 19, '2023-04-14'),
(6, 22, '2023-04-14'),
(7, 23, '2023-04-15'),
(7, 24, '2023-04-15'),
(7, 25, '2023-04-16'),
(7, 26, '2023-04-16'),
(8, 27, '2023-04-17'),
(8, 28, '2023-04-17'),
(8, 29, '2023-04-18');

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE `tracks` (
  `track_id` int NOT NULL,
  `album_id` int DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `duration_seconds` int DEFAULT NULL,
  `stream_count` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tracks`
--

INSERT INTO `tracks` (`track_id`, `album_id`, `title`, `duration_seconds`, `stream_count`) VALUES
(1, 1, 'FEM', 190, 650),
(2, 1, 'Jowo', 205, 12000),
(3, 1, 'Very Special', 215, 8000),
(4, 1, 'The Best', 198, 6400),
(5, 2, 'Unavailable', 220, 15000),
(6, 2, 'Feel', 200, 9000),
(7, 2, 'Kante', 210, 7000),
(8, 2, 'In the Garden', 230, 4300),
(9, 3, 'Essence', 246, 21000),
(10, 3, 'True Love', 190, 11000),
(11, 3, 'Blessed', 200, 8600),
(12, 3, 'No Stress', 195, 9700),
(13, 4, 'Believe Me', 205, 6100),
(14, 4, 'Bad to Me', 195, 5200),
(16, 5, 'Calm Down', 239, 30000),
(17, 5, 'Away', 180, 14000),
(18, 5, 'Divine', 190, 9800),
(19, 5, 'Soundgasm', 200, 12500),
(20, 6, 'Ozeba', 175, 18700),
(21, 6, 'Benin Boys', 185, 9400),
(22, 6, 'Villain Rose', 210, 7300),
(23, 7, 'Shake It Off', 219, 45050),
(24, 7, 'Blank Space', 231, 42050),
(25, 7, 'Style', 231, 38050),
(26, 7, 'Bad Blood', 211, 29050),
(27, 8, 'Lover', 221, 33050),
(28, 8, 'Cruel Summer', 178, 47050),
(29, 8, 'The Man', 190, 21050),
(30, 8, 'Death by a Thousand Cuts', 228, 15650),
(31, 3, 'Midnight Rain', 210, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `join_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `join_date`) VALUES
(1, 'bilal_dee', 'Bilal@fanmail.com', '2023-01-15'),
(3, 'Banger_boy', 'banger@fanmail.com', '2023-03-10'),
(4, 'Jameel_Udo', 'Jameel@fanmail.com', '2023-05-02'),
(5, 'sarah_swiftie', 'sarah@fanmail.com', '2023-06-18'),
(6, 'DJ_Shadow', 'shadow@beats.com', '2026-09-03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`album_id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`artist_id`);/ 

--
-- Indexes for table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`playlist_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `playlist_tracks`
--
ALTER TABLE `playlist_tracks`
  ADD PRIMARY KEY (`playlist_id`,`track_id`),
  ADD KEY `track_id` (`track_id`);

--
-- Indexes for table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`track_id`),
  ADD KEY `album_id` (`album_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `artist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `playlist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `track_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`);

--
-- Constraints for table `playlists`
--
ALTER TABLE `playlists`
  ADD CONSTRAINT `playlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `playlist_tracks`
--
ALTER TABLE `playlist_tracks`
  ADD CONSTRAINT `playlist_tracks_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlists` (`playlist_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `playlist_tracks_ibfk_2` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`track_id`) ON DELETE CASCADE;

--
-- Constraints for table `tracks`
--
ALTER TABLE `tracks`
  ADD CONSTRAINT `tracks_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;