<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit();
}


// Примерни данни за зали (може да се замени с данни от базата)
$rooms = ['Зал 101', 'Зал 102', 'Зал 103', 'Зал 104', 'Зал 105'];
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Общ изглед - Резервации на зали</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Заглавна част -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Добре дошъл, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <a href="?page=logout" class="btn btn-danger">Изход</a>
        </div>

        <!-- Списък на зали -->
        <h3 class="mb-4">Списък на зали</h3>
        <div class="row">
            <?php foreach ($rooms as $room) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $room; ?></h5>
                            <a href="?page=room_detaled_view&room=<?php echo urlencode($room); ?>" class="btn btn-primary">Виж заетост</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>