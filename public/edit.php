<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../config/database.php';
$task = null;
$errors = [];
$success = false;

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

if(isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $task = $stmt->fetch();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
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
            $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ?");
            $stmt->execute([$title, $description, $_POST['id']]);

            if ($stmt->rowCount() > 0) {
                $_SESSION['success'] = "Задача успешно обновлена";
                header('Location: index.php');
                exit;
            }
            else {
                $errors[] = "Задача не найдена или данные не изменились";
            }
        }
        catch (PDOException $e) {
            $errors[] = "Ошибка обновления" . $e->getMessage();
        }
    }
}
?>

<?php if ($task): ?>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="id" value="<?= $task['id'] ?>">
    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
    <textarea name="description" value="<?= htmlspecialchars($task['description']) ?>"></textarea>
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