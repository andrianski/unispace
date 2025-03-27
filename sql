-- Създаване на базата данни
CREATE DATABASE IF NOT EXISTS `unispace` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `unispace`;

-- Таблица за потребители
CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `role` ENUM('admin', 'teacher', 'student') NOT NULL DEFAULT 'student',
  `full_name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица за зали
CREATE TABLE `rooms` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `capacity` INT NOT NULL,
  `equipment` TEXT,
  `status` ENUM('available', 'occupied', 'maintenance') DEFAULT 'available'
);

-- Таблица за времеви интервали
CREATE TABLE `time_slots` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `label` VARCHAR(50) NOT NULL (e.g., '09:00-11:00')
);

-- Таблица за резервации
CREATE TABLE `reservations` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `room_id` INT NOT NULL,
  `time_slot_id` INT NOT NULL,
  `date` DATE NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`),
  FOREIGN KEY (`time_slot_id`) REFERENCES `time_slots`(`id`)
);

-- Допълнителни таблици (за уведомления, отчети и др.)
CREATE TABLE `notifications` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('reminder', 'alert', 'info') NOT NULL,
  `is_read` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

-- Примерни данни
INSERT INTO `users` (`username`, `password`, `email`, `role`, `full_name`) 
VALUES 
('admin', SHA2('admin123', 256), 'admin@uni.bg', 'admin', 'Администратор Администраторов'),
('teacher1', SHA2('teacher123', 256), 'teacher@uni.bg', 'teacher', 'Професор Иванов'),
('student1', SHA2('student123', 256), 'student@uni.bg', 'student', 'Студент Петров');

INSERT INTO `rooms` (`name`, `capacity`, `equipment`) 
VALUES 
('Зал 101', 50, 'Проектор, бела дъска'),
('Зал 202', 100, 'Компютри, климатик'),
('Конферентна зала', 30, 'Телевизор, микрофони');

INSERT INTO `time_slots` (`start_time`, `end_time`, `label`)
VALUES
('09:00:00', '11:00:00', '09:00-11:00'),
('11:30:00', '13:30:00', '11:30-13:30'),
('14:00:00', '16:00:00', '14:00-16:00');

-- Индекси за оптимизация
CREATE INDEX `idx_reservations_date` ON `reservations`(`date`);
CREATE INDEX `idx_users_role` ON `users`(`role`);
