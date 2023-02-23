-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Gegenereerd op: 16 okt 2022 om 08:11
-- Serverversie: 8.0.22
-- PHP-versie: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zwerfkei`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `name` varchar(80) COLLATE utf8_bin NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `descr` text COLLATE utf8_bin NOT NULL,
  `img` varchar(40) COLLATE utf8_bin NOT NULL,
  `lastedit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Gegevens worden geëxporteerd voor tabel `items`
--

INSERT INTO `items` (`id`, `name`, `price`, `descr`, `img`, `lastedit`) VALUES
(1, '14 daagse Ijslandreis', '3000', '10 dagen rijden met eigen vervoer langs de mooiste plekken rond ringweg nr 1.\r\nInclusief overtocht Hirtshals - Seydisfjordur vv, 4 overnachtingen aan boord, en 10 in eigen tentje op campings.', 'ijsland.jpg', '2022-10-13 09:34:10'),
(2, '14 daagse Noorwegenreis', '2000', '12 dagen rijden met eigen vervoer langs de mooiste plekken in Zuid Noorwegen.\r\nInclusief overtocht Hirtshals - Kristiansand vv, 11 overnachtingen in eigen tentje op campings.', 'norway.jpg', '2022-10-13 09:34:10'),
(3, '8 daagse Faroerreis', '1800', '6 dagen rijden met eigen vervoer langs de mooiste plekken op de Faroer.\r\nInclusief overtocht Hirtshals - Torshavn vv, 2 overnachtingen aan boord en 5 overnachtingen in eigen tentje op campings.', 'faroer.jpg', '2022-10-13 09:34:10'),
(4, '11 daagse Shetlandreis', '1800', '10 dagen rijden met eigen vervoer langs de mooiste plekken op de Shetlands.\r\nInclusief overtochten Ijmuiden-Newcastle en Aberdeen - Lerwik vv, 4 overnachtingen aan boord en 5 overnachtingen in eigen tentje op campings.', 'shetlands.jpg', '2022-10-13 09:34:10'),
(5, '10 daagse Orkneyreis', '1600', '7 dagen rijden met eigen vervoer langs de mooiste plekken op de Orkneys.\r\nInclusief overtochten Ijmuiden-Newcastle en Scrabster-Stromness vv, 2 overnachtingen aan boord en 6 overnachtingen in eigen tentje op campings.', 'orkneys.jpg', '2022-10-13 09:34:10');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ordered_items`
--

CREATE TABLE `ordered_items` (
  `order_id` int NOT NULL,
  `item_id` int NOT NULL,
  `amount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Gegevens worden geëxporteerd voor tabel `ordered_items`
--

INSERT INTO `ordered_items` (`order_id`, `item_id`, `amount`) VALUES
(3, 1, 1),
(3, 2, 1),
(7, 2, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date_ordered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('Ordered','Packed','Send','Delivered') COLLATE utf8_bin NOT NULL DEFAULT 'Ordered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Gegevens worden geëxporteerd voor tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `date_ordered`, `status`) VALUES
(1, 2, '2022-10-13 10:21:37', 'Ordered'),
(2, 2, '2022-10-13 10:26:47', 'Ordered'),
(3, 2, '2022-10-13 14:27:36', 'Ordered'),
(7, 3, '2022-10-16 08:10:56', 'Ordered');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(80) COLLATE utf8_bin NOT NULL,
  `email` varchar(80) COLLATE utf8_bin NOT NULL,
  `password` varchar(80) COLLATE utf8_bin NOT NULL,
  `lastedit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `lastedit`) VALUES
(2, 'Geert', 'coach@man-kind.nl', '7D63627C72', '2022-10-13 07:02:24'),
(3, 'Jeroen Heemskerk', 'jeroen.heemskerk@educom.nu', '4B67796148787F68', '2022-10-16 08:02:16');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `ordered_items`
--
ALTER TABLE `ordered_items`
  ADD UNIQUE KEY `order_item` (`order_id`,`item_id`),
  ADD KEY `ItemID` (`item_id`);

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserID` (`user_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `ordered_items`
--
ALTER TABLE `ordered_items`
  ADD CONSTRAINT `ItemID` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `OrderID` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Beperkingen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `UserID` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
