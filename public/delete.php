<?php
session_start();
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Ошибка безопасности";
        header('Location: index.php');
        exit;
    }

    if (isset($_POST['id'])) {
        try {
            $check = $pdo->prepare("SELECT id FROM tasks WHERE id = ?");
            $check->execute([$_POST['id']]);

            if ($check->fetch()) {
                $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $_SESSION['success'] = "Задача удалена";
            }
            else {
                $_SESSION['error'] = "Задача не найдена";
            }
        }
        catch (PDOException $e) {
            $_SESSION['error'] = "Ошибка удаления" . $e->getMessage();
        }
    }
}
header('Location: index.php');
exit;
?>