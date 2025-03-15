<?php
//session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Дашборд - Резервации на зали</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Заглавна част -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Добре дошъл, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <a href="?page=logout" class="btn btn-danger">Изход</a>
        </div>

        <!-- Таблица за заетост на залите -->
        <h3 class="mb-3">Заетост на залите</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Час / Ден</th>
                        <th>Понеделник</th>
                        <th>Вторник</th>
                        <th>Сряда</th>
                        <th>Четвъртък</th>
                        <th>Петък</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Примерни данни за заетост (може да се замени с реални данни от базата)
                    $hours = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
                    $rooms = ['Зал 101', 'Зал 102', 'Зал 103'];

                    foreach ($hours as $hour) {
                        echo "<tr>";
                        echo "<td>{$hour}</td>";
                        for ($i = 0; $i < 5; $i++) { // 5 дни (Понеделник - Петък)
                            $isBooked = (rand(0, 1) === 1); // Примерна логика за заетост
                            $room = $isBooked ? $rooms[array_rand($rooms)] : 'Свободна';
                            $class = $isBooked ? 'table-danger' : 'table-success';
                            echo "<td class='{$class}'>{$room}</td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>