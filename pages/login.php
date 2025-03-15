<?php
if (isset($_SESSION['user_id'])) {
    header("Location: ?page=dashboard");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        header("Location: index.php?page=dashboard");
        exit;
    } else {
        echo "Грешно потребителско име или парола!";
    }
}
?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4">Логин</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Потребителско име" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Парола" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Вход</button>
        </form>
        <div class="text-center mt-3">
            <a href="?page=register" class="text-decoration-none">Регистрация</a>
        </div>
    </div>
</div>