<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Проверете дали имейлът съществува в базата данни
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Генерирайте уникален код за възстановяване
        $reset_token = bin2hex(random_bytes(50)); // Генериране на 100 символен токен
        $expiry_time = date('Y-m-d H:i:s', strtotime('+1 hour')); // Токенът ще бъде валиден 1 час

        // Актуализирайте базата данни с новия токен и валидността му
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->execute([$reset_token, $expiry_time, $email]);

        // Изпратете имейл със съответния линк
        $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $reset_token;

        // Изпращане на имейл с линк за възстановяване на паролата
        $subject = "Възстановяване на парола";
        $message = "Здравейте,\n\nИзползвайте следния линк за възстановяване на паролата си:\n" . $reset_link;
        $headers = "From: no-reply@yourwebsite.com";
        
        mail($email, $subject, $message, $headers);

        echo "Инструкции за възстановяване на паролата са изпратени на вашия имейл.";
    } else {
        echo "Няма акаунт с този имейл.";
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Въведете имейл" required>
    <button type="submit">Изпратете линк за възстановяване</button>
</form>
