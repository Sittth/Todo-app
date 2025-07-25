<?php
session_start();
require '../config/database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ошибка безопасности");
    }
    
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $errors[] = "Название задачи обязательно";
    }

    if (mb_strlen($title) > 100) {
        $errors[] = "Название не должно превышать 100 символов";
    }

    if (mb_strlen($description) > 1000) {
        $errors[] = "Описание не должно превышать 1000 символов";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO tasks (title, description, user_id) VALUES (?, ?, ?)");
            $stmt->execute([$title, $description, $_SESSION['user_id']]);
            header('Location: index.php');
            exit;
        }
        catch (PDOException $e) {
            $errors[] = "Ошибка базы данных: " . $e->getMessage();
        }
    }
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES) ?>">
    <input type="text" name="title" placeholder="Название" value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title'], ENT_QUOTES) : ''?>" maxlength="100" required>
    <textarea name="description" placeholder="Описание" maxlength="1000"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_QUOTES): '' ?></textarea>
    <button type="submit">Добавить</button>
    <?php if (!empty($errors)) : ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p> <?= htmlspecialchars($error) ?> </p>
            <?php endforeach ?>
        </div>
    <?php endif; ?>
</form>