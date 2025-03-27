<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Формулярът е изпратен
    
    // Взимане на данните от POST заявката
    $id = $_POST['id'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $role = $_POST['role'] ?? 'student';

    // Валидация на данните
    $errors = [];
    
    if (empty($username)) {
        $errors[] = 'Потребителското име е задължително поле';
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Моля, въведете валиден email адрес';
    }
    
    if (empty($errors)) {
        // Данните са валидни - обработка и запис в базата данни
        try {
            if (empty($id)) {
                // INSERT логика за нов потребител
            } else {
                // UPDATE логика за съществуващ потребител
            }
            
            // Успешен запис - пренасочване или съобщение
            $message = 'Данните са запазени успешно!';
        } catch (Exception $e) {
            $errors[] = 'Грешка при запис в базата данни: ' . $e->getMessage();
        }
    }
    
    // Показване на грешките (ако има такива)
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
        }
    } elseif (isset($message)) {
        echo '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
    }



try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Initialize variables
$user = [
    'id' => '',
    'username' => '',
    'fname' => '',
    'lname' => '',
    'email' => '',
    'mobile' => '',
    'role' => 'student'
];
$message = '';

// Check if we're editing an existing user
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        $message = '<div class="alert alert-danger">Потребителят не е намерен!</div>';
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $id = $_POST['id'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $role = $_POST['role'] ?? 'student';
    
    // Basic validation
    $errors = [];
    if (empty($username)) $errors[] = 'Потребителското име е задължително';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Невалиден email адрес';
    
    if (empty($errors)) {
        try {
            if (empty($id)) {
                // Insert new user
                $stmt = $pdo->prepare("INSERT INTO users (username, password, fname, lname, email, mobile, role) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
                // In a real application, you should hash the password!
                $temp_password = 'default_password'; // Replace with proper password handling
                $stmt->execute([$username, $temp_password, $fname, $lname, $email, $mobile, $role]);
                $message = '<div class="alert alert-success">Потребителят е създаден успешно!</div>';
            } else {
                // Update existing user
                $stmt = $pdo->prepare("UPDATE users SET 
                                      username = ?, 
                                      fname = ?, 
                                      lname = ?, 
                                      email = ?, 
                                      mobile = ?, 
                                      role = ? 
                                      WHERE id = ?");
                $stmt->execute([$username, $fname, $lname, $email, $mobile, $role, $id]);
                $message = '<div class="alert alert-success">Данните са обновени успешно!</div>';
                
                // Refresh user data
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Грешка при запис: ' . $e->getMessage() . '</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }
}
}
?>


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center"><?= empty($user['id']) ? 'Добавяне на нов потребител' : 'Редактиране на потребител' ?></h3>
                    </div>
                    <div class="card-body">
                        <?= $message ?>
                        <form method="post" action="">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id'] ?? '') ?>">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Потребителско име *</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fname" class="form-label">Име</label>
                                    <input type="text" class="form-control" id="fname" name="fname" 
                                           value="<?= htmlspecialchars($user['fname'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lname" class="form-label">Фамилия</label>
                                    <input type="text" class="form-control" id="lname" name="lname" 
                                           value="<?= htmlspecialchars($user['lname'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Телефон</label>
                                <input type="tel" class="form-control" id="mobile" name="mobile" 
                                       value="<?= htmlspecialchars($user['mobile'] ?? '') ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label">Роля *</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Администратор</option>
                                    <option value="teacher" <?= ($user['role'] ?? '') === 'teacher' ? 'selected' : '' ?>>Учител</option>
                                    <option value="student" <?= (($user['role'] ?? 'student') === 'student') ? 'selected' : '' ?>>Студент</option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Запази</button>
                                <a href="users_list.php" class="btn btn-secondary">Назад към списъка</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   