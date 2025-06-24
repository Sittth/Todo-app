<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
$task = null;
$errors = [];
$success = false;

if(isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $task = $stmt->fetch();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $errors[] = "Название задачи обязательно";
    }

    if (mb_strlen($title) > 100) {
        $errors[] = "Название не должно превышать 100 символов";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ?");
        $stmt->execute([
            $title,
            $description,
            $_POST['id']
        ]);
    }
}
?>

<?php if ($task): ?>
    <?php if ($success): ?>
        <div class="success">Задача успешно обновлена!</div>
    <?php endif; ?>
<form method="POST">
    <input type="hidden" name="id" value="<?= $task['id'] ?>">
    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
    <input name="description" value="<?= htmlspecialchars($task['description']) ?>" required>
    <button type="submit">Обновить</button>

    <?php if(!empty($errors)) : ?>
        <div class="errors">
            <?php foreach($errors as $error): ?>
                <p> <?= htmlspecialchars($error) ?> </p>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</form>
<?php else: ?>
    <p>Задача не найдена!</p>
<?php endif; ?>