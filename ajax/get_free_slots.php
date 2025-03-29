<?php
require '../config-dist.php'; // Връзка с базата

if (!isset($_GET['room_id'])) {
    echo "<p>Грешка: Не е избрана зала.</p>";
    exit();
}

$room_id = $_GET['room_id'];

// Зареждане на свободните часове за залата
$stmt = $pdo->prepare("
    SELECT ts.id, ts.start_time, ts.end_time
    FROM time_slots ts
    WHERE ts.id NOT IN (
        SELECT time_slot_id FROM reservations WHERE room_id = :room_id
    )
");
$stmt->execute([':room_id' => $room_id]);
$free_slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($free_slots) {
    foreach ($free_slots as $slot) {
        echo "<div class='form-check'>
                <input class='form-check-input' type='checkbox' name='time_slot_id[]' value='{$slot['id']}' id='slot_{$slot['id']}'>
                <label class='form-check-label' for='slot_{$slot['id']}'>
                    {$slot['start_time']} - {$slot['end_time']}
                </label>
              </div>";
    }
} else {
    echo "<p>Няма свободни часове за тази зала.</p>";
}
?>
