<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit();
}
// Вземане на данни от формата
if (isset($_POST['room_id'])) {
    $room_id = $_POST['room_id'];

$date = $_POST['date'];
$teacher_name = $_POST['teacher_name'];
$course_name = $_POST['course_name'];
$time_slots = $_POST['time_slot_id']; // Масив с избраните часове

// Записване на резервациите в базата данни
try {
    $pdo->beginTransaction(); // Започваме транзакция

    foreach ($time_slots as $time_slot_id) {
        $stmt = $pdo->prepare("INSERT INTO `reservations` (`id`, `user_id`, `room_id`, `time_slot_id`, `date`, `status`, `created_at`) VALUES (NULL, '3', '1', '1', '2025-03-11', 'pending', CURRENT_TIMESTAMP)
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
            display: none; /* Скриваме контейнера за часове първоначално */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Резервиране на зала</h2>
        <form action="" method="post">
            <!-- Избор на зала -->
            <div class="mb-3">
                <label class="form-label">Избери зала:</label>
                <select name="room_id" id="room_id" class="form-select" required>
                    <option value="">-- Избери зала --</option>
                    <?php foreach ($rooms as $room) : ?>
                        <option value="<?= $room['id'] ?>"><?= $room['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Контейнер за свободни часове -->
            <div id="time_slots_container" class="mb-3">
                <label class="form-label">Свободни часове:</label>
                <div id="time_slots_list"></div> <!-- Тук ще се показват checkboxes -->
            </div>

            <!-- Информация за преподавателя -->
            <div class="mb-3">
                <label class="form-label">Преподавател:</label>
                <input type="text" name="teacher_name" class="form-control" required>
            </div>

            <!-- Дисциплина -->
            <div class="mb-3">
                <label class="form-label">Дисциплина:</label>
                <input type="text" name="course_name" class="form-control" required>
            </div>

            <!-- Бутон за изпращане -->
            <button type="submit" class="btn btn-primary">Изпрати заявка</button>
        </form>
    </div>

    <!-- jQuery за AJAX заявки -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // При промяна на избраната зала
            $('#room_id').change(function () {
                const roomId = $(this).val();

                if (roomId) {
                    // AJAX заявка за зареждане на свободните часове
                    $.ajax({
                        url: './ajax/get_free_slots.php', // Скрипт, който връща свободните часове
                        type: 'GET',
                        data: { room_id: roomId },
                        success: function (response) {
                            $('#time_slots_list').html(response); // Показваме checkboxes
                            $('#time_slots_container').show(); // Показваме контейнера
                        },
                        error: function () {
                            alert('Грешка при зареждане на свободните часове.');
                        }
                    });
                } else {
                    $('#time_slots_container').hide(); // Скриваме контейнера, ако няма избрана зала
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