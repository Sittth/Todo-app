<?php
require '../config/database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $errors[] = "Название задачи обязательно";
    }

    if (mb_strlen($title) > 100) {
        $errors[] = "Название не должно превышать 100 символов";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]);
        header('Location: index.php');
        exit;
    }
}
?>

<form method="POST">
    <input type="text" name="title" placeholder="Название" value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title'], ENT_QUOTES) : ''?>" maxlength="100" required>
    <textarea name="description" placeholder="Описание" maxlength="1000">
        <?= isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_QUOTES): '' ?>
    </textarea>
    <button type="submit">Добавить</button>
    <?php if (!empty($errors)) : ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p> <?= htmlspecialchars($error) ?> </p>
            <?php endforeach ?>
        </div>
    <?php endif; ?>
</form>