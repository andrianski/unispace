<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Вземане на избраната зала от URL параметъра
$room = isset($_GET['room']) ? htmlspecialchars($_GET['room']) : '';

// Примерни данни за заетост (може да се замени с данни от базата)
$hours = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
$days = ['Понеделник', 'Вторник', 'Сряда', 'Четвъртък', 'Петък'];

// Примерни данни за резервации (преподавател и дисциплина)
$bookings = [
    'Зал 101' => [
        'Понеделник' => ['08:00' => 'Иван Иванов - Математика', '10:00' => 'Петър Петров - Физика'],
        'Вторник' => ['09:00' => 'Мария Георгиева - Химия'],
    ],
    'Зал 102' => [
        'Сряда' => ['14:00' => 'Георги Димитров - Биология'],
    ],
    // Добавете повече данни според нуждите
];
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детайли за <?php echo $room; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Заглавна част -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Заетост на <?php echo $room; ?></h2>
            <a href="pages/dashboard.php" class="btn btn-secondary">Назад към списъка</a>
        </div>

        <!-- Таблица за заетост -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Час / Ден</th>
                        <?php foreach ($days as $day) : ?>
                            <th><?php echo $day; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hours as $hour) : ?>
                        <tr>
                            <td><?php echo $hour; ?></td>
                            <?php foreach ($days as $day) : ?>
                                <td>
                                    <?php
                                    if (isset($bookings[$room][$day][$hour])) {
                                        echo $bookings[$room][$day][$hour];
                                    } else {
                                        echo 'Свободна';
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>