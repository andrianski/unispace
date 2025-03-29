<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit();
}

if (isset($_POST['room_id'])) {
    $room_id = $_POST['room_id'];

    $teacher_name = isset($_POST['teacher_name']) ? $_POST['teacher_name'] : null;
    $course_name = isset($_POST['course_name']) ? $_POST['course_name'] : null;
    $time_slots = isset($_POST['time_slot_id']) ? $_POST['time_slot_id'] : [];

    // Проверка дали всички задължителни полета са попълнени
    if (!$teacher_name || !$course_name || empty($time_slots)) {
        echo "Моля, попълнете всички задължителни полета.";
        exit();
    }

    try {
        $pdo->beginTransaction();

        foreach ($time_slots as $time_slot_id) {
            $stmt = $pdo->prepare("
                INSERT INTO `reservations` (`id`, `user_id`, `room_id`, `time_slot_id`, `status`, `created_at`) 
                VALUES (NULL, :user_id, :room_id, :time_slot_id, 'pending', CURRENT_TIMESTAMP)
            ");
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':room_id' => $room_id,
                ':time_slot_id' => $time_slot_id
            ]);
        }

        $pdo->commit();
        echo "Резервациите са успешни!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Грешка при резервация: " . $e->getMessage();
    }

} else {

    // Връзка с базата данни и извличане на данни
    $stmt = $pdo->query("SELECT id, name FROM rooms");
    $rooms = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Резервиране на зала</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #time_slots_container {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Резервиране на зала</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Избери зала:</label>
                <select name="room_id" id="room_id" class="form-select" required>
                    <option value="">-- Избери зала --</option>
                    <?php foreach ($rooms as $room) : ?>
                        <option value="<?= $room['id'] ?>"><?= $room['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="time_slots_container" class="mb-3">
                <label class="form-label">Свободни часове:</label>
                <div id="time_slots_list"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Преподавател:</label>
                <input type="text" name="teacher_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Дисциплина:</label>
                <input type="text" name="course_name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Изпрати заявка</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function () {
    $('#room_id').change(function () {
        const roomId = $(this).val();

        if (roomId) {
            $.ajax({
                url: './ajax/get_free_slots.php',
                type: 'GET',
                data: { room_id: roomId },
                success: function (response) {
                    $('#time_slots_list').html(response);
                    $('#time_slots_container').show();
                },
                error: function () {
                    alert('Грешка при зареждане на свободните часове.');
                }
            });
        } else {
            $('#time_slots_container').hide();
        }
    });
});
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
}
?>
