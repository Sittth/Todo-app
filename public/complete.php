<?php
require '../config/database.php';

if (isset($_POST['id'])) {
    $comleted = isset($_POST['completed']) ? 1 : 0;
    $stmt = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ?");
    $stmt->execute([$comleted, $_POST['id']]);
}
header('Location: index.php');
?>