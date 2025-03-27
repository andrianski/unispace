<?php
if (isset($_SESSION['user_id'])) {
    header("Location: ?page=dashboard");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Проверка дали потребителското име вече съществува
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->fetch()) {
        echo "Грешка: Потребителското име вече е заето.";
    } else {
        // Хеширане на паролата
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Вмъкване на нов потребител
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($stmt->execute([$username, $hashed_password])) {
            echo "Регистрацията е успешна! <a href='index.php?page=login'>Вход</a>";
        } else {
            echo "Грешка при регистрацията!";
        }
    }
}
?>

<h2>Регистрация</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Потребителско име" required>
    <input type="password" name="password" placeholder="Парола" required>
    <button type="submit">Регистрация</button>
</form>
