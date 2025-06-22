<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
$task = null;

if(isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $task = $stmt->fetch();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['title'])) {
    $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ?");
    $stmt->execute([
        $_POST['title'],
        $_POST['description'],
        $_POST['id']
    ]);
    header('Location: index.php');
    exit;
}
?>

<?php if ($task): ?>

<form method="POST">
    <input type="hidden" name="id" value="<?= $task['id'] ?>">
    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
    <input name="description" value="<?= htmlspecialchars($task['description']) ?>" require>
    <button type="submit">Обновить</button>
</form>

<?php endif; ?>