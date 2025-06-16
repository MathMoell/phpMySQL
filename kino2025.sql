-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 09:58 AM
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
-- Database: `kino2025`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminid`
--

CREATE TABLE `adminid` (
  `id` int(11) NOT NULL,
  `kasutajanimi` varchar(50) NOT NULL,
  `parool` varchar(255) NOT NULL,
  `loodud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminid`
--

INSERT INTO `adminid` (`id`, `kasutajanimi`, `parool`, `loodud`) VALUES
(3, 'admin', '$2y$10$ejFuk8pHOQwp6nLMIcqC0OGNTXEuRZni6xLHn6Vj8fQIsy7Q2onYa', '2025-06-15 19:20:32');

-- --------------------------------------------------------

--
-- Table structure for table `filmid`
--

CREATE TABLE `filmid` (
  `id` int(11) NOT NULL,
  `nimi` varchar(255) NOT NULL,
  `kestus` int(11) NOT NULL,
  `zanr` varchar(100) NOT NULL,
  `kirjeldus` text DEFAULT NULL,
  `vanusepiirang` varchar(10) DEFAULT NULL,
  `poster_url` varchar(500) DEFAULT NULL,
  `aktiivne` tinyint(1) DEFAULT 1,
  `loodud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `filmid`
--

INSERT INTO `filmid` (`id`, `nimi`, `kestus`, `zanr`, `kirjeldus`, `vanusepiirang`, `poster_url`, `aktiivne`, `loodud`) VALUES
(1, 'Pohmakas 2', 181, 'Tegevus/Seiklus', 'Peale pidu leiavad need 3 peategelast end...', '18+', 'https://theultimaterabbit.com/wp-content/uploads/2021/02/the-hangover-part-ii-movie-poster.jpg', 1, '2025-06-15 18:59:05'),
(2, 'The Substance', 194, 'Horror/Triller', 'Body horror kuulsast näitlejast...', '16+', 'https://upload.wikimedia.org/wikipedia/en/thumb/f/ff/The_Substance_poster.jpg/250px-The_Substance_poster.jpg', 1, '2025-06-15 18:59:05'),
(3, 'Jackass 4.0', 100, 'Stunts/Komöödia', 'Julged kaskadöörid', '18+', 'https://i.ytimg.com/vi/cBQWHmVfQWI/maxresdefault.jpg', 1, '2025-06-15 18:59:05'),
(4, 'Joker', 199, 'Draama/Kriminaal', 'Kurikuulsa kurjategija päritolu lugu', '14+', 'https://upload.wikimedia.org/wikipedia/en/e/e1/Joker_%282019_film%29_poster.jpg', 1, '2025-06-15 18:59:05'),
(5, 'The Grimsby brothers', 103, 'Tegevus/Komöödia', 'Super spioon ja tema vend päästavad maailma...', '18+', 'https://m.media-amazon.com/images/S/pv-target-images/ff2eebf8771803583e75ef891c52a2261568b0b26cffa64d2d393597a797bd80.jpg', 1, '2025-06-15 18:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `kasutajad`
--

CREATE TABLE `kasutajad` (
  `id` int(11) NOT NULL,
  `eesnimi` varchar(100) NOT NULL,
  `perekonnanimi` varchar(100) NOT NULL,
  `isikukood` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `loodud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kasutajad`
--

INSERT INTO `kasutajad` (`id`, `eesnimi`, `perekonnanimi`, `isikukood`, `email`, `telefon`, `loodud`) VALUES
(1, 'Tim', 'Cheese', '50605279042', 'hhll2mr@gmail.com', NULL, '2025-06-16 07:43:29'),
(2, 'John', 'Pork', '12345678901', 'Richlord@yahoo.hotmail', NULL, '2025-06-16 07:44:10'),
(3, 'Urmas', 'Lõvi', '00000000001', 'first@gmail.com', NULL, '2025-06-16 07:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `piletid`
--

CREATE TABLE `piletid` (
  `id` int(11) NOT NULL,
  `kasutaja_id` int(11) NOT NULL,
  `seanss_id` int(11) NOT NULL,
  `rida` int(11) NOT NULL,
  `koht` int(11) NOT NULL,
  `staatus` enum('broneeritud','ostetud','aegunud') DEFAULT 'broneeritud',
  `broneeritud_aeg` timestamp NOT NULL DEFAULT current_timestamp(),
  `makstud_aeg` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `piletid`
--

INSERT INTO `piletid` (`id`, `kasutaja_id`, `seanss_id`, `rida`, `koht`, `staatus`, `broneeritud_aeg`, `makstud_aeg`) VALUES
(1, 1, 4, 6, 8, 'broneeritud', '2025-06-16 07:43:29', NULL),
(2, 2, 4, 6, 9, 'broneeritud', '2025-06-16 07:44:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `saalid`
--

CREATE TABLE `saalid` (
  `id` int(11) NOT NULL,
  `nimi` varchar(100) NOT NULL,
  `ridade_arv` int(11) NOT NULL,
  `kohti_reas` int(11) NOT NULL,
  `kokku_kohti` int(11) NOT NULL,
  `loodud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saalid`
--

INSERT INTO `saalid` (`id`, `nimi`, `ridade_arv`, `kohti_reas`, `kokku_kohti`, `loodud`) VALUES
(1, 'Saal 1', 10, 12, 120, '2025-06-15 18:59:05'),
(2, 'Saal 2', 8, 10, 80, '2025-06-15 18:59:05'),
(3, 'Saal 3', 12, 14, 168, '2025-06-15 18:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `seansid`
--

CREATE TABLE `seansid` (
  `id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `saal_id` int(11) NOT NULL,
  `kuupaev` date NOT NULL,
  `kellaaeg` time NOT NULL,
  `hind` decimal(5,2) NOT NULL,
  `aktiivne` tinyint(1) DEFAULT 1,
  `loodud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seansid`
--

INSERT INTO `seansid` (`id`, `film_id`, `saal_id`, `kuupaev`, `kellaaeg`, `hind`, `aktiivne`, `loodud`) VALUES
(1, 1, 1, '2099-07-01', '18:00:00', 8.50, 1, '2025-06-15 18:59:05'),
(2, 1, 1, '2099-07-02', '21:00:00', 8.50, 1, '2025-06-15 18:59:05'),
(3, 2, 2, '2099-01-15', '19:30:00', 7.50, 1, '2025-06-15 18:59:05'),
(4, 3, 3, '2099-01-15', '16:00:00', 6.50, 1, '2025-06-15 18:59:05'),
(5, 4, 1, '2099-01-16', '20:00:00', 9.00, 1, '2025-06-15 18:59:05'),
(6, 5, 2, '2099-01-16', '17:00:00', 6.50, 1, '2025-06-15 18:59:05'),
(7, 1, 2, '2099-01-17', '19:00:00', 8.50, 1, '2025-06-15 18:59:05'),
(8, 2, 3, '2099-01-17', '21:30:00', 7.50, 1, '2025-06-15 18:59:05'),
(9, 1, 1, '2099-07-01', '18:00:00', 8.50, 1, '2025-06-16 07:41:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminid`
--
ALTER TABLE `adminid`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kasutajanimi` (`kasutajanimi`);

--
-- Indexes for table `filmid`
--
ALTER TABLE `filmid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kasutajad`
--
ALTER TABLE `kasutajad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isikukood` (`isikukood`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `piletid`
--
ALTER TABLE `piletid`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_seat` (`seanss_id`,`rida`,`koht`),
  ADD KEY `kasutaja_id` (`kasutaja_id`);

--
-- Indexes for table `saalid`
--
ALTER TABLE `saalid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seansid`
--
ALTER TABLE `seansid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `film_id` (`film_id`),
  ADD KEY `saal_id` (`saal_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminid`
--
ALTER TABLE `adminid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `filmid`
--
ALTER TABLE `filmid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kasutajad`
--
ALTER TABLE `kasutajad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `piletid`
--
ALTER TABLE `piletid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `saalid`
--
ALTER TABLE `saalid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `seansid`
--
ALTER TABLE `seansid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `piletid`
--
ALTER TABLE `piletid`
  ADD CONSTRAINT `piletid_ibfk_1` FOREIGN KEY (`kasutaja_id`) REFERENCES `kasutajad` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `piletid_ibfk_2` FOREIGN KEY (`seanss_id`) REFERENCES `seansid` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seansid`
--
ALTER TABLE `seansid`
  ADD CONSTRAINT `seansid_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `filmid` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seansid_ibfk_2` FOREIGN KEY (`saal_id`) REFERENCES `saalid` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
