<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]);
        header('Location: index.php');
        exit;
    }
}
?>

<form method="POST">
    <input type="text" name="title" placeholder="Название" required>
    <input name="description" placeholder="Описание"></input>
    <button type="submit">Добавить</button>
</form>