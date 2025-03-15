<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        die("Не сте влезли в системата.");
    }

    $user_id = $_SESSION['user_id'];
    $room_id = $_POST['room_id'];
    $date = $_POST['date'];
    $time_slot_id = $_POST['time_slot_id'];

    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, room_id, time_slot_id, date, status) 
                           VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$user_id, $room_id, $time_slot_id, $date]);

    header("Location: index.php");
}
?>
 
