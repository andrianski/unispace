<?php
$stmt = $pdo->query("SELECT r.name, res.date, ts.start_time, ts.end_time, u.name AS reserved_by
    FROM reservations res
    JOIN rooms r ON res.room_id = r.id
    JOIN time_slots ts ON res.time_slot_id = ts.id
    JOIN users u ON res.user_id = u.id
    ORDER BY res.date, ts.start_time");

echo "<table border='1'>
        <tr>
            <th>Зала</th>
            <th>Дата</th>
            <th>Час</th>
            <th>Резервирана от</th>
        </tr>";

while ($row = $stmt->fetch()) {
    echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['date']}</td>
            <td>{$row['start_time']} - {$row['end_time']}</td>
            <td>{$row['reserved_by']}</td>
          </tr>";
}
echo "</table>";
?>
 
