<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}

// Initialize variables
$errors = [];
$reservation = [
    'id' => null,
    'classroom_id' => '',
    'group_id' => '',
    'slot_id' => '',
    'date' => date('Y-m-d'),
    'title' => '',
    'description' => '',
    'is_recurring' => false,
    'recurrence_type' => 'weekly',
    'recurrence_end' => date('Y-m-d', strtotime('+2 weeks'))
];
$edit_mode = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $reservation['id'] = isset($_POST['id']) ? intval($_POST['id']) : null;
    $reservation['classroom_id'] = intval($_POST['classroom_id']);
    $reservation['group_id'] = !empty($_POST['group_id']) ? intval($_POST['group_id']) : null;
    $reservation['slot_id'] = intval($_POST['slot_id']);
    $reservation['date'] = $_POST['date'];
    $reservation['title'] = trim($_POST['title']);
    $reservation['description'] = trim($_POST['description']);
    $reservation['is_recurring'] = isset($_POST['is_recurring']);
    $reservation['recurrence_type'] = $_POST['recurrence_type'] ?? 'weekly';
    $reservation['recurrence_end'] = $_POST['recurrence_end'] ?? $reservation['date'];
    
    // Validate input
    if (empty($reservation['title'])) {
        $errors[] = "Title is required";
    }
    
    if (empty($reservation['date'])) {
        $errors[] = "Date is required";
    } elseif (!strtotime($reservation['date'])) {
        $errors[] = "Invalid date format";
    }
    
    if ($reservation['is_recurring'] && !strtotime($reservation['recurrence_end'])) {
        $errors[] = "Invalid recurrence end date";
    }
    
    // If no errors, process reservation
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            if ($reservation['is_recurring']) {
                // Handle recurring reservation
                $dates = calculateRecurringDates(
                    $reservation['date'],
                    $reservation['recurrence_end'],
                    $reservation['recurrence_type']
                );
                
                $series_id = uniqid();
                
                foreach ($dates as $date) {
                    if (!$this->isClassroomAvailable($reservation['classroom_id'], $reservation['slot_id'], $date, $reservation['id'])) {
                        throw new Exception("Classroom is not available on {$date}");
                    }
                    
                    $this->saveReservation([
                        'classroom_id' => $reservation['classroom_id'],
                        'user_id' => $_SESSION['user_id'],
                        'group_id' => $reservation['group_id'],
                        'slot_id' => $reservation['slot_id'],
                        'date' => $date,
                        'title' => $reservation['title'],
                        'description' => $reservation['description'],
                        'series_id' => $series_id
                    ]);
                }
            } else {
                // Handle single reservation
                if (!$this->isClassroomAvailable($reservation['classroom_id'], $reservation['slot_id'], $reservation['date'], $reservation['id'])) {
                    throw new Exception("Classroom is not available on this date and time");
                }
                
                $this->saveReservation([
                    'id' => $reservation['id'],
                    'classroom_id' => $reservation['classroom_id'],
                    'user_id' => $_SESSION['user_id'],
                    'group_id' => $reservation['group_id'],
                    'slot_id' => $reservation['slot_id'],
                    'date' => $reservation['date'],
                    'title' => $reservation['title'],
                    'description' => $reservation['description']
                ]);
            }
            
            $pdo->commit();
            $_SESSION['success'] = $reservation['id'] ? "Reservation updated successfully" : "Reservation created successfully";
            header("Location: reservations.php");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = $e->getMessage();
        }
    }
} elseif (isset($_GET['edit'])) {
    // Load existing reservation for editing
    $edit_mode = true;
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['edit'], $_SESSION['user_id']]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$reservation) {
        $_SESSION['error'] = "Reservation not found or you don't have permission to edit it";
        header("Location: reservations.php");
        exit();
    }
}

// Helper functions
function isClassroomAvailable($classroom_id, $slot_id, $date, $exclude_id = null) {
    global $pdo;
    
    $sql = "SELECT COUNT(*) FROM reservations 
            WHERE classroom_id = ? AND slot_id = ? AND date = ? 
            AND status IN ('approved', 'pending')";
    
    $params = [$classroom_id, $slot_id, $date];
    
    if ($exclude_id) {
        $sql .= " AND id != ?";
        $params[] = $exclude_id;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchColumn() == 0;
}

function saveReservation($data) {
    global $pdo;
    
    if (isset($data['id'])) {
        // Update existing reservation
        $stmt = $pdo->prepare("UPDATE reservations SET 
            classroom_id = ?, group_id = ?, slot_id = ?, date = ?, 
            title = ?, description = ?, series_id = ?
            WHERE id = ?");
        
        $stmt->execute([
            $data['classroom_id'], $data['group_id'], $data['slot_id'], 
            $data['date'], $data['title'], $data['description'], 
            $data['series_id'] ?? null, $data['id']
        ]);
    } else {
        // Create new reservation
        $stmt = $pdo->prepare("INSERT INTO reservations 
            (classroom_id, user_id, group_id, slot_id, date, title, description, series_id, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        
        $stmt->execute([
            $data['classroom_id'], $data['user_id'], $data['group_id'], 
            $data['slot_id'], $data['date'], $data['title'], 
            $data['description'], $data['series_id'] ?? null
        ]);
    }
}

function calculateRecurringDates($start_date, $end_date, $recurrence_type) {
    $dates = [];
    $current = new DateTime($start_date);
    $end = new DateTime($end_date);
    
    while ($current <= $end) {
        $dates[] = $current->format('Y-m-d');
        
        switch ($recurrence_type) {
            case 'daily':
                $current->modify('+1 day');
                break;
            case 'weekly':
                $current->modify('+1 week');
                break;
            case 'monthly':
                $current->modify('+1 month');
                break;
        }
    }
    
    return $dates;
}

// Fetch data for dropdowns
$classrooms = $pdo->query("SELECT id, name, building FROM classrooms WHERE is_active = 1 ORDER BY building, name")->fetchAll();
$groups = $pdo->query("SELECT id, name FROM student_groups ORDER BY name")->fetchAll();
$slots = $pdo->query("SELECT id, CONCAT(start_time, ' - ', end_time) AS name FROM time_slots ORDER BY start_time")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $edit_mode ? 'Edit' : 'Create' ?> Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .recurrence-options { display: none; }
        .is-recurring .recurrence-options { display: block; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2><?= $edit_mode ? 'Edit Reservation' : 'Create New Reservation' ?></h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="post" class="mt-3">
            <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
            
            <div class="mb-3">
                <label for="title" class="form-label">Title *</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= htmlspecialchars($reservation['title']) ?>" required>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="classroom_id" class="form-label">Classroom *</label>
                    <select class="form-select" id="classroom_id" name="classroom_id" required>
                        <option value="">Select classroom</option>
                        <?php foreach ($classrooms as $classroom): ?>
                            <option value="<?= $classroom['id'] ?>" 
                                <?= $classroom['id'] == $reservation['classroom_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($classroom['building'] . ' - ' . $classroom['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="group_id" class="form-label">Student Group (optional)</label>
                    <select class="form-select" id="group_id" name="group_id">
                        <option value="">No specific group</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?= $group['id'] ?>" 
                                <?= $group['id'] == $reservation['group_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($group['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="date" class="form-label">Date *</label>
                    <input type="date" class="form-control" id="date" name="date" 
                           value="<?= htmlspecialchars($reservation['date']) ?>" required>
                </div>
                
                <div class="col-md-4">
                    <label for="slot_id" class="form-label">Time Slot *</label>
                    <select class="form-select" id="slot_id" name="slot_id" required>
                        <option value="">Select time slot</option>
                        <?php foreach ($slots as $slot): ?>
                            <option value="<?= $slot['id'] ?>" 
                                <?= $slot['id'] == $reservation['slot_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($slot['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= 
                    htmlspecialchars($reservation['description']) ?></textarea>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_recurring" name="is_recurring" 
                    <?= $reservation['is_recurring'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_recurring">This is a recurring event</label>
            </div>
            
            <div id="recurrenceOptions" class="mb-3 p-3 border rounded recurrence-options 
                <?= $reservation['is_recurring'] ? 'is-recurring' : '' ?>">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Recurrence Pattern</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="recurrence_type" id="weekly" 
                                   value="weekly" <?= $reservation['recurrence_type'] == 'weekly' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="weekly">Weekly</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="recurrence_type" id="daily" 
                                   value="daily" <?= $reservation['recurrence_type'] == 'daily' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="daily">Daily</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="recurrence_type" id="monthly" 
                                   value="monthly" <?= $reservation['recurrence_type'] == 'monthly' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="monthly">Monthly</label>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="recurrence_end" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="recurrence_end" name="recurrence_end" 
                               value="<?= htmlspecialchars($reservation['recurrence_end']) ?>">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary"><?= $edit_mode ? 'Update' : 'Create' ?> Reservation</button>
            <a href="reservations.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('is_recurring').addEventListener('change', function() {
            document.getElementById('recurrenceOptions').classList.toggle('is-recurring', this.checked);
        });
    </script>
</body>
</html>