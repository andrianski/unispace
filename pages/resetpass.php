<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $token = $_GET['token'];

    // Проверете дали токенът съществува и е валиден
    $stmt = $pdo->prepare("SELECT id, reset_token, reset_token_expiry FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && strtotime($user['reset_token_expiry']) > time()) {
        // Хеширайте новата парола
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Актуализирайте паролата в базата данни
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->execute([$hashed_password, $token]);

        echo "Паролата беше успешно променена!";
    } else {
        echo "Невалиден или изтекъл токен.";
    }
}
?>

<form method="POST">
    <input type="password" name="new_password" placeholder="Нова парола" required>
    <button type="submit">Промени паролата</button>
</form>
