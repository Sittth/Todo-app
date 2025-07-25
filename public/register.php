<?php
session_start();
require '../config/database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm'] ?? '');

    if (empty($username)) $errors[] = "Имя пользователя обязательно";
    if (empty($password)) $errors[] = "Пароль обязателен";
    if ($password !== $confirm) $errors[] = "Пароли не совпадают";
    if (strlen($username) > 50) $errors[] = "Имя пользователя слишком длинное";

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) $errors[] = "Имя пользователя занято";

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?,?)");
        $stmt->execute([$username, $hashedPassword]);

        $_SESSION['success'] = "Регистрация успешна! Войдите в систему";
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Регистрация</title>
    </head>
    <body>
        <h1>Регистрация</h1>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p> <?= htmlspecialchars($error) ?> </p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div>
                <label>Имя пользователя:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label>Повторите пароль:</label>
                <input type="password" name="confirm" required>
            </div>
            <button type="submit">Зарегестрироваться</button>
        </form>
        <p>Уже есть аккаунт? <a href="login.php">Войдите</a></p>
    </body>
</html>