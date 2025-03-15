<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Връзка с базата данни
require 'db_connection.php';

// Вземане на данни от формата
$room_id = $_POST['room_id'];
$teacher_name = $_POST['teacher_name'];
$course_name = $_POST['course_name'];
$time_slots = $_POST['time_slots']; // Масив с избраните часове

// Записване на резервациите в базата данни
try {
    $pdo->beginTransaction(); // Започваме транзакция

    foreach ($time_slots as $time_slot_id) {
        $stmt = $pdo->prepare("
            INSERT INTO reservations (room_id, time_slot_id, teacher_name, course_name)
            VALUES (:room_id, :time_slot_id, :teacher_name, :course_name)
        ");
        $stmt->execute([
            ':room_id' => $room_id,
            ':time_slot_id' => $time_slot_id,
            ':teacher_name' => $teacher_name,
            ':course_name' => $course_name,
        ]);
    }

    $pdo->commit(); // Потвърждаваме транзакцията
    echo "Резервациите са успешни!";
} catch (PDOException $e) {
    $pdo->rollBack(); // Отменяме транзакцията при грешка
    echo "Грешка при резервация: " . $e->getMessage();
}