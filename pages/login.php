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
        
        $error_message = "Грешно потребителско име или парола!";
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
        <div class="text-center mt-3">
            <a href="?page=forgottenpass" class="text-decoration-none">Забравена парола</a>
        </div>
    </div>
</div>
<!-- Bootstrap Modal за показване на съобщение за грешка -->
<?php if (!empty($error_message)): ?>
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Грешка</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo $error_message; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Затвори</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script>
    // Отваряне на модала при грешка
    <?php if (!empty($error_message)): ?>
        var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
        myModal.show();
    <?php endif; ?>
</script>