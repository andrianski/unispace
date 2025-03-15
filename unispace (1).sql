-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 15 март 2025 в 12:17
-- Версия на сървъра: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unispace`
--

-- --------------------------------------------------------

--
-- Структура на таблица `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `time_slot_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура на таблица `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `equipment` text,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `capacity`, `equipment`, `status`, `created_at`) VALUES
(1, 'Зала 101', 30, NULL, 0, '2025-02-20 08:42:21'),
(2, 'Зала 102', 25, NULL, 0, '2025-02-20 08:42:21'),
(3, 'Зала 201', 40, NULL, 0, '2025-02-20 08:42:21'),
(4, 'Зала 202', 35, NULL, 0, '2025-02-20 08:42:21'),
(5, 'Зала 301', 50, NULL, 0, '2025-02-20 08:42:21'),
(6, 'Зала 302', 45, NULL, 0, '2025-02-20 08:42:21'),
(7, 'Аудитория 1', 100, NULL, 0, '2025-02-20 08:42:21'),
(8, 'Аудитория 2', 120, NULL, 0, '2025-02-20 08:42:21');

-- --------------------------------------------------------

--
-- Структура на таблица `time_slots`
--

CREATE TABLE `time_slots` (
  `id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `time_slots`
--

INSERT INTO `time_slots` (`id`, `start_time`, `end_time`, `created_at`) VALUES
(1, '08:00:00', '09:30:00', '2025-02-20 08:43:13'),
(2, '09:30:00', '11:00:00', '2025-02-20 08:43:13'),
(3, '11:00:00', '12:30:00', '2025-02-20 08:43:13'),
(4, '13:00:00', '14:30:00', '2025-02-20 08:43:13'),
(5, '14:30:00', '16:00:00', '2025-02-20 08:43:13'),
(6, '16:00:00', '17:30:00', '2025-02-20 08:43:13'),
(7, '18:00:00', '19:30:00', '2025-02-20 08:43:13');

-- --------------------------------------------------------

--
-- Структура на таблица `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'ivan', 'e10adc3949ba59abbe56e057f20f883e'),
(2, 'petar', '741f6ba10dc0aa92b17b49716023f24a'),
(3, 'admin', '$2y$10$GSZ29.ZbKPNg..qMCnJwgeusuTYHGjiLPcyjFJwu7NoCwKvGT/cIu'),
(4, 'admin1', '$2y$10$jLwert4NkySk22N.h/2PHOIeL3mWn7KtfAufBajcxJSarFfY8ugoe'),
(5, 'Petko', '$2y$10$rpThhV0anAiuQMhwc8HP5.nGAzAwtCIYPJx/0hGnNluhqVejVCLDq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `time_slot_id` (`time_slot_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения за дъмпнати таблици
--

--
-- Ограничения за таблица `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`time_slot_id`) REFERENCES `time_slots` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
