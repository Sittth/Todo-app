<?php
session_start();
require '../config/database.php';

if (isset($_POST['id'])) {
    $completed = isset($_POST['completed']) ? 1 : 0;
    
    try {
        $stmt = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$completed, $_POST['id'], $_SESSION['user_id']]);

        if ($stmt->rowCount() === 0) {
            $_SESSION['error'] = 'Задача не найдена';
        }
    }
    catch (PDOException $e) {
        $_SESSION['error'] = "Ошибка обновления: " . $e->getMessage();
    }
}

if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Ошибка безопасности';
    header('Location: index.php');
    exit;
}

header('Location: index.php');
?>