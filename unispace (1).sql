-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 20, 2025 at 09:00 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Table structure for table `reservations`
--
USE unispace;
DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `time_slot_id` int NOT NULL,
  `date` date NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `room_id` (`room_id`),
  KEY `time_slot_id` (`time_slot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `capacity` int NOT NULL,
  `equipment` text,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `rooms`
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
-- Table structure for table `time_slots`
--

DROP TABLE IF EXISTS `time_slots`;
CREATE TABLE IF NOT EXISTS `time_slots` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `time_slots`
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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL,
  `email` varchar(150) NOT NULL,
  `role` enum('student','professor','admin') DEFAULT 'student',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `date_created`, `reset_token`, `reset_token_expiry`) VALUES
(8, 'admin', '$2y$10$MCH0L3DpqsDjUTmwWbr71.IREoQV5S4EH5T7Fjr0ZJvOhhU0i6o7G', '', 'student', '2025-03-20 08:42:58', NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`time_slot_id`) REFERENCES `time_slots` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
